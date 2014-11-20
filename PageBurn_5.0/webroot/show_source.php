 <?php 
/**
 * This is a Pageburn pagecontroller.
 *
 */
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 


// Add style for csource
$pageburn['stylesheets'][] = 'css/source.css';


// Create the object to display sourcecode
//$source = new CSource();
$source = new CSource(array('secure_dir' => '..', 'base_dir' => '..'));


// Do it and store it all in variables in the Anax container.
$pageburn['title'] = "Visa källkod";
 
$pageburn['main'] = "<h1>Visa källkod</h1>\n" . $source->View();


// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);

