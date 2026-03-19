<?php
include 'dbconnect.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$success = "";
$error   = "";

// delete file

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $stmt = $pdo->prepare("SELECT * FROM mandate_doc WHERE id = ?");
    $stmt->execute([$id]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($file) {

        $filePath = "uploads/mandate_doc/" . $file['file_path']; //uploads/exam_routine/

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $pdo->prepare("DELETE FROM mandate_doc WHERE id = ?")->execute([$id]);
        $_SESSION['success'] = "Document deleted successfully!";
        header("Location: mandate_disc.php?success=deleted");
        exit;
    }
}

// upload file

if (isset($_POST['upload'])) {

    $category   = $_POST['category'];
    // $class_name = trim($_POST['class_name']);

    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {

        $file_tmp  = $_FILES['document']['tmp_name'];
        $file_type = mime_content_type($file_tmp);

        if ($file_type == "application/pdf") {

            $file_name  = $_FILES['document']['name'];
            $new_name   = time() . "_" . basename($file_name);
            $uploadPath = "uploads/mandate_doc/" . $new_name;

            move_uploaded_file($file_tmp, $uploadPath);

            $stmt = $pdo->prepare("INSERT INTO mandate_doc 
                (file_name, file_path, category, uploaded_at) 
                VALUES (?, ?, ?, NOW())");

            $stmt->execute([$file_name, $new_name, $category]);
            $_SESSION['success'] = "Document added successfully!";
            header("Location: mandate_disc.php?success=uploaded");
            exit;
        } else {
            $_SESSION['error'] = "Error in uploading document!";
            header("Location: mandate_disc.php?error=pdfonly");
            exit;
        }
    }
}
// update file

if (isset($_POST['update'])) {

    $id         = intval($_POST['id']);
    // $class_name = trim($_POST['class_name']);
    $category   = $_POST['category'];

    $stmt = $pdo->prepare("SELECT * FROM mandate_doc WHERE id = ?");
    $stmt->execute([$id]);
    $old = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($old) {

        $file_name = $old['file_name'];
        $file_path = $old['file_path'];

        if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {

            $file_tmp  = $_FILES['document']['tmp_name'];
            $file_type = mime_content_type($file_tmp);

            if ($file_type == "application/pdf") {

                $oldPath = "uploads/mandate_doc/" . $old['file_path'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }

                $file_name = $_FILES['document']['name'];
                $new_name  = time() . "_" . basename($file_name);
                $uploadPath = "uploads/mandate_doc/" . $new_name;

                move_uploaded_file($file_tmp, $uploadPath);

                $file_path = $new_name;
            } else {
                // header("Location: mandate_disc.php?error=pdfonly");
                // exit;
                $_SESSION['error'] = "Error in updating document!";
                header("Location: mandate_disc.php?error=pdfonly");
                exit;
            }
        }

        $update = $pdo->prepare("UPDATE mandate_doc 
            SET file_name = ?, 
                file_path = ?, 
                category = ?
            WHERE id = ?");

        $update->execute([$file_name, $file_path, $category, $id]);
        $_SESSION['success'] = "Document updated successfully!";
        header("Location: mandate_disc.php?success=updated");
        exit;
    }
}

$docs = $pdo->query("SELECT * FROM mandate_doc ORDER BY id DESC")
    ->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Documents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/header.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php include 'sidebar.php'; ?>
    <?php include 'header.php'; ?>

    <div class="main-content" style="margin-left:220px; padding:30px;">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Mandate Disc Document Management</h3>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                + Add Mandate Document
            </button>
        </div>

        <!-- ADD MODAL -->
        <div class="modal fade" id="addModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="post" enctype="multipart/form-data" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Mandate Document</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Category</label>
                            <select name="category" class="form-control" required>
                                <option value="">-- Select Section --</option>
                                <option value="managing_comm">Managing Committee</option>
                                <option value="s&f_details">Staff & Faculty Details</option>
                                <option value="pta_member">PTA Member</option>
                                <option value="aff_cer">Affliation Certificate</option>
                                <option value="society_reg">Society Registration</option>
                                <option value="NOC">NOC</option>
                                <option value="RTE">RTE Affliation Application</option>
                                <option value="land_agg">Land Agreement</option>
                                <option value="b&s_cer">Building & Safety Certificate</option>
                                <option value="Fire_safety_cer">Fire Safety Certificate</option>
                                <option value="wh&s_certificate">WH&S Certificate</option>
                                <option value="self_certification">Self Certification</option>
                                <option value="mandate_disclosure">Mandate Disclosure Link</option>
                            </select>
                        </div>

                        <!-- <div class="mb-3">
                            <label>Class</label>
                            <input type="text" name="class_name" class="form-control" required>
                        </div> -->

                        <div class="mb-3">
                            <label>Upload PDF</label>
                            <input type="file" name="document" accept=".pdf" class="form-control" required>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="upload" class="btn btn-success">Add Document</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TABLE -->
        <div class="container-fluid bg-white p-3 rounded shadow-sm">

            <h4>Uploaded Files</h4>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <!-- <th>ID</th> -->
                        <!-- <th>Class</th> -->
                        <th>File Name</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($docs as $doc): ?>
                        <tr>
                            <!-- <td><?= $doc['id']; ?></td> -->
                            <!-- <td><?= htmlspecialchars($doc['class_name']); ?></td> -->
                            <td><?= htmlspecialchars($doc['file_name']); ?></td>
                            <td><?= ucfirst(str_replace('_', ' ', $doc['category'])); ?></td>
                            <td><?= $doc['uploaded_at']; ?></td>
                            <td>

                                <a href="uploads/mandate_doc/<?= $doc['file_path']; ?>" target="_blank" class="btn btn-info btn-sm">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $doc['id']; ?>">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <button class="btn btn-danger btn-sm deleteBtn" data-id="<?= $doc['id']; ?>">
                                    <i class="fa fa-trash"></i>
                                </button>

                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>



    <!-- EDIT MODAL -->
    <?php foreach ($docs as $doc) : ?>
        <div class="modal fade" id="editModal<?= $doc['id']; ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Document</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <input type="hidden" name="id" value="<?= $doc['id']; ?>">

                            <!-- <div class="mb-3">
                                <label>Class</label>
                                <input type="text" name="class_name" value="<?= htmlspecialchars($doc['class_name']); ?>" class="form-control" required>
                            </div> -->

                            <div class="mb-3">
                                <label>Category</label>
                                <select name="category" class="form-control" required>
                                    <option value="managing_comm" <?= $doc['category'] == 'managing_comm' ? 'selected' : '' ?>>Managing Committee</option>
                                    <option value="s&f_details" <?= $doc['category'] == 's&f_details' ? 'selected' : '' ?>>Staff & Faculty Details</option>
                                    <option value="pta_member" <?= $doc['category'] == 'pta_member' ? 'selected' : '' ?>>PTA Member</option>
                                    <option value="aff_cer" <?= $doc['category'] == 'aff_cer' ? 'selected' : '' ?>>Affliation Certificate</option>
                                    <option value="society_reg" <?= $doc['category'] == 'society_reg' ? 'selected' : '' ?>>Society Registration</option>
                                    <option value="NOC" <?= $doc['category'] == 'NOC' ? 'selected' : '' ?>>NOC</option>
                                    <option value="RTE" <?= $doc['category'] == 'RTE' ? 'selected' : '' ?>>RTE Affliation Application</option>
                                    <option value="land_agg" <?= $doc['category'] == 'land_agg' ? 'selected' : '' ?>>Land Agreement</option>
                                    <option value="b&s_cer" <?= $doc['category'] == 'b&s_cer' ? 'selected' : '' ?>>Building & Safety Certificate</option>
                                    <option value="Fire_safety_cer" <?= $doc['category'] == 'Fire_safety_cer' ? 'selected' : '' ?>>Fire Safety Certificate</option>
                                    <option value="wh&s_certificate" <?= $doc['category'] == 'wh&s_certificate' ? 'selected' : '' ?>>WH&S Certificate</option>
                                    <option value="self_certification" <?= $doc['category'] == 'self_certification' ? 'selected' : '' ?>>Self Certification</option>
                                    <option value="mandate_disclosure" <?= $doc['category'] == 'mandate_disclosure' ? 'selected' : '' ?>>Mandate Disclosure Link</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Current File</label><br>
                                <a href="uploads/mandate_doc/<?= $doc['file_path']; ?>" target="_blank">
                                    <?= htmlspecialchars($doc['file_name']); ?>
                                </a>
                            </div>

                            <div class="mb-3">
                                <label>Upload New PDF (Optional)</label>
                                <input type="file" name="document" accept=".pdf" class="form-control">
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="submit" name="update" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php include 'footer.php'; ?>

    <script>
        $(document).on("click", ".deleteBtn", function() {

            let id = $(this).data("id");

            Swal.fire({
                title: "Are you sure?",
                text: "This file will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "?delete=" + id;
                }
            });
        });

        <?php if (isset($_SESSION['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= $_SESSION['success']; ?>',
                timer: 2000,
                showConfirmButton: false
            });
        <?php unset($_SESSION['success']);
        endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?= $_SESSION['error'] ?>'
            });
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>