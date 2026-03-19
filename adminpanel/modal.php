<?php
include 'dbconnect.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

/* DELETE */

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $stmt = $pdo->prepare("DELETE FROM add_modal WHERE id=?");
    $stmt->execute([$id]);

    $_SESSION['success'] = "Modal deleted successfully";
    header("Location: modal.php");
    exit;
}

/* FETCH DATA */

$events = $pdo->query("SELECT * FROM add_modal ORDER BY id DESC")
    ->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <title>Add Modal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/header.css">

</head>

<body class="d-flex flex-column min-vh-100">

    <?php include 'sidebar.php'; ?>
    <?php include 'header.php'; ?>

    <div class="main-content" style="margin-left:220px;padding:20px;">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h3>Modal Notifications</h3>

            <button class="btn btn-primary btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#notificationModal">

                + Add Notification Modal

            </button>

        </div>

        <table class="table table-bordered table-striped">

            <thead class="table-dark">

                <tr>

                    <th>Image</th>
                    <th>Description</th>
                    <th>Link</th>
                    <th style="width:160px;">Action</th>

                </tr>

            </thead>

            <tbody>

                <?php foreach ($events as $event): ?>

                    <tr>

                        <td>

                            <?php if (!empty($event['banner_image'])): ?>

                                <img src="uploads/modal/<?php echo $event['banner_image']; ?>"
                                    width="80"
                                    class="img-thumbnail">

                            <?php endif; ?>

                        </td>

                        <td><?= htmlspecialchars($event['description']) ?></td>

                        <td>

                            <a href="<?= htmlspecialchars($event['link']) ?>" target="_blank">

                                <?= htmlspecialchars($event['link']) ?>

                            </a>

                        </td>

                        <td>

                            <button class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal<?= $event['id'] ?>">

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


    <!-- ADD MODAL -->

    <div class="modal fade" id="notificationModal">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">

                        <i class="fas fa-bell"></i> Add Notification

                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <form method="POST" action="save_modal.php" enctype="multipart/form-data">

                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label">Notification Description</label>

                            <textarea name="description"
                                class="form-control"
                                rows="4"
                                required></textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Banner Image</label>

                            <input type="file"
                                name="banner_image"
                                class="form-control"
                                accept=".jpg, .jpeg, .png">

                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notification Link</label>
                            <input type="text" name="link" class="form-control" placeholder="https://example.com">
                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="save_notification" class="btn btn-primary">
                            Save Notification
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- EDIT MODALS -->

    <?php foreach ($events as $event): ?>
        <div class="modal fade" id="editModal<?= $event['id'] ?>">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-bell"></i> Edit Notification
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="save_modal.php" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $event['id'] ?>">

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($event['description']) ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Banner Image</label>
                                <?php if (!empty($event['banner_image'])): ?>
                                    <br>
                                    <img src="uploads/modal/<?php echo $event['banner_image']; ?>" width="120" class="mb-2">
                                <?php endif; ?>

                                <input type="file" name="banner_image" class="form-control" accept=".jpg, .png, .jpeg">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Link</label>
                                <input type="text" name="link" class="form-control" value="<?= htmlspecialchars($event['link']) ?>">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="submit" name="update" class="btn btn-primary">
                                Update
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        /* DELETE CONFIRM */

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This notification will be permanently deleted!",
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


        /* SUCCESS ALERT */

        <?php if (isset($_SESSION['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?= $_SESSION['success']; ?>',
                timer: 2000,
                showConfirmButton: false
            });
        <?php unset($_SESSION['success']);
        endif; ?>

        <?php if (isset($_SESSION['error'])): ?>

            Swal.fire({
                icon: 'error',
                title: 'Invalid File',
                text: '<?= $_SESSION['error']; ?>'
            });

        <?php unset($_SESSION['error']);
        endif; ?>
    </script>

</body>

</html>