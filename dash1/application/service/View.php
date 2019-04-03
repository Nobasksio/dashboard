<?php

namespace application\service;

class View {

	private $tEngine;

	public function __construct(\Twig_Environment $engine) {
		$this->tEngine = $engine;
	}	

	public function addGlobal($key, $value) {
		$this->tEngine->addGlobal($key, $value);
	}

	public function render($template, $params = []) {
		$template = $this->tEngine->loadTemplate($template.".tmpl");
		echo $template->render($params);
	}
}