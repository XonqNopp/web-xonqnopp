<?php
/*** Created: Tue 2014-08-05 16:43:49 CEST
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
$args->rootpage = "..";
$body .= $page->GoHome($args);
$body .= $page->SetTitle("Useful stuff before changing of position");
$page->HotBooty();

$body .= "<div><ul>\n";
$body .= "<li><a href=\"questions.php\">Interview questions</a></li>\n";
$body .= "<li><a href=\"resignation.php\">How to write a resignation letter</a></li>\n";
$body .= "<li><a href=\"interview.php\">Stuff to have ready at the interview</a></li>\n";
$body .= "<li><a href=\"sample.php?from=85&amp;to=105\">Sample of salary</a></li>\n";
$body .= "</ul></div>\n";
$body .= "<p>\n";
$body .= "<a href=\"https://medium.com/@GarinEtch/how-i-landed-5-dream-jobs-in-one-month-by-giving-away-my-best-ideas-486a35f00ead\">send your ideas to people</a>\n";
$body .= "</p>\n";

$body .= "<p>\n";
$body .= "<a href=\"http://www.travailler-en-suisse.ch/emploi-suisse/offres-emploi-suisse/grandes-entreprises-multinationales\">travailler en Suisse</a>\n";
$body .= "</p>\n";

$body .= "<div>According to career center at EPFL:\n";
$body .= "<ul>\n";
$body .= "<li>Best way to get a job in Switzerland is answering job offers, then unsollicited applications, then knowing the right person.</li>\n";
$body .= "<li>Of all the applications you send, 40% will be completely useless because opening was filled, postponed, cancelled or whatever.</li>\n";
$body .= "<li>After one month without hearing from an application you send, consider it finished and you are out.</li>\n";
$body .= "<li>If you see a new job offer who is exactly like another one you answered at least one month ago\n";
$body .= "without getting any answer or being said the job is not opened anymore but will soon, you should reapply.\n";
$body .= "They also consider that after a month your situation has completely changed and the information you provided\n";
$body .= "are out of date and maybe you got another job.<?li>\n";
$body .= "<li>To send a message on LinkedIn to a person not in your network (e.g. to ask for advice about a job opening in his team),\n";
$body .= "check out his profile and list his groups. Then find a group where you can freely enroll. Once in the same group, you can send him a message.</li>\n";
$body .= "<li>Cover letter structure: 1/6 where you explain how you link to the company, 1/3 where you state your motivation\n";
$body .= "for the company (or department, lab... The smallest unit) and the opening (except for unsollicited applications),\n";
$body .= "explaining you want to do your job well. Speak about competitors. Ask questions.\n";
$body .= "1/2 about you and them, stating what are your plans once hired.</li>\n";
$body .= "<li>On your resume, put at the beginning 3 key points you want the recruiter to remember about you. These and your technical skills should be adapted for each application you send.</li>\n";
$body .= "</ul>\n";
$body .= "</div>\n";

$page->show($body);
unset($page);
?>
