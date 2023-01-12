<?php
require("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require("common.php");
$page = new PhPage($rootPath);
$page->loginHelper->notAllowed();
$page->dbHelper->init();

require("$funcpath/form_fields.php");
use FieldAttributes;
use FieldEmbedder;
global $theHiddenInput;
global $theTextInput;
global $theSelectInput;
global $theNumberInput;


// debug
//$page->htmlHelper->init();
//$page->logger->levelUp(6);
// jump
$page->cssHelper->dirUpWing();
$page->htmlHelper->jsForm();



// Make a DB query to select something with matching NavID
//
// Args:
//     query (str)
//     sqlData (DbDataArray): to use type and value of NavID
//
// Returns:
//     result of the query
function dbSelectNavID($page, $query, $sqlData) {
	$sqlQuery = $page->dbHelper->queryPrepare($query);
	$sqlQuery->bind_param($sqlData->fields["NavID"]->type, $sqlData->get("NavID"));
	$page->dbHelper->executeManage($sqlQuery);

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
	$getNav = $page->dbHelper->idManage("SELECT `NavID` FROM `{$page->dbHelper->dbName}`.`$TABLE` WHERE `$TABLE`.`id` = ?", $id);
	$getNav->bind_result($NavID);
	$getNav->fetch();
	$getNav->close();

	$page->dbHelper->queryDelete($TABLE, $id);

	// Delete PDF of nav as it is out of date
	deletaNavPdfFile($NavID);

	// Go back to nav page
	$page->htmlHelper->headerLocation("display.php?id={$_POST["NavID"]}");

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

	$sql = $page->dbHelper->queryPrepare($query);
	$sql->bind_params($params, ...$bindParams);
	$page->dbHelper->executeManage($sql);
	$check->bind_result($tot);
	$check->fetch();
	$check->close();

	if($tot > 0) {
		$page->logger->error("WPnum {$sqlData->get('WPnum')} already exists for Nav #{$sqlData->get('NavID')}");

	} else {
		if(isset($_POST["id"])) {
			// Update
			$page->dbHelper->queryUpdate($TABLE, $sqlData);

		} else {
			// Insert
			$id = $page->dbHelper->queryInsert($TABLE, $sqlData);
		}

		deleteNavPdfFile($sqlData->get("NavID"));
		$page->htmlHelper->headerLocation("display.php?id={$sqlData->get('NavID')}#WP{$sqlData->get('WPnum')}");
	}

	$page_title = "Edit waypoint {$sqlData->get('waypoint')} (#{$sqlData->get('WPnum')})";

	// when we fetch from POST, for display in FORM we need without escaping
	$sqlData->set("waypoint", $_POST["waypoint"]);
	$sqlData->set("notes", $_POST["notes"]);

} elseif(isset($_GET["id"])) {
	// get data for display
	$sqlData->set("id", $_GET["id"]);
	$sql = $page->dbHelper->selectId("NavWaypoints", $sqlData->get("id"));

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
	$page->htmlHelper->headerLocation();
}

$navName = dbSelectNavID($page, "SELECT `name` from `NavList` WHERE `id` = ?", $sqlData);


$page_title .= " for $navName (#{$sqlData->get('NavID')})";

$body = $page->bodyHelper->goHome("..", "display.php?id={$sqlData->get('NavID')}");
$body .= $page->htmlHelper->setTitle($page_title);// before HotBooty
$page->htmlHelper->hotBooty();
//
	// form
	$body .= "<div>\n";
	$body .= $page->formHelper->tag();

	$attrHeading = new FieldAttributes();
	$attrHeading->min = 1;
	$attrHeading->max = 360;
	$attrHeading->step = 1;

	$attrMin0 = new FieldAttributes();
	$attrMin0->min = 0;

	$notForWp0 = " (not for WP0)";

	if($id > 0) {
		$body .= $theHiddenInput->get("id", $sqlData);
	}

	$body .= $theHiddenInput->get("NavID", $sqlData);

		$attr = new FieldAttributes(true);
		$attr->min = 0;
		$body .= $theNumberInput->get("WPnum", $sqlData, "Waypoint number", $attr);

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

	$wpAttr = FieldAttributes(true, true);
	$body .= $theTextInput->get("waypoint", $sqlData, "Waypoint name", NULL, $wpAttr);

	$embedder = new FieldEmbedder("TC", "&deg; $notForWp0");
	$body .= $theNumberInput->get("TC", $sqlData, NULL, $attrHeading, $embedder);

	$embedder = new FieldEmbedder("Distance", "NM $notForWp0");
	$body .= $theNumberInput->get("distance", $sqlData, NULL, $attrMin0, $embedder);

	$embedder = FieldEmbedder("Altitude", "ft/FL $notForWp0");
	$body .= $theTextInput->get("altitude", $sqlData, "Altitude", NULL, NULL, $embedder);

		// wind
		$body .= "<div class=\"WindMain\">\n";
		$body .= "<p><b>Wind</b> $notForWp0</p>\n";
		$body .= "<p>If the waypoint Nr. {$kWaypoints->wayOut->start} has no wind, no wind at all will be used for the navigation.</p>\n";
		$body .= "<p>If a following waypoint has no wind, the wind data from the previous one will be used.</p>\n";

		$embedder = new FieldEMbedder("TC", "&deg;");
		$body .= $theNumberInput->get("windTC", $sqlData, NULL, $attrHeading, $embedder);

		$embedder = new FieldEmbedder("speed", "kts");
		$body .= $theNumberInput->get("windSpeed", $sqlData, NULL, $attrMin0, $embedder);

		$body .= "</div>\n";

	$body .= $theTextInput->get("notes", $sqlData, "Notes");

	$embedder = FieldEmbedder("Climbing leg", $notForWp0);
	$body .= $theSelectInput->get("climbing", array("no", "yes"), $sqlData, NULL, $embedder);


	$body .= $page->formHelper->subButt(
		$sqlData->get("id") > 0,
		"{$sqlData->get('waypoint')} (#{$sqlData->get('WPnum')})",
		"display.php?id={$sqlData->get('NavID')}"
	);

	$body .= "</div>\n";


echo $body;
unset($page);
?>
