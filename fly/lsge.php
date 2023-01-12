<?php
require_once("../functions/page_helper.php");

$page = new PhPage("..");

require_once("homebase.php");

//$skybriefingLogin .= "<tt>lsge0927@hotmail.com</tt><br /><tt>LSge0927</tt>";

$title = "LSGE: Ecuvillens";


    // Infos
    $infos = $page->bodyBuilder->liAnchor("http://lsge.airmanager.ch", "LSGE AirManager");

    //$infos .= "<li><a target=\"_blank\" href=\"http://lsge-flights.azurewebsites.net/LSGE_Login.aspx\">LSGE avis</a>";
    //if($page->loginHelper->userIsAdmin()) {
    //    $infos .= ": Guest - " . $page->miscInit->lsgeAvis;
    //}
    //$infos .= "</li>\n";

    $infos .= $page->bodyBuilder->liAnchor("http://gvme.ch/", "GVM Ecuvillens");

    if($page->loginHelper->userIsAdmin()) {
        $infos .= "<li>Code cl&eacute;: {$page->miscInit->lsgeKey}</li>\n";
    }

    $infos .= "<li>Bern ATIS:<br />\n";
    $infos .= "125.130MHz<br />\n";
    $infos .= $page->bodyBuilder->tel("+41224174076");
    $infos .= "</li>\n";

    $infos .= $page->bodyBuilder->liAnchor("http://www.fribourg-voltige.ch/", "Fribourg voltige");
//
    // Webcam airport
    $webcamAirportImg = $page->bodyBuilder->img("http://www.aerodrome-ecuvillens.ch/webcam/webcam_rwy27.jpg", "LSGE webcam 27", "width");
    $webcamAirportImg .= "<br />\n";
    $webcamAirportImg .= $page->bodyBuilder->img("http://www.aerodrome-ecuvillens.ch/webcam/webcam_rwy09.jpg", "LSGE webcam 09", "width");
    $webcamAirport = $page->bodyBuilder->anchor("http://www.aerodrome-ecuvillens.ch/index.php?page=./meteo_webcam_2021.htm", $webcamAirportImg, "LSGE webcam");

$wunderground = "IFREIBUR2";

    // Webcam area
    $webcamArea = $page->bodyBuilder->anchor(
        "https://montgibloux.roundshot.com/",
        "WebCam Gibloux 4348ft - La Berra 5600ft 6NM - Bulle 3NM - Mol&eacute;son 6600ft 8NM"
    );

    $webcamArea .= "<br/>\n";
    $webcamArea .= "Depuis LSGE: Neyruz 1.5km, Arconciel 3.5km (SVFR), Villars-sur-Glane 5.0km (VFR), Gibloux 8.3km (CAVOK).\n";


echo homebase($page, $title, $infos, $webcamAirport, $wunderground, $webcamArea);
?>
