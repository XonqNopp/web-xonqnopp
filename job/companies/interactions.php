<?php
/*** Created: Thu 2015-04-30 07:22:16 CEST
 * TODO:
 *
 */
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require("dicts.php");
$page = new PhPage($rootPath);
$page->NotAllowed();
$page->initDB();
//$page->LogLevelUp(6);
$body = "";
//
	//// DisplayComco
	function DisplayComco($page, $item, $now, $cname = "") {
		$back = "";
		$sid = $item->id;
		$cid = $item->company;
		$timestamp = $item->timestamp;
		$who = $item->who;
		$media = $item->media;
		$way = $item->way;
		$kind = $item->kind;
		$content = $item->content;
		//
		$timestamp = substr($timestamp, 0, 10);
		$year  = substr($timestamp, 0, 4) + 0;
		$month = substr($timestamp, 5, 2) + 0;
		$day   = substr($timestamp, 8, 2) + 0;
		$timestamp = $page->Months($month) . "&nbsp;$day";
		if($year != $now->year) {
			$timestamp .= ", $year";
		}
		//
		$back .= "<div class=\"csstab64_row\">\n";
			//// edit
			$back .= "<div class=\"csstab64_cell\">\n";
			$back .= "<a class=\"edit\" href=\"communication.php?id=$sid\" title=\"edit\">edit</a>\n";
			$back .= "</div>\n";
		//
			//// company name (if required)
			if($cname == "") {
				$com_sql = $page->DB_QueryManage("SELECT * FROM `companies` WHERE `id` = $cid");
				$com_obj = $com_sql->fetch_object();
				$com_sql->close();
				$cname = $com_obj->name;
				$back .= "<div class=\"csstab64_cell company_name\">\n";
				$back .= "<a href=\"display.php?id=$cid\" title=\"$cname\">$cname</a>\n";
				$back .= "</div>\n";
			}
		//
			//// way
			$back .= "<div class=\"csstab64_cell way\">\n";
			$back .= "<img alt=\"$way\" src=\"$way.png\" />\n";
			$back .= "</div>\n";
		//
			//// timestamp
			$back .= "<div class=\"csstab64_cell timestamp\">\n";
			$back .= "$timestamp\n";
			$back .= "</div>\n";
		//
			//// who
			$back .= "<div class=\"csstab64_cell who\">\n";
			$back .= "$who\n";
			$back .= "</div>\n";
		//
			//// media
			$back .= "<div class=\"csstab64_cell media\">\n";
			$back .= "<img alt=\"$media\" title=\"$media\" src=\"$media.png\" />\n";
			$back .= "</div>\n";
		//
			//// kind
			$back .= "<div class=\"csstab64_cell kind\">\n";
			$back .= "<img alt=\"$kind\" title=\"$kind\" src=\"$kind.png\" />\n";
			$back .= "</div>\n";
		//
			//// content
			$back .= "<div class=\"csstab64_cell content\">\n";
			$back .= $page->SQL2URL($content) . "\n";
			$back .= "</div>\n";
		//
		$back .= "</div>\n";
		return $back;
	}
//
$chrono = true;
$page_title = "Chronological interactions";
if(isset($_GET["sort"]) && $_GET["sort"] == "companies") {
	$chrono = false;
	$page_title = "Interactions by companies";
}

$sum = $page->DB_GetCount("comco");
$page_title .= " ($sum)";

$page->CSS_ppJump(2);
$page->CSS_ppWing(2);
$oldLang = $page->ChangeSessionLang("english");

$gohome = new stdClass();
$gohome->rootpage = "../..";
$body .= $page->GoHome($gohome);
$body .= $page->SetTitle($page_title);// before HotBooty
$page->HotBooty();
//
	//// heads
	$body .= "<div class=\"wide\">\n";
	$body .= "<div class=\"lhead\">\n";
	$body .= "</div>\n";
	$body .= "<div class=\"chead\">\n";
	$body .= "</div>\n";
	$body .= "<div class=\"rhead\">\n";
	if($chrono) {
		//// sort by companies
		$body .= "<a href=\"interactions.php?sort=companies\" title=\"sort by companies\">sort by companies</a>\n";
	} else {
		//// sort chronologically
		$body .= "<a href=\"interactions.php\" title=\"sort chronologically\">sort chronologically</a>\n";
	}
	$body .= "</div>\n";
	$body .= "</div>\n";
//
$now = $page->GetNow();
$body .= "<div class=\"csstab64_table comco\">\n";
if($chrono) {
	//// chronological
	$sql = $page->DB_QueryManage("SELECT * FROM `comco` ORDER BY `timestamp` DESC, `company` ASC");
	$last = 999999;
	while($item = $sql->fetch_object()) {
		$timestamp = $item->timestamp;
		$year  = substr($timestamp, 0, 4);
		$month = substr($timestamp, 5, 2);
		$new = "$year$month";
		$new += 0;
		if($new < $last) {
			$last = $new;
			$body .= "<div class=\"csstab64_row\">\n";
			$body .= "<h2 class=\"csstab64_h2\">\n";
			$body .= $page->Months($month+0);
			$body .= " $year\n";
			$body .= "</h2>\n";
			$body .= "</div>\n";
		}
		$body .= DisplayComco($page, $item, $now);
	}
	$sql->close();
} else {
	//// companies
	$com_sql = $page->DB_QueryManage("SELECT * FROM `companies` ORDER BY `name` ASC");
	while($c = $com_sql->fetch_object()) {
		$cid = $c->id;
		$name = $c->name;
		$sql = $page->DB_QueryManage("SELECT * FROM `comco` WHERE `company` = $cid ORDER BY `timestamp` DESC");
		if($sql->num_rows > 0) {
			$body .= "<div class=\"csstab64_row\">\n";
			$body .= "<h2 class=\"csstab64_h2\">\n";
			$body .= "<a href=\"display.php?id=$cid\" title=\"$name\">$name</a>\n";
			$body .= "</h2>\n";
			$body .= "</div>\n";
			while($item = $sql->fetch_object()) {
				$body .= DisplayComco($page, $item, $now, $name);
			}
		}
		$sql->close();
	}
	$com_sql->close();
}
$body .= "</div>\n";




$body .= "</div>\n";


$page->ChangeSessionLang($oldLang);
$page->show($body);
unset($page);
?>
