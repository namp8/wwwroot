<?php
    $pageTitle = "printing Recipe";
    
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar1.php";
    include_once "../../content.php";


    include_once "../../inc/class.printing.inc.php";
    $printing = new Printing($db);

?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="../process.php">Sachet Rolls</a>
        </li>
        <li class="breadcrumb-item">
            <a href="process.php">Printing</a>
        </li>
        <li class="breadcrumb-item active">Recipe</li>
    </ol>
    <h2>Printing - Recipe for each color (ink and solvent)</h2>


    <div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
    if(!empty($_POST['action']) and $_POST['action'] ==1)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($printing->createFormula()){

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
        if($printing->updateFormula()){

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
        if($printing->deleteFormula()){

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
                <li><a onclick="update()" data-toggle="modal" data-target="#modal1">Update Percentage</a></li>
                <li><a onclick="deleteFormula()" data-toggle="modal" data-target="#modal1">Delete Material</a></li>
            </ul>
         </div>
    </div>

    <div class="row" >
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4>Roto</h4>
                </div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                    <th>Material</th>
                                    <th>Grade</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
    $roto = $printing->giveFormulas(3);
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
                    <h4>Flexo 1</h4>
                </div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                    <th>Material</th>
                                    <th>Grade</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
    $flexo1 = $printing->giveFormulas(4);
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
                    <h4>Flexo 2</h4>
                </div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                    <th>Material</th>
                                    <th>Grade</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
    $flexo2 = $printing->giveFormulas(5);
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
                <label for="form">Machine</label>
                <input type="hidden" class="form-control" id="machine" name="machine"><br>
                <div class="btn-group">
                    <button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_machine">&nbsp&nbsp<span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu" id="dropdown_machine">
                        <li><input type="text" placeholder="Search machine.." class="searchDropdown" id="searchMachine" onkeyup="filterMachine()" width="100%"></li>
                        <?php
    $printing->machinesDropdown();
?>
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <label for="material">Material</label><br />
                <input type="hidden" class="form-control" id="material" name="material">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_material">&nbsp&nbsp<span class="caret"></span></button>
                    <ul class="dropdown-menu" id="dropdown_material">
                        <li><input type="text" placeholder="Search material.." class="searchDropdown" id="searchMaterial" onkeyup="filterMaterials()"></li>
                        <li><a id="null" onclick="selectMaterial(null,'Fresh Ink','')"><b>Fresh Ink</b></a></li>
                        <?php
    $printing->noColorsDropdown();
?>
                    </ul>
                </div>
            </div>
            <div class="form-group" id="kgForm">
                <label for="kg">Percentage (%)</label>
                <input type="number" class="form-control" step="1" min="1" max="100" id="percentage" name="percentage">
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
                    yValueFormatString: "###0.0\"%\"",
                    dataPoints: [
                        <?php
        foreach ($roto as $value) {
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
                    yValueFormatString: "###0.0\"%\"",
                    dataPoints: [
                        <?php
        foreach ($flexo1 as $value) {
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
                    yValueFormatString: "###0.0\"%\"",
                    dataPoints: [
                        <?php
        foreach ($flexo2 as $value) {
            echo "{y: ". $value[2].", label:'". $value[0]." (". $value[1].")'},";
        }
    ?>
                    ]
                }]
            });
            chart2.render();

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

        function update() {

            document.getElementById("action").value = 2;
            document.getElementById("buttonForm").innerHTML = "Update";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");  
            document.getElementById("panelTitle").innerHTML = "Update Material Percentage";
            document.getElementById("kgForm").style.display = "";
        }

        function deleteFormula() {
            document.getElementById("action").value = 3;
            document.getElementById("buttonForm").innerHTML = "Delete";
            document.getElementById("buttonForm").setAttribute("class","btn btn-danger");  
            document.getElementById("panelTitle").innerHTML = "Delete material";
            document.getElementById("kgForm").style.display = "none";
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
        function selectMachine(id, name, size) {
            document.getElementById("btn_machine").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
            document.getElementById("machine").value = id;
        }
        function filterMachines() {
            var input, filter, ul, li, a, i;
            input = document.getElementById("searchMachine");
            filter = input.value.toUpperCase();
            div = document.getElementById("dropdown_Machine");
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
    include_once '../../footer.php';
?>