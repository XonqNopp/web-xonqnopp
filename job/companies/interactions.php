<?php
require("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require("dicts.php");
$page = new PhPage($rootPath);
$page->loginHelper->notAllowed();
$page->dbHelper->init();
//$page->logger->levelUp(6);
$body = "";

	// DisplayComco
	function DisplayComco($page, $item, $nowYear, $cname = "") {
		$back = "";
		$sid = $item->id;
		$cid = $item->company;
		$timestamp = $item->timestamp;
		$who = $item->who;
		$media = $item->media;
		$way = $item->way;
		$kind = $item->kind;
		$content = $item->content;

		$timestamp = substr($timestamp, 0, 10);
		$year  = substr($timestamp, 0, 4) + 0;
		$month = substr($timestamp, 5, 2) + 0;
		$day   = substr($timestamp, 8, 2) + 0;
		$timestamp = $page->timeHelper->months($month) . "&nbsp;$day";
		if($year != $nowYear) {
			$timestamp .= ", $year";
		}

		$back .= $page->tableHelper->rowOpen();

			// edit
			$back .= $page->tableHelper->cellOpen();
			$back .= "<a class=\"edit\" href=\"communication.php?id=$sid\" title=\"edit\">edit</a>\n";
			$back .= $page->tableHelper->cellClose();
		//
			// company name (if required)
			if($cname == "") {
				$query = $page->dbHelper->queryManage("SELECT * FROM `companies` WHERE `id` = $cid");
				$companyObj = $query->fetch_object();
				$query->close();
				$cname = $companyObj->name;

				$back .= $page->tableHelper->cellOpen("company_name");
				$back .= "<a href=\"display.php?id=$cid\" title=\"$cname\">$cname</a>\n";
				$back .= $page->tableHelper->cellClose();
			}
		//
			// way
			$back .= $page->tableHelper->cellOpen("way");
			$back .= "<img alt=\"$way\" src=\"$way.png\" />\n";
			$back .= $page->tableHelper->cellClose();
		//
			// timestamp
			$back .= $page->tableHelper->cellOpen("timestamp");
			$back .= "$timestamp\n";
			$back .= $page->tableHelper->cellClose();
		//
			// who
			$back .= $page->tableHelper->cellOpen("who");
			$back .= "$who\n";
			$back .= $page->tableHelper->cellClose();
		//
			// media
			$back .= $page->tableHelper->cellOpen("media");
			$back .= "<img alt=\"$media\" title=\"$media\" src=\"$media.png\" />\n";
			$back .= $page->tableHelper->cellClose();
		//
			// kind
			$back .= $page->tableHelper->cellOpen("kind");
			$back .= "<img alt=\"$kind\" title=\"$kind\" src=\"$kind.png\" />\n";
			$back .= $page->tableHelper->cellClose();
		//
			// content
			$back .= $page->tableHelper->cellOpen("content");
			$back .= $page->dbText->sql2url($content) . "\n";
			$back .= $page->tableHelper->cellClose();

		$back .= $page->tableHelper->rowClose();
		return $back;
	}

$chrono = true;
$page_title = "Chronological interactions";
if(isset($_GET["sort"]) && $_GET["sort"] == "companies") {
	$chrono = false;
	$page_title = "Interactions by companies";
}

$sum = $page->dbHelper->getCount("comco");
$page_title .= " ($sum)";

$page->cssHelper->dirUpWing(2);
$page->languageHelper->changeSessionLang("english");

$body .= $page->bodyHelper->goHome("../..");
$body .= $page->htmlHelper->setTitle($page_title);// before HotBooty
$page->htmlHelper->hotBooty();

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

$nowYear = $page->timeHelper->getNow()->year;

$body .= $page->tableHelper->open("comco");

if($chrono) {
	// chronological
	$sql = $page->dbHelper->queryManage("SELECT * FROM `comco` ORDER BY `timestamp` DESC, `company` ASC");
	$last = 999999;

	while($item = $sql->fetch_object()) {
		$timestamp = $item->timestamp;
		$year  = substr($timestamp, 0, 4);
		$month = substr($timestamp, 5, 2);
		$new = (int)"$year$month";

		if($new < $last) {
			$last = $new;
			$body .= $page->tableHelper->rowOpen();
			$body .= "<h2 class=\"csstab64_h2\">\n";
			$body .= $page->timeHelper->months($month+0);
			$body .= " $year\n";
			$body .= "</h2>\n";
			$body .= $page->tableHelper->rowClose();
		}

		$body .= DisplayComco($page, $item, $nowYear);
	}

	$sql->close();

} else {
	// companies
	$query = $page->dbHelper->queryManage("SELECT * FROM `companies` ORDER BY `name` ASC");

	while($c = $query->fetch_object()) {
		$cid = $c->id;
		$name = $c->name;
		$sql = $page->dbHelper->queryManage("SELECT * FROM `comco` WHERE `company` = $cid ORDER BY `timestamp` DESC");

		if($sql->num_rows > 0) {
			$body .= $page->tableHelper->rowOpen();
			$body .= "<h2 class=\"csstab64_h2\">\n";
			$body .= "<a href=\"display.php?id=$cid\" title=\"$name\">$name</a>\n";
			$body .= "</h2>\n";
			$body .= $page->tableHelper->rowClose();

			while($item = $sql->fetch_object()) {
				$body .= DisplayComco($page, $item, $nowYear, $name);
			}
		}

		$sql->close();
	}

	$query->close();
}

$body .= "</div>\n";  // TOOD not sure

$body .= $page->tableHelper->close();


echo $body;
unset($page);
?>
