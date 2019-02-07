<?php

namespace application\model;

use \application\service\Service;
use \application\model\BaseModel;
use \application\model\GoodsModel;
use \application\model\BasketModel;

class DashboardModel extends BaseModel {

	public function startDash() {

        $array_account = $this->getAcountFromMysql();

        $today_year = date('Y');
        $last_year = $today_year -1;

        foreach($array_account as $str) {
            $month = self::clearDate($str['DateTime'], 'M');
            $year = self::clearDate($str['DateTime'], 'Y');

            $Department = $str['Department'];
            $sum_incom = $str['Sum.Incoming'];
            $sum_out = $str['Sum.Outgoing'];
            $summ[$month][$year] = $summ[$month][$year] + $sum_out - $sum_incom;
            $all_summ[$year] = $all_summ[$year] + $sum_out - $sum_incom;

        }
        $to_json = array();

        foreach ($summ as $month=>$arr_year){
            $to_json1 = array();
            $to_json1[] = array('month'=>$month);
            foreach ($arr_year as $year=>$summ) {
                $to_json1[]=array("$year"=>$summ);

            }
            $to_json[] = $to_json1;
        }

        $delta = round($all_summ[$today_year]/($all_summ[$last_year]/100)-100,2);
        return array('to_json' =>  $to_json,
            'all_summ'=>$all_summ[$today_year],
            'per_delta' => $delta
            );
	}

	protected function getAcountFromMysql($where=''){

        $statement = self::$connection->prepare("SELECT * FROM dashboard_this_years");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }


	public function startTypeDash($type){
        $accounts = $this->getAcountFromMysql();
        $array_account = $this->prepareArrayFromMysql($accounts,$type);



        $today_year = date('Y');
        $last_year = $today_year -1;



        foreach($array_account as $brand=>$array_month){

            $sum_department = array();
            foreach($array_month as $month=>$array_year){
                foreach($array_year as $year=>$sum){
                    $sum_department[$year] = $sum_department[$year] + $sum;
                }
            }
            if (isset($sum_department[$last_year])) {
                $delta = round($sum_department[$today_year] / ($sum_department[$last_year] / 100) - 100, 2);
            } else {
                $delta =0;
            }
            If ($delta>0){
                $style = 'text-success';
                $ikon = 'fa-arrow-up';
            }
            elseif ($delta<0) {
                $style = 'text-danger';
                $ikon = 'fa-arrow-down';
            }
            else {
                $style = 'text-muted';
                $ikon = '';
            }
            $array_out[] = array(
                'brand' => $brand,
                'vir' => $sum_department[$today_year],
                'delta' => $delta,
                'style' => $style,
                'ikon'=> $ikon
            );
        }



        return $array_out;

    }

    public function prepareArrayFromMysql($array_account,$type){
        $brand_department_ar = array(
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
            'Контакт-центр'  => 'Другое',
            'СБ 130квартал РНГ' => 'Sushi Studio Бары',
            'СБ Карла Маркса (не раб)' => 'Sushi Studio Бары',
            'СБ Карла Маркса РНГ' => 'Sushi Studio Бары',
            'СБ Партизанская (не раб)' => 'Sushi Studio Бары',
            'СБ Партизанская РНГ' => 'Sushi Studio Бары',
            'СБ Советская' => 'Sushi Studio Бары',
            'СБ Юбилейный' => 'Sushi Studio Бары',
            'СМ Снегирь' => 'СушиТочка',
            'СМ Фортуна' => 'СушиТочка',
            'СТ Абсолют' => 'СушиТочка',
            'СТ Ангара' => 'СушиТочка',
            'СТ Европарк' => 'СушиТочка',
            'СТ Комсомолл' => 'СушиТочка',
            'СТ Лермонтов' => 'СушиТочка',
            'СТ Модный квартал' => 'СушиТочка',
            'СТ Сильвермолл' => 'СушиТочка',
            'СТ ЦП Байкальская' => 'СушиТочка',
            'ТОТО МК' => 'Другое',
            'Фабрика Багаутдинова'  => 'Другое',
            'Фабрика ИП Димаков' => 'Другое',
            'Фо Ми' => 'Phome',
            'Фуд Корт Лермонтов (не раб)' => 'Sushi Studio Фудкорты',
            'Фуд Корт МК' => 'Sushi Studio Фудкорты',
            'Фуд Корт Новый' => 'Sushi Studio Фудкорты',
            'Центральный Офис'  => 'Другое',
        );

        $department_ald_name_ar = array(
            'Антрекот Ангарск' => 'Антрекот Ангарск',
            'Антрекот КМ' => 'Антрекот КМ',
            'Антрекот МК' => 'Антрекот МК',
            'Антрекот СМ' => 'Антрекот СМ',
            'Доставка Култукская (не раб)' => 'Доставка Култукская',
            'Доставка Култукская Иоффе' => 'Доставка Култукская',
            'Доставка Ново-Ленино (не раб)' => 'Доставка Ново-Ленино',
            'Доставка Ново-Ленино Никулина' => 'Доставка Ново-Ленино',
            'Доставка Сигма (не раб)' => 'Доставка Сигма',
            'Доставка Сигма Никулина' => 'Доставка Сигма',
            'Доставка Солнечный (не раб)' => 'Доставка Солнечный',
            'Доставка Солнечный Иоффе' => 'Доставка Солнечный',
            'Рассольник' => 'Рассольник',
            'Контакт-центр'  => 'Контакт-центр',
            'СБ 130квартал РНГ' => 'СБ 130квартал РНГ',
            'СБ Карла Маркса (не раб)' => 'СБ Карла Маркса',
            'СБ Карла Маркса РНГ' => 'СБ Карла Маркса',
            'СБ Партизанская (не раб)' => 'СБ Партизанская',
            'СБ Партизанская РНГ' => 'СБ Партизанская',
            'СБ Советская' => 'СБ Советская',
            'СБ Юбилейный' => 'СБ Юбилейный',
            'СМ Снегирь' => 'СМ Снегирь',
            'СМ Фортуна' => 'СМ Фортуна',
            'СТ Абсолют' => 'СТ Абсолют',
            'СТ Ангара' => 'СТ Ангара',
            'СТ Европарк' => 'СТ Европарк',
            'СТ Комсомолл' => 'СТ Комсомолл',
            'СТ Лермонтов' => 'СТ Лермонтов',
            'СТ Модный квартал' => 'СТ Модный квартал',
            'СТ Сильвермолл' => 'СТ Сильвермолл',
            'СТ ЦП Байкальская' => 'СТ ЦП Байкальская',
            'ТОТО МК' => 'ТОТО МК',
            'Фабрика Багаутдинова'  => 'Фабрика',
            'Фабрика ИП Димаков' => 'Фабрика',
            'Фо Ми' => 'Phome',
            'Фуд Корт Лермонтов (не раб)' => 'Фуд Корт Лермонтов',
            'Фуд Корт МК' => 'Фуд Корт МК',
            'Фуд Корт Новый' => 'Фуд Корт Новый',
            'Центральный Офис'  => 'Центральный Офис',
        );
        if ($type=='brand') {

            $group_arr = $brand_department_ar;


        } elseif ($type=='department'){
            $group_arr = $department_ald_name_ar;
        }

        foreach ($array_account as $str) {
            $Department = $str['Department'];
            $group = $group_arr[$Department];
            $month = self::clearDate($str['DateTime'], 'M');
            $year = self::clearDate($str['DateTime'], 'Y');


            $sum_incom = $str['Sum.Incoming'];
            $sum_out = $str['Sum.Outgoing'];
            $summ[$group][$month][$year] = $summ[$group][$month][$year] + $sum_out - $sum_incom;
            $all_summ[$group][$year] = $all_summ[$group][$year] + $sum_out - $sum_incom;

        }
        return $summ;
    }

    protected function arrayForJson(){

    }
    public static function clearDate($date_from_mysql,$format='Y-m-d H:i:s')
    {
        if (self::isDate($date_from_mysql)) {
            $date_prepare = \DateTime::createFromFormat("Y-m-d H:i:s", $date_from_mysql)->format($format);
            return $date_prepare;
        } else return FALSE;

    }
    public static function isDate($date){
        if (isset($date) and ($date!=NULL) and $date!=''){
            return TRUE;
        } else {
            return FALSE;
        }

    }


}