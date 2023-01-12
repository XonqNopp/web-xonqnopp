<?php
require("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->logger->levelUp(6);

$page->cssHelper->dirUpWing();

$body = $page->bodyHelper->goHome(NULL, "..");

$body .= $page->htmlHelper->setTitle("Job stuff");
$page->htmlHelper->hotBooty();

$body .= "<div>\n";
$body .= "<ul>\n";
$body .= "<li><a href=\"preparation.php\">Preparation</a></li>\n";
$body .= "<li><a href=\"teaching.php\">Teaching</a></li>\n";
$body .= "<li><a href=\"management.php\">Management</a></li>\n";
$body .= "<li><a href=\"companies/index.php\">Companies</a></li>\n";
$body .= "<li><a href=\"https://www.gate.bfs.admin.ch/salarium/public/index.html#/calculation?regionCode=2&nogaId=26&skillLevelCode=25&mgmtLevelCode=3&weeklyHourValue=42&educationCode=1&ageCode=30&workYearsCode=4&companySizeCode=3&month13SalaryCode=1&specialFeesCode=0&hourSalaryCode=0\" target=\"_blank\">Salarium OFS</a></li>\n";
if($page->loginHelper->userIsAdmin()) {
	$body .= "<li><a href=\"applications\">Submitted applications</a></li>\n";
}
$body .= "</ul>\n";
$body .= "</div>\n";


$body .= "<div>\n";
if($page->loginHelper->userIsAdmin()) {
		//// Current goals
		$body .= "<h2>Desired goals</h2>\n";
		$body .= "<p>I would like to work in something involving computers which leads to a concrete application.\n";
		$body .= "My favourite fields for this are aero- and hydrodynamics especially with instabilities and turbulence.</p>\n";
		$body .= "<p>If I could join the tokamak plasma physics research, that would also be an interesting option.</p>\n";
		$body .= "<p>If I could be paid to fly, it is also an option to consider</p>\n";
	//
		//// long-term objectives
		$body .= "<h2>Long-term objectives</h2>\n";
		$body .= "<p>I hope to change my career path each 10 years:</p>\n";
		$body .= "<ul>\n";
		$body .= "<li>age 35 (2020)</li>\n";
		$body .= "<li>age 45 (2030)</li>\n";
		$body .= "<li>age 55 (2040)</li>\n";
		$body .= "</ul>\n";
		$body .= "<p>2020 status: at my 4th different job within 3 companies.</p>\n";
	//
}
$body .= "</div>\n";

echo $body;
unset($page);
?>
