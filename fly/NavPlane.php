<?php
/*** Created: Tue 2015-07-14 17:38:34 CEST
 * TODO:
 */
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
require("NavFunctions.php");
$page = new PhPage($rootPath);
$page->NotAllowed();
$page->initDB();
//$page->initHTML();
//$page->LogLevelUp(6);
$page->CSS_ppJump();
$page->CSS_ppWing();
$page->js_Form();
$body = "";

$page_title = "Insert new plane";
$id = 0;
$PlaneType = "";
$PlaneID = "HB-";
$PlanningSpeed = 0;
$ClimbSpeed = 0;
$FuelCons = 0;
$FuelUnit = "USG";
$UnusableFuel = 0;
$DryMass = 0;
$DryMassUnit = "lbs";
$DryMoment = 0;
$DryMomentUnit = "in lbs";
$DryTimestamp = $page->GetNow()->date;
$ArmUnit = "in";
$FrontArm = 0;
$RearArm = 0;
$LuggageArm = 0;
$FuelArm = 0;
$MTOW = 0;
$minGC = 0;
$maxGC = 0;

if(isset($_POST["erase"])) {
	//// delete entry
	$id = $_POST["id"];
	$sql = $page->DB_IdManage("SELECT COUNT(*) AS `tot` FROM `NavList` WHERE `plane` = ?", $id);
	$sql->bind_result($tot);
	$sql->fetch();
	$sql->close();
	if($tot == 0) {
		$sql = $page->DB_IdManage("DELETE FROM `{$page->ddb->DBname}`.`aircrafts` WHERE `aircrafts`.`id` = ? LIMIT 1;", $id);
		$page->HeaderLocation("NavList.php");
	} else {
		$page->NewError("Cannot erase plane with navigations entries; delete them before");
		$PlaneType = $_POST["PlaneType"];
		$PlaneID = $_POST["PlaneID"];
		$PlanningSpeed = $_POST["PlanningSpeed"];
		$ClimbSpeed = $_POST["ClimbSpeed"];
		$FuelCons = $_POST["FuelCons"];
		$FuelUnit = $_POST["FuelUnit"];
		$UnusableFuel = $_POST["UnusableFuel"];
		$DryMass = $_POST["DryMass"];
		$DryMassUnit = $_POST["DryMassUnit"];
		$DryMoment = $_POST["DryMoment"];
		$DryMomentUnit = $_POST["DryMomentUnit"];
		$DryTimestamp = $_POST["DryTimestamp"];
		$ArmUnit = $_POST["ArmUnit"];
		$FrontArm = round($_POST["FrontArm"], 3);
		$RearArm = round($_POST["RearArm"], 3);
		$LuggageArm = round($_POST["LuggageArm"], 3);
		$FuelArm = round($_POST["FuelArm"], 3);
		$MTOW = $_POST["MTOW"];
		$page_title = "Edit infos for $PlaneID";
	}
} elseif(isset($_POST["submit"])) {
	//// DB handling
	$PlaneType = $page->field2SQL($_POST["PlaneType"]);
	$PlaneID = $page->field2SQL($_POST["PlaneID"]);
	$PlanningSpeed = $_POST["PlanningSpeed"];
	$ClimbSpeed = $_POST["ClimbSpeed"];
	$FuelCons = $_POST["FuelCons"];
	$FuelUnit = $_POST["FuelUnit"];
	$UnusableFuel = $_POST["UnusableFuel"];
	$DryMass = $_POST["DryMass"];
	$DryMassUnit = $_POST["DryMassUnit"];
	$DryMoment = $_POST["DryMoment"];
	$DryMomentUnit = $_POST["DryMomentUnit"];
	$DryTimestamp = $page->field2SQL($_POST["DryTimestamp"]);
	$ArmUnit = $_POST["ArmUnit"];
	$FrontArm = round($_POST["FrontArm"], 3);
	$RearArm = round($_POST["RearArm"], 3);
	$LuggageArm = round($_POST["LuggageArm"], 3);
	$FuelArm = round($_POST["FuelArm"], 3);
	$MTOW = $_POST["MTOW"];
	$minGC = $_POST["minGC"];
	$maxGC = $_POST["maxGC"];
	if(isset($_POST["id"])) {
		//// update
		$id = $_POST["id"];
		$query = "UPDATE `{$page->ddb->DBname}`.`aircrafts` SET ";
		$query .= "`PlaneType` = ?, `PlaneID` = ?, `PlanningSpeed` = ?, `ClimbSpeed` = ?, `FuelCons` = ?, `FuelUnit` = ?, `UnusableFuel` = ?, `DryMass` = ?, `DryMassUnit` = ?, `DryMoment` = ?, `DryMomentUnit` = ?, `DryTimestamp` = ?, `ArmUnit` = ?, `FrontArm` = ?, `RearArm` = ?, `LuggageArm` = ?, `FuelArm` = ?, `MTOW` = ?, `minGC` = ?, `maxGC` = ?";
		$query .= " WHERE `aircrafts`.`id` = ? LIMIT 1;";
		$sql = $page->DB_QueryPrepare($query);
		$sql->bind_param("ssiiisiisisssddddiddi", $PlaneType, $PlaneID, $PlanningSpeed, $ClimbSpeed, $FuelCons, $FuelUnit, $UnusableFuel, $DryMass, $DryMassUnit, $DryMoment, $DryMomentUnit, $DryTimestamp, $ArmUnit, $FrontArm, $RearArm, $LuggageArm, $FuelArm, $MTOW, $minGC, $maxGC, $id);
		$page->DB_ExecuteManage($sql);
	} else {
		//// insert
		$query = "INSERT INTO `{$page->ddb->DBname}`.`aircrafts` (`PlaneType`, `PlaneID`, `PlanningSpeed`, `ClimbSpeed`, `FuelCons`, `FuelUnit`, `UnusableFuel`, `DryMass`, `DryMassUnit`, `DryMoment`, `DryMomentUnit`, `DryTimestamp`, `ArmUnit`, `FrontArm`, `RearArm`, `LuggageArm`, `FuelArm`, `MTOW`, `minGC`, `maxGC`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$sql = $page->DB_QueryPrepare($query);
		$sql->bind_param("ssiiisiisisssddddidd", $PlaneType, $PlaneID, $PlanningSpeed, $ClimbSpeed, $FuelCons, $FuelUnit, $UnusableFuel, $DryMass, $DryMassUnit, $DryMoment, $DryMomentUnit, $DryTimestamp, $ArmUnit, $FrontArm, $RearArm, $LuggageArm, $FuelArm, $MTOW, $minGC, $maxGC);
		$page->DB_ExecuteManage($sql);
		$id = $sql->insert_id;
	}
	$page->HeaderLocation("NavList.php#a$id");
	$page_title = "Edit infos for $PlaneID";
} elseif(isset($_GET["id"])) {
	//// get data for display
	$id = $_GET["id"];
	$sql = $page->DB_SelectId("aircrafts", $id);
	$sql->bind_result($id, $PlaneType, $PlaneID, $PlanningSpeed, $ClimbSpeed, $FuelCons, $FuelUnit, $UnusableFuel, $DryMass, $DryMassUnit, $DryMoment, $DryMomentUnit, $DryTimestamp, $ArmUnit, $FrontArm, $RearArm, $LuggageArm, $FuelArm, $MTOW, $minGC, $maxGC);
	$sql->fetch();
	$sql->close();
	$PlaneType = $page->SQL2field($PlaneType);
	$PlaneID = $page->SQL2field($PlaneID);
	$FrontArm = round($FrontArm, 3);
	$RearArm = round($RearArm, 3);
	$LuggageArm = round($LuggageArm, 3);
	$FuelArm = round($FuelArm, 3);
	$minGC = round($minGC, 3);
	$maxGC = round($maxGC, 3);
	$page_title = "Edit infos for $PlaneID";
}

$gohome = new stdClass();
$gohome->page = "NavList";
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
			//// PlaneType
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Plane type";
			$args->name = "PlaneType";
			$args->value = $PlaneType;
			$args->required = true;
			$args->autofocus = true;
			$body .= $page->FormField($args);
		//
			//// PlaneID
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Plane ID";
			$args->name = "PlaneID";
			$args->value = $PlaneID;
			$args->required = true;
			$body .= $page->FormField($args);
		//
			//// PlanningSpeed
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Planning speed";
			$args->name = "PlanningSpeed";
			$args->value = $PlanningSpeed;
			$args->min = 0;
			$args->required = true;
			$args->posttitle = "kts";
			$body .= $page->FormField($args);
		//
			//// ClimbSpeed
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Climb speed";
			$args->name = "ClimbSpeed";
			$args->value = $ClimbSpeed;
			$args->min = 0;
			$body .= $page->FormField($args);
		//
			//// Fuel
			$body .= "<div class=\"FuelMain\">\n";
				//// FuelCons
				$args = new stdClass();
				$args->type = "number";
				$args->title = "Fuel consumption per hour";
				$args->name = "FuelCons";
				$args->value = $FuelCons;
				$args->min = 0;
				$args->required = true;
				$body .= $page->FormField($args);
			//
				//// FuelUnit
				$args = new stdClass();
				$args->type = "select";
				$args->name = "FuelUnit";
				$args->value = $FuelUnit;
				$args->list = FuelUnits();
				$args->required = true;
				$body .= $page->FormField($args);
			//
			$body .= "</div>\n";
			//
		//
			// Unusable fuel
				$args = new stdClass();
				$args->type = "number";
				$args->title = "Unusable fuel";
				$args->name = "UnusableFuel";
				$args->value = $UnusableFuel;
				$args->min = 0;
				$args->required = true;
				$body .= $page->FormField($args);
		//
			//// MTOW
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Max Take-Off mass";
			$args->name = "MTOW";
			$args->value = $MTOW;
			$args->min = 0;
			$args->required = true;
			$body .= $page->FormField($args);
		//
			//// Dry empty mass
			$body .= "<div class=\"DryMassMain\">\n";
				//// DryMass
				$args = new stdClass();
				$args->type = "number";
				$args->title = "Dry empty mass";
				$args->name = "DryMass";
				$args->value = $DryMass;
				$args->min = 0;
				$args->required = true;
				$body .= $page->FormField($args);
			//
				//// DryMassUnit
				$args = new stdClass();
				$args->type = "select";
				$args->name = "DryMassUnit";
				$args->value = $DryMassUnit;
				$args->list = MassUnits();
				$args->required = true;
				$body .= $page->FormField($args);
			//
			$body .= "</div>\n";
			//
		//
			//// Dry empty moment
			$body .= "<div class=\"DryMomentMain\">\n";
				//// DryMoment
				$args = new stdClass();
				$args->type = "number";
				$args->title = "Dry empty moment";
				$args->name = "DryMoment";
				$args->value = $DryMoment;
				$args->min = 0;
				$args->step = 0.001;
				$args->required = true;
				$body .= $page->FormField($args);
			//
				//// DryMomentUnit
				$args = new stdClass();
				$args->type = "select";
				$args->name = "DryMomentUnit";
				$args->value = $DryMomentUnit;
				$args->list = MomentUnits();
				$args->required = true;
				$body .= $page->FormField($args);
			//
			$body .= "</div>\n";
			//
		//
			//// DryTimestamp
			$args = new stdClass();
			$args->type = "date";
			$args->title = "Timestamp of dry measures";
			$args->name = "DryTimestamp";
			$args->value = $DryTimestamp;
			$args->required = true;
			$body .= $page->FormField($args);
		//
			//// ArmUnit
			$args = new stdClass();
			$args->type = "select";
			$args->title = "Arm unit";
			$args->name = "ArmUnit";
			$args->value = $ArmUnit;
			$args->list = ArmUnits();
			$args->required = true;
			$body .= $page->FormField($args);
		//
			//// FrontArm
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Front arm";
			$args->name = "FrontArm";
			$args->value = $FrontArm;
			$args->min = 0;
			$args->step = 0.001;
			$args->required = true;
			$body .= $page->FormField($args);
		//
			//// RearArm
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Rear arm (optional)";
			$args->name = "RearArm";
			$args->value = $RearArm;
			$args->min = 0;
			$args->step = 0.001;
			$body .= $page->FormField($args);
		//
			//// LuggageArm
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Luggage arm";
			$args->name = "LuggageArm";
			$args->value = $LuggageArm;
			$args->min = 0;
			$args->step = 0.001;
			$args->required = true;
			$body .= $page->FormField($args);
		//
			//// FuelArm
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Fuel arm";
			$args->name = "FuelArm";
			$args->value = $FuelArm;
			$args->min = 0;
			$args->step = 0.001;
			$args->required = true;
			$body .= $page->FormField($args);
		//
			//// min GC
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Minimum of GC (if constant)";
			$args->name = "minGC";
			$args->value = $minGC;
			$args->min = 0;
			$args->step = 0.001;
			$body .= $page->FormField($args);
		//
			//// max GC
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Maximum of GC (if constant)";
			$args->name = "maxGC";
			$args->value = $maxGC;
			$args->min = 0;
			$args->step = 0.001;
			$body .= $page->FormField($args);
		//
	//
		//// buttons
		$args = new stdClass();
		$args->cancelURL = "NavList.php";
		$args->CloseTag = true;
		$body .= $page->SubButt($id > 0, $PlaneID, $args);
	//
	$body .= "</div>\n";
//

$page->show($body);
unset($page);
?>
