<?php 
/**
 * This is a Pageburn pagecontroller.
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


if(isset($_POST['login']))
{
        $user->Login($_POST['acronym'], $_POST['password']);
	header('Location: loginController.php'); 
}

// Do it and store it all in variables in the Anax container.
$pageburn['title'] = "Logga in";



$pageburn['main'] = <<<EOD
<h1>{$pageburn['title']}</h1>
<form method=post>
<fieldset>
	<p><em>Du kan logga in med burnie:burnie eller admin:admin.</em></p>
	<p><label>Användare:<br/><input type='text' name='acronym' value=''/></label></p>
	<p><label>Lösenord:<br/><input type='text' name='password' value=''/></label></p>
	<p><input type='submit' name='login' value='Login'/></p>
	<p><a href='movie_logout.php'>Logout</a></p>
	<output><b>{$output}</b></output>
</fieldset>
</form>

EOD;




// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);

