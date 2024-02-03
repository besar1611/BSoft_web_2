<?php require_once('auth.php'); ?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Projetcs</title>
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

      <!-- Add this code for the toast message -->
      <div class="toast-container position-fixed top-8 end-0 p-3" style="z-index: 1050">
        <?php
        if (isset($_SESSION['successMessage'])) {
          $successMessage = $_SESSION['successMessage'];

          echo '<div class="toast-container position-fixed top-8 end-0 p-3" style="z-index: 1050">
            <div id="toastSuccess" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
              <div class="toast-header bg-success text-white">
                <i class="bx bx-check-circle me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
              <div class="toast-body">
                ' . $successMessage . '
              </div>
            </div>
          </div>';
          echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
              var toast = document.getElementById("toastSuccess");
              var toastInstance = new bootstrap.Toast(toast);
              toastInstance.show();
              setTimeout(function() {
                toastInstance.hide();
              }, 3000);
            });
          </script>';

          unset($_SESSION['successMessage']);
        } elseif (isset($_SESSION['errorMessage'])) {
          $errorMessage = $_SESSION['errorMessage'];

          echo '<div class="toast-container position-fixed top-8 end-0 p-3" style="z-index: 1050">
            <div id="toastError" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
              <div class="toast-header bg-danger text-white">
                <i class="bx bx-x-circle me-2"></i>
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
              <div class="toast-body">
                ' . $errorMessage . '
              </div>
            </div>
          </div>';
          echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
              var toast = document.getElementById("toastError");
              var toastInstance = new bootstrap.Toast(toast);
              toastInstance.show();
              setTimeout(function() {
                toastInstance.hide();
              }, 3000);
            });
          </script>';

          unset($_SESSION['errorMessage']);
        }
        ?>
      </div>



      <!-- Page header -->
      <div class="page-header d-print-none">
        <div class="container-xl">
          <div class="row g-2 align-items-center">
            <div class="col">
              <h2 class="page-title">
                List of Projects
              </h2>
            </div>
          </div>
        </div>
      </div>


      <!-- Page body -->
      <?php
      require_once('../mysqli_connect.php');

      // Pagination parameters
      $recordsPerPage = 8;
      $page = isset($_GET['page']) ? $_GET['page'] : 1;
      $offset = ($page - 1) * $recordsPerPage;

      // Fetch data from MySQL with pagination
      $sql = "SELECT * FROM portfolio_t LIMIT $offset, $recordsPerPage";
      $result = $conn->query($sql);

      // Calculate total number of records
      $totalRecordsSql = "SELECT COUNT(*) as count FROM portfolio_t";
      $totalRecordsResult = $conn->query($totalRecordsSql);
      $totalRecords = $totalRecordsResult->fetch_assoc()['count'];

      // Calculate total number of pages
      $totalPages = ceil($totalRecords / $recordsPerPage);
      ?>

      <div class="page-body">
        <div class="container-xl">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Projects</h3>
            </div>
            <!-- <div class="card-body border-bottom py-3">
              <div class="d-flex">
                <div class="ms-auto text-muted">
                  Search:
                  <div class="ms-2 d-inline-block">
                    <input type="text" class="form-control form-control-sm" aria-label="Search invoice">
                  </div>
                </div>
              </div>
            </div> -->
            <div class="table-responsive">
              <table class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Platform</th>
                    <th>Image</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Loop through the data and populate the table rows
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                      <td><a href='../portfolio-details.php?id=" . $row['id'] . "' class='text-reset' tabindex='-1' target='_blank'>" . $row['title'] . "</a></td>
                      <td>" . $row['category_text'] . "</td>
                      <td>" . $row['platform'] . "</td>
                      <td><img src='" . $row['header_image'] . "' alt='Image' style='max-width: 75px; max-height: 75px;'></td>
                      <td class='text-start'>
                          <span class='dropdown'>
                              <button class='btn dropdown-toggle align-text-top' data-bs-boundary='viewport' data-bs-toggle='dropdown' data-bs-placement='left'>Actions</button>
                              <div class='dropdown-menu dropdown-menu-start'>            
                                  <a class='dropdown-item' href='" . $row['download_link'] . "'>
                                      Download
                                  </a>       
                                  <a class='dropdown-item' href='edit_project.php?id=" . $row['id'] . "'>
                                      Edit
                                  </a>
                                  <a class='dropdown-item' href='delete_project.php?id=" . $row['id'] . "'>
                                      Delete
                                  </a>
                              </div>
                          </span>
                      </td>
                    </tr>";
                  }
                  ?>
                </tbody>


              </table>
            </div>
            <div class="card-footer d-flex align-items-center">
              <p class="m-0 text-muted">Showing <span>
                  <?= $offset + 1 ?>
                </span> to <span>
                  <?= min($offset + $recordsPerPage, $totalRecords) ?>
                </span> of <span>
                  <?= $totalRecords ?>
                </span> entries</p>
              <ul class="pagination m-0 ms-auto">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                  <li class="page-item<?= $i == $page ? ' active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>">
                      <?= $i ?>
                    </a>
                  </li>
                <?php endfor; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <?php
      // Close the database connection
      $conn->close();
      ?>
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
  <script src="./dist/libs/list.js/dist/list.min.js?1684106062" defer></script>
  <!-- Tabler Core -->
  <script src="./dist/js/tabler.min.js?1684106062" defer></script>
  <script src="./dist/js/demo.min.js?1684106062" defer></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const list = new List('table-default', {
        sortClass: 'table-sort',
        listClass: 'table-tbody',
        valueNames: ['sort-name', 'sort-type', 'sort-city', 'sort-score',
          { attr: 'data-date', name: 'sort-date' },
          { attr: 'data-progress', name: 'sort-progress' },
          'sort-quantity'
        ]
      });
    })
  </script>

</body>

</html>