<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require_once("common.php");
$page = new PhPage($rootPath);

require_once("$funcpath/form_fields.php");
global $theHiddenInput;


$page->loginHelper->notAllowed();

$page->bobbyTable->init();

// debug
//$page->htmlHelper->init();
//$page->logger->levelUp(6);
// CSS paths
$page->cssHelper->dirUpWing();
$page->htmlHelper->jsForm();



// POST action THEN redirect to nav
if(isset($_POST["erase"])) {
    $navID = $_POST["navID"];

    $page->bobbyTable->queryDelete("NavWaypoints", $navID, "NavID", 100);

    deleteNavPdfFile($navID);

    $page->htmlHelper->headerLocation("display.php?id=$navID");
}


// NO GET redirect to list
if(!isset($_GET["id"])) {
    $page->htmlHelper->headerLocation();
}


// GET request confirm
$navID = $_GET["id"];
// check ID in DB
$check = $page->bobbyTable->idManage("SELECT COUNT(*) AS `tot` FROM `NavList` WHERE `NavList`.`id` = ?", $navID);
$check->bind_result($tot);
$check->fetch();
$check->close();
if($tot == 0) {
    $page->htmlHelper->headerLocation();
}

// Prepare nav variables
//$filename = getNavFilename($navID);
$nav = $page->bobbyTable->idManage("SELECT `name` FROM `NavList` WHERE `NavList`.`id` = ?", $navID);
$nav->bind_result($htmlName);
$nav->fetch();
$nav->close();
$title = "DELETE WP: $htmlName";


$body = $page->bodyBuilder->goHome("..");

// Set title and hot booty
$body .= $page->htmlHelper->setTitle($title);// before HotBooty
$page->htmlHelper->hotBooty();


// FORM confirm cancel
$body .= "<div>\n";
$body .= $page->formHelper->tag();
$body .= "<p>Are you sure you want to delete all waypoints from the navigation '$htmlName'???</p>\n";
$body .= $theHiddenInput->get("navID", $navID);
$body .= $page->formHelper->subButt(True, $htmlName, "display.php?id=$navID", true, "confirm");
$body .= "</div>\n";


echo $body;
?>
