<?php
//truy van du lieu
require 'database/database.php';

//viet ham ktra dang nhap nguoi dung, ktra dang nhap co ton tai trong DB ko

function checkLoginUser($username,$password)
{
    $db = connectionDB(); //co dc ket noi toi DB
    // viet cau lenh sql truy van
    $sql =  "SELECT a.*, u.`full_name`, u.`email`, u.`phone` from `account`AS a INNER JOIN `users` AS u ON a.user_id = u.id WHERE `username`= :user AND `password` = :pass AND a.`status` = 1 LIMIT 1";
    $statement = $db->prepare($sql); //ktra cau lenh sql
    $dataUser = []; //mang rong chua thong tin ng dung

    if($statement){
        //ktra tham so truyen vao SQL
        $statement->bindParam(':user', $username, PDO::PARAM_STR);
        $statement->bindParam(':pass', $password, PDO::PARAM_STR);

        //thuc thi cau lenh SQL
        if($statement->execute()){
            //ktra truy van SQL co dl tra ve ko?
            if($statement->rowCount() > 0){
                //co DL trong DB, lay DL do ra
                $dataUser = $statement->fetch(PDO::FETCH_ASSOC);
            }
        }
    }
    disconnectDB($db); // dong ket noi DB
    return $dataUser; //tra ve DL chua thong tin ng dung
}
