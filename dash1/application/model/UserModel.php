<?php

namespace application\model;

use \application\service\Service;
use \application\model\BaseModel;

class UserModel extends BaseModel {

	public function getUserById($id) {
		$statement = self::$connection->prepare("SELECT * FROM user WHERE id = :id");
		$statement->bindValue(':id', $id, \PDO::PARAM_INT);
		$statement->execute();

		return $statement->fetch(\PDO::FETCH_ASSOC);
	}	

	public function getUserByNameAndPassword($login, $password) {
		$statement = self::$connection->prepare("SELECT * FROM user WHERE login = :login AND password=:password");
		$statement->bindValue(':login', $login, \PDO::PARAM_STR);
		$statement->bindValue(':password', md5($password), \PDO::PARAM_STR);
		$statement->execute();

		return $statement->fetch(\PDO::FETCH_ASSOC);
	}
	
	public function createUser($name, $login, $password) {
		$statement = self::$connection->prepare("INSERT INTO user (name, login, password) VALUES (:name, :login, :password)");
		$statement->bindValue(':name', $name, \PDO::PARAM_STR);
		$statement->bindValue(':login', $login, \PDO::PARAM_STR);
		$statement->bindValue(':password', md5($password), \PDO::PARAM_STR);
		$statement->execute();

        $id = self::$connection->lastInsertId();


		return array('id'=>$id );
	}
}