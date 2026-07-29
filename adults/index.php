<?php
require_once("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->htmlHelper->init();
//$page->logger->levelUp(6);
$userIsAdmin = $page->loginHelper->userIsAdmin();

$body = $page->bodyBuilder->goHome(NULL, "..");

$body .= $page->htmlHelper->setTitle("Contenus pour adultes");
$page->htmlHelper->hotBooty();

$body .= "<p>Garder en t&ecirc;te qu'un adulte apprend 10% avec des cours, 20% de ses pairs, et 70% par l'exp&eacute;rience.</p>\n";

$body .= "<div><ul>\n";
    $body .= "<li>" . $page->bodyBuilder->anchor("adult.php", "Devenir adulte");
    $body .= ":\n";
    $body .= $page->bodyBuilder->anchor("independance.php", "Devenir ind&eacute;pendant");
    $body .= "- " . $page->bodyBuilder->anchor("woman.php", "Devenir femme");
    $body .= "- " . $page->bodyBuilder->anchor("computer.php", "Utiliser les ordinateurs");
    $body .= "</li>\n";
//
    $body .= "<li>" . $page->bodyBuilder->anchor("parenting.php", "Devenir parent");
    $body .= ":\n";
    $body .= $page->bodyBuilder->anchor("education.php", "Education positive");
    $body .= "- " . $page->bodyBuilder->anchor("ecrans.php", "Enfants et &eacute;crans");
    $body .= "</li>\n";
//
    $body .= "<li>" . $page->bodyBuilder->anchor("jardin.php", "Devenir jardinier") . "</li>\n";
//
    $body .= "<li>" . $page->bodyBuilder->anchor("cuisine.php", "Devenir cuisinier");
    $body .= ":\n";
    // TODO recettes: section pas recettes?
    $body .= "- " . $page->bodyBuilder->anchor("../recettes/index.html", "Nos recettes");
    $body .= "</li>\n";
//
    $body .= "<li>" . $page->bodyBuilder->anchor("maison.php", "Devenir propri&eacute;taire");
    $body .= ":\n";

    $hypothequeUrl = "hypotheque.php" . ($userIsAdmin ? "?revenu=75000&cash=95000&lpp=120000" : "");  // 2020-11-01
    $body .= $page->bodyBuilder->anchor($hypothequeUrl, "Mon calculateur d'hypoth&egrave;que");
    $body .= "</li>\n";
//
    $body .= "<li>" . $page->bodyBuilder->anchor("teamwork.php", "Devenir coll&egrave;gue") . "</li>\n";
//
    $body .= "<li>" . $page->bodyBuilder->anchor("pilot.php", "Devenir pilote") . "</li>\n";
//
    $body .= "<li>" . $page->bodyBuilder->anchor("sport.php", "Devenir sportif") . "</li>\n";
//
    $body .= "<li>Devenir cheyenne\n:\n";
    $body .= $page->bodyBuilder->anchor("https://www.youtube.com/watch?v=dQw4w9WgXcQ", "Combattre quand m&ecirc;me...");
    $body .= "</li>\n";
$body .= "</ul></div>\n";

echo $body;
?>
