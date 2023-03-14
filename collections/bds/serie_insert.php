<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->initDB();

require("${funcpath}_local/fetch_from_isbn.php");

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
	$check = $page->DB_IdManage("SELECT COUNT(*) AS `count` FROM `bds` WHERE `serie_id` = ?", $id);
	$check->bind_result($count);
	$check->fetch();
	$check->close();
	if($count > 0) {
		$page->NewError("Cannot delete serie because still has $count entr" . ($count > 1 ? "ies" : "y"));
		$_GET["id"] = $id;
	} else {
		$page->DB_IdManage("DELETE FROM `" . $page->ddb->DBname . "` . `bd_series` WHERE `bd_series` . `id` = ? LIMIT 1;", $id);
		$page->HeaderLocation();
	}
} elseif(isset($_POST["submit"])) {
	if(isset($_POST["id"]) && $_POST["id"] > 0) {
		$id = $_POST["id"];
	}
	$isbn = $_POST["isbn"];
	$name = $page->Field2SQL($_POST["name"]);
	$thumb = "";
	$type = $_POST["type"];
	$Nalbums = $page->Field2SQL($_POST["Nalbums"]);
	$query = "";
	if($id > 0) {
		$query = $page->DB_QueryPrepare("UPDATE `" . $page->ddb->DBname . "` . `bd_series` SET `name` = ?, `thumb` = ?, `type` = ?, `Nalbums` = ? WHERE `bd_series` . `id` = ? LIMIT 1;");
		$query->bind_param("sssii", $name, $thumb, $type, $Nalbums, $id);
	} else {
		$query = $page->DB_QueryPrepare("INSERT INTO `" . $page->ddb->DBname . "` . `bd_series` (`id`, `name`, `thumb`, `type`, `Nalbums`) VALUES(NULL, ?, ?, ?, ?);");
		$query->bind_param("sssi", $name, $thumb, $type, $Nalbums);
	}
	$page->DB_ExecuteManage($query);
	//
	$redirect = "index.php";
	if($id > 0) {//// first because comes from form and was here at beginning
		$redirect = "serie_display.php?id=$id";
	} elseif($isbn != "") {
		$redirect = "insert.php?isbn=$isbn";
	}
	$page->HeaderLocation($redirect);
}

if(isset($_GET["new"]) && isset($_GET["isbn"])) {
	$name = $_GET["new"];
	$isbn = $_GET["isbn"];
}

$page->CSS_ppJump(2);
$page->CSS_ppWing();
$page->js_Form();

$page_title = "Insert a new serie";
$body = "";
$args = new stdClass();
$args->rootpage = "..";
$body .= $page->GoHome($args);
$body .= "<div class=\"index_whole\">\n";

if(isset($_GET["id"])) {
	$id = $_GET["id"];
	if($id == 1) {
		$page->HeaderLocation("serie_display.php?id=1");
	}
	$serie = $page->DB_IdManage("SELECT * FROM `bd_series` WHERE `id` = ?", $id);
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

$body .= $page->SetTitle($page_title);
$page->HotBooty();

$body .= "<div class=\"bd_serie_insert_main\">\n";
$body .= $page->FormTag();
$args = new stdClass();
// ISBN
$args->type = "hidden";
$args->name = "isbn";
$args->value = $isbn;
$args->css = "bd_serie_isbn";
$body .= $page->FormField($args);
//
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
		$args->type = "hidden";
		$args->name = "id";
		$args->value = $id;
		$args->css = "bd_serie_id";
		$body .= $page->FormField($args);
	}
//
if($isbn > 0) {
	//// ISBN: serie already exists, go to insert
	$body .= "<div><a href=\"insert.php?isbn=$isbn&amp;fetcherlavache\" title=\"serie exists\">serie exists</a></div>\n";
}
//
	// Serie name
	$args->type = "text";
	$args->title = "Name";
	$args->name = "name";
	$args->value = $name;
	$args->css = "bd_serie_name";
	$args->size = 50;
	$args->autofocus = true;
	$args->required = true;
	$body .= $page->FormField($args);
	$args->required = false;
	$args->autofocus = false;
	$args->size = 0;
//
	// Upload if needed
	$body .= "<div class=\"bd_serie_upload\">\n";
	// Look at nidji site to copy-paste or create a function
	if($thumb == "") {
		$body .= "Not yet...";
	}
	$body .= "</div>\n";
//
	// type
	$args->type = "select";
	$args->title = "Type";
	$args->name = "type";
	$args->value = $type;
	$args->list = array("BD" => "BD", "manga" => "manga", "comics" => "comics", "other" => "other");
	$args->css = "bd_serie_type";
	$body .= $page->FormField($args);
//
	// N albums
	$args->type = "number";
	$args->min = 0;
	$args->title = "Number of albums";
	$args->name = "Nalbums";
	$args->value = $N;
	$args->css = "bd_serie_N";
	$body .= $page->FormField($args);
//

// Buttons
$args = new stdClass();
$args->css = "bd_serie_valbut";
$args->CloseTag = true;
$body .= $page->SubButt($id > 0, "'$name'", $args);

// Done
$body .= "</div>\n";
$body .= "</div>\n";

/*** Printing ***/
$page->show($body);
unset($page);
?>
