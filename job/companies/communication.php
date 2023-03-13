<?php
/*** Created: Wed 2015-01-14 20:43:36 CET
 * TODO:
 *
 */
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require("dicts.php");
$page = new PhPage($rootPath);
$page->NotAllowed();
$page->initDB();
//$page->LogLevelUp(6);
$body = "";
$page->CSS_ppJump(2);
$page->CSS_ppWing(2);
$page->js_Form();

$now = $page->GetNow();

$id = 0;
$company = 0;
$name = "";
$timestamp = $now->timestamp;
$who = "";
$media = "";
$way = "";
$kind = "";
$content = "";
$page_title = "";




if(isset($_POST["erase"])) {
	$id = $_POST["id"];
	$company = $_POST["company"];
	$query = "DELETE FROM `" . $page->ddb->DBname . "`.`comco` WHERE `comco`.`id` = ? LIMIT 1;";
	$sql = $page->DB_QueryPrepare($query);
	$sql->bind_param("i", $id);
	$page->DB_ExecuteManage($sql);
	$page->HeaderLocation("display.php?id=$company");
} elseif(isset($_POST["submit"])) {
	$company = $_POST["company"];
	$timestamp = $_POST["timestamp"];
	$who = $page->field2SQL($_POST["who"]);
	$media = $_POST["media"];
	$way = $_POST["way"];
	$kind = $_POST["kind"];
	$content = $page->txtarea2SQL($_POST["content"]);
	if(isset($_POST["id"])) {
		//// update
		$id = $_POST["id"];
		$query = "UPDATE `" . $page->ddb->DBname . "`.`comco` SET `timestamp` = ?, `who` = ?, `media` = ?, `way` = ?, `kind` = ?, `content` = ? WHERE `comco`.`id` = ? LIMIT 1;";
		$sql = $page->DB_QueryPrepare($query);
		$sql->bind_param("ssssssi", $timestamp, $who, $media, $way, $kind, $content, $id);
		$page->DB_ExecuteManage($sql);
	} else {
		//// insert
		$query = "INSERT INTO `" . $page->ddb->DBname . "`.`comco` (`company`, `timestamp`, `who`, `media`, `way`, `kind`, `content`) VALUES(?, ?, ?, ?, ?, ?, ?);";
		$sql = $page->DB_QueryPrepare($query);
		$sql->bind_param("issssss", $company, $timestamp, $who, $media, $way, $kind, $content);
		$page->DB_ExecuteManage($sql);
		$id = $sql->insert_id;
	}
	$page->HeaderLocation("display.php?id=$company");
	$sql = $page->DB_IdManage("SELECT * FROM `companies` WHERE `id` = ?", $company);
	$sql->bind_result($company, $name, $colo, $coca, $cotr, $cofi, $coph, $coco, $cohr, $copep, $copch, $coprd, $coom, $cow, $cora, $coo);
	$sql->fetch();
	$sql->close();
	$page_title = "Edit #$id for $name";
} elseif(isset($_GET["id"])) {
	$id = $_GET["id"];
	$SQL = $page->DB_IdManage("SELECT * FROM `comco` WHERE `id` = ?", $id);
	$SQL->bind_result($id, $company, $timestamp, $who, $media, $way, $kind, $content);
	$SQL->fetch();
	$SQL->close();
	$who = $page->SQL2field($who);
	$content = $page->SQL2txtarea($content);
	$sql = $page->DB_IdManage("SELECT * FROM `companies` WHERE `id` = ?", $company);
	$sql->bind_result($company, $name, $colo, $coca, $cotr, $cofi, $coph, $coco, $cohr, $copep, $copch, $coprd, $coom, $cow, $cora, $coo);
	$sql->fetch();
	$sql->close();
	$page_title = "Edit #$id for $name";
} elseif(isset($_GET["new"])) {
	$company = $_GET["new"];
	$sql = $page->DB_IdManage("SELECT * FROM `companies` WHERE `id` = ?", $company);
	$sql->bind_result($company, $name, $colo, $coca, $cotr, $cofi, $coph, $coco, $cohr, $copep, $copch, $coprd, $coom, $cow, $cora, $coo);
	$sql->fetch();
	$sql->close();
	$page_title = "Insert new communication for $name";
}

if($company == 0) {
	$page->HeaderLocation();
}


$gohome = new stdClass();
$body .= $page->GoHome($gohome);
$body .= $page->SetTitle($page_title);// before HotBooty
$page->HotBooty();

$body .= $page->FormTag();
//
	//// id
	$args = new stdClass();
	$args->type = "hidden";
	$args->name = "id";
	$args->value = $id;
	if($id > 0) {
		$body .= $page->FormField($args);
	}
//
	//// company
	$args->type = "hidden";
	$args->name = "company";
	$args->value = $company;
	$body .= $page->FormField($args);
//
	//// timestamp
	$args->type = "datetime";
	$args->title = "Timestamp";
	$args->name = "timestamp";
	$args->value = $timestamp;
	$args->autofocus = true;
	$args->required = true;
	$body .= $page->FormField($args);
//
	//// who
	$args->type = "text";
	$args->title = "Who";
	$args->name = "who";
	$args->value = $who;
	$args->autofocus = false;
	$args->required = false;
	$body .= $page->FormField($args);
//
	//// media
	$args->type = "select";
	$args->title = "Media";
	$args->name = "media";
	$args->value = $media;
	$args->list = media();
	$body .= $page->FormField($args);
//
	//// way
	$args->type = "select";
	$args->title = "Way";
	$args->name = "way";
	$args->value = $way;
	$args->list = array("FromMe" => "from me", "ToMe" => "to me");
	$body .= $page->FormField($args);
//
	//// kind
	$args->type = "select";
	$args->title = "Kind";
	$args->name = "kind";
	$args->value = $kind;
	$args->list = kind();
	$body .= $page->FormField($args);
//
	//// content
	$args->type = "textarea";
	$args->title = "Content";
	$args->name = "content";
	$args->value = $content;
	$body .= $page->FormField($args);
//
$butt = new stdClass();
$butt->CloseTag = true;
$body .= $page->SubButt($id > 0, "la communication #$id avec $company", $butt);


$page->show($body);
unset($page);
?>
