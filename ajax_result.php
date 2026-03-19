<?php
header('Content-Type: application/json');
include 'adminpanel/dbconnect.php';

$toppers = $pdo->query("SELECT * FROM toppers ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$results = $pdo->query("SELECT * FROM student_results ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);


$topper_html = "";

foreach ($toppers as $topper) {

    $topper_html .= '

<div class="topper-card">

<img src="adminpanel/uploads/toppers/' . $topper['image_path'] . '" alt="Topper Image">

<h3>' . htmlspecialchars($topper['student_name']) . '</h3>

<p>Class: ' . htmlspecialchars($topper['class_name']) . '</p>

<span class="position">' . htmlspecialchars($topper['position']) . '</span>

</div>

';
}


$result_html = "";

foreach ($results as $r) {

    $result_html .= '

<tr>

<td>' . htmlspecialchars($r['class_name']) . '</td>

<td>' . htmlspecialchars($r['section']) . '</td>

<td>' . htmlspecialchars($r['session']) . '</td>

<td>

<a class="pdf-btn"
href="adminpanel/uploads/results/' . $r['pdf_file'] . '"
target="_blank">
View PDF
</a>

</td>

</tr>

';
}

echo json_encode([
    "toppers" => $topper_html,
    "results" => $result_html
]);
