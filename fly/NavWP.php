<?php
/*** Created: Wed 2015-07-15 17:38:34 CEST
 * TODO:
 */
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
require("NavFunctions.php");
$page = new PhPage($rootPath);
$page->NotAllowed();
$page->initDB();
//// debug
//$page->initHTML();
//$page->LogLevelUp(6);
//// jump
$page->CSS_ppJump();
$page->CSS_ppWing();
$page->js_Form();
$body = "";

$page_title = "Insert new waypoint";
$id = 0;
$NavID = 0;
$WPnum = 0;
$waypoint = "";
$TC = 0;
$distance = 0;
$altitude = 0;
$windTC = 0;
$windSpeed = 0;
$notes = "";
$climbing = 0;

if(isset($_POST["erase"])) {
	//// delete entry
	$id = $_POST["id"];
	$getNav = $page->DB_IdManage("SELECT `NavID` FROM `{$page->ddb->DBname}`.`NavWaypoints` WHERE `NavWaypoints`.`id` = ?", $id);
	$getNav->bind_result($NavID);
	$getNav->fetch();
	$getNav->close();
	$sql = $page->DB_IdManage("DELETE FROM `{$page->ddb->DBname}`.`NavWaypoints` WHERE `NavWaypoints`.`id` = ? LIMIT 1;", $id);
	$filename = "nav/nav" . sprintf("%06d", $NavID);
	if(file_exists("$filename.pdf")) {
		unlink("$filename.pdf");
	}
	$page->HeaderLocation("NavDetails.php?id={$_POST["NavID"]}");
} elseif(isset($_POST["submit"])) {
	//// DB handling
	$NavID = $_POST["NavID"];
	$WPnum = $_POST["WPnum"];
	$waypoint = $page->field2SQL($_POST["waypoint"]);
	$TC = $_POST["TC"];
	$distance = $_POST["distance"];
	$altitude = $_POST["altitude"];
	$windTC = $_POST["windTC"];
	$windSpeed = $_POST["windSpeed"];
	$notes = $page->field2SQL($_POST["notes"]);
	$climbing = $_POST["climbing"];
	$filename = "nav/nav" . sprintf("%06d", $NavID);
	if(isset($_POST["id"])) {
		//// update
		$id = $_POST["id"];
		//// check no different waypoint with same WPnum and NavID
		$check = $page->DB_QueryPrepare("SELECT COUNT(*) AS `tot` FROM `NavWaypoints` WHERE `NavID` = ? AND `WPnum` = ? AND `id` <> ?");
		$check->bind_param("iii", $NavID, $WPnum, $id);
		$page->DB_ExecuteManage($check);
		$check->bind_result($tot);
		$check->fetch();
		$check->close();
		if($tot > 0) {
			$page->NewError("WPnum $WPnum already exists for Nav #$NavID");
		} else {
			$query = "UPDATE `{$page->ddb->DBname}`.`NavWaypoints` SET ";
			$query .= "`WPnum` = ?, `waypoint` = ?, `TC` = ?, `distance` = ?, `altitude` = ?, `windTC` = ?, `windSpeed` = ?, `notes` = ?, `climbing` = ?";
			$query .= " WHERE `NavWaypoints`.`id` = ? LIMIT 1;";
			$sql = $page->DB_QueryPrepare($query);
			$sql->bind_param("isiiiiisii", $WPnum, $waypoint, $TC, $distance, $altitude, $windTC, $windSpeed, $notes, $climbing, $id);
			$page->DB_ExecuteManage($sql);
			if(file_exists("$filename.pdf")) {
				unlink("$filename.pdf");
			}
			$page->HeaderLocation("NavDetails.php?id=$NavID#WP$WPnum");
		}
	} else {
		//// insert
		//// check no waypoint with same WPnum and NavID
		$check = $page->DB_QueryPrepare("SELECT COUNT(*) AS `tot` FROM `NavWaypoints` WHERE `NavID` = ? AND `WPnum` = ?");
		$check->bind_param("ii", $NavID, $WPnum);
		$page->DB_ExecuteManage($check);
		$check->bind_result($tot);
		$check->fetch();
		$check->close();
		if($tot > 0) {
			$page->NewError("WPnum $WPnum already exists for Nav #$NavID");
		} else {
			$query = "INSERT INTO `{$page->ddb->DBname}`.`NavWaypoints` (`NavID`, `WPnum`, `waypoint`, `TC`, `distance`, `altitude`, `windTC`, `windSpeed`, `notes`, `climbing`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$sql = $page->DB_QueryPrepare($query);
			$sql->bind_param("iisiiiiisi", $NavID, $WPnum, $waypoint, $TC, $distance, $altitude, $windTC, $windSpeed, $notes, $climbing);
			$page->DB_ExecuteManage($sql);
			$id = $sql->insert_id;
			if(file_exists("$filename.pdf")) {
				unlink("$filename.pdf");
			}
			$page->HeaderLocation("NavDetails.php?id=$NavID#WP$WPnum");
		}
	}
	$page_title = "Edit waypoint $waypoint (#$WPnum)";
	$waypoint = $_POST["waypoint"];
	$notes = $_POST["notes"];
} elseif(isset($_GET["id"])) {
	//// get data for display
	$id = $_GET["id"];
	$sql = $page->DB_SelectId("NavWaypoints", $id);
	$sql->bind_result($id, $NavID, $WPnum, $waypoint, $TCguess, $TC, $distance, $altitude, $windTC, $windSpeed, $notes, $climbing);
	$sql->fetch();
	$sql->close();
	$page_title = "Edit waypoint $waypoint (#$WPnum)";
} else {
	if(isset($_GET["nav"])) {
		$NavID = $_GET["nav"];
		$page_title = "New waypoint";
		$count = $page->DB_QueryPrepare("SELECT COUNT(*) AS `tot` FROM `NavWaypoints` WHERE `NavID` = ?");
		$count->bind_param("i", $NavID);
		$page->DB_ExecuteManage($count);
		$count->bind_result($sumWP);
		$count->fetch();
		$count->close();
		if($sumWP > 0) {
			$num = $page->DB_QueryPrepare("SELECT MAX(`WPnum`) AS `oldWP` FROM `NavWaypoints` WHERE `NavID` = ?");
			$num->bind_param("i", $NavID);
			$page->DB_ExecuteManage($num);
			$num->bind_result($oldWP);
			$num->fetch();
			$num->close();
			$WPnum = $oldWP + 1;
			if($oldWP == 99) {
				$WPnum = 901;
			} elseif($oldWP == 199) {
				$WPnum = 901;
			} elseif($oldWP == 999) {
				$WPnum = 98;
			}
		} else {
			$waypoint = "LSGE";
		}
	} else {
		$page->HeaderLocation("NavList.php");
	}
}

$sqlName = $page->DB_SelectId("NavList", $NavID);
$sqlName->bind_result($NavID_not_used, $navName, $MapUsed, $plane, $Power, $PowerManifold, $PowerManifoldUnit, $PowerRPM, $NavAltitude, $variation, $FrontMass, $RearMass, $LuggageMass, $comment);
$sqlName->fetch();
$sqlName->close();

$navName = preg_replace("/ SKIP/", ",", $navName);
$page_title .= " for $navName (#$NavID)";

$gohome = new stdClass();
$gohome->page = "NavDetails";
$gohome->id = $NavID;
$gohome->rootpage = "index";
$body .= $page->GoHome($gohome);
$body .= $page->SetTitle($page_title);// before HotBooty
$page->HotBooty();
//
	//// form
	$body .= "<div>\n";
	$body .= $page->FormTag();
	//
		//// fields
			//// id
			$args = new stdClass();
			$args->type = "hidden";
			$args->name = "id";
			$args->value = $id;
			if($id > 0) {
				$body .= $page->FormField($args);
			}
		//
			//// NavID
			$args = new stdClass();
			$args->type = "hidden";
			$args->name = "NavID";
			$args->value = $NavID;
			$body .= $page->FormField($args);
		//
			//// WPnum
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Waypoint number";
			$args->name = "WPnum";
			$args->value = $WPnum;
			$args->min = 0;
			$args->required = true;
			$body .= $page->FormField($args);
				//// explain
				$body .= "<div class=\"WPnumExplained\">\n";
				$body .= "<ul>\n";
				$body .=   "<li><b>0:</b> departure waypoint of nav</li>\n";
				$body .=   "<li><b>1:</b> first waypoint of nav. If this one has no wind, no wind at all will be computed</li>\n";
				$body .=  "<li><b>99:</b> last waypoint of nav (destination)</li>\n";
				$body .= "<li><b>101:</b> first waypoint of backwards nav (optional)</li>\n";
				$body .= "<li><b>199:</b> last waypoint of backwards nav (optional)</li>\n";
				$body .= "<li><b>901:</b> first waypoint of alternate nav (optional)</li>\n";
				$body .= "<li><b>999:</b> last waypoint of alternate nav (optional)</li>\n";
				$body .= "</ul>\n";
				$body .= "<p>All these special waypoints except Nr. 0 and Nr. 901 will have an additional 5 minutes in the EET.</p>\n";
				$body .= "<p>Valid waypoint numbers: 0-99, 101-199, 901-999</p>\n";
				$body .= "</div>\n";
		//
			//// waypoint
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Waypoint name";
			$args->name = "waypoint";
			$args->value = $waypoint;
			$args->required = true;
			$args->autofocus = true;
			$body .= $page->FormField($args);
		//
			//// TC
			$args = new stdClass();
			$args->type = "number";
			$args->title = "TC";
			$args->name = "TC";
			$args->value = $TC;
			$args->min = 0;
			$args->max = 360;
			$args->posttitle = "&deg; (not for WP0)";
			$body .= $page->FormField($args);
		//
			//// distance
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Distance";
			$args->name = "distance";
			$args->value = $distance;
			$args->min = 0;
			$args->posttitle = "NM (not for WP0)";
			$body .= $page->FormField($args);
		//
			//// altitude
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Altitude";
			$args->name = "altitude";
			$args->value = $altitude;
			$args->min = 0;
			$args->posttitle = "ft (not for WP0)";
			$body .= $page->FormField($args);
		//
			//// wind
			$body .= "<div class=\"WindMain\">\n";
			$body .= "<p><b>Wind</b> (not for WP0)</p>\n";
			$body .= "<p>If the waypoint Nr. 1 has no wind, no wind at all will be used for the navigation.</p>\n";
			$body .= "<p>If a following waypoint has no wind, the wind data from the previous one will be used.</p>\n";
				//// windTC
				$args = new stdClass();
				$args->type = "number";
				$args->title = "TC";
				$args->name = "windTC";
				$args->value = $windTC;
				$args->min = 0;
				$args->max = 360;
				$args->posttitle = "&deg;";
				$body .= $page->FormField($args);
			//
				//// windSpeed
				$args = new stdClass();
				$args->type = "number";
				$args->title = "speed";
				$args->name = "windSpeed";
				$args->value = $windSpeed;
				$args->min = 0;
				$args->posttitle = "kts";
				$body .= $page->FormField($args);
			//
			$body .= "</div>\n";
			//
		//
			//// notes
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Notes";
			$args->name = "notes";
			$args->value = $notes;
			$body .= $page->FormField($args);
		//
			//// climbing
			$args = new stdClass();
			$args->type = "select";
			$args->title = "Climbing leg";
			$args->name = "climbing";
			$args->value = $climbing;
			$args->list = array("no", "yes");
			$args->keyval = true;
			$args->posttitle = "(not for WP0)";
			$body .= $page->FormField($args);
		//
	//
		//// buttons
		$args = new stdClass();
		$args->cancelURL = "NavDetails.php?id=$NavID";
		$args->CloseTag = true;
		$body .= $page->SubButt($id > 0, "$waypoint (#$WPnum)", $args);
	//
	$body .= "</div>\n";
//

$page->show($body);
unset($page);
?>
