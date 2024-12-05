<?php
// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// if isSigned = false, then unset the session
if (isset($_GET['isSignedIn']) && $_GET['isSignedIn'] == 'false') {
    session_unset();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Medical Appointment App</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="assets/styles/styles.css">

    <!-- Load jQuery from CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <div style="min-width:auto;">
        <?php 
            $page = isset($_GET['page']) ? $_GET['page'] : 'home';
            $isSignedIn = isset($_GET['isSignedIn']) ? $_GET['isSignedIn'] : false;
            $user = isset($_GET['user']) ? $_GET['user'] : null;
        ?>

        <!-- Header -->
        <?php include 'components/header.php'; ?>

        <!-- Controller: Body (Content) -->
        <!-- 
            Following the MVC Principles
        -->
        <div class="mx-5 mt-3 flex-grow-1" style="margin-bottom: 5em;">
            <?php
                switch ($page) {
                    case 'home':
                        // include 'index.php';
                        // echo   '<h1>
                        //             Hello!
                        //         </h1><hr>';
                        // echo    '<p>
                        //             This is the homepage for medical appointment with doctor!
                        //         </p>';
                        // break;

                        include 'pages/home/home.php';
                        break;
                    case 'signin':
                        // TODO: Implement sign in page
                        include 'signin.php';
                        break;
                    // case 'admin':
                    //     // TODO: If not signed in then redirect to sign in page and send an alert "You have to log in first"
                    //     if ($isSignedIn) {
                    //         include 'pages/admin.php';
                    //     } else {
                    //         // Allert: "You have to log in first"
                    //         echo    "<script>
                    //                     alert('You have to log in first');
                    //                     window.location.href = 'index.php?page=signin&isSignedIn=false';
                    //                 </script>";
                    //         include 'signin.php';
                    //     }
                    //     break;
                    case 'staff':
                        // TODO: If not signed in then redirect to sign in page and send an alert "You have to log in first"
                        if ($isSignedIn) {
                            include 'pages/staff.php';
                        } else {
                            echo    "<script>
                                        alert('You have to log in first');
                                        window.location.href = 'index.php?page=signin&isSignedIn=false';
                                    </script>";
                            include 'signin.php';
                        }
                        break;
                    case 'doctor':
                        // TODO: If not signed in then redirect to sign in page and send an alert "You have to log in first"
                        if ($isSignedIn) {
                            include 'pages/doctor/doctor.php';
                        } else {
                            // Allert: "You have to log in first"
                            echo    "<script>
                                        alert('You have to log in first');
                                        window.location.href = 'index.php?page=signin&isSignedIn=false';
                                    </script>";
                            include 'signin.php';
                        }
                        break;
                    case 'guest':
                        // TODO: If not signed in then redirect to sign in page and send an alert "You have to log in first"
                        include 'pages/patient/patient.php';                   
                        break;
                    case 'history':
                        // TODO: If not signed in then redirect to sign in page and send an alert "You have to log in first"
                        include 'pages/patient/views/history.php';                   
                        break;
                    default:
                        // Allert: "You have to log in first"
                        echo    "<script>
                                    alert('404 Page Not Found');
                                    window.location.href = 'index.php';
                                </script>";
                        // Redirect to Homepage
                        // include 'index.php';
                        break;
                }
            ?>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'components/footer.php'; ?>
</body>
</html>