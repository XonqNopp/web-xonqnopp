<?php
require_once("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->logger->levelUp(6);
$page->loginHelper->notAllowed("../index.php");
$page->bobbyTable->init();

$today = $page->timeHelper->getNow();

$next = clone($today);
$next->day = 1;
$next->month += 2;
if($next->month > 12) {
    $next->month = 1;
    $next->year++;
}

if(isset($_POST["pawo"]) && $_POST["pawo"] == $page->miscInit->testament) {
    $newDate = NULL;

    if(isset($_POST["go"])) {
        // Reset
        $newDate = new stdClass();
        $newDate->year  = $_POST["ownyear"];
        $newDate->month = $_POST["ownmonth"];
        $newDate->day   = $_POST["ownday"];
    } elseif(isset($_POST["NOW"])) {
        // EMERGENCY SET TO TOMORROW
        $newDate = clone($today);
        $newDate->day++;
        if($newDate->day > 28) {
            $newDate->day -= 28;
            $newDate->month++;
            if($newDate->month > 12) {
                $newDate->month = 1;
                $newDate->year++;
            }
        }
    }

    if($newDate !== NULL) {
        $query  = "UPDATE `{$page->bobbyTable->dbName}` . `testament` SET `duedate` = ? WHERE `testament` . `id` = 1 LIMIT 1";
        $q = $page->bobbyTable->queryPrepare($query);
        $q->bind_param("s", $page->timeHelper->obj2date($newDate)->date);
        $page->bobbyTable->executemanage($q);
    }
}


$body = $page->bodyBuilder->goHome("..");

$body .= $page->htmlHelper->setTitle("Unauthorized page: please go back!");
$page->htmlHelper->hotBooty();

$body .= $page->formHelper->tag();
$body .= "<div id=\"testament_body\">\n";

$check = $page->bobbyTable->querymanage("SELECT * FROM `{$page->bobbyTable->dbName}` . `testament` WHERE `testament` . `id` = 1 LIMIT 1" );

if($check->num_rows == 0) {
    $body .= "Sorry, there has been a problem in the DB, you should check it right now!";
}

$due = $check->fetch_object();
$check->close();
$date = $page->timeHelper->str2date($due->duedate);
$body .= "<div id=\"testament_date\">Due date: {$date->day}.{$date->month}.{$date->year}</div>\n";

$body .= "<div id=\"testament_next\">Do you want to set it to:\n";
$body .= "<input type=\"text\" maxlength=\"4\" size=\"3\" name=\"ownyear\" value=\"{$next->year}\">\n";
$body .= "<input type=\"text\" maxlength=\"2\" size=\"1\" name=\"ownmonth\" value=\"{$next->month}\">\n";
$body .= "<input type=\"text\" maxlength=\"2\" size=\"1\" name=\"ownday\" value=\"{$next->day}\">\n";
$body .= "</div>\n";

$body .= "<div id=\"testament_password\">\n";
$body .= "Enter password:&nbsp;<input type=\"password\" name=\"pawo\">\n";
$body .= "</div>\n";

$body .= "<div id=\"testament_button\">\n";
$body .= "<input type=\"submit\" name=\"go\" value=\"YES\">\n";
$body .= "<input type=\"submit\" name=\"NOW\" value=\"EMERGENCY\">\n";
$body .= "</div>\n";

$body .= "</div>\n";
$body .= "</form>\n";

echo $body;
?>
