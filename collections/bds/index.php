<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->logger->levelUp(6);
$page->bobbyTable->init();

$GI = $page->loginHelper->userIsAdmin();

$page->cssHelper->dirUpWing();

$getcount = $page->bobbyTable->queryManage("SELECT COUNT(*) AS `the_count` FROM `bds`");
$fetchCount = $getcount->fetch_object();
$bdCount = $fetchCount->the_count;
$getcount->close();
$getcount = $page->bobbyTable->queryManage("SELECT COUNT(*) AS `the_count` FROM `bd_series`");
$fetchCount = $getcount->fetch_object();
$serieCount = $fetchCount->the_count;
$getcount->close();

$body = $page->bodyBuilder->goHome("../..", "..");

$body .= $page->htmlHelper->setTitle("My $bdCount BDs in $serieCount series");
$page->htmlHelper->hotBooty();

// Propose to add a new if authorized
$body .= "<div class=\"wide\">\n";
$body .= "<div class=\"lhead\">\n";
//$body .= $page->bodyBuilder->anchor("search.php", "Search");
$body .= "</div>\n";
$body .= "<div class=\"chead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"rhead\">\n";
$body .= $page->bodyBuilder->anchor("../missings/index.php?view=bds", "Missing BDs");
if($GI) {
    $body .= "<br />\n";
    $body .= $page->bodyBuilder->anchor("insert.php", "New BD") . "<br />\n";
    $body .= $page->bodyBuilder->anchor("serie_insert.php", "New serie");
}
$body .= "</div>\n";
$body .= "</div>\n";


$body .= "<div>\n";


function getBody() {
    global $page;

    $body = "";

    $querySeries = $page->bobbyTable->queryAlpha("bd_series", "name");
    $nSeries = $querySeries->num_rows;
    if($nSeries == 0) {
        $querySeries->close();
        return "Sorry, no result to display...";
    }

    $seriesWidth = 4;
    $seriesPerColumn = $nSeries * 1.0 / $seriesWidth;

    $body .= "<div class=\"bd_display_table\">\n";
    $body .= $page->waitress->tableOpen(array(), false);
    $body .= $page->waitress->rowOpen();
    $body .= $page->waitress->cellOpen(array("class" => "stem_cell"));
    $check = 0;
    while($serie = $querySeries->fetch_object()) {
        $check++;
        if($check > $seriesPerColumn) {
            $body .= $page->waitress->cellClose();
            $body .= $page->waitress->cellOpen();
            $check = 0;
        }

        $body .= "<div class=\"bd_serie_item\">\n";
        $serieId = $serie->id;
        $name = $serie->name;
        $nAlbums = $serie->Nalbums;
        if($name == "") {
            $name = "Hors s&eacute;ries";
        }
        //$thumb = $serie->thumb;
        $getCount = $page->bobbyTable->idManage("SELECT COUNT(*) AS `count` FROM `bds` WHERE `serie_id` = ?", $serieId);
        $count = NULL;
        $getCount->bind_result($count);
        $getCount->fetch();
        $getCount->close();

        $body .= "<div class=\"bd_serie_name\">\n";
        $body .= $page->bodyBuilder->anchor("serie_display.php?id=$serieId", $name);
        $body .= "</div>\n";

        if($nAlbums <= 0) {
            $body .= "<div class=\"bd_serie_count\">($count)</div>\n";
            $body .= "</div><!-- bd_serie_item -->\n";
            continue;
        }

        $body .= "<div class=\"bd_serie_count";
        if($count < $nAlbums) {
            $body .= "_incomplete";
        }
        $body .= "\">($count&nbsp;/$nAlbums)</div>";

        $body .= "</div><!-- bd_serie_item -->\n";
    }

    $body .= $page->waitress->cellClose();
    $body .= $page->waitress->rowClose();
    $body .= $page->waitress->tableClose();
    $body .= "</div><!-- bd_display_table -->\n";

    $querySeries->close();

    $body .= "</div>\n";
    return $body;
}


echo $body . getBody();
?>
