<?php

namespace application\controller;

use \application\service\Service;
use \application\controller\BaseController;
use \application\model\CategoryModel;
use \application\model\BasketModel;
use \application\model\ProductModel;
use \application\model\AdminModel;

class ProductController extends BaseController {


    public function action_all()
    {
        $id_b = $this->request->getGet('id_b');
        $id_p = $this->request->getGet('id_p');
        $id_d = $this->request->getGet('id_d');
        $user = $this->session->get("user");

        if (isset($id_b)){
            $rigth = $this->checkRight($user, $id_d);
        } else {
            $rigth = $this->checkRight($user, $id_d);
        }

        $month = $this->request->getGet('month');
        if ($month == 'True') {
            $month = true;
        } else {
            $month = false;
        }

        $productModel = new ProductModel;
        $dash_array = $productModel->getAllProducts(array($id_d), $month);

        return $this->view->render("product/index", array('products' => $dash_array['products'],
            'month' => $month,
            'type' => 'products',
            'level' => 'all'));
    }
    public function action_one()
    {

        $admin_model = new adminmodel();
        $admin_model->makeDishList();

        $id_b = $this->request->getGet('id_b');
        $id_p = $this->request->getGet('id_p');
        $id_d = $this->request->getGet('id_d');
        $user = $this->session->get("user");

        if (isset($id_b)){
            $rigth = $this->checkRight($user, $id_d);
        } else {
            $rigth = $this->checkRight($user, $id_d);
        }

        $month = $this->request->getGet('month');
        if ($month == 'True') {
            $month = true;
        } else {
            $month = false;
        }

        $productModel = new ProductModel;


        $dash_array = $productModel->getOneProduct($id_d,$id_p, $month);

        return $this->view->render("product/index", array('ss' => $dash_array['ss'],
            'price' => $dash_array['price'],
            'separator_name' => $dash_array['separator_name'],
            'count' => $dash_array['count'],
            'delta_ss' => $dash_array['delta_ss'],
            'month' => $month,
            'type' => 'products',
            'level' => 'all'));
    }
	public function action_category() {

		$categoryModel = new CategoryModel();
		$categories = $categoryModel->getAllProducts($group);

		$this->view->render("product/index", [
			"categories" => $categories
		]);
	}


}