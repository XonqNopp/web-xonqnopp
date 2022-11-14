<?php
require_once("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->logger->levelUp(6);

$page->cssHelper->dirUpWing();

$body = $page->bodyBuilder->goHome(NULL, "..");

$body .= $page->htmlHelper->setTitle("Job stuff");
$page->htmlHelper->hotBooty();

$body .= "<div>\n";
$body .= "<ul>\n";
$body .= $page->bodyBuilder->liAnchor("preparation.php", "Preparation");
$body .= $page->bodyBuilder->liAnchor("teaching.php", "Teaching");
$body .= $page->bodyBuilder->liAnchor("management.php", "Management");
$body .= $page->bodyBuilder->liAnchor("companies/index.php", "Companies");
$body .= $page->bodyBuilder->liAnchor(
    "https://www.gate.bfs.admin.ch/salarium/public/index.html#/calculation?regionCode=2&nogaId=26&skillLevelCode=25&mgmtLevelCode=3&weeklyHourValue=42&educationCode=1&ageCode=30&workYearsCode=4&companySizeCode=3&month13SalaryCode=1&specialFeesCode=0&hourSalaryCode=0",
    "Salarium OFS"
);

if($page->loginHelper->userIsAdmin()) {
    $body .= $page->bodyBuilder->liAnchor("applications", "Submitted applications");
}
$body .= "</ul>\n";
$body .= "</div>\n";

echo $body;
?>
