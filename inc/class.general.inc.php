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
	public function giveCustomers()
	{
		$sql = "SELECT `customers`.`customer_id`,
					`customers`.`customer_name`,
					`customers`.`sachet_rolls`,
					`customers`.`packing_bags`,
					`customers`.`shrink_film`
				FROM `ups_db`.`customers`;";
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
