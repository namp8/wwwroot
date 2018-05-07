<?php
    include_once "../inc/class.stock.inc.php";
    $stock = new Stock($db);
?>
<!-- href links different in integration, collaspe in class added server side in integration -->
<!-- sidebar -->
<div id="sidebar" class="sidebar-toggle">
	<ul class="nav nav-sidebar">
		<li>
			<a href="home.php"><i class="fa fa-home" aria-hidden="true"></i><span>Finished Goods</span></a>
		</li><li role="separator" class="divider"></li>
<?php
	
	if($stock->access(4))
	{
		echo '
		<li>
			<a href="stock.php"><i class="fa fa-cubes" aria-hidden="true"></i><span>Stock</span></a>
		</li>

		<li role="separator" class="divider"></li>
		';
	}		
	if($stock->access(3))
	{
		echo '
		<li>
			<a href="issue.php">
									<i class="fa fa-share-square-o" aria-hidden="true"></i>
									<span>Issue</span>
							</a>
		</li>

		<li role="separator" class="divider"></li>
		<li>
			<a href="receive.php"><i class="fa fa-share" aria-hidden="true"></i><span>Receive</span></a>
		</li>
		<li role="separator" class="divider"></li>
		';
	}
	
	if($stock->access(5))
	{
		echo '<li>
			<a href="reports.php"> 
							<i class="fa fa-bar-chart" aria-hidden="true"></i><span>Reports</span>
						</a>
		</li>

		<li role="separator" class="divider"></li>';
	}	
?>
		

	</ul>
</div>
<!-- /sidebar -->