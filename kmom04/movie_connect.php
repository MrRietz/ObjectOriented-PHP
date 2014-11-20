<?php 

//This is a Pageburn pagecontroller */


include(__DIR__.'/config.php'); 


$db = new CDatabase($pageburn['database']);



$sql = "SELECT * FROM Movie;";
$res = $db->ExecuteSelectQueryAndFetchAll($sql);

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
