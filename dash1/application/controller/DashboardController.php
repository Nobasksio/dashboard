<?php

namespace application\controller;

use application\model\AdminModel;
use \application\service\Service;
use \application\controller\BaseController;
use \application\model\DashboardModel;

class DashboardController extends BaseController
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


        if (count($rigth_arr)==0){
            return $this->view->render("dash/wait",array('type'=>'wait'));
        } else {
            $this->right = $rigth_arr;
        }

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
        $dash_array = $dashModel->startTypeDash($this->right,'departments',$month);

        return $this->view->render("dash/departments", array('brands' => $dash_array,
            'month' => $month,
            'type' => 'dashboard',
            'level' => 'departments'));
    }

    public function action_chain()
    {

        if ($this->request->getGet('month') == 'True') {
            $dashModel = new DashboardModel;
            $dash_array = $dashModel->startDash(true);


            return $this->view->render("dash/index", array(
                'to_json' => $dash_array['to_json'],
                'all_summ' => $dash_array['all_summ'],
                'per_delta' => $dash_array['per_delta'],
                'separator_name' => $dash_array['separator_name'],
                'style' => $dash_array['style'],
                'ikon' => $dash_array['ikon'],
                'month' => True,
                'type' => 'dashboard',
                'level' => 'index'));
        } else {
            $dashModel = new DashboardModel;
            $dash_array = $dashModel->startDash();
            return $this->view->render("dash/index", array(
                'to_json' => $dash_array['to_json'],
                'all_summ' => $dash_array['all_summ'],
                'per_delta' => $dash_array['per_delta'],
                'style' => $dash_array['style'],
                'ikon' => $dash_array['ikon'],
                'month' => False,
                'separator_name' => $dash_array['separator_name'],
                'type' => 'dashboard',
                'level' => 'index'
            ));
        }


    }

    public function action_brands()
    {

        $month = $this->request->getGet('month');
        if ($month=='True'){
            $month=true;
        } else {
            $month=false;
        }

        $dashModel = new DashboardModel;
        $dash_array = $dashModel->startTypeDash($this->right,'brands',$month);

        return $this->view->render("dash/brands", array('brands' => $dash_array,
            'month' => $month,
            'type' => 'dashboard',
            'level' => 'brands'));

    }

    public function action_brand(){

        $month = $this->request->getGet('month');
        $id_b = $this->request->getGet('id_b');
        $user = $this->session->get("user");
        $dashModel = new DashboardModel;

        if ($month=='True'){
            $month=true;
        } else {
            $month=false;
        }
        $dash_array = $dashModel->startTypeDash($this->right,'brand', $month, $id_b);
        return $this->view->render("dash/brand", array(
            'to_json' => $dash_array['to_json'],
            'all_summ' => $dash_array['all_summ'],
            'all_summ_ss' => $dash_array['all_summ_ss'],
            'per_delta' => $dash_array['per_delta'],
            'per_ss' => $dash_array['per_ss'],
            'per_fot' => $dash_array['per_fot'],
            'fot' => $dash_array['fot'],
            'department' => $dash_array['department'],
            'json_fot' => $dash_array['json_fot'],
            'json_ss' => $dash_array['json_ss'],
            'to_table_fot' => $dash_array['to_table_fot'],
            'to_table_fot_per' => $dash_array['to_table_fot_per'],
            'to_table_ss' => $dash_array['to_table_ss'],
            'to_table_ss_per' => $dash_array['to_table_ss_per'],
            'to_table_taxi' => $dash_array['to_table_taxi'],
            'to_table_taxi_per' => $dash_array['to_table_taxi_per'],
            'separator_name' => $dash_array['separator_name'],
            'style' => $dash_array['style'],
            'ikon' => $dash_array['ikon'],
            'month' => $month,
            'id_b' => $id_b,
            'type' => 'dashboard',
            'level' => 'brand'
            ));
    }

    public function action_departments()
    {

        $month = $this->request->getGet('month');
        if ($month=='True'){
            $month=true;
        } else {
            $month=false;
        }



        $dashModel = new DashboardModel;
        $dash_array = $dashModel->startTypeDash($this->right,'departments',$month);

        return $this->view->render("dash/departments", array('brands' => $dash_array,
            'month' => $month,
            'type' => 'dashboard',
            'level' => 'departments'));

    }

    public function action_department()
    {

        $month = $this->request->getGet('month');
        $id_d = $this->request->getGet('id_d');
        $user = $this->session->get("user");


        $rigth = $this->checkRight($user,$id_d);

        if ($month=='True'){
            $month=true;
        } else {
            $month=false;
        }

        $dashModel = new DashboardModel;
        $dash_array = $dashModel->startTypeDash(array($rigth),'department',$month);
        return $this->view->render("dash/department", array(
                'to_json' => $dash_array['to_json'],
                'all_summ' => $dash_array['all_summ'],
                'all_summ_ss' => $dash_array['all_summ_ss'],
                'per_delta' => $dash_array['per_delta'],
                'per_ss' => $dash_array['per_ss'],
                'per_fot' => $dash_array['per_fot'],
                'fot' => $dash_array['fot'],
                'department' => $dash_array['department'],
                'json_fot' => $dash_array['json_fot'],
                'json_ss' => $dash_array['json_ss'],
                'to_table_fot' => $dash_array['to_table_fot'],
                'to_table_fot_per' => $dash_array['to_table_fot_per'],
                'to_table_ss' => $dash_array['to_table_ss'],
                'to_table_ss_per' => $dash_array['to_table_ss_per'],
                'to_table_taxi' => $dash_array['to_table_taxi'],
                'to_table_taxi_per' => $dash_array['to_table_taxi_per'],
                'separator_name' => $dash_array['separator_name'],
                'style' => $dash_array['style'],
                'ikon' => $dash_array['ikon'],
                'month' => $month,
                'id_d' => $id_d,
                 'type' => 'dashboard',
                'level' => 'department'));

    }



}