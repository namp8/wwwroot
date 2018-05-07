<?php
    $pageTitle = "Packing Bags Settings";
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar.php";
    include_once "../../content.php";


    include_once "../../inc/class.packing.inc.php";
    $packing = new Packing($db);

?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="process.php">Packing Bags</a>
        </li>
        <li class="breadcrumb-item active">Settings</li>
    </ol>

    <h2>Packing Bags - Settings</h2>

 
    <div id="alertMessage" class="alert hide" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            <?php
        if(!empty($_POST['action']) and $_POST['action'] !=0)
        {
            echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
            if($packing->editSettings()){

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
                        <label for="date">Machines Capacity:</label>
                        <input type='text'  class="form-control text-center" id="target" disabled /><br />
                        <button  class="btn btn-default" onclick="target()" data-toggle="modal" data-target="#modal1">Edit Capacity</button>
                    </div>
				
                    <div class="col-lg-2 form-group">
                        <label for="date">Thickness:</label>
                        <input type='text' class="form-control text-center" id="thickness" disabled/><br />
                        <button  class="btn btn-default" onclick="thick()" data-toggle="modal" data-target="#modal1">Edit Thickness</button>
                    </div>
                   
                    <div class="col-lg-2 form-group">
                        <label for="date">Target waste:</label>
                        <input type='text'  class="form-control text-center" id="waste" disabled/><br />
                        <button class="btn btn-default" onclick="targetWaste()" data-toggle="modal" data-target="#modal1">Edit Target waste</button>
                    </div>
                    <div class="col-lg-2 form-group">
                        <label for="date">Cone weight:</label>
                        <input type='text'  class="form-control text-center" id="cone" disabled /><br />
                        <button  class="btn btn-default" onclick="cone()" data-toggle="modal" data-target="#modal1">Edit cone</button>
                    </div>
            </div>
        </div>
    </div>
 <?php
    $packing->giveSettings();
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
        function target()
        {
            document.getElementById("action").value = 3;    
            document.getElementById("titleModal").innerHTML = "Edit Target for Production";
            document.getElementById("labelModal").innerHTML = "New target for production (in kgs)";
        }
        function targetWaste()
        {
            document.getElementById("action").value = 4;    
            document.getElementById("titleModal").innerHTML = "Edit Target for Waste";
            document.getElementById("labelModal").innerHTML = "New target for waste (in %)";
        }
        function cone()
        {
            document.getElementById("action").value = 5;    
            document.getElementById("titleModal").innerHTML = "Edit cone weight";
            document.getElementById("labelModal").innerHTML = "New cone weight (in kgs)";
        }
    </script>


    <?php
    include_once '../../footer.php';
?>