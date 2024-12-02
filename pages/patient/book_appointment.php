<?php
session_start();

// Check if the user is logged in as a patient
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'patient') {
    echo "<script>
            alert('You must be logged in as a patient to book an appointment.');
            window.location.href = 'index.php?page=signin';
          </script>";
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctorOffice = $_POST['doctor_office'];
    $timeSlot = $_POST['time_slot'];
    $fullName = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $patientId = $_SESSION['userid']; // Get the logged-in patient's ID

    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "medical_appointment";

    try {
        $conn = new mysqli($host, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }

        // Insert appointment into the database
        $stmt = $conn->prepare("INSERT INTO appointments (doctor_office_id, time_slot_id, patient_id, full_name, phone, email) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisss", $doctorOffice, $timeSlot, $patientId, $fullName, $phone, $email); // Bind parameters including patient_id

        if ($stmt->execute()) {
            // Redirect with success message
            header("Location: http://localhost/web-programming-assignment/index.php?page=guest&isSignedIn=true&user=guest&message=Appointment+Booked");
        } else {
            // Redirect with error message
            header("Location: http://localhost/web-programming-assignment/index.php?page=guest&isSignedIn=true&user=guest&message=Failed+to+Book+Appointment");
        }
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        $conn->close();
    }
}
?>
