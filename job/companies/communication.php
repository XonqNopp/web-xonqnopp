<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require_once("dicts.php");
$page = new PhPage($rootPath);
$page->loginHelper->notAllowed();
$page->bobbyTable->init();

require_once("$funcpath/form_fields.php");
global $theHiddenInput;
global $theTextInput;
global $theSelectInput;
global $theDatetimeInput;
global $theTextarea;


//$page->logger->levelUp(6);
$body = "";
$page->cssHelper->dirUpWing(2);
$page->htmlHelper->jsForm();

$id = 0;
$company = 0;
$name = "";
$timestamp = $page->timeHelper->getNow()->timestamp;
$who = "";
$media = "";
$way = "";
$kind = "";
$content = "";
$page_title = "";




if(isset($_POST["erase"])) {
    $id = $_POST["id"];
    $company = $_POST["company"];
    $query = "DELETE FROM `{$page->bobbyTable->dbName}`.`comco` WHERE `comco`.`id` = ? LIMIT 1;";
    $sql = $page->bobbyTable->queryPrepare($query);
    $sql->bind_param("i", $id);
    $page->bobbyTable->executeManage($sql);
    $page->htmlHelper->headerLocation("display.php?id=$company");

} elseif(isset($_POST["submit"])) {
    $company = $_POST["company"];
    $timestamp = $_POST["timestamp"];
    $who = $page->dbText->input2sql($_POST["who"]);
    $media = $_POST["media"];
    $way = $_POST["way"];
    $kind = $_POST["kind"];
    $content = $page->dbText->inputTextarea2sql($_POST["content"]);

    if(isset($_POST["id"])) {
        // update
        $id = $_POST["id"];
        $query = "UPDATE `{$page->bobbyTable->dbName}`.`comco` SET `timestamp` = ?, `who` = ?, `media` = ?, `way` = ?, `kind` = ?, `content` = ? WHERE `comco`.`id` = ? LIMIT 1;";
        $sql = $page->bobbyTable->queryPrepare($query);
        $sql->bind_param("ssssssi", $timestamp, $who, $media, $way, $kind, $content, $id);
        $page->bobbyTable->executeManage($sql);

    } else {
        // insert
        $query = "INSERT INTO `{$page->bobbyTable->dbName}`.`comco` (`company`, `timestamp`, `who`, `media`, `way`, `kind`, `content`) VALUES(?, ?, ?, ?, ?, ?, ?);";
        $sql = $page->bobbyTable->queryPrepare($query);
        $sql->bind_param("issssss", $company, $timestamp, $who, $media, $way, $kind, $content);
        $page->bobbyTable->executeManage($sql);
        $id = $sql->insert_id;
    }

    $page->htmlHelper->headerLocation("display.php?id=$company");
    $sql = $page->bobbyTable->idManage("SELECT * FROM `companies` WHERE `id` = ?", $company);
    $sql->bind_result($company, $name, $colo, $coca, $cotr, $cofi, $coph, $coco, $cohr, $copep, $copch, $coprd, $coom, $cow, $cora, $coo);
    $sql->fetch();
    $sql->close();
    $page_title = "Edit #$id for $name";

} elseif(isset($_GET["id"])) {
    $id = $_GET["id"];
    $SQL = $page->bobbyTable->idManage("SELECT * FROM `comco` WHERE `id` = ?", $id);
    $SQL->bind_result($id, $company, $timestamp, $who, $media, $way, $kind, $content);
    $SQL->fetch();
    $SQL->close();
    $who = $page->dbText->sql2html($who);
    $content = $page->dbText->sql2htmlTextarea($content);
    $sql = $page->bobbyTable->idManage("SELECT * FROM `companies` WHERE `id` = ?", $company);
    $sql->bind_result($company, $name, $colo, $coca, $cotr, $cofi, $coph, $coco, $cohr, $copep, $copch, $coprd, $coom, $cow, $cora, $coo);
    $sql->fetch();
    $sql->close();
    $page_title = "Edit #$id for $name";

} elseif(isset($_GET["new"])) {
    $company = $_GET["new"];
    $sql = $page->bobbyTable->idManage("SELECT * FROM `companies` WHERE `id` = ?", $company);
    $sql->bind_result($company, $name, $colo, $coca, $cotr, $cofi, $coph, $coco, $cohr, $copep, $copch, $coprd, $coom, $cow, $cora, $coo);
    $sql->fetch();
    $sql->close();
    $page_title = "Insert new communication for $name";
}

if($company == 0) {
    $page->htmlHelper->headerLocation();
}


$body .= $page->bodyBuilder->goHome();
$body .= $page->htmlHelper->setTitle($page_title);// before HotBooty
$page->htmlHelper->hotBooty();

$body .= $page->formHelper->tag();
//
if($id > 0) {
    $body .= $theHiddenInput->get("id", $id);
}

$body .= $theHiddenInput->get("company", $company);

$attr = new FieldAttributes(true, true);
$body .= $theDatetimeInput->get("timestamp", $timestamp, "Timestamp", $attr);

$body .= $theTextInput->get("who", $who, "Who");

    // media
    $mediaList = $page->utilsHelper->arraySequential2Associative(array(
        "mail",
        "website",
        "linkedin",
        "phone",
        "meeting",
    ));
    $body .= $theSelectInput->get("media", $mediaList, $media, "Media");
//
    // way
    $ways = array("FromMe" => "from me", "ToMe" => "to me");
    $body .= $theSelectInput->get("way", $ways, $way, "Way");
//
    // kind
    $kinds = $page->utilsHelper->arraySequential2Associative(array(
        "application",
        "offer",
        "ideas",
        "conversation",
        "misc",
    ));
    $body .= $theSelectInput->get("kind", $kinds, $kind, "Kind");

$body .= $theTextarea->get("content", $content, NULL, NULL, "Content");

$body .= $page->formHelper->subButt($id > 0, "la communication #$id avec $name");


echo $body;
?>
