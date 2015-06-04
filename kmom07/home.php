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



define('IMG_PATH', '../img/movies/');
$db = new CDatabase($pageburn['database']); 
$movies = new CMovies($db); 
$blog = new CBlog($db);

$allGenres = $movies->GetAllGenres(true); 
$res = $blog->getHomePosts(); 
// Do it and store it all in variables in the Pageburn container.
$pageburn['title'] = "Hem";

$pageburn['sidebarTitle'] = "Senaste Nyheter";

if (isset($res[0])) {
    
    foreach ($res as $content) {
        $blog->sanitizeVariables($content);
        $pageburn['sidebar'] .=  $blog->renderHTML($content);
    }
}


$pageburn['main'] = <<<EOD
<article>
<h2>Välkommen till RM Rental Movies</h2>
        
<p>Du har hittat hit, vilken tur du har. Här på RM Rental Movies slipper du både långa nedladdningstider och att streama film.
    Bara på några klick så kommer en film blixtsnabbt ner i din brevlåda. Vi har ett stort utbud på hela 10 filmer och här ska du nog kunna finna vad du vill se.
 Vi har hittat en gammal antikhandlare som sålde ut sina DVD så inom en snar framtid har vi några filmer till. </p>
        
<div id="slideshow" class='slideshow' data-host="" data-path="img/me/" data-images='["me_1.jpg", "me_2.jpg", "me_3.jpg","me_2.jpg"]'>
<img src='img/me/me_1.jpg' width='100%' height='200px' alt='Me'/>
</div>
        
<div class='genres'>
<h3>Tillgängliga genrer</h3>
{$allGenres}
</div>
<h3>Populäraste film</h3> 
<div class="row">
  <div class="col-sm-6 col-md-6">
    <div class="thumbnail">
      <img src="#" alt="...">
      <div class="caption">
        <h3>Thumbnail label</h3>
        <p>...</p>
        <p><a href="#" class="btn btn-primary" role="button">Youtube</a> <a href="#" class="btn btn-default" role="button">IMDB</a></p>
      </div>
    </div>
  </div>
</div>
<h3>Våra senaste filmer</h3>
{$movies->RenderThreeLatest()}


</article>
EOD;

// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
