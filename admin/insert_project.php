<?php
require_once('auth.php');
require_once('../mysqli_connect.php');

// Check the database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $platform = mysqli_real_escape_string($conn, $_POST['platform']);
    $text = mysqli_real_escape_string($conn, $_POST['example-textarea-input']);
    $download_link = mysqli_real_escape_string($conn, $_POST['download_link']);

    // File upload directory for portfolio_image and main_header_image
    $portfolioImageDirectory = 'upload/portfolio/';
    $mainHeaderImageDirectory = 'upload/portfolio/';

    // Handle portfolio_image upload
    $portfolioImageName = basename($_FILES["portfolio_image"]["name"]);
    $portfolioImageTarget = $portfolioImageDirectory . $portfolioImageName;
    move_uploaded_file($_FILES["portfolio_image"]["tmp_name"], $portfolioImageTarget);

    // Handle header_image upload
    $headerImageName = basename($_FILES["header_image"]["name"]);
    $headerImageTarget = $mainHeaderImageDirectory . $headerImageName;
    move_uploaded_file($_FILES["header_image"]["tmp_name"], $headerImageTarget);

    // Handle main_header_image upload
    $mainHeaderImageName = basename($_FILES["main_header_image"]["name"]);
    $mainHeaderImageTarget = $mainHeaderImageDirectory . $mainHeaderImageName;
    move_uploaded_file($_FILES["main_header_image"]["tmp_name"], $mainHeaderImageTarget);

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

    // Insert data into the portfolio table
    $sql = "INSERT INTO portfolio_t (title, category, image_src, header_image, portfolio_text, category_text, platform, main_header_image, download_link) VALUES ('$title', '$category', '$portfolioImageTarget', '$headerImageTarget', '$text', '$categoryText', '$platform', '$mainHeaderImageTarget', '$download_link')";

    if (mysqli_query($conn, $sql)) {
        // Get the last inserted portfolio_id
        $portfolioId = mysqli_insert_id($conn);

        // Handle multiple images upload
        $projectImages = $_FILES["multipleimages"]["name"];
        $tempNameArray = $_FILES["multipleimages"]["tmp_name"];

        foreach ($projectImages as $key => $projectImage) {
            $projectImageName = basename($projectImage);
            $projectImageTarget = 'upload/portfolio_details/' . $projectImageName;
            move_uploaded_file($tempNameArray[$key], $projectImageTarget);

            // Insert data into the portfolio_images table
            $sqlImages = "INSERT INTO portfolio_images_t (portfolio_id, image_src) VALUES ('$portfolioId', '$projectImageTarget')";
            mysqli_query($conn, $sqlImages);
        }
          // Deletion successful, redirect to the confirmation page
          header('Cache-Control: no-cache, must-revalidate');
          $_SESSION['successMessage'] = "Successfully inserted.";
          header('Location: projects.php');
          exit;
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    // Close the connection
    mysqli_close($conn);
}
?>
