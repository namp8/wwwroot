<?php
    $pageTitle = "Sacks - Cutting Sacks";
    
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
	<h2>Cutting - Sacks</h2>


	<div id="alertMessage" class="alert hide" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
		<?php
    if(!empty($_POST['machine']) )
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($sacks->createSacks()){

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
			<button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Submit sacks&nbsp&nbsp<i class="fa fa-caret-down" style="display: inline;"></i></button>
			<ul class="dropdown-menu dropdown-menu-right">
				<li><a onclick="selectMachine(21,'Cutting 1')" data-toggle="modal" data-target="#modal1">Cutting 1</a></li>
				<li><a onclick="selectMachine(22,'Cutting 2')" data-toggle="modal" data-target="#modal1">Cutting 2</a></li>
				<li><a onclick="selectMachine(23,'Cutting 3')" data-toggle="modal" data-target="#modal1">Cutting 3</a></li>
				<li><a onclick="selectMachine(24,'Cutting 4')" data-toggle="modal" data-target="#modal1">Cutting 4</a></li>
				<li><a onclick="selectMachine(25,'Cutting 5')" data-toggle="modal" data-target="#modal1">Cutting 5</a></li>
				<li><a onclick="selectMachine(26,'Cutting 6')" data-toggle="modal" data-target="#modal1">Cutting 6</a></li>
				<li><a onclick="selectMachine(27,'Cutting 7')" data-toggle="modal" data-target="#modal1">Cutting 7</a></li>
				<li><a onclick="selectMachine(28,'Cutting 8')" data-toggle="modal" data-target="#modal1">Cutting 8</a></li>
				<li><a onclick="selectMachine(29,'Cutting 9')" data-toggle="modal" data-target="#modal1">Cutting 9</a></li>
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
					Daily sacks by machine
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
									<th class="text-center">M/C No. 9</th>
									<th class="text-center">Total</th>
								</tr>
							</thead>
							<tbody>
								<?php
     $sacks->giveSacksTable(0);
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
					Daily sacks by machine
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
									<th class="text-center">M/C No. 9</th>
									<th class="text-center">Total</th>
								</tr>
							</thead>
							<tbody>
								<?php
     $sacks->giveSacksTable(1);
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
					Daily sacks by machine
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
									<th class="text-center">M/C No. 9</th>
									<th class="text-center">Total</th>
								</tr>
							</thead>
							<tbody>
								<?php
     $sacks->giveSacksTable(2);
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
								<div class="col-md-6 form-group">
									<label for="date">Date <span class="text-danger">*</span></label>
									<div class='input-group date' id='datetimepicker2'>
										<input type='text' class="form-control" id="date" name="date" required/>
										<span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
										</span>
									</div>
								</div>
								<div class="col-md-6 form-group">
									<label for="size">Machine <span class="text-danger">*</span></label><br />
									<input type="hidden" class="form-control" id="machine" name="machine" value="1" required>
									<input type="text" class="form-control" step="1" min="1" id="machineName"  value="" disabled>
								</div>
							</div>
							<div class="panel panel-info">
								<div class="panel-heading">
									Shift: Day 
								</div>
								<div class="panel-body">
									
							<div class="row">
								
								<div class="col-md-6 form-group">
									<label for="shift">Operator 1 <span class="text-danger">*</span></label><br />
									<input type="hidden" class="form-control" id="employee1" name="employee1">
									<div class="btn-group">
										<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_employee1">&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu" role="menu" id="dropdown_employee1">
											<li><input type="text" placeholder="Search employee.." class="searchDropdown" id="searchEmployee1" onkeyup="filterEmployee1()" width="100%"></li>
											<?php
	$sacks->operatorsDropdown(1);
?>
										</ul>
									</div>
								</div>
								
								<div class="col-md-6 form-group">
									<label for="shift">Operator 2 </label><br />
									<input type="hidden" class="form-control" id="employee2" name="employee2" >
									<div class="btn-group">
										<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_employee2">&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu" role="menu" id="dropdown_employee2">
											<li><input type="text" placeholder="Search employee.." class="searchDropdown" id="searchEmployee2" onkeyup="filterEmployee2()" width="100%"></li>
											<?php
	$sacks->operatorsDropdown(2);
?>
										</ul>
									</div>
								</div>
							</div>
									<table class="table table-bordered table-hover" width="100%" cellspacing="0">
										<thead>
											<tr class="active">
												<th class="text-center">Sack No</th>
												<th class="text-center">Sack Wt</th>
												<th class="text-center">Sack No</th>
												<th class="text-center">Sack Wt</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1 <span class="text-danger">*</span></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_1" id="wt1_1" onkeyup="calculateTotal()"></td>
												<td>2</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_2" id="wt1_2" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>3</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_3" id="wt1_3" onkeyup="calculateTotal()" ></td>
												<td>4</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_4" id="wt1_4" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>5</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_5" id="wt1_5" onkeyup="calculateTotal()" ></td>
												<td>6</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_6" id="wt1_6" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>7</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_7" id="wt1_7" onkeyup="calculateTotal()" ></td>
												<td>8</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_8" id="wt1_8" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>9</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_9" id="wt1_9" onkeyup="calculateTotal()" ></td>
												<td>10</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_10" id="wt1_10" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>11</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_11" id="wt1_11" onkeyup="calculateTotal()" ></td>
												<td>12</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_12" id="wt1_12" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>13</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_13" id="wt1_13" onkeyup="calculateTotal()" ></td>
												<td>14</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_14" id="wt1_14" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>15</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_15" id="wt1_15" onkeyup="calculateTotal()" ></td>
												<td>16</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_16" id="wt1_16" onkeyup="calculateTotal()" ></td>
											</tr>
											<tr>
												<td>17</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_17" id="wt1_17" onkeyup="calculateTotal()"></td>
												<td>18</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_18" id="wt1_18" onkeyup="calculateTotal()"></td>
											</tr>
											<tr>
												<td>19</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_19" id="wt1_19" onkeyup="calculateTotal()" ></td>
												<td>20</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_20" id="wt1_20" onkeyup="calculateTotal()"></td>
											</tr>
											<tr>
												<td>21</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_21" id="wt1_21" onkeyup="calculateTotal()" ></td>
												<td>22</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_22" id="wt1_22" onkeyup="calculateTotal()"></td>
											</tr>
											<tr>
												<td>23</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_23" id="wt1_23" onkeyup="calculateTotal()" ></td>
												<td>24</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt1_24" id="wt1_24" onkeyup="calculateTotal()"></td>
											</tr>
											<tr>
												<td>Cutting waste</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="film1" id="film1" onkeyup="calculateWaste()"></td>
												<td>Roll waste</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="block1" id="block1" onkeyup="calculateWaste()"></td>
											</tr>
										</tbody>
									</table>
									<div class="col-md-6 form-group">
									<label for="size">Total gross weight</label><br />
									<td><input type="number" class="form-control input-sm" step="0.01" min="0"  id="total1"  readonly></td>
									</div>
									
									<div class="col-md-6 form-group">
									<label for="size">Total waste</label><br />
									<td><input type="number" class="form-control input-sm" step="0.01" min="0"  id="waste1"  readonly></td>
									</div>
								</div>
							</div>
							<div class="panel panel-info">
								<div class="panel-heading">
									Shift: Night
								</div>
								<div class="panel-body">
											
							<div class="row">
								
								<div class="col-md-6 form-group">
									<label for="shift">Operator 1 <span class="text-danger">*</span></label><br />
									<input type="hidden" class="form-control" id="employee3" name="employee3" >
									<div class="btn-group">
										<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_employee3">&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu" role="menu" id="dropdown_employee1">
											<li><input type="text" placeholder="Search employee.." class="searchDropdown" id="searchEmployee1" onkeyup="filterEmployee1()" width="100%"></li>
											<?php
	$sacks->operatorsDropdown(3);
?>
										</ul>
									</div>
								</div>
								
								<div class="col-md-6 form-group">
									<label for="shift">Operator 2 </label><br />
									<input type="hidden" class="form-control" id="employee4" name="employee4" >
									<div class="btn-group">
										<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_employee4">&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu" role="menu" id="dropdown_employee2">
											<li><input type="text" placeholder="Search employee.." class="searchDropdown" id="searchEmployee2" onkeyup="filterEmployee2()" width="100%"></li>
											<?php
	$sacks->operatorsDropdown(4);
?>
										</ul>
									</div>
								</div>
							</div>
									<table class="table table-bordered table-hover" width="100%" cellspacing="0">
										<thead>
											<tr class="active">
												<th class="text-center">Sack No</th>
												<th class="text-center">Sack Wt</th>
												<th class="text-center">Sack No</th>
												<th class="text-center">Sack Wt</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1 <span class="text-danger">*</span></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_1" id="wt2_1" onkeyup="calculateTotal2()" ></td>
												<td>2</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_2" id="wt2_2" onkeyup="calculateTotal2()" ></td>
											</tr>
											<tr>
												<td>3</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_3" id="wt2_3" onkeyup="calculateTotal2()" ></td>
												<td>4</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_4" id="wt2_4" onkeyup="calculateTotal2()" ></td>
											</tr>
											<tr>
												<td>5</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_5" id="wt2_5" onkeyup="calculateTotal2()" ></td>
												<td>6</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_6" id="wt2_6" onkeyup="calculateTotal2()" ></td>
											</tr>
											<tr>
												<td>7</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_7" id="wt2_7" onkeyup="calculateTotal2()" ></td>
												<td>8</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_8" id="wt2_8" onkeyup="calculateTotal2()" ></td>
											</tr>
											<tr>
												<td>9</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_9" id="wt2_9" onkeyup="calculateTotal2()" ></td>
												<td>10</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_10" id="wt2_10" onkeyup="calculateTotal2()" ></td>
											</tr>
											<tr>
												<td>11</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_11" id="wt2_11" onkeyup="calculateTotal2()" ></td>
												<td>12</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_12" id="wt2_12" onkeyup="calculateTotal2()" ></td>
											</tr>
											<tr>
												<td>13</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_13" id="wt2_13" onkeyup="calculateTotal2()" ></td>
												<td>14</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_14" id="wt2_14" onkeyup="calculateTotal2()" ></td>
											</tr>
											<tr>
												<td>15</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_15" id="wt2_15" onkeyup="calculateTotal2()" ></td>
												<td>16</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_16" id="wt2_16" onkeyup="calculateTotal2()" ></td>
											</tr>
											<tr>
												<td>17</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_17" id="wt2_17" onkeyup="calculateTotal2()"></td>
												<td>18</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_18" id="wt2_18" onkeyup="calculateTotal2()"></td>
											</tr>
											<tr>
												<td>19</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_19" id="wt2_19" onkeyup="calculateTotal2()" ></td>
												<td>20</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_20" id="wt2_20" onkeyup="calculateTotal2()"></td>
											</tr>
											<tr>
												<td>21</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_21" id="wt2_21" onkeyup="calculateTotal2()" ></td>
												<td>22</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_22" id="wt2_22" onkeyup="calculateTotal2()"></td>
											</tr>
											<tr>
												<td>23</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_23" id="wt2_23" onkeyup="calculateTotal2()" ></td>
												<td>24</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="wt2_24" id="wt2_24" onkeyup="calculateTotal2()"></td>
											</tr>
											<tr>
												<td>Cutting waste</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="film2" id="film2" onkeyup="calculateWaste2()"></td>
												<td>Roll waste</td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" max="50" name="block2" id="block2" onkeyup="calculateWaste2()"></td>
											</tr>
										</tbody>
									</table>
									<div class="col-md-6 form-group">
									<label for="size">Total gross weight</label><br />
									<td><input type="number" class="form-control input-sm" step="0.01" min="0"  id="total2"  readonly></td>
									</div>
									
									<div class="col-md-6 form-group">
									<label for="size">Total waste</label><br />
									<td><input type="number" class="form-control input-sm" step="0.01" min="0"  id="waste2"  readonly></td>
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
					for(i = 1; i<25; i++)
					{
						if(document.getElementById("wt1_"+i).value !== null && document.getElementById("wt1_"+i).value !== '')
						{
							total += Number(document.getElementById("wt1_"+i).value);
						}
					}
					document.getElementById("total1").value = total;
				}
				function calculateWaste() {
					var total = +0;
					total += Number(document.getElementById("film1").value);
					total += Number(document.getElementById("block1").value);
					document.getElementById("waste1").value = total;
				}
				
				function calculateWaste2() {
					var total = +0;
					total += Number(document.getElementById("film2").value);
					total += Number(document.getElementById("block2").value);
					document.getElementById("waste2").value = total;
				}
				function calculateTotal2() {
					var total = +0;
					for(i = 1; i<25; i++)
					{
						if(document.getElementById("wt2_"+i).value !== null && document.getElementById("wt2_"+i).value !== '')
						{
							total += Number(document.getElementById("wt2_"+i).value);
						}
					}
					document.getElementById("total2").value = total;
				}
				
				function selectEmployee1(id, name) {
					document.getElementById("btn_employee1").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("employee1").value = id;
				}
				
				function selectEmployee2(id, name) {
					document.getElementById("btn_employee2").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("employee2").value = id;
				}
				function selectEmployee3(id, name) {
					document.getElementById("btn_employee3").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("employee3").value = id;
				}
				
				function selectEmployee4(id, name) {
					document.getElementById("btn_employee4").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("employee4").value = id;
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
				
				function filterEmployee2() {
					var input, filter, ul, li, a, i;
					input = document.getElementById("searchEmployee2");
					filter = input.value.toUpperCase();
					div = document.getElementById("dropdown_employee2");
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
									$('#datetimepicker2').data('DateTimePicker').minDate(moment().add(-4, 'days').millisecond(0).second(0).minute(0).hour(0));
								}
								else
								{
									$('#datetimepicker2').data('DateTimePicker').minDate(moment().add(-3, 'days').millisecond(0).second(0).minute(0).hour(0));
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