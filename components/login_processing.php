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

        // Check if user exists
        $sql = "SELECT * FROM users WHERE username='$usernameweb' AND password='$passwordweb'";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['userid'] = $user['id']; // Store user ID in session
            $_SESSION['role'] = $user['role']; // Store role in session

            echo "<console class='log'>User is " . $user['role'] . "</console>";

            // Redirect to home page
            header("Location: http://localhost/web-programming-assignment/index.php?page=home&isSignedIn=true&user=" . urlencode($user['role']));
            exit();
        } else {
            // Redirect to login page and send a message saying wrong username or password
            header("Location: http://localhost/web-programming-assignment/index.php?page=signin&message=wrong_username_or_password");
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