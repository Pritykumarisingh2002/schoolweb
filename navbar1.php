<div class="notification-bar">
    <div class="notification-label">Latest Notification:</div>
    <marquee behavior="scroll" direction="left">
        Admission Open for 2025-26 | Annual Sports Day on 25th March | Board Results Published | Parent-Teacher Meeting on 15th Feb
    </marquee>
</div>
<header class="top-header">
    <div class="header-container">
        <div class="school-logo">
            <img src="images/jps_logo.jpeg" alt="School Logo">
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
        <!-- <img class="logo-left" src="images/jps_logo.jpeg" alt="JPS_Logo" width="70"> -->
        <ul class="menu">
            <li><a href="index.php">Home</a></li>
            <!-- SCHOOL -->
            <li class="dropdown">
                <a href="#" class="dropdown-toggle">School ▾</a>
                <ul class="submenu">
                    <li><a href="about.php">About School</a></li>
                    <li><a href="rules.php">Rules & Regulations</a></li>
                    <li><a href="doc/CalenderAcademicyear2023_24.pdf">Academic Calendar</a></li>
                    <li><a href="co-curricular.php">Co-Curricular Activities</a></li>
                    <li><a href="uniform.php">School Uniform</a></li>
                    <li><a href="book_list.php">Book List</a></li>
                    <li><a href="results.php">Board Results</a></li>
                    <li><a href="infra.php">Infrastructure</a></li>
                    <li><a href="innovation.php">Innovation in Technology</a></li>
                </ul>
            </li>
            <li><a href="co-curricular.php">Activities</a></li>
            <!-- <li><a href="doc/CalenderAcademicyear2023_24.pdf">Academic</a></li> -->
            <li class="dropdown">
                <a href="#" class="dropdown-toggle">Academic ▾</a>
                <ul class="submenu">
                    <li><a href="results.php">Results</a></li>
                    <li><a href="exam.php">Exam</a></li>
                </ul>
            </li>
            <li><a href="gallery.php">Gallery & Events</a></li>
            <li>
                <a href="https://jps.symphonytek.com/fee" target="_blank">
                    School Fee
                </a>
            </li>
            <!-- <li><a href="donation.php">Donation</a></li> -->
            <li><a href="contact.php">Contact</a></li>
            <!-- MANDATE DISC -->
            <li class="dropdown">
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
            </li>
            <!-- PARENT CORNER -->
            <li class="dropdown">
                <a href="#" class="dropdown-toggle">Parent Corner ▾</a>
                <ul class="submenu">
                    <li><a href="suggestion.php">Suggestion</a></li>
                    <li><a href="feedback.php">Feedback About Teacher</a></li>
                </ul>
            </li>

        </ul>
    </div>
</nav>
<script>
    document.addEventListener("DOMContentLoaded", function() {

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

        document.addEventListener("click", function(e) {
            if (!e.target.closest(".dropdown")) {
                document.querySelectorAll(".submenu").forEach(function(menu) {
                    menu.classList.remove("active");
                });
            }
        });

    });
</script>