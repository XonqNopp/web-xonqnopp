<?php
require_once("preparations.php");


/**
 * Make a homebase page.
 *
 * @SuppressWarnings(PHPMD.MissingImport)
 */
function homebase($page, $title, $infos, $webcamAirport, $webcamArea) {
    // debug
    //$page->htmlHelper->init();
    //$page->logger->levelUp(6);
    // CSS paths
    //$page->cssHelper->dirUpWing();

    $body = $page->bodyBuilder->goHome("..");

    // Set title and hot booty
    $body .= $page->htmlHelper->setTitle($title);// before HotBooty
    $page->htmlHelper->hotBooty();

    $body .= $page->waitress->tableOpen(array("class" => "links"), false);
    $body .= $page->waitress->rowOpen();

        // Infos
        $body .= $page->waitress->cell(
            "<div>\n<ul>\n$infos</ul>\n</div>\n",
            array("class" => "third")
        );
    //
        // webcam
        $body .= $page->waitress->cell(
            "<div>\n$webcamAirport</div>\n",
            array("class" => "third")
        );
    //

    $body .= $page->waitress->cell(commonPreparations($page), array("class" => "third left"));

    $body .= $page->waitress->rowClose();
    $body .= $page->waitress->tableClose();

    $body .= "<div class=\"wide\">\n$webcamArea</div>\n";

    // Do not have text glued at bottom
    $body .= "<div>&nbsp;</div>\n";

    return $body;
}
?>
