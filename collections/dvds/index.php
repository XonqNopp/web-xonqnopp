<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->bobbyTable->init();

require_once("{$funcpath}_local/borrowback.php");

//require_once("$funcpath/fetch_from_imdb.php");
//$body .= fetch_IMDB("http://www.imdb.fr/title/tt0112864/");
//$body .= "\n\n";

// Borrowed item came home (link from missing index)
if(isset($_GET["back"])) {
    $backId = NULL;
    if(isset($_GET["id"])) {
        $backId = $_GET["id"];
    }
    borrow_back($page, "dvds", $_GET["back"], $backId);
}

$page->cssHelper->dirUpWing();

$GI = $page->loginHelper->userIsAdmin();

$getcount = $page->bobbyTable->queryManage("SELECT COUNT(*) AS `the_count` FROM `dvds`");
$fetch_count = $getcount->fetch_object();
$dvd_count = $fetch_count->the_count;
$getcount->close();

$body = $page->bodyBuilder->goHome("../..", "..");
$body .= $page->htmlHelper->setTitle("My $dvd_count DVDs");
$page->htmlHelper->hotBooty();

$body .= "<div class=\"wide\">\n";
$body .= "<div class=\"lhead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"chead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"rhead\">\n";
$body .= $page->bodyBuilder->anchor("../missings/index.php?view=dvds", "Missing DVDs");
if($GI) {
    $body .= "<br />\n";
    // Propose to add a new if authorized
    $body .= $page->bodyBuilder->anchor("insert.php", "New DVD");
}
$body .= "</div>\n";
$body .= "</div>\n";

// Display
$dvds = $page->bobbyTable->queryManage("SELECT *, " . $page->bobbyTable->sortAlpha("title") . " FROM `dvds` WHERE `category` <> 'tvserie' AND `title` <> '' ORDER BY " . $page->bobbyTable->orderAlpha("title"));
$series = $page->bobbyTable->queryManage("SELECT *, " . $page->bobbyTable->sortAlpha("serie") . ", " . $page->bobbyTable->sortAlpha("title") . " FROM `dvds` WHERE `category` = 'tvserie' OR `serie` <> '' ORDER BY " . $page->bobbyTable->orderAlpha("serie") . ", `number` ASC, " . $page->bobbyTable->orderAlpha("title"));
$series_count = $page->bobbyTable->queryManage("SELECT COUNT(DISTINCT(serie)) AS `sc` FROM `dvds`");
$sc = $series_count->fetch_object();
$series_count->close();
$N = $sc->sc - 1.0;
if($dvds->num_rows == 0) {
    $body .= "Sorry, no result to display...";
} else {
    // Results
    if($series->num_rows > 0) {
        // Series
        $body .= "<!--    SERIES   -->\n";
        $body .= "<h3 class=\"dvd_display\">Series</h3>\n";
        $body .= $page->waitress->tableOpen(array("class" => "dvd_display_table_serie"));
        $body .= $page->waitress->rowOpen();
        $body .= $page->waitress->cellOpen();
        $old_serie = "";
        $serie_width = 3;
        $serie_index = 0;
        while($volume = $series->fetch_object()) {
            $serie = $volume->serie;
            if($serie != $old_serie) {
                $old_serie = $serie;
                $serie_index++;
                if($serie_index > $N / $serie_width) {
                    $serie_index = 0;
                    $body .= $page->waitress->cellClose();
                    $body .= $page->waitress->cellOpen();
                }
                $body .= "<div class=\"dvd_display_table_serie_item\">\n";
                $id = $volume->id;
                $body .= $page->bodyBuilder->anchor("serie_display.php?id=$id", $serie);
                $body .= "</div>\n";
            }
        }
        $body .= $page->waitress->cellClose();
        $body .= $page->waitress->rowClose();
        $body .= $page->waitress->tableClose();

        $body .= "<!--    DVDs     -->\n";
        $body .= "<h3 class=\"dvd_display\">DVDs</h3>\n";
    }
    //// Individual DVDs (including those in series)
    $N = $dvds->num_rows;
    $dvd_width = 2;
    $body .= $page->waitress->tableOpen(array("class" => "dvd_display_table"));
    $body .= $page->waitress->rowOpen();
    $body .= $page->waitress->cellOpen("dvd_display_table_cell");
    $dvd_index = 0;
    while($dvd = $dvds->fetch_object()) {
        $dvd_index++;

        if($dvd_index > $N * 1.0 / $dvd_width) {
            $dvd_index = 0;
            $body .= $page->waitress->cellClose();
            $body .= $page->waitress->cellOpen(array("class" => "dvd_display_table_cell"));
        }

        $id = $dvd->id;
        $title = $dvd->title;
        $body .= "<div id=\"dvd$id\" class=\"flushleft";
        if($dvd->borrowed) {
            $body .= " away";
        }

        $body .= "\">\n";

        if($GI) {
            $body .= "<div class=\"InB EditBorrow\">\n";
            $body .= $page->bodyBuilder->anchor("insert.php?id=$id", "edit");
            $body .= "&nbsp;\n";
            if($dvd->borrowed) {
                $body .= $page->bodyBuilder->anchor("../missings/index.php?view=dvds$id#dvds$id", "who");

            } else {
                $body .= $page->bodyBuilder->anchor("../missings/insert.php?db=dvds&amp;id=$id", "borrow");
            }

            $body .= "</div>\n";
        }

        $body .= "<div class=\"InB MainBook\">\n";
        $body .= $page->bodyBuilder->anchor("display.php?id=$id", $title);
        $body .= "</div>\n";
        $body .= "</div>\n";
    }

    $body .= $page->waitress->cellClose();
    $body .= $page->waitress->rowClose();
    $body .= $page->waitress->tableClose();
}
$dvds->close();
$series->close();

echo $body;
?>
