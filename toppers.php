<?php
include 'adminpanel/dbconnect.php';

$stmt = $pdo->query("SELECT * FROM toppers ORDER BY session DESC, class_name ASC");
$toppers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Our Toppers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 60px 20px;
            margin: 0;
        }

        h2 {
            text-align: center;
            margin-bottom: 50px;
            color: #8b0000;
            font-size: 32px;
        }

        .topper-container {
            max-width: 1200px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .topper-card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: 0.3s;
        }

        .topper-card:hover {
            transform: translateY(-5px);
        }

        .topper-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #8b0000;
            margin-bottom: 15px;
        }

        .student-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .class-name {
            color: #555;
            margin-bottom: 5px;
        }

        .position {
            font-weight: bold;
            color: #8b0000;
        }

        .no-data {
            text-align: center;
            font-size: 18px;
            color: #777;
        }
    </style>
</head>
<body>

<h2> Our Toppers</h2>

<?php if ($toppers): ?>
<div class="topper-container">

    <?php foreach ($toppers as $topper): ?>
        <div class="topper-card">
            <img class="topper-img" 
                 src="adminpanel/uploads/toppers/<?php echo $topper['image_path']; ?>" 
                 alt="Topper Image">

            <div class="student-name">
                <?php echo htmlspecialchars($topper['student_name']); ?>
            </div>

            <div class="class-name">
                Class: <?php echo htmlspecialchars($topper['class_name']); ?>
            </div>

            <div class="position">
                Position: <?php echo htmlspecialchars($topper['position']); ?>
            </div>
        </div>
    <?php endforeach; ?>

</div>
<?php else: ?>
    <p class="no-data">No toppers available.</p>
<?php endif; ?>

</body>
</html>