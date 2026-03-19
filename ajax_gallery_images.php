<?php
include "adminpanel/dbconnect.php";

$album_id = isset($_GET['album_id']) ? (int)$_GET['album_id'] : 0;

$stmt=$pdo->prepare("SELECT * FROM album_images WHERE album_id=? ORDER BY id DESC");

$stmt->execute([$album_id]);

$images=$stmt->fetchAll();

echo '<div class="row">';

foreach($images as $img){
?>

<div class="col-lg-3 col-md-4 col-sm-6 mb-4">

<a href="adminpanel/uploads/gallery/<?= $img['filename'] ?>" 
data-lightbox="gallery">

<img src="adminpanel/uploads/gallery/<?= $img['filename'] ?>" class="gallery-img">

</a>

</div>

<?php
}

echo '</div>';
?>