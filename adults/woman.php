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
$body .= $page->htmlHelper->setTitle("Devenir femme");  // before HotBooty
$page->htmlHelper->hotBooty();


$body .= instagramSources(
    array(
        "nillan.naturopathie",
        "osteofeminin"
    )
);



$body .= "<divL><dl>\n";

$body .= "<dt>Prendre la lumi&egrave;re naturelle le plus vite possible apr&egrave;s le r&eacute;veil.</dt>\n";
$body .= "<dd>\n";
$body .= "Il est important que la lumi&egrave;re naturelle atteigne la r&eacute;tine (penser &agrave; enlever lunettes/lentilles).\n";
$body .= "Le cerveau va alors stimuler la production de dopamine (hormone de la motivation),\n";
$body .= "&eacute;qujilibrer le cortisol (hormone du stress), et favoriser la communication entre le cerveau et les ovaires.\n";
$body .= "Cela va contribuer &agrave; &eacute;quilibrer le ratio oestrog&egrave;ne/progest&eacute;rone pour un cycle plus facile.\n";
$body .= "</dd>\n";

$body .= "<dt>Prendre un petit d&eacute;jeuner sal&eacute;, gras et protein&eacute;.</dt>\n";
$body .= "<dd>\n";
$body .= "<dl>\n";
$body .= "<dt>Sal&eacute;</dt>\n";
$body .= "<dd>Soutient le cortisol, &eacute;vite les chutes de tensions.</dd>\n";
$body .= "<dt>Gras</dt>\n";
$body .= "<dd>Mati&egrave;re premi&egrave;re des hormones.</dd>\n";
$body .= "<dt>Protein&eacute;</dt>\n";
$body .= "<dd>Resource utilis&eacute;e dans la fabrication des hormones, donne une &eacute;nergie durable.</dd>\n";
$body .= "</dl>\n";
$body .= "Tout cela aide &agrave; diminuer les inflammations et\n";
$body .= "&eacute;viter les pics de glyc&eacute;mie,\n";
$body .= "et cela donne des apports suffisants\n";
$body .= "pour fabriquer des hormones de qualit&eacute;,\n";
$body .= "pour avoir une digestion plus stable et\n";
$body .= "pour avoir un cycle plus &eacute;quilibr&eacute;.\n";
$body .= "</dd>\n";

$body .= "<dt>Marche 30min chaque jour.</dt>\n";
$body .= "<dd>\n";
$body .= "La s&eacute;dentarit&eacute; tue plus que le cancer.\n";
$body .= "Elle a un impact d&eacute;sastreux sur la sant&eacute; cardiovasculaire et m&eacute;tabolique.\n";
$body .= "Mais elle a aussi un impact sur nos hormones.\n";
$body .= "En marchant tous les jours:\n";
$body .= "<ul>\n";
$body .= "<li>La lymphe est plus en mouvement, il y a moins de r&eacute;tention d'eau.</li>\n";
$body .= "<li>Le cortisol s'&eacute;quilibre et laisse plus de r&eacute;serves pour l'oestrog&egrave;ne et la progest&eacute;rone.</li>\n";
$body .= "<li>On a une meilleure sensibilit&eacute; &agrave; l'insuline.</li>\n";
$body .= "<li>Le cycle est plus r&eacute;gulier et plus &eacute;quilibr&eacute;.</li>\n";
$body .= "</ul>\n";
$body .= "</dd>\n";

$body .= "<dt>Remplacer le caf&eacute; par de la chicor&eacute;e.</dt>\n";
$body .= "<dd>\n";
$body .= "Meilleure digestion, moins de stress m&eacute;tabolique, meilleure &eacute;limination des oestrog&egrave;nes.\n";
$body .= "Donc un cycle en meilleure forme.\n";
$body .= "</dd>\n";

$body .= "<dt>Manger une carotte crue chaque jour.</dt>\n";
$body .= "<dd>\n";
$body .= "C'est particuli&egrave;rement important en phase lut&eacute;ale, entre l'ovulation et le d&eacute;but des r&egrave;gles.\n";
$body .= "Les fibres vont pi&egrave;ger une partie des m&eacute;tabolites hormonaux et les &eacute;liminer dans les selles.\n";
$body .= "Cela &eacute;vite ainsi au corps de les r&eacute;absorb&eacute; et d'avoir un exc&egrave;s d'oestrog&egrave;ne.\n";
$body .= "La carotte va aussi l&eacute;g&egrave;rement dimninuer l'activit&eacute; de l'enzyme impliqu&eacute;e dans la r&eacute;activation des oestrog&egrave;nes.\n";
$body .= "</dd>\n";

$body .= "<dt>Faire 5min de pause plusieurs fois par jour.</dt>\n";
$body .= "<dd>\n";
$body .= "<ul>\n";
$body .= "<li>Faire un mouvement de mobilit&eacute; des hanches: r&eacute;duit les tensions lombaires et pelviennes.</li>\n";
$body .= "<li>Faire un &eacute;tirement du psoas: diminue les douleurs pendant les r&egrave;gles.</li>\n";
$body .= "<li>Faire un exercice de respiration: le syst&egrave;me nerveux est davantage r&eacute;gul&eacute;, les hormones peuvent fonctionner normalement.</li>\n";
$body .= "</ul>\n";
$body .= "</dd>\n";

$body .= "<dt>Mettre les jambes verticales contre le mur 15min chaque soir.</dt>\n";
$body .= "<dd>\n";
$body .= "Drainage lymphatique simple pour la maison.\n";
$body .= "R&eacute;duit la r&eacute;tention d'eau, meilleure circulation lymphatique.\n";
$body .= "Meilleure activation de la thyro&iuml;de, donc une meilleure &eacute;nergie et un meilleur &eacute;quilibre hormonal.\n";
$body .= "</dd>\n";

$body .= "<dt>Faire vibrer sa gorge 2min chaque jour.</dt>\n";
$body .= "<dd>\n";
$body .= "Stimule en douceur le nerf vague.\n";
$body .= "Cela va aider le syst&egrave;me nerveux &agrave; &ecirc;tre mieux r&eacute;gul&eacute; et le cortisol plus &eacute;quilibr&eacute;,\n";
$body .= "ce qui aide &agrave; avoir une meilleure digestion et un cycle plus facile.\n";
$body .= "On peut le faire de diff&eacute;rentes fa&ccedil;ons:\n";
$body .= "se gargariser avec de l'eau,\n";
$body .= "chanter Ommmmmmm,\n";
$body .= "chantonner une note qui fait vibrer la gorge...\n";
$body .= "</dd>\n";

$body .= "</dl></div>\n";

echo $body;
?>
