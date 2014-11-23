 <?php 
/**
 * This is a Anax pagecontroller.
 *
 */
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 



// Connect to a MySQL database using PHP PDO
$db = new CDatabase($pageburn['database']);
$page = new CPage($db); 


$content = $page->getCurrentPage();
$page->sanitizeVariables($content); 


// Prepare content and store it all in variables in the Anax container.
$pageburn['title'] = $page->getTitle();
$pageburn['debug'] = $db->Dump();


$pageburn['main'] = <<<EOD
{$page->renderHTML($content)}
EOD;



// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
