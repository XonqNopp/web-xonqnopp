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
//$page->initHTML();
$body = "";
$page->CSS_ppJump(2);
$page->CSS_ppWing(2);
$page->js_Form();

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
	$sql = $page->DB_IdManage("SELECT COUNT(*) AS tot FROM `comco` WHERE `company` = ?", $id);
	$sql->bind_result($tot);
	$sql->fetch();
	$sql->close();
	if($tot == 0) {
		$query = "DELETE FROM `" . $page->ddb->DBname . "`.`companies` WHERE `companies`.`id` = ? LIMIT 1;";
		$sql = $page->DB_IdManage($query, $id);
		$page->HeaderLocation();
	} else {
		$page->NewError("Cannot erase company with communication entries; delete them before");
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
	$name = $page->field2SQL($_POST["name"]);
	$location = $page->field2SQL($_POST["location"]);
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
	$contact = $page->field2SQL($_POST["contact"]);
	$HR = $page->field2SQL($_POST["HR"]);
	$people = $_POST["people"];
	$peopleCH = $_POST["peopleCH"];
	$peopleRD = $_POST["peopleRD"];
	$competitors = $page->field2SQL($_POST["competitors"]);
	$website = $page->field2SQL($_POST["website"]);
	$ranking = $_POST["ranking"];
	$comment = $page->txtarea2SQL($_POST["comment"]);
	if(isset($_POST["id"])) {
		//// update
		$id = $_POST["id"];
		$query = "UPDATE `" . $page->ddb->DBname . "`.`companies` SET `name` = ?, `location` = ?, `car_time` = ?, `train_time` = ?, `fields` = ?, `physicist` = ?, `contact` = ?, `HRname` = ?, `people` = ?, `peopleCH` = ?, `peopleRD` = ?, `competitors` = ?, `website` = ?, `ranking` = ?, `comment` = ?";
		$query .= " WHERE `companies`.`id` = ? LIMIT 1;";
		$sql = $page->DB_QueryPrepare($query);
		$sql->bind_param("ssiissssiiissisi", $name, $location, $car_time, $train_time, $fields, $physicist, $contact, $HR, $people, $peopleCH, $peopleRD, $competitors, $website, $ranking, $comment, $id);
		$page->DB_ExecuteManage($sql);
	} else {
		//// insert
		$query = "INSERT INTO `" . $page->ddb->DBname . "`.`companies` (`name`, `location`, `car_time`, `train_time`, `fields`, `physicist`, `contact`, `HRname`, `people`, `peopleCH`, `peopleRD`, `competitors`, `website`, `ranking`, `comment`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$sql = $page->DB_QueryPrepare($query);
		$sql->bind_param("ssiissssiiissis", $name, $location, $car_time, $train_time, $fields, $physicist, $contact, $HRname, $people, $peopleCH, $peopleRD, $competitors, $website, $ranking, $comment);
		$page->DB_ExecuteManage($sql);
		$id = $sql->insert_id;
	}
	$page->HeaderLocation("display.php?id=$id");
	$page_title = "Edit $name";
	$fields = explode(",", $fields);
	$physicist = explode(",", $physicist);
} elseif(isset($_GET["id"])) {
	$id = $_GET["id"];
	$SQL = $page->DB_IdManage("SELECT * FROM `companies` WHERE `id` = ?", $id);
	$SQL->bind_result($id, $name, $location, $car_time, $train_time, $fields, $physicist, $contact, $HR, $people, $peopleCH, $peopleRD, $competitors, $website, $ranking, $comment);
	$SQL->fetch();
	$SQL->close();
	$page_title = "Edit $name";
	$name = $page->SQL2field($name);
	$location = $page->SQL2field($location);

	if($car_time === NULL) {
		$car_time = "";
	}

	if($train_time === NULL) {
		$train_time = "";
	}

	$car_time = (int)$car_time;
	$train_time = (int)$train_time;

	$fields = fields(explode(",", $fields));
	$physicist = physicist(explode(",", $physicist));
	$contact = $page->SQL2field($contact);
	$HR = $page->SQL2field($HR);
	/*** temporary because new fields ***/
	if($peopleCH === NULL) {
		$peopleCH = "";
	}
	if($peopleRD === NULL) {
		$peopleRD = "";
	}
	/******/
	$competitors = $page->SQL2field($competitors);
	$website = $page->SQL2field($website);
	$comment = $page->SQL2txtarea($comment);
}


$gohome = new stdClass();
$body .= $page->GoHome($gohome);
$body .= $page->SetTitle($page_title);// before HotBooty
$page->HotBooty();

$body .= "<div>\n";
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
	//// name
	$args->type = "text";
	$args->title = "Name";
	$args->name = "name";
	$args->value = $name;
	$args->autofocus = true;
	$args->required = true;
	$body .= $page->FormField($args);
//
	//// location
	$args->type = "text";
	$args->title = "Location";
	$args->name = "location";
	$args->value = $location;
	$args->autofocus = false;
	$args->required = false;
	$body .= $page->FormField($args);
//
	//// car time
	$args->type = "number";
	$args->min = 0;
	$args->title = "Travel time by car";
	$args->name = "car_time";
	$args->value = $car_time;
	$args->autofocus = false;
	$args->required = false;
	//$body .= $page->FormField($args);
//
	//// train time
	$args->type = "number";
	$args->min = 0;
	$args->title = "Travel time by train";
	$args->name = "train_time";
	$args->value = $train_time;
	$args->autofocus = false;
	$args->required = false;
	//$body .= $page->FormField($args);
//
$body .= "<div class=\"csstab64_table\">\n";
$body .= "<div class=\"csstab64_row\">\n";
	//// fields
	$args->type = "checkbox";
	$args->title = "Fields of work";
	$args->name = "fields";
	$args->value = $fields;
	$args->list = fields();
	$args->vlist = true;
	$args->css = "csstab64_cell fields";
	$body .= $page->FormField($args);
//
	//// physicist
	$args->type = "checkbox";
	$args->title = "What would a physicist do by them";
	$args->name = "physicist";
	$args->value = $physicist;
	$args->list = physicist();
	$args->css = "csstab64_cell physicist";
	$body .= $page->FormField($args);
$body .= "</div>\n";
$body .= "</div>\n";
//
	//// contact
	$args->type = "text";
	$args->title = "Insider";
	$args->name = "contact";
	$args->value = $contact;
	$args->css = "contact";
	$body .= $page->FormField($args);
//
	//// HR person
	$args->type = "text";
	$args->title = "HR person";
	$args->name = "HR";
	$args->value = $HR;
	$args->css = "HR";
	$body .= $page->FormField($args);
//
	//// #people
	$args->type = "number";
	$args->min = 0;
	$args->title = "Global number of employees";
	$args->name = "people";
	$args->value = $people;
	$args->css = "people";
	$body .= $page->FormField($args);
//
	//// #people
	$args->type = "number";
	$args->min = 0;
	$args->title = "Number of employees in Switzerland";
	$args->name = "peopleCH";
	$args->value = $peopleCH;
	$args->css = "peopleCH";
	$body .= $page->FormField($args);
//
	//// #people
	$args->type = "number";
	$args->min = 0;
	$args->title = "Number of employees in Switzerland R&amp;D";
	$args->name = "peopleRD";
	$args->value = $peopleRD;
	$args->css = "peopleRD";
	$body .= $page->FormField($args);
//
	//// competitors
	$args->type = "text";
	$args->title = "Competitors";
	$args->name = "competitors";
	$args->value = $competitors;
	$args->css = "competitors";
	$body .= $page->FormField($args);
//
	//// website
	$args->type = "text";
	$args->title = "URL";
	$args->name = "website";
	$args->value = $website;
	$args->css = "website";
	$body .= $page->FormField($args);
//
	//// ranking
	$args->type = "number";
	$args->max = 9;
	$args->title = "Personal ranking";
	$args->name = "ranking";
	$args->value = $ranking;
	$args->css = "ranking";
	$body .= $page->FormField($args);
//
	//// comment
	$args->type = "textarea";
	$args->title = "Comment";
	$args->name = "comment";
	$args->value = $comment;
	$args->css = "comment";
	$body .= $page->FormField($args);
//
$butt = new stdClass();
$butt->CloseTag = true;
$body .= $page->SubButt($id > 0, $name, $butt);

$body .= "</div>\n";


$page->show($body);
unset($page);
?>
