<?php
    $pageTitle = "Slitting - Rolls";
    
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar1.php";
    include_once "../../content.php";


    include_once "../../inc/class.slitting.inc.php";
    $slitting = new Slitting($db);

	include_once "../../inc/class.users.inc.php";
    $users = new Users($db);
?>
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="../../index.php">United Production System</a>
		</li>
		<li class="breadcrumb-item">
			<a href="../process.php">Sachet Rolls</a>
		</li>
		<li class="breadcrumb-item">
			<a href="process.php">Slitting</a>
		</li>
		<li class="breadcrumb-item active">Rolls</li>
	</ol>
	<h2>Slitting - Rolls</h2>


	<div id="alertMessage" class="alert hide" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
		<?php
    if(!empty($_POST['shift']) and !empty($_POST['wt_1']) )
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($slitting->createOutputRolls()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
    if(!empty($_POST['shift']) and !empty($_POST['rollid']) )
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($slitting->createInputRolls()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }  
?>
	</div>


	<form id="formMachine" class="form-inline" style="padding-bottom:20px;" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<input type='hidden' class="form-control" name="customer" id="customer_id" required/>
		<input type='hidden' class="form-control" name="name" id="customer_name" required/>
		<div class="pull-right text-right">
			<div class="dropdown">
				<button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Submit Input Rolls&nbsp&nbsp<i class="fa fa-caret-down" style="display: inline;"></i></button>
				<ul class="dropdown-menu dropdown-menu-right">
					<?php
    $slitting->customersDropdown(2);
?>
				</ul>
			</div>
			<div class="dropdown">
				<button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Submit Output Rolls&nbsp&nbsp<i class="fa fa-caret-down" style="display: inline;"></i></button>
				<ul class="dropdown-menu dropdown-menu-right">
					<li><a onclick="selectMachine(46,'Slitting 1')" data-toggle="modal" data-target="#modal1">Slitting 1</a></li>
					<li><a onclick="selectMachine(47,'Slitting 2')" data-toggle="modal" data-target="#modal1">Slitting 2</a></li>
				</ul>
			</div>
		</div>
	</form>


	<form class="form-inline" style="padding-bottom:20px;" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<div class="form-group">
			<label for="date">Date:</label>
			<div class='input-group date' id='datetimepicker'>
				<input type='text' class="form-control" name="dateSearch" id="dateSearch" />
				<span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
				</span>
			</div>
		</div>
		<div class="form-group">
			<button type="submit" id="buttonForm" class="btn btn-info">View</button>
		</div>
	</form>
	<ul class="nav nav-tabs nav-justified">
		<li class="active"><a data-toggle="tab" href="#today" id="dateTitle"></a></li>
		<li><a data-toggle="tab" href="#day">Shift: Day</a></li>
		<li><a data-toggle="tab" href="#night">Shift: Night</a></li>
	</ul>
	<div class="tab-content">
		<div id="today" class="tab-pane fade in active">
			<h3 id="dateTitle2"></h3>
			<div class="panel panel-info">
				<div class="panel-heading">
					Input Rolls Details
				</div>
				<div class="panel-body">

					<div class="table-responsive">
						<table class="table table-bordered table-hover table-condensed " width="100%" cellspacing="0">
							<thead>
								<tr class="active text-center">
									<th>Roll No</th>
									<th>Gross Weight</th>
									<th>Net Weight</th>
									<th>Used Weight</th>
								</tr>
							</thead>
							<tbody>
								<?php
     $slitting->giveInputRollsTable(0);
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="panel panel-info">
				<div class="panel-heading">
					Output Rolls Details
				</div>
				<div class="panel-body">

					<div class="table-responsive">
						<table class="table table-bordered table-hover table-condensed " width="100%" cellspacing="0">
							<thead>
								<tr class="active text-center">
									<th></th>
									<th>Gross Weight</th>
									<th>Net Weight</th>
									<th>Job name</th>
								</tr>
							</thead>
							<tbody>
								<?php
     $slitting->giveRollsTable(0);
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div id="day" class="tab-pane fade">
			<h3>Day</h3>
			<div class="panel panel-info">
				<div class="panel-heading">
					Input Rolls Details
				</div>
				<div class="panel-body">

					<div class="table-responsive">
						<table class="table table-bordered table-hover table-condensed " width="100%" cellspacing="0">
							<thead>
								<tr class="active text-center">
									<th>Roll No</th>
									<th>Gross Weight</th>
									<th>Net Weight</th>
									<th>Used Weight</th>
								</tr>
							</thead>
							<tbody>
								<?php
     $slitting->giveInputRollsTable(1);
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="panel panel-info">
				<div class="panel-heading">
					Output Rolls Details
				</div>
				<div class="panel-body">

					<div class="table-responsive">
						<table class="table table-bordered table-hover" width="100%" cellspacing="0">
							<thead>
								<tr class="active text-center">
									<th></th>
									<th>Gross Weight</th>
									<th>Net Weight</th>
									<th>Job name</th>
								</tr>
							</thead>
							<tbody>
								<?php
     $slitting->giveRollsTable(1);
?>
							</tbody>
						</table>
					</div>

				</div>
			</div>

		</div>
		<div id="night" class="tab-pane fade">
			<h3>Night</h3>
			<div class="panel panel-info">
				<div class="panel-heading">
					Input Rolls Details
				</div>
				<div class="panel-body">

					<div class="table-responsive">
						<table class="table table-bordered table-hover table-condensed " width="100%" cellspacing="0">
							<thead>
								<tr class="active text-center">
									<th>Roll No</th>
									<th>Gross Weight</th>
									<th>Net Weight</th>
									<th>Used Weight</th>
								</tr>
							</thead>
							<tbody>
								<?php
     $slitting->giveInputRollsTable(2);
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="panel panel-info">
				<div class="panel-heading">
					Output Rolls Details
				</div>
				<div class="panel-body">

					<div class="table-responsive">
						<table class="table table-bordered table-hover" width="100%" cellspacing="0">
							<thead>
								<tr class="active text-center">
									<th></th>
									<th>Gross Weight</th>
									<th>Net Weight</th>
									<th>Job name</th>
								</tr>
							</thead>
							<tbody>
								<?php
     $slitting->giveRollsTable(2);
?>
							</tbody>
						</table>
					</div>

				</div>
			</div>

		</div>
	</div>

	<div class="modal fade" id="modal1" role="dialog" tabindex="-1">
		<div class="modal-dialog" style="width: 600px">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">x</button>
					<h4 class="modal-title">Submit output rolls details</h4>
				</div>
				<form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<div class="modal-body">

						<div class="row">
							<div class="col-md-4 form-group">
								<label for="date">Date <span class="text-danger">*</span></label>
								<div class='input-group date' id='datetimepicker2'>
									<input type='text' class="form-control" id="date" name="date" required/>
									<span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
									</span>
								</div>
							</div>
							<div class="col-md-4  form-group">
								<label for="shift">Shift <span class="text-danger">*</span></label><br />
								<input type="hidden" class="form-control" id="shift" name="shift" value="1" required>
								<div class="dropdown">
									<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_shift" style="height:30px;">Day&nbsp&nbsp<span class="caret"></span></button>
									<ul class="dropdown-menu">
										<li><a onclick="selectShift(1,'Day',1)">Day</a></li>
										<li><a onclick="selectShift(2,'Night',1)">Night</a></li>
									</ul>
								</div>
							</div>

							<div class="col-md-4 form-group">
								<label for="size">Machine <span class="text-danger">*</span></label><br />
								<input type="hidden" class="form-control" id="machine" name="machine" value="1" required>
								<input type="text" class="form-control" step="1" min="1" id="machineName" value="" disabled>
							</div>
						</div>
						<div class="row">

							<div class="col-md-4 form-group">
								<label for="shift">Operator <span class="text-danger">*</span></label><br />
								<input type="hidden" class="form-control" id="employee1" name="employee1" required>
								<div class="btn-group">
									<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_employee1">&nbsp&nbsp<span class="caret"></span></button>
									<ul class="dropdown-menu" role="menu" id="dropdown_employee1">
										<li><input type="text" placeholder="Search employee.." class="searchDropdown" id="searchEmployee1" onkeyup="filterEmployee1()" width="100%"></li>
										<?php
	$slitting->operatorsDropdown();
?>
									</ul>
								</div>
							</div>
							<div class="col-lg-4 form-group">
								<label for="customer">Customer</label><br />
								<input type="hidden" class="form-control" id="customer" name="customer">
								<div class="dropdown">
									<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_customer">&nbsp&nbsp<span class="caret"></span></button>
									<ul class="dropdown-menu" id="dropdown_customer">
										<li><input type="text" placeholder="Search customer.." class="searchDropdown" id="searchCustomer" onkeyup="filterCustomers()" width="100%"></li>
										<?php
    $slitting->customersDropdown(1);
 ?>
									</ul>
								</div>

							</div>
						</div>
						<div class="panel panel-info">
							<div class="panel-heading">
								Output Rolls Details
							</div>
							<div class="panel-body">
								<table class="table table-bordered table-hover" width="100%" cellspacing="0">
									<thead>
										<tr class="active">
											<th class="text-center">Roll No.</th>
											<th class="text-center">Roll Wt.</th>
											<th class="text-center">Roll Wt.</th>
											<th class="text-center">Roll Wt.</th>
											<th class="text-center">Roll Wt.</th>
											<th class="text-center">Roll Wt.</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>1</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_1" id="wt_1" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_26" id="wt_26" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_51" id="wt_51" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_76" id="wt_76" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_101" id="wt_101" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>2</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_2" id="wt_2" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_27" id="wt_27" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_52" id="wt_52" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_77" id="wt_77" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_102" id="wt_102" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>3</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_3" id="wt_3" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_28" id="wt_28" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_53" id="wt_53" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_78" id="wt_78" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_103" id="wt_103" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>4</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_4" id="wt_4" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_29" id="wt_29" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_54" id="wt_54" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_79" id="wt_79" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_104" id="wt_104" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>5</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_5" id="wt_5" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_30" id="wt_30" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_55" id="wt_55" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_80" id="wt_80" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_105" id="wt_105" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>6</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_6" id="wt_6" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_31" id="wt_31" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_56" id="wt_56" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_81" id="wt_81" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_106" id="wt_106" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>7</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_7" id="wt_7" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_32" id="wt_32" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_57" id="wt_57" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_82" id="wt_82" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_107" id="wt_107" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>8</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_8" id="wt_8" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_33" id="wt_33" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_58" id="wt_58" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_83" id="wt_83" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_108" id="wt_108" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>9</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_9" id="wt_9" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_34" id="wt_34" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_59" id="wt_59" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_84" id="wt_84" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_109" id="wt_109" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>10</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_10" id="wt_10" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_35" id="wt_35" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_60" id="wt_60" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_85" id="wt_85" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_110" id="wt_110" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>11</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_11" id="wt_11" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_36" id="wt_36" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_61" id="wt_61" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_86" id="wt_86" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_111" id="wt_111" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>12</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_12" id="wt_12" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_37" id="wt_37" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_62" id="wt_62" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_87" id="wt_87" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_112" id="wt_112" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>13</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_13" id="wt_13" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_38" id="wt_38" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_63" id="wt_63" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_88" id="wt_88" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_113" id="wt_113" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>14</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_14" id="wt_14" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_39" id="wt_39" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_64" id="wt_64" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_89" id="wt_89" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_114" id="wt_114" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>15</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_15" id="wt_15" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_40" id="wt_40" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_65" id="wt_65" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_90" id="wt_90" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_115" id="wt_115" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>16</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_16" id="wt_16" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_41" id="wt_41" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_66" id="wt_66" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_91" id="wt_91" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_116" id="wt_116" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>17</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_17" id="wt_17" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_42" id="wt_42" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_67" id="wt_67" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_92" id="wt_92" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_117" id="wt_117" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>18</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_18" id="wt_18" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_43" id="wt_43" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_68" id="wt_68" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_93" id="wt_93" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_118" id="wt_118" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>19</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_19" id="wt_19" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_44" id="wt_44" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_69" id="wt_69" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_94" id="wt_94" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_119" id="wt_119" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>20</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_20" id="wt_20" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_45" id="wt_45" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_70" id="wt_70" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_95" id="wt_95" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_120" id="wt_120" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>21</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_21" id="wt_21" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_46" id="wt_46" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_71" id="wt_71" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_96" id="wt_96" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_121" id="wt_121" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>22</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_22" id="wt_22" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_47" id="wt_47" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_72" id="wt_72" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_97" id="wt_97" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_122" id="wt_122" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>23</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_23" id="wt_23" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_48" id="wt_48" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_73" id="wt_73" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_98" id="wt_98" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_123" id="wt_123" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>24</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_24" id="wt_24" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_49" id="wt_49" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_74" id="wt_74" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_99" id="wt_99" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_124" id="wt_124" onkeyup="calculateTotal()" required></td>
										</tr>
										<tr>
											<td>25</td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_25" id="wt_25" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_50" id="wt_50" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_75" id="wt_75" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_100" id="wt_100" onkeyup="calculateTotal()" required></td>
											<td><input type="number" class="form-control input-sm" step="0.01" min="13" max="20" name="wt_125" id="wt_125" onkeyup="calculateTotal()" required></td>
										</tr>

									</tbody>
								</table>
							</div>
							<div class="col-md-6 form-group">
									<label for="size">Total gross weight</label><br />
									<td><input type="number" class="form-control input-sm" step="0.01" min="13"  id="total"  readonly></td>
									</div>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
						<button type="submit" id="buttonForm" class="btn btn-info">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal2" role="dialog" tabindex="-1">
		<div class="modal-dialog" style="width: 700px;">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">x</button>
					<h4 class="modal-title">Submit input rolls</h4>
				</div>
				<form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<div class="modal-body">
						<div class="row">
							<div class="col-lg-4 form-group">
								<label for="date">Date:</label>
								<div class='input-group date' id='datetimepicker3'>
									<input type='text' class="form-control" name="date" id="date" />
									<span class="input-group-addon">
														<span class="fa fa-calendar"></span>
									</span>
								</div>
							</div>
							<div class="col-lg-4 form-group">
								<label for="customer">Customer</label><br />
								<input type="hidden" class="form-control" id="customer2" name="customer">
								<input type="text" class="form-control" id="customername" disabled>
							</div>
							<div class="col-lg-4 form-group">
								<label for="shift">Shift</label><br />
								<input type="hidden" class="form-control" id="shift2" name="shift" value="1">
								<div class="dropdown">
									<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_shift2" style="height:30px;">Day&nbsp&nbsp<span class="caret"></span></button>
									<ul class="dropdown-menu">
										<li><a onclick="selectShift(1,'Day',2)">Day</a></li>
										<li><a onclick="selectShift(2,'Night',2)">Night</a></li>
									</ul>
								</div>
							</div>
						</div>

						<div class="panel panel-info">
							<div class="panel-heading">
								Input Rolls Details
							</div>
							<div class="panel-body">
								<div class="col-lg-3 form-group">
									<label for="rollno">Roll No.</label><br />
									<input type="hidden" class="form-control" id="rollid" name="rollid">
									<input type="hidden" class="form-control" id="rollno" name="rollno">
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_roll">&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu" id="dropdown_roll">
											<li><input type="text" placeholder="Search roll.." class="searchDropdown" id="searchRoll" onkeyup="filterRolls()" width="100%"></li>
											<?php
   if(!empty($_POST['customer']) and !empty($_POST['name']))
   {
        echo '<script>document.getElementById("customer2").value = '.$_POST['customer'].';</script>';
        echo '<script>document.getElementById("customername").value = "'.$_POST['name'].'";</script>';
        $slitting->giveRollsCustomerDropdown($_POST['customer'],1);
   }
 ?>
										</ul>
									</div>
								</div>
								<div class="col-lg-3 form-group">
									<label>Gross Wt.</label>
									<input type="number" class="form-control" step="0.1" min="1" id="inputRollWt" value="0" disabled>
								</div>
								<div class="col-lg-3 form-group">
									<label>Net Wt.</label>
									<input type="number" class="form-control" step="0.1" min="1" id="inputNetWt" value="0" disabled>
								</div>
								<div class="col-lg-3 form-group">
									<label>Balance Wt.</label>
									<input type="number" class="form-control" step="0.1" min="13" id="balanceWt" name="balance1" value="0">
								</div>
								<div class="col-lg-3 form-group">
									<label for="rollno2">Roll No.</label><br />
									<input type="hidden" class="form-control" id="rollid2" name="rollid2" value="null">
									<input type="hidden" class="form-control" id="rollno2" name="rollno">
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_roll2">&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu" id="dropdown_roll2">
											<li><input type="text" placeholder="Search roll.." class="searchDropdown" id="searchRoll2" onkeyup="filterRolls2()" width="100%"></li>
											<li><a onclick="selectRoll(2,null,'None',0,0)">None</a></li>
											<?php
   if(!empty($_POST['customer'])and !empty($_POST['name']))
   {
        $slitting->giveRollsCustomerDropdown($_POST['customer'],2);
        echo '<script>$(modal2).modal();</script>';
   }
 ?>
										</ul>
									</div>
								</div>
								<div class="col-lg-3 form-group">
									<label>Gross Wt.</label>
									<input type="number" class="form-control" step="0.1" min="1" id="inputRollWt2" value="0" disabled>
								</div>
								<div class="col-lg-3 form-group">
									<label>Net Wt.</label>
									<input type="number" class="form-control" step="0.1" min="1" id="inputNetWt2" value="0" disabled>
								</div>
								<div class="col-lg-3 form-group">
									<label>Balance Wt.</label>
									<input type="number" class="form-control" step="0.1" min="13" id="balanceWt2" name="balance2" value="0">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
						<button type="submit" id="buttonForm" class="btn btn-info">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<?php
  if(!empty($_POST['dateSearch']) )
   {
       echo '<script>document.getElementById("dateTitle").innerHTML = "'. $_POST['dateSearch'] .'";</script>';
      echo '<script>document.getElementById("dateTitle2").innerHTML = "'. $_POST['dateSearch'] .'";</script>';
       echo '<script>document.getElementById("dateSearch").value = "'. $_POST['dateSearch'] .'";</script>';
   }
 else
 {
       echo '<script>var d = new Date();
            var month = d.getMonth()+1;
            document.getElementById("dateTitle").innerHTML = d.getDate() + "/" + month +"/"+ d.getFullYear();
            document.getElementById("dateTitle2").innerHTML = d.getDate() + "/" + month +"/"+ d.getFullYear();
            document.getElementById("dateSearch").value = d.getDate() + "/" + month +"/"+ d.getFullYear();</script>';
 }
    
?>
		<script>
			function calculateTotal() {
				var total = +0;
				for (i = 1; i < 126; i++) {
					if (document.getElementById("wt_" + i).value !== null && document.getElementById("wt_" + i).value !== '') {
						total += Number(document.getElementById("wt_" + i).value);
					}
				}
				document.getElementById("total").value = total.toLocaleString();
			}

			function selectCustomer1(id, name) {
				document.getElementById("btn_customer").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
				document.getElementById("customer").value = id;
			}

			function selectCustomer2(id, name) {
				document.getElementById("customer_name").value = name;
				document.getElementById("customer_id").value = id;
				document.getElementById("formMachine").submit();
			}


			function selectEmployee1(id, name) {
				document.getElementById("btn_employee1").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
				document.getElementById("employee1").value = id;
			}


			function filterEmployee1() {
				var input, filter, ul, li, a, i;
				input = document.getElementById("searchEmployee1");
				filter = input.value.toUpperCase();
				div = document.getElementById("dropdown_employee1");
				a = div.getElementsByTagName("a");
				for (i = 0; i < a.length; i++) {
					if (a[i].id.toUpperCase().startsWith(filter)) {
						a[i].style.display = "";
					} else {
						a[i].style.display = "none";
					}
				}
			}

			function filterCustomers() {
				var input, filter, ul, li, a, i;
				input = document.getElementById("searchCustomer");
				filter = input.value.toUpperCase();
				div = document.getElementById("dropdown_customer");
				a = div.getElementsByTagName("a");
				for (i = 0; i < a.length; i++) {
					if (a[i].id.toUpperCase().startsWith(filter)) {
						a[i].style.display = "";
					} else {
						a[i].style.display = "none";
					}
				}
			}


			function selectShift(id, name, x) {
				if (x == 1) {
					document.getElementById("btn_shift").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("shift").value = id;
				} else if (x == 2) {
					document.getElementById("btn_shift2").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("shift2").value = id;
				}

			}

			function selectMachine(id, name) {
				document.getElementById("machine").value = id;
				document.getElementById("machineName").value = name;
			}

			function selectRoll(i, id, no, gross, net) {
				if (i == 1) {
					document.getElementById("btn_roll").innerHTML = no + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("rollid").value = id;
					document.getElementById("rollno").value = no;
					document.getElementById("inputRollWt").value = gross;
					document.getElementById("inputNetWt").value = net;
				} else {
					document.getElementById("btn_roll2").innerHTML = no + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("rollid2").value = id;
					document.getElementById("rollno2").value = no;
					document.getElementById("inputRollWt2").value = gross;
					document.getElementById("inputNetWt2").value = net;
				}
			}

			function filterRolls() {
				var input, filter, ul, li, a, i;
				input = document.getElementById("searchRoll");
				filter = input.value.toUpperCase();
				div = document.getElementById("dropdown_roll");
				a = div.getElementsByTagName("a");
				for (i = 0; i < a.length; i++) {
					if (a[i].id.toUpperCase().startsWith(filter)) {
						a[i].style.display = "";
					} else {
						a[i].style.display = "none";
					}
				}
			}

			function filterRolls2() {
				var input, filter, ul, li, a, i;
				input = document.getElementById("searchRoll2");
				filter = input.value.toUpperCase();
				div = document.getElementById("dropdown_roll2");
				a = div.getElementsByTagName("a");
				for (i = 0; i < a.length; i++) {
					if (a[i].id.toUpperCase().startsWith(filter)) {
						a[i].style.display = "";
					} else {
						a[i].style.display = "none";
					}
				}
			}
		</script>
		<script>
			$(function() {
				// #datePicker
				$('#datetimepicker').datetimepicker({
					format: 'DD/MM/YYYY',
					defaultDate: moment()
				});


				$('#datetimepicker').data("DateTimePicker").maxDate(new Date());

				$('#datetimepicker2').datetimepicker({
					format: 'DD/MM/YYYY',
					defaultDate: moment()
				});
				<?php 
						   if(!$users->admin())
						   {	
							   echo "if(moment().weekday()==1)
								{
									$('#datetimepicker2').data('DateTimePicker').minDate(moment().add(-2, 'days').millisecond(0).second(0).minute(0).hour(0));
								}
								else
								{
									$('#datetimepicker2').data('DateTimePicker').minDate(moment().add(-1, 'days').millisecond(0).second(0).minute(0).hour(0));
								}";
						   }
					?>
				$('#datetimepicker2').data("DateTimePicker").maxDate(new Date());


				$('#datetimepicker3').datetimepicker({
					format: 'DD/MM/YYYY',
					defaultDate: moment()
				});
				<?php 
						   if(!$users->admin())
						   {	
							   echo "if(moment().weekday()==1)
								{
									$('#datetimepicker2').data('DateTimePicker').minDate(moment().add(-2, 'days').millisecond(0).second(0).minute(0).hour(0));
								}
								else
								{
									$('#datetimepicker2').data('DateTimePicker').minDate(moment().add(-1, 'days').millisecond(0).second(0).minute(0).hour(0));
								}";
						   }
					?>
				$('#datetimepicker3').data("DateTimePicker").maxDate(new Date());


			})
		</script>

		<?php
    include_once '../../footer.php';
?>