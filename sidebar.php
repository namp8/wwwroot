<?php
    include_once "inc/class.users.inc.php";
    $users = new Users($db);
?>
<!-- href links different in integration, collaspe in class added server side in integration -->
<!-- sidebar -->
<div id="sidebar" class="sidebar-toggle">
	<ul class="nav nav-sidebar">
		<li>
			<a href="/index.php"><i class="fa fa-home" aria-hidden="true"></i><span>Home</span></a>
		</li>
		<li role="separator" class="divider"></li>
		<li role="separator" class="divider"></li>
		<li role="separator" class="divider"></li>
<?php
	if($users->access('warehouse'))
	{
		echo '<li><a href="/warehouse/home.php"><i class="fa fa-building" aria-hidden="true"></i><span>Warehouse</span></a></li>
		<li role="separator" class="divider"></li>
		<li role="separator" class="divider"></li>
		<li role="separator" class="divider"></li>';
	}
	if($users->access('sacks'))
	{
		echo '<li><a href="/sacks/process.php"><i class="fa fa-clone" aria-hidden="true"></i><span>Sacks</span></a></li>
		<li role="separator" class="divider"></li>';
	}
	if($users->access('injection'))
	{
		echo '<li><a href="/injection/process.php"><i class="fa fa-hdd-o" aria-hidden="true"></i><span>Injection</span></a></li>
		<li role="separator" class="divider"></li>';
	}
	if($users->access('packing'))
	{
		echo '<li><a href="/packing_bag/process.php"><i class="fa fa-square-o" aria-hidden="true"></i><span>Packing Bags</span></a></li>
		<li role="separator" class="divider"></li>';
	}
	if($users->access('multilayer') or $users->access('printing'))
	{
		echo '<li><a href="/sachet_rolls/process.php"><i class="fa fa-battery-empty" aria-hidden="true"></i><span>Sachet Rolls</span></a></li>
		<li role="separator" class="divider"></li>';
	}
	if($users->access('macchi'))
	{
		echo '<li><a href="/macchi/process.php"><i class="fa fa-battery-full" aria-hidden="true"></i><span>Macchi</span></a></li>
		<li role="separator" class="divider"></li>
		';
	}
?>	
		
<!--
		<li><a href="/finishedgoods/home.php"><i class="fa fa-cubes" aria-hidden="true"></i><span>Finished Goods</span></a></li>
		<li role="separator" class="divider"></li>
-->
		
		<li role="separator" class="divider"></li>
		<li role="separator" class="divider"></li>
		
<!--
		<li><a href="/settings/home.php"><i class="fa fa-gears" aria-hidden="true"></i><span>Settings</span></a></li>
		<li role="separator" class="divider"></li>

		<li role="separator" class="divider"></li>
		<li role="separator" class="divider"></li>
-->
<!--
		<li>
			<a href="#"><i class="fa fa-fw fa-bell"></i><span>Alerts</span></a>

		</li>
		<li role="separator" class="divider"></li>
-->
		<li>
			<a>	<i class="fa fa-user fa-fw" aria-hidden="true"></i><span>User: <?php echo $_SESSION['Username'] ?></span></a>
		</li>
		<li role="separator" class="divider"></li>
		<li><a data-toggle="modal" data-target="#exampleModal"><i class="fa fa-sign-out fa-fw" aria-hidden="true"></i><span>Log Out</span></a></li>
		<li role="separator" class="divider"></li>
	</ul>
</div>
<!-- /sidebar -->