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
    
    
}


?>
