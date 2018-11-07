<?php

/**
 * Handles user interactions within the slitting section
 * Short Falls
 *
 * PHP version 5
 *
 * @author Natalia Montañez
 * @copyright 2017 Natalia Montañez
 *
 */
class Slitting
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
	
	public function operatorsDropdown()
    {
        $sql = "SELECT `employees`.`employee_id`,
					`employees`.employee_name
				FROM `employees`
				WHERE slitting = 1;
				ORDER BY employee_name";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['employee_id'];
                $NAME = $row['employee_name'];
                echo  '<li><a id="'. $NAME .'" onclick="selectEmployee1(\''. $ID .'\',\''. $NAME .'\')">'. $NAME .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	public function customersDropdown($x)
    {
        $sql = "SELECT `customers`.`customer_id`,`customers`.`customer_name`
        FROM  `customers`
		WHERE sachet_rolls = 1;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['customer_id'];
                $NAME = $row['customer_name'];
                echo  '<li><a id="'. $NAME .'" onclick="selectCustomer'.$x.'(\''. $ID .'\',\''. $NAME .'\')">'. $NAME .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	
	public function giveRollsCustomerDropdown($customer, $i)
    {
        $sql = "SELECT `printing_rolls_id`, `rollno`, gross_weight, net_weight
                FROM  `printing_rolls`
                WHERE status_roll = 0 AND customer_id = ". $customer ." ;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['printing_rolls_id'];
                $ROLLNO = $row['rollno'];
                $GROSS = $row['gross_weight'];
                $NET = $row['net_weight'];
                echo  '<li><a id="'. $ROLLNO .'" onclick="selectRoll(\''. $i .'\',\''. $ID .'\',\''. $ROLLNO .'\',\''. $GROSS .'\',\''. $NET .'\')">'. $ROLLNO .'</a></li>'; 
            }
            
           
            $stmt->closeCursor();
        }
        else
        {
            echo "Something went wrong. ". $db->errorInfo;
        }
    }
	
	public function giveRollsTable($shift)
    {
        $newDateString = date("Y-m-d");
        if(!empty($_POST['dateSearch']))
        {
           $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch']);
           $newDateString = $myDateTime->format('Y-m-d');
        }
        $date = "date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString ." 23:59:59'";
		
		
		$sql = "SELECT  gross_weight, net_weight, null as one, customer_name
FROM slitting_rolls
LEFT JOIN employees one ON one.employee_id = slitting_rolls.employee_id
LEFT JOIN customers ON slitting_rolls.customer_id = customers.customer_id
WHERE ". $date ."
ORDER BY slitting_rolls_id";

		if($shift != 0)
		{
			$sql = "SELECT  gross_weight, net_weight, one.employee_name as one, customer_name
				FROM slitting_rolls
				LEFT JOIN employees one ON one.employee_id = slitting_rolls.employee_id
				LEFT JOIN customers ON slitting_rolls.customer_id = customers.customer_id
				WHERE ". $date ." AND shift = ". $shift ." 
				ORDER BY slitting_rolls_id";
		}
		
		if($stmt = $this->_db->prepare($sql))
		{
				$stmt->execute();
				$entro = false;
				$i = 0;
				$total1 = 0;
				$total2 = 0;
				$output = "";
				while($row = $stmt->fetch())
				{ 
					$i++;
				   if(!$entro)
					{
					   $output = '<tr class="active">
								  <th style="text-align:center">Operator name</th>
								  <th class="text-right">'. $row['one'] .'</th>
								  <th></th>
								  <th></th>
								</tr>';
					   $entro = true;
					}
					$total1 += $row['gross_weight'];
					$total2 += $row['net_weight'];
					$JOB = '';
					if(!is_null($row['customer_name']))
					{
						$JOB = $row['customer_name'];
					}
					$output = $output . '<tr>
								  <td style="text-align:center">'. $i .'</th>
								  <td class="text-right">'. number_format($row['gross_weight'],2,'.',',') .'</th>
								  <td class="text-right">'. number_format($row['net_weight'],2,'.',',') .'</th>
								  <td style="text-align:center">'.  $JOB .'</th>
								</tr>';
					
				}
				echo '
								<tr class="active">
								  <th style="text-align:center">Total</th>
								  <th class="text-right">'. number_format($total1,2,'.',',') .'</th>
								  <th class="text-right">'. number_format($total2,2,'.',',') .'</th>
								  <th class="text-right">'. number_format($i,2,'.',',') .' Rolls</th>
								</tr>';
				echo $output;
		}
		
	 }
	
	public function giveInputRollsTable($shift)
    {
        $newDateString = date("Y-m-d");
        if(!empty($_POST['dateSearch']))
        {
           $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch']);
           $newDateString = $myDateTime->format('Y-m-d');
        }
        $date = "date_slitting IS NOT NULL AND date_slitting BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString ." 23:59:59'";
		
		
		$sql = "SELECT rollno, gross_weight, net_weight, used_weight
				FROM printing_rolls
				WHERE ". $date ."
				ORDER BY printing_rolls_id";

		if($shift != 0)
		{
			$sql = "SELECT rollno, gross_weight, net_weight, used_weight
					FROM printing_rolls
					WHERE ". $date ." AND `shift_slitting` = ". $shift ."
					ORDER BY printing_rolls_id ";
		}
		
		if($stmt = $this->_db->prepare($sql))
		{
				$stmt->execute();
				$total1 = 0;
				$total2 = 0;
				$total3 = 0;
				$output = "";
				while($row = $stmt->fetch())
				{ 
					$total1 += $row['gross_weight'];
					$total2 += $row['net_weight'];
					$total3 += $row['used_weight'];
					
					$output = $output . '<tr>
								  <td style="text-align:center">'. $row['rollno'] .'</th>
								  <td class="text-right">'. number_format($row['gross_weight'],2,'.',',') .'</th>
								  <td class="text-right">'. number_format($row['net_weight'],2,'.',',') .'</th>
								  <td class="text-right">'. number_format($row['used_weight'],2,'.',',') .'</th>
								</tr>';
					
				}
				echo '
								<tr class="active">
								  <th style="text-align:center">Total</th>
								  <th class="text-right">'. number_format($total1,2,'.',',') .'</th>
								  <th class="text-right">'. number_format($total2,2,'.',',') .'</th>
								  <th class="text-right">'. number_format($total3,2,'.',',') .'</th>
								</tr>';
				echo $output;
		}
		
	 }
	
	 public function createInputRolls()
    {
         
        $date = $shift = $rollid = $rollid2 = $balance = $balance1 = "";
		
        
        $rollid = trim($_POST["rollid"]);
        $rollid = stripslashes($rollid);
        $rollid = htmlspecialchars($rollid);
		
        $rollid2 = trim($_POST["rollid2"]);
        $rollid2 = stripslashes($rollid2);
        $rollid2 = htmlspecialchars($rollid2);
        
        $shift = trim($_POST["shift"]);
        $shift = stripslashes($shift);
        $shift = htmlspecialchars($shift);
		 
        $balance1 = trim($_POST["balance1"]);
        $balance1 = stripslashes($balance1);
        $balance1 = htmlspecialchars($balance1);
		 
		$balance2 = trim($_POST["balance2"]);
        $balance2 = stripslashes($balance2);
        $balance2 = htmlspecialchars($balance2);
		 
        $date = date("Y-m-d");
        if($shift == 2)
        {
            $date = date("Y-m-d", time() - 60 * 60 * 24);
        }
        if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = $newDateString;
        }
        
       
		$sql = "UPDATE `printing_rolls`
SET
`status_roll` = 1,
`date_slitting` = '". $date ."',
`shift_slitting` = '". $shift ."', 
`used_weight` = `net_weight` - ". $balance1 ."
WHERE `printing_rolls_id` = ". $rollid .";";

		if(!is_null($rollid2))
		{
			$sql = $sql . "UPDATE `printing_rolls`
SET
`status_roll` = 1,
`date_slitting` = '". $date ."',
`shift_slitting` = '". $shift ."', 
`used_weight` = `net_weight` - ". $balance2 ."
WHERE `printing_rolls_id` = ". $rollid2 .";";

		}
		 
		try
		{   
			$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			$stmt->closeCursor();

			echo '<strong>SUCCESS!</strong> The input roll was successfully added to the database for the shift: <strong>'. $this->giveShiftname($shift) .'</strong>';
			return TRUE;
		} catch (PDOException $e) {
			if ($e->getCode() == 23000) {
			  echo '<strong>ERROR</strong> The input roll has already being register for the shift: <strong>'. $this->giveShiftname($shift) .'</strong>.<br>';
			} else {
			  echo '<strong>ERROR</strong> Could not insert the roll into the database. Please try again.<br>'. $e->getMessage();
			}

			return FALSE;
		} 
        
    }
	
	public function createOutputRolls()
    {
        
      	$shift  = $employee1 = $customer = "";
		
        $shift = trim($_POST["shift"]);
        $shift = stripslashes($shift);
        $shift = htmlspecialchars($shift);
		
				
		$customer = trim($_POST["customer"]);
        $customer = stripslashes($customer);
        $customer = htmlspecialchars($customer);
		if(empty($_POST['customer']))
        {
			$customer = 'NULL';
		}
		
		$machine = trim($_POST["machine"]);
        $machine = stripslashes($machine);
        $machine = htmlspecialchars($machine);
		
		$employee1 = trim($_POST["employee1"]);
        $employee1 = stripslashes($employee1);
        $employee1 = htmlspecialchars($employee1);
		if(empty($_POST['employee1']))
        {
			$employee1 = 'NULL';
		}
		
		
        //DATE
        $date = date("Y-m-d");
        if($shift == 2)
        {
            $date = date("Y-m-d", time() - 60 * 60 * 24);
        }
        if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = $newDateString;
        }
		
		$CONE = 0;
        $sql = "SELECT `settings`.`value_setting`
                FROM  `settings` 
                WHERE machine_id=32 AND name_setting='330cone';";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $CONE = $row['value_setting'];
            }
        }
		
		// GETS ROLL NO 
	   $sql = "SELECT COUNT(DISTINCT(rollno)) as rollcount
				FROM `slitting_rolls` WHERE date_roll BETWEEN '". $date ." 00:00:00' AND '". $date ." 23:59:59';";
		$count = 0;
		if($stmt = $this->_db->prepare($sql))
		{
			$stmt->execute();

			$row = $stmt->fetch();
			$count = $row['rollcount']  ;
			$stmt->closeCursor();

		}
		
		$myDateTime = DateTime::createFromFormat('Y-m-d', $date);
		$newDateString = $myDateTime->format('d-m');
		
		$totalnet = 0;
		
		$rolls = "INSERT INTO `slitting_rolls`
(`slitting_rolls_id`,`date_roll`,`rollno`,`shift`,`size`,`gross_weight`,`net_weight`,`user_id`,`status_roll`,`customer_id`,`employee_id`,`machine_id`)
VALUES";
		foreach ($_POST as $k=>$v)
		{
			if (substr( $k, 0, 3 ) === "wt_" and !empty($v)){
				$net = $v - $CONE;
				$totalnet = $totalnet + $net;
				$count = $count + 1;
				$rollno = $newDateString."-".$count;
				$rolls = $rolls. " (NULL, '". $date."', '". $rollno."',". $shift .",0, ". $v .", ". $net .", ". $_SESSION['Userid'] .",0 , ". $customer.",". $employee1 .",". $machine .") ,";
			}
		}
		
			$sql = substr($rolls,0,strlen($rolls)-2). "; ";
			try {   
				$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
				$stmt->closeCursor();
				echo '<strong>SUCCESS!</strong> The rolls were successfully added to the database for the shift: <strong>'. $this->giveShiftname($shift) .'</strong>';
				return TRUE;
			}
			catch (PDOException $e) {
				echo '<strong>ERROR</strong> Could not insert the rolls into the database. Please try again.<br>'. $e->getMessage();
				return FALSE;
			}
			}
		
		 public function giveShiftname($shift)
		{
			$shiftname = "";
			if($shift == 1)
			{
				$shiftname = "DAY";
			}
			else if($shift == 2)
			{
				$shiftname = "NIGHT";
			}
			return $shiftname;
		}
	
	public function giveWaste()
    {
		$location = 4;
		$sql = "SELECT 
    machine_name,
    `waste`.`date_waste`,
    `waste`.`shift`,
    `waste`.`waste` as print, slitting_waste.waste as slitting, trim_waste.waste as trim,
    username
FROM
    `waste`
NATURAL JOIN
    users
NATURAL JOIN
    machines
LEFT JOIN
    `waste` slitting_waste ON `waste`.date_waste = slitting_waste.date_waste
        AND `waste`.machine_id = slitting_waste.machine_id
        AND `waste`.shift = slitting_waste.shift
        AND slitting_waste.type = 2 
LEFT JOIN
    `waste` trim_waste ON `waste`.date_waste = trim_waste.date_waste
        AND `waste`.machine_id = trim_waste.machine_id
        AND `waste`.shift = trim_waste.shift
        AND trim_waste.type = 3 
WHERE
	`waste`.type = 1 AND location_id = ". $location." AND MONTH(`waste`.date_waste) >= MONTH(CURRENT_DATE())-1 AND YEAR(`waste`.date_waste) = YEAR(CURRENT_DATE())
ORDER BY `waste`.date_waste DESC,  `waste`.`shift` DESC, `waste`.machine_id;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $DATE = $row['date_waste'];
                $USER = $row['username'];
                $SHIFT = $this->giveShiftname($row['shift']);
                $TOTAL = $row['print'] + $row['slitting']+ $row['trim'];
                echo '<tr>
                        <td>'. $DATE .'</td>
                        <td>'. $SHIFT .'</td>
                        <td>'. $row['machine_name'] .'</td>
                        <td>'. $USER .'</td>
                        <td class="text-right">'. number_format($row['print'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['slitting'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['trim'],2,'.',',') .'</td>
                        <th class="text-right">'. number_format($TOTAL,2,'.',',') .'</th>
                    </tr>';
                }
            
            $stmt->closeCursor();
        }
        else
        {
            echo "<tr>
                    <td>Something went wrong.</td>
                    <td>$db->errorInfo</td>
                    <td></td>
                </tr>";
        }
    }
	 public function createWaste()
    {
        $machine = $shift = $total = "";
        
        $machine = trim($_POST["machine"]);
        $machine = stripslashes($machine);
        $machine = htmlspecialchars($machine);
		
        $shift = trim($_POST["shift"]);
        $shift = stripslashes($shift);
        $shift = htmlspecialchars($shift);
        
        $print = trim($_POST["print"]);
        $print = stripslashes($print);
        $print = htmlspecialchars($print); 
		
        $slitting = trim($_POST["slitting"]);
        $slitting = stripslashes($slitting);
        $slitting = htmlspecialchars($slitting);
		 
		$trim = trim($_POST["trim"]);
        $trim = stripslashes($trim);
        $trim = htmlspecialchars($trim); 
		
		
		
		$total = $trim + $print + $slitting;
        
        //DATE
        $date = date("Y-m-d");
        if($shift == 2)
        {
            $date = date("Y-m-d", time() - 60 * 60 * 24);
        }
		if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = $newDateString;
        }
        
        
            
		//INSERT THE WASTE IN THE DAY DECREASES THE  KGS FROM THE  MULTILAYER_BATCHES_STOCK 
		$sql = "INSERT INTO  `waste`(`waste_id`,`date_waste`,`shift`,`machine_id`,`waste`,`user_id`,`type`) VALUES (NULL,'". $date."', ". $shift .",". $machine .", ". $print .", ". $_SESSION['Userid'] .", 1), (NULL,'". $date."', ". $shift .",". $machine .", ". $slitting .", ". $_SESSION['Userid'] .", 2), (NULL,'". $date."', ". $shift .",". $machine .", ". $trim .", ". $_SESSION['Userid'] .", 3);";
		try
		{   
			$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
				$stmt->closeCursor();
				echo '<strong>SUCCESS!</strong> The waste were successfully added to the database for the shift: <strong>'. $this->giveShiftname($shift) .'</strong>';
				return TRUE;
			} 
		catch (PDOException $e) {
			if ($e->getCode() == 23000) {
				echo '<strong>ERROR</strong> The waste have already being register for the shift: <strong>'. $this->giveShiftname($shift) .'</strong>.<br>';
			} 
			else {
				echo '<strong>ERROR</strong> Could not insert the waste into the database. Please try again.<br>'. $e->getMessage();
			}
			return FALSE;
		}
	}
    
}

?>