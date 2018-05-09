<?php

    $pageTitle = "Injection Waste";

     include_once "../base.php";
    include_once "../header.php";
    include_once "sidebar.php";
    include_once "../content.php";


    include_once "../inc/class.Injection.inc.php";
    $injection = new Injection($db);

?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="process.php">Injection </a>
        </li>
        <li class="breadcrumb-item active">Extruder - Waste</li>
    </ol>
    <h2>Injection - Waste</h2>



        <div class="panel panel-info">
            <div class="panel-heading">
                Historic waste:
            </div>
            <div class="panel-body">

                <div class="table-responsive">
                    <table class="table table-bordered  table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="active">
                                <th>Date</th>
                                <th>Shift</th>
                                <th>Machine</th>
                                <th>User</th>
                                <th>Product Waste in Kgs</th>
                            </tr>
                        </thead>
						
					<tfoot>
						<tr class="active">
							<th></th>
							<th></th>
							<th></th>
							<th>Total</th>
							<th style="text-align:right"></th>
						</tr>
					</tfoot>
                        <tbody>
<?php
     $injection->giveWaste();
?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <script>
            $(function() {
				
				
				
			$("#dataTable").DataTable({
				"order": [],
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

					pageTotal4 = api
						.column(4, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(4).footer()).html(
						'' + pageTotal4.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
				
					
				}
			});
				
            })
        </script>
  
    
    <?php
    include_once '../footer.php';
?>