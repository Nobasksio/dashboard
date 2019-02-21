<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20/02/2019
 * Time: 13:47
 */

namespace application\controller;

use \application\service\Service;
use \application\controller\BaseController;
use \application\model\UserModel;
use \application\model\AdminModel;


class AdminController extends BaseController
{
    public function before() {
        parent::before();

        $admin_model = new AdminModel;

        $right_admin = $admin_model->getRight($this->session->get("user"));

        if ($this->session->get("user") && !($this->request->get("path") == "auth/logout") &&($right_admin['id_role'] !=1)) {
            return $this->view->render("error500");
        }

        return true;
    }

    public function action_users(){
        $admin_model = new AdminModel;

        $user_arr = $admin_model->getAllusers();

        print_r($user_arr);

        return $this->view->render("admin/users", array('users' => $user_arr));
    }

    public function action_user(){
        $user_id = $this->request->getGet('user_id');
        $user_model = new UserModel;

        $user_arr = $user_model->getUserById($user_id);
        $admin_model = new AdminModel();
        $departments = $admin_model->makeDepartmentList();
        $departments = $admin_model->getDepartmentWithRight($user_id);

        return $this->view->render("admin/user", array('user' => $user_arr,
            'depatments'=>$departments,
            'user_id'=>$user_id
            ));
    }
    public function action_giveright(){
        $right = $this->request->getGet('right');
        $id_dep = $this->request->getGet('id_dep');
        $user_id = $this->request->getGet('user_id');
        print "$user_id";
        $admin_model = new AdminModel;

        $user_arr = $admin_model->action_giveright($id_dep,$user_id,$right);


        return $user_arr;
    }


}