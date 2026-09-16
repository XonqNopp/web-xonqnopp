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
        "information.bienetre"
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

$body .= "<p>Si tu n'as pas d'&eacute;nergie,\n";  // TODO IWASHERE
$body .= "</p>\n";  // information.bienetre

echo $body;
?>
