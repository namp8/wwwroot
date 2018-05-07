<?php
    $pageTitle = "Printing Stock (Rolls)";
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar1.php";
    include_once "../../content.php";


    include_once "../../inc/class.printing.inc.php";
    $printing = new Printing($db);
?>

    <script src="/assets/js/canvasjs.min.js"></script>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="../process.php">Sachet Rolls</a>
        </li>
        <li class="breadcrumb-item active">Stock (Printed Rolls)</li>
    </ol>
    <h2>Printing - Stock (Rolls)</h2>

    <div class="panel panel-info">
        <div class="panel-heading"> Rolls information </div>
        <div class="panel-body">


            <div class="col-md-3">
                <div id="chartContainer" style="height: 200px; ">
                </div>
            </div>
            <div class="col-md-6">
                <div id="chartContainer2" style="height: 200px; ">
                </div>
            </div>
            <div class="col-md-3">
                <div id="chartContainer3" style="height: 200px; ">
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
    $rollsInfo = $printing->giveRollsInfo();
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
        <div class="panel-heading"> List of rolls in floor </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="active">
                            <th>Customer</th>
                            <th>Roll No.</th>
                            <th>Size</th>
                            <th>Gross Weight</th>
                            <th>Net Weight</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
    $printing->giveRollsStock();
?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>


    <?php
    include_once '../../footer.php';
?>