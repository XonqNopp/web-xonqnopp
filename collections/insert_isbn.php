<?php
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->LogLevelUp(6);

$page->NotAllowed();

if(isset($_POST["isbn"])) {
	if(isset($_POST["cancel"]) && $_POST["cancel"]=="Cancel") {
		$page->HeaderLocation("index.php");
	} else {
		/*** Fetch data from isbndb.com ***/
		$isbn = $_POST["isbn"];
		$type = $_POST["type"];
		$page->HeaderLocation("${type}/insert.php?isbn=$isbn");
	}
	exit;
}

$page->CSS_ppJump();

$body = "";
$body .= $page->GoHome();
$body .= $page->SetTitle("Insert by ISBN");
$page->HotBooty();

$type = "bds";
if(isset($_GET["book"])) {
	$type = "books";
}

$body .= "<form action=\"insert_isbn.php\" method=\"POST\">\n";
$body .= "<div class=\"isbnrequest\">\n";
$body .= "<div class=\"isbntext\">Enter the ISBN&nbsp;:</div>\n";
$body .= "<div class=\"isbnfield\"><input type=\"number\" min=\"0\" name=\"isbn\" autofocus=\"autofocus\" /></div>\n";
	$args = new stdClass();
	$args->type = "select";
	$args->title = "Type";
	$args->name = "type";
	$args->value = $type;
	$args->list = array("bds" => "BD", "books" => "Book");
	$args->css = "isbnradio";
	$body .= $page->FormField($args);
$body .= "<div class=\"isbnsubmit\">\n";
$body .= "<input type=\"submit\" name=\"validate\" value=\"Enter\" />\n";
$body .= "<input type=\"submit\" name=\"cancel\" value=\"Cancel\" />\n";
$body .= "</div>\n";
$body .= "</div>\n";
$body .= "</form>\n";
/*** Printing ***/
$page->show($body);
unset($page);
?>
