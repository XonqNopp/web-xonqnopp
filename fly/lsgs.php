<?php
require("../functions/page_helper.php");
require("homebase.php");

$page = new PhPage("..");


$title = "LSGS: Sion";


	// Infos
	$infos = "<li><a target=\"_blank\" href=\"http://app.aviator.club/\">LSGS app</a>";
	// if($page->loginHelper->userIsAdmin()) {
	// 	$infos .= ":&nbsp;<tt>{$page->miscInit->lsgsLogin}</tt>";
	// }
	$infos .= "</li>\n";

	$infos .= "<li><a target=\"_blank\" href=\"http://gvm-sion.ch/\">GVM Sion</a>";
	if($page->loginHelper->userIsAdmin()) {
		$infos .= ":&nbsp;<tt>{$page->miscInit->lsgsDoor}</tt>";
	}
	$infos .= "</li>\n";

	$infos .= "<li>Sion handling:<br />\n";
	$infos .= "131.475MHz<br />\n";
	$infos .= $page->bodyHelper->tel("+41273290600");
	$infos .= "</li>\n";

	$infos .= "<li>Sion ATIS:<br />\n";
	$infos .= "130.630MHz<br />\n";
	$infos .= $page->bodyHelper->tel("+41224174080");
	$infos .= "</li>\n";

	$infos .= "<li>\n";
	$infos .= "<a target=\"_blank\" href=\"http://fgo.ch\">LSTA Raron</a>\n";
	$infos .= "&nbsp;-&nbsp;\n";
	$infos .= "<a target=\"_blank\" href=\"https://fgo.ch/clubdesk/www?p=1000002\">PPR</a>\n";
	$infos .= "</li>\n";
//
	// Webcam airport
	$webcamAirport .= "<a href=\"https://www.air-zermatt.ch/wordpress/en/webcam/\" target=\"_blank\">\n";
	$webcamAirport .= "<img class=\"width\" title=\"LSTA\" alt=\"LSTA\" src=\"https://www.air-zermatt.ch/webcam/Richtung_Goms.jpg\" />\n";
	$webcamAirport .= "<br />\n";
	$webcamAirport .= "<img class=\"width\" title=\"LSTA\" alt=\"LSTA\" src=\"https://www.air-zermatt.ch/webcam/Heliport2.jpg\" />\n";
	$webcamAirport .= "<br />\n";
	$webcamAirport .= "<img class=\"width\" title=\"LSTA\" alt=\"LSTA\" src=\"https://www.air-zermatt.ch/webcam/Heliport1.jpg\" />\n";
	$webcamAirport .= "<br />\n";
	$webcamAirport .= "<img class=\"width\" title=\"LSTA\" alt=\"LSTA\" src=\"https://www.air-zermatt.ch/webcam/Richtung_Sion.jpg\" />\n";
	$webcamAirport .= "</a>\n";

$wunderground = "IVALAISS15";

	// Webcam area
	$webcamArea = "<a target=\"_blank\" href=\"https://sionairport.roundshot.com/\">WebCam Sion airport</a>\n";
	$webcamArea .= "<br />\n";

	$webcamArea .= "Veysonnaz 4200ft:\n";
	$webcamArea .= "<br />\n";
	$webcamArea .= "<img src=\"https://www.caboulis.ch/sion.jpg\" alt=\"WebCam Veysonnaz\" />\n";


homebases($title, $infos, $webcamAirport, $wunderground, $webcamArea);
?>
