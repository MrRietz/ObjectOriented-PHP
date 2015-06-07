<?php 
/**
 * This is a Pageburn controller.
 *
 */
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__.'/config.php'); 


// Define what to include to make the plugin to work
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
        $pageburn['sidebar'] .=  $blog->renderHTML($content, true);
    }
}

$latestMovies = $movies->RenderThreeLatest(); 
$imgPaths = array(IMG_PATH . $latestMovies[0]->image1, IMG_PATH . $latestMovies[1]->image1, IMG_PATH . $latestMovies[2]->image1); 
$pageburn['main'] = <<<EOD
<article>
<h2>Välkommen till RM Rental Movies</h2>
        
<p>Du har hittat hit, vilken tur du har. Här på RM Rental Movies slipper du både långa nedladdningstider och att streama film.
    Bara på några klick så kommer en film blixtsnabbt ner i din brevlåda. Vi har ett stort utbud på hela 10 filmer och här ska du nog kunna finna vad du vill se.
 Vi har hittat en gammal antikhandlare som sålde ut sina DVD så inom en snar framtid har vi några filmer till. </p>
 
   <h3>Våra senaste filmer</h3>

<div id='carousel' class='carousel slide' data-ride='carousel'>
          <!-- Indicators -->
          <ol class='carousel-indicators'>
            <li data-target='#carousel' data-slide-to='0' class='active'></li>
            <li data-target='#carousel' data-slide-to='1'></li>
            <li data-target='#carousel' data-slide-to='2'></li>
          </ol>

          <!-- Wrapper for slides -->
          <div class='carousel-inner' role='listbox'>
            <div class='item active'>
             <a href='movies.php?id={$latestMovies[0]->id}'>
              <img src='img.php?src={$imgPaths[0]}'/>
              <div class='carousel-caption'>
                <h3>{$latestMovies[0]->title}</h3></a>
                <a href='{$latestMovies[0]->imdblink}' class='btn btn-default' target='_blank' role='button'>IMDB</a>
              </div>
               
            </div>
            <div class='item'>
             <a href='movies.php?id={$latestMovies[1]->id}'>
              <img src='img.php?src={$imgPaths[0]}'/>
              <div class='carousel-caption'>
                <h3>{$latestMovies[1]->title}</h3></a>
                <a href='{$latestMovies[1]->imdblink}' class='btn btn-default' target='_blank' role='button'>IMDB</a>
              </div>
            </div>
            <div class='item'>
             <a href='movies.php?id={$latestMovies[2]->id}'>
              <img src='img.php?src={$imgPaths[0]}'/>
              <div class='carousel-caption'>
                <h3>{$latestMovies[2]->title}</h3></a>
                <a href='{$latestMovies[2]->imdblink}' class='btn btn-default' target='_blank' role='button'>IMDB</a>
              </div>
            </div>
          </div>

          <!-- Controls -->
          <a class='left carousel-control' href='#carousel' role='button' data-slide='prev'>
            <span class='glyphicon glyphicon-chevron-left' aria-hidden='true'></span>
            <span class='sr-only'>Previous</span>
          </a>
          <a class='right carousel-control' href='#carousel' role='button' data-slide='next'>
            <span class='glyphicon glyphicon-chevron-right' aria-hidden='true'></span>
            <span class='sr-only'>Next</span>
          </a>
</div>
        
<div class='genres'>
<h3>Tillgängliga genrer</h3>
{$allGenres}
</div>
<div class='row'>
  <div class="col-sm-6 col-md-6">
<h3>Populäraste film</h3> 


    <div class="thumbnail">
      <img src="img.php?src=../img/movies/hobbit.jpg">
      <div class="caption text-center">
        <h3>The Hobbit: The Desolation of Smaug</h3>
        {$movies->getMovieModal('hobbit','The Hobbit: The Desolation of Smaug','https://www.youtube.com/embed/OPVWy1tFXuc')} <a href="http://www.imdb.com/title/tt1170358/?ref_=nv_sr_2" class="btn btn-default" role="button">IMDB</a>
      </div>
    </div>

</div>
  <div class="col-sm-6 col-md-6">
<h3>Senast hyrda film</h3> 
    <div class="thumbnail">
      <img src="img.php?src=../img/movies/godfather.jpg">
      <div class="caption text-center">
        <h3>The Godfather</h3>
        {$movies->getMovieModal('godfather','The Godfather','https://www.youtube.com/embed/sY1S34973zA')} <a href="http://www.imdb.com/title/tt0068646/" class="btn btn-default" role="button">IMDB</a>
      </div>
    </div>

</div>
</div>

</article>
EOD;

// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
