<?php
/*** Created: Thu 2014-08-07 15:06:56 CEST
 ***
 *** TODO:
 ***
 ***/
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->initDB();
//$page->LogLevelUp(6);
$page->CSS_ppJump(2);
$page->CSS_ppWing();
//
require("categories.php");

$cats = GetCats();
$alphabet = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
$UserIsAdmin = $page->UserIsAdmin();

$body = "";
$args = new stdClass();
$args->page = "..";
//$args->rootpage = "../..";
$body .= $page->GoHome($args);
//
	/*** new favourite ***/
	if($UserIsAdmin && isset($_GET["NewFav"])) {
		$NewFav = $_GET["NewFav"];
		$FavVal = 1;
		if($NewFav < 0) {
			$FavVal = 0;
			$NewFav = -$NewFav;
		}
		if($NewFav > 0) {
			$q = $page->DB_QueryPrepare("UPDATE `quotations` SET `fav` = ? WHERE `id` = ? LIMIT 1;");
			$q->bind_param("ii", $FavVal, $NewFav);
			$page->DB_ExecuteManage($q);// no header because link should be .php?NewFav=-0#c0
		}
	}
//
	/*** Prepare query ***/
	if(isset($_GET["random"])) {
		/*** random entry ***/
		$randid = $page->DB_RandomEntry("quotations");
		$query = "SELECT * FROM `quotations` WHERE `id` = $randid";
	} else {
		/*** query and order ***/
			/*** prepare sorting order ***/
			$sorting = "";
			$sc = "";
			if(isset($_POST["sorting"])) {
				$sorting = $_POST["sorting"];
			}
			if(isset($_POST["sc"])) {
				$sc = $_POST["sc"];
			}
			$by = "ASC";
			if($sc == "dsc") {
				$by = "DESC";
			}
			// default is "alast"
			$order = "`authorlast` $by, `authorfirst` ASC, `place` ASC, `quote` ASC";
			if($sorting == "afirst") {
				$order = "`authorfirst` $by, `authorlast` ASC, `place` ASC, `quote` ASC";
			} elseif($sorting == "place") {
				$order = "`place` $by, `authorlast` ASC, `authorfirst` ASC, `quote` ASC";
			} elseif($sorting == "quote") {
				$order = "`quote` $by, `authorlast` ASC, `authorfirst` ASC, `place` ASC";
			} elseif($sorting == "date") {
				$order = "`id` $by";
			}
			$order = " ORDER BY $order";
		//
		$favq = "";
		$catq = "";
		if(isset($_POST["cats"])) {
			if(in_array("fav", $_POST["cats"])) {
				$favq = "`fav` = '1'";
			}
			foreach($cats as $dog) {
				if(in_array($dog, $_POST["cats"])) {
					if($catq != "") {
						$catq .= " AND ";
					}
					$catq .= "`$dog` = '1'";
				}
			}
		}
		/*
		$keyword = "";
		if(isset($_POST["keyword"])) {
			$keyword = $page->field2SQL($_POST["keyword"]);
		}
		$query = "";
		if($keyword != "") {
			$query .= "(";
			$query .= "`quote` LIKE '%$keyword%'";
			$query .= " OR ";
			$query .= "`authorlast` LIKE '%$keyword%'";
			$query .= " OR ";
			$query .= "`authorfist LIKE '%$keyword%'";
			$query .= " OR ";
			$query .= "`place` LIKE '%$keyword%'";
			$query .= ")";
		}
		 */
		if($favq != "") {
			if($query != "") {
				$query .= " AND ";
			}
			$query .= $favq;
		}
		if($catq != "") {
			if($query != "") {
				$query .= " AND ";
			}
			$query .= $catq;
		}
		if($query != "") {
			$query = " WHERE $query";
		}
		$query = "SELECT * FROM `quotations`$query$order";
	}
//
$result = $page->DB_QueryManage($query);

$body .= $page->SetTitle("Citations");
$page->HotBooty();

$body .= $page->FormTag();
$body .= "<div id=\"gael\">\n";

	$body .= "<div class=\"wide\">\n";
	$body .= "<div class=\"lhead\">\n";
	$body .= "</div>\n";
	$body .= "<div class=\"chead\">\n";
		/*** Alphabet links ***/
		$body .= "<div class=\"headlinks\">\n";
		foreach($alphabet as $letter) {
			$body .= "<a href=\"#$letter\">" . strtoupper($letter) . "</a>\n";
			if($letter != "z") {
				$body .= "&nbsp;\n";
			}
		}
		$body .= "</div>\n";
	$body .= "</div>\n";
	/*** right head ***/
	$body .= "<div class=\"rhead\">\n";
	// [new] rand search order
	if($UserIsAdmin) {
		// new edit fav
		$body .= "<a class=\"blue\" href=\"insert.php\" title=\"New quote\">new</a>\n";
		$body .= "<br />\n";
	}
	//
	if(isset($_GET["random"]) || isset($_GET["search"]) || isset($_POST["search"])) {
		$body .= "<a class=\"blue\" href=\"index.php\" title=\"Toutes les citations\">toutes</a>\n";
		$body .= "&nbsp;-&nbsp;\n";
	}
		/*** random ***/
		$body .= "<a class=\"blue\" href=\"index.php?random\" title=\"hasard\">hasard</a>\n";
	//
		/*** search link ***/
		//$body .= "&nbsp;-&nbsp;\n";
		//$body .= "<a class=\"blue\" href=\"index.php?search\">search</a>\n";
	$body .= "<br />\n";
	//
		/*** order ***/
		$sorting = "";
		$sc = "";
		if(isset($_POST["sorting"])) {
			$sorting = $_POST["sorting"];
		}
		if(isset($_POST["sc"])) {
			$sc = $_POST["sc"];
		}
		$sl = " selected=\"selected\"";
		$ch = " checked=\"checked\"";
		if($sc == "dsc") {
			$dscd = $ch;
		} else {
			$ascd = $ch;
		}
		$body .= "<div id=\"sort\">\n";
		$body .= "<select name=\"sorting\" >\n";
		$body .= "<option value=\"alast\"";
		if($sorting == "alast" || $sorting == "") {
			$body .= $sl;
		}
		$body .= ">Nom</option>\n";
		$body .= "<option value=\"afirst\"";
		if($sorting == "afirst") {
			$body .= $sl;
		}
		$body .= ">Pr&eacute;nom</option>\n";
		$body .= "<option value=\"place\"";
		if($sorting == "place") {
			$body .= $sl;
		}
		$body .= ">Oeuvre</option>\n";
		$body .= "<option value=\"quote\"";
		if($sorting == "quote") {
			$body .= $sl;
		}
		$body .= ">Citation</option>\n";
		$body .= "<option value=\"date\"";
		if($sorting == "date") {
			$body .= $sl;
		}
		$body .= ">Date d'ajout</option>\n";
		$body .= "</select>\n";
		$body .= "<br />\n";

		$body .= "<input type=\"radio\" id=\"o_asc\" name=\"sc\" value=\"asc\"";
		if($sc == "asc" || $sc == "") {
			$body .= $ch;
		}
		$body .= " /><label for=\"o_asc\">&nbsp;croissant</label><br />\n";
		$body .= "<input type=\"radio\" id=\"o_dsc\" name=\"sc\" value=\"dsc\"";
		if($sc == "dsc") {
			$body .= $ch;
		}
		$body .= " /><label for=\"o_dsc\">&nbsp;d&eacute;croissant</label><br />\n";

		$body .= "<input type=\"submit\" value=\"Trier\" />\n";

		$body .= "</div>\n";
	//
	$body .= "</div>\n";
	$body .= "</div>\n";
//
$body .= "</div>\n";
//
	/*** search fields ***/
	if(isset($_GET["search"]) || isset($_POST["search"])) {
		$body .= "<div class=\"search\">\n";
		$checked = " checked=\"checked\"";

		$keyword = "";
		if(isset($_POST["keyword"])) {
			$keyword = $_POST["keyword"];
		}
		$body .= "<input type=\"text\" name=\"keyword\" value=\"$keyword\" size=\"13\" /><br />\n";
		$body .= "<input id=\"s_fav\" type=\"checkbox\" name=\"cats[]\" value=\"fav\"";
		if(in_array("fav", $_POST["cats"])) {
			$body .= $checked;
		}
		$body .= " /><label for=\"s_fav\">Favourite</label><br />\n";
		foreach($cats as $dog) {
			$body .= "<input id=\"s_$dog\" type=\"checkbox\" name=\"cats[]\" value=\"$dog\"";
			if(in_array($dog, $_POST["cats"])) {
				$body .= $checked;
			}
			$body .= " /><label for=\"s_$dog\">$dog</label><br />\n";
		}
		$body .= "<input type=\"submit\" name=\"search\" value=\"search\" />\n";
		$body .= "</div>\n";
	}
//
$body .= "</form>\n";

// here comes display
// TODO: if admin put edit/fav anyway

if($result->num_rows == 0) {
	$body .= "<div id=\"warning\">Il n'y a aucune citation r&eacute;pondant &agrave; ces crit&egrave;res...</div>\n";
} else {
	/*** main content ***/
	$last_author = "";
	$last_letter = "";
	$body .= "<div id=\"lebloc\">\n";
	$body .= "<table style=\"width: 100%; border: none;\">\n";
	// edit fav quote
	while($row = $result->fetch_object()) {
		$id = $row->id;
		$fav = $row->fav;
		$clf = "";
		if($fav) {$clf = " favclass";}
		$a_last = $row->authorlast;
		$a_first = $row->authorfirst;
		$authorlast = $a_last;
		$authorfirst = $a_first;
		$inter = " ";
		if(substr($authorfirst, -1) == "'") {
			$inter = "";
		}
		$new_author = "$authorfirst$inter$authorlast";
		if($new_author == " ") {
			$new_author = "Anonyme";
		}
		if($last_author != $new_author) {
			$body .= "<tr>\n";
			$body .= "<th colspan=\"3\">\n";
			$last_author = $new_author;
			$new_letter = strtolower(substr($a_last, 0, 1));
			$body .= "<!-- - - " . strtoupper($new_author) . " - - -->\n";
			$body .= "<div class=\"author\"";
			if($new_letter != $last_letter) {
				$body .= " id=\"$new_letter\"";
				$last_letter = $new_letter;
			}
			$body .= ">\n";
			//
			$al = "";
			$af = "";
			$with_link = false;
			if($a_last != "" ) {$al = "al=$a_last"; }
			if($a_first != "") {$af = "af=$a_first";}
			if($al != "" && $af != "" && $UserIsAdmin) {
				$body .= "<a class=\"author\" href=\"insert.php?$al&amp;$af\" title=\"New for $new_author\">\n";
				$with_link = true;
			} elseif(($al != "" || $af != "") && $UserIsAdmin) {
				$body .= "<a class=\"author\" href=\"insert.php?$al$af\" title=\"New for $new_author\">\n";
				$with_link = true;
			}
			$body .= "$new_author\n";
			if($with_link) {
				$body .= "</a>\n";
			}
			$body .= "</div>\n";
			$body .= "</th>\n";
			$body .= "</tr>\n";
		}
		$body .= "<tr>\n";
		/*** edit ***/
		$body .= "<td class=\"editbutton\">\n";
		if($UserIsAdmin) {
			$body .= "<a class=\"blue\" href=\"insert.php?id=$id\" title=\"edit\">edit</a>\n";
		}
		$body .= "</td>\n";
		//
		/*** fav ***/
		$body .= "<td class=\"editbutton\">\n";
		if($UserIsAdmin) {
			$corpse = "";
			$coroner = "favorize";
			if($fav) {
				$corpse = "-";
				$coroner = "un$coroner";
			}
			$body .= "<a class=\"blue\" href=\"index.php?NewFav=$corpse$id#c$id\" title=\"$coroner\">$coroner</a>";
		}
		$body .= "</td>\n";
		//
		/*** quote ***/
		$body .= "<td class=\"quotab\">\n";
		$body .= "<div class=\"quote$clf\" id=\"c$id\">\n";
		$body .= $row->quote;
		$body .= "</div>\n";
		if($row->place != "") {
			$body .= "<div class=\"place\">\n";
			$body .= $row->place;
			$body .= "</div>\n";
		}
		$body .= "</td>\n";
		//
		$body .= "</tr>\n";
	}
	$body .= "</table>\n";
	$body .= "</div>\n";
}

$result->close();

$page->show($body);
unset($page);
?>
