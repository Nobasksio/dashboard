<?php

namespace application\model;

use \application\service\Service;

abstract class BaseModel {

	protected static $connection = null;

	public function __construct() {

		if (!self::$connection) {
			$db = Service::config()->get("db");

			self::$connection = new \PDO($db["dsn"], $db["user"], $db["password"]);
			self::$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
	}
}