<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

include 'dbconnect.php';


$album_id = isset($_GET['album_id']) ? (int)$_GET['album_id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {

    $result = validateFileUpload($_FILES['image'], 2 * 1024 * 1024);

    if ($result !== true) {
        $_SESSION['error'] = $result;
        header("Location: slider_image_crud.php?album_id=$album_id");
        exit;
    }

    if ($_FILES['image']['error'] === 0) {
        // if ($_FILES['image']['size'] <= 2 * 1024 * 1024) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
        if ($imageData === false) {
            $_SESSION['error'] = "Failed to read image data.";
            header("Location: slider_image_crud.php?album_id=$album_id");
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO slider_images (album_id, image_data) VALUES (?, ?)");
        $stmt->bindParam(1, $album_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $imageData, PDO::PARAM_LOB);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Slider updated successfully!";
            header("Location: slider.php?success=1");
            exit;
        } else {
            echo "<script>alert('Database insert failed.'); history.back();</script>";
            exit;
        }
        // } else {
        //     $_SESSION['error'] = "Upload error or file too large (Max 2MB). Please resize and try again.";
        //     header("Location: slider.php");
        //     exit;
        // }
    } else {
        $_SESSION['error'] = "Image upload error.";
        header("Location: slider.php");
        exit;
    }
}

$images = [];
if ($album_id) {
    $stmt = $pdo->prepare("SELECT id, image_data FROM slider_images WHERE album_id = ? ORDER BY id DESC");
    $stmt->execute([$album_id]);
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Slider Photos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/upload_album.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include 'sidebar.php'; ?>
    <?php include 'header.php'; ?>
    <div class="main-content" style="margin-left: 220px; padding: 20px;">

        <div class="album-header">
            <h3><b></b>Add-</b>Slider Photos <span style="color:red; font-size:16px">Recommended Size : Width=1280px </span></h3>
            <div>
                <a href="slider.php" class="btn btn-secondary btn-sm me-2">Back</a>
                <a href="https://bulkresizephotos.com/en" class="btn btn-secondary btn-sm me-2">Resize Photo</a>
            </div>
        </div>

        <div class="album-options mb-4">
            <form action="slider_image_crud.php?album_id=<?= $album_id ?>" method="post" enctype="multipart/form-data" class="d-inline">
                <div class="mb-3">
                    <label>Upload Photo : ( *Allowed file type : JPG, JPEG or PNG, Max : 2MB) </label>
                    <input type="file" name="image" id="image" class="form-control" accept=".jpg, .jpeg, .png" required>
                </div>
                <div>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="row">
                <?php foreach ($images as $img): ?>
                    <div class="col-md-3 mb-3">
                        <img src="data:image/jpeg;base64,<?= base64_encode($img['image_data']) ?>" class="img-fluid rounded border" />
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        /* DELETE CONFIRM */
        // function confirmDelete(id) {
        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: "This image will be permanently deleted!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#d33',
        //         cancelButtonColor: '#3085d6',
        //         confirmButtonText: 'Yes, delete it!'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             window.location.href = "slider.php?delete_id=" + id;
        //         }
        //     });
        // }

        /* SUCCESS ALERT */
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

        /* ERROR ALERT */
        <?php if (isset($_SESSION['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?= $_SESSION['error']; ?>'
            });
        <?php unset($_SESSION['error']);
        endif; ?>
    </script>
</body>

</html>