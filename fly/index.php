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

	//// columns of my stuff
	$body .= "<div class=\"wide\">\n";
		//// 1st column
		$navcount = $page->DB_GetCount("NavList");
		$body .= "<div class=\"column quarter\">\n";
		$body .= "<ul>\n";
		$body .= "<li>\n";
		$body .= "<a href=\"PAX.php?language=francais\" title=\"Information for passengers\">Informations pour mes passagers</a>\n";
		$body .= "&nbsp;(" . RenderDoodle() . ")\n";
		$body .= "</li>\n";
		$body .= "<li><a href=\"logbook.php\">My flight logbook</a></li>\n";
		$body .= "<li><a href=\"NavList.php\">My navigations</a>&nbsp;($navcount)</li>\n";
		$body .= "</ul>\n";
		$body .= "</div>\n";
	//
		//// 2nd column
		$body .= "<div class=\"column quarter left\">\n";
		$body .= "My PDF/checklists:\n";
		$body .= "<ul>\n";
		$body .= "<li><a href=\"nav/navTemplate.pdf\" target=\"_blank\">Navigation plan</a></li>\n";
		$body .= "<li><a href=\"pdf\">other PDFs (checklists, road map...)</a>\n";
		//$body .= "<li><a href=\"pdf/RoadMap.pdf\" target=\"_blank\">Road map</a></li>\n";
		//$body .= "<li><a href=\"pdf/PICnPAX.pdf\" target=\"_blank\">PIC and PAX</a></li>\n";
		//$body .= "<li><a href=\"pdf/katana.pdf\" target=\"_blank\">Diamond DV20 Katana (HB-SDI)</a></li>\n";
		//$body .= "<li><a href=\"pdf/archer2.pdf\" target=\"_blank\">Piper PA28-181 Archer II (HB-PMR)</a></li>\n";
		//$body .= "<li><a href=\"pdf/dr400_160b.pdf\" target=\"_blank\">Robin DR400-160B (HB-KFQ HB-KEX)</a></li>\n";
		//$body .= "<li><a href=\"pdf/c172r.pdf\" target=\"_blank\">Cessna 172R (HB-TEA HB-TEB HB-CQR)</a></li>\n";
		//$body .= "<li><a href=\"pdf/c182s.pdf\" target=\"_blank\">Cessna 182S (HB-TDR)</a></li>\n";
		$body .= "</ul>\n";
		$body .= "</div>\n";
	//
		//// 3rd column
		$body .= "<div class=\"column quarter left\">\n";
		$body .= "Flight preparations:\n";
		$body .= "<ul>\n";
		$body .= "<li><a href=\"lsge.php\" title=\"LSGE\">LSGE</a></li>\n";
		$body .= "<li><a href=\"lsgs.php\" title=\"LSGS\">LSGS</a></li>\n";
		$body .= "</ul>\n";
		$body .= "</div>\n";
	// Closing div
	$body .= "</div>\n";
//
	//// Body with external links
	$body .= "<div class=\"wide links\">\n";
	//
		$body .= "<div class=\"column half\">\n";
		$body .= "<ul>\n";
		$body .= "<li><a target=\"_blank\" href=\"http://seaplanes.ch/\">seaplanes.ch</a></li>\n";
		$body .= "<li><a target=\"_blank\" href=\"http://www.sust.admin.ch/fr/index.html\">SESE&nbsp;-&nbsp;SUST&nbsp;-&nbsp;STSB</a></li>\n";
		$body .= "<li><a target=\"_blank\" href=\"http://www.bazl.admin.ch/index.html?lang=fr\">OFAC&nbsp;-&nbsp;BAZL&nbsp;-&nbsp;FOCA</a></li>\n";
		$body .= "</ul>\n";
		$body .= "</div>\n";
	//
		//// 3rd column
		$body .= "<div class=\"column half\">\n";
		$body .= "<ul>\n";
		//
		$body .= "<li><a target=\"_blank\" href=\"http://www.airplane-pictures.net/photo/333863/mm7288-italy-air-force-eurofighter-typhoon-s/\">airplane pictures</a></li>\n";
		$body .= "<li><a target=\"_blank\" href=\"http://www.flightradar24.com/\">FlightRadar24</a></li>\n";
		$body .= "<li><a target=\"_blank\" href=\"http://planefinder.net/\">PlaneFinder</a></li>\n";
		$body .= "<li><a target=\"_blank\" href=\"http://www.pyrochta.ch/\">Pyrochta</a></li>\n";
		//
		$body .= "</ul>\n";
		$body .= "</div>\n";
	//
	//
	$body .= "<div>&nbsp;</div>\n";
	$body .= "</div>";
//

$page->show($body);
unset($page);
?>
