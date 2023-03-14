<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require("${funcpath}_local/fetch_from_isbn.php");

$page = new PhPage($rootPath);
$page->NotAllowed();
$page->initDB();
//$page->LogLevelUp(6);
//$page->initHTML();

$page->CSS_ppJump(2);
$page->CSS_ppWing();
$page->js_Form();

$body = "";
$args = new stdClass();
$args->rootpage = "..";
$body .= $page->GoHome($args);

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
	$page->DB_IdManage("DELETE FROM `" . $page->ddb->DBname . "` . `bds` WHERE `bds` . `id` = ? LIMIT 1;", $id);
	$page->HeaderLocation("serie_display.php?id=$serie_id");
} elseif(isset($_POST["title"])) {
	// DB treatement
	if(isset($_POST["id"])) {
		$id = $_POST["id"];
	}
	$isbn      = $page->field2SQL($_POST["isbn"]);
	$serie_id  = $_POST["serie_id"];
	$tome      = $page->field2SQL($_POST["tome"]);
	$title     = $page->field2SQL($_POST["title"]);
	$author    = $page->field2SQL($_POST["author"]);
	$publisher = $page->field2SQL($_POST["publisher"]);
	$date      = $page->field2SQL($_POST["date"]);
	$query = "";
	if($id > 0) {
		$query = $page->DB_QueryPrepare("UPDATE `" . $page->ddb->DBname . "` . `bds` SET `isbn` = ?, `serie_id` = ?, `tome` = ?, `title` = ?, `author` = ?, `publisher` = ?, `date` = ? WHERE `bds` . `id` = ? LIMIT 1;");
		$query->bind_param("sssssssi", $isbn, $serie_id, $tome, $title, $author, $publisher, $date, $id);
	} else {
		$query = $page->DB_QueryPrepare("INSERT INTO `" . $page->ddb->DBname . "` . `bds` (`id`, `isbn`, `serie_id`, `tome`, `title`, `author`, `publisher`, `date`) VALUES(NULL, ?, ?, ?, ?, ?, ?, ?);");
		$query->bind_param("sssssss", $isbn, $serie_id, $tome, $title, $author, $publisher, $date);
	}
	$page->DB_ExecuteManage($query);
	$page->HeaderLocation("serie_display.php?id=$serie_id");
} else {
	$isbn = "";
	if(isset($_GET["isbn"])) {
		// Fetch infos from ISBN
		$isbn = $_GET["isbn"];
		$checkisbn = $page->DB_IdManage("SELECT * FROM `bds` WHERE `isbn` = ?", $isbn);
		$checkisbn->store_result();
		if($checkisbn->num_rows > 0) {
			$checkisbn->bind_result($isbn_id, $isbn, $serie_id, $tome, $title, $ti, $author, $publisher, $date, $borrowed);
			$checkisbn->fetch();
		} else {
			$page->ln_3(6, "fetching ISBN...", "insert.php");
			$infos = fetch_ISBN($page, "bd", $isbn);
			$serie = $infos->serie;
			$page->ln_3(5, "serie=$serie", "insert.php");
			if($serie != "" & !isset($_GET["fetcherlavache"])) {
				$page->ln_3(6, "serie valid", "insert.php");
				$getserie = $page->DB_QueryPrepare("SELECT `id`,`name` FROM `bd_series` WHERE `name` LIKE ? LIMIT 1;");
				$serielike = "%" . "$serie%";//// because % is screwing my var :-S
				$page->ln_3(5, "serielike=$serielike", "insert.php");
				$getserie->bind_param("s", $serielike);
				$page->DB_ExecuteManage($getserie);
				$getserie->store_result();
				$page->ln_3(6, "get serie executed", "insert.php");
				if($getserie->num_rows == 0) {
					$page->ln_3(6, "no serie found", "insert.php");
					$getserie->close();
					$page->HeaderLocation("serie_insert.php?new=$serie&isbn=$isbn");
					exit;
				}
				$page->ln_3(6, "found a serie", "insert.php");
				$getserie->bind_result($serie_id, $serie);
				$getserie->fetch();
				$getserie->close();
				$page->ln_3(5, "serie_id=$serie_id", "insert.php");
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
			$query = $page->DB_IdManage("SELECT * FROM `" . $page->ddb->DBname . "` . `bds` WHERE `bds` . `id` = ? LIMIT 1;", $id);
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
			$query = $page->DB_IdManage("SELECT * FROM `" . $page->ddb->DBname . "` . `bds` WHERE `bds` . `isbn` = ? LIMIT 1;", $isbn);
			$query->store_result();
			if($query->num_rows == 0) {
				$query->close();
				exit("Error bad isbn");
			}
			$query->bind_result($id, $isbn, $serie_id, $tome, $title, $ti, $author, $publisher, $date, $borrowed);
			$query->fetch();
			$query->close();
		}
		$serie_query = $page->DB_IdManage("SELECT * FROM `bd_series` WHERE `id` = ?", $serie_id);
		$serie_query->bind_result($serie_id, $serie_title, $serie_thumb, $serie_type, $serie_N);
		$serie_query->fetch();
		$serie_query->close();
		if($tome == 0 || $tome == "0") {
			$tome = "";
		}
		$title     = $page->SQL2field($title);
		$author    = $page->SQL2field($author);
		$publisher = $page->SQL2field($publisher);
		$date      = $page->SQL2field($date);
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
		$body .= $page->SetTitle("Update infos for $page_title (BD)");
		$body .= $page->FormTag();
		$args = new stdClass();
		$args->type = "hidden";
		$args->name = "id";
		$args->value = $id;
		$args->css = "bd_new_id";
		$body .= $page->FormField($args);
	} else {
		if(isset($_GET["serie_id"])) {
			$serie_id = $_GET["serie_id"];
		}
		$body .= $page->SetTitle("Insert a new BD");
		$body .= $page->FormTag();
	}
	$page->HotBooty();
	//// ISBN
	$args = new stdClass();
	$args->type = "number";
	$args->title = "ISBN";
	$args->name = "isbn";
	$args->value = $isbn;
	$args->css = "bd_new_isbn";
	//$args->size = 25;
	$args->autofocus = true;
	$body .= $page->FormField($args);
	//// Serie
	$body .= "<div class=\"bd_new_serie\">\n";
	$selectargs = array();
	$series = $page->DB_QueryAlpha("bd_series", "name");
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
	$args = new stdClass();
	$args->type = "select";
	$args->title = "Serie";
	$args->name = "serie_id";
	$args->value = $serie_id;
	$args->list = $selectargs;
	$args->div = false;
	$body .= $page->FormField($args);
	$body .= "&nbsp;-&nbsp;";
	$body .= "<a href=\"serie_insert.php\" title=\"New serie\">New serie</a>\n";
	$body .= "</div>\n";
	//// Tome
	$args = new stdClass();
	$args->type = "number";
	$args->title = "Tome";
	$args->name = "tome";
	$args->value = $tome;
	$args->css = "bd_new_tome";
	//$args->size = 7;
	$body .= $page->FormField($args);
	//// Title
	$args->type = "text";
	$args->title = "Title";
	$args->name = "title";
	$args->value = $title;
	$args->css = "bd_new_title";
	$args->size = 50;
	$body .= $page->FormField($args);
	//// Author
	$args->title = "Author";
	$args->name = "author";
	$args->value = $author;
	$args->css = "bd_new_author";
	$args->size = 40;
	$body .= $page->FormField($args);
	//// Publisher
	$args->title = "Publisher";
	$args->name = "publisher";
	$args->value = $publisher;
	$args->css = "bd_new_publisher";
	$args->size = 30;
	$body .= $page->FormField($args);
	//// Date
	$args->title = "Date";
	$args->name = "date";
	$args->value = $date;
	$args->css = "bd_new_date";
	$args->size = 15;
	$args->yearFirst = 1900;
	$args->yearLast = -1;
	$body .= $page->FormField($args);
	//// Buttons
	$body .= "<div class=\"bd_new_valbut\">\n";
	$args = new stdClass();
	if($serie_id != "") {
		$args->cancelURL = "serie_display.php?id=$serie_id";
	}
	$erasetxt = "";
	if($title != "") {
		$erasetxt .= $page->field2SQL($title);
		$erasetxt .= " (";
	}
	$erasetxt .= $serie_title;
	if($tome > 0) {
		$erasetxt .= " #$tome";
	}
	if($title != "") {
		$erasetxt .= ")";
	}
	$body .= $page->SubButt($id > 0, $erasetxt, $args);
	$body .= "</div>\n";

	$body .= "</form>\n";
}

/*** Printing ***/
$page->show($body);
unset($page);
?>
