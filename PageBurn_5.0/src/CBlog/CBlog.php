<?php

class CBlog
{
    
    private $db = null; 
    
	
	
	public function __construct($database)
	{
		$this->db = $database; 
	}
	
}
