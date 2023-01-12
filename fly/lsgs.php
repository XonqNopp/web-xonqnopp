<?php
require_once("../functions/page_helper.php");

$page = new PhPage("..");

require_once("homebase.php");

$title = "LSGS: Sion";


    // Infos
    $infos = $page->bodyBuilder->liAnchor("http://app.aviator.club/", "LSGS app");

    $infos .= "<li>";
    $infos .= $page->bodyBuilder->anchor("http://gvm-sion.ch/", "GVM Sion");
    if($page->loginHelper->userIsAdmin()) {
        $infos .= ":&nbsp;<tt>{$page->miscInit->lsgsDoor}</tt>";
    }
    $infos .= "</li>\n";

    $infos .= "<li>Sion handling:<br />\n";
    $infos .= "131.475MHz<br />\n";
    $infos .= $page->bodyBuilder->tel("+41273290600");
    $infos .= "</li>\n";

    $infos .= "<li>Sion ATIS:<br />\n";
    $infos .= "130.630MHz<br />\n";
    $infos .= $page->bodyBuilder->tel("+41224174080");
    $infos .= "</li>\n";

    $infos .= "<li>\n";
    $infos .= $page->bodyBuilder->anchor("http://fgo.ch", "LSTA Raron");
    $infos .= "&nbsp;-&nbsp;\n";
    $infos .= $page->bodyBuilder->anchor("https://fgo.ch/clubdesk/www?p=1000002", "PPR");
    $infos .= "</li>\n";
//
    // Webcam airport
    $webcamAirportImg = $page->bodyBuilder->img("https://www.air-zermatt.ch/webcam/Richtung_Goms.jpg", "LSTA Webcam Richtung Goms", "width");
    $webcamAirportImg .= "<br />\n";
    $webcamAirportImg .= $page->bodyBuilder->img("https://www.air-zermatt.ch/webcam/Heliport2.jpg", "LSTA Webcam Heliport2", "width");
    $webcamAirportImg .= "<br />\n";
    $webcamAirportImg .= $page->bodyBuilder->img("https://www.air-zermatt.ch/webcam/Heliport1.jpg", "LSTA Webcam Heliport1", "width");
    $webcamAirportImg .= "<br />\n";
    $webcamAirportImg .= $page->bodyBuilder->img("https://www.air-zermatt.ch/webcam/Richtung_Sion.jpg", "LSTA Webcam Richtung Sion", "width");
    $webcamAirport = $page->bodyBuilder->anchor("https://www.air-zermatt.ch/wordpress/en/webcam/", $webcamAirportImg, "LSTA Webcams");

$wunderground = "IVALAISS15";

    // Webcam area
    $webcamArea = $page->bodyBuilder->anchor("https://sionairport.roundshot.com/", "Webcam Sion airport");
    $webcamArea .= "<br />\n";

    $webcamArea .= "Veysonnaz 4200ft:\n";
    $webcamArea .= "<br />\n";
    $webcamArea .= "<img src=\"https://www.caboulis.ch/sion.jpg\" alt=\"WebCam Veysonnaz\" />\n";


echo homebase($page, $title, $infos, $webcamAirport, $wunderground, $webcamArea);
?>
