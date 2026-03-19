<?php
include 'adminpanel/dbconnect.php';

$status = "";
$messageText = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $status = "error";
        $messageText = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $status = "error";
        $messageText = "Invalid email format.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $subject, $message]);

            header("Location: contact.php?status=success");
            exit;
        } catch (PDOException $e) {
            header("Location: contact.php?status=error&msg=server");
            exit;
        }
    }
} else {
    header("Location: contact.php");
    exit;
}
?>
