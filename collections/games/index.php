<?php
/*** Created: Thu 2015-07-16 08:39:55 CEST
 * TODO:
 */
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->initDB();

//// borrow back
require("${funcpath}_local/borrowback.php");
borrow_back($page, "games");

//// debug
//$page->initHTML();
//$page->LogLevelUp(6);
//// CSS paths
$page->CSS_ppJump(2);
$page->CSS_ppWing(2);
$GI = $page->UserIsAdmin();
//// init body
$body = "";
$page_title = "Games";

//// GoHome
$gohome = new stdClass();
$gohome->page = "..";
$body .= $page->GoHome($gohome);
//// Set title and hot booty
$body .= $page->SetTitle($page_title);// before HotBooty
$page->HotBooty();
	//// head
	$body .= "<div class=\"wide\">\n";
	$body .= "<div class=\"lhead\">\n";
	$body .= "</div>\n";
	$body .= "<div class=\"chead\">\n";
	$body .= "</div>\n";
	$body .= "<div class=\"rhead\">\n";
	$body .= "<a href=\"../missings/index.php?view=games\" title=\"Missing games\">missing</a>\n";
	if($GI) {
		$body .= "<br />\n";
		$body .= "<a href=\"insert.php\" title=\"new\">new</a>\n";
	}
	$body .= "</div>\n";
	$body .= "</div>\n";
//
$body .= "<div>\n";
$body .= "<table>\n";
	//// table head
	$body .= "<tr class=\"header\">\n";
	if($GI) {$body .= "<td rowspan=\"2\" class=\"edit\"></td>\n";}
	$body .= "<th rowspan=\"2\">Name</th>\n";
	$body .= "<th colspan=\"2\">Players number</th>\n";
	$body .= "<th rowspan=\"2\">Age</th>\n";
	$body .= "<th rowspan=\"2\">Comment</th>\n";
	$body .= "</tr>\n";
	$body .= "<tr>\n";
	$body .= "<th>Min</th>\n";
	$body .= "<th>Max</th>\n";
	$body .= "</tr>\n";
//
$sql = $page->DB_QueryAlpha("games", "name");
if($sql->num_rows == 0) {
	$body .= "<tr><td colspan=\"";
	if($GI) {
		$body .= 6;
	} else {
		$body .= 5;
	}
	$body .= "\">\n";
	$body .= "No games...\n";
	$body .= "</td></tr>\n";
} else {
	//$sql->store_result();
	//$sql->bind_result($id, $name, $minP, $maxP, $age, $comment, $name_nodet);
	while($g = $sql->fetch_object()) {
		$id = $g->id;
		$name = $g->name;
		$minP = $g->minP;
		$maxP = $g->maxP;
		$age = $g->age;
		$comment = $g->comment;
		$body .= "<tr id=\"g$id";
		if($g->borrowed) {
			$body .= " away";
		}
		$body .= "\">\n";
		if($GI) {
			$body .= "<td class=\"edit\">\n";
			$body .= "<a href=\"insert.php?id=$id\" title=\"edit $name\">edit</a>\n";
			$body .= "&nbsp;\n";
			if($g->borrowed) {
				$body .= "<a href=\"index.php?back=$id\" title=\"back\">back</a>\n";
				$body .= "&nbsp;-&nbsp;";
				$body .= "<a href=\"../missings/index.php?view=games$id#games$id\" title=\"who\">who";
			} else {
				$body .= "<a href=\"../missings/insert.php?db=games&amp;id=$id\" title=\"borrow\">borrow</a>\n";
			}
			$body .= "</td>\n";
		}
		$body .= "<td class=\"name\">$name</td>\n";
		if($minP == $maxP) {
			$body .= "<td class=\"minPmaxP\" colspan=\"2\">$minP</td>\n";
		} else {
			$body .= "<td class=\"minP\">$minP</td>\n";
			$body .= "<td class=\"maxP\">$maxP</td>\n";
		}
		$body .= "<td class=\"age\">$age</td>\n";
		$body .= "<td class=\"comment\">$comment</td>\n";
		$body .= "</tr>\n";
	}
}
$sql->close();

$body .="</table>\n";
$body .= "</div>\n";


//// Finish
echo $body;
unset($page);
?>
