<?php
/*** Created: Wed 2015-07-08 11:05:39 CEST
 * TODO:
 */
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->initDB();
//$page->initHTML();
//$page->LogLevelUp(6);
$page->CSS_ppJump();
$page->CSS_ppWing();
$body = "";
$GI = $page->UserIsAdmin();

$navcount = $page->DB_GetCount("NavList");

$gohome = new stdClass();
$gohome->rootpage = "..";
$body .= $page->GoHome($gohome);
$body .= $page->SetTitle("Navigations ($navcount)");// before HotBooty
$page->HotBooty();
//
	//// planes (preparation, output comes later)
	$PlaneOrder = array("PlaneID");
	$planes = $page->DB_SelectAll("aircrafts", $PlaneOrder);
	$Nplanes = $page->DB_GetCount("aircrafts");
	$AllPlanes = array();
	$width = 1;
	$L = $Nplanes / (1.0 * $width);
	$i = 0;
	$bp = "";
	$bp .= "<div class=\"csstab64_table\">\n";
	$bp .= "<div class=\"csstab64_row\">\n";
	$bp .= "<div class=\"csstab64_cell\">\n";
	while($p = $planes->fetch_object()) {
		$i++;
		if($i > $L) {
			$bp .= "</div>\n";
			$bp .= "<div class=\"csstab64_cell\">\n";
		}
		$id = $p->id;
		$PlaneID = $p->PlaneID;
		$PlaneType = $p->PlaneType;
		$PlanningSpeed = $p->PlanningSpeed;
		$DryMass = $p->DryMass;
		$DryMassUnit = $p->DryMassUnit;
		$MTOW = $p->MTOW;
		$payload = $MTOW - $DryMass;
		if($DryMassUnit == "lbs") {
			$payload = floor($payload / 2.2);
		}
		$AllPlanes[$id] = $PlaneID;
		$bp .= "<div>\n";
		if($GI) {
			$bp .= "<span class=\"edit\">\n";
			$bp .= "<a href=\"NavPlane.php?id=$id\" title=\"edit $PlaneID\">edit</a>\n";
			$bp .= "</span>\n";
		}
		$bp .= "$PlaneID: $PlaneType {$PlanningSpeed}kts, payload {$payload}kg\n";
		$bp .= "</div>\n";
	}
	$bp .= "</div>\n";
	$bp .= "</div>\n";
	$bp .= "</div>\n";
	$planes->close();
//
//// Navigations
$NavOrders = array("name", "plane", "id");
$nav = $page->DB_SelectAll("NavList", $NavOrders);
$width = 2;
$N = $navcount;
$L = $N / (1.0 * $width);
$i = 0;

if($GI) {
	$body .= "<div class=\"wide\">\n";
	$body .= "<div class=\"lhead\">\n";
	$body .= "<a href=\"NavDetails.php?id=0\" title=\"refresh tempate\">refresh template</a><br />\n";
	$body .= "<a href=\"nav/navTemplate.tex\" title=\"template\">template</a><br />\n";
	$body .= "<a href=\"nav/navTemplate.pdf\" target=\"_blank\">PDF</a>\n";
	$body .= "</div>\n";
	$body .= "<div class=\"chead\">\n";
	$body .= "</div>\n";
	$body .= "<div class=\"rhead\">\n";
	$body .= "<a href=\"NavNew.php\" title=\"new nav\">new nav</a>\n";
	$body .= "</div>\n";
	$body .= "</div>\n";
}

$body .= "<div class=\"csstab64_table\">\n";
$body .= "<div class=\"csstab64_row\">\n";
$body .= "<div class=\"csstab64_cell\">\n";
while($item = $nav->fetch_object()) {
	$i++;
	if($i > $L) {
		$i = 0;
		$body .= "</div>\n";
		$body .= "<div class=\"csstab64_cell\">\n";
	}
	$id = $item->id;
	$name = preg_replace("/ SKIP/", ",", $item->name);
	$plane = "";
	if($item->plane > 0) {
		$plane = $AllPlanes[$item->plane];
	}
	$body .= "<div>\n";
	if($GI) {
		$body .= "<span class=\"edit\">\n";
		$body .= "<a href=\"NavNew.php?id=$id\" title=\"edit $name\">edit</a>\n";
		$body .= "</span>\n";
	}
	$body .= "<a href=\"NavDetails.php?id=$id\" title=\"$name\">\n";
	$body .= "$name";
	if($plane != "") {
		$body .= " ($plane)";
	}
	$body .= "\n";
	$body .= "</a>\n";
	$filename = "nav/nav" . sprintf("%06d", $id);
	if(file_exists("$filename.pdf")) {
		$body .= "&nbsp;\n";
		$body .= "<span class=\"edit\">\n";
		$body .= "<a href=\"$filename.pdf\" title=\"$name PDF\">PDF</a>\n";
		$body .= "</span>\n";
	}
	$body .= "</div>\n";
}
$body .= "</div>\n";
$body .= "</div>\n";
$body .= "</div>\n";

$nav->close();


$body .= "<h2>Available airplanes</h2>\n";
if($GI) {
	$body .= "<div class=\"wide\">\n";
	$body .= "<div class=\"lhead\">\n";
	$body .= "</div>\n";
	$body .= "<div class=\"chead\">\n";
	$body .= "</div>\n";
	$body .= "<div class=\"rhead\">\n";
	$body .= "<a href=\"NavPlane.php\" title=\"new plane\">new plane</a>\n";
	$body .= "</div>\n";
	$body .= "</div>\n";
}
$body .= $bp;


$page->show($body);
unset($page);
?>
