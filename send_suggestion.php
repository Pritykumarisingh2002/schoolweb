<?php
include 'adminpanel/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $pname     = trim($_POST['pname']);
    $cname     = trim($_POST['cname']);
    $admno     = trim($_POST['admno']);
    $mobile     = trim($_POST['mobile']);
    $email    = trim($_POST['email']);
    $suggestion = trim($_POST['suggestion']);

    if (empty($pname) || empty($cname) || empty($admno) || empty($mobile) ||empty($email) || empty($suggestion)) {
        header("Location: suggestion.php?status=error&msg=empty");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: suuggestion.php?status=error&msg=invalid");
        exit;
    }

    try {

        $stmt = $pdo->prepare("INSERT INTO suggestion (pname, cname, admno, mobile, email, suggestion) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$pname, $cname, $admno, $mobile, $email, $suggestion]);

        header("Location: suggestion.php?status=success");
        exit;

    } catch (PDOException $e) {

        header("Location: suggestion.php?status=error&msg=server");
        exit;
    }

} else {
    header("Location: suggestion.php");
    exit;
}