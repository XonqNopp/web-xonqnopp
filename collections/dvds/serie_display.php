<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->logger->levelUp(6);
//$page->htmlHelper->init();
$page->bobbyTable->init();

$page->cssHelper->dirUpWing();

$GI = $page->loginHelper->userIsAdmin();


$body = $page->bodyBuilder->goHome("..");

// Find which serie we are dealing with
$serie_id = $_GET["id"];
$findserie = $page->bobbyTable->idManage("SELECT * FROM `dvds` WHERE `id` = ?", $serie_id);
$findserie->store_result();
if($findserie->num_rows == 0) {
    $findserie->close();
    exit("Error bad id");
}
$findserie->bind_result($serie_id, $title, $director, $actors, $languages, $subtitles, $duration, $serie, $number, $category, $summary, $burnt, $format, $borrowed);
$findserie->fetch();
$findserie->close();
// Title
$body .= $page->htmlHelper->setTitle("$serie (DVDs)");
$page->htmlHelper->hotBooty();

$body .= "<div class=\"wide\">\n";
$body .= "<div class=\"lhead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"chead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"rhead\">\n";
$body .= $page->bodyBuilder->anchor("../missings/index.php?view=dvds", "Missing DVDs");
if($GI) {
    $body .= "<br>\n";
    // Propose to add a new if authorized
    $body .= $page->bodyBuilder->anchor("insert.php", "New DVD");
}
$body .= "</div>\n";
$body .= "</div>\n";

// Fetch all from this serie
$sql_serie = $serie;
$dvds = $page->bobbyTable->queryPrepare("SELECT * FROM `dvds` WHERE `serie` = ? ORDER BY `number` ASC, `title` ASC");
$dvds->bind_param("s", $sql_serie);
$page->bobbyTable->executeManage($dvds);
$dvds->store_result();
if($dvds->num_rows == 0) {
    $dvds->close();
    $page->htmlHelper->headerLocation();
}
$dvds->bind_result($id, $title, $director, $actors, $languages, $subtitles, $duration, $serie, $number, $category, $summary, $burnt, $format, $borrowed);
$body .= "<div class=\"dvd_serie_table\">\n";
$body .= $page->butler->tableOpen(array("class" => "dvd_serie_table"));
while($dvds->fetch()) {
    $csstitle = "dvd_serie_table_title";
    if($borrowed == 1) {
        $csstitle .= " away";
    }
    $body .= $page->butler->rowOpen(array("class" => "dvd_serie_table"));

    $body .= $page->butler->cell($GI ? $page->bodyBuilder->anchor("insert.php?id=$id", "edit") : "", array("class" => "dvd_serie_table_edit"));

    // Borrow
    $body .= $page->butler->cellOpen(array("class" => "dvd_serie_borrow"));
    if($GI) {
        if($borrowed) {
            $body .= $page->bodyBuilder->anchor("../missings/index.php?view=dvds$id#dvds$id", "who");
        } else {
            $body .= $page->bodyBuilder->anchor("../missings/insert.php?db=dvds&amp;id=$id", "borrow");
        }
    }

    $body .= $page->butler->cellClose();

    // Number
    if($number == "0") { $number = ""; }
    $body .= $page->butler->cell($number != "" ? $number : "", array("class" => "dvd_serie_table_number"));

    // Title
    $body .= $page->butler->cellOpen(array("class" => $csstitle));
    $body .= $page->bodyBuilder->anchor("display.php?id=$id", $title, NULL, "dvd_serie_display_title", false, "id=\"b$id\"");
    $body .= $page->butler->cellClose();
    $body .= $page->butler->rowClose();
}
$body .= $page->butler->tableClose();
$body .= "</div>\n";
$dvds->close();

echo $body;
?>
