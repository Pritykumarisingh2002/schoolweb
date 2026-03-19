<?php

include 'adminpanel/dbconnect.php';
header('Content-Type: application/json');

$booklists=$pdo->query("SELECT * from documents where category='book_list' order by class_name ASC");
$booklist_html = "";
foreach ($booklists as $booklist){
    $booklist_html .= '
<tr>

<td>' . htmlspecialchars($booklist['class_name']) . '</td>

<td>' . htmlspecialchars($booklist['uploaded_at']) . '</td>

<td>

<a class="pdf-btn"
href="adminpanel/uploads/' . htmlspecialchars($booklist['file_path']) . '"
target="_blank">
View PDF
</a>

</td>

</tr>

';
}
echo json_encode([
    "booklist" => $booklist_html
]);
?>
