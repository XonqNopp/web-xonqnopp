<?php
require("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->dbHelper->init();

require("{$funcpath}_local/borrowback.php");

//require("$funcpath/fetch_from_imdb.php");
//$body .= fetch_IMDB("http://www.imdb.fr/title/tt0112864/");
//$body .= "\n\n";

// Borrowed item came home
if(isset($_GET["back"])) {
	$backId = NULL;
	if(isset($_GET["id"])) {
		$backId = $_GET["id"];
	}
	borrow_back($page, "dvds", $_GET["back"], $backId);
}

$page->cssHelper->dirUpWing();

$GI = $page->loginHelper->userIsAdmin();

$getcount = $page->dbHelper->queryManage("SELECT COUNT(*) AS `the_count` FROM `dvds`");
$fetch_count = $getcount->fetch_object();
$dvd_count = $fetch_count->the_count;
$getcount->close();

$body = $page->bodyHelper->goHome(NULL, "..");
$body .= $page->htmlHelper->setTitle("My $dvd_count DVDs");
$page->htmlHelper->hotBooty();

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
$dvds = $page->dbHelper->queryManage("SELECT *, " . $page->dbHelper->sortAlpha("title") . " FROM `dvds` WHERE `category` <> 'tvserie' AND `title` <> '' ORDER BY " . $page->dbHelper->orderAlpha("title"));
$series = $page->dbHelper->queryManage("SELECT *, " . $page->dbHelper->sortAlpha("serie") . ", " . $page->dbHelper->sortAlpha("title") . " FROM `dvds` WHERE `category` = 'tvserie' OR `serie` <> '' ORDER BY " . $page->dbHelper->orderAlpha("serie") . ", `number` ASC, " . $page->dbHelper->orderAlpha("title"));
$series_count = $page->dbHelper->queryManage("SELECT COUNT(DISTINCT(serie)) AS `sc` FROM `dvds`");
$sc = $series_count->fetch_object();
$series_count->close();
$N = $sc->sc - 1.0;
if($dvds->num_rows == 0) {
	$body .= "Sorry, no result to display...";
} else {
	// Results
	if($series->num_rows > 0) {
		// Series
		$body .= "<!--    SERIES   -->\n";
		$body .= "<h3 class=\"dvd_display\">Series</h3>\n";
		$body .= $page->tableHelper->open("dvd_display_table_serie");
		$body .= $page->tableHelper->rowOpen();
		$body .= $page->tableHelper->cellOpen();
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
					$body .= $page->tableHelper->cellClose();
					$body .= $page->tableHelper->cellOpen();
				}
				$body .= "<div class=\"dvd_display_table_serie_item\">\n";
				$id = $volume->id;
				$body .= "<a href=\"serie_display.php?id=$id\" title=\"$serie\">$serie</a>\n";
				$body .= "</div>\n";
			}
		}
		$body .= $page->tableHelper->cellClose();
		$body .= $page->tableHelper->rowClose();
		$body .= $page->tableHelper->close();

		$body .= "<!--    DVDs     -->\n";
		$body .= "<h3 class=\"dvd_display\">DVDs</h3>\n";
	}
	//// Individual DVDs (including those in series)
	$N = $dvds->num_rows;
	$dvd_width = 2;
	$body .= $page->tableHelper->open("dvd_display_table");
	$body .= $page->tableHelper->rowOpen();
	$body .= $page->tableHelper->cellOpen("dvd_display_table_cell");
	$dvd_index = 0;
	while($dvd = $dvds->fetch_object()) {
		$dvd_index++;

		if($dvd_index > $N * 1.0 / $dvd_width) {
			$dvd_index = 0;
			$body .= $page->tableHelper->cellClose();
			$body .= $page->tableHelper->cellOpen("dvd_display_table_cell");
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

	$body .= $page->tableHelper->cellClose();
	$body .= $page->tableHelper->rowClose();
	$body .= $page->tableHelper->close();
}
$dvds->close();
$series->close();

echo $body;
unset($page);
?>
