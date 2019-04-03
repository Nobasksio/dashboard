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
use \application\model\ProductModel;
use \application\model\DepartmentModel;
use \application\model\DownloadModel;


class AdminController extends BaseController
{
    public function before() {
        parent::before();
        if (!$this->session->get("user")) {
            $this->request->redirect("?path=auth/index");
        }

        $admin_model = new AdminModel;

        $right_admin = $admin_model->getRight($this->session->get("user"));

        if (!($this->session->get("user")) || ($right_admin['id_role'] !=1)) {
            return $this->view->render("error500");
        }

        return true;
    }
    public function action_dowload2(){
        $admin_model = new DownloadModel;

        $user_arr = $admin_model->test_iiko();
        return $this->view->render("admin/download2", array('users' => $user_arr));
    }
    public function action_dowload(){
        $admin_model = new DownloadModel;

        $user_arr = $admin_model->dowloadSaleIiko('20.03.2019','30.03.2019');
        return $this->view->render("admin/download", array('users' => $user_arr));
    }

    public function action_users(){
        $admin_model = new AdminModel;

        $user_arr = $admin_model->getAllusers();
        return $this->view->render("admin/users", array('users' => $user_arr));
    }



    public function action_user(){
        $user_id = $this->request->getGet('user_id');
        $user_model = new UserModel;

        $user_arr = $user_model->getUserById($user_id);
        $admin_model = new AdminModel();
        //$admin_model->makeDepartmentList();
        //$admin_model->makeCategoryList();
        //$admin_model->makeDepartmentsCategoryRelations();
        $departments = $admin_model->getDepartmentWithRight($user_id);

        return $this->view->render("admin/user", array('user' => $user_arr,
            'depatments'=>$departments,
            'user_id'=>$user_id
            ));
    }
    public function action_departments(){
        $admin_model = new AdminModel;

        $departments_arr = $admin_model->getAllDepartments();
        return $this->view->render("admin/departments", array('departments' => $departments_arr));
    }

    public function action_department(){
        $depart_id = $this->request->getGet('depart_id');
        $admin_model = new AdminModel();
        //$admin_model->setCategoryAll();
        //$admin_model->makeCategoryList();

        //$admin_model->makeDepartmentsCategoryRelations();
        $DepartmentModel = new DepartmentModel;

        $departments_arr = $DepartmentModel->getDepartmentInfo($depart_id);
        $productModel = new ProductModel;
        //$product = $productModel->getAllProducts($depart_id,$this->date_start, $this->date_finish);
        $category = $productModel->getAllCategory($depart_id,$this->date_start, $this->date_finish);

        return $this->view->render("admin/department", array(
            'department' => $departments_arr,
            //'product' => $product['products'],
                'category' => $category
            )
        );
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
    public function action_SetViewGroup(){

        #todo надо сделать проверку прав
        $status = $this->request->getGet('status');
        $id_dep = $this->request->getGet('id_dep');
        $id_category = $this->request->getGet('id_category');

        $admin_model = new AdminModel;

        $user_arr = $admin_model->SetViewGroup($id_dep,$id_category,$status);


        return $user_arr;
    }
    public function action_setaliasCategory(){

        #todo надо сделать проверку прав
        $alias = $this->request->getGet('alias');
        $id_dep = $this->request->getGet('id_dep');
        $id_category = $this->request->getGet('id_category');

        $admin_model = new AdminModel;

        $user_arr = $admin_model->SetAliasCategory($id_category,$alias);


        return $user_arr;
    }


}