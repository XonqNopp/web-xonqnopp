<?php
require_once("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->cssHelper->dirUpWing();

$page_title = "En cas de disparition, voici mes derni&egrave;res volont&eacute;s";

$body = $page->bodyBuilder->goHome("..");

$body .= $page->htmlHelper->setTitle($page_title);
$page->htmlHelper->hotBooty();

$body .= "<div style=\"margin-left: 60%; border: dashed; padding: 2mm\">\n";
$body .= "<p style=\"font-size: 10pt\">N'allez pas sur ma tombe pour pleurer, je ne suis pas l&agrave;.</p>\n";
$body .= "<p style=\"font-size: 10pt; text-align: right; font-style: italic; padding-right: 7mm;\">Pri&egrave;re indienne</p>\n";
$body .= "</div>\n";

// preliminary remark
$body .= "<h2>Note</h2>\n";
$body .= "<p>Si cela est en lien avec un accident d'avion, merci de tramsettre aux bureau suisse des enqu&ecirc;tes\n";
$body .= "sur les accidents d'avion ma page internet ";
$body .= $page->bodyBuilder->anchor("http://www.xonqnopp.ch/fly");

// Maniere
$body .= "<h2>Mani&egrave;re</h2>\n";
$body .= "<p>Je souhaite &ecirc;tre enterr&eacute; nu et sans cercueil dans la terre. Ainsi le cercle de la vie continuera.</p>\n";

// Musique
$body .= "<h2>Musique</h2>\n";
$body .= "Les musiques et artistes que je souhaite partager &agrave; mon enterrement sont:\n";
$body .= "<ul>\n";
$body .= "<li><i>Circle of Life</i> du Roi Lion pour commencer</li>\n";
$body .= "<li><i>Agnus Dei</i> du Requiem de John Rutter</li>\n";
$body .= "<li><i>Mon Testament</i> d'Oldelaf pour finir</li>\n";
$body .= "</ul>\n";

// Lectures
$body .= "<h2>Lectures</h2>\n";
$body .= "<ul>\n";
$body .= "<li>Jade et les sacr&eacute;s myst&egrave;res de la vie</li>\n";
$body .= "</ul>\n";

echo $body;
?>
