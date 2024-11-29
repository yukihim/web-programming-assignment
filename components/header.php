<?php
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';
    $isSignedIn = isset($_GET['isSignedIn']) ? $_GET['isSignedIn'] : false;
    $user = isset($_GET['user']) ? $_GET['user'] : null;

    // Handle logout
    if ($isSignedIn == 'false') {
        $isSignedIn = false;
        $user = null;
    }
?>

<header class="d-flex flex-column align-items-end" style="background-color: #292726; color: #aba4a1; top: 0; width: 100%;">
    <div class="d-flex justify-content-between w-100 p-3">
        <h1>Medical Appointment App</h1>
    </div>

    <!-- 
        This navigation bar displays only home, login, it will start showing other tabs when user is logged in and will display by what that user has access to
    -->
    <nav class="w-100">
        <ul class="nav nav-tabs nav-justified">
            <li class="nav-item">
                <a class="nav-link <?php echo $page == 'home' ? 'active' : ''; ?>" href="index.php?page=home<?php echo $isSignedIn ? '&isSignedIn=' . $isSignedIn : ''; ?><?php echo $isSignedIn ? '&user=' . $user : ''; ?>" style="<?php echo $page == 'home' ? '' : 'color:#aba4a1'; ?>">Home</a>
            </li>
            <?php if (!$isSignedIn): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo $page == 'signin' ? 'active' : ''; ?>" href="index.php?page=signin" style="<?php echo $page == 'signin' ? '' : 'color:#aba4a1'; ?>">Login</a>
                </li>
            <?php else: ?>
                <?php if ($user == 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'admin' ? 'active' : ''; ?>" href="index.php?page=admin&isSignedIn=true&user=admin" style="<?php echo $page == 'admin' ? '' : 'color:#aba4a1'; ?>">Admin</a>
                    </li>
                <?php elseif ($user == 'staff'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'staff' ? 'active' : ''; ?>" href="index.php?page=staff&isSignedIn=true&user=staff" style="<?php echo $page == 'staff' ? '' : 'color:#aba4a1'; ?>">Staff</a>
                    </li>
                <?php elseif ($user == 'doctor'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'doctor' ? 'active' : ''; ?>" href="index.php?page=doctor&isSignedIn=true&user=doctor" style="<?php echo $page == 'doctor' ? '' : 'color:#aba4a1'; ?>">Doctor</a>
                    </li>
                <?php elseif ($user == 'guest'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'guest' ? 'active' : ''; ?>" href="index.php?page=guest&isSignedIn=true&user=guest" style="<?php echo $page == 'guest' ? '' : 'color:#aba4a1'; ?>">Patient</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo $page == 'logout' ? 'active' : ''; ?>" href="index.php?page=home&isSignedIn=false" style="<?php echo $page == 'logout' ? '' : 'color:#b23b3b'; ?>">Logout</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</header>