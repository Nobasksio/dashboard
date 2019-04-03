<?php

namespace application\controller;

use \application\service\Service;
use \application\controller\BaseController;
use \application\model\BasketModel;
use \application\model\OrderModel;

/**
 * /?path=basket/{action}
 */
class BasketController extends BaseController {

	public function action_index() {

		$user = $this->session->get("user");
		
		$basketModel = new BasketModel();
		$items = $basketModel->getUserItems($user["id"]);

		return $this->view->render("basket/index", [
			"items"=>$items
		]);
	}	

	public function action_delete() {
		$id = $this->request->get("id");
		
		$basketModel = new BasketModel();
		$basketModel->deleteByBasketId($id);

		$this->request->redirect("/?path=basket/index");
	}

	public function action_increase_amount() {
		$amount = $this->session->get("amount");
		$id = $this->request->get("id");
		
		$basketModel = new BasketModel();
		$basketModel->updateAmount($id, $amount);

		$this->request->redirect("/?path=basket/index");
	}	

	public function action_order() {
		$user = $this->session->get("user");
		$good_id = $this->request->get("id");
		$amount = $this->request->get("amount");
		
		$orderModel = new OrderModel();
		$orderModel->create($user["id"], $good_id, $amount);

		$this->request->redirect("/?path=basket/index");
	}	
}