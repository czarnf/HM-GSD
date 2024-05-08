<?php
require_once("vendor/autoload.php");


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use app\controller\PatientController;
use app\controller\TeamController;
use app\controller\WardController;

$page = "patient";
$path = "/assignment/hms";
session_start();
if (isset($_SESSION["isAuth"]) && isset($_SESSION["user"]) && isset($_SESSION["role"])) {
  // var_dump($_SESSION);
} else {
  header("location: $path/login.php");
}



$list = [];
$recordList = [];
$visitCount = 0;



$controllerTeam = new TeamController();
$controllerWard = new WardController();
$controller = new PatientController();


$teamResponse = json_decode($controllerTeam->fetchTeams(), true);
$wardResponse = json_decode($controllerWard->fetchWards(), true);

$teamList = [];
$wardList = [];



if (isset($_POST["btnTransfer"])) {



  if (strlen(trim($_POST["team"])) > 0) {
    $payload = [
      "patient_ward_id" => (int) $_POST["ward"],
      "patient_team_id" => (int) $_POST["team"],
      "patient_number" => $_POST["number"],
    ];
    $resTransfer = json_decode($controller->transferPatient($payload, true), true);
  } else {
    $payload = [
      "patient_ward_id" => (int) $_POST["ward"],
      "patient_number" => $_POST["number"],
    ];
    $resTransfer = json_decode($controller->transferPatient($payload, false), true);
  }



  if (is_array($resTransfer)) {
    echo "<script> alert('" . $resTransfer["message"] . "'); </script>";
  } else {
    var_dump($resTransfer);
  }
}


if (isset($_GET["delete"])) {
  // var_dump();
  $id = $_GET["id"];
  $response = json_decode($controller->deletePatientBySlug($id), true);
  if (strlen($response["message"]) > 5) {
    echo "<script> alert('" . $response["message"] . "'); </script>";
    header("refresh:0; url=$path/appointments.php");
  }
}




if (is_array($teamResponse) && isset($teamResponse["message"])) {
  $teamList = $teamResponse["message"];
}


if (is_array($wardResponse) && isset($wardResponse["message"])) {
  $wardList = $wardResponse["message"];
}



if (isset($_GET["id"]) && strlen(trim($_GET["id"])) > 5) {
  $id = $_GET["id"];

  if (isset($_GET["mark"])) {
    $markResponse = json_decode($controller->modifyPatientStatus($id, 1), true);
    if (is_array($markResponse)) {
      echo "<script> alert('" . $markResponse["message"] . "'); </script>";
    }
  }



  $response = json_decode($controller->fetchPatientByNumber($id), true);

  if (count($response["message"]) > 0) {
    $list = $response["message"];
    $recordResponse = json_decode($controllerTeam->fetchDoctorsVisit($response["message"]["patient_id"]), true);
    $visitCount = count($recordResponse["message"]);

    if ($visitCount > 0) {
      $recordList = $recordResponse["message"];
    }
  } else {
    header("location: $path/patients.php");
  }
} else {
  header("location: $path/patients.php");
}

// var_dump($list);


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
            <h3 class="block-title"><?php echo count($list) > 0 ? explode(" ", $list["patient_name"])[0]  . "'s Record" : "Unidentified Patient Record"; ?></h3>
          </div>
          <div class="col-md-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="index.html">
                  <span class="ti-home"></span>
                </a>
              </li>
              <li class="breadcrumb-item active">Patient</li>
            </ol>
          </div>
        </div>
      </div>
      <!-- /Page Title -->

      <!-- /Breadcrumb -->
      <!-- Main Content -->
      <div class="container">
        <div class="row">
          <?php
          if (isset($_SESSION["user"]["staff_role"]) && $_SESSION["user"]["staff_role"] == "consultant") {
          ?>
            <div class="col-12" style="margin-top:20px; display:flex; justify-content:flex-end;">
              <a class="btn btn-primary" href="./assign-doctor.php?patient=<?php echo $list["patient_id"]; ?>&team=<?php echo $list["patient_team_id"]; ?>">(Re)Aassign a doctor</a>
            </div>
          <?php } ?>
          <!-- Widget Item -->
          <div class="col-md-6">
            <div class="widget-area-2 proclinic-box-shadow">
              <h3 class="widget-title">Patient Details</h3>
              <div class="table-responsive">
                <table class="table table-bordered">
                  <tbody>
                    <tr>
                      <td><strong>Admitted Date</strong></td>
                      <td><?php echo $list["createdAt"] ?></td>
                    </tr>
                    <tr>
                      <td><strong>Name</strong></td>
                      <td><?php echo $list["patient_name"] ?></td>
                    </tr>
                    <tr>
                      <td><strong>Age</strong></td>
                      <td><?php echo $list["patient_age"] ?> years</td>
                    </tr>
                    <tr>
                      <td><strong>Gender</strong></td>
                      <td><?php echo ucfirst($list["patient_gender"]); ?></td>
                    </tr>
                    <tr>
                      <td><strong>Ward</strong></td>
                      <td><?php echo ucfirst($list["ward_name"]); ?></td>
                    </tr>
                    <tr>
                      <td><strong>Bed number</strong></td>
                      <td><?php echo "Number " . $list["ward_total_bed"]; ?></td>
                    </tr>
                    <tr>
                      <td><strong>Team</strong></td>
                      <td><?php echo $list["team_name"]; ?></td>
                    </tr>
                    <tr>
                      <td><strong>Assigned Doctor</strong></td>
                      <td><?php echo $list["staff_name"]; ?></td>
                    </tr>

                    <tr>
                      <td><strong>Total Visit Count</strong></td>
                      <td><?php echo $visitCount; ?></td>
                    </tr>


                  </tbody>
                </table>
              </div>

              <?php
              if (isset($_SESSION["user"]["staff_role"]) && $list["patient_status"] == 1 && ($_SESSION["user"]["staff_role"] == "admin" || $_SESSION["user"]["staff_role"] == "Front Desk")) {
              ?>
                <a href="./about-patient.php?id=<?php echo $list["patient_number"]; ?>&delete=true" class="btn btn-danger mb-3">
                  <span class="ti-trash"></span> Discharge Patient
                </a>
              <?php } ?>
            </div>
          </div>

          <div class="col-md-6">
            <div class="widget-area-2 proclinic-box-shadow">
              <h3 class="widget-title">Patient Record</h3>
              <div class="table-responsive">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <td>Sn</td>
                      <th>Doctor Name</th>
                      <th>Visit Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if (count($recordList)  > 0) {
                      $count = 1;
                      foreach ($recordList as $key => $record) {
                        // if ($record["staff_name"] == NULL) continue;
                    ?>
                        <tr>
                          <td><?php echo $count++; ?></td>
                          <td><?php echo $record["staff_name"]; ?></td>
                          <td><?php echo $record["createdAt"]; ?></td>
                        </tr>
                      <?php
                      }
                    } else {
                      ?>
                      <tr>
                        <td style="text-align: center;" colspan="2">No data to display!</td>
                      </tr>
                    <?php } ?>

                  </tbody>
                </table>
              </div>
              <?php
              if (isset($_SESSION["user"]["staff_role"]) && $list["patient_status"] == 0 && strlen(trim($list["tr_id"])) > 0 && ($_SESSION["user"]["staff_role"] == "consultant" || $_SESSION["user"]["staff_role"] == "doctor")) {
              ?>
                <a href="./record-visit.php?id=<?php echo $list["tr_id"] . "." . $list["staff_id"]; ?>&pid=<?php echo $list["patient_number"]; ?>" class="btn btn-success mb-3">
                  <span class="ti-arrow-top-left"></span> Record Patient Visit
                </a>
                <a href="./about-patient.php?id=<?php echo $list["patient_number"]; ?>&mark=true" class="btn btn-danger mb-3">
                  <span class="ti-trash"></span> Mark Patient For Discharge
                </a>

              <?php }

              if (isset($_SESSION["user"]["staff_role"]) && $list["patient_status"] == 1 && strlen(trim($list["tr_id"])) > 0 && ($_SESSION["user"]["staff_role"] == "consultant" || $_SESSION["user"]["staff_role"] == "doctor")) {
                echo "<p class='alert alert-info'> This patient has been marked for discharge. And will soon be discharged</p>";
              }


              ?>
            </div>
          </div>

          <div class="col-md-6">
            <div class="widget-area-2 proclinic-box-shadow">
              <h3 class="widget-title">Transfer Patient</h3>

              <form method="post">
                <div class="form-row">
                  <div class="form-group col-12">
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

                  <input type="hidden" name="number" value="<?php echo $list["patient_number"]; ?>">

                  <div class="form-group col-12">
                    <label for="team">Team <small>( <em>Optional</em> )</small></label>
                    <select class="form-control" id="team" name="team">
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

                  <div class="form-group col-md-6 mb-3">
                    <button type="submit" name="btnTransfer" class="btn btn-primary btn-lg">
                      Transfer Patient
                    </button>
                  </div>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
      <!-- /Main Content -->
      <!--Copy Rights-->
      <div class="container" style="margin-top: 100px;">
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