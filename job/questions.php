<?php
/*** Created: Wed 2014-08-06 12:05:43 CEST
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
$body .= $page->SetTitle("Interview questions commented (or not)");
$page->HotBooty();
//
	//// TOC
	$body .= "<h2>TOC</h2>\n";
	$body .= "<div>\n";
	$body .= "<ul>\n";
	$body .= "<li><a href=\"#top10\">Top 10 des questions des recruteurs expliqu&eacute;es</a></li>\n";
	$body .= "<li><a href=\"#weakness\">What is your greatest weakness?</a></li>\n";
	$body .= "<li><a href=\"#sheepie\">Better answers to sheepie questions</a></li>\n";
	$body .= "<li><a href=\"#liste\">Liste de questions</a></li>\n";
	$body .= "<li><a href=\"#list\">List of questions</a></li>\n";
	$body .= "<li><a href=\"#body\">Body-language mistakes</a></li>\n";
	$body .= "</ul>\n";
	$body .= "</div>\n";
//

	/*** Top 10 ***/
	$body .= "<h2 id=\"top10\">Top 10 des questions des recruteurs</h2>\n";
		/*** Preamble ***/
		$body .= "<h3>Pr&eacute;ambule</h3>\n";
		$body .= "<p>Dans n'importe quel type d'organisation, un jour ou l'autre, il est possible que tu sois invit&eacute; &agrave; passer un entretien de s&eacute;lection dans le dessein d'obtenir un poste.</p>";
		$body .= "<p>Le sc&eacute;nario est bien connu, &agrave; priori, tu te trouves dans un lieu inconnu, la tension monte, ton coeur bat plus vite que jamais, tes mains sont d&eacute;sagr&eacute;ablement moites et ton front perl&eacute; de fines gouttes de sueur. La porte d'un bureau s'ouvre et voil&agrave; qu'un ou plusieurs individus appel&eacute;s ``recruteurs'' se tiennent devant toi. Sans trop tarder, ils te prient de bien vouloir les rejoindre. Apr&egrave;s, de sorte &agrave; d&eacute;tendre l'atmosph&egrave;re l&eacute;g&egrave;rement anxiog&egrave;ne, ils se pr&eacute;sentent et te donnent des informations sur l'organisation et sur le poste. Ensuite, ils te posent toute une s&eacute;rie de questions pour d&eacute;terminer si tu corresponds ou non au profil qu'ils recherchent.</p>\n";
		$body .= "<p>Voil&agrave; donc le th&egrave;me sur lequel nous souhaiterions nous attarder: les attentes des recruteurs vis-&agrave;-vis des dix questions de l'entretien de s&eacute;lection les plus souvent pos&eacute;es.</p>\n";
		$body .= "<p>M&ecirc;me si d'apr&egrave;s ces r&eacute;sultats, ces dix questions sont celles qui sont le plus souvent pos&eacute;es lors d'un entretien de s&eacute;lection, il est fort probable que d'autres questions le soient &eacute;galement. C'est pourquoi, cette liste doit &ecirc;tre appr&eacute;hend&eacute;e avec pr&eacute;caution, comme une possibilit&eacute; parmi tant d'autres et comme un aper&ccedil;u des questions qui peuvent &ecirc;tre rencontr&eacute;es dans la pratique.</p>\n";
		$body .= "<p>Que veulent-ils savoir en me posant de telles questions?</p>\n";
		$body .= "<p>Malgr&eacute; ta motivation et ta volont&eacute; de fer, les r&eacute;ponses qui sortent de ta bouche te semblent peu convaincantes. Tu quittes le recruteur la queue entre les jambes, et tu te poses la question suivante: « Que voulait-il savoir &agrave; mon propos en me posant de telles questions? Cette situation n'est pas la plus fr&eacute;quente mais peut t'arriver. C'est pourquoi, il est utile de s'y pr&eacute;parer et de conna&icirc;tre les informations qu'un recruteur cherche &agrave; atteindre.</p>\n";
		$body .= "<p>Cette br&egrave;ve synth&egrave;se nous permet de constater que les m&eacute;dias offrent aux candidats «leur propre version» du lien entre la question et le(s) contenu(s) qu'elle cherche &agrave; mesurer</p>\n";
		$body .= "<p>Cela est d&eacute;licat pour les candidats car ils doivent s'attendre &agrave; jongler avec le contenu des questions de l'entretien de s&eacute;lection puisqu'il est probable que chaque recruteur ait des attentes diff&eacute;rentes vis-&agrave;-vis d'une question et du contenu qu'elle cherche &agrave; mesurer. C'est pourquoi, nous tenons &agrave; souligner que l'entretien de s&eacute;lection est une t&acirc;che complexe, qui n&eacute;cessite une pr&eacute;paration minutieuse de la part du candidat (et du recruteur) et o&ugrave; l'improvisation est &agrave; bannir par tous les moyens possibles.</p>\n";
		$body .= "<p>Le but de l'entretien est de rassurer le bonhomme en lui affirmant qu'il fait le bon choix. Il faut le surprendre, &eacute;ventuellement le faire rire.</p>\n";
	//
		/*** The questions ***/
		$body .= "<h3>Les questions comment&eacute;es</h3>\n";
		$body .= "<h4>Qu'est-ce qui vous attire dans ce poste?</h4>\n";
		$body .= "<p class=\"it\">Cette question serait pos&eacute;e pour mesurer les aspirations, les objectifs professionnels, la motivation et le dynamisme, les int&eacute;r&ecirc;ts et la capacit&eacute; du candidat &agrave; se projeter dans sa future fonction.</p>\n";
		$body .= "<h4>Parlez-moi de vous!</h4>\n";
		$body .= "<p class=\"it\">Cette requ&ecirc;te &eacute;valuerait la capacit&eacute; du candidat &agrave; exposer et &agrave; synth&eacute;tiser des informations concernant les &eacute;tapes de son parcours scolaire et/ ou professionnel. En r&egrave;gle g&eacute;n&eacute;rale, les recruteurs n'attendraient du candidat qu'une r&eacute;ponse en rapport avec le domaine professionnel ou sa personnalit&eacute; et ne chercheraient pas &agrave; s'immiscer dans sa vie priv&eacute;e.</p>\n";
		$body .= "<h4>Que vous ont apport&eacute; vos exp&eacute;riences professionnelles pass&eacute;es?</h4>\n";
		$body .= "<p class=\"it\">Cette question viserait &agrave; &eacute;valuer chez le candidat sa capacit&eacute; &agrave; synth&eacute;tiser, hi&eacute;rarchiser et comparer des informations concernant ses exp&eacute;riences pass&eacute;es. En outre, les recruteurs chercheraient &eacute;galement &agrave; &eacute;valuer les comp&eacute;tences que les candidats ont pu d&eacute;velopper lors de leurs exp&eacute;riences professionnelles pr&eacute;c&eacute;dentes et de voir si elles seront en ad&eacute;quation avec le poste &agrave; pourvoir.</p>\n";
		$body .= "<h4>Quelles sont vos pr&eacute;tentions salariales?</h4>\n";
		$body .= "<p class=\"it\">Cette question viserait &agrave; savoir si les candidats connaissent non seulement leur propre valeur sur le monde du march&eacute;, mais aussi, sur quelles informations ils se basent pour arriver &agrave; la fourchette salariale qu'ils estiment leur &ecirc;tre due. En outre, certains recruteurs &eacute;valuent aussi l'ambition ainsi que les int&eacute;r&ecirc;ts des candidats.</p>\n";
		$body .= "<h4>Quels sont vos points forts et vos points faibles?</h4>\n";
		$body .= "<p class=\"it\">D'un c&ocirc;t&eacute;, cette question viserait &agrave; &eacute;valuer chez le candidat son honn&ecirc;tet&eacute;, sa capacit&eacute; &agrave; r&eacute;fl&eacute;chir sur soi-m&ecirc;me et &agrave; d&eacute;terminer si les qualit&eacute;s &eacute;nonc&eacute;es par le candidat seraient en ad&eacute;quation avec le poste.</p>\n";
		$body .= "<h4>Pr&eacute;f&eacute;rez-vous travailler seul ou en &eacute;quipe?</h4>\n";
		$body .= "<p class=\"it\">Cette question serait relative au poste et aux exp&eacute;riences professionnelles pr&eacute;c&eacute;dentes (assistant, collaborateur, chef d'&eacute;quipe, ou cadre) et viserait &agrave; &eacute;valuer chez le candidat sa capacit&eacute; d'adaptation dans un groupe en tant que membre ou leader.</p>\n";
		$body .= "<h4>Pourquoi devrions-nous vous engager?</h4>\n";
		$body .= "<p class=\"it\">Cette question viserait &agrave; obtenir une information inhabituelle ou unique qui d&eacute;marquerait le candidat alpha du candidat beta. En autres, certains recruteurs &eacute;valueraient la capacit&eacute; des candidats &agrave; donner des exemples de leurs exp&eacute;riences pass&eacute;es en relation avec le poste mis &agrave; concours, d'autres chercheraient &agrave; &eacute;valuer tes connaissances sur l'organisation, le secteur et le poste.</p>\n";
		$body .= "<h4>&Agrave; quel type de management adh&eacute;rez-vous le plus?</h4>\n";
		$body .= "<p class=\"it\">Cette question semblerait &ecirc;tre r&eacute;currente lors de l'entretien de s&eacute;lection, n&eacute;anmoins, il y a peu, voire aucune information vis-&agrave;-vis du contenu qu'elle cherche &agrave; mesurer. Intuitivement, nous supposons qu'elle est pos&eacute;e pour savoir si le type de management auquel le candidat adh&egrave;re est le m&ecirc;me que celui pr&eacute;conis&eacute; par l'organisation.</p>\n";
		$body .= "<h4>Pourquoi notre entreprise vous int&eacute;resse-t-elle?</h4>\n";
		$body .= "<p class=\"it\">Cette question serait pos&eacute;e pour &eacute;valuer &agrave; quel niveau le candidat se serait renseign&eacute; sur l'organisation, son domaine d'activit&eacute;, ses concurrents, son chiffre d'affaires, son ou ses dirigeants, le nombre des salari&eacute;s qu'elle emploie ou encore la curiosit&eacute; ou l'ouverture d'esprit des candidats vis-&agrave;-vis de l'organisation.</p>\n";
		$body .= "<h4>Comment envisagez-vous votre carri&egrave;re dans 5 ans?</h4>\n";
		$body .= "<p class=\"it\">Cette question serait pos&eacute;e par les recruteurs pour &eacute;valuer l'implication &agrave; moyen/long terme des candidats dans l'entreprise. Par ailleurs, certains recruteurs poseraient cette question pour &eacute;valuer l'ambition et les int&eacute;r&ecirc;ts des candidats.</p>\n";
	//
	//
//
	/*** What is your greatest weakness ***/
	$body .= "<h2 id=\"weakness\">What is your greatest weakness?</h2>\n";
	$body .= "<p>Found on <a href=\"https://www.linkedin.com/today/post/article/20130709020109-52594-answering-the-question-what-s-your-greatest-weakness?trk=hb_ntf_MEGAPHONE_ARTICLE_LIKE?trk=object-title\">this LinkedIn page</a>.</p>\n";
	$body .= "<p><span class=\"bo\">AMY, MARKETING MANAGER:</span> So, Jason, what would you say is your greatest weakness?</p>\n";
	$body .= "<p><span class=\"bo\">JASON:</span> (Really? Oh, well. The company might require Amy to ask that lame question. That's not a great sign vis-a-vis the culture here, but I won't throw the baby out with the bathwater. Let's see if Amy can deal with my alternative to the usual gutless answer to the question, before I form an opinion about this place)</p>\n";
	$body .= "<p>Greatest weakness? That's a great question.</p>\n";
	$body .= "<p>When I was younger, I used to obsess about weaknesses and deficiencies. I bought For Dummies books and took classes in all kinds of things. As I got older, I realized that people don't have weaknesses, in the sense of things they're not good at and therefore need to work on. It's just the opposite, I think - I should focus on what I'm good at, and steer myself toward the things I'm meant to be doing. I should be doing web design and web strategy. I've learned that I'll never be good at graphic design, so I steer clear of it. These days, I don't think in terms of weaknesses that need correcting. I ask myself instead, \"What does the universe want me to do?\"</p>\n";
	$body .= "<p><span class=\"bo\">AMY:</span> Wow. I never thought about it that way. Doesn't everyone have weaknesses?</p>\n";
	$body .= "<p><span class=\"bo\">JASON:</span> I think everyone has strengths. You figure out what your strengths are.  You don't waste time getting better at things you'll never be much good at and more importantly, have no desire to do. What's your take?</p>\n";
	$body .= "<p><span class=\"bo\">AMY:</span> As you say it....it makes sense. I guess I've always believed that we have to work hard to improve ourselves.</p>\n";
	$body .= "<p><span class=\"bo\">JASON:</span> I agree completely! The only question is, what does self-improvement mean? Does it mean correcting deficiencies, begging the question 'Who decides what skills a person is supposed to have as an adult?' or does it mean getting better at the things you do best, and listening to an inner voice that keeps you on your path?</p>\n";
	$body .= "<p><span class=\"bo\">AMY:</span> Now I'm all confused. Thanks for getting me thinking about that, Jason!</p>\n";
//
	//// Better answers to sheepie questions
	$body .= "<h2 id=\"sheepie\">Better answers to sheepie questions</h2>\n";
	$body .= "<p><a href=\"https://www.linkedin.com/pulse/article/20140913052148-52594-smart-answers-to-stupid-interview-questions?trk=tod-home-art-list-large_0\">From this</a></p>\n";
	$body .= "<h3>What's your greatest weakness?</h3>\n";
	$body .= "<p><b>Sheepie answer:</b> I'm a hard worker, and I can be too hard on myself and other people when I think that either me or somebody else could give a little more to a project.</p>\n";
	$body .= "<p><b>High-mojo answer:</b> I used to obsess about my weaknesses. I used to think I had a million defects that needed correcting, and I read books and took classes to try to improve on them. Gradually I learned that it makes no sense for me to work on things that I'm not great at, and it makes no sense for me to think of myself as having weaknesses. These days I focus on getting better at things I'm already good at -- graphic design, especially.</p>\n";
	$body .= "<h3>With all the talented candidates, why should we hire you?</h3>\n";
	$body .= "<p><b>Sheepie answer:</b> I've been working in this arena for sixteen years and I've got a great track record.</p>\n";
	$body .= "<p><b>High-mojo answer:</b> That's what we're here to figure out, I guess! I can't say that you should hire me. There might be somebody else who's perfect for the job - you've met the other candidates or will meet them, and of course you know more about the needs here than I do. I can say this - if this match is meant to be, both of us will know it.</p>\n";
	$body .= "<h3>Where do you see yourself in five years?</h3>\n";
	$body .= "<p><b>Sheepie answer:</b> Working hard here or in another Financial Analyst role, with luck moving up to Senior Financial Analyst and being more involved in strategic investments than I've been so far.</p>\n";
	$body .= "<p><b>High-mojo answer:</b> Exploring one of my passions, undoubtedly -- maybe in Finance, or my interest in ecommerce or in an international role. I have a lot of passions!</p>\n";
//
	/*** Questions de la brochure du Forum EPFL ***/
	$body .= "<h2 id=\"liste\">Questions de la brochure du Forum EPFL</h2>\n";
		/*** Sur la personnalite ***/
		$body .= "<h3>Sur la personnalit&eacute;</h3>\n";
		$body .= "<ul>\n";
		$body .= "<li>Comment vous y prenez-vous pour chercher un emploi?</li>\n";
		$body .= "<li>Pour vous, quel est le poste id&eacute;al?</li>\n";
		$body .= "<li>Quels sont les crit&egrave;res les plus importants dans votre recherche?</li>\n";
		$body .= "<li>Comment allez-vous faire le choix final?</li>\n";
		$body .= "<li>Si vous aviez trois adjectifs pour vous d&eacute;finir, quels seraient-ils?</li>\n";
		$body .= "<li>Comment savez-vous que vous &ecirc;tes ce que vous dites?</li>\n";
		$body .= "<li>Quels sont les points faibles sur lesquels vous savez que vous devez travailler?</li>\n";
		$body .= "<li>Faites un portrait de vous-m&ecirc;mes.</li>\n";
		$body .= "<li>Comment vous d&eacute;crirait votre entourage?</li>\n";
		$body .= "<li>Quelles qualit&eacute;s ou d&eacute;fauts recherchez-vous ou ha&iuml;ssez-vous chez les autres?</li>\n";
		$body .= "</ul>\n";
	//
		/*** Sur les objectifs professionnels ***/
		$body .= "<h3>Sur les objectifs professionnels</h3>\n";
		$body .= "<ul>\n";
		$body .= "<li>Pourquoi avoir postul&eacute; dans notre entreprise?</li>\n";
		$body .= "<li>Quelle image avez-vous de notre entreprise?</li>\n";
		$body .= "<li>&Agrave; quoi avez-vous &eacute;t&eacute; sensible dans notre annonce?</li>\n";
		$body .= "<li>Que pensez-vous du poste propos&eacute;?</li>\n";
		$body .= "<li>Qu'attendez-vous de cet emploi?</li>\n";
		$body .= "<li>Que pouvez-vous nous apporter?</li>\n";
		$body .= "<li>Quels sont vos projets professionnels?</li>\n";
		$body .= "<li>Quelles sont, &agrave; votre avis, les qualit&eacute;s requises pour le poste?</li>\n";
		$body .= "<li>Que va changer pour vous le fait d'entrer chez nous?</li>\n";
		$body .= "<li>Comment vous voyez-vous &eacute;voluer?</li>\n";
		$body .= "<li>Que cherchez-vous chez votre sup&eacute;rieur hi&eacute;rarchique?</li>\n";
		$body .= "<li>Pr&eacute;f&eacute;rez-vous travailler seul ou en &eacute;quipe?</li>\n";
		$body .= "<li>D&eacute;crivez l'environnement humain que vous souhaiteriez int&eacute;grer.</li>\n";
		$body .= "<li>Comment r&eacute;agissez-vous lorsque vous &ecirc;tes en d&eacute;saccord avec votre hi&eacute;rarchie?</li>\n";
		$body .= "<li>Comment r&eacute;agissez-vous &agrave; la critique?</li>\n";
		$body .= "<li>Acceptez-vous facilement les ordres?</li>\n";
		$body .= "<li>Quelles sont les d&eacute;cisions les plus difficiles &agrave; prendre?</li>\n";
		$body .= "<li>Quelles sont vos pr&eacute;tentions salariales?</li>\n";
		$body .= "<li>Quelles sont votre mobilit&eacute; et votre flexibilit&eacute; horaire?</li>\n";
		$body .= "</ul>\n";
	//
		/*** Sur la formation ***/
		$body .= "<h3>Sur la formation</h3>\n";
		$body .= "<ul>\n";
		$body .= "<li>Pourquoi avez-vous choisi cette formation?</li>\n";
		$body .= "<li>Avez-vous particip&eacute; &agrave; une vie associative? Si oui, en quoi et comment?</li>\n";
		$body .= "<li>Pourquoi ne pas avoir continu&eacute; vos &eacute;tudes?</li>\n";
		$body .= "<li>Si c'&eacute;tait &agrave; refaire?</li>\n";
		$body .= "<li>Aimeriez-vous suivre une autre formation?</li>\n";
		$body .= "<li>Si vous deviez changer quelque chose au syst&egrave;me d'&eacute;tudes, que feriez-vous?</li>\n";
		$body .= "<li>Parlez-nous de vos travaux d'&eacute;tudes.</li>\n";
		$body .= "<li>Avez-vous s&eacute;journ&eacute; dans un pays &eacute;tranger?</li>\n";
		$body .= "</ul>\n";
	//
		/*** Sur les stages ou les jobs d'ete ***/
		$body .= "<h3>Sur les stages ou les jobs d'&eacute;t&eacute;</h3>\n";
		$body .= "<ul>\n";
		$body .= "<li>Par quel interm&eacute;diaire avez-vous trouv&eacute; ce stage?</li>\n";
		$body .= "<li>Qu'en avez-vous retir&eacute;?</li>\n";
		$body .= "<li>Quelles difficult&eacute;s avez-vous rencontr&eacute;es?</li>\n";
		$body .= "<li>Quelle exp&eacute;rience vous a le plus int&eacute;ress&eacute;?</li>\n";
		$body .= "</ul>\n";
	//
		/*** Sur l'experience ***/
		$body .= "<h3>Sur l'exp&eacute;rience professionnelle</h3>\n";
		$body .= "<ul>\n";
		$body .= "<li>Que vous ont apport&eacute; vos exp&eacute;riences?</li>\n";
		$body .= "<li>Quelles difficult&eacute;s avez-vous rencontr&eacute;es?</li>\n";
		$body .= "<li>Quelle a &eacute;t&eacute; votre principale r&eacute;ussite? &eacute;chec?</li>\n";
		$body .= "<li>Parlez-nous de vos changements d'emplois.</li>\n";
		$body .= "<li>Parlez-nous des trous dans votre CV.</li>\n";
		$body .= "<li>Quelles d&eacute;cisions aviez-vous &agrave; prendre dans vos emplois pr&eacute;c&eacute;dents?</li>\n";
		$body .= "<li>Quels ont &eacute;t&eacute; vos rapports avec la hi&eacute;rarchie? Avec les coll&egrave;gues? Avec les collaborateurs?</li>\n";
		$body .= "<li>Comment voyez-vous votre &eacute;volution?</li>\n";
		$body .= "<li>De vos exp&eacute;riences, quels sont les traits de caract&egrave;res qui vous ont aid&eacute; et ceux qui vous ont desservi?</li>\n";
		$body .= "<li>Pourquoi cherchez-vous un nouvel emploi?</li>\n";
		$body .= "<li>Comment d&eacute;finissez-vous l'esprit d'&eacute;quipe?</li>\n";
		$body .= "<li>Pourquoi avoir choisi une carri&egrave;re dans le priv&eacute; plut&ocirc;t que dans l'acad&eacute;mique?</li>\n";
		$body .= "</ul>\n";
	//
//
	/*** Questions found on LinkedIn ***/
	$body .= "<h2 id=\"list\">Questions found on LinkedIn</h2>\n";
	$body .= "<p>Sources:</p>\n";
	$body .= "<ul>\n";
	$body .= "<li><a href=\"http://www.linkedin.com/today/post/article/20130123154152-201849-32-killer-interview-questions\">LinkedIn</li>\n";
	$body .= "<li><a href=\"http://www.inc.com/lou-adler/best-interview-question-ever.html?cid=sf01001\">inc.com</a></li>\n";
	$body .= "<li><a href=\"https://www.linkedin.com/today/post/article/20140807094555-20017018-13-ceos-share-their-favorite-job-interview-questions?trk=tod-home-art-list-large_0\">another LinkedIn</a></li>\n";
	$body .= "<li><a href=\"https://www.linkedin.com/today/post/article/20140813123349-36714090-the-2nd-most-important-interview-question?trk=tod-home-art-list-small_2\">another another LI</a></li>\n";
	$body .= "<li>and others...</li>\n";
	$body .= "</ul>\n";
	$body .= "<ul>\n";
	$body .= "<li>What is the biggest mistake you made in your life and what did you learn from it?</li>\n";
	$body .= "<li>If in 3months you found the job not to be what you expected it to be, what would it look like?</li>\n";
	$body .= "<li>What superhero would you be and why?</li>\n";
	$body .= "<li>What is one misconception people have about you?</li>\n";
	$body .= "<li>If you were a kitchen appliance what would you be?</li>\n";
	$body .= "<li>Why shouldn't I call for a reference and why?</li>\n";
	$body .= "<li>So what are you going to do for us?</li>\n";
	$body .= "<li>Who are your heroes and why?</li>\n";
	$body .= "<li>What works for you and why? How can you increase your interaction with the energy generators? And what can you do to be one yourself?</li>\n";
	$body .= "<li>Who at your former place of work gave you the most energy and why?</li>\n";
	$body .= "<li>What do you think will be the biggest challenges you and I will face in your first 3 months on the job?</li>\n";
	$body .= "<li>What kind of person do you like to work with?</li>\n";
	$body .= "<li>If you could wave a magic wand, what ill in the world would you solve and why?</li>\n";
	$body .= "<li>You are on your death bed for what do you want to be remembered?</li>\n";
	$body .= "<li>Can you do this job?</li>\n";
	$body .= "<li>Why are you qualified?</li>\n";
	$body .= "<li>What was your most and least satisfying job and why?</li>\n";
	$body .= "<li>What is your view of office and business politics? How does it work? Can it be used in a positive way?</li>\n";
	$body .= "<li>What has been your best work that you think you are proud of yourself and what about them could you have done better?</li>\n";
	$body .= "<li>Describe a time when you missed a deadline/revenues/failed to meet expectations and why? How did you feel? What lesson did you learn from it?</li>\n";
	$body .= "<li>What are top 2 professional traits people in the company will miss or not the miss the most about you?</li>\n";
	$body .= "<li>Why did you sign on for the position? What kept you there months and years later?</li>\n";
	$body .= "<li>When was the last time you lost your temper? What was the situation and why do you think this affects you so?</li>\n";
	$body .= "<li>What was unfair about your last job?</li>\n";
	$body .= "<li>What motivates you and what doesn't?</li>\n";
	$body .= "<li>What is your 5 year goal?</li>\n";
	$body .= "<li>Tell me in no more than 2 words what you think we do?</li>\n";
	$body .= "<li>How many companies are you actively pursuing at the moment?</li>\n";
	$body .= "<li>What industries are you interested in?</li>\n";
	$body .= "<li>If your current employer offered you the same would you stay? Why or why not?</li>\n";
	$body .= "<li>If you are hired for the position, what would your top priorities be in the coming quarter?</li>\n";
	$body .= "<li>Can you give me a detailed overview of the accomplishment?</li>\n";
	$body .= "<li>Tell me about the company, your title, your position, your role, and the team involved.</li>\n";
	$body .= "<li>What were the actual results achieved?  When did it take place and how long did the project take?</li>\n";
	$body .= "<li>Why were you chosen?</li>\n";
	$body .= "<li>What were the 3-4 biggest challenges you faced and how did you deal with them?</li>\n";
	$body .= "<li>Where did you go the extra mile or take the initiative?</li>\n";
	$body .= "<li>Walk me through the plan, how you managed it, and its measured success.</li>\n";
	$body .= "<li>Describe the environment and resources.</li>\n";
	$body .= "<li>Explain your manager's style and whether you liked it.</li>\n";
	$body .= "<li>What were the technical skills needed to accomplish the objective and how were they used?</li>\n";
	$body .= "<li>What were some of the biggest mistakes you made?</li>\n";
	$body .= "<li>What aspects of the project did you truly enjoy?</li>\n";
	$body .= "<li>What aspects did you not especially care about and how did you handle them?</li>\n";
	$body .= "<li>Give examples of how you managed and influenced others.</li>\n";
	$body .= "<li>How did you change and grow as a person?</li>\n";
	$body .= "<li>What you would do differently if you could do it again?</li>\n";
	$body .= "<li>What type of formal recognition did your receive?</li>\n";
	$body .= "<li>If we're sitting here a year from now celebrating what a great twelve months it's been for you in this role, what did we achieve together?</li>\n";
	$body .= "<li>If you got hired, loved everything about this job, and are paid the salary you asked for, what kind of offer from another company would you consider?</li>\n";
	$body .= "<li>What things do you not like to do?</li>\n";
	$body .= "<li>What questions do you have for me?</li>\n";
	$body .= "<li>Tell us about a time when things didn't go the way you wanted -- like a promotion you wanted and didn't get, or a project that didn't turn out how you had hoped.</li>\n";
	$body .= "<li>Why was that important to you?</li>\n";
	$body .= "<li>Describe in detail how you tried to deal with it before choosing to leave and what happened when you tried?</li>\n";
	$body .= "<li>What first attracted you to the role and company? What changed and when?</li>\n";
	$body .= "<li>What would have had to change to keep you from leaving? Why is that important for you?</li>\n";
	$body .= "<li>Please describe your role, the company, the overall culture and the team members you worked with.</li>\n";
	$body .= "<li>Please describe in detail your manager's style.</li>\n";
	$body .= "<li>What did you like most or least about your manager?</li>\n";
	$body .= "<li>What challenges did you face with your manager and how did you deal with it?</li>\n";
	$body .= "<li>Describe in detail the dynamics of the team that you worked with.</li>\n";
	$body .= "<li>What did you like most and least about your team?</li>\n";
	$body .= "<li>Describe in detail some of the challenges you faced with your team members and how you dealt with them.</li>\n";
	$body .= "<li>If everything about that job, company, and culture would have been perfect for you, what would it have taken to get you to leave?</li>\n";
	$body .= "<li>What 3 biggest challenges did you face in that role and how did you address them?</li>\n";
	$body .= "<li>What was your greatest success during your time with the company?</li>\n";
	$body .= "<li>What was your biggest on the job failure and what did you learn from it?</li>\n";
	$body .= "<li>Why do you think that this job is a better fit for you and your needs/goals?</li>\n";
	$body .= "<li>Why do you think you will be a good fit for this job and company?</li>\n";
	$body .= "</ul>\n";
	//
		/*** Most unusual questions ***/
		$body .= "<h3>The most unusual questions</h3>\n";
		$body .= "<p><a href=\"https://www.linkedin.com/today/post/article/20140804100911-64875646-the-most-unusual-interview-questions-and-how-to-answer-them?trk=tod-home-art-list-small_2\">Source</a></p>\n";


		$body .= "<h4>You're a new addition to the crayon box, what color would you be and why?</h4>\n";
		$body .= "<p>Asked at Urban Outfitters.</p>\n";
		$body .= "<p>This is very much in the vein of \"what animal would you be?\" or \"If you were a superhero, what would your power be?\" The interviewer could be looking for personality traits&nbsp;-&nbsp;saying you'd be a shade of red might indicate boldness, blue might indicate fading more into the background&nbsp;-&nbsp;or creativity. Urban Outfitters almost certainly values employees who see life a little differently from the mainstream, and would be looking for creative \"out of the box\" answers.</p>\n";
		$body .= "<h4>What is the funniest thing that has happened to you recently?</h4>\n";
		$body .= "<p>Asked at Applebee's.</p>\n";
		$body .= "<p>Applebee's is known for wanting its waitstaff to be positive, upbeat, and friendly, so asking for a funny story is probably actually an audition to see how well you would perform \"chit-chatting\" with customers. Your entire interview is an audition for the position, so always answer accordingly. (In other words, don't tell the story where you woke up drunk missing your left shoe — no matter how hilarious.)</p>\n";
		$body .= "<h4>Have you ever been on a boat?</h4>\n";
		$body .= "<p>Asked at Applied Systems.</p>\n";
		$body .= "<p>If you're not ready for it, a question like this could really throw you for a loop. It's known as a creative open-ended question, and the interviewer is looking to see where you will go with it. Will you simply say yes or no? Will you tell a funny story? Brag about the cruise you took? Talk about how you were the leader of your crew team? Every answer will give her insight into your personality. Consider the position you're applying for when you give your answer.</p>\n";
		$body .= "<h4>If you were a pizza deliveryman how would you benefit from scissors?</h4>\n";
		$body .= "<p>Asked at Apple.</p>\n";
		$body .= "<p>This is definitely one of my favorites, because it's such a unique way for the interviewer to look for creative thinking, grace under pressure, problem solving, and many more desirable job traits. A similar (more common) question might be, \"What you would take with you to be marooned on a desert island?\"</p>\n";
		$body .= "<h4>Why is a tennis ball fuzzy?</h4>\n";
		$body .= "<p>Asked at Xerox.</p>\n";
		$body .= "<p>We have to assume that the HR staff at Xerox don't actually expect anyone to know the correct answer to this (in case you're wondering) — unless they're staffing their Corporate Jeopardy team. Rather, they might be looking for whether you answer this with something you work out logically, or something more creative. If you're applying for an accounting position, go for logical. Marketing? Get creative.</p>\n";
		$body .= "<h4>If you could throw a parade of any caliber through the Zappos office, what type of parade would it be?</h4>\n";
		$body .= "<p>Asked at The Zappos Family.</p>\n";
		$body .= "<p>According to insiders, this isn't such a strange question when you consider that employees do throw parades at Zappos. Considering their reputation for outstanding customer service, they may be looking for employees who can demonstrate a sense of fun and camaraderie in the workplace. This question could also be about creativity and attention to detail, considering how specific you get with your answer.</p>\n";
	//
		/*** How to answer the most common questions ***/
		$body .= "<h3>How to answer the most common interview questions</h3>\n";
		$body .= "<p><a href=\"http://www.careerealism.com/most-common-interview-questions/#!Z6W0u\">Source</a></p>\n";
		// 1
		$body .= "<h4>Tell Me About Yourself</h4>\n";
		$body .= "<p>What the hiring manager is really asking: \"How do your education, work history, and professional aspirations relate to the open job?\"</p>\n";
		$body .= "<p>How to respond: Select key work and education information that shows the hiring manager why you are a perfect fit for the job and for the company.</p>\n";
		$body .= "<p>For example, a recent grad might say something like, \"I went to X University where I majored in Y and completed an internship at Z Company. During my internship, I did this and that (name achievements that match the job description), which really solidified my passion for this line of work.\"</p>\n";
		// 2
		$body .= "<h4>Where Do You See Yourself In Five Years?</h4>\n";
		$body .= "<p>What the hiring manager is really asking: \"Does this position fit into your long-term career goals? Do you even have long-term career goals?\"</p>\n";
		$body .= "<p>How to respond: Do NOT say you don't know (even if you don't) and do not focus on your personal life (it's nice that you want to get married, but it's not relevant). Show the employer you've thought about your career path and that your professional goals align with the job.</p>\n";
		// 3
		$body .= "<h4>What's Your  Greatest Weakness?</h4>\n";
		$body .= "<p>What the hiring manager is really asking: \"Are you self-aware? Do you know where you could stand to improve and are you proactive about getting better?\"</p>\n";
		$body .= "<p>How to respond: A good way to answer this is with real-life feedback that you received in the past. For instance, maybe a former boss told you that you needed to work on your presentation skills.</p>\n";
		$body .= "<p>Note that fact, then tell the employer how you've been proactively improving. Avoid any deal breakers (\"I don't like working with other people.\") or clich&eacute; answers (\"I'm a perfectionist and I work too hard.\").</p>\n";
		// 4
		$body .= "<h4>What Motivates You To Perform?</h4>\n";
		$body .= "<p>What the hiring manager is really asking: \"Are you a hard worker? Am I going to have to force you to produce quality work?\"</p>\n";
		$body .= "<p>How to respond: Ideal employees are motivated internally, so tell the hiring manager that you find motivation when working toward a goal, contributing to a team effort, and/or developing your skills. Provide a specific example that supports your response.</p>\n";
		$body .= "<p>Finally, even if it's true, do not tell an employer that you're motivated by bragging rights, material things, or the fear of being disciplined.</p>\n";
		// 5
		$body .= "<h4>Tell Me About A Time That You Failed.</h4>\n";
		$body .= "<p>What the hiring manager is really asking: \"How do you respond to failure? Do you learn from your mistakes? Are you resilient?\"</p>\n";
		$body .= "<p>How to respond: Similar to the \"greatest weakness\" question, you need to demonstrate how you've turned a negative experience into a learning experience.</p>\n";
		$body .= "<p>To do this, acknowledge one of your failures, take responsibility for it, and explain how you improved as a result. Don't say you've never failed (Delusional, much?), don't play the blame game, and don't bring up something that's a deal breaker (\"I failed a drug test once...\")</p>\n";
		// 6
		$body .= "<h4>Why Do You Want To Work Here?</h4>\n";
		$body .= "<p>What the hiring manager is really asking: \"Are you genuinely interested in the job? Are you a good fit for the company?\"</p>\n";
		$body .= "<p>How to respond: Your goal for this response is to demonstrate why you and the company are a great match in terms of philosophy and skill. Discuss what you've learned about them, noting how you align with their mission, company culture, and reputation.</p>\n";
		$body .= "<p>Next, highlight how you would benefit professionally from the job and how the company would benefit professionally from you.</p>\n";
		// 7
		$body .= "<h4>How Many Couches Are There In America?</h4>\n";
		$body .= "<p>What the hiring manager is really asking: \"Can you think on your feet? Can you handle pressure? Can you think critically?\"</p>\n";
		$body .= "<p>How to respond: When faced with a seemingly absurd question like this (there are many variations&nbsp;-&nbsp;just ask anyone who interviewed at Google before December), it's important you not be caught off guard.</p>\n";
		$body .= "<p>Resist your urge to tell the interviewer the question is stupid and irrelevant, and instead walk him through your problem-solving thought process. For this particular question, you would talk about how many people are in the U.S., where couches are found (homes, hotels, furniture stores), etc.</p>\n";
	//
//
	/*** Body language mistakes ***/
	$body .= "<h2 id=\"body\">Body language mistakes</h2>\n";
	$body .= "<ul>\n";
	$body .= "<li>Leaning Back too much&nbsp;-&nbsp;you come off lazy or arrogant.</li>\n";
	$body .= "<li>Leaning forward&nbsp;-&nbsp;can seem aggressive. Aim for a neutral posture.</li>\n";
	$body .= "<li>Breaking eye contact too soon&nbsp;-&nbsp;can make you seem untrustworthy or overly nervous. Hold eye contact a hair longer, especially during a handshake.</li>\n";
	$body .= "<li>Nodding too much&nbsp;-&nbsp;can make you look like a bobble head doll! Even if you agree with what's being said, nod once and then try to remain still.</li>\n";
	$body .= "<li>Chopping or pointing with your hands&nbsp;-&nbsp;feels aggressive.</li>\n";
	$body .= "<li>Crossing your arms&nbsp;-&nbsp;makes you look defensive, especially when you're answering questions. Try to keep your arms at your sides.</li>\n";
	$body .= "<li>Fidgeting&nbsp;-&nbsp;instantly telegraphs how nervous you are. Avoid it at all costs.</li>\n";
	$body .= "<li>Holding your hands behind your back (or firmly in your pockets)&nbsp;-&nbsp;can look rigid and stiff. Aim for a natural, hands at your sides posture.</li>\n";
	$body .= "<li>Looking up or looking around&nbsp;-&nbsp;is a natural cue that someone is lying or not being themselves. Try to hold steady eye contact.</li>\n";
	$body .= "<li>Staring&nbsp;-&nbsp;can be interpreted as aggressive. There's a fine line between holding someone's gaze and staring them down.</li>\n";
	$body .= "<li>Failing to smile&nbsp;-&nbsp;can make people uncomfortable, and wonder if you really want to be there. Go for a genuine smile especially when meeting someone for the first time.</li>\n";
	$body .= "<li>Stepping back when you're asking for a decision&nbsp;-&nbsp;conveys fear or uncertainty. Stand your ground, or even take a slight step forward with conviction.</li>\n";
	$body .= "<li>Steepling your fingers or holding palms up&nbsp;-&nbsp;looks like a begging position and conveys weakness.</li>\n";
	$body .= "<li>Standing with hands on hips&nbsp;-&nbsp;is an aggressive posture, like a bird or a dog puffing themselves up to look bigger.</li>\n";
	$body .= "<li>Checking your phone or watch&nbsp;-&nbsp;says you want to be somewhere else. Plus, it's just bad manners.</li>\n";
	$body .= "</ul>\n";
//

$page->show($body);
unset($page);
?>
