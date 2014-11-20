<?php 
/**
 * This is a Pageburn pagecontroller.
 */
 
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 

$db  = new CDatabase($pageburn['database']); 

// Get parameters for sorting
$orderby  = isset($_GET['orderby']) ? strtolower($_GET['orderby']) : 'id';
$order    = isset($_GET['order'])   ? strtolower($_GET['order'])   : 'asc';

// Check that incoming is valid
in_array($orderby, array('id', 'title', 'year')) or die('Check: Not valid column.');
in_array($order, array('asc', 'desc')) or die('Check: Not valid sort order.');


// Do SELECT from a table
$sql = "SELECT * FROM VMovie ORDER BY $orderby $order;";
$res = $db->ExecuteSelectQueryAndFetchAll($sql, array($orderby, $order));


//dump($res);
/**
 * Function to create links for sorting
 *
 * @param string $column the name of the database column to sort by
 * @return string with links to order by column.
 */
function orderby($column) 
{
  return "<span class='orderby'><a href='?orderby={$column}&order=asc'>&darr;</i></a><a href='?orderby={$column}&order=desc'>&uarr;</a></span>";
}

$container =     
   	" <table>
        <tr>
        	<th>Row</th> 
        	<th>Id " . orderby('id') . "</th> 
        	<th>Movie Title " . orderby('title') . "</th>
        	<th>Image</th>
        	<th>Year ". orderby('year') . "</th> 
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
$pageburn['title'] = "Sortera tabellens innehåll";


$pageburn['main'] = <<<EOD
<h1>{$pageburn['title']}</h1>

<p>Resultatet från SQL-frågan:</p>
<p><code>{$sql}</code></p>

{$container}

EOD;




// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);

