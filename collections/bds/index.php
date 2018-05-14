<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->LogLevelUp(6);
$page->initDB();

$GI = $page->UserIsAdmin();

$page->CSS_ppJump(2);
$page->CSS_ppWing();

$getcount = $page->DB_QueryManage("SELECT COUNT(*) AS `the_count` FROM `bds`");
$fetch_count = $getcount->fetch_object();
$bd_count = $fetch_count->the_count;
$getcount->close();
$getcount = $page->DB_QueryManage("SELECT COUNT(*) AS `the_count` FROM `bd_series`");
$fetch_count = $getcount->fetch_object();
$serie_count = $fetch_count->the_count;
$getcount->close();

$body = "";
$args = new stdClass();
$args->page = "..";
$args->rootpage = "../..";
$body .= $page->GoHome($args);
$body .= $page->SetTitle("My $bd_count BDs in $serie_count series");
$page->HotBooty();

// Propose to add a new if authorized
$body .= "<div class=\"wide\">\n";
$body .= "<div class=\"lhead\">\n";
//$body .= "<a href=\"search.php\" title=\"Search\">Search</a>\n";
$body .= "</div>\n";
$body .= "<div class=\"chead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"rhead\">\n";
$body .= "<a href=\"../missings/index.php?view=bds\" title=\"Missing BDs\">Missing BDs</a>\n";
if($GI) {
	$body .= "<br />\n";
	//$body .= "<a href=\"../insert_isbn.php\" title=\"Add a BD by ISBN\">New ISBN</a><br />\n";
	$body .= "<a href=\"insert.php\" title=\"Add a BD\">New BD</a><br />\n";
	$body .= "<a href=\"serie_insert.php\" title=\"Add a BD serie\">New serie</a>\n";
}
$body .= "</div>\n";
$body .= "</div>\n";

//
$body .= "<div>\n";
//
$serie = "";
$query_series = $page->DB_QueryAlpha("bd_series", "name");
$N = $query_series->num_rows;
if($N == 0) {
	$body .= "Sorry, no result to display...";
} else {
	$serie_width = 4;
	$L = $N * 1.0 / $serie_width;
	$bd_series = array();
	$body .= "<div class=\"bd_display_table\">\n";
	$body .= "<div class=\"csstab64_table\">\n";
	$body .= "<div class=\"csstab64_row\">\n";
	$body .= "<div class=\"csstab64_cell stem_cell\">\n";
	$check = 0;
	while($serie = $query_series->fetch_object()) {
		$check++;
		if($check > $L) {
			$body .= "</div>\n";
			$body .= "<div class=\"csstab64_cell\">\n";
			$check = 0;
		}
		//if($serie !== null) {
			$body .= "<div class=\"bd_serie_item\">\n";
			$id = $serie->id;
			$name = $serie->name;
			$Nalbums = $serie->Nalbums;
			if($name == "") {
				$name = "Hors s&eacute;ries";
			}
			$thumb = $serie->thumb;
			$GetCount = $page->DB_IdManage("SELECT COUNT(*) AS `count` FROM `bds` WHERE `serie_id` = ?", $id);
			$GetCount->bind_result($count);
			$GetCount->fetch();
			$GetCount->close();
			//
			if($thumb != "") {
				$body .= "<div class=\"bd_serie_thumb\">\n";
				$body .= "<a href=\"serie_display.php?id=$id\" title=\"$name\">\n";
				$body .= "<img src=\"../pictures/bds/$thumb\" alt=\"$name\" />\n";
				$body .= "</a>\n";
				$body .= "</div>\n";
			}
			$body .= "<div class=\"bd_serie_name\">\n";
			$body .= "<a href=\"serie_display.php?id=$id\" title=\"$name\">\n";
			$body .= "$name\n";
			$body .= "</a>\n";
			$body .= "</div>\n";
			$body .= "<div class=\"bd_serie_count\">\n";
			$body .= "($count";
			if($Nalbums > 0) {
				//$ratio = round(100 * $count / $Nalbums);
				$body .= "&nbsp;/$Nalbums";
				//$body .= " =$ratio%";
			}
			$body .= ")\n";
			$body .= "</div>\n";
			$body .= "</div>\n";
		//}
	}
	$body .= "</div>\n";
	$body .= "</div>\n";
	$body .= "</div>\n";
	$body .= "</div>\n";
}
$query_series->close();
//
$body .= "</div>\n";
//

/*** Printing ***/
$page->show($body);
unset($page);
?>
