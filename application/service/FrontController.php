<?php

namespace application\service;

use \application\service\Service;

class FrontController {

	protected 
		$view,
		$config,
		$request,
	    $history;
	
	public function __construct() {
		$this->view = Service::view();
		$this->config = Service::config();
		$this->request = Service::request();
        $this->history = Service::history();
	}

	protected function before() {

	}

	/**
	 * /?path=controller/action
	 * Ex: /?path=home/index
	 */
	public function run() {
		
		if (is_null($this->request->get("path"))) {
			throw new \Exception("Wrong path");
		}

		list($controller, $action) = explode("/", $this->request->get("path"));

		//HomeController
		$class = '\\application\\controller\\'.ucfirst($controller)."Controller";

		if (!class_exists($class)) {
			return $this->view->render("error500");
		}

		$controller = new $class;

		if (!method_exists($controller, "action_".$action)) {
			return $this->view->render("error500");
		}

		return $controller->{"action_".$action}();
	}
}