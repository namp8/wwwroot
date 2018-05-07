<?php
    $pageTitle = "Multilayer Batches";

    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar1.php";
    include_once "../../content.php";


    include_once "../../inc/class.multilayer.inc.php";
    $multilayer = new Multilayer($db);

?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="../process.php">Sachet Rolls</a>
        </li>
        <li class="breadcrumb-item">
            <a href="process.php">Multilayer</a>
        </li>
        <li class="breadcrumb-item active">Batches</li>
    </ol>
    <h2>Multilayer - Batches</h2>


    <div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
           <?php
    if(!empty($_POST['shift']) )
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($multilayer->createBatch()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
       
?>
    </div>
    
    <button class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" data-toggle="modal" data-target="#modal1">Submit batches</button>

        <div class="panel panel-info">
            <div class="panel-heading"> 
                Historic batches:
            </div>
            <div class="panel-body">
                
                <form class="form-inline" style="padding-bottom:20px;" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="form-group">
                            <label for="date">Date:</label>
                            <div class='input-group date' id='datetimepicker'>
                                <input type='text' class="form-control" name="dateSearch" id="dateSearch" />
                                <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" id="buttonForm" class="btn btn-info">View</button> 
                        </div>
                </form>
                <ul class="nav nav-tabs nav-justified">
                    <li class="active"><a data-toggle="tab" href="#today" id="dateTitle"></a></li>
                    <li><a data-toggle="tab" href="#day">Shift: Day</a></li>
                    <li><a data-toggle="tab" href="#night">Shift: Night</a></li>
                </ul>
                <div class="tab-content">
                    <div id="today" class="tab-pane fade in active">
                        <h3 id="dateTitle2"></h3>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover"width="100%" cellspacing="0">
                            <thead>
                                <tr class="active">
                                        <th width="25%"></th>
                                        <th width="15%" class="text-right">Outer</th>
                                        <th width="15%" class="text-right">Middle</th>
                                        <th width="15%" class="text-right">Inner</th>
                                        <th width="30%" style="text-align:center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
     $multilayer->giveBatches(0);
?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="day" class="tab-pane fade">
                        <h3>Day</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr class="active">
                                        <th width="25%"></th>
                                        <th width="15%" class="text-right">Outer</th>
                                        <th width="15%" class="text-right">Middle</th>
                                        <th width="15%" class="text-right">Inner</th>
                                        <th width="30%" style="text-align:center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
     $multilayer->giveBatches(1);
?>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                    <div id="night" class="tab-pane fade">
                        <h3>Night</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                        <th width="25%"></th>
                                        <th width="15%" class="text-right">Outer</th>
                                        <th width="15%" class="text-right">Middle</th>
                                        <th width="15%" class="text-right">Inner</th>
                                        <th width="30%" style="text-align:center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
     $multilayer->giveBatches(2);
?>
                                </tbody>
                            </table>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div> 

<!-- Modal-->
    <div class="modal fade" id="modal1" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-m">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal">x</button>
            <h4 class="modal-title">Submit batches</h4>
          </div>
        <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <div class="modal-body">
            <div class="form-group">
                <label for="date">Date</label>
                <div class='input-group date' id='datetimepicker2'>
                    <input type='text' class="form-control"  id="date" name="date"/>
                    <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                    </span>
                </div>
            </div>
          <div class="form-group">
                <label for="shift">Shift</label><br />
                <input type="hidden" class="form-control" id="shift" name="shift" required value="1">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_shift">Day&nbsp&nbsp<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a onclick="selectShift(1,'Day')">Day</a></li>
                        <li><a onclick="selectShift(2,'Night')">Night</a></li>
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <label for="layer">Layer</label><br />
                <input type="hidden" class="form-control" id="layer" name="layer" value="1" required>
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_layer" >Outer&nbsp&nbsp<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a onclick="selectLayer(1,'Outer')">Outer</a></li>
                        <li><a onclick="selectLayer(2,'Middle')">Middle</a></li>
                        <li><a onclick="selectLayer(3,'Inner')">Inner</a></li>
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <label for="total">Total</label>
                <input type="number" class="form-control" min="1" id="total" name="total" value="0" required>
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

<?php
  if(!empty($_POST['dateSearch']) )
   {
       echo '<script>document.getElementById("dateTitle").innerHTML = "'. $_POST['dateSearch'] .'";</script>';
      echo '<script>document.getElementById("dateTitle2").innerHTML = "'. $_POST['dateSearch'] .'";</script>';
       echo '<script>document.getElementById("dateSearch").value = "'. $_POST['dateSearch'] .'";</script>';
   }
 else
 {
       echo '<script>var d = new Date();
            var month = d.getMonth()+1;
            document.getElementById("dateTitle").innerHTML = d.getDate() + "/" + month +"/"+ d.getFullYear();
            document.getElementById("dateTitle2").innerHTML = d.getDate() + "/" + month +"/"+ d.getFullYear();
            document.getElementById("dateSearch").value = d.getDate() + "/" + month +"/"+ d.getFullYear();</script>';
 }
    
?>
    <script>
        function selectLayer(id, name) {
            document.getElementById("btn_layer").innerHTML = name+" &nbsp&nbsp<span class='caret'></span> ";
            document.getElementById("layer").value = id;
        }

        function selectShift(id, name) {
            document.getElementById("btn_shift").innerHTML = name+" &nbsp&nbsp<span class='caret'></span> ";
            document.getElementById("shift").value = id;
            if(id==1)
            {
                var d = new Date();
                var month = d.getMonth()+1;
                document.getElementById("date").value = d.getDate() + "/" + month +"/"+ d.getFullYear();
            }
            else
            {
                var d = new Date();
                d.setDate(d.getDate() -1);
                var month = d.getMonth()+1;
                document.getElementById("date").value = d.getDate() + "/" + month +"/"+ d.getFullYear();
            }
        }
    </script>
    <script>
			$(function () {
                // #datePicker
                $('#datetimepicker').datetimepicker({         
                        format: 'DD/MM/YYYY'
                    });
                
                $('#datetimepicker').data("DateTimePicker").maxDate(new Date());
                
                $('#datetimepicker2').datetimepicker({         
                        format: 'DD/MM/YYYY'
                    });
                
                $('#datetimepicker2').data("DateTimePicker").maxDate(new Date());
                
                var d = new Date();
                var month = d.getMonth()+1;
                document.getElementById("date").value = d.getDate() + "/" + month +"/"+ d.getFullYear();
                  

			})
    </script>            

    <?php
    include_once '../../footer.php';
?>