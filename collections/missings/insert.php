<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->loginHelper->notAllowed();
$page->bobbyTable->init();

require_once("$funcpath/form_fields.php");
global $theHiddenInput;
global $theSelectInput;
global $theDateInput;


//$page->logger->levelUp(6);

if(isset($_POST["dbid"])) {
    $dbtable = $_POST["dbtable"];
    $dbid = $_POST["dbid"];
    $borrower = $_POST["borrower"];

    $missing = $page->bobbyTable->queryPrepare("INSERT INTO `{$page->bobbyTable->dbName}` . `missings` (`id`, `borrower`, `dbtable`, `dbid`, `when`) VALUES(NULL, ?, ?, ?, ?)");
    $missing->bind_param("isis", $borrower, $dbtable, $dbid, $_POST["date"]);
    $page->bobbyTable->executeManage($missing);

    $otherdb = $page->bobbyTable->queryPrepare("UPDATE `{$page->bobbyTable->dbName}` . `{$dbtable}` SET `borrowed` = 1 WHERE `id` = ? LIMIT 1;");
    $otherdb->bind_param("i", $dbid);
    $page->bobbyTable->executeManage($otherdb);

    $page->htmlHelper->headerLocation("index.php?view=$dbtable$dbid#borrower$borrower");

    // Must add to missing and change status in corresponding DB
    // Tip: when changing status elsehwere for return items, think to delete the entry in missing DB
}

$page->cssHelper->dirUpWing();

// Get info from URL
$dbtable = $_GET["db"];
$tables = array("bds" => "BD", "books" => "book", "dvds" => "DVD");
$type = $tables[$dbtable];
$dbid = $_GET["id"];

$body = $page->bodyBuilder->goHome("..", "../$dbtable/index.php");


// Fetch item info
$item = $page->bobbyTable->idManage("SELECT * FROM `$dbtable` WHERE `id` = ?", $dbid);
$item->store_result();

if($item->num_rows == 0) {
    $item->close();
    $page->logger->fatal("Item not found");
}

if($dbtable == "bds") {
    $item->bind_result($dbid, $isbn, $serie_id, $number, $title, $ti, $author, $publisher, $date, $borrowed);
    $item->fetch();
    $serie = "";
    if($serie_id > 0) {
        $getserie = $page->bobbyTable->queryManage("SELECT * FROM `bd_series` WHERE `id` = $serie_id");
        $serieSQL = $getserie->fetch_object();
        $getserie->close();
        $serie = $serieSQL->name;
    }

} elseif($dbtable == "books") {
    $item->bind_result($dbid, $isbn, $author, $title, $serie, $number, $publisher, $date, $language, $category, $summary, $borrowed);
    $item->fetch();

} elseif($dbtable == "dvds") {
    $item->bind_result($dbid, $title, $director, $actors, $languages, $subtitles, $duration, $serie, $number, $category, $summary, $burnt, $format, $borrowed);
    $item->fetch();
}

$item->close();

// Fetch borrowers infos
$borrowers = $page->bobbyTable->queryManage("SELECT * FROM `borrowers` ORDER BY `name` ASC");
if($borrowers->num_rows == 0) {
    $borrowers->close();
    $page->logger->fatal("No borrowers found");

} else {
    while($person = $borrowers->fetch_object()) {
        $people[$person->id] = $person->name;
    }
}
$borrowers->close();

// Now what to print
// Title
function titleSerie($title, $serie, $number) {
    if($serie == "") {
        return $title;
    }

    if($title == "") {
        return "$serie $number";
    }

    return "$title ($serie $number)";
}


$title = titleSerie($title, $serie, $number);
$body .= $page->htmlHelper->setTitle("Borrow request for: $title ($type)");
$page->htmlHelper->hotBooty();

$body .= "<div>\n";
$body .= $page->formHelper->tag();


// Hidden infos
$body .= $theHiddenInput->get("dbtable", $dbtable);
$body .= $theHiddenInput->get("dbid", $dbid);


$attributes = new FieldAttributes(false, true);
$body .= $theSelectInput->get("borrower", $people, "", "Borrower", $attributes);

$attr = new FieldAttributes();
$attr->max = "now";
$body .= $theDateInput->get("date", NULL, NULL, $attr);


// Buttons
$cancelUrl = "../bds/index.php";// suggestions???

if($dbtable != "bds") {
    $sharp = substr($dbtable, 0, -1);
    $sharp = "#$sharp$dbid";

    $cancelUrl = "../$dbtable/index.php$sharp";
}

$body .= $page->formHelper->subButt(false, null, $cancelUrl);
$body .= "</div>\n";

echo $body;
?>
