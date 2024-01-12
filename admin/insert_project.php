<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('../mysqli_connect.php');

    function sanitizeInput($input)
    {
        global $conn;
        return mysqli_real_escape_string($conn, $input);
    }

    function uploadFile($file, $targetDirectory, $targetFileName)
    {
        $targetFile = $targetDirectory . $targetFileName;

        if (move_uploaded_file($file, $targetFile)) {
            return $targetFile;
        } else {
            return false;
        }
    }

    $targetDirectoryHeader = "upload/portfolio/";
    $targetDirectoryDetails = "upload/portfolio_details/";

    if (!file_exists($targetDirectoryHeader)) {
        mkdir($targetDirectoryHeader, 0777, true);
    }

    if (!file_exists($targetDirectoryDetails)) {
        mkdir($targetDirectoryDetails, 0777, true);
    }

    $title = sanitizeInput($_POST['title']);
    $category = sanitizeInput($_POST['category']);
    $textContent = sanitizeInput($_POST['example-textarea-input']);
    $category_text = sanitizeInput($_POST['category']);
    $platfrom = sanitizeInput($_POST['platform']);
   

    // Handle portfolio image
    if (!empty($_FILES['portfolio_image']['name'])) {
        $originalFileName = $_FILES['portfolio_image']['name'];
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

        // Generate a unique file name
        $uniqueFileName = 'portfolio_image_' . time() . '_' . rand(1000, 9999) . '.' . $fileExtension;

        $portfolioImagePath = uploadFile($_FILES['portfolio_image']['tmp_name'], $targetDirectoryHeader, $uniqueFileName);

        if ($portfolioImagePath !== false) {
            // Insert into projects_t table
            $sql = "INSERT INTO portfolio_t (title, category, image_src, header_image, portfolio_text, category_text, platform) 
                    VALUES ('$title', '$category', '$portfolioImagePath', '$clientName', '$address', '$area', '$headerText', '$textContent')";

            if ($conn->query($sql) === TRUE) {
                $project_id = $conn->insert_id;

                // Handle multiple images
                if (!empty($_FILES['multipleimages']['name'][0])) {
                    foreach ($_FILES['multipleimages']['name'] as $key => $value) {
                        // Generate a unique file name based on project ID and count
                        $uniqueFileName = 'image_' . $project_id . '_' . ($key + 1) . '.' . pathinfo($value, PATHINFO_EXTENSION);

                        $imagePath = uploadFile($_FILES['multipleimages']['tmp_name'][$key], $targetDirectoryDetails, $uniqueFileName);

                        if ($imagePath !== false) {
                            // Insert into images_t table
                            $insertImageSql = "INSERT INTO portfolio_images_t (portfolio_id, img_src) VALUES ('$project_id', '$imagePath')";

                            if ($conn->query($insertImageSql) !== TRUE) {
                                echo "Error inserting image: " . $conn->error;
                            }
                        } else {
                            echo "Error uploading image: " . $_FILES['multipleimages']['error'][$key];
                        }
                    }
                }

                header('Cache-Control: no-cache, must-revalidate');
                $_SESSION['successMessage'] = "Inserted Successfully.";
                header("Location: projects.php");
                exit();
            } else {
                echo "Error inserting project: " . $conn->error;
            }
        } else {
            echo "Error uploading portfolio image: " . $_FILES['portfolio_image']['error'];
        }
    }

    $conn->close();
}
?>
