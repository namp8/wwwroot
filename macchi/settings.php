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
    ?>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading"> Settings </div>
        <div class="panel-body">
            <div class="text-center" >
				<h2>Macchi - Settings</h2>
                    <div class="col-lg-4 form-group">
                        <label for="date">Water Pouch Capacity:</label>
                        <input type='text'  class="form-control text-center" id="targetRolls" disabled /><br />
                        <button  class="btn btn-default" onclick="targetRolls('targetRolls')" data-toggle="modal" data-target="#modal1">Water Pouch Capacity</button>
                    </div>
                    
                    <div class="col-lg-4 form-group">
                        <label for="date">Shrink Film Capacity:</label>
                        <input type='text'  class="form-control text-center" id="targetShrink" disabled/><br />
                        <button class="btn btn-default" onclick="targetShrink('targetShrink')" data-toggle="modal" data-target="#modal1">Edit Shrink Film Capacity</button>
                    </div>
					<div class="col-lg-4 form-group">
                        <label for="date">Target Waste:</label>
                        <input type='text'  class="form-control text-center" id="waste" disabled/><br />
                        <button class="btn btn-default" onclick="targetWaste('waste')" data-toggle="modal" data-target="#modal1">Edit TShrink Film Capacity</button>
                    </div>
				<h2>Water Pouch</h2>
					<div class="col-lg-6 form-group">
                        <label for="date">680 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="680cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone680('680cone')" data-toggle="modal" data-target="#modal1">Edit 680 cone</button>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="date">1010 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="1010cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone1010('1010cone')" data-toggle="modal" data-target="#modal1">Edit 1010 cone</button>
					</div>				
				<h2>Shrink Film</h2>
					<div class="col-lg-2 form-group">
                        <label for="date">340 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="340cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone340('340cone')" data-toggle="modal" data-target="#modal1">Edit 340 cone</button>
                    </div>
                    <div class="col-lg-2 form-group">
                        <label for="date">350 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="350cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone350('350cone')" data-toggle="modal" data-target="#modal1">Edit 350 cone</button>
					</div>
					<div class="col-lg-2 form-group">
                        <label for="date">360 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="360cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone360('360cone')" data-toggle="modal" data-target="#modal1">Edit 360 cone</button>
                    </div>
                    <div class="col-lg-2 form-group">
                        <label for="date">370 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="370cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone370('370cone')" data-toggle="modal" data-target="#modal1">Edit 370 cone</button>
					</div>
				
                    <div class="col-lg-2 form-group">
                        <label for="date">380 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="380cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone380('380cone')" data-toggle="modal" data-target="#modal1">Edit 380 cone</button>
					</div>
					<div class="col-lg-2 form-group">
                        <label for="date">420 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="420cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone420('420cone')" data-toggle="modal" data-target="#modal1">Edit 420 cone</button>
					</div>
				<div class="col-lg-2 form-group">
                        <label for="date">445 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="445cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone445('445cone')" data-toggle="modal" data-target="#modal1">Edit 445 cone</button>
                    </div>
                    <div class="col-lg-2 form-group">
                        <label for="date">500 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="500cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone500('500cone')" data-toggle="modal" data-target="#modal1">Edit 500 cone</button>
					</div>
				
                    <div class="col-lg-2 form-group">
                        <label for="date">532 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="532cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone532('532cone')" data-toggle="modal" data-target="#modal1">Edit 532 cone</button>
					</div>
					<div class="col-lg-2 form-group">
                        <label for="date">665 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="665cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone665('665cone')" data-toggle="modal" data-target="#modal1">Edit 665 cone</button>
					</div>
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
        function cone680(name)
        {
            document.getElementById("action").value = name;     
            document.getElementById("titleModal").innerHTML = "Edit cone weight for rolls 680";
            document.getElementById("labelModal").innerHTML = "New cone weight for 680 mm (in kgs)";
        }
        function cone1010(name)
        {
            document.getElementById("action").value = name;    
            document.getElementById("titleModal").innerHTML = "Edit cone weight for roll 1010";
            document.getElementById("labelModal").innerHTML = "New cone weight for 1010 mm (in kgs)";
        }
		function cone340(name)
        {
            document.getElementById("action").value = name;     
            document.getElementById("titleModal").innerHTML = "Edit cone weight for rolls 340";
            document.getElementById("labelModal").innerHTML = "New cone weight for 340 mm (in kgs)";
        }
        function cone350(name)
        {
            document.getElementById("action").value = name;     
            document.getElementById("titleModal").innerHTML = "Edit cone weight for roll 350";
            document.getElementById("labelModal").innerHTML = "New cone weight for 350 mm (in kgs)";
        }
		function cone360(name)
        {
            document.getElementById("action").value = name;    
            document.getElementById("titleModal").innerHTML = "Edit cone weight for rolls 360";
            document.getElementById("labelModal").innerHTML = "New cone weight for 360 mm (in kgs)";
        }
        function cone370(name)
        {
            document.getElementById("action").value = name;       
            document.getElementById("titleModal").innerHTML = "Edit cone weight for roll 370";
            document.getElementById("labelModal").innerHTML = "New cone weight for 370 mm (in kgs)";
        }
		function cone380(name)
        {
            document.getElementById("action").value = name;      
            document.getElementById("titleModal").innerHTML = "Edit cone weight for rolls 380";
            document.getElementById("labelModal").innerHTML = "New cone weight for 380 mm (in kgs)";
        }
        function cone420(name)
        {
            document.getElementById("action").value = name;       
            document.getElementById("titleModal").innerHTML = "Edit cone weight for roll 420";
            document.getElementById("labelModal").innerHTML = "New cone weight for 420 mm (in kgs)";
        }
		function cone445(name)
        {
            document.getElementById("action").value = name;    
            document.getElementById("titleModal").innerHTML = "Edit cone weight for rolls 445";
            document.getElementById("labelModal").innerHTML = "New cone weight for 445 mm (in kgs)";
        }
        function cone500(name)
        {
            document.getElementById("action").value = name;       
            document.getElementById("titleModal").innerHTML = "Edit cone weight for roll 500";
            document.getElementById("labelModal").innerHTML = "New cone weight for 500 mm (in kgs)";
        }
		function cone532(name)
        {
            document.getElementById("action").value = name;      
            document.getElementById("titleModal").innerHTML = "Edit cone weight for rolls 532";
            document.getElementById("labelModal").innerHTML = "New cone weight for 532 mm (in kgs)";
        }
        function cone665(name)
        {
            document.getElementById("action").value = name;       
            document.getElementById("titleModal").innerHTML = "Edit cone weight for roll 665";
            document.getElementById("labelModal").innerHTML = "New cone weight for 665 mm (in kgs)";
        }
    </script>


    <?php
    include_once '../footer.php';
?>