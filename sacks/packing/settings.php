<?php
    $pageTitle = "Sacks - Packing Settings";
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
            <a href="process.php">Sacks - Packing</a>
        </li>
        <li class="breadcrumb-item active">Settings</li>
    </ol>

    <h2>Sacks - Packing - Settings</h2>

 
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
                    <div class="col-lg-2 col-lg-offset-5  form-group">
                        <label for="date">Plant Capacity:</label>
                        <input type='text'  class="form-control text-center" id="target" disabled /><br />
                        <button  class="btn btn-default" onclick="target('target')" data-toggle="modal" data-target="#modal1">Edit Capacity</button>
                    </div>
				
				</div>
                    
                   
            </div>
        </div>
    </div>
 <?php
    $sacks->giveSettings(11);
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
		function target(name)
        {
            document.getElementById("action").value = name;  
            document.getElementById("machine").value = 31;    
            document.getElementById("titleModal").innerHTML = "Edit Plant Capacity ";
            document.getElementById("labelModal").innerHTML = "New Plant Capacity (in sacks)";
        }
		
    </script>


    <?php
    include_once '../../footer.php';
?>