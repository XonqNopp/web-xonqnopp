<?php
/*** Created: Mon 2015-07-20 13:53:42 CEST
 * TODO:
 */
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->NotAllowed();
$page->initDB();
require("functions.php");
//// debug
//$page->initHTML();
//$page->LogLevelUp(6);
//// CSS paths
$page->CSS_ppJump(2);
$page->CSS_ppWing(2);
$page->js_Form();
//// init body
$body = "";

$page_title = "Insert new sheet music";

$id = 0;
$title = "";
$author = "";
$opus = "";
$year = 0;
$pages = 0;
$SATB = "";
$origin = "";
$categories = "";
$comment = "";

if(isset($_POST["erase"])) {
	//// delete entry
	$id = $_POST["id"];
	$sql = $page->DB_IdManage("DELETE FROM `{$page->ddb->DBname}`.`SheetMusic` WHERE `SheetMusic`.`id` = ? LIMIT 1;", $id);
	$page->HeaderLocation();
} elseif(isset($_POST["submit"])) {
	//// DB handling
	$title = $page->field2SQL($_POST["title"]);
	$author = $page->field2SQL($_POST["author"]);
	$opus = $page->field2SQL($_POST["opus"]);
	$year = $_POST["year"];
	$pages = $_POST["pages"];
	$SATB = $page->field2SQL($_POST["SATB"]);
	$origin = $page->field2SQL($_POST["origin"]);
	$categories = implode("", $_POST["categories"]);
	$comment = $page->field2SQL($_POST["comment"]);
	if(isset($_POST["id"])) {
		//// update
		$id = $_POST["id"];
		$query = "UPDATE `{$page->ddb->DBname}`.`SheetMusic` SET ";
		$query .= "`title` = ?, `author` = ?, `opus` = ?, `year` = ?, `pages` = ?, `SATB` = ?, `origin` = ?, `categories` = ?, `comment` = ?";
		$query .= " WHERE `SheetMusic`.`id` = ? LIMIT 1;";
		$sql = $page->DB_QueryPrepare($query);
		$sql->bind_param("sssiissssi", $title, $author, $opus, $year, $pages, $SATB, $origin, $categories, $comment, $id);
		$page->DB_ExecuteManage($sql);
	} else {
		//// insert
		$query = "INSERT INTO `{$page->ddb->DBname}`.`SheetMusic` (`title`, `author`, `opus`, `year`, `pages`, `SATB`, `origin`, `categories`, `comment`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$sql = $page->DB_QueryPrepare($query);
		$sql->bind_param("sssiissss", $title, $author, $opus, $year, $pages, $SATB, $origin, $categories, $comment);
		$page->DB_ExecuteManage($sql);
		$id = $sql->insert_id;
	}
	$page->HeaderLocation("index.php#s$id");
	$page_title = "Edit sheet $title";
	$categories = CatExplode($categories);
} elseif(isset($_GET["id"])) {
	//// get data for display
	$id = $_GET["id"];
	$sql = $page->DB_SelectId("SheetMusic", $id);
	$sql->bind_result($id, $title, $author, $opus, $year, $pages, $SATB, $origin, $categories, $comment);
	$sql->fetch();
	$sql->close();
	$title = $page->SQL2field($title);
	$author = $page->SQL2field($author);
	$opus = $page->SQL2field($opus);
	$SATB = $page->SQL2field($SATB);
	$origin = $page->SQL2field($origin);
	$comment = $page->SQL2field($comment);
	$categories = CatExplode($categories);
	$page_title = "Edit sheet $title";
}

//// GoHome
$gohome = new stdClass();
$body .= $page->GoHome($gohome);
//// Set title and hot booty
$body .= $page->SetTitle($page_title);// before HotBooty
$page->HotBooty();
//
	//// form
	$body .= "<div>\n";
	$body .= $page->FormTag();
	//
		//// fields
			//// id
			$args = new stdClass();
			$args->type = "hidden";
			$args->name = "id";
			$args->value = $id;
			if($id > 0) {
				$body .= $page->FormField($args);
			}
		//
			//// title
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Title";
			$args->name = "title";
			$args->value = $title;
			$args->required = true;
			$args->autofocus = true;
			$body .= $page->FormField($args);
		//
			//// author
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Author";
			$args->name = "author";
			$args->value = $author;
			$body .= $page->FormField($args);
		//
			//// opus
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Opus";
			$args->name = "opus";
			$args->value = $opus;
			$body .= $page->FormField($args);
		//
			//// year
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Year";
			$args->name = "year";
			$args->value = $year;
			$args->min = 0;
			$body .= $page->FormField($args);
		//
			//// pages
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Pages";
			$args->name = "pages";
			$args->value = $pages;
			$args->min = 0;
			$body .= $page->FormField($args);
		//
			//// SATB
			$args = new stdClass();
			$args->type = "text";
			$args->title = "SATB";
			$args->name = "SATB";
			$args->value = $SATB;
			$body .= $page->FormField($args);
		//
			//// origin
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Origin";
			$args->name = "origin";
			$args->value = $origin;
			$body .= $page->FormField($args);
		//
			//// categories
			$args = new stdClass();
			$args->type = "checkbox";
			$args->title = "Categories";
			$args->name = "categories";
			$args->value = $categories;
			$args->list = $dogs;
			$args->vlist = true;
			$body .= $page->FormField($args);
		//
			//// comment
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Comment";
			$args->name = "comment";
			$args->value = $comment;
			$body .= $page->FormField($args);
		//
	//
		//// buttons
		$args = new stdClass();
		$args->CloseTag = true;
		$body .= $page->SubButt($id > 0, "'$title'", $args);
	//
	$body .= "</div>\n";
//


//// Finish
echo $body;
unset($page);
?>
