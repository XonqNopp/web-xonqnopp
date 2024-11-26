<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->bobbyTable->init();

$page->cssHelper->dirUpWing();

$body = $page->bodyBuilder->goHome("..");

// Find which serie we are dealing with
$serie_id = $_GET["id"];
$serie = "";
$findserie = $page->bobbyTable->idManage("SELECT * FROM `books` WHERE `id` = ?", $serie_id);
$findserie->store_result();
if($findserie->num_rows == 0) {
    $page->htmlHelper->headerLocation();
}
$findserie->bind_result($serie_id, $isbn, $author, $title, $serie, $number, $publisher, $date, $language, $category, $summary, $borrowed);
$findserie->fetch();
$findserie->close();
// Title
$body .= $page->htmlHelper->setTitle("$serie (books)");

$page->htmlHelper->hotBooty();


$body .= "<div class=\"wide\">\n";
$body .= "<div class=\"lhead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"chead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"rhead\">\n";
$body .= $page->bodyBuilder->anchor("../missings/index.php?view=books", "Missing books");
if($page->loginHelper->userIsAdmin()) {
    // Propose to add a new if authorized
    $body .= "<br />\n";
    $body .= $page->bodyBuilder->anchor("insert.php", "New book");
}
$body .= "</div>\n";
$body .= "</div>\n";

// Fetch all from this serie
$sql_serie = $serie;
$books = $page->bobbyTable->queryPrepare("SELECT * FROM `books` WHERE `serie` = ? ORDER BY `number` ASC, `title` ASC");
$books->bind_param("s", $sql_serie);
$page->bobbyTable->executeManage($books);
$books->store_result();
if($books->num_rows == 0) {
    $page->htmlHelper->headerLocation();
}
$books->bind_result($id, $isbn, $author, $title, $serie, $number, $publisher, $date, $language, $category, $summary, $borrowed);
$body .= "<div class=\"book_serie_table\">\n";
$body .= $page->butler->tableOpen(array("class" => "book_serie_table"));
while($books->fetch()) {
    $csstitle = "book_serie_table_title";
    if($borrowed) {
        $csstitle .= " away";
    }
    $body .= $page->butler->rowOpen(array("class" => "book_serie_table", "b$id"));

    $body .= $page->butler->cellOpen(array("class" => "book_serie_edit"));
    if($page->loginHelper->userIsAdmin()) {
        $body .= "<div class=\"InB EditBorrow\">\n";
        $body .= $page->bodyBuilder->anchor("insert.php?id=$id", "edit");
        $body .= "&nbsp;\n";
        if($borrowed) {
            $body .= $page->bodyBuilder->anchor("../missings/index.php?view=books$id#books$id", "who");

        } else {
            $body .= $page->bodyBuilder->anchor("../missings/insert.php?db=books&amp;id=$id", "borrow");
        }

        $body .= "</div>\n";
    }
    $body .= $page->butler->cellClose();

    $body .= $page->butler->cell($number, array("class" => "book_serie_table_number"));
    $body .= $page->butler->cell($page->bodyBuilder->anchor("display.php?id=$id", $title), array("class" => $csstitle));

    $body .= $page->butler->rowClose();
}
$body .= $page->butler->tableClose();
$body .= "</div><!-- book_serie_table -->\n";
$books->close();

echo $body;
?>
