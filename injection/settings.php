<?php
    $pageTitle = "Injection - Settings";
    include_once "../base.php";
    include_once "../header.php";
    include_once "sidebar.php";
    include_once "../content.php";


    include_once "../inc/class.injection.inc.php";
    $injection = new Injection($db);

?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="../process.php">Injection</a>
        </li>
        <li class="breadcrumb-item active">Settings</li>
    </ol>

    <h2>Injection  - Settings</h2>

 
    <div id="alertMessage" class="alert hide" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            <?php
        if(!empty($_POST['action']))
        {
            echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
            if($injection->editSettings()){

                echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
            }
            else
            {
                echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
            }
        }
    ?>
    </div>



    <div class="pull-right" style="margin-top:5px;margin-right:30px;">
        <div class="dropdown" >
            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Edit Settings&nbsp&nbsp <i class="fa fa-caret-down" style="display: inline;"></i></button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a onclick="add()" data-toggle="modal" data-target="#modal1">Add settings</a></li>
                <li><a onclick="update()" data-toggle="modal" data-target="#modal1">Edit settings</a></li>
                <li><a onclick="deleteFormula()" data-toggle="modal" data-target="#modal1">Delete setting</a></li>
            </ul>
         </div>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading"> Settings </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered  table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="active">
                            <th>Product Name</th>
                            <th>Cycle Time (secs)</th>
                            <th>No. of cavities</th>
                            <th>Production Target</th>
                            <th>Product weight (g)</th>
                            <th>Pieces in sack</th>
                            <th>Sacks weight (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
    $injection->giveSettings();
?>
                    </tbody>
                </table>

            </div>
            
                    
                   
        </div>
    </div>

<script>$(document).ready(function() {
			$("#dataTable").DataTable({
				"order": [],
				"lengthMenu": [[-1, 10, 25, 50, 100], ["All", 10, 25, 50, 100]]
			});
		});</script>


    <?php
    include_once '../footer.php';
?>