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
use \application\model\AdminModel;

class MarketingController extends BaseController
{
    public $right;
    public function before()
    {
        if (!$this->session->get("user")) {
            $this->request->redirect("?path=auth/index");
        }

        parent::before();

        $admin_model = new AdminModel();
        $user = $this->session->get("user");
        $rigth_arr = $admin_model->getRightOnDepartment($user['id']);

        if (count($rigth_arr) == 0) {
            return $this->view->render("dash/wait", array('type' => 'wait'));
        } else {
            $this->right = $rigth_arr;
        }

        return true;
    }

    public function action_index()
    {

        $month = $this->request->getGet('month');
        if ($month == 'True') {
            $month = true;
        } else {
            $month = false;
        }

        $dashModel = new DashboardModel;
        $dash_array = $dashModel->startTypeDash($this->right, 'departments', $this->date_start, $this->date_finish,$month);

        return $this->view->render("dash/marketing_d", array('brands' => $dash_array,
            'month' => $month,
            'date_start' => $this->date_start,
            'date_finish' => $this->date_finish,
            'type' => 'marketing',
            'level' => 'departments'));
    }

    public function action_departments()
    {

        $month = $this->request->getGet('month');
        if ($month == 'True') {
            $month = true;
        } else {
            $month = false;
        }

        //print_r($this->right);

        $dashModel = new DashboardModel;
        $dash_array = $dashModel->startTypeDash($this->right, 'departments', $this->date_start, $this->date_finish,$month);

        return $this->view->render("dash/marketing_d", array('brands' => $dash_array,
            'month' => $month,
            'date_start' => $this->date_start,
            'date_finish' => $this->date_finish,
            'type' => 'marketing',
            'level' => 'departments'));
    }

    public function action_brands()
    {

        $month = $this->request->getGet('month');
        if ($month == 'True') {
            $month = true;
        } else {
            $month = false;
        }

        $dashModel = new DashboardModel;
        $dash_array = $dashModel->startTypeDash($this->right, 'brands', $this->date_start, $this->date_finish,$month);

        return $this->view->render("dash/marketing_b", array('brands' => $dash_array,
            'month' => $month,
            'date_start' => $this->date_start,
            'date_finish' => $this->date_finish,
            'type' => 'marketing',
            'level' => 'brands'));
    }


    public function action_department()
    {
        $id_d = $this->request->getGet('id_d');
        $user = $this->session->get("user");

        if ($this->request->getGet('month') == 'True') {
            $month = true;
        } else {
            $month = false;
        }
        $rigth = $this->checkRight($user, $id_d);


        $dashModel = new MarketingModel;
        $dash_array = $dashModel->getDepartSalesInfo(array($rigth), true,$this->date_start, $this->date_finish);
        $dash_array_check = $dashModel->getCheckStatistic($id_d, true,$this->date_start, $this->date_finish);

        return $this->view->render("dash/marketing", array(

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
            'date_start' => $this->date_start,
            'date_finish' => $this->date_finish,
            'type' => 'marketing',
            'level' => 'department',
            'id_d' => $id_d
        ));
    }

    public function action_brand()
    {
        $id_b = $this->request->getGet('id_b');
        $user = $this->session->get("user");

        if ($this->request->getGet('month') == 'True') {
            $month = true;
        } else {
            $month = false;
        }


        $dashModel = new MarketingModel;
        //$dash_array = $dashModel->getDepartSalesInfo(array($rigth), true);
        $dash_array = $dashModel->getDepartSalesInfo($this->right, true,$this->date_start, $this->date_finish, $id_b);
        $dash_array_check = $dashModel->getCheckStatistic($id_b, true);

        return $this->view->render("dash/marketing", array(
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
            'date_start' => $this->date_start,
            'date_finish' => $this->date_finish,
            'type' => 'marketing',
            'level' => 'brand',
            'id_b' => $id_b
        ));
    }

    public function action_product()
    {
        $id_b = $this->request->getGet('id_b');
        $id_p = $this->request->getGet('id_p');
        $id_d = $this->request->getGet('id_d');
        $user = $this->session->get("user");

        if ($this->request->getGet('month') == 'True') {
            $month = true;
        } else {
            $month = false;
        }

        $dashModel = new MarketingModel;

        $dash_array = $dashModel->getProductInfo(array($this->rigth), true, $this->date_start, $this->date_finish,array($id_p));
        $dash_array_check = $dashModel->getCheckStatistic($id_b, true);

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
            'date_start' => $this->date_start,
            'date_finish' => $this->date_finish,
            'month' => $month,
            'type' => 'marketing',
            'level' => 'brand',
            'id_b' => $id_b
        ));
    }

}