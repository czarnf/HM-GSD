<?php
require_once("vendor/autoload.php");
$page = "dashboard";

use app\controller\AppointmentController;
use app\controller\PatientController;
use app\controller\StaffController;
use app\controller\TeamController;
use app\controller\WardController;

$path = "/assignment/hms";
session_start();

$controller = new StaffController();

$staffCount = 0;
$doctorCount = 0;
$patientCount = 0;
$appointmentCount = 0;
$wardCount = 0;
$teamCount = 0;
$teamPatientCount = 0;
$teamStaffCount = 0;


$appointmentList = [];
$list = [];
$patientList = [];



if (isset($_SESSION["isAuth"]) && isset($_SESSION["user"]) && isset($_SESSION["role"])) {
  // var_dump();
  $controller = new StaffController();
  $wardController =  new WardController();
  $teamController = new TeamController();
  $patientController = new PatientController();
  $appointmentController = new AppointmentController();

  $staffCount = $controller->getStaffCounts();
  $doctorCount = $controller->getDoctorsCounts();
  $teamCount = $teamController->getTeamCounts();
  $wardCount = $wardController->getWardCounts();
  $patientCount = $patientController->getPatientCounts();

  $resP = json_decode($patientController->fetchPatientByTeam((int) $_SESSION["user"]["staff_team_id"]), true);
  $resT = json_decode($controller->fetchStaffByTeam((int) $_SESSION["user"]["staff_team_id"]), true);



  $teamPatientCount = count($resP["message"]);
  $teamStaffCount = count($resT["message"]);
  $appointmentCount = $appointmentController->getAppointmentCounts();

  $response = json_decode($appointmentController->fetchAllAppointment(), true);



  if (count($response["message"]) > 0) {
    $list = $response["message"];
  }
} else {
  header("location: $path/login.php");
}








if (isset($_SESSION["user"]) && isset($_SESSION["user"]["staff_id"]) && strlen(trim($_SESSION["user"]["staff_id"])) > 0) {
  $id = (int) $_SESSION["user"]["staff_id"];
  $controller = new PatientController();
  $response = json_decode($controller->fetchPatientByAssignedDoctor($id), true);
  if (count($response["message"]) > 0) {
    $patientList = $response["message"];
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
  <link rel="stylesheet" href="datatable/dataTables.bootstrap4.min.css" />

  <!-- jvectormap -->
  <link rel="stylesheet" href="css/jquery-jvectormap.css" />

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
            <h3 class="block-title"><?php echo ucfirst($_SESSION["user"]["staff_role"]); ?> Statistics</h3>
          </div>
          <div class="col-md-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="index.html">
                  <span class="ti-home"></span>
                </a>
              </li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
      <!-- /Page Title -->
      <!-- /Breadcrumb -->
      <!-- Main Content -->
      <div class="container home">
        <div class="row">
          <!-- Widget Item -->
          <?php
          if (isset($_SESSION["user"]["staff_role"]) && ($_SESSION["user"]["staff_role"] == "doctor" || $_SESSION["user"]["staff_role"] == "consultant")) {
          ?>
            <div class="col-md-4">
              <div class="widget-area proclinic-box-shadow color-yellow" style="display:flex; align-items: center;">
                <div class="widget-left">
                  <span class="ti-wheelchair"></span>
                </div>
                <div class="widget-right">
                  <h4 class="wiget-title">My Patients</h4>
                  <span class="numeric color-yellow"><?php echo number_format(count($patientList)); ?></span>
                </div>
              </div>
            </div>
          <?php
          }
          if (isset($_SESSION["user"]["staff_role"]) && $_SESSION["user"]["staff_role"] == "consultant") {
          ?>
            <div class="col-md-4">
              <div class="widget-area proclinic-box-shadow color-red" style="display:flex; align-items: center;">
                <div class="widget-left">
                  <span class="ti-wheelchair"></span>
                </div>
                <div class="widget-right">
                  <h4 class="wiget-title">Team Patients</h4>
                  <span class="numeric color-red"><?php echo number_format($teamPatientCount); ?></span>
                </div>
              </div>
            </div>
          <?php
          }
          if (isset($_SESSION["user"]["staff_role"]) && ($_SESSION["user"]["staff_role"] == "admin" || $_SESSION["user"]["staff_role"] == "Front Desk")) {
          ?>
            <div class="col-md-4">
              <div class="widget-area proclinic-box-shadow color-red" style="display:flex; align-items: center;">
                <div class="widget-left">
                  <span class="ti-wheelchair"></span>
                </div>
                <div class="widget-right">
                  <h4 class="wiget-title">Total Admitted Patients</h4>
                  <span class="numeric color-red"><?php echo number_format($patientCount); ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="widget-area proclinic-box-shadow color-blue" style="display:flex; align-items: center;">
                <div class="widget-left">
                  <span class="ti-pin"></span>
                </div>
                <div class="widget-right">
                  <h4 class="wiget-title">Total Doctors</h4>
                  <span class="numeric color-blue"><?php echo number_format($doctorCount); ?></span>
                </div>
              </div>
            </div>

          <?php }
          if (isset($_SESSION["user"]["staff_role"]) && $_SESSION["user"]["staff_role"] == "admin") {
          ?>
            <div class="col-md-4">
              <div class="widget-area proclinic-box-shadow color-green" style="display:flex; align-items: center;">
                <div class="widget-left">
                  <span class="ti-user"></span>
                </div>
                <div class="widget-right">
                  <h4 class="wiget-title">Total Staffs</h4>
                  <span class="numeric color-green"><?php echo number_format($staffCount); ?></span>
                </div>
              </div>
            </div>

            <!-- /Widget Item -->
          <?php
          }
          if (isset($_SESSION["user"]["staff_role"]) && $_SESSION["user"]["staff_role"] == "consultant") {
          ?>
            <div class="col-md-4">
              <div class="widget-area proclinic-box-shadow color-blue" style="display:flex; align-items: center;">
                <div class="widget-left">
                  <span class="ti-pin"></span>
                </div>
                <div class="widget-right">
                  <h4 class="wiget-title">Total Team Members</h4>
                  <span class="numeric color-blue"><?php echo number_format($teamStaffCount); ?></span>
                </div>
              </div>
            </div>
            <!-- /Widget Item -->
          <?php
          }
          if (isset($_SESSION["user"]["staff_role"]) && ($_SESSION["user"]["staff_role"] == "admin" || $_SESSION["user"]["staff_role"] == "Front Desk")) {
          ?>
            <div class="col-md-4">
              <div class="widget-area proclinic-box-shadow color-yellow" style="display:flex; align-items: center;">
                <div class="widget-left">
                  <span class="ti-key"></span>
                </div>
                <div class="widget-right">
                  <h4 class="wiget-title">Total Wards</h4>
                  <span class="numeric color-yellow"><?php echo number_format($wardCount); ?></span>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="widget-area proclinic-box-shadow color-red" style="display:flex; align-items: center;">
                <div class="widget-left">
                  <span class="ti-files"></span>
                </div>
                <div class="widget-right">
                  <h4 class="wiget-title">Total Teams</h4>
                  <span class="numeric color-red"><?php echo number_format($teamCount); ?></span>
                </div>
              </div>
            </div>

          <?php }
          if (isset($_SESSION["user"]["staff_role"]) && $_SESSION["user"]["staff_role"] != "doctor") {
          ?>

            <div class="col-md-4">
              <div class="widget-area proclinic-box-shadow color-green" style="display:flex; align-items: center;">
                <div class="widget-left">
                  <span class="ti-envelope"></span>
                </div>
                <div class="widget-right">
                  <h4 class="wiget-title">Total Appointment</h4>
                  <span class="numeric color-green"><?php echo number_format($appointmentCount); ?></span>
                </div>
              </div>
            </div>
          <?php } ?>
          <!-- /Widget Item -->
        </div>

        <div class="row">
          <!-- Widget Item -->
          <?php
          if (isset($_SESSION["user"]["staff_role"]) && ($_SESSION["user"]["staff_role"] == "Front Desk" || $_SESSION["user"]["staff_role"] == "admin")) {
          ?>
            <div class="col-md-12">
              <div class="widget-area-2 proclinic-box-shadow">
                <h3 class="widget-title">Appointments List</h3>
                <div class="table-responsive mb-3">
                  <table id="tableId" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Sn</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Email address</th>
                        <th>Phone number</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>...</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if (count($list)  > 0) {
                        $count = 1;
                        foreach ($list as $key => $appointment) {
                      ?>
                          <tr>
                            <td><?php echo $count++; ?></td>
                            <td><?php echo $appointment["appointment_name"]; ?></td>
                            <td> <a href="./team-members.php?id=<?php echo $appointment["team_id"]; ?>" style="text-decoration: underline; color:green;"><?php echo $appointment["team_desc"]; ?></a> </td>
                            <td><?php echo $appointment["appointment_email"]; ?></td>
                            <td><?php echo $appointment["appointment_phone"]; ?></td>
                            <td><?php echo $appointment["appointment_date"]; ?></td>
                            <td><?php echo $appointment["appointment_time"]; ?></td>
                            <td>
                              <a href="./about-appointment.php?slug=<?php echo $appointment["appointment_slug"]; ?>" class="badge badge-success">View More</a>
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
          <?php }
          if (isset($_SESSION["user"]["staff_role"]) && ($_SESSION["user"]["staff_role"] == "doctor" || $_SESSION["user"]["staff_role"] == "consultant")) {
          ?>

            <div class="col-md-12">
              <div class="widget-area-2 proclinic-box-shadow">
                <h3 class="widget-title">My Patients List</h3>
                <div class="table-responsive mb-3">
                  <table id="tableId" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Number</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Bed No</th>
                        <th>...</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if (count($patientList)  > 0) {
                        $count = 1;
                        foreach ($patientList as $key => $patient) {
                      ?>
                          <tr>
                            <td><?php echo $count++; ?></td>
                            <td><?php echo $patient["patient_name"]; ?></td>
                            <td><?php echo $patient["patient_number"]; ?></td>
                            <td><?php echo $patient["patient_age"]; ?></td>
                            <td><?php echo $patient["patient_gender"]; ?></td>
                            <td><?php echo $patient["patient_bed_no"]; ?></td>
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
          <?php } ?>
          <!-- /Widget Item -->
        </div>
      </div>
      <!-- /Main Content -->
      <!--Copy Rights-->
      <div class="container" style="margin-top: 100px;">
        <div class="d-sm-flex justify-content-center">
          <span class="text-muted text-center d-block d-sm-inline-block">Copyright © 2024
            <a href="./" target="_blank">HMS ~ Assignment</a>. All rights reserved.</span>
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
  <!-- morris charts -->
  <script src="charts/js/raphael-min.js"></script>
  <script src="charts/js/morris.min.js"></script>
  <script src="js/custom-morris.js"></script>

  <!-- Datatable  -->
  <script src="datatable/jquery.dataTables.min.js"></script>
  <script src="datatable/dataTables.bootstrap4.min.js"></script>

  <!-- Custom Script-->
  <script src="js/custom.js"></script>
  <script src="js/custom-datatables.js"></script>
</body>

</html>