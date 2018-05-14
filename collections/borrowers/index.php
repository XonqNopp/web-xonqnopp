<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->initDB();

$page->CSS_ppJump(2);
$page->CSS_ppWing();

$body = "";
$args = new stdClass();
$args->page = "..";
$body .= $page->GoHome($args);
$body .= $page->SetTitle("List of known borrowers");
$page->HotBooty();

$UserIsAdmin = $page->UserIsAdmin();

$body .= "<div class=\"wide\">\n";
$body .= "<div class=\"lhead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"chead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"rhead\">\n";
if($UserIsAdmin) {
	// Propose to add a new if authorized
	$body .= "<a href=\"insert.php\" title=\"Add a borrower\">Add a borrower</a>\n";
}
$body .= "</div>\n";
$body .= "</div>\n";

// Display exsiting ones
$query = $page->DB_QueryManage("SELECT * FROM `borrowers` ORDER BY `name` ASC");
if($query->num_rows == 0) {
	$body .= "Sorry, no one stored yet...";
} else {
	$body .= "<div class=\"borrower_display_table\">\n";
	$body .= "<table class=\"borrower_display\">\n";
	$body .= "<tr class=\"borrower_display_header\">\n";
	$body .= "<th>Who</th>\n";
	$body .= "<th>Number</th>\n";
	$body .= "</tr>\n";
	while($it = $query->fetch_object()) {
		$id = $it->id;
		$name = $it->name;
		$body .= "<tr class=\"borrower_display\">\n";
		$body .= "<td class=\"borrower_display\">\n";
		if($UserIsAdmin) {
			$body .= "<a href=\"insert.php?id=$id\" title=\"$name\">";
		}
		$body .= "$name";
		if($UserIsAdmin) {
			$body .= "</a>";
		}
		$body .= "\n";
		$body .= "</td>\n";
		// Fetch count items borrowed
		$howmany_q = $page->DB_IdManage("SELECT COUNT(*) AS `how_many` FROM `missings` WHERE `borrower` = ?", $id);
		$howmany_q->bind_result($howmany);
		$howmany_q->fetch();
		$howmany_q->close();
		if($howmany == 0) {
			$plural = "";
			$howmany = "no";
			$the_link = "no item";
		} else {
			if($howmany == 1) {
				$plural = "";
			} else {
				$plural = "s";
			}
			$the_link = "<a href=\"../missings/index.php?view=borrower$id#borrower$id\" title=\"$howmany item$plural\">$howmany item$plural</a>";
		}
		$body .= "<td class=\"borrower_display_count\">";
		$body .= $the_link;
		$body .= "</td>\n";
		$body .= "</tr>\n";
	}
	$body .= "</table>\n";
	$body .= "</div>\n";
}
$query->close();

$page->show($body);
unset($page);
?>
