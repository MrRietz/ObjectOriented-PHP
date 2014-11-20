<?php  
/**
This is the Configuration file for Pageburn. Feel free to change the settings to affect the installation. 
*/

/**
 Set the error reporting.
************************************************************************/
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors 
ini_set('output_buffering', 0);   // Do not buffer outputs, write directly


/**
 Define Pageburn paths.
************************************************************************/
define('PAGEBURN_INSTALL_PATH', __DIR__ . '/../PageBurn_4.0');
define('PAGEBURN_THEME_PATH', PAGEBURN_INSTALL_PATH . '/theme/render.php');

/**
 * Include bootstrapping functions.
************************************************************************/
include(PAGEBURN_INSTALL_PATH . '/src/bootstrap.php');
/**
 * Start the session.
 *
 */
session_name(preg_replace('/[:\.\/-_]/', '', __DIR__));
session_start();


/** Create the Pageburn variable. 
************************************************************************/
$pageburn = array(); 


/** Site settings*/
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
************************************************************************/
$pageburn['database']['dsn']            = 'mysql:host=blu-ray.student.bth.se;dbname=rorb09;';
$pageburn['database']['username']       = 'rorb09';
$pageburn['database']['password']       = 'UZ"D7Vw/';
$pageburn['database']['driver_options'] = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");

/** The sidebar
************************************************************************/
$pageburn['sidebarTitle'] = "<h2>Min filmdatabas</h2>"; 
$pageburn['sidebar'] =  array(
  'class' => 'sidebarNav', 
  'items' => array(
     //this is a menu item
     	    'All'  => array(
		    'text' => 'Alla filmer',  
		    'url'=>'movie_connect.php',  
		    'title'=> 'Alla filmer'),
    	   'Titlesearch' => array(
    	  	  	  'text' => 'Sök på titel',  
    	  	  	  'url'=>'movie_search_title.php',  
    	  	  	  'title'=> 'Movie title'
    	  	  	    ),
    	  'Yearsearch' => array(
    	  	  	'text' => 'Sök på år',  
			'url'=>'movie_search_by_year.php',  
			'title'=> 'Year'
    	  	  	    ),
    	  'Genresearch' => array(
    	  	  	  'text' => 'Sök på genre',  
    	  	  	  'url'=>'movie_search_genre.php',  
    	  	  	  'title'=> 'Genre'
    	  	  	    ),
    	  'Sort' => array(
    	  	  	  'text' => 'Sortera innehåll',  
    	  	  	  'url'=>'movie_order_by.php',  
    	  	  	  'title'=> 'Sort'
    	  	  	    ),
    	  'Pages' => array(
    	  	  	  'text' => 'Paginering',  
    	  	  	  'url'=>'movie_pages.php',  
    	  	  	  'title'=> 'Pages'
    	  	  	    ),
    	  'Login' => array(
    	  	  	  'text' => 'Logga in',  
    	  	  	  'url'=>'movie_login.php',  
    	  	  	  'title'=> 'login'
    	  	  	    ),
    	   'Logout' => array(
    	  	  	  'text' => 'Logga ut',  
    	  	  	  'url'=>'movie_logout.php',  
    	  	  	  'title'=> 'logout'
    	  	  	    ),
    	  'Update' => array(
    	  	  	  'text' => 'Uppdatera',  
    	  	  	  'url'=>'movie_view_edit.php',  
    	  	  	  'title'=> 'Update'
    	  	  	    ),
    	  'Create' => array(
    	  	  	  'text' => 'Skapa ny',  
    	  	  	  'url'=>'movie_create.php',  
    	  	  	  'title'=> 'Create'
    	  	  	    ),
    	  'Remove' => array(
    	  	  	  'text' => 'Ta bort',  
    	  	  	  'url'=>'movie_view_remove.php',  
    	  	  	  'title'=> 'Remove'
    	  	  	    ),
    	  'Altsearch' => array(
    	  	  	  'text' => 'Multisök',  
    	  	  	  'url'=>'movie_alternative_search.php',  
    	  	  	  'title'=> 'Altsearch'
    	  	  	    ),
    	  	  	),
  'callback' => function($url) {
    if(basename($_SERVER['SCRIPT_FILENAME']) == $url) {
      return true;
    }
  }
);
/** The navbar 
************************************************************************/
//$pageburn['navbar'] = null; // To skip the navbar
$pageburn['navbar'] = array(
  'class' => 'nb-skype', 
  'items' => array(
     //this is a menu item
    'hem'         => array(
    	    'text'=>'Hem',         
    	    'url'=>'me.php',         
    	    'title' => 'Min presentation om mig själv'),
    'redovisning' => array(
    	    'text'=>'Redovisning', 
    	    'url'=>'report.php', 	    
    	    'title' => 'Redovisningar för kursmomenten',
    	   //lets add the submenu here
    	  'submenu' => array(
    	      'items' => array(
    	  	  //this menu item is part of the submenu
    	  	  'item 1' => array(
    	  	  	  'text' => 'Kmom01',  
    	  	  	  'url'=>'kmom01.php',  
    	  	  	  'title'=> 'Kmom01'
    	  	  	  ),
    	  	   //this menu item is part of the submenu
    	  	  'item 2' => array(
    	  	  	  'text' => 'Kmom02',  
    	  	  	  'url'=>'kmom02.php',  
    	  	  	  'title'=> 'Kmom02'
    	  	  	  ),
    	  	    //this menu item is part of the submenu
    	  	  'item 3' => array(
    	  	  	  'text' => 'Kmom03',  
    	  	  	  'url'=>'kmom03.php',  
    	  	  	  'title'=> 'Kmom03'
    	  	  	  ),
    	  	      //this menu item is part of the submenu
    	  	  'item 4' => array(
    	  	  	  'text' => 'Kmom04',  
    	  	  	  'url'=>'kmom04.php',  
    	  	  	  'title'=> 'Kmom04'
    	  	  	  ),
    	  	  ),
    	       ),
    	  ),
     'Dice'     => array(
    	    'text'=>'Dicegame 100',
    	    'url'=>'dicegame.php',      
    	    'title' => 'Dicegame 100'
    ),	
    'Filmdatabas' => array(
    	    'text'=>'Min Filmdatabas', 
    	    'url'=>'movie_connect.php', 	    
    	    'title' => 'Min Filmdatabas',
    	   //lets add the submenu here
    	  'submenu' => array(
    	      'items' => array(
    	  	  //this menu item is part of the submenu
    	  	  'item 1' => array(
    	  	  	  'text' => 'Alla filmer',  
    	  	  	  'url'=>'movie_connect.php',  
    	  	  	  'title'=> 'Alla filmer'
    	  	  	  ),
    	  	    //this menu item is part of the submenu
    	  	  'item 2' => array(
    	  	  	  'text' => 'Sök på titel',  
    	  	  	  'url'=>'movie_search_title.php',  
    	  	  	  'title'=> 'Movie title'
    	  	  	    ),
    	  	  'item 3' => array(
    	  	  	'text' => 'Sök på år',  
			'url'=>'movie_search_by_year.php',  
			'title'=> 'Year'
    	  	  	    ),
    	  	  'item 4' => array(
    	  	  	  'text' => 'Sök på genre',  
    	  	  	  'url'=>'movie_search_genre.php',  
    	  	  	  'title'=> 'Genre'
    	  	  	    ),
    	  	  'item 5' => array(
    	  	  	  'text' => 'Sortera innehåll',  
    	  	  	  'url'=>'movie_order_by.php',  
    	  	  	  'title'=> 'Sort'
    	  	  	    ),
    	  	   'item 6' => array(
    	  	  	  'text' => 'Paginering',  
    	  	  	  'url'=>'movie_pages.php',  
    	  	  	  'title'=> 'Pages'
    	  	  	    ),
    	  	   'item 7' => array(
    	  	  	  'text' => 'Logga in',  
    	  	  	  'url'=>'movie_login.php',  
    	  	  	  'title'=> 'login'
    	  	  	    ),
    	  	   'item 8' => array(
    	  	  	  'text' => 'Logga ut',  
    	  	  	  'url'=>'movie_logout.php',  
    	  	  	  'title'=> 'logout'
    	  	  	    ),
    	  	     'item 9' => array(
    	  	  	  'text' => 'Uppdatera',  
    	  	  	  'url'=>'movie_view_edit.php',  
    	  	  	  'title'=> 'Update'
    	  	  	    ),
    	  	        'item 10' => array(
    	  	  	  'text' => 'Skapa ny',  
    	  	  	  'url'=>'movie_create.php',  
    	  	  	  'title'=> 'Create'
    	  	  	    ),
    	  	  	'item 11' => array(
    	  	  	  'text' => 'Ta bort',  
    	  	  	  'url'=>'movie_view_remove.php',  
    	  	  	  'title'=> 'Remove'
    	  	  	    ),
    	  	  	'item 12' => array(
    	  	  	  'text' => 'Multisök',  
    	  	  	  'url'=>'movie_alternative_search.php',  
    	  	  	  'title'=> 'Altsearch'
    	  	  	    ),
    	  	  
    	  	  ),
    	       ),
    ),
    'kallkod'     => array(
    	    'text'=>'Källkod',
    	    'url'=>'source.php',      
    	    'title' => 'Se källkoden'
    ),
  ),
  'callback' => function($url) {
    if(basename($_SERVER['SCRIPT_FILENAME']) == $url) {
      return true;
    }
  }
);



/**
 * Theme related settings.
************************************************************************/
//$anax['stylesheet'] = 'css/style.css';
$pageburn['stylesheets'] = array('css/style.css');
$pageburn['favicon']    = 'favicon.ico';



/**
 * Settings for JavaScript.
 *
 */
$pageburn['modernizr']  = 'js/modernizr.js';
//$pageburn['jquery']     = null; // To disable jQuery
$pageburn['jquery'] = '//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js';
$pageburn['javascript_include'] = array();
//$anax['javascript_include'] = array('js/main.js'); // To add extra javascript files



/**
 * Google analytics.
 *
 */
$pageburn['google_analytics'] = 'UA-22093351-1'; // Set to null to disable google analytics

