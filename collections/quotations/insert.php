<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->loginHelper->notAllowed();

//$page->htmlHelper->init();
//$page->logger->levelUp(6);

require_once("$funcpath/form_fields.php");
global $theTextInput;
global $theSelectInput;
global $theCheckboxInput;
global $theTextarea;


$page->bobbyTable->init();
$page->cssHelper->dirUpWing();
$page->htmlHelper->jsForm();

$page_title = "Insert a new quote";


require_once("categories.php");
global $kCategories;

$id = 0;
$quote = "";
$authorfirst = "";
$authorlast = "";
$place = "";
$favs = array("no", "yes");
$fav = 0;
foreach($kCategories as $dog) {
    eval("$$dog = 0;");
}


    /*** DB ***/
    if(isset($_POST["submit"])) {
        if(isset($_POST["id"])) {
            $id = $_POST["id"];
        }

        $quote = $page->dbText->inputTextareaParagraph2sql($_POST["quote"]);
        $authorlast = $page->dbText->input2sql($_POST["authorlast"]);
        $authorfirst = $page->dbText->input2sql($_POST["authorfirst"]);
        $place = $page->dbText->input2sql($_POST["place"]);
        $fav = $_POST["fav"];

        if(isset($_POST["cats"])) {
            foreach($kCategories as $dog) {
                if(in_array($dog, $_POST["cats"])) {
                    $$dog = 1;
                }
            }
        }

        if($id > 0) {
            $query = "UPDATE `{$page->bobbyTable->dbName}` . `quotations` SET `quote` = ?, `authorlast` = ?, `authorfirst` = ?, `place` = ?, `fav` = ?";
            foreach($kCategories as $dog) {
                $query .= ", `$dog` = ?";
            }
            $query .= " WHERE `quotations` . `id` = ? LIMIT 1;";
            $sql = $page->bobbyTable->queryPrepare($query);
            $sql->bind_param("ssssiiiiiiiiiiiiiiiiiii", $quote, $authorlast, $authorfirst, $place, $fav, $amour, $argent, $cuisine, $environnement, $EPFL, $humour, $informatique, $litterature, $medecine, $militaire, $musique, $philosophie, $politique, $religions, $sciences, $sexe, $sports, $id);
            $page->bobbyTable->executeManage($sql);
        } else {
            $query = "INSERT INTO `{$page->bobbyTable->dbName}` . `quotations` (`id`, `quote`, `authorlast`, `authorfirst`, `place`, `fav`";
            $qmarks = "";
            foreach($kCategories as $dog) {
                $query .= ", `$dog`";
                $qmarks .= ", ?";
            }
            $query .= ") VALUES(NULL, ?, ?, ?, ?, ?$qmarks);";
            $sql = $page->bobbyTable->queryPrepare($query);
            $sql->bind_param("ssssiiiiiiiiiiiiiiiiii", $quote, $authorlast, $authorfirst, $place, $fav, $amour, $argent, $cuisine, $environnement, $EPFL, $humour, $informatique, $litterature, $medecine, $militaire, $musique, $philosophie, $politique, $religions, $sciences, $sexe, $sports);
            $page->bobbyTable->executeManage($sql);
            $id = $sql->insert_id;
        }
        $page->htmlHelper->headerLocation("index.php#c$id");
    } elseif(isset($_POST["erase"])) {
        $id = $_POST["id"];
        $query = "DELETE FROM `{$page->bobbyTable->dbName}` . `quotations` WHERE `quotations` . `id` = ? LIMIT 1;";
        $sql = $page->bobbyTable->queryPrepare($query);
        $sql->bind_param("i", $id);
        $page->bobbyTable->executeManage($sql);
        $page->htmlHelper->headerLocation();
        exit;
    }

if(isset($_GET["id"])) {
    $id = $_GET["id"];
    $query = "SELECT * FROM `quotations` WHERE `id` = ? LIMIT 1;";
    $result = $page->bobbyTable->idManage($query, $id);
    $result->store_result();
    if($result->num_rows == 0) {
        $result->close();
        exit("bad id");
    }
    $result->bind_result($id, $quote, $authorlast, $authorfirst, $place, $fav, $amour, $argent, $cuisine, $environnement, $EPFL, $humour, $informatique, $litterature, $medecine, $militaire, $musique, $philosophie, $politique, $religions, $sciences, $sexe, $sports);
    $result->fetch();
    $result->close();
    $quote = $page->dbText->sql2htmlTextareaParagraph($quote);
    $authorfirst = $page->dbText->sql2html($authorfirst);
    $authorlast = $page->dbText->sql2html($authorlast);
    $place = $page->dbText->sql2html($place);
    //foreach($kCategories as $dog) {
        //eval("$$dog = \$entry->$dog;");
    //}
    $page_title = "Edit quote #$id";
} else {
    if(isset($_GET["al"])) {
        $authorlast = $_GET["al"];
    }
    if(isset($_GET["af"])) {
        $authorfirst = $_GET["af"];
    }
}

$goUp = "index.php";
if($id > 0) {
    $goUp .= "?id=$id";
}
$body = $page->bodyBuilder->goHome("..", $goUp);

$body .= $page->htmlHelper->setTitle($page_title);
$page->htmlHelper->hotBooty();

$body .= $page->formHelper->tag();

$body .= "<div class=\"quotationinput\">\n";
    /*** ID ***/
    if($id > 0) {
        $body .= $theHiddenInput->get("id", $id);
    }
//
    /*** quote ***/
    $attr = new FieldAttributes(true, true);
    $embedder = new FieldEmbedder("Citation");
    $embedder->hasDiv = false;
    $body .= $theTextarea->get("quote", $quote, 7, 70, "Citation", $attr, $embedder);
//
$body .= "</div>\n";
    /*** Author ***/
    $body .= "<div class=\"authorinput\">Author name:</div>\n";
    $body .= $theTextInput->get("authorfirst", $authorfirst, "First");
    $body .= $theTextInput->get("authorlast", $authorlast, "Last");

$body .= $theTextInput->get("place", $place, "Oeuvre");
$body .= $theSelectInput->get("fav", $favs, $fav, "Favorite");

    /*** cats ***/
    $cats = array();
    $values = array();
    foreach($kCategories as $dog) {
        $cats[$dog] = $dog;  // associative array

        if($$dog) {
            $values[] = $dog;
        }
    }
    $body .= $theCheckboxInput->get("cats", $cats, $values, "Categories", true);

// buttons
$cancelUrl = null;
if($id > 0) {
    $cancelUrl = "index.php#c$id";
}
$body .= $page->formHelper->subButt($id > 0, "la citation #$id", $cancelUrl);

echo $body;
?>
