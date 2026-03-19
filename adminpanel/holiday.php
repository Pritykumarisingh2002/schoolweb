<?php
include 'dbconnect.php';
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Add Holiday
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $date = $_POST['date'];
    $stmt = $pdo->prepare("INSERT INTO holidays (name, date) VALUES (?, ?)");
    $stmt->execute([$name, $date]);
    $_SESSION['success'] = "Slider added successfully!";
    header("Location: holiday.php");
    exit;
}

// Delete Holiday
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM holidays WHERE id = ?")->execute([$id]);
    $_SESSION['success'] = "Slider deleted successfully!";
    header("Location: holiday.php");
    exit;
}

// Update Holiday
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $name = trim($_POST['name']);
    $date = $_POST['date'];
    $stmt = $pdo->prepare("UPDATE holidays SET name = ?, date = ? WHERE id = ?");
    $stmt->execute([$name, $date, $id]);
    $_SESSION['success'] = "Slider updated successfully!";
    header("Location: holiday.php");
    exit;
}

// Search / Fetch Holidays
$where = [];
$params = [];

if (!empty($_GET['search_name'])) {
    $where[] = "(name LIKE ? OR date LIKE ?)";
    $params[] = '%' . $_GET['search_name'] . '%';
    $params[] = '%' . $_GET['search_name'] . '%';
}

$sql = "SELECT * FROM holidays";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY date";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$holidays = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Holidays</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/header.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php include 'sidebar.php'; ?>
    <?php include 'header.php'; ?>

    <div class="main-content" style="margin-left: 220px; padding: 20px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Holidays</h3>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                + Add Holiday
            </button>
        </div>

        <!-- Search Form -->
        <div class="mb-3">
            <form method="get" class="row g-2">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" name="search_name" class="form-control" placeholder="Search by name or date" value="<?= htmlspecialchars($_GET['search_name'] ?? '') ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th width="180">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($holidays) > 0): ?>
                    <?php foreach ($holidays as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['date']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">
                                    <i class="fa fa-edit"></i> Edit
                                </button>

                                <button onclick="confirmDelete(<?= $row['id'] ?>)"
                                    class="btn btn-danger btn-sm mb-1">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No holidays found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add" class="btn btn-success">Add Holiday</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modals -->
    <?php foreach ($holidays as $row): ?>
        <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <form method="post" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Holiday</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <div class="mb-3">
                            <label>Date</label>
                            <input type="date" name="date" value="<?= $row['date'] ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="form-control" required>
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
                    window.location.href = "holiday.php?delete=" + id;
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

</body>

</html>