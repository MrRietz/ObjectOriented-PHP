 <?php 
/**
 * This is a Pageburn pagecontroller.
 *
 */
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 



// Connect to a MySQL database using PHP PDO
$dsn      = 'mysql:host=localhost;dbname=Movie;';
$login    = 'root';
$password = "";
$options  = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");
$pdo = new PDO($dsn, $login, $password, $options);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);



// Get parameters
$genre = isset($_GET['genre']) ? $_GET['genre'] : null;


// Get all genres that are active
$sql = '
  SELECT DISTINCT G.name
  FROM Genre AS G
    INNER JOIN Movie2Genre AS M2G
      ON G.id = M2G.idGenre
';
$sth = $pdo->prepare($sql);
$sth->execute();
$res = $sth->fetchAll();

$genres = null; 
foreach($res as $val)
{
	$genres .= "<a href=?genre={$val->name}>{$val->name}</a> ";
}


// Do SELECT from a table
if($genre) {
  $sql = '
    SELECT 
      M.*,
      G.name AS genre
    FROM Movie AS M
      LEFT OUTER JOIN Movie2Genre AS M2G
        ON M.id = M2G.idMovie
      INNER JOIN Genre AS G
        ON M2G.idGenre = G.id
    WHERE G.name = ?
    ;
  ';
  $params = array(
    $genre,
  );  
} 
else {
  $sql = "SELECT * FROM VMovie;";
  $params = null;
}

$sth = $pdo->prepare($sql);
$sth->execute($params);
$res = $sth->fetchAll();

//dump($res);

 $container =     
   " <table>
        <tr>
        	<th>Row</th> 
        	<th>Id</th> 
        	<th>Movie Title</th>
        	<th>Image</th>
        	<th>Year</th> 
        	<th>Genre</th>
        </tr>";
        foreach($res AS $key => $obj)
        {
   $container .= "
        <tr>
        	<td>{$obj->id}</td>
        	<td>{$key}</td>
        	<td>{$obj->title}</td>
        	<td><img src='{$obj->image}' width='80' height='40'></td>
        	<td>{$obj->year}</td>
        	<td>{$obj->genre}</td> 
        </tr>
         ";
        }
$container .=" </table>"; 

// Do it and store it all in variables in the Anax container.
$pageburn['title'] = "Sök film per genre";

$paramsPrint = print_r($params, 1);
$pageburn['main'] = <<<EOD
<h1>{$pageburn['title']}</h1>
<form>
<fieldset>
<legend>Sök</legend>
<p><label>Välj genre:</label>
<br/>{$genres}
</p>

<p><a href='?'>Visa alla</a></p>
</fieldset>
</form>
<p>Resultatet från SQL-frågan:</p>
<pre>{$sql}</pre>
<pre>{$paramsPrint}</pre>

{$container}

EOD;




// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);

