<?php

namespace application\model;

use \application\service\Service;
use \application\model\BaseModel;

class GoodsModel extends BaseModel {	

	const STATUS_ACTIVE = 10;
	const STATUS_INACTIVE = 20;

	public function getById($id) {
		$statement = self::$connection->prepare("SELECT * FROM goods WHERE id = :id");
		$statement->bindValue(':id', $id, \PDO::PARAM_INT);
		$statement->execute();

		return $statement->fetch(\PDO::FETCH_ASSOC);
	}

}