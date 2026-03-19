<?php
include "adminpanel/dbconnect.php";
$album_id = isset($_GET['album_id']) ? (int)$_GET['album_id'] : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jamshedpur Public School</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css">
    <style>

/* SECTION */

#gallery-section{
padding:60px 0;
background:#f8f9fa;
}

/* HEADING */

#gallery-section h2{
text-align:center;
font-weight:bold;
margin-bottom:40px;
color:#8b0000;
}

/* ALBUM HEADER */

.album-header{
display:flex;
justify-content:space-between;
align-items:center;
background:linear-gradient(135deg,#8b0000,#c40000);
padding:18px 25px;
border-radius:10px;
color:white;
box-shadow:0 4px 12px rgba(0,0,0,0.2);
margin-bottom:30px;
}

.album-title{
font-size:22px;
font-weight:bold;
}

.back-btn{
background:white;
color:#8b0000;
border:none;
padding:8px 16px;
border-radius:6px;
font-weight:600;
text-decoration:none;
}

.back-btn:hover{
background:#f2f2f2;
color:#8b0000;
}

/* ALBUM CARDS */

.album-card{
border-radius:10px;
overflow:hidden;
transition:0.3s;
box-shadow:0 4px 15px rgba(0,0,0,0.15);
}

.album-card img{
width:100%;
height:200px;
object-fit:cover;
transition:0.3s;
}

.album-card:hover img{
transform:scale(1.08);
}

/* GALLERY IMAGES */

.gallery-img{
width:100%;
height:260px;
object-fit:cover;
border-radius:8px;
transition:0.3s;
box-shadow:0 3px 12px rgba(0,0,0,0.2);
}

.gallery-img:hover{
transform:scale(1.05);
}

</style> 

</head>

<body>

    <?php include('navbar.php'); ?>

    <section id="gallery-section">
        <div class="container">
            <h2>Gallery & Events</h2>

            <?php

            /* SHOW ALBUM LIST */

            if ($album_id == 0) {
                $stmt = $pdo->query("SELECT * FROM albums WHERE status='published' ORDER BY id DESC");
                $albums = $stmt->fetchAll();
                echo '<div class="row">';
                foreach ($albums as $album) {
                    $img_stmt = $pdo->prepare("SELECT filename FROM album_images WHERE album_id=? LIMIT 1");
                    $img_stmt->execute([$album['id']]);
                    $image = $img_stmt->fetch();
                    $thumbnail = $image ? "adminpanel/uploads/gallery/" . $image['filename'] : "https://via.placeholder.com/400x200?text=No+Image";
            ?>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="album-card">
                            <a href="gallery.php?album_id=<?= $album['id'] ?>">
                                <img src="<?= $thumbnail ?>">
                            </a>

                            <div class="p-3 text-center">
                                <h5><?= htmlspecialchars($album['name']) ?></h5>
                            </div>
                        </div>
                    </div>

                <?php
                }
                echo '</div>';
            }

            /* SHOW ALBUM IMAGES */ 
            else {

                $stmt = $pdo->prepare("SELECT * FROM albums WHERE id=?");
                $stmt->execute([$album_id]);
                $album = $stmt->fetch();
                ?>

                <div class="album-header">
                    <div class="album-title">
                        <i class="fa-solid fa-images"></i>
                        <?= htmlspecialchars($album['name']) ?>
                    </div>

                    <a href="gallery.php" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Back to Albums
                    </a>
                </div>

                <!-- AJAX Gallery Container -->

                <div id="gallery-container"></div>
            <?php
            }
            ?>
        </div>

    </section>

    <?php include('footer.php'); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
    <script>
        function loadGallery() {
            let album_id = <?= $album_id ?>;
            fetch("ajax_gallery_images.php?album_id=" + album_id)
                .then(res => res.text())
                .then(data => {
                    document.getElementById("gallery-container").innerHTML = data;
                });
        }

        /* First load */

        <?php if ($album_id != 0) { ?>
            loadGallery();
            setInterval(loadGallery, 5000);
        <?php } ?>
    </script>

</body>

</html>