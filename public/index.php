<?php
session_start();
define("BASE_PATH", dirname(dirname(__FILE__)));
define("APP", dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."/application");

require_once APP.DIRECTORY_SEPARATOR.'service'.DIRECTORY_SEPARATOR.'Autoloader.php';
require_once APP.DIRECTORY_SEPARATOR.'service'.DIRECTORY_SEPARATOR.'Twig'.DIRECTORY_SEPARATOR.'Autoloader.php';

try {

	\Twig_Autoloader::register();
	\application\service\Autoloader::register();

	$loader = new \Twig_Loader_Filesystem(APP.DIRECTORY_SEPARATOR.'view');
	$twig = new \Twig_Environment($loader);

	/**
	 * Supporting objects
	 */
	$view = new \application\service\View($twig);
	$config = new \application\service\Config();
	$request = new \application\service\Request();
    $history = new \application\service\History();

	/**
	 * Define singleton
	 */
	\application\service\Service::set("view", $view);
	\application\service\Service::set("config", $config);
	\application\service\Service::set("request", $request);
    \application\service\Service::set("history", $history);

	/**
	 * Run application
	 */
	$app = new \application\service\FrontController();
	$app->run();


} catch (Exception $e) {
	die ('ERROR: ' . $e->getMessage());
}
?>
