<?php
    $pageTitle = "Colors for ".$_POST['name'];
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar1.php";
    include_once "../../content.php";


    include_once "../../inc/class.printing.inc.php";
    $printing = new Printing($db);

?>
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="../index.php">United Production System</a>
		</li>
		<li class="breadcrumb-item">
			<a href="../process.php">Sachet Rolls</a>
		</li>
		<li class="breadcrumb-item">
			<a>Orders</a>
		</li>
		<li class="breadcrumb-item"><a href="customers.php">Customers</a></li>
		<li class="breadcrumb-item active">Colors (
			<?php echo $_POST['name']?>)</li>
	</ol>
	<h2>Orders - Colors for
		<?php echo $_POST['name']?> </h2>


	<div id="alertMessage" class="alert hide" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
		<?php
    if(!empty($_POST['action']) and $_POST['action'] ==1)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($printing->createColor()){

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
        if($printing->updateColor()){

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
        if($printing->deleteColor()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
      
       
?>
	</div>

	<div class="row">
		<div class="col-md-6">
			<div class="text-left" style="padding-bottom:20px">
				<div class="dropdown">
					<button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Edit Design&nbsp&nbsp <i class="fa fa-caret-down" style="display: inline;"></i></button>
					<ul class="dropdown-menu dropdown-menu-right">
						<li><a onclick="add()" data-toggle="modal" data-target="#modal1">Add Color</a></li>
						<li><a onclick="update()" data-toggle="modal" data-target="#modal1">Update Color</a></li>
						<li><a onclick="deleteColor()" data-toggle="modal" data-target="#modal1">Delete Color</a></li>
					</ul>
				</div>
			</div>
			<div class="panel panel-info">
				<div class="panel-heading">
					<h4>List of colors</h4>
				</div>
				<div class="panel-body">

					<div class="table-responsive">
						<table class="table table-bordered table-hover" width="100%" cellspacing="0">
							<thead>
								<tr class="active">
									<th>Color</th>
									<th>Fresh Ink consumption for 100 kgs</th>
									<th>Medium</th>
								</tr>
							</thead>
							<tbody>
								<?php
	$colors = $printing->giveColors()
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			
			<div id="chartContainer" >
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
						<input type="hidden" class="form-control" name="customer" id="customer" value="<?php echo $_POST['customer']?>" />
						<input type="hidden" class="form-control" name="name" id="name" value="<?php echo $_POST['name']?>"/>
						<input type="hidden" class="form-control" id="action" name="action" value=0>
						<div class="form-group">
							<label for="color">Color</label><br />
							<input type="hidden" class="form-control" id="color" name="color">
							<div class="dropdown">
								<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_color">&nbsp&nbsp<span class="caret"></span></button>
								<ul class="dropdown-menu" id="dropdown_color">
									<li><input type="text" placeholder="Search color.." class="searchDropdown" id="searchColor" onkeyup="filterColors()"></li>
									<?php
    $printing->colorsDropdown();
?>
								</ul>
							</div>
						</div>
						<div class="form-group" id="kgForm">
							<label for="kg">Fresh Ink consumption for 100 kgs</label>
							<input type="number" class="form-control" step="0.1" min="0.1" id="kg" name="kg">
						</div>
						<div class="form-group" id="mediumForm">
							<div class="checkbox" style="font-size: 16px;">
								<label><input type="checkbox" name="medium" value="1">Medium</label>
							</div>
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
				title: { 
                text: "Colors for <?php echo $_POST['name']?>  "
				},
				exportFileName: "Colors for <?php echo $_POST['name']?> " ,
				exportEnabled: true,
                animationEnabled: true,
                data: [{
                    type: "pie",
                    indexLabelFontSize: 12,
                    indexLabel: "{label} - {y}",
                    yValueFormatString: "###0.0\"%\"",
                    dataPoints: [
                        <?php
        foreach ($colors as $value) {
            echo "{y: ". $value[1].", label:'". $value[0]."'},";
        }
    ?>
                    ]
                }]
            });
            chart.render();
		 }
	 
        function add() {
            document.getElementById("action").value = 1;
            document.getElementById("buttonForm").innerHTML = "Add";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");    
            document.getElementById("panelTitle").innerHTML = "Add color";
            document.getElementById("kgForm").style.display = "";
            document.getElementById("mediumForm").style.display = "";
        }

        function update() {

            document.getElementById("action").value = 2;
            document.getElementById("buttonForm").innerHTML = "Update";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");  
            document.getElementById("panelTitle").innerHTML = "Update Color";
            document.getElementById("kgForm").style.display = "";
            document.getElementById("mediumForm").style.display = "";
        }

        function deleteColor() {
            document.getElementById("action").value = 3;
            document.getElementById("buttonForm").innerHTML = "Delete";
            document.getElementById("buttonForm").setAttribute("class","btn btn-danger");  
            document.getElementById("panelTitle").innerHTML = "Delete color";
            document.getElementById("kgForm").style.display = "none";
            document.getElementById("mediumForm").style.display = "none";
        }


        function selectColor(id, name) {
            document.getElementById("btn_color").innerHTML = name+" &nbsp&nbsp<span class='caret'></span> ";
            document.getElementById("color").value = id;
        }


        function filterColors() {
            var input, filter, ul, li, a, i;
            input = document.getElementById("searchColor");
            filter = input.value.toUpperCase();
            div = document.getElementById("dropdown_color");
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