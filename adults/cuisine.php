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
        "emmanuelle_blanck",
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
//
    $body .= $page->bodyBuilder->titleAnchor("Fruits et l&eacute;gumes");
        $body .= $page->bodyBuilder->titleAnchor("Ail", 3);
        $body .= "<p>Au lieu de le couper grossi&egrave;rement et le mettre directement dans la casserole,\n";
        $body .= "on peut le hacher finement ou l'&eacute;craser,\n";
        $body .= "le laisser reposer 10min pour que l'allinase (enzyme) forme de l'allicine (compos&eacute; protecteur),\n";
        $body .= "l'ajouter en fin de cuisson ou le consommer cru pour profiter au maximum de ce compos&eacute;.</p>\n";  // emmanuelle_blanck
    //
        $body .= $page->bodyBuilder->titleAnchor("Brocoli", 3);
        $body .= "<p>Au lieu de cuire le brocoli juste apr&egrave;s l'avoir coup&eacute;,\n";
        $body .= "on peut le laisser reposer 45-60min entre la coupe et la cuisson pour activer une enzyme qui forme un compos&eacute; protecteur.<p/>\n";  // emmanuelle_blanck
    //
        $body .= $page->bodyBuilder->titleAnchor("Champignon", 3);
        $body .= "<p>Au lieu de les laver et de les cuire directement,\n";
        $body .= "on peut les brosser &agrave; sec,\n";
        $body .= "les poser 30min au Soleil lamelles en haut (fabriquent de la vitamine D),\n";
        $body .= "les faire griller ou sauter plut&ocirc;t que bouillir (conservent mieux leurs antioxydants).</p>\n";  // emmanuelle_blanck
    //
        $body .= $page->bodyBuilder->titleAnchor("Lentille, pois chiche", 3);
        $body .= "<p>Au lieu de cuire les lentilles/pois chiches directement dans le jus de la conserve,\n";
        $body .= "on peut les acheter sec et les faire tremper 8-12h avant la cuisson,\n";
        $body .= "jeter l'eau et les rincer encore une fois pour avoir moins de phytates (bloquent l'absorption de fer, magn&eacute;sium, zinc),\n";
        $body .= "les consommer avec une source de vitamine C pour mieux absorber leur fer (citron, persil, poivron).</p>\n";  // emmanuelle_blanck
    //
        $body .= $page->bodyBuilder->titleAnchor("Lin", 3);
        $body .= "<p>Au lieu de mettre quelques graines de lin directement dans un jogurt ou une salade,\n";
        $body .= "on peut les moudre juste avant de les consommer car enti&egrave;res elles traversent les intestins sans &ecirc;tre dig&eacute;r&eacute;es,\n";
        $body .= "ce qui permet &agrave; notre corps d'acc&eacute;der aux om&eacute;gas 3 et aux lignanes (bons pour la m&eacute;nopause),\n";
        $body .= "mais la poudre se conserve au frigo pour ne pas rancir trop vite.</p>\n";  // emmasnuelle_blanck
    //
        $body .= $page->bodyBuilder->titleAnchor("Myrtille", 3);
        $body .= "<p>Au lieu de les manger fra&icirc;ches apr&egrave;s les avoir conserv&eacute;es au frigo,\n";
        $body .= "on peut les congeler dans les heures apr&egrave;s les avoir cueillie &agrave; maturit&eacute;\n";
        $body .= "pour fragiliser les parois des cellules afin que les anthocyanes (pigments antioxydants) deviennent plus accessibles.</p>\n";  // emmanuelle_blanck
    //
        $body .= $page->bodyBuilder->titleAnchor("Pomme de terre", 3);
        $body .= "<p>Au lieu de les plucher pour les r&eacute;duire en pur&eacute;e et les manger chaudes,\n";
        $body .= "on peut choisir des vari&eacute;t&eacute;s fermes,\n";
        $body .= "leur laisser la peau (plus de fibres et de potassium),\n";
        $body .= "les refroidir une nuit au frigo pour qu'une partie de l'amidon se transforme en amidon r&eacute;sistant (nourrit le microbiote, lisse le pic de glyc&eacute;mie),\n";
        $body .= "les assaisonner d'une vinaigrette pour ralentir l'absorption du sucre.</p>\n";  // emmanuelle_blanck
    //
        $body .= $page->bodyBuilder->titleAnchor("Riz", 3);
        $body .= "<p>Au lieu de verser le riz dans la cassrole pour le cuire et le manger directement,\n";
        $body .= "on peut le rincer et le faire tremper,\n";
        $body .= "le cuire dans beaucoup d'eau qu'on &eacute;goutte pour enlever de l'arsenic,\n";
        $body .= "ajouter 1cc d'huile (de coco) &agrave; la cuisson pour lisser le pic de glyc&eacute;mie quand on le mange,\n";
        $body .= "le refroidir une nuit au frigo et le r&eacute;chauffer pour que l'amidon se transforme en partie en amidon r&eacute;sistant (nourrit le microbiote).</p>\n";  // emmanuelle_blanck
    //
        $body .= $page->bodyBuilder->titleAnchor("Tomate", 3);
        $body .= "<p>Au lieu de les manger crues,\n";
        $body .= "on peut les faire cuire pour casser la paroi des cellules (lib&egrave;re du lycop&egrave;ne, plus concentr&eacute; dans la peau),\n";
        $body .= "ajouter une cuill&egrave;re d'huile d'olive (le lycop&egrave;ne a besoin de gras pour &ecirc;tre absorb&eacute;</p>\n";  // emmanuelle_blanck


echo $body;
?>
