<?php
    include_once "base.php";
     if(empty($_SESSION['LoggedIn']) && empty($_SESSION['Username'])):
         echo "<meta http-equiv='refresh' content='0;/logIn.php'>";
        exit;
    else: 
?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="description" content="UNITED production system" />
		<meta name="author" content="summIT Solution | Natalia Montanez" />

		<title>UPS |
			<?php echo $pageTitle ?>
		</title>

		<!-- stylesheets -->
		<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="/assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link href="/assets/css/style.css" rel="stylesheet">
		<link href="/assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

		<!-- Page level plugin CSS-->
		<link href="/assets/datatables/dataTables.bootstrap4.css" rel="stylesheet">




		<!-- scripts -->
		<script src="/assets/js/jquery-3.1.0.min.js"></script>
		<script src="/assets/js/moment.min.js"></script>
		<script src="/assets/js/bootstrap.min.js"></script>
		<script src="/assets/js/canvasjs.min.js"></script>


	</head>

	<body>

		<!-- header -->
		<nav id="header" class="navbar navbar-inverse navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<div id="sidebar-toggle-button">
						<i class="fa fa-bars" aria-hidden="true"></i>
					</div>

					<div class="brand">
						<a href="/index.php">
							United Production System
						</a>
					</div>


				</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-right">
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle mr-lg-2" id="alertsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-bell"></i>
            <span class="indicator text-warning d-none d-lg-block">
              <i class="fa fa-fw fa-circle"></i>
            </span>
          </a>
							<div class="dropdown-menu" aria-labelledby="alertsDropdown">
								<h6 class="dropdown-header">New Alerts:</h6>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">
              <span class="text-success">
                <strong>
                  <i class="fa fa-long-arrow-up fa-fw"></i>Status Update</strong>
              </span>
              <span class="small float-right text-muted">11:21 AM</span>
              <div class="dropdown-message small">This is an automated server response message. All systems are online.</div>
            </a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">
              <span class="text-danger">
                <strong>
                  <i class="fa fa-long-arrow-down fa-fw"></i>Status Update</strong>
              </span>
              <span class="small float-right text-muted">11:21 AM</span>
              <div class="dropdown-message small">This is an automated server response message. All systems are online.</div>
            </a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">
              <span class="text-success">
                <strong>
                  <i class="fa fa-long-arrow-up fa-fw"></i>Status Update</strong>
              </span>
              <span class="small float-right text-muted">11:21 AM</span>
              <div class="dropdown-message small">This is an automated server response message. All systems are online.</div>
            </a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item small" href="#">View all alerts</a>
							</div>
						</li>
						<li>
							<a>	<i class="fa fa-user fa-fw" aria-hidden="true"></i><span>USER: <?php echo $_SESSION['Username'] ?></span></a>
						</li>
						<li><a data-toggle="modal" data-target="#exampleModal"><i class="fa fa-sign-out fa-fw" aria-hidden="true"></i><span>LOG OUT</span></a></li>
					</ul>

				</div>
			</div>
		</nav>
		<!-- /header -->

		<!-- Logout Modal-->
		<div class="modal fade" id="exampleModal" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-m">
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" type="button" data-dismiss="modal">x</button>
						<h4 class="modal-title">Ready to Leave?</h4>
					</div>
					<div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
						<a class="btn btn-info" href="/logout.php">Logout</a>
					</div>
				</div>
			</div>
		</div>


		<?php   
    endif;
?>