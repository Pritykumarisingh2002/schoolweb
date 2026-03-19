<?php
include '../../shcjsr/dbconnect.php';

try {
    $stmt = $pdo->query("SELECT * FROM albums ORDER BY id DESC");
    $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $thumbnails = [];
    foreach ($albums as $album) {
        $img_stmt = $pdo->prepare("SELECT filename FROM album_images WHERE album_id = ? ORDER BY id ASC LIMIT 1");
        $img_stmt->execute([$album['id']]);
        $img = $img_stmt->fetch();
        $thumbnails[$album['id']] = $img ? '../../shcjsr/uploads/' . $img['filename'] : 'https://via.placeholder.com/400x200?text=No+Image';
    }

    if ($albums) {
        $output = '';
        foreach ($albums as $index => $album) {
            if (($index + 1) % 3 === 1) $output .= '<div class="row mb-4">';
            $id = $album['id'];
            $name = $album['name'];
            $img = $thumbnails[$id];

            $output .= '
            <div class="col-lg-4 col-md-4 wow" data-wow-offset="150">
                <a href="album-view.php?id=' . $id . '&a=' . urlencode($name) . '" class="grid_item">
                    <figure>
                        <img src="' . htmlspecialchars($img) . '" class="img-fluid" alt="' . htmlspecialchars($name) . '">
                        <p class="text-center1"><b>' . htmlspecialchars($name) . '</b></p>
                    </figure>
                </a>
            </div>';

            if (($index + 1) % 3 === 0) $output .= '</div>';
        }
        if (count($albums) % 3 !== 0) $output .= '</div>'; 
        echo $output;
    } else {
        echo '<p class="text-center">No Album Found.</p>';
    }
} catch (PDOException $e) {
    echo '<p class="text-danger">Database Error: ' . $e->getMessage() . '</p>';
}
