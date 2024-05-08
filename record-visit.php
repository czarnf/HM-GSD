<?php

use app\controller\TeamController;

require_once("vendor/autoload.php");

if (isset($_POST["btnRecord"])) {
  if (isset($_GET["id"]) && strlen(trim($_GET["id"])) > 0) {

    $ids = explode(".", $_GET["id"]);

    if (count($ids) == 2) {
      $id = (int) $ids[0];
      $staffId = (int) $ids[1];

      $payload = [
        "record_tr_id" => $id,
        "record_staff_id" =>  $staffId
      ];

      $controller = new TeamController();

      $response = json_decode($controller->addDoctorRecord($payload), true);

      if (is_array($response)) {
        echo "<script> alert('" . $response["message"] . "'); </script>";
        header("refresh:0; url= ./");
      } else {
        var_dump($response);
      }
    } else {
      echo "<script> alert('Please, restart this process again. Click the back link to restart. Thanks.'); </script>";
    }
  }
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Record Doctors Visit - HMS Assignment</title>
</head>
<style>
  @font-face {
    font-family: "Syne";
    src: url("./public/fonts/Syne-Bold.ttf") format("truetype");
    font-weight: bold;
    font-style: normal;
    font-display: swap;
  }

  body {
    margin: 0;
    padding: 0;
  }

  * {
    font-family: 'Syne', sans-serif;
  }

  main {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    gap: 20px;
    height: 100vh;
    width: 100%;
  }

  main button {
    background: #080808;
    color: #fff;
    font-size: 20px;
    padding: 15px 30px;
    border: none;
    width: 100%;
    font-weight: 300;
    cursor: pointer;

    transition: all 0.5s ease-in-out;


  }

  main button:hover {
    background: #303530;
  }

  main a {
    font-size: 20px;
    color: #080808;
  }
</style>

<body>
  <main>
    <form action="" method="post">
      <button type="submit" name="btnRecord">Click to Record Visit</button>
    </form>
    <a href="./about-patient.php?id=<?php echo $_GET["pid"]; ?>">Go Back</a>
  </main>
</body>

</html>