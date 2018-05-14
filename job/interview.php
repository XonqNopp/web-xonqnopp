<?php
/*** Created: Mon 2014-10-27 20:57:40 CET
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
$gohome = new stdClass();
$gohome->page = "preparation";
$gohome->rootpage = "..";
$body .= $page->GoHome($gohome);
$body .= $page->SetTitle("Stuff to have ready at interview");
$page->HotBooty();

$body .= "<ul>\n";
$body .= "<li>What I do in my current position...</li>\n";
//$body .= "<li>evolution politics?</li>\n";
//$body .= "<li>continuous formation?</li>\n";
$body .= "<li>explain a typical task for this position?</li>\n";
$body .= "<li>If asked how competent I am for a given skill, ask them how I could illustrate it in their environment</li>\n";
$body .= "</ul>\n";
//Once hired, ask for the cahier des charges and for the position description
//(description de poste).

$body .= "<h2>Salary</h2>\n";
$body .= "<ul>\n";
$body .= "<li>Ask how much holidays</li>\n";
$body .= "<li>Compute how much is a month and a week</li>\n";
$body .= "<li>Speak about gross (brut) annual 42h-week 4-week-holidays salary</li>\n";
//$body .= "<li>Ask if working time is flexible or fixed</li>\n";
//$body .= "<li>Ask for 90% or 80% with flexible schedule</li>\n";
//$body .= "<li>Ask if holidays are free or some are imposed</li>\n";
$body .= "</ul>\n";
//Do not bring this topic in first meeting. If the guy does, be prepared to
//answer. Salarium@OFS helps (not up to date). Before coming to second interview,
//tell about target (if right person on the phone). When they make their offer
//(generally lower), it is time to negotiate speaking about bonus or holidays.

	$body .= "<h2>Questions to ask</h2>\n";
	$body .= "<div>\n";
	$body .= "<ul>\n";
	$body .= "<li>Pourquoi ce poste est-il vacant?</li>\n";
	$body .= "<li>Qu'est devenue la personne qui occupait cette place?</li>\n";
	$body .= "<li>Comment se d&eacute;roule une journ&eacute;e typique pour ce poste?</li>\n";
	$body .= "<li>Comment d&eacute;crivez-vous la culture de cette entreprise?</li>\n";
	$body .= "<li>Quels sont les buts de cette compagnie dans les 5 prochaines ann&eacute;es? Comment se placent cette position et ce d&eacute;partement dans ces buts?</li>\n";
	$body .= "<li>Est-ce que vous aimez travailler ici?</li>\n";
	$body .= "<li>Quel genre de probl&egrave;me aura &agrave; r&eacute;soudre la personne &agrave; ce poste?</li>\n";
	$body .= "<li>What is the set of problems that will be resolved 90 days after your new hire comes on board?</li>\n";
	$body .= "<li>Who are the most important internal and external customers for this role? What do each of them look to this person to deliver?</li>\n";
	$body .= "<li>How will the new person in this role make your life easier?</li>\n";
	$body .= "<li>What's your story in this company? When did you arrive, and what has happened for you since then?</li>\n";
	$body .= "<li>What are the biggest goals for the company this year?</li>\n";
	$body .= "<li>What is this new person's role in reaching those goals?</li>\n";
	$body .= "<li>What are the biggest decisions that this new person will make on his or her own?</li>\n";
	$body .= "<li>What is the story behind this job opening -- is it a new role, or has someone been promoted or left the organization, or something else?</li>\n";
	$body .= "<li>Apart from the formal responsibilities, what is the informal role of this person on the team? Some roles require an air traffic controller, some require a cheerleader and morale-booster, some require a drill sergeant. How would you describe this one?</li>\n";
	$body .= "<li>What are the yardsticks your new hire will pay the most attention to? Why are those milestones important?</li>\n";
	$body .= "</ul>\n";
	$body .= "<a href=\"https://www.linkedin.com/pulse/article/20141124074017-64875646-job-interview-the-5-questions-you-must-ask?trk=tod-home-art-list-small_3\">5 questions to ask</a>:\n";
	$body .= "<ul>\n";
	$body .= "<li>Which of my skills do you see as most important for the challenges that come with the position?</li>\n";
	$body .= "<li>How will the company help me develop?</li>\n";
	$body .= "<li>Can you tell me a little about the team I’ll be working with?</li>\n";
	$body .= "<li>What constitutes success with this position and company?</li>\n";
	$body .= "<li>Do you see any gaps in my skills or qualifications that I need to fill?</li>\n";
	$body .= "</ul>\n";
	$body .= "</div>\n";
	$body .= "<div>\n";
	$body .= "<a href=\"https://www.linkedin.com/pulse/1-most-impressive-job-interview-question-ask-dave-kerpen\">The 1 Most Impressive Job Interview Question to Ask</a>:\n";
	$body .= "<ul>\n";
	$body .= "<li>What New Skills Can I Hope to Learn Here?</li>\n";
	$body .= "<li>How Do You See This Position Evolving in The Next Three Years?</li>\n";
	$body .= "<li>What Can I Help to Clarify That Would Make Hiring Me an Easy Decision?</li>\n";
	$body .= "<li>How Can 'X' Scenario Move 'Y' Idea Forward?</li>\n";
	$body .= "<li>If You Could Improve One Thing About The Company, What Would It Be?</li>\n";
	$body .= "<li>What's The Most Frustrating Part of Working Here?</li>\n";
	$body .= "<li>Who's Your Ideal Candidate And How Can I Make Myself More Like Them?</li>\n";
	$body .= "<li>How Did You Get Your Start?</li>\n";
	$body .= "<li>What Is Holding the Company Back?</li>\n";
	$body .= "<li>What Keeps You Up at Night?</li>\n";
	$body .= "<li>What Concerns/Reservations Do You Have About Me for This Position?</li>\n";
	$body .= "</ul>\n";
	$body .= "</div>\n";

$body .= "<div><a href=\"http://www.kaspersky.com/recruitment-scheme/job-interview\" title=\"Prepare for an interview\">Prepare for an interview</a></div>\n";

if($page->UserIsAdmin()) {
	$body .= "<h2>ME</h2>\n";
	$body .= "<p>Experimental physicist specialized in plasma physics and hydrodynamics, my experience brought me basics R&amp;D project management skills. I like learning and discovering new knowledges, and automate computer procedures with scripts.</p>\n";
	$body .= "<p>Specialties:</p>\n";
	$body .= "<ul>\n";
	$body .= "<li><b>Physics:</b> plasma physics, magnetohydrodynamics, hydrodynamics</li>\n";
	$body .= "<li><b>IT:</b> Matlab/Octave, Perl, Python, C++, Bash, Vim, LaTeX, wiki</li>\n";
	$body .= "<li><b>Objective:</b> acquiring new skills by solving your problems!</li>\n";
	$body .= "</ul>\n";
}


$page->show($body);
unset($page);
?>
