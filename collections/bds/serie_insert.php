<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->bobbyTable->init();

require_once("{$funcpath}/form_fields.php");
global $theHiddenInput;
global $theTextInput;
global $theSelectInput;
global $theNumberInput;


// if get_id is given, edit serie id
// else insert new serie


/*** default empty values ***/
$id = 0;
$name = "";
$editor = "";
$type = "BD";
$nAlbums = "";


if(isset($_POST["erase"])) {
    // Erase serie
    $id = $_POST["id"];
    $check = $page->bobbyTable->idManage("SELECT COUNT(*) AS `count` FROM `bds` WHERE `serie_id` = ?", $id);
    $check->bind_result($count);
    $check->fetch();
    $check->close();
    if($count > 0) {
        $page->logger->error("Cannot delete serie because still has $count entr" . ($count > 1 ? "ies" : "y"));
        $_GET["id"] = $id;
    } else {
        $page->bobbyTable->idManage("DELETE FROM `{$page->bobbyTable->dbName}` . `bd_series` WHERE `bd_series` . `id` = ? LIMIT 1;", $id);
        $page->htmlHelper->headerLocation();
    }
} elseif(isset($_POST["submit"])) {
    if(isset($_POST["id"]) && $_POST["id"] > 0) {
        $id = $_POST["id"];
    }
    $name = $page->dbText->input2sql($_POST["name"]);
    $editor = $page->dbText->input2sql($_POST["editor"]);
    $type = $_POST["type"];
    $Nalbums = $page->dbText->input2sql($_POST["Nalbums"]);
    $query = "";
    if($id > 0) {
        $query = $page->bobbyTable->queryPrepare("UPDATE `{$page->bobbyTable->dbName}` . `bd_series` SET `name` = ?, `editor` = ?, `type` = ?, `Nalbums` = ? WHERE `bd_series` . `id` = ? LIMIT 1;");
        $query->bind_param("sssii", $name, $editor, $type, $Nalbums, $id);
    } else {
        $query = $page->bobbyTable->queryPrepare("INSERT INTO `{$page->bobbyTable->dbName}` . `bd_series` (`id`, `name`, `editor`, `type`, `Nalbums`) VALUES(NULL, ?, ?, ?, ?);");
        $query->bind_param("sssi", $name, $editor, $type, $Nalbums);
    }
    $page->bobbyTable->executeManage($query);
    //
    $redirect = "index.php";
    if($id > 0) {//// first because comes from form and was here at beginning
        $redirect = "serie_display.php?id=$id";
    }
    $page->htmlHelper->headerLocation($redirect);
}

if(isset($_GET["new"])) {
    $name = $_GET["new"];
}

$page->cssHelper->dirUpWing();
$page->htmlHelper->jsForm();

$body = $page->bodyBuilder->goHome("..");

$page_title = "Insert a new serie";

if(isset($_GET["id"])) {
    $id = $_GET["id"];
    if($id == 1) {
        $page->htmlHelper->headerLocation("serie_display.php?id=1");
    }
    $serie = $page->bobbyTable->idManage("SELECT * FROM `bd_series` WHERE `id` = ?", $id);
    $serie->store_result();
    if($serie->num_rows > 0) {
        $serie->bind_result($id, $name, $editor, $type, $nAlbums);
        $serie->fetch();
        if($nAlbums == 0) {
            $nAlbums = "";
        }

        $page_title = "Edit BD serie \"$name\"";
    }
    $serie->close();
}

$body .= $page->htmlHelper->setTitle($page_title);
$page->htmlHelper->hotBooty();

$body .= "<div class=\"bd_serie_insert_main\">\n";
$body .= $page->formHelper->tag();

    // Serie id (if known)
    if($id > 0) {
        $body .= $theHiddenInput->get("id", $id);
    }
//
    // Serie name
    $nameAttr = new FieldAttributes(true, true);
    $nameAttr->size = 50;
    $body .= $theTextInput->get("name", $name, "Name", NULL, $nameAttr);
//
    // Serie editor
    $editorAttr = new FieldAttributes();
    $editorAttr->size = 50;
    $body .= $theTextInput->get("editor", $editor, "Editeur", NULL, $editorAttr);
//
    // type
    $types = $page->utilsHelper->arraySequential2Associative(array("BD", "manga", "comics", "other"));
    $body .= $theSelectInput->get("type", $types, $type, "Type");

$body .= $theNumberInput->get("Nalbums", $nAlbums, "Number of albums");


// Buttons
$body .= $page->formHelper->subButt($id > 0, "'$name'", null, true, null, "bd_serie_valbut");

$body .= "</div><!-- bd_serie_insert_main -->\n";

echo $body;
?>
