<?php

/**
 * Handles user interactions within the stock
 *
 * PHP version 5
 *
 * @author Natalia Montañez
 * @copyright 2017 Natalia Montañez
 *
 */
class Stock
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
     * Access to warehouse pages
	 * 1 =  purchases, 2= approve, 3= issue, 4= stock, 5= reports
     */
	public function access($page)
	{
		$search = "";
		if($page == 1 )
		{
			$search = "`warehouse_purchases`";
		}
		else if($page == 2 )
		{
			$search = "`warehouse_approve`";
		}
		else if($page == 3 )
		{
			$search = "`warehouse_issue`";
		}
		else if($page == 4 )
		{
			$search = "`warehouse_stock`";
		}
		else if($page == 5 )
		{
			$search = "`warehouse_reports`";
		}
		
		$sql = "SELECT ". $search ." as search
				FROM `users`
				WHERE user_id = ". $_SESSION['Userid'];
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                if($row['search'] == 1)
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
            echo 'Something went wrong.';  
        }
	}
	
	/**
     * Access to warehouse pages
	 * 1 =  purchases, 2= approve, 3= issue, 4= stock, 5= reports
     */
	public function administrators()
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
            echo 'Something went wrong.';  
        }
	}
	
	public function materialsconsumablesDropdown()
    {
        $sql = "SELECT `materials`.`material_id`,
                `materials`.`material_name`,
                `materials`.`material_grade`
                FROM `materials`
				WHERE `macchi` = 1  OR `consumables` = 1 OR `color` = 1 OR `master_batch` = 1
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
     * Give the RMI code for the next import
     *
     * This function outputs boolean if the transaction was succesful
     */
    public function giveRawMaterialImportCode()
    {
		$sql = "SELECT COUNT(`raw_materials_imports`.`raw_materials_imports_id`) as number, YEAR(current_date()) as year
				FROM `raw_materials_imports`
				WHERE YEAR(`raw_materials_imports`.pi_date) = YEAR(current_date());";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['number']+1;
                echo  'RMI/'.$ID.'/'.$row['year']; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo 'Something went wrong.';  
        }
	}
	
	  /**
     * Loads the dropdown of all the materials
     *
     * This function outputs <li> tags with materials
     */
    public function RMIordersDropdown()
    {
        $sql = "SELECT `raw_materials_imports`.`raw_materials_imports_id`,
					`raw_materials_imports`.`rmi_no`,
					DATE_FORMAT(`raw_materials_imports`.`exp_date_shipment`, '%d/%m/%Y') AS date
				FROM `raw_materials_imports`
				WHERE `raw_materials_imports`.`status` = 0
				ORDER BY `raw_materials_imports_id`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['raw_materials_imports_id'];
                $NO = $row['rmi_no'];
                $DATE = $row['date'];
                echo  '<li><a id="'. $NO .'" onclick="selectRMIorder(\''. $ID .'\',\''. $NO .'\',\''. $DATE .'\')">'. $NO .'</a></li>'; 
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
    public function RMIshipmentsDropdown()
    {
        $sql = "SELECT `raw_materials_imports`.`raw_materials_imports_id`,
					`raw_materials_imports`.`rmi_no`, `qty`,
					DATE_FORMAT(`raw_materials_imports`.`exp_date_arrival`, '%d/%m/%Y') AS date
				FROM `raw_materials_imports`
				WHERE `raw_materials_imports`.`status` = 1
				ORDER BY `raw_materials_imports_id`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['raw_materials_imports_id'];
                $NO = $row['rmi_no'];
                $DATE = $row['date'];
                $QTY = $row['qty'];
                echo  '<li><a id="'. $NO .'" onclick="selectRMIshipment(\''. $ID .'\',\''. $NO .'\',\''. $DATE .'\',\''. $QTY .'\')">'. $NO .'</a></li>'; 
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
    public function RMIclearingsDropdown()
    {
        $sql = "SELECT `raw_materials_imports`.`raw_materials_imports_id`,
					`raw_materials_imports`.`rmi_no`,
					DATE_FORMAT(`raw_materials_imports`.`bill_due_date`, '%d/%m/%Y') AS date
				FROM `raw_materials_imports`
				WHERE `raw_materials_imports`.`status` = 2
				ORDER BY `raw_materials_imports_id`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['raw_materials_imports_id'];
                $NO = $row['rmi_no'];
                $DATE = $row['date'];
                echo  '<li><a id="'. $NO .'" onclick="selectRMIcleared(\''. $ID .'\',\''. $NO .'\',\''. $DATE .'\')">'. $NO .'</a></li>'; 
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	
	/**
     * Open the Raw Material Import File
     */
    public function createLocalPurchase()
    {
        $material = $date = $invoice = $supplier = $bags = $amount = $remarks= "";
        
        $material = trim($_POST["material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
		
		if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = "'".$newDateString ."'";
        }
		else
		{
			$date = "CURRENT_DATE()";
		}
		
		
        $invoice = trim($_POST["invoice"]);
        $invoice = stripslashes($invoice);
        $invoice = htmlspecialchars($invoice);
		
		$supplier = trim($_POST["supplier"]);
        $supplier = stripslashes($supplier);
        $supplier = htmlspecialchars($supplier);
		        
        $bags = trim($_POST["bags"]);
        $bags = stripslashes($bags);
        $bags = htmlspecialchars($bags);
        
        $amount = trim($_POST["amount"]);
        $amount = stripslashes($amount);
        $amount = htmlspecialchars($amount);
		
        $cost = trim($_POST["cost"]);
        $cost = stripslashes($cost);
        $cost = htmlspecialchars($cost);
		
        $remarks = stripslashes($_POST["remarks"]);
        $remarks = htmlspecialchars($remarks);
        
		
        //CHECK THE ACTUAL BAGS IN STOCK MATERIAL 
        $sql = "SELECT stock_material_id, bags, material_name, material_grade
                FROM stock_materials 
				JOIN materials ON materials.material_id = stock_materials.material_id
                WHERE stock_materials.material_id = ". $material ." AND
                machine_id  = 1 ;";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$stmt = $this->_db->prepare($sql);
            $stmt->execute();
            if($row = $stmt->fetch())
            {
                $ID_SM = $row['stock_material_id'];
                $BAGSTOCK = $row['bags'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $newbags = $BAGSTOCK + $bags;
                //INCREASES THE BAGS FROM THE STOCK MATERIALS CHANGE THE STATUS TRANSFER, BAGS RECEIVED, AND USER RECEIVED
                $sql = "INSERT INTO `local_purchases`(`local_purchase_id`,`material_id`,`date_arrived`,`invoice_no`,`supplier`,`qty`,`amount`,`remarks`,`user_id`,`cost_kg`)VALUES
				(NULL,". $material .",". $date .",'". $invoice ."','". $supplier ."',". $bags .",". $amount .",'". $remarks."',".$_SESSION['Userid'].",". $cost .");
                UPDATE  `stock_materials`
                SET `bags` = ". $newbags ."
                WHERE `stock_material_id` = ". $ID_SM .";";
                try
                {   
                    $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
					$stmt = $this->_db->prepare($sql);
                    $stmt->execute();
                    $stmt->closeCursor();
                    echo '<strong>SUCCESS!</strong> The local purchase of the raw material '.$MATERIAL.' - '.$GRADE.' was successfully created in the system, and the material was received in the warehouse stock.';
					return TRUE;
                } 
                catch (PDOException $e) {
                      echo '<strong>ERROR</strong> Could not create the local purchase in the system. Please try again.<br>'. $e->getMessage();
            			return FALSE;
                } 
            }
            else
            {
                //INSERT THE BAGS FROM THE STOCK MATERIALS
                $sql = "INSERT INTO `local_purchases`(`local_purchase_id`,`material_id`,`date_arrived`,`invoice_no`,`supplier`,`qty`,`amount`,`remarks`,`user_id`,`cost_kg`)VALUES
				(NULL,". $material .",". $date .",'". $invoice ."','". $supplier ."',". $bags .",". $amount .",'". $remarks."',".$_SESSION['Userid'].",". $cost .");
                INSERT INTO  `stock_materials`(`stock_material_id`,`material_id`,`machine_id`,`bags`)VALUES(NULL,". $material .",1,". $bags. ");";
                try
                {   
                    $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
					$stmt = $this->_db->prepare($sql);
                    $stmt->execute();
                    $stmt->closeCursor();
                    echo '<strong>SUCCESS!</strong> The local purchase of the raw material was successfully created in the system, and the material was received in the warehouse stock.';
					return TRUE;
                } 
                catch (PDOException $e) {
                      echo '<strong>ERROR</strong> Could not create the local purchase in the system. Please try again.<br>'. $e->getMessage();
            return FALSE;
                } 
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e) {
             echo '<strong>ERROR</strong> Could not create the local purchase in the system. Please try again.<br>'. $e->getMessage();
            return FALSE;
        } 
            
    }
	
	/**
     * Open the Raw Material Import File
     */
    public function createLoanRawMaterial()
    {
        $material = $date = $invoice = $supplier = $bags = $remarks= "";
        
        $material = trim($_POST["material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
		
		if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = "'".$newDateString ."'";
        }
		else
		{
			$date = "CURRENT_DATE()";
		}
		
		
        $invoice = trim($_POST["invoice"]);
        $invoice = stripslashes($invoice);
        $invoice = htmlspecialchars($invoice);
		
		$supplier = trim($_POST["supplier"]);
        $supplier = stripslashes($supplier);
        $supplier = htmlspecialchars($supplier);
		        
        $bags = trim($_POST["bags"]);
        $bags = stripslashes($bags);
        $bags = htmlspecialchars($bags);
        		
        $remarks = stripslashes($_POST["remarks"]);
        $remarks = htmlspecialchars($remarks);
        
		
        //CHECK THE ACTUAL BAGS IN STOCK MATERIAL 
        $sql = "SELECT stock_material_id, bags, material_name, material_grade
                FROM stock_materials 
				JOIN materials ON materials.material_id = stock_materials.material_id
                WHERE stock_materials.material_id = ". $material ." AND
                machine_id  = 1 ;";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$stmt = $this->_db->prepare($sql);
            $stmt->execute();
            if($row = $stmt->fetch())
            {
                $ID_SM = $row['stock_material_id'];
                $BAGSTOCK = $row['bags'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $newbags = $BAGSTOCK + $bags;
                //INCREASES THE BAGS FROM THE STOCK MATERIALS CHANGE THE STATUS TRANSFER, BAGS RECEIVED, AND USER RECEIVED
                $sql = "INSERT INTO `rm_loans`(`rm_loans_id`,`material_id`,`date_arrived`,`invoice_no`,`supplier`,`qty`,`remarks`,`user_id`) VALUES
				(NULL,". $material .",". $date .",'". $invoice ."','". $supplier ."',". $bags .",'". $remarks ."',".$_SESSION['Userid'].");
                UPDATE  `stock_materials`
                SET `bags` = ". $newbags ."
                WHERE `stock_material_id` = ". $ID_SM .";";
                try
                {   
                    $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
					$stmt = $this->_db->prepare($sql);
                    $stmt->execute();
                    $stmt->closeCursor();
                    echo '<strong>SUCCESS!</strong> The loan of the raw material '.$MATERIAL.' - '.$GRADE.' was successfully created in the system, and the material was received in the warehouse stock.';
					return TRUE;
                } 
                catch (PDOException $e) {
                      echo '<strong>ERROR</strong> Could not create the loan of the raw material in the system. Please try again.<br>'. $e->getMessage();
            			return FALSE;
                } 
            }
            else
            {
                //INSERT THE BAGS FROM THE STOCK MATERIALS
                $sql = "INSERT INTO `rm_loans`(`rm_loans_id`,`material_id`,`date_arrived`,`invoice_no`,`supplier`,`qty`,`remarks`,`user_id`) VALUES
				(NULL,". $material .",". $date .",'". $invoice ."','". $supplier ."',". $bags .",'". $remarks ."',".$_SESSION['Userid'].");
                INSERT INTO  `stock_materials`(`stock_material_id`,`material_id`,`machine_id`,`bags`)VALUES(NULL,". $material .",1,". $bags. ");";
                try
                {   
                    $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
					$stmt = $this->_db->prepare($sql);
                    $stmt->execute();
                    $stmt->closeCursor();
                    echo '<strong>SUCCESS!</strong> The loan of the raw material was successfully created in the system, and the material was received in the warehouse stock.';
					return TRUE;
                } 
                catch (PDOException $e) {
                      echo '<strong>ERROR</strong> Could not create the loan of the raw material in the system. Please try again.<br>'. $e->getMessage();
            return FALSE;
                } 
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e) {
             echo '<strong>ERROR</strong> Could not create the loan of the raw material in the system. Please try again.<br>'. $e->getMessage();
            return FALSE;
        } 
            
    }
	
	/**
     * Open the Raw Material Import File
     */
    public function createPVC()
    {
        $material = $date = $pipes = $total = "";
        
        $material = trim($_POST["material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
		
		if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = "'".$newDateString ."'";
        }
		else
		{
			$date = "CURRENT_DATE()";
		}
		
        $pipes = trim($_POST["pipes"]);
        $pipes = stripslashes($pipes);
        $pipes = htmlspecialchars($pipes);
		
        $cones = trim($_POST["total"]);
        $cones = stripslashes($cones);
        $cones = htmlspecialchars($cones);
		
        //CHECK THE ACTUAL BAGS IN STOCK MATERIAL 
        $sql = 'SELECT stock_material_id, bags, material_name, material_grade, stock_materials.material_id
FROM stock_materials 
JOIN materials ON materials.material_id = stock_materials.material_id
WHERE material_name = "UPVC PIPES" AND machine_id  = 1 ;';
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$stmt = $this->_db->prepare($sql);
            $stmt->execute();
            if($row = $stmt->fetch())
            {
                $ID_SM = $row['stock_material_id'];
                $BAGSTOCK = $row['bags'];
                $MATERIAL = $row['material_name'];
                $ID = $row['material_id'];
                $GRADE = $row['material_grade'];
                $newbags = $BAGSTOCK - $pipes;
				if($newbags >= 0)
				{
					//DECREASES THE BAGS FROM THE STOCK MATERIALS CHANGE THE STATUS TRANSFER, BAGS RECEIVED, AND USER RECEIVED
					$sql = "
					UPDATE  `stock_materials`
					SET `bags` = ". $newbags ."
					WHERE `stock_material_id` = ". $ID_SM .";
					
					INSERT INTO `transformations`
					(`transformation_id`,`date`,`material_from`,`material_to`,`qty_from`,`qty_to`,`user_id`,`pvc`)
					VALUES
					(NULL,". $date .",". $ID .",". $material .",". $pipes .",". $cones .",". $_SESSION['Userid'] .",1);
					
					INSERT INTO  `stock_materials_transfers`(`stock_materials_transfers_id`,`machine_from`,`machine_to`,`material_id`,`date_required`,`bags_required`,`bags_approved`,`bags_issued`,`bags_receipt`,`user_id_required`,`user_id_approved`,`user_id_issued`,`user_id_receipt`,`status_transfer`,`remarks_approved`,`remarks_issued`)VALUES(NULL,1,100, ". $ID .",". $date .",". $pipes .",". $pipes .",". $pipes .",". $pipes .",". $_SESSION['Userid'] .",". $_SESSION['Userid'] .",". $_SESSION['Userid'] .",". $_SESSION['Userid'] .",3,NULL,NULL);
					
					
					INSERT INTO  `stock_materials_transfers`(`stock_materials_transfers_id`,`machine_from`,`machine_to`,`material_id`,`date_required`,`bags_required`,`bags_approved`,`bags_issued`,`bags_receipt`,`user_id_required`,`user_id_approved`,`user_id_issued`,`user_id_receipt`,`status_transfer`,`remarks_approved`,`remarks_issued`)VALUES(NULL,100,1, ". $material .",". $date .",". $cones .",". $cones .",". $cones .",". $cones .",". $_SESSION['Userid'] .",". $_SESSION['Userid'] .",". $_SESSION['Userid'] .",". $_SESSION['Userid'] .",3,NULL,NULL);";
					try
					{   
						$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
						$stmt = $this->_db->prepare($sql);
						$stmt->execute();
						$stmt->closeCursor();
						
						
						//CHECK THE ACTUAL CONES IN STOCK MATERIAL 
						$sql = "SELECT stock_material_id, bags, material_name, material_grade
								FROM stock_materials 
								JOIN materials ON materials.material_id = stock_materials.material_id
								WHERE stock_materials.material_id = ". $material ." AND
								machine_id  = 1 ;";
						try
						{   
							$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
							$stmt = $this->_db->prepare($sql);
							$stmt->execute();
							if($row = $stmt->fetch())
							{
								$ID_SM = $row['stock_material_id'];
								$BAGSTOCK = $row['bags'];
								$MATERIAL = $row['material_name'];
								$GRADE = $row['material_grade'];
								$newbags = $BAGSTOCK + $cones;
								//INCREASES THE BAGS FROM THE STOCK MATERIALS CHANGE THE STATUS TRANSFER, BAGS RECEIVED, AND USER RECEIVED
								$sql = "
								UPDATE  `stock_materials`
								SET `bags` = ". $newbags ."
								WHERE `stock_material_id` = ". $ID_SM .";";
								try
								{   
									$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
									$stmt = $this->_db->prepare($sql);
									$stmt->execute();
									$stmt->closeCursor();
									echo '<strong>SUCCESS!</strong> The '.$pipes.' pipes of UPVC PIPE 3 inch x 6 m were cutted into '.$cones.' cones of '. $MATERIAL.'.';
									return TRUE;
								} 
								catch (PDOException $e) {
									  echo '<strong>ERROR</strong> Could not create the PVC cones from the UPVC pipes. Please try again.<br>'. $e->getMessage();
										return FALSE;
								} 
							}
							else
							{
								//INSERT THE BAGS FROM THE STOCK MATERIALS
								$sql = "
								INSERT INTO  `stock_materials`(`stock_material_id`,`material_id`,`machine_id`,`bags`)VALUES(NULL,". $material .",1,". $cones. ");";
								try
								{   
									$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
									$stmt = $this->_db->prepare($sql);
									$stmt->execute();
									$stmt->closeCursor();
									echo '<strong>SUCCESS!</strong> The '.$pipes.' pipes of UPVC PIPE 3 inch x 6 m were cutted into '.$cones.' PVC cones.';
									return TRUE;
								} 
								catch (PDOException $e) {
									  echo '<strong>ERROR</strong> Could not create the PVC cones from the UPVC pipes. Please try again.<br>'. $e->getMessage();
										return FALSE;
								} 
							}
							$stmt->closeCursor();
						}
						catch (PDOException $e) {
							echo '<strong>ERROR</strong> Could not create the PVC cones from the UPVC pipes. Please try again.<br>'. $e->getMessage();
							return FALSE;
						} 
					} 
					catch (PDOException $e) {
						  echo '<strong>ERROR</strong> Could not create the loan of the raw material in the system. Please try again.<br>'. $e->getMessage();
							return FALSE;
					} 
				}
                else
                {
                    echo '<strong>ERROR: </strong>There is not enough pipes of the material:<b> '.$MATERIAL .' </b>on stock. There are <b>'. $BAGSTOCK .' pipes</b> and you want to cut <b>'. $bags .' pipes</b>. Please try with a lower number of pipes.';
                    return FALSE;
                }
            }
            else
            {
                echo '<strong>ERROR: </strong>There is not enough pipes of of this material on this stock location.';
                return FALSE;
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e) {
             echo '<strong>ERROR</strong> Could not create the pvc cones in the system. Please try again.<br>'. $e->getMessage();
            return FALSE;
        } 
            
    }
	
	
	public function LoansReport()
    {
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
		$sql = "SELECT `rm_loans`.`rm_loans_id`,
					`rm_loans`.`material_id`, material_name, material_grade,
					`rm_loans`.`date_arrived`,
					`rm_loans`.`invoice_no`,
					`rm_loans`.`supplier`,
					`rm_loans`.`qty`,
					`rm_loans`.`remarks`,
					`rm_loans`.`user_id`, username
				FROM `rm_loans`
				JOIN materials ON materials.material_id = `rm_loans`.material_id
				JOIN users ON users.user_id = `rm_loans`.user_id
				WHERE date_arrived BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59' ";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['qty'];
                $REMARKS = $row['remarks'];
                 echo '<tr>
                        <td>'. $row['date_arrived'] .'</td>
                        <td>'. $MATERIAL .' - '. $GRADE .'</td>
                        <td>'. $row['supplier'] .'</td>
                        <td>'. $row['invoice_no'] .'</td>
                        <td class="text-right">'. number_format((float) $BAGS,0,'.',',') .'</td>
                        <td>'. $row['username'] .'</td>
                        <td>'. $REMARKS .'</td>
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
	
	public function LocalPurchasesReport()
    {
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
		$sql = "SELECT `local_purchases`.`local_purchase_id`,
				`local_purchases`.`material_id`, material_name, material_grade,
				`local_purchases`.`date_arrived`,
				`local_purchases`.`invoice_no`,
				`local_purchases`.`supplier`,
				`local_purchases`.`qty`,
				`local_purchases`.`amount`,
				`local_purchases`.`remarks`,
				`local_purchases`.`user_id`, username,
				`local_purchases`.`cost_kg`
			FROM `local_purchases`
			JOIN materials ON materials.material_id = local_purchases.material_id
			JOIN users ON users.user_id = local_purchases.user_id
			WHERE date_arrived BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59' ;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['qty'];
                $REMARKS = $row['remarks'];
                 echo '<tr>
                        <td>'. $row['date_arrived'] .'</td>
                        <td>'. $MATERIAL .' - '. $GRADE .'</td>
                        <td>'. $row['supplier'] .'</td>
                        <td>'. $row['invoice_no'] .'</td>
                        <td class="text-right">'. number_format((float) $BAGS,0,'.',',') .'</td>
                        <td class="text-right">'. number_format((float) $row['amount'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format((float) $row['cost_kg'],2,'.',',') .'</td>
                        <td>'. $row['username'] .'</td>
                        <td>'. $REMARKS .'</td>
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
	
	public function givePVC()
    {
		$sql = "SELECT `transformations`.`date`, material_name,
    `transformations`.`qty_from`,
    `transformations`.`qty_to`,username
FROM `transformations`
JOIN materials ON materials.material_id = material_to
JOIN users ON users.user_id = transformations.user_id
WHERE `pvc`=1 
ORDER BY `date` DESC;";
        
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				$UNITS = ceil($row['qty_to'] / $row['qty_from']);
                 echo '<tr>
                        <td>'. $row['date'] .'</td>
                        <td>'. $row['material_name'] .'</td>
                        <td  style="text-align:right">'. $row['qty_from'] .'</td>
                        <td  style="text-align:right">'. $UNITS .'</td>
                        <td  style="text-align:right">'. $row['qty_to'] .'</td>
                        <td>'. $row['username'] .'</td>
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
	
	public function giveLoansRawMaterial()
    {
		$sql = "SELECT `rm_loans`.`rm_loans_id`,
					`rm_loans`.`material_id`, material_name, material_grade,
					`rm_loans`.`date_arrived`,
					`rm_loans`.`invoice_no`,
					`rm_loans`.`supplier`,
					`rm_loans`.`qty`,
					`rm_loans`.`remarks`,
					`rm_loans`.`user_id`, username
				FROM `rm_loans`
				JOIN materials ON materials.material_id = `rm_loans`.material_id
				JOIN users ON users.user_id = `rm_loans`.user_id
				WHERE MONTH(date_arrived) >= MONTH(CURRENT_DATE())-2 AND YEAR(date_arrived) = YEAR(CURRENT_DATE())
				ORDER BY date_arrived DESC;";
        
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['qty'];
                $REMARKS = $row['remarks'];
                 echo '<tr>
                        <td>'. $row['date_arrived'] .'</td>
                        <td>'. $MATERIAL .' - '. $GRADE .'</td>
                        <td>'. $row['supplier'] .'</td>
                        <td>'. $row['invoice_no'] .'</td>
                        <td class="text-right">'. number_format((float) $BAGS,0,'.',',') .'</td>
                        <td>'. $row['username'] .'</td>
                        <td>'. $REMARKS .'</td>
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
	
	public function giveLocalPurchases()
    {
		$sql = "SELECT `local_purchases`.`local_purchase_id`,
				`local_purchases`.`material_id`, material_name, material_grade,
				`local_purchases`.`date_arrived`,
				`local_purchases`.`invoice_no`,
				`local_purchases`.`supplier`,
				`local_purchases`.`qty`,
				`local_purchases`.`amount`,
				`local_purchases`.`remarks`,
				`local_purchases`.`user_id`, username,
				`local_purchases`.`cost_kg`
			FROM `local_purchases`
			JOIN materials ON materials.material_id = local_purchases.material_id
			JOIN users ON users.user_id = local_purchases.user_id
			WHERE MONTH(date_arrived) >= MONTH(CURRENT_DATE())-1 AND YEAR(date_arrived) = YEAR(CURRENT_DATE())
			ORDER BY date_arrived DESC;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['qty'];
                $REMARKS = $row['remarks'];
                 echo '<tr>
                        <td>'. $row['date_arrived'] .'</td>
                        <td>'. $MATERIAL .' - '. $GRADE .'</td>
                        <td>'. $row['supplier'] .'</td>
                        <td>'. $row['invoice_no'] .'</td>
                        <td class="text-right">'. number_format((float) $BAGS,2,'.',',') .'</td>
                        <td class="text-right">'. number_format((float) $row['amount'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format((float) $row['cost_kg'],2,'.',',') .'</td>
                        <td>'. $row['username'] .'</td>
                        <td>'. $REMARKS .'</td>
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
	
	
	/**
     * Open the Raw Material Import File
     */
    public function openFileRMI()
    {
        $rmi = $material = $date = $pino = $pidate = $supplier = $manufacturer = $bags =$remarks= "";
        
        $rmi = trim($_POST["rmi"]);
        $rmi = stripslashes($rmi);
        $rmi = htmlspecialchars($rmi);
				
        $material = trim($_POST["material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
		
		$pino = trim($_POST["pino"]);
        $pino = stripslashes($pino);
        $pino = htmlspecialchars($pino);
		
		
		if(!empty($_POST['pidate']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['pidate']);
            $newDateString = $myDateTime->format('Y-m-d');
            $pidate = "'".$newDateString ."'";
        }
		else
		{
			$pidate = "NULL";
		}
		
		$supplier = trim($_POST["supplier"]);
        $supplier = stripslashes($supplier);
        $supplier = htmlspecialchars($supplier);
				
        $manufacturer = trim($_POST["manufacturer"]);
        $manufacturer = stripslashes($manufacturer);
        $manufacturer = htmlspecialchars($manufacturer);
        
        $bags = trim($_POST["bags"]);
        $bags = stripslashes($bags);
        $bags = htmlspecialchars($bags);
        
        $amount = trim($_POST["amount"]);
        $amount = stripslashes($amount);
        $amount = htmlspecialchars($amount);
		
        $remarks = stripslashes($_POST["remarks"]);
        $remarks = htmlspecialchars($remarks);
        
		if(!empty($_POST['exdateship']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['exdateship']);
            $newDateString = $myDateTime->format('Y-m-d');
            $exdate = "'".$newDateString ."'";
        }
		else
		{
			$exdate = "NULL";
		}
        
		$sql = "INSERT INTO `raw_materials_imports`(`raw_materials_imports_id`,`rmi_no`,`material_id`,`pi_no`,`pi_date`,`supplier`,`manufacturer`,`qty`,
		`amount`,`exp_date_shipment`,`remarks_order`,`user_order`)  
		VALUES
		(NULL,'". $rmi ."',". $material .",'". $pino ."',". $pidate .",'". $supplier ."','". $manufacturer ."',". $bags .",". $amount .",". $exdate .",'". $remarks."',".$_SESSION['Userid'].");";
		try
		{   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			$stmt->closeCursor();
			echo '<strong>SUCCESS!</strong> The <strong>'. $rmi .'</strong> file was successfully created in the system.';
			return TRUE;
		} 
		catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> The file with the code <strong>'. $rmi .'</strong> is alredy in the system.<br>';
            } else {
              echo '<strong>ERROR</strong> Could not create the file in the system. Please try again.<br>'. $e->getMessage();
            }
            
            return FALSE;
        } 

            
    }
	
	/**
     * Inputs the raw material shipment information
     */
    public function shipRMI()
    {
        $rmi = $blno = $bldate = $cino = $delay = $terms = $datearr = $duedate = $remarks= "";
        
        $rmi = trim($_POST["rmino1"]);
        $rmi = stripslashes($rmi);
        $rmi = htmlspecialchars($rmi);
		
		$blno = trim($_POST["blno"]);
        $blno = stripslashes($blno);
        $blno = htmlspecialchars($blno);
		
		if(!empty($_POST['bldate']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['bldate']);
            $newDateString = $myDateTime->format('Y-m-d');
            $bldate = "'".$newDateString ."'";
        }
		else
		{
			$bldate = "NULL";
		}
		
		$cino = trim($_POST["cino"]);
        $cino = stripslashes($cino);
        $cino = htmlspecialchars($cino);
		
		if(empty($_POST['delay1']))
        {
            $delay = "NULL";
        }
		else
		{
			$delay = trim($_POST["delay1"]);
			$delay = stripslashes($delay);
			$delay = htmlspecialchars($delay);
		}
		
        $terms = trim($_POST["terms"]);
        $terms = stripslashes($terms);
        $terms = htmlspecialchars($terms);
		
		if(!empty($_POST['exdatearr']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['exdatearr']);
            $newDateString = $myDateTime->format('Y-m-d');
            $datearr = "'".$newDateString ."'";
        }
		else
		{
			$datearr = "NULL";
		}
		
		if(!empty($_POST['duedate']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['duedate']);
            $newDateString = $myDateTime->format('Y-m-d');
            $duedate = "'".$newDateString ."'";
        }
		else
		{
			$duedate = "NULL";
		}
		
        $remarks = stripslashes($_POST["remarks1"]);
        $remarks = htmlspecialchars($remarks);
        
        
		$sql = "UPDATE `raw_materials_imports`
				SET
				`bill_no` = '". $blno ."',
				`date_shipment` = ". $bldate .",
				`invoice_no` = '". $cino ."',
				`delay_sent` = ". $delay .",
				`terms` = ". $terms .",
				`exp_date_arrival` = ". $datearr .",
				`bill_due_date` = ". $duedate .",
				`remarks_shipped` = '". $remarks."',
				`user_shipped` = ".$_SESSION['Userid'].",
				`status` = 1
				WHERE `raw_materials_imports_id` = '". $rmi ."';";
		try
		{   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			$stmt->closeCursor();
			echo '<strong>SUCCESS!</strong> The raw material import was successfully shipped on <strong>'. $bldate .'</strong>.';
			return TRUE;
		} 
		catch (PDOException $e) {
			echo '<strong>ERROR</strong> Could not change the status of the file from ordered to shipped. Please try again.<br>'. $e->getMessage(); 
			return FALSE;
		} 
            
    }
	
	/**
     * Receive stock in the Warehouse, checking the actual bags in stock material, if there is not material in that stock location it will create it or if exist changing the status and then reducing it.
     *
     * This function outputs boolean if the transaction was succesful
     */
    public function clearRMI()
    {
        $rmi = $date1 = $date2 = $delay = $damaged = $remarks = $qty1 = $qty2 = "";
		
		$rmi = trim($_POST["rmino2"]);
        $rmi = stripslashes($rmi);
        $rmi = htmlspecialchars($rmi);
		
		if(!empty($_POST['date_cleared']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date_cleared']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date1 = "'".$newDateString ."'";
        }
		else
		{
			$date1 = "NULL";
		}
		
		if(!empty($_POST['date_cleared2']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date_cleared2']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date2 = "'".$newDateString ."'";
			
			$qty1 = trim($_POST["qtycleared"]);
			$qty1 = stripslashes($qty1);
        	$qty1 = htmlspecialchars($qty1);
			
			$qty2 = trim($_POST["qtycleared2"]);
			$qty2 = stripslashes($qty2);
			$qty2 = htmlspecialchars($qty2);
        }
		else
		{
			$date2 = "NULL";
			$qty1 = " `qty` ";
			$qty2 = "NULL";
		}
		
		if(empty($_POST['delay2']))
        {
            $delay = "NULL";
        }
		else
		{
			$delay = trim($_POST["delay2"]);
			$delay = stripslashes($delay);
			$delay = htmlspecialchars($delay);
		}
		
		$declaration = trim($_POST["declaration"]);
        $declaration = stripslashes($declaration);
        $declaration = htmlspecialchars($declaration);
		
		$damaged = trim($_POST["damaged"]);
        $damaged = stripslashes($damaged);
        $damaged = htmlspecialchars($damaged);
		
		$rate = trim($_POST["rate"]);
        $rate = stripslashes($rate);
        $rate = htmlspecialchars($rate);
		
		$duty = trim($_POST["duty"]);
        $duty = stripslashes($duty);
        $duty = htmlspecialchars($duty);
				
		$clearing = trim($_POST["clearing"]);
        $clearing = stripslashes($clearing);
        $clearing = htmlspecialchars($clearing);
		
		$unloading = trim($_POST["unloading"]);
        $unloading = stripslashes($unloading);
        $unloading = htmlspecialchars($unloading);
		
        $remarks = stripslashes($_POST["remarks2"]);
        $remarks = htmlspecialchars($remarks);
		
        //CHECK THE ACTUAL BAGS IN STOCK MATERIAL 
        $sql = "SELECT qty, `raw_materials_imports`.`material_id`, `raw_materials_imports`.`amount`, stock_material_id, bags, kgs_bag
				FROM `raw_materials_imports`
				JOIN materials ON `raw_materials_imports`.material_id = `materials`.material_id
				LEFT JOIN stock_materials ON `raw_materials_imports`.`material_id` = `stock_materials`.`material_id` AND machine_id = 1
				WHERE `raw_materials_imports_id` = ".$rmi." AND status = 1";
        try
        {   
			
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID_SM = $row['stock_material_id'];
                $BAGSTOCK = $row['bags'];
				$bags = $row['qty'];
                $newbags = $BAGSTOCK + $bags;
				$material = $row['material_id'];
				$kgs_bag = $row['kgs_bag'];
				$amount = $row['amount'];
				$kgs = $bags * $kgs_bag;
				$total = $duty + $clearing + $unloading + $amount;
				$cost = $total / $kgs;
				$cost = number_format($cost, 2, '.', '');
				
				if(!is_null($ID_SM))
				{
					//INCREASES THE BAGS FROM THE STOCK MATERIALS UODATE THE RAW MATERIAL IMPORT
					$sql = "UPDATE  `stock_materials`
					SET `bags` = ". $newbags ."
					WHERE `stock_material_id` = ". $ID_SM .";
					UPDATE `raw_materials_imports`
					SET
					`date_cleared` = ". $date1 .",
					`date_cleared2` = ". $date2 .",
					`qty_cleared` = ". $qty1 .",
					`qty_cleared2` = ". $qty2 .",
					`delay_arrived` = ". $delay .",
					`declaration_no` = '". $declaration ."',
					`damaged_qty` = ". $damaged .",
					`usd_rate` = ". $rate .",
					`duty` = ". $duty .",
					`clearing` = ". $clearing .",
					`unloading` = ". $unloading .",
					`cost_kg` = ". $cost .",
					`remarks_cleared` = '". $remarks."',
					`user_cleared` = ".$_SESSION['Userid'].",
					`status` = 2
					WHERE `raw_materials_imports_id` = ". $rmi .";";
				}
				else
				{
					$sql = "INSERT INTO  `stock_materials`(`stock_material_id`,`material_id`,`machine_id`,`bags`)VALUES(NULL,". $material .",1,". $bags. ");
					UPDATE `raw_materials_imports`
					SET
					`date_cleared` = ". $date1 .",
					`date_cleared2` = ". $date2 .",
					`qty_cleared` = ". $qty1 .",
					`qty_cleared2` = ". $qty2 .",
					`delay_arrived` = ". $delay .",
					`declaration_no` = '". $declaration ."',
					`damaged_qty` = ". $damaged .",
					`usd_rate` = ". $rate .",
					`duty` = ". $duty .",
					`clearing` = ". $clearing .",
					`unloading` = ". $unloading .",
					`cost_kg` = ". $cost .",
					`remarks_cleared` = '". $remarks."',
					`user_cleared` = ".$_SESSION['Userid'].",
					`status` = 2
					WHERE `raw_materials_imports_id` = ". $rmi .";";
				}
				
				
                try
                {   
					
            		$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                    $stmt = $this->_db->prepare($sql);
                    $stmt->execute();
                    $stmt->closeCursor();
                    echo '<strong>SUCCESS!</strong> The <strong>'. $bags .'</strong>  bags/drumps/pieces of the raw material import were successfully cleared and received in the warehouse stock.';
                    return TRUE;
                } 
                catch (PDOException $e) {
                    echo '<strong>ERROR</strong> Could not cleared the raw material in the factory. Please try again. Please try again.<br>'. $e->getMessage(); 
                    return FALSE;
                } 
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not cleared the raw material in the factory. Please try again.<br>'. $e->getMessage();
            return FALSE;
        } 
    }
	
	/**
     * Inputs the raw material shipment information
     */
    public function paidRMI()
    {
        $rmi = $bankdate = $datepaid = $delay = $remarks= "";
        
        $rmi = trim($_POST["rmino3"]);
        $rmi = stripslashes($rmi);
        $rmi = htmlspecialchars($rmi);
		
		if(!empty($_POST['bank_date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['bank_date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $bankdate = "'".$newDateString ."'";
        }
		else
		{
			$bankdate = "NULL";
		} 
		
		if(!empty($_POST['date_paid']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date_paid']);
            $newDateString = $myDateTime->format('Y-m-d');
            $datepaid = "'".$newDateString ."'";
        }
		else
		{
			$datepaid = "NULL";
		}
		
		$delay = trim($_POST["delay3"]);
        $delay = stripslashes($delay);
        $delay = htmlspecialchars($delay);
		
        $remarks = stripslashes($_POST["remarks3"]);
        $remarks = htmlspecialchars($remarks);
        
        
		$sql = "UPDATE `raw_materials_imports`
				SET
				`bank_letter_date` = ". $bankdate .",
				`paid_date` = ". $datepaid .",
				`delay_payment` = ". $delay .",
				`remarks_paid` = '". $remarks."',
				`user_paid` = ".$_SESSION['Userid'].",
				`status` = 3
				WHERE `raw_materials_imports_id` = '". $rmi ."';";
		try
		{   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			$stmt->closeCursor();
			echo '<strong>SUCCESS!</strong> The raw material import was successfully paid on <strong>'. $datepaid .'</strong>.';
			return TRUE;
		} 
		catch (PDOException $e) {
			echo '<strong>ERROR</strong> Could not change the status of the file from cleared to paid. Please try again.<br>'. $e->getMessage(); 
			return FALSE;
		} 
            
    }
	
	
	 /**
     * Loads the table of all the stock of the raw materials import orders in Warehouse
     * This function outputs <tr> tags with raw materials import orders
     */
    public function giveRMIordersWarehouse()
    {
        $sql = "SELECT `raw_materials_imports`.`raw_materials_imports_id`,`raw_materials_imports`.`rmi_no`,material_name, material_grade,`raw_materials_imports`.`pi_no`,`raw_materials_imports`.`pi_date`,`raw_materials_imports`.`supplier`,`raw_materials_imports`.`manufacturer`,`raw_materials_imports`.`qty`, `amount`, `raw_materials_imports`.`remarks_order`,`raw_materials_imports`.`exp_date_shipment`,username,`raw_materials_imports`.`status`
	FROM `raw_materials_imports`
    JOIN materials ON `raw_materials_imports`.material_id = `materials`.material_id
	INNER JOIN users ON user_order = user_id
	WHERE `raw_materials_imports`.`status` = 0
	ORDER BY raw_materials_imports_id;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['qty'];
                $REMARKS = $row['remarks_order'];
                 echo '<tr>
                        <td>'. $row['rmi_no'] .'</td>
                        <td>'. $row['exp_date_shipment'] .'</td>
                        <td>'. $MATERIAL .' - '. $GRADE .'</td>
                        <td>'. $row['supplier'] .'</td>
                        <td class="text-right">'. number_format((float) $BAGS,0,'.',',') .'</td>
                        <td class="text-right">$'. number_format((float) $row['amount'],2,'.',',') .'</td>
                        <td>'. $row['pi_no'] .'</td>
                        <td>'. $row['pi_date'] .'</td>
                        <td>'. $row['manufacturer'] .'</td>
                        <td>'. $row['username'] .'</td>
                        <td>'. $REMARKS .'</td>
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
	
	/**
     * Loads the table of all the stock of the raw materials import shipments in Warehouse
     * This function outputs <tr> tags with raw materials import shipments
     */
    public function giveRMIshipsWarehouse()
    {
        $sql = "SELECT `raw_materials_imports`.`raw_materials_imports_id`,`raw_materials_imports`.`rmi_no`,material_name, material_grade,`raw_materials_imports`.`bill_no`,`raw_materials_imports`.`date_shipment`, `raw_materials_imports`.`invoice_no`,`raw_materials_imports`.`delay_sent`, `amount`, `raw_materials_imports`.`terms`,`raw_materials_imports`.`exp_date_arrival`,`raw_materials_imports`.`bill_due_date`,`raw_materials_imports`.`supplier`,`raw_materials_imports`.`manufacturer`,`raw_materials_imports`.`qty`, `raw_materials_imports`.`remarks_shipped`,`raw_materials_imports`.`exp_date_shipment`,username,`raw_materials_imports`.`status`
	FROM `raw_materials_imports`
    JOIN materials ON `raw_materials_imports`.material_id = `materials`.material_id
	INNER JOIN users ON user_shipped = user_id
	WHERE `raw_materials_imports`.`status` = 1
	ORDER BY raw_materials_imports_id;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['qty'];
                $REMARKS = $row['remarks_shipped'];
				if($row['remarks_shipped'] == null)
                {
                    $REMARKS = "";
                }
                $DELAY = $row['delay_sent'];
				if($row['delay_sent'] == null)
                {
                    $DELAY = "";
                }
                 echo '<tr>
                        <td>'. $row['rmi_no'] .'</td>
                        <td>'. $row['exp_date_arrival'] .'</td>
                        <td>'. $row['bill_due_date'] .'</td>
                        <td class="text-right">$'. number_format((float) $row['amount'],2,'.',',') .'</td>
                        <td>'. $MATERIAL .' - '. $GRADE .'</td>
                        <td>'. $row['supplier'] .'</td>
                        <td class="text-right">'. number_format((float) $BAGS,0,'.',',') .'</td>
                        <td>'. $row['date_shipment'] .'</td>
                        <td>'. $DELAY .'</td>
                        <td>'. $row['bill_no'] .'</td>
                        <td>'. $row['invoice_no'] .'</td>
                        <td>'. $row['terms'] .'</td>
                        <td>'. $row['username'] .'</td>
                        <td>'. $REMARKS .'</td>
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
	
	/**
     * Loads the table of all the stock of the raw materials import clearings in Warehouse
     * This function outputs <tr> tags with raw materials import shipments
     */
    public function giveRMIclearingsWarehouse()
    {
        $sql = "SELECT `raw_materials_imports`.`raw_materials_imports_id`,`raw_materials_imports`.`rmi_no`,material_name, material_grade, kgs_bag, `raw_materials_imports`.`supplier`,`raw_materials_imports`.`manufacturer`,`raw_materials_imports`.`qty`, username,`raw_materials_imports`.`status`, `amount`,
`raw_materials_imports`.`bill_due_date`,`raw_materials_imports`.`date_cleared`,`raw_materials_imports`.`delay_arrived`,`raw_materials_imports`.`declaration_no`,`raw_materials_imports`.`damaged_qty`,`raw_materials_imports`.`duty`,`raw_materials_imports`.`clearing`,`raw_materials_imports`.`unloading`,`raw_materials_imports`.`remarks_cleared`, `raw_materials_imports`.`cost_kg`
	FROM `raw_materials_imports`
    JOIN materials ON materials.material_id = raw_materials_imports.material_id
	INNER JOIN users ON `raw_materials_imports`.`user_cleared` = user_id
	WHERE `raw_materials_imports`.`status` = 2
	ORDER BY raw_materials_imports_id;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['qty'];
                $DAMAGED = $row['damaged_qty'];
                $REMARKS = $row['remarks_cleared'];
				$TOTAL = $row['amount'] + $row['duty'] + $row['clearing']+$row['unloading'];
                 echo '<tr>
                        <td>'. $row['rmi_no'] .'</td>
                        <td>'. $row['bill_due_date'] .'</td>
                        <td class="text-right">$'. number_format((float) $row['amount'],2,'.',',') .'</td>
                        <td>'. $MATERIAL .' - '. $GRADE .'</td>
                        <td>'. $row['supplier'] .'</td>
                        <td class="text-right">'. number_format((float) $BAGS,0,'.',',') .'</td>
                        <td class="text-right">'. number_format((float) $DAMAGED,0,'.',',') .'</td>
                        <td>'. $row['date_cleared'] .'</td>
                        <td>'. $row['delay_arrived'] .'</td>
                        <td>'. $row['declaration_no'] .'</td>
                        <td class="text-right">$'. number_format((float) $row['duty'],2,'.',',') .'</td>
                        <td class="text-right">$'. number_format((float) $row['clearing'],2,'.',',') .'</td>
                        <td class="text-right">$'. number_format((float) $row['unloading'],2,'.',',') .'</td>
                        <td class="text-right">$'. number_format((float) $TOTAL,2,'.',',') .'</td>
                        <td class="text-right">'. number_format((float) $row['cost_kg'],2,'.',',') .'</td>
                        <td>'. $row['username'] .'</td>
                        <td>'. $REMARKS .'</td>
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
	
	/**
     * Loads the table of all the stock of the raw materials import payments in Warehouse for the last month
     * This function outputs <tr> tags with raw materials import shipments
     */
    public function giveRMIpaymentsWarehouse()
    {
        $sql = "SELECT `raw_materials_imports`.`raw_materials_imports_id`,`raw_materials_imports`.`rmi_no`,material_name, material_grade,
`raw_materials_imports`.`supplier`,`raw_materials_imports`.`manufacturer`,`raw_materials_imports`.`qty`, `amount`, username,`raw_materials_imports`.`status`, 
`raw_materials_imports`.`bank_letter_date`, `raw_materials_imports`.`paid_date`,`raw_materials_imports`.`delay_payment`, `raw_materials_imports`.`remarks_paid`,`raw_materials_imports`.`user_paid`
	FROM `raw_materials_imports`
    JOIN materials ON materials.material_id = raw_materials_imports.material_id
	INNER JOIN users ON `raw_materials_imports`.`user_cleared` = user_id
	WHERE `raw_materials_imports`.`status` = 3 AND MONTH(paid_date) = MONTH(CURRENT_DATE()) AND YEAR(paid_date) = YEAR(CURRENT_DATE())
	ORDER BY raw_materials_imports_id;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['qty'];
                $REMARKS = $row['remarks_paid'];
                 echo '<tr>
                        <td>'. $row['rmi_no'] .'</td>
                        <td>'. $MATERIAL .' - '. $GRADE .'</td>
                        <td>'. $row['supplier'] .'</td>
                        <td class="text-right">'. number_format((float) $BAGS,0,'.',',') .'</td>
                        <td class="text-right">$'. number_format((float) $row['amount'],2,'.',',') .'</td>
                        <td>'. $row['bank_letter_date'] .'</td>
                        <td>'. $row['paid_date'] .'</td>
                        <td>'. $row['delay_payment'] .'</td>
                        <td>'. $row['username'] .'</td>
                        <td>'. $REMARKS .'</td>
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
	
	
	/**
     * Loads the table of all the stock of the raw materials
     * This function outputs <tr> tags with stock of raw materials
     * location_id = 1 Warehouse, 2 Multilayer, 3 Printing stock location
     */
    public function stockMaterials($x)
    {
        $sql = "SELECT material_name, material_grade, bags, kgs_bag
                FROM  `stock_materials` 
    			INNER JOIN materials ON materials.material_id = `stock_materials`.material_id AND `material` = 1
                WHERE machine_id = ". $x .";";
        $totalBags = $totalkgs = 0.00;
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags'];
                $kgs = $BAGS*$row['kgs_bag'];
                $totalBags = $totalBags + $BAGS;
                $totalkgs = $totalkgs + $kgs;
                 
                echo '<tr>
                        <td>'. $NAME .'</td>
                        <td>'. $GRADE .'</td>
                        <td class="text-right">'. number_format($BAGS,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($kgs,2,'.',',') .'</td>
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
                    <td></td>
                </tr>";
        }
    }
	
	public function stockSpareParts($x)
    {
        $sql = "SELECT material_name, material_grade, bags, recycle, sacks, cutting
                FROM  `stock_materials` 
    			INNER JOIN materials ON materials.material_id = `stock_materials`.material_id AND `spare_parts` = 1
                WHERE machine_id = ". $x .";";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags'];
				$SECTION = '';
				
				if($row['recycle'] == 1)
				{
					$SECTION = 'Recycle';
				}
				else if($row['sacks'] == 1)
				{
					$SECTION = 'Extruder';
				}
                else if($row['cutting'] == 1)
				{
					$SECTION = 'Cutting';
				}
				else
				{
					$SECTION = 'General';
				}
                echo '<tr>
                        <td>'. $SECTION .'</td>
                        <td>'. $NAME .'</td>
                        <td>'. $GRADE .'</td>
                        <td class="text-right">'. number_format($BAGS,2,'.',',') .'</td>
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
                    <td></td>
                </tr>";
        }
    }
	
	 public function stockInksSolvents($x)
    {
        $sql = "SELECT material_name, material_grade, bags, kgs_bag
                FROM  `stock_materials` 
    			INNER JOIN materials ON materials.material_id = `stock_materials`.material_id AND `color` = 1
                WHERE machine_id = ". $x .";";
        $totalBags = $totalkgs = 0.00;
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags'];
                $kgs = $BAGS*$row['kgs_bag'];
                $totalBags = $totalBags + $BAGS;
                $totalkgs = $totalkgs + $kgs;
                 
                echo '<tr>
                        <td>'. $NAME .'</td>
                        <td>'. $GRADE .'</td>
                        <td class="text-right">'. number_format($BAGS,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($kgs,2,'.',',') .'</td>
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
                    <td></td>
                </tr>";
        }
    }
	
	 public function stockMasterbatch($x)
    {
        $sql = "SELECT material_name, material_grade, bags, kgs_bag
                FROM  `stock_materials` 
    			INNER JOIN materials ON materials.material_id = `stock_materials`.material_id AND `master_batch` = 1
                WHERE machine_id = ". $x .";";
        $totalBags = $totalkgs = 0.00;
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags'];
                $kgs = $BAGS*$row['kgs_bag'];
                $totalBags = $totalBags + $BAGS;
                $totalkgs = $totalkgs + $kgs;
                 
                echo '<tr>
                        <td>'. $NAME .'</td>
                        <td>'. $GRADE .'</td>
                        <td class="text-right">'. number_format($BAGS,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($kgs,2,'.',',') .'</td>
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
                    <td></td>
                </tr>";
        }
    }
	
	public function stockSemifinished($x)
    {
        $sql = "SELECT material_name, material_grade, bags, kgs_bag
                FROM  `stock_materials` 
    			INNER JOIN materials ON materials.material_id = `stock_materials`.material_id AND `semifinished` = 1
                WHERE machine_id = ". $x .";";
        $totalBags = $totalkgs = 0.00;
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags'];
                $kgs = $BAGS*$row['kgs_bag'];
                $totalBags = $totalBags + $BAGS;
                $totalkgs = $totalkgs + $kgs;
                 
                echo '<tr>
                        <td>'. $GRADE .'</td>
                        <td>'. $NAME .'</td>
                        <td class="text-right">'. number_format($BAGS,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($kgs,2,'.',',') .'</td>
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
                    <td></td>
                </tr>";
        }
    }
	
	public function stockfinished($x)
    {
        $sql = "SELECT material_name, bags, kgs_bag
                FROM  `stock_materials` 
    			INNER JOIN materials ON materials.material_id = `stock_materials`.material_id AND `finished` = 1
                WHERE machine_id = ". $x .";";
        $totalBags = $totalkgs = 0.00;
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $NAME = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags'];
                $kgs = $BAGS*$row['kgs_bag'];
                $totalBags = $totalBags + $BAGS;
                $totalkgs = $totalkgs + $kgs;
                 
                echo '<tr>
                        <td>'. $GRADE .'</td>
                        <td>'. $NAME .'</td>
                        <td class="text-right">'. number_format($BAGS,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($kgs,2,'.',',') .'</td>
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
                    <td></td>
                </tr>";
        }
    }
	
	/**
     * Loads the table of all the stock of the raw materials
     * This function outputs <tr> tags with stock of raw materials
     * location_id = 1 Warehouse, 2 Multilayer, 3 Printing stock location
     */
    public function stockConsumables($x)
    {
        $sql = "SELECT material_name, bags, kgs_bag
                FROM  `stock_materials` 
    			INNER JOIN materials ON materials.material_id = `stock_materials`.material_id AND `consumables` = 1
                WHERE machine_id = ". $x .";";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $NAME = $row['material_name'];
                $BAGS = $row['bags'];
                $kgs = $BAGS*$row['kgs_bag'];
                 
                echo '<tr>
                        <td>'. $NAME .'</td>
                        <td class="text-right">'. number_format($BAGS,2,'.',',') .'</td>
                        <td class="text-right">'. number_format($kgs,2,'.',',') .'</td>
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
                    <td></td>
                </tr>";
        }
    }
	
	
	/**
     * Balance the stock materials in the location
     */
    public function balanceStockMaterials($machine)
    {
        $material = $date = $oldbags = $newbags = $difference = $remarks= "";
        
		$date = "NOW()";
        if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = "'".$newDateString ." 07:00:00'";
        }
		
        $material = trim($_POST["material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
		        
        $newbags = trim($_POST["newbags"]);
        $newbags = stripslashes($newbags);
        $newbags = htmlspecialchars($newbags);
        
        $oldbags = trim($_POST["oldbags"]);
        $oldbags = stripslashes($oldbags);
        $oldbags = htmlspecialchars($oldbags);
        
        $difference = trim($_POST["difference"]);
        $difference = stripslashes($difference);
        $difference = htmlspecialchars($difference);
        
        $remarks = stripslashes($_POST["remarks"]);
        $remarks = htmlspecialchars($remarks);
        
		if($difference < 0)
		{
			$bags = $difference;
		}
		else
		{
			$bags = "+ ". $difference;
		}
		
        $sql = "INSERT INTO `stock_balance`(`stock_balance_id`,`date_balance`,`machine_id`,`material_id`,`oldbags`,`newbags`,`difference`,`user_id`,`remarks`)VALUES(NULL,". $date .",". $machine .",". $material .",". $oldbags .",". $newbags .",". $difference .",".$_SESSION['Userid'].",'". $remarks."');
		UPDATE `stock_materials`
		SET `bags` = `bags` ". $bags ."
		WHERE material_id = ". $material ." AND machine_id = ". $machine ." ;";
			
		try
		{   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			$stmt->closeCursor();
			echo '<strong>SUCCESS!</strong> The material stock was successfully updated with <strong>'. $newbags .'</strong>  bags/drumps/pieces .';
			return TRUE;
		} 
		catch (PDOException $e) {
			echo '<strong>ERROR</strong> Could not balance the number of bags/drumps/pieces of this material. Please try again.<br>'. $e->getMessage(); 
			return FALSE;
		} 
    }
	
	/**
     * Approve stock transfers from one location, checking the stock of that location then changing the status
     *
     * This function outputs boolean if the transaction was succesful
     */
    public function approve()
    {
        $id = $material = $bags = "";
        
        $id = trim($_POST["id_transfer"]);
        $id = stripslashes($id);
        $id = htmlspecialchars($id);
        
        $bags = trim($_POST["bags"]);
        $bags = stripslashes($bags);
        $bags = htmlspecialchars($bags);
		
		$material = trim($_POST["material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
		
        
        $remarks = trim($_POST["remarks"]);
        $remarks = stripslashes($remarks);
        $remarks = htmlspecialchars($remarks);
		
		if($bags == 0)
		{
			//CHANGE THE STATUS TRANSFER, BGAS ISSUED, AND USER ISSUED
			$sql = "UPDATE  `stock_materials_transfers`
					SET `status_transfer` = 3,
							`bags_approved` = :bags,
							`bags_issued` = :bags,
							`bags_receipt` = :bags,
							`user_id_approved` = :user,
							`user_id_issued` = :user,
							`user_id_receipt` = :user,
							`remarks_approved` = :remarks,
							`material_id` = :material
					WHERE `stock_materials_transfers_id` = :id;";
			 try
			{   
				$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				$stmt = $this->_db->prepare($sql);
				$stmt->bindParam(":bags", $bags, PDO::PARAM_INT);
				$stmt->bindParam(":remarks", $remarks, PDO::PARAM_STR);
				$stmt->bindParam(":id", $id, PDO::PARAM_INT);
				$stmt->bindParam(":material", $material, PDO::PARAM_INT);
				$stmt->bindParam(":user", $_SESSION['Userid'], PDO::PARAM_INT);
				$stmt->execute();
				$stmt->closeCursor();
				echo '<strong>SUCCESS!</strong> The <strong>'. $bags .'</strong>  bags/drumps/pieces of the material were successfully approved for issue.';
				return TRUE;
			} 
			catch (PDOException $e) {
				echo '<strong>ERROR</strong> Could change the status of the transfer from the database. Please try again.<br>'. $e->getMessage(); 
				return FALSE;
			} 
		}
        else
		{
				//CHECK IF THE BAGS IN STOCK MATERIAL ARE GREATER THAN THE BAGS REQUESTED
			//CHANGE THE STATUS TRANSFER, BGAS ISSUED, AND USER ISSUED
			$sql = "UPDATE  `stock_materials_transfers`
					SET 
					`material_id` = ". $material ."
					WHERE `stock_materials_transfers_id` = ". $id .";";
			 try
			{   
				$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
				$stmt->closeCursor();
			
			$sql = "SELECT stock_material_id, bags, material_name, material_grade
					FROM stock_materials
					JOIN materials ON materials.material_id = stock_materials.material_id
					WHERE stock_materials.material_id IN (SELECT material_id FROM stock_materials_transfers WHERE stock_materials_transfers_id = ". $id .") AND
					machine_id IN (SELECT machine_from FROM stock_materials_transfers WHERE stock_materials_transfers_id = ". $id .");";
			try
			{   

				$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
				if($row = $stmt->fetch())
				{
					$ID_SM = $row['stock_material_id'];
					$BAGSTOCK = $row['bags'];
					$MATERIAL = $row['material_name'];
					$GRADE = $row['material_grade'];
					if($BAGSTOCK >= $bags)
					{
						//CHANGE THE STATUS TRANSFER, BGAS ISSUED, AND USER ISSUED
						$sql = "UPDATE  `stock_materials_transfers`
								SET `status_transfer` = 1,
										`bags_approved` = :bags,
										`user_id_approved` = :user,
										`remarks_approved` = :remarks
								WHERE `stock_materials_transfers_id` = :id;";
						 try
						{   
							$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
							$stmt = $this->_db->prepare($sql);
							$stmt->bindParam(":bags", $bags, PDO::PARAM_INT);
							$stmt->bindParam(":remarks", $remarks, PDO::PARAM_STR);
							$stmt->bindParam(":id", $id, PDO::PARAM_INT);
							$stmt->bindParam(":user", $_SESSION['Userid'], PDO::PARAM_INT);
							$stmt->execute();
							$stmt->closeCursor();
							echo '<strong>SUCCESS!</strong> The <strong>'. $bags .'</strong>  bags/drumps/pieces of the material:<b> '.$MATERIAL .' - '. $GRADE.' </b>were successfully approved for issue.';
//							 $this->send();
							return TRUE;
						} 
						catch (PDOException $e) {
							echo '<strong>ERROR</strong> Could change the status of the transfer from the database. Please try again.<br>'. $e->getMessage(); 
							return FALSE;
						} 
					}
					else
					{
						echo '<strong>ERROR: </strong>There is not enough  bags/drumps/pieces of the material:<b> '.$MATERIAL .' - '. $GRADE.' </b>on stock. There are <b>'. $BAGSTOCK .' bags/drumps/pieces</b> and you want to approve <b>'. $bags .'  bags/drumps/pieces</b>. Please try with a lower number of bags/drumps/pieces.';
						return FALSE;
					}
				}
				else
				{
					echo '<strong>ERROR: </strong>There is not bags/drumps/pieces of this material on this stock location.';
					return FALSE;
				}
				$stmt->closeCursor();
				
				
			}
			catch (PDOException $e) {
				echo '<strong>ERROR</strong> Could not issue the raw material. Please try again.<br>'. $e->getMessage();
				return FALSE;
			} 
		}
		catch (PDOException $e) {
					echo '<strong>ERROR</strong> Could change the material of the transfer from the database. Please try again.<br>'. $e->getMessage(); 
					return FALSE;
				} 
		}
    }
	
	public function approveAll()
    {
        $sql = "SELECT `stock_materials_transfers`.`stock_materials_transfers_id`, material_name, material_grade, bags_required
FROM `stock_materials_transfers`
JOIN materials ON materials.material_id = `stock_materials_transfers`.material_id
WHERE status_transfer = 0 AND MONTH(date_required) >= MONTH(CURRENT_DATE())-1 AND YEAR(date_required) = YEAR(CURRENT_DATE());";
        try
        {   
			
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				
				$id = $row['stock_materials_transfers_id'];
				$MATERIAL = $row['material_name'];
				$GRADE = $row['material_grade'];
				$bags =  $row['bags_required'];
				
				$sql = "SELECT stock_material_id, bags, material_name, material_grade
						FROM stock_materials
						JOIN materials ON materials.material_id = stock_materials.material_id
						WHERE stock_materials.material_id IN (SELECT material_id FROM stock_materials_transfers WHERE stock_materials_transfers_id = ". $id .") AND
						machine_id IN (SELECT machine_from FROM stock_materials_transfers WHERE stock_materials_transfers_id = ". $id .");";
				if($stmt2 = $this->_db->prepare($sql))
				{
					$stmt2->execute();
					if($row2 = $stmt2->fetch())
					{
						$ID_SM = $row2['stock_material_id'];
						$BAGSTOCK = $row2['bags'];
						$MATERIAL = $row2['material_name'];
						$GRADE = $row2['material_grade'];
						if($BAGSTOCK >= $bags)
						{
							//CHANGE THE STATUS TRANSFER, BGAS ISSUED, AND USER ISSUED
							$sql = "UPDATE  `stock_materials_transfers`
									SET `status_transfer` = 1,
											`bags_approved` = `bags_required`,
											`user_id_approved` = :user,
                                    		`remarks_approved` = ''
									WHERE `stock_materials_transfers_id` = :id;";
							 try
							{   
								$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
								$stmt3 = $this->_db->prepare($sql);
								$stmt3->bindParam(":id", $id, PDO::PARAM_INT);
								$stmt3->bindParam(":user", $_SESSION['Userid'], PDO::PARAM_INT);
								$stmt3->execute();
								$stmt3->closeCursor();
								echo '<strong>SUCCESS!</strong> The <strong>'. $bags .'</strong>  bags/drumps/pieces of the material:<b> '.$MATERIAL .' - '. $GRADE.' </b>were successfully approved for issue.<br/>';
								 
            					$stmt3->closeCursor();
							} 
							catch (PDOException $e) {
								echo '<strong>ERROR</strong> Could change the status of the transfer from the database. Please try again.<br>'. $e->getMessage().'<br/>'; 
							} 
						}
						else
						{
							echo '<strong>ERROR: </strong>There is not enough  bags/drumps/pieces of the material:<b> '.$MATERIAL .' - '. $GRADE.' </b>on stock. There are <b>'. $BAGSTOCK .' bags/drumps/pieces</b> and you want to approve <b>'. $bags .'  bags/drumps/pieces</b>. Please try with a lower number of bags/drumps/pieces.<br/>';
						}
					
            		$stmt2->closeCursor();
					}
					else
					{
						echo '<strong>ERROR: </strong>There is not enough  bags/drumps/pieces of the material:<b> '.$MATERIAL .' - '. $GRADE.' </b>on stock. There are <b>0 bags/drumps/pieces</b> and you want to approve <b>'. $bags .'  bags/drumps/pieces</b>. Please try adding the raw material import, or local purchase, or loan.<br/>';
					}
					
				}
				else {
					echo '<strong>ERROR</strong> Could not issue the raw material:<b> '.$MATERIAL .' - '. $GRADE.' </b>on stock. There are <b>0 bags/drumps/pieces</b> and you want to approve <b>'. $bags .'  bags/drumps/pieces</b>. Please try adding the raw material import, or local purchase, or loan.<br/>';
				} 
            }
            $stmt->closeCursor(); 
//        $this->issueAll();
			return TRUE;
        }
        catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not approve all the raw material request. Please try again.<br>'. $e->getMessage();
            return FALSE;
        } 
		
    }
	
	
	
	public function issueAll()
    {
        $sql = "SELECT `stock_materials_transfers`.`stock_materials_transfers_id`, material_name, material_grade, bags_approved
FROM `stock_materials_transfers`
JOIN materials ON materials.material_id = `stock_materials_transfers`.material_id
WHERE status_transfer = 1 AND MONTH(date_required) >= MONTH(CURRENT_DATE()) -1 AND YEAR(date_required) = YEAR(CURRENT_DATE());";
        try
        {   
			
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				
				$id = $row['stock_materials_transfers_id'];
				$MATERIAL = $row['material_name'];
				$GRADE = $row['material_grade'];
				$bags =  $row['bags_approved'];

				//CHECK IF THE BAGS IN STOCK MATERIAL ARE GREATER THAN THE BAGS REQUESTED
				$sql = "SELECT stock_material_id, bags, material_name, material_grade
						FROM stock_materials 
						JOIN materials ON materials.material_id = stock_materials.material_id
						WHERE stock_materials.material_id IN (SELECT material_id FROM stock_materials_transfers WHERE stock_materials_transfers_id = ". $id .") AND
						machine_id IN (SELECT machine_from FROM stock_materials_transfers WHERE stock_materials_transfers_id = ". $id .");";
				if($stmt2 = $this->_db->prepare($sql))
				{
					$stmt2->execute();
					if($row2 = $stmt2->fetch())
					{
						$ID_SM = $row2['stock_material_id'];
						$BAGSTOCK = $row2['bags'];
						$MATERIAL = $row2['material_name'];
						$GRADE = $row2['material_grade'];
						if($BAGSTOCK >= $bags)
						{
							$newbags = $BAGSTOCK - $bags;
							//DECREASES THE BAGS FROM THE STOCK MATERIALS CHANGE THE STATUS TRANSFER, BGAS ISSUED, AND USER ISSUED
							$sql = "UPDATE  `stock_materials`
									SET `bags` = ". $newbags ."
									WHERE `stock_material_id` = ". $ID_SM .";
									UPDATE  `stock_materials_transfers`
									SET `status_transfer` = 2,
											`bags_issued` = `bags_approved`,
											`user_id_issued` = ".$_SESSION['Userid'].",
                                    		`remarks_issued` = ''
									WHERE `stock_materials_transfers_id` = ". $id .";";
							try
							{   
								$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
								$stmt3 = $this->_db->prepare($sql);
								$stmt3->execute();
								$stmt3->closeCursor();
								echo '<strong>SUCCESS!</strong> The <strong>'. $bags .'</strong>  bags/drumps/pieces of the material:<b> '.$MATERIAL .' - '. $GRADE.' </b>were successfully issued.<br/>';
							} 
							catch (PDOException $e) {
								echo '<strong>ERROR</strong> Could not decrease the number of  bags/drumps/pieces of this material or change the status of the transfer from the database. Please try again.<br>'. $e->getMessage() .'<br/>'; 
							} 
						}
						else
						{
							echo '<strong>ERROR: </strong>There is not enough bags/drumps/pieces of the material:<b> '.$MATERIAL .' - '. $GRADE.' </b>on stock. There are <b>'. $BAGSTOCK .' bags/drumps/pieces</b> and you want to send <b>'. $bags .' bags/drumps/pieces</b>. Please try with a lower number of bags/drumps/pieces.<br/>';
						}
					}
					else
					{
						echo '<strong>ERROR: </strong>There is not enough  bags/drumps/pieces of the material:<b> '.$MATERIAL .' - '. $GRADE.' </b>on stock. There are <b>0 bags/drumps/pieces</b> and you want to approve <b>'. $bags .'  bags/drumps/pieces</b>. Please try adding the raw material import, or local purchase, or loan.<br/>';
					}
					$stmt2->closeCursor();
				}
				else
				{
					echo '<strong>ERROR</strong> Could not issue the raw material:<b> '.$MATERIAL .' - '. $GRADE.' </b>on stock. There are <b>0 bags/drumps/pieces</b> and you want to approve <b>'. $bags .'  bags/drumps/pieces</b>. Please try adding the raw material import, or local purchase, or loan.<br/>';
				}
				
            }
            $stmt->closeCursor(); 
			return TRUE;
        }
        catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not approve all the raw material request. Please try again.<br>'. $e->getMessage();
            return FALSE;
        } 
    }
	
	public function receiveAll($machine)
    {
        $sql = "SELECT `stock_materials_transfers`.`stock_materials_transfers_id`, material_name, material_grade, bags_issued
FROM `stock_materials_transfers`
JOIN materials ON materials.material_id = stock_materials_transfers.material_id
WHERE status_transfer = 2 AND machine_to = ". $machine .";";
        try
        {   
			
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->execute();
            while($row = $stmt->fetch())
            {
				
				$id = $row['stock_materials_transfers_id'];
				$MATERIAL = $row['material_name'];
				$GRADE = $row['material_grade'];
				$bags =  $row['bags_issued'];

						//CHECK THE ACTUAL BAGS IN STOCK MATERIAL 
				$sql = "SELECT stock_material_id, bags, material_name, material_grade, consumables
						FROM stock_materials JOIN materials ON materials.material_id = stock_materials.material_id
						WHERE stock_materials.material_id IN (SELECT material_id FROM stock_materials_transfers WHERE stock_materials_transfers_id = ". $id .") AND
						machine_id IN (SELECT machine_to FROM stock_materials_transfers WHERE stock_materials_transfers_id = ". $id .");";
				try
				{   
					$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
					$stmt1 = $this->_db->prepare($sql);
					$stmt1->execute();
					if($row1 = $stmt1->fetch())
					{
						$ID_SM = $row1['stock_material_id'];
						$BAGSTOCK = $row1['bags'];
						$MATERIAL = $row1['material_name'];
						$GRADE = $row1['material_grade'];
						$newbags = $BAGSTOCK + $bags;
						//INCREASES THE BAGS FROM THE STOCK MATERIALS CHANGE THE STATUS TRANSFER, BAGS RECEIVED, AND USER RECEIVED

							$sql = "UPDATE  `stock_materials`
								SET `bags` = ". $newbags ."
								WHERE `stock_material_id` = ". $ID_SM .";
								UPDATE  `stock_materials_transfers`
								SET `status_transfer` = 3,
										`bags_receipt` = ". $bags. ",
										`user_id_receipt` = ".$_SESSION['Userid']."
								WHERE `stock_materials_transfers_id` = ". $id .";";
						try
						{   
							$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
							$stmt1 = $this->_db->prepare($sql);
							$stmt1->execute();
							$stmt1->closeCursor();
							echo '<strong>SUCCESS!</strong> The <strong>'. $bags .'</strong> bags/drumps/pieces of the material:<b> '.$MATERIAL .' - '. $GRADE.' </b>were successfully received.<br/>' ;
						} 
						catch (PDOException $e) {
							echo '<strong>ERROR</strong> Could not increase the number of  bags/drumps/pieces of this material or change the status of the transfer from the database. Please try again.<br>'. $e->getMessage() .'<br/>'; 
						} 
					}
					else
					{
						$sql = "SELECT consumables
						FROM materials 
						WHERE material_id IN (SELECT material_id FROM stock_materials_transfers WHERE stock_materials_transfers_id = ". $id .");";
						try
						{   
							$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
							$stmt2 = $this->_db->prepare($sql);
							$stmt2->execute();
							if($row2 = $stmt2->fetch())
							{
								$CONSUMABLE = $row2['consumables'];

								if($CONSUMABLE == 0)
								{
									//INSERT THE BAGS FROM THE STOCK MATERIALS 
									$sql = "UPDATE  `stock_materials_transfers`
										SET `status_transfer` = 3,
												`bags_receipt` = ". $bags. ",
												`user_id_receipt` = ".$_SESSION['Userid']."
										WHERE `stock_materials_transfers_id` = ". $id .";
										INSERT INTO  `stock_materials`(`stock_material_id`,`material_id`,`machine_id`,`bags`)VALUES(NULL,(SELECT material_id FROM stock_materials_transfers WHERE stock_materials_transfers_id = ". $id ."),(SELECT machine_to FROM stock_materials_transfers WHERE stock_materials_transfers_id = ". $id ."),". $bags. ");";
								}
								else
								{
									$sql = "UPDATE  `stock_materials_transfers`
										SET `status_transfer` = 3,
												`bags_receipt` = ". $bags. ",
												`user_id_receipt` = ".$_SESSION['Userid']."
										WHERE `stock_materials_transfers_id` = ". $id .";";
								}

								try
								{   
									$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
									$stmt2 = $this->_db->prepare($sql);
									$stmt2->execute();
									$stmt2->closeCursor();
									echo '<strong>SUCCESS!</strong> The <strong>'. $bags .'</strong>  bags/drumps/pieces of the material were successfully received. <br/>';
								} 
								catch (PDOException $e) {
									echo '<strong>ERROR</strong> Could not increase the number of  bags/drumps/pieces of this material or change the status of the transfer from the database. Please try again.<br>'. $e->getMessage() .'<br/>'; 
								} 
							}
						}
						catch (PDOException $e) {
							echo '<strong>ERROR</strong> Could not receive the raw material. Please try again.<br>'. $e->getMessage() .'<br/>';
						} 

					}
				}
				catch (PDOException $e) {
					echo '<strong>ERROR</strong> Could not receive the raw material. Please try again.<br>'. $e->getMessage() .'<br/>';
				} 
				
            }
            $stmt->closeCursor(); 
			return TRUE;
        }
        catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not receive all the raw material request. Please try again.<br>'. $e->getMessage();
            return FALSE;
        } 
    }
	
	/**
	/**
     * Loads the table of all the stock Issues of the raw materials
     * This function outputs <tr> tags with stock transfer of raw materials
     * $from is the machine_from. I.e $from is Warehouse (Transfers from the warehouse to Other sections)
     */
    public function stockApprovals()
    {
        $sql = "SELECT stock_materials_transfers_id, from_table.machine_name AS from_t, to_table.machine_name AS to_t,
                    material_name, material_grade, DATE_FORMAT(`stock_materials_transfers`.`date_required`, '%Y/%m/%d %H:%i') AS date_t, 
                    `stock_materials_transfers`.`bags_required`, `stock_materials_transfers`.`bags_approved`, `stock_materials_transfers`.`remarks_approved`,`stock_materials_transfers`.material_id,
                    u_required.username AS urequired , u_approved.username AS uapproved,`stock_materials_transfers`.`status_transfer`
                FROM stock_materials_transfers 
    			LEFT JOIN materials ON materials.material_id = `stock_materials_transfers`.material_id
                INNER JOIN users u_required ON stock_materials_transfers.user_id_required = u_required.user_id
                LEFT JOIN users u_approved ON stock_materials_transfers.user_id_approved = u_approved.user_id
                INNER JOIN machines from_table ON stock_materials_transfers.machine_from = from_table.machine_id
                INNER JOIN machines to_table ON stock_materials_transfers.machine_to = to_table.machine_id
                WHERE machine_from = 1 AND machine_to <> 1 AND YEAR(date_required) = YEAR(CURRENT_DATE()) ORDER BY status_transfer, `date_required` DESC;";
		
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['stock_materials_transfers_id'];
                $DATE = $row['date_t'];
                $APPROVEDBY = $row['uapproved'];
                if($row['uapproved'] == null)
                {
                    $APPROVEDBY = "";
                }
                $FROM = $row['from_t'];
                $TO = $row['to_t'];
                $REQUESTEDBY = $row['urequired'];
                $MATERIALID = $row['material_id'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGSRE = $row['bags_required'];
                $BAGSAP = $row['bags_approved'];
                $REMARKS = $row['remarks_approved'];
                if($row['remarks_approved'] == null)
                {
                    $REMARKS = "";
                }
                if($row['bags_approved'] == null)
                {
                    $APP = '">';
                }
				else
				{
					$APP = '">'.number_format((float) $BAGSAP,1,'.',',');
					if($BAGSAP != $BAGSRE)
					{
						$APP = 'text-danger">'.number_format((float) $BAGSAP,1,'.',',').'';
					}
				}
                $STATUS = "";
                $disabled = '" data-toggle="modal" data-target="#modal1" onclick="edit(\''. $ID .'\',\''. $DATE .'\',\''. $FROM.'\',\''. $TO .'\',\''. $MATERIALID.'\',\''. $MATERIAL.'\',\''. $GRADE .'\',\''. $BAGSRE.'\')"';
                if($row['status_transfer']==0)
                {
                    $STATUS = "<p class='text-muted'>Requested</p>";
                }
                else if($row['status_transfer']==1)
                {
                    $STATUS = "<p class='text-warning'>Approved</p>";
                    $disabled = ' disabled" ';
                }
                else if($row['status_transfer']==2)
                {
                    $STATUS = "<p class='text-info'>Issued</p>";
                    $disabled = ' disabled" ';
                }
                else if($row['status_transfer']==3)
                {
                    $STATUS = "<b class='text-success'> Received </b>";
                    $disabled = ' disabled" ';
                }
                
                 
                echo '<tr>
                        <td><button class="btn btn-link'. $disabled .'">Approve</button></td>
                        <td>'. $DATE .'</td>
                        <td>'. $TO .'</td>
                        <td><b>'. $MATERIAL .'</b>&nbsp - &nbsp'. $GRADE .'</td>
                        <td class="text-right">'. number_format((float) $BAGSRE,2,'.',',') .'</td>
                        <td>'. $REQUESTEDBY .'</td>
                        <td class="text-right '. $APP .'</td>
                        <td>'. $APPROVEDBY .'</td>
                        <td>'. $STATUS .'</td>
                        <td>'. $REMARKS .'</td>
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
     * Send stock from one location, checking the stock of that location then reducing it and changing the status
     *
     * This function outputs boolean if the transaction was succesful
     */
    public function send()
    {
        $id = $bags = "";
        
        $id = trim($_POST["id_transfer"]);
        $id = stripslashes($id);
        $id = htmlspecialchars($id);
        
        $bags = trim($_POST["bags"]);
        $bags = stripslashes($bags);
        $bags = htmlspecialchars($bags);
        
        $remarks = trim($_POST["remarks"]);
        $remarks = stripslashes($remarks);
        $remarks = htmlspecialchars($remarks);
		
		if($bags == 0)
		{
			//CHANGE THE STATUS TRANSFER, BGAS ISSUED, AND USER ISSUED
			$sql = "UPDATE  `stock_materials_transfers`
					SET `status_transfer` = 3,
							`bags_issued` = :bags,
							`bags_receipt` = :bags,
							`user_id_issued` = :user,
							`user_id_receipt` = :user,
							`remarks_approved` = :remarks
					WHERE `stock_materials_transfers_id` = :id;";
			 try
			{   
				$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				$stmt = $this->_db->prepare($sql);
				$stmt->bindParam(":bags", $bags, PDO::PARAM_INT);
				$stmt->bindParam(":remarks", $remarks, PDO::PARAM_STR);
				$stmt->bindParam(":id", $id, PDO::PARAM_INT);
				$stmt->bindParam(":user", $_SESSION['Userid'], PDO::PARAM_INT);
				$stmt->execute();
				$stmt->closeCursor();
				echo '<strong>SUCCESS!</strong> The <strong>'. $bags .'</strong>  bags/drumps/pieces of the material were successfully approved for issue.';
				return TRUE;
			} 
			catch (PDOException $e) {
				echo '<strong>ERROR</strong> Could change the status of the transfer from the database. Please try again.<br>'. $e->getMessage(); 
				return FALSE;
			} 
		}
        else
		{
        //CHECK IF THE BAGS IN STOCK MATERIAL ARE GREATER THAN THE BAGS REQUESTED
        $sql = "SELECT stock_material_id, bags, material_name, material_grade
                FROM stock_materials 
				JOIN materials ON materials.material_id = stock_materials.material_id
                WHERE stock_materials.material_id IN (SELECT material_id FROM stock_materials_transfers WHERE stock_materials_transfers_id = ". $id .") AND
                machine_id IN (SELECT machine_from FROM stock_materials_transfers WHERE stock_materials_transfers_id = ". $id .");";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->execute();
            if($row = $stmt->fetch())
            {
                $ID_SM = $row['stock_material_id'];
                $BAGSTOCK = $row['bags'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                if($BAGSTOCK >= $bags)
                {
                    $newbags = $BAGSTOCK - $bags;
                    //DECREASES THE BAGS FROM THE STOCK MATERIALS CHANGE THE STATUS TRANSFER, BGAS ISSUED, AND USER ISSUED
                    $sql = "UPDATE  `stock_materials`
                            SET `bags` = ". $newbags ."
                            WHERE `stock_material_id` = ". $ID_SM .";
                            UPDATE  `stock_materials_transfers`
                            SET `status_transfer` = 2,
                                    `bags_issued` = ". $bags. ",
                                    `user_id_issued` = ".$_SESSION['Userid'].",
                                    `remarks_issued` = '".$remarks."'
                            WHERE `stock_materials_transfers_id` = ". $id .";";
                    try
                    {   
            			$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                        $stmt = $this->_db->prepare($sql);
                        $stmt->execute();
                        $stmt->closeCursor();
                        echo '<strong>SUCCESS!</strong> The <strong>'. $bags .'</strong>  bags/drumps/pieces of the material:<b> '.$MATERIAL .' - '. $GRADE.' </b>were successfully issued.';
                        return TRUE;
                    } 
                    catch (PDOException $e) {
                        echo '<strong>ERROR</strong> Could not decrease the number of  bags/drumps/pieces of this material or change the status of the transfer from the database. Please try again.<br>'. $e->getMessage(); 
                        return FALSE;
                    } 
                }
                else
                {
                    echo '<strong>ERROR: </strong>There is not enough bags/drumps/pieces of the material:<b> '.$MATERIAL .' - '. $GRADE.' </b>on stock. There are <b>'. $BAGSTOCK .' bags/drumps/pieces</b> and you want to send <b>'. $bags .' bags/drumps/pieces</b>. Please try with a lower number of bags/drumps/pieces.';
                    return FALSE;
                }
            }
            else
            {
                echo '<strong>ERROR: </strong>There is not  bags/drumps/pieces of this material on this stock location.';
                return FALSE;
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not issue the raw material. Please try again.<br>'. $e->getMessage();
            return FALSE;
        } }
    }
	
	/**
     * Loads the table of all the stock Issues of the raw materials
     * This function outputs <tr> tags with stock transfer of raw materials
     * $from is the machine_from. I.e $from is Warehouse (Transfers from the warehouse to Other sections)
     */
    public function stockIssuesFrom($from){
		$sql = "SELECT stock_materials_transfers_id, from_table.machine_name AS from_t, to_table.machine_name AS to_t,
                    material_name, material_grade, DATE_FORMAT(`stock_materials_transfers`.`date_required`, '%Y/%m/%d %H:%i') AS date_t, 
                    `stock_materials_transfers`.`bags_required`, `stock_materials_transfers`.`bags_approved`,`stock_materials_transfers`.`bags_issued`, `stock_materials_transfers`.`remarks_approved`,`stock_materials_transfers`.`remarks_issued`,
                    u_required.username AS urequired , u_approved.username AS uapproved ,u_issued.username AS uissued, `stock_materials_transfers`.`status_transfer`
                FROM stock_materials_transfers 
    			LEFT JOIN materials ON materials.material_id = `stock_materials_transfers`.material_id
                INNER JOIN users u_required ON stock_materials_transfers.user_id_required = u_required.user_id
                INNER JOIN users u_approved ON stock_materials_transfers.user_id_approved = u_approved.user_id
                LEFT JOIN users u_issued ON stock_materials_transfers.user_id_issued = u_issued.user_id
                INNER JOIN machines from_table ON stock_materials_transfers.machine_from = from_table.machine_id
                INNER JOIN machines to_table ON stock_materials_transfers.machine_to = to_table.machine_id
                WHERE machine_from  = ". $from ." AND YEAR(date_required) = YEAR(CURRENT_DATE()) OR status_transfer = 1) 
				ORDER BY `date_required` DESC, status_transfer;";
		
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['stock_materials_transfers_id'];
                $DATE = $row['date_t'];
                $ISSUEDBY = $row['uissued'];
                if($row['uissued'] == null)
                {
                    $ISSUEDBY = "";
                }
                $FROM = $row['from_t'];
                $TO = $row['to_t'];
                $REQUESTEDBY = $row['urequired'];
                $APPROVEDBY = $row['uapproved'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
				
                $BAGSIS = $row['bags_issued'];
				
                $REMARKSIS = $row['remarks_issued'];
                if($row['remarks_issued'] == null)
                {
                    $REMARKSIS = "";
                }
				if($row['bags_issued'] == null)
                {
                    $ISS = '">';
                }
				else if($from ==5)
				{
					$KGS = $BAGSIS * 20;
					$ISS = '">'.number_format((float) $KGS,2,'.',',');
				}
				else
				{
					$ISS = '">'.number_format((float) $BAGSIS,2,'.',',');
				}	
                $STATUS = "";
                
                if($row['status_transfer']==2)
                {
                    $STATUS = "<p class='text-info'>Issued</p>";
                    $disabled = ' disabled" ';
                }
                else if($row['status_transfer']==3)
                {
                    $STATUS = "<b class='text-success'> Received </b>";
                    $disabled = ' disabled" ';
                }
                 
                 
                echo '<tr>
                        <td>'. $DATE .'</td>
                        <td>'. $FROM .'</td>
                        <td>'. $TO .'</td>
                        <td><b>'. $MATERIAL .'</b>&nbsp - &nbsp'. $GRADE .'</td>
                        <td class="text-right '. $ISS .'</td>
                        <td>'. $ISSUEDBY .'</td>
                        <td>'. $STATUS .'</td>
                        <td>'. $REMARKSIS .'</td>
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
     * Loads the table of all the stock Issues of the raw materials
     * This function outputs <tr> tags with stock transfer of raw materials
     * $from is the machine_from. I.e $from is Warehouse (Transfers from the warehouse to Other sections)
     */
    public function stockIssues()
    {
		$sql = "SELECT stock_materials_transfers_id, from_table.machine_name AS from_t, to_table.machine_name AS to_t,
                    material_name, material_grade, DATE_FORMAT(`stock_materials_transfers`.`date_required`, '%Y/%m/%d %H:%i') AS date_t, 
                    `stock_materials_transfers`.`bags_required`, kgs_bag, `stock_materials_transfers`.`bags_approved`,`stock_materials_transfers`.`bags_issued`, `stock_materials_transfers`.`remarks_approved`,`stock_materials_transfers`.`remarks_issued`,
                    u_required.username AS urequired , u_approved.username AS uapproved ,u_issued.username AS uissued, `stock_materials_transfers`.`status_transfer`
                FROM stock_materials_transfers 
    			LEFT JOIN materials ON materials.material_id = `stock_materials_transfers`.material_id
                INNER JOIN users u_required ON stock_materials_transfers.user_id_required = u_required.user_id
                INNER JOIN users u_approved ON stock_materials_transfers.user_id_approved = u_approved.user_id
                LEFT JOIN users u_issued ON stock_materials_transfers.user_id_issued = u_issued.user_id
                INNER JOIN machines from_table ON stock_materials_transfers.machine_from = from_table.machine_id
                INNER JOIN machines to_table ON stock_materials_transfers.machine_to = to_table.machine_id
                WHERE machine_from = 1 AND machine_to <> 1  AND YEAR(date_required) = YEAR(CURRENT_DATE()) 
				ORDER BY status_transfer, `date_required` DESC;";
		
		
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['stock_materials_transfers_id'];
                $DATE = $row['date_t'];
                $ISSUEDBY = $row['uissued'];
                if($row['uissued'] == null)
                {
                    $ISSUEDBY = "";
                }
                $FROM = $row['from_t'];
                $TO = $row['to_t'];
                $REQUESTEDBY = $row['urequired'];
                $APPROVEDBY = $row['uapproved'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGSRE = $row['bags_required'];
                $BAGSAP = $row['bags_approved'];
                $KGSIS = $row['bags_issued']*$row['kgs_bag'];
                $BAGSIS = $row['bags_issued'];
				$APP = '">'.number_format((float) $BAGSAP,2,'.',',');
				if($BAGSAP != $BAGSRE)
				{
					$APP = 'text-danger">'.number_format((float) $BAGSAP,2,'.',',').'';
				}
                $REMARKSAP = $row['remarks_approved'];
                if($row['remarks_approved'] == null)
                {
                    $REMARKSAP = "";
                }
                $REMARKSIS = $row['remarks_issued'];
                if($row['remarks_issued'] == null)
                {
                    $REMARKSIS = "";
                }
				if($row['bags_issued'] == null)
                {
                    $ISS = '">';
                }
				else
				{
					$ISS = '">'.number_format((float) $BAGSIS,2,'.',',');
					if($BAGSIS != $BAGSAP)
					{
						$ISS = 'text-danger">'.number_format((float) $BAGSIS,2,'.',',').'';
					}
				}
                $STATUS = "";
                $disabled = '" data-toggle="modal" data-target="#modal1"  onclick="edit(\''. $ID .'\',\''. $DATE .'\',\''. $FROM.'\',\''. $TO .'\',\''. $MATERIAL.'\',\''. $GRADE .'\',\''. $BAGSAP.'\')"';
                
                if($row['status_transfer']==0)
                {
                    $STATUS = "<p class='text-muted'>Requested</p>";
                    $disabled = ' disabled" ';
                }
                else if($row['status_transfer']==1)
                {
                    $STATUS = "<p class='text-warning'>Approved</p>";
                }
                else if($row['status_transfer']==2)
                {
                    $STATUS = "<p class='text-info'>Issued</p>";
                    $disabled = ' disabled" ';
                }
                else if($row['status_transfer']==3)
                {
                    $STATUS = "<b class='text-success'> Received </b>";
                    $disabled = ' disabled" ';
                }
                 
                 
                echo '<tr>
                        <td><button class="btn btn-link'. $disabled .'">Send</button></td>
                        <td>'. $DATE .'</td>
                        <td>'. $TO .'</td>
                        <td><b>'. $MATERIAL .'</b>&nbsp - &nbsp'. $GRADE .'</td><td class="text-right '. $ISS .'</td>
                        <td class="text-right">'. number_format((float) $KGSIS,2,'.',',') .'</td>
                        <td>'. $ISSUEDBY .'</td>
                        <td class="text-right">'. number_format((float) $BAGSRE,2,'.',',') .'</td>
                        <td>'. $REQUESTEDBY .'</td>
                        <td class="text-right '. $APP .'</td>
                        <td>'. $APPROVEDBY .'</td>
                        
                        <td>'. $STATUS .'</td>
                        <td>'. $REMARKSAP .'</td>
                        <td>'. $REMARKSIS .'</td>
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
	public function stockMovements()
    {
		$sql = "SELECT stock_materials_transfers_id, from_table.machine_name AS from_t, to_table.machine_name AS to_t,
                    material_name, material_grade, DATE_FORMAT(`stock_materials_transfers`.`date_required`, '%Y/%m/%d %H:%i') AS date_t, 
                    `stock_materials_transfers`.`bags_required`, kgs_bag, `stock_materials_transfers`.`bags_approved`,`stock_materials_transfers`.`bags_issued`, 
    `stock_materials_transfers`.`bags_receipt`,`stock_materials_transfers`.`remarks_approved`,`stock_materials_transfers`.`remarks_issued`,
                    u_required.username AS urequired , u_approved.username AS uapproved ,u_issued.username AS uissued,u_received.username AS ureceived, `stock_materials_transfers`.`status_transfer`
                FROM stock_materials_transfers 
    			LEFT JOIN materials ON materials.material_id = `stock_materials_transfers`.material_id
                INNER JOIN users u_required ON stock_materials_transfers.user_id_required = u_required.user_id
                LEFT JOIN users u_approved ON stock_materials_transfers.user_id_approved = u_approved.user_id
                LEFT JOIN users u_issued ON stock_materials_transfers.user_id_issued = u_issued.user_id
                LEFT JOIN users u_received ON stock_materials_transfers.user_id_receipt = u_received.user_id
                INNER JOIN machines from_table ON stock_materials_transfers.machine_from = from_table.machine_id
                INNER JOIN machines to_table ON stock_materials_transfers.machine_to = to_table.machine_id
                WHERE machine_from = 1  AND YEAR(date_required) = YEAR(CURRENT_DATE())
				ORDER BY status_transfer, `date_required` DESC;";
		
		
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['stock_materials_transfers_id'];
                $DATE = $row['date_t'];
                $APPROVEDBY = $row['uapproved'];
				if($row['uapproved'] == null)
                {
                    $APPROVEDBY = "";
                }
                $ISSUEDBY = $row['uissued'];
                if($row['uissued'] == null)
                {
                    $ISSUEDBY = "";
                }
				$RECEBY = $row['ureceived'];
                if($row['ureceived'] == null)
                {
                    $RECEDBY = "";
                }
                $FROM = $row['from_t'];
                $TO = $row['to_t'];
                $REQUESTEDBY = $row['urequired'];
                $APPROVEDBY = $row['uapproved'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGSRE = $row['bags_required'];
                $BAGSAP = $row['bags_approved'];
                $BAGSIS = $row['bags_issued'];
                $BAGSREC = $row['bags_receipt'];
				
                $REMARKSAP = $row['remarks_approved'];
                if($row['remarks_approved'] == null)
                {
                    $REMARKSAP = "";
                }
                $REMARKSIS = $row['remarks_issued'];
                if($row['remarks_issued'] == null)
                {
                    $REMARKSIS = "";
                }
				
				if($row['bags_approved'] == null)
				{
					$APP = '"> 0.00';
				}
				else
				{
					$APP = '">'.number_format((float) $BAGSAP,2,'.',',');
					if($BAGSAP != $BAGSRE)
					{
						$APP = 'text-danger">'.number_format((float) $BAGSAP,2,'.',',').'';
					}
				}
				
				if($row['bags_issued'] == null)
                {
                    $ISS = '"> 0.00';
                }
				else
				{
					$ISS = '">'.number_format((float) $BAGSIS,2,'.',',');
					if($BAGSIS != $BAGSAP)
					{
						$ISS = 'text-danger">'.number_format((float) $BAGSIS,2,'.',',').'';
					}
				}
				if($row['bags_receipt'] == null)
                {
                    $RECE = '"> 0.00';
                }
				else
				{
					$RECE = '">'.number_format((float) $BAGSREC,2,'.',',');
					if($BAGSREC != $BAGSIS)
					{
						$RECE = 'text-danger">'.number_format((float) $BAGSREC,2,'.',',').'';
					}
				}
				
                $STATUS = "";
                if($row['status_transfer']==0)
                {
                    $STATUS = "<p class='text-muted'>Requested</p>";
                }
                else if($row['status_transfer']==1)
                {
                    $STATUS = "<p class='text-warning'>Approved</p>";
                }
                else if($row['status_transfer']==2)
                {
                    $STATUS = "<p class='text-info'>Issued</p>";
                }
                else if($row['status_transfer']==3)
                {
                    $STATUS = "<b class='text-success'> Received </b>";
                }
                 
                 
                 
                echo '<tr>
                        <td></td>
                        <td>'. $DATE .'</td>
                        <td>'. $TO .'</td>
                        <td><b>'. $MATERIAL .'</b>&nbsp - &nbsp'. $GRADE .'</td>
                        <td class="text-right">'. number_format((float) $BAGSRE,2,'.',',') .'</td>
                        <td>'. $REQUESTEDBY .'</td>
                        <td class="text-right '. $APP .'</td>
                        <td>'. $APPROVEDBY .'</td>
						<td class="text-right '. $ISS .'</td>
                        <td>'. $ISSUEDBY .'</td>
						<td class="text-right '. $RECE .'</td>
                        <td>'. $ISSUEDBY .'</td>
                        <td>'. $STATUS .'</td>
                        <td>'. $REMARKSAP .'</td>
                        <td>'. $REMARKSIS .'</td>
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
	
	public function stockBalances()
    {
		$sql = "SELECT `stock_balance`.`stock_balance_id`,`stock_balance`.`oldbags`,`stock_balance`.`newbags`,`stock_balance`.`difference`,`stock_balance`.`remarks`, `stock_balance`.`material_id` , machine_name,material_name, material_grade, DATE_FORMAT(`stock_balance`.`date_balance`, '%d/%m/%Y') AS date_t, username
		FROM `stock_balance` 
		INNER JOIN materials ON materials.material_id = `stock_balance`.`material_id`
		INNER JOIN users ON `stock_balance`.`user_id` = users.user_id
		INNER JOIN machines ON `stock_balance`.`machine_id` = machines.machine_id
		WHERE YEAR(date_balance) = YEAR(CURRENT_DATE()) 
		ORDER BY `date_balance` DESC;";
		
		
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['stock_balance_id'];
                $DATE = $row['date_t'];
                $MACHINE = $row['machine_name'];
                $USER = $row['username'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                echo '<tr>
					<td>'. $DATE .'</td>
                        <td>'. $MACHINE .'</td>
                        <td><b>'. $MATERIAL .'</b>&nbsp - &nbsp'. $GRADE .'</td>
                        <td class="text-right">'. number_format((float) $row['oldbags'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format((float) $row['newbags'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format((float) $row['difference'],2,'.',',') .'</td>
                        <td>'. $USER.'</td>
                        <td>'. $row['remarks'] .'</td>';
				if($this->administrators())
				{
					echo '<td><button class="btn btn-xs btn-warning" type="button" onclick="edit(\''. $ID .'\',\''. $DATE .'\',\''. $MACHINE .'\',\''. $row['material_id'] .'\',\''. $MATERIAL .' - '. $GRADE .'\',\''. $row['oldbags'] .'\',\''. $row['newbags'] .'\',\''. $row['difference'] .'\',\''. $row['remarks'] .'\')"><i class="fa fa-pencil" aria-hidden="true"></i></button></td>
					<td><button class="btn btn-xs btn-danger" type="button" onclick="deleteBalance(\''. $ID .'\',\''. $DATE .'\',\''. $MACHINE .'\',\''. $row['material_id'] .'\',\''. $MATERIAL .' - '. $GRADE .'\',\''. $row['oldbags'] .'\',\''. $row['newbags'] .'\',\''. $row['difference'] .'\',\''. $row['remarks'] .'\')">X</button></td>';
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
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>";
        }
    }
	public function updateBalance()
    {
        $id = $material = "";
		
		$id = trim($_POST["id_balance"]);
        $id = stripslashes($id);
        $id = htmlspecialchars($id);
		
		
		$material = trim($_POST["id_material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
		
		$date = $oldbags = $newbags = $difference = $remarks= "";
        
		$date = "NOW()";
        if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = "'".$newDateString ." 07:00:00'";
        }
		        
        $newbags = trim($_POST["newbags"]);
        $newbags = stripslashes($newbags);
        $newbags = htmlspecialchars($newbags);
        
        $oldbags = trim($_POST["oldbags"]);
        $oldbags = stripslashes($oldbags);
        $oldbags = htmlspecialchars($oldbags);
        
        $difference = trim($_POST["difference"]);
        $difference = stripslashes($difference);
        $difference = htmlspecialchars($difference);
        
        $remarks = stripslashes($_POST["remarks"]);
        $remarks = htmlspecialchars($remarks);
		
        
        $sql = "UPDATE `ups_db`.`stock_balance`
				SET
				`date_balance` = ". $date .",
				`oldbags` = :oldbags,
				`newbags` = :newbags,
				`difference` = :difference,
				`remarks` = :remarks
				WHERE `stock_balance_id` = :id;";
		try
		{   
			$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(":id", $id, PDO::PARAM_INT);
			$stmt->bindParam(":oldbags", $oldbags, PDO::PARAM_STR);
			$stmt->bindParam(":newbags", $newbags, PDO::PARAM_STR);
			$stmt->bindParam(":difference", $difference, PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $remarks, PDO::PARAM_STR);
			$stmt->execute();
			$stmt->closeCursor();
			
			$this->CalculatestockMaterial($material);
			echo '<br><strong>SUCCESS!</strong> The balance was successfully updated in the database.<br>';
			return true;
		} 
		catch (PDOException $e) {
		  echo '<strong>ERROR</strong> Could not update the balance from the database.<br>'. $e->getMessage();
			return false;
		} 
		

    }
	
	public function deleteBalance()
    {
        $id = "";
		
		$id = trim($_POST["id_balance"]);
        $id = stripslashes($id);
        $id = htmlspecialchars($id);
		
		$material = trim($_POST["id_material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
        
        $sql = "DELETE FROM `stock_balance`
				WHERE `stock_balance`.`stock_balance_id` = :id;";
		try
		{   
			$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(":id", $id, PDO::PARAM_INT);
			$stmt->execute();
			$stmt->closeCursor();
			
			$this->CalculatestockMaterial($material);
			echo '<br><strong>SUCCESS!</strong> The balance was successfully disabled from the database.<br>';
			return true;
		} 
		catch (PDOException $e) {
		  echo '<strong>ERROR</strong> Could not delete the balance from the database.<br>'. $e->getMessage();
			return false;
		} 
		

    }
	/**
     * Loads the report of raw material transfers
     *
     * This function outputs <li> tags with materials
     */
    public function RequestAndIssueSlip()
    {
        
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
            
            $sql = " 
			 SELECT DATE_FORMAT(`date_required`, '%b/%Y') as date, DATE_FORMAT(`date_required`, '%m/%Y') as date2, to_table.machine_name AS to_t, material_name, material_grade, SUM(bags_required), SUM(bags_approved), SUM(bags_issued), SUM(bags_receipt), 
                u_required.username AS ureq, u_issued.username AS uiss, u_approved.username AS uapp, u_receipt.username AS urec, GROUP_CONCAT(remarks_approved SEPARATOR '') as rapproved, GROUP_CONCAT(remarks_issued SEPARATOR '') as rissued
FROM stock_materials_transfers 
    LEFT JOIN materials ON materials.material_id = `stock_materials_transfers`.material_id
	INNER JOIN users u_required ON stock_materials_transfers.user_id_required = u_required.user_id
	INNER JOIN users u_issued ON stock_materials_transfers.user_id_issued = u_issued.user_id
	INNER JOIN users u_approved ON stock_materials_transfers.user_id_approved = u_approved.user_id
	INNER JOIN users u_receipt ON stock_materials_transfers.user_id_receipt = u_receipt.user_id
	INNER JOIN machines to_table ON stock_materials_transfers.machine_to = to_table.machine_id
WHERE machine_from = 1 AND machine_to <> 1 AND status_transfer = 3 AND date_required BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_required`, '%b/%Y'), machine_to, `stock_materials_transfers`.material_id 
ORDER BY `date_required`, machine_to, `stock_materials_transfers`.material_id ;";
			
			
            
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
            
            $sql = " SELECT DATE_FORMAT(`date_required`, '%Y') as date, to_table.machine_name AS to_t, material_name, material_grade, SUM(bags_required), SUM(bags_approved), SUM(bags_issued), SUM(bags_receipt), 
                u_required.username AS ureq, u_issued.username AS uiss, u_approved.username AS uapp, u_receipt.username AS urec, GROUP_CONCAT(remarks_approved SEPARATOR '') as rapproved, GROUP_CONCAT(remarks_issued SEPARATOR '') as rissued
FROM stock_materials_transfers 
LEFT JOIN materials ON materials.material_id = `stock_materials_transfers`.material_id
	INNER JOIN users u_required ON stock_materials_transfers.user_id_required = u_required.user_id
	INNER JOIN users u_issued ON stock_materials_transfers.user_id_issued = u_issued.user_id
	INNER JOIN users u_approved ON stock_materials_transfers.user_id_approved = u_approved.user_id
	INNER JOIN users u_receipt ON stock_materials_transfers.user_id_receipt = u_receipt.user_id
	INNER JOIN machines to_table ON stock_materials_transfers.machine_to = to_table.machine_id
WHERE machine_from = 1 AND machine_to <> 1 AND status_transfer = 3 AND date_required BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_required`, '%Y'), machine_to, `stock_materials_transfers`.material_id 
ORDER BY `date_required`, machine_to, `stock_materials_transfers`.material_id ;";
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
            
            $sql = " SELECT DATE_FORMAT(`date_required`, '%Y/%m/%d') as date, DATE_FORMAT(`date_required`, '%m/%Y') as date2, to_table.machine_name AS to_t, material_name, material_grade, SUM(bags_required), SUM(bags_approved), SUM(bags_issued), SUM(bags_receipt), 
                u_required.username AS ureq, u_issued.username AS uiss, u_approved.username AS uapp, u_receipt.username AS urec, GROUP_CONCAT(remarks_approved SEPARATOR '') as rapproved, GROUP_CONCAT(remarks_issued SEPARATOR '') as rissued
FROM stock_materials_transfers 
    LEFT JOIN materials ON materials.material_id = `stock_materials_transfers`.material_id
	INNER JOIN users u_required ON stock_materials_transfers.user_id_required = u_required.user_id
	INNER JOIN users u_issued ON stock_materials_transfers.user_id_issued = u_issued.user_id
	INNER JOIN users u_approved ON stock_materials_transfers.user_id_approved = u_approved.user_id
	INNER JOIN users u_receipt ON stock_materials_transfers.user_id_receipt = u_receipt.user_id
	INNER JOIN machines to_table ON stock_materials_transfers.machine_to = to_table.machine_id
WHERE machine_from = 1 AND machine_to <> 1 AND status_transfer = 3 AND date_required BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59'
GROUP BY DATE_FORMAT(`date_required`, '%Y/%m/%d'), machine_to, `stock_materials_transfers`.material_id 
ORDER BY `date_required`, machine_to, `stock_materials_transfers`.material_id ;";
            
        }
        
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $TO = $row['to_t'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGSRE = $row['SUM(bags_required)'];
				
                $BAGSAP = '<td class="text-right">'.number_format((float) $row['SUM(bags_approved)'],2,'.',',') .'</td>';
				if($row['SUM(bags_approved)'] != $row['SUM(bags_required)'])
				{
					$BAGSAP = '<th class="text-right text-danger">'.number_format((float) $row['SUM(bags_approved)'],2,'.',',').'</th>';
				}
				
				$BAGSIS = '<td class="text-right">'. number_format((float) $row['SUM(bags_issued)'],2,'.',',') .'</td>';
				if($row['SUM(bags_approved)'] != $row['SUM(bags_issued)'])
				{
					$BAGSIS = '<th class="text-right text-danger">'. number_format((float) $row['SUM(bags_issued)'],2,'.',',') .'</th>';
				}
				
				$BAGSREC = '<td class="text-right">'. number_format((float) $row['SUM(bags_receipt)'],2,'.',',') .'</td>';
				if($row['SUM(bags_issued)'] != $row['SUM(bags_receipt)'])
				{
					$BAGSREC = '<th class="text-right text-danger">'. number_format((float) $row['SUM(bags_receipt)'],2,'.',',') .'</th>';
				}
				
                $REQUESTEDBY = $row['ureq'];
                $APPROVEDBY = $row['uapp'];
                $ISSUEDBY = $row['uiss'];
                $RECEIVEDBY = $row['urec'];
               
                $REMARKSAP = $row['rapproved'];
                $REMARKSIS = $row['rissued'];
                
				
                 
                echo '<tr>
                        <td class="text-right">'. $row['date'] .'</td>
                        <td>'. $TO .'</td>
                        <td>'. $MATERIAL .' - '. $GRADE .'</td>
                        <td class="text-right">'. number_format((float) $BAGSRE,2,'.',',') .'</td>';
				echo $BAGSAP . $BAGSIS . $BAGSREC;	
				echo	'
                        <td>'. $REQUESTEDBY .'</td>
                        <td>'. $APPROVEDBY .'</td>
                        <td>'. $ISSUEDBY .'</td>
                        <td>'. $REQUESTEDBY .'</td>
                        <td>'. $REMARKSAP . $REMARKSIS .'</td>
                    </tr>';
            }
          
            $stmt->closeCursor();
        }
        else
        {
            echo '<li>Something went wrong.'. $db->errorInfo .'</li>';  
        }
    }
	
	/**
     * Raw materials Imports report
     */
    public function OutStandingOrdersReport()
    {
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
		
		$sql = "SELECT `raw_materials_imports`.`rmi_no`,
    `raw_materials_imports`.`date_shipment`,
    `raw_materials_imports`.`invoice_no`,
    `raw_materials_imports`.`bill_due_date`,
    `raw_materials_imports`.`amount`,
	material_name, material_grade,
    `raw_materials_imports`.`supplier`,
    `raw_materials_imports`.`qty`, `raw_materials_imports`.`status`
		FROM `raw_materials_imports`
    	LEFT JOIN materials ON materials.material_id = `raw_materials_imports`.material_id
		WHERE `raw_materials_imports`.`status` = 2 AND `raw_materials_imports`.`bill_due_date` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59' 
		ORDER BY `raw_materials_imports`.`date_shipment`;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
				
                 echo '<tr>
                        <td>'. $row['rmi_no'] .'</td>
                        <td>'. $row['invoice_no'] .'</td>
                        <td>'. $row['date_shipment'] .'</td>
                        <td>'. $row['bill_due_date'] .'</td>
                        <td class="text-right"> $ '. number_format((float) $row['amount'],0,'.',',') .'</td>
                        <td>'. $MATERIAL .' - '. $GRADE .'</td>
                        <td>'. $row['supplier'] .'</td>
                        <td class="text-right">'. number_format((float) $row['qty'],0,'.',',') .'</td>
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
                    <td></td>
                </tr>";
        }
    }
	
	/**
     * Raw materials Imports report
     */
    public function ImportsReport()
    {
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
		
		$sql = "SELECT `raw_materials_imports`.`raw_materials_imports_id`,
		`raw_materials_imports`.`rmi_no`,material_name, material_grade,
		`raw_materials_imports`.`supplier`,
		`raw_materials_imports`.`qty`,
		`raw_materials_imports`.`amount`,
		`raw_materials_imports`.`date_cleared`,
		`raw_materials_imports`.`declaration_no`,
		`raw_materials_imports`.`duty`,
		`raw_materials_imports`.`clearing`,
		`raw_materials_imports`.`unloading`,
		`raw_materials_imports`.`cost_kg`,
		`raw_materials_imports`.`status`
		FROM `raw_materials_imports`
    	LEFT JOIN materials ON materials.material_id = `raw_materials_imports`.material_id
		WHERE `raw_materials_imports`.`date_cleared` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59' 
		ORDER BY raw_materials_imports_id;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
				$STATUS = '';
				if($row['status']==0)
				{
					$STATUS = "Ordered";
				}
				else if($row['status']==1)
				{
					$STATUS = "Shipped";
				}
				else if($row['status']==2)
				{
					$STATUS = "Cleared";
				}
				else if($row['status']==3)
				{
					$STATUS = "Paid";
				}
				
				$TOTAL = $row['amount'] + $row['duty'] + $row['clearing']+$row['unloading'];
                 echo '<tr>
                        <td>'. $row['rmi_no'] .'</td>
                        <td>'. $MATERIAL .' - '. $GRADE .'</td>
                        <td>'. $row['supplier'] .'</td>
                        <td class="text-right">'. number_format((float) $row['qty'],0,'.',',') .'</td>
                        <td class="text-right">$'. number_format((float) $row['amount'],2,'.',',') .'</td>
                        <td>'. $row['date_cleared'] .'</td>
						<td>'. $row['declaration_no'] .'</td>
						<td class="text-right">$'. number_format((float)$row['duty'],2,'.',',') .'</td>
						<td class="text-right">$'. number_format((float)$row['clearing'],2,'.',',') .'</td>
						<td class="text-right">$'. number_format((float)$row['unloading'],2,'.',',') .'</td>
						<td class="text-right">$'. number_format((float)$TOTAL,2,'.',',') .'</td>
						<td>'. $row['cost_kg'] .'</td>
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
                    <td></td>
                </tr>";
        }
    }
	
	/**
     * Raw materials Imports report
     */
    public function ImportsByMaterialReport()
    {
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
		
		$sql = "SELECT material_name, material_grade, SUM(`raw_materials_imports`.`qty`) as bags, SUM(`raw_materials_imports`.`cost_kg`)/COUNT(raw_materials_imports_id) as price
FROM `raw_materials_imports`
LEFT JOIN materials ON materials.material_id = `raw_materials_imports`.material_id
WHERE `raw_materials_imports`.`date_cleared` BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59' 
GROUP BY `raw_materials_imports`.material_id
ORDER BY material_name, material_grade;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                 echo '<tr>
                        <td>'. $MATERIAL .' - '. $GRADE .'</td>
                        <td class="text-right">'. number_format((float) $row['bags'],0,'.',',') .'</td>
                        <td class="text-right">$'. number_format((float) $row['price'],2,'.',',') .'</td>
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
                    <td></td>
                </tr>";
        }
    }
	
	/**
     * Balance Stock report
     */
    public function BalanceStockReport($machine)
    {
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
			echo '<thead><tr  class="active">';
			echo '<th>Date</th>';
			echo '<th>Raw Material</th>';
			echo '<th>Variance on bags</th>
				<th>Balanced By</th>
				<th>Remarks</th>';
			echo '</tr></thead> <tfoot><tr class="active">
			<th></th>
			<th>Total</th>
			<th style="text-align:right"></th>
			<th></th>
			<th></th></tr></tfoot><tbody>'; 
			$sql = "SELECT DATE_FORMAT(`date_balance`, '%b/%Y') as date, material_name, material_grade,
						SUM(difference) as diff,GROUP_CONCAT( username SEPARATOR '') as users
					   ,GROUP_CONCAT(`stock_balance`.`remarks` SEPARATOR '') as remark
					FROM `stock_balance`
    				LEFT JOIN materials ON materials.material_id = `stock_balance`.material_id
					JOIN users ON users.user_id = `stock_balance`.user_id
					WHERE machine_id = ". $machine ." AND date_balance BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59' 
					GROUP BY DATE_FORMAT(`date_balance`, '%m/%Y') , `stock_balance`.material_id
					ORDER BY `date_balance` , `stock_balance`.material_id;";
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
			echo '<thead><tr  class="active">';
			echo '<th>Date</th>';
			echo '<th>Raw Material</th>';
			echo '<th>Variance on bags</th>
				<th>Balanced By</th>
				<th>Remarks</th>';
			echo '</tr></thead><tfoot><tr class="active">
			<th></th>
			<th>Total</th>
			<th style="text-align:right"></th>
			<th></th>
			<th></th></tr></tfoot><tbody>'; 
			$sql = "SELECT DATE_FORMAT(`date_balance`, '%Y') as date, material_name, material_grade,
						SUM(difference) as diff,GROUP_CONCAT( username SEPARATOR '') as users
					   ,GROUP_CONCAT(`stock_balance`.`remarks` SEPARATOR '') as remark
					FROM `stock_balance`
    				LEFT JOIN materials ON materials.material_id = `stock_balance`.material_id
					JOIN users ON users.user_id = `stock_balance`.user_id
					WHERE machine_id = ". $machine ." AND date_balance BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59' 
					GROUP BY DATE_FORMAT(`date_balance`, '%Y') , `stock_balance`.material_id
					ORDER BY `date_balance` , `stock_balance`.material_id;";
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
			echo '<thead><tr  class="active">';
			echo '<th>Date</th>';
			echo '<th>Raw Material</th>';
			echo '<th>Variance on bags</th>
				<th>Bags/drumps/pieces on UPS</th>
				<th>Bags/drumps/pieces on Floor</th>
				<th>Balanced By</th>
				<th>Remarks</th>';
			echo '</tr></thead><tfoot><tr class="active">
			<th></th>
			<th>Total</th>
			<th style="text-align:right"></th>
			<th></th>
			<th></th><th></th>
			<th></th></tr></tfoot><tbody>';  
			$sql = "SELECT DATE_FORMAT(`date_balance`, '%Y/%m/%d') as date, material_name, material_grade,
						oldbags, newbags,difference as diff,username as users,remarks as remark
					FROM `stock_balance`
    				LEFT JOIN materials ON materials.material_id = `stock_balance`.material_id
					JOIN users ON users.user_id = `stock_balance`.user_id
					WHERE machine_id = ". $machine ." AND date_balance BETWEEN '". $newDateString ." 00:00:00' AND '". $newDateString2 ." 23:59:59' 
					ORDER BY `date_balance`;";
        }
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $DATE = $row['date'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $DIFERENCE = $row['diff'];
                $USER = $row['users'];
                $REMARKS = $row['remark'];
                 
                 echo '<tr>
                        <td>'. $DATE .'</td>
                        <td>'. $MATERIAL .' - '. $GRADE .'</td> 
						<th class="text-right text-danger">'. number_format((float) $DIFERENCE,4,'.',',') .'</th>';
				if(empty($_POST['searchBy']) or $_POST['searchBy']==1)
        		{
					echo '<td class="text-right">'. number_format((float) $row['oldbags'],4,'.',',') .'</td>
						<td class="text-right">'. number_format((float) $row['newbags'],4,'.',',') .'</td>';
				}
				
				echo '
						<td>'. $USER .'</td>
                        <td>'. $REMARKS .'</td>
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
                    <td></td>
                </tr>";
        }
        echo '</tbody>';
    }
	
	
	public function reportLocalPurchases()
    {
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
		$sql = "SELECT `local_purchases`.`local_purchase_id`,
				`local_purchases`.`material_id`, material_name, material_grade,
				`local_purchases`.`date_arrived`,
				`local_purchases`.`invoice_no`,
				`local_purchases`.`supplier`,
				`local_purchases`.`qty`,
				`local_purchases`.`amount`,
				`local_purchases`.`remarks`,
				`local_purchases`.`user_id`, username,
				`local_purchases`.`cost_kg`
			FROM `local_purchases`
    		LEFT JOIN materials ON materials.material_id = `local_purchases`.material_id
			JOIN users ON users.user_id = `local_purchases`.user_id
			WHERE YEAR(date_arrived) = YEAR(CURRENT_DATE());";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['qty'];
                $REMARKS = $row['remarks'];
                 echo '<tr>
                        <td>'. $row['date_arrived'] .'</td>
                        <td>'. $MATERIAL .' - '. $GRADE .'</td>
                        <td>'. $row['supplier'] .'</td>
                        <td>'. $row['invoice_no'] .'</td>
                        <td class="text-right">'. number_format((float) $BAGS,0,'.',',') .'</td>
                        <td class="text-right">'. number_format((float) $row['amount'],2,'.',',') .'</td>
                        <td class="text-right">'. number_format((float) $row['cost_kg'],2,'.',',') .'</td>
                        <td>'. $row['username'] .'</td>
                        <td>'. $REMARKS .'</td>
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
	
	public function ClosingMonth()
	{
		$TYPE = "";
		for($x = 1; $x<5; ++$x) 
		{ 
			 if($x == 1)
			{
				$sql = "SELECT `materials`.`material_id`
					FROM `materials`
					WHERE material = 1
					ORDER BY `materials`.`material_name`;";
				$TYPE = "Raw Material";
			}
			else if($x == 2)
			{
				$sql = "SELECT `materials`.`material_id`
					FROM `materials`
					WHERE master_batch = 1
					ORDER BY `materials`.`material_name`;";
				$TYPE = "Master Batch";
			}
			else if($x == 3)
			{
				$sql = "SELECT `materials`.`material_id`
					FROM `materials`
					WHERE color = 1
					ORDER BY `materials`.`material_name`;";
				$TYPE = "Ink or Solvent";
			}
			else if($x == 4)
			{
				$sql = "SELECT `materials`.`material_id`
					FROM `materials`
					WHERE consumables = 1 AND semifinished = 0
					ORDER BY `materials`.`material_name`;";
				$TYPE = "Consumables";
			}
			if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $material = $row['material_id'];
				$query = "SELECT 
						`raw_materials_imports`.date_cleared AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1
							AND `stock_balance`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `raw_materials_imports`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							AND `raw_materials_imports`.raw_materials_imports_id <> cleared_2.raw_materials_imports_id
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
					WHERE
						`raw_materials_imports`.`material_id` = ".$material."
					UNION ALL SELECT 
						`local_purchases`.date_arrived AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						SUM(local_purchases.qty) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `local_purchases`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
							LEFT JOIN  (SELECT SUM(bags_issued) as bags_issued, machine_from, material_id, date_required 
                            FROM `stock_materials_transfers`
                            GROUP BY machine_from, material_id, date_required)
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = `local_purchases`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1
							AND `stock_balance`.`material_id` = `local_purchases`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `local_purchases`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `local_purchases`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` = ".$material." 
					group by `local_purchases`.date_arrived, `local_purchases`.`material_id`
					UNION ALL SELECT 
						`rm_loans`.date_arrived AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + SUM(rm_loans.qty) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `rm_loans`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
							LEFT JOIN (SELECT SUM(bags_issued) as bags_issued, machine_from, material_id, date_required 
                            FROM `stock_materials_transfers`
                            GROUP BY machine_from, material_id, date_required)
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = `rm_loans`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = `rm_loans`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `rm_loans`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `rm_loans`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` = ".$material." 
					group by `rm_loans`.date_arrived, `rm_loans`.`material_id` 
					UNION ALL SELECT 
						DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(SUM(stock_materials_transfers.bags_issued), 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `stock_materials_transfers`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `stock_materials_transfers`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = `stock_materials_transfers`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `stock_materials_transfers`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `stock_materials_transfers`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` = ".$material."
							AND `stock_materials_transfers`.machine_from = 1 
							group by datereport
					UNION ALL SELECT 
						DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`stock_balance` ON `stock_balance`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `stock_balance`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `stock_balance`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = `stock_balance`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `stock_balance`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `stock_balance`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` = ".$material." AND `stock_balance`.machine_id = 1 
					UNION ALL SELECT 
						DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`raw_materials_imports` cleared_2 ON cleared_2.material_id = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` IS NULL
							AND cleared_2.material_id = ".$material."
							AND cleared_2.date_cleared2 IS NOT NULL 
					UNION ALL SELECT 
						DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`stock_materials_transfers` trans ON trans.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = trans.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = trans.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = trans.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = trans.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON cleared_2.material_id = trans.`material_id`
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` IS NULL
							AND cleared_2.material_id IS NULL
							AND trans.machine_to = 1
							AND trans.material_id = ".$material."
					UNION ALL SELECT 
						dateTable.selected_date,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						(SELECT 
							ADDDATE('2018-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) selected_date
						FROM
							(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) dateTable
							LEFT JOIN
						`raw_materials_imports` ON `raw_materials_imports`.`material_id` = ".$material."
							AND DATE_FORMAT(`raw_materials_imports`.date_cleared,
								'%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = ".$material."
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = ".$material."
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = ".$material."
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = ".$material."
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON cleared_2.material_id = ".$material."
							AND cleared_2.date_cleared2 IS NOT NULL
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = ".$material."
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = dateTable.selected_date";
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
					
					

					$sql2= "SELECT DATE_FORMAT(`datereport`, '%b/%Y') AS datereport, material_name, material_grade,  closing
FROM
	(
    SELECT 
			datereport,".$material." as material_id,
			@a AS opening,imported, local, other, issued, difference,
			@a:=@a + imported + local + other - issued + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(imported) as imported, SUM(local) as local, SUM(other) as other, SUM(issued) as issued, SUM(difference) as difference
			FROM
			(
					". $query ."
					WHERE
						selected_date <= '". $newDateString2 ."'
							AND `raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` IS NULL
							AND cleared_2.material_id IS NULL
							AND trans.material_id IS NULL
				ORDER BY datereport
			) movements GROUP BY DATE_FORMAT(datereport, '%m/%Y') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
JOIN materials ON materials.material_id = report.material_id
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

							$sql2= "SELECT DATE_FORMAT(`datereport`, '%Y') AS datereport, material_name, material_grade,  closing
FROM
	(
    SELECT 
			datereport,".$material." as material_id,
			@a AS opening,imported, local, other, issued, difference,
			@a:=@a + imported + local + other - issued + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(imported) as imported, SUM(local) as local, SUM(other) as other, SUM(issued) as issued, SUM(difference) as difference
			FROM
			(
				". $query ."
					WHERE
						selected_date <= '". $newDateString2 ."'
							AND `raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` IS NULL
							AND cleared_2.material_id IS NULL
							AND trans.material_id IS NULL
				ORDER BY datereport
			) movements GROUP BY DATE_FORMAT(datereport, '%Y') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
JOIN materials ON materials.material_id = report.material_id
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
					
							$sql2= "SELECT DATE_FORMAT(`datereport`, '%d/%m/%Y') AS datereport, material_name, material_grade,  closing
FROM
	(
    SELECT 
			datereport,".$material." as material_id,
			@a AS opening,imported, local, other, issued, difference,
			@a:=@a + imported + local + other - issued + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(imported) as imported, SUM(local) as local, SUM(other) as other, SUM(issued) as issued, SUM(difference) as difference
			FROM
			(
					". $query ."
					WHERE
						selected_date <= '". $newDateString2 ."'
							AND `raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` IS NULL
							AND cleared_2.material_id IS NULL
							AND trans.material_id IS NULL
			ORDER BY datereport
			) movements GROUP BY DATE_FORMAT(datereport, '%Y-%m-%d') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
JOIN materials ON materials.material_id = report.material_id
WHERE datereport BETWEEN '". $newDateString ."' AND '". $newDateString2 ."'
ORDER BY report.datereport;";

				}
				
				ini_set('max_execution_time', 300);
					if($stmt2 = $this->_db->prepare($sql2))
					{
						$stmt2->execute();
						while($row2 = $stmt2->fetch())
						{
							$DATE = $row2['datereport'];
							$MATERIAL = $row2['material_name'];
							$GRADE = $row2['material_grade'];
							$CLOSING = $row2['closing'];

						
							if($x == 2)
							{
								
								$CLOSING = $CLOSING*25;
								echo '<tr>
									<td class="text-right">'. $DATE .'</td>
                        			<td>'. $TYPE .'</td>
                        			<td>'. $MATERIAL .' - '. $GRADE .'</td>';
								echo '
										<td class="text-right">'. number_format((float) $CLOSING,2,'.',',') .'</td>
									</tr>';
							}
							else
							{
								echo '<tr>
									<td class="text-right">'. $DATE .'</td>
                        			<td>'. $TYPE .'</td>
                        			<td>'. $MATERIAL .' - '. $GRADE .'</td>';
							echo '
									<td class="text-right">'. number_format((float) $CLOSING,2,'.',',') .'</td>
								</tr>';
							}
							
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
	
	public function Calculatestock()
    {
		for($x = 1; $x<5; ++$x) 
		{ 
		if($x == 1)
		{
        	$sql = "SELECT `materials`.`material_id`
                FROM `materials`
				WHERE material = 1
                ORDER BY `materials`.`material_name`;";
		}
		else if($x == 2)
		{
        	$sql = "SELECT `materials`.`material_id`
                FROM `materials`
				WHERE master_batch = 1
                ORDER BY `materials`.`material_name`;";
		}
		else if($x == 3)
		{
        	$sql = "SELECT `materials`.`material_id`
                FROM `materials`
				WHERE color = 1
                ORDER BY `materials`.`material_name`;";
		}
		else if($x == 4)
		{
        	$sql = "SELECT `materials`.`material_id`
                FROM `materials`
				WHERE consumables = 1 AND semifinished = 0
                ORDER BY `materials`.`material_name`;";
		}
		
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $material = $row['material_id'];
				$query = "SELECT 
						`raw_materials_imports`.date_cleared AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1
							AND `stock_balance`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `raw_materials_imports`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							AND `raw_materials_imports`.raw_materials_imports_id <> cleared_2.raw_materials_imports_id
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
					WHERE
						`raw_materials_imports`.`material_id` = ".$material."
					UNION ALL SELECT 
						`local_purchases`.date_arrived AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						SUM(local_purchases.qty) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `local_purchases`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
							LEFT JOIN  (SELECT SUM(bags_issued) as bags_issued, machine_from, material_id, date_required 
                            FROM `stock_materials_transfers`
                            GROUP BY machine_from, material_id, date_required)
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = `local_purchases`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1
							AND `stock_balance`.`material_id` = `local_purchases`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `local_purchases`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `local_purchases`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` = ".$material." 
					group by `local_purchases`.date_arrived, `local_purchases`.`material_id`
					UNION ALL SELECT 
						`rm_loans`.date_arrived AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + SUM(rm_loans.qty) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `rm_loans`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
							LEFT JOIN (SELECT SUM(bags_issued) as bags_issued, machine_from, material_id, date_required 
                            FROM `stock_materials_transfers`
                            GROUP BY machine_from, material_id, date_required)
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = `rm_loans`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = `rm_loans`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `rm_loans`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `rm_loans`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` = ".$material." 
					group by datereport, `rm_loans`.`material_id` 
					UNION ALL SELECT 
						DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(SUM(stock_materials_transfers.bags_issued), 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `stock_materials_transfers`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `stock_materials_transfers`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = `stock_materials_transfers`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `stock_materials_transfers`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `stock_materials_transfers`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` = ".$material."
							AND `stock_materials_transfers`.machine_from = 1 
							group by datereport
					UNION ALL SELECT 
						DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`stock_balance` ON `stock_balance`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `stock_balance`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `stock_balance`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = `stock_balance`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `stock_balance`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `stock_balance`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` = ".$material." AND `stock_balance`.machine_id = 1 
					UNION ALL SELECT 
						DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`raw_materials_imports` cleared_2 ON cleared_2.material_id = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` IS NULL
							AND cleared_2.material_id = ".$material."
							AND cleared_2.date_cleared2 IS NOT NULL 
					UNION ALL SELECT 
						DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`stock_materials_transfers` trans ON trans.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = trans.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = trans.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = trans.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = trans.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON cleared_2.material_id = trans.`material_id`
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` IS NULL
							AND cleared_2.material_id IS NULL
							AND trans.machine_to = 1
							AND trans.material_id = ".$material."
					UNION ALL SELECT 
						dateTable.selected_date,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						(SELECT 
							ADDDATE('2018-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) selected_date
						FROM
							(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) dateTable
							LEFT JOIN
						`raw_materials_imports` ON `raw_materials_imports`.`material_id` = ".$material."
							AND DATE_FORMAT(`raw_materials_imports`.date_cleared,
								'%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = ".$material."
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = ".$material."
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = ".$material."
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = ".$material."
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON cleared_2.material_id = ".$material."
							AND cleared_2.date_cleared2 IS NOT NULL
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = ".$material."
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = dateTable.selected_date";
				$newDateString = date("Y-m-d");
        		$newDateString2 = date("Y-m-d");
				
					$sql2= "SELECT DATE_FORMAT(`datereport`, '%Y-%m-%d') AS datereport, material_name, material_grade, opening, imported, local, other, issued, difference, closing
FROM
	(
    SELECT 
			datereport,".$material." as material_id,
			@a AS opening,imported, local, other, issued, difference,
			@a:=@a + imported + local + other - issued + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(imported) as imported, SUM(local) as local, SUM(other) as other, SUM(issued) as issued, SUM(difference) as difference
			FROM
			(
					". $query ."
					WHERE
						selected_date <= '". $newDateString2 ."'
							AND `raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` IS NULL
							AND cleared_2.material_id IS NULL
							AND trans.material_id IS NULL
			ORDER BY datereport
			) movements GROUP BY DATE_FORMAT(datereport, '%Y-%m-%d') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
JOIN materials ON materials.material_id = report.material_id
WHERE datereport BETWEEN '". $newDateString ."' AND '". $newDateString2 ."'
ORDER BY report.datereport;";

				
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
							$IMPORT = $row2['imported'];
							$LOCAL = $row2['local'];
							$OTHER = $row2['other'];
							$ISSUED = $row2['issued'];
							$CLOSING = $row2['closing'];
							
							if($DATE == date("Y-m-d"))
							{
								$sql3 = "SELECT `stock_materials`.`stock_material_id`,`stock_materials`.`material_id`, `stock_materials`.`machine_id`,`stock_materials`.`bags` 
								FROM `stock_materials`
								WHERE material_id = ".$material." AND machine_id = 1;";
								if($stmt3 = $this->_db->prepare($sql3))
								{
									$stmt3->execute();
									while($row3 = $stmt3->fetch())
									{
										if($row3['bags'] != $CLOSING)
										{
											$sql4 = "UPDATE `stock_materials` SET`bags` = :bags
											WHERE `stock_material_id` = :id;";
											try
											{   
												$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
												$stmt4 = $this->_db->prepare($sql4);
												$stmt4->bindParam(":id", $row3['stock_material_id'], PDO::PARAM_INT);
												$stmt4->bindParam(":bags", $CLOSING, PDO::PARAM_INT);
												$stmt4->execute();
												$stmt4->closeCursor();
											} catch (PDOException $e) {
												echo '<strong>ERROR</strong> There is an error recalculating the stock, please try to execute it again.<br>'. $e->getMessage();
												
												return false;
											} 
										}
									}
								}
							}

							
							
							
						}
						
						$stmt2->closeCursor();
					}
				
            }
            $stmt->closeCursor();
        }
		
		echo '<strong>SUCCESS</strong> The stock was succesfully recalculated';
		return true;
                  
		}
    }
	
	/**
     * Loads the report of raw material opening and closing
     *
     * This function outputs <li> tags with materials
     */
    public function CalculatestockMaterial($materialid)
    {
		$material = $materialid;
				$query = "SELECT 
						`raw_materials_imports`.date_cleared AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1
							AND `stock_balance`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `raw_materials_imports`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							AND `raw_materials_imports`.raw_materials_imports_id <> cleared_2.raw_materials_imports_id
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
					WHERE
						`raw_materials_imports`.`material_id` = ".$material."
					UNION ALL SELECT 
						`local_purchases`.date_arrived AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						SUM(local_purchases.qty) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `local_purchases`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
							LEFT JOIN  (SELECT SUM(bags_issued) as bags_issued, machine_from, material_id, date_required 
                            FROM `stock_materials_transfers`
                            GROUP BY machine_from, material_id, date_required)
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = `local_purchases`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1
							AND `stock_balance`.`material_id` = `local_purchases`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `local_purchases`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `local_purchases`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` = ".$material." 
					group by `local_purchases`.date_arrived, `local_purchases`.`material_id`
					UNION ALL SELECT 
						`rm_loans`.date_arrived AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + SUM(rm_loans.qty) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `rm_loans`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
							LEFT JOIN (SELECT SUM(bags_issued) as bags_issued, machine_from, material_id, date_required 
                            FROM `stock_materials_transfers`
                            GROUP BY machine_from, material_id, date_required)
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = `rm_loans`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = `rm_loans`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `rm_loans`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `rm_loans`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` = ".$material." 
					group by datereport, `rm_loans`.`material_id` 
					UNION ALL SELECT 
						DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(SUM(stock_materials_transfers.bags_issued), 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `stock_materials_transfers`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `stock_materials_transfers`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = `stock_materials_transfers`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `stock_materials_transfers`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `stock_materials_transfers`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` = ".$material."
							AND `stock_materials_transfers`.machine_from = 1 
							group by datereport
					UNION ALL SELECT 
						DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`stock_balance` ON `stock_balance`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `stock_balance`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `stock_balance`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = `stock_balance`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `stock_balance`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `stock_balance`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` = ".$material." AND `stock_balance`.machine_id = 1 
					UNION ALL SELECT 
						DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`raw_materials_imports` cleared_2 ON cleared_2.material_id = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` IS NULL
							AND cleared_2.material_id = ".$material."
							AND cleared_2.date_cleared2 IS NOT NULL 
					UNION ALL SELECT 
						DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`stock_materials_transfers` trans ON trans.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = trans.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = trans.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = trans.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = trans.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON cleared_2.material_id = trans.`material_id`
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` IS NULL
							AND cleared_2.material_id IS NULL
							AND trans.machine_to = 1
							AND trans.material_id = ".$material."
					UNION ALL SELECT 
						dateTable.selected_date,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						(SELECT 
							ADDDATE('2018-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) selected_date
						FROM
							(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) dateTable
							LEFT JOIN
						`raw_materials_imports` ON `raw_materials_imports`.`material_id` = ".$material."
							AND DATE_FORMAT(`raw_materials_imports`.date_cleared,
								'%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = ".$material."
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = ".$material."
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = ".$material."
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = ".$material."
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON cleared_2.material_id = ".$material."
							AND cleared_2.date_cleared2 IS NOT NULL
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = ".$material."
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = dateTable.selected_date";
				$newDateString = date("Y-m-d");
        		$newDateString2 = date("Y-m-d");
				
					$sql2= "SELECT DATE_FORMAT(`datereport`, '%Y-%m-%d') AS datereport, material_name, material_grade, opening, imported, local, other, issued, difference, closing
FROM
	(
    SELECT 
			datereport,".$material." as material_id,
			@a AS opening,imported, local, other, issued, difference,
			@a:=@a + imported + local + other - issued + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(imported) as imported, SUM(local) as local, SUM(other) as other, SUM(issued) as issued, SUM(difference) as difference
			FROM
			(
					". $query ."
					WHERE
						selected_date <= '". $newDateString2 ."'
							AND `raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` IS NULL
							AND cleared_2.material_id IS NULL
							AND trans.material_id IS NULL
			ORDER BY datereport
			) movements GROUP BY DATE_FORMAT(datereport, '%Y-%m-%d') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
JOIN materials ON materials.material_id = report.material_id
WHERE datereport BETWEEN '". $newDateString ."' AND '". $newDateString2 ."'
ORDER BY report.datereport;";

				
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
							$IMPORT = $row2['imported'];
							$LOCAL = $row2['local'];
							$OTHER = $row2['other'];
							$ISSUED = $row2['issued'];
							$CLOSING = $row2['closing'];
							
							if($DATE == date("Y-m-d"))
							{
								$sql3 = "SELECT `stock_materials`.`stock_material_id`,`stock_materials`.`material_id`, `stock_materials`.`machine_id`,`stock_materials`.`bags` 
								FROM `stock_materials`
								WHERE material_id = ".$material." AND machine_id = 1;";
								if($stmt3 = $this->_db->prepare($sql3))
								{
									$stmt3->execute();
									while($row3 = $stmt3->fetch())
									{
										if($row3['bags'] != $CLOSING)
										{
											$sql4 = "UPDATE `stock_materials` SET`bags` = :bags
											WHERE `stock_material_id` = :id;";
											try
											{   
												$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
												$stmt4 = $this->_db->prepare($sql4);
												$stmt4->bindParam(":id", $row3['stock_material_id'], PDO::PARAM_INT);
												$stmt4->bindParam(":bags", $CLOSING, PDO::PARAM_INT);
												$stmt4->execute();
												$stmt4->closeCursor();
											} catch (PDOException $e) {
												echo '<strong>ERROR</strong> There is an error recalculating the stock, please try to execute it again.<br>'. $e->getMessage();
												
												return false;
											} 
										}
									}
								}
							}
							
						}
						$stmt2->closeCursor();
					}
		echo '<strong>SUCCESS</strong> The stock was succesfully recalculated';
		return true;
    }
		 
		
	/**
     * Loads the report of raw material opening and closing
     *
     * This function outputs <li> tags with materials
     */
    public function OpeningClosing($x)
    {
		if($x == 1)
		{
        	$sql = "SELECT `materials`.`material_id`
                FROM `materials`
				WHERE material = 1
                ORDER BY `materials`.`material_name`;";
		}
		else if($x == 2)
		{
        	$sql = "SELECT `materials`.`material_id`
                FROM `materials`
				WHERE master_batch = 1
                ORDER BY `materials`.`material_name`;";
		}
		else if($x == 3)
		{
        	$sql = "SELECT `materials`.`material_id`
                FROM `materials`
				WHERE color = 1
                ORDER BY `materials`.`material_name`;";
		}
		else if($x == 4)
		{
        	$sql = "SELECT `materials`.`material_id`
                FROM `materials`
				WHERE consumables = 1 AND semifinished = 0
                ORDER BY `materials`.`material_name`;";
		}
		
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $material = $row['material_id'];
				$query = "SELECT 
						`raw_materials_imports`.date_cleared AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(SUM(stock_materials_transfers.bags_issued), 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1
							AND `stock_balance`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `raw_materials_imports`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							AND `raw_materials_imports`.raw_materials_imports_id <> cleared_2.raw_materials_imports_id
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
					WHERE
						`raw_materials_imports`.`material_id` = ".$material."
					group by `raw_materials_imports`.date_cleared, `raw_materials_imports`.`material_id`
					UNION ALL SELECT 
						`local_purchases`.date_arrived AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `local_purchases`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
							LEFT JOIN  (SELECT SUM(bags_issued) as bags_issued, machine_from, material_id, date_required 
                            FROM `stock_materials_transfers`
                            GROUP BY machine_from, material_id, date_required)
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = `local_purchases`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1
							AND `stock_balance`.`material_id` = `local_purchases`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `local_purchases`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `local_purchases`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` = ".$material." 
					group by `local_purchases`.date_arrived, `local_purchases`.`material_id`
					UNION ALL SELECT 
						`rm_loans`.date_arrived AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `rm_loans`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
							LEFT JOIN (SELECT SUM(bags_issued) as bags_issued, machine_from, material_id, date_required 
                            FROM `stock_materials_transfers`
                            GROUP BY machine_from, material_id, date_required)
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = `rm_loans`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = `rm_loans`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `rm_loans`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `rm_loans`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` = ".$material." 
					group by datereport, `rm_loans`.`material_id` 
					UNION ALL SELECT 
						DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(SUM(stock_materials_transfers.bags_issued), 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `stock_materials_transfers`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `stock_materials_transfers`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = `stock_materials_transfers`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `stock_materials_transfers`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `stock_materials_transfers`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` = ".$material."
							AND `stock_materials_transfers`.machine_from = 1 
							group by datereport
					UNION ALL SELECT 
						DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`stock_balance` ON `stock_balance`.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = `stock_balance`.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = `stock_balance`.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = `stock_balance`.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON `stock_balance`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = `stock_balance`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` = ".$material." AND `stock_balance`.machine_id = 1 
					UNION ALL SELECT 
						DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`raw_materials_imports` cleared_2 ON cleared_2.material_id = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = cleared_2.material_id
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` IS NULL
							AND cleared_2.material_id = ".$material."
							AND cleared_2.date_cleared2 IS NOT NULL 
					UNION ALL SELECT 
						DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') AS datereport,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						`raw_materials_imports`
							RIGHT JOIN
						`stock_materials_transfers` trans ON trans.`material_id` = `raw_materials_imports`.`material_id`
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = `raw_materials_imports`.date_cleared
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = trans.`material_id`
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = trans.`material_id`
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = trans.`material_id`
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = trans.`material_id`
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON cleared_2.material_id = trans.`material_id`
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = DATE_FORMAT(trans.`date_required`, '%Y-%m-%d')
					WHERE
						`raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` IS NULL
							AND cleared_2.material_id IS NULL
							AND trans.machine_to = 1
							AND trans.material_id = ".$material."
					UNION ALL SELECT 
						dateTable.selected_date,
						COALESCE(raw_materials_imports.qty_cleared, 0) + COALESCE(cleared_2.qty_cleared2, 0) AS imported,
						COALESCE(local_purchases.qty, 0) + COALESCE(rm_loans.qty, 0) AS local,
						COALESCE(stock_materials_transfers.bags_issued, 0) AS issued,
						COALESCE(trans.bags_receipt, 0) AS other,
						COALESCE(stock_balance.difference, 0) AS difference
					FROM
						(SELECT 
							ADDDATE('2018-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) selected_date
						FROM
							(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) dateTable
							LEFT JOIN
						`raw_materials_imports` ON `raw_materials_imports`.`material_id` = ".$material."
							AND DATE_FORMAT(`raw_materials_imports`.date_cleared,
								'%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`local_purchases` ON `local_purchases`.`material_id` = ".$material."
							AND DATE_FORMAT(`local_purchases`.date_arrived,
								'%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`rm_loans` ON `rm_loans`.`material_id` = ".$material."
							AND DATE_FORMAT(`rm_loans`.date_arrived, '%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`stock_materials_transfers` ON `stock_materials_transfers`.machine_from = 1
							AND `stock_materials_transfers`.`material_id` = ".$material."
							AND DATE_FORMAT(`stock_materials_transfers`.`date_required`,
								'%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`stock_balance` ON `stock_balance`.machine_id = 1  AND `stock_balance`.`material_id` = ".$material."
							AND DATE_FORMAT(`stock_balance`.`date_balance`,
								'%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`raw_materials_imports` cleared_2 ON cleared_2.material_id = ".$material."
							AND cleared_2.date_cleared2 IS NOT NULL
							AND DATE_FORMAT(cleared_2.date_cleared2, '%Y-%m-%d') = dateTable.selected_date
							LEFT JOIN
						`stock_materials_transfers` trans ON trans.machine_to = 1
							AND trans.`material_id` = ".$material."
							AND DATE_FORMAT(trans.`date_required`, '%Y-%m-%d') = dateTable.selected_date";
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
					
					

					$sql2= "SELECT DATE_FORMAT(`datereport`, '%b/%Y') AS datereport, material_name, material_grade, opening, imported, local, other, issued, difference, closing
FROM
	(
    SELECT 
			datereport,".$material." as material_id,
			@a AS opening,imported, local, other, issued, difference,
			@a:=@a + imported + local + other - issued + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(imported) as imported, SUM(local) as local, SUM(other) as other, SUM(issued) as issued, SUM(difference) as difference
			FROM
			(
					". $query ."
					WHERE
						selected_date <= '". $newDateString2 ."'
							AND `raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` IS NULL
							AND cleared_2.material_id IS NULL
							AND trans.material_id IS NULL
				ORDER BY datereport
			) movements GROUP BY DATE_FORMAT(datereport, '%m/%Y') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
JOIN materials ON materials.material_id = report.material_id
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

							$sql2= "SELECT DATE_FORMAT(`datereport`, '%Y') AS datereport, material_name, material_grade, opening, imported, local, other, issued, difference, closing
FROM
	(
    SELECT 
			datereport,".$material." as material_id,
			@a AS opening,imported, local, other, issued, difference,
			@a:=@a + imported + local + other - issued + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(imported) as imported, SUM(local) as local, SUM(other) as other, SUM(issued) as issued, SUM(difference) as difference
			FROM
			(
				". $query ."
					WHERE
						selected_date <= '". $newDateString2 ."'
							AND `raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` IS NULL
							AND cleared_2.material_id IS NULL
							AND trans.material_id IS NULL
				ORDER BY datereport
			) movements GROUP BY DATE_FORMAT(datereport, '%Y') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
JOIN materials ON materials.material_id = report.material_id
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
					
							$sql2= "SELECT DATE_FORMAT(`datereport`, '%Y-%m-%d') AS datereport, material_name, material_grade, opening, imported, local, other, issued, difference, closing
FROM
	(
    SELECT 
			datereport,".$material." as material_id,
			@a AS opening,imported, local, other, issued, difference,
			@a:=@a + imported + local + other - issued + difference AS closing
		FROM
        (
			SELECT 
					datereport, SUM(imported) as imported, SUM(local) as local, SUM(other) as other, SUM(issued) as issued, SUM(difference) as difference
			FROM
			(
					". $query ."
					WHERE
						selected_date <= '". $newDateString2 ."'
							AND `raw_materials_imports`.`material_id` IS NULL
							AND `local_purchases`.`material_id` IS NULL
							AND `rm_loans`.`material_id` IS NULL
							AND `stock_materials_transfers`.`material_id` IS NULL
							AND `stock_balance`.`material_id` IS NULL
							AND cleared_2.material_id IS NULL
							AND trans.material_id IS NULL
			ORDER BY datereport
			) movements GROUP BY DATE_FORMAT(datereport, '%Y-%m-%d') ORDER BY datereport ) months, 
			(SELECT @a:= 0) t) report
JOIN materials ON materials.material_id = report.material_id
WHERE datereport BETWEEN '". $newDateString ."' AND '". $newDateString2 ."'
ORDER BY report.datereport;";

				}
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
							$IMPORT = $row2['imported'];
							$LOCAL = $row2['local'];
							$OTHER = $row2['other'];
							$ISSUED = $row2['issued'];
							$CLOSING = $row2['closing'];
							
							if($DATE == date("Y-m-d"))
							{
								$sql3 = "SELECT `stock_materials`.`stock_material_id`,`stock_materials`.`material_id`, `stock_materials`.`machine_id`,`stock_materials`.`bags` 
								FROM `stock_materials`
								WHERE material_id = ".$material." AND machine_id = 1;";
								if($stmt3 = $this->_db->prepare($sql3))
								{
									$stmt3->execute();
									while($row3 = $stmt3->fetch())
									{
										if($row3['bags'] != $CLOSING)
										{
											$sql4 = "UPDATE `stock_materials` SET`bags` = :bags
											WHERE `stock_material_id` = :id;";
											try
											{   
												$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
												$stmt4 = $this->_db->prepare($sql4);
												$stmt4->bindParam(":id", $row3['stock_material_id'], PDO::PARAM_INT);
												$stmt4->bindParam(":bags", $CLOSING, PDO::PARAM_INT);
												$stmt4->execute();
												$stmt4->closeCursor();
											} catch (PDOException $e) {
												echo '<strong>ERROR</strong> There is an error running the report, please try to execute it again.<br>'.
													$e->getMessage();
											} 
										}
									}
								}
							}

							
							$DIFF = '<td class="text-right">'. number_format((float) $row2['difference'],0,'.',',') .'</td>';
							if($row2['difference'] != 0)
							{
								$DIFF = '<th class="text-right text-danger">'. number_format((float) $row2['difference'],0,'.',',') .'</th>';
							}
							if($x == 2)
							{
								$OPENING = $OPENING*25;
								$IMPORT = $IMPORT*25;
								$LOCAL = $LOCAL*25;
								$OTHER = $OTHER*25;
								$ISSUED = $ISSUED*25 ;
								$CLOSING = $CLOSING*25;
								if($row2['difference'] != 0)
								{
									$DIFFERENCE = $row2['difference']*25;
									$DIFF = '<th class="text-right text-danger">'. number_format((float) $DIFFERENCE,0,'.',',') .'</th>';
								}
								echo '<tr>
									<td class="text-right">'. $DATE .'</td>
                        			<td>'. $MATERIAL .' - '. $GRADE .'</td>
									<td class="text-right">'. number_format((float) $OPENING,2,'.',',') .'</td>
									<td class="text-right">'. number_format((float) $IMPORT,2,'.',',') .'</td>
									<td class="text-right">'. number_format((float) $LOCAL,2,'.',',') .'</td>
									<td class="text-right">'. number_format((float) $OTHER,2,'.',',') .'</td>
									<td class="text-right">'. number_format((float) $ISSUED,2,'.',',') .'</td>';
								echo $DIFF;
								echo '
										<td class="text-right">'. number_format((float) $CLOSING,2,'.',',') .'</td>
									</tr>';
							}
							else
							{
								echo '<tr>
									<td class="text-right">'. $DATE .'</td>
                        			<td>'. $MATERIAL .' - '. $GRADE .'</td>
									<td class="text-right">'. number_format((float) $OPENING,2,'.',',') .'</td>
									<td class="text-right">'. number_format((float) $IMPORT,2,'.',',') .'</td>
									<td class="text-right">'. number_format((float) $LOCAL,2,'.',',') .'</td>
									<td class="text-right">'. number_format((float) $OTHER,2,'.',',') .'</td>
									<td class="text-right">'. number_format((float) $ISSUED,2,'.',',') .'</td>';
							echo $DIFF;
							echo '
									<td class="text-right">'. number_format((float) $CLOSING,2,'.',',') .'</td>
								</tr>';
							}
							
						}
						
						$stmt2->closeCursor();
					}
					else
					{
						echo 'Something went wrong.'. $db->errorInfo .'';  
					}
				
            }
            $stmt->closeCursor();
        }
        else
        {
            echo '<tr><td>Something went wrong.</tr><td';  
        }
		
		
                  
           
    }
	
	
	 /**
     * Loads the table of all the stock reques of the raw materials
     * This function outputs <tr> tags with stock transfer of raw materials
      $from is the machine_from. $to is the machine_to. I.e $from is Warehouse $to is Multilayer (Transfers from the warehouse to Multilayer)  WHERE YEAR(date_required) = YEAR(CURRENT_DATE())
     */
    public function stockConsumableRequest($to)
    {
		$sql = "SELECT stock_materials_transfers_id, from_table.machine_name AS from_t, to_table.machine_name AS to_t,
                    material_name, material_grade, DATE_FORMAT(`stock_materials_transfers`.`date_required`, '%Y/%m/%d %H:%i') AS date_t, 
                    `stock_materials_transfers`.`bags_required`, u_required.username, `stock_materials_transfers`.`status_transfer`
                FROM stock_materials_transfers 
				JOIN materials ON materials.material_id = stock_materials_transfers.material_id
                INNER JOIN users u_required ON stock_materials_transfers.user_id_required = u_required.user_id
                INNER JOIN machines from_table ON stock_materials_transfers.machine_from = from_table.machine_id
                INNER JOIN machines to_table ON stock_materials_transfers.machine_to = to_table.machine_id AND to_table.location_id =  ". $to ."
				WHERE `consumables` = 1  AND (( MONTH(date_required) >= MONTH(CURRENT_DATE())-1 AND YEAR(date_required) = YEAR(CURRENT_DATE())) OR status_transfer = 0)
                ORDER BY `date_required` DESC, status_transfer, stock_materials_transfers_id DESC;";
		
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $DATE = $row['date_t'];
                $USER = $row['username'];
                $FROM = $row['from_t'];
                $TO = $row['to_t'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags_required'];
                $STATUS = "";
                if($row['status_transfer']==0)
                {
                    $STATUS = "<p class='text-muted'>Requested</p>";
                }
                else if($row['status_transfer']==1)
                {
                    $STATUS = "<p class='text-warning'>Approved</p>";
                }
                else if($row['status_transfer']==2)
                {
                    $STATUS = "<p class='text-info'>Issued</p>";
                }
                else if($row['status_transfer']==3)
                {
                    $STATUS = "<b class='text-success'> Received </b>";
                }
                 
                 
                echo '<tr>
                        <td>'. $DATE .'</td>
                        <td>'. $FROM .'</td>
                        <td>'. $TO .'</td>
                        <td><b>'. $MATERIAL .'</b>&nbsp - &nbsp'. $GRADE .'</td>                        
                        <td class="text-right">'. number_format((float) $BAGS,1,'.',',') .'</td>
                        <td>'. $USER .'</td>
                        <td>'. $STATUS .'</td>
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
     * Loads the table of all the stock reques of the raw materials
     * This function outputs <tr> tags with stock transfer of raw materials
     * $from is the machine_from. $to is the machine_to. I.e $from is Warehouse $to is Multilayer (Transfers from the warehouse to Multilayer)  WHERE MONTH(date_required) >= MONTH(CURRENT_DATE())-1 AND YEAR(date_required) = YEAR(CURRENT_DATE())
     */
    public function stockRequest($to)
    {
		$sql = "SELECT stock_materials_transfers_id, from_table.machine_name AS from_t, to_table.machine_name AS to_t,
                    material_name, material_grade, DATE_FORMAT(`stock_materials_transfers`.`date_required`, '%Y/%m/%d %H:%i') AS date_t, 
                    `stock_materials_transfers`.`bags_required`, u_required.username, `stock_materials_transfers`.`status_transfer`
                FROM stock_materials_transfers 
				JOIN materials ON materials.material_id = stock_materials_transfers.material_id
                INNER JOIN users u_required ON stock_materials_transfers.user_id_required = u_required.user_id
                INNER JOIN machines from_table ON stock_materials_transfers.machine_from = from_table.machine_id
                INNER JOIN machines to_table ON stock_materials_transfers.machine_to = to_table.machine_id AND to_table.location_id =  ". $to ."
				WHERE (( MONTH(date_required) >= MONTH(CURRENT_DATE())-1 AND YEAR(date_required) = YEAR(CURRENT_DATE())) OR status_transfer = 0)
                ORDER BY `date_required` DESC, status_transfer, stock_materials_transfers_id DESC;";
		
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $DATE = $row['date_t'];
                $USER = $row['username'];
                $FROM = $row['from_t'];
                $TO = $row['to_t'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags_required'];
                $STATUS = "";
                if($row['status_transfer']==0)
                {
                    $STATUS = "<p class='text-muted'>Requested</p>";
                }
                else if($row['status_transfer']==1)
                {
                    $STATUS = "<p class='text-warning'>Approved</p>";
                }
                else if($row['status_transfer']==2)
                {
                    $STATUS = "<p class='text-info'>Issued</p>";
                }
                else if($row['status_transfer']==3)
                {
                    $STATUS = "<b class='text-success'> Received </b>";
                }
                 
                 
                echo '<tr>
                        <td>'. $DATE .'</td>
                        <td>'. $FROM .'</td>
                        <td>'. $TO .'</td>
                        <td><b>'. $MATERIAL .'</b>&nbsp'. $GRADE .'</td>                        
                        <td class="text-right">'. number_format((float) $BAGS,1,'.',',') .'</td>
                        <td>'. $USER .'</td>
                        <td>'. $STATUS .'</td>
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
	    
    public function stockMaterialsRequest($to)
    {
		$sql = "SELECT stock_materials_transfers_id, from_table.machine_name AS from_t, to_table.machine_name AS to_t,
                    material_name, material_grade, DATE_FORMAT(`stock_materials_transfers`.`date_required`, '%Y/%m/%d %H:%i') AS date_t, 
                    `stock_materials_transfers`.`bags_required`, u_required.username, `stock_materials_transfers`.`status_transfer`
                FROM stock_materials_transfers 
				JOIN materials ON materials.material_id = stock_materials_transfers.material_id
                INNER JOIN users u_required ON stock_materials_transfers.user_id_required = u_required.user_id
                INNER JOIN machines from_table ON stock_materials_transfers.machine_from = from_table.machine_id
                INNER JOIN machines to_table ON stock_materials_transfers.machine_to = to_table.machine_id AND to_table.location_id =  ". $to ."
				WHERE `material` = 1 AND (( MONTH(date_required) >= MONTH(CURRENT_DATE())-1 AND YEAR(date_required) = YEAR(CURRENT_DATE())) OR status_transfer = 0)
                ORDER BY `date_required` DESC, status_transfer, stock_materials_transfers_id DESC;";
		
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $DATE = $row['date_t'];
                $USER = $row['username'];
                $FROM = $row['from_t'];
                $TO = $row['to_t'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags_required'];
                $STATUS = "";
                if($row['status_transfer']==0)
                {
                    $STATUS = "<p class='text-muted'>Requested</p>";
                }
                else if($row['status_transfer']==1)
                {
                    $STATUS = "<p class='text-warning'>Approved</p>";
                }
                else if($row['status_transfer']==2)
                {
                    $STATUS = "<p class='text-info'>Issued</p>";
                }
                else if($row['status_transfer']==3)
                {
                    $STATUS = "<b class='text-success'> Received </b>";
                }
                 
                 
                echo '<tr>
                        <td>'. $DATE .'</td>
                        <td>'. $FROM .'</td>
                        <td>'. $TO .'</td>
                        <td><b>'. $MATERIAL .'</b>&nbsp'. $GRADE .'</td>                        
                        <td class="text-right">'. number_format((float) $BAGS,1,'.',',') .'</td>
                        <td>'. $USER .'</td>
                        <td>'. $STATUS .'</td>
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
	
	public function stockMaterialsMasterBatchRequest($to)
    {
		$sql = "SELECT stock_materials_transfers_id, from_table.machine_name AS from_t, to_table.machine_name AS to_t,
                    material_name, material_grade, DATE_FORMAT(`stock_materials_transfers`.`date_required`, '%Y/%m/%d %H:%i') AS date_t, 
                    `stock_materials_transfers`.`bags_required`, u_required.username, `stock_materials_transfers`.`status_transfer`
                FROM stock_materials_transfers 
				JOIN materials ON materials.material_id = stock_materials_transfers.material_id
                INNER JOIN users u_required ON stock_materials_transfers.user_id_required = u_required.user_id
                INNER JOIN machines from_table ON stock_materials_transfers.machine_from = from_table.machine_id
                INNER JOIN machines to_table ON stock_materials_transfers.machine_to = to_table.machine_id AND to_table.location_id =  ". $to ."
				WHERE (`material` = 1 OR `master_batch` = 1) AND (( MONTH(date_required) >= MONTH(CURRENT_DATE())-1 AND YEAR(date_required) = YEAR(CURRENT_DATE())) OR status_transfer = 0)
                ORDER BY `date_required` DESC, status_transfer, stock_materials_transfers_id DESC;";
		
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $DATE = $row['date_t'];
                $USER = $row['username'];
                $FROM = $row['from_t'];
                $TO = $row['to_t'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags_required'];
                $STATUS = "";
                if($row['status_transfer']==0)
                {
                    $STATUS = "<p class='text-muted'>Requested</p>";
                }
                else if($row['status_transfer']==1)
                {
                    $STATUS = "<p class='text-warning'>Approved</p>";
                }
                else if($row['status_transfer']==2)
                {
                    $STATUS = "<p class='text-info'>Issued</p>";
                }
                else if($row['status_transfer']==3)
                {
                    $STATUS = "<b class='text-success'> Received </b>";
                }
                 
                 
                echo '<tr>
                        <td>'. $DATE .'</td>
                        <td>'. $FROM .'</td>
                        <td>'. $TO .'</td>
                        <td><b>'. $MATERIAL .'</b>&nbsp'. $GRADE .'</td>                        
                        <td class="text-right">'. number_format((float) $BAGS,1,'.',',') .'</td>
                        <td>'. $USER .'</td>
                        <td>'. $STATUS .'</td>
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
	
	public function stockInkRequest($to)
    {
		$sql = "SELECT stock_materials_transfers_id, from_table.machine_name AS from_t, to_table.machine_name AS to_t,
                    material_name, material_grade, DATE_FORMAT(`stock_materials_transfers`.`date_required`, '%Y/%m/%d %H:%i') AS date_t, 
                    `stock_materials_transfers`.`bags_required`, u_required.username, `stock_materials_transfers`.`status_transfer`, kgs_bag
                FROM stock_materials_transfers 
				JOIN materials ON materials.material_id = stock_materials_transfers.material_id 
                INNER JOIN users u_required ON stock_materials_transfers.user_id_required = u_required.user_id
                INNER JOIN machines from_table ON stock_materials_transfers.machine_from = from_table.machine_id
                INNER JOIN machines to_table ON stock_materials_transfers.machine_to = to_table.machine_id AND to_table.location_id =  ". $to ."
				WHERE `color` = 1 and (( MONTH(date_required) >= MONTH(CURRENT_DATE())-1 AND YEAR(date_required) = YEAR(CURRENT_DATE())) OR status_transfer = 0)
                ORDER BY `date_required` DESC, status_transfer, stock_materials_transfers_id DESC;";
		
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $DATE = $row['date_t'];
                $USER = $row['username'];
                $FROM = $row['from_t'];
                $TO = $row['to_t'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags_required']*$row['kgs_bag'];
                $STATUS = "";
                if($row['status_transfer']==0)
                {
                    $STATUS = "<p class='text-muted'>Requested</p>";
                }
                else if($row['status_transfer']==1)
                {
                    $STATUS = "<p class='text-warning'>Approved</p>";
                }
                else if($row['status_transfer']==2)
                {
                    $STATUS = "<p class='text-info'>Issued</p>";
                }
                else if($row['status_transfer']==3)
                {
                    $STATUS = "<b class='text-success'> Received </b>";
                }
                 
                 
                echo '<tr>
                        <td>'. $DATE .'</td>
                        <td>'. $FROM .'</td>
                        <td>'. $TO .'</td>
                        <td><b>'. $MATERIAL .'</b>&nbsp'. $GRADE .'</td>                        
                        <td class="text-right">'. number_format((float) $BAGS,1,'.',',') .'</td>
                        <td>'. $USER .'</td>
                        <td>'. $STATUS .'</td>
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
	
	
	 public function stockMasterBatchRequest($to)
    {
		$sql = "SELECT stock_materials_transfers_id, from_table.machine_name AS from_t, to_table.machine_name AS to_t,
                    material_name, material_grade, DATE_FORMAT(`stock_materials_transfers`.`date_required`, '%Y/%m/%d %H:%i') AS date_t, 
                    `stock_materials_transfers`.`bags_required`, u_required.username, `stock_materials_transfers`.`status_transfer`, kgs_bag
                FROM stock_materials_transfers 
				JOIN materials ON materials.material_id = stock_materials_transfers.material_id
                INNER JOIN users u_required ON stock_materials_transfers.user_id_required = u_required.user_id
                INNER JOIN machines from_table ON stock_materials_transfers.machine_from = from_table.machine_id
                INNER JOIN machines to_table ON stock_materials_transfers.machine_to = to_table.machine_id AND to_table.location_id =  ". $to ."
				WHERE `master_batch` = 1 AND (( MONTH(date_required) >= MONTH(CURRENT_DATE())-1 AND YEAR(date_required) = YEAR(CURRENT_DATE())) OR status_transfer = 0)
                ORDER BY `date_required` DESC, status_transfer, stock_materials_transfers_id DESC;";
		
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $DATE = $row['date_t'];
                $USER = $row['username'];
                $FROM = $row['from_t'];
                $TO = $row['to_t'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags_required']*$row['kgs_bag'];
                $STATUS = "";
                if($row['status_transfer']==0)
                {
                    $STATUS = "<p class='text-muted'>Requested</p>";
                }
                else if($row['status_transfer']==1)
                {
                    $STATUS = "<p class='text-warning'>Approved</p>";
                }
                else if($row['status_transfer']==2)
                {
                    $STATUS = "<p class='text-info'>Issued</p>";
                }
                else if($row['status_transfer']==3)
                {
                    $STATUS = "<b class='text-success'> Received </b>";
                }
                 
                 
                echo '<tr>
                        <td>'. $DATE .'</td>
                        <td>'. $FROM .'</td>
                        <td>'. $TO .'</td>
                        <td><b>'. $MATERIAL .'</b>&nbsp'. $GRADE .'</td>                        
                        <td class="text-right">'. number_format((float) $BAGS,1,'.',',') .'</td>
                        <td>'. $USER .'</td>
                        <td>'. $STATUS .'</td>
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
	
    public function stockConsumablesRequest($to)
    {
		$sql = "SELECT stock_materials_transfers_id, from_table.machine_name AS from_t, to_table.machine_name AS to_t,
                    material_name, material_grade, DATE_FORMAT(`stock_materials_transfers`.`date_required`, '%Y/%m/%d %H:%i') AS date_t, 
                    `stock_materials_transfers`.`bags_required`, u_required.username, `stock_materials_transfers`.`status_transfer`
                FROM stock_materials_transfers 
				JOIN materials ON materials.material_id = stock_materials_transfers.material_id
                INNER JOIN users u_required ON stock_materials_transfers.user_id_required = u_required.user_id
                INNER JOIN machines from_table ON stock_materials_transfers.machine_from = from_table.machine_id
                INNER JOIN machines to_table ON stock_materials_transfers.machine_to = to_table.machine_id AND to_table.location_id =  ". $to ."
				WHERE `consumables` = 1 AND (( MONTH(date_required) >= MONTH(CURRENT_DATE())-1 AND YEAR(date_required) = YEAR(CURRENT_DATE())) OR status_transfer = 0)
                ORDER BY `date_required` DESC, status_transfer, stock_materials_transfers_id DESC;";
		
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $DATE = $row['date_t'];
                $USER = $row['username'];
                $FROM = $row['from_t'];
                $TO = $row['to_t'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGS = $row['bags_required'];
                $STATUS = "";
                if($row['status_transfer']==0)
                {
                    $STATUS = "<p class='text-muted'>Requested</p>";
                }
                else if($row['status_transfer']==1)
                {
                    $STATUS = "<p class='text-warning'>Approved</p>";
                }
                else if($row['status_transfer']==2)
                {
                    $STATUS = "<p class='text-info'>Issued</p>";
                }
                else if($row['status_transfer']==3)
                {
                    $STATUS = "<b class='text-success'> Received </b>";
                }
                 
                 
                echo '<tr>
                        <td>'. $DATE .'</td>
                        <td>'. $FROM .'</td>
                        <td>'. $TO .'</td>
                        <td><b>'. $MATERIAL .'</b>&nbsp'. $GRADE .'</td>                        
                        <td class="text-right">'. number_format((float) $BAGS,1,'.',',') .'</td>
                        <td>'. $USER .'</td>
                        <td>'. $STATUS .'</td>
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
     * Checks and inserts a new transfer
     *
     * @return boolean  true if can insert  false if not
     */
    public function createRequest($from,$to)
    {
        $material = $bags = "";
        
        $material = trim($_POST["material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
        
        $bags = trim($_POST["bags"]);
        $bags = stripslashes($bags);
        $bags = htmlspecialchars($bags);
        
        $date = "NOW()";
        if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = "'".$newDateString ." ". date("H:i:s")."'";
        }
		
		
        
        $sql = "INSERT INTO  `stock_materials_transfers`(`stock_materials_transfers_id`,`machine_from`,`machine_to`,`material_id`,`date_required`,`bags_required`,`bags_approved`,`bags_issued`,`bags_receipt`,`user_id_required`,`user_id_approved`,`user_id_issued`,`user_id_receipt`,`status_transfer`,`remarks_approved`,`remarks_issued`)VALUES(NULL,:from, :to, :material,". $date .",:bags,NULL,NULL,NULL,:user,NULL,NULL,NULL,0,NULL,NULL);";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":from", $from, PDO::PARAM_INT);
            $stmt->bindParam(":to", $to, PDO::PARAM_INT);
            $stmt->bindParam(":material", $material, PDO::PARAM_INT);
            $stmt->bindParam(":bags", $bags, PDO::PARAM_INT);
            $stmt->bindParam(":user", $_SESSION['Userid'], PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            echo '<strong>SUCCESS!</strong> The request was successfully created.';
            return TRUE;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
              echo '<strong>ERROR</strong> There is a request for this material already in this date.';
            } else {
              echo '<strong>ERROR</strong> Could not insert the request of raw material into the database. Please try again.<br>'. $e->getMessage();
            }return FALSE;
        } 

    }
	
	/**
     * Checks and inserts a new transfer
     *
     * @return boolean  true if can insert  false if not
     */
    public function createIssue($from)
    {
        $material = $bags = $to = "";
        
        $material = trim($_POST["material"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
        
        $bags = trim($_POST["bags"]);
        $bags = stripslashes($bags);
        $bags = htmlspecialchars($bags);
        
		$to = trim($_POST["to"]);
        $to = stripslashes($to);
        $to = htmlspecialchars($to);
		
        $date = "NOW()";
        if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = "'".$newDateString ." 07:00:00'";
        }
        
        $remarks = trim($_POST["remarks"]);
        $remarks = stripslashes($remarks);
        $remarks = htmlspecialchars($remarks);
		
		//CHECK IF THE BAGS IN STOCK MATERIAL ARE GREATER THAN THE BAGS REQUESTED
        $sql = "SELECT stock_material_id, bags, material_name, material_grade
                FROM stock_materials JOIN materials ON materials.material_id = stock_materials.material_id
                WHERE stock_materials.material_id = ". $material ." AND
                machine_id = ". $from .";";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->execute();
            if($row = $stmt->fetch())
            {
                $ID_SM = $row['stock_material_id'];
                $BAGSTOCK = $row['bags'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                if($BAGSTOCK >= $bags)
                {
                    $newbags = $BAGSTOCK - $bags;
                    //DECREASES THE BAGS FROM THE STOCK MATERIALS CHANGE THE STATUS TRANSFER, BGAS ISSUED, AND USER ISSUED
                    $sql = "UPDATE  `stock_materials`
                            SET `bags` = ". $newbags ."
                            WHERE `stock_material_id` = ". $ID_SM .";
							INSERT INTO  `stock_materials_transfers`(`stock_materials_transfers_id`,`machine_from`,`machine_to`,`material_id`,`date_required`,`bags_required`,`bags_approved`,`bags_issued`,`bags_receipt`,`user_id_required`,`user_id_approved`,`user_id_issued`,`user_id_receipt`,`status_transfer`,`remarks_approved`,`remarks_issued`)VALUES(NULL,:from, :to, :material,". $date .",:bags,:bags,:bags,NULL,:user,:user,:user,NULL,2,NULL,:remarks);";
                    try
                    {   
            			$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
						$stmt = $this->_db->prepare($sql);
						$stmt->bindParam(":from", $from, PDO::PARAM_INT);
						$stmt->bindParam(":to", $to, PDO::PARAM_INT);
						$stmt->bindParam(":material", $material, PDO::PARAM_INT);
						$stmt->bindParam(":bags", $bags, PDO::PARAM_INT);
						$stmt->bindParam(":user", $_SESSION['Userid'], PDO::PARAM_INT);
						$stmt->bindParam(":remarks", $remarks, PDO::PARAM_STR);
						$stmt->execute();
						$stmt->closeCursor();
                        echo '<strong>SUCCESS!</strong> The <strong>'. $bags .'</strong>  bags/drumps/pieces of the material:<b> '.$MATERIAL .' - '. $GRADE.' </b>were successfully issued.';
                        return TRUE;
                    } 
                    catch (PDOException $e) {
						if ($e->getCode() == 23000) {
						  echo '<strong>ERROR</strong> There is a issue for this material already in this date.';
						} else {
                          echo '<strong>ERROR</strong> Could not decrease the number of bags/drumps/pieces of this material or create the stock transfer on the database. Please try again.<br>'. $e->getMessage(); 
						}return FALSE;
                        return FALSE;
                    } 
                }
                else
                {
                    echo '<strong>ERROR: </strong>There is not enough bags/drumps/pieces of the material:<b> '.$MATERIAL .' - '. $GRADE.' </b>on stock. There are <b>'. $BAGSTOCK .' bags/drumps/pieces</b> and you want to send <b>'. $bags .' bags/drumps/pieces</b>. Please try with a lower number of bags/drumps/pieces.';
                    return FALSE;
                }
            }
            else
            {
                echo '<strong>ERROR: </strong>There is not bags/drumps/pieces of this material on this stock location.';
                return FALSE;
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not issue the raw material. Please try again.<br>'. $e->getMessage();
            return FALSE;
        } 

    }
	
	
	/**
     * Checks and inserts a new transfer
     *
     * @return boolean  true if can insert  false if not
     */
    public function sent()
    {
        $bags = $to = $from = $material = "";
        
        $bags = trim($_POST["bags"]);
        $bags = stripslashes($bags);
        $bags = htmlspecialchars($bags);
        
		$to = trim($_POST["to"]);
        $to = stripslashes($to);
        $to = htmlspecialchars($to);
		
		$from = trim($_POST["from"]);
        $from = stripslashes($from);
        $from = htmlspecialchars($from);
		
		$material = trim($_POST["material2"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
		
        $date = "NOW()";
        if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = "'".$newDateString ." 07:00:00'";
        }
        
        $remarks = trim($_POST["remarks"]);
        $remarks = stripslashes($remarks);
        $remarks = htmlspecialchars($remarks);
		
		//CHECK IF THE BAGS IN STOCK MATERIAL ARE GREATER THAN THE BAGS REQUESTED
        $sql = "SELECT stock_material_id, bags, material_name, material_grade
                FROM stock_materials 
				JOIN materials ON materials.material_id = stock_materials.material_id
                WHERE stock_materials.material_id = ". $material." AND machine_id = ". $from." ;";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->execute();
            if($row = $stmt->fetch())
            {
                $ID_SM = $row['stock_material_id'];
                $BAGSTOCK = $row['bags'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                if($BAGSTOCK >= $bags)
		
                {
                    $newbags = $BAGSTOCK - $bags;
                    //DECREASES THE BAGS FROM THE STOCK MATERIALS CHANGE THE STATUS TRANSFER, BGAS ISSUED, AND USER ISSUED
                    $sql = "UPDATE  `stock_materials`
                            SET `bags` = ". $newbags ."
                            WHERE `stock_material_id` = ". $ID_SM .";
                            INSERT INTO  `stock_materials_transfers`(`stock_materials_transfers_id`,`machine_from`,`machine_to`,`material_id`,`date_required`,`bags_required`,`bags_approved`,`bags_issued`,`bags_receipt`,`user_id_required`,`user_id_approved`,`user_id_issued`,`user_id_receipt`,`status_transfer`,`remarks_approved`,`remarks_issued`)VALUES(NULL,". $from .",". $to .", ". $material.",". $date .",". $bags .",". $bags .",". $bags .",NULL,".  $_SESSION['Userid'] .",".  $_SESSION['Userid'] .",".  $_SESSION['Userid'] .",NULL,2,NULL,'". $remarks ."');";
				
                    try
                    {   
            			$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                        $stmt = $this->_db->prepare($sql);
                        $stmt->execute();
                        $stmt->closeCursor();
                        echo '<strong>SUCCESS!</strong> The <strong>'. $bags .'</strong>  bags/drumps/pieces of the material:<b> '.$MATERIAL .' - '. $GRADE.' </b>were successfully issued.';
                        return TRUE;
                    } 
                    catch (PDOException $e) {
                        echo '<strong>ERROR</strong> Could not decrease the number of  bags/drumps/pieces of this material or create the transfer from the database. Please try again.<br>'. $e->getMessage(); 
                        return FALSE;
                    } 
                }
                else
                {
                    echo '<strong>ERROR: </strong>There is not enough bags/drumps/pieces of the material:<b> '.$MATERIAL .' - '. $GRADE.' </b>on stock. There are <b>'. $BAGSTOCK .' bags/drumps/pieces</b> and you want to send <b>'. $bags .' bags/drumps/pieces</b>. Please try with a lower number of bags/drumps/pieces.';
                    return FALSE;
                }
            }
            else
            {
                echo '<strong>ERROR: </strong>There is not bags/drumps/pieces of this material on this stock location.';
                return FALSE;
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not issue the raw material. Please try again.<br>'. $e->getMessage();
            return FALSE;
        } 
            

    }
	
	 public function sentReprocess()
    {
        $bags = $to = $from = $material = "";
        
        $bags = trim($_POST["bags"]);
        $bags = stripslashes($bags);
        $bags = htmlspecialchars($bags);
        
		$to = trim($_POST["to"]);
        $to = stripslashes($to);
        $to = htmlspecialchars($to);
		
		$from = trim($_POST["from"]);
        $from = stripslashes($from);
        $from = htmlspecialchars($from);
		
		$material = trim($_POST["material2"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
		
        $date = "NOW()";
        if(!empty($_POST['date']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = "'".$newDateString ." 07:00:00'";
        }
        
        $remarks = trim($_POST["remarks"]);
        $remarks = stripslashes($remarks);
        $remarks = htmlspecialchars($remarks);
		
		//CHECK IF THE BAGS IN STOCK MATERIAL ARE GREATER THAN THE BAGS REQUESTED
        $sql = "SELECT stock_material_id, bags, material_name, material_grade
                FROM stock_materials 
				JOIN materials ON materials.material_id = stock_materials.material_id
                WHERE stock_materials.material_id = ". $material." AND machine_id = ". $from." ;";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->execute();
            if($row = $stmt->fetch())
            {
                $ID_SM = $row['stock_material_id'];
                $BAGSTOCK = $row['bags'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                
                    $newbags = $BAGSTOCK - $bags;
                    //DECREASES THE BAGS FROM THE STOCK MATERIALS CHANGE THE STATUS TRANSFER, BGAS ISSUED, AND USER ISSUED
                    $sql = "INSERT INTO  `stock_materials_transfers`(`stock_materials_transfers_id`,`machine_from`,`machine_to`,`material_id`,`date_required`,`bags_required`,`bags_approved`,`bags_issued`,`bags_receipt`,`user_id_required`,`user_id_approved`,`user_id_issued`,`user_id_receipt`,`status_transfer`,`remarks_approved`,`remarks_issued`)VALUES(NULL,". $from .",". $to .", ". $material.",". $date .",". $bags .",". $bags .",". $bags .",NULL,".  $_SESSION['Userid'] .",".  $_SESSION['Userid'] .",".  $_SESSION['Userid'] .",NULL,2,NULL,'". $remarks ."');";
				
                    try
                    {   
            			$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                        $stmt = $this->_db->prepare($sql);
                        $stmt->execute();
                        $stmt->closeCursor();
                        echo '<strong>SUCCESS!</strong> The <strong>'. $bags .'</strong>  bags/drumps/pieces of the material:<b> '.$MATERIAL .' - '. $GRADE.' </b>were successfully issued.';
                        return TRUE;
                    } 
                    catch (PDOException $e) {
                        echo '<strong>ERROR</strong> Could not decrease the number of  bags/drumps/pieces of this material or create the transfer from the database. Please try again.<br>'. $e->getMessage(); 
                        return FALSE;
                    }
            }
            else
            {
                echo '<strong>ERROR: </strong>There is not bags/drumps/pieces of this material on this stock location.';
                return FALSE;
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not issue the raw material. Please try again.<br>'. $e->getMessage();
            return FALSE;
        } 
            

    }
	
	/**
     * Checks and inserts a new transfer
     *
     * @return boolean  true if can insert  false if not
     */
    public function useConsumables()
    {
        $bags = $material = "";
        
		$material = trim($_POST["material3"]);
        $material = stripslashes($material);
        $material = htmlspecialchars($material);
        
        $bags = trim($_POST["bags2"]);
        $bags = stripslashes($bags);
        $bags = htmlspecialchars($bags);
		
        $date = "NOW()";
        if(!empty($_POST['date3']))
        {
            $myDateTime = DateTime::createFromFormat('d/m/Y', $_POST['date3']);
            $newDateString = $myDateTime->format('Y-m-d');
            $date = "'".$newDateString ." 07:00:00'";
        }
        
        $remarks = trim($_POST["remarks"]);
        $remarks = stripslashes($remarks);
        $remarks = htmlspecialchars($remarks);
        
		 //CHECK IF THE BAGS IN STOCK MATERIAL ARE GREATER THAN THE BAGS REQUESTED
        $sql = "SELECT stock_material_id, bags, material_name, material_grade
                FROM stock_materials 
				JOIN materials ON materials.material_id = stock_materials.material_id
                WHERE stock_materials.material_id = ". $material." AND machine_id = 1;";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->execute();
            if($row = $stmt->fetch())
            {
                $ID_SM = $row['stock_material_id'];
                $BAGSTOCK = $row['bags'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                if($BAGSTOCK >= $bags)
                {
                    $newbags = $BAGSTOCK - $bags;
                    //DECREASES THE BAGS FROM THE STOCK MATERIALS CHANGE THE STATUS TRANSFER, BGAS ISSUED, AND USER ISSUED
                    $sql = "UPDATE  `stock_materials`
                            SET `bags` = ". $newbags ."
                            WHERE `stock_material_id` = ". $ID_SM .";
                            INSERT INTO  `stock_materials_transfers`(`stock_materials_transfers_id`,`machine_from`,`machine_to`,`material_id`,`date_required`,`bags_required`,`bags_approved`,`bags_issued`,`bags_receipt`,`user_id_required`,`user_id_approved`,`user_id_issued`,`user_id_receipt`,`status_transfer`,`remarks_approved`,`remarks_issued`)VALUES(NULL,1,100, ". $material.",". $date .",". $bags .",". $bags .",". $bags .",". $bags .",".  $_SESSION['Userid'] .",".  $_SESSION['Userid'] .",".  $_SESSION['Userid'] .",".  $_SESSION['Userid'] .",3,NULL,'". $remarks ."');";
				
                    try
                    {   
            			$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                        $stmt = $this->_db->prepare($sql);
                        $stmt->execute();
                        $stmt->closeCursor();
                        echo '<strong>SUCCESS!</strong> The <strong>'. $bags .'</strong>  bags/drumps/pieces of the material:<b> '.$MATERIAL .' - '. $GRADE.' </b>were successfully issued and used in the warehouse.';
                        return TRUE;
                    } 
                    catch (PDOException $e) {
                        echo '<strong>ERROR</strong> Could not decrease the number of  bags/drumps/pieces of this material or create the transfer from the database. Please try again.<br>'. $e->getMessage(); 
                        return FALSE;
                    } 
                }
                else
                {
                    echo '<strong>ERROR: </strong>There is not enough bags/drumps/pieces of the material:<b> '.$MATERIAL .' - '. $GRADE.' </b>on stock. There are <b>'. $BAGSTOCK .' bags/drumps/pieces</b> and you want to use <b>'. $bags .' bags/drumps/pieces</b>. Please try with a lower number of bags/drumps/pieces.';
                    return FALSE;
                }
            }
            else
            {
                echo '<strong>ERROR: </strong>There is not  bags/drumps/pieces of this material on this stock location.';
                return FALSE;
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not issue the raw material. Please try again.<br>'. $e->getMessage();
            return FALSE;
        } 
    }
	/**
     * Loads the table of all the stock Receipts of the raw materials
     * This function outputs <tr> tags with stock transfer of raw materials
     * $to is the machine_from. I.e $from is Warehouse (Transfers from the warehouse to Other sections)
     */
    public function stockReceiptsWarehouse($machine)
    {
		 $sql = "SELECT stock_materials_transfers_id, from_table.machine_name AS from_t, to_table.machine_name AS to_t,
                    material_name, material_grade, DATE_FORMAT(`stock_materials_transfers`.`date_required`, '%d/%m/%Y %H:%i') AS date_t, 
                    `stock_materials_transfers`.`bags_required`, `stock_materials_transfers`.`bags_issued`, `stock_materials_transfers`.`bags_receipt`,
                    u_required.username AS urequired ,u_issued.username AS uissued, u_receipt.username AS ureceipt ,`stock_materials_transfers`.`status_transfer`
                FROM stock_materials_transfers 
				JOIN materials ON materials.material_id = stock_materials_transfers.material_id 
                    INNER JOIN users u_required ON stock_materials_transfers.user_id_required = u_required.user_id
                    INNER JOIN users u_issued ON stock_materials_transfers.user_id_issued = u_issued.user_id
                    LEFT JOIN users u_receipt ON stock_materials_transfers.user_id_receipt = u_receipt.user_id
                    INNER JOIN machines from_table ON stock_materials_transfers.machine_from = from_table.machine_id
                    INNER JOIN machines to_table ON stock_materials_transfers.machine_to = to_table.machine_id AND to_table.machine_id =  ". $machine ."
			   WHERE stock_materials_transfers.machine_from <> 100 AND (MONTH(date_required) >= MONTH(CURRENT_DATE())-1 AND YEAR(date_required) = YEAR(CURRENT_DATE()) OR status_transfer = 2) 
               ORDER BY `date_required` DESC, status_transfer, stock_materials_transfers_id DESC;";
		
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['stock_materials_transfers_id'];
                $DATE = $row['date_t'];
                $RECEIVEDBY = $row['ureceipt'];
                if($row['ureceipt'] == null)
                {
                    $RECEIVEDBY = "";
                }
                $ISSUEDBY = $row['uissued'];
                $FROM = $row['from_t'];
                $TO = $row['to_t'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGSIS = $row['bags_issued'];
                $BAGSREC = $row['bags_receipt'];
                if($row['bags_receipt'] == null)
                {
                    $BAGSREC = "";
                }
                $STATUS = "";
                $disabled = '" data-toggle="modal" data-target="#modal1"   onclick="receive(\''. $ID .'\',\''. $DATE .'\',\''. $FROM.'\',\''. $TO .'\',\''. $MATERIAL.'\',\''. $GRADE .'\',\''. $BAGSIS.'\')"';
                if($row['status_transfer']==0)
                {
                    $STATUS = "<p class='text-muted'>Requested</p>";
                }
                else if($row['status_transfer']==1)
                {
                    $STATUS = "<p class='text-warning'>Approved</p>";
                }
                else if($row['status_transfer']==2)
                {
                    $STATUS = "<p class='text-info'>Issued</p>";
                }
                else if($row['status_transfer']==3)
                {
                    $STATUS = "<b class='text-success'> Received </b>";
                    $disabled = ' disabled" ';
                }
                 
                 
                echo '<tr>
                        <td><button class="btn btn-link'. $disabled .'">Receive</button></td>
                        <td>'. $DATE .'</td>
                        <td>'. $FROM .'</td>
                        <td><b>'. $MATERIAL .'</b>&nbsp - &nbsp'. $GRADE .'</td>
                        <td class="text-right">'. number_format((float) $BAGSIS,1,'.',',') .'</td>
                        <td>'. $ISSUEDBY .'</td>
                        <td class="text-right">'. number_format((float) $BAGSREC,1,'.',',') .'</td>
                        <td>'. $RECEIVEDBY .'</td>
                        <td>'. $STATUS .'</td>
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
     * Loads the table of all the stock Receipts of the raw materials
     * This function outputs <tr> tags with stock transfer of raw materials
     * $to is the machine_from. I.e $from is Warehouse (Transfers from the warehouse to Other sections)
     */
    public function stockReceipts($to)
    {
		$sql = "SELECT stock_materials_transfers_id, from_table.machine_name AS from_t, to_table.machine_name AS to_t,
                    material_name, material_grade, DATE_FORMAT(`stock_materials_transfers`.`date_required`, '%Y/%m/%d %H:%i') AS date_t, 
                    `stock_materials_transfers`.`bags_required`, `stock_materials_transfers`.`bags_issued`, `stock_materials_transfers`.`bags_receipt`,
                    u_required.username AS urequired ,u_issued.username AS uissued, u_receipt.username AS ureceipt ,`stock_materials_transfers`.`status_transfer`
                FROM stock_materials_transfers 
				JOIN materials ON materials.material_id = stock_materials_transfers.material_id 
                    INNER JOIN users u_required ON stock_materials_transfers.user_id_required = u_required.user_id
                    INNER JOIN users u_issued ON stock_materials_transfers.user_id_issued = u_issued.user_id
                    LEFT JOIN users u_receipt ON stock_materials_transfers.user_id_receipt = u_receipt.user_id
                    INNER JOIN machines from_table ON stock_materials_transfers.machine_from = from_table.machine_id
                    INNER JOIN machines to_table ON stock_materials_transfers.machine_to = to_table.machine_id AND to_table.location_id =  ". $to ."
				WHERE (( MONTH(date_required) >= MONTH(CURRENT_DATE())-1 AND YEAR(date_required) = YEAR(CURRENT_DATE())) OR status_transfer = 2)
               ORDER BY status_transfer, `date_required` DESC;";
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $ID = $row['stock_materials_transfers_id'];
                $DATE = $row['date_t'];
                $RECEIVEDBY = $row['ureceipt'];
                if($row['ureceipt'] == null)
                {
                    $RECEIVEDBY = "";
                }
                $ISSUEDBY = $row['uissued'];
                $FROM = $row['from_t'];
                $TO = $row['to_t'];
                $REQUESTEDBY = $row['urequired'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $BAGSRE = $row['bags_required'];
                $BAGSIS = $row['bags_issued'];
                $BAGSREC = $row['bags_receipt'];
                if($row['bags_receipt'] == null)
                {
                    $BAGSREC = "";
                }
                $STATUS = "";
                $disabled = '" data-toggle="modal" data-target="#modal1"   onclick="receive(\''. $ID .'\',\''. $DATE .'\',\''. $FROM.'\',\''. $TO .'\',\''. $MATERIAL.'\',\''. $GRADE .'\',\''. $BAGSIS.'\')"';
                if($row['status_transfer']==0)
                {
                    $STATUS = "<p class='text-muted'>Requested</p>";
                }
                else if($row['status_transfer']==1)
                {
                    $STATUS = "<p class='text-warning'>Approved</p>";
                }
                else if($row['status_transfer']==2)
                {
                    $STATUS = "<p class='text-info'>Issued</p>";
                }
                else if($row['status_transfer']==3)
                {
                    $STATUS = "<b class='text-success'> Received </b>";
                    $disabled = ' disabled" ';
                }
                 
                 
                echo '<tr>
                        <td><button class="btn btn-link'. $disabled .'">Receive</button></td>
                        <td>'. $DATE .'</td>
                        <td>'. $FROM .'</td>
                        <td><b>'. $MATERIAL .'</b>&nbsp - &nbsp'. $GRADE .'</td>
                        <td class="text-right">'. number_format((float) $BAGSRE,1,'.',',') .'</td>
                        <td>'. $REQUESTEDBY .'</td>
                        <td class="text-right">'. number_format((float) $BAGSIS,1,'.',',') .'</td>
                        <td>'. $ISSUEDBY .'</td>
                        <td class="text-right">'. number_format((float) $BAGSREC,1,'.',',') .'</td>
                        <td>'. $RECEIVEDBY .'</td>
                        <td>'. $STATUS .'</td>
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
     * Receive stock from one location, checking the actual bags in stock material, if there is not material in that stock location it will create it or if exist changing the status and then reducing it.
     *
     * This function outputs boolean if the transaction was succesful
     */
    public function receive()
    {
        $id = $material = $bags = "";
        
        $id = trim($_POST["id_transfer"]);
        $id = stripslashes($id);
        $id = htmlspecialchars($id);
        
        $bags = trim($_POST["bags"]);
        $bags = stripslashes($bags);
        $bags = htmlspecialchars($bags);
        
        //CHECK THE ACTUAL BAGS IN STOCK MATERIAL 
        $sql = "SELECT stock_material_id, bags, material_name, material_grade, consumables
                FROM stock_materials JOIN materials ON materials.material_id = stock_materials.material_id
                WHERE stock_materials.material_id IN (SELECT material_id FROM stock_materials_transfers WHERE stock_materials_transfers_id = ". $id .") AND
                machine_id IN (SELECT machine_to FROM stock_materials_transfers WHERE stock_materials_transfers_id = ". $id .");";
        try
        {   
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stmt = $this->_db->prepare($sql);
            $stmt->execute();
            if($row = $stmt->fetch())
            {
                $ID_SM = $row['stock_material_id'];
                $BAGSTOCK = $row['bags'];
                $MATERIAL = $row['material_name'];
                $GRADE = $row['material_grade'];
                $newbags = $BAGSTOCK + $bags;
                //INCREASES THE BAGS FROM THE STOCK MATERIALS CHANGE THE STATUS TRANSFER, BAGS RECEIVED, AND USER RECEIVED
				
                	$sql = "UPDATE  `stock_materials`
                        SET `bags` = ". $newbags ."
                        WHERE `stock_material_id` = ". $ID_SM .";
                        UPDATE  `stock_materials_transfers`
                        SET `status_transfer` = 3,
                                `bags_receipt` = ". $bags. ",
                                `user_id_receipt` = ".$_SESSION['Userid']."
                        WHERE `stock_materials_transfers_id` = ". $id .";";
                try
                {   
            		$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                    $stmt = $this->_db->prepare($sql);
                    $stmt->execute();
                    $stmt->closeCursor();
                    echo '<strong>SUCCESS!</strong> The <strong>'. $bags .'</strong> bags/drumps/pieces of the material:<b> '.$MATERIAL .' - '. $GRADE.' </b>were successfully received.';
                    return TRUE;
                } 
                catch (PDOException $e) {
                    echo '<strong>ERROR</strong> Could not increase the number of  bags/drumps/pieces of this material or change the status of the transfer from the database. Please try again.<br>'. $e->getMessage(); 
                    return FALSE;
                } 
            }
            else
            {
				$sql = "SELECT consumables
                FROM materials 
                WHERE material_id IN (SELECT material_id FROM stock_materials_transfers WHERE stock_materials_transfers_id = ". $id .");";
				try
				{   
					$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
					$stmt = $this->_db->prepare($sql);
					$stmt->execute();
					if($row = $stmt->fetch())
					{
						$CONSUMABLE = $row['consumables'];
						
						if($CONSUMABLE == 0)
						{
							//INSERT THE BAGS FROM THE STOCK MATERIALS 
							$sql = "UPDATE  `stock_materials_transfers`
								SET `status_transfer` = 3,
										`bags_receipt` = ". $bags. ",
										`user_id_receipt` = ".$_SESSION['Userid']."
								WHERE `stock_materials_transfers_id` = ". $id .";
								INSERT INTO  `stock_materials`(`stock_material_id`,`material_id`,`machine_id`,`bags`)VALUES(NULL,(SELECT material_id FROM stock_materials_transfers WHERE stock_materials_transfers_id = ". $id ."),(SELECT machine_to FROM stock_materials_transfers WHERE stock_materials_transfers_id = ". $id ."),". $bags. ");";
						}
						else
						{
							$sql = "UPDATE  `stock_materials_transfers`
								SET `status_transfer` = 3,
										`bags_receipt` = ". $bags. ",
										`user_id_receipt` = ".$_SESSION['Userid']."
								WHERE `stock_materials_transfers_id` = ". $id .";";
						}
						
						try
						{   
							$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
							$stmt = $this->_db->prepare($sql);
							$stmt->execute();
							$stmt->closeCursor();
							echo '<strong>SUCCESS!</strong> The <strong>'. $bags .'</strong>  bags/drumps/pieces of the material were successfully received.';
							return TRUE;
						} 
						catch (PDOException $e) {
							echo '<strong>ERROR</strong> Could not increase the number of  bags/drumps/pieces of this material or change the status of the transfer from the database. Please try again.<br>'. $e->getMessage(); 
							return FALSE;
						} 
					}
				}
				catch (PDOException $e) {
					echo '<strong>ERROR</strong> Could not receive the raw material. Please try again.<br>'. $e->getMessage();
					return FALSE;
				} 
                
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e) {
            echo '<strong>ERROR</strong> Could not receive the raw material. Please try again.<br>'. $e->getMessage();
            return FALSE;
        } 
    }
}