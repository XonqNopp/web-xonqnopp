<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->initDB();

//// ONLY BEFORE READY
$page->HeaderRefresh(5, "index.php");


$book_id = $_GET["id"];
// Get book author
// Look for books that match the author

$page->CSS_ppJump(2);
$page->CSS_ppWing();

$body = "";
$args = new stdClass();
$args->rootpage = "..";
$body .= $page->GoHome($args);

$body .= $page->SetTitle("On work...");
$page->HotBooty();

$page->show($body);
unset($page);
?>
