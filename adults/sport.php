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
$body .= $page->htmlHelper->setTitle("Devenir sportif");  // before HotBooty
$page->htmlHelper->hotBooty();

$body .= $page->bodyBuilder->titleAnchor("Escalade");

$body .= $page->bodyBuilder->titleAnchor("Randonn&eacute;e");

// Patte humide sur fourmilliere, morceaux de fruits pendant 5min, secouer, frotter sur soi
// jamalimo14


echo $body;
?>
