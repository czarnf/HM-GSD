<?php

use app\controller\StaffController;

require_once("vendor/autoload.php");


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



$controller = new StaffController();

if (isset($_POST["loginForm"])) {
  if (isset($_POST["user"]) && isset($_POST["password"])) {
    $username = $_POST["user"];
    $password = $_POST["password"];


    $response = json_decode($controller->staffLoginAuth($username, $password), true);

    if ($response["status_code"] == 200 && count($response["data"]) > 3) {
      session_start();
      $_SESSION["isAuth"] = true;
      $_SESSION["user"] = $response['data'];
      $_SESSION["role"] = $response['data']["staff_role"];
      header("location: ./");
    }

    // var_dump($response);
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
  <!-- Main CSS -->
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="css/green.css" id="style_theme" />
  <link rel="stylesheet" href="css/responsive.css" />

  <script src="js/modernizr.min.js"></script>
</head>

<body class="auth-bg">
  <!-- Pre Loader -->
  <div class="loading">
    <div class="spinner">
      <div class="double-bounce1"></div>
      <div class="double-bounce2"></div>
    </div>
  </div>
  <!--/Pre Loader -->

  <div class="wrapper">
    <!-- Page Content  -->
    <div id="content">
      <div class="container">
        <div class="row">
          <div class="col-sm-5 auth-box">
            <div class="proclinic-box-shadow">
              <h3 class="widget-title">HMS Staff Login</h3>
              <form class="widget-form" method="post">
                <!-- form-group -->
                <div class="form-group row" style="margin-bottom: 0">
                  <div class="col-sm-12">
                    <input name="user" placeholder="Username" class="form-control" required="" />
                  </div>
                </div>
                <!-- /.form-group -->
                <!-- form-group -->
                <div class="form-group row">
                  <div class="col-sm-12">
                    <input type="password" placeholder="Password" name="password" class="form-control" required />
                  </div>
                </div>
                <!-- /.form-group -->

                <!-- Login Button -->
                <div class="button-btn-block">
                  <button type="submit" name="loginForm" class="btn btn-primary btn-lg btn-block">
                    Login
                  </button>
                </div>
                <!-- /Login Button -->
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /Page Content  -->
  </div>
  <!-- Jquery Library-->
  <script src="js/jquery-3.2.1.min.js"></script>
  <!-- Popper Library-->
  <script src="js/popper.min.js"></script>
  <!-- Bootstrap Library-->
  <script src="js/bootstrap.min.js"></script>
  <!-- Custom Script-->
  <script src="js/custom.js"></script>
</body>

</html>