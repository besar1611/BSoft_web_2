<?php
require_once('auth.php');
// Database connection
require_once('../mysqli_connect.php');

// Fetch data for specific ID
$id = $_GET['id']; // Assuming the ID is passed through the URL parameter
$query = "SELECT * FROM projects_t WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$title = $_POST['title'];
$category = $_POST['category'];
$clientName = $_POST['clientname'];
$address = $_POST['address'];
$area = $_POST['area'];
$headerText = $_POST['headertext'];
$textContent = $_POST['exampleTextarea'];

// Update query with prepared statements
$updateQuery = "UPDATE projects_t SET title = ?, category = ?, client_name = ?, address = ?, area = ?, height_text = ?, text = ? WHERE id = ?";

// Create a prepared statement
$stmt = mysqli_prepare($conn, $updateQuery);

// Bind parameters
mysqli_stmt_bind_param($stmt, "sssssssi", $title, $category, $clientName, $address, $area, $headerText, $textContent, $id);

// Execute the update query
if (mysqli_stmt_execute($stmt)) {
    $_SESSION['successMessage'] = "Updated Successfully.";
    header('Location: ./projects.php');
    exit;
} else {
    // Display an error message if the update fails
    echo "Error updating user: " . mysqli_error($conn);
}

// Close the statement
mysqli_stmt_close($stmt);

// Close the database connection
mysqli_close($conn);
?>
