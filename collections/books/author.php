<?php
require("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->dbHelper->init();

// ONLY BEFORE READY
$page->htmlHelper->headerRefresh(5, "index.php");


$book_id = $_GET["id"];
// Get book author
// Look for books that match the author

$page->cssHelper->dirUpWing();

$body = $page->bodyHelper->goHome("..");

$body .= $page->htmlHelper->setTitle("On work...");
$page->htmlHelper->hotBooty();

echo $body;
unset($page);
?>
