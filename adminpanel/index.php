<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login");
    exit;
}

include 'dbconnect.php';

if (isset($_GET['delete_id'])) {
    $album_id = (int)$_GET['delete_id'];

    $stmt = $pdo->prepare("SELECT filename FROM album_images WHERE album_id = ?");
    $stmt->execute([$album_id]);
    foreach ($stmt->fetchAll() as $img) {
        $path = 'uploads/gallery/' . $img['filename'];
        if (file_exists($path)) {
            unlink($path);
        }
    }

    $pdo->prepare("DELETE FROM album_images WHERE album_id = ?")->execute([$album_id]);
    $pdo->prepare("DELETE FROM albums WHERE id = ?")->execute([$album_id]);

    header("Location: index.php?status=deleted");
    exit;
}

try {
    $stmt = $pdo->query("SELECT * FROM albums ORDER BY id DESC");
    $albums = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Photo Album Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/header.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .card-img-top {
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #ccc;
        }
        .card-title {
            font-weight: bold;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<?php include 'sidebar.php'; ?>
<?php include 'header.php'; ?>

<div class="main-content" style="margin-left: 220px; padding: 20px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fa fa-images"></i> Published Albums</h3>
        <a href="album_image_crud.php" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> New Album</a>
    </div>

    <div class="row">
        <?php if (count($albums) === 0): ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">No albums found. Click "New Album" to create one.</div>
            </div>
        <?php endif; ?>

        <?php foreach ($albums as $album): ?>
            <?php
                $img_stmt = $pdo->prepare("SELECT filename FROM album_images WHERE album_id = ? ORDER BY id ASC LIMIT 1");
                $img_stmt->execute([$album['id']]);
                $image = $img_stmt->fetch();
                $thumbnail = $image ? 'uploads/gallery/' . $image['filename'] : 'https://via.placeholder.com/400x200?text=No+Image';

                $created = $album['created_at'] ?? date('Y-m-d H:i:s'); 
                $date = date('jS F Y', strtotime($created));
                $time = date('h:i A', strtotime($created));
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?= htmlspecialchars($thumbnail) ?>" class="card-img-top" alt="Album Image">

                    <div class="card-body">
                        <h5 class="card-title mb-2"><?= htmlspecialchars($album['name']) ?></h5>
                        <div class="d-flex justify-content-between">
                        <p class="card-text mb-1"><i class="fa fa-calendar-alt"></i> <?= $date ?></p>
                        <p class="card-text mb-2"><i class="fa fa-clock"></i> <?= $time ?></p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="gallery.php?album_id=<?= $album['id'] ?>" class="btn btn-sm btn-primary">
                                <i class="fa fa-images"></i> Manage
                            </a>
                            <a href="index.php?delete_id=<?= $album['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this album?');">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>

/* DELETE CONFIRMATION */

document.querySelectorAll(".delete-btn").forEach(button => {

button.addEventListener("click", function(e){

e.preventDefault();

const link = this.getAttribute("href");

Swal.fire({

title:"Are you sure?",
text:"This album and all images will be deleted!",
icon:"warning",
showCancelButton:true,
confirmButtonColor:"#d33",
cancelButtonColor:"#3085d6",
confirmButtonText:"Yes delete it"

}).then((result)=>{

if(result.isConfirmed){

window.location.href = link;

}

});

});

});


/* SUCCESS ALERT */

const params = new URLSearchParams(window.location.search);

if(params.get("status")==="deleted"){

Swal.fire({

icon:"success",
title:"Deleted",
text:"Album deleted successfully!"

});

}

</script>
</body>
</html>
