<?php
require_once("vendor/autoload.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use app\controller\AppointmentController;

$page = "appointment";

$path = "/assignment/hms";
session_start();
if (isset($_SESSION["isAuth"]) && isset($_SESSION["user"]) && isset($_SESSION["role"])) {
  // var_dump($_SESSION);
} else {
  header("location: $path/login.php");
}


$controller = new AppointmentController();

if (isset($_GET["slug"]) && strlen(trim($_GET["slug"])) > 10) {
  $slug = $_GET["slug"];

  if (isset($_GET["present"])) {
    $response = json_decode($controller->modifyAppointmentStatus($slug, 1), true);
    if (strlen($response["message"]) > 5) {
      echo "<script> alert('" . $response["message"] . "'); </script>";
    }
  }

  if (isset($_GET["completed"])) {
    $response = json_decode($controller->modifyAppointmentStatus($slug, 2), true);
    if (strlen($response["message"]) > 5) {
      echo "<script> alert('" . $response["message"] . "'); </script>";
    }
  }

  $response = json_decode($controller->fetchAppointmentBySlug($slug), true);
  if (count($response["message"]) > 0) {
    $list = $response["message"];

    if (isset($_GET["delete"])) {
      $response = json_decode($controller->deleteAppointmentBySlug($slug), true);
      if (strlen($response["message"]) > 5) {
        echo "<script> alert('" . $response["message"] . "'); </script>";
        header("refresh:0; url=$path/appointments.php");
      }
    }
  } else {
    header("location: $path/appointments.php");
  }
} else {
  header("location: $path/appointments.php");
}



$list = [];

if (count($response["message"]) > 0) {
  $list = $response["message"];
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
            <h3 class="block-title">Appointment Details</h3>
          </div>
          <div class="col-md-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="index.html">
                  <span class="ti-home"></span>
                </a>
              </li>
              <li class="breadcrumb-item">Appointments</li>
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
              <div class="table-responsive">
                <table class="table table-bordered table-striped">
                  <tbody>
                    <tr>
                      <td><strong>Fullname</strong></td>
                      <td><?php echo $list["appointment_name"]; ?></td>
                    </tr>
                    <tr>
                      <td><strong>Email address</strong></td>
                      <td><?php echo $list["appointment_email"]; ?></td>
                    </tr>
                    <tr>
                      <td><strong>Phone number</strong></td>
                      <td><?php echo $list["appointment_phone"]; ?></td>
                    </tr>
                    <tr>
                      <td><strong>Department</strong></td>
                      <td><?php echo $list["team_desc"]; ?></td>
                    </tr>
                    <tr>
                      <td><strong>Appointment Date</strong></td>
                      <td><?php echo $list["appointment_date"]; ?></td>
                    </tr>
                    <tr>
                      <td><strong>Time Slot </strong></td>
                      <td><?php echo $list["appointment_time"]; ?></td>
                    </tr>
                    <tr>
                      <td><strong>Comment/Problem</strong></td>
                      <td><?php echo $list["appointment_comment"]; ?></td>
                    </tr>
                    <tr>
                      <td><strong>Status</strong></td>
                      <td><?php echo $list["appointment_status"] == 0 ? "Awaiting patient" : "Processing..."; ?></td>
                    </tr>
                  </tbody>
                </table>

                <?php
                if ($list["appointment_status"] == 2 && ($_SESSION["user"]["staff_role"] == "Front Desk" || $_SESSION["user"]["staff_role"] == "admin")) {
                ?>
                  <p class=" text-danger">
                    <strong>Note: </strong>Ensure to admit patient if need be; before you click the clear data button.
                  </p>
                  <a href="./add-patient.php?name=<?php echo $list["appointment_name"]; ?>" class="btn btn-success mb-3">
                    <span class="ti-wheelchair"></span> Admit Patient
                  </a>
                  <a href="./about-appointment.php?slug=<?php echo $_GET["slug"]; ?>&delete=true" class="btn btn-danger mb-3">
                    <span class="ti-check"></span> Appointment Cleared Data
                  </a>
                <?php
                } ?>

                <?php
                if (isset($_SESSION["user"]["staff_role"]) && ($_SESSION["user"]["staff_role"] == "consultant" || $_SESSION["user"]["staff_role"] == "doctor") && $list["appointment_status"] == 1) {
                ?>
                  <a href="./about-appointment.php?slug=<?php echo $_GET["slug"]; ?>&completed=true" class="btn btn-danger mb-3">
                    <span class="ti-check"></span> Appointment Done
                  </a>
                  <!-- status = 2 -->
                <?php
                } ?>

                <?php
                if (isset($_SESSION["user"]["staff_role"]) && $_SESSION["user"]["staff_role"] == "Front Desk" && $list["appointment_status"] == 0) {
                ?>
                  <a href="./about-appointment.php?slug=<?php echo $_GET["slug"]; ?>&present=true" class="btn btn-info mb-3">
                    <span class="ti-check"></span> Mark as Present
                    <!-- status = 1 -->
                  </a>
                <?php
                }
                ?>

                <?php
                if (isset($_SESSION["user"]["staff_role"]) && $_SESSION["user"]["staff_role"] == "Front Desk" && $list["appointment_status"] == 1) {
                ?>
                  <button class="btn btn-default mb-3">
                    <span class="ti-check"></span> Awaiting response from doctor
                    <!-- status = 1 -->
                  </button>
                <?php
                }
                ?>

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
</body>

</html>