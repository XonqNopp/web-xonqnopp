<?php
require("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->loginHelper->notAllowed();

//$page->htmlHelper->init();
//$page->logger->levelUp(6);

require("$funcpath/form_fields.php");
use HiddenInput;
global $theTextInput;
global $theSelectInput;
global $theCheckboxInput;
global $theTextarea;


$page->dbHelper->init();
$page->cssHelper->dirUpWing();
$page->htmlHelper->jsForm();

$page_title = "Insert a new quote";


require("categories.php");
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
		$quote = $page->dbText->paragraph2SQL($_POST["quote"]);
		$authorlast = $page->dbText->field2SQL($_POST["authorlast"]);
		$authorfirst = $page->dbText->field2SQL($_POST["authorfirst"]);
		$place = $page->dbText->field2SQL($_POST["place"]);
		$fav = $_POST["fav"];
		foreach($kCategories as $dog) {
			if(in_array($dog, $_POST["cats"])) {
				$$dog = 1;
			}
		}
		if($id > 0) {
			$query = "UPDATE `{$page->dbHelper->dbName}` . `quotations` SET `quote` = ?, `authorlast` = ?, `authorfirst` = ?, `place` = ?, `fav` = ?";
			foreach($kCategories as $dog) {
				$query .= ", `$dog` = ?";
			}
			$query .= " WHERE `quotations` . `id` = ? LIMIT 1;";
			$sql = $page->dbHelper->queryPrepare($query);
			$sql->bind_param("ssssiiiiiiiiiiiiiiiiiii", $quote, $authorlast, $authorfirst, $place, $fav, $amour, $argent, $cuisine, $environnement, $EPFL, $humour, $informatique, $litterature, $medecine, $militaire, $musique, $philosophie, $politique, $religions, $sciences, $sexe, $sports, $id);
			$page->dbHelper->executeManage($sql);
		} else {
			$query = "INSERT INTO `{$page->dbHelper->dbName}` . `quotations` (`id`, `quote`, `authorlast`, `authorfirst`, `place`, `fav`";
			$qmarks = "";
			foreach($kCategories as $dog) {
				$query .= ", `$dog`";
				$qmarks .= ", ?";
			}
			$query .= ") VALUES(NULL, ?, ?, ?, ?, ?$qmarks);";
			$sql = $page->dbHelper->queryPrepare($query);
			$sql->bind_param("ssssiiiiiiiiiiiiiiiiii", $quote, $authorlast, $authorfirst, $place, $fav, $amour, $argent, $cuisine, $environnement, $EPFL, $humour, $informatique, $litterature, $medecine, $militaire, $musique, $philosophie, $politique, $religions, $sciences, $sexe, $sports);
			$page->dbHelper->executeManage($sql);
			$id = $sql->insert_id;
		}
		$page->htmlHelper->headerLocation("index.php#c$id");
	} elseif(isset($_POST["erase"])) {
		$id = $_POST["id"];
		$query = "DELETE FROM `{$page->dbHelper->dbName}` . `quotations` WHERE `quotations` . `id` = ? LIMIT 1;";
		$sql = $page->dbHelper->queryPrepare($query);
		$sql->bind_param("i", $id);
		$page->dbHelper->executeManage($sql);
		$page->htmlHelper->headerLocation();
		exit;
	}
//
if(isset($_GET["id"])) {
	$id = $_GET["id"];
	$query = "SELECT * FROM `quotations` WHERE `id` = ? LIMIT 1;";
	$result = $page->dbHelper->idManage($query, $id);
	$result->store_result();
	if($result->num_rows == 0) {
		$result->close();
		exit("bad id");
	}
	$result->bind_result($id, $quote, $authorlast, $authorfirst, $place, $fav, $amour, $argent, $cuisine, $environnement, $EPFL, $humour, $informatique, $litterature, $medecine, $militaire, $musique, $philosophie, $politique, $religions, $sciences, $sexe, $sports);
	$result->fetch();
	$result->close();
	$quote = $page->dbText->sql2paragraph($quote);
	$authorfirst = $page->dbText->sql2field($authorfirst);
	$authorlast = $page->dbText->sql2field($authorlast);
	$place = $page->dbText->sql2field($place);
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
$body = $page->bodyHelper->goHome("..", $goUp);

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
	$embedder->bDiv = false;
	$body .= $theTextarea->get("quote", $quote, 7, 70, "Citation", $attr, $embedder);
//
$body .= "</div>\n";
	/*** Author ***/
	$body .= "<div class=\"authorinput\">Author:</div>\n";
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
unset($page);
?>
