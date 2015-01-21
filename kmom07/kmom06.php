<?php 
/**
 * This is a Pageburn pagecontroller.
 *
 */
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__.'/config.php'); 

// Do it and store it all in variables in the Pageburn container.
$pageburn['title'] = "Redovisning Kmom06";

$pageburn['main'] = <<<EOD
<h1> {$pageburn['title']} </h1>
<p>
Mitt första problem var att jag först och främst hade glömt bort att skapa en mapp för cache. Då sade img.php “The cache dir is not a writable directory”. Det var ju bra att img.php stödde sådan trevlig felhantering. Programmering för mig är mycket mer än att bara göra rätt från början. Gör om gör rätt brukar det heta. 
</p><p>
Ett annat problem jag hade var när jag skulle skapa klassen Cimage, bilderna ville absolut inte renderas ut. Jag lyckades få fram 3-4 bilder som sparades i cache-mappen. Efter många timmars felsökning så lyckades jag lokalisera felet. Jag hade ”bara” råkat placera några $-tecken för mycket framför några this-> så det stod $$ istället. Blev överlycklig när jag hittade felet. 
</p>
<p>
Mina tidigare erfarenheter består av hantering av texturer i spelskapande. Så några funktioner för texturhantering har jag väl skapat tidigare. Nästan helt nytt för mig på webben, men det var roligt. Jag tyckte att PHP GD stödde de funktioner som man skulle önska av en bildhanterare. Smidigt att slippa editera bilderna med hjälp av photoshop som jag brukar göra. 
</p><p>
Filen img.php kändes bra att jobba med, särskilt när jag fick ordning på Cimage klassen istället. Jag valde att bara implementera grunddelarna, men jag kanske bygger ut det i projektet sen. Min tanke är att denna klass kan vara bra att använda i projektet sen. 
</p><p>
Slutligen så tycker jag att kursen har varit lärorik hittils. Vi har lärt oss att med hjälp av Anax bland annat att skapa ett tärningspel, login/logout funktionalitet, en blogg, stöd för databaskoppling och ett galleri. 
</p>
EOD;


// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
