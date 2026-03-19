<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

include 'dbconnect.php';

$album_id = isset($_GET['album_id']) ? (int)$_GET['album_id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM albums WHERE id = ?");
$stmt->execute([$album_id]);
$album = $stmt->fetch();

if (!$album) {
    echo "Album not found!";
    exit;
}

/* UPDATE ALBUM */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_album'])) {

    $name = $_POST['album_name'];
    $session = $_POST['album_session'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE albums SET name=?,session=?,status=? WHERE id=?");
    $stmt->execute([$name, $session, $status, $album_id]);

    header("Location: gallery.php?album_id=$album_id&status=updated");
    exit;
}

/* DELETE ALBUM */

if (isset($_GET['delete_album'])) {

    $stmt = $pdo->prepare("SELECT filename FROM album_images WHERE album_id=?");
    $stmt->execute([$album_id]);

    foreach ($stmt->fetchAll() as $img) {
        $path = "uploads/gallery/" . $img['filename'];
        if (file_exists($path)) {
            unlink($path);
        }
    }

    $pdo->prepare("DELETE FROM album_images WHERE album_id=?")->execute([$album_id]);
    $pdo->prepare("DELETE FROM albums WHERE id=?")->execute([$album_id]);

    header("Location:index.php?status=album_deleted");
    exit;
}


/* UPLOAD MULTIPLE IMAGES */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['images'])) {

    $uploadDir = "uploads/gallery/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $allowedMimeTypes = ['image/jpeg', 'image/png'];

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {

        if ($_FILES['images']['error'][$key] === 0) {

            $filename = time() . '_' . $key . '_' . basename($_FILES['images']['name'][$key]);
            $target = $uploadDir . $filename;

            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (!in_array($extension, $allowedExtensions)) {
                continue;
            }
            
            if (move_uploaded_file($tmp_name, $target)) {

                $stmt = $pdo->prepare("INSERT INTO album_images(album_id,filename) VALUES(?,?)");
                $stmt->execute([$album_id, $filename]);
            }
        }
    }

    // header("Location: gallery.php?album_id=$album_id&status=uploaded");
    if ($uploaded){
        header("Location: gallery.php?album_id=$album_id&status=uploaded");
    } else {
        header("Location: gallery.php?album_id=$album_id&status=invalid_file");
    }
    exit;
}


/* DELETE IMAGE */

if (isset($_GET['delete_id'])) {

    $id = (int)$_GET['delete_id'];

    $stmt = $pdo->prepare("SELECT filename FROM album_images WHERE id=?");
    $stmt->execute([$id]);
    $img = $stmt->fetch();

    if ($img) {

        $path = "uploads/gallery/" . $img['filename'];

        if (file_exists($path)) {
            unlink($path);
        }

        $pdo->prepare("DELETE FROM album_images WHERE id=?")->execute([$id]);
    }

    header("Location: gallery.php?album_id=$album_id&status=img_deleted");
    exit;
}


$stmt = $pdo->prepare("SELECT * FROM album_images WHERE album_id=? ORDER BY id DESC");
$stmt->execute([$album_id]);
$images = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Album</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .main-content {
            margin-left: 220px;
            padding: 25px;
        }

        .album-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-img-top {
            height: 220px;
            object-fit: cover;
        }

        .gallery-card {
            border: none;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }

        .gallery-card:hover {
            transform: translateY(-4px);
        }

        .delete-btn {
            position: absolute;
            top: 8px;
            right: 8px;
        }

        .upload-btn input {
            display: none;
        }
    </style>

</head>


<body class="d-flex flex-column min-vh-100">

    <?php include 'sidebar.php'; ?>
    <?php include 'header.php'; ?>

    <div class="main-content">

        <div class="album-header">

            <h3>Album Gallery</h3>

            <a href="index.php" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left"></i> Back
            </a>

        </div>


        <div class="d-flex justify-content-between align-items-center mb-3">

            <h5>
                <i class="fa fa-folder"></i>
                <?= htmlspecialchars($album['name']) ?>
            </h5>

            <div>

                <button class="btn btn-primary btn-sm me-2" onclick="showEdit()">
                    <i class="fa fa-pen"></i> Edit
                </button>

                <label class="btn btn-success btn-sm upload-btn">

                    <i class="fa fa-upload"></i> Upload

                    <form action="gallery.php?album_id=<?= $album_id ?>" method="post" enctype="multipart/form-data">

                        <input type="file" name="images[]" multiple accept=".jpg, .jpeg, .png" onchange="this.form.submit()">

                    </form>

                </label>

                <button class="btn btn-danger btn-sm" onclick="deleteAlbum()">

                    <i class="fa fa-trash"></i> Delete Album

                </button>

            </div>

        </div>



        <div id="editSection" class="card p-3 mb-4 d-none">

            <form method="post">

                <input type="hidden" name="update_album" value="1">

                <div class="row">

                    <div class="col-md-6">

                        <label>Album Name</label>

                        <input type="text" name="album_name" class="form-control" value="<?= htmlspecialchars($album['name']) ?>">

                    </div>

                    <div class="col-md-3">

                        <label>Session</label>

                        <input type="text" name="album_session" class="form-control" value="<?= htmlspecialchars($album['session']) ?>">

                    </div>

                    <div class="col-md-3">

                        <label>Status</label>

                        <select name="status" class="form-control">

                            <option value="published" <?= $album['status'] == 'published' ? 'selected' : '' ?>>Published</option>

                            <option value="draft" <?= $album['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>

                        </select>

                    </div>

                </div>

                <button class="btn btn-success btn-sm mt-3">

                    <i class="fa fa-check"></i> Update

                </button>

                <button type="button" class="btn btn-secondary btn-sm mt-3" onclick="hideEdit()">Close</button>

            </form>

        </div>



        <div class="row">

            <?php foreach ($images as $img): ?>

                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">

                    <div class="card gallery-card position-relative">

                        <a href="uploads/gallery/<?= htmlspecialchars($img['filename']) ?>" target="_blank">

                            <img src="uploads/gallery/<?= htmlspecialchars($img['filename']) ?>" class="card-img-top">

                        </a>

                        <a href="?album_id=<?= $album_id ?>&delete_id=<?= $img['id'] ?>" class="btn btn-danger btn-sm delete-btn delete-image">

                            <i class="fa fa-trash"></i>

                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>


    <script>
        function showEdit() {
            document.getElementById("editSection").classList.remove("d-none");
        }

        function hideEdit() {
            document.getElementById("editSection").classList.add("d-none");
        }


        /* IMAGE DELETE */

        document.querySelectorAll(".delete-image").forEach(btn => {

            btn.addEventListener("click", function(e) {

                e.preventDefault();

                let link = this.href;

                Swal.fire({

                    title: "Delete Image?",
                    text: "This image will be permanently deleted.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Yes Delete"

                }).then((result) => {

                    if (result.isConfirmed) {

                        window.location.href = link;

                    }

                });

            });

        });


        /* DELETE ALBUM */

        function deleteAlbum() {

            Swal.fire({

                title: "Delete Album?",
                text: "All images inside this album will also be deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                confirmButtonText: "Delete Album"

            }).then((result) => {

                if (result.isConfirmed) {

                    window.location.href = "gallery.php?album_id=<?= $album_id ?>&delete_album=1";

                }

            });

        }


        /* SUCCESS ALERTS */

        const params = new URLSearchParams(window.location.search);

        if (params.get("status") === "img_deleted") {

            Swal.fire("Deleted", "Image deleted successfully", "success");

        }

        if (params.get("status") === "uploaded") {

            Swal.fire("Uploaded", "Images uploaded successfully", "success");

        } else if (params.get("status") === "invalid_file"){
            swal.fire("Invalid_file", "Only JPG, PNG and JPEG file are allowed.", "error");
        }

        if (params.get("status") === "updated") {

            Swal.fire("Updated", "Album updated successfully", "success");

        }
    </script>

    <?php include 'footer.php'; ?>

</body>

</html>