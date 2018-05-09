<?php
    $pageTitle = "Injection - Production";
    
    include_once "../base.php";
    include_once "../header.php";
    include_once "sidebar.php";
    include_once "../content.php";


    include_once "../inc/class.injection.inc.php";
    $injection = new Injection($db);

?>
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="../index.php">United Production System</a>
		</li>
		<li class="breadcrumb-item">
			<a href="process.php">Injection</a>
		</li>
		<li class="breadcrumb-item active">Production</li>
	</ol>
	<h2>Injection - Production</h2>


	<div id="alertMessage" class="alert hide" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
		<?php
    if(!empty($_POST['shift']) )
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($injection->createProduction()){

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
			<button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Submit production&nbsp&nbsp<i class="fa fa-caret-down" style="display: inline;"></i></button>
			<ul class="dropdown-menu dropdown-menu-right">
				<li><a onclick="selectMachine(35,'Injection 1')" data-toggle="modal" data-target="#modal1">Injection 1</a></li>
				<li><a onclick="selectMachine(36,'Injection 2')" data-toggle="modal" data-target="#modal1">Injection 2</a></li>
				<li><a onclick="selectMachine(37,'Injection 3')" data-toggle="modal" data-target="#modal1">Injection 3</a></li>
				<li><a onclick="selectMachine(38,'Injection 4')" data-toggle="modal" data-target="#modal1">Injection 4</a></li>
				<li><a onclick="selectMachine(39,'Injection 5')" data-toggle="modal" data-target="#modal1">Injection 5</a></li>
				<li><a onclick="selectMachine(40,'Injection 6')" data-toggle="modal" data-target="#modal1">Injection 6</a></li>
				<li><a onclick="selectMachine(41,'Injection 7')" data-toggle="modal" data-target="#modal1">Injection 7</a></li>
				<li><a onclick="selectMachine(42,'Injection 8')" data-toggle="modal" data-target="#modal1">Injection 8</a></li>
				<li><a onclick="selectMachine(43,'Injection 9')" data-toggle="modal" data-target="#modal1">Injection 9</a></li>
				<li><a onclick="selectMachine(44,'Injection 10')" data-toggle="modal" data-target="#modal1">Injection 9</a></li>
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
					Daily production
				</div>
				<div class="panel-body">
					<table class="table table-bordered table-hover" width="100%" cellspacing="0">
						<thead>
							<tr class="active">
								<th>Product Name</th>
								<th>Type</th>
								<th>Machine</th>
								<th>No. of Cavities</th>
								<th>Produced Qty (Shots)</th>
								<th>Produced Qty (Pcs)</th>
								<th>Produced Waste (Pcs)</th>
								<th>Good Production (Pcs)</th>
								<th>Raw Material Consumption</th>
							</tr>
						</thead>
						<tbody>
							<?php
$injection->giveProduction(0);
?>
						</tbody>
					</table>
			</div>
			</div>

		</div>
		<div id="day" class="tab-pane fade">
			<h3>Day</h3>
<div class="panel panel-info">
				<div class="panel-heading">
					Daily production
				</div>
				<div class="panel-body">
					<table class="table table-bordered table-hover" width="100%" cellspacing="0">
						<thead>
							<tr class="active">
								<th>Product Name</th>
								<th>Type</th>
								<th>Machine</th>
								<th>No. of Cavities</th>
								<th>Produced Qty (Shots)</th>
								<th>Produced Qty (Pcs)</th>
								<th>Produced Waste (Pcs)</th>
								<th>Good Production (Pcs)</th>
								<th>Raw Material Consumption</th>
							</tr>
						</thead>
						<tbody>
							<?php
$injection->giveProduction(1);
?>
						</tbody>
					</table>
					
					
			</div>
			
		</div></div>
		<div id="night" class="tab-pane fade">
			<h3>Night</h3>
			<div class="panel panel-info">
				<div class="panel-heading">
					Daily production
				</div>
				<div class="panel-body">
					<table class="table table-bordered table-hover" width="100%" cellspacing="0">
						<thead>
							<tr class="active">
								<th>Product Name</th>
								<th>Type</th>
								<th>Machine</th>
								<th>No. of Cavities</th>
								<th>Produced Qty (Shots)</th>
								<th>Produced Qty (Pcs)</th>
								<th>Produced Waste (Pcs)</th>
								<th>Good Production (Pcs)</th>
								<th>Raw Material Consumption</th>
							</tr>
						</thead>
						<tbody>
							<?php
$injection->giveProduction(2);
?>
						</tbody>
					</table>
					
			</div>
			</div>

		</div>

		<div class="modal fade" id="modal1" role="dialog" tabindex="-1">
			<div class="modal-dialog" style="width: 600px">
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" type="button" data-dismiss="modal">x</button>
						<h4 class="modal-title">Submit Production</h4>
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
									<input type="text" class="form-control" step="1" min="1" id="machineName"  value="" disabled>
								</div>
								<div class="col-md-6 form-group">
									<label for="material">Product Name <span class="text-danger">*</span></label><br>
									<input type="hidden" class="form-control" id="product" name="product" required>
									<div class="btn-group">
										<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_material">&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu" role="menu" id="dropdown_material">
											<li><input type="text" placeholder="Search material.." class="searchDropdown" id="searchMaterial" onkeyup="filterMaterials()" width="100%"></li>
											<?php
						$injection->semifinishedDropdown();
					?>
										</ul>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label for="shift">Type <span class="text-danger">*</span></label><br />
									<input type="hidden" class="form-control" id="type" name="type" value="-1" required>
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_type" style="height:30px;">Transparent&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="selectType(-1,'Transparent')">Transparent</a></li>
										</ul>
									</div>
								</div>
								<div class="col-md-6 form-group">
									<label for="size">No. of running cavities <span class="text-danger">*</span></label>
									<input type="number" class="form-control input-sm" step="1" min="0" name="cavities" id="cavities" value="2">
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label for="size" class="text-info">Produced Qty in Shots <span class="text-danger">*</span></label>
									<input type="number" class="form-control input-sm" name="shots" id="shots" value="0"  step="1" min="1" required onkeyup="calculateProducedPcs()" >
								</div>
								<div class="col-md-6 form-group">
									<label for="size" class="text-info">Produced Qty in Pcs <span class="text-danger">*</span></label>
									<input type="number" class="form-control input-sm" name="pcs" id="pcs"   value="0"  step="1" min="1" required readonly>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label for="size" class="text-danger">Produced Waste in Kgs <span class="text-danger">*</span></label>
									<input type="text" class="form-control input-sm" name="waste" value="0"  step="0.001" min="1" required>
								</div>
								<div class="col-md-6 form-group">
									<label for="size" class="text-danger">Produced Waste in Pcs<span class="text-danger">*</span></label>
									<input type="number" class="form-control input-sm" name="wastepcs" id="wastepcs"  value="0"  step="1" min="1" required onkeyup="calculateGood()" >
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label for="size" class="text-success">Total Raw material used in kgs<span class="text-danger">*</span></label>
									<input type="number" class="form-control input-sm" name="consumed" value="0"  step="0.001" min="1" required>
								</div>
								<div class="col-md-6 form-group">
									<label for="size" class="text-success">Good production in Pcs <span class="text-danger">*</span></label>
									<input type="text" class="form-control input-sm" name="good" id="good"  value="0"  step="1" min="1" required readonly>
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
				
				function calculateProducedPcs() {
					var shots= document.getElementById('shots').value;
					var cavities= document.getElementById('cavities').value;
					document.getElementById('pcs').value = shots * cavities;
					calculateGood();
				}
				
				function calculateGood() {
					var pcs= document.getElementById('pcs').value;
					var waste= document.getElementById('wastepcs').value;
					document.getElementById('good').value = pcs - waste;
				}
				
				function selectMaterial(id, name, grade) {
					document.getElementById("btn_material").innerHTML = name + " - " + grade+ " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("product").value = id;
				}
				function filterMaterials() {
					var input, filter, ul, li, a, i;
					input = document.getElementById("searchMaterial");
					filter = input.value.toUpperCase();
					div = document.getElementById("dropdown_material");
					a = div.getElementsByTagName("a");
					for (i = 0; i < a.length; i++) {
						if (a[i].id.toUpperCase().startsWith(filter)) {
							a[i].style.display = "";
						} else {
							a[i].style.display = "none";
						}
					}
				}
				
				function selectType(id, name) {
					document.getElementById("btn_type").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("type").value = id;
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
    include_once '../footer.php';
?>