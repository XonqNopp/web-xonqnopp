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
require("doodle.php");
$page = new PhPage($rootPath);
//$page->LogLevelUp(6);
$page->initDB();

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

	$body .= "<div class=\"wide left\">\n";
		$body .= "<ul>\n";
		$body .= "<li>\n";
		// PAX
		$body .= "<a href=\"PAX.php?language=francais\" title=\"Information for passengers\">Informations pour mes passagers</a>\n";
		$body .= "&nbsp;(" . RenderDoodle() . ")\n";
		$body .= "</li>\n";
		// logbook
		$body .= "<li><a href=\"logbook.php\">my flight logbook</a></li>\n";
		// nav
		$navcount = $page->DB_GetCount("NavList");
		$body .= "<li><a href=\"NavList.php\">my navigations</a>&nbsp;($navcount)</li>\n";
		// PDF
		$body .= "<li><a href=\"pdf\">my PDF/checklists</a></li>\n";
		$body .= "</ul>\n";
		// Preparation
		$body .= "Flight preparations:\n";
		$body .= "<ul>\n";
		$body .= "<li><a href=\"lsge.php\" title=\"LSGE\">LSGE</a></li>\n";
		$body .= "<li><a href=\"lsgs.php\" title=\"LSGS\">LSGS</a></li>\n";
		$body .= "</ul>\n";
		// misc
		$body .= "Misc:\n";
		$body .= "<ul>\n";
		$body .= "<li><a target=\"_blank\" href=\"http://www.sust.admin.ch/fr/index.html\">SESE&nbsp;-&nbsp;SUST&nbsp;-&nbsp;STSB</a></li>\n";
		$body .= "<li><a target=\"_blank\" href=\"http://www.bazl.admin.ch/index.html?lang=fr\">OFAC&nbsp;-&nbsp;BAZL&nbsp;-&nbsp;FOCA</a></li>\n";
		$body .= "<li><a target=\"_blank\" href=\"http://seaplanes.ch/\">seaplanes.ch</a></li>\n";
		$body .= "<li><a target=\"_blank\" href=\"http://www.flightradar24.com/\">FlightRadar24</a></li>\n";
		$body .= "<li><a target=\"_blank\" href=\"http://planefinder.net/\">PlaneFinder</a></li>\n";
		$body .= "</ul>\n";
	// Closing div
	$body .= "</div>\n";
//
$page->show($body);
unset($page);
?>
