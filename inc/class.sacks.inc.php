<?php

/**
 * Handles user interactions within the sacks section
 *
 * PHP version 5
 *
 * @author Natalia Montañez
 * @copyright 2017 Natalia Montañez
 *
 */
class Sacks
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
    
	public function admin()
	{
		$sql = "SELECT admin
				FROM `users`
				WHERE user_id = ". $_SESSION['Userid'];
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                if($row['admin'] == 1)
				{
					return true;
				}
				else
				{
					return false;
				}
            }
            $stmt->closeCursor();
        }
        else
        {
            return false;  
        }
	}
	
     /**
     * Loads the dropdown of all the materials
     *
     * This function outputs <li> tags with materials
     */
    public function materialsDropdown()
    {
        $sql = "SELECT `materials`.`material_id`,
                `materials`.`material_name`,
                `materials`.`material_grade`
                FROM `materials`
				WHERE `sacks` = 1 AND `material` = 1
                ORDER BY `materials`.`material_name`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                echo  '<li><a id="'. $NAME .'" onclick="selectMaterial(\''. $ID .'\',\''. $NAME .'\',\''. $GRADE .'\')"><b>'. $NAME .'</b>&nbsp - &nbsp'. $GRADE .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	
	 public function operatorsDropdown($x)
    {
        $sql = "SELECT `employees`.`employee_id`,
					`employees`.employee_name
				FROM `ups_db`.`employees`
				WHERE sacks = 1
				ORDER BY employee_name";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['employee_id'];
                $NAME = $row['employee_name'];
                echo  '<li><a id="'. $NAME .'" onclick="selectEmployee'.$x.'(\''. $ID .'\',\''. $NAME .'\')">'. $NAME .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	
	
	/**
     * Loads the dropdown of all the materials
     *
     * This function outputs <li> tags with materials
     */
    public function consumablesDropdown()
    {
        $sql = "SELECT `materials`.`material_id`,`materials`.`material_name`,`materials`.`material_grade`
                FROM  `materials`
				WHERE `sacks` = 1 AND `consumables` = 1
                ORDER BY `materials`.`material_name`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                echo  '<li><a id="'. $NAME .'" onclick="selectConsumable(\''. $ID .'\',\''. $NAME .'\')">'. $NAME .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
    
    
	/**
     * Loads the table of all the rolls
     * This function outputs <tr> tags with rolls
     * Parameter= ID of the shift ALL DAY=0 MORNING=1 NIGHT=2
     */
    public function giveRolls($shift, $machine)
    {
        $newDateString = date("Y-m-d");
        if(!empty($_POST['dateSearch']))
        {
           $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch']);
           $newDateString = $myDateTime->format('Y-m-d');
        }
        $date = "date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString ." 23:59:59'";

        $sql = "SELECT  gross_weight, net_weight
FROM `sacks_rolls` 
WHERE machine_id = ". $machine ."  AND ". $date ."
ORDER BY sacks_rolls_id";

        if($shift != 0)
        {
            $sql = "SELECT  gross_weight, net_weight
FROM `sacks_rolls` 
WHERE machine_id = ". $machine ."  AND ". $date ." AND shift = ". $shift ." ORDER BY sacks_rolls_id";
        }
        
        $total1 = $total2 =$i = 0;
                
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {   
				
				$i ++;
                $total1 = $total1 + $row['gross_weight'];
                $total2 = $total2 + $row['net_weight'];
                echo '<tr>
                        <td>'.  $i .'</td>                    
                        <td class="text-right">'. number_format($row['gross_weight'],2,'.',',') .'</td>
                        
                       </tr>';
            }
			$net = $total1 - $total2;
			if($i > 0)
			{
				
            echo '
              <tfoot>
                <tr class="active">
                  <th style="text-align:center">Weight of Rolls</th>
                  <th class="text-right">'. number_format($total1,2,'.',',') .'</th>
                </tr>
				<tr >
                  <th style="text-align:center">Rolls produced</th>
                  <th class="text-right">'. number_format($i,2,'.',',') .'</th>
                </tr>
				<tr >
                  <th style="text-align:center">Cone weight</th>
                  <th class="text-right">'. number_format($net,2,'.',',') .'</th>
                </tr>
				<tr class="active">
                  <th style="text-align:center">Good production</th>
                  <th class="text-right">'. number_format($total2,2,'.',',') .'</th>
                </tr>
              </tfoot>';
			}
            $stmt->closeCursor();
            
        }
        else
        {
            echo "<tr>
                    <td>Something went wrong</td>
                    <td>$db->errorInfo</td>
                    <td></td>
                    <td></td>
                </tr>";
        }
        
    }
	
	/**
     * Loads the table of all the rolls
     * This function outputs <tr> tags with rolls
     * Parameter= ID of the shift ALL DAY=0 MORNING=1 NIGHT=2
     */
    public function giveRollsTable($shift)
    {
        $newDateString = date("Y-m-d");
        if(!empty($_POST['dateSearch']))
        {
           $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch']);
           $newDateString = $myDateTime->format('Y-m-d');
        }
        $date = "date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString ." 23:59:59'";
		
		$machine1 = array();
		$machine2 = array();
		$machine3 = array();
		$machine4 = array();
		$machine5 = array();
		$machine6 = array();
		$machine7 = array();
		$machine8 = array();
		
		$machine1t = array();
		$machine2t = array();
		$machine3t = array();
		$machine4t = array();
		$machine5t = array();
		$machine6t = array();
		$machine7t = array();
		$machine8t = array();
		
		$t1 = $t2 = $t3 =$t4 = 0;
		
		
		for($machine = 13; $machine<21; ++$machine) 
		{ 
			$sql = "SELECT  gross_weight, net_weight
			FROM `sacks_rolls` 
			WHERE machine_id = ". $machine ."  AND ". $date ."
			ORDER BY sacks_rolls_id";

			if($shift != 0)
			{
				$sql = "SELECT  gross_weight, net_weight
						FROM `sacks_rolls` 
						WHERE machine_id = ". $machine ." AND ". $date ." AND shift = ". $shift ." ORDER BY sacks_rolls_id";
			}
			$total1 = $total2 =$i = 0;
                
			if($stmt = $this->_db->prepare($sql))
			{
				$stmt->execute();
				while($row = $stmt->fetch())
				{   

					$i ++;
					$total1 = $total1 + $row['gross_weight'];
					$total2 = $total2 + $row['net_weight'];
					
					if($machine == 13)
					{
						array_push($machine1,number_format($row['gross_weight'],2,'.',','));
					}
					else if($machine == 14)
					{
						array_push($machine2,number_format($row['gross_weight'],2,'.',','));
					}
					else if($machine == 15)
					{
						array_push($machine3,number_format($row['gross_weight'],2,'.',','));
					}
					else if($machine == 16)
					{
						array_push($machine4,number_format($row['gross_weight'],2,'.',','));
					}
					else if($machine == 17)
					{
						array_push($machine5,number_format($row['gross_weight'],2,'.',','));
					}
					else if($machine == 18)
					{
						array_push($machine6,number_format($row['gross_weight'],2,'.',','));
					}
					else if($machine == 19)
					{
						array_push($machine7,number_format($row['gross_weight'],2,'.',','));
					}
					else if($machine == 20)
					{
						array_push($machine8,number_format($row['gross_weight'],2,'.',','));
					}
					
				}
				$net = $total1 - $total2;
				if($i > 0)
				{
					$t1 += $total1;
					$t2 += $i;
					$t3 += $net;
					$t4 += $total2;
					if($machine == 13)
					{
						array_push($machine1t,number_format($total1,2,'.',','));
						array_push($machine1t,number_format($i,2,'.',','));
						array_push($machine1t,number_format($net,2,'.',','));
						array_push($machine1t,number_format($total2,2,'.',','));
					}
					else if($machine == 14)
					{
						array_push($machine2t,number_format($total1,2,'.',','));
						array_push($machine2t,number_format($i,2,'.',','));
						array_push($machine2t,number_format($net,2,'.',','));
						array_push($machine2t,number_format($total2,2,'.',','));
					}
					else if($machine == 15)
					{
						array_push($machine3t,number_format($total1,2,'.',','));
						array_push($machine3t,number_format($i,2,'.',','));
						array_push($machine3t,number_format($net,2,'.',','));
						array_push($machine3t,number_format($total2,2,'.',','));
					}
					else if($machine == 16)
					{
						array_push($machine4t,number_format($total1,2,'.',','));
						array_push($machine4t,number_format($i,2,'.',','));
						array_push($machine4t,number_format($net,2,'.',','));
						array_push($machine4t,number_format($total2,2,'.',','));
					}
					else if($machine == 17)
					{
						array_push($machine5t,number_format($total1,2,'.',','));
						array_push($machine5t,number_format($i,2,'.',','));
						array_push($machine5t,number_format($net,2,'.',','));
						array_push($machine5t,number_format($total2,2,'.',','));
					}
					else if($machine == 18)
					{
						array_push($machine6t,number_format($total1,2,'.',','));
						array_push($machine6t,number_format($i,2,'.',','));
						array_push($machine6t,number_format($net,2,'.',','));
						array_push($machine6t,number_format($total2,2,'.',','));
					}
					else if($machine == 19)
					{
						array_push($machine7t,number_format($total1,2,'.',','));
						array_push($machine7t,number_format($i,2,'.',','));
						array_push($machine7t,number_format($net,2,'.',','));
						array_push($machine7t,number_format($total2,2,'.',','));
					}
					else if($machine == 20)
					{
						array_push($machine8t,number_format($total1,2,'.',','));
						array_push($machine8t,number_format($i,2,'.',','));
						array_push($machine8t,number_format($net,2,'.',','));
						array_push($machine8t,number_format($total2,2,'.',','));
					}
				}
				else
				{
					if($machine == 13)
					{
						array_push($machine1t,'');
						array_push($machine1t,'');
						array_push($machine1t,'');
						array_push($machine1t,'');
					}
					else if($machine == 14)
					{
						array_push($machine2t,'');
						array_push($machine2t,'');
						array_push($machine2t,'');
						array_push($machine2t,'');
					}
					else if($machine == 15)
					{
						array_push($machine3t,'');
						array_push($machine3t,'');
						array_push($machine3t,'');
						array_push($machine3t,'');
					}
					else if($machine == 16)
					{
						array_push($machine4t,'');
						array_push($machine4t,'');
						array_push($machine4t,'');
						array_push($machine4t,'');
					}
					else if($machine == 17)
					{
						array_push($machine5t,'');
						array_push($machine5t,'');
						array_push($machine5t,'');
						array_push($machine5t,'');
					}
					else if($machine == 18)
					{
						array_push($machine6t,'');
						array_push($machine6t,'');
						array_push($machine6t,'');
						array_push($machine6t,'');
					}
					else if($machine == 19)
					{
						array_push($machine7t,'');
						array_push($machine7t,'');
						array_push($machine7t,'');
						array_push($machine7t,'');
					}
					else if($machine == 20)
					{
						array_push($machine8t,'');
						array_push($machine8t,'');
						array_push($machine8t,'');
						array_push($machine8t,'');
					}
				}
				$stmt->closeCursor();
			}
		}
		echo '<tr class="text-center">
				<th class="text-center">Weight of Rolls</th>
				<td>'. $machine1t[0] .'</td>
				<td>'. $machine2t[0] .'</td>
				<td>'. $machine3t[0] .'</td>
				<td>'. $machine4t[0] .'</td>
				<td>'. $machine5t[0] .'</td>
				<td>'. $machine6t[0] .'</td>
				<td>'. $machine7t[0] .'</td>
				<td>'. $machine8t[0] .'</td>
				<th class="text-center">'. $t1 .'</th>
			</tr>';
		echo '<tr class="text-center">
				<th class="text-center">No. of Rolls produced</th>
				<td>'. $machine1t[1] .'</td>
				<td>'. $machine2t[1] .'</td>
				<td>'. $machine3t[1] .'</td>
				<td>'. $machine4t[1] .'</td>
				<td>'. $machine5t[1] .'</td>
				<td>'. $machine6t[1] .'</td>
				<td>'. $machine7t[1] .'</td>
				<td>'. $machine8t[1] .'</td>
				<th class="text-center">'. $t2 .'</th>
			</tr>';
        echo '<tr class="text-center">
				<th class="text-center">Cone weight</th>
				<td>'. $machine1t[2] .'</td>
				<td>'. $machine2t[2] .'</td>
				<td>'. $machine3t[2] .'</td>
				<td>'. $machine4t[2] .'</td>
				<td>'. $machine5t[2] .'</td>
				<td>'. $machine6t[2] .'</td>
				<td>'. $machine7t[2] .'</td>
				<td>'. $machine8t[2] .'</td>
				<th class="text-center">'. $t3.'</th>
			</tr>';
        echo '<tr class="text-center">
				<th class="text-center">Good production</th>
				<td>'. $machine1t[3] .'</td>
				<td>'. $machine2t[3] .'</td>
				<td>'. $machine3t[3] .'</td>
				<td>'. $machine4t[3] .'</td>
				<td>'. $machine5t[3] .'</td>
				<td>'. $machine6t[3] .'</td>
				<td>'. $machine7t[3] .'</td>
				<td>'. $machine8t[3] .'</td>
				<th class="text-center">'. $t4 .'</th>
			</tr>';
		 echo '<tr class="active text-center">
				<th class="text-center">Roll Weight</th>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>';
		
		for($i = 0; $i<60; ++$i) 
		{
			$entro = false;
			$count = $i + 1;
			$string = '<tr class="text-center">
				<th class="text-center">'. $count .'</th>';
			
			if(sizeof($machine1)> $i)
			{
				$options = "";
				if($this->admin())
				{
					$options = '&nbsp&nbsp&nbsp<button class="btn btn-xs btn-warning" type="button" onclick="edit()"><i class="fa fa-pencil" aria-hidden="true"></i></button>&nbsp<button class="btn btn-xs btn-danger" type="button" onclick="deleteEntry()">X</button>';
				}
				$string = $string. '<td>'. $machine1[$i]. $options .'</td>';
				$entro = true;
			}
			else
			{
				$string = $string. '<td></td>';
			}
			if(sizeof($machine2)> $i)
			{
				$options = "";
				if($this->admin())
				{
					$options = '<button class="btn btn-xs btn-warning" type="button" onclick="edit()"><i class="fa fa-pencil" aria-hidden="true"></i></button>&nbsp<button class="btn btn-xs btn-danger" type="button" onclick="deleteEntry()">X</button>&nbsp&nbsp';
				}
				$string = $string. '<td>'. $machine2[$i]. $options .'</td>';
				$entro = true;
			}
			else
			{
				$string = $string.  '<td></td>';
			}
			if(sizeof($machine3)> $i)
			{
				$string = $string.  '<td>'. $machine3[$i] .'</td>';
				$entro = true;
			}
			else
			{
				$string = $string.  '<td></td>';
			}
			if(sizeof($machine4)> $i)
			{
				$string = $string.  '<td>'. $machine4[$i] .'</td>';
				$entro = true;
			}
			else
			{
				$string = $string.  '<td></td>';
			}
			if(sizeof($machine5)> $i)
			{
				$string = $string.  '<td>'. $machine5[$i] .'</td>';
				$entro = true;
			}
			else
			{
				$string = $string.  '<td></td>';
			}
			if(sizeof($machine6)> $i)
			{
				$string = $string.  '<td>'. $machine6[$i] .'</td>';
				$entro = true;
			}
			else
			{
				$string = $string.  '<td></td>';
			}
			if(sizeof($machine7)> $i)
			{
				$string = $string.  '<td>'. $machine7[$i] .'</td>';
				$entro = true;
			}
			else
			{
				$string = $string.  '<td></td>';
			}
			if(sizeof($machine8)> $i)
			{
				$string = $string.  '<td>'. $machine8[$i] .'</td>';
				$entro = true;
			}
			else
			{
				$string = $string.  '<td></td>';
			}
			$string = $string.  '<td></td>';
			$string = $string.  '</tr>';
			if($entro)
			{
				echo $string;
			}
			else
			{
				$i = 100;
			}
		}
		
        
    }
	
	
	
	 public function giveSacksTable($shift)
    {
        $newDateString = date("Y-m-d");
        if(!empty($_POST['dateSearch']))
        {
           $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch']);
           $newDateString = $myDateTime->format('Y-m-d');
        }
        $date = "date_sacks BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString ." 23:59:59'";
		
		$machine1 = array();
		$machine2 = array();
		$machine3 = array();
		$machine4 = array();
		$machine5 = array();
		$machine6 = array();
		$machine7 = array();
		$machine8 = array();
		$machine9 = array();
		
		$machine1t = array();
		$machine2t = array();
		$machine3t = array();
		$machine4t = array();
		$machine5t = array();
		$machine6t = array();
		$machine7t = array();
		$machine8t = array();
		$machine9t = array();
		 
		
		$t1 = $t2 = $t3 =$t4 =$t5= 0;
		
		for($machine = 21; $machine<30; ++$machine) 
		{ 
			$sql = "SELECT  gross_weight, net_weight, null as one, null as two
FROM cutting_sacks 
LEFT JOIN employees one ON one.employee_id = cutting_sacks.employee_id
LEFT JOIN employees two ON two.employee_id = cutting_sacks.employee_id2
WHERE machine_id = ". $machine ."  AND ". $date ."
ORDER BY cutting_sacks_id";

			if($shift != 0)
			{
				$sql = "SELECT  gross_weight, net_weight, one.employee_name as one, two.employee_name as two
					FROM cutting_sacks
					LEFT JOIN employees one ON one.employee_id = cutting_sacks.employee_id
					LEFT JOIN employees two ON two.employee_id = cutting_sacks.employee_id2
					WHERE machine_id = ". $machine ."  AND ". $date ." AND shift = ". $shift ." ORDER BY cutting_sacks_id";
			}
			$total1 = $total2 =$i = 0;
                
			if($stmt = $this->_db->prepare($sql))
			{
				$stmt->execute();
				while($row = $stmt->fetch())
				{   

					$i ++;
					$total1 = $total1 + $row['gross_weight'];
					$total2 = $total2 + $row['net_weight'];
					$employee = '';
					if(!empty($row['one']))
					{
						$employee = $row['one'];
					}
					if(!empty($row['two']))
					{
						$employee = $employee . ' / ' . $row['two'];
					}
					
					if($machine == 21)
					{
						array_push($machine1,number_format($row['gross_weight'],2,'.',','));
					}
					else if($machine == 22)
					{
						array_push($machine2,number_format($row['gross_weight'],2,'.',','));
					}
					else if($machine == 23)
					{
						array_push($machine3,number_format($row['gross_weight'],2,'.',','));
					}
					else if($machine == 24)
					{
						array_push($machine4,number_format($row['gross_weight'],2,'.',','));
					}
					else if($machine == 25)
					{
						array_push($machine5,number_format($row['gross_weight'],2,'.',','));
					}
					else if($machine == 26)
					{
						array_push($machine6,number_format($row['gross_weight'],2,'.',','));
					}
					else if($machine == 27)
					{
						array_push($machine7,number_format($row['gross_weight'],2,'.',','));
					}
					else if($machine == 28)
					{
						array_push($machine8,number_format($row['gross_weight'],2,'.',','));
					}
					else if($machine == 29)
					{
						array_push($machine9,number_format($row['gross_weight'],2,'.',','));
					}
					
				}
				$sacks = $i * 0.12;
				$label = $total1 *0.00195;
				if($i > 0)
				{
					
					$t1 += $total1;
					$t2 += $i;
					$t3 += $sacks;
					$t4 += $label;
					$t5 += $total2;
					if($machine == 21)
					{
						array_push($machine1t,number_format($total1,2,'.',','));
						array_push($machine1t,number_format($i,2,'.',','));
						array_push($machine1t,number_format($sacks,2,'.',','));
						array_push($machine1t,number_format($label,2,'.',','));
						array_push($machine1t,number_format($total2,2,'.',','));
						array_push($machine1t,$employee);
					}
					else if($machine == 22)
					{
						array_push($machine2t,number_format($total1,2,'.',','));
						array_push($machine2t,number_format($i,2,'.',','));
						array_push($machine2t,number_format($sacks,2,'.',','));
						array_push($machine2t,number_format($label,2,'.',','));
						array_push($machine2t,number_format($total2,2,'.',','));
						array_push($machine2t,$employee);
					}
					else if($machine == 23)
					{
						array_push($machine3t,number_format($total1,2,'.',','));
						array_push($machine3t,number_format($i,2,'.',','));
						array_push($machine3t,number_format($sacks,2,'.',','));
						array_push($machine3t,number_format($label,2,'.',','));
						array_push($machine3t,number_format($total2,2,'.',','));
						array_push($machine3t,$employee);
					}
					else if($machine == 24)
					{
						array_push($machine4t,number_format($total1,2,'.',','));
						array_push($machine4t,number_format($i,2,'.',','));
						array_push($machine4t,number_format($sacks,2,'.',','));
						array_push($machine4t,number_format($label,2,'.',','));
						array_push($machine4t,number_format($total2,2,'.',','));
						array_push($machine4t,$employee);
					}
					else if($machine == 25)
					{
						array_push($machine5t,number_format($total1,2,'.',','));
						array_push($machine5t,number_format($i,2,'.',','));
						array_push($machine5t,number_format($sacks,2,'.',','));
						array_push($machine5t,number_format($label,2,'.',','));
						array_push($machine5t,number_format($total2,2,'.',','));
						array_push($machine5t,$employee);
					}
					else if($machine == 26)
					{
						array_push($machine6t,number_format($total1,2,'.',','));
						array_push($machine6t,number_format($i,2,'.',','));
						array_push($machine6t,number_format($sacks,2,'.',','));
						array_push($machine6t,number_format($label,2,'.',','));
						array_push($machine6t,number_format($total2,2,'.',','));
						array_push($machine6t,$employee);
					}
					else if($machine == 27)
					{
						array_push($machine7t,number_format($total1,2,'.',','));
						array_push($machine7t,number_format($i,2,'.',','));
						array_push($machine7t,number_format($sacks,2,'.',','));
						array_push($machine7t,number_format($label,2,'.',','));
						array_push($machine7t,number_format($total2,2,'.',','));
						array_push($machine7t,$employee);
					}
					else if($machine == 28)
					{
						array_push($machine8t,number_format($total1,2,'.',','));
						array_push($machine8t,number_format($i,2,'.',','));
						array_push($machine8t,number_format($sacks,2,'.',','));
						array_push($machine8t,number_format($label,2,'.',','));
						array_push($machine8t,number_format($total2,2,'.',','));
						array_push($machine8t,$employee);
					}
					else if($machine == 29)
					{
						array_push($machine9t,number_format($total1,2,'.',','));
						array_push($machine9t,number_format($i,2,'.',','));
						array_push($machine9t,number_format($sacks,2,'.',','));
						array_push($machine9t,number_format($label,2,'.',','));
						array_push($machine9t,number_format($total2,2,'.',','));
						array_push($machine9t,$employee);
					}
				}
				else
				{
					if($machine == 21)
					{
						array_push($machine1t,'');
						array_push($machine1t,'');
						array_push($machine1t,'');
						array_push($machine1t,'');
						array_push($machine1t,'');
						array_push($machine1t,'');
					}
					else if($machine == 22)
					{
						array_push($machine2t,'');
						array_push($machine2t,'');
						array_push($machine2t,'');
						array_push($machine2t,'');
						array_push($machine2t,'');
						array_push($machine2t,'');
					}
					else if($machine == 23)
					{
						array_push($machine3t,'');
						array_push($machine3t,'');
						array_push($machine3t,'');
						array_push($machine3t,'');
						array_push($machine3t,'');
						array_push($machine3t,'');
					}
					else if($machine == 24)
					{
						array_push($machine4t,'');
						array_push($machine4t,'');
						array_push($machine4t,'');
						array_push($machine4t,'');
						array_push($machine4t,'');
						array_push($machine4t,'');
					}
					else if($machine == 25)
					{
						array_push($machine5t,'');
						array_push($machine5t,'');
						array_push($machine5t,'');
						array_push($machine5t,'');
						array_push($machine5t,'');
						array_push($machine5t,'');
					}
					else if($machine == 26)
					{
						array_push($machine6t,'');
						array_push($machine6t,'');
						array_push($machine6t,'');
						array_push($machine6t,'');
						array_push($machine6t,'');
						array_push($machine6t,'');
					}
					else if($machine == 27)
					{
						array_push($machine7t,'');
						array_push($machine7t,'');
						array_push($machine7t,'');
						array_push($machine7t,'');
						array_push($machine7t,'');
						array_push($machine7t,'');
					}
					else if($machine == 28)
					{
						array_push($machine8t,'');
						array_push($machine8t,'');
						array_push($machine8t,'');
						array_push($machine8t,'');
						array_push($machine8t,'');
						array_push($machine8t,'');
					}
					else if($machine == 29)
					{
						array_push($machine9t,'');
						array_push($machine9t,'');
						array_push($machine9t,'');
						array_push($machine9t,'');
						array_push($machine9t,'');
						array_push($machine9t,'');
					}
				}
				
				$stmt->closeCursor();
			}
		}
		echo '<tr class="text-center">
				<th class="text-center">Good production</th>
				<td>'. $machine1t[0] .'</td>
				<td>'. $machine2t[0] .'</td>
				<td>'. $machine3t[0] .'</td>
				<td>'. $machine4t[0] .'</td>
				<td>'. $machine5t[0] .'</td>
				<td>'. $machine6t[0] .'</td>
				<td>'. $machine7t[0] .'</td>
				<td>'. $machine8t[0] .'</td>
				<td>'. $machine9t[0] .'</td>
				<th class="text-center">'. $t1.'</th>
			</tr>';
		echo '<tr class="text-center">
				<th class="text-center">No. of sacks</th>
				<td>'. $machine1t[1] .'</td>
				<td>'. $machine2t[1] .'</td>
				<td>'. $machine3t[1] .'</td>
				<td>'. $machine4t[1] .'</td>
				<td>'. $machine5t[1] .'</td>
				<td>'. $machine6t[1] .'</td>
				<td>'. $machine7t[1] .'</td>
				<td>'. $machine8t[1] .'</td>
				<td>'. $machine9t[1] .'</td>
				<th class="text-center">'. $t2.'</th>
			</tr>';
        echo '<tr class="text-center">
				<th class="text-center">Sacks weight</th>
				<td>'. $machine1t[2] .'</td>
				<td>'. $machine2t[2] .'</td>
				<td>'. $machine3t[2] .'</td>
				<td>'. $machine4t[2] .'</td>
				<td>'. $machine5t[2] .'</td>
				<td>'. $machine6t[2] .'</td>
				<td>'. $machine7t[2] .'</td>
				<td>'. $machine8t[2] .'</td>
				<td>'. $machine9t[2] .'</td>
				<th class="text-center">'. $t3.'</th>
			</tr>';
        echo '<tr class="text-center">
				<th class="text-center">Label weight</th>
				<td>'. $machine1t[3] .'</td>
				<td>'. $machine2t[3] .'</td>
				<td>'. $machine3t[3] .'</td>
				<td>'. $machine4t[3] .'</td>
				<td>'. $machine5t[3] .'</td>
				<td>'. $machine6t[3] .'</td>
				<td>'. $machine7t[3] .'</td>
				<td>'. $machine8t[3] .'</td>
				<td>'. $machine9t[3] .'</td>
				<th class="text-center">'. $t4.'</th>
			</tr>';
        echo '<tr class="text-center">
				<th class="text-center">Net production</th>
				<td>'. $machine1t[4] .'</td>
				<td>'. $machine2t[4] .'</td>
				<td>'. $machine3t[4] .'</td>
				<td>'. $machine4t[4] .'</td>
				<td>'. $machine5t[4] .'</td>
				<td>'. $machine6t[4] .'</td>
				<td>'. $machine7t[4] .'</td>
				<td>'. $machine8t[4] .'</td>
				<td>'. $machine9t[4] .'</td>
				<th class="text-center">'. $t5.'</th>
			</tr>';
        echo '<tr class="text-center">
				<th class="text-center">Operator Name</th>
				<td>'. $machine1t[5] .'</td>
				<td>'. $machine2t[5] .'</td>
				<td>'. $machine3t[5] .'</td>
				<td>'. $machine4t[5] .'</td>
				<td>'. $machine5t[5] .'</td>
				<td>'. $machine6t[5] .'</td>
				<td>'. $machine7t[5] .'</td>
				<td>'. $machine8t[5] .'</td>
				<td>'. $machine9t[5] .'</td>
				<td></td>
			</tr>';
		 echo '<tr class="active text-center">
				<th class="text-center">Sacks Weight</th>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>';
		
		for($i = 0; $i<60; ++$i) 
		{
			$entro = false;
			$count = $i + 1;
			$string = '<tr class="text-center">
				<th class="text-center">'. $count .'</th>';
			if(sizeof($machine1)> $i)
			{
				$options = "";
				if($this->admin())
				{
					$options = '<button class="btn btn-xs btn-warning" type="button" onclick="edit()"><i class="fa fa-pencil" aria-hidden="true"></i></button>&nbsp<button class="btn btn-xs btn-danger" type="button" onclick="deleteEntry()">X</button>&nbsp&nbsp';
				}
				$string = $string. '<td>'. $machine1[$i]. $options .'</td>';
				$entro = true;
			}
			else
			{
				$string = $string. '<td></td>';
			}
			if(sizeof($machine2)> $i)
			{
				$options = "";
				if($this->admin())
				{
					$options = ' <button class="btn btn-xs btn-warning" type="button" onclick="edit()"><i class="fa fa-pencil" aria-hidden="true"></i></button>&nbsp<button class="btn btn-xs btn-danger" type="button" onclick="deleteEntry()">X</button>&nbsp&nbsp';
				}
				$string = $string. '<td>'. $machine2[$i]. $options .'</td>';
				$entro = true;
			}
			else
			{
				$string = $string.  '<td></td>';
			}
			if(sizeof($machine3)> $i)
			{
				$string = $string.  '<td>'. $machine3[$i] .'</td>';
				$entro = true;
			}
			else
			{
				$string = $string.  '<td></td>';
			}
			if(sizeof($machine4)> $i)
			{
				$string = $string.  '<td>'. $machine4[$i] .'</td>';
				$entro = true;
			}
			else
			{
				$string = $string.  '<td></td>';
			}
			if(sizeof($machine5)> $i)
			{
				$string = $string.  '<td>'. $machine5[$i] .'</td>';
				$entro = true;
			}
			else
			{
				$string = $string.  '<td></td>';
			}
			if(sizeof($machine6)> $i)
			{
				$string = $string.  '<td>'. $machine6[$i] .'</td>';
				$entro = true;
			}
			else
			{
				$string = $string.  '<td></td>';
			}
			if(sizeof($machine7)> $i)
			{
				$string = $string.  '<td>'. $machine7[$i] .'</td>';
				$entro = true;
			}
			else
			{
				$string = $string.  '<td></td>';
			}
			if(sizeof($machine8)> $i)
			{
				$string = $string.  '<td>'. $machine8[$i] .'</td>';
				$entro = true;
			}
			else
			{
				$string = $string.  '<td></td>';
			}
			if(sizeof($machine9)> $i)
			{
				$string = $string.  '<td>'. $machine9[$i] .'</td>';
				$entro = true;
			}
			else
			{
				$string = $string.  '<td></td>';
			}
			$string = $string.  '<td></td>';
			$string = $string.  '</tr>';
			if($entro)
			{
				echo $string;
			}
			else
			{
				$i = 100;
			}
		}
		
        
    }
	
	/**
     * Loads the table of all the rolls
     * This function outputs <tr> tags with rolls
     * Parameter= ID of the shift ALL DAY=0 MORNING=1 NIGHT=2
     */
    public function givePackingSacks($shift, $customer)
    {
        $newDateString = date("Y-m-d");
        if(!empty($_POST['dateSearch']))
        {
           $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch']);
           $newDateString = $myDateTime->format('Y-m-d');
        }
        $date = "date_sacks BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString ." 23:59:59'";

        $sql = "SELECT `packing_sacks`.`number`,
    `packing_sacks`.`weight`
FROM `packing_sacks`
WHERE customer_id = ". $customer."  AND ". $date ."
ORDER BY packing_sacks_id";

        if($shift != 0)
        {
            $sql = "SELECT `packing_sacks`.`number`,
    `packing_sacks`.`weight`
FROM `packing_sacks`
WHERE customer_id = ". $customer."  AND ". $date ."  AND shift = ". $shift ."
ORDER BY packing_sacks_id";
        }
        
        $total1 = $total2 = 0;
                
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {   
				
                $total1 = $total1 + $row['number'];
                $total2 = $total2 + $row['weight'];
                echo '<tr>
                        <td>'.  number_format($row['number'],0,'.',',') .'</td>                    
                        <td class="text-right">'. number_format($row['weight'],2,'.',',') .'</td>
                        
                       </tr>';
            }
			
			if($total1 > 0)
			{
			$avg = $total2 / $total1;	
            echo '
              <tfoot>
                <tr class="active">
                  <th style="text-align:center">Total Weight</th>
                  <th class="text-right">'. number_format($total2,2,'.',',') .'</th>
                </tr>
				<tr >
                  <th style="text-align:center">No. of sacks</th>
                  <th class="text-right">'. number_format($total1,0,'.',',') .'</th>
                </tr>
				<tr >
                  <th style="text-align:center">Average weight</th>
                  <th class="text-right">'. number_format($avg,2,'.',',') .'</th>
                </tr>
              </tfoot>';
			}
            $stmt->closeCursor();
            
        }
        else
        {
            echo "<tr>
                    <td>Something went wrong</td>
                    <td>$db->errorInfo</td>
                    <td></td>
                    <td></td>
                </tr>";
        }
        
    }
	
	/**
    * Checks and inserts the sacks
    *
    * @return boolean true if can insert false if not
    */
    public function createSacks()
    {
        $SACKWT = 0;
        $sql = "SELECT `settings`.`value_setting`
                FROM  `settings` 
                WHERE machine_id=34 AND name_setting='sack';";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $SACKWT = $row['value_setting'];
            }
        }
        
		$LABELWT = 1;
		 $sql = "SELECT `settings`.`value_setting`
                FROM  `settings` 
                WHERE machine_id=34 AND name_setting='label';";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $LABELWT = $row['value_setting'];
            }
        }
		
		$PACKETWT = 1;
		 $sql = "SELECT `settings`.`value_setting`
                FROM  `settings` 
                WHERE machine_id=34 AND name_setting='packet';";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $PACKETWT = $row['value_setting'];
            }
        }
        
       $machine = $machinecode  = $employee1  = $employee2  ="";
		
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
		
		
		$employee2 = trim($_POST["employee2"]);
        $employee2 = stripslashes($employee2);
        $employee2 = htmlspecialchars($employee2);
		if(empty($_POST['employee2']))
        {
			$employee2 = 'NULL';
		}
		
		
		$employee3 = trim($_POST["employee3"]);
        $employee3 = stripslashes($employee3);
        $employee3 = htmlspecialchars($employee3);
		if(empty($_POST['employee3']))
        {
			$employee3 = 'NULL';
		}
		
		
		$employee4 = trim($_POST["employee4"]);
        $employee4 = stripslashes($employee4);
        $employee4 = htmlspecialchars($employee4);
		if(empty($_POST['employee4']))
        {
			$employee4 = 'NULL';
		}
		
		$film1 = trim($_POST["film1"]);
        $film1 = stripslashes($film1);
        $film1 = htmlspecialchars($film1);
		if(empty($_POST['film1']))
		{
			$film1 = 0;
		}
		
		$block1 = trim($_POST["block1"]);
        $block1 = stripslashes($block1);
        $block1 = htmlspecialchars($block1);
		if(empty($_POST['block1']))
		{
			$block1 = 0;
		}
		
		$film2 = trim($_POST["film2"]);
        $film2 = stripslashes($film2);
        $film2 = htmlspecialchars($film2);
		if(empty($_POST['film2']))
		{
			$film2 = 0;
		}
		
		$block2 = trim($_POST["block2"]);
        $block2 = stripslashes($block2);
        $block2 = htmlspecialchars($block2);
		if(empty($_POST['block2']))
		{
			$block2 = 0;
		}
		
        //DATE
        $date = date("Y-m-d");
        if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = $newDateString;
        }
		
		$totalnet = 0;
		
		$sacks = "INSERT INTO `cutting_sacks`(`cutting_sacks_id`,`date_sacks`,`shift`,`gross_weight`,`net_weight`,`user_id`,`machine_id`,`employee_id`,`employee_id2`) VALUES";
		foreach ($_POST as $k=>$v)
		{
			if (substr( $k, 0, 4 ) === "wt1_" and !empty($v)){
				$net = $v - $SACKWT;
				$label = $net * $LABELWT / $PACKETWT; 
				$net = $net - $label;
				$totalnet = $totalnet + $net;
				$sacks = $sacks. " (NULL, '". $date."', 1, ". $v .", ". $net .", ". $_SESSION['Userid'] .", ". $machine .", ". $employee1 .", ". $employee2 .") ,";
			}
			else if (substr( $k, 0, 4 ) === "wt2_" and !empty($v)){
				$net = $v - $SACKWT;
				$label = $net * $LABELWT / $PACKETWT; 
				$net = $net - $label;
				$totalnet = $totalnet + $net;
				$sacks = $sacks. " (NULL, '". $date."', 2, ". $v .", ". $net .", ". $_SESSION['Userid'] .", ". $machine .", ". $employee3 .", ". $employee4 .") ,";
			}
		}
		
		$totalnet = $totalnet + $film1 + $block1 + $film2 + $block2;
		
		$update = "";
		
		
		$sql = "SELECT sacks_rolls_id, SUM(`net_weight`) as net, SUM(used_weight) as used
				FROM `sacks_rolls`;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['sacks_rolls_id']))
				{
					$TOTAL = $row['net'] - $row['used'];
					if($TOTAL<$totalnet)
					{
						echo '<strong>ERROR</strong> The sacks were not added to the production. Because there is not enought rolls production in stock. <br> There are <strong>'. $TOTAL .'</strong> kgs in stock, and you need <strong>'. $totalnet .'</strong> kgs.  Please try again after submit the rolls for the extruder.';
						return false;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The sack was not added to the production. Because there is not enought rolls production in stock. <br>  Please try again after submit the rolls for the extruder.';
						return false;
				   }
            }
		}
		
		$sql = "SELECT sacks_rolls_id, `net_weight`, used_weight
				FROM `sacks_rolls`
				WHERE `status_roll` = 0
				ORDER BY date_roll, sacks_rolls_id
				LIMIT 100;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['sacks_rolls_id']))
				{
					$TOTAL = $row['net_weight'] - $row['used_weight'];
					if(($TOTAL > $totalnet) and ($totalnet > 0))
					{
						if($totalnet + $row['used_weight'] == $row['net_weight'])
						{
							
							$update = $update . "
						UPDATE `sacks_rolls` SET
                        `used_weight` = `used_weight`+". $totalnet .", `status_roll` = 1
						WHERE `sacks_rolls_id` = ". $row['sacks_rolls_id']."; ";
						}
						else
						{
							
							$update = $update . "
						UPDATE `sacks_rolls` SET
                        `used_weight` = `used_weight`+". $totalnet .", `status_roll` = 0
						WHERE `sacks_rolls_id` = ". $row['sacks_rolls_id']."; ";
						}
						$totalnet = 0;
						$stmt->closeCursor();
					}
					else if(($TOTAL <= $totalnet) and ($totalnet > 0))
					{
						$update = $update . "
						UPDATE `sacks_rolls` SET
                        `used_weight` = `used_weight`+". $TOTAL .", `status_roll` = 1
						WHERE `sacks_rolls_id` = ". $row['sacks_rolls_id']."; ";
						$totalnet = $totalnet - $TOTAL;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The sack was not added to the production. Because there is not enought rolls production in stock. <br>  Please try again after submit the rolls for the extruder.';
						return false;
				   }
            }
		
			$waste = "";
			if(!empty($_POST['wt1_1']))
			{
				$waste = "INSERT INTO  `waste`(`waste_id`,`date_waste`,`shift`,`machine_id`,`waste`,`user_id`,`type`) VALUES (NULL,'". $date."', 1,". $machine .", ". $film1 .", ". $_SESSION['Userid'] .", 1), (NULL,'". $date."', 1,". $machine .", ". $block1 .", ". $_SESSION['Userid'] .", 2); ";
			}
			if(!empty($_POST['wt2_2']))
			{
				$waste = $waste . "INSERT INTO  `waste`(`waste_id`,`date_waste`,`shift`,`machine_id`,`waste`,`user_id`,`type`) VALUES (NULL,'". $date."', 2,". $machine .", ". $film2 .", ". $_SESSION['Userid'] .", 1), (NULL,'". $date."', 2,". $machine .", ". $block2 .", ". $_SESSION['Userid'] .", 2); ";
			}
			
			$sql = substr($sacks,0,strlen($sacks)-2). "; ". $waste . $update ;
			try {   
				$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
				$stmt->closeCursor();
				echo '<strong>SUCCESS!</strong> The sacks were successfully added to the database for the day';
				return TRUE;
			}
			catch (PDOException $e) {
				echo '<strong>ERROR</strong> Could not insert the sacks into the database. Please try again.<br>'. $e->getMessage();
				return FALSE;
			}
			}
		}
	
	/**
    * Checks and inserts the sacks
    *
    * @return boolean true if can insert false if not
    */
    public function createPackingSacks()
    {
       
        
       $customer = $shift  = "";
		
		$customer = trim($_POST["customer"]);
        $customer = stripslashes($customer);
        $customer = htmlspecialchars($customer);
       
        $shift = trim($_POST["shift"]);
        $shift = stripslashes($shift);
        $shift = htmlspecialchars($shift);
		
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
		
		$totalnet = $KGS = $number = 0;
		
		$sacks = "INSERT INTO `packing_sacks`(`packing_sacks_id`,`date_sacks`,`shift`,`number`,`weight`,`user_id`,`customer_id`) VALUES ";
		foreach ($_POST as $k=>$v)
		{
			if (substr( $k, 0, 3 ) === "wt_" and !empty($v)){
				$i = explode("_",$k)[1];
				
				$no = trim($_POST["no_".$i]);
				$number = $number + $no;
				$totalnet = $totalnet + $v;
				$sacks = $sacks. " (NULL, '". $date."', ". $shift .", ". $no .", ". $v .", ". $_SESSION['Userid'] .", ". $customer .") ,";
			}
		}
		
		$KGS = $totalnet;
		
		$update = "";
		
		
		$sql = "SELECT cutting_sacks_id, SUM(`net_weight`) as net, SUM(used_weight) as used
				FROM `cutting_sacks`
				WHERE `status_sack` = 0
				ORDER BY `date_sacks`;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['cutting_sacks_id']))
				{
					$TOTAL = $row['net'] - $row['used'];
					if($TOTAL<$totalnet)
					{
						echo '<strong>ERROR</strong> The sacks were not added to the production. Because there is not enought cutting sacks production in stock. <br> There are <strong>'. $TOTAL .'</strong> kgs in stock, and you need <strong>'. $totalnet .'</strong> kgs.  Please try again after submit the sacks for the cutting.';
						return false;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The sack was not added to the production. Because there is not enought cutting sacks production in stock. <br>  Please try again after submit the sacks for the cutting.';
						return false;
				   }
            }
		}
		
		$sql = "SELECT cutting_sacks_id, `net_weight`, used_weight 
				FROM `cutting_sacks`
				WHERE `status_sack` = 0
				ORDER BY  `date_sacks`, cutting_sacks_id
				LIMIT 100;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['cutting_sacks_id']) and ($totalnet > 0))
				{
					$TOTAL = $row['net_weight'] - $row['used_weight'];
					if(($TOTAL > $totalnet) and ($totalnet > 0))
					{
						if($totalnet + $row['used_weight'] == $row['net_weight'])
						{
							
							$update = $update . "
						UPDATE `cutting_sacks` SET
                        `used_weight` = `used_weight`+". $totalnet .", `status_sack` = 1
						WHERE `cutting_sacks_id` = ". $row['cutting_sacks_id']."; ";
						}
						else
						{
							
							$update = $update . "
						UPDATE `cutting_sacks` SET
                        `used_weight` = `used_weight`+". $totalnet .", `status_sack` = 0
						WHERE `cutting_sacks_id` = ". $row['cutting_sacks_id']."; ";
						}
						$totalnet = 0;
						$stmt->closeCursor();
					}
					else if(($TOTAL <= $totalnet) and ($totalnet > 0))
					{
						$update = $update . "
						UPDATE `cutting_sacks` SET
                        `used_weight` = `used_weight`+". $TOTAL .", `status_sack` = 1
						WHERE `cutting_sacks_id` = ". $row['cutting_sacks_id'].";";
						$totalnet = $totalnet - $TOTAL;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The sack was not added to the production. Because there is not enought cutting sacks production in stock. <br>  Please try again after submit the sacks for the cutting.';
						return false;
				   }
            }
		
			if($customer == 1)
			{
				$material = 'UNITED Sacks';
			}
			else
			{
				$material = 'EBONY Sacks';
			}
			
			$transfer = " INSERT INTO  `stock_materials_transfers`(`stock_materials_transfers_id`,`machine_from`,`machine_to`,`material_id`,`date_required`,`bags_required`,`bags_approved`,`bags_issued`,`bags_receipt`,`user_id_required`,`user_id_approved`,`user_id_issued`,`user_id_receipt`,`status_transfer`,`remarks_approved`,`remarks_issued`)VALUES(NULL,31,12, (SELECT material_id FROM materials WHERE material_name = '". $material." (weight)'),'". $date ."',". $KGS . ",". $KGS . ",". $KGS . ",NULL,". $_SESSION['Userid'] . ",". $_SESSION['Userid'] . ",". $_SESSION['Userid'] . ",NULL,2,'Total No. of Sacks = ". $number ."',NULL),(NULL,31,12, (SELECT material_id FROM materials WHERE material_name = '". $material." (pcs)'),'". $date ."',". $number . ",". $number . ",". $number . ",NULL,". $_SESSION['Userid'] . ",". $_SESSION['Userid'] . ",". $_SESSION['Userid'] . ",NULL,2,'Total Weight of Sacks = ". $KGS ."',NULL);";
			
			$sql = substr($sacks,0,strlen($sacks)-2). "; ". $update . $transfer ;
			
			try {   
				$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
				$stmt->closeCursor();
				
				echo '<strong>SUCCESS!</strong> The sacks were successfully added to the database for the shift: <strong>'. $this->giveShiftname($shift) .'</strong>';
				return TRUE;
					
			}
			catch (PDOException $e) {
				echo '<strong>ERROR</strong> Could not insert the sacks into the database. Please try again.<br>'. $e->getMessage();
				return FALSE;
			}
			}
		}
	
	
	/**
    * Checks and inserts the rolls WITHOUT BATCHES
    *
    * @return boolean true if can insert false if not
    */
    public function createRolls()
    {
        $CONE = 0;
        $sql = "SELECT `settings`.`value_setting`
                FROM  `settings` 
                WHERE machine_id=7 AND name_setting='cone';";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $CONE = $row['value_setting'];
            }
        }
        
        
       $machine = $machinecode =  $thickness = $size = "";
		
        $machine = trim($_POST["machine"]);
        $machine = stripslashes($machine);
        $machine = htmlspecialchars($machine);
		
		if($machine == 13)
		{
			$machinecode = "A";
		}
		else if($machine == 14)
		{
			$machinecode = "B";
		}
		else if($machine == 15)
		{
			$machinecode = "C";
		}
		else if($machine == 16)
		{
			$machinecode = "D";
		}
		else if($machine == 17)
		{
			$machinecode = "E";
		}
		else if($machine == 18)
		{
			$machinecode = "F";
		}
		else if($machine == 19)
		{
			$machinecode = "G";
		}
		else if($machine == 20)
		{
			$machinecode = "H";
		}
		
		
		$size = trim($_POST["size"]);
        $size = stripslashes($size);
        $size = htmlspecialchars($size);
		
		$thickness = trim($_POST["thickness"]);
        $thickness = stripslashes($thickness);
        $thickness = htmlspecialchars($thickness);
		
		$film1 = trim($_POST["film1"]);
        $film1 = stripslashes($film1);
        $film1 = htmlspecialchars($film1);
		if(empty($_POST['film1']))
		{
			$film1 = 0;
		}
		
		$block1 = trim($_POST["block1"]);
        $block1 = stripslashes($block1);
        $block1 = htmlspecialchars($block1);
		if(empty($_POST['block1']))
		{
			$block1 = 0;
		}
		
		$film2 = trim($_POST["film2"]);
        $film2 = stripslashes($film2);
        $film2 = htmlspecialchars($film2);
		if(empty($_POST['film2']))
		{
			$film2 = 0;
		}
		
		$block2 = trim($_POST["block2"]);
        $block2 = stripslashes($block2);
        $block2 = htmlspecialchars($block2);
		if(empty($_POST['block2']))
		{
			$block2 = 0;
		}
		
        //DATE
        $date = date("Y-m-d");
        if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = $newDateString;
        }
		
		$totalnet = 0;
		
		
		// GETS ROLL NO 
	   $sql = "SELECT COUNT(DISTINCT(rollno)) as rollcount
				FROM `sacks_rolls` WHERE substr(rollno, 7,1) = '". $machinecode."' AND date_roll BETWEEN '". $date ." 00:00:00' AND '". $date ." 23:59:59' AND machine_id = ".$machine.";";
		$count = 0;
		if($stmt = $this->_db->prepare($sql))
		{
			$stmt->execute();

			$row = $stmt->fetch();
			$count = $row['rollcount'] ;
			$stmt->closeCursor();

		}

		$myDateTime = DateTime::createFromFormat('Y-m-d', $date);
		$newDateString = $myDateTime->format('d-m');
		
		$rolls = "INSERT INTO `sacks_rolls`
	(`sacks_rolls_id`,`date_roll`,`rollno`,`shift`,`size`,`gross_weight`,`net_weight`,`thickness`,`user_id`,`machine_id`,`status_roll`)
		VALUES  ";
		foreach ($_POST as $k=>$v)
		{
			if (substr( $k, 0, 4 ) === "wt1_" and !empty($v)){
				$count = $count + 1;
				$rollno = $newDateString."-".$machinecode."-".$count;
				$net = $v - $CONE;
				$totalnet = $totalnet + $net;
				$rolls = $rolls. " (NULL, '". $date."', '".$rollno."', 1, ". $size .", ". $v .", ". $net .", ". $thickness .", ". $_SESSION['Userid'] .", ". $machine .", 0) ,";
				
			}
			if (substr( $k, 0, 4 ) === "wt2_" and !empty($v)){
				$count = $count + 1;
				$rollno = $newDateString."-".$machinecode."-".$count;
				$net = $v - $CONE;
				$totalnet = $totalnet + $net;
				$rolls = $rolls. " (NULL, '". $date."', '".$rollno."', 2, ". $size .", ". $v .", ". $net .", ". $thickness .", ". $_SESSION['Userid'] .", ". $machine .", 0) ,";
			}
		}
		
		$totalnet = $totalnet + $film1 + $block1 + $film2 + $block2;
	
		
		
		$update = "";
		
		
		$sql = "SELECT stock_material_id,
					`stock_materials`.`bags`, kgs_bag, material_name, material_grade
				FROM `stock_materials`
				RIGHT JOIN `materials` ON  `materials`.material_id = `stock_materials`.material_id
				WHERE `machine_id` = 7 AND materials.material_grade = 'TR 144';";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['stock_material_id']))
				{
					$KGSNEEDED = $totalnet/2;
					$BAGSNEEDED = $KGSNEEDED / $row['kgs_bag'];
					$BAGSNEEDED = number_format($BAGSNEEDED ,4,'.','');
					//LANZA ERROR SI LAS BOLSAS ACTUALES SON MENORES A LAS QUE SE NECESITAN
					if($row['bags']<$BAGSNEEDED)
					{
						echo '<strong>ERROR</strong> The rolls were not added to the production. Because there is not enought  material <strong>'. $row['material_name'] .' - '. $row['material_grade'] .'</strong> in stock. <br> There are <strong>'. $row['bags'] .'</strong> bags in stock, and you need <strong>'. $BAGSNEEDED .'</strong> bags. Please try again receiving the raw material or updating the formula.';
						return false;
					}
					// VA CREANDO EL UPDATE PARA CAMBIAR DESPUES EL NUMERO DE BOLSAS DE STOCK_MATERIALS
					else
					{
						$newbags = $row['bags']-$BAGSNEEDED;
						$update = $update . "UPDATE  `stock_materials` SET `bags` = ".$newbags." WHERE `stock_material_id` = ". $row['stock_material_id']. "; ";
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The roll was not added to the production. Because there is not enought material <strong>'. $row['material_name'] .' - '. $row['material_grade'] .'</strong> in stock. <br>  Please try again receiving the raw material or updating the formula.';
						return false;
				   }
            }
		}
				$sql = "SELECT stock_material_id,
					`stock_materials`.`bags`, kgs_bag, material_name, material_grade
				FROM `stock_materials`
				RIGHT JOIN `materials` ON  `materials`.material_id = `stock_materials`.material_id
				WHERE `machine_id` = 7 AND materials.material_grade = 'M9255F';";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['stock_material_id']))
				{
					$KGSNEEDED = $totalnet/2;
					$BAGSNEEDED = $KGSNEEDED / $row['kgs_bag'];
					$BAGSNEEDED = number_format($BAGSNEEDED ,4,'.','');
					//LANZA ERROR SI LAS BOLSAS ACTUALES SON MENORES A LAS QUE SE NECESITAN
					if($row['bags']<$BAGSNEEDED)
					{
						echo '<strong>ERROR</strong> The rolls were not added to the production. Because there is not enought  material <strong>'. $row['material_name'] .' - '. $row['material_grade'] .'</strong> in stock. <br> There are <strong>'. $row['bags'] .'</strong> bags in stock, and you need <strong>'. $BAGSNEEDED .'</strong> bags. Please try again receiving the raw material or updating the formula.';
						return false;
					}
					// VA CREANDO EL UPDATE PARA CAMBIAR DESPUES EL NUMERO DE BOLSAS DE STOCK_MATERIALS
					else
					{
						$newbags = $row['bags']-$BAGSNEEDED;
						$update = $update . "UPDATE  `stock_materials` SET `bags` = ".$newbags." WHERE `stock_material_id` = ". $row['stock_material_id']. "; ";
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The roll was not added to the production. Because there is not enought  material <strong>'. $row['material_name'] .' - '. $row['material_grade'] .'</strong> in stock. <br>  Please try again receiving the raw material or updating the formula.';
						return false;
				   }
            }
		}
			
			$waste = "";
			if(!empty($_POST['wt1_1']))
			{
				$waste = "INSERT INTO  `waste`(`waste_id`,`date_waste`,`shift`,`machine_id`,`waste`,`user_id`,`type`) VALUES (NULL,'". $date."', 1,". $machine .", ". $film1 .", ". $_SESSION['Userid'] .", 1), (NULL,'". $date."', 1,". $machine .", ". $block1 .", ". $_SESSION['Userid'] .", 2); ";
			}
			if(!empty($_POST['wt2_2']))
			{
				$waste = $waste . "INSERT INTO  `waste`(`waste_id`,`date_waste`,`shift`,`machine_id`,`waste`,`user_id`,`type`) VALUES (NULL,'". $date."', 2,". $machine .", ". $film2 .", ". $_SESSION['Userid'] .", 1), (NULL,'". $date."', 2,". $machine .", ". $block2 .", ". $_SESSION['Userid'] .", 2); ";
			}
			
			$sql = substr($rolls,0,strlen($rolls)-2). ";". $waste . $update ;
			try {   
				$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
				$stmt->closeCursor();
				echo '<strong>SUCCESS!</strong> The production was successfully added to the database';
				return TRUE;
			}
			catch (PDOException $e) {
				if ($e->getCode() == 23000) {
					echo '<strong>ERROR</strong> The rolls numbers have already being register for the shift.<br>';
				} 
				else {
					echo '<strong>ERROR</strong> Could not insert the rolls into the database. Please try again.<br>'. $e->getMessage();
				}
				return FALSE;
			}
		}
    
	
	 /**
     * Loads the table of all the rolls in the multilayer section
     * This function outputs <tr> tags with the rolls
     */
    public function giveRollsInfo()
    {
        $a=array();
        $b=array();
        $c=array();
        $d=array();
        $sql = "SELECT size, count(sacks_rolls_id) AS count_rolls, ROUND(SUM(gross_weight),2) AS totalgross, ROUND(SUM(net_weight),2) As totalnet,ROUND(SUM(net_weight)-SUM(used_weight),2) As notused, ROUND(SUM(net_weight)/count(sacks_rolls_id),2) AS average_weight
                FROM  `sacks_rolls`
                WHERE status_roll = 0 group by size;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $SIZE = $row['size'];
                $COUNT = $row['count_rolls'];
                $GROSS = $row['totalgross'];
                $NET = $row['totalnet'];
                $AVERAGE = $row['average_weight'];
                
                echo '<tr>
                        <td>'. $this->giveSizeName($SIZE) .'</td>
                        <td>'. number_format($COUNT,0,'.',',') .'</td>
                        <td class="text-right">'. number_format($GROSS,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($NET,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['notused'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($AVERAGE,2,'.',',') .'</td>
                    </tr>';
                
                $countArray=array("y" => $COUNT, "label" => $this->giveSizeName($SIZE));
                array_push($a,$countArray);
                $weightArray=array("y" => $GROSS, "label" => $this->giveSizeName($SIZE)) ;
                array_push($b,$weightArray);
                $weightArray=array("y" => $NET, "label" => $this->giveSizeName($SIZE)) ;
                array_push($c,$weightArray);
                $averageArray=array("y" => $AVERAGE, "label" => $this->giveSizeName($SIZE)) ;
                array_push($d,$averageArray);
            }
            $stmt->closeCursor();
            $x=array();
            array_push($x,$a);
            array_push($x,$b);
            array_push($x,$c);
            array_push($x,$d);
            return $x;
        }
        else
        {
            echo "<tr>
                    <td>Something went wrong.</td>
                    <td>$db->errorInfo</td>
                    <td></td>
                    <td></td>
                </tr>";
        }
    }
	
	/**
     * Loads the table of all the rolls in the multilayer section
     * This function outputs <tr> tags with the rolls
     */
    public function giveSacksInfo()
    {
        $a=array();
        $b=array();
        $c=array();
        $d=array();
        $sql = "SELECT count(cutting_sacks_id) AS count_rolls, ROUND(SUM(gross_weight),2) AS totalgross, ROUND(SUM(net_weight),2) As totalnet, ROUND(SUM(net_weight)-SUM(used_weight),2) As notused, ROUND(SUM(net_weight)/count(cutting_sacks_id),2) AS average_weight
                FROM  `cutting_sacks`
                WHERE status_sack = 0;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $COUNT = $row['count_rolls'];
                $GROSS = $row['totalgross'];
                $NET = $row['totalnet'];
                $AVERAGE = $row['average_weight'];
                
                echo '<tr>
                        <td>'. $COUNT .'</td>
                        <td class="text-right">'. number_format($GROSS,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($NET,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['notused'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($AVERAGE,2,'.',',') .'</td>
                    </tr>';
                
                $countArray=array("y" => $COUNT, "label" => 'Sacks');
                array_push($a,$countArray);
                $weightArray=array("y" => $GROSS, "label" => 'Sacks') ;
                array_push($b,$weightArray);
                $weightArray=array("y" => $NET, "label" => 'Sacks') ;
                array_push($c,$weightArray);
                $averageArray=array("y" => $AVERAGE, "label" => 'Sacks') ;
                array_push($d,$averageArray);
            }
            $stmt->closeCursor();
            $x=array();
            array_push($x,$a);
            array_push($x,$b);
            array_push($x,$c);
            array_push($x,$d);
            return $x;
        }
        else
        {
            echo "<tr>
                    <td>Something went wrong.</td>
                    <td>$db->errorInfo</td>
                    <td></td>
                    <td></td>
                </tr>";
        }
    }
	
	
	public function giveSacksStock()
    {
        $sql = "SELECT DATE_FORMAT(`date_sacks`, '%Y-%m-%d') as date, gross_weight, net_weight
                 FROM  cutting_sacks
                WHERE status_sack = 0;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $DATE = $row['date'];
                $GROSS = $row['gross_weight'];
                $NET = $row['net_weight'];
                
                echo '<tr>
                        <td>'. $DATE .'</td>
                        <td class="text-right">'. number_format($GROSS,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($NET,2,'.',',') .'</td>
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
	

	
	/**
     * Loads the table of all the rolls in the multilayer section
     * This function outputs <tr> tags with the rolls
     */
    public function giveRollsStock()
    {
        $sql = "SELECT `rollno`,`size`, gross_weight, net_weight
                 FROM  `sacks_rolls`
                WHERE status_roll = 0;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ROLLNO = $row['rollno'];
                $SIZE = $row['size'];
                $GROSS = $row['gross_weight'];
                $NET = $row['net_weight'];
                
                echo '<tr>
                        <td>'. $ROLLNO .'</td>
                        <td>'. $this->giveSizename($SIZE) .'</td>
                        <td class="text-right">'. number_format($GROSS,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($NET,2,'.',',') .'</td>
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
	
	
	public function createCuttingWaste()
    {
		$waste = trim($_POST["waste"]);
        $waste = stripslashes($waste);
        $waste = htmlspecialchars($waste); 
		$totalnet = $waste;
		$type = trim($_POST["type"]);
        $type = stripslashes($type);
        $type = htmlspecialchars($type); 
		
        $date = date("Y-m-d");
		if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = $newDateString;
        }
		
        $update = "";
		
		
		$sql = "SELECT sacks_rolls_id, SUM(`net_weight`) as net, SUM(used_weight) as used
				FROM `sacks_rolls`
				WHERE `status_roll` = 0;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['sacks_rolls_id']))
				{
					$TOTAL = $row['net'] - $row['used'];
					if($TOTAL<$totalnet)
					{
						echo '<strong>ERROR</strong> The waste was not added to the production. Because there is not enought rolls production in stock. <br> There are <strong>'. $TOTAL .'</strong> kgs in stock, and you need <strong>'. $totalnet .'</strong> kgs.  Please try again after submit the <strong>rolls for the extruder</strong>.';
						return false;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The waste was not added to the production. Because there is not enought rolls production in stock. <br>  Please try again after submit the <strong>rolls for the extruder</strong>.';
						return false;
				   }
            }
		}
		
		$sql = "SELECT sacks_rolls_id, `net_weight`, used_weight
				FROM `sacks_rolls`
				WHERE `status_roll` = 0
				ORDER BY date_roll, sacks_rolls_id
				LIMIT 100;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['sacks_rolls_id']))
				{
					$status = 0;
					$TOTAL = $row['net_weight'] - $row['used_weight'];
					if(($TOTAL > $totalnet) and ($totalnet > 0))
					{
						if($totalnet + $row['used_weight'] == $row['net_weight'])
						{
							$status = 1;
						}
						$update = $update . "
						UPDATE `sacks_rolls` SET
                        `used_weight` = `used_weight`+". $totalnet .", `status_roll` = ". $status ."
						WHERE `sacks_rolls_id` = ". $row['sacks_rolls_id']."; ";
						$totalnet = 0;
						
						$stmt->closeCursor();
					}
					else if(($TOTAL <= $totalnet) and ($totalnet > 0))
					{
						$update = $update . "
						UPDATE `sacks_rolls` SET
                        `used_weight` = `used_weight`+". $TOTAL .", `status_roll` = 1
						WHERE `sacks_rolls_id` = ". $row['sacks_rolls_id']."; ";
						$totalnet = $totalnet - $TOTAL;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The waste was not added to the production. Because there is not enought rolls production in stock. <br>  Please try again after submit the <strong>rolls for the extruder</strong>.';
						return false;
				   }
            }
			
            
            //INSERT THE WASTE IN THE DAY DECREASES THE  KGS FROM THE  MULTILAYER_BATCHES_STOCK 
			$sql = "INSERT INTO  `waste`(`waste_id`,`date_waste`,`shift`,`machine_id`,`waste`,`user_id`,`type`) VALUES (NULL,'". $date."', 0,34, ". $waste .", ". $_SESSION['Userid'] .",". $type .");". $update;
            try
            {   
                $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                    $stmt = $this->_db->prepare($sql);
                    $stmt->execute();
                    $stmt->closeCursor();
                    echo '<strong>SUCCESS!</strong> The waste was successfully added to the database.';
                    return TRUE;
                } 
            catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    echo '<strong>ERROR</strong> The waste have already being register for this date.<br>';
                } 
                else {
                    echo '<strong>ERROR</strong> Could not insert the waste into the database. Please try again.<br>'. $e->getMessage();
                }
                return FALSE;
            }
        }
    } 
	
	 /**
    * Checks and inserts the waste
    *
    * @return boolean true if can insert false if not
    */
    public function createSacksWaste()
    {
        $machine = $shift = $total = "";
        
		
        $machine = trim($_POST["machine"]);
        $machine = stripslashes($machine);
        $machine = htmlspecialchars($machine);
		
        $shift = trim($_POST["shift"]);
        $shift = stripslashes($shift);
        $shift = htmlspecialchars($shift);
        
        $film = trim($_POST["film"]);
        $film = stripslashes($film);
        $film = htmlspecialchars($film); 
		
        $block = trim($_POST["block"]);
        $block = stripslashes($block);
        $block = htmlspecialchars($block); 
		
		$totalnet = $film + $block;
        
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
        
        $update = "";
		
		
		$sql = "SELECT sacks_rolls_id, SUM(`net_weight`) as net, SUM(used_weight) as used
				FROM `sacks_rolls`
				WHERE `status_roll` = 0;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['sacks_rolls_id']))
				{
					$TOTAL = $row['net'] - $row['used'];
					if($TOTAL<$totalnet)
					{
						echo '<strong>ERROR</strong> The waste were not added to the production. Because there is not enought rolls production in stock. <br> There are <strong>'. $TOTAL .'</strong> kgs in stock, and you need <strong>'. $totalnet .'</strong> kgs.  Please try again after submit the <strong>rolls for the extruder</strong>.';
						return false;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The waste was not added to the production. Because there is not enought rolls production in stock. <br>  Please try again after submit the <strong>rolls for the extruder</strong>.';
						return false;
				   }
            }
		}
		
		$sql = "SELECT sacks_rolls_id, `net_weight`, used_weight
				FROM `sacks_rolls`
				WHERE `status_roll` = 0
				ORDER BY date_roll, sacks_rolls_id
				LIMIT 100;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['sacks_rolls_id']))
				{
					$status = 0;
					$TOTAL = $row['net_weight'] - $row['used_weight'];
					if(($TOTAL > $totalnet) and ($totalnet > 0))
					{
						if($totalnet + $row['used_weight'] == $row['net_weight'])
						{
							$status = 1;
						}
						$update = $update . "
						UPDATE `sacks_rolls` SET
                        `used_weight` = `used_weight`+". $totalnet .", `status_roll` = ". $status ."
						WHERE `sacks_rolls_id` = ". $row['sacks_rolls_id']."; ";
						$totalnet = 0;
						
						$stmt->closeCursor();
					}
					else if(($TOTAL <= $totalnet) and ($totalnet > 0))
					{
						$update = $update . "
						UPDATE `sacks_rolls` SET
                        `used_weight` = `used_weight`+". $TOTAL .", `status_roll` = 1
						WHERE `sacks_rolls_id` = ". $row['sacks_rolls_id']."; ";
						$totalnet = $totalnet - $TOTAL;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The  waste was not added to the production. Because there is not enought rolls production in stock. <br>  Please try again after submit the <strong>rolls for the extruder</strong>.';
						return false;
				   }
            }
			
            
            //INSERT THE WASTE IN THE DAY DECREASES THE  KGS FROM THE  MULTILAYER_BATCHES_STOCK 
            $sql = "INSERT INTO  `waste`(`waste_id`,`date_waste`,`shift`,`machine_id`,`waste`,`user_id`,`type`) VALUES (NULL,'". $date."', ". $shift .",". $machine .", ". $film .", ". $_SESSION['Userid'] .", 1), (NULL,'". $date."', ". $shift .",". $machine .", ". $block .", ". $_SESSION['Userid'] .", 2);". $update;
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
	
	 public function createPackingWaste()
    {
       $machine = $shift = "";
		
        $machine = trim($_POST["machine"]);
        $machine = stripslashes($machine);
        $machine = htmlspecialchars($machine);
		
        $shift = trim($_POST["shift"]);
        $shift = stripslashes($shift);
        $shift = htmlspecialchars($shift);
        
        $film = trim($_POST["film"]);
        $film = stripslashes($film);
        $film = htmlspecialchars($film); 
		
		 $totalnet = $film;
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
        
        $update = "";
		
		
		$sql = "SELECT cutting_sacks_id, SUM(`net_weight`) as net, SUM(used_weight) as used
				FROM `cutting_sacks`
				WHERE `status_sack` = 0
				ORDER BY `date_sacks`;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['cutting_sacks_id']))
				{
					$TOTAL = $row['net'] - $row['used'];
					if($TOTAL<$totalnet)
					{
						echo '<strong>ERROR</strong> The sacks were not added to the production. Because there is not enought cutting sacks production in stock. <br> There are <strong>'. $TOTAL .'</strong> kgs in stock, and you need <strong>'. $totalnet .'</strong> kgs.  Please try again after submit the sacks for the cutting.';
						return false;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The sack was not added to the production. Because there is not enought cutting sacks production in stock. <br>  Please try again after submit the sacks for the cutting.';
						return false;
				   }
            }
		}
		
		$sql = "SELECT cutting_sacks_id, `net_weight`, used_weight 
				FROM `cutting_sacks`
				WHERE `status_sack` = 0
				ORDER BY  `date_sacks`, cutting_sacks_id
				LIMIT 100;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['cutting_sacks_id']))
				{
					$TOTAL = $row['net_weight'] - $row['used_weight'];
					if(($TOTAL > $totalnet) and ($totalnet > 0))
					{
						if($totalnet + $row['used_weight'] == $row['net_weight'])
						{
							
							$update = $update . "
						UPDATE `cutting_sacks` SET
                        `used_weight` = `used_weight`+". $totalnet .", `status_sack` = 1
						WHERE `cutting_sacks_id` = ". $row['cutting_sacks_id']."; ";
						}
						else
						{
							
							$update = $update . "
						UPDATE `cutting_sacks` SET
                        `used_weight` = `used_weight`+". $totalnet .", `status_sack` = 0
						WHERE `cutting_sacks_id` = ". $row['cutting_sacks_id']."; ";
						}
						$totalnet = 0;
						$stmt->closeCursor();
					}
					else if(($TOTAL <= $totalnet) and ($totalnet > 0))
					{
						$update = $update . "
						UPDATE `cutting_sacks` SET
                        `used_weight` = `used_weight`+". $TOTAL .", `status_sack` = 1
						WHERE `cutting_sacks_id` = ". $row['cutting_sacks_id'].";";
						$totalnet = $totalnet - $TOTAL;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The sack was not added to the production. Because there is not enought cutting sacks production in stock. <br>  Please try again after submit the sacks for the cutting.';
						return false;
				   }
            }
            
            //INSERT THE WASTE IN THE DAY DECREASES THE  KGS FROM THE  MULTILAYER_BATCHES_STOCK 
            $sql = "INSERT INTO  `waste`(`waste_id`,`date_waste`,`shift`,`machine_id`,`waste`,`user_id`,`type`) VALUES (NULL,'". $date."', ". $shift .",". $machine .", ". $film .", ". $_SESSION['Userid'] .", 1);". $update;
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
	 /**
    * Checks and inserts the waste
    *
    * @return boolean true if can insert false if not
    */
    public function createWaste()
    {
        $machine = $shift = $total = "";
        
		
        $machine = trim($_POST["machine"]);
        $machine = stripslashes($machine);
        $machine = htmlspecialchars($machine);
		
        $shift = trim($_POST["shift"]);
        $shift = stripslashes($shift);
        $shift = htmlspecialchars($shift);
        
        $film = trim($_POST["film"]);
        $film = stripslashes($film);
        $film = htmlspecialchars($film); 
		
        $block = trim($_POST["block"]);
        $block = stripslashes($block);
        $block = htmlspecialchars($block); 
		
		$total = $film + $block;
        
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
        
        $sql = "SELECT stock_material_id, `stock_materials`.`bags`, kgs_bag, material_name, material_grade
				FROM `stock_materials`
				RIGHT JOIN `materials` ON  `materials`.material_id = `stock_materials`.material_id
				WHERE `machine_id` = 7;";
		
		$update = "";
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['stock_material_id']))
				{
					$KGSNEEDED = $total;
					$BAGSNEEDED = $KGSNEEDED / $row['kgs_bag'];
					$BAGSNEEDED = number_format($BAGSNEEDED ,4,'.','');
					//LANZA ERROR SI LAS BOLSAS ACTUALES SON MENORES A LAS QUE SE NECESITAN
					if($row['bags']<$BAGSNEEDED)
					{
						echo '<strong>ERROR</strong> The waste were not added to the production. Because there is not enought material <strong>'. $row['material_name'] .' - '. $row['material_grade'] .'</strong> in stock. <br> There are <strong>'. $row['bags'] .'</strong> bags in stock, and you need <strong>'. $BAGSNEEDED .'</strong> bags. Please try again receiving the raw material or updating the formula.';
						return false;
					}
					// VA CREANDO EL UPDATE PARA CAMBIAR DESPUES EL NUMERO DE BOLSAS DE STOCK_MATERIALS
					else
					{
						$newbags = $row['bags']-$BAGSNEEDED;
						$update = $update . "UPDATE  `stock_materials` SET `bags` = ".$newbags." WHERE `stock_material_id` = ". $row['stock_material_id']. "; ";
					}
				}
				else
				{
					echo '<strong>ERROR</strong> The waste were not added to the production. Because there is not enought material <strong>'. $row['material_name'] .' - '. $row['material_grade'] .'</strong> in stock. <br> Please try again receiving the raw material or updating the formula.';
						return false;
				}
            }
            
            //INSERT THE WASTE IN THE DAY DECREASES THE  KGS FROM THE  MULTILAYER_BATCHES_STOCK 
            $sql = "INSERT INTO  `waste`(`waste_id`,`date_waste`,`shift`,`machine_id`,`waste`,`user_id`,`type`) VALUES (NULL,'". $date."', ". $shift .",". $machine .", ". $film .", ". $_SESSION['Userid'] .", 1), (NULL,'". $date."', ". $shift .",". $machine .", ". $block .", ". $_SESSION['Userid'] .", 2);". $update;
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
	
	/**
    * Checks and inserts the waste
    *
    * @return boolean true if can insert false if not
    */
    public function createExtruderWaste()
    {
        
        $waste = trim($_POST["waste"]);
        $waste = stripslashes($waste);
        $waste = htmlspecialchars($waste); 
		
        $date = date("Y-m-d");
		if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = $newDateString;
        }
        
        $sql = "SELECT stock_material_id, `stock_materials`.`bags`, kgs_bag, material_name, material_grade
				FROM `stock_materials`
				RIGHT JOIN `materials` ON  `materials`.material_id = `stock_materials`.material_id
				WHERE `machine_id` = 7;";
		
		$update = "";
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['stock_material_id']))
				{
					$KGSNEEDED = $waste;
					$BAGSNEEDED = $KGSNEEDED / $row['kgs_bag'];
					$BAGSNEEDED = number_format($BAGSNEEDED ,4,'.','');
					//LANZA ERROR SI LAS BOLSAS ACTUALES SON MENORES A LAS QUE SE NECESITAN
					if($row['bags']<$BAGSNEEDED)
					{
						echo '<strong>ERROR</strong> The waste were not added to the production. Because there is not enought material <strong>'. $row['material_name'] .' - '. $row['material_grade'] .'</strong> in stock. <br> There are <strong>'. $row['bags'] .'</strong> bags in stock, and you need <strong>'. $BAGSNEEDED .'</strong> bags. Please try again receiving the raw material or updating the formula.';
						return false;
					}
					// VA CREANDO EL UPDATE PARA CAMBIAR DESPUES EL NUMERO DE BOLSAS DE STOCK_MATERIALS
					else
					{
						$newbags = $row['bags']-$BAGSNEEDED;
						$update = $update . "UPDATE  `stock_materials` SET `bags` = ".$newbags." WHERE `stock_material_id` = ". $row['stock_material_id']. "; ";
					}
				}
				else
				{
					echo '<strong>ERROR</strong> The waste were not added to the production. Because there is not enought material <strong>'. $row['material_name'] .' - '. $row['material_grade'] .'</strong> in stock. <br> Please try again receiving the raw material or updating the formula.';
						return false;
				}
            }
            
            //INSERT THE WASTE IN THE DAY DECREASES THE  KGS FROM THE  MULTILAYER_BATCHES_STOCK 
            $sql = "INSERT INTO  `waste`(`waste_id`,`date_waste`,`shift`,`machine_id`,`waste`,`user_id`,`type`) VALUES (NULL,'". $date."', 0,7, ". $waste .", ". $_SESSION['Userid'] .",0);". $update;
            try
            {   
                $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                    $stmt = $this->_db->prepare($sql);
                    $stmt->execute();
                    $stmt->closeCursor();
                    echo '<strong>SUCCESS!</strong> The waste were successfully added to the database';
                    return TRUE;
                } 
            catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    echo '<strong>ERROR</strong> The waste have already being register for this day.<br>';
                } 
                else {
                    echo '<strong>ERROR</strong> Could not insert the waste into the database. Please try again.<br>'. $e->getMessage();
                }
                return FALSE;
            }
        }
    }
	
	
	/**
     * Loads the table of all the waste in the multilayer section
     * This function outputs <tr> tags with the waste
     */
    public function giveWaste($location)
    {
		$sql = "SELECT 
    machine_name,
    `waste`.`date_waste`,
    `waste`.`shift`,
    `waste`.`waste` as film, block_waste.waste as block,
    username
FROM
    `waste`
        NATURAL JOIN
    users
        NATURAL JOIN
    machines
        LEFT JOIN
    `waste` block_waste ON `waste`.date_waste = block_waste.date_waste
        AND `waste`.machine_id = block_waste.machine_id
        AND `waste`.shift = block_waste.shift
        AND block_waste.type = 2 
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
                $TOTAL = $row['film'] + $row['block'];
                echo '<tr>
                        <td>'. $DATE .'</td>
                        <td>'. $SHIFT .'</td>
                        <td>'. $row['machine_name'] .'</td>
                        <td>'. $USER .'</td>
                        <td class="text-right">'. number_format($row['film'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['block'],2,'.',',') .'</td>
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
	
	/**
     * Loads the table of all the waste in the multilayer section
     * This function outputs <tr> tags with the waste
     */
    public function giveSectionWaste($machine)
    {
//		$sql = "SELECT 
//    `waste`.`date_waste`,
//    `waste`.`waste` , type,
//    username
//FROM
//    `waste`
//        NATURAL JOIN
//    users
//WHERE
//	`waste`.type = 0 AND machine_id = ". $machine." AND MONTH(`waste`.date_waste) >= MONTH(CURRENT_DATE())-1 AND YEAR(`waste`.date_waste) = YEAR(CURRENT_DATE())
//ORDER BY `waste`.date_waste DESC,  `waste`.machine_id;";
		$sql = "SELECT 
    `waste`.`date_waste`,
    `waste`.`waste` , type,
    username
FROM
    `waste`
        NATURAL JOIN
    users
WHERE  machine_id = ". $machine." 
ORDER BY `waste`.date_waste DESC,  `waste`.machine_id;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $DATE = $row['date_waste'];
                $USER = $row['username'];
                $TOTAL = $row['waste'];
				$TYPE = '';
				if($row['type']==0)
				{
					$TYPE = 'Sweeping waste';
				}
				else if($row['type']==3)
				{
					$TYPE = 'Rejected Roll';
				}
                echo '<tr>
                        <td>'. $DATE .'</td>
                        <td>'. $USER .'</td>
                        <td>'. $TYPE .'</td>
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
	
	/**
     * Loads the table of all the waste in the multilayer section
     * This function outputs <tr> tags with the waste
     */
    public function givePackingWaste()
    {
		$sql = "SELECT
    `waste`.`date_waste`,
    `waste`.`shift`,
    `waste`.`waste`,
    username
FROM
`waste`
NATURAL JOIN
    users
WHERE
	machine_id = 31
ORDER BY `waste`.date_waste DESC,  `waste`.`shift`";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $DATE = $row['date_waste'];
                $USER = $row['username'];
                $SHIFT = $this->giveShiftname($row['shift']);
                $TOTAL = $row['waste'];
                echo '<tr>
                        <td>'. $DATE .'</td>
                        <td>'. $SHIFT .'</td>
                        <td>'. $USER .'</td>
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
	
	
	
	/**
     * Checks gives the settings
     *
     */
    public function giveSettings($location)
    {
        $sql = "SELECT `settings`.`name_setting`, `settings`.`value_setting`
                FROM  `settings` 
				JOIN `machines` ON `machines`.machine_id = `settings`.machine_id
                WHERE location_id=". $location .";";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $NAME = $row['name_setting'];
                $VALUE = $row['value_setting'];
                echo '<script>
            document.getElementById("'. $NAME .'").value = "'. $VALUE .'";</script>';
            }
            $stmt->closeCursor();
            
        }  
        else
        {
            echo "Something went wrong. $db->errorInfo";
        }
    }
	
	/**
     * Checks gives the settings
     *
     */
    public function editSettings()
    {
        $value = trim($_POST["input"]);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        
        $name = trim($_POST["action"]);
        $name = stripslashes($name);
        $name = htmlspecialchars($name);
        
		$machine = trim($_POST["machine"]);
        $machine = stripslashes($machine);
        $machine = htmlspecialchars($machine);
		
        $sql = "UPDATE  `settings`
                SET `to` = CURRENT_DATE, `actual` = 0
                WHERE machine_id = ". $machine ." AND `name_setting` = '". $name ."' AND `actual` = 1;
				INSERT INTO `settings`(`setting_id`,`machine_id`,`name_setting`,`value_setting`,`from`,
				`to`,`actual`)VALUES
				(NULL,". $machine .",'". $name ."','". $value ."',CURRENT_DATE(),NULL,1);";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->execute();
            $stmt->closeCursor();
            echo '<strong>SUCCESS!</strong> The '. $name .' was successfully updated to the value: <strong>'. $value .'</strong>.';
            return TRUE;
        } catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not update the'. $name .' in the database. Please try again.<br>'. $e->getMessage(); 
            return FALSE;
        } 
    }
	
	
	
	/**
     * Loads the Efficiency Report in the multilayer section
     * This function outputs <tr> tags with the report
     */
    public function reportEfficiency()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Machine</th>';
        echo '<th>Machine Capacity</th>';
        echo '<th>Orders Target</th>';
        echo '<th>Actual Production</th>';
        echo '<th>% Eff</th>';
        echo '<th>Waste in Kgs</th>';
        echo '<th>Target Waste %</th>';
        echo '<th>Waste %</th>';
        echo '</tr></thead>
			<tfoot><tr  class="active">
			<th style="text-align:right">Total</th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			</tr></tfoot><tbody>'; 
        
        $a=array();
        $b1=array();
        $b2=array();
        $b3=array();
        $b4=array();
        $b5=array();
        $b6=array();
        $b7=array();
        $b8=array();
        $c=array();
        $d1=array();
        $d2=array();
        $d3=array();
        $d4=array();
        $d5=array();
        $d6=array();
        $d7=array();
        $d8=array();
        $e=array();
		
		        
        $newDateString = date("Y-m-d");
        $newDateString2 = date("Y-m-d");
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
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
            
			
            $sql = "SELECT 
    DATE_FORMAT(`date_roll`, '%b/%Y') AS date, DATE_FORMAT(`date_roll`, '%m/%Y') as date2, machine_name, sacks_rolls.machine_id,
    ROUND(SUM(net_weight), 2) AS actual,
    waste.wastekgs,
    COUNT(DISTINCT (DATE_FORMAT(`date_roll`, '%d/%m/%Y'))) AS days,
    target, target_waste, capacity
FROM
    sacks_rolls
LEFT JOIN machines ON sacks_rolls.machine_id = machines.machine_id
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%m/%Y') AS date,
            SUM(waste) AS wastekgs, machine_id
    FROM
        `waste`
	NATURAL JOIN machines
    WHERE location_id = 7 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%m/%Y'), machine_id
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%m/%Y') AND waste.machine_id = sacks_rolls.machine_id
	LEFT JOIN
    (SELECT 
        DATE_FORMAT(`target_orders`.`date`, '%m/%Y') AS date,
            SUM(target_order) AS target, machine_id
    FROM
        `target_orders`
   	NATURAL JOIN machines
    WHERE location_id = 7 
        AND date BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`target_orders`.`date`, '%m/%Y'), machine_id
    ORDER BY `target_orders`.`date`) targets ON targets.date = DATE_FORMAT(`date_roll`, '%m/%Y') AND targets.machine_id = sacks_rolls.machine_id
	LEFT JOIN
	(
		SELECT AVG(`settings`.value_setting) AS target_waste, DATE_FORMAT(`settings`.`to`, '%m/%Y') AS `to` , DATE_FORMAT(`settings`.`from`, '%m/%Y') AS `from`
        FROM `settings`
        WHERE `settings`.machine_id = 7 AND `settings`.name_setting = 'waste'
		GROUP BY DATE_FORMAT(`settings`.from, '%m/%Y')
    )
    waste_target ON waste_target.`from` <= DATE_FORMAT(`date_roll`, '%m/%Y') AND (waste_target.`to` IS NULL OR waste_target.`to` > DATE_FORMAT(`date_roll`, '%m/%Y'))
	LEFT JOIN
	(
		SELECT AVG(`settings`.value_setting) AS capacity, DATE_FORMAT(`settings`.`to`, '%m/%Y') AS `to` , DATE_FORMAT(`settings`.`from`, '%m/%Y') AS `from`, `settings`.machine_id
        FROM `settings`
		NATURAL JOIN machines
    	WHERE location_id = 7 
		GROUP BY DATE_FORMAT(`settings`.from, '%m/%Y'), `settings`.machine_id
    )
    capacity ON  capacity.machine_id = sacks_rolls.machine_id AND capacity.`from` <= DATE_FORMAT(`date_roll`, '%m/%Y') AND (capacity.`to` IS NULL OR capacity.`to` > DATE_FORMAT(`date_roll`, '%m/%Y'))
WHERE
    date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_roll`, '%b/%Y'), sacks_rolls.machine_id 
ORDER BY `date_roll`, sacks_rolls.machine_id  ;";
            
            
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
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
            
              $sql = "SELECT 
    DATE_FORMAT(`date_roll`, '%Y') AS date, machine_name, sacks_rolls.machine_id,
    ROUND(SUM(net_weight), 2) AS actual,
    waste.wastekgs,
    COUNT(DISTINCT (DATE_FORMAT(`date_roll`, '%d/%m/%Y'))) AS days,
    target, target_waste, capacity
FROM
    sacks_rolls
LEFT JOIN machines ON sacks_rolls.machine_id = machines.machine_id
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%Y') AS date,
            SUM(waste) AS wastekgs, machine_id
    FROM
        `waste`
	NATURAL JOIN machines
    WHERE location_id = 7 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%Y'), machine_id
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y') AND waste.machine_id = sacks_rolls.machine_id
	LEFT JOIN
    (SELECT 
        DATE_FORMAT(`target_orders`.`date`, '%Y') AS date,
            SUM(target_order) AS target, machine_id
    FROM
        `target_orders`
   	NATURAL JOIN machines
    WHERE location_id = 7 
        AND date BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`target_orders`.`date`, '%Y'), machine_id
    ORDER BY `target_orders`.`date`) targets ON targets.date = DATE_FORMAT(`date_roll`, '%Y') AND targets.machine_id = sacks_rolls.machine_id
	LEFT JOIN
	(
		SELECT AVG(`settings`.value_setting) AS target_waste, DATE_FORMAT(`settings`.`to`, '%m/%Y') AS `to` , DATE_FORMAT(`settings`.`from`, '%m/%Y') AS `from`
        FROM `settings`
        WHERE `settings`.machine_id = 8 AND `settings`.name_setting = 'waste'
		GROUP BY DATE_FORMAT(`settings`.from, '%Y')
    )
    waste_target ON waste_target.`from` <= DATE_FORMAT(`date_roll`, '%Y') AND (waste_target.`to` IS NULL OR waste_target.`to` > DATE_FORMAT(`date_roll`, '%Y'))
	LEFT JOIN
	(
		SELECT AVG(`settings`.value_setting) AS capacity, DATE_FORMAT(`settings`.`to`, '%m/%Y') AS `to` , DATE_FORMAT(`settings`.`from`, '%m/%Y') AS `from`, `settings`.machine_id
        FROM `settings`
		NATURAL JOIN machines
    	WHERE location_id = 7 
		GROUP BY DATE_FORMAT(`settings`.from, '%Y'), `settings`.machine_id
    )
    capacity ON  capacity.machine_id = sacks_rolls.machine_id AND capacity.`from` <= DATE_FORMAT(`date_roll`, '%Y') AND (capacity.`to` IS NULL OR capacity.`to` > DATE_FORMAT(`date_roll`, '%Y'))
WHERE
    date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_roll`, '%Y'), sacks_rolls.machine_id 
ORDER BY `date_roll`, sacks_rolls.machine_id ;";
            
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
            
            $sql = "SELECT 
    DATE_FORMAT(`date_roll`, '%d/%m/%Y') AS date, machine_name, sacks_rolls.machine_id,
    ROUND(SUM(net_weight), 2) AS actual,
    waste.wastekgs,
    COUNT(DISTINCT (DATE_FORMAT(`date_roll`, '%d/%m/%Y'))) AS days,
    target, target_waste, capacity
FROM
    sacks_rolls
LEFT JOIN machines ON sacks_rolls.machine_id = machines.machine_id
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%Y/%m/%d') AS date,
            SUM(waste) AS wastekgs, machine_id
    FROM
        `waste`
	NATURAL JOIN machines
    WHERE location_id = 7 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d'), machine_id
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y/%m/%d') AND waste.machine_id = sacks_rolls.machine_id
	LEFT JOIN
    (SELECT 
        DATE_FORMAT(`target_orders`.`date`, '%Y/%m/%d') AS date,
            SUM(target_order) AS target, machine_id
    FROM
        `target_orders`
   	NATURAL JOIN machines
    WHERE location_id = 7 
        AND date BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`target_orders`.`date`, '%Y/%m/%d'), machine_id
    ORDER BY `target_orders`.`date`) targets ON targets.date = DATE_FORMAT(`date_roll`, '%Y/%m/%d') AND targets.machine_id = sacks_rolls.machine_id
	LEFT JOIN
	(
		SELECT `settings`.value_setting AS target_waste, `settings`.to, `settings`.from
        FROM `settings`
        WHERE `settings`.machine_id = 8 AND `settings`.name_setting = 'waste'
    )
    waste_target ON waste_target.`from` <= DATE_FORMAT(`date_roll`, '%Y/%m/%d') AND (waste_target.`to` IS NULL OR waste_target.`to` > DATE_FORMAT(`date_roll`, '%Y/%m/%d'))
	LEFT JOIN
	(
		SELECT `settings`.value_setting AS capacity, `settings`.to, `settings`.from, `settings`.machine_id
        FROM `settings`
		NATURAL JOIN machines
    	WHERE location_id = 7 
    )
    capacity ON  capacity.machine_id = sacks_rolls.machine_id AND capacity.`from` <= DATE_FORMAT(`date_roll`, '%Y/%m/%d') AND (capacity.`to` IS NULL OR capacity.`to` > DATE_FORMAT(`date_roll`, '%Y/%m/%d'))
WHERE
    date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_roll`, '%d/%m/%Y'), sacks_rolls.machine_id 
ORDER BY `date_roll`, sacks_rolls.machine_id ;";
            
        }
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $CAPACITYROW = $row['capacity'] * $row['days'];
				$TARGET = $row['target'];
				$TARGETWASTE = $row['target_waste'];
                $WASTEKG = $row['wastekgs'];
                if(is_null($row['wastekgs']))
                {
                    $WASTEKG = 0;
                }
                $ACTUAL = $row['actual'] + $WASTEKG;
                if(is_null($row['actual']))
                {
                    $ACTUAL = 0 + $WASTEKG;
                    $WASTEEFF = 0;
                }
                else
                {
                    $WASTEEFF  = round($WASTEKG* 100 / $ACTUAL , 2);
                }
				if(is_null($row['target']) and !is_null($row['capacity']))
                {
                    $TARGET = $CAPACITYROW;
					$EFF = round($ACTUAL *100/ $CAPACITYROW, 2);
                }
				else if(is_null($row['capacity']))
                {
                    $TARGET = $CAPACITYROW;
					$EFF = 100;
                }
                else
                {
					$EFF = round($ACTUAL *100/ $TARGET, 2);
                }
                echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. $row['machine_name'] .'</td>
                        <td class="text-right">'. number_format($CAPACITYROW,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($TARGET,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($ACTUAL,2,'.',',') .'</td>
                        <th class="text-right">'. number_format($EFF,2,'.',',') .'</th>
                        <td class="text-right">'. number_format($WASTEKG,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($TARGETWASTE,2,'.',',') .'</td>
                        <th class="text-right">'. number_format($WASTEEFF,2,'.',',') .'</th>
                    </tr>';
                $entrie0 = array( $row['date'], $CAPACITYROW);
                $entrie = array( $row['date'], $TARGET);
                $entrie1 = array( $row['date'], $ACTUAL);
                $entrie2 = array( $row['date'], $TARGETWASTE);
                $entrie3 = array( $row['date'], $WASTEEFF);
                if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
                {
                    $entrie0 = array( $row['date2'], $CAPACITYROW);
					$entrie = array( $row['date2'], $TARGET);
                    $entrie1 = array( $row['date2'],$ACTUAL);
                    $entrie2 = array( $row['date2'], $TARGETWASTE);
                    $entrie3 = array( $row['date2'], $WASTEEFF);
                }
                array_push($a,$entrie);
				if($row['machine_id'] == 13)
				{
                	array_push($b1,$entrie1);
				}
				else if($row['machine_id'] == 14)
				{
                	array_push($b2,$entrie1);
				}
				else if($row['machine_id'] == 15)
				{
                	array_push($b3,$entrie1);
				}
				else if($row['machine_id'] == 16)
				{
                	array_push($b4,$entrie1);
				}
				else if($row['machine_id'] == 17)
				{
                	array_push($b5,$entrie1);
				}
				else if($row['machine_id'] == 18)
				{
                	array_push($b6,$entrie1);
				}
				else if($row['machine_id'] == 19)
				{
                	array_push($b7,$entrie1);
				}
				else if($row['machine_id'] == 20)
				{
                	array_push($b8,$entrie1);
				}
                array_push($c,$entrie2);
				if($row['machine_id'] == 13)
				{
                	array_push($d1,$entrie3);
				}
				else if($row['machine_id'] == 14)
				{
                	array_push($d2,$entrie3);
				}
				else if($row['machine_id'] == 15)
				{
                	array_push($d3,$entrie3);
				}
				else if($row['machine_id'] == 16)
				{
                	array_push($d4,$entrie3);
				}
				else if($row['machine_id'] == 17)
				{
                	array_push($d5,$entrie3);
				}
				else if($row['machine_id'] == 18)
				{
                	array_push($d6,$entrie3);
				}
				else if($row['machine_id'] == 19)
				{
                	array_push($d7,$entrie3);
				}
				else if($row['machine_id'] == 20)
				{
                	array_push($d8,$entrie3);
				}
                array_push($e,$entrie0);
            }
            $stmt->closeCursor();
        }
        else
        {
            echo "<tr>
                    <td>Something went wrong.</td>
                    <td>$db->errorInfo</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>";
        }
         echo '</tbody>';
        echo '<script>document.getElementById("chartContainer").style= "height:200px;";</script>';
        echo '<script>document.getElementById("chartContainer2").style= "height:200px;";</script>';
         echo '<script> 
            var chart = new CanvasJS.Chart("chartContainer", {
            theme: "light2",
            title: { 
                text: "Production Target Achievement"
            },
            exportFileName: "Production Target Achievement",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "KGS" },
            toolTip: {
                shared: true
            },
            legend:{
                itemclick : function(e){
				
					if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
						e.dataSeries.visible = false;
					} else {
						e.dataSeries.visible = true;
					}
					chart.render();
				}
            },';
        if(!empty($_POST['searchBy']) and  $_POST['searchBy']==2)
        {  
            echo 'axisX:{ valueFormatString: "MMM YYYY"},';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            echo 'axisX:{ valueFormatString: "YYYY"},';
        }
        else
        {
            echo 'axisX:{ valueFormatString: "DD MMM"},';
        }
        echo 'data: [
            {
                type: "line",
		      showInLegend: true,
		      name: "Extruder - 1",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,###.00 Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($b1 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($b1 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($b1 as $key=>$value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
        echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Extruder - 2",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,###.00 Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($b2 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($b2 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($b2 as $key=>$value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Extruder - 3",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,###.00 Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($b3 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($b3 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($b3 as $key=>$value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Extruder - 4",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,###.00 Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($b4 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($b4 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($b4 as $key=>$value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Extruder - 5",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,###.00 Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($b5 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($b5 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($b5 as $key=>$value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Extruder - 6",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,###.00 Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($b6 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($b6 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($b6 as $key=>$value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Extruder - 7",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,###.00 Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($b7 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($b7 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($b7 as $key=>$value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Extruder - 8",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,###.00 Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($b8 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($b8 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($b8 as $key=>$value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        echo'] }]});
        chart.render();
        </script>'; 
        echo '<script> 
            var chart1 = new CanvasJS.Chart("chartContainer2", {
            theme: "light2",
            title: { 
                text: "Waste Target Achievement"
            },
            exportFileName: "Waste Target Achievement",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "Waste %" },
            toolTip: {
                shared: true
            },
			legend:{
                itemclick : function(e){
				
					if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
						e.dataSeries.visible = false;
					} else {
						e.dataSeries.visible = true;
					}
					chart1.render();
				}
            },';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'axisX:{ valueFormatString: "MMM YYYY"},';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            echo 'axisX:{ valueFormatString: "YYYY"},';
        }
        else
        {
            echo 'axisX:{ valueFormatString: "DD MMM"},';
        }
        echo 'data: [
            {
                type: "line",
		showInLegend: true,
		name: "Target",
		lineDashType: "dash",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.0",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($c as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1] .'},';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($c as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($c as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
        echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Extruder - 1 ",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.00 ",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($d1 as $key => $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($d1 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($d1 as $key => $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		 echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Extruder - 2",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.0 ",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($d2 as $key => $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($d2 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($d2 as $key => $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Extruder - 3",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.0 ",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($d3 as $key => $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($d3 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($d3 as $key => $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Extruder - 4",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.0 ",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($d4 as $key => $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($d4 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($d4 as $key => $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Extruder - 5",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.0 ",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($d5 as $key => $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($d5 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($d5 as $key => $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Extruder - 6",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.0 ",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($d6 as $key => $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($d6 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($d6 as $key => $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Extruder - 7",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.0 ",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($d7 as $key => $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($d7 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($d7 as $key => $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Extruder - 8",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.0 ",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($d8 as $key => $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($d8 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($d8 as $key => $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        echo'] }]});
        chart1.render(); 
        </script>'; 
    }
	
	
	
	/**
     * Loads the Production Report in the multilayer section
     * This function outputs <tr> tags with the report
     */
    public function reportProduction()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Machine</th>';
        echo '<th>Good Production</th>';
        echo '<th>No. of rolls produced</th>';
        echo '</tr></thead><tfoot><tr  class="active">
			<th style="text-align:right">Total</th>
			<th></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th></tr></tfoot><tbody>';   
        
        $a1=array();
        $a2=array();
        $a3=array();
        $a4=array();
        $a5=array();
        $a6=array();
        $a7=array();
        $a8=array();
        $b1=array();
        $b2=array();
        $b3=array();
        $b4=array();
        $b5=array();
        $b6=array();
        $b7=array();
        $b8=array();
		
		
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
            
            $sql = " SELECT DATE_FORMAT(`date_roll`, '%b/%Y') as date, DATE_FORMAT(`date_roll`, '%m/%Y') as date2, ROUND(SUM(net_weight),2) as actual, COUNT(sacks_rolls_id) as rolls, machine_name, sacks_rolls.machine_id   
             FROM sacks_rolls
             LEFT JOIN machines ON sacks_rolls.machine_id = machines.machine_id
             WHERE `sacks_rolls`.date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_roll`, '%b/%Y'), sacks_rolls.machine_id 
             ORDER BY `date_roll`;";
            
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
            
            $sql = " SELECT DATE_FORMAT(`date_roll`, '%Y') as date, ROUND(SUM(net_weight),2) as actual, COUNT(sacks_rolls_id) as rolls, machine_name, sacks_rolls.machine_id   
             FROM sacks_rolls
             LEFT JOIN machines ON sacks_rolls.machine_id = machines.machine_id
             WHERE `sacks_rolls`.date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_roll`, '%Y'), sacks_rolls.machine_id 
             ORDER BY `date_roll`;";
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
            
            $sql = " SELECT DATE_FORMAT(`date_roll`, '%d/%m/%Y') as date, ROUND(SUM(net_weight),2) as actual, COUNT(sacks_rolls_id) as rolls, machine_name, sacks_rolls.machine_id   
             FROM sacks_rolls
             LEFT JOIN machines ON sacks_rolls.machine_id = machines.machine_id
             WHERE `sacks_rolls`.date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_roll`, '%d/%m/%Y'), sacks_rolls.machine_id 
             ORDER BY `date_roll`;";
            
        }
        
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. $row['machine_name'] .'</td>
                        <td class="text-right">'. number_format($row['actual'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['rolls'],0,'.',',') .'</td>
                    </tr>';
                $entrie = array( $row['date'], $row['actual']);
                $entrie1 = array( $row['date'], $row['rolls']);
                if($_POST['searchBy']==2)
                {
                    $entrie = array( $row['date2'], $row['actual']);
                    $entrie1 = array( $row['date2'], $row['rolls']);
                }
				if($row['machine_id'] == 13)
				{
                	array_push($a1,$entrie);
				}
				else if($row['machine_id'] == 14)
				{
                	array_push($a2,$entrie);
				}
				else if($row['machine_id'] == 15)
				{
                	array_push($a3,$entrie);
				}
				else if($row['machine_id'] == 16)
				{
                	array_push($a4,$entrie);
				}
				else if($row['machine_id'] == 17)
				{
                	array_push($a5,$entrie);
				}
				else if($row['machine_id'] == 18)
				{
                	array_push($a6,$entrie);
				}
				else if($row['machine_id'] == 19)
				{
                	array_push($a7,$entrie);
				}
				else if($row['machine_id'] == 20)
				{
                	array_push($a8,$entrie);
				}
				
				if($row['machine_id'] == 13)
				{
                	array_push($b1,$entrie1);
				}
				else if($row['machine_id'] == 14)
				{
                	array_push($b2,$entrie1);
				}
				else if($row['machine_id'] == 15)
				{
                	array_push($b3,$entrie1);
				}
				else if($row['machine_id'] == 16)
				{
                	array_push($b4,$entrie1);
				}
				else if($row['machine_id'] == 17)
				{
                	array_push($b5,$entrie1);
				}
				else if($row['machine_id'] == 18)
				{
                	array_push($b6,$entrie1);
				}
				else if($row['machine_id'] == 19)
				{
                	array_push($b7,$entrie1);
				}
				else if($row['machine_id'] == 20)
				{
                	array_push($b8,$entrie1);
				}
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
        echo '<script>document.getElementById("chartContainer").style= "height:200px;";</script>';
        echo '<script>document.getElementById("chartContainer2").style= "height:200px;";</script>';
         echo '<script> 
            var chart = new CanvasJS.Chart("chartContainer", {
            theme: "light2",
            title: { 
                text: "Production "
            },
            exportFileName: "Production",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "Total net production (kgs)" },
            toolTip: {
                shared: true
            },legend:{
                itemclick : function(e){
				
					if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
						e.dataSeries.visible = false;
					} else {
						e.dataSeries.visible = true;
					}
					chart.render();
				}
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
		      name: "Extruder - 1",';
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
            foreach($a1 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a1 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a1 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		 echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 2",';
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
            foreach($a2 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a2 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a2 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 3",';
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
            foreach($a3 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a3 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a3 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 4",';
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
            foreach($a4 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a4 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a4 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 5",';
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
            foreach($a5 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a5 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a5 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 6",';
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
            foreach($a6 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a6 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a6 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 7",';
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
            foreach($a7 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a7 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a7 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 8",';
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
            foreach($a8 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a8 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a8 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
        echo'] }]});
        chart.render(); 
        </script>'; 
        echo '<script> 
            var chart1 = new CanvasJS.Chart("chartContainer2", {
            theme: "light2",
            title: { 
                text: "No. of rolls produced"
            },
            exportFileName: "No. of rolls produced",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "Rolls" },
            toolTip: {
                shared: true
            },
			legend:{
                itemclick : function(e){
				
					if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
						e.dataSeries.visible = false;
					} else {
						e.dataSeries.visible = true;
					}
					chart1.render();
				}
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
		      name: "Extruder - 1",';
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
        echo ' yValueFormatString: "#,### rolls",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($b1 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($b1 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($b1 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 2",';
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
        echo ' yValueFormatString: "#,### rolls",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($b2 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($b2 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($b2 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 3",';
        if($_POST['searchBy']==3)
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
        echo ' yValueFormatString: "#,### rolls",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($b3 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($b3 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($b3 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 4",';
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
        echo ' yValueFormatString: "#,### rolls",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($b4 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($b4 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($b4 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 5",';
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
        echo ' yValueFormatString: "#,### rolls",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($b5 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($b5 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($b5 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 6",';
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
        echo ' yValueFormatString: "#,### rolls",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($b6 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($b6 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($b6 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 7",';
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
        echo ' yValueFormatString: "#,### rolls",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($b7 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($b7 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($b7 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 8",';
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
        echo ' yValueFormatString: "#,### rolls",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($b8 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($b8 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($b8 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
        echo'] }]});
        chart1.render(); 
        </script>';
    }
	
	
		/**
     * Loads the Waste Report in the multilayer section
     * This function outputs <tr> tags with the report
     */
    public function reportWaste()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Machine</th>';
        echo '<th>Total Waste</th>';
        echo '</tr></thead><tfoot><tr  class="active">
			<th style="text-align:right">Total</th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th></tr></tfoot><tbody>';   
        
        $a1=array();
        $a2=array();
        $a3=array();
        $a4=array();
        $a5=array();
        $a6=array();
        $a7=array();
        $a8=array();
		
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
            
            $sql = " SELECT DATE_FORMAT(`date_waste`, '%b/%Y') as date, DATE_FORMAT(`date_waste`, '%m/%Y') as date2, SUM(`waste`.`waste`) AS total, machine_name, waste.machine_id
             FROM  `waste`
             LEFT JOIN machines ON waste.machine_id = machines.machine_id
             WHERE location_id=7 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_waste`, '%b/%Y'), waste.machine_id  
             ORDER BY `date_waste`;";
            
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
            
            $sql = " SELECT DATE_FORMAT(`date_waste`, '%Y') as date, SUM(`waste`.`waste`) AS total, machine_name, waste.machine_id
             FROM  `waste`
             LEFT JOIN machines ON waste.machine_id = machines.machine_id
             WHERE location_id=7 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_waste`, '%Y'), waste.machine_id
             ORDER BY `date_waste`;";
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
            
            $sql = " SELECT DATE_FORMAT(`date_waste`, '%d/%m/%Y') AS date, SUM(`waste`.`waste`) AS total, machine_name, waste.machine_id
             FROM  `waste`
             LEFT JOIN machines ON waste.machine_id = machines.machine_id  
             WHERE location_id=7 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_waste`, '%d/%m/%Y'), waste.machine_id 
             ORDER BY `date_waste`;";
            
        }
        
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. $row['machine_name'] .'</td>
                        <td class="text-right">'. number_format($row['total'],2,'.',',') .'</td>
                    </tr>';
                $entrie = array( $row['date'], $row['total']);
                if($_POST['searchBy']==2)
                {
                    $entrie = array( $row['date2'], $row['total']);
                }
                if($row['machine_id'] == 13)
				{
                	array_push($a1,$entrie);
				}
				else if($row['machine_id'] == 14)
				{
                	array_push($a2,$entrie);
				}
				else if($row['machine_id'] == 15)
				{
                	array_push($a3,$entrie);
				}
				else if($row['machine_id'] == 16)
				{
                	array_push($a4,$entrie);
				}
				else if($row['machine_id'] == 17)
				{
                	array_push($a5,$entrie);
				}
				else if($row['machine_id'] == 18)
				{
                	array_push($a6,$entrie);
				}
				else if($row['machine_id'] == 19)
				{
                	array_push($a7,$entrie);
				}
				else if($row['machine_id'] == 20)
				{
                	array_push($a8,$entrie);
				}
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
		name: "Extruder - 1",';
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
            foreach($a1 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a1 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a1 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		showInLegend: true,
		name: "Extruder - 2",';
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
            foreach($a2 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a2 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a2 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 3",';
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
            foreach($a3 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a3 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a3 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 4",';
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
            foreach($a4 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a4 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a4 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 5",';
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
            foreach($a5 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a5 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a5 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 6",';
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
            foreach($a6 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a6 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a6 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 7",';
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
            foreach($a7 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a7 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a7 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Extruder - 8",';
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
            foreach($a8 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a8 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a8 as $value) {
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
     * Loads the Downtime, Remarks, Reason Report in the multilayer section
     * This function outputs <tr> tags with the report
     */
    public function reportReason()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Machine</th>';
        echo '<th>Downtime</th>';
        echo '<th>Reason for Short Fall</th>';
        echo '<th>Action Plan</th>';
        echo '</tr></thead><tbody>';   
        
        $a1=array();
        $a2=array();
        $a3=array();
        $a4=array();
        $a5=array();
        $a6=array();
        $a7=array();
        $a8=array();
		
        
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
            
            $sql = "
			SELECT 
				DATE_FORMAT(`date_fall`, '%b/%Y') AS date, DATE_FORMAT(`date_fall`, '%m/%Y') as date2,
				SEC_TO_TIME(SUM(TIME_TO_SEC(`shortfalls`.`downtime`))) AS total,
				SUM(HOUR(`shortfalls`.`downtime`)) AS hours,
				SUM(MINUTE(`shortfalls`.`downtime`)) AS minutes, (SELECT 
					SEC_TO_TIME(SUM(TIME_TO_SEC(`shortfalls`.`downtime`))) AS total_time
				FROM
					`shortfalls`
				LEFT JOIN machines ON shortfalls.machine_id = machines.machine_id 
				WHERE
					location_id=7 AND  `shortfalls`.`date_fall`  BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59') AS total_time,
				GROUP_CONCAT(`shortfalls`.`reason`
					SEPARATOR '<br />') AS reason,
				GROUP_CONCAT(`shortfalls`.`action_plan`
					SEPARATOR '<br />') AS action, machine_name, shortfalls.machine_id
			FROM
				`shortfalls`			
             LEFT JOIN machines ON shortfalls.machine_id = machines.machine_id
			 WHERE
				location_id=7 AND `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
			GROUP BY DATE_FORMAT(`date_fall`, '%b/%Y'), shortfalls.machine_id
			ORDER BY `date_fall`;";
            
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
            
            $sql = "
			SELECT 
				DATE_FORMAT(`date_fall`, '%Y') AS date,
				SEC_TO_TIME(SUM(TIME_TO_SEC(`shortfalls`.`downtime`))) AS total,
				SUM(HOUR(`shortfalls`.`downtime`)) AS hours,
				SUM(MINUTE(`shortfalls`.`downtime`)) AS minutes, (SELECT 
					SEC_TO_TIME(SUM(TIME_TO_SEC(`shortfalls`.`downtime`))) AS total_time
				FROM
					`shortfalls`
				LEFT JOIN machines ON shortfalls.machine_id = machines.machine_id 
				WHERE
					location_id=7 AND  `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59') AS total_time,
				GROUP_CONCAT(`shortfalls`.`reason`
					SEPARATOR '<br />') AS reason,
				GROUP_CONCAT(`shortfalls`.`action_plan`
					SEPARATOR '<br />') AS action, machine_name, shortfalls.machine_id
			FROM
				`shortfalls`
					
             LEFT JOIN machines ON shortfalls.machine_id = machines.machine_id
			 WHERE
				location_id=7 AND `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
			GROUP BY DATE_FORMAT(`date_fall`, '%Y'), shortfalls.machine_id
			ORDER BY `date_fall`;";
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
            
            $sql = "
			SELECT 
				DATE_FORMAT(`date_fall`, '%d/%m/%Y') AS date,
				SEC_TO_TIME(SUM(TIME_TO_SEC(`shortfalls`.`downtime`))) AS total,
				SUM(HOUR(`shortfalls`.`downtime`)) AS hours,
				SUM(MINUTE(`shortfalls`.`downtime`)) AS minutes, (SELECT 
					SEC_TO_TIME(SUM(TIME_TO_SEC(`shortfalls`.`downtime`))) AS total_time
				FROM
					`shortfalls`
				LEFT JOIN machines ON shortfalls.machine_id = machines.machine_id 
				WHERE
					location_id=7 AND  `shortfalls`.`date_fall`  BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59') AS total_time,
				GROUP_CONCAT(`shortfalls`.`reason`
					SEPARATOR '<br />') AS reason,
				GROUP_CONCAT(`shortfalls`.`action_plan`
					SEPARATOR '<br />') AS action, machine_name, shortfalls.machine_id 
			FROM
				`shortfalls`
			LEFT JOIN machines ON shortfalls.machine_id = machines.machine_id
			WHERE
				location_id=7 AND `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
			GROUP BY DATE_FORMAT(`date_fall`, '%d/%m/%Y'), shortfalls.machine_id
			ORDER BY `date_fall`;";
            
        }
		
        $TOTAL = 0;
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
			   if($_POST['searchBy']==1)
        		{ 
					$myDateTime = DateTime::createFromFormat('d/m/Y', $row['date']);
				   $day = $myDateTime->format('w');
					if($day == 0)
					{
						echo '<tr class="warning">
							<td class="text-right">'. $row['date'] .'</td>
							<td class="text-right">'. $row['machine_name'] .'</td>
							<td class="text-right">'. $row['total'] .'</td>
							<td>'. $row['reason'] .'</td>
							<td>'. $row['action'] .'</td>
						</tr>';
					}
				   else
					{
						echo '<tr>
							<td class="text-right">'. $row['date'] .'</td>
							<td class="text-right">'. $row['machine_name'] .'</td>
							<td class="text-right">'. $row['total'] .'</td>
							<td>'. $row['reason'] .'</td>
							<td>'. $row['action'] .'</td>
						</tr>';
					}
			   }
				else
				{
                	echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. $row['machine_name'] .'</td>
                        <td class="text-right">'. $row['total'] .'</td>
                        <td>'. $row['reason'] .'</td>
                        <td>'. $row['action'] .'</td>
                    </tr>';
				}
                $hours = $row['hours'] + ($row['minutes'] / 60);
                $entrie = array( $row['date'], $hours);
                if($_POST['searchBy']==2)
                {
                    $entrie = array( $row['date2'], $hours);
                }
				if($row['machine_id'] == 13)
				{
                	array_push($a1,$entrie);
				}
				else if($row['machine_id'] == 14)
				{
                	array_push($a2,$entrie);
				}
				else if($row['machine_id'] == 15)
				{
                	array_push($a3,$entrie);
				}
				else if($row['machine_id'] == 16)
				{
                	array_push($a4,$entrie);
				}
				else if($row['machine_id'] == 17)
				{
                	array_push($a5,$entrie);
				}
				else if($row['machine_id'] == 18)
				{
                	array_push($a6,$entrie);
				}
				else if($row['machine_id'] == 19)
				{
                	array_push($a7,$entrie);
				}
				else if($row['machine_id'] == 20)
				{
                	array_push($a8,$entrie);
				}
				
				$TOTAL = $row['total_time'];
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
		
		
        echo '</tbody><tfoot><tr  class="active"><th></th>
			<th style="text-align:right"></th>
			<th style="text-align:right">Total</th>
			<th style="text-align:right"></th>
			<th></th></tr></tfoot>';
        echo '<script>document.getElementById("divChart1").setAttribute("class","col-md-12");</script>';
        echo '<script>document.getElementById("chartContainer").style= "height:200px;width:100%";</script>';
         echo '<script> 
            var chart = new CanvasJS.Chart("chartContainer", {
            theme: "light2",
            title: { 
                text: "Downtime"
            },
            exportFileName: "Downtime",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "Hours" },
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
		name: "Extruder - 1",';
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
        echo ' yValueFormatString: "#,###.# Hours",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a1 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a1 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a1 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		showInLegend: true,
		name: "Extruder - 2",';
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
        echo ' yValueFormatString: "#,###.# Hours",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a2 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a2 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a2 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		showInLegend: true,
		name: "Extruder - 3",';
        if($_POST['searchBy']==3)
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
        echo ' yValueFormatString: "#,###.# Hours",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a3 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a3 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a3 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		showInLegend: true,
		name: "Extruder - 4",';
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
        echo ' yValueFormatString: "#,###.# Hours",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a4 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a4 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a4 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		showInLegend: true,
		name: "Extruder - 5",';
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
        echo ' yValueFormatString: "#,###.# Hours",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a5 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a5 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a5 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		showInLegend: true,
		name: "Extruder - 6",';
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
        echo ' yValueFormatString: "#,###.# Hours",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a6 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a6 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a6 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		showInLegend: true,
		name: "Extruder - 7",';
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
        echo ' yValueFormatString: "#,###.# Hours",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a7 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a7 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a7 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		showInLegend: true,
		name: "Extruder - 8",';
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
        echo ' yValueFormatString: "#,###.# Hours",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a8 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a8 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a8 as $value) {
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
     * Loads the Raw Material Consumption Report in the multilayer section
     * This function outputs <tr> tags with the report
     */
    public function reportMaterialConsumption()
    {
       
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
        }
		$materialid = "";
		$materialname = "";
		$materialgrade = "";
		
		$sql2 = "SELECT `materials`.`material_id`,
					`materials`.`material_name`,
					`materials`.`material_grade`
				FROM `materials`
				WHERE  `materials`.`sacks` =1 ANd `material`=1;";
		if($stmt2 = $this->_db->prepare($sql2))
        {
            $stmt2->execute();
            while($row2 = $stmt2->fetch())
            {
				$materialid = $row2['material_id'];
				$materialname = $row2['material_name'];
				$materialgrade = $row2['material_grade'];
			}
		}
		else
		{
			echo "Something went wrong. $db->errorInfo";
		}
		
			
			echo '<table class="table table-bordered table-hover" width="100%" cellspacing="0"  >
				  <thead><tr  class="active">';
			echo '<th class="text-center">From: '.$newDateString.'<br/> To: '. $newDateString2.'</th>';
					echo '<th class="text-center">'. $materialname .' - '. $materialgrade .'<br/> (100 %)</th>';    
			echo '</tr></thead><tbody>';
			if($_POST['searchBy']==2)
			{  
				$sql = "SELECT DATE_FORMAT(`date_report`, '%b/%Y') as date, DATE_FORMAT(`date_report`, '%m/%Y') as date2, SUM(actual) as actual, SUM(wastekgs) as wastekgs
				FROM
				(
				SELECT `date_roll` as date_report, ROUND(SUM(net_weight),2) as actual, wastekgs
				 FROM sacks_rolls
				LEFT JOIN
				(   SELECT DATE_FORMAT(`date_waste`, '%Y/%m/%d') as date, SUM(waste) as wastekgs
					FROM  `waste`
					NATURAL JOIN machines
					WHERE location_id = 7 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
					GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d') 
					ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y/%m/%d')
				 WHERE `sacks_rolls`.date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
				 GROUP BY DATE_FORMAT(`date_roll`, '%d/%m/%Y') 
				 ORDER BY `date_roll`) report
				GROUP BY DATE_FORMAT(`date_report`, '%m/%Y')";
			}
			else if($_POST['searchBy']==3)
			{  
				$sql = "SELECT DATE_FORMAT(`date_report`, '%Y') as date, DATE_FORMAT(`date_report`, '%m/%Y') as date2, SUM(actual) as actual, SUM(wastekgs) as wastekgs
				FROM
				(SELECT `date_roll` as date_report, ROUND(SUM(net_weight),2) as actual, wastekgs
				 FROM sacks_rolls
				LEFT JOIN
				(   SELECT DATE_FORMAT(`date_waste`, '%Y/%m/%d') as date, SUM(waste) as wastekgs
					FROM  `waste`
					NATURAL JOIN machines
					WHERE location_id = 7 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
					GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d') 
					ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y/%m/%d')
				 WHERE `sacks_rolls`.date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
				 GROUP BY DATE_FORMAT(`date_roll`, '%d/%m/%Y') 
				 ORDER BY `date_roll`) report
				GROUP BY DATE_FORMAT(`date_report`, '%Y');";
			}
			else
			{
				$sql = " SELECT DATE_FORMAT(`date_roll`, '%d/%m/%Y')  as date, ROUND(SUM(net_weight),2) as actual, wastekgs
				 FROM sacks_rolls
				LEFT JOIN
				(   SELECT DATE_FORMAT(`date_waste`, '%Y/%m/%d') as date, SUM(waste) as wastekgs
					FROM  `waste`
					NATURAL JOIN machines
					WHERE location_id = 7 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
					GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d') 
					ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y/%m/%d')
				 WHERE `sacks_rolls`.date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
				 GROUP BY DATE_FORMAT(`date_roll`, '%d/%m/%Y') 
				 ORDER BY `date_roll`;";
			}
		$totalC = 0;
				if($stmt = $this->_db->prepare($sql))
				{
					$stmt->execute();
					while($row = $stmt->fetch())
					{
						$TOTAL = $row['actual'] + $row['wastekgs'];
						$entrie = array( $row['date'], $TOTAL);
						if($_POST['searchBy']==2)
						{
							$entrie = array( $row['date2'], $TOTAL);
						}
						
						$totalC += $TOTAL;
						array_push($a,$entrie);
						echo '<tr>
								<td class="text-right">'. $row['date'] .'</td>
								<td class="text-right">'. number_format($TOTAL,2,'.',',') .'</td>'  ;
						echo '</tr>';
						
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
				echo '
					<tfoot><tr  class="active">
					<th class="text-right">Total</th>';
				echo '<th class="text-right">'. number_format($totalC,2,'.',',') .'</th>';
				
				echo '</tr></tfoot></table>';
       
		
		       
        echo '<script>document.getElementById("divChart1").setAttribute("class","col-md-12");</script>';
        echo '<script>document.getElementById("chartContainer").style= "height:200px;width:100%";</script>';
         echo '<script> 
            var chart = new CanvasJS.Chart("chartContainer", {
            theme: "light2",
            title: { 
                text: "Raw Material Consumption "
            },
            exportFileName: "Raw Material Consumption ",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "Total net consumption (kgs)" },
            legend:{
                itemclick : toggleDataSeries
            },
            toolTip: {
                shared: true
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
                 type: "line",
				connectNullData: false,
		      showInLegend: true,
		      name: "'.$materialname .' - '. $materialgrade.'",';
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
        echo ' yValueFormatString: "#,##0.00 Kgs",
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
        echo ']}';
        
        echo']});
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
     * Loads the Report in the multilayer section
     * This function outputs <tr> tags with the report
     */
    public function reportRawMaterial()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Opening</th>';
        echo '<th>Raw Material Received</th>';
        echo '<th>Good Production Rolls + Waste</th>';
        echo '<th>Balance</th>';
        echo '<th>Closing</th>';
        echo '</tr></thead>
			<tfoot><tr  class="active">
			<th style="text-align:right">Total</th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			</tr></tfoot><tbody>'; 
		
		
		$report = "SELECT 
    DATE_FORMAT(`date_required`, '%Y-%m-%d') AS datereport,
    COALESCE(SUM(`stock_materials_transfers`.bags_receipt * materials.kgs_bag),0) AS received,
    ROUND(COALESCE(production.net, 0) + COALESCE(waste.waste, 0),
            2) AS consumed,
    ROUND(COALESCE(stock_balance.difference, 0), 2) AS difference
FROM
    `stock_materials_transfers`
        JOIN
    `materials` ON `stock_materials_transfers`.material_id = materials.material_id
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        sacks_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) production ON DATE_FORMAT(production.`date_roll`, '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    NATURAL JOIN machines
    WHERE
        location_id = 7
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) waste ON DATE_FORMAT(waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_balance,
            SUM(difference * materials.kgs_bag) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id AND materials.material=1
    WHERE
        machine_id = 7 
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
WHERE
    `stock_materials_transfers`.machine_to = 7 AND materials.material = 1
GROUP BY DATE_FORMAT(`stock_materials_transfers`.`date_required`,
        '%Y-%m-%d')
        
UNION ALL


SELECT 
    DATE_FORMAT(`date_roll`, '%Y-%m-%d') AS datereport,
   0 AS received,
    ROUND(COALESCE(production.net, 0) + COALESCE(waste.waste, 0),
            2) AS consumed,
    ROUND(COALESCE(stock_balance.difference, 0), 2) AS difference
FROM
(SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        sacks_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) production
	
	LEFT JOIN ( SELECT date_required, machine_to
	FROM `stock_materials_transfers`
        JOIN
    `materials` ON `stock_materials_transfers`.material_id = materials.material_id AND material = 1
    WHERE machine_to = 7 ) `stock_materials_transfers` ON DATE_FORMAT(`date_required`, '%Y-%m-%d') =  DATE_FORMAT(production.`date_roll`, '%Y-%m-%d')
        
	LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    NATURAL JOIN machines
    WHERE
        location_id = 7
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) waste ON DATE_FORMAT(waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(production.`date_roll`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_balance,
            SUM(difference * materials.kgs_bag) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id AND materials.material=1
    WHERE
        machine_id = 7
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(production.`date_roll`, '%Y-%m-%d')
WHERE
    `stock_materials_transfers`.machine_to IS NULL
GROUP BY DATE_FORMAT(production.`date_roll`,
        '%Y-%m-%d') 
        
UNION ALL

SELECT 
    DATE_FORMAT(`date_balance`,
            '%Y-%m-%d') AS datereport,
    0 AS received,
    0 AS consumed,
    ROUND(SUM(difference * materials.kgs_bag),2) AS difference
FROM
stock_balance
JOIN `materials` ON stock_balance.material_id = materials.material_id 


	LEFT JOIN ( SELECT date_required, machine_to
	FROM `stock_materials_transfers`
        JOIN
    `materials` ON `stock_materials_transfers`.material_id = materials.material_id AND material = 1
    WHERE machine_to = 7 ) `stock_materials_transfers` ON DATE_FORMAT(`date_required`, '%Y-%m-%d') =   DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
    LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        sacks_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) production ON DATE_FORMAT(production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')
WHERE machine_id =7 AND
    `stock_materials_transfers`.machine_to IS NULL
        AND production.net IS NULL  AND materials.material=1
GROUP BY DATE_FORMAT(`date_balance`,'%Y-%m-%d') 

UNION ALL 

SELECT 
    dateTable.selected_date AS datereport,
    0 AS received,
    0 AS consumed,
    0 AS difference
FROM
    (SELECT 
        ADDDATE('2018-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) selected_date
    FROM
        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) dateTable
        LEFT JOIN ( SELECT date_required, machine_to
	FROM `stock_materials_transfers`
        JOIN
    `materials` ON `stock_materials_transfers`.material_id = materials.material_id AND material = 1
    WHERE machine_to = 7 ) `stock_materials_transfers` ON DATE_FORMAT(`date_required`, '%Y-%m-%d') = dateTable.selected_date
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        sacks_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d'))production ON DATE_FORMAT(production.`date_roll`,
            '%Y-%m-%d') = dateTable.selected_date
     LEFT JOIN
    (
		SELECT 
        date_balance, SUM(difference * materials.kgs_bag) AS difference
		FROM
        stock_balance
        JOIN
		`materials` ON stock_balance.material_id = materials.material_id  AND materials.material=1
        WHERE machine_id = 7
		GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')
    ) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = dateTable.selected_date
WHERE";
		
		if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
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

			$sql= "SELECT DATE_FORMAT(`datereport`, '%b/%Y') AS datereport, opening, received, consumed, difference, closing
FROM
	(
    SELECT 
			datereport,	@a AS opening, received, consumed,  difference,
			@a:=@a + received - consumed + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(received) as received, SUM(consumed) as consumed, SUM(difference) as difference
			FROM
			("
				. $report .	
				
			"
    selected_date <= '". $newDateString2 ."'
        AND `stock_materials_transfers`.machine_to IS NULL
        AND production.net IS NULL
        AND stock_balance.difference IS NULL
ORDER BY datereport ) movements GROUP BY DATE_FORMAT(datereport, '%m/%Y') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
WHERE datereport BETWEEN '". $newDateString ."' AND '". $newDateString2 ."'
ORDER BY report.datereport;";

		}
		else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
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
			
			$sql= "SELECT DATE_FORMAT(`datereport`, '%Y') AS datereport, opening, received, consumed, difference, closing
FROM
	(
    SELECT 
			datereport,	@a AS opening, received, consumed,  difference,
			@a:=@a + received - consumed + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(received) as received, SUM(consumed) as consumed, SUM(difference) as difference
			FROM
			("
				. $report .			
			" 
    selected_date <= '". $newDateString2 ."'
        AND `stock_materials_transfers`.machine_to IS NULL
        AND production.net IS NULL
        AND stock_balance.difference IS NULL
ORDER BY datereport) movements GROUP BY DATE_FORMAT(datereport, '%Y') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
WHERE datereport BETWEEN '". $newDateString ."' AND '". $newDateString2 ."'
ORDER BY report.datereport;";
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
			
						$sql= "SELECT DATE_FORMAT(`datereport`, '%Y-%m-%d') AS datereport, opening, received, consumed, difference, closing
FROM
	(
    SELECT 
			datereport,	@a AS opening, received, consumed,  difference,
			@a:=@a + received - consumed + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(received) as received, SUM(consumed) as consumed, SUM(difference) as difference
			FROM
			("
				. $report .			
			" 
    selected_date <= '". $newDateString2 ."'
        AND `stock_materials_transfers`.machine_to IS NULL
        AND production.net IS NULL
        AND stock_balance.difference IS NULL
ORDER BY datereport) movements GROUP BY DATE_FORMAT(datereport, '%Y-%m-%d') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
WHERE datereport BETWEEN '". $newDateString ."' AND '". $newDateString2 ."'
ORDER BY report.datereport;";

		}
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $DATE = $row['datereport'];
				$OPENING = $row['opening'];
				$RECEIVED = $row['received'];
				$CONSUMED = $row['consumed'];
				$CLOSING = $row['closing'];


				$DIFF = '<td class="text-right">'. number_format((float) $row['difference'],2,'.',',') .'</td>';
				if($row['difference'] != 0)
				{
					$DIFF = '<th class="text-right text-danger">'. number_format((float) $row['difference'],2,'.',',') .'</th>';
				}

				echo '<tr>
						<td class="text-right">'. $DATE .'</td>
						<td class="text-right">'. number_format((float) $OPENING,2,'.',',') .'</td>
						<td class="text-right">'. number_format((float) $RECEIVED,2,'.',',') .'</td>
						<td class="text-right">'. number_format((float) $CONSUMED,2,'.',',') .'</td>';
				echo $DIFF;
				echo '
						<td class="text-right">'. number_format((float) $CLOSING,2,'.',',') .'</td>
					</tr>';
						
						
				
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<tr><td>Something went wrong.</tr><td';  
        }   
	}
	
	
	/**
     * Loads the Report in the multilayer section
     * This function outputs <tr> tags with the report
     */
    public function reportRollsExtruder()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Opening</th>';
        echo '<th>Good Production Rolls</th>';
        echo '<th>Good Production Cutting Sacks + Waste</th>';
        echo '<th>Balance</th>';
        echo '<th>Closing</th>';
        echo '</tr></thead>
			<tfoot><tr  class="active">
			<th style="text-align:right">Total</th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			</tr></tfoot><tbody>'; 
		
		$report = "SELECT 
    DATE_FORMAT(`date_roll`, '%Y-%m-%d') AS datereport,
    ROUND(COALESCE(production.net, 0),2) AS received,
    ROUND(COALESCE(consumed.used, 0) + COALESCE(waste.waste, 0),2) AS consumed,
    ROUND(COALESCE(stock_balance.difference, 0), 2) AS difference
FROM
(SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        sacks_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) production
LEFT JOIN
(SELECT 
        date_sacks, SUM(net_weight) AS used
    FROM
        cutting_sacks
    GROUP BY DATE_FORMAT(`date_sacks`, '%Y-%m-%d')) consumed ON DATE_FORMAT(consumed.`date_sacks`, '%Y-%m-%d') = DATE_FORMAT(production.`date_roll`, '%Y-%m-%d') 
LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    NATURAL JOIN machines
    WHERE
        location_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) waste ON DATE_FORMAT(waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(production.`date_roll`, '%Y-%m-%d') 
        
LEFT JOIN
    (SELECT 
        date_balance, SUM(difference) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id AND materials.semifinished = 1 
    WHERE
        machine_id = 34
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(production.`date_roll`, '%Y-%m-%d')
GROUP BY DATE_FORMAT(production.`date_roll`,
        '%Y-%m-%d') 
        
UNION ALL

SELECT 
    DATE_FORMAT(`date_sacks`, '%Y-%m-%d') AS datereport,
    0 AS received,
    ROUND(COALESCE(consumed.used, 0) + COALESCE(waste.waste, 0),2) AS consumed,
    ROUND(COALESCE(stock_balance.difference, 0), 2) AS difference
FROM
(SELECT 
        date_sacks, SUM(net_weight) AS used
    FROM
        cutting_sacks
    GROUP BY DATE_FORMAT(`date_sacks`, '%Y-%m-%d')) consumed 
LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    NATURAL JOIN machines
    WHERE
        location_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) waste ON DATE_FORMAT(waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(consumed.`date_sacks`, '%Y-%m-%d') 
     
LEFT JOIN (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        sacks_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) production ON DATE_FORMAT(consumed.`date_sacks`, '%Y-%m-%d') = DATE_FORMAT(production.`date_roll`, '%Y-%m-%d') 
LEFT JOIN
    (SELECT 
        date_balance, SUM(difference) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id AND materials.semifinished = 1 
    WHERE
        machine_id = 34
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(consumed.`date_sacks`, '%Y-%m-%d')
WHERE `date_sacks` IS NOT NULL AND production.date_roll IS NULL
GROUP BY DATE_FORMAT(consumed.`date_sacks`,'%Y-%m-%d') 

UNION ALL

SELECT 
    DATE_FORMAT(`date_balance`, '%Y-%m-%d') AS datereport,
    0 AS received,
    0 AS consumed,
    ROUND(COALESCE(stock_balance.difference, 0), 2) AS difference
FROM
(SELECT 
        date_balance, SUM(difference) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id AND materials.semifinished = 1 
    WHERE
        machine_id = 34
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance
LEFT JOIN (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        sacks_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) production ON DATE_FORMAT(date_balance, '%Y-%m-%d') = DATE_FORMAT(production.`date_roll`, '%Y-%m-%d') 
LEFT JOIN
(   SELECT 
        date_sacks, SUM(net_weight) AS used
    FROM
        cutting_sacks
    GROUP BY DATE_FORMAT(`date_sacks`, '%Y-%m-%d')) consumed
     ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(consumed.`date_sacks`, '%Y-%m-%d')
WHERE `date_sacks` IS NULL AND production.date_roll IS NULL
GROUP BY DATE_FORMAT(`date_balance`,'%Y-%m-%d')

UNION ALL 

SELECT 
    dateTable.selected_date AS datereport,
    0 AS received,
    0 AS consumed,
    0 AS difference
FROM
    (SELECT 
        ADDDATE('2018-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) selected_date
    FROM
        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) dateTable
LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        sacks_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) production ON DATE_FORMAT(production.`date_roll`,
            '%Y-%m-%d') = dateTable.selected_date
LEFT JOIN
(   SELECT 
        date_sacks, SUM(net_weight) AS used
    FROM
        cutting_sacks
    GROUP BY DATE_FORMAT(`date_sacks`, '%Y-%m-%d')) consumed ON DATE_FORMAT(consumed.`date_sacks`, '%Y-%m-%d')  = dateTable.selected_date
LEFT JOIN
    (SELECT 
        date_balance, SUM(difference) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id AND materials.semifinished = 1 
    WHERE
        machine_id = 34
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = dateTable.selected_date 
";
		
		
		if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
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

			$sql= "SELECT DATE_FORMAT(`datereport`, '%b/%Y') AS datereport, opening, received, consumed, difference, closing
FROM
	(
    SELECT 
			datereport,	@a AS opening, received, consumed,  difference,
			@a:=@a + received - consumed + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(received) as received, SUM(consumed) as consumed, SUM(difference) as difference
			FROM
			("
				. $report .			
			"WHERE
    selected_date <= '". $newDateString2 ."'
        AND `date_sacks` IS NULL AND production.date_roll IS NULL
        AND stock_balance.difference IS NULL
ORDER BY datereport
) movements GROUP BY DATE_FORMAT(datereport, '%m/%Y') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
WHERE datereport BETWEEN '". $newDateString ."' AND '". $newDateString2 ."'
ORDER BY report.datereport;";

		}
		else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
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
			
			$sql= "SELECT DATE_FORMAT(`datereport`, '%Y') AS datereport, opening, received, consumed, difference, closing
FROM
	(
    SELECT 
			datereport,	@a AS opening, received, consumed,  difference,
			@a:=@a + received - consumed + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(received) as received, SUM(consumed) as consumed, SUM(difference) as difference
			FROM
			("
				. $report .			
			" WHERE
    selected_date <= '". $newDateString2 ."'
        AND `date_sacks` IS NULL AND production.date_roll IS NULL
        AND stock_balance.difference IS NULL
ORDER BY datereport
 ) movements GROUP BY DATE_FORMAT(datereport, '%Y') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
WHERE datereport BETWEEN '". $newDateString ."' AND '". $newDateString2 ."'
ORDER BY report.datereport;";
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
			
						$sql= "SELECT DATE_FORMAT(`datereport`, '%Y-%m-%d') AS datereport, opening, received, consumed, difference, closing
FROM
	(
    SELECT 
			datereport,	@a AS opening, received, consumed,  difference,
			@a:=@a + received - consumed + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(received) as received, SUM(consumed) as consumed, SUM(difference) as difference
			FROM
			("
				. $report .			
			" WHERE
    selected_date <= '". $newDateString2 ."'
        AND `date_sacks` IS NULL AND production.date_roll IS NULL
        AND stock_balance.difference IS NULL
ORDER BY datereport
) movements GROUP BY DATE_FORMAT(datereport, '%Y-%m-%d') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
WHERE datereport BETWEEN '". $newDateString ."' AND '". $newDateString2 ."'
ORDER BY report.datereport;";

		}
		
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $DATE = $row['datereport'];
				$OPENING = $row['opening'];
				$RECEIVED = $row['received'];
				$CONSUMED = $row['consumed'];
				$CLOSING = $row['closing'];


				$DIFF = '<td class="text-right">'. number_format((float) $row['difference'],2,'.',',') .'</td>';
				if($row['difference'] != 0)
				{
					$DIFF = '<th class="text-right text-danger">'. number_format((float) $row['difference'],2,'.',',') .'</th>';
				}

				echo '<tr>
						<td class="text-right">'. $DATE .'</td>
						<td class="text-right">'. number_format((float) $OPENING,2,'.',',') .'</td>
						<td class="text-right">'. number_format((float) $RECEIVED,2,'.',',') .'</td>
						<td class="text-right">'. number_format((float) $CONSUMED,2,'.',',') .'</td>';
				echo $DIFF;
				echo '
						<td class="text-right">'. number_format((float) $CLOSING,2,'.',',') .'</td>
					</tr>';
						
						
				
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<tr><td>Something went wrong.</tr><td';  
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
	
	 public function giveSizename($size)
    {
        $sizename = "";
        if($size == 1)
        {
            $sizename = "6 1/2";
        }
        return $sizename;
    }
	
    
	
	 public function reportEfficiencyCutting()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Machine</th>';
        echo '<th>Machine Capacity</th>';
        echo '<th>Orders Target</th>';
        echo '<th>Actual Production</th>';
        echo '<th>% Eff</th>';
        echo '<th>Waste in Kgs</th>';
        echo '<th>Target Waste %</th>';
        echo '<th>Waste %</th>';
        echo '</tr></thead>
			<tfoot><tr  class="active">
			<th style="text-align:right">Total</th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			</tr></tfoot><tbody>'; 
        
        $a=array();
        $b1=array();
        $b2=array();
        $b3=array();
        $b4=array();
        $b5=array();
        $b6=array();
        $b7=array();
        $b8=array();
        $b9=array();
        $c=array();
        $d1=array();
        $d2=array();
        $d3=array();
        $d4=array();
        $d5=array();
        $d6=array();
        $d7=array();
        $d8=array();
        $d9=array();
        $e=array();
		
		        
        $newDateString = date("Y-m-d");
        $newDateString2 = date("Y-m-d");
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
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
            
			
            $sql = "SELECT 
    DATE_FORMAT(`date_sacks`, '%b/%Y') AS date, DATE_FORMAT(`date_sacks`, '%m/%Y') as date2, machine_name, cutting_sacks.machine_id,
    ROUND(SUM(net_weight), 2) AS actual,
    waste.wastekgs,
    COUNT(DISTINCT (DATE_FORMAT(`date_sacks`, '%d/%m/%Y'))) AS days,
    target, target_waste, capacity
FROM
    cutting_sacks
LEFT JOIN machines ON cutting_sacks.machine_id = machines.machine_id
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%m/%Y') AS date,
            SUM(waste) AS wastekgs, machine_id
    FROM
        `waste`
	NATURAL JOIN machines
    WHERE location_id = 10 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%m/%Y'), machine_id
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_sacks`, '%m/%Y') AND waste.machine_id = cutting_sacks.machine_id

LEFT JOIN
    (SELECT 
        DATE_FORMAT(`target_orders`.`date`, '%m/%Y') AS date,
            SUM(target_order) AS target, machine_id
    FROM
        `target_orders`
   	NATURAL JOIN machines
    WHERE location_id = 10
        AND date BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`target_orders`.`date`, '%m/%Y'), machine_id
    ORDER BY `target_orders`.`date`) targets ON targets.date = DATE_FORMAT(`date_sacks`, '%m/%Y') AND targets.machine_id = cutting_sacks.machine_id

 LEFT JOIN
	(
		SELECT AVG(`settings`.value_setting) AS target_waste, DATE_FORMAT(`settings`.`to`, '%m/%Y') AS `to` , DATE_FORMAT(`settings`.`from`, '%m/%Y') AS `from`
        FROM `settings`
        WHERE `settings`.machine_id = 34 AND `settings`.name_setting = 'waste'
		GROUP BY DATE_FORMAT(`settings`.from, '%m/%Y')
    )
    waste_target ON waste_target.`from` <= DATE_FORMAT(`date_sacks`, '%m/%Y') AND (waste_target.`to` IS NULL OR waste_target.`to` > DATE_FORMAT(`date_sacks`, '%m/%Y'))
    
    LEFT JOIN
	(
		SELECT AVG(`settings`.value_setting) AS capacity, DATE_FORMAT(`settings`.`to`, '%m/%Y') AS `to` , DATE_FORMAT(`settings`.`from`, '%m/%Y') AS `from`, `settings`.machine_id
        FROM `settings`
		NATURAL JOIN machines
    	WHERE location_id = 10 
		GROUP BY DATE_FORMAT(`settings`.from, '%m/%Y'), `settings`.machine_id
    )
    capacity ON  capacity.machine_id = cutting_sacks.machine_id AND capacity.`from` <= DATE_FORMAT(`date_sacks`, '%m/%Y') AND (capacity.`to` IS NULL OR capacity.`to` > DATE_FORMAT(`date_sacks`, '%m/%Y'))
    
    WHERE
    date_sacks BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_sacks`, '%b/%Y'), cutting_sacks.machine_id 
ORDER BY `date_sacks`, cutting_sacks.machine_id ;";
            
            
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
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
            
              $sql = "SELECT 
    DATE_FORMAT(`date_sacks`, '%Y') AS date, machine_name, cutting_sacks.machine_id,
    ROUND(SUM(net_weight), 2) AS actual,
    waste.wastekgs,
    COUNT(DISTINCT (DATE_FORMAT(`date_sacks`, '%d/%m/%Y'))) AS days,
    target, target_waste, capacity
FROM
    cutting_sacks
LEFT JOIN machines ON cutting_sacks.machine_id = machines.machine_id
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%Y') AS date,
            SUM(waste) AS wastekgs, machine_id
    FROM
        `waste`
	NATURAL JOIN machines
    WHERE location_id = 10 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%Y'), machine_id
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_sacks`, '%Y') AND waste.machine_id = cutting_sacks.machine_id

LEFT JOIN
    (SELECT 
        DATE_FORMAT(`target_orders`.`date`, '%Y') AS date,
            SUM(target_order) AS target, machine_id
    FROM
        `target_orders`
   	NATURAL JOIN machines
    WHERE location_id = 10
        AND date BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`target_orders`.`date`, '%Y'), machine_id
    ORDER BY `target_orders`.`date`) targets ON targets.date = DATE_FORMAT(`date_sacks`, '%Y') AND targets.machine_id = cutting_sacks.machine_id

 LEFT JOIN
	(
		SELECT AVG(`settings`.value_setting) AS target_waste, DATE_FORMAT(`settings`.`to`, '%Y') AS `to` , DATE_FORMAT(`settings`.`from`, '%Y') AS `from`
        FROM `settings`
        WHERE `settings`.machine_id = 34 AND `settings`.name_setting = 'waste'
		GROUP BY DATE_FORMAT(`settings`.from, '%Y')
    )
    waste_target ON waste_target.`from` <= DATE_FORMAT(`date_sacks`, '%Y') AND (waste_target.`to` IS NULL OR waste_target.`to` > DATE_FORMAT(`date_sacks`, '%Y'))
    
    LEFT JOIN
	(
		SELECT AVG(`settings`.value_setting) AS capacity, DATE_FORMAT(`settings`.`to`, '%Y') AS `to` , DATE_FORMAT(`settings`.`from`, '%Y') AS `from`, `settings`.machine_id
        FROM `settings`
		NATURAL JOIN machines
    	WHERE location_id = 10 
		GROUP BY DATE_FORMAT(`settings`.from, '%Y'), `settings`.machine_id
    )
    capacity ON  capacity.machine_id = cutting_sacks.machine_id AND capacity.`from` <= DATE_FORMAT(`date_sacks`, '%Y') AND (capacity.`to` IS NULL OR capacity.`to` > DATE_FORMAT(`date_sacks`, '%Y'))
    
    WHERE
    date_sacks BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_sacks`, '%Y'), cutting_sacks.machine_id 
ORDER BY `date_sacks`, cutting_sacks.machine_id ;";
            
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
            
            $sql = "SELECT 
    DATE_FORMAT(`date_sacks`, '%d/%m/%Y') AS date, machine_name, cutting_sacks.machine_id,
    ROUND(SUM(net_weight), 2) AS actual,
    waste.wastekgs,
    COUNT(DISTINCT (DATE_FORMAT(`date_sacks`, '%d/%m/%Y'))) AS days,
    target, target_waste, capacity
FROM
    cutting_sacks
LEFT JOIN machines ON cutting_sacks.machine_id = machines.machine_id
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%Y/%m/%d') AS date,
            SUM(waste) AS wastekgs, machine_id
    FROM
        `waste`
	NATURAL JOIN machines
    WHERE location_id = 10 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d'), machine_id
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_sacks`, '%Y/%m/%d') AND waste.machine_id = cutting_sacks.machine_id

LEFT JOIN
    (SELECT 
        DATE_FORMAT(`target_orders`.`date`, '%Y/%m/%d') AS date,
            SUM(target_order) AS target, machine_id
    FROM
        `target_orders`
   	NATURAL JOIN machines
    WHERE location_id = 10
        AND date BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`target_orders`.`date`, '%Y/%m/%d'), machine_id
    ORDER BY `target_orders`.`date`) targets ON targets.date = DATE_FORMAT(`date_sacks`, '%Y/%m/%d') AND targets.machine_id = cutting_sacks.machine_id

 LEFT JOIN
	(
		SELECT `settings`.value_setting AS target_waste, `settings`.to, `settings`.from
        FROM `settings`
        WHERE `settings`.machine_id = 34 AND `settings`.name_setting = 'waste'
    )
    waste_target ON waste_target.`from` <= DATE_FORMAT(`date_sacks`, '%Y/%m/%d') AND (waste_target.`to` IS NULL OR waste_target.`to` > DATE_FORMAT(`date_sacks`, '%Y/%m/%d'))
    
    LEFT JOIN
	(
		SELECT `settings`.value_setting AS capacity, `settings`.to, `settings`.from, `settings`.machine_id
        FROM `settings`
		NATURAL JOIN machines
    	WHERE location_id = 10 
    )
    capacity ON  capacity.machine_id = cutting_sacks.machine_id AND capacity.`from` <= DATE_FORMAT(`date_sacks`, '%Y/%m/%d') AND (capacity.`to` IS NULL OR capacity.`to` > DATE_FORMAT(`date_sacks`, '%Y/%m/%d'))
    
    WHERE
    date_sacks BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_sacks`, '%d/%m/%Y'), cutting_sacks.machine_id 
ORDER BY `date_sacks`, cutting_sacks.machine_id ;";
            
        }
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $CAPACITYROW = $row['capacity'] * $row['days'];
				$TARGET = $row['target'];
				$TARGETWASTE = $row['target_waste'];
                $WASTEKG = $row['wastekgs'];
                if(is_null($row['wastekgs']))
                {
                    $WASTEKG = 0;
                }
                $ACTUAL = $row['actual'] + $WASTEKG;
                if(is_null($row['actual']))
                {
                    $ACTUAL = 0 + $WASTEKG;
                    $WASTEEFF = 0;
                }
                else
                {
                    $WASTEEFF  = round($WASTEKG* 100 / $ACTUAL , 2);
                }
				if(is_null($row['target']) and !is_null($row['capacity']))
                {
                    $TARGET = $CAPACITYROW;
					$EFF = round($ACTUAL *100/ $CAPACITYROW, 2);
                }
				else if(is_null($row['capacity']))
                {
                    $TARGET = $CAPACITYROW;
					$EFF = 100;
                }
                else
                {
					$EFF = round($ACTUAL *100/ $TARGET, 2);
                }
                echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. $row['machine_name'] .'</td>
                        <td class="text-right">'. number_format($CAPACITYROW,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($TARGET,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($ACTUAL,2,'.',',') .'</td>
                        <th class="text-right">'. number_format($EFF,2,'.',',') .'</th>
                        <td class="text-right">'. number_format($WASTEKG,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($TARGETWASTE,2,'.',',') .'</td>
                        <th class="text-right">'. number_format($WASTEEFF,2,'.',',') .'</th>
                    </tr>';
                $entrie0 = array( $row['date'], $CAPACITYROW);
                $entrie = array( $row['date'], $TARGET);
                $entrie1 = array( $row['date'], $ACTUAL);
                $entrie2 = array( $row['date'], $TARGETWASTE);
                $entrie3 = array( $row['date'], $WASTEEFF);
                if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
                {
                    $entrie0 = array( $row['date2'], $CAPACITYROW);
					$entrie = array( $row['date2'], $TARGET);
                    $entrie1 = array( $row['date2'],$ACTUAL);
                    $entrie2 = array( $row['date2'], $TARGETWASTE);
                    $entrie3 = array( $row['date2'], $WASTEEFF);
                }
                array_push($a,$entrie);
				if($row['machine_id'] == 21)
				{
                	array_push($b1,$entrie1);
				}
				else if($row['machine_id'] == 22)
				{
                	array_push($b2,$entrie1);
				}
				else if($row['machine_id'] == 23)
				{
                	array_push($b3,$entrie1);
				}
				else if($row['machine_id'] == 24)
				{
                	array_push($b4,$entrie1);
				}
				else if($row['machine_id'] == 25)
				{
                	array_push($b5,$entrie1);
				}
				else if($row['machine_id'] == 26)
				{
                	array_push($b6,$entrie1);
				}
				else if($row['machine_id'] == 27)
				{
                	array_push($b7,$entrie1);
				}
				else if($row['machine_id'] == 28)
				{
                	array_push($b8,$entrie1);
				}
				else if($row['machine_id'] == 29)
				{
                	array_push($b9,$entrie1);
				}
                array_push($c,$entrie2);
				if($row['machine_id'] == 21)
				{
                	array_push($d1,$entrie3);
				}
				else if($row['machine_id'] == 22)
				{
                	array_push($d2,$entrie3);
				}
				else if($row['machine_id'] == 23)
				{
                	array_push($d3,$entrie3);
				}
				else if($row['machine_id'] == 24)
				{
                	array_push($d4,$entrie3);
				}
				else if($row['machine_id'] == 25)
				{
                	array_push($d5,$entrie3);
				}
				else if($row['machine_id'] == 26)
				{
                	array_push($d6,$entrie3);
				}
				else if($row['machine_id'] == 27)
				{
                	array_push($d7,$entrie3);
				}
				else if($row['machine_id'] == 28)
				{
                	array_push($d8,$entrie3);
				}
				else if($row['machine_id'] == 29)
				{
                	array_push($d9,$entrie3);
				}
                array_push($e,$entrie0);
            }
            $stmt->closeCursor();
        }
        else
        {
            echo "<tr>
                    <td>Something went wrong.</td>
                    <td>$db->errorInfo</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>";
        }
         echo '</tbody>';
        echo '<script>document.getElementById("chartContainer").style= "height:200px;";</script>';
        echo '<script>document.getElementById("chartContainer2").style= "height:200px;";</script>';
         echo '<script> 
            var chart = new CanvasJS.Chart("chartContainer", {
            theme: "light2",
            title: { 
                text: "Production Target Achievement"
            },
            exportFileName: "Production Target Achievement",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "KGS" },
            toolTip: {
                shared: true
            },
            legend:{
                itemclick : function(e){
				
					if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
						e.dataSeries.visible = false;
					} else {
						e.dataSeries.visible = true;
					}
					chart.render();
				}
            },';
        if(!empty($_POST['searchBy']) and  $_POST['searchBy']==2)
        {  
            echo 'axisX:{ valueFormatString: "MMM YYYY"},';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            echo 'axisX:{ valueFormatString: "YYYY"},';
        }
        else
        {
            echo 'axisX:{ valueFormatString: "DD MMM"},';
        }
        echo 'data: [
            {
                type: "line",
		      showInLegend: true,
		      name: "Cutting - 1",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,###.00 Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($b1 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($b1 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($b1 as $key=>$value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
        echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Cutting - 2",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,###.00 Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($b2 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($b2 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($b2 as $key=>$value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Cutting - 3",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,###.00 Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($b3 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($b3 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($b3 as $key=>$value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Cutting - 4",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,###.00 Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($b4 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($b4 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($b4 as $key=>$value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Cutting - 5",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,###.00 Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($b5 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($b5 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($b5 as $key=>$value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Cutting - 6",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,###.00 Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($b6 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($b6 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($b6 as $key=>$value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Cutting - 7",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,###.00 Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($b7 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($b7 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($b7 as $key=>$value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Cutting - 8",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,###.00 Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($b8 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($b8 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($b8 as $key=>$value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		 
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Cutting - 9",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,###.00 Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($b9 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($b9 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($b9 as $key=>$value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$a[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        } 
        echo'] }]});
        chart.render();
        </script>'; 
        echo '<script> 
            var chart1 = new CanvasJS.Chart("chartContainer2", {
            theme: "light2",
            title: { 
                text: "Waste Target Achievement"
            },
            exportFileName: "Waste Target Achievement",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "Waste %" },
            toolTip: {
                shared: true
            },
			legend:{
                itemclick : function(e){
				
					if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
						e.dataSeries.visible = false;
					} else {
						e.dataSeries.visible = true;
					}
					chart1.render();
				}
            },';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'axisX:{ valueFormatString: "MMM YYYY"},';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            echo 'axisX:{ valueFormatString: "YYYY"},';
        }
        else
        {
            echo 'axisX:{ valueFormatString: "DD MMM"},';
        }
        echo 'data: [
            {
                type: "line",
		showInLegend: true,
		name: "Target",
		lineDashType: "dash",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.0",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($c as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1] .'},';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($c as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($c as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
        echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Cutting - 1 ",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.00 ",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($d1 as $key => $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($d1 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($d1 as $key => $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		 echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Cutting - 2",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.0 ",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($d2 as $key => $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($d2 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($d2 as $key => $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Cutting - 3",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.0 ",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($d3 as $key => $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($d3 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($d3 as $key => $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Cutting - 4",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.0 ",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($d4 as $key => $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($d4 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($d4 as $key => $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Cutting - 5",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.0 ",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($d5 as $key => $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($d5 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($d5 as $key => $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Cutting - 6",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.0 ",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($d6 as $key => $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($d6 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($d6 as $key => $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Cutting - 7",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.0 ",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($d7 as $key => $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($d7 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($d7 as $key => $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Cutting - 8",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.0 ",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($d8 as $key => $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($d8 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($d8 as $key => $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		 
		 echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Cutting - 9",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.0 ",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($d9 as $key => $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($d9 as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($d9 as $key => $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]>$c[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        echo'] }]});
        chart1.render(); 
        </script>'; 
    }
	
	/**
     * Loads the Production Report in the multilayer section
     * This function outputs <tr> tags with the report
     */
    public function reportProductionCutting()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Machine</th>';
        echo '<th>Good Production</th>';
        echo '<th>No. of sacks produced</th>';
        echo '</tr></thead><tfoot><tr  class="active">
			<th style="text-align:right">Total</th>
			<th></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th></tr></tfoot><tbody>';   
        
        $a1=array();
        $a2=array();
        $a3=array();
        $a4=array();
        $a5=array();
        $a6=array();
        $a7=array();
        $a8=array();
        $a9=array();
        $b1=array();
        $b2=array();
        $b3=array();
        $b4=array();
        $b5=array();
        $b6=array();
        $b7=array();
        $b8=array();
        $b9=array();
		
		
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
            
            $sql = " SELECT DATE_FORMAT(`date_sacks`, '%b/%Y') as date, DATE_FORMAT(`date_sacks`, '%m/%Y') as date2, ROUND(SUM(net_weight),2) as actual, COUNT(cutting_sacks_id) as rolls, machine_name, cutting_sacks.machine_id   
             FROM cutting_sacks
             LEFT JOIN machines ON cutting_sacks.machine_id = machines.machine_id
             WHERE `date_sacks` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_sacks`, '%Y'), cutting_sacks.machine_id 
             ORDER BY `date_sacks`;";
            
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
            
            $sql = "SELECT DATE_FORMAT(`date_sacks`, '%Y') as date, ROUND(SUM(net_weight),2) as actual, COUNT(cutting_sacks_id) as rolls, machine_name, cutting_sacks.machine_id   
             FROM cutting_sacks
             LEFT JOIN machines ON cutting_sacks.machine_id = machines.machine_id
             WHERE `date_sacks` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_sacks`, '%Y'), cutting_sacks.machine_id 
             ORDER BY `date_sacks`;";
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
            
            $sql = " SELECT DATE_FORMAT(`date_sacks`, '%d/%m/%Y') as date, ROUND(SUM(net_weight),2) as actual, COUNT(cutting_sacks_id) as rolls, machine_name, cutting_sacks.machine_id   
             FROM cutting_sacks
             LEFT JOIN machines ON cutting_sacks.machine_id = machines.machine_id
             WHERE `date_sacks` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_sacks`, '%d/%m/%Y'), cutting_sacks.machine_id 
             ORDER BY `date_sacks`;";
            
        }
        
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. $row['machine_name'] .'</td>
                        <td class="text-right">'. number_format($row['actual'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['rolls'],0,'.',',') .'</td>
                    </tr>';
                $entrie = array( $row['date'], $row['actual']);
                $entrie1 = array( $row['date'], $row['rolls']);
                if($_POST['searchBy']==2)
                {
                    $entrie = array( $row['date2'], $row['actual']);
                    $entrie1 = array( $row['date2'], $row['rolls']);
                }
				if($row['machine_id'] == 21)
				{
                	array_push($a1,$entrie);
				}
				else if($row['machine_id'] == 22)
				{
                	array_push($a2,$entrie);
				}
				else if($row['machine_id'] == 23)
				{
                	array_push($a3,$entrie);
				}
				else if($row['machine_id'] == 24)
				{
                	array_push($a4,$entrie);
				}
				else if($row['machine_id'] == 25)
				{
                	array_push($a5,$entrie);
				}
				else if($row['machine_id'] == 26)
				{
                	array_push($a6,$entrie);
				}
				else if($row['machine_id'] == 27)
				{
                	array_push($a7,$entrie);
				}
				else if($row['machine_id'] == 28)
				{
                	array_push($a8,$entrie);
				}
				else if($row['machine_id'] == 29)
				{
                	array_push($a9,$entrie);
				}
				
				if($row['machine_id'] == 21)
				{
                	array_push($b1,$entrie1);
				}
				else if($row['machine_id'] == 22)
				{
                	array_push($b2,$entrie1);
				}
				else if($row['machine_id'] == 23)
				{
                	array_push($b3,$entrie1);
				}
				else if($row['machine_id'] == 24)
				{
                	array_push($b4,$entrie1);
				}
				else if($row['machine_id'] == 25)
				{
                	array_push($b5,$entrie1);
				}
				else if($row['machine_id'] == 26)
				{
                	array_push($b6,$entrie1);
				}
				else if($row['machine_id'] == 27)
				{
                	array_push($b7,$entrie1);
				}
				else if($row['machine_id'] == 28)
				{
                	array_push($b8,$entrie1);
				}
				else if($row['machine_id'] == 29)
				{
                	array_push($b9,$entrie1);
				}
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
        echo '<script>document.getElementById("chartContainer").style= "height:200px;";</script>';
        echo '<script>document.getElementById("chartContainer2").style= "height:200px;";</script>';
         echo '<script> 
            var chart = new CanvasJS.Chart("chartContainer", {
            theme: "light2",
            title: { 
                text: "Production "
            },
            exportFileName: "Production",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "Total net production (kgs)" },
            toolTip: {
                shared: true
            },legend:{
                itemclick : function(e){
				
					if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
						e.dataSeries.visible = false;
					} else {
						e.dataSeries.visible = true;
					}
					chart.render();
				}
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
		      name: "Cutting - 1",';
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
            foreach($a1 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a1 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a1 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		 echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 2",';
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
            foreach($a2 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a2 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a2 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 3",';
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
            foreach($a3 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a3 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a3 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 4",';
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
            foreach($a4 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a4 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a4 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 5",';
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
            foreach($a5 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a5 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a5 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 6",';
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
            foreach($a6 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a6 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a6 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 7",';
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
            foreach($a7 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a7 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a7 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting  - 8",';
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
            foreach($a8 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a8 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a8 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting  - 9",';
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
            foreach($a9 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a9 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a9 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
        echo'] }]});
        chart.render(); 
        </script>'; 
        echo '<script> 
            var chart1 = new CanvasJS.Chart("chartContainer2", {
            theme: "light2",
            title: { 
                text: "No. of sacks produced"
            },
            exportFileName: "No. of sacks produced",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "Sacks" },
            toolTip: {
                shared: true
            },
			legend:{
                itemclick : function(e){
				
					if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
						e.dataSeries.visible = false;
					} else {
						e.dataSeries.visible = true;
					}
					chart1.render();
				}
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
		      name: "Cutting - 1",';
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
        echo ' yValueFormatString: "#,### sacks",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($b1 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($b1 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($b1 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 2",';
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
        echo ' yValueFormatString: "#,### sacks",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($b2 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($b2 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($b2 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 3",';
        if($_POST['searchBy']==3)
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
        echo ' yValueFormatString: "#,### sacks",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($b3 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($b3 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($b3 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 4",';
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
        echo ' yValueFormatString: "#,### sacks",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($b4 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($b4 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($b4 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 5",';
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
        echo ' yValueFormatString: "#,### sacks",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($b5 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($b5 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($b5 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 6",';
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
        echo ' yValueFormatString: "#,### sacks",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($b6 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($b6 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($b6 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 7",';
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
        echo ' yValueFormatString: "#,### sacks",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($b7 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($b7 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($b7 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 8",';
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
        echo ' yValueFormatString: "#,### sacks",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($b8 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($b8 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($b8 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 9",';
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
        echo ' yValueFormatString: "#,### sacks",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($b9 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($b9 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($b9 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
        echo'] }]});
        chart1.render(); 
        </script>';
    }
	
	
	public function reportWasteCutting()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Machine</th>';
        echo '<th>Total Waste</th>';
        echo '</tr></thead><tfoot><tr  class="active">
			<th style="text-align:right">Total</th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th></tr></tfoot><tbody>';   
        
        $a1=array();
        $a2=array();
        $a3=array();
        $a4=array();
        $a5=array();
        $a6=array();
        $a7=array();
        $a8=array();
        $a9=array();
		
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
            
            $sql = " SELECT DATE_FORMAT(`date_waste`, '%b/%Y') as date, DATE_FORMAT(`date_waste`, '%m/%Y') as date2, SUM(`waste`.`waste`) AS total, machine_name, waste.machine_id
             FROM  `waste`
             LEFT JOIN machines ON waste.machine_id = machines.machine_id
             WHERE location_id=10 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_waste`, '%b/%Y'), waste.machine_id  
             ORDER BY `date_waste`;";
            
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
            
            $sql = " SELECT DATE_FORMAT(`date_waste`, '%Y') as date, SUM(`waste`.`waste`) AS total, machine_name, waste.machine_id
             FROM  `waste`
             LEFT JOIN machines ON waste.machine_id = machines.machine_id
             WHERE location_id=10 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_waste`, '%Y'), waste.machine_id
             ORDER BY `date_waste`;";
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
            
            $sql = " SELECT DATE_FORMAT(`date_waste`, '%d/%m/%Y') AS date, SUM(`waste`.`waste`) AS total, machine_name, waste.machine_id
             FROM  `waste`
             LEFT JOIN machines ON waste.machine_id = machines.machine_id  
             WHERE location_id=10 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_waste`, '%d/%m/%Y'), waste.machine_id 
             ORDER BY `date_waste`;";
            
        }
        
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. $row['machine_name'] .'</td>
                        <td class="text-right">'. number_format($row['total'],2,'.',',') .'</td>
                    </tr>';
                $entrie = array( $row['date'], $row['total']);
                if($_POST['searchBy']==2)
                {
                    $entrie = array( $row['date2'], $row['total']);
                }
                if($row['machine_id'] == 21)
				{
                	array_push($a1,$entrie);
				}
				else if($row['machine_id'] == 22)
				{
                	array_push($a2,$entrie);
				}
				else if($row['machine_id'] == 23)
				{
                	array_push($a3,$entrie);
				}
				else if($row['machine_id'] == 24)
				{
                	array_push($a4,$entrie);
				}
				else if($row['machine_id'] == 25)
				{
                	array_push($a5,$entrie);
				}
				else if($row['machine_id'] == 26)
				{
                	array_push($a6,$entrie);
				}
				else if($row['machine_id'] == 27)
				{
                	array_push($a7,$entrie);
				}
				else if($row['machine_id'] == 28)
				{
                	array_push($a8,$entrie);
				}
				else if($row['machine_id'] == 29)
				{
                	array_push($a9,$entrie);
				}
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
		name: "Cutting - 1",';
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
            foreach($a1 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a1 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a1 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		showInLegend: true,
		name: "Cutting - 2",';
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
            foreach($a2 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a2 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a2 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 3",';
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
            foreach($a3 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a3 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a3 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 4",';
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
            foreach($a4 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a4 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a4 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 5",';
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
            foreach($a5 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a5 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a5 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 6",';
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
            foreach($a6 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a6 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a6 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 7",';
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
            foreach($a7 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a7 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a7 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 8",';
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
            foreach($a8 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a8 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a8 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		
		echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Cutting - 9",';
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
            foreach($a9 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a9 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a9 as $value) {
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
	
	 public function reportMaterialConsumptionCutting()
    {
       
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
        }
		$materialid = "";
		$materialname = "Rolls";
		$materialgrade = "Extruder";
		
		
			
			echo '<table class="table table-bordered table-hover" width="100%" cellspacing="0"  >
				  <thead><tr  class="active">';
			echo '<th class="text-center">From: '.$newDateString.'<br/> To: '. $newDateString2.'</th>';
					echo '<th class="text-center">'. $materialname .' - '. $materialgrade .'<br/> (100 %)</th>';    
			echo '</tr></thead><tbody>';
			if($_POST['searchBy']==2)
			{  
				$sql = "SELECT DATE_FORMAT(`date_report`, '%b/%Y') as date, DATE_FORMAT(`date_report`, '%m/%Y') as date2, SUM(actual) as actual, SUM(wastekgs) as wastekgs
				FROM
				(
					SELECT `date_sacks`  as date_report, ROUND(SUM(net_weight),2) as actual, wastekgs
					 FROM cutting_sacks
					LEFT JOIN
					(   SELECT DATE_FORMAT(`date_waste`, '%Y/%m/%d') as date, SUM(waste) as wastekgs
						FROM  `waste`
						NATURAL JOIN machines
						WHERE location_id = 10 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
						GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d') 
						ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_sacks`, '%Y/%m/%d')
					 WHERE date_sacks BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
					 GROUP BY DATE_FORMAT(`date_sacks`, '%d/%m/%Y') 
					 ORDER BY `date_sacks`
				) report
				GROUP BY DATE_FORMAT(`date_report`, '%m/%Y')";
			}
			else if($_POST['searchBy']==3)
			{  
				$sql = "SELECT DATE_FORMAT(`date_report`, '%Y') as date, DATE_FORMAT(`date_report`, '%m/%Y') as date2, SUM(actual) as actual, SUM(wastekgs) as wastekgs
				FROM
				(
					SELECT `date_sacks`  as date_report, ROUND(SUM(net_weight),2) as actual, wastekgs
					 FROM cutting_sacks
					LEFT JOIN
					(   SELECT DATE_FORMAT(`date_waste`, '%Y/%m/%d') as date, SUM(waste) as wastekgs
						FROM  `waste`
						NATURAL JOIN machines
						WHERE location_id = 10 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
						GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d') 
						ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_sacks`, '%Y/%m/%d')
					 WHERE date_sacks BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
					 GROUP BY DATE_FORMAT(`date_sacks`, '%d/%m/%Y') 
					 ORDER BY `date_sacks`
				) report
				GROUP BY DATE_FORMAT(`date_report`, '%Y');";
			}
			else
			{
				$sql = " SELECT DATE_FORMAT(`date_sacks`, '%d/%m/%Y')  as date, ROUND(SUM(net_weight),2) as actual, wastekgs
				 FROM cutting_sacks
				LEFT JOIN
				(   SELECT DATE_FORMAT(`date_waste`, '%Y/%m/%d') as date, SUM(waste) as wastekgs
					FROM  `waste`
					NATURAL JOIN machines
					WHERE location_id = 10 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
					GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d') 
					ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_sacks`, '%Y/%m/%d')
				 WHERE date_sacks BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
				 GROUP BY DATE_FORMAT(`date_sacks`, '%d/%m/%Y') 
				 ORDER BY `date_sacks`;";
			}
		$totalC = 0;
				if($stmt = $this->_db->prepare($sql))
				{
					$stmt->execute();
					while($row = $stmt->fetch())
					{
						$TOTAL = $row['actual'] + $row['wastekgs'];
						$entrie = array( $row['date'], $TOTAL);
						if($_POST['searchBy']==2)
						{
							$entrie = array( $row['date2'], $TOTAL);
						}
						
						$totalC += $TOTAL;
						array_push($a,$entrie);
						echo '<tr>
								<td class="text-right">'. $row['date'] .'</td>
								<td class="text-right">'. number_format($TOTAL,2,'.',',') .'</td>'  ;
						echo '</tr>';
						
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
				echo '
					<tfoot><tr  class="active">
					<th class="text-right">Total</th>';
				echo '<th class="text-right">'. number_format($totalC,2,'.',',') .'</th>';
				
				echo '</tr></tfoot></table>';
       
		
		       
        echo '<script>document.getElementById("divChart1").setAttribute("class","col-md-12");</script>';
        echo '<script>document.getElementById("chartContainer").style= "height:200px;width:100%";</script>';
         echo '<script> 
            var chart = new CanvasJS.Chart("chartContainer", {
            theme: "light2",
            title: { 
                text: "Raw Material Consumption "
            },
            exportFileName: "Raw Material Consumption ",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "Total net consumption (kgs)" },
            legend:{
                itemclick : toggleDataSeries
            },
            toolTip: {
                shared: true
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
                 type: "line",
				connectNullData: false,
		      showInLegend: true,
		      name: "'.$materialname .' - '. $materialgrade.'",';
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
        echo ' yValueFormatString: "#,##0.00 Kgs",
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
        echo ']}';
        
        echo']});
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
     * Loads the Downtime, Remarks, Reason Report in the multilayer section
     * This function outputs <tr> tags with the report
     */
    public function reportReasonCutting()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Machine</th>';
        echo '<th>Downtime</th>';
        echo '<th>Reason for Short Fall</th>';
        echo '<th>Action Plan</th>';
        echo '</tr></thead><tbody>';   
        
        $a1=array();
        $a2=array();
        $a3=array();
        $a4=array();
        $a5=array();
        $a6=array();
        $a7=array();
        $a8=array();
        $a9=array();
		
        
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
            
            $sql = "
			SELECT 
				DATE_FORMAT(`date_fall`, '%b/%Y') AS date, DATE_FORMAT(`date_fall`, '%m/%Y') as date2,
				SEC_TO_TIME(SUM(TIME_TO_SEC(`shortfalls`.`downtime`))) AS total,
				SUM(HOUR(`shortfalls`.`downtime`)) AS hours,
				SUM(MINUTE(`shortfalls`.`downtime`)) AS minutes, (SELECT 
					SEC_TO_TIME(SUM(TIME_TO_SEC(`shortfalls`.`downtime`))) AS total_time
				FROM
					`shortfalls`
				LEFT JOIN machines ON shortfalls.machine_id = machines.machine_id 
				WHERE
					location_id=10 AND  `shortfalls`.`date_fall` BETWEEN '2018-01-01 00:00:00' AND '2018-01-31 23:59:59') AS total_time,
				GROUP_CONCAT(`shortfalls`.`reason`
					SEPARATOR '<br />') AS reason,
				GROUP_CONCAT(`shortfalls`.`action_plan`
					SEPARATOR '<br />') AS action, machine_name, shortfalls.machine_id
			FROM
				`shortfalls`			
             LEFT JOIN machines ON shortfalls.machine_id = machines.machine_id
			 WHERE
				location_id=10 AND `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
			GROUP BY DATE_FORMAT(`date_fall`, '%b/%Y'), shortfalls.machine_id
			ORDER BY `date_fall`;";
            
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
            
            $sql = "
			SELECT 
				DATE_FORMAT(`date_fall`, '%Y') AS date,
				SEC_TO_TIME(SUM(TIME_TO_SEC(`shortfalls`.`downtime`))) AS total,
				SUM(HOUR(`shortfalls`.`downtime`)) AS hours,
				SUM(MINUTE(`shortfalls`.`downtime`)) AS minutes, (SELECT 
					SEC_TO_TIME(SUM(TIME_TO_SEC(`shortfalls`.`downtime`))) AS total_time
				FROM
					`shortfalls`
				LEFT JOIN machines ON shortfalls.machine_id = machines.machine_id 
				WHERE
					location_id=10 AND  `shortfalls`.`date_fall` BETWEEN '2018-01-01 00:00:00' AND '2018-01-31 23:59:59') AS total_time,
				GROUP_CONCAT(`shortfalls`.`reason`
					SEPARATOR '<br />') AS reason,
				GROUP_CONCAT(`shortfalls`.`action_plan`
					SEPARATOR '<br />') AS action, machine_name, shortfalls.machine_id
			FROM
				`shortfalls`
					
             LEFT JOIN machines ON shortfalls.machine_id = machines.machine_id
			 WHERE
				location_id=10 AND `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
			GROUP BY DATE_FORMAT(`date_fall`, '%Y'), shortfalls.machine_id
			ORDER BY `date_fall`;";
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
            
            $sql = "
			SELECT 
				DATE_FORMAT(`date_fall`, '%d/%m/%Y') AS date,
				SEC_TO_TIME(SUM(TIME_TO_SEC(`shortfalls`.`downtime`))) AS total,
				SUM(HOUR(`shortfalls`.`downtime`)) AS hours,
				SUM(MINUTE(`shortfalls`.`downtime`)) AS minutes, (SELECT 
					SEC_TO_TIME(SUM(TIME_TO_SEC(`shortfalls`.`downtime`))) AS total_time
				FROM
					`shortfalls`
				LEFT JOIN machines ON shortfalls.machine_id = machines.machine_id 
				WHERE
					location_id=10 AND  `shortfalls`.`date_fall` BETWEEN '2018-01-01 00:00:00' AND '2018-01-31 23:59:59') AS total_time,
				GROUP_CONCAT(`shortfalls`.`reason`
					SEPARATOR '<br />') AS reason,
				GROUP_CONCAT(`shortfalls`.`action_plan`
					SEPARATOR '<br />') AS action, machine_name, shortfalls.machine_id 
			FROM
				`shortfalls`
			LEFT JOIN machines ON shortfalls.machine_id = machines.machine_id
			WHERE
				location_id=10 AND `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
			GROUP BY DATE_FORMAT(`date_fall`, '%d/%m/%Y'), shortfalls.machine_id
			ORDER BY `date_fall`;";
            
        }
		
        $TOTAL = 0;
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
			   if($_POST['searchBy']==1)
        		{ 
					$myDateTime = DateTime::createFromFormat('d/m/Y', $row['date']);
				   $day = $myDateTime->format('w');
					if($day == 0)
					{
						echo '<tr class="warning">
							<td class="text-right">'. $row['date'] .'</td>
							<td class="text-right">'. $row['machine_name'] .'</td>
							<td class="text-right">'. $row['total'] .'</td>
							<td>'. $row['reason'] .'</td>
							<td>'. $row['action'] .'</td>
						</tr>';
					}
				   else
					{
						echo '<tr>
							<td class="text-right">'. $row['date'] .'</td>
							<td class="text-right">'. $row['machine_name'] .'</td>
							<td class="text-right">'. $row['total'] .'</td>
							<td>'. $row['reason'] .'</td>
							<td>'. $row['action'] .'</td>
						</tr>';
					}
			   }
				else
				{
                	echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. $row['machine_name'] .'</td>
                        <td class="text-right">'. $row['total'] .'</td>
                        <td>'. $row['reason'] .'</td>
                        <td>'. $row['action'] .'</td>
                    </tr>';
				}
                $hours = $row['hours'] + ($row['minutes'] / 60);
                $entrie = array( $row['date'], $hours);
                if($_POST['searchBy']==2)
                {
                    $entrie = array( $row['date2'], $hours);
                }
				if($row['machine_id'] == 21)
				{
                	array_push($a1,$entrie);
				}
				else if($row['machine_id'] == 22)
				{
                	array_push($a2,$entrie);
				}
				else if($row['machine_id'] == 23)
				{
                	array_push($a3,$entrie);
				}
				else if($row['machine_id'] == 24)
				{
                	array_push($a4,$entrie);
				}
				else if($row['machine_id'] == 25)
				{
                	array_push($a5,$entrie);
				}
				else if($row['machine_id'] == 26)
				{
                	array_push($a6,$entrie);
				}
				else if($row['machine_id'] == 27)
				{
                	array_push($a7,$entrie);
				}
				else if($row['machine_id'] == 28)
				{
                	array_push($a8,$entrie);
				}
				else if($row['machine_id'] == 29)
				{
                	array_push($a9,$entrie);
				}
				
				$TOTAL = $row['total_time'];
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
		
		
        echo '</tbody><tfoot><tr  class="active"><th></th>
			<th style="text-align:right"></th>
			<th style="text-align:right">Total</th>
			<th style="text-align:right"></th>
			<th></th></tr></tfoot>';
        echo '<script>document.getElementById("divChart1").setAttribute("class","col-md-12");</script>';
        echo '<script>document.getElementById("chartContainer").style= "height:200px;width:100%";</script>';
         echo '<script> 
            var chart = new CanvasJS.Chart("chartContainer", {
            theme: "light2",
            title: { 
                text: "Downtime"
            },
            exportFileName: "Downtime",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "Hours" },
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
		name: "Cutting - 1",';
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
        echo ' yValueFormatString: "#,###.# Hours",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a1 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a1 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a1 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		showInLegend: true,
		name: "Cutting - 2",';
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
        echo ' yValueFormatString: "#,###.# Hours",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a2 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a2 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a2 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		showInLegend: true,
		name: "Cutting - 3",';
        if($_POST['searchBy']==3)
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
        echo ' yValueFormatString: "#,###.# Hours",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a3 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a3 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a3 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		showInLegend: true,
		name: "Cutting - 4",';
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
        echo ' yValueFormatString: "#,###.# Hours",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a4 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a4 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a4 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		showInLegend: true,
		name: "Cutting - 5",';
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
        echo ' yValueFormatString: "#,###.# Hours",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a5 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a5 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a5 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		showInLegend: true,
		name: "Cutting - 6",';
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
        echo ' yValueFormatString: "#,###.# Hours",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a6 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a6 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a6 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		showInLegend: true,
		name: "Cutting - 7",';
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
        echo ' yValueFormatString: "#,###.# Hours",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a7 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a7 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a7 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		showInLegend: true,
		name: "Cutting - 8",';
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
        echo ' yValueFormatString: "#,###.# Hours",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a8 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a8 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a8 as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo ']},
            {
                type: "column",
		showInLegend: true,
		name: "Cutting - 9",';
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
        echo ' yValueFormatString: "#,###.# Hours",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a9 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a9 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a9 as $value) {
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
	
	public function reportSacksCutting()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Opening</th>';
        echo '<th>Good Production Cutting Sacks</th>';
        echo '<th>Good Production Packing Sacks + Waste</th>';
        echo '<th>Balance</th>';
        echo '<th>Closing</th>';
        echo '</tr></thead>
			<tfoot><tr  class="active">
			<th style="text-align:right">Total</th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			</tr></tfoot><tbody>'; 
		
		$report = "SELECT 
    DATE_FORMAT(production.`date_sacks`, '%Y-%m-%d') AS datereport,
    ROUND(COALESCE(production.net, 0),2) AS received,
    ROUND(COALESCE(consumed.used, 0) + COALESCE(waste.waste, 0),2) AS consumed,
    ROUND(COALESCE(stock_balance.difference, 0), 2) AS difference
FROM
(SELECT 
        date_sacks, SUM(net_weight) AS net
    FROM
        cutting_sacks
    GROUP BY DATE_FORMAT(`date_sacks`, '%Y-%m-%d')) production
LEFT JOIN
(SELECT 
        date_sacks, SUM(weight) AS used
    FROM
        packing_sacks
    GROUP BY DATE_FORMAT(`date_sacks`, '%Y-%m-%d')) consumed ON DATE_FORMAT(consumed.`date_sacks`, '%Y-%m-%d') = DATE_FORMAT(production.`date_sacks`, '%Y-%m-%d') 
LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 31
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) waste ON DATE_FORMAT(waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(production.`date_sacks`, '%Y-%m-%d') 
        
LEFT JOIN
    (SELECT 
        date_balance, SUM(difference) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id AND materials.semifinished = 1 
    WHERE
        machine_id = 34
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(production.`date_sacks`, '%Y-%m-%d')
GROUP BY DATE_FORMAT(production.`date_sacks`, '%Y-%m-%d') 


UNION ALL

SELECT 
    DATE_FORMAT(consumed.`date_sacks`, '%Y-%m-%d') AS datereport,
    0 AS received,
    ROUND(COALESCE(consumed.used, 0) + COALESCE(waste.waste, 0),2) AS consumed,
    ROUND(COALESCE(stock_balance.difference, 0), 2) AS difference
FROM
(SELECT 
        date_sacks, SUM(weight) AS used
    FROM
        packing_sacks
    GROUP BY DATE_FORMAT(`date_sacks`, '%Y-%m-%d')) consumed
LEFT JOIN
(SELECT 
        date_sacks, SUM(net_weight) AS net
    FROM
        cutting_sacks
    GROUP BY DATE_FORMAT(`date_sacks`, '%Y-%m-%d')) production ON DATE_FORMAT(consumed.`date_sacks`, '%Y-%m-%d') = DATE_FORMAT(production.`date_sacks`, '%Y-%m-%d') 
LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 31
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) waste ON DATE_FORMAT(waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(consumed.`date_sacks`, '%Y-%m-%d') 
        
LEFT JOIN
    (SELECT 
        date_balance, SUM(difference) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id AND materials.semifinished = 1 
    WHERE
        machine_id = 34
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(consumed.`date_sacks`, '%Y-%m-%d')
WHERE consumed.`date_sacks` IS NOT NULL AND production.date_sacks IS NULL
GROUP BY DATE_FORMAT(consumed.`date_sacks`,'%Y-%m-%d') 


UNION ALL

SELECT 
    DATE_FORMAT(`date_balance`, '%Y-%m-%d') AS datereport,
    0 AS received,
    0 AS consumed,
    ROUND(COALESCE(stock_balance.difference, 0), 2) AS difference
FROM
(SELECT 
        date_balance, SUM(difference) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id AND materials.semifinished = 1 
    WHERE
        machine_id = 34
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance
    
LEFT JOIN
(SELECT 
        date_sacks, SUM(weight) AS used
    FROM
        packing_sacks
    GROUP BY DATE_FORMAT(`date_sacks`, '%Y-%m-%d')) consumed 
     ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(consumed.`date_sacks`, '%Y-%m-%d')
LEFT JOIN
(SELECT 
        date_sacks, SUM(net_weight) AS net
    FROM
        cutting_sacks
    GROUP BY DATE_FORMAT(`date_sacks`, '%Y-%m-%d')) production ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(production.`date_sacks`, '%Y-%m-%d') 
  
WHERE consumed.`date_sacks` IS NULL AND production.date_sacks IS NULL
GROUP BY DATE_FORMAT(`date_balance`,'%Y-%m-%d')

UNION ALL 

SELECT 
    dateTable.selected_date AS datereport,
    0 AS received,
    0 AS consumed,
    0 AS difference
FROM
    (SELECT 
        ADDDATE('2018-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) selected_date
    FROM
        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) dateTable
LEFT JOIN
(SELECT 
        date_balance, SUM(difference) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id AND materials.semifinished = 1 
    WHERE
        machine_id = 34
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = dateTable.selected_date
    
LEFT JOIN
(SELECT 
        date_sacks, SUM(weight) AS used
    FROM
        packing_sacks
    GROUP BY DATE_FORMAT(`date_sacks`, '%Y-%m-%d')) consumed ON DATE_FORMAT(consumed.`date_sacks`, '%Y-%m-%d')  = dateTable.selected_date
LEFT JOIN
(SELECT 
        date_sacks, SUM(net_weight) AS net
    FROM
        cutting_sacks
    GROUP BY DATE_FORMAT(`date_sacks`, '%Y-%m-%d')) production ON DATE_FORMAT(production.`date_sacks`,'%Y-%m-%d') = dateTable.selected_date
";
		
		
		if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
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

			$sql= "SELECT DATE_FORMAT(`datereport`, '%b/%Y') AS datereport, opening, received, consumed, difference, closing
FROM
	(
    SELECT 
			datereport,	@a AS opening, received, consumed,  difference,
			@a:=@a + received - consumed + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(received) as received, SUM(consumed) as consumed, SUM(difference) as difference
			FROM
			("
				. $report .			
			"WHERE
    selected_date <= '". $newDateString2 ."'
        AND consumed.`date_sacks` IS NULL AND production.date_sacks IS NULL
        AND stock_balance.difference IS NULL
ORDER BY datereport
) movements GROUP BY DATE_FORMAT(datereport, '%m/%Y') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
WHERE datereport BETWEEN '". $newDateString ."' AND '". $newDateString2 ."'
ORDER BY report.datereport;";

		}
		else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
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
			
			$sql= "SELECT DATE_FORMAT(`datereport`, '%Y') AS datereport, opening, received, consumed, difference, closing
FROM
	(
    SELECT 
			datereport,	@a AS opening, received, consumed,  difference,
			@a:=@a + received - consumed + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(received) as received, SUM(consumed) as consumed, SUM(difference) as difference
			FROM
			("
				. $report .			
			" WHERE
    selected_date <= '". $newDateString2 ."'
        AND consumed.`date_sacks` IS NULL AND production.date_sacks IS NULL
        AND stock_balance.difference IS NULL
ORDER BY datereport
 ) movements GROUP BY DATE_FORMAT(datereport, '%Y') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
WHERE datereport BETWEEN '". $newDateString ."' AND '". $newDateString2 ."'
ORDER BY report.datereport;";
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
			
						$sql= "SELECT DATE_FORMAT(`datereport`, '%Y-%m-%d') AS datereport, opening, received, consumed, difference, closing
FROM
	(
    SELECT 
			datereport,	@a AS opening, received, consumed,  difference,
			@a:=@a + received - consumed + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(received) as received, SUM(consumed) as consumed, SUM(difference) as difference
			FROM
			("
				. $report .			
			" WHERE
    selected_date <= '". $newDateString2 ."'
        AND consumed.`date_sacks` IS NULL AND production.date_sacks IS NULL
        AND stock_balance.difference IS NULL
ORDER BY datereport
) movements GROUP BY DATE_FORMAT(datereport, '%Y-%m-%d') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
WHERE datereport BETWEEN '". $newDateString ."' AND '". $newDateString2 ."'
ORDER BY report.datereport;";

		}
		
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $DATE = $row['datereport'];
				$OPENING = $row['opening'];
				$RECEIVED = $row['received'];
				$CONSUMED = $row['consumed'];
				$CLOSING = $row['closing'];


				$DIFF = '<td class="text-right">'. number_format((float) $row['difference'],2,'.',',') .'</td>';
				if($row['difference'] != 0)
				{
					$DIFF = '<th class="text-right text-danger">'. number_format((float) $row['difference'],2,'.',',') .'</th>';
				}

				echo '<tr>
						<td class="text-right">'. $DATE .'</td>
						<td class="text-right">'. number_format((float) $OPENING,2,'.',',') .'</td>
						<td class="text-right">'. number_format((float) $RECEIVED,2,'.',',') .'</td>
						<td class="text-right">'. number_format((float) $CONSUMED,2,'.',',') .'</td>';
				echo $DIFF;
				echo '
						<td class="text-right">'. number_format((float) $CLOSING,2,'.',',') .'</td>
					</tr>';
						
						
				
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<tr><td>Something went wrong.</tr><td';  
        }   
	}
	
	
	public function reportEfficiencyPacking()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Plant Capacity</th>';
        echo '<th>Day Sacks</th>';
        echo '<th>Night Sacks</th>';
        echo '<th>Total Sacks</th>';
        echo '<th>United</th>';
        echo '<th>Ebony</th>';
        echo '<th>Total Production in Kgs</th>';
        echo '<th>Eff %</th>';
        echo '<th>Waste in Kgs</th>';
        echo '<th>Waste %</th>';
        echo '<th>Target weight per Sack</th>';
        echo '<th>Actual weight per Sack</th>';
        echo '</tr></thead>
			<tfoot><tr  class="active">
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
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			</tr></tfoot><tbody>'; 
        
        $a=array();
        $b=array();
        $c=array();
        $d=array();
        $e=array();
		
		        
        $newDateString = date("Y-m-d");
        $newDateString2 = date("Y-m-d");
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
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
            
			
            $sql = "SELECT  DATE_FORMAT(`date_sacks`, '%b/%Y') AS date, DATE_FORMAT(`date_sacks`, '%m/%Y') as date2, ROUND(SUM(weight), 2) AS actual,SUM(number) AS total, targetsack,
    waste.wastekgs,
    COUNT(DISTINCT (DATE_FORMAT(`date_sacks`, '%d/%m/%Y'))) AS days, capacity, day_sacks.day, night_sacks.night, united.count as united , ebony.count as ebony
FROM `packing_sacks`
LEFT JOIN
	(SELECT DATE_FORMAT(`date_sacks`, '%m/%Y') AS date, sum(number) as day
    FROM `packing_sacks`
    where shift = 1
    GROUP BY DATE_FORMAT(`date_sacks`, '%m/%Y')
    )day_sacks ON day_sacks.date = DATE_FORMAT(`packing_sacks`.`date_sacks`, '%m/%Y')
LEFT JOIN
	(SELECT DATE_FORMAT(`date_sacks`, '%m/%Y') AS date, sum(number) as night
    FROM `packing_sacks`
    where shift = 2
    GROUP BY DATE_FORMAT(`date_sacks`, '%m/%Y')
    )night_sacks ON night_sacks.date = DATE_FORMAT(`packing_sacks`.`date_sacks`, '%m/%Y')
	LEFT JOIN
(SELECT DATE_FORMAT(`date_sacks`, '%m/%Y') AS date, sum(number) as count
    FROM `packing_sacks`
    where customer_id = 1
    GROUP BY DATE_FORMAT(`date_sacks`, '%m/%Y')
    )united ON united.date = DATE_FORMAT(`packing_sacks`.`date_sacks`, '%m/%Y')
LEFT JOIN
	(SELECT DATE_FORMAT(`date_sacks`, '%m/%Y') AS date, sum(number) as count
    FROM `packing_sacks`
    where customer_id = 2
    GROUP BY DATE_FORMAT(`date_sacks`, '%m/%Y')
    )ebony ON ebony.date = DATE_FORMAT(`packing_sacks`.`date_sacks`, '%m/%Y')
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%m/%Y') AS date,
            SUM(waste) AS wastekgs
    FROM
        `waste`
    WHERE machine_id = 31 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%m/%Y')
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_sacks`, '%m/%Y')
LEFT JOIN
	(
		SELECT `settings`.value_setting AS capacity, DATE_FORMAT(`settings`.`to`, '%m/%Y') AS `to` , DATE_FORMAT(`settings`.`from`, '%m/%Y') AS `from`
        FROM `settings`
        WHERE `settings`.machine_id = 31 AND `settings`.name_setting = 'target'
    )
    target ON target.`from` <= DATE_FORMAT(`date_sacks`, '%m/%Y') AND (target.`to` IS NULL OR target.`to` > DATE_FORMAT(`date_sacks`, '%m/%Y'))
LEFT JOIN
	(
		SELECT `settings`.value_setting AS targetsack, `settings`.to, `settings`.from
        FROM `settings`
        WHERE `settings`.machine_id = 31 AND `settings`.name_setting = 'targetsack'
    )
    targetsack ON targetsack.`from` <= DATE_FORMAT(`date_sacks`, '%m/%Y') AND (targetsack.`to` IS NULL OR targetsack.`to` > DATE_FORMAT(`date_sacks`, '%m/%Y'))
WHERE
    date_sacks BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_sacks`, '%b/%Y')
ORDER BY `date_sacks`;";
            
            
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
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
            
              $sql = "SELECT  DATE_FORMAT(`date_sacks`, '%Y') AS date, ROUND(SUM(weight), 2) AS actual,SUM(number) AS total,
    waste.wastekgs,targetsack,
    COUNT(DISTINCT (DATE_FORMAT(`date_sacks`, '%d/%m/%Y'))) AS days, capacity, day_sacks.day, night_sacks.night, united.count as united , ebony.count as ebony
FROM `packing_sacks`
LEFT JOIN
	(SELECT DATE_FORMAT(`date_sacks`, '%Y') AS date, sum(number) as day
    FROM `packing_sacks`
    where shift = 1
    GROUP BY DATE_FORMAT(`date_sacks`, '%Y')
    )day_sacks ON day_sacks.date = DATE_FORMAT(`packing_sacks`.`date_sacks`, '%Y')
LEFT JOIN
	(SELECT DATE_FORMAT(`date_sacks`, '%Y') AS date, sum(number) as night
    FROM `packing_sacks`
    where shift = 2
    GROUP BY DATE_FORMAT(`date_sacks`, '%Y')
    )night_sacks ON night_sacks.date = DATE_FORMAT(`packing_sacks`.`date_sacks`, '%Y')
LEFT JOIN
	(SELECT DATE_FORMAT(`date_sacks`, '%Y') AS date, sum(number) as count
    FROM `packing_sacks`
    where customer_id = 1
    GROUP BY DATE_FORMAT(`date_sacks`, '%Y')
    )united ON united.date = DATE_FORMAT(`packing_sacks`.`date_sacks`, '%Y')
LEFT JOIN
	(SELECT DATE_FORMAT(`date_sacks`, '%Y') AS date, sum(number) as count
    FROM `packing_sacks`
    where customer_id = 2
    GROUP BY DATE_FORMAT(`date_sacks`, '%Y')
    )ebony ON ebony.date = DATE_FORMAT(`packing_sacks`.`date_sacks`, '%Y')
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%Y') AS date,
            SUM(waste) AS wastekgs
    FROM
        `waste`
    WHERE machine_id = 31 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%Y')
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_sacks`, '%Y')
LEFT JOIN
	(
		SELECT `settings`.value_setting AS capacity, DATE_FORMAT(`settings`.`to`, '%Y') AS `to` , DATE_FORMAT(`settings`.`from`, '%Y') AS `from`
        FROM `settings`
        WHERE `settings`.machine_id = 31 AND `settings`.name_setting = 'target'
    )
    target ON target.`from` <= DATE_FORMAT(`date_sacks`, '%Y') AND (target.`to` IS NULL OR target.`to` > DATE_FORMAT(`date_sacks`, '%Y'))
LEFT JOIN
	(
		SELECT `settings`.value_setting AS targetsack, `settings`.to, `settings`.from
        FROM `settings`
        WHERE `settings`.machine_id = 31 AND `settings`.name_setting = 'targetsack'
    )
    targetsack ON targetsack.`from` <= DATE_FORMAT(`date_sacks`, '%Y') AND (targetsack.`to` IS NULL OR targetsack.`to` > DATE_FORMAT(`date_sacks`, '%Y'))
WHERE
    date_sacks BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_sacks`, '%Y')
ORDER BY `date_sacks`;";
            
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
            
            $sql = "SELECT DATE_FORMAT(`date_sacks`, '%d/%m/%Y') AS date, ROUND(SUM(weight), 2) AS actual,SUM(number) AS total,
    waste.wastekgs,targetsack,
    COUNT(DISTINCT (DATE_FORMAT(`date_sacks`, '%d/%m/%Y'))) AS days, capacity, day_sacks.day, night_sacks.night, united.count as united , ebony.count as ebony
FROM `packing_sacks`
LEFT JOIN
	(SELECT DATE_FORMAT(`date_sacks`, '%Y/%m/%d') AS date, sum(number) as day
    FROM `packing_sacks`
    where shift = 1
    GROUP BY DATE_FORMAT(`date_sacks`, '%d/%m/%Y')
    )day_sacks ON day_sacks.date = DATE_FORMAT(`packing_sacks`.`date_sacks`, '%Y/%m/%d')
LEFT JOIN
	(SELECT DATE_FORMAT(`date_sacks`, '%Y/%m/%d') AS date, sum(number) as night
    FROM `packing_sacks`
    where shift = 2
    GROUP BY DATE_FORMAT(`date_sacks`, '%d/%m/%Y')
    )night_sacks ON night_sacks.date = DATE_FORMAT(`packing_sacks`.`date_sacks`, '%Y/%m/%d')
LEFT JOIN
	(SELECT DATE_FORMAT(`date_sacks`, '%Y/%m/%d') AS date, sum(number) as count
    FROM `packing_sacks`
    where customer_id = 1
    GROUP BY DATE_FORMAT(`date_sacks`, '%d/%m/%Y')
    )united ON united.date = DATE_FORMAT(`packing_sacks`.`date_sacks`, '%Y/%m/%d')
LEFT JOIN
	(SELECT DATE_FORMAT(`date_sacks`, '%Y/%m/%d') AS date, sum(number) as count
    FROM `packing_sacks`
    where customer_id = 2
    GROUP BY DATE_FORMAT(`date_sacks`, '%d/%m/%Y')
    )ebony ON ebony.date = DATE_FORMAT(`packing_sacks`.`date_sacks`, '%Y/%m/%d')
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%Y/%m/%d') AS date,
            SUM(waste) AS wastekgs
    FROM
        `waste`
    WHERE machine_id = 31 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d')
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_sacks`, '%Y/%m/%d')
LEFT JOIN
	(
		SELECT `settings`.value_setting AS capacity, `settings`.to, `settings`.from
        FROM `settings`
        WHERE `settings`.machine_id = 31 AND `settings`.name_setting = 'target'
    )
    target ON target.`from` <= DATE_FORMAT(`date_sacks`, '%Y/%m/%d') AND (target.`to` IS NULL OR target.`to` > DATE_FORMAT(`date_sacks`, '%Y/%m/%d'))
LEFT JOIN
	(
		SELECT `settings`.value_setting AS targetsack, `settings`.to, `settings`.from
        FROM `settings`
        WHERE `settings`.machine_id = 31 AND `settings`.name_setting = 'targetsack'
    )
    targetsack ON targetsack.`from` <= DATE_FORMAT(`date_sacks`, '%Y/%m/%d') AND (targetsack.`to` IS NULL OR targetsack.`to` > DATE_FORMAT(`date_sacks`, '%Y/%m/%d'))
WHERE
    date_sacks BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_sacks`, '%d/%m/%Y')
ORDER BY `date_sacks`;";
            
        }
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $CAPACITYROW = $row['capacity'] * $row['days'];
                $WASTEKG = $row['wastekgs'];
                if(is_null($row['wastekgs']))
                {
                    $WASTEKG = 0;
                }
                $ACTUAL = $row['actual'] + $WASTEKG;
                if(is_null($row['actual']))
                {
                    $ACTUAL = 0 + $WASTEKG;
                    $WASTEEFF = 0;
                }
                else
                {
                    $WASTEEFF  = round($WASTEKG* 100 / $ACTUAL , 2);
                }
				if(!is_null($row['capacity']))
                {
					$EFF = round($row['total'] *100/ $CAPACITYROW, 2);
                }
				if(!is_null($row['targetsack']))
                {
					$TARGETSACK = $row['targetsack'];
					$SACK = round( $ACTUAL / $row['total'], 2);
                }
                echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. number_format($CAPACITYROW,0,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['day'],0,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['night'],0,'.',',') .'</td>
                        <th class="text-right">'. number_format($row['total'],0,'.',',') .'</th>
                        <td class="text-right">'. number_format($row['united'],0,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['ebony'],0,'.',',') .'</td>
                        <td class="text-right">'. number_format($ACTUAL,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($EFF,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($WASTEKG,2,'.',',') .'</td>
                        <th class="text-right">'. number_format($WASTEEFF,2,'.',',') .'</th>
                        <td class="text-right">'. number_format($TARGETSACK,2,'.',',') .'</td>
                        <th class="text-right">'. number_format($SACK,2,'.',',') .'</th>
                    </tr>';
                $entrie0 = array( $row['date'], $CAPACITYROW);
                $entrie1 = array( $row['date'], $row['total']);
                $entrie3 = array( $row['date'], $WASTEEFF);
                if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
                {
                    $entrie0 = array( $row['date2'], $CAPACITYROW);
                	$entrie1 = array( $row['date2'], $row['total']);
                	$entrie3 = array( $row['date2'], $WASTEEFF);
                }
				array_push($b,$entrie1);
                array_push($d,$entrie3);
				array_push($e,$entrie0);
            }
            $stmt->closeCursor();
        }
        else
        {
            echo "<tr>
                    <td>Something went wrong.</td>
                    <td>$db->errorInfo</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>";
        }
         echo '</tbody>';
        echo '<script>document.getElementById("chartContainer").style= "height:200px;";</script>';
        echo '<script>document.getElementById("chartContainer2").style= "height:200px;";</script>';
         echo '<script> 
            var chart = new CanvasJS.Chart("chartContainer", {
            theme: "light2",
            title: { 
                text: "Production Target Achievement"
            },
            exportFileName: "Production Target Achievement",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "Sacks" },
            toolTip: {
                shared: true
            },
            legend:{
                itemclick : function(e){
				
					if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
						e.dataSeries.visible = false;
					} else {
						e.dataSeries.visible = true;
					}
					chart.render();
				}
            },';
        if(!empty($_POST['searchBy']) and  $_POST['searchBy']==2)
        {  
            echo 'axisX:{ valueFormatString: "MMM YYYY"},';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            echo 'axisX:{ valueFormatString: "YYYY"},';
        }
        else
        {
            echo 'axisX:{ valueFormatString: "DD MMM"},';
        }
        echo 'data: [
            {
                type: "line",
		      showInLegend: true,
		      name: "Packing Sacks",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,### Sacks",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($b as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$e[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($b as $key => $value) {
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$e[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].', '.$marker.' },';
            }; 
        }
        else
        {
            foreach($b as $key=>$value) {
                $var = (int) explode("/", $value[0])[1]-1;
                $marker = 'markerType: "triangle",  markerColor: "green"';
                if($value[1]<$e[$key][1])
                {
                    $marker = 'markerType: "cross", markerColor: "tomato"';
                }
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].', '.$marker.' },';
            }; 
        }
		echo ']},
            {
                type: "line",
				showInLegend: true,
				name: "Target",
				lineDashType: "dash",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,### Sacks",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($e as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1] .'},';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($e as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($e as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
        echo'] }]});
        chart.render();
        </script>'; 
        echo '<script> 
            var chart1 = new CanvasJS.Chart("chartContainer2", {
            theme: "light2",
            title: { 
                text: "Waste Target Achievement"
            },
            exportFileName: "Waste Target Achievement",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "Waste %" },
            toolTip: {
                shared: true
            },
			legend:{
                itemclick : function(e){
				
					if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
						e.dataSeries.visible = false;
					} else {
						e.dataSeries.visible = true;
					}
					chart1.render();
				}
            },';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'axisX:{ valueFormatString: "MMM YYYY"},';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            echo 'axisX:{ valueFormatString: "YYYY"},';
        }
        else
        {
            echo 'axisX:{ valueFormatString: "DD MMM"},';
        }
        echo 'data: [
            {
              type: "line",
		      showInLegend: true,
		      name: "Waste",';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            echo 'xValueFormatString: "MMM YYYY",';
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            
            echo 'xValueFormatString: "YYYY",';
        }
        else
        {
            echo 'xValueFormatString: "DD MMM",';
        }
        echo ' yValueFormatString: "#,##0.00 ",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($d as $key => $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
        {   
            foreach($d as $key => $value) {
                
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].' },';
            }; 
        }
        else
        {
            foreach($d as $key => $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
        echo'] }]});
        chart1.render(); 
        </script>'; 
    }
	
	public function reportProductionPacking()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Good Production</th>';
        echo '<th>No. of sacks produced</th>';
        echo '</tr></thead><tfoot><tr  class="active">
			<th style="text-align:right">Total</th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th></tr></tfoot><tbody>';   
        
        $a=array();
        
        $b=array();
		
		
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
            
            $sql = " SELECT DATE_FORMAT(`date_sacks`, '%b/%Y') as date, DATE_FORMAT(`date_sacks`, '%m/%Y') as date2, ROUND(SUM(weight),2) as actual, SUM(number) as rolls
FROM packing_sacks
WHERE `date_sacks` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_sacks`, '%m/%Y')
ORDER BY `date_sacks`;";
            
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
            
            $sql = "SELECT DATE_FORMAT(`date_sacks`, '%Y') as date, ROUND(SUM(weight),2) as actual, SUM(number) as rolls
FROM packing_sacks
WHERE `date_sacks` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_sacks`, '%Y')
ORDER BY `date_sacks`;";
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
            
            $sql = "SELECT DATE_FORMAT(`date_sacks`, '%d/%m/%Y') as date, ROUND(SUM(weight),2) as actual, SUM(number) as rolls
FROM packing_sacks
WHERE `date_sacks` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_sacks`, '%d/%m/%Y')
ORDER BY `date_sacks`;";
            
        }
        
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. number_format($row['actual'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['rolls'],0,'.',',') .'</td>
                    </tr>';
                $entrie = array( $row['date'], $row['actual']);
                $entrie1 = array( $row['date'], $row['rolls']);
                if($_POST['searchBy']==2)
                {
                    $entrie = array( $row['date2'], $row['actual']);
                    $entrie1 = array( $row['date2'], $row['rolls']);
                }
				array_push($a,$entrie);
				array_push($b,$entrie1);
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
        echo '<script>document.getElementById("chartContainer").style= "height:200px;";</script>';
        echo '<script>document.getElementById("chartContainer2").style= "height:200px;";</script>';
         echo '<script> 
            var chart = new CanvasJS.Chart("chartContainer", {
            theme: "light2",
            title: { 
                text: "Production "
            },
            exportFileName: "Production",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "Total net production (kgs)" },
            toolTip: {
                shared: true
            },legend:{
                itemclick : function(e){
				
					if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
						e.dataSeries.visible = false;
					} else {
						e.dataSeries.visible = true;
					}
					chart.render();
				}
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
		      name: "Packing",';
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
        </script>'; 
        echo '<script> 
            var chart1 = new CanvasJS.Chart("chartContainer2", {
            theme: "light2",
            title: { 
                text: "No. of sacks produced"
            },
            exportFileName: "No. of sacks produced",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "Sacks" },
            toolTip: {
                shared: true
            },
			legend:{
                itemclick : function(e){
				
					if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
						e.dataSeries.visible = false;
					} else {
						e.dataSeries.visible = true;
					}
					chart1.render();
				}
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
		      name: "Packing",';
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
        echo ' yValueFormatString: "#,### sacks",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($b as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($b as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($b as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
        echo'] }]});
        chart1.render(); 
        </script>';
    }
	
	public function reportWastePacking()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Total Waste</th>';
        echo '</tr></thead><tfoot><tr  class="active">
			<th style="text-align:right">Total</th>
			<th style="text-align:right"></th></tr></tfoot><tbody>';   
        
        $a1=array();
		
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
            
            $sql = " SELECT DATE_FORMAT(`date_waste`, '%b/%Y') as date, DATE_FORMAT(`date_waste`, '%m/%Y') as date2, SUM(`waste`.`waste`) AS total
             FROM  `waste`
             WHERE machine_id=31 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_waste`, '%m/%Y') 
             ORDER BY `date_waste`;";
            
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
            
            $sql = " SELECT DATE_FORMAT(`date_waste`, '%Y') AS date, SUM(`waste`.`waste`) AS total
             FROM  `waste`
             WHERE machine_id=31 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_waste`, '%Y') 
             ORDER BY `date_waste`";
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
            
            $sql = " SELECT DATE_FORMAT(`date_waste`, '%d/%m/%Y') AS date, SUM(`waste`.`waste`) AS total
             FROM  `waste`
             WHERE machine_id=31 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_waste`, '%d/%m/%Y') 
             ORDER BY `date_waste`;";
            
        }
        
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. number_format($row['total'],2,'.',',') .'</td>
                    </tr>';
                $entrie = array( $row['date'], $row['total']);
                if($_POST['searchBy']==2)
                {
                    $entrie = array( $row['date2'], $row['total']);
                }
                array_push($a1,$entrie);
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
		name: "Packing",';
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
            foreach($a1 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a1 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a1 as $value) {
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
	
	 public function reportMaterialConsumptionPacking()
    {
       
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
        }
		$materialid = "";
		$materialname = "Sacks";
		$materialgrade = "Cutting";
		
		
			
			echo '<table class="table table-bordered table-hover" width="100%" cellspacing="0"  >
				  <thead><tr  class="active">';
			echo '<th class="text-center">From: '.$newDateString.'<br/> To: '. $newDateString2.'</th>';
					echo '<th class="text-center">'. $materialname .' - '. $materialgrade .'<br/> (100 %)</th>';    
			echo '</tr></thead><tbody>';
			if($_POST['searchBy']==2)
			{  
				$sql = "SELECT DATE_FORMAT(`date_report`, '%b/%Y') as date, DATE_FORMAT(`date_report`, '%m/%Y') as date2, SUM(actual) as actual, SUM(wastekgs) as wastekgs
				FROM
				(
					SELECT `date_sacks`  as date_report, ROUND(SUM(weight),2) as actual, wastekgs
					 FROM packing_sacks
					LEFT JOIN
					(   SELECT DATE_FORMAT(`date_waste`, '%Y/%m/%d') as date, SUM(waste) as wastekgs
						FROM  `waste`
						WHERE Machine_id = 31 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
						GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d') 
						ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_sacks`, '%Y/%m/%d')
					 WHERE date_sacks BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
					 GROUP BY DATE_FORMAT(`date_sacks`, '%d/%m/%Y') 
					 ORDER BY `date_sacks`
				) report
				GROUP BY DATE_FORMAT(`date_report`, '%m/%Y')";
			}
			else if($_POST['searchBy']==3)
			{  
				$sql = "SELECT DATE_FORMAT(`date_report`, '%Y') as date, DATE_FORMAT(`date_report`, '%m/%Y') as date2, SUM(actual) as actual, SUM(wastekgs) as wastekgs
				FROM
				(
					SELECT `date_sacks`  as date_report, ROUND(SUM(weight),2) as actual, wastekgs
					 FROM packing_sacks
					LEFT JOIN
					(   SELECT DATE_FORMAT(`date_waste`, '%Y/%m/%d') as date, SUM(waste) as wastekgs
						FROM  `waste`
						WHERE Machine_id = 31 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
						GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d') 
						ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_sacks`, '%Y/%m/%d')
					 WHERE date_sacks BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
					 GROUP BY DATE_FORMAT(`date_sacks`, '%d/%m/%Y') 
					 ORDER BY `date_sacks`
				) report
				GROUP BY DATE_FORMAT(`date_report`, '%Y');";
			}
			else
			{
				$sql = "SELECT DATE_FORMAT(`date_sacks`, '%d/%m/%Y')  as date, ROUND(SUM(weight),2) as actual, wastekgs
				 FROM packing_sacks
				LEFT JOIN
				(   SELECT DATE_FORMAT(`date_waste`, '%Y/%m/%d') as date, SUM(waste) as wastekgs
					FROM  `waste`
					WHERE Machine_id = 31 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
					GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d') 
					ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_sacks`, '%Y/%m/%d')
				 WHERE date_sacks BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
				 GROUP BY DATE_FORMAT(`date_sacks`, '%d/%m/%Y') 
				 ORDER BY `date_sacks`;";
			}
		$totalC = 0;
				if($stmt = $this->_db->prepare($sql))
				{
					$stmt->execute();
					while($row = $stmt->fetch())
					{
						$TOTAL = $row['actual'] + $row['wastekgs'];
						$entrie = array( $row['date'], $TOTAL);
						if($_POST['searchBy']==2)
						{
							$entrie = array( $row['date2'], $TOTAL);
						}
						
						$totalC += $TOTAL;
						array_push($a,$entrie);
						echo '<tr>
								<td class="text-right">'. $row['date'] .'</td>
								<td class="text-right">'. number_format($TOTAL,2,'.',',') .'</td>'  ;
						echo '</tr>';
						
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
				echo '
					<tfoot><tr  class="active">
					<th class="text-right">Total</th>';
				echo '<th class="text-right">'. number_format($totalC,2,'.',',') .'</th>';
				
				echo '</tr></tfoot></table>';
       
		
		       
        echo '<script>document.getElementById("divChart1").setAttribute("class","col-md-12");</script>';
        echo '<script>document.getElementById("chartContainer").style= "height:200px;width:100%";</script>';
         echo '<script> 
            var chart = new CanvasJS.Chart("chartContainer", {
            theme: "light2",
            title: { 
                text: "Raw Material Consumption "
            },
            exportFileName: "Raw Material Consumption ",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "Total net consumption (kgs)" },
            legend:{
                itemclick : toggleDataSeries
            },
            toolTip: {
                shared: true
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
                 type: "line",
				connectNullData: false,
		      showInLegend: true,
		      name: "'.$materialname .' - '. $materialgrade.'",';
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
        echo ' yValueFormatString: "#,##0.00 Kgs",
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
        echo ']}';
        
        echo']});
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
	
	public function reportReasonPacking()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Downtime</th>';
        echo '<th>Reason for Short Fall</th>';
        echo '<th>Action Plan</th>';
        echo '</tr></thead><tbody>';   
        
        $a1=array();
		
        
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
            
            $sql = "
			SELECT 
				DATE_FORMAT(`date_fall`, '%b/%Y') AS date, DATE_FORMAT(`date_fall`, '%m/%Y') as date2,
	SEC_TO_TIME(SUM(TIME_TO_SEC(`shortfalls`.`downtime`))) AS total,
	SUM(HOUR(`shortfalls`.`downtime`)) AS hours,
	SUM(MINUTE(`shortfalls`.`downtime`)) AS minutes, 
	GROUP_CONCAT(`shortfalls`.`reason`
		SEPARATOR '<br />') AS reason,
	GROUP_CONCAT(`shortfalls`.`action_plan`
		SEPARATOR '<br />') AS action
FROM
	`shortfalls`
WHERE
	machine_id=31 AND `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_fall`, '%m/%Y')
ORDER BY `date_fall`;";
            
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
            
            $sql = "
			SELECT 
	DATE_FORMAT(`date_fall`, '%Y') AS date,
	SEC_TO_TIME(SUM(TIME_TO_SEC(`shortfalls`.`downtime`))) AS total,
	SUM(HOUR(`shortfalls`.`downtime`)) AS hours,
	SUM(MINUTE(`shortfalls`.`downtime`)) AS minutes, 
	GROUP_CONCAT(`shortfalls`.`reason`
		SEPARATOR '<br />') AS reason,
	GROUP_CONCAT(`shortfalls`.`action_plan`
		SEPARATOR '<br />') AS action
FROM
	`shortfalls`
WHERE
	machine_id=31 AND `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_fall`, '%Y')
ORDER BY `date_fall`;";
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
            
            $sql = "
			SELECT 
	DATE_FORMAT(`date_fall`, '%d/%m/%Y') AS date,
	SEC_TO_TIME(SUM(TIME_TO_SEC(`shortfalls`.`downtime`))) AS total,
	SUM(HOUR(`shortfalls`.`downtime`)) AS hours,
	SUM(MINUTE(`shortfalls`.`downtime`)) AS minutes, 
	GROUP_CONCAT(`shortfalls`.`reason`
		SEPARATOR '<br />') AS reason,
	GROUP_CONCAT(`shortfalls`.`action_plan`
		SEPARATOR '<br />') AS action
FROM
	`shortfalls`
WHERE
	machine_id=31 AND `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_fall`, '%d/%m/%Y')
ORDER BY `date_fall`;";
            
        }
		
        $TOTAL = 0;
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
			   if($_POST['searchBy']==1)
        		{ 
					$myDateTime = DateTime::createFromFormat('d/m/Y', $row['date']);
				   $day = $myDateTime->format('w');
					if($day == 0)
					{
						echo '<tr class="warning">
							<td class="text-right">'. $row['date'] .'</td>
							<td class="text-right">'. $row['total'] .'</td>
							<td>'. $row['reason'] .'</td>
							<td>'. $row['action'] .'</td>
						</tr>';
					}
				   else
					{
						echo '<tr>
							<td class="text-right">'. $row['date'] .'</td>
							<td class="text-right">'. $row['total'] .'</td>
							<td>'. $row['reason'] .'</td>
							<td>'. $row['action'] .'</td>
						</tr>';
					}
			   }
				else
				{
                	echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. $row['total'] .'</td>
                        <td>'. $row['reason'] .'</td>
                        <td>'. $row['action'] .'</td>
                    </tr>';
				}
                $hours = $row['hours'] + ($row['minutes'] / 60);
                $entrie = array( $row['date'], $hours);
                if($_POST['searchBy']==2)
                {
                    $entrie = array( $row['date2'], $hours);
                }
				array_push($a1,$entrie);
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
		
		
        echo '</tbody><tfoot><tr  class="active"><th></th>
			<th style="text-align:right">Total</th>
			<th style="text-align:right"></th>
			<th></th></tr></tfoot>';
        echo '<script>document.getElementById("divChart1").setAttribute("class","col-md-12");</script>';
        echo '<script>document.getElementById("chartContainer").style= "height:200px;width:100%";</script>';
         echo '<script> 
            var chart = new CanvasJS.Chart("chartContainer", {
            theme: "light2",
            title: { 
                text: "Downtime"
            },
            exportFileName: "Downtime",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "Hours" },
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
		name: "Packing",';
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
        echo ' yValueFormatString: "#,###.# Hours",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($a1 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a1 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a1 as $value) {
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