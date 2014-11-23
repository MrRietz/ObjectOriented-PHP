 <?php 
/**
 * This is a Pageburn pagecontroller.
 *
 */
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__.'/config.php'); 

// Do it and store it all in variables in the Anax container.
$pageburn['title'] = "Redovisning Kmom01";

$pageburn['main'] = <<<EOD
<h1> Kmom01 </h1>
<p>Detta kursmoment gick bra och det var lärorikt, jag stötte inte på några generella svårigheter. Däremot så hade jag ett problem när jag skulle sätta ihop Me-sidan, javascriptet  slutade nämligen att köra min Slide-show. Jag felsökte i 2 timmar sen så lyckades jag med hjälp av firebug hitta vart felet fanns. Det var av någon anledning så att scriptet jquery.min.js saknade värde. Jag plockade bort kodrad 125 i fil kmom01/config.php, då började det fungera igen.</p> 
<p>Mina tidigare erfarenheter av PHP har jag fått ifrån kursen HTMLPHP samt lite ifrån gymnasiet. Det ska bli spännande att jobba mer med Anax. Jag lärde mig nya saker såsom:</p>
<p>
•	Hur man använder PHPShorttags
<br>
•	Vad en sidkontroller är
<br>
•	.htaccess
<br>
•	Javascript slideshow
<br>
•	Hur man gör en Meny med undermenyer 

<p>
<p>Jag använder mig av utvecklingsmiljön Jedit för redigering, Firefox för visning av sidan, Filezilla för uppladdning till server och WAMP för lokalt. Har inte formatterat min burk sen jag läste föregående kurs och jag tycker denna miljön är bäst. Lärde mig dock en sak från förra kursen att det kan vara bra att kolla igenom hur sidan ser ut på respektive webbläsare, om man önskar att den ska vara kompatibel. </p>
<p>Strukturen på Anax tycker jag för närvarande är riktigt bra. Den ger även en öppning för utbyggnad och förbättringar om man så skulle önska i framtiden. Jag valde att inte göra några förbättringar denna gång. Återanvändbarheten  är bra för man då slipper skapa en ny uppbyggnad för varje enskild sida. Jag döpte min webbmall till Pageburn.</p>
<p>Eftersom jag ansåg att jag ville utnyttja Anax till fullo så skapade jag source.php som en modul. Dock så gjorde jag inte extrauppgiften med Github, jag sparar det till ett senare tillfälle. Skall redan nu Scrumplanera uppgift 2, detta var allt för mig den här gången. </p>
<p>Här kommer länken till min sida: http://www.student.bth.se/~rorb09/oophp/kmom01/me.php</p>



{$pageburn['byline']}


EOD;


// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);

