<?php
    $pageTitle = "Sacks - Extruder Rolls";
    
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar.php";
    include_once "../../content.php";


    include_once "../../inc/class.sacks.inc.php";
    $sacks = new Sacks($db);
	include_once "../../inc/class.users.inc.php";
    $users = new Users($db);

?>
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="../../index.php">United Production System</a>
		</li>
		<li class="breadcrumb-item">
			<a href="../process.php">Sacks </a>
		</li>
		<li class="breadcrumb-item active">Extruder - Rolls</li>
	</ol>
	<h2>Sacks - Extruder - Rolls</h2>


	<div id="alertMessage" class="alert hide" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
		<?php
    if(!empty($_POST['shift']) )
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($sacks->createRolls()){

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
				<li><a onclick="selectMachine(13,'Extruder 1')" data-toggle="modal" data-target="#modal1">Extruder 1</a></li>
				<li><a onclick="selectMachine(14,'Extruder 2')" data-toggle="modal" data-target="#modal1">Extruder 2</a></li>
				<li><a onclick="selectMachine(15,'Extruder 3')" data-toggle="modal" data-target="#modal1">Extruder 3</a></li>
				<li><a onclick="selectMachine(16,'Extruder 4')" data-toggle="modal" data-target="#modal1">Extruder 4</a></li>
				<li><a onclick="selectMachine(17,'Extruder 5')" data-toggle="modal" data-target="#modal1">Extruder 5</a></li>
				<li><a onclick="selectMachine(18,'Extruder 6')" data-toggle="modal" data-target="#modal1">Extruder 6</a></li>
				<li><a onclick="selectMachine(19,'Extruder 7')" data-toggle="modal" data-target="#modal1">Extruder 7</a></li>
				<li><a onclick="selectMachine(20,'Extruder 8')" data-toggle="modal" data-target="#modal1">Extruder 8</a></li>
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

					<div class="table-responsive">
						<table class="table table-bordered table-hover" width="100%" cellspacing="0">
							<thead>
								<tr class="active text-center">
									<th></th>
									<th class="text-center">M/C No. 1</th>
									<th class="text-center">M/C No. 2</th>
									<th class="text-center">M/C No. 3</th>
									<th class="text-center">M/C No. 4</th>
									<th class="text-center">M/C No. 5</th>
									<th class="text-center">M/C No. 6</th>
									<th class="text-center">M/C No. 7</th>
									<th class="text-center">M/C No. 8</th>
									<th class="text-center">Total</th>
								</tr>
							</thead>
							<tbody>
								<?php
     $sacks->giveRollsTable(0);
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
					Daily rolls by machine
				</div>
				<div class="panel-body">
					
					<div class="table-responsive">
						<table class="table table-bordered table-hover" width="100%" cellspacing="0">
							<thead>
								<tr class="active text-center">
									<th></th>
									<th class="text-center">M/C No. 1</th>
									<th class="text-center">M/C No. 2</th>
									<th class="text-center">M/C No. 3</th>
									<th class="text-center">M/C No. 4</th>
									<th class="text-center">M/C No. 5</th>
									<th class="text-center">M/C No. 6</th>
									<th class="text-center">M/C No. 7</th>
									<th class="text-center">M/C No. 8</th>
									<th class="text-center">Total</th>
								</tr>
							</thead>
							<tbody>
								<?php
     $sacks->giveRollsTable(1);
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
					Daily rolls by machine
				</div>
				<div class="panel-body">
					
					<div class="table-responsive">
						<table class="table table-bordered table-hover" width="100%" cellspacing="0">
							<thead>
								<tr class="active text-center">
									<th></th>
									<th class="text-center">M/C No. 1</th>
									<th class="text-center">M/C No. 2</th>
									<th class="text-center">M/C No. 3</th>
									<th class="text-center">M/C No. 4</th>
									<th class="text-center">M/C No. 5</th>
									<th class="text-center">M/C No. 6</th>
									<th class="text-center">M/C No. 7</th>
									<th class="text-center">M/C No. 8</th>
									<th class="text-center">Total</th>
								</tr>
							</thead>
							<tbody>
								<?php
     $sacks->giveRollsTable(2);
?>
							</tbody>
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
										<input type='text' class="form-control" id="date" name="date" required />
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
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label for="size">Machine <span class="text-danger">*</span></label><br />
									<input type="hidden" class="form-control" id="machine" name="machine" value="1" required>
									<input type="text" class="form-control" step="1" min="1" id="machineName" value="" disabled>
								</div>
								<div class="col-md-3 form-group">
									<label for="size">Thickness (µ)<span class="text-danger">*</span></label><br />
									<input type="number" class="form-control" step="1" min="1" id="thickness" name="thickness" value="6" required>
								</div>
								<div class="col-md-3 form-group">
									<label for="size">Size<span class="text-danger">*</span></label><br />
									<input type="hidden" class="form-control" id="size" name="size" value="1" required>
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_size" style="height:30px;">6 1/2&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="selectSize(1,'6 1/2' )">6 1/2</a></li>
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
											<tr>
												<td>17</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_17"></td>
												<td>18</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_18"></td>
											</tr>
											<tr>
												<td>19</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_19"></td>
												<td>20</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_20"></td>
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
						format: 'DD/MM/YYYY',
						defaultDate: moment()
					});

					$('#timepicker').datetimepicker({
						format: 'HH:mm'
					});
					$('#timepicker2').datetimepicker({
						format: 'HH:mm'
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

				})
			</script>

			<?php
    include_once '../../footer.php';
?>