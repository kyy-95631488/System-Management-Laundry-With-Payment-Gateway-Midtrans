<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ./../../'); // Redirect to login if not logged in
    exit();
}

include_once './koneksi.php'; // Include the database connection file

// Fetch user name based on session user_id
$user_id = $_SESSION['user_id'];
$query = "SELECT nama_user FROM user WHERE id_user = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $nama_user = $user['nama_user'];
} else {
    $nama_user = 'Unknown User'; // Fallback if user is not found
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate form input
    $reviewer = $nama_user;  // Using nama_user instead of username
    $bintang = isset($_POST['bintang']) ? intval($_POST['bintang']) : 0;
    $paket = isset($_POST['paket']) ? htmlspecialchars($_POST['paket']) : '';
    $review = isset($_POST['review']) ? htmlspecialchars($_POST['review']) : '';

    // Validate rating value
    if ($bintang < 0 || $bintang > 5) {
        echo "Rating must be between 0 and 5.";
        exit();
    }

    // Prepare and bind statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO reviews (reviewer, bintang, paket, review) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $reviewer, $bintang, $paket, $review);

    // Execute the statement
    if ($stmt->execute()) {
        // Set success message in the session
        $_SESSION['review_success'] = "Review submitted successfully!";
        header('Location: ./../'); // Redirect back to index.php
    } else {
        $_SESSION['review_error'] = "Error submitting review. Please try again.";
        header('Location: ./../'); // Redirect back to index.php
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
