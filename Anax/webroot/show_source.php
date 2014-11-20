 <?php 
/**
 * This is a Anax pagecontroller.
 *
 */
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 


// Add style for csource
$anax['stylesheets'][] = 'css/source.css';


// Create the object to display sourcecode
//$source = new CSource();
$source = new CSource(array('secure_dir' => '..', 'base_dir' => '..'));


// Do it and store it all in variables in the Anax container.
$anax['title'] = "Visa källkod";
 
$anax['header'] = <<<EOD
<img class='sitelogo' src='img/anax.png' alt='Anax Logo'/>
<span class='sitetitle'>Anax webbtemplate</span>
<span class='siteslogan'>Återanvändbara moduler för webbutveckling med PHP</span>
EOD;
$anax['main'] = "<h1>Visa källkod</h1>\n" . $source->View();
$anax['footer'] = <<<EOD
<footer><span class='sitefooter'>Copyright (c) Mikael Roos (me@mikaelroos.se) | <a href='https://github.com/mosbth/Anax-base'>Anax på GitHub</a> | <a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a></span></footer>
EOD;

// Finally, leave it all to the rendering phase of Anax.
include(ANAX_THEME_PATH);

