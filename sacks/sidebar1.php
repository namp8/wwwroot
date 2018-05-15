<!-- href links different in integration, collaspe in class added server side in integration -->
<!-- sidebar -->
<div id="sidebar" class="sidebar-toggle">
	<ul class="nav nav-sidebar">
		<li role="separator" class="divider"></li>
					<!-- multilayer -->
		
						<li>
							<a href="process.php"><i class="fa fa-recycle" aria-hidden="true"></i><span>PROCESS</span></a>
						</li><li role="separator" class="divider"></li>
					<li data-toggle="collapse" href="#submenu" aria-expanded="true" aria-controls="submenu">
						<a href="#"> 
							<i class="fa fa-battery-empty fa-fw" aria-hidden="true"></i><span>EXTRUDER</span>
						</a>
					</li>

					<li>
						<ul id="submenu" class="sub-menu collapse">
							<li><a href="extruder/settings.php"><i class="fa fa-cogs" aria-hidden="true"></i><span>&nbsp Settings</span></a></li>
							<li><a href="extruder/reports.php"><i class="fa fa-bar-chart" aria-hidden="true"></i><span>&nbsp Reports</span></a></li>
                            <li class="divider"></li><center>Raw Material</center>
							<li><a class="two" href="extruder/request.php"><i class="fa fa-reply " aria-hidden="true" ></i><span>&nbsp Request</span></a></li>
							<li><a class="two" href="extruder/receive.php"><i class="fa fa-share" aria-hidden="true"></i><span>&nbsp Receive</span></a></li>
							<li><a class="two" href="extruder/stock.php"><i class="fa fa-cubes" aria-hidden="true"></i><span>&nbsp Raw material</span></a></li>
                            
                            <li class="divider"></li><center>Production</center>
							<li><a class="four" href="extruder/rolls.php"><i class="fa fa-battery-empty" aria-hidden="true"></i><span>&nbsp Rolls</span></a></li>
                            <li><a class="four" href="extruder/rolls_stock.php"><i class="fa fa-cubes" aria-hidden="true"></i><span>&nbsp Rolls on floor </span></a></li>
                            <li><a class="five" href="extruder/waste.php"><i class="fa fa-trash-o" aria-hidden="true"></i><span>&nbsp Waste</span></a></li>
                            <li class="divider"></li>
                            <li><a class="one" href="extruder/remarks.php"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i><span>&nbsp Short Fall</span></a></li>
                        </ul>
					</li>
					<!-- /multilayer -->

					<li role="separator" class="divider"></li>
		
		
		
		<!-- multilayer -->
					<li data-toggle="collapse" href="#cutting" aria-expanded="true" aria-controls="submenu">
						<a href="#"> 
							<i class="fa fa-scissors fa-fw" aria-hidden="true"></i><span>CUTTING</span>
						</a>
					</li>
		<li>
						<ul id="cutting" class="sub-menu collapse">
							<li><a href="cutting/settings.php"><i class="fa fa-cogs" aria-hidden="true"></i><span>&nbsp Settings</span></a></li>
							<li><a href="cutting/reports.php"><i class="fa fa-bar-chart" aria-hidden="true"></i><span>&nbsp Reports</span></a></li>
                            <li class="divider"></li><center>Consumables</center>
							<li><a class="two" href="cutting/request.php"><i class="fa fa-reply " aria-hidden="true" ></i><span>&nbsp Request</span></a></li>
							<li><a class="two" href="cutting/receive.php"><i class="fa fa-share" aria-hidden="true"></i><span>&nbsp Receive</span></a></li>
							<li class="divider"></li><center>Rolls on Floor</center>
							<li><a class="two" href="extruder/rolls_stock.php"><i class="fa fa-cubes " aria-hidden="true" ></i><span>&nbsp Rolls on floor</span></a></li>
                            
                            <li class="divider"></li><center>Production</center>
							<li><a class="four" href="cutting/sacks.php"><i class="fa fa-battery-empty" aria-hidden="true"></i><span>&nbsp Sacks</span></a></li>
                            <li><a class="four" href="cutting/sacks_stock.php"><i class="fa fa-cubes" aria-hidden="true"></i><span>&nbsp Sacks on floor </span></a></li>
                            <li><a class="five" href="cutting/waste.php"><i class="fa fa-trash-o" aria-hidden="true"></i><span>&nbsp Waste</span></a></li>
                            <li class="divider"></li>
                            <li><a class="one" href="cutting/remarks.php"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i><span>&nbsp Short Fall</span></a></li>
                        </ul>
					</li>
					<!-- /multilayer -->
		
					<li role="separator" class="divider"></li>
		
		<li data-toggle="collapse" href="#packing" aria-expanded="true" aria-controls="submenu">
						<a href="#"> 
							<i class="fa fa-clone fa-fw" aria-hidden="true"></i><span>PACKING</span>
						</a>
					</li>
		<li>
						<ul id="packing" class="sub-menu collapse">
							<li><a href="packing/settings.php"><i class="fa fa-cogs" aria-hidden="true"></i><span>&nbsp Settings</span></a></li>
							<li><a href="packing/reports.php"><i class="fa fa-bar-chart" aria-hidden="true"></i><span>&nbsp Reports</span></a></li>
                            <li class="divider"></li><center>Consumables</center>
							<li><a class="two" href="packing/request.php"><i class="fa fa-reply " aria-hidden="true" ></i><span>&nbsp Request</span></a></li>
							<li><a class="two" href="packing/receive.php"><i class="fa fa-share" aria-hidden="true"></i><span>&nbsp Receive</span></a></li>
							<li class="divider"></li><center>Cutting Sacks on Floor</center>
							<li><a class="two" href="cutting/sacks_stock.php"><i class="fa fa-cubes " aria-hidden="true" ></i><span>&nbsp Cutting Sacks on floor</span></a></li>
                            <li class="divider"></li><center>Production</center>
							<li><a class="four" href="packing/sacks.php"><i class="fa fa-battery-empty" aria-hidden="true"></i><span>&nbsp Sacks</span></a></li>
                            <li><a class="five" href="packing/waste.php"><i class="fa fa-trash-o" aria-hidden="true"></i><span>&nbsp Waste</span></a></li>
                            <li class="divider"></li>
                            <li><a class="one" href="packing/remarks.php"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i><span>&nbsp Short Fall</span></a></li>
                        </ul>
					</li>
					<!-- /multilayer -->
		
					<li role="separator" class="divider"></li>

	</ul>
</div>
<!-- /sidebar -->