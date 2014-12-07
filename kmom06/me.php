<?php 
/**
 * This is a Pageburn controller.
 *
 */
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__.'/config.php'); 


// Define what to include to make the plugin to work
$pageburn['stylesheets'][]        = 'css/slideshow.css';
//$pageburn['jquery']               = true;
$pageburn['javascript_include'] = array('js/slideshow.js');


// Do it and store it all in variables in the Pageburn container.
$pageburn['title'] = "Om mig";


$pageburn['main'] = <<<EOD
<div id="slideshow" class='slideshow' data-host="" data-path="img/me/" data-images='["me_1.jpg", "me_2.jpg", "me_3.jpg"]'>
<img src='img/me/me_3.jpg' width='950px' height='200px' alt='Me'/>
</div>

<article>
<h1>Om Mig</h1>

<p>Mitt namn är Robin Rietz Berntsson, jag är född 1990 och är uppvuxen i den lilla byn Svängsta precis norr om Karlshamn.
Min farfar har faktiskt huggt ner skogen där vi bodde han var en riktigt duktig skogshuggare.  
Jag gick i låg och mellanstadiet i samma by, för att sedan ta steget till högstadiet i grannbyn Mörrum.  </p>

<p>
När jag började gymnasiet 2006 så valde jag IT linje på John Bauer gymnasiet med inriktning på programmering. 
Det första språket som vi fick lära oss där var HTML. CSS hade jag knappt hört talas om då man fortfarande gjorde mycket i HTML koden.
Vidare så lärde vi oss C# och även PHP. Det mest avancerade jag lyckades göra var ett flygspel till XBOX360. 
Jag och min klasskamrat Alexander Borgström startade tillsammans med några andra i skolan en förening sponsrad av Microsoft med inriktning på Spelprogrammering. 
Det var mycket detta som fick mig att välja Spelprogrammeringslinjen på Blekinge Tekniska Högskola i Ronneby. 
Mitt under gymnasiets tid så började också vid 16 års ålder spela gitarr. Jag spelade i mitt första band bara ett år efteråt. Musik och programmering är en väldigt bra kombination.
</p>
<p>
Då var vi framme på året 2009, året då jag började studera på BTH. Jag läste från början programmet Spelprogrammering. När alla kurser hade gått klart 2012, så hade jag bara lite högskolepoäng kvar. Jag tog ett jobb på systembolaget, tänkte det kunde vara kul att testa. Framtiden ser väldigt ljus ut, jag håller utkik på IT-jobb varje vecka. Siktar på IT avdelningen på Systembolagets huvudkontor. Där kan man jobba med både Webb och andra trevliga applikationer. Jag siktar på att plocka ut min kandidatexamen i Augusti i år iallafall. 
Tack för att ni har tagit er tiden att läsa om mitt liv!
</p>

{$pageburn['byline']}

</article>

EOD;

// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
