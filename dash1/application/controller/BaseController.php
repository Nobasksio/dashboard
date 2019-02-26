<?php

namespace application\controller;


use \application\service\Service;
use \application\service\FrontController;
use \application\model\AdminModel;

class BaseController extends FrontController {

    protected function before() {
		$this->view->addGlobal('title', $this->config->get("title"));
		$this->view->addGlobal('user', $this->session->get("user"));


		return true;
	}

	protected function checkRight($user,$id_department,$type='dep'){
        $admin_model = new AdminModel();

        if ($type=='dep') {
            $rigth = $admin_model->checkRightOnDepartment($user['id'], $id_department);

            if (($rigth == false) or ($rigth['right_u'] == 0)) {

                return false;
            }
        } else {

        }
        return $rigth;

    }


}