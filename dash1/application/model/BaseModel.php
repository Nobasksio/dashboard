<?php

namespace application\model;

use \application\service\Service;

use \application\model\DashboardModel;

abstract class BaseModel {

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
        'СБ Юбилейный РНГ' => 'Sushi Studio Бары',
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
        'СТ ЦП Дзержинского' => 'СушиТочка',
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
        'СТ ЦП Дзержинского'=> 'СТ ЦП Дзержинского',
        'ТОТО МК' => 'ТОТО МК',
        'Фабрика Багаутдинова' => 'Фабрика',
        'Фабрика ИП Димаков' => 'Фабрика',
        'Фо Ми' => 'Phome',
        'СБ Юбилейный РНГ'=>'SS СБ Юбилейный',
        'Центральный Офис' => 'Центральный Офис',
        'Антрекот Сухэ-Батора' => 'Антрекот КМ',
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
        'СБ Юбилейный РНГ'=>13,
        'Фуд Корт Лермонтов (не раб)' => 0,
        'Фуд Корт МК' => 27,
        'Фуд Корт Новый' => 28,
        'СМ Снегирь' => 14,
        'СТ Фортуна' => 22,
        'СМ Фортуна' => 22,
        'СТ Абсолют' => 15,
        'СТ Ангара' => 16,
        'СТ Европарк' => 17,
        'СТ Комсомолл' => 18,
        'СТ Лермонтов' => 19,
        'СТ Модный квартал' => 20,
        'СТ Сильвермолл' => 21,
        'СТ ЦП Байкальская' => 23,
        'ТОТО МК' => 0,
        'Фабрика Багаутдинова' => 0,
        'Фабрика ИП Димаков' => 0,
        'Фо Ми' => 26,
        'Центральный Офис' => 0,
        'СТ ЦП Дзержинского'=>1124
    );
    public $type_menu = array(
        1 =>array(
            'Бар Меню' => 'bar',
            'Кухня меню СБ' => 'kitchen',
        ),
        2 => array(
            'Бар Меню' =>'bar',
            'Бар меню' =>'bar',
            'Кухня меню СБ' => 'kitchen',
            'Детское меню2018'=>'kitchen',
            'Бар СБ НОВОЕ' => 'bar',
            'Кухня СБ НОВОЕ' => 'kitchen'
        ),
        3 => array(
            'Бар меню ФК' => 'bar',
            'Кухня меню СБ' => 'kitchen',
            'Бар меню' => 'bar',
            'начинка лапша СБ' => 'kitchen',
        ),
        4 => array(
            'Кухня меню'=>'kitchen',
            'Бар меню'=>'bar',
            'Бар остальное'=>'bar',
            'Кухня остальное'=>'kitchen',
            'Горячее комбо2'=>'kitchen',
            'Гарнир к стейк обед'=>'kitchen',
            'Салат комбо1'=>'kitchen',
            'Суп комбо1'=>'kitchen',
        ),
        5 => array(
            'Кухня Окинава'=>'kitchen',
            'Дополнительно СМ'=>'kitchen',
            'Кухня Суши Точка'=>'kitchen',
            'Напитки СТ'=>'bar',
            'Дополнительно СТ'=>'kitchen',
        ),
        6 => array(
            'Бар меню ФМ'=>'bar',
            'Кухня меню ФМ'=>'kitchen',
            'Комбо1'=>'kitchen',
            'Комбо2'=>'kitchen',
            'Комбо3'=>'kitchen',
            'Комбо напитки ФМ'=>'bar',
        )
    );

	protected static $connection = null;



	public function __construct() {

		if (!self::$connection) {
			$db = Service::config()->get("db");

			self::$connection = new \PDO($db["dsn"], $db["user"], $db["password"],array('charset'=>'utf8'));

            self::$connection->exec('SET NAMES utf8');

			self::$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
	}
    public static function clearDate($date_from_mysql, $format = 'Y-m-d H:i:s')
    {
        if (self::isDate($date_from_mysql)) {
            $date_prepare = \DateTime::createFromFormat("Y-m-d H:i:s", $date_from_mysql)->format($format);
            return $date_prepare;
        } else return FALSE;

    }
    public static function isDate($date)
    {
        if (isset($date) and ($date != NULL) and $date != '') {
            return TRUE;
        } else {
            return FALSE;
        }

    }
    protected function getSalesFromMysql($month,$date_start, $date_finish,$Department='',$products='')
    {
        $where  = '';
        $where2 = '';
        if ($Department !='') {
            $where = "where s.Department IN $Department ";
        }
        $date_start_arr = explode(".", $date_start);
        $date_finish_arr = explode(".", $date_finish);
        $month_start = $date_start[1];
        $day_start = $date_start[0];

        $month_finish = $date_finish[1];
        $day_finish = $date_finish[0];

        $date_start = $date_start_arr[2]."-".$date_start_arr[1]."-".$date_start_arr[0];
        $date_finish = $date_finish_arr[2]."-".$date_finish_arr[1]."-".$date_finish_arr[0];

        $date_where = "and DATE(s.date) BETWEEN '$date_start' AND '$date_finish'";

        if ($products!=''){
            $where2 = " and s.DishName IN $products";
        }

        $statement = self::$connection->prepare(
            "SELECT * FROM 
                          sales s
                          LEFT JOIN dishs d ON s.DishName=dish_name
                          LEFT JOIN departments dep ON s.Department=dep.department_name
                          LEFT JOIN category c ON s.group=c.category_name
                          LEFT JOIN category_for_department cfd ON c.id_category=cfd.category_id and dep.id_department=cfd.department_id
                          $where $date_where
                          $where2
                          and cfd.status=1
                          order by s.Department");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function getAllAcounts($month,$Department = '',$date_start,$date_finish)
    {
        if ($Department != '') {
            $where = "and d.Department IN $Department";
        } else {
            $where = '';
        }
        $date_start_arr = explode(".", $date_start);
        $date_finish_arr = explode(".", $date_finish);
        $month_start = $date_start[1];
        $day_start = $date_start[0];

        $month_finish = $date_finish[1];
        $day_finish = $date_finish[0];

        $date_start = $date_start_arr[2]."-".$date_start_arr[1]."-".$date_start_arr[0];
        $date_finish = $date_finish_arr[2]."-".$date_finish_arr[1]."-".$date_finish_arr[0];

        $date_where = "and DATE(d.DateTime) BETWEEN '$date_start' AND '$date_finish'";


        $statement = self::$connection->prepare(
            "SELECT * FROM 
                          dashboard_this_years_test d, 
                          dashboard_type_account t 
                        where d.AccountName = t.name 
                          $where $date_where
                          order by d.DateTime ");
        $statement->execute();


        $arr_accounts = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $date_start = ($date_start_arr[2]-1)."-".$date_start_arr[1]."-".$date_start_arr[0];
        $date_finish = ($date_finish_arr[2]-1)."-".$date_finish_arr[1]."-".$date_finish_arr[0];

        $date_where2 = "and DATE(d.DateTime) BETWEEN '$date_start' AND '$date_finish'";
        $statement2 = self::$connection->prepare(
            "SELECT * FROM 
                          dashboard_this_years_test d, 
                          dashboard_type_account t 
                        where  d.AccountName = t.name 
                          $where $date_where2
                          order by d.DateTime");
        $statement2->execute();

        $arr_accounts = array_merge($arr_accounts,$statement2->fetchAll(\PDO::FETCH_ASSOC));

        return $arr_accounts;
    }
    protected function getAcounts($month, $Department = '',$date_start,$date_finish)
    {

        if ($Department != '') {
            $where = "and d.Department IN $Department";
        } else {
            $where = '';
        }

        $date_start_arr = explode(".", $date_start);
        $date_finish_arr = explode(".", $date_finish);
        $month_start = $date_start[1];
        $day_start = $date_start[0];

        $month_finish = $date_finish[1];
        $day_finish = $date_finish[0];

        $date_start = $date_start_arr[2]."-".$date_start_arr[1]."-".$date_start_arr[0];
        $date_finish = $date_finish_arr[2]."-".$date_finish_arr[1]."-".$date_finish_arr[0];

        $date_where = "and DATE(d.DateTime) BETWEEN '$date_start' AND '$date_finish'";


        $statement = self::$connection->prepare(
            "SELECT * FROM 
                          dashboard_this_years_test d, 
                          dashboard_type_account t 
                        where t.group=1 
                          and d.AccountName = t.name 
                          $where $date_where
                          order by d.DateTime  ");
        $statement->execute();

        $arr_accounts = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $date_start = ($date_start_arr[2]-1)."-".$date_start_arr[1]."-".$date_start_arr[0];
        $date_finish = ($date_finish_arr[2]-1)."-".$date_finish_arr[1]."-".$date_finish_arr[0];

        $date_where2 = "and DATE(d.DateTime) BETWEEN '$date_start' AND '$date_finish'";
        $statement = self::$connection->prepare(
            "SELECT * FROM 
                          dashboard_this_years_test d, 
                          dashboard_type_account t 
                        where t.group=1 
                          and d.AccountName = t.name 
                          $where $date_where2
                          order by d.DateTime DESC");
        $statement->execute();

       $arr_accounts = array_merge($arr_accounts,$statement->fetchAll(\PDO::FETCH_ASSOC));

        return $arr_accounts;
    }

    protected function getOrdersFromMysql($month, $Department = '',$produts,$date_start,$date_finish,$id_waiter)
    {
        $date_start_arr = explode(".", $date_start);
        $date_finish_arr = explode(".", $date_finish);
        $date_start = ($date_start_arr[2])."-".$date_start_arr[1]."-".$date_start_arr[0];
        $date_finish = ($date_finish_arr[2])."-".$date_finish_arr[1]."-".$date_finish_arr[0];
        $date_where = " and DATE(OpenTime) BETWEEN '$date_start' AND '$date_finish'";
        $statement = self::$connection->prepare(
            "SELECT DISTINCT OrderNum 
                         FROM orders
                        where Department_id=$Department
                          and DishName_id=$produts
                          and 
                          $date_where
                           ");
        $statement->execute();

        $arr_number = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if (count($arr_number)>0) {

            $where_order_num = DashboardModel::ArraytoWhereMysql($arr_number, 'OrderNum');

            $statement = self::$connection->prepare(
                "SELECT * 
                         FROM orders
                        where OrderNum IN $where_order_num
                          and Department_id = $Department
                           ");
            $statement->execute();
            $order_arr = $statement->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $order_arr=array();
        }

        return $order_arr;
    }
    protected function getOrdersWaiterFromMysql($Department = '',$date_start,$date_finish,$id_waiter,$kol)
    {
        $date_start_arr = explode(".", $date_start);
        $date_finish_arr = explode(".", $date_finish);
        $date_start = ($date_start_arr[2])."-".$date_start_arr[1]."-".$date_start_arr[0];
        $date_finish = ($date_finish_arr[2])."-".$date_finish_arr[1]."-".$date_finish_arr[0];
        $date_where = " and DATE(o.OpenTime) BETWEEN '$date_start' AND '$date_finish'";
        $statement = self::$connection->prepare(
            "SELECT DISTINCT o.OrderNum
                         FROM orders o 
                         LEFT JOIN checks c ON c.OrderNum=o.OrderNum
                         LEFT JOIN waiters w ON w.waiter_name=c.Waiter
                        where o.Department_id = $Department
                          and w.id_waiter = :id_waiter
                          $date_where
                          Limit $kol
                           ");

        $statement->bindValue(':id_waiter', $id_waiter);
        $statement->execute();
        $arr_number = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if (count($arr_number)>0) {

            $where_order_num = DashboardModel::ArraytoWhereMysql($arr_number, 'OrderNum');

            $statement = self::$connection->prepare(
                "SELECT * 
                         FROM orders o 
                         LEFT JOIN checks c ON c.OrderNum=o.OrderNum
                         LEFT JOIN waiters w ON w.waiter_name=c.Waiter
                         LEFT JOIN dishs d ON d.id_dish=o.DishName_id
                        where o.Department_id = $Department
                          and o.OrderNum IN $where_order_num
                          and w.id_waiter = :id_waiter
                          $date_where
                           ");

            $statement->bindValue(':id_waiter', $id_waiter);
            $statement->execute();
            $order_arr = $statement->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $order_arr=array();
        }

        return $order_arr;
    }
    public function getCheckFromMysql($Department = '',$date_start,$date_finish,$search_waiter='')
    {
        if ($Department != '') {
            $where = " s.Department IN $Department ";

        } else {
            $where = '';
        }

        if ($search_waiter != '') {
            $where_waiters = " and s.Waiter IN $search_waiter ";

        } else {
            $where_waiters = '';
        }
        $date_start_arr = explode(".", $date_start);
        $date_finish_arr = explode(".", $date_finish);

        $date_start = $date_start_arr[2]."-".$date_start_arr[1]."-".$date_start_arr[0];
        $date_finish = $date_finish_arr[2]."-".$date_finish_arr[1]."-".$date_finish_arr[0];
        $date_where = " and DATE(s.date) BETWEEN '$date_start' AND '$date_finish'";

        $statement = self::$connection->prepare(
            "SELECT * FROM 
                          checks s
                          LEFT JOIN waiters w ON s.Waiter=w.waiter_name
                        where 
                          $where $where_waiters
                          $date_where
                          order by s.Department ");
        if ($Department != '') {
            $statement->bindValue(':Department', $Department);
        }
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCategoryMysql($rigth_arr){
        $statement = self::$connection->prepare(
            "SELECT *, cfd.status statusm  FROM 
                          category_for_department cfd
                          LEFT JOIN category c ON cfd.category_id=c.id_category
                        where 
                          cfd.department_id = :dep_id ");
        $statement->bindValue(':dep_id', $rigth_arr);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    public static function normaDate($date){

        $date_arr = explode(".", $date);
        $date_return = $date_arr[2]."-".$date_arr[1]."-".$date_arr[0];

        return $date_return;
    }

    public static function addAllDate($arr_value,$arr_date,$type_separator='day'){
        foreach ($arr_date as $date) {
            $separator = self::clearDate($date, 'm.d');
            $year = self::clearDate($date, 'Y');
            if (!isset($arr_value[$separator])){
                $arr_value[$separator][$year] = 0;
            }
	    }
	    return $arr_value;
    }

    public static function arrIntervalDate($date_start,$date_finish){
        $from = new \DateTime($date_start);
        $to   = new \DateTime($date_finish);

        $period = new \DatePeriod($from, new \DateInterval('P1D'), $to);

        $arrayOfDates = array_map(
            function($item){return $item->format('Y-m-d H:i:s');},
            iterator_to_array($period)
        );
        return $arrayOfDates;
    }




}