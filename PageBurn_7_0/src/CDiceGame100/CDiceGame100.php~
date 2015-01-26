<?php 
// Start a named session
//session_name('DiceGame100');
//session_start();

class CDiceGame100
{
  private $html;
  private $player; 
  public function __construct()
  {
	    session_name('DiceGame100');
	    session_start();
	    $this->html = null;
	    $this->player = "";  
  }
  public function __destruct() 
  {
    
  }
  private function InitSession()
  {
  		// Create the object or get it from the session
	if(isset($_SESSION['dicehand'])) 
	{
		$this->player  = $_SESSION['dicehand'];
	}
	else 
	{
		$this->player = new CDiceHand(1);
		$_SESSION['dicehand'] = $this->player;	
	}  
  }
  private function DestroySession()
  {
  	  // Unset all of the session variables.
	  $_SESSION = array();
	
	  // If it's desired to kill the session, also delete the session cookie.
	  // Note: This will destroy the session, and not just the session data!
	  if (ini_get("session.use_cookies")) {
	      $params = session_get_cookie_params();
	      setcookie(session_name(), '', time() - 42000,
		  $params["path"], $params["domain"],
		  $params["secure"], $params["httponly"]
	      );
	  }

	  // Finally, destroy the session.
	  session_destroy();
	   header('Location:?');
	  exit;
  }
  private function GameLogic()
  {
  	  // Get the arguments from the query string
	$roll = isset($_GET['roll']) ? true : false;
	$save = isset($_GET['save']) ? true : false;
	$destroy = isset($_GET['destroy']) ? true : false; 
  	if($roll) 
	{
	    $this->player->Roll();  
	}
	else if($save)
        {
            $this->html .= "<p>Då var det sparat!</p>";
            $this->player->SavePoints($this->player->GetRoundTotal()); 
            $this->player->InitRound();
        }
        else if($destroy)
        {
        	$this->DestroySession(); 
        }
        	
	if($this->player->GetCurrentFace() == 1)
	{
	   $this->player->InitRound();
	   $this->html .="<p>Tärningen visade 1 dina poäng nollställs!</p>"; 
	}
	$this->html .= "<p>" .  $this->player->GetRollsAsImageList() ."</p>
	<p>Summan i denna spelrundan är hittills: ".  $this->player->GetRoundTotal()."</p>";
	$sum = $this->player->GetRoundTotal() + $this->player->GetSavedPoints(); 
	$this->html .= "<p>Sparar du nu så är du uppe i " . $sum . " poäng!<p> 
        <p>Dina sparade poäng är: " .  $this->player->GetSavedPoints();
        
    
	$winning = $this->player->GetSavedPoints() + $this->player->GetRoundTotal(); 
	if($winning >= 100) 
	{
	    $this->html = "Grattis du har vunnit spelet nu! 	<p><a href='?destroy'>Tryck för att Starta om spelet</a>.</p>";
	}
  }
  public function PlayGame() 
  {
	//Dicegame 100
	$this->html .= "<h1>Tärningsspelet 100</h1>
	<p>Tärningsspelet 100 är ett enkelt, men roligt, tärningsspel. Det gäller att samla ihop poäng för att komma först till 100. 
	I varje omgång kastar en spelare tärning tills hon väljer att stanna och 
	spara poängen eller tills det dyker upp en 1:a och hon förlorar alla poäng som samlats in i rundan. <p>

	<p><a href='?roll'>Nytt kast</a></p>
	<p><a href='?save'>Spara poängen</a></p>
	<p><a href='?destroy'>Starta om spel</a></p>";

	$this->InitSession();	
	$this->GameLogic(); 
	
	return $this->html; 	
  }
}

