<?php
require("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require("dicts.php");
$page = new PhPage($rootPath);
$page->dbHelper->init();
//$page->logger->levelUp(6);
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

$page->cssHelper->dirUpWing(2);
$GI = $page->loginHelper->userIsAdmin();

if(!isset($_GET["id"])) {
	$page->htmlHelper->headerLocation();
}

$id = $_GET["id"];

$SQL = $page->dbHelper->idManage("SELECT * FROM `companies` WHERE `id` = ?", $id);
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


$body .= $page->bodyHelper->goHome("../..");

$body .= $page->htmlHelper->setTitle($name);// before HotBooty
$page->htmlHelper->hotBooty();

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
			$body .= "car: " . $page->timeHelper->minutesDisplay($car_time);
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
				$body .= $page->timeHelper->minutesDisplay($train_time);
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
	$body .= $page->dbText->sql2url($website);
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
	$page->languageHelper->changeSessionLang("english");
	$sub = $page->dbHelper->idManage("SELECT * FROM `comco` WHERE `company` = ? ORDER BY `timestamp` ASC", $id);
	$sub->bind_result($sid, $forgetit, $timestamp, $who, $media, $way, $kind, $content);

	$body .= $page->tableHelper->open("comco");

	$nowYear = $page->timeHelper->getNow()->year;
	while($sub->fetch()) {
		$txtTimestamp = substr($timestamp, 0, 10);
		$year  = substr($txtTimestamp, 0, 4) + 0;
		$month = substr($txtTimestamp, 5, 2) + 0;
		$day   = substr($txtTimestamp, 8, 2) + 0;
		$txtTimestamp = $page->timeHelper->months($month) . "&nbsp;$day";
		if($year != $nowYear) {
			$txtTimestamp .= ", $year";
		}
		//$titleTimestamp = (substr($timestamp, 11) == "00:00:00") ? "" : $timestamp;
		$body .= $page->tableHelper->rowOpen();

		$body .= $page->tableHelper->cellOpen();
		$body .= "<a class=\"edit\" href=\"communication.php?id=$sid\" title=\"edit\">edit</a>\n";
		$body .= $page->tableHelper->cellClose();

		$body .= $page->tableHelper->cellOpen("way");
		$body .= "<img alt=\"$way\" src=\"$way.png\" />\n";
		$body .= $page->tableHelper->cellClose();

		$body .= $page->tableHelper->cellOpen("timestamp");// title="$titleTimestamp"
		$body .= "$txtTimestamp\n";
		$body .= $page->tableHelper->cellClose();

		$body .= $page->tableHelper->cellOpen("who");
		$body .= "$who\n";
		$body .= $page->tableHelper->cellClose();

		$body .= $page->tableHelper->cellOpen("media");
		$body .= "<img alt=\"$media\" title=\"$media\" src=\"$media.png\" />\n";
		$body .= $page->tableHelper->cellClose();

		$body .= $page->tableHelper->cellOpen("kind");
		$body .= "<img alt=\"$kind\" title=\"$kind\" src=\"$kind.png\" />\n";
		$body .= $page->tableHelper->cellClose();

		$body .= $page->tableHelper->cellOpen("content");
		$body .= $page->dbText->sql2url($content) . "\n";
		$body .= $page->tableHelper->cellClose();

		$body .= $page->tableHelper->rowClose();
	}

	$sub->close();

	$body .= $page->tableHelper->rowOpen();
	$body .= "<a href=\"communication.php?new=$id\" title=\"new\">new</a>\n";
	$body .= $page->tableHelper->rowClose();

	$body .= $page->tableHelper->close();
}

$body .= "</div>\n";


echo $body;
unset($page);
?>
