<?php
    $pageTitle = "Settings - Operators";
    include_once "../base.php";
    include_once "../header.php";
    include_once "sidebar.php";
    include_once "../content.php";


    include_once "../inc/class.general.inc.php";
    $general = new General($db);

if(!$users->access('settings'))
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
        <li class="breadcrumb-item active">Operators</li>
    </ol>
    <h2>Settings - Operators</h2>

<div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
    if(!empty($_POST['action']) and $_POST['action'] ==1)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($general->createOperator()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
    if(!empty($_POST['action']) and $_POST['action'] ==2)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($general->updateOperator()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
    if(!empty($_POST['action']) and $_POST['action'] ==3)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($general->deleteOperator()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-warning show");</script>';
        }
    }
      
       
?>
    </div>
	<div class="pull-right text-right">
			<button class="btn btn-info btn-add" type="button" data-toggle="modal" data-target="#modal1" onclick="add()">Create Operator</button>
	</div>
        <div class="panel panel-info">
            <div class="panel-heading">Operators</div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-xs" id="dataTable" width="100%" cellspacing="0">
                        <thead>
							<tr class="active">
                                <th colspan="3"></th>
                                <th colspan="8">Sections</th>
                            </tr>
                            <tr class="active">
                                <th></th>
                                <th></th>
                                <th>Operator Name</th>
                                <th>Sacks</th>
								<th>Multilayer</th>
								<th>Printing</th>
								<th>Slitting</th>
								<th>Injection</th>
								<th>Macchi</th>
								<th>Packing Bags</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
    $general->giveOperatorsList();
?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <!-- Logout Modal-->
    <div class="modal fade" id="modal1" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-m">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal">x</button>
            <h4 class="modal-title" id="panelTitle">Create Operator</h4>
          </div>
            <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                
          <div class="modal-body">
                <input type="hidden" class="form-control" id="action" name="action" required>
                <input type="hidden" class="form-control" id="id_operator" name="id_operator" required>
               
                <div class="form-group">
                    <label for="form">Operator Name</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="form-group" id="views">
                    <label for="form">Sections</label><br>
                    <input type="checkbox" id="Sacks" name="view[]" value="Sacks">Sacks<br>
                    <input type="checkbox"  id="Multilayer" name="view[]" value="Multilayer">Multilayer<br>
                    <input type="checkbox" id="Printing" name="view[]" value="Printing">Printing<br>
                    <input type="checkbox" id="Slitting" name="view[]" value="Slitting">Slitting<br>
					<input type="checkbox" id="Injection" name="view[]" value="Injection">Injection<br>
                    <input type="checkbox"  id="Macchi" name="view[]" value="Macchi">Macchi<br>
                    <input type="checkbox" id="Packing" name="view[]" value="Packing">Packing Bags<br>
                </div>
               
               
               
                </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <button type="submit" id="buttonForm" class="btn btn-info">Create</button>
          </div>  
            </form>
          </div>
      </div>
    </div>

    <script>
		function add() {
            document.getElementById("action").value = 1;
            document.getElementById("buttonForm").innerHTML = "Create";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");    
            document.getElementById("panelTitle").innerHTML = "Create Operator";
            document.getElementById("views").style.display = "";
			
            document.getElementById("id_operator").value = null;
            document.getElementById("name").value = "";
            document.getElementById("Sacks").checked = false;
            document.getElementById("Multilayer").checked = false;
            document.getElementById("Printing").checked = false;
            document.getElementById("Slitting").checked = false;
            document.getElementById("Injection").checked = false;
            document.getElementById("Macchi").checked = false;
            document.getElementById("Packing").checked = false;
			
            document.getElementById("name").readOnly = false;
        }
		
        function edit(id,name,sacks, multilayer, printing, slitting, injection, macchi, packing) {
            document.getElementById("action").value = 2;
            document.getElementById("buttonForm").innerHTML = "Update";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");  
            document.getElementById("panelTitle").innerHTML = "Edit Operator";
            document.getElementById("views").style.display = "";
			
            document.getElementById("id_operator").value = id;
            document.getElementById("name").value = name;
			
            document.getElementById("Sacks").checked = sacks;
            document.getElementById("Multilayer").checked = multilayer;
            document.getElementById("Printing").checked = printing;
            document.getElementById("Slitting").checked = slitting;
            document.getElementById("Injection").checked = injection;
            document.getElementById("Macchi").checked = macchi;
            document.getElementById("Packing").checked = packing;
			$(modal1).modal();
        }

        function deleteOperator(id,name) {
            document.getElementById("action").value = 3;
            document.getElementById("buttonForm").innerHTML = "Delete";
            document.getElementById("buttonForm").setAttribute("class","btn btn-danger");  
            document.getElementById("panelTitle").innerHTML = "Delete Operator";
            document.getElementById("views").style.display = "none";
			
			document.getElementById("id_operator").value = id;
            document.getElementById("name").value = name;
            document.getElementById("name").readOnly = true;
			
			$(modal1).modal();
        }
		
    </script>
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