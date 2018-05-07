<?php
    $pageTitle = "Multilayer Settings";
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
        <li class="breadcrumb-item active">Settings</li>
    </ol>

    <h2>Multilayer - Settings</h2>

 
    <div id="alertMessage" class="alert hide" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            <?php
        if(!empty($_POST['action']) and $_POST['action'] !=0)
        {
            echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
            if($multilayer->editSettings()){

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
				
                    <div class="col-lg-2 form-group">
                        <label for="date">Machine Capacity:</label>
                        <input type='text'  class="form-control text-center" id="target" disabled /><br />
                        <button  class="btn btn-default" onclick="target()" data-toggle="modal" data-target="#modal1">Edit Capacity</button>
                    </div>
                    <div class="col-lg-2 form-group">
                        <label for="date">Thickness:</label>
                        <input type='text' class="form-control text-center" id="thickness" disabled/><br />
                        <button  class="btn btn-default" onclick="thick()" data-toggle="modal" data-target="#modal1">Edit Thickness</button>
                    </div>
                    <div class="col-lg-2 form-group">
                        <label for="date">Treatment level:</label>
                        <input type='text' class="form-control text-center" id="treatment" disabled /><br />
                        <button  class="btn btn-default" onclick="treat()" data-toggle="modal" data-target="#modal1">Edit Treatment</button>
                    </div>
                    <div class="col-lg-2 form-group">
                        <label for="date">Target waste:</label>
                        <input type='text'  class="form-control text-center" id="waste" disabled/><br />
                        <button class="btn btn-default" onclick="targetWaste()" data-toggle="modal" data-target="#modal1">Edit Target waste</button>
                    </div>
                    <div class="col-lg-2 form-group">
                        <label for="date">680 - Cone weight:</label>
                        <input type='text'  class="form-control text-center" id="680cone" disabled /><br />
                        <button  class="btn btn-default" onclick="cone680()" data-toggle="modal" data-target="#modal1">Edit 680 cone</button>
                    </div>
                    <div class="col-lg-2 form-group">
                        <label for="date">1010 - Cone weight:</label>
                        <input type='text'  class="form-control text-center" id="1010cone" disabled/><br />
                        <button class="btn btn-default" onclick="cone1010()" data-toggle="modal" data-target="#modal1">Edit 1010 cone</button>
                    </div>
            </div>
        </div>
    </div>
 <?php
    $multilayer->giveSettings();
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
        function thick()
        {
            document.getElementById("action").value = 1;    
            document.getElementById("titleModal").innerHTML = "Edit Thickness";
            document.getElementById("labelModal").innerHTML = "New Thickness";
        }
        function treat()
        {
            document.getElementById("action").value = 2;    
            document.getElementById("titleModal").innerHTML = "Edit Treatment level";
            document.getElementById("labelModal").innerHTML = "New Treatment level";
        }
        function target()
        {
            document.getElementById("action").value = 3;    
            document.getElementById("titleModal").innerHTML = "Edit Machine Capacity for Production";
            document.getElementById("labelModal").innerHTML = "New Machine Capacity for production (in kgs)";
        }
        function targetWaste()
        {
            document.getElementById("action").value = 4;    
            document.getElementById("titleModal").innerHTML = "Edit Target for Waste";
            document.getElementById("labelModal").innerHTML = "New target for waste (in %)";
        }
        function cone680()
        {
            document.getElementById("action").value = 5;    
            document.getElementById("titleModal").innerHTML = "Edit cone weight for roll 680";
            document.getElementById("labelModal").innerHTML = "New cone weight for 680 mm (in kgs)";
        }
        function cone1010()
        {
            document.getElementById("action").value = 6;    
            document.getElementById("titleModal").innerHTML = "Edit cone weight for roll 1010";
            document.getElementById("labelModal").innerHTML = "New cone weight for 1010 mm (in kgs)";
        }
    </script>


    <?php
    include_once '../../footer.php';
?>