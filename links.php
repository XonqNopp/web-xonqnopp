<?php
require("functions/classPage.php");
$page = new PhPage();
//$page->LogLevelUp(6);
	/*** prepare text ***/
	$annwvyn = "Le Peuple d&#039;Annwvyn";
	$cdj = "Choeur Sainte-C&eacute;cile de Bramois";
	$ski = "Ski-Club Bramois";
	$crpp = "Centre de Recherches en Physique des Plasmas";
	if($page->CheckSessionLang($page->GetFrench())) {
		$title = "Quelques liens externes";
		$aum = "Aum&ocirc;nerie de l&#039;UNIL et de l&#039;EPFL";
		$bb = "Mon";
	} else {
		$title = "Some external links";
		$aum = "EPFL and UNIL&#039;s chaplaincy";
		$bb = "My";
	}
//

$body = "";
$page->CSS_Push("index");
$page->HotBooty();

$body .= "<h1 class=\"ext\">$title</h1>\n";
$body .= $page->Languages();
$body .= $page->GoHome();

$body .= "<div>\n";
$body .= "<ul>\n";

	$body .= "<li><a target=\"_blank\" href=\"http://www.nidji.org/\">nidji.org</a></li>\n";
	$body .= "<li><a target=\"_blank\" href=\"exams/choose.php?which=m\">exams</a></li>\n";
	$body .= "<li><a target=\"_blank\" href=\"http://www.taize.fr/\">Taiz&eacute;</a></li>\n";
	$body .= "<li><a target=\"_blank\" href=\"http://www.phdcomics.com/comics.php\">PhD comics</a></li>\n";
	$body .= "<li><a target=\"_blank\" href=\"http://xkcd.org\">XKCD</a></li>\n";
	$body .= "<li><a target=\"_blank\" href=\"http://lar5.com/cube\">lar5 cube</a></li>\n";
	$body .= "<li><a target=\"_blank\" href=\"http://esolangs.org/wiki/Main_Page\">esolangs</a></li>\n";

$body .= "</ul>\n";
$body .= "</div>\n";
//

/*** DISPLAY ***/
$page->show($body);
unset($page);
?>
