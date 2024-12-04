<?php
require_once 'core/Database.php';
require_once 'models/TimeSlot.php';

if (!isset($_SESSION)) {
    session_start();
}

// session_start();
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('You must log in as a guest to access this page.'); window.location.href = 'index.php?page=signin';</script>";
    exit();
}

$timeSlotModel = new TimeSlot();
$officesQuery = "SELECT * FROM doctor_offices";
$conn = Database::getInstance()->getConnection();
$officesResult = $conn->query($officesQuery);

include 'views/appointment_form.php';
?>
