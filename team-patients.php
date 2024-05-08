<?php
require_once("vendor/autoload.php");

use app\controller\PatientController;
use app\controller\StaffController;

$page = "team";
$path = "/assignment/hms";
session_start();
if (isset($_SESSION["isAuth"]) && isset($_SESSION["user"]) && isset($_SESSION["role"])) {
  // var_dump($_SESSION);
} else {
  header("location: $path/login.php");
}



$list = [];


if (isset($_GET["id"]) && strlen(trim($_GET["id"])) > 0) {
  $id = (int) $_GET["id"];
  $controller = new PatientController();

  $response = json_decode($controller->fetchPatientByTeam($id), true);

  if (count($response["message"]) > 0) {
    $list = $response["message"];
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

      <!-- Page Title -->
      <div class="container mt-0">
        <div class="row breadcrumb-bar">
          <div class="col-md-6">
            <h3 class="block-title"><?php echo count($list) > 0 ? $list[0]["team_name"] . " Patient List" : "Unidentified Patient List"; ?></h3>
          </div>
          <div class="col-md-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="index.html">
                  <span class="ti-home"></span>
                </a>
              </li>
              <li class="breadcrumb-item active">All Patients</li>
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
              <div class="table-responsive mb-3">
                <table id="tableId" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Card Number</th>
                      <th>Age</th>
                      <th>Gender</th>
                      <th>Ward</th>
                      <th>Bed No</th>
                      <th>Team</th>
                      <th>...</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if (count($list)  > 0) {
                      $count = 1;
                      foreach ($list as $key => $patient) {
                    ?>
                        <tr>
                          <td><?php echo $count++; ?></td>
                          <td><?php echo $patient["patient_name"]; ?></td>
                          <td><?php echo $patient["patient_number"]; ?></td>
                          <td><?php echo $patient["patient_age"]; ?></td>
                          <td><?php echo $patient["patient_gender"]; ?></td>
                          <td><?php echo $patient["ward_name"]; ?></td>
                          <td><?php echo $patient["patient_bed_no"]; ?></td>
                          <td><?php echo $patient["team_name"]; ?></td>
                          <td>
                            <a href="./about-patient.php?id=<?php echo $patient["patient_number"]; ?>">
                              <span class="ti-arrow-top-right text-success"></span>
                            </a>
                          </td>
                        </tr>
                    <?php
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
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
  <script src="js/custom-datatables.js"></script>
</body>

</html>