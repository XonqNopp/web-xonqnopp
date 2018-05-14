<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->initDB();

$page->NotAllowed();
require("${funcpath}_local/fetch_from_isbn.php");

	//// init vars
	$id        = 0;
	$isbn      = "";
	$title     = "";
	$author    = "";
	$serie     = "";
	$number    = "";
	$publisher = "";
	$date      = "";
	$language  = "fr";
	$category  = "novel";
	$summary   = "";
//
$page->CSS_ppJump(2);
$page->CSS_ppWing();
$page->js_Form();

$body = "";
if(isset($_POST["title"])) {
	if(isset($_POST["erase"])) {
		// Erase entry
		$id = $_POST["id"];
		$page->DB_IdManage("DELETE FROM `" . $page->ddb->DBname . "` . `books` WHERE `books` . `id` = ? LIMIT 1;", $id);
		$page->HeaderLocation();
	} else {
		// DB treatement
		if(isset($_POST["id"])) {
			$id = $_POST["id"];
		}
		$isbn = $page->field2SQL($_POST["isbn"]);
		$title = $page->field2SQL($_POST["title"]);
		$author = $page->field2SQL($_POST["author"]);
		$serie = $page->field2SQL($_POST["serie"]);
		$number = $page->field2SQL($_POST["number"]);
		$publisher = $page->field2SQL($_POST["publisher"]);
		$date = $page->field2SQL($_POST["date"]);
		$language = $_POST["language"];
		$category = $_POST["category"];
		$summary = $page->txtarea2SQL($_POST["summary"]);
		$query = "";
		if($id > 0) {
			$query = $page->DB_QueryPrepare("UPDATE `" . $page->ddb->DBname . "` . `books` SET `isbn` = ?, `title` = ?, `author` = ?, `serie` = ?, `number` = ?, `publisher` = ?, `date` = ?, `language` = ?, `category` = ?, `summary` = ? WHERE `books` . `id` = ? LIMIT 1;");
			$query->bind_param("ssssssssssi", $isbn, $title, $author, $serie, $number, $publisher, $date, $language, $category, $summary, $id);
		} else {
			$query = $page->DB_QueryPrepare("INSERT INTO `" . $page->ddb->DBname . "` . `books` (`id`, `isbn`, `title`, `author`, `serie`, `number`, `publisher`, `date`, `language`, `category`, `summary`) VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
			$query->bind_param("ssssssssss", $isbn, $title, $author, $serie, $number, $publisher, $date, $language, $category, $summary);
		}
		$page->DB_ExecuteManage($query);
		$id_back = 0;
		if($id > 0) {
			$id_back = $id;
		} else {
			$id_back = $query->insert_id;
		}
		$page->HeaderLocation("index.php#book$id_back");// Check if serie
	}
	exit;
}

$body = "";
$args = new stdClass();
//$args->page = "";
$args->rootpage = "..";
$body .= $page->GoHome($args);

$page_title = "Insert a new book";
$morebody = "";
if(isset($_GET["isbn"])) {
	// Fetch infos from ISBN
	$isbn = $_GET["isbn"];
	$checkisbn = $page->DB_IdManage("SELECT * FROM `books` WHERE `isbn` = ?", $isbn);
	$checkisbn->store_result();
	if($checkisbn->num_rows > 0) {
		$checkisbn->bind_result($isbn_id, $isbn, $author, $title, $serie, $number, $publisher, $date, $language, $category, $summary, $borrowed);
		$found = $checkisbn->fetch();
	} else {
		$subisbn = substr($isbn,3,1);
		if($subisbn == "0" || $subisbn == "1") {
			$language = "en";
		} elseif($subisbn == "2") {
			$language = "fr";
		} elseif($subisbn == "3") {
			$language = "de";
		}
		$category = "novel";
		$infos = fetch_ISBN($page, "book", $isbn);
		foreach($infos as $key => $value) {
			eval("\$$key = \"" . addslashes($value) . "\";");// Must escape some characters!
		}
	}
	$checkisbn->close();
}
if(isset($_GET["id"])) {
	$id = $_GET["id"];
}
if(isset($isbn_id)) {
	$id = $isbn_id;
}
if($id > 0) {
	// Fetch infos from DB
	$query = $page->DB_IdManage("SELECT * FROM `" . $page->ddb->DBname . "` . `books` WHERE `books` . `id` = ? LIMIT 1;", $id);
	$query->store_result();
	if($query->num_rows == 0) {
		$query->close();
		exit("Error bad id");
	}
	$query->bind_result($id, $isbn, $author, $title, $serie, $number, $publisher, $date, $language, $category, $summary, $borrowed);
	$query->fetch();
	$query->close();
	$author    = $page->SQL2field($author);
	$title     = $page->SQL2field($title);
	$serie     = $page->SQL2field($serie);
	$number    = $page->SQL2field($number);
	$publisher = $page->SQL2field($publisher);
	$date      = $page->SQL2field($date);
	$summary = $page->SQL2txtarea($summary);
	if($date == "0000-00-00") {
		$date = "";
	}
	// Some infos to display
	$page_title = "Update infos for book $title";
	$args = new stdClass();
	$args->type = "hidden";
	$args->name = "id";
	$args->value = $id;
	$args->css = "book_new_id";
	$morebody .= $page->FormField($args);
}

$body .= $page->SetTitle($page_title);
$page->HotBooty();

$body .= $page->FormTag();
$body .= $morebody;

$args = new stdClass();

// ISBN
$args->type = "number";
$args->title = "ISBN";
$args->name = "isbn";
$args->value = $isbn;
$args->css = "book_new_isbn";
$args->autofocus = true;
$args->min = 0;
$body .= $page->FormField($args);
$args->autofocus = false;
// Title
$args->type = "text";
$args->title = "Title";
$args->name = "title";
$args->value = $title;
$args->css = "book_new_title";
$args->size = 50;
$args->required = true;
$body .= $page->FormField($args);
$args->required = false;
// Author
$args->type = "text";
$args->title = "Author";
$args->name = "author";
$args->value = $author;
$args->size = 25;
$args->css = "book_new_author";
$body .= $page->FormField($args);
// Serie
$args->type = "text";
$args->title = "Serie";
$args->name = "serie";
$args->value = $serie;
$args->css = "book_new_serie";
$args->size = 50;
$body .= $page->FormField($args);
// Number
$args->type = "number";
$args->title = "Number";
$args->name = "number";
$args->value = $number;
$args->css = "book_new_number";
$args->size = 0;
$body .= $page->FormField($args);
// Publisher
$args->type = "text";
$args->title = "Publisher";
$args->name = "publisher";
$args->value = $publisher;
$args->css = "book_new_publisher";
$args->size = 25;
$body .= $page->FormField($args);
// Date
$args->type = "date";
$args->title = "Date";
$args->name = "date";
$args->value = $date;
$args->css = "book_new_date";
$args->size = 15;
$body .= $page->FormField($args);
// Summary
$args->type = "textarea";
$args->title = "Summary";
$args->name = "summary";
$args->value = $summary;
$args->css = "book_new_summary";
$args->rows = 15;
$args->cols = 70;
$body .= $page->FormField($args);
// Language
$args->type = "radio";
$args->title = "Language";
$args->name = "language";
$args->value = $language;
$args->list = array("fr" => "French","en" => "English","it" => "Italian","de" => "German","??" => "other");
$args->css = "book_new_language";
$args->size = 0;
$args->rows = 0;
$args->cols = 0;
$body .= $page->FormField($args);
// Category
$args->type = "radio";
$args->title = "Category";
$args->name = "category";
$args->value = $category;
$args->list = array("novel" => "Novel", "doc" => "Doc");
$args->css = "book_new_cat";
$body .= $page->FormField($args);
// Buttons
$args = new stdClass();
$args->css = "book_new_valbut";
$args->CloseTag = true;
$body .= $page->SubButt($id > 0, "'$title'", $args);

$page->show($body);
unset($page);
?>
