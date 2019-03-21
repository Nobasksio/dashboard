<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20/03/2019
 * Time: 13:50
 */

namespace application\controller;

use application\model\AdminModel;
use \application\service\Service;
use \application\controller\BaseController;
use \application\model\DashboardModel;
use \application\model\WaitersModel;

class WaitersController extends BaseController
{
    public function before()
    {

        if (!$this->session->get("user")) {
            $this->request->redirect("?path=auth/index");
        }

        parent::before();

        $admin_model = new AdminModel();

        $admin_model->makeWaitersList();
        $user = $this->session->get("user");

        $rigth_arr = $admin_model->getRightOnDepartment($user['id']);
        //$admin_model->makeDepartmentList();

        if (count($rigth_arr) == 0) {
            return $this->view->render("dash/wait", array('type' => 'wait'));
        } else {
            $this->right = $rigth_arr;
        }

        return true;
    }
    public function action_all()
    {

        $month = $this->request->getGet('month');
        if ($month == 'True') {
            $month = true;
        } else {
            $month = false;
        }
        $id_d = $this->request->getGet('id_d');
        $dashModel = new WaitersModel;
        $dash_array = $dashModel->getWaitersInfo($id_d,  $this->date_start, $this->date_finish, $month);


        return $this->view->render("waiters/all", array('waiter_arr' => $dash_array['waiter_arr'],
            'department'=> $dash_array['department'],
            'month' => $month,
            'type' => 'waiters',
            'id_d' => $id_d,
            'date_start' => $this->date_start,
            'date_finish' => $this->date_finish,
            'level' => 'all'));
    }
    public function action_one()
    {

        $month = $this->request->getGet('month');
        if ($month == 'True') {
            $month = true;
        } else {
            $month = false;
        }
        $id_d = $this->request->getGet('id_d');
        $id_waiter = $this->request->getGet('id_waiter');
        $dashModel = new WaitersModel;
        $dash_array = $dashModel->getOneWaiterInfo($id_d, $id_waiter, $this->date_start, $this->date_finish, $month);


        return $this->view->render("waiters/one", array('waiter_arr' => $dash_array['waiter_arr'],
            'department'=> $dash_array['department'],
            'month' => $month,
            'type' => 'waiters',
            'id_d' => $id_d,

            'date_start' => $this->date_start,
            'date_finish' => $this->date_finish,
            'level' => 'all'));
    }

}