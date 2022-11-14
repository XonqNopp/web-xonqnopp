<?php
require_once("../functions/page_helper.php");
$page = new PhPage("..");
//$page->logger->levelUp(6);
require_once("preparations.php");

//$page->cssHelper->dirUpWing();// cannot because it screws my display (but why?)

$body = $page->bodyBuilder->goHome("..");

$body .= $page->htmlHelper->setTitle("Fly!");
$page->htmlHelper->hotBooty();

$body .= $page->waitress->tableOpen(array(), false);
$body .= $page->waitress->rowOpen();
    // PAX
    $body .= $page->waitress->cell(
        $page->bodyBuilder->anchor("pax.php?language=francais", "Informations pour mes passagers"),
        array("class" => "flyTitle")
    );

    // logbook
    $body .= $page->waitress->cell(
        $page->bodyBuilder->anchor("logbook.php", "My flight logbook"),
        array("class" => "flyTitle")
    );

$body .= $page->waitress->rowClose();
$body .= $page->waitress->tableClose();

$body .= "<div></div>\n";  // some space

$body .= $page->waitress->tableOpen(array("class" => "left"), false);
$body .= $page->waitress->rowOpen();
    $body .= $page->waitress->cellOpen(array("class" => "half"));
        $body .= "<ul>\n";
        $body .= $page->bodyBuilder->liAnchor("nav/index.php", "navigations");
        $body .= $page->bodyBuilder->liAnchor("pdf", "my PDFs");
        $body .= $page->bodyBuilder->liAnchor("computer.php", "computer");
        $body .= "</ul>\n";
    //
        // Preparation
        $body .= "Flight preparations:\n";
        $body .= "<ul>\n";
        $body .= $page->bodyBuilder->liAnchor("lsge.php", "LSGE");
        $body .= $page->bodyBuilder->liAnchor("lsgs.php", "LSGS");
        $body .= "</ul>\n";
    //
        // misc
        $body .= "Misc:\n";
        $body .= "<ul>\n";
        $body .= $page->bodyBuilder->liAnchor("https://www.sust.admin.ch/fr/sese-page-daccueil", "SESE/SUST");

        $body .= "<li>\n";
        $body .= $page->bodyBuilder->anchor("https://www.bazl.admin.ch/bazl/fr/home.html", "OFAC/BAZL");
        $body .= "&nbsp;-&nbsp;\n";
        $body .= $page->bodyBuilder->anchor("https://www.bazl.admin.ch/bazl/fr/home/personal/flugausbildung/pilotes/formulaires.html", "Formulaires AESA, avions &agrave; moteur, 60.521 SEP TMG revaildation EASA");
        $body .= "</li>\n";

        $body .= $page->bodyBuilder->liAnchor("http://seaplanes.ch/", "seaplanes.ch");
        $body .= $page->bodyBuilder->liAnchor("http://www.flightradar24.com/", "FlightRadar24.com");
        $body .= $page->bodyBuilder->liAnchor("http://planefinder.net/", "PlaneFinder.net");
        $body .= "</ul>\n";
    $body .= $page->waitress->cellClose();
//
    $body .= $page->waitress->cell(
        commonPreparations($page),
        array("class" => "half")
    );

$body .= $page->waitress->rowClose();
$body .= $page->waitress->tableClose();

echo $body;
?>
