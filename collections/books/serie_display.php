<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->initDB();

require("${funcpath}_local/borrowback.php");

// Borrowed item came home
borrow_back($page, "books");

$page->CSS_ppJump(2);
$page->CSS_ppWing();

$body = "";
$args = new stdClass();
$args->rootpage = "..";
$body .= $page->GoHome($args);

// Find which serie we are dealing with
$serie_id = $_GET["id"];
$serie = "";
$findserie = $page->DB_IdManage("SELECT * FROM `books` WHERE `id` = ?", $serie_id);
$findserie->store_result();
if($findserie->num_rows == 0) {
	$page->HeaderLocation();
}
$findserie->bind_result($serie_id, $isbn, $author, $title, $serie, $number, $publisher, $date, $language, $category, $summary, $borrowed);
$findserie->fetch();
$findserie->close();
// Title
$body .= $page->SetTitle("$serie (books)");

$page->HotBooty();


$body .= "<div class=\"wide\">\n";
$body .= "<div class=\"lhead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"chead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"rhead\">\n";
$body .= "<a href=\"../missings/index.php?view=books\" title=\"Missing books\">Missing books</a>\n";
if($page->UserIsAdmin()) {
	// Propose to add a new if authorized
	$body .= "<br />\n";
	$body .= "<a href=\"insert.php\" title=\"New book\">New book</a><br />\n";
	$body .= "<a href=\"../insert_isbn.php?book\" title=\"New ISBN\">New ISBN</a>\n";
}
$body .= "</div>\n";
$body .= "</div>\n";

// Fetch all from this serie
$sql_serie = $serie;
$books = $page->DB_QueryPrepare("SELECT * FROM `books` WHERE `serie` = ? ORDER BY `number` ASC, `title` ASC");
$books->bind_param("s", $sql_serie);
$page->DB_ExecuteManage($books);
$books->store_result();
if($books->num_rows == 0) {
	$page->HeaderLocation();
}
$books->bind_result($id, $isbn, $author, $title, $serie, $number, $publisher, $date, $language, $category, $summary, $borrowed);
$body .= "<div class=\"book_serie_table\">\n";
$body .= "<table class=\"book_serie_table\">\n";
while($books->fetch()) {
	$csstitle = "book_serie_table_title";
	if($borrowed) {
		$csstitle .= " away";
	}
	$body .= "<tr class=\"book_serie_table\" id=\"b$id\">\n";
	$body .= "<td class=\"book_serie_edit\">\n";
	if($page->UserIsAdmin()) {
		$body .= "<div class=\"InB EditBorrow\">\n";
		$body .= "<a href=\"insert.php?id=$id\" title=\"edit\">edit</a>\n";
		$body .= "&nbsp;\n";
		if($borrowed) {
			$body .= "<a href=\"serie_display.php?id=$id&back\" title=\"back\">back</a>\n";
			$body .= "&nbsp;-&nbsp;\n";
			$body .= "<a href=\"../missings/index.php?view=books$id#books$id\" title=\"who\">who";
		} else {
			$body .= "<a href=\"../missings/insert.php?db=books&amp;id=$id\" title=\"borrow\">borrow</a>\n";
		}
		$body .= "</div>\n";
	}
	$body .= "</td>\n";
	$body .= "<td class=\"book_serie_table_number\">$number</td>\n";
	$body .= "<td class=\"$csstitle\">\n";
	$body .= "<a href=\"display.php?id=$id\" title=\"$title\">$title</a>\n";
	$body .= "</td>\n";
	$body .= "<td class=\"book_serie_table_author\">\n";
	$body .= "<a href=\"author.php?id=$id\" title=\"$author\">$author</a>\n";
	$body .= "</td>\n";
	$body .= "</tr>\n";
}
$body .= "</table>\n";
$body .= "</div>\n";
$books->close();

$page->show($body);
unset($page);
?>
