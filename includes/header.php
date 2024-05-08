<?php
$active = $GLOBALS["page"];
?>
<!-- Top Navigation -->
<div class="container top-brand">
    <nav class="navbar navbar-default">
        <div class="navbar-header">
            <div class="sidebar-header" style="width: 120px">
                <a href="./"><img src="images/logo-dark.png" class="logo" alt="logo" style="width: 100%" /></a>
            </div>
        </div>
        <ul class="nav justify-content-end">
            <li class="nav-item">
                <a class="nav-link">
                    <span title="Fullscreen" class="ti-fullscreen fullscreen"></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="ti-user"></span>
                </a>
                <div class="dropdown-menu proclinic-box-shadow2 profile animated flipInY">
                    <h5><?php
                        $stringSplit = explode(" ", $_SESSION["user"]["staff_name"]);
                        echo $stringSplit[0] . " " . $stringSplit[1];
                        ?></h5>
                    <a class="dropdown-item" href="#">
                        <span class="ti-settings"></span> Settings</a>
                    <a class="dropdown-item" href="./logout.php">
                        <span class="ti-power-off"></span> Logout</a>
                </div>
            </li>
        </ul>
    </nav>
</div>
<!-- /Top Navigation -->
<!-- Menu -->
<div class="container menu-nav">
    <nav class="navbar navbar-expand-lg proclinic-bg text-white">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="ti-menu text-white"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?php echo $active == "dashboard" ? "active" : ""; ?> ">
                    <a class="nav-link" href="./" role="button" aria-haspopup="true" aria-expanded="false"><span class="ti-home"></span> Dashboard</a>
                </li>

                <?php
                if (isset($_SESSION["user"]["staff_role"]) && $_SESSION["user"]["staff_role"] == "admin") {
                ?>
                    <li class="nav-item dropdown <?php echo $active == "patient" ? "active" : ""; ?> ">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="ti-wheelchair"></span> Manage Patients</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="./add-patient.php">Admit Patient</a>
                            <a class="dropdown-item" href="./patients.php">Patients List</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown <?php echo $active == "staff" ? "active" : ""; ?> ">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="ti-user"></span> Manage Staffs</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="./add-staff.php">Add Staff</a>
                            <a class="dropdown-item" href="./staffs.php">Staff List</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown <?php echo $active == "team" ? "active" : ""; ?> ">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="ti-folder"></span> Manage Teams</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="add-team.php">Create Team</a>
                            <a class="dropdown-item" href="teams.php">Team List</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown <?php echo $active == "ward" ? "active" : ""; ?> ">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="ti-key"></span> Manage Wards</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="./add-ward.php">Add Ward</a>
                            <a class="dropdown-item" href="./wards.php">Ward List</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown <?php echo $active == "appointment" ? "active" : ""; ?> ">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="ti-pencil-alt"></span> Appointments</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" target="_blank" href="./public/">Schedule an appointment</a>
                            <a class="dropdown-item" href="./appointments.php
                        ">Appointment List</a>
                        </div>
                    </li>
                <?php
                }
                if (isset($_SESSION["user"]["staff_role"]) && $_SESSION["user"]["staff_role"] == "consultant") {
                ?>
                    <li class="nav-item <?php echo $active == "teampatient" ? "active" : ""; ?> ">
                        <a class="nav-link" href="./my-patients.php?id=<?php echo  $_SESSION["user"]["staff_team_id"]; ?>" role="button" aria-haspopup="true" aria-expanded="false"><span class="ti-wheelchair"></span> Team Patient</a>
                    </li>
                    <li class="nav-item <?php echo $active == "team" ? "active" : ""; ?> ">
                        <a class="nav-link" href="./my-team.php?id=<?php echo  $_SESSION["user"]["staff_team_id"]; ?>" role="button" aria-haspopup="true" aria-expanded="false"><span class="ti-folder"></span> My Team</a>
                    </li>
                    <li class="nav-item <?php echo $active == "appointment" ? "active" : ""; ?> ">
                        <a class="nav-link" href="./appointments.php" role="button" aria-haspopup="true" aria-expanded="false"><span class="ti-folder"></span> Appointment List</a>
                    </li>
                <?php }


                if (isset($_SESSION["user"]["staff_role"]) && $_SESSION["user"]["staff_role"] == "Front Desk") {
                ?>
                    <li class="nav-item dropdown <?php echo $active == "patient" ? "active" : ""; ?> ">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="ti-wheelchair"></span> Manage Patients</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="./add-patient.php">Admit Patient</a>
                            <a class="dropdown-item" href="./patients.php">Patients List</a>
                        </div>
                    </li>
                    <li class="nav-item <?php echo $active == "team" ? "active" : ""; ?> ">
                        <a class="nav-link" href="./teams.php" role="button" aria-haspopup="true" aria-expanded="false"><span class="ti-folder"></span> Teams</a>
                    </li>
                    <li class="nav-item dropdown <?php echo $active == "appointment" ? "active" : ""; ?> ">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="ti-pencil-alt"></span> Appointments</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" target="_blank" href="./public/">Schedule an appointment</a>
                            <a class="dropdown-item" href="./appointments.php
                        ">Appointment List</a>
                        </div>
                    </li>
                <?php }

                if (isset($_SESSION["user"]["staff_role"]) && ($_SESSION["user"]["staff_role"] == "doctor" || $_SESSION["user"]["staff_role"] == "consultant")) {
                ?>
                    <li class="nav-item <?php echo $active == "patient" ? "active" : ""; ?> ">
                        <a class="nav-link" href="./record-patients.php?id=<?php echo  $_SESSION["user"]["staff_id"]; ?>" role="button" aria-haspopup="true" aria-expanded="false"><span class="ti-wheelchair"></span> My Patient</a>
                    </li>
                <?php } ?>


            </ul>
        </div>
    </nav>
</div>
<!-- /Menu -->