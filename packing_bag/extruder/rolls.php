<?php
    $pageTitle = "Packing Bags Rolls";
    
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar.php";
    include_once "../../content.php";


    include_once "../../inc/class.packing.inc.php";
    $packing = new Packing($db);

?>
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="../../index.php">United Production System</a>
		</li>
		<li class="breadcrumb-item">
			<a href="process.php">Packing Bags</a>
		</li>
		<li class="breadcrumb-item active">Rolls</li>
	</ol>
	<h2>Packing Bags - Rolls</h2>


	<div id="alertMessage" class="alert hide" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
		<?php
    if(!empty($_POST['shift']) )
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($packing->createRolls()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
       
?>
	</div>


	<div class="pull-right text-right">
		<div class="dropdown">
			<button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Submit roll&nbsp&nbsp<i class="fa fa-caret-down" style="display: inline;"></i></button>
			<ul class="dropdown-menu dropdown-menu-right">
				<li><a onclick="selectMachine(9,'Extruder 1')" data-toggle="modal" data-target="#modal1">Extruder 1</a></li>
				<li><a onclick="selectMachine(10,'Extruder 2')" data-toggle="modal" data-target="#modal1">Extruder 2</a></li>
			</ul>
		</div>
	</div>


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
					Daily rolls by machine
				</div>
				<div class="panel-body">
					<div class="col-md-6">
						<h4>Extruder No. 1</h4>
						<div class="table-responsive">
							<table class="table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr class="active">
										<th width="20%">Roll No</th>
										<th width="20%">Gross weight</th>
										<th width="20%">Net weight</th>
										<th width="20%">Thickness</th>
										<th width="20%">Color</th>
									</tr>
								</thead>
								<tbody>
									<?php
     $packing->giveRolls(0,9);
?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-6">
						<h4>Extruder No. 2</h4>
						<div class="table-responsive">
							<table class="table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr class="active">
										<th width="20%">Roll No</th>
										<th width="20%">Gross weight</th>
										<th width="20%">Net weight</th>
										<th width="20%">Thickness</th>
										<th width="20%">Color</th>
									</tr>
								</thead>
								<tbody>
									<?php
     $packing->giveRolls(0,10);
?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-info">
				<div class="panel-heading">
					Total net production
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" width="100%" cellspacing="0">
							<thead>
								<tr class="active">
									<th class="text-center">Machine<br/> No</th>
									<th class="text-center">Weight of Rolls</th>
									<th class="text-center">No. of Rolls produced</th>
									<th class="text-center">Weight of cones</th>
									<th class="text-center">Actual weight</th>
								</tr>
							</thead>
							<tbody>
<?php
     $packing->giveTotalProduction(0);
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="panel panel-info">
				<div class="panel-heading">
					Total Raw Material Consumption (From Rolls)
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" cellspacing="0">
<?php
     $packing->giveConsumption(0);
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
					Daily rolls by machine
				</div>
				<div class="panel-body">
					<div class="col-md-6">
						<h4>Extruder No. 1</h4>
						<div class="table-responsive">
							<table class="table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr class="active">
										<th width="20%">Roll No</th>
										<th width="20%">Gross weight</th>
										<th width="20%">Net weight</th>
										<th width="20%">Thickness</th>
										<th width="20%">Color</th>
									</tr>
								</thead>
								<tbody>
									<?php
     $packing->giveRolls(1,9);
?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-6">
						<h4>Extruder No. 2</h4>
						<div class="table-responsive">
							<table class="table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr class="active">
										<th width="20%">Roll No</th>
										<th width="20%">Gross weight</th>
										<th width="20%">Net weight</th>
										<th width="20%">Thickness</th>
										<th width="20%">Color</th>
									</tr>
								</thead>
								<tbody>
									<?php
     $packing->giveRolls(1,10);
?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-info">
				<div class="panel-heading">
					Total net production
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" width="100%" cellspacing="0">
							<thead>
								<tr class="active">
									<th class="text-center">Machine<br/> No</th>
									<th class="text-center">Weight of Rolls</th>
									<th class="text-center">No. of Rolls produced</th>
									<th class="text-center">Weight of cones</th>
									<th class="text-center">Actual weight</th>
								</tr>
							</thead>
							<tbody>
<?php
     $packing->giveTotalProduction(1);
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="panel panel-info">
				<div class="panel-heading">
					Total Raw Material Consumption (From Rolls)
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" cellspacing="0">
<?php
     $packing->giveConsumption(1);
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
					Daily rolls by machine
				</div>
				<div class="panel-body">
					<div class="col-md-6">
						<h4>Extruder No. 1</h4>
						<div class="table-responsive">
							<table class="table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr class="active">
										<th width="20%">Roll No</th>
										<th width="20%">Gross weight</th>
										<th width="20%">Net weight</th>
										<th width="20%">Thickness</th>
										<th width="20%">Color</th>
									</tr>
								</thead>
								<tbody>
									<?php
     $packing->giveRolls(2,9);
?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-6">
						<h4>Extruder No. 2</h4>
						<div class="table-responsive">
							<table class="table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr class="active">
										<th width="20%">Roll No</th>
										<th width="20%">Gross weight</th>
										<th width="20%">Net weight</th>
										<th width="20%">Thickness</th>
										<th width="20%">Color</th>
									</tr>
								</thead>
								<tbody>
									<?php
     $packing->giveRolls(2,10);
?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-info">
				<div class="panel-heading">
					Total net production
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" width="100%" cellspacing="0">
							<thead>
								<tr class="active">
									<th class="text-center">Machine<br/> No</th>
									<th class="text-center">Weight of Rolls</th>
									<th class="text-center">No. of Rolls produced</th>
									<th class="text-center">Weight of cones</th>
									<th class="text-center">Actual weight</th>
								</tr>
							</thead>
							<tbody>
<?php
     $packing->giveTotalProduction(2);
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="panel panel-info">
				<div class="panel-heading">
					Total Raw Material Consumption (From Rolls)
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" cellspacing="0">
<?php
     $packing->giveConsumption(2);
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
								<div class="col-md-6 form-group">
									<label for="date">Date <span class="text-danger">*</span></label>
									<div class='input-group date' id='datetimepicker2'>
										<input type='text' class="form-control" id="date" name="date" required/>
										<span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
										</span>
									</div>
								</div>
								<div class="col-md-3 form-group">
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
									<label for="shift">Color<span class="text-danger">*</span></label><br />
									<input type="hidden" class="form-control" id="color" name="color" value="0" required>
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_color" style="height:30px;">Natural&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="selectColor(0,'Natural')">Natural</a></li>
											<li><a onclick="selectColor(1,'White')">White</a></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label for="size">Machine <span class="text-danger">*</span></label><br />
									<input type="hidden" class="form-control" id="machine" name="machine" value="1" required>
									<input type="text" class="form-control" step="1" min="1" id="machineName"  value="" disabled>
								</div>
								<div class="col-md-3 form-group">
									<label for="size">Thickness (µ)<span class="text-danger">*</span></label><br />
									<input type="number" class="form-control" step="1" min="1" id="thickness" name="thickness" value="23" required>
								</div>
								<div class="col-md-3 form-group">
									<label for="size">Size<span class="text-danger">*</span></label><br />
									<input type="hidden" class="form-control" id="size" name="size" value="1" required>
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_size" style="height:30px;">10 x 2.5&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="selectSize(1,'10 x 2.5' )">10 x 2.5</a></li>
											<li><a onclick="selectSize(2,'9.8 x 2.5' )">9.8 x 2.5</a></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="panel panel-info">
								<div class="panel-heading">
									Rolls
								</div>
								<div class="panel-body">
									<table class="table table-bordered table-hover" width="100%" cellspacing="0">
										<thead>
											<tr class="active">
												<th class="text-center">Roll No</th>
												<th class="text-center">Roll Wt</th>
												<th class="text-center">Roll No</th>
												<th class="text-center">Roll Wt</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1 <span class="text-danger">*</span></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_1" required></td>
												<td>2</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_2"></td>
											</tr>
											<tr>
												<td>3</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_3"></td>
												<td>4</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_4"></td>
											</tr>
											<tr>
												<td>5</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_5"></td>
												<td>6</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_6"></td>
											</tr>
											<tr>
												<td>7</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_7"></td>
												<td>8</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_8"></td>
											</tr>
											<tr>
												<td>9</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_9"></td>
												<td>10</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_10"></td>
											</tr>
											<tr>
												<td>11</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_11"></td>
												<td>12</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_12"></td>
											</tr>
											<tr>
												<td>13</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_13"></td>
												<td>14</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_14"></td>
											</tr>
											<tr>
												<td>15</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_15"></td>
												<td>16</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_16"></td>
											</tr>
										</tbody>
									</table>
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
				function selectShift(id, name) {
					document.getElementById("btn_shift").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("shift").value = id;
					if (id == 1) {
						var d = new Date();
						var month = d.getMonth() + 1;
						document.getElementById("date").value = d.getDate() + "/" + month + "/" + d.getFullYear();
					} else {
						var d = new Date();
						d.setDate(d.getDate() - 1);
						var month = d.getMonth() + 1;
						document.getElementById("date").value = d.getDate() + "/" + month + "/" + d.getFullYear();
					}

				}
				
				function selectMachine(id, name) {
					document.getElementById("machine").value = id;
					document.getElementById("machineName").value = name;
				}

				function selectSize(id, name) {
					document.getElementById("btn_size").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("size").value = id;
				}
				
				function selectColor(id, name) {
					document.getElementById("btn_color").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("color").value = id;
				}
			</script>
			<script>
				$(function() {
					// #datePicker
					$('#datetimepicker').datetimepicker({
						format: 'DD/MM/YYYY'
					});

					$('#timepicker').datetimepicker({
						format: 'HH:mm'
					});
					$('#timepicker2').datetimepicker({
						format: 'HH:mm'
					});

					$('#datetimepicker').data("DateTimePicker").maxDate(new Date());

					$('#datetimepicker2').datetimepicker({
						format: 'DD/MM/YYYY'
					});

					$('#datetimepicker2').data("DateTimePicker").maxDate(new Date());

					var d = new Date();
					var month = d.getMonth() + 1;
					document.getElementById("date").value = d.getDate() + "/" + month + "/" + d.getFullYear();

				})
			</script>

			<?php
    include_once '../../footer.php';
?>