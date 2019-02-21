<?php

namespace application\model;

use \application\service\Service;
use \application\model\BaseModel;
use \application\model\GoodsModel;
use \application\model\BasketModel;
use \application\model\AdminModel;

class DashboardModel extends BaseModel
{

    public $brand_department_ar = array(
        'Антрекот Ангарск' => 'Антрекот',
        'Антрекот КМ' => 'Антрекот',
        'Антрекот МК' => 'Антрекот',
        'Антрекот СМ' => 'Антрекот',
        'Доставка Култукская (не раб)' => 'Sushi Studio Доставка',
        'Доставка Култукская Иоффе' => 'Sushi Studio Доставка',
        'Доставка Ново-Ленино (не раб)' => 'Sushi Studio Доставка',
        'Доставка Ново-Ленино Никулина' => 'Sushi Studio Доставка',
        'Доставка Сигма (не раб)' => 'Sushi Studio Доставка',
        'Доставка Сигма Никулина' => 'Sushi Studio Доставка',
        'Доставка Солнечный (не раб)' => 'Sushi Studio Доставка',
        'Доставка Солнечный Иоффе' => 'Sushi Studio Доставка',
        'Рассольник' => 'Другое',
        'Контакт-центр' => 'Другое',
        'СБ 130квартал РНГ' => 'Sushi Studio Бары',
        'СБ Карла Маркса (не раб)' => 'Sushi Studio Бары',
        'СБ Карла Маркса РНГ' => 'Sushi Studio Бары',
        'СБ Партизанская (не раб)' => 'Sushi Studio Бары',
        'СБ Партизанская РНГ' => 'Sushi Studio Бары',
        'СБ Советская' => 'Sushi Studio Бары',
        'СБ Юбилейный' => 'Sushi Studio Бары',
        'СМ Снегирь' => 'СушиТочка',
        'СМ Фортуна' => 'СушиТочка',
        'СТ Фортуна' => 'СушиТочка',
        'СТ Абсолют' => 'СушиТочка',
        'СТ Ангара' => 'СушиТочка',
        'СТ Европарк' => 'СушиТочка',
        'СТ Комсомолл' => 'СушиТочка',
        'СТ Лермонтов' => 'СушиТочка',
        'СТ Модный квартал' => 'СушиТочка',
        'СТ Сильвермолл' => 'СушиТочка',
        'СТ ЦП Байкальская' => 'СушиТочка',
        'ТОТО МК' => 'Другое',
        'Фабрика Багаутдинова' => 'Другое',
        'Фабрика ИП Димаков' => 'Другое',
        'Фо Ми' => 'Phome',
        'Фуд Корт Лермонтов (не раб)' => 'Sushi Studio Фудкорты',
        'Фуд Корт МК' => 'Sushi Studio Фудкорты',
        'Фуд Корт Новый' => 'Sushi Studio Фудкорты',
        'Центральный Офис' => 'Другое',
    );
    public $brand_id_department_ar = array(
        'Антрекот' => 4,
        'Sushi Studio Доставка' => 1,
        'Другое' => 0,
        'Sushi Studio Бары' => 2,
        'СушиТочка' => 5,
        'Sushi Studio Фудкорты' => 3,
        'Phome' => 6
    );

    public $department_ald_name_ar = array(
        'Антрекот Ангарск' => 'Антрекот Ангарск',
        'Антрекот КМ' => 'Антрекот КМ',
        'Антрекот МК' => 'Антрекот МК',
        'Антрекот СМ' => 'Антрекот СМ',
        'Доставка Култукская (не раб)' => 'SS Доставка Култукская',
        'Доставка Култукская Иоффе' => 'SS Доставка Култукская',
        'Доставка Ново-Ленино (не раб)' => 'SS Доставка Ново-Ленино',
        'Доставка Ново-Ленино Никулина' => 'SS Доставка Ново-Ленино',
        'Доставка Сигма (не раб)' => 'SS Доставка Сигма',
        'Доставка Сигма Никулина' => 'SS Доставка Сигма',
        'Доставка Солнечный (не раб)' => 'SS Доставка Солнечный',
        'Доставка Солнечный Иоффе' => 'SS Доставка Солнечный',
        'Рассольник' => 'Рассольник',
        'Контакт-центр' => 'Контакт-центр',
        'СБ 130квартал РНГ' => 'SS СБ 130квартал РНГ',
        'СБ Карла Маркса (не раб)' => 'SS СБ Карла Маркса',
        'СБ Карла Маркса РНГ' => 'SS СБ Карла Маркса',
        'СБ Партизанская (не раб)' => 'SS СБ Партизанская',
        'СБ Партизанская РНГ' => 'SS СБ Партизанская',
        'СБ Советская' => 'SS СБ Советская',
        'СБ Юбилейный' => 'SS СБ Юбилейный',
        'Фуд Корт Лермонтов (не раб)' => 'SS Фуд Корт Лермонтов',
        'Фуд Корт МК' => 'SS Фуд Корт МК',
        'Фуд Корт Новый' => 'SS Фуд Корт Новый',
        'СМ Снегирь' => 'СТ Снегирь',
        'СМ Фортуна' => 'СТ Фортуна',
        'СТ Фортуна' => 'СТ Фортуна',
        'СТ Абсолют' => 'СТ Абсолют',
        'СТ Ангара' => 'СТ Ангара',
        'СТ Европарк' => 'СТ Европарк',
        'СТ Комсомолл' => 'СТ Комсомолл',
        'СТ Лермонтов' => 'СТ Лермонтов',
        'СТ Модный квартал' => 'СТ Модный квартал',
        'СТ Сильвермолл' => 'СТ Сильвермолл',
        'СТ ЦП Байкальская' => 'СТ ЦП Байкальская',
        'ТОТО МК' => 'ТОТО МК',
        'Фабрика Багаутдинова' => 'Фабрика',
        'Фабрика ИП Димаков' => 'Фабрика',
        'Фо Ми' => 'Phome',

        'Центральный Офис' => 'Центральный Офис',
    );

    public $department_id_ar = array(
        'Антрекот Ангарск' => 1,
        'Антрекот КМ' => 2,
        'Антрекот МК' => 3,
        'Антрекот СМ' => 4,
        'Доставка Култукская (не раб)' => 5,
        'Доставка Култукская Иоффе' => 5,
        'Доставка Ново-Ленино (не раб)' => 6,
        'Доставка Ново-Ленино Никулина' => 6,
        'Доставка Сигма (не раб)' => 7,
        'Доставка Сигма Никулина' => 7,
        'Доставка Солнечный (не раб)' => 8,
        'Доставка Солнечный Иоффе' => 8,
        'Рассольник' => 0,
        'Контакт-центр' => 0,
        'СБ 130квартал РНГ' => 9,
        'СБ Карла Маркса (не раб)' => 10,
        'СБ Карла Маркса РНГ' => 10,
        'СБ Партизанская (не раб)' => 11,
        'СБ Партизанская РНГ' => 11,
        'СБ Советская' => 12,
        'СБ Юбилейный' => 13,
        'Фуд Корт Лермонтов (не раб)' => 0,
        'Фуд Корт МК' => 14,
        'Фуд Корт Новый' => 15,
        'СМ Снегирь' => 16,
        'СТ Фортуна' => 16,
        'СМ Фортуна' => 17,
        'СТ Абсолют' => 18,
        'СТ Ангара' => 19,
        'СТ Европарк' => 20,
        'СТ Комсомолл' => 21,
        'СТ Лермонтов' => 22,
        'СТ Модный квартал' => 23,
        'СТ Сильвермолл' => 24,
        'СТ ЦП Байкальская' => 25,
        'ТОТО МК' => 0,
        'Фабрика Багаутдинова' => 0,
        'Фабрика ИП Димаков' => 0,
        'Фо Ми' => 26,
        'Центральный Офис' => 0,
    );

    public function startTypeDash($rigth_arr, $type='all',$month = False)
    {

        $department_name_arr = $this->getNameDepart($rigth_arr);

        $str_department = $this->ArraytoWhereMysql($department_name_arr,'department_name');


        $accounts = $this->getAcounts($str_department,$month);




        $array_prepare = $this->prepareArrayFromMysql($accounts, $type);
        $array_account = $array_prepare['arr_sum'];
        $depart_id = $array_prepare['depart_id'];
        $today_year = date('Y');
        $last_year = $today_year - 1;


        foreach ($array_account as $brand => $array_month) {

            $sum_department = array();
            foreach ($array_month as $month => $array_year) {
                foreach (@$array_year as $year => $sum) {
                    $sum_department[$year] = @$sum_department[$year] + $sum;
                }
            }
            if (isset($sum_department[$last_year])) {
                $delta = round(@$sum_department[$today_year] / ($sum_department[$last_year] / 100) - 100, 2);
            } else {
                $delta = 0;
            }

            If ($delta > 0) {
                $style = 'text-success';
                $ikon = 'fa-arrow-up';
            } elseif ($delta < 0) {
                $style = 'text-danger';
                $ikon = 'fa-arrow-down';
            } else {
                $style = 'text-muted';
                $ikon = '';
            }
            if (@$sum_department[$today_year] == 0) continue;
            if ($brand == "Другое" or $brand == "Фабрика") continue;
            $id_d = $depart_id[$brand];
            $array_out[] = array(
                'brand' => $brand,
                'vir' => @$sum_department[$today_year],
                'delta' => $delta,
                'last_year_vir' => @$sum_department[$last_year],
                'style' => $style,
                'id_d' => $id_d,
                'ikon' => $ikon
            );
        }


        return $array_out;

    }

    protected function cookDepartment($array_accaunts)
    {

    }

    protected function cookDepartments($array_accaunts)
    {

    }

    protected function cookBrand($array_accaunts)
    {

    }
    protected function cookBrands($array_accaunts)
    {

    }

    public function startDash($rigth_arr, $month = false)
    {

        if ($month) {
            $array_account = $this->getAcountMonthFromMysql();

        } else {
            $array_account = $this->getAcountYearFromMysql();
        }

        return $this->toBeatifullArray($array_account, $month);

    }

    protected function toBeatifullArray($array_account, $month, $brand=false)
    {

        $today_year = date('Y');
        $last_year = $today_year - 1;


        foreach ($array_account as $str) {
            if (!$month) {
                $separator = self::clearDate($str['DateTime'], 'M');
                $separator_name = 'month';
            } else {
                $separator = self::clearDate($str['DateTime'], 'd');
                $separator_name = 'day';
            }
            $year = self::clearDate($str['DateTime'], 'Y');

            if (!$brand) {
                $Department = $str['Department'];
            } else {
                $Department = $brand;
            }
            $group = $str['group'];
            $sum_incom = $str['Sum.Incoming'];
            $sum_out = $str['Sum.Outgoing'];

            //print "$Department {$str['AccountName']} $group $sum_incom $sum_out {$str['DateTime']}<br>";
            if ($str['AccountName'] == "Такси"){
                $all_taxi_for_json[$separator][$year] = @$all_taxi_for_json[$separator][$year] - $sum_out + $sum_incom;
            }
            if ($group == 1) {
                $summ[$separator][$year] = @$summ[$separator][$year] + $sum_out - $sum_incom;
                $all_summ[$year] = @$all_summ[$year] + $sum_out - $sum_incom;
            } elseif ($group == 2) {

                $all_summ_ss[$year] = @$all_summ_ss[$year] - $sum_out + $sum_incom;
                $all_ss_for_json[$separator][$year] = @$all_ss_for_json[$separator][$year] - $sum_out + $sum_incom;
            } elseif ($group == 3) {
                $all_summ_fot[$year] = @$all_summ_fot[$year] - $sum_out + $sum_incom;
                $all_fot_for_json[$separator][$year] = @$all_fot_for_json[$separator][$year] - $sum_out + $sum_incom;
            }


        }
        $to_json = array();

        ksort($summ);

        $to_json_fot = @$this->to_json_moris($all_fot_for_json, $separator_name, $summ, true);
        $to_json_ss = @$this->to_json_moris($all_ss_for_json, $separator_name, $summ, true);
        $to_json = @$this->to_json_moris($summ, $separator_name);

        $totable_fot = @$this->to_table($all_fot_for_json,$summ,true);
        //print_r($totable_fot);

        $totable_ss = @$this->to_table($all_ss_for_json,$summ,true);


        $totable_taxi = @$this->to_table($all_taxi_for_json,$summ,true);


        $delta = @round($all_summ[$today_year] / ($all_summ[$last_year] / 100) - 100, 2);

        If ($delta > 0) {
            $style = 'text-success';
            $ikon = 'fa-arrow-up';
        } elseif ($delta < 0) {
            $style = 'text-danger';
            $ikon = 'fa-arrow-down';
        } else {
            $style = 'text-muted';
            $ikon = '';
        }
        $per_ss = round($all_summ_ss[$today_year] / ($all_summ[$today_year] / 100), 2);
        $per_fot = round($all_summ_fot[$today_year] / ($all_summ[$today_year] / 100), 2);
        $return_array = array('to_json' => $to_json,
            'json_fot' => $to_json_fot,
            'json_ss' => $to_json_ss,
            'to_table_fot' => $totable_fot['summ'],
            'to_table_fot_per' => $totable_fot['per'] ,
            'to_table_ss' => $totable_ss['summ'],
            'to_table_ss_per' => $totable_ss['per'],
            'to_table_taxi' => $totable_taxi['summ'],
            'to_table_taxi_per' => $totable_taxi['per'],
            'all_summ' => $all_summ[$today_year],
            'all_summ_ss' => $all_summ_ss[$today_year],
            'per_delta' => $delta,
            'per_ss' => $per_ss,
            'per_fot' => $per_fot,
            'fot' => $all_summ_fot[$today_year],
            'department' => $Department,
            'separator_name' => $separator_name,
            'style' => $style,
            'ikon' => $ikon
        );

        //print_r($return_array);
        return $return_array;
    }

    protected function to_json_moris($array, $separator_name, $summ = [], $absolut = false)
    {
        @ksort($array);

        if ($absolut) {
            ksort($summ);

            foreach ($summ as $month => $arr_year) {

                $to_json1 = array();
                $to_json1[] = array($separator_name => $month);

                foreach ($arr_year as $year => $summ2) {
                    $to_json1[] = array("$year" => round($array[$month][$year] / ($summ2 / 100), 2));

                }

                $to_json[] = $to_json1;
            }
        } else {
            foreach ($array as $month => $arr_year) {

                $to_json1 = array();
                $to_json1[] = array($separator_name => $month);

                foreach ($arr_year as $year => $summ) {

                    $to_json1[] = array("$year" => $summ);

                }
                $to_json[] = $to_json1;
            }
        }


        return $to_json;
    }

    protected function to_table($array, $summ = [], $absolut = false)
    {
        @ksort($array);
        $today_year = date('Y');

        ksort($summ);

        foreach ($summ as $month => $arr_year) {
            foreach ($arr_year as $year => $summ2) {
                if ($year == $today_year) {

                    $value_arr['per'][$month] = round($array[$month][$year]/($summ2/100),2);
                    if (isset($array[$month][$year])) {
                        $value_arr['summ'][$month] = $array[$month][$year];
                    } else {
                        $value_arr['summ'][$month] = 0;
                    }
                }


            }

        }

        return $value_arr;
    }

    public function getInfoDepartments($id_d, $month = false)
    {

        $array_account = array();
        $array_account_all = array();

        $department_name_arr = $this->getNameDepart($id_d);

        $str_department = $this->ArraytoWhereMysql($department_name_arr,'department_name');

        $account = $this->getAcountYearFromMysqlTest($str_department,$month);

        return $this->toBeatifullArray($account, $month);
    }

    public function getInfoBrand($id_b,$user, $month = false){
        $array_arr_id_brand = $this->brand_id_department_ar;
        $array_arr_id_departments = $this->brand_department_ar;
        $key = array_keys($array_arr_id_brand, $id_b);
        $keys = array_keys($array_arr_id_departments, $key[0]);

        $array_account = array();
        $array_account_all = array();

        $admin_model = new AdminModel();
        $department_right = $admin_model->getRightOnDepartment($user['id']);

        $str_department = $this->ArraytoWhereMysql($department_right,'id_department');



            $array_account = $this->getAcountYearFromMysqlTest($str_department, $month);
            $array_account_all = array_merge($array_account_all, $array_account);


        return $this->toBeatifullArray($array_account_all, $month,$key[0]);
    }


    protected function getAcounts($Department = '',$month)
    {

        if ($Department != '') {
            $where = "and d.Department IN $Department";
        } else {
            $where = '';
        }
        if ($month) {
            $table = 'dashboard_this_month';

        } else {
            $table = 'dashboard_this_years';

        }
        print "$where $Department";
        $statement = self::$connection->prepare(
            "SELECT * FROM 
                          $table d, 
                          dashboard_type_account t 
                        where t.group=1 
                          and d.AccountName = t.name 
                          $where
                          order by Department ");
        if ($Department != '') {
            //$statement->bindValue(':Department', $Department);
        }
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function getAcountMonthFromMysql($Department = '',$month)
    {
        //print($Department);
        if ($Department != '') {
            $where = "WHERE Department='$Department'";

            //print$where;
        } else {
            $where = '';
        }
        if ($month) {
            $table = 'dashboard_this_month';

        } else {
            $table = 'dashboard_this_years';

        }
        $department_where = "where dep.id_department IN $Department";

        print "$table $where ";
        $statement = self::$connection->prepare(
            "SELECT * FROM 
                          $table $where  ");
        if ($Department != '') {
            //$statement->bindValue(':Department', $Department);
        }
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }


    // todo надо переписать на этот медот все запросы

    protected function getAcountYearFromMysqlTest($Department = '', $month = false)
    {
        $department_where='';
        if ($Department!=''){

            $department_where = "and d.Department IN $Department";

        }
        if ($month) {
            $table = 'dashboard_this_month';

        } else {
            $table = 'dashboard_this_years';

        }
        $statement = self::$connection->prepare(
            "SELECT * FROM 
                          dashboard_this_years d, 
                          dashboard_type_account t 
                        where t.group=1 
                          and d.AccountName = t.name 
                          $where
                          order by Department ");

        $statement->execute();
        //$this->returnArrayTest($statement->fetchAll(\PDO::FETCH_ASSOC));
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function returnArrayTest($array)
    {
        print"тест:<br>";
        print_r($array);
        print"конец теста<br>";
    }





    public function prepareArrayFromMysql($array_account, $type)
    {

        if ($type == 'brands') {

            $group_arr = $this->brand_department_ar;
            $group_id = $this->brand_id_department_ar;


        } elseif ($type == 'departments') {
            $group_arr = $this->department_ald_name_ar;
            $group_id = $this->department_id_ar;
        }


        foreach ($array_account as $str) {

            $Department = $str['Department'];
            $group = $group_arr["$Department"];

            $month = self::clearDate($str['DateTime'], 'M');
            $year = self::clearDate($str['DateTime'], 'Y');

            if ($type == 'brands') {
                $depart_id[$group] = $group_id[$group];
            } else {
                $depart_id[$group] = $group_id[$Department];
            }

            $sum_incom = $str['Sum.Incoming'];
            $sum_out = $str['Sum.Outgoing'];
            $summ[$group][$month][$year] = @$summ[$group][$month][$year] + $sum_out - $sum_incom;
            $all_summ[$group][$year] = @$all_summ[$group][$year] + $sum_out - $sum_incom;


        }
        ksort($summ);
        ksort($depart_id);
        $array_id = array('arr_sum' => $summ,
            'depart_id' => $depart_id);
        return $array_id;
    }



    protected function ArraytoWhereMysql($department_arr,$key){
        $str_out = '(';
        foreach($department_arr as $name_key=>$value){
            $str_out .= "'$value[$key]'".",";
        }
        $str_out = substr($str_out, 0, -1);
        $str_out .= ")";
        //print "$str_out";
        return $str_out;
    }
//todo написать отдельную функцию когда просишь одно подразделние
    protected function getNameDepart($depart){
        $str_out = '(';
        if (gettype($depart)=="array") {
            foreach ($depart as $item) {
                $str_out .= $item['id_department'] . ",";
            }
        } else {
            $str_out .= $depart. ",";
        }

        $str_out = substr($str_out, 0, -1);
        $str_out .= ")";

        $department_where = "where id_department IN $str_out";
        $statement = self::$connection->prepare(
            "SELECT * FROM 
                          departments
                       $department_where ");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }







}