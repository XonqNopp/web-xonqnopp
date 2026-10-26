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
        "littlebunbao",
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

    $body .= "<p>Pour se rafra&icric;chir quand il fait chaud, on peut refroidir ses extr&eacute;mit&eacute;s.\n";
    $body .= "Par exemple, on peut tremper les pieds dans l'eau ou enrouler les jambes dans un linge humide.\n";
    $body .= "Cela peut aussi aider &agrave; s'endormir.\n";
    $body .= "</p>\n";  // gege_en_vadrouille

    $body .= "<p>Au lieu de passer beaucoup de temps &agrave; tout ranger, on peut aussi optimiser les trajets.\n";
    $body .= "Par exemple, lorsqu'on doit aller dans une autre pi&egrave;ce, on prend avec un objet qui doit justement s'y ranger.\n";
    $body .= "</p>\n";  // littlebunbao

    $body .= "<p>Lorsqu'on vit dans un m&eacute;nage avec d'autres personnes, il y a un bon truc contre la flemme.\n";
    $body .= "On a souvent pas envie de faire une t&acirc;che et on se dit qu'on la fera plus tard...\n";
    $body .= "Si au lieu de penser comme &ccedil;a, on se dit que si on ne le fait pas, &ccedil;a sera &agrave; une autre personne de le faire,\n";
    $body .= "le cerveau y voit une injustice il aura plus envie de r&eacute;parer l'injustice que d'&eacute;viter de faire la t&acirc;che.\n";
    $body .= "</p>\n";  // littlebunbao

    $body .= "<p>Si une t&acirc;che fait moins de 5min, il est beaucoup plus efficace de la faire imm&eacute;diatement plut&ocirc;t que\n";
    $body .= "de la mettre sur une to-do list.\n";
    $body .= "Cela aide aussi le cerveau &agrave; se lib&eacute;rer et avoir l'impression que les choses avancent.\n";
    $body .= "</p>\n";  // littlebunbao

    $body .= "<p>Pour les t&acirc;ches ennuyeuses pour une personne TDAH, on peut le faire en m&ecirc;me temps qu'autre chose.\n";
    $body .= "Par exemple, on peut faire le m&eacute;nage en &eacute;coutant un podcast, une s&eacute;rie, un audiobook ou en t&eacute;l&eacute;phonant.\n";
    $body .= "Pour les personnes plut&ocirc;t monot&acirc;ches, on peut aussi juste mettre de la musique pour passer un meilleur moment.\n";
    $body .= "</p>\n";  // littlebunbao

    $body .= "<p>Si faire le rangement/m&eacute;nage de toute l'habitation en une seule fois est trop d&eacute;courageant,\n";
    $body .= "on peut changer de m&eacute;thode et faire une pi&egrave;ce &agrave; fond.\n";
    $body .= "Chaque personne s'occupe d'une zone, et on fait un roulement selon les jours/semaines.\n";
    $body .= "</p>\n";  // littlebunbao
//
    $body .= $page->bodyBuilder->titleAnchor("M&eacute;nage");


echo $body;
?>
