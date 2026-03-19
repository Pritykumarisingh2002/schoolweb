<?php
include "adminpanel/dbconnect.php";

/* FETCH MANDATORY DOCUMENTS */

$stmt1 = $pdo->prepare("SELECT category, file_path FROM mandate_doc");
$stmt1->execute();

$documents = [];

while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
    $documents[$row['category']] = $row['file_path'];
}


/* FETCH Academic Calender */

$stmt2 = $pdo->prepare("SELECT class_name, file_path FROM documents WHERE category='academic_calendar' ORDER BY class_name ASC");
$stmt2->execute();

$academic_calendar = $stmt2->fetch(PDO::FETCH_ASSOC);

// Notification for marquee

$stmtNotice = $pdo->prepare("SELECT description,link FROM add_modal");
$stmtNotice->execute();

$notices = $stmtNotice->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jamshedpur Public School</title>
    <link rel="icon" type="image/x-icon" href="images/jps_logo.jpeg">
    <!-- <link rel="stylesheet" href="styles.css"> -->
    <style>
        /* RESET */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* logo spin */
        /* @keyframes spinHorizontal {
            0% {
                transform: rotateY(0deg);
            }

            100% {
                transform: rotateY(360deg);
            }
        }

        .logo-spin {
            width: 100px;
            height: 100px;

            animation: spinHorizontal 4s linear infinite;
        } */


        /* NOTIFICATION BAR */
        .notification-bar {
            background: #8b0000;
            padding: 8px 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            overflow: scroll;
        }

        .notification-label {
            font-weight: bold;
        }

        .notification-text {
            white-space: nowrap;
            animation: scroll 18s linear infinite;
        }

        @keyframes scroll {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        /* HEADER */
        .top-header {
            background: #f5f5f5;
            padding: 15px 0;
        }

        .header-container {
            max-width: 1200px;
            margin: auto;
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 0 15px;
        }

        .school-logo img {
            width: 100px;
            height: auto;
        }

        .school-name h1 {
            font-size: 26px;
            color: #8b0000;
        }

        .school-name p {
            font-size: 14px;
            color: #333;
        }

        /* NAVBAR */
        nav {
            background: #8b0000;
            width: 100%;
        }

        .nav-wrapper {
            max-width: 1200px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            position: relative;
        }

        .menu {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .menu li {
            position: relative;
        }

        .menu li a {
            display: block;
            padding: 14px 12px;
            color: white;
            text-decoration: none;
            font-size: 15px;
            transition: 0.3s;
        }

        .menu li a:hover {
            background: #a00000;
            border-radius: 4px;
        }

        .submenu {
            display: none;
            position: absolute;
            background: #a00000;
            list-style: none;
            min-width: 230px;
            top: 100%;
            left: 0;
            border-radius: 4px;
            /* overflow: hidden; */
            overflow: scroll;
        }

        .submenu li a {
            padding: 10px 15px;
            display: block;
        }

        .submenu li a:hover {
            background: white;
        }

        .submenu li a.active {
            background: white;
            color: #8b0000 !important;
            font-weight: bold;
            border-radius: 4px;
        }

        .submenu.active {
            display: block;
        }

        /* HAMBURGER */
        .menu-toggle {
            display: none;
            font-size: 26px;
            /* color: black; */
            color: white;
            cursor: pointer;
            position: absolute;
            right: 20px;
            top: 12px;
        }

        /* MOBILE RESPONSIVE */
        @media (max-width: 1100px) {

            .menu-toggle {
                display: block;
            }

            .nav-wrapper {
                flex-direction: column;
                align-items: flex-start;
            }

            .menu {
                flex-direction: column;
                width: 100%;
                display: none;
                background: #8b0000;
            }

            .menu.active {
                display: flex;
            }

            .menu li {
                width: 100%;
            }

            .menu li a {
                padding: 12px 20px;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
            }

            .submenu {
                position: static;
                width: 100%;
            }

            .submenu li a {
                padding-left: 40px;
            }
        }

        /* .header-container {
            flex-direction: column;
            text-align: center;
        } */

        .header-container {
            max-width: 1200px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            padding: 10px 15px;
        }

        .school-logo img {
            width: 100px;
            height: auto;
        }

        .school-name h1 {
            font-size: 20px;
        }
    </style>
</head>

<body>

    <div class="notification-bar">
        <div class="notification-label">Latest Notification:</div>
        <marquee behavior="scroll" direction="left"
            onmouseover="this.stop();"
            onmouseout="this.start();">
            <?php if (!empty($notices)) { ?>
                <?php foreach ($notices as $notice) { ?>
                    <a href="<?php echo htmlspecialchars($notice['link']); ?>"
                        style="color:white; text-decoration:none; font-weight:bold;"
                        target="_blank">
                        <?php echo htmlspecialchars($notice['description']); ?>
                    </a>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                <?php } ?>
            <?php } else { ?>
                No latest notifications available
            <?php } ?>
        </marquee>
    </div>

    <header class="top-header">
        <div class="header-container">
            <div class="school-logo">
                <img class="logo-spin" src="images/jps_logo.jpeg" alt="School Logo">
            </div>
            <div class="school-name">
                <h1>Jamshedpur Public School</h1>
                <p>Panchvati Road, New Baridih, Jamshedpur <br>
                </p>
            </div>
        </div>
    </header>

    <nav>
        <div class="nav-wrapper">
            <div class="menu-toggle">☰</div>
            <!-- <img class="logo-left" src="images/jps_logo.jpeg" alt="JPS_Logo" width="70"> -->
            <ul class="menu">
                <li><a href="index">Home</a></li>
                <!-- SCHOOL -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">School ▾</a>
                    <ul class="submenu">
                        <li><a href="about">About School</a></li>
                        <li><a href="rules">Rules & Regulations</a></li>
                        <li>
                            <a href="adminpanel/uploads/<?php echo htmlspecialchars($academic_calendar['file_path']); ?>" target="_blank">
                                Academic Calendar
                            </a>
                        </li>
                        <li><a href="co-curricular">Co-Curricular Activities</a></li>
                        <li><a href="uniform">School Uniform</a></li>
                        <li><a href="book_list">Book List</a></li>
                        <li><a href="results">Board Results</a></li>
                        <li><a href="infra">Infrastructure</a></li>
                        <li><a href="innovation">Innovation in Technology</a></li>
                    </ul>
                </li>
                <li><a href="co-curricular.php">Activities</a></li>
                <!-- <li><a href="doc/CalenderAcademicyear2023_24.pdf">Academic</a></li> -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Academic ▾</a>
                    <ul class="submenu">
                        <li><a href="results">Results</a></li>
                        <li><a href="exam">Exam</a></li>
                    </ul>
                </li>
                <li><a href="gallery.php">Gallery & Events</a></li>
                <li>
                    <a href="https://jps.symphonytek.com/fee" target="_blank">
                        School Fee
                    </a>
                </li>
                <!-- <li><a href="donation.php">Donation</a></li> -->
                <li><a href="contact">Contact</a></li>
                <!-- MANDATE DISC -->
                <!-- <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Mandate Disc ▾</a>
                    <ul class="submenu">
                        <li><a href="doc/ManagingCommittee_23_24.pdf">Managing Committee</a></li>
                        <li><a href="doc/StaffandFacultiesDetails.pdf">Staff & Faculty Details</a></li>
                        <li><a href="doc/Parents_Teacher_AssociateMember.pdf">Parents Teacher Associate Members</a></li>
                        <li><a href="doc/AffiliationCertificate.pdf">Affliation Certificate</a></li>
                        <li><a href="doc/SocietyRegistration.pdf">Society Registration</a></li>
                        <li><a href="doc/NOC.pdf">NOC</a></li>
                        <li><a href="doc/Application_for_RTE.pdf">RTE Affliation Application</a></li>
                        <li><a href="doc/LandAgreement.pdf">Land Agreement</a></li>
                        <li><a href="doc/BuildingSafetyCertificate.pdf">Building Safety Certificate</a></li>
                        <li><a href="doc/FireCertificate.pdf">Fire Safety Certificate</a></li>
                        <li><a href="doc/SafeWater_Sanitation.pdf">Water, Health & Sanitation Certficates</a></li>
                        <li><a href="doc/SelfCertification.pdf">Self Certification</a></li>
                        <li><a href="doc/MandatoryDisclosureLink1.pdf">Mandatory Dislosure Link</a></li>
                    </ul>
                </li> -->

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Mandate Disc ▾</a>

                    <ul class="submenu">

                        <li>
                            <a href="<?php echo isset($documents['managing_comm']) ? 'adminpanel/uploads/mandate_doc/' . $documents['managing_comm'] : 'javascript:void(0)'; ?>"
                                <?php if (!isset($documents['managing_comm'])) { ?>
                                onclick="alert('No file found'); return false;"
                                <?php } ?>>
                                Managing Committee
                            </a>
                        </li>
                        <!-- s&f_details -->
                        <li>
                            <a href="<?php echo isset($documents['s&f_details']) ? 'adminpanel/uploads/mandate_doc/' . $documents['s&f_details'] : 'javascript:void(0)'; ?>"
                                <?php if (!isset($documents['s&f_details'])) { ?>
                                onclick="alert('No file found'); return false;"
                                <?php } ?>>
                                Staff & Faculty details
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo isset($documents['pta_member']) ? 'adminpanel/uploads/mandate_doc/' . $documents['pta_member'] : 'javascript:void(0)'; ?>"
                                <?php if (!isset($documents['pta_member'])) { ?>
                                onclick="alert('No file found'); return false;"
                                <?php } ?>>
                                Parent, Teacher Assoicate Members
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo isset($documents['aff_cer']) ? 'adminpanel/uploads/mandate_doc/' . $documents['aff_cer'] : 'javascript:void(0)'; ?>"
                                <?php if (!isset($documents['aff_cer'])) { ?>
                                onclick="alert('No file found'); return false;"
                                <?php } ?>>
                                Parent, Teacher Assoicate Members
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo isset($documents['society_reg']) ? 'adminpanel/uploads/mandate_doc/' . $documents['society_reg'] : 'javascript:void(0)'; ?>"
                                <?php if (!isset($documents['society_reg'])) { ?>
                                onclick="alert('No file found'); return false;"
                                <?php } ?>
                                target="_blank">
                                Society Registration
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo isset($documents['NOC']) ? 'adminpanel/uploads/mandate_doc/' . $documents['NOC'] : 'javascript:void(0)'; ?>"
                                <?php if (!isset($documents['NOC'])) { ?>
                                onclick="alert('No file found'); return false;"
                                <?php } ?>
                                target="_blank">
                                NOC
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo isset($documents['RTE']) ? 'adminpanel/uploads/mandate_doc/' . $documents['RTE'] : 'javascript:void(0)'; ?>"
                                <?php if (!isset($documents['RTE'])) { ?>
                                onclick="alert('No file found'); return false;"
                                <?php } ?>
                                target="_blank">
                                RTE Affiliation Application
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo isset($documents['land_agg']) ? 'adminpanel/uploads/mandate_doc/' . $documents['land_agg'] : 'javascripr:void(0)'; ?>"
                                <?php if (!isset($documents['land_agg'])) { ?>
                                onclick="alert('No file found'); return false;"
                                <?php } ?>
                                target="_blank">
                                Land Agreement
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo isset($documents['b&s_cer']) ? 'adminpanel/uploads/mandate_doc/' . $documents['b&s_cer'] : 'javascript:void(0)'; ?>"
                                <?php if (!isset($documents['b&s_cer'])) { ?>
                                onclick="alert('No file found'); return false;"
                                <?php } ?>
                                target="_blank">
                                Building Safety Certificate
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo isset($documents['Fire_safety_cer']) ? 'adminpanel/uploads/mandate_doc/' . $documents['Fire_safety_cer'] : 'javascript:void(0)'; ?>"
                                <?php if (!isset($documents['Fire_safety-cer'])) { ?>
                                onclick="alert('No file found'); return false;"
                                <?php } ?>
                                target="_blank">
                                Fire Safety Certificate
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo isset($documents['wh&s_certificate']) ? 'adminpanel/uploads/mandate_doc/' . $documents['wh&s_certificate'] : 'javascript:void(0)'; ?>"
                                <?php if (!isset($documents['w&s_certificate'])) { ?>
                                onclick="alert('No file found'); return false;"
                                <?php } ?>
                                target="_blank">
                                Water, Health & Sanitation Certificates
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo isset($documents['self_certification']) ? 'adminpanel/uploads/mandate_doc/' . $documents['self_certification'] : 'javascript:void(0)'; ?>"
                                <?php if (!isset($documents['self_certification'])) { ?>
                                onclick="alert('No file found'); return false;"
                                <?php } ?>
                                target="_blank">
                                Self Certification
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo isset($documents['mandate_disclosure']) ? 'adminpanel/uploads/mandate_doc/' . $documents['mandate_disclosure'] : 'javascript:void(0)'; ?>"
                                <?php if (!isset($documents['mandate_disclosure'])) { ?>
                                onclick="alert('No file found'); return false;"
                                <?php } ?>
                                target="_blank">
                                Mandatory Disclosure Link
                            </a>
                        </li>

                    </ul>
                </li>
                <!-- PARENT CORNER -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Parent Corner ▾</a>
                    <ul class="submenu">
                        <li><a href="suggestion">Suggestion</a></li>
                        <li><a href="feedback">Feedback About Teacher</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const menuToggle = document.querySelector(".menu-toggle");
            const menu = document.querySelector(".menu");

            menuToggle.addEventListener("click", function() {
                menu.classList.toggle("active");
            });

            const toggles = document.querySelectorAll(".dropdown-toggle");

            toggles.forEach(function(toggle) {
                toggle.addEventListener("click", function(e) {
                    e.preventDefault();

                    const parent = this.parentElement;
                    const submenu = parent.querySelector(".submenu");

                    document.querySelectorAll(".submenu").forEach(function(menu) {
                        if (menu !== submenu) {
                            menu.classList.remove("active");
                        }
                    });

                    submenu.classList.toggle("active");
                });
            });

            /* Submenu Active Color Change */
            const submenuLinks = document.querySelectorAll(".submenu li a");

            submenuLinks.forEach(function(link) {
                link.addEventListener("click", function() {

                    submenuLinks.forEach(function(l) {
                        l.classList.remove("active");
                    });

                    this.classList.add("active");
                });
            });

            document.addEventListener("click", function(e) {
                if (!e.target.closest("nav")) {
                    document.querySelectorAll(".submenu").forEach(function(menu) {
                        menu.classList.remove("active");
                    });
                    menu.classList.remove("active");
                }
            });

        });
    </script>