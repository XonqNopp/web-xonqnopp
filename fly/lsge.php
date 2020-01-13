<?php
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
require("preparations.php");
$page = new PhPage($rootPath);
//$page->initDB();
//// debug
//$page->initHTML();
//$page->LogLevelUp(6);
//// CSS paths
$page->CSS_ppJump();
//$page->CSS_ppWing();
//// init body
$body = "";


//$skybriefingLogin .= "<tt>lsge0927@hotmail.com</tt><br /><tt>LSge0927</tt>";


//// GoHome
$gohome = new stdClass();
$body .= $page->GoHome($gohome);
//// Set title and hot booty
$body .= $page->SetTitle("LSGE: Ecuvillens");// before HotBooty
$page->HotBooty();

$body .= "<div class=\"csstab64_table links\">\n";
$body .= "<div class=\"csstab64_row\">\n";

	// Infos & webcam
	$body .= "<div class=\"csstab64_cell\">\n";
		// Infos
		$body .= "<div>\n";
		$body .= "<ul>\n";

		$body .= "<li><a target=\"_blank\" href=\"http://www.resnet.ch/LSGE/index.asp\">LSGE resair</a></li>\n";

		$body .= "<li><a target=\"_blank\" href=\"http://lsge-flights.azurewebsites.net/LSGE_Login.aspx\">LSGE avis</a>";
		if($page->UserIsAdmin()) {
			$body .= ": Guest - " . $page->miscInit->lsgeAvis;
		}
		$body .= "</li>\n";

		$body .= "<li><a target=\"_blank\" href=\"http://gvme.ch/\">GVME</a></li>\n";

		if($page->UserIsAdmin()) {
			$body .= "<li>Code cl&eacute;: " . $page->miscInit->lsgeKey . "</li>\n";
		}

		$body .= "<li>Bern ATIS:<br />\n";
		$body .= "125.130MHz<br />\n";
		$body .= "<a href=\"tel:+41224174076\">+41&nbsp;22&nbsp;417&nbsp;40&nbsp;76</a></li>\n";

		$body .= "<li><a target=\"_blank\" href=\"http://www.fribourg-voltige.ch/Activlites.htm\">Fribourg voltige</a></li>\n";

		$body .= "</ul>\n";
		$body .= "</div>\n";
	//
		// LSGE webcam
		$body .= "<div>\n";
		$body .= "<a href=\"http://www.aerodrome-ecuvillens.ch/index.php?page=meteo_webcam.htm\" target=\"_blank\">\n";
		$body .= "<img class=\"width\" title=\"LSGE\" alt=\"LSGE\" src=\"http://www.aerodrome-ecuvillens.ch/webcam/webcam_rwy28.jpg\" />\n";
		$body .= "<br />\n";
		$body .= "<img class=\"width\" title=\"LSGE\" alt=\"LSGE\" src=\"http://www.aerodrome-ecuvillens.ch/webcam/webcam_rwy10.jpg\" />\n";
		$body .= "</a>\n";
		$body .= "</div>\n";
	$body .= "</div>\n";
//
	// Weather station
	$body .= "<div class=\"csstab64_cell\">\n";
		// LSGE weather station
		$body .= "<div><img class=\"width\" src=\"http://www.wunderground.com/cgi-bin/wxStationGraphAll?ID=IFREIBUR2&amp;type=3&amp;width=500&amp;showsolarradiation=1&amp;showtemp=1&amp;showpressure=1&amp;showwind=1&amp;showwinddir=1&amp;showrain=1\" alt=\"weather station\" /></div>\n";
	$body .= "</div>\n";
//
	// Common
	$body .= "<div class=\"csstab64_cell left\">\n";
	$body .= commonPreparations($page->UserIsAdmin(), $page->miscInit);
	$body .= "</div>\n";

$body .= "</div>\n";
$body .= "</div>\n";

	// gibloux webcam
	$body .= "<div class=\"wide\">\n";

	$body .= "<a target=\"_blank\" href=\"https://montgibloux.roundshot.com/\" title=\"WebCam Gibloux\">\n";
	$body .= "WebCam Gibloux 4000ft<br />\n";
	$body .= "La Berra 5600ft 6NM<br />\n";
	$body .= "Bulle 3NM<br />\n";
	$body .= "Mol&eacute;son 6600ft 8NM\n";
	$body .= "</a>\n";

	$body .= "</div>\n";

// Do not have text glued at bottom
$body .= "<div>&nbsp;</div>\n";


//// Finish
echo $body;
unset($page);
?>
