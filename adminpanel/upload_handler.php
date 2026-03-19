<?php
include 'dbconnect.php';

$response = ['status' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $category   = $_POST['category'] ?? '';
    $class_name = $_POST['class_name'] ?? '';

    if (!$category) {
        $response['message'] = "Category is required.";
        echo json_encode($response);
        exit;
    }

    // If category is book_list, class is required
    if ($category === "book_list" && empty($class_name)) {
        $response['message'] = "Class is required for Book List.";
        echo json_encode($response);
        exit;
    }

    if (!isset($_FILES['document']) || $_FILES['document']['error'] != 0) {
        $response['message'] = "File upload failed.";
        echo json_encode($response);
        exit;
    }

    $file_tmp  = $_FILES['document']['tmp_name'];
    $file_type = mime_content_type($file_tmp);

    if ($file_type !== "application/pdf") {
        $response['message'] = "Only PDF files allowed.";
        echo json_encode($response);
        exit;
    }

    $file_name = $_FILES['document']['name'];

    // Make class-wise unique file name
    $safe_class = str_replace(" ", "_", strtolower($class_name));
    $new_name = $category . "_" . $safe_class . ".pdf";

    $upload_path = "uploads/" . $new_name;

    // Delete old file only for SAME category + SAME class
    $old = $pdo->prepare("SELECT * FROM documents WHERE category = ? AND class_name = ?");
    $old->execute([$category, $class_name]);
    $oldFile = $old->fetch(PDO::FETCH_ASSOC);

    if ($oldFile) {
        if (file_exists("uploads/" . $oldFile['file_path'])) {
            unlink("uploads/" . $oldFile['file_path']);
        }

        $pdo->prepare("DELETE FROM documents WHERE id = ?")
            ->execute([$oldFile['id']]);
    }

    // Move file
    move_uploaded_file($file_tmp, $upload_path);

    // Insert new record
    $stmt = $pdo->prepare("INSERT INTO documents (file_name, file_path, category, class_name) VALUES (?, ?, ?, ?)");
    $stmt->execute([$file_name, $new_name, $category, $class_name]);

    $response['status'] = true;
    $response['message'] = "File uploaded successfully for " . $class_name;
}

echo json_encode($response);