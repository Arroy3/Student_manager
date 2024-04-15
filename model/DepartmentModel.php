<?php
require "database/database.php";

function updateDepartmentById($name, $slug, $leader, $status, $beginDate, $logo, $id)
{
    $checkUpdate = false;
    $db = connectionDb();
    $sql = "UPDATE `department` SET `name` = :nameDepartment,
         `slug` = :slug, `leader` = :leader, `date_beginning` = :beginDate, 
         `status` = :statusDepartment, `logo` = :logo,
          `updated_at` = :updated_at WHERE `id` = :id AND `deleted_at` IS NULL";
    $updateTime = date('Y-m-d H:i:s');
    $stmt   = $db->prepare($sql);
    if ($stmt) {
        $stmt->bindParam(':nameDepartment', $name, PDO::PARAM_STR);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->bindParam(':leader', $leader, PDO::PARAM_STR);
        $stmt->bindParam(':beginDate', $beginDate, PDO::PARAM_STR);
        $stmt->bindParam(':statusDepartment', $status, PDO::PARAM_INT); //int
        $stmt->bindParam(':logo', $logo, PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $updateTime, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); //int
        if ($stmt->execute()) {
            $checkUpdate = true;
        }
    }
    disconnectDb($db);
    return $checkUpdate;
}
function getDetailDepartmentById($id = 0)
{
    $sql = "SELECT * FROM `department` WHERE `id` = :id AND `deleted_at` IS NULL";
    $db = connectionDB();
    $data = [];
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bindParam('id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
    disconnectDB($db);
    return $data;
}
function deleteDepartmentById($id = 0)
{
    $sql = "UPDATE `department` SET `deleted_at` = :deleted_at WHERE `id` = :id ";
    $db = connectionDB();
    $checkDelete = false;
    $deleteTime = date("y-m-d H:i:s");
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bindParam('deleted_at', $deleteTime, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $checkDelete = true;
        }
    }
    disconnectDB($db);
    return $checkDelete;
}

function getAllDataDepartments($keyword = null)
{
    $db = connectionDB();
    $key = "%{$keyword}%";
    $sql = "SELECT * from `department` WHERE (`name` LIKE :nameDepartment OR `leader` LIKE :leader) AND `deleted_at` IS NULL";
    $stmt = $db->prepare($sql);
    $data = [];
    if ($stmt) {
        $stmt->bindParam(':nameDepartment', $key, PDO::PARAM_STR);
        $stmt->bindParam(':leader', $key, PDO::PARAM_STR);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }
    disconnectDB($db);
    return $data;
}
function getAllDataDepartmentsByPage($keyword = null, $start = 0, $limit = 10)
{
    $key  = "%{$keyword}%";
    $sql = "SELECT * from `department` WHERE (`name` LIKE :nameDepartment OR `leader` LIKE :leader) AND `deleted_at`IS NULL LIMIT :startData, :limitData";
    $db = connectionDB();
    $stmt = $db->prepare($sql);
    $data = [];
    if ($stmt) {
        $stmt->bindParam(':nameDepartment', $key, PDO::PARAM_STR);
        $stmt->bindParam(':leader', $key, PDO::PARAM_STR);
        $stmt->bindParam(':startData', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limitData', $limit, PDO::PARAM_INT);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }
    disconnectDB($db);
    return $data;
}

function insertDepartment($name, $slug, $leader, $status, $logo, $beginDate)
{
    //viet cau lenh sql insert vao bang department
    $sqlInsert = "INSERT INTO `department` (`name`, `slug`, `leader`, `date_beginning`, `status`,`logo`, `created_at`) 
    VALUES (:nameDepartment, :slug, :leader, :beginDate, :statusDepartment,:logo, :createdAt)";
    $checkInsert = false;
    $db = connectionDB();
    $stmt = $db->prepare($sqlInsert);
    $currenDate = date('Y-m-d H:i:s');
    if ($stmt) {
        $stmt->bindParam(':nameDepartment', $name, PDO::PARAM_STR);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->bindParam(':leader', $leader, PDO::PARAM_STR);
        $stmt->bindParam(':beginDate', $beginDate, PDO::PARAM_STR);
        $stmt->bindParam(':statusDepartment', $status, PDO::PARAM_STR);
        $stmt->bindParam(':logo', $logo, PDO::PARAM_STR);
        $stmt->bindParam(':createdAt', $currenDate, PDO::PARAM_STR);
        if ($stmt->execute()) {
            $checkInsert = true;
        }
    }
    disconnectDB($db);
    //tra ve true, insert thanh cong va  nguoc lai
    return $checkInsert;
}
