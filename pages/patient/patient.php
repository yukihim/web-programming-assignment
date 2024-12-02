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
            <label for="time_slot" class="form-label">Available Time Slot</label>
            <select id="time_slot" name="time_slot" class="form-select" required>
                <option value="" disabled selected>Select a time slot</option>
                <!-- Time slots will be loaded dynamically via JavaScript -->
            </select>
        </div>

        <!-- Personal Information -->
        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" id="full_name" name="full_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" id="phone" name="phone" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email (Optional)</label>
            <input type="email" id="email" name="email" class="form-control">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-100">Book Appointment</button>
    </form>
</div>

<script>
    // Load available time slots dynamically based on selected doctor office
    console.log(`Logged-in User ID: ${userid}`);
    document.getElementById('doctor_office').addEventListener('change', function() {
        const officeId = this.value;
        // echo officeId;
        fetch(`pages/patient/get_time_slots.php?office_id=${officeId}`)
            .then(response => response.json())
            .then(data => {
                const timeSlotSelect = document.getElementById('time_slot');
                timeSlotSelect.innerHTML = '<option value="" disabled selected>Select a time slot</option>';
                data.forEach(slot => {
                    const option = document.createElement('option');
                    option.value = slot.id;
                    option.textContent = slot.time;
                    timeSlotSelect.appendChild(option);
                });
            });
    });
</script>
