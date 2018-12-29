<?php

    $pageTitle = "Slitting - Waste";

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
		<li class="breadcrumb-item active">Waste</li>
	</ol>
	<h2>Slitting - Waste</h2>


    <div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
    if(!empty($_POST['shift']) )
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($slitting->createWaste()){

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
		<div class="dropdown" style="margin-top:5px;margin-right:30px;">
			<button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Submit waste &nbsp&nbsp<i class="fa fa-caret-down" style="display: inline;"></i></button>
			<ul class="dropdown-menu dropdown-menu-right">
				<li><a onclick="selectMachine(46,'Slitting 1')" data-toggle="modal" data-target="#modal1">Slitting 1</a></li>
				<li><a onclick="selectMachine(47,'Slitting 2')" data-toggle="modal" data-target="#modal1">Slitting 2</a></li>
			</ul>
		</div>
	</div>

        <div class="panel panel-info">
            <div class="panel-heading">
                Historic machines waste:
            </div>
            <div class="panel-body">

                <div class="table-responsive">
                    <table class="table table-bordered  table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="active">
                                <th>Date</th>
                                <th>Shift</th>
                                <th>Machine</th>
                                <th>Customer</th>
                                <th>User</th>
                                <th>Print Waste</th>
                                <th>Slitting Waste</th>
                                <th>Trim Waste</th>
                                <th>Total Waste</th>
                            </tr>
                        </thead>
						
					<tfoot>
						<tr class="active">
							<th></th>
							<th></th>
							<th></th>
							<th>Total</th>
							<th style="text-align:right"></th>
							<th style="text-align:right"></th>
							<th style="text-align:right"></th>
							<th style="text-align:right"></th>
						</tr>
					</tfoot>
                        <tbody>
<?php
     $slitting->giveWaste();
?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


<div class="modal fade" id="modal1" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-m">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal">x</button>
            <h4 class="modal-title">Submit waste</h4>
          </div>
        <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <div class="modal-body">
            <div class="form-group">
				<label for="date">Date <span class="text-danger">*</span></label>
				<div class='input-group date' id='datetimepicker2'>
					<input type='text' class="form-control" id="date" name="date" required />
					<span class="input-group-addon">
							<span class="fa fa-calendar"></span>
					</span>
				</div>
			</div>
          <div class="form-group">
                <label for="shift">Shift <span class="text-danger">*</span></label><br />
                <input type="hidden" class="form-control" id="shift" name="shift" value="1" required>
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_shift">Day&nbsp&nbsp<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a onclick="selectShift(1,'Day')">Day</a></li>
                        <li><a onclick="selectShift(2,'Night')">Night</a></li>
                    </ul>
                </div>
            </div>
			  <div class="form-group">
				<label for="size">Machine <span class="text-danger">*</span></label><br />
				<input type="hidden" class="form-control" id="machine" name="machine" value="1" required>
				<input type="text" class="form-control" step="1" min="1" id="machineName"  value="" disabled>
			</div>
			<div class="form-group">
                                    <label for="customer">Customer</label><br />
                                    <input type="hidden" class="form-control" id="customer" name="customer">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_customer">&nbsp&nbsp<span class="caret"></span></button>
                                        <ul class="dropdown-menu" id="dropdown_customer">
                                            <li><input type="text" placeholder="Search customer.." class="searchDropdown" id="searchCustomer" onkeyup="filterCustomers()" width="100%"></li>
                                            <?php
    $slitting->customersDropdown(1);
 ?>
                                        </ul>
                                    </div>
                                    
             </div>
            <div class="form-group">
                <label for="total">Print waste <span class="text-danger">*</span></label>
                <input type="number" class="form-control" min="0" step="0.01" id="print" name="print" value="0" required>
            </div>
            <div class="form-group">
                <label for="total">Slitting waste <span class="text-danger">*</span></label>
                <input type="number" class="form-control" min="0" step="0.01" id="slitting" name="slitting" value="0" required>
            </div>
            <div class="form-group">
                <label for="total">Trim waste <span class="text-danger">*</span></label>
                <input type="number" class="form-control" min="0" step="0.01" id="trim" name="trim" value="0" required>
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
			
				 function selectCustomer1(id, name) {
                    document.getElementById("btn_customer").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
                    document.getElementById("customer").value = id;
                }
            function selectShift(id, name) {
                document.getElementById("btn_shift").innerHTML = name+" &nbsp&nbsp<span class='caret'></span> ";
                document.getElementById("shift").value = id;
            }
			
			
			function selectMachine(id, name) {
				document.getElementById("machine").value = id;
				document.getElementById("machineName").value = name;
			}
        </script>
        <script>
            $(function() {
				
				
				
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

					pageTotal4 = api
						.column(4, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(4).footer()).html(
						'' + pageTotal4.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
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
						'' + pageTotal5.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
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
						'' + pageTotal6.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
					pageTotal7 = api
						.column(7, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(7).footer()).html(
						'' + pageTotal7.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
				
					
				}
			});
				
				
                var d = new Date();
                var month = d.getMonth() + 1;
                document.getElementById("date").value = d.getDate() + "/" + month + "/" + d.getFullYear();
				
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
                
            })
        </script>
  
    
    <?php
    include_once '../../footer.php';
?>