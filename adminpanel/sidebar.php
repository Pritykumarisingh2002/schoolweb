<?php
$current_page = basename($_SERVER['PHP_SELF'], ".php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jamshedpur Public School</title>
    <link rel="icon" type="image/x-icon" href="../images/jps_logo.jpeg">

    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <div class="sidebar" id="sidebar">

        <div class="sidebar-title">Admin Panel</div>

        <ul class="sidebar-menu">

            <li>
                <a href="index.php" class="<?= ($current_page == 'index') ? 'active' : '' ?>">
                    <i class="fas fa-image"></i>
                    <span>Photo Gallery</span>
                </a>
            </li>

            <li>
                <a href="slider.php" class="<?= ($current_page == 'slider') ? 'active' : '' ?>">
                    <i class="fas fa-sliders-h"></i>
                    <span>Sliders</span>
                </a>
            </li>

            <li>
                <a href="modal.php" class="<?= ($current_page == 'modal') ? 'active' : '' ?>">
                    <i class="fas fa-bell"></i>
                    <span>Add Notification</span>
                </a>
            </li>

            <li>
                <a href="valedictorian.php" class="<?= ($current_page == 'valedictorian') ? 'active' : '' ?>">
                    <i class="fas fa-plus-circle"></i>
                    <span>Valedictorian</span>
                </a>
            </li>

            <li>
                <a href="holiday.php" class="<?= ($current_page == 'holiday') ? 'active' : '' ?>">
                    <i class="fa-solid fa-champagne-glasses"></i>
                    <span>Holidays</span>
                </a>
            </li>

            <li>
                <a href="exam_routine.php" class="<?= ($current_page == 'exam_routine') ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt"></i> <span>Exam Routine</span>
                </a>
            </li>

            <li>
                <a href="view_message.php" class="<?= ($current_page == 'view_message') ? 'active' : '' ?>">
                    <i class="fa-solid fa-message"></i> <span>View Message</span>
                </a>
            </li>

            <li>
                <a href="view_feedback.php" class="<?= ($current_page == 'view_feedback') ? 'active' : '' ?>">
                    <i class="fa-solid fa-comment"></i> <span>View Feedback</span>
                </a>
            </li>

            <li>
                <a href="view_suggestion.php" class="<?= ($current_page == 'view_suggestion') ? 'active' : '' ?>">
                    <i class="fa-solid fa-comment-dots"></i> <span>View Suggestion</span>
                </a>
            </li>

            <li>
                <a href="document.php" class="<?= ($current_page == 'document') ? 'active' : '' ?>">
                    <i class="fa-solid fa-upload"></i> <span>Upload Documents</span>
                </a>
            </li>

            <li>
                <a href="mandate_disc.php" class="<?= ($current_page == 'mandate_disc') ? 'active' : '' ?>">
                    <i class="fa-solid fa-upload"></i> <span>Mandate Disc Documents</span>
                </a>
            </li>

            <li>
                <a href="event.php" class="<?= ($current_page == 'event') ? 'active' : '' ?>">
                    <i class="fas fa-calendar"></i> <span>Forthcoming Events</span>
                </a>
            </li>

            <li>
                <a href="result.php" class="<?= ($current_page == 'result') ? 'active' : '' ?>">
                    <i class="fas fa-newspaper"></i> <span>Results</span>
                </a>
            </li>

            <li>
                <a href="http://jpsweb.symphonytek.com" target="_blank">
                    <i class="fas fa-globe"></i> <span>Visit School Website</span>
                </a>
            </li>

        </ul>

    </div>