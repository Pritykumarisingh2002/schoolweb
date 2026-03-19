<?php
include 'dbconnect.php'; 
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $notification = trim($_POST['notification']);
    $stmt = $pdo->prepare("INSERT INTO notifications (notification) VALUES (?)");
    $stmt->execute([$notification]);
    $_SESSION['success'] = "Notification added successfully!";
    header("Location: notification.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $notification = trim($_POST['notification']);
    $stmt = $pdo->prepare("UPDATE notifications SET notification= ? WHERE id = ?");
    $stmt->execute([$notification,  $id]);
    $_SESSION['success'] = "Notification updated successfully!";
    header("Location: notification.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM notifications WHERE id = ?")->execute([$id]);
     $_SESSION['success'] = "Notification deleted successfully!";
    header("Location: notification.php");
    exit;
}

$events = $pdo->query("SELECT * FROM notifications ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/header.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include 'sidebar.php'; ?>
    <?php include 'header.php'; ?>

    <div class="main-content" style="margin-left: 220px; padding: 20px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Notification</h3>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                + Add Notification
            </button>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Notification</th>
                    <th style="width: 155px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td>
                            <strong style="color: black;">
                                <?php
                                    $text = htmlspecialchars($event['notification']);
                                    $text = preg_replace(
                                        '/(https?:\/\/[^\s]+)/i',
                                        '<a href="$1" target="_blank" style="color: blue;">$1</a>',
                                        $text
                                    );
                                    echo $text;
                                ?>
                            </strong>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $event['id'] ?>">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <button onclick="confirmDelete(<?= $event['id'] ?>)"
                            class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Notification Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="notification_add" class="form-label">Notification</label>
                        <input type="text" id="notification_add" name="notification" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add" class="btn btn-success">Add Notification</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Notification Modals -->
    <?php foreach ($events as $event): ?>
        <div class="modal fade" id="editModal<?= $event['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <form method="post" class="modal-content bg-white">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Notification</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $event['id'] ?>">
                        <div class="mb-3">
                            <label for="notification<?= $event['id'] ?>" class="form-label">Notification</label>
                            <input type="text" id="notification<?= $event['id'] ?>" name="notification"
                                   class="form-control"
                                   value="<?= htmlspecialchars($event['notification'], ENT_QUOTES) ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; ?>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

<script>
/* ================= DELETE CONFIRM ================= */
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This event will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "?delete=" + id;
        }
    });
}

/* ================= SUCCESS ALERT ================= */
<?php if (isset($_SESSION['success'])): ?>
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: '<?= $_SESSION['success']; ?>',
    timer: 2000,
    showConfirmButton: false
});
<?php unset($_SESSION['success']); endif; ?>
</script>

</body>

</html>
