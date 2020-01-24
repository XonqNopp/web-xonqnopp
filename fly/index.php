<?php
/*** Created: Thu 2014-05-01 07:47:18 CEST
 ***
 *** TODO:
 *** * subpages for LSGE and LSGS with associated webcams and pics and links
 ***
 ***/
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->LogLevelUp(6);
$page->initDB();
require("preparations.php");

$args = new stdClass();
$args->redirect = "";
$page->LoginCookie($args);

$page->CSS_ppJump();
//$page->CSS_ppWing();// cannot because it screws my display (but why?)

$body = "";
$args = new stdClass();
$args->page = "..";
$body .= $page->GoHome($args);
$body .= $page->SetTitle("Fly!");
$page->HotBooty();

$body .= "<div class=\"csstab64_table left\" style=\"margin-bottom: 2em;\">\n";
$body .= "<div class=\"csstab64_row\">\n";
	// PAX
	$body .= "<div class=\"csstab64_cell flyTitle\">\n";
	$body .= "<a href=\"PAX.php?language=francais\">Informations pour mes passagers</a>\n";
	$body .= "</div>\n";

	// logbook
	$body .= "<div class=\"csstab64_cell flyTitle\">\n";
	$body .= "<a href=\"logbook.php\">My flight logbook</a>\n";
	$body .= "</div>\n";
$body .= "</div>\n";
$body .= "</div>\n";


$body .= "<div class=\"csstab64_table left\">\n";
$body .= "<div class=\"csstab64_row\">\n";
	$body .= "<div class=\"csstab64_cell half\">\n";
		$body .= "<ul>\n";
		// nav
		$navcount = $page->DB_GetCount("NavList");
		$body .= "<li><a href=\"NavList.php\">my navigations</a>&nbsp;($navcount)</li>\n";
		// PDF
		$body .= "<li><a href=\"pdf\">my PDF/checklists</a></li>\n";
		$body .= "<li><a href=\"computer.php\">computer</a></li>\n";
		$body .= "</ul>\n";
	//
		// Preparation
		$body .= "Flight preparations:\n";
		$body .= "<ul>\n";
		$body .= "<li><a href=\"lsge.php\" title=\"LSGE\">LSGE</a></li>\n";
		$body .= "<li><a href=\"lsgs.php\" title=\"LSGS\">LSGS</a></li>\n";
		$body .= "</ul>\n";
	//
		// misc
		$body .= "Misc:\n";
		$body .= "<ul>\n";
		$body .= "<li><a target=\"_blank\" href=\"http://www.sust.admin.ch/fr/index.html\">SESE/SUST</a></li>\n";

		$body .= "<li>\n";
		$body .= "<a target=\"_blank\" href=\"http://www.bazl.admin.ch/index.html?lang=fr\">OFAC/BAZL</a>\n";
		$body .= "&nbsp;-&nbsp;\n";
		$body .= "<a target=\"_blank\" href=\"https://www.bazl.admin.ch/bazl/fr/home/experts/formation-et-licences/pilotes/formulaires.html\">Formulaires AESA, avions a moteur, 60.521 SEP TMG revalidation EASA</a>\n";
		$body .= "</li>\n";

		$body .= "<li><a target=\"_blank\" href=\"http://seaplanes.ch/\">seaplanes.ch</a></li>\n";
		$body .= "<li><a target=\"_blank\" href=\"http://www.flightradar24.com/\">FlightRadar24</a></li>\n";
		$body .= "<li><a target=\"_blank\" href=\"http://planefinder.net/\">PlaneFinder</a></li>\n";
		$body .= "</ul>\n";
	// Closing div
	$body .= "</div>\n";
//
	$body .= "<div class=\"csstab64_cell half\">\n";
	$body .= commonPreparations($page->UserIsAdmin(), $page->miscInit);
	$body .= "</div>\n";

$body .= "</div>\n";
$body .= "</div>\n";

$page->show($body);
unset($page);
?>
