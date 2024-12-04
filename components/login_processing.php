<?php
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

            // Kích hoạt session và lưu thông tin người dùng
            session_start();
            $_SESSION['isSignedIn'] = true;
            $_SESSION['user'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];

            // Nếu là bác sĩ, lưu thêm thông tin `doctor_office_id` vào session
            if ($user['role'] === 'doctor') {
                $stmt = $conn->prepare("
                    SELECT id AS doctor_office_id 
                    FROM doctor_offices 
                    WHERE doctor_id = ?
                ");
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

            // nếu là bệnh nhân, lưu thêm thông tin `patient_id` vào session
                //todo

            // nếu là staff, lưu thêm thông tin `staff_id` vào session
                //todo

            // nếu là admin, lưu thêm thông tin `admin_id` vào session
                //todo

            header("Location: ../index.php?page=home&isSignedIn=true&user=" . urlencode($user['role']));
            exit();
        } else {
            // Sai tên đăng nhập hoặc mật khẩu
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