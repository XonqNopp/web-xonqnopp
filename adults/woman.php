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

$body .= "<dd>\n";
$body .= "<dt>Prendre un petit d&eacute;jeuner sal&eacute;, gras et protein&eacute;.</dt>\n";
// TODO IWASHERE
$body .= "</dd>\n";

$body .= "</dl></div>\n";

echo $body;
?>
