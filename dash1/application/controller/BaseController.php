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

	protected function checkRight($user,$id_d){
        $admin_model = new AdminModel();
        $rigth = $admin_model->checkRightOnDepartment($user['id'],$id_d);

        if (($rigth==false) or ($rigth['right_u']==0)){

            return false;
        }
        return $rigth;

    }
}