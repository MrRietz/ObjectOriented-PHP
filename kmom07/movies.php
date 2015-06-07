<?php 
/**
 * This is a Anax pagecontroller.
 *
 */
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__.'/config.php'); 

define('IMG_PATH', '../img/movies/');


$db = new CDatabase($pageburn['database']);
$movies = new CMovies($db);  
 
if(isset($_GET['id'])) {
    
    $movie = $movies->getMovieById($_GET['id']); 
    $pageburn['title'] = $movie->title . " (" . $movie->year . ")" ;   
    $pageburn['main'] = <<<EOD
        
    {$movies->RenderSingleMovie($movie)}
EOD;
    $pageburn['sidebarTitle'] =  "Om Filmen"; 
    $pageburn['sidebar'] = <<<EOD
        
    {$movies->RenderSingleMovieAside($_GET['id'])}
EOD;
    

} else {

    $pageburn['title'] = "Filmer";         
    $pageburn['main'] = <<<EOD
    {$movies->RenderMovies()}
EOD;
$blog = new CBlog($db);

$res = $blog->getHomePosts(); 

$pageburn['sidebarTitle'] = "Senaste Nyheter";

if (isset($res[0])) {
    
    foreach ($res as $content) {
        $blog->sanitizeVariables($content);
        $pageburn['sidebar'] .=  $blog->renderHTML($content, true);
    }
}
}


// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
