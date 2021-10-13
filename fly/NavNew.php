<?php
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
require("common.php");
$page = new PhPage($rootPath);
$page->NotAllowed();
$page->initDB();

//$page->initHTML();
//$page->LogLevelUp(6);

$page->CSS_ppJump();
$page->CSS_ppWing();
$page->js_Form();

use stdClass;

$body = "";

$page_title = "Insert new navigation";


$kDefaultTable = "NavList";
$kDefaultPilotMass = 90;  // [kg]


$sqlData = new DbDataArray();
// name is SQL name
// type is used in bind_params
// value is the value itself
$sqlData->addField("id", "i", 0);
$sqlData->addField("name", "s", "");
$sqlData->addField("plane", "i", 0);  // plane ID from table
$sqlData->addField("variation", "i", $kDefaultVariation);
$sqlData->addField("FrontMass", "i", $kDefaultPilotMass);
$sqlData->addField("Rear0Mass", "i", 0);
$sqlData->addField("Rear1Mass", "i", 0);
$sqlData->addField("Luggage0Mass", "i", $kDefaultLuggageMass);
$sqlData->addField("Luggage1Mass", "i", 0);
$sqlData->addField("Luggage2Mass", "i", 0);
$sqlData->addField("Luggage3Mass", "i", 0);
$sqlData->addField("comment", "s", "");


if(isset($_POST["erase"]) || isset($_POST["submit"])) {
	$sqlData->setDataValuesFromPost($page);
}


if(isset($_POST["erase"])) {
	// delete entry
	$id = $_POST["id"];
	$sql = $page->DB_IdManage("SELECT COUNT(*) AS `tot` FROM `NavWaypoints` WHERE `NavID` = ?", $id);
	$sql->bind_result($tot);
	$sql->fetch();
	$sql->close();

	if($tot == 0) {
		$page->DB_QueryDelete($kDefaultTable, $id);
		deleteNavPdfFile($id);
		$page->HeaderLocation("NavList.php");

	} else {
		$page->NewError("Cannot erase navigation with waypoints; delete them before");
		$page_title = "Edit navigation " . $sqlData->get("name");
	}

} elseif(isset($_POST["submit"])) {
	// DB handling

	if(isset($_POST["id"])) {
		// update
		$page->DB_QueryUpdate($kDefaultTable, $sqlData);

		deleteNavPdfFile($_POST["id"]);

	} else {
		// insert
		$sqlData->set("id", $page->DB_QueryInsert($kDefaultTable, $sqlData));

	}
	$page->HeaderLocation("NavDetails.php?id=" . $sqlData->get("id"));
	$page_title = "Edit navigation " . $sqlData->get("name");

} elseif(isset($_GET["id"])) {
	// get data for display
	$sqlData->set("id", $_GET["id"]);
	$sql = $page->DB_SelectId($kDefaultTable, $sqlData->get("id"));

	$sql->bind_result(
		$sqlData->fields["id"]->value,
		$sqlData->fields["name"]->value,
		$sqlData->fields["plane"]->value,
		$sqlData->fields["variation"]->value,
		$sqlData->fields["FrontMass"]->value,
		$sqlData->fields["Rear0Mass"]->value,
		$sqlData->fields["Rear1Mass"]->value,
		$sqlData->fields["Luggage0Mass"]->value,
		$sqlData->fields["Luggage1Mass"]->value,
		$sqlData->fields["Luggage2Mass"]->value,
		$sqlData->fields["Luggage3Mass"]->value,
		$sqlData->fields["comment"]->value
	);

	$sql->fetch();
	$sql->close();

	if($sqlData->get("Rear0Mass") === NULL) {$sqlData->set("Rear0Mass", 0);}
	if($sqlData->get("Rear1Mass") === NULL) {$sqlData->set("Rear1Mass", 0);}
	if($sqlData->get("Luggage0Mass") === NULL) {$sqlData->set("Luggage0Mass", 0);}
	if($sqlData->get("Luggage1Mass") === NULL) {$sqlData->set("Luggage1Mass", 0);}
	if($sqlData->get("Luggage2Mass") === NULL) {$sqlData->set("Luggage2Mass", 0);}
	if($sqlData->get("Luggage3Mass") === NULL) {$sqlData->set("Luggage3Mass", 0);}

	$page_title = "Edit navigation " . $sqlData->get("name");
}

$sqlData->SQL2field();

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

		// fields
			// id
			if($sqlData->get("id") > 0) {
				$args = new stdClass();
				$args->type = "hidden";
				$args->name = "id";
				$args->value = $sqlData->get($args->name);
				$body .= $page->FormField($args);
			}
		//
			// name
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Name";
			$args->name = "name";
			$args->value = $sqlData->get($args->name);
			$args->required = true;
			$args->autofocus = true;
			$body .= $page->FormField($args);
		//
			// plane
				// fetch planes
				$PlaneList = array();
				$PlaneSQL = $page->DB_QueryManage("SELECT `id`, `PlaneType`, `PlaneID` FROM `aircrafts` ORDER BY `PlaneID`");
				while($p = $PlaneSQL->fetch_object()) {
					$PlaneList[$p->id] = "{$p->PlaneID} ({$p->PlaneType})";
				}
				$PlaneSQL->close();
			//
				// display
				$args = new stdClass();
				$args->type = "select";
				$args->title = "Plane";
				$args->name = "plane";
				$args->value = $sqlData->get($args->name);
				$args->list = $PlaneList;
				$args->WithEmpty = true;
				$body .= $page->FormField($args);
			//
		//
			// variation
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Variation";
			$args->name = "variation";
			$args->value = $sqlData->get($args->name);
			$args->required = true;
			$args->min = -180;
			$args->max =  180;
			$args->step = 0.1;
			$args->posttitle = "&deg;E";
			$body .= $page->FormField($args);
		//
			// FrontMass
			$args = new stdClass();
			$args->type = "number";
			$args->title = "Front mass";
			$args->name = "FrontMass";
			$args->value = $sqlData->get($args->name);
			$args->min = 0;
			$args->posttitle = "kg";
			$body .= $page->FormField($args);
		//
			// RearMass
			$body .= "<div class=\"csstab64_table\">\n";
			$body .= "<div class=\"csstab64_row\">\n";
			$body .= "<div class=\"csstab64_cell\"><b>Rear masses (optional):</b></div>\n";

			$args = new stdClass();
			$args->type = "number";
			$args->min = 0;
			$args->posttitle = "kg";

			$args->name = "Rear0Mass";
			$args->value = $sqlData->get($args->name);
			$body .= "<div class=\"csstab64_cell\">{$page->FormField($args)}</div>\n";

			$args->name = "Rear1Mass";
			$args->value = $sqlData->get($args->name);
			$body .= "<div class=\"csstab64_cell\">{$page->FormField($args)}</div>\n";

			$body .= "</div>  <!-- row -->\n";
			$body .= "</div>  <!-- table -->\n";
		//
			// LuggageMass
			$body .= "<div class=\"csstab64_table\">\n";
			$body .= "<div class=\"csstab64_row\">\n";
			$body .= "<div class=\"csstab64_cell\"><b>Luggage masses (optional):</b></div>\n";

			$args = new stdClass();
			$args->type = "number";
			$args->min = 0;
			$args->posttitle = "kg";

			$args->name = "Luggage0Mass";
			$args->value = $sqlData->get($args->name);
			$body .= "<div class=\"csstab64_cell\">{$page->FormField($args)}</div>\n";

			$args->name = "Luggage1Mass";
			$args->value = $sqlData->get($args->name);
			$body .= "<div class=\"csstab64_cell\">{$page->FormField($args)}</div>\n";

			$args->name = "Luggage2Mass";
			$args->value = $sqlData->get($args->name);
			$body .= "<div class=\"csstab64_cell\">{$page->FormField($args)}</div>\n";

			$args->name = "Luggage3Mass";
			$args->value = $sqlData->get($args->name);
			$body .= "<div class=\"csstab64_cell\">{$page->FormField($args)}</div>\n";

			$body .= "</div>  <!-- row -->\n";
			$body .= "</div>  <!-- table -->\n";
		//
			// Comment
			$args = new stdClass();
			$args->type = "text";
			$args->title = "Comment";
			$args->name = "comment";
			$args->value = $sqlData->get($args->name);
			$body .= $page->FormField($args);
		//
	//
		// buttons
		$args = new stdClass();
		$args->cancelURL = "NavDetails.php?id=" . $sqlData->get("id");
		$args->CloseTag = true;
		$body .= $page->SubButt($sqlData->get("id") > 0, $sqlData->get("name"), $args);
	//
	$body .= "</div>\n";

$page->show($body);
unset($page);
?>
