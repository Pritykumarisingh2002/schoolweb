<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

include 'dbconnect.php';

$error = '';
$album_id = isset($_GET['album_id']) ? (int)$_GET['album_id'] : 0;
$album = null;

/* CREATE ALBUM */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_album'])) {

    $album_name = $_POST['album_name'] ?? '';
    $album_session = $_POST['album_session'] ?? '';
    $status = $_POST['status'] ?? 'draft';

    $stmt = $pdo->prepare("INSERT INTO albums (name, session, status) VALUES (?, ?, ?)");
    $stmt->execute([$album_name, $album_session, $status]);

    $album_id = $pdo->lastInsertId();

    /* IMAGE VALIDATION */
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        $tmp = $_FILES['image']['tmp_name'];
        $name = $_FILES['image']['name'];

        // Extension check
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
            header("Location: album_image_crud.php?album_id=$album_id&status=invalid");
            exit;
        }

        // Real image check
        if (getimagesize($tmp) === false) {
            header("Location: album_image_crud.php?album_id=$album_id&status=invalid");
            exit;
        }

        // MIME check
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmp);
        finfo_close($finfo);

        if (!in_array($mime, ['image/jpeg', 'image/png'])) {
            header("Location: album_image_crud.php?album_id=$album_id&status=invalid");
            exit;
        }

        $uploadDir = 'uploads/gallery/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $filename = time() . '_' . uniqid() . '.' . $ext;
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($tmp, $targetPath)) {
            $stmt = $pdo->prepare("INSERT INTO album_images (album_id, filename) VALUES (?, ?)");
            $stmt->execute([$album_id, $filename]);
        }
    }

    header("Location: album_image_crud.php?album_id=$album_id&status=album_created");
    exit;
}


/* FETCH ALBUM */

if ($album_id) {
    $stmt = $pdo->prepare("SELECT * FROM albums WHERE id = ?");
    $stmt->execute([$album_id]);
    $album = $stmt->fetch();
}


/* IMAGE UPLOAD */

if ($album_id && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image']) && !isset($_POST['create_album'])) {

    if ($_FILES['image']['error'] === 0) {

        $tmp = $_FILES['image']['tmp_name'];
        $name = $_FILES['image']['name'];

        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
            header("Location: album_image_crud.php?album_id=$album_id&status=invalid");
            exit;
        }

        if (getimagesize($tmp) === false) {
            header("Location: album_image_crud.php?album_id=$album_id&status=invalid");
            exit;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmp);
        finfo_close($finfo);

        if (!in_array($mime, ['image/jpeg', 'image/png'])) {
            header("Location: album_image_crud.php?album_id=$album_id&status=invalid");
            exit;
        }

        $uploadDir = 'uploads/gallery/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $filename = time() . '_' . uniqid() . '.' . $ext;
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($tmp, $targetPath)) {
            $stmt = $pdo->prepare("INSERT INTO album_images (album_id, filename) VALUES (?, ?)");
            $stmt->execute([$album_id, $filename]);
        }

        header("Location: album_image_crud.php?album_id=$album_id&status=uploaded");
        exit;
    }
}


/* DELETE IMAGE */

if (isset($_GET['delete_id'])) {

    $id = (int)$_GET['delete_id'];

    $stmt = $pdo->prepare("SELECT filename FROM album_images WHERE id = ?");
    $stmt->execute([$id]);
    $image = $stmt->fetch();

    if ($image) {

        $filePath = 'uploads/gallery/' . $image['filename'];

        if (file_exists($filePath)) unlink($filePath);

        $stmt = $pdo->prepare("DELETE FROM album_images WHERE id = ?");
        $stmt->execute([$id]);
    }

    header("Location: album_image_crud.php?album_id=$album_id&status=deleted");
    exit;
}


/* UPDATE IMAGE */

if (isset($_POST['edit_id']) && isset($_FILES['new_image'])) {

    $edit_id = (int)$_POST['edit_id'];

    if ($_FILES['new_image']['error'] === 0) {

        $tmp = $_FILES['new_image']['tmp_name'];
        $name = $_FILES['new_image']['name'];

        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
            header("Location: album_image_crud.php?album_id=$album_id&status=invalid");
            exit;
        }

        if (getimagesize($tmp) === false) {
            header("Location: album_image_crud.php?album_id=$album_id&status=invalid");
            exit;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmp);
        finfo_close($finfo);

        if (!in_array($mime, ['image/jpeg', 'image/png'])) {
            header("Location: album_image_crud.php?album_id=$album_id&status=invalid");
            exit;
        }

        // delete old
        $stmt = $pdo->prepare("SELECT filename FROM album_images WHERE id = ?");
        $stmt->execute([$edit_id]);
        $old = $stmt->fetch();

        if ($old) {
            $oldPath = 'uploads/gallery/' . $old['filename'];
            if (file_exists($oldPath)) unlink($oldPath);
        }

        $newFilename = time() . '_' . uniqid() . '.' . $ext;
        $newTarget = 'uploads/gallery/' . $newFilename;

        if (move_uploaded_file($tmp, $newTarget)) {
            $stmt = $pdo->prepare("UPDATE album_images SET filename=? WHERE id=?");
            $stmt->execute([$newFilename, $edit_id]);
        }
    }

    header("Location: album_image_crud.php?album_id=$album_id&status=updated");
    exit;
}


/* FETCH IMAGES */

$images = [];

if ($album_id) {
    $stmt = $pdo->prepare("SELECT * FROM album_images WHERE album_id = ? ORDER BY id DESC");
    $stmt->execute([$album_id]);
    $images = $stmt->fetchAll();
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Create New Album</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/header.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="d-flex flex-column min-vh-100">

    <?php include 'sidebar.php'; ?>
    <?php include 'header.php'; ?>
    <div class="main-content" style="margin-left:220px;padding:20px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Create New Album</h3>
            <a href="index.php" class="btn btn-secondary btn-sm">Back to Gallery</a>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="album_image_crud.php" method="post" enctype="multipart/form-data" class="p-3"
            style="background-color:rgb(239,247,232);border-radius:8px;">
            <input type="hidden" name="create_album" value="1">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Album Name</label>
                    <input type="text" name="album_name" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Session</label>
                    <input type="text" name="album_session" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Upload First Image (optional)</label>
                <input type="file" name="image" class="form-control" accept=".jpg, .jpeg, .png">
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">Create Album</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </div>

        </form>



        <?php if (!empty($images)): ?>

            <hr>
            <h5 class="mt-4">Uploaded Images</h5>
            <div class="row">
                <?php foreach ($images as $img): ?>
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <img src="uploads/gallery/<?= htmlspecialchars($img['filename']) ?>" class="card-img-top">
                            <div class="card-body text-center">
                                <form method="post" enctype="multipart/form-data" class="d-flex">
                                    <input type="hidden" name="edit_id" value="<?= $img['id'] ?>">
                                    <input type="file" name="new_image" accept=".jpg, .jpeg, .png" required class="form-control form-control-sm">
                                    <button type="submit" class="btn btn-sm btn-primary ms-2">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <a href="?album_id=<?= $album_id ?>&delete_id=<?= $img['id'] ?>"
                                        class="btn btn-sm btn-danger ms-2 delete-btn">
                                        <i class="fa fa-trash"></i>
                                    </a>

                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        /* DELETE CONFIRM */
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function(e) {
                e.preventDefault();
                const link = this.getAttribute("href");
                Swal.fire({
                    title: "Are you sure?",
                    text: "This image will be permanently deleted!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes delete it"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = link;
                    }
                });
            });
        });

        /* STATUS ALERTS */

        const params = new URLSearchParams(window.location.search);
        const status = params.get("status");

        if (status === "uploaded") {
            Swal.fire("Success", "Image uploaded successfully", "success");
        }

        if (status === "invalid") {
            Swal.fire("Invalid File", "Only JPG, JPEG and PNG allowed", "error");
        }

        if (status === "error") {
            Swal.fire("Upload Failed", "File upload failed", "error");
        }

        if (status === "updated") {
            Swal.fire("Updated", "Image updated successfully", "success");
        }

        if (status === "deleted") {
            Swal.fire("Deleted", "Image deleted successfully", "success");
        }

        if (status === "album_created") {
            Swal.fire("Success", "Album created successfully", "success");
        }
    </script>

</body>

</html>