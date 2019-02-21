<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 28/01/2019
 * Time: 21:39
 */

namespace application\model;

use \application\service\Service;
use \application\model\BaseModel;

class UserModel extends BaseModel
{
    protected $id;
    protected $login;
    protected $name;
    protected $pass;

    public function getUserByLoginAndPass($login,$password) {

        $statement = self::$connection->prepare("SELECT * FROM user WHERE login = :login and pass = :pass");
        $statement->bindValue(':login', $login, \PDO::PARAM_INPUT_OUTPUT);
        $statement->bindValue(':pass', $password, \PDO::PARAM_INPUT_OUTPUT);
        $statement->execute();

        $UserInfo = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $_SESSION['id'] = $UserInfo[0]['id'];

        $this->name = $UserInfo[0]['name'];
        $this->id = $UserInfo[0]['id'];
        $this->login = $UserInfo[0]['login'];
        $this->pass = $UserInfo[0]['pass'];

        return $UserInfo;


    }
    public function getUserById($id)
    {

        $statement = self::$connection->prepare("SELECT * FROM user WHERE id = :id ");
        $statement->bindValue(':id', $id, \PDO::PARAM_INPUT_OUTPUT);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getUser_info(){

        if (isset($_SESSION['id'])) {
            $UserInfo = array(
                'name' => $this->name,
                'id' => $this->id,
                'pass' => $this->pass,
                'login' => $this->login
            );
            return $UserInfo;
        } else {
            return False;
        }
}

}