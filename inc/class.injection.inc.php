<?php

/**
 * Handles user interactions within the injection section
 *
 * PHP version 5
 *
 * @author Natalia Montañez
 * @copyright 2017 Natalia Montañez
 *
 */
class Injection
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
				WHERE `injection` = 1 AND `material` = 1
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
	
	 public function productTypeDropdown()
    {
        $sql = "SELECT distinct(`materials`.`material_grade`) AS material_grade
FROM `materials`
WHERE `injection` = 1 AND `semifinished` = 1
ORDER BY `materials`.`material_grade`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                
                $GRADE = $row['material_grade'];
                echo  '<li><a id="'. $GRADE .'" onclick="selectType(\''. $GRADE .'\')">'. $GRADE .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	
	 public function masterBatchDropdown()
    {
        $sql = "SELECT `materials`.`material_id`,
                `materials`.`material_name`,
                `materials`.`material_grade`
                FROM `materials`
				WHERE `injection` = 1 AND `master_batch` = 1
                ORDER BY `materials`.`material_name`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                echo  '<li><a id="'. $NAME .'" onclick="selectMasterbatch(\''. $ID .'\',\''. $NAME .'\',\''. $GRADE .'\')"><b>'. $NAME .'</b>&nbsp - &nbsp'. $GRADE .'</a></li>'; 
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
				WHERE `injection` = 1 AND `consumables` = 1
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
	
	public function semifinishedDropdown()
    {
        $sql = "SELECT `materials`.`material_id`,`materials`.`material_name`,`materials`.`material_grade`
                FROM  `materials`
				WHERE `injection` = 1 AND `semifinished` = 1
                ORDER BY `materials`.`material_name`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                echo  '<li><a id="'. $NAME .'" onclick="selectMaterial(\''. $ID .'\',\''. $NAME .'\',\''. $GRADE .'\')">'. $NAME .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	
	public function giveFormula()
    {
        $sql = "SELECT `injection_formulas`.`injection_formula`,
    `injection_formulas`.`material_grade` AS product,
    material_name, materials.material_grade
FROM `injection_formulas`
JOIN `materials` ON  `materials`.material_id = `injection_formulas`.material_id
WHERE `actual` = 1;";
		
		if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
			 $total = 0;
            while($row = $stmt->fetch())
            {
				echo '<tr>
                        <td>'. $row['product'] .'</td>
                        <td><b>'. $row['material_name'] .'</b>  -  '. $row['material_grade'] .'</td>
                    </tr>';
				
            }
            $stmt->closeCursor();
            
            
        }
        else
        {
            echo "Something went wrong. $db->errorInfo";
        }
    }
	
	public function createFormula()
    {
        $type = $material = $remarks= "";
		
        $type = trim($_POST["type"]);
        $type = stripslashes($type);
        $type = htmlspecialchars($type);
		
        $material = trim($_POST["material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
		
        $remarks = stripslashes($_POST["remarks"]);
        $remarks = htmlspecialchars($remarks);
        
        $sql = "INSERT INTO `injection_formulas`(`injection_formula`,`material_grade`,`material_id`,`from`,`to`,`actual`,`remarks`)
		VALUES(NULL,:type,:material,CURRENT_DATE(),NULL,1, :remarks);";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":type", $type, PDO::PARAM_STR);
            $stmt->bindParam(":material", $material, PDO::PARAM_INT);
            $stmt->bindParam(":remarks", $remarks, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
            
            echo '<strong>SUCCESS!</strong> The material was successfully added to the formula for the product tyep: <strong>'. $type .' </strong>';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> The material is already in the formula.<br>';
            } else {
              echo '<strong>ERROR</strong> Could not insert the material into the database. Please try again.<br>'. $e->getMessage();
            }
            
            return FALSE;
        } 

    }
	
	public function deleteFormula()
    {
        $type = $material = $remarks= "";
		
        $type = trim($_POST["type"]);
        $type = stripslashes($type);
        $type = htmlspecialchars($type);
		
        $material = trim($_POST["material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
		
        $remarks = stripslashes($_POST["remarks"]);
        $remarks = htmlspecialchars($remarks);
        
        $sql = "UPDATE  `injection_formulas`
                SET `to` = CURRENT_DATE, `actual` = 0, `remarks` = concat(`remarks`,' ". $remarks."') 
                WHERE `material_id` = '".$material ."' AND `material_grade` = '".$type ."' AND `actual` = 1;";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":material", $material, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            
            echo '<strong>SUCCESS!</strong> The material was successfully deleted from the Product Type: '. $type .'.';
            return TRUE;
        } catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not delete the material from the database. Please try again.<br>'. $e->getMessage(); 
            return FALSE;
        } 

    }
	
	
    public function createProduction()
    {
              
       $machine = $shift = $product = $type = $cavities = $production = $waste = $wastepcs = $good = $consumed =  "";
		
        $machine = trim($_POST["machine"]);
        $machine = stripslashes($machine);
        $machine = htmlspecialchars($machine);
		
        $shift = trim($_POST["shift"]);
        $shift = stripslashes($shift);
        $shift = htmlspecialchars($shift);
	
		$product = trim($_POST["product"]);
        $product = stripslashes($product);
        $product = htmlspecialchars($product);
		
		$type = trim($_POST["type"]);
        $type = stripslashes($type);
        $type = htmlspecialchars($type);
		
		if($type == -1)
		{
			$type = 'NULL';
		}
		
		$cavities = trim($_POST["cavities"]);
        $cavities = stripslashes($cavities);
        $cavities = htmlspecialchars($cavities);
		
		$production = trim($_POST["pcs"]);
        $production = stripslashes($production);
        $production = htmlspecialchars($production);
		
		$waste = trim($_POST["waste"]);
        $waste = stripslashes($waste);
        $waste = htmlspecialchars($waste);
		
		$wastepcs = trim($_POST["wastepcs"]);
        $wastepcs = stripslashes($wastepcs);
        $wastepcs = htmlspecialchars($wastepcs);
		
		$consumed = trim($_POST["consumed"]);
        $consumed = stripslashes($consumed);
        $consumed = htmlspecialchars($consumed);
		
		$good = trim($_POST["good"]);
        $good = stripslashes($good);
        $good = htmlspecialchars($good);
		
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
		$sql = "SELECT stock_material_id, `stock_materials`.`bags`, kgs_bag, material_name, material_grade
FROM `stock_materials`
JOIN (SELECT `injection_formulas`.`material_id`
FROM `injection_formulas`
WHERE `material_grade` = (SELECT material_grade FROM materials WHERE material_id = ". $product .") AND actual = 1) rawmaterials
ON  rawmaterials.material_id = `stock_materials`.material_id
JOIN materials ON materials.material_id = `stock_materials`.material_id
WHERE `machine_id` = 6
GROUP BY stock_material_id;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
			$total = $consumed;
            while($row = $stmt->fetch())
            {
				if(!is_null($row['stock_material_id']))
				{
					$kgsStock = $row['bags'] * $row['kgs_bag'];
					if($kgsStock >= $total)
					{
						$BAGSNEEDED = $total / $row['kgs_bag'];
						$BAGSNEEDED = number_format($BAGSNEEDED ,4,'.','');
						$total = 0;
						$newbags = $row['bags']-$BAGSNEEDED;
						$update = $update . "UPDATE  `stock_materials` SET `bags` = ".$newbags." WHERE `stock_material_id` = ". $row['stock_material_id']. "; ";
            			$stmt->closeCursor();
					}
					else
					{
						$total = $total - $kgsStock;
						$update = $update . "UPDATE  `stock_materials` SET `bags` = 0 WHERE `stock_material_id` = ". $row['stock_material_id']. "; ";
					}
					
				}
            }
			if($total > 0)
		   {
				echo '<strong>ERROR</strong> The production was not added to the production. Because there is not enought raw material in stock. <br>  Please try again receiving the raw material.';
				return false;
		   }
			$consumed = $consumed - $waste;
			$sql = "INSERT INTO `injection_production`(`injection_production_id`,`date_production`,`shift`,`machine_id`,`material_id`,`type_id`,`cavities`,`produced_pcs`,`waste_pcs`,`good_pcs`,`net_weight`,`user_id`,`status_production`,`used_weight`,`date_change`) VALUES (NULL,'". $date."',". $shift .",". $machine .",". $product .",". $type .", ". $cavities .",". $production .",". $wastepcs .",". $good .",". $consumed .",". $_SESSION['Userid'] .",0,0.00,NULL); 
			INSERT INTO  `waste`(`waste_id`,`date_waste`,`shift`,`machine_id`,`waste`,`user_id`) VALUES (NULL,'". $date."', ". $shift .",". $machine .", ". $waste .", ". $_SESSION['Userid'] .");
                ". $update; 
			try
			{   
				$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
				$stmt->closeCursor();
                echo '<strong>SUCCESS!</strong> The production and waste were successfully added to the database for the shift: <strong>'. $this->giveShiftname($shift) .'</strong>';
                return TRUE;
            } 
            catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    echo '<strong>ERROR</strong> The production or waste have already being register for the shift: <strong>'. $this->giveShiftname($shift) .'</strong>.<br>';
                } 
                else {
                    echo '<strong>ERROR</strong> Could not insert the production into the database. Please try again.<br>'. $e->getMessage();
                }
                return FALSE;
            }
		}
    }
	
	public function giveProduction($shift)
    {
        $newDateString = date("Y-m-d");
        if(!empty($_POST['dateSearch']))
        {
           $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch']);
           $newDateString = $myDateTime->format('Y-m-d');
        }
        $date = "date_production BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString ." 23:59:59'";

        $sql = "SELECT  machine_name,
    materials.material_name, materials.material_grade, types.material_name as type, 
    `injection_production`.`cavities`,
    `injection_production`.`produced_pcs`,
    `injection_production`.`waste_pcs`,
    `injection_production`.`good_pcs`,
    `injection_production`.`net_weight`
FROM `injection_production`
JOIN machines ON machines.machine_id = `injection_production`.`machine_id`
JOIN materials ON materials.material_id = `injection_production`.`material_id`
LEFT JOIN materials types ON types.material_id = `injection_production`.`type_id`  
	WHERE ". $date ." ORDER BY material_name";

        if($shift != 0)
        {
            $sql = "SELECT machine_name,
    materials.material_name, materials.material_grade, types.material_name as type, 
    `injection_production`.`cavities`,
    `injection_production`.`produced_pcs`,
    `injection_production`.`waste_pcs`,
    `injection_production`.`good_pcs`,
    `injection_production`.`net_weight`
FROM `injection_production`
JOIN machines ON machines.machine_id = `injection_production`.`machine_id`
JOIN materials ON materials.material_id = `injection_production`.`material_id`
LEFT JOIN materials types ON types.material_id = `injection_production`.`type_id`  
	WHERE ". $date ." AND SHIFT = ". $shift ." ORDER BY material_name";
        }
        
                
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
           
            while($row = $stmt->fetch())
            {   
				$shots = $row['produced_pcs'] / $row['cavities'];
				$type = $row['type'];
				if(empty($row['type']))
				{
					$type = 'Transparent';
				}
                echo '<tr>
                        <td>'.  $row['material_name'] .' - '. $row['material_grade'].'</td>
                        <td>'.  $type .'</td>                        
                        <td>'.  $row['machine_name'] .'</td>                        
                        <td class="text-right">'. number_format($row['cavities'],0,'.',',') .'</td>
                        <td class="text-right">'. number_format($shots,0,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['produced_pcs'],0,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['waste_pcs'],0,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['good_pcs'],0,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['net_weight'],2,'.',',') .'</td>
                        
                       </tr>';
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
	
	public function giveWaste()
    {
		$sql = "SELECT 
    machine_name,
    `waste`.`date_waste`,
    `waste`.`shift`,
    `waste`.`waste`,
    username
FROM
    `waste`
        NATURAL JOIN
    users
        NATURAL JOIN
    machines
WHERE
	`location_id` = 6
ORDER BY `waste`.date_waste DESC,  `waste`.`shift`, `waste`.machine_id;";
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
                        <th class="text-right">'. number_format($row['waste'],2,'.',',') .'</th>
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
    
}


?>