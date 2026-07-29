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
$body .= $page->htmlHelper->setTitle("Devenir pilote");  // before HotBooty
$page->htmlHelper->hotBooty();

// Calculer 12h/ans
// Calculer 1h30 instructeur /2ans
// Calculer 1h30 instructeur /4ans LPC
// Calculer docteur
// Jamais sur partir avant d'etre en l'air (avion maintenance, meteo...)
// Jamais sur faire itineraire avant atterrir


echo $body;
?>
