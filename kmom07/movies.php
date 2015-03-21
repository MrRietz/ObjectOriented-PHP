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

define('IMG_PATH', '../img/movies/');


$db = new CDatabase($pageburn['database']);
$movies = new CMovies($db);  






  
if(isset($_GET['id'])) {
    
    $movieTitle = $movies->getMovieById($_GET['id']); 
    $pageburn['title'] = $movieTitle->title;   
    $pageburn['main'] = <<<EOD
        
    {$movies->RenderSingleMovie($_GET['id'])}
EOD;
    $pageburn['sidebarTitle'] =  "<h2> {$pageburn['title']} </h2>"; 
    $pageburn['sidebar'] = <<<EOD
        
    {$movies->RenderSingleMovieAside($_GET['id'])}
EOD;
    

} else {

    $pageburn['title'] = "Våra Filmer";         
    $pageburn['main'] = <<<EOD

    <h1>{$pageburn['title']}</h1>
    {$movies->RenderMovies()}
EOD;
$blog = new CBlog($db);

$res = $blog->getHomePosts(); 

$pageburn['sidebarTitle'] = "<h2>Senaste News</h2>";

if (isset($res[0])) {
    
    foreach ($res as $content) {
        $blog->sanitizeVariables($content);
        $pageburn['sidebar'] .=  $blog->renderHTML($content);
    }
}
}


// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
