<?php
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
require("common.php");
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

use stdClass;

$body = "";


// Make a DB query to select something with matching NavID
//
// Args:
//     query (str)
//     sqlData (DbDataArray): to use type and value of NavID
//
// Returns:
//     result of the query
function dbSelectNavID($page, $query, $sqlData) {
	$sqlQuery = $page->DB_QueryPrepare($query);
	$sqlQuery->bind_param($sqlData->fields["NavID"]->type, $sqlData->get("NavID"));
	$page->DB_ExecuteManage($sqlQuery);

	$result = NULL;
	$sqlQuery->bind_result($result);
	$sqlQuery->fetch();
	$sqlQuery->close();

	return $result;
}


$page_title = "Insert new waypoint";

$TABLE = "NavWaypoints";

$sqlData = new DbDataArray();
$sqlData->addField("id", 0, "i");
$sqlData->addField("NavID", 0, "i");
$sqlData->addField("WPnum", 0, "i");
$sqlData->addField("waypoint", "", "s");
$sqlData->addField("TC", 0, "i");
$sqlData->addField("distance", 0, "i");
$sqlData->addField("altitude", 0, "i");
$sqlData->addField("windTC", 0, "i");
$sqlData->addField("windSpeed", 0, "i");
$sqlData->addField("notes", "", "s");
$sqlData->addField("climbing", 0, "i");


if(isset($_POST["erase"]) || isset($_POST["submit"])) {
	$sqlData->setDataValuesFromPost($page);
}

if(isset($_POST["erase"])) {
	// delete entry
	$id = $_POST["id"];

	// Get the Nav ID to go back to the correct page
	$getNav = $page->DB_IdManage("SELECT `NavID` FROM `{$page->ddb->DBname}`.`$TABLE` WHERE `$TABLE`.`id` = ?", $id);
	$getNav->bind_result($NavID);
	$getNav->fetch();
	$getNav->close();

	$page->DB_QueryDelete($TABLE, $id);

	// Delete PDF of nav as it is out of date
	deletaNavPdfFile($NavID);

	// Go back to nav page
	$page->HeaderLocation("NavDetails.php?id={$_POST["NavID"]}");

} elseif(isset($_POST["submit"])) {
	// DB handling
	// check no different waypoint with same WPnum and NavID
	$matchingFields = array("NavID", "WPnum");
	$params = "";
	$query = "SELECT COUNT(*) AS `tot` FROM `$TABLE` WHERE";
	$bindParams = array();
	$first = true;
	foreach($matchingFields as $field) {
		if($first) {
			$first = false;
		} else {
			$query .= " AND";
		}

		$query .= " `$field` = ?";
		$params .= $sqlData->fields[$field]->type;
		$bindParams[] = $sqlData->get($field);
	}
	if(isset($_POST["id"])) {
		// Update
		$query .= " AND `id` <> ?";
		$params .= $sqlData->fields["id"]->type;
		$bindParams[] = $sqlData->get("id");
	}

	$sql = $page->DB_QueryPrepare($query);
	$sql->bind_params($params, ...$bindParams);
	$page->DB_ExecuteManage($sql, "NavWP.php", 87);
	$check->bind_result($tot);
	$check->fetch();
	$check->close();

	if($tot > 0) {
		$page->NewError("WPnum {$sqlData->get('WPnum')} already exists for Nav #{$sqlData->get('NavID')}");

	} else {
		if(isset($_POST["id"])) {
			// Update
			$page->DB_QueryUpdate($TABLE, $sqlData);

		} else {
			// Insert
			$id = $page->DB_QueryInsert($TABLE, $sqlData);
		}

		deleteNavPdfFile($sqlData->get("NavID"));
		$page->HeaderLocation("NavDetails.php?id={$sqlData->get('NavID')}#WP{$sqlData->get('WPnum')}");
	}

	$page_title = "Edit waypoint {$sqlData->get('waypoint')} (#{$sqlData->get('WPnum')})";

	// when we fetch from POST, for display in FORM we need without escaping
	$sqlData->set("waypoint", $_POST["waypoint"]);
	$sqlData->set("notes", $_POST["notes"]);

} elseif(isset($_GET["id"])) {
	// get data for display
	$sqlData->set("id", $_GET["id"]);
	$sql = $page->DB_SelectId("NavWaypoints", $sqlData->get("id"));

	$sql->bind_result(
		$sqlData->fields["id"]->value,
		$sqlData->fields["NavID"]->value,
		$sqlData->fields["WPnum"]->value,
		$sqlData->fields["waypoint"]->value,
		$sqlData->fields["TC"]->value,
		$sqlData->fields["distance"]->value,
		$sqlData->fields["altitude"]->value,
		$sqlData->fields["windTC"]->value,
		$sqlData->fields["windSpeed"]->value,
		$sqlData->fields["notes"]->value,
		$sqlData->fields["climbing"]->value
	);

	$sql->fetch();
	$sql->close();
	$page_title = "Edit waypoint {$sqlData->get('waypoint')} (#{$sqlData->get('WPnum')})";

} elseif(isset($_GET["nav"])) {
	$sqlData->set("NavID", $_GET["nav"]);
	$page_title = "New waypoint";

	$queryEnd = " FROM `$TABLE` WHERE `NavID` = ?";

	// TODO can we already ask max even if no matching?
	$sumWP = dbSelectNavID($page, "SELECT COUNT(*) AS `tot`" . $queryEnd, $sqlData);

	if($sumWP > 0) {
		$oldWP = dbSelectNavID($page, "SELECT MAX(`WPnum`) AS `oldWP`" . $queryEnd, $sqlData);
		$WPnum = $kWaypoints->getNext($oldWP);

	} else {
		$sqlData->set("waypoint", "LSGE");
		$sqlData->set("WPnum", 0);  // TODO somehow this is not propagated
	}

} else {
	// Not enough data to come on this page, fall back
	$page->HeaderLocation("NavList.php");
}

$navName = dbSelectNavID($page, "SELECT `name` from `NavList` WHERE `id` = ?", $sqlData);


$page_title .= " for $navName (#{$sqlData->get('NavID')})";

$gohome = new stdClass();
$gohome->page = "NavDetails";
$gohome->id = $sqlData->get("NavID");
$gohome->rootpage = "index";
$body .= $page->GoHome($gohome);
$body .= $page->SetTitle($page_title);// before HotBooty
$page->HotBooty();
//
	// form
	$body .= "<div>\n";
	$body .= $page->FormTag();
	//
		// fields
			// id
			$args = new stdClass();
			$args->type = "hidden";
			$args->name = "id";
			$args->value = $sqlData->get("id");
			if($id > 0) {
				$body .= $page->FormField($args);
			}
		//
			// NavID
			$args = new stdClass();
			$args->type = "hidden";
			$args->name = "NavID";
			$args->value = $sqlData->get("NavID");
			$body .= $page->FormField($args);
		//
			// WPnum
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Waypoint number";
			$args->name = "WPnum";
			$args->value = $sqlData->get("WPnum");
			$args->min = 0;
			$args->required = true;
			$body .= $page->FormField($args);
				// explain
				$body .= "<div class=\"WPnumExplained\">\n";
				$body .= "<ul>\n";
				$body .= "<li><b>{$kWaypoints->wayOut->base}:</b> departure waypoint of nav</li>\n";
				$body .= "<li><b>{$kWaypoints->wayOut->start}:</b> first waypoint of nav. If this one has no wind, no wind at all will be computed</li>\n";
				$body .= "<li><b>{$kWaypoints->wayOut->last}:</b> last waypoint of nav (destination)</li>\n";
				$body .= "<li><b>{$kWaypoints->wayBack->start}:</b> first waypoint of backwards nav (optional)</li>\n";
				$body .= "<li><b>{$kWaypoints->wayBack->last}:</b> last waypoint of backwards nav (optional)</li>\n";
				$body .= "<li><b>{$kWaypoints->alternate->start}:</b> first waypoint of alternate nav (optional)</li>\n";
				$body .= "<li><b>{$kWaypoints->alternate->last}:</b> last waypoint of alternate nav (optional)</li>\n";
				$body .= "</ul>\n";
				$body .= "<p>All these special waypoints except Nr. {$kWaypoints->wayOut->base} and Nr. {$kWaypoints->alternate->start} will have an additional 5 minutes in the EET.</p>\n";
				$body .= "<p>Valid waypoint numbers: ";
				$body .= "{$kWaypoints->wayOut->base}-{$kWaypoints->wayOut->last}, ";
				$body .= "{$kWaypoints->wayBack->start}-{$kWaypoints->wayBack->last}, ";
				$body .= "{$kWaypoints->alternate->start}-{$kWaypoints->alternate->last}";
				$body .= "</p>\n";
				$body .= "</div>\n";
		//
			// waypoint
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Waypoint name";
			$args->name = "waypoint";
			$args->value = $sqlData->get("waypoint");
			$args->required = true;
			$args->autofocus = true;
			$body .= $page->FormField($args);
		//
			// TC
			$args = new stdClass();
			$args->type = "number";
			$args->title = "TC";
			$args->name = "TC";
			$args->value = $sqlData->get("TC");
			$args->min = 1;
			$args->max = 360;
			$args->posttitle = "&deg; (not for WP0)";
			$body .= $page->FormField($args);
		//
			// distance
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Distance";
			$args->name = "distance";
			$args->value = $sqlData->get("distance");
			$args->min = 0;
			$args->posttitle = "NM (not for WP0)";
			$body .= $page->FormField($args);
		//
			// altitude
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Altitude";
			$args->name = "altitude";
			$args->value = $sqlData->get("altitude");
			$args->posttitle = "ft/FL (not for WP0)";
			$body .= $page->FormField($args);
		//
			// wind
			$body .= "<div class=\"WindMain\">\n";
			$body .= "<p><b>Wind</b> (not for WP0)</p>\n";
			$body .= "<p>If the waypoint Nr. {$kWaypoints->wayOut->start} has no wind, no wind at all will be used for the navigation.</p>\n";
			$body .= "<p>If a following waypoint has no wind, the wind data from the previous one will be used.</p>\n";
				// windTC
				$args = new stdClass();
				$args->type = "number";
				$args->title = "TC";
				$args->name = "windTC";
				$args->value = $sqlData->get("windTC");
				$args->min = 1;
				$args->max = 360;
				$args->posttitle = "&deg;";
				$body .= $page->FormField($args);
			//
				// windSpeed
				$args = new stdClass();
				$args->type = "number";
				$args->title = "speed";
				$args->name = "windSpeed";
				$args->value = $sqlData->get("windSpeed");
				$args->min = 0;
				$args->posttitle = "kts";
				$body .= $page->FormField($args);
			//
			$body .= "</div>\n";
			//
		//
			// notes
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Notes";
			$args->name = "notes";
			$args->value = $sqlData->get("notes");
			$body .= $page->FormField($args);
		//
			// climbing
			$args = new stdClass();
			$args->type = "select";
			$args->title = "Climbing leg";
			$args->name = "climbing";
			$args->value = $sqlData->get("climbing");
			$args->list = array("no", "yes");
			$args->keyval = true;
			$args->posttitle = "(not for WP0)";
			$body .= $page->FormField($args);
		//
	//
		// buttons
		$args = new stdClass();
		$args->cancelURL = "NavDetails.php?id={$sqlData->get('NavID')}";
		$args->CloseTag = true;
		$body .= $page->SubButt($sqlData->get("id") > 0, "{$sqlData->get('waypoint')} (#{$sqlData->get('WPnum')})", $args);
	//
	$body .= "</div>\n";
//

$page->show($body);
unset($page);
?>
