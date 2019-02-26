<?php

namespace application\controller;

use \application\service\Service;
use \application\controller\BaseController;
use \application\model\CategoryModel;
use \application\model\BasketModel;
use \application\model\ProductModel;

class ProductController extends BaseController {

	public function action_category() {

		$categoryModel = new CategoryModel();
		$categories = $categoryModel->getAllProducts($group);

		$this->view->render("product/index", [
			"categories" => $categories
		]);
	}


}