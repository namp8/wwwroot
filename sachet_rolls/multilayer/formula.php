<?php
    $pageTitle = "Multilayer Formula";
    
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar1.php";
    include_once "../../content.php";


    include_once "../../inc/class.multilayer.inc.php";
    $multilayer = new Multilayer($db);

?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="../process.php">Sachet Rolls</a>
        </li>
        <li class="breadcrumb-item">
            <a href="process.php">Multilayer</a>
        </li>
        <li class="breadcrumb-item active">Formula</li>
    </ol>
    <h2>Multilayer - Formula</h2>


    <div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
    if(!empty($_POST['action']) and $_POST['action'] ==1)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($multilayer->createFormula()){

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
        if($multilayer->updateFormula()){

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
        if($multilayer->deleteFormula()){

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
            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Edit Formula&nbsp&nbsp <i class="fa fa-caret-down" style="display: inline;"></i></button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a onclick="add()" data-toggle="modal" data-target="#modal1">Add Material</a></li>
                <li><a onclick="update()" data-toggle="modal" data-target="#modal1">Update Material weight</a></li>
                <li><a onclick="deleteFormula()" data-toggle="modal" data-target="#modal1">Delete Material</a></li>
            </ul>
         </div>
    </div>

    <div class="row" >
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4>Outer (31.25%)</h4>
                </div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                    <th>Material</th>
                                    <th>Grade</th>
                                    <th>KG</th>
                                    <th>Bags</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
    $outer = $multilayer->giveFormulas(1);
?>
                            </tbody>
                        </table>
                    </div>
                    <div id="chartContainer" style="height: 180px; width: 100%;">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4>Middle (37.5%)</h4>
                </div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                    <th>Material</th>
                                    <th>Grade</th>
                                    <th>KG</th>
                                    <th>Bags</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
    $middle = $multilayer->giveFormulas(2);
?>
                            </tbody>
                        </table>
                    </div>
                    <div id="chartContainer2" style="height: 180px; width: 100%;">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4>Inner (31.25%)</h4>
                </div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                    <th>Material</th>
                                    <th>Grade</th>
                                    <th>KG</th>
                                    <th>Bags</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
    $inner = $multilayer->giveFormulas(3);
?>
                            </tbody>
                        </table>
                    </div>
                    <div id="chartContainer3" style="height: 180px; width: 100%;">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h4>TOTAL (100%)</h4>
        </div>
        <div class="panel-body">

            <div id="chartTotal" style="height: 180px; width: 100%;">
                <?php
    $total = $multilayer->giveTotalFormula();
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
                <label for="layer">Layer <span class="text-danger">*</span></label><br />
                <input type="hidden" class="form-control" id="layer" name="layer" value=1 required>
                <div class="dropdown" >
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_layer">Outer&nbsp&nbsp<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a onclick="selectLayer(1,'Outer')">Outer</a></li>
                        <li><a onclick="selectLayer(2,'Middle')">Middle</a></li>
                        <li><a onclick="selectLayer(3,'Inner')">Inner</a></li>
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
    $multilayer->materialsFormulaDropdown();
?>
                    </ul>
                </div>
            </div>
            <div class="form-group" id="kgForm">
                <label for="kg">KG <span class="text-danger">*</span></label>
                <input type="number" class="form-control" step="0.1" min="0.1" id="kg" name="kg" onkeyup="calculateBags()" >
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
        foreach ($outer as $value) {
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
        foreach ($middle as $value) {
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
        foreach ($inner as $value) {
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


        function selectLayer(id, name) {
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