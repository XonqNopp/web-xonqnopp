<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->LogLevelUp(6);
//$page->initHTML();
$page->initDB();

require("${funcpath}_local/borrowback.php");

// Borrowed item came home
borrow_back($page, "dvds");

$page->CSS_ppJump(2);
$page->CSS_ppWing();

$GI = $page->UserIsAdmin();

$body = "";
$args = new stdClass();
$args->rootpage = "..";
$body .= $page->GoHome($args);

// Find which serie we are dealing with
$serie_id = $_GET["id"];
$findserie = $page->DB_IdManage("SELECT * FROM `dvds` WHERE `id` = ?", $serie_id);
$findserie->store_result();
if($findserie->num_rows == 0) {
	$findserie->close();
	exit("Error bad id");
}
$findserie->bind_result($serie_id, $title, $director, $actors, $languages, $subtitles, $duration, $serie, $number, $category, $summary, $burnt, $format, $borrowed);
$findserie->fetch();
$findserie->close();
// Title
$body .= $page->SetTitle("$serie (DVDs)");
$page->HotBooty();

$body .= "<div class=\"wide\">\n";
$body .= "<div class=\"lhead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"chead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"rhead\">\n";
$body .= "<a href=\"../missings/index.php?view=dvds\" title=\"Missing DVDs\">Missing DVDs</a>\n";
if($GI) {
	$body .= "<br />\n";
	// Propose to add a new if authorized
	$body .= "<a href=\"insert.php\" title=\"New DVD\">New DVD</a>\n";
	//$body .= "<a href=\"../insert_barcode.php\" title=\"New barcode\">New barcode</a>\n";
	//$body .= "<a href=\"../insert_imdb.php\" title=\"New IMDB\">New IMDB</a>\n";
}
$body .= "</div>\n";
$body .= "</div>\n";

// Fetch all from this serie
$sql_serie = $serie;
$dvds = $page->DB_QueryPrepare("SELECT * FROM `dvds` WHERE `serie` = ? ORDER BY `number` ASC, `title` ASC");
$dvds->bind_param("s", $sql_serie);
$page->DB_ExecuteManage($dvds);
$dvds->store_result();
if($dvds->num_rows == 0) {
	$dvds->close();
	$page->HeaderLocation();
}
$dvds->bind_result($id, $title, $director, $actors, $languages, $subtitles, $duration, $serie, $number, $category, $summary, $burnt, $format, $borrowed);
$body .= "<div class=\"dvd_serie_table\">\n";
$body .= "<table class=\"dvd_serie_table\">\n";
while($dvds->fetch()) {
	$csstitle = "dvd_serie_table_title";
	if($borrowed == 1) {
		$csstitle .= " away";
	}
	$body .= "<tr class=\"dvd_serie_table\">\n";
	// Edit
	$body .= "<td class=\"dvd_serie_table_edit\">\n";
	if($GI) {
		$body .= "<a href=\"insert.php?id=$id\" title=\"edit\">edit</a>\n";
	}
	$body .= "</td>\n";
	// Borrow
	$body .= "<td class=\"dvd_serie_borrow\">\n";
	if($GI) {
		$body .= "<a href=\"";
		if($borrowed) {
			$body .= "serie_display.php?id=$serie_id&amp;back=$id\" title=\"back\">back";
			$body .= "&nbsp;-&nbsp;\n";
			$body .= "<a href=\"../missings/index.php?view=dvds$id#dvds$id\" title=\"who\">who";
		} else {
			$body .= "../missings/insert.php?db=dvds&amp;id=$id\" title=\"borrow\">borrow";
		}
		$body .= "</a>\n";
	}
	$body .= "</td>\n";
	// Number
	if($number != "" && $number != "0") {
		$body .= "<td class=\"dvd_serie_table_number\">$number</td>\n";
	} else {
		$body .= "<td class=\"dvd_serie_table_number\"></td>\n";
	}
	// Title
	$body .= "<td class=\"$csstitle\">\n";
	$body .= "<a id=\"b$id\" class=\"dvd_serie_display_title\"";
	$body .= " href=\"display.php?id=$id\" title=\"$title\">$title</a>\n";
	$body .= "</td>\n";
	$body .= "</tr>\n";
}
$body .= "</table>\n";
$body .= "</div>\n";
$dvds->close();

$page->show($body);
unset($page);
?>
