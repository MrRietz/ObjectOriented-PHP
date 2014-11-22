 <?php 
/**
 * This is a Anax pagecontroller.
 *
 */
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 
include(__DIR__.'/filter.php'); 


// Connect to a MySQL database using PHP PDO
$db = new CDatabase($pageburn['database']);


// Get parameters 
$url     = isset($_GET['url']) ? $_GET['url'] : null;
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;


// Get content
$sql = "
SELECT *
FROM Content
WHERE
  type = 'page' AND
  url = ? AND
  published <= NOW();
";
$res = $db->ExecuteSelectQueryAndFetchAll($sql, array($url));

if(isset($res[0])) {
  $c = $res[0];
}
else {
  die('Misslyckades: det finns inget innehåll.');
}

// Sanitize content before using it.
$title  = htmlentities($c->title, null, 'UTF-8');
$data   = doFilter(htmlentities($c->data, null, 'UTF-8'), $c->filter);


// Prepare content and store it all in variables in the Anax container.
$pageburn['title'] = $title;
$pageburn['debug'] = $db->Dump();

$editLink = $acronym ? "<a href='edit.php?id={$c->id}'>Uppdatera sidan</a>" : null;

$pageburn['main'] = <<<EOD
<article>
<header>
<h1>{$title}</h1>
</header>

{$data}

<footer>
{$editLink}
</footer
</article>
EOD;



// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
