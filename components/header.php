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

<header class="d-flex justify-content-between align-items-center overflow-hidden shadow-lg" style="background-color: #5390d9; color: white; top: 0; width: 100%; height: 5.5em;">
    <div class="d-flex p-3" style="width: fit-content;">
        <h2 class="m-0" style="color: white">Medical Appointment App</h2>
    </div>

    <!-- 
        This navigation bar displays only home, login, it will start showing other tabs when user is logged in and will display by what that user has access to
    -->
    <!-- <nav class="w-100" style="background-color: #5390d9; font-size: 1.2em;">
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
                    <a class="nav-link <?php echo $page == 'logout' ? 'active' : ''; ?>" href="index.php?page=home&isSignedIn=false" style="<?php echo $page == 'logout' ? '' : 'color:#001233'; ?>">Logout</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav> -->
    <div class="d-flex align-items-center h-100">
        <nav class="position-relative z-2 d-flex h-100">
            <div class="h-100 d-flex tab-container">
                <a class="nav-link align-self-center <?php echo $page == 'home' ? 'active' : ''; ?>" href="index.php?page=home<?php echo $isSignedIn ? '&isSignedIn=' . $isSignedIn : ''; ?><?php echo $isSignedIn ? '&user=' . $user : ''; ?>" style="<?php echo $page == 'home' ? '' : 'color:#001233'; ?>"> <strong>Home</strong></a>
            </div>
            <?php if (!$isSignedIn): ?>
                <div class="h-100 d-flex tab-container">
                    <a class="nav-link align-self-center <?php echo $page == 'signin' ? 'active' : ''; ?>" href="index.php?page=signin" style="<?php echo $page == 'signin' ? '' : 'color:#001233'; ?>"> <strong>Login</strong></a>
                </div>
            <?php else: ?>
                <?php if ($user == 'admin'): ?>
                    <div class="h-100 d-flex tab-container">
                        <a class="nav-link align-self-center <?php echo $page == 'admin' ? 'active' : ''; ?>" href="index.php?page=admin&isSignedIn=true&user=admin" style="<?php echo $page == 'admin' ? '' : 'color:#001233'; ?>"> <strong>Admin</strong></a>
                    </div>
                <?php elseif ($user == 'staff'): ?>
                    <div class="h-100 d-flex tab-container">
                        <a class="nav-link align-self-center <?php echo $page == 'staff' ? 'active' : ''; ?>" href="index.php?page=staff&isSignedIn=true&user=staff" style="<?php echo $page == 'staff' ? '' : 'color:#001233'; ?>"> <strong>Staff</strong></a>
                    </div>
                <?php elseif ($user == 'doctor'): ?>
                    <div class="h-100 d-flex tab-container">
                        <a class="nav-link align-self-center <?php echo $page == 'doctor' ? 'active' : ''; ?>" href="index.php?page=doctor&isSignedIn=true&user=doctor" style="<?php echo $page == 'doctor' ? '' : 'color:#001233'; ?>"> <strong>Doctor</strong></a>
                    </div>
                <?php elseif ($user == 'guest' || $user == 'patient'): ?>
                    <div class="h-100 d-flex tab-container">
                        <a class="nav-link align-self-center <?php echo $page == 'guest' ? 'active' : ''; ?>" href="index.php?page=guest&isSignedIn=true&user=guest" style="<?php echo $page == 'guest' ? '' : 'color:#001233'; ?>"> <strong>Patient</strong></a>
                    </div>
                <?php endif; ?>
                <div class="h-100 d-flex tab-container">
                    <a class="nav-link align-self-center <?php echo $page == 'logout' ? 'active' : ''; ?>" href="index.php?page=home&isSignedIn=false" style="<?php echo $page == 'logout' ? '' : 'color:#001233'; ?>"> <strong>Logout</strong></a>
                </div>
            <?php endif; ?>
        </nav>
        <div class="position-relative z-3 h-100 d-flex align-items-center justify-content-center" style="background-color: #5390d9; width: 3em;">
            <svg class="nav-expand-button" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M0 96C0 78.3 14.3 64 32 64l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 288c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32L32 448c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z"/></svg>
        </div>
    </div>
</header>

<script>
    $(document).ready(function(){
        $('nav').addClass('nav-expand')
        $('.nav-expand-button').click(function(e){
            e.preventDefault();
            $('nav').toggleClass('nav-expand')
        })
    })
</script>