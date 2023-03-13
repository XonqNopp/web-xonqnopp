<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->initDB();

require("${funcpath}_local/borrowback.php");
$languages = array("fr" => "French", "en" => "English", "it" => "Italian", "de" => "German", "??" => "other");

// Borrowed item came home
borrow_back($page, "dvds");

$body = "";

$args = new stdClass();
$args->rootpage = "..";
$body .= $page->GoHome($args);

$id = $_GET["id"];
$query = $page->DB_IdManage("SELECT * FROM `dvds` WHERE `id` = ?", $id);
$query->store_result();
if($query->num_rows == 0) {
	$body .= "Sorry, no result...";
	$page_title = "No result";

} else {
	$query->bind_result($id, $title, $director, $actors, $languages, $subtitles, $duration, $serie, $number, $category, $summary, $burnt, $format, $borrowed);
	$query->fetch();

	$languages = explode(",", $languages);
	$subtitles = explode(",", $subtitles);

	$duration = (int)$duration;
	$number = (int)$number;

	if($category == "tvserie") {
		$args = new stdClass();
		$args->page = "serie_display";
		$args->id = $id;
		$args->rootpage = "..";
		$body = $page->GoHome($args);
	}
	if($borrowed == "1") {
		$isbor = " class=\"away\"";
	} else {
		$isbor = "";
	}
	// Display depends if TV serie (Serie Title) or not (Title (serie))
	if($category == "tvserie") {
		$page_title = $serie;
		if($number > 0) {
			$page_title = "$page_title $number";
		}
		if($title != "") {
			$page_title = "$page_title ($title)";
		}
	} else {
		$page_title = $title;
		if($serie != "") {
			$page_title = "$page_title ($serie";
			if($number > 0) {
				$page_title = "$page_title $number";
			}
			$page_title = "$page_title)";
		}
	}

	$the_languages = array("fr" => "French", "en" => "English", "it" => "Italian", "de" => "German", "zz" => "other");
	$cats = array("movie" => "Movie", "animation" => "Animation", "tvserie" => "TV Serie", "doc" => "Documentary", "humor" => "Humorist", "music" => "Musical", "memory" => "Memory");
	$formats = array("dvd" => "DVD", "blu" => "Blu-ray", "avi" => "AVI");

	// Print
	$body .= "<h1$isbor>$page_title</h1>\n";
	$body .= "<div class=\"wide\">\n";
	// L head
	$body .= "<div class=\"lhead\">\n";
	if($page->UserIsAdmin()) {
		// Edit
		$body .= "<a href=\"insert.php?id=$id\" title=\"edit\">edit</a><br />\n";
		// Propose to borrow or give back
		$body .= "<a href=\"";
		if($borrowed == 1) {
			// Back
			$body .= "display.php?id=$id&amp;back\" title=\"back\">back</a>";
			$body .= "&nbsp;-&nbsp;";
			$body .= "<a href=\"../missings/index.php?view=dvds$id#dvds$id\" title=\"who\">who";
		} else {
			// Borrow
			$body .= "../missings/insert.php?db=dvds&amp;id=$id\" title=\"borrow\">borrow";
		}
		$body .= "</a>\n";
	}
	$body .= "</div>\n";
	$body .= "<div class=\"chead\">\n";
	$body .= "</div>\n";
	// R head
	$body .= "<div class=\"rhead\">\n";
	$body .= "<a href=\"../missings/index.php?view=dvds\" title=\"Missing DVDs\">Missing DVDs</a>\n";
	// Search
	// Propose to add a new if authorized
	if($page->UserIsAdmin()) {
		$body .= "<br />\n";
		// Add
		$body .= "<a href=\"insert.php\" title=\"New DVD\">New DVD</a>\n";
		// IMDB
		//$body .= "<a href=\"../insert_barcode.php\" title=\"New IMDB\">New IMDB</a><br />\n";
	}
	$body .= "</div>\n";
	$body .= "</div>\n";
	$body .= "<div class=\"whole\">\n";
	$body .= "<div class=\"dvd_display_people\">\n";
	// Director
	$body .= "<div class=\"dvd_display_director\">";
	if($director != "") {
		$body .= "Director&nbsp;: $director";
	}
	$body .= "</div>\n";
	// Actors
	$body .= "<div class=\"dvd_display_actors\">";
	if($actors != "") {
		$body .= "Actors&nbsp;: $actors";
	}
	$body .= "</div>\n";
	$body .= "</div>\n";
	// Languages
	$body .= "<div class=\"dvd_display_lang\">\n";
	$body .= "<div class=\"dvd_display_languages\">";
	if($languages != array("")) {
		$tobody = "";
		$hm = 0;
		foreach($languages as $l) {
			if($tobody != "") {
				$tobody .= ", ";
			}
			$tobody .= $the_languages[$l];
			$hm++;
		}
		$body .= "Language";
		if($hm > 1) {
			$body .= "s";
		}
		$body .= "&nbsp;: ";
		$body .= $tobody;
	}
	$body .= "</div>\n";
	// Subtitles
	$body .= "<div class=\"dvd_display_subtitles\">";
	if($subtitles != array("")) {
		$tobody = "";
		$hm = 0;
		foreach($subtitles as $s) {
			if($tobody != "") {
				$tobody .= ", ";
			}
			$tobody .= $the_languages[$s];
			$hm++;
		}
		$body .= "Subtitle";
		if($hm > 1) {
			$body .= "s";
		}
		$body .= "&nbsp;: ";
		$body .= $tobody;
	}
	$body .= "</div>\n";
	$body .= "</div>\n";
	// Duration
	$body .= "<div class=\"dvd_display_duration\">";
	if($duration > 0) {
		if($duration / 60.0 < 1) {
			$body .= "$duration min";
		} else {
			$duration_h = floor($duration / 60.0);
			$duration_m = $duration % 60;
			if($duration_m == 0) {
				$duration_m = "00";
			} elseif($duration_m < 10) {
				$duration_m = "0$duration_m";
			}
			$body .= "$duration_h" . "h$duration_m";
		}
	}
	$body .= "</div>\n";
	// Category
	$body .= "<div class=\"dvd_display_category\">";
	if($category != "movie") {
		$body .= $cats[$category];
	}
	$body .= "</div>\n";
	// Burnt
	$body .= "<div class=\"dvd_display_burnt\">";
	if($burnt == "1") {
		$body .= "Burnt!!";
	}
	$body .= "</div>\n";
	// Format
	$body .= "<div class=\"dvd_display_format\">" . $formats[$format] . "</div>\n";
	// Summary
	$body .= "<div class=\"dvd_display_summary\">$summary</div>\n";
	$body .= "</div>\n";
}
$query->close();

$page->CSS_ppJump(2);
$page->CSS_ppWing();
$page->SetTitle($page_title);
$page->HotBooty();

$page->show($body);
unset($page);
?>
