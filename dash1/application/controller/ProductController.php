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
        $dash_array = $productModel->getAllProducts($id_d,$this->date_start, $this->date_finish, $month,$id_b);

        return $this->view->render("product/all", array('products' => $dash_array['products'],
            'id_d' =>$id_d,
            'month' => $month,
            'date_start' => $this->date_start,
            'date_finish' => $this->date_finish,
            'type' => 'product',
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
            $rigth = $this->checkRight($user, $id_b);
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
        //сделать чтобы работало в рамках всего бренда
        $dash_array = $productModel->getOneProduct($id_d,$id_p,$this->date_start, $this->date_finish, $month);

        $dash_array2 = $productModel->getRelations($id_d,$id_p,$this->date_start, $this->date_finish, $month);


        return $this->view->render("product/one", array('ss' => $dash_array['ss'],
            'price' => $dash_array['price'],
            'separator_name' => $dash_array['separator_name'],
            'count' => $dash_array['count'],
            'count_order' => @$dash_array2['count_order'],
            'delta_ss' => $dash_array['delta_ss'],
            'relations'=>@$dash_array2['name_arr'],
            'department' => $dash_array['department'],
            'product_name' => $dash_array['product_name'],
            'date_start' => $this->date_start,
            'date_finish' => $this->date_finish,
            'month' => $month,
            'id_p'=>$id_p,
            'id_b'=>$id_b,
            'id_d'=>$id_d,
            'type' => 'product',
            'level' => 'one'));
    }
	public function action_category() {

		$categoryModel = new CategoryModel();
		$categories = $categoryModel->getAllProducts($group);

		$this->view->render("product/index", [
			"categories" => $categories
		]);
	}


}