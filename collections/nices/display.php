<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->initDB();

$page->CSS_ppJump(2);
$page->CSS_ppWing();
$page->js_Push("newtab", "..");
//$page->SetBodyguards("onload=\"init_newtab()\"");

$grades = array("0" => "Bad", "1" => "Good", "2" => "Excellent");
$GI = $page->UserIsAdmin();

$id = $_GET["id"];
$body = "";
$args = new stdClass();
$args->rootpage = "..";
$body .= $page->GoHome($args);


$query = $page->DB_IdManage("SELECT * FROM `nices` WHERE `id` = ?", $id);
$query->store_result();
if($query->num_rows == 0) {
	$body .= "Sorry, no result...";
	$page_title = "No result";
} else {
	$query->bind_result($id, $name, $name_det, $address, $zip, $city, $canton, $country, $website, $trip, $menu, $grade);
	$query->fetch();
	//$g = $grades[$grade];
	$page_title = $name;


	// Print
	$body .= $page->SetTitle($page_title);
	$page->HotBooty();

	$body .= "<div class=\"wide\">\n";
	$body .= "<div class=\"lhead\">\n";
	$body .= "</div>\n";
	$body .= "<div class=\"chead\">\n";
	$body .= "</div>\n";
	// R head
	$body .= "<div class=\"rhead\">\n";
	// Propose to add a new if authorized
	if($GI) {
		// Add
		$body .= "<a href=\"insert.php\" title=\"Add a nice place\">Add a nice place</a><br />\n";
		// Edit
		$body .= "<a href=\"insert.php?id=$id\" title=\"Edit\">Edit</a><br />\n";
	}
	$body .= "</div>\n";
	$body .= "</div>\n";

	// Main part
	$body .= "<div class=\"whole\">\n";
	$body .= "<div class=\"nice_info_address\">\n";
	$body_add = "";
	$link_add = "";
	if($address != "") {
		$link_add .= $address;
	}
	if($zip > 0) {
		$link_add .= ", $zip";
	}
	if($city != "") {
		$link_add .= " $city";
	}
	if($country != "") {
		if($country == "CH") {$country = "Switzerland";}
		$link_add .= ", $country";
	}
	if($link_add != "" && $link_add != $address) {
		$link_bis = preg_replace("/ /", "+", $link_add);
		if($address != "") {
			$body_add .= "<a rel=\"external\" target=\"_blank\" href=\"http://maps.google.com?q=$link_bis\" title=\"$link_add\">$address</a><br />\n";
		}
		$body_add .= "<a rel=\"external\" target=\"_blank\" href=\"http://maps.google.com?q=$link_bis\" title=\"$link_add\">";
		if($zip > 0) {
			$body_add .= "$zip&nbsp;";
		}
		if($city != "") {
			$body_add .= $city;
		}
		$body_add .= "</a>\n";
		if($country != "") {
			$body_add .= "<br />\n";
			$body_add .= "<a rel=\"external\" target=\"_blank\" href=\"http://maps.google.com/?q=$link_bis\" title=\"$link_add\">$country</a>\n";
		}
	}
	$body .= $body_add;
	if($website != "") {
		$body .= "<br />\n<a rel=\"external\" target=\"_blank\" href=\"$website\" title=\"$website\">$website</a>\n";
	}
	if($trip != "") {
		$body .= "<br />\n<a rel=\"external\" target=\"_blank\" href=\"$trip\" title=\"Trip Advisor\">Trip Advisor</a>\n";
	}
	$body .= "</div>\n";
	$body .= "<div class=\"nice_info_menu\">\n";
	$body .= "$menu\n";
	$body .= "</div>\n";
	//$body .= "<div class=\"nice_info_grade\">$g</div>\n";
	$body .= "</div>\n";
}
$query->close();


/*** Printing ***/
$page->show($body);
unset($page);
?>
