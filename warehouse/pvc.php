<?php
    $pageTitle = "Warehouse - PVC Pipes";
    include_once "../base.php";
    include_once "../header.php";
    include_once "sidebarwarehouse.php";
    include_once "../content.php";


    include_once "../inc/class.stock.inc.php";
    $stock = new Stock($db);

if(!$stock->access(3))
	{
		echo "<meta http-equiv='refresh' content='0;/index.php'>";
        exit;
	}
	
    include_once "../inc/class.materials.inc.php";
    $materials = new Materials($db);


?>
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="../index.php">United Production System</a>
		</li>
		<li class="breadcrumb-item">
			<a href="home.php">Warehouse</a>
		</li>
		<li class="breadcrumb-item active">PVC Pipes</li>
	</ol>
	<h2>Warehouse - PVC Pipes</h2>

	<div id="alertMessage" class="alert hide" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>

<?php
    if($_SERVER["REQUEST_METHOD"] == "POST" and !empty($_POST['material']))
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($stock->createPVC()){

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


	<button class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" data-toggle="modal" data-target="#modal1">Cut PVC Pipes</button>
    <div class="panel panel-info">
        <div class="panel-heading"> PVC Pipes</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                            <th>Date</th>
                            <th>Size</th>
                            <th>Quantity of Pipes</th>
                            <th>Cones per Pipe</th>
                            <th>Total Cones</th>
							<th>Submitted by</th>
                        </tr>
                    </thead>
					<tfoot>
						<tr class="active">
							<th></th>
							<th>Total</th>
							<th style="text-align:right"></th>
							<th></th>
							<th style="text-align:right"></th>
							<th></th>
						</tr>
					</tfoot>
                    <tbody>
<?php
    $stock->givePVC();
?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Modal-->
    <div class="modal fade" id="modal1" role="dialog" tabindex="-1">
      <div class="modal-dialog" style="width:600px;">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal">x</button>
            <h4 class="modal-title">Cut PVC Pipes</h4>
          </div>
            <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                
          <div class="modal-body">
                <input type="hidden" class="form-control" id="id_transfer" name="id_transfer" required>
			  
						<div class="row">
                <div class="form-group col-md-6">
                    <label for="date">Date</label>
                    <div class='input-group date' id='datetimepicker'>
                        <input type='text' class="form-control" id="date" name="date"/>
                        <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                        </span>
                    </div>
                </div>
                <div class="form-group col-md-6">
					<label for="material">Size <span class="text-danger">*</span></label>
					<input type="hidden" class="form-control" id="material" name="material" required>
					<input type="hidden" class="form-control" id="kg_material" name="kg_material" required><br>
					<div class="btn-group">
						<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_material">&nbsp&nbsp<span class="caret"></span></button>
						<ul class="dropdown-menu" role="menu" id="dropdown_material">
							<li><input type="text" placeholder="Search material.." class="searchDropdown" id="searchMaterial" onkeyup="filterMaterials()" width="100%"></li>
							<?php
$materials->pvcconesDropdown();
?>
						</ul>
					</div>
				</div>
			  	
				<div class="form-group col-md-4">
					<label for="total">Pipes Qty <span class="text-danger">*</span></label>
					<input type="number" class="form-control" min="1" step="1" id="pipes" name="pipes" onkeyup="calculateTotal()" required>
				</div>
                <div class="form-group col-md-4">
                    <label for="oldbags">Cones per Pipe<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" min="1" step="1"  id="cones" name="cones" onkeyup="calculateTotal()" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="remarks">Total Cones <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" min="1" step="0.001"  id="total" name="total">
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

	<script>
				
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
		
		function calculateTotal() {
			var pipes = document.getElementById("pipes").value;
			var cones = document.getElementById("cones").value;
			var total = pipes * cones;
			document.getElementById("total").value = total;
		}
		
		$(document).ready(function() {
			$('#datetimepicker').datetimepicker({
				format: 'DD/MM/YYYY',
				defaultDate : moment()
			});
$("#dataTable").DataTable({
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
						'' + pageTotal2.toLocaleString()
					);
					
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
				
					
				}
			});
		});
		 
        </script>
    <?php
    include_once '../footer.php';
?>