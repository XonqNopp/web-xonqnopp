<?php
/*** Created: Tue 2014-08-05 15:32:50 CEST
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
$body .= $page->SetTitle("Enseignement");
$page->HotBooty();

	/*** General ***/
	$body .= "<h2>G&eacute;n&eacute;ral</h2>\n";
	$body .= "<p>Voir <a href=\"http://formasciences.epfl.ch\">cette page</a>.</p>\n";
//
	/*** Secondaire 1 ***/
	$body .= "<h2 id=\"Secondaire1\">Secondaire 1</h2>\n";
	$body .= "<p>Pour enseigner au Secondaire I, les exigences de la <a href=\"http://www.hepl.ch/\">HEP du canton de Vaud</a> sont les suivantes:</p>\n";
	$body .= "<ul>\n";
	$body .= "<li>Obtenir un titre de Bachelor acad&eacute;mique (180 cr&eacute;dit ECTS) dans 1, 2 ou 3 disciplines enseignables. Pour une discipline enseignable: 110 cr&eacute;dits au minimum. Pour 2 ou 3 disciplines enseignables: 60 cr&eacute;dits au minimum dans la premi&egrave;re et 40 cr&eacute;dits dans la ou les suivantes</li>\n";
	$body .= "<li>Suivre une formation p&eacute;dagogique dans une HEP (Vaud: 120 cr&eacute;dits) et obtenir un master of science en enseignement pour le degr&eacute; secondaire I</li>\n";
	$body .= "</ul>\n";
	$body .= "<p>Les disciplines scientifiques enseignables au secondaire I sont les <b>math&eacute;matiques</b>, les <b>sciences naturelles</b> et la <b>g&eacute;ographie</b>.</p>\n";
	$body .= "<p>Les diff&eacute;rents <a href=\"#Complements\">compl&eacute;ments de formation</a> propos&eacute;s par l'EPFL et l'UNIL indiquent les cr&eacute;dits pouvant &ecirc;tre acquis dans ces disciplines au cours du bachelor.</p>\n";
	$body .= "<p>Le choix d'une deuxi&egrave;me discipline enseignable peut se justifier en fonction des possibilit&eacute;s d'emploi. Par exemple, la discipline ``sciences naturelles'' ne compte que 2 p&eacute;riodes/ann&eacute;e alors que les math&eacute;matiques en comptent 4 &agrave; 6.</p>\n";
	$body .= "<p>Pour se former &agrave; deux disciplines, il faut avoir au minimum 25 cr&eacute;dits dans la deuxi&egrave;me discipline pour &ecirc;tre admis &agrave; la HEP. Le compl&eacute;ment (maximum 15 cr&eacute;dits) peut &ecirc;tre acquis durant la formation &agrave; la HEP.</p>\n";
	$body .= "<p>Difff&eacute;rents <a href=\"#Complements\">compl&eacute;ments de formation</a> permettant d'obtenir les cr&eacute;dits compl&eacute;mentaires n&eacute;cessaires sont propos&eacute;s par l'EPFL et l'UNIL.</p>\n";
//
	/*** Secondaire 2 ***/
	$body .= "<h2 id=\"Secondaire2\">Secondaire 2</h2>\n";
		/*** Une discipline enseignable ***/
		$body .= "<h3 id=\"1discipline\">Une discipline enseignable</h3>\n";
		$body .= "<p>Pour enseigner au Secondaire II (gymnases et &eacute;coles professionnelles), les exigences de la <a href=\"http://www.hepl.ch\">HEP du canton de Vaud</a> sont les suivantes:</p>\n";
		$body .= "<ul>\n";
		$body .= "<li>Obtenir un titre de Master acad&eacute;mique (270-300 cr&eacute;dits ECTS) dans une discipline enseignable. La discipline enseignable doit avoir au minimum 90 cr&eacute;dit ECTS, acquis aux cycles Bachelor et Master, dont au moins 30 cr&eacute;dits au Master.</li>\n";
		$body .= "<li>Suivre une formation p&eacute;dagogique en HEP de 60 cr&eacute;dits ECTS. Le profil de formation est donc celui d&eacute;crit par le graphique ci-dessous.</li>\n";
		$body .= "</ul>\n";
		$body .= "<p>Les disciplines enseignables au secondaire II sont les math&eacute;matiques, la physique, la chimie, la biologie, la g&eacute;ographie, l'informatique et les arts visuels (master en architecture).</p>\n";
		$body .= "<div><img src=\"formasciences_image02.png\" alt=\"image 02\" /></div>\n";
	//
		/*** Deux disciplines enseignables ***/
		$body .= "<h3 id=\"2disciplines\">Deux disciplines enseignables</h3>\n";
		$body .= "<p style=\"font-style: italic; font-size: 10pt;\">Depuis 2010, en cas de limitation des admissions &agrave; la HEP (Discipline par discipline en fonction du nombre des places de stage), la seconde discipline n'est pas prioritaire. Dans ce cas, la formation didactique sp&eacute;cifique peut &ecirc;tre suivie polus tard. Pour plus d'informations, voir sous <a href=\"http://www.hepl.ch\">http://www.hepl.ch</a>.</p>\n";
		$body .= "<p>Certaines disciplines ont des d&eacute;bouch&eacute;s limit&eacute;s, comme <b>informatique</b> ou <b>g&eacute;ographie</b>.</p>\n";
		$body .= "<p>La formation p&eacute;dagogique est possible dans deux disciplines pour les d&eacute;tenteurs d'un Master comportant 60 cr&eacute;dits d'une seconde discipline enseignable (dont 30 au Master), le cas &eacute;ch&eacute;ant ap&egrave;rs compl&eacute;ment d'&eacute;tudes.</p>\n";
		$body .= "<p>Le profil de formation correspondant est illustr&eacute; par le graphique ci-dessous.</p>\n";
		$body .= "<div><img src=\"formasciences_image01.png\" alt=\"image 01\" /></div>\n";
	//
//
	/*** Complements de formation ***/
	$body .= "<h2 id=\"Complements\">Compl&eacute;ments de formation</h2>\n";
	$body .= "<p><b>Pour l'enseignement d'une deuxi&egrave;me discipline au secondaire I}</b></p>\n";
	$body .= "<p>Un minimum de 25 cr&eacute;dits dans la deuxi&egrave;me discipline enseignable est requis pour &ecirc;tre admis &agrave; la HEP du canton de Vaud. Le compl&eacute;ment (maximum 15 cr&eacute;dits) peut &ecirc;tre acquis durant la formation &agrave; la HEP.</p>\n";
	$body .= "<p>Chaque compl&eacute;ment de formation propos&eacute; par l'EPFL et l'UNIL d&eacute;crit:</p>\n";
	$body .= "<ul>\n";
	$body .= "<li>Les cr&eacute;dits dans une premi&egrave;re discipline pouvant &ecirc;tre acquis au cours du bachelor</li>\n";
	$body .= "<li>Les cr&eacute;dits dans une deuxi&egrave;me discipline pouvant &ecirc;tre acquis au cours du bachelor</li>\n";
	$body .= "<li>Les cours permettant d'obtenir les cr&eacute;dits compl&eacute;mentaires n&eacute;cessaire.</li>\n";
	$body .= "</ul>\n";
	//
	$body .= "<p><a href=\"http://formasciences.epfl.ch/page-57532-fr.html\">Compl&eacute;ment de formation de l'EPFL</a></p>\n";
//

$page->show($body);
unset($page);
?>
