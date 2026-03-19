<?php
include 'adminpanel/dbconnect.php';

$results = $pdo->query("SELECT * FROM exam_routine ORDER BY updated_at DESC")->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $r) {

echo "<tr>";

echo "<td>".htmlspecialchars($r['Exam_Notification'])."</td>";

if(!empty($r['file_path'])){

echo "<td>
<a class='pdf-btn'
href='adminpanel/".$r['file_path']."'
target='_blank'>
View PDF
</a>
</td>";

}else{

echo "<td>
<a class='pdf-btn'
href='javascript:void(0)'
onclick=\"alert('No file found')\">
View PDF
</a>
</td>";

}

echo "</tr>";
}
?>