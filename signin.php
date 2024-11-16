<!-- signin.php -->
<!--
    This file will contain the sign in form
-->

<?php
    // $host = "localhost";
    // $username = "root";
    // $password = "";
    // $dbname = "users";

    // // Connect to MySQL server
    // $conn = new mysqli($host, $username, $password);

    // // Check connection
    // if ($conn->connect_error) {
    //     die("(Server) Connection failed: " . $conn->connect_error);
    // }

    // // Check if the database exists
    // $query = "SHOW DATABASES LIKE '$dbname'";
    // $result = $conn->query($query);

    // if ($result->num_rows == 0) {
    //     // If there is no database named dbname in the server
    //     $result = null;
    // } else {
    //     // Connect to db name dbname
    //     $conn->select_db($dbname);

    //     // Fetch data from the users table
    //     $sql = "SELECT * FROM users";
    //     $result = $conn->query($sql);
    // }
?>

<form action="login.php" method="POST" class="d-flex justify-content-center pt-5">
    <div class="w-25 p-5 border" style="border-radius: 20; min-width: 400px;">
        <h2>Login</h2>
        <label for="username" class="form-label">Username</label><br>
        <input type="text" class="form-control" id="username" name="username" required>
        <label for="password" class="form-label">Password</label><br>
        <input type="password" class="form-control" id="password" name="password" required>
        <div class="d-flex justify-content-end">
            <a href="index.php?user=guest" class="text-primary" style="text-decoration: underline;">Sign in as Guest?</a><br><br>
        </div>
        <button type="submit" class="btn btn-primary w-100">Sign In</button>
    </div>
</form>