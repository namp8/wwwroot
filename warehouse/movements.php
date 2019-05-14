<?php
    $pageTitle = "Warehouse - Stock Movements";
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
        <li class="breadcrumb-item active">Stock Movements</li>
    </ol>
    <h2>Warehouse - Stock Movements</h2>

<div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
    if(!empty($_POST['id_transfer']))
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($stock->send()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
	
	else if(!empty($_POST['approveAll']))
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if(!$stock->issueAll()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-info show");</script>';
        }
    }
	
    else if(!empty($_POST['material2']) )
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($stock->sent()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
	else if(!empty($_POST['date3']))
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($stock->useConsumables()){

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
            <div class="panel-heading"> Historic Stock Movements of the year</div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                <th></th>
                                <th>Date</th>
                                <th>To</th>
                                <th>Material</th>
                                <th>Bags/Drumps Required</th>
                                <th>Requested By</th>
                                <th>Bags/Drumps Approved</th>
                                <th>Approved By</th>
                                <th>Bags/Drumps Issued</th>
                                <th>Issued By</th>
                                <th>Bags/Drumps Received</th>
                                <th>Received By</th>
                                <th>Status</th>
                                <th>Approved Remarks</th>
                                <th>Issued Remarks</th>
                            </tr>
                        </thead>
						<tfoot>
							<tr class="active">
								<th></th>
								<th></th>
								<th></th>
								<th>Total Required</th>
								<th style="text-align:right"></th>
								<th>Total Approved</th>
								<th style="text-align:right"></th>
								<th>Total Issued</th>
								<th style="text-align:right"></th>
								<th>Total Received</th>
								<th style="text-align:right"></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</tfoot>
                        <tbody>
                            <?php  
    $stock->stockMovements();
?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

 <!-- Logout Modal-->
    <div class="modal fade" id="modal1" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-m">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal">x</button>
            <h4 class="modal-title"> Issue raw material</h4>
          </div>
            <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                
          <div class="modal-body">
                <input type="hidden" class="form-control" id="id_transfer" name="id_transfer">
                <div class="form-group">
                    <label for="date">Date</label>
                    <div class='input-group date' id='datetimepicker'>
                        <input type='text' class="form-control" id="date" name="date" disabled/>
                        <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="form">From</label>
                    <input type="text" class="form-control" id="from" name="from" disabled>
                </div>
                <div class="form-group">
                    <label for="form">To</label>
                    <input type="text" class="form-control" id="to" name="to" disabled>
                </div>
                <div class="form-group">
                    <label for="material">Material</label>
                    <input type="text" class="form-control" id="material" name="material" disabled>
                </div>
                <div class="form-group">
                    <label for="total">Total Bags/Drumps issued <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" min="0" step="0.0001" id="bags" name="bags" required>
                </div>
                <div class="form-group">
                    <label for="form">Remarks</label>
                    <input type="text" class="form-control" id="remarks" name="remarks" >
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
        function edit(id, date, from, to, material, grade, bags) {
            document.getElementById("id_transfer").value = id;
            document.getElementById("date").value = date;
            document.getElementById("from").value = from;
            document.getElementById("to").value = to;
            document.getElementById("material").value = material + " - " + grade;
            document.getElementById("bags").value = bags;
            document.getElementById("form").style.display = "";
            
            document.getElementById("alertMessage").removeAttribute("class");
            document.getElementById("alertMessage").setAttribute("class","alert hide");
            window.scrollTo(0,0);
        }
		function selectMaterial(id, name, grade, kgs) {
			document.getElementById("btn_material").innerHTML = name + " - " + grade + " &nbsp&nbsp<span class='caret'></span> ";
			document.getElementById("material2").value = id;
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
	function selectConsumable(id, name) {
            document.getElementById("btn_consumable").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
            document.getElementById("material3").value = id;
        }
        function filterConsumables() {
            var input, filter, ul, li, a, i;
            input = document.getElementById("searchConsumable");
            filter = input.value.toUpperCase();
            div = document.getElementById("dropdown_consumable");
            a = div.getElementsByTagName("a");
            for (i = 0; i < a.length; i++) {
                if (a[i].id.toUpperCase().startsWith(filter)) {
                    a[i].style.display = "";
                } else {
                    a[i].style.display = "none";
                }
            }
        }
    </script>

    <script>
        $(document).ready(function() {
                $('#datetimepicker').datetimepicker({         
                        format: 'DD/MM/YYYY'
                    });
                
                $('#datetimepicker').data("DateTimePicker").maxDate(new Date());
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
					pageTotal4 = api.column(4, {page: 'current'}).data().reduce(function(a, b) {	return intVal(a) + intVal(b);	}, 0);
					$(api.column(4).footer()).html('' + pageTotal4.toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2}));
					
					pageTotal6 = api.column(6, {page: 'current'}).data().reduce(function(a, b) {	return intVal(a) + intVal(b);	}, 0);
					$(api.column(6).footer()).html('' + pageTotal6.toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2}));
					
					pageTotal8 = api.column(8, {page: 'current'}).data().reduce(function(a, b) {	return intVal(a) + intVal(b);	}, 0);
					$(api.column(8).footer()).html('' + pageTotal8.toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2}));
					
					pageTotal10 = api.column(10, {page: 'current'}).data().reduce(function(a, b) {	return intVal(a) + intVal(b);	}, 0);
					$(api.column(10).footer()).html('' + pageTotal10.toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2}));
					
				}
			});
        });
    </script>


    <?php
    include_once '../footer.php';
?>