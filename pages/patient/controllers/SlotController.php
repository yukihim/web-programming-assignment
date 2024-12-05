<?php
$officeId = $_GET['office_id'];

$host = "localhost";
$username = "root";
$password = "";
$dbname = "medical_appointment";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get the current time
$currentTime = date('Y-m-d H:i:s'); // Current server time in MySQL datetime format

// Modify the query to only select time slots that are after the current time
$slotsQuery = "SELECT * FROM time_slots WHERE doctor_office_id = $officeId AND available_time > '$currentTime' ORDER BY available_time ASC";
$slotsResult = $conn->query($slotsQuery);

$timeSlots = [];
while ($slot = $slotsResult->fetch_assoc()) {
    $timeSlots[] = [
        'id' => $slot['id'],
        'available_time' => $slot['available_time']
    ];
}

header('Content-Type: application/json');
echo json_encode($timeSlots);
?>
