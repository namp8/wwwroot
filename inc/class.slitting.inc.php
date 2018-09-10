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
	public function customersDropdown()
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
                echo  '<li><a id="'. $NAME .'" onclick="selectCustomer(\''. $ID .'\',\''. $NAME .'\')">'. $NAME .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
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
				while($row = $stmt->fetch())
				{ 
					$i++;
				   if(!$entro)
					{
						echo '<tr class="active">
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
					echo '<tr>
								  <td style="text-align:center">'. $i .'</th>
								  <td class="text-right">'. number_format($row['gross_weight'],2,'.',',') .'</th>
								  <td class="text-right">'. number_format($row['net_weight'],2,'.',',') .'</th>
								  <td style="text-align:center">'.  $JOB .'</th>
								</tr>';
					
				}
				echo '
								<tr class="active">
								  <th style="text-align:center">Total.</th>
								  <th class="text-right">'. number_format($total1,2,'.',',') .'</th>
								  <th class="text-right">'. number_format($total2,2,'.',',') .'</th>
								  <th class="text-right">'. number_format($i,2,'.',',') .' Rolls</th>
								</tr>';
		}
		
	 }
	
	public function createRolls()
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
		
		$totalnet = 0;
		
		$rolls = "INSERT INTO `ups_db`.`slitting_rolls`
(`slitting_rolls_id`,`date_roll`,`rollno`,`shift`,`size`,`gross_weight`,`net_weight`,`user_id`,`status_roll`,`customer_id`)
VALUES";
		foreach ($_POST as $k=>$v)
		{
			if (substr( $k, 0, 3 ) === "wt_" and !empty($v)){
				$net = $v;
				$totalnet = $totalnet + $net;
				$rolls = $rolls. " (NULL, '". $date."', ". $shift .", ". $v .", ". $net .", ". $_SESSION['Userid'] .", ". $employee1 .", ". $size .",". $color.", ". $customer.") ,";
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
}

?>