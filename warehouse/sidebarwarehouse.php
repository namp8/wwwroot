<?php
    include_once "../inc/class.stock.inc.php";
    $stock = new Stock($db);
?>
<!-- href links different in integration, collaspe in class added server side in integration -->
<!-- sidebar -->
<div id="sidebar" class="sidebar-toggle">
	<ul class="nav nav-sidebar">
		<li>
			<a href="home.php"><i class="fa fa-home" aria-hidden="true"></i><span>Warehouse</span></a>
		</li><li role="separator" class="divider"></li>
<?php
	if($stock->access(1))
	{
		echo '<li>
			<a href="import.php"><i class="fa fa-share" aria-hidden="true"></i><span>Import</span></a>
		</li>
		<li role="separator" class="divider"></li>
		<li>
			<a href="purchases.php"><i class="fa fa-share" aria-hidden="true"></i><span>Local Purchases</span></a>
		</li>
		<li role="separator" class="divider"></li>
		<li>
			<a href="loans.php"><i class="fa fa-share" aria-hidden="true"></i><span>Loans & Returns</span></a>
		</li>
		<li role="separator" class="divider"></li>
		<li role="separator" class="divider"></li>
		<li role="separator" class="divider"></li>';
	}
	
	if($stock->access(4))
	{
		echo '<!-- warehouse -->
		<li data-toggle="collapse" href="#multilayer_submenu" aria-expanded="true" aria-controls="multilayer_submenu">
			<a href="#"> 
							<i class="fa fa-building" aria-hidden="true"></i><span>Stock</span>
						</a>
		</li>

		<li>
			<ul id="multilayer_submenu" class="sub-menu collapse">
				<li><a href="materials.php">Materials</a></li>
				<li><a href="inks.php">Ink and Solvents</a></li>
				<li><a href="masterbatch.php">Master batch</a></li>
				<li><a href="consumables.php">Consumable Items</a></li>
				<li><a href="semifinished.php">Semi finished products</a></li>
				<li><a href="spareparts.php">Spare Parts</a></li>
			</ul>
		</li>
		<!-- /warehouse -->

		<li role="separator" class="divider"></li>
		';
		echo '<li>
			<a href="balance.php">
									<i class="fa fa-balance-scale" aria-hidden="true"></i>
									<span>Balance</span>
							</a>
		</li>

		<li role="separator" class="divider"></li>
		<li role="separator" class="divider"></li>
		<li role="separator" class="divider"></li>';
	}		
	if($stock->access(2))
	{
		echo '<li>
			<a href="approve.php">
									<i class="fa fa-check" aria-hidden="true"></i>
									<span>Approve</span>
							</a>
		</li>

		<li role="separator" class="divider"></li>';
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
		<li role="separator" class="divider"></li>';
	}
	if($stock->access(2) or $stock->access(3))
	{
		echo '
		<li>
			<a href="receive.php"><i class="fa fa-share" aria-hidden="true"></i><span>Receive</span></a>
		</li>
		<li role="separator" class="divider"></li>
		<li>
			<a href="movements.php"><i class="fa fa-share" aria-hidden="true"></i><span>Stock Movements</span></a>
		</li>
		<li role="separator" class="divider"></li>
		<li role="separator" class="divider"></li>
		<li role="separator" class="divider"></li>
		<li>
			<a href="pvc.php"><i class="fa fa-scissors" aria-hidden="true"></i><span>PVC Pipes</span></a>
		</li>
		<li role="separator" class="divider"></li>
		<li role="separator" class="divider"></li>
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