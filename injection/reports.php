<?php
    $pageTitle = "Injection - Reports";
    
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
        <li class="breadcrumb-item active">Reports</li>
    </ol>
    <h2>Injection - Reports</h2>


    <div class="text-center" id="form">
        <form class="form-inline" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="form-group">
                <label>Search by</label>
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
                <label for="date">From:</label>
                <div class='input-group date' id='datetimepicker'>
                    <input type='text' class="form-control" name="dateSearch" id="dateSearch" />
                    <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label for="date">To:</label>
                <div class='input-group date' id='datetimepicker2'>
                    <input type='text' class="form-control" name="dateSearch2" id="dateSearch2" />
                    <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                    </span>
                </div>
            </div><br/>
            <div class="form-group">
                <label >Report</label>
                <input type="hidden" class="form-control" id="report" name="report" value="1">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_report">Efficiency Report&nbsp&nbsp<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a onclick="selectReport(1)">Injection - Efficiency Report</a></li>
                        <li><a onclick="selectReport(2)">Injection - Production Report</a></li>
                        <li><a onclick="selectReport(3)">Injection - Waste Report</a></li>
                        <li><a onclick="selectReport(4)">Injection - Raw Material Consumption Report</a></li>
                        <li><a onclick="selectReport(5)">Injection - Short Fall and Downtime Report</a></li>
                        <li><a onclick="selectReport(6)">Injection - Raw Material Stock Details</a></li>
                        <li><a onclick="selectReport(7)">Injection - Rolls on Floor Details</a></li>
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" id="buttonForm" class="btn btn-info">Search</button> </div>
        </form>
    </div>

    <div class="row " style="padding-top:30px;" id="panelReport">
         <button class="btn btn-info pull-right" style="margin-top:5px;margin-right:30px;" onclick="exportToPDF()">Export Table to PDF</button>
            <div class="panel panel-info">
                <div class="panel-heading" id="titleReport"> </div>
                <div class="panel-body">
                    <div class="col-12" id="divChart1">
                        <i aria-hidden="true" id="iconChart1"></i>
                        <div id="chartContainer">

                        </div>
                    </div>
                    <div class="col-12">
                        <i aria-hidden="true" id="iconChart2"></i>
                        <div id="chartContainer2" >

                        </div>
                    </div>  
                        <div class="table-responsive">
                        
                            
                            
 <?php
    if( empty($_POST['report']) or $_POST['report'] == 1 )
    {
        echo '<table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0"  >';
		$injection->reportEfficiency();        
		echo '</table>';
    }     
    else if( empty($_POST['report']) or $_POST['report'] == 2 )
    {
        echo '<table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0"  >';
        $injection->reportProduction();
		echo '</table>';
    }  
    else if( empty($_POST['report']) or $_POST['report'] == 3 )
    {
        echo '<table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0"  >';
        $injection->reportWaste();
		echo '</table>';
    }  
    else if( empty($_POST['report']) or $_POST['report'] == 4 )
    {
        $injection->reportMaterialConsumption();
    }
	 else if( empty($_POST['report']) or $_POST['report'] == 5 )
    {
        echo '<table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0"  >';
        $injection->reportReason();
		echo '</table>';
    } 
	else if( empty($_POST['report']) or $_POST['report'] == 6 )
    {
        echo '<table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0"  >';
        $injection->reportRawMaterial();
		echo '</table>';
    }
	else if( empty($_POST['report']) or $_POST['report'] == 7 )
    {
        echo '<table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0"  >';
        $injection->reportRollsInjection();
		echo '</table>';
    }
?>
                
                         
                </div>
            </div>
        </div>


    <script>
        
        function selectReport(id) {
            document.getElementById("report").value = id;
            var name = "";
            if(id ==1)
            {
                name = "Injection - Efficiency Report";
            }
            else if (id == 2)
            {
                name = "Injection - Production Report";
            }
            else if (id == 3)
            {
                name = "Injection - Waste Report";
            }
            else if (id == 4)
            {
                name = "Injection - Raw Material Consumption Report";
            }
			else if (id == 5)
            {
                name = "Injection - Short Fall and Downtime Report";
            }
            else if (id == 6)
            {
                name = "Injection - Raw Material Stock Details";
            }
            else if (id == 7)
            {
                name = "Injection - Rolls on Floor Details";
            }
            document.getElementById("btn_report").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
        }
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
                    useCurrent: false, 
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
                $('#dataTable').DataTable( {
                "order": [],
				"lengthMenu": [[-1, 10, 25, 50, 100], ["All", 10, 25, 50, 100]]
<?php
				if(!empty($_POST['report']) && $_POST['report'] == 1)
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
					
					
					
					
					// TOTAL 2
					pageTotal2 = api
						.column(2, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(2).footer()).html(
						''+ pageTotal2.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
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
						''+ pageTotal3.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
					// TOTAL 4
					pageTotal4 = api
						.column(4, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(4).footer()).html(
						
						 pageTotal4.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
}) 
					);
					
					// Total 5
					pageTotal5 = pageTotal4/pageTotal3*100;
					$(api.column(5).footer()).html(
						'' + pageTotal5.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})+'%'
					);
					
					// TOTAL 8
					pageTotal6 = api
						.column(6, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(6).footer()).html(
						''+ pageTotal6.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
					// Total 6
					var columnData = api
						.column( 7 )
						.data();

					var theColumnTotal = columnData
						.reduce( function (a, b) {
							if(isNaN(a)){
								return '';
							} else {
								a = parseFloat(a);
							}
							if(isNaN(b)){
								return '';
							} else {
								b = parseFloat(b);
							}
							return (a + b).toFixed(2);
						}, 0 );
					pageTotal7 = theColumnTotal / columnData.count();
					$(api.column(7).footer()).html(
						 pageTotal7.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})+ '%'
					);
					
					// Total Bags
					pageTotal8 = pageTotal6/pageTotal4*100;
					$(api.column(8).footer()).html(
						 pageTotal8.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})+ '%'
					);
					
					
					
					}";
				}
				else if(!empty($_POST['report']) && $_POST['report'] == 2)
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

					// TOTAL 2
					pageTotal2 = api
						.column(2, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(2).footer()).html(
						''+ pageTotal2.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
					// TOTAL 2
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
					
					}";
				}
				else if(!empty($_POST['report']) && $_POST['report'] == 3)
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
					// TOTAL 1
					pageTotal2 = api
						.column(2, {
							page: 'current'
						})
						.data()
						.reduce(function(a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					$(api.column(2).footer()).html(
						''+ pageTotal2.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
					}";
				}
				else if(!empty($_POST['report']) && $_POST['report'] == 5)
				{ 
					echo ", \"footerCallback\": function(row, data, start, end, display) {
					
					var api = this.api(), data;
					pageTotal_Duration = api.column(2, { page: 'current'} ).data().reduce( function (a, b) {
						return moment.duration(a).asMilliseconds() + moment.duration(b).asMilliseconds();
					}, 0 );
					var days = moment.utc(pageTotal_Duration).format(\"DDD\") - 1;
					$( api.column(3).footer()).html(
					    days + ' days and ' + moment.utc(pageTotal_Duration).format(\"H\")  + ' hours and ' + moment.utc(pageTotal_Duration).format(\"m\")  + ' minutes.' 
					);
					
					}";
				}
					else if(!empty($_POST['report']) && ($_POST['report'] == 6 or $_POST['report'] == 7))
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
						''+ pageTotal2.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
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
						'' + pageTotal4.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})
					);
					
					
					}";
				}
						
?>
					
            	} );
                
                  
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
                
			})
    </script>
        
    <script src='../assets/pdfmake/pdfmake.min.js'></script>
    <script src='../assets/pdfmake/vfs_fonts.js'></script>
        
    <script>
     function exportToPDF() {
		 		if(!document.getElementById("titleReport").innerHTML.split("&nbsp;")[0].includes("Consumption"))
				{
				   var table = document.getElementById("dataTable");
					var bdy = [];
					for (var y = 0; y < table.rows.length; y++) {
						bdy[y] = [];
						for (var x = 0; x < table.rows[y].cells.length; x++) {
							if (y == 0 || y == table.rows.length-1) {
								bdy[y][x] = {
									text: table.rows[y].cells[x].innerHTML.replace("<br>","\n"),
									style: 'tableHeader'
								};
							} else {
								bdy[y][x] = table.rows[y].cells[x].innerHTML;
								if (!document.getElementById("titleReport").innerHTML.split("&nbsp;")[0].includes("Fall") && (x!=0)) {
									bdy[y][x] = {
										text: table.rows[y].cells[x].innerHTML,
										style: 'number'
									};
								}
							}

						}
					}
					tbl =	{
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
                        };
				}
		 		else
				{
					var tbl = [];
					for(var i = 0; i < document.getElementsByTagName("table").length; i++)
					{
						var table = document.getElementsByTagName("table")[i];
						var bdy = [];
						for (var y = 0; y < table.rows.length; y++) {
							bdy[y] = [];
							for (var x = 0; x < table.rows[y].cells.length; x++) {
								if (y == 0 || y == table.rows.length-2) {
									bdy[y][x] = {
										text: table.rows[y].cells[x].innerHTML.replace("<br>","\n"),
										style: 'tableHeader'
									};
								} else {
									bdy[y][x] = table.rows[y].cells[x].innerHTML;
									if (!document.getElementById("titleReport").innerHTML.split("&nbsp;")[0].includes("Fall") && (x!=0)) {
										bdy[y][x] = {
											text: table.rows[y].cells[x].innerHTML,
											style: 'number'
										};
									}
								}

							}
						}
						var tab =	{
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
                                    return (i === 0 || i === node.table.body.length-2) ? '#d9edf7' : null;
                                }

                            }
                        };
						var space = 
                        {
                            text: '\n Formula #' + (i+1) + '.\n',
                            style: 'quote'
                        };
						tbl.push(space);
						tbl.push(tab);
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
                        tbl
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
                            alignment: 'center',
                            color: '#31708f'
                        },
						number: {
                            alignment: 'right'
						}
                    }};

                    pdfMake.createPdf(dd).open();
		 			pdfMake.createPdf(dd).download(document.getElementById("titleReport").innerHTML.split("&nbsp;")[0]+document.getElementById("titleReport").innerHTML.split("&nbsp;")[2] + '.pdf');
                }    
    </script>

    <?php
    include_once '../footer.php';
?>