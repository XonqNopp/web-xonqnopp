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


$sortByEditor = false;
if(isset($_GET["sort"]) && $_GET["sort"] == "editor") {
    $sortByEditor = true;
}


// Propose to add a new if authorized
$body .= "<div class=\"wide\">\n";
$body .= "<div class=\"lhead\">\n";
if($sortByEditor) {
    $body .= $page->bodyBuilder->anchor("?sort=alpha", "Tri alphab&eacute;tique");
} else {
    $body .= $page->bodyBuilder->anchor("?sort=editor", "Tri par &eacute;diteur");
}
//$body .= $page->bodyBuilder->anchor("search.php", "Search");
$body .= "</div>\n";
$body .= "<div class=\"chead\"></div>\n";
$body .= "<div class=\"rhead\">\n";
$body .= $page->bodyBuilder->anchor("../missings/index.php?view=bds", "Missing BDs");
if($GI) {
    $body .= "<br>\n" . $page->bodyBuilder->anchor("insert.php", "New BD");
    $body .= "<br>\n" . $page->bodyBuilder->anchor("serie_insert.php", "New serie");
}
$body .= "</div>\n";
$body .= "</div><!-- wide -->\n";


$body .= "<div>\n";


function displaySerie($serie, $displayEditor) {
    global $page;

    $body = "<div class=\"bd_serie_item\">\n";
    $serieId = $serie->id;
    $name = $serie->name;
    $nAlbums = $serie->Nalbums;
    if($name == "") {
        $name = "Hors s&eacute;ries";
    }
    $editor = $serie->editor;
    $getCount = $page->bobbyTable->idManage("SELECT COUNT(*) AS `count` FROM `bds` WHERE `serie_id` = ?", $serieId);
    $count = NULL;
    $getCount->bind_result($count);
    $getCount->fetch();
    $getCount->close();

    $serieContent = $name;
    $serieTitle = $name;
    if($displayEditor && $editor != "") {
        $serieContent .= "<br>[<span class=\"editor\">$editor</span>]\n";
        $serieTitle .= " [$editor]";
    }

    $body .= "<div class=\"bd_serie_name\">\n";
    $body .= $page->bodyBuilder->anchor("serie_display.php?id=$serieId", $serieContent, $serieTitle);
    $body .= "</div>\n";

    if($nAlbums <= 0) {
        $body .= "<div class=\"bd_serie_count\">($count)</div>\n";
        $body .= "</div><!-- bd_serie_item -->\n";
        return $body;
    }

    $body .= "<div class=\"bd_serie_count";
    if($count < $nAlbums) {
        $body .= "_incomplete";
    }
    $body .= "\">($count&nbsp;/$nAlbums)</div>";

    $body .= "</div><!-- bd_serie_item -->\n";

    return $body;
}


$seriesWidth = 4;


function displayByAlpha() {
    global $page;
    global $seriesWidth;

    $querySeries = $page->bobbyTable->queryAlpha("bd_series", "name");
    $nSeries = $querySeries->num_rows;
    if($nSeries == 0) {
        $querySeries->close();
        return "Sorry, no result to display...";
    }

    $seriesPerColumn = $nSeries * 1.0 / $seriesWidth;

    $body = "<div class=\"bd_display_table\">\n";
    $body .= $page->waitress->tableOpen(array(), false);
    $body .= $page->waitress->rowOpen();
    $body .= $page->waitress->cellOpen(array("class" => "stem_cell"));
    $check = 0;
    while($serie = $querySeries->fetch_object()) {
        $check++;
        if($check > $seriesPerColumn) {
            $body .= $page->waitress->cellClose();
            $body .= $page->waitress->cellOpen(array("class" => "stem_cell"));
            $check = 0;
        }

        $body .= displaySerie($serie, true);
    }

    $body .= $page->waitress->cellClose();
    $body .= $page->waitress->rowClose();
    $body .= $page->waitress->tableClose();
    $body .= "</div><!-- bd_display_table -->\n";

    $querySeries->close();

    $body .= "</div>\n";
    return $body;
}


function numberOfSeriesPerColumnByEditor($editor) {
    global $page;
    global $seriesWidth;
    $nSeries = $page->bobbyTable->queryManage("SELECT COUNT(*) AS `the_count` FROM `bd_series` WHERE `editor` = '$editor'")->fetch_object()->the_count;
    return floor($nSeries * 1.0 / $seriesWidth);
}


function displayByEditor() {
    global $page;

    // Complex query
    $query = "SELECT *, ";
    $query .= $page->bobbyTable->sortAlpha("editor") . ", ";
    $query .= $page->bobbyTable->sortAlpha("name");
    $query .= "FROM `bd_series` ORDER BY ";
    $query .= $page->bobbyTable->orderAlpha("editor") . ", ";
    $query .= $page->bobbyTable->orderAlpha("name");
    $querySeries = $page->bobbyTable->queryManage($query);

    $nSeries = $querySeries->num_rows;
    if($nSeries == 0) {
        $querySeries->close();
        return "Sorry, no result to display...";
    }

    $body = "";

    $editor = "";
    $body .= $page->bodyBuilder->titleAnchor("???", 2, "UNKNOWN");
    $seriesPerColumn  = numberOfSeriesPerColumnByEditor($editor);

    $body .= "<div class=\"bd_display_table\">\n";
    $body .= $page->waitress->tableOpen(array(), false);
    $body .= $page->waitress->rowOpen();
    $body .= $page->waitress->cellOpen(array("class" => "stem_cell"));
    $check = 1;

    while($serie = $querySeries->fetch_object()) {
        if($serie->editor != $editor) {
            $body .= $page->waitress->cellClose();
            $body .= $page->waitress->rowClose();
            $body .= $page->waitress->tableClose();
            $body .= "</div><!-- bd_display_table -->\n";

            $editor = $serie->editor;
            $body .= $page->bodyBuilder->titleAnchor($editor);

            $body .= "<div class=\"bd_display_table\">\n";
            $body .= $page->waitress->tableOpen(array(), false);
            $body .= $page->waitress->rowOpen();
            $body .= $page->waitress->cellOpen(array("class" => "stem_cell"));

            $check = 0;
            $seriesPerColumn  = numberOfSeriesPerColumnByEditor($editor);
        }

        if($check > $seriesPerColumn) {
            $body .= $page->waitress->cellClose();
            $body .= $page->waitress->cellOpen(array("class" => "stem_cell"));
            $check = 1;
        }

        $body .= displaySerie($serie, false);
        $check++;
    }

    $body .= $page->waitress->cellClose();
    $body .= $page->waitress->rowClose();
    $body .= $page->waitress->tableClose();
    $body .= "</div><!-- bd_display_table -->\n";

    $querySeries->close();

    $body .= "</div>\n";
    return $body;
}


function getBody() {
    global $sortByEditor;

    if($sortByEditor) {
        return displayByEditor();
    }

    return displayByAlpha();
}


echo $body . getBody();
?>
