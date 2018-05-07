<?php
    $pageTitle = "Packing Bags Short Falls";
    
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar.php";
    include_once "../../content.php";


    include_once "../../inc/class.packing.inc.php";
    $packing = new Packing($db);

?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="process.php">Packing Bags</a>
        </li>
        <li class="breadcrumb-item active">Short Fall</li>
    </ol>
    <h2>Packing Bags - Short Fall</h2>

<div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
    if(!empty($_POST['reason']))
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($packing->createFall()){

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
			<button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Submit Reason for Short Fall&nbsp&nbsp<i class="fa fa-caret-down" style="display: inline;"></i></button>
			<ul class="dropdown-menu dropdown-menu-right">
				<li><a onclick="selectMachine(9,'Extruder 1')" data-toggle="modal" data-target="#modal1">Extruder 1</a></li>
				<li><a onclick="selectMachine(10,'Extruder 2')" data-toggle="modal" data-target="#modal1">Extruder 2</a></li>
			</ul>
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
     $packing->giveShortFall();
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
              <input type="hidden" class="form-control" id="id_transfer" name="id_transfer">
                <div class="form-group">
				<label for="date">Date <span class="text-danger">*</span></label>
				<div class='input-group date' id='datetimepicker2' required>
					<input type='text' class="form-control" id="date" name="date" />
					<span class="input-group-addon">
							<span class="fa fa-calendar"></span>
					</span>
				</div>
			</div>
			  
			  <div class="form-group">
				<label for="size">Machine <span class="text-danger">*</span></label><br />
				<input type="hidden" class="form-control" id="machine" name="machine" value="1" required>
				<input type="text" class="form-control" step="1" min="1" id="machineName"  value="" disabled>
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
                    <label >Reason For Short Fall <span class="text-danger">*</span></label>
                    <textarea type="text" class="form-control" rows="3" id="reason" name="reason" required></textarea>
                </div>
                <div class="form-group">
                    <label >Action Plan <span class="text-danger">*</span></label>
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
				document.getElementById("machineName").value = name;
		}
		  
        $(document).ready(function() {
             var d = new Date();
                var month = d.getMonth()+1;
                document.getElementById("date").value = d.getDate() + "/" + month +"/"+ d.getFullYear();
			$('#datetimepicker2').datetimepicker({         
                        format: 'DD/MM/YYYY'
                    });
                
                $('#datetimepicker2').data("DateTimePicker").maxDate(new Date());
                
            $('#dataTable').DataTable( {
                "order": [[ 0, "desc" ]]
            } );
                $('#timepicker').datetimepicker({
                    format: 'HH:mm'
                });
        } );
    </script>    
    

    
    <?php
    include_once '../../footer.php';
?>