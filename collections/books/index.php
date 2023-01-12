<?php
require("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require("{$funcpath}_local/borrowback.php");
$page = new PhPage($rootPath);
$page->dbHelper->init();

// Borrowed item came home
if(isset($_GET["back"])) {
	$backId = NULL;
	if(isset($_GET["id"])) {
		$backId = $_GET["id"];
	}
	borrow_back($page, "books", $_GET["back"], $backId);
}

$page->cssHelper->dirUpWing();

$GI = $page->loginHelper->userIsAdmin();

$getcount = $page->dbHelper->queryManage("SELECT COUNT(*) AS `the_count` FROM `books`");
$fetch_count = $getcount->fetch_object();
$book_count = $fetch_count->the_count;
$getcount->close();

$body = $page->bodyHelper->goHome("..");

$body .= $page->htmlHelper->setTitle("My $book_count books");
$page->htmlHelper->hotBooty();

$body .= "<div class=\"wide\">\n";
$body .= "<div class=\"lhead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"chead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"rhead\">\n";
$body .= "<a href=\"../missings/index.php?view=books\" title=\"Missing books\">Missing books</a>\n";
if($GI) {
	//// Propose to add a new if authorized
	$body .= "<br />\n";
	$body .= "<a href=\"insert.php\" title=\"Add a book\">New book</a><br />\n";
	$body .= "<a href=\"../insert_isbn.php?book\" title=\"New ISBN\">New ISBN</a>\n";
}
$body .= "</div>\n";
$body .= "</div>\n";

// Fetch data
$books = $page->dbHelper->queryAlpha("books", "title");
$series = $page->dbHelper->queryXi("books", array("serie" => "a", "number" => "", "title" => "a"));
$series_count = $page->dbHelper->queryManage("SELECT COUNT(DISTINCT(serie)) AS `sc` FROM `books`");
$sc = $series_count->fetch_object();
$series_count->close();
$N = $sc->sc;
if($books->num_rows == 0) {
	$body .= "Sorry, no result to display...";
} else {
	// Results
		// Series
		$old_serie = "";
		if($N > 0) {
			$serie_width = 3;
			$body .= "<!--    SERIES   -->\n";
			$body .= "<h3 class=\"book_display\">Series</h3>\n";
			$body .= $page->tableHelper->open("book_display_table_serie");
			$body .= $page->tableHelper->rowOpen();
			$body .= $page->tableHelper->cellOpen();
			$serie_index = 0;
			while($volume = $series->fetch_object()) {
				$serie = $volume->serie;
				if($serie != $old_serie) {
					$old_serie = $serie;
					$serie_index++;
					if($serie_index > $N * 1.0 / $serie_width) {
						$serie_index = 0;
						$body .= $page->tableHelper->cellClose();
						$body .= $page->tableHelper->cellOpen();
					}
					$id = $volume->id;
					$title = $volume->serie;
					$body .= "<div class=\"book_serie_item\">\n";
					$body .= "<a href=\"serie_display.php?id=$id\" title=\"$title\">$title</a>";
					$body .= "</div>\n";
				}
			}
			$body .= $page->tableHelper->cellClose();
			$body .= $page->tableHelper->rowClose();
			$body .= $page->tableHelper->close();

			$body .= "<!--    BOOKS    -->\n";
			$body .= "<h3 class=\"book_display\">Books</h3>\n";
		}
	//
		// Individual books (including those in series)
		$book_width = 2;
		$N = $books->num_rows;
		$index = 0;
		$body .= $page->tableHelper->open("book_display_table");
		$body .= $page->tableHelper->rowOpen();
		$body .= $page->tableHelper->cellOpen();
		while($book = $books->fetch_object()) {
			$index++;
			if($index > $N * 1.0 / $book_width) {
				$index = 0;
				$body .= $page->tableHelper->cellClose();
				$body .= $page->tableHelper->cellOpen();
			}
			$id = $book->id;
			$title  = $book->title;
			$author = $book->author;
			$csscell = "flushleft";
			$body .= "<div id=\"book$id\" class=\"flushleft";

			if($book->borrowed) {
				$body .= " away";
			}

			$body .= "\">\n";

			if($GI) {
				$body .= "<div class=\"InB EditBorrow BookCell\">\n";
				$body .= "<a href=\"insert.php?id=$id\" title=\"edit\">edit</a>\n";
				$body .= "&nbsp;\n";
				if($book->borrowed) {
					$body .= "<a href=\"../missings/index.php?view=books$id#books$id\" title=\"who\">who";

				} else {
					$body .= "<a href=\"../missings/insert.php?db=books&amp;id=$id\" title=\"borrow\">borrow</a>\n";
				}

				$body .= "</div>\n";
			}

			$body .= "<div class=\"InB MainBook BookCell\">\n";
			$body .= "<a href=\"display.php?id=$id\" title=\"$title\">$title</a>\n";

			if($author != "") {
				$body .= "&nbsp;-&nbsp;\n";
				$body .= "<span class=\"author_link\">\n";
				$body .= "<a href=\"author.php?id=$id\" title=\"$author\">$author</a>";
				$body .= "</span>\n";
			}

			$body .= "</div>\n";
			$body .= "</div>\n";
		}
		$body .= $page->tableHelper->cellClose();
		$body .= $page->tableHelper->rowClose();
		$body .= $page->tableHelper->close();
}
$books->close();
$series->close();

echo $body;
unset($page);
?>
