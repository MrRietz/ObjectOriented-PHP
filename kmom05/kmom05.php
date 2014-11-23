<?php 
/**
 * This is a Pageburn pagecontroller.
 *
 */
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__.'/config.php'); 

// Do it and store it all in variables in the Pageburn container.
$pageburn['title'] = "Redovisning Kmom05";

$pageburn['main'] = <<<EOD
<h1> {$pageburn['title']} </h1>
<p>
Innan jag började med detta kursmomentet så satte jag upp SmartGIT mot mitt github repository. Jag skapade en branch som hette kmom05 baserat på min master-branch som innehöll allt från föregående uppgift. Här är en länk till min github: https://github.com/MrRietz/ObjectOriented-PHP . Jag hade lite problem med uppladdningen på studentservern. Men det var lite olika faktorer, delvis inställningar i config filen och sedan att jag råkade ladda upp sånt som var från en gammal branch. 
</p><p>
Modulerna är användbara och skapar en säkrare och bättre struktur för sidan. Jag tycker att de mest återanvändbara modulerna är Cdatabase och Cuser hittils. Jag har bra koll på det objektorienterade tänket sedan länge, och jag tycker om att kunna utföra det med hjälp av PHP på ett smidigt sätt. Det finns till exempel läge för utbyggnad med modul för bildhantering eller andra spel än tärningsspelet. 
</p>
<p>
Den här uppgiften var mest lärorik hittils, det var kul att skapa en blogg. Det blev mest kod i Ccontent modulen. Jag lät sedan klasserna CPage och CBlog ärva ifrån Ccontent med protected variabler i Ccontent för lätt åtkomst i arvshiarkin. Jag gjorde en kontroller för varje action, en editController ska tillexempel inte hantera mer än det som har med edit att göra. Likaså ska en sidkontroller för  page ska bara hantera och visa pages. 
</p><p>
Det känns mer logiskt att strukturera upp det i klasser, jag tycker att en sidkontroller också borde vara en klass. På något sätt så tycker jag att man borde förtydliga vad som är ”Model”, ”View” och ”Controller” i ANAX. Därav lade jag till Controller i slutet på varje sidkontroller. Har jag förstått rätt så ligger vyn i filen index.tpl.php filen och inkluderas i render.php för anax.
</p>

EOD;


// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
