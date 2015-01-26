<?php
/**
 * A CDice class to play around with a dice.
 *
 */
class CDice 
{
  private $faces;
  private $currentFace; 
  protected $rolls = array();

  public function __construct($faces=6)
  {
    $this->faces = $faces;
  }
  public function __destruct() 
  {
    
  }

  /** Roll the dice */
  public function Roll($times) 
  {
    $this->rolls = array();
    for($i = 0; $i < $times; $i++) 
    {
    	$this->currentFace = rand(1, $this->faces);
      	$this->rolls[] = $this->currentFace;
    }
    return $this->currentFace;
  }
  
  public function GetFaces() 
  {
  	  return $this->faces; 
  }
  public function GetResults() 
  {
  	  return $this->rolls;
  }
  public function GetCurrentFace() 
  {
  	  return $this->currentFace;
  }
   public function GetRollsAsSerie() 
   {
    $html = null;
    foreach($this->rolls as $val) 
    {
      $html .= "{$val}, ";
    }
    return substr($html, 0, strlen($html) - 2);
  }

  /** Get the total from the last roll(s).*/
  public function GetTotal() 
  {
  	  return array_sum($this->rolls);
  }
  public function GetAverage()
  {
    return round(array_sum($this->rolls) / count($this->rolls), 1);
  }

}
