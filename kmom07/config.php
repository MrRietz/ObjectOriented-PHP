<?php

/**
  This is the Configuration file for Pageburn. Feel free to change the settings to affect the installation.
 */
/**
  Set the error reporting.
 * ********************************************************************** */
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors 
ini_set('output_buffering', 0);   // Do not buffer outputs, write directly


/**
  Define Pageburn paths.
 * ********************************************************************** */
define('PAGEBURN_INSTALL_PATH', __DIR__ . '/../PageBurn_7_0');
define('PAGEBURN_THEME_PATH', PAGEBURN_INSTALL_PATH . '/theme/render.php');

/**
 * Include bootstrapping functions.
 * ********************************************************************** */
include(PAGEBURN_INSTALL_PATH . '/src/bootstrap.php');
/**
 * Start the session.
 *
 */

session_name(preg_replace('/[:\.\/-_]/', '', __DIR__));
session_start();

/** Create the Pageburn variable. 
 * ********************************************************************** */
$pageburn = array();


/** Site settings */
$pageburn['lang'] = 'sv';
$pageburn['title_append'] = ' | RM Rental Movies';


$pageburn['header'] = null;

$pageburn['sidebarTitle'] = "";
$pageburn['sidebar'] = <<<EOD
EOD;

$pageburn['footer'] = <<<EOD
<div class='well well-sm'>
<footer class='footer'>
   <div class="container"> 
   <div class='text-center'>Copyright (c) RM Rental Movies | 
    <a href='https://github.com/MrRietz/ObjectOriented-PHP/tree/master/PageBurn_5.0'>Pageburn på GitHub</a> | 
    <a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a></span> |
    <a href='admin.php'>Admin</a></div> 
        </div>
</footer>
</div>
EOD;



$pageburn['byline'] = <<<EOD
<footer class="byline">
  <figure class="right"><img src="img/byline_me.jpg" alt="Robin närbild">
    <figcaption>En riktigt glad Robin</figcaption>
  </figure>
  <p>Robin Rietz älskar musik, och spelar gärna gitarr på fritiden. Han studerar på Blekinge Tekniska Högskola. En stor del av hans vänner kallar också honom för Burnie. 
 <p> </p>
     <a href='http://www.facebook.com/robinrietz'><img src='img/facebookicon.png' alt='facebook-icon' title='Robin Rietz på Facebook' width='24' height='24'/></a>
     <a href='http://www.youtube.com/user/skaterboyzero'><img src='img/youtubeicon.png' alt='youtube-icon' title='Robin Rietz på YouTube' width='24' height='24'/></a>
     <a href='http://instagram.com/burniesk8'><img src='img/instagramicon.png' alt='instagram-icon' title='Robin Rietz på Instagram' width='24' height='24'/></a>


</footer>
EOD;

/** Settings for database
 * ********************************************************************** */
define('DB_USER', 'rorb09'); // The database username
define('DB_PASSWORD', 'UZ"D7Vw/'); // The database password

//$pageburn['database']['dsn'] = 'mysql:host=blu-ray.student.bth.se;dbname=rorb09;';
//$pageburn['database']['username'] = DB_USER;
//$pageburn['database']['password'] = DB_PASSWORD;
$pageburn['database']['dsn'] = 'mysql:host=localhost;dbname=Kmom07;';
$pageburn['database']['username'] = 'root';
$pageburn['database']['password'] = '';
$pageburn['database']['driver_options'] = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");


/** The navbar 
 * ********************************************************************** */
//$pageburn['navbar'] = null; // To skip the navbar
$pageburn['navbar'] = array(
    'class' => 'navbar navbar-default',
    'items' => array(
        //this is a menu item
        'hem' => array('text' => 'HEM', 'url' => 'home.php', 'title' => 'Min presentation om mig själv'),
        'filmer' => array('text' => 'FILMER', 'url' => 'movies.php', 'title' => 'Gallery'),
        'nyheter' => array('text' => 'NYHETER', 'url' => 'news.php', 'title' => 'Vy som visar innehållet'),
        'om' => array('text' => 'OM', 'url' => 'about.php', 'title' => 'Om'),
    ),
    'callback' => function($url) {
        if (basename($_SERVER['SCRIPT_FILENAME']) == $url) {
            return true;
        }
    }
);
      
/**
 * Theme related settings.
 * ********************************************************************** */
$pageburn['stylesheets'] = array('css/pageburn.css');
$pageburn['favicon'] = 'favicon.ico';

/**
 * Settings for JavaScript.
 *
 */
$pageburn['modernizr'] = 'js/modernizr.js';
//$pageburn['jquery']     = null; // To disable jQuery
$pageburn['jquery'] = 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js';
$pageburn['javascript_include'] = array('js/pageburn.js');
/**
 * Google analytics.
 */
$pageburn['google_analytics'] = 'UA-22093351-1'; // Set to null to disable google analytics

