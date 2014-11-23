 <?php 
/**
 * This is a Pageburn pagecontroller.
 *
 */
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__.'/config.php'); 

$db = new CDatabase($pageburn['database']);
$content = new CContent($db); 
$output = null;
if(isset($_POST['restore']) || isset($_GET['restore'])) {
    
    if($content->resetDB()) {
        $output = "<p>Databasen är återställd!</p> <a href='viewController.php'> Tillbaka till innehållet. </a>";
    }
    else {
        $output = "<p>Något gick fel vid återställandet av databasen"; 
    }
}


// Do it and store it all in variables in the Pageburn container.
$pageburn['title'] = "Återställ databasen till ursprungligt skick";

$pageburn['main'] = <<<EOD
<h1>{$pageburn['title']}</h1>
<form method=post>
<input type=submit name=restore value='Återställ databasen'/>
<output>{$output}</output>
</form>
EOD;




// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
