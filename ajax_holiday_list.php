<?php
include __DIR__ . '/adminpanel/dbconnect.php';

$holidays = $pdo->query("SELECT name AS title, date FROM holidays ORDER BY date ASC")->fetchAll(PDO::FETCH_ASSOC);

if (!empty($holidays)) {
  foreach ($holidays as $holiday) {
    $timestamp = strtotime($holiday['date']);
    $day = date("d", $timestamp);
    $month = date("M", $timestamp);
    $year = date("Y", $timestamp);

    echo '<li>
      <div class="holiday-date-box">
        <div class="day">' . $day . '</div>
        <div class="month">' . $month . '</div>
        <div class="year">' . $year . '</div>
      </div>
      <p class="holiday-name">' . htmlspecialchars($holiday['title']) . '</p>
    </li>';
  }
} else {
  echo '<li><p class="holiday-name">No holidays found.</p></li>';
}
