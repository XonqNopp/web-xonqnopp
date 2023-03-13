<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require("${funcpath}_local/borrowback.php");

$page = new PhPage($rootPath);

//$page->initHTML();
//$page->LogLevelUp(6);

$page->initDB();

// Borrowed item came home
borrow_back($page, "bds");

$page->CSS_ppJump(2);
$page->CSS_ppWing();

$body = "";

$GI = $page->UserIsAdmin();

//// Find serie
$serie_id = $_GET["id"];
$serie = "";
$findserie = $page->DB_IdManage("SELECT `name` FROM `bd_series` WHERE `id` = ?", $serie_id);
$findserie->store_result();
if($findserie->num_rows == 0) {
	$findserie->close();
	$page->HeaderLocation();
} else {
	$findserie->bind_result($serie);
	$findserie->fetch();
	$findserie->close();
}
	// Title
	$page_title = "$serie (BDs)";
	if($serie == "") {
		$page_title = "Hors s&eacute;rie (BDs)";
	}
//
$args = new stdClass();
$args->rootpage = "..";
$body .= $page->GoHome($args);// add previous+next
$body .= $page->SetTitle($page_title);

$page->HotBooty();

$body .= "<div class=\"wide\">\n";
$body .= "<div class=\"lhead\">\n";
$body .= "<a href=\"search.php\" title=\"Search\">Search</a>\n";
$body .= "</div>\n";
$body .= "<div class=\"chead\">\n";
$body .= "</div>\n";
//// Propose to add a new
$body .= "<div class=\"rhead\">\n";
$body .= "<a href=\"../missings/index.php?view=bds\" title=\"Missing BDs\">Missing BDs</a>\n";
if($GI) {
	$body .= "<br />\n";
	if($serie_id > 1) {
		$body .= "<a href=\"serie_insert.php?id=$serie_id\" title=\"Edit BD serie\">Edit BD serie</a><br />\n";
	}
	//$body .= "<a href=\"../insert_isbn.php\" title=\"Add a BD by ISBN\">New ISBN</a><br />\n";
	$body .= "<a href=\"insert.php?serie_id=$serie_id\" title=\"Add a BD\">New BD</a><br />\n";
	$body .= "<a href=\"serie_insert.php\" title=\"Add a BD serie\">New serie</a>\n";
}
$body .= "</div>\n";

$body .= "</div>\n";

// Fetch all from this serie
$display_serie = $page->DB_IdManage("SELECT * FROM `bds` WHERE `serie_id` = ? ORDER BY `tome` ASC, `title` ASC", $serie_id);
$display_serie->store_result();
if($display_serie->num_rows == 0) {
	$body .= "<div>No BD yet</div>\n";
} else {
	$display_serie->bind_result($id, $isbn, $serie_id, $tome, $title, $ti, $author, $publisher, $date, $borrowed);
	$body .= "<div class=\"bd_serie_table\">\n";
	$body .= "<table class=\"bd_serie_table\">\n";
	while($display_serie->fetch()) {
		if($borrowed == "1") {
			$isbor = " away";
		} else {
			$isbor = "";
		}
		$body .= "<tr class=\"bd_serie_table$isbor\">\n";
		//// Edit'n'Borrow
		$body .= "<td class=\"bd_serie_edit twenty\">\n";
		if($GI) {
			$body .= "<a href=\"insert.php?id=$id\" title=\"edit\">edit</a>\n";
			$body .= "&nbsp;\n";
			if($borrowed) {
				$body .= "<a href=\"serie_display.php?id=$serie_id&amp;back=$id\" title=\"back\">back</a>\n";
				$body .= "&nbsp;\n";
				$body .= "<a href=\"../missings/index.php?view=bds$id#bds$id\" title=\"who\">who</a>\n";
			} else {
				$body .= "<a href=\"../missings/insert.php?db=bds&amp;id=$id\" title=\"borrow\">borrow</a>\n";
			}
		}
		$body .= "</td>\n";
		//// Tome
		$body .= "<td class=\"bd_serie_table_tome ten\">\n";
		if($tome > 0) {
			$body .= "$tome\n";
		}
		$body .= "</td>\n";
		//// Title
		$body .= "<td id=\"bd$id\" class=\"bd_serie_table_title\">\n";
		$body .= "$title\n";
		$body .= "</td>\n";
		//// author
		$body .= "<td class=\"bd_serie_table_author\">\n";
		$body .= "$author\n";
		$body .= "</td>\n";
		//
		$body .= "</tr>\n";
	}
	$body .= "</table>\n";
	$body .= "</div>\n";
}
$display_serie->close();

/*** Printing ***/
$page->show($body);
unset($page);
?>
