<?php

namespace application\controller;

use \application\service\Service;
use \application\controller\BaseController;
use \application\model\DashboardModel;

class DashboardController extends BaseController {

	public function before() {

		if (!$this->session->get("user")) {
			$this->request->redirect("?path=auth/index");
		}

		parent::before();

		return true;
	}

    public function action_index() {

	    $dashModel = new DashboardModel;

        if ($this->request->getGet('type')){
            $type = $this->request->getGet('type');
            $dash_array = $dashModel->startTypeDash($type);
        } else {
            $dash_array = $dashModel->startDash();
        }




        return $this->view->render("dash/index", array(
            'to_json' => $dash_array['to_json'],
            'all_summ' => $dash_array['all_summ'],
            'per_delta' => $dash_array['per_delta']
        ));


    }
    public function action_brand() {

        $dashModel = new DashboardModel;

        $dash_array = $dashModel->startTypeDash('brand');

        return $this->view->render("dash/brand", array('brands'=>$dash_array));


    }
    public function action_department() {

        $dashModel = new DashboardModel;

        $dash_array = $dashModel->startTypeDash('department');

        return $this->view->render("dash/department", array('brands'=>$dash_array));


    }



}