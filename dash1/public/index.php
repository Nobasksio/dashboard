<?php

define("BASE_PATH", dirname(dirname(__FILE__)));
define("APP", dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."/application");

require_once BASE_PATH.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

try {

	$loader = new \Twig_Loader_Filesystem(APP.DIRECTORY_SEPARATOR.'view');
	$twig = new \Twig_Environment($loader);

	/**
	 * Supporting objects
	 */
	$session = new \application\service\Session();
	$session->start();

	$view = new \application\service\View($twig);
	$config = new \application\service\Config();
	$request = new \application\service\Request();

	/**
	 * Define singleton
	 */
	\application\service\Service::set("session", $session);
	\application\service\Service::set("view", $view);
	\application\service\Service::set("config", $config);
	\application\service\Service::set("request", $request);

	/**
	 * Run application
	 */
	$app = new \application\service\FrontController();
	$app->run();


} catch (Exception $e) {
	die ('ERROR: ' . $e->getMessage());
}
?>
