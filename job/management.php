<?php
require_once("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->logger->levelUp(6);

$page->cssHelper->dirUpWing();

$body = $page->bodyBuilder->goHome("..");

$body .= $page->htmlHelper->setTitle("Management");
$page->htmlHelper->hotBooty();

$body .= "<p>work 60% on regular tasks, 20% to help others and 20% on free investigations</p>\n";

$body .= "<p>have at least one holiday per month, one holiweek per semester, maximum free, birthday free</p>\n";

$body .= "<p>be a leader, not a boss</p>\n";

$body .= "<p>have at least one formation per year</p>\n";

$body .= "<p>When someone quits, make his last day in the company a special day to thank him for his contributions.</p>\n";

$body .= "<p>Make 4x/year departement meetings where everyone presents what he's done during the last 3 months to the others and to the board of bosses.</p>\n";

$body .= "<div>\n";
$body .= "<p>Each generation has its concerns. ";
$body .= $page->bodyBuilder->anchor("http://talent.linkedin.com/blog/index.php/2015/03/how-to-keep-millennials-from-quitting", "How to Keep Millennials from Quitting");
$body .= "</div>\n";

$body .= "<div>\n";
$body .= "<p>";
$body .= $page->bodyBuilder->anchor("http://www.virgin.com/entrepreneur/richard-branson-launching-a-business-do-these-three-things-or-fail", "Read Richard Branson");
$body .= ":</p>\n";
$body .= "<ol>\n";
$body .= "<li>Have some fun with your customers</li>\n";
$body .= "<li>Differentiate yourselves from the competition</li>\n";
$body .= "<li>Nurture your employees</li>\n";
$body .= "</ol>\n";
$body .= "</div>\n";
$body .= "<div>\n";

$body .= "<h2>Act Like A Business Owner To Advance Your Career</h2>\n";
$body .= "<p>";
$body .= $page->bodyBuilder->anchor("http://www.careerealism.com/business-owner-advance-career/");
$body .= "</p>\n";
$body .= "<ul>\n";
$body .= "<li>Show Passion And Enthusiasm</li>\n";
$body .= "<li>Treat Your Customers And Clients Like Gold</li>\n";
$body .= "<li>Take Care Of Your People</li>\n";
$body .= "<li>Deliver More Than Expected</li>\n";
$body .= "<li>Roll Up Your Sleeves Whenever Necessary</li>\n";
$body .= "<li>Get Better And Better&nbsp;-&nbsp;Continuously Improve</li>\n";
$body .= "<li>Systematize Things</li>\n";
$body .= "<li>Focus On Cash Flow And Spend Like It's Your Money</li>\n";
$body .= "<li>Think Ahead, See The Big Picture</li>\n";
$body .= "</ul>\n";
$body .= "</div>\n";

echo $body;
?>
