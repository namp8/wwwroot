<?php
    $pageTitle = "Injection Formula";
    
    include_once "../base.php";
    include_once "../header.php";
    include_once "sidebar.php";
    include_once "../content.php";


    include_once "../inc/class.injection.inc.php";
    $injection = new Injection($db);

    include_once "../inc/class.materials.inc.php";
    $materials = new Materials($db);
?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="process.php">Injection</a>
        </li>
        <li class="breadcrumb-item active">Formula</li>
    </ol>
    <h2>Injection - Formula</h2>


    <div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
    if(!empty($_POST['action']) and $_POST['action'] ==1)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($injection->createFormula()){

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
        if($injection->deleteFormula()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
      
       
?>
    </div>


    <div class="pull-right" style="margin-top:5px;margin-right:30px;">
        <div class="dropdown" >
            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Edit Formula&nbsp&nbsp <i class="fa fa-caret-down" style="display: inline;"></i></button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a onclick="add()" data-toggle="modal" data-target="#modal1">Add material</a></li>
                <li><a onclick="deleteFormula()" data-toggle="modal" data-target="#modal1">Delete material</a></li>
            </ul>
         </div>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading">Injection - Formula
        </div>
        <div class="panel-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr class="active">
							<th>Product Type</th>
							<th>Material</th>
						</tr>
					</thead>
					<tbody>
<?php
    $injection->giveFormula();
?>
					</tbody>
				</table>
			</div>
        </div>
    </div>

    
<!-- Modal-->
    <div class="modal fade" id="modal1" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-m">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal">x</button>
            <h4 class="modal-title" id="panelTitle"></h4>
          </div>
        <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <div class="modal-body">
            <input type="hidden" class="form-control" id="action" name="action" value=0>
            <div class="form-group">
                <label >Product Type <span class="text-danger">*</span></label><br />
                <input type="hidden" class="form-control" id="type" name="type" required>
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_type">&nbsp&nbsp<span class="caret"></span></button>
                    <ul class="dropdown-menu" id="dropdown_type">
                        <li><input type="text" placeholder="Search product type.." class="searchDropdown" id="searchType" onkeyup="filterTypes()"></li>
                        <?php
    $injection->productTypeDropdown();
?>
                    </ul>
                </div>
            </div>
             <div class="form-group">
                <label for="material">Material <span class="text-danger">*</span></label><br />
                <input type="hidden" class="form-control" id="material" name="material" required>
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_material">&nbsp&nbsp<span class="caret"></span></button>
                    <ul class="dropdown-menu" id="dropdown_material">
                        <li><input type="text" placeholder="Search material.." class="searchDropdown" id="searchMaterial" onkeyup="filterMaterials()"></li>
                        <?php
    $injection->materialsDropdown();
?>
                    </ul>
                </div>
            </div>
			 <div class="form-group">
                    <label >Remarks <span class="text-danger">*</span></label>
                    <textarea type="text" class="form-control" rows="3" id="remarks" name="remarks" required></textarea>
                </div>
               </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <button type="submit" id="buttonForm" class="btn btn-info">Add</button>
          </div>
        </form>
        </div>
      </div>
    </div>

    <script>
        function add() {
            document.getElementById("action").value = 1;
            document.getElementById("buttonForm").innerHTML = "Add";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");    
            document.getElementById("panelTitle").innerHTML = "Add material";
            document.getElementById("kgForm").style.display = "";
            document.getElementById("bagsForm").style.display = "";
        }


        function deleteFormula() {
            document.getElementById("action").value = 3;
            document.getElementById("buttonForm").innerHTML = "Delete";
            document.getElementById("buttonForm").setAttribute("class","btn btn-danger");  
            document.getElementById("panelTitle").innerHTML = "Delete material";
            document.getElementById("kgForm").style.display = "none";
            document.getElementById("bagsForm").style.display = "none";
        }


        function selectMaterial(id, name, grade) {
            document.getElementById("btn_material").innerHTML = name + " - " + grade + " &nbsp&nbsp<span class='caret'></span> ";
            document.getElementById("material").value = id;
        }

        function filterMaterials() {
            var input, filter, ul, li, a, i;
            input = document.getElementById("searchMaterial");
            filter = input.value.toUpperCase();
            div = document.getElementById("dropdown_material");
            a = div.getElementsByTagName("a");
            for (i = 0; i < a.length; i++) {
                if (a[i].id.toUpperCase().startsWith(filter)) {
                    a[i].style.display = "";
                } else {
                    a[i].style.display = "none";
                }
            }
        }
		
		function selectType(name) {
            document.getElementById("btn_type").innerHTML = name +  " &nbsp&nbsp<span class='caret'></span> ";
            document.getElementById("type").value = name;
        }

        function filterTypes() {
            var input, filter, ul, li, a, i;
            input = document.getElementById("searchType");
            filter = input.value.toUpperCase();
            div = document.getElementById("dropdown_type");
            a = div.getElementsByTagName("a");
            for (i = 0; i < a.length; i++) {
                if (a[i].id.toUpperCase().startsWith(filter)) {
                    a[i].style.display = "";
                } else {
                    a[i].style.display = "none";
                }
            }
        }

		
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