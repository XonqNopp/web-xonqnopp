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



//// GoHome
$gohome = new stdClass();
$body .= $page->GoHome($gohome);
//// Set title and hot booty
$body .= $page->SetTitle("LSGS: Sion");// before HotBooty
$page->HotBooty();

$body .= "<div class=\"csstab64_table links\">\n";
$body .= "<div class=\"csstab64_row\">\n";

	// Infos & webcam
	$body .= "<div class=\"csstab64_cell third\">\n";
		// Infos
		$body .= "<div>\n";
		$body .= "<ul>\n";
		$body .= "<li><a target=\"_blank\" href=\"http://www.resnet.ch/LSGS-GVM/index.asp\">LSGS-GVM resair</a>";
		if($page->UserIsAdmin()) {
			$body .= ":&nbsp;<tt>" . $page->miscInit->lsgsLogin . "</tt>";
		}
		$body .= "</li>\n";

		$body .= "<li><a target=\"_blank\" href=\"http://gvm-sion.ch/\">GVM Sion</a>";
		if($page->UserIsAdmin()) {
			$body .= ":&nbsp;<tt>" . $page->miscInit->lsgsDoor . "</tt>";
		}
		$body .= "</li>\n";

		$body .= "<li>Sion handling:<br />\n";
		$body .= "131.475MHz<br />\n";
		$body .= "<a href=\"tel:+41273290600\">+41&nbsp;27&nbsp;329&nbsp;06&nbsp;00</a></li>\n";

		$body .= "<li>Sion ATIS:<br />\n";
		$body .= "130.630MHz<br />\n";
		$body .= "<a href=\"tel:+41224174080\">+41&nbsp;22&nbsp;417&nbsp;40&nbsp;80</a></li>\n";

		$body .= "<li>\n";
		$body .= "<a target=\"_blank\" href=\"http://fgo.ch\">LSTA Raron</a>\n";
		$body .= "&nbsp;-&nbsp;\n";
		$body .= "<a target=\"_blank\" href=\"https://fgo.ch/clubdesk/www?p=1000002\">PPR</a>\n";
		$body .= "</li>\n";
		$body .= "</ul>\n";
		$body .= "</div>\n";
	//
		// webcam
		$body .= "<div>\n";
		$body .= "<a href=\"https://www.air-zermatt.ch/wordpress/en/webcam/\" target=\"_blank\">\n";
		$body .= "<img class=\"width\" title=\"LSTA\" alt=\"LSTA\" src=\"https://www.air-zermatt.ch/webcam/Richtung_Goms.jpg\" />\n";
		$body .= "<br />\n";
		$body .= "<img class=\"width\" title=\"LSTA\" alt=\"LSTA\" src=\"https://www.air-zermatt.ch/webcam/Heliport2.jpg\" />\n";
		$body .= "<br />\n";
		$body .= "<img class=\"width\" title=\"LSTA\" alt=\"LSTA\" src=\"https://www.air-zermatt.ch/webcam/Heliport1.jpg\" />\n";
		$body .= "<br />\n";
		$body .= "<img class=\"width\" title=\"LSTA\" alt=\"LSTA\" src=\"https://www.air-zermatt.ch/webcam/Richtung_Sion.jpg\" />\n";
		$body .= "</a>\n";
		$body .= "</div>\n";
	$body .= "</div>\n";
//
	// Weather station
	$station = "IVALAISS15";
	$body .= "<div class=\"csstab64_cell third\">\n";
	$body .= "<a target=\"_blank\" href=\"https://www.wunderground.com/dashboard/pws/$station\">\n";
	$body .= "<img class=\"width\" src=\"http://www.wunderground.com/cgi-bin/wxStationGraphAll?ID=$station&amp;type=3&amp;width=500&amp;showsolarradiation=1&amp;showtemp=1&amp;showpressure=1&amp;showwind=1&amp;showwinddir=1&amp;showrain=1\" alt=\"weather station\" />\n";
	$body .= "</a>\n";
	$body .= "</div>\n";
//
	// Common
	$body .= "<div class=\"csstab64_cell third left\">\n";
	$body .= commonPreparations($page->UserIsAdmin(), $page->miscInit);
	$body .= "</div>\n";

$body .= "</div>\n";
$body .= "</div>\n";

	// webcam
	$body .= "<div class=\"wide\">\n";

	$body .= "<a target=\"_blank\" href=\"https://sionairport.roundshot.com/\">WebCam Sion airport</a>\n";
	$body .= "<br />\n";

	$body .= "Veysonnaz 4200ft:\n";
	$body .= "<br />\n";
	$body .= "<img src=\"https://www.caboulis.ch/sion.jpg\" alt=\"WebCam Veysonnaz\" />\n";

	$body .= "</div>\n";

// Do not have text glued at bottom
$body .= "<div>&nbsp;</div>\n";


//// Finish
echo $body;
unset($page);
?>
