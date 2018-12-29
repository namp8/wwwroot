<?php
    $pageTitle = "Macchi Water Pouch - Rolls";
    
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar1.php";
    include_once "../../content.php";


    include_once "../../inc/class.macchi.inc.php";
    $macchi = new Macchi($db);

?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item active">Water Pouch - Rolls on floor</li>
    </ol>
    <h2>Macchi - Water Pouch - Rolls on floor</h2>

    <div class="panel panel-info">
        <div class="panel-heading"> Rolls information by size</div>
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
                  <th>Size</th>
                  <th>Total Rolls</th>
                  <th>Total Gross Weight</th>
                  <th>Total Net Weight</th>
                  <th>Average Net Weight</th>
                </tr>
              </thead>
              <tbody>
<?php
    $rollsInfo = $macchi->giveRollsInfo();
//    echo '<script>alert('. $rollsInfo[0][0][0] .');</script>';
    echo '<script>var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    theme: "light2",
                    title: {
                        text: "Number of rolls"
                    },
                    data: [
                    {
                        type: "column",   
                        legendText: "# Rolls",      
                        showInLegend :true,              
                        dataPoints: '. json_encode($rollsInfo[0], JSON_NUMERIC_CHECK) 
                    .'}
                    ]
                });
                chart.render();</script>';  
    echo '<script>var chart = new CanvasJS.Chart("chartContainer2", {
                    animationEnabled: true,
                    theme: "light2",
                    title: {
                        text: "Rolls weight"
                    },
                    data: [
                    {
                        type: "column", 
                        legendText: "Gross weight",
                        showInLegend :true,
                        dataPoints: '. json_encode($rollsInfo[1], JSON_NUMERIC_CHECK) 
                    .'},
                    {
                        type: "column",          
                        legendText: "Net weight",      
                        showInLegend :true,
                        dataPoints: '. json_encode($rollsInfo[2], JSON_NUMERIC_CHECK) 
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
                        dataPoints: '. json_encode($rollsInfo[3], JSON_NUMERIC_CHECK) 
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
        <div class="panel-heading"> Rolls information by thickness</div>
      <div class="panel-body">
    

    <div class="col-md-3">
        <div id="chartContainer4"  style="height: 200px; ">
        </div>
    </div>
    <div class="col-md-6">
        <div id="chartContainer5"  style="height: 200px; ">
        </div>
    </div>
    <div class="col-md-3">
        <div id="chartContainer6"  style="height: 200px; ">
        </div>
    </div>
    <div class="table-responsive" style="padding-top:50px;">
            <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr class="active">
                  <th>Thickness</th>
                  <th>Total Rolls</th>
                  <th>Total Gross Weight</th>
                  <th>Total Net Weight</th>
                  <th>Average Net Weight</th>
                </tr>
              </thead>
              <tbody>
<?php
    $rollsInfo2 = $macchi->giveRollsByThickness();
//    echo '<script>alert('. $rollsInfo[0][0][0] .');</script>';
    echo '<script>var chart = new CanvasJS.Chart("chartContainer4", {
                    animationEnabled: true,
                    theme: "light2",
                    title: {
                        text: "Number of rolls"
                    },
                    data: [
                    {
                        type: "column",   
                        legendText: "# Rolls",      
                        showInLegend :true,              
                        dataPoints: '. json_encode($rollsInfo2[0], JSON_NUMERIC_CHECK) 
                    .'}
                    ]
                });
                chart.render();</script>';  
    echo '<script>var chart = new CanvasJS.Chart("chartContainer5", {
                    animationEnabled: true,
                    theme: "light2",
                    title: {
                        text: "Rolls weight"
                    },
                    data: [
                    {
                        type: "column", 
                        legendText: "Gross weight",
                        showInLegend :true,
                        dataPoints: '. json_encode($rollsInfo2[1], JSON_NUMERIC_CHECK) 
                    .'},
                    {
                        type: "column",          
                        legendText: "Net weight",      
                        showInLegend :true,
                        dataPoints: '. json_encode($rollsInfo2[2], JSON_NUMERIC_CHECK) 
                    .'},
                    ]
                });
                chart.render();</script>';  
    echo '<script>var chart = new CanvasJS.Chart("chartContainer6", {
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
                        dataPoints: '. json_encode($rollsInfo2[3], JSON_NUMERIC_CHECK) 
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
        <div class="panel-heading"> List of rolls in floor </div>
        <div class="panel-body">
<div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="active">
                  <th>Roll No.</th>
                  <th>Size</th>
                  <th>Gross Weight</th>
                  <th>Net Weight</th>
                  <th>Thickness</th>
                </tr>
              </thead>
              <tbody>
<?php
    $macchi->giveRollsStock();
?>
              </tbody>
				<tfoot>
					<tr class="active">
						<th></th>
						<th>Total</th>
						<th style="text-align:right"></th>
						<th style="text-align:right"></th>
						<th></th>
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