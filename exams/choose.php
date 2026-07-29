<?php
session_start();
require("session.php");
session();
require("headers.php");
require("connection.php");
$link = connection();
/*** HEADER ***/
$print = beforehead();
$print .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"csscommon.css\" />\n";
/*** MAIN ***/
if(!isset($_GET["which"]) || (isset($_GET["which"]) && $_GET["which"] != "b" && $_GET["which"] != "m")) {
	$print .= "<form action=\"choose.php\" method=\"get\">\n";
	$print .= "<div class=\"volee\">\n";
	$print .= "<input type=\"radio\" name=\"which\" value=\"m\" id=\"m\" /><label for=\"m\"> Master</label><br />\n";
	$print .= "<input type=\"radio\" name=\"which\" value=\"b\" id=\"b\" /><label for=\"b\"> 3<sup>e</sup> Bachelor</label><br />\n";
	$print .= "</div>\n";
	$print .= "<div class=\"voleebutton\">\n";
	$print .= "<input type=\"submit\" value=\"Voir les &eacute;tudiants\" />\n";
	$print .= "</form>\n";
} else {
	$which = $_GET["which"];
	$print .= "<div class=\"which\">\n";
	$print .= "<a href=\"choose.php?which=";
	if($which == "m") {
		$print .= "b\">Bachelor";
	} else {
		$print .= "m\">Master";
	}
	$print .= "</a>\n";
	$print .= "</div>\n";
	if(isset($_GET["st"])) {
		/* IF ID->STUDENT */
		$st_id = $_GET["st"];
		$q = $link->prepare("SELECT * FROM `students_$which` WHERE `id` = ?");
		$q->bind_param("i", $st_id);
		if(!$q->execute()) {
			displerr($q, $link);
		}
		$q->bind_result($st_id, $name);
		$st = $q->fetch();
		$q->close();
		if(!$lectures = $link->query("SELECT * FROM `lectures_$which` WHERE `students` LIKE '%$name%' ORDER BY `lecture` ASC")) {
			displerr($lectures, $link);
		}
		$all = array();
		$i = 0;
		while($l = $lectures->fetch_assoc()) {
			for($k = 0; $k < $l["howmanydates"]; $k++) {
				$kp1 = $k + 1;
				$dt = "date$kp1";
				$all[$i] = array("date" => $l[$dt], "lecture" => "<a href=\"choose.php?which=$which&amp;lc=" . $l["id"] . "\">" . $l["lecture"] . "</a>");
				$i++;
			}
		}
		$lectures->close();
		$start = 21;
		$stop = 10;
		$monthstart = 6;
		$monthstop = 7;
		$month = $monthstart;
		$pluszero = "";
		if($month < 10) {
			$pluszero = "0";
		}
		$print .= headertitle("Collisions pour $name");
		$print .= "</head>\n";
		$print .= "<body>\n";
		$print .= "<h1>Collisions pour $name</h1>\n";
		$print .= "<div class=\"bottin\"><a href=\"http://search.epfl.ch/compoundDirectory.do?q=$name\">Trouver dans le bottin EPFL</a></div>\n";
		$print .= "<table>\n";
		$print .= "<tr>\n";
		$sem = 1;
		for($d = $start; $month < $monthstop || $d <= $stop; ++$d) {// ATTENTION : Est-ce que ca marche comme il faut?
			$pluszerobis = "";
			if($d < 10) {
				$pluszerobis = "0";
			}
			$toprint = "";
			$many = 0;
			$ecrit = 0;
			foreach($all as $it) {
				if($it["date"] == "2010-$pluszero$month-$pluszerobis$d") {
					$toprint .= "<li>" . $it["lecture"] . "</li>\n";
					$ecrit += preg_match("/ECRIT/", $it["lecture"]);
					$many++;
				}
			}
			if($many == 0) {
				$print .= "<td class=\"empty\">\n";
			} elseif($many == 1) {
				if($ecrit >= 1) {
					$print .= "<td class=\"ecrit\">\n";
				} else {
					$print .= "<td class=\"single\">\n";
				}
			} else {
				if($ecrit >= 1) {
					$print .= "<td class=\"ecritmany\">\n";
				} else {
					$print .= "<td class=\"many\">\n";
				}
			}
			$print .= "<div class=\"date\">$d.$pluszero$month</div>\n";
			$print .= "<div class=\"content\">\n";
			$print .= "<ul>\n";
			$print .= "$toprint</ul>\n";
			$print .= "</div>\n";
			$print .= "</td>\n";
			if($sem == 7) {
				$print .= "</tr>\n";
				$print .= "<tr>\n";
				$sem = 1;
			} else {
				$sem++;
			}
			if($d > 29) {
				$month++;
				$d = 0;
			}
		}
		$print .= "</tr>\n";
		$print .= "</table>\n";
	}
	if(isset($_GET["lc"])) {
		/* IF ID->LECTURE */
		$lc_id = $_GET["lc"];
		$q = $link->prepare("SELECT * FROM `lectures_$which` WHERE `id` = ?");
		$q->bind_param("i", $lc_id);
		if(!$q->execute()) {
			displerr($q, $link);
		}
		$q->bind_result($lc_id, $lecture, $goodstudents, $howmanydates, $date1, $date2, $date3, $date4, $date5);
		$q->fetch();
		$q->close();
		$student_table = preg_split("/, /", $goodstudents);
		$print .= headertitle("&Eacute;tudiants inscrits pour le cours $lecture");
		$print .= "</head>\n";
		$print .= "<body>\n";
		$print .= "<h1>&Eacute;tudiants inscrits pour le cours $lecture</h1>\n";
		$print .= "<table>\n";
		$print .= "<tr>\n";
		$iter = 0;
		$n_std = 0;
		foreach($student_table as $s) {
			$n_std++;
			if(!$thisone = $link->query("SELECT * FROM `students_$which` WHERE `student` = '$s'")) {
				displerr($thisone, $link);
			}
			$goodone = $thisone->fetch_assoc();
			$goodidea = $goodone["id"];
			$print .= "<td><a href=\"choose.php?which=$which&amp;st=$goodidea\">$s</a></td>\n";
			if($iter >= 3) {
				$iter = 0;
				$print .= "</tr>\n";
				$print .= "<tr>\n";
			} else {
				$iter++;
			}
		}
		$n_std--;
		//$print .= "<td>Number of students : $n_std</td>\n";
		$print .= "</tr>\n";
		$print .= "</table>\n";
		$print .= "<div class=\"N\">Number of students : $n_std</div>\n";
	}
	/*** CHOOSE STUDENT ***/
	if(!$students = $link->query("SELECT * FROM `students_$which` ORDER BY `id` ASC")) {
		displerr($students, $link);
	}
	if(!isset($st_id) && !isset($lc_id)) {
		$print .= headertitle("Choisir un &eacute;tudiant");
		$print .= "</head>\n";
		$print .= "<body>\n";
	}
	$print .= "<table><tr><td>\n";
	$print .= "<form method=\"get\" action=\"choose.php\">\n";
	$print .= "<h2>Choisir un &eacute;tudiant</h2>\n";
	$print .= "<div class=\"select\">\n";
	$print .= "<input type=\"hidden\" name=\"which\" value=\"$which\" />\n";
	$print .= "<select name=\"st\">\n";
	while($s = $students->fetch_assoc()) {
		$isone = "";
		if(isset($st_id) && $s["id"] == $st_id) {
			$isone = " selected=\"selected\"";
		}
		$print .= "<option value=\"" . $s["id"] . "\"$isone>" . $s["student"] . "</option>\n";
	}
	$students->close();
	$print .= "</select>\n";
	$print .= "</div>\n";
	$print .= "<div class=\"select\">\n";
	$print .= "<input type=\"submit\" value=\"OK\" />\n";
	$print .= "</div>\n";
	$print .= "</form>\n";
	$print .= "</td>\n";
	/*** CHOOSE LECTURE ***/
	if(!$lectures = $link->query("SELECT * FROM `lectures_$which` ORDER BY `id` ASC")) {
		displerr($lectures, $link);
	}
	$print .= "<td>\n";
	$print .= "<form method=\"get\" action=\"choose.php\">\n";
	$print .= "<h2>Choisir un cours</h2>\n";
	$print .= "<div class=\"select\">\n";
	$print .= "<input type=\"hidden\" name=\"which\" value=\"$which\" />\n";
	$print .= "<select name=\"lc\">\n";
	while($l = $lectures->fetch_assoc()) {
		$isone = "";
		if(isset($lc_id) && $l["id"] == $lc_id) {
			$isone = " selected=\"selected\"";
		}
		$print .= "<option value=\"" . $l["id"] . "\"$isone>" . $l["lecture"] . "</option>\n";
	}
	$print .= "</select>\n";
	$print .= "</div>\n";
	$print .= "<div class=\"select\">\n";
	$print .= "<input type=\"submit\" value=\"OK\" />\n";
	$print .= "</div>\n";
	$print .= "</form>\n";
	$print .= "</td>\n";
	$print .= "</tr>\n";
	$print .= "</table>\n";
}
/*** FOOTER ***/
$print .= endhtml();
/*** ECHO ***/
echo $print;
?>
