<?php
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
require("common.php");

use stdClass;

$page = new PhPage($rootPath);
$page->NotAllowed();
$page->initDB();
//$page->initHTML();
//$page->LogLevelUp(6);
$page->CSS_ppJump();
$page->CSS_ppWing();
$page->js_Form();
$body = "";

	// Functions
		// Get a rear arm field
		//
		// Args:
		//     page (ClassPage)
		//     sqlData (DbDataArray)
		//     defaultStep (float)
		//     index (int): index of desired rear arm
		//
		// Returns:
		//     (str) a CSS table cell with the rear arm field
		function getRearArmField($page, $sqlData, $defaultStep, $index) {
			$args = new stdClass();
			$args->type = "number";
			$args->min = 0;
			$args->step = $defaultStep;
			$args->div = False;

			$args->title = "#" . ($index + 1);
			$args->name = "Rear{$index}Arm";
			$args->value = $sqlData->get($args->name);
			return "<div class=\"csstab64_cell\">{$page->FormField($args)}</div>\n";
		}
	//
		// Get a luggage station fields row
		//
		// Args:
		//     page (ClassPage)
		//     sqlData (DbDataArray)
		//     defaultStep (float)
		//     index (int): index of desired luggage station
		//
		// Returns:
		//     (str) a CSS table row with the luggage fields
		function getLuggageStationFieldsRow($page, $sqlData, $defaultStep, $index, $required=False) {
			$output = "";
			$output .= "<div class=\"csstab64_row\">\n";
			$output .= "<div class=\"csstab64_cell bo\">#" . ($index + 1);
			if($required) {
				$output .= " (required)";
			}
			$output .= "</div>\n";

			// Arm
			$armArgs = new stdClass();
			$armArgs->type = "number";
			$armArgs->min = 0;
			$armArgs->div = False;
			$armArgs->required = $required;

			// Args are the same until this point
			$massArgs = clone($armArgs);

			$armArgs->step = $defaultStep;
			$armArgs->name = "Luggage{$index}Arm";
			$armArgs->value = $sqlData->get($armArgs->name);
			$output .= "<div class=\"csstab64_cell\">{$page->FormField($armArgs)}</div>\n";

			// Max mass
			$massArgs->name = "Luggage{$index}MaxMass";
			$massArgs->value = $sqlData->get($massArgs->name);
			$output .= "<div class=\"csstab64_cell\">{$page->FormField($massArgs)}</div>\n";

			$output .= "</div>  <!-- row -->\n";
			return $output;
		}
	//
		// Get a fuel tank fields row
		//
		// Args:
		//     page (ClassPage)
		//     sqlData (DbDataArray)
		//     defaultStep (float)
		//     index (int): index of desired rear arm
		//
		// Returns:
		//     (str) a CSS table cell with the rear arm field
		function getFuelTankRow($page, $sqlData, $defaultStep, $index, $required=False) {
			// Common args
			$armArgs = new stdClass();
			$armArgs->type = "number";
			$armArgs->div = False;
			$armArgs->min = 0;
			$armArgs->required = $required;

			$quantityArgs = clone($armArgs);

			$armArgs->step = $defaultStep;

			$output = "";
			$output .= "<div class=\"csstab64_row\">\n";
			$output .= "<div class=\"csstab64_cell bo\">#" . ($index + 1);
			if($required) {
				$output .= " (required)";
			}
			$output .= "</div>\n";
				// Arm
				$armArgs->name = "Fuel{$index}Arm";
				$armArgs->value = $sqlData->get($armArgs->name);
				$output .= "<div class=\"csstab64_cell\">{$page->FormField($armArgs)}</div>\n";;
			//
				// Total capacity
				$quantityArgs->name = "Fuel{$index}TotalCapacity";
				$quantityArgs->value = $sqlData->get($quantityArgs->name);
				$output .= "<div class=\"csstab64_cell\">{$page->FormField($quantityArgs)}</div>\n";;
			//
				// Unusable
				$quantityArgs->name = "Fuel{$index}Unusable";
				$quantityArgs->value = $sqlData->get($quantityArgs->name);
				$output .= "<div class=\"csstab64_cell\">{$page->FormField($quantityArgs)}</div>\n";;
			//
				// All or nothing
				$allArgs = new stdClass();
				$allArgs->type = "select";
				$allArgs->name = "Fuel{$index}AllOrNothing";
				$allArgs->value = $sqlData->get($allArgs->name);
				$allArgs->list = array("no", "yes");
				$allArgs->keyval = true;
				$output .= "<div class=\"csstab64_cell\">{$page->FormField($allArgs)}</div>\n";;
			//
			$output .= "</div>  <!-- row -->\n";
			return $output;
		}


$page_title = "Insert new plane";

$DEFAULT_STEP = 0.001;
$hSubtitle = "h3";


$TABLE = "aircrafts";

	// SQL data
	$sqlData = new DbDataArray();
	$sqlData->addField("id", "i", "");
	$sqlData->addField("PlaneType", "s", "");
	$sqlData->addField("PlaneID", "s", "HB-");
	$sqlData->addField("PlanningSpeed", "d", 0);
	$sqlData->addField("ClimbSpeed", "d", 0);

	$sqlData->addField("MassUnit", "s", "");
	$sqlData->addField("ArmUnit", "s", "");
	$sqlData->addField("MomentUnit", "s", "");

	$sqlData->addField("DryEmptyTimestamp", "s", $page->GetNow()->date);
	$sqlData->addField("DryEmptyMass", "i", 0);
	$sqlData->addField("DryEmptyMoment", "d", 0);

	$sqlData->addField("MTOW", "i", 0);
	$sqlData->addField("MLDGW", "i", 0);

	$sqlData->addField("GCmin", "d", 0);
	$sqlData->addField("GCmax", "d", 0);

	$sqlData->addField("FrontArm", "d", 0);

	$sqlData->addField("Rear0Arm", "d", 0);
	$sqlData->addField("Rear1Arm", "d", 0);

	$sqlData->addField("Luggage0Arm", "d", 0);
	$sqlData->addField("Luggage0MaxMass", "i", 0);
	$sqlData->addField("Luggage1Arm", "d", 0);
	$sqlData->addField("Luggage1MaxMass", "i", 0);
	$sqlData->addField("Luggage2Arm", "d", 0);
	$sqlData->addField("Luggage2MaxMass", "i", 0);
	$sqlData->addField("Luggage3Arm", "d", 0);
	$sqlData->addField("Luggage3MaxMass", "i", 0);

	$sqlData->addField("LuggageMaxTotalMass", "i", 0);

	$sqlData->addField("Fuel0Arm", "d", 0);
	$sqlData->addField("Fuel0TotalCapacity", "i", 0);
	$sqlData->addField("Fuel0Unusable", "i", 0);
	$sqlData->addField("Fuel0AllOrNothing", "i", 0);

	$sqlData->addField("Fuel1Arm", "d", 0);
	$sqlData->addField("Fuel1TotalCapacity", "i", 0);
	$sqlData->addField("Fuel1Unusable", "i", 0);
	$sqlData->addField("Fuel1AllOrNothing", "i", 0);

	$sqlData->addField("Fuel2Arm", "d", 0);
	$sqlData->addField("Fuel2TotalCapacity", "i", 0);
	$sqlData->addField("Fuel2Unusable", "i", 0);
	$sqlData->addField("Fuel2AllOrNothing", "i", 0);

	$sqlData->addField("Fuel3Arm", "d", 0);
	$sqlData->addField("Fuel3TotalCapacity", "i", 0);
	$sqlData->addField("Fuel3Unusable", "i", 0);
	$sqlData->addField("Fuel3AllOrNothing", "i", 0);

	$sqlData->addField("FuelCons", "i", 0);
	$sqlData->addField("FuelUnit", "s", "");
	$sqlData->addField("FuelType", "s", "");


if(isset($_POST["erase"]) || isset($_POST["submit"])) {
	$sqlData->setDataValuesFromPost($page);
	$roundedFields = array(
		"FrontArm",
		"Rear0Arm",
		"Rear1Arm",
		"Luggage0Arm",
		"Luggage1Arm",
		"Luggage2Arm",
		"Luggage3Arm",
		"Fuel0Arm",
		"Fuel1Arm",
		"Fuel2Arm",
		"Fuel3Arm",
	);
	foreach($fields as $field) {
		$sqlData->round($field);
	}
}


if(isset($_POST["erase"])) {
	// delete entry
	$sql = $page->DB_IdManage("SELECT COUNT(*) AS `tot` FROM `NavList` WHERE `plane` = ?", $sqlData->get("id"));
	$sql->bind_result($tot);
	$sql->fetch();
	$sql->close();

	if($tot == 0) {
		$sql = $page->DB_IdManage("DELETE FROM `{$page->ddb->DBname}`.`aircrafts` WHERE `aircrafts`.`id` = ? LIMIT 1;", $sqlData->get("id"));
		$page->HeaderLocation("NavList.php");

	} else {
		$page->NewError("Cannot erase plane with navigations entries; delete them before");
		$page_title = "Edit infos for " . $sqlData->get("PlaneID");
	}

	// Remove char escaping
	$sqlData->SQL2field();

	// Reset strings without escaping for later input fields
	$sqlData->set("PlaneType");
	$sqlData->set("PlaneID");
	$sqlData->set("DryEmptyTimestampe");

} elseif(isset($_POST["submit"])) {
	// DB handling
	if(isset($_POST["id"])) {
		// update
		$page->DB_QueryUpdate($TABLE, $sqlData);

	} else {
		// insert
		$id = $page->DB_QueryInsert($TABLE, $sqlData);
		$sqlData->set("id", $id);
	}

	$page->HeaderLocation("NavList.php#a{$sqlData['id']}");
	$page_title = "Edit infos for " . $sqlData->get("PlaneID");

	// Remove char escaping
	$sqlData->SQL2field();

	// Reset strings without escaping for later input fields
	$sqlData->set("PlaneType");
	$sqlData->set("PlaneID");
	$sqlData->set("DryEmptyTimestampe");

} elseif(isset($_GET["id"])) {
	// get data for display
	$theplane = $page->DB_SelectId($TABLE, $aircraftId);

	$theplane->bind_result(
		$sqlData->fields["id"]->value,
		$sqlData->fields["PlaneType"]->value,
		$sqlData->fields["PlaneID"]->value,
		$sqlData->fields["PlanningSpeed"]->value,
		$sqlData->fields["ClimbSpeed"]->value,

		$sqlData->fields["MassUnit"]->value,
		$sqlData->fields["ArmUnit"]->value,
		$sqlData->fields["MomentUnit"]->value,

		$sqlData->fields["DryEmptyTimestamp"]->value,
		$sqlData->fields["DryEmptyMass"]->value,
		$sqlData->fields["DryEmptyMoment"]->value,

		$sqlData->fields["MTOW"]->value,
		$sqlData->fields["MLDGW"]->value,

		$sqlData->fields["GCmin"]->value,
		$sqlData->fields["GCmax"]->value,

		$sqlData->fields["FrontArm"]->value,

		$sqlData->fields["Rear0Arm"]->value,
		$sqlData->fields["Rear1Arm"]->value,

		$sqlData->fields["Luggage0Arm"]->value,
		$sqlData->fields["Luggage0MaxMass"]->value,
		$sqlData->fields["Luggage1Arm"]->value,
		$sqlData->fields["Luggage1MaxMass"]->value,
		$sqlData->fields["Luggage2Arm"]->value,
		$sqlData->fields["Luggage2MaxMass"]->value,
		$sqlData->fields["Luggage3Arm"]->value,
		$sqlData->fields["Luggage3MaxMass"]->value,

		$sqlData->fields["LuggageMaxTotalMass"]->value,

		$sqlData->fields["Fuel0Arm"]->value,
		$sqlData->fields["Fuel0TotalCapacity"]->value,
		$sqlData->fields["Fuel0Unusable"]->value,
		$sqlData->fields["Fuel0AllOrNothing"]->value,

		$sqlData->fields["Fuel1Arm"]->value,
		$sqlData->fields["Fuel1TotalCapacity"]->value,
		$sqlData->fields["Fuel1Unusable"]->value,
		$sqlData->fields["Fuel1AllOrNothing"]->value,

		$sqlData->fields["Fuel2Arm"]->value,
		$sqlData->fields["Fuel2TotalCapacity"]->value,
		$sqlData->fields["Fuel2Unusable"]->value,
		$sqlData->fields["Fuel2AllOrNothing"]->value,

		$sqlData->fields["Fuel3Arm"]->value,
		$sqlData->fields["Fuel3TotalCapacity"]->value,
		$sqlData->fields["Fuel3Unusable"]->value,
		$sqlData->fields["Fuel3AllOrNothing"]->value,

		$sqlData->fields["FuelConsumption"]->value,
		$sqlData->fields["FuelUnit"]->value,
		$sqlData->fields["FuelType"]->value
	);

	$theplane->fetch();
	$theplane->close();

	// Remove char escaping
	$sqlData->SQL2field();

	$page_title = "Edit infos for " . $sqlData->get("PlaneID");
}

$gohome = new stdClass();
$gohome->page = "NavList";
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
			$args->value = $sqlData->get($args->name);
			if($sqlData->get("id") > 0) {
				$body .= $page->FormField($args);
			}
		//
			// PlaneType
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Plane type";
			$args->name = "PlaneType";
			$args->value = $sqlData->get($args->name);
			$args->required = true;
			$args->autofocus = true;
			$body .= $page->FormField($args);
		//
			// PlaneID
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Plane ID";
			$args->name = "PlaneID";
			$args->value = $sqlData->get($args->name);
			$args->required = true;
			$body .= $page->FormField($args);
		//
			// Speed
			$body .= "<div class=\"csstab64_table\">\n";
			$body .= "<div class=\"csstab64_row\">\n";
			$body .= "<div class=\"csstab64_cell\"><b>Speed:</b></div>\n";
			//
			// Common args
			$args = new stdClass();
			$args->type = "number";
			$args->min = 0;
			$args->required = true;
			$args->posttitle = "kts";
			$args->div = False;
			//
				// PlanningSpeed
				$args->title = "planning";
				$args->name = "PlanningSpeed";
				$args->value = $sqlData->get($args->name);
				$body .= "<div class=\"csstab64_cell\">{$page->FormField($args)}</div>\n";
			//
				// ClimbSpeed
				$args->title = "climb";
				$args->name = "ClimbSpeed";
				$args->value = $sqlData->get($args->name);
				$body .= "<div class=\"csstab64_cell\">{$page->FormField($args)}</div>\n";;
			$body .= "</div>\n";
			$body .= "</div>\n";
		//
			// Dry empty
			$body .= "<div class=\"csstab64_table\">\n";
			$body .= "<div class=\"csstab64_row\">\n";
			$body .= "<div class=\"csstab64_cell\"><b>Dry empty:</b></div>\n";
				// Dry empty mass
				$body .= "<div class=\"csstab64_cell\">\n";
					// DryEmptyMass
					$args = new stdClass();
					$args->type = "number";
					$args->title = "mass";
					$args->name = "DryEmptyMass";
					$args->value = $sqlData->get($args->name);
					$args->min = 0;
					$args->required = true;
					$args->div = False;
					$body .= $page->FormField($args);
				//
					// MassUnit
					$args = new stdClass();
					$args->type = "select";
					$args->name = "MassUnit";
					$args->value = $sqlData->get($args->name);
					$args->list = flattenDict($kMassUnits);
					$args->required = true;
					$args->div = False;
					$body .= $page->FormField($args);

				$body .= "</div>\n";
			//
				// Dry empty moment
				$body .= "<div class=\"csstab64_cell\">\n";
					// DryEmptyMoment
					$args = new stdClass();
					$args->type = "number";
					$args->title = "moment";
					$args->name = "DryEmptyMoment";
					$args->value = $sqlData->get($args->name);
					$args->min = 0;
					$args->step = $DEFAULT_STEP;
					$args->required = true;
					$args->div = False;
					$body .= $page->FormField($args);
				//
					// MomentUnit
					$args = new stdClass();
					$args->type = "select";
					$args->name = "MomentUnit";
					$args->value = $sqlData->get($args->name);
					$args->list = flattenDict($kMomentUnits);
					$args->required = true;
					$args->div = False;
					$body .= $page->FormField($args);

				$body .= "</div>\n";
			//
				// DryEmptyTimestamp
				$args = new stdClass();
				$args->type = "date";
				$args->title = "Timestamp of measures";
				$args->name = "DryEmptyTimestamp";
				$args->value = $sqlData->get($args->name);
				$args->required = true;
				$args->div = False;
				$body .= "<div class=\"csstab64_cell\">{$page->FormField($args)}</div>\n";
		//
			// ArmUnit
			$args = new stdClass();
			$args->type = "select";
			$args->title = "Arm unit";
			$args->name = "ArmUnit";
			$args->value = $sqlData->get($args->name);
			$args->list = flattenDict($kArmUnits);
			$args->required = true;
			$body .= $page->FormField($args);
		//
			// Max mass
			$body .= "<$hSubtitle>Maximum mass</$hSubtitle>\n";
				// MTOW
				$args = new stdClass();
				$args->type = "number";
				$args->title = "Take-Off";
				$args->name = "MTOW";
				$args->value = $sqlData->get($args->name);
				$args->min = 0;
				$args->required = true;
				$body .= $page->FormField($args);
			//
				// MLDGW
				$args->title = "Landing (optional)";
				$args->name = "MLDGW";
				$args->value = $sqlData->get($args->name);
				$args->required = False;
				$body .= $page->FormField($args);
		//
			// GC boundaries
			$body .= "<$hSubtitle>Gravity center boundaries</$hSubtitle>\n";
				// min GC
				$args = new stdClass();
				$args->type = "number";
				$args->title = "Highest minimum of GC";
				$args->name = "GCmin";
				$args->value = $sqlData->get($args->name);
				$args->min = 0;
				$args->step = $DEFAULT_STEP;
				$args->required = True;
				$body .= $page->FormField($args);
			//
				// max GC
				$args->title = "Lowest maximum of GC";
				$args->name = "GCmax";
				$args->value = $sqlData->get($args->name);
				$body .= $page->FormField($args);
		//
			// Front+Rear arms
			$body .= "<$hsubTitle>Front and rear arms</$hsubTitle>\n";
				// FrontArm
				$args = new stdClass();
				$args->type = "number";
				$args->title = "Front arm";
				$args->name = "FrontArm";
				$args->value = $sqlData->get($args->name);
				$args->min = 0;
				$args->step = $DEFAULT_STEP;
				$args->required = true;
				$body .= $page->FormField($args);
			//
				// Rear arms
				$body .= "<div class=\"csstab64_table\">\n";
				$body .= "<div class=\"csstab64_row\">\n";
				$body .= "<div class=\"csstab64_cell\"><b>Rear arms (optional):</b></div>\n";
				$body .= getRearArmField($page, $sqlData, $DEFAULT_STEP, 0);
				$body .= getRearArmField($page, $sqlData, $DEFAULT_STEP, 1);
				$body .= "</div>  <!-- row -->\n";
				$body .= "</div>  <!-- table -->\n";
		//
			// Luggage stations
			$body .= "<$hSubtitle>Luggages</$hSubtitle>\n";
			$body .= "<div class=\"csstab64_table\">\n";
				// Header row
				$body .= "<div class=\"csstab64_row bo\">\n";
				$body .= "<div class=\"csstab64_cell\">Arm</div>\n";
				$body .= "<div class=\"csstab64_cell\">Max mass</div>\n";
				$body .= "</div>  <!-- row -->\n";
			//
			$body .= getLuggageStationFieldsRow($page, $sqlData, $DEFAULT_STEP, 0, True);
			$body .= getLuggageStationFieldsRow($page, $sqlData, $DEFAULT_STEP, 1);
			$body .= getLuggageStationFieldsRow($page, $sqlData, $DEFAULT_STEP, 2);
			$body .= getLuggageStationFieldsRow($page, $sqlData, $DEFAULT_STEP, 3);

				// Max Luggage total mass
				$body .= "<div class=\"csstab64_row\">\n";
				$body .= "<div class=\"csstab64_cell bo\">Max total mass:</div>\n";
				$body .= "<div class=\"csstab64_cell\"></div>\n";  // no arm

				$massArgs = new stdClass();
				$massArgs->type = "number";
				$massArgs->min = 0;
				$massArgs->div = False;
				$massArgs->name = "LuggageMaxTotalMass";
				$massArgs->value = $sqlData->get($massArgs->name);
				$body .= "<div class=\"csstab64_cell\">{$page->FormField($massArgs)}</div>\n";

				$body .= "</div>  <!-- row -->\n";
			//

			$body .= "</div>  <!-- table -->\n";
		//
			// Fuel tanks
			$body .= "<$hSubtitle>Fuel tanks</$hSubtitle>\n";
			$body .= "<div class=\"csstab64_table\">\n";
				// Header row
				$body .= "<div class=\"csstab64_row bo\">\n";
				$body .= "<div class=\"csstab64_cell\">Fuel tank</div>\n";
				$body .= "<div class=\"csstab64_cell\">Arm</div>\n";
				$body .= "<div class=\"csstab64_cell\">Total capacity</div>\n";
				$body .= "<div class=\"csstab64_cell\">Unusable</div>\n";
				$body .= "<div class=\"csstab64_cell\">All or nothing</div>\n";
				$body .= "</div>  <!-- row -->\n";

			$body .= getFuelTankRow($page, $sqlData, $DEFAULT_STEP, 0, True);
			$body .= getFuelTankRow($page, $sqlData, $DEFAULT_STEP, 1);
			$body .= getFuelTankRow($page, $sqlData, $DEFAULT_STEP, 2);
			$body .= getFuelTankRow($page, $sqlData, $DEFAULT_STEP, 3);

			$body .= "</div>  <!-- table -->\n";
		//
			// Fuel consumption
			$body .= "<$hSubtitle>Fuel consumption</$hSubtitle>\n";
			$body .= "<div class=\"FuelMain\">\n";
				// FuelCons
				$args = new stdClass();
				$args->type = "number";
				$args->title = "Fuel consumption per hour";
				$args->name = "FuelCons";
				$args->value = $sqlData->get("FuelConsumption");
				$args->min = 0;
				$args->required = true;
				$body .= $page->FormField($args);
			//
				// FuelUnit
				$args = new stdClass();
				$args->type = "select";
				$args->name = "FuelUnit";
				$args->value = $sqlData->get($args->name);
				$args->list = flattenDict($kFuelUnits);
				$args->required = true;
				$body .= $page->FormField($args);
			//
				// FuelType
				$args->name = "FuelType";
				$args->value = $sqlData->get($args->name);
				$args->list = flattenDict($kFuelTypes);
				$body .= $page->FormField($args);
			//
			$body .= "</div>\n";
	//
		// buttons
		$args = new stdClass();
		$args->cancelURL = "NavList.php";
		$args->CloseTag = true;
		$body .= $page->SubButt($sqlData->get("id") > 0, $sqlData->get("PlaneID"), $args);
	//
	$body .= "</div>\n";
//

$page->show($body);
unset($page);
?>
