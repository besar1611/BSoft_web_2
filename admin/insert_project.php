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

    $targetDirectory = "upload/projects/";

    if (!file_exists($targetDirectory)) {
        mkdir($targetDirectory, 0777, true);
    }

    $title = sanitizeInput($_POST['title']);
    $category = sanitizeInput($_POST['category']);
    $clientName = sanitizeInput($_POST['clientname']);
    $address = sanitizeInput($_POST['address']);
    $area = sanitizeInput($_POST['area']);
    $headerText = sanitizeInput($_POST['headertext']);
    $textContent = sanitizeInput($_POST['example-textarea-input']);

    // Handle header image
    if (!empty($_FILES['header_image']['name'])) {
        $originalFileName = $_FILES['header_image']['name'];
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

        // Generate a unique file name
        $uniqueFileName = 'header_image_' . time() . '_' . rand(1000, 9999) . '.' . $fileExtension;

        $headerImagePath = uploadFile($_FILES['header_image']['tmp_name'], $targetDirectory, $uniqueFileName);

        if ($headerImagePath !== false) {
            // Insert into projects_t table
            $sql = "INSERT INTO projects_t (title, category, image_path, client_name, address, area, height_text, text) 
                    VALUES ('$title', '$category', '$headerImagePath', '$clientName', '$address', '$area', '$headerText', '$textContent')";

            if ($conn->query($sql) === TRUE) {
                $project_id = $conn->insert_id;

                // Handle multiple images
                if (!empty($_FILES['multipleimages']['name'][0])) {
                    foreach ($_FILES['multipleimages']['name'] as $key => $value) {
                        $imageCaption = sanitizeInput($value);

                        // Generate a unique file name based on project ID and count
                        $uniqueFileName = 'image_' . $project_id . '_' . ($key + 1) . '.' . pathinfo($value, PATHINFO_EXTENSION);

                        $imagePath = uploadFile($_FILES['multipleimages']['tmp_name'][$key], $targetDirectory, $uniqueFileName);

                        if ($imagePath !== false) {
                            // Insert into images_t table
                            $insertImageSql = "INSERT INTO images_t (project_id, image_path, caption) VALUES ('$project_id', '$imagePath', '$imageCaption')";

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
            echo "Error uploading header image: " . $_FILES['header_image']['error'];
        }
    }

    $conn->close();
}
?>