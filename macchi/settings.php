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
                        <button class="btn btn-default" onclick="cone('680cone')" data-toggle="modal" data-target="#modal1">Edit 680 cone</button>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="date">1010 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="1010cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('1010cone')" data-toggle="modal" data-target="#modal1">Edit 1010 cone</button>
					</div>				
				<h2>Shrink Film</h2>
					<div class="col-lg-2 form-group">
                        <label for="date">300 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="300cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('300cone')" data-toggle="modal" data-target="#modal1">Edit 300 cone</button>
                    </div>
					<div class="col-lg-2 form-group">
                        <label for="date">330 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="330cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('330cone')" data-toggle="modal" data-target="#modal1">Edit 330 cone</button>
                    </div>
					<div class="col-lg-2 form-group">
                        <label for="date">340 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="340cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('340cone')" data-toggle="modal" data-target="#modal1">Edit 340 cone</button>
                    </div>
                    <div class="col-lg-2 form-group">
                        <label for="date">350 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="350cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('350cone')" data-toggle="modal" data-target="#modal1">Edit 350 cone</button>
					</div>
				
                    <div class="col-lg-2 form-group">
                        <label for="date">355 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="355cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('355cone')" data-toggle="modal" data-target="#modal1">Edit 355 cone</button>
					</div>
					<div class="col-lg-2 form-group">
                        <label for="date">360 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="360cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('360cone')" data-toggle="modal" data-target="#modal1">Edit 360 cone</button>
                    </div>
                    <div class="col-lg-2 form-group">
                        <label for="date">370 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="370cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('370cone')" data-toggle="modal" data-target="#modal1">Edit 370 cone</button>
					</div>
				
                    <div class="col-lg-2 form-group">
                        <label for="date">380 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="380cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('380cone')" data-toggle="modal" data-target="#modal1">Edit 380 cone</button>
					</div>
                    <div class="col-lg-2 form-group">
                        <label for="date">390 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="390cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('390cone')" data-toggle="modal" data-target="#modal1">Edit 390 cone</button>
					</div>
					<div class="col-lg-2 form-group">
                        <label for="date">400 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="400cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('400cone')" data-toggle="modal" data-target="#modal1">Edit 400 cone</button>
					</div>
					<div class="col-lg-2 form-group">
                        <label for="date">420 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="420cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('420cone')" data-toggle="modal" data-target="#modal1">Edit 420 cone</button>
					</div>
					<div class="col-lg-2 form-group">
                        <label for="date">430 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="430cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('430cone')" data-toggle="modal" data-target="#modal1">Edit 430 cone</button>
					</div>
				<div class="col-lg-2 form-group">
                        <label for="date">435 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="435cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('435cone')" data-toggle="modal" data-target="#modal1">Edit 435 cone</button>
					</div>
				<div class="col-lg-2 form-group">
                        <label for="date">440 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="440cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('440cone')" data-toggle="modal" data-target="#modal1">Edit 440 cone</button>
                    </div>
				<div class="col-lg-2 form-group">
                        <label for="date">445 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="445cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('445cone')" data-toggle="modal" data-target="#modal1">Edit 445 cone</button>
                    </div>
				
				<div class="col-lg-2 form-group">
                        <label for="date">450 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="450cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('450cone')" data-toggle="modal" data-target="#modal1">Edit 450 cone</button>
                    </div>
                    <div class="col-lg-2 form-group">
                        <label for="date">500 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="500cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('500cone')" data-toggle="modal" data-target="#modal1">Edit 500 cone</button>
					</div>
				<div class="col-lg-2 form-group">
                        <label for="date">505 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="505cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('505cone')" data-toggle="modal" data-target="#modal1">Edit 505 cone</button>
					</div>
                    <div class="col-lg-2 form-group">
                        <label for="date">532 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="532cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('532cone')" data-toggle="modal" data-target="#modal1">Edit 532 cone</button>
					</div>
				
					<div class="col-lg-2 form-group">
                        <label for="date">600 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="600cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('600cone')" data-toggle="modal" data-target="#modal1">Edit 600 cone</button>
					</div>
					<div class="col-lg-2 form-group">
                        <label for="date">665 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="665cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('665cone')" data-toggle="modal" data-target="#modal1">Edit 665 cone</button>
					</div>
					<div class="col-lg-2 form-group">
                        <label for="date">680 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="680cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('680cone')" data-toggle="modal" data-target="#modal1">Edit 680 cone</button>
					</div>
					<div class="col-lg-2 form-group">
                        <label for="date">730 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="730cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('730cone')" data-toggle="modal" data-target="#modal1">Edit 730 cone</button>
					</div>
					<div class="col-lg-2 form-group">
                        <label for="date">770 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="770cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('770cone')" data-toggle="modal" data-target="#modal1">Edit 770 cone</button>
					</div>
					<div class="col-lg-2 form-group">
                        <label for="date">870 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="870cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('870cone')" data-toggle="modal" data-target="#modal1">Edit 870 cone</button>
					</div>
					<div class="col-lg-2 form-group">
                        <label for="date">1680 mm - Cone wt:</label>
                        <input type='text'  class="form-control text-center" id="1680cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone('1680cone')" data-toggle="modal" data-target="#modal1">Edit 1680 cone</button>
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