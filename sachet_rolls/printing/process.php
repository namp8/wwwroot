<?php
    $pageTitle = "Printing Process";
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar1.php";
    include_once "../../content.php";


    include_once "../../inc/class.printing.inc.php";
    $printing = new Printing($db);

?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="../process.php">Sachet Rolls</a>
        </li>
        <li class="breadcrumb-item">
            <a href="process.php">Printing</a>
        </li>
        <li class="breadcrumb-item active">Process</li>
    </ol>

    <h2>Printing - Process</h2>

      <img src="../../img/printing.PNG">

<div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
    if(!empty($_POST['action']) and $_POST['action'] !=0)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($printing->editSettings()){

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
    $printing->giveSettings();
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
                            <label id="labelModal"></label>
                            <input type="text" class="form-control" id="input" name="input">
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
        function cone680()
        {
            document.getElementById("action").value = 1;    
            document.getElementById("titleModal").innerHTML = "Edit cone weight for roll 680";
            document.getElementById("labelModal").innerHTML = "New cone weight for 680 mm (in kgs)";
        }
        function cone1010()
        {
            document.getElementById("action").value = 2;    
            document.getElementById("titleModal").innerHTML = "Edit cone weight for roll 1010";
            document.getElementById("labelModal").innerHTML = "New cone weight for 1010 mm (in kgs)";
        }
    </script>


    <?php
    include_once '../../footer.php';
?>