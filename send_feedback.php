<?php
include 'adminpanel/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $subject  = trim($_POST['subject']);
    $feedback = trim($_POST['feedback']);

    if (empty($name) || empty($email) || empty($subject) || empty($feedback)) {
        header("Location: feedback.php?status=error&msg=empty");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: feedback.php?status=error&msg=invalid");
        exit;
    }

    try {

        $stmt = $pdo->prepare("INSERT INTO feedback (name, email, subject, feedback) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $subject, $feedback]);

        // Redirect after success (prevents resubmission on refresh)
        header("Location: feedback.php?status=success");
        exit;

    } catch (PDOException $e) {

        header("Location: feedback.php?status=error&msg=server");
        exit;
    }

} else {
    header("Location: feedback.php");
    exit;
}