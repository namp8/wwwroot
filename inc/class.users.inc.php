<?php

/**
 * Handles user interactions within the users
 *
 * PHP version 5
 *
 * @author Natalia Montañez
 * @copyright 2017 Natalia Montañez
 *
 */
class Users
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
     * Checks credentials and logs in the user
     *
     * @return boolean    TRUE on success and FALSE on failure
     */
    public function accountLogin()
    {
        $username = $password = "";
        
        $username = trim($_POST["username"]);
        $username = stripslashes($username);
        $username = htmlspecialchars($username);
        
        $password = trim($_POST["password"]);
        $password = stripslashes($password);
        $password = htmlspecialchars($password);
        
        $sql = "SELECT `users`.`user_id`,`users`.`username`
                FROM users
                WHERE username=:user
                AND password=MD5(:pass)
                LIMIT 1";
        try
        {
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(':user', $username, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $password, PDO::PARAM_STR);
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $_SESSION['Username'] = htmlentities($_POST['username'], ENT_QUOTES);
                $_SESSION['Userid'] = $row['user_id'];
                $_SESSION['LoggedIn'] = 1;
                return TRUE;
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e) {
            echo '<strong>ERROR</strong><br>'. $e->getMessage(); 
            return FALSE;
        } 
    }
	
	/**
     * Access to other pages
     */
	public function access($search)
	{
		
		
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
            return false; 
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
    
}


?>
