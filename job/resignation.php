<?php
/*** Created: Wed 2014-08-06 13:51:26 CEST
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
$args->page = "preparation";
$args->rootpage = "..";
$body .= $page->GoHome($args);
$body .= $page->SetTitle("How to write a resignation letter");
$page->HotBooty();

$body .= "<p>A resignation letter should be a single page and contain the following:</p>\n";
$body .= "<ul>\n";
$body .= "<li>This decision is irrevocable</li>\n";
$body .= "<li>The date of the last day of employment</li>\n";
$body .= "<li>What you enjoyed most at your position</li>\n";
$body .= "<li>Any special needs including days off for the new employer</li>\n";
$body .= "<li>A brief statement that you want to help make the transition as smooth as possible</li>\n";
$body .= "</ul>\n";

$page->show($body);
unset($page);
?>
