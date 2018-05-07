<?php
    $pageTitle = "Settings - Materials, Master Batch, Ink and Solvents";
    include_once "../base.php";
    include_once "../header.php";
    include_once "sidebar.php";
    include_once "../content.php";


    include_once "../inc/class.materials.inc.php";
    $materials = new Materials($db);
	
	include_once "../inc/class.users.inc.php";
    $users = new Users($db);

if(!$users->access(6))
	{
		echo "<meta http-equiv='refresh' content='0;/index.php'>";
        exit;
	}
?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="home.php">Settings</a>
        </li>
        <li class="breadcrumb-item active">Materials</li>
    </ol>
    <h2>Settings - Materials</h2>

<div id="alertMessage" class="alert hide" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>

<?php
    if($_SERVER["REQUEST_METHOD"] == "POST" and !empty($_POST['material']))
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($materials->createMaterial()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
	else if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		echo '<strong>ERROR</strong> The material is a required field. Please try again, selecting a material from the dropdown.<br>';
		echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
		
	}
?>
	</div>


    <button class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" onclick="exportToPDF()">Create Material</button>

    <div class="panel panel-info">
        <div class="panel-heading"> List of Materials</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                            <th>Material</th>
                            <th>Grade</th>
                            <th>Kgs per bag</th>
                            <th>Sacks</th>
                            <th>Multilayer</th>
                            <th>Injection</th>
                            <th>Macchi</th>
                            <th>Packing Bags</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
    $materials->giveMaterials();
?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


	<script>
		
		
		$(document).ready(function() {
			$("#dataTable").DataTable({
				"order": [],
				"lengthMenu": [[-1, 10, 25, 50, 100], ["All", 10, 25, 50, 100]]
				
			});
		});
		
	     
        </script>
    <?php
    include_once '../footer.php';
?>