<?php

use app\controller\AppointmentController;
use app\controller\TeamController;

$page = "staff";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../vendor/autoload.php");

//$path = "/assignment/hms"; 
$path = "/assignment/hms";

$controllerTeam = new TeamController();

$teamResponse = json_decode($controllerTeam->fetchTeams(), true);

$teamList = [];

if (is_array($teamResponse) && isset($teamResponse["message"])) {
  $teamList = $teamResponse["message"];
}


if (isset($_POST["btnAppointment"])) {
  $name = $_POST["name"];
  $team = (int) $_POST["department"];
  $email = $_POST["email"];
  $phone = $_POST["phone"];
  $date = $_POST["date"];
  $time = $_POST["time"];
  $comment = $_POST["comment"];

  $controller = new AppointmentController();

  $payload = [
    "appointment_name" => $name,
    "appointment_team_id" => $team,
    "appointment_phone" => $phone,
    "appointment_email" => $email,
    "appointment_date" => $date,
    "appointment_time" => $time,
    "appointment_comment" => $comment,
  ];

  $response = json_decode($controller->addAppointment($payload), true);

  if (is_array($response)) {
    echo "<script> alert('" . $response["message"] . "'); </script>";
  } else {
    var_dump($response);
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Public Appointment</title>
  <link rel="stylesheet" href="./main.css" />
  <link rel="shortcut icon" href="../images/fav.png" type="image/x-icon" />
</head>

<body>
  <header>
    <img src="../images/logo-dark.png" alt="logo" srcset="" />

    <div class="">
      <button id="handburger">
        <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5"></path>
        </svg>
        <span>Menu</span>
      </button>
    </div>
  </header>
  <main>
    <div id="bg">
      <img src="./bg.svg" alt="Appointment background" />
    </div>
    <div id="formContainer">
      <h2>Book Your Appointment</h2>
      <p>
        <q>HMS Appointment</q> simplifies scheduling your next doctor's visit.
        Select your preferred date, time, and doctor specialization, and we'll take care of
        the rest. Say goodbye to long waits and hello to personalized
        healthcare!
      </p>
      <form action="" method="post">
        <div class="formControl">
          <label for="name">Full name:</label>
          <input type="text" id="name" required name="name" />
        </div>
        <div class="formControl">
          <label for="department">Department:</label>
          <select name="department" id="department" required>
            <option value="">Select a specialization</option>
            <?php
            if (count($teamList) > 0) {
              foreach ($teamList as $key => $team) {
                if ($team["team_name"] == "administrator") continue;
            ?>
                <option value="<?php echo $team["team_id"]; ?>"><?php echo ucfirst($team["team_desc"]); ?></option>
            <?php
              }
            }
            ?>
          </select>
        </div>
        <div class="full">
          <div class="formControl">
            <label for="email">Email address:</label>
            <input type="email" id="email" name="email" required />
          </div>
          <div class="formControl">
            <label for="phone">Phone number:</label>
            <input type="text" id="phone" name="phone" required />
          </div>
        </div>
        <div class="full">
          <div class="formControl">
            <label for="date">Date:</label>
            <input type="date" id="date" required name="date" />
          </div>
          <div class="formControl">
            <label for="time">Time:</label>
            <select id="time" name="time" required>
              <option value="">Select an appointment slot</option>
              <option value="9AM-10AM">9AM - 10AM</option>
              <option value="10AM-11AM">10AM - 11AM</option>
              <option value="11AM-12PM">11AM - 12PM</option>
              <option value="12PM-1PM">12PM - 1PM</option>
              <option value="2PM-3PM">2PM - 3PM</option>
              <option value="3PM-4PM">3PM - 4PM</option>
              <option value="4PM-5PM">4PM - 5PM</option>
            </select>
          </div>
        </div>
        <div class="formControl">
          <label for="comment">Comment:</label>
          <textarea name="comment" id="comment" rows="3" required></textarea>
        </div>

        <button name="btnAppointment" type="submit">Book Appointment</button>
      </form>
    </div>
  </main>
  <footer>
    Copyright © 2024
    <a href="./" target="_blank">HMS - Assignment</a>. All rights reserved.
  </footer>
</body>

</html>