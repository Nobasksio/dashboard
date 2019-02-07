<?php

namespace application\controller;

use \application\service\Service;
use \application\service\FrontController;

class BaseController extends FrontController {

    protected function before() {
		$this->view->addGlobal('title', $this->config->get("title"));
		$this->view->addGlobal('user', $this->session->get("user"));
		return true;
	}
}