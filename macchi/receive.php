<?php
    $pageTitle = "Macchi Receive";
    
    include_once "../base.php";
    include_once "../header.php";
    include_once "sidebar.php";
    include_once "../content.php";


    include_once "../inc/class.stock.inc.php";
    $stock = new Stock($db);
?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="process.php">Macchi</a>
        </li>
        <li class="breadcrumb-item active">Receive</li>
    </ol>
    <h2>Macchi - Receive</h2>

<div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
    if(!empty($_POST['id_transfer']))
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($stock->receive()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
	else if(!empty($_POST['approveAll']))
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if(!$stock->receiveAll(5)){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-info show");</script>';
        }
    }
?>
    </div>

	<form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
 		<input type="submit" id="approveAll" name="approveAll" class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" value="Receive All">
     </form>

        <div class="panel panel-info">
            <div class="panel-heading"> Historic Receipts </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                <th>Receive</th>
                                <th>Date</th>
                                <th>To</th>
                                <th>Material</th>
                                <th>Bags Required</th>
                                <th>Requested By</th>
                                <th>Bags Issued</th>
                                <th>Issued By</th>
                                <th>Bags Received</th>
                                <th>Received By</th>
                                <th>Status</th>
                            </tr>
                        </thead>
						<tfoot>
							<tr class="active">
								<th></th>
								<th></th>
								<th></th>
								<th>Total Required</th>
								<th style="text-align:right"></th>
								<th>Total Issued</th>
								<th style="text-align:right"></th>
								<th>Total Received</th>
								<th style="text-align:right"></th>
								<th></th>
								<th></th>
							</tr>
						</tfoot>
                        <tbody>
                            <?php  
    $stock->stockReceipts(5);
?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    
<!-- Logout Modal-->
    <div class="modal fade" id="modal1" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-m">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal">x</button>
            <h4 class="modal-title"> Receive raw material</h4>
          </div>
            <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                
          <div class="modal-body">
              <input type="hidden" class="form-control" id="id_transfer" name="id_transfer">
                <div class="form-group">
                    <label for="date">Date</label>
                    <div class='input-group date' id='datetimepicker'>
                        <input type='text' class="form-control" id="date" name="date" disabled/>
                        <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="form">From</label>
                    <input type="text" class="form-control" id="from" name="from" disabled>
                </div>
                <div class="form-group">
                    <label for="form">To</label>
                    <input type="text" class="form-control" id="to" name="to" disabled>
                </div>

                <div class="form-group">
                    <label for="material">Material</label>
                    <input type="text" class="form-control" id="material" name="material" disabled>
                </div>
                <div class="form-group">
                    <label for="total">Total bags received <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" min="1" step="0.1" id="bags" name="bags" required>
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

<script>
        function receive(id, date, from, to, material, grade, bags) {
            document.getElementById("id_transfer").value = id;
            document.getElementById("date").value = date;
            document.getElementById("from").value = from;
            document.getElementById("to").value = to;
            document.getElementById("material").value = material + " - " + grade;
            document.getElementById("bags").value = bags;
            document.getElementById("form").style.display = "";
            document.getElementById("alertMessage").removeAttribute("class");
            document.getElementById("alertMessage").setAttribute("class","alert hide");
            window.scrollTo(0,0);
        }
    </script>

      <script>
			
        $(document).ready(function() {
            $('#dataTable').DataTable( {"order": [],
				"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
				"footerCallback": function(row, data, start, end, display) {
					var api = this.api(),
						data;

					// Remove the formatting to get integer data for summation
					var intVal = function(i) {
						return typeof i === 'string' ?
							i.replace(/[\$,]/g, '') * 1 :
							typeof i === 'number' ?
							i : 0;
					};

					// Total Amount
					pageTotal4 = api
						.column(4, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(4).footer()).html(
						'' + pageTotal4.toLocaleString()
					);
					
					pageTotal6 = api
						.column(6, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(6).footer()).html(
						'' + pageTotal6.toLocaleString()
					);
					pageTotal8 = api
						.column(8, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(8).footer()).html(
						'' + pageTotal8.toLocaleString()
					);
				}
            } );
        } );
    </script>    
    

    
    <?php
    include_once '../footer.php';
?>