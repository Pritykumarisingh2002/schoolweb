<?php
include 'adminpanel/dbconnect.php';

// $toppers = $pdo->query("SELECT * FROM toppers ORDER BY created_at DESC")->fetchAll();
// $results  = $pdo->query("SELECT * FROM exam_routine ORDER BY updated_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Jamshedpur Public School</title>
<link rel="stylesheet" href="styles.css">
</head>

<body>
<?php include('navbar.php'); ?>

    <!-- Exam Routine -->
    <h2 class="section-title">Exam Time Table</h2>

    <table class="result-table">
        <tr>
            <th>Exam Notification</th>
            <th>Action</th>
        </tr>
        <tbody id="examRoutineTable">

        </tbody>

    </table>
    
<?php include('footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function loadExamRoutine() {
  $.ajax({
    url: 'ajax_exam_routine.php',
    method: 'GET',
    cache: false,
    success: function (data) {
      $('#examRoutineTable').html(data);
    },
    error: function () {
      $('#examRoutineTable').html('<tr><td colspan="2">Error loading exam routine.</td></tr>');
    }
  });
}

loadExamRoutine(); 
setInterval(loadExamRoutine, 1000); 
</script>
</body>
</html>