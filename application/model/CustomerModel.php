<?php

namespace application\model;

use \application\service\Service;
use \application\model\BaseModel;

class CustomerModel extends BaseModel {

	public function getCustomers() {
		$statement = self::$connection->prepare("SELECT * FROM customers");
		$statement->execute();   

		return $statement->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getCustomerById($id) {
		$statement = self::$connection->prepare("SELECT * FROM customers WHERE id = :id");
		$statement->bindValue(':id', $id, \PDO::PARAM_INT);
		$statement->execute();

		return $statement->fetchAll(\PDO::FETCH_ASSOC);
	}	

	public function createNewCustomer() {
		$statement = self::$connection->prepare("INSERT INTO customers ...");
		$statement->bindValue(':id', $id, \PDO::PARAM_INT);
		$statement->execute();

		return $statement->fetchAll(\PDO::FETCH_ASSOC);
	}		
}