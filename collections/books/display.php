<?php
// TODO SetTitle
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->initDB();
$languages = array("fr" => "French", "en" => "English", "it" => "Italian", "de" => "German", "??" => "other");


$body = "";

$page->CSS_ppJump(2);
$page->CSS_ppWing();

$args = new stdClass();
$args->rootpage = "..";
$body .= $page->GoHome($args);

$id = $_GET["id"];
$query = $page->DB_IdManage("SELECT * FROM `books` WHERE `id` = ?", $id);
$query->store_result();
if($query->num_rows == 0) {
	$title = "Sorry, no result...";
	$body .= "$title\n";
} else {
	$query->bind_result($id, $isbn, $author, $title, $serie, $number, $publisher, $date, $booklang, $category, $summary, $borrowed);
	$query->fetch();
	if($borrowed == "1") {
		$isbor = " class=\"away\"";
	} else {
		$isbor = "";
	}

	// Display
	$body .= "<h1$isbor>$title</h1>\n";
	$body .= "<h2>$author</h2>\n";

	// heads
	$body .= "<div class=\"wide\">\n";

	// L head
	$body .= "<div class=\"lhead\">\n";

	if($page->UserIsAdmin()) {
		// Edit
		$body .= "<a href=\"insert.php?id=$id\" title=\"edit\">edit</a><br />\n";

		// Propose to borrow
		if($borrowed == 1) {
			$body .= "<a href=\"../missings/index.php?view=books$id#books$id\" title=\"who\">who</a>\n";

		} else {
			$body .= "<a href=\"../missings/insert.php?db=books&amp;id=$id\" title=\"borrow\">borrow</a>\n";
		}

	}

	$body .= "</div>\n";

	$body .= "<div class=\"chead\">\n";
	$body .= "</div>\n";

	// R head
	$body .= "<div class=\"rhead\">\n";
	$body .= "<a href=\"../missings/index.php?view=books\" title=\"Missing books\">Missing books</a>\n";
	// Search
	// Propose to add a new if authorized
	if($page->UserIsAdmin()) {
		$body .= "<br />\n";
		// Add
		$body .= "<a href=\"insert.php\" title=\"New book\">New book</a><br />\n";
		// ISBN
		$body .= "<a href=\"../insert_isbn.php?book\" title=\"New ISBN\">New ISBN</a><br />\n";
		// Edit
		//$body .= "<a href=\"insert.php?id=$id\" title=\"Edit infos for this book\">Edit</a>\n";
	}
	$body .= "</div>\n";
	$body .= "<div class=\"whole\">\n";

	// Serie?
	$body .= "<div class=\"book_info_serie\">$serie";
	if($number > 0) {
		$body .= " ($number)";
	}
	$body .= "</div>\n";
	// Language
	$body .= "<div class=\"book_info_lang\">" . $languages[$booklang] . "</div>\n";
	// Summary
	$body .= "<div class=\"book_info_summary\">$summary</div>\n";
	$body .= "</div>\n";
}
$query->close();

$page->SetTitle($title);
$page->HotBooty();

$page->show($body);
unset($page);
?>
