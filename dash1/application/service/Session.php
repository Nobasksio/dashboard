<?php

namespace application\service;

class Session {

	public function start() {
		session_start();
	}

	public function get($key) {
		return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
	}

	public function set($key, $value) {
		$_SESSION[$key] = $value;
	}	

	public function destroy() {
		session_destroy();
	}
}