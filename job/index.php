<?php
/*** Created: Mon 2014-08-04 14:13:23 CEST
 ***
 *** TODO:
 ***
 ***/
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->LogLevelUp(6);

$page->CSS_ppJump();
$page->CSS_ppWing();

$body = "";
$args = new stdClass();
$args->page = "..";
$body .= $page->GoHome($args);
$body .= $page->SetTitle("Job stuff");
$page->HotBooty();

$body .= "<div>\n";
$body .= "<ul>\n";
$body .= "<li><a href=\"preparation.php\">Preparation</a></li>\n";
$body .= "<li><a href=\"teaching.php\">Teaching</a></li>\n";
$body .= "<li><a href=\"management.php\">Management</a></li>\n";
$body .= "<li><a href=\"companies/index.php\">Companies</a></li>\n";
$body .= "<li><a href=\"https://www.gate.bfs.admin.ch/salarium/public/index.html#/calculation?regionCode=2&nogaId=26&skillLevelCode=25&mgmtLevelCode=3&weeklyHourValue=42&educationCode=1&ageCode=30&workYearsCode=4&companySizeCode=3&month13SalaryCode=1&specialFeesCode=0&hourSalaryCode=0\" target=\"_blank\">Salarium OFS</a></li>\n";
$body .= "</ul>\n";
if($page->UserIsAdmin()) {
		//// Current goals
		$body .= "<h2>Desired goals</h2>\n";
		$body .= "<p>I would like to work in something involving scientific modelling which leads to a concrete application. My favourite fields for this are aero- and hydrodynamics especially with instabilities and turbulence.</p>\n";
		$body .= "<p>If I could join the tokamak plasma physics research, that would also be an interesting option.</p>\n";
		$body .= "<p>If I could be paid to fly, it is also an option to consider</p>\n";
	//
		//// unwanted career paths
		$body .= "<h2>Unwanted career paths</h2>\n";
		$body .= "<p>I don't want to work for companies that are not compatible with my ethics: tobacco, weapons, alcohol</p>\n";
		$body .= "<p>I do not want to work as a statistician in banks or insurance companies.</p>\n";
	//
		//// long-term objectives
		$body .= "<h2>Long-term objectives</h2>\n";
		$body .= "<p>I hope to change my career path each 10 years:</p>\n";
		$body .= "<ul>\n";
		$body .= "<li>age 35 (2020)</li>\n";
		$body .= "<li>age 45 (2030)</li>\n";
		$body .= "<li>age 55 (2040)</li>\n";
		$body .= "</ul>\n";
	//
		//// consultant
		$body .= "<h2>Consultant</h2>\n";
		$body .= "<p>Brand: &nu;&phi;. Numerical modelisation, physics, plasma physics, hydro- and aerodynamics, optimistation, automation of computer processes with scripts. Need business cards and logo.</p>\n";
	//
}
$body .= "</div>\n";

$page->show($body);
unset($page);
?>
