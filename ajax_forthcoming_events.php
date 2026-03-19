<?php
include 'adminpanel/dbconnect.php';

$upcoming_events = $pdo->query("SELECT event_name, event_date FROM forthcoming_events ORDER BY event_date ASC")->fetchAll(PDO::FETCH_ASSOC);

if (!empty($upcoming_events)) {
    foreach ($upcoming_events as $event) {
        $timestamp = strtotime($event['event_date']);
        $day = date("d", $timestamp);
        $month = date("M", $timestamp);
        $year = date("Y", $timestamp);

        echo '<li>
                <div class="date-box">
                    <div class="day">' . $day . '</div>
                    <div class="month">' . $month . '</div>
                    <div class="year">' . $year . '</div>
                </div>
                <p class="event-title">' . htmlspecialchars($event['event_name']) . '</p>
            </li>';
    }
} else {
    echo '<li><p class="event-title">No upcoming events found.</p></li>';
}
?>
