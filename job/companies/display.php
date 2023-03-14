<?php
/*** Created: Wed 2015-01-14 19:33:34 CET
 * TODO:
 *
 */
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require("dicts.php");
$page = new PhPage($rootPath);
$page->initDB();
//$page->LogLevelUp(6);
$body = "";

function SIround($val) {
	$mega = 1000000;
	$kilo =    1000;
	if($val >= $mega) {
		$rest = $val % $mega;
		$val = (int) ($val / $mega);
		$val = "{$val}M";
		if($rest > 0) {
			$val = "$val+";
		}
	} elseif($val >= $kilo) {
		$rest = $val % $kilo;
		$val = (int) ($val / $kilo);
		$val = "{$val}k";
		if($rest > 0) {
			$val = "$val+";
		}
	}
	return $val;
}

$page->CSS_ppJump(2);
$page->CSS_ppWing(2);
$GI = $page->UserIsAdmin();

if(!isset($_GET["id"])) {
	$page->HeaderLocation();
}

$id = $_GET["id"];

$SQL = $page->DB_IdManage("SELECT * FROM `companies` WHERE `id` = ?", $id);
$SQL->bind_result($id, $name, $location, $car_time, $train_time, $fields, $physicist, $contact, $HR, $peopleAll, $peopleCH, $peopleRD, $competitors, $website, $ranking, $comment);
$SQL->fetch();
$SQL->close();
$fields = implode(", ", fields(explode(",", $fields)));
$physicist = implode(", ", physicist(explode(",", $physicist)));
$peopleAll = SIround($peopleAll);
$peopleCH = SIround($peopleCH);
$peopleRD = SIround($peopleRD);





$people = "";
if($peopleAll > 0) {
	if($peopleCH > 0 || $peopleRD > 0) {
		$people = "all# $peopleAll - ";
	} else {
		$people = $peopleAll;
	}
}
if($peopleCH > 0) {
	$people .= "CH# $peopleCH";
	if($peopleRD > 0) {
		$people .= " - ";
	}
}
if($peopleRD > 0) {
	$people .= "R&amp;D# $peopleRD";
}
/*
if($website != "") {
	$webtxt = preg_replace("/^(https?:\/\/[^\/]+\/).*$/", "$1", $website);
	$website = "<a target=\"_blank\" href=\"$website\" title=\"$name\">$webtxt</a>";
}
 */


$gohome = new stdClass();
$gohome->rootpage = "../..";
$body .= $page->GoHome($gohome);
$body .= $page->SetTitle($name);// before HotBooty
$page->HotBooty();

$body .= "<div class=\"wide\">\n";
$body .= "<div class=\"lhead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"chead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"rhead\">\n";
if($GI) {
	$body .= "<a href=\"insert.php?id=$id\" title=\"edit\">edit</a><br />\n";
	$body .= "<a href=\"insert.php\" title=\"new\">new</a><br />\n";
	$body .= "<a href=\"interactions.php\" title=\"interactions\">interactions</a>\n";
}
$body .= "</div>\n";
$body .= "</div>\n";

$body .= "<div>\n";
$body .= "<ul>\n";
if($location != "") {
	$body .= "<li><b>Location:</b>\n";
	$locos = explode("/", $location);
	$first = 1;
	$cff = "";
	foreach($locos as $lolo) {
		$lolo = preg_replace("/^ +/", "", $lolo);
		$lolo = preg_replace("/ +$/", "", $lolo);
		if($first) {
			$first = 0;
		} else {
			$body .= ",";
		}
		$body .= " ";
		if($lolo == "worldwide") {
			$body .= $lolo;
		} else {
			$body .= "$lolo ";
			$body .= "(";
			$body .= "<a target=\"_blank\" href=\"http://maps.google.ch?";
			$Body .= "f=d";
			if($GI) {
				$body .= "&amp;saddr=champ+bonjard,+1782+belfaux";
			}
			$body .= "&amp;daddr=";
			$body .= preg_replace("/ /", "+", $lolo);
			$body .= "\" title=\"$lolo\">map</a>";
			$body .= ",";// &+-/;,
			$body .= "<a target=\"_blank\" href=\"";
			$body .= "http://fahrplan.sbb.ch/bin/query.exe/fn?";
			$body .= "start=yes";
			$body .= "&amp;REQ0JourneyStopsS0A=1";
			if($GI) {
				$body .= "&amp;REQ0JourneyStopsS0G=Belfaux%21";
			}
			$body .= "&amp;REQ0JourneyStopsZ0A=1";
			$body .= "&amp;REQ0JourneyStopsZ0G=$lolo%21";
			$body .= "&amp;REQ0JourneyProduct_opt_section_0_list=0%3A0000";
			$body .= "&amp;REQ0JourneyTime=06%3A00";
			//$body .= "&amp;REQ0JourneyTime=08%3A00";
			//$body .= "&amp;REQ0HafasSearchForw=1";
			$body .= "\" title=\"CFF\">CFF</a>";
			$body .= ")";
		}
	}
	/*
	if($car_time > 0 || $train_time > 0 || $cff != "") {
		$body .= " (";
		if($car_time > 0) {
			$body .= "car: " . $page->minutesDisplay($car_time);
			if($train_time > 0) {
				$body .= ", ";
			}
		}
		if($train_time > 0 || $cff != "") {
			if($cff != "") {
				$body .= "$cff";
			}
			$body .= "train: ";
			if($train_time > 0) {
				$body .= $page->minutesDisplay($train_time);
			} else {
				$body .= "?";
			}
			if($cff != "") {
				$body .= "</a>";
			}
		}
		$body .= ")";
	}
	 */
	$body .= "</li>\n";
}
if($fields != "") {
	$body .= "<li><b>Fields of work:</b> $fields</li>\n";
}
if($physicist != "") {
	$body .= "<li><b>What would a physicist do by them:</b> $physicist</li>\n";
}
if($contact != "") {
	$li = preg_replace("/ /", "+", $contact);
	$li = "https://www.linkedin.com/vsearch/p?type=all&amp;keywords=$li&amp;f_N=F,S";
	$li = "<a target=\"_blank\" href=\"$li\">";
	$contact = "$li$contact</a>";
	$body .= "<li><b>Contact person inside:</b> $contact</li>\n";
}
if($HR != "") {
	$body .= "<li><b>HR person:</b> $HR</li>\n";
}
if($people != "") {
	$body .= "<li><b>Number of employees:</b> $people</li>\n";
}
if($competitors != "") {
	$body .= "<li><b>Competitors:</b> $competitors</li>\n";
}
if($website != "") {
	$body .= "<li><b>Website:</b> ";
	$body .= $page->SQL2URL($website);
	$body .= "</li>\n";
}
if($GI) {
	$body .= "<li><b>Personal ranking:</b> $ranking</li>\n";
}
if($comment != "") {
	$body .= "<li><b>Comment:</b> $comment</li>\n";
}
$body .= "</ul>\n";

if($GI) {
	$oldLang = $page->ChangeSessionLang("english");
	$sub = $page->DB_IdManage("SELECT * FROM `comco` WHERE `company` = ? ORDER BY `timestamp` ASC", $id);
	$sub->bind_result($sid, $forgetit, $timestamp, $who, $media, $way, $kind, $content);
	$body .= "<div class=\"csstab64_table comco\">\n";
	$now = $page->GetNow();
	while($sub->fetch()) {
		$txtTimestamp = substr($timestamp, 0, 10);
		$year  = substr($txtTimestamp, 0, 4) + 0;
		$month = substr($txtTimestamp, 5, 2) + 0;
		$day   = substr($txtTimestamp, 8, 2) + 0;
		$txtTimestamp = $page->Months($month) . "&nbsp;$day";
		if($year != $now->year) {
			$txtTimestamp .= ", $year";
		}
		$titleTimestamp = (substr($timestamp, 11) == "00:00:00") ? "" : $timestamp;
		$body .= "<div class=\"csstab64_row\">\n";
		$body .= "<div class=\"csstab64_cell\">\n";
		$body .= "<a class=\"edit\" href=\"communication.php?id=$sid\" title=\"edit\">edit</a>\n";
		$body .= "</div>\n";
		$body .= "<div class=\"csstab64_cell way\">\n";
		$body .= "<img alt=\"$way\" src=\"$way.png\" />\n";
		$body .= "</div>\n";
		$body .= "<div class=\"csstab64_cell timestamp\" title=\"$titleTimestamp\">\n";
		$body .= "$txtTimestamp\n";
		$body .= "</div>\n";
		$body .= "<div class=\"csstab64_cell who\">\n";
		$body .= "$who\n";
		$body .= "</div>\n";
		$body .= "<div class=\"csstab64_cell media\">\n";
		$body .= "<img alt=\"$media\" title=\"$media\" src=\"$media.png\" />\n";
		$body .= "</div>\n";
		$body .= "<div class=\"csstab64_cell kind\">\n";
		$body .= "<img alt=\"$kind\" title=\"$kind\" src=\"$kind.png\" />\n";
		$body .= "</div>\n";
		$body .= "<div class=\"csstab64_cell content\">\n";
		$body .= $page->SQL2URL($content) . "\n";
		$body .= "</div>\n";
		$body .= "</div>\n";
	}
	$sub->close();
	$body .= "<div class=\"csstab64_row\"><a href=\"communication.php?new=$id\" title=\"new\">new</a></div>\n";
	$body .= "</div>\n";
	$page->ChangeSessionLang($oldLang);
}

$body .= "</div>\n";


$page->show($body);
unset($page);
?>
