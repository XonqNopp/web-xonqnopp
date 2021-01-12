<?php
/* TODO:
 */
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
// debug
//$page->initHTML();
//$page->LogLevelUp(6);
// CSS paths
$page->CSS_ppJump();
//$page->CSS_ppWing();
// init body
$body = "";

// Shortcuts
$rf = "Registre Foncier";
$proprio = "propri&eacute;taire";
$arch = "architecte";
$entr = "entrepreneur";
$arcent = "$arch/$entr";
$pret = "pr&ecirc;t";
$hyp = "hypoth&egrave;que";
$interets = "int&eacute;r&ecirc;ts";
$impot = "imp&ocirc;t";
$impots = "imp&ocirc;ts";

function getTitle($title, $level = 2) {
	$ascii = $title;
	$string = "";
	$string .= "<!-- H$level $title -->\n";
	$string .= "<h$level id=\"$ascii\">";
	$string .= "$title&nbsp;";
	$string .= "<a class=\"titleAnchor\" href=\"#$ascii\">#</a>";
	$string .= "</h$level>";
	$string .= "\n";
	return $string;
}

function getLink($url) {
	return "<a target=\"_blank\" href=\"$url\">$url</a>";
}

function lili($content) {
	return "<li>$content</li>\n";
}


// GoHome
$gohome = new stdClass();
$gohome->rootpage = "..";
$body .= $page->GoHome($gohome);
// Set title and hot booty
$body .= $page->SetTitle("Je deviens propri&eacute;taire");// before HotBooty
$page->HotBooty();

$body .= "<p>Petit r&eacute;sum&eacute; <b>subjectif</b> de:\n";
$body .= "<i>Je deviens propri&eacute;taire</i>,\n";
$body .= "Ellen Weigand,\n";
$body .= "aux Editions Tout compte fait (2015).</p>\n";


$body .= "<div class=\"framed\">\n";
$body .= "<div style=\"font-weight: 700\">Liens utiles:</div>\n";
$body .= "<ul>\n";
$body .= "<li>" . getLink("http://fri.ch") . "</li>\n";
$body .= "<li>" . getLink("http://vermoegenszentrum.ch") . "</li>\n";
//$body .= "<li>" . getLink("http://") . "</li>\n";
//$body .= "<li>" . getLink("http://") . "</li>\n";
//$body .= "<li>" . getLink("http://") . "</li>\n";
//$body .= "<li>" . getLink("http://") . "</li>\n";
//$body .= "<li>" . getLink("http://") . "</li>\n";
$body .= "</ul>\n";
$body .= "</div>\n";


	// Intro
	$body .= getTitle("Introduction");
	$body .= "<p>(Rien de notable, mais gard&eacute;e seulement pour la coh&eacute;rence de la num&eacute;rotation des chapitres.)</p>\n";
//
	// Financement
	$body .= getTitle("Financement");
	$body .= "<div>\n";
	$body .= "<p>Futur $proprio doit obtenir un $pret hypoth&eacute;caire en mettant sa maison en gage.\n";
	$body .= "Il doit apporter 20% de fonds propres, l'$hyp ne couvrant pas l'entier du bien.\n";
	$body .= "De plus, les charges du bien ne doivent pas d&eacute;passer le tiers du revenu brut du $proprio (oui, brut; certaines banquent chipotent, faire jouer la concurrence).\n";
	$body .= "L'$hyp ne peut pas &ecirc;tre plus que le 2/3 du prix du bien apres 15 ans.\n";
	$body .= "Il faut donc amortir une partie de l'hyp.\n";
	$body .= "L'amortissement d&eacute;bute g&eacute;n&eacute;ralement 1 an apre&egrave;s avoir re&ccedil;u le cr&eacute;dit et se fait par paiements r&eacute;guliers..</p>\n";

	$body .= "<p>Pour v&eacute;rifier la capacit&eacute; financi&egrave;re du futur $proprio (pour comparer avec le tiers du revenu brut),\n";
	$body .= "les banques prennent en compte:</p>\n";

	$body .= "<ul>\n";
	$body .= "<li>Les $interets de l'$hyp en se basant sur un taux de 5% du cr&eacute;dit par an</li>\n";
	$body .= "<li>L'amortissement pour ramener l'$hyp au 2/3 de la valeur du bien apr&egrave;s 15 ans</li>\n";
	$body .= "<li>L'entretien du bien, en comptant 1% de la valeur du bien par an</li>\n";
	$body .= "</ul>\n";

	$body .= "<p>En plus de tout cela, il faut pr&eacute;voir environ 5% de frais d'acquisition en plus\n";
	$body .= "(frais de notaire, $RF, taxes, $impots...).</p>\n";

	$body .= "<p>Une fois le bien acquis, il faut aussi pr&eacute;voir environ 3% de la valeur de bien annuellement pour l'entretien et les r&eacute;parations.</p>\n";

	$body .= "<p>La banque se base sur l'estimation du bien la plus basse.\n";
	$body .= "Si le vendeur en demande plus, le $proprio devra mettre la diff&eacute;rence en fonds propres.</p>\n";

	$body .= "<p>Les fonds propres peuvent provenir de diff&eacute;rentes fa&ccedil;ons:</p>\n";
	$body .= "<ul>\n";
	$body .= "<li>cash</li>\n";
	$body .= "</ul>\n";



echo $body;

//// Finish
unset($page);
?>
