<?php
/*** Created: Thu 2015-07-16 08:39:55 CEST
 * TODO:
 */
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->initDB();
require("functions.php");
//// debug
//$page->initHTML();
//$page->LogLevelUp(6);
//// CSS paths
$page->CSS_ppJump(2);
$page->CSS_ppWing(2);
$GI = $page->UserIsAdmin();
//// init body
$body = "";
$page_title = "Liste des partitions";

$jerry = "";
if(isset($_GET["cat"])) {
	$jerry = $_GET["cat"];
	$page_title .= " (" . $dogs[$jerry] . ")";
}

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
	if($GI) {
		$body .= "<a href=\"insert.php\" title=\"new\">new</a>\n";
	}
	$body .= "</div>\n";
	$body .= "<div class=\"chead\">\n";
	$body .= "</div>\n";
	$body .= "<div class=\"rhead\">\n";
	$br = true;
	if($jerry != "") {
		$br = false;
		$body .= "<a href=\"index.php\" title =\"TOUTES\">TOUTES</a>\n";
		$body .="<br />&nbsp;\n";
	}
	foreach($dogs as $mouse => $cat) {
		if($jerry != $mouse) {
			if($br) {
				$br = false;
			} else {
				$body .= "<br />\n";
			}
			$body .= "<a href=\"index.php?cat=$mouse\" title=\"$cat\">$cat</a>\n";
		}
	}
	$body .= "</div>\n";
	$body .= "</div>\n";
//
$body .= "<div>\n";
$body .= "<table>\n";
	//// table head
	$body .= "<tr class=\"header\">\n";
	if($GI) {$body .= "<td rowspan=\"2\" class=\"edit\"></td>\n";}
	$body .= "<th rowspan=\"2\">Titre</th>\n";
	$body .= "<th rowspan=\"2\">Oeuvre</th>\n";
	$body .= "<th rowspan=\"2\">Auteur</th>\n";
	$body .= "<th rowspan=\"2\">Ann&eacute;e</th>\n";
	$body .= "<th rowspan=\"2\"># pages</th>\n";
	$body .= "<th rowspan=\"2\">SATB</th>\n";
	$body .= "<th rowspan=\"2\">Origine</th>\n";
	$body .= "<th colspan=\"" . count($dogs) . "\">Cat&eacute;gories</th>\n";
	$body .= "<th rowspan=\"2\">Commentaire</th>\n";
	$body .= "</tr>\n";
	$body .= "<tr>\n";
	foreach($dogs as $mouse => $cat) {
		$body .= "<th class=\"cattitle\"><div class=\"cattitle\">$cat</div></th>\n";
	}
	$body .= "</tr>\n";
//
$oldCapital = "";
$MoreRows = 3;
$fields = array(
	"title" => "a",
	"opus"  => "a", //// to be checked with DB
	"year"  => "",
	"id"    => ""
);
	//// prepare query string
	$query = "";
	$queryselect  = "";
	$queryselect .= "SELECT *";
	$queryorder  = "";
	$queryorder .= "ORDER BY ";
	foreach($fields as $field => $way) {
		if($queryorder != "ORDER BY ") {
			$queryorder .= ", ";
		}
		if(substr($way, 0, 1) == "a") {
			$queryselect .= ", " . $page->DB_SortAlpha($field);
			$way = substr($way, 1);
			if($way == "") {
				$way = "ASC";
			}
			$queryorder .= $page->DB_OrderAlpha($field, $way);
		} else {
			if($way == "") {
				$way = "ASC";
			}
			$queryorder .= " `$field` $way";
		}
	}
	$queryselect .= "FROM `SheetMusic`";
$sql = null;
if($jerry == "") {
	$query = "$queryselect $queryorder";
	$sql = $page->DB_QueryPrepare($query);
} else {
	$queryselect .= " WHERE `categories` LIKE ?";
	$query = "$queryselect $queryorder";
	$sql = $page->DB_QueryPrepare($query);
	$jerrylike = "%" . "$jerry%";
	$sql->bind_param("s", $jerrylike);
}
$page->DB_ExecuteManage($sql);
//$sql->store_result();
$sql->bind_result($id, $title, $author, $opus, $year, $pages, $SATB, $origin, $categories, $comment, $title_nodet, $opus_nodet);
while($sql->fetch()) {
	$capital = strtoupper(substr($title_nodet, 0, 1));
	if($oldCapital != $capital) {
		if($oldCapital != "") {
			for($i = 0; $i < $MoreRows; $i++) {
				$body .= "<tr class=\"more\">\n";
				if($GI) {$body .= "<td class=\"edit\"></td>\n";}
				$body .= "<td>&nbsp;</td>\n";
				$body .= "<td></td>\n";
				$body .= "<td></td>\n";
				$body .= "<td></td>\n";
				$body .= "<td></td>\n";
				$body .= "<td></td>\n";
				$body .= "<td></td>\n";
				for($j = 0; $j < count($dogs); $j++) {
					$body .= "<td></td>\n";
				}
				$body .= "<td></td>\n";
				$body .= "</tr>\n";
			}
		}
		$oldCapital = $capital;
		$body .= "<tr id=\"$capital\">\n";
		if($GI) {$body .= "<td class=\"edit\"></td>\n";}
		$body .= "<td colspan=\"" . (8+count($dogs)) . "\" class=\"capital\">$capital</td>\n";
		$body .= "</tr>\n";
	}
	//
	$body .= "<tr id=\"m$id\">\n";
	if($GI) {
		$body .= "<td class=\"edit\"><a href=\"insert.php?id=$id\" title=\"edit $title\">edit</a></td>\n";
	}
	$body .= "<td class=\"title\">$title</td>\n";
	$body .= "<td class=\"opus\">$opus</td>\n";
	$body .= "<td class=\"author\">$author</td>\n";
	$body .= "<td class=\"year\">$year</td>\n";
	$body .= "<td class=\"pages\">$pages</td>\n";
	$body .= "<td class=\"SATB\">$SATB</td>\n";
	$body .= "<td class=\"origin\">$origin</td>\n";
	foreach($dogs as $mouse => $cat) {
		if(preg_match("/{$mouse}/", $categories)) {
			$body .= "<td class=\"$cat cattrue\">x</td>\n";
		} else {
			$body .= "<td class=\"$cat catfalse\"></td>\n";
		}
	}
	$body .= "<td class=\"comment\">$comment</td>\n";
	$body .= "</tr>\n";
}
$sql->close();

	//// add more lines to write new sheets
	for($i = 0; $i < $MoreRows; $i++) {
		$body .= "<tr class=\"more\">\n";
		if($GI) {$body .= "<td class=\"edit\"></td>\n";}
		$body .= "<td>&nbsp;</td>\n";
		$body .= "<td></td>\n";
		$body .= "<td></td>\n";
		$body .= "<td></td>\n";
		$body .= "<td></td>\n";
		$body .= "<td></td>\n";
		$body .= "<td></td>\n";
		for($j = 0; $j < count($dogs); $j++) {
			$body .= "<td></td>\n";
		}
		$body .= "<td></td>\n";
		$body .= "</tr>\n";
	}

$body .="</table>\n";
$body .= "</div>\n";

$body .= "<div class=\"timestamp\">{$page->GetNow()->timestamp}</div>\n";


//// Finish
echo $body;
unset($page);
?>
