<?php
    $pageTitle = "Packing Bags Formula";
    
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar.php";
    include_once "../../content.php";


    include_once "../../inc/class.packing.inc.php";
    $packing = new Packing($db);

    include_once "../../inc/class.materials.inc.php";
    $materials = new Materials($db);
?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="process.php">Packing Bags</a>
        </li>
        <li class="breadcrumb-item active">Formula</li>
    </ol>
    <h2>Packing Bags - Formula</h2>


    <div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
	if(!empty($_POST['kgcolor']))
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($packing->updateColorFormula()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
    if(!empty($_POST['action']) and $_POST['action'] ==1)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($packing->createFormula()){

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
        if($packing->updateFormula()){

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
        if($packing->deleteFormula()){

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
                <li><a onclick="add()" data-toggle="modal" data-target="#modal1">Add Material</a></li>
                <li><a onclick="update()" data-toggle="modal" data-target="#modal1">Update Material weight</a></li>
                <li><a onclick="deleteFormula()" data-toggle="modal" data-target="#modal1">Delete Material</a></li>
            </ul>
         </div>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading">Packing Bags - Total Formula (100%)
        </div>
        <div class="panel-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr class="active">
							<th>Material</th>
							<th>Grade</th>
							<th>KG</th>
							<th>Bags</th>
							<th>Percentage</th>
						</tr>
					</thead>
					<tbody>
<?php
    $total = $packing->giveTotalFormula();
?>
					</tbody>
				</table>
			</div>
            <div id="chartTotal" style="height: 180px; width: 100%;">
            </div>
        </div>
    </div>

	<button class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" data-toggle="modal" data-target="#modal2">Update Color weight</button>


    <div class="panel panel-info">
        <div class="panel-heading">Packing Bags - White Bags (100%)
        </div>
        <div class="panel-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr class="active">
							<th>Material</th>
							<th>Grade</th>
							<th>KG</th>
							<th>Bags</th>
							<th>Percentage</th>
						</tr>
					</thead>
					<tbody>
<?php
    $packing->giveColorFormula(1);
?>
					</tbody>
				</table>
			</div>
        </div>
    </div>

    <!-- Modal-->
    <div class="modal fade" id="modal2" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-m">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal">x</button>
            <h4 class="modal-title">Update Color weight</h4>
          </div>
        <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <div class="modal-body">
            <input type="hidden" class="form-control" id="action" name="action" value=0>
            <div class="form-group">
                <label for="material">Material <span class="text-danger">*</span></label><br />
                <input type="text" class="form-control" value="Master Batch - White"  disabled>
            </div>
            <div class="form-group" id="kgForm">
                <label for="kg">KG <span class="text-danger">*</span></label>
                <input type="number" class="form-control" step="0.1" min="0.1" id="kgcolor" name="kgcolor" >
            </div>
			 <div class="form-group">
                    <label >Remarks <span class="text-danger">*</span></label>
                    <textarea type="text" class="form-control" rows="3" id="remarkscolor" name="remarkscolor" required></textarea>
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
                <label for="material">Material <span class="text-danger">*</span></label><br />
                <input type="hidden" class="form-control" id="material" name="material" required>
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_material">&nbsp&nbsp<span class="caret"></span></button>
                    <ul class="dropdown-menu" id="dropdown_material">
                        <li><input type="text" placeholder="Search material.." class="searchDropdown" id="searchMaterial" onkeyup="filterMaterials()"></li>
                        <?php
    $packing->materialsDropdown();
?>
                    </ul>
                </div>
            </div>
            <div class="form-group" id="kgForm">
                <label for="kg">KG <span class="text-danger">*</span></label>
                <input type="number" class="form-control" step="0.1" min="0.1" id="kg" name="kg" onkeyup="calculateBags()">
            </div>
            <div class="form-group" id="bagsForm">
                <label for="bags">Bags <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="bags" name="bags"  disabled>
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
        window.onload = function() {

            var chart3 = new CanvasJS.Chart("chartTotal", {
                theme: "light2",
                animationEnabled: true,
                data: [{
                    type: "pie",
                    indexLabelFontSize: 12,
                    radius: 50,
                    indexLabel: "{label} - {y}",
                    yValueFormatString: "###0.00\"%\"",
                    dataPoints: [
                        <?php
        foreach ($total as $value) {
            echo "{y: ". $value[2].", label:'". $value[0]." (". $value[1].")'},";
        }
    ?>
                    ]
                }]
            });
            chart3.render();
        }
    </script>

    <script>
        function add() {
            document.getElementById("action").value = 1;
            document.getElementById("buttonForm").innerHTML = "Add";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");    
            document.getElementById("panelTitle").innerHTML = "Add material";
            document.getElementById("kgForm").style.display = "";
            document.getElementById("bagsForm").style.display = "";
        }

        function update() {

            document.getElementById("action").value = 2;
            document.getElementById("buttonForm").innerHTML = "Update";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");  
            document.getElementById("panelTitle").innerHTML = "Update Material weight";
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

        function calculateBags() {
            var input;
            input = document.getElementById("kg").value;
            var bags = Math.floor(input / 25);
            var remainder = input % 25;
            var answer = "";
            if (bags > 0) {
                answer = bags + " bags";
            }
            if (bags > 0 && remainder > 0) {
                answer += " + ";
            }
            if (remainder > 0) {
                answer += remainder + " kg";
            }
            document.getElementById("bags").value = answer;
        }
    </script>

    <?php
    include_once '../../footer.php';
?>