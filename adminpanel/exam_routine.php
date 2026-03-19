<?php
include 'dbconnect.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$uploadDir = "uploads/exam_routine/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

/* ================= ADD ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    try {
        $notification = trim($_POST['notification']);
        $filePath = "";

        if (!empty($_FILES['file']['name'])) {

            $file_tmp  = $_FILES['file']['tmp_name'];
            $file_name = $_FILES['file']['name'];

            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($file_ext !== 'pdf') {
                throw new Exception("Only PDF files are allowed!");
            }

            $file_type = mime_content_type($file_tmp);
            if ($file_type !== 'application/pdf') {
                throw new Exception("Invalid file type! Upload PDF only.");
            }

            $new_name  = time() . "_" . basename($file_name);
            $targetFile = $uploadDir . $new_name;

            if (move_uploaded_file($file_tmp, $targetFile)) {
                $filePath = $targetFile;
            } else {
                throw new Exception("File upload failed!");
            }
        }

        $stmt = $pdo->prepare("INSERT INTO exam_routine (exam_notification, file_path) VALUES (?, ?)");
        $stmt->execute([$notification, $filePath]);

        $_SESSION['success'] = "Exam routine added successfully!";
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    header("Location: exam_routine.php");
    exit;
}

/* ================= UPDATE ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {

    $id = (int)$_POST['id'];
    $notification = trim($_POST['notification']);

    $stmtOld = $pdo->prepare("SELECT file_path FROM exam_routine WHERE id = ?");
    $stmtOld->execute([$id]);
    $oldData = $stmtOld->fetch(PDO::FETCH_ASSOC);

    $filePath = $oldData['file_path'];

    if (!empty($_FILES['file']['name'])) {

    $file_tmp  = $_FILES['file']['tmp_name'];
    $file_name = $_FILES['file']['name'];

    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if ($file_ext !== 'pdf') {
        $_SESSION['error'] = "Only PDF files are allowed!";
        header("Location: exam_routine.php");
        exit;
    }

    if (!empty($oldData['file_path']) && file_exists($oldData['file_path'])) {
        unlink($oldData['file_path']);
    }

    $new_name  = time() . "_" . basename($file_name);
    $targetFile = $uploadDir . $new_name;

    if (move_uploaded_file($file_tmp, $targetFile)) {
        $filePath = $targetFile;
    } else {
        $_SESSION['error'] = "File upload failed!";
        header("Location: exam_routine.php");
        exit;
    }
}
    try {
        $stmt = $pdo->prepare("UPDATE exam_routine SET Exam_Notification = ?, file_path = ? WHERE id = ?");
        $stmt->execute([$notification, $filePath, $id]);

        $_SESSION['success'] = "Exam routine updated successfully!";
        // header("Location: exam_routine.php");
        // exit;
    } catch (Exception $e) {
        $_SESSION['error'] = "Update Failed!";
    }
}

/* ================= DELETE ================= */
if (isset($_GET['delete'])) {

    $id = (int)$_GET['delete'];

    $stmtOld = $pdo->prepare("SELECT file_path FROM exam_routine WHERE id = ?");
    $stmtOld->execute([$id]);
    $oldData = $stmtOld->fetch(PDO::FETCH_ASSOC);

    if (!empty($oldData['file_path']) && file_exists($oldData['file_path'])) {
        unlink($oldData['file_path']);
    }

    $pdo->prepare("DELETE FROM exam_routine WHERE id = ?")->execute([$id]);

    $_SESSION['success'] = "Exam routine deleted successfully!";
    header("Location: exam_routine.php");
    exit;
}

$events = $pdo->query("SELECT * FROM exam_routine ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Exam Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/header.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php include 'sidebar.php'; ?>
    <?php include 'header.php'; ?>

    <div class="main-content" style="margin-left:220px;padding:20px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Exam Notification</h3>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                + Add Exam Notification
            </button>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Exam Notification</th>
                    <th>File</th>
                    <th style="width:200px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td>
                            <strong>
                                <?= htmlspecialchars($event['Exam_Notification']); ?>
                            </strong>
                        </td>

                        <td>
                            <?php if (!empty($event['file_path'])): ?>
                                <a href="<?= $event['file_path']; ?>" target="_blank" class="btn btn-info btn-sm">
                                    <i class="fa fa-file"></i> View
                                </a>
                            <?php else: ?>
                                <span class="text-muted">No File</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $event['id'] ?>">
                                <i class="fa fa-edit"></i>
                            </button>

                            <button onclick="confirmDelete(<?= $event['id'] ?>)" class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- ADD MODAL -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <form method="post" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5>Add Exam Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Exam Notification</label>
                        <input type="text" name="notification" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Upload File</label>
                        <input type="file" name="file" accept=".pdf" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add" class="btn btn-success">Add</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODALS -->
    <?php foreach ($events as $event): ?>
        <div class="modal fade" id="editModal<?= $event['id'] ?>">
            <div class="modal-dialog">
                <form method="post" enctype="multipart/form-data" class="modal-content">
                    <div class="modal-header">
                        <h5>Edit Exam Notification</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $event['id'] ?>">

                        <div class="mb-3">
                            <label>Exam Notification</label>
                            <input type="text" name="notification"
                                value="<?= htmlspecialchars($event['Exam_Notification']); ?>"
                                class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Current File</label><br>

                            <?php if (!empty($event['file_path'])): ?>
                                <a href="<?= $event['file_path']; ?>" target="_blank" class="btn btn-info btn-sm">
                                    View Uploaded File
                                </a>
                            <?php else: ?>
                                <span class="text-muted">No file uploaded</span>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label>Replace File (Optional)</label>
                            <input type="file" name="file" accept=".pdf" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; ?>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This record will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "?delete=" + id;
                }
            });
        }

        <?php if (isset($_SESSION['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?= $_SESSION['success']; ?>',
                timer: 2000,
                showConfirmButton: false
            });
        <?php unset($_SESSION['success']);
        endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Invalid File',
                text: '<?= $_SESSION['error']; ?>'
            });
        <?php unset($_SESSION['error']);
        endif; ?>
    </script>

</body>

</html>