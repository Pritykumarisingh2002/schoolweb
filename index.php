<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jamshedpur Public School</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>

    <!-- Navbar -->
    <?php include('navbar.php'); ?>

    <?php
    include 'adminpanel/dbconnect.php';
    /* FETCH SLIDER IMAGES */
    $slider_images = $pdo->query("SELECT image_data FROM slider_images ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

    /* FETCH LATEST MODAL */
    $stmt = $pdo->prepare("SELECT * FROM add_modal order by id");
    $stmt->execute();
    $modals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>


    <!-- ================= NOTIFICATION MODAL ================= -->

    <?php foreach ($modals as $index => $banner): ?>
        <div class="modal fade notification-modal" id="bannerModal<?php echo $index; ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-top modal-md">
                <div class="modal-content text-center" style="background-image:url('adminpanel/uploads/modal/<?php echo $banner['banner_image']; ?>');
                    background-size:cover;
                    background-position:center;
                    height:250px;
                    color:white;">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-bell"></i> Important Notification
                        </h5>
                        <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body d-flex flex-column justify-content-center" style="background:rgba(0,0,0,0.5); height:100%;">
                        <p style="font-size:16px; line-height:1.6; color:white; font-weight:bold">
                            <?php echo htmlspecialchars($banner['description']); ?>
                        </p>

                        <?php if (!empty($banner['link'])): ?>
                            <a href="<?= htmlspecialchars($banner['link']) ?>"
                                target="_blank"
                                class="btn btn-primary mt-2">
                                Open Link
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Crousel -->
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000" data-bs-pause="false">
        <div class="carousel-inner">
            <?php foreach ($slider_images as $index => $slide): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="data:image/jpeg;base64,<?= base64_encode($slide['image_data']) ?>" class="d-block w-100" alt="Slide <?= $index + 1 ?>">
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <!-- </section> -->

    <!-- About Section -->
    <section id="about-section">

        <div class="about-container">

            <!-- LEFT SIDE : ABOUT -->
            <div class="about-box">
                <h2>About Us</h2>
                <p class="mission-list">
                    Jamshedpur Public School was founded in March 1988, under the aegis of
                    A.I.W.C (All India Women's Conference), a Non-Profit organization.
                    The school is a co-educational institution affiliated to CBSE.
                    The motto of the school is <b>SHEELAM PARAM BHUSHANAM</b>.
                    The school strives to impart quality education that encourages thinking and not just learning.
                    The school operates from classes Nursery to XII (with 2317 students).

                    <span id="dots">...</span>

                    <span id="more" style="display: none;">
                        The school is proud of its dedicated and competent teaching faculty,
                        well qualified and experienced to guide the students and develop their innate talents and potential.
                        JPS is located in Jamshedpur and has one of the lowest fee structures among comparable schools in town.
                        The curriculum of the school is directed towards the all-round development of young boys and girls
                        so as to produce intellectually well-informed, socially concerned, morally upright,
                        confident, self-reliant and responsible citizens of the country.
                        The school believes in an educational system that blends modern methodology & technology
                        with moral values to prepare students to be global citizens of tomorrow.
                        The school promotes cultural programmes conducted by SPIC MACAY,
                        and conducts programmes such as Annual Prize Night, Sports Day,
                        Science Exhibition, Commerce Fest, Quiz, debates etc.
                    </span>
                </p>

                <button onclick="myFunction()" id="myBtn">Read more</button>
            </div>

            <!-- RIGHT SIDE : NEWS / EVENTS / NOTICE -->
            <div class="updates-box">

                <div class="tab-buttons">
                    <button class="tab-btn active" onclick="openTab('notice')">Valedictorian</button>
                    <button class="tab-btn" onclick="openTab('holiday')">Holiday</button>
                    <button class="tab-btn" onclick="openTab('events')">Events</button>
                    <!-- <button class="tab-btn" onclick="openTab('news')">News</button> -->
                </div>

                <div class="tab-content">

                    <div id="notice" class="tab-panel active">
                        <?php include 'notification.php'; ?>
                    </div>

                    <div id="holiday" class="tab-panel">
                        <?php include 'holiday-list.php'; ?>
                    </div>

                    <div id="events" class="tab-panel">
                        <?php include 'forthcoming_events.php'; ?>
                    </div>

                    <div id="news" class="tab-panel">
                        <ul class="uniform-list">
                            <li>School Ranked No.1 in District</li>
                            <li>Students Won National Olympiad</li>
                            <li>New Computer Lab Inaugurated</li>
                        </ul>
                    </div>

                </div>

            </div>

        </div>

    </section>


    <section id="about-section">
        <h2>Mission, Vision & Values</h2>

        <div class="about-container">

            <!-- LEFT SIDE : ABOUT -->
            <div class="about-box">
                <h2>Mission</h2>
                <p class="mission-list">
                    Human Excellence Through Education
                    To produce life long learners
                    who are provided equal opprtunities
                    to begin their own vision
                    in a supportive, innovative
                    learning environment in
                    a learning organization,
                    which is like no other place
                    in the world.
                </p>
            </div>

            <!-- RIGHT SIDE : NEWS / EVENTS / NOTICE -->
            <div class="about-box">
                <div class="tab-content">
                    <h2>Vision</h2>
                    <ul class="uniform-list">
                        <li>Excellence in academics,sports,co-curricular activitiesand life skills,in an environment that promets safety</li>
                        <li>(Our endeavour is to bring up our students and work force to achive the three ingredients of a good life:) Learning,Earning & Yearning for the good, and thereby living upto the motto of their ALMA MATER</li>
                        <li>"SHEELAM PARAM BHUSHANAM"(character is the best attire one can adorn) </li>
                    </ul>
                </div>
            </div>

            <div class="about-box">
                <div class="tab-content">
                    <h2>Values of the School</h2>
                    <p class="mission-list">Our students are customers and the product we deliver is to allow them exhibit their innate talent and to achieve to their highest ability. </p>
                    <ul class="uniform-list">
                        <li>Focus on children.</li>
                        <li>Emphasis on learning rather than knowledge gathering.</li>
                        <li>Learning is an active process where a student discovers & creates knowledge.</li>
                    </ul>
                </div>
            </div>

    </section>

    <?php
    include __DIR__ . '/adminpanel/dbconnect.php';

    $toppers = $pdo->query("SELECT * FROM toppers ORDER BY created_at DESC")->fetchAll();
    ?>

    <section class="principal-message">

        <h2>Our Toppers</h2>
        <div class="container">

            <div class="topper-grid">
                <?php foreach ($toppers as $topper): ?>
                    <div class="topper-card">
                        <img src="adminpanel/uploads/toppers/<?php echo $topper['image_path']; ?>"
                            alt="Topper Image">

                        <h3><?php echo htmlspecialchars($topper['student_name']); ?></h3>

                        <p>Class: <?php echo htmlspecialchars($topper['class_name']); ?></p>

                        <span class="position">
                            <?php echo htmlspecialchars($topper['position']); ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>

    </section>

    <section class="principal-message">

        <h2>Message from the Principal</h2>

        <div class="message-container">

            <!-- Principal Image -->
            <div class="principal-image">
                <img src="images/principal.jpg" alt="Principal Photo">
            </div>

            <!-- Message Content -->
            <div class="message-content">
                <p>We would like to see our students grow up to be responsible citizens who will always remember the core values i.e; Honesty Sincerity and Integrity.</p>

                <!-- <p>
                Welcome to JPS Public School. Our institution is committed to 
                nurturing young minds with knowledge, discipline, and moral values. 
                We believe in holistic education that shapes students into 
                responsible citizens and future leaders.
            </p> -->

                <p>let us build a strong foundation for lifelong learning
                    and excellence.
                </p>

                <div class="principal-info">
                    <h3>Mrs.Namita Agarwal</h3>
                    <span>Principal(2010-till date)</span><br>
                    <span>Jamshedpur Public School</span>
                </div>
            </div>

        </div>

    </section>


    <!-- Contact Info -->
    <section id="contact">
        <h2>Contact Us</h2>

        <div class="contact-container">

            <div class="contact-box">
                <h3>Address</h3>
                <p>
                    Jamshedpur Public School, Panchvati Road, New Baridih, Jamshedpur<br>
                    <b>Email:</b> jpsaiwc1988@gmail.com
                </p>
            </div>

            <div class="contact-box">
                <h3>Office Contact</h3>
                <p>
                    Jamshedpur Public School Office, J.P.S<br>
                    <b>Phone:</b> 0657-2344050 / 9430371428 (Only for Online fee 8 a.m to 1 p.m)<br>
                    <b>Office timing:</b> 7:00am to 1:00pm (Monday to Friday)<br>
                    <b>Office timing:</b> 7:00am to 11:00am (Saturday)<br>
                    <b>Email:</b> jpsaiwc1988@gmail.com
                </p>
            </div>

            <div class="contact-box">
                <h3>Principal</h3>
                <p>
                    Mrs. Namita Agarwal<br>
                    Jamshedpur Public School<br>
                    Panchvati Road, New Baridih, Jamshedpur<br>
                    <b>Phone:</b> 0657-2344050<br>
                    <b>Email:</b> principal@jamshedpurpublicschool.in
                </p>
            </div>

        </div>
    </section>

    <?php include('footer.php'); ?>

    <script>
        function openTab(tabName) {
            // Hide all panels
            var panels = document.querySelectorAll(".tab-panel");
            panels.forEach(panel => panel.classList.remove("active"));

            // Remove active from buttons
            var buttons = document.querySelectorAll(".tab-btn");
            buttons.forEach(btn => btn.classList.remove("active"));

            // Show selected tab
            document.getElementById(tabName).classList.add("active");

            // Highlight active button
            event.target.classList.add("active");
        }

        // Read more script
        function myFunction() {
            var dots = document.getElementById("dots");
            var moreText = document.getElementById("more");
            var btnText = document.getElementById("myBtn");

            if (dots.style.display === "none") {
                dots.style.display = "inline";
                btnText.innerHTML = "Read more";
                moreText.style.display = "none";
            } else {
                dots.style.display = "none";
                btnText.innerHTML = "Read less";
                moreText.style.display = "inline";
            }
        }

        /* SHOW MODAL */

        document.addEventListener("DOMContentLoaded", function() {

            let modals = document.querySelectorAll('.notification-modal');
            let index = 0;

            function showNextModal() {
                if (index < modals.length) {
                    let modal = new bootstrap.Modal(modals[index]);
                    modal.show();

                    modals[index].addEventListener('hidden.bs.modal', function() {
                        index++;
                        showNextModal();
                    }, {
                        once: true
                    });
                }
            }

            showNextModal();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>