<?php

namespace application\controller;

use \application\service\Service;
use \application\service\FrontController;
use \application\model\CustomerModel;

class ProductController extends FrontController {

	public function action_index() {

		$customer = new CustomerModel();
		$customerCollection = $customer->getCustomerById(2);

		$this->view->render("product/index", [
			"title"					=> $this->config->get("title"),
			"customerCollection"	=> $customerCollection
		]);

	}

	public function action_new() {

		if (!$this->request->isPost()) {
			return $this->view->render("error500");				
		}

		return $this->view->render("product/new", [
			"title"=>$this->config->get("title"),
			"name"=>$this->request->get("name"), //$_POST["name"]
		]);		
	}	

}