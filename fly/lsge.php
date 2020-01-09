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
			$body .= ": Guest - " . $page->miscIni->lsgeAvis;
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

	//// gibloux webcam
	$body .= "<div class=\"wide\">\n";
	$gibloux = "https://contentdelivery.sbcdc.ch/webcams/gibl/current_view.jpg";
	$body .= "<img src=\"$gibloux\" alt=\"WebCam Gibloux\" />\n";
	$body .= "<br />\n";
	$body .= "<div class=\"left\">\n";
	$body .= "<table>\n";
	$body .= "<tr><th>Location</th><th>Altitude [ft]</th><th>Distance [NM]</th></tr>\n";
	$body .= "<tr><td><b>Gibloux</b>                        </td><td>4000</td><td>&nbsp;</td></tr>\n";
	$body .= "<tr><td><b>Bulle</b>                          </td><td>2500</td><td>3.2</td></tr>\n";
	$body .= "<tr><td><b>Gruy&egrave;re leftmost hill</b>   </td><td>2800</td><td>6.8</td></tr>\n";
	$body .= "<tr><td><b>1st summit foreground</b>          </td><td>5000</td><td>8.2</td></tr>\n";
	$body .= "<tr><td><b>2nd summit foreground</b>          </td><td>5300</td><td>8.0</td></tr>\n";
	$body .= "<tr><td><b>Hill in front of Mol&eacute;son</b></td><td>4600</td><td>6.4</td></tr>\n";
	$body .= "<tr><td><b>Mol&eacute;son</b>                 </td><td>6600</td><td>8.4</td></tr>\n";
	$body .= "</table>\n";
	$body .= "</div>\n";
	$body .= "</div>\n";
	$body .= "<div>&nbsp;</div>\n";
//


//// Finish
echo $body;
unset($page);
?>
