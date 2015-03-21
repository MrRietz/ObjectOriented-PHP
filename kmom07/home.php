<?php 
/**
 * This is a Pageburn controller.
 *
 */
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__.'/config.php'); 


// Define what to include to make the plugin to work
$pageburn['stylesheets'][]        = 'css/slideshow.css';
$pageburn['stylesheets'][] = 'css/gallery.css'; 
//$pageburn['jquery']               = true;
$pageburn['javascript_include'] = array('js/slideshow.js');
define('IMG_PATH', '../img/movies/');
$db = new CDatabase($pageburn['database']); 
$movies = new CMovies($db); 
$blog = new CBlog($db);

$allGenres = $movies->GetAllGenresHome(); 
$res = $blog->getHomePosts(); 
// Do it and store it all in variables in the Pageburn container.
$pageburn['title'] = "Hem";

$pageburn['sidebarTitle'] = "<h2>Senaste News</h2>";

if (isset($res[0])) {
    
    foreach ($res as $content) {
        $blog->sanitizeVariables($content);
        $pageburn['sidebar'] .=  $blog->renderHTML($content);
    }
}


$pageburn['main'] = <<<EOD
<div id="slideshow" class='slideshow' data-host="" data-path="img/me/" data-images='["me_1.jpg", "me_2.jpg", "me_3.jpg","me_2.jpg"]'>
<img src='img/me/me_1.jpg' width='800px' height='200px' alt='Me'/>
</div>

<article>
<h1>Välkommen till RM Rental Movies</h1>

<p>Du har hittat hit, vilken tur du har. Här på RM Rental Movies slipper du både långa nedladdningstider och att streama film.
    Bara på några klick så kommer en film blixtsnabbt ner i din brevlåda. Vi har ett stort utbud på hela 10 filmer och här ska du nog kunna finna vad du vill se.
 Vi har hittat en gammal antikhandlare som sålde ut sina DVD så inom en snar framtid har vi några filmer till. </p>
<div class='genres'>
<h1>Tillgängliga genrer</h1>
{$allGenres}
</div>
<h1>Populäraste film</1> 

<h1>Våra senaste filmer</h1>
{$movies->RenderThreeLAtest()}


</article>

EOD;

// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
