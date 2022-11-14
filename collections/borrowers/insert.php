<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->loginHelper->notAllowed();
//$page->logger->levelUp(6);

require_once("$funcpath/form_fields.php");
global $theHiddenInput;
global $theTextInput;


$page->bobbyTable->init();

$page->cssHelper->dirUpWing();
$page->htmlHelper->jsForm();

// init vars
$id = 0;
$name = "";


/**
 * Delete a borrower.
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
function deleteBorrower() {
    global $page;

    $howmany = NULL;

    $borrowerId = $_POST["id"];
    $howManyQuery = $page->bobbyTable->idManage("SELECT COUNT(*) AS `how_many` FROM `missings` WHERE `borrower` = ?", $borrowerId);
    $howManyQuery->bind_result($howmany);
    $howManyQuery->fetch();
    $howManyQuery->close();

    if($howmany > 0) {
        $page->logger->error("Cannot delete borrower, still has $howmany item" . ($howmany > 1 ? "s" : ""));
        $_GET["id"] = $borrowerId;
        return $borrowerId;
    }

    $page->bobbyTable->idManage("DELETE FROM `{$page->bobbyTable->dbName}` . `borrowers` WHERE `borrowers` . `id` = ? LIMIT 1;", $borrowerId);
    $page->htmlHelper->headerLocation();

    return $borrowerId;
}


if(isset($_POST["erase"])) {
    $id = deleteBorrower();
}

if(isset($_POST["submit"])) {
    if(isset($_POST["id"])) {
        $id = $_POST["id"];
    }

    $name = $page->dbText->input2sql($_POST["name"]);

    if($name == "") {
        $error = "Cannot use empty string for borrower name";

        if($id > 0) {
            $error .= " for #$id";
            $_GET["id"] = $id;
        }

        $page->logger->error($error);

    } else {
        if($id > 0) {
            $query = $page->bobbyTable->queryPrepare("UPDATE `{$page->bobbyTable->dbName}` . `borrowers` SET `name` = ? WHERE `borrowers` . `id` = ? LIMIT 1;");
            $query->bind_param("si", $name, $id);

        } else {
            $query = $page->bobbyTable->queryPrepare("INSERT INTO `{$page->bobbyTable->dbName}` . `borrowers` (`id`, `name`) VALUES(NULL, ?)");
            $query->bind_param("s", $name);
        }

        $page->bobbyTable->executeManage($query);
        $page->htmlHelper->headerLocation("index.php");
    }
}

$page_title = "Add a new borrower";

$body = $page->bodyBuilder->goHome("..");

if(isset($_GET["id"])) {
    $id = $_GET["id"];
    $find = $page->bobbyTable->idManage("SELECT * FROM `borrowers` WHERE `id` = ?", $id);
    $find->store_result();
    if($find->num_rows == 0) {
        $find->close();
        exit("Error bad id");
    } else {
        $find->bind_result($id, $name);
        $find->fetch();
        $find->close();
        $page_title = "Edit infos for $name";
    }
}

$body .= $page->htmlHelper->setTitle($page_title);
$page->htmlHelper->hotBooty();

$body .= "<div>\n";

$body .= $page->formHelper->tag();

if($id > 0) {
    $body .= $theHiddenInput->get("id", $id);
}

$nameAttr = new FieldAttributes(true, true);
$body .= $theTextInput->get("name", $name, "Name", NULL, $nameAttr);

$body .= $page->formHelper->subButt($id > 0, $name);
$body .= "</div>\n";

echo $body;
?>
