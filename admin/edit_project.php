<?php require_once('auth.php'); ?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Edit Projetcs</title>
  <!-- CSS files -->
  <link href="./dist/css/tabler.min.css?1684106062" rel="stylesheet" />
  <link href="./dist/css/tabler-flags.min.css?1684106062" rel="stylesheet" />
  <link href="./dist/css/tabler-payments.min.css?1684106062" rel="stylesheet" />
  <link href="./dist/css/tabler-vendors.min.css?1684106062" rel="stylesheet" />
  <link href="./dist/css/demo.min.css?1684106062" rel="stylesheet" />
  <style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
      --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }

    body {
      font-feature-settings: "cv03", "cv04", "cv11";
    }
  </style>
</head>

<body>
  <script src="./dist/js/demo-theme.min.js?1684106062"></script>
  <div class="page">
    <!-- Navbar -->
    <?php include 'header.php'; ?>
    <div class="page-wrapper">
      <!-- Page header -->
      <div class="page-header d-print-none">
        <div class="container-xl">
          <div class="row g-2 align-items-center">
            <div class="col">
              <h2 class="page-title">
                Edit project
              </h2>
            </div>
          </div>
        </div>
      </div>
      <!-- Page body -->
      <div class="page-body">
        <div class="container-xl">
          <div class="row row-cards">
            <div class="col-12">
              <?php
              require_once('auth.php');
              require_once('../mysqli_connect.php');

              // Check the database connection
              if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
              }

              // Retrieve the project ID from the URL
              $projectId = isset($_GET['id']) ? $_GET['id'] : null;

              // Validate the project ID (add your validation logic if needed)
              if (!$projectId || !is_numeric($projectId)) {
                die("Invalid project ID");
              }

              // Fetch data from the database based on the project ID
              $sql = "SELECT * FROM portfolio_t WHERE id = $projectId";
              $result = mysqli_query($conn, $sql);

              if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);

                // Fetch values from the database
                $title = $row['title'];
                $category = $row['category'];
                $portfolioImage = $row['image_src'];
                $headerImage = $row['header_image'];
                $mainHeaderImage = $row['main_header_image'];
                $text = $row['portfolio_text'];
                $platform = $row['platform'];
                $download_link = $row['download_link'];

                // Close the result set
                mysqli_free_result($result);
              } else {
                echo "Error fetching project details: " . mysqli_error($conn);
              }

              // Close the connection
              mysqli_close($conn);
              ?>

              <form action="update_project.php?id=<?php echo $projectId; ?>" method="POST" class="card" enctype="multipart/form-data">
                <div class="card-header">
                  <h4 class="card-title">Project</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-xl-12">
                      <div class="row">
                        <div class="col-md-6 col-xl-12">
                          <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="Title"
                              value="<?php echo $title; ?>">
                          </div>
                          <div class="mb-3">
                            <div class="form-label">Category</div>
                            <select class="form-select" name="category" id="category">
                              <option value="web_design" <?php echo ($category === 'web_design') ? 'selected' : ''; ?>>Web
                                Development</option>
                              <option value="desktop_apps" <?php echo ($category === 'desktop_apps') ? 'selected' : ''; ?>>Desktop App</option>
                              <option value="mobile_apps" <?php echo ($category === 'mobile_apps') ? 'selected' : ''; ?>>
                                Mobile App</option>
                              <option value="logo_design" <?php echo ($category === 'logo_design') ? 'selected' : ''; ?>>
                                Logo Design</option>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Text <span id="charCount"
                                class="form-label-description">0/2000</span></label>
                            <textarea class="form-control" name="example-textarea-input" id="exampleTextarea" rows="6"
                              placeholder="Content.." maxlength="2000"><?php echo $text; ?></textarea>
                          </div>
                          <div class="mb-3">
                            <div class="form-label">Platform</div>
                            <select class="form-select" name="platform" id="platform">
                              <option value="Web" <?php echo ($platform === 'Web') ? 'selected' : ''; ?>>Web</option>
                              <option value="Windows" <?php echo ($platform === 'Windows') ? 'selected' : ''; ?>>Windows
                              </option>
                              <option value="iOS & Android" <?php echo ($platform === 'iOS & Android') ? 'selected' : ''; ?>>iOS & Android</option>
                              <option value="Design" <?php echo ($platform === 'Design') ? 'selected' : ''; ?>>Design
                              </option>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Download Link</label>
                            <input type="text" class="form-control" name="download_link" id="download_link" placeholder="Download Link"
                              value="<?php echo $download_link; ?>">
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer text-end">
                  <div class="d-flex">
                    <a href="./projects.php" class="btn btn-link">Cancel</a>
                    <button type="submit" class="btn btn-primary ms-auto">Save</button>
                  </div>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
      <footer class="footer footer-transparent d-print-none">
        <div class="container-xl">
          <div class="row text-center align-items-center flex-row-reverse">
            <div class="col-12 col-lg-auto mt-3 mt-lg-0">
              <ul class="list-inline list-inline-dots mb-0">
                <li class="list-inline-item">
                  Copyright &copy;
                  <script>document.write(new Date().getFullYear())</script>
                  <a href="." class="link-secondary">BSoft</a>.
                  All rights reserved.
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <!-- Libs JS -->
  <script src="./dist/libs/nouislider/dist/nouislider.min.js?1684106062" defer></script>
  <script src="./dist/libs/litepicker/dist/litepicker.js?1684106062" defer></script>
  <script src="./dist/libs/tom-select/dist/js/tom-select.base.min.js?1684106062" defer></script>
  <!-- Tabler Core -->
  <script src="./dist/js/tabler.min.js?1684106062" defer></script>
  <script src="./dist/js/demo.min.js?1684106062" defer></script>

  <style>
    #imagePreview {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    .image-container {
      text-align: center;
      position: relative;
    }

    .preview-image {
      width: 100px;
      /* Set the desired width */
      height: 100px;
      /* Set the desired height */
      object-fit: cover;
      border: 1px solid #ccc;
      padding: 5px;
    }

    .image-name {
      position: absolute;
      bottom: -20px;
      /* Adjust this value to move the text more down */
      left: 50%;
      transform: translateX(-50%);
      margin-top: 5px;
      font-size: 12px;
    }

    .delete-icon {
      position: absolute;
      top: 5px;
      right: 5px;
      cursor: pointer;
      color: red;
      font-size: 16px;
    }
  </style>


  <script>
    $(document).ready(function () {
      $("#inputImage").fileinput({
        theme: 'fa', // You can change the theme based on your preference
        showUpload: false, // Do not show the upload button
        showRemove: false, // Do not show the remove button
        showClose: false, // Do not show the close button
        initialPreviewAsData: true, // Treat initialPreview as raw HTML content
        allowedFileExtensions: ['jpg', 'jpeg'], // Allowed file extensions
        browseClass: 'btn btn-primary', // Style for the browse button
        browseLabel: 'Browse', // Label for the browse button
        browseIcon: '<i class="fas fa-image"></i>', // Icon for the browse button
        removeClass: 'btn btn-danger', // Style for the remove button
        removeLabel: 'Remove', // Label for the remove button
        removeIcon: '<i class="fas fa-trash-alt"></i>', // Icon for the remove button
        // Add any other options you need
      });
    });
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      var textarea = document.getElementById("exampleTextarea");
      var charCountSpan = document.getElementById("charCount");

      // Initial update on page load
      updateCharCount();

      // Update character count on input
      textarea.addEventListener("input", updateCharCount);

      function updateCharCount() {
        var currentCount = textarea.value.length;
        var maxCount = textarea.getAttribute("maxlength");
        charCountSpan.textContent = currentCount + "/" + maxCount;
      }
    });
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      var imageInput = document.getElementById("multipleimages"); // Update the id here
      var imagePreview = document.getElementById("imagePreview");
      var batchId = generateId(); // Generate a single ID for the batch

      // Update image preview on file selection
      imageInput.addEventListener("change", function () {
        // Clear previous preview
        imagePreview.innerHTML = "";

        // Iterate through selected files
        for (var i = 0; i < imageInput.files.length; i++) {
          var file = imageInput.files[i];

          // Create a new image container
          var imageContainer = document.createElement("div");
          imageContainer.className = "image-container";

          // Create a new image element
          var image = document.createElement("img");
          image.className = "preview-image";
          image.src = URL.createObjectURL(file);

          // Create a new span for the image name
          var imageNameSpan = document.createElement("span");
          imageNameSpan.className = "image-name";
          imageNameSpan.textContent = "project" + batchId + "_" + (i + 1);

          // Create a delete icon
          var deleteIcon = document.createElement("span");
          deleteIcon.className = "delete-icon";
          deleteIcon.innerHTML = "&#x2716;"; // Unicode for the 'X' symbol
          deleteIcon.addEventListener("click", createDeleteHandler(imageContainer));

          // Append elements to the image container
          imageContainer.appendChild(image);
          imageContainer.appendChild(imageNameSpan);
          imageContainer.appendChild(deleteIcon);

          // Append the image container to the preview container
          imagePreview.appendChild(imageContainer);
        }

        // Update the file input label with the number of uploaded images
        updateFileInputLabel(imageInput.files.length);
      });

      // Function to generate a unique ID (replace with your logic)
      function generateId() {
        return Math.floor(Math.random() * 1000);
      }

      // Closure to capture the correct values of imageContainer and i
      function createDeleteHandler(container) {
        return function () {
          container.remove(); // Remove the image container on delete icon click

          // Update the file input label after deletion
          updateFileInputLabel(imageInput.files.length);
        };
      }

      // Function to update the file input label
      function updateFileInputLabel(count) {
        var label = "Project Images";
        if (count > 0) {
          label += " (" + count + ")";
        }
        document.querySelector(".form-label[for='imageInput']").textContent = label;
      }
    });
  </script>

</body>

</html>