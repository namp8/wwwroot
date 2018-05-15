<?php
    $pageTitle = "Sacks - Extruder Settings";
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar.php";
    include_once "../../content.php";


    include_once "../../inc/class.sacks.inc.php";
    $sacks = new Sacks($db);

?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="../process.php">Sacks</a>
        </li>
        <li class="breadcrumb-item active">Extruder - Settings</li>
    </ol>

    <h2>Sacks  - Extruder - Settings</h2>

 
    <div id="alertMessage" class="alert hide" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            <?php
        if(!empty($_POST['action']))
        {
            echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
            if($sacks->editSettings()){

                echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
            }
            else
            {
                echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
            }
        }
    ?>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading"> Settings </div>
        <div class="panel-body">
            <div class="text-center" >
				<div class="row">
                    <div class="col-lg-3 col-lg-offset-3 form-group">
                        <label for="date">Target waste:</label>
                        <input type='text'  class="form-control text-center" id="waste" disabled/><br />
                        <button class="btn btn-default" onclick="targetWaste('waste')" data-toggle="modal" data-target="#modal1">Edit Target waste</button>
                    </div>
                    <div class="col-lg-3 form-group">
                        <label for="date">Cone weight:</label>
                        <input type='text'  class="form-control text-center" id="cone" disabled /><br />
                        <button  class="btn btn-default" onclick="cone('cone')" data-toggle="modal" data-target="#modal1">Edit cone</button>
                    </div></div>
				<div class="row">
                    <div class="col-lg-3  form-group">
                        <label for="date">M/C No.1 Capacity:</label>
                        <input type='text'  class="form-control text-center" id="target1" disabled /><br />
                        <button  class="btn btn-default" onclick="target1('target1')" data-toggle="modal" data-target="#modal1">Edit Capacity</button>
                    </div>
				
					<div class="col-lg-3 form-group">
                        <label for="date">M/C No.2 Capacity:</label>
                        <input type='text'  class="form-control text-center" id="target2" disabled /><br />
                        <button  class="btn btn-default" onclick="target2('target2')" data-toggle="modal" data-target="#modal1">Edit Capacity</button>
                    </div>
					<div class="col-lg-3 form-group">
                        <label for="date">M/C No.3 Capacity:</label>
                        <input type='text'  class="form-control text-center" id="target3" disabled /><br />
                        <button  class="btn btn-default" onclick="target3('target3')" data-toggle="modal" data-target="#modal1">Edit Capacity</button>
                    </div>
					<div class="col-lg-3 form-group">
                        <label for="date">M/C No.4 Capacity:</label>
                        <input type='text'  class="form-control text-center" id="target4" disabled /><br />
                        <button  class="btn btn-default" onclick="target4('target4')" data-toggle="modal" data-target="#modal1">Edit Capacity</button>
                    </div>
					<div class="col-lg-3 form-group">
                        <label for="date">M/C No.5 Capacity:</label>
                        <input type='text'  class="form-control text-center" id="target5" disabled /><br />
                        <button  class="btn btn-default" onclick="target5('target5')" data-toggle="modal" data-target="#modal1">Edit Capacity</button>
                    </div>
					<div class="col-lg-3 form-group">
                        <label for="date">M/C No.6 Capacity:</label>
                        <input type='text'  class="form-control text-center" id="target6" disabled /><br />
                        <button  class="btn btn-default" onclick="target6('target6')" data-toggle="modal" data-target="#modal1">Edit Capacity</button>
                    </div>
				<div class="col-lg-3 form-group">
                        <label for="date">M/C No.7 Capacity:</label>
                        <input type='text'  class="form-control text-center" id="target7" disabled /><br />
                        <button  class="btn btn-default" onclick="target7('target7')" data-toggle="modal" data-target="#modal1">Edit Capacity</button>
                    </div>
				<div class="col-lg-3 form-group">
                        <label for="date">M/C No.8 Capacity:</label>
                        <input type='text'  class="form-control text-center" id="target8" disabled /><br />
                        <button  class="btn btn-default" onclick="target8('target8')" data-toggle="modal" data-target="#modal1">Edit Capacity</button>
                    </div>
				</div>
                    
                   
            </div>
        </div>
    </div>
 <?php
    $sacks->giveSettings(7);
?>
    <div class="modal fade" id="modal1" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">x</button>
                    <h4 class="modal-title" id="titleModal"></h4>
                </div>
                <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <input type="hidden" class="form-control" id="action" name="action">
                    <input type="hidden" class="form-control" id="machine" name="machine" value=7>
                    <div class="modal-body">
                         <div class="form-group">
                            <label id="labelModal"></label><span class="text-danger">*</span>
                            <input type="text" class="form-control" id="input" name="input" required>
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
        function targetWaste(name)
        {
            document.getElementById("action").value = name;      
            document.getElementById("titleModal").innerHTML = "Edit Target for Waste";
            document.getElementById("labelModal").innerHTML = "New target for waste (in %)";
        }
        function cone(name)
        {
            document.getElementById("action").value = name;     
            document.getElementById("titleModal").innerHTML = "Edit cone weight ";
            document.getElementById("labelModal").innerHTML = "New cone weight (in kgs)";
        }
		function target1(name)
        {
            document.getElementById("action").value = name;  
            document.getElementById("machine").value = 13;    
            document.getElementById("titleModal").innerHTML = "Edit M/C No.1 Capacity ";
            document.getElementById("labelModal").innerHTML = "New M/C No.1 Capacity (in kgs)";
        }
		function target2(name)
        {
            document.getElementById("action").value = name;     
            document.getElementById("machine").value = 14;  
            document.getElementById("titleModal").innerHTML = "Edit M/C No.2 Capacity ";
            document.getElementById("labelModal").innerHTML = "New M/C No.2 Capacity (in kgs)";
        }
		function target3(name)
        {
            document.getElementById("action").value = name;   
            document.getElementById("machine").value = 15;    
            document.getElementById("titleModal").innerHTML = "Edit M/C No.3 Capacity ";
            document.getElementById("labelModal").innerHTML = "New M/C No.3 Capacity (in kgs)";
        }
		function target4(name)
        {
            document.getElementById("action").value = name;   
            document.getElementById("machine").value = 16;    
            document.getElementById("titleModal").innerHTML = "Edit M/C No.4 Capacity ";
            document.getElementById("labelModal").innerHTML = "New M/C No.4 Capacity (in kgs)";
        }
		function target5(name)
        {
            document.getElementById("action").value = name;   
            document.getElementById("machine").value = 17;    
            document.getElementById("titleModal").innerHTML = "Edit M/C No.5 Capacity ";
            document.getElementById("labelModal").innerHTML = "New M/C No.5 Capacity (in kgs)";
        }
		function target6(name)
        {
            document.getElementById("action").value = name;    
            document.getElementById("machine").value = 18;   
            document.getElementById("titleModal").innerHTML = "Edit M/C No.6 Capacity ";
            document.getElementById("labelModal").innerHTML = "New M/C No.6 Capacity (in kgs)";
        }
		function target7(name)
        {
            document.getElementById("action").value = name;  
            document.getElementById("machine").value = 19;     
            document.getElementById("titleModal").innerHTML = "Edit M/C No.7 Capacity ";
            document.getElementById("labelModal").innerHTML = "New M/C No.7 Capacity (in kgs)";
        }
		function target8(name)
        {
            document.getElementById("action").value = name;    
            document.getElementById("machine").value = 20;   
            document.getElementById("titleModal").innerHTML = "Edit M/C No.8 Capacity ";
            document.getElementById("labelModal").innerHTML = "New M/C No.8 Capacity (in kgs)";
        }
		
    </script>


    <?php
    include_once '../../footer.php';
?>