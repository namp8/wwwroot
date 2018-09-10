<?php

    $pageTitle = "Packing Bags - Sacks Waste";

     include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar.php";
    include_once "../../content.php";
   
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
            <a href="../process.php">Packing Bags</a>
        </li>
        <li class="breadcrumb-item active">Sacks Waste</li>
    </ol>
    <h2>Packing Bags - Sacks Waste</h2>


    <div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
    if(!empty($_POST['shift']) )
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($packing->createSacksWaste()){

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
			<button class="btn btn-info" type="button" data-toggle="modal" data-target="#modal1">Submit waste </button>
		</div>
	</div>

        <div class="panel panel-info">
            <div class="panel-heading">
                Historic waste:
            </div>
            <div class="panel-body">

                <div class="table-responsive">
                    <table class="table table-bordered  table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="active">
                                <th>Date</th>
                                <th>Shift</th>
                                <th>User</th>
                                <th>Cutting Waste</th>
                                <th>Printing Waste</th>
                                <th>Total</th>
                            </tr>
                        </thead>
						
					<tfoot>
						<tr class="active">
							<th></th>
							<th></th>
							<th>Total</th>
							<th style="text-align:right"></th>
							<th style="text-align:right"></th>
							<th style="text-align:right"></th>
						</tr>
					</tfoot>
                        <tbody>
<?php
     $packing->giveCuttingWaste();
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
                <label for="total">Printing waste <span class="text-danger">*</span></label>
                <input type="number" class="form-control" min="0" step="0.01" id="waste2" name="printing" value="0" required>
            </div>
            <div class="form-group">
                <label for="total">Cutting waste <span class="text-danger">*</span></label>
                <input type="number" class="form-control" min="0" step="0.01" id="waste" name="cutting" value="0" required>
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
            function selectShift(id, name) {
                document.getElementById("btn_shift").innerHTML = name+" &nbsp&nbsp<span class='caret'></span> ";
                document.getElementById("shift").value = id;
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

					pageTotal3 = api
						.column(3, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(3).footer()).html(
						'' + pageTotal3.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
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