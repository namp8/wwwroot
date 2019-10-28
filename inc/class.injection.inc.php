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
	
	 public function colorsDropdown($id)
    {
		if(is_null($id))
		{
			$id = '';
		}
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
                echo  '<li><a id="'. $NAME .'" onclick="selectType'.$id.'(\''. $ID .'\',\''. $NAME .'\')">'. $NAME .'</a></li>'; 
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
	
	public function semifinishedDropdown($id)
    {
		if(is_null($id))
		{
			$id = '';
		}
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
                echo  '<li><a id="'. $NAME .'" onclick="selectMaterial'.$id.'(\''. $ID .'\',\''. $NAME .'\',\''. $GRADE .'\')">'. $NAME .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	
	public function finishedDropdown()
    {
        $sql = "SELECT `materials`.`material_id`,`materials`.`material_name`,`materials`.`material_grade`
                FROM  `materials`
				WHERE `injection` = 1 AND `finished` = 1
                ORDER BY `materials`.`material_name`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                echo  '<li><a id="'. $NAME .'" onclick="selectFinished(\''. $ID .'\',\''. $NAME .'\',\''. $GRADE .'\')">'. $NAME .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	
	public function finishedFullDropdown()
    {
        $sql = "SELECT finishproduct, materials.material_name as finishname, 
					`injection_sacks`.`semifinished1`, semi1.material_name as semi1name,
					`injection_sacks`.`semifinished2`, semi2.material_name as semi2name,
					`injection_sacks`.`semifinished3`, semi3.material_name as semi3name,
					`injection_sacks`.`pieces`
				FROM `injection_sacks`
				JOIN materials ON materials.material_id = injection_sacks.finishproduct 
				JOIN materials semi1 ON semi1.material_id = injection_sacks.semifinished1
				LEFT JOIN materials semi2 ON semi2.material_id = injection_sacks.semifinished2 
				LEFT JOIN materials semi3 ON semi3.material_id = injection_sacks.semifinished3 
				WHERE actual = 1;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				$NAME = $row['finishname'];
                echo  '<li><a id="'. $NAME .'" onclick="selectFinished(\''. $row['finishproduct'] .'\',\''. $row['finishname'] .'\',\''. $row['semifinished1'] .'\',\''. $row['semi1name'] .'\',\''. $row['semifinished2'] .'\',\''. $row['semi2name'] .'\',\''. $row['semifinished3'] .'\',\''. $row['semi3name'] .'\',\''. $row['pieces'] .'\')">'. $NAME .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	public function giveSacksSettings()
    {
        $sql = "SELECT `injection_sacks`.`injection_sacks_id`,
    finish.material_name as finished, semi1.material_name as semifinished1 , semi2.material_name as semifinished2 , semi3.material_name as semifinished3 , 
    `injection_sacks`.`pieces`,`weight`
FROM `injection_sacks`
JOIN `materials` finish ON  finish.material_id = `injection_sacks`.`finishproduct`
JOIN `materials` semi1 ON  semi1.material_id = `injection_sacks`.`semifinished1`
LEFT JOIN `materials` semi2 ON  semi2.material_id = `injection_sacks`.`semifinished2`
LEFT JOIN `materials` semi3 ON  semi3.material_id = `injection_sacks`.`semifinished3`
WHERE `actual` = 1
ORDER BY finish.material_name, finish.material_grade;";
		
		if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				echo '<tr>
                        <td>'. $row['finished'] .'</td>
                        <td>'. $row['semifinished1'] .'</td>
                        <td>'. $row['semifinished2'] .'</td>
                        <td>'. $row['semifinished3'] .'</td>
                        <td class="text-right">'. number_format($row['pieces'],0,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['weight'],2,'.',',') .'</td>
                    </tr>';
				
            }
            $stmt->closeCursor();
            
            
        }
        else
        {
            echo "Something went wrong. $db->errorInfo";
        }
    }
	
	public function giveSettings()
    {
        $sql = "SELECT material_name, material_grade,
    `injection_sacks_formulas`.`cycle`,
    `injection_sacks_formulas`.`cavities`,
    `injection_sacks_formulas`.`target`,
    `injection_sacks_formulas`.`unit_weight`
FROM `injection_sacks_formulas`
JOIN `materials` ON  `materials`.material_id = `injection_sacks_formulas`.`material_id`
WHERE `actual` = 1
ORDER BY material_name, material_grade;";
		
		if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				echo '<tr>
                        <td>'. $row['material_name'] .'</td>
                        <td class="text-right">'. number_format($row['cycle'],1,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['cavities'],0,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['target'],0,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['unit_weight'],1,'.',',') .'</td>
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
	
	public function createSetting()
    {
        $material = $cycle = $cavities = $target = $part = $pcs = $sack = $remarks= "";
		
        $material = trim($_POST["product"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
		
		$cycle = trim($_POST["cycle"]);
        $cycle = stripslashes($cycle);
        $cycle = htmlspecialchars($cycle);
		
		$cavities = trim($_POST["cavities"]);
        $cavities = stripslashes($cavities);
        $cavities = htmlspecialchars($cavities);
		
		$target = trim($_POST["target"]);
        $target = stripslashes($target);
        $target = htmlspecialchars($target);
 
		$part = trim($_POST["part"]);
        $part = stripslashes($part);
        $part = htmlspecialchars($part);
		
        $remarks = stripslashes($_POST["remarks"]);
        $remarks = htmlspecialchars($remarks);
        
        $sql = "INSERT INTO `injection_sacks_formulas`
(`injection_sack_formula`,`material_id`,`cycle`,`cavities`,`target`,`unit_weight`,`from`,`to`,`actual`,`remarks`)
VALUES
(NULL,'". $material."','". $cycle."','". $cavities."', '". $target."', '". $part ."', CURRENT_DATE() ,NULL,1, '". $remarks."');";
		
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->execute();
            $stmt->closeCursor();
            
            echo '<strong>SUCCESS!</strong> The product was successfully added to the settings';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> The product is already in the settings.<br>';
            } else {
              echo '<strong>ERROR</strong> Could not insert the formula into the database. Please try again.<br>'. $e->getMessage();
            }
            
            return FALSE;
        } 

    }
	
	public function createSackSetting()
    {
        $finished = $semifinished1 = $semifinished2 = $semifinished3 = $pieces = $sack = $remarks= "";
		
        $finished = trim($_POST["finished"]);
        $finished = stripslashes($finished);
        $finished = htmlspecialchars($finished);
		
		$semifinished1 = trim($_POST["semifinished1"]);
        $semifinished1 = stripslashes($semifinished1);
        $semifinished1 = htmlspecialchars($semifinished1);
		
		$semifinished2 = trim($_POST["semifinished2"]);
        $semifinished2 = stripslashes($semifinished2);
        $semifinished2 = htmlspecialchars($semifinished2);
		
		if(empty($_POST['semifinished2']))
		{
			$semifinished2 = 'NULL';
		}
 
		$semifinished3 = trim($_POST["semifinished3"]);
        $semifinished3 = stripslashes($semifinished3);
        $semifinished3 = htmlspecialchars($semifinished3);	
		if(empty($_POST['semifinished3']))
		{
			$semifinished3 = 'NULL';
		}
		
        $pieces = trim($_POST["pieces"]);
        $pieces = stripslashes($pieces);
        $pieces = htmlspecialchars($pieces);
		
		$sack = trim($_POST["sack"]);
        $sack = stripslashes($sack);
        $sack = htmlspecialchars($sack);
        $remarks = stripslashes($_POST["remarks"]);
        $remarks = htmlspecialchars($remarks);
		
        $sql = "INSERT INTO `injection_sacks`
(`injection_sacks_id`,`finishproduct`,`semifinished1`,`semifinished2`,`semifinished3`,`pieces`,`weight`,`user_id`,`from`,`to`,`actual`,`remarks`)
VALUES
(NULL,'". $finished."','". $semifinished1."',". $semifinished2.", ". $semifinished3.", '". $pieces ."', '". $sack ."', ". $_SESSION['Userid'] .", CURRENT_DATE() ,NULL,1, '". $remarks."');";
		
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->execute();
            $stmt->closeCursor();
            
            echo '<strong>SUCCESS!</strong> The sack was successfully added to the settings';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> The sack is already in the settings.<br>';
            } else {
              echo '<strong>ERROR</strong> Could not insert the sack into the database. Please try again.<br>'. $e->getMessage();
            }
            
            return FALSE;
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
    SUM(`injection_production`.`produced_pcs`) as produced_pcs,
    SUM(`injection_production`.`waste_pcs`) as waste_pcs,
    SUM(`injection_production`.`good_pcs`) as good_pcs,
    SUM(`injection_production`.`net_weight`) as net_weight 
FROM `injection_production`
JOIN machines ON machines.machine_id = `injection_production`.`machine_id`
JOIN materials ON materials.material_id = `injection_production`.`material_id`
LEFT JOIN materials types ON types.material_id = `injection_production`.`type_id`  
	WHERE ". $date ." GROUP BY `injection_production`.`machine_id`, `injection_production`.`material_id`, `injection_production`.`type_id`  
    ORDER BY machine_name ";

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
	WHERE ". $date ." AND SHIFT = ". $shift ." ORDER BY machine_name ";
        }
        
		$total1 = $total2 = $total3 = $total4 = $total5 = 0;
                
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
				$total1 += $shots;
				$total2 += $row['produced_pcs'];
				$total3 += $row['waste_pcs'];
				$total4 += $row['good_pcs'];
				$total5 += $row['net_weight'];
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
            echo '
				<tfoot><tr class="active">
						<th></th>
						<th></th>     
						<th></th>                        
                        <th class="text-right">Total</th>
                        <th class="text-right">'. number_format($total1,0,'.',',') .'</th>
                        <th class="text-right">'. number_format($total2,0,'.',',') .'</th>
                        <th class="text-right">'. number_format($total3,0,'.',',') .'</th>
                        <th class="text-right">'. number_format($total4,0,'.',',') .'</th>
                        <th class="text-right">'. number_format($total5,2,'.',',') .'</th>
                        
                       </tr>
				</tfoot>';
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
	
	public function createSacksProduction()
    { 
		$shift = $finished = $type1 = $type2 =$type3 = $sacks = $cols = "";
		
        $shift = trim($_POST["shift"]);
        $shift = stripslashes($shift);
        $shift = htmlspecialchars($shift);
		
		$finished = trim($_POST["finished"]);
        $finished = stripslashes($finished);
        $finished = htmlspecialchars($finished);
	
		$cols = trim($_POST["cols"]);
        $cols = stripslashes($cols);
        $cols = htmlspecialchars($cols);
		
		$type1 = trim($_POST["type1"]);
        $type1 = stripslashes($type1);
        $type1 = htmlspecialchars($type1);
		if($type1 == -1)
		{
			$type1 = 'NULL';
			$type1sql = 'IS '. $type1;;
		}
		else
		{
			$type1sql = '= '. $type1;
		}
		
		$type2 = trim($_POST["type2"]);
        $type2 = stripslashes($type2);
        $type2 = htmlspecialchars($type2);
		if($type2 == -1)
		{
			$type2 = 'NULL';
			$type2sql = 'IS '. $type2;;
		}
		else
		{
			$type2sql = '= '. $type2;
		}
		
		$type3 = trim($_POST["type3"]);
        $type3 = stripslashes($type3);
        $type3 = htmlspecialchars($type3);
		if($type3 == -1)
		{
			$type3 = 'NULL';
			$type3sql = 'IS '. $type3;;
		}
		else
		{
			$type3sql = '= '. $type3;
		}
		
		$sacks = trim($_POST["sacks"]);
        $sacks = stripslashes($sacks);
        $sacks = htmlspecialchars($sacks);
		
		$pieces = trim($_POST["pieces"]);
        $pieces = stripslashes($pieces);
        $pieces = htmlspecialchars($pieces);
		
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
		
		$totalnet = $pieces * $sacks;
		
        $update = "";
		
		//SEMIFINISHED 1
		$sql = "SELECT injection_production_id, SUM(`good_pcs`) as net, SUM(used_weight) as used
FROM `injection_production`
WHERE `status_production` = 0 AND type_id ". $type1sql ." AND material_id = (SELECT semifinished1 FROM `injection_sacks` WHERE finishproduct = ". $finished .");";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['injection_production_id']))
				{
					$TOTAL = $row['net'] - $row['used'];
					if($TOTAL<$totalnet)
					{
						echo '<strong>ERROR</strong> The sacks were not added to the production. Because there is not enought production in stock. <br> There are <strong>'. $TOTAL .'</strong> pieces in stock for the <strong>semifinished #1</strong>, and you need <strong>'. $totalnet .'</strong> pieces.  Please try again after submit the <strong>production for injection.</strong>.';
						return false;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The sacks were not added to the production. Because there is not enought production in stock for the <strong>semifinished #1</strong>. <br>  Please try again after submit the <strong>production for injection.</strong>';
						return false;
				   }
            }
		}
		
		$sql = "SELECT `injection_production_id`, `good_pcs` as net, used_weight as used
				FROM `injection_production`
				WHERE `status_production` = 0 AND type_id ". $type1sql ." AND material_id = (SELECT semifinished1 FROM `injection_sacks` WHERE finishproduct = ". $finished .");
				ORDER BY date_production, injection_production_id
				LIMIT 100;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
			$pieces = $totalnet;
            while($row = $stmt->fetch() and $pieces > 0)
            {
				if(!is_null($row['injection_production_id']) )
				{
					$status = 0;
					$TOTAL = $row['net'] - $row['used'];
					if(($TOTAL > $pieces) and ($pieces > 0))
					{
						if($pieces+ $row['used'] == $row['net'])
						{
							$status = 1;
						}
						$update = $update . "
						UPDATE `injection_production` SET
                        `used_weight` = `used_weight`+". $pieces .", `status_production` = ". $status ."
						WHERE `injection_production_id` = ". $row['injection_production_id']."; ";
						$pieces = 0;
						
						$stmt->closeCursor();
					}
					else if(($TOTAL <= $pieces) and ($pieces > 0))
					{
						$update = $update . "
						UPDATE `injection_production` SET
                        `used_weight` = `used_weight`+". $TOTAL .", `status_production` = 1
						WHERE `injection_production_id` = ". $row['injection_production_id']."; ";
						$pieces = $pieces - $TOTAL;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The sacks were not added to the production. Because there is not enought production in stock for the <strong>semifinished #1</strong>. <br>  Please try again after submit the <strong>production for injection.</strong>';
						return false;
				   }
            }
		
			
		//SEMIFINISHED 2
			
		$sql = "SELECT semifinished2 FROM `injection_sacks` WHERE finishproduct = ". $finished .";";
		if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['semifinished2']))
				{
					$sql = "SELECT injection_production_id, SUM(`good_pcs`) as net, SUM(used_weight) as used
FROM `injection_production`
WHERE `status_production` = 0 AND type_id  ". $type2sql ." AND material_id = (SELECT semifinished2 FROM `injection_sacks` WHERE finishproduct = ". $finished .");";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['injection_production_id']))
				{
					$TOTAL = $row['net'] - $row['used'];
					if($TOTAL<$totalnet)
					{
						echo '<strong>ERROR</strong> The sacks were not added to the production. Because there is not enought production in stock. <br> There are <strong>'. $TOTAL .'</strong> pieces in stock for the <strong>semifinished #2</strong>, and you need <strong>'. $totalnet .'</strong> pieces.  Please try again after submit the <strong>production for injection.</strong>.';
						return false;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The sacks were not added to the production. Because there is not enought production in stock for the <strong>semifinished #2</strong>. <br>  Please try again after submit the <strong>production for injection.</strong>';
						return false;
				   }
            }
		}
		
		$sql = "SELECT `injection_production_id`, `good_pcs` as net, used_weight as used
				FROM `injection_production`
				WHERE `status_production` = 0 AND type_id  ". $type2sql ." AND material_id = (SELECT semifinished2 FROM `injection_sacks` WHERE finishproduct = ". $finished .");
				ORDER BY date_production, injection_production_id
				LIMIT 100;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
			$pieces = $totalnet;
            while($row = $stmt->fetch() and $pieces > 0)
            {
				if(!is_null($row['injection_production_id']) )
				{
					$status = 0;
					$TOTAL = $row['net'] - $row['used'];
					if(($TOTAL > $pieces) and ($pieces > 0))
					{
						if($pieces+ $row['used'] == $row['net'])
						{
							$status = 1;
						}
						$update = $update . "
						UPDATE `injection_production` SET
                        `used_weight` = `used_weight`+". $pieces .", `status_production` = ". $status ."
						WHERE `injection_production_id` = ". $row['injection_production_id']."; ";
						$pieces = 0;
						
						$stmt->closeCursor();
					}
					else if(($TOTAL <= $pieces) and ($pieces > 0))
					{
						$update = $update . "
						UPDATE `injection_production` SET
                        `used_weight` = `used_weight`+". $TOTAL .", `status_production` = 1
						WHERE `injection_production_id` = ". $row['injection_production_id']."; ";
						$pieces = $pieces - $TOTAL;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The sacks were not added to the production. Because there is not enought production in stock for the <strong>semifinished #2</strong>. <br>  Please try again after submit the <strong>production for injection.</strong>';
						return false;
				   }
            }
		}
				}
			}
		}
			
		
		//SEMIFINISHED 3
			
		$sql = "SELECT semifinished3 FROM `injection_sacks` WHERE finishproduct = ". $finished .";";
		if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['semifinished3']))
				{
					$sql = "SELECT injection_production_id, SUM(`good_pcs`) as net, SUM(used_weight) as used
FROM `injection_production`
WHERE `status_production` = 0 AND type_id  ". $type3sql ." AND material_id = (SELECT semifinished3 FROM `injection_sacks` WHERE finishproduct = ". $finished .");";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['injection_production_id']))
				{
					$TOTAL = $row['net'] - $row['used'];
					if($TOTAL<$totalnet)
					{
						echo '<strong>ERROR</strong> The sacks were not added to the production. Because there is not enought production in stock. <br> There are <strong>'. $TOTAL .'</strong> pieces in stock for the <strong>semifinished #3</strong>, and you need <strong>'. $totalnet .'</strong> pieces.  Please try again after submit the <strong>production for injection.</strong>.';
						return false;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The sacks were not added to the production. Because there is not enought production in stock for the <strong>semifinished #3</strong>. <br>  Please try again after submit the <strong>production for injection.</strong>';
						return false;
				   }
            }
		}
		
		$sql = "SELECT `injection_production_id`, `good_pcs` as net, used_weight as used
				FROM `injection_production`
				WHERE `status_production` = 0 AND type_id  ". $type3sql ." AND material_id = (SELECT semifinished3 FROM `injection_sacks` WHERE finishproduct = ". $finished .");
				ORDER BY date_production, injection_production_id
				LIMIT 100;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
			$pieces = $totalnet;
            while($row = $stmt->fetch() and $pieces > 0)
            {
				if(!is_null($row['injection_production_id']) )
				{
					$status = 0;
					$TOTAL = $row['net'] - $row['used'];
					if(($TOTAL > $pieces) and ($pieces > 0))
					{
						if($pieces+ $row['used'] == $row['net'])
						{
							$status = 1;
						}
						$update = $update . "
						UPDATE `injection_production` SET
                        `used_weight` = `used_weight`+". $pieces .", `status_production` = ". $status ."
						WHERE `injection_production_id` = ". $row['injection_production_id']."; ";
						$pieces = 0;
						
						$stmt->closeCursor();
					}
					else if(($TOTAL <= $pieces) and ($pieces > 0))
					{
						$update = $update . "
						UPDATE `injection_production` SET
                        `used_weight` = `used_weight`+". $TOTAL .", `status_production` = 1
						WHERE `injection_production_id` = ". $row['injection_production_id']."; ";
						$pieces = $pieces - $TOTAL;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The sacks were not added to the production. Because there is not enought production in stock for the <strong>semifinished #3</strong>. <br>  Please try again after submit the <strong>production for injection.</strong>';
						return false;
				   }
            }
		}
				}
			}
		}	
			
			
			//INSERT
			$sql = "INSERT INTO `injection_sacks_production`
(`injection_sacks_production_id`,`date_production`,`shift`,`finishproduct`,`semifinished1`,`semifinished2`,`semifinished3`,`sacks`,`user_id`,`status_production`,`used`,`cols`) VALUES (NULL,'". $date."',". $shift .",". $finished .",". $type1 .",". $type2 .", ". $type3 .",". $sacks .",". $_SESSION['Userid'] .",0,0.00,". $cols ."); ". $update; 
			try
			{   
				$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
				$stmt->closeCursor();
                echo '<strong>SUCCESS!</strong> The production of sacks were successfully added to the database for the shift: <strong>'. $this->giveShiftname($shift) .'</strong>';
                return TRUE;
            } 
            catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    echo '<strong>ERROR</strong> The production of sacks have already being register for the shift: <strong>'. $this->giveShiftname($shift) .'</strong>.<br>';
                } 
                else {
                    echo '<strong>ERROR</strong> Could not insert the production into the database. Please try again.<br>'. $e->getMessage();
                }
                return FALSE;
            }
		}
    }
	
	public function giveSacksProduction($shift)
    {
        $newDateString = date("Y-m-d");
        if(!empty($_POST['dateSearch']))
        {
           $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch']);
           $newDateString = $myDateTime->format('Y-m-d');
        }
        $date = "date_production BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString ." 23:59:59'";

        $sql = "SELECT `injection_sacks_production`.`injection_sacks_production_id`,
    `injection_sacks_production`.`date_production`,`injection_sacks_production`.`cols`,
    `injection_sacks_production`.`shift`,materials.material_name as finishname, 
    semi1.material_name as semi1, color1.material_name as color1, 
    semi2.material_name as semi2, color2.material_name as color2, 
    semi3.material_name as semi3, color3.material_name as color3,
    sum(`injection_sacks_production`.`sacks`) as sacks
FROM `injection_sacks_production`
JOIN injection_sacks ON injection_sacks_production.finishproduct = injection_sacks.finishproduct
JOIN materials ON materials.material_id = injection_sacks_production.finishproduct 
JOIN materials semi1 ON semi1.material_id = injection_sacks.semifinished1
LEFT JOIN materials color1 ON color1.material_id = injection_sacks_production.semifinished1
LEFT JOIN materials semi2 ON semi2.material_id = injection_sacks.semifinished2
LEFT JOIN materials color2 ON color2.material_id = injection_sacks_production.semifinished2 
LEFT JOIN materials semi3 ON semi3.material_id = injection_sacks.semifinished3
LEFT JOIN materials color3 ON color3.material_id = injection_sacks_production.semifinished3
	WHERE ". $date ."  
	GROUP BY `date_production`, injection_sacks_production.finishproduct,`injection_sacks_production`.`cols`,  injection_sacks_production.semifinished1, injection_sacks_production.semifinished2, injection_sacks_production.semifinished3
    ORDER BY finishname ";

        if($shift != 0)
        {
            $sql = "SELECT `injection_sacks_production`.`injection_sacks_production_id`,
    `injection_sacks_production`.`date_production`,`injection_sacks_production`.`cols`,
    `injection_sacks_production`.`shift`,materials.material_name as finishname, 
    semi1.material_name as semi1, color1.material_name as color1, 
    semi2.material_name as semi2, color2.material_name as color2, 
    semi3.material_name as semi3, color3.material_name as color3,
    `injection_sacks_production`.`sacks`
FROM `injection_sacks_production`
JOIN injection_sacks ON injection_sacks_production.finishproduct = injection_sacks.finishproduct
JOIN materials ON materials.material_id = injection_sacks_production.finishproduct 
JOIN materials semi1 ON semi1.material_id = injection_sacks.semifinished1
LEFT JOIN materials color1 ON color1.material_id = injection_sacks_production.semifinished1
LEFT JOIN materials semi2 ON semi2.material_id = injection_sacks.semifinished2
LEFT JOIN materials color2 ON color2.material_id = injection_sacks_production.semifinished2 
LEFT JOIN materials semi3 ON semi3.material_id = injection_sacks.semifinished3
LEFT JOIN materials color3 ON color3.material_id = injection_sacks_production.semifinished3
	WHERE ". $date ." AND SHIFT = ". $shift ." ORDER BY finishname";
        }
        
		$total1 = $total2 = $total3 = $total4 = $total5 = 0;
                
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
           
            while($row = $stmt->fetch())
            {   
				$type1 = $row['color1'];
				if(empty($row['color1']))
				{
					$type = 'Transparent';
				}
				$semi1 = $row['semi1'] .' - '.  $type1;
				if(empty($row['semi1']))
				{
					$semi1 = '';
				}
				
				$type2 = $row['color2'];
				if(empty($row['color2']))
				{
					$type = 'Transparent';
				}
				$semi2 = $row['semi2'] .' - '.  $type2;
				if(empty($row['semi2']))
				{
					$semi2 = '';
				}
				
				$type3 = $row['color3'];
				if(empty($row['color3']))
				{
					$type = 'Transparent';
				}
				$semi3 = $row['semi3'] .' - '.  $type3;
				if(empty($row['semi3']))
				{
					$semi3 = '';
				}
				$cols = 'Transparent';
				if($row['cols']==1)
				{
					$cols = 'Colors';
				}
				$total1 = $total1 + $row['sacks'];
                echo '<tr>
                        <td>'.  $row['finishname'] .'</td> 
                        <td>'.  $cols .'</td>                  
                        <td>'.  $semi1 .'</td>                   
                        <td>'.  $semi2 .'</td>                     
                        <td>'.  $semi3 .'</td>                         
                        <td class="text-right">'. number_format($row['sacks'],0,'.',',') .'</td>
                        
                       </tr>';
            }
            $stmt->closeCursor();
            echo '
				<tfoot><tr class="active">
						<th></th>
						<th></th>
						<th></th>
						<th></th>
                        <th class="text-right">Total</th>
                        <th class="text-right">'. number_format($total1,0,'.',',') .'</th>
                        
                       </tr>
				</tfoot>';
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
    SUM(`injection_production`.`good_pcs`) as pcs,
    SUM(`injection_production`.`used_weight`) as used
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
                $PCS = $row['pcs'] - $row['used'] ;
                
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
                $PCS = $row['pcs']  ;
                
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
	/**
    * Checks and inserts the sacks
    *
    * @return boolean true if can insert false if not
    */
    public function createSacksWeight()
    {
        
       	$shift = $finished = $cols = "";
		
        $shift = trim($_POST["shift"]);
        $shift = stripslashes($shift);
        $shift = htmlspecialchars($shift);
		
		$finished = trim($_POST["finished"]);
        $finished = stripslashes($finished);
        $finished = htmlspecialchars($finished);
	
		$cols = trim($_POST["cols"]);
        $cols = stripslashes($cols);
        $cols = htmlspecialchars($cols);
		
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
		
		$totalnet = $totalSacks = $number = 0;
		
		$sacks = "INSERT INTO `injection_sacks_weight`
(`injection_sacks_weight_id`,`date_sacks`,`shift`,`number`,`weight`,`user_id`,`finishproduct`,`cols`)
VALUES";
		foreach ($_POST as $k=>$v)
		{
			if (substr( $k, 0, 3 ) === "wt_" and !empty($v)){
				$i = explode("_",$k)[1];
				
				$no = trim($_POST["no_".$i]);
				$number = $number + $no;
				$totalSacks = $totalSacks + $no;
				$totalnet = $totalnet + $v;
				$sacks = $sacks. " (NULL, '". $date."', ". $shift .", ". $no .", ". $v .", ". $_SESSION['Userid'] .", ". $finished .", ". $cols .") ,";
			}
		}
		
		
		$update = "";
		
		
		$sql = "SELECT `injection_sacks_production_id`, SUM(`sacks`) as total, SUM(used) as used
FROM `injection_sacks_production`
WHERE `status_production` = 0 AND cols= ". $cols." AND finishproduct = ". $finished ."
";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['injection_sacks_production_id']))
				{
					$TOTAL = $row['total'] - $row['used'];
					if($TOTAL<$number)
					{
						echo '<strong>ERROR</strong> The sacks were not added to the production. Because there is not enought injection sacks production in stock. <br> There are <strong>'. $TOTAL .'</strong> kgs in stock, and you need <strong>'. $number .'</strong> sacks.  Please try again after submit the sacks for the production.';
						return false;
					}
				}
					else
				   {
						echo '<strong>ERROR</strong> The sack was not added to the production. ecause there is not enought injection sacks production in stock. <br>  Please try again after submit the sacks for the production.';
						return false;
				   }
            }
		}
		
		$sql = "SELECT `injection_sacks_production_id`, `sacks`, used
				FROM `injection_sacks_production`
				WHERE `status_production` = 0 AND cols= ". $cols." AND finishproduct = ". $finished ."
				ORDER BY `date_production` DESC, `injection_sacks_production_id`
				LIMIT 10;";
		
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['injection_sacks_production_id']))
				{
					$TOTAL = $row['sacks'] - $row['used'];
					if(($TOTAL > $number) and ($number > 0))
					{
						if($number + $row['used'] == $row['sacks'])
						{
							
							$update = $update . "
						UPDATE `injection_sacks_production` SET
                        `used` = `used`+". $number .", `status_production` = 1
						WHERE `injection_sacks_production_id` = ". $row['injection_sacks_production_id']."; ";
						}
						else
						{
							
							$update = $update . "
						UPDATE `injection_sacks_production` SET
                        `used` = `used`+". $number .", `status_production` = 0
						WHERE `injection_sacks_production_id` = ". $row['injection_sacks_production_id']."; ";
						}
						$number = 0;
						$stmt->closeCursor();
					}
					else if(($TOTAL <= $number) and ($number > 0))
					{
						$update = $update . "
						UPDATE `injection_sacks_production` SET
                        `used` = `used`+". $TOTAL .", `status_production` = 1
						WHERE `injection_sacks_production_id` = ". $row['injection_sacks_production_id'].";";
						$number = $number - $TOTAL;
					}
				}
					else
				   {
						echo '<strong>ERROR 3</strong> The sack was not added to the production. ecause there is not enought injection sacks production in stock. <br>  Please try again after submit the sacks for the production.';
						return false;
				   }
            }
		
					
			$transfer = " INSERT INTO  `stock_materials_transfers`(`stock_materials_transfers_id`,`machine_from`,`machine_to`,`material_id`,`date_required`,`bags_required`,`bags_approved`,`bags_issued`,`bags_receipt`,`user_id_required`,`user_id_approved`,`user_id_issued`,`user_id_receipt`,`status_transfer`,`remarks_approved`,`remarks_issued`)VALUES(NULL,6,12, '". $finished ."','". $date ."',". $totalSacks . ",". $totalSacks . ",". $totalSacks . ",NULL,". $_SESSION['Userid'] . ",". $_SESSION['Userid'] . ",". $_SESSION['Userid'] . ",NULL,2,'Total Weight of Sacks = ". $totalnet ."',NULL);";
			
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
	
	
	public function giveSacksWeight($shift)
    {
        $newDateString = date("Y-m-d");
        if(!empty($_POST['dateSearch']))
        {
           $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch']);
           $newDateString = $myDateTime->format('Y-m-d');
        }
        $date = "date_sacks BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString ." 23:59:59'";

        $sql = "SELECT `material_name`, `cols`, `number`,`weight`
FROM `injection_sacks_weight`
JOIN materials ON materials.material_id = finishproduct 
WHERE ". $date ."
ORDER BY injection_sacks_weight_id";

        if($shift != 0)
        {
            $sql = "SELECT `material_name`, `cols`, `number`,`weight`
FROM `injection_sacks_weight`
JOIN materials ON materials.material_id = finishproduct 
WHERE ". $date ." AND shift = ". $shift ."
ORDER BY injection_sacks_weight_id";
        }
        
        $total1 = $total2 = 0;
		$material = "";
                
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {   
				$cols = 'Transparent';
				if($row['cols']==1)
				{
					$cols = 'Colors';
				}
				if($material != $row['material_name']. " - " . $cols )
				{
					if($total1 > 0)
					{
					$avg = $total2 / $total1;	
					echo '</tbody>
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
					  </tfoot>
					  </table>
						</div>
						</div>';
					}
        			$total1 = $total2 = 0;
					$material = $row['material_name']. " - " . $cols;
					echo '<div class="col-md-3">
						<h4>'. $material .'</h4>
						<div class="table-responsive">
							<table class="table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr class="active">
										<th width="70%">No. of sacks</th>
										<th>Weight</th>
									</tr>
								</thead>
								<tbody>';
				}
				
					
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
					echo '</tbody>
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
					  </tfoot>
					  </table>
						</div>
						</div>';
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
        echo '<th>Part Name</th>';
        echo '<th>Target</th>';
        echo '<th>Actual Production</th>';
        echo '<th>% Eff</th>';
        echo '<th>Raw Material Used</th>';
        echo '<th>Waste in Kgs</th>';
        echo '<th>Target Waste</th>';
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
			<th style="text-align:right"></th>
			</tr></tfoot><tbody>'; 
        
        $a=array();
        $c=array();
        $b1=array();
        $b2=array();
        $b3=array();
        $b4=array();
        $b5=array();
        $b6=array();
        $b7=array();
        $b8=array();
        $b9=array();
        $b10=array();
        $d1=array();
        $d2=array();
        $d3=array();
        $d4=array();
        $d5=array();
        $d6=array();
        $d7=array();
        $d8=array();
        $d9=array();
        $d10=array();
		
		        
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
            
			
            $sql = "SELECT  DATE_FORMAT(`injection_production`.`date_production`, '%b/%Y') AS date, machine_name,
    materials.material_name, materials.material_grade, types.material_name as type, 
   `injection_production`.`cavities`,waste_target.target_waste,
    SUM(`injection_production`.`produced_pcs`) as produced_pcs,
    SUM(`injection_production`.`good_pcs`) as good_pcs,
    SUM(`injection_production`.`net_weight`) as net_weight, waste.wastekgs,`injection_production`.`machine_id`,target.target
FROM `injection_production`
JOIN machines ON machines.machine_id = `injection_production`.`machine_id`
JOIN materials ON materials.material_id = `injection_production`.`material_id`
LEFT JOIN materials types ON types.material_id = `injection_production`.`type_id`  
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%b/%Y') AS date, SUM(waste) AS wastekgs, waste.machine_id
    FROM
        `waste`
	JOIN machines ON machines.machine_id = waste.`machine_id`
    WHERE location_id = 6 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%b/%Y'), machine_id) waste ON waste.date = DATE_FORMAT(`injection_production`.`date_production`, '%b/%Y') AND waste.machine_id = `injection_production`.`machine_id`
LEFT JOIN
	(
		SELECT AVG(`injection_sacks_formulas`.`target`), DATE_FORMAT(`to`, '%b/%Y') AS `to` , DATE_FORMAT(`from`, '%b/%Y') AS `from` `injection_sacks_formulas`.`material_id`
        FROM `injection_sacks_formulas`
		GROUP BY DATE_FORMAT(from, '%m/%Y')
    )
     target ON  target.material_id = `injection_production`.`material_id` AND target.`from` <= DATE_FORMAT(`injection_production`.`date_production`, '%Y-%m-%d') AND (target.`to` IS NULL OR target.`to` > DATE_FORMAT(`injection_production`.`date_production`, '%Y-%m-%d'))
	LEFT JOIN
	(
		SELECT `settings`.value_setting AS target_waste, `settings`.to, `settings`.from
        FROM `settings`
        WHERE `settings`.machine_id = 6 AND `settings`.name_setting = 'waste'
    )
    waste_target ON waste_target.`from` <= DATE_FORMAT(`injection_production`.`date_production`, '%b/%Y') AND (waste_target.`to` IS NULL OR waste_target.`to` > DATE_FORMAT(`injection_production`.`date_production`, '%b/%Y'))

	WHERE date_production BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_production`, '%b/%Y'), `injection_production`.`machine_id`, `injection_production`.`material_id`, `injection_production`.`type_id`  
    ORDER BY `injection_production`.`date_production`;";
            
            
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
            
              $sql = "SELECT  DATE_FORMAT(`injection_production`.`date_production`, '%Y') AS date, machine_name,
    materials.material_name, materials.material_grade, types.material_name as type, 
   `injection_production`.`cavities`,waste_target.target_waste,
    SUM(`injection_production`.`produced_pcs`) as produced_pcs,
    SUM(`injection_production`.`good_pcs`) as good_pcs,
    SUM(`injection_production`.`net_weight`) as net_weight, waste.wastekgs,`injection_production`.`machine_id`,target.target
FROM `injection_production`
JOIN machines ON machines.machine_id = `injection_production`.`machine_id`
JOIN materials ON materials.material_id = `injection_production`.`material_id`
LEFT JOIN materials types ON types.material_id = `injection_production`.`type_id`  
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%Y') AS date, SUM(waste) AS wastekgs, waste.machine_id
    FROM
        `waste`
	JOIN machines ON machines.machine_id = waste.`machine_id`
    WHERE location_id = 6 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%Y'), machine_id) waste ON waste.date = DATE_FORMAT(`injection_production`.`date_production`, '%Y') AND waste.machine_id = `injection_production`.`machine_id`
LEFT JOIN
	(
		SELECT AVG(`injection_sacks_formulas`.`target`), DATE_FORMAT(`to`, '%Y') AS `to` , DATE_FORMAT(`from`, '%Y') AS `from` `injection_sacks_formulas`.`material_id`
        FROM `injection_sacks_formulas`
		GROUP BY DATE_FORMAT(from, '%m/%Y')
    )
     target ON  target.material_id = `injection_production`.`material_id` AND target.`from` <= DATE_FORMAT(`injection_production`.`date_production`, '%Y-%m-%d') AND (target.`to` IS NULL OR target.`to` > DATE_FORMAT(`injection_production`.`date_production`, '%Y-%m-%d'))
	LEFT JOIN
	(
		SELECT `settings`.value_setting AS target_waste, `settings`.to, `settings`.from
        FROM `settings`
        WHERE `settings`.machine_id = 6 AND `settings`.name_setting = 'waste'
    )
    waste_target ON waste_target.`from` <= DATE_FORMAT(`injection_production`.`date_production`, '%Y') AND (waste_target.`to` IS NULL OR waste_target.`to` > DATE_FORMAT(`injection_production`.`date_production`, '%Y'))

	WHERE date_production BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_production`, '%Y'), `injection_production`.`machine_id`, `injection_production`.`material_id`, `injection_production`.`type_id`  
    ORDER BY `injection_production`.`date_production` ";
            
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
            
            $sql = "SELECT  DATE_FORMAT(`injection_production`.`date_production`, '%d/%m/%Y') AS date, machine_name,
    materials.material_name, materials.material_grade, types.material_name as type, 
   `injection_production`.`cavities`,waste_target.target_waste,
    SUM(`injection_production`.`produced_pcs`) as produced_pcs,
    SUM(`injection_production`.`good_pcs`) as good_pcs,
    SUM(`injection_production`.`net_weight`) as net_weight, waste.wastekgs,`injection_production`.`machine_id`,target.target
FROM `injection_production`
JOIN machines ON machines.machine_id = `injection_production`.`machine_id`
JOIN materials ON materials.material_id = `injection_production`.`material_id`
LEFT JOIN materials types ON types.material_id = `injection_production`.`type_id`  
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%Y/%m/%d') AS date, SUM(waste) AS wastekgs, waste.machine_id
    FROM
        `waste`
	JOIN machines ON machines.machine_id = waste.`machine_id`
    WHERE location_id = 6 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d'), machine_id) waste ON waste.date = DATE_FORMAT(`injection_production`.`date_production`, '%Y/%m/%d') AND waste.machine_id = `injection_production`.`machine_id`
LEFT JOIN
	(
		SELECT `injection_sacks_formulas`.`target`, `injection_sacks_formulas`.`to`,`injection_sacks_formulas`.`from`, `injection_sacks_formulas`.`material_id`
        FROM `injection_sacks_formulas`
    )
     target ON  target.material_id = `injection_production`.`material_id` AND target.`from` <= DATE_FORMAT(`injection_production`.`date_production`, '%Y-%m-%d') AND (target.`to` IS NULL OR target.`to` > DATE_FORMAT(`injection_production`.`date_production`, '%Y-%m-%d'))
	LEFT JOIN
	(
		SELECT `settings`.value_setting AS target_waste, `settings`.to, `settings`.from
        FROM `settings`
        WHERE `settings`.machine_id = 6 AND `settings`.name_setting = 'waste'
    )
    waste_target ON waste_target.`from` <= DATE_FORMAT(`injection_production`.`date_production`, '%Y-%m-%d') AND (waste_target.`to` IS NULL OR waste_target.`to` > DATE_FORMAT(`injection_production`.`date_production`, '%Y-%m-%d'))

	WHERE date_production BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
	GROUP BY `injection_production`.`date_production`, `injection_production`.`machine_id`, `injection_production`.`material_id`, `injection_production`.`type_id`  
    ORDER BY `injection_production`.`date_production` ";
            
        }
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
               
                $WASTEKG = $row['wastekgs'];
                if(is_null($row['wastekgs']))
                {
                    $WASTEKG = 0;
                }
                $ACTUAL = $row['net_weight'] + $WASTEKG;
                if(is_null($row['net_weight']))
                {
                    $ACTUAL = 0 + $WASTEKG;
                    $WASTEEFF = 0;
                }
                else
                {
                    $WASTEEFF  = round($WASTEKG* 100 / $ACTUAL , 2);
                }
				$TARGET = $row['target'];
				$TARGETWASTE = $row['target_waste'];
				$EFF = round($row['good_pcs'] *100/ $TARGET, 2);
                echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. $row['machine_name'] .'</td>
                        <td class="text-right">'. $row['material_name'] .' - '.  $row['material_grade'] .'</td>
                        <td class="text-right">'. number_format($TARGET,0,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['good_pcs'],0,'.',',') .'</td>
                        <th class="text-right">'. number_format($EFF,2,'.',',') .'</th>
                        <th class="text-right">'. number_format($row['net_weight'],2,'.',',') .'</th>
                        <td class="text-right">'. number_format($WASTEKG,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($TARGETWASTE,2,'.',',') .'</td>
                        <th class="text-right">'. number_format($WASTEEFF,2,'.',',') .'</th>
                    </tr>';
                $entrie = array( $row['date'], $TARGET);
                $entrie0 = array( $row['date'], $TARGETWASTE);
                $entrie1 = array( $row['date'], $row['good_pcs']);
                $entrie2 = array( $row['date'], $WASTEEFF);
                if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
                {
					$entrie = array( $row['date2'], $TARGET);
                    $entrie1 = array( $row['date2'],$row['good_pcs']);
                    $entrie2 = array( $row['date2'], $WASTEEFF);
                }
                array_push($a,$entrie);
                array_push($c,$entrie0);
				if($row['machine_id'] == 35)
				{
                	array_push($b1,$entrie1);
                	array_push($d1,$entrie2);
				}
				else if($row['machine_id'] == 36)
				{
                	array_push($b2,$entrie1);
                	array_push($d2,$entrie2);
				}
				else if($row['machine_id'] == 37)
				{
                	array_push($b3,$entrie1);
                	array_push($d3,$entrie2);
				}
				else if($row['machine_id'] == 38)
				{
                	array_push($b4,$entrie1);
                	array_push($d4,$entrie2);
				}
				else if($row['machine_id'] == 39)
				{
                	array_push($b5,$entrie1);
                	array_push($d5,$entrie2);
				}
				else if($row['machine_id'] == 40)
				{
                	array_push($b6,$entrie1);
                	array_push($d6,$entrie2);
				}
				else if($row['machine_id'] == 41)
				{
                	array_push($b7,$entrie1);
                	array_push($d7,$entrie2);
				}
				else if($row['machine_id'] == 42)
				{
                	array_push($b8,$entrie1);
                	array_push($d8,$entrie2);
				}
				else if($row['machine_id'] == 43)
				{
                	array_push($b9,$entrie1);
                	array_push($d9,$entrie2);
				}
				else if($row['machine_id'] == 44)
				{
                	array_push($b10,$entrie1);
                	array_push($d10,$entrie2);
				}
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
            axisY: {includeZero: false, title: "Good Pcs" },
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
		      name: "Injection - 1",';
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
        echo ' yValueFormatString: "#,### Pcs",
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
		      name: "Injection - 2",';
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
        echo ' yValueFormatString: "#,### Pcs",
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
		      name: "Injection - 3",';
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
        echo ' yValueFormatString: "#,### Pcs",
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
		      name: "Injection - 4",';
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
        echo ' yValueFormatString: "#,### Pcs",
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
		      name: "Injection - 5",';
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
        echo ' yValueFormatString: "#,### Pcs",
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
		      name: "Injection - 6",';
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
        echo ' yValueFormatString: "#,### Pcs",
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
		      name: "Injection - 7",';
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
        echo ' yValueFormatString: "#,### Pcs",
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
		      name: "Injection - 8",';
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
        echo ' yValueFormatString: "#,### Pcs",
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
		      name: "Injection - 9",';
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
        echo ' yValueFormatString: "#,### Pcs",
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
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Injection - 10",';
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
        echo ' yValueFormatString: "#,### Pcs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($b10 as $key => $value) {
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
            foreach($b10 as $key => $value) {
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
            foreach($b10 as $key=>$value) {
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
		      name: "Injection - 1 ",';
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
        echo ' yValueFormatString: "#,##0.00",
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
		      name: "Injection - 2",';
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
        echo ' yValueFormatString: "#,##0.00",
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
		      name: "Injection - 3",';
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
        echo ' yValueFormatString: "#,##0.00",
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
		      name: "Injection - 4",';
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
        echo ' yValueFormatString: "#,##0.00",
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
		      name: "Injection - 5",';
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
        echo ' yValueFormatString: "#,##0.00",
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
		      name: "Injection - 6",';
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
		      name: "Injection - 7",';
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
        echo ' yValueFormatString: "#,##0.00",
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
		      name: "Injection - 8",';
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
        echo ' yValueFormatString: "#,##0.00",
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
		      name: "Injection - 9",';
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
        echo ' yValueFormatString: "#,##0.00",
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
		echo ']},{
                type: "line",
		      showInLegend: true,
		      name: "Injection - 10",';
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
        echo ' yValueFormatString: "#,##0.00",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($d10 as $key => $value) {
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
            foreach($d10 as $key => $value) {
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
            foreach($d10 as $key => $value) {
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
    
    
     public function reportProduction()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Machine</th>';
        echo '<th>Job</th>';
        echo '<th>Actual Production</th>';
        echo '</tr></thead>
			<tfoot><tr  class="active">
			<th style="text-align:right">Total</th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			<th style="text-align:right"></th>
			</tr></tfoot><tbody>'; 
        
       
        $b1=array();
        $b2=array();
        $b3=array();
        $b4=array();
        $b5=array();
        $b6=array();
        $b7=array();
        $b8=array();
        $b9=array();
        $b10=array();
		
		        
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
            
			
            $sql = "SELECT  DATE_FORMAT(`injection_production`.`date_production`, '%b/%Y') AS date, machine_name,
    materials.material_name, materials.material_grade, types.material_name as type, `injection_production`.`machine_id`,
    SUM(`injection_production`.`good_pcs`) as good_pcs
FROM `injection_production`
JOIN machines ON machines.machine_id = `injection_production`.`machine_id`
JOIN materials ON materials.material_id = `injection_production`.`material_id`
LEFT JOIN materials types ON types.material_id = `injection_production`.`type_id`  
	WHERE date_production BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_production`, '%b/%Y'), `injection_production`.`machine_id`, `injection_production`.`material_id`, `injection_production`.`type_id`  
    ORDER BY `injection_production`.`date_production` ";
            
            
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
            
              $sql = "SELECT  DATE_FORMAT(`injection_production`.`date_production`, '%Y') AS date, machine_name,
    materials.material_name, materials.material_grade, types.material_name as type, `injection_production`.`machine_id`,
    SUM(`injection_production`.`good_pcs`) as good_pcs
FROM `injection_production`
JOIN machines ON machines.machine_id = `injection_production`.`machine_id`
JOIN materials ON materials.material_id = `injection_production`.`material_id`
LEFT JOIN materials types ON types.material_id = `injection_production`.`type_id`  
	WHERE date_production BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_production`, '%Y'), `injection_production`.`machine_id`, `injection_production`.`material_id`, `injection_production`.`type_id`  
    ORDER BY `injection_production`.`date_production` ";
            
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
            
            $sql = "SELECT  DATE_FORMAT(`injection_production`.`date_production`, '%d/%m/%Y') AS date, machine_name,
    materials.material_name, materials.material_grade, types.material_name as type, `injection_production`.`machine_id`,
    SUM(`injection_production`.`good_pcs`) as good_pcs
FROM `injection_production`
JOIN machines ON machines.machine_id = `injection_production`.`machine_id`
JOIN materials ON materials.material_id = `injection_production`.`material_id`
LEFT JOIN materials types ON types.material_id = `injection_production`.`type_id`  
	WHERE date_production BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
	GROUP BY `injection_production`.`date_production`, `injection_production`.`machine_id`, `injection_production`.`material_id`, `injection_production`.`type_id`  
    ORDER BY `injection_production`.`date_production` ";
            
        }
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
               
                echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. $row['machine_name'] .'</td>
                        <td class="text-right">'. $row['material_name'] .' - '.  $row['material_grade'] .'</td>
                        <td class="text-right">'. number_format($row['good_pcs'],0,'.',',') .'</td>
                    </tr>';
                $entrie1 = array( $row['date'], $row['good_pcs']);
                if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
                {
                    $entrie1 = array( $row['date2'],$row['good_pcs']);
                }
				if($row['machine_id'] == 35)
				{
                	array_push($b1,$entrie1);
				}
				else if($row['machine_id'] == 36)
				{
                	array_push($b2,$entrie1);
				}
				else if($row['machine_id'] == 37)
				{
                	array_push($b3,$entrie1);
				}
				else if($row['machine_id'] == 38)
				{
                	array_push($b4,$entrie1);
				}
				else if($row['machine_id'] == 39)
				{
                	array_push($b5,$entrie1);
				}
				else if($row['machine_id'] == 40)
				{
                	array_push($b6,$entrie1);
				}
				else if($row['machine_id'] == 41)
				{
                	array_push($b7,$entrie1);
				}
				else if($row['machine_id'] == 42)
				{
                	array_push($b8,$entrie1);
				}
				else if($row['machine_id'] == 43)
				{
                	array_push($b9,$entrie1);
				}
				else if($row['machine_id'] == 44)
				{
                	array_push($b10,$entrie1);
				}
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
         
        echo '<script>document.getElementById("divChart1").setAttribute("class","col-md-12");</script>';
        echo '<script>document.getElementById("chartContainer").style= "height:200px;width:100%";</script>';
         echo '<script> 
            var chart = new CanvasJS.Chart("chartContainer", {
            theme: "light2",
            title: { 
                text: "Production "
            },
            exportFileName: "Production",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {includeZero: false, title: "Total Good production (pcs)"},
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
		name: "Injection - 1",';
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
        echo ' yValueFormatString: "#,### pcs",
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
		name: "Injection - 2",';
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
        echo ' yValueFormatString: "#,### pcs",
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
		      name: "Injection - 3",';
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
        echo ' yValueFormatString: "#,### pcs",
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
		      name: "Injection - 4",';
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
        echo ' yValueFormatString: "#,### pcs",
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
		      name: "Injection - 5",';
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
        echo ' yValueFormatString: "#,### pcs",
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
		      name: "Injection - 6",';
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
        echo ' yValueFormatString: "#,### pcs",
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
		      name: "Injection - 7",';
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
        echo ' yValueFormatString: "#,### pcs",
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
		      name: "Injection - 8",';
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
        echo ' yValueFormatString: "#,### pcs",
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
		      name: "Injection - 9",';
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
        echo ' yValueFormatString: "#,### pcs",
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
         
         echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Injection - 10",';
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
        echo ' yValueFormatString: "#,### pcs",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($b10 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($b10 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($b10 as $value) {
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
        $a9=array();
        $a10=array();
		
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
             WHERE location_id=6 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
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
             WHERE location_id=6 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
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
             WHERE location_id=6 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
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
                if($row['machine_id'] == 35)
				{
                	array_push($a1,$entrie);
				}
				else if($row['machine_id'] == 36)
				{
                	array_push($a2,$entrie);
				}
				else if($row['machine_id'] == 37)
				{
                	array_push($a3,$entrie);
				}
				else if($row['machine_id'] == 38)
				{
                	array_push($a4,$entrie);
				}
				else if($row['machine_id'] == 39)
				{
                	array_push($a5,$entrie);
				}
				else if($row['machine_id'] == 40)
				{
                	array_push($a6,$entrie);
				}
				else if($row['machine_id'] == 41)
				{
                	array_push($a7,$entrie);
				}
				else if($row['machine_id'] == 42)
				{
                	array_push($a8,$entrie);
				}
				else if($row['machine_id'] == 43)
				{
                	array_push($a9,$entrie);
				}
				else if($row['machine_id'] == 44)
				{
                	array_push($a10,$entrie);
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
		name: "Injection - 1",';
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
		name: "Injection - 2",';
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
		      name: "Injection - 3",';
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
		      name: "Injection - 4",';
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
		      name: "Injection - 5",';
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
		      name: "Injection - 6",';
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
		      name: "Injection - 7",';
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
		      name: "Injection - 8",';
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
		      name: "Injection - 9",';
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
         
         echo ']},
            {
                type: "column",
		      showInLegend: true,
		      name: "Injection - 10",';
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
            foreach($a10 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a10 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a10 as $value) {
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
        $a9=array();
        $a10=array();
		
        
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
					location_id=6 AND  `shortfalls`.`date_fall`  BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59') AS total_time,
				GROUP_CONCAT(`shortfalls`.`reason`
					SEPARATOR '<br />') AS reason,
				GROUP_CONCAT(`shortfalls`.`action_plan`
					SEPARATOR '<br />') AS action, machine_name, shortfalls.machine_id
			FROM
				`shortfalls`			
             LEFT JOIN machines ON shortfalls.machine_id = machines.machine_id
			 WHERE
				location_id=6 AND `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
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
					location_id=6 AND  `shortfalls`.`date_fall`  BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59') AS total_time,
				GROUP_CONCAT(`shortfalls`.`reason`
					SEPARATOR '<br />') AS reason,
				GROUP_CONCAT(`shortfalls`.`action_plan`
					SEPARATOR '<br />') AS action, machine_name, shortfalls.machine_id
			FROM
				`shortfalls`
					
             LEFT JOIN machines ON shortfalls.machine_id = machines.machine_id
			 WHERE
				location_id=6 AND `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
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
					location_id=6 AND  `shortfalls`.`date_fall`  BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59') AS total_time,
				GROUP_CONCAT(`shortfalls`.`reason`
					SEPARATOR '<br />') AS reason,
				GROUP_CONCAT(`shortfalls`.`action_plan`
					SEPARATOR '<br />') AS action, machine_name, shortfalls.machine_id 
			FROM
				`shortfalls`
			LEFT JOIN machines ON shortfalls.machine_id = machines.machine_id
			WHERE
				location_id=6 AND `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
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
				if($row['machine_id'] == 35)
				{
                	array_push($a1,$entrie);
				}
				else if($row['machine_id'] == 36)
				{
                	array_push($a2,$entrie);
				}
				else if($row['machine_id'] == 37)
				{
                	array_push($a3,$entrie);
				}
				else if($row['machine_id'] == 38)
				{
                	array_push($a4,$entrie);
				}
				else if($row['machine_id'] == 39)
				{
                	array_push($a5,$entrie);
				}
				else if($row['machine_id'] == 40)
				{
                	array_push($a6,$entrie);
				}
				else if($row['machine_id'] == 41)
				{
                	array_push($a7,$entrie);
				}
				else if($row['machine_id'] == 42)
				{
                	array_push($a8,$entrie);
				}
				else if($row['machine_id'] == 43)
				{
                	array_push($a9,$entrie);
				}
				else if($row['machine_id'] == 44)
				{
                	array_push($a10,$entrie);
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
		name: "Injection - 1",';
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
		name: "Injection - 2",';
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
		name: "Injection - 3",';
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
		name: "Injection - 4",';
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
		name: "Injection - 5",';
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
		name: "Injection - 6",';
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
		name: "Injection - 7",';
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
		name: "Injection - 8",';
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
		name: "Injection - 9",';
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
        
		echo ']},
            {
                type: "column",
		showInLegend: true,
		name: "Injection - 10",';
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
            foreach($a10 as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($a10 as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($a10 as $value) {
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