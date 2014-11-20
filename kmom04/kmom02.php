<?php 
/**
 * This is a Pageburn pagecontroller.
 *
 */
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__.'/config.php'); 

if(isset($p)) echo "id='".strip_tags($p)."'"; 
// Do it and store it all in variables in the Anax container.
$pageburn['title'] = "Redovisning Kmom02";

$pageburn['main'] = <<<EOD
<h1> Kmom02 </h1>
<p>Detta kursmomentet flöt på utan några större svårigheter. Jag har god erfarenhet sedan tidigare att programmera på ett objektorienterat vis med språk som C++, C# eller Java. Syntaxmässigt så kan man stöta på en del oklarheter ibland, men manualen för PHP är riktigt bra och jag brukar även kolla en hel mängd av forum. Singleton som nämndes i guiden för OOPHP har jag jobbat med tidigare i ett spelprojekt, det är ett väldigt tacksamt interface, den var skriven i C++. </p> 
<p>Det var en självklarhet för mig att göra oophp-på-20-steg för att prova själv i en testfil som heter test.php. Det visade sig sedan vara guld värt av att faktiskt ha fått det ”up and running”, med tanke på tärningsspelet 100. Efter min upptäckt att man måste ha en separat mapp för varje fil när man använder ”moduleloadern”,  så funderar jag på om man kan förändra den lite. Det kan bli lite mycket filer där och det vore trevligt om man kunde samla liknande moduler i filter.</p>
<p>Efter att jag slutfört guiden så började jag att göra tärningsspelet. Jag började med att göra en ny pagecontroller dicegame.php som inte gör annat än att skapa ett nytt CDicegame100 objekt för att sedan köra funktionen PlayGame() i main renderingen till Anax. Då krav nummer 3 löd ” Se till att du har minimalt med kod i sidkontrollern, det är viktigt, flytta logiken till klasserna.”. All min logik för spelet körs i klassen CDiceGame100. Den har medlemsvariablerna html som används som outputsträng och player som för nuvarande hanterar en spelare. Utöver defaultkonstruktor och destruktor så har jag två privata funktioner och en publik funktion.  <p>

<p>Den första private funktionen InitSession() initierar en session och skapar en ny Player som då har en CDiceImage i sin hand. Vidare så har vi funktionen Gamelogic() som kör själva logiken för spelet. Dessa två funktioner kallas i slutet av den publika funktionen PlayGame(), den här lärdomen tog jag med mig från guiden. 
<p>Som sagt så består min variabel Player av en CDiceImage. Den håller reda på bland annat sparade poäng, rundands poäng, här finns också funktioner för att spara poäng, slå tärning, få totala poängen etc. Jag stötte på problem när jag skulle lägga till flera spelare, jag fick syntaxfel gång på gång. Efter ett par timmar så bestämde jag mig för att skippa flerspelarläget denna gång. Det jag hade kunnat göra bättre till nästa gång är att skapa en klass för Player som håller reda på varje spelares värden. Denna klass skulle sedan kunna ha en medlemsvariabel för CDiceHand. </p>
<p>Sammanfattningsvis så lärde jag mig hur man på ett smidigt sätt kan rendera ut både sin html och php, med hjälp utav den smidiga variabeln html. Jag fick många syntaxfel innan jag kom på hur det gick att skriva. En annan lärdom var hur smidigt det är att arbeta med sessioner. Det var allt för mig den här gången. </p>
<p>Här kommer länken till min sida: http://www.student.bth.se/~rorb09/oophp/kmom02/me.php</p>



{$pageburn['byline']}


EOD;


// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);

