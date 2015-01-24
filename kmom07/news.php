<?php

/**
 * This is a Pageburn pagecontroller.
 *
 */
include(__DIR__ . '/config.php');


// Connect to a MySQL database using PHP PDO
$db = new CDatabase($pageburn['database']);
$blog = new CBlog($db);


$pageburn['title'] = "Bloggen";
$pageburn['debug'] = $db->Dump();

$pageburn['main'] = null;

$res = $blog->getPosts();




$pageburn['main'] = null;
if (isset($res[0])) {
    
    foreach ($res as $content) {
        $blog->sanitizeVariables($content);
        if ($content->slug) {
            $pageburn['title'] = "$content->title | " . $pageburn['title'];
        }

        $pageburn['main'] .= <<<EOD
        {$blog->renderHTML($content)}
EOD;
    }
    $pageburn['main'] .= <<<EOD
    <a href='addNewController.php'>Lägg till ny.</a></p>
EOD;
    
} else if ($slug) {
    $pageburn['main'] = "Det fanns inte en sådan bloggpost.";
} else {
    $pageburn['main'] = "Det fanns inga bloggposter.";
}



// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
