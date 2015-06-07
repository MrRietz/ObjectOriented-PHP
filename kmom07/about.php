<?php 
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__.'/config.php'); 


$db = new CDatabase($pageburn['database']);
$blog = new CBlog($db);

$pageburn['title'] = "Om RM Rental Movies";         
$pageburn['main'] = <<<EOD
<h2>RM Rental Movies</h2>
Allting började en vanlig dag när jag var ute och gick med hunden. 
EOD;


$res = $blog->getHomePosts(); 

$pageburn['sidebarTitle'] = "Senaste Nyheter";

if (isset($res[0])) {
    
    foreach ($res as $content) {
        $blog->sanitizeVariables($content);
        $pageburn['sidebar'] .=  $blog->renderHTML($content, true);
    }
}



// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
