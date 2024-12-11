<?php
// session_start();
if (!isset($_SESSION['isSignedIn']) || !$_SESSION['isSignedIn']) {
    header("Location: login.php"); // Redirect to login if not signed in
    exit();
}
$db = mysqli_connect("localhost", "root", NULL, "medical_appointment", 3306, NULL);

// Assuming patient is logged in, and their user ID is stored in session
$patientId = $_SESSION['user_id']; // Get logged-in patient ID

$query = "SELECT a.patient_id, a.status, a.time_slot_id, ts.available_time, u.name AS doctor_name, a.created_at, do.name AS doctor_office_name
          FROM appointments a
          JOIN time_slots ts ON a.time_slot_id = ts.id
          JOIN doctor_offices do ON a.doctor_office_id = do.id
          JOIN users u ON do.doctor_id = u.id
          WHERE a.patient_id = ?";

$stmt = $db->prepare($query);
$stmt->bind_param("i", $patientId);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2>Appointment History</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Doctor Office</th>
                <th>Doctor Name</th>
                <th>Time Slot</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($appointment = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($appointment['doctor_office_name']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['available_time']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['status']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['created_at']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>