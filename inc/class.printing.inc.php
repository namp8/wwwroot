<?php

/**
 * Handles user interactions within the printing
 *
 * PHP version 5
 *
 * @author Natalia Montañez
 * @copyright 2017 Natalia Montañez
 *
 */
class Printing
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
    public function machinesDropdown()
    {
        $sql = "SELECT `machines`.`machine_id`,`machines`.`machine_name`
                FROM  `machines`
                WHERE location_id = 3 AND machine_id <> 33
                ORDER BY `machines`.`machine_name`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['machine_id'];
                $NAME = $row['machine_name'];
                echo  '<li><a id="'. $NAME .'" onclick="selectMachine(\''. $ID .'\',\''. $NAME .'\')">'. $NAME .'</a></li>'; 
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
    public function materialsDropdown()
    {
        $sql = "SELECT `materials`.`material_id`,`materials`.`material_name`,`materials`.`material_grade`
                FROM  `materials`
				WHERE `printing` = 1 AND `color` = 1
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
    
	
    /**
     * Loads the dropdown of all the materials
     *
     * This function outputs <li> tags with materials
     */
    public function consumablesDropdown()
    {
        $sql = "SELECT `materials`.`material_id`,`materials`.`material_name`,`materials`.`material_grade`
                FROM  `materials`
				WHERE `printing` = 1 AND `consumables` = 1
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
    public function noColorsDropdown()
    {
        $sql = "SELECT `materials`.`material_id`,`materials`.`material_name`,`materials`.`material_grade`
                FROM  `materials`
                WHERE location_id = 3 AND color = 0
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
    
    /**
     * Loads the list all the rolls in the multilayer section
     * This function outputs <li> tags with the rolls
     * Param $x is the machine
     */
    public function giveRollsMultilayerDropdown($x, $i)
    {
        $sql = "SELECT `multilayer_rolls`.`multilayer_rolls_id`, `multilayer_rolls`.`rollno`, gross_weight, net_weight,     `multilayer_rolls`.`size`
                FROM  `multilayer_rolls`
                WHERE status_roll = 0 AND `multilayer_rolls`.`size`= (SELECT size FROM machines WHERE machine_id = ". $x .") ;";
        $SIZE = 0;
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['multilayer_rolls_id'];
                $ROLLNO = $row['rollno'];
                $GROSS = $row['gross_weight'];
                $NET = $row['net_weight'];
                $SIZE = $row['size'];
                echo  '<li><a id="'. $ROLLNO .'" onclick="selectRoll(\''. $i .'\',\''. $ID .'\',\''. $ROLLNO .'\',\''. $GROSS .'\',\''. $NET .'\')">'. $ROLLNO .'</a></li>'; 
            }
            
			
            $stmt->closeCursor();
        }
        else
        {
            echo "Something went wrong. ". $db->errorInfo;
        }
        echo '<script>document.getElementById("size").value = "'. $SIZE .'";</script>';
        echo '<script>document.getElementById("sizeName").value = "'.$this->giveSizename($SIZE).'";</script>';
        $name = "";
        if($SIZE == 1)
        {
            $name = "680cone";
        }
        else if($SIZE == 2)
        {
            $name = "1010cone";
        }

        $sql = "SELECT `settings`.`value_setting`
                FROM `settings`
                WHERE machine_id=33 AND `name_setting` = '". $name ."' ";

        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {  
                $CONE = $row['value_setting'];
                 echo '<script>document.getElementById("cone").value = "'. $CONE .'";</script>';
            }
            $stmt->closeCursor();
        }
        else
        {
            echo "Something went wrong. ". $db->errorInfo;
        }
    }
	
	
    /**
     * Loads the list all the rolls in the multilayer section
     * This function outputs <li> tags with the rolls
     * Param $x is the machine
     */
    public function giveRollsMacchiDropdown($x, $i)
    {
        $sql = "SELECT `macchi_rolls_id`, `rollno`, gross_weight, net_weight,  `size`
                FROM  `macchi_rolls`
                WHERE status_roll = 0 AND `macchi_rolls`.`size`= (SELECT size FROM machines WHERE machine_id = ". $x .") ;";
        $SIZE = 0;
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['macchi_rolls_id'];
                $ROLLNO = $row['rollno'];
                $GROSS = $row['gross_weight'];
                $NET = $row['net_weight'];
                $SIZE = $row['size'];
                echo  '<li><a id="'. $ROLLNO .'" onclick="selectRoll(\''. $i .'\',\''. $ID .'\',\''. $ROLLNO .'\',\''. $GROSS .'\',\''. $NET .'\')">'. $ROLLNO .'</a></li>'; 
            }
            
           
            $stmt->closeCursor();
        }
        else
        {
            echo "Something went wrong. ". $db->errorInfo;
        }
        echo '<script>document.getElementById("size").value = "'. $SIZE .'";</script>';
        echo '<script>document.getElementById("sizeName").value = "'.$this->giveSizename($SIZE).'";</script>';
        $name = "";
        if($SIZE == 1)
        {
            $name = "680cone";
        }
        else if($SIZE == 2)
        {
            $name = "1010cone";
        }

        $sql = "SELECT `settings`.`value_setting`
                FROM `settings`
                WHERE machine_id=33 AND `name_setting` = '". $name ."' ";

        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {  
                $CONE = $row['value_setting'];
                 echo '<script>document.getElementById("cone").value = "'. $CONE .'";</script>';
            }
            $stmt->closeCursor();
        }
        else
        {
            echo "Something went wrong. ". $db->errorInfo;
        }
    }
	
	 public function giveRollsPackingDropdown($x, $i)
    {
        $sql = "SELECT `packing_rolls_id`, `rollno`, gross_weight, net_weight
FROM  `packing_rolls`
 WHERE status_roll = 0 ;";
        $SIZE = 0;
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['packing_rolls_id'];
                $ROLLNO = $row['rollno'];
                $GROSS = $row['gross_weight'];
                $NET = $row['net_weight'];
                echo  '<li><a id="'. $GROSS .'" onclick="selectRoll(\''. $i .'\',\''. $ID .'\',\''. $ROLLNO .'\',\''. $GROSS .'\',\''. $NET .'\')">'. $GROSS .'</a></li>'; 
            }
            
           
            $stmt->closeCursor();
        }
        else
        {
            echo "Something went wrong. ". $db->errorInfo;
        }
		 $SIZE = 3;
        echo '<script>document.getElementById("size").value = "'. $SIZE .'";</script>';
        echo '<script>document.getElementById("sizeName").value = "'.$this->giveSizename($SIZE).'";</script>';
        $name = "";
        if($SIZE == 1)
        {
            $name = "680cone";
        }
        else if($SIZE == 2)
        {
            $name = "1010cone";
        }
        else if($SIZE == 3)
        {
            $name = "packingcone";
        }

        $sql = "SELECT `settings`.`value_setting`
                FROM `settings`
                WHERE machine_id=33 AND `name_setting` = '". $name ."' ";

        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {  
                $CONE = $row['value_setting'];
                 echo '<script>document.getElementById("cone").value = "'. $CONE .'";</script>';
            }
            $stmt->closeCursor();
        }
        else
        {
            echo "Something went wrong. ". $db->errorInfo;
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
                WHERE location_id=3;";
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
        if($_POST['action'] == 1)
        {
            $name = "680cone";
        }
        else if($_POST['action'] == 2)
        {
            $name = "1010cone";
        }
        
        $sql = "UPDATE  `settings`
                SET `value_setting` = '". $value ."'
                WHERE location_id=3 AND `name_setting` = '". $name ."' ";
       
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
     * Checks and inserts a new customer
     *
     * @return boolean  true if can insert  false if not
     */
    public function createRoll()
    {
         
        $inputWaste = $outputWaste = $date = $rollno  = $shift = $size = $gross = $cone = $net = $user = $machine = $customer = $rollid = "";
		
		$CONESMALL = 0;
        $sql = "SELECT `settings`.`value_setting`
                FROM  `settings` 
                WHERE machine_id=33 AND name_setting='680cone';";
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
                WHERE machine_id=33 AND name_setting='1010cone';";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $CONEBIG = $row['value_setting'];
            }
        }
		
		$PACKINGCONE = 0;
        $sql = "SELECT `settings`.`value_setting`
                FROM  `settings` 
                WHERE machine_id=33 AND name_setting='packingcone';";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $PACKINGCONE = $row['value_setting'];
            }
        }
		
        $dyne = $dyne2 = $tape = 0;
        if(!empty($_POST['dyne']) )
        {
            $dyne = trim($_POST["dyne"]);
            $dyne = stripslashes($dyne);
            $dyne = htmlspecialchars($dyne);
        }
		
        if(!empty($_POST['dyne2']) )
        {
            $dyne2 = trim($_POST["dyne2"]);
            $dyne2 = stripslashes($dyne2);
            $dyne2 = htmlspecialchars($dyne2);
        }
        if(!empty($_POST['tape']) )
        {
            $tape = trim($_POST["tape"]);
            $tape = stripslashes($tape);
            $tape = htmlspecialchars($tape);
        }
        
        $inputWaste = trim($_POST["inputWaste"]);
        $inputWaste = stripslashes($inputWaste);
        $inputWaste = htmlspecialchars($inputWaste);
		
		
        $inputWaste2 = trim($_POST["inputWaste2"]);
        $inputWaste2 = stripslashes($inputWaste2);
        $inputWaste2 = htmlspecialchars($inputWaste2);
        
        $outputWaste = trim($_POST["outputWaste"]);
        $outputWaste = stripslashes($outputWaste);
        $outputWaste = htmlspecialchars($outputWaste);
        
        $rollno = trim($_POST["outputRoll"]);
        $rollno = stripslashes($rollno);
        $rollno = htmlspecialchars($rollno);
        
        
        $rollid = trim($_POST["rollid"]);
        $rollid = stripslashes($rollid);
        $rollid = htmlspecialchars($rollid);
		
		
        $rollid2 = trim($_POST["rollid2"]);
        $rollid2 = stripslashes($rollid2);
        $rollid2 = htmlspecialchars($rollid2);
        
        $shift = trim($_POST["shift"]);
        $shift = stripslashes($shift);
        $shift = htmlspecialchars($shift);
        
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
        
        $size = trim($_POST["size"]);
        $size = stripslashes($size);
        $size = htmlspecialchars($size);
        
        $gross = trim($_POST["outputRollWt"]);
        $gross = stripslashes($gross);
        $gross = htmlspecialchars($gross);
		
        $cone = $CONESMALL;
		if($size == 2)
		{
			$cone = $CONEBIG;
		}
		else if($size == 3)
		{
			$cone = $PACKINGCONE;
		}
        
        $net = $gross - $cone;

        $machine = trim($_POST["machine1"]);
        $machine = stripslashes($machine);
        $machine = htmlspecialchars($machine);
        
        $customer = trim($_POST["customer"]);
        $customer = stripslashes($customer);
        $customer = htmlspecialchars($customer);
        
		
        $from = trim($_POST["from"]);
        $from = stripslashes($from);
        $from = htmlspecialchars($from);
		
        $a=array();
        //REVISA LA FORMULA PARA LA MAQUINA
        $sql = "SELECT stock_material_id, printing_formulas.material_id, percentage, bags, kgs_bag, material_name  
                FROM `printing_formulas`
                LEFT JOIN stock_materials ON stock_materials.material_id = `printing_formulas`.material_id AND stock_materials.machine_id = ".$machine."
                LEFT JOIN materials ON printing_formulas.material_id = materials.material_id
                WHERE printing_formulas.machine_id =  ".$machine."
                ORDER BY printing_formulas.material_id;";
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['stock_material_id'];
                $PERCENTAGE = $row['percentage'];
                $NAME = $row['material_name'];
                $BAGS = $row['bags'];
                if(!is_null($ID) and is_null($BAGS))
                {
                    echo '<strong>ERROR</strong> The roll was not added to the system, because there is not material <strong>'. $row['material_name'] .'</strong> in stock.';
                    return false;
                }
                $KGS_BAGS = $row['kgs_bag'];
                $materialArray=array($ID,$PERCENTAGE,$BAGS,$KGS_BAGS,$NAME,0);
                array_push($a,$materialArray);
                
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<strong>ERROR</strong> Could not insert the roll into the database. Please try again.<br>'. $e->getMessage();
            return FALSE;
        }
        
        //REVISA LOS COLORES Y SOLVENTES EN STOCK Y LAS QUE SE NECESITAN PARA HACER EL BATCH
        $sql = "SELECT stock_material_id, stock_materials.material_id, material_name, material_grade, bags, consumption, medium, kgs_bag 
        FROM `customers_colors`
        LEFT JOIN stock_materials ON stock_materials.material_id = customers_colors.material_id AND stock_materials.machine_id = ".$machine."
        INNER JOIN materials ON stock_materials.material_id = materials.material_id
        WHERE customer_id = ".$customer.";";
        $update = "";
        if($stmt = $this->_db->prepare($sql))
         {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $kgsneeded = $net * $row['consumption'] / 100;
                $bagsneeded = $kgsneeded / $row['kgs_bag'];
                if(is_null($row['bags']))
                {
                    echo '<strong>ERROR</strong> The roll was not added to the system, because there is not material <strong>'. $row['material_name'] .' - '. $row['material_grade'] .'</strong> in stock.';
                    return false;
                }
                //LANZA ERROR SI LOS KGS ACTUALES SON MENORES A LAS QUE SE NECESITAN
                if($row['bags']<$bagsneeded)
                {
                    echo '<strong>ERROR</strong> The rolls were not added to the system, because there is not enough color <strong>'. $row['material_name'] .' - '. $row['material_grade'] .'</strong> in stock. <br> There are <strong>'. $row['bags'] .'</strong> buckets in stock, and you need <strong>'. $bagsneeded .'</strong> buckets.';
                    return false;
                }
                // VA CREANDO EL UPDATE PARA CAMBIAR DESPUES EL NUMERO DE KGS DE MULTILAYER_BATCHES_STOCK
                else
                {
                    $newbags = $row['bags']-$bagsneeded;
                    $update = $update . "UPDATE  `stock_materials` SET `bags` = ".$newbags." WHERE `stock_material_id` = ". $row['stock_material_id']. "; ";
                    $percentageColor = $a[0][1] / 100; 
                    for($i = 1; $i < count($a); ++$i) {
                        $percentageMaterial = $a[$i][1] / 100;
                        $kgs = $kgsneeded * $percentageMaterial / $percentageColor;
                        $bags = $kgs / $a[$i][3];
                        $a[$i][5] = $a[$i][5] + $bags;
                    }
                }
            }
            for($i = 1; $i < count($a); ++$i) 
            {
                if($a[$i][2]<$a[$i][5])
                {
                    echo '<strong>ERROR</strong> The rolls were not added to the system, because there is not enough material <strong>'. $a[$i][4] .'</strong> in stock. <br> There are <strong>'. $a[$i][2] .'</strong> buckets in stock, and you need <strong>'. $a[$i][5] .'</strong> buckets.';
                    return false;
                }
                else
                {
                    $newbags = $a[$i][2]-$a[$i][5];
                    $update = $update . "UPDATE  `stock_materials` SET `bags` = ".$newbags." WHERE `stock_material_id` = ". $a[$i][0]. "; ";
                }
            }
				
			if($from == 1)
			{
				$sql = "INSERT INTO `printing_rolls`(`printing_rolls_id`,`date_roll`,`rollno`,`shift`,`size`,`gross_weight`,`net_weight`,`user_id`,`status_roll`,`machine_id`,`customer_id`,`roll_id`,`roll_id2`,`tape_test`,`waste_printing`,`macchi`,`multilayer`,`packing_bags`)
				VALUES(NULL,'". $date ."','". $rollno ."',".$shift.",".$size.",".$gross .",". $net.",".$_SESSION['Userid'].",0,". $machine.",".$customer.",". $rollid .",". $rollid2 .", ". $tape.",". $outputWaste .", 0, 1,0);
				UPDATE `multilayer_rolls`
				SET `dyne_test` = ". $dyne .",
				`status_roll` = 1,
				`waste_printing` = ". $inputWaste ."
				WHERE `multilayer_rolls_id` = ". $rollid ."; ". $update;
				
				if(!is_null($rollid2))
				{
					$sql = $sql . " UPDATE `multilayer_rolls`
				SET `dyne_test` = ". $dyne2 .",
				`status_roll` = 1,
				`waste_printing` = ". $inputWaste2 ."
				WHERE `multilayer_rolls_id` = ". $rollid2 .";";
				}
			}
			else if($from == 2)
			{
				$sql = "INSERT INTO `printing_rolls`(`printing_rolls_id`,`date_roll`,`rollno`,`shift`,`size`,`gross_weight`,`net_weight`,`user_id`,`status_roll`,`machine_id`,`customer_id`,`roll_id`,`roll_id2`,`tape_test`,`waste_printing`,`macchi`,`multilayer`,`packing_bags`)
				VALUES(NULL,'". $date ."','". $rollno ."',".$shift.",".$size.",".$gross .",". $net.",".$_SESSION['Userid'].",0,". $machine.",".$customer.",". $rollid .",". $rollid2 .", ". $tape.",". $outputWaste .", 1, 0,0);
				UPDATE `macchi_rolls`
				SET `dyne_test` = ". $dyne .",
				`status_roll` = 1,
				`waste_printing` = ". $inputWaste ."
				WHERE `macchi_rolls_id` = ". $rollid ."; ". $update;
				
				if(!is_null($rollid2))
				{
					$sql = $sql . " UPDATE `macchi_rolls`
				SET `dyne_test` = ". $dyne2 .",
				`status_roll` = 1,
				`waste_printing` = ". $inputWaste2 ."
				WHERE `macchi_rolls_id` = ". $rollid2 .";";
				}
			}
			else if($from == 3)
			{
				$sql = "INSERT INTO `printing_rolls`(`printing_rolls_id`,`date_roll`,`rollno`,`shift`,`size`,`gross_weight`,`net_weight`,`user_id`,`status_roll`,`machine_id`,`customer_id`,`roll_id`,`roll_id2`,`tape_test`,`waste_printing`,`macchi`,`multilayer`,`packing_bags`)
				VALUES(NULL,'". $date ."','". $rollno ."',".$shift.",".$size.",".$gross .",". $net.",".$_SESSION['Userid'].",0,". $machine.",".$customer.",". $rollid .",". $rollid2 .", ". $tape.",". $outputWaste .", 0, 0,1);
				UPDATE `packing_rolls`
				SET `dyne_test` = ". $dyne .",
				`status_roll` = 1,
                `used_weight` = `net_weight`,
				`waste_printing` = ". $inputWaste ."
				WHERE `packing_rolls_id` = ". $rollid ."; ". $update;
				
				if(!is_null($rollid2))
				{
					$sql = $sql . " UPDATE `packing_rolls`
				SET `dyne_test` = ". $dyne2 .",
				`status_roll` = 1,
                `used_weight` = `net_weight`,
				`waste_printing` = ". $inputWaste2 ."
				WHERE `packing_rolls_id` = ". $rollid2 ."; ;";
				}
				
			}
            
            try
            {   
                $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                $stmt = $this->_db->prepare($sql);
                $stmt->execute();
                $stmt->closeCursor();

                echo '<strong>SUCCESS!</strong> The roll was successfully added to the database for the shift: <strong>'. $this->giveShiftname($shift) .'</strong>';
                return TRUE;
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                  echo '<strong>ERROR</strong> The roll has already being register for the shift: <strong>'. $this->giveShiftname($shift) .'</strong>.<br>';
                } else {
                  echo '<strong>ERROR</strong> Could not insert the roll into the database. Please try again.<br>'. $e->getMessage();
                }

                return FALSE;
            } 
        }
        
        else
        {
            echo '<strong>ERROR</strong> Could not insert the roll into the database. Please try again.<br>'. $e->getMessage();
            return FALSE;
        }
    }
    
    /**
     * Loads the table of all the rolls
     * This function outputs <tr> tags with rolls
     * Parameter= ROTO=3 FLEXO1=4 FLEXO2=5                ALL DAY=0 MORNING=1 NIGHT=2
     */
    public function giveRolls($x, $machine)
    {
        $newDateString = date("Y-m-d");
        if(!empty($_POST['dateSearch']))
        {
           $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['dateSearch']);
           $newDateString = $myDateTime->format('Y-m-d');
        }
        $date = "`printing_rolls`.`date_roll` BETWEEN '". $newDateString ." ' AND '". $newDateString ." '";

        $sql = "SELECT `customers`.`customer_name`,`printing_rolls`.`rollno`,`printing_rolls`.`gross_weight`,`printing_rolls`.`net_weight`,`printing_rolls`.`waste_printing`,`printing_rolls`.`tape_test`,
`macchi_rolls`.gross_weight as inputgross, `macchi_rolls`.net_weight as inputnet,`macchi_rolls`.waste_printing as inputwaste,`macchi_rolls`.dyne_test,`macchi_rolls`.rollno AS inputroll
FROM `printing_rolls`
INNER JOIN `customers` ON `customers`.`customer_id` = `printing_rolls`.`customer_id`
INNER JOIN `macchi_rolls` ON `printing_rolls`.roll_id = `macchi_rolls`.macchi_rolls_id
        WHERE ". $date ."  AND `printing_rolls`.`machine_id` = ". $machine ."  AND macchi = 1
UNION 
SELECT `customers`.`customer_name`,`printing_rolls`.`rollno`,null,null, null,`printing_rolls`.`tape_test`,
`macchi_rolls`.gross_weight as inputgross, `macchi_rolls`.net_weight as inputnet,`macchi_rolls`.waste_printing as inputwaste,`macchi_rolls`.dyne_test,`macchi_rolls`.rollno AS inputroll
FROM `printing_rolls`
INNER JOIN `customers` ON `customers`.`customer_id` = `printing_rolls`.`customer_id`
INNER JOIN `macchi_rolls` ON `printing_rolls`.roll_id2 = `macchi_rolls`.macchi_rolls_id
        WHERE ". $date ."  AND `printing_rolls`.`machine_id` = ". $machine ."   AND macchi = 1
UNION 
SELECT `customers`.`customer_name`,`printing_rolls`.`rollno`,`printing_rolls`.`gross_weight`,`printing_rolls`.`net_weight`,`printing_rolls`.`waste_printing`,`printing_rolls`.`tape_test`,
`multilayer_rolls`.gross_weight as inputgross, `multilayer_rolls`.net_weight as inputnet,`multilayer_rolls`.waste_printing as inputwaste,`multilayer_rolls`.dyne_test,`multilayer_rolls`.rollno AS inputroll
FROM `printing_rolls`
INNER JOIN `customers` ON `customers`.`customer_id` = `printing_rolls`.`customer_id`
INNER JOIN `multilayer_rolls` ON `printing_rolls`.roll_id = `multilayer_rolls`.multilayer_rolls_id
        WHERE ". $date ."  AND `printing_rolls`.`machine_id` = ". $machine ."   AND multilayer = 1
UNION 
SELECT `customers`.`customer_name`,`printing_rolls`.`rollno`,null,null, null,`printing_rolls`.`tape_test`,
`multilayer_rolls`.gross_weight as inputgross, `multilayer_rolls`.net_weight as inputnet,`multilayer_rolls`.waste_printing as inputwaste,`multilayer_rolls`.dyne_test,`multilayer_rolls`.rollno AS inputroll
FROM `printing_rolls`
INNER JOIN `customers` ON `customers`.`customer_id` = `printing_rolls`.`customer_id`
INNER JOIN `multilayer_rolls` ON `printing_rolls`.roll_id2 = `multilayer_rolls`.multilayer_rolls_id
        WHERE ". $date ."  AND `printing_rolls`.`machine_id` = ". $machine ."   AND multilayer = 1
UNION 
SELECT `customers`.`customer_name`,`printing_rolls`.`rollno`,`printing_rolls`.`gross_weight`,`printing_rolls`.`net_weight`,`printing_rolls`.`waste_printing`,`printing_rolls`.`tape_test`,
`packing_rolls`.gross_weight as inputgross, `packing_rolls`.net_weight as inputnet,`packing_rolls`.waste_printing as inputwaste,`packing_rolls`.dyne_test,`packing_rolls`.rollno AS inputroll
FROM `printing_rolls`
INNER JOIN `customers` ON `customers`.`customer_id` = `printing_rolls`.`customer_id`
INNER JOIN `packing_rolls` ON `printing_rolls`.roll_id = `packing_rolls`.packing_rolls_id
        WHERE ". $date ."  AND `printing_rolls`.`machine_id` = ". $machine ."   AND `printing_rolls`.`packing_bags` = 1
UNION 
SELECT `customers`.`customer_name`,`printing_rolls`.`rollno`,null,null, null,`printing_rolls`.`tape_test`,
`packing_rolls`.gross_weight as inputgross, `packing_rolls`.net_weight as inputnet,`packing_rolls`.waste_printing as inputwaste,`packing_rolls`.dyne_test,`packing_rolls`.rollno AS inputroll
FROM `printing_rolls`
INNER JOIN `customers` ON `customers`.`customer_id` = `printing_rolls`.`customer_id`
INNER JOIN `packing_rolls` ON `printing_rolls`.roll_id2 = `packing_rolls`.packing_rolls_id
        WHERE ". $date ."  AND `printing_rolls`.`machine_id` = ". $machine ."   AND `printing_rolls`.`packing_bags` = 1
		ORDER BY CAST(SUBSTRING(rollno,7,2) AS UNSIGNED)";

        if($x != 0)
        {
            $sql = "SELECT `customers`.`customer_name`,`printing_rolls`.`rollno`,`printing_rolls`.`gross_weight`,`printing_rolls`.`net_weight`,`printing_rolls`.`waste_printing`,`printing_rolls`.`tape_test`,
`macchi_rolls`.gross_weight as inputgross, `macchi_rolls`.net_weight as inputnet,`macchi_rolls`.waste_printing as inputwaste,`macchi_rolls`.dyne_test,`macchi_rolls`.rollno AS inputroll
FROM `printing_rolls`
INNER JOIN `customers` ON `customers`.`customer_id` = `printing_rolls`.`customer_id`
INNER JOIN `macchi_rolls` ON `printing_rolls`.roll_id = `macchi_rolls`.macchi_rolls_id
        WHERE ". $date ."  AND `printing_rolls`.`machine_id` = ". $machine ."  AND `printing_rolls`.`shift` = ". $x ."  AND macchi = 1
UNION 
SELECT `customers`.`customer_name`,`printing_rolls`.`rollno`,null,null, null,`printing_rolls`.`tape_test`,
`macchi_rolls`.gross_weight as inputgross, `macchi_rolls`.net_weight as inputnet,`macchi_rolls`.waste_printing as inputwaste,`macchi_rolls`.dyne_test,`macchi_rolls`.rollno AS inputroll
FROM `printing_rolls`
INNER JOIN `customers` ON `customers`.`customer_id` = `printing_rolls`.`customer_id`
INNER JOIN `macchi_rolls` ON `printing_rolls`.roll_id2 = `macchi_rolls`.macchi_rolls_id
        WHERE ". $date ."  AND `printing_rolls`.`machine_id` = ". $machine ."  AND `printing_rolls`.`shift` = ". $x ."  AND macchi = 1
UNION 
SELECT `customers`.`customer_name`,`printing_rolls`.`rollno`,`printing_rolls`.`gross_weight`,`printing_rolls`.`net_weight`,`printing_rolls`.`waste_printing`,`printing_rolls`.`tape_test`,
`multilayer_rolls`.gross_weight as inputgross, `multilayer_rolls`.net_weight as inputnet,`multilayer_rolls`.waste_printing as inputwaste,`multilayer_rolls`.dyne_test,`multilayer_rolls`.rollno AS inputroll
FROM `printing_rolls`
INNER JOIN `customers` ON `customers`.`customer_id` = `printing_rolls`.`customer_id`
INNER JOIN `multilayer_rolls` ON `printing_rolls`.roll_id = `multilayer_rolls`.multilayer_rolls_id
        WHERE ". $date ."  AND `printing_rolls`.`machine_id` = ". $machine ."  AND `printing_rolls`.`shift` = ". $x ."  AND multilayer = 1
UNION 
SELECT `customers`.`customer_name`,`printing_rolls`.`rollno`,null,null, null,`printing_rolls`.`tape_test`,
`multilayer_rolls`.gross_weight as inputgross, `multilayer_rolls`.net_weight as inputnet,`multilayer_rolls`.waste_printing as inputwaste,`multilayer_rolls`.dyne_test,`multilayer_rolls`.rollno AS inputroll
FROM `printing_rolls`
INNER JOIN `customers` ON `customers`.`customer_id` = `printing_rolls`.`customer_id`
INNER JOIN `multilayer_rolls` ON `printing_rolls`.roll_id2 = `multilayer_rolls`.multilayer_rolls_id
        WHERE ". $date ."  AND `printing_rolls`.`machine_id` = ". $machine ."  AND `printing_rolls`.`shift` = ". $x ."   AND multilayer = 1
		UNION 
SELECT `customers`.`customer_name`,`printing_rolls`.`rollno`,`printing_rolls`.`gross_weight`,`printing_rolls`.`net_weight`,`printing_rolls`.`waste_printing`,`printing_rolls`.`tape_test`,
`packing_rolls`.gross_weight as inputgross, `packing_rolls`.net_weight as inputnet,`packing_rolls`.waste_printing as inputwaste,`packing_rolls`.dyne_test,`packing_rolls`.rollno AS inputroll
FROM `printing_rolls`
INNER JOIN `customers` ON `customers`.`customer_id` = `printing_rolls`.`customer_id`
INNER JOIN `packing_rolls` ON `printing_rolls`.roll_id = `packing_rolls`.packing_rolls_id
        WHERE ". $date ."  AND `printing_rolls`.`machine_id` = ". $machine ." AND `printing_rolls`.`shift` = ". $x ."  AND `printing_rolls`.`packing_bags` = 1
UNION 
SELECT `customers`.`customer_name`,`printing_rolls`.`rollno`,null,null, null,`printing_rolls`.`tape_test`,
`packing_rolls`.gross_weight as inputgross, `packing_rolls`.net_weight as inputnet,`packing_rolls`.waste_printing as inputwaste,`packing_rolls`.dyne_test,`packing_rolls`.rollno AS inputroll
FROM `printing_rolls`
INNER JOIN `customers` ON `customers`.`customer_id` = `printing_rolls`.`customer_id`
INNER JOIN `packing_rolls` ON `printing_rolls`.roll_id2 = `packing_rolls`.packing_rolls_id
        WHERE ". $date ."  AND `printing_rolls`.`machine_id` = ". $machine ." AND `printing_rolls`.`shift` = ". $x ."  AND `printing_rolls`.`packing_bags`= 1
		ORDER BY CAST(SUBSTRING(rollno,7,2) AS UNSIGNED)";
        }
        
        $total1 = $total2 = $total3 = $total4 = $total5 = $total6 = 0;
                
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            echo '<table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr class="active">
                                    <th></th>'.
                                    '<th colspan="5" style="text-align:center">INPUT ROLL</th>'.
                                    '<th colspan="5" style="text-align:center">OUTPUT ROLL </th>
                                </tr>
                                <tr class="active">
                                    <th>Customer</th>'.
									'
                                    <th>Roll No.</th>
                                    <th>Gross Wt.</th>
                                    <th>Net Wt.</th>
                                    <th>Waste</th>
                                    <th>Dyne</th>'.
				'
                                    <th>Roll No.</th>
                                    <th>Gross Wt.</th>
                                    <th>Net Wt.</th>
                                    <th>Waste</th>
                                    <th>Tape</th>
                                </tr>
                            </thead>
                            <tbody>';
           
            while($row = $stmt->fetch())
            {   
                $total1 = $total1 + $row['inputgross'];
                $total2 = $total2 + $row['inputnet'];
                $total3 = $total3 + $row['inputwaste'];
                $total4 = $total4 + $row['gross_weight'];
                $total5 = $total5 + $row['net_weight'];
                $total6 = $total6 + $row['waste_printing'];
                
                $DYNE = $TAPE = "OK";
                if($row['dyne_test']==0)
                {
                    $DYNE = '<p class="text-danger">NO</p>';
                }
                if($row['tape_test']==0)
                {
                    $TAPE = '<p class="text-danger">NO</p>';
                }
                
                echo '<tr>
                        <td>'.  $row['customer_name'] .'</td>
                        <td>'.  $row['inputroll'] .'</td>                        
                        <td class="text-right">'. number_format($row['inputgross'],1,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['inputnet'],1,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['inputwaste'],1,'.',',') .'</td>
                        <td>'.  $DYNE .'</td> 
                        <td>'.  $row['rollno'] .'</td>                        
                        <td class="text-right">'. number_format($row['gross_weight'],1,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['net_weight'],1,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['waste_printing'],1,'.',',') .'</td>
                        <td>'.  $TAPE .'</td> 
                       </tr>';
            }
            echo '
              <tfoot>
                <tr class="active">
                  <th style="text-align:center"></th>
                  <th style="text-align:center">TOTAL</th>'.
                  '<th class="text-right">'. number_format($total1,2,'.',',') .'</th>
                  <th class="text-right">'. number_format($total2,2,'.',',') .'</th>
                  <th class="text-right">'. number_format($total3,2,'.',',') .'</th>
                  <th style="text-align:center"></th>
                  <th style="text-align:center">TOTAL</th>'.
                  '<th class="text-right">'. number_format($total4,2,'.',',') .'</th>
                  <th class="text-right">'. number_format($total5,2,'.',',') .'</th>
                  <th class="text-right">'. number_format($total6,2,'.',',') .'</th>
                  <th></th>
                </tr>
              </tfoot></tbody>
                        </table>';
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
     * Loads the table of all the rolls in the printing section
     * This function outputs <tr> tags with the rolls
     */
    public function giveRollsInfo()
    {
        $a=array();
        $b=array();
        $c=array();
        $d=array();
        $sql = "SELECT `customers`.`customer_name`, count(`printing_rolls`.`printing_rolls_id`) AS count_rolls, ROUND(SUM(`printing_rolls`.`gross_weight`),2) AS totalgross, ROUND(SUM(`printing_rolls`.`net_weight`),2) As totalnet, ROUND(SUM(`printing_rolls`.`net_weight`)/count(`printing_rolls`.`printing_rolls_id`),2) AS average_weight
        FROM  `printing_rolls`
        NATURAL JOIN `customers`
        WHERE status_roll = 0 group by `printing_rolls`.`customer_id`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $CUSTOMER = $row['customer_name'];
                $COUNT = $row['count_rolls'];
                $GROSS = $row['totalgross'];
                $NET = $row['totalnet'];
                $AVERAGE = $row['average_weight'];
                
                echo '<tr>
                        <td>'. $CUSTOMER .'</td>
                        <td>'. $COUNT .'</td>
                        <td class="text-right">'. number_format($GROSS,1,'.',',') .'</td>
                        <td class="text-right">'. number_format($NET,1,'.',',') .'</td>
                        <td class="text-right">'. number_format($AVERAGE,1,'.',',') .'</td>
                    </tr>';
                
                $countArray=array("y" => $COUNT, "label" => $CUSTOMER);
                array_push($a,$countArray);
                $weightArray=array("y" => $GROSS, "label" => $CUSTOMER) ;
                array_push($b,$weightArray);
                $weightArray=array("y" => $NET, "label" => $CUSTOMER) ;
                array_push($c,$weightArray);
                $averageArray=array("y" => $AVERAGE, "label" => $CUSTOMER) ;
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
         $sql = "SELECT `customers`.`customer_name`,`printing_rolls`.`rollno`,`printing_rolls`.`size`, `printing_rolls`.`gross_weight`, `printing_rolls`.`net_weight`
        FROM  `printing_rolls`
        NATURAL JOIN `customers`
        WHERE status_roll = 0;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $CUSTOMER = $row['customer_name'];
                $ROLLNO = $row['rollno'];
                $SIZE = $row['size'];
                $GROSS = $row['gross_weight'];
                $NET = $row['net_weight'];
                
                echo '<tr>
                        <td>'. $CUSTOMER .'</td>
                        <td>'. $ROLLNO .'</td>
                        <td>'. $this->giveSizename($SIZE) .'</td>
                        <td class="text-right">'. number_format($GROSS,1,'.',',') .'</td>
                        <td class="text-right">'. number_format($NET,1,'.',',') .'</td>
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
        $sql = "SELECT `shortfalls`.`date_fall`, `machines`.`machine_name`,DATE_FORMAT(`shortfalls`.`downtime`, '%H:%i') AS time_t, `shortfalls`.`reason`,`shortfalls`.`action_plan`
        FROM  `shortfalls` 
        NATURAL JOIN `machines`
        WHERE `machines`.`location_id`=3 AND MONTH(date_fall) = MONTH(CURRENT_DATE()) AND YEAR(date_fall) = YEAR(CURRENT_DATE()) ORDER BY date_fall;";
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
        $reason = $action = $time = "";
        
        $reason = stripslashes($_POST["reason"]);
        $reason = htmlspecialchars($reason);
        
        $action = stripslashes($_POST["action"]);
        $action = htmlspecialchars($action);
        
        $machine = trim($_POST["machine"]);
        $machine = stripslashes($machine);
        $machine = htmlspecialchars($machine);
        
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
        (NULL,:machine,'". $date . "',:time,:reason,:action);";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":machine", $machine, PDO::PARAM_INT);
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
     * Checks gives the customers
     *
     */
    public function giveCustomers()
    {
        $sql = "SELECT `customers`.`customer_id`, `customers`.`customer_name`,  `customers`.`cylinder`,`customers`.`repeat_length`, `customers`.`pifa`, `customers`.`ons`,`thickness`,
		`reel`, colors 
		FROM  `customers`
		LEFT JOIN (SELECT COUNT(`customers_colors`.`material_id`) as colors, `customers_colors`.`customer_id`
		FROM `customers_colors`
		GROUP BY `customers_colors`.`customer_id`) colorstable on colorstable.`customer_id` = `customers`.`customer_id`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $PIFA = "Pifa " .$row['pifa'];
                if($row['pifa'] == 0)
                {
                    $PIFA = "N/A";
                }
				if(empty($row['colors']))
                {
                    $COLORS = 0;
                }
				else
				{
					$COLORS = $row['colors'];
				}
                echo '<tr>
                                <td>'. $row['customer_name'] .'</td>
                                <td>'. $row['thickness'] .'</td>
                                <td>'. $row['reel'] .'</td>
                                <td >'. $row['repeat_length'] .'</td>
                                <td>'. $row['cylinder'] .'</td>
                                <td>'. $PIFA .'</td>
                                <td >'. $row['ons'] .'</td>
                                <td><form id="form"  method="post" action="colors.php">
									<input type="hidden" class="form-control" name="customer" id="customer" value="'.$row['customer_id'].'" />
									<input type="hidden" class="form-control" name="name" id="name" value="'.$row['customer_name'].'"/>
                        			<button type="submit" id="buttonForm" class="btn btn-link">'. $COLORS .' colors</button>
								</form></td>
                                <td><button class="btn btn-link" data-toggle="modal" data-target="#modal1" onclick="edit(\''. $row['customer_id'] .'\',\''. $row['customer_name'] .'\',\''. $row['cylinder'] .'\',\''. $row['repeat_length'] .'\',\''. $row['pifa'] .'\',\''. $PIFA .'\',\''. $row['ons'] .'\',\''. $row['thickness'] .'\',\''. $row['reel'] .'\')"">Edit</button></td>
                                <td><button class="btn btn-link" data-toggle="modal" data-target="#modal1" onclick="delet(\''. $row['customer_id'] .'\',\''. $row['customer_name'] .'\')"><p class="text-danger">Delete</p></button></td>
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
     * Checks and inserts a new customer
     *
     * @return boolean  true if can insert  false if not
     */
    public function createCustomer()
    {
        $name = $cylinder = $length = $pifa = $ons = "";
        
        $name = trim($_POST["name"]);
        $name = stripslashes($name);
        $name = htmlspecialchars($name);
        
        
        $cylinder = trim($_POST["cylinder"]);
        $cylinder = stripslashes($cylinder);
        $cylinder = htmlspecialchars($cylinder);
        
        $length = trim($_POST["length"]);
        $length = stripslashes($length);
        $length = htmlspecialchars($length);
        
        $pifa = trim($_POST["pifa"]);
        $pifa = stripslashes($pifa);
        $pifa = htmlspecialchars($pifa);
        
        $ons = trim($_POST["ons"]);
        $ons = stripslashes($ons);
        $ons = htmlspecialchars($ons);
		
		$thickness = trim($_POST["thickness"]);
        $thickness = stripslashes($thickness);
        $thickness = htmlspecialchars($thickness);
		
		$reel = trim($_POST["reel"]);
        $reel = stripslashes($reel);
        $reel = htmlspecialchars($reel);
        
        $sql = "INSERT INTO  `customers`(`customer_id`,`customer_name`,`cylinder`,`repeat_length`,`pifa`,`ons`,`thickness`,`reel`)
        VALUES(NULL,:name,:cylinder,:length,:pifa,:ons,:thickness,:reel);";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":cylinder", $cylinder, PDO::PARAM_STR);
            $stmt->bindParam(":length", $length, PDO::PARAM_STR);
            $stmt->bindParam(":pifa", $pifa, PDO::PARAM_INT);
            $stmt->bindParam(":ons", $ons, PDO::PARAM_STR);
            $stmt->bindParam(":thickness", $thickness, PDO::PARAM_STR);
            $stmt->bindParam(":reel", $reel, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
            
            echo '<strong>SUCCESS!</strong> The customer was successfully added to the database.';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> This customer is already in the database. If you want to change the information, please try updating it.<br>';
            } else {
              echo '<strong>ERROR</strong> Could not insert the customer into the database. Please try again.<br>'. $e->getMessage();
            }
            
            return FALSE;
        } 

    }
    
     /**
     * Checks and update a customer
     *
     * @return boolean  true if can update false if not
     */
    public function updateCustomer()
    {
        $id = $name = $cylinder = $length = $pifa = $ons = "";
        
        $id = trim($_POST["customer"]);
        $id = stripslashes($id);
        $id = htmlspecialchars($id);
        
        $name = trim($_POST["name"]);
        $name = stripslashes($name);
        $name = htmlspecialchars($name);
        
        
        $cylinder = trim($_POST["cylinder"]);
        $cylinder = stripslashes($cylinder);
        $cylinder = htmlspecialchars($cylinder);
        
        $length = trim($_POST["length"]);
        $length = stripslashes($length);
        $length = htmlspecialchars($length);
        
        $pifa = trim($_POST["pifa"]);
        $pifa = stripslashes($pifa);
        $pifa = htmlspecialchars($pifa);
        
        $ons = trim($_POST["ons"]);
        $ons = stripslashes($ons);
        $ons = htmlspecialchars($ons);
		
		$thickness = trim($_POST["thickness"]);
        $thickness = stripslashes($thickness);
        $thickness = htmlspecialchars($thickness);
		
		$reel = trim($_POST["reel"]);
        $reel = stripslashes($reel);
        $reel = htmlspecialchars($reel);
        
        $sql = "UPDATE  `customers`
                SET
                    `customer_name` = :name,
                    `cylinder` = :cylinder,
                    `repeat_length` = :length,
                    `pifa` = :pifa,
                    `ons` = :ons,
					`thickness` = :thickness,
					`reel` = :reel
                WHERE `customer_id` = :id;";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":cylinder", $cylinder, PDO::PARAM_STR);
            $stmt->bindParam(":length", $length, PDO::PARAM_STR);
            $stmt->bindParam(":pifa", $pifa, PDO::PARAM_INT);
            $stmt->bindParam(":ons", $ons, PDO::PARAM_STR);
            $stmt->bindParam(":thickness", $thickness, PDO::PARAM_STR);
            $stmt->bindParam(":reel", $reel, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
            echo '<strong>SUCCESS!</strong> The customer was successfully updated in the database.';
            return TRUE;
        } catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not update the customer into the database. Please try again.<br>'. $e->getMessage(); 
            return FALSE;
        } 

    }
    
    /**
     * Checks and delete a formula
     *
     * @return boolean  true if can update false if not
     */
    public function deleteCustomer()
    {
        $id = "";
        
        $id = trim($_POST["customer"]);
        $id = stripslashes($id);
        $id = htmlspecialchars($id);
        
        $sql = "DELETE FROM  `customers`
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
            echo '<strong>ERROR</strong> Could not delete the customer from the database. Please try again.<br>'. $e->getMessage(); 
            return FALSE;
        } 

    }
    
    
    /**
     * Loads the dropdown of all the customers
     *
     * This function outputs <li> tags with customers
     */
    public function customersDropdown()
    {
        $sql = "SELECT `customers`.`customer_id`,`customers`.`customer_name`
        FROM  `customers`
		WHERE sachet_rolls=1
		ORDER BY customer_name;";
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
	public function customersPackingDropdown()
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
     * Checks gives the colors from a customer
     *
     */
    public function giveColors()
    {
        $sql = "SELECT `materials`.`material_name`,`customers_colors`.`consumption`,`customers_colors`.`medium`
				FROM `customers_colors`
				NATURAL JOIN `materials`
				WHERE `customers_colors`.`customer_id` = ".$_POST['customer'].";";
		$a=array();
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
			
            $total = 0;
            while($row = $stmt->fetch())
            {
                $MEDIUM = "";
                if($row['medium'] == 1)
                {
                    $MEDIUM = "<p class='text-success'>YES</p>";
                }
				$total = $total + $row['consumption'];
                echo '<tr>
                                <td>'. $row['material_name'] .'</td>
                                <td class="text-right">'. $row['consumption'] .' kgs</td>
                                <td>'. $MEDIUM .'</td>
                            </tr>';
				$color=array($row['material_name'],$row['consumption']);
				array_push($a,$color);
            }
            $stmt->closeCursor();
			 echo '<tr class="active">
                    <td><strong>TOTAL</strong></td>
                    <td class="text-right"><strong>'. number_format($total,2,'.',',') .' kgs</strong></td>
                    <td></td>
                </tr>';
			
            foreach ($a as $key => $value) {
                $a[$key][1] = ($value[1]/$total) * 100;
            }
            
            return $a;
        }  
        else
        {
            echo "Something went wrong. $db->errorInfo";
        }
    }
	
	/**
     * Loads the dropdown of all the colors
     *
     * This function outputs <li> tags with colors
     */
    public function colorsDropdown()
    {
        $sql = "SELECT `materials`.`material_id`,
					`materials`.`material_name`
				FROM `materials`
				WHERE `materials`.`color` = 1;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
                $NAME = $row['material_name'];
                echo  '<li><a id="'. $NAME .'" onclick="selectColor(\''. $ID .'\',\''. $NAME .'\')">'. $NAME .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	
	/**
     * Checks and inserts a new color
     *
     * @return boolean  true if can insert  false if not
     */
    public function createColor()
    {
        $color = $customer = $kg = $medium = "";
        
        $color = trim($_POST["color"]);
        $color = stripslashes($color);
        $color = htmlspecialchars($color);
        
		$customer = trim($_POST["customer"]);
        $customer = stripslashes($customer);
        $customer = htmlspecialchars($customer);
		
        $kg = trim($_POST["kg"]);
        $kg = stripslashes($kg);
        $kg = htmlspecialchars($kg);
		

		$medium = 0;
        if(!empty($_POST['medium']) )
        {
            $medium = trim($_POST["medium"]);
            $medium = stripslashes($medium);
            $medium = htmlspecialchars($medium);
		}
        
        $sql = "INSERT INTO `customers_colors`(`customers_colors`,`material_id`,`customer_id`,`consumption`,`medium`)
		VALUES(NULL,:color,:customer,:kg,:medium);";
        
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":color", $color, PDO::PARAM_INT);
            $stmt->bindParam(":customer", $customer, PDO::PARAM_INT);
            $stmt->bindParam(":kg", $kg, PDO::PARAM_STR);
            $stmt->bindParam(":medium", $medium, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            
            echo '<strong>SUCCESS!</strong> The color was successfully added to the customer: <strong>'. $_POST["name"] .'</strong>';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> The color is already in that layer. If you want to change the amount of kilograms, please try updating it.<br>';
            } else {
              echo '<strong>ERROR</strong> Could not insert the color into the database. Please try again.<br>'. $e->getMessage();
            }
            
            return FALSE;
        } 

    }
    
     /**
     * Checks and update a color
     *
     * @return boolean  true if can update false if not
     */
    public function updateColor()
    {
        $color = $customer = $kg = $medium = "";
        
        $color = trim($_POST["color"]);
        $color = stripslashes($color);
        $color = htmlspecialchars($color);
        
		$customer = trim($_POST["customer"]);
        $customer = stripslashes($customer);
        $customer = htmlspecialchars($customer);
		
        $kg = trim($_POST["kg"]);
        $kg = stripslashes($kg);
        $kg = htmlspecialchars($kg);
		
		$medium = 0;
        if(!empty($_POST['medium']) )
        {
            $medium = trim($_POST["medium"]);
            $medium = stripslashes($medium);
            $medium = htmlspecialchars($medium);
		}
		
        $sql = "UPDATE `customers_colors`
				SET
				`consumption` = :kg,
				`medium` = :medium
				WHERE `material_id` =:color AND `customer_id` =:customer;";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":color", $color, PDO::PARAM_INT);
            $stmt->bindParam(":customer", $customer, PDO::PARAM_INT);
            $stmt->bindParam(":kg", $kg, PDO::PARAM_STR);
            $stmt->bindParam(":medium", $medium, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            echo '<strong>SUCCESS!</strong> The color was successfully updated to the customer: <strong>'. $_POST["name"]  .'</strong> with <strong>'. $kg .' kgs </strong>';
            return TRUE;
        } catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not update the color into the database. Please try again.<br>'. $e->getMessage(); 
            return FALSE;
        } 

    }
    
    /**
     * Checks and delete a color
     *
     * @return boolean  true if can update false if not
     */
    public function deleteColor()
    {
        $color = $customer = "";
        
        $color = trim($_POST["color"]);
        $color = stripslashes($color);
        $color = htmlspecialchars($color);
        
		$customer = trim($_POST["customer"]);
        $customer = stripslashes($customer);
        $customer = htmlspecialchars($customer);
		
      
        $sql = "DELETE FROM `customers_colors`
				WHERE `material_id` =:color AND `customer_id` =:customer;";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":color", $color, PDO::PARAM_INT);
            $stmt->bindParam(":customer", $customer, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            
            echo '<strong>SUCCESS!</strong> The color was successfully deleted from the customer: <strong>'. $_POST["name"]  .'</strong>';
            return TRUE;
        } catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not delete the color from the database. Please try again.<br>'. $e->getMessage(); 
            return FALSE;
        } 

    }
    
     /**
     * Loads the table of all the formulas depending the layer
     * Parameter= ID of the layer OUTER=1 MIDDLE=2 INNER=3
     * This function outputs <tr> tags with formulas
     */
    public function giveFormulas($x)
    {
        $sql = "SELECT material_name, material_grade, machine_id, percentage               
                FROM `printing_formulas` 
                LEFT JOIN  `materials`ON printing_formulas.material_id = materials.material_id
                WHERE machine_id=".$x." ORDER BY percentage";
        $a=array();
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            $total = 0;
            while($row = $stmt->fetch())
            {
                $NAME = $row['material_name'];
                if(is_null($NAME))
                {
                    $NAME = "Fresh Ink";
                }
                $GRADE = $row['material_grade'];
                $PERCENTAGE = $row['percentage'];
                $total = $total + $PERCENTAGE;
                echo '<tr>
                    <td><b>'. $NAME .'</b></td>
                    <td>'. $GRADE .'</td>
                    <td class="text-right">'. number_format($PERCENTAGE,1,'.',',') .'</td>
                </tr>';
                $materialArray=array($NAME,$GRADE,$PERCENTAGE);
                array_push($a,$materialArray);
            }
            
            echo '<tr class="active">
                    <td  colspan="2" class="text-center"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong>'. number_format($total ,1,'.',',') .'</strong></td>
                </tr>';
            $stmt->closeCursor();
            
            foreach ($a as $key => $value) {
                $a[$key][2] = ($value[2]/$total) * 100;
            }
            
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
    
     /**
     * Checks and inserts a new formula
     *
     * @return boolean  true if can insert  false if not
     */
    public function createFormula()
    {
        $machine = $material = $percentage = "";
        
        $machine = trim($_POST["machine"]);
        $machine = stripslashes($machine);
        $machine = htmlspecialchars($machine);
        
        if(!empty($_POST['material']))
        {
            $material = trim($_POST["material"]);
            $material = stripslashes($material);
            $material = htmlspecialchars($material);
        }
        else
        {
           $material = NULL;
        }
        
        $percentage = trim($_POST["percentage"]);
        $percentage = stripslashes($percentage);
        $percentage = htmlspecialchars($percentage);
        
        $sql = "INSERT INTO `printing_formulas` (`printing_formula`, `material_id`, `machine_id`, `percentage`) VALUES (NULL, :material, :machine, :percentage);";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":machine", $machine, PDO::PARAM_INT);
            $stmt->bindParam(":material", $material, PDO::PARAM_INT);
            $stmt->bindParam(":percentage", $percentage, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
            
            echo '<strong>SUCCESS!</strong> The material was successfully added to the machine.';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> The material is already in that machine. If you want to change the percentage, please try updating it.<br>';
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
        $machine = $material = $percentage = "";
        
        $machine = trim($_POST["machine"]);
        $machine = stripslashes($machine);
        $machine = htmlspecialchars($machine);
        
        if(!empty($_POST['material']))
        {
            $material = trim($_POST["material"]);
            $material = stripslashes($material);
            $material = htmlspecialchars($material);
            $material = "`material_id` = '".$material."'";
        }
        else
        {
           $material = "`material_id` IS NULL";
           
        }
        
        $percentage = trim($_POST["percentage"]);
        $percentage = stripslashes($percentage);
        $percentage = htmlspecialchars($percentage);
        
        
        $sql = "UPDATE  `printing_formulas`
                SET `percentage` = :percentage
                WHERE ". $material ." AND `machine_id` = :machine;";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":percentage", $percentage, PDO::PARAM_STR);
            $stmt->bindParam(":machine", $machine, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            echo '<strong>SUCCESS!</strong> The material was successfully updated to the machine with <strong>'. $percentage .' % </strong>';
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
    public function deleteFormula()
    {
        $machine = $material = "";
        
        $machine = trim($_POST["machine"]);
        $machine = stripslashes($machine);
        $machine = htmlspecialchars($machine);
        
        if(!empty($_POST['material']))
        {
            $material = trim($_POST["material"]);
            $material = stripslashes($material);
            $material = htmlspecialchars($material);
            $material = "`material_id` = '".$material."'";
        }
        else
        {
           $material = "`material_id` IS NULL";
           
        }
        
        
        $sql = "DELETE FROM  `printing_formulas`
                WHERE ". $material ." AND `machine_id` = :machine;";
        
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":machine", $machine, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            
            echo '<strong>SUCCESS!</strong> The material was successfully deleted from the machine.';
            return TRUE;
        } catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not delete the material from the database. Please try again.<br>'. $e->getMessage(); 
            return FALSE;
        } 

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
        else if($size == 3)
        {
            $sizename = "Natural Film";
        }
        return $sizename;
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
    
    
    /**
     * Loads the Production Report in the multilayer section
     * This function outputs <tr> tags with the report
     */
    public function reportProduction()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Machine</th>';
        echo '<th>Production</th>';
        echo '<th>No. of rolls produced</th>';
        echo '</tr></thead><tbody>';   
        
        $rotoProduction=array();
        $rotoRolls=array();
		
		$flexo1Production=array();
        $flexo1Rolls=array();
		
		$flexo2Production=array();
        $flexo2Rolls=array();
        
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
            
            $sql = " SELECT DATE_FORMAT(`date_roll`, '%b/%Y') as date, DATE_FORMAT(`date_roll`, '%m/%Y') as date2, machine_id, machine_name, ROUND(SUM(net_weight),2) as actual, COUNT(printing_rolls_id) as rolls 
            FROM printing_rolls NATURAL JOIN machines
            WHERE `printing_rolls`.date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
            GROUP BY DATE_FORMAT(`date_roll`, '%b/%Y'), machine_id 
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
            
            $sql = " SELECT DATE_FORMAT(`date_roll`, '%Y') as date, machine_id, machine_name, ROUND(SUM(net_weight),2) as actual, COUNT(printing_rolls_id) as rolls 
            FROM printing_rolls NATURAL JOIN machines
            WHERE `printing_rolls`.date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
            GROUP BY DATE_FORMAT(`date_roll`, '%Y'), machine_id 
            ORDER BY `date_roll`;;";
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
            
            $sql = " SELECT DATE_FORMAT(`date_roll`, '%d/%m/%Y') as date, machine_id, machine_name, ROUND(SUM(net_weight),2) as actual, COUNT(printing_rolls_id) as rolls 
            FROM printing_rolls NATURAL JOIN machines
            WHERE `printing_rolls`.date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
            GROUP BY DATE_FORMAT(`date_roll`, '%d/%m/%Y'), machine_id 
            ORDER BY `date_roll`;;";
            
        }
        
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. $row['machine_name'] .'</td>
                        <td class="text-right">'. number_format($row['actual'],1,'.',',') .'</td>
                        <td class="text-right">'. number_format($row['rolls'],0,'.',',') .'</td>
                    </tr>';
                $entrie = array( $row['date'], $row['actual']);
                $entrie1 = array( $row['date'], $row['rolls']);
                if($_POST['searchBy']==2)
                {
                    $entrie = array( $row['date2'], $row['actual']);
                    $entrie1 = array( $row['date2'],$row['rolls']);
                }
				if($row['machine_id']==3)
				{
					array_push($rotoProduction,$entrie);
					array_push($rotoRolls,$entrie1);
				}
				else if($row['machine_id']==4)
				{
					array_push($flexo1Production,$entrie);
					array_push($flexo2Rolls,$entrie1);
				}
				else if($row['machine_id']==5)
				{
					array_push($flexo1Production,$entrie);
					array_push($flexo2Rolls,$entrie1);
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
            axisY: {title: "Total net production (kgs)" },
	toolTip: {
		shared: true
	},
	legend: {
		cursor:"pointer",
		itemclick: toggleDataSeries
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
				name: "Roto",
				legendText: "Roto",
				showInLegend: true,';
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
        echo ' yValueFormatString: "#,###.# Kgs",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($rotoProduction as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'}, ';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($rotoProduction as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($rotoProduction as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo '] },{
                type: "column",
				name: "Flexo 1",
				legendText: "Flexo 1",
				showInLegend: true,';
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
        echo ' yValueFormatString: "#,###.# Kgs",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($flexo1Production as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'}, ';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($flexo1Production as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($flexo1Production as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
        echo'] }'; 
		echo ',{
                type: "column",
				name: "Flexo 2",
				legendText: "Flexo 2",
				showInLegend: true,';
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
        echo ' yValueFormatString: "#,###.# Kgs",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($flexo2Production as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'}, ';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($flexo2Production as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($flexo2Production as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
        echo'] }'; 
        echo']});
        chart.render(); 
		function toggleDataSeries(e) {
			if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
				e.dataSeries.visible = false;
			}
			else {
				e.dataSeries.visible = true;
			}
			chart.render();
		}
        </script>'; 
		echo '<script> 
            var chart = new CanvasJS.Chart("chartContainer2", {
            theme: "light2",
            title: { 
                text: "Rolls produced"
            },
            exportFileName: "Rolls produced",
            exportEnabled: true,
            animationEnabled: true,
            axisY: {title: "No. of rolls produced " },
	toolTip: {
		shared: true
	},
	legend: {
		cursor:"pointer",
		itemclick: toggleDataSeries
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
				name: "Roto",
				legendText: "Roto",
				showInLegend: true,';
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
        echo ' yValueFormatString: "#,###.# Kgs",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($rotoRolls as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'}, ';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($rotoRolls as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($rotoRolls as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
		echo '] },{
                type: "column",
				name: "Flexo 1",
				legendText: "Flexo 1",
				showInLegend: true,';
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
        echo ' yValueFormatString: "#,###.# Kgs",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($flexo1Rolls as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'}, ';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($flexo1Rolls as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($flexo1Rolls as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
        echo'] }'; 
		echo ',{
                type: "column",
				name: "Flexo 2",
				legendText: "Flexo 2",
				showInLegend: true,';
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
        echo ' yValueFormatString: "#,###.# Kgs",
                dataPoints: [ ';
        if($_POST['searchBy']==2)
        {  
            foreach($flexo2Rolls as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'}, ';
            }; 
        }
        else if($_POST['searchBy']==3)
        {   
            foreach($flexo2Rolls as $value) {
                echo '{ x: new Date('. $value[0] . ',0), y: '. $value[1].'},';
            }; 
        }
        else
        {
            foreach($flexo2Rolls as $value) {
                $var = (int) explode("/", $value[0])[1]-1;
                echo '{ x: new Date('. explode("/", $value[0])[2] . ','. $var .','.explode("/", $value[0])[0] .'), y: '. $value[1].'},';
            }; 
        }
        echo'] }'; 
        echo']});
        chart.render(); 
		function toggleDataSeries(e) {
			if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
				e.dataSeries.visible = false;
			}
			else {
				e.dataSeries.visible = true;
			}
			chart.render();
		}
        </script>'; 
       
    }
    
    /**
     * Loads the Efficiency Report in the multilayer section
     * This function outputs <tr> tags with the report
     */
    public function reportEfficiency()
    {
        echo '<thead><tr  class="active">';
        echo '<th>Date</th>';
        echo '<th>Machine Name</th>';
        echo '<th>Target Production</th>';
        echo '<th>Actual Production</th>';
        echo '<th>% Eff</th>';
        echo '<th>Waste in Kgs</th>';
        echo '<th>Target Waste %</th>';
        echo '<th>Waste %</th>';
        echo '</tr></thead><tbody>'; 
        $TARGET = 0;
        $a=array();
        $b=array();
        $c=array();
        $d=array();
        $sql = "SELECT `settings`.`value_setting`
                FROM  `settings` 
                WHERE location_id=2 AND name_setting='target';";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $TARGET = $row['value_setting'];
            }
        }
        
        $TARGETWASTE = 0;
        $sql = "SELECT `settings`.`value_setting`
                FROM  `settings` 
                WHERE location_id=2 AND name_setting='waste';";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $TARGETWASTE = $row['value_setting'];
            }
        }
        
        $a=array();
        
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
            
            $sql = "SELECT DATE_FORMAT(`date_roll`, '%b/%Y') as date, DATE_FORMAT(`date_roll`, '%m/%Y') as date2, ROUND(SUM(net_weight),2) as actual, waste.wastekgs, COUNT(DISTINCT (DATE_FORMAT(`date_roll`, '%d/%m/%Y'))) AS days
            FROM multilayer_rolls 
            LEFT JOIN (
	            SELECT DATE_FORMAT(`date_waste`, '%b/%Y') as date, SUM(waste) as wastekgs
	            FROM  `waste`
	            WHERE location_id = 2 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
                GROUP BY DATE_FORMAT(`date_waste`, '%b/%Y') 
                ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%b/%Y')
            WHERE date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
            GROUP BY DATE_FORMAT(`date_roll`, '%b/%Y') 
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
            
            $sql = "SELECT DATE_FORMAT(`date_roll`, '%Y') as date, ROUND(SUM(net_weight),2) as actual, waste.wastekgs, COUNT(DISTINCT (DATE_FORMAT(`date_roll`, '%d/%m/%Y'))) AS days
            FROM multilayer_rolls 
            LEFT JOIN (
	            SELECT DATE_FORMAT(`date_waste`, '%Y') as date, SUM(waste) as wastekgs
	            FROM  `waste`
	            WHERE location_id = 2 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
                GROUP BY DATE_FORMAT(`date_waste`, '%Y') 
                ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%Y')
            WHERE date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
            GROUP BY DATE_FORMAT(`date_roll`, '%Y') 
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
            
            $sql = "SELECT DATE_FORMAT(`date_roll`, '%d/%m/%Y') as date, ROUND(SUM(net_weight),2) as actual, waste.wastekgs, COUNT(DISTINCT (DATE_FORMAT(`date_roll`, '%d/%m/%Y'))) AS days
            FROM multilayer_rolls 
            LEFT JOIN (
	            SELECT DATE_FORMAT(`date_waste`, '%d/%m/%Y') as date, SUM(waste) as wastekgs
	            FROM  `waste`
	            WHERE location_id = 2 AND date_waste BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
                GROUP BY DATE_FORMAT(`date_waste`, '%d/%m/%Y') 
                ORDER BY `date_waste`) waste ON waste.date = DATE_FORMAT(`date_roll`, '%d/%m/%Y')
            WHERE date_roll BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
            GROUP BY DATE_FORMAT(`date_roll`, '%d/%m/%Y') 
            ORDER BY `date_roll`;";
            
        }
        
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $TARGETROW = $TARGET * $row['days'];
                $TARGETWASTEROW = $TARGETWASTE * $row['days'];
                $ACTUAL = $row['actual'];
                $EFF = round($ACTUAL *100/ $TARGET, 2);
                $WASTEKG = $row['wastekgs'];
                if(is_null($row['wastekgs']))
                {
                    $WASTEKG = 0;
                }
                if(is_null($row['actual']))
                {
                    $ACTUAL = 0;
                    $WASTEEFF = 0;
                }
                else
                {
                    $WASTEEFF  = round($WASTEKG* 100 / $ACTUAL , 2);
                }
                echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td class="text-right">'. number_format($TARGETROW,1,'.',',') .'</td>
                        <td class="text-right">'. number_format($ACTUAL,1,'.',',') .'</td>
                        <td class="text-right">'. number_format($EFF,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($WASTEKG,1,'.',',') .'</td>
                        <td class="text-right">'. number_format($TARGETWASTEROW,1,'.',',') .'</td>
                        <td class="text-right">'. number_format($WASTEEFF,2,'.',',') .'</td>
                    </tr>';
                $entrie = array( $row['date'], $TARGETROW);
                $entrie1 = array( $row['date'], $ACTUAL);
                $entrie2 = array( $row['date'], $TARGETWASTEROW);
                $entrie3 = array( $row['date'], $WASTEEFF);
                if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
                {
                    $entrie = array( $row['date2'], $TARGETROW);
                    $entrie1 = array( $row['date2'],$ACTUAL);
                    $entrie2 = array( $row['date2'], $TARGETWASTEROW);
                    $entrie3 = array( $row['date2'], $WASTEEFF);
                }
                array_push($a,$entrie);
                array_push($b,$entrie1);
                array_push($c,$entrie2);
                array_push($d,$entrie3);
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
            axisY: {title: "KGS" },
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
        echo ' yValueFormatString: "#,###.# Kgs",
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
                type: "column",
		      showInLegend: true,
		      name: "Actual",';
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
        echo ' yValueFormatString: "#,###.# Kgs",
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
            axisY: {title: "Waste %" },
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
        echo ' yValueFormatString: "#,###.# Kgs",
                dataPoints: [ ';
        if(!empty($_POST['searchBy']) and $_POST['searchBy']==2)
        {  
            foreach($c as $value) {
                $var = (int) explode("/", $value[0])[0]-1;
                echo '{ x: new Date('. explode("/", $value[0])[1] . ','. $var .',1), y: '. $value[1].'},';
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
                type: "column",
		      showInLegend: true,
		      name: "Actual",';
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
        echo ' yValueFormatString: "#,###.# Kgs",
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
        echo'] }]});
        chart.render(); 
        </script>'; 
    }
	
	/**
     * Checks gives the customers
     *
     */
    public function giveSalesOrders()
    {
        $sql = "SELECT `sales_orders`.`sales_order_id`,
					`sales_orders`.`sales_order_no`,
					`sales_orders`.`order_date`, customer_name,
					`sales_orders`.`customer_lpo`,
					`sales_orders`.`product_name`,
					`sales_orders`.`order_qty`,
					`sales_orders`.`delivery_date`,
					`sales_orders`.`price`,
					`sales_orders`.`terms`,
					`sales_orders`.`status`,username
				FROM `united_db`.`sales_orders`
				NATURAL JOIN customers
				NATURAL JOIN users;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
               echo '<tr>
                                <td>'. $row['sales_order_no'] .'</td>
                                <td>'. $row['order_date'] .'</td>
                                <td>'. $row['customer_name'] .'</td>
                                <td >'. $row['customer_lpo'] .'</td>
                                <td>'. $row['product_name'] .'</td>
                                <td >'. $row['order_qty'] .'</td>
                                <td >'. $row['delivery_date'] .'</td>
                                <td >'. $row['price'] .'</td>
                                <td >'. $row['terms'] .'</td>
                                <td >'. $row['status'] .'</td>
                                <td >'. $row['username'] .'</td>
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
     * Checks gives the customers
     *
     */
    public function giveProductionPlan()
    {
        $sql = "SELECT customer_name,
					`sales_orders`.`product_name`,
					`sales_orders`.`order_qty`
				FROM `united_db`.`sales_orders`
				NATURAL JOIN customers
				WHERE sales_orders.status  = 0
                ORDER BY sales_orders.order_date;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
			$date = new DateTime('now');
			$x = 0;
			$y = 0;
            while($row = $stmt->fetch())
            {
			   $x = $row['order_qty'];
			   if($y > 0)
			   {
				  $x = $x - $y;
				  if($x > 0)
				   {
					   echo '<tr>
								<td>'. $date->format('d/m/Y') .'</td>
								<td>'. $row['customer_name'] .'</td>
								<td >'. $row['product_name'] .'</td>
								<td >'. $y .'</td>
							</tr>';
				   }
				   else
				   {
					    echo '<tr>
								<td>'. $date->format('d/m/Y') .'</td>
								<td>'. $row['customer_name'] .'</td>
								<td >'. $row['product_name'] .'</td>
								<th class="text-success">'. $y .'</th>
							</tr>';
				   }
				   $y = 0;
				   $date->modify('+1 day');	
			   }
			   while ($x > 9000)
			   {
				  echo '<tr>
							<td>'. $date->format('d/m/Y') .'</td>
							<td>'. $row['customer_name'] .'</td>
							<td >'. $row['product_name'] .'</td>
							<td >'. 9000 .'</td>
						</tr>';
				   $x = $x - 9000;
				   $date->modify('+1 day');
			   }
			   if ($x > 0)
			   {
					echo '<tr>
							<td>'. $date->format('d/m/Y') .'</td>
							<td>'. $row['customer_name'] .'</td>
							<td >'. $row['product_name'] .'</td>
							<th class="text-success">'. $x .'</th>
						</tr>';
				   $y = 9000 - $x;
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
     * Checks and inserts a new sales order
     *
     * @return boolean  true if can insert  false if not
     */
    public function createSalesOrder()
    {
        $salesno = $date = $customer = $remarks = $product = $qty = $deliverydate = $price = $terms = "";
        
        $salesno = trim($_POST["salesno"]);
        $salesno = stripslashes($salesno);
        $salesno = htmlspecialchars($salesno);

		$date = "NOW()";
        if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = "".$newDateString ."";
        }
		
        $remarks = stripslashes($_POST["remarks"]);
        $remarks = htmlspecialchars($remarks);
		
		$deliverydate = "NULL";
        if(!empty($_POST['deliverydate']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $deliverydate = "'".$newDateString ."'";
        }
		
        $customer = trim($_POST["customer"]);
        $customer = stripslashes($customer);
        $customer = htmlspecialchars($customer);
        
        $product = trim($_POST["product"]);
        $product = stripslashes($product);
        $product = htmlspecialchars($product);
        
        $qty = trim($_POST["qty"]);
        $qty = stripslashes($qty);
        $qty = htmlspecialchars($qty);
        
        $price = trim($_POST["price"]);
        $price = stripslashes($price);
        $price = htmlspecialchars($price);
		
		$terms = trim($_POST["terms"]);
        $terms = stripslashes($terms);
        $terms = htmlspecialchars($terms);
        
        $sql = "INSERT INTO `united_db`.`sales_orders`(`sales_order_id`,`sales_order_no`,`order_date`,`customer_id`,`customer_lpo`,`product_name`,`order_qty`,`delivery_date`,`price`,`terms`,`status`,`user_id`)VALUES(NULL,:salesno,:date,:customer,:remarks,:product,:qty,".$deliverydate.",:price,:terms,0,:user);";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":salesno", $salesno, PDO::PARAM_STR);
            $stmt->bindParam(":date", $date, PDO::PARAM_STR);
            $stmt->bindParam(":customer", $customer, PDO::PARAM_INT);
            $stmt->bindParam(":remarks", $remarks, PDO::PARAM_STR);
            $stmt->bindParam(":product", $product, PDO::PARAM_STR);
            $stmt->bindParam(":qty", $qty, PDO::PARAM_INT);
            $stmt->bindParam(":price", $price, PDO::PARAM_STR);
            $stmt->bindParam(":terms", $terms, PDO::PARAM_STR);
            $stmt->bindParam(":user", $_SESSION['Userid'], PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            
            echo '<strong>SUCCESS!</strong> The sales order was successfully added to the database.';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> The sales order number: <strong>'. $salesno .'</strong> is already in the database. If you want to change the information, please try updating it.<br>';
            } else {
              echo '<strong>ERROR</strong> Could not insert the sales order into the database. Please try again.<br>'. $e->getMessage();
            }
            
            return FALSE;
        } 

    }
    
}