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
$body .= $page->htmlHelper->setTitle("Trucs pour le jardin");  // before HotBooty
$page->htmlHelper->hotBooty();


// TODO alphabetic

    $body .= $page->bodyBuilder->titleAnchor("Aubergines");
    $body .= "<p>Quand la premi&egrave;re fleur principale fleurit, couper toutes les branches annexes au-dessous.</p>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("Concombres");
    $body .= "<p>Pour les plants &agrave; 6 feuilles, enlever les petits ftruits, les branches annexes et les minis fleurs.</p>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("Haricots");
    $body .= "<p>Quand le plant a fait 5 feuilles, pincer le sommet pour &eacute;viter que la plante fasse des branches au lieu des fruits.</p>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("Poivrons");
    $body .= "<p>Quand il y a le premier bouquet de fleurs, enlever toutes les branches au-dessous.</p>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("Tomates");
    $body .= "<p>Quand les premi&egrave;res fleurs s'ouvrent, enlever tous les gourmands.</p>\n";



echo $body;
?>
