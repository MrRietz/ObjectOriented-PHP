<?php 
/**
 * This is a Pageburn pagecontroller.
 */
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 



$db  = new CDatabase($pageburn['database']); 


// Get parameters
$year1 = isset($_GET['year1']) && !empty($_GET['year1']) ? $_GET['year1'] : null;
$year2 = isset($_GET['year2']) && !empty($_GET['year2']) ? $_GET['year2'] : null;


// Do SELECT from a table
if($year1 && $year2) {
  $sql = "SELECT * FROM Movie WHERE year >= ? AND year <= ?;";
  $params = array(
    $year1,
    $year2,
  );  
} 
elseif($year1) {
  $sql = "SELECT * FROM Movie WHERE year >= ?;";
  $params = array(
    $year1,
  );  
} 
elseif($year2) {
  $sql = "SELECT * FROM Movie WHERE year <= ?;";
  $params = array(
    $year2,
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
$pageburn['title'] = "Sök film per år";

$year1 = htmlentities($year1);
$year2 = htmlentities($year2);
$paramsPrint = htmlentities(print_r($params, 1));

$pageburn['main'] = <<<EOD
<h1>{$pageburn['title']}</h1>
<form>
<fieldset>
<legend>Sök</legend>
<p><label>Skapad mellan åren: 
    <input type='text' name='year1' value='{$year1}'/>
    - 
    <input type='text' name='year2' value='{$year2}'/>
  </label>
</p>
<p><input type='submit' name='submit' value='Sök'/></p>
<p><a href='?'>Visa alla</a></p>
</fieldset>
</form>
<p>Resultatet från SQL-frågan:</p>
<p><code>{$sql}</code></p>
<p><pre>{$paramsPrint}</pre></p>
{$container}
EOD;




// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
