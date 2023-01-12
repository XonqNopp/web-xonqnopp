<?php
require("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->loginHelper->notAllowed();
//$page->logger->levelUp(6);

require("$funcpath/form_fields.php");
use FieldAttributes;
global $theHiddenInput;
global $theTextInput;


$page->dbHelper->init();

$page->cssHelper->dirUpWing();
$page->htmlHelper->jsForm();

//// init vars
$id = 0;
$name = "";

if(isset($_POST["erase"])) {
	$id = $_POST["id"];
	$howmany_q = $page->dbHelper->idManage("SELECT COUNT(*) AS `how_many` FROM `missings` WHERE `borrower` = ?", $id);
	$howmany_q->bind_result($howmany);
	$howmany_q->fetch();
	$howmany_q->close();
	if($howmany > 0) {
		$page->logger->error("Cannot delete borrower, still has $howmany item" . ($howmany > 1 ? "s" : ""));
		$_GET["id"] = $id;
	} else {
		$page->dbHelper->idManage("DELETE FROM `{$page->dbHelper->dbName}` . `borrowers` WHERE `borrowers` . `id` = ? LIMIT 1;", $id);
		$page->htmlHelper->headerLocation();
	}
}
if(isset($_POST["submit"])) {
	if(isset($_POST["id"])) {
		$id = $_POST["id"];
	}
	$name = $page->dbText->field2SQL($_POST["name"]);
	if($id > 0) {
		$query = $page->dbHelper->queryPrepare("UPDATE `{$page->dbHelper->dbName}` . `borrowers` SET `name` = ? WHERE `borrowers` . `id` = ? LIMIT 1;");
		$query->bind_param("si", $name, $id);
	} else {
		$query = $page->dbHelper->queryPrepare("INSERT INTO `{$page->dbHelper->dbName}` . `borrowers` (`id`, `name`) VALUES(NULL, ?)");
		$query->bind_param("s", $name);
	}
	$page->dbHelper->executeManage($query);
	$page->htmlHelper->headerLocation("index.php");
}

$page_title = "Add a new borrower";

$body = $page->bodyHelper->goHome("..");

if(isset($_GET["id"])) {
	$id = $_GET["id"];
	$find = $page->dbHelper->idManage("SELECT * FROM `borrowers` WHERE `id` = ?", $id);
	$find->store_result();
	if($find->num_rows == 0) {
		$find->close();
		exit("Error bad id");
	} else {
		$find->bind_result($id, $name);
		$find->fetch();
		$find->close();
		$page_title = "Edit infos for $name";
	}
}

$body .= $page->htmlHelper->setTitle($page_title);
$page->htmlHelper->hotBooty();

$body .= "<div class=\"whole\">\n";

$body .= $page->formHelper->tag();

if($id > 0) {
	$body .= $theHiddenInput->get("id", $id);
}

$nameAttr = FieldAttributes(false, true);
$body .= $theTextInput->get("name", $name, "Name", NULL, $nameAttr);

$body .= $page->formHelper->subButt($id > 0, $name);
$body .= "</div>\n";

echo $body;
unset($page);
?>
