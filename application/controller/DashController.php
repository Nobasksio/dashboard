<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 29/01/2019
 * Time: 12:51
 */

namespace application\controller;

use \application\service\Service;
use \application\service\FrontController;

class DashController extends FrontController
{
    public function action_index() {

        $order = new OrderModel();
        $order_arr = $order->getTodayOrders();

        $this->view->render("product/index", [
            "title"					=> $this->config->get("title"),
            "customerCollection"	=> $order_arr
        ]);

    }

}