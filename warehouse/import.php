<?php
    $pageTitle = "Warehouse - Import";
    include_once "../base.php";
    include_once "../header.php";
    include_once "sidebarwarehouse.php";
    include_once "../content.php";


    include_once "../inc/class.stock.inc.php";
    $stock = new Stock($db);


    include_once "../inc/class.materials.inc.php";
    $materials = new Materials($db);

	if(!$stock->access(1))
	{
		echo "<meta http-equiv='refresh' content='0;/index.php'>";
        exit;
	}
?>
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="../index.php">United Production System</a>
		</li>
		<li class="breadcrumb-item">
			<a href="home.php">Warehouse</a>
		</li>
		<li class="breadcrumb-item active">Import</li>
	</ol>
	<h2>Warehouse - Raw Material Import</h2>

	<div id="alertMessage" class="alert hide" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
		<?php
    if($_SERVER["REQUEST_METHOD"] == "POST" and !empty($_POST['material']))
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($stock->openFileRMI()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
	else if(!empty($_POST['rmino1']))
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($stock->shipRMI()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
	else if(!empty($_POST['rmino2']))
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($stock->clearRMI()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
	else if(!empty($_POST['rmino3']))
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($stock->paidRMI()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
	else if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		echo '<strong>ERROR</strong> The material is a required field. Please try again, selecting a material from the dropdown.<br>';
		echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
		
	}
?>
	</div>
	<ul class="nav nav-tabs nav-justified">
		<li class="active"><a data-toggle="tab" href="#Ordered" onclick="document.getElementById('titleReport').innerHTML = 'Raw Material Import Ordered'"><i class="fa fa-file-powerpoint-o fa-fw" aria-hidden="true" ></i><span>Ordered</span></a></li>
		<li><a data-toggle="tab" href="#Shipped" onclick="document.getElementById('titleReport').innerHTML = 'Raw Material Import Shipped'"><i class="fa fa-ship fa-fw" aria-hidden="true"></i><span>Shipped</span></a></li>
		<li><a data-toggle="tab" href="#Cleared" onclick="document.getElementById('titleReport').innerHTML = 'Raw Material Import Cleared to Spintex Factory'"><i class="fa fa-check-square-o fa-fw" aria-hidden="true"></i><span>Cleared</span></a></li>
		<li><a data-toggle="tab" href="#Paid" onclick="document.getElementById('titleReport').innerHTML = 'Raw Material Import Paid this month'"><i class="fa fa-money fa-fw" aria-hidden="true"></i><span>Paid</span></a></li>
	</ul>
	<h4 id="titleReport" style="display:none;">Raw Material Import Orders</h4>
	<div class="tab-content">
		<div id="Ordered" class="tab-pane fade in active">

			<button class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" onclick="exportToPDF('dataTable')">Export to PDF</button>
			<button class="btn btn-info pull-right" style="margin-top:5px;margin-right:15px;" data-toggle="modal" data-target="#modal1">Open File</button>

			<div class="panel panel-info">
				<div class="panel-heading">Raw Material Import Ordered</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
							<thead>
								<tr class="active">
									<th>File No.</th>
									<th>Expected Date of Shipment</th>
									<th>Material</th>
									<th>Supplier</th>
									<th>Bags / Drumps</th>
									<th>Amount</th>
									<th>Proforma Invoice No.</th>
									<th>Proforma Invoice Date</th>
									<th>Manufacturer</th>
									<th>Submitted by</th>
									<th>Remarks</th>
								</tr>
							</thead>
							<tbody>
								<?php  
    $stock->giveRMIordersWarehouse();
?>
							</tbody>
							<tfoot>
								<tr class="active">
									<th></th>
									<th></th>
									<th></th>
									<th >Total</th>
									<th style="text-align:right"></th>
									<th style="text-align:right"></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div id="Shipped" class="tab-pane fade ">
			<button class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" onclick="exportToPDF('dataTable2')">Export to PDF</button>
			<button class="btn btn-info pull-right" style="margin-top:5px;margin-right:15px;" data-toggle="modal" data-target="#modal2">Add shipment information</button>

			<div class="panel panel-info">
				<div class="panel-heading">Raw Material Import Shipped</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="dataTable2" width="100%" cellspacing="0">
							<thead>
								<tr class="active">
									<th>File No.</th>
									<th>Expected Date of Arrival</th>
									<th>Bill Due Date</th>
									<th>Amount</th>
									<th>Material</th>
									<th>Supplier</th>
									<th>Bags / Drumps</th>
									<th>Date of Shipment</th>
									<th>Shipment Delay</th>
									<th>Bill of Lading No.</th>
									<th>Commercial Invoice No.</th>
									<th>Terms of Payment (days)</th>
									<th>Submitted by</th>
									<th>Remarks</th>
								</tr>
							</thead>
							<tbody>
								<?php  
    $stock->giveRMIshipsWarehouse();
?>
							</tbody>
							<tfoot>
								<tr class="active">
									<th></th>
									<th></th>
									<th >Total</th>
									<th style="text-align:right"></th>
									<th></th>
									<th >Total</th>
									<th style="text-align:right"></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div id="Paid" class="tab-pane fade ">
			<button class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" onclick="exportToPDF('dataTable4')">Export to PDF</button>
			<button class="btn btn-info pull-right" style="margin-top:5px;margin-right:15px;" data-toggle="modal" data-target="#modal4">Add payment information</button>

			<div class="panel panel-info">
				<div class="panel-heading">Raw Material Import Paid this month</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="dataTable4" width="100%" cellspacing="0">
							<thead>
								<tr class="active">
									<th>File No.</th>
									<th>Material</th>
									<th>Supplier</th>
									<th>Bags / Drumps</th>
									<th>Amount</th>
									<th>Letter to bank Date</th>
									<th>Bank paid on</th>
									<th>Payment Delay</th>
									<th>Submitted by</th>
									<th>Remarks</th>
								</tr>
							</thead>
							<tbody>
								<?php  
    $stock->giveRMIpaymentsWarehouse();
?>
							</tbody>
							<tfoot>
								<tr class="active">
									<th></th>
									<th></th>
									<th>Total</th>
									<th style="text-align:right"></th>
									<th style="text-align:right"></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div id="Cleared" class="tab-pane fade ">
			<button class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" onclick="exportToPDF('dataTable3')">Export to PDF</button>
			<button class="btn btn-info pull-right" style="margin-top:5px;margin-right:15px;" data-toggle="modal" data-target="#modal3">Add clearing information</button>

			<div class="panel panel-info">
				<div class="panel-heading">Raw Material Import Cleared to Spintex Factory</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="dataTable3" width="100%" cellspacing="0">
							<thead>
								<tr class="active">
									<th>File No.</th>
									<th>Bill Due Date</th>
									<th>Amount</th>
									<th>Material</th>
									<th>Supplier</th>
									<th>Bags / Drumps</th>
									<th>Damaged Bags / Drumps</th>
									<th>Date Cleared to factory</th>
									<th>Arrival Delay</th>
									<th>Declaration No.</th>
									<th>Customs duty (USD)</th>
									<th>Clearing cost (USD)</th>
									<th>Offloading cost (USD)</th>
									<th>Total (USD)</th>
									<th>Cost by Kg (USD)</th>
									<th>Submitted by</th>
									<th>Remarks</th>
								</tr>
							</thead>
							<tbody>
								<?php  
    $stock->giveRMIclearingsWarehouse();
?>
							</tbody>
							<tfoot>
								<tr class="active">
									<th></th>
									<th>Total</th>
									<th style="text-align:right"></th>
									<th></th>
									<th>Total</th>
									<th style="text-align:right"></th>
									<th style="text-align:right"></th>
									<th></th>
									<th></th>
									<th>Total</th>
									<th style="text-align:right"></th>
									<th style="text-align:right"></th>
									<th style="text-align:right"></th>
									<th style="text-align:right"></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>



	<!-- Open File Modal-->
	<div class="modal fade" id="modal1" role="dialog" tabindex="-1">
		<div class="modal-dialog" style="width:600px;">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">x</button>
					<h4 class="modal-title">Open File for Raw Material Import</h4>
				</div>
				<form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<div class="modal-body">
						<div class="row">
							<div class="form-group col-md-6">
								<label for="date">File No. <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="rmi" name="rmi" value="<?php $stock->giveRawMaterialImportCode(); ?>" required/>
							</div>

							<div class="form-group col-md-6">
								<label for="material">Material <span class="text-danger">*</span></label>
								<input type="hidden" class="form-control" id="material" name="material" required>
								<input type="hidden" class="form-control" id="kg_material" name="kg_material" required><br>
								<div class="btn-group">
									<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_material">&nbsp&nbsp<span class="caret"></span></button>
									<ul class="dropdown-menu" role="menu" id="dropdown_material">
										<li><input type="text" placeholder="Search material.." class="searchDropdown" id="searchMaterial" onkeyup="filterMaterials()" width="100%"></li>
										<?php
        $materials->materialsKgDropdown();
    ?>
									</ul>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label for="date">ProForma Invoice No. <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="pino" name="pino" required/>
							</div>
							<div class="form-group col-md-6">
								<label for="date">ProForma Invoice Date <span class="text-danger">*</span></label>
								<div class='input-group date' id='datetimepicker2'>
									<input type='text' class="form-control" id="pidate" name="pidate" required/>
									<span class="input-group-addon">
									<span class="fa fa-calendar"></span>
									</span>
								</div>
							</div>

						</div>

						<div class="row">
							<div class="form-group col-md-6">
								<label for="date">Supplier <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="supplier" name="supplier" required/>
							</div>

							<div class="form-group col-md-6">
								<label for="date">Manufacturer</label>
								<input type="text" class="form-control" id="manufacturer" name="manufacturer" />
							</div>

							<div class="form-group col-md-6">
								<label for="date">Expected Date of Shipment</label>
								<div class='input-group date' id='datetimepicker'>
									<input type='text' class="form-control" id="exdateship" name="exdateship" />
									<span class="input-group-addon">
                            <span class="fa fa-calendar"></span>
									</span>
								</div>
							</div>
							<div class="form-group col-md-6">
								<label for="total">Amount (USD)<span class="text-danger">*</span></label>
								<input type="number" class="form-control" min="1" step="0.01" id="amount" name="amount" required>
							</div>
						</div>

						<div class="row">
							<div class="form-group col-md-4">
								<label for="total">Qty MT <span class="text-danger">*</span></label>
								<input type="number" class="form-control" min="0.025" step="0.001" id="mt" name="mt" onkeyup="calculateQty()" required>
							</div>
							<div class="form-group col-md-4">
								<label for="total">Qty Kgs</label>
								<input type="text" class="form-control" min="1" id="kgs" name="kgs" disabled>
							</div>
							<div class="form-group col-md-4">
								<label for="total">Qty Bags/Drumps</label>
								<input type="text" class="form-control" min="1" id="bags" name="bags" readonly="readonly">
							</div>

						</div>
						<div class="form-group">
							<label>Remarks</label>
							<textarea type="text" class="form-control" rows="3" id="remarks" name="remarks"></textarea>
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

	<!-- Shipment Modal-->
	<div class="modal fade" id="modal2" role="dialog" tabindex="-1">
		<div class="modal-dialog" style="width:600px;">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">x</button>
					<h4 class="modal-title">Add shipment information for Raw Material Import</h4>
				</div>
				<form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<div class="modal-body">
						<div class="row">
							<div class="form-group col-md-6">
								<label for="date">File No. <span class="text-danger">*</span></label>
								<input type="hidden" class="form-control" id="rmino1" name="rmino1" required><br>
								<div class="btn-group">
									<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_rmino1">&nbsp&nbsp<span class="caret"></span></button>
									<ul class="dropdown-menu" role="menu" id="dropdown_rmino1">
										<li><input type="text" placeholder="Search File No.." class="searchDropdown" id="searchRmino1" onkeyup="filterRmino1()" width="100%"></li>
										<?php
        $stock->RMIordersDropdown();
    ?>
									</ul>
								</div>
							</div>

							<div class="form-group col-md-6">
								<label for="material">Expected date of Shipment</label>
								<input type="text" class="form-control" id="exdateship1" name="exdateship1" disabled>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label for="date">Bill of Lading No. <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="blno" name="blno" required/>
							</div>
							<div class="form-group col-md-6">
								<label for="date">Bill of Lading Date <span class="text-danger">*</span></label>
								<div class='input-group date' id='datetimepicker3'>
									<input type='text' class="form-control" id="bldate" name="bldate" onblur="calculateDelayLading()" required/>
									<span class="input-group-addon">
									<span class="fa fa-calendar"></span>
									</span>
								</div>
							</div>

						</div>

						<div class="row">
							<div class="form-group col-md-6">
								<label for="date">Commercial Invoice No. <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="cino" name="cino" required/>
							</div>

							<div class="form-group col-md-6">
								<label for="date">Shipment Delay (days)</label>
								<input type="text" class="form-control" id="delay1" name="delay1" readonly="readonly" />
							</div>
							<div class="form-group col-md-6">
								<label for="total">Expected days for Arrival<span class="text-danger">*</span></label>
								<input type="number" class="form-control" min="1" step="1" id="arrdays" name="arrdays" value="45" onkeyup="calculateArrival()" required>
							</div>
							<div class="form-group col-md-6">
								<label for="date">Expected Date of Arrival</label>
								<div class='input-group date' id='datetimepicker4'>
									<input type='text' class="form-control" id="exdatearr" name="exdatearr" readonly="readonly" />
									<span class="input-group-addon">
                            <span class="fa fa-calendar"></span>
									</span>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="form-group col-md-6">
								<label for="total">Terms of Payment (days)<span class="text-danger">*</span></label>
								<input type="number" class="form-control" min="1" step="1" id="terms" name="terms" value="120" onkeyup="calculateDueDate()" required>
							</div>
							<div class="form-group col-md-6">
								<label for="total">Bill Due Date</label>
								<input type="text" class="form-control" id="duedate" name="duedate" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label>Remarks</label>
							<textarea type="text" class="form-control" rows="3" id="remarks1" name="remarks1"></textarea>
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

	<!-- Clearing Modal-->
	<div class="modal fade" id="modal3" role="dialog" tabindex="-1">
		<div class="modal-dialog" style="width:600px;">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">x</button>
					<h4 class="modal-title">Clear Raw Material Import</h4>
				</div>
				<form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<div class="modal-body">
						<div class="row">
							<div class="form-group col-md-4">
								<label for="date">File No. <span class="text-danger">*</span></label>
								<input type="hidden" class="form-control" id="rmino2" name="rmino2" required><br>
								<div class="btn-group">
									<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_rmino2">&nbsp&nbsp<span class="caret"></span></button>
									<ul class="dropdown-menu" role="menu" id="dropdown_rmino2">
										<li><input type="text" placeholder="Search File No.." class="searchDropdown" id="searchRmino2" onkeyup="filterRmino2()" width="100%"></li>
										<?php
        $stock->RMIshipmentsDropdown();
    ?>
									</ul>
								</div>
							</div>

							<div class="form-group col-md-4">
								<label for="material">Expected date of Arrival</label>
								<input type="text" class="form-control" id="exdatearr1" name="exdatearr1" disabled>
							</div>
							
							<div class="form-group col-md-4">
								<label for="date">Arrival Delay (days)</label>
								<input type="text" class="form-control" id="delay2" name="delay2" readonly="readonly" />
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label for="date">First Date cleared to Factory <span class="text-danger">*</span></label>
								<div class='input-group date' id='datetimepicker5'>
									<input type='text' class="form-control" id="date_cleared" name="date_cleared" onblur="calculateDelayArrival()" required/>
									<span class="input-group-addon">
									<span class="fa fa-calendar"></span>
									</span>
								</div>
							</div>
							<div class="form-group col-md-6">
								<label for="total">First Quantity cleared<span class="text-danger">*</span></label>
								<input type="number" class="form-control" min="1" step="1" id="qtycleared" name="qtycleared" required>
							</div>
							<div class="form-group col-md-6">
								<label for="date">Second Date cleared to Factory</label>
								<div class='input-group date' id='datetimepicker5'>
									<input type='text' class="form-control" id="date_cleared2" name="date_cleared2"/>
									<span class="input-group-addon">
									<span class="fa fa-calendar"></span>
									</span>
								</div>
							</div>
							<div class="form-group col-md-6">
								<label for="total">Second Quantity cleared</label>
								<input type="number" class="form-control" min="1" step="1" id="qtycleared2" name="qtycleared2">
							</div>
							<div class="form-group col-md-6">
								<label for="date">Declaration No <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="declaration" name="declaration" required/>
							</div>
							<div class="form-group col-md-6">
								<label for="total">Damaged bags / drumps <span class="text-danger">*</span></label>
								<input type="number" class="form-control" min="0" step="1" id="damaged" name="damaged" required>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-4">
								<label for="total">Rate of Exchange<span class="text-danger">*</span></label>
								<input type="number" class="form-control" min="1" step="0.0001" id="rate" name="rate" value="4.5" onkeyup="calculateAll()" required>
							</div>
							<div class="form-group col-md-4">
								<label for="total">Customs duty (GHc)<span class="text-danger">*</span></label>
								<input type="number" class="form-control" min="1" step="0.01" id="dutyghc" name="dutyghc" onkeyup="calculateDuty()" required>
							</div>
							<div class="form-group col-md-4">
								<label for="total">Customs duty (USD)<span class="text-danger">*</span></label>
								<input type="number" class="form-control" min="1" step="1" id="duty" name="duty" readonly="readonly">
							</div>
							<div class="form-group col-md-4">
								<label for="total">Grand Total Emmdoray<span class="text-danger">*</span></label>
								<input type="number" class="form-control" min="1" step="0.01" id="invoice" name="invoice" onkeyup="calculateClearing()" required>
							</div>
							<div class="form-group col-md-4">
								<label for="total">Clearing cost (GHc)<span class="text-danger">*</span></label>
								<input type="number" class="form-control" min="1" step="0.01" id="clearingghc" name="clearingghc" disabled>
							</div>
							<div class="form-group col-md-4">
								<label for="total">Clearing cost (USD)<span class="text-danger">*</span></label>
								<input type="number" class="form-control" min="1" step="1" id="clearing" name="clearing" readonly="readonly">
							</div>
							<div class="form-group col-md-4">
								<label for="total">No. of containers (20 ft)<span class="text-danger">*</span></label>
								<input type="number" class="form-control" min="1" step="1" id="containers" name="containers" onkeyup="calculateUnloading()" required>
							</div>
							<div class="form-group col-md-4">
								<label for="total">Offloading cost (GHc)<span class="text-danger">*</span></label>
								<input type="number" class="form-control" min="1" step="1" id="unloadingghc" name="unloadingghc" disabled>
							</div>
							<div class="form-group col-md-4">
								<label for="total">Offloading cost (USD)<span class="text-danger">*</span></label>
								<input type="number" class="form-control" min="1" step="1" id="unloading" name="unloading" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label>Remarks</label>
							<textarea type="text" class="form-control" rows="3" id="remarks2" name="remarks2"></textarea>
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

	<!-- Payment Modal-->
	<div class="modal fade" id="modal4" role="dialog" tabindex="-1">
		<div class="modal-dialog" style="width:600px;">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">x</button>
					<h4 class="modal-title">Paid Raw Material Import</h4>
				</div>
				<form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<div class="modal-body">
						<div class="row">
							<div class="form-group col-md-6">
								<label for="date">File No. <span class="text-danger">*</span></label>
								<input type="hidden" class="form-control" id="rmino3" name="rmino3" required><br>
								<div class="btn-group">
									<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_rmino3">&nbsp&nbsp<span class="caret"></span></button>
									<ul class="dropdown-menu" role="menu" id="dropdown_rmino3">
										<li><input type="text" placeholder="Search File No.." class="searchDropdown" id="searchRmino3" onkeyup="filterRmino3()" width="100%"></li>
										<?php
        $stock->RMIclearingsDropdown();
    ?>
									</ul>
								</div>
							</div>

							<div class="form-group col-md-6">
								<label for="material">Bill Due Date</label>
								<input type="text" class="form-control" id="duedate1" name="duedate1" disabled>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label for="date">Letter to Bank <span class="text-danger">*</span></label>
								<div class='input-group date' id='datetimepicker6'>
									<input type='text' class="form-control" id="bank_date" name="bank_date" />
									<span class="input-group-addon">
									<span class="fa fa-calendar"></span>
									</span>
								</div>
							</div>
							<div class="form-group col-md-6">
								<label for="date">Bank Paid On <span class="text-danger">*</span></label>
								<div class='input-group date' id='datetimepicker7'>
									<input type='text' class="form-control" id="date_paid" name="date_paid" onblur="calculateDelayPayment()" required/>
									<span class="input-group-addon">
									<span class="fa fa-calendar"></span>
									</span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6 ">
								<label for="date">Payment Delay (days)</label>
								<input type="text" class="form-control" id="delay3" name="delay3" readonly="readonly" />
							</div>
						</div>
						<div class="form-group">
							<label>Remarks</label>
							<textarea type="text" class="form-control" rows="3" id="remarks3" name="remarks3"></textarea>
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

	<script>
		//CREATE FILE
		function selectMaterial(id, name, grade, kgs) {
			document.getElementById("btn_material").innerHTML = name + " - " + grade + " &nbsp&nbsp<span class='caret'></span> ";
			document.getElementById("material").value = id;
			document.getElementById("kg_material").value = kgs;
			calculateQty();
		}

		function filterMaterials() {
			var input, filter, ul, li, a, i;
			input = document.getElementById("searchMaterial");
			filter = input.value.toUpperCase();
			div = document.getElementById("dropdown_material");
			a = div.getElementsByTagName("a");
			for (i = 0; i < a.length; i++) {
				if (a[i].id.toUpperCase().includes(filter)) {
					a[i].style.display = "";
				} else {
					a[i].style.display = "none";
				}
			}
		}

		function calculateQty() {
			var input;
			input = document.getElementById("mt").value;
			var kgs_material = document.getElementById("kg_material").value;
			var kgs = input * 1000;
			var bags = Math.floor(kgs / kgs_material);
			document.getElementById("kgs").value = kgs;
			document.getElementById("bags").value = bags;
		}

		//SHIPMENT 
		function selectRMIorder(id, no, date) {
			document.getElementById("btn_rmino1").innerHTML = no + " &nbsp&nbsp<span class='caret'></span> ";
			document.getElementById("rmino1").value = id;
			document.getElementById("exdateship1").value = date;
		}

		function filterRmino1() {
			var input, filter, ul, li, a, i;
			input = document.getElementById("searchRmino1");
			filter = input.value.toUpperCase();
			div = document.getElementById("dropdown_rmino1");
			a = div.getElementsByTagName("a");
			for (i = 0; i < a.length; i++) {
				if (a[i].id.toUpperCase().startsWith(filter)) {
					a[i].style.display = "";
				} else {
					a[i].style.display = "none";
				}
			}
		}

		function calculateDelayLading() {
			if (document.getElementById("exdateship1").value != "") {
				var a = moment("'" + document.getElementById("exdateship1").value + "'", 'DD/MM/YYYY');
				var b = moment("'" + document.getElementById("bldate").value + "'", 'DD/MM/YYYY');
				var diffDays = b.diff(a, 'days');
				document.getElementById("delay1").value = diffDays;
			}
			calculateDueDate();
			calculateArrival();
		}

		function calculateArrival() {
			var a = moment("'" + document.getElementById("bldate").value + "'", 'DD/MM/YYYY').add(document.getElementById("arrdays").value, 'days');
			document.getElementById("exdatearr").value = a.format('DD/MM/YYYY');
		}

		function calculateDueDate() {
			var a = moment("'" + document.getElementById("bldate").value + "'", 'DD/MM/YYYY').add(document.getElementById("terms").value, 'days');
			document.getElementById("duedate").value = a.format('DD/MM/YYYY');
		}

		//CLEARING
		function selectRMIshipment(id, no, date, qty) {
			document.getElementById("btn_rmino2").innerHTML = no + " &nbsp&nbsp<span class='caret'></span> ";
			document.getElementById("rmino2").value = id;
			document.getElementById("exdatearr1").value = date;
			document.getElementById("qtycleared").value = qty;
		}

		function filterRmino2() {
			var input, filter, ul, li, a, i;
			input = document.getElementById("searchRmino2");
			filter = input.value.toUpperCase();
			div = document.getElementById("dropdown_rmino2");
			a = div.getElementsByTagName("a");
			for (i = 0; i < a.length; i++) {
				if (a[i].id.toUpperCase().startsWith(filter)) {
					a[i].style.display = "";
				} else {
					a[i].style.display = "none";
				}
			}
		}

		function calculateDelayArrival() {
			if (document.getElementById("exdatearr1").value != "") {
				var a = moment("'" + document.getElementById("exdatearr1").value + "'", 'DD/MM/YYYY');
				var b = moment("'" + document.getElementById("date_cleared").value + "'", 'DD/MM/YYYY');
				var diffDays = b.diff(a, 'days');
				document.getElementById("delay2").value = diffDays;
			}
		}

		function calculateAll() {
			calculateDuty();
			calculateClearing();
			calculaterOnloading();
		}

		function calculateDuty() {
			var ghc = document.getElementById("dutyghc").value;
			var rate = document.getElementById("rate").value;
			var usd = parseFloat(Math.round(ghc / rate * 100) / 100).toFixed(2);
			document.getElementById("duty").value = usd;
		}

		function calculateClearing() {
			var invoice = document.getElementById("invoice").value;
			var ghc = document.getElementById("invoice").value - document.getElementById("dutyghc").value;
			document.getElementById("clearingghc").value = parseFloat(Math.round(ghc * 100) / 100).toFixed(2);
			var rate = document.getElementById("rate").value;
			var usd = parseFloat(Math.round(ghc / rate * 100) / 100).toFixed(2);
			document.getElementById("clearing").value = usd;
		}

		function calculateUnloading() {
			var containers = document.getElementById("containers").value;
			var ghc = containers * 160;
			document.getElementById("unloadingghc").value = parseFloat(Math.round(ghc * 100) / 100).toFixed(2);
			var rate = document.getElementById("rate").value;
			var usd = parseFloat(Math.round(ghc / rate * 100) / 100).toFixed(2);
			document.getElementById("unloading").value = usd;
		}

		//PAYMENT 
		function selectRMIcleared(id, no, date) {
			document.getElementById("btn_rmino3").innerHTML = no + " &nbsp&nbsp<span class='caret'></span> ";
			document.getElementById("rmino3").value = id;
			document.getElementById("duedate1").value = date;
		}

		function filterRmino3() {
			var input, filter, ul, li, a, i;
			input = document.getElementById("searchRmino3");
			filter = input.value.toUpperCase();
			div = document.getElementById("dropdown_rmino3");
			a = div.getElementsByTagName("a");
			for (i = 0; i < a.length; i++) {
				if (a[i].id.toUpperCase().startsWith(filter)) {
					a[i].style.display = "";
				} else {
					a[i].style.display = "none";
				}
			}
		}

		function calculateDelayPayment() {
			if (document.getElementById("duedate1").value != "") {
				var a = moment("'" + document.getElementById("duedate1").value + "'", 'DD/MM/YYYY');
				var b = moment("'" + document.getElementById("date_paid").value + "'", 'DD/MM/YYYY');
				var diffDays = b.diff(a, 'days');
				document.getElementById("delay3").value = diffDays;
			}
		}
	</script>

	<script>
		$(document).ready(function() {
			$('#datetimepicker').datetimepicker({
				format: 'DD/MM/YYYY'
			});

			$('#datetimepicker').data("DateTimePicker").maxDate(new Date());

			$('#datetimepicker2').datetimepicker({
				format: 'DD/MM/YYYY'
			});
			$('#datetimepicker2').data("DateTimePicker").maxDate(new Date());


			$('#datetimepicker3').datetimepicker({
				format: 'DD/MM/YYYY'
			});
			$('#datetimepicker3').data("DateTimePicker").maxDate(new Date());


			$('#datetimepicker4').datetimepicker({
				format: 'DD/MM/YYYY'
			});
			$('#datetimepicker4').data("DateTimePicker").maxDate(new Date());


			$('#datetimepicker5').datetimepicker({
				format: 'DD/MM/YYYY'
			});
			$('#datetimepicker5').data("DateTimePicker").maxDate(new Date());


			$('#datetimepicker6').datetimepicker({
				format: 'DD/MM/YYYY'
			});
			$('#datetimepicker6').data("DateTimePicker").maxDate(new Date())


			$('#datetimepicker7').datetimepicker({
				format: 'DD/MM/YYYY'
			});
			$('#datetimepicker7').data("DateTimePicker").maxDate(new Date());


			$('#dataTable').DataTable({
				"order": [],
				"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
				"footerCallback": function(row, data, start, end, display) {
					var api = this.api(),
						data;

					// Remove the formatting to get integer data for summation
					var intVal = function(i) {
						return typeof i === 'string' ?
							i.replace(/[\$,]/g, '') * 1 :
							typeof i === 'number' ?
							i : 0;
					};

					// Total Amount
					pageTotal4 = api
						.column(4, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(4).footer()).html(
						'' + pageTotal4.toLocaleString()
					);
					
					// Total Bags
					pageTotal5 = api
						.column(5, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(5).footer()).html(
						'$' + pageTotal5.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
				}
			});
			$('#dataTable2').DataTable({
				"order": [],
				"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
				"footerCallback": function(row, data, start, end, display) {
					var api = this.api(),
						data;

					// Remove the formatting to get integer data for summation
					var intVal = function(i) {
						return typeof i === 'string' ?
							i.replace(/[\$,]/g, '') * 1 :
							typeof i === 'number' ?
							i : 0;
					};

					// Total Amount
					pageTotal3 = api
						.column(3, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(3).footer()).html(
						'$' + pageTotal3.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
					// Total Bags
					pageTotal6 = api
						.column(6, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(6).footer()).html(
						'' + pageTotal6.toLocaleString()
					);
					
				}
			});
			$('#dataTable3').DataTable({
				"order": [],
				"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
				"footerCallback": function(row, data, start, end, display) {
					var api = this.api(),
						data;

					// Remove the formatting to get integer data for summation
					var intVal = function(i) {
						return typeof i === 'string' ?
							i.replace(/[\$,]/g, '') * 1 :
							typeof i === 'number' ?
							i : 0;
					};

					// Total Amount
					pageTotal2 = api
						.column(2, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(2).footer()).html(
						'$' + pageTotal2.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
					// Total Bags
					pageTotal5 = api
						.column(5, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(5).footer()).html(
						'' + pageTotal5.toLocaleString()
					);
					
					// Total Damaged
					pageTotal6 = api
						.column(6, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(6).footer()).html(
						'' + pageTotal6.toLocaleString()
					);
					
					// Total duty
					pageTotal10 = api
						.column(10, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(10).footer()).html(
						'$' + pageTotal10.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
					// Total Clearance
					pageTotal11 = api
						.column(11, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(11).footer()).html(
						'$' + pageTotal11.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
					// Total Unloading
					pageTotal12 = api
						.column(12, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(12).footer()).html(
						'$' + pageTotal12.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
					// Total 
					pageTotal13 = api
						.column(13, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(13).footer()).html(
						'$' + pageTotal13.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
				}
			});

			$('#dataTable4').DataTable({
				"order": [],
				"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
				"footerCallback": function(row, data, start, end, display) {
					var api = this.api(),
						data;

					// Remove the formatting to get integer data for summation
					var intVal = function(i) {
						return typeof i === 'string' ?
							i.replace(/[\$,]/g, '') * 1 :
							typeof i === 'number' ?
							i : 0;
					};

					// Total Amount
					pageTotal3 = api
						.column(3, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(3).footer()).html(
						'' + pageTotal3.toLocaleString()
					);
					
					// Total Bags
					pageTotal4 = api
						.column(4, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(4).footer()).html(
						'$' + pageTotal4.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
				}
			});
		});
	</script>


	<script src='../assets/pdfmake/pdfmake.min.js'></script>
	<script src='../assets/pdfmake/vfs_fonts.js'></script>

	<script>
		function exportToPDF(table) {
			var table = document.getElementById(table);
			var bdy = [];
			for (var y = 0; y < table.rows.length; y++) {
				bdy[y] = [];
				for (var x = 0; x < table.rows[y].cells.length; x++) {
					if (y == 0 || y == table.rows.length-1 ) {
						bdy[y][x] = {
							text: table.rows[y].cells[x].innerHTML,
							style: 'tableHeader'
					};
					} else {
						bdy[y][x] = {
							text: table.rows[y].cells[x].innerHTML,
							style: 'text'
						};
						if (table.rows[y].cells[x].innerHTML.includes("<br>")) {
							bdy[y][x] = {
								text: table.rows[y].cells[x].innerHTML.replace("<br>", "\n"),
								style: 'text'
							};
						}
					}
				}
			}

			var dd = {
				pageSize: 'A3',
				pageOrientation: 'landscape',
				pageMargins: [20, 60, 20, 60],
				content: [{
						text: document.getElementById("titleReport").innerHTML,
						style: 'header'
					},
					{
						text: new Date().toLocaleString(),
						style: 'header'
					},
					{
						text: 'Generated by United Production System\n\n',
						style: 'quote'
					},
					{
						style: 'tableExample',
						color: '#444',
						table: {
							headerRows: 1,
							body: bdy
						},
						layout: {
							hLineWidth: function(i, node) {
								return 1;
							},
							vLineWidth: function(i, node) {
								return 1;
							},
							hLineColor: function(i, node) {
								return '#bce8f1';
							},
							vLineColor: function(i, node) {
								return '#bce8f1';
							},
							fillColor: function(i, node) {
								return (i === 0) ? '#d9edf7' : null;
							}

						}
					},
				],
				styles: {
					header: {
						fontSize: 18,
						bold: true,
						alignment: 'center',
						color: '#31708f'
					},
					quote: {
						italics: true,
						alignment: 'right',
						color: '#31708f'
					},
					tableHeader: {
						bold: true,
						fontSize: 11,
						color: '#31708f',
						alignment: 'center'
					},
					text: {
						fontSize: 10
					}
				}
			};

			pdfMake.createPdf(dd).open();
			pdfMake.createPdf(dd).download(document.getElementById("titleReport").innerHTML + new Date().toLocaleString() + '.pdf');
		}
	</script>

	<?php
    include_once '../footer.php';
?>