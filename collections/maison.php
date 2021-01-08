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

function getTitle($title, $level = 2) {
	$ascii = str_replace("");
	$string = "";
	$string .= "<!-- H$level $title -->\n";
	$string .= "<a href=\"#$ascii\">";
	$string .= "<h$level id=\"$ascii\">";
	$string .= "$title";
	$string .= "<span class=\"titleAnchor\">&nbsp;#</span>";
	$string .= "</h$level>";
	$string .= "</a>";
	$string .= "\n";
	return $string;
}

function getLink($url) {
	return "<a target=\"_blank\" href=\"$url\">$url</a>";
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
//$body .= "<li>" . getLink("http://") . "</li>\n";
//$body .= "<li>" . getLink("http://") . "</li>\n";
//$body .= "<li>" . getLink("http://") . "</li>\n";
//$body .= "<li>" . getLink("http://") . "</li>\n";
//$body .= "<li>" . getLink("http://") . "</li>\n";
//$body .= "<li>" . getLink("http://") . "</li>\n";
$body .= "</ul>\n";
$body .= "</div>\n";


$body .= getTitle("Introduction");




echo $body;

//// Finish
unset($page);
?>
