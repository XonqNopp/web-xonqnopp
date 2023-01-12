<?php
require("../functions/page_helper.php");
require("homebase.php");

$page = new PhPage("..");

//$skybriefingLogin .= "<tt>lsge0927@hotmail.com</tt><br /><tt>LSge0927</tt>";

$title = "LSGE: Ecuvillens";


	// Infos
	$infos = "<li><a target=\"_blank\" href=\"http://www.resair.ch/LSGE/index.asp\">LSGE resair</a></li>\n";

	$infos .= "<li><a target=\"_blank\" href=\"http://lsge-flights.azurewebsites.net/LSGE_Login.aspx\">LSGE avis</a>";
	//if($page->loginHelper->userIsAdmin()) {
	//	$infos .= ": Guest - " . $page->miscInit->lsgeAvis;
	//}
	$infos .= "</li>\n";

	$infos .= "<li><a target=\"_blank\" href=\"http://gvme.ch/\">GVME</a></li>\n";

	if($page->loginHelper->userIsAdmin()) {
		$infos .= "<li>Code cl&eacute;: {$page->miscInit->lsgeKey}</li>\n";
	}

	$infos .= "<li>Bern ATIS:<br />\n";
	$infos .= "125.130MHz<br />\n";
	$infos .= $page->bodyHelper->tel("+41224174076");
	$infos .= "</li>\n";

	$infos .= "<li><a target=\"_blank\" href=\"http://www.fribourg-voltige.ch/Activlites.htm\">Fribourg voltige</a></li>\n";
//
	// Webcam airport
	$webcamAirport = "<a href=\"http://www.aerodrome-ecuvillens.ch/index.php?page=meteo_webcam.htm\" target=\"_blank\">\n";
	$webcamAirport .= "<img class=\"width\" title=\"LSGE\" alt=\"LSGE\" src=\"http://www.aerodrome-ecuvillens.ch/webcam/webcam_rwy27.jpg\" />\n";
	$webcamAirport .= "<br />\n";
	$webcamAirport .= "<img class=\"width\" title=\"LSGE\" alt=\"LSGE\" src=\"http://www.aerodrome-ecuvillens.ch/webcam/webcam_rwy09.jpg\" />\n";
	$webcamAirport .= "</a>\n";

$wunderground = "IFREIBUR2";

	// Webcam area
	$webcamArea = "<a target=\"_blank\" href=\"https://montgibloux.roundshot.com/\">\n";
	$webcamArea .= "WebCam Gibloux 4348ft\n";
	$webcamArea .= "- La Berra 5600ft 6NM\n";
	$webcamArea .= "- Bulle 3NM\n";
	$webcamArea .= "- Mol&eacute;son 6600ft 8NM\n";
	$webcamArea .= "</a>\n";

	$webcamArea .= "<br/>\n";
	$webcamArea .= "Depuis LSGE: Neyruz 1.5km, Arconciel 3.5km (SVFR), Villars-sur-Glane 5.0km (VFR), Gibloux 8.3km (CAVOK).\n";


homebases($title, $infos, $webcamAirport, $wunderground, $webcamArea);
?>
