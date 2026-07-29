<?php
session_start();
require("session.php");
session();
require("headers.php");
require("connection.php");
$link = connection();
/*** HEADER ***/
$print = beforehead();
$print .= jsrandom("all");
$print .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"csscommon.css\" />\n";
/*** MAIN ***/
if(!isset($_POST["done"])) {
	$print .= "<title>Choisir les cours de master</title>\n";
	$print .= "</head>\n";
	$print .= "<body>\n";
	$print .= "<h1>Choisir les cours de master</h1>\n";
	$print .= "<form name=\"ttablefrm\" method=\"post\" action=\"ttable.php\">\n";
	$print .= "<div class=\"all\">\n";
	$print .= "<input id=\"all\" type=\"checkbox\" onclick=\"return checkall()\" />&nbsp;<label for=\"all\">Select all</label>\n";
	$print .= "</div>\n";
	$print .= "<div class=\"listbutton\">\n";
	$print .= "<input type=\"hidden\" name=\"done\" />\n";
	$print .= "<input type=\"submit\" value=\"OK\" />\n";
	$print .= "</div>\n";
	$print .= "<div class=\"list\">\n";
	// MySQL begins here
	if(!$lectures = $link->query("SELECT * FROM `master` ORDER BY `name` ASC")) {
		displerr($lectures, $link);
	}
	$list = array();
	while($l = $lectures->fetch_assoc()) {
		$id = $l["id"];
		$name = $l["name"];
		if(!in_array($name, $list)) {
			$list[] = $name;
			$print .= "<input type=\"checkbox\" name=\"$id\" id=\"$id\" />&nbsp;<label for=\"$id\">$name</label><br />\n";
		}
	}
	$lectures->close();
	$print .= "</div>\n";
	$print .= "</form>\n";
} else {
	if(!$lectures = $link->query("SELECT * FROM `master` ORDER BY `name` ASC")) {
		displerr($lectures, $link);
	}
	$list = array();
	//$all = array();
	while($l = $lectures->fetch_assoc()) {
		$id = $l["id"];
		$name = $l["name"];
		if(isset($_POST[$id]) && !in_array($name, $list)) {
			$list[] = $name;
		}
	}
	$lectures->close();
	$print .= "<title>Horaire personnalis&eacute; pour le master</title>\n";
	$print .= "</head>\n";
	$print .= "<body>\n";
	$print .= "<h1>Horaire personnalis&eacute; pour le master</h1>\n";
	$print .= "<div class=\"back\"><a class=\"blue\" href=\"ttable.php\" title=\"S&eacute;l&eacute;ction des cours\">S&eacute;l&eacute;ction des cours</a></div>\n";
	$print .= "<table>\n";
	$print .= "<tr>\n";
	$week = array("monday", "tuesday", "wednesday", "thursday", "friday");
	$start = 0;
	foreach($week as $day) {
		$print .= "<td><div class=\"day\">$day</div></td>\n";
	}
	$print .= "</tr>\n";
	$print .= "<tr>\n";
	foreach($week as $day) {
		if(!$lectures = $link->query("SELECT * FROM `master` WHERE `day` = '$day' ORDER BY `timestart` ASC")) {
			displerr($lectures, $link);
		}
		//$print .= "<td><div class=\"day\">$day</div>\n";
		$print .= "<td class=\"ttable\">\n";
		$thisday = array();
		//$sort_hours = array();
		while($l = $lectures->fetch_assoc()) {
			$id = $l["id"];
			$name = $l["name"];
			if(in_array($name, $list)) {
				$type = $l["type"];
				$timestart = $l["timestart"];
				$timestop = $l["timestop"];
				$location = $l["location"];
				if($location != "") {
					$location = ", $location";
				}
				$item = array("name" => $name, "type" => $type, "start" => $timestart, "stop" => $timestop, "location" => $location);
				$thisday[] = $item;
			}
		}
		$lectures->close();
		$hours = array(8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19);
		$finish = array();
		foreach($hours as $hour) {
			$thishour = array();
			foreach($thisday as $it) {
				if($it["start"] <= $hour && $it["stop"] > $hour) {
					$thishour[] = $it;
				}
			}
			$css = "";
			if(count($thishour) == 0) {
				$toprint = "";
			} elseif(count($thishour) == 1) {
				$toprint = "<div class=\"name\">" . $thishour[0]["name"] . " (" . $thishour[0]["type"] . $thishour[0]["location"] . ")</div>\n";
				$css = " single";
			} else {
				$css = " many";
				$toprint = "";
				$toprint .= "<div class=\"name\"><ul class=\"ttable\">\n";
				foreach($thishour as $th) {
					$toprint .= "<li class=\"ttable\">" . $th["name"] . " (" . $th["type"] . $th["location"] . ")</li>\n";
				}
				$toprint .= "</ul></div>\n";
			}
			//if($toprint != "") {
				$print .= "<div class=\"entry$css\">\n";
				$print .= "<div class=\"time\">$hour - " . ($hour + 1) . "h</div>\n";
				$print .= $toprint;
				$print .= "</div>\n";
			//}
			//$it["disp"] = true;
		}
		$print .= "</td>\n";
	}
	$print .= "</tr>\n";
	$print .= "</table>\n";
}
/*** FOOTER ***/
$print .= endhtml();
/*** ECHO ***/
echo $print;
?>
