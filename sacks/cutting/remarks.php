<?php
    $pageTitle = "Sacks - Cutting Short Falls";
	$location = "Cutting";
    
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar.php";
    include_once "../../content.php";


    include_once "../../inc/class.general.inc.php";
    $general = new General($db);

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
        <li class="breadcrumb-item active">Short Fall</li>
    </ol>
    <h2>Sacks - Cutting - Short Fall</h2>

	<div id="alertMessage" class="alert hide" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
		<?php
    if(!empty($_POST['machine']) and empty($_POST['id_shortfall']))
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($general->createFall()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
	else if(!empty($_POST['machine']) and !empty($_POST['id_shortfall']))
	{
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($general->updateFall()){
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
	else if(!empty($_POST['id_delete']))
	{
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($general->deleteFall()){

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
			<button class="btn btn-info dropdown-toggle" type="button" data-toggle="modal" data-target="#modal1">Submit Reason for Short Fall</button>
		</div>
	</div>


	<div class="panel panel-info">
		<div class="panel-heading"> Historic Short Falls </div>
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr class="active">
							<th>Date</th>
							<th>Machine</th>
							<th>Downtime</th>
							<th>Reason for Short Fall</th>
							<th>Action Plan</th>
						</tr>
					</thead>
					<tbody>
						<?php
     $general->giveShortFall($location);
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
					<h4 class="modal-title">Short fall</h4>
				</div>
				<form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

					<div class="modal-body">
						<input type="hidden" class="form-control" id="id_shortfall" name="id_shortfall">
						<div class="form-group">
							<label for="date">Date <span class="text-danger">*</span></label>
							<div class='input-group date' id='datetimepicker2' required>
								<input type='text' class="form-control" id="date" name="date"  />
								<span class="input-group-addon">
							<span class="fa fa-calendar"></span>
								</span>
							</div>
						</div>

						<div class="form-group">
							<label for="size">Machine <span class="text-danger">*</span></label><br />
							<input type="hidden" class="form-control" id="machine" name="machine" value="1" required>
							<div class="btn-group">
								<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_machine">&nbsp&nbsp<span class="caret"></span></button>
								<ul class="dropdown-menu" role="menu" id="dropdown_machine">
									<?php
    $general->giveMachines($location);
?>
								</ul>
							</div>
						</div>
						<div class="form-group">
							<label>Downtime (HH:mm) <span class="text-danger">*</span></label>
							<div class='input-group date' id='timepicker'>
								<input type='text' class="form-control" id="time" name="time" required/>
								<span class="input-group-addon">
                            <span class="fa fa-clock-o"></span>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label>Reason For Short Fall <span class="text-danger">*</span></label>
							<textarea type="text" class="form-control" rows="3" id="reason" name="reason" required></textarea>
						</div>
						<div class="form-group">
							<label>Action Plan <span class="text-danger">*</span></label>
							<textarea type="text" class="form-control" rows="3" id="action" name="action"></textarea>
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
		function selectMachine(id, name) {
			document.getElementById("machine").value = id;
			document.getElementById("btn_machine").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
		}

		function edit(id, date, machine, machineName, downtime, reason, plan) {
			document.getElementById("id_shortfall").value = id;
			document.getElementById("date").value = date;
			document.getElementById("machine").value = machine;
			document.getElementById("btn_machine").innerHTML = machineName + " &nbsp&nbsp<span class='caret'></span> ";
			document.getElementById("time").value = downtime;
			document.getElementById("reason").value = reason;
			document.getElementById("action").value = plan;

		}

		function deleteFall(id, date, machine, machineName, downtime, reason, plan) {
			document.getElementById("id_delete").value = id;
			document.getElementById("date2").value = date;
			document.getElementById("machine2").value = machine;
			document.getElementById("time2").value = downtime;
			document.getElementById("reason2").value = reason;
			document.getElementById("action2").value = plan;

		}


		$(document).ready(function() {
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

			$('#dataTable').DataTable({
				"order": [
					[0, "desc"]
				]
			});
			$('#timepicker').datetimepicker({
				format: 'HH:mm'
			});
		});
	</script>



	<?php
    include_once '../../footer.php';
?>