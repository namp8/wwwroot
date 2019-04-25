<?php
    include_once "../inc/class.users.inc.php";
    $users = new Users($db);
?>
<!-- href links different in integration, collaspe in class added server side in integration -->
<!-- sidebar -->
<div id="sidebar" class="sidebar-toggle">
	<ul class="nav nav-sidebar">
		
<?php
	if($users->admin())
	{
		echo '<li>
			<a href="home.php"><i class="fa fa-home" aria-hidden="true"></i><span>Settings</span></a>
		</li><li role="separator" class="divider"></li>';
		echo '<li>
			<a href="users.php"><i class="fa fa-user" aria-hidden="true"></i><span>Users</span></a>
		</li><li role="separator" class="divider"></li>';
		echo '<li>
			<a href="customer.php"><i class="fa fa-user" aria-hidden="true"></i><span>Customers</span></a>
		</li><li role="separator" class="divider"></li>';
		echo '<li>
			<a href="operators.php"><i class="fa fa-user" aria-hidden="true"></i><span>Operators</span></a>
		</li><li role="separator" class="divider"></li>';
		echo '
		<li data-toggle="collapse" href="#multilayer_submenu" aria-expanded="true" aria-controls="multilayer_submenu">
			<a href="#"> 
							<i class="fa fa-building" aria-hidden="true"></i><span>Material</span>
						</a>
		</li>
		<li>
			<ul id="multilayer_submenu" class="sub-menu collapse">
				<li><a href="materials.php">Raw Material</a></li>
				<li><a href="inks.php">Ink and Solvents</a></li>
				<li><a href="master_batch.php">Master batch</a></li>
				<li><a href="consumables.php">Consumable Items</a></li>
				<li><a href="semifinished.php">Semi finished products</a></li>
				<li><a href="spare_parts.php">Spare Parts</a></li>
			</ul>
		</li>
		<!-- /warehouse -->

		<li role="separator" class="divider"></li>';
	}
?>
		

	</ul>
</div>
<!-- /sidebar -->