<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Jamshedpur Public School</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php include('navbar.php'); ?>

<section class="contact-page">

    <h1 class="page-title">Contact Us</h1>

    <!-- Contact Info Boxes -->
    <div class="contact-container">

        <div class="contact-box">
            <h3>Address</h3>
            <p>
                Jamshedpur Public School,<br>
                Panchvati Road, New Baridih,<br>
                Jamshedpur<br>
                <b>Email:</b> jpsaiwc1988@gmail.com
            </p>
        </div>

        <div class="contact-box">
            <h3>Office Contact</h3>
            <p>
                <b>Phone:</b> 0657-2344050 / 9430371428<br>
                (Only for Online fee 8 a.m to 1 p.m)<br><br>

                <b>Office Timing:</b><br>
                Monday–Friday: 7:00am – 1:00pm<br>
                Saturday: 7:00am – 11:00am<br>

                <b>Email:</b> jpsaiwc1988@gmail.com
            </p>
        </div>

        <div class="contact-box">
            <h3>Principal</h3>
            <p>
                Mrs. Namita Agarwal<br>
                <b>Phone:</b> 0657-2344050<br>
                <b>Email:</b> principal@jamshedpurpublicschool.in
            </p>
        </div>

    </div>


    <!-- Contact Form + Map -->
    <div class="contact-bottom">

        <!-- Contact Form -->
        <div class="contact-form">
            <h2>Send Us a Message</h2>

            <form action="send_message.php" method="post">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <input type="text" name="subject" placeholder="Subject" required>
                <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
                <button type="submit" class="btn-submit">Send Message</button>
            </form>
        </div>

        <!-- Google Map -->
        <div class="contact-map">
            <iframe 
                src="https://www.google.com/maps?q=Jamshedpur+Public+School,+New+Baridih,+Jamshedpur&output=embed"
                width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>

    </div>

</section>

<?php include('footer.php'); ?>

<script>
        const params = new URLSearchParams(window.location.search);

        if (params.get("status") === "success") {
            Swal.fire({
                icon: "success",
                title: "Thank You!",
                text: "Your Message has been sent successfully!",
                confirmButtonColor: "#8b0000"
            }).then(() => {
                window.history.replaceState({}, document.title, "contact.php");
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
                window.history.replaceState({}, document.title, "contact.php");
            });
        }
    </script>


</body>
</html>
