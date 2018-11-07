<?php
    $pageTitle = "Injection - Sacks Weighted";
    
    include_once "../base.php";
    include_once "../header.php";
    include_once "sidebar.php";
    include_once "../content.php";


    include_once "../inc/class.injection.inc.php";
    $injection = new Injection($db);

	include_once "../inc/class.users.inc.php";
    $users = new Users($db);
?>
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="../../index.php">United Production System</a>
		</li>
		<li class="breadcrumb-item">
			<a href="process.php">Injection</a>
		</li>
		<li class="breadcrumb-item active">Sacks Weighted</li>
	</ol>
	<h2>Injection - Sacks Weighted</h2>


	<div id="alertMessage" class="alert hide" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
		<?php
    if(!empty($_POST['shift']) )
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($injection->createSacksWeight()){

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
			<button class="btn btn-info dropdown-toggle" type="button"  data-toggle="modal" data-target="#modal1">Submit sacks&nbsp&nbsp</button>
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
					Daily packing sacks
				</div>
				<div class="panel-body">
<?php
     $injection->giveSacksWeight(0);
?>
					
			</div>

		</div>
		<div id="day" class="tab-pane fade">
			<h3>Day</h3>
<div class="panel panel-info">
				<div class="panel-heading">
					Daily packing sacks
				</div>
				<div class="panel-body">
					
<?php
     $injection->giveSacksWeight(1);
?>
					
					
			</div>
			
		</div></div>
		<div id="night" class="tab-pane fade">
			<h3>Night</h3>
			<div class="panel panel-info">
				<div class="panel-heading">
					Daily packing sacks
				</div>
				<div class="panel-body">
					
<?php
     $injection->giveSacksWeight(2);
?>
					
					
			</div>
			</div>

		</div>

		<div class="modal fade" id="modal1" role="dialog" tabindex="-1">
			<div class="modal-dialog" style="width: 600px">
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" type="button" data-dismiss="modal">x</button>
						<h4 class="modal-title">Submit sacks</h4>
					</div>
					<form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-6 form-group">
									<label for="date">Date <span class="text-danger">*</span></label>
									<div class='input-group date' id='datetimepicker2'>
										<input type='text' class="form-control" id="date" name="date" required  />
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
								
								<div class="col-md-6 form-group">
									<label for="shift">Type <span class="text-danger">*</span></label><br />
									<input type="hidden" class="form-control" id="cols" name="cols" value="0" required>
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_cols" style="height:30px;">Transparent&nbsp&nbsp<span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="selectCols(0,'Transparent')">Transparent</a></li>
											<li><a onclick="selectCols(1,'Colors')">Colors</a></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="panel panel-info">
								<div class="panel-heading">
									Sacks
								</div>
								<div class="panel-body">
									<table class="table table-bordered table-hover" width="100%" cellspacing="0">
										<thead>
											<tr class="active">
												<th class="text-center">No. of sacks.</th>
												<th class="text-center">Sack Wt.</th>
												<th class="text-center">No. of sacks.</th>
												<th class="text-center">Sack Wt.</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><input type="number" class="form-control input-sm" step="1" min="1" name="no_1" value="25" required></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_1" required></td>
												<td><input type="number" class="form-control input-sm" step="1" min="0" name="no_2" value="25"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_2"></td>
											</tr>
											<tr>
												<td><input type="number" class="form-control input-sm" step="1" min="0" name="no_3" value="25"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_3"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="0" name="no_4" value="25"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_4"></td>
											</tr>
											<tr>
												
												<td><input type="number" class="form-control input-sm" step="1" min="0" name="no_5" value="25"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_5"></td>
												<td><input type="number" class="form-control input-sm" step="1" min="0" name="no_6" value="25"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_6"></td>
											</tr>
											<tr>
												
												<td><input type="number" class="form-control input-sm" step="1" min="0" name="no_7" value="25"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_7"></td>
												
												<td><input type="number" class="form-control input-sm" step="1" min="0" name="no_8" value="25"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_8"></td>
											</tr>
											<tr>
												
												<td><input type="number" class="form-control input-sm" step="1" min="0" name="no_9" value="25"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_9"></td>
												
												<td><input type="number" class="form-control input-sm" step="1" min="0" name="no_10" value="25"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_10"></td>
											</tr>
											<tr>
												
												<td><input type="number" class="form-control input-sm" step="1" min="0" name="no_11" value="25"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_11"></td>
												
												<td><input type="number" class="form-control input-sm" step="1" min="0" name="no_12" value="25"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_12"></td>
											</tr>
											<tr>
												
												<td><input type="number" class="form-control input-sm" step="1" min="0" name="no_13" value="25"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_13"></td>
												
												<td><input type="number" class="form-control input-sm" step="1" min="0" name="no_14" value="25"></td>
												<td><input type="number" class="form-control input-sm" step="0.01" min="0" name="wt_14"></td>
											</tr>
											
										</tbody>
									</table>
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

				}
				
				function selectCols(id, name) {
					document.getElementById("btn_cols").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
					document.getElementById("cols").value = id;
				}
				
				function selectFinished(id, name, semi1id, semi1name, semi2id, semi2name, semi3id, semi3name, pieces ) {
					document.getElementById("btn_finished").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
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
			</script>
			<script>
				$(function() {
					// #datePicker
					$('#datetimepicker').datetimepicker({
						format: 'DD/MM/YYYY',
						defaultDate: moment()
					});


					$('#datetimepicker').data("DateTimePicker").maxDate(new Date());

					$('#datetimepicker2').datetimepicker({
						format: 'DD/MM/YYYY',
						defaultDate: moment()
					});
<?php 
						   if(!$users->admin())
						   {	
							   echo "if(moment().weekday()==1)
								{
									$('#datetimepicker2').data('DateTimePicker').minDate(moment().add(-2, 'days').millisecond(0).second(0).minute(0).hour(0));
								}
								else
								{
									$('#datetimepicker2').data('DateTimePicker').minDate(moment().add(-1, 'days').millisecond(0).second(0).minute(0).hour(0));
								}";
						   }
					?>	
					$('#datetimepicker2').data("DateTimePicker").maxDate(new Date());

					var d = new Date();
					var month = d.getMonth() + 1;
					document.getElementById("date").value = d.getDate() + "/" + month + "/" + d.getFullYear();

				})
			</script>

			<?php
    include_once '../footer.php';
?>