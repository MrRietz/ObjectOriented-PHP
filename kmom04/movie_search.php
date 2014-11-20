<?php 
/**
This is a Pageburn pagecontroller.

 */
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 


// Do it and store it all in variables in the Pageburn container.
$pageburn['title'] = "Här kan du söka i databasen";

$pageburn['main'] = <<<EOD
<h1>{$pageburn['title']}</h1>
<a href="movie_search_title.php"> Sök på titel </a>
EOD;




// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
