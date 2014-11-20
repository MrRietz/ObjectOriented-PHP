<?php 
/**
 * This is a Pageburn pagecontroller.
 *
 */
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 

$user = new CUser(new CDatabase($pageburn['database'])); 
if($user->IsAuthenticated())
{
	$output = "Välkommen {$user->GetAcronym()} ({$user->GetName()}) du är nu inloggad"; 
}
else
{
	$output = "Du är offline."; 
}



// Logout the user
if(isset($_POST['logout'])) 
{
  $user->Logout(); 
  header('Location: movie_logout.php');
}



// Do it and store it all in variables in the Anax container.
$pageburn['title'] = "Logout";

$pageburn['main'] = <<<EOD
<h1>{$pageburn['title']}</h1>

<form method=post>
  <fieldset>
  <legend>Login</legend>
  <p><input type='submit' name='logout' value='Logout'/></p>
  <p><a href='movie_login.php'>Login</a></p>
  <output><b>{$output}</b></output>
  </fieldset>
</form>

EOD;



// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
