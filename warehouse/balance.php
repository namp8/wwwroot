<?php
    $pageTitle = "Warehouse - Balance";
    include_once "../base.php";
    include_once "../header.php";
    include_once "sidebarwarehouse.php";
    include_once "../content.php";


    include_once "../inc/class.stock.inc.php";
    $stock = new Stock($db);

	include_once "../inc/class.users.inc.php";
    $users = new Users($db);	

	if(!$stock->access(4))
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
		<li class="breadcrumb-item active">Balance</li>
	</ol>
	<h2>Warehouse - Balance</h2>

	<div id="alertMessage" class="alert hide" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
		<?php
	if(!empty($_POST['action']) and $_POST['action'] ==2)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($stock->updateBalance()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
    if(!empty($_POST['action']) and $_POST['action'] ==3)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($stock->deleteBalance()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
?>
	</div>
	<div class="panel panel-info">
		<div class="panel-heading"> Historic Balance of the year</div>
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr class="active">
							
								<th>Date</th>
								<th>Machine</th>
								<th>Material</th>
								<th>Previous Bags/Drumps</th>
								<th>New Bags/Drumps</th>
								<th>Difference</th>
								<th>Balanced By</th>
								<th>Remarks</th><?php
									if($users->admin())
									{
										echo '<th></th>';
										echo '<th></th>';
									}
								?>
						</tr>
					</thead>
					<tfoot>
						<tr class="active">
							
							<th></th>
							<th></th>
							<th></th>
							<th style="text-align:right"></th>
							<th style="text-align:right"></th>
							<th style="text-align:right"></th>
							<th></th>
							<th></th><?php
									if($users->admin())
									{
										echo '<th></th>';
										echo '<th></th>';
									}
								?>
						</tr>
					</tfoot>
					<tbody>
						<?php  
    $stock->stockBalances();
?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<!-- Modal-->
	<div class="modal fade" id="modal1" role="dialog" tabindex="-1">
		<div class="modal-dialog modal-m">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">x</button>
					<h4 class="modal-title" id="panelTitle">Create Balance</h4>
				</div>
				<form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

					<input type="hidden" class="form-control" id="action" name="action" required>
					<div class="modal-body">
						<input type="hidden" class="form-control" id="id_balance" name="id_balance" required>
						<div class="form-group">
							<label for="date">Date</label>
							<div class='input-group date' id='datetimepicker'>
								<input type='text' class="form-control" id="date" name="date" />
								<span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label for="machine">Machine <span class="text-danger">*</span></label>
							<input type="hidden" class="form-control" id="id_material" name="id_material" required>
							<input type="text" class="form-control" id="machine" name="machine" readonly="readonly"><br>
						</div>
						<div class="form-group">
							<label for="material">Material <span class="text-danger">*</span></label>
							<input type="text" class="form-control" id="material" name="material" readonly="readonly"><br>
						</div>
						<div class="form-group">
							<label for="oldbags">Bags/Drumps on UPS<span class="text-danger">*</span></label>
							<input type="number" class="form-control" min="1" step="0.001" id="oldbags" name="oldbags" onkeyup="calculate()">
						</div>
						<div class="form-group">
							<label for="newbags">Bags/Drumps on floor<span class="text-danger">*</span></label>
							<input type="number" class="form-control" min="1" step="0.001" id="newbags" name="newbags" onkeyup="calculate()" required>
						</div>
						<div class="form-group">
							<label for="difference">Variance<span class="text-danger">*</span></label>
							<input type="number" class="form-control" min="1" id="difference" name="difference" readonly="readonly">
						</div>
						<div class="form-group">
							<label for="remarks">Remarks</label>
							<input type="text" class="form-control" id="remarks" name="remarks">
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            			<button type="submit" id="buttonForm" class="btn btn-info">Create</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>
		function edit(id, date, machine, materialid, material, oldbags, newbags, difference, remarks) {
			document.getElementById("action").value = 2;
			document.getElementById("buttonForm").innerHTML = "Update";
			document.getElementById("buttonForm").setAttribute("class", "btn btn-info");
			document.getElementById("panelTitle").innerHTML = "Edit Customer";

			document.getElementById("id_balance").value = id;
			document.getElementById("date").value = date;
			document.getElementById("machine").value = machine;
			document.getElementById("id_material").value = materialid;
			document.getElementById("material").value = material;
			document.getElementById("oldbags").value = oldbags;
			document.getElementById("newbags").value = newbags;
			document.getElementById("difference").value = difference;
			document.getElementById("remarks").value = remarks;
			
			
            document.getElementById("date").readOnly = false;
            document.getElementById("oldbags").readOnly = false;
            document.getElementById("newbags").readOnly = false;
            document.getElementById("remarks").readOnly = false;
			
			$(modal1).modal();
		}

		function deleteBalance(id, date, machine, materialid,  material, oldbags, newbags, difference, remarks) {
			document.getElementById("action").value = 3;
			document.getElementById("buttonForm").innerHTML = "Delete";
			document.getElementById("buttonForm").setAttribute("class", "btn btn-danger");
			document.getElementById("panelTitle").innerHTML = "Delete customer";

			document.getElementById("id_balance").value = id;
			document.getElementById("date").value = date;
			document.getElementById("machine").value = machine;
			document.getElementById("id_material").value = materialid;
			document.getElementById("material").value = material;
			document.getElementById("oldbags").value = oldbags;
			document.getElementById("newbags").value = newbags;
			document.getElementById("difference").value = difference;
			document.getElementById("remarks").value = remarks;
			
			
            document.getElementById("date").readOnly = true;
            document.getElementById("oldbags").readOnly = true;
            document.getElementById("newbags").readOnly = true;
            document.getElementById("remarks").readOnly = true;
			
			$(modal1).modal();
		}
		function calculate() {
            var oldbags = document.getElementById("oldbags").value;
            var newbags = document.getElementById("newbags").value;
		   	var difference = newbags - oldbags;
            document.getElementById("difference").value = difference.toFixed(2);
        }
	</script>

	<script>
		$(document).ready(function() {
			
			$('#datetimepicker').datetimepicker({
				format: 'DD/MM/YYYY',
				defaultDate : moment()
			});
			$("#dataTable").DataTable({
				"order": [],
				"lengthMenu": [
					[10, 25, 50, 100, -1],
					[10, 25, 50, 100, "All"]
				],
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

				}
			});
		});
	</script>


	<?php
    include_once '../footer.php';
?>