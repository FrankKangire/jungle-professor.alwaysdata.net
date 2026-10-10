<?php
include "connection.php";
session_start();
if(isset($_POST["submit"])){
    $username = mysqli_real_escape_string($conn, $_POST["name"]);
    $password = $_POST["pass"];
    $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if($row && password_verify($password, $row["password"])){
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        header("Location: welcome.php");
    } else {
        echo '<script>alert("Invalid credentials");</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jungle Professor</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/theme.css">
</head>
<!-- data-jp-bg="general" tells the slideshow which image set to use -->
<body data-jp-bg="general">
    <?php $currentPage = 'login';?>
    <?php include "navbar.php"; ?>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-4 col-sm-10">
                <div class="jp-card p-4 mt-4">
                    <h2 class="text-center mb-4">🌿 Login</h2>
                    <form action="login.php" method="post">
                        <div class="mb-3">
                            <label class="form-label">Username or Email</label>
                            <input type="text" name="name" 
                                   class="form-control jp-input" 
                                   placeholder="Enter username or email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="pass" 
                                   class="form-control jp-input" 
                                   placeholder="Enter password" required>
                        </div>
                        <button type="submit" name="submit" 
                                class="btn btn-jp-primary w-100 mt-2">
                            Sign In
                        </button>
                    </form>
                    <p class="text-center mt-3 small" style="color:#a9dfbf;">
                        New here? <a href="signup.php" style="color:#2ecc71;">Create account</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script src="bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/slideshow.js"></script>
</body>
</html>