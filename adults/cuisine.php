<?php
require_once("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);

require_once("shared.php");

// debug
//$page->htmlHelper->init();
//$page->logger->levelUp(6);

$page->bobbyTable->init();
//$userIsAdmin = $page->loginHelper->userIsAdmin();

$body = "";


$body = $page->bodyBuilder->goHome(NULL, "..");
// Set title and hot booty
$body .= $page->htmlHelper->setTitle("Devenir cuisinier");  // before HotBooty
$page->htmlHelper->hotBooty();

$body .= instagramSources(
    array(
        "creative_explained",
        "exactlyhowthingswork",
        "noxhrecettes",
        "vicplusgreen",
    )
);


$body .= "<p>On peut enlever les taches sur une planche en bois avec du savon et du bicarbonate de soude.</p>\n";  // exactlyhowthingswork
$body .= "<p>On peut enlever les taches sur une planche en bois avec du sel et on frotte avec un demi-citron.</p>\n";  // createive_explained
$body .= "<p>On peut nettoyer une r&acirc;pe &agrave; fromages en r&acirc;pant un demi-citron pres&eacute;.</p>\n";  // creative_explained

$body .= "<p>Pour nettoyer une machine &agrave; paninis, quand on a fini on d&eacute;branche,\n";
$body .= "on met un peu de vinaigre ou de jus de citron, on place un chiffon humide et on ferme 1 minute,\n";
$body .= "puis avec un autre chiffon on peut tout nettoyer.</p>\n";  // creative_explained


    $body .= $page->bodyBuilder->titleAnchor("Casserole (et po&ecirc;le) en inox");

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
    $body .= "On peut ensuite la laver et les taches devraient partir plus facilement (mais peut-&ecirc;tre pas &agrave; 100%).\n";
    $body .= "</p>\n";  // vicplusgreen
//
    $body .= $page->bodyBuilder->titleAnchor("Conservation");
    $body .= "<p>Dans un endroit frais et sec: ail, oignon.</p>\n";  // creative_explained
    $body .= "<p>Dans un endroit frais et sec et sombre: pommes de terre.</p>\n";  // creative_explained
    $body .= "<p>Humidifier et emballer et mettre au frigo: salade.</p>\n";  // creative_explained
    $body .= "<p>Le pied dans l'eau avec un sac sur la t&ecirc;te et au frigo: poireau, asperge.</p>\n";  // creative_explained
    $body .= "<p>Dans un sac perm&eacute;able au frais: concombre.</p>\n";  // creative_explained
    $body .= "<p>Dans un coin de la cuisine: tomate.</p>\n";  // creative_explained
    $body .= "<p>A l'&eacute;cart des autres fruits: pomme, poire, kiwi.</p>\n";  // creative_explained
    $body .= "<p>Dans un chiffon humide, dans un sac, au frigo: herbes.</p>\n";  // creative_explained
    $body .= "<p>Emballer le sommet dans du plastique: banane.</p>\n";  // creative_explained


echo $body;
?>
