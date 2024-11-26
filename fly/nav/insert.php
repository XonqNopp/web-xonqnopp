<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require_once("common.php");
$page = new PhPage($rootPath);
$page->loginHelper->notAllowed();
$page->bobbyTable->init();

require_once("$funcpath/form_fields.php");
global $theHiddenInput;
global $theTextInput;
global $theSelectInput;
global $theNumberInput;


//$page->htmlHelper->init();
//$page->logger->levelUp(6);

$page->cssHelper->dirUpWing();
$page->htmlHelper->jsForm();


$page_title = "Insert new navigation";


$kDefaultTable = "NavList";
$kDefaultPilotMass = 90;  // [kg]


$sqlData = new DbDataArray();
// name is SQL name
// type is used in bind_param
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
// We do not check for armless here since the plane can change.
// display.php checks if any armless station provides a mass.


if(isset($_POST["erase"]) || isset($_POST["submit"])) {
    $sqlData->setDataValuesFromPost($page);
}


if(isset($_POST["erase"])) {
    // delete entry
    $id = $_POST["id"];
    $sql = $page->bobbyTable->idManage("SELECT COUNT(*) AS `tot` FROM `NavWaypoints` WHERE `NavID` = ?", $id);
    $sql->bind_result($tot);
    $sql->fetch();
    $sql->close();

    if($tot == 0) {
        $page->bobbyTable->queryDelete($kDefaultTable, $id);
        deleteNavPdfFile($id);
        deleteNavFile($id, "tex");
        $page->htmlHelper->headerLocation();

    } else {
        $page->logger->error("Cannot erase navigation with waypoints; delete them before");
        $page_title = "Edit navigation " . $sqlData->get("name");
    }

} elseif(isset($_POST["submit"])) {
    // DB handling

    if(isset($_POST["id"])) {
        // update
        $page->bobbyTable->queryUpdate($kDefaultTable, $sqlData);

        deleteNavPdfFile($_POST["id"]);

    } else {
        // insert
        $sqlData->set("id", $page->bobbyTable->queryInsert($kDefaultTable, $sqlData));

    }
    $page->htmlHelper->headerLocation("display.php?id=" . $sqlData->get("id"));
    $page_title = "Edit navigation " . $sqlData->get("name");

} elseif(isset($_GET["id"])) {
    // get data for display
    $sqlData->set("id", $_GET["id"]);
    $sql = $page->bobbyTable->selectId($kDefaultTable, $sqlData->get("id"));

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

$sqlData->applySql2html();

$body = $page->bodyBuilder->goHome("..");
$body .= $page->htmlHelper->setTitle($page_title);// before HotBooty
$page->htmlHelper->hotBooty();
//
    // form
    $body .= "<div>\n";
    $body .= $page->formHelper->tag();

        // fields
            // id
            if($sqlData->get("id") > 0) {
                $body .= $theHiddenInput->get("id", $sqlData);
            }
        //
            // name
            $nameAttr = new FieldAttributes(true, true);
            $body .= $theTextInput->get("name", $sqlData, "Name", NULL, $nameAttr);
        //
            // plane
                // fetch planes
                $PlaneList = array();

                // Add empty option first
                $PlaneList[""] = "--";

                $PlaneSQL = $page->bobbyTable->queryManage("SELECT `id`, `PlaneType`, `PlaneID` FROM `aircrafts` ORDER BY `PlaneID`");
                while($p = $PlaneSQL->fetch_object()) {
                    $PlaneList[$p->id] = "{$p->PlaneID} ({$p->PlaneType})";
                }
                $PlaneSQL->close();

            $body .= $theSelectInput->get("plane", $PlaneList, $sqlData, "Plane");
        //
            // variation
            $embedVariation = new FieldEmbedder("Variation", "&deg;E");
            $attrVariation = new FieldAttributes(true);
            $attrVariation->min = -180;
            $attrVariation->max = 180;
            $attrVariation->step = 0.1;
            $body .= $theNumberInput->get("variation", $sqlData, NULL, $attrVariation, $embedVariation);
        //
        $attrMass = new FieldAttributes();
        $attrMass->min = 0;

            // FrontMass
            $embedFrontMass = new FieldEmbedder("Front mass", "kg");
            $body .= $theNumberInput->get("FrontMass", $sqlData, NULL, $attrMass, $embedFrontMass);
        //
            // RearMass
            $body .= $page->waitress->tableOpen();
            $body .= $page->waitress->rowOpen();

            $body .= $page->waitress->cell("<b>Rear masses (optional):</b>\n");

            $embedMass = new FieldEmbedder("", "kg");

            $numRears = 2;
            $numLuggages = 4;

            for($rearIndex = 0; $rearIndex < $numRears; ++$rearIndex) {
                $body .= $page->waitress->cell(
                    $theNumberInput->get("Rear{$rearIndex}Mass", $sqlData, NULL, $attrMass, $embedMass)
                );
            }

            $body .= $page->waitress->rowClose();
            $body .= $page->waitress->tableClose();
        //
            // LuggageMass
            $body .= $page->waitress->tableOpen();
            $body .= $page->waitress->rowOpen();

            $body .= $page->waitress->cell("<b>Luggage masses (optional):</b>\n");

            for($luggageIndex = 0; $luggageIndex < $numLuggages; ++$luggageIndex) {
                $body .= $page->waitress->cell(
                    $theNumberInput->get("Luggage{$luggageIndex}Mass", $sqlData, NULL, $attrMass, $embedMass)
                );
            }

            $body .= $page->waitress->rowClose();
            $body .= $page->waitress->tableClose();
        //
            // Comment
            $body .= $theTextInput->get("comment", $sqlData, "Comment");
        //
    //
        // buttons
        $body .= $page->formHelper->subButt($sqlData->get("id") > 0, $sqlData->get("name"), "display.php?id={$sqlData->get('id')}");
    //
    $body .= "</div>\n";

echo $body;
?>
