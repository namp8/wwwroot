<?php
    $pageTitle = "Injection - Settings";
    include_once "../base.php";
    include_once "../header.php";
    include_once "sidebar.php";
    include_once "../content.php";


    include_once "../inc/class.injection.inc.php";
    $injection = new Injection($db);

?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="../process.php">Injection</a>
        </li>
        <li class="breadcrumb-item active">Settings</li>
    </ol>

    <h2>Injection  - Settings</h2>

 
    <div id="alertMessage" class="alert hide" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            <?php
   if(empty($_POST['finished']) and !empty($_POST['action']) and $_POST['action'] ==1)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($injection->createSetting()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
    if(empty($_POST['finished']) and !empty($_POST['action']) and $_POST['action'] ==2)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($injection->updateSetting()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
    if(empty($_POST['finished']) and!empty($_POST['action']) and $_POST['action'] ==3)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($injection->deleteSetting()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
		if(!empty($_POST['finished']) and !empty($_POST['action']) and $_POST['action'] ==1)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($injection->createSackSetting()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
    if(!empty($_POST['finished']) and !empty($_POST['action']) and $_POST['action'] ==2)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($injection->updateSackSetting()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
    if(!empty($_POST['finished']) and!empty($_POST['action']) and $_POST['action'] ==3)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($injection->deleteSackSetting()){

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
            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Edit Sacks Settings&nbsp&nbsp <i class="fa fa-caret-down" style="display: inline;"></i></button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a onclick="addSacks()" data-toggle="modal" data-target="#modal2">Add Sacks settings</a></li>
                <li><a onclick="updateSacks()" data-toggle="modal" data-target="#modal2">Edit Sacks settings</a></li>
                <li><a onclick="deleteSacks()" data-toggle="modal" data-target="#modal2">Delete Sacks setting</a></li>
            </ul>
         </div>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading"> Sacks settings </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered  table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="active">
                            <th>Finished Product</th>
                            <th>Semifinished product 1</th>
                            <th>Semifinished product 2</th>
                            <th>Semifinished product 3</th>
                            <th>Pieces in sack</th>
                            <th>Sacks weight (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
    $injection->giveSacksSettings();
?>
                    </tbody>
                </table>

            </div>
            
                    
                   
        </div>
    </div>


    <div class="pull-right" style="margin-top:5px;margin-right:30px;">
        <div class="dropdown" >
            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Edit Settings&nbsp&nbsp <i class="fa fa-caret-down" style="display: inline;"></i></button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a onclick="add()" data-toggle="modal" data-target="#modal1">Add settings</a></li>
                <li><a onclick="update()" data-toggle="modal" data-target="#modal1">Edit settings</a></li>
                <li><a onclick="deleteFormula()" data-toggle="modal" data-target="#modal1">Delete setting</a></li>
            </ul>
         </div>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading"> Targets and Weight </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered  table-hover" id="dataTable2" width="100%" cellspacing="0">
                    <thead>
                        <tr class="active">
                            <th>Product Name</th>
                            <th>Cycle Time (secs)</th>
                            <th>No. of cavities</th>
                            <th>Production Target</th>
                            <th>Product weight (g)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
    $injection->giveSettings();
?>
                    </tbody>
                </table>

            </div>
            
                    
                   
        </div>
    </div>



		<div class="modal fade" id="modal1" role="dialog" tabindex="-1">
      <div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" type="button" data-dismiss="modal">x</button>
            			<h4 class="modal-title" id="panelTitle"></h4>
					</div>
					<form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
						<div class="modal-body">
            <input type="hidden" class="form-control" id="action" name="action" value=0>
							<div class="row">
								<div class="col-md-12 form-group">
									<label for="material">Product Name <span class="text-danger">*</span></label><br>
									<input type="hidden" class="form-control" id="product" name="product" required>
									<div class="btn-group">
										<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_material">&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu" role="menu" id="dropdown_material">
											<li><input type="text" placeholder="Search material.." class="searchDropdown" id="searchMaterial" onkeyup="filterMaterials()" width="100%"></li>
											<?php
						$injection->semifinishedDropdown(null);
					?>
										</ul>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label for="size">Cycle Time in sec <span class="text-danger">*</span></label>
									<input type="number" class="form-control input-sm" step="0.1" min="0.1" name="cycle" id="cycle" onkeyup="calculateTarget()" required>
								</div>
								<div class="col-md-6 form-group">
									<label for="size">No. of running cavities <span class="text-danger">*</span></label>
									<input type="number" class="form-control input-sm" step="1" min="0" name="cavities" id="cavities" value="2" onkeyup="calculateTarget()" required>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label for="size">Production Target in pcs per day <span class="text-danger">*</span></label>
									<input type="number" class="form-control input-sm" step="0.1" min="0.1" name="target" id="target"  readonly onkeyup="calculateTarget()" required>
								</div>
								<div class="col-md-6 form-group">
									<label for="size">Part weight in grm <span class="text-danger">*</span></label>
									<input type="number" class="form-control input-sm" step="0.01"  name="part" id="part" required >
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

<div class="modal fade" id="modal2" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-m" >
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" type="button" data-dismiss="modal">x</button>
            			<h4 class="modal-title" id="panelTitle2"></h4>
					</div>
					<form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
						<div class="modal-body">
            <input type="hidden" class="form-control" id="action2" name="action" value=0>
							<div class="row">
								<div class="col-md-12 form-group">
									<label for="material">Finished Product <span class="text-danger">*</span></label><br>
									<input type="hidden" class="form-control" id="finished" name="finished" required>
									<div class="btn-group">
										<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_finished">&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu" role="menu" id="dropdown_finished">
											<li><input type="text" placeholder="Search finished product.." class="searchDropdown" id="searchFinished" onkeyup="filterFinished()" width="100%"></li>
											<?php
						$injection->finishedDropdown();
					?>
										</ul>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 form-group">
									<label for="material">Semi-Finished Product 1 <span class="text-danger">*</span></label><br>
									<input type="hidden" class="form-control" id="semifinished1" name="semifinished1" required>
									<div class="btn-group">
										<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_material1">&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu" role="menu" id="dropdown_material1">
											<li><input type="text" placeholder="Search material.." class="searchDropdown" id="searchMaterial1" onkeyup="filterMaterials1()" width="100%"></li>
											<?php
						$injection->semifinishedDropdown(1);
					?>
										</ul>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 form-group">
									<label for="material">Semi-Finished Product 2 <span class="text-danger">*</span></label><br>
									<input type="hidden" class="form-control" id="semifinished2" name="semifinished2" required>
									<div class="btn-group">
										<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_material2">&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu" role="menu" id="dropdown_material2">
											<li><input type="text" placeholder="Search material.." class="searchDropdown" id="searchMaterial2" onkeyup="filterMaterials2()" width="100%"></li>
											<?php
						$injection->semifinishedDropdown(2);
					?>
										</ul>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 form-group">
									<label for="material">Semi-Finished Product 3 <span class="text-danger">*</span></label><br>
									<input type="hidden" class="form-control" id="semifinished3" name="semifinished3" required>
									<div class="btn-group">
										<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_material3">&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu" role="menu" id="dropdown_material3">
											<li><input type="text" placeholder="Search material.." class="searchDropdown" id="searchMaterial3" onkeyup="filterMaterials3()" width="100%"></li>
											<?php
						$injection->semifinishedDropdown(3);
					?>
										</ul>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 form-group">
									<label for="size" >Pieces in sack<span class="text-danger">*</span></label>
									<input type="number" class="form-control input-sm" name="pieces" id="pieces" value="0"  step="1" min="1" required >
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-12 form-group">
									<label for="size">Sack weight in kgs <span class="text-danger">*</span></label>
									<input type="number" class="form-control input-sm" step="0.01"  name="sack" id="sack"  required>
								</div>
							</div>
							
							<div class="form-group">
                    <label >Remarks <span class="text-danger">*</span></label>
                    <textarea type="text" class="form-control" rows="3" name="remarks" required></textarea>
                </div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
							<button type="submit" id="buttonForm2" class="btn btn-info">Submit</button>
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
            document.getElementById("panelTitle").innerHTML = "Add setting";
        }


        function update() {
            document.getElementById("action").value = 2;
            document.getElementById("buttonForm").innerHTML = "Update";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");    
            document.getElementById("panelTitle").innerHTML = "Update setting";
        }

        function deleteFormula() {
            document.getElementById("action").value = 3;
            document.getElementById("buttonForm").innerHTML = "Delete";
            document.getElementById("buttonForm").setAttribute("class","btn btn-danger");  
            document.getElementById("panelTitle").innerHTML = "Delete setting";
        }
	
	 function addSacks() {
            document.getElementById("action2").value = 1;
            document.getElementById("buttonForm2").innerHTML = "Add";
            document.getElementById("buttonForm2").setAttribute("class","btn btn-info");    
            document.getElementById("panelTitle2").innerHTML = "Add Sacks setting";
        }


        function updateSacks() {
            document.getElementById("action2").value = 2;
            document.getElementById("buttonForm").innerHTML = "Update";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");    
            document.getElementById("panelTitle").innerHTML = "Update Sacks  setting";
        }

        function deleteSacks() {
            document.getElementById("action2").value = 3;
            document.getElementById("buttonForm").innerHTML = "Delete";
            document.getElementById("buttonForm").setAttribute("class","btn btn-danger");  
            document.getElementById("panelTitle").innerHTML = "Delete Sacks setting";
        }
	
	function calculateTarget() {
		var cycle= document.getElementById('cycle').value;
		var cavities= document.getElementById('cavities').value;
		if(cycle != null && cavities!= null)
		{
			document.getElementById('target').value = (3600 /cycle)*cavities*24;
		}
		else
		{
			document.getElementById('target').value = 0;
		}
	}
	
	function selectMaterial(id, name, grade) {
					document.getElementById("btn_material").innerHTML = name + " - " + grade+ " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("product").value = id;
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
	
	function selectMaterial1(id, name, grade) {
					document.getElementById("btn_material1").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("semifinished1").value = id;
				}
				function filterMaterials1() {
					var input, filter, ul, li, a, i;
					input = document.getElementById("searchMaterial1");
					filter = input.value.toUpperCase();
					div = document.getElementById("dropdown_material1");
					a = div.getElementsByTagName("a");
					for (i = 0; i < a.length; i++) {
						if (a[i].id.toUpperCase().startsWith(filter)) {
							a[i].style.display = "";
						} else {
							a[i].style.display = "none";
						}
					}
				}
				function selectMaterial2(id, name, grade) {
					document.getElementById("btn_material2").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("semifinished2").value = id;
				}
				function filterMaterials2() {
					var input, filter, ul, li, a, i;
					input = document.getElementById("searchMaterial2");
					filter = input.value.toUpperCase();
					div = document.getElementById("dropdown_material2");
					a = div.getElementsByTagName("a");
					for (i = 0; i < a.length; i++) {
						if (a[i].id.toUpperCase().startsWith(filter)) {
							a[i].style.display = "";
						} else {
							a[i].style.display = "none";
						}
					}
				}
				function selectMaterial3(id, name, grade) {
					document.getElementById("btn_material3").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("semifinished3").value = id;
				}
				function filterMaterials3() {
					var input, filter, ul, li, a, i;
					input = document.getElementById("searchMaterial3");
					filter = input.value.toUpperCase();
					div = document.getElementById("dropdown_material3");
					a = div.getElementsByTagName("a");
					for (i = 0; i < a.length; i++) {
						if (a[i].id.toUpperCase().startsWith(filter)) {
							a[i].style.display = "";
						} else {
							a[i].style.display = "none";
						}
					}
				}
	function selectFinished(id, name, grade) {
					document.getElementById("btn_finished").innerHTML = name + " - " + grade+ " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("finished").value = id;
				}
				function filterFinished() {
					var input, filter, ul, li, a, i;
					input = document.getElementById("searchFinished");
					filter = input.value.toUpperCase();
					div = document.getElementById("dropdown_finished");
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
			$("#dataTable2").DataTable({
				"order": [],
				"lengthMenu": [[-1, 10, 25, 50, 100], ["All", 10, 25, 50, 100]]
			});
		});</script>


    <?php
    include_once '../footer.php';
?>