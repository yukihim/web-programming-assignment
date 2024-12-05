

<?php
// Check if session is started
if (!isset($_SESSION)) {
    session_start();
}

// Connect to the database
$mysql = mysqli_connect("localhost", "root", NULL, "medical_appointment", 3306, NULL);

// Get all doctor offices (for dropdown)
$doctor_offices = mysqli_query($mysql, "SELECT * FROM doctor_offices");

// Process form submission (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = mysqli_real_escape_string($mysql, $_POST['name']);
    $email = mysqli_real_escape_string($mysql, $_POST['email']);
    $phone = mysqli_real_escape_string($mysql, $_POST['phone']);
    $password = mysqli_real_escape_string($mysql, $_POST['password']);
    $username = mysqli_real_escape_string($mysql, $_POST['username']);
    $doctor_office = mysqli_real_escape_string($mysql, $_POST['doctor_office']);
    $timeslot = mysqli_real_escape_string($mysql, $_POST['timeslot']);

    // Check if username is unique
    $username_check_query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($mysql, $username_check_query);

    if (mysqli_num_rows($result) > 0) {
        // Username is already taken - Show alert
        echo "<script>
                alert('Username is already taken. Please choose another one.');
                window.location.href = 'index.php?page=home&isSignedIn=false&user=guest';
              </script>";
        exit(); // Stop further execution
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert patient information into the 'users' table
        $query = "INSERT INTO users (name, email, phone, password, username, role) 
                  VALUES ('$name', '$email', '$phone', '$hashed_password', '$username', 'patient')";
        if (mysqli_query($mysql, $query)) {
            // Get patient ID
            $patient_id = mysqli_insert_id($mysql);

            // Insert appointment into the 'appointments' table with status 'confirmed'
            $query_appointments = "INSERT INTO appointments (patient_id, doctor_office_id, time_slot_id, status) 
                                   VALUES ('$patient_id', '$doctor_office', '$timeslot', 'confirmed')";
            if (mysqli_query($mysql, $query_appointments)) {
                // Set session variable for the user
                $_SESSION['userid'] = $patient_id;
                $_SESSION['appointment_success'] = true;
                header("Location: index.php?page=home&isSignedIn=false&user=guest&appointment_success=true");

                // Redirect and show success message
                // echo "<script>
                //         document.addEventListener('DOMContentLoaded', function () {
                //             var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                //             successModal.show();
                //         });
                //     </script>";
            } else {
                echo "<script>
                        alert('Failed to create appointment.');
                        window.location.href = 'index.php?page=home&isSignedIn=false&user=guest';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Failed to register. Please try again.');
                    window.location.href = 'index.php?page=home&isSignedIn=false&user=guest';
                  </script>";
        }
        exit(); // Stop further execution
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Appointment System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid mt-5">
    <div class="row justify-content-start">
        <!-- Left side (Image) -->
        <div class="col-md-6 d-flex justify-content-center align-items-center">
            <img src="assets/images/image.png" alt="Medical Services" class="img-fluid rounded">
        </div>

        <!-- Right side (Form) -->
        <div class="col-md-6 d-flex justify-content-center align-items-center">
            <div>
                <h2>Welcome to Our Medical Appointment System</h2>
                <p>Our platform is designed to make booking medical appointments easier and more convenient for you. With just a few clicks, you can schedule an appointment with some of the best doctors in town, whether it's for a routine check-up, consultation, or specialized treatment.</p>

                <p>We understand that your health is important, which is why we’ve created a user-friendly system that allows you to:</p>
                <ul>
                    <li>Book appointments with trusted healthcare professionals at your convenience.</li>
                    <li>Choose from a wide variety of specialties, including general practitioners, specialists, and more.</li>
                    <li>Check doctor availability in real-time to ensure that you can get the earliest possible appointment.</li>
                    <li>Receive confirmation and reminders for your appointments to avoid any last-minute surprises.</li>
                    <li>Manage your medical appointments online without the need for phone calls or waiting in long queues.</li>
                </ul>

                <p>Our system ensures that you get the right care at the right time, with transparency and ease. With our advanced technology and dedicated support, we aim to make healthcare more accessible and affordable for everyone.</p>

                <p>By registering today, you can begin your journey toward better health. It’s quick, simple, and secure. Join thousands of other patients who trust us to manage their healthcare appointments efficiently.</p>

                <p>Take control of your health today and experience the convenience of booking medical appointments with just a few clicks!</p>

                <p><strong>Ready to get started? Register now and schedule your first appointment!</strong></p>
            </div>
        </div>
    </div>

    <!-- Patient Registration Form -->
    <div class="card shadow-sm rounded" style="max-width: 500px; margin: 20px auto; margin-bottom: 5em;">
        <div class="card-body">
            <h2 class="card-title text-center">New Patient Registration</h2>
            
            <!-- Registration Form -->
            <form action="index.php?page=home&isSignedIn=true&user=guest" method="POST">
                <div class="form-group">
                    <label for="name" class="form-label">Patient Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <!-- Add Username field -->
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>

                <!-- Select Doctor's Office -->
                <div class="form-group">
                    <label for="doctor_office" class="form-label">Select Doctor's Office</label>
                    <select class="form-select" id="doctor_office" name="doctor_office" required>
                        <option value="">Select office</option>
                        <?php while ($doctor_office = mysqli_fetch_assoc($doctor_offices)): ?>
                            <option value="<?= $doctor_office['id'] ?>"><?= $doctor_office['name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Select Time Slot -->
                <div class="form-group" id="timeslot-container">
                    <label for="timeslot" class="form-label">Select Time Slot</label>
                    <select class="form-select" id="timeslot" name="timeslot" required>
                        <option value="">Select time slot</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-3">Sign Up & Register</button>
            </form>
            <p class="text-center mt-3" style="color: green; font-weight: bold;">
                Thank you for registering with us! We will send you a confirmation soon. 
                If you want to make more appointments, please <a href="index.php?page=signin" style="color: blue; text-decoration: underline;">sign in</a>.
            </p>

        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Appointment Booked Successfully. Please check your email for details.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="window.location.href='index.php?page=home';">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Check if the appointment was successfully booked based on the URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('appointment_success') === 'true') {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        }
    });
</script>
</body>
</html>