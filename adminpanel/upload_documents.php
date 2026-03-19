<?php
include 'dbconnect.php';

$message = "";

// Allowed file types
$allowed_types = [
    "application/pdf",
    "application/msword",
    "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
    "application/vnd.ms-excel",
    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
];

if (isset($_POST['upload'])) {

    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {

        $file_name = $_FILES['document']['name'];
        $file_tmp = $_FILES['document']['tmp_name'];
        $file_type = mime_content_type($file_tmp);

        if (in_array($file_type, $allowed_types)) {

            $upload_dir = "../uploads/";
            $new_name = time() . "_" . basename($file_name);
            $file_path = $upload_dir . $new_name;

            if (move_uploaded_file($file_tmp, $file_path)) {

                $stmt = $pdo->prepare("INSERT INTO documents (file_name, file_type, file_path) VALUES (?, ?, ?)");
                $stmt->execute([$file_name, $file_type, $new_name]);

                $message = "File uploaded successfully!";
            } else {
                $message = "Failed to upload file.";
            }

        } else {
            $message = "Only PDF, Word, and Excel files are allowed!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Documents</title>
    <style>
        body { font-family: Arial; padding: 30px; background: #f4f4f4; }
        .container { max-width: 700px; margin: auto; background: #fff; padding: 30px; border-radius: 8px; }
        h2 { text-align: center; color: #8b0000; }
        input[type="file"] { margin-bottom: 15px; }
        button { background: #8b0000; color: white; padding: 8px 15px; border: none; cursor: pointer; }
        table { width: 100%; margin-top: 30px; border-collapse: collapse; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        th { background: #8b0000; color: white; }
        .msg { margin-bottom: 15px; color: green; }
    </style>
</head>
<body>

<div class="container">
    <h2>Upload Documents</h2>

    <?php if ($message != ""): ?>
        <p class="msg"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="document" required>
        <br>
        <button type="submit" name="upload">Upload File</button>
    </form>

    <h3>Uploaded Files</h3>

    <table>
        <tr>
            <th>ID</th>
            <th>File Name</th>
            <th>Uploaded Date</th>
            <th>Download</th>
        </tr>

        <?php
        $docs = $pdo->query("SELECT * FROM documents ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($docs as $doc):
        ?>
        <tr>
            <td><?php echo $doc['id']; ?></td>
            <td><?php echo $doc['file_name']; ?></td>
            <td><?php echo $doc['uploaded_at']; ?></td>
            <td>
                <a href="../uploads/<?php echo $doc['file_path']; ?>" target="_blank">Download</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

</div>

</body>
</html>