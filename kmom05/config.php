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
define('PAGEBURN_INSTALL_PATH', __DIR__ . '/../PageBurn_5.0');
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
$pageburn['title_append'] = ' | Robins Sida';


$pageburn['header'] = <<<EOD
<img class ='sitelogo' src='img/pageburn.png' alt='pageburn Logo'/>
<span class='sitetitle'> Me oophp</span> 
<span class='siteslogan'>Min Me-sida i kursen Databaser och Objektorienterad PHP-programmering</span>
EOD;

$pageburn['footer'] = <<<EOD
<footer><span class='sitefooter'>Copyright (c) Mikael Roos (me@mikaelroos.se) | <a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a></span></footer>
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

$pageburn['database']['dsn'] = 'mysql:host=blu-ray.student.bth.se;dbname=rorb09;';
$pageburn['database']['username'] = DB_USER;
$pageburn['database']['password'] = DB_PASSWORD;
$pageburn['database']['driver_options'] = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");


/** The navbar 
 * ********************************************************************** */
//$pageburn['navbar'] = null; // To skip the navbar
$pageburn['navbar'] = array(
    'class' => 'nb-skype',
    'items' => array(
        //this is a menu item
        'hem' => array('text' => 'Hem', 'url' => 'me.php', 'title' => 'Min presentation om mig själv'),
        'redovisning' => array('text' => 'Redovisning', 'url' => 'report.php', 'title' => 'Redovisningar för kursmomenten',
            //lets add the submenu here
            'submenu' => array(
                //this menu item is part of the submenu
                'items' => array('item 1' => array('text' => 'Kmom01', 'url' => 'kmom01.php', 'title' => 'Kmom01'),
                    'item 2' => array('text' => 'Kmom02', 'url' => 'kmom02.php', 'title' => 'Kmom02'),
                    'item 3' => array('text' => 'Kmom03', 'url' => 'kmom03.php', 'title' => 'Kmom03'),
                    'item 4' => array('text' => 'Kmom04', 'url' => 'kmom04.php', 'title' => 'Kmom04'),
                ),
            ),
        ),
<<<<<<< HEAD
        'blog' => array('text' =>  'Blogg', 'url' =>        'viewController.php', 'title' => 'Vy som visar innehållet',
            //lets add the submenu here
                'submenu' => array(
                    //this menu item is part of the submenu
                    'items' => array(
                        'item 1' => array('text' => 'Reset', 'url' => 'resetDBController.php', 'title' => 'Reset DB'),
                        /*'item 2' => array('text' => 'Kmom02', 'url' => 'kmom02.php', 'title' => 'Kmom02'),
                        'item 3' => array('text' => 'Kmom03', 'url' => 'kmom03.php', 'title' => 'Kmom03'),
                        'item 4' => array('text' => 'Kmom04', 'url' => 'kmom04.php', 'title' => 'Kmom04'),*/
                    ),
                ),
        ),
=======
        'view' => array('text' =>  'Blogg', 'url' =>        'viewController.php', 'title' => 'Vy som visar innehållet'),
>>>>>>> kmom05Server
        'login' => array('text' =>  'Login', 'url' =>        'loginController.php', 'title' => 'Logga in för att ändra i databasen'),
        'logout' => array('text' => 'Logout', 'url' =>       'logoutController.php', 'title' => 'Logga ut'),
        'kallkod' => array('text' =>'Källkod', 'url' =>      'source.php', 'title' => 'Se källkoden'),
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
//$anax['stylesheet'] = 'css/style.css';
$pageburn['stylesheets'] = array('css/style.css');
$pageburn['favicon'] = 'favicon.ico';



/**
 * Settings for JavaScript.
 *
 */
$pageburn['modernizr'] = 'js/modernizr.js';
//$pageburn['jquery']     = null; // To disable jQuery
$pageburn['jquery'] = '//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js';
$pageburn['javascript_include'] = array();
//$anax['javascript_include'] = array('js/main.js'); // To add extra javascript files



/**
 * Google analytics.
 *
 */
$pageburn['google_analytics'] = 'UA-22093351-1'; // Set to null to disable google analytics

