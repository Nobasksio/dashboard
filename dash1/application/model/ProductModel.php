<?php

namespace application\model;

use \application\service\Service;
use \application\model\BaseModel;
use \application\model\MarketingModel;

class ProductModel extends BaseModel {

	const STATUS_ACTIVE = 10;
	const STATUS_INACTIVE = 20;

	public function getById($id) {

		$statement = self::$connection->prepare("SELECT * FROM goods WHERE id = :id");
		$statement->bindValue(':id', $id, \PDO::PARAM_INT);
		$statement->execute();

		return $statement->fetch(\PDO::FETCH_ASSOC);

	}

    public function getAllProducts($rigth_arr, $date_start,$date_finish,$month=true,$id_b=false) {

        $dashboard_model = new DashboardModel;
        $brand = false;
        if ($id_b){

            $rigth_arr = $dashboard_model->onlyChooseBrand($rigth_arr,$id_b);
            $brand = true;
            $brand_name = $key = array_search($id_b, $this->brand_id_department_ar);

        }

        $department_name_arr = $dashboard_model->getNameDepart($rigth_arr);

        $search_department = $dashboard_model->ArraytoWhereMysql($department_name_arr, 'department_name');

        $array_sales = $this->getSalesFromMysql($month,$date_start,$date_finish,$search_department);
        //rint_r($array_sales);
        return $this->productsToBeatifullArray($array_sales, $month);

    }

    public function getAllCategory($rigth_arr, $date_start,$date_finish,$month=true,$id_b=false) {

        $dashboard_model = new DashboardModel;
        $brand = false;
        if ($id_b){

            $rigth_arr = $dashboard_model->onlyChooseBrand($rigth_arr,$id_b);
            $brand = true;
            $brand_name = $key = array_search($id_b, $this->brand_id_department_ar);

        }


        $array_category = $this->getCategoryMysql($rigth_arr);
        //rint_r($array_sales);
        return $array_category;

    }
    public function getOneProduct($rigth_arr,$id_p, $date_start,$date_finish,$month) {

        $dashboard_model = new DashboardModel;
        $brand = false;

        $department_name_arr = $dashboard_model->getNameDepart($rigth_arr);

        $search_department = $dashboard_model->ArraytoWhereMysql($department_name_arr, 'department_name');

        $department_name_arr = $dashboard_model->getNameProduct("($id_p)");

        $search_product = $dashboard_model->ArraytoWhereMysql($department_name_arr, 'dish_name');

        $array_sales = $this->getSalesFromMysql($month,$date_start,$date_finish,$search_department,$search_product);

        return $this->productToBeatifullArray($array_sales, $month);

    }

    public function getRelations($id_d,$id_p,$date_start,$date_finish,$month){

        $dashboard_model = new DashboardModel;
        //$search_department = $dashboard_model->ArraytoWhereMysql($id_d, 'Department_id');

        $array_sales = $this->getOrdersFromMysql($month,$id_d,$id_p,$date_start,$date_finish);
        $array_order = array();
        $array_rel = array();

        if (count($array_sales)>0) {
            foreach ($array_sales as $item) {
                $DishAmountInt = $item['DishAmountInt'];
                $OpenTime = $item['OpenTime'];
                $OrderNum = $item['OrderNum'];

                $DishName_id = $item['DishName_id'];
                if ($id_p == $DishName_id) continue;
                $date = self::clearDate($OpenTime, 'd');
                $order_num_prep = $OrderNum . $date;

                $array_order[] = $order_num_prep;

                $array_id[] = $DishName_id;
                @$array_rel[$DishName_id]++;

            }
            //print_r($array_order);
            $all = count(array_unique($array_order));
            $attay_id_uniq = array_unique($array_id);

            $search_department = $dashboard_model->ArraytoWhereMysql($attay_id_uniq);
            $name_arr = $dashboard_model->getNameProduct($search_department);
            //print_r($all);

            foreach ($name_arr as $key => $item) {
                $id_dish = $item['id_dish'];
                $kol = $array_rel[$id_dish];
                $name_arr[$key]['count_rel'] = $kol;
            }
            $return_arr = array('count_order' => $all,
                'name_arr' => $name_arr);
            //print_r($name_arr);
        } else {
            $return_arr =array();
        }
        return $return_arr;
    }
    protected function productToBeatifullArray($array_account, $month, $brand=false)
    {
        $dashboardModel = new DashboardModel();
        $today_year = date('Y');
        $last_year = $today_year - 1;

        foreach ($array_account as $str) {

            /*
            if (!$month) {
                $separator = self::clearDate($str['date'], 'M');
                $separator_name = 'month';
            } else {
                $separator = self::clearDate($str['date'], 'd');
                $separator_name = 'day';
            }

*/          $separator = self::clearDate($str['date'], 'm.d');
            $separator_name = 'day';

            $year = self::clearDate($str['date'], 'Y');

            if (!$brand) {
                $Department = $str['Department'];
            } else {
                $Department = $brand;
            }
            $DishAmountInt = $str['DishAmountInt'];
            $DishName = $str['DishName'];
            $price = $str['price'];
            $ss = $str['ss'];

            $all_ss[$separator][$year] = $ss;
            $all_price[$separator][$year] = $price;
            $all_count[$separator][$year] = $DishAmountInt;

        }

        $to_json_ss = $dashboardModel->to_json_moris($all_ss, $separator_name,0);
        $to_json_price = $dashboardModel->to_json_moris($all_price, $separator_name, 0);
        $to_json_count = $dashboardModel->to_json_moris($all_count, $separator_name);

        $delta_ss = $this->delta($all_ss);

        If ($delta_ss > 0) {
            $style = 'text-success';
            $ikon = 'fa-arrow-up';
        } elseif ($delta_ss < 0) {
            $style = 'text-danger';
            $ikon = 'fa-arrow-down';
        } else {
            $style = 'text-muted';
            $ikon = '';
        }

        $return_array = array('ss' => $to_json_ss,
            'product_name' => $DishName,
            'price' => $to_json_price,
            'count' => $to_json_count,
            'department' => $Department,
            'separator_name' => $separator_name,
            'delta_ss' => $delta_ss,
            'style' => $style,
            'ikon' => $ikon
        );

        return $return_array;
    }
    protected function categoryToBeatifullArray($array_account, $month, $brand=false)
    {
        $array_category = [];
        foreach ($array_account as $str) {
            $array_category[] = $str['group'];

        }
       $array_unique = array_unique($array_category);

        return $array_unique;
    }
    protected function productsToBeatifullArray($array_account, $month, $brand=false)
    {
        $marketingModel = new MarketingModel();
        $today_year = date('Y');
        $last_year = $today_year - 1;

        $group_sum = array();
        $group_count = array();
        $all_vir = 0;
        $all_vir_bar = 0;
        $all_vir_kitch = 0;


        $array_dish = array();
        $array_dish_count = array();

        $arr_price = $marketingModel->getMaxPrice($array_account);

        $arr_ss = $marketingModel->getMeanSs($array_account);
        $top_high_ss = $marketingModel->getTopHightSs($arr_price,$arr_ss,$array_account,0,1000);

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

                @$array_dish[$DishName]['summ'] += $price * $DishAmountInt;
                @$array_dish[$DishName]['group'] = $group;

                @$array_dish[$DishName]['count'] += $DishAmountInt;
                $groupTop = $str['groupTop'];
                if ($group == null) {
                    $group = $str['group0'];;

                }
                $group_t[] = $groupTop;
                $price = $str['price'];

                $kol_sale = $str['DishAmountInt'];
                $depart_name = $str['Department'];
                $brand_id = $marketingModel->getBrandIdfromNameDepatment($depart_name);
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

        $array_dish = $this->add_in_arr($array_dish, $arr_price,'price');
        $array_dish = $this->add_in_arr($array_dish, $id_arr,'id_product');


        $array_dish = $this->add_in_arr($array_dish, $top_high_ss,'ss');
        //print_r($array_dish);
        asort($group_sum);
        asort($group_count);

        $return_array = array('products' => $array_dish,
            'vir_kitch' => $all_vir_kitch,
            'sum_ar_bar' => $group_sum['bar'],
            'sum_ar_kitch' => $group_sum['kitchen'],
            'count_ar_bar' => $group_count['bar'],
            'count_ar_kitch' => $group_count['kitchen'],
            'top10high_ss' => $top_high_ss,
            'department' => $Department
        );

        //print_r($return_array);
        return $return_array;
    }
    protected function add_in_arr($array_dish,$arr_add,$name_field){
        foreach ($array_dish as $dish=>$item) {

            if (isset($arr_add[$dish])){
                $add_value = $arr_add[$dish];
            } else {
                $add_value = 0;
            }

            $array_dish[$dish][$name_field] = $add_value;
	    }
	    return $array_dish;
    }

    public function delta($array){

        $min_value = @array_shift(min($array));
        $max_value = @array_shift(max($array));

        if ($min_value!=0) {
            $delta_per = ($max_value - $min_value) / ($min_value / 100);
            $delta_rub = $max_value - $min_value;
        }
        return array('delta_per' => $delta_per,
            'delta_rub' => $delta_rub
            );
    }

}