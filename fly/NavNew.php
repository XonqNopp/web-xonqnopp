<?php
/*** Created: Mon 2015-07-13 17:38:34 CEST
 * TODO:
 */
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
require("NavFunctions.php");
require("NavDefaults.php");
$page = new PhPage($rootPath);
$page->NotAllowed();
$page->initDB();

//$page->initHTML();
//$page->LogLevelUp(6);

$page->CSS_ppJump();
$page->CSS_ppWing();
$page->js_Form();
$body = "";

$page_title = "Insert new navigation";

$id = 0;
$name = "";
$MapUsed = "";
$plane = 0;
$Power = 0;
$PowerManifold = 0;
$PowerManifoldUnit = "";
$PowerRPM = 0;
$altitude = 0;
$variation = $defaultVariation;
$FrontMass = $defaultPilotMass;
$RearMass = 0;
$LuggageMass = $defaultLuggageMass;
$comment = "";
	//$filename = "nav" . sprintf("%06d", $NavID);
			//if(file_exists("$filename.pdf")) {
				//unlink("$filename.pdf");
			//}

if(isset($_POST["erase"])) {
	//// delete entry
	$id = $_POST["id"];
	$filename = "nav/nav" . sprintf("%06d", $id);
	$sql = $page->DB_IdManage("SELECT COUNT(*) AS `tot` FROM `NavWaypoints` WHERE `NavID` = ?", $id);
	$sql->bind_result($tot);
	$sql->fetch();
	$sql->close();
	if($tot == 0) {
		$sql = $page->DB_IdManage("DELETE FROM `{$page->ddb->DBname}`.`NavList` WHERE `NavList`.`id` = ? LIMIT 1;", $id);
		if(file_exists("$filename.pdf")) {
			unlink("$filename.pdf");
		}
		$page->HeaderLocation("NavList.php");
	} else {
		$page->NewError("Cannot erase navigation with waypoints; delete them before");
		$name = $_POST["name"];
		$plane = $_POST["plane"];
		$Power = $_POST["Power"];
		$PowerManifold = $_POST["PowerManifold"];
		$PowerManifoldUnit = $_POST["PowerManifoldUnit"];
		$PowerRPM = $_POST["PowerRPM"];
		$altitude = $_POST["altitude"];
		$variation = $_POST["variation"];
		$FrontMass = $_POST["FrontMass"];
		$RearMass = $_POST["RearMass"];
		$LuggageMass = $_POST["LuggageMass"];
		$comment = $_POST["comment"];
		$page_title = "Edit navigation $name";
	}
} elseif(isset($_POST["submit"])) {
	//// DB handling
	$name = $page->field2SQL($_POST["name"]);
	$plane = $_POST["plane"];
	$Power = $_POST["Power"];
	$PowerManifold = $_POST["PowerManifold"];
	$PowerManifoldUnit = $_POST["PowerManifoldUnit"];
	$PowerRPM = $_POST["PowerRPM"];
	$altitude = $_POST["altitude"];
	$variation = $_POST["variation"];
	$FrontMass = $_POST["FrontMass"];
	$RearMass = $_POST["RearMass"];
	$LuggageMass = $_POST["LuggageMass"];
	$comment = $page->field2SQL($_POST["comment"]);
	if(isset($_POST["id"])) {
		//// update
		$id = $_POST["id"];
		$filename = "nav/nav" . sprintf("%06d", $id);
		$query = "UPDATE `{$page->ddb->DBname}`.`NavList` SET ";
		$query .= "`name` = ?, `MapUsed` = NULL, `plane` = ?, `Power` = ?, `PowerManifold` = ?, `PowerManifoldUnit` = ?, `PowerRPM` = ?, `altitude` = ?, `variation` = ?, `FrontMass` = ?, `RearMass` = ?, `LuggageMass` = ?, `comment` = ?";
		$query .= " WHERE `NavList`.`id` = ? LIMIT 1;";
		$sql = $page->DB_QueryPrepare($query);
		$sql->bind_param("siidsdiiiiisi", $name, $plane, $Power, $PowerManifold, $PowerManifoldUnit, $PowerRPM, $altitude, $variation, $FrontMass, $RearMass, $LuggageMass, $comment, $id);
		$page->DB_ExecuteManage($sql);
		if(file_exists("$filename.pdf")) {
			unlink("$filename.pdf");
		}
	} else {
		//// insert
		$query = "INSERT INTO `{$page->ddb->DBname}`.`NavList` (`name`, `MapUsed`, `plane`, `Power`, `PowerManifold`, `PowerManifoldUnit`, `PowerRPM`, `altitude`, `variation`, `FrontMass`, `RearMass`, `LuggageMass`, `comment`) VALUES(?, NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$sql = $page->DB_QueryPrepare($query);
		$sql->bind_param("siidsdiiiiis", $name, $plane, $Power, $PowerManifold, $PowerManifoldUnit, $PowerRPM, $altitude, $variation, $FrontMass, $RearMass, $LuggageMass, $comment);
		$page->DB_ExecuteManage($sql);
		$id = $sql->insert_id;
	}
	$page->HeaderLocation("NavDetails.php?id=$id");
	$page_title = "Edit navigation $name";
} elseif(isset($_GET["id"])) {
	//// get data for display
	$id = $_GET["id"];
	$sql = $page->DB_SelectId("NavList", $id);
	$sql->bind_result($id, $name, $MapUsed, $plane, $Power, $PowerManifold, $PowerManifoldUnit, $PowerRPM, $altitude, $variation, $FrontMass, $RearMass, $LuggageMass, $comment);
	$sql->fetch();
	$sql->close();
	$name = $page->SQL2field($name);
	$comment = $page->SQL2field($comment);
	if($RearMass === NULL) {$RearMass = 0;}
	$page_title = "Edit navigation $name";
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
			//// name
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Name";
			$args->name = "name";
			$args->value = $name;
			$args->required = true;
			$args->autofocus = true;
			$body .= $page->FormField($args);
		//
			//// plane
				//// fetch planes
				$PlaneList = array();
				$PlaneSQL = $page->DB_QueryManage("SELECT `id`, `PlaneType`, `PlaneID` FROM `aircrafts` ORDER BY `PlaneID`");
				while($p = $PlaneSQL->fetch_object()) {
					$PlaneList[$p->id] = "{$p->PlaneID} ({$p->PlaneType})";
				}
				$PlaneSQL->close();
			//
				//// display
				$args = new stdClass();
				$args->type = "select";
				$args->title = "Plane";
				$args->name = "plane";
				$args->value = $plane;
				$args->list = $PlaneList;
				$args->WithEmpty = true;
				$body .= $page->FormField($args);
			//
			//
		//
		/*
			//// Power
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Power";
			$args->name = "Power";
			$args->value = $Power;
			$args->min = 0;
			$args->posttitle = "%";
			$body .= $page->FormField($args);
		//
			//// Power manifold
			$body .= "<div class=\"PowerManifoldMain\">\n";
				//// PowerManifold
				$args = new stdClass();
				$args->type = "number";
				$args->title = "Manifold pressure";
				$args->name = "PowerManifold";
				$args->value = $PowerManifold;
				$args->min = 0;
				$args->step = 0.001;
				$body .= $page->FormField($args);
			//
				//// PowerManifoldUnit
				$args = new stdClass();
				$args->type = "select";
				$args->name = "PowerManifoldUnit";
				$args->value = $PowerManifoldUnit;
				$args->list = PowerManifoldUnits();
				$body .= $page->FormField($args);
			//
			$body .= "</div>\n";
			//
		//
			//// PowerRPM
			$args = new stdClass();
			$args->type = "number";
			$args->title = "RPM";
			$args->name = "PowerRPM";
			$args->value = $PowerRPM;
			$args->min = 0;
			$body .= $page->FormField($args);
		//
			//// altitude
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Altitude";
			$args->name = "altitude";
			$args->value = $altitude;
			$args->min = 0;
			$args->posttitle = "ft";
			$body .= $page->FormField($args);
		 */
		//
			//// variation
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Variation";
			$args->name = "variation";
			$args->value = $variation;
			$args->required = true;
			$args->min = -360;
			$args->max =  360;
			$args->step = 0.1;
			$args->posttitle = "&deg;E";
			$body .= $page->FormField($args);
		//
			//// FrontMass
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Front mass";
			$args->name = "FrontMass";
			$args->value = $FrontMass;
			$args->min = 0;
			$args->posttitle = "kg";
			$body .= $page->FormField($args);
		//
			//// RearMass
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Rear mass (optional)";
			$args->name = "RearMass";
			$args->value = $RearMass;
			$args->min = 0;
			$args->posttitle = "kg";
			$body .= $page->FormField($args);
		//
			//// LuggageMass
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Luggage mass";
			$args->name = "LuggageMass";
			$args->value = $LuggageMass;
			$args->min = 0;
			$args->posttitle = "kg";
			$body .= $page->FormField($args);
		//
			//// Comment
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Comment";
			$args->name = "comment";
			$args->value = $comment;
			$body .= $page->FormField($args);
		//
	//
		//// buttons
		$args = new stdClass();
		$args->cancelURL = "NavDetails.php?id=$id";
		$args->CloseTag = true;
		$body .= $page->SubButt($id > 0, $name, $args);
	//
	$body .= "</div>\n";
//

$page->show($body);
unset($page);
?>
