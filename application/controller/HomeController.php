<?php

namespace application\controller;

use \application\service\Service;
use \application\service\FrontController;

class HomeController extends FrontController {

	protected function before() {
		parent::before();
		return true;
	}

	public function action_index() {
		return $this->view->render("home/index", [
			"title"=>$this->config->get("title"),
			"version"=>$this->config->get("version")
		]);
	}

	public function action_show() {
		$this->view->render("home/index", [
			"title"=>$this->config->get("title"),
			"version"=>$this->config->get("version"),
		]);
	}	

}