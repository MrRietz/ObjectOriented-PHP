<?php 
/**
This is a Pageburn pagecontroller.

 */
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 

// Restore the database to its original settings
$sql      = 'movie.sql';
$mysql    = '/usr/local/bin/mysql';
$host     = 'localhost';
$login    = 'acronym';
$password = "Intentionally removed by CSource";
$output = null;

// Use these settings on windows and WAMPServer, 
// but you must check - and change - your path to the executable mysql.exe
//$mysql    = 'C:\wamp\bin\mysql\mysql5.5.24\bin\mysql.exe';
//$login    = 'root';
//$password = "Intentionally removed by CSource";


if(isset($_POST['restore']) || isset($_GET['restore'])) {
  // Use on Windows, remove password if its empty
  $cmd = "$mysql -h{$host} -u{$login} < $sql";

  $res = exec($cmd);
  $output = "<p>Databasen är återställd via kommandot<br/><code>{$cmd}</code></p><p>{$res}</p>";
}


// Do it and store it all in variables in the Anax container.
$pageburn['title'] = "Återställ databasen till ursprungligt skick";

$pageburn['main'] = <<<EOD
<h1>{$pageburn['title']}</h1>
<form method=post>
<input type=submit name=restore value='Återställ databasen'/>
<output>{$output}</output>
</form>
EOD;




// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
