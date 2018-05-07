<?php
    $pageTitle = "Printing Stock (Ink)";
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar1.php";
    include_once "../../content.php";


    include_once "../../inc/class.printing.inc.php";
    $printing = new printing($db);

    include_once "../../inc/class.stock.inc.php";
    $stock = new Stock($db);

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
        <li class="breadcrumb-item active">Stock (Ink / Solvent)</li>
    </ol>
    <h2>Printing - Stock (Ink / Solvent)</h2>


    <ul class="nav nav-tabs nav-justified">
        <li class="active"><a data-toggle="tab" href="#Roto">Roto</a></li>
        <li><a data-toggle="tab" href="#Flexo1">Flexo 1</a></li>
        <li><a data-toggle="tab" href="#Flexo2">Flexo 2</a></li>
    </ul>
    <div class="tab-content">
        <div id="Roto" class="tab-pane fade in active">
            <h3>Roto</h3>
            <div class="panel panel-info">
                <div class="panel-heading"> Roto - List of Ink / Solvent in stock </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered  table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr class="active">
                                    <th>Ink / Solvent</th>
                                    <th>Grade</th>
                                    <th>Buckets</th>
                                    <th>Kgs</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <?php
        $stock->stockMaterials(3);
    ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="Flexo1" class="tab-pane fade">
            <h3>Flexo</h3>
            <div class="panel panel-info">
                <div class="panel-heading"> Flexo 1 - List of Ink / Solvent in stock </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered  table-hover" id="dataTable1" width="100%" cellspacing="0">
                            <thead>
                                <tr class="active">
                                    <th>Ink / Solvent</th>
                                    <th>Grade</th>
                                    <th>Buckets</th>
                                    <th>Kgs</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
        $stock->stockMaterials(4);
    ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="Flexo2" class="tab-pane fade">
            <h3>Flexo 2</h3>
            <div class="panel panel-info">
                <div class="panel-heading"> Flexo 2 - List of Ink / Solvent in stock </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered  table-hover" id="dataTable2" width="100%" cellspacing="0">
                            <thead>
                                <tr class="active">
                                    <th>Ink / Solvent</th>
                                    <th>Grade</th>
                                    <th>Buckets</th>
                                    <th>Kgs</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
        $stock->stockMaterials(5);
    ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            $('#dataTable').DataTable();
            $('#dataTable1').DataTable();
            $('#dataTable2').DataTable();
        });
    </script>

    <?php
    include_once '../../footer.php';
?>