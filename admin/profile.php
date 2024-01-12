<?php require_once('auth.php'); ?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Profile</title>
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
              // Database connection
              require_once('../mysqli_connect.php');

              // Fetch data for specific ID from projects_t table
              $id = $_GET['id']; 
              $query = "SELECT * FROM user_t WHERE id = $id";
              $result = mysqli_query($conn, $query);
              $row = mysqli_fetch_assoc($result);

              $hashedPassword = $row['password'];
              ?>

              <form id="updateForm" method="POST" action="edit_profile.php?id=<?php echo $id; ?>" class="card" enctype="multipart/form-data">
                <div class="card-header">
                  <h4 class="card-title">Project</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-xl-12">
                      <div class="row">
                        <div class="col-md-6 col-xl-12">
                          <div class="mb-3">
                            <label class="form-label">Fullname</label>
                            <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Fullname"
                              value="<?= $row['fullname'] ?>">
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="xusername" id="xusername"
                              placeholder="Username" value="<?= $row['username'] ?>">
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="xpassword" id="xpassword"
                              placeholder="Password" value="">
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" name="email" id="email" placeholder="Email"
                              value="<?= $row['email'] ?>">
                          </div>
                          <!-- Add other input fields with values from the database -->

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer text-end">
                  <div class="d-flex">
                    <a href="./" class="btn btn-link">Cancel</a>
                    <button type="submit" class="btn btn-primary ms-auto">Update</button>
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
      max-width: 100px;
      /* Set the desired maximum width */
      max-height: 100px;
      /* Set the desired maximum height */
      object-fit: cover;
      border: 1px solid #ccc;
      padding: 5px;
    }

    .image-name {
      position: absolute;
      bottom: -20px;
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

      // Update character count on input
      textarea.addEventListener("input", function () {
        var currentCount = textarea.value.length;
        var maxCount = textarea.getAttribute("maxlength");
        charCountSpan.textContent = currentCount + "/" + maxCount;
      });
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