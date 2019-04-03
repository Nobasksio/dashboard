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
use \Curl\Curl;


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

    public function getAllDepartments() {
        $statement = self::$connection->prepare(
            "SELECT * FROM departments
        ");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllDishs() {
        $statement = self::$connection->prepare(
            "SELECT * FROM dishs
        ");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllCategory() {
        $statement = self::$connection->prepare(
            "SELECT * FROM category
        ");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function makeDepartmentList(){
        $statement = self::$connection->prepare("SELECT DISTINCT Department FROM dashboard_this_years_test");
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

    public static function addDepartment($department){
        try {
            $statement = self::$connection->prepare("INSERT IGNORE INTO departments (department_name) VALUE(:dep)");
            $statement->bindValue(':dep', $department);
            $statement->execute();
            $dbo = self::$connection;
            return $dbo->lastInsertId();
        } catch(PDOExecption $e) {
            return false;
        }
    }
    public static function addCategory($Group,$Group2){
        try {
            $statement = self::$connection->prepare("INSERT IGNORE INTO category (category_name) VALUE(:category_name)");
            $statement->bindValue(':category_name', $Group);
            $statement->execute();
            $dbo = self::$connection;
            return $dbo->lastInsertId();
        } catch(PDOExecption $e) {
            return false;
        }
    }
    public static function addDish($dish_name){
        try {
            $statement = self::$connection->prepare("INSERT IGNORE INTO dishs (dish_name) VALUE(:dish_name)");
            $statement->bindValue(':dish_name', $dish_name);
            $statement->execute();
            $dbo = self::$connection;
            return $dbo->lastInsertId();
        } catch(PDOExecption $e) {
            return false;
        }
    }
    public static function UpdateDish($id_dish,$name_col,$new_value){
        $statement2 = self::$connection->prepare(
            "UPDATE dishs SET $name_col=$new_value
                        where `id_dish`=$id_dish");
        $statement2->execute();
    }
    public static function UpdateCategory($category_id,$name_col,$new_value){
        $statement2 = self::$connection->prepare(
            "UPDATE category SET $name_col=$new_value
                        where `id_category`=$category_id");
        $statement2->execute();
    }


    public function makeCategoryList(){
        $statement = self::$connection->prepare("SELECT DISTINCT `group` FROM sales");
        $statement->execute();
        $dish_departments = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement2 = self::$connection->prepare("CREATE TABLE IF NOT EXISTS category (
              id_category INT AUTO_INCREMENT PRIMARY KEY,
              category_name VARCHAR(60) NOT NULL UNIQUE,
              alias_name VARCHAR(60) NOT NULL,
              status tinyint(1) default 1,
              relations INT(20) 
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
          AUTO_INCREMENT=1;
        ");
        $statement2->execute();

        foreach ($dish_departments as $category){

                $category_name = $category['group'];

            $statement = self::$connection->prepare("INSERT IGNORE INTO category (category_name) VALUE(:category_name)");
            $statement->bindValue(':category_name', $category_name);
            $statement->execute();
        }

        return $statement;
    }

    public function setCategoryAll(){
        $statement2 = self::$connection->prepare(
            "UPDATE sales s SET s.group=s.group0 
                        where `group` is NULL");
        $statement2->execute();
    }

    public function makeDepartmentsCategoryRelations(){
        $statement = self::$connection->prepare("
            SELECT DISTINCT 
                s.group, c.id_category, d.department_name,
                d.id_department
            FROM 
                sales s
            LEFT JOIN category c ON s.group=c.category_name
            LEFT JOIN departments d ON s.Department=d.department_name
            ");
        $statement->execute();
        $Categoty = $statement->fetchAll(\PDO::FETCH_ASSOC);


        $statement2 = self::$connection->prepare("CREATE TABLE IF NOT EXISTS category_for_department (
              id INT AUTO_INCREMENT PRIMARY KEY,
              category_id INT NOT NULL,
              department_id INT NOT NULL,
              status tinyint(1) default 1,
              UNIQUE KEY `combined` (`category_id`,`department_id`) 
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
          AUTO_INCREMENT=1;
        ");
        $statement2->execute();

        foreach ($Categoty as $category){
            $category_id = $category['id_category'];
            $id_department = $category['id_department'];

            $statement = self::$connection->prepare("INSERT IGNORE INTO category_for_department (category_id,department_id) VALUE(:category_id,:department_id)");
            $statement->bindValue(':category_id', $category_id);
            $statement->bindValue(':department_id', $id_department);
            $statement->execute();
        }

        return $statement;
    }

    //todo доделать создание уникальных столбцов
    public function makeUniqOrderId(){
        $statement = self::$connection->prepare( "ALTER TABLE `orders` ADD `uid` varchar( 255 ) AFTER `date_update`");
        $statement->execute();
        $statement2 = self::$connection->prepare(
            "UPDATE orders SET uid=s.group0 
                        where `group` is NULL");
        $statement2->execute();

    }

    public function makeDishList(){
        $statement = self::$connection->prepare("SELECT DISTINCT DishName FROM sales");
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
    public function makeWaitersList(){
        $statement = self::$connection->prepare("SELECT DISTINCT Waiter FROM checks");
        $statement->execute();
        $dish_departments = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement2 = self::$connection->prepare("CREATE TABLE IF NOT EXISTS waiters (
              id_waiter INT AUTO_INCREMENT PRIMARY KEY,
              waiter_name VARCHAR(60) NOT NULL UNIQUE,
              alias_name VARCHAR(60) NOT NULL,
              relations INT(20) 
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
          AUTO_INCREMENT=1;
        ");
        $statement2->execute();

        foreach ($dish_departments as $dish){
            $Waiter = $dish['Waiter'];

            $statement = self::$connection->prepare("INSERT IGNORE INTO waiters (waiter_name) VALUE(:waiter_name)");
            $statement->bindValue(':waiter_name', $Waiter);
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

    public function SetViewGroup($id_dep,$id_category,$status) {
        if ($status == 'true'){
            $status = true;
        } else {
            $status = false;
        }


        $statement2 = self::$connection->prepare(
            "INSERT INTO category_for_department (category_id, department_id) 
                        values (:category_id,:id_dep) 
                        ON DUPLICATE KEY UPDATE `status` = :status
        ");
        $statement2->bindValue(':id_dep', $id_dep);
        $statement2->bindValue(':category_id', $id_category);
        $statement2->bindValue(':status', (bool)$status,\PDO::PARAM_BOOL);


        return  $statement2->execute();
    }
    public function SetAliasCategory($id_category,$alias) {

        $statement2 = self::$connection->prepare(
            "UPDATE category SET alias_name=:alias_name 
                        where id_category = :category_id");
        $statement2->bindValue(':category_id', $id_category);
        $statement2->bindValue(':alias_name', $alias);


        return  $statement2->execute();
    }


}