<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20/03/2019
 * Time: 13:55
 */

namespace application\model;

use \application\model\BaseModel;
use \application\model\DashboardModel;

class WaitersModel extends BaseModel
{
    function getWaitersInfo($right,$date_start, $date_finish,$waiters_id=''){

        $brand_name = '';
        $dashboard_model = new DashboardModel;

            $department_name_arr = $dashboard_model->getNameDepart($right);
            $search_department = $dashboard_model->ArraytoWhereMysql($department_name_arr, 'department_name');
            $array_sales = $this->getCheckFromMysql($search_department,$date_start, $date_finish);


        return $this->toBeatifullArray($array_sales, true, $brand_name);


    }
    function getOneWaiterInfo($right,$id_waiter,$date_start, $date_finish){

        $brand_name = '';
        $dashboard_model = new DashboardModel;


            $department_name_arr = $dashboard_model->getNameDepart($right);
            $search_department = $dashboard_model->ArraytoWhereMysql($department_name_arr, 'department_name');

            $search_waiter = $dashboard_model->getNameWaiters($id_waiter);
            $search_waiter = $dashboard_model->ArraytoWhereMysql($search_waiter,'waiter_name');
            $array_sales = $this->getCheckFromMysql($search_department,$date_start, $date_finish,$search_waiter);



        return $this->toBeatifullArrayOne($array_sales, true,$date_start, $date_finish,$brand_name);


    }
    protected function toBeatifullArray($array_account, $month, $brand=false)
    {

        $today_year = date('Y');

        $all_vir_bar = 0;
        $all_vir_kitch = 0;

        $waiter_arr = array();

        $all_vir = 0;

        $array_dish_sum = array();
        $array_dish_count = array();

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

                $Waiter = $str['Waiter'];
                $Id_waiter= $str['id_waiter'];
                $GuestNum = $str['GuestNum'];
                $DishDiscountSumInt = $str['DishDiscountSumInt'];


                @$waiter_arr[$Waiter]['num'] = @$waiter_arr[$Waiter]['num'] + 1;
                @$waiter_arr[$Waiter]['num_guest'] = @$waiter_arr[$Waiter]['num_guest'] + $GuestNum;
                @$waiter_arr[$Waiter]['sum'] = @$waiter_arr[$Waiter]['sum'] + $DishDiscountSumInt;
                @$waiter_arr[$Waiter]['id'] = $Id_waiter;

            }

        }

        $return_array = array('waiter_arr' => $waiter_arr,
            'department' => $Department
        );

        //print_r($return_array);
        return $return_array;
    }
    protected function toBeatifullArrayOne($array_account, $month, $date_start, $date_finish, $brand=false)
    {

        $today_year = date('Y');

        $waiter_arr = array();

        $summ = 0;
        $all_summ = 0;
        $num_check = 0;
        $num_guest = 0;


        foreach ($array_account as $str) {
            if (!$month) {
                $separator = self::clearDate($str['date'], 'M');
                $separator_name = 'month';
            } else {
                $separator = self::clearDate($str['date'], 'm.d');
                $separator_name = 'day';
            }
            $year = self::clearDate($str['date'], 'Y');

            if ($year==$today_year) {

                if (!$brand) {
                    $Department = $str['Department'];
                } else {
                    $Department = $brand;
                }

                $Waiter = $str['Waiter'];
                $Id_waiter= $str['id_waiter'];
                $GuestNum = $str['GuestNum'];
                $DishDiscountSumInt = $str['DishDiscountSumInt'];
                $num_guest = $num_guest + $GuestNum;

                $all_summ = $all_summ+$DishDiscountSumInt;
                $num_check++;

                @$waiter_arr['num'][$separator][$year] = @$waiter_arr['num'][$separator][$year] + 1;
                @$waiter_arr['num_guest'][$separator][$year] = @$waiter_arr['num_guest'][$separator][$year] + $GuestNum;
                @$waiter_arr['sum'][$separator][$year] = @$waiter_arr['sum'][$separator][$year] + $DishDiscountSumInt;
                @$waiter_arr['id'][$separator] = $Id_waiter;

            }

        }
        $arr_interval = self::arrIntervalDate($date_start,$date_finish);
        $waiter_arr['num'] = self::addAllDate($waiter_arr['num'],$arr_interval);
        $waiter_arr['num_guest'] = self::addAllDate($waiter_arr['num_guest'],$arr_interval);
        $waiter_arr['sum'] = self::addAllDate($waiter_arr['sum'],$arr_interval);


        $mean_check = $all_summ/$num_check;
        $mean_arr = $this->meanCheckForDay($waiter_arr['sum'],$waiter_arr['num']);
        $mean_guest_arr = $this->meanCheckForDay($waiter_arr['sum'],$waiter_arr['num_guest']);

        $to_json_num = DashboardModel::to_json_moris($waiter_arr['num'], $separator_name, $summ, false);
        $to_json_sum = DashboardModel::to_json_moris($waiter_arr['sum'], $separator_name, $summ, false);
        $to_json_mean_check = DashboardModel::to_json_moris($mean_arr, $separator_name, $summ, false);
        $to_json_mean_guest = DashboardModel::to_json_moris($mean_guest_arr, $separator_name, $summ, false);

        $return_array = array('waiter_arr' => $waiter_arr,
            'department' => $Department,
            'separator_name' => $separator_name,
            'mean_check' => $mean_check,
            'waiter_name' => $Waiter,
            'num_check' => $num_check,
            'num_guest' => $num_guest,
            'all_summ' => $all_summ,
            'json_sum' =>$to_json_sum,
            'json_num' =>$to_json_num,
            'json_mean_check' => $to_json_mean_check,
            'json_mean_guest' => $to_json_mean_guest
        );


        return $return_array;
    }
    public function meanCheckForDay($arr_sum,$arr_num){
        $array_mean = array();
        foreach ($arr_sum as $separator=>$item_arr) {
            foreach ($item_arr as $year=>$value) {
                if ($arr_num[$separator][$year]!=0) {
                    $array_mean[$separator][$year] = $value / $arr_num[$separator][$year];
                } else {
                    $array_mean[$separator][$year] = 0;
                }
            }
        }
        return $array_mean;
    }


}