<?php
    $pageTitle = "Settings - Consumables";
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
        <li class="breadcrumb-item active">Consumables</li>
    </ol>
    <h2>Settings - Consumables</h2>

<div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
    if(!empty($_POST['action']) and $_POST['action'] ==1)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($materials->createMaterial(4)){

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
			<button class="btn btn-info btn-add" type="button" data-toggle="modal" data-target="#modal1" onclick="add()">Create Consumable</button>
	</div>
    <div class="panel panel-info">
        <div class="panel-heading">List of Consumables</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover  table-xs" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                            <th>Consumable</th>
                            <th>Type</th>
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
    $materials->giveMaterials(4);
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
            <h4 class="modal-title" id="panelTitle">Create Consumable</h4>
          </div>
            <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                
          <div class="modal-body">
                <input type="hidden" class="form-control" id="action" name="action" required>
                <input type="hidden" class="form-control" id="id_material" name="id_material" required>
               
                <div class="form-group">
                    <label for="form">Material Name</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
			  	<div class="form-group">
                    <label for="form">Material Grade</label>
                    <input type="text" class="form-control" id="grade" name="grade">
                </div>
			  	<div id="panel_add">
			  	<div class="form-group">
                    <label for="form">Kgs per bag</label>
                    <input type="number" class="form-control" step="0.01" min="0" max="50" value="0" id="kgs" name="kgs">
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
		function add() {
            document.getElementById("action").value = 1;
            document.getElementById("buttonForm").innerHTML = "Create";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");    
            document.getElementById("panelTitle").innerHTML = "Create Consumable";
            document.getElementById("panel_add").style.display = "";
			
            document.getElementById("id_material").value = null;
            document.getElementById("name").value = "";
            document.getElementById("grade").value = "";
            document.getElementById("kgs").value = 0;
           	document.getElementById("sacks").checked = false;
           	document.getElementById("cutting").checked = false;
           	document.getElementById("multilayer").checked = false;
           	document.getElementById("printing").checked = false;
           	document.getElementById("injection").checked = false;
           	document.getElementById("macchi").checked = false;
           	document.getElementById("packing").checked = false;
        }
		
        function edit(id,name,grade,kgs,sacks,cutting,multilayer,printing,injection,macchi,packing) {
            document.getElementById("action").value = 2;
            document.getElementById("buttonForm").innerHTML = "Update";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");  
            document.getElementById("panelTitle").innerHTML = "Edit Consumable";
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
			$(modal1).modal();
        }

        function deleteMaterial(id,name,grade) {
            document.getElementById("action").value = 3;
            document.getElementById("buttonForm").innerHTML = "Delete";
            document.getElementById("buttonForm").setAttribute("class","btn btn-danger");  
            document.getElementById("panelTitle").innerHTML = "Delete Consumable";
            document.getElementById("panel_add").style.display = "none";
			
			document.getElementById("id_material").value = id;
            document.getElementById("name").value = name;
            document.getElementById("name").readonly = true;
            document.getElementById("grade").value = grade;
            document.getElementById("grade").readonly = true;
			
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