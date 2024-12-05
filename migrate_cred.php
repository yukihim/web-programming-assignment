<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "medical_appointment";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, password FROM users";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $user_id = $row['id'];
    $plain_text_password = $row['password'];

    // Skip if already hashed
    if (password_get_info($plain_text_password)['algo'] === 0) {
        $hashed_password = password_hash($plain_text_password, PASSWORD_DEFAULT);
        $update_query = "UPDATE users SET password='$hashed_password' WHERE id=$user_id";
        $conn->query($update_query);
    }
}

$conn->close();
echo "Password migration completed.";
?>
