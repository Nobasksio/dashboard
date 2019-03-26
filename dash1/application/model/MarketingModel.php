<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 15/02/2019
 * Time: 15:51
 */

namespace application\model;

use \application\service\Service;
use \application\model\BaseModel;
use \application\model\DashboardModel;
use \application\model\BasketModel;

class MarketingModel extends BaseModel
{
    public $today_year;
    public function __construct() {
        parent::__construct();
        $this->today_year = date('Y');
    }

    public function getDepartSalesInfo($rigth_arr,$month,$date_start, $date_finish, $id_brand='',$id_products='')
    {
        $dashboard_model = new DashboardModel;
        $brand = false;
        $brand_name = '';

        if ($id_brand){
            $rigth_arr = $dashboard_model->onlyChooseBrand($rigth_arr,$id_brand);
            $brand = true;
            $brand_name = $key = array_search($id_brand, $this->brand_id_department_ar);
        }

        $department_name_arr = $dashboard_model->getNameDepart($rigth_arr);
        $search_department = $dashboard_model->ArraytoWhereMysql($department_name_arr, 'department_name');
        $array_sales = $this->getSalesFromMysql($month,$date_start, $date_finish,$search_department);

        return $this->toBeatifullArray($array_sales, $month,$brand_name);

    }
    public function getProductInfo($rigth_arr,$month,$id_products='')
    {
        $dashboard_model = new DashboardModel;
        $brand = false;

        if ($id_brand){
            $rigth_arr = $dashboard_model->onlyChooseBrand($rigth_arr,$id_brand);
            $brand = true;
        }

        $department_name_arr = $dashboard_model->getNameDepart($rigth_arr);
        $search_department = $dashboard_model->ArraytoWhereMysql($department_name_arr, 'department_name');
        $array_sales = $this->getSalesFromMysql($month,$search_department);

        return $this->toBeatifullArray($array_sales, $month,$brand);

    }


    public function getCheckStatistic($id_dep,$month,$date_start,$date_finish)
    {

        $dashboard_model = new DashboardModel;

        $department_name_arr = $dashboard_model->getNameDepart($id_dep);
        $search_department = $dashboard_model->ArraytoWhereMysql($department_name_arr, 'department_name');

        $array_account_all = array();


        $array_account = $this->getCheckFromMysql($search_department,$date_start,$date_finish);
        $array_account_all = array_merge($array_account_all, $array_account);

        $today_year = date('Y');
        $sum_dept = 0;
        $count_check_dept = 0;
        $count_guest_dept = 0;
        foreach ($array_account_all as $item) {

            $sum = $item['DishDiscountSumInt'];
            $guest_count = $item['GuestNum'];
            $check_count = $item['OrderNum'];

            $year = self::clearDate($item['date'], 'Y');

            if ($year==$today_year) {
                $sum_dept += $sum;
                $count_check_dept++;
                $count_guest_dept += $guest_count;

            }

        }

        return array(
            'mean_check' => @round($sum_dept/$count_check_dept,2),
            'mean_guest' => @round($sum_dept/$count_guest_dept,2)
        );

    }

    protected function toBeatifullArray($array_account, $month, $brand=false)
    {

        $today_year = date('Y');
        $last_year = $today_year - 1;

        $all_vir_bar = 0;
        $all_vir_kitch = 0;

        $group_sum = array();
        $group_count = array();
        $all_vir = 0;

        $array_dish_sum = array();
        $array_dish_count = array();

        $arr_price = $this->getMaxPrice($array_account);

        $arr_ss = $this->getMeanSs($array_account);
        $top_high_ss = $this->getTopHightSs($arr_price,$arr_ss,$array_account,0,10);

        foreach ($array_account as $str) {
            if (!$month) {
                $separator = self::clearDate($str['date'], 'M');
                $separator_name = 'month';
            } else {
                $separator = self::clearDate($str['date'], 'd');
                $separator_name = 'day';
            }
            $year = self::clearDate($str['date'], 'Y');

            if ($year==$today_year) {

                if (!$brand) {
                    $Department = $str['Department'];
                } else {
                    $Department = $brand;
                }
                $group = $str['group'];

                $ss = $str['ss'];

                $DishName = $str['DishName'];
                $id_dish = $str['id_dish'];
                $id_arr[$DishName] = $id_dish;
                $DishAmountInt = $str['DishAmountInt'];
                $price = $arr_price[$DishName]['price'];

                @$array_dish_sum[$DishName] += $price * $DishAmountInt;

                if ($price != 0) {
                    @$array_dish_count[$DishName] += $DishAmountInt;
                }
                $groupTop = $str['groupTop'];
                if ($group == null) {
                    $group = $str['group0'];;

                }
                $group_t[] = $groupTop;
                $price = $str['price'];

                $kol_sale = $str['DishAmountInt'];
                $depart_name = $str['Department'];
                $brand_id = $this->getBrandIdfromNameDepatment($depart_name);
                $type_menu_arr = $this->type_menu[$brand_id];
                $type_menu_str = @$type_menu_arr[$groupTop];


                if ($type_menu_str == 'bar') {
                    $all_vir_bar += $kol_sale * $price;
                } elseif ($type_menu_str == 'kitchen') {
                    $all_vir_kitch += $kol_sale * $price;
                }
                @$group_sum[$type_menu_str][$group] += $kol_sale * $price;
                @$group_count[$type_menu_str][$group] += $kol_sale;

            }

        }

        $array_dish_sum_out = $this->getTopX($array_dish_sum,$array_dish_count,$id_arr,'sum',10);
        $array_dish_count_out = $this->getTopX($array_dish_sum,$array_dish_count,$id_arr,'count',10);

        asort($group_sum);
        asort($group_count);

        $return_array = array('vir_bar' => $all_vir_bar,
            'vir_kitch' => $all_vir_kitch,
            'sum_ar_bar' => $group_sum['bar'],
            'sum_ar_kitch' => $group_sum['kitchen'],
            'count_ar_bar' => $group_count['bar'],
            'count_ar_kitch' => $group_count['kitchen'],
            'top10sum' => $array_dish_sum_out,
            'top10count' => $array_dish_count_out,
            'top10high_ss' => $top_high_ss,
            'department' => $Department
        );

        //print_r($return_array);
        return $return_array;
    }

    public function getMaxPrice($array_sales){
        $arr_price = array();
        foreach ($array_sales as $item) {
            $name = $item['DishName'];
            $price = $item['price'];
            $id_dish = $item['id_dish'];
            $year = self::clearDate($item['date'], 'Y');
            if ($year==$this->today_year) {
                if (isset($arr_price[$name])) {
                    if ($price > $arr_price[$name]) {
                        $arr_price[$name] = array('price'=>$price,
                            'id_dish'=>$id_dish);
                    }

                } else {
                    $arr_price[$name] = array('price'=>$price,
                        'id_dish'=>$id_dish);
                }
            }
        }

        return $arr_price;
    }

    public function getMeanSs($array_sales){
        $arr_ss = array();
        $arr_val_ss = array();
        $arr_count_ss = array();
        foreach ($array_sales as $item) {
            $name = $item['DishName'];
            $ss = $item['ss'];
            $kol = $item['DishAmountInt'];
            $year = self::clearDate($item['date'], 'Y');
            if ($year==$this->today_year) {
                $val_ss = $ss * $kol;

                if (isset($arr_val_ss[$name])) {
                    $arr_val_ss[$name] += $val_ss;
                    $arr_count_ss[$name] += $kol;

                } else {
                    $arr_val_ss[$name] = $val_ss;
                    $arr_count_ss[$name] = $kol;
                }
            }
        }
        foreach ($arr_val_ss as $name=>$val_ss){
            $arr_ss[$name] = array( 'ss'=> $val_ss/$arr_count_ss[$name],
                'val_ss'=>$val_ss);
        }

        return $arr_ss;
    }
    public function getTopHightSs($arr_price,$arr_ss,$array_account,$level_ss=0,$count_top=10){
        $ss_per = array();
        $ss_return = array();

        $all_vall_ss = $this->getValSs($arr_ss);

        foreach($arr_ss as $name=>$ss_item){
            if ($arr_price[$name]['price']!=0) {
                $per = $ss_item['ss'] / $arr_price[$name]['price'];
            } else {
                continue;
            }
            $ss_per_order[$name] = $ss_item['val_ss'];

            $ss_per[$name] = array('ss_per'=>$per,
                'ss'=>$ss_item['ss'],
                'id_dish'=>$arr_price[$name]['id_dish'],
                'val_ss'=>$ss_item['val_ss'],
                'ves_ss'=>$ss_item['val_ss']/($all_vall_ss/100)
                );
        }
        arsort($ss_per_order);
        $i=0;
        foreach($ss_per_order as $name=>$ss_item){
            if (($ss_per[$name]['ss']/$arr_price[$name]['price'])>$level_ss) {
                $ss_return[$name] = array('ss_per' => $ss_per[$name]['ss'] / $arr_price[$name]['price'],
                    'ss' => $ss_per[$name]['ss'],
                    'id_dish'=>$arr_price[$name]['id_dish'],
                    'val_ss' => $ss_per[$name]['val_ss'],
                    'ves_ss' => $ss_per[$name]['ves_ss'],
                );
                if ($i == $count_top) {
                    break;
                }

                $i++;
            } else {
                continue;
            }
        }

        return $ss_return;
    }
    public function getValSs($ss_arr){
        $all_val_ss = 0;
        foreach($ss_arr as $item){

            $all_val_ss += $item['val_ss'];
        }

        return $all_val_ss;
    }

    public function toJsonC3($array_group, $vir){
        $for_json_sum = '';
        foreach ($array_group as $group => $summ) {
            $per = round($summ / ($vir / 100), 1);
            $for_json_sum .= "['{$group} $per%',{$summ}],";
        }

        return $for_json_sum;
    }



    protected function getNamesDepartmentFromId($id_dep)
    {

        $keys = array_keys($this->department_id_ar,$id_dep);
        return $keys;
    }
    protected function getActualNameDepartmentFromId($id_dep)
    {
        $array_return = array();
        $keys = array_keys($this->department_id_ar,$id_dep);


        foreach($keys as $name_depart){
            $array_return[] = $this->department_ald_name_ar[$name_depart];
        }
        $array_return = array_unique($array_return);

        if ((count($array_return))==1){
            return $array_return[0];

        } else {
            return false;
        }

    }

    public function getBrandIdfromNameDepatment($name_depart){
        $brand_name = $this->brand_department_ar[$name_depart];
        return $this->brand_id_department_ar[$brand_name];
    }

    public function getBrandIdfromIdDepart($id_department){
        $names = array_keys($this->department_id_ar,$id_department);
        $one_name = $names[0];
        $name_brand = $this->brand_department_ar[$one_name];

        return $this->brand_id_department_ar[$name_brand];

    }
    public function getIdsDepartFromBrandName($brand_name){

        $array_depart = array_keys($this->brand_department_ar,$brand_name);

        return $array_depart;
    }
    public function getIdsFromBrandId($brand_id){

        $arr_brand = array_keys($this->brand_id_department_ar,$brand_id);
        if (count($arr_brand)==1){
            $array_depart = array_search($arr_brand[0],$this->brand_department_ar);
        } else {
            return false;
        }

        return $array_depart;
    }

    public function getTopX($array_dish_sum,$array_dish_count,$id_arr,$type_top,$x=500){

        $output = array();
        if ($type_top=='sum'){
            arsort($array_dish_sum);
            $prepare = array_slice($array_dish_sum, 0, $x);
        } else {
            arsort($array_dish_count);
            $prepare = array_slice($array_dish_count, 0, $x);
        }

        foreach ($prepare as $name => $value){
            $output[] = array('name'=>$name,
                'sum'=>$array_dish_sum[$name],
                'count'=>$array_dish_count[$name],
                'id_product'=>$id_arr[$name]
                );
        }

        return $output;
    }
}