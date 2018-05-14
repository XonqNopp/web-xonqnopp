<?php
/*** Created: Wed 2014-08-13 11:46:15 CEST
 ***
 *** TODO:
 * redo this page with goal at 42h/w and 5w holidays
 * then compute 1w holliday, 1h/w, -10%/w
 * keep from-to, but default from==to (change link in other page)
 ***
 ***/
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);

$hoursweek = 42;
$salarymonths = 13;
$holidays = 4;
//$from =  75;
//$to   = 100;
if(!isset($_GET["from"]) || !isset($_GET["to"])) {
	exit;
}
$from = $_GET["from"];
$to   = $_GET["to"];
if($from == 0 || $to == 0) {
	exit;
}
$step =   2;

$page->CSS_ppJump();
$page->CSS_ppWing();

$body = "";
$args = new stdClass();
$args->rootpage = "..";
$body .= $page->GoHome($args);
$body .= $page->SetTitle("Sample of salary");
$page->HotBooty();

$body .= "<div>\n";
$body .= "<ul>\n";
$body .= "<li>raw</li>\n";
$body .= "<li>hours per week: $hoursweek</li>\n";
$body .= "<li>weeks of holidays: $holidays</li>\n";
$body .= "<li>number of salaries per year: $salarymonths</li>\n";
$body .= "</ul>\n";
$body .= "</div>\n";
$body .= "<div class=\"sample_table\">\n";
$body .= "<table>\n";
$body .= "<tr>\n";
$body .= "<th>year</th>\n";
$body .= "<th>month</th>\n";
$body .= "<th>week</th>\n";
$body .= "</tr>\n";

$onetwo = 0;
$bronx  = "";
for($i = $from; $i <= $to; $i += $step) {
	$onetwo++;
	$year  = $i * 1000;
	$month = round($year / 13);
	//$week  = round($year / 48);
	$week  = round($month / 4);
	if($onetwo % 2) {
		$bronx = "odd";
	} else {
		$bronx = "even";
	}
	$body .= "<tr class=\"$bronx\">\n";
	$body .= "<td>$year.-</td>\n";
	$body .= "<td>$month.-</td>\n";
	$body .= "<td>$week.-</td>\n";
	$body .= "</tr>\n";
}
$body .= "</table>\n";
$body .= "</div>\n";

$page->show($body);
unset($page);
?>
