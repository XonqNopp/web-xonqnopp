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
$body .= $page->htmlHelper->setTitle("Devenir sportif");  // before HotBooty
$page->htmlHelper->hotBooty();


$body .= instagramSources(
    array(
        "Sam.secrets",
    )
);


$body .= $page->bodyBuilder->titleAnchor("Se maintenir en forme");

    $body .= $page->bodyBuilder->titleAnchor("Se pendre &agrave; une barre 2 minutes", 3);
    // Sam.secrets
    $body .= "<p>Se suspendre &agrave; une barre pendant 2 minutes est un tr&egrave;s bon exercice aux bienfaits sous-estim&eacute;s.\n";
    $body .= "Ce mouvement a pourtant &eacute;t&eacute; naturel pendant plusieurs milliers d'ann&eacute;es.\n";
    $body .= "Le pratiquer r&eacute;guli&egrave;rement aide &agrave; renforcer la main, le poignet et l'avant-bras.\n";
    $body .= "Il ne faut pas n&eacute;cessairement faire 2 minutes d'affil&eacute;es, mais 2 minutes cumul&eacute;es sur la journ&eacute;e.\n";
    $body .= "Il faut commencer gentiment par 10s et augmenter progressivement au fil des s&eacute;eances.\n";
    $body .= "Les b&eacute;n&eacute;fices seront sur le long terme (plus de 6 mois).\n";

    $body .= "Lorsqu'on ne le fait pas assez,\n";
    $body .= "l'espace dans la r&eacute;gion subacromial (o&ugrave; passent les tendons de la coiffe des rotateurs)\n";
    $body .= "se retr&eacute;cit et peut amener des g&ecirc;nes et des douleurs.\n";
    $body .= "Cela arrive facilement si on passe la journ&eacute;e assis &agrave; l'ordinateur.\n";
    $body .= "En se pendant &agrave; une barre, on met l'&eacute;paule en traction et cela aide &agrave; r&eacute;tablir cet espace.\n";

    $body .= "Dans la colonne vert&eacute;brale, les disques se compressent lorsqu'on reste assis.\n";
    $body .= "En compression, ils n'arrivent pas &agrave; absorber les nutriments.\n";
    $body .= "En se pendant &agrave; une barre, les vert&egrave;bres thoraciques et lombaires se d&eacute;compressent,\n";
    $body .= "et les disques peuvent se r&eacute;hydrater et absorber les nutriments.\n";

    $body .= "Quand on arrive &agrave; faire cet exercice confortablement, on peut le changer de passif &agrave; actif.\n";
    $body .= "Une fois suspendu, on tire l'&eacute;paule en arri&egrave;re contre en bas sans plier les coudes.\n";
    $body .= "Au lieu d'&ecirc;tre un simple stretching, cela devient un travail de stabilisation de l'&eacute;paule.\n";
    $body .= "On ne fait plus simplement de la mobilit&eacute;, mais on travaille aussi le contr&ocirc;le.\n";

    $body .= "</p>\n";

$body .= $page->bodyBuilder->titleAnchor("Escalade");

$body .= $page->bodyBuilder->titleAnchor("Randonn&eacute;e");

// Patte humide sur fourmilliere, morceaux de fruits pendant 5min, secouer, frotter sur soi
// jamalimo14


echo $body;
?>
