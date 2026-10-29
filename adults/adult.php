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
$body .= $page->htmlHelper->setTitle("Devenir adulte");  // before HotBooty
$page->htmlHelper->hotBooty();


$body .= instagramSources(
    array(
        "corehealthway",
        "gege_en_vadrouille",
        "information.bienetre",
        "intimit_eve",
    )
);


$body .= "<p>Quand on n'arrive pas &agrave; dormir, on peut cligner des yeux lentement toutes les 3 secondes pendant 1 minute.\n";
$body .= "En ralentissant volontairement les clignements, cela envoie au cerveau un signal d'apaisement.\n";
$body .= "Il comprend que l'on est pr&ecirc;t &agrave; s'endormir.\n";
$body .= "</p>\n";  // information.bienetre

$body .= "<p>En cas d'anxi&eacute;t&eacute;, pose ta main sur ton coeur et fais de longues et profondes respirations pendant 1 minute.\n";
$body .= "La respiration lente active le syst&egrave;me nerveux parasympatique et calme le rythme cardiaque.\n";
$body .= "La main sur le coeur amplifie l'effet en r&eacute;duisant le cortisol (???).\n";
$body .= "</p>\n";  // information.bienetre

$body .= "<p>Si le matin tu te sens un peu mou, brosse-toi les dents avec l'autre main.\n";
$body .= "Faire une t&acirc;che habituelle avec la main non-dominante force le cerveau &agrave; sortir du pilote automatique et demande plus de concentration.\n";
$body .= "</p>\n";  // information.bienetre

$body .= "<p>Quand le nez est bouch&eacute;, poser un gla&ccedil;on contre le palais et le tenir avec la langue pendant 30 secondes.\n";
$body .= "Le nez bouch&eacute; est caus&eacute; par des vaisseaux sanguins enflamm&eacute;s.\n";
$body .= "Le froid provoque une contraction des vaisseaux sanguins et lib&egrave;re le passage.\n";
$body .= "</p>\n";  // information.bienetre

//$body .= "<p>Si tu es nerveux, pince ton nez, ferme ta bouche et essaie doucement d'expirer pendant 10s.\n";
//$body .= "Cette technique stimule le nerf vague et coupe la r&eacute;ponse 'fight or flight'.\n";
//$body .= "</p>\n";  // information.bienetre

$body .= "<p>Si tu n'as pas d'&eacute;nergie, plonge ton visage dans une bassine d'eau froide pendant 30s.\n";
$body .= "Le froid active le r&eacute;flexe mammalien de plong&eacute;e et stimule le nerf vague.\n";
$body .= "Le flux sanguin se redirige vers le cerveau et le coeur, et le niveau d'alerte monte.\n";
$body .= "</p>\n";  // information.bienetre

$body .= "<p>Un bon rythme de journ&eacute;e consiste &agrave; travailler t&ocirc;t le matin,\n";
$body .= "se reposer pendant les heures chaudes, et travailler en fin d'apr&egrave;s-midi.\n";
$body .= "Cela est d'autant plus important en p&eacute;riode de canicule.\n";
$body .= "</p>\n";  // gege_en_vadrouille

$body .= "<p>Passer un gla&ccedil;on sur la peau et imm&eacute;diatement souffler de l'air chaud au m&ecirc;me endroit fait un choc thermique qui sature le syst&egrave;me nerveux.\n";
$body .= "Le cerveau bug instantan&eacute;ment, arr&ecirc;te de cogiter et se reconnecte au corps &agrave; 100%.\n";
$body .= "Cette technique peut aussi s'utiliser au lit &agrave; deux pour l&acirc;cher prise.\n";
$body .= "</p>\n";  // intimit_eve

$body .= "<p>Tirer la langue 40s aide &agrave; diminuer les tensions dans la nuque.\n";
$body .= "La langue est connect&eacute;e &agrave; l'os hyo&iuml;de o&ugrave; sont ancr&eacute;es les principaux muscles responsables des tensions dans la nuque.\n";
$body .= "Tirer la langue le plus loin possible cr&eacute;e une distraction m&eacute;canique\n";
$body .= "qui force les muscles &agrave; s'allonger et d&eacute;compresser le canal du nerf vague.\n";
$body .= "La dur&eacute;e est importante: moins de 30s, les muscles se remettent dans la m&ecirc;me tension qu'avant.\n";
$body .= "A partir de 40s, les muscles se recalibrent &agrave; la tension (plus basse).\n";
$body .= "Il est important d'&ecirc;tre dans une position o&ugrave; on peut d&eacute;tendre ses &eacute;paules pour faire &ccedil;a.\n";
$body .= "On ouvre grand la bouche et tire la langue le plus loin possible tout en respirant par le nez.\n";
$body .= "Id&eacute;aelement on le fait 3 fois de suite avec des pauses de 60s entre deux.\n";
$body .= "Le matin est mieux car on est avant les tensions du jour, mais on peut aussi le faire le soir avant d'aller au lit.\n";
$body .= "</p>\n";  // corehealthway

echo $body;
?>
