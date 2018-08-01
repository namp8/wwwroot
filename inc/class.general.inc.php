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
