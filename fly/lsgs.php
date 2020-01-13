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
	$body .= "<div class=\"csstab64_cell third\">\n";
		// weather station
		$body .= "<div><img class=\"width\" src=\"http://www.wunderground.com/cgi-bin/wxStationGraphAll?ID=IVALAISS15&amp;type=3&amp;width=500&amp;showsolarradiation=1&amp;showtemp=1&amp;showpressure=1&amp;showwind=1&amp;showwinddir=1&amp;showrain=1\" alt=\"weather station\" /></div>\n";
	$body .= "</div>\n";
//
	// Common
	$body .= "<div class=\"csstab64_cell third left\">\n";
	$body .= commonPreparations($page->UserIsAdmin(), $page->miscInit);
	$body .= "</div>\n";

$body .= "</div>\n";
$body .= "</div>\n";

	//// gibloux webcam
	$body .= "<div class=\"wide\">\n";
	$body .= "<a target=\"_blank\" href=\"https://sionairport.roundshot.com/\">\n";
	$body .= "<img src=\"https://www.caboulis.ch/sion.jpg\" alt=\"WebCam\" />\n";
	$body .= "</a>\n";
	$body .= "<br />\n";
	$body .= "<div class=\"left\">\n";
	$body .= "<table>\n";
	$body .= "<tr><th>Location</th><th>Altitude [ft]</th><th>Distance [NM]</th></tr>\n";
	$body .= "<tr><td><b>Veysonnaz</b>                        </td><td>4200</td><td>&nbsp;</td></tr>\n";
	//$body .= "<tr><td><b>Bulle</b>                          </td><td>2500</td><td>3.2</td></tr>\n";
	//$body .= "<tr><td><b>Gruy&egrave;re leftmost hill</b>   </td><td>2800</td><td>6.8</td></tr>\n";
	//$body .= "<tr><td><b>1st summit foreground</b>          </td><td>5000</td><td>8.2</td></tr>\n";
	//$body .= "<tr><td><b>2nd summit foreground</b>          </td><td>5300</td><td>8.0</td></tr>\n";
	//$body .= "<tr><td><b>Hill in front of Mol&eacute;son</b></td><td>4600</td><td>6.4</td></tr>\n";
	//$body .= "<tr><td><b>Mol&eacute;son</b>                 </td><td>6600</td><td>8.4</td></tr>\n";
	$body .= "</table>\n";
	$body .= "</div>\n";
	$body .= "</div>\n";
	$body .= "<div>&nbsp;</div>\n";
//


//// Finish
echo $body;
unset($page);
?>
