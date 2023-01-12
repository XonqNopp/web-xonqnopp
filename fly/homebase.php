<?php
require("../functions/page_helper.php");
require("preparations.php");
use PhPage;


function homebase($title, $infos, $webcamAirport, $wunderground, $webcamArea) {
	$page = new PhPage("..");
	//$page->dbHelper->init();
	// debug
	//$page->htmlHelper->init();
	//$page->logger->levelUp(6);
	// CSS paths
	//$page->cssHelper->dirUpWing();

	$body = $page->bodyHelper->goHome();

	// Set title and hot booty
	$body .= $page->htmlHelper->setTitle($title);// before HotBooty
	$page->htmlHelper->hotBooty();

	$body .= $page->tableHelper->open("links");
	$body .= $page->tableHelper->rowOpen();

		// Infos & webcam
		$body .= $page->tableHelper->cellOpen("third");
		$body .= "<div>\n<ul>\n$infos</ul>\n</div>\n";
		$body .= "<div>\n$webcamAirport</div>\n";
		$body .= $page->tableHelper->cellClose();
	//
		// Weather station
		$body .= $page->tableHelper->cellOpen("third");
		$body .= "<a target=\"_blank\" href=\"https://www.wunderground.com/dashboard/pws/$wunderground\">\n";
		$body .= "<img class=\"width\" src=\"http://www.wunderground.com/cgi-bin/wxStationGraphAll?ID=$wunderground&amp;type=3&amp;width=500&amp;showsolarradiation=1&amp;showtemp=1&amp;showpressure=1&amp;showwind=1&amp;showwinddir=1&amp;showrain=1\" alt=\"weather station\" />\n";
		$body .= "</a>\n";
		$body .= $page->tableHelper->cellClose();
	//
		// Common
		$body .= $page->tableHelper->cellOpen("third left");
		$body .= commonPreparations($page->loginHelper->userIsAdmin());
		$body .= $page->tableHelper->cellClose();

	$body .= $page->tableHelper->rowClose();
	$body .= $page->tableHelper->close();

	$body .= "<div class=\"wide\">\n$webcamArea</div>\n";

	// Do not have text glued at bottom
	$body .= "<div>&nbsp;</div>\n";


	// Finish
	echo $body;
	unset($page);
}
?>
