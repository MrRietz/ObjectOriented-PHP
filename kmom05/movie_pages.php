 <?php 
/**
 * This is a Pageburn pagecontroller.
 */
 
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 


/**
 * Use the current querystring as base, modify it according to $options and return the modified query string.
 *
 * @param array $options to set/change.
 * @param string $prepend this to the resulting query string
 * @return string with an updated query string.
 */
function getQueryString($options, $prepend='?') {
  // parse query string into array
  $query = array();
  parse_str($_SERVER['QUERY_STRING'], $query);

  // Modify the existing query string with new options
  $query = array_merge($query, $options);

  // Return the modified querystring
  return $prepend . http_build_query($query);
}


/**
 * Create links for hits per page.
 *
 * @param array $hits a list of hits-options to display.
 * @return string as a link to this page.
 */
function getHitsPerPage($hits) 
{
  $nav = "Träffar per sida: ";
  foreach($hits AS $val) {
    $nav .= "<a href='" . getQueryString(array('hits' => $val)) . "'>$val</a> ";
  }  
  return $nav;
}



/**
 * Create navigation among pages.
 *
 * @param integer $hits per page.
 * @param integer $page current page.
 * @param integer $max number of pages. 
 * @param integer $min is the first page number, usually 0 or 1. 
 * @return string as a link to this page.
 */
function getPageNavigation($hits, $page, $max, $min=1)
{
  $nav  = "<a href='" . getQueryString(array('page' => $min)) . "'>&lt;&lt;</a> ";
  $nav .= "<a href='" . getQueryString(array('page' => ($page > $min ? $page - 1 : $min) )) . "'>&lt;</a> ";

  for($i=$min; $i<=$max; $i++) 
  {
    $nav .= "<a href='" . getQueryString(array('page' => $i)) . "'>$i</a> ";
  }

  $nav .= "<a href='" . getQueryString(array('page' => ($page < $max ? $page + 1 : $max) )) . "'>&gt;</a> ";
  $nav .= "<a href='" . getQueryString(array('page' => $max)) . "'>&gt;&gt;</a> ";
  return $nav;
}


// Connect to a MySQL database using PHP PDO
$dsn      = 'mysql:host=localhost;dbname=Movie;';
$login    = 'root';
$password = "";
$options  = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");
$pdo = new PDO($dsn, $login, $password, $options);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);






//Parameters for sorting
$hits = isset($_GET['hits']) ? $_GET['hits'] : 8; 
$page = isset($_GET['page']) ? $_GET['page'] : 1; 

//Validate
is_numeric($hits) or die('Check: Hits must be numeric.'); 
is_numeric($page) or die('Check: Page must be numeric.'); 


//Get the rows
$sql = "SELECT COUNT(id) AS rows FROM VMovie"; 
$sth = $pdo->prepare($sql); 
$sth->execute(); 
$res = $sth->fetchAll(); 


//get max pages
$max = ceil($res[0]->rows / $hits);



//select from the table
$sql = "SELECT * FROM VMovie LIMIT $hits OFFSET "  . (($page - 1) * $hits); 
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
$pageburn['title'] = "Visa resultatet med paginering";

$hitsPerPage = getHitsPerPage(array(2, 4, 8));
$navigatePage = getPageNavigation($hits, $page, $max);

$pageburn['main'] = <<<EOD
<h1>{$pageburn['title']}</h1>

<p>Resultatet från SQL-frågan:</p>
<p><code>{$sql}</code></p>
{$hitsPerPage}

{$container}
{$navigatePage}

EOD;




// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);

