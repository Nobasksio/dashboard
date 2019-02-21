<?php

namespace application\service;

class Config {
	protected $path;
	protected $config;

	public function __construct() {
		$this->path = BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . "config.php";
		$this->config = include $this->path;
	}

	public function get($item) {
		return $this->config[$item];
	}

}