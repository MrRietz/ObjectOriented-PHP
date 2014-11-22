<?php 
/**
 * This is a Pageburn pagecontroller.
 *
 */
// Include the essential config-file which also creates the $pageburn variable with its defaults.
include(__DIR__.'/config.php'); 

if(isset($p)) echo "id='".strip_tags($p)."'"; 
// Do it and store it all in variables in the Pageburn container.
$pageburn['title'] = "Redovisning Kmom04";

$pageburn['main'] = <<<EOD
<h1> Kmom04 </h1>
<p>
Denna uppgiften var en utmaning och var tidskrävande då jag försökte göra mer än vad den krävde. Det fick bra att arbeta med PDO då jag tidigare har använt mig av det. De största svårigheterna med detta var att hitta rätt användarnamn till min egen localhost då jag hade ”root” i wamp. Jag hade också lite problem med session_start() men efter lite sökande på forumet så hittades felet, mellanslag innan <?php stort tack Mikael för detta inlägg. Det var även smidigt att i klassen Cdatabase kunna göra olika funktioner beroende på vilken typ sql sats man vill köra.
</p><p>
Filmdatabas-guiden gick bra, när jag skulle lägga upp allting på studentservern så fick jag ändra inställningarna för databasen och lägga till Cdatabase objektet i alla controllers som inte använde sig av det. Jag valde att ta med hela guiden på servern också, men jag fick inte återställ funktionen att fungera så den plockade jag bort. Här försökte jag också göra en sidomeny till dropdown menyn för att samla exempelvis sökfunktionerna. Efter att ha bråkat med callback funktionen lite så lämnade jag det på hyllan, den ville inte markera alla menyval som jag ville ha det.
</p>
<p>
I denna uppgiften så skapade jag tre klasser CDatabase, CUser och CSearchAlternative. CDatabase gjorde det möjligt att enkelt köra select-satser mot databasen, fördelen är ju att vi här slipper att skriva samma kod många gånger i de olika sidkontrollerna. Användbarheten ökar då man i sina moduler får en mer strukturerad och lättläslig kod. Jag kan inte se några direkta nackdelar med att skapa en klass för detta. CUser ger möjligheten att logga in och logga ut, den håller koll på vem som är inloggad på sidan. Vi får en snyggare lösning eftersom att vi skickar in parametrarna acronym och password i loginfunktionen. Slutligen har jag gjort klassen CSearchAlternative den innehåller en konstruktor, en samling privata funktioner samt en publik funktion som heter RenderHtml. Jag tyckte att det enda som var väsentligt med multisök var att RenderHtml funktionen kunde returnera den HTML som krävdes för att visa upp tabellen med filmerna och sökformuläret. Sidkontrollern movie_alternative_search.php innehåller ytterst lite kod, alla funktioner körs i klassen istället.
</p><p>
Jag lärde mig definitivt mer om menyer denna uppgiften, lite besviken att jag inte hann fixa sidomenyn. Jag lärde mig mer om felsökning med hjälp av SaveDebug funktionen i databasklassen. Resultatet är jag nöjd med, ändrade även lite på sidans layout och lade till en sidebar i Anax. 
</p>

EOD;


// Finally, leave it all to the rendering phase of Anax.
include(PAGEBURN_THEME_PATH);
