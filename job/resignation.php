<?php
require_once("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->logger->levelUp(6);

$page->cssHelper->dirUpWing();

$body = $page->bodyBuilder->goHome("..", "preparation.php");

$body .= $page->htmlHelper->setTitle("How to write a resignation letter");
$page->htmlHelper->hotBooty();

$body .= "<p>A resignation letter should be a single page and contain the following:</p>\n";
$body .= "<ul>\n";
$body .= "<li>This decision is irrevocable</li>\n";
$body .= "<li>The date of the last day of employment</li>\n";
$body .= "<li>What you enjoyed most at your position</li>\n";
$body .= "<li>Any special needs including days off for the new employer</li>\n";
$body .= "<li>A brief statement that you want to help make the transition as smooth as possible</li>\n";
$body .= "</ul>\n";

echo $body;
?>
