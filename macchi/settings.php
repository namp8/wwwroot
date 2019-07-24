<?php
    $pageTitle = "Macchi Settings";
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
        <li class="breadcrumb-item active">Settings</li>
    </ol>

    <h2>Macchi - Settings</h2>

 
    <div id="alertMessage" class="alert hide" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            <?php
        if(!empty($_POST['action']) and $_POST['action'] !=0)
        {
            echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
            if($macchi->editSettings()){

                echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
            }
            else
            {
                echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
            }
        }
		if(!empty($_POST['size']))
        {
            echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
            if($macchi->addRollSize()){

                echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
            }
            else
            {
                echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
            }
        }
    ?>
    </div>

	<button class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" data-toggle="modal" data-target="#modal2">Add shrink film roll</button>

    <div class="panel panel-info">
        <div class="panel-heading"> Settings </div>
        <div class="panel-body">
            <div class="text-center" >
				<h2>Macchi - Settings</h2>
                    <div class="col-lg-4 form-group">
                        <label for="date">Water Pouch Capacity:</label>
                        <input type='text'  class="form-control text-center" id="targetRolls" disabled /><br />
                        <button  class="btn btn-default" onclick="targetRolls('targetRolls')" data-toggle="modal" data-target="#modal1">Edit Water Pouch Capacity</button>
                    </div>
                    
                    <div class="col-lg-4 form-group">
                        <label for="date">Shrink Film Capacity:</label>
                        <input type='text'  class="form-control text-center" id="targetShrink" disabled/><br />
                        <button class="btn btn-default" onclick="targetShrink('targetShrink')" data-toggle="modal" data-target="#modal1">Edit Shrink Film Capacity</button>
                    </div>
					<div class="col-lg-4 form-group">
                        <label for="date">Target Waste:</label>
                        <input type='text'  class="form-control text-center" id="waste" disabled/><br />
                        <button class="btn btn-default" onclick="targetWaste('waste')" data-toggle="modal" data-target="#modal1">Edit Target Waste</button>
                    </div>
				<h2>Water Pouch</h2>
					<div class="col-lg-6 form-group">
                        <label for="date">680 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="680pouch" disabled/><br />
                        <button class="btn btn-default" onclick="cone('680pouch')" data-toggle="modal" data-target="#modal1">Edit 680 cone</button>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="date">1010 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="1010pouch" disabled/><br />
                        <button class="btn btn-default" onclick="cone('1010pouch')" data-toggle="modal" data-target="#modal1">Edit 1010 cone</button>
					</div>				
				<h2>Shrink Film</h2>		
 <?php
    $macchi->giveSettingsShrinkRolls();
?>
            </div>
        </div>
    </div>
 <?php
    $macchi->giveSettings();
?>
    <div class="modal fade" id="modal1" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">x</button>
                    <h4 class="modal-title" id="titleModal"></h4>
                </div>
                <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <input type="hidden" class="form-control" id="action" name="action" value=0>
                    <div class="modal-body">
                         <div class="form-group">
                            <label id="labelModal"></label><span class="text-danger">*</span>
                            <input type="number" class="form-control" id="input" name="input" step="0.001" min="0.001" required>
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

	<div class="modal fade" id="modal2" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Add shrink film roll</h4>
                </div>
                <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="modal-body">
                         <div class="form-group">
                            <label>Roll size (mm)<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="size" name="size" step="1" min="1" required>
                        </div>
						
                         <div class="form-group">
                            <label>Cone Weight<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="weight" name="weight" value="0" step="0.001" min="0.001" required>
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
        
        function targetRolls(name)
        {
            document.getElementById("action").value = name;    
            document.getElementById("titleModal").innerHTML = "Edit Water Pouch Capacity";
            document.getElementById("labelModal").innerHTML = "New Water Pouch Capacity (in kgs)";
        }
		function targetShrink(name)
        {
            document.getElementById("action").value = name;    
            document.getElementById("titleModal").innerHTML = "Edit Shrink Film Capacity";
            document.getElementById("labelModal").innerHTML = "New Shrink Film Capacity (in kgs)";
        }
        function targetWaste(name)
        {
            document.getElementById("action").value = name;      
            document.getElementById("titleModal").innerHTML = "Edit Target for Waste";
            document.getElementById("labelModal").innerHTML = "New target for waste (in %)";
        }
        function cone(name)
        {
            document.getElementById("action").value = name;       
            document.getElementById("titleModal").innerHTML = "Edit weight for roll "  + name;
            document.getElementById("labelModal").innerHTML = "New weight for "+ name +" mm (in kgs)";
        }
    </script>


    <?php
    include_once '../footer.php';
?>