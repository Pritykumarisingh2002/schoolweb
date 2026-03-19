<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}

include 'dbconnect.php';

// Delete image
if (isset($_GET['delete_id'])) {
  $id = (int)$_GET['delete_id'];
  $stmt = $pdo->prepare("DELETE FROM slider_images WHERE id = ?");
  $stmt->execute([$id]);
  $_SESSION['success'] = "Slider deleted successfully!";
  header("Location: slider.php");
  exit;
}

// Update image
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id']) && isset($_FILES['new_image'])) {
  $result = validateFileUpload($_FILES['new_image'], 2 * 1024 * 1024);

    if ($result !== true) {
        $_SESSION['error'] = $result;
        header("Location: slider.php?album_id=$album_id");
        exit;
    }
  
  $edit_id = (int)$_POST['edit_id'];
  if ($_FILES['new_image']['error'] === 0 && $_FILES['new_image']['size'] <= 2 * 1024 * 1024) {
    $newImageData = file_get_contents($_FILES['new_image']['tmp_name']);
    $stmt = $pdo->prepare("UPDATE slider_images SET image_data = ? WHERE id = ?");
    $stmt->bindParam(1, $newImageData, PDO::PARAM_LOB);
    $stmt->bindParam(2, $edit_id);
    $stmt->execute();
    $_SESSION['success'] = "Slider updated successfully!";
    header("Location: slider.php");
    exit;
  } else {
     $_SESSION['error'] = "Upload error or file too large (Max 2MB).";
    header("Location: slider.php");
    exit;
    }
}

$stmt = $pdo->query("SELECT id, image_data FROM slider_images ORDER BY id DESC");
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Slider Photos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="css/header.css">
  <style>
    .card-img-top {
      height: 200px;
      object-fit: cover;
      border-bottom: 1px solid #ccc;
    }
  </style>
</head>

<body class="d-flex flex-column min-vh-100">

  <?php include 'sidebar.php'; ?>
  <?php include 'header.php'; ?>

  <div class="main-content" style="margin-left: 220px; padding: 20px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3><i class="fa fa-images"></i> Slider Photos</h3>
      <div>
        <!-- <a href="index.php" class="btn btn-secondary btn-sm me-2">Back</a> -->
        <a href="slider_image_crud" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add Slider Photos</a>
      </div>

    </div>

    <div class="row">
      <?php foreach ($images as $img): ?>
        <div class="col-md-3 mb-4">
          <div class="card shadow-sm">
            <img src="data:image/jpeg;base64,<?= base64_encode($img['image_data']) ?>" class="card-img-top" alt="Slider Image">
            <div class="card-body text-center">
              <button class="btn btn-info btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#viewModal<?= $img['id'] ?>">
                <i class="fa fa-eye"></i> View
              </button>

              <button class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#editModal<?= $img['id'] ?>">
                <i class="fa fa-edit"></i> Edit
              </button>

              <button onclick="confirmDelete(<?= $img['id'] ?>)"
                class="btn btn-danger btn-sm">
                <i class="fa fa-trash"></i> Delete
              </button>
            </div>
          </div>
        </div>

        <!-- View Modal -->
        <div class="modal fade" id="viewModal<?= $img['id'] ?>" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-center">
                <img src="data:image/jpeg;base64,<?= base64_encode($img['image_data']) ?>" class="img-fluid rounded" />
              </div>
            </div>
          </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal<?= $img['id'] ?>" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <form method="post" enctype="multipart/form-data">
                <div class="modal-header">
                  <h5 class="modal-title">Update Image</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="edit_id" value="<?= $img['id'] ?>">
                  <div class="mb-3">
                    <label for="new_image<?= $img['id'] ?>" class="form-label">Select New Image</label>
                    <input type="file" name="new_image" id="new_image<?= $img['id'] ?>" class="form-control" accept=".jpg,.png,.jpeg" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Update</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
              </form>
            </div>
          </div>
        </div>

      <?php endforeach; ?>
    </div>
  </div>

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
            window.location.href = "slider.php?delete_id=" + id;
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

/* ================= ERROR ALERT ================= */
<?php if (isset($_SESSION['error'])): ?>
Swal.fire({
    icon: 'error',
    title: 'Error!',
    text: '<?= $_SESSION['error']; ?>'
});
<?php unset($_SESSION['error']); endif; ?>
</script>
  <?php include 'footer.php'; ?>
</body>

</html>