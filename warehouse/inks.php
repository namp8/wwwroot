<?php
    $pageTitle = "Warehouse - Inks and Solvents";
    include_once "../base.php";
    include_once "../header.php";
    include_once "sidebarwarehouse.php";
    include_once "../content.php";


    include_once "../inc/class.materials.inc.php";
    $materials = new Materials($db);

    include_once "../inc/class.stock.inc.php";
    $stock = new Stock($db);

if(!$stock->access(4))
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
        <li class="breadcrumb-item active">Inks and Solvents</li>
    </ol>
    <h2>Warehouse - Inks and Solvents</h2>

<div id="alertMessage" class="alert hide" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>

<?php
    if($_SERVER["REQUEST_METHOD"] == "POST" and !empty($_POST['material']))
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($stock->balanceStockMaterials(1)){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
	else if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		echo '<strong>ERROR</strong> The material is a required field. Please try again, selecting a material from the dropdown.<br>';
		echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
		
	}
?>
	</div>


    <button class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" onclick="exportToPDF()">Export to PDF</button>
<?php
	if($stock->administrators())
	{
		echo '<button class="btn btn-info pull-right" style="margin-top:5px;margin-right:15px;" data-toggle="modal" data-target="#modal1">Balance Stock</button>';
	}
?>
    <div class="panel panel-info">
        <div class="panel-heading"> List of Inks and Solvents </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                            <th>Item</th>
                            <th>Color / Solvent</th>
                            <th>Drumps</th>
                            <th>Kgs</th>
                        </tr>
                    </thead>
					<tfoot>
						<tr class="active">
							<th></th>
							<th>Total</th>
							<th style="text-align:right"></th>
							<th style="text-align:right"></th>
						</tr>
					</tfoot>
                    <tbody>
<?php
    $stock->stockInksSolvents(1);
?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Modal-->
    <div class="modal fade" id="modal1" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-m">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal">x</button>
            <h4 class="modal-title">Balance Warehouse Stock</h4>
          </div>
            <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                
          <div class="modal-body">
                <input type="hidden" class="form-control" id="id_transfer" name="id_transfer" required>
                <div class="form-group">
                    <label for="date">Date</label>
                    <div class='input-group date' id='datetimepicker'>
                        <input type='text' class="form-control" id="date" name="date"/>
                        <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="material">Inks and Solvents  <span class="text-danger">*</span></label>
					<input type="hidden" class="form-control" id="material" name="material" required><br>
					<div class="btn-group">
						<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_material">&nbsp&nbsp<span class="caret"></span></button>
						<ul class="dropdown-menu" role="menu" id="dropdown_material">
							<li><input type="text" placeholder="Search material.." class="searchDropdown" id="searchMaterial" onkeyup="filterMaterials()" width="100%"></li>
							<?php
$materials->inksStockDropdown(1);
?>
						</ul>
					</div>
                </div>
                <div class="form-group">
                    <label for="oldbags">Bags/Drumps on UPS<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" min="1" id="oldbags" name="oldbags" readonly="readonly">
                </div>
                <div class="form-group">
                    <label for="newbags">Bags/Drumps on floor<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" min="1" step="0.001" id="newbags" name="newbags" onkeyup="calculate()" required>
                </div>
			  	<div class="form-group">
                    <label for="difference">Variance<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" min="1" id="difference" name="difference" readonly="readonly">
                </div>
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <input type="text" class="form-control" id="remarks" name="remarks">
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


        <script src='../assets/pdfmake/pdfmake.min.js'></script>
        <script src='../assets/pdfmake/vfs_fonts.js'></script>
	<script>
		
		function calculate() {
            var oldbags = document.getElementById("oldbags").value;
            var newbags = document.getElementById("newbags").value;
		   	var difference = newbags - oldbags;
            document.getElementById("difference").value = difference.toFixed(2);
        }
		
		function selectMaterial(id, name, grade, bags) {
			document.getElementById("btn_material").innerHTML = name + " - " + grade + " &nbsp&nbsp<span class='caret'></span> ";
			document.getElementById("material").value = id;
			document.getElementById("oldbags").value = bags;
			calculate();
		}

		function filterMaterials() {
			var input, filter, ul, li, a, i;
			input = document.getElementById("searchMaterial");
			filter = input.value.toUpperCase();
			div = document.getElementById("dropdown_material");
			a = div.getElementsByTagName("a");
			for (i = 0; i < a.length; i++) {
				if (a[i].id.toUpperCase().includes(filter)) {
					a[i].style.display = "";
				} else {
					a[i].style.display = "none";
				}
			}
		}
		
		$(document).ready(function() {
			$('#datetimepicker').datetimepicker({
				format: 'DD/MM/YYYY',
				defaultDate : moment()
			});
			$("#dataTable").DataTable({
				"order": [],
				"lengthMenu": [[-1, 10, 25, 50, 100], ["All", 10, 25, 50, 100]],
				"footerCallback": function(row, data, start, end, display) {
					var api = this.api(),
						data;

					// Remove the formatting to get integer data for summation
					var intVal = function(i) {
						return typeof i === 'string' ?
							i.replace(/[\,]/g, '') * 1 :
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
						'' + pageTotal2.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
					pageTotal3 = api
						.column(3, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(3).footer()).html(
						'' + pageTotal3.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
				
					
				}
			});
		});
		
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
                            bdy[y][x] = table.rows[y].cells[x].innerHTML;
                            if(table.rows[y].cells[x].innerHTML.includes("<br>"))
                            {
                                bdy[y][x] = table.rows[y].cells[x].innerHTML.replace("<br>","\n");
                            }
							if (x== 2 || x==3) {
								bdy[y][x] = {
									text: table.rows[y].cells[x].innerHTML,
									style: 'number'
								};
							}
                        }
                    }
                }

                var dd = {
                    content: [{
                            text: 'List of Inks and Solvents in Warehouse stock',
                            style: 'header'
                        },
                        {
                            text:  new Date().toLocaleString(),
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
                            fontSize: 13,
                            color: '#31708f',
                            alignment: 'center'
                        },
						number: {
                            alignment: 'right'
						}
                    }};

                    pdfMake.createPdf(dd).open();
                    pdfMake.createPdf(dd).download("List of Inks and Solvents in Warehouse stock "+new Date().toLocaleString() +'.pdf');
                }  
        </script>
    <?php
    include_once '../footer.php';
?>