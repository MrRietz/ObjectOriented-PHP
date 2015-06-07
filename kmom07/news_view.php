<?php

/**
 * This is a Pageburn pagecontroller.
 *
 */
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__ . '/config.php');


$db = new CDatabase($pageburn['database']);
$content = new CContent($db); 
$user = new CUser($db); 

if (isset($_POST['logout'])) {
    $user->Logout();
    header('Location: admin.php');
}
if(!$user->IsAuthenticated()) {
    die('Check: You must login to edit.');
}
$pageburn['sidebarTitle'] = "Tools";
$pageburn['sidebar'] = <<<EOD
    {$content->GetAdminToolbar()}
EOD;

// Do it and store it all in variables in the Anax container.
$pageburn['title'] = "Editera nyheter";

$pageburn['main'] = <<<EOD
<label><h4>Inlägg:</h4> </label> 
<ul>
{$content->renderAvailableContent()}
</ul>



EOD;

// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);

