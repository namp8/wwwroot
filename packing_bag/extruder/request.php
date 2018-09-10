<?php
    $pageTitle = "Packing Bag Request";
    
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar.php";
    include_once "../../content.php";

    include_once "../../inc/class.stock.inc.php";
    $stock = new Stock($db);
	
	include_once "../../inc/class.packing.inc.php";
    $packing = new Packing($db);
	include_once "../../inc/class.users.inc.php";
    $users = new Users($db);
?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="process.php">Packing Bag</a>
        </li>
        <li class="breadcrumb-item active">Request</li>
    </ol>
    <h2>Packing Bag - Request</h2>


    <div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
           <?php
    if(!empty($_POST['material']) )
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($stock->createRequest(1,11)){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
       
?>
    </div>

<ul class="nav nav-tabs nav-justified">
        <li class="active"><a data-toggle="tab" href="#materialTab">Raw Material</a></li>
        <li><a data-toggle="tab" href="#consumableTab">Consumables</a></li>
    </ul>
	 <div class="tab-content">
        <div id="materialTab" class="tab-pane fade in active">

    <button class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" data-toggle="modal" data-target="#modal1" onclick="modal(1)">Request Raw Material</button>
			
        <div class="panel panel-info">
            <div class="panel-heading"> Historic Requests of the month</div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                <th>Date</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Material</th>
                                <th>Bags Required</th>
                                <th>Requested By</th>
                                <th>Status</th>
                            </tr>
                        </thead>
						<tfoot>
							<tr class="active">
								<th></th>
								<th></th>
								<th></th>
								<th>Total Required</th>
								<th style="text-align:right"></th>
								<th></th>
								<th></th>
							</tr>
						</tfoot>
                        <tbody>
<?php  
    $stock->stockMaterialsRequest(2);
?>
                        </tbody>
                    </table>
                </div>
            </div>
			</div></div>
        <div id="consumableTab" class="tab-pane fade">

    <button class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" data-toggle="modal" data-target="#modal1" onclick="modal(2)">Request Consumable Items</button>

        <div class="panel panel-info">
            <div class="panel-heading"> Historic Requests of the month</div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable2" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                <th>Date</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Description</th>
                                <th>Quantity Required</th>
                                <th>Requested By</th>
                                <th>Status</th>
                            </tr>
                        </thead>
						<tfoot>
							<tr class="active">
								<th></th>
								<th></th>
								<th></th>
								<th>Total Required</th>
								<th style="text-align:right"></th>
								<th></th>
								<th></th>
							</tr>
						</tfoot>
                        <tbody>
<?php  
    $stock->stockConsumablesRequest(2);
?>
                        </tbody>
                    </table>
                </div>
            </div>
			</div></div>
</div>


    <!-- Modal-->
    <div class="modal fade" id="modal1" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-m">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal">x</button>
            <h4 class="modal-title">Create Request of raw material</h4>
          </div>
        <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <div class="modal-body">
<div class="form-group">
                <label for="date">Date <span class="text-danger">*</span></label>
                <div class='input-group date' id='datetimepicker'>
                    <input type='text' class="form-control" id="date" name="date" required <?php 
						   if(!$users->admin())
						   {	
							   echo 'readonly';
						   }
						   ?>/>
                    <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                    </span>
                </div>
            </div>
            <br>
            <div class="form-group">
                <label for="form">From</label>
                <input type="text" class="form-control" id="from" name="from" value="Warehouse"  disabled>
            </div>
            <div class="form-group">
                <label for="form">To</label>
                <input type="text" class="form-control" id="to" name="to" value="Packing Bag"  disabled>
            </div>
            
           <div class="form-group" id="materialbtn">
                <label for="material">Material <span class="text-danger">*</span></label>
                <input type="hidden" class="form-control" id="material" name="material" required><br>
                <div class="btn-group">
                    <button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_material">&nbsp&nbsp<span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu" id="dropdown_material">
                        <li><input type="text" placeholder="Search material.." class="searchDropdown" id="searchMaterial" onkeyup="filterMaterials()" width="100%"></li>
                        <?php
    $packing->materialsDropdown();
?>
                    </ul>
                </div>
            </div>
			 <div class="form-group hide" id="consumables">
                <label >Consumable Items  <span class="text-danger">*</span></label><br>
                <div class="btn-group">
                    <button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_consumable">&nbsp&nbsp<span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu" id="dropdown_consumable">
                        <li><input type="text" placeholder="Search consumable item.." class="searchDropdown" id="searchConsumable" onkeyup="filterConsumable()" width="100%"></li>
                        <?php
    $packing->consumablesDropdown();
?>
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <label for="total">Total bags <span class="text-danger">*</span></label>
                <input type="number" class="form-control"  min="0.01" step="0.01" id="bags" name="bags" required>
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
	
	function modal(id)
		{
			if(id == 2)
			{
				document.getElementById("materialbtn").removeAttribute("class");
				document.getElementById("materialbtn").setAttribute("class","form-group hide");
				document.getElementById("consumables").removeAttribute("class");
				document.getElementById("consumables").setAttribute("class","form-group");
			}
			else
			{
				document.getElementById("materialbtn").removeAttribute("class");
				document.getElementById("materialbtn").setAttribute("class","form-group");
				document.getElementById("consumables").removeAttribute("class");
				document.getElementById("consumables").setAttribute("class","form-group hide");
			}
		}
	
        function selectMaterial(id, name, grade) {
            document.getElementById("btn_material").innerHTML = name + " - " + grade+ " &nbsp&nbsp<span class='caret'></span> ";
            document.getElementById("material").value = id;
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
            document.getElementById("material").value = id;
        }
        function filterConsumables() {
            var input, filter, ul, li, a, i;
            input = document.getElementById("searchConsumable");
            filter = input.value.toUpperCase();
            div = document.getElementById("dropdown_consumable");
            a = div.getElementsByTagName("a");
            for (i = 0; i < a.length; i++) {
                if (a[i].id.toUpperCase().includes(filter)) {
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
                        format: 'DD/MM/YYYY',
						defaultDate: moment()
                    });
                
                $('#datetimepicker').data("DateTimePicker").maxDate(new Date());
            
            $('#dataTable').DataTable( {
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
					
				}
            } );
			
			
			$('#dataTable2').DataTable( {
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
					
				}
            } );
        } );
    </script>            


    <?php
    include_once '../../footer.php';
?>