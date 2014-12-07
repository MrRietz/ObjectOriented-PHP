<?php 
/**
 * This is a Anax pagecontroller.
 *
 */
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__.'/config.php'); 
$pageburn['stylesheets'][] = 'css/figure.css'; 
$pageburn['stylesheets'][] = 'css/gallery.css'; 
$pageburn['stylesheets'][] = 'css/breadcrumb.css'; 

define('GALLERY_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'img');
define('GALLERY_BASEURL', '');

$gallery = new CGallery(); 

$gallery->render(); 

$pageburn['title'] = "Ett galleri";
$pageburn['main'] = <<<EOD
<h1>{$pageburn['title']}</h1>

{$gallery->getBreadcrumb()}

{$gallery->getGallery()}

EOD;



// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
