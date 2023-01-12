<?php
require("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);

require("$funcpath/form.php");
use FieldAttributes;
use FieldEmbedder;
global $theHiddenInput;
global $theTextInput;
global $theNumberInput;


// debug
//$page->htmlHelper->init();
//$page->logger->levelUp(6);
// CSS paths
$page->cssHelper->dirUpWing();
$page->htmlHelper->jsForm();


$body = $page->bodyHelper->goHome();

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
		$body .= $theNumberInput->get("alt", $alt, NULL, 2, NULL, $attrAlt, $embedderAlt);
	//
		// qnh
		$embedderQnh = new FieldEmbedder("QNH", "hPa&nbsp;");
		$attrQnh = new FieldAttributes();
		$attrQnh->min = 900;
		$attrQnh->max = 1100;
		$attrQnh->step = 1;
		$body .= $theNumberInput->get("qnh", $qnh, NULL, NULL, NULL, $attrQnh, $embedderQnh);
	//
		// -> pressure altitude
		$pa = pressureAltitude($alt, $qnh);
		$paAttr = FieldAttributes();
		$paAttr->bReadonly = true;
		$paEmbedder = FieldEmbedder("Pressure altitude", "ft&nbsp;");
		$body .= $theTextInput->get("pa", $pa, NULL, 0, NULL, NULL, $paAttr, $paEmbedder);
	//
		// oat
		$attrOat = new FieldAttributes();
		$attrOat->min = -50;
		$attrOat->max = 100;
		$embedderOat = new FieldEmbedder("Temperature", "&deg;C&nbsp;");
		$body .= $theNumberInput->get("oat", $oat, NULL, NULL, NULL, $attrOat, $embedderOat);
	//
		// -> density altitude
		$daAttr = FieldAttributes();
		$daAttr->bReadonly = true;
		$daEmbedder = FieldEmbedder("Density altitude", "ft&nbsp;");
		$body .= $theTextInput->get("DA", densityAltitude($pa, $oat), NULL, 0, NULL, NULL, $daAttr, $daEmbedder);

	$body .= "</div>\n";

$body .= "<div>&nbsp;</div>\n";

$attrHdg = new FieldAttributes();
$attrHdg->min = 0;
$attrHdg->max = 359;

$embedderHdg = new FieldEmbedder("MC", "deg");

	// MC
	$embedderHdg->title = "MC";
	$body .= $theNumberInput->get("MC[]", $MC, NULL, NULL, NULL, $attrHdg, $embedderHdg);
//
	// speed
	$attrSpeed = new FieldAttributes();
	$attrSpeed->min = 0;
	$embedderSpeed = new FieldEmbedder("speed", "kts");
	$body .= $theNumberInput->get("speed[]", $speed, NULL, NULL, NULL, $attrSpeed, $embedderSpeed);
//
	// altitude
	$embedderAlt = new FieldEmbedder("altitude", "ft");
	$body .= $theNumberInput->get("altitude[]", $altitude, NULL, NULL, NULL, NULL, $embedderAlt);
//
	// temperature
	$embedderTemperature = new FieldEmbedder("temperature", "&deg;C");
	$body .= $theNumberInput->get("temperature[]", $temperature, NULL, NULL, NULL, NULL, $embedderTemperature);
//
	// windHeading
	$attrHdg->bAutofocus = true;
	$embedderHdg->title = "wind hdg";
	$body .= $theNumberInput->get("windHeading[]", $windHeading, NULL, NULL, NULL, $attrHeading, $embedderHdg);
//
	// windSpeed
	$embedderSpeed->title = "wind speed";
	$body .= $theNumberInput->get("windSpeed[]", $windSpeed, NULL, NULL, NULL, $attrSpeed, $embedderSpeed);
//
	// buttons
	$body .= $page->formHelper->subButt(False, NULL, "file", false);
//
	// Prepare table
	if(isset($_POST["MC"])) {
		$table .= "<div>\n";
		$table .= "<table>\n";
		$table .= "<tr>\n";
		$table .= "<th colspan=\"6\">Inputs</th>\n";
		$table .= "<th colspan=\"3\">Outputs</th>\n";
		$table .= "</tr>\n";
		$table .= "<tr>\n";
		$table .= "<th>MC [deg]</th>\n";
		$table .= "<th>v [kts]</th>\n";
		$table .= "<th>altitude [ft]</th>\n";
		$table .= "<th>T [degC]</th>\n";
		$table .= "<th>wind hdg [deg]</th>\n";
		$table .= "<th>v(wind) [kts]</th>\n";
		$table .= "<th>MH [deg]</th>\n";
		$table .= "<th>GS [kts]</th>\n";
		$table .= "<th>altimeter [ft]</th>\n";
		$table .= "</tr>\n";
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
				$table .= "<tr>\n";
				$table .= "<td>$MC</td>\n";
				$table .= "<td>$speed</td>\n";
				$table .= "<td>$altitude</td>\n";
				$table .= "<td>$temperature</td>\n";
				$table .= "<td>$windHeading</td>\n";
				$table .= "<td>$windSpeed</td>\n";
				$table .= "<td>$MH</td>\n";
				$table .= "<td>$GS</td>\n";
				$table .= "<td>$altimeter</td>\n";
				$table .= "</tr>\n";
		}
	}

$body .= "</form>\n";
$body .= "</div>\n";


if($table != "") {
	$table .= "</table>\n";
	$table .= "</div>\n";
}

$body .= $table;

// Finish
echo $body;
unset($page);
?>

