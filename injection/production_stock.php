<?php
    $pageTitle = "Injection Stock (Production)";
    
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
            <a href="process.php">Injection </a>
        </li>
        <li class="breadcrumb-item active">Production on floor</li>
    </ol>
    <h2>Injection - Production on floor</h2>

    <div class="panel panel-info">
        <div class="panel-heading"> Production information </div>
      <div class="panel-body">
    

    <div class="col-md-12">
        <div id="chartContainer"  style="height: 200px; ">
        </div>
    </div>
    <div class="table-responsive" style="padding-top:50px;">
            <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr class="active">
                  <th>Product Name</th>
                  <th>Type</th>
                  <th>Good Production in Pcs</th>
                </tr>
              </thead>
              <tbody>
<?php
    $ProductionInfo = $injection->giveProductionInfo();
    echo '<script>var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    theme: "light2",
                    title: {
                        text: "Good Production in Pcs"
                    },
                    data: [
                    {
                        type: "column",   
                        legendText: "Pcs",      
                        showInLegend :true,              
                        dataPoints: '. json_encode($ProductionInfo[0], JSON_NUMERIC_CHECK) 
                    .'}
                    ]
                });
                chart.render();</script>';  
?>
              </tbody>
            </table>
    </div>
    </div>
</div>
<div class="panel panel-info">
        <div class="panel-heading"> List of Production in floor </div>
        <div class="panel-body">
<div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="active">
                  <th>Production Date</th>
                  <th>Product Name</th>
                  <th>Type</th>
                  <th>Good Production in Pcs</th>
                </tr>
              </thead>
              <tbody>
<?php
    $injection->giveProductionStock();
?>
              </tbody>
				<tfoot>
					<tr class="active">
						<th></th>
						<th></th>
						<th>Total</th>
						<th style="text-align:right"></th>
					</tr>
				</tfoot>
            </table>

</div>
</div>
</div>

<script>
        $(document).ready(function() {            
            $('#dataTable').DataTable( {
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

					// Total 3
					pageTotal3 = api
						.column(3, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(3).footer()).html(
						''+ pageTotal3.toLocaleString()
					);
					
					
				}
            } );
        } );
    </script>  
    
    <?php
    include_once '../footer.php';
?>