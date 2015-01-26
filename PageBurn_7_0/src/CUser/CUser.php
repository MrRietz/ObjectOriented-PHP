<?php

class CUser
{
	private $db  = null;  
	private $acronym = null;  
	
	public function __construct($database)
	{
		$this->db = $database; 
	}
	public function IsAuthenticated()
	{
		$this->acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
		if($this->acronym)
		{
			return true;//"Välkommen $acronym ({$_SESSION['user']->name}) du är nu inloggad"; 
		}
		else
		{
			return false;//"Du är offline."; 
		}
	}
        public function Login($user, $password)
        {
        	$sql = "SELECT acronym, name FROM User WHERE acronym = ? AND password = md5(concat(?, salt))";
	
		$params = array($user, $password);
		$paramsPrint = htmlentities(print_r($params, 1));
		$res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $params);
		if(isset($res[0]))
		{
			$_SESSION['user'] = $res[0]; 
		}
        }
        public function Logout()
        {
        	unset($_SESSION['user']);
        }
        public function GetAcronym()
        {
        	return $this->acronym; 
        }
        public function GetName() 
        {
        	return $_SESSION['user']->name; 
        }	
}
