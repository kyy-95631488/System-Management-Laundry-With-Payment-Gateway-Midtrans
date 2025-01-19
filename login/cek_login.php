<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "mikj2431_mikada-laundry");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = $_POST['username'];
$password = $_POST['password'];

// Prepare the SQL statement
$query = "SELECT * FROM user WHERE username = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    // Bind the parameter
    mysqli_stmt_bind_param($stmt, "s", $username);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    // Verify password
    if ($data) {
        // Extract salt and stored hashed password
        $stored_hash = $data['password']; // Password hash from database
        $salt = $data['salt'];           // Salt from database

        // Combine input password with the salt and hash it
        $hashed_input_password = hash('sha512', $password . $salt);

        // Compare the hashes
        if ($hashed_input_password === $stored_hash) {
            // Password is correct, set session variables
            $_SESSION['role'] = $data['role'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['user_id'] = $data['id_user'];
            $_SESSION['outlet_id'] = $data['outlet_id'];

            // Redirect based on role
            if ($data['role'] == 'admin') {
                header('location: ../manage/admin/');
            } 
            // Uncomment the block below if needed in the future
            /* else if ($data['role'] == 'kasir') {
                header('location: ../manage/kasir/');
            } else if ($data['role'] == 'owner') {
                header('location: ../manage/owner/');
            } */
            else if ($data['role'] == 'user') {
                header('location:../manage/user/');
            } else {
                // If role is not recognized, redirect to the main page
                header('location: ./../');
            }
        } else {
            // Password mismatch
            $message = 'Username atau password salah!!!';
            header('location:index.php?message=' . urlencode($message));
        }
    } else {
        // User not found
        $message = 'Username atau password salah!!!';
        header('location:index.php?message=' . urlencode($message));
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    die("Failed to prepare statement: " . mysqli_error($conn));
}

// Close the connection
mysqli_close($conn);
?>
