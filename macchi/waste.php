<?php

    $pageTitle = "Macchi Waste";

     include_once "../base.php";
    include_once "../header.php";
    include_once "sidebar.php";
    include_once "../content.php";


    include_once "../inc/class.macchi.inc.php";
    $macchi = new Macchi($db);

?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="process.php">Macchi</a>
        </li>
        <li class="breadcrumb-item active">Waste</li>
    </ol>
    <h2>Macchi - Waste</h2>


    <div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
    if(!empty($_POST['shift']) )
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($macchi->createWaste()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
       
?>
    </div>

    <button class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" data-toggle="modal" data-target="#modal1">Submit waste</button>
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
                                <th>Job</th>
                                <th>User</th>
                                <th>Waste</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
     $macchi->giveWaste();
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
					<input type='text' class="form-control" id="date" name="date" required/>
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
                <label for="shift">Job <span class="text-danger">*</span></label><br />
                <input type="hidden" class="form-control" id="job" name="job" value="2" required>
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_job">Shrink Film&nbsp&nbsp<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a onclick="selectJob(2,'Shrink Film')">Shrink Film</a></li>
                        <li><a onclick="selectJob(1,'Water Pouch')">Water Pouch</a></li>
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <label for="total">Total waste <span class="text-danger">*</span></label>
                <input type="number" class="form-control" min="0" step="0.01" id="total" name="total" value="0" required>
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
			
			function selectJob(id, name) {
					document.getElementById("btn_job").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("job").value = id;
				}
        </script>
        <script>
            $(function() {
                $('#dataTable').DataTable( {
                "order": [[ 0, "desc" ]]} );
                var d = new Date();
                var month = d.getMonth() + 1;
                document.getElementById("date").value = d.getDate() + "/" + month + "/" + d.getFullYear();
				
				$('#datetimepicker2').datetimepicker({         
                        format: 'DD/MM/YYYY'
                    });
                
                $('#datetimepicker2').data("DateTimePicker").maxDate(new Date());
                
            })
        </script>
  
    
    <?php
    include_once '../footer.php';
?>