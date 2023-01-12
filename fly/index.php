<?php
require("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->logger->levelUp(6);
//$page->dbHelper->init();
require("preparations.php");

//$page->cssHelper->dirUpWing();// cannot because it screws my display (but why?)

$body = $page->bodyHelper->goHome("..");

$body .= $page->htmlHelper->setTitle("Fly!");
$page->htmlHelper->hotBooty();

$body .= $page->tableHelper->open();
$body .= $page->tableHelper->rowOpen();
	// PAX
	$body .= $page->tableHelper->cellOpen("flyTitle");
	$body .= "<a href=\"pax.php?language=francais\">Informations pour mes passagers</a>\n";
	$body .= $page->tableHelper->cellClose();

	// logbook
	$body .= $page->tableHelper->cellOpen("flyTitle");
	$body .= "<a href=\"logbook.php\">My flight logbook</a>\n";
	$body .= $page->tableHelper->cellClose();

$body .= $page->tableHelper->rowClose();
$body .= $page->tableHelper->close();

$body .= "<div></div>\n";  // some space

$body .= $page->tableHelper->open("left");
$body .= $page->tableHelper->rowOpen();
	$body .= $page->tableHelper->cellOpen("half");
		$body .= "<ul>\n";
		$body .= "<li><a href=\"nav/index.php\">my navigations</a></li>\n";
		$body .= "<li><a href=\"pdf\">my PDF/checklists</a></li>\n";
		$body .= "<li><a href=\"computer.php\">computer</a></li>\n";
		$body .= "</ul>\n";
	//
		// Preparation
		$body .= "Flight preparations:\n";
		$body .= "<ul>\n";
		$body .= "<li><a href=\"lsge.php\" title=\"LSGE\">LSGE</a></li>\n";
		$body .= "<li><a href=\"lsgs.php\" title=\"LSGS\">LSGS</a></li>\n";
		$body .= "</ul>\n";
	//
		// misc
		$body .= "Misc:\n";
		$body .= "<ul>\n";
		$body .= "<li><a target=\"_blank\" href=\"http://www.sust.admin.ch/fr/index.html\">SESE/SUST</a></li>\n";

		$body .= "<li>\n";
		$body .= "<a target=\"_blank\" href=\"http://www.bazl.admin.ch/index.html?lang=fr\">OFAC/BAZL</a>\n";
		$body .= "&nbsp;-&nbsp;\n";
		$body .= "<a target=\"_blank\" href=\"https://www.bazl.admin.ch/bazl/fr/home/experts/formation-et-licences/pilotes/formulaires.html\">Formulaires AESA, avions a moteur, 60.521 SEP TMG revalidation EASA</a>\n";
		$body .= "</li>\n";

		$body .= "<li><a target=\"_blank\" href=\"http://seaplanes.ch/\">seaplanes.ch</a></li>\n";
		$body .= "<li><a target=\"_blank\" href=\"http://www.flightradar24.com/\">FlightRadar24</a></li>\n";
		$body .= "<li><a target=\"_blank\" href=\"http://planefinder.net/\">PlaneFinder</a></li>\n";
		$body .= "</ul>\n";
	$body .= $page->tableHelper->cellClose();
//
	$body .= $page->tableHelper->cellOpen("half");
	$body .= commonPreparations($page->loginHelper->userIsAdmin(), $page->miscInit);
	$body .= $page->tableHelper->cellClose();

$body .= $page->tableHelper->rowClose();
$body .= $page->tableHelper->close();

echo $body;
unset($page);
?>
