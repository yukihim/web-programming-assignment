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

// Modify the query to select only time slots with available capacity
$slotsQuery = "
    SELECT ts.id, ts.available_time, ts.max_slots, 
           (ts.max_slots - COALESCE(SUM(CASE WHEN a.status = 'confirmed' THEN 1 ELSE 0 END), 0)) AS available_slots
    FROM time_slots ts
    LEFT JOIN appointments a ON ts.id = a.time_slot_id
    WHERE ts.doctor_office_id = $officeId 
      AND ts.available_time > '$currentTime'
    GROUP BY ts.id, ts.available_time, ts.max_slots
    HAVING available_slots > 0
    ORDER BY ts.available_time ASC";

$slotsResult = $conn->query($slotsQuery);

$timeSlots = [];
while ($slot = $slotsResult->fetch_assoc()) {
    $timeSlots[] = [
        'id' => $slot['id'],
        'available_time' => $slot['available_time'],
        'available_slots' => $slot['available_slots']
    ];
}

header('Content-Type: application/json');
echo json_encode($timeSlots);
?>
