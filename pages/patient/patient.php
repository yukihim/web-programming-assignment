<?php
// Check if the user is signed in and is a guest
session_start();
if (!isset($_GET['isSignedIn']) || $_GET['user'] !== 'guest') {
    echo "<script>
            alert('You must log in as a guest to access this page.');
            window.location.href = 'index.php?page=signin';
          </script>";
    exit();
}

echo "<script>const userid = " . json_encode($_SESSION['userid']) . ";</script>";

// Connect to the database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "medical_appointment";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch doctor offices and available time slots
$officesQuery = "SELECT * FROM doctor_offices";
$officesResult = $conn->query($officesQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #4CAF50;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }
        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }
        .form-select {
            border-radius: 8px;
        }
        .mb-3 {
            margin-bottom: 20px;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    
    <h2>Book an Appointment</h2>
    <form action="pages/patient/book_appointment.php" method="POST">
        <!-- Select Doctor Office -->
        <div class="mb-3">
            <label for="doctor_office" class="form-label">Doctor Office</label>
            <select id="doctor_office" name="doctor_office" class="form-select" required>
                <option value="" disabled selected>Select a doctor office</option>
                <?php while ($office = $officesResult->fetch_assoc()): ?>
                    <option value="<?php echo $office['id']; ?>"><?php echo $office['name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Select Time Slot -->
        <div class="mb-3">
            <label for="available_time" class="form-label">Available Time Slot</label>
            <select id="available_time" name="time_slot" class="form-select" required>
                <option value="" disabled selected>Select a time slot</option>
                <!-- Time slots will be loaded dynamically via JavaScript -->
            </select>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-100">Book Appointment</button>
    </form>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<script>
    // Load available time slots dynamically based on selected doctor office
    console.log(`Logged-in User ID: ${userid}`);
    document.getElementById('doctor_office').addEventListener('change', function() {
        const officeId = this.value;
        console.log(`Selected Doctor Office ID: ${officeId}`);
        fetch(`pages/patient/get_time_slots.php?office_id=${officeId}`)
            .then(response => response.json())
            .then(data => {
                const timeSlotSelect = document.getElementById('available_time');
                timeSlotSelect.innerHTML = '<option value="" disabled selected>Select a time slot</option>';
                data.forEach(slot => {
                    const option = document.createElement('option');
                    option.value = slot.id;
                    option.textContent = slot.available_time;
                    timeSlotSelect.appendChild(option);
                });
            });
    });
</script>

</body>
</html>
