<?php 
/**
 * This is a Pageburn pagecontroller.
 *
 */
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__.'/config.php'); 

// Do it and store it all in variables in the Anax container.
$pageburn['title'] = "Redovisning";

$pageburn['main'] = <<<EOD
<h1> Redovisningar </h1>
Klicka på en Undermeny för att komma till en redovisning



{$pageburn['byline']}


EOD;


// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);

