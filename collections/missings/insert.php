<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->NotAllowed();
$page->initDB();
//$page->LogLevelUp(6);

if(isset($_POST["dbid"])) {
	$dbtable = $_POST["dbtable"];
	$dbid = $_POST["dbid"];
	////
	$borrower = $_POST["borrower"];
	$dt = new stdClass();
	$dt->year  = $_POST["date_year"];
	$dt->month = $_POST["date_month"];
	$dt->day   = $_POST["date_day"];
	$date = $page->ConvertDate($dt)->date;
	$missing = $page->DB_QueryPrepare("INSERT INTO `" . $page->ddb->DBname . "` . `missings` (`id`, `borrower`, `dbtable`, `dbid`, `when`) VALUES(NULL, ?, ?, ?, ?)");
	$otherdb = $page->DB_QueryPrepare("UPDATE `" . $page->ddb->DBname . "` . `$dbtable` SET `borrowed` = 1 WHERE `id` = ? LIMIT 1;");
	$missing->bind_param("isis", $borrower, $dbtable, $dbid, $date);
	$otherdb->bind_param("i", $dbid);
	$page->DB_ExecuteManage($missing);
	$page->DB_ExecuteManage($otherdb);
	if($dbtable == "bds") {
		$page->HeaderLocation("index.php?view=bds$dbid#borrower$borrower");
	} else {
		$page->HeaderLocation("index.php?view=$dbtable$dbid#borrower$borrower");
	}
	// Must add to missing and change status in corresponding DB
	// Tip: when changing status elsehwere for return items, think to delete the enrty in missing DB
}

$page->CSS_ppJump(2);
$page->CSS_ppWing();

$body = "";

// Get info from URL
$dbtable = $_GET["db"];
$tables = array("bds" => "BD", "books" => "book", "dvds" => "DVD", "games" => "game");
$type = $tables[$dbtable];
$dbid = $_GET["id"];

$args = new stdClass();
$args->page = "../$dbtable/index";
$args->rootpage = "..";
$body .= $page->GoHome($args);

// Fetch item info
$item = $page->DB_IdManage("SELECT * FROM `$dbtable` WHERE `id` = ?", $dbid);
$item->store_result();
if($item->num_rows == 0) {
	$item->close();
	exit("Item not found");
}
if($dbtable == "bds") {
	$item->bind_result($dbid, $isbn, $serie_id, $tome, $title, $ti, $author, $publisher, $date, $borrowed);
	$item->fetch();
	if($title == "") {
		$getserie = $page->QueryManage("SELECT * FROM `bdseries` WHERE `id` = $serie_id");
		$serieSQL = $getserie->fetch_object();
		$getserie->close();
		$title = $serieSQL->name;
		if($tome != 0) {
			$title .= " ($tome)";
		}
	}
} elseif($dbtable == "books") {
	$item->bind_result($dbid, $isbn, $author, $title, $serie, $number, $publisher, $date, $language, $category, $summary, $borrowed);
	$item->fetch();
	if($title == "") {
		$title = $serie;
		if($number != 0) {
			$title .= " ($number)";
		}
	}
} elseif($dbtable == "dvds") {
	$item->bind_result($dbid, $title, $director, $actors, $languages, $subtitles, $duration, $serie, $number, $category, $summary, $burnt, $format, $borrowed);
	$item->fetch();
	if($title == "") {
		$title = $serie;
		if($number != 0) {
			$title .= " ($number)";
		}
	}
} elseif($dbtable == "games") {
	$item->bind_result($dbid, $title, $minP, $maxP, $age, $borrowed, $comment);
	$item->fetch();
}
$item->close();
// Fetch borrowers infos
$borrowers = $page->DB_QueryManage("SELECT * FROM `borrowers` ORDER BY `name` ASC");
if($borrowers->num_rows == 0) {
	exit("No borrowers found");
} else {
	while($person = $borrowers->fetch_object()) {
		$people[$person->id] = $person->name;
	}
}

/*** Now what to print ***/
$leaf = new stdClass();
// Title
$body .= $page->SetTitle("Borrow request for $title ($type)");
$page->HotBooty();

$body .= "<div class=\"whole\">\n";
$body .= $page->FormTag();
// Hidden infos
$body .= "<div class=\"missing_insert_hidden\">\n";

$args = new stdClass();
$args->type = "hidden";
$args->div = false;

$args->name = "dbtable";
$args->value = $dbtable;
$body .= $page->FormField($args);

$args->name = "dbid";
$args->value = $dbid;
$body .= $page->FormField($args);

$body .= "</div>\n";
// Borrower
$leaf->type = "select";
$leaf->title = "Borrower";
$leaf->name = "borrower";
$leaf->list = $people;
$leaf->value = "";
$leaf->autofocus = true;
$leaf->css = "missing_insert_borrower";
$body .= $page->FormField($leaf);

$leaf->autofocus = false;

// Date
$leaf->type = "Date";
$leaf->name = "date";
$leaf->css = "missing_insert_date";
$leaf->yearLast = 0;
$body .= $page->FormField($leaf);

// Buttons
$args = new stdClass();
$args->css = "missing_insert_button";
$args->CloseTag = true;
if($dbtable == "bds") {
	$args->cancelURL = "../bds/index.php";// suggestions???
} else {
	$sharp = substr($dbtable, 0, -1);
	$sharp = "#$sharp$dbid";
	$args->cancelURL = "../$dbtable/index.php$sharp";
}
$body .= $page->SubButt(false, null, $args);
$body .= "</div>\n";

$page->show($body);
unset($page);
?>
