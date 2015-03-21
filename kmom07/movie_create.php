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



// Get parameters 
$title  = isset($_POST['title']) ? strip_tags($_POST['title']) : null;
$title  = isset($_POST['director']) ? strip_tags($_POST['director']) : null;
$title  = isset($_POST['year']) ? strip_tags($_POST['year']) : null;
$title  = isset($_POST['image']) ? strip_tags($_POST['image']) : null;
$title  = isset($_POST['subtext']) ? strip_tags($_POST['title']) : null;
$title  = isset($_POST['title']) ? strip_tags($_POST['title']) : null;
$title  = isset($_POST['title']) ? strip_tags($_POST['title']) : null;
$create = isset($_POST['create'])  ? true : false;
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;

// Check that incoming parameters are valid
isset($acronym) or die('Check: You must login to edit.');

// Check if form was submitted
if($create) {
  $sql = 'INSERT INTO Movie (title, director, year, image, subtext, speech, asideImg, price, published, created, updated) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), NOW())';

  $db->ExecuteQuery($sql);
  $db->SaveDebug();
  
  $fileUpload->uploadFile('image');
  $fileUpload->uploadFile('asideImg');
  //header('Location: movie_edit.php?id=' . $db->LastInsertId());
  exit;
}



// Do it and store it all in variables in the Anax container.
$pageburn['title'] = "Skapa ny film";

$sqlDebug = $db->Dump();

$pageburn['main'] = <<<EOD
<h1>{$pageburn['title']}</h1>

<form method=post enctype="multipart/form-data">
  <fieldset>
  <legend>Skapa ny film</legend>
  <p><label>Titel:<br/><input type='text' name='title'/></label></p>
  <p><label>Regissör:<br/><input type='text' name='director'/></label></p>
  <p><label>Årtal:<br/><input type='text' name='year'/></label></p>
  <p><label>Subs:<br/><input type='text' name='subtext'/></label></p>
  <p><label>Tal:<br/><input type='text' name='speech'/></label></p>
  <p><label>Pris:<br/><input type='text' name='price'/></label></p>


  <p>Bild1:<input type="file" name="image" id="uploadfile"></p>
  <p>Bild2:<input type="file" name="asideImg" id="uploadfile"></p>
  <p><input type='submit' name='create' value='Skapa'/></p>
  </fieldset>
</form>

EOD;



// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
