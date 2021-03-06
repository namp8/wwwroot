<?php
    $pageTitle = "Macchi Shrink Film - Rolls";
    
    include_once "../base.php";
    include_once "../header.php";
    include_once "sidebar.php";
    include_once "../content.php";


    include_once "../inc/class.macchi.inc.php";
    $macchi = new Macchi($db);

?>
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="../index.php">United Production System</a>
		</li>
		<li class="breadcrumb-item">
			<a href="process.php">Macchi</a>
		</li>
		<li class="breadcrumb-item active">Water Pouch - Rolls</li>
	</ol>
	<h2>Macchi - Shrink Film - Rolls</h2>


	<div id="alertMessage" class="alert hide" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
		<?php
    if(!empty($_POST['shift']) )
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($macchi->createShrink()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
       
?>
	</div>


	<button class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" data-toggle="modal" data-target="#modal1">Submit rolls</button>


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
					Daily rolls by size
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" width="100%" cellspacing="0">
							<thead>
								<tr class="active">
									<th>Job Name</th>
									<th>Roll No. From</th>
									<th>Roll No. To</th>
									<th># Rolls</th>
									<th>Gross weight</th>
									<th>Net weight</th>
									<th>Size</th>
									<th>Thickness</th>
								</tr>
							</thead>
							<tbody>
								<?php
     $macchi->giveShrink(0);
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>


			<div class="panel panel-info">
				<div class="panel-heading">
					Total Raw Material Consumption
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" cellspacing="0">

							<?php
     $macchi->giveShrinkConsumption(0);
?>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div id="day" class="tab-pane fade">
			<h3>Day</h3>
			<div class="panel panel-info">
				<div class="panel-heading">
					Daily rolls by size
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" width="100%" cellspacing="0">
							<thead>
								<tr class="active">
									<th>Job Name</th>
									<th>Roll No. From</th>
									<th>Roll No. To</th>
									<th># Rolls</th>
									<th>Gross weight</th>
									<th>Net weight</th>
									<th>Size</th>
									<th>Thickness</th>
								</tr>
							</thead>
							<tbody>
								<?php
     $macchi->giveShrink(1);
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>


			<div class="panel panel-info">
				<div class="panel-heading">
					Total Raw Material Consumption
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" cellspacing="0">

							<?php
     $macchi->giveShrinkConsumption(1);
?>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div id="night" class="tab-pane fade">
			<h3>Night</h3>
			<div class="panel panel-info">
				<div class="panel-heading">
					Daily rolls by size
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" width="100%" cellspacing="0">
							<thead>
								<tr class="active">
									<th>Job Name</th>
									<th>Roll No. From</th>
									<th>Roll No. To</th>
									<th># Rolls</th>
									<th>Gross weight</th>
									<th>Net weight</th>
									<th>Size</th>
									<th>Thickness</th>
								</tr>
							</thead>
							<tbody>
								<?php
     $macchi->giveShrink(2);
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>


			<div class="panel panel-info">
				<div class="panel-heading">
					Total Raw Material Consumption
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" cellspacing="0">

							<?php
     $macchi->giveShrinkConsumption(2);
?>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="modal1" role="dialog" tabindex="-1">
			<div class="modal-dialog" style="width: 600px">
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" type="button" data-dismiss="modal">x</button>
						<h4 class="modal-title">Submit rolls</h4>
					</div>
					<form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-3 form-group">
									<label for="date">Date <span class="text-danger">*</span></label>
									<div class='input-group date' id='datetimepicker2'>
										<input type='text' class="form-control" id="date" name="date" required/>
										<span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
										</span>
									</div>
								</div>
								<div class="col-md-2 form-group">
									<label for="shift">Shift <span class="text-danger">*</span></label><br />
									<input type="hidden" class="form-control" id="shift" name="shift" value="1" required>
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_shift" style="height:30px;">Day&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="selectShift(1,'Day')">Day</a></li>
											<li><a onclick="selectShift(2,'Night')">Night</a></li>
										</ul>
									</div>
								</div>
								<div class="col-md-3 form-group">
									<label for="size">Production <span class="text-danger">*</span></label><br />
									<input type="hidden" class="form-control" id="sample" name="sample" value="0" required>
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_sample" style="height:30px;">Normal&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="selectSample(0,'Normal')">Normal</a></li>
											<li><a onclick="selectSample(1,'Sample')">Sample</a></li>
										</ul>
									</div>
								</div>
                                <div class="col-md-4 form-group">
									<label for="customer">Customer</label><br />
									<input type="hidden" class="form-control" id="customer" name="customer">
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_customer">&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu" id="dropdown_customer">
											<li><input type="text" placeholder="Search customer.." class="searchDropdown" id="searchCustomer" onkeyup="filterCustomers()" width="100%"></li>
											<?php
    $macchi->customersDropdown();
 ?>
										</ul>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4 form-group">
									<label for="size">Thickness (µ)<span class="text-danger">*</span></label><br />
									<input type="number" class="form-control" step="1" min="1" id="thickness" name="thickness" value="45" required>
								</div>
								<div class="col-md-4 form-group">
									<label for="size">Size (mm)<span class="text-danger">*</span></label><br />
									<input type="number" class="form-control" step="1" min="1" id="size" name="size" value="445" required>
								</div>
                                <div class="col-md-4 form-group">
									<label for="size">Cone Weight (kg)<span class="text-danger">*</span></label><br />
									<input type="number" class="form-control" step="0.001" min="0" id="cone" name="cone" value="0.625" required>
								</div>
							</div>
							<div class="panel panel-info">
								<div class="panel-heading">
									Rolls
								</div>
								<div class="panel-body">
									<table class="table table-bordered table-hover table-condensed" width="100%" cellspacing="0">
										<thead>
											<tr class="active">
												<th class="text-center">Time</th>
												<th class="text-center">Roll No. From</th>
												<th class="text-center">Roll No. To</th>
												<th class="text-center"># Rolls</th>
												<th class="text-center">Gross Wt</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<div class='input-group date' id='timepicker' required>
														<input type='text' class="form-control" id="time" name="time" value="00:00" required/>
														<span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="from_1" id="from_1" value="1" required onkeyup="calculate(1)"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="to_1" id="to_1" value="40" required onkeyup="calculate(1)"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="rolls_1" id="rolls_1" readonly value="40"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_1"  id="wt_1" onkeyup="calculateTotal()"  required></td>
											</tr>
											<tr>
												<td>
													<div class='input-group date' id='timepicker2' required>
														<input type='text' class="form-control" id="time2" name="time2" value="00:00" />
														<span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="from_2" id="from_2" onkeyup="calculate(2)" value="41"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="to_2" id="to_2" onkeyup="calculate(2)" value="80"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="rolls_2" id="rolls_2" readonly value="40"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_2"  id="wt_2" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>
													<div class='input-group date' id='timepicker3' required>
														<input type='text' class="form-control" id="time3" name="time3" value="00:00" />
														<span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="from_3" id="from_3" onkeyup="calculate(3)" value="81"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="to_3" id="to_3" onkeyup="calculate(3)" value="120"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="rolls_3" id="rolls_3" readonly value="40"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_3"  id="wt_3" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>
													<div class='input-group date' id='timepicker4' required>
														<input type='text' class="form-control" id="time4" name="time4" value="00:00" />
														<span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="from_4" id="from_4" onkeyup="calculate(4)" value="121"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="to_4" id="to_4" onkeyup="calculate(4)" value="160"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="rolls_4" id="rolls_4" readonly value="40"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_4"  id="wt_4" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>
													<div class='input-group date' id='timepicker5' required>
														<input type='text' class="form-control" id="time5" name="time5" value="00:00" />
														<span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="from_5" id="from_5" onkeyup="calculate(5)" value="161"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="to_5" id="to_5" onkeyup="calculate(5)" value="200"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="rolls_5" id="rolls_5" readonly value="40"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_5"  id="wt_5" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>
													<div class='input-group date' id='timepicker6' required>
														<input type='text' class="form-control" id="time6" name="time6" value="00:00" />
														<span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="from_6" id="from_6" onkeyup="calculate(6)" value="201"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="to_6" id="to_6" onkeyup="calculate(6)" value="240"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="rolls_6" id="rolls_6" readonly value="40"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_6"  id="wt_6" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>
													<div class='input-group date' id='timepicker7' required>
														<input type='text' class="form-control" id="time7" name="time7" value="00:00" />
														<span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="from_7" id="from_7" onkeyup="calculate(7)" value="241"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="to_7" id="to_7" onkeyup="calculate(7)" value="280"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="rolls_7" id="rolls_7" readonly value="40"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_7"  id="wt_7" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>
													<div class='input-group date' id='timepicker8' required>
														<input type='text' class="form-control" id="time8" name="time8" value="00:00" />
														<span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="from_8" id="from_8" onkeyup="calculate(8)" value="281"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="to_8" id="to_8" onkeyup="calculate(8)" value="320"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="rolls_8" id="rolls_8" readonly value="40"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_8"  id="wt_8" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>
													<div class='input-group date' id='timepicker9' required>
														<input type='text' class="form-control" id="time9" name="time9" value="00:00" />
														<span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="from_9" id="from_9" onkeyup="calculate(9)" value="321"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="to_9" id="to_9" onkeyup="calculate(9)" value="360"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="rolls_9" id="rolls_9" readonly value="40"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_9"  id="wt_9" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>
													<div class='input-group date' id='timepicker10' required>
														<input type='text' class="form-control" id="time10" name="time10" value="00:00" />
														<span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="from_10" id="from_10" onkeyup="calculate(10)" value="361"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="to_10" id="to_10" onkeyup="calculate(10)" value="400"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="rolls_10" id="rolls_10" readonly value="40"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_10" id="wt_10" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>
													<div class='input-group date' id='timepicker11' required>
														<input type='text' class="form-control" id="time11" name="time11" value="00:00" />
														<span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="from_11" id="from_11" onkeyup="calculate(11)" value="361"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="to_11" id="to_11" onkeyup="calculate(11)" value="400"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="rolls_11" id="rolls_11" readonly value="40"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_11" id="wt_11" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>
													<div class='input-group date' id='timepicker12' required>
														<input type='text' class="form-control" id="time12" name="time12" value="00:00" />
														<span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="from_12" id="from_12" onkeyup="calculate(12)" value="401"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="to_12" id="to_12" onkeyup="calculate(12)" value="440"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="rolls_12" id="rolls_12" readonly value="40"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_12" id="wt_12" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>
													<div class='input-group date' id='timepicker13' required>
														<input type='text' class="form-control" id="time13" name="time13" value="00:00" />
														<span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="from_13" id="from_13" onkeyup="calculate(13)" value="441"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="to_13" id="to_13" onkeyup="calculate(13)" value="480"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="rolls_13" id="rolls_13" readonly value="40"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_13" id="wt_13" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>
													<div class='input-group date' id='timepicker14' required>
														<input type='text' class="form-control" id="time14" name="time14" value="00:00" />
														<span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="from_14" id="from_14" onkeyup="calculate(14)" value="481"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="to_14" id="to_14" onkeyup="calculate(14)" value="520"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="rolls_14" id="rolls_14" readonly value="40"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_14" id="wt_14" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>
													<div class='input-group date' id='timepicker15' required>
														<input type='text' class="form-control" id="time15" name="time15" value="00:00" />
														<span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="from_15" id="from_15" onkeyup="calculate(15)" value="521"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="to_15" id="to_15" onkeyup="calculate(15)" value="560"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="rolls_15" id="rolls_15" readonly value="40"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_15" id="wt_15" onkeyup="calculateTotal()" ></td>
											</tr>
										</tbody>
									</table>

									<div class="col-md-4 form-group pull-right">
										<label for="size">Total gross weight</label><br />
										<td><input type="number" class="form-control input-sm" step="0.01" min="1" id="total" readonly></td>
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
				for (i = 1; i < 16; i++) {
					if (document.getElementById("wt_" + i).value !== null && document.getElementById("wt_" + i).value !== '') {
						total += Number(document.getElementById("wt_" + i).value);
					}
				}
				document.getElementById("total").value = total;
			}
				
				function selectShift(id, name) {
					document.getElementById("btn_shift").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("shift").value = id;
				}

				function selectCustomer(id, name) {
					document.getElementById("btn_customer").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("customer").value = id;
				}


				function selectSample(id, name) {
					document.getElementById("btn_sample").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("sample").value = id;

				}

				function calculate(id) {
					var from, to, rolls;
					from = document.getElementById("from_" + id).value;
					to = document.getElementById("to_" + id).value;
					rolls = to - from + 1;
					if (rolls > 0) {
						document.getElementById("rolls_" + id).value = rolls;
					}
				}
			</script>
			<script>
				$(function() {
					// #datePicker
					$('#datetimepicker').datetimepicker({
						format: 'DD/MM/YYYY'
					});

					$('#datetimepicker').data("DateTimePicker").maxDate(new Date());

					$('#datetimepicker2').datetimepicker({
						format: 'DD/MM/YYYY'
					});

					$('#datetimepicker2').data("DateTimePicker").maxDate(new Date());

					$('#timepicker').datetimepicker({
						format: 'HH:mm'
					});
					$('#timepicker2').datetimepicker({
						format: 'HH:mm'
					});
					$('#timepicker3').datetimepicker({
						format: 'HH:mm'
					});
					$('#timepicker4').datetimepicker({
						format: 'HH:mm'
					});
					$('#timepicker5').datetimepicker({
						format: 'HH:mm'
					});
					$('#timepicker6').datetimepicker({
						format: 'HH:mm'
					});
					$('#timepicker7').datetimepicker({
						format: 'HH:mm'
					});
					$('#timepicker8').datetimepicker({
						format: 'HH:mm'
					});
					$('#timepicker9').datetimepicker({
						format: 'HH:mm'
					});
					$('#timepicker10').datetimepicker({
						format: 'HH:mm'
					});

					var d = new Date();
					var month = d.getMonth() + 1;
					document.getElementById("date").value = d.getDate() + "/" + month + "/" + d.getFullYear();

				})
			</script>

			<?php
    include_once '../footer.php';
?>