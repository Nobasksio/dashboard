<?php

namespace application\controller;

use \application\service\Service;
use \application\controller\BaseController;
use \application\model\CategoryModel;
use \application\model\BasketModel;
use \application\model\GoodsModel;

class ProductController extends BaseController {

	public function action_index() {

		$categoryModel = new CategoryModel();
		$categories = $categoryModel->getAllCategories();

		$this->view->render("product/index", [
			"categories" => $categories
		]);
	}


}