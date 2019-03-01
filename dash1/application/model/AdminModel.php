<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20/02/2019
 * Time: 14:03
 */

namespace application\model;

use \application\service\Service;
use \application\model\BaseModel;


class AdminModel extends BaseModel
{
    public function getRight($user) {
        $statement = self::$connection->prepare("SELECT * FROM user_role WHERE id_user = :id");
        $statement->bindValue(':id', $user['id'], \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }
    public function getDepartmentWithRight($user_id) {
        $statement = self::$connection->prepare("SELECT * FROM departments");

        $statement->execute();

        $deparments_arr = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $right_arr = $this->getRightOnDepartment($user_id);


        foreach($deparments_arr as $key=>$dep_item){
            $id_dep = $dep_item['id_department'];
            $right = 0;
            foreach ($right_arr as $right_item){

                if ($id_dep == $right_item['id_department']) {
                    $right = $right_item['right_u'];
                }
            }
            $deparments_arr[$key]['right'] = $right;
        }


        return $deparments_arr;
    }
    //todo изменить название на depatrments
    public function getRightOnDepartment($user_id){
        $statement = self::$connection->prepare("SELECT * FROM right_department WHERE user_id = :user_id and right_u=1");
        $statement->bindValue(':user_id', $user_id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function checkRightOnDepartment($user_id,$id_dep){
        $statement = self::$connection->prepare("SELECT * FROM right_department WHERE user_id = :user_id and id_department = :id_dep");
        $statement->bindValue(':user_id', $user_id, \PDO::PARAM_INT);
        $statement->bindValue(':id_dep', $id_dep, \PDO::PARAM_INT);
        $statement->execute();

        $array_return = $statement->fetch(\PDO::FETCH_ASSOC);

        return $array_return;
    }

    public function getAllUsers() {
        $statement = self::$connection->prepare(
            "SELECT id as uid, name as uname, login FROM user u
                      LEFT JOIN user_role r ON r.id_user=u.id
                      LEFT JOIN role rname ON r.id_role=rname.id_roll
        ");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function makeDepartmentList(){
        $statement = self::$connection->prepare("SELECT DISTINCT Department FROM dashboard_this_month");
        $statement->execute();
        $arr_departments = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement2 = self::$connection->prepare("CREATE TABLE IF NOT EXISTS departments (
              id_department INT AUTO_INCREMENT PRIMARY KEY,
              department_name VARCHAR(30) NOT NULL,
              relations INT(20) 
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
          AUTO_INCREMENT=1;
        ");
        $statement2->execute();

        foreach ($arr_departments as $department){
            $department = $department['Department'];
            $statement = self::$connection->prepare("INSERT IGNORE INTO departments (department_name) VALUE(:dep)");
            $statement->bindValue(':dep', $department);
            $statement->execute();
        }

        return $statement;
    }
    public function makeDishList(){
        $statement = self::$connection->prepare("SELECT DISTINCT DishName FROM sales_this_month");
        $statement->execute();
        $dish_departments = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement2 = self::$connection->prepare("CREATE TABLE IF NOT EXISTS dishs (
              id_dish INT AUTO_INCREMENT PRIMARY KEY,
              dish_name VARCHAR(60) NOT NULL UNIQUE,
              alias_name VARCHAR(60) NOT NULL,
              relations INT(20) 
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
          AUTO_INCREMENT=1;
        ");
        $statement2->execute();

        foreach ($dish_departments as $dish){
            $DishName = $dish['DishName'];

            $statement = self::$connection->prepare("INSERT IGNORE INTO dishs (dish_name) VALUE(:dish_name)");
            $statement->bindValue(':dish_name', $DishName);
            $statement->execute();
        }

        return $statement;
    }

    public function action_giveright($id_dep,$user_id,$right) {
        if ($right == 'true'){
            $right = true;
        } else {
            $right = false;
        }
        $statement1 = self::$connection->prepare("CREATE TABLE IF NOT EXISTS right_department (
              id_right INT AUTO_INCREMENT PRIMARY KEY,
              id_department VARCHAR(30) NOT NULL,
              user_id INT(6) NOT NULL,
              right_u BOOLEAN
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
          AUTO_INCREMENT=1;
        ");
        $statement1->execute();

        $statement2 = self::$connection->prepare(
            "INSERT INTO right_department (id_department, user_id, right_u) 
                        values (:id_dep, :user_id, :right_u) 
                        ON DUPLICATE KEY UPDATE right_u = :right_u
        ");
        $statement2->bindValue(':id_dep', $id_dep);
        $statement2->bindValue(':user_id', $user_id);
        $statement2->bindValue(':right_u', (bool)$right,\PDO::PARAM_BOOL);
        $statement2->execute();

        return  $statement2->execute();
    }

}