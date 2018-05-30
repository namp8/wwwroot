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
                echo  '<li><a id="'. $GRADE .'" onclick="selectProduct(\''. $GRADE .'\')">'. $GRADE .'</a></li>'; 
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
	
	 public function colorsDropdown()
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
                echo  '<li><a id="'. $NAME .'" onclick="selectType(\''. $ID .'\',\''. $NAME .'\')">'. $NAME .'</a></li>'; 
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
	
	public function giveSettings()
    {
        $sql = "SELECT material_name, material_grade,
    `injection_sacks_formulas`.`cycle`,
    `injection_sacks_formulas`.`cavities`,
    `injection_sacks_formulas`.`target`,
    `injection_sacks_formulas`.`unit_weight`,
    `injection_sacks_formulas`.`pcs_sack`,
    `injection_sacks_formulas`.`sack_weight`
FROM `injection_sacks_formulas`
JOIN `materials` ON  `materials`.material_id = `injection_sacks_formulas`.`material_id`
WHERE `actual` = 1
ORDER BY material_grade;";
		
		if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				echo '<tr>
                        <td>'. $row['material_name'] .'</td>
                        <td class="text-right">'. number_format($row['cycle'],1,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['cavities'],0,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['target'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['unit_weight'],1,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['pcs_sack'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['sack_weight'],1,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['percentage'],1,'.',',')  .'</td>
                    </tr>';
				
            }
            $stmt->closeCursor();
            
            
        }
        else
        {
            echo "Something went wrong. $db->errorInfo";
        }
    }
	
	public function giveFormula()
    {
        $sql = "SELECT `injection_formulas`.`injection_formula`,
    `injection_formulas`.`material_grade` AS product, `injection_formulas`.type as type, percentage ,
    material_name, materials.material_grade
FROM `injection_formulas`
LEFT JOIN `materials` ON  `materials`.material_id = `injection_formulas`.material_id
WHERE `actual` = 1
ORDER BY `injection_formulas`.`material_grade`, `injection_formulas`.type;";
		
		if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
			 $total = 0;
            while($row = $stmt->fetch())
            {
				if($row['type'] == 0)
				{
					$type = 'Transparent';
				}
				else if($row['type'] == 1)
				{
					$type = 'Color';
				}
				else if($row['type'] == 2)
				{
					$type = 'Top';
				}
				else if($row['type'] == 3)
				{
					$type = 'Bottom';
				}
				$MATERIAL = '<b>Master Batch</b>';
				if(!empty($row['material_name']))
				{
					$MATERIAL = '<b>'. $row['material_name'] .'</b>  -  '. $row['material_grade']; 
				}
				echo '<tr>
                        <td>'. $row['product'] .'</td>
                        <td>'. $type .'</td>
                        <td>'. $MATERIAL .'</td>
                        <td class="text-right">'. number_format($row['percentage'],1,'.',',')  .'</td>
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
        $product = $type = $material = $remarks= "";
		
        $product = trim($_POST["product"]);
        $product = stripslashes($product);
        $product = htmlspecialchars($product);
		
        $type = trim($_POST["type"]);
        $type = stripslashes($type);
        $type = htmlspecialchars($type);
		
        $material = trim($_POST["material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
		
		if($material == -1)
		{
			$material = "NULL";
		}
		
        $percentage = trim($_POST["percentage"]);
        $percentage = stripslashes($percentage);
        $percentage = htmlspecialchars($percentage);
		
        $remarks = stripslashes($_POST["remarks"]);
        $remarks = htmlspecialchars($remarks);
        
        $sql = "INSERT INTO `injection_formulas`(`injection_formula`,`material_grade`,`material_id`,`type`,
`percentage`,`from`,`to`,`actual`,`remarks`) VALUES
(NULL,'". $product."',". $material.",". $type.", ". $percentage.", CURRENT_DATE(),NULL,1, '". $remarks."');";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->execute();
            $stmt->closeCursor();
            
            echo '<strong>SUCCESS!</strong> The material was successfully added to the formula for the product name: <strong>'. $product .' </strong>';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> The material is already in the formula.<br>';
            } else {
              echo '<strong>ERROR</strong> Could not insert the formula into the database. Please try again.<br>'. $e->getMessage();
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
        $product = $type = $material = $remarks= "";
		
        $product = trim($_POST["product"]);
        $product = stripslashes($product);
        $product = htmlspecialchars($product);
		
        $type = trim($_POST["type"]);
        $type = stripslashes($type);
        $type = htmlspecialchars($type);
		
        $material = trim($_POST["material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
		
		if($material == -1)
		{
			$material = "NULL";
		}
		
        $percentage = trim($_POST["percentage"]);
        $percentage = stripslashes($percentage);
        $percentage = htmlspecialchars($percentage);
		
        $remarks = stripslashes($_POST["remarks"]);
        $remarks = htmlspecialchars($remarks);
        
        $sql = "UPDATE  `injection_formulas`
                SET `to` = CURRENT_DATE, `actual` = 0, `remarks` = concat(`remarks`,' ". $remarks."') 
                WHERE `material_id` = ".$material ." AND `material_grade` = '".$product ."' AND `type` = '". $type."'AND `actual` = 1;
				INSERT INTO `injection_formulas`(`injection_formula`,`material_grade`,`material_id`,`type`,
`percentage`,`from`,`to`,`actual`,`remarks`) VALUES
(NULL,'". $product."',". $material.",". $type.", ". $percentage.", CURRENT_DATE(),NULL,1, '". $remarks."');";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->execute();
            $stmt->closeCursor();
            echo '<strong>SUCCESS!</strong> The material was successfully updated.';
            return TRUE;
        } catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not update the material into the database. Please try again.<br>'. $e->getMessage(); 
            return FALSE;
        } 

    }
	
	public function deleteFormula()
    {
        $product = $type = $material = $remarks= "";
		
        $product = trim($_POST["product"]);
        $product = stripslashes($product);
        $product = htmlspecialchars($product);
		
        $type = trim($_POST["type"]);
        $type = stripslashes($type);
        $type = htmlspecialchars($type);
		
        $material = trim($_POST["material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
		
		if($material == -1)
		{
			$material = "NULL";
		}
		
        $remarks = stripslashes($_POST["remarks"]);
        $remarks = htmlspecialchars($remarks);
		
        $sql = "UPDATE  `injection_formulas`
                SET `to` = CURRENT_DATE, `actual` = 0, `remarks` = concat(`remarks`,' ". $remarks."') 
                WHERE `material_id` = ".$material ." AND `material_grade` = '".$product ."' AND `type` = '". $type."'AND `actual` = 1;";
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
              
       $machine = $shift = $product = $type = $color = $cavities = $production = $waste = $wastepcs = $good = $consumed =  "";
		
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
		$color = 1;
		
		if($type == -1)
		{
			$type = 'NULL';
			$color = 0;
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
		$sql = "SELECT stock_material_id, `stock_materials`.`bags`, kgs_bag, material_name, material_grade, percentage
FROM `stock_materials`
JOIN (SELECT `injection_formulas`.`material_id`, percentage
FROM `injection_formulas`
WHERE `material_grade` = (SELECT material_grade FROM materials WHERE material_id = ". $product .") AND type =  ". $color ." AND actual = 1) rawmaterials
ON  rawmaterials.material_id = `stock_materials`.material_id
JOIN materials ON materials.material_id = `stock_materials`.material_id
WHERE `machine_id` = 6

UNION ALL

SELECT stock_material_id, `stock_materials`.`bags`, kgs_bag, material_name, material_grade, (SELECT percentage
FROM `injection_formulas`
WHERE `material_grade` = (SELECT material_grade FROM materials WHERE material_id = 128) AND type =  ". $color ." AND actual = 1 AND material_id is NULL) AS percentage
FROM `stock_materials`
JOIN materials ON materials.material_id = `stock_materials`.material_id
WHERE `machine_id` = 6 AND `stock_materials`.material_id = ". $type ."

GROUP BY stock_material_id;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['stock_material_id']))
				{
					$kgsStock = $row['bags'] * $row['kgs_bag'];
					$total = ($consumed + $waste) * $row['percentage'] / 100;
					$BAGSNEEDED = $total / $row['kgs_bag'];
					$BAGSNEEDED = number_format($BAGSNEEDED ,4,'.','');
					if($kgsStock >= $total)
					{
						$newbags = $row['bags']-$BAGSNEEDED;
						$update = $update . "UPDATE  `stock_materials` SET `bags` = ".$newbags." WHERE `stock_material_id` = ". $row['stock_material_id']. "; ";
            			$stmt->closeCursor();
					}
					else
					{
						echo '<strong>ERROR</strong> The production was not added to the production. Because there is not enought material <strong>'. $row['material_name'] .' - '. $row['material_grade'] .'</strong> in stock. <br> There are <strong>'. $row['bags'] .'</strong> bags in stock, and you need <strong>'. $BAGSNEEDED .'</strong> bags.';
						return false;
					}
					
				}
				else
				{
					echo '<strong>ERROR</strong> The production was not added to the production. Because there is not enought raw material in stock. <br>  Please try again receiving the raw material.';
					return false;
				}
            }
			$sql = "INSERT INTO `injection_production`(`injection_production_id`,`date_production`,`shift`,`machine_id`,`material_id`,`type_id`,`cavities`,`produced_pcs`,`waste_pcs`,`good_pcs`,`net_weight`,`user_id`,`status_production`,`used_weight`) VALUES (NULL,'". $date."',". $shift .",". $machine .",". $product .",". $type .", ". $cavities .",". $production .",". $wastepcs .",". $good .",". $consumed .",". $_SESSION['Userid'] .",0,0.00); 
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
	
	 /**
     * Loads the table of all the rolls in the multilayer section
     * This function outputs <tr> tags with the rolls
     */
    public function giveProductionInfo()
    {
        $a=array();
        $sql = "SELECT 
    materials.material_name as product,
    color.material_name as type,
    SUM(`injection_production`.`good_pcs`) as pcs
FROM `injection_production`
JOIN materials ON materials.material_id = injection_production.material_id
LEFT JOIN materials color ON color.material_id = type_id
WHERE status_production = 0
GROUP BY `injection_production`.material_id, type_id;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $PRODUCT = $row['product'];
                $TYPE = $row['type'];
				if(empty($row['type']))
				{
					$TYPE = 'Transparent';
				}
                $PCS = $row['pcs'];
                
                echo '<tr>
                        <td>'. $PRODUCT .'</td>
                        <td>'. $TYPE .'</td>
                        <td class="text-right">'. number_format($PCS,0,'.',',') .'</td>
                    </tr>';
                
                $countArray=array("y" => $PCS, "label" => $PRODUCT . ' - ' . $TYPE);
                array_push($a,$countArray);
            }
            $stmt->closeCursor();
            $x=array();
            array_push($x,$a);
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
	
	 public function giveProductionStock()
    {
        $sql = "SELECT  `injection_production`.`date_production`,
    materials.material_name as product,
    color.material_name as type,
    `injection_production`.`good_pcs` as pcs
FROM `injection_production`
JOIN materials ON materials.material_id = injection_production.material_id
LEFT JOIN materials color ON color.material_id = type_id
WHERE status_production = 0;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $PRODUCT = $row['product'];
                $TYPE = $row['type'];
				if(empty($row['type']))
				{
					$TYPE = 'Transparent';
				}
                $PCS = $row['pcs'];
                
                echo '<tr>
                        <td>'. $row['date_production'] .'</td>
                        <td>'. $PRODUCT .'</td>
                        <td>'. $TYPE .'</td>
                        <td class="text-right">'. number_format($PCS,0,'.',',') .'</td>
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
    
}


?>