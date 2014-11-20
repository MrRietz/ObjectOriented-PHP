<?php 
/**
 * This is a Pageburn controller.
 *
 */
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__.'/config.php'); 
include(__DIR__.'/simple_class.php');

// Start a named session
session_name('oophp20guiden');
session_start();

if(isset($_GET['destroy'])) {
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
  echo "Sessionen raderas, <a href='?'>starta om spelet</a>";
  exit;
}



// Create a object of the classes
$obj = new SimpleClass();
$dice = new CDiceImage();
$histogram = new CHistogram(); 

// Use the class
$html = null; 
$msg = null; 
// Use the class
$html .= "<p> The value is: {$obj->DisplayVar()}
</p>";
 
// Change the state of the object and use it again
$html .= "The value should now be 2 = 

{$obj->DisplayVar()} <p> </p>";



// här kastar vi tärning
$html .= "
<h1>Kasta tärning</h1>
<p>Detta är en exempelsida som visar hur Anax fungerar tillsammans med återanvändbara moduler.</p>
<p>Hur många kast vill du göra, <a href='?roll=1'>1 kast</a>, <a href='?roll=3'>3 kast</a> eller <a href='?roll=6'>6 kast</a>? </p>";


$roll = isset($_GET['roll']) && is_numeric($_GET['roll']) ? $_GET['roll'] : 0;
if($roll) 
{
	$dice->Roll($roll);

  $html .= "<p>Du gjorde {$roll} kast och fick följande resultat: " . $dice->GetRollsAsImageList() . "</p>";   
  

  $html .= "<p>Totalt fick du " . $dice->GetTotal() . " poäng på dina kast.</p>";
  
    $html .= "<p>Snittvärdet är: " . $dice->GetAverage() .".</p>";
  
  $html .= "<p>Tärningen hade ". $dice->GetFaces() . " sidor.</p>";
  
  $html .= "<p>Enligt histogram: </p> {$histogram->PrintHistogram($dice->GetResults(), $dice->GetFaces())}"; 
}

//21 game
$html .= "<h1>Kasta två tärningar och försök komma så nära 21 som möjligt</h1>
<p><a href='?init'>Starta en ny runda</a>.</p>
<p><a href='?roll1'>Gör ett nytt kast</a>.</p>
<p><a href='?destroy'>Förstör sessionen</a>.</p>";


// Get the arguments from the query string
$roll1 = isset($_GET['roll1']) ? true : false;
$init = isset($_GET['init']) ? true : false;

// Create the object or get it from the session
if(isset($_SESSION['dicehand'])) 
{
  $msg =  "<i>Objektet finns redan i sessionen</i>";
  $hand = $_SESSION['dicehand'];
}
else 
{
  $msg =  "<i>Objektet finns inte i sessionen, skapar nytt objekt och lagrar det i sessionen</i>";
  $hand = new CDiceHand(2);
  $_SESSION['dicehand'] = $hand;
}



if($roll1) 
{
  $hand->Roll();
}
else if($init) {
  $hand->InitRound();
}
  
  $html .= "<p>" . $hand->GetRollsAsImageList() ."</p>
  <p>Summan av alla tärningsslag är: " . $hand->GetTotal() . "</p>
  <p>Summan i denna spelrundan är hittills: ". $hand->GetRoundTotal()."</p>";
// Do it and store it all in variables in the Pageburn container.
$pageburn['title'] = "Kom igång med objektorienterad PHP";


$pageburn['main'] = <<<EOD
<article>
Såhär ska det se ut:
{$html}
<br>
{$msg} 

{$pageburn['byline']}

</article>

EOD;

// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
