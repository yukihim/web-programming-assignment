<?php
session_start();

header('Content-Type: application/json'); // Ensure the response is JSON

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'patient') {
    echo json_encode(['success' => false, 'message' => 'You must be logged in as a patient to book an appointment.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctorOffice = $_POST['doctor_office'];
    $timeSlot = $_POST['time_slot'];
    $statuss = 'confirmed';
    $patientId = $_SESSION['userid'];

    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $time_right_now = date('Y-m-d H:i:s');

    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "medical_appointment";

    try {
        $conn = new mysqli($host, $username, $password, $dbname);
        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_office_id, time_slot_id, status, created_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiss", $patientId, $doctorOffice, $timeSlot, $statuss, $time_right_now);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to book appointment.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    } finally {
        $conn->close();
    }
}
?>
