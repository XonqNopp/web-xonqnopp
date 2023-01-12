<?php
require("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->logger->levelUp(6);

require("$funcpath/form_fields.php");
global $theSelectInput;


$page->loginHelper->notAllowed();

if(isset($_POST["isbn"])) {
	if(isset($_POST["cancel"]) && $_POST["cancel"]=="Cancel") {
		$page->htmlHelper->headerLocation("index.php");
	} else {
		/*** Fetch data from isbndb.com ***/
		$isbn = $_POST["isbn"];
		$type = $_POST["type"];
		$page->htmlHelper->headerLocation("{$type}/insert.php?isbn=$isbn");
	}
	exit;
}


$body = $page->bodyHelper->goHome();
$body .= $page->htmlHelper->setTitle("Insert by ISBN");
$page->htmlHelper->hotBooty();

$type = "bds";
if(isset($_GET["book"])) {
	$type = "books";
}

$body .= "<form action=\"insert_isbn.php\" method=\"POST\">\n";
$body .= "<div class=\"isbnrequest\">\n";
$body .= "<div class=\"isbntext\">Enter the ISBN&nbsp;:</div>\n";
$body .= "<div class=\"isbnfield\"><input type=\"number\" min=\"0\" name=\"isbn\" autofocus=\"autofocus\" /></div>\n";

$types = array("bds" => "BD", "books" => "Book");
$body .= $theSelectInput->get("type", $types, $type, "Type");

$body .= "<div class=\"isbnsubmit\">\n";
$body .= "<input type=\"submit\" name=\"validate\" value=\"Enter\" />\n";
$body .= "<input type=\"submit\" name=\"cancel\" value=\"Cancel\" />\n";
$body .= "</div>\n";
$body .= "</div>\n";
$body .= "</form>\n";
/*** Printing ***/
echo $body;
unset($page);
?>
