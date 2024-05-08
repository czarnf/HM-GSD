<?php

use app\controller\PatientController;
use app\controller\TeamController;
use app\controller\WardController;


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$path = "/assignment/hms";
$page = "patient";

$get_name = "";
require_once("vendor/autoload.php");

session_start();
if (isset($_SESSION["isAuth"]) && isset($_SESSION["user"]) && isset($_SESSION["role"])) {
  // var_dump($_SESSION);
} else {
  header("location: $path/login.php");
}



$controllerTeam = new TeamController();
$controllerWard = new WardController();

$teamResponse = json_decode($controllerTeam->fetchTeams(), true);
$wardResponse = json_decode($controllerWard->fetchWards(), true);

$teamList = [];
$wardList = [];







if (is_array($teamResponse) && isset($teamResponse["message"])) {
  $teamList = $teamResponse["message"];
}


if (is_array($wardResponse) && isset($wardResponse["message"])) {
  $wardList = $wardResponse["message"];
}


function generate_staffId()
{
  $random_numbers = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
  $random_numbers_2 = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
  $username = 'PHMS' . $random_numbers . $random_numbers_2;
  return $username;
}



if (isset($_POST["btnPatient"])) {
  $name = $_POST["name"];
  $id = $_POST["id"];
  $team = (int) $_POST["team"];
  $ward = (int) $_POST["ward"];
  $password = $_POST["password"];
  $gender = $_POST["gender"];
  $bed_no = $_POST["bed_no"];
  $age = $_POST["age"];

  $controller = new PatientController();

  $payload = [
    "patient_name" => $name,
    "patient_team_id" => $team,
    "patient_number" => $id,
    "patient_password" => $password,
    "patient_age" => $age,
    "patient_gender" => $gender,
    "patient_ward_id" => $ward,
    "patient_bed_no" => $bed_no
  ];


  $response = json_decode($controller->addPatient($payload), true);

  if (is_array($response)) {
    echo "<script> alert('" . $response["message"] . "'); </script>";
  } else {
    var_dump($response);
  }
}


if (isset($_GET["name"]) && strlen(trim($_GET["name"])) > 2) {
  $get_name = $_GET["name"];
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
            <h3 class="block-title">Admit a new Patient</h3>
          </div>
          <div class="col-md-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="index.html">
                  <span class="ti-home"></span>
                </a>
              </li>
              <li class="breadcrumb-item">Staffs</li>
              <li class="breadcrumb-item active">Add Staff</li>
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
                    <label for="name">Name</label>
                    <input type="text" class="form-control" value="<?php echo $get_name; ?>" required placeholder="Patient name" id="name" name="name" />
                  </div>
                  <div class="form-group col-md-6">
                    <label for="id">Id Number</label>
                    <input type="text" placeholder="Patient Id" value="<?php echo generate_staffId(); ?>" required class="form-control" id="id" name="id" />
                  </div>
                  <div class="form-group col-md-6">
                    <label for="age">Age</label>
                    <input type="text" placeholder="Patient Age" required class="form-control" id="age" name="age" />
                  </div>
                  <div class="form-group col-md-6">
                    <label for="team">Gender</label>
                    <select class="form-control" id="gender" name="gender" required>
                      <option value="">Select a gender</option>
                      <option value="female">Female</option>
                      <option value="male">Male</option>
                    </select>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="ward">Ward</label>
                    <select class="form-control" id="ward" name="ward" required>
                      <option value="">Select a ward</option>
                      <?php
                      if (count($wardList) > 0) {
                        foreach ($wardList as $key => $ward) {
                      ?>
                          <option value="<?php echo $ward["ward_id"]; ?>"><?php echo ucfirst($ward["ward_name"]); ?></option>
                      <?php
                        }
                      }
                      ?>
                    </select>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="bed_no">Bed Number</label>
                    <input type="number" required placeholder="Bed number" class="form-control" id="bed_no" name="bed_no" />
                  </div>
                  <div class="form-group col-md-6">
                    <label for="team">Team</label>
                    <select class="form-control" id="team" name="team" required>
                      <option value="">Select a team</option>
                      <?php
                      if (count($teamList) > 0) {
                        foreach ($teamList as $key => $team) {
                          if ($team["team_name"] == "administrator") continue;
                      ?>
                          <option value="<?php echo $team["team_id"]; ?>"><?php echo ucfirst($team["team_name"]); ?></option>
                      <?php
                        }
                      }
                      ?>
                    </select>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="password">Password</label>
                    <input type="text" placeholder="Password" value="<?php echo generate_staffId(); ?>" required class="form-control" id="password" name="password" />

                  </div>

                  <div class="form-group col-12 mb-3">
                    <button type="submit" name="btnPatient" class="btn btn-primary btn-lg">
                      Admit Patient
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