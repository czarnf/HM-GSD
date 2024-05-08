<?php

require_once("vendor/autoload.php");

use app\controller\WardController;

$page = "ward";
$path = "/assignment/hms";
session_start();
if (isset($_SESSION["isAuth"]) && isset($_SESSION["user"]) && isset($_SESSION["role"])) {
  // var_dump($_SESSION);
} else {
  header("location: $path/login.php");
}




if (isset($_POST["btnWard"])) {
  $name = $_POST["name"];
  $bedno = $_POST["bedno"];
  $type = $_POST["type"];
  $gender = $_POST["gender"];

  $controller = new WardController();

  $payload = [
    "ward_name" => $name,
    "ward_total_bed" => $bedno,
    "ward_type" => $type,
    "ward_gender" => $gender,
  ];

  $response = json_decode($controller->addWard($payload), true);

  if (is_array($response)) {
    echo "<script> alert('" . $response["message"] . "'); </script>";
  } else {
    var_dump($response);
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>HMS - Assignment</title>
  <!-- Fav  Icon Link -->
  <link rel="shortcut icon" type="image/png" href="images/fav.png" />
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <!-- themify icons CSS -->
  <link rel="stylesheet" href="css/themify-icons.css" />
  <!-- Animations CSS -->
  <link rel="stylesheet" href="css/animate.css" />
  <!-- Main CSS -->
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="css/green.css" id="style_theme" />
  <link rel="stylesheet" href="css/responsive.css" />
  <!-- morris charts -->
  <link rel="stylesheet" href="charts/css/morris.css" />
  <!-- jvectormap -->
  <link rel="stylesheet" href="css/jquery-jvectormap.css" />
  <link rel="stylesheet" href="datatable/dataTables.bootstrap4.min.css" />

  <script src="js/modernizr.min.js"></script>
</head>

<body>
  <!-- Pre Loader -->
  <div class="loading">
    <div class="spinner">
      <div class="double-bounce1"></div>
      <div class="double-bounce2"></div>
    </div>
  </div>
  <!--/Pre Loader -->
  <div class="wrapper">
    <!-- Page Content -->
    <div id="content">
      <?php include_once("./includes/header.php") ?>

      <!-- Breadcrumb -->
      <!-- Page Title -->
      <div class="container mt-0">
        <div class="row breadcrumb-bar">
          <div class="col-md-6">
            <h3 class="block-title">Create Ward</h3>
          </div>
          <div class="col-md-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="index.html">
                  <span class="ti-home"></span>
                </a>
              </li>
              <li class="breadcrumb-item active"> Team</li>
            </ol>
          </div>
        </div>
      </div>
      <!-- /Page Title -->

      <!-- /Breadcrumb -->
      <!-- Main Content -->
      <div class="container">
        <div class="row">
          <!-- Widget Item -->
          <div class="col-md-12">
            <div class="widget-area-2 proclinic-box-shadow">
              <form method="post">
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="name">Ward Name</label>
                    <input type="text" name="name" required class="form-control" placeholder="Team name" id="name" />
                  </div>
                  <div class="form-group col-md-6">
                    <label for="bed">Total Bed</label>
                    <input type="number" name="bedno" required class="form-control" placeholder="Team name" id="bed" />
                  </div>
                  <div class="form-group col-md-6">
                    <label for="type">Ward Type</label>
                    <input type="text" class="form-control" required placeholder="Ward Type" name="type" id="type" />
                  </div>

                  <div class="form-group col-md-6">
                    <label for="gender">For what gender?</label>
                    <select class="form-control" required id="gender" name="gender">
                      <option value="male">Male</option>
                      <option value="female">Female</option>
                      <option value="both">Both</option>
                    </select>
                  </div>

                  <div class="form-group col-12 mb-3">
                    <button type="submit" name="btnWard" class="btn btn-primary btn-lg">
                      Create Ward
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!-- /Widget Item -->
        </div>
      </div>
      <!-- /Main Content -->
      <!--Copy Rights-->
      <div class="container">
        <div class="d-sm-flex justify-content-center">
          <span class="text-muted text-center d-block d-sm-inline-block">Copyright © 2024
            <a href="./" target="_blank">HMS - Assignment</a>. All rights
            reserved.</span>
        </div>
      </div>
      <!-- /Copy Rights-->
    </div>
    <!-- /Page Content -->
  </div>
  <!-- Back to Top -->
  <a id="back-to-top" href="#" class="back-to-top">
    <span class="ti-angle-up"></span>
  </a>
  <!-- /Back to Top -->
  <!-- Jquery Library-->
  <script src="js/jquery-3.2.1.min.js"></script>
  <!-- Popper Library-->
  <script src="js/popper.min.js"></script>
  <!-- Bootstrap Library-->
  <script src="js/bootstrap.min.js"></script>

  <!-- Datatable  -->
  <script src="datatable/jquery.dataTables.min.js"></script>
  <script src="datatable/dataTables.bootstrap4.min.js"></script>

  <!-- Custom Script-->
  <script src="js/custom.js"></script>
</body>

</html>