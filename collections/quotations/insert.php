<?php
/*** Created: Thu 2014-08-07 15:05:59 CEST
 ***
 *** TODO:
 ***
 ***/
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->NotAllowed();

//$page->initHTML();
//$page->LogLevelUp(6);

$page->initDB();
$page->CSS_ppJump(2);
$page->CSS_ppWing();
$page->js_Form();
$page_title = "Insert a new quote";
$body = "";

require("categories.php");
$cats = GetCats();

$id = 0;
$quote = "";
$authorfirst = "";
$authorlast = "";
$place = "";
$favs = array("no", "yes");
$fav = 0;
foreach($cats as $dog) {
	eval("$$dog = 0;");
}


	/*** DB ***/
	if(isset($_POST["submit"])) {
		if(isset($_POST["id"])) {
			$id = $_POST["id"];
		}
		$quote = $page->paragraph2SQL($_POST["quote"]);
		$authorlast = $page->field2SQL($_POST["authorlast"]);
		$authorfirst = $page->field2SQL($_POST["authorfirst"]);
		$place = $page->field2SQL($_POST["place"]);
		$fav = $_POST["fav"];
		foreach($cats as $dog) {
			if(in_array($dog, $_POST["cats"])) {
				$$dog = 1;
			}
		}
		if($id > 0) {
			$query = "UPDATE `" . $page->ddb->DBname . "` . `quotations` SET `quote` = ?, `authorlast` = ?, `authorfirst` = ?, `place` = ?, `fav` = ?";
			foreach($cats as $dog) {
				$query .= ", `$dog` = ?";
			}
			$query .= " WHERE `quotations` . `id` = ? LIMIT 1;";
			$sql = $page->DB_QueryPrepare($query);
			$sql->bind_param("ssssiiiiiiiiiiiiiiiiiii", $quote, $authorlast, $authorfirst, $place, $fav, $amour, $argent, $cuisine, $environnement, $EPFL, $humour, $informatique, $litterature, $medecine, $militaire, $musique, $philosophie, $politique, $religions, $sciences, $sexe, $sports, $id);
			$page->DB_ExecuteManage($sql);
		} else {
			$query = "INSERT INTO `" . $page->ddb->DBname . "` . `quotations` (`id`, `quote`, `authorlast`, `authorfirst`, `place`, `fav`";
			$qmarks = "";
			foreach($cats as $dog) {
				$query .= ", `$dog`";
				$qmarks .= ", ?";
			}
			$query .= ") VALUES(NULL, ?, ?, ?, ?, ?$qmarks);";
			$sql = $page->DB_QueryPrepare($query);
			$sql->bind_param("ssssiiiiiiiiiiiiiiiiii", $quote, $authorlast, $authorfirst, $place, $fav, $amour, $argent, $cuisine, $environnement, $EPFL, $humour, $informatique, $litterature, $medecine, $militaire, $musique, $philosophie, $politique, $religions, $sciences, $sexe, $sports);
			$page->DB_ExecuteManage($sql);
			$id = $sql->insert_id;
		}
		$page->HeaderLocation("index.php#c$id");
	} elseif(isset($_POST["erase"])) {
		$id = $_POST["id"];
		$query = "DELETE FROM `" . $page->ddb->DBname . "` . `quotations` WHERE `quotations` . `id` = ? LIMIT 1;";
		$sql = $page->DB_QueryPrepare($query);
		$sql->bind_param("i", $id);
		$page->DB_ExecuteManage($sql);
		$page->HeaderLocation();
		exit;
	}
//
if(isset($_GET["id"])) {
	$id = $_GET["id"];
	$query = "SELECT * FROM `quotations` WHERE `id` = ? LIMIT 1;";
	$result = $page->DB_IdManage($query, $id);
	$result->store_result();
	if($result->num_rows == 0) {
		$result->close();
		exit("bad id");
	}
	$result->bind_result($id, $quote, $authorlast, $authorfirst, $place, $fav, $amour, $argent, $cuisine, $environnement, $EPFL, $humour, $informatique, $litterature, $medecine, $militaire, $musique, $philosophie, $politique, $religions, $sciences, $sexe, $sports);
	$result->fetch();
	$result->close();
	$quote = $page->SQL2paragraph($quote);
	$authorfirst = $page->SQL2field($authorfirst);
	$authorlast = $page->SQL2field($authorlast);
	$place = $page->SQL2field($place);
	//foreach($cats as $dog) {
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

$args = new stdClass();
if($id > 0) {
	$args->id = $id;
}
$args->rootpage = "..";
$body .= $page->GoHome($args);

$body .= $page->SetTitle($page_title);
$page->HotBooty();

$body .= $page->FormTag();

$body .= "<div class=\"quotationinput\">\n";
	/*** ID ***/
	if($id > 0) {
		$args = new stdClass();
		$args->type = "hidden";
		$args->name = "id";
		$args->value = $id;
		$body .= $page->FormField($args);
	}
//
	/*** quote ***/
	$args = new stdClass();
	$args->type = "textarea";
	$args->required = true;
	$args->autofocus = true;
	$args->rows =  7;
	$args->cols = 70;
	$args->title = "Citation";
	$args->name = "quote";
	$args->value = $quote;
	$args->div = false;
	$body .= $page->FormField($args);
//
$body .= "</div>\n";
	/*** Author ***/
	$body .= "<div class=\"authorinput\">Author:</div>\n";
		/*** First name ***/
		$args = new stdClass();
		$args->type = "text";
		$args->title = "First";
		$args->name = "authorfirst";
		$args->value = $authorfirst;
		$args->css = "authorinput_first";
		$args->colon = false;
		$body .= $page->FormField($args);
	//
		/*** Last name ***/
		$args->title = "Last";
		$args->name = "authorlast";
		$args->value = $authorlast;
		$args->css = "authorinput_last";
		$body .= $page->FormField($args);
	//
//
	/*** Place ***/
	$args->title = "Oeuvre";
	$args->name = "place";
	$args->value = $place;
	$args->colon = true;
	$args->css = "placeinput";
	$body .= $page->FormField($args);
//
	/*** fav ***/
	$args = new stdClass();
	$args->title = "Favorite";
	$args->name = "fav";
	$args->type = "select";
	$args->list = $favs;
	$args->value = $favs[$fav];
	$args->css = "favinput";
	//$args->vlist = true;
	$body .= $page->FormField($args);
//
	/*** cats ***/
	$args = new stdClass();
	$args->title = "Categories";
	$args->name = "cats";
	$args->type = "checkbox";
	$args->css = "catsinput";
	$args->list = array();
	$args->value = array();
	foreach($cats as $dog) {
		$args->list[$dog] = $dog;
		if($$dog) {
			$args->value[] = $dog;
		}
	}
	$args->vlist = true;
	$body .= $page->FormField($args);
//
// buttons
$args = new stdClass();
$args->CloseTag = true;
if($id > 0) {
	$args->cancelURL = "index.php#c$id";
}
$body .= $page->SubButt($id > 0, "la citation #$id", $args);

$page->show($body);
unset($page);
?>
