<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 29/01/2019
 * Time: 12:43
 */

class order
{
    public static function getTodayOrders(){
        $sRequest = @mysqli_query($connect_sql, "SELECT * FROM `today`");
    }

    public static function clearDate($date_from_mysql,$format='Y-m-d H:i:s')
    {
        if (self::isDate($date_from_mysql)) {
            $date_prepare = DateTime::createFromFormat("Y-m-d H:i:s", $date_from_mysql)->format($format);
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