<?php

use app\controller\StaffController;
use app\controller\TeamController;

$page = "staff";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("vendor/autoload.php");

$path = "/assignment/hms";
session_start();
if (isset($_SESSION["isAuth"]) && isset($_SESSION["user"]) && isset($_SESSION["role"])) {
  // var_dump($_SESSION);
} else {
  header("location: $path/login.php");
}


$controllerTeam = new TeamController();

$teamResponse = json_decode($controllerTeam->fetchTeams(), true);

$teamList = [];

if (is_array($teamResponse) && isset($teamResponse["message"])) {
  $teamList = $teamResponse["message"];
}



if (isset($_POST["btnCreateStaff"])) {
  $name = $_POST["name"];
  $id = $_POST["id"];
  $team = (int) $_POST["team"];
  $password = $_POST["password"];
  $role = $_POST["role"];
  $grade = $_POST["grade"];

  $controller = new StaffController();

  $payload = [
    "staff_name" => $name,
    "staff_team_id" => $team,
    "staff_number" => $id,
    "staff_password" => $password,
    "staff_role" => $role,
    "staff_grade" => $grade
  ];

  $response = json_decode($controller->addStaff($payload), true);

  if (is_array($response)) {
    echo "<script> alert('" . $response["message"] . "'); </script>";
  } else {
    var_dump($response);
  }
}


function generate_staffId()
{
  $random_numbers = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
  $random_numbers_2 = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
  $username = 'HMS' . $random_numbers . $random_numbers_2;
  return $username;
}

// 
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
            <h3 class="block-title">Create a staff account</h3>
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
              <h3 class="widget-title">Add Staff</h3>
              <form method="post">
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" required placeholder="Staff name" id="name" />
                  </div>
                  <div class="form-group col-md-6">
                    <label for="id">ID Number</label>
                    <input type="text" placeholder="Staff Number" value="<?php echo generate_staffId(); ?>" required class="form-control" id="id" name="id" />
                  </div>
                  <div class="form-group col-md-6">
                    <label for="team">Team</label>
                    <select class="form-control" id="team" name="team" required>
                      <option value="">Select a team</option>
                      <?php
                      if (count($teamList) > 0) {
                        foreach ($teamList as $key => $team) {
                          // if ($team["team_name"] == "administrator") continue;
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
                    <input type="text" placeholder="Password" required minlength="5" class="form-control" id="password" name="password" />
                  </div>
                  <div class="form-group col-md-6">
                    <label for="role">Role</label>
                    <select class="form-control" id="role" required name="role">
                      <option value="">Select a staff role</option>
                      <option value="admin">Admin</option>
                      <option value="doctor">Doctor</option>
                      <option value="consultant">Consultant</option>
                      <option class="front-desk">Front Desk</option>
                    </select>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="grade">Grade</label>
                    <input type="text" placeholder="Enter grade for only junior doctor" class="form-control" id="grade" name="grade" />
                  </div>

                  <!-- <div class="form-group col-md-12">
                    <label for="file">File</label>
                    <input type="file" class="form-control" id="file" />
                  </div> -->

                  <div class="form-group col-md-6 mb-3">
                    <button type="submit" name="btnCreateStaff" class="btn btn-primary btn-lg">
                      Create Staff
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