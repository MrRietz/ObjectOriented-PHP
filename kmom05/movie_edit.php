 <?php 
/**
 * This is a Pageburn pagecontroller.
 *
 */
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 

// Connect to a MySQL database using PHP PDO
$db = new CDatabase($pageburn['database']);


// Get parameters 
$id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
$title  = isset($_POST['title']) ? strip_tags($_POST['title']) : null;
$year   = isset($_POST['year'])  ? strip_tags($_POST['year'])  : null;
$image  = isset($_POST['image']) ? strip_tags($_POST['image']) : null;
$genre  = isset($_POST['genre']) ? $_POST['genre'] : array();
$save   = isset($_POST['save'])  ? true : false;
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;


// Check that incoming parameters are valid
isset($acronym) or die('Check: You must login to edit.');
is_numeric($id) or die('Check: Id must be numeric.');
is_array($genre) or die('Check: Genre must be array.');



// Check if form was submitted
$output = null;
if($save) {
  $sql = '
    UPDATE Movie SET
      title = ?,
      year = ?
    WHERE 
      id = ?
  ';
  $params = array($title, $year, $id);
  $db->ExecuteQuery($sql, $params);
  $output = 'Informationen sparades.';
}


// Select information on the movie
$sql = 'SELECT * FROM Movie WHERE id = ?';
$params = array($id);
$res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);

if(isset($res[0])) {
  $movie = $res[0];
}
else {
  die('Failed: There is no movie with that id');
}


// Do it and store it all in variables in the Anax container.
$pageburn['title'] = "Uppdatera info om film";

$sqlDebug = $db->Dump();

$pageburn['main'] = <<<EOD
<h1>{$pageburn['title']}</h1>

<form method=post>
  <fieldset>
  <legend>Uppdatera info om film</legend>
  <input type='hidden' name='id' value='{$id}'/>
  <p><label>Titel:<br/><input type='text' name='title' value='{$movie->title}'/></label></p>
  <p><label>År:<br/><input type='text' name='year' value='{$movie->year}'/></label></p>
  <p><label>Bild:<br/><input type='text' name='image' value='{$movie->image}'/></label></p>
  <p><input type='submit' name='save' value='Spara'/> <input type='reset' value='Återställ'/></p>
  <p><a href='movie_view_edit.php'>Visa alla</a></p>
  <output>{$output}</output>
  </fieldset>
</form>

<div class=debug>{$sqlDebug}</div>

EOD;



// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);

