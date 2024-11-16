<!DOCTYPE html>
<html>
<head>
    <title>Medical Appointment App</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- <link rel="stylesheet" href="assets/styles/styles.css"> -->
</head>
<body>
    <!-- Add header -->
    <?php include 'components/header.php'; ?>

    <!-- View Controller -->
    <?php
        // Check if page is set or else set it to home
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 'home';
        }

        switch ($page) {
            case 'home':
                echo '<p>Welcome to the home page!</p>';
                break;
            case 'signin':
                // TODO: Implement sign in page

                break;
            case 'admin':
                // TODO: If not signed in then redirect to sign in page and send an alert "You have to log in first"
                
                break;
            case 'staff':
                // TODO: If not signed in then redirect to sign in page and send an alert "You have to log in first"
                
                break;
            case 'doctor':
                // TODO: If not signed in then redirect to sign in page and send an alert "You have to log in first"
                
                break;
            case 'patient':
                // TODO: If not signed in then redirect to sign in page and send an alert "You have to log in first"
                
                break;
            default:
                // Redirect to Homepage
                echo '<p>Welcome to the home page!</p>';
                break;
        }
    ?>

    <!-- Add footer -->
    <?php include 'components/footer.php'; ?>
</body>
</html>