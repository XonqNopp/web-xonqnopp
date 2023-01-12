<?php
require("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require("common.php");

require("$funcpath/form_fields.php");
use FieldAttributes;
use FieldEmbedder;
global $theHiddenInput;
global $theTextInput;
global $theSelectInput;
global $theNumberInput;
global $theDateInput;



$page = new PhPage($rootPath);
$page->loginHelper->notAllowed();
$page->dbHelper->init();
//$page->htmlHelper->init();
//$page->logger->levelUp(6);
$page->cssHelper->dirUpWing();
$page->htmlHelper->jsForm();


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

	$sqlData->addField("DryEmptyTimestamp", "s", $page->timeHelper->getNow()->date);
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
	$sql = $page->dbHelper->idManage("SELECT COUNT(*) AS `tot` FROM `NavList` WHERE `plane` = ?", $sqlData->get("id"));
	$sql->bind_result($tot);
	$sql->fetch();
	$sql->close();

	if($tot == 0) {
		$sql = $page->dbHelper->idManage("DELETE FROM `{$page->dbHelper->dbName}`.`aircrafts` WHERE `aircrafts`.`id` = ? LIMIT 1;", $sqlData->get("id"));
		$page->htmlHelper->headerLocation();

	} else {
		$page->logger->error("Cannot erase plane with navigations entries; delete them before");
		$page_title = "Edit infos for " . $sqlData->get("PlaneID");
	}

	// Remove char escaping
	$sqlData->sql2field();

	// Reset strings without escaping for later input fields
	$sqlData->set("PlaneType");
	$sqlData->set("PlaneID");
	$sqlData->set("DryEmptyTimestampe");

} elseif(isset($_POST["submit"])) {
	// DB handling
	if(isset($_POST["id"])) {
		// update
		$page->dbHelper->queryUpdate($TABLE, $sqlData);

	} else {
		// insert
		$id = $page->dbHelper->queryInsert($TABLE, $sqlData);
		$sqlData->set("id", $id);
	}

	$page->htmlHelper->headerLocation("index.php#a{$sqlData['id']}");
	$page_title = "Edit infos for " . $sqlData->get("PlaneID");

	// Remove char escaping
	$sqlData->sql2field();

	// Reset strings without escaping for later input fields
	$sqlData->set("PlaneType");
	$sqlData->set("PlaneID");
	$sqlData->set("DryEmptyTimestampe");

} elseif(isset($_GET["id"])) {
	// get data for display
	$theplane = $page->dbHelper->selectId($TABLE, $aircraftId);

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

		$sqlData->fields["FuelCons"]->value,
		$sqlData->fields["FuelUnit"]->value,
		$sqlData->fields["FuelType"]->value
	);

	$theplane->fetch();
	$theplane->close();

	// Remove char escaping
	$sqlData->sql2field();

	$page_title = "Edit infos for " . $sqlData->get("PlaneID");
}

$body = $page->bodyHelper->goHome("..");
$body .= $page->htmlHelper->setTitle($page_title);// before HotBooty
$page->htmlHelper->hotBooty();

	// form
	$body .= "<div>\n";
	$body .= $page->formHelper->tag();

	$reqAttr = FieldAttributes(true);

	$noDivEmptyEmbedder = FieldEmbedder();
	$noDivEmptyEmbedder->bDiv = false;

		// fields
			// id
			if($sqlData->get("id") > 0) {
				$body .= $theHiddenInput->get("id", $sqlData);
			}
		//
			// PlaneType
			$attr = FieldAttributes(true, true);
			$body .= $theTextInput->get("PlaneType", $sqlData, "Plane type", NULL, $attr);
		//
			// PlaneID
			$body .= $theTextInput->get("PlaneID", $sqlData, "Plane ID", NULL, $reqAttr);
		//
			// Speed
			$body .= $page->tableHelper->open();
			$body .= $page->tableHelper->rowOpen();
			$body .= $page->tableHelper->cellOpen();
			$body .= "<b>Speed:</b>\n";
			$body .= $page->tableHelper->cellClose();

			$attrSpeed = new FieldAttributes(true);
			$attrSpeed->min = 0;

			$embedderSpeed = new FieldEmbedder(NULL, "kts");
			$embedderSpeed->bDiv = false;

			$embedderSpeed->title = "planning";
			$body .= $page->tableHelper->cellOpen();
			$body .= $theNumberInput->get("PlanningSpeed", $sqlData, NULL, $attrSpeed, $embedderSpeed);
			$body .= $page->tableHelper->cellClose();

			$embedderSpeed->title = "climb";
			$body .= $page->tableHelper->cellOpen();
			$body .= $theNumberInput->get("ClimbSpeed", $sqlData, NULL, $attrSpeed, $embedderSpeed);
			$body .= $page->tableHelper->cellClose();

			$body .= $page->tableHelper->rowClose();
			$body .= $page->tableHelper->close();

		$attrReqMass = new FieldAttributes(true);
		$attrReqMass->min = 0;

			// Dry empty
			$body .= $page->tableHelper->open();
			$body .= $page->tableHelper->rowOpen();

			$body .= $page->tableHelper->cellOpen();
			$body .= "<b>Dry empty:</b>\n";
			$body .= $page->tableHelper->cellClose();

			$emptyEmbedder = new FieldEmbedder();
			$emptyEmbedder->bDiv = false;

				// Dry empty mass
				$body .= $page->tableHelper->cellOpen();

				$emptyEmbedder->title = "mass";
				$body .= $theNumberInput->get("DryEmptyMass", $sqlData, NULL, $attrReqMass, $emptyEmbedder);

				$body .= $theSelectInput->get(
					"MassUnit",
					$page->utilsHelper->arraySequential2Associative(arrayKeysWithoutEmptyString($kMassUnits)),
					$sqlData,
					NULL,
					$noDivEmptyEmbedder
				);

				$body .= $page->tableHelper->cellClose();
			//
				// Dry empty moment
				$body .= $page->tableHelper->cellOpen();

				$emptyEmbedder->title = "moment";
				$attrEmptyMoment = new FieldAttributes(true);
				$attrEmptyMoment->min = 0;
				$attrEmptyMoment->step = $DEFAULT_STEP;
				$body .= $theNumberInput->get("DryEmptyMoment", $sqlData, NULL, $attrEmptyMoment, $emptyEmbedder);

				$body .= $theSelectInput->get(
					"MomentUnit",
					$page->utilsHelper->arraySequential2Associative(arrayKeysWithoutEmptyString($kMomentUnits)),
					$sqlData,
					NULL,
					$noDivEmptyEmbedder
				);

				$body .= $page->tableHelper->cellClose();
			//
				$emptyEmbedder->title = "Timestamp of measures";
				$attrTimestamp = new FieldAttributes(true);
				$attrTimestamp->max = "now";

				$body .= $page->tableHelper->cellOpen();
				$body .= $theDateInput->get("DryEmptyTimestamp", $sqlData, NULL, $attrTimestamp, $emptyEmbedder);
				$body .= $page->tableHelper->cellClose();

			$body .= $page->tableHelper->rowClose();
			$body .= $page->tableHelper->close();
		//
			// ArmUnit
			$body .= $theSelectInput->get(
				"ArmUnit",
				$page->utilsHelper->arraySequential2Associative(arrayKeysWithoutEmptyString($kArmUnits)),
				$sqlData,
				"Arm unit",
				$reqAttr,
			);
		//
			// Max mass
			$body .= "<$hSubtitle>Maximum mass</$hSubtitle>\n";

			$attrMass->bRequired = true;
			$body .= $theNumberInput->get("MTOW", $sqlData, "Take-Off", $attrMass);

			$attrMass->bRequired = false;

			$body .= $theNumberInput->get("MLDGW", $sqlData, "Landing (optional)", $attrMass);
		//
			// GC boundaries
			$body .= "<$hSubtitle>Gravity center boundaries</$hSubtitle>\n";
			$attrGC = new FieldAttributes(true);
			$attrGC->step = $DEFAULT_STEP;

			$body .= $theNumberInput->get("GCmin", $sqlData, "Highest minimum of GC", $attrGC);

			$body .= $theNumberInput->get("GCmax", $sqlData, "Lowest maximum of GC", $attrGC);

		$attrArm = new FieldAttributes(true);
		$attrArm->step = $DEFAULT_STEP;

			// Front+Rear arms
			$body .= "<$hsubTitle>Front and rear arms</$hsubTitle>\n";

			$body .= $theNumberInput->get("FrontArm", $sqlData, "Front arm", $attrArm);

			$attrArm->bRequired = false;

				// Rear arms
				$body .= $page->tableHelper->open();
				$body .= $page->tableHelper->rowOpen();

				$body .= $page->tableHelper->cellOpen();
				$body .= "<b>Rear arms (optional):</b>\n";
				$body .= $page->tableHelper->cellClose();

				for($rearIndex = 0; $rearIndex <= 1; ++$rearIndex) {
					$embedder = new FieldEmbedder("#" . ($rearIndex + 1));
					$embedder->bDiv = false;

					$body .= $page->tableHelper->cellOpen();
					$body .= $theNumberInput->get("Rear{$index}Arm", $sqlData, "#" . ($index + 1), NULL, $attrArm, $embedder);
					$body .= $page->tableHelper->cellClose();
				}

				$body .= $page->tableHelper->rowClose();
				$body .= $page->tableHelper->close();

		$attrMass = new FieldAttributes();
		$attrMass->min = 0;

			// Luggage stations
			$body .= "<$hSubtitle>Luggages</$hSubtitle>\n";
			$body .= $page->tableHelper->open();

				// Header row
				$body .= $page->tableHelper->rowOpen("bo");
				$body .= $page->tableHelper->cell("Arm");
				$body .= $page->tableHelper->cell("Max mass");
				$body .= $page->tableHelper->rowClose();

			$attrArm->bRequired = true;  // first is required
			$attrMass->bRequired = true;  // first is required

			for($luggageIndex = 0; $luggageIndex <= 3; ++$luggageIndex) {
				$body .= $page->tableHelper->rowOpen();

				$station = "#" . ($index + 1);
				if($attrArm->bRequired) {
					$station .= " (required)";
				}
				$body .= $page->tableHelper->cell($station, "bo");

				// Arm
				$body .= $page->tableHelper->cellOpen();
				$body .= $theNumberInput->get("Luggage{$luggageIndex}Arm", $sqlData, NULL, $attrArm, $noDivEmptyEmbedder);
				$body .= $page->tableHelper->cellClose();

				// Max mass
				$body .= $page->tableHelper->cellOpen();
				$body .= $theNumberInput->get("Luggage{$luggageIndex}MaxMass", $sqlData, NULL, $attrMass, $noDivEmptyEmbedder);
				$body .= $page->tableHelper->cellClose();

				$body .= $page->tableHelper->rowClose();

				$attrArm->bRequired = false;  // only first is required
				$attrMass->bRequired = false;  // only first is required
			}

				// Max Luggage total mass
				$body .= $page->tableHelper->rowOpen();

				$body .= $page->tableHelper->cell("Max total mass:", "bo");
				$body .= $page->tableHelper->cell();  // no arm

				$body .= $page->tableHelper->cellOpen();
				$body .= $theNumberInput->get("LuggageMaxTotalMass", $sqlData, NULL, $attrMass, $noDivEmptyEmbedder);
				$body .= $page->tableHelper->cellClose();

				$body .= $page->tableHelper->rowClose();
			//

			$body .= $page->tableHelper->close();
		//
			// Fuel tanks
			$body .= "<$hSubtitle>Fuel tanks</$hSubtitle>\n";
			$body .= $page->tableHelper->open();

				// Header row
				$body .= $page->tableHelper->rowOpen("bo");
				$body .= $page->tableHelper->cell("Fuel tank");
				$body .= $page->tableHelper->cell("Arm");
				$body .= $page->tableHelper->cell("Total capacity");
				$body .= $page->tableHelper->cell("Unusable");
				$body .= $page->tableHelper->cell("All or nothing");
				$body .= $page->tableHelper->rowClose();

			$attrQuantity = new FieldAttributes();
			$attrQuantity->min = 0;
			$attrQuantity->step = 1;

			$attrArm->bRequired = true;  // first is required
			$attrMass->bRequired = true;  // first is required

			for($fuelIndex = 0; $fuelIndex <= 3; ++$fuelIndex) {
				$body .= $page->tableHelper->rowOpen();

				$station = "#" . ($fuelIndex + 1);
				if($attrArm->bRequired) {
					$station .= " (required)";
				}
				$body .= $page->tableHelper->cell($station, "bo");

				$body .= $page->tableHelper->cellOpen();
				$body .= $theNumberInput->get("Fuel{$fuelIndex}Arm", $sqlData, NULL, $attrArm, $noDivEmptyEmbedder);
				$body .= $page->tableHelper->cellClose();

				$body .= $page->tableHelper->cellOpen();
				$body .= $theNumberInput->get("Fuel{$fuelIndex}TotalCapacity", $sqlData, NULL, $attrQuantity, $noDivEmptyEmbedder);
				$body .= $page->tableHelper->cellClose();

				$body .= $page->tableHelper->cellOpen();
				$body .= $theNumberInput->get("Fuel{$fuelIndex}Unusable", $sqlData, NULL, $attrQuantity, $noDivEmptyEmbedder);
				$body .= $page->tableHelper->cellClose();

				$body .= $page->tableHelper->cellOpen();
				$body .= $theSelectInput->get("Fuel{$fuelIndex}AllOrNothing", array("no", "yes"), $sqlData, NULL, $noDivEmptyEmbedder);
				$body .= $page->tableHelper->cellClose();

				$body .= $page->tableHelper->rowClose();

				$attrArm->bRequired = false;  // only first is required
				$attrQuantity->bRequired = false;  // only first is required
			}

			$body .= $page->tableHelper->close();
		//
			// Fuel consumption
			$body .= "<$hSubtitle>Fuel consumption</$hSubtitle>\n";
			$body .= "<div class=\"FuelMain\">\n";
				// FuelCons
				$attr = new FieldAttributes(true);
				$attr->min = 0;

				$body .= $theNumberInput->get("FuelCons", $sqlData, "Fuel consumption per hour", $attr);
			//
				// FuelUnit
				$body .= $theSelectInput->get(
					"FuelUnit",
					$page->utilsHelper->arraySequential2Associative(arrayKeysWithoutEmptyString($kFuelUnits)),
					$sqlData,
				);
			//
				// FuelType
				$body .= $theSelectInput->get(
					"FuelType",
					$page->utilsHelper->arraySequential2Associative(arrayKeysWithoutEmptyString($kFuelTypes)),
					$sqlData,
				);
			//
			$body .= "</div>\n";
	//
		// buttons
		$body .= $page->formHelper->subButt($sqlData->get("id") > 0, $sqlData->get("PlaneID"));
	//
	$body .= "</div>\n";
//

echo $body;
unset($page);
?>
