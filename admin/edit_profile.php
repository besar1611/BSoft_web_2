<?php
require_once('auth.php');
// Database connection
require_once('../mysqli_connect.php');

// Fetch data for specific ID
$id = $_GET['id']; // Assuming the ID is passed through the URL parameter
$query = "SELECT * FROM user_t WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Check if the password field is empty
if (!empty($_POST['xpassword'])) {
    // Password field is not empty, hash the new password
    $password = md5($_POST['xpassword']);

    // Update the password field in the database
    $updatePasswordQuery = "UPDATE user_t SET password = '$password' WHERE id = $id";
    mysqli_query($conn, $updatePasswordQuery);
}

// Update the other fields in the database
$fullname = $_POST['fullname'];
$username = $_POST['xusername'];
$email = $_POST['email'];

// Update query
$updateQuery = "UPDATE user_t SET fullname = '$fullname', username = '$username', email = '$email' WHERE id = $id";

// Execute the update query
if (mysqli_query($conn, $updateQuery)) {
    // $successMessage = "Me sukes u ndryshuan të dhënat.";
    // header('Location: index.php?successMessage=' . urlencode($successMessage));
    $_SESSION['successMessage'] = "Updated Successfully.";
    header('Location: index.php');
    exit;
} else {
    // Display an error message if the update fails
    echo "Error updating user: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>