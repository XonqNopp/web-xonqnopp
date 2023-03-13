<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->initDB();

require("${funcpath}_local/borrowback.php");

//require("$funcpath/fetch_from_imdb.php");
//$body .= fetch_IMDB("http://www.imdb.fr/title/tt0112864/");
//$body .= "\n\n";

// Borrowed item came home
borrow_back($page, "dvds");

$page->CSS_ppJump(2);
$page->CSS_ppWing();

$GI = $page->UserIsAdmin();

$getcount = $page->DB_QueryManage("SELECT COUNT(*) AS `the_count` FROM `dvds`");
$fetch_count = $getcount->fetch_object();
$dvd_count = $fetch_count->the_count;
$getcount->close();

$body = "";

$args = new stdClass();
$args->page = "..";
$body .= $page->GoHome($args);
$body .= $page->SetTitle("My $dvd_count DVDs");
$page->HotBooty();

$body .= "<div class=\"wide\">\n";
$body .= "<div class=\"lhead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"chead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"rhead\">\n";
$body .= "<a href=\"../missings/index.php?view=dvds\" title=\"Missing DVDs\">Missing DVDs</a>\n";
if($GI) {
	$body .= "<br />\n";
	// Propose to add a new if authorized
	$body .= "<a href=\"insert.php\" title=\"New DVD\">New DVD</a>\n";
	//$body .= "<a href=\"../insert_barcode.php\" title=\"New barcode\">New barcode</a><br />\n";
	//$body .= "<a href=\"../insert_imdb.php\" title=\"New IMDB\">New IMDB</a>\n";
}
$body .= "</div>\n";
$body .= "</div>\n";

//// Display
$dvds = $page->DB_QueryManage("SELECT *, " . $page->DB_SortAlpha("title") . " FROM `dvds` WHERE `category` <> 'tvserie' AND `title` <> '' ORDER BY " . $page->DB_OrderAlpha("title"));
$series = $page->DB_QueryManage("SELECT *, " . $page->DB_SortAlpha("serie") . ", " . $page->DB_SortAlpha("title") . " FROM `dvds` WHERE `category` = 'tvserie' OR `serie` <> '' ORDER BY " . $page->DB_OrderAlpha("serie") . ", `number` ASC, " . $page->DB_OrderAlpha("title"));
$series_count = $page->DB_QueryManage("SELECT COUNT(DISTINCT(serie)) AS `sc` FROM `dvds`");
$sc = $series_count->fetch_object();
$series_count->close();
$N = $sc->sc - 1.0;
if($dvds->num_rows == 0) {
	$body .= "Sorry, no result to display...";
} else {
	//// Results
	if($series->num_rows > 0) {
		//// Series
		$body .= "<!--    SERIES   -->\n";
		$body .= "<h3 class=\"dvd_display\">Series</h3>\n";
		$body .= "<div class=\"csstab64_table dvd_display_table_serie\">\n";
		$body .= "<div class=\"csstab64_row\">\n";
		$body .= "<div class=\"csstab64_cell\">\n";
		$old_serie = "";
		$serie_width = 3;
		$serie_index = 0;
		while($volume = $series->fetch_object()) {
			$serie = $volume->serie;
			if($serie != $old_serie) {
				$old_serie = $serie;
				$serie_index++;
				if($serie_index > $N / $serie_width) {
					$serie_index = 0;
					$body .= "</div>\n";
					$body .= "<div class=\"csstab64_cell\">\n";
				}
				$body .= "<div class=\"dvd_display_table_serie_item\">\n";
				$id = $volume->id;
				$body .= "<a href=\"serie_display.php?id=$id\" title=\"$serie\">$serie</a>\n";
				$body .= "</div>\n";
			}
		}
		$body .= "</div>\n";
		$body .= "</div>\n";
		$body .= "</div>\n";
		////
		$body .= "<!--    DVDs     -->\n";
		$body .= "<h3 class=\"dvd_display\">DVDs</h3>\n";
	}
	//// Individual DVDs (including those in series)
	$N = $dvds->num_rows;
	$dvd_width = 2;
	$body .= "<div class=\"csstab64_table dvd_display_table\">\n";
	$body .= "<div class=\"csstab64_row\">\n";
	$body .= "<div class=\"csstab64_cell dvd_display_table_cell\">\n";
	$dvd_index = 0;
	while($dvd = $dvds->fetch_object()) {
		$dvd_index++;
		if($dvd_index > $N * 1.0 / $dvd_width) {
			$dvd_index = 0;
			$body .= "</div>\n";
			$body .= "<div class=\"csstab64_cell dvd_display_table_cell\">\n";
		}
		$id = $dvd->id;
		$title = $dvd->title;
		$body .= "<div id=\"dvd$id\" class=\"flushleft";
		if($dvd->borrowed) {
			$body .= " away";
		}
		$body .= "\">\n";
		if($GI) {
			$body .= "<div class=\"InB EditBorrow\">\n";
			$body .= "<a href=\"insert.php?id=$id\" title=\"edit\">edit</a>\n";
			$body .= "&nbsp;\n";
			if($dvd->borrowed) {
				$body .= "<a href=\"index.php?back=$id\" title=\"back\">back</a>\n";
				$body .= "&nbsp;-&nbsp;";
				$body .= "<a href=\"../missings/index.php?view=dvds$id#dvds$id\" title=\"who\">who";
			} else {
				$body .= "<a href=\"../missings/insert.php?db=dvds&amp;id=$id\" title=\"borrow\">borrow</a>\n";
			}
			$body .= "</div>\n";
		}
		$body .= "<div class=\"InB MainBook\">\n";
		$body .= "<a href=\"display.php?id=$id\" title=\"$title\">$title</a>\n";
		$body .= "</div>\n";
		$body .= "</div>\n";
	}
	$body .= "</div>\n";
	$body .= "</div>\n";
	$body .= "</div>\n";
}
$dvds->close();
$series->close();

$page->show($body);
unset($page);
?>
