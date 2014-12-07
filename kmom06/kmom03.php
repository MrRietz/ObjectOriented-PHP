<?php 
/**
 * This is a Pageburn pagecontroller.
 *
 */
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__.'/config.php'); 

// Do it and store it all in variables in the Anax container.
$pageburn['title'] = "Redovisning Kmom03";

$pageburn['main'] = <<<EOD
<h1> Kmom03 </h1>
<p>Den här uppgiften såg jag som en liten repitition av Databaskursen  jag läste för ett tag sedan. Där lärde vi oss språket och vi skrev koden i SQL Server. Det var bra att fräsha upp minnet och gå igenom gamla moment. I gymnasiet gavs en kort introduktion till MySql med hjälp av PhpMyAdmin så den kunde jag bra. Vidare så skiljde inte MySql Workbench sig så mycket ifrån SQL Server så det var ganska lätt att komma in i gränsnittet. Jag testade även att logga in på min databas med hjälp av putty och skapa lite tabeller. </p>
<p>Det tog ett tag innan jag kom in på bth:s labbmiljö, detta berodde på att jag inte visste att denna krävde ett annat lösenord som man kunde få ifrån studentportalen. När jag väl fått lösenordet så gick det smidigt. MySQL Workbench klagar på kompatibiliteten när man ska logga in bara, misstänker att det inte är uppdaterat på länge. </p>
<p>Min favoritmiljö blev PhpMyAdmin både lokalt och på bth:s labbmiljö. Jag tycker den har en avsevärt snyggare design och ett plus är att man slipper installera extra program för att administrera sina databaser. Jag gjorde första halvan av SQL-övningen i PhpMyAdmin. Först när jag nådde uppgift 11 med vyer började jag spara mina queries och istället köra i workbench, detta för att jag behövde extra repetition på de sista uppgifterna.  </p>
<p>Slutligen så lärde jag mig lite mer om uppbyggnaden av tabeller och vad Storage Engine innebär. Hade ett litet problem när jag skulle lägga till Foreign Key för användarnamnet, jag hade nämligen glömt att ändra tabellen Larare till InnoDB. De sista uppgifterna ansåg jag var bra då jag behövde repetera det här med Views och Joins. Det kändes bra att få en liten genomgång av sådant jag gjorde för längesen och att få lära sig något nytt på kuppen. </p>


{$pageburn['byline']}


EOD;


// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);

