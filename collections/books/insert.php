<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->bobbyTable->init();

$page->loginHelper->notAllowed();
require_once("{$funcpath}_local/fetch_from_isbn.php");

require_once("{$funcpath}/form_fields.php");
global $theHiddenInput;
global $theTextInput;
global $theRadioInput;
global $theTextarea;
global $theNumberInput;
global $theDateInput;


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
$page->cssHelper->dirUpWing();
$page->htmlHelper->jsForm();

$body = "";
if(isset($_POST["title"])) {
    if(isset($_POST["erase"])) {
        // Erase entry
        $id = $_POST["id"];
        $page->bobbyTable->idManage("DELETE FROM `{$page->bobbyTable->dbName}` . `books` WHERE `books` . `id` = ? LIMIT 1;", $id);
        $page->htmlHelper->headerLocation();
    } else {
        // DB treatement
        if(isset($_POST["id"])) {
            $id = $_POST["id"];
        }
        $isbn = $page->dbText->field2SQL($_POST["isbn"]);
        $title = $page->dbText->field2SQL($_POST["title"]);
        $author = $page->dbText->field2SQL($_POST["author"]);
        $serie = $page->dbText->field2SQL($_POST["serie"]);
        $number = $page->dbText->field2SQL($_POST["number"]);
        $publisher = $page->dbText->field2SQL($_POST["publisher"]);
        $date = $page->dbText->field2SQL($_POST["date"]);
        $language = $_POST["language"];
        $category = $_POST["category"];
        $summary = $page->dbText->txtarea2SQL($_POST["summary"]);
        $query = "";
        if($id > 0) {
            $query = $page->bobbyTable->queryPrepare("UPDATE `{$page->bobbyTable->dbName}` . `books` SET `isbn` = ?, `title` = ?, `author` = ?, `serie` = ?, `number` = ?, `publisher` = ?, `date` = ?, `language` = ?, `category` = ?, `summary` = ? WHERE `books` . `id` = ? LIMIT 1;");
            $query->bind_param("ssssssssssi", $isbn, $title, $author, $serie, $number, $publisher, $date, $language, $category, $summary, $id);

        } else {
            $query = $page->bobbyTable->queryPrepare("INSERT INTO `{$page->bobbyTable->dbName}` . `books` (`id`, `isbn`, `title`, `author`, `serie`, `number`, `publisher`, `date`, `language`, `category`, `summary`) VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
            $query->bind_param("ssssssssss", $isbn, $title, $author, $serie, $number, $publisher, $date, $language, $category, $summary);
        }
        $page->bobbyTable->executeManage($query);
        $id_back = 0;
        if($id > 0) {
            $id_back = $id;
        } else {
            $id_back = $query->insert_id;
        }
        $page->htmlHelper->headerLocation("index.php#book$id_back");// Check if serie
    }
    exit;
}

$body = $page->bodyBuilder->goHome("..");

$page_title = "Insert a new book";
$morebody = "";
if(isset($_GET["isbn"])) {
    // Fetch infos from ISBN
    $isbn = $_GET["isbn"];
    $checkisbn = $page->bobbyTable->idManage("SELECT * FROM `books` WHERE `isbn` = ?", $isbn);
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
        $infos = fetch_ISBN("book", $isbn);
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
    $query = $page->bobbyTable->idManage("SELECT * FROM `{$page->bobbyTable->dbName}` . `books` WHERE `books` . `id` = ? LIMIT 1;", $id);
    $query->store_result();
    if($query->num_rows == 0) {
        $query->close();
        exit("Error bad id");
    }
    $query->bind_result($id, $isbn, $author, $title, $serie, $number, $publisher, $date, $language, $category, $summary, $borrowed);
    $query->fetch();
    $query->close();
    $author    = $page->dbText->sql2field($author);
    $title     = $page->dbText->sql2field($title);
    $serie     = $page->dbText->sql2field($serie);
    $number    = $page->dbText->sql2field($number);
    $publisher = $page->dbText->sql2field($publisher);
    $date      = $page->dbText->sql2field($date);
    $summary = $page->dbText->sql2txtarea($summary);
    if($date == "0000-00-00") {
        $date = "";
    }
    // Some infos to display
    $page_title = "Update infos for book $title";
    $morebody .= $theHiddenInput->get("id", $id);
}

$body .= $page->htmlHelper->setTitle($page_title);
$page->htmlHelper->hotBooty();

$body .= $page->formHelper->tag();
$body .= $morebody;

$attr = new FieldAttributes(false, true);
$attr->min = 0;
$body .= $theNumberInput->get("isbn", $isbn, "ISBN", $attr);

$args->autofocus = false;
// Title
$titleAttr = new FieldAttributes(true);
$titleAttr->size = 50;
$body .= $theTextInput->get("title", $title, "Title", NULL, $titleAttr);

$attrSize = new FieldAttributes();
$attrSize->size = 25;
$body .= $theTextInput->get("author", $author, "Author", NULL, $attrSize);
$attrSize->size = 50;
$body .= $theTextInput->get("serie", $serie, "Serie", NULL, $attrSize);
$body .= $theNumberInput->get("number", $number, "Number");
$attrSize->size = 25;
$body .= $theTextInput->get("publisher", $publisher, "Publisher", NULL, $attrSize);
$attrSize->size = 15;
$body .= $theDateInput->get("date", $date, "Date", $attrSize);
$body .= $theTextarea->get("summary", $summary, 15, 70, "Summary");

$languages = array("fr" => "French","en" => "English","it" => "Italian","de" => "German","??" => "other");
$body .= $theRadioInput->get("language", $languages, $language, "Language");

$categories = array("novel" => "Novel", "doc" => "Doc");
$body .= $theRadioInput->get("category", $categories, $category, "Category");

// Buttons
$body .= $page->formHelper->subButt($id > 0, "'$title'");

echo $body;
?>
