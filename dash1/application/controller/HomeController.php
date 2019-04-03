<?php

namespace application\controller;

use \application\service\Service;
use \application\controller\BaseController;

class HomeController extends BaseController {

	protected function before() {
		parent::before();
		return true;
	}

	public function action_index() {
        if (!$this->session->get('user')) {
            $this->request->redirect("?path=auth/index");
        } else {
            $this->request->redirect("?path=dashboard/index");
        }
	}

	public function action_about() {
		return $this->view->render("home/index");
	}

}