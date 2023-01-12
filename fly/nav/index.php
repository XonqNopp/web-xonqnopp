<?php
require("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require("common.php");
$page = new PhPage($rootPath);
$page->dbHelper->init();
//$page->htmlHelper->init();
//$page->logger->levelUp(6);
$page->cssHelper->dirUpWing();
$GI = $page->loginHelper->userIsAdmin();

$navcount = $page->dbHelper->getCount("NavList");

$body = $page->bodyHelper->goHome(NULL, "..");
$body .= $page->htmlHelper->setTitle("Navigations ($navcount)");// before HotBooty
$page->htmlHelper->hotBooty();

	// planes (preparation, output comes later)
	$PlaneOrder = array("PlaneID");
	$planes = $page->dbHelper->selectAll("aircrafts", $PlaneOrder);
	$Nplanes = $page->dbHelper->getCount("aircrafts");
	$AllPlanes = array();
	$width = 1;
	$L = $Nplanes / (1.0 * $width);
	$i = 0;

	$bp = $page->tableHelper->open();
	$bp .= $page->tableHelper->rowOpen();
	$bp .= $page->tableHelper->cellOpen();

	while($p = $planes->fetch_object()) {
		$i++;
		if($i > $L) {
			$bp .= $page->tableHelper->cellClose();
			$bp .= $page->tableHelper->cellOpen();
		}
		$id = $p->id;
		$payload = $p->MTOW - $p->DryMass;
		if($p->DryMassUnit == "lbs") {
			$payload = floor($payload / 2.2);
		}
		$AllPlanes[$id] = $p->PlaneID;
		$bp .= "<div>\n";
		if($GI) {
			$bp .= "<span class=\"edit\">\n";
			$bp .= "<a href=\"plane.php?id=$id\" title=\"edit {$p->PlaneID}\">edit</a>\n";
			$bp .= "</span>\n";
		}
		$bp .= "{$p->PlaneID}: {$p->PlaneType} {$p->PlanningSpeed}kts, payload {$p->payload}kg\n";
		$bp .= "</div>\n";
	}

	$planes->close();

	$bp .= $page->tableHelper->cellClose();
	$bp .= $page->tableHelper->rowClose();
	$bp .= $page->tableHelper->close();
//
// Navigations
$NavOrders = array("name", "plane", "id");
$nav = $page->dbHelper->selectAll("NavList", $NavOrders);
$width = 2;
$N = $navcount;
$L = $N / (1.0 * $width);
$i = 0;

if($GI) {
	$body .= "<div class=\"wide\">\n";
	$body .= "<div class=\"lhead\">\n";
	$body .= "<a href=\"display.php?id=0\" title=\"refresh tempate\">refresh template</a><br />\n";
	$body .= "<a href=\"nav/navTemplate.tex\" title=\"template\">template</a><br />\n";
	$body .= "<a href=\"pdf/navTemplate.pdf\" target=\"_blank\">PDF</a>\n";
	$body .= "</div>\n";
	$body .= "<div class=\"chead\">\n";
	$body .= "</div>\n";
	$body .= "<div class=\"rhead\">\n";
	$body .= "<a href=\"insert.php\" title=\"new nav\">new nav</a>\n";
	$body .= "</div>\n";
	$body .= "</div>\n";
}

$body .= $page->tableHelper->open();
$body .= $page->tableHelper->rowOpen();
$body .= $page->tableHelper->cellOpen();

while($item = $nav->fetch_object()) {
	$i++;
	if($i > $L) {
		$i = 0;
		$body .= $page->tableHelper->cellClose();
		$body .= $page->tableHelper->cellOpen();
	}
	$plane = "";
	if($item->plane > 0) {
		$plane = $AllPlanes[$item->plane];
	}
	$body .= "<div>\n";
	if($GI) {
		$body .= "<span class=\"edit\">\n";
		$body .= "<a href=\"insert.php?id={$item->id}\" title=\"edit {$item->name}\">edit</a>\n";
		$body .= "</span>\n";
	}
	$body .= "<a href=\"display.php?id={$item->id}\" title=\"{$item->name}\">\n";
	$body .= "{$item->name}";
	if($plane != "") {
		$body .= " ($plane)";
	}
	$body .= "\n";
	$body .= "</a>\n";
	$filename = getNavFilename($item->id);
	if(file_exists("$filename.pdf")) {
		$body .= "&nbsp;\n";
		$body .= "<span class=\"edit\">\n";
		$body .= "<a href=\"$filename.pdf\" title=\"{$item->name} PDF\">PDF</a>\n";
		$body .= "</span>\n";
	}
	$body .= "</div>\n";
}

$nav->close();

$body .= $page->tableHelper->cellClose();
$body .= $page->tableHelper->rowClose();
$body .= $page->tableHelper->close();


$body .= "<h2>Available airplanes</h2>\n";
if($GI) {
	$body .= "<div class=\"wide\">\n";
	$body .= "<div class=\"lhead\">\n";
	$body .= "</div>\n";
	$body .= "<div class=\"chead\">\n";
	$body .= "</div>\n";
	$body .= "<div class=\"rhead\">\n";
	$body .= "<a href=\"plane.php\" title=\"new plane\">new plane</a>\n";
	$body .= "</div>\n";
	$body .= "</div>\n";
}
$body .= $bp;


echo $body;
unset($page);
?>
