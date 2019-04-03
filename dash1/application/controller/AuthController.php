<?php

namespace application\controller;

use \application\service\Service;
use \application\controller\BaseController;
use \application\model\UserModel;

/**
 * /?path=auth/{action}
 */
class AuthController extends BaseController {

	public function before() {
		parent::before();

		if ($this->session->get("user") && !($this->request->get("path") == "auth/logout")) {
			$this->request->redirect("?path=dashboard/index");
		}

		return true;
	}

	public function action_index() {


		return $this->view->render("auth/index2",array('type'=>$this->request->get("type")));
	}	

	/**
	 * /?path=auth/login
	 */
	public function action_login() {

		if (!$this->request->isPost()) {
            $this->request->redirect("?path=auth/index");
		}

		$login = $this->request->getPost("login");
		$password = $this->request->getPost("password");

		$userModel = new UserModel();
		$user = $userModel->getUserByNameAndPassword($login, $password);

		if (!$user) {

            return $this->view->render("auth/index2",array('error'=>'Пользователь с такими учетными данными не найден'));
			//$this->request->redirect("?path=auth/index2",array('error'=>'Пользователь с такими учетными данными не найден'));
		}

		$this->session->set("user", $user);
		$this->request->redirect("?path=dashboard/departments");
	}

	/**
	 * /?path=auth/signup
	 */
	public function action_signup() {
		return $this->view->render("auth/signup");
	}

	/**
	 * /?path=auth/register
	 */
	public function action_register() {

		if (!$this->request->isPost()) {
			$this->request->redirect("?path=auth/signup&message=Post is required");
		}

		$firstname = $this->request->getPost("firstname");
		$login = $this->request->getPost("login");
		$password = $this->request->getPost("password");

		if (!$login || !$password) {
			$this->request->redirect("?path=auth/signup&message=Login and password are required");
		}

		$userModel = new UserModel();
        $user_id = $userModel->createUser($firstname, $login, $password);

		$this->session->set("user", array('id'=>$user_id['id'],
            'login'=>$login));
		$this->request->redirect("?path=dashboard/departments");
	}	

	/**
	 * /?path=auth/logout
	 */
	public function action_logout() {
		$this->session->destroy();
		$this->request->redirect("?path=auth/index");
	}	
}