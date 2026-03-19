<?php
include 'dbconnect.php';
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

/* ================= TOPPER UPLOAD ================= */
if (isset($_POST['upload_topper'])) {

    $student_name = $_POST['student_name'];
    $class_name   = $_POST['class_name'];
    $session      = $_POST['session'];
    $position     = $_POST['position'];

    if (!empty($student_name) && !empty($class_name) && !empty($session) && !empty($position)) {

        $img_name = $_FILES['image']['name'];
        $img_tmp  = $_FILES['image']['tmp_name'];
        $ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {

            if (!is_dir("uploads/toppers")) {
                mkdir("uploads/toppers", 0777, true);
            }

            $new_name = time() . "_" . uniqid() . "." . $ext;
            move_uploaded_file($img_tmp, "uploads/toppers/" . $new_name);

            $stmt = $pdo->prepare("INSERT INTO toppers (student_name, class_name, session, position, image_path) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$student_name, $class_name, $session, $position, $new_name]);
        }
    }
}

/* ================= RESULT UPLOAD ================= */
if (isset($_POST['upload_result'])) {

    $class_name = $_POST['result_class'];
    $section    = $_POST['section'];
    $session    = $_POST['result_session'];

    if (!empty($class_name) && !empty($section) && !empty($session)) {

        $pdf_tmp  = $_FILES['result_pdf']['tmp_name'];
        $type     = mime_content_type($pdf_tmp);

        if ($type === "application/pdf") {

            if (!is_dir("uploads/results")) {
                mkdir("uploads/results", 0777, true);
            }

            $new_pdf = time() . "_" . uniqid() . ".pdf";
            move_uploaded_file($pdf_tmp, "uploads/results/" . $new_pdf);

            $stmt = $pdo->prepare("INSERT INTO student_results (class_name, section, session, pdf_file) VALUES (?, ?, ?, ?)");
            $stmt->execute([$class_name, $section, $session, $new_pdf]);
        }
    }
}

/* ================= DELETE ================= */
if (isset($_GET['delete_topper'])) {
    $id = intval($_GET['delete_topper']);
    $stmt = $pdo->prepare("SELECT image_path FROM toppers WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if ($row) {
        unlink("uploads/toppers/" . $row['image_path']);
        $pdo->prepare("DELETE FROM toppers WHERE id=?")->execute([$id]);
    }
    header("Location: notification.php");
    exit;
}

if (isset($_GET['delete_result'])) {
    $id = intval($_GET['delete_result']);
    $stmt = $pdo->prepare("SELECT pdf_file FROM student_results WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if ($row) {
        unlink("uploads/results/" . $row['pdf_file']);
        $pdo->prepare("DELETE FROM student_results WHERE id=?")->execute([$id]);
    }
    header("Location: notification.php");
    exit;
}

/* ================= FETCH ================= */
$toppers = $pdo->query("SELECT * FROM toppers ORDER BY created_at DESC")->fetchAll();
$resultsData = $pdo->query("SELECT * FROM student_results ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/header.css">
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 40px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
        }

        h2 {
            color: #8b0000;
            margin-top: 40px;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }

        button {
            padding: 8px 15px;
            background: #8b0000;
            color: #fff;
            border: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background: #8b0000;
            color: #fff;
        }

        img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .delete {
            background: red;
            color: #fff;
            padding: 5px 10px;
            text-decoration: none;
        }

        .msg {
            color: green;
            margin-bottom: 15px;
        }

        .main-content {
            margin-left: 200px;
            /* same width as sidebar */
            padding: 20px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <?php include 'header.php'; ?>

    <div class="main-content">
        <div class="container-fluid mt-4">

            <!-- ================= TOPPER SECTION ================= -->

            <h3 class="mb-3">Topper Management</h3>

            <form method="post" enctype="multipart/form-data" class="row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" name="student_name" class="form-control" placeholder="Student Name" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="class_name" class="form-control" placeholder="Class" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="session" class="form-control" placeholder="Session" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="position" class="form-control" placeholder="Position" required>
                </div>
                <div class="col-md-2">
                    <input type="file" name="image" class="form-control" required>
                </div>
                <div class="col-md-1">
                    <button type="submit" name="upload_topper" class="btn btn-success w-100">Add</button>
                </div>
            </form>

            <table class="table table-bordered table-striped">
                <thead class="table-dark>
                    <tr>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Session</th>
                        <th>Position</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($toppers as $t): ?>
                        <tr>
                            <td><?= htmlspecialchars($t['student_name']); ?></td>
                            <td><?= $t['class_name']; ?></td>
                            <td><?= $t['session']; ?></td>
                            <td><?= $t['position']; ?></td>
                            <td>
                                <img src="uploads/toppers/<?= $t['image_path']; ?>"
                                    width="60" height="60"
                                    style="border-radius:50%;object-fit:cover;">
                            </td>
                            <td>
                                <a href="?delete_topper=<?= $t['id']; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete topper?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <hr class="my-5">

            <!-- ================= RESULT SECTION ================= -->

            <h3 class="mb-3">Class Result Management</h3>

            <form method="post" enctype="multipart/form-data" class="row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" name="result_class" class="form-control" placeholder="Class" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="section" class="form-control" placeholder="Section" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="result_session" class="form-control" placeholder="Session" required>
                </div>
                <div class="col-md-3">
                    <input type="file" name="result_pdf" class="form-control" accept=".pdf" required>
                </div>
                <div class="col-md-1">
                    <button type="submit" name="upload_result" class="btn btn-primary w-100">
                        Add
                    </button>
                </div>
            </form>

            <table class="table table-bordered table-striped">
                <thead class="table-dark>
                    <tr>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Session</th>
                        <th>PDF</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultsData as $r): ?>
                        <tr>
                            <td><?= $r['class_name']; ?></td>
                            <td><?= $r['section']; ?></td>
                            <td><?= $r['session']; ?></td>
                            <td>
                                <a href="uploads/results/<?= $r['pdf_file']; ?>"
                                    target="_blank"
                                    class="btn btn-info btn-sm">
                                    View
                                </a>
                            </td>
                            <td>
                                <a href="?delete_result=<?= $r['id']; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete result?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>