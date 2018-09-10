<?php
    $pageTitle = "Injection - Sacks Production";
    
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
			<a href="process.php">Injection</a>
		</li>
		<li class="breadcrumb-item active">Sacks Production</li>
	</ol>
	<h2>Injection - Sacks Production</h2>


	<div id="alertMessage" class="alert hide" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
		<?php
    if(!empty($_POST['shift']) and !empty($_POST['finished']))
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($injection->createSacksProduction()){

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
		<div class="dropdown">
			<button class="btn btn-info" type="button"  data-toggle="modal" data-target="#modal1">Submit sacks production</button>
		</div>
	</div>


	<form class="form-inline" style="padding-bottom:20px;" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<div class="form-group">
			<label for="date">Date:</label>
			<div class='input-group date' id='datetimepicker'>
				<input type='text' class="form-control" name="dateSearch" id="dateSearch" />
				<span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
				</span>
			</div>
		</div>
		<div class="form-group">
			<button type="submit" id="buttonForm" class="btn btn-info">View</button>
		</div>
	</form>
	<ul class="nav nav-tabs nav-justified">
		<li class="active"><a data-toggle="tab" href="#today" id="dateTitle"></a></li>
		<li><a data-toggle="tab" href="#day">Shift: Day</a></li>
		<li><a data-toggle="tab" href="#night">Shift: Night</a></li>
	</ul>
	<div class="tab-content">
		<div id="today" class="tab-pane fade in active">
			<h3 id="dateTitle2"></h3>
			<div class="panel panel-info">
				<div class="panel-heading">
					Daily production
				</div>
				<div class="panel-body">
					<table class="table table-bordered table-hover" width="100%" cellspacing="0">
						<thead>
							<tr class="active">
                            <th>Finished Product</th>
                            <th>Semifinished product 1</th>
                            <th>Semifinished product 2</th>
                            <th>Semifinished product 3</th>
                            <th>Sacks</th>
							</tr>
						</thead>
						<tbody>
							<?php
$injection->giveSacksProduction(0);
?>
						</tbody>
					</table>
			</div>
			</div>

		</div>
		<div id="day" class="tab-pane fade">
			<h3>Day</h3>
<div class="panel panel-info">
				<div class="panel-heading">
					Daily production
				</div>
				<div class="panel-body">
					<table class="table table-bordered table-hover" width="100%" cellspacing="0">
						<thead>
							<tr class="active">
                            <th>Finished Product</th>
                            <th>Semifinished product 1</th>
                            <th>Semifinished product 2</th>
                            <th>Semifinished product 3</th>
                            <th>Sacks</th>
							</tr>
						</thead>
						<tbody>
							<?php
$injection->giveSacksProduction(1);
?>
						</tbody>
					</table>
					
					
			</div>
			
		</div></div>
		<div id="night" class="tab-pane fade">
			<h3>Night</h3>
			<div class="panel panel-info">
				<div class="panel-heading">
					Daily production
				</div>
				<div class="panel-body">
					<table class="table table-bordered table-hover" width="100%" cellspacing="0">
						<thead>
							<tr class="active">
                            <th>Finished Product</th>
                            <th>Semifinished product 1</th>
                            <th>Semifinished product 2</th>
                            <th>Semifinished product 3</th>
                            <th>Sacks</th>
							</tr>
						</thead>
						<tbody>
							<?php
$injection->giveSacksProduction(2);
?>
						</tbody>
					</table>
					
			</div>
			</div>

		</div>

		<div class="modal fade" id="modal1" role="dialog" tabindex="-1">
			<div class="modal-dialog" style="width: 600px">
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" type="button" data-dismiss="modal">x</button>
						<h4 class="modal-title">Submit Production</h4>
					</div>
					<form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-6 form-group">
									<label for="date">Date <span class="text-danger">*</span></label>
									<div class='input-group date' id='datetimepicker2'>
										<input type='text' class="form-control" id="date" name="date" required/>
										<span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
										</span>
									</div>
								</div>
								<div class="col-md-6 form-group">
									<label for="shift">Shift <span class="text-danger">*</span></label><br />
									<input type="hidden" class="form-control" id="shift" name="shift" value="1" required>
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_shift" style="height:30px;">Day&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="selectShift(1,'Day')">Day</a></li>
											<li><a onclick="selectShift(2,'Night')">Night</a></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label for="material">Finished Product <span class="text-danger">*</span></label><br>
									<input type="hidden" class="form-control" id="finished" name="finished" required>
									<div class="btn-group">
										<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_finished">&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu" role="menu" id="dropdown_finished">
											<li><input type="text" placeholder="Search finished product.." class="searchDropdown" id="searchFinished" onkeyup="filterFinished()" width="100%"></li>
											<?php
						$injection->finishedFullDropdown();
					?>
										</ul>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label for="material">Semi-Finished Product 1 <span class="text-danger">*</span></label><br>
									<input type="hidden" class="form-control" id="semifinished1" name="semifinished1" required>
									<input type="text" class="form-control input-sm"  id="btn_material1"  readonly required >	
								</div>
								<div class="col-md-6 form-group">
									<label for="shift">Type <span class="text-danger">*</span></label><br />
									<input type="hidden" class="form-control" id="type1" name="type1" required>
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_type1" style="height:30px;">&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="selectType1(-1,'Transparent')">Transparent</a></li>
											<?php
						$injection->colorsDropdown(1);
					?>
										</ul>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label for="material">Semi-Finished Product 2 <span class="text-danger">*</span></label><br>
									<input type="hidden" class="form-control" id="semifinished2" name="semifinished2" required>
									<input type="text" class="form-control input-sm"  id="btn_material2"  readonly required >	
								</div>
								<div class="col-md-6 form-group">
									<label for="shift">Type <span class="text-danger">*</span></label><br />
									<input type="hidden" class="form-control" id="type2" name="type2"  required>
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_type2" style="height:30px;">&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="selectType2(-1,'Transparent')">Transparent</a></li>
											<?php
						$injection->colorsDropdown(2);
					?>
										</ul>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label for="material">Semi-Finished Product 3 <span class="text-danger">*</span></label><br>
									<input type="hidden" class="form-control" id="semifinished3" name="semifinished3" required>
											
									<input type="text" class="form-control input-sm"  id="btn_material3"  readonly required >							

								</div>
								<div class="col-md-6 form-group">
									<label for="shift">Type </label><br />
									<input type="hidden" class="form-control" id="type3" name="type3"  required>
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_type3" style="height:30px;">&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="selectType3(-1,'Transparent')">Transparent</a></li>
											<?php
						$injection->colorsDropdown(3);
					?>
										</ul>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label for="size" >Pieces in sack<span class="text-danger">*</span></label>
									<input type="number" class="form-control input-sm" name="pieces" id="pieces" value="0"  step="1" min="1" required >
								</div>
								<div class="col-md-6 form-group">
									<label for="size" >Sacks<span class="text-danger">*</span></label>
									<input type="number" class="form-control input-sm" name="sacks" id="sacks" value="0"  step="1" min="1" required >
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
							<button type="submit" id="buttonForm" class="btn btn-info">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<?php
  if(!empty($_POST['dateSearch']) )
   {
       echo '<script>document.getElementById("dateTitle").innerHTML = "'. $_POST['dateSearch'] .'";</script>';
      echo '<script>document.getElementById("dateTitle2").innerHTML = "'. $_POST['dateSearch'] .'";</script>';
       echo '<script>document.getElementById("dateSearch").value = "'. $_POST['dateSearch'] .'";</script>';
   }
 else
 {
       echo '<script>var d = new Date();
            var month = d.getMonth()+1;
            document.getElementById("dateTitle").innerHTML = d.getDate() + "/" + month +"/"+ d.getFullYear();
            document.getElementById("dateTitle2").innerHTML = d.getDate() + "/" + month +"/"+ d.getFullYear();
            document.getElementById("dateSearch").value = d.getDate() + "/" + month +"/"+ d.getFullYear();</script>';
 }
    
?>
			<script>
				function selectShift(id, name) {
					document.getElementById("btn_shift").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("shift").value = id;
					if (id == 1) {
						var d = new Date();
						var month = d.getMonth() + 1;
						document.getElementById("date").value = d.getDate() + "/" + month + "/" + d.getFullYear();
					} else {
						var d = new Date();
						d.setDate(d.getDate() - 1);
						var month = d.getMonth() + 1;
						document.getElementById("date").value = d.getDate() + "/" + month + "/" + d.getFullYear();
					}

				}
				
				function selectType1(id, name) {
					document.getElementById("btn_type1").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("type1").value = id;
				}
				
				function selectType2(id, name) {
					document.getElementById("btn_type2").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("type2").value = id;
				}
				
				
				function selectType3(id, name) {
					document.getElementById("btn_type3").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("type3").value = id;
				}
				
				function selectFinished(id, name, semi1id, semi1name, semi2id, semi2name, semi3id, semi3name, pieces ) {
					document.getElementById("btn_finished").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("finished").value = id;
					if(semi1id != null)
					{
						selectType1(-1,'Transparent');
					}
					if(semi2id != null)
					{
						selectType2(-1,'Transparent');
					}
					if(semi3id != null)
					{
						selectType3(-1,'Transparent');
					}
					document.getElementById("btn_material1").value = semi1name;
					document.getElementById("semifinished1").value = semi1id;
					document.getElementById("btn_material2").value = semi2name;
					document.getElementById("semifinished2").value = semi2id;
					document.getElementById("btn_material3").value = semi3name;
					document.getElementById("semifinished3").value = semi3id;
					document.getElementById("pieces").value = pieces;
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
				
			</script>
			<script>
				$(function() {
					// #datePicker
					$('#datetimepicker').datetimepicker({
						format: 'DD/MM/YYYY'
					});

					$('#timepicker').datetimepicker({
						format: 'HH:mm'
					});
					$('#timepicker2').datetimepicker({
						format: 'HH:mm'
					});

					$('#datetimepicker').data("DateTimePicker").maxDate(new Date());

					$('#datetimepicker2').datetimepicker({
						format: 'DD/MM/YYYY'
					});

					$('#datetimepicker2').data("DateTimePicker").maxDate(new Date());

					var d = new Date();
					var month = d.getMonth() + 1;
					document.getElementById("date").value = d.getDate() + "/" + month + "/" + d.getFullYear();

				})
			</script>

			<?php
    include_once '../footer.php';
?>