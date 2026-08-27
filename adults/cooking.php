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
$body .= $page->htmlHelper->setTitle("Trucs pour la cuisine");  // before HotBooty
$page->htmlHelper->hotBooty();


    $body .= $page->bodyBuilder->titleAnchor("Casserole et po&ecirc;le en inox");

    $body .= "<p>Pour que les aliments n'adh&egrave;rent pas &agrave; la casserole, il est important de la chauffer.\n";
    $body .= "Pour savoir si elle a la temp&eacute;rature ad&eacute;quate, on peut faire le test des gouttes d'eau avec l'effet Leidenfrost.\n";
    $body .= "Lorsqu'on laisse tomber quelques gouttes d'eau sur la casserole, il y a 3 cas:\n";
    $body .= "</p>\n";
    $body .= "<div><ul>\n";
    $body .= "<li>Les gouttes d'eau bouillent et s&eacute;vaporent: la casserole n'est pas assez chaude.</li>\n";
    $body .= "<li>Les gouttes d'eau \"flottent\" sur la surface et finissent par se rassembler: bonne temp&eacute;rature.</li>\n";
    $body .= "<li>Les gouttes d'eau \"flottent\" sur la surface mais se s&eacute;parent en plus petites: trop chaud.</li>\n";
    $body .= "</ul></div>\n";

    $body .= "<p>Il peut arriver qu'on a dans une casserole inox des taches qu'on n'arrive pas &agrave; enlever.\n";
    $body .= "Un bon moyen de nettoyer l'inox est de remplier d'eau bouillante,\n";
    $body .= "et de saupoudrer une bonne dose de percarbonate de soude,\n";
    $body .= "et de laisser reposer 10 minutes.\n";
    $body .= "</p>\n";


echo $body;
?>
