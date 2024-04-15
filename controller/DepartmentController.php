<?php

require 'model/DepartmentModel.php';

// m = tên của hàm nằm trong file controller trong thư mục controller
$m = trim($_GET['m'] ?? 'index'); // ham mac dinh trong controller ten la index
$m = strtolower($m); //viết thường tất cả tên hàm

switch ($m) {
    case 'index';
        index();
        break;

    case 'add';
        Add();
        break;

    case 'handle-add';
        handleAdd();
        break;

    case 'delete';
        handleDelete();
        break;

    case 'edit';
        edit();
        break;

    case 'handle-edit';
        handleEdit();
        break;

    default:
        index();
        break;
}

function handleEdit()
{
    if (isset($_POST['btnSave'])) {

        $id = trim($_GET['id'] ?? null);
        $id = is_numeric($id) ? $id : 0;
        $info = getDetailDepartmentById($id); //goi ten ham trong model

        $name = trim($_POST['name'] ?? null);
        $name = strip_tags($name);

        $leader = trim($_POST['leader'] ?? null);
        $leader = strip_tags($leader);

        $status = trim($_POST['status'] ?? null);
        $status = $status === '0' || $status === '1' ? $status : 0;

        $Datebeginning = trim($_POST['date_beginning'] ?? null);
        $Datebeginning = date('Y-m-d', strtotime($Datebeginning));

        $_SESSION['error_update_department'] = [];
        if (empty($name)) {
            $_SESSION['error_update_department']['name'] = 'Enter name of department, please';
        } else {
            $_SESSION['error_update_department']['name'] = null;
        }

        if (empty($leader)) {
            $_SESSION['error_update_department']['leader'] = 'Enter name of leader, please';
        } else {
            $_SESSION['error_update_department']['leader'] = null;
        }
        $logo = $info['logo'] ?? null;
        if (!empty($_FILES['logo']['tmp_name'])) {
            $logo = uploadFile($_FILES['logo'], 'public/uploads/images/', ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'], 10 * 1024 * 1024);
            if (empty($logo)) {
                $_SESSION['error_update_department']['logo'] = 'File only accept extension is .png, .jpg, .jpeg, .gif and file <= 10Mb';
            } else {
                $_SESSION['error_update_department']['logo'] = null;
            }
        }
        $flagCheckingError = false;
        foreach ($_SESSION['error_update_department'] as $error) {
            if (!empty($error)) {
                $flagCheckingError = true;
                break;
            }
        }
        if (!$flagCheckingError) {
            // ko loi
            if (isset($_SESSION['error_update_department'])) {
                unset($_SESSION['error_update_department']);
            }
            $slug = slug_string($name);
            $update = updateDepartmentById($name, $slug, $leader, $status, $Datebeginning, $logo, $id);
            if ($update) {
                //update thanh cong
                header("Location:index.php?c=department&state=success");
            } else {
                header("Location:index.php?c=department&m=edit&id={$id}&state=error");
            }
        } else {
            //co loi - quay lai form
            header("Location:index.php?c=department&m=edit&id={$id}&state=failure");
        }
    }
}
function edit()
{
    // phai dang nhpa ms su dung duoc chuc nang nay
    if (!isLoginUser()) {
        header("Location:index.php");
        exit();
    }
    $id = trim($_GET['id'] ?? null);
    $id = is_numeric($id) ? $id : 0;
    $info = getDetailDepartmentById($id); //goi ham trong model
    if (!empty($info)) {
        //co dl trong db
        //hien thi giao dien-thong tin chi tiet dl

        require 'view/department/edit_view.php';
    } else {
        //khong co dl trong db
        //thong bao 1 giao dien loi

        require 'view/error_view.php';
    }
}

function handleDelete()
{
    if (!isLoginUser()) {
        header("Location:index.php");
        exit();
    }
    $id = trim($_GET['id'] ?? null);
    $id = is_numeric($id) ? $id : 0;
    $delete = deleteDepartmentById($id); //goi ten ham trong model
    if ($delete) {
        //xoa thanh cong
        header("Location:index.php?c=department&state_del=success");
    } else {
        //xoa that bai
        header("Location:index.php?c=department&state_del=failure");
    }
}

function handleAdd()
{
    if (isset($_POST['btnSave'])) {
        $name = trim($_POST['name'] ?? null);
        $name = strip_tags($name);

        $leader = trim($_POST['leader'] ?? null);
        $leader = strip_tags($leader);

        $status = trim($_POST['status'] ?? null);
        $status = $status === '0' || $status === '1' ? $status : 0;

        $Datebeginning = trim($_POST['date_beginning'] ?? null);
        $Datebeginning = date('Y-m-d', strtotime($Datebeginning));

        //ktra thong tin
        $_SESSION['error_add_department'] = [];
        if (empty($name)) {
            $_SESSION['error_add_department']['name'] = 'Enter name of department, please';
        } else {
            $_SESSION['error_add_department']['name'] = null;
        }

        if (empty($leader)) {
            $_SESSION['error_add_department']['leader'] = 'Enter name of leader, please';
        } else {
            $_SESSION['error_add_department']['leader'] = null;
        }

        //xu ly upload logo
        $logo = null;
        if (!empty($_FILES['logo']['tmp_name'])) {
            $logo = uploadFile($_FILES['logo'], 'public/uploads/images/', ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'], 5 * 1024 * 1024);
            if (empty($logo)) {
                $_SESSION['error_add_department']['logo'] = 'File only accept extension is .png, .jpg, .jpeg, .gif and file <= 5Mb';
            } else {
                $_SESSION['error_add_department']['logo'] = null;
            }
        }

        $flagCheckingError = false;
        foreach ($_SESSION['error_add_department'] as $error) {
            if (!empty($error)) {
                $flagCheckingError = true;
                break;
            }
        }

        //check lai thong tin
        if (!$flagCheckingError) {
            //tien hanh insert vao DB
            $slug = slug_string($name);
            $insert = insertDepartment($name, $slug, $leader, $status, $logo, $Datebeginning);
            if ($status) {
                header("Location:index.php?c=department&state=success");
            } else {
                header("Location:index.php?c=department&m=add&state=error");
            }
        } else {
            //thong bao loi cho nguoi dung biet
            header("Location:index.php?c=department&m=add&state=fail");
        }
    }
}
function Add()
{
    require 'view/department/add_view.php';
}
function index()
{
    if (!isLoginUser()) {
        header("Location:index.php");
        exit();
    }
    $keyword = trim($_GET['search'] ?? null);
    $keyword = strip_tags($keyword);
    $page = trim($_GET['page'] ?? null);
    $page = (is_numeric($page) && $page > 0) ? $page : 1;
    $linkPage = createLink([
        'c' => 'department',
        'm' => 'index',
        'page' => '{page}',
        'search' => $keyword
    ]);

    $totalItems = getAllDataDepartments($keyword);
    $totalItems = count($totalItems);
    //departments

    $panigate = pagigate($linkPage, $totalItems, $page, $keyword, 10);
    $start = $panigate['start'] ?? 0;
    $departments = getAllDataDepartmentsByPage($keyword, $start, 10);
    $htmlPage = $panigate['pagination'] ?? null;

    require 'view/department/index_view.php';
}
