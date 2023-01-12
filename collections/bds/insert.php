<?php
require("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require("{$funcpath}_local/fetch_from_isbn.php");

require("$funcpath/logging.php");

require("$funcpath/form_fields.php");
use FieldEmbedder;
use FieldAttributes;
global $theHiddenInput;
global $theSelectInput;
global $theTextInput;
global $theNumberInput;


$page = new PhPage($rootPath);
$page->loginHelper->notAllowed();
$page->dbHelper->init();
//$page->logger->levelUp(6);
//$page->htmlHelper->init();

$page->cssHelper->dirUpWing();
$page->htmlHelper->jsForm();

$logger = $theLogger;

$body = $page->bodyHelper->goHome("..");

	/*** default empty values ***/
	$id = 0;
	$isbn      = "";
	$serie_id  = "";
	$serie_title = "";
	$tome      = "";
	$title     = "";
	$author    = "";
	$publisher = "";
	$date      = "";

if(isset($_POST["erase"])) {
	// Erase entry
	$id = $_POST["id"];
	$serie_id  = $_POST["serie_id"];
	$page->dbHelper->idManage("DELETE FROM `{$page->dbHelper->dbName}` . `bds` WHERE `bds` . `id` = ? LIMIT 1;", $id);
	$page->htmlHelper->headerLocation("serie_display.php?id=$serie_id");
} elseif(isset($_POST["title"])) {
	// DB treatement
	if(isset($_POST["id"])) {
		$id = $_POST["id"];
	}
	$isbn      = $page->dbText->field2SQL($_POST["isbn"]);
	$serie_id  = $_POST["serie_id"];
	$tome      = $page->dbText->field2SQL($_POST["tome"]);
	$title     = $page->dbText->field2SQL($_POST["title"]);
	$author    = $page->dbText->field2SQL($_POST["author"]);
	$publisher = $page->dbText->field2SQL($_POST["publisher"]);
	$date      = $page->dbText->field2SQL($_POST["date"]);
	$query = "";
	if($id > 0) {
		$query = $page->dbHelper->queryPrepare("UPDATE `{$page->dbHelper->dbName}` . `bds` SET `isbn` = ?, `serie_id` = ?, `tome` = ?, `title` = ?, `author` = ?, `publisher` = ?, `date` = ? WHERE `bds` . `id` = ? LIMIT 1;");
		$query->bind_param("sssssssi", $isbn, $serie_id, $tome, $title, $author, $publisher, $date, $id);
	} else {
		$query = $page->dbHelper->queryPrepare("INSERT INTO `{$page->dbHelper->dbName}` . `bds` (`id`, `isbn`, `serie_id`, `tome`, `title`, `author`, `publisher`, `date`) VALUES(NULL, ?, ?, ?, ?, ?, ?, ?);");
		$query->bind_param("sssssss", $isbn, $serie_id, $tome, $title, $author, $publisher, $date);
	}
	$page->dbHelper->executeManage($query);
	$page->htmlHelper->headerLocation("serie_display.php?id=$serie_id");
} else {
	$isbn = "";
	if(isset($_GET["isbn"])) {
		// Fetch infos from ISBN
		$isbn = $_GET["isbn"];
		$checkisbn = $page->dbHelper->idManage("SELECT * FROM `bds` WHERE `isbn` = ?", $isbn);
		$checkisbn->store_result();
		if($checkisbn->num_rows > 0) {
			$checkisbn->bind_result($isbn_id, $isbn, $serie_id, $tome, $title, $ti, $author, $publisher, $date, $borrowed);
			$checkisbn->fetch();
		} else {
			$logger->trace("fetching ISBN...", "insert.php");
			$infos = fetch_ISBN("bd", $isbn);
			$serie = $infos->serie;
			$logger->debug("serie=$serie", "insert.php");
			if($serie != "" & !isset($_GET["fetcherlavache"])) {
				$logger->trace("serie valid", "insert.php");
				$getserie = $page->dbHelper->queryPrepare("SELECT `id`,`name` FROM `bd_series` WHERE `name` LIKE ? LIMIT 1;");
				$serielike = "%" . "$serie%";//// because % is screwing my var :-S
				$logger->debug("serielike=$serielike", "insert.php");
				$getserie->bind_param("s", $serielike);
				$page->dbHelper->executeManage($getserie);
				$getserie->store_result();
				$logger->trace("get serie executed", "insert.php");
				if($getserie->num_rows == 0) {
					$logger->trace("no serie found", "insert.php");
					$getserie->close();
					$page->htmlHelper->headerLocation("serie_insert.php?new=$serie&isbn=$isbn");
					exit;
				}
				$logger->trace("found a serie", "insert.php");
				$getserie->bind_result($serie_id, $serie);
				$getserie->fetch();
				$getserie->close();
				$logger->debug("serie_id=$serie_id", "insert.php");
			}
			//// not sure if addslashes is required, should run some tests
			foreach($infos as $key => $value) {
				eval("\$$key = \"" . addslashes($value) . "\";");// Must escape some characters!
			}
			$serie_title = $serie;
		}
		$checkisbn->close();
	}
	if(isset($_GET["id"]) || isset($isbn_id)) {
		$it = "";
		if(isset($_GET["id"])) {
			$id = $_GET["id"];
			// Fetch infos from DB
			$query = $page->dbHelper->idManage("SELECT * FROM `{$page->dbHelper->dbName}` . `bds` WHERE `bds` . `id` = ? LIMIT 1;", $id);
			$query->store_result();
			if($query->num_rows == 0) {
				$query->close();
				exit("Error bad id");
			}
			$query->bind_result($id, $isbn, $serie_id, $tome, $title, $ti, $author, $publisher, $date, $borrowed);
			$query->fetch();
			$query->close();
			if($isbn == 0 || $isbn == "0") {
				$isbn = "";
			}
		} else {
			// Fetch infos from DB
			$query = $page->dbHelper->idManage("SELECT * FROM `{$page->dbHelper->dbName}` . `bds` WHERE `bds` . `isbn` = ? LIMIT 1;", $isbn);
			$query->store_result();
			if($query->num_rows == 0) {
				$query->close();
				exit("Error bad isbn");
			}
			$query->bind_result($id, $isbn, $serie_id, $tome, $title, $ti, $author, $publisher, $date, $borrowed);
			$query->fetch();
			$query->close();
		}
		$serie_query = $page->dbHelper->idManage("SELECT * FROM `bd_series` WHERE `id` = ?", $serie_id);
		$serie_query->bind_result($serie_id, $serie_title, $serie_thumb, $serie_type, $serie_N);
		$serie_query->fetch();
		$serie_query->close();
		if($tome == 0 || $tome == "0") {
			$tome = "";
		}
		$title     = $page->dbText->sql2field($title);
		$author    = $page->dbText->sql2field($author);
		$publisher = $page->dbText->sql2field($publisher);
		$date      = $page->dbText->sql2field($date);
		if($date == "0000-00-00") {
			$date = "";
		}
		$page_title = $serie_title;
		if($page_title == "") {
			$page_title = $title;
		} else {
			$page_title = "'$page_title'";
			if($tome != "") {
				$page_title .= " #$tome";
			}
		}
		// Some infos to display
		$body .= $page->htmlHelper->setTitle("Update infos for $page_title (BD)");
		$body .= $page->formHelper->tag();
		$body .= $theHiddenInput->get("id", $id);
	} else {
		if(isset($_GET["serie_id"])) {
			$serie_id = $_GET["serie_id"];
		}
		$body .= $page->htmlHelper->setTitle("Insert a new BD");
		$body .= $page->formHelper->tag();
	}
	$page->htmlHelper->hotBooty();

	$isbnAttr = new FieldAttributes(false, true);
	$body .= $theNumberInput->get("isbn", $isbn, "ISBN", $isbnAttr);

		// Serie
		$body .= "<div class=\"bd_new_serie\">\n";
		$selectargs = array();
		$series = $page->dbHelper->queryAlpha("bd_series", "name");
		if($series->num_rows > 0) {
			while($s = $series->fetch_object()) {
				$serie_name = $s->name;
				if($serie_name == "") {
					$serie_name = "HORS SERIES";
				}
				$selectargs[$s->id] = $serie_name;
			}
			$series->close();
		}

		$embedder = new FieldEmbedder("Serie");
		$embedder->bDiv = false;
		$body .= $theSelectInput->get("serie_id", $selectargs, $serie_id, "", NULL, NULL, $embedder);

		$body .= "&nbsp;-&nbsp;";
		$body .= "<a href=\"serie_insert.php\" title=\"New serie\">New serie</a>\n";
		$body .= "</div>\n";

	$attrSize = new FieldAttributes();

	$body .= $theNumberInput->get("tome", $tome, "Tome");
	$attrSize->size = 50;
	$body .= $theTextInput->get("title", $title, "Title", NULL, $attrSize);
	$attrSize->size = 40;
	$body .= $theTextInput->get("author", $author, "Author", NULL, $attrSize);
	$attrSize->size = 30;
	$body .= $theTextInput->get("publisher", $publisher, "Publisher", NULL, $attrSize);
	$attrSize->size = 15;
	$body .= $theTextInput->get("date", $date, "Date", NULL, $attrSize);

		// Buttons
		$cancelUrl = null;

		if($serie_id != "") {
			$cancelUrl = "serie_display.php?id=$serie_id";
		}

		$erasetxt = "";

		if($title != "") {
			$erasetxt = $page->dbText->field2SQL($title) . " (";
		}

		$erasetxt .= $serie_title;

		if($tome > 0) {
			$erasetxt .= " #$tome";
		}

		if($title != "") {
			$erasetxt .= ")";
		}

		$body .= $page->formHelper->subButt($id > 0, $erasetxt, $cancelUrl);
}

/*** Printing ***/
echo $body;
unset($page);
?>
