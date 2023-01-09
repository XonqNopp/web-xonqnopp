<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->initDB();

$page->CSS_ppJump(2);
$page->CSS_ppWing();

$tables = array("bds" => "BD", "books" => "book", "dvds" => "DVD");

$view = "";
if(isset($_GET["view"])) {
	$view = $_GET["view"];
}

$body = "";
$args = new stdClass();
$args->page = "..";
$body .= $page->GoHome($args);
$body .= $page->SetTitle("Missing items");
$page->HotBooty();

$body .= "<div class=\"whole\">\n";


function getMissingBD($page, $borrowerId, $missingItem) {
	global $view;

	$dbid = $missingItem->dbid;

	$iteminfo = $page->DB_QueryManage("SELECT * FROM `bds` WHERE `id` = $dbid");
	$device = $iteminfo->fetch_object();
	$iteminfo->close();

	$serieId = $device->serie_id;
	$title = $device->title;

	if($serieId > 1) {
		$serieQuery = $page->DB_QueryManage("SELECT * FROM `bd_series` WHERE `id` = {$device->serie_id}");
		$serieEntry = $serieQuery->fetch_object();
		$serieQuery->close();
		$title = "{$serieEntry->name} {$device->tome}";

		if($device->title != "") {
			$title = "{$device->title} ($title)";
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

	$body = "<tr class=\"missing_display$csswanted$cssitems\">\n";

	if($page->UserIsAdmin()) {
		$body .= "<td class=\"missing_back\">\n";
		$body .= "<a href=\"../bds/serie_display.php?back=$dbid&amp;id=$serieId\" title=\"back\">back</a>\n";
		$body .= "</td>\n";
	}

	$body .= "<td class=\"missing_display_title\">\n";
	$body .= "<a id=\"bds$dbid\"";
	$body .= " href=\"../bds/serie_display.php?id=$serieId\"";
	$body .= " title=\"$title\">";
	$body .= "$title - BD";
	$body .= "</a>\n";
	$body .= "</td>\n";
	$body .= "<td class=\"missing_display_date\">{$missingItem->when}</td>\n";
	$body .= "</tr>\n";

	return $body;
}


function getMissingOther($page, $borrowerId, $missingItem) {
	global $view;
	global $tables;

	$dbtable = $missingItem->dbtable;
	$dbid = $missingItem->dbid;
	$iteminfo = $page->DB_QueryManage("SELECT * FROM `$dbtable` WHERE `id` = $dbid");
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

	$body = "<tr class=\"missing_display$csswanted$cssitems\">\n";

	if($page->UserIsAdmin()) {
		$body .= "<td class=\"missing_back\">\n";
		$body .= "<a href=\"../$dbtable/index.php?back=$dbid\" title=\"back\">back</a>\n";
		$body .= "</td>\n";
	}

	$body .= "<td class=\"missing_display_title\">\n";
	$body .= "<a id=\"$dbtable$dbid\"";
	$body .= " href=\"../$dbtable/index.php?id=$dbid\"";
	$body .= " title=\"$title\">";
	$body .= "$title - {$tables[$dbtable]}";
	$body .= "</a>\n";
	$body .= "</td>\n";
	$body .= "<td class=\"missing_display_date\">{$missingItem->when}</td>\n";
	$body .= "</tr>\n";

	return $body;
}


function getMissingItem($page, $borrowerId, $missingItem) {
	if($missingItem->dbtable == "bds") {
		return getMissingBD($page, $borrowerId, $missingItem);
	}

	return getMissingOther($page, $borrowerId, $missingItem);
}


function getMissingsFromBorrower($page, $person) {
	global $view;

	$borrowerId = $person->id;
	$missings = $page->DB_QueryManage("SELECT * FROM `missings` WHERE `borrower` = $borrowerId ORDER BY `when` ASC, `dbtable` ASC, `id` ASC");
	if($missings->num_rows <= 0) {
		$missings->close();
		return "";
	}

	$body = "<tr>\n";

	$cssborrower = "";
	if($view == "borrower$borrowerId") {
		$cssborrower = " missing_display_name_borrower";
	}

	$body .= "<td class=\"missing_display_name$cssborrower\" colspan=\"2\">\n";
	$body .= "<a id=\"borrower$borrowerId\">{$person->name} ({$missings->num_rows})</a>\n";
	$body .= "</td>\n";
	$body .= "</tr>\n";

	while($item = $missings->fetch_object()) {
		$body .= getMissingItem($page, $borrowerId, $item);
	}
	$missings->close();
	return $body;
}


function getBorrowers($page) {
	$borrowers = $page->DB_QueryManage("SELECT * FROM `borrowers` ORDER BY `name` ASC");
	if($borrowers->num_rows == 0) {
		$borrowers->close();
		return "Nobody is registered as a borrower...\n";
	}

	$body = "<div class=\"missing_display_table\">\n";
	$body .= "<table class=\"missing_display\">\n";
	while($person = $borrowers->fetch_object()) {
		$body .= getMissingsFromBorrower($page, $person);
	}
	$borrowers->close();

	$body .= "</table>\n";
	$body .= "</div>\n";
	return $body;
}


function getBorrowed($page) {
	$checkDoMiss = $page->DB_QueryManage("SELECT COUNT(*) AS `howmany` FROM `missings`");
	$checkFetch = $checkDoMiss->fetch_object();
	$checkDoMiss->close();
	if($checkFetch->howmany <= 0) {
		return "Nothing is being borrowed for now...\n";
	}

	return getBorrowers($page);
}


$body .= getBorrowed($page);
$body .= "</div>\n";

$page->show($body);
unset($page);
?>
