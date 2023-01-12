<?php
require("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->debug();

$page->setBodyguards("style=\"");
$page->setBodyguards("background-color : #e0f0ff;");
$page->setBodyguards("\"");

$page_title = "En cas de disparition, voici mes derni&egrave;res volont&eacute;s";

$body = $page->bodyHelper->goHome("..");

$body .= $page->htmlHelper->setTitle($page_title);
$page->htmlHelper->hotBooty();

$body .= "<div style=\"margin-left: 60%; border: dashed; padding: 2mm\">\n";
$body .= "<p style=\"font-size: 10pt\">N'allez pas sur ma tombe pour pleurer, je ne suis pas l&agrave;.</p>\n";
$body .= "<p style=\"font-size: 10pt; text-align: right; font-style: italic; padding-right: 7mm;\">Pri&egrave;re indienne</p>\n";
$body .= "</div>\n";

// preliminary remark
$body .= "<h2>Note</h2>\n";
$body .= "<p>Si cela est en lien avec un accident d'avion, merci de tramsettre aux bureau suisse des enqu&ecirc;tes sur les accidents d'avion ma page internet <a href=\"http://www.xonqnopp.ch/fly\">http://www.xonqnopp.ch/fly</a></p>\n";

// Maniere
$body .= "<h2>Mani&egrave;re</h2>\n";
$body .= "<p>Je souhaite &ecirc;tre enterr&eacute; nu et sans cercueil &agrave; la verticale dans une for&ecirc;t, sous un jeune pommier. Ainsi le cercle de la vie continuera. Je nourrirai le pommier, il fleurira et donnera des fruits.</p>\n";

// Musique
$body .= "<h2>Musique</h2>\n";
$body .= "Les musiques et artistes que je souhaite partager &agrave; mon enterrement sont:\n";
$body .= "<ul>\n";
$body .= "<li><i>Circle of Life</i> du Roi Lion pour commencer</li>\n";
$body .= "<li><i>Agnus Dei</i> du Requiem de John Rutter</li>\n";
$body .= "<li>Johnny Clegg</li>\n";
$body .= "<li>Ladysmith Black Mambazo</li>\n";
$body .= "<li>Dizu Platjes</li>\n";
$body .= "<li><i>Mon Testament</i> d'Oldelaf pour finir</li>\n";
$body .= "</ul>\n";

// Lectures
$body .= "<h2>Lectures</h2>\n";
$body .= "<ul>\n";
$body .= "<li>Jade et les sacr&eacute;s myst&egrave;res de la vie</li>\n";
//$body .= "<li></li>\n";
$body .= "</ul>\n";

// Printing
echo $body;
unset($page);
?>
