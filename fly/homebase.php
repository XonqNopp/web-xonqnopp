<?php
require_once("preparations.php");


/**
 * Make a homebase page.
 *
 * @SuppressWarnings(PHPMD.MissingImport)
 */
function homebase($page, $title, $infos, $webcamAirport, $wunderground, $webcamArea) {
    // debug
    //$page->htmlHelper->init();
    //$page->logger->levelUp(6);
    // CSS paths
    //$page->cssHelper->dirUpWing();

    $body = $page->bodyBuilder->goHome();

    // Set title and hot booty
    $body .= $page->htmlHelper->setTitle($title);// before HotBooty
    $page->htmlHelper->hotBooty();

    $body .= $page->waitress->tableOpen(array("class" => "links"), false);
    $body .= $page->waitress->rowOpen();

        // Infos & webcam
        $body .= $page->waitress->cellOpen(array("class" => "third"));
        $body .= "<div>\n<ul>\n$infos</ul>\n</div>\n";
        $body .= "<div>\n$webcamAirport</div>\n";
        $body .= $page->waitress->cellClose();
    //
        // Weather station
        $body .= $page->waitress->cellOpen(array("class" => "third"));
        $body .= $page->bodyBuilder->imgAnchor(
            "https://www.wunderground.com/dashboard/pws/$wunderground",
            "http://www.wunderground.com/cgi-bin/wxStationGraphAll?ID=$wunderground"
            . "&amp;type=3"
            . "&amp;width=500"
            . "&amp;showsolarradiation=1"
            . "&amp;showtemp=1"
            . "&amp;showpressure=1"
            . "&amp;showwind=1"
            . "&amp;showwinddir=1"
            . "&amp;showrain=1",
            "weather station",
            "width"
        );
        $body .= $page->waitress->cellClose();

    $body .= $page->waitress->cell(commonPreparations($page), array("class" => "third left"));

    $body .= $page->waitress->rowClose();
    $body .= $page->waitress->tableClose();

    $body .= "<div class=\"wide\">\n$webcamArea</div>\n";

    // Do not have text glued at bottom
    $body .= "<div>&nbsp;</div>\n";

    return $body;
}
?>
