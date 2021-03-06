<?php 
/**
 * This is a Anax pagecontroller.
 *
 */
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 


// Define what to include to make the plugin to work
$anax['stylesheets'][]        = 'css/slideshow.css';
$anax['javascript_include'][] = 'js/slideshow.js';


// Do it and store it all in variables in the Anax container.
$anax['title'] = "Slideshow för att testa JavaScript i Anax";

$anax['main'] = <<<EOD
<div id="slideshow" class='slideshow' data-host="" data-path="img/me/" data-images='["me_1.jpg", "me_2.jpg", "me_3.jpg"]'>
<img src='img/me/me_1.jpg' width='950px' height='200px' alt='Me'/>
</div>

<h1>En slideshow med JavaScript</h1>
<p>Detta är en exempelsida som visar hur Anax fungerar tillsammans med JavaScript.</p>
EOD;


// Finally, leave it all to the rendering phase of Anax.
include(ANAX_THEME_PATH);
