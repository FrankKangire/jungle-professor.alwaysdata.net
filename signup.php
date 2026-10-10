<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "connection.php";

if(isset($_POST["submit"])){
    $username = mysqli_real_escape_string($conn, $_POST["name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["pass"];
    $cpassword = $_POST["cpass"];

    $check = mysqli_query(
        $conn,
        "SELECT * FROM users WHERE username = '$username' OR email = '$email'"
    );

    if(mysqli_num_rows($check) == 0){

        if($password == $cpassword){

            $hash = password_hash($password, PASSWORD_DEFAULT);

            mysqli_query(
                $conn,
                "INSERT INTO users(username, email, password)
                 VALUES('$username', '$email', '$hash')"
            );

            // LOG IN AUTOMATICALLY
            $new_user_res = mysqli_query(
                $conn,
                "SELECT * FROM users WHERE username = '$username'"
            );

            $user_data = mysqli_fetch_assoc($new_user_res);

            session_start();

            $_SESSION["user_id"] = $user_data["id"];
            $_SESSION["username"] = $user_data["username"];

            header("Location: welcome.php");
            exit();

        } else {
            echo '<script>alert("Passwords do not match");</script>';
        }

    } else {
        echo '<script>alert("User already exists");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sign Up - Jungle Professor</title>

    <link
        rel="stylesheet"
        href="bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css"
    >

    <link rel="stylesheet" href="css/theme.css">
</head>

<!-- Use the same background system as the login page -->
<body data-jp-bg="general">

    <?php $currentPage = 'signup'; ?>

    <?php include "navbar.php"; ?>

    <div class="container">

        <div class="row justify-content-center mt-5">

            <div class="col-md-5 col-sm-10">

                <div class="jp-card p-4 mt-4">

                    <h2 class="text-center mb-4">
                        🌿 Create Account
                    </h2>

                    <form method="post" action="signup.php">

                        <div class="mb-3">
                            <label class="form-label">
                                Username
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control jp-input"
                                placeholder="Enter username"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control jp-input"
                                placeholder="Enter email"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                name="pass"
                                class="form-control jp-input"
                                placeholder="Enter password"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Confirm Password
                            </label>

                            <input
                                type="password"
                                name="cpass"
                                class="form-control jp-input"
                                placeholder="Confirm password"
                                required
                            >
                        </div>

                        <button
                            type="submit"
                            name="submit"
                            class="btn btn-jp-primary w-100 mt-2"
                        >
                            Create Account & Play
                        </button>

                    </form>

                    <p
                        class="text-center mt-3 small"
                        style="color:#a9dfbf;"
                    >
                        Already have an account?
                        <a
                            href="login.php"
                            style="color:#2ecc71;"
                        >
                            Sign in
                        </a>
                    </p>

                </div>

            </div>

        </div>

    </div>

    <script src="bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

    <script src="js/slideshow.js"></script>

</body>
</html>
