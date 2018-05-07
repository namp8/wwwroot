<?php
    include_once "../base.php";
    $pageTitle = "Warehouse Reports";
    include_once "../header.php";
    include_once "sidebarwarehouse.php";
    include_once "../content.php";
    

    include_once "../inc/class.stock.inc.php";
    $stock = new Stock($db);

if(!$stock->access(5))
	{
		echo "<meta http-equiv='refresh' content='0;/index.php'>";
        exit;
	}
?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="home.php">Warehouse</a>
        </li>
        <li class="breadcrumb-item active">Reports</li>
    </ol>
    <h2>Warehouse - Reports</h2>

    <div class="text-center" style="padding-bottom:30px;">
        <form class="form-inline" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="form-group">
                <label>Search by <span class="text-danger">*</span></label>
                <input type="hidden" class="form-control" id="searchBy" name="searchBy" value="1">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_by">Days &nbsp&nbsp <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a onclick="selectBy(1)">Days</a></li>
                        <li><a onclick="selectBy(2)">Months</a></li>
                        <li><a onclick="selectBy(3)">Years</a></li>
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <label for="date">From: <span class="text-danger">*</span></label>
                <div class='input-group date' id='datetimepicker'>
                    <input type='text' class="form-control" name="dateSearch" id="dateSearch" required/>
                    <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label for="date">To: <span class="text-danger">*</span></label>
                <div class='input-group date' id='datetimepicker2'>
                    <input type='text' class="form-control" name="dateSearch2" id="dateSearch2" required/>
                    <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                    </span>
                </div>
            </div><br/>
            <div class="form-group">
                <label for="shift">Report <span class="text-danger">*</span></label>
                <input type="hidden" class="form-control" id="report" name="report" required>
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_report" style="height:30px;">Requests and Issues&nbsp&nbsp<span class='caret'></span></button>
                    <ul class="dropdown-menu">
                        <li><a onclick="selectReport(1)">Requests and Issues</a></li>
                        <li><a onclick="selectReport(2)">Opening and Closing - Materials</a></li>
                        <li><a onclick="selectReport(10)">Opening and Closing - Ink and Solvents</a></li>
                        <li><a onclick="selectReport(9)">Opening and Closing - Master Batch</a></li>
                        <li><a onclick="selectReport(11)">Opening and Closing - Consumable Items</a></li>
                        <li><a onclick="selectReport(3)">Outstanding Raw Material Orders</a></li>
                        <li><a onclick="selectReport(4)">Raw Material Import By Files</a></li>
                        <li><a onclick="selectReport(8)">Raw Material Import By Material</a></li>
                        <li><a onclick="selectReport(5)">Raw Material Local Purchases</a></li>
                        <li><a onclick="selectReport(6)">Raw Material Loans & Returns</a></li>
                        <li><a onclick="selectReport(7)">Balance Stock</a></li>
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" id="buttonForm" class="btn btn-info">Search</button> </div>
        </form>
    </div>


    <button class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" onclick="exportToPDF()">Export to PDF</button>

    <div class="panel panel-info">
        <div class="panel-heading" id="titleReport">Report</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                       
                    <?php
    if( empty($_POST['report']) or $_POST['report'] == 1 )
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th><th>Section</th>';
        echo '<th>Raw Material</th>';
        echo '<th>Qty Required</th>';
        echo '<th>Qty Approved</th>';
        echo '<th>Qty Issued</th>';
        echo '<th>Qty Receipt</th>';
        echo '<th>Required by</th>';
        echo '<th>Approved by</th>';
        echo '<th>Issued by</th>';
        echo '<th>Receipt by</th>';
        echo '<th>Remarks</th>';
        echo '</tr></thead> <tfoot><tr  class="active">
			<th></th>
			<th></th>
			<th>Total</th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th></th><th></th><th></th><th></th>
			<th></th></tr></tfoot>  <tbody>';    
        $stock->RequestAndIssueSlip();
        
        echo '</tbody>';
    }      
	else if($_POST['report'] == 2 )
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Raw Material</th>';
        echo '<th>Opening</th>';
        echo '<th>Import</th>';
        echo '<th>Local Purchases, Loans or Returns</th>';
        echo '<th>Other receipts</th>';
        echo '<th>Issued</th>';
        echo '<th>Balance</th>';
        echo '<th>Closing</th>';
        echo '</tr></thead>
		<tfoot><tr  class="active">
			<th></th>
			<th>Total</th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th></tr></tfoot>
			<tbody>';    
        $stock->OpeningClosing(1);
        
        echo '</tbody>';
    }
	else if($_POST['report'] == 3 )
    {
        echo '<thead><tr  class="active">
			<th>File No.</th>
			<th>Commercial Invoice No.</th>
			<th>Commercial Invoice Date</th>
			<th>Bill Due Date</th>
			<th>Invoice Value</th>
			<th>Material</th>
			<th>Supplier</th>
			<th>Bags/Drumps</th></tr></thead>
			
			<tfoot><tr  class="active">
			<th></th>
			<th></th>
			<th></th>
			<th>Total</th>
			<th style="text-align:right"></th>
			<th></th>
			<th>Total</th>
			<th style="text-align:right"></th></tr></tfoot><tbody>';    
        $stock->OutstandingOrdersReport();
        
        echo '</tbody>';
    }
	else if($_POST['report'] == 4 )
    {
        echo '<thead><tr  class="active">
			<th>File No.</th>
			<th>Material</th>
			<th>Supplier</th>
			<th>Bags/Drumps</th>
			<th>Amount</th>
			<th>Date Cleared to factory</th>
			<th>Declaration No.</th>
			<th>Customs of duty (USD)</th>
			<th>Clearing cost (USD)</th>
			<th>Offloading cost (USD)</th>
			<th>Total (USD)</th>
			<th>Cost by Kg (USD)</th></tr></thead>
			<tfoot><tr  class="active">
			<th></th>
			<th></th>
			<th>Total</th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th></th>
			<th>Total</th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th><th></th></tr></tfoot>
			<tbody>';    
        $stock->ImportsReport();
        
        echo '</tbody>';
    }
	else if($_POST['report'] == 5 )
    {
        echo '<thead><tr  class="active">
			<th>Date Arrived</th>
			<th>Material</th>
			<th>Supplier</th>
			<th>Invoice</th>
			<th>Bags / Drumps</th>
			<th>Amount (GHC)</th>
			<th>Cost by Kg/pcs (GHC)</th>
			<th>Submitted by</th>
			<th>Remarks</th></tr></thead>
			<tfoot><tr  class="active">
			<th></th>
			<th></th>
			<th></th>
			<th>Total</th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th></th>
			<th></th>
			<th></th></tr></tfoot><tbody>';    
        $stock->LocalPurchasesReport();
        
        echo '</tbody>';
    }
	else if($_POST['report'] == 6 )
    {
        echo '<thead><tr  class="active">
			<th>Date Arrived</th>
			<th>Material</th>
			<th>Company</th>
			<th>Invoice</th>
			<th>Bags / Drumps</th>
			<th>Submitted by</th>
			<th>Remarks</th></tr></thead>
			<tfoot><tr class="active">
			<th></th>
			<th></th>
			<th></th>
			<th>Total</th>
			<th style="text-align:right"></th>
			<th></th>
			<th></th></tr></tfoot><tbody>';    
        $stock->LoansReport();
        
        echo '</tbody>';
    }
	else if($_POST['report'] == 7 )
    {
          
        $stock->BalanceStockReport(1);
        
    }
	else if($_POST['report'] == 8 )
    {
        echo '<thead><tr  class="active">
			<th>Material</th>
			<th>Bags/Drumps</th>
			<th>Cost by Kg (USD)</th></tr></thead>
			<tfoot><tr  class="active">
			<th>Total</th>
			<th style="text-align:right"></th>
			<th></th></tr></tfoot>
			<tbody>';    
        $stock->ImportsByMaterialReport();
        
        echo '</tbody>';
    }
	else if($_POST['report'] == 9 )
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Raw Material</th>';
        echo '<th>Opening (Kgs)</th>';
        echo '<th>Import (Kgs)</th>';
        echo '<th>Local Purchases, Loans or Returns (Kgs)</th>';
        echo '<th>Other receipts (Kgs)</th>';
        echo '<th>Issued (Kgs)</th>';
        echo '<th>Balance (Kgs)</th>';
        echo '<th>Closing (Kgs)</th>';
        echo '</tr></thead>
		<tfoot><tr  class="active">
			<th></th>
			<th>Total</th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			</tr></tfoot>
			<tbody>';    
        $stock->OpeningClosing(2);
        
        echo '</tbody>';
    }
					else if($_POST['report'] == 10 )
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Ink / Solvent</th>';
        echo '<th>Opening</th>';
        echo '<th>Import</th>';
        echo '<th>Local Purchases, Loans or Returns </th>';
        echo '<th>Other receipts</th>';
        echo '<th>Issued</th>';
        echo '<th>Balance</th>';
        echo '<th>Closing</th>';
        echo '</tr></thead>
		<tfoot><tr  class="active">
			<th></th>
			<th>Total</th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			</tr></tfoot>
			<tbody>';    
        $stock->OpeningClosing(3);
        
        echo '</tbody>';
    }
					else if($_POST['report'] == 11 )
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Consumable Items</th>';
        echo '<th>Opening</th>';
        echo '<th>Import</th>';
        echo '<th>Local Purchases, Loans or Returns </th>';
        echo '<th>Other receipts</th>';
        echo '<th>Issued</th>';
        echo '<th>Balance</th>';
        echo '<th>Closing</th>';
        echo '</tr></thead>
		<tfoot><tr  class="active">
			<th></th>
			<th>Total</th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			</tr></tfoot>
			<tbody>';    
        $stock->OpeningClosing(4);
        
        echo '</tbody>';
    }
?>

                </table>
            </div>
        </div>
    </div>


    
        <script src='../assets/pdfmake/pdfmake.min.js'></script>
        <script src='../assets/pdfmake/vfs_fonts.js'></script>
        <script>
			function selectBy(id) {
            document.getElementById("searchBy").value = id;
            var name = "";
            if(id == 1)
            {
                $('#datetimepicker').datetimepicker().data('DateTimePicker').format('DD/MM/YYYY');
                $('#datetimepicker2').datetimepicker().data('DateTimePicker').format('DD/MM/YYYY');
                $('#datetimepicker').datetimepicker().data('DateTimePicker').viewMode('days');
                $('#datetimepicker2').datetimepicker().data('DateTimePicker').viewMode('days');
                name = "Days";
            }
            else if(id == 2)
            {
                $('#datetimepicker').datetimepicker().data('DateTimePicker').format('MMM/YYYY');
                $('#datetimepicker2').datetimepicker().data('DateTimePicker').format('MMM/YYYY'); 
                $('#datetimepicker').datetimepicker().data('DateTimePicker').viewMode('months');
                $('#datetimepicker2').datetimepicker().data('DateTimePicker').viewMode('months'); 
                name = "Months";
            }
            else 
            {
                $('#datetimepicker').datetimepicker().data('DateTimePicker').format('YYYY'); 
                $('#datetimepicker2').datetimepicker().data('DateTimePicker').format('YYYY'); 
                $('#datetimepicker').datetimepicker().data('DateTimePicker').viewMode('years');
                $('#datetimepicker2').datetimepicker().data('DateTimePicker').viewMode('years');
                name = "Years";
            }
            document.getElementById("btn_by").innerHTML = name + "  &nbsp&nbsp<span class='caret'></span> ";
        }
            function selectReport(id) {
                document.getElementById("report").value = id;
				var name = "";
				if(id ==1)
				{
					name = "Requests and Issues";
				}
				else if (id == 2)
				{
					name = "Opening and Closing - Materials";
				}
				else if (id == 3)
				{
					name = "Outstanding Raw Material Orders";
				}
				else if (id == 4)
				{
					name = "Raw Material Import by Files";
				}
				else if (id == 5)
				{
					name = "Raw Material Local Purchases";
				}
				else if (id == 6)
				{
					name = "Raw Material Loans or Returns ";
				}
				else if (id == 7)
				{
					name = "Balance Stock";
				}
				else if (id == 8)
				{
					name = "Raw Material Import by Material";
				}
				else if (id == 9)
				{
					name = "Opening and Closing - Master Batch";
				}
				else if (id == 10)
				{
					name = "Opening and Closing - Ink and Solvents";
				}
				else if (id == 11)
				{
					name = "Opening and Closing - Consumable Items";
				}
				document.getElementById("btn_report").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
            }

            function exportToPDF() {
                var table = document.getElementById("dataTable");
                var bdy = [];
                for (var y = 0; y < table.rows.length; y++) {
                    bdy[y] = [];
                    for (var x = 0; x < table.rows[y].cells.length; x++) {
                        if (y == 0 || y == table.rows.length-1) {
                            bdy[y][x] = {
                                text: table.rows[y].cells[x].innerHTML,
                                style: 'tableHeader'
                            };
                        } else {
							bdy[y][x] = {
								text: table.rows[y].cells[x].innerHTML,
								style: 'text'
							};
							if (table.rows[y].cells[x].innerHTML.includes("<br>")) {
								bdy[y][x] = {
									text: table.rows[y].cells[x].innerHTML.replace("<br>", "\n"),
									style: 'text'
								};
							}
							if (document.getElementById("titleReport").innerHTML.split("&nbsp;")[0].includes("Outstanding") && (x== 4 || x==7)) {
								bdy[y][x] = {
									text: table.rows[y].cells[x].innerHTML,
									style: 'number'
								};
							}
							if (document.getElementById("titleReport").innerHTML.split("&nbsp;")[0].includes("Request") && (x== 3 || x==4 || x==5 || x==6)) {
								bdy[y][x] = {
									text: table.rows[y].cells[x].innerHTML,
									style: 'number'
								};
							}
							if (document.getElementById("titleReport").innerHTML.split("&nbsp;")[0].includes("Opening") && (x== 2 ||x== 3 || x==4 || x==5 || x==6|| x==7|| x==8)) {
								bdy[y][x] = {
									text: table.rows[y].cells[x].innerHTML,
									style: 'number'
								};
							}
							if (document.getElementById("titleReport").innerHTML.split("&nbsp;")[0].includes("Import by Material") && (x== 1 || x==2)) {
								bdy[y][x] = {
									text: table.rows[y].cells[x].innerHTML,
									style: 'number'
								};
							}
                        }
                    }
                }

                var dd = {
                    pageOrientation: 'landscape',
                    content: [{
                            text: document.getElementById("titleReport").innerHTML.split("&nbsp;")[0],
                            style: 'header'
                        },
                        {
                            text: document.getElementById("titleReport").innerHTML.split("&nbsp;")[2],
                            style: 'header'
                        },
                        {
                            text: 'Generated by United Production System\n\n',
                            style: 'quote'
                        },
                        {
                            style: 'tableExample',
                            color: '#444',
                            table: {
                                headerRows: 1,
                                body: bdy
                            },
                            layout: {
                                hLineWidth: function(i, node) {
                                    return 1;
                                },
                                vLineWidth: function(i, node) {
                                    return 1;
                                },
                                hLineColor: function(i, node) {
                                    return '#bce8f1';
                                },
                                vLineColor: function(i, node) {
                                    return '#bce8f1';
                                },
                                fillColor: function(i, node) {
                                    return (i === 0 || i === node.table.body.length-1) ? '#d9edf7' : null;
                                }

                            }
                        },
                    ],
                    styles: {
                        header: {
                            fontSize: 18,
                            bold: true,
                            alignment: 'center',
                            color: '#31708f'
                        },
                        quote: {
                            italics: true,
                            alignment: 'right',
                            color: '#31708f'
                        },
						tableHeader: {
							bold: true,
							fontSize: 11,
							color: '#31708f',
							alignment: 'center'
						},
						text: {
							fontSize: 10
						},
						number: {
							fontSize: 10,
                            alignment: 'right'
						}
                    }};

                    pdfMake.createPdf(dd).open();
                    pdfMake.createPdf(dd).download(document.getElementById("titleReport").innerHTML.split("&nbsp;")[0]+document.getElementById("titleReport").innerHTML.split("&nbsp;")[2] + '.pdf');
                }  
        </script>
        <script>
            $(function () {
                $('#datetimepicker').datetimepicker({ 
                    format: 'DD/MM/YYYY',
 <?php            
  if(!empty($_POST['dateSearch']) and !empty($_POST['searchBy']))
  {
      if($_POST['searchBy'] == 1)
      {
        $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch']);
        $newDateString = $myDateTime->format('m/d/Y');
      }
      else if($_POST['searchBy'] == 2)
      {
        $myDateTime = DateTime::createFromFormat('M/Y', $_POST['dateSearch']);
        $myDateTime->modify('first day of this month');
        $newDateString = $myDateTime->format('m/d/Y');
      }
      else
      {
         $myDateTime = DateTime::createFromFormat('Y', $_POST['dateSearch']);
         $myDateTime->modify('first day of January ' . $_POST['dateSearch']);
         $newDateString = $myDateTime->format('m/d/Y');
      }
    echo "defaultDate : '". $newDateString ."'";
  } 
  else
  {
      echo "defaultDate : moment()";
  }
?>  
                });
                $('#datetimepicker2').datetimepicker({      
                    format: 'DD/MM/YYYY',
                    useCurrent: false, //Important! See issue #1075
 <?php            
  if(!empty($_POST['dateSearch2']) and !empty($_POST['searchBy']))
  {
      if($_POST['searchBy'] == 1)
      {
        $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch2']);
        $newDateString = $myDateTime->format('m/d/Y');
      }
      else if($_POST['searchBy'] == 2)
      {
        $myDateTime = DateTime::createFromFormat('M/Y', $_POST['dateSearch2']);
        $myDateTime->modify('last day of this month');
        $newDateString = $myDateTime->format('m/d/Y');
      }
      else
      {
         $myDateTime = DateTime::createFromFormat('Y', $_POST['dateSearch2']);
         $myDateTime->modify('last day of December ' . $_POST['dateSearch2']);
         $newDateString = $myDateTime->format('m/d/Y');
      }
    echo "defaultDate : '". $newDateString ."'";
  } 
  else
  {
      echo "defaultDate : moment()";
  }
?>  
    
                });
                $("#datetimepicker").on("dp.change", function (e) {
                    $('#datetimepicker2').data("DateTimePicker").minDate(e.date);
                });
                $("#datetimepicker2").on("dp.change", function (e) {
                    $('#datetimepicker').data("DateTimePicker").maxDate(e.date);
                });
                $('#datetimepicker').data("DateTimePicker").maxDate(new Date());
                $('#datetimepicker2').data("DateTimePicker").maxDate(new Date());
<?php
  if(!empty($_POST['report']) )
  {
       echo 'selectReport('.$_POST['report'] .');';
  }
  else
  {
      echo 'selectReport(1);';
  }
  if(!empty($_POST['searchBy']) )
  {
       echo 'selectBy('.$_POST['searchBy'] .');';
  }
  else
  {
      echo 'selectBy(1);';
  } 
  echo 'document.getElementById("titleReport").innerHTML = document.getElementById("btn_report").innerHTML.split("<")[0] + " From:" + document.getElementById("dateSearch").value + " To " + document.getElementById("dateSearch2").value;';
?>		
                $('#dataTable').DataTable( {
                "order": [],
				"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
<?php
				if(!empty($_POST['report']) && $_POST['report'] ==3)
				{ 
					echo ", \"footerCallback\": function(row, data, start, end, display) {
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
						'$'+ pageTotal4.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
					// Total Bags
					pageTotal7 = api
						.column(7, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(7).footer()).html(
						'' + pageTotal7.toLocaleString()
					);
					
					}";
				}
				else if(!empty($_POST['report']) && $_POST['report'] ==5)
				{ 
					echo ", \"footerCallback\": function(row, data, start, end, display) {
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
						''+ pageTotal4.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					// Total 5
					pageTotal5 = api
						.column(5, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(5).footer()).html(
						'$' + pageTotal5.toLocaleString()
					);
					
					}";
				}
				else if(!empty($_POST['report']) && $_POST['report'] ==6)
				{ 
					echo ", \"footerCallback\": function(row, data, start, end, display) {
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
						''+ pageTotal4.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
					}";
				}
					else if(!empty($_POST['report']) && $_POST['report'] ==7)
				{ 
					echo ", \"footerCallback\": function(row, data, start, end, display) {
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
					
					}";
				}	
						else if(!empty($_POST['report']) && $_POST['report'] ==8)
				{ 
					echo ", \"footerCallback\": function(row, data, start, end, display) {
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
					
					}";
				}
				else if(!empty($_POST['report']) && $_POST['report'] ==1)
				{ 
					echo ", \"footerCallback\": function(row, data, start, end, display) {
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
					
					// Total 5
					pageTotal5 = api
						.column(5, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(5).footer()).html(
						'' + pageTotal5.toLocaleString()
					);
					
					// Total 4
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
					// Total 6
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
					
					
					}";
				}
				else if(!empty($_POST['report']) && ($_POST['report'] ==2 or $_POST['report'] ==9 or $_POST['report'] ==10 or $_POST['report'] ==11))
				{ 
					echo ", \"footerCallback\": function(row, data, start, end, display) {
					var api = this.api(),
						data;

					// Remove the formatting to get integer data for summation
					var intVal = function(i) {
						return typeof i === 'string' ?
							i.replace(/[\$,]/g, '') * 1 :
							typeof i === 'number' ?
							i : 0;
					};

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
					
					// Total 5
					pageTotal5 = api
						.column(5, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(5).footer()).html(
						'' + pageTotal5.toLocaleString()
					);
					
					// Total 4
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
					// Total 6
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
					
					pageTotal7 = api
						.column(7, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(7).footer()).html(
						'' + pageTotal7.toLocaleString()
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
					}";
					
				}
				else if(!empty($_POST['report']) && $_POST['report'] ==4 )
				{ 
					echo ", \"footerCallback\": function(row, data, start, end, display) {
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
						''+ pageTotal3.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
					
					// Total 4
					pageTotal4 = api
						.column(4, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(4).footer()).html(
						'$' + pageTotal4.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					pageTotal7 = api
						.column(7, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(7).footer()).html(
						'$' + pageTotal7.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
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
						'$' + pageTotal8.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					pageTotal9 = api
						.column(9, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(9).footer()).html(
						'$' + pageTotal9.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					pageTotal10 = api
						.column(10, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(10).footer()).html(
						'$' + pageTotal10.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
					
					}";
				}
?>
            } );

                
			})
        </script>

        <?php
    include_once '../footer.php';
?>