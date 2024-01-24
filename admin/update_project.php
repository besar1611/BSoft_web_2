<?php
require_once('auth.php');
require_once('../mysqli_connect.php');

// Check the database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the project ID from the form
    $projectId = isset($_GET['id']) ? $_GET['id'] : null;
    

     // Validate the project ID (add your validation logic if needed)
     if (!$projectId || !is_numeric($projectId)) {
    //    // Invalid ID, redirect to the confirmation page
    //    header('Cache-Control: no-cache, must-revalidate');
    //    $_SESSION['errorMessage'] = "Invalid ID.";
    //    header('Location: projects.php');

    echo "Debug: Project ID - $projectId";
       exit;
      }

    // Escape user inputs for security
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $platform = mysqli_real_escape_string($conn, $_POST['platform']);
    $text = mysqli_real_escape_string($conn, $_POST['example-textarea-input']);

    // Modify category_text based on the selected category
    $categoryText = '';
    switch ($category) {
        case 'web_design':
            $categoryText = 'Web Development';
            break;
        case 'desktop_apps':
            $categoryText = 'Desktop App';
            break;
        case 'mobile_apps':
            $categoryText = 'Mobile App';
            break;
        case 'logo_design':
            $categoryText = 'Logo Design';
            break;
        // Add more cases if needed
        default:
            $categoryText = 'Default Category Text';
    }

    // Update data in the portfolio table
    $sql = "UPDATE portfolio_t SET title='$title', category='$category', portfolio_text='$text', category_text='$categoryText', platform='$platform' WHERE id='$projectId'";

    if (mysqli_query($conn, $sql)) {
        // Update successful, redirect to the confirmation page
        header('Cache-Control: no-cache, must-revalidate');
        $_SESSION['successMessage'] = "Successfully updated.";
        header('Location: projects.php');
        exit;
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    // Close the connection
    mysqli_close($conn);
}
?>
