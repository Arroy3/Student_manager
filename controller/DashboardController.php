<?php

// m = tên của hàm nằm trong file controller trong thư mục controller
$m = trim($_GET['m'] ?? 'index'); // ham mac dinh trong controller ten la index
$m = strtolower($m); //viết thường tất cả tên hàm

switch($m){
    case 'index';
        index();
    break;
    default:
        index();
    break;
}

function index(){
if(!isLoginUser()){
    header("Location:index.php");
    exit;
}

    require 'view/dashboard/index_view.php';
}