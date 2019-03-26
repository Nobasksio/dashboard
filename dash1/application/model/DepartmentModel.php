<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 21/03/2019
 * Time: 15:00
 */

namespace application\model;


class DepartmentModel extends BaseModel
{
    public function getDepartmentInfo($depart_id){
        $arr_depart = $this->getDepartmentFromMysql($depart_id);
        return $arr_depart;
    }
    public function getDepartmentFromMysql($depart_id) {
        $statement = self::$connection->prepare("
            SELECT * FROM 
                departments d
            LEFT JOIN brand_department b ON b.id_department=d.id_department
            LEFT JOIN brands brand ON brand.id_brand=b.id_brand
            WHERE d.id_department = :id");
        $statement->bindValue(':id', $depart_id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }
}