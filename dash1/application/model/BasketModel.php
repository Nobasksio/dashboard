<?php

namespace application\model;

use \application\service\Service;
use \application\model\BaseModel;
use \application\model\GoodsModel;

class BasketModel extends BaseModel {	

	public function create($user_id, $id) {

		$goodsModel = new GoodsModel;
		$good = $goodsModel->getById($id);

		$statement = self::$connection->prepare("INSERT INTO basket (user_id, good_id, price) VALUES (:user_id, :good_id, :price)");
		$statement->bindValue(':user_id', $user_id, \PDO::PARAM_INT);
		$statement->bindValue(':good_id', $id, \PDO::PARAM_INT);
		$statement->bindValue(':price', $good["price"], \PDO::PARAM_STR);
		$statement->execute();

		return true;
	}

	public function getUserItems($id) {
		$statement = self::$connection->prepare("
			SELECT 
				basket.id as basket_id,
				goods.id as id,
				goods.name as name,
				goods.price as price
			FROM 
				basket 
			JOIN 
				goods ON (goods.id = basket.good_id)
			WHERE 
				basket.user_id = :id 
			AND 
				basket.order_id IS NULL
		");
		$statement->bindValue(':id', $id, \PDO::PARAM_INT);
		$statement->execute();

		return $statement->fetchAll(\PDO::FETCH_ASSOC);		
	}

	public function deleteByBasketId($id) {
		$statement = self::$connection->prepare("DELETE FROM basket WHERE id = :id");
		$statement->bindValue(':id', $id, \PDO::PARAM_INT);
		$statement->execute();

		return true;		
	}

	public function getByUserIdAndGoodId($user_id, $id) {
		$statement = self::$connection->prepare("SELECT * FROM basket WHERE user_id = :user_id AND good_id=:good_id");
		$statement->bindValue(':user_id', $user_id, \PDO::PARAM_INT);
		$statement->bindValue(':good_id', $id, \PDO::PARAM_INT);
		$statement->execute();

		return $statement->fetchAll(\PDO::FETCH_ASSOC);		
	}

	public function updateOrderId($id, $order_id) {
		$statement = self::$connection->prepare("
			UPDATE 
				basket 
			SET 
				order_id = :order_id 
			WHERE 
				id = :id
		");
		$statement->bindValue(':order_id', $order_id, \PDO::PARAM_INT);
		$statement->bindValue(':id', $id, \PDO::PARAM_INT);
		$statement->execute();

		return true;		
	}

}