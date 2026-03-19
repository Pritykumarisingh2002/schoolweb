<?php
include 'dbconnect.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$action = $_POST['action'] ?? '';

/* ================= ADD TOPPER ================= */
if ($action == "add_topper") {

    $student_name = trim($_POST['student_name']);
    $class_name   = trim($_POST['class_name']);
    $session      = trim($_POST['session']);
    $position     = trim($_POST['position']);

    if (!$student_name || !$class_name || !$session || !$position) {
        echo json_encode(["status" => "error", "message" => "All fields required"]);
        exit;
    }

    if (!isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
        echo json_encode(["status" => "error", "message" => "Image required"]);
        exit;
    }

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
        echo json_encode(["status" => "error", "message" => "Only JPG, JPEG, PNG allowed"]);
        exit;
    }

    if (!is_dir("uploads/toppers")) {
        mkdir("uploads/toppers", 0755, true);
    }

    $new_name = time() . '_' . uniqid() . '.' . $ext;
    move_uploaded_file($_FILES['image']['tmp_name'], "uploads/toppers/" . $new_name);

    $stmt = $pdo->prepare("INSERT INTO toppers (student_name,class_name,session,position,image_path) VALUES (?,?,?,?,?)");
    $stmt->execute([$student_name, $class_name, $session, $position, $new_name]);

    echo json_encode(["status" => "success"]);
    exit;
}

// Update Topper
if ($action == "update_topper") {

    $id           = intval($_POST['id']);
    $student_name = trim($_POST['student_name']);
    $class_name   = trim($_POST['class_name']);
    $session      = trim($_POST['session']);
    $position     = trim($_POST['position']);

    if (!$student_name || !$class_name || !$session || !$position) {
        echo json_encode(["status" => "error", "message" => "All fields required"]);
        exit;
    }

    // Get old image
    $stmt = $pdo->prepare("SELECT image_path FROM toppers WHERE id=?");
    $stmt->execute([$id]);
    $old = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$old) {
        echo json_encode(["status" => "error", "message" => "Topper not found"]);
        exit;
    }

    $image_name = $old['image_path'];

    // If new image uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
            echo json_encode(["status" => "error", "message" => "Only JPG, JPEG, PNG allowed"]);
            exit;
        }

        $image_name = time() . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/toppers/" . $image_name);

        // delete old image
        if (!empty($old['image_path']) && file_exists("uploads/toppers/" . $old['image_path'])) {
            unlink("uploads/toppers/" . $old['image_path']);
        }
    }

    $stmt = $pdo->prepare("UPDATE toppers 
        SET student_name=?, class_name=?, session=?, position=?, image_path=? 
        WHERE id=?");

    $stmt->execute([$student_name, $class_name, $session, $position, $image_name, $id]);

    echo json_encode(["status" => "success", "message" => "Topper updated successfully"]);
    exit;
}

/* ================= DELETE TOPPER ================= */
if ($action == "delete_topper") {

    $id = intval($_POST['id']);

    $stmt = $pdo->prepare("SELECT image_path FROM toppers WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if ($row) {
        if (file_exists("uploads/toppers/" . $row['image_path'])) {
            unlink("uploads/toppers/" . $row['image_path']);
        }
        $pdo->prepare("DELETE FROM toppers WHERE id=?")->execute([$id]);
    }

    echo json_encode(["status" => "success"]);
    exit;
}


/* ================= ADD RESULT ================= */
if ($action == "add_result") {

    $class_name = trim($_POST['class_name']);
    $section    = trim($_POST['section']);
    $session    = trim($_POST['session']);

    if (!$class_name || !$section || !$session) {
        echo json_encode(["status" => "error", "message" => "All fields required"]);
        exit;
    }

    if (!isset($_FILES['pdf']) || $_FILES['pdf']['error'] != 0) {
        echo json_encode(["status" => "error", "message" => "PDF required"]);
        exit;
    }

    if (mime_content_type($_FILES['pdf']['tmp_name']) != "application/pdf") {
        echo json_encode(["status" => "error", "message" => "Only PDF allowed"]);
        exit;
    }

    if (!is_dir("uploads/results")) {
        mkdir("uploads/results", 0755, true);
    }

    $new_pdf = time() . '_' . uniqid() . '.pdf';
    move_uploaded_file($_FILES['pdf']['tmp_name'], "uploads/results/" . $new_pdf);

    $stmt = $pdo->prepare("INSERT INTO student_results (class_name,section,session,pdf_file) VALUES (?,?,?,?)");
    $stmt->execute([$class_name, $section, $session, $new_pdf]);

    echo json_encode(["status" => "success"]);
    exit;
}

// update result

if ($action == "update_result") {

    $id         = intval($_POST['id']);
    $class_name = trim($_POST['class_name']);
    $section    = trim($_POST['section']);
    $session    = trim($_POST['session']);

    if (!$class_name || !$section || !$session) {
        echo json_encode(["status" => "error", "message" => "All fields required"]);
        exit;
    }

    // Get old PDF
    $stmt = $pdo->prepare("SELECT pdf_file FROM student_results WHERE id=?");
    $stmt->execute([$id]);
    $old = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$old) {
        echo json_encode(["status" => "error", "message" => "Result not found"]);
        exit;
    }

    $pdf_name = $old['pdf_file'];

    // If new PDF uploaded
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {

        if (mime_content_type($_FILES['pdf']['tmp_name']) != "application/pdf") {
            echo json_encode(["status" => "error", "message" => "Only PDF allowed"]);
            exit;
        }

        $pdf_name = time() . '_' . uniqid() . '.pdf';
        move_uploaded_file($_FILES['pdf']['tmp_name'], "uploads/results/" . $pdf_name);

        // delete old pdf
        if (!empty($old['pdf_file']) && file_exists("uploads/results/" . $old['pdf_file'])) {
            unlink("uploads/results/" . $old['pdf_file']);
        }
    }

    $stmt = $pdo->prepare("UPDATE student_results 
        SET class_name=?, section=?, session=?, pdf_file=? 
        WHERE id=?");

    $stmt->execute([$class_name, $section, $session, $pdf_name, $id]);

    echo json_encode(["status" => "success", "message" => "Result updated successfully"]);
    exit;
}

/* ================= DELETE RESULT ================= */
if ($action == "delete_result") {

    $id = intval($_POST['id']);

    $stmt = $pdo->prepare("SELECT pdf_file FROM student_results WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if ($row) {
        if (file_exists("uploads/results/" . $row['pdf_file'])) {
            unlink("uploads/results/" . $row['pdf_file']);
        }
        $pdo->prepare("DELETE FROM student_results WHERE id=?")->execute([$id]);
    }

    echo json_encode(["status" => "success"]);
    exit;
}


/* ================= FETCH DATA ================= */
if ($action == "fetch_data") {

    $toppers = $pdo->query("SELECT * FROM toppers ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    $results = $pdo->query("SELECT * FROM student_results ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "toppers" => $toppers,
        "results" => $results
    ]);
    exit;
}
