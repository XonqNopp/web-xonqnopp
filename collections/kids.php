<?php
/* TODO:
 */
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->NotAllowed();
//// debug
//$page->initHTML();
//$page->LogLevelUp(6);
//// CSS paths
$page->CSS_ppJump();
//$page->CSS_ppWing();
//// init body
$body = "";


//// GoHome
$gohome = new stdClass();
$gohome->rootpage = "..";
$body .= $page->GoHome($gohome);
//// Set title and hot booty
$body .= $page->SetTitle("Activit&eacute;s avec les enfants");// before HotBooty
$page->HotBooty();

$body .= "<div><table>\n";

$body .= "<tr>\n";
$body .= "<td rowspan=\"2\"></td>\n";
$body .= "<th colspan=\"2\" class=\"top\">&Eacute;t&eacute;</th>\n";
$body .= "<th colspan=\"2\" class=\"top\">Hiver</th>\n";
$body .= "</tr>\n";
$body .= "<tr>\n";
//$body .= "<td></td>\n";
$body .= "<th class=\"bot\">Beau temps</th>\n";
$body .= "<th class=\"bot\">Mauvais temps</th>\n";
$body .= "<th class=\"bot\">Beau temps</th>\n";
$body .= "<th class=\"bot\">Mauvais temps</th>\n";
$body .= "</tr>\n";

function line($name, $summerGood='x', $summerBad='x', $winterGood='x', $winterBad='x') {
	$result = "";
	$result .= "<tr>";
	$result .= "<td>$name</td>";
	$result .= "<td class=\"x\">$summerGood</td>";
	$result .= "<td class=\"x\">$summerBad</td>";
	$result .= "<td class=\"x\">$winterGood</td>";
	$result .= "<td class=\"x\">$winterBad</td>";
	$result .= "</tr>";
	$result .= "\n";
	return $result;
}

// Playground
$body .= line('Platy', 'x', '(x)', '(x)', '-');
$body .= line('B&ouml;singen', '(x)', 'x', '(x)', 'x');
$body .= line('Petits pas', '(x)', 'x', '(x)', 'x');

// Swimming
$body .= line('Pensier', 'x', '-', '-', '-');
$body .= line('Piscine Murten', '(x)', 'x', '(x)', 'x');
$body .= line('Bernaqua', '(x)', 'x', 'x', 'x');

// Museum
$body .= line('MHN Fri', '(x)', 'x', '(x)', 'x');
$body .= line('Papillorama', '(x)', 'x', '(x)', 'x');

// Promenade, sport
$body .= line('V&eacute;lo', 'x', '-', '-', '-');
$body .= line('Ch&egrave;vres for&ecirc;t', 'x', '(x)', 'x', '-');

// Parks, animals
$body .= line('Calao', 'x', '-', '-', '-');
$body .= line('Tierpark', 'x', '-', '(x)', '-');
$body .= line('Servion', 'x', '-', '?', '-');
$body .= line('Ratvel', 'x', '-', '?', '-');

// Misc
$body .= line('Ecuvillens', 'x', '-', '(x)', '-');

// People
$body .= line('Papy');
$body .= line('Droz');

$body .= "</table><div>\n";


//// Finish
echo $body;
unset($page);
?>
