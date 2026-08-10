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

    $body .= $page->bodyBuilder->titleAnchor("Devenir parent");
    $body .= "<div><ul>\n";
        $body .= $page->bodyBuilder->liAnchor("education.php", "Education positive");
        $body .= $page->bodyBuilder->liAnchor("ecrans.php", "Enfants et &eacute;crans");
        $body .= $page->bodyBuilder->liAnchor("parenting.php", "Apprendre &agrave; &ecirc;tre parent");
    $body .= "</ul></div>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("Devenir femme");
    $body .= "<div><ul>\n";
    // TODO regles insta
    // osteofeminin
    // nillan.naturopathie
    $body .= "</ul></div>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("Devenir jardinier");
    $body .= "<div><ul>\n";
    $body .= "</ul></div>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("Devenir cuisiner");
    $body .= "<div><ul>\n";
    $body .= $page->bodyBuilder->liAnchor("cooking.php", "Trucs pour la cuisine");
    // TODO poele inox
    // TODO recettes: section pas recettes?
    $body .= $page->bodyBuilder->liAnchor("../recettes/index.html", "nos recettes");
    $body .= "</ul></div>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("Devenir propri&eacute;taire");
    $body .= "<div><ul>\n";
        $body .= $page->bodyBuilder->liAnchor("maison.php", "Acheter une maison");

        $hypothequeUrl = "hypotheque.php" . ($userIsAdmin ? "?revenu=75000&cash=95000&lpp=120000" : "");  // 2020-11-01
        $body .= $page->bodyBuilder->liAnchor($hypothequeUrl, "Mon calculateur d'hypoth&egrave;que");
    $body .= "</ul></div>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("Devenir coll&egrave;gue");
    $body .= "<div><ul>\n";
        $body .= $page->bodyBuilder->liAnchor("teamwork.php", "Team work");
        $body .= $page->bodyBuilder->liAnchor("ai.php", "AI tricks");
    $body .= "</ul></div>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("Devenir pilote");
    $body .= "<div><ul>\n";
    $body .= "</ul></div>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("Devenir cheyenne");
    $body .= "<div><ul>\n";
    $body .= $page->bodyBuilder->liAnchor("https://www.youtube.com/watch?v=dQw4w9WgXcQ", "Combattre quand m&ecirc;me...");
    $body .= "</ul></div>\n";

echo $body;
?>
