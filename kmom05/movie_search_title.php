 <?php 
/**
 * This is a Pageburn pagecontroller.
 */
 
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 

// Connect to a MySQL database using PHP PDO
/*$dsn      = 'mysql:host=localhost;dbname=Movie;';
$login    = 'root';
$password = "";
$options  = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");
$pdo = new PDO($dsn, $login, $password, $options);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);*/

$db  = new CDatabase($pageburn['database']); 


// Get parameters for sorting
$title = isset($_GET['title']) ? $_GET['title'] : null;


// Do SELECT from a table
if($title) {
  $sql = "SELECT * FROM Movie WHERE title LIKE ?;";
  $params = array(
    $title,
  );  
} 
else {
  $sql = "SELECT * FROM Movie;";
  $params = null;
}

$res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);
//dump($res);

$container =     
   " <table>
        <tr>
        	<th>Row</th> 
        	<th>Id</th> 
        	<th>Movie Title</th>
        	<th>Image</th>
        	<th>Year</th> 
        </tr>";
        foreach($res AS $key => $obj)
        {
   $container .= "
        <tr>
        	<td>{$key}</td>
        	<td>{$obj->id}</td>
        	<td>{$obj->title}</td>
        	<td><img src='{$obj->image}' width='80' height='40'></td>
        	<td>{$obj->year}</td>
        </tr>
         ";
        }
$container .=" </table>"; 

// Do it and store it all in variables in the Anax container.
$pageburn['title'] = "Sök titel i filmdatabasen";

$title = htmlentities($title);
$paramsPrint = htmlentities(print_r($params, 1));

$pageburn['main'] = <<<EOD
<h1>{$pageburn['title']}</h1>
<form>
<fieldset>
<legend>Sök</legend>
<p><label>Titel (delsträng, använd % som wildcard): <input type='search' name='title' value='{$title}'/></label></p>
<p><a href='?'>Visa alla</a></p>
</fieldset>
</form>
<p>Resultatet från SQL-frågan:</p>
<p><code>{$sql}</code></p>
<p><pre>{$paramsPrint}</pre></p>
<table>
{$container}
</table>
EOD;




// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);

