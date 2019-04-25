<?php
    $pageTitle = "Settings - Ink and Solvents";
    include_once "../base.php";
    include_once "../header.php";
    include_once "sidebar.php";
    include_once "../content.php";


    include_once "../inc/class.materials.inc.php";
    $materials = new Materials($db);
	
	include_once "../inc/class.users.inc.php";
    $users = new Users($db);

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
        <li class="breadcrumb-item active">Ink and Solvents</li>
    </ol>
    <h2>Settings - Ink and Solvents</h2>

<div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
    if(!empty($_POST['action']) and $_POST['action'] ==1)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($materials->createMaterial(2)){

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
        if($materials->updateMaterial()){

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
        if($materials->deleteMaterial()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
      
       
?>
    </div>


	<div class="pull-right text-right">
			<button class="btn btn-info btn-add" type="button" data-toggle="modal" data-target="#modal1" onclick="addInk()">Create Ink</button>
			<button class="btn btn-info btn-add" type="button" data-toggle="modal" data-target="#modal1" onclick="addSolvent()">Create Solvent</button>
	</div>
    <div class="panel panel-info">
        <div class="panel-heading">List of Ink and Solvents</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-xs" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                            <th>Item</th>
                            <th>Color / Solvent name</th>
                            <th>Kgs per bag</th>
                            <th>Sacks</th>
                            <th>Cutting</th>
                            <th>Multilayer</th>
                            <th>Printing</th>
                            <th>Injection</th>
                            <th>Macchi</th>
                            <th>Packing Bags</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
    $materials->giveMaterials(2);
?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<div class="modal fade" id="modal1" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-m">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal">x</button>
            <h4 class="modal-title" id="panelTitle">Create Ink and Solvents</h4>
          </div>
            <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                
          <div class="modal-body">
                <input type="hidden" class="form-control" id="action" name="action" required>
                <input type="hidden" class="form-control" id="id_material" name="id_material" required>
               
                <div class="form-group">
                    <label for="form">Item</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
			  	<div class="form-group">
                    <label for="form" id="label_text">Color / Solvent name</label>
                    <input type="text" class="form-control" id="grade" name="grade">
                </div>
			  	<div id="panel_add">
			  	<div class="form-group">
                    <label for="form">Kgs per drump</label>
                    <input type="number" class="form-control" step="0.01" min="0" value="25" id="kgs" name="kgs">
                </div>
                <div class="form-group">
                    <label for="form">Section</label><br>
                    <input type="checkbox" id="sacks" name="material[]" value="sacks">Sacks<br>
                    <input type="checkbox"  id="cutting" name="material[]" value="cutting">Cutting<br>
                    <input type="checkbox" id="multilayer" name="material[]" value="multilayer">Multilayer<br>
                    <input type="checkbox" id="printing" name="material[]" value="printing">Printing<br>
                    <input type="checkbox" id="injection" name="material[]" value="injection">Injection<br>
                    <input type="checkbox" id="macchi" name="material[]" value="macchi">Macchi<br>
                    <input type="checkbox" id="packing" name="material[]" value="packing">Packing Bags<br>
                </div>
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
		function addInk() {
            document.getElementById("action").value = 1;
            document.getElementById("buttonForm").innerHTML = "Create";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");    
            document.getElementById("panelTitle").innerHTML = "Create ink";
            document.getElementById("label_text").innerHTML = "Color";
            document.getElementById("panel_add").style.display = "";
			
            document.getElementById("id_material").value = null;;
            document.getElementById("name").value = "Genius Ink";
            document.getElementById("grade").value = "";
            document.getElementById("kgs").value = 20;
           	document.getElementById("sacks").checked = false;
           	document.getElementById("cutting").checked = false;
           	document.getElementById("multilayer").checked = false;
           	document.getElementById("printing").checked = true;
           	document.getElementById("injection").checked = false;
           	document.getElementById("macchi").checked = false;
           	document.getElementById("packing").checked = false;
			
            document.getElementById("name").readOnly = false;
            document.getElementById("grade").readOnly = false;
        }
		function addSolvent() {
            document.getElementById("action").value = 1;
            document.getElementById("buttonForm").innerHTML = "Create";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");    
            document.getElementById("panelTitle").innerHTML = "Create solvent";
            document.getElementById("label_text").innerHTML = "Solvent name x kgs";
            document.getElementById("panel_add").style.display = "";
			
            document.getElementById("id_material").value = null;
            document.getElementById("name").value = "Solvent";
            document.getElementById("grade").value = "";
            document.getElementById("kgs").value = 160;
           	document.getElementById("sacks").checked = false;
           	document.getElementById("cutting").checked = false;
           	document.getElementById("multilayer").checked = false;
           	document.getElementById("printing").checked = true;
           	document.getElementById("injection").checked = false;
           	document.getElementById("macchi").checked = false;
           	document.getElementById("packing").checked = false;
			
            document.getElementById("name").readOnly = true;
            document.getElementById("grade").readOnly = false;
        }
		
        function edit(id,name,grade,kgs,sacks,cutting,multilayer,printing,injection,macchi,packing) {
            document.getElementById("action").value = 2;
            document.getElementById("buttonForm").innerHTML = "Update";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");  
            document.getElementById("panelTitle").innerHTML = "Edit item";
            document.getElementById("panel_add").style.display = "";
			
            document.getElementById("id_material").value = id;
            document.getElementById("name").value = name;
            document.getElementById("grade").value = grade;
            document.getElementById("kgs").value = kgs;
           	document.getElementById("sacks").checked = sacks;
           	document.getElementById("cutting").checked = cutting;
           	document.getElementById("multilayer").checked = multilayer;
           	document.getElementById("printing").checked = printing;
           	document.getElementById("injection").checked = injection;
           	document.getElementById("macchi").checked = macchi;
           	document.getElementById("packing").checked = packing;
			
            document.getElementById("name").readOnly = false;
            document.getElementById("grade").readOnly = false;
			
			$(modal1).modal();
        }

        function deleteMaterial(id,name,grade) {
            document.getElementById("action").value = 3;
            document.getElementById("buttonForm").innerHTML = "Delete";
            document.getElementById("buttonForm").setAttribute("class","btn btn-danger");  
            document.getElementById("panelTitle").innerHTML = "Delete item";
            document.getElementById("panel_add").style.display = "none";
			
			document.getElementById("id_material").value = id;
            document.getElementById("name").value = name;
            document.getElementById("name").readOnly = true;
            document.getElementById("grade").value = grade;
            document.getElementById("grade").readOnly = true;
			
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