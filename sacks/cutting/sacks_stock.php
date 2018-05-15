<?php
    $pageTitle = "Sacks - Cutting Stock";
    
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar.php";
    include_once "../../content.php";


    include_once "../../inc/class.sacks.inc.php";
    $sacks = new Sacks($db);

?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../../index.php">United Production System</a>
        </li>
		<li class="breadcrumb-item">
			<a href="../process.php">Sacks</a>
		</li>
        <li class="breadcrumb-item active">Sacks on floor</li>
    </ol>
    <h2>Sacks - Cutting - Sacks on floor</h2>

    <div class="panel panel-info">
        <div class="panel-heading"> Sacks information </div>
      <div class="panel-body">
    

    <div class="col-md-3">
        <div id="chartContainer"  style="height: 200px; ">
        </div>
    </div>
    <div class="col-md-6">
        <div id="chartContainer2"  style="height: 200px; ">
        </div>
    </div>
    <div class="col-md-3">
        <div id="chartContainer3"  style="height: 200px; ">
        </div>
    </div>
    <div class="table-responsive" style="padding-top:50px;">
            <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr class="active">
                  <th>Total Sacks</th>
                  <th>Total Gross Weight</th>
                  <th>Total Net Weight</th>
                  <th>Total Not used Weight</th>
                  <th>Average Net Weight</th>
                </tr>
              </thead>
              <tbody>
<?php
    $sacksInfo = $sacks->giveSacksInfo();
    echo '<script>var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    theme: "light2",
                    title: {
                        text: "Number of sacks"
                    },
                    data: [
                    {
                        type: "column",   
                        legendText: "# Sacks",      
                        showInLegend :true,              
                        dataPoints: '. json_encode($sacksInfo[0], JSON_NUMERIC_CHECK) 
                    .'}
                    ]
                });
                chart.render();</script>';  
    echo '<script>var chart = new CanvasJS.Chart("chartContainer2", {
                    animationEnabled: true,
                    theme: "light2",
                    title: {
                        text: "Sacks weight"
                    },
                    data: [
                    {
                        type: "column", 
                        legendText: "Gross weight",
                        showInLegend :true,
                        dataPoints: '. json_encode($sacksInfo[1], JSON_NUMERIC_CHECK) 
                    .'},
                    {
                        type: "column",          
                        legendText: "Net weight",      
                        showInLegend :true,
                        dataPoints: '. json_encode($sacksInfo[2], JSON_NUMERIC_CHECK) 
                    .'},
                    ]
                });
                chart.render();</script>';  
    echo '<script>var chart = new CanvasJS.Chart("chartContainer3", {
                    animationEnabled: true,
                    theme: "light2",
                    title: {
                        text: "Average net weight"
                    },
                    data: [
                    {
                        type: "column",   
                        legendText: "Net weight",      
                        showInLegend :true,              
                        dataPoints: '. json_encode($sacksInfo[3], JSON_NUMERIC_CHECK) 
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
        <div class="panel-heading"> List of sacks in floor </div>
        <div class="panel-body">
<div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="active">
                  <th>Sack No.</th>
                  <th>Gross Weight</th>
                  <th>Net Weight</th>
                </tr>
              </thead>
              <tbody>
<?php
    $sacks->giveSacksStock();
?>
              </tbody>
				<tfoot>
					<tr class="active">
						<th>Total</th>
						<th style="text-align:right"></th>
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
					pageTotal1 = api
						.column(1, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(1).footer()).html(
						''+ pageTotal1.toLocaleString()
					);
					
					// Total 2
					pageTotal2 = api
						.column(2, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(2).footer()).html(
						''+ pageTotal2.toLocaleString()
					);
					
				}
            } );
        } );
    </script>  
    
    <?php
    include_once '../../footer.php';
?>