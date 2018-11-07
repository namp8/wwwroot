<?php
    $pageTitle = "Warehouse - Issues";
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
        <li class="breadcrumb-item active">Issue</li>
    </ol>
    <h2>Warehouse - Issue</h2>

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
<form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
 		<input type="submit" id="approveAll" name="approveAll" class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" value="Issued All">
     </form>
    <button class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" data-toggle="modal" data-target="#modal2">Send to Finished Goods</button>
    <button class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" data-toggle="modal" data-target="#modal3">Use Consumable Item in Warehouse</button> 


        <div class="panel panel-info">
            <div class="panel-heading"> Historic Issues of the month</div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                <th>Send</th>
                                <th>Date</th>
                                <th>To</th>
                                <th>Material</th>
                                <th>Bags/Drumps Required</th>
                                <th>Requested By</th>
                                <th>Bags/Drumps Approved</th>
                                <th>Kgs Approved</th>
                                <th>Approved By</th>
                                <th>Bags/Drumps Issued</th>
                                <th>Issued By</th>
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
								<th></th>
								<th>Total Issued</th>
								<th style="text-align:right"></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</tfoot>
                        <tbody>
                            <?php  
    $stock->stockIssues();
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
<div class="modal fade" id="modal2" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-m">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal">x</button>
            <h4 class="modal-title">Send to Finished Goods</h4>
          </div>
        <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <div class="modal-body">
<div class="form-group">
                <label for="date">Date<span class="text-danger">*</span></label>
                <div class='input-group date' id='datetimepicker2'>
                    <input type='text' class="form-control" id="date2" name="date" />
                    <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                    </span>
                </div>
            </div>
            <br>
            <div class="form-group">
                <label for="form">From</label>
				<input type="hidden" class="form-control" name="from" value="1" required>
                <input type="text" class="form-control" value="Warehouse"  disabled>
            </div>
            <div class="form-group">
                <label for="form">To</label><br />
				<input type="hidden" class="form-control" name="to" value="12" required>
                <input type="text" class="form-control" value="Finished Goods"  disabled>
            </div>
            
			<div class="form-group ">
				<label for="material">Material <span class="text-danger">*</span></label><br/>
				<input type="hidden" class="form-control" id="material2" name="material2" required>
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
            <div class="form-group">
                <label for="total">Total bags<span class="text-danger">*</span></label>
                <input type="number" class="form-control"  min="1" step="0.0001" id="bags" name="bags" required>
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
<div class="modal fade" id="modal3" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-m">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal">x</button>
            <h4 class="modal-title">Use Consumable Item in Warehouse</h4>
          </div>
        <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <div class="modal-body">
<div class="form-group">
                <label for="date">Date <span class="text-danger">*</span></label>
                <div class='input-group date' id='datetimepicker3'>
                    <input type='text' class="form-control" id="date3" name="date3" required/>
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
                <input type="text" class="form-control" id="to" name="to" value="Warehouse"  disabled>
            </div>
			 <div class="form-group">
                <label >Consumable Items  <span class="text-danger">*</span></label><br>
            	<input type="hidden" class="form-control" id="material3" name="material3">
                <div class="btn-group">
                    <button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_consumable">&nbsp&nbsp<span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu" id="dropdown_consumable">
                        <li><input type="text" placeholder="Search consumable item.." class="searchDropdown" id="searchConsumable" onkeyup="filterConsumable()" width="100%"></li>
                        <?php
    $materials->consumablesDropdown();
?>
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <label for="total">Total quantity <span class="text-danger">*</span></label>
                <input type="number" class="form-control"  min="1" id="bags2" name="bags2" required>
            </div>
			  
                <div class="form-group">
                    <label for="form">Remarks</label>
                    <input type="text" class="form-control" id="remarks" name="remarks">
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
                $('#datetimepicker2').datetimepicker({         
                        format: 'DD/MM/YYYY'
                    });
                
                $('#datetimepicker2').data("DateTimePicker").maxDate(new Date());
                $('#datetimepicker3').datetimepicker({         
                        format: 'DD/MM/YYYY'
                    });
                
                $('#datetimepicker3').data("DateTimePicker").maxDate(new Date());
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
					pageTotal9 = api
						.column(9, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(9).footer()).html(
						'' + pageTotal9.toLocaleString()
					);
				}
			});
        });
    </script>


    <?php
    include_once '../footer.php';
?>