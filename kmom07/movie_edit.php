<?php 
/**
 * This is a Pageburn pagecontroller.
 *
 */
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 

// Connect to a MySQL database using PHP PDO
$db = new CDatabase($pageburn['database']);
$fileUpload = new CFileUpload(); 
$movies = new CMovies($db); 
$user = new CUser($db); 

if (isset($_POST['logout'])) {
    $user->Logout();
    header('Location: admin.php');
}
if(!$user->IsAuthenticated()) {
    die('Check: You must login to edit.');
}



// Do it and store it all in variables in the Anax container.
$pageburn['title'] = "Editera film";

$sqlDebug = $db->Dump();
$pageburn['sidebarTitle'] = "Tools";
$pageburn['sidebar'] = <<<EOD
    {$movies->GetAdminToolbar()}
EOD;

if(isset($_GET['id'])) {

$pageburn['main'] = <<<EOD

{$movies->GetEditForm($_GET['id'], $movies->updateContent($_GET['id']))}

EOD;

} else {
    $pageburn['main'] = <<<EOD
<div class="alert alert-info" role="alert">Ingen film att editera.</div>
EOD;

}


// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
