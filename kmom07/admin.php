<?php 
/**
 * This is a Pageburn pagecontroller.
 */
 
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 
$db = new CDatabase($pageburn['database']); 
$user = new CUser($db); 
$movies = new CMovies($db);
if (isset($_POST['login'])) {

    $user->Login($_POST['acronym'], $_POST['password']);
    header('Location: admin.php');
}
if (isset($_POST['logout'])) {

    $user->Logout();
    header('Location: admin.php');
}
if($user->IsAuthenticated())
{
	$output = "Välkommen {$user->GetAcronym()} ({$user->GetName()}) du är nu inloggad"; 
}
else
{
	$output = "Du är offline."; 
}


if($user->IsAuthenticated())
{
    $pageburn['title'] = "Admin";
    $pageburn['main'] = <<<EOD
    <fieldset>
        <div class="alert alert-success" role="alert">{$output} </div>       
    </fieldset>

EOD;
        /** The sidebar
     * ********************************************************************** */
$pageburn['sidebarTitle'] = "Tools";
$pageburn['sidebar'] = <<<EOD
 {$movies->GetAdminToolbar()}
EOD;

} else
{
    $pageburn['sidebarTitle'] = "Tools";
$pageburn['sidebar'] = <<<EOD
          <div class="alert alert-info" role="alert"> {$output} </div>
EOD;
    // Do it and store it all in variables in the Anax container.
    $pageburn['title'] = "Logga in";
    $pageburn['main'] = <<<EOD
    <form method=post>
    <fieldset>
            <p><div class="alert alert-info" role="alert">Du kan logga in med burnie:burnie eller admin:admin.</div></p>
            <p><label>Användare:<br/><input class='form-control' type='text' name='acronym' value=''/></label></p>
            <p><label>Lösenord:<br/><input class='form-control' type='text' name='password' value=''/></label></p>
            <p><input class='btn btn-primary' type='submit' id='login' name='login' value='Login'/></p>
    </fieldset>
    </form>

EOD;

}









// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);

