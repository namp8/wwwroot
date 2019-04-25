<?php

/**
 * Handles user interactions within the materials
 *
 * PHP version 5
 *
 * @author Natalia Montañez
 * @copyright 2017 Natalia Montañez
 *
 */
class Materials
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
				WHERE `consumables` = 0
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
	
	
	
	 public function consumablesDropdown()
    {
        $sql = "SELECT `materials`.`material_id`,
                `materials`.`material_name`,
                `materials`.`material_grade`
                FROM `materials` 
				WHERE `consumables` = 1
                ORDER BY `materials`.`material_name`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                echo  '<li><a id="'. $NAME .'" onclick="selectConsumable(\''. $ID .'\',\''. $NAME .'\',\''. $GRADE .'\')"><b>'. $NAME .'</b>&nbsp - &nbsp'. $GRADE .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	
	
	public function materialsKgDropdown()
    {
        $sql = "SELECT `materials`.`material_id`,`materials`.`kgs_bag`,
                `materials`.`material_name`,
                `materials`.`material_grade`
                FROM `materials`
				WHERE `semifinished` = 0 AND `finished` = 0
                ORDER BY `materials`.`material_name`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                $KGS = $row['kgs_bag'];
				
                echo  '<li><a id="'. $NAME .' - '. $GRADE .'" onclick="selectMaterial(\''. $ID .'\',\''. $NAME .'\',\''. $GRADE .'\',\''. $KGS .'\')"><b>'. $NAME .'</b>&nbsp - &nbsp'. $GRADE .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	
	public function pvcconesDropdown()
    {
        $sql = "SELECT `materials`.`material_id`,`materials`.`kgs_bag`,
                `materials`.`material_name`,
                `materials`.`material_grade`
                FROM `materials`
				WHERE `pvccones` = 1
                ORDER BY `materials`.`material_name`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                $KGS = $row['kgs_bag'];
				
                echo  '<li><a id="'. $NAME .' - '. $GRADE .'" onclick="selectMaterial(\''. $ID .'\',\''. $NAME .'\',\''. $GRADE .'\',\''. $KGS .'\')"><b>'. $NAME .'</b>&nbsp - &nbsp'. $GRADE .'</a></li>'; 
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
    public function materialsStockDropdown($machine)
    {
        $sql = "SELECT `materials`.`material_id`,`materials`.`material_name`,`materials`.`material_grade`,`stock_materials`.`bags`
				FROM `stock_materials`
				NATURAL JOIN materials
				WHERE machine_id = ". $machine ." AND `material` = 1
                ORDER BY `materials`.`material_name`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags'];
                echo  '<li><a id="'. $NAME .'" onclick="selectMaterial(\''. $ID .'\',\''. $NAME .'\',\''. $GRADE .'\',\''. $BAGS .'\')"><b>'. $NAME .'</b>&nbsp - &nbsp'. $GRADE .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	
	public function sparePartsStockDropdown($machine)
    {
        $sql = "SELECT `materials`.`material_id`,`materials`.`material_name`,`materials`.`material_grade`,`stock_materials`.`bags`
				FROM `stock_materials`
				NATURAL JOIN materials
				WHERE machine_id = ". $machine ." AND `spare_parts` = 1
                ORDER BY `materials`.`material_name`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags'];
                echo  '<li><a id="'. $NAME .'" onclick="selectMaterial(\''. $ID .'\',\''. $NAME .'\',\''. $GRADE .'\',\''. $BAGS .'\')"><b>'. $NAME .'</b>&nbsp - &nbsp'. $GRADE .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	
	public function inksStockDropdown($machine)
    {
        $sql = "SELECT `materials`.`material_id`,`materials`.`material_name`,`materials`.`material_grade`,`stock_materials`.`bags`
				FROM `stock_materials`
				NATURAL JOIN materials
				WHERE machine_id = ". $machine ." AND `color` = 1
                ORDER BY `materials`.`material_name`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags'];
                echo  '<li><a id="'. $NAME .'" onclick="selectMaterial(\''. $ID .'\',\''. $NAME .'\',\''. $GRADE .'\',\''. $BAGS .'\')"><b>'. $NAME .'</b>&nbsp - &nbsp'. $GRADE .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	public function masterbatchStockDropdown($machine)
    {
        $sql = "SELECT `materials`.`material_id`,`materials`.`material_name`,`materials`.`material_grade`,`stock_materials`.`bags`
				FROM `stock_materials`
				NATURAL JOIN materials
				WHERE machine_id = ". $machine ." AND `master_batch` = 1
                ORDER BY `materials`.`material_name`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags'];
                echo  '<li><a id="'. $NAME .'" onclick="selectMaterial(\''. $ID .'\',\''. $NAME .'\',\''. $GRADE .'\',\''. $BAGS .'\')"><b>'. $NAME .'</b>&nbsp - &nbsp'. $GRADE .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	
	public function consumablesStockDropdown($machine)
    {
        $sql = "SELECT `materials`.`material_id`,`materials`.`material_name`,`materials`.`material_grade`,`stock_materials`.`bags`
				FROM `stock_materials`
				NATURAL JOIN materials
				WHERE machine_id = ". $machine ." AND `consumables` = 1
                ORDER BY `materials`.`material_name`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags'];
                echo  '<li><a id="'. $NAME .'" onclick="selectMaterial(\''. $ID .'\',\''. $NAME .'\',\''. $GRADE .'\',\''. $BAGS .'\')"><b>'. $NAME .'</b>&nbsp - &nbsp'. $GRADE .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	
	public function semifinishedStockDropdown($machine)
    {
        $sql = "SELECT `materials`.`material_id`,`materials`.`material_name`,`materials`.`material_grade`,`stock_materials`.`bags`
				FROM `stock_materials`
				NATURAL JOIN materials
				WHERE machine_id = ". $machine ." AND `semifinished` = 1
                ORDER BY `materials`.`material_name`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags'];
                echo  '<li><a id="'. $NAME .'" onclick="selectMaterial(\''. $ID .'\',\''. $NAME .'\',\''. $GRADE .'\',\''. $BAGS .'\')"><b>'. $NAME .'</b>&nbsp - &nbsp'. $GRADE .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	
	public function finishedStockDropdown($machine)
    {
        $sql = "SELECT `materials`.`material_id`,`materials`.`material_name`,`materials`.`material_grade`,`stock_materials`.`bags`
				FROM `stock_materials`
				NATURAL JOIN materials
				WHERE machine_id = ". $machine ." AND `finished` = 1
                ORDER BY `materials`.`material_name`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['material_id'];
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags'];
                echo  '<li><a id="'. $NAME .'" onclick="selectMaterial(\''. $ID .'\',\''. $NAME .'\',\''. $GRADE .'\',\''. $BAGS .'\')"><b>'. $NAME .'</b>&nbsp - &nbsp'. $GRADE .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
    
	public function giveMaterials($type)
	{
		$color = $master_batch = $material = $consumables = $semifinished = $finished = $pvccones = $spare_parts = 0;
		$where = "";
		if($type ==1)
		{
			$material = 1;
			$where = $where . "`material`= ". $material."";
		}
		else if($type ==2)
		{
			$color = 1;
			$where = $where . "`color` = ". $color ."";
		}
		else if($type ==3)
		{
			$master_batch = 1;
			$where = $where . "`master_batch` = ". $master_batch ." ";
		}
		else if($type ==4)
		{
			$consumables = 1;
			$where = $where . "`consumables` = ". $consumables ."";
		}
		else if($type ==5)
		{
			$semifinished = 1;
			$where = $where . "`semifinished` = ". $semifinished ."";
		}
		else if($type ==6)
		{
			$finished = 1;
			$where = $where . "`finished` = ". $finished ."";
		}
		else if($type ==7)
		{
			$spare_parts = 1;
			$where = $where . "`spare_parts` = ". $spare_parts ." ";
		}
        $sql = "SELECT `materials`.`material_id`,
    `materials`.`material_name`,
    `materials`.`material_grade`,
    `materials`.`kgs_bag`,
    `materials`.`color`,
    `materials`.`sacks`,
    `materials`.`cutting`,
    `materials`.`multilayer`,
    `materials`.`printing`,
    `materials`.`injection`,
    `materials`.`macchi`,
    `materials`.`packing`,
    `materials`.`recycle`,
    `materials`.`manufacturer`,
    `materials`.`master_batch`,
    `materials`.`material`,
    `materials`.`consumables`,
    `materials`.`semifinished`,
    `materials`.`finished`,
    `materials`.`pvccones`,
    `materials`.`spare_parts`
FROM `materials`
WHERE ". $where ."
ORDER BY `material_name`, `material_grade`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $SACKS = $CUTTING = $ML = $PTG = $INJ = $MACCHI = $PACKING = "";
				$sacks = $cutting = $multilayer = $printing = $injection = $macchi = $packing = false;
				
				if($row['sacks'] == 1)
				{
					$SACKS = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$sacks = true;
				}
				if($row['cutting'] == 1)
				{
					$CUTTING = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$cutting = true;
				}
				if($row['multilayer'] == 1)
				{
					$ML = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$multilayer = true;
				}
				if($row['printing'] == 1)
				{
					$PTG = '<i class="fa fa-check text-success" aria-hidden="true"></i>';					
					$printing = true;
				}
				if($row['injection'] == 1)
				{
					$INJ = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$injection = true;
				}
				if($row['macchi'] == 1)
				{
					$MACCHI = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$macchi = true;
				}
				if($row['packing'] == 1)
				{
					$PACKING = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$packing = true;
				}
				echo '<tr>
                        <td>'. $row['material_name'] .'</td>
                        <td>'. $row['material_grade'] .'</td>
                        <td>'. $row['kgs_bag'] .'</td>
                        <td class="text-center">'. $SACKS .'</td>
                        <td class="text-center">'. $CUTTING .'</td>
                        <td class="text-center">'. $ML .'</td>
                        <td class="text-center">'. $PTG .'</td>
                        <td class="text-center">'. $INJ .'</td>
                        <td class="text-center">'. $MACCHI .'</td>
                        <td class="text-center">'. $PACKING .'</td>
						<td><button class="btn btn-xs btn-warning" type="button" onclick="edit(\''. $row['material_id'] .'\',\''. $row['material_name'] .'\',\''. $row['material_grade'] .'\',\''. $row['kgs_bag'] .'\',\''. $sacks .'\',\''. $cutting .'\',\''. $multilayer .'\',\''. $printing .'\',\''. $injection .'\',\''. $macchi .'\',\''. $packing .'\')"><i class="fa fa-pencil" aria-hidden="true"></i></button>
						<button class="btn btn-xs btn-danger" type="button" onclick="deleteMaterial(\''. $row['material_id'] .'\',\''. $row['material_name'] .'\',\''. $row['material_grade'] .'\')">X</button></td>
                    </tr>';
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
    }
	
	public function createMaterial($type)
    {
        $name = $grade = $kgs = "";
        $sacks = $cutting = $multilayer = $printing = $injection = $macchi = $packing = 0;
		$color = $master_batch = $material = $consumables = $semifinished = $finished = $pvccones = $spare_parts = 0;
		
		if($type ==1)
		{
			$material = 1;
		}
		else if($type ==2)
		{
			$color = 1;
		}
		else if($type ==3)
		{
			$master_batch = 1;
		}
		else if($type ==4)
		{
			$consumables = 1;
		}
		else if($type ==5)
		{
			$semifinished = 1;
		}
		else if($type ==6)
		{
			$finished = 1;
		}
		else if($type ==7)
		{
			$spare_parts = 1;
		}
		
        $name = trim($_POST["name"]);
        $name = stripslashes($name);
        $name = htmlspecialchars($name);
		
		$grade = trim($_POST["grade"]);
        $grade = stripslashes($grade);
        $grade = htmlspecialchars($grade);
		
		$kgs = trim($_POST["kgs"]);
        $kgs = stripslashes($kgs);
        $kgs = htmlspecialchars($kgs);
		
		if(isset($_POST["material"]))
		{
			$array = $_POST["material"];
			foreach($array as $materials)
			{
				if($materials == "sacks")
				{
					$sacks = 1;
				}
				else if($materials == "cutting")
				{
					$cutting = 1;
				}
				else if($materials == "multilayer")
				{
					$multilayer = 1;
				}
				else if($materials == "printing")
				{
					$printing = 1;
				}
				else if($materials == "injection")
				{
					$injection = 1;
				}
				else if($materials == "macchi")
				{
					$macchi = 1;
				}
				else if($materials == "packing")
				{
					$packing = 1;
				}
			}
		}
		else
		{
			echo '<strong>ERROR</strong> You did not choose any sections. Please try again.'; 
			return FALSE;
		}
        
		
        $sql = "INSERT INTO `materials`
(`material_id`,`material_name`,`material_grade`,`kgs_bag`,`color`,`sacks`,`cutting`,`multilayer`,`printing`,`injection`,`macchi`,`packing`,`master_batch`,`material`,`consumables`,`semifinished`,`finished`,`pvccones`,`spare_parts`) VALUES (NULL,:name,:grade,:kgs,:color, :sacks,:cutting,:multilayer,:printing,:injection,:macchi,:packing,:master_batch,:material,:consumables,:semifinished,:finished,:pvccones,:spare_parts);";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":grade", $grade, PDO::PARAM_STR);
            $stmt->bindParam(":kgs", $kgs, PDO::PARAM_STR);
            $stmt->bindParam(":color", $color, PDO::PARAM_INT);
            $stmt->bindParam(":sacks", $sacks, PDO::PARAM_INT);
            $stmt->bindParam(":cutting", $cutting, PDO::PARAM_INT);
            $stmt->bindParam(":multilayer", $multilayer, PDO::PARAM_INT);
            $stmt->bindParam(":printing", $printing, PDO::PARAM_INT);
            $stmt->bindParam(":injection", $injection, PDO::PARAM_INT);
            $stmt->bindParam(":macchi", $macchi, PDO::PARAM_INT);
            $stmt->bindParam(":packing", $packing, PDO::PARAM_INT);
            $stmt->bindParam(":master_batch", $master_batch, PDO::PARAM_INT);
            $stmt->bindParam(":material", $material, PDO::PARAM_INT);
            $stmt->bindParam(":consumables", $consumables, PDO::PARAM_INT);
            $stmt->bindParam(":semifinished", $semifinished, PDO::PARAM_INT);
            $stmt->bindParam(":finished", $finished, PDO::PARAM_INT);
            $stmt->bindParam(":pvccones", $pvccones, PDO::PARAM_INT);
            $stmt->bindParam(":spare_parts", $spare_parts, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            echo '<strong>SUCCESS!</strong> The item was successfully added to the database.';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> There is an item in the system with the same name and grade. Try updating it<br>';
            } else {
              echo '<strong>ERROR</strong> Could not insert the item into the database. Please try again.<br>'. $e->getMessage();
            }
            return FALSE;
        } 

    }
	
	
	public function updateMaterial()
    {
        $id = $name = $grade = $kgs = "";
        $sacks = $cutting = $multilayer = $printing = $injection = $macchi = $packing = 0;
		
		$id = trim($_POST["id_material"]);
        $id = stripslashes($id);
        $id = htmlspecialchars($id);
		
        $name = trim($_POST["name"]);
        $name = stripslashes($name);
        $name = htmlspecialchars($name);
		
		$grade = trim($_POST["grade"]);
        $grade = stripslashes($grade);
        $grade = htmlspecialchars($grade);
		
		$kgs = trim($_POST["kgs"]);
        $kgs = stripslashes($kgs);
        $kgs = htmlspecialchars($kgs);
		
		if(isset($_POST["material"]))
		{
			$array = $_POST["material"];
			foreach($array as $materials)
			{
				if($materials == "sacks")
				{
					$sacks = 1;
				}
				else if($materials == "cutting")
				{
					$cutting = 1;
				}
				else if($materials == "multilayer")
				{
					$multilayer = 1;
				}
				else if($materials == "printing")
				{
					$printing = 1;
				}
				else if($materials == "injection")
				{
					$injection = 1;
				}
				else if($materials == "macchi")
				{
					$macchi = 1;
				}
				else if($materials == "packing")
				{
					$packing = 1;
				}
			}
		}
		else
		{
			echo '<strong>ERROR</strong> You did not choose any sections. Please try again.'; 
			return FALSE;
		}
        
        $sql = "UPDATE `materials`
				SET
				`material_name` = :name,
				`material_grade` = :grade,
				`kgs_bag` = :kgs,
				`sacks` = :sacks,
				`cutting` = :cutting,
				`multilayer` = :multilayer,
				`printing` = :printing,
				`injection` = :injection,
				`macchi` = :macchi,
				`packing` = :packing
				WHERE `material_id` = :id";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
           	$stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":grade", $grade, PDO::PARAM_STR);
            $stmt->bindParam(":kgs", $kgs, PDO::PARAM_STR);
            $stmt->bindParam(":sacks", $sacks, PDO::PARAM_INT);
            $stmt->bindParam(":cutting", $cutting, PDO::PARAM_INT);
            $stmt->bindParam(":multilayer", $multilayer, PDO::PARAM_INT);
            $stmt->bindParam(":printing", $printing, PDO::PARAM_INT);
            $stmt->bindParam(":injection", $injection, PDO::PARAM_INT);
            $stmt->bindParam(":macchi", $macchi, PDO::PARAM_INT);
            $stmt->bindParam(":packing", $packing, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            echo '<strong>SUCCESS!</strong> The item was successfully updated the database.';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> There is an item in the system with the same name and grade. Try updating it<br>';
            } else {
              echo '<strong>ERROR</strong> Could not update the item into the database. Please try again.<br>'. $e->getMessage();
            }
            return FALSE;
        } 

    }
	
	public function deleteMaterial()
    {
        $id = "";
		
		$id = trim($_POST["id_material"]);
        $id = stripslashes($id);
        $id = htmlspecialchars($id);
        
        $sql = "DELETE FROM `materials`
				WHERE `material_id` = :id;";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            echo '<strong>SUCCESS!</strong> The item was successfully deleted from the database.';
            return TRUE;
        } catch (PDOException $e) {
              echo '<strong>ERROR</strong> Could not delete the item from the database. The reason is that the item has transfers, purchases, imports or more information associated to it.<br>'. $e->getMessage();
            return FALSE;
        } 

    }
    
    
}


?>