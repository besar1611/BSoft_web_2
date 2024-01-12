<?php
require_once('auth.php');
// Check if the id parameter is provided in the URL
if (isset($_GET['id'])) {
    // Retrieve the id parameter
    $id = $_GET['id'];

    // Perform the database operation to delete the news article
    require_once('../mysqli_connect.php');

    // Construct the DELETE query
    $query = "DELETE FROM portfolio_t WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        // Deletion successful, redirect to the confirmation page
        header('Cache-Control: no-cache, must-revalidate');
        $_SESSION['successMessage'] = "Successfully deleted.";
        header('Location: projects.php');
        exit;
    } else {
        echo "Error deleting account: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // If the id parameter is not provided, redirect to an error page or handle the error accordingly
    echo "Invalid request";
}
?>
