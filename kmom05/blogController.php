<?php 
/**
 * This is a Pageburn pagecontroller.
 *
 */

include(__DIR__.'/config.php'); 
include(__DIR__.'/filter.php'); 


// Connect to a MySQL database using PHP PDO
$db = new CDatabase($pageburn['database']);


// Get parameters 
$slug    = isset($_GET['slug']) ? $_GET['slug'] : null;
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;


// Get content
$slugSql = $slug ? 'slug = ?' : '1';
$sql = "
SELECT *
FROM Content
WHERE
  type = 'post' AND
  $slugSql AND
  published <= NOW()
ORDER BY updated DESC
;
";
$res = $db->ExecuteSelectQueryAndFetchAll($sql, array($slug));


// Prepare content and store it all in variables in the Anax container.
$pageburn['title'] = "Bloggen";
$pageburn['debug'] = $db->Dump();

$pageburn['main'] = null;
if(isset($res[0])) {
  foreach($res as $c) {
    // Sanitize content before using it.
    $title  = htmlentities($c->title, null, 'UTF-8');
    $data   = doFilter(htmlentities($c->data, null, 'UTF-8'), $c->filter);

    if($slug) {
      $pageburn['title'] = "$title | " . $pageburn['title'];
    }
    $editLink = $acronym ? "<a href='edit.php?id={$c->id}'>Uppdatera posten</a>" : null;

    $pageburn['main'] .= <<<EOD
<section>
  <article>
  <header>
  <h1><a href='blog.php?slug={$c->slug}'>{$title}</a></h1>
  </header>

  {$data}

  <footer>
  {$editLink}
  </footer
  </article>
</section>
EOD;
  }
}
else if($slug) {
  $pageburn['main'] = "Det fanns inte en sådan bloggpost.";
}
else {
  $pageburn['main'] = "Det fanns inga bloggposter.";
}


// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);