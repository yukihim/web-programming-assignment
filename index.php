<!DOCTYPE html>
<html>
<head>
    <title>Medical Appointment App</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- <link rel="stylesheet" href="assets/styles/styles.css"> -->
</head>
<body>
    <div style="min-width:auto;">
        <?php 
            $page = isset($_GET['page']) ? $_GET['page'] : 'home';
            $user = isset($_GET['user']) ? $_GET['user'] : null;
        ?>

        <!-- Header -->
        <?php include 'components/header.php'; ?>

        <!-- Navigation bar -->
        <!-- 
            This navigation bar displays only home, login, it will start showing other tabs when user is logged in and will display by what that user has access to
        -->
        <nav>
            <ul class="nav nav-tabs nav-justified">
                <li class="nav-item">
                    <a class="nav-link <?php echo $page == 'home' ? 'active' : ''; ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $page == 'login' ? 'active' : ''; ?>" href="index.php?page=signin">Login</a>
                </li>
            </ul>
        </nav>


        <!-- Controller -->
        <!-- 
            Following the MVC Principles
        -->
        <div>
            <?php
                switch ($page) {
                    case 'home':
                        echo '<p>Welcome to the home page!</p>';
                        break;
                    case 'signin':
                        // TODO: Implement sign in page
                        include 'signin.php';
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
        </div>

        <!-- Footer -->
        <?php include 'components/footer.php'; ?>
    </div>
</body>
</html>