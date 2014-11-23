<?php

/**
 * This is a Pageburn pagecontroller.
 *
 */
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__ . '/config.php');


$db = new CDatabase($pageburn['database']);


$content = new CContent($db); 

// Do it and store it all in variables in the Anax container.
$pageburn['title'] = "Visa allt innehåll";

$pageburn['main'] = <<<EOD
<h1>{$pageburn['title']}</h1>

<p>Här är en lista på allt innehåll i databasen.</p>

<ul>
{$content->renderAvailableContent()}
</ul>

<p><a href='blogController.php'>Visa alla bloggposter.</a> <a href='addNewController.php'>Lägg till ny.</a></p>


EOD;

// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);

