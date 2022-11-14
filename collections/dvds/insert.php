<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->loginHelper->notAllowed();


require_once("$funcpath/form_fields.php");
global $theHiddenInput;
global $theTextInput;
global $theCheckboxInput;
global $theSelectInput;
global $theTextarea;
global $theNumberInput;


$page->bobbyTable->init();


    //// init values
    $id = 0;
    $title = "";
    $director = "";
    $actors = "";
    $languages = array("");
    $subtitles = array("");
    $duration = "";
    $serie = "";
    $number = "";
    $summary = "";
    $burnt = false;
    $format = "dvd";
    $category = "";
//
$is_it = array("yes" => 1, "no" => 0);
$the_languages = array("fr" => "French", "en" => "English", "it" => "Italian", "de" => "German", "zz" => "other");
$cats = array("movie" => "Movie", "animation" => "Animation", "tvserie" => "TV Serie", "doc" => "Documentary", "humor" => "Humorist", "music" => "Musical", "memory" => "Memory");
$formats = array("dvd" => "DVD","blu" => "Blu-ray","avi" => "AVI");

if(isset($_POST["title"])) {
    if(isset($_POST["erase"])) {
        $id = $_POST["id"];
        $page->bobbyTable->idManage("DELETE FROM `{$page->bobbyTable->dbName}` . `dvds` WHERE `dvds` . `id` = ? LIMIT 1;", $id);
        $page->htmlHelper->headerLocation();
    } else {
        // DB treatement
        if(isset($_POST["id"])) {
            $id = $_POST["id"];
        }
        $title = $page->dbText->input2sql($_POST["title"]);
        $director = $page->dbText->input2sql($_POST["director"]);
        $actors = $page->dbText->input2sql($_POST["actors"]);
        if($_POST["languages"] != "") {
            $languages = implode(",",$_POST["languages"]);
        }
        if($_POST["subtitles"] != "") {
            $subtitles = implode(",",$_POST["subtitles"]);
        }
        $duration = $_POST["duration"];
        $serie = $page->dbText->input2sql($_POST["serie"]);
        $number = $page->dbText->input2sql($_POST["number"]);
        $summary = $page->dbText->inputTextareaParagraph2sql($_POST["summary"]);
        $burnt = $is_it[$_POST["burnt"]];
        $format = $_POST["format"];
        $category = $_POST["category"];
        $query = "";
        if($id > 0) {
            $query = $page->bobbyTable->queryPrepare("UPDATE `{$page->bobbyTable->dbName}` . `dvds` SET `title` = ?, `director` = ?, `actors` = ?, `languages` = ?, `subtitles` = ?, `duration` = ?, `serie` = ?, `number` = ?, `summary` = ?, `burnt` = ?, `format` = ?, `category` = ? WHERE `dvds` . `id` = ? LIMIT 1;");
            $query->bind_param("sssssisisissi", $title, $director, $actors, $languages, $subtitles, $duration, $serie, $number, $summary, $burnt, $format, $category, $id);
        } else {
            $query = $page->bobbyTable->queryPrepare("INSERT INTO `{$page->bobbyTable->dbName}` . `dvds` (`id`, `title`, `director`, `actors`, `languages`, `subtitles`, `duration`, `serie`, `number`, `summary`, `burnt`, `format`, `category`) VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
            $query->bind_param("sssssisisiss", $title, $director, $actors, $languages, $subtitles, $duration, $serie, $number, $summary, $burnt, $format, $category);
        }
        $page->bobbyTable->executeManage($query);
        $id_back = 0;
        if($id > 0) {
            $id_back = $id;
        } else {
            $id_back = $query->insert_id;
        }
        $page->htmlHelper->headerLocation("index.php#dvd$id_back");// Check if serie
    }
    exit;
}

$page->cssHelper->dirUpWing();
$page->htmlHelper->jsForm();


// DISPLAY
$morebody = "";
$page_title = "Insert a new DVD";
$category = "movie";
$format = "dvd";
$languages = array();
$subtitles = array();

if(isset($_GET["id"]) || isset($_POST["id"])) {
    if(isset($_GET["id"])) {
        $id = $_GET["id"];
    } else {
        $id = $_POST["id"];
    }
    // Fetch infos from DB
    $query = $page->bobbyTable->idManage("SELECT * FROM `{$page->bobbyTable->dbName}` . `dvds` WHERE `dvds` . `id` = ? LIMIT 1;", $id);
    $query->store_result();
    if($query->num_rows == 0) {
        $query->close();
        exit("Error bad id");
    }
    $query->bind_result($id, $title, $director, $actors, $languages, $subtitles, $duration, $serie, $number, $category, $summary, $burnt, $format, $borrowed);
    $query->fetch();
    $query->close();
    $title    = $page->dbText->sql2html($title);
    $director = $page->dbText->sql2html($director);
    $actors   = $page->dbText->sql2html($actors);
    $languages = explode(",", $languages);
    $subtitles = explode(",", $subtitles);
    if($duration == 0) {
        $duration = "";
    }
    $serie   = $page->dbText->sql2html($serie);
    $number  = $page->dbText->sql2html($number);
    if($number == 0) {
        $number = "";
    }
    $summary = $page->dbText->sql2htmlTextareaParagraph($summary);

    // Some infos to display
    $GHargs->page = "display";
    $GHargs->id = $id;
    $page_title = "Update infos for DVD " . stripslashes($title);
    $morebody .= $theHiddenInput->get("id", $id);
}

$body = $page->bodyBuilder->goHome("..");
$body .= $page->htmlHelper->setTitle($page_title);
$page->htmlHelper->hotBooty();

$body .= "<div class=\"main\">\n";

$body .= $page->formHelper->tag();
$body .= $morebody;

$args = new stdClass();

// Title
$titleAttr = new FieldAttributes(true, true);
$titleAttr->size = 60;
$body .= $theTextInput->get("title", $title, "Title", NULL, $titleAttr);

$attrSize = new FieldAttributes();

$attrSize->size = 50;
$body .= $theTextInput->get("director", $director, "Director", $attrSize);
$body .= $theTextInput->get("actors", $actors, "Actors", $attrSize);
$body .= $theCheckboxInput->get("languages", $the_languages, $languages, "Languages");
$body .= $theCheckboxInput->get("subtitles", $the_languages, $subtitles, "Subtitles");

$embedder = new FieldEmbedder("Duration", "minutes");
$body .= $theNumberInput->get("duration", $duration, NULL, NULL, $embedder);

$body .= $theTextInput->get("serie", $serie, "Serie", $attrSize);
$body .= $theNumberInput->get("number", $number, "Number");


$burnes = array("no" => "No", "yes" => "Yes");
$burne = "no";
if($burnt) {$burne = "yes";}
$body .= $theSelectInput->get("burnt", $burnes, $burne, "Burnt");

$body .= $theSelectInput->get("format", $formats, $format, "Format");
$body .= $theSelectInput->get("category", $cats, $category, "Category");
$body .= $theTextarea->get("summary", $summary, 15, 70, "Summary");

// Buttons
$body .= $page->formHelper->subButt($id > 0, "'$title'");

$body .= "</div>\n";

echo $body;
?>
