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
    if(!empty($_POST['shift']) )
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($slitting->createRolls()){

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
			<button class="btn btn-info" type="button" data-toggle="modal" data-target="#modal1">Submit rolls</button>
			
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
								<div class="col-md-6 form-group">
									<label for="date">Date <span class="text-danger">*</span></label>
									<div class='input-group date' id='datetimepicker2'>
										<input type='text' class="form-control" id="date" name="date" required/>
										<span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
										</span>
									</div>
								</div>
								<div class="col-md-6  form-group">
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
								 <div class="col-lg-6 form-group">
                                    <label for="customer">Customer</label><br />
                                    <input type="hidden" class="form-control" id="customer" name="customer">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_customer">&nbsp&nbsp<span class="caret"></span></button>
                                        <ul class="dropdown-menu" id="dropdown_customer">
                                            <li><input type="text" placeholder="Search customer.." class="searchDropdown" id="searchCustomer" onkeyup="filterCustomers()" width="100%"></li>
                                            <?php
    $slitting->customersDropdown();
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
												<th class="text-center">Roll No.</th>
												<th class="text-center">Roll Wt.</th>
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
											
											<tr>
												<td>21</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_21"></td>
												<td>22</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_22"></td>
											</tr>
											<tr>
												<td>23</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_23"></td>
												<td>24</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_24"></td>
											</tr>
											<tr>
												<td>25</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_25"></td>
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
				 function selectCustomer(id, name) {
                    document.getElementById("btn_customer").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
                    document.getElementById("customer").value = id;
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

					var d = new Date();
					var month = d.getMonth() + 1;
					document.getElementById("date").value = d.getDate() + "/" + month + "/" + d.getFullYear();

				})
			</script>

			<?php
    include_once '../../footer.php';
?>