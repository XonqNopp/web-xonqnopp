<?php
require_once("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);

// debug
//$page->htmlHelper->init();
//$page->logger->levelUp(6);

$page->bobbyTable->init();
//$userIsAdmin = $page->loginHelper->userIsAdmin();

$body = "";


$body = $page->bodyBuilder->goHome("..");
// Set title and hot booty
$body .= $page->htmlHelper->setTitle("Apprendre &agrave; &ecirc;tre parent");  // before HotBooty
$page->htmlHelper->hotBooty();


function liInstagram($username) {
    global $page;
    return $page->bodyBuilder->liAnchor("https://www.instagram.com/$username", "<tt>@$username</tt>");
}


$body .= "<p>Principales sources d'inspiration sur instagram:</p>\n";
$body .= "<div><ul>\n";
$body .= liInstagram("etunjour_cavamieux");
$body .= liInstagram("thepositiveparenting");
$body .= liInstagram("nurturedfirst");
$body .= liInstagram("littlebunbao");
$body .= liInstagram("cynthiaarscott");
$body .= liInstagram("goldmindsapp");
$body .= liInstagram("dralexbeyondbehavior");
$body .= liInstagram("thecoolmama.club");
$body .= liInstagram("yestess.familles");
$body .= "</ul></div>\n";

$body .= "<p>Les enfants connaissent le nom de chaque partie du corps et le nom des parties intimes.\n";
$body .= 'On ne dit pas "zizi" ou "n&eacute;n&eacute;" et compagnie.' . "\n";
$body .= "On en parle &agrave; la maison, on n'a pas honte de nommer les parties du corps.\n";
$body .= "Utiliser d'autres termes cr&eacute;e un tabou.\n";
$body .= "</p>\n";

$body .= "<p>Les secrets sont interdits.\n";
$body .= "On peut faire des surprises, mais un adulte qui demande &agrave; un enfant de garder un secret c'est RED FLAG!\n";
$body .= "</p>\n";

$body .= "<p>Un adulte/ado qui demande de l'aide a un enfant (hors vie quotidienne) alors qu'il y a d'autres grandes personnes autour,\n";
$body .= "c'est souvent une technique d'approche des pr&eacute;dateurs.</p>\n";

$body .= "<p>On a des livres qui parlent de consentement.\n";
$body .= "On les lit en famille ou chacun de son c&ocirc;t&eacute;, mais &ccedil;a permet d'ouvrir la discussion.\n";
$body .= "On aime bien offrir ces livres aux copains pour les anniversaires.\n";
$body .= "On trouve aussi ce genre de livres &agrave; la biblioth&egrave;que.\n";
$body .= "</p>\n";

$body .= "<p>Toquer &agrave; la porte de la chambre ou de la salle de bain avant d'entrer, c'est respecter leur intimit&eacute;.\n";
$body .= "Les enfants grandissent ainsi avec cette r&egrave;gle.\n";
$body .= "Ils comprennent que le jour o&ugrave; elle n'est pas respect&eacute;e, il faut se m&eacute;fier.\n";
$body .= "</p>\n";

$body .= "<p>Le constentement, c'est de ne pas toucher le corps de l'autre sans avoir son accord.\n";
$body .= "Cela vaut aussi pour les bisous et les c&acirc;lins.\n";
$body .= "On n'a pas &agrave; se forcer si on en n'a pas envie.\n";
$body .= "Ce n'est pas parce qu'hier l'autre personne avait envie que c'est toujours le cas maintenant.\n";
$body .= "Il y a diff&eacute;rentes fa&ccedil;ons de se dire bonjour avec plus ou moins de contacts corporels,\n";
$body .= "chacun est libre de choisir &agrave; chaque occasion la mani&egrave;re qui lui convient le mieux.\n";
$body .= "</p>\n";

// Consentement aussi pour calin/bisous


echo $body;
?>
