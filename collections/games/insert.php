<?php
/*** Created: Mon 2015-07-20 13:53:42 CEST
 * TODO:
 */
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->NotAllowed();
$page->initDB();
//// debug
//$page->initHTML();
//$page->LogLevelUp(6);
//// CSS paths
$page->CSS_ppJump(2);
$page->CSS_ppWing(2);
$page->js_Form();
//// init body
$body = "";

$page_title = "Insert new game";

$id = 0;
$name = "";
$minP = 0;
$maxP = 0;
$age = 0;
$comment = "";

if(isset($_POST["erase"])) {
	//// delete entry
	$id = $_POST["id"];
	$sql = $page->DB_IdManage("DELETE FROM `{$page->ddb->DBname}`.`games` WHERE `games`.`id` = ? LIMIT 1;", $id);
	$page->HeaderLocation();
} elseif(isset($_POST["submit"])) {
	//// DB handling
	$name = $page->field2SQL($_POST["name"]);
	$minP = $page->field2SQL($_POST["minP"]);
	$maxP = $page->field2SQL($_POST["maxP"]);
	$age = $page->field2SQL($_POST["age"]);
	$comment = $page->field2SQL($_POST["comment"]);
	if(isset($_POST["id"])) {
		//// update
		$id = $_POST["id"];
		$query = "UPDATE `{$page->ddb->DBname}`.`games` SET ";
		$query .= "`name` = ?, `minP` = ?, `maxP` = ?, `age` = ?, `comment` = ?";
		$query .= " WHERE `games`.`id` = ? LIMIT 1;";
		$sql = $page->DB_QueryPrepare($query);
		$sql->bind_param("siiisi", $name, $minP, $maxP, $age, $comment, $id);
		$page->DB_ExecuteManage($sql);
	} else {
		//// insert
		$query = "INSERT INTO `{$page->ddb->DBname}`.`games` (`name`, `minP`, `maxP`, `age`, `comment`) VALUES(?, ?, ?, ?, ?)";
		$sql = $page->DB_QueryPrepare($query);
		$sql->bind_param("siiis", $name, $minP, $maxP, $age, $comment);
		$page->DB_ExecuteManage($sql);
		$id = $sql->insert_id;
	}
	$page->HeaderLocation("index.php#s$id");
	$page_title = "Edit game $name";
} elseif(isset($_GET["id"])) {
	//// get data for display
	$id = $_GET["id"];
	$sql = $page->DB_SelectId("games", $id);
	$sql->bind_result($id, $name, $minP, $maxP, $age, $borrowed, $comment);
	$sql->fetch();
	$sql->close();
	$name = $page->SQL2field($name);
	$comment = $page->SQL2field($comment);
	$page_title = "Edit game $name";
}

//// GoHome
$gohome = new stdClass();
$body .= $page->GoHome($gohome);
//// Set title and hot booty
$body .= $page->SetTitle($page_title);// before HotBooty
$page->HotBooty();
//
	//// form
	$body .= "<div>\n";
	$body .= $page->FormTag();
	//
		//// fields
			//// id
			$args = new stdClass();
			$args->type = "hidden";
			$args->name = "id";
			$args->value = $id;
			if($id > 0) {
				$body .= $page->FormField($args);
			}
		//
			//// name
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Name";
			$args->name = "name";
			$args->value = $name;
			$args->required = true;
			$args->autofocus = true;
			$body .= $page->FormField($args);
		//
			//// minP
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Minimum players";
			$args->name = "minP";
			$args->value = $minP;
			$args->min = 0;
			$body .= $page->FormField($args);
		//
			//// maxP
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Maximum players";
			$args->name = "maxP";
			$args->value = $maxP;
			$args->min = 0;
			$body .= $page->FormField($args);
		//
			//// age
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Age required";
			$args->name = "age";
			$args->value = $age;
			$args->min = 0;
			$body .= $page->FormField($args);
		//
			//// comment
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Comment";
			$args->name = "comment";
			$args->value = $comment;
			$body .= $page->FormField($args);
		//
	//
		//// buttons
		$args = new stdClass();
		$args->CloseTag = true;
		$body .= $page->SubButt($id > 0, $name, $args);
	//
	$body .= "</div>\n";
//


//// Finish
echo $body;
unset($page);
?>
