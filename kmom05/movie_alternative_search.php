 <?php 
/**
 * This is a Pageburn pagecontroller.
 */
 
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 

$db  = new CDatabase($pageburn['database']); 
$searchAlt = new CSearchAlternative($db); 


// Do it and store it all in variables in the Anax container.
$pageburn['title'] = "Visa filmer med sökalternativ kombinerade";



$pageburn['main'] = <<<EOD
<h1>{$pageburn['title']}</h1>

{$searchAlt->RenderHtml()}

 <br>
 <br>
EOD;





// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);

