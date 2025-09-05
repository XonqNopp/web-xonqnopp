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
global $theUrlInput;
global $theCheckboxInput;
global $theTextarea;
global $theNumberInput;


//$page->logger->levelUp(6);
//$page->htmlHelper->init();
$body = "";
$page->cssHelper->dirUpWing(2);
$page->htmlHelper->jsForm();

$id = 0;
$name = "";
$location = "";
$car_time = 0;
$train_time = 0;
$fields = array();
$physicist = array();
$contact = "";
$HR = "";
$people = "";
$peopleCH = "";
$peopleRD = "";
$competitors = "";
$website = "";
$ranking = 1;
$comment = "";
$page_title = "Insert a new company";




if(isset($_POST["erase"])) {
    $id = $_POST["id"];
    //// check that no communication items
    $sql = $page->bobbyTable->idManage("SELECT COUNT(*) AS tot FROM `comco` WHERE `company` = ?", $id);
    $sql->bind_result($tot);
    $sql->fetch();
    $sql->close();
    if($tot == 0) {
        $query = "DELETE FROM `{$page->bobbyTable->dbName}`.`companies` WHERE `companies`.`id` = ? LIMIT 1;";
        $sql = $page->bobbyTable->idManage($query, $id);
        $page->htmlHelper->headerLocation();
    } else {
        $page->logger->error("Cannot erase company with communication entries; delete them before");
        $name = $_POST["name"];
        $location = $_POST["location"];
        $car_time = $_POST["car_time"];
        $train_time = $_POST["train_time"];
        $fields = $_POST["fields"];
        $physicist = $_POST["physicist"];
        $contact = $_POST["contact"];
        $HR = $_POST["HR"];
        $people = $_POST["people"];
        $peopleCH = $_POST["peopleCH"];
        $peopleRD = $_POST["peopleRD"];
        $competitors = $_POST["competitors"];
        $website = $_POST["website"];
        $ranking = $_POST["ranking"];
        $comment = $_POST["comment"];
    }
} elseif(isset($_POST["submit"])) {
    $name = $page->dbText->input2sql($_POST["name"]);
    $location = $page->dbText->input2sql($_POST["location"]);
    //$car_time = $_POST["car_time"];
    //$train_time = $_POST["train_time"];
    $fields = "";
    if(isset($_POST["fields"])) {
        $fields = implode(",", $_POST["fields"]);
    }
    $physicist = "";
    if(isset($_POST["physicist"])) {
        $physicist = implode(",", $_POST["physicist"]);
    }
    $contact = $page->dbText->input2sql($_POST["contact"]);
    $HR = $page->dbText->input2sql($_POST["HR"]);
    $people = $_POST["people"];
    $peopleCH = $_POST["peopleCH"];
    $peopleRD = $_POST["peopleRD"];
    $competitors = $page->dbText->input2sql($_POST["competitors"]);
    $website = $page->dbText->input2sql($_POST["website"]);
    $ranking = $_POST["ranking"];
    $comment = $page->dbText->inputTextarea2sql($_POST["comment"]);

    if(isset($_POST["id"])) {
        // update
        $id = $_POST["id"];
        $query = "UPDATE `{$page->bobbyTable->dbName}`.`companies` SET `name` = ?, `location` = ?, `car_time` = ?, `train_time` = ?, `fields` = ?, `physicist` = ?, `contact` = ?, `HRname` = ?, `people` = ?, `peopleCH` = ?, `peopleRD` = ?, `competitors` = ?, `website` = ?, `ranking` = ?, `comment` = ?";
        $query .= " WHERE `companies`.`id` = ? LIMIT 1;";
        $sql = $page->bobbyTable->queryPrepare($query);
        $sql->bind_param("ssiissssiiissisi", $name, $location, $car_time, $train_time, $fields, $physicist, $contact, $HR, $people, $peopleCH, $peopleRD, $competitors, $website, $ranking, $comment, $id);
        $page->bobbyTable->executeManage($sql);

    } else {
        // insert
        $query = "INSERT INTO `{$page->bobbyTable->dbName}`.`companies` (`name`, `location`, `car_time`, `train_time`, `fields`, `physicist`, `contact`, `HRname`, `people`, `peopleCH`, `peopleRD`, `competitors`, `website`, `ranking`, `comment`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $sql = $page->bobbyTable->queryPrepare($query);
        $sql->bind_param("ssiissssiiissis", $name, $location, $car_time, $train_time, $fields, $physicist, $contact, $HRname, $people, $peopleCH, $peopleRD, $competitors, $website, $ranking, $comment);
        $page->bobbyTable->executeManage($sql);
        $id = $sql->insert_id;
    }
    $page->htmlHelper->headerLocation("display.php?id=$id");
    $page_title = "Edit $name";
    $fields = explode(",", $fields);
    $physicist = explode(",", $physicist);
} elseif(isset($_GET["id"])) {
    $id = $_GET["id"];
    $SQL = $page->bobbyTable->idManage("SELECT * FROM `companies` WHERE `id` = ?", $id);
    $SQL->bind_result($id, $name, $location, $car_time, $train_time, $fields, $physicist, $contact, $HR, $people, $peopleCH, $peopleRD, $competitors, $website, $ranking, $comment);
    $SQL->fetch();
    $SQL->close();
    $page_title = "Edit $name";
    $name = $page->dbText->sql2html($name);
    $location = $page->dbText->sql2html($location);

    if($car_time === NULL) {
        $car_time = "";
    }

    if($train_time === NULL) {
        $train_time = "";
    }

    $car_time = (int)$car_time;
    $train_time = (int)$train_time;

    $fields = explode(",", $fields);
    $physicist = explode(",", $physicist);
    $contact = $page->dbText->sql2html($contact);
    $HR = $page->dbText->sql2html($HR);
    /*** temporary because new fields ***/
    if($peopleCH === NULL) {
        $peopleCH = "";
    }
    if($peopleRD === NULL) {
        $peopleRD = "";
    }
    /******/
    $competitors = $page->dbText->sql2html($competitors);
    $website = $page->dbText->sql2html($website);
    $comment = $page->dbText->sql2htmlTextarea($comment);
}


$body .= $page->bodyBuilder->goHome();

$body .= $page->htmlHelper->setTitle($page_title);// before HotBooty
$page->htmlHelper->hotBooty();

$body .= "<div>\n";
$body .= $page->formHelper->tag();

if($id > 0) {
    $body .= $theHiddenInput->get("id", $id);
}

$attr = new FieldAttributes(true, true);
$body .= $theTextInput->get("name", $name, "Name", NULL, $attr);

$body .= $theTextInput->get("location", $location, "Location");

$attrMin0 = new FieldAttributes();
$attrMin0->min = 0;

//$body .= $theNumberInput->get("car_time", $car_time, "Travel time by car", $attrMin0);
//$body .= $theNumberInput->get("train_time", $train_time, "Travel time by train", $attrMin0);

$body .= $page->waitress->tableOpen(array(), false);
$body .= $page->waitress->rowOpen();

$fieldsEmbedder = new FieldEmbedder("Fields of work");
$fieldsEmbedder->css = "csstab64_cell fields";
$fieldsEmbedder->hasLabel = false;
global $kFields;
$body .= $theCheckboxInput->get(
    "fields",
    $kFields,
    $fields,
    "Fields of work",
    true,
    true,
    NULL,
    $fieldsEmbedder
);

$physicistEmbedder = new FieldEmbedder("What would a physicist do by them");
$physicistEmbedder->css = "csstab64_cell physicist";
$physicistEmbedder->hasLabel = false;
global $kPhysicist;
$body .= $theCheckboxInput->get(
    "physicist",
    $kPhysicist,
    $physicist,
    "What would a physicist do by them",
    true,
    true,
    NULL,
    $physicistEmbedder
);

$body .= $page->waitress->rowClose();
$body .= $page->waitress->tableClose();

$body .= $theTextInput->get("contact", $contact, "Insider");
$body .= $theTextInput->get("HR", $HR, "HR person");

$attrMin0 = new FieldAttributes();
$attrMin0->min = 0;

$body .= $theNumberInput->get("people", $people, "Global number of employees", $attrMin0);
$body .= $theNumberInput->get("peopleCH", $peopleCH, "Number of employees in Switzerland", $attrMin0);
$body .= $theNumberInput->get("peopleRD", $peopleRD, "Number of employees in Switzerland R&amp;D", $attrMin0);
$body .= $theTextInput->get("competitors", $competitors, "Competitors");
$body .= $theUrlInput->get("website", $website, "URL");

$attrMin0->max = 9;  // not used later so we do not care about wrong name
$body .= $theNumberInput->get("ranking", $ranking, "Personal ranking", $attrMin0);

$body .= $theTextarea->get("comment", $comment, NULL, NULL, "Comment");


$body .= $page->formHelper->subButt($id > 0, $name);

$body .= "</div>\n";


echo $body;
?>
