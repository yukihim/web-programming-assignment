<?php
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';
    $isSignedIn = isset($_GET['isSignedIn']) ? $_GET['isSignedIn'] : false;
    $user = isset($_GET['user']) ? $_GET['user'] : null;

    // Handle logout
    if ($isSignedIn == 'false') {
        $isSignedIn = false;
        $user = null;
    }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>

<header class="d-flex flex-column align-items-end" style="background-color: #5390d9; color: #001233; top: 0; width: 100%;">
    <div class="d-flex justify-content-between w-100 p-3">
        <h1>Medical Appointment App</h1>
    </div>

    <!-- 
        This navigation bar displays only home, login, it will start showing other tabs when user is logged in and will display by what that user has access to
    -->
    <nav class="w-100" style="background-color: #5390d9; font-size: 1.2em;">
        <ul class="nav nav-tabs nav-justified">
            <li class="nav-item">
                <a class="nav-link <?php echo $page == 'home' ? 'active' : ''; ?>" href="index.php?page=home<?php echo $isSignedIn ? '&isSignedIn=' . $isSignedIn : ''; ?><?php echo $isSignedIn ? '&user=' . $user : ''; ?>" style="<?php echo $page == 'home' ? '' : 'color:#001233'; ?>">Home</a>
            </li>
            <?php if (!$isSignedIn): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo $page == 'signin' ? 'active' : ''; ?>" href="index.php?page=signin" style="<?php echo $page == 'signin' ? '' : 'color:#001233'; ?>">Login</a>
                </li>
            <?php else: ?>
                <?php if ($user == 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'admin' ? 'active' : ''; ?>" href="index.php?page=admin&isSignedIn=true&user=admin" style="<?php echo $page == 'admin' ? '' : 'color:#001233'; ?>">Admin</a>
                    </li>
                <?php elseif ($user == 'staff'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'staff' ? 'active' : ''; ?>" href="index.php?page=staff&isSignedIn=true&user=staff" style="<?php echo $page == 'staff' ? '' : 'color:#001233'; ?>">Staff</a>
                    </li>
                <?php elseif ($user == 'doctor'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'doctor' ? 'active' : ''; ?>" href="index.php?page=doctor&isSignedIn=true&user=doctor" style="<?php echo $page == 'doctor' ? '' : 'color:#001233'; ?>">Doctor</a>
                    </li>
                <?php elseif ($user == 'guest' || $user == 'patient'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'guest' ? 'active' : ''; ?>" href="index.php?page=guest&isSignedIn=true&user=guest" style="<?php echo $page == 'guest' ? '' : 'color:#001233'; ?>">Patient</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo $page == 'logout' ? 'active' : ''; ?>" href="index.php?page=home&isSignedIn=false" style="<?php echo $page == 'logout' ? '' : 'color:#ef476f'; ?>">Logout</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</header>