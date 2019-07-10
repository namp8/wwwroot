<?php
    $pageTitle = "Printed Rolls";
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
            <a href="process.php">Printing</a>
        </li>
        <li class="breadcrumb-item active">Rolls</li>
    </ol>
    <h2>Printing - Rolls</h2>


    <div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
    if(!empty($_POST['shift']) )
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($printing->createRoll()){

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
        <div class="pull-right text-right">
			<form id="formMachine" class="form-inline" style="padding-bottom:20px;" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<input type='hidden' class="form-control" name="machine" id="machine" required/>
            <input type='hidden' class="form-control" name="name" id="name" required/>
            <input type='hidden' class="form-control" name="from" id="from" required/>
			<div class="dropdown">
                <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" onclick="selectFrom(2)">Submit Macchi roll&nbsp&nbsp<i class="fa fa-caret-down" style="display: inline;"></i></button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <?php
    $printing->machinesDropdown();
?>
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" onclick="selectFrom(1)">Submit Multilayer roll&nbsp&nbsp<i class="fa fa-caret-down" style="display: inline;"></i></button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <?php
    $printing->machinesDropdown();
?>
                </ul>
            </div>
				<div class="dropdown">
                <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" onclick="selectFrom(3)">Submit Packing Bags roll&nbsp&nbsp<i class="fa fa-caret-down" style="display: inline;"></i></button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <?php
    $printing->machinesDropdown();
?>
                </ul>
            </div>
			</form>
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
    </div>
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
                    Roto
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                         <?php
     $printing->giveRolls(0,3);
?>
                            
                    </div>
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    Flexo 1
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        
                                <?php
     $printing->giveRolls(0,4);
?>
                    </div>
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    Flexo 2
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        
                                <?php
     $printing->giveRolls(0,5);
?>
                    </div>
                </div>
            </div>
        </div>
        <div id="day" class="tab-pane fade">
            <h3>Day</h3>
            <div class="panel panel-info">
                <div class="panel-heading">
                    Roto
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                       
                                <?php
     $printing->giveRolls(1,3);
?>
                    </div>
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    Flexo 1
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                                <?php
     $printing->giveRolls(1,4);
?>
                    </div>
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    Flexo 2
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        
                                <?php
     $printing->giveRolls(1,5);
?>
                    </div>
                </div>
            </div>

        </div>
        <div id="night" class="tab-pane fade">
            <h3>Night</h3>
<div class="panel panel-info">
                <div class="panel-heading">
                    Roto
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                       
                                <?php
     $printing->giveRolls(2,3);
?>
                    </div>
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    Flexo 1
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        
                                <?php
     $printing->giveRolls(2,4);
?>
                    </div>
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    Flexo 2
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        
                                <?php
     $printing->giveRolls(2,5);
?>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal1" role="dialog" tabindex="-1">
            <div class="modal-dialog" style="width: 1000px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" type="button" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Submit rolls for <span id="titlemachine"></span></h4>
                    </div>
                    <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="modal-body">
                            <div class="row">

                                <input type="hidden" class="form-control" id="machine1" name="machine1">
                                <div class="col-lg-3 form-group">
									<label for="date">Date:</label>
									<div class='input-group date' id='datetimepicker2'>
										<input type='text' class="form-control" name="date" id="date" />
										<span class="input-group-addon">
														<span class="fa fa-calendar"></span>
										</span>
									</div>
								</div>
                                <div class="col-lg-3 form-group">
                                    <label for="customer">Customer</label><br />
                                    <input type="hidden" class="form-control" id="customer" name="customer">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_customer">&nbsp&nbsp<span class="caret"></span></button>
                                        <ul class="dropdown-menu" id="dropdown_customer">
                                            <li><input type="text" placeholder="Search customer.." class="searchDropdown" id="searchCustomer" onkeyup="filterCustomers()" width="100%"></li>
                                            <?php
    
   if(!empty($_POST['machine']) and $_POST['from'] ==1 )
   {
        $printing->customersDropdown();
   }
   if(!empty($_POST['machine']) and $_POST['from'] ==2 )
   {
        $printing->customersDropdown();
   }	
   if(!empty($_POST['machine']) and $_POST['from'] ==3 )
   {
        $printing->customersPackingDropdown();
   }
 ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <label for="shift">Shift</label><br />
                                    <input type="hidden" class="form-control" id="shift" name="shift" value="1">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_shift" style="height:30px;">Day&nbsp&nbsp<span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li><a onclick="selectShift(1,'Day')">Day</a></li>
                                            <li><a onclick="selectShift(2,'Night')">Night</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <label for="size">Size</label><br />
                                    <input type="hidden" class="form-control" id="size" name="size">
                                    <input type="hidden" class="form-control" id="cone" name="cone">
                                    <input type="text" class="form-control" id="sizeName" value="0" disabled>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    Input Rolls Details
                                </div>
                                <div class="panel-body">
                                    <div class="col-lg-2 form-group">
                                        <label for="rollno">Roll No.</label><br />
                                        <input type="hidden" class="form-control" id="rollid" name="rollid">
                                        <input type="hidden" class="form-control" id="rollno" name="rollno">
                                        <input type="hidden" class="form-control" id="from1" name="from">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_roll">&nbsp&nbsp<span class="caret"></span></button>
                                            <ul class="dropdown-menu" id="dropdown_roll">
                                                <li><input type="text" placeholder="Search roll.." class="searchDropdown" id="searchRoll" onkeyup="filterRolls()" width="100%"></li>
                                                <?php
   if(!empty($_POST['machine']) and $_POST['from'] ==1 )
   {
        $printing->giveRollsMultilayerDropdown($_POST['machine'],1);
        echo '<script>document.getElementById("titlemachine").innerHTML = "'.$_POST['name'].'";</script>';
        echo '<script>document.getElementById("machine1").value = '.$_POST['machine'].';</script>';
        echo '<script>document.getElementById("from1").value = '.$_POST['from'].';</script>';
   }
   if(!empty($_POST['machine']) and $_POST['from'] ==2 )
   {
        $printing->giveRollsMacchiDropdown($_POST['machine'],1);
        echo '<script>document.getElementById("titlemachine").innerHTML = "'.$_POST['name'].'";</script>';
        echo '<script>document.getElementById("machine1").value = '.$_POST['machine'].';</script>';
        echo '<script>document.getElementById("from1").value = '.$_POST['from'].';</script>';
   }	
   if(!empty($_POST['machine']) and $_POST['from'] ==3 )
   {
        $printing->giveRollsPackingDropdown($_POST['machine'],1);
        echo '<script>document.getElementById("titlemachine").innerHTML = "'.$_POST['name'].'";</script>';
        echo '<script>document.getElementById("machine1").value = '.$_POST['machine'].';</script>';
        echo '<script>document.getElementById("from1").value = '.$_POST['from'].';</script>';
   }
 ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 form-group">
                                        <label>Gross Wt.</label>
                                        <input type="number" class="form-control" step="0.1" min="1" id="inputRollWt" value="0" disabled>
                                    </div>
                                    <div class="col-lg-2 form-group">
                                        <label>Net Wt.</label>
                                        <input type="number" class="form-control" step="0.1" min="1" id="inputNetWt" value="0" disabled>
                                    </div>
									<div class="col-lg-2 form-group">
                                        <label>Gross Wt used.</label>
                                        <input type="number" class="form-control" step="0.1" min="0" id="grossRollWt" name="grossRollWt" value="0" onkeyup="getBalance()">
                                    </div>
                                    <div class="col-lg-2 form-group">
                                        <label>Waste</label>
                                        <input type="number" class="form-control" step="0.01" min="0" id="inputWaste" name="inputWaste" value="0">
                                    </div>
                                    <div class="col-lg-2 form-group">
                                        <label>Test</label>
                                        <div class="checkbox" style="font-size: 16px;">
                                            <label><input type="checkbox" value="1" name="dyne" checked>Dyne test</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 form-group">
                                        <label for="rollno2">Roll No.</label><br />
                                        <input type="hidden" class="form-control" id="rollid2" name="rollid2" value="null">
                                        <input type="hidden" class="form-control" id="rollno2" name="rollno2">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_roll2">&nbsp&nbsp<span class="caret"></span></button>
                                            <ul class="dropdown-menu" id="dropdown_roll2">
                                                <li><input type="text" placeholder="Search roll.." class="searchDropdown" id="searchRoll2" onkeyup="filterRolls2()" width="100%"></li>
												<li><a onclick="selectRoll(2,null,'None',0,0)">None</a></li>
                                                <?php
   if(!empty($_POST['machine']) and $_POST['from'] ==1 )
   {
        $printing->giveRollsMultilayerDropdown($_POST['machine'],2);
        echo '<script>$(modal1).modal();</script>';
   }
   if(!empty($_POST['machine']) and $_POST['from'] ==2 )
   {
        $printing->giveRollsMacchiDropdown($_POST['machine'],2);
        echo '<script>$(modal1).modal();</script>';
   }	
   if(!empty($_POST['machine']) and $_POST['from'] ==3 )
   {
        $printing->giveRollsPackingDropdown($_POST['machine'],2);
        echo '<script>$(modal1).modal();</script>';
   }
 ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 form-group">
                                        <label>Gross Wt.</label>
                                        <input type="number" class="form-control" step="0.1" min="1" id="inputRollWt2" value="0" disabled>
                                    </div>
                                    <div class="col-lg-2 form-group">
                                        <label>Net Wt.</label>
                                        <input type="number" class="form-control" step="0.1" min="1" id="inputNetWt2" value="0" disabled>
                                    </div>
									<div class="col-lg-2 form-group">
                                        <label>Gross Wt used.</label>
                                        <input type="number" class="form-control" step="0.1" min="0" id="grossRollWt2" name="grossRollWt2" value="0" onkeyup="getBalance()">
                                    </div>
                                    <div class="col-lg-2 form-group">
                                        <label>Waste</label>
                                        <input type="number" class="form-control" step="0.01" min="0" id="inputWaste2" name="inputWaste2" value="0">
                                    </div>
                                    <div class="col-lg-2 form-group">
                                        <label>Test</label>
                                        <div class="checkbox" style="font-size: 16px;">
                                            <label><input type="checkbox" value="1" name="dyne2" checked>Dyne test</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    Output Roll Details
                                </div>
                                <div class="panel-body">
                                    <div class="col-lg-3 form-group">
                                        <label>Roll No.</label>
                                        <input class="form-control" id="outputRoll" name="outputRoll">
                                    </div>
                                    <div class="col-lg-2 form-group">
                                        <label>Gross Wt.</label>
                                        <input type="number" class="form-control" step="0.1" min="1" id="outputRollWt" name="outputRollWt" value="0" onkeyup="getNet()">
                                    </div>
                                    <div class="col-lg-2 form-group">
                                        <label>Net Wt.</label>
                                        <input type="number" class="form-control" step="0.1" min="1" id="outputNetWt" disabled>
                                    </div>
                                    <div class="col-lg-2 form-group">
                                        <label>Waste</label>
                                        <input type="number" class="form-control" step="0.1" min="0" id="outputWaste" name="outputWaste" value="0">
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label>Test</label>
                                        <div class="checkbox" style="font-size: 16px;">
                                            <label><input type="checkbox" value="1" name="tape" checked>Tape test</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<div class="panel panel-info">
                                <div class="panel-heading">
                                    Balance Roll
                                </div>
                                <div class="panel-body">
                                    <div class="col-lg-2 form-group">
                                        <label>Gross Wt.</label>
                                        <input type="number" class="form-control" step="0.1" min="1" id="balanceWt" name="balanceWt" value="0" readonly>
                                    </div>
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
                function selectMachine(id, name, from) {
                    document.getElementById("machine").value = id;
                    document.getElementById("name").value = name;
                    document.getElementById("formMachine").submit();
                }
				function selectFrom(from) {
                    document.getElementById("from").value = from;
                }

                function selectShift(id, name) {
                    document.getElementById("btn_shift").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
                    document.getElementById("shift").value = id;

                }
				
				function getBalance() {
					
					var initial = 0;
					var actual = 0; 
					var initial2 = 0;
					var actual2 = 0; 
					if(!document.getElementById("rollno").value.includes("Balance"))
					{
						initial = document.getElementById("inputRollWt").value;
						actual = document.getElementById("grossRollWt").value;
					}
					
                   	if(!document.getElementById("rollno2").value.includes("Balance"))
					{
						initial2 = document.getElementById("inputRollWt2").value;
						actual2 = document.getElementById("grossRollWt2").value;
					}
					
					var balance = (initial - actual) + (initial2 - actual2);
					document.getElementById("balanceWt").value = balance;

                }
				function filterCustomers() {
					var input, filter, ul, li, a, i;
					input = document.getElementById("searchCustomer");
					filter = input.value.toUpperCase();
					div = document.getElementById("dropdown_customer");
					a = div.getElementsByTagName("a");
					for (i = 0; i < a.length; i++) {
						if (a[i].id.toUpperCase().startsWith(filter)) {
							a[i].style.display = "";
						} else {
							a[i].style.display = "none";
						}
					}
				}

                function selectRoll(i, id, no, gross, net) {
					if(i == 1)
					{
						document.getElementById("btn_roll").innerHTML = no + " &nbsp&nbsp<span class='caret'></span> ";
						document.getElementById("rollid").value = id;
						document.getElementById("rollno").value = no;
						document.getElementById("inputRollWt").value = gross;
						document.getElementById("grossRollWt").value = gross;
						document.getElementById("inputNetWt").value = net;
						if(no.includes("Balance"))
						{
							document.getElementById("inputRollWt").value = "-";
							document.getElementById("inputNetWt").value = "-";
						}
					}
					else
					{
						document.getElementById("btn_roll2").innerHTML = no + " &nbsp&nbsp<span class='caret'></span> ";
						document.getElementById("rollid2").value = id;
						document.getElementById("rollno2").value = no;
						document.getElementById("inputRollWt2").value = gross;
						document.getElementById("grossRollWt2").value = gross;
						document.getElementById("inputNetWt2").value = net;
						if(no.includes("Balance"))
						{
							document.getElementById("inputRollWt2").value = "-";
							document.getElementById("inputNetWt2").value = "-";
						}
					}
                    document.getElementById("outputRoll").value = document.getElementById("date").value.split("/")[0] + "-" + document.getElementById("date").value.split("/")[1] + "-";
					
					getBalance();
                }

                function selectCustomer(id, name) {
                    document.getElementById("btn_customer").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
                    document.getElementById("customer").value = id;
                }

                function getNet() {
                    document.getElementById("outputNetWt").value = document.getElementById("outputRollWt").value - document.getElementById("cone").value;
                }

                function filterRolls() {
                    var input, filter, ul, li, a, i;
                    input = document.getElementById("searchRoll");
                    filter = input.value.toUpperCase();
                    div = document.getElementById("dropdown_roll");
                    a = div.getElementsByTagName("a");
                    for (i = 0; i < a.length; i++) {
                        if (a[i].id.toUpperCase().startsWith(filter)) {
                            a[i].style.display = "";
                        } else {
                            a[i].style.display = "none";
                        }
                    }
                }
				
                function filterRolls2() {
                    var input, filter, ul, li, a, i;
                    input = document.getElementById("searchRoll2");
                    filter = input.value.toUpperCase();
                    div = document.getElementById("dropdown_roll2");
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
    include_once '../../footer.php';
?>