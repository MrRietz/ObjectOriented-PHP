<?php 
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__.'/config.php'); 


$db = new CDatabase($pageburn['database']);
$blog = new CBlog($db);

$pageburn['title'] = "Om RM Rental Movies";         
$pageburn['main'] = <<<EOD
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">The Roots</h3>
  </div>
  <div class="panel-body">
    <p>Mitt namn är Jörgen Karmapolis och jag är en av grundarna till RM Rental Movies. Det hela började när jag var ute på bio med min kompis Robin, vi såg då nya Iron Man filmen i 3D format. Vid ingången till biosalongen stod en man och delade ut glasögon som man var tvungen att bära för att över huvud taget kunna se på filmen. Efter filmen så diskuterade jag och Robin om hur omständigt det var att behöva se på filmen genom ett par glasögon. Det vi kom fram till var att det faktiskt är bekvämare att se film hemma på TVn utan jobbiga 3D-glasögon.</p>
    <p>Då kom vi med den galna ideén om vad jag skulle göra med min gamla DVD samling.  Nämligen att starta en uthyrningstjänst för filmer på nätet med möjlighet att få hem filmen i brevlådan.  Då webbutveckling inte var mitt starka ämne  och att den enda dator jag ägde var en gammal C64 dator, så var Robin till stor hjälp vid skapandet av sidan</p>
    <p>RM:s syfte är att så många som möjligt skall få ta del av den film kultur som världen har att erbjuda. Våran målgrupp är både gamla som unga, ingen lämnas i sticket så vi erbjuder genrer som komedi, skräck, action eller family. Än så länge har ingen klagat på vår utomordentliga filmuthyrningstjänst. Vi kan garantera dig en snabb leveranstid på 1 till 5 dagar, då vi har hyrt ett helt gäng med cyklister som ibland får cykla mer än 100 mil för att leverera din film. </p>
    <p>Vi erbjuder även ett nyhetsflöde som slår både facebook och twitters flöden  med hästlängder. Här får du ta del av de senaste nyheterna, läsa om kommande filmer eller helt enkelt bara njuta av våra enormt välskrivna uppdateringar. Se till att lägga in RM i ditt rss flöde så fort du kan, så får du alla uppdateringar snabbare än ljusets hastighet. </p>
  </div>
</div>
EOD;


$res = $blog->getHomePosts(); 

$pageburn['sidebarTitle'] = "Senaste Nyheter";

if (isset($res[0])) {
    
    foreach ($res as $content) {
        $blog->sanitizeVariables($content);
        $pageburn['sidebar'] .=  $blog->renderHTML($content, true);
    }
}



// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
