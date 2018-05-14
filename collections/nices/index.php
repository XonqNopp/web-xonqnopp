<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->initDB();

$page->CSS_ppJump(2);
$page->CSS_ppWing();

$GI = $page->UserIsAdmin();

$body = "";
$args = new stdClass();
$args->page = "..";
$body .= $page->GoHome($args);
$body .= $page->SetTitle("All the nice places I visited");
$page->HotBooty();

$body .= "<div class=\"wide\">\n";
$body .= "<div class=\"lhead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"chead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"rhead\">\n";
if($GI) {
// Propose to add a new if authorized
	$body .= "<a href=\"insert.php\" title=\"new nice place\">new nice place</a>\n";
}
$body .= "</div>\n";
$body .= "</div>\n";

$body .= "<div class=\"center\">All on my <a target=\"_blank\" href=\"https://www.tripadvisor.com/members/XonqNopp#CITY_TILES\" title=\"TripAdvisor profile\">TripAdvisor profile</a>, if not on TripAdvisor check below.</div>\n";
$body .= "<div>&nbsp;</div>\n";

// Display
$nices = $page->DB_QueryManage("SELECT *, " . $page->DB_SortAlpha("name") . " FROM `nices` ORDER BY `country` ASC, `city` ASC, " . $page->DB_OrderAlpha("name"));// `canton` ASC
$N = $nices->num_rows;
if($N == 0) {
	$body .= "Sorry, no result to display...";
} else {
	$old_country = "";
	$nice_width = 2;
	$index = 0;
	$body .= "<div class=\"csstab64_table nice_display_table\">\n";
	$body .= "<div class=\"csstab64_row\">\n";
	$body .= "<div class=\"csstab64_cell\">\n";
	while($nice = $nices->fetch_object()) {
		$index++;
		if($index > $N * 1.0 / $nice_width) {
			$index = 0;
			$body .= "</div>\n";
			$body .= "<div class=\"csstab64_cell\">\n";
		}
		$id = $nice->id;
		$name    = $nice->name;
		$city    = $nice->city;
		$country = $nice->country;
		if($country != "") {
			$country = " ($country)";
		}
		$loc = "$city$country";
		$body .= "<div id=\"r$id\" class=\"flushleft\">\n";
		if($GI) {
			$body .= "<div class=\"InB EditBorrow BookCell\">\n";
			$body .= "<a href=\"insert.php?id=$id\" title=\"edit\">edit</a>\n";
			$body .= "</div>\n";
		}
		$body .= "<div class=\"InB MainBook BookCell\">\n";
		$body .= "<a href=\"display.php?id=$id\" title=\"$name\">\n";
		$body .= "$name\n";
		if($loc != "") {
			$body .= "&nbsp;-&nbsp;\n";
			$body .= "<span class=\"author_link\">$loc</span>\n";
		}
		$body .= "</a>\n";
		$body .= "</div>\n";
		$body .= "</div>\n";
	}
	$body .= "</div>\n";
	$body .= "</div>\n";
	$body .= "</div>\n";
	//
}
$nices->close();

$page->show($body);
unset($page);
?>
