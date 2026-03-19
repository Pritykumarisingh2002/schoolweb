<?php
include 'dbconnect.php';
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Result Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- SweetAlert -->
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

            <!-- ================= TOPPER SECTION ================= -->
            <h3 class="mb-4">Topper Management</h3>
            <!-- <div class="card mb-5 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Topper Management</h5>
                </div>

                <div class="card-body"> -->

            <!-- Topper Form -->
            <form id="topperForm" enctype="multipart/form-data" class="row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" name="student_name" class="form-control" placeholder="Student Name" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="class_name" class="form-control" placeholder="Class" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="session" class="form-control" placeholder="Session" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="position" class="form-control" placeholder="Position" required>
                </div>
                <div class="col-md-2">
                    <input type="file" name="image" accept=".jpg, .jpeg, .png" class="form-control" required>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-success w-100">Add</button>
                </div>
            </form>

            <!-- Topper Edit Modal -->

            <!-- Edit Topper Modal -->
            <div class="modal fade" id="editTopperModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title">Edit Topper</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form id="updateTopperForm" enctype="multipart/form-data">
                            <div class="modal-body">

                                <input type="hidden" name="id" id="edit_topper_id">

                                <input type="text" name="student_name" id="edit_student_name"
                                    class="form-control mb-2" required>

                                <input type="text" name="class_name" id="edit_class_name"
                                    class="form-control mb-2" required>

                                <input type="text" name="session" id="edit_session"
                                    class="form-control mb-2" required>

                                <input type="text" name="position" id="edit_position"
                                    class="form-control mb-2" required>

                                <input type="file" name="image" accept=".jpg, .jpeg, .png" class="form-control">

                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-success w-100">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Topper Table -->
            <!-- <div class="table-responsive"> -->
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Session</th>
                        <th>Position</th>
                        <th>Image</th>
                        <th width="100">Action</th>
                    </tr>
                </thead>
                <tbody id="topperTable"></tbody>
            </table>
            <!-- </div>

                </div>
            </div> -->


            <!-- ================= RESULT SECTION ================= -->
            <hr class="my-5">

            <h3 class="mb-4">Class Result Management</h3>
            <!-- <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Class Result Management</h5>
                </div> -->

            <!-- <div class="card-body"> -->

            <!-- Result Form -->
            <form id="resultForm" enctype="multipart/form-data" class="row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" name="class_name" class="form-control" placeholder="Class" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="section" class="form-control" placeholder="Section" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="session" class="form-control" placeholder="Session" required>
                </div>
                <div class="col-md-3">
                    <input type="file" name="pdf" class="form-control" acceept=".pdf" required>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary w-100">Add</button>
                </div>
            </form>

            <!-- Result Edit Modal -->

            <!-- Edit Result Modal -->
            <div class="modal fade" id="editResultModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Result</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form id="updateResultForm" enctype="multipart/form-data">
                            <div class="modal-body">

                                <input type="hidden" name="id" id="edit_result_id">

                                <input type="text" name="class_name" id="edit_result_class"
                                    class="form-control mb-2" required>

                                <input type="text" name="section" id="edit_result_section"
                                    class="form-control mb-2" required>

                                <input type="text" name="session" id="edit_result_session"
                                    class="form-control mb-2" required>

                                <input type="file" name="pdf" accept=".pdf" class="form-control">

                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-success w-100">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Result Table -->
            <!-- <div class="table-responsive"> -->
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Session</th>
                        <th>PDF</th>
                        <th width="100">Action</th>
                    </tr>
                </thead>
                <tbody>
                <tbody id="resultTable"></tbody>
            </table>
            <!-- </div> -->

            <!-- </div> -->
            <!-- </div> -->

        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script>
        $(document).on("click", ".editTopper", function() {

            $("#edit_topper_id").val($(this).data("id"));
            $("#edit_student_name").val($(this).data("name"));
            $("#edit_class_name").val($(this).data("class"));
            $("#edit_session").val($(this).data("session"));
            $("#edit_position").val($(this).data("position"));

            new bootstrap.Modal(document.getElementById('editTopperModal')).show();
        });

        $(document).on("click", ".editResult", function() {

            $("#edit_result_id").val($(this).data("id"));
            $("#edit_result_class").val($(this).data("class"));
            $("#edit_result_section").val($(this).data("section"));
            $("#edit_result_session").val($(this).data("session"));

            new bootstrap.Modal(document.getElementById('editResultModal')).show();
        });

        $(document).ready(function() {

            loadData();

            function loadData() {
                $.post("result_ajax.php", {
                    action: "fetch_data"
                }, function(res) {

                    let topperHTML = "";
                    res.toppers.forEach(function(t) {
                        topperHTML += `
                <tr>
                    <td>${t.student_name}</td>
                    <td>${t.class_name}</td>
                    <td>${t.session}</td>
                    <td>${t.position}</td>
                    <td><img src="uploads/toppers/${t.image_path}" width="60"></td>
                    <td>
    <button class="btn btn-warning btn-sm editTopper"
        data-id="${t.id}"
        data-name="${t.student_name}"
        data-class="${t.class_name}"
        data-session="${t.session}"
        data-position="${t.position}">
        <i class="fa fa-edit"></i>
    </button>

    <button class="btn btn-danger btn-sm deleteTopper" data-id="${t.id}">
        <i class="fa fa-trash"></i>
    </button>
</td>
                </tr>`;
                    });
                    $("#topperTable").html(topperHTML);

                    let resultHTML = "";
                    res.results.forEach(function(r) {
                        resultHTML += `
                <tr>
                    <td>${r.class_name}</td>
                    <td>${r.section}</td>
                    <td>${r.session}</td>
                    <td>
                        <a href="uploads/results/${r.pdf_file}" target="_blank" 
                           class="btn btn-info btn-sm">
                           <i class="fa fa-eye"></i>
                        </a>
                    </td>
                    <td>
    <button class="btn btn-warning btn-sm editResult"
        data-id="${r.id}"
        data-class="${r.class_name}"
        data-section="${r.section}"
        data-session="${r.session}">
        <i class="fa fa-edit"></i>
    </button>

    <button class="btn btn-danger btn-sm deleteResult" data-id="${r.id}">
        <i class="fa fa-trash"></i>
    </button>
</td>
                </tr>`;
                    });
                    $("#resultTable").html(resultHTML);

                }, "json");
            }


            /* ================= ADD TOPPER ================= */
            $("#topperForm").submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                formData.append("action", "add_topper");

                $.ajax({
                    url: "result_ajax.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function(res) {

                        if (res.status === "success") {

                            Swal.fire({
                                icon: "success",
                                title: "Success!",
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            $("#topperForm")[0].reset();
                            loadData();

                        } else {
                            Swal.fire("Error", res.message, "error");
                        }
                    }
                });
            });

            // Update topper form
            $("#updateTopperForm").submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                formData.append("action", "update_topper");

                $.ajax({
                    url: "result_ajax.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function(res) {

                        if (res.status === "success") {

                            Swal.fire("Updated!", res.message, "success");
                            $("#editTopperModal").modal("hide");
                            loadData();

                        } else {
                            Swal.fire("Error", res.message, "error");
                        }
                    }
                });
            });

            /* ================= DELETE TOPPER ================= */
            $(document).on("click", ".deleteTopper", function() {

                let id = $(this).data("id");

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.post("result_ajax.php", {
                            action: "delete_topper",
                            id: id
                        }, function(res) {

                            Swal.fire({
                                icon: "success",
                                title: "Deleted!",
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            loadData();

                        }, "json");
                    }
                });
            });


            /* ================= ADD RESULT ================= */
            $("#resultForm").submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                formData.append("action", "add_result");

                $.ajax({
                    url: "result_ajax.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function(res) {

                        if (res.status === "success") {

                            Swal.fire({
                                icon: "success",
                                title: "Success!",
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            $("#resultForm")[0].reset();
                            loadData();

                        } else {
                            Swal.fire("Error", res.message, "error");
                        }
                    }
                });
            });

            // update result form

            $("#updateResultForm").submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                formData.append("action", "update_result");

                $.ajax({
                    url: "result_ajax.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function(res) {

                        if (res.status === "success") {

                            Swal.fire("Updated!", res.message, "success");
                            $("#editResultModal").modal("hide");
                            loadData();

                        } else {
                            Swal.fire("Error", res.message, "error");
                        }
                    }
                });
            });


            /* ================= DELETE RESULT ================= */
            $(document).on("click", ".deleteResult", function() {

                let id = $(this).data("id");

                Swal.fire({
                    title: "Are you sure?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.post("result_ajax.php", {
                            action: "delete_result",
                            id: id
                        }, function(res) {

                            Swal.fire({
                                icon: "success",
                                title: "Deleted!",
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            loadData();

                        }, "json");
                    }
                });
            });

        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>