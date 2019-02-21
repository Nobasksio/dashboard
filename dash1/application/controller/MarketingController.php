<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 15/02/2019
 * Time: 15:36
 */

namespace application\controller;

use \application\service\Service;
use \application\controller\BaseController;
use \application\model\MarketingModel;
use \application\model\DashboardModel;

class MarketingController extends  BaseController
{
    public function before()
    {

        if (!$this->session->get("user")) {
            $this->request->redirect("?path=auth/index");
        }

        parent::before();

        return true;
    }
    public function action_index(){
        $month = $this->request->getGet('month');
        if ($month=='True'){
            $month=true;
        } else {
            $month=false;
        }
        $dashModel = new DashboardModel;
        $dash_array = $dashModel->startTypeDash('department', $month);
        return $this->view->render("dash/marketing_d", array('brands' => $dash_array,
            'month' => $month,
            'type' => 'marketing',
            'level' => 'index'));
    }


    public function action_department()
    {
        $id_d = $this->request->getGet('id_d');

        if ($this->request->getGet('month') == 'True') {
            $month = true;

        } else {
            $month = false;

        }
        $dashModel = new MarketingModel;
        $dash_array = $dashModel->getCatStatistic($id_d,true);
        $dash_array_check = $dashModel->getCheckStatistic($id_d,true);

        return $this->view->render("dash/marketing", array(
            'to_json_sale' => $dash_array['to_json_sale'],
            'sum_ar_bar' => $dash_array['sum_ar_bar'],
            'count_ar_bar' => $dash_array['count_ar_bar'],
            'sum_ar_kitch' => $dash_array['sum_ar_kitch'],
            'count_ar_kitch' => $dash_array['count_ar_kitch'],
            'vir_bar' => $dash_array['vir_bar'],
            'vir_kitch' => $dash_array['vir_kitch'],
            'department' => $dash_array['department'],
            'top10sum' => $dash_array['top10sum'],
            'top10count' => $dash_array['top10count'],
            'top10high_ss' => $dash_array['top10high_ss'],
            'mean_check' => $dash_array_check['mean_check'],
            'mean_guest' => $dash_array_check['mean_guest'],
            'month' => $month,
            'type' => 'marketing',
            'level' => 'department',
            'id_d'=>$id_d
        ));


    }

}