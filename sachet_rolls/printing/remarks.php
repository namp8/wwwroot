<?php
    $pageTitle = "Printing Short Falls";
    
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar1.php";
    include_once "../../content.php";


    include_once "../../inc/class.printing.inc.php";
    $printing = new printing($db);

?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="../process.php">Sachet Rolls</a>
        </li>
        <li class="breadcrumb-item">
            <a href="process.php">Printing</a>
        </li>
        <li class="breadcrumb-item active">Short Fall</li>
    </ol>
    <h2>Printing - Short Fall</h2>

<div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
    if(!empty($_POST['reason']))
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($printing->createFall()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
?>
    </div>

 <button class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" data-toggle="modal" data-target="#modal1">Submit Reason for Short Fall</button>



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
     $printing->giveShortFall();
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
                    <label for="date">Date</label>
                    <div class='input-group date' id='datetimepicker'>
                        <input type='text' class="form-control" id="date" name="date" />
                        <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label >Machine</label><br />
                    <input type="hidden" class="form-control" id="machine" name="machine" value="3">
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_machine" style="height:30px;">Roto&nbsp&nbsp<span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a onclick="selectMachine(3,'Roto')">Roto</a></li>
                            <li><a onclick="selectMachine(4,'Flexo 1')">Flexo 1</a></li>
                            <li><a onclick="selectMachine(5,'Flexo 2')">Flexo 2</a></li>
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <label>Downtime (HH:mm)</label>
                    <div class='input-group date' id='timepicker'>
                        <input type='text' class="form-control" id="time" name="time" required/>
                        <span class="input-group-addon">
                            <span class="fa fa-clock-o"></span>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label >Reason For Short Fall</label>
                    <textarea type="text" class="form-control" rows="3" id="reason" name="reason"></textarea>
                </div>
                <div class="form-group">
                    <label >Action Plan</label>
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
            document.getElementById("btn_machine").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
            document.getElementById("machine").value = id;
        }
          
        $(document).ready(function() {
			 $('#datetimepicker').datetimepicker({
                        format: 'DD/MM/YYYY'
                    });


                    $('#datetimepicker').data("DateTimePicker").maxDate(new Date());
					
             var d = new Date();
                var month = d.getMonth()+1;
                document.getElementById("date").value = d.getDate() + "/" + month +"/"+ d.getFullYear();
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