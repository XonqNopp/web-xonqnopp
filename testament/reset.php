<?php
require("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->logger->levelUp(6);
$page->loginHelper->loginHelper->notAllowed("../index.php");
$page->dbHelper->init();

$today = $page->timeHelper->getNow();

$next = clone($today);
$next->day = 1;
$next->month += 2;
if($next->month > 12) {
	$next->month = 1;
	$next->year++;
}

$tomorrow = clone($today);
$tomorrow->day++;
if($tomorrow->day > 28) {
	$tomorrow->day -= 28;
	$tomorrow->month++;
	if($tomorrow->month > 12) {
		$tomorrow->month = 1;
		$tomorrow->year++;
	}
}


if(isset($_POST["go"])) {
	/*** Reset ***/
	if($_POST["pawo"] == $page->miscInit->testament) {
		$dt = new stdClass();
		$dt->year  = $_POST["ownyear"];
		$dt->month = $_POST["ownmonth"];
		$dt->day   = $_POST["ownday"];
		$newdate = $page->timeHelper->obj2date($dt)->date;
		$query  = "UPDATE `{$page->dbHelper->dbName}` . `testament` ";
		$query .= "SET `duedate` = ? ";
		$query .= "WHERE `testament` . `id` = 1 ";
		$query .= "LIMIT 1";
		$q = $page->dbHelper->queryPrepare($query);
		$q->bind_param("s", $newdate);
		$page->dbHelper->executemanage($q);
	}
} elseif(isset($_POST["NOW"])) {
	/*** EMERGENCY SET TO TOMORROW ***/
	if($_POST["pawo"] == $page->miscInit->testament) {
		$newdate = $page->timeHelper->obj2date($tomorrow)->date;
		$query  = "UPDATE `{$page->dbHelper->dbName}` . `testament` ";
		$query .= "SET `duedate` = ? ";
		$query .= "WHERE `testament` . `id` = 1 ";
		$query .= "LIMIT 1";
		$q = $page->dbHelper->queryPrepare($query);
		$q->bind_param("s", $newdate);
		$page->dbHelper->executemanage($q);
	}
}


$body = $page->bodyHelper->goHome("..");

$body .= $page->htmlHelper->setTitle("Unauthorized page: please go back!");
$page->htmlHelper->hotBooty();

$body .= $page->formHelper->tag();
$body .= "<div id=\"testament_body\">\n";

$check = $page->dbHelper->querymanage("SELECT * FROM `{$page->dbHelper->dbName}` . `testament` WHERE `testament` . `id` = 1 LIMIT 1" );

if($check->num_rows == 0) {
	$body .= "Sorry, there has been a problem in the DB, you should check it right now!";
}

$due = $check->fetch_object();
$check->close();
$date = $page->timeHelper->str2date($due->duedate);
$body .= "<div id=\"testament_date\">Due date: {$date->day}.{$date->month}.{$date->year}</div>\n";

$body .= "<div id=\"testament_next\">Do you want to set it to:\n";
$body .= "<input type=\"text\" maxlength=\"4\" size=\"3\" name=\"ownyear\" value=\"{$next->year}\" />\n";
$body .= "<input type=\"text\" maxlength=\"2\" size=\"1\" name=\"ownmonth\" value=\"{$next->month}\" />\n";
$body .= "<input type=\"text\" maxlength=\"2\" size=\"1\" name=\"ownday\" value=\"{$next->day}\" />\n";
$body .= "</div>\n";

$body .= "<div id=\"testament_password\">\n";
$body .= "Enter password:&nbsp;<input type=\"password\" name=\"pawo\" />\n";
$body .= "</div>\n";

$body .= "<div id=\"testament_button\">\n";
$body .= "<input type=\"submit\" name=\"go\" value=\"YES\" />\n";
$body .= "<input type=\"submit\" name=\"NOW\" value=\"EMERGENCY\" />\n";
$body .= "</div>\n";

$body .= "</div>\n";
$body .= "</form>\n";

echo $body;
unset($page);
?>
