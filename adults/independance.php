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
$body .= $page->htmlHelper->setTitle("Devenir ind&eacute;pendant");  // before HotBooty
$page->htmlHelper->hotBooty();


$body .= instagramSources(
    array(
        "bee.greener",
        "gege_en_vadrouille",
    )
);


    $body .= $page->bodyBuilder->titleAnchor("Alimentation");
// TODO proportions repas avec/sans viande
// eviter sucre, plutot fin


    $body .= "<p>Les queues des fraises se mangent et sont pleines d'antioxydants puissants qui aident &agrave; lutter contre le stress et l'inflammation.</p>\n";  // bee.greener

    $body .= "<p>La peau des kiwis (bio) se mange et est pleine de nutriments et de vitamines.</p>\n";  // bee.greener

    $body .= "<p>Les boissons froides &eacute;teignent la chaleur naturelle de l'estomac.\n";
    $body .= "Lorsqu'on consomme une boisson froide, le corps va utiliser beaucoup d'&eacute;nergie &agrave; r&eacute;chauffer l'estomac.\n";
    $body .= "Quand il fait chaud, on peut aider le corps &agrave; ne pas surchauffer en consommant des boissons tid&egrave;des.\n";
    $body .= "Les b&eacute;douins dans le d&eacute;sert boivent m&ecirc;me du th&eacute; br&ucirc;lant, ce qui fait que le corps transpire plus et &eacute;vacue plus de chaleur.\n";
    $body .= "</p>\n";  // gege_en_vadrouille

//
    $body .= $page->bodyBuilder->titleAnchor("Habiter");

    $body .= "<p>Pour refroidir son logement quand il fait chaud, on peut tendre des tissus mouill&eacute;s devant les fen&ecirc;tres ouvertes.\n";
    $body .= "L'air qui traverse la fen&ecirc;tre va se refroidir en faisant &eacute;vaporer l'eau.\n";
    $body .= "</p>\n";  // gege_en_vadrouille

    $body .= "<p>Pour refroidir son logement quand il fait chaud, dans les ch&acirc;teaux on couvrait le sol de plantes coup&eacute;es le matin.\n";
    $body .= "En marchant dessus, on &eacute;crasait les feuilles et elles lib&eacute;raient de l'humidit&eacute; par &eacute;vaporation.\n";
    $body .= "Aujourd'hui, cette m&eacute;thode n'est pas id&eacute;ale dans la maison, mais on peut la substituer par un coup de serpilli&egrave;re humide sur le sol, ce qui va &eacute;galement rafra&icirc;chir l'air ambiant.\n";
    $body .= "Attention toutefois &agrave; ne le faire que si on peut ouvrir les fen&ecirc;tres pour que l'air chaud s'&eacute;vacue, sinon on va juste humidifier l'air ambiant et le rendre plus chaud.\n";
    $body .= "</p>\n";  // gege_en_vadrouille

//
    $body .= $page->bodyBuilder->titleAnchor("M&eacute;nage");


echo $body;
?>
