<?php
session_start(); // Start the session to store session variables

if (isset($_POST['username']) && isset($_POST['password'])) {
    $usernameweb = $_POST['username'];
    $passwordweb = $_POST['password'];
}

$host = "localhost";
$username = "root";
$password = "";
$dbname = "medical_appointment";
$conn = null;

try {
    // Create connection
    $conn = new mysqli($host, $username, $password);
    
    // Check connection
    if ($conn->connect_error) {
        die("(Database) Connection failed: " . $conn->connect_error);
    }

    // Select the database
    $conn->select_db($dbname);

    // Check if user exists (get user details including the hashed password)
    $sql = "SELECT * FROM users WHERE username='$usernameweb'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password using password_verify()
        if (password_verify($passwordweb, $user['password'])) {
            // Password is correct, log the user in
            $_SESSION['userid'] = $user['id']; // Store user ID in session
            $_SESSION['role'] = $user['role']; // Store role in session

            $_SESSION['isSignedIn'] = true;
            $_SESSION['user'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];

            // If the user is a doctor, get additional information
            if ($user['role'] === 'doctor') {
                $stmt = $conn->prepare("SELECT id AS doctor_office_id FROM doctor_offices WHERE doctor_id = ?");
                $stmt->bind_param("i", $user['id']);
                $stmt->execute();
                $doctorOfficeResult = $stmt->get_result();

                if ($doctorOfficeResult && $doctorOfficeResult->num_rows > 0) {
                    $doctorOffice = $doctorOfficeResult->fetch_assoc();
                    $_SESSION['doctor_office_id'] = $doctorOffice['doctor_office_id'];
                } else {
                    $_SESSION['doctor_office_id'] = null;
                }
            }

            // Redirect based on the user role
            header("Location: ../index.php?page=home&isSignedIn=true&user=" . urlencode($user['role']));
            exit();
        } else {
            // Incorrect password
            header("Location: ../index.php?page=signin&message=wrong_username_or_password");
            exit();
        }
    } else {
        // Username not found
        header("Location: ../index.php?page=signin&message=wrong_username_or_password");
        exit();
    }

} catch (mysqli_sql_exception $e) {
    echo "->(Database) Error connecting: " . $e->getMessage();
} finally {
    // Close the connection
    if ($conn) {
        $conn->close();
    }
}
?>
