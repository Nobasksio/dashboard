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
        if ($waiters_id==''){

            $department_name_arr = $dashboard_model->getNameDepart($right);
            $search_department = $dashboard_model->ArraytoWhereMysql($department_name_arr, 'department_name');
            $array_sales = $this->getCheckFromMysql($search_department,$date_start, $date_finish);

        }

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



        return $this->toBeatifullArray($array_sales, true, $brand_name);


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


}