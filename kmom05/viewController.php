<?php

/**
 * This is a Pageburn pagecontroller.
 *
 */
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__ . '/config.php');

/**
 * Create a link to the content, based on its type.
 *
 * @param object $content to link to.
 * @return string with url to display content.
 */
function getUrlToContent($content) {
    switch ($content->type) {
        case 'page': return "pageController.php?url={$content->url}";
            break;
        case 'post': return "blogController.php?slug={$content->slug}";
            break;
        default: return null;
            break;
    }
}

$db = new CDatabase($pageburn['database']);

$sql = '
  SELECT *, (published <= NOW()) AS available
  FROM Content;
';

$res = $db->ExecuteSelectQueryAndFetchAll($sql);

// Put results into a list
$items = null;
foreach($res AS $key => $val) {
  $items .= "<li>{$val->type} (" . (!$val->available ? 'inte ' : null) . "publicerad): " . htmlentities($val->title, null, 'UTF-8') . " (<a href='editController.php?id={$val->id}'>editera</a> <a href='" . getUrlToContent($val) . "'>visa</a>)</li>\n";
}

// Do it and store it all in variables in the Anax container.
$pageburn['title'] = "Visa allt innehåll";

$pageburn['main'] = <<<EOD
<h1>{$pageburn['title']}</h1>

<p>Här är en lista på allt innehåll i databasen.</p>

<ul>
{$items}
</ul>

<p><a href='blog.php'>Visa alla bloggposter.</a></p>

EOD;

// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);

