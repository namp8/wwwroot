<?php

/**
 * Handles user interactions within the general production sections
 * Short Falls
 *
 * PHP version 5
 *
 * @author Natalia Montañez
 * @copyright 2017 Natalia Montañez
 *
 */
class General
{
	/**
	 * The database object
	 *
	 * @var object
	 */
	private $_db;
	
	/**
	 * Checks for a database object and creates one if none is found
	 *
	 * @param object $db
	 * @return void
	 */
	public function __construct($db=NULL)
	{
		if(is_object($db))
		{
			$this->_db = $db;
		}
		else
		{
			$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
			$this->_db = new PDO($dsn, DB_USER, DB_PASS);
		}
	}
	
	public function giveMachines($location)
	{
		$sql = "SELECT `machines`.`machine_id`, `machines`.`machine_name`, `machines`.`location_id`, `machines`.`size`, location_name
				FROM `machines`
				INNER JOIN locations ON machines.location_id = locations.location_id
				WHERE location_name = '". $location."' AND section = 0;";
		if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                echo '<li><a onclick="selectMachine('. $row['machine_id'] .',\''.$row['machine_name'].'\')">'.$row['machine_name'].'</a></li>';
            }
            $stmt->closeCursor();
            
        }  
        else
        {
            echo "Something went wrong. $db->errorInfo";
        }
	}
	
	//CUSTOMERS
	public function giveOperators()
	{
		$sql = "SELECT `employees`.`employee_id`,
    `employees`.`employee_name`,
    `employees`.`sacks`,
    `employees`.`injection`,
    `employees`.`macchi`,
    `employees`.`multilayer`,
    `employees`.`printing`,
    `employees`.`packing`,
    `employees`.`slitting`
FROM `employees`;
";
		if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				$sachet = $bags = $shrink = false;
				$SACHET = '';
				if($row['sachet_rolls'] == 1)
				{
					$SACHET = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$sachet = true;
				}
				$BAGS = '';
				if($row['packing_bags'] == 1)
				{
					$BAGS = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$bags = true;
				}
				$SHRINK = '';
				if($row['shrink_film'] == 1)
				{
					$SHRINK = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$shrink = true;
				}
                echo '<tr>
                    <td>'. $row['customer_name'] .'</td>
                    <td>'. $SACHET .'</td>
                    <td>'. $BAGS .'</td>
                    <td>'. $SHRINK .'</td>
					<td><button class="btn btn-xs btn-warning" type="button" onclick="edit(\''. $row['customer_id'] .'\',\''. $row['customer_name'] .'\',\''. $sachet .'\',\''. $bags .'\',\''. $shrink .'\')"><i class="fa fa-pencil" aria-hidden="true"></i></button>
					<button class="btn btn-xs btn-danger" type="button" onclick="deleteCustomer(\''. $row['customer_id'] .'\',\''. $row['customer_name'] .'\')">X</button></td>
                </tr>';
            }
            $stmt->closeCursor();
            
        }  
        else
        {
            echo "Something went wrong. $db->errorInfo";
        }
	}
	
	//CUSTOMERS
	public function giveCustomers()
	{
		$sql = "SELECT `customers`.`customer_id`,
					`customers`.`customer_name`,
					`customers`.`sachet_rolls`,
					`customers`.`packing_bags`,
					`customers`.`shrink_film`
				FROM `customers`;";
		if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				$sachet = $bags = $shrink = false;
				$SACHET = '';
				if($row['sachet_rolls'] == 1)
				{
					$SACHET = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$sachet = true;
				}
				$BAGS = '';
				if($row['packing_bags'] == 1)
				{
					$BAGS = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$bags = true;
				}
				$SHRINK = '';
				if($row['shrink_film'] == 1)
				{
					$SHRINK = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$shrink = true;
				}
                echo '<tr>
                    <td>'. $row['customer_name'] .'</td>
                    <td>'. $SACHET .'</td>
                    <td>'. $BAGS .'</td>
                    <td>'. $SHRINK .'</td>
					<td><button class="btn btn-xs btn-warning" type="button" onclick="edit(\''. $row['customer_id'] .'\',\''. $row['customer_name'] .'\',\''. $sachet .'\',\''. $bags .'\',\''. $shrink .'\')"><i class="fa fa-pencil" aria-hidden="true"></i></button>
					<button class="btn btn-xs btn-danger" type="button" onclick="deleteCustomer(\''. $row['customer_id'] .'\',\''. $row['customer_name'] .'\')">X</button></td>
                </tr>';
            }
            $stmt->closeCursor();
            
        }  
        else
        {
            echo "Something went wrong. $db->errorInfo";
        }
	}
	
    public function createCustomer()
    {
        $name = "";
        $sachet = $bags = $shrink = 0;
		
        $name = trim($_POST["name"]);
        $name = stripslashes($name);
        $name = htmlspecialchars($name);
		
		if(isset($_POST["product"]))
		{
			$array = $_POST["product"];
			foreach($array as $product)
			{
				if($product == "sachet")
				{
					$sachet = 1;
				}
				else if($product == "bags")
				{
					$bags = 1;
				}
				else if($product == "shrink")
				{
					$shrink = 1;
				}
			}
		}
		else
		{
			echo '<strong>ERROR</strong> You did not choose any products. Please try again.'; 
			return FALSE;
		}
        
        $sql = "INSERT INTO `customers`(`customer_id`,`customer_name`,`sachet_rolls`,`packing_bags`,`shrink_film`) VALUES (NULL,:name,:sachet,:bags,:shrink);";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":sachet", $sachet, PDO::PARAM_INT);
            $stmt->bindParam(":bags", $bags, PDO::PARAM_INT);
            $stmt->bindParam(":shrink", $shrink, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            echo '<strong>SUCCESS!</strong> The customer was successfully added to the database.';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> There is customer in the system with the same name. Try updating it<br>';
            } else {
              echo '<strong>ERROR</strong> Could not insert the customer into the database. Please try again.<br>'. $e->getMessage();
            }
            return FALSE;
        } 

    }
	
	public function updateCustomer()
    {
        $id = $name = "";
        $sachet = $bags = $shrink = 0;
		
		$id = trim($_POST["id_customer"]);
        $id = stripslashes($id);
        $id = htmlspecialchars($id);
		
        $name = trim($_POST["name"]);
        $name = stripslashes($name);
        $name = htmlspecialchars($name);
		
		if(isset($_POST["product"]))
		{
			$array = $_POST["product"];
			foreach($array as $product)
			{
				if($product == "sachet")
				{
					$sachet = 1;
				}
				else if($product == "bags")
				{
					$bags = 1;
				}
				else if($product == "shrink")
				{
					$shrink = 1;
				}
			}
		}
		else
		{
			echo '<strong>ERROR</strong> You did not choose any products. Please try again.'; 
			return FALSE;
		}
        
        $sql = "UPDATE`customers`
				SET
				`customer_name` = :name,
				`sachet_rolls` = :sachet,
				`packing_bags` = :bags,
				`shrink_film` = :shrink
				WHERE `customer_id` = :id;";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":sachet", $sachet, PDO::PARAM_INT);
            $stmt->bindParam(":bags", $bags, PDO::PARAM_INT);
            $stmt->bindParam(":shrink", $shrink, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            echo '<strong>SUCCESS!</strong> The customer was successfully updated the database.';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> There is customer in the system with the same name. Try updating it<br>';
            } else {
              echo '<strong>ERROR</strong> Could not update the customer into the database. Please try again.<br>'. $e->getMessage();
            }
            return FALSE;
        } 

    }
	
	public function deleteCustomer()
    {
        $id = $name = "";
		
		$id = trim($_POST["id_customer"]);
        $id = stripslashes($id);
        $id = htmlspecialchars($id);
		
        $name = trim($_POST["name"]);
        $name = stripslashes($name);
        $name = htmlspecialchars($name);
		
        
        $sql = "DELETE FROM `customers`
				WHERE `customer_id` = :id;";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            echo '<strong>SUCCESS!</strong> The customer was successfully deleted from the database.';
            return TRUE;
        } catch (PDOException $e) {
              echo '<strong>ERROR</strong> Could not delete the customer from the database. The reason is that the customer has products or orders associated to it.<br>'. $e->getMessage();
            return FALSE;
        } 

    }
	//OPERATORS
	public function giveOperatorsList()
	{
		$sql = "SELECT `employees`.`employee_id`,
					`employees`.`employee_name`,
					`employees`.`sacks`,
					`employees`.`injection`,
					`employees`.`macchi`,
					`employees`.`multilayer`,
					`employees`.`printing`,
					`employees`.`packing`,
					`employees`.`slitting`
				FROM `employees`
				ORDER BY `employee_name`;";
		if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				$sacks = $injection = $macchi = $multilayer = $printing = $packing  = $slitting = false;
				
				
				$SACKS = '';
				if($row['sacks'] == 1)
				{
					$SACKS = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$sacks = true;
				}
				
				$INJECTION = '';
				if($row['injection'] == 1)
				{
					$INJECTION = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$injection = true;
				}
				
				$MACCHI = '';
				if($row['macchi'] == 1)
				{
					$MACCHI = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$macchi = true;
				}
				
				$MULTILAYER = '';
				if($row['multilayer'] == 1)
				{
					$MULTILAYER = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$multilayer = true;
				}
				
				$PRINTING = '';
				if($row['printing'] == 1)
				{
					$PRINTING = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$printing = true;
				}
				
				$PACKING = '';
				if($row['packing'] == 1)
				{
					$PACKING = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$packing = true;
				}
				
				$SLITTING = '';
				if($row['slitting'] == 1)
				{
					$SLITTING = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$slitting = true;
				}
				
                echo '<tr>
					<td><button class="btn btn-xs btn-warning" type="button" onclick="edit(\''. $row['employee_id'] .'\',\''. $row['employee_name'] .'\',\''. $sacks .'\',\''. $multilayer .'\',\''. $printing .'\',\''. $slitting .'\',\''. $injection .'\',\''. $macchi .'\',\''. $packing .'\')"><i class="fa fa-pencil" aria-hidden="true"></i></button></td>
					<td><button class="btn btn-xs btn-danger" type="button" onclick="deleteOperator(\''. $row['employee_id'] .'\',\''. $row['employee_name'] .'\')">X</button></td>
                    <td>'. $row['employee_name'] .'</td>
                    <td>'. $SACKS .'</td>
					<td>'. $MULTILAYER .'</td>
					<td>'. $PRINTING .'</td>
					<td>'. $SLITTING .'</td>
					<td>'. $INJECTION .'</td>
					<td>'. $MACCHI .'</td>
					<td>'. $PACKING .'</td>
					
                </tr>';
            }
            $stmt->closeCursor();
            
        }  
        else
        {
            echo "Something went wrong. $db->errorInfo";
        }
	}
	public function createOperator()
    {
        $name = "";
        $sacks = $injection = $macchi = $multilayer = $printing = $packing = $slitting = 0;
		
        $name = trim($_POST["name"]);
        $name = stripslashes($name);
        $name = htmlspecialchars($name);
		
		if(isset($_POST["view"]))
		{
			$array = $_POST["view"];
			foreach($array as $view)
			{
				if($view == "Sacks")
				{
					$sacks = 1;
				}
				else if($view == "Multilayer")
				{
					$multilayer = 1;
				}
				else if($view == "Printing")
				{
					$printing = 1;
				}
				else if($view == "Slitting")
				{
					$slitting = 1;
				}
				else if($view == "Injection")
				{
					$injection = 1;
				}
				else if($view == "Macchi")
				{
					$macchi = 1;
				}
				else if($view == "Packing")
				{
					$packing = 1;
				}
			}
		}
		else
		{
			echo '<strong>ERROR</strong> You did not choose any section. Please try again.'; 
			return FALSE;
		}
        
        $sql = "INSERT INTO `employees`
(`employee_id`,`employee_name`,`sacks`,`injection`,`macchi`,`multilayer`,`printing`,`packing`,`slitting`)
VALUES (NULL,:name,:sacks,:injection,:macchi,:multilayer,:printing,:packing,:slitting);";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":sacks", $sacks, PDO::PARAM_INT);
            $stmt->bindParam(":injection", $injection, PDO::PARAM_INT);
            $stmt->bindParam(":macchi", $macchi, PDO::PARAM_INT);
            $stmt->bindParam(":multilayer", $multilayer, PDO::PARAM_INT);
            $stmt->bindParam(":printing", $printing, PDO::PARAM_INT);
            $stmt->bindParam(":packing", $packing, PDO::PARAM_INT);
            $stmt->bindParam(":slitting", $slitting, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            echo '<strong>SUCCESS!</strong> The operator was successfully added to the database.';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> There is a operator in the system with the same name. Try updating it<br>';
            } else {
              echo '<strong>ERROR</strong> Could not insert the operator into the database. Please try again.<br>'. $e->getMessage();
            }
            return FALSE;
        } 

    }
	public function updateOperator()
    {
        $id = $name = "";
		
		$sacks = $injection = $macchi = $multilayer = $printing = $packing = $slitting = 0;
		
		$id = trim($_POST["id_operator"]);
        $id = stripslashes($id);
        $id = htmlspecialchars($id);
		
        $name = trim($_POST["name"]);
        $name = stripslashes($name);
        $name = htmlspecialchars($name);
		
		if(isset($_POST["view"]))
		{
			$array = $_POST["view"];
			foreach($array as $view)
			{
				if($view == "Sacks")
				{
					$sacks = 1;
				}
				else if($view == "Multilayer")
				{
					$multilayer = 1;
				}
				else if($view == "Printing")
				{
					$printing = 1;
				}
				else if($view == "Slitting")
				{
					$slitting = 1;
				}
				else if($view == "Injection")
				{
					$injection = 1;
				}
				else if($view == "Macchi")
				{
					$macchi = 1;
				}
				else if($view == "Packing")
				{
					$packing = 1;
				}
			}
		}
		else
		{
			echo '<strong>ERROR</strong> You did not choose any section. Please try again.'; 
			return FALSE;
		}
        
        $sql = "UPDATE `employees`
				SET
				`employee_name` = :name,
				`sacks` = :sacks,
				`injection` = :injection,
				`macchi` = :macchi,
				`multilayer` = :multilayer,
				`printing` = :printing,
				`packing` = :packing,
				`slitting` = :slitting
				WHERE `employee_id` = :id;";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":sacks", $sacks, PDO::PARAM_INT);
            $stmt->bindParam(":injection", $injection, PDO::PARAM_INT);
            $stmt->bindParam(":macchi", $macchi, PDO::PARAM_INT);
            $stmt->bindParam(":multilayer", $multilayer, PDO::PARAM_INT);
            $stmt->bindParam(":printing", $printing, PDO::PARAM_INT);
            $stmt->bindParam(":packing", $packing, PDO::PARAM_INT);
            $stmt->bindParam(":slitting", $slitting, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            echo '<strong>SUCCESS!</strong> The operator was successfully updated in  the database.';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> There is operator in the system with the same name. Try updating it<br>';
            } else {
              echo '<strong>ERROR</strong> Could not update the operator into the database. Please try again.<br>'. $e->getMessage();
            }
            return FALSE;
        } 

    }
	public function deleteOperator()
    {
        $id = "";
		
		$id = trim($_POST["id_operator"]);
        $id = stripslashes($id);
        $id = htmlspecialchars($id);
        
        $sql = "UPDATE `employees`
				SET `disable` = 1
				WHERE `employee_id` = :id;";
		try
		{   
			$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(":id", $id, PDO::PARAM_INT);
			$stmt->execute();
			$stmt->closeCursor();
			echo '<strong>SUCCESS!</strong> The operator was successfully disabled from the database.<br>';
			return true;
		} 
		catch (PDOException $e) {
		  echo '<strong>ERROR</strong> Could not delete the operator from the database.<br>'. $e->getMessage();
			return false;
		} 

    }
	//USERS
	public function giveUsers()
	{
		$sql = "SELECT `users`.`user_id`,
				`users`.`username`,
				`users`.`admin`,
				`users`.`warehouse_purchases`,
				`users`.`warehouse_approve`,
				`users`.`warehouse_issue`,
				`users`.`warehouse_stock`,
				`users`.`warehouse_reports`,
				`users`.`settings`,
				`users`.`sacks`,
				`users`.`injection`,
				`users`.`macchi`,
				`users`.`multilayer`,
				`users`.`printing`,
				`users`.`packing`,
				`users`.`warehouse`,
				`users`.`slitting`
			FROM `users`
			WHERE `disable` = 0
			ORDER BY `username`;";
		if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				$admin = $purchases = $approve = $issue = $stock = $reports = $settings = $sacks = $injection = $macchi = $multilayer = $printing = $packing = $warehouse = $slitting = false;
				
				$ADMIN = '';
				if($row['admin'] == 1)
				{
					$ADMIN = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$admin = true;
				}
				
				$PURCHASES = '';
				if($row['warehouse_purchases'] == 1)
				{
					$PURCHASES = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$purchases = true;
				}
				
				$APPROVE = '';
				if($row['warehouse_approve'] == 1)
				{
					$APPROVE = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$approve = true;
				}
				
				$ISSUE = '';
				if($row['warehouse_issue'] == 1)
				{
					$ISSUE = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$issue = true;
				}
				
				$STOCK = '';
				if($row['warehouse_stock'] == 1)
				{
					$STOCK = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$stock = true;
				}
				
				$REPORTS = '';
				if($row['warehouse_reports'] == 1)
				{
					$REPORTS = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$reports = true;
				}
				
				$SETTINGS = '';
				if($row['settings'] == 1)
				{
					$SETTINGS = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$settings = true;
				}
				
				$SACKS = '';
				if($row['sacks'] == 1)
				{
					$SACKS = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$sacks = true;
				}
				
				$INJECTION = '';
				if($row['injection'] == 1)
				{
					$INJECTION = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$injection = true;
				}
				
				$MACCHI = '';
				if($row['macchi'] == 1)
				{
					$MACCHI = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$macchi = true;
				}
				
				$MULTILAYER = '';
				if($row['multilayer'] == 1)
				{
					$MULTILAYER = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$multilayer = true;
				}
				
				$PRINTING = '';
				if($row['printing'] == 1)
				{
					$PRINTING = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$printing = true;
				}
				
				$PACKING = '';
				if($row['packing'] == 1)
				{
					$PACKING = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$packing = true;
				}
				
				$WAREHOUSE = '';
				if($row['warehouse'] == 1)
				{
					$WAREHOUSE = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$warehouse = true;
				}
				
				$SLITTING = '';
				if($row['slitting'] == 1)
				{
					$SLITTING = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$slitting = true;
				}
				
                echo '<tr>
					<td><button class="btn btn-xs btn-warning" type="button" onclick="edit(\''. $row['user_id'] .'\',\''. $row['username'] .'\',\''. $admin .'\',\''. $settings .'\',\''. $sacks .'\',\''. $multilayer .'\',\''. $printing .'\',\''. $slitting .'\',\''. $injection .'\',\''. $macchi .'\',\''. $packing .'\',\''. $warehouse .'\',\''. $purchases .'\',\''. $approve .'\',\''. $issue .'\',\''. $stock.'\',\''. $reports.'\')"><i class="fa fa-pencil" aria-hidden="true"></i></button></td>
					<td><button class="btn btn-xs btn-danger" type="button" onclick="deleteUser(\''. $row['user_id'] .'\',\''. $row['username'] .'\')">X</button></td>
                    <td>'. $row['username'] .'</td>
                    <td>'. $ADMIN .'</td>
                    <td>'. $SETTINGS .'</td>
                    <td>'. $SACKS .'</td>
					<td>'. $MULTILAYER .'</td>
					<td>'. $PRINTING .'</td>
					<td>'. $SLITTING .'</td>
					<td>'. $INJECTION .'</td>
					<td>'. $MACCHI .'</td>
					<td>'. $PACKING .'</td>
					<td>'. $WAREHOUSE .'</td>
					<td>'. $PURCHASES .'</td>
					<td>'. $APPROVE .'</td>
					<td>'. $ISSUE .'</td>
					<td>'. $STOCK .'</td>
					<td>'. $REPORTS .'</td>
					
                </tr>';
            }
            $stmt->closeCursor();
            
        }  
        else
        {
            echo "Something went wrong. $db->errorInfo";
        }
	}
	
	public function createUser()
    {
        $name = $password =  "";
        $admin = $purchases = $approve = $issue = $stock = $reports = $settings = $sacks = $injection = $macchi = $multilayer = $printing = $packing = $warehouse = $slitting = 0;
		
        $name = trim($_POST["name"]);
        $name = stripslashes($name);
        $name = htmlspecialchars($name);
		
		$password = trim($_POST["password"]);
        $password = stripslashes($password);
        $password = htmlspecialchars($password);
		
		if(isset($_POST["view"]))
		{
			$array = $_POST["view"];
			foreach($array as $view)
			{
				if($view == "Admin")
				{
					$admin = 1;
				}
				else if($view == "Sacks")
				{
					$sacks = 1;
				}
				else if($view == "Multilayer")
				{
					$multilayer = 1;
				}
				else if($view == "Printing")
				{
					$printing = 1;
				}
				else if($view == "Slitting")
				{
					$slitting = 1;
				}
				else if($view == "Injection")
				{
					$injection = 1;
				}
				else if($view == "Macchi")
				{
					$macchi = 1;
				}
				else if($view == "Packing")
				{
					$packing = 1;
				}
				else if($view == "Warehouse")
				{
					$warehouse = 1;
				}
				else if($view == "Settings")
				{
					$settings = 1;
				}
				else if($view == "Purchases")
				{
					$purchases = 1;
				}
				else if($view == "Approve")
				{
					$approve = 1;
				}
				else if($view == "Issue")
				{
					$issue = 1;
				}
				else if($view == "Stock")
				{
					$stock = 1;
				}
				else if($view == "Reports")
				{
					$reports = 1;
				}
			}
		}
		else
		{
			echo '<strong>ERROR</strong> You did not choose any view. Please try again.'; 
			return FALSE;
		}
        
        $sql = "INSERT INTO `users` (`user_id`,`username`,`password`,`admin`,`warehouse_purchases`,`warehouse_approve`,`warehouse_issue`,`warehouse_stock`,`warehouse_reports`,`settings`,`sacks`,`injection`,`macchi`,`multilayer`,`printing`,`packing`,`warehouse`,`slitting`,`disable`)
		VALUES (NULL,:name,MD5(:password),:admin,:purchases,:approve,:issue,:stock,:reports,:settings,:sacks,:injection,:macchi,:multilayer,:printing,:packing,:warehouse,:slitting,0);";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":password", $password, PDO::PARAM_STR);
            $stmt->bindParam(":admin", $admin, PDO::PARAM_INT);
            $stmt->bindParam(":purchases", $purchases, PDO::PARAM_INT);
            $stmt->bindParam(":approve", $approve, PDO::PARAM_INT);
            $stmt->bindParam(":issue", $issue, PDO::PARAM_INT);
            $stmt->bindParam(":stock", $stock, PDO::PARAM_INT);
            $stmt->bindParam(":reports", $reports, PDO::PARAM_INT);
            $stmt->bindParam(":settings", $settings, PDO::PARAM_INT);
            $stmt->bindParam(":sacks", $sacks, PDO::PARAM_INT);
            $stmt->bindParam(":injection", $injection, PDO::PARAM_INT);
            $stmt->bindParam(":macchi", $macchi, PDO::PARAM_INT);
            $stmt->bindParam(":multilayer", $multilayer, PDO::PARAM_INT);
            $stmt->bindParam(":printing", $printing, PDO::PARAM_INT);
            $stmt->bindParam(":packing", $packing, PDO::PARAM_INT);
            $stmt->bindParam(":warehouse", $warehouse, PDO::PARAM_INT);
            $stmt->bindParam(":slitting", $slitting, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            echo '<strong>SUCCESS!</strong> The user was successfully added to the database.';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> There is a user in the system with the same username. Try updating it<br>';
            } else {
              echo '<strong>ERROR</strong> Could not insert the user into the database. Please try again.<br>'. $e->getMessage();
            }
            return FALSE;
        } 

    }
	public function updateUser()
    {
        $id = $name = "";
		
		$admin = $purchases = $approve = $issue = $stock = $reports = $settings = $sacks = $injection = $macchi = $multilayer = $printing = $packing = $warehouse = $slitting = 0;
		
		$id = trim($_POST["id_user"]);
        $id = stripslashes($id);
        $id = htmlspecialchars($id);
		
        $name = trim($_POST["name"]);
        $name = stripslashes($name);
        $name = htmlspecialchars($name);
		
		$password = trim($_POST["password"]);
        $password = stripslashes($password);
        $password = htmlspecialchars($password);
		
		if(isset($_POST["view"]))
		{
			$array = $_POST["view"];
			foreach($array as $view)
			{
				if($view == "Admin")
				{
					$admin = 1;
				}
				else if($view == "Sacks")
				{
					$sacks = 1;
				}
				else if($view == "Multilayer")
				{
					$multilayer = 1;
				}
				else if($view == "Printing")
				{
					$printing = 1;
				}
				else if($view == "Slitting")
				{
					$slitting = 1;
				}
				else if($view == "Injection")
				{
					$injection = 1;
				}
				else if($view == "Macchi")
				{
					$macchi = 1;
				}
				else if($view == "Packing")
				{
					$packing = 1;
				}
				else if($view == "Warehouse")
				{
					$warehouse = 1;
				}
				else if($view == "Settings")
				{
					$settings = 1;
				}
				else if($view == "Purchases")
				{
					$purchases = 1;
				}
				else if($view == "Approve")
				{
					$approve = 1;
				}
				else if($view == "Issue")
				{
					$issue = 1;
				}
				else if($view == "Stock")
				{
					$stock = 1;
				}
				else if($view == "Reports")
				{
					$reports = 1;
				}
			}
		}
		else
		{
			echo '<strong>ERROR</strong> You did not choose any view. Please try again.'; 
			return FALSE;
		}
        
        $sql = "UPDATE `users`
				SET
				`username` = :name,
				`password` = MD5(:password),
				`admin` = :admin,
				`warehouse_purchases` = :purchases,
				`warehouse_approve` = :approve,
				`warehouse_issue` = :issue,
				`warehouse_stock` = :stock,
				`warehouse_reports` = :reports,
				`settings` = :settings,
				`sacks` = :sacks,
				`injection` = :injection,
				`macchi` = :macchi,
				`multilayer` = :multilayer,
				`printing` = :printing,
				`packing` = :packing,
				`warehouse` = :warehouse,
				`slitting` = :slitting
				WHERE `user_id` = :id;";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":password", $password, PDO::PARAM_STR);
            $stmt->bindParam(":admin", $admin, PDO::PARAM_INT);
            $stmt->bindParam(":purchases", $purchases, PDO::PARAM_INT);
            $stmt->bindParam(":approve", $approve, PDO::PARAM_INT);
            $stmt->bindParam(":issue", $issue, PDO::PARAM_INT);
            $stmt->bindParam(":stock", $stock, PDO::PARAM_INT);
            $stmt->bindParam(":reports", $reports, PDO::PARAM_INT);
            $stmt->bindParam(":settings", $settings, PDO::PARAM_INT);
            $stmt->bindParam(":sacks", $sacks, PDO::PARAM_INT);
            $stmt->bindParam(":injection", $injection, PDO::PARAM_INT);
            $stmt->bindParam(":macchi", $macchi, PDO::PARAM_INT);
            $stmt->bindParam(":multilayer", $multilayer, PDO::PARAM_INT);
            $stmt->bindParam(":printing", $printing, PDO::PARAM_INT);
            $stmt->bindParam(":packing", $packing, PDO::PARAM_INT);
            $stmt->bindParam(":warehouse", $warehouse, PDO::PARAM_INT);
            $stmt->bindParam(":slitting", $slitting, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            echo '<strong>SUCCESS!</strong> The user was successfully updated the database.';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> There is user in the system with the same username. Try updating it<br>';
            } else {
              echo '<strong>ERROR</strong> Could not update the user into the database. Please try again.<br>'. $e->getMessage();
            }
            return FALSE;
        } 

    }
	public function deleteUser()
    {
        $id = $name = "";
		
		$id = trim($_POST["id_user"]);
        $id = stripslashes($id);
        $id = htmlspecialchars($id);
		
        $name = trim($_POST["name"]);
        $name = stripslashes($name);
        $name = htmlspecialchars($name);
		
        
        $sql = "DELETE FROM `users`
				WHERE `user_id` = :id;";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            echo '<strong>SUCCESS!</strong> The user was successfully deleted from the database.';
            return TRUE;
        } catch (PDOException $e) {
				$sql = "UPDATE `users`
						SET`password` = 'disable',`disable` = 1
						WHERE `user_id` = :id;";
				try
				{   
					$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
					$stmt = $this->_db->prepare($sql);
					$stmt->bindParam(":id", $id, PDO::PARAM_INT);
					$stmt->execute();
					$stmt->closeCursor();
					echo '<strong>ERROR</strong> Could not delete the user from the database. The reason is that the user has entries associated to it. The user was disabled from the system<br>';
				} 
				catch (PDOException $e) {
				  echo '<strong>ERROR</strong> Could not delete the user from the database.<br>'. $e->getMessage();
				} 
				
            return FALSE;
        } 

    }
	
	///SHORTFALLS 
	
	/**
     * Checks and inserts a new short fall
     *
     * @return boolean  true if can insert  false if not
     */
    public function createFall()
    {
        $machine = $reason = $action = $time = "";
        
        $machine = trim($_POST["machine"]);
        $machine = stripslashes($machine);
        $machine = htmlspecialchars($machine);
		
        $reason = stripslashes($_POST["reason"]);
        $reason = htmlspecialchars($reason);
        
        $action = stripslashes($_POST["action"]);
        $action = htmlspecialchars($action);
        
        $time = trim($_POST["time"]);
        $time = stripslashes($time);
        $time = htmlspecialchars($time);
		
		if($time == '23:59')
		{
			$time = '24:00';
		}
		
		$date = "CURRENT_DATE()";
		if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = $newDateString;
        }
        
        $sql = "INSERT INTO  `shortfalls`(`shortfall_id`,`machine_id`,`date_fall`,`downtime`,`reason`,`action_plan`)
        VALUES
        (NULL,". $machine .",'". $date . "',:time,:reason,:action);";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":time", $time, PDO::PARAM_STR);
            $stmt->bindParam(":reason", $reason, PDO::PARAM_STR);
            $stmt->bindParam(":action", $action, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
            
            echo '<strong>SUCCESS!</strong> The reason for short fall was successfully added to the database.';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> There is a reason for short fall already in that date.<br>';
            } else {
              echo '<strong>ERROR</strong> Could not insert the reason for short fall  into the database. Please try again.<br>'. $e->getMessage();
            }
            
            return FALSE;
        } 

    }
	/**
     * Checks gives the shortfalls reasons
     *
     */
    public function giveShortFall($location)
    {
		$sql = "SELECT `shortfalls`.`shortfall_id`,  machines.machine_id, machine_name,
    `shortfalls`.`date_fall`,
    `shortfalls`.`downtime` AS time_t,
    `shortfalls`.`reason`,
    `shortfalls`.`action_plan`
FROM
    `shortfalls`
INNER JOIN machines ON machines.machine_id = shortfalls.machine_id
INNER JOIN locations ON machines.location_id = locations.location_id
WHERE location_name = '". $location."' AND MONTH(date_fall) >= MONTH(CURRENT_DATE())-1 AND YEAR(date_fall) = YEAR(CURRENT_DATE())
ORDER BY date_fall, `shortfalls`.machine_id;";
		
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				$ID = $row['shortfall_id'];
                echo '<tr>
                    <td>'. $row['date_fall'] .'</td>
                    <td>'. $row['machine_name'] .'</td>
                    <td>'. $row['time_t'] .'</td>
                    <td>'. $row['reason'] .'</td>
                    <td>'. $row['action_plan'] .'</td>
                </tr>';
            }
            $stmt->closeCursor();
            
        }  
        else
        {
            echo "Something went wrong. $db->errorInfo";
        }
    }
    
    	/**
     * Loads the Waste Report 
     * This function outputs <tr> tags with the report
     */
    public function reportWaste()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Sacks - Extruder</th>';
        echo '<th>Sacks - Cutting</th>';
        echo '<th>Sacks - Packing</th>';
        echo '<th>Injection</th>';
        echo '<th>Packing Bags</th>';
        echo '<th>Multilayer</th>';
        echo '<th>Macchi</th>';
        echo '<th>Printing</th>';
        echo '<th>Slitting</th>';
        echo '<th>Total Waste</th>';
        echo '</tr></thead><tfoot><tr  class="active">
			<th style="text-align:right">Total</th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th></tr></tfoot><tbody>';   
        
        $a=array();
        
        $newDateString = date("Y-m-d");
        $newDateString2 = date("Y-m-d");
        if($_POST['searchBy']==2)
        {    
            if(!empty($_POST['dateSearch']))
            {
               $myDateTime = DateTime::createFromFormat('M/Y', $_POST['dateSearch']);
               $myDateTime->modify('first day of this month');
               $newDateString = $myDateTime->format('Y-m-d');
            }
            if(!empty($_POST['dateSearch2']))
            {
               $myDateTime = DateTime::createFromFormat('M/Y', $_POST['dateSearch2']);
               $myDateTime->modify('last day of this month');
               $newDateString2 = $myDateTime->format('Y-m-d');
            }
            $format = "%m/%Y";
        }
        else if($_POST['searchBy']==3)
        {    
            if(!empty($_POST['dateSearch']))
            {
               $myDateTime = DateTime::createFromFormat('Y', $_POST['dateSearch']);
               $myDateTime->modify('first day of January ' . $_POST['dateSearch']);
               $newDateString = $myDateTime->format('Y-m-d');
            }
            if(!empty($_POST['dateSearch2']))
            {
               $myDateTime = DateTime::createFromFormat('Y', $_POST['dateSearch2']);
               $myDateTime->modify('last day of December ' . $_POST['dateSearch2']);
               $newDateString2 = $myDateTime->format('Y-m-d');
            }
            $format = "%Y";
        }
        else
        {
            if(!empty($_POST['dateSearch']))
            {
               $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch']);
               $newDateString = $myDateTime->format('Y-m-d');
            }
            if(!empty($_POST['dateSearch2']))
            {
               $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch2']);
               $newDateString2 = $myDateTime->format('Y-m-d');
            }
            $format = "%d/%m/%Y";
        }
        
         $sql = " SELECT DATE_FORMAT(waste.`date_waste`, '". $format ."') AS date, DATE_FORMAT(waste.`date_waste`, '%b/%Y') AS date2, extruder.waste as extruder, cutting.waste as cutting, packing.waste as packing,
 injection.waste as injection, packing_bag.waste as packing_bag, multilayer.waste as multilayer, macchi.waste as macchi, printing.waste as printing,
 slitting.waste as slitting, SUM(`waste`.`waste`) AS total
 FROM  `waste`
 LEFT JOIN (
 SELECT DATE_FORMAT(`date_waste`, '". $format ."') AS `date_waste`, SUM(`waste`.`waste`) AS waste
 FROM  `waste`
 JOIN machines m ON waste.machine_id = m.machine_id
 WHERE location_id = 7 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
 GROUP BY DATE_FORMAT(`date_waste`, '". $format ."')) extruder ON DATE_FORMAT(waste.`date_waste`, '". $format ."') = extruder.date_waste
 LEFT JOIN (
 SELECT DATE_FORMAT(`date_waste`, '". $format ."') AS `date_waste`, SUM(`waste`.`waste`) AS waste
 FROM  `waste`
 JOIN machines m ON waste.machine_id = m.machine_id
 WHERE location_id = 10 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
 GROUP BY DATE_FORMAT(`date_waste`, '". $format ."')) cutting ON DATE_FORMAT(waste.`date_waste`, '". $format ."') = cutting.date_waste
 LEFT JOIN (
 SELECT DATE_FORMAT(`date_waste`, '". $format ."') AS `date_waste`, SUM(`waste`.`waste`) AS waste
 FROM  `waste`
 JOIN machines m ON waste.machine_id = m.machine_id
 WHERE location_id = 11 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
 GROUP BY DATE_FORMAT(`date_waste`, '". $format ."')) packing ON DATE_FORMAT(waste.`date_waste`, '". $format ."') = packing.date_waste
 LEFT JOIN (
 SELECT DATE_FORMAT(`date_waste`, '". $format ."') AS `date_waste`, SUM(`waste`.`waste`) AS waste
 FROM  `waste`
 JOIN machines m ON waste.machine_id = m.machine_id
 WHERE location_id = 6 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
 GROUP BY DATE_FORMAT(`date_waste`, '". $format ."')) injection ON DATE_FORMAT(waste.`date_waste`, '". $format ."') = injection.date_waste
 LEFT JOIN (
 SELECT DATE_FORMAT(`date_waste`, '". $format ."') AS `date_waste`, SUM(`waste`.`waste`) AS waste
 FROM  `waste`
 JOIN machines m ON waste.machine_id = m.machine_id
 WHERE location_id = 8 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
 GROUP BY DATE_FORMAT(`date_waste`, '". $format ."')) packing_bag ON DATE_FORMAT(waste.`date_waste`, '". $format ."') = packing_bag.date_waste
 LEFT JOIN (
 SELECT DATE_FORMAT(`date_waste`, '". $format ."') AS `date_waste`, SUM(`waste`.`waste`) AS waste
 FROM  `waste`
 JOIN machines m ON waste.machine_id = m.machine_id
 WHERE location_id = 2 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
 GROUP BY DATE_FORMAT(`date_waste`, '". $format ."')) multilayer ON DATE_FORMAT(waste.`date_waste`, '". $format ."') = multilayer.date_waste
 LEFT JOIN (
 SELECT DATE_FORMAT(`date_waste`, '". $format ."') AS `date_waste`, SUM(`waste`.`waste`) AS waste
 FROM  `waste`
 JOIN machines m ON waste.machine_id = m.machine_id
 WHERE location_id = 5 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
 GROUP BY DATE_FORMAT(`date_waste`, '". $format ."')) macchi ON DATE_FORMAT(waste.`date_waste`, '". $format ."') = macchi.date_waste
 LEFT JOIN (
 SELECT DATE_FORMAT(`date_waste`, '". $format ."') AS `date_waste`, SUM(`waste`.`waste`) AS waste
 FROM  `waste`
 JOIN machines m ON waste.machine_id = m.machine_id
 WHERE location_id = 3 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
 GROUP BY DATE_FORMAT(`date_waste`, '". $format ."')) printing ON DATE_FORMAT(waste.`date_waste`, '". $format ."') = printing.date_waste
 LEFT JOIN (
 SELECT DATE_FORMAT(`date_waste`, '". $format ."') AS `date_waste`, SUM(`waste`.`waste`) AS waste
 FROM  `waste`
 JOIN machines m ON waste.machine_id = m.machine_id
 WHERE location_id = 4 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
 GROUP BY DATE_FORMAT(`date_waste`, '". $format ."')) slitting ON DATE_FORMAT(waste.`date_waste`, '". $format ."') = slitting.date_waste
WHERE `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(waste.`date_waste`, '". $format ."')
ORDER BY waste.`date_waste`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                if($_POST['searchBy']==2)
                {
                     echo '<tr>
                        <td class="text-right">'. $row['date2'] .'</td>
                        <td class="text-right">'. number_format($row['extruder'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['cutting'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['packing'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['injection'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['packing_bag'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['multilayer'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['macchi'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['printing'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['slitting'],2,'.',',') .'</td>
                        <th class="text-right">'. number_format($row['total'],2,'.',',') .'</th>
                    </tr>';
                }
                else
                {
                    
                echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. number_format($row['extruder'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['cutting'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['packing'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['injection'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['packing_bag'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['multilayer'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['macchi'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['printing'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['slitting'],2,'.',',') .'</td>
                        <th class="text-right">'. number_format($row['total'],2,'.',',') .'</th>
                    </tr>';
                }
                $entrie = array( $row['date'], $row['total']);
                array_push($a,$entrie);
            }
            $stmt->closeCursor();
        }
        else
        {
            echo "<tr>
                    <td>Something went wrong.</td>
                    <td>$db->errorInfo</td>
                </tr>";
        }
        echo '</tbody>';
        echo '<script>document.getElementById("divChart1").setAttribute("class","col-md-12");</script>';
        echo '<script>document.getElementById("chartContainer").style= "height:200px;width:100%";</script>';
         echo '<script> 
            var chart = new CanvasJS.Chart("chartContainer", {
            theme: "light2",
            title: { 
                text: "Waste "
            },
            exportFileName: "Waste",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "Total Process Waste (kgs)"},
            toolTip: {
                shared: true
            },legend:{
                itemclick : toggleDataSeries
            },';
        if($_POST['searchBy']==2)
        {  
            echo 'axisX:{ valueFormatString: "MMM YYYY"},';
        }
        else if($_POST['searchBy']==3)
        {   
            echo 'axisX:{ valueFormatString: "YYYY"},';
        }
        else
        {
            echo 'axisX:{ valueFormatString: "DD MMM"},';
        }
        echo 'data: [
            {
                type: "column",
		showInLegend: true,
		name: "Waste",';
        if($_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if($_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,###.00 Kgs",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
        echo'] }]});
        chart.render(); 
        function toggleDataSeries(e) {
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
                e.dataSeries.visible = false;
            } else {
                e.dataSeries.visible = true;
            }
            chart.render();
        } 
        </script>'; 
    }
    
    
     	/**
     * Loads the Waste Report 
     * This function outputs <tr> tags with the report
     */
    public function reportProduction()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Sacks - Extruder</th>';
        echo '<th>Sacks - Cutting</th>';
        echo '<th>Sacks - Packing</th>';
        echo '<th>Injection</th>';
        echo '<th>Packing Bags</th>';
        echo '<th>Multilayer</th>';
        echo '<th>Macchi</th>';
        echo '<th>Printing</th>';
        echo '<th>Slitting</th>';
        echo '<th>Total Production</th>';
        echo '</tr></thead><tfoot><tr  class="active">
			<th style="text-align:right">Total</th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th></tr></tfoot><tbody>';   
        
        $a=array();
        
        $newDateString = date("Y-m-d");
        $newDateString2 = date("Y-m-d");
        if($_POST['searchBy']==2)
        {    
            if(!empty($_POST['dateSearch']))
            {
               $myDateTime = DateTime::createFromFormat('M/Y', $_POST['dateSearch']);
               $myDateTime->modify('first day of this month');
               $newDateString = $myDateTime->format('Y-m-d');
            }
            if(!empty($_POST['dateSearch2']))
            {
               $myDateTime = DateTime::createFromFormat('M/Y', $_POST['dateSearch2']);
               $myDateTime->modify('last day of this month');
               $newDateString2 = $myDateTime->format('Y-m-d');
            }
            $format = "%m/%Y";
        }
        else if($_POST['searchBy']==3)
        {    
            if(!empty($_POST['dateSearch']))
            {
               $myDateTime = DateTime::createFromFormat('Y', $_POST['dateSearch']);
               $myDateTime->modify('first day of January ' . $_POST['dateSearch']);
               $newDateString = $myDateTime->format('Y-m-d');
            }
            if(!empty($_POST['dateSearch2']))
            {
               $myDateTime = DateTime::createFromFormat('Y', $_POST['dateSearch2']);
               $myDateTime->modify('last day of December ' . $_POST['dateSearch2']);
               $newDateString2 = $myDateTime->format('Y-m-d');
            }
            $format = "%Y";
        }
        else
        {
            if(!empty($_POST['dateSearch']))
            {
               $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch']);
               $newDateString = $myDateTime->format('Y-m-d');
            }
            if(!empty($_POST['dateSearch2']))
            {
               $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch2']);
               $newDateString2 = $myDateTime->format('Y-m-d');
            }
            $format = "%d/%m/%Y";
        }
         $sql = "SELECT DATE_FORMAT(date_report, '". $format ."') as date, DATE_FORMAT(date_report, '%b/%Y') AS date2, extruder.actual as extruder, cutting.actual as cutting, 
packing.actual as packing, injection.actual as injection, packing_bag.actual as packing_bag, multilayer.actual as multilayer, 
COALESCE(macchi_rolls.actual,0) + COALESCE(macchi_shrink.actual,0) as macchi, printing.actual as printing, slitting.actual as slitting
FROM ( 
SELECT ADDDATE('2018-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) date_report
  FROM (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) 
  dateTable
  
LEFT JOIN (
	SELECT DATE_FORMAT(`date_roll`, '". $format ."') as date, SUM(net_weight) as actual  
	FROM sacks_rolls
	WHERE `sacks_rolls`.date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_roll`, '". $format ."')) extruder ON extruder.date = DATE_FORMAT(date_report, '". $format ."')

LEFT JOIN (
	SELECT DATE_FORMAT(`date_sacks`, '". $format ."') as date, SUM(net_weight) as actual  
	FROM cutting_sacks
	WHERE `date_sacks` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_sacks`, '". $format ."')) cutting ON cutting.date = DATE_FORMAT(date_report, '". $format ."')

LEFT JOIN (
	SELECT DATE_FORMAT(`date_sacks`, '". $format ."') as date, SUM(weight) as actual
	FROM packing_sacks
	WHERE `date_sacks` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_sacks`, '". $format ."')) packing  ON packing.date = DATE_FORMAT(date_report, '". $format ."')
    
LEFT JOIN (
	SELECT DATE_FORMAT(`injection_production`.`date_production`, '". $format ."') as date, SUM(`injection_production`.`net_weight`) as actual
	FROM `ups_db`.`injection_production`
	WHERE date_production BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_production`, '". $format ."')) injection  ON injection.date = DATE_FORMAT(date_report, '". $format ."')
    
 LEFT JOIN (
	SELECT DATE_FORMAT(`date_roll`, '". $format ."') as date, SUM(net_weight) as actual 
	FROM packing_rolls
	WHERE `packing_rolls`.date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_roll`, '". $format ."')) packing_bag  ON packing_bag.date = DATE_FORMAT(date_report, '". $format ."')
    
 LEFT JOIN (
	SELECT DATE_FORMAT(`date_roll`, '". $format ."') as date, SUM(net_weight) as actual 
	FROM multilayer_rolls
	WHERE `multilayer_rolls`.date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_roll`, '". $format ."')) multilayer  ON multilayer.date = DATE_FORMAT(date_report, '". $format ."')
     
  LEFT JOIN (
	SELECT DATE_FORMAT(`date_roll`, '". $format ."') AS date, SUM(net_weight) AS actual
	FROM macchi_rolls
	WHERE date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_roll`, '". $format ."')) macchi_rolls  ON macchi_rolls.date = DATE_FORMAT(date_report, '". $format ."')
       
  LEFT JOIN (
	SELECT DATE_FORMAT(`date_shrink`, '". $format ."') AS date, SUM(net_weight) AS actual
	FROM `macchi_shrink`
	WHERE date_shrink BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_shrink`, '". $format ."')) macchi_shrink  ON macchi_shrink.date = DATE_FORMAT(date_report, '". $format ."')
    
  LEFT JOIN (
	SELECT DATE_FORMAT(`date_roll`, '". $format ."') AS date, SUM(`net_weight`) AS actual
	FROM `printing_rolls`
	WHERE date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_roll`, '". $format ."')) printing  ON printing.date = DATE_FORMAT(date_report, '". $format ."')
    
  LEFT JOIN (
	SELECT DATE_FORMAT(`date_roll`, '". $format ."') AS date, SUM(`net_weight`) AS actual
	FROM `slitting_rolls`
	WHERE date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_roll`, '". $format ."')) slitting  ON slitting.date = DATE_FORMAT(date_report, '". $format ."')
     
WHERE date_report BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(date_report, '". $format ."')
ORDER BY date_report;";
        
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $TOTAL  = $row['extruder'] + $row['cutting'] + $row['packing'] + $row['injection'] + $row['packing_bag'] + $row['multilayer'] + $row['macchi'] + $row['printing'] + $row['slitting'];
                if($_POST['searchBy']==2)
                {
                     echo '<tr>
                        <td class="text-right">'. $row['date2'] .'</td>
                        <td class="text-right">'. number_format($row['extruder'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['cutting'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['packing'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['injection'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['packing_bag'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['multilayer'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['macchi'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['printing'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['slitting'],2,'.',',') .'</td>
                        <th class="text-right">'. number_format($TOTAL,2,'.',',') .'</th>
                    </tr>';
                }
                else
                {
                    
                echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. number_format($row['extruder'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['cutting'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['packing'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['injection'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['packing_bag'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['multilayer'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['macchi'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['printing'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['slitting'],2,'.',',') .'</td>
                        <th class="text-right">'. number_format($TOTAL,2,'.',',') .'</th>
                    </tr>';
                }
                $entrie = array( $row['date'], $TOTAL);
                array_push($a,$entrie);
            }
            $stmt->closeCursor();
        }
        else
        {
            echo "<tr>
                    <td>Something went wrong.</td>
                    <td>$db->errorInfo</td>
                </tr>";
        }
        echo '</tbody>';
        echo '<script>document.getElementById("divChart1").setAttribute("class","col-md-12");</script>';
        echo '<script>document.getElementById("chartContainer").style= "height:200px;width:100%";</script>';
         echo '<script> 
            var chart = new CanvasJS.Chart("chartContainer", {
            theme: "light2",
            title: { 
                text: "Waste "
            },
            exportFileName: "Waste",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "Total Process Waste (kgs)"},
            toolTip: {
                shared: true
            },legend:{
                itemclick : toggleDataSeries
            },';
        if($_POST['searchBy']==2)
        {  
            echo 'axisX:{ valueFormatString: "MMM YYYY"},';
        }
        else if($_POST['searchBy']==3)
        {   
            echo 'axisX:{ valueFormatString: "YYYY"},';
        }
        else
        {
            echo 'axisX:{ valueFormatString: "DD MMM"},';
        }
        echo 'data: [
            {
                type: "column",
		showInLegend: true,
		name: "Waste",';
        if($_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if($_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,###.00 Kgs",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
        echo'] }]});
        chart.render(); 
        function toggleDataSeries(e) {
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
                e.dataSeries.visible = false;
            } else {
                e.dataSeries.visible = true;
            }
            chart.render();
        } 
        </script>'; 
    }
    
}


?>
