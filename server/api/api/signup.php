<?php
// FILE: signup.php (Corrected for PDO with Input Checks)

include 'db_connect.php'; // This provides a PDO connection object named $conn

// --- NEW: Check if the required POST variables are set ---
if (!isset($_POST['username']) || !isset($_POST['password'])) {
    // If they are not set, it means the script was likely visited directly
    // in a browser or the app sent a bad request.
    echo json_encode([
        "status" => "error", 
        "message" => "Invalid request. Username and password are required."
    ]);
    // Stop the script here.
    exit();
}

// If we get past the check, we can safely use the variables.
$username = $_POST['username'];
$password = $_POST['password'];

// Prevent empty submissions
if (empty($username) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Username and password cannot be empty."]);
    exit();
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // First, check if the user exists using PDO syntax
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        echo json_encode(["status" => "error", "message" => "Username already exists."]);
    } else {
        // Insert new user using PDO syntax
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        if ($stmt->execute([$username, $hashed_password])) {
            echo json_encode([
                "status" => "success", 
                "username" => $username, 
                "message" => "Signup successful!"
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error during signup."]);
        }
    }
} catch (PDOException $e) {
    // Catch any database-level errors
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}

?>
