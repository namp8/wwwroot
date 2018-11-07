<?php
    include_once "../../inc/class.users.inc.php";
    $users = new Users($db);
?>


<!-- href links different in integration, collaspe in class added server side in integration --> 
		<!-- sidebar --> 
		<div id="sidebar" class="sidebar-toggle">
			<ul class="nav nav-sidebar">
				
						<li>
							<a href="../process.php"><i class="fa fa-recycle" aria-hidden="true"></i><span>PROCESS</span></a>
						</li><li role="separator" class="divider"></li>
                    <!-- orders -->
<!--
					<li data-toggle="collapse" href="#orders_submenu" aria-expanded="true" aria-controls="orders_submenu">
						<a href="#"> 
							<i class="fa fa-pencil-square-o fa-fw" aria-hidden="true"></i><span>ORDERS</span>
						</a>
					</li>
                    <li>
						<ul id="orders_submenu" class="sub-menu collapse">
							<li ><a  href="../orders/customers.php"><i class="fa fa-group" aria-hidden="true" ></i><span>&nbsp Customers</span></a></li>
							<li ><a  href="../orders/orders.php"><i class="fa fa-file-archive-o" aria-hidden="true" ></i><span>&nbsp Sales Orders</span></a></li>
                        </ul>
					</li>
                
                
					<li role="separator" class="divider"></li>
-->
                
				  <?php
	if($users->access('multilayer'))
	{
		echo '<!-- multilayer -->
					<li data-toggle="collapse" href="#multilayer_submenu" aria-expanded="true" aria-controls="multilayer_submenu">
						<a href="#"> 
							<i class="fa fa-sitemap fa-fw" aria-hidden="true"></i><span>MULTILAYER</span>
						</a>
					</li>

					<li>
						<ul id="multilayer_submenu" class="sub-menu collapse">
							<li ><a  href="../multilayer/process.php"><i class="fa fa-recycle" aria-hidden="true" ></i><span>&nbsp Process</span></a></li>
							<li><a href="../multilayer/settings.php"><i class="fa fa-cogs" aria-hidden="true"></i><span>&nbsp Settings</span></a></li>
							<li><a href="../multilayer/reports.php"><i class="fa fa-bar-chart" aria-hidden="true"></i><span>&nbsp Reports</span></a></li>
							<li><a href="../multilayer/formula.php"><i class="fa fa-eyedropper" aria-hidden="true"></i><span>&nbsp Formula</span></a></li>
                            <li class="divider"></li><center>Raw Material</center>
							<li><a class="two" href="../multilayer/request.php"><i class="fa fa-reply " aria-hidden="true" ></i><span>&nbsp Request</span></a></li>
							<li><a class="two" href="../multilayer/receive.php"><i class="fa fa-share" aria-hidden="true"></i><span>&nbsp Receive</span></a></li>
							<li><a class="two" href="../multilayer/stock.php"><i class="fa fa-cubes" aria-hidden="true"></i><span>&nbsp Raw material</span></a></li>
                                                      
                            <li class="divider"></li><center>Production</center>
                            <li><a class="four" href="../multilayer/rolls.php"><i class="fa fa-battery-empty" aria-hidden="true"></i><span>&nbsp Rolls</span></a></li>
                            <li><a class="four" href="../multilayer/rolls_stock.php"><i class="fa fa-cubes" aria-hidden="true"></i><span>&nbsp Rolls on floor </span></a></li>
                            <li><a class="four" href="../multilayer/rolls_stock.php"><i class="fa fa-exchange" aria-hidden="true"></i><span>&nbsp Rolls Tracker </span></a></li>
                            <li><a class="five" href="../multilayer/waste.php"><i class="fa fa-trash-o" aria-hidden="true"></i><span>&nbsp Waste</span></a></li>
                            <li class="divider"></li>
                            <li><a class="one" href="../multilayer/remarks.php"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i><span>&nbsp Short Fall</span></a></li>
                        </ul>
					</li>
					<!-- /multilayer -->

					<li role="separator" class="divider"></li>';
	}
	if($users->access('printing'))
	{
		echo '<!-- printing -->
					<li data-toggle="collapse" href="#printing_submenu" aria-expanded="true" aria-controls="printing_submenu">
						<a href="#"> 
							<i class="fa fa-paint-brush fa-fw" aria-hidden="true"></i><span>PRINTING</span>
						</a>
					</li>
                
                    <li>
						<ul id="printing_submenu" class="sub-menu collapse">
							<li ><a  href="../printing/process.php"><i class="fa fa-recycle" aria-hidden="true" ></i><span>&nbsp Process</span></a></li>
                            <li><a href="../printing/reports.php"><i class="fa fa-bar-chart" aria-hidden="true"></i><span>&nbsp Reports</span></a></li>
							<li ><a  href="../printing/plan.php"><i class="fa fa-recycle" aria-hidden="true" ></i><span>&nbsp Production Plan</span></a></li>
							<li><a href="../printing/formula.php"><i class="fa fa-eyedropper" aria-hidden="true"></i><span>&nbsp Formula</span></a></li>
                            <li><a href="../printing/customers.php"><i class="fa fa-eyedropper" aria-hidden="true"></i><span>&nbsp Customers</span></a></li>
							
                            <li class="divider"></li><center>Ink and Solvent</center>
							<li><a class="two" href="../printing/request.php"><i class="fa fa-reply " aria-hidden="true" ></i><span>&nbsp Request</span></a></li>
							<li><a class="two" href="../printing/receive.php"><i class="fa fa-share" aria-hidden="true"></i><span>&nbsp Receive</span></a></li>
							<li><a class="two" href="../printing/stock.php"><i class="fa fa-cubes" aria-hidden="true"></i><span>&nbsp Stock</span></a></li>
                            
                            <li class="divider"></li><center>Multilayer</center>
                            <li><a class="three" href="../multilayer/rolls_stock.php"><i class="fa fa-cubes" aria-hidden="true"></i><span>&nbsp Multilayer rolls </span></a></li>
                            
                            <li class="divider"></li><center>Production</center>
                            <li><a class="four" href="../printing/rolls.php"><i class="fa fa-battery-empty" aria-hidden="true"></i><span>&nbsp Rolls</span></a></li>
                            <li><a class="four" href="../printing/rolls_stock.php"><i class="fa fa-cubes" aria-hidden="true"></i><span>&nbsp Printed rolls </span></a></li>
                            <li><a class="four" href="#"><i class="fa fa-exchange" aria-hidden="true"></i><span>&nbsp Rolls Tracker </span></a></li>
                        
                            <li class="divider"></li>
                            <li><a class="one" href="../printing/remarks.php"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i><span>&nbsp Short Fall</span></a></li>
						</ul>
					</li>
					<!-- /printing -->
                
					<li role="separator" class="divider"></li>';
	}
	if($users->access('slitting'))
	{
		echo '
					<li data-toggle="collapse" href="#slitting_submenu" aria-expanded="true" aria-controls="slitting_submenu">
						<a href="#"> 
							<i class="fa fa-battery-empty  fa-fw" aria-hidden="true"></i><span>SLITTING</span>
						</a>
					</li>
                
                    <li>
						<ul id="slitting_submenu" class="sub-menu collapse">
							<li ><a  href="../slitting/process.php"><i class="fa fa-recycle" aria-hidden="true" ></i><span>&nbsp Process</span></a></li>
							<li><a href="../slitting/reports.php"><i class="fa fa-bar-chart" aria-hidden="true"></i><span>&nbsp Reports</span></a></li>
                            
                            <li class="divider"></li><center>Consumable Items</center>
							<li><a class="two" href="../slitting/request.php"><i class="fa fa-reply " aria-hidden="true" ></i><span>&nbsp Request</span></a></li>
							<li><a class="two" href="../slitting/receive.php"><i class="fa fa-share" aria-hidden="true"></i><span>&nbsp Receive</span></a></li>
                            
                            <li class="divider"></li><center>Production</center>
                            <li><a class="four" href="../slitting/rolls.php"><i class="fa fa-battery-empty" aria-hidden="true"></i><span>&nbsp Rolls</span></a></li>
                            <li><a class="four" href="../slitting/rolls_stock.php"><i class="fa fa-cubes" aria-hidden="true"></i><span>&nbsp Rolls on floor </span></a></li>
                            <li><a class="four" href="#"><i class="fa fa-exchange" aria-hidden="true"></i><span>&nbsp Rolls Tracker </span></a></li>
                        	<li><a class="five" href="../slitting/waste.php"><i class="fa fa-trash-o" aria-hidden="true"></i><span>&nbsp Waste</span></a></li>
                            <li class="divider"></li>
                            <li><a class="one" href="../slitting/remarks.php"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i><span>&nbsp Short Fall</span></a></li>
						</ul>
					</li>
					<!-- /printing -->
                    
					<li role="separator" class="divider"></li>';
	}
?>	
				
					
                
                    
                
                    
			</ul>
		</div>
		<!-- /sidebar -->