<?php

namespace application\model;

use \application\service\Service;

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
    protected function getSalesFromMysql($month,$Department='',$products='')
    {
        if ($Department !='') {
            $where = "where Department IN $Department";
        } else {
            $where = '';
        }
        if ($month) {
            $table = 'sales_this_month';

        } else {
            $table = 'sales_this_year';

        }
        $statement = self::$connection->prepare(
            "SELECT * FROM 
                          $table   
                          $where
                          order by Department");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function getAllAcounts($month,$Department = '')
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

        $statement = self::$connection->prepare(
            "SELECT * FROM 
                          $table d, 
                          dashboard_type_account t 
                        where d.AccountName = t.name 
                          $where
                          order by `DateTime`  ");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    protected function getAcounts($month, $Department = '')
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

        $statement = self::$connection->prepare(
            "SELECT * FROM 
                          $table d, 
                          dashboard_type_account t 
                        where t.group=1 
                          and d.AccountName = t.name 
                          $where
                          order by Department ");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

}