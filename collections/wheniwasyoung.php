<?php
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->LogLevelUp(6);

$tv_title = "TV";
$tv = array();
$computer = array();
$mobile = array();
$movies = array();
$misc = array();

$page_title = "Quand j'&eacute;tais petit...";
	// TV
	$tv[] = "Les &eacute;crans &eacute;taient aussi profonds que large. Un ecran de 20\" &eacute;tait tr&egrave;s grand";
	$tv[] = "Il y avait la TSR. Plus tard est apparu Suisse4, transform&eacute;e ensuite en TSR2";
	$tv[] = "Pour conna&icric;tre le programme TV, il fallait acheter un journal";
	$tv[] = "Si on avait loup&eacute; un programme, on ne pouvait pas le revoir.";
//
	// Computer
	$computer_title = "Ordinateurs";
	$computer[] = "On ne pouvait pas t&eacute;l&eacute;phoner en m&ecirc;me temps qu'&ecirc;tre sur internet.";
	$computer[] = "Pour se connecter, le modem faisait plein de bruits bizarres pendant 2min";
	$computer[] = "La connecion &eacute;tait factur&eacute;e &agrave; la minute";
	$computer[] = "Il fallait environ 1min pour charger une page simple, une image prenait souvent plus que 5min";
	$computer[] = "Facebook n'existait pas; Yahoo &eacute;tait plus connu que Google, mais ces 2 sites n'&eacute;taient que des moteurs de recherches";
	$computer[] = "Les jeunes cool avaient une adresse Caramail. Plus tard, les jeunes avaient une adresse hotmail et discutaient sur MSN";
	$computer[] = "Les ordinateurs &eacute;tait plus gros que les &eacute;crans";
	$computer[] = "Ouvrir un document prenait 5 &agrave; 10min";
	$computer[] = "On stockait les donn&eacute;es sur des disquettes de 1,4Mo";
//
	// phones
	$mobile_title = "T&eacute;l&eacute;phones et Natel";
	$mobile[] = "les t&eacute;l&eacute;phones avaient un fil qui les reliaient au mur";
	$mobile[] = "les natels &eacute;taient gros, &eacute;pais avec un petit &eacute;cran en noir et blanc et une antenne &agrave; sortir pour t&eacute;l&eacute;phoner ou envoyer des sms, les seules fonctions possibles";
	$mobile[] = "quand quelqu'un nous appelait, on ne pouvait pas savoir qui";
//
	// Movies
	$movies_title = "Cin&eacute;ma";
	$movies[] = "Le cin&eacute;ma 3D n'existait que dans des parcs d'attractions sp&eacute;ciaux et ne fonctionnait qu'avec des grosses lunettes compliqu&eacute;es qui donnaient mal &agrave; la t&ecirc;te";
	$movies[] = "Quand un film sortait aux USA, il fallait environ un an pour qu'il sorte en Europe. Il fallait ensuite encore un an avant d'avoir le DVD";
//
	// Misc
	$misc_title = "Divers";
	$misc[] = "les voitures roulaient &agrave; l'essence avec ou sans plomb, les camions au diesel";
	$misc[] = "Les grenouilles en sucre coutaient 5ct";
	$misc[] = "Les champions de F1 s'appelaient Schumacher ou Hakkinen";
	$misc[] = "En voiture, si on on n'avait pas de carte et que notre destination n'&eacute;tait pas indiqu&eacute;e sur les panneaux, on &eacute;tait perdu.";
	$misc[] = "Il fallait l&eacute;cher les timbres pour les coller sur les enveloppes.";

$page->CSS_ppJump();

$body = "";
$body .= $page->GoHome();
$body .= $page->SetTitle($page_title);
$page->HotBooty();

//$body .= "<div class=\"the_body\">\n";
	// TV
	$body .= "<h2>$tv_title</h2>\n";
	$body .= "<div>\n";
	$body .= "<ul>\n";
	foreach($tv as $item) {
		$body .= "<li>$item</li>\n";
	}
	$body .= "</ul>\n";
	$body .= "</div>\n";
//
	// Computer
	$body .= "<h2>$computer_title</h2>\n";
	$body .= "<div>\n";
	$body .= "<ul>\n";
	foreach($computer as $item) {
		$body .= "<li>$item</li>\n";
	}
	$body .= "</ul>\n";
	$body .= "</div>\n";
//
	// Phones
	$body .= "<h2>$mobile_title</h2>\n";
	$body .= "<div>\n";
	$body .= "<ul>\n";
	foreach($mobile as $item) {
		$body .= "<li>$item</li>\n";
	}
	$body .= "</ul>\n";
	$body .= "</div>\n";
//
	// Movies
	$body .= "<h2>$movies_title</h2>\n";
	$body .= "<div>\n";
	$body .= "<ul>\n";
	foreach($movies as $item) {
		$body .= "<li>$item</li>\n";
	}
	$body .= "</ul>\n";
	$body .= "</div>\n";
//
	// Misc
	$body .= "<h2>$misc_title</h2>\n";
	$body .= "<div>\n";
	$body .= "<ul>\n";
	foreach($misc as $item) {
		$body .= "<li>$item</li>\n";
	}
	$body .= "</ul>\n";
	$body .= "</div>\n";
//$body .= "</div>\n";

/*** Printing ***/
$page->show($body);
unset($page);
?>
