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

    function login_user($user, $conn) {
        session_start();
        $_SESSION['userid'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['isSignedIn'] = true;
        $_SESSION['user'] = $user['role'];
        $_SESSION['user_id'] = $user['id'];
    
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
    
        header("Location: ../index.php?page=home&isSignedIn=true&user=" . urlencode($user['role']));
        exit();
    }
    
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $stored_password = $user['password'];
        
        // Check if the stored password is hashed
        if (password_verify($passwordweb, $stored_password)) {
            // Password is hashed and verified
            login_user($user, $conn);
        } else if ($stored_password === $passwordweb) {
            // Password is plain text and matches
            // Migrate this user's password to a hashed version
            $hashed_password = password_hash($passwordweb, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET password='$hashed_password' WHERE id={$user['id']}";
            $conn->query($update_query);
            
            // Log the user in
            login_user($user, $conn);
        } else {
            // Password does not match
            header("Location: ../index.php?page=signin&message=wrong_username_or_password");
            exit();
        }
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
