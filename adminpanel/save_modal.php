<?php
session_start();
include 'dbconnect.php';

$uploadDir = "uploads/modal/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

/* FILE VALIDATION FUNCTION */

function validateFile($file)
{
    $allowed = ['jpg','jpeg','png'];

    if ($file['error'] != 0) {
        return "No file uploaded";
    }

    if ($file['size'] > 2097152) {
        return "File size must be under 2MB";
    }

    $filename = $file['name'];

    /* BLOCK DOUBLE EXTENSION */

    if (substr_count($filename,'.') > 1) {
        return "Double extension not allowed";
    }

    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($ext,$allowed)) {
        return "Invalid file type. Only JPG, JPEG, PNG allowed";
    }

    return true;
}


/* SAVE NEW MODAL */

if(isset($_POST['save_notification'])){

    $description = $_POST['description'];
    $link = $_POST['link'];

    $filename = "";

    if(!empty($_FILES['banner_image']['name'])){

        $check = validateFile($_FILES['banner_image']);

        if($check !== true){

            $_SESSION['error'] = $check;
            header("Location: modal.php");
            exit;

        }

        $filename = time().'_'.basename($_FILES['banner_image']['name']);
        $target = $uploadDir.$filename;

        move_uploaded_file($_FILES['banner_image']['tmp_name'],$target);
    }

    $stmt = $pdo->prepare("INSERT INTO add_modal (description,link,banner_image) VALUES (?,?,?)");
    $stmt->execute([$description,$link,$filename]);

    $_SESSION['success'] = "Notification added successfully";
    header("Location: modal.php");
    exit;
}


/* UPDATE MODAL */

if(isset($_POST['update'])){

    $id = $_POST['id'];
    $description = $_POST['description'];
    $link = $_POST['link'];

    if(!empty($_FILES['banner_image']['name'])){

        $check = validateFile($_FILES['banner_image']);

        if($check !== true){

            $_SESSION['error'] = $check;
            header("Location: modal.php");
            exit;

        }

        $filename = time().'_'.basename($_FILES['banner_image']['name']);
        $target = $uploadDir.$filename;

        move_uploaded_file($_FILES['banner_image']['tmp_name'],$target);

        $stmt = $pdo->prepare("UPDATE add_modal SET description=?,link=?,banner_image=? WHERE id=?");
        $stmt->execute([$description,$link,$filename,$id]);

    }else{

        $stmt = $pdo->prepare("UPDATE add_modal SET description=?,link=? WHERE id=?");
        $stmt->execute([$description,$link,$id]);

    }

    $_SESSION['success'] = "Notification updated successfully";
    header("Location: modal.php");
    exit;

}
?>