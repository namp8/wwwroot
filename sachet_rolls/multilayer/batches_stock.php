<?php
    $pageTitle = "Multilayer Stock (Mixed)";
    
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar1.php";
    include_once "../../content.php";


    include_once "../../inc/class.multilayer.inc.php";
    $multilayer = new Multilayer($db);

?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="../process.php">Sachet Rolls</a>
        </li>
        <li class="breadcrumb-item">
            <a href="process.php">Multilayer</a>
        </li>
        <li class="breadcrumb-item active">Stock (Mixed)</li>
    </ol>
    <h2>Multilayer - Stock (Mixed Material)</h2>
<div class="panel panel-info">
        <div class="panel-heading"> List of mixed materials in stock </div>
        <div class="panel-body">
<div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                  <th>Layer</th>
                  <th>Kgs</th>
                  <th>Bags</th>
                </tr>
              </thead>
              <tbody>
<?php
    $multilayer->stockBatches();
?>
              </tbody>
            </table>

</div>
        </div>
    </div>
    <script>
			
        $(document).ready(function() {
            $('#dataTable').DataTable( {
                "order": [[ 0, "desc" ]]
            } );
        } );
    </script>    
    <?php
    include_once '../../footer.php';
?>