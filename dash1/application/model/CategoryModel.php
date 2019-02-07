<?php

namespace application\model;

use \application\service\Service;
use \application\model\BaseModel;

class CategoryModel extends BaseModel {

	public function getAllCategories() {
		$statement = self::$connection->prepare("SELECT * FROM category");
		$statement->execute();

		return $statement->fetchAll(\PDO::FETCH_ASSOC);
	}	

	public function getCategoryWithProducts($id) {
		$statement = self::$connection->prepare("
			SELECT * 
			FROM 
				category 
			LEFT JOIN 
				goods ON (goods.category_id = category.id)
			WHERE 
				category.id = :id 
			AND 
				goods.status = :status
		");
		$statement->bindValue(':id', $id, \PDO::PARAM_INT);
		$statement->bindValue(':status', GoodsModel::STATUS_ACTIVE, \PDO::PARAM_INT);
		$statement->execute();

		return $statement->fetchAll(\PDO::FETCH_ASSOC);
	}

}