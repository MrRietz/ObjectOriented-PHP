<?php 

//This is a Pageburn pagecontroller */


include(__DIR__.'/config.php'); 


//Connecting to Mysql
$dsn      = 'mysql:host=localhost;dbname=Movie;';
$login    = 'root';
$password = '';
$options  = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");
  $pdo = new PDO($dsn, $login, $password, $options);
try 	
{
  $pdo = new PDO($dsn, $login, $password, $options);
}
catch(Exception $e) 
{
  //throw $e; // For debug purpose, shows all connection details
  throw new PDOException('Could not connect to database, hiding connection details.'); // Hide connection details.
}
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

$sql = "SELECT * FROM Movie;";
$sth = $pdo->prepare($sql); 
$sth->execute(); 
$res = $sth->fetchAll(); 
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
$pageburn['title'] = "Koppla upp PHP PDO mot MySQL";

$pageburn['main'] = <<<EOD
<h1>{$pageburn['title']}</h1>
<p>Resultatet från SQL-frågan:</p>
<p><code>{$sql}</code></p>

{$container}

EOD;
	
	
	
// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
