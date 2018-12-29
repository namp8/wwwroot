<?php
    $pageTitle = "Macchi Formula Shrink Film";
    
    include_once "../base.php";
    include_once "../header.php";
    include_once "sidebar.php";
    include_once "../content.php";


    include_once "../inc/class.macchi.inc.php";
    $macchi = new Macchi($db);

?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="process.php">Macchi</a>
        </li>
        <li class="breadcrumb-item active">Formula</li>
    </ol>
    <h2>Macchi - Formula  - Shrink Film</h2>


    <div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
    if(!empty($_POST['action']) and $_POST['action'] ==1)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($macchi->createFormula(2)){

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
        if($macchi->updateFormula(2)){

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
        if($macchi->deleteFormula(2)){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
      
       
?>
    </div>

    <div class="row text-right" style="padding-bottom:15px;">
        <div class="dropdown" >
            <button class="btn btn-info" type="button" onclick="add()" data-toggle="modal" data-target="#modal1">Add Material </button>
           
         </div>
    </div>

    <div class="row row-eq-height" >
        <div class="col-md-4">
            <div class="panel panel-info" style="width: 100%; height: 100%;">
                <div class="panel-heading">
                    <h4>A (10%)</h4>
                </div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                    <th>Material</th>
                                    <th>Grade</th>
                                    <th>%</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
    $a = $macchi->giveFormulas(2,1);
?>
                            </tbody>
                        </table>
                    </div>
                    <div id="chartContainer" style="height: 90px; width: 100%;">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-info" style="width: 100%; height: 100%;">
                <div class="panel-heading">
                    <h4>B (20%)</h4>
                </div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                    <th>Material</th>
                                    <th>Grade</th>
                                    <th>%</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
    $b = $macchi->giveFormulas(2,2);
?>
                            </tbody>
                        </table>
                    </div>
                    <div id="chartContainer2" style="height: 90px; width: 100%;">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-info" style="width: 100%; height: 100%;">
                <div class="panel-heading" >
                    <h4>C (40%)</h4>
                </div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                    <th>Material</th>
                                    <th>Grade</th>
                                    <th>%</th>
                                    <th></th>
                            </thead>
                            <tbody>
                                <?php
    $c = $macchi->giveFormulas(2,3);
?>
                            </tbody>
                        </table>
                    </div>
                    <div id="chartContainer3" style="height: 120px; width: 100%;">

                    </div>
                </div>
            </div>
        </div>
	</div>
<br />
	<div class="row  row-eq-height" >	
		<div class="col-md-4">
            <div class="panel panel-info" style="width: 100%; height: 100%;">
                <div class="panel-heading" >
                    <h4>D (20%)</h4>
                </div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                    <th>Material</th>
                                    <th>Grade</th>
                                    <th>%</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
    $d = $macchi->giveFormulas(2,4);
?>
                            </tbody>
                        </table>
                    </div>
                    <div id="chartContainer4" style="height: 90px; width: 100%;">

                    </div>
                </div>
            </div>
        </div>
		<div class="col-md-4">
            <div class="panel panel-info" style="width: 100%; height: 100%;">
                <div class="panel-heading" >
                    <h4>E (10%)</h4>
                </div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                    <th>Material</th>
                                    <th>Grade</th>
                                    <th>%</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
    $e = $macchi->giveFormulas(2,5);
?>
                            </tbody>
                        </table>
                    </div>
                    <div id="chartContainer5" style="height: 90px; width: 100%;">

                    </div>
                </div>
            </div>
        </div>
 	<div class="col-md-4">
    <div class="panel panel-info" style="width: 100%; height: 100%;">
        <div class="panel-heading">
            <h4>TOTAL (100%)</h4>
        </div>
        <div class="panel-body">

            <div id="chartTotal" style="height: 250px; width: 100%;">
                <?php
    $total = $macchi->giveTotalFormula(2);
?>
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
									<label for="date">Date <span class="text-danger">*</span></label>
									<div class='input-group date' id='datetimepicker'>
										<input type='text' class="form-control" id="date" name="date" required/>
										<span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
										</span>
									</div>
								</div>
            <div class="form-group">
                <label for="layer">Layer <span class="text-danger">*</span></label><br />
                <input type="hidden" class="form-control" id="layer" name="layer" value=1 required>
                <div class="dropdown" >
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_layer">A&nbsp&nbsp<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a onclick="selectLayer(1)">A</a></li>
                        <li><a onclick="selectLayer(2)">B</a></li>
                        <li><a onclick="selectLayer(3)">C</a></li>
                        <li><a onclick="selectLayer(4)">D</a></li>
                        <li><a onclick="selectLayer(5)">E</a></li>
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
    $macchi->materialsDropdown();
?>
                    </ul>
                </div>
            </div>
            <div class="form-group" id="kgForm">
                <label for="kg">Percentage <span class="text-danger">*</span></label>
                <input type="number" class="form-control" step="0.1" min="0.1" id="percentage" name="percentage">
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
			
			$('#datetimepicker').datetimepicker({
						format: 'DD/MM/YYYY'
					});
				var d = new Date();
					var month = d.getMonth() + 1;
					document.getElementById("date").value = d.getDate() + "/" + month + "/" + d.getFullYear();

            var chart = new CanvasJS.Chart("chartContainer", {
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
        foreach ($a as $value) {
            echo "{y: ". $value[2].", label:'". $value[0]." (". $value[1].")'},";
        }
    ?>
                    ]
                }]
            });
            chart.render();

            var chart1 = new CanvasJS.Chart("chartContainer2", {
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
        foreach ($b as $value) {
            echo "{y: ". $value[2].", label:'". $value[0]." (". $value[1].")'},";
        }
    ?>
                    ]
                }]
            });
            chart1.render();

            var chart2 = new CanvasJS.Chart("chartContainer3", {
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
        foreach ($c as $value) {
            echo "{y: ". $value[2].", label:'". $value[0]." (". $value[1].")'},";
        }
    ?>
                    ]
                }]
            });
            chart2.render();

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
			
			var chart4 = new CanvasJS.Chart("chartContainer4", {
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
        foreach ($d as $value) {
            echo "{y: ". $value[2].", label:'". $value[0]." (". $value[1].")'},";
        }
    ?>
                    ]
                }]
            });
            chart4.render();
			
			var chart5 = new CanvasJS.Chart("chartContainer5", {
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
        foreach ($e as $value) {
            echo "{y: ". $value[2].", label:'". $value[0]." (". $value[1].")'},";
        }
    ?>
                    ]
                }]
            });
            chart5.render();
        }
    </script>

    <script>
        function add() {
            document.getElementById("action").value = 1;
            document.getElementById("buttonForm").innerHTML = "Add";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");    
            document.getElementById("panelTitle").innerHTML = "Add material";
            document.getElementById("kgForm").style.display = "";
        }
		
        function update(layer,materialid, materialname, materialgrade) {
			selectLayer(layer);
			selectMaterial(materialid, materialname, materialgrade);
            document.getElementById("action").value = 2;
            document.getElementById("buttonForm").innerHTML = "Update";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");  
            document.getElementById("panelTitle").innerHTML = "Update Material weight";
            document.getElementById("kgForm").style.display = "";
			$(modal1).modal();
        }

        function deleteFormula(layer,materialid, materialname, materialgrade) {
			selectLayer(layer);
			selectMaterial(materialid, materialname, materialgrade);
            document.getElementById("action").value = 3;
            document.getElementById("buttonForm").innerHTML = "Delete";
            document.getElementById("buttonForm").setAttribute("class","btn btn-danger");  
            document.getElementById("panelTitle").innerHTML = "Delete material";
            document.getElementById("kgForm").style.display = "none";
			$(modal1).modal();
        }


        function selectLayer(id) {
			if(id == 1)
			{
				name = 'A';
			}
			else if(id == 2)
			{
				name = 'B';
			}
			else if(id == 3)
			{
				name = 'C';
			}
			else if(id == 4)
			{
				name = 'D';
			}
			else if(id == 5)
			{
				name = 'E';
			}
            document.getElementById("btn_layer").innerHTML = name+" &nbsp&nbsp<span class='caret'></span> ";
            document.getElementById("layer").value = id;
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

    </script>

    <?php
    include_once '../footer.php';
?>