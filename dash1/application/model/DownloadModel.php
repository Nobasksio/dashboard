<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 29/03/2019
 * Time: 10:27
 */

namespace application\model;

use \application\service\Service;
use \application\model\BaseModel;
use \application\model\AdminModel;
use \Curl\Curl;


class DownloadModel extends BaseModel
{
    private $mylogin='ElchenkovA';
    private $PASS = '123456';
    private $hash_password;
    private $key_hash;
    private $curl_object;
    private $date_start;
    private $date_finish;

    private $departments_arr;
    private $dishs_arr;
    private $categorys_arr;

    protected $session;

    public function __construct() {

        $this->session = Service::session();

        $this->curl_object = new Curl();
        $old_hash = $this->session->get("key_hash", $this->key_hash);
        $this->curl_object->get('http://195.206.46.94:9080/resto/api/logout?key='.$old_hash);

        // $this->hash_password = sha1($this->PASS);

        // $this->curl_object->get('http://195.206.46.94:9080/resto/api/auth?login='.$this->mylogin.'&pass='.$this->hash_password);
        // $this->key_hash = $this->curl_object->response;

        // $this->session->set("key_hash", $this->key_hash);

    }

    public function dowloadSaleIiko($date_start='',$date_finish=''){

        $this->setDates($date_start,$date_finish);

        $arr_interval = self::arrIntervalDate($this->date_start,$this->date_finish);

        foreach($arr_interval as $item_date){

            $from = new \DateTime($item_date);
            $date_s = $from->format('Y-m-d');
            $date_start_str = $from->format('Y-m-d').'T00:00:00.000';
            $from->modify( '+1 day' );
            $date_f = $from->format('Y-m-d');
            $date_finish_str =  $from->format('Y-m-d').'T00:00:00.000';

            $response = $this->requestSale($date_start_str,$date_finish_str);

            $this->saveSale($response,$date_s,$date_f);
            print $this->date_start;
        }

        $this->curl_object->get('http://195.206.46.94:9080/resto/api/logout?key='.$this->key_hash);
        return 1;

    }
    protected function requestSale($date_start,$date_finish){
        $curl = new Curl();

        $request = array(
            "reportType" => "SALES",
            "groupByRowFields" => array(
                "DishName",
                "Department",
                "DishGroup.SecondParent",
                "DishGroup.ThirdParent",
                "DishGroup",
                "OpenDate.Typed"
            ),
            "groupByColFields" => array(),
            "aggregateFields" => array(
                "ProductCostBase.OneItem",
                "DishDiscountSumInt.averagePrice",
                "DishAmountInt"
            ),
            "filters" => array(
                "OpenDate.Typed" => array(
                    "filterType" => "DateRange",
                    "periodType" => "CUSTOM",
                    "from" => "$date_start",
                    "to" => "$date_finish"
                ),
                "DeletedWithWriteoff" => array(
                    "filterType" => "ExcludeValues",
                    "values" => array("DELETED_WITH_WRITEOFF","DELETED_WITHOUT_WRITEOFF")
                ),
                "OrderDeleted" => array(
                    "filterType" => "IncludeValues",
                    "values" => ["NOT_DELETED"]
                )
            )
        );
        $req = json_encode($request);
        $curl->setHeader('Content-Type', 'application/json');
        $curl->post('http://195.206.46.94:9080/resto/api/v2/reports/olap?key='.$this->key_hash."&reportType=TRANSACTIONS", $req);

        return $curl->response;
    }

    protected function setDates($date_start,$date_finish){
        if ($date_start == '' or !isset($date_start)) {
            $date_obj = new \DateTime('today');
            $this->date_start = $date_obj->format('Y-m-d');
        } else {
            $this->date_start = BaseModel::normaDate($date_start);
        }
        if ($date_finish == '' or !isset($date_start)) {
             $date_obj = new \DateTime('tomorrow');
             $this->date_finish = $date_obj->format('Y-m-d');
        } else {
            $this->date_finish = BaseModel::normaDate($date_finish);
        }
    }

    protected function saveSale($response,$date_start,$date_finish){

        $statement2 = self::$connection->prepare("CREATE TABLE IF NOT EXISTS sales2 (
              sale_id INT AUTO_INCREMENT PRIMARY KEY,
              department_id INT NOT NULL,
              kol FLOAT(20) NOT NULL,
              price FLOAT(20) NOT NULL,
              ss fLOAT(20) NOT NULL,
              dish_id INT NOT NULL,
              date DATETIME,
              date_update timestamp on update CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
          AUTO_INCREMENT=1;
        ");
        $statement2->execute();

        $statement = self::$connection->prepare(
            "DELETE FROM 
                          sales2
                          WHERE DATE(`date`) BETWEEN '$date_start' AND '$date_finish'");

        //$statement->bindValue(':date_start', $date_start);
        //$statement->bindValue(':date_finish', $date_finish);
        $statement->execute();

        $admin_model = new AdminModel();
        $this->departments_arr = $admin_model->getAllDepartments();
        $this->dishs_arr = $admin_model->getAllDishs();
        $this->categorys_arr = $admin_model->getAllCategory();


        $data = json_decode(json_encode($response->data), True);
        foreach($data as $item_sale) {

            $Department_name = $item_sale['Department'];
            $department_id = $this->search_id($this->departments_arr,$Department_name,'department','id_department');

            $category = $item_sale['DishGroup.ThirdParent'];
            $DishGroup = $item_sale['DishGroup'];
            if ($category=='' or !isset($category)){
                $category = $DishGroup;
            }

            $category_id = $this->search_id($this->categorys_arr,$category,'category','id_category');
            $Group2 = $item_sale['DishGroup.SecondParent'];

            $pos = strpos(strtolower($Group2), 'бар');
            $pos2 = strpos($Group2, 'Бар');
            if (($pos === false) and ($pos2 === false)) {
                $type_k = 0;
            } else {
                $type_k = 1;
            }

            $categorys_arr = $this->categorys_arr;
            $key_value = array_search($category, array_column($categorys_arr, 'category_name'));
            if ($type_k != $categorys_arr[$key_value]['type_k']){
                AdminModel::UpdateCategory($category_id,'type_k',$type_k);
            }

            $dish_name = $item_sale['DishName'];
            $dish_id = $this->search_id($this->dishs_arr,$dish_name,'dish','id_dish');

            $dish_arr = $this->dishs_arr;
            $key_value = array_search($dish_name, array_column($dish_arr, 'dish_name'));
            if ($category_id != $dish_arr[$key_value]['category_id']){
                AdminModel::UpdateDish($dish_id,'category_id',$category_id);
            }

            $date = $item_sale['OpenDate.Typed']." 00:00:00";
            $ss = $item_sale['ProductCostBase.OneItem'];
            $price = (float)$item_sale['DishDiscountSumInt.averagePrice'];
            $count = $item_sale['DishAmountInt'];

            print"$Department_name $dish_name $price<br>";
            $statement = self::$connection->prepare(
                "INSERT INTO sales2 (`department_id`,`kol`,`price`,`ss`,`dish_id`,`date`) VALUE($department_id,$count,$price,$ss,$dish_id,'$date')");

            $statement->execute();

        }
    }

    protected function search_id($arr, $search_value, $search_entity,$name_id){

        $key_value = array_search($search_value, array_column($arr, $search_entity.'_name'));

        if (!$key_value and $key_value!=0){

            $name_method = 'add'.ucfirst($search_entity);
            $value_id = AdminModel::{$name_method}($search_value);
            $arr[] = array($search_entity.'_name' =>$search_value, "$name_id"=>$value_id);
        } else {
            $value_id = $arr[$key_value]["$name_id"];

        }
        $this->{$search_entity.'s_arr'} = $arr;

        return $value_id;
    }

    public function test_iiko(){
        $array_depart = array(
            '8091'=>"антрекот мк",
            '8090'=>"антрекот км",
            '8101'=>"антрекот сильвер",
            '8108'=>"антрекот ангарск",
            '8087'=>"доставка култукская",
            '8089'=>"доставка ново ленино",
            '8088'=>"доставка сигма",
            '8103'=>"доставка солнечный",
            '8080'=>"СБ Партизанская",
            '8115'=>"СБ Карла маркса",
            '8083'=>"СБ Советская",
            '8084'=>"СБ Юбилейный",
            '8117'=>"СБ 130",
            '8102' => "СМ фортуна",
            '8105' => "СМ комсомолл",
            '8106' =>"СМ снегирь",
            '8107' =>"СМ сильвер",
            '8109' =>"СМ европарк",
            '8110' => "СМ ангара",
            '8111' => "СМ абсолют",
            '8113' => "СМ лермонтов",
            '8114' => "СМ модный квартал",
            '8116' => "СМ фабрика",
            '8118' => "СМ цп байкальская",
            '8119' => "СМ цп дзержинского",
            '8086' => "ФУДКОРТ МК",
            '8100' => "ФУДКОРТ НОВЫЙ",
            '8112' => "ФО МИ",
            '9080' => "Chain"
        );


        $statement = self::$connection->prepare("SELECT DISTINCT * FROM user_iiko ");
        $statement->execute();

        $all_users= $statement->fetchAll(\PDO::FETCH_ASSOC);

        $curl =  new Curl();
        $curl->setBasicAuthentication('admin', 'Asf358355');
        $array_all = array(
        );

        foreach ($array_depart as $port => $name_depart) {

            $array_val = array(
                0 => array(),
                1 => array(),
                2 => array(),
                3 => array(),
                4 => array(),
                5 => array(),
                6 => array(),
                7 => array(),
                8 => array(),
                9 => array()
            );
            $curl->get('http://iiko.rest38.ru:'.$port.'/resto/service/monitoring/connections.jsp');

            $result = $curl->response;

            $dom = new \DOMDocument;
            $dom->loadHTML($result);

            $table = $dom->getElementsByTagName('td');
            $i = 0;

            foreach ($table as $item) {
                $i++;
                $value = preg_replace('%[^A-Za-zА-Яа-я0-9,-:]%', '', $item->nodeValue);


                if ($i < 8) {
                    $array_val[$i][] = $value;
                } else {
                    $i2 = $i % 8;
                    $array_val[$i2][] = $value;
                }


            }
            $array_all[$name_depart] = $array_val;

        }
        $need_type = array('iikoChainOperationsChain', 'iikoRMSiikoOffice');


        foreach($array_all as $depart => $item_department){
            foreach($item_department[7] as $key => $module){

                $module = preg_replace('%[^A-Za-zА-Яа-я0-9]%', '', $module);
                if (!in_array($module,$need_type)){
                    foreach ($array_all[$depart] as $Key_delet=>$some_array){
                        unset($array_all[$depart][$Key_delet][$key]);
                    }
                } else {

                    $user_name = preg_replace('%[^A-Za-zА-Яа-я0-9]%', '', $array_all[$depart][4][$key]);
                    foreach ($all_users as $user) {
                        if ($user_name == $user['login']){
                            $array_all[$depart][8][$key] = $user['name'];
                        }
                    }
                }

            }
        }
        $new_array = array();
        foreach($array_all as $depart => $item_department){
            foreach ($item_department as $type=>$array_str){
                foreach ($array_str as $key=>$some_value){

                    $new_str = substr($item_department[0][$key],0,16);
                    $arr_time = explode('T',$new_str);
                    $str_time = $arr_time[0].' '.$arr_time[1];
                    $new_array[$depart][] = array('last_activ'=>$str_time,
                        'ip'=>$item_department[1][$key],
                        'comp_name'=>$item_department[2][$key],
                        'term_name'=>$item_department[3][$key],
                        'login'=>$item_department[4][$key],
                        'modul'=>$item_department[7][$key],
                        'user_name'=>$item_department[8][$key]
                    );
                }
                break;
            }

        }



    return $new_array;




    }

}