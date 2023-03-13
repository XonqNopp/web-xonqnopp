<?php
/* TODO:
 */
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->NotAllowed();
$page->initDB();
// debug
//$page->initHTML();
//$page->LogLevelUp(6);
// CSS paths
$page->CSS_ppJump();
$page->CSS_ppWing();
$page->js_Form();
// init body
$body = "";

// POST action THEN redirect to nav
if(isset($_POST["erase"])) {
	$navID = $_POST["navID"];

	$sql = $page->DB_IdManage("DELETE FROM `{$page->ddb->DBname}`.`NavWaypoints` WHERE `NavWaypoints`.`NavID` = ?;", $navID);

	$filename = "nav/nav" . sprintf("%06d", $navID);
	if(file_exists("$filename.pdf")) {
		unlink("$filename.pdf");
	}

	$page->HeaderLocation("NavDetails.php?id=$navID");
}


// NO GET redirect to list
if(!isset($_GET["id"])) {
	$page->HeaderLocation("NavList.php");
}


// GET request confirm
$navID = $_GET["id"];
// check ID in DB
$check = $page->DB_IdManage("SELECT COUNT(*) AS `tot` FROM `NavList` WHERE `NavList`.`id` = ?", $navID);
$check->bind_result($tot);
$check->fetch();
$check->close();
if($tot == 0) {
	$page->HeaderLocation("NavList.php");
}

// Prepare nav variables
$filename = "nav/nav" . sprintf("%06d", $navID);
$nav = $page->DB_IdManage("SELECT `name` FROM `NavList` WHERE `NavList`.`id` = ?", $navID);
$nav->bind_result($name);
$nav->fetch();
$nav->close();
$htmlName = preg_replace("/ SKIP/", ",", $name);
$title = "DELETE WP: $htmlName";


// GoHome
$gohome = new stdClass();
$gohome->page = "NavList";
$gohome->rootpage = "index";
$body .= $page->GoHome($gohome);
// Set title and hot booty
$body .= $page->SetTitle($title);// before HotBooty
$page->HotBooty();


// FORM confirm cancel
$body .= "<div>\n";
$body .= $page->FormTag();
$body .= "<p>Are you sure you want to delete all waypoints from the navigation '$htmlName'???</p>\n";
	//// navID
	$args = new stdClass();
	$args->type = "hidden";
	$args->name = "navID";
	$args->value = $navID;
	$body .= $page->FormField($args);
//
	//// buttons
	$args = new stdClass();
	$args->cancelURL = "NavDetails.php?id=$navID";
	$args->CloseTag = true;
	$args->add = "confirm";
	$body .= $page->SubButt(True, $htmlName, $args);
$body .= "</div>\n";


// Finish
echo $body;
unset($page);
?>
