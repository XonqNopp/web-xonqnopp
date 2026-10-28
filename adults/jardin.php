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
$body .= $page->htmlHelper->setTitle("Devenir jardinier");  // before HotBooty
$page->htmlHelper->hotBooty();


$body .= instagramSources(
    array(
        "alex.777terieur",
        "bee.greener",
        "gardening.tipss",
        "gilles_jardin_potager_bio",
        "jardinbiobzh",
    )
);


// TODO alphabetic

    $body .= $page->bodyBuilder->titleAnchor("Aubergines");
    $body .= "<p>Quand la premi&egrave;re fleur principale fleurit, couper toutes les branches annexes au-dessous.</p>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("Concombres");
    $body .= "<p>Pour les plants &agrave; 6 feuilles, enlever les petits ftruits, les branches annexes et les minis fleurs.</p>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("Fraises");
    $body .= "<p>Les queues des fraises se mangent et sont pleines d'antioxydants puissants qui aident &agrave; lutter contre le stress et l'inflammation.</p>\n";  // bee.greener
//
    $body .= $page->bodyBuilder->titleAnchor("Haricots");
    $body .= "<p>Quand le plant a fait 5 feuilles, pincer le sommet pour &eacute;viter que la plante fasse des branches au lieu des fruits.</p>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("Poivrons");
    $body .= "<p>Quand il y a le premier bouquet de fleurs, enlever toutes les branches au-dessous.</p>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("Tomates");
    $body .= "<p>Quand les premi&egrave;res fleurs s'ouvrent, enlever tous les gourmands.</p>\n";
    $body .= "<p>Chatouiller les plants pour aider la pollinisation.</p>\n";  // gilles_jardin_potager_bio

    $body .= "<p>2-3 jours avant la plantation, enlever les feuilles du bas (un bon 10cm) pour que cela ait le temps de cicatriser.\n";
    $body .= "On fait un trou assez large et profond pour permettre au plant de bien s'enraciner,\n";
    $body .= "et on essaie de d&eacute;compacter le sol sur 20cm de plus pour qu'il ait de la facilit&eacute; &agrave; faire ses racines.\n";
    $body .= "On inonde bien le trou jusqu'&agrave; ce qu'il y ait quelques centim&egrave;tres d'eau qui restent.\n";
    $body .= "On d;colle un peu les racines du plant pour les mettre contre en bas.\n";
    $body .= "On enterre la tige jusqu'aux premi&egrave;res feuilles et on rebouche tout.\n";
    $body .= "Maintenant il ne faut surtout pas l'arroser pendant 15 jours.\n";
    $body .= "Cela va permettre au plant de faire des racines profondes pour aller chercher l'eau.\n";
    $body .= "Ensuite on irigue r&eacute;guli&egrave;rement, par exemple une oya avec 1 litre par plant par semaine suffit.\n";
    $body .= "</p>\n";  // jardinbiobzh



echo $body;
?>
