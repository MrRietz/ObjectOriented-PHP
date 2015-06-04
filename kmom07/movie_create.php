<?php 
/**
 * This is a Pageburn pagecontroller.
 *
 */
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 

// Connect to a MySQL database using PHP PDO
$db = new CDatabase($pageburn['database']);
$fileUpload = new CFileUpload(); 
$movies = new CMovies($db); 

$movies->insertContent($fileUpload); 

// Do it and store it all in variables in the Anax container.
$pageburn['title'] = "Skapa ny film";

$sqlDebug = $db->Dump();

$pageburn['main'] = <<<EOD

<form method=post enctype="multipart/form-data">
  <fieldset>
  <div class='col-xs-12 col-sm-12 col-md-6 col-lg-4'>
    <label class='control-label'>Titel: </label><input class='form-control' type='text'     name='title'/>
    <label class='control-label'>Regissör: </label><input class='form-control' type='text'  name='director'/>
    <label class='control-label'>Längd i minuter: </label><input class='form-control' type='number'   name='length'/>
    <label class='control-label'>Årtal: </label><input class='form-control' type='number'   name='year'/>
    <label class='control-label'>Subs: </label><input class='form-control' type='text'      name='subtext'/>
    <label class='control-label'>Språk: </label><input class='form-control' type='text'     name='speech'/>
    <label class='control-label'>Pris: </label><input class='form-control' type='number'    name='price'/>
    <label class='control-label'>Youtube: </label><input class='form-control' type='url'    name='youtubelink'/>
    <label class='control-label'>IMDB: </label><input class='form-control' type='url'       name='imdblink'/>      
 </div>
 <div class='col-xs-12 col-sm-12 col-md-6 col-lg-4'>
    <label class='control-label'>Handling: </label><textarea rows='6' cols='50' class='form-control' name='plot'/></textarea>
    <label class='control-label'>Huvudbild 1: </label><input class='form-control' type="file" name="image" id="uploadfile">
    <label class='control-label'>Huvudbild 2: </label><input class='form-control' type="file" name="image1" id="uploadfile">
    <label class='control-label'>Details Image: </label><input class='form-control' type="file" name="asideImg" id="uploadfile">
    <input class='btn btn-primary' type='submit' name='create' value='Skapa'/>
  </div>
  </fieldset>
</form>

EOD;



// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
