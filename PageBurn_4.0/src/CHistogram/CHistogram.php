<?php

class CHistogram 
{
  private $res;
  public function __construct() 
  {

  }
   public function __destruct() 
  {
    
  }

  public function PrintHistogram($currentRollsList, $max)
  {
  	  $this->PrepareHistogram($currentRollsList);
  	  
	   // Prepare out a textual representation of the histogram
	    $html = "<ol>";
	   for($i = 1; $i <= $max; $i++) 
	    {
	    	    $val = isset($this->res[$i]) ? $this->res[$i] : null;
	    	     $html .= "<li>{$val}</li>";
	    }
	    $html .= "</ol>";
	
	    return $html;	  	  
  }
    private function PrepareHistogram($values) 
  {
	    $this->res = array();
	    foreach($values as $key => $value) 
	    {
	      @$this->res[$value] .= '*'; // Use @ to ignore warning for not initiating variabel, not really nice but powerful.
	    }
	    ksort($this->res);
  }



}
