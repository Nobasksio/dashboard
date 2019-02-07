<?php

namespace application\model;

use \application\service\Service;
use \application\model\BaseModel;
use \application\model\GoodsModel;
use \application\model\BasketModel;

class OrderModel extends BaseModel {	

	public function create($user_id, $id, $amount) {
		$statement = self::$connection->prepare("INSERT INTO orders (user_id, amount, good_id, created) VALUES (:user_id, :amount, :good_id, :created)");
		$statement->bindValue(':user_id', $user_id, \PDO::PARAM_INT);
		$statement->bindValue(':good_id', $id, \PDO::PARAM_INT);
		$statement->bindValue(':amount', $amount, \PDO::PARAM_INT);
		$statement->bindValue(':created', date("Y-m-d"));
		$statement->execute();

		$basketModel = new BasketModel;
		$items = $basketModel->getByUserIdAndGoodId($user_id, $id);

		$statement = self::$connection->query("SELECT LAST_INSERT_ID()");
		$lastId = $statement->fetchColumn();

		foreach($items as $item) {
			$basketModel->updateOrderId($item["id"], $lastId);
		}

		return true;
	}

}