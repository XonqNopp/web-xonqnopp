<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->bobbyTable->init();

$page->cssHelper->dirUpWing();

$tables = array("bds" => "BD", "books" => "book", "dvds" => "DVD");

$view = "";
if(isset($_GET["view"])) {
    $view = $_GET["view"];
}

$body = $page->bodyBuilder->goHome("../..", "..");
$body .= $page->htmlHelper->setTitle("Missing items");
$page->htmlHelper->hotBooty();

$body .= "<div>\n";


function getMissingBD($borrowerId, $missingItem) {
    global $page;
    global $view;

    $dbid = $missingItem->dbid;

    $iteminfo = $page->bobbyTable->queryManage("SELECT * FROM `bds` WHERE `id` = $dbid");
    $device = $iteminfo->fetch_object();
    $iteminfo->close();

    $serieId = $device->serie_id;
    $title = $device->title;

    if($serieId > 1) {
        $serieQuery = $page->bobbyTable->queryManage("SELECT * FROM `bd_series` WHERE `id` = {$device->serie_id}");
        $serieEntry = $serieQuery->fetch_object();
        $serieQuery->close();
        $title = "{$serieEntry->name} {$device->tome}";

        if($device->title != "") {
            $title = "{$device->title}<br />($title)";
        }
    }

    $cssitems = "";
    if($view == "borrower$borrowerId") {
        $cssitems = " missing_display_borrowed";
    }

    $csswanted = "";
    if($view == "bds$dbid" || $view == "bds") {
        $csswanted = " missing_wanted";
    }

    $body = $page->butler->rowOpen(array("class" => "missing_display$csswanted$cssitems"));

    if($page->loginHelper->userIsAdmin()) {
        $body .= $page->butler->cell($page->bodyBuilder->anchor("../bds/serie_display.php?back=$dbid&amp;id=$serieId", "back"), array("class" => "missing_back"));
    }

    $body .= $page->butler->cell(
        $page->bodyBuilder->anchor("../bds/serie_display.php?id=$serieId", $title, NULL, NULL, false, "id=\"bds$dbid\""),
        array("class" => "missing_display_title")
    );

    $body .= $page->butler->cell($missingItem->when, array("class" => "missing_display_date"));
    $body .= $page->butler->rowClose();

    return $body;
}


function getMissingOther($borrowerId, $missingItem) {
    global $page;
    global $view;
    global $tables;

    $dbtable = $missingItem->dbtable;
    $dbid = $missingItem->dbid;
    $iteminfo = $page->bobbyTable->queryManage("SELECT * FROM `$dbtable` WHERE `id` = $dbid");
    $device = $iteminfo->fetch_object();
    $iteminfo->close();
    $title = "";

    $title = $device->title;
    if($device->serie != "") {
        $title .= " ({$device->serie} {$device->number})";
    }

    $cssitems = "";
    if($view == "borrower$borrowerId") {
        $cssitems = " missing_display_borrowed";
    }

    $csswanted = "";
    if($view == "$dbtable$dbid" || $view == $dbtable) {
        $csswanted = " missing_wanted";
    }

    $body = $page->butler->rowOpen(array("class" => "missing_display$csswanted$cssitems"));

    if($page->loginHelper->userIsAdmin()) {
        $body .= $page->butler->cell($page->bodyBuilder->anchor("../$dbtable/index.php?back=$dbid", "back"), array("class" => "missing_back"));
    }

    $body .= $page->butler->cell(
        $page->bodyBuilder->anchor("../$dbtable/index.php?id=$dbid", "$title - {$tables[$dbtable]}", $title, NULL, false, "id=\"$dbtable$dbid\""),
        array("class" => "missing_display_title")
    );

    $body .= $page->butler->cell($missingItem->when, array("class" => "missing_display_date"));
    $body .= $page->butler->rowClose();

    return $body;
}


function getMissingItem($borrowerId, $missingItem) {
    if($missingItem->dbtable == "bds") {
        return getMissingBD($borrowerId, $missingItem);
    }

    return getMissingOther($borrowerId, $missingItem);
}


function getMissingsFromBorrower($person) {
    global $page;
    global $view;

    $borrowerId = $person->id;
    $missings = $page->bobbyTable->queryManage("SELECT * FROM `missings` WHERE `borrower` = $borrowerId ORDER BY `when` ASC, `dbtable` ASC, `id` ASC");
    if($missings->num_rows <= 0) {
        $missings->close();
        return "";
    }

    $body = $page->butler->rowOpen();

    $cssborrower = "";
    if($view == "borrower$borrowerId") {
        $cssborrower = " missing_display_name_borrower";
    }

    $colspan = 2;
    if($page->loginHelper->userIsAdmin()) {
        $colspan += 1;
    }

    $body .= $page->butler->cell(
        "{$person->name} ({$missings->num_rows})",
        array("class" => "missing_display_name$cssborrower", "colspan" => $colspan, "id" => "borrower$borrowerId")
    );

    $body .= $page->butler->rowClose();

    while($item = $missings->fetch_object()) {
        $body .= getMissingItem($borrowerId, $item);
    }
    $missings->close();
    return $body;
}


function getBorrowers() {
    global $page;

    $borrowers = $page->bobbyTable->queryManage("SELECT * FROM `borrowers` ORDER BY `name` ASC");
    if($borrowers->num_rows == 0) {
        $borrowers->close();
        return "Nobody is registered as a borrower...\n";
    }

    $body = "<div class=\"missing_display_table\">\n";
    $body .= $page->butler->tableOpen(array("class" => "missing_display"));
    while($person = $borrowers->fetch_object()) {
        $body .= getMissingsFromBorrower($person);
    }
    $borrowers->close();

    $body .= $page->butler->tableClose();
    $body .= "</div><!-- missing_display_table -->\n";
    return $body;
}


function getBorrowed() {
    global $page;

    $checkDoMiss = $page->bobbyTable->queryManage("SELECT COUNT(*) AS `howmany` FROM `missings`");
    $checkFetch = $checkDoMiss->fetch_object();
    $checkDoMiss->close();
    if($checkFetch->howmany <= 0) {
        return "Nothing is being borrowed for now...\n";
    }

    return getBorrowers();
}


$body .= getBorrowed();
$body .= "</div>\n";

echo $body;
?>
