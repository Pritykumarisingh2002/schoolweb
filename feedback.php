<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jamshedpur Public School</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- <style>
        .btn-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-top: 20px;
        }
    </style> -->

</head>

<body>

    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <div class="contact-form">
            <h2>Feedback Form</h2>

            <form action="send_feedback.php" method="post">
                <input type="text" class="form-control" name="name" placeholder="Your Name" required>
                <input type="email" class="form-control" name="email" placeholder="Your Email" required>
                <input type="text" class="form-control" name="subject" placeholder="Subject" required>
                <textarea name="feedback" class="form-control" rows="5" placeholder="Your Feedback" required></textarea>
                <!-- <button type="submit" class="btn-submit">Send Feedback</button> -->
                <div class="btn-row">
                    <button type="reset" class="btn-submit">Reset</button>
                    <button type="submit" class="btn-submit">Send Feedback</button>
                </div>
            </form>
        </div>
    </div>

    <?php include('footer.php'); ?>

    <script>
        const params = new URLSearchParams(window.location.search);

        if (params.get("status") === "success") {
            Swal.fire({
                icon: "success",
                title: "Thank You!",
                text: "Your feedback has been sent successfully!",
                confirmButtonColor: "#8b0000"
            }).then(() => {
                window.history.replaceState({}, document.title, "feedback.php");
            });
        }

        if (params.get("status") === "error") {

            let message = "Something went wrong.";

            if (params.get("msg") === "empty") {
                message = "All fields are required.";
            }

            if (params.get("msg") === "invalid") {
                message = "Invalid email format.";
            }

            if (params.get("msg") === "server") {
                message = "Server error. Please try again.";
            }

            Swal.fire({
                icon: "error",
                title: "Error",
                text: message,
                confirmButtonColor: "#8b0000"
            }).then(() => {
                window.history.replaceState({}, document.title, "feedback.php");
            });
        }
    </script>

</body>

</html>