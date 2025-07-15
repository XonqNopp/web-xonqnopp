<?php
require_once("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);

require_once("$funcpath/form_fields.php");
global $theHiddenInput;
global $theTextInput;
global $theNumberInput;


// debug
//$page->htmlHelper->init();
//$page->logger->levelUp(6);
// CSS paths
$page->cssHelper->dirUpWing();
$page->htmlHelper->jsForm();


$body = $page->bodyBuilder->goHome();

// Set title and hot booty
$body .= $page->htmlHelper->setTitle("Flight computer");// before HotBooty
$page->htmlHelper->hotBooty();


function pressureAltitude($altitude, $qnh) {
    if($altitude === NULL) {
        return "";
    }

    $qne = 1013;  // [hPa]
    $factor = 28;  // [ft/hPa]

    return ceil($altitude + ($qne - $qnh) * $factor);
}


function densityAltitude($pressureAltitude, $oat) {
    if($oat === NULL) {
        return "";
    }

    $referenceT = 15;  // sea level
    $gradient = -2;  // [K/1000ft]
    $factor = 120;  // [ft/K]

    $tISA = $referenceT + $gradient * ($pressureAltitude / 1000.0);
    $delta = $oat - $tISA;
    return ceil($pressureAltitude + $factor * $delta);
}


function altimeterISA($altitude, $temperature) {
    if($altitude == NULL || $temperature == NULL) {
        return NULL;
    }

    $tISA = 15 - (2 * ($altitude / 1000));
    $deltaT = $tISA - $temperature;
    $effectiveAltitude = floor($altitude * (1 - 0.004 * $deltaT));
    return $effectiveAltitude;
}


function windInfluence($heading, $speed, $windHeading, $windSpeed) {
    $results = array("MH" => NULL, "GS" => NULL);
    if($heading == NULL || $speed == NULL || $windHeading == NULL || $windSpeed == NULL) {
        return $results;
    }

    $alpha1 = (360 + $heading + $windHeading) % 360;
    $sign = 1;
    if($alpha1 > 180) {
        $sign = -1;
        $alpha1 = (360 + $windHeading - $heading) % 360;
    }

    if($alpha1 == 0) {
        // plane goes with wind
        $results["MH"] = $heading;
        $results["GS"] = $speed + $windSpeed;
        return $results;
    }

    if($alpha1 == 180) {
        // plane goes against wind
        $results["MH"] = $heading;
        $results["GS"] = $speed - $windSpeed;
        return $results;
    }

    $sinAlpha1 = sin(deg2rad($alpha1));

    $alpha2 = rad2deg(asin($windSpeed / $speed * $sinAlpha1));

    $alpha3 = 180 - ($alpha1 + $alpha2);

    $results["MH"] = round($heading + $sign * $alpha2, 0);
    $results["GS"] = floor(sin(deg2rad($alpha3)) / $sinAlpha1 * $speed);

    return $results;
}


$alt = NULL;
$qnh = NULL;
$oat = NULL;

$MC = NULL;
$speed = NULL;
$altitude = NULL;
$temperature = NULL;
$windHeading = NULL;
$windSpeed = NULL;

if(isset($_POST["alt"])) {
    $alt = $_POST["alt"];
    $qnh = $_POST["qnh"];
    $oat = $_POST["oat"];
}

if(isset($_POST["MC"])) {
    $MC = $_POST["MC"][0];
    $speed = $_POST["speed"][0];
    $altitude = $_POST["altitude"][0];
    $temperature = $_POST["temperature"][0];
    $windHeading = $_POST["windHeading"][0];
    $windSpeed = $_POST["windSpeed"][0];
}

$table = "";

$body .= "<div style=\"text-align: center; margin: 1em;\">\n";
$body .= $page->formHelper->tag();

    // Density altitude
    $body .= "<div>\n";
        // altitude
        $attrAlt = new FieldAttributes(false, true);
        $attrAlt->min = -5000;
        $attrAlt->max = 60000;
        $embedderAlt = new FieldEmbedder("Altitude", "ft&nbsp;");
        $body .= $theNumberInput->get("alt", $alt, NULL, $attrAlt, $embedderAlt);
    //
        // qnh
        $embedderQnh = new FieldEmbedder("QNH", "hPa&nbsp;");
        $attrQnh = new FieldAttributes();
        $attrQnh->min = 900;
        $attrQnh->max = 1100;
        $attrQnh->step = 1;
        $body .= $theNumberInput->get("qnh", $qnh, NULL, $attrQnh, $embedderQnh);
    //
        // -> pressure altitude
        $pa = pressureAltitude($alt, $qnh);
        $paAttr = new FieldAttributes();
        $paAttr->isDisabled = true;
        $paEmbedder = new FieldEmbedder("Pressure altitude", "ft&nbsp;");
        $body .= $theTextInput->get("pa", $pa, NULL, NULL, $paAttr, $paEmbedder);
    //
        // oat
        $attrOat = new FieldAttributes();
        $attrOat->min = -50;
        $attrOat->max = 100;
        $embedderOat = new FieldEmbedder("Temperature", "&deg;C&nbsp;");
        $body .= $theNumberInput->get("oat", $oat, NULL, $attrOat, $embedderOat);
    //
        // -> density altitude
        $daAttr = new FieldAttributes();
        $daAttr->isDisabled = true;
        $daEmbedder = new FieldEmbedder("Density altitude", "ft&nbsp;");
        $body .= $theTextInput->get("DA", densityAltitude($pa, $oat), NULL, NULL, $daAttr, $daEmbedder);

    $body .= "</div>\n";

$body .= "<div>&nbsp;</div>\n";

$attrHdg = new FieldAttributes();
$attrHdg->min = 0;
$attrHdg->max = 359;

$embedderHdg = new FieldEmbedder("MC", "deg");

    // MC
    $embedderHdg->title = "MC";
    $body .= $theNumberInput->get("MC[]", $MC, NULL, $attrHdg, $embedderHdg);
//
    // speed
    $attrSpeed = new FieldAttributes();
    $attrSpeed->min = 0;
    $embedderSpeed = new FieldEmbedder("speed", "kts");
    $body .= $theNumberInput->get("speed[]", $speed, NULL, $attrSpeed, $embedderSpeed);
//
    // altitude
    $embedderAlt = new FieldEmbedder("altitude", "ft");
    $body .= $theNumberInput->get("altitude[]", $altitude, NULL, NULL, $embedderAlt);
//
    // temperature
    $embedderTemperature = new FieldEmbedder("temperature", "&deg;C");
    $body .= $theNumberInput->get("temperature[]", $temperature, NULL, NULL, $embedderTemperature);
//
    // windHeading
    $embedderHdg->title = "wind hdg";
    $body .= $theNumberInput->get("windHeading[]", $windHeading, NULL, $attrHdg, $embedderHdg);
//
    // windSpeed
    $embedderSpeed->title = "wind speed";
    $body .= $theNumberInput->get("windSpeed[]", $windSpeed, NULL, $attrSpeed, $embedderSpeed);
//
    // buttons
    $body .= $page->formHelper->subButt(False, NULL, "file", false);
//
    // Prepare table
    if(isset($_POST["MC"])) {
        $table .= "<div>\n";
        $table .= $page->butler->tableOpen();
        $table .= $page->butler->rowOpen();
        $table .= $page->butler->headerCell("Inputs", array("colspan" => 6));
        $table .= $page->butler->headerCell("Outputs", array("colspan" => 3));
        $table .= $page->butler->rowClose();

        $table .= $page->butler->rowOpen();
        $table .= $page->butler->headerCell("MC [deg]");
        $table .= $page->butler->headerCell("v [kts]");
        $table .= $page->butler->headerCell("altitude [ft]");
        $table .= $page->butler->headerCell("T [degC]");
        $table .= $page->butler->headerCell("wind hdg [deg]");
        $table .= $page->butler->headerCell("v(wind) [kts]");
        $table .= $page->butler->headerCell("MH [deg]");
        $table .= $page->butler->headerCell("GS [kts]");
        $table .= $page->butler->headerCell("altimeter [ft]");
        $table .= $page->butler->rowClose();
    }
//
    // Now iterate over past results
    if(isset($_POST["MC"])) {
        for($i = 0; $i < count($_POST["MC"]); $i++) {
                // vars
                $MC = $_POST["MC"][$i];
                $speed = $_POST["speed"][$i];
                $altitude = $_POST["altitude"][$i];
                $temperature = $_POST["temperature"][$i];
                $windHeading = $_POST["windHeading"][$i];
                $windSpeed = $_POST["windSpeed"][$i];

                // skip empty lines
                if($MC == NULL && $speed == NULL && $altitude == NULL && $temperature == NULL && $windHeading == NULL && $windSpeed == NULL) {
                    continue;
                }
            //
                // compute
                $altimeter = altimeterISA($altitude, $temperature);
                $windInfluence = windInfluence($MC, $speed, $windHeading, $windSpeed);
                $MH = $windInfluence["MH"];
                $GS = $windInfluence["GS"];
            //
                // form
                $body .= $theHiddenInput->get("MC[]", $MC);
                $body .= $theHiddenInput->get("speed[]", $speed);
                $body .= $theHiddenInput->get("altitude[]", $altitude);
                $body .= $theHiddenInput->get("temperature[]", $temperature);
                $body .= $theHiddenInput->get("windHeading[]", $windHeading);
                $body .= $theHiddenInput->get("windSpeed[]", $windSpeed);
            //
                // table
                $table .= $page->butler->rowOpen();
                $table .= $page->butler->cell($MC);
                $table .= $page->butler->cell($speed);
                $table .= $page->butler->cell($altitude);
                $table .= $page->butler->cell($temperature);
                $table .= $page->butler->cell($windHeading);
                $table .= $page->butler->cell($windSpeed);
                $table .= $page->butler->cell($MH);
                $table .= $page->butler->cell($GS);
                $table .= $page->butler->cell($altimeter);
                $table .= $page->butler->rowClose();
        }
    }

$body .= "</form>\n";
$body .= "</div>\n";


if($table != "") {
    $table .= $page->butler->tableClose();
    $table .= "</div>\n";
}

$body .= $table;

echo $body;
?>
