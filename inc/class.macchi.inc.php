<?php

/**
 * Handles user interactions within the macchi section
 *
 * PHP version 5
 *
 * @author Natalia Montañez
 * @copyright 2017 Natalia Montañez
 *
 */
class Macchi
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
				WHERE `macchi` = 1 AND `consumables` = 0
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
	
	/**
     * Loads the dropdown of all the materials
     *
     * This function outputs <li> tags with materials
     */
    public function consumablesDropdown()
    {
        $sql = "SELECT `materials`.`material_id`,`materials`.`material_name`,`materials`.`material_grade`
                FROM  `materials`
				WHERE `macchi` = 1 AND `consumables` = 1
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
     * Loads the dropdown of all the materials
     *
     * This function outputs <li> tags with materials
     */
    public function customersDropdown()
    {
        $sql = "SELECT `customers`.`customer_id`,
					`customers`.`customer_name`
				FROM `customers`
				WHERE `shrink_film` = 1
                ORDER BY `customers`.`customer_name`;";
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
     * Loads the table of all the formulas depending the layer
     * Parameter= ID of the layer OUTER=1 MIDDLE=2 INNER=3
     * This function outputs <tr> tags with formulas
     */
    public function giveFormulas($product, $layer)
    {
        $sql = "SELECT material_id, material_name, material_grade, percentage, layer
                FROM  `macchi_formulas` NATURAL JOIN  `materials`
                WHERE layer=". $layer ." AND `actual` = 1 AND `product` = ". $product ." ORDER BY material_name;";
        $a=array();
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            $total = 0;
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
                $NAME = $row['material_name'];
                if(!is_null($NAME))
                {
                    $GRADE = $row['material_grade'];
                    $PERCENTAGE = $row['percentage'];
                    $LAYER = $row['layer'];
                    $total = $total + $PERCENTAGE;

                    echo '<tr>
                        <td><b>'. $NAME .'</b></td>
                        <td>'. $GRADE .'</td>
                        <td class="text-right">'. number_format($PERCENTAGE,1,'.',',') .'</td>
                        <td><button class="btn btn-xs btn-warning" type="button" onclick="update(\''. $LAYER .'\',\''. $ID .'\',\''. $NAME .'\',\''. $GRADE .'\')">E</button><button class="btn btn-xs btn-danger" type="button" onclick="deleteFormula(\''. $LAYER .'\',\''. $ID .'\',\''. $NAME .'\',\''. $GRADE .'\')">X</button></td>
                    </tr>';
                    $materialArray=array($NAME,$GRADE,$PERCENTAGE);
                    array_push($a,$materialArray);
                }
            }
            
            echo '<tr class="active">
                    <td colspan="2" class="text-center"><strong>TOTAL</strong></td>
                    <td><strong>'. number_format($total,1,'.',',') .'</strong></td>
                    <td></td>
                </tr>';
            $stmt->closeCursor();
            
            return $a;
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
	
	 public function giveTotalFormula($product)
    {
        $sql = "SELECT  material_name, material_grade,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.actual = 1 AND a.product = ". $product ."
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.actual = 1 AND b.product = ". $product ."
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.actual = 1 AND c.product = ". $product ."
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.actual = 1 AND d.product = ". $product ."
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.actual = 1 AND e.product = ". $product ."

UNION ALL

SELECT  material_name, material_grade,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.actual = 1  AND b.product = ". $product ."
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.actual = 1 AND a.product = ". $product ."
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.actual = 1 AND c.product = ". $product ."
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.actual = 1 AND d.product = ". $product ."
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.actual = 1 AND e.product = ". $product ."
WHERE a.material_id IS NULL

UNION ALL

SELECT  material_name, material_grade,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.actual = 1 AND c.product = ". $product ."
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.actual = 1 AND a.product = ". $product ."
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.actual = 1 AND b.product = ". $product ."
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.actual = 1 AND d.product = ". $product ."
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.actual = 1 AND e.product = ". $product ."
WHERE a.material_id IS NULL AND b.material_id IS NULL

UNION ALL

SELECT  material_name, material_grade,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.actual = 1 AND d.product = ". $product ."
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.actual = 1 AND a.product = ". $product ."
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.actual = 1 AND b.product = ". $product ."
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.actual = 1 AND c.product = ". $product ."
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.actual = 1 AND e.product = ". $product ."
WHERE a.material_id IS NULL AND b.material_id IS NULL AND c.material_id IS NULL

UNION ALL

SELECT  material_name, material_grade,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.actual = 1 AND e.product = ". $product ."
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.actual = 1 AND a.product = ". $product ."
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.actual = 1 AND b.product = ". $product ."
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.actual = 1 AND c.product = ". $product ."
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.actual = 1 AND d.product = ". $product ."
WHERE a.material_id IS NULL AND b.material_id IS NULL AND c.material_id IS NULL AND d.material_id IS NULL";
		
        $a=array();
		if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
				$PERCENTAGE = $row['a_percentage']+$row['b_percentage']+$row['c_percentage']+$row['d_percentage']+$row['e_percentage'];
				$PERCENTAGE = $PERCENTAGE / 100;
				$materialArray=array($NAME,$GRADE,number_format($PERCENTAGE,2,'.',''));
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
     * Checks and inserts a new formula
     *
     * @return boolean  true if can insert  false if not
     */
    public function createFormula($product)
    {
        $layer = $material = $percentage = $remarks= "";
        
        $layer = trim($_POST["layer"]);
        $layer = stripslashes($layer);
        $layer = htmlspecialchars($layer);
        
        $material = trim($_POST["material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
        
        $percentage = trim($_POST["percentage"]);
        $percentage = stripslashes($percentage);
        $percentage = htmlspecialchars($percentage);
		
        $remarks = stripslashes($_POST["remarks"]);
        $remarks = htmlspecialchars($remarks);
        
        $sql = "INSERT INTO  `macchi_formulas`(`macchi_formula_id`,`material_id`, `layer`,`percentage`,`from`,`to`,`actual`,
		`remarks`,`product`) VALUES(NULL,:material, :layer,:percentage, CURRENT_DATE(),NULL,1, :remarks,:product);";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":layer", $layer, PDO::PARAM_INT);
            $stmt->bindParam(":material", $material, PDO::PARAM_INT);
            $stmt->bindParam(":percentage", $percentage, PDO::PARAM_STR);
            $stmt->bindParam(":remarks", $remarks, PDO::PARAM_STR);
            $stmt->bindParam(":product", $product, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            
            echo '<strong>SUCCESS!</strong> The material was successfully added to the layer: <strong>'. $this->giveLayername($layer) .'</strong>';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> The material is already in that layer. If you want to change the amount of kilograms, please try updating it.<br>';
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
    public function updateFormula($product)
    {
        $layer = $material = $percentage = $remarks= "";
        
        $layer = trim($_POST["layer"]);
        $layer = stripslashes($layer);
        $layer = htmlspecialchars($layer);
        
        $material = trim($_POST["material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
        
        $percentage = trim($_POST["percentage"]);
        $percentage = stripslashes($percentage);
        $percentage = htmlspecialchars($percentage);
		
        $remarks = stripslashes($_POST["remarks"]);
        $remarks = htmlspecialchars($remarks);
        
        $sql = "UPDATE  `macchi_formulas`
                SET `to` = CURRENT_DATE, `actual` = 0, `remarks` = concat(`remarks`,' ". $remarks."') 
                WHERE `material_id` = '".$material ."' AND `layer` ='".$layer ."' AND `actual` = 1 AND `product` = ". $product .";
				INSERT INTO `macchi_formulas`(`macchi_formula_id`,`material_id`, `layer`,`percentage`,`from`,`to`,`actual`,
				`remarks`,`product`) VALUES(NULL,:material, :layer,:percentage, CURRENT_DATE(),NULL,1, :remarks,:product);";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":layer", $layer, PDO::PARAM_INT);
            $stmt->bindParam(":material", $material, PDO::PARAM_INT);
            $stmt->bindParam(":percentage", $percentage, PDO::PARAM_STR);
            $stmt->bindParam(":remarks", $remarks, PDO::PARAM_STR);
            $stmt->bindParam(":product", $product, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            echo '<strong>SUCCESS!</strong> The material was successfully updated to the layer: <strong>'. $this->giveLayername($layer) .'</strong> with <strong>'. $percentage .'% </strong>';
            return TRUE;
        } catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not update the material into the database. Please try again.<br>'. $e->getMessage(); 
            return FALSE;
        } 

    }
    
    /**
     * Checks and delete a formula
     *
     * @return boolean  true if can update false if not
     */
    public function deleteFormula($product)
    {
        $layer = $material = $remarks= "";
        
        $layer = trim($_POST["layer"]);
        $layer = stripslashes($layer);
        $layer = htmlspecialchars($layer);
        
        $material = trim($_POST["material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
        
        $remarks = stripslashes($_POST["remarks"]);
        $remarks = htmlspecialchars($remarks);
        
        $sql = "UPDATE  `macchi_formulas`
                SET `to` = CURRENT_DATE, `actual` = 0, `remarks` = concat(`remarks`,' ". $remarks."') 
                WHERE `material_id` = '".$material ."' AND `layer` ='".$layer ."' AND `actual` = 1 AND `product` = ". $product .";";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":layer", $layer, PDO::PARAM_INT);
            $stmt->bindParam(":material", $material, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            
            echo '<strong>SUCCESS!</strong> The material was successfully deleted from the layer: <strong>'. $this->giveLayername($layer) .'</strong>';
            return TRUE;
        } catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not delete the material from the database. Please try again.<br>'. $e->getMessage(); 
            return FALSE;
        } 

    }
	
	 
    /**
     * Loads the table of all the rolls
     * This function outputs <tr> tags with rolls
     * Parameter= ID of the shift ALL DAY=0 MORNING=1 NIGHT=2
     */
    public function giveRolls($x, $size)
    {
        $newDateString = date("Y-m-d");
        if(!empty($_POST['dateSearch']))
        {
           $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch']);
           $newDateString = $myDateTime->format('Y-m-d');
        }
        $date = "date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString ." 23:59:59'";

        $sql = "SELECT rollno, DATE_FORMAT(date_roll, '%H:%i') AS time_r, gross_weight, net_weight, thickness FROM `macchi_rolls` WHERE ". $date ." AND size = ". $size ." ORDER BY CAST(SUBSTRING(rollno,8,2) AS UNSIGNED);";

        if($x != 0)
        {
            $sql = "SELECT rollno, DATE_FORMAT(date_roll, '%H:%i') AS time_r, gross_weight, net_weight, thickness FROM `macchi_rolls` WHERE ". $date ." AND SHIFT = ". $x ." AND size = ". $size ." ORDER BY CAST(SUBSTRING(rollno,8,2) AS UNSIGNED);";
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
                        <td>'.  $row['time_r'] .'</td>                        
                        <td class="text-right">'. number_format($row['gross_weight'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['net_weight'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['thickness'],0,'.',',') .' µ</td>
                        
                       </tr>';
            }
            echo '
              <tfoot>
                <tr class="active">
                  <th colspan="2" style="text-align:center">TOTAL</th>
                  <th class="text-right">'. number_format($total1,2,'.',',') .'</th>
                  <th class="text-right">'. number_format($total2,2,'.',',') .'</th>
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
     * Loads the table of all the shrink rolls
     * This function outputs <tr> tags with rolls
     * Parameter= ID of the shift ALL DAY=0 MORNING=1 NIGHT=2
     */
    public function giveShrink($x)
    {
        $newDateString = date("Y-m-d");
        if(!empty($_POST['dateSearch']))
        {
           $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch']);
           $newDateString = $myDateTime->format('Y-m-d');
        }
        $date = "date_shrink BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString ." 23:59:59'";

        $sql = "SELECT customer_name,
    `macchi_shrink`.`roll_from`,
    `macchi_shrink`.`roll_to`,
    `macchi_shrink`.`rolls`,
    `macchi_shrink`.`shift`,
    `macchi_shrink`.`size`,
    `macchi_shrink`.`thickness`,
    `macchi_shrink`.`gross_weight`,
    `macchi_shrink`.`net_weight`
FROM `macchi_shrink`
NATURAL JOIN customers WHERE ". $date ."
ORDER BY `macchi_shrink_id`";

        if($x != 0)
        {
            $sql = "SELECT customer_name,
    `macchi_shrink`.`roll_from`,
    `macchi_shrink`.`roll_to`,
    `macchi_shrink`.`rolls`,
    `macchi_shrink`.`shift`,
    `macchi_shrink`.`size`,
    `macchi_shrink`.`thickness`,
    `macchi_shrink`.`gross_weight`,
    `macchi_shrink`.`net_weight`
FROM `macchi_shrink`
NATURAL JOIN customers WHERE ". $date ." AND SHIFT = ". $x ."
ORDER BY `macchi_shrink_id`";
        }
        
        $total1 = $total2 = $total3 = 0;
                
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
           
            while($row = $stmt->fetch())
            {   
                $total1 = $total1 + $row['gross_weight'];
                $total2 = $total2 + $row['net_weight'];
                $total3 = $total3 + $row['rolls'];
				
                echo '<tr>
                        <td>'.  $row['customer_name'] .'</td>
                        <td>'.  $row['roll_from'] .'</td>        
                        <td>'.  $row['roll_to'] .'</td>                 
                        <td class="text-right">'. number_format($row['rolls'],0,'.',',') .'</td>       
                        <td class="text-right">'. number_format($row['gross_weight'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['net_weight'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['size'],0,'.',',') .' mm</td>
                        <td class="text-right">'. number_format($row['thickness'],0,'.',',') .' µ</td>
                        
                       </tr>';
            }
            echo '
              <tfoot>
                <tr class="active">
                  <th colspan="3" style="text-align:center">TOTAL</th>
                  <th class="text-right">'. number_format($total3,0,'.',',') .'</th>
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
        $date = "`macchi_rolls`.date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString ." 23:59:59'";

        $sql = "SELECT rollno, DATE_FORMAT(date_roll, '%H:%i') AS time_r, `net_weight` AS production
                FROM `macchi_rolls` 
                WHERE ". $date ." ORDER BY CAST(SUBSTRING(rollno,8,2) AS UNSIGNED);";

        if($x != 0)
        {
            $sql = "SELECT rollno, DATE_FORMAT(date_roll, '%H:%i') AS time_r, `net_weight` AS production
                FROM `macchi_rolls` 
                WHERE ". $date ." AND shift = ". $x ." ORDER BY CAST(SUBSTRING(rollno,8,2) AS UNSIGNED);";
        }       
        $a = $this->giveFormulaFor($newDateString,1);
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
                            <th class="text-center">Roll No</th>
                            <th class="text-center">Time</th>
                            <th class="text-center">Net weight</th>';
            for($i = 0; $i<count($a); ++$i) 
                { 
                    echo '<th class="text-center">'. $a[$i][0] .' - '. $a[$i][1] .'<br/>('. $a[$i][2] .' %)</th>                    ';    
                }
            echo '</thead>
                    <tbody>';
           while($row = $stmt->fetch())
            {
                 echo '<tr>
                    <td>'.  $row['rollno'] .'</td>
                    <td>'.  $row['time_r'] .'</td>
                    <td class="text-right">'.  number_format($row['production'],2,'.',',') .'</td>'  ;
                    $total[0] = $total[0] + $row['production'];
					for($i = 0; $i<count($a); ++$i) 
					{ 
						$x = $a[$i][2]/100*$row['production'];
						$total[$i+1] = $total[$i+1]+$x;
						echo '<td class="text-right">'. number_format($x,2,'.',',') .'</td>';   
					}
               echo '</tr>';
            }
            echo '</tbody>';
            echo '
              <tfoot>
                <tr class="active">
                  <th colspan="2" style="text-align:center">TOTAL KGS</th>';
             for($i = 0; $i<count($total); ++$i) 
                { 
                    echo '<th class="text-right">'. number_format($total[$i],2,'.',',') .'</th>';
             }
            echo '</tr>
            <tr >
                  <td colspan="3" style="text-align:center">TOTAL BAGS</td>';
             for($i = 1; $i<count($total); ++$i) 
                { 
                    echo '<td class="text-right">'. $this->giveBags($total[$i]) .'</td>';
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
	
	
	/**
     * Loads the table of all the consumption for the rolls
     * This function outputs <tr> tags with rolls
     * Parameter= ID of the shift ALL DAY=0 MORNING=1 NIGHT=2
     */
    public function giveShrinkConsumption($x)
    {
        $newDateString = date("Y-m-d");
        if(!empty($_POST['dateSearch']))
        {
           $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch']);
           $newDateString = $myDateTime->format('Y-m-d');
        }
        $date = " `date_shrink` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString ." 23:59:59'";

        $sql = "SELECT `macchi_shrink`.`roll_from`,`macchi_shrink`.`roll_to`, `macchi_shrink`.`rolls`, `net_weight` AS production
                FROM `macchi_shrink` 
                WHERE ". $date ." ORDER BY `macchi_shrink_id`;";

        if($x != 0)
        {
            $sql = "SELECT `macchi_shrink`.`roll_from`,`macchi_shrink`.`roll_to`, `macchi_shrink`.`rolls`, `net_weight` AS production
                FROM `macchi_shrink` 
                WHERE ". $date ." AND shift = ". $x ." ORDER BY `macchi_shrink_id`;";
        }       
        $a = $this->giveFormulaFor($newDateString,2);
        if($stmt = $this->_db->prepare($sql))
        {
          $stmt->execute();
          $total=array();
		  $totalRolls = 0;
            for($i = 0; $i<count($a)+1; ++$i) 
            { 
                 array_push($total,0);
            }
            echo '<thead>
                        <tr class="active">
                            <th class="text-center">Roll No. From</th>
							<th class="text-center">Roll No. To</th>
							<th class="text-center"># Rolls</th>
                            <th class="text-center">Net weight</th>';
            for($i = 0; $i<count($a); ++$i) 
                { 
                    echo '<th class="text-center">'. $a[$i][0] .' - '. $a[$i][1] .'<br/>('. $a[$i][2] .' %)</th>                    ';    
                }
            echo '</thead>
                    <tbody>';
           while($row = $stmt->fetch())
            {
			    $totalRolls = $totalRolls + $row['rolls'];
				echo '<tr>
                    <td>'.  $row['roll_from'] .'</td>
                    <td>'.  $row['roll_to'] .'</td>
                    <td>'.  $row['rolls'] .'</td>
                    <td class="text-right">'.  number_format($row['production'],2,'.',',') .'</td>'  ;
                    $total[0] = $total[0] + $row['production'];
					for($i = 0; $i<count($a); ++$i) 
					{ 
						$x = $a[$i][2]/100*$row['production'];
						$total[$i+1] = $total[$i+1]+$x;
						echo '<td class="text-right">'. number_format($x,2,'.',',') .'</td>';   
					}
               echo '</tr>';
            }
            echo '</tbody>';
            echo '
              <tfoot>
                <tr class="active">
                  <th colspan="2" style="text-align:center">TOTAL KGS</th>';
			echo '<th class="text-right">'. $totalRolls .'</th>';
             for($i = 0; $i<count($total); ++$i) 
                { 
                    echo '<th class="text-right">'. number_format($total[$i],2,'.',',') .'</th>';
             }
            echo '</tr>
            <tr >
                  <td colspan="4" style="text-align:center">TOTAL BAGS</td>';
             for($i = 1; $i<count($total); ++$i) 
                { 
                    echo '<td class="text-right">'. $this->giveBags($total[$i]) .'</td>';
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
	
	 public function giveFormulaFor($date,$product)
    {
        $sql = "SELECT materials.material_id, material_name, material_grade,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.product = ".$product." AND a.`from` <= '".$date."' AND (a.`to` IS NULL OR a.`to` > '".$date."')
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.product = ".$product." AND b.`from` <= '".$date."' AND (b.`to` IS NULL OR b.`to` > '".$date."')
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.product = ".$product." AND c.`from` <= '".$date."' AND (c.`to` IS NULL OR c.`to` > '".$date."')
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.product = ".$product." AND d.`from` <= '".$date."' AND (d.`to` IS NULL OR d.`to` > '".$date."')
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.product = ".$product." AND e.`from` <= '".$date."' AND (e.`to` IS NULL OR e.`to` > '".$date."')

UNION ALL

SELECT materials.material_id, material_name, material_grade,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.product = ".$product." AND b.`from` <= '".$date."' AND (b.`to` IS NULL OR b.`to` > '".$date."')
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.product = ".$product." AND a.`from` <= '".$date."' AND (a.`to` IS NULL OR a.`to` > '".$date."')
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.product = ".$product." AND c.`from` <= '".$date."' AND (c.`to` IS NULL OR c.`to` > '".$date."')
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.product = ".$product." AND d.`from` <= '".$date."' AND (d.`to` IS NULL OR d.`to` > '".$date."')
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.product = ".$product." AND e.`from` <= '".$date."' AND (e.`to` IS NULL OR e.`to` > '".$date."')
WHERE a.material_id IS NULL

UNION ALL

SELECT materials.material_id, material_name, material_grade,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.product = ".$product." AND c.`from` <= '".$date."' AND (c.`to` IS NULL OR c.`to` > '".$date."')
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.product = ".$product." AND a.`from` <= '".$date."' AND (a.`to` IS NULL OR a.`to` > '".$date."')
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.product = ".$product." AND b.`from` <= '".$date."' AND (b.`to` IS NULL OR b.`to` > '".$date."')
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.product = ".$product." AND d.`from` <= '".$date."' AND (d.`to` IS NULL OR d.`to` > '".$date."')
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.product = ".$product." AND e.`from` <= '".$date."' AND (e.`to` IS NULL OR e.`to` > '".$date."')
WHERE a.material_id IS NULL AND b.material_id IS NULL

UNION ALL

SELECT materials.material_id, material_name, material_grade,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.product = ".$product." AND d.`from` <= '".$date."' AND (d.`to` IS NULL OR d.`to` > '".$date."')
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.product = ".$product." AND a.`from` <= '".$date."' AND (a.`to` IS NULL OR a.`to` > '".$date."')
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.product = ".$product." AND b.`from` <= '".$date."' AND (b.`to` IS NULL OR b.`to` > '".$date."')
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.product = ".$product." AND c.`from` <= '".$date."' AND (c.`to` IS NULL OR c.`to` > '".$date."')
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.product = ".$product." AND e.`from` <= '".$date."' AND (e.`to` IS NULL OR e.`to` > '".$date."')
WHERE a.material_id IS NULL AND b.material_id IS NULL AND c.material_id IS NULL

UNION ALL

SELECT materials.material_id, material_name, material_grade,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.product = ".$product." AND e.`from` <= '".$date."' AND (e.`to` IS NULL OR e.`to` > '".$date."')
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.product = ".$product." AND a.`from` <= '".$date."' AND (a.`to` IS NULL OR a.`to` > '".$date."')
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.product = ".$product." AND b.`from` <= '".$date."' AND (b.`to` IS NULL OR b.`to` > '".$date."')
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.product = ".$product." AND c.`from` <= '".$date."' AND (c.`to` IS NULL OR c.`to` > '".$date."')
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.product = ".$product." AND d.`from` <= '".$date."' AND (d.`to` IS NULL OR d.`to` > '".$date."')
WHERE a.material_id IS NULL AND b.material_id IS NULL AND c.material_id IS NULL AND d.material_id IS NULL";
        $a=array();
		if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
				$NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
				$PERCENTAGE = $row['a_percentage']+$row['b_percentage']+$row['c_percentage']+$row['d_percentage']+$row['e_percentage'];
				$PERCENTAGE = $PERCENTAGE / 100;
				
				$materialArray=array($NAME,$GRADE,number_format($PERCENTAGE,2,'.',''),$ID);
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
    * Checks and inserts the rolls WITHOUT BATCHES
    *
    * @return boolean true if can insert false if not
    */
    public function createRolls()
    {
        $CONESMALL = 0;
        $sql = "SELECT `settings`.`value_setting`
                FROM  `settings` 
                WHERE machine_id=5 AND name_setting='680cone';";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $CONESMALL = $row['value_setting'];
            }
        }
        
        $CONEBIG = 0;
        $sql = "SELECT `settings`.`value_setting`
                FROM  `settings` 
                WHERE machine_id=5 AND name_setting='1010cone';";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $CONEBIG = $row['value_setting'];
            }
        }
		
		
        
        
        $shift = $thickness = $size = $sample = "";
		
        $shift = trim($_POST["shift"]);
        $shift = stripslashes($shift);
        $shift = htmlspecialchars($shift);
		
		$size = trim($_POST["size"]);
        $size = stripslashes($size);
        $size = htmlspecialchars($size);
		
		$sample = trim($_POST["sample"]);
        $sample = stripslashes($sample);
        $sample = htmlspecialchars($sample);
		
		
		$thickness = trim($_POST["thickness"]);
        $thickness = stripslashes($thickness);
        $thickness = htmlspecialchars($thickness);
		
		$CONE = $CONEBIG;
		if($size == 1)
		{
			$CONE = $CONESMALL;
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
		
		
		// GETS ROLL NO 
	   $sql = "SELECT COUNT(DISTINCT(rollno)) as rollcount
				FROM `macchi_rolls` WHERE size =  ". $size ." AND  date_roll BETWEEN '". $date ." 00:00:00' AND '". $date ." 23:59:59' ;";
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
		
		$rolls = "INSERT INTO `macchi_rolls`(`macchi_rolls_id`,`date_roll`,`rollno`,`shift`,`size`,`gross_weight`,`net_weight`,`user_id`,`status_roll`,`dyne_test`,`waste_printing`,`thickness`,`sample`)VALUES ";
		foreach ($_POST as $k=>$v)
		{
			if (substr( $k, 0, 3 ) === "wt_" and !empty($v)){
				
				$time = "00:00:00";
				if($k === "wt_1" or $k === "wt_2")
				{
					$time = $_POST["time"]. ":00";
				}
				else if($k === "wt_3" or $k === "wt_4")
				{
					$time = $_POST["time2"]. ":00";
				}
				else if($k === "wt_5" or $k === "wt_6")
				{
					$time = $_POST["time3"]. ":00";
				}
				else if($k === "wt_7" or $k === "wt_8")
				{
					$time = $_POST["time4"]. ":00";
				}
				else if($k === "wt_9" or $k === "wt_10")
				{
					$time = $_POST["time5"]. ":00";
				}
				else if($k === "wt_11" or $k === "wt_12")
				{
					$time = $_POST["time6"]. ":00";
				}
				else if($k === "wt_13" or $k === "wt_14")
				{
					$time = $_POST["time7"]. ":00";
				}
				else if($k === "wt_15" or $k === "wt_16")
				{
					$time = $_POST["time8"]. ":00";
				}
				
				$datetime = $date . " " . $time;
				$count = $count + 1;
				$rollno = "M".$newDateString."-".$count;
				$net = $v - $CONE;
				$totalnet = $totalnet + $net;
				$rolls = $rolls. " (NULL, '". $datetime."', '".$rollno."', ". $shift .", ". $size .", ". $v .", ". $net .",". $_SESSION['Userid'] .", 0, 1, 0.00,". $thickness .",". $sample .") ,";
			}
		}
		
		$sql = "SELECT stock_material_id, material_name, material_grade,bags,kgs_bag,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.product = 1 AND a.`from` <= '".$date."' AND (a.`to` IS NULL OR a.`to` > '".$date."')
LEFT JOIN stock_materials ON materials.material_id = stock_materials.material_id  AND machine_id = 5
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.product = 1 AND b.`from` <= '".$date."' AND (b.`to` IS NULL OR b.`to` > '".$date."')
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.product = 1 AND c.`from` <= '".$date."' AND (c.`to` IS NULL OR c.`to` > '".$date."')
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.product = 1 AND d.`from` <= '".$date."' AND (d.`to` IS NULL OR d.`to` > '".$date."')
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.product = 1 AND e.`from` <= '".$date."' AND (e.`to` IS NULL OR e.`to` > '".$date."')
UNION ALL

SELECT  stock_material_id, material_name, material_grade,bags,kgs_bag,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.product = 1 AND b.`from` <= '".$date."' AND (b.`to` IS NULL OR b.`to` > '".$date."')
LEFT JOIN stock_materials ON materials.material_id = stock_materials.material_id  AND machine_id = 5
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.product = 1 AND a.`from` <= '".$date."' AND (a.`to` IS NULL OR a.`to` > '".$date."')
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.product = 1 AND c.`from` <= '".$date."' AND (c.`to` IS NULL OR c.`to` > '".$date."')
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.product = 1 AND d.`from` <= '".$date."' AND (d.`to` IS NULL OR d.`to` > '".$date."')
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.product = 1 AND e.`from` <= '".$date."' AND (e.`to` IS NULL OR e.`to` > '".$date."')
WHERE a.material_id IS NULL

UNION ALL

SELECT  stock_material_id, material_name, material_grade,bags,kgs_bag,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.product = 1 AND c.`from` <= '".$date."' AND (c.`to` IS NULL OR c.`to` > '".$date."')
LEFT JOIN stock_materials ON materials.material_id = stock_materials.material_id  AND machine_id = 5
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.product = 1 AND a.`from` <= '".$date."' AND (a.`to` IS NULL OR a.`to` > '".$date."')
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.product = 1 AND b.`from` <= '".$date."' AND (b.`to` IS NULL OR b.`to` > '".$date."')
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.product = 1 AND d.`from` <= '".$date."' AND (d.`to` IS NULL OR d.`to` > '".$date."')
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.product = 1 AND e.`from` <= '".$date."' AND (e.`to` IS NULL OR e.`to` > '".$date."')
WHERE a.material_id IS NULL AND b.material_id IS NULL

UNION ALL

SELECT stock_material_id, material_name, material_grade,bags,kgs_bag,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.product = 1 AND d.`from` <= '".$date."' AND (d.`to` IS NULL OR d.`to` > '".$date."')
LEFT JOIN stock_materials ON materials.material_id = stock_materials.material_id  AND machine_id = 5
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.product = 1 AND a.`from` <= '".$date."' AND (a.`to` IS NULL OR a.`to` > '".$date."')
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.product = 1 AND b.`from` <= '".$date."' AND (b.`to` IS NULL OR b.`to` > '".$date."')
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.product = 1 AND c.`from` <= '".$date."' AND (c.`to` IS NULL OR c.`to` > '".$date."')
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.product = 1 AND e.`from` <= '".$date."' AND (e.`to` IS NULL OR e.`to` > '".$date."')
WHERE a.material_id IS NULL AND b.material_id IS NULL AND c.material_id IS NULL

UNION ALL

SELECT stock_material_id, material_name, material_grade,bags,kgs_bag,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.product = 1 AND e.`from` <= '".$date."' AND (e.`to` IS NULL OR e.`to` > '".$date."')
LEFT JOIN stock_materials ON materials.material_id = stock_materials.material_id  AND machine_id = 5
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.product = 1 AND a.`from` <= '".$date."' AND (a.`to` IS NULL OR a.`to` > '".$date."')
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.product = 1 AND b.`from` <= '".$date."' AND (b.`to` IS NULL OR b.`to` > '".$date."')
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.product = 1 AND c.`from` <= '".$date."' AND (c.`to` IS NULL OR c.`to` > '".$date."')
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.product = 1 AND d.`from` <= '".$date."' AND (d.`to` IS NULL OR d.`to` > '".$date."')
WHERE a.material_id IS NULL AND b.material_id IS NULL AND c.material_id IS NULL AND d.material_id IS NULL";
		
		$update = "";
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['stock_material_id']))
				{
					
					$PERCENTAGE = $row['a_percentage']+$row['b_percentage']+$row['c_percentage']+$row['d_percentage']+$row['e_percentage'];
					$PERCENTAGE = $PERCENTAGE / 100; //percentage 80 
					$PERCENTAGE = $PERCENTAGE / 100; //percentage 0.8
				
					$KGSNEEDED = $totalnet * $PERCENTAGE;
					$BAGSNEEDED = $KGSNEEDED / $row['kgs_bag'];
					$BAGSNEEDED = number_format($BAGSNEEDED ,4,'.','');
					//LANZA ERROR SI LAS BOLSAS ACTUALES SON MENORES A LAS QUE SE NECESITAN
					if($row['bags']<$BAGSNEEDED)
					{
						echo '<strong>ERROR</strong> The roll was not added to the production. Because there is not enought material <strong>'. $row['material_name'] .' - '. $row['material_grade'] .'</strong> in stock. <br> There are <strong>'. $row['bags'] .'</strong> bags in stock, and you need <strong>'. $BAGSNEEDED .'</strong> bags. Please try again receiving the raw material or updating the formula.';
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
	
	/**
     * Loads the table of all the rolls in the macchi section
     * This function outputs <tr> tags with the rolls
     */
    public function giveRollsInfo()
    {
        $a=array();
        $b=array();
        $c=array();
        $d=array();
        $sql = "SELECT size, count(macchi_rolls_id) AS count_rolls, ROUND(SUM(gross_weight),2) AS totalgross, ROUND(SUM(net_weight),2) As totalnet, ROUND(SUM(net_weight)/count(macchi_rolls_id),2) AS average_weight
                FROM  `macchi_rolls`
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
                        <td>'. $this->giveSizename($SIZE) .'</td>
                        <td>'. $COUNT .'</td>
                        <td class="text-right">'. number_format($GROSS,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($NET,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($AVERAGE,2,'.',',') .'</td>
                    </tr>';
                
                $countArray=array("y" => $COUNT, "label" => $this->giveSizename($SIZE));
                array_push($a,$countArray);
                $weightArray=array("y" => $GROSS, "label" => $this->giveSizename($SIZE)) ;
                array_push($b,$weightArray);
                $weightArray=array("y" => $NET, "label" => $this->giveSizename($SIZE)) ;
                array_push($c,$weightArray);
                $averageArray=array("y" => $AVERAGE, "label" => $this->giveSizename($SIZE)) ;
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
     * Loads the table of all the rolls in the macchi section
     * This function outputs <tr> tags with the rolls
     */
    public function giveShrinkInfo()
    {
        $a=array();
        $b=array();
        $c=array();
        $d=array();
        $sql = "SELECT customer_name, `macchi_shrink`.`size`, SUM(`macchi_shrink`.`rolls`) as count_rolls, SUM(`macchi_shrink`.`gross_weight`) as totalgross, SUM(`macchi_shrink`.`net_weight`) as totalnet, SUM(`macchi_shrink`.`net_weight`) / SUM(`macchi_shrink`.`rolls`) as average_weight
FROM `macchi_shrink`
NATURAL JOIN customers
WHERE `status_shrink` = 0 
GROUP BY customer_id, size;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $CUSTOMER = $row['customer_name'];
                $SIZE = $row['size'];
                $COUNT = $row['count_rolls'];
                $GROSS = $row['totalgross'];
                $NET = $row['totalnet'];
                $AVERAGE = $row['average_weight'];
                
                echo '<tr>
                        <td>'. $CUSTOMER .'</td>
                        <td>'. $SIZE .'mm</td>
                        <td>'. $COUNT .'</td>
                        <td class="text-right">'. number_format($GROSS,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($NET,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($AVERAGE,2,'.',',') .'</td>
                    </tr>';
                
                $countArray=array("y" => $COUNT, "label" => $CUSTOMER.' - '.$SIZE.'mm');
                array_push($a,$countArray);
                $weightArray=array("y" => $GROSS, "label" => $CUSTOMER.' - '.$SIZE.'mm') ;
                array_push($b,$weightArray);
                $weightArray=array("y" => $NET, "label" => $CUSTOMER.' - '.$SIZE.'mm') ;
                array_push($c,$weightArray);
                $averageArray=array("y" => number_format($AVERAGE,2,'.',','), "label" => $CUSTOMER.' - '.$SIZE.'mm') ;
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
     * Loads the table of all the rolls in the macchi section
     * This function outputs <tr> tags with the rolls
     */
    public function giveShrinkByThickness()
    {
        $a=array();
        $b=array();
        $c=array();
        $d=array();
       $sql = "SELECT customer_name, `macchi_shrink`.`thickness`, SUM(`macchi_shrink`.`rolls`) as count_rolls, SUM(`macchi_shrink`.`gross_weight`) as totalgross, SUM(`macchi_shrink`.`net_weight`) as totalnet, SUM(`macchi_shrink`.`net_weight`) / SUM(`macchi_shrink`.`rolls`) as average_weight
FROM `macchi_shrink`
NATURAL JOIN customers
WHERE `status_shrink` = 0 
GROUP BY customer_id, size;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $CUSTOMER = $row['customer_name'];
                $THICKNESS = $row['thickness'];
                $COUNT = $row['count_rolls'];
                $GROSS = $row['totalgross'];
                $NET = $row['totalnet'];
                $AVERAGE = $row['average_weight'];
                
                echo '<tr>
                        <td>'. $CUSTOMER .'</td>
                        <td>'. $THICKNESS .'µ</td>
                        <td>'. $COUNT .'</td>
                        <td class="text-right">'. number_format($GROSS,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($NET,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($AVERAGE,2,'.',',') .'</td>
                    </tr>';
                
                $countArray=array("y" => $COUNT, "label" => $CUSTOMER.' - '.$THICKNESS.'µ');
                array_push($a,$countArray);
                $weightArray=array("y" => $GROSS, "label" => $CUSTOMER.' - '.$THICKNESS.'µ') ;
                array_push($b,$weightArray);
                $weightArray=array("y" => $NET, "label" => $CUSTOMER.' - '.$THICKNESS.'µ') ;
                array_push($c,$weightArray);
                $averageArray=array("y" => number_format($AVERAGE,2,'.',','), "label" => $CUSTOMER.' - '.$THICKNESS.'µ') ;
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
     * Loads the table of all the rolls in the macchi section
     * This function outputs <tr> tags with the rolls
     */
    public function giveRollsByThickness()
    {
        $a=array();
        $b=array();
        $c=array();
        $d=array();
        $sql = "SELECT thickness, count(macchi_rolls_id) AS count_rolls, ROUND(SUM(gross_weight),2) AS totalgross, ROUND(SUM(net_weight),2) As totalnet, ROUND(SUM(net_weight)/count(macchi_rolls_id),2) AS average_weight
                FROM  `macchi_rolls`
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
    public function giveRollsStock()
    {
        $sql = "SELECT `macchi_rolls`.`rollno`,`macchi_rolls`.`size`, gross_weight, net_weight, thickness
                 FROM  `macchi_rolls`
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
                
                echo '<tr>
                        <td>'. $ROLLNO .'</td>
                        <td>'. $this->giveSizename($SIZE) .'</td>
                        <td class="text-right">'. number_format($GROSS,1,'.',',') .'</td>
                        <td class="text-right">'. number_format($NET,1,'.',',') .'</td>
                        <td class="text-right">'. $THICKNESS .' µ</td>
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
    public function giveShrinkStock()
    {
        $sql = "SELECT customer_name,
    `macchi_shrink`.`roll_from`,
    `macchi_shrink`.`roll_to`,
    `macchi_shrink`.`rolls`,
    `macchi_shrink`.`shift`,
    `macchi_shrink`.`size`,
    `macchi_shrink`.`thickness`,
    `macchi_shrink`.`gross_weight`,
    `macchi_shrink`.`net_weight`
FROM `macchi_shrink`
NATURAL JOIN customers
                WHERE status_shrink = 0;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                              
                echo '<tr>
                        <td>'.  $row['customer_name'] .'</td>
                        <td>'.  $row['roll_from'] .'</td>        
                        <td>'.  $row['roll_to'] .'</td>                 
                        <td class="text-right">'. number_format($row['rolls'],0,'.',',') .'</td>       
                        <td class="text-right">'. number_format($row['gross_weight'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['net_weight'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['size'],0,'.',',') .'mm</td>
                        <td class="text-right">'. number_format($row['thickness'],0,'.',',') .'µ</td>
                        
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
    public function giveWaste()
    {
//        $sql = "SELECT `date_waste`,`waste`.`shift`,`waste`.`waste`,username
//                FROM  `waste` NATURAL JOIN users
//                WHERE machine_id=5 AND MONTH(date_waste) = MONTH(CURRENT_DATE()) AND YEAR(date_waste) = YEAR(CURRENT_DATE()) ORDER BY date_waste DESC;";
		$sql = "SELECT `date_waste`,`waste`.`shift`,`waste`.`waste`,username, type
                FROM  `waste` NATURAL JOIN users
                WHERE machine_id=5 
				ORDER BY date_waste DESC;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $DATE = $row['date_waste'];
                $USER = $row['username'];
                $WASTE = $row['waste'];
                $TYPE = $row['type'];
				if($TYPE == 1)
				{
					 $TYPE = 'Water Pouch';
				}
				else
				{
					$TYPE = 'Shrink Film';
				}
				
                $SHIFT = $this->giveShiftname($row['shift']);
                
                echo '<tr>
                        <td>'. $DATE .'</td>
                        <td>'. $SHIFT .'</td>
                        <td>'. $TYPE .'</td>
                        <td>'. $USER .'</td>
                        <th class="text-right">'. number_format($WASTE,1,'.',',') .'</th>
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
    * Checks and inserts the waste
    *
    * @return boolean true if can insert false if not
    */
    public function createWaste()
    {
        $product = $shift = $total = "";
        
		$product = trim($_POST["job"]);
        $product = stripslashes($product);
        $product = htmlspecialchars($product);
		
        $shift = trim($_POST["shift"]);
        $shift = stripslashes($shift);
        $shift = htmlspecialchars($shift);
        
        $total = trim($_POST["total"]);
        $total = stripslashes($total);
        $total = htmlspecialchars($total); 
        
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
        
        
        //REVISA LAS BOLSAS ACTUALES EN STOCK Y LAS QUE SE NECESITAN PARA HACER EL ROLL EN LA CAPA OUTER
        $sql = "SELECT stock_material_id, material_name, material_grade,bags,kgs_bag,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.product = ".$product." AND a.`from` <= '".$date."' AND (a.`to` IS NULL OR a.`to` > '".$date."')
LEFT JOIN stock_materials ON materials.material_id = stock_materials.material_id  AND machine_id = 5
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.product = ".$product." AND b.`from` <= '".$date."' AND (b.`to` IS NULL OR b.`to` > '".$date."')
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.product = ".$product." AND c.`from` <= '".$date."' AND (c.`to` IS NULL OR c.`to` > '".$date."')
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.product = ".$product." AND d.`from` <= '".$date."' AND (d.`to` IS NULL OR d.`to` > '".$date."')
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.product = ".$product." AND e.`from` <= '".$date."' AND (e.`to` IS NULL OR e.`to` > '".$date."')
UNION ALL

SELECT  stock_material_id, material_name, material_grade,bags,kgs_bag,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.product = ".$product." AND b.`from` <= '".$date."' AND (b.`to` IS NULL OR b.`to` > '".$date."')
LEFT JOIN stock_materials ON materials.material_id = stock_materials.material_id  AND machine_id = 5
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.product = ".$product." AND a.`from` <= '".$date."' AND (a.`to` IS NULL OR a.`to` > '".$date."')
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.product = ".$product." AND c.`from` <= '".$date."' AND (c.`to` IS NULL OR c.`to` > '".$date."')
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.product = ".$product." AND d.`from` <= '".$date."' AND (d.`to` IS NULL OR d.`to` > '".$date."')
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.product = ".$product." AND e.`from` <= '".$date."' AND (e.`to` IS NULL OR e.`to` > '".$date."')
WHERE a.material_id IS NULL

UNION ALL

SELECT  stock_material_id, material_name, material_grade,bags,kgs_bag,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.product = ".$product." AND c.`from` <= '".$date."' AND (c.`to` IS NULL OR c.`to` > '".$date."')
LEFT JOIN stock_materials ON materials.material_id = stock_materials.material_id  AND machine_id = 5
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.product = ".$product." AND a.`from` <= '".$date."' AND (a.`to` IS NULL OR a.`to` > '".$date."')
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.product = ".$product." AND b.`from` <= '".$date."' AND (b.`to` IS NULL OR b.`to` > '".$date."')
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.product = ".$product." AND d.`from` <= '".$date."' AND (d.`to` IS NULL OR d.`to` > '".$date."')
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.product = ".$product." AND e.`from` <= '".$date."' AND (e.`to` IS NULL OR e.`to` > '".$date."')
WHERE a.material_id IS NULL AND b.material_id IS NULL

UNION ALL

SELECT stock_material_id, material_name, material_grade,bags,kgs_bag,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.product = ".$product." AND d.`from` <= '".$date."' AND (d.`to` IS NULL OR d.`to` > '".$date."')
LEFT JOIN stock_materials ON materials.material_id = stock_materials.material_id  AND machine_id = 5
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.product = ".$product." AND a.`from` <= '".$date."' AND (a.`to` IS NULL OR a.`to` > '".$date."')
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.product = ".$product." AND b.`from` <= '".$date."' AND (b.`to` IS NULL OR b.`to` > '".$date."')
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.product = ".$product." AND c.`from` <= '".$date."' AND (c.`to` IS NULL OR c.`to` > '".$date."')
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.product = ".$product." AND e.`from` <= '".$date."' AND (e.`to` IS NULL OR e.`to` > '".$date."')
WHERE a.material_id IS NULL AND b.material_id IS NULL AND c.material_id IS NULL

UNION ALL

SELECT stock_material_id, material_name, material_grade,bags,kgs_bag,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.product = ".$product." AND e.`from` <= '".$date."' AND (e.`to` IS NULL OR e.`to` > '".$date."')
LEFT JOIN stock_materials ON materials.material_id = stock_materials.material_id  AND machine_id = 5
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.product = ".$product." AND a.`from` <= '".$date."' AND (a.`to` IS NULL OR a.`to` > '".$date."')
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.product = ".$product." AND b.`from` <= '".$date."' AND (b.`to` IS NULL OR b.`to` > '".$date."')
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.product = ".$product." AND c.`from` <= '".$date."' AND (c.`to` IS NULL OR c.`to` > '".$date."')
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.product = ".$product." AND d.`from` <= '".$date."' AND (d.`to` IS NULL OR d.`to` > '".$date."')
WHERE a.material_id IS NULL AND b.material_id IS NULL AND c.material_id IS NULL AND d.material_id IS NULL;";
		
        $update = "";
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['stock_material_id']))
				{
					
					$PERCENTAGE = $row['a_percentage']+$row['b_percentage']+$row['c_percentage']+$row['d_percentage']+$row['e_percentage'];
					$PERCENTAGE = $PERCENTAGE / 100; //percentage 80 
					$PERCENTAGE = $PERCENTAGE / 100; //percentage 0.8
				
					$KGSNEEDED = $total * $PERCENTAGE;
					$BAGSNEEDED = $KGSNEEDED / $row['kgs_bag'];
					$BAGSNEEDED = number_format($BAGSNEEDED ,4,'.','');
					//LANZA ERROR SI LAS BOLSAS ACTUALES SON MENORES A LAS QUE SE NECESITAN
					if($row['bags']<$BAGSNEEDED)
					{
						echo '<strong>ERROR</strong> The waste was not added to the production. Because there is not enought material <strong>'. $row['material_name'] .' - '. $row['material_grade'] .'</strong> in stock. <br> There are <strong>'. $row['bags'] .'</strong> bags in stock, and you need <strong>'. $BAGSNEEDED .'</strong> bags. Please try again receiving the raw material or updating the formula.';
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
				   	echo '<strong>ERROR</strong> The waste was not added to the production. Because there is not enought material <strong>'. $row['material_name'] .' - '. $row['material_grade'] .'</strong> in stock. <br>  Please try again receiving the raw material or updating the formula.';
					return false;
			   }
            }
                    
           
            
            //INSERT THE WASTE IN THE DAY DECREASES THE  KGS FROM THE  MULTILAYER_BATCHES_STOCK 
			$bags = $total / 25;
            $sql = "INSERT INTO  `waste`(`waste_id`,`date_waste`,`shift`,`machine_id`,`waste`,`user_id`, `type`) VALUES (NULL,'". $date."', ". $shift .",5, ". $total .", ". $_SESSION['Userid'] .", ". $product .");". $update. ";";
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
    * Checks and inserts the rolls WITHOUT BATCHES
    *
    * @return boolean true if can insert false if not
    */
    public function createShrink()
    {
       
        $shift = $thickness = $size = $customer = $sample = "";
		
		$size = trim($_POST["size"]);
        $size = stripslashes($size);
        $size = htmlspecialchars($size);
		
		$CONE = 0;
        $sql = "SELECT `settings`.`value_setting`
                FROM  `settings` 
                WHERE machine_id=5 AND name_setting='". $size ."cone';";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $CONE = $row['value_setting'];
            }
        }
        
        
		
        $shift = trim($_POST["shift"]);
        $shift = stripslashes($shift);
        $shift = htmlspecialchars($shift);
		
		$sample = trim($_POST["sample"]);
        $sample = stripslashes($sample);
        $sample = htmlspecialchars($sample);
		
		$thickness = trim($_POST["thickness"]);
        $thickness = stripslashes($thickness);
        $thickness = htmlspecialchars($thickness);
		
		$customer = trim($_POST["customer"]);
        $customer = stripslashes($customer);
        $customer = htmlspecialchars($customer);
		
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
	   	$sql = "SELECT SUM(`rolls`) as rollcount
				FROM `macchi_shrink` WHERE customer_id = ".$customer." AND date_shrink BETWEEN '". $date ." 00:00:00' AND '". $date ." 23:59:59';";
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
		
		$rollsql = "INSERT INTO `macchi_shrink`(`macchi_shrink_id`,`date_shrink`,`roll_from`,`roll_to`,`rolls`,`shift`,`size`,`thickness`,`gross_weight`,`net_weight`,`user_id`,`customer_id`,`status_shrink`,`sample`)
		VALUES ";
		foreach ($_POST as $k=>$v)
		{
			if (substr( $k, 0, 3 ) === "wt_" and !empty($v)){
				$i = explode("_",$k)[1];
				
				$rolls = trim($_POST["rolls_".$i]);
        		$rolls = stripslashes($rolls);
        		$rolls = htmlspecialchars($rolls);
				
				if($rolls > 0)
				{
					$time = "00:00:00";
					if($k === "wt_1" )
					{
						$time = $_POST["time"]. ":00";
					}
					else if($k === "wt_2" )
					{
						$time = $_POST["time2"]. ":00";
					}
					else if($k === "wt_3")
					{
						$time = $_POST["time3"]. ":00";
					}
					else if($k === "wt_4")
					{
						$time = $_POST["time4"]. ":00";
					}
					else if($k === "wt_5" )
					{
						$time = $_POST["time5"]. ":00";
					}
					else if($k === "wt_6" )
					{
						$time = $_POST["time6"]. ":00";
					}
					else if($k === "wt_7" )
					{
						$time = $_POST["time7"]. ":00";
					}
					else if($k === "wt_8")
					{
						$time = $_POST["time8"]. ":00";
					}
					else if($k === "wt_9" )
					{
						$time = $_POST["time9"]. ":00";
					}
					else if($k === "wt_10")
					{
						$time = $_POST["time10"]. ":00";
					}

					$datetime = $date . " " . $time;
					$count = $count + 1;
					$rollno = "M".$newDateString."-".$count;
					$count = $count + $rolls - 1;
					$rollno2 = "M".$newDateString."-".$count;
					$net = $v - ($CONE*$rolls);
					$totalnet = $totalnet + $net;
					$rollsql = $rollsql. " (NULL, '". $datetime."', '".$rollno."', '".$rollno2."', '".$rolls."', ". $shift .", ". $size .",". $thickness .", ". $v .", ". $net .",". $_SESSION['Userid'] .", '".$customer."', 0, '".$sample."') ,";
				}
			}
		}
		
		$sql = "SELECT stock_material_id, material_name, material_grade,bags,kgs_bag,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.actual = 1 AND a.product = 2
LEFT JOIN stock_materials ON materials.material_id = stock_materials.material_id  AND machine_id = 5
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.actual = 1 AND b.product = 2
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.actual = 1 AND c.product = 2
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.actual = 1 AND d.product = 2
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.actual = 1 AND e.product = 2

UNION ALL

SELECT  stock_material_id, material_name, material_grade,bags,kgs_bag,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.actual = 1  AND b.product = 2
LEFT JOIN stock_materials ON materials.material_id = stock_materials.material_id  AND machine_id = 5
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.actual = 1 AND a.product = 2
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.actual = 1 AND c.product = 2
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.actual = 1 AND d.product = 2
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.actual = 1 AND e.product = 2
WHERE a.material_id IS NULL

UNION ALL

SELECT  stock_material_id, material_name, material_grade,bags,kgs_bag,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.actual = 1 AND c.product = 2
LEFT JOIN stock_materials ON materials.material_id = stock_materials.material_id  AND machine_id = 5
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.actual = 1 AND a.product = 2
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.actual = 1 AND b.product = 2
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.actual = 1 AND d.product = 2
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.actual = 1 AND e.product = 2
WHERE a.material_id IS NULL AND b.material_id IS NULL

UNION ALL

SELECT stock_material_id, material_name, material_grade,bags,kgs_bag,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.actual = 1 AND d.product = 2
LEFT JOIN stock_materials ON materials.material_id = stock_materials.material_id  AND machine_id = 5
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.actual = 1 AND a.product = 2
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.actual = 1 AND b.product = 2
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.actual = 1 AND c.product = 2
LEFT JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.actual = 1 AND e.product = 2
WHERE a.material_id IS NULL AND b.material_id IS NULL AND c.material_id IS NULL

UNION ALL

SELECT stock_material_id, material_name, material_grade,bags,kgs_bag,
(a.percentage)*10 AS a_percentage, 
(b.percentage)*20 AS b_percentage, 
(c.percentage)*40 AS c_percentage, 
(d.percentage)*20 AS d_percentage, 
(e.percentage)*10 AS e_percentage
FROM materials 
INNER JOIN `macchi_formulas` e ON materials.material_id = e.material_id AND e.layer=5 AND e.actual = 1 AND e.product = 2
LEFT JOIN stock_materials ON materials.material_id = stock_materials.material_id  AND machine_id = 5
LEFT JOIN `macchi_formulas` a ON materials.material_id = a.material_id AND a.layer=1 AND a.actual = 1 AND a.product = 2
LEFT JOIN `macchi_formulas` b ON materials.material_id = b.material_id AND b.layer=2 AND b.actual = 1 AND b.product = 2
LEFT JOIN `macchi_formulas` c ON materials.material_id = c.material_id AND c.layer=3 AND c.actual = 1 AND c.product = 2
LEFT JOIN `macchi_formulas` d ON materials.material_id = d.material_id AND d.layer=4 AND d.actual = 1 AND d.product = 2
WHERE a.material_id IS NULL AND b.material_id IS NULL AND c.material_id IS NULL AND d.material_id IS NULL";
		
		$update = "";
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				if(!is_null($row['stock_material_id']))
				{
					
					$PERCENTAGE = $row['a_percentage']+$row['b_percentage']+$row['c_percentage']+$row['d_percentage']+$row['e_percentage'];
					$PERCENTAGE = $PERCENTAGE / 100; //percentage 80 
					$PERCENTAGE = $PERCENTAGE / 100; //percentage 0.8
				
					$KGSNEEDED = $totalnet * $PERCENTAGE;
					$BAGSNEEDED = $KGSNEEDED / $row['kgs_bag'];
					$BAGSNEEDED = number_format($BAGSNEEDED ,2,'.','');
					//LANZA ERROR SI LAS BOLSAS ACTUALES SON MENORES A LAS QUE SE NECESITAN
					if($row['bags']<$BAGSNEEDED)
					{
						echo '<strong>ERROR</strong> The roll was not added to the production. Because there is not enought material <strong>'. $row['material_name'] .' - '. $row['material_grade'] .'</strong> in stock. <br> There are <strong>'. $row['bags'] .'</strong> bags in stock, and you need <strong>'. $BAGSNEEDED .'</strong> bags. Please try again receiving the raw material or updating the formula.';
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
		
			$sql = substr($rollsql,0,strlen($rollsql)-2). "; ". $update;
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
	
	 /**
     * Loads the Downtime, Remarks, Reason Report in the multilayer section
     * This function outputs <tr> tags with the report
     */
    public function reportReason()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Downtime</th>';
        echo '<th>Reason for Short Fall</th>';
        echo '<th>Action Plan</th>';
        echo '</tr></thead><tbody>';   
        
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
            
            $sql = "
			SELECT 
				DATE_FORMAT(`date_fall`, '%b/%Y') AS date, DATE_FORMAT(`date_fall`, '%m/%Y') as date2,
				SEC_TO_TIME(SUM(TIME_TO_SEC(`shortfalls`.`downtime`))) AS total,
				SUM(HOUR(`shortfalls`.`downtime`)) AS hours,
				SUM(MINUTE(`shortfalls`.`downtime`)) AS minutes, total_time,
				GROUP_CONCAT(`shortfalls`.`reason`
					SEPARATOR '<br />') AS reason,
				GROUP_CONCAT(`shortfalls`.`action_plan`
					SEPARATOR '<br />') AS action
			FROM
				`shortfalls`
					LEFT JOIN
				(SELECT 
					SEC_TO_TIME(SUM(TIME_TO_SEC(`shortfalls`.`downtime`))) AS total_time,
						machine_id
				FROM
					`shortfalls`
				WHERE
					machine_id = 5 AND  `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59') total ON total.machine_id = `shortfalls`.machine_id
			WHERE
				`shortfalls`.machine_id = 5 AND `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
			GROUP BY DATE_FORMAT(`date_fall`, '%b/%Y')
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
				SUM(MINUTE(`shortfalls`.`downtime`)) AS minutes, total_time,
				GROUP_CONCAT(`shortfalls`.`reason`
					SEPARATOR '<br />') AS reason,
				GROUP_CONCAT(`shortfalls`.`action_plan`
					SEPARATOR '<br />') AS action
			FROM
				`shortfalls`
					LEFT JOIN
				(SELECT 
					SEC_TO_TIME(SUM(TIME_TO_SEC(`shortfalls`.`downtime`))) AS total_time,
						machine_id
				FROM
					`shortfalls`
				WHERE
					machine_id = 5 AND  `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59') total ON total.machine_id = `shortfalls`.machine_id
			WHERE
				`shortfalls`.machine_id = 5 AND `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
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
				SUM(MINUTE(`shortfalls`.`downtime`)) AS minutes, total_time,
				GROUP_CONCAT(`shortfalls`.`reason`
					SEPARATOR '<br />') AS reason,
				GROUP_CONCAT(`shortfalls`.`action_plan`
					SEPARATOR '<br />') AS action
			FROM
				`shortfalls`
					LEFT JOIN
				(SELECT 
					SEC_TO_TIME(SUM(TIME_TO_SEC(`shortfalls`.`downtime`))) AS total_time,
						machine_id
				FROM
					`shortfalls`
				WHERE
					machine_id = 5 AND  `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59') total ON total.machine_id = `shortfalls`.machine_id
			WHERE
				`shortfalls`.machine_id = 5 AND `shortfalls`.`date_fall` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
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
                array_push($a,$entrie);
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
		
        echo '</tbody><tfoot><tr  class="active">
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
            axisY: {includeZero: false, title: "Hours" },';
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
                type: "column",';
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
        echo'] }]});
        chart.render(); 
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
        echo '<th>Job</th>';
        echo '<th>Good Production</th>';
        echo '<th>No. of rolls produced</th>';
        echo '</tr></thead><tfoot><tr  class="active">
			<th></th>
			<th style="text-align:right">Total</th>
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
            
            $sql = " SELECT DATE_FORMAT(`date_roll`, '%b/%Y') as date, DATE_FORMAT(`date_roll`, '%m/%Y') as date2,
    ROUND(SUM(macchi_rolls.net_weight), 2) AS actual,
    ROUND(SUM(macchi_rolls.net_weight), 2) AS actual_rolls,
    0 AS actual_shrink, COUNT(macchi_rolls_id) as rolls,
    waste.wastekgs
FROM
    macchi_rolls
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%m/%Y') AS date,
            SUM(waste) AS wastekgs
    FROM
        `waste`
    WHERE
        machine_id = 5 AND type = 1
            AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%m/%Y')
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%m/%Y') 
WHERE
    date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59' 
GROUP BY DATE_FORMAT(`date_roll`, '%m/%Y') 
UNION ALL 
SELECT  DATE_FORMAT(`date_shrink`, '%b/%Y') as date, DATE_FORMAT(`date_shrink`, '%m/%Y') as date2,
    ROUND(SUM(net_weight),2) AS actual,
    0 AS actual_rolls,
    ROUND(SUM(net_weight),2) AS actual_shrink, SUM(rolls) as rolls,
    waste.wastekgs
FROM
        `macchi_shrink`
    LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%m/%Y') AS date,
            SUM(waste) AS wastekgs
    FROM
        `waste`
    WHERE
        machine_id = 5 AND type = 2
            AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%m/%Y')
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_shrink`, '%m/%Y')
WHERE
 date_shrink BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_shrink`, '%m/%Y')
ORDER BY date, actual_shrink;";
            
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
            
            $sql = "SELECT 
    DATE_FORMAT(`date_roll`, '%Y') AS date,
    ROUND(SUM(macchi_rolls.net_weight), 2) AS actual,
    ROUND(SUM(macchi_rolls.net_weight), 2) AS actual_rolls,
    0 AS actual_shrink, COUNT(macchi_rolls_id) as rolls,
    waste.wastekgs
FROM
    macchi_rolls
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%Y') AS date,
            SUM(waste) AS wastekgs
    FROM
        `waste`
    WHERE
        machine_id = 5 AND type = 1
            AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%Y')
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y') 
WHERE
    date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59' 
GROUP BY DATE_FORMAT(`date_roll`, '%Y') 
UNION ALL 
SELECT 
    DATE_FORMAT(`date_shrink`, '%Y') AS date,
    ROUND(SUM(net_weight),2) AS actual,
    0 AS actual_rolls,
    ROUND(SUM(net_weight),2) AS actual_shrink, SUM(rolls) as rolls,
    waste.wastekgs
FROM
        `macchi_shrink`
    LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%Y') AS date,
            SUM(waste) AS wastekgs
    FROM
        `waste`
    WHERE
        machine_id = 5 AND type = 2
            AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%Y')
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_shrink`, '%Y')
WHERE
 date_shrink BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_shrink`, '%Y')
ORDER BY date, actual_shrink;";
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
            
            $sql = " SELECT 
    DATE_FORMAT(`date_roll`, '%d/%m/%Y') AS date,
    ROUND(SUM(macchi_rolls.net_weight), 2) AS actual,
    ROUND(SUM(macchi_rolls.net_weight), 2) AS actual_rolls,
    0 AS actual_shrink, COUNT(macchi_rolls_id) as rolls,
    waste.wastekgs
FROM
    macchi_rolls
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%Y/%m/%d') AS date,
            SUM(waste) AS wastekgs
    FROM
        `waste`
    WHERE
        machine_id = 5 AND type = 1
            AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d')
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y/%m/%d') 
WHERE
    date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59' 
GROUP BY DATE_FORMAT(`date_roll`, '%d/%m/%Y') 
UNION ALL 
SELECT 
    DATE_FORMAT(`date_shrink`, '%d/%m/%Y') AS date,
    ROUND(SUM(net_weight),2) AS actual,
    0 AS actual_rolls,
    ROUND(SUM(net_weight),2) AS actual_shrink, SUM(rolls) as rolls,
    waste.wastekgs
FROM
        `macchi_shrink`
    LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%Y/%m/%d') AS date,
            SUM(waste) AS wastekgs
    FROM
        `waste`
    WHERE
        machine_id = 5 AND type = 2
            AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d')
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_shrink`, '%Y/%m/%d')
WHERE
 date_shrink BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_shrink`, '%Y/%m/%d')
ORDER BY date, actual_shrink;";
            
        }
        
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				$PRODUCT = 'Water pouch';
				if($row['actual_shrink'] > 0)
				{
					$PRODUCT = 'Shrink Film';
					
				}
                echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. $PRODUCT .'</td>
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
                if($row['actual_shrink'] == 0)
				{
                	array_push($a,$entrie);
				}
				else
				{
                	array_push($c,$entrie);
				}
                if($row['actual_shrink'] == 0)
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
            axisY: {includeZero: false, title: "Total net production (kgs)" },';
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
		      name: "Water Pouch",';
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
		      name: "Shrink Film",';
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
            axisY: {includeZero: false, title: "Rolls" },';
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
		      name: "Water Pouch",';
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
		      name: "Shrink Film",';
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
        echo '<th>Waste</th>';
        echo '</tr></thead><tfoot><tr  class="active">
			<th style="text-align:right">Total</th>
			<th style="text-align:right"></th></tr></tfoot><tbody>';   
        
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
            
            $sql = " SELECT DATE_FORMAT(`date_waste`, '%b/%Y') as date, DATE_FORMAT(`date_waste`, '%m/%Y') as date2, SUM(`waste`.`waste`) AS total
             FROM  `waste`
             WHERE machine_id=5 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_waste`, '%b/%Y') 
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
            
            $sql = " SELECT DATE_FORMAT(`date_waste`, '%Y') as date, SUM(`waste`.`waste`) AS total
             FROM  `waste`
             WHERE machine_id=5 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
             GROUP BY DATE_FORMAT(`date_waste`, '%Y') 
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
            
            $sql = " SELECT DATE_FORMAT(`date_waste`, '%d/%m/%Y') AS date, SUM(`waste`.`waste`) AS total
             FROM  `waste`
             WHERE machine_id=5 AND `waste`.date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
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
                        <td class="text-right">'. number_format($row['total'],1,'.',',') .'</td>
                    </tr>';
                $entrie = array( $row['date'], $row['total']);
                if($_POST['searchBy']==2)
                {
                    $entrie = array( $row['date2'], $row['total']);
                }
                array_push($a,$entrie);
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
            axisY: {includeZero: false, title: "Total Process Waste (kgs)" },';
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
                type: "column",';
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
    }
	
	 /**
     * Checks gives the shortfalls reasons
     *
     */
    public function giveShortFall()
    {
//        $sql = "SELECT `shortfalls`.`date_fall`, `shortfalls`.`downtime` AS time_t, `shortfalls`.`reason`,`shortfalls`.`action_plan`
//                FROM  `shortfalls` 
//                WHERE machine_id = 5 AND MONTH(date_fall) = MONTH(CURRENT_DATE()) AND YEAR(date_fall) = YEAR(CURRENT_DATE()) ORDER BY date_fall ;";
		$sql = "SELECT `shortfalls`.`date_fall`, `shortfalls`.`downtime` AS time_t, `shortfalls`.`reason`,`shortfalls`.`action_plan`
                FROM  `shortfalls` 
                WHERE machine_id = 5 
				ORDER BY date_fall ;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                echo '<tr>
                    <td>'. $row['date_fall'] .'</td>
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
        $reason = $action = $time = "";
        
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
        (NULL,5,'". $date . "',:time,:reason,:action);";
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
     * Checks gives the settings
     *
     */
    public function giveSettings()
    {
        $sql = "SELECT `settings`.`name_setting`, `settings`.`value_setting`
                FROM  `settings` 
                WHERE machine_id=5;";
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
        
        $sql = "UPDATE  `settings`
                SET `to` = CURRENT_DATE, `actual` = 0
                WHERE machine_id = 5 AND `name_setting` = '". $name ."' AND `actual` = 1;
				INSERT INTO `settings`(`setting_id`,`machine_id`,`name_setting`,`value_setting`,`from`,
				`to`,`actual`)VALUES
				(NULL,5,'". $name ."','". $value ."',CURRENT_DATE(),NULL,1);";
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
        echo '<th>Job</th>';
        echo '<th>Machine Capacity</th>';
        echo '<th>Orders Target</th>';
        echo '<th>Actual Production</th>';
        echo '<th>% Eff</th>';
        echo '<th>Waste in Kgs</th>';
        echo '<th>Target Waste %</th>';
        echo '<th>Waste %</th>';
        echo '</tr></thead>
			<tfoot><tr  class="active">
			<th style="text-align:right"></th>
			<th style="text-align:right">Total</th>
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
            
			
            $sql = "SELECT DATE_FORMAT(`date_roll`, '%b/%Y') as date, DATE_FORMAT(`date_roll`, '%m/%Y') as date2,
    ROUND(SUM(macchi_rolls.net_weight), 2) AS actual,
    ROUND(SUM(macchi_rolls.net_weight), 2) AS actual_rolls,
    0 AS actual_shrink,
    waste.wastekgs,
    COUNT(DISTINCT (DATE_FORMAT(`date_roll`, '%m/%Y'))) AS days,
    target_waste,
    target,
    capacity
FROM
    macchi_rolls
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%m/%Y') AS date,
            SUM(waste) AS wastekgs
    FROM
        `waste`
    WHERE
        machine_id = 5 AND type = 1
            AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%m/%Y')
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%m/%Y')
        LEFT JOIN
    (SELECT 
        DATE_FORMAT(`target_orders`.`date`, '%m/%Y') AS date,
            SUM(target_order) AS target
    FROM
        `target_orders`
    WHERE
        machine_id = 5
            AND date BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`target_orders`.`date`, '%m/%Y')
    ORDER BY `target_orders`.`date`) targets ON targets.date = DATE_FORMAT(`date_roll`, '%m/%Y')
        LEFT JOIN
    (SELECT 
        `settings`.value_setting AS target_waste,
          DATE_FORMAT(`settings`.to, '%m/%Y') AS `to` ,
		  DATE_FORMAT(`settings`.from, '%m/%Y') AS `from` 
    FROM
        `settings`
    WHERE
        `settings`.machine_id = 5
            AND `settings`.name_setting = 'waste') waste_target ON waste_target.`from` <= DATE_FORMAT(`date_roll`, '%m/%Y')
        AND (waste_target.`to` IS NULL
        OR waste_target.`to` > DATE_FORMAT(`date_roll`, '%m/%Y'))
        LEFT JOIN
    (SELECT 
        `settings`.value_setting AS capacity,
          DATE_FORMAT(`settings`.to, '%m/%Y') AS `to` ,
		  DATE_FORMAT(`settings`.from, '%m/%Y') AS `from` 
    FROM
        `settings`
    WHERE
        `settings`.machine_id = 5
            AND `settings`.name_setting = 'targetRolls') capacity_rolls ON capacity_rolls.`from` <= DATE_FORMAT(`date_roll`, '%m/%Y')
        AND (capacity_rolls.`to` IS NULL
        OR capacity_rolls.`to` > DATE_FORMAT(`date_roll`, '%m/%Y'))
      
WHERE
    date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59' 
GROUP BY DATE_FORMAT(`date_roll`, '%m/%Y') 
UNION ALL 
SELECT 
    DATE_FORMAT(`date_shrink`, '%b/%Y') as date, DATE_FORMAT(`date_shrink`, '%m/%Y') as date2,
    ROUND(SUM(net_weight),2) AS actual,
    0 AS actual_rolls,
    ROUND(SUM(net_weight),2) AS actual_shrink,
    waste.wastekgs,
    COUNT(DISTINCT (DATE_FORMAT(`date_shrink`, '%m/%Y'))) AS days,
    target_waste,
    target,
    capacity
FROM
        `macchi_shrink`
    LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%m/%Y') AS date,
            SUM(waste) AS wastekgs
    FROM
        `waste`
    WHERE
        machine_id = 5 AND type = 2
            AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%m/%Y')
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_shrink`, '%m/%Y')
        LEFT JOIN
    (SELECT 
        DATE_FORMAT(`target_orders`.`date`, '%m/%Y') AS date,
            SUM(target_order) AS target
    FROM
        `target_orders`
    WHERE
        machine_id = 5
            AND date BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`target_orders`.`date`, '%m/%Y')
    ORDER BY `target_orders`.`date`) targets ON targets.date = DATE_FORMAT(`date_shrink`, '%m/%Y')
        LEFT JOIN
    (SELECT 
        `settings`.value_setting AS target_waste,
          DATE_FORMAT(`settings`.to, '%m/%Y') AS `to` ,
		  DATE_FORMAT(`settings`.from, '%m/%Y') AS `from` 
    FROM
        `settings`
    WHERE
        `settings`.machine_id = 5
            AND `settings`.name_setting = 'waste') waste_target ON waste_target.`from` <= DATE_FORMAT(`date_shrink`, '%m/%Y')
        AND (waste_target.`to` IS NULL
        OR waste_target.`to` > DATE_FORMAT(`date_shrink`, '%m/%Y'))
      LEFT JOIN
    (SELECT 
        `settings`.value_setting AS capacity,
          DATE_FORMAT(`settings`.to, '%m/%Y') AS `to` ,
		  DATE_FORMAT(`settings`.from, '%m/%Y') AS `from` 
    FROM
        `settings`
    WHERE
        `settings`.machine_id = 5
            AND `settings`.name_setting = 'targetShrink') capacity_shrink ON capacity_shrink.`from` <= DATE_FORMAT(`date_shrink`, '%m/%Y')
        AND (capacity_shrink.`to` IS NULL
        OR capacity_shrink.`to` > DATE_FORMAT(`date_shrink`, '%m/%Y'))
WHERE
 date_shrink BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_shrink`, '%m/%Y')
ORDER BY date2, actual_shrink;";
            
            
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
    DATE_FORMAT(`date_roll`, '%Y') AS date,
    ROUND(SUM(macchi_rolls.net_weight), 2) AS actual,
    ROUND(SUM(macchi_rolls.net_weight), 2) AS actual_rolls,
    0 AS actual_shrink,
    waste.wastekgs,
    COUNT(DISTINCT (DATE_FORMAT(`date_roll`, '%Y'))) AS days,
    target_waste,
    target,
    capacity
FROM
    macchi_rolls
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%Y') AS date,
            SUM(waste) AS wastekgs
    FROM
        `waste`
    WHERE
        machine_id = 5 AND type = 1
            AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%Y')
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y')
        LEFT JOIN
    (SELECT 
        DATE_FORMAT(`target_orders`.`date`, '%Y') AS date,
            SUM(target_order) AS target
    FROM
        `target_orders`
    WHERE
        machine_id = 5
            AND date BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`target_orders`.`date`, '%Y')
    ORDER BY `target_orders`.`date`) targets ON targets.date = DATE_FORMAT(`date_roll`, '%Y')
        LEFT JOIN
    (SELECT 
        `settings`.value_setting AS target_waste,
          DATE_FORMAT(`settings`.to, '%Y') AS `to` ,
          DATE_FORMAT(`settings`.from, '%Y') AS `from` 
    FROM
        `settings`
    WHERE
        `settings`.machine_id = 5
            AND `settings`.name_setting = 'waste') waste_target ON waste_target.`from` <= DATE_FORMAT(`date_roll`, '%Y')
        AND (waste_target.`to` IS NULL
        OR waste_target.`to` > DATE_FORMAT(`date_roll`, '%Y'))
        LEFT JOIN
    (SELECT 
        `settings`.value_setting AS capacity,
          DATE_FORMAT(`settings`.to, '%Y') AS `to` ,
          DATE_FORMAT(`settings`.from, '%Y') AS `from` 
    FROM
        `settings`
    WHERE
        `settings`.machine_id = 5
            AND `settings`.name_setting = 'targetRolls') capacity_rolls ON capacity_rolls.`from` <= DATE_FORMAT(`date_roll`, '%Y')
        AND (capacity_rolls.`to` IS NULL
        OR capacity_rolls.`to` > DATE_FORMAT(`date_roll`, '%Y'))
      
WHERE
    date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59' 
GROUP BY DATE_FORMAT(`date_roll`, '%Y') 
UNION ALL 
SELECT 
    DATE_FORMAT(`date_shrink`, '%Y') AS date,
    ROUND(SUM(net_weight),2) AS actual,
    0 AS actual_rolls,
    ROUND(SUM(net_weight),2) AS actual_shrink,
    waste.wastekgs,
    COUNT(DISTINCT (DATE_FORMAT(`date_shrink`, '%Y'))) AS days,
    target_waste,
    target,
    capacity
FROM
        `macchi_shrink`
    LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%Y') AS date,
            SUM(waste) AS wastekgs
    FROM
        `waste`
    WHERE
        machine_id = 5 AND type = 2
            AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%Y')
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_shrink`, '%Y')
        LEFT JOIN
    (SELECT 
        DATE_FORMAT(`target_orders`.`date`, '%Y') AS date,
            SUM(target_order) AS target
    FROM
        `target_orders`
    WHERE
        machine_id = 5
            AND date BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`target_orders`.`date`, '%Y')
    ORDER BY `target_orders`.`date`) targets ON targets.date = DATE_FORMAT(`date_shrink`, '%Y')
        LEFT JOIN
    (SELECT 
        `settings`.value_setting AS target_waste,
          DATE_FORMAT(`settings`.to, '%Y') AS `to` ,
          DATE_FORMAT(`settings`.from, '%Y') AS `from` 
    FROM
        `settings`
    WHERE
        `settings`.machine_id = 5
            AND `settings`.name_setting = 'waste') waste_target ON waste_target.`from` <= DATE_FORMAT(`date_shrink`, '%Y')
        AND (waste_target.`to` IS NULL
        OR waste_target.`to` > DATE_FORMAT(`date_shrink`, '%Y'))
      LEFT JOIN
    (SELECT 
        `settings`.value_setting AS capacity,
          DATE_FORMAT(`settings`.to, '%Y') AS `to` ,
          DATE_FORMAT(`settings`.from, '%Y') AS `from` 
    FROM
        `settings`
    WHERE
        `settings`.machine_id = 5
            AND `settings`.name_setting = 'targetShrink') capacity_shrink ON capacity_shrink.`from` <= DATE_FORMAT(`date_shrink`, '%Y')
        AND (capacity_shrink.`to` IS NULL
        OR capacity_shrink.`to` > DATE_FORMAT(`date_shrink`, '%Y'))
WHERE
 date_shrink BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_shrink`, '%Y')
ORDER BY date, actual_shrink;";
            
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
    DATE_FORMAT(`date_roll`, '%d/%m/%Y') AS date,
    ROUND(SUM(macchi_rolls.net_weight), 2) AS actual,
    ROUND(SUM(macchi_rolls.net_weight), 2) AS actual_rolls,
    0 AS actual_shrink,
    waste.wastekgs,
    COUNT(DISTINCT (DATE_FORMAT(`date_roll`, '%d/%m/%Y'))) AS days,
    target_waste,
    target,
    capacity
FROM
    macchi_rolls
LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%Y/%m/%d') AS date,
            SUM(waste) AS wastekgs
    FROM
        `waste`
    WHERE
        machine_id = 5 AND type = 1
            AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d')
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y/%m/%d')
        LEFT JOIN
    (SELECT 
        DATE_FORMAT(`target_orders`.`date`, '%Y/%m/%d') AS date,
            SUM(target_order) AS target
    FROM
        `target_orders`
    WHERE
        machine_id = 5
            AND date BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`target_orders`.`date`, '%Y/%m/%d')
    ORDER BY `target_orders`.`date`) targets ON targets.date = DATE_FORMAT(`date_roll`, '%Y/%m/%d')
        LEFT JOIN
    (SELECT 
        `settings`.value_setting AS target_waste,
            `settings`.to,
            `settings`.from
    FROM
        `settings`
    WHERE
        `settings`.machine_id = 5
            AND `settings`.name_setting = 'waste') waste_target ON waste_target.`from` <= DATE_FORMAT(`date_roll`, '%Y/%m/%d')
        AND (waste_target.`to` IS NULL
        OR waste_target.`to` > DATE_FORMAT(`date_roll`, '%Y/%m/%d'))
        LEFT JOIN
    (SELECT 
        `settings`.value_setting AS capacity,
            `settings`.to,
            `settings`.from
    FROM
        `settings`
    WHERE
        `settings`.machine_id = 5
            AND `settings`.name_setting = 'targetRolls') capacity_rolls ON capacity_rolls.`from` <= DATE_FORMAT(`date_roll`, '%Y/%m/%d')
        AND (capacity_rolls.`to` IS NULL
        OR capacity_rolls.`to` > DATE_FORMAT(`date_roll`, '%Y/%m/%d'))
      
WHERE
    date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59' 
GROUP BY DATE_FORMAT(`date_roll`, '%d/%m/%Y') 
UNION ALL 
SELECT 
    DATE_FORMAT(`date_shrink`, '%d/%m/%Y') AS date,
    ROUND(SUM(net_weight),2) AS actual,
    0 AS actual_rolls,
    ROUND(SUM(net_weight),2) AS actual_shrink,
    waste.wastekgs,
    COUNT(DISTINCT (DATE_FORMAT(`date_shrink`, '%d/%m/%Y'))) AS days,
    target_waste,
    target,
    capacity
FROM
        `macchi_shrink`
    LEFT JOIN
    (SELECT 
        DATE_FORMAT(`date_waste`, '%Y/%m/%d') AS date,
            SUM(waste) AS wastekgs
    FROM
        `waste`
    WHERE
        machine_id = 5 AND type = 2
            AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d')
    ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_shrink`, '%Y/%m/%d')
        LEFT JOIN
    (SELECT 
        DATE_FORMAT(`target_orders`.`date`, '%Y/%m/%d') AS date,
            SUM(target_order) AS target
    FROM
        `target_orders`
    WHERE
        machine_id = 5
            AND date BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
    GROUP BY DATE_FORMAT(`target_orders`.`date`, '%Y/%m/%d')
    ORDER BY `target_orders`.`date`) targets ON targets.date = DATE_FORMAT(`date_shrink`, '%Y/%m/%d')
        LEFT JOIN
    (SELECT 
        `settings`.value_setting AS target_waste,
            `settings`.to,
            `settings`.from
    FROM
        `settings`
    WHERE
        `settings`.machine_id = 5
            AND `settings`.name_setting = 'waste') waste_target ON waste_target.`from` <= DATE_FORMAT(`date_shrink`, '%Y/%m/%d')
        AND (waste_target.`to` IS NULL
        OR waste_target.`to` > DATE_FORMAT(`date_shrink`, '%Y/%m/%d'))
      LEFT JOIN
    (SELECT 
        `settings`.value_setting AS capacity,
            `settings`.to,
            `settings`.from
    FROM
        `settings`
    WHERE
        `settings`.machine_id = 5
            AND `settings`.name_setting = 'targetShrink') capacity_shrink ON capacity_shrink.`from` <= DATE_FORMAT(`date_shrink`, '%Y/%m/%d')
        AND (capacity_shrink.`to` IS NULL
        OR capacity_shrink.`to` > DATE_FORMAT(`date_shrink`, '%Y/%m/%d'))
WHERE
 date_shrink BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_shrink`, '%Y/%m/%d')
ORDER BY date, actual_shrink;";
            
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
				if(is_null($row['target']))
                {
                    $TARGET = $CAPACITYROW;
					$EFF = round($ACTUAL *100/ $CAPACITYROW, 2);
                }
                else
                {
					$EFF = round($ACTUAL *100/ $TARGET, 2);
                }
				$PRODUCT = 'Water pouch';
				if($row['actual_shrink'] > 0)
				{
					$PRODUCT = 'Shrink Film';
					
				}
                echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. $PRODUCT .'</td>
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
                $entrie2 = array( $row['date'], number_format($TARGETWASTE,1,'.',','));
                $entrie3 = array( $row['date'], $WASTEEFF);
                if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
                {
                    $entrie0 = array( $row['date2'], $CAPACITYROW);
					$entrie = array( $row['date2'], $TARGET);
                    $entrie1 = array( $row['date2'],$ACTUAL);
                    $entrie2 = array( $row['date2'], number_format($TARGETWASTE,1,'.',','));
                    $entrie3 = array( $row['date2'], $WASTEEFF);
                }
                array_push($a,$entrie);
				if($row['actual_shrink'] == 0)
				{
                	array_push($b,$entrie1);
				}
				else
				{
                	array_push($f,$entrie1);
				}
                array_push($c,$entrie2);
				if($row['actual_shrink'] == 0)
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
         echo '</tbody>'; echo '<script>document.getElementById("chartContainer").style= "height:200px;";</script>';
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
		      name: "Water Pouch",';
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
		      name: "Shrink Film",';
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
		      name: "Water Pouch",';
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
		      name: "Shrink Film",';
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
		$datesPouch = array();
		$datesShrink = array();
		$sql2 = "SELECT distinct(`to`) as date 
				FROM `macchi_formulas`
				WHERE `macchi_formulas`.`from` <= '". $newDateString2 ."' AND `macchi_formulas`.`to` >= '". $newDateString ."' AND `macchi_formulas`.`to` <= '". $newDateString2 ."' AND product = 1
				ORDER BY `to` DESC";
		if($stmt2 = $this->_db->prepare($sql2))
        {
            $stmt2->execute();
			$dateBefore = $newDateString2;
            while($row2 = $stmt2->fetch())
            {
				$dateArray=array($row2['date'],$dateBefore);
				array_push($datesPouch,$dateArray);
				$dateBefore = date('Y-m-d', strtotime('-1 day', strtotime($row2['date'])));
			}
			if($newDateString <= $dateBefore )
			{
				$dateArray=array($newDateString,$dateBefore);
				array_push($datesPouch,$dateArray);
			}
		}
		else
		{
			echo "Something went wrong. $db->errorInfo";
		}
		
		$sql2 = "SELECT distinct(`to`) as date 
				FROM `macchi_formulas`
				WHERE `macchi_formulas`.`from` <= '". $newDateString2 ."' AND `macchi_formulas`.`to` >= '". $newDateString ."' AND `macchi_formulas`.`to` <= '". $newDateString2 ."' AND product = 2
				ORDER BY `to` DESC";
		if($stmt2 = $this->_db->prepare($sql2))
        {
            $stmt2->execute();
			$dateBefore = $newDateString2;
            while($row2 = $stmt2->fetch())
            {
				$dateArray=array($row2['date'],$dateBefore);
				array_push($datesShrink,$dateArray);
				$dateBefore = date('Y-m-d', strtotime('-1 day', strtotime($row2['date'])));
			}
			if($newDateString <= $dateBefore )
			{
				$dateArray=array($newDateString,$dateBefore);
				array_push($datesShrink,$dateArray);
			}
		}
		else
		{
			echo "Something went wrong. $db->errorInfo";
		}
		
		for($z = count($datesPouch)-1; $z>= 0; --$z) 
		{
			$materialsTable = $this->giveFormulaFor($datesPouch[$z][1],1);
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
			echo '<th class="text-center">WATER POUCH <br/> From: '.$datesPouch[$z][0].' <br/> To: '. $datesPouch[$z][1].'</th>
				  <th class="text-center">Total Consumption <br/> (Rolls + Waste)</th>';
			for($i = 0; $i<count($materialsTable); ++$i) 
			{ 
				 echo '<th class="text-center">'. $materialsTable[$i][0] .' - '. $materialsTable[$i][1] .'<br/> ('.$materialsTable[$i][2].' %)</th>';    
			}
			echo '</tr></thead>';
			if($_POST['searchBy']==2)
			{  
				$sql = " SELECT DATE_FORMAT(`date_roll`, '%b/%Y') as date, DATE_FORMAT(`date_roll`, '%m/%Y') as date2,ROUND(SUM(net_weight),2) as actual, wastekgs
				 FROM macchi_rolls
LEFT JOIN
(   SELECT DATE_FORMAT(`date_waste`, '%m/%Y') as date, SUM(waste) as wastekgs
	FROM  `waste`
	WHERE machine_id = 5 AND type = 1 AND date_waste BETWEEN '". $datesPouch[$z][0] ." 00:00:00' AND '". $datesPouch[$z][1] ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_waste`, '%m/%Y') 
	ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%m/%Y')
 WHERE `macchi_rolls`.date_roll BETWEEN '". $datesPouch[$z][0] ." 00:00:00' AND '". $datesPouch[$z][1] ." 23:59:59'
 GROUP BY DATE_FORMAT(`date_roll`, '%m/%Y') 
 ORDER BY `date_roll`;";
			}
			else if($_POST['searchBy']==3)
			{  
				$sql = "SELECT DATE_FORMAT(`date_roll`, '%Y') as date, ROUND(SUM(net_weight),2) as actual, wastekgs
				 FROM macchi_rolls
LEFT JOIN
(   SELECT DATE_FORMAT(`date_waste`, '%Y') as date, SUM(waste) as wastekgs
	FROM  `waste`
	WHERE machine_id = 5 AND type = 1 AND date_waste BETWEEN '". $datesPouch[$z][0] ." 00:00:00' AND '". $datesPouch[$z][1] ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_waste`, '%Y') 
	ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y')
 WHERE `macchi_rolls`.date_roll BETWEEN '". $datesPouch[$z][0] ." 00:00:00' AND '". $datesPouch[$z][1] ." 23:59:59'
 GROUP BY DATE_FORMAT(`date_roll`, '%Y') 
 ORDER BY `date_roll`;";
			}
			else
			{
				$sql = " SELECT DATE_FORMAT(`date_roll`, '%d/%m/%Y') as date, ROUND(SUM(net_weight),2) as actual, wastekgs
				 FROM macchi_rolls
LEFT JOIN
(   SELECT DATE_FORMAT(`date_waste`, '%Y/%m/%d') as date, SUM(waste) as wastekgs
	FROM  `waste`
	WHERE machine_id = 5 AND type = 1 AND date_waste BETWEEN '". $datesPouch[$z][0] ." 00:00:00' AND '". $datesPouch[$z][1] ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d') 
	ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y/%m/%d')
 WHERE `macchi_rolls`.date_roll BETWEEN '". $datesPouch[$z][0] ." 00:00:00' AND '". $datesPouch[$z][1] ." 23:59:59'
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
								<td class="text-right">'. number_format($TOTAL,2,'.',',') .'</td>'  ;
								$total[0] = $total[0] + $TOTAL;
								for($i = 0; $i<count($materialsTable); ++$i) 
								{
									for($j = 0; $j<count($materials); ++$j) 
									{ 
										if($materials[$j][0][3] == $materialsTable[$i][3])
										{
											$x = $materialsTable[$i][2]/100*$TOTAL;
											$total[$i+1] = $total[$i+1] + $x;
											echo '<td class="text-right">'. number_format($x,2,'.',',') .'</td>'; 
											
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
						echo '<th class="text-right">'. number_format($total[$i],2,'.',',') .'</th>';
				 }	
				echo '</tr>
				<tr >
					  <td style="text-align:center">TOTAL BAGS</td>';
				 for($i = 0; $i<count($total); ++$i) 
					{ 
						echo '<td class="text-right">'. $this->giveBags($total[$i]) .'</td>';
				 }
				echo '</tr></tfoot></table>';
        }
		
		for($z = count($datesShrink)-1; $z>= 0; --$z) 
		{
			$materialsTable = $this->giveFormulaFor($datesShrink[$z][1],2);
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
			echo '<th class="text-center">SHRINK FILM <br/>From: '.$datesShrink[$z][0].' <br/> To: '. $datesShrink[$z][1].'</th>
				  <th class="text-center">Total Consumption <br/> (Rolls + Waste)</th>';
			for($i = 0; $i<count($materialsTable); ++$i) 
			{ 
				 echo '<th class="text-center">'. $materialsTable[$i][0] .' - '. $materialsTable[$i][1] .'<br/> ('.$materialsTable[$i][2].' %)</th>';    
			}
			echo '</tr></thead>';
			if($_POST['searchBy']==2)
			{  
				$sql = " SELECT DATE_FORMAT(`date_roll`, '%b/%Y') as date, DATE_FORMAT(`date_roll`, '%m/%Y') as date2, ROUND(SUM(net_weight),2) as actual, wastekgs
FROM macchi_shrink
LEFT JOIN
(   SELECT DATE_FORMAT(`date_waste`, '%m/%Y') as date, SUM(waste) as wastekgs
	FROM  `waste`
	WHERE machine_id = 5 AND type =2 AND date_waste BETWEEN '". $datesShrink[$z][0] ." 00:00:00' AND '". $datesShrink[$z][1] ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_waste`, '%m/%Y') 
	ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_shrink`, '%m/%Y')
 WHERE date_shrink BETWEEN '". $datesShrink[$z][0] ." 00:00:00' AND '". $datesShrink[$z][1] ." 23:59:59'
 GROUP BY DATE_FORMAT(`date_shrink`, '%m/%Y') 
 ORDER BY `date_shrink`;";
			}
			else if($_POST['searchBy']==3)
			{  
				$sql = "SELECT DATE_FORMAT(`date_shrink`, '%Y') as date, ROUND(SUM(net_weight),2) as actual, wastekgs
FROM macchi_shrink
LEFT JOIN
(   SELECT DATE_FORMAT(`date_waste`, '%Y') as date, SUM(waste) as wastekgs
	FROM  `waste`
	WHERE machine_id = 5 AND type =2 AND date_waste BETWEEN '". $datesShrink[$z][0] ." 00:00:00' AND '". $datesShrink[$z][1] ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_waste`, '%Y') 
	ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_shrink`, '%Y')
 WHERE date_shrink BETWEEN '". $datesShrink[$z][0] ." 00:00:00' AND '". $datesShrink[$z][1] ." 23:59:59'
 GROUP BY DATE_FORMAT(`date_shrink`, '%Y') 
 ORDER BY `date_shrink`;";
			}
			else
			{
				$sql = " SELECT DATE_FORMAT(`date_shrink`, '%d/%m/%Y') as date, ROUND(SUM(net_weight),2) as actual, wastekgs
FROM macchi_shrink
LEFT JOIN
(   SELECT DATE_FORMAT(`date_waste`, '%Y/%m/%d') as date, SUM(waste) as wastekgs
	FROM  `waste`
	WHERE machine_id = 5 AND type =2 AND date_waste BETWEEN '". $datesShrink[$z][0] ." 00:00:00' AND '". $datesShrink[$z][1] ." 23:59:59'
	GROUP BY DATE_FORMAT(`date_waste`, '%Y/%m/%d') 
	ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_shrink`, '%Y/%m/%d')
 WHERE date_shrink BETWEEN '". $datesShrink[$z][0] ." 00:00:00' AND '". $datesShrink[$z][1] ." 23:59:59'
 GROUP BY DATE_FORMAT(`date_shrink`, '%d/%m/%Y') 
 ORDER BY `date_shrink`;";
			}
				if($stmt = $this->_db->prepare($sql))
				{
					$stmt->execute();
					while($row = $stmt->fetch())
					{
						$TOTAL = $row['actual'] + $row['wastekgs'];
						
						$entro = false;
						for($i = 0; $i<count($a) and !$entro; ++$i)
						{
							if($a[$i][0] == $row['date'])
							{
								$a[$i][1] = $a[$i][1] + $TOTAL;
								$entro = true;
							}
							if($_POST['searchBy']==2 and $a[$i][0] == $row['date2']) 
							{
								$a[$i][1] = $a[$i][1] + $TOTAL;
								$entro = true;
							}
						}
						if(!$entro)
						{
							$entrie = array( $row['date'], $TOTAL);
							if($_POST['searchBy']==2)
							{
								$entrie = array( $row['date2'], $TOTAL);
							}
							array_push($a,$entrie);
						}
						
						
						echo '<tr>
								<td class="text-right">'. $row['date'] .'</td>
								<td class="text-right">'. number_format($TOTAL,2,'.',',') .'</td>'  ;
								$total[0] = $total[0] + $TOTAL;
								for($i = 0; $i<count($materialsTable); ++$i) 
								{
									for($j = 0; $j<count($materials); ++$j) 
									{ 
										if($materials[$j][0][3] == $materialsTable[$i][3])
										{
											$x = $materialsTable[$i][2]/100*$TOTAL;
											$total[$i+1] = $total[$i+1] + $x;
											echo '<td class="text-right">'. number_format($x,2,'.',',') .'</td>'; 
											
											$entro = false;
											for($k = 0; $k<count($materials[$j][1]) and !$entro; ++$k)
											{
												if($materials[$j][1][$k][0] == $row['date'])
												{
													$materials[$j][1][$k][1] = $materials[$j][1][$k][1] + $x;
													$entro = true;
												}
												if($_POST['searchBy']==2 and $materials[$j][1][$k][0] == $row['date2']) 
												{
													$materials[$j][1][$k][1] = $materials[$j][1][$k][1] + $x;
													$entro = true;
												}
											}
											if(!$entro)
											{
												$entrie = array( $row['date'], $x);
												if($_POST['searchBy']==2)
												{
													$entrie = array( $row['date2'], $x);
												}
												array_push($materials[$j][1],$entrie);
											}
											
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
						echo '<th class="text-right">'. number_format($total[$i],2,'.',',') .'</th>';
				 }	
				echo '</tr></tfoot></table>';
        }
		
		
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
                    $x = $value[1];
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
    
	 public function giveLayername($layer)
    {
        $layername = "";
        if($layer == 1)
        {
            $layername = "A";
        }
        else if($layer == 2)
        {
            $layername = "B";
        }
        else if($layer == 3)
        {
            $layername = "C";
        }
		else if($layer == 4)
        {
            $layername = "D";
        }
		else if($layer == 5)
        {
            $layername = "E";
        }
        return $layername;
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
            $sizename = "680 mm";
        }
        else if($size == 2)
        {
            $sizename = "1010 mm";
        }
        return $sizename;
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
        echo '<th>Consumed</th>';
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
			datereport,	@a AS opening, received, consumed,difference,
			@a:=@a + received - consumed  + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(received) as received, SUM(consumed) as consumed, SUM(difference) as difference
			FROM
			(
				SELECT 
    DATE_FORMAT(`stock_materials_transfers`.`date_required`,
            '%Y-%m-%d') AS datereport,
    SUM(`stock_materials_transfers`.bags_receipt * materials.kgs_bag) AS received,
    ROUND(COALESCE(rolls.net, 0) + COALESCE(shrink.net, 0) + COALESCE(macchi_waste.waste, 0),
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
        macchi_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) rolls ON DATE_FORMAT(rolls.`date_roll`, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
            '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_shrink, SUM(net_weight) AS net
    FROM
        macchi_shrink
    GROUP BY DATE_FORMAT(`date_shrink`, '%Y-%m-%d')) shrink ON DATE_FORMAT(shrink.`date_shrink`, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
            '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) macchi_waste ON DATE_FORMAT(macchi_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
            '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_balance,
            SUM(difference * materials.kgs_bag) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
            '%Y-%m-%d')
WHERE
    `stock_materials_transfers`.machine_to = 5
GROUP BY DATE_FORMAT(`stock_materials_transfers`.`date_required`,
        '%Y-%m-%d') 
UNION ALL SELECT 
    DATE_FORMAT(macchi_rolls.date_roll, '%Y-%m-%d') AS datereport,
    0 AS received,
    ROUND(SUM(net_weight) + COALESCE(shrink.net, 0) + COALESCE(macchi_waste.waste, 0),
            2) AS consumed,
    ROUND(COALESCE(stock_balance.difference, 0), 2) AS difference
FROM
    macchi_rolls
        LEFT JOIN
    `stock_materials_transfers` ON machine_to = 5
        AND DATE_FORMAT(`date_required`, '%Y-%m-%d') = DATE_FORMAT(macchi_rolls.`date_roll`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_shrink, SUM(net_weight) AS net
    FROM
        macchi_shrink
    GROUP BY DATE_FORMAT(`date_shrink`, '%Y-%m-%d')) shrink ON DATE_FORMAT(shrink.`date_shrink`, '%Y-%m-%d') = DATE_FORMAT(macchi_rolls.`date_roll`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) macchi_waste ON DATE_FORMAT(macchi_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(macchi_rolls.`date_roll`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_balance,
            SUM(difference * materials.kgs_bag) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(macchi_rolls.`date_roll`,
            '%Y-%m-%d')
WHERE
   `stock_materials_transfers`.machine_to IS NULL
GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')

UNION ALL SELECT 
    DATE_FORMAT(macchi_shrink.date_shrink, '%Y-%m-%d') AS datereport,
    0 AS received,
    ROUND(SUM(net_weight) + COALESCE(macchi_waste.waste, 0),
            2) AS consumed,
    ROUND(COALESCE(stock_balance.difference, 0), 2) AS difference
FROM
    macchi_shrink
	LEFT JOIN `stock_materials_transfers` ON machine_to = 5
        AND DATE_FORMAT(`date_required`, '%Y-%m-%d') = DATE_FORMAT(`date_shrink`, '%Y-%m-%d')
	LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) macchi_waste ON DATE_FORMAT(macchi_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(`date_shrink`, '%Y-%m-%d')
    LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        macchi_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) rolls ON DATE_FORMAT(rolls.`date_roll`, '%Y-%m-%d') = DATE_FORMAT(`date_shrink`, '%Y-%m-%d')    
        LEFT JOIN
    (SELECT 
        date_balance,
            SUM(difference * materials.kgs_bag) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(`date_shrink`, '%Y-%m-%d')
WHERE
   `stock_materials_transfers`.machine_to IS NULL 
        AND rolls.net IS NULL
GROUP BY DATE_FORMAT(`date_shrink`, '%Y-%m-%d')

UNION ALL 


SELECT 
    DATE_FORMAT(`date_balance`,
            '%Y-%m-%d') AS datereport,
    0 AS received,
    0 AS consumed,
    SUM(difference * materials.kgs_bag) AS difference
FROM
    stock_balance
JOIN
    `materials` ON `stock_balance`.material_id = materials.material_id
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        macchi_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) rolls ON DATE_FORMAT(rolls.`date_roll`, '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_shrink, SUM(net_weight) AS net
    FROM
        macchi_shrink
    GROUP BY DATE_FORMAT(`date_shrink`, '%Y-%m-%d')) shrink ON DATE_FORMAT(shrink.`date_shrink`, '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) macchi_waste ON DATE_FORMAT(macchi_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')
	LEFT JOIN
    (SELECT 
        date_required,
            SUM(bags_receipt * materials.kgs_bag) AS received
    FROM
        stock_materials_transfers
    JOIN `materials` ON stock_materials_transfers.material_id = materials.material_id
    WHERE
        machine_to = 5
    GROUP BY DATE_FORMAT(`date_required`, '%Y-%m-%d')) received ON DATE_FORMAT(received.`date_required`, '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')

WHERE machine_id = 5 AND received.received IS NULL 
        AND rolls.net IS NULL AND shrink.net IS NULL 
GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d') 


UNION ALL

SELECT dateTable.selected_date AS datereport,
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
        macchi_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) rolls ON DATE_FORMAT(rolls.`date_roll`, '%Y-%m-%d') = dateTable.selected_date
        LEFT JOIN
    (SELECT 
        date_shrink, SUM(net_weight) AS net
    FROM
        macchi_shrink
    GROUP BY DATE_FORMAT(`date_shrink`, '%Y-%m-%d')) shrink ON DATE_FORMAT(shrink.`date_shrink`, '%Y-%m-%d') = dateTable.selected_date
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) macchi_waste ON DATE_FORMAT(macchi_waste.`date_waste`, '%Y-%m-%d') = dateTable.selected_date
	LEFT JOIN
    (SELECT 
        date_required,
            SUM(bags_receipt * materials.kgs_bag) AS received
    FROM
        stock_materials_transfers
    JOIN `materials` ON stock_materials_transfers.material_id = materials.material_id
    WHERE
        machine_to = 5
    GROUP BY DATE_FORMAT(`date_required`, '%Y-%m-%d')) received ON DATE_FORMAT(received.`date_required`, '%Y-%m-%d') = dateTable.selected_date
	   LEFT JOIN
    (SELECT 
        date_balance,
            SUM(difference * materials.kgs_bag) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = dateTable.selected_date
WHERE selected_date <= '". $newDateString2 ."' AND received.received IS NULL 
        AND rolls.net IS NULL AND shrink.net IS NULL AND stock_balance.difference IS NULL
ORDER BY datereport
            ) movements GROUP BY DATE_FORMAT(datereport, '%m/%Y') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
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
			
			$sql= "SELECT DATE_FORMAT(`datereport`, '%Y') AS datereport, opening, received, consumed, difference, closing
FROM
	(
    SELECT 
			datereport,	@a AS opening, received, consumed,difference,
			@a:=@a + received - consumed  + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(received) as received, SUM(consumed) as consumed, SUM(difference) as difference
			FROM
			(
				SELECT 
    DATE_FORMAT(`stock_materials_transfers`.`date_required`,
            '%Y-%m-%d') AS datereport,
    SUM(`stock_materials_transfers`.bags_receipt * materials.kgs_bag) AS received,
    ROUND(COALESCE(rolls.net, 0) + COALESCE(shrink.net, 0) + COALESCE(macchi_waste.waste, 0),
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
        macchi_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) rolls ON DATE_FORMAT(rolls.`date_roll`, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
            '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_shrink, SUM(net_weight) AS net
    FROM
        macchi_shrink
    GROUP BY DATE_FORMAT(`date_shrink`, '%Y-%m-%d')) shrink ON DATE_FORMAT(shrink.`date_shrink`, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
            '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) macchi_waste ON DATE_FORMAT(macchi_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
            '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_balance,
            SUM(difference * materials.kgs_bag) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
            '%Y-%m-%d')
WHERE
    `stock_materials_transfers`.machine_to = 5
GROUP BY DATE_FORMAT(`stock_materials_transfers`.`date_required`,
        '%Y-%m-%d') 
UNION ALL SELECT 
    DATE_FORMAT(macchi_rolls.date_roll, '%Y-%m-%d') AS datereport,
    0 AS received,
    ROUND(SUM(net_weight) + COALESCE(shrink.net, 0) + COALESCE(macchi_waste.waste, 0),
            2) AS consumed,
    ROUND(COALESCE(stock_balance.difference, 0), 2) AS difference
FROM
    macchi_rolls
        LEFT JOIN
    `stock_materials_transfers` ON machine_to = 5
        AND DATE_FORMAT(`date_required`, '%Y-%m-%d') = DATE_FORMAT(macchi_rolls.`date_roll`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_shrink, SUM(net_weight) AS net
    FROM
        macchi_shrink
    GROUP BY DATE_FORMAT(`date_shrink`, '%Y-%m-%d')) shrink ON DATE_FORMAT(shrink.`date_shrink`, '%Y-%m-%d') = DATE_FORMAT(macchi_rolls.`date_roll`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) macchi_waste ON DATE_FORMAT(macchi_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(macchi_rolls.`date_roll`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_balance,
            SUM(difference * materials.kgs_bag) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(macchi_rolls.`date_roll`,
            '%Y-%m-%d')
WHERE
   `stock_materials_transfers`.machine_to IS NULL
GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')

UNION ALL SELECT 
    DATE_FORMAT(macchi_shrink.date_shrink, '%Y-%m-%d') AS datereport,
    0 AS received,
    ROUND(SUM(net_weight) + COALESCE(macchi_waste.waste, 0),
            2) AS consumed,
    ROUND(COALESCE(stock_balance.difference, 0), 2) AS difference
FROM
    macchi_shrink
	LEFT JOIN `stock_materials_transfers` ON machine_to = 5
        AND DATE_FORMAT(`date_required`, '%Y-%m-%d') = DATE_FORMAT(`date_shrink`, '%Y-%m-%d')
	LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) macchi_waste ON DATE_FORMAT(macchi_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(`date_shrink`, '%Y-%m-%d')
    LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        macchi_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) rolls ON DATE_FORMAT(rolls.`date_roll`, '%Y-%m-%d') = DATE_FORMAT(`date_shrink`, '%Y-%m-%d')    
        LEFT JOIN
    (SELECT 
        date_balance,
            SUM(difference * materials.kgs_bag) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(`date_shrink`, '%Y-%m-%d')
WHERE
   `stock_materials_transfers`.machine_to IS NULL 
        AND rolls.net IS NULL
GROUP BY DATE_FORMAT(`date_shrink`, '%Y-%m-%d')

UNION ALL 


SELECT 
    DATE_FORMAT(`date_balance`,
            '%Y-%m-%d') AS datereport,
    0 AS received,
    0 AS consumed,
    SUM(difference * materials.kgs_bag) AS difference
FROM
    stock_balance
JOIN
    `materials` ON `stock_balance`.material_id = materials.material_id
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        macchi_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) rolls ON DATE_FORMAT(rolls.`date_roll`, '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_shrink, SUM(net_weight) AS net
    FROM
        macchi_shrink
    GROUP BY DATE_FORMAT(`date_shrink`, '%Y-%m-%d')) shrink ON DATE_FORMAT(shrink.`date_shrink`, '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) macchi_waste ON DATE_FORMAT(macchi_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')
	LEFT JOIN
    (SELECT 
        date_required,
            SUM(bags_receipt * materials.kgs_bag) AS received
    FROM
        stock_materials_transfers
    JOIN `materials` ON stock_materials_transfers.material_id = materials.material_id
    WHERE
        machine_to = 5
    GROUP BY DATE_FORMAT(`date_required`, '%Y-%m-%d')) received ON DATE_FORMAT(received.`date_required`, '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')

WHERE machine_id = 5 AND received.received IS NULL 
        AND rolls.net IS NULL AND shrink.net IS NULL 
GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d') 


UNION ALL

SELECT dateTable.selected_date AS datereport,
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
        macchi_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) rolls ON DATE_FORMAT(rolls.`date_roll`, '%Y-%m-%d') = dateTable.selected_date
        LEFT JOIN
    (SELECT 
        date_shrink, SUM(net_weight) AS net
    FROM
        macchi_shrink
    GROUP BY DATE_FORMAT(`date_shrink`, '%Y-%m-%d')) shrink ON DATE_FORMAT(shrink.`date_shrink`, '%Y-%m-%d') = dateTable.selected_date
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) macchi_waste ON DATE_FORMAT(macchi_waste.`date_waste`, '%Y-%m-%d') = dateTable.selected_date
	LEFT JOIN
    (SELECT 
        date_required,
            SUM(bags_receipt * materials.kgs_bag) AS received
    FROM
        stock_materials_transfers
    JOIN `materials` ON stock_materials_transfers.material_id = materials.material_id
    WHERE
        machine_to = 5
    GROUP BY DATE_FORMAT(`date_required`, '%Y-%m-%d')) received ON DATE_FORMAT(received.`date_required`, '%Y-%m-%d') = dateTable.selected_date
	   LEFT JOIN
    (SELECT 
        date_balance,
            SUM(difference * materials.kgs_bag) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = dateTable.selected_date
WHERE selected_date <= '". $newDateString2 ."' AND received.received IS NULL 
        AND rolls.net IS NULL AND shrink.net IS NULL AND stock_balance.difference IS NULL
ORDER BY datereport
            ) movements GROUP BY DATE_FORMAT(datereport, '%Y') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
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
			
						$sql= "SELECT DATE_FORMAT(`datereport`, '%Y-%m-%d') AS datereport, opening, received, consumed, difference, closing
FROM
	(
    SELECT 
			datereport,	@a AS opening, received, consumed,difference,
			@a:=@a + received - consumed  + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(received) as received, SUM(consumed) as consumed, SUM(difference) as difference
			FROM
			(
				SELECT 
    DATE_FORMAT(`stock_materials_transfers`.`date_required`,
            '%Y-%m-%d') AS datereport,
    SUM(`stock_materials_transfers`.bags_receipt * materials.kgs_bag) AS received,
    ROUND(COALESCE(rolls.net, 0) + COALESCE(shrink.net, 0) + COALESCE(macchi_waste.waste, 0),
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
        macchi_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) rolls ON DATE_FORMAT(rolls.`date_roll`, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
            '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_shrink, SUM(net_weight) AS net
    FROM
        macchi_shrink
    GROUP BY DATE_FORMAT(`date_shrink`, '%Y-%m-%d')) shrink ON DATE_FORMAT(shrink.`date_shrink`, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
            '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) macchi_waste ON DATE_FORMAT(macchi_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
            '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_balance,
            SUM(difference * materials.kgs_bag) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
            '%Y-%m-%d')
WHERE
    `stock_materials_transfers`.machine_to = 5
GROUP BY DATE_FORMAT(`stock_materials_transfers`.`date_required`,
        '%Y-%m-%d') 
UNION ALL SELECT 
    DATE_FORMAT(macchi_rolls.date_roll, '%Y-%m-%d') AS datereport,
    0 AS received,
    ROUND(SUM(net_weight) + COALESCE(shrink.net, 0) + COALESCE(macchi_waste.waste, 0),
            2) AS consumed,
    ROUND(COALESCE(stock_balance.difference, 0), 2) AS difference
FROM
    macchi_rolls
        LEFT JOIN
    `stock_materials_transfers` ON machine_to = 5
        AND DATE_FORMAT(`date_required`, '%Y-%m-%d') = DATE_FORMAT(macchi_rolls.`date_roll`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_shrink, SUM(net_weight) AS net
    FROM
        macchi_shrink
    GROUP BY DATE_FORMAT(`date_shrink`, '%Y-%m-%d')) shrink ON DATE_FORMAT(shrink.`date_shrink`, '%Y-%m-%d') = DATE_FORMAT(macchi_rolls.`date_roll`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) macchi_waste ON DATE_FORMAT(macchi_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(macchi_rolls.`date_roll`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_balance,
            SUM(difference * materials.kgs_bag) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(macchi_rolls.`date_roll`,
            '%Y-%m-%d')
WHERE
   `stock_materials_transfers`.machine_to IS NULL
GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')

UNION ALL SELECT 
    DATE_FORMAT(macchi_shrink.date_shrink, '%Y-%m-%d') AS datereport,
    0 AS received,
    ROUND(SUM(net_weight) + COALESCE(macchi_waste.waste, 0),
            2) AS consumed,
    ROUND(COALESCE(stock_balance.difference, 0), 2) AS difference
FROM
    macchi_shrink
	LEFT JOIN `stock_materials_transfers` ON machine_to = 5
        AND DATE_FORMAT(`date_required`, '%Y-%m-%d') = DATE_FORMAT(`date_shrink`, '%Y-%m-%d')
	LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) macchi_waste ON DATE_FORMAT(macchi_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(`date_shrink`, '%Y-%m-%d')
    LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        macchi_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) rolls ON DATE_FORMAT(rolls.`date_roll`, '%Y-%m-%d') = DATE_FORMAT(`date_shrink`, '%Y-%m-%d')    
        LEFT JOIN
    (SELECT 
        date_balance,
            SUM(difference * materials.kgs_bag) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = DATE_FORMAT(`date_shrink`, '%Y-%m-%d')
WHERE
   `stock_materials_transfers`.machine_to IS NULL 
        AND rolls.net IS NULL
GROUP BY DATE_FORMAT(`date_shrink`, '%Y-%m-%d')

UNION ALL 


SELECT 
    DATE_FORMAT(`date_balance`,
            '%Y-%m-%d') AS datereport,
    0 AS received,
    0 AS consumed,
    SUM(difference * materials.kgs_bag) AS difference
FROM
    stock_balance
JOIN
    `materials` ON `stock_balance`.material_id = materials.material_id
        LEFT JOIN
    (SELECT 
        date_roll, SUM(net_weight) AS net
    FROM
        macchi_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) rolls ON DATE_FORMAT(rolls.`date_roll`, '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_shrink, SUM(net_weight) AS net
    FROM
        macchi_shrink
    GROUP BY DATE_FORMAT(`date_shrink`, '%Y-%m-%d')) shrink ON DATE_FORMAT(shrink.`date_shrink`, '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) macchi_waste ON DATE_FORMAT(macchi_waste.`date_waste`, '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')
	LEFT JOIN
    (SELECT 
        date_required,
            SUM(bags_receipt * materials.kgs_bag) AS received
    FROM
        stock_materials_transfers
    JOIN `materials` ON stock_materials_transfers.material_id = materials.material_id
    WHERE
        machine_to = 5
    GROUP BY DATE_FORMAT(`date_required`, '%Y-%m-%d')) received ON DATE_FORMAT(received.`date_required`, '%Y-%m-%d') = DATE_FORMAT(`date_balance`, '%Y-%m-%d')

WHERE machine_id = 5 AND received.received IS NULL 
        AND rolls.net IS NULL AND shrink.net IS NULL 
GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d') 


UNION ALL

SELECT dateTable.selected_date AS datereport,
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
        macchi_rolls
    GROUP BY DATE_FORMAT(`date_roll`, '%Y-%m-%d')) rolls ON DATE_FORMAT(rolls.`date_roll`, '%Y-%m-%d') = dateTable.selected_date
        LEFT JOIN
    (SELECT 
        date_shrink, SUM(net_weight) AS net
    FROM
        macchi_shrink
    GROUP BY DATE_FORMAT(`date_shrink`, '%Y-%m-%d')) shrink ON DATE_FORMAT(shrink.`date_shrink`, '%Y-%m-%d') = dateTable.selected_date
        LEFT JOIN
    (SELECT 
        date_waste, SUM(waste) AS waste
    FROM
        waste
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_waste`, '%Y-%m-%d')) macchi_waste ON DATE_FORMAT(macchi_waste.`date_waste`, '%Y-%m-%d') = dateTable.selected_date
	LEFT JOIN
    (SELECT 
        date_required,
            SUM(bags_receipt * materials.kgs_bag) AS received
    FROM
        stock_materials_transfers
    JOIN `materials` ON stock_materials_transfers.material_id = materials.material_id
    WHERE
        machine_to = 5
    GROUP BY DATE_FORMAT(`date_required`, '%Y-%m-%d')) received ON DATE_FORMAT(received.`date_required`, '%Y-%m-%d') = dateTable.selected_date
	   LEFT JOIN
    (SELECT 
        date_balance,
            SUM(difference * materials.kgs_bag) AS difference
    FROM
        stock_balance
    JOIN `materials` ON stock_balance.material_id = materials.material_id
    WHERE
        machine_id = 5
    GROUP BY DATE_FORMAT(`date_balance`, '%Y-%m-%d')) stock_balance ON DATE_FORMAT(stock_balance.`date_balance`, '%Y-%m-%d') = dateTable.selected_date
WHERE selected_date <= '". $newDateString2 ."' AND received.received IS NULL 
        AND rolls.net IS NULL AND shrink.net IS NULL AND stock_balance.difference IS NULL
ORDER BY datereport
            ) movements GROUP BY DATE_FORMAT(datereport, '%Y-%m-%d') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
WHERE datereport BETWEEN '". $newDateString ."' AND '". $newDateString2 ."'
ORDER BY report.datereport";

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


				$DIFF = '<td class="text-right">'. number_format((float) $row['difference'],0,'.',',') .'</td>';
				if($row['difference'] != 0)
				{
					$DIFF = '<th class="text-right text-danger">'. number_format((float) $row['difference'],0,'.',',') .'</th>';
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
    
	
	
}


?>