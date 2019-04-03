<?php

namespace application\model;

use \application\service\Service;
use \application\model\BaseModel;
use \application\model\GoodsModel;
use \application\model\BasketModel;
use \application\model\AdminModel;

class DashboardModel extends BaseModel
{
    public $today_year;
    public $last_year;

    public function __construct()
    {
        parent::__construct();
        $this->today_year = date('Y');
        $this->last_year = $this->today_year - 1;
    }



    public function startTypeDash($rigth_arr, $type = 'all', $date_start, $date_finish, $month = False, $brand = '')
    {
        $brand_name = '';
        if ($type == 'brand') {

            $rigth_arr = $this->onlyChooseBrand($rigth_arr, $brand);
            $brand_name = $key = array_search($brand, $this->brand_id_department_ar);
        }


        $department_name_arr = $this->getNameDepart($rigth_arr);
        $search_department = $this->ArraytoWhereMysql($department_name_arr, 'department_name');

        if (($type == 'all') || ($type == 'departments') || ($type == 'brands') ) {
            $accounts = $this->getAcounts($month,$search_department,$date_start,$date_finish);
            $array_out = $this->cookList($accounts, $type);

        } else {

            $accounts = $this->getAllAcounts($month, $search_department,$date_start,$date_finish);
            $array_out = $this->cookDepartment($accounts, $month,$brand_name);
        }

        return $array_out;

    }

    protected function cookDepartment($array_accaunts, $month, $brand = '')
    {
        $summ = array();

        foreach ($array_accaunts as $str) {

            if (!$month) {
                $separator = self::clearDate($str['DateTime'], 'M');
                $separator_name = 'month';
            } else {
                $separator = self::clearDate($str['DateTime'], 'M.d');
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
            if ($str['AccountName'] == "Такси") {
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

        $to_json_fot = @$this->to_json_moris($all_fot_for_json, $separator_name, $summ, true);
        $to_json_ss = @$this->to_json_moris($all_ss_for_json, $separator_name, $summ, true);
        $to_json = @$this->to_json_moris($summ, $separator_name);

        $totable_fot = @$this->to_table($all_fot_for_json, $summ, true);
        $totable_ss = @$this->to_table($all_ss_for_json, $summ, true);
        $totable_taxi = @$this->to_table($all_taxi_for_json, $summ, true);

        $delta = @round($all_summ[$this->today_year] / ($all_summ[$this->last_year] / 100) - 100, 2);

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

        $per_ss = round($all_summ_ss[$this->today_year] / ($all_summ[$this->today_year] / 100), 2);
        $per_fot = round($all_summ_fot[$this->today_year] / ($all_summ[$this->today_year] / 100), 2);
        $return_array = array('to_json' => $to_json,
            'json_fot' => $to_json_fot,
            'json_ss' => $to_json_ss,
            'to_table_fot' => $totable_fot['summ'],
            'to_table_fot_per' => $totable_fot['per'],
            'to_table_ss' => $totable_ss['summ'],
            'to_table_ss_per' => $totable_ss['per'],
            'to_table_taxi' => $totable_taxi['summ'],
            'to_table_taxi_per' => $totable_taxi['per'],
            'all_summ' => $all_summ[$this->today_year],
            'all_summ_ss' => $all_summ_ss[$this->today_year],
            'per_delta' => $delta,
            'per_ss' => $per_ss,
            'per_fot' => $per_fot,
            'fot' => $all_summ_fot[$this->today_year],
            'department' => $Department,
            'separator_name' => $separator_name,
            'style' => $style,
            'ikon' => $ikon
        );

        return $return_array;

    }

    protected function cookList($accounts, $type)
    {
        $array_prepare = $this->prepareArrayFromMysql($accounts, $type);

        $array_account = $array_prepare['arr_sum'];
        $depart_id = $array_prepare['depart_id'];

        $this->today_year;
        foreach ($array_account as $brand => $array_month) {

            $sum_department = array();
            foreach ($array_month as $month => $array_year) {
                foreach (@$array_year as $year => $sum) {
                    $sum_department[$year] = @$sum_department[$year] + $sum;
                }
            }
            if (isset($sum_department[$this->last_year])) {
                $delta = round(@$sum_department[$this->today_year] / ($sum_department[$this->last_year] / 100) - 100, 2);
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
            if (@$sum_department[$this->today_year] == 0) continue;
            if ($brand == "Другое" or $brand == "Фабрика") continue;
            $id_d = $depart_id[$brand];
            $array_out[] = array(
                'brand' => $brand,
                'vir' => @$sum_department[$this->today_year],
                'delta' => $delta,
                'last_year_vir' => @$sum_department[$this->last_year],
                'style' => $style,
                'id_d' => $id_d,
                'ikon' => $ikon
            );
        }
        return $array_out;

    }


    public static function to_json_moris($array, $separator_name, $summ = [], $absolut = false)
    {
        krsort($array,SORT_STRING);
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
            ksort($array,SORT_STRING);
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

    public function to_table($array, $summ = [], $absolut = false)
    {
        @ksort($array);
        $today_year = date('Y');

        ksort($summ);

        foreach ($summ as $month => $arr_year) {
            foreach ($arr_year as $year => $summ2) {
                if ($year == $today_year) {

                    $value_arr['per'][$month] = round($array[$month][$year] / ($summ2 / 100), 2);
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


    public static function ArraytoWhereMysql($department_arr, $key='')
    {
        $str_out = '(';

        foreach ($department_arr as $name_key => $value) {

            if ($key !='') {
                $str_out .= "'$value[$key]'" . ",";
            } else {
                $str_out .= "$value" . ",";

            }
        }
        $str_out = substr($str_out, 0, -1);
        $str_out .= ")";

        return $str_out;
    }

//todo написать отдельную функцию когда просишь одно подразделние
    public function getNameWaiters($id_waiters)
    {

        if (gettype($id_waiters) == "array") {

            $search_str = $this->ArraytoWhereMysql($id_waiters);

        } else {

            $search_str = '(' . $id_waiters . ")";
        }

        $waiter_where = "where id_waiter IN $search_str";
        $statement = self::$connection->prepare("SELECT * FROM waiters $waiter_where ");
        $statement->execute();
        $depart_arr = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $relations_where = "where relations IN $search_str";
        $statement2 = self::$connection->prepare("SELECT * FROM waiters $waiter_where ");
        $statement2->execute();
        $depart_arr2 = $statement2->fetchAll(\PDO::FETCH_ASSOC);

        if ($depart_arr2) {
            $depart_arr = array_merge($depart_arr, $depart_arr2);
        }

        return $depart_arr;
    }
    public function getNameDepart($depart)
    {

        if (gettype($depart) == "array") {

            $search_str = $this->ArraytoWhereMysql($depart, 'id_department');

        } else {

            $search_str = '(' . $depart . ")";
        }

        $department_where = "where id_department IN $search_str";
        $statement = self::$connection->prepare("SELECT * FROM departments $department_where ");
        $statement->execute();
        $depart_arr = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $relations_where = "where relations IN $search_str";
        $statement2 = self::$connection->prepare("SELECT * FROM departments $relations_where ");
        $statement2->execute();
        $depart_arr2 = $statement2->fetchAll(\PDO::FETCH_ASSOC);

        if ($depart_arr2) {
            $depart_arr = array_merge($depart_arr, $depart_arr2);
        }

        return $depart_arr;
    }
    public function getNameProduct($depart)
    {

        if (gettype($depart) == "array") {

            $search_str = $this->ArraytoWhereMysql($depart, 'product');

        } else {

            $search_str = $depart;
        }

        $department_where = "where id_dish IN $search_str";
        $statement = self::$connection->prepare("SELECT * FROM dishs $department_where ");
        $statement->execute();
        $depart_arr = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $relations_where = "where relations IN $search_str";
        $statement2 = self::$connection->prepare("SELECT * FROM dishs $relations_where ");
        $statement2->execute();
        $depart_arr2 = $statement2->fetchAll(\PDO::FETCH_ASSOC);

        if ($depart_arr2) {
            $depart_arr = array_merge($depart_arr, $depart_arr2);
        }

        return $depart_arr;
    }

    //todo решить все таки показывать только те на которые права есть или все которые к бренду относятся
    public function onlyChooseBrand($arr_right, $brand_id)
    {

        $statement = self::$connection->prepare("SELECT * FROM brand_department where id_brand = $brand_id");
        $statement->execute();
        $depart_arr = $statement->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($depart_arr as $item) {
            $arr_right[] = array('id_department' => $item['id_department']);
        }

        return $depart_arr;
    }

    public function getIdsFromBrandId($brand_id)
    {
        $arr_brand = array_keys($this->brand_id_department_ar, $brand_id);

        if (count($arr_brand) == 1) {
            $array_depart = array_search($arr_brand[0], $this->brand_department_ar);
        } else {
            return false;
        }
        return $array_depart;
    }


}