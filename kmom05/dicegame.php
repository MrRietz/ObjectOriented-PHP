<?php 
/**
 * This is a Pageburn controller.
 *
 */
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__.'/config.php'); 


// Do it and store it all in variables in the Pageburn container.
$pageburn['title'] = "Dicegame 100";

$dicegame = new CDiceGame100(); 


$pageburn['main'] = <<<EOD
<article> 
{$dicegame->PlayGame()}




</article>

EOD;

// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
