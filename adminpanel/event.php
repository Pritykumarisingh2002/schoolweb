<?php
include 'dbconnect.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

/* ================= ADD EVENT ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $event_name = trim($_POST['event_name']);
    $event_date = $_POST['event_date'];

    $stmt = $pdo->prepare("INSERT INTO forthcoming_events (event_name, event_date) VALUES (?, ?)");
    $stmt->execute([$event_name, $event_date]);

    $_SESSION['success'] = "Event added successfully!";
    header("Location: event.php");
    exit;
}

/* ================= UPDATE EVENT ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $event_name = trim($_POST['event_name']);
    $event_date = $_POST['event_date'];

    $stmt = $pdo->prepare("UPDATE forthcoming_events SET event_name = ?, event_date = ? WHERE id = ?");
    $stmt->execute([$event_name, $event_date, $id]);

    $_SESSION['success'] = "Event updated successfully!";
    header("Location: event.php");
    exit;
}

/* ================= DELETE EVENT ================= */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    $pdo->prepare("DELETE FROM forthcoming_events WHERE id = ?")->execute([$id]);

    $_SESSION['success'] = "Event deleted successfully!";
    header("Location: event.php");
    exit;
}

/* ================= SEARCH & FETCH ================= */
$where = [];
$params = [];

if (!empty($_GET['search_name'])) {
    $where[] = "event_name LIKE ?";
    $params[] = '%' . $_GET['search_name'] . '%';
}

$sql = "SELECT * FROM forthcoming_events";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY event_date";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Forthcoming Events</title>
    <meta charset="UTF-8">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/header.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="d-flex flex-column min-vh-100">

<?php include 'sidebar.php'; ?>
<?php include 'header.php'; ?>

<div class="main-content" style="margin-left: 220px; padding: 20px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Forthcoming Events</h3>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
            + Add Event
        </button>
    </div>

    <!-- Search -->
    <div class="mb-3">
        <form method="get" class="row g-2">
            <div class="col-md-12">
                <div class="input-group">
                    <input type="text" name="search_name" class="form-control"
                        placeholder="Search by Event Name"
                        value="<?= htmlspecialchars($_GET['search_name'] ?? '') ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Event Name</th>
                <th style="width: 180px;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($events): ?>
                <?php foreach ($events as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['event_date']) ?></td>
                        <td><?= htmlspecialchars($row['event_name']) ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal<?= $row['id'] ?>">
                                <i class="fa fa-edit"></i> Edit
                            </button>

                            <button onclick="confirmDelete(<?= $row['id'] ?>)"
                                class="btn btn-sm btn-danger">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">No events found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- ADD MODAL -->
<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <form method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Forthcoming Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Event Date</label>
                    <input type="date" name="event_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Event Name</label>
                    <input type="text" name="event_name" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="add" class="btn btn-success">Add Event</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MODALS -->
<?php foreach ($events as $row): ?>
<div class="modal fade" id="editModal<?= $row['id'] ?>">
    <div class="modal-dialog">
        <form method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <div class="mb-3">
                    <label>Event Date</label>
                    <input type="date" name="event_date"
                        value="<?= htmlspecialchars($row['event_date']) ?>"
                        class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Event Name</label>
                    <input type="text" name="event_name"
                        value="<?= htmlspecialchars($row['event_name'], ENT_QUOTES) ?>"
                        class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="update" class="btn btn-primary">
                    Update
                </button>
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