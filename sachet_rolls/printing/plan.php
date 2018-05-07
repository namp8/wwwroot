<?php
    $pageTitle = "Orders SalesOrders";
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
        <li class="breadcrumb-item active">Production Plan</li>
    </ol>
    <h2>Orders - Production Plan</h2>



    <div class="row">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4>Production plan for sales orders</h4>
            </div>
            <div class="panel-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Product name</th>
                                <th>Plan Quantity (kgs)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
    $printing->giveProductionPlan();
?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    

   <script>
        $(document).ready(function() {
            $('#dataTable').DataTable(  );
        } );
    </script>

    <?php
    include_once '../../footer.php';
?>