<!DOCTYPE html>
<html>
<head>
    <title>Manage Documents</title>
    <style>
        body { font-family: Arial; background:#f4f4f4; padding:30px; }
        .container { max-width:900px; margin:auto; background:#fff; padding:30px; border-radius:8px; }
        h2 { text-align:center; color:#8b0000; }
        select, input[type="file"] { padding:8px; width:100%; margin-bottom:15px; }
        button { background:#8b0000; color:#fff; padding:8px 15px; border:none; cursor:pointer; }
        table { width:100%; margin-top:30px; border-collapse:collapse; }
        table, th, td { border:1px solid #ddd; }
        th { background:#8b0000; color:white; }
        th, td { padding:10px; text-align:left; }
        .msg { color:green; margin-bottom:15px; }
        .delete { color:red; text-decoration:none; }
    </style>
</head>
<body>

<div class="container">
    <h2>Document Management</h2>

    <?php if ($message): ?>
        <p class="msg"><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- Upload Form -->
    <form id="uploadForm" enctype="multipart/form-data">

    <label>Select Section</label>
    <select name="category" id="category" required onchange="toggleClassField()">
        <option value="">-- Select Section --</option>
        <option value="book_list">Book List</option>
        <option value="exam_portion">Exam Portion</option>
        <option value="academic_calendar">Academic Calendar</option>
    </select>

    <div id="classField" style="display:none;">
        <label>Enter Class</label>
        <input type="text" name="class_name" placeholder="e.g. Class 1">
    </div>

    <label>Upload PDF</label>
    <input type="file" name="document" accept=".pdf" required>

    <button type="submit">Upload</button>

    <p id="responseMsg" style="margin-top:10px;"></p>
</form>

    <!-- Documents Table -->
    <h3>Uploaded Files</h3>

    <table>
        <tr>
            <th>ID</th>
            <th>File Name</th>
            <th>Category</th>
            <th>Uploaded Date</th>
            <th>Action</th>
        </tr>

        <?php
        $docs = $pdo->query("SELECT * FROM documents ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($docs as $doc):
        ?>
        <tr>
            <td><?php echo $doc['id']; ?></td>
            <td><?php echo $doc['file_name']; ?></td>
            <td><?php echo ucfirst(str_replace('_',' ',$doc['category'])); ?></td>
            <td><?php echo $doc['uploaded_at']; ?></td>
            <td>
                <a href="../uploads/<?php echo $doc['file_path']; ?>" target="_blank">View</a> |
                <a href="?delete=<?php echo $doc['id']; ?>" class="delete" onclick="return confirm('Delete this file?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

</div>
<script>
function toggleClassField() {
    var category = document.getElementById("category").value;
    var classField = document.getElementById("classField");

    if (category === "book_list") {
        classField.style.display = "block";
    } else {
        classField.style.display = "none";
    }
}

document.getElementById("uploadForm").addEventListener("submit", function(e) {
    e.preventDefault();

    var formData = new FormData(this);

    fetch("upload_handler.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        var msg = document.getElementById("responseMsg");
        msg.innerHTML = data.message;
        msg.style.color = data.status ? "green" : "red";

        if (data.status) {
            document.getElementById("uploadForm").reset();
            document.getElementById("classField").style.display = "none";
            setTimeout(() => location.reload(), 1000);
        }
    })
    .catch(error => {
        console.error("Error:", error);
    });
});
</script>
</body>
</html>