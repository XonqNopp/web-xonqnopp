<?php
require_once("../functions/page_helper.php");

$page = new PhPage("..");

require_once("homebase.php");

$title = "LSGS: Sion";
$smallTt = "<tt class=\"smaller\">";


    // Infos
    $infos = "<li>\n";
    $infos .= $page->bodyBuilder->anchor("http://app.aviator.club/", "LSGS app");
    if($page->loginHelper->userIsAdmin()) {
        $infos .= "<br />{$smallTt}{$page->miscInit->fly->lsgs}</tt>";
        $infos .= "<br />{$smallTt}{$page->miscInit->fly->clearance}</tt>";
    }
    $infos .= "</li>\n";

    $infos .= "<li>";
    $infos .= $page->bodyBuilder->anchor("http://gvm-sion.ch/", "GVM Sion");
    if($page->loginHelper->userIsAdmin()) {
        $infos .= "&nbsp;{$smallTt}{$page->miscInit->fly->lsgsDoor}</tt>";
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
    $infos .= $page->bodyBuilder->anchor("https://fgo.flightforms.ch/", "PPR");
    $infos .= "</li>\n";
//
    // Webcam airport
    $webcamAirportImg = $page->bodyBuilder->img("https://meteo-oberwallis.ch/webcam/raron/flugplatz.php", "LSTA Webcam", "width");
    $webcamAirportImg .= "<br />\n";
    $webcamAirportImg .= $page->bodyBuilder->img("https://www.air-zermatt.ch/webcam/Richtung_Goms.jpg", "LSER Webcam Richtung Goms", "width");
    $webcamAirportImg .= "<br />\n";
    $webcamAirportImg .= $page->bodyBuilder->img("https://www.air-zermatt.ch/webcam/Heliport2.jpg", "LSER Webcam Heliport2", "width");
    $webcamAirportImg .= "<br />\n";
    $webcamAirportImg .= $page->bodyBuilder->img("https://www.air-zermatt.ch/webcam/Heliport1.jpg", "LSER Webcam Heliport1", "width");
    $webcamAirportImg .= "<br />\n";
    $webcamAirportImg .= $page->bodyBuilder->img("https://www.air-zermatt.ch/webcam/Richtung_Sion.jpg", "LSER Webcam Richtung Sion", "width");
    $webcamAirport = $page->bodyBuilder->anchor("https://www.air-zermatt.ch/en/general-information/services/webcams", $webcamAirportImg, "LSER Webcams");
//
    // Webcam area
    $webcamArea = $page->bodyBuilder->anchor("https://sionairport.roundshot.com/", "Webcam Sion airport");
    $webcamArea .= "<br />\n";

    $webcamArea .= "Veysonnaz 4200ft:\n";
    $webcamArea .= "<br />\n";
    $webcamArea .= "<img src=\"https://www.caboulis.ch/sion.jpg\" alt=\"WebCam Veysonnaz\" />\n";


echo homebase($page, $title, $infos, $webcamAirport, $webcamArea);
?>
