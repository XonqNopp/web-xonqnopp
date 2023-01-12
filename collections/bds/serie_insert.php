<?php
require("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->dbHelper->init();

require("{$funcpath}_local/fetch_from_isbn.php");

require("{$funcpath}/form_fields.php");
use FieldAttributes;
global $theHiddenInput;
global $theTextInput;
global $theSelectInput;
global $theNumberInput;


// if get_id is given, edit serie id
// else insert new serie

// if update
// insert/update info, move thumb file to (bds/)thumbs/[serie name]_[serie id].[ext]

/*** default empty values ***/
$id = 0;
$isbn = "";
$thumb = "";
$name = "";
$type = "BD";
$N = "";


if(isset($_POST["erase"])) {
	// Erase serie
	$id = $_POST["id"];
	$check = $page->dbHelper->idManage("SELECT COUNT(*) AS `count` FROM `bds` WHERE `serie_id` = ?", $id);
	$check->bind_result($count);
	$check->fetch();
	$check->close();
	if($count > 0) {
		$page->logger->error("Cannot delete serie because still has $count entr" . ($count > 1 ? "ies" : "y"));
		$_GET["id"] = $id;
	} else {
		$page->dbHelper->idManage("DELETE FROM `{$page->dbHelper->dbName}` . `bd_series` WHERE `bd_series` . `id` = ? LIMIT 1;", $id);
		$page->htmlHelper->headerLocation();
	}
} elseif(isset($_POST["submit"])) {
	if(isset($_POST["id"]) && $_POST["id"] > 0) {
		$id = $_POST["id"];
	}
	$isbn = $_POST["isbn"];
	$name = $page->dbText->field2SQL($_POST["name"]);
	$thumb = "";
	$type = $_POST["type"];
	$Nalbums = $page->dbText->field2SQL($_POST["Nalbums"]);
	$query = "";
	if($id > 0) {
		$query = $page->dbHelper->queryPrepare("UPDATE `{$page->dbHelper->dbName}` . `bd_series` SET `name` = ?, `thumb` = ?, `type` = ?, `Nalbums` = ? WHERE `bd_series` . `id` = ? LIMIT 1;");
		$query->bind_param("sssii", $name, $thumb, $type, $Nalbums, $id);
	} else {
		$query = $page->dbHelper->queryPrepare("INSERT INTO `{$page->dbHelper->dbName}` . `bd_series` (`id`, `name`, `thumb`, `type`, `Nalbums`) VALUES(NULL, ?, ?, ?, ?);");
		$query->bind_param("sssi", $name, $thumb, $type, $Nalbums);
	}
	$page->dbHelper->executeManage($query);
	//
	$redirect = "index.php";
	if($id > 0) {//// first because comes from form and was here at beginning
		$redirect = "serie_display.php?id=$id";
	} elseif($isbn != "") {
		$redirect = "insert.php?isbn=$isbn";
	}
	$page->htmlHelper->headerLocation($redirect);
}

if(isset($_GET["new"]) && isset($_GET["isbn"])) {
	$name = $_GET["new"];
	$isbn = $_GET["isbn"];
}

$page->cssHelper->dirUpWing();
$page->htmlHelper->jsForm();

$body = $page->bodyHelper->goHome("..");

$body .= "<div class=\"index_whole\">\n";

$page_title = "Insert a new serie";

if(isset($_GET["id"])) {
	$id = $_GET["id"];
	if($id == 1) {
		$page->htmlHelper->headerLocation("serie_display.php?id=1");
	}
	$serie = $page->dbHelper->idManage("SELECT * FROM `bd_series` WHERE `id` = ?", $id);
	$serie->store_result();
	if($serie->num_rows > 0) {
		$serie->bind_result($id, $name, $thumb, $type, $N);
		$serie->fetch();
		if($N == 0) {
			$N = "";
		}

		$page_title = "Edit BD serie \"$name\"";
	}
	$serie->close();
}

$body .= $page->htmlHelper->setTitle($page_title);
$page->htmlHelper->hotBooty();

$body .= "<div class=\"bd_serie_insert_main\">\n";
$body .= $page->formHelper->tag();

$body .= $theHiddenInput->get("isbn", $isbn);

	// Displaying thumb
	$tothumb = "";
	if($thumb != "") {
		$tothumb = "<img class=\"bd_serie_thumbup\" alt=\"$name\" title=\"$name\" src=\"thumbs/$thumb\" />\n";
	}
	$body .= "<div class=\"bd_serie_thumbs\">\n";
	$body .= "<div class=\"bd_serie_thumbup\">\n";
	$body .= $tothumb;
	$body .= "</div>\n";
	$body .= "<div class=\"bd_serie_thumbdown\">\n";
	$body .= $tothumb;
	$body .= "</div>\n";
	$body .= "</div>\n";
//
	// Serie id (if known)
	if($id > 0) {
		$body .= $theHiddenInput->get("id", $id);
	}

if($isbn > 0) {
	// ISBN: serie already exists, go to insert
	$body .= "<div><a href=\"insert.php?isbn=$isbn&amp;fetcherlavache\" title=\"serie exists\">serie exists</a></div>\n";
}

	// Serie name
	$nameAttr = new FieldAttributes(true, true);
	$nameAttr->size = 50;
	$body .= $theTextInput->get("name", $name, "Name", NULL, $nameAttr);
//
	// type
	$types = $page->utilsHelper->arraySequential2Asscoiative(array("BD", "manga", "comics", "other"));
	$body .= $theSelectInput->get("type", $types, $type, "Type");

$body .= $theNumberInput->get("Nalbums", $N, "Number of albums");


// Buttons
$body .= $page->formHelper->subButt($id > 0, "'$name'", null, true, null, "bd_serie_valbut");

// Done
$body .= "</div>\n";
$body .= "</div>\n";

/*** Printing ***/
echo $body;
unset($page);
?>
