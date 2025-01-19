<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Validate required fields
    if (empty($full_name) || empty($username) || empty($password) || empty($email)) {
        header("Location: ../?message=All fields are required&status=error");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../?message=Invalid email format&status=error");
        exit();
    }

    // Validate password length
    if (strlen($password) < 8) {
        header("Location: ../?message=Password must be at least 8 characters&status=error");
        exit();
    }

    // Generate a random salt
    $salt = bin2hex(random_bytes(32)); // Generate a 32-byte salt

    // Hash password with SHA-512 and the salt
    $hashed_password = hash('sha512', $password . $salt);

    // Set default role and outlet_id
    $role = 'user';
    $outlet_id = null;

    // Check if username or email already exists
    $checkUsernameQuery = "SELECT * FROM user WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query($conn, $checkUsernameQuery);

    if (mysqli_num_rows($result) > 0) {
        header("Location: ../?message=Username or email already taken&status=error");
        exit();
    } else {
        // Insert user into the database, storing the salt along with the hash
        $insertQuery = "INSERT INTO user (nama_user, username, email, password, salt, outlet_id, role) 
                        VALUES ('$full_name', '$username', '$email', '$hashed_password', '$salt', '$outlet_id', '$role')";

        if (mysqli_query($conn, $insertQuery)) {
            header("Location: ../?message=Registration successful, please login&status=success");
            exit();
        } else {
            // Log error message to troubleshoot
            error_log("Error: " . mysqli_error($conn));
            header("Location: ../?message=Error registering user&status=error");
            exit();
        }
    }
}
?>
