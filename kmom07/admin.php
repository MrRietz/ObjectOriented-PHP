<?php 
/**
 * This is a Pageburn pagecontroller.
 */
 
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 

$pageburn['javascript_include'] = array('js/message.js');
$user = new CUser(new CDatabase($pageburn['database'])); 



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
    <h1>{$pageburn['title']}</h1>
    
    <fieldset>
        <output><p> {$output} </p></output>      
        <form method=post>
        <p><input type='submit' name='logout' value='Logga ut'/></p>
        </form>
    </fieldset>

EOD;
        /** The sidebar
     * ********************************************************************** */
    $pageburn['sidebarTitle'] = "<h2>Min filmdatabas</h2>";
    $pageburn['sidenav'] = array(
        'class' => 'sidebarNav',
        'items' => array(
            //this is a menu item
            'All' => array(
                'text' => 'Alla filmer',
                'url' => 'movie_connect.php',
                'title' => 'Alla filmer'),
            'Titlesearch' => array(
                'text' => 'Sök på titel',
                'url' => 'movie_search_title.php',
                'title' => 'Movie title'
            ),
            'Yearsearch' => array(
                'text' => 'Sök på år',
                'url' => 'movie_search_by_year.php',
                'title' => 'Year'
            ),
            'Genresearch' => array(
                'text' => 'Sök på genre',
                'url' => 'movie_search_genre.php',
                'title' => 'Genre'
            ),
        ),
    'callback' => function($url) {
        if (basename($_SERVER['SCRIPT_FILENAME']) == $url) {
            return true;
        }
        }
    );
} else
{
    
    // Do it and store it all in variables in the Anax container.
    $pageburn['title'] = "Logga in";
    $pageburn['main'] = <<<EOD
    <h1>{$pageburn['title']}</h1>
    <form method=post>
    <fieldset>
            <p><em><div id='message'>Du kan logga in med burnie:burnie eller admin:admin.<div></em></p>
            <p><label>Användare:<br/><input type='text' name='acronym' value=''/></label></p>
            <p><label>Lösenord:<br/><input type='text' name='password' value=''/></label></p>
            <output><p> {$output} </p></output>     
            <p><input type='submit' id='login' name='login' value='Login'/></p>
    </fieldset>
    </form>

EOD;

}









// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);

