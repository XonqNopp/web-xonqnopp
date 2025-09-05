<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require_once("{$funcpath}_local/borrowback.php");
$page = new PhPage($rootPath);
$page->bobbyTable->init();

// Borrowed item came home (link from missing index)
if(isset($_GET["back"])) {
    $backId = NULL;
    if(isset($_GET["id"])) {
        $backId = $_GET["id"];
    }
    borrow_back($page, "books", $_GET["back"], $backId);
}

$page->cssHelper->dirUpWing();

$GI = $page->loginHelper->userIsAdmin();

$getcount = $page->bobbyTable->queryManage("SELECT COUNT(*) AS `the_count` FROM `books`");
$fetch_count = $getcount->fetch_object();
$book_count = $fetch_count->the_count;
$getcount->close();

$body = $page->bodyBuilder->goHome("..");

$body .= $page->htmlHelper->setTitle("My $book_count books");
$page->htmlHelper->hotBooty();

$body .= "<div class=\"wide\">\n";
$body .= "<div class=\"lhead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"chead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"rhead\">\n";
$body .= $page->bodyBuilder->anchor("../missings/index.php?view=books", "Missing books");
if($GI) {
    // Propose to add a new if authorized
    $body .= "<br>\n";
    $body .= $page->bodyBuilder->anchor("insert.php", "New book");
}
$body .= "</div>\n";
$body .= "</div>\n";

// Fetch data
$books = $page->bobbyTable->queryAlpha("books", "title");
$series = $page->bobbyTable->queryXi("books", array("serie" => "a", "number" => "", "title" => "a"));
$series_count = $page->bobbyTable->queryManage("SELECT COUNT(DISTINCT(serie)) AS `sc` FROM `books`");
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
            $body .= $page->waitress->tableOpen(array("class" => "book_display_table_serie"));
            $body .= $page->waitress->rowOpen();
            $body .= $page->waitress->cellOpen();
            $serie_index = 0;
            while($volume = $series->fetch_object()) {
                $serie = $volume->serie;
                if($serie != $old_serie) {
                    $old_serie = $serie;
                    $serie_index++;
                    if($serie_index > $N * 1.0 / $serie_width) {
                        $serie_index = 0;
                        $body .= $page->waitress->cellClose();
                        $body .= $page->waitress->cellOpen();
                    }
                    $id = $volume->id;
                    $title = $volume->serie;
                    $body .= "<div class=\"book_serie_item\">\n";
                    $body .= $page->bodyBuilder->anchor("serie_display.php?id=$id", $title);
                    $body .= "</div>\n";
                }
            }
            $body .= $page->waitress->cellClose();
            $body .= $page->waitress->rowClose();
            $body .= $page->waitress->tableClose();

            $body .= "<!--    BOOKS    -->\n";
            $body .= "<h3 class=\"book_display\">Books</h3>\n";
        }
    //
        // Individual books (including those in series)
        $book_width = 2;
        $N = $books->num_rows;
        $index = 0;
        $body .= $page->waitress->tableOpen(array("class" => "book_display_table"));
        $body .= $page->waitress->rowOpen();
        $body .= $page->waitress->cellOpen();
        while($book = $books->fetch_object()) {
            $index++;
            if($index > $N * 1.0 / $book_width) {
                $index = 0;
                $body .= $page->waitress->cellClose();
                $body .= $page->waitress->cellOpen();
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
                $body .= $page->bodyBuilder->anchor("insert.php?id=$id", "edit");
                $body .= "&nbsp;\n";
                if($book->borrowed) {
                    $body .= $page->bodyBuilder->anchor("../missings/index.php?view=books$id#books$id", "who");

                } else {
                    $body .= $page->bodyBuilder->anchor("../missings/insert.php?db=books&amp;id=$id", "borrow");
                }

                $body .= "</div>\n";
            }

            $body .= "<div class=\"InB MainBook BookCell\">\n";
            $body .= $page->bodyBuilder->anchor("display.php?id=$id", $title);

            $body .= "</div>\n";
            $body .= "</div>\n";
        }
        $body .= $page->waitress->cellClose();
        $body .= $page->waitress->rowClose();
        $body .= $page->waitress->tableClose();
}
$books->close();
$series->close();

echo $body;
?>
