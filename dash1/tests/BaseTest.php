<?php

namespace tests;

define("BASE_PATH", dirname(dirname(__FILE__)));
define("APP", dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."/application");

use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase{

	protected function setUp() {
		$loader = new \Twig_Loader_Filesystem(APP.DIRECTORY_SEPARATOR.'view');
		$twig = new \Twig_Environment($loader);
		
		$view = new \application\service\View($twig);
		$config = new \application\service\Config();
		$request = new \application\service\Request();

		\application\service\Service::set("view", $view);
		\application\service\Service::set("config", $config);
		\application\service\Service::set("request", $request);
	}	

	public function request($method, $controller, $action)
	{
		// Capture STDOUT
		ob_start();

		$controller->$action();
		
		// Return STDOUT
		return ob_get_clean();
	}

	protected function tearDown() {

	}

}
