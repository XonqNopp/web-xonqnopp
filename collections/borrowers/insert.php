<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->NotAllowed();
//$page->LogLevelUp(6);

$page->initDB();

$page->CSS_ppJump(2);
$page->CSS_ppWing();
$page->js_Form();

//// init vars
$id = 0;
$name = "";

if(isset($_POST["erase"])) {
	$id = $_POST["id"];
	$howmany_q = $page->DB_IdManage("SELECT COUNT(*) AS `how_many` FROM `missings` WHERE `borrower` = ?", $id);
	$howmany_q->bind_result($howmany);
	$howmany_q->fetch();
	$howmany_q->close();
	if($howmany > 0) {
		$page->NewError("Cannot delete borrower, still has $howmany item" . ($howmany > 1 ? "s" : ""));
		$_GET["id"] = $id;
	} else {
		$page->DB_IdManage("DELETE FROM `" . $page->ddb->DBname . "` . `borrowers` WHERE `borrowers` . `id` = ? LIMIT 1;", $id);
		$page->HeaderLocation();
	}
}
if(isset($_POST["submit"])) {
	if(isset($_POST["id"])) {
		$id = $_POST["id"];
	}
	$name = $page->field2SQL($_POST["name"]);
	if($id > 0) {
		$query = $page->DB_QueryPrepare("UPDATE `" . $page->ddb->DBname . "` . `borrowers` SET `name` = ? WHERE `borrowers` . `id` = ? LIMIT 1;");
		$query->bind_param("si", $name, $id);
	} else {
		$query = $page->DB_QueryPrepare("INSERT INTO `" . $page->ddb->DBname . "` . `borrowers` (`id`, `name`) VALUES(NULL, ?)");
		$query->bind_param("s", $name);
	}
	$page->DB_ExecuteManage($query);
	$page->HeaderLocation("index.php");
}

$body = "";
$page_title = "Add a new borrower";

$args = new stdClass();
$args->page = "index";
$args->rootpage = "..";
$body .= $page->GoHome($args);

if(isset($_GET["id"])) {
	$id = $_GET["id"];
	$find = $page->DB_IdManage("SELECT * FROM `borrowers` WHERE `id` = ?", $id);
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

$body .= $page->SetTitle($page_title);
$page->HotBooty();

$body .= "<div class=\"whole\">\n";

$body .= $page->FormTag();
$args = new stdClass();

if($id > 0) {
	$args->type = "hidden";
	$args->name = "id";
	$args->value = $id;
	$args->css = "borrower_id";
	$body .= $page->FormField($args);
}

$args->type = "text";
$args->title = "Name";
$args->name = "name";
$args->value = $name;
$args->css = "borrower_name";
$args->autofocus = true;
$body .= $page->FormField($args);
$args = new stdClass();
$args->css = "borrower_button";
$args->CloseTag = true;
$body .= $page->SubButt($id > 0, $name, $args);
$body .= "</div>\n";

$page->show($body);
unset($page);
?>
