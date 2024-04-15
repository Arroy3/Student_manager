<?php
// ket noi database
// sdung thu vien PDO de lam vc vs mySQL

// ham ket noi toi database

// function connectionDB()
// {
//     try {
//         $dbh = new PDO('mysql:host=localhost;dbname=student_manager;charset=utf8', 'root', '');
//         $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//         return $dbh; // tra ve bien ket noi
//     } catch (PDOException $e) {
//         // attempt to retry the connection after some timeout for example
//         echo "Can not connect to db";
//         print_r($e);
//         die();
//     }
// }

// //ham ngat ket noi toi database
// function disconnectDB($connection)
// {
//     $connection = null;
// }

// Kiểm tra xem hàm connectionDB đã được định nghĩa chưa trước khi định nghĩa lại
if (!function_exists('connectionDB')) {
    // Định nghĩa hàm connectionDB
    function connectionDB()
    {
        try {
            $dbh = new PDO('mysql:host=localhost;dbname=students_manager4;charset=utf8', 'root', '');
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $dbh; // tra ve bien ket noi
        } catch (PDOException $e) {
            // attempt to retry the connection after some timeout for example
            echo "Can not connect to db";
            print_r($e);
            die();
        }
    }
}

// Kiểm tra xem hàm disconnectDB đã được định nghĩa chưa trước khi định nghĩa lại
if (!function_exists('disconnectDB')) {
    // Định nghĩa hàm disconnectDB
    function disconnectDB($connection)
    {
        $connection = null;
    }
}
