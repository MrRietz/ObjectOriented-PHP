 <?php 
/**
 * This is a Pageburn pagecontroller.
 *
 */
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__.'/config.php'); 

if(isset($p)) echo "id='".strip_tags($p)."'"; 
// Do it and store it all in variables in the Anax container.
$pageburn['title'] = "Filmdatabas";

$pageburn['main'] = <<<EOD
<h1> Min Filmdatabas </h1>
Kodexempel för hur man gör sin egen filmdatabas sökbar



{$pageburn['byline']}


EOD;


// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);

