<?php

include 'adminpanel/dbconnect.php';

$stmt = $pdo->query("SELECT notification FROM notifications ORDER BY id DESC");

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

echo '<div class="notice-card">';

echo $row['notification'];

echo '</div>';

}

?>