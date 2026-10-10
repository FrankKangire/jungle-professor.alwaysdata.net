<?php
session_start();
// Redirect logged-in users straight to dashboard
if(isset($_SESSION['user_id'])) {
    header("Location: welcome.php");
    exit();
}
$currentPage = 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/theme.css">
    <title>Jungle Professor</title>
</head>
<!-- data-jp-bg="general" tells slideshow.js which images to use -->
<body data-jp-bg="general">
    <?php $currentPage = 'home';?>
    <?php include "navbar.php"; ?>

    <div class="container d-flex align-items-center justify-content-center" 
         style="min-height: 80vh;">
        <div class="text-center">
            <h1 style="color:#2ecc71; font-size: 3rem; font-weight: bold;">
                🌿 Jungle Professor
            </h1>
            <p style="color:#a9dfbf; font-size: 1.2rem; margin: 20px 0;">
                A Universal Home Study Guide in a World Safari Game
            </p>
            <div class="mt-4 d-flex gap-3 justify-content-center flex-wrap">
                <a href="login.php" class="btn btn-jp-primary">Login</a>
                <a href="signup.php" class="btn btn-jp-secondary">Sign Up</a>
            </div>
        </div>
    </div>

    <script src="bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/slideshow.js"></script>
</body>
</html>