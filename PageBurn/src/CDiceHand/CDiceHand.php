<?php

class CDiceHand
{
	private $dices; 
	private $numDices;
	private $sum; 
	private $savedPoints; 
	private $sumRound;
	private $currentFace; 
	private $nrOfRounds; 
	public function __construct($numDices = 5) 
	{
		for($i=0; $i < $numDices; $i++) 
		{
		      $this->dices[] = new CDiceImage();
		}
		$this->numDices = $numDices;
		$this->sum = 0;
		$this->sumRound = 0;
	        $this->currentFace = 0; 
	        $savedPoints = 0; 
	        $nrOfRounds = 0; 
	}
	public function __destruct() 
	{
	}
	public function Roll()
	{
		 $this->sum = 0;
		 $roll = 0; 
		 for($i=0; $i < $this->numDices; $i++) 
		 {
		 	 $roll += $this->dices[$i]->Roll(1);
		 	 $this->currentFace = $roll;
		 	 $this->sum += $roll;
		 	 $this->sumRound += $roll;
		 }
	}
	public function GetTotal() 
	{
	  	   return $this->sum;
	}
	public function GetSavedPoints()
	{
		if($this->savedPoints == 0)
		{
			return "0"; 
		}
		else
		{
			return $this->savedPoints; 
		}
	}
	public function SavePoints($toSave)
	{
		$this->nrOfRounds++; 
		$this->savedPoints += $toSave; 
	}
	
	public function InitRound() 
	{
		$this->sumRound = 0;
    	}
    	public function GetNrOfRounds()
    	{
    		return $this->nrOfRounds; 
    	}
    	public function GetRoundTotal() 
    	{
    		return $this->sumRound;
    	}
    	public function GetCurrentFace()
    	{
    		return $this->currentFace; 
    	}
	public function GetRollsAsImageList()
	{
	  	    $html = "<ul class='dice'>";
		    foreach($this->dices as $dice)
		    {
		      $val = $dice->GetCurrentFace();
		      $html .= "<li class='dice-{$val}'></li>";
		    }
		    $html .= "</ul>";
		    return $html;
	}
}

