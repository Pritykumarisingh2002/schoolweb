<?php
include 'dbconnect.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

/* DELETE MESSAGE */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $stmt = $pdo->prepare("DELETE FROM suggestion WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = "Suggestion deleted successfully!";

    header("Location: view_suggestion.php");
    exit;
}

/* FETCH MESSAGES */
$stmt = $pdo->prepare("SELECT * FROM suggestion ORDER BY created_at DESC");
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Contact Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/header.css">
</head>

<body class="d-flex flex-column min-vh-100">

<?php include 'sidebar.php'; ?>
<?php include 'header.php'; ?>

<div class="main-content" style="margin-left: 220px; padding: 30px;">
    <div class="container-fluid">

        <h3 class="mb-4">Parent's Suggestion</h3>

        <?php if ($messages): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Parent Name</th>
                            <th>Student Name</th>
                            <th>Adm_No.</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Suggestion</th>
                            <th>Date</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $msg): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($msg['pname']); ?></td>
                                <td><?php echo htmlspecialchars($msg['cname']); ?></td>
                                <td><?php echo htmlspecialchars($msg['admno']); ?></td>
                                <td><?php echo $msg['mobile']; ?></td>
                                <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                <td><?php echo htmlspecialchars($msg['suggestion']); ?></td>
                                <td><?php echo $msg['created_at']; ?></td>
                                <td>
                                    <button onclick="confirmDelete(<?= $msg['id'] ?>)"
                                    class="btn btn-danger btn-sm mb-1">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                No messages found.
            </div>
        <?php endif; ?>

    </div>
</div>

<?php include 'footer.php'; ?>

<script>
        /* ================= DELETE CONFIRM ================= */
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This image will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "view_suggestion.php?delete=" + id;
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
        <?php unset($_SESSION['success']);
        endif; ?>

        /* ================= ERROR ALERT ================= */
        <?php if (isset($_SESSION['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?= $_SESSION['error']; ?>'
            });
        <?php unset($_SESSION['error']);
        endif; ?>
    </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>