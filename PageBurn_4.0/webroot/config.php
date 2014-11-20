<?php
/**
 * Config-file for Pageburn. Change settings here to affect installation.
 *
 */
 
/**
 * Set the error reporting.
 *
 */
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors 
ini_set('output_buffering', 0);   // Do not buffer outputs, write directly
 
 
/**
 * Define Pageburn paths.
 *
 */
define('PAGEBURN_INSTALL_PATH', __DIR__ . '/..');
define('PAGEBURN_THEME_PATH', PAGEBURN_INSTALL_PATH . '/theme/render.php');
 
 
/**
 * Include bootstrapping functions.
 *
 */
include(PAGEBURN_INSTALL_PATH . '/src/bootstrap.php');
 
 
/**
 * Start the session.
 *
 */
session_name(preg_replace('/[^a-z\d]/i', '', __DIR__));
session_start();
 
 
/**
 * Create the pageburn variable.
 *
 */
$pageburn = array();
 

$pageburn['header'] = <<<EOD
<img class='sitelogo' src='img/anax.png' alt='Anax Logo'/>
<span class='sitetitle'>Anax webbtemplate</span>
<span class='siteslogan'>Återanvändbara moduler för webbutveckling med PHP</span>
EOD;

$pageburn['sidebar'] = null; 
$pageburn['footer'] = <<<EOD
<footer><span class='sitefooter'>Copyright (c) Mikael Roos (me@mikaelroos.se) | <a href='https://github.com/mosbth/Anax-base'>Anax på GitHub</a> | <a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a></span></footer>
EOD;
 
/**
 * Site wide settings.
 *
 */
$pageburn['lang']         = 'sv';
$pageburn['title_append'] = ' | Robins sida';

/**
 * Theme related settings.
 *
 */
$pageburn['stylesheets'] = array('css/style.css');
$pageburn['favicon']    = 'favicon.ico';

/**
 * Settings for JavaScript.
 *
 */
$pageburn['modernizr'] = 'js/modernizr.js';
$pageburn['jquery'] = '//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js';

$pageburn['javascript_include'] = array();
//$anax['javascript_include'] = array('js/main.js'); // To add extra javascript files

/**
 * Google analytics.
 *
 */
$pageburn['google_analytics'] = 'UA-22093351-1'; // Set to null to disable google
