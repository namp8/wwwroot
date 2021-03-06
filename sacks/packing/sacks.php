<?php
    $pageTitle = "Sacks - Packing Sacks";
    
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
			<a href="../process.php">Sacks</a>
		</li>
		<li class="breadcrumb-item active">Sacks</li>
	</ol>
	<h2>Packing - Sacks</h2>


	<div id="alertMessage" class="alert hide" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
		<?php
    if(!empty($_POST['shift']) )
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($sacks->createPackingSacks()){

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
			<button class="btn btn-info dropdown-toggle" type="button"  data-toggle="modal" data-target="#modal1">Submit sacks&nbsp&nbsp</button>
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
					Daily packing sacks
				</div>
				<div class="panel-body">
					<div class="col-md-6">
						<h4>UNITED</h4>
						<div class="table-responsive">
							<table class="table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr class="active">
										<th width="70%">No. of sacks</th>
										<th>Weight</th>
									</tr>
								</thead>
								<tbody>
<?php
     $sacks->givePackingSacks(0, 1);
?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-6">
						<h4>EBONY</h4>
						<div class="table-responsive">
							<table class="table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr class="active">
										<th width="70%">No. of sacks</th>
										<th>Weight</th>
									</tr>
								</thead>
								<tbody>
<?php
     $sacks->givePackingSacks(0, 2);
?>
								</tbody>
							</table>
						</div>
					</div>
					
			</div>
			</div>

		</div>
		<div id="day" class="tab-pane fade">
			<h3>Day</h3>
<div class="panel panel-info">
				<div class="panel-heading">
					Daily packing sacks
				</div>
				<div class="panel-body">
					<div class="col-md-6">
						<h4>UNITED</h4>
						<div class="table-responsive">
							<table class="table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr class="active">
										<th width="70%">No. of sacks</th>
										<th>Weight</th>
									</tr>
								</thead>
								<tbody>
<?php
     $sacks->givePackingSacks(1, 1);
?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-6">
						<h4>EBONY</h4>
						<div class="table-responsive">
							<table class="table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr class="active">
										<th width="70%">No. of sacks</th>
										<th>Weight</th>
									</tr>
								</thead>
								<tbody>
<?php
     $sacks->givePackingSacks(1, 2);
?>
								</tbody>
							</table>
						</div>
					</div>
					
			</div>
			
		</div></div>
		<div id="night" class="tab-pane fade">
			<h3>Night</h3>
			<div class="panel panel-info">
				<div class="panel-heading">
					Daily packing sacks
				</div>
				<div class="panel-body">
					<div class="col-md-6">
						<h4>UNITED</h4>
						<div class="table-responsive">
							<table class="table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr class="active">
										<th width="70%">No. of sacks</th>
										<th>Weight</th>
									</tr>
								</thead>
								<tbody>
<?php
     $sacks->givePackingSacks(2, 1);
?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-6">
						<h4>EBONY</h4>
						<div class="table-responsive">
							<table class="table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr class="active">
										<th width="70%">No. of sacks</th>
										<th>Weight</th>
									</tr>
								</thead>
								<tbody>
<?php
     $sacks->givePackingSacks(2, 2);
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
						<h4 class="modal-title">Submit sacks</h4>
					</div>
					<form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-4 form-group">
									<label for="date">Date <span class="text-danger">*</span></label>
									<div class='input-group date' id='datetimepicker2'>
										<input type='text' class="form-control" id="date" name="date" required  />
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
								<div class="col-md-5 form-group">
									<label for="size">Customer <span class="text-danger">*</span></label><br />
									<input type="hidden" class="form-control" id="customer" name="customer" value="1" required>
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_customer" style="height:30px;">UNITED&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="selectCustomer(1,'UNITED')">UNITED</a></li>
											<li><a onclick="selectCustomer(2,'EBONY')">EBONY</a></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="panel panel-info">
								<div class="panel-heading">
									Sacks
								</div>
								<div class="panel-body">
									<table class="table table-bordered table-hover" width="100%" cellspacing="0">
										<thead>
											<tr class="active">
												<th class="text-center">No. of sacks.</th>
												<th class="text-center">Sack Wt.</th>
												<th class="text-center">No. of sacks.</th>
												<th class="text-center">Sack Wt.</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><input type="number" class="form-control input-sm" step="1" min="1" max="50"  name="no_1" id="no_1" value="50" onkeyup="calculateTotal()"  required></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="400" name="wt_1" id="wt_1" onkeyup="calculateTotal()" required></td>
												<td><input type="number" class="form-control input-sm" step="1" min="0" max="50" name="no_2" id="no_2" value="50" onkeyup="calculateTotal()" ></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="400" name="wt_2" id="wt_2" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td><input type="number" class="form-control input-sm" step="1" min="0" max="50" name="no_3" id="no_3" value="50" onkeyup="calculateTotal()" ></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="400" name="wt_3" id="wt_3" onkeyup="calculateTotal()" ></td>
												<td><input type="number" class="form-control input-sm" step="1" min="0" max="50" name="no_4" id="no_4" value="50" onkeyup="calculateTotal()" ></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="400" name="wt_4" id="wt_4" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												
												<td><input type="number" class="form-control input-sm" step="1" min="0" max="50" name="no_5" id="no_5" value="50" onkeyup="calculateTotal()" ></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="400" name="wt_5" id="wt_5" onkeyup="calculateTotal()" ></td>
												<td><input type="number" class="form-control input-sm" step="1" min="0" max="50" name="no_6" id="no_6" value="50" onkeyup="calculateTotal()" ></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="400" name="wt_6" id="wt_6" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												
												<td><input type="number" class="form-control input-sm" step="1" min="0" max="50" name="no_7" id="no_7" value="50" onkeyup="calculateTotal()" ></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="400" name="wt_7" id="wt_7" onkeyup="calculateTotal()" ></td>
												
												<td><input type="number" class="form-control input-sm" step="1" min="0" max="50" name="no_8" id="no_8" value="50" onkeyup="calculateTotal()" ></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="400" name="wt_8" id="wt_8" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												
												<td><input type="number" class="form-control input-sm" step="1" min="0" max="50" name="no_9" id="no_9" value="50" onkeyup="calculateTotal()" ></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="400" name="wt_9" id="wt_9" onkeyup="calculateTotal()" ></td>
												
												<td><input type="number" class="form-control input-sm" step="1" min="0" max="50" name="no_10" id="no_10" value="50" onkeyup="calculateTotal()" ></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="400" name="wt_10" id="wt_10" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												
												<td><input type="number" class="form-control input-sm" step="1" min="0" max="50" name="no_11" id="no_11" value="50" onkeyup="calculateTotal()" ></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="400" name="wt_11" id="wt_11" onkeyup="calculateTotal()" ></td>
												
												<td><input type="number" class="form-control input-sm" step="1" min="0" max="50" name="no_12" id="no_12" value="50" onkeyup="calculateTotal()" ></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="400" name="wt_12" id="wt_12" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												
												<td><input type="number" class="form-control input-sm" step="1" min="0" max="50" name="no_13" id="no_13" value="50" onkeyup="calculateTotal()" ></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="400" name="wt_13" id="wt_13" onkeyup="calculateTotal()" ></td>
												
												<td><input type="number" class="form-control input-sm" step="1" min="0" max="50"  name="no_14" id="no_14" value="50" onkeyup="calculateTotal()" ></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="400" name="wt_14" id="wt_14" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												
												<td><input type="number" class="form-control input-sm" step="1" min="0" max="50" name="no_15" id="no_15" value="50" onkeyup="calculateTotal()" ></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="400" name="wt_15" id="wt_15" onkeyup="calculateTotal()" ></td>
												<td><input type="number" class="form-control input-sm" step="1" min="0" max="50" name="no_16" id="no_16" value="50" onkeyup="calculateTotal()" ></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="400" name="wt_16" id="wt_16" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td>Total Sacks</td>
												<td><input type="number" class="form-control input-sm" readonly id="total"></td>
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
				
				function calculateTotal() {
					var total = +0;
					for(i = 1; i<17; i++)
					{
						if(document.getElementById("wt_"+i).value !== null && document.getElementById("wt_"+i).value !== '')
						{
							total += Number(document.getElementById("no_"+i).value);
						}
					}
					document.getElementById("total").value = total;
				}
				
				function selectMachine(id, name) {
					document.getElementById("machine").value = id;
					document.getElementById("machineName").value = name;
				}

				function selectSize(id, name) {
					document.getElementById("btn_size").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("size").value = id;
				}
				
				function selectCustomer(id, name) {
					document.getElementById("btn_customer").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("customer").value = id;
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
//<?php 
//						   if(!$users->admin())
//						   {	
//							   echo "if(moment().weekday()==1)
//								{
//									$('#datetimepicker2').data('DateTimePicker').minDate(moment().add(-2, 'days').millisecond(0).second(0).minute(0).hour(0));
//								}
//								else
//								{
//									$('#datetimepicker2').data('DateTimePicker').minDate(moment().add(-1, 'days').millisecond(0).second(0).minute(0).hour(0));
//								}";
//						   }
//					?>	
					$('#datetimepicker2').data("DateTimePicker").maxDate(new Date());

					var d = new Date();
					var month = d.getMonth() + 1;
					document.getElementById("date").value = d.getDate() + "/" + month + "/" + d.getFullYear();

				})
			</script>

			<?php
    include_once '../../footer.php';
?>