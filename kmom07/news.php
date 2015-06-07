<?php

/**
 * This is a Pageburn pagecontroller.
 *
 */
include(__DIR__ . '/config.php');


// Connect to a MySQL database using PHP PDO
$db = new CDatabase($pageburn['database']);
$blog = new CBlog($db);
$pageburn['sidebarTitle'] = "Välj genre";
$pageburn['sidebar'] = <<<EOD
    {$blog->getGenreList()}
EOD;

$pageburn['title'] = "Nyheter";
$pageburn['debug'] = $db->Dump();
 
$pageburn['main'] = null;
if (isset($_GET['genre'])) {
    $pageburn['title'] = "Nyheter";

    $pageburn['main'] = $blog->getPostsByGenre();

} else if (isset($_GET['slug'])) {
    
    $pageburn['main'] = $blog->getPostBySlug();
    
} else {
    $pageburn['main'] = $blog->getAllPosts();
}



// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
