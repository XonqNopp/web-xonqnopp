<?php
require_once("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);

// debug
//$page->htmlHelper->init();
//$page->logger->levelUp(6);

$page->bobbyTable->init();
//$userIsAdmin = $page->loginHelper->userIsAdmin();

$body = "";


$body = $page->bodyBuilder->goHome(NULL, "..");
// Set title and hot booty
$body .= $page->htmlHelper->setTitle("Trucs pour les femmes");  // before HotBooty
$page->htmlHelper->hotBooty();


function liInstagram($username) {
    global $page;
    return $page->bodyBuilder->liAnchor("https://www.instagram.com/$username", "<tt>@$username</tt>");
}


$body .= "<p>Principales sources d'inspiration sur instagram:</p>\n";
$body .= "<div><ul>\n";
$body .= liInstagram("osteofeminin");
$body .= liInstagram("nillan.naturopathie");
$body .= "</ul></div>\n";


// TODO regles insta

$body .= "<divL><dl>\n";

$body .= "<dt>Prendre la lumi&egrave;re naturelle le plus vite possible apr&egrave;s le r&eacute;veil.</dt>\n";
$body .= "<dd>\n";
$body .= "Il est important que la lumi&egrave;re naturelle atteigne la r&eacute;tine (penser &agrave; enlever lunettes/lentilles).\n";
$body .= "Le cerveau va alors stimuler la production de dopamine (hormone de la motivation),\n";
$body .= "&eacute;qujilibrer le cortisol (hormone du stress), et favoriser la communication entre le cerveau et les ovaires.\n";
$body .= "Cela va contribuer &agrave; &eacute;quilibrer le ratio oestrog&egrave;ne/progest&eacute;rone pour un cycle plus facile.\n";
$body .= "</dd>\n";

$body .= "<dt>Prendre un petit d&eacute;jeuner sal&eacute;, gras et protein&eacute;.</dt>\n";
$body .= "<dd>\n";
$body .= "<dl>\n";
$body .= "<dt>Sal&eacute;</dt>\n";
$body .= "<dd>Soutient le cortisol, &eacute;vite les chutes de tensions.</dd>\n";
$body .= "<dt>Gras</dt>\n";
$body .= "<dd>Mati&egrave;re premi&egrave;re des hormones.</dd>\n";
$body .= "<dt>Protein&eacute;</dt>\n";
$body .= "<dd>Resource utilis&eacute;e dans la fabrication des hormones, donne une &eacute;nergie durable.</dd>\n";
$body .= "</dl>\n";
$body .= "Tout cela aide &agrave; diminuer les inflammations et\n";
$body .= "&eacute;viter les pics de glyc&eacute;mie,\n";
$body .= "et cela donne des apports suffisants\n";
$body .= "pour fabriquer des hormones de qualit&eacute;,\n";
$body .= "pour avoir une digestion plus stable et\n";
$body .= "pour avoir un cycle plus &eacute;quilibr&eacute;.\n";
$body .= "</dd>\n";

$body .= "<dt>Marche 30min chaque jour.</dt>\n";
$body .= "<dd>\n";
$body .= "La s&eacute;dentarit&eacute; tue plus que le cancer.\n";
$body .= "Elle a un impact d&eacute;sastreux sur la sant&eacute; cardiovasculaire et m&eacute;tabolique.\n";
$body .= "Mais elle a aussi un impact sur nos hormones.\n";
$body .= "En marchant tous les jours:\n";
$body .= "<ul>\n";
$body .= "<li>La lymphe est plus en mouvement, il y a moins de r&eacute;tention d'eau.</li>\n";
$body .= "<li>Le cortisol s'&eacute;quilibre et laisse plus de r&eacute;serves pour l'oestrog&egrave;ne et la progest&eacute;rone.</li>\n";
$body .= "<li>On a une meilleure sensibilit&eacute; &agrave; l'insuline.</li>\n";
$body .= "<li>Le cycle est plus r&eacute;gulier et plus &eacute;quilibr&eacute;.</li>\n";
$body .= "</ul>\n";
$body .= "</dd>\n";

$body .= "<dt>Remplacer le caf&eacute; par de la chicor&eacute;e.</dt>\n";
$body .= "<dd>\n";
$body .= "</dd>\n";

// TODO IWASHERE

$body .= "</dl></div>\n";

echo $body;
?>
