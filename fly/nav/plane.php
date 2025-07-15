<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require_once("common.php");
global $kArmless;

require_once("$funcpath/form_fields.php");
global $theHiddenInput;
global $theTextInput;
global $theSelectInput;
global $theNumberInput;
global $theDateInput;


$page = new PhPage($rootPath);
$page->bobbyTable->init();
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
    $sqlData->addField("FrontMaxMass", "d", 0);

    $sqlData->addField("Rear0Arm", "d", $kArmless);
    $sqlData->addField("Rear0MaxMass", "d", 0);
    $sqlData->addField("Rear1Arm", "d", $kArmless);
    $sqlData->addField("Rear1MaxMass", "d", 0);

    $sqlData->addField("Luggage0Arm", "d", $kArmless);
    $sqlData->addField("Luggage0MaxMass", "i", 0);
    $sqlData->addField("Luggage1Arm", "d", $kArmless);
    $sqlData->addField("Luggage1MaxMass", "i", 0);
    $sqlData->addField("Luggage2Arm", "d", $kArmless);
    $sqlData->addField("Luggage2MaxMass", "i", 0);
    $sqlData->addField("Luggage3Arm", "d", $kArmless);
    $sqlData->addField("Luggage3MaxMass", "i", 0);

    $sqlData->addField("LuggageMaxTotalMass", "i", 0);

    $sqlData->addField("Fuel0Arm", "d", 0);
    $sqlData->addField("Fuel0TotalCapacity", "i", 0);
    $sqlData->addField("Fuel0Unusable", "i", 0);
    $sqlData->addField("Fuel0AllOrNothing", "i", 0);

    $sqlData->addField("Fuel1Arm", "d", $kArmless);
    $sqlData->addField("Fuel1TotalCapacity", "i", 0);
    $sqlData->addField("Fuel1Unusable", "i", 0);
    $sqlData->addField("Fuel1AllOrNothing", "i", 0);

    $sqlData->addField("Fuel2Arm", "d", $kArmless);
    $sqlData->addField("Fuel2TotalCapacity", "i", 0);
    $sqlData->addField("Fuel2Unusable", "i", 0);
    $sqlData->addField("Fuel2AllOrNothing", "i", 0);

    $sqlData->addField("Fuel3Arm", "d", $kArmless);
    $sqlData->addField("Fuel3TotalCapacity", "i", 0);
    $sqlData->addField("Fuel3Unusable", "i", 0);
    $sqlData->addField("Fuel3AllOrNothing", "i", 0);

    $sqlData->addField("FuelCons", "i", 0);
    $sqlData->addField("FuelUnit", "s", "");
    $sqlData->addField("FuelType", "s", "");
//
    $armFields = array(
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


if(isset($_POST["erase"]) || isset($_POST["submit"])) {
    $page->loginHelper->notAllowed();

    $sqlData->setDataValuesFromPost($page);

    foreach($armFields as $arm) {
        if($sqlData->get($arm) == "") {
            $sqlData->set($arm, $kArmless);
        }
    }

    $roundedFields = array(
        "FrontArm",
        "FrontMaxMass",
        "Rear0Arm",
        "Rear0MaxMass",
        "Rear1Arm",
        "Rear1MaxMass",
        "Luggage0Arm",
        "Luggage1Arm",
        "Luggage2Arm",
        "Luggage3Arm",
        "Fuel0Arm",
        "Fuel1Arm",
        "Fuel2Arm",
        "Fuel3Arm",
    );
    foreach($roundedFields as $field) {
        $sqlData->applyRound($field);
    }
}


if(isset($_POST["erase"])) {
    // delete entry
    $sql = $page->bobbyTable->idManage("SELECT COUNT(*) AS `tot` FROM `NavList` WHERE `plane` = ?", $sqlData->get("id"));
    $sql->bind_result($tot);
    $sql->fetch();
    $sql->close();

    if($tot == 0) {
        $sql = $page->bobbyTable->idManage("DELETE FROM `{$page->bobbyTable->dbName}`.`aircrafts` WHERE `aircrafts`.`id` = ? LIMIT 1;", $sqlData->get("id"));
        $page->htmlHelper->headerLocation();

    } else {
        $navigation = "navigation" . ($tot > 1 ? "s" : "");
        $page->logger->error("Cannot erase plane, used in $tot $navigation; edit them before");
        $page_title = "Edit infos for " . $sqlData->get("PlaneID");
    }

    // Remove char escaping
    $sqlData->applySql2html();

    // Reset strings without escaping for later input fields
    $sqlData->set("PlaneType");
    $sqlData->set("PlaneID");
    $sqlData->set("DryEmptyTimestamp");

} elseif(isset($_POST["submit"])) {
    // process armless",
    foreach($armFields as $field) {
        if($sqlData->get($field) == "") {
            $sqlData->set($field, $kArmless);
        }
    }

    if(isset($_POST["id"])) {
        // update
        $page->bobbyTable->queryUpdate($TABLE, $sqlData);

    } else {
        // insert
        $id = $page->bobbyTable->queryInsert($TABLE, $sqlData);
        $sqlData->set("id", $id);
    }

    $page->htmlHelper->headerLocation("index.php#a" . $sqlData->get("id"));
    $page_title = "Edit infos for " . $sqlData->get("PlaneID");

    // Remove char escaping
    $sqlData->applySql2html();

    // Reset strings without escaping for later input fields
    $sqlData->set("PlaneType");
    $sqlData->set("PlaneID");
    $sqlData->set("DryEmptyTimestamp");

    foreach($armFields as $arm) {
        if($sqlData->get($arm) == $kArmless) {
            $sqlData->set($arm, "");
        }
    }

} elseif(isset($_GET["id"])) {
    // get data for display
    $sqlData->set("id", $_GET["id"]);
    $theplane = $page->bobbyTable->selectId($TABLE, $sqlData->get("id"));

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
        $sqlData->fields["FrontMaxMass"]->value,

        $sqlData->fields["Rear0Arm"]->value,
        $sqlData->fields["Rear0MaxMass"]->value,
        $sqlData->fields["Rear1Arm"]->value,
        $sqlData->fields["Rear1MaxMass"]->value,

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
    $sqlData->applySql2html();

    foreach($armFields as $arm) {
        if($sqlData->get($arm) == $kArmless) {
            $sqlData->set($arm, "");
        }
    }

    $page_title = "Edit infos for " . $sqlData->get("PlaneID");
}


function cellOptionalArm($armName, $sqlData, $armAttr, $armEmbedder) {
    global $page;
    global $kArmless;
    global $theNumberInput;

    $cell = $page->waitress->cellOpen();
    $armValue = $sqlData->get($armName);
    if($armValue == $kArmless) {
        $armValue = "";
    }
    $cell .= $theNumberInput->get($armName, $armValue, NULL, $armAttr, $armEmbedder);
    $cell .= $page->waitress->cellClose();
    return $cell;
}


$body = $page->bodyBuilder->goHome("..");
$body .= $page->htmlHelper->setTitle($page_title);// before HotBooty
$page->htmlHelper->hotBooty();

    // form
    $body .= "<div><!-- form -->\n";

    if($page->loginHelper->userIsAdmin()) {
        $body .= $page->formHelper->tag();
    }

    $disabled = !$page->loginHelper->userIsAdmin();

    $disabledAttr = new FieldAttributes();
    $disabledAttr->isDisabled = $disabled;

    $noDivEmptyEmbedder = new FieldEmbedder();
    $noDivEmptyEmbedder->hasDiv = false;

        // fields
            // id
            if($sqlData->get("id") > 0) {
                $body .= $theHiddenInput->get("id", $sqlData);
            }
        //
            // Plane
            $body .= $page->waitress->tableOpen();
            $body .= $page->waitress->rowOpen();
            $body .= $page->waitress->cell("<b>Plane:</b>");

                // type
                $attr = new FieldAttributes(true, true);
                $attr->isDisabled = $disabled;

                $body .= $page->waitress->cell(
                    $theTextInput->get("PlaneType", $sqlData, "type", NULL, $attr)
                );
            //
                // ID
                $idAttr = new FieldAttributes(true);
                $idAttr->isDisabled = $disabled;

                $body .= $page->waitress->cell(
                    $theTextInput->get("PlaneID", $sqlData, "ID", NULL, $idAttr)
                );

            $body .= $page->waitress->rowClose();
            $body .= $page->waitress->tableClose();
        //
            // Speed
            $body .= $page->waitress->tableOpen();
            $body .= $page->waitress->rowOpen();
            $body .= $page->waitress->cell("<b>Speed:</b>");

            $attrSpeed = new FieldAttributes(true);
            $attrSpeed->isDisabled = $disabled;
            $attrSpeed->min = 0;

            $embedderSpeed = new FieldEmbedder(NULL, "kts");
            $embedderSpeed->hasDiv = false;

            $embedderSpeed->title = "planning";
            $body .= $page->waitress->cell(
                $theNumberInput->get("PlanningSpeed", $sqlData, NULL, $attrSpeed, $embedderSpeed)
            );

            $embedderSpeed->title = "climb";
            $body .= $page->waitress->cell(
                $theNumberInput->get("ClimbSpeed", $sqlData, NULL, $attrSpeed, $embedderSpeed)
            );

            $body .= $page->waitress->rowClose();
            $body .= $page->waitress->tableClose();

        $attrMaxMass = new FieldAttributes();
        $attrMaxMass->isDisabled = $disabled;
        $attrMaxMass->step = 0.01;
        $attrMaxMass->min = 0;

            // Dry empty
            $body .= $page->waitress->tableOpen();
            $body .= $page->waitress->rowOpen();
            $body .= $page->waitress->cell("<b>Dry empty:</b>");

            $emptyEmbedder = new FieldEmbedder();
            $emptyEmbedder->hasDiv = false;

                // Dry empty mass
                $body .= $page->waitress->cellOpen();

                $emptyEmbedder->title = "mass";
                $attrMaxMass->isRequired = true;
                $body .= $theNumberInput->get("DryEmptyMass", $sqlData, NULL, $attrMaxMass, $emptyEmbedder);
                $attrMaxMass->isRequired = false;

                $body .= $theSelectInput->get(
                    "MassUnit",
                    $page->utilsHelper->arraySequential2Associative(arrayKeysWithoutEmptyString($kMassUnits)),
                    $sqlData,
                    "",
                    $disabledAttr,
                    $noDivEmptyEmbedder
                );

                $body .= $page->waitress->cellClose();
            //
                // Arm unit
                $armUnitAttr = new FieldAttributes();
                $armUnitAttr->isDisabled = $disabled;

                $body .= $page->waitress->cell(
                    $theSelectInput->get(
                        "ArmUnit",
                        $page->utilsHelper->arraySequential2Associative(arrayKeysWithoutEmptyString($kArmUnits)),
                        $sqlData,
                        "Arm unit",
                        $armUnitAttr,
                    )
                );
            //
                // Dry empty moment
                $body .= $page->waitress->cellOpen();

                $emptyEmbedder->title = "moment";
                $attrEmptyMoment = new FieldAttributes(true);
                $attrEmptyMoment->isDisabled = $disabled;
                $attrEmptyMoment->min = 0;
                $attrEmptyMoment->step = $DEFAULT_STEP;
                $body .= $theNumberInput->get("DryEmptyMoment", $sqlData, NULL, $attrEmptyMoment, $emptyEmbedder);

                $body .= $theSelectInput->get(
                    "MomentUnit",
                    $page->utilsHelper->arraySequential2Associative(arrayKeysWithoutEmptyString($kMomentUnits)),
                    $sqlData,
                    "",
                    $disabledAttr,
                    $noDivEmptyEmbedder
                );

                $body .= $page->waitress->cellClose();
            //
                // Timestamp of measures
                $timestampEmbedder = new FieldEmbedder();
                $timestampEmbedder->hasDiv = false;
                $timestampEmbedder->title = "Timestamp of measures";
                $attrTimestamp = new FieldAttributes(true);
                $attrTimestamp->isDisabled = $disabled;
                $attrTimestamp->max = "now";

                $body .= $page->waitress->cell(
                    $theDateInput->get("DryEmptyTimestamp", $sqlData, NULL, $attrTimestamp, $timestampEmbedder)
                );

            $body .= $page->waitress->rowClose();
            $body .= $page->waitress->tableClose();
        //
            // Max mass
            $body .= $page->waitress->tableOpen();
            $body .= $page->waitress->rowOpen();
            $body .= $page->waitress->cell("<b>Maximum mass:</b>");

                $attrMaxMass->isRequired = true;
                $body .= $page->waitress->cell(
                    $theNumberInput->get("MTOW", $sqlData, "Take-Off", $attrMaxMass)
                );
            //
                $attrMaxMass->isRequired = false;
                $body .= $page->waitress->cell(
                    $theNumberInput->get("MLDGW", $sqlData, "Landing (optional)", $attrMaxMass)
                );

            $body .= $page->waitress->rowClose();
            $body .= $page->waitress->tableClose();
        //
            // GC boundaries
            $body .= $page->waitress->tableOpen();
            $body .= $page->waitress->rowOpen();
            $body .= $page->waitress->cell("<b>Gravity center boundaries:</b>");

            $attrGC = new FieldAttributes(true);
            $attrGC->isDisabled = $disabled;
            $attrGC->step = $DEFAULT_STEP;

            $body .= $page->waitress->cell(
                $theNumberInput->get("GCmin", $sqlData, "Highest minimum of GC", $attrGC)
            );

            $body .= $page->waitress->cell(
                $theNumberInput->get("GCmax", $sqlData, "Lowest maximum of GC", $attrGC)
            );

            $body .= $page->waitress->rowClose();
            $body .= $page->waitress->tableClose();

        $attrArm = new FieldAttributes(true);
        $attrArm->isDisabled = $disabled;
        $attrArm->step = $DEFAULT_STEP;

        $noDivEmbedder = new FieldEmbedder();
        $noDivEmbedder->hasDiv = false;

            // Front+Rear arms
            $body .= "<$hSubtitle>Front and rear arms</$hSubtitle>\n";
            $body .= $page->waitress->tableOpen();

                // Header row
                $body .= $page->waitress->rowOpen(array("class" => "bo"));
                $body .= $page->waitress->cell();
                $body .= $page->waitress->cell("Arm");
                $body .= $page->waitress->cell("Max mass");
                $body .= $page->waitress->rowClose();

            $body .= $page->waitress->rowOpen();
            $body .= $page->waitress->cell("Front (required)", array("class" => "bo"));
            $body .= $page->waitress->cell(
                $theNumberInput->get("FrontArm", $sqlData, NULL, $attrArm, $noDivEmptyEmbedder)
            );
            $body .= $page->waitress->cell(
                $theNumberInput->get("FrontMaxMass", $sqlData, NULL, $attrMaxMass, $noDivEmptyEmbedder)
            );
            $body .= $page->waitress->rowClose();

            $attrArm->isRequired = false;

                // Rear arms
                for($rearIndex = 0; $rearIndex < $kRearStationsNum; ++$rearIndex) {
                    $body .= $page->waitress->rowOpen();
                    $body .= $page->waitress->cell("Rear #" . ($rearIndex + 1), array("class" => "bo"));
                    $body .= cellOptionalArm("Rear{$rearIndex}Arm", $sqlData, $attrArm, $noDivEmbedder);

                    $body .= $page->waitress->cell(
                        $theNumberInput->get("Rear{$rearIndex}MaxMass", $sqlData, NULL, $attrMaxMass, $noDivEmbedder)
                    );
                    $body .= $page->waitress->rowClose();
                }

                $body .= $page->waitress->tableClose();
        //
            // Luggage stations
            $body .= "<$hSubtitle>Luggages</$hSubtitle>\n";
            $body .= $page->waitress->tableOpen();

                // Header row
                $body .= $page->waitress->rowOpen(array("class" => "bo"));
                $body .= $page->waitress->cell();
                $body .= $page->waitress->cell("Arm");
                $body .= $page->waitress->cell("Max mass");
                $body .= $page->waitress->rowClose();

            for($luggageIndex = 0; $luggageIndex < $kLuggageStationsNum; ++$luggageIndex) {
                $body .= $page->waitress->rowOpen();

                $body .= $page->waitress->cell("#" . ($luggageIndex + 1), array("class" => "bo"));
                $body .= cellOptionalArm("Luggage{$luggageIndex}Arm", $sqlData, $attrArm, $noDivEmptyEmbedder);

                $body .= $page->waitress->cell(
                    $theNumberInput->get("Luggage{$luggageIndex}MaxMass", $sqlData, NULL, $attrMaxMass, $noDivEmptyEmbedder)
                );
                $body .= $page->waitress->rowClose();
            }

                // Max Luggage total mass
                $body .= $page->waitress->rowOpen();

                $body .= $page->waitress->cell("Max total mass:", array("class" => "bo"));
                $body .= $page->waitress->cell();  // no arm

                $body .= $page->waitress->cell(
                    $theNumberInput->get("LuggageMaxTotalMass", $sqlData, NULL, $attrMaxMass, $noDivEmptyEmbedder)
                );

                $body .= $page->waitress->rowClose();
            //

            $body .= $page->waitress->tableClose();
        //
            // Fuel
            $body .= "<$hSubtitle>Fuel</$hSubtitle>\n";

                // tanks
                $body .= $page->waitress->tableOpen();

                    // Header row
                    $body .= $page->waitress->rowOpen(array("class" => "bo"));
                    $body .= $page->waitress->cell("Fuel tank");
                    $body .= $page->waitress->cell("Arm");
                    $body .= $page->waitress->cell("Total capacity");
                    $body .= $page->waitress->cell("Unusable");
                    $body .= $page->waitress->cell("All or nothing");
                    $body .= $page->waitress->rowClose();

                $attrQuantity = new FieldAttributes(true);
                $attrQuantity->isDisabled = $disabled;
                $attrQuantity->min = 0;
                $attrQuantity->step = 1;

                $attrArm->isRequired = true;  // first is required

                for($fuelIndex = 0; $fuelIndex < $kFuelTanksNum; ++$fuelIndex) {
                    $body .= $page->waitress->rowOpen();

                    $station = "#" . ($fuelIndex + 1);
                    if($attrArm->isRequired) {
                        $station .= " (required)";
                    }
                    $body .= $page->waitress->cell($station, array("class" => "bo"));
                    $body .= cellOptionalArm("Fuel{$fuelIndex}Arm", $sqlData, $attrArm, $noDivEmptyEmbedder);

                    $body .= $page->waitress->cell(
                        $theNumberInput->get("Fuel{$fuelIndex}TotalCapacity", $sqlData, NULL, $attrQuantity, $noDivEmptyEmbedder)
                    );

                    $body .= $page->waitress->cell(
                        $theNumberInput->get("Fuel{$fuelIndex}Unusable", $sqlData, NULL, $attrQuantity, $noDivEmptyEmbedder)
                    );

                    $body .= $page->waitress->cell(
                        $theSelectInput->get(
                            "Fuel{$fuelIndex}AllOrNothing",
                            array("no", "yes"),
                            $sqlData,
                            "",
                            $disabledAttr,
                            $noDivEmptyEmbedder
                        )
                    );

                    $body .= $page->waitress->rowClose();

                    $attrArm->isRequired = false;  // only first is required
                    $attrQuantity->isRequired = false;  // only first is required
                }

                $body .= $page->waitress->tableClose();
            //
                // Fuel consumption
                $body .= "<div class=\"consumption\">\n";
                $body .= "<b>Consumption:</b>\n";

                    // FuelCons
                    $attr = new FieldAttributes(true);
                    $attr->isDisabled = $disabled;
                    $attr->min = 0;

                    $body .= $theNumberInput->get("FuelCons", $sqlData, "Fuel per hour", $attr, $noDivEmbedder);
                    $noDivEmbedder->title = NULL;
                //
                    // FuelUnit
                    $body .= $theSelectInput->get(
                        "FuelUnit",
                        $page->utilsHelper->arraySequential2Associative(arrayKeysWithoutEmptyString($kFuelUnits)),
                        $sqlData,
                        "",
                        $disabledAttr,
                        $noDivEmbedder
                    );
                //
                    // FuelType
                    $body .= $theSelectInput->get(
                        "FuelType",
                        $page->utilsHelper->arraySequential2Associative(arrayKeysWithoutEmptyString($kFuelTypes)),
                        $sqlData,
                        "",
                        $disabledAttr,
                        $noDivEmbedder
                    );

                $body .= "</div><!-- consumption -->\n";

    if($page->loginHelper->userIsAdmin()) {
        // buttons (only if admin)
        $body .= $page->formHelper->subButt($sqlData->get("id") > 0, $sqlData->get("PlaneID"));
    }

    $body .= "</div><!-- form -->\n";

echo $body;
?>
