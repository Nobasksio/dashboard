<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 28/01/2019
 * Time: 23:06
 */

namespace application\service;


class History
{
    public $arr_url;
    public function __construct() {
        $pageURL = 'Http//:'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        $_SESSION['history'][] = $pageURL;
        $this->arr_url = $_SESSION['history'];
    }
}