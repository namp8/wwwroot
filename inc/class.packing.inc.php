<?php

/**
 * Handles user interactions within the packing bag section
 *
 * PHP version 5
 *
 * @author Natalia Montañez
 * @copyright 2017 Natalia Montañez
 *
 */
class Packing
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
				WHERE `packing` =1 AND (`material` = 1 OR `master_batch` = 1)
                ORDER BY `materials`.`material_name`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                echo  '<li><a id="'. $NAME .' - '. $GRADE .'" onclick="selectMaterial(\''. $ID .'\',\''. $NAME .'\',\''. $GRADE .'\')"><b>'. $NAME .'</b>&nbsp - &nbsp'. $GRADE .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	
	 public function consumablesDropdown()
    {
        $sql = "SELECT `materials`.`material_id`,
                `materials`.`material_name`,
                `materials`.`material_grade`
                FROM `materials`
				WHERE `packing` = 1 AND `consumables` = 1
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
	
	public function operators1Dropdown()
    {
        $sql = "SELECT `employees`.`employee_id`,
					`employees`.employee_name
				FROM `employees`
				WHERE packing = 1;
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
		WHERE packing_bags = 1;";
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
	
	/**
     * Checks gives the settings
     *
     */
    public function giveSettings()
    {
        $sql = "SELECT `settings`.`name_setting`, `settings`.`value_setting`
                FROM  `settings` 
                WHERE machine_id=8;";
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
        
        $name = "";
        if($_POST['action'] ==1)
        {
            $name = "thickness";
        }
        else if($_POST['action'] ==3)
        {
            $name = "target";
        }
        else if($_POST['action'] ==4)
        {
            $name = "waste";
        }
        else if($_POST['action'] ==5)
        {
            $name = "cone";
        }
        
        $sql = "UPDATE  `settings`
                SET `to` = CURRENT_DATE, `actual` = 0
                WHERE machine_id = 8 AND `name_setting` = '". $name ."' AND `actual` = 1;
				INSERT INTO `settings`(`setting_id`,`machine_id`,`name_setting`,`value_setting`,`from`,
				`to`,`actual`)VALUES
				(NULL,8,'". $name ."','". $value ."',CURRENT_DATE(),NULL,1);";
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
	
	 public function giveTotalFormula()
    {
        $sql = "SELECT `packing_bag_formulas`.`material_id`, material_name, material_grade, `packing_bag_formulas`.`kg`,
	`packing_bag_formulas`.`kg`/(SELECT sum(`packing_bag_formulas`.`kg`) FROM `packing_bag_formulas` WHERE actual =1 AND color =0)*100 AS percentage
FROM `packing_bag_formulas`
JOIN `materials` ON  `materials`.material_id = `packing_bag_formulas`.material_id
WHERE `actual` = 1 AND `packing_bag_formulas`.color = 0;";
		
        $a=array();
		if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
			 $total = 0;
            while($row = $stmt->fetch())
            {
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
				$KG = $row['kg']; 
				$total = $total + $KG;
				$PERCENTAGE = $row['percentage'];
				$materialArray=array($NAME,$GRADE,number_format($PERCENTAGE,2,'.',''));
				array_push($a,$materialArray);
				echo '<tr>
                        <td><b>'. $NAME .'</b></td>
                        <td>'. $GRADE .'</td>
                        <td class="text-right">'. number_format($KG,1,'.',',') .'</td>
                        <td class="text-right">'. $this->giveBags($KG) .'</td>
                        <td class="text-right">'. number_format($PERCENTAGE,2,'.',',') .' %</td>
                    </tr>';
				
            }
			echo '<tr class="active">
                    <td colspan="2" class="text-center"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong>'. number_format($total,1,'.',',') .'</strong></td>
                    <td class="text-right"><strong>'. $this->giveBags($total) .'</strong></td>
                    <td class="text-right"><strong> 100.00 % </strong></td>
                </tr>';
            $stmt->closeCursor();
            
            
            return $a;
        }
        else
        {
            echo "Something went wrong. $db->errorInfo";
        }
    }
	
	 public function giveColorFormula($color)
    {
        $sql = "SELECT `packing_bag_formulas`.`material_id`, material_name, material_grade, `packing_bag_formulas`.`kg`
FROM `packing_bag_formulas`
JOIN `materials` ON  `materials`.material_id = `packing_bag_formulas`.material_id
WHERE `actual` = 1 AND `packing_bag_formulas`.color = ". $color .";";
		
        $a=array();
		if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
			$total = 0;			
			$KG = 25;
			$total = $total + $KG;
            while($row = $stmt->fetch())
            {
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
				$KG = $row['kg']; 
				$total = $total + $KG;
				$PERCENTAGE = $KG/$total * 100;
				$materialArray=array($NAME,$GRADE,number_format($PERCENTAGE,2,'.',''));
				array_push($a,$materialArray);
				
				
            }
			
			$PERCEN = 25 / $total * 100;
			echo '<tr>
					<td><b>MIX MATERIAL</b></td>
					<td></td>
					<td class="text-right">'. number_format(25,1,'.',',') .'</td>
					<td class="text-right">'. $this->giveBags(25) .'</td>
                    <td class="text-right"><strong>'. number_format($PERCEN,2,'.',',') .' %</strong></td>
				</tr>';
			echo '<tr>
                        <td><b>'. $NAME .'</b></td>
                        <td>'. $GRADE .'</td>
                        <td class="text-right">'. number_format($KG,1,'.',',') .'</td>
                        <td class="text-right">'. $this->giveBags($KG) .'</td>
                    	<td class="text-right"><strong> '. number_format($PERCENTAGE,2,'.','').' %</strong></td>
                    </tr>';
			echo '<tr class="active">
                    <td colspan="2" class="text-center"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong>'. number_format($total,1,'.',',') .'</strong></td>
                    <td class="text-right"><strong>'. $this->giveBags($total) .'</strong></td>
                    <td class="text-right"><strong> 100.00 % </strong></td>
                </tr>';
            $stmt->closeCursor();
            
            
            return $a;
            $stmt->closeCursor();
            
            return $a;
        }
        else
        {
            echo "Something went wrong. $db->errorInfo";
        }
    }
	 /**
     * Checks and inserts a new formula
     *
     * @return boolean  true if can insert  false if not
     */
    public function createFormula()
    {
        $material = $kg = $remarks= "";
        
        $material = trim($_POST["material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
        
        $kg = trim($_POST["kg"]);
        $kg = stripslashes($kg);
        $kg = htmlspecialchars($kg);
		
        $remarks = stripslashes($_POST["remarks"]);
        $remarks = htmlspecialchars($remarks);
		
		//DATE
        $date = date("Y-m-d");
        if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = $newDateString;
        }
        
        
        $sql = "INSERT INTO `packing_bag_formulas`(`packing_bag_formula`,`material_id`, `kg`,`from`,`to`,`actual`,
		`remarks`) VALUES(NULL,:material, :kg,'". $date."',NULL,1, :remarks);";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":material", $material, PDO::PARAM_INT);
            $stmt->bindParam(":kg", $kg, PDO::PARAM_STR);
            $stmt->bindParam(":remarks", $remarks, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
            
            echo '<strong>SUCCESS!</strong> The material was successfully added to the formula with <strong>'. $kg .' kgs </strong>';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> The material is already in the formula. If you want to change the amount of kilograms, please try updating it.<br>';
            } else {
              echo '<strong>ERROR</strong> Could not insert the material into the database. Please try again.<br>'. $e->getMessage();
            }
            
            return FALSE;
        } 

    }
    
     /**
     * Checks and update a formula
     *
     * @return boolean  true if can update false if not
     */
    public function updateFormula()
    {
        $material = $kg = $remarks= "";
        
        $material = trim($_POST["material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
        
        $kg = trim($_POST["kg"]);
        $kg = stripslashes($kg);
        $kg = htmlspecialchars($kg);
        
		
        $remarks = stripslashes($_POST["remarks"]);
        $remarks = htmlspecialchars($remarks);
		
		//DATE
        $date = date("Y-m-d");
        if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = $newDateString;
        }
		
        $sql = "UPDATE  `packing_bag_formulas`
                SET `to` = '". $date."', `actual` = 0, `remarks` = concat(`remarks`,' ". $remarks."') 
                WHERE `material_id` = '".$material ."' AND `actual` = 1;
				INSERT INTO `packing_bag_formulas`(`packing_bag_formula`,`material_id`, `kg`,`from`,`to`,`actual`,
				`remarks`) VALUES(NULL,:material, :kg, '". $date."',NULL,1, :remarks);";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":material", $material, PDO::PARAM_INT);
            $stmt->bindParam(":kg", $kg, PDO::PARAM_STR);
            $stmt->bindParam(":remarks", $remarks, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
            echo '<strong>SUCCESS!</strong> The material was successfully updated with <strong>'. $kg .' kgs </strong>';
            return TRUE;
        } catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not update the material into the database. Please try again.<br>'. $e->getMessage(); 
            return FALSE;
        } 

    }
	
	/**
     * Checks and update a formula
     *
     * @return boolean  true if can update false if not
     */
    public function updateColorFormula()
    {
        $material = $kg = $remarks= "";
		
		$sql = "SELECT `material_id`
                FROM  `packing_bag_formulas`
                WHERE color = 1;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $material = $row['material_id'];
				
				$kg = trim($_POST["kgcolor"]);
				$kg = stripslashes($kg);
				$kg = htmlspecialchars($kg);


				$remarks = stripslashes($_POST["remarks"]);
				$remarks = htmlspecialchars($remarks);
				
				//DATE
        $date = date("Y-m-d");
        if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = $newDateString;
        }

				$sql = "UPDATE  `packing_bag_formulas`
						SET `to` = '". $date."', `actual` = 0, `remarks` = concat(`remarks`,' ". $remarks."') 
						WHERE `material_id` = '".$material ."' AND `actual` = 1;
						INSERT INTO `packing_bag_formulas`(`packing_bag_formula`,`material_id`, `kg`,`from`,`to`,`actual`,
						`remarks`, `color`) VALUES(NULL,:material, :kg, '". $date."',NULL,1, :remarks, 1);";
				try
				{   
					$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
					$stmt = $this->_db->prepare($sql);
					$stmt->bindParam(":material", $material, PDO::PARAM_INT);
					$stmt->bindParam(":kg", $kg, PDO::PARAM_STR);
					$stmt->bindParam(":remarks", $remarks, PDO::PARAM_STR);
					$stmt->execute();
					$stmt->closeCursor();
					echo '<strong>SUCCESS!</strong> The material was successfully updated with <strong>'. $kg .' kgs </strong>';
					return TRUE;
				} catch (PDOException $e) {
					echo '<strong>ERROR</strong> Could not update the material into the database. Please try again.<br>'. $e->getMessage(); 
					return FALSE;
				} 
				
            }
            $stmt->closeCursor();
            
        }  
        else
        {
            echo "Something went wrong. $db->errorInfo";
        }
    }
    
    
    /**
     * Checks and delete a formula
     *
     * @return boolean  true if can update false if not
     */
    public function deleteFormula()
    {
        $material = $remarks= "";
      
        $material = trim($_POST["material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
        
		
        $remarks = stripslashes($_POST["remarks"]);
        $remarks = htmlspecialchars($remarks);
		
		//DATE
        $date = date("Y-m-d");
        if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = $newDateString;
        }
        
        $sql = "UPDATE  `packing_bag_formulas`
                SET `to` = '". $date."', `actual` = 0, `remarks` = concat(`remarks`,' ". $remarks."') 
                WHERE `material_id` = '".$material ."' AND `actual` = 1;";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":material", $material, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            
            echo '<strong>SUCCESS!</strong> The material was successfully deleted from the formula.';
            return TRUE;
        } catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not delete the material from the database. Please try again.<br>'. $e->getMessage(); 
            return FALSE;
        } 

    }
     public function giveBags($x)
    {
        $bags = floor($x/25);
        $remainder = fmod($x, 25);
        $remainder = round($remainder,2);
        $answer = "";
        if($bags > 0)
        {
            $answer = $bags . " bags";
        }
        if($bags > 0 && $remainder >0)
        {
            $answer =  $answer . " + ";
        }
        if($remainder>0)
        {
            $answer = $answer. $remainder . " kg";
        }
        return $answer;
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

        $sql = "SELECT rollno, gross_weight, net_weight, thickness, color FROM `packing_rolls` WHERE ". $date ."  AND machine_id = ". $machine ." ORDER BY packing_rolls_id";

        if($shift != 0)
        {
            $sql = "SELECT rollno, gross_weight, net_weight, thickness, color FROM `packing_rolls` WHERE ". $date ." AND shift = ". $shift ." AND machine_id = ". $machine ."  ORDER BY packing_rolls_id";
        }
        
        $total1 = $total2 = 0;
                
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
           
            while($row = $stmt->fetch())
            {   
                $total1 = $total1 + $row['gross_weight'];
                $total2 = $total2 + $row['net_weight'];
                echo '<tr>
                        <td>'.  $row['rollno'] .'</td>                    
                        <td class="text-right">'. number_format($row['gross_weight'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['net_weight'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['thickness'],0,'.',',') .' µ</td>
                        <td >'. $this->giveColorName($row['color']) .'</td>
                        
                       </tr>';
            }
            echo '
              <tfoot>
                <tr class="active">
                  <th style="text-align:center">TOTAL</th>
                  <th class="text-right">'. number_format($total1,2,'.',',') .'</th>
                  <th class="text-right">'. number_format($total2,2,'.',',') .'</th>
                  <th></th>
				  <th></th>
                </tr>
              </tfoot>';
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
     * Loads the table of all the total production
     * Parameter= ID of the shift ALL DAY=0 MORNING=1 NIGHT=2
     */
    public function giveTotalProduction($shift)
    {
        $newDateString = date("Y-m-d");
        if(!empty($_POST['dateSearch']))
        {
           $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch']);
           $newDateString = $myDateTime->format('Y-m-d');
        }
        $date = "date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString ." 23:59:59'";

        $sql = "SELECT machine_name, SUM(gross_weight) as rollswt, COUNT(rollno) as rolls, SUM(gross_weight)-SUM(net_weight) as coneswt,
				SUM(net_weight) as actualwt
				FROM `packing_rolls`
				JOIN `machines` ON machines.machine_id = packing_rolls.machine_id
				WHERE ". $date ."
				GROUP BY machines.machine_id ORDER BY machines.machine_id;";

        if($shift != 0)
        {
            $sql = "SELECT machine_name, SUM(gross_weight) as rollswt, COUNT(rollno) as rolls, SUM(gross_weight)-SUM(net_weight) as coneswt, SUM(net_weight) as actualwt
				FROM `packing_rolls`
				JOIN `machines` ON machines.machine_id = packing_rolls.machine_id
				WHERE ". $date ." AND shift = ". $shift ."
				GROUP BY machines.machine_id ORDER BY machines.machine_id;";
        }
        
        $total1 = $total2 = $total3 = $total4 = 0;
                
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
           
            while($row = $stmt->fetch())
            {   
                $total1 = $total1 + $row['rollswt'];
                $total2 = $total2 + $row['rolls'];
                $total3 = $total3 + $row['coneswt'];
                $total4 = $total4 + $row['actualwt'];
                echo '<tr>
                        <td>'.  $row['machine_name'] .'</td>                    
                        <td class="text-right">'. number_format($row['rollswt'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['rolls'],0,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['coneswt'],1,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['actualwt'],2,'.',',') .'</td>
                        
                       </tr>';
            }
            echo '
              <tfoot>
                <tr class="active">
                  <th style="text-align:center">TOTAL</th>
                  <th class="text-right">'. number_format($total1,2,'.',',') .'</th>
                  <th class="text-right">'. number_format($total2,0,'.',',') .'</th>
                  <th class="text-right">'. number_format($total3,1,'.',',') .'</th>
                  <th class="text-right">'. number_format($total4,2,'.',',') .'</th>
                </tr>
              </tfoot>';
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
    * Checks and inserts the rolls WITHOUT BATCHES
    *
    * @return boolean true if can insert false if not
    */
    public function createRolls()
    {
        $CONE = 0;
        $sql = "SELECT `settings`.`value_setting`
                FROM  `settings` 
                WHERE machine_id=8 AND name_setting='cone';";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $CONE = $row['value_setting'];
            }
        }
        
        
        $color = $machine = $machinecode = $shift = $thickness = $size = "";
		
        $machine = trim($_POST["machine"]);
        $machine = stripslashes($machine);
        $machine = htmlspecialchars($machine);
		
		if($machine == 9)
		{
			$machinecode = "A";
		}
		else
		{
			$machinecode = "B";
		}
		
		$color = trim($_POST["color"]);
        $color = stripslashes($color);
        $color = htmlspecialchars($color);
		
        $shift = trim($_POST["shift"]);
        $shift = stripslashes($shift);
        $shift = htmlspecialchars($shift);
		
		$size = trim($_POST["size"]);
        $size = stripslashes($size);
        $size = htmlspecialchars($size);
		
		$thickness = trim($_POST["thickness"]);
        $thickness = stripslashes($thickness);
        $thickness = htmlspecialchars($thickness);
		
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
		
		
		// GETS ROLL NO 
	   $sql = "SELECT COUNT(DISTINCT(rollno)) as rollcount
				FROM `packing_rolls` WHERE substr(rollno, 7,1) = '". $machinecode."' AND date_roll BETWEEN '". $date ." 00:00:00' AND '". $date ." 23:59:59';";
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
		
		$rolls = "INSERT INTO `packing_rolls`(`packing_rolls_id`,`date_roll`,`rollno`,`shift`,`size`,`gross_weight`,`net_weight`,`thickness`,`user_id`,`machine_id`,`status_roll`,`dyne_test`,`waste_printing`, `color`)
		VALUES";
		foreach ($_POST as $k=>$v)
		{
			if (substr( $k, 0, 3 ) === "wt_" and !empty($v)){
				$count = $count + 1;
				$rollno = $newDateString."-".$machinecode."-".$count;
				$net = $v - $CONE;
				$totalnet = $totalnet + $net;
				$rolls = $rolls. " (NULL, '". $date."', '".$rollno."', ". $shift .", ". $size .", ". $v .", ". $net .", ". $thickness .", ". $_SESSION['Userid'] .", ". $machine .", 0, 1, NULL, ". $color.") ,";
			}
		}
		
		$update = "";
		if($color > 0)
			{
				$sql = "SELECT `packing_bag_formulas`.`material_id`, material_name, material_grade, `packing_bag_formulas`.`kg`,
						`packing_bag_formulas`.`kg`/25*".$totalnet." AS kgs_needed, stock_material_id, `materials`.`kgs_bag`, `stock_materials`.`bags`
					FROM `packing_bag_formulas`
					LEFT JOIN `stock_materials` ON `packing_bag_formulas`.material_id = stock_materials.material_id  AND machine_id = 11
					JOIN `materials` ON  `materials`.material_id = `packing_bag_formulas`.material_id
					WHERE `actual` = 1 AND `packing_bag_formulas`.color = ". $color .";";

					$update = "";
					if($stmt = $this->_db->prepare($sql))
					 {
						$stmt->execute();
						while($row = $stmt->fetch())
						{
							if(!is_null($row['stock_material_id']))
							{
								$KGSNEEDED = $row['kgs_needed'];
								$totalnet = $totalnet - $KGSNEEDED;
								$BAGSNEEDED = $KGSNEEDED / $row['kgs_bag'];
								$BAGSNEEDED = number_format($BAGSNEEDED ,4,'.','');
								//LANZA ERROR SI LAS BOLSAS ACTUALES SON MENORES A LAS QUE SE NECESITAN
								if($row['bags']<$BAGSNEEDED)
								{
									echo '<strong>ERROR</strong> The rolls were not added to the production. Because there is not enought material <strong>'. $row['material_name'] .' - '. $row['material_grade'] .'</strong> in stock. <br> There are <strong>'. $row['bags'] .'</strong> bags in stock, and you need <strong>'. $BAGSNEEDED .'</strong> bags. Please try again receiving the raw material or updating the formula.';
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
		}
		
		$sql = "SELECT `packing_bag_formulas`.`material_id`, material_name, material_grade, `packing_bag_formulas`.`kg`,
	`packing_bag_formulas`.`kg`/(SELECT sum(`packing_bag_formulas`.`kg`) FROM `packing_bag_formulas` WHERE actual =1 AND color = 0)*".$totalnet." AS kgs_needed, stock_material_id, `materials`.`kgs_bag`, `stock_materials`.`bags`
FROM `packing_bag_formulas`
LEFT JOIN `stock_materials` ON `packing_bag_formulas`.material_id = stock_materials.material_id  AND machine_id = 11
JOIN `materials` ON  `materials`.material_id = `packing_bag_formulas`.material_id
WHERE `actual` = 1 AND `packing_bag_formulas`.color = 0;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['stock_material_id']))
				{
					$KGSNEEDED = $row['kgs_needed'];
					$BAGSNEEDED = $KGSNEEDED / $row['kgs_bag'];
					$BAGSNEEDED = number_format($BAGSNEEDED ,4,'.','');
					//LANZA ERROR SI LAS BOLSAS ACTUALES SON MENORES A LAS QUE SE NECESITAN
					if($row['bags']<$BAGSNEEDED)
					{
						echo '<strong>ERROR</strong> The rolls were not added to the production. Because there is not enought material <strong>'. $row['material_name'] .' - '. $row['material_grade'] .'</strong> in stock. <br> There are <strong>'. $row['bags'] .'</strong> bags in stock, and you need <strong>'. $BAGSNEEDED .'</strong> bags. Please try again receiving the raw material or updating the formula.';
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
			
			
			$sql = substr($rolls,0,strlen($rolls)-2). "; ". $update;
			try {   
				$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
				$stmt->closeCursor();
				echo '<strong>SUCCESS!</strong> The rolls were successfully added to the database for the shift: <strong>'. $this->giveShiftname($shift) .'</strong>';
				return TRUE;
			}
			catch (PDOException $e) {
				if ($e->getCode() == 23000) {
					echo '<strong>ERROR</strong> The rolls numbers have already being register for the shift: <strong>'. $this->giveShiftname($shift) .'</strong>.<br>';
				} 
				else {
					echo '<strong>ERROR</strong> Could not insert the rolls into the database. Please try again.<br>'. $e->getMessage();
				}
				return FALSE;
			}
		}
    }
	
	public function createSacks()
    {
        
        
      $shift  = $employee1 = $size = $color = "";
		
        $shift = trim($_POST["shift"]);
        $shift = stripslashes($shift);
        $shift = htmlspecialchars($shift);
		
		
		$size = trim($_POST["size"]);
        $size = stripslashes($size);
        $size = htmlspecialchars($size);
				
		$customer = trim($_POST["customer"]);
        $customer = stripslashes($customer);
        $customer = htmlspecialchars($customer);
		if(empty($_POST['customer']))
        {
			$customer = 'NULL';
		}
		
		$color = trim($_POST["color"]);
        $color = stripslashes($color);
        $color = htmlspecialchars($color);
		
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
		
		$sacks = "INSERT INTO `packing_bags_sacks`(`packing_bags_sacks_id`,`date_sacks`,`shift`,`gross_weight`,`net_weight`,`user_id`,`employee_id`,`size`,`color`,`customer_id`) VALUES";
		foreach ($_POST as $k=>$v)
		{
			if (substr( $k, 0, 3 ) === "wt_" and !empty($v)){
				$net = $v;
				$totalnet = $totalnet + $net;
				$sacks = $sacks. " (NULL, '". $date."', ". $shift .", ". $v .", ". $net .", ". $_SESSION['Userid'] .", ". $employee1 .", ". $size .",". $color.", ". $customer.") ,";
			}
		}
		
		$update = "";
		
		
		$sql = "SELECT packing_rolls_id, SUM(`net_weight`) as net, SUM(used_weight) as used
				FROM `packing_rolls`
				WHERE `status_roll` = 0 AND `color` = ". $color .";";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['packing_rolls_id']))
				{
					$TOTAL = $row['net'] - $row['used'];
					if($TOTAL<$totalnet)
					{
						echo '<strong>ERROR</strong> The sacks were not added to the production. Because there is not enought rolls production in stock. <br> There are <strong>'. $TOTAL .'</strong> kgs in stock, and you need <strong>'. $totalnet .'</strong> kgs.  Please try again after submit the rolls for the packing bags - extruder.';
						return false;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The sack was not added to the production. Because there is not enought rolls production in stock. <br>  Please try again after submit the rolls for the packing bags - extruder.';
						return false;
				   }
            }
		}
		
		$sql = "SELECT packing_rolls_id, `net_weight`, used_weight
				FROM `packing_rolls`
				WHERE `status_roll` = 0 AND `color` = ". $color ."
				ORDER BY date_roll, packing_rolls_id
				LIMIT 100;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['packing_rolls_id']))
				{
					$TOTAL = $row['net_weight'] - $row['used_weight'];
					if(($TOTAL > $totalnet) and ($totalnet > 0))
					{
						if($totalnet + $row['used_weight'] == $row['net_weight'])
						{
							
							$update = $update . "
						UPDATE `packing_rolls` SET
                        `used_weight` = `used_weight`+". $totalnet .", `status_roll` = 1
						WHERE `packing_rolls_id` = ". $row['packing_rolls_id']."; ";
						}
						else
						{
							
							$update = $update . "
						UPDATE `packing_rolls` SET
                        `used_weight` = `used_weight`+". $totalnet .", `status_roll` = 1
						WHERE `packing_rolls_id` = ". $row['packing_rolls_id']."; ";
						}
						$totalnet = 0;
						$stmt->closeCursor();
					}
					else if(($TOTAL <= $totalnet) and ($totalnet > 0))
					{
						$update = $update . "
						UPDATE `packing_rolls` SET
                        `used_weight` = `used_weight`+". $TOTAL .", `status_roll` = 1
						WHERE `packing_rolls_id` = ". $row['packing_rolls_id']."; ";
						$totalnet = $totalnet - $TOTAL;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The sack was not added to the production. Because there is not enought rolls production in stock. <br>  Please try again after submit the rolls for the Packing Bags - extruder.';
						return false;
				   }
            }
		
			
			
			$sql = substr($sacks,0,strlen($sacks)-2). "; ". $update;
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
	
	public function giveSacksTable($shift)
    {
        $newDateString = date("Y-m-d");
        if(!empty($_POST['dateSearch']))
        {
           $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch']);
           $newDateString = $myDateTime->format('Y-m-d');
        }
        $date = "date_sacks BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString ." 23:59:59'";
		
		
		$sql = "SELECT  gross_weight, null as one, size, color, customer_name
FROM packing_bags_sacks
LEFT JOIN employees one ON one.employee_id = packing_bags_sacks.employee_id
LEFT JOIN customers ON packing_bags_sacks.customer_id = customers.customer_id
WHERE ". $date ."
ORDER BY `packing_bags_sacks_id`";

		if($shift != 0)
		{
			$sql = "SELECT  gross_weight, one.employee_name as one, size, color, customer_name
				FROM packing_bags_sacks
				LEFT JOIN employees one ON one.employee_id = packing_bags_sacks.employee_id
				LEFT JOIN customers ON packing_bags_sacks.customer_id = customers.customer_id
				WHERE ". $date ." AND shift = ". $shift ." ORDER BY `packing_bags_sacks_id`";
		}
		
		if($stmt = $this->_db->prepare($sql))
		{
				$stmt->execute();
				$entro = false;
				$i = 0;
				$total = 0;
				$output = "";
				while($row = $stmt->fetch())
				{ 
					$i++;
				   if(!$entro)
					{
						$output = $output . '<tr class="active">
								  <th style="text-align:center">Operator name</th>
								  <th class="text-right">'. $row['one'] .'</th>
								  <th></th>
								  <th></th>
								</tr>';
					   $entro = true;
					}
					$total += $row['gross_weight'];
					$SIZE = $row['size'];
					$COLOR = $this->giveColorName($row['color']);
					if(!is_null($row['customer_name']))
					{
						$COLOR = $COLOR . ' - ' .$row['customer_name'];
					}
					$output = $output .'<tr>
								  <td style="text-align:center">'. $i .'</th>
								  <td class="text-right">'. number_format($row['gross_weight'],2,'.',',') .'</th>
								  <td style="text-align:center">'. $this->giveSizeSacks($SIZE)  .'</th>
								  <td style="text-align:center">'.  $COLOR .'</th>
								</tr>';
					
				}
				echo '
								<tr class="active">
								  <th style="text-align:center">Total sacks wt.</th>
								  <th class="text-right">'. number_format($total,2,'.',',') .'</th>
								  <th style="text-align:center">Total No. of sacks.</th>
								  <th class="text-right">'. number_format($i,2,'.',',') .'</th>
								</tr>';
				echo $output;
		}
		
	 }
	 /**
     * Loads the table of all the consumption for the rolls
     * This function outputs <tr> tags with rolls
     * Parameter= ID of the shift ALL DAY=0 MORNING=1 NIGHT=2
     */
    public function giveConsumption($x)
    {
        $newDateString = date("Y-m-d");
        if(!empty($_POST['dateSearch']))
        {
           $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch']);
           $newDateString = $myDateTime->format('Y-m-d');
        }
        $date = "date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString ." 23:59:59'";

        $sql = "SELECT machine_name, SUM(net_weight) as actualwt, `packing_rolls`.color
				FROM `packing_rolls`
				JOIN `machines` ON machines.machine_id = packing_rolls.machine_id
				WHERE ". $date ."
				GROUP BY machines.machine_id, `packing_rolls`.color ORDER BY `packing_rolls`.color;";

        if($x != 0)
        {
            $sql = "SELECT machine_name, SUM(net_weight) as actualwt, `packing_rolls`.color
				FROM `packing_rolls`
				JOIN `machines` ON machines.machine_id = packing_rolls.machine_id
                WHERE ". $date ." AND shift = ". $x ."
				GROUP BY machines.machine_id, `packing_rolls`.color ORDER BY`packing_rolls`.color;";
        }       
		
		$color = 0;
        $a = $this->giveFormulaFor($newDateString, $color);
        if($stmt = $this->_db->prepare($sql))
        {
          $stmt->execute();
          $total=array();
            for($i = 0; $i<count($a)+1; ++$i) 
            { 
                 array_push($total,0);
            }
            echo '<thead>
                        <tr class="active">
                            <th class="text-center">Machine</th>
                            <th class="text-center">Total net weight</th>';
            for($i = 0; $i<count($a); ++$i) 
                { 
                    echo '<th class="text-center">'. $a[$i][0] .' - '. $a[$i][1] .'<br/>('. $a[$i][2] .' %)</th>                    ';    
                }
            echo '</thead>
                    <tbody>';
           while($row = $stmt->fetch())
            {
			   if($color != $row['color'])
			   {
				   $color = $row['color'];
				   echo '</tbody>';
					echo '
					  <tfoot>
						<tr class="active">
						  <th style="text-align:center">TOTAL KGS</th>';
					 for($i = 0; $i<count($total); ++$i) 
						{ 
							echo '<th class="text-right">'. number_format($total[$i],2,'.',',') .'</th>';
					 }
					echo '</tr>
					  </tfoot>';
						   echo '<table class="table table-bordered table-hover" cellspacing="0">';
        				$a = $this->giveFormulaFor($newDateString, $color);
						   $total=array();
					for($i = 0; $i<count($a)+1; ++$i) 
					{ 
						 array_push($total,0);
					}
					echo '<thead>
								<tr class="active">
									<th class="text-center">Machine</th>
									<th class="text-center">Total net weight</th>';
					for($i = 0; $i<count($a); ++$i) 
						{ 
							echo '<th class="text-center">'. $a[$i][0] .' - '. $a[$i][1] .'<br/>('. $a[$i][2] .' %)</th>                    ';    
						}
					echo '</thead>
							<tbody>';
			   }
                 echo '<tr>
                    <td>'.  $row['machine_name'] .'</td>
                    <td class="text-right">'.  number_format($row['actualwt'],2,'.',',') .'</td>'  ;
                    $total[0] = $total[0] + $row['actualwt'];
					for($i = 0; $i<count($a); ++$i) 
					{ 
						$x = $a[$i][2]/100*$row['actualwt'];
						$total[$i+1] = $total[$i+1]+$x;
						echo '<td class="text-right">'. number_format($x,2,'.',',') .'</td>';   
					}
               echo '</tr>';
            }
            echo '</tbody>';
            echo '
              <tfoot>
                <tr class="active">
                  <th style="text-align:center">TOTAL KGS</th>';
             for($i = 0; $i<count($total); ++$i) 
                { 
                    echo '<th class="text-right">'. number_format($total[$i],2,'.',',') .'</th>';
             }
            echo '</tr>
              </tfoot>';
            $stmt->closeCursor();
            
        }
        else
        {
            echo "<tr>
                    <td>Something went wrong</td>
                    <td>$db->errorInfo</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>";
        }
        
    }
	
	 public function giveFormulaFor($date, $color)
    {
        $a=array();
		 
		 $MASTERBATCH= 0;
		 if($color != 0)
		 {
			 $sql = "SELECT `packing_bag_formulas`.`material_id`, material_name, material_grade, `packing_bag_formulas`.`kg`
				FROM `packing_bag_formulas`
				LEFT  JOIN `materials` ON `packing_bag_formulas`.`material_id` = materials.material_id
				WHERE `from` <= '".$date."' AND (`to` IS NULL OR `to` > '".$date."')
				AND `packing_bag_formulas`.color = ". $color." 
				ORDER BY material_name, material_grade;";
				 if($stmt = $this->_db->prepare($sql))
				{
					$stmt->execute();
					while($row = $stmt->fetch())
					{
						$ID = $row['material_id'];
						$NAME = $row['material_name'];
						$GRADE = $row['material_grade'];
						$PERCENTAGE = $row['kg']/25 *100;
						$MASTERBATCH = $PERCENTAGE;
						$materialArray=array($NAME,$GRADE,number_format($PERCENTAGE,2,'.',''),$ID);
						array_push($a,$materialArray);

					}

					$stmt->closeCursor();

				}
				else
				{
					echo "Something went wrong. $db->errorInfo";
				}
		 }
		 
		 
		 $MIX = 100 - $MASTERBATCH;
		 
		 $sql = "SELECT `packing_bag_formulas`.`material_id`, material_name, material_grade, `packing_bag_formulas`.`kg`,
					`packing_bag_formulas`.`kg`/(SELECT sum(`packing_bag_formulas`.`kg`) FROM `packing_bag_formulas` WHERE `from` <= '".$date."' AND (`to` IS NULL OR `to` > '".$date."') AND color =0)*". $MIX ." AS percentage
				FROM `packing_bag_formulas`
				LEFT  JOIN `materials` ON `packing_bag_formulas`.`material_id` = materials.material_id
				WHERE `from` <= '".$date."' AND (`to` IS NULL OR `to` > '".$date."')
				AND `packing_bag_formulas`.color = 0 
				ORDER BY material_name, material_grade;";
        
		if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
				$NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
				$PERCENTAGE = $row['percentage'];
				
				$materialArray=array($NAME,$GRADE,number_format($PERCENTAGE,4,'.',''),$ID);
				array_push($a,$materialArray);
				
            }
			
            $stmt->closeCursor();
            
            return $a;
        }
        else
        {
            echo "Something went wrong. $db->errorInfo";
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
        
        $sql = "SELECT `packing_bag_formulas`.`material_id`, material_name, material_grade,bags,kgs_bag,
					`packing_bag_formulas`.`kg`/(SELECT sum(`packing_bag_formulas`.`kg`) FROM `packing_bag_formulas` WHERE actual =1)*".$total." AS kgs_needed, stock_material_id
				FROM `packing_bag_formulas`
				NATURAL JOIN `materials`
				LEFT JOIN `stock_materials` ON materials.material_id = stock_materials.material_id  AND machine_id = 11
				WHERE `actual` = 1;";
		
		$update = "";
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['stock_material_id']))
				{
					$KGSNEEDED = $row['kgs_needed'];
					$BAGSNEEDED = $KGSNEEDED / $row['kgs_bag'];
					$BAGSNEEDED = number_format($BAGSNEEDED ,2,'.','');
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
    public function createSacksWaste()
    {
        $shift = $total = "";
       
		
        $shift = trim($_POST["shift"]);
        $shift = stripslashes($shift);
        $shift = htmlspecialchars($shift);
		
        $cutting = trim($_POST["cutting"]);
        $cutting = stripslashes($cutting);
        $cutting = htmlspecialchars($cutting); 
		
        $printing = trim($_POST["printing"]);
        $printing = stripslashes($printing);
        $printing = htmlspecialchars($printing); 
		
		$totalnet = $cutting + $printing;
        
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
		
		
		$sql = "SELECT packing_rolls_id, SUM(`net_weight`) as net, SUM(used_weight) as used
				FROM `packing_rolls`
				WHERE `status_roll` = 0;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['packing_rolls_id']))
				{
					$TOTAL = $row['net'] - $row['used'];
					if($TOTAL<$totalnet)
					{
						echo '<strong>ERROR</strong> The waste were not added to the production. Because there is not enought rolls production in stock. <br> There are <strong>'. $TOTAL .'</strong> kgs in stock, and you need <strong>'. $totalnet .'</strong> kgs.  Please try again after submit the <strong>rolls for the packing bags - extruder</strong>.';
						return false;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The waste was not added to the production. Because there is not enought rolls production in stock. <br>  Please try again after submit the <strong>rolls for the packing bags -  extruder</strong>.';
						return false;
				   }
            }
		}
		
		$sql = "SELECT packing_rolls_id, `net_weight`, used_weight
				FROM `packing_rolls`
				WHERE `status_roll` = 0
				ORDER BY date_roll, packing_rolls_id
				LIMIT 100;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['packing_rolls_id']))
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
						UPDATE `packing_rolls` SET
                        `used_weight` = `used_weight`+". $totalnet .", `status_roll` = ". $status. "
						WHERE `packing_rolls_id` = ". $row['packing_rolls_id']."; ";
						$totalnet = 0;
						
						$stmt->closeCursor();
					}
					else if(($TOTAL <= $totalnet) and ($totalnet > 0))
					{
						$update = $update . "
						UPDATE `packing_rolls` SET
                        `used_weight` = `used_weight`+". $TOTAL .", `status_roll` = 1
						WHERE `packing_rolls_id` = ". $row['packing_rolls_id']."; ";
						$totalnet = $totalnet - $TOTAL;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The  waste was not added to the production. Because there is not enought rolls production in stock. <br>  Please try again after submit the <strong>rolls for the extruder</strong>.';
						return false;
				   }
            }
			
            $machine = 45;
            //INSERT THE WASTE 
            $sql = "INSERT INTO  `waste`(`waste_id`,`date_waste`,`shift`,`machine_id`,`waste`,`user_id`,`type`) VALUES (NULL,'". $date."', ". $shift .",". $machine .", ". $cutting .", ". $_SESSION['Userid'] .", 1), (NULL,'". $date."', ". $shift .",". $machine .", ". $printing .", ". $_SESSION['Userid'] .", 2);". $update;
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
     * Loads the table of all the waste in the multilayer section
     * This function outputs <tr> tags with the waste
     */
    public function giveWaste()
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
	`waste`.type = 1 AND (machines.machine_id = 9 OR machines.machine_id = 10)
ORDER BY `waste`.date_waste DESC, `waste`.`shift` DESC, `waste`.machine_id;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $DATE = $row['date_waste'];
                $USER = $row['username'];
                $SHIFT = $this->giveShiftname($row['shift']);
                
                echo '<tr>
                        <td>'. $DATE .'</td>
                        <td>'. $SHIFT .'</td>
                        <td>'. $row['machine_name'] .'</td>
                        <td>'. $USER .'</td>
                        <th class="text-right">'. number_format($row['film'],2,'.',',') .'</th>
                        <th class="text-right">'. number_format($row['block'],2,'.',',') .'</th>
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
	
	public function giveCuttingWaste()
    {
		$sql = "SELECT 
    machine_name,
    `waste`.`date_waste`,
    `waste`.`shift`,
    `waste`.`waste` as cutting, block_waste.waste as printing,
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
	`waste`.type = 1 AND waste.machine_id = 45 AND MONTH(`waste`.date_waste) >= MONTH(CURRENT_DATE())-1 AND YEAR(`waste`.date_waste) = YEAR(CURRENT_DATE())
ORDER BY `waste`.date_waste DESC, `waste`.`shift` DESC;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $DATE = $row['date_waste'];
                $USER = $row['username'];
                $SHIFT = $this->giveShiftname($row['shift']);
                $TOTAL = $row['cutting'] + $row['printing'];
                
                echo '<tr>
                        <td>'. $DATE .'</td>
                        <td>'. $SHIFT .'</td>
                        <td>'. $USER .'</td>
                        <td class="text-right">'. number_format($row['cutting'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['printing'],2,'.',',') .'</td>
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
     * Checks gives the shortfalls reasons
     *
     */
    public function giveShortFall()
    {
//        $sql = "SELECT machine_name,
//    `shortfalls`.`date_fall`,
//    `shortfalls`.`downtime` AS time_t,
//    `shortfalls`.`reason`,
//    `shortfalls`.`action_plan`
//FROM
//    `shortfalls`
//NATURAL JOIN `machines`
//WHERE
//    location_id = 8 
//        AND MONTH(date_fall) = MONTH(CURRENT_DATE())
//        AND YEAR(date_fall) = YEAR(CURRENT_DATE())
//ORDER BY date_fall;";
		$sql = "SELECT machine_name,
    `shortfalls`.`date_fall`,
    `shortfalls`.`downtime` AS time_t,
    `shortfalls`.`reason`,
    `shortfalls`.`action_plan`
FROM
    `shortfalls`
NATURAL JOIN `machines`
WHERE
    location_id = 8 AND machine_id <>45
ORDER BY date_fall;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
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
	
	public function giveShortFallSacks()
    {
//        $sql = "SELECT machine_name,
//    `shortfalls`.`date_fall`,
//    `shortfalls`.`downtime` AS time_t,
//    `shortfalls`.`reason`,
//    `shortfalls`.`action_plan`
//FROM
//    `shortfalls`
//NATURAL JOIN `machines`
//WHERE
//    location_id = 8 
//        AND MONTH(date_fall) = MONTH(CURRENT_DATE())
//        AND YEAR(date_fall) = YEAR(CURRENT_DATE())
//ORDER BY date_fall;";
		$sql = "SELECT machine_name,
    `shortfalls`.`date_fall`,
    `shortfalls`.`downtime` AS time_t,
    `shortfalls`.`reason`,
    `shortfalls`.`action_plan`
FROM
    `shortfalls`
NATURAL JOIN `machines`
WHERE machines.machine_id = 45
ORDER BY date_fall;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
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
     * Loads the table of all the rolls in the multilayer section
     * This function outputs <tr> tags with the rolls
     */
    public function giveRollsByThickness()
    {
        $a=array();
        $b=array();
        $c=array();
        $d=array();
        $sql = "SELECT thickness, count(packing_rolls_id) AS count_rolls, ROUND(SUM(gross_weight),2) AS totalgross, ROUND(SUM(net_weight),2) As totalnet, ROUND(SUM(net_weight)/count(packing_rolls_id),2) AS average_weight
                FROM  `packing_rolls`
                WHERE status_roll = 0 group by thickness;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $THICKNESS = $row['thickness'];
                $COUNT = $row['count_rolls'];
                $GROSS = $row['totalgross'];
                $NET = $row['totalnet'];
                $AVERAGE = $row['average_weight'];
                
                echo '<tr>
                        <td>'. $THICKNESS .' µ</td>
                        <td>'. $COUNT .'</td>
                        <td class="text-right">'. number_format($GROSS,1,'.',',') .'</td>
                        <td class="text-right">'. number_format($NET,1,'.',',') .'</td>
                        <td class="text-right">'. number_format($AVERAGE,1,'.',',') .'</td>
                    </tr>';
                
                $countArray=array("y" => $COUNT, "label" => $THICKNESS.' µ');
                array_push($a,$countArray);
                $weightArray=array("y" => $GROSS, "label" => $THICKNESS.' µ') ;
                array_push($b,$weightArray);
                $weightArray=array("y" => $NET, "label" => $THICKNESS.' µ') ;
                array_push($c,$weightArray);
                $averageArray=array("y" => $AVERAGE, "label" => $THICKNESS.' µ') ;
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
    public function giveRollsInfo()
    {
        $a=array();
        $b=array();
        $c=array();
        $d=array();
        $sql = "SELECT color, count(packing_rolls_id) AS count_rolls, ROUND(SUM(gross_weight),2) AS totalgross, ROUND(SUM(net_weight),2) As totalnet, ROUND(SUM(net_weight)/count(packing_rolls_id),2) AS average_weight
                FROM  `packing_rolls`
                WHERE status_roll = 0 group by color;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $COLOR = $row['color'];
                $COUNT = $row['count_rolls'];
                $GROSS = $row['totalgross'];
                $NET = $row['totalnet'];
                $AVERAGE = $row['average_weight'];
                
                echo '<tr>
                        <td>'. $this->giveColorName($COLOR) .'</td>
                        <td>'. $COUNT .'</td>
                        <td class="text-right">'. number_format($GROSS,1,'.',',') .'</td>
                        <td class="text-right">'. number_format($NET,1,'.',',') .'</td>
                        <td class="text-right">'. number_format($AVERAGE,1,'.',',') .'</td>
                    </tr>';
                
                $countArray=array("y" => $COUNT, "label" => $this->giveColorName($COLOR));
                array_push($a,$countArray);
                $weightArray=array("y" => $GROSS, "label" => $this->giveColorName($COLOR)) ;
                array_push($b,$weightArray);
                $weightArray=array("y" => $NET, "label" => $this->giveColorName($COLOR)) ;
                array_push($c,$weightArray);
                $averageArray=array("y" => $AVERAGE, "label" => $this->giveColorName($COLOR)) ;
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
    public function giveRollsStock()
    {
        $sql = "SELECT `packing_rolls`.`rollno`,`packing_rolls`.`size`, gross_weight, net_weight, thickness, color
                 FROM  `packing_rolls`
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
				$THICKNESS = $row['thickness'];
				$COLOR = $row['color'];
                
                echo '<tr>
                        <td>'. $ROLLNO .'</td>
                        <td>'. $this->giveSizename($SIZE) .'</td>
                        <td class="text-right">'. number_format($GROSS,1,'.',',') .'</td>
                        <td class="text-right">'. number_format($NET,1,'.',',') .'</td>
                        <td class="text-right">'. $THICKNESS .'µ</td>
                        <td>'. $this->giveColorName($COLOR) .'</td>
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
            $sizename = "10 x 2.5";
        }
		else if($size == 2)
        {
            $sizename = "9.8 x 2.5";
        }
        return $sizename;
    }
	 public function giveSizeSacks($size)
    {
        $sizename = "";
        if($size == 1)
        {
            $sizename = "5000 ml x 100";
        }
		else if($size == 2)
        {
            $sizename = "1000 ml x 100";
        }
		else if($size == 3)
        {
            $sizename = "6 1/2 Packing";
        }
        return $sizename;
    }
	
    public function giveColorName($color)
    {
        $colorname = "";
        if($color == 0)
        {
            $colorname = "Natural";
        }
		else if($color == 1)
        {
            $colorname = "White";
        }
        return $colorname;
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
        $b=array();
        $c=array();
        $d=array();
        $e=array();
        $f=array();
        $g=array();
		
		        
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
            
			
            $sql = "SELECT DATE_FORMAT(`date_roll`, '%b/%Y') as date, DATE_FORMAT(`date_roll`, '%m/%Y') as date2, machine_name, packing_rolls.machine_id,
    ROUND(SUM(net_weight), 2) AS actual,
    waste.wastekgs,
    COUNT(DISTINCT (DATE_FORMAT(`date_roll`, '%d/%m/%Y'))) AS days,
    target, target_waste, capacity
FROM
    packing_rolls
LEFT JOIN machines ON packing_rolls.machine_id = machines.machine_id
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%m/%Y') AS date,
            SUM(waste) AS wastekgs, machine_id
    FROM
        `waste`
	NATURAL JOIN machines
    WHERE machine_id = 9 OR machine_id = 10 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%m/%Y'), machine_id
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%m/%Y') AND waste.machine_id = packing_rolls.machine_id
	LEFT JOIN
    (SELECT 
        DATE_FORMAT(`target_orders`.`date`, '%m/%Y') AS date,
            SUM(target_order) AS target, machine_id
    FROM
        `target_orders`
    WHERE
        machine_id = 9 OR machine_id = 10
        AND date BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`target_orders`.`date`, '%m/%Y'), machine_id
    ORDER BY `target_orders`.`date`) targets ON targets.date = DATE_FORMAT(`date_roll`, '%m/%Y') AND targets.machine_id = packing_rolls.machine_id
	LEFT JOIN
	(
		SELECT AVG(`settings`.value_setting) AS target_waste, DATE_FORMAT(`settings`.`to`, '%m/%Y') AS `to` , DATE_FORMAT(`settings`.`from`, '%m/%Y') AS `from`
        FROM `settings`
        WHERE `settings`.machine_id = 8 AND `settings`.name_setting = 'waste'
		GROUP BY DATE_FORMAT(`settings`.from, '%m/%Y')
    )
    waste_target ON waste_target.`from` <= DATE_FORMAT(`date_roll`, '%m/%Y') AND (waste_target.`to` IS NULL OR waste_target.`to` >= DATE_FORMAT(`date_roll`, '%m/%Y'))
	LEFT JOIN
	(
		SELECT AVG(`settings`.value_setting) AS capacity, DATE_FORMAT(`settings`.`to`, '%m/%Y') AS `to` , DATE_FORMAT(`settings`.`from`, '%m/%Y') AS `from`
        FROM `settings`
        WHERE `settings`.machine_id = 8 AND `settings`.name_setting = 'target'
		GROUP BY DATE_FORMAT(`settings`.from, '%m/%Y')
    )
    capacity ON capacity.`from` <= DATE_FORMAT(`date_roll`, '%m/%Y') AND (capacity.`to` IS NULL OR capacity.`to` >= DATE_FORMAT(`date_roll`, '%m/%Y'))
WHERE
    date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_roll`, '%m/%Y'), packing_rolls.machine_id
ORDER BY `date_roll`;";
            
            
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
    DATE_FORMAT(`date_roll`, '%Y') AS date, machine_name, packing_rolls.machine_id,
    ROUND(SUM(net_weight), 2) AS actual,
    waste.wastekgs,
    COUNT(DISTINCT (DATE_FORMAT(`date_roll`, '%d/%m/%Y'))) AS days,
    target, target_waste, capacity
FROM
    packing_rolls
LEFT JOIN machines ON packing_rolls.machine_id = machines.machine_id
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%Y') AS date,
            SUM(waste) AS wastekgs, machine_id
    FROM
        `waste`
	NATURAL JOIN machines
    WHERE machine_id = 9 OR machine_id = 10 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%Y'), machine_id
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y') AND waste.machine_id = packing_rolls.machine_id
	LEFT JOIN
    (SELECT 
        DATE_FORMAT(`target_orders`.`date`, '%Y') AS date,
            SUM(target_order) AS target, machine_id
    FROM
        `target_orders`
    WHERE
        machine_id = 9 OR machine_id = 10
        AND date BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`target_orders`.`date`, '%Y'), machine_id
    ORDER BY `target_orders`.`date`) targets ON targets.date = DATE_FORMAT(`date_roll`, '%Y') AND targets.machine_id = packing_rolls.machine_id
	LEFT JOIN
	(
		SELECT AVG(`settings`.value_setting) AS target_waste, DATE_FORMAT(`settings`.`to`, '%Y') AS `to` , DATE_FORMAT(`settings`.`from`, '%Y') AS `from`
        FROM `settings`
        WHERE `settings`.machine_id = 8 AND `settings`.name_setting = 'waste'
		GROUP BY DATE_FORMAT(`settings`.from, '%Y')
    )
    waste_target ON waste_target.`from` <= DATE_FORMAT(`date_roll`, '%Y') AND (waste_target.`to` IS NULL OR waste_target.`to` >= DATE_FORMAT(`date_roll`, '%Y'))
	LEFT JOIN
	(
		SELECT AVG(`settings`.value_setting) AS capacity, DATE_FORMAT(`settings`.`to`, '%Y') AS `to` , DATE_FORMAT(`settings`.`from`, '%Y') AS `from`
        FROM `settings`
        WHERE `settings`.machine_id = 8 AND `settings`.name_setting = 'target'
		GROUP BY DATE_FORMAT(`settings`.from, '%Y')
    )
    capacity ON capacity.`from` <= DATE_FORMAT(`date_roll`, '%Y') AND (capacity.`to` IS NULL OR capacity.`to` >= DATE_FORMAT(`date_roll`, '%Y'))
WHERE
    date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_roll`, '%Y'), packing_rolls.machine_id
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
            
            $sql = "SELECT 
    DATE_FORMAT(`date_roll`, '%d/%m/%Y') AS date, machine_name, packing_rolls.machine_id,
    ROUND(SUM(net_weight), 2) AS actual,
    waste.wastekgs,
    COUNT(DISTINCT (DATE_FORMAT(`date_roll`, '%d/%m/%Y'))) AS days,
    target, target_waste, capacity
FROM
    packing_rolls
LEFT JOIN machines ON packing_rolls.machine_id = machines.machine_id
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%Y/%m/%d') AS date,
            SUM(waste) AS wastekgs, machine_id
    FROM
        `waste`
	NATURAL JOIN machines
    WHERE machine_id = 9 OR machine_id = 10 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d'), machine_id
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y/%m/%d') AND waste.machine_id = packing_rolls.machine_id
	LEFT JOIN
    (SELECT 
        DATE_FORMAT(`target_orders`.`date`, '%Y/%m/%d') AS date,
            SUM(target_order) AS target, machine_id
    FROM
        `target_orders`
    WHERE
        machine_id = 9 OR machine_id = 10
        AND date BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`target_orders`.`date`, '%Y/%m/%d'), machine_id
    ORDER BY `target_orders`.`date`) targets ON targets.date = DATE_FORMAT(`date_roll`, '%Y/%m/%d') AND targets.machine_id = packing_rolls.machine_id
	LEFT JOIN
	(
		SELECT `settings`.value_setting AS target_waste, `settings`.to, `settings`.from
        FROM `settings`
        WHERE `settings`.machine_id = 8 AND `settings`.name_setting = 'waste'
    )
    waste_target ON waste_target.`from` <= DATE_FORMAT(`date_roll`, '%Y/%m/%d') AND (waste_target.`to` IS NULL OR waste_target.`to` > DATE_FORMAT(`date_roll`, '%Y/%m/%d'))
	LEFT JOIN
	(
		SELECT `settings`.value_setting AS capacity, `settings`.to, `settings`.from
        FROM `settings`
        WHERE `settings`.machine_id = 8 AND `settings`.name_setting = 'target'
    )
    capacity ON capacity.`from` <= DATE_FORMAT(`date_roll`, '%Y/%m/%d') AND (capacity.`to` IS NULL OR capacity.`to` > DATE_FORMAT(`date_roll`, '%Y/%m/%d'))
WHERE
    date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_roll`, '%d/%m/%Y'), packing_rolls.machine_id
ORDER BY `date_roll`;";
            
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
                        <td class="text-right">'. number_format($CAPACITYROW,1,'.',',') .'</td>
                        <td class="text-right">'. number_format($TARGET,1,'.',',') .'</td>
                        <td class="text-right">'. number_format($ACTUAL,2,'.',',') .'</td>
                        <th class="text-right">'. number_format($EFF,2,'.',',') .'</th>
                        <td class="text-right">'. number_format($WASTEKG,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($TARGETWASTE,1,'.',',') .'</td>
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
				if($row['machine_id'] == 9)
				{
                	array_push($b,$entrie1);
				}
				else
				{
                	array_push($f,$entrie1);
				}
                array_push($c,$entrie2);
				if($row['machine_id'] == 9)
				{
                	array_push($d,$entrie3);
				}
				else
				{
                	array_push($g,$entrie3);
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
		name: "Orders Target",
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
        echo ' yValueFormatString: "#,###.0 Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($a as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if(!empty($_POST['searchBy']) and $_POST['searchBy']==3)
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
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Machine Capacity",
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
        echo ' yValueFormatString: "#,###.0 Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($e as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
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
        echo ']},{
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
            foreach($b as $key => $value) {
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
            foreach($b as $key => $value) {
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
            foreach($b as $key=>$value) {
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
            foreach($f as $key => $value) {
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
            foreach($f as $key => $value) {
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
            foreach($f as $key=>$value) {
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
            var chart = new CanvasJS.Chart("chartContainer2", {
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
            foreach($d as $key => $value) {
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
            foreach($d as $key => $value) {
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
            foreach($d as $key => $value) {
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
            foreach($g as $key => $value) {
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
            foreach($g as $key => $value) {
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
            foreach($g as $key => $value) {
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
        chart.render(); 
        </script>'; 
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
        
        $a=array();
        $b=array();
		$c=array();
        $d=array();
        
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
            
            $sql = " SELECT DATE_FORMAT(`date_roll`, '%b/%Y') as date, DATE_FORMAT(`date_roll`, '%m/%Y') as date2, ROUND(SUM(net_weight),2) as actual, COUNT(packing_rolls_id) as rolls, machine_name, packing_rolls.machine_id   
             FROM packing_rolls
             LEFT JOIN machines ON packing_rolls.machine_id = machines.machine_id
             WHERE `packing_rolls`.date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_roll`, '%b/%Y'), packing_rolls.machine_id 
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
            
            $sql = " SELECT DATE_FORMAT(`date_roll`, '%Y') as date, ROUND(SUM(net_weight),2) as actual, COUNT(packing_rolls_id) as rolls, machine_name, packing_rolls.machine_id   
             FROM packing_rolls
             LEFT JOIN machines ON packing_rolls.machine_id = machines.machine_id
             WHERE `packing_rolls`.date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_roll`, '%Y'), packing_rolls.machine_id 
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
            
            $sql = " SELECT DATE_FORMAT(`date_roll`, '%d/%m/%Y') as date, ROUND(SUM(net_weight),2) as actual, COUNT(packing_rolls_id) as rolls, machine_name, packing_rolls.machine_id 
             FROM packing_rolls
             LEFT JOIN machines ON packing_rolls.machine_id = machines.machine_id
             WHERE `packing_rolls`.date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_roll`, '%d/%m/%Y'), packing_rolls.machine_id
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
				if($row['machine_id'] == 9)
				{
                	array_push($a,$entrie);
				}
				else
				{
                	array_push($c,$entrie);
				}
				if($row['machine_id'] == 9)
				{
                	array_push($b,$entrie1);
				}
				else
				{
                	array_push($d,$entrie1);
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
            foreach($c as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
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
        echo'] }]});
        chart.render(); 
        </script>'; 
        echo '<script> 
            var chart = new CanvasJS.Chart("chartContainer2", {
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
            foreach($d as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($d as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($d as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
        echo'] }]});
        chart.render(); 
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
            
            $sql = " SELECT DATE_FORMAT(`date_waste`, '%b/%Y') as date, DATE_FORMAT(`date_waste`, '%m/%Y') as date2, SUM(`waste`.`waste`) AS total, machine_name, waste.machine_id
             FROM  `waste`
             LEFT JOIN machines ON waste.machine_id = machines.machine_id
             WHERE location_id=8 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
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
             WHERE location_id=8 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
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
             WHERE location_id=8 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
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
                        <td class="text-right">'. number_format($row['total'],1,'.',',') .'</td>
                    </tr>';
                $entrie = array( $row['date'], $row['total']);
                if($_POST['searchBy']==2)
                {
                    $entrie = array( $row['date2'], $row['total']);
                }
                if($row['machine_id'] == 9)
				{
                	array_push($a,$entrie);
				}
				else
				{
                	array_push($b,$entrie);
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
        chart.render(); 
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
		$materials = array();
		$dates = array();
		$sql2 = "SELECT distinct(`to`) as date 
				FROM `packing_bag_formulas`
				WHERE `packing_bag_formulas`.`from` <= '". $newDateString2 ."' AND `packing_bag_formulas`.`to` >= '". $newDateString ."' AND `packing_bag_formulas`.`to` <= '". $newDateString2 ."' 
				ORDER BY `to` DESC";
		if($stmt2 = $this->_db->prepare($sql2))
        {
            $stmt2->execute();
			$dateBefore = $newDateString2;
            while($row2 = $stmt2->fetch())
            {
				$dateArray=array($row2['date'],$dateBefore);
				array_push($dates,$dateArray);
				$dateBefore = date('Y-m-d', strtotime('-1 day', strtotime($row2['date'])));
			}
			if($newDateString <= $dateBefore )
			{
				$dateArray=array($newDateString,$dateBefore);
				array_push($dates,$dateArray);
			}
		}
		else
		{
			echo "Something went wrong. $db->errorInfo";
		}
		
		echo "<h2>Natural Packing Bags</h2>";
		for($z = count($dates)-1; $z>= 0; --$z) 
		{
			$materialsTable = $this->giveFormulaFor($dates[$z][1],0);
			$total=array();
			for($i = 0; $i<count($materialsTable)+1; ++$i) 
			{ 
				array_push($total,0);
			}
			if(count($materials)!=0)
			{
				for($i = 0; $i<count($materialsTable); ++$i) 
				{
					$entro = false;
					for($j = 0; $j<count($materials) and !$entro; ++$j) 
					{ 
						if($materials[$j][0][3] == $materialsTable[$i][3])
						{
							$entro = true;
						}
					}
					if(!$entro)
					{
						$material = array($materialsTable[$i],array());
						array_push($materials,$material);
					}
				}
			}
			else
			{
				for($i = 0; $i<count($materialsTable); ++$i) 
				{ 
					$material = array($materialsTable[$i],array());
					array_push($materials,$material);
				}
			}
			echo '<table class="table table-bordered table-hover" width="100%" cellspacing="0"  >
				  <thead><tr  class="active">';
			echo '<th class="text-center">From: '.$dates[$z][0].'<br/> To: '. $dates[$z][1].'</th>
				  <th class="text-center">Total Consumption <br/> (Rolls + Waste)</th>';
			for($i = 0; $i<count($materialsTable); ++$i) 
			{ 
				 echo '<th class="text-center">'. $materialsTable[$i][0] .' - '. $materialsTable[$i][1] .'<br/> ('.$materialsTable[$i][2].' %)</th>';    
			}
			echo '</tr></thead><tbody>';
			if($_POST['searchBy']==2)
			{  
				$sql = "SELECT DATE_FORMAT(`date_report`, '%b/%Y') as date, DATE_FORMAT(`date_report`, '%m/%Y') as date2, SUM(actual) as actual, SUM(wastekgs) as wastekgs
				FROM
				(
				SELECT `date_roll` as date_report, ROUND(SUM(net_weight),2) as actual, wastekgs
				 FROM packing_rolls
								LEFT JOIN
								(   SELECT DATE_FORMAT(`date_waste`, '%Y/%m/%d') as date, SUM(waste) as wastekgs
									FROM  `waste`
									WHERE (machine_id = 9 OR machine_id = 10) AND date_waste BETWEEN '". $dates[$z][0] ." 00:00:00' AND '". $dates[$z][1] ." 23:59:59'
									GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d') 
									ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y/%m/%d')
								 WHERE `packing_rolls`.date_roll BETWEEN '". $dates[$z][0] ." 00:00:00' AND '". $dates[$z][1] ." 23:59:59' AND `packing_rolls`.color = 0
								 GROUP BY DATE_FORMAT(`date_roll`, '%d/%m/%Y') 
								 ORDER BY `date_roll`) report
				GROUP BY DATE_FORMAT(`date_report`, '%m/%Y')";
			}
			else if($_POST['searchBy']==3)
			{  
				$sql = "SELECT DATE_FORMAT(`date_report`, '%Y') as date, DATE_FORMAT(`date_report`, '%m/%Y') as date2, SUM(actual) as actual, SUM(wastekgs) as wastekgs
				FROM
				(
				SELECT `date_roll` as date_report, ROUND(SUM(net_weight),2) as actual, wastekgs
				 FROM packing_rolls
								LEFT JOIN
								(   SELECT DATE_FORMAT(`date_waste`, '%Y/%m/%d') as date, SUM(waste) as wastekgs
									FROM  `waste`
									WHERE (machine_id = 9 OR machine_id = 10) AND date_waste BETWEEN '". $dates[$z][0] ." 00:00:00' AND '". $dates[$z][1] ." 23:59:59'
									GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d') 
									ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y/%m/%d')
								 WHERE `packing_rolls`.date_roll BETWEEN '". $dates[$z][0] ." 00:00:00' AND '". $dates[$z][1] ." 23:59:59' AND `packing_rolls`.color = 0
								 GROUP BY DATE_FORMAT(`date_roll`, '%d/%m/%Y') 
								 ORDER BY `date_roll`) report
				GROUP BY DATE_FORMAT(`date_report`, '%Y');";
			}
			else
			{
				$sql = " SELECT DATE_FORMAT(`date_roll`, '%d/%m/%Y') as date, ROUND(SUM(net_weight),2) as actual, wastekgs
				 FROM packing_rolls
				LEFT JOIN
				(   SELECT DATE_FORMAT(`date_waste`, '%Y/%m/%d') as date, SUM(waste) as wastekgs
					FROM  `waste`
					WHERE (machine_id = 9 OR machine_id = 10) AND date_waste BETWEEN '". $dates[$z][0] ." 00:00:00' AND '". $dates[$z][1] ." 23:59:59'
					GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d') 
					ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y/%m/%d')
				 WHERE `packing_rolls`.date_roll BETWEEN '". $dates[$z][0] ." 00:00:00' AND '". $dates[$z][1] ." 23:59:59' AND `packing_rolls`.color = 0
				 GROUP BY DATE_FORMAT(`date_roll`, '%d/%m/%Y') 
				 ORDER BY `date_roll`;";
			}
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
						
						array_push($a,$entrie);
						echo '<tr>
								<td class="text-right">'. $row['date'] .'</td>
								<td class="text-right">'. number_format($TOTAL,4,'.',',') .'</td>'  ;
								$total[0] = $total[0] + $TOTAL;
								for($i = 0; $i<count($materialsTable); ++$i) 
								{
									for($j = 0; $j<count($materials); ++$j) 
									{ 
										if($materials[$j][0][3] == $materialsTable[$i][3])
										{
											$x = $materialsTable[$i][2]/100*$TOTAL;
											$total[$i+1] = $total[$i+1] + $x;
											echo '<td class="text-right">'. number_format($x,4,'.',',') .'</td>'; 
											
											$entrie = array( $row['date'], $x);
											if($_POST['searchBy']==2)
											{
												$entrie = array( $row['date2'], $x);
											}
											array_push($materials[$j][1],$entrie);
										}
									}
								}
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
				for($i = 0; $i<count($total); ++$i) 
					{ 
						echo '<th class="text-right">'. number_format($total[$i],4,'.',',') .'</th>';
				 }	
				
				echo '</tr></tfoot></table>';
        }; 
		
		
		echo "<h2>White Packing Bags</h2>";
		for($z = count($dates)-1; $z>= 0; --$z) 
		{
			$materialsTable = $this->giveFormulaFor($dates[$z][1],1);
			$total=array();
			for($i = 0; $i<count($materialsTable)+1; ++$i) 
			{ 
				array_push($total,0);
			}
			if(count($materials)!=0)
			{
				for($i = 0; $i<count($materialsTable); ++$i) 
				{
					$entro = false;
					for($j = 0; $j<count($materials) and !$entro; ++$j) 
					{ 
						if($materials[$j][0][3] == $materialsTable[$i][3])
						{
							$entro = true;
						}
					}
					if(!$entro)
					{
						$material = array($materialsTable[$i],array());
						array_push($materials,$material);
					}
				}
			}
			else
			{
				for($i = 0; $i<count($materialsTable); ++$i) 
				{ 
					$material = array($materialsTable[$i],array());
					array_push($materials,$material);
				}
			}
			
			
			if($_POST['searchBy']==2)
			{  
				$sql = "SELECT DATE_FORMAT(`date_report`, '%b/%Y') as date, DATE_FORMAT(`date_report`, '%m/%Y') as date2, SUM(actual) as actual, SUM(wastekgs) as wastekgs
				FROM
				(
				SELECT `date_roll` as date_report, ROUND(SUM(net_weight),2) as actual, wastekgs
				 FROM packing_rolls
								LEFT JOIN
								(   SELECT DATE_FORMAT(`date_waste`, '%Y/%m/%d') as date, SUM(waste) as wastekgs
									FROM  `waste`
									WHERE (machine_id = 9 OR machine_id = 10) AND date_waste BETWEEN '". $dates[$z][0] ." 00:00:00' AND '". $dates[$z][1] ." 23:59:59'
									GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d') 
									ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y/%m/%d')
								 WHERE `packing_rolls`.date_roll BETWEEN '". $dates[$z][0] ." 00:00:00' AND '". $dates[$z][1] ." 23:59:59' AND `packing_rolls`.color = 1
								 GROUP BY DATE_FORMAT(`date_roll`, '%d/%m/%Y') 
								 ORDER BY `date_roll`) report
				GROUP BY DATE_FORMAT(`date_report`, '%m/%Y')";
			}
			else if($_POST['searchBy']==3)
			{  
				$sql = "SELECT DATE_FORMAT(`date_report`, '%Y') as date, DATE_FORMAT(`date_report`, '%m/%Y') as date2, SUM(actual) as actual, SUM(wastekgs) as wastekgs
				FROM
				(
				SELECT `date_roll` as date_report, ROUND(SUM(net_weight),2) as actual, wastekgs
				 FROM packing_rolls
								LEFT JOIN
								(   SELECT DATE_FORMAT(`date_waste`, '%Y/%m/%d') as date, SUM(waste) as wastekgs
									FROM  `waste`
									WHERE (machine_id = 9 OR machine_id = 10) AND date_waste BETWEEN '". $dates[$z][0] ." 00:00:00' AND '". $dates[$z][1] ." 23:59:59'
									GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d') 
									ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y/%m/%d')
								 WHERE `packing_rolls`.date_roll BETWEEN '". $dates[$z][0] ." 00:00:00' AND '". $dates[$z][1] ." 23:59:59' AND `packing_rolls`.color = 1
								 GROUP BY DATE_FORMAT(`date_roll`, '%d/%m/%Y') 
								 ORDER BY `date_roll`) report
				GROUP BY DATE_FORMAT(`date_report`, '%Y');";
			}
			else
			{
				$sql = " SELECT DATE_FORMAT(`date_roll`, '%d/%m/%Y') as date, ROUND(SUM(net_weight),2) as actual, wastekgs
				 FROM packing_rolls
				LEFT JOIN
				(   SELECT DATE_FORMAT(`date_waste`, '%Y/%m/%d') as date, SUM(waste) as wastekgs
					FROM  `waste`
					WHERE (machine_id = 9 OR machine_id = 10) AND date_waste BETWEEN '". $dates[$z][0] ." 00:00:00' AND '". $dates[$z][1] ." 23:59:59'
					GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d') 
					ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y/%m/%d')
				 WHERE `packing_rolls`.date_roll BETWEEN '". $dates[$z][0] ." 00:00:00' AND '". $dates[$z][1] ." 23:59:59' AND `packing_rolls`.color = 1
				 GROUP BY DATE_FORMAT(`date_roll`, '%d/%m/%Y') 
				 ORDER BY `date_roll`;";
			}
				if($stmt = $this->_db->prepare($sql))
				{
					$stmt->execute();
					echo '<table class="table table-bordered table-hover" width="100%" cellspacing="0"  >
							  <thead><tr  class="active">';
						echo '<th class="text-center">From: '.$dates[$z][0].'<br/> To: '. $dates[$z][1].'</th>
							  <th class="text-center">Total Consumption <br/> (Rolls + Waste)</th>';
						for($i = 0; $i<count($materialsTable); ++$i) 
						{ 
							 echo '<th class="text-center">'. $materialsTable[$i][0] .' - '. $materialsTable[$i][1] .'<br/> ('.$materialsTable[$i][2].' %)</th>';    
						}
						echo '</tr></thead><tbody>';
					while($row = $stmt->fetch())
					{
						
						$TOTAL = $row['actual'];
						$entrie = array( $row['date'], $TOTAL);
						if($_POST['searchBy']==2)
						{
							$entrie = array( $row['date2'], $TOTAL);
						}
						
						array_push($a,$entrie);
						echo '<tr>
								<td class="text-right">'. $row['date'] .'</td>
								<td class="text-right">'. number_format($TOTAL,4,'.',',') .'</td>'  ;
								$total[0] = $total[0] + $TOTAL;
								for($i = 0; $i<count($materialsTable); ++$i) 
								{
									for($j = 0; $j<count($materials); ++$j) 
									{ 
										if($materials[$j][0][3] == $materialsTable[$i][3])
										{
											$x = $materialsTable[$i][2]/100*$TOTAL;
											$total[$i+1] = $total[$i+1] + $x;
											echo '<td class="text-right">'. number_format($x,4,'.',',') .'</td>'; 
											
											$entrie = array( $row['date'], $x);
											if($_POST['searchBy']==2)
											{
												$entrie = array( $row['date2'], $x);
											}
											array_push($materials[$j][1],$entrie);
										}
									}
								}
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
				for($i = 0; $i<count($total); ++$i) 
					{ 
						echo '<th class="text-right">'. number_format($total[$i],4,'.',',') .'</th>';
				 }	
				
				echo '</tr></tfoot></table>';
        }; 
		
		
		echo "<h2>Total Consumption</h2>";
		
		echo '<table class="table table-bordered table-hover" width="100%" cellspacing="0"  >
				  <thead><tr  class="active">';
		echo '<th class="text-center">Total Consumption <br/> (Rolls + Waste)</th>';
		for($i = 0; $i<count($materials); ++$i) 
		{ 
			 echo '<th class="text-center">'. $materials[$i][0][0] .' - '. $materials[$i][0][1] .'<br/></th>';    
		}
		echo '</tr></thead><tbody>';
		$total = 0;
		for($j = 0; $j<count($a); ++$j) 
		{
			$total = $total + $a[$j][1];
		}
		echo '<td class="text-right">'. number_format($total,4,'.',',') .'</td>';
		for($i = 0; $i<count($materials); ++$i) 
		{ 
			$total = 0;
			for($j = 0; $j<count($materials[$i][1]); ++$j) 
			{
				$total = $total + $materials[$i][1][$j][1];
			}
			echo '<td class="text-right">'. number_format($total,4,'.',',') .'</td>';  
		}
		echo '</tbody></table>';
       
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
		      name: "Total",';
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
        for($i = 0; $i<count($materials); ++$i) 
        { 
            echo ',
                {
                     type: "line",
					connectNullData: false,
                      showInLegend: true,
                  name: "'. $materials[$i][0][0] .' - '. $materials[$i][0][1] .'",';
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
                foreach($materials[$i][1] as $value) {
                    $var = (int) explode("/", $value[0])[0]-1;
                    $x = $value[1];
                    echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $x.'},';
                }; 
            }
            else if($_POST['searchBy']==3)
            {   
                foreach($materials[$i][1] as $value) {
                    $x = $value[1];
                echo '{ x: new Date('. $value[0] . ',0), y: '. $x.'},';
                }; 
            }
            else
            {
                foreach($materials[$i][1] as $value) {
                    $var = (int) explode("/", $value[0])[1]-1;
                    $x =$value[1];
                    echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $x.'},';
                }; 
            }
            echo ']}';
        }
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
    public function reportReason()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Machine</th>';
        echo '<th>Downtime</th>';
        echo '<th>Reason for Short Fall</th>';
        echo '<th>Action Plan</th>';
        echo '</tr></thead><tbody>';   
        
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
					location_id=8 AND  `shortfalls`.`date_fall` BETWEEN '2018-01-01 00:00:00' AND '2018-01-31 23:59:59') AS total_time,
				GROUP_CONCAT(`shortfalls`.`reason`
					SEPARATOR '<br />') AS reason,
				GROUP_CONCAT(`shortfalls`.`action_plan`
					SEPARATOR '<br />') AS action, machine_name, shortfalls.machine_id
			FROM
				`shortfalls`			
             LEFT JOIN machines ON shortfalls.machine_id = machines.machine_id
			 WHERE
				location_id=8 AND `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
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
					location_id=8 AND  `shortfalls`.`date_fall` BETWEEN '2018-01-01 00:00:00' AND '2018-01-31 23:59:59') AS total_time,
				GROUP_CONCAT(`shortfalls`.`reason`
					SEPARATOR '<br />') AS reason,
				GROUP_CONCAT(`shortfalls`.`action_plan`
					SEPARATOR '<br />') AS action, machine_name, shortfalls.machine_id
			FROM
				`shortfalls`
					
             LEFT JOIN machines ON shortfalls.machine_id = machines.machine_id
			 WHERE
				location_id=8 AND `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
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
					location_id=8 AND  `shortfalls`.`date_fall` BETWEEN '2018-01-01 00:00:00' AND '2018-01-31 23:59:59') AS total_time,
				GROUP_CONCAT(`shortfalls`.`reason`
					SEPARATOR '<br />') AS reason,
				GROUP_CONCAT(`shortfalls`.`action_plan`
					SEPARATOR '<br />') AS action, machine_name, shortfalls.machine_id 
			FROM
				`shortfalls`
			LEFT JOIN machines ON shortfalls.machine_id = machines.machine_id
			WHERE
				location_id=8 AND `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
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
				
				if($row['machine_id'] == 9)
				{
                	array_push($a,$entrie);
				}
				else
				{
                	array_push($b,$entrie);
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
		if($TOTAL == 0)
		{
			$TOTAL = '00:00:00';
		}
		$time = explode(":", $TOTAL)[0];
		$days = floor($time/24);
        $hour = fmod($time, 24);
        $answer = "";
        if($days > 0)
        {
            $answer = $days . " days";
        }
        if($days > 0 && $hour >0)
        {
            $answer =  $answer . " ";
        }
        if($hour>0)
        {
            $answer = $answer. $hour . " hours ";
        }
		if(explode(":", $TOTAL)[1]>0)
		{
            $answer = $answer. " ". explode(":", $TOTAL)[1] . " minutes. ";
		}
		
        echo '</tbody><tfoot><tr  class="active"><th></th>
			<th style="text-align:right">Total</th>
			<th style="text-align:right">'.$TOTAL.'</th>
			<th style="text-align:right">'.$answer.'</th>
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
        chart.render(); 
        </script>'; 
    }

	/**
     * Loads the Efficiency Report in the multilayer section
     * This function outputs <tr> tags with the report
     */
    public function reportRawMaterial()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Opening</th>';
        echo '<th>Received</th>';
        echo '<th>Consumed Multilayer</th>';
        echo '<th>Consumed Packing</th>';
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
			<th style="text-align:right"></th>
			</tr></tfoot><tbody>'; 
		
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

			$sql= "SELECT DATE_FORMAT(`datereport`, '%b/%Y') AS datereport, opening, received, multilayer, packing, difference, closing
FROM
	(
    SELECT 
			datereport,	@a AS opening, received, multilayer, packing, difference,
			@a:=@a + received - multilayer - packing + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(received) as received, SUM(multilayer) as multilayer, SUM(packing) as packing, SUM(difference) as difference
			FROM
			(
				SELECT 
    DATE_FORMAT(`date_required`, '%Y-%m-%d') AS datereport,
    SUM(`stock_materials_transfers`.bags_receipt * materials.kgs_bag) AS received,
    ROUND(COALESCE(multilayer_production.net, 0) + COALESCE(multilayer_waste.waste, 0),
            2) AS multilayer,
    ROUND(COALESCE(packing_production.net, 0) + COALESCE(packing_waste.waste, 0),
            2) AS packing,
    ROUND(COALESCE(stock_balance.difference, 0),2) AS difference
FROM
    `stock_materials_transfers`
        JOIN
    `materials` ON `stock_materials_transfers`.material_id = materials.material_id
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        multilayer_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) multilayer_production ON DATE_FORMAT(multilayer_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production ON DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
	LEFT JOIN
    (
		SELECT 
        date_balance, SUM(difference * materials.kgs_bag) AS difference
		FROM
        stock_balance
        JOIN
		`materials` ON stock_balance.material_id = materials.material_id
        WHERE machine_id = 11
		GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')
    ) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
WHERE
    `stock_materials_transfers`.machine_to = 11
GROUP BY DATE_FORMAT(`stock_materials_transfers`.`date_required`,
        '%Y-%m-%d') 
UNION ALL SELECT 
    DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d') AS datereport,
    0 AS received,
    ROUND(SUM(net_weight) + COALESCE(multilayer_waste.waste, 0),
            2) AS multilayer,
    ROUND(COALESCE(packing_production.net, 0) + COALESCE(packing_waste.waste, 0),
            2) AS packing,
    ROUND(COALESCE(stock_balance.difference, 0),2) AS difference
FROM
    multilayer_rolls
        LEFT JOIN
    `stock_materials_transfers` ON machine_to = 11
        AND DATE_FORMAT(`date_required`, '%Y-%m-%d') = DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') = DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production ON DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d')
	LEFT JOIN
    (
		SELECT 
        date_balance, SUM(difference * materials.kgs_bag) AS difference
		FROM
        stock_balance
        JOIN
		`materials` ON stock_balance.material_id = materials.material_id
        WHERE machine_id = 11
		GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')
    ) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d')
WHERE
    `stock_materials_transfers`.machine_to IS NULL
GROUP BY DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d') 
UNION ALL SELECT 
    DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d') AS datereport,
    0 AS received,
    0 AS multilayer,
    ROUND(COALESCE(packing_production.net, 0) + COALESCE(packing_waste.waste, 0),
            2) AS packing,
    ROUND(COALESCE(stock_balance.difference, 0),2) AS difference
FROM
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production
        LEFT JOIN
    `stock_materials_transfers` ON machine_to = 11
        AND DATE_FORMAT(`date_required`, '%Y-%m-%d') = DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        multilayer_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) multilayer_production ON DATE_FORMAT(multilayer_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') = DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d')
	LEFT JOIN
    (
		SELECT 
        date_balance, SUM(difference * materials.kgs_bag) AS difference
		FROM
        stock_balance
        JOIN
		`materials` ON stock_balance.material_id = materials.material_id
        WHERE machine_id = 11
		GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')
    ) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(packing_production.`date_roll`, '%Y-%m-%d')
WHERE
    `stock_materials_transfers`.machine_to IS NULL
        AND multilayer_production.net IS NULL
GROUP BY DATE_FORMAT(packing_production.`date_roll`,
        '%Y-%m-%d') 
UNION ALL 
SELECT 
    DATE_FORMAT(`date_balance`,
            '%Y-%m-%d') AS datereport,
    0 AS received,
    0 AS multilayer,
	0 AS packing,
    ROUND(SUM(difference * materials.kgs_bag),2) AS difference
FROM
stock_balance
JOIN `materials` ON stock_balance.material_id = materials.material_id
LEFT JOIN
    stock_materials_transfers ON 
    stock_materials_transfers.machine_to = 11 
    AND  DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
    LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        multilayer_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) multilayer_production ON DATE_FORMAT(multilayer_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production ON DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste 
    ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') =  DATE_FORMAT(`date_balance`, '%Y-%m-%d')

WHERE machine_id =11 AND
    `stock_materials_transfers`.machine_to IS NULL
        AND multilayer_production.net IS NULL 
        AND packing_production.net IS NULL
GROUP BY DATE_FORMAT(`date_balance`,'%Y-%m-%d') 
UNION ALL SELECT 
    dateTable.selected_date AS datereport,
    0 AS received,
    0 AS multilayer,
    0 AS packing,
    0 AS difference
FROM
    (SELECT 
        ADDDATE('2018-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) selected_date
    FROM
        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) dateTable
        LEFT JOIN
    `stock_materials_transfers` ON machine_to = 11
        AND DATE_FORMAT(`date_required`, '%Y-%m-%d') = dateTable.selected_date
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        multilayer_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) multilayer_production ON DATE_FORMAT(multilayer_production.`date_roll`,
            '%Y-%m-%d') = dateTable.selected_date
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') = dateTable.selected_date
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production ON DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d') = dateTable.selected_date
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') = dateTable.selected_date
	LEFT JOIN
    (
		SELECT 
        date_balance, SUM(difference * materials.kgs_bag) AS difference
		FROM
        stock_balance
        JOIN
		`materials` ON stock_balance.material_id = materials.material_id
        WHERE machine_id = 11
		GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')
    ) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = dateTable.selected_date
WHERE
    selected_date <= '". $newDateString2 ."'
        AND `stock_materials_transfers`.machine_to IS NULL
        AND multilayer_production.net IS NULL
        AND packing_production.net IS NULL 
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
			
			$sql= "SELECT DATE_FORMAT(`datereport`, '%Y') AS datereport, opening, received, multilayer, packing, difference, closing
FROM
	(
    SELECT 
			datereport,	@a AS opening, received, multilayer, packing, difference,
			@a:=@a + received - multilayer - packing + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(received) as received, SUM(multilayer) as multilayer, SUM(packing) as packing, SUM(difference) as difference
			FROM
			(
				SELECT 
    DATE_FORMAT(`date_required`, '%Y-%m-%d') AS datereport,
    SUM(`stock_materials_transfers`.bags_receipt * materials.kgs_bag) AS received,
    ROUND(COALESCE(multilayer_production.net, 0) + COALESCE(multilayer_waste.waste, 0),
            2) AS multilayer,
    ROUND(COALESCE(packing_production.net, 0) + COALESCE(packing_waste.waste, 0),
            2) AS packing,
    ROUND(COALESCE(stock_balance.difference, 0),2) AS difference
FROM
    `stock_materials_transfers`
        JOIN
    `materials` ON `stock_materials_transfers`.material_id = materials.material_id
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        multilayer_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) multilayer_production ON DATE_FORMAT(multilayer_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production ON DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
	LEFT JOIN
    (
		SELECT 
        date_balance, SUM(difference * materials.kgs_bag) AS difference
		FROM
        stock_balance
        JOIN
		`materials` ON stock_balance.material_id = materials.material_id
        WHERE machine_id = 11
		GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')
    ) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
WHERE
    `stock_materials_transfers`.machine_to = 11
GROUP BY DATE_FORMAT(`stock_materials_transfers`.`date_required`,
        '%Y-%m-%d') 
UNION ALL SELECT 
    DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d') AS datereport,
    0 AS received,
    ROUND(SUM(net_weight) + COALESCE(multilayer_waste.waste, 0),
            2) AS multilayer,
    ROUND(COALESCE(packing_production.net, 0) + COALESCE(packing_waste.waste, 0),
            2) AS packing,
    ROUND(COALESCE(stock_balance.difference, 0),2) AS difference
FROM
    multilayer_rolls
        LEFT JOIN
    `stock_materials_transfers` ON machine_to = 11
        AND DATE_FORMAT(`date_required`, '%Y-%m-%d') = DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') = DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production ON DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d')
	LEFT JOIN
    (
		SELECT 
        date_balance, SUM(difference * materials.kgs_bag) AS difference
		FROM
        stock_balance
        JOIN
		`materials` ON stock_balance.material_id = materials.material_id
        WHERE machine_id = 11
		GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')
    ) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d')
WHERE
    `stock_materials_transfers`.machine_to IS NULL
GROUP BY DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d') 
UNION ALL SELECT 
    DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d') AS datereport,
    0 AS received,
    0 AS multilayer,
    ROUND(COALESCE(packing_production.net, 0) + COALESCE(packing_waste.waste, 0),
            2) AS packing,
    ROUND(COALESCE(stock_balance.difference, 0),2) AS difference
FROM
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production
        LEFT JOIN
    `stock_materials_transfers` ON machine_to = 11
        AND DATE_FORMAT(`date_required`, '%Y-%m-%d') = DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        multilayer_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) multilayer_production ON DATE_FORMAT(multilayer_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') = DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d')
	LEFT JOIN
    (
		SELECT 
        date_balance, SUM(difference * materials.kgs_bag) AS difference
		FROM
        stock_balance
        JOIN
		`materials` ON stock_balance.material_id = materials.material_id
        WHERE machine_id = 11
		GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')
    ) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(packing_production.`date_roll`, '%Y-%m-%d')
WHERE
    `stock_materials_transfers`.machine_to IS NULL
        AND multilayer_production.net IS NULL
GROUP BY DATE_FORMAT(packing_production.`date_roll`,
        '%Y-%m-%d') 
UNION ALL 
SELECT 
    DATE_FORMAT(`date_balance`,
            '%Y-%m-%d') AS datereport,
    0 AS received,
    0 AS multilayer,
	0 AS packing,
    ROUND(SUM(difference * materials.kgs_bag),2) AS difference
FROM
stock_balance
JOIN `materials` ON stock_balance.material_id = materials.material_id
LEFT JOIN
    stock_materials_transfers ON 
    stock_materials_transfers.machine_to = 11 
    AND  DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
    LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        multilayer_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) multilayer_production ON DATE_FORMAT(multilayer_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production ON DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste 
    ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') =  DATE_FORMAT(`date_balance`, '%Y-%m-%d')

WHERE machine_id =11 AND
    `stock_materials_transfers`.machine_to IS NULL
        AND multilayer_production.net IS NULL 
        AND packing_production.net IS NULL
GROUP BY DATE_FORMAT(`date_balance`,'%Y-%m-%d') 
UNION ALL SELECT 
    dateTable.selected_date AS datereport,
    0 AS received,
    0 AS multilayer,
    0 AS packing,
    0 AS difference
FROM
    (SELECT 
        ADDDATE('2018-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) selected_date
    FROM
        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) dateTable
        LEFT JOIN
    `stock_materials_transfers` ON machine_to = 11
        AND DATE_FORMAT(`date_required`, '%Y-%m-%d') = dateTable.selected_date
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        multilayer_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) multilayer_production ON DATE_FORMAT(multilayer_production.`date_roll`,
            '%Y-%m-%d') = dateTable.selected_date
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') = dateTable.selected_date
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production ON DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d') = dateTable.selected_date
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') = dateTable.selected_date
	LEFT JOIN
    (
		SELECT 
        date_balance, SUM(difference * materials.kgs_bag) AS difference
		FROM
        stock_balance
        JOIN
		`materials` ON stock_balance.material_id = materials.material_id
        WHERE machine_id = 11
		GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')
    ) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = dateTable.selected_date
WHERE
    selected_date <= '". $newDateString2 ."'
        AND `stock_materials_transfers`.machine_to IS NULL
        AND multilayer_production.net IS NULL
        AND packing_production.net IS NULL 
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
			
						$sql= "SELECT DATE_FORMAT(`datereport`, '%Y-%m-%d') AS datereport, opening, received, multilayer, packing, difference, closing
FROM
	(
    SELECT 
			datereport,	@a AS opening, received, multilayer, packing, difference,
			@a:=@a + received - multilayer - packing + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(received) as received, SUM(multilayer) as multilayer, SUM(packing) as packing, SUM(difference) as difference
			FROM
			(
				SELECT 
    DATE_FORMAT(`date_required`, '%Y-%m-%d') AS datereport,
    SUM(`stock_materials_transfers`.bags_receipt * materials.kgs_bag) AS received,
    ROUND(COALESCE(multilayer_production.net, 0) + COALESCE(multilayer_waste.waste, 0),
            2) AS multilayer,
    ROUND(COALESCE(packing_production.net, 0) + COALESCE(packing_waste.waste, 0),
            2) AS packing,
    ROUND(COALESCE(stock_balance.difference, 0),2) AS difference
FROM
    `stock_materials_transfers`
        JOIN
    `materials` ON `stock_materials_transfers`.material_id = materials.material_id
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        multilayer_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) multilayer_production ON DATE_FORMAT(multilayer_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production ON DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
	LEFT JOIN
    (
		SELECT 
        date_balance, SUM(difference * materials.kgs_bag) AS difference
		FROM
        stock_balance
        JOIN
		`materials` ON stock_balance.material_id = materials.material_id
        WHERE machine_id = 11
		GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')
    ) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
WHERE
    `stock_materials_transfers`.machine_to = 11
GROUP BY DATE_FORMAT(`stock_materials_transfers`.`date_required`,
        '%Y-%m-%d') 
UNION ALL SELECT 
    DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d') AS datereport,
    0 AS received,
    ROUND(SUM(net_weight) + COALESCE(multilayer_waste.waste, 0),
            2) AS multilayer,
    ROUND(COALESCE(packing_production.net, 0) + COALESCE(packing_waste.waste, 0),
            2) AS packing,
    ROUND(COALESCE(stock_balance.difference, 0),2) AS difference
FROM
    multilayer_rolls
        LEFT JOIN
    `stock_materials_transfers` ON machine_to = 11
        AND DATE_FORMAT(`date_required`, '%Y-%m-%d') = DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') = DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production ON DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d')
	LEFT JOIN
    (
		SELECT 
        date_balance, SUM(difference * materials.kgs_bag) AS difference
		FROM
        stock_balance
        JOIN
		`materials` ON stock_balance.material_id = materials.material_id
        WHERE machine_id = 11
		GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')
    ) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d')
WHERE
    `stock_materials_transfers`.machine_to IS NULL
GROUP BY DATE_FORMAT(multilayer_rolls.`date_roll`, '%Y-%m-%d') 
UNION ALL SELECT 
    DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d') AS datereport,
    0 AS received,
    0 AS multilayer,
    ROUND(COALESCE(packing_production.net, 0) + COALESCE(packing_waste.waste, 0),
            2) AS packing,
    ROUND(COALESCE(stock_balance.difference, 0),2) AS difference
FROM
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production
        LEFT JOIN
    `stock_materials_transfers` ON machine_to = 11
        AND DATE_FORMAT(`date_required`, '%Y-%m-%d') = DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        multilayer_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) multilayer_production ON DATE_FORMAT(multilayer_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') = DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d')
	LEFT JOIN
    (
		SELECT 
        date_balance, SUM(difference * materials.kgs_bag) AS difference
		FROM
        stock_balance
        JOIN
		`materials` ON stock_balance.material_id = materials.material_id
        WHERE machine_id = 11
		GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')
    ) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(packing_production.`date_roll`, '%Y-%m-%d')
WHERE
    `stock_materials_transfers`.machine_to IS NULL
        AND multilayer_production.net IS NULL
GROUP BY DATE_FORMAT(packing_production.`date_roll`,
        '%Y-%m-%d') 
UNION ALL 
SELECT 
    DATE_FORMAT(`date_balance`,
            '%Y-%m-%d') AS datereport,
    0 AS received,
    0 AS multilayer,
	0 AS packing,
    ROUND(SUM(difference * materials.kgs_bag),2) AS difference
FROM
stock_balance
JOIN `materials` ON stock_balance.material_id = materials.material_id
LEFT JOIN
    stock_materials_transfers ON 
    stock_materials_transfers.machine_to = 11 
    AND  DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
    LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        multilayer_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) multilayer_production ON DATE_FORMAT(multilayer_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production ON DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste 
    ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') =  DATE_FORMAT(`date_balance`, '%Y-%m-%d')

WHERE machine_id =11 AND
    `stock_materials_transfers`.machine_to IS NULL
        AND multilayer_production.net IS NULL 
        AND packing_production.net IS NULL
GROUP BY DATE_FORMAT(`date_balance`,'%Y-%m-%d') 
UNION ALL SELECT 
    dateTable.selected_date AS datereport,
    0 AS received,
    0 AS multilayer,
    0 AS packing,
    0 AS difference
FROM
    (SELECT 
        ADDDATE('2018-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) selected_date
    FROM
        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) dateTable
        LEFT JOIN
    `stock_materials_transfers` ON machine_to = 11
        AND DATE_FORMAT(`date_required`, '%Y-%m-%d') = dateTable.selected_date
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        multilayer_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) multilayer_production ON DATE_FORMAT(multilayer_production.`date_roll`,
            '%Y-%m-%d') = dateTable.selected_date
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') = dateTable.selected_date
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production ON DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d') = dateTable.selected_date
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') = dateTable.selected_date
	LEFT JOIN
    (
		SELECT 
        date_balance, SUM(difference * materials.kgs_bag) AS difference
		FROM
        stock_balance
        JOIN
		`materials` ON stock_balance.material_id = materials.material_id
        WHERE machine_id = 11
		GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')
    ) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = dateTable.selected_date
WHERE
    selected_date <= '". $newDateString2 ."'
        AND `stock_materials_transfers`.machine_to IS NULL
        AND multilayer_production.net IS NULL
        AND packing_production.net IS NULL 
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
				$ML = $row['multilayer'];
				$PK = $row['packing'];
				$CLOSING = $row['closing'];


				$DIFF = '<td class="text-right">'. number_format((float) $row['difference'],0,'.',',') .'</td>';
				if($row['difference'] != 0)
				{
					$DIFF = '<th class="text-right text-danger">'. number_format((float) $row['difference'],0,'.',',') .'</th>';
				}

				echo '<tr>
						<td class="text-right">'. $DATE .'</td>
						<td class="text-right">'. number_format((float) $OPENING,2,'.',',') .'</td>
						<td class="text-right">'. number_format((float) $RECEIVED,2,'.',',') .'</td>
						<td class="text-right">'. number_format((float) $ML,2,'.',',') .'</td>
						<td class="text-right">'. number_format((float) $PK,2,'.',',') .'</td>';
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
	
	
    public function reportByMaterial()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Material</th>';
        echo '<th>Opening</th>';
        echo '<th>Received</th>';
        echo '<th>Consumed Multilayer</th>';
        echo '<th>Consumed Packing</th>';
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
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			</tr></tfoot><tbody>'; 
		
		$sql = "SELECT multilayer_formulas.`material_id`, 0 as color
FROM multilayer_formulas
LEFT JOIN packing_bag_formulas ON multilayer_formulas.material_id = packing_bag_formulas.material_id
GROUP BY multilayer_formulas.material_id
UNION ALL 
SELECT packing_bag_formulas.`material_id`, packing_bag_formulas.color
FROM packing_bag_formulas
LEFT JOIN multilayer_formulas ON multilayer_formulas.material_id = packing_bag_formulas.material_id
WHERE multilayer_formulas.material_id IS NULL
GROUP BY packing_bag_formulas.material_id;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $material = $row['material_id'];
				$newDateString = date("Y-m-d");
        		$newDateString2 = date("Y-m-d");
				
				$query = "SELECT 
    DATE_FORMAT(`date_required`, '%Y-%m-%d') AS datereport,
    `stock_materials_transfers`.bags_receipt * materials.kgs_bag AS received, ROUND((COALESCE(multilayer_production.net,0) + COALESCE(multilayer_waste.waste,0))*(
    ((COALESCE(outer_l.kg,0)/(SELECT sum(`multilayer_formulas`.`kg`) FROM `multilayer_formulas` WHERE layer=1 AND `multilayer_formulas`.`from` <=  DATE_FORMAT(`date_required`, '%Y-%m-%d') AND (`multilayer_formulas`.`to` IS NULL OR `multilayer_formulas`.`to` > DATE_FORMAT(`date_required`, '%Y-%m-%d'))))*31.25)+
    ((COALESCE(middle_l.kg,0)/(SELECT sum(`multilayer_formulas`.`kg`) FROM `multilayer_formulas` WHERE layer=2 AND `multilayer_formulas`.`from` <=  DATE_FORMAT(`date_required`, '%Y-%m-%d') AND (`multilayer_formulas`.`to` IS NULL OR `multilayer_formulas`.`to` >  DATE_FORMAT(`date_required`, '%Y-%m-%d'))))*37.5)+
    ((COALESCE(inner_l.kg,0)/(SELECT sum(`multilayer_formulas`.`kg`) FROM `multilayer_formulas` WHERE layer=3 AND `multilayer_formulas`.`from` <=  DATE_FORMAT(`date_required`, '%Y-%m-%d') AND (`multilayer_formulas`.`to` IS NULL OR `multilayer_formulas`.`to` >  DATE_FORMAT(`date_required`, '%Y-%m-%d'))))*31.25))/100,4) AS 
    consumption_multilayer, 
    ROUND((COALESCE(packing_production.net,0) + COALESCE(packing_waste.waste,0))*(COALESCE(`packing_bag_formulas`.`kg`,0)/(SELECT sum(`packing_bag_formulas`.`kg`) FROM `packing_bag_formulas` WHERE `packing_bag_formulas`.color =0 AND `from` <= DATE_FORMAT(`date_required`, '%Y-%m-%d') AND (`to` IS NULL OR `to` > DATE_FORMAT(`date_required`, '%Y-%m-%d')))),4)
    AS consumption_packing,
    ROUND(COALESCE(stock_balance.difference, 0),2) AS difference
FROM
    `stock_materials_transfers`
JOIN
    `materials` ON `stock_materials_transfers`.material_id = materials.material_id
LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        multilayer_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) multilayer_production ON DATE_FORMAT(multilayer_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
 LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
LEFT JOIN `multilayer_formulas` outer_l ON outer_l.material_id = `stock_materials_transfers`.material_id
AND outer_l.layer=1 AND outer_l.`from` <=  DATE_FORMAT(`date_required`, '%Y-%m-%d') AND (outer_l.`to` IS NULL OR outer_l.`to` >  DATE_FORMAT(`date_required`, '%Y-%m-%d'))
LEFT JOIN `multilayer_formulas` middle_l ON middle_l.material_id = `stock_materials_transfers`.material_id
AND middle_l.layer=2 AND middle_l.`from` <=  DATE_FORMAT(`date_required`, '%Y-%m-%d') AND (middle_l.`to` IS NULL OR middle_l.`to` >  DATE_FORMAT(`date_required`, '%Y-%m-%d'))
LEFT JOIN `multilayer_formulas` inner_l ON inner_l.material_id = `stock_materials_transfers`.material_id
AND inner_l.layer=3 AND inner_l.`from` <=  DATE_FORMAT(`date_required`, '%Y-%m-%d') AND (inner_l.`to` IS NULL OR inner_l.`to` >  DATE_FORMAT(`date_required`, '%Y-%m-%d'))

LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production ON DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')

LEFT JOIN `packing_bag_formulas` ON `packing_bag_formulas`.material_id = `stock_materials_transfers`.material_id
AND `packing_bag_formulas`.`from` <=  DATE_FORMAT(`date_required`, '%Y-%m-%d') AND (`packing_bag_formulas`.`to` IS NULL OR `packing_bag_formulas`.`to` >  DATE_FORMAT(`date_required`, '%Y-%m-%d')) AND `packing_bag_formulas`.color =0


LEFT JOIN
    (
		SELECT 
        date_balance, SUM(difference * materials.kgs_bag) AS difference, stock_balance.material_id
		FROM
        stock_balance
        JOIN
		`materials` ON stock_balance.material_id = materials.material_id
        WHERE machine_id = 11 AND stock_balance.material_id=". $material ."
		GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')
    ) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d') AND stock_balance.material_id = `stock_materials_transfers`.material_id
WHERE `stock_materials_transfers`.machine_to = 11 AND `stock_materials_transfers`.material_id = ". $material ."
GROUP BY DATE_FORMAT(`stock_materials_transfers`.`date_required`,'%Y-%m-%d') 

UNION ALL

SELECT 
    DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d') AS datereport,
    0 AS received, ROUND((COALESCE(multilayer_production.net,0) + COALESCE(multilayer_waste.waste,0))*(
    ((COALESCE(outer_l.kg,0)/(SELECT sum(`multilayer_formulas`.`kg`) FROM `multilayer_formulas` WHERE layer=1 AND `multilayer_formulas`.`from` <=  DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d') AND (`multilayer_formulas`.`to` IS NULL OR `multilayer_formulas`.`to` > DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d'))))*31.25)+
    ((COALESCE(middle_l.kg,0)/(SELECT sum(`multilayer_formulas`.`kg`) FROM `multilayer_formulas` WHERE layer=2 AND `multilayer_formulas`.`from` <=  DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d') AND (`multilayer_formulas`.`to` IS NULL OR `multilayer_formulas`.`to` >  DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d'))))*37.5)+
    ((COALESCE(inner_l.kg,0)/(SELECT sum(`multilayer_formulas`.`kg`) FROM `multilayer_formulas` WHERE layer=3 AND `multilayer_formulas`.`from` <=  DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d') AND (`multilayer_formulas`.`to` IS NULL OR `multilayer_formulas`.`to` >  DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d'))))*31.25))/100,4) AS 
    consumption_multilayer, 
    ROUND((COALESCE(packing_production.net,0) + COALESCE(packing_waste.waste,0))*(COALESCE(`packing_bag_formulas`.`kg`,0)/(SELECT sum(`packing_bag_formulas`.`kg`) FROM `packing_bag_formulas` WHERE `packing_bag_formulas`.color =0 AND `from` <= DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d') AND (`to` IS NULL OR `to` > DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d')))),4)
    AS consumption_packing,
    ROUND(COALESCE(stock_balance.difference, 0),2) AS difference
FROM
(SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        multilayer_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) multilayer_production
LEFT JOIN
    `stock_materials_transfers` ON machine_to=11 AND `stock_materials_transfers`.material_id = ". $material ." AND DATE_FORMAT(multilayer_production.`date_roll`,'%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
 LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') = DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d')
            
LEFT JOIN `multilayer_formulas` outer_l ON outer_l.material_id = ". $material ." 
AND outer_l.layer=1 AND outer_l.`from` <=  DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d') AND (outer_l.`to` IS NULL OR outer_l.`to` >  DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d'))
LEFT JOIN `multilayer_formulas` middle_l ON middle_l.material_id = ". $material ."
AND middle_l.layer=2 AND middle_l.`from` <=  DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d') AND (middle_l.`to` IS NULL OR middle_l.`to` >  DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d'))
LEFT JOIN `multilayer_formulas` inner_l ON inner_l.material_id = ". $material ."
AND inner_l.layer=3 AND inner_l.`from` <=  DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d') AND (inner_l.`to` IS NULL OR inner_l.`to` >  DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d'))

LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production ON DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d')
LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d')

LEFT JOIN `packing_bag_formulas` ON `packing_bag_formulas`.material_id = ". $material ."
AND `packing_bag_formulas`.`from` <=  DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d') AND (`packing_bag_formulas`.`to` IS NULL OR `packing_bag_formulas`.`to` >  DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d')) AND `packing_bag_formulas`.color =0


LEFT JOIN
    (
		SELECT 
        date_balance, SUM(difference * materials.kgs_bag) AS difference, stock_balance.material_id
		FROM
        stock_balance
        JOIN
		`materials` ON stock_balance.material_id = materials.material_id
        WHERE machine_id = 11 AND stock_balance.material_id=". $material ."
		GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')
    ) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(multilayer_production.`date_roll`, '%Y-%m-%d') 
WHERE `stock_materials_transfers`.machine_to IS NULL
GROUP BY DATE_FORMAT(multilayer_production.`date_roll`,'%Y-%m-%d') 

UNION ALL


SELECT 
    DATE_FORMAT(packing_production.`date_roll`, '%Y-%m-%d') AS datereport,
    0 AS received, 0 AS consumption_multilayer, 
    ROUND((COALESCE(packing_production.net,0) + COALESCE(packing_waste.waste,0))*(COALESCE(`packing_bag_formulas`.`kg`,0)/(SELECT sum(`packing_bag_formulas`.`kg`) FROM `packing_bag_formulas` WHERE `packing_bag_formulas`.color =0 AND `from` <= DATE_FORMAT(packing_production.`date_roll`, '%Y-%m-%d') AND (`to` IS NULL OR `to` > DATE_FORMAT(packing_production.`date_roll`, '%Y-%m-%d')))),4)
    AS consumption_packing,
    ROUND(COALESCE(stock_balance.difference, 0),2) AS difference
FROM
(SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production

LEFT JOIN
    `stock_materials_transfers` ON machine_to=11 AND `stock_materials_transfers`.material_id = ". $material ." AND DATE_FORMAT(packing_production.`date_roll`,'%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
LEFT JOIN
(SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        multilayer_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) multilayer_production ON DATE_FORMAT(multilayer_production.`date_roll`,
            '%Y-%m-%d') = DATE_FORMAT(packing_production.`date_roll`, '%Y-%m-%d')
LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') = DATE_FORMAT(packing_production.`date_roll`, '%Y-%m-%d')
            
LEFT JOIN `multilayer_formulas` outer_l ON outer_l.material_id = ". $material ."
AND outer_l.layer=1 AND outer_l.`from` <=  DATE_FORMAT(packing_production.`date_roll`, '%Y-%m-%d') AND (outer_l.`to` IS NULL OR outer_l.`to` >  DATE_FORMAT(packing_production.`date_roll`, '%Y-%m-%d'))
LEFT JOIN `multilayer_formulas` middle_l ON middle_l.material_id = ". $material ."
AND middle_l.layer=2 AND middle_l.`from` <=  DATE_FORMAT(packing_production.`date_roll`, '%Y-%m-%d') AND (middle_l.`to` IS NULL OR middle_l.`to` >  DATE_FORMAT(packing_production.`date_roll`, '%Y-%m-%d'))
LEFT JOIN `multilayer_formulas` inner_l ON inner_l.material_id = ". $material ."
AND inner_l.layer=3 AND inner_l.`from` <=  DATE_FORMAT(packing_production.`date_roll`, '%Y-%m-%d') AND (inner_l.`to` IS NULL OR inner_l.`to` >  DATE_FORMAT(packing_production.`date_roll`, '%Y-%m-%d'))

LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(packing_production.`date_roll`, '%Y-%m-%d')

LEFT JOIN `packing_bag_formulas` ON `packing_bag_formulas`.material_id = ". $material ."
AND `packing_bag_formulas`.`from` <=  DATE_FORMAT(packing_production.`date_roll`, '%Y-%m-%d') AND (`packing_bag_formulas`.`to` IS NULL OR `packing_bag_formulas`.`to` >  DATE_FORMAT(packing_production.`date_roll`, '%Y-%m-%d')) AND `packing_bag_formulas`.color =0


LEFT JOIN
    (
		SELECT 
        date_balance, SUM(difference * materials.kgs_bag) AS difference, stock_balance.material_id
		FROM
        stock_balance
        JOIN
		`materials` ON stock_balance.material_id = materials.material_id
        WHERE machine_id = 11 AND stock_balance.material_id=". $material ."
		GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')
    ) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(packing_production.`date_roll`, '%Y-%m-%d')
WHERE `stock_materials_transfers`.machine_to IS NULL AND multilayer_production.net IS NULL
GROUP BY DATE_FORMAT(packing_production.`date_roll`,'%Y-%m-%d') 

UNION ALL

SELECT 
    DATE_FORMAT(stock_balance.date_balance, '%Y-%m-%d') AS datereport,
    0 AS received, 0 AS consumption_multilayer, 
	0 AS consumption_packing,
    ROUND(COALESCE(stock_balance.difference, 0),2) AS difference
FROM
(
		SELECT 
        date_balance, SUM(difference * materials.kgs_bag) AS difference, stock_balance.material_id
		FROM
        stock_balance
        JOIN
		`materials` ON stock_balance.material_id = materials.material_id
        WHERE machine_id = 11 AND stock_balance.material_id=". $material ."
		GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')
    ) stock_balance

LEFT JOIN
    `stock_materials_transfers` ON machine_to=11 AND DATE_FORMAT(stock_balance.date_balance,'%Y-%m-%d') = DATE_FORMAT(`date_required`, '%Y-%m-%d')
LEFT JOIN
(SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        multilayer_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) multilayer_production ON DATE_FORMAT(multilayer_production.date_roll,
            '%Y-%m-%d') = DATE_FORMAT(stock_balance.date_balance, '%Y-%m-%d')
LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') = DATE_FORMAT(stock_balance.date_balance, '%Y-%m-%d')
            
LEFT JOIN `multilayer_formulas` outer_l ON outer_l.material_id = ". $material ."
AND outer_l.layer=1 AND outer_l.`from` <=  DATE_FORMAT(stock_balance.date_balance, '%Y-%m-%d') AND (outer_l.`to` IS NULL OR outer_l.`to` >  DATE_FORMAT(stock_balance.date_balance, '%Y-%m-%d'))
LEFT JOIN `multilayer_formulas` middle_l ON middle_l.material_id = ". $material ."
AND middle_l.layer=2 AND middle_l.`from` <=  DATE_FORMAT(stock_balance.date_balance, '%Y-%m-%d') AND (middle_l.`to` IS NULL OR middle_l.`to` >  DATE_FORMAT(stock_balance.date_balance, '%Y-%m-%d'))
LEFT JOIN `multilayer_formulas` inner_l ON inner_l.material_id = ". $material ."
AND inner_l.layer=3 AND inner_l.`from` <=  DATE_FORMAT(stock_balance.date_balance, '%Y-%m-%d') AND (inner_l.`to` IS NULL OR inner_l.`to` >  DATE_FORMAT(stock_balance.date_balance, '%Y-%m-%d'))

LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(stock_balance.date_balance, '%Y-%m-%d')

LEFT JOIN `packing_bag_formulas` ON `packing_bag_formulas`.material_id = ". $material ."
AND `packing_bag_formulas`.`from` <=  DATE_FORMAT(stock_balance.date_balance, '%Y-%m-%d') AND (`packing_bag_formulas`.`to` IS NULL OR `packing_bag_formulas`.`to` >  DATE_FORMAT(stock_balance.date_balance, '%Y-%m-%d')) AND `packing_bag_formulas`.color =0


LEFT JOIN
	(SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production
     ON DATE_FORMAT(packing_production.date_roll, '%Y-%m-%d') = DATE_FORMAT(stock_balance.date_balance, '%Y-%m-%d')
WHERE `stock_materials_transfers`.machine_to IS NULL AND multilayer_production.net IS NULL AND packing_production.net IS NULL 
GROUP BY DATE_FORMAT(stock_balance.date_balance,'%Y-%m-%d') 

UNION ALL

SELECT dateTable.selected_date AS datereport,
    0 AS received, 0 AS consumption_multilayer, 
	0 AS consumption_packing,
    0 AS difference
FROM
    (SELECT 
        ADDDATE('2018-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) selected_date
    FROM
        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) dateTable

LEFT JOIN
    `stock_materials_transfers` ON machine_to=11 AND dateTable.selected_date = DATE_FORMAT(`date_required`, '%Y-%m-%d')
LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        multilayer_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) multilayer_production ON DATE_FORMAT(multilayer_production.`date_roll`,
            '%Y-%m-%d') = dateTable.selected_date
 LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 2
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) multilayer_waste ON DATE_FORMAT(multilayer_waste.`date_waste`,
            '%Y-%m-%d') =dateTable.selected_date
			
LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        packing_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) packing_production ON DATE_FORMAT(packing_production.`date_roll`,
            '%Y-%m-%d') = dateTable.selected_date
LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 9 OR machine_id = 10
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) packing_waste ON DATE_FORMAT(packing_waste.`date_waste`, '%Y-%m-%d') = dateTable.selected_date

LEFT JOIN `packing_bag_formulas` ON `packing_bag_formulas`.material_id = `stock_materials_transfers`.material_id
AND `packing_bag_formulas`.`from` <=  dateTable.selected_date AND (`packing_bag_formulas`.`to` IS NULL OR `packing_bag_formulas`.`to` >  dateTable.selected_date) AND `packing_bag_formulas`.color =0


LEFT JOIN
    (
		SELECT 
        date_balance, SUM(difference * materials.kgs_bag) AS difference, stock_balance.material_id
		FROM
        stock_balance
        JOIN
		`materials` ON stock_balance.material_id = materials.material_id
        WHERE machine_id = 11 AND stock_balance.material_id=". $material ."
		GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')
    ) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = dateTable.selected_date 
WHERE selected_date <= '". $newDateString2 ."'
AND `stock_materials_transfers`.machine_to IS NULL AND multilayer_production.net IS NULL AND packing_production.net IS NULL  AND stock_balance.difference IS NULL

ORDER BY datereport";
		
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

					$sql2= "SELECT DATE_FORMAT(`datereport`, '%b/%Y') AS datereport, material_name, material_grade, opening, received, consumption_multilayer, consumption_packing, difference, closing
FROM
	(
    SELECT 
			datereport, ".$material." as material_id,	@a AS opening, received, consumption_multilayer, consumption_packing, difference,
			@a:=@a + received - consumption_multilayer - consumption_packing  + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(received) as received, SUM(consumption_multilayer) as consumption_multilayer, SUM(consumption_packing) as consumption_packing,SUM(difference) as difference
			FROM
			(". $query . "
            ) movements GROUP BY DATE_FORMAT(datereport, '%m/%Y') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
NATURAL JOIN materials
WHERE datereport BETWEEN '". $newDateString ."' AND '". $newDateString2 ."'
ORDER BY report.datereport";

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

					$sql2= "SELECT DATE_FORMAT(`datereport`, '%Y') AS datereport, material_name, material_grade, opening, received, consumption_multilayer, consumption_packing, difference, closing
FROM
	(
    SELECT 
			datereport, ".$material." as material_id,	@a AS opening, received, consumption_multilayer, consumption_packing, difference,
			@a:=@a + received - consumption_multilayer - consumption_packing  + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(received) as received, SUM(consumption_multilayer) as consumption_multilayer, SUM(consumption_packing) as consumption_packing,SUM(difference) as difference
			FROM
			(
				". $query . "

            ) movements GROUP BY DATE_FORMAT(datereport, '%Y') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
NATURAL JOIN materials
WHERE datereport BETWEEN '". $newDateString ."' AND '". $newDateString2 ."'
ORDER BY report.datereport";

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

					$sql2= "SELECT DATE_FORMAT(`datereport`, '%Y/%m/%d') AS datereport, material_name, material_grade, opening, received, consumption_multilayer, consumption_packing, difference, closing
FROM
	(
    SELECT 
			datereport, ".$material." as material_id,	@a AS opening, received, consumption_multilayer, consumption_packing, difference,
			@a:=@a + received - consumption_multilayer - consumption_packing  + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(received) as received, SUM(consumption_multilayer) as consumption_multilayer, SUM(consumption_packing) as consumption_packing,SUM(difference) as difference
			FROM
			(". $query . "
            ) movements GROUP BY DATE_FORMAT(datereport, '%Y/%m/%d') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
NATURAL JOIN materials
WHERE datereport BETWEEN '". $newDateString ."' AND '". $newDateString2 ."'
ORDER BY report.datereport;";

				}
				echo '<script>alert("'. $sql2 .'");</script>';
				ini_set('max_execution_time', 300);
				if($stmt2 = $this->_db->prepare($sql2))
				{
					$stmt2->execute();
					while($row2 = $stmt2->fetch())
					{
        
						$DATE = $row2['datereport'];
						$MATERIAL = $row2['material_name'];
						$GRADE = $row2['material_grade'];
						$OPENING = $row2['opening'];
						$RECEIVED = $row2['received'];
						$ML = $row2['consumption_multilayer'];
						$PK = $row2['consumption_packing'];
						$CLOSING = $row2['closing'];
						
						$DIFF = '<td class="text-right">'. number_format((float) $row2['difference'],2,'.',',') .'</td>';
						if($row2['difference'] != 0)
						{
							$DIFF = '<th class="text-right text-danger">'. number_format((float) $row2['difference'],2,'.',',') .'</th>';
						}

						echo '<tr>
								<td class="text-right">'. $DATE .'</td>
                        		<td>'. $MATERIAL .' - '. $GRADE .'</td>
								<td class="text-right">'. number_format((float) $OPENING,2,'.',',') .'</td>
								<td class="text-right">'. number_format((float) $RECEIVED,2,'.',',') .'</td>
								<td class="text-right">'. number_format((float) $ML,2,'.',',') .'</td>
								<td class="text-right">'. number_format((float) $PK,2,'.',',') .'</td>';
						echo $DIFF;
						echo '
								<td class="text-right">'. number_format((float) $CLOSING,2,'.',',') .'</td>
							</tr>';
						}
						
						$stmt2->closeCursor();
					}
					else
					{
						echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
					}
				
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<tr><td>Something went wrong.</tr><td';  
        }
		
		
                  
           
    }

}



?>