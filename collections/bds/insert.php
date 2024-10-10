<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";

require_once("$funcpath/logging.php");

require_once("$funcpath/form_fields.php");
global $theHiddenInput;
global $theSelectInput;
global $theTextInput;
global $theNumberInput;


$page = new PhPage($rootPath);
$page->loginHelper->notAllowed();
$page->bobbyTable->init();
//$page->logger->levelUp(6);
//$page->htmlHelper->init();

$page->cssHelper->dirUpWing();
$page->htmlHelper->jsForm();

$logger = $theLogger;

$body = $page->bodyBuilder->goHome("..");

    /*** default empty values ***/
    $id = 0;
    $serie_id  = "";
    $serie_title = "";
    $tome      = "";
    $title     = "";
    $author    = "";

if(isset($_POST["erase"])) {
    // Erase entry
    $id = $_POST["id"];
    $serie_id  = $_POST["serie_id"];
    $page->bobbyTable->idManage("DELETE FROM `{$page->bobbyTable->dbName}` . `bds` WHERE `bds` . `id` = ? LIMIT 1;", $id);
    $page->htmlHelper->headerLocation("serie_display.php?id=$serie_id");
} elseif(isset($_POST["title"])) {
    // DB treatement
    if(isset($_POST["id"])) {
        $id = $_POST["id"];
    }
    $serie_id  = $_POST["serie_id"];
    $tome      = $page->dbText->input2sql($_POST["tome"]);
    $title     = $page->dbText->input2sql($_POST["title"]);
    $author    = $page->dbText->input2sql($_POST["author"]);

    $query = null;

    if($id > 0) {
        $query = $page->bobbyTable->queryPrepare(
            "UPDATE `{$page->bobbyTable->dbName}` . `bds` "
            . "SET `isbn` = NULL, `serie_id` = ?, `tome` = ?, `title` = ?, `author` = ?, `publisher` = NULL, `date` = NULL "
            . "WHERE `bds` . `id` = ? LIMIT 1;"
        );

        $query->bind_param("ssssi", $serie_id, $tome, $title, $author, $id);

    } else {
        $query = $page->bobbyTable->queryPrepare(
            "INSERT INTO `{$page->bobbyTable->dbName}` . `bds` "
            . "(`id`, `isbn`, `serie_id`, `tome`, `title`, `author`, `publisher`, `date`) "
            . "VALUES(NULL, NULL, ?, ?, ?, ?, NULL, NULL);"
        );

        $query->bind_param("ssss", $serie_id, $tome, $title, $author);
    }

    $page->bobbyTable->executeManage($query);
    $page->htmlHelper->headerLocation("serie_display.php?id=$serie_id");

} else {
    if(isset($_GET["id"])) {
        $id = $_GET["id"];
        // Fetch infos from DB
        $query = $page->bobbyTable->idManage("SELECT * FROM `{$page->bobbyTable->dbName}` . `bds` WHERE `bds` . `id` = ? LIMIT 1;", $id);
        $query->store_result();
        if($query->num_rows == 0) {
            $query->close();
            exit("Error bad id");
        }
        $query->bind_result($id, $isbnNOTUSED, $serie_id, $tome, $title, $ti, $author, $publisherNOTUSED, $dateNOTUSED, $borrowed);
        $query->fetch();
        $query->close();
        $serie_query = $page->bobbyTable->idManage("SELECT * FROM `bd_series` WHERE `id` = ?", $serie_id);
        $serie_query->bind_result($serie_id, $serie_title, $serie_thumb, $serie_type, $serie_N);
        $serie_query->fetch();
        $serie_query->close();
        if($tome == 0 || $tome == "0") {
            $tome = "";
        }
        $title     = $page->dbText->sql2html($title);
        $author    = $page->dbText->sql2html($author);
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

        // Serie
        $body .= "<div class=\"bd_new_serie\">\n";
        $selectargs = array();
        $series = $page->bobbyTable->queryAlpha("bd_series", "name");
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
        $embedder->hasDiv = false;
        $body .= $theSelectInput->get("serie_id", $selectargs, $serie_id, "", NULL, NULL, $embedder);

        $body .= "&nbsp;-&nbsp;";
        $body .= $page->bodyBuilder->anchor("serie_insert.php", "New serie");
        $body .= "</div>\n";

    $attrSize = new FieldAttributes();

    $body .= $theNumberInput->get("tome", $tome, "Tome");
    $attrSize->size = 50;
    $body .= $theTextInput->get("title", $title, "Title", NULL, $attrSize);
    $attrSize->size = 40;
    $body .= $theTextInput->get("author", $author, "Author", NULL, $attrSize);

        // Buttons
        $cancelUrl = null;

        if($serie_id != "") {
            $cancelUrl = "serie_display.php?id=$serie_id";
        }

        $erasetxt = "";

        if($title != "") {
            $erasetxt = $page->dbText->input2sql($title) . " (";
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

echo $body;
?>
