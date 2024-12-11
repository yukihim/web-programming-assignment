<!-- signin.php -->
<!--
    This file will contain the sign in form
-->

<?php
    $message = isset($_GET['message']) ? $_GET['message'] : null;
?>

<form action="components/login_processing.php" method="POST" style="min-height:calc(100vh - 20.5em)" class="d-flex justify-content-center pt-5">
    <div class="w-25 p-5 border shadow-lg" style="border-radius: 20px; min-width: 400px; height: fit-content;">
        <h2>Login</h2>
        <?php if ($message): ?>
            <div class="alert alert-danger" role="alert">
                <?php 
                    if ($message == "wrong_username_or_password") {
                        echo "You have entered wrong Username or Password!";
                    }
                ?>
            </div>
        <?php endif; ?>
        <label for="username" class="form-label">Username</label><br>
        <input type="text" class="form-control" id="username" name="username" required>
        <label for="password" class="form-label">Password</label><br>
        <input type="password" class="form-control" id="password" name="password" required>
        <div class="d-flex justify-content-end">
            <a href="index.php?page=home&isSignedIn=false" class="text-primary" style="text-decoration: underline;">Continue as a Guest?</a><br><br>
        </div>
        <button type="submit" class="btn btn-primary w-100">Sign In</button>
    </div>
</form>