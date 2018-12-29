<?php
    $pageTitle = "Settings - Customers";
    include_once "../base.php";
    include_once "../header.php";
    include_once "sidebar.php";
    include_once "../content.php";


    include_once "../inc/class.general.inc.php";
    $general = new General($db);

	if(!$users->admin())
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
        <li class="breadcrumb-item active">Customers</li>
    </ol>
    <h2>Settings - Customers</h2>

<div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
    if(!empty($_POST['action']) and $_POST['action'] ==1)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($general->createCustomer()){

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
        if($general->updateCustomer()){

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
        if($general->deleteCustomer()){

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
			<button class="btn btn-info btn-add" type="button" data-toggle="modal" data-target="#modal1" onclick="add()">Create Customer</button>
	</div>
        <div class="panel panel-info">
            <div class="panel-heading">Customers</div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                <th>Name</th>
                                <th>Sachet Rolls</th>
                                <th>Packing Bags</th>
                                <th>Shrink Film</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
    $general->giveCustomers();
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
            <h4 class="modal-title" id="panelTitle">Create Customer</h4>
          </div>
            <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                
          <div class="modal-body">
                <input type="hidden" class="form-control" id="action" name="action" required>
                <input type="hidden" class="form-control" id="id_customer" name="id_customer" required>
               
                <div class="form-group">
                    <label for="form">Name</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="form-group" id="products">
                    <label for="form">Products</label><br>
                    <input type="checkbox" id="sachet" name="product[]" value="sachet">Sachet Rolls<br>
                    <input type="checkbox"  id="bags" name="product[]" value="bags">Packing Bags<br>
                    <input type="checkbox" id="shrink" name="product[]" value="shrink">Shrink Film<br>
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
            document.getElementById("panelTitle").innerHTML = "Create Customer";
            document.getElementById("products").style.display = "";
			
            document.getElementById("id_customer").value = null;
            document.getElementById("name").value = "";
           	document.getElementById("sachet").checked = false;
            document.getElementById("bags").checked = false;
            document.getElementById("shrink").checked = false;
        }
		
        function edit(id,name,sachet,bags,shrink) {
            document.getElementById("action").value = 2;
            document.getElementById("buttonForm").innerHTML = "Update";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");  
            document.getElementById("panelTitle").innerHTML = "Edit Customer";
            document.getElementById("products").style.display = "";
			
            document.getElementById("id_customer").value = id;
            document.getElementById("name").value = name;
           	document.getElementById("sachet").checked = sachet;
            document.getElementById("bags").checked = bags;
            document.getElementById("shrink").checked = shrink;
			$(modal1).modal();
        }

        function deleteCustomer(id,name) {
            document.getElementById("action").value = 3;
            document.getElementById("buttonForm").innerHTML = "Delete";
            document.getElementById("buttonForm").setAttribute("class","btn btn-danger");  
            document.getElementById("panelTitle").innerHTML = "Delete customer";
            document.getElementById("products").style.display = "none";
			
			document.getElementById("id_customer").value = id;
            document.getElementById("name").value = name;
            document.getElementById("name").readonly = true;
			
			$(modal1).modal();
        }
		
    </script>
<script>
        $(document).ready(function() {
            $("#dataTable").DataTable();
        });
    </script>

    <?php
    include_once '../footer.php';
?>