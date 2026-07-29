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


$body = $page->bodyBuilder->goHome("..");
// Set title and hot booty
$body .= $page->htmlHelper->setTitle("Apprendre &agrave; &ecirc;tre parent");  // before HotBooty
$page->htmlHelper->hotBooty();

$body .= "<p>Les enfants connaissent le nom de chaque partie du corps et le nom des parties intimes.\n";
$body .= 'On ne dit pas "zizi" ou "n&eacute;n&eacute;" et compagnie.' . "\n";
$body .= "On n'a pas honte de nommer les parties du corps.\n";
$body .= "Utiliser d'autres termes cr&eacute;e un tabou.\n";
$body .= "</p>\n";

$body .= "<p>Les secrets sont interdits.\n";
$body .= "On peut faire des surprises, mais un adulte qui demande &agrave; un enfant de garder un secret c'est RED FLAG!\n";
$body .= "</p>\n";

$body .= "<p>Un adulte/ado qui demande de l'aide a un enfant (hors vie quotidienne) alors qu'il y a d'autres grandes personnes autour,\n";
$body .= "c'est souvent une technique d'approche des pr&eacute;dateurs.</p>\n";

// Il faut en parler a la maison, pas en faire un tabou.
// Toquer a la porte chambre/sdb
// Consentement aussi pour calin/bisous


echo $body;
?>
