<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->bobbyTable->init();
$languages = array("fr" => "French", "en" => "English", "it" => "Italian", "de" => "German", "??" => "other");


$body = "";

$page->cssHelper->dirUpWing();

$body .= $page->bodyBuilder->goHome("..");

$id = $_GET["id"];
$query = $page->bobbyTable->idManage("SELECT * FROM `books` WHERE `id` = ?", $id);
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

    if($page->loginHelper->userIsAdmin()) {
        // Edit
        $body .= $page->bodyBuilder->anchor("insert.php?id=$id", "edit");

        // Propose to borrow
        if($borrowed == 1) {
            $body .= $page->bodyBuilder->anchor("../missings/index.php?view=books$id#bookds$id", "who");

        } else {
            $body .= $page->bodyBuilder->anchor("../missings/insert.php?db=books&amp;id=$id", "borrow");
        }

    }

    $body .= "</div><!-- lhead -->\n";

    $body .= "<div class=\"chead\"></div>\n";

    // R head
    $body .= "<div class=\"rhead\">\n";
    $body .= $page->bodyBuilder->anchor("../missings/index.php?view=books", "Missing books");
    // Search
    // Propose to add a new if authorized
    if($page->loginHelper->userIsAdmin()) {
        $body .= "<br />\n";
        // Add
        $body .= $page->bodyBuilder->anchor("insert.php", "New book") . "<br />\n";
        // Edit
        //$body .= $page->bodyBuilder->anchor("insert.php", "edit");
    }
    $body .= "</div><!-- rhead -->\n";
    $body .= "</div><!-- wide -->\n";

    $body .= "<div>\n";

    // Serie?
    $body .= "<div class=\"book_info_serie\">$serie";
    if($number > 0) {
        $body .= " ($number)";
    }
    $body .= "</div><!-- book_info_serie -->\n";

    $body .= "<div class=\"book_info_lang\">" . $languages[$booklang] . "</div>\n";
    $body .= "<div class=\"book_info_summary\">$summary</div>\n";

    $body .= "</div>\n";
}
$query->close();

$page->htmlHelper->setTitle($title);
$page->htmlHelper->hotBooty();

echo $body;
?>
