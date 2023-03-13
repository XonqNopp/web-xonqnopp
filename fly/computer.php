<?php
/* TODO:
 */
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
// debug
//$page->initHTML();
//$page->LogLevelUp(6);
// CSS paths
$page->CSS_ppJump();
$page->CSS_ppWing();
$page->js_Form();
// init body
$body = "";


// GoHome
$gohome = new stdClass();
$body .= $page->GoHome($gohome);
// Set title and hot booty
$body .= $page->SetTitle("Flight computer");// before HotBooty
$page->HotBooty();


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

	$Tisa = $referenceT + $gradient * ($pressureAltitude / 1000.0);
	$delta = $oat - $Tisa;
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
$body .= $page->FormTag();

	// Density altitude
	$body .= "<div>\n";
		// altitude
		$args = new stdClass();
		$args->type = "number";
		$args->title = "Altitude";
		$args->name = "alt";
		$args->value = $alt;
		$args->min = -2000;
		$args->max = 60000;
		$args->posttitle = "ft&nbsp;";
		//$args->div = false;
		$args->size = 2;
		$args->autofocus = true;
		$body .= $page->FormField($args);
	//
		// qnh
		$args = new stdClass();
		$args->type = "number";
		$args->title = "QNH";
		$args->name = "qnh";
		$args->value = $qnh;
		$args->min = 900;
		$args->max = 1100;
		$args->posttitle = "hPa&nbsp;";
		//$args->div = false;
		$body .= $page->FormField($args);
	//
		// -> pressure altitude
		$pa = pressureAltitude($alt, $qnh);
		$args = new stdClass();
		$args->type = "text";
		$args->title = "Pressure altitude";
		$args->name = "pa";
		$args->value = $pa;
		$args->posttitle = "ft&nbsp;";
		//$args->div = false;
		$args->readonly = true;
		$body .= $page->FormField($args);
	//
		// oat
		$args = new stdClass();
		$args->type = "number";
		$args->title = "Temperature";
		$args->name = "oat";
		$args->value = $oat;
		$args->min = -50;
		$args->max = 100;
		$args->posttitle = "degC&nbsp;";
		//$args->div = false;
		$body .= $page->FormField($args);
	//
		// -> density altitude
		$args = new stdClass();
		$args->type = "text";
		$args->title = "Density altitude";
		$args->name = "DA";
		$args->value = densityAltitude($pa, $oat);
		$args->posttitle = "ft&nbsp;";
		//$args->div = false;
		$args->readonly = true;
		$body .= $page->FormField($args);

	$body .= "</div>\n";

$body .= "<div>&nbsp;</div>\n";

	// MC
	$args = new stdClass();
	$args->type = "number";
	$args->title = "MC";
	$args->name = "MC[]";
	$args->value = $MC;
	$args->min = 0;
	$args->max = 359;
	$args->posttitle = "deg";
	$body .= $page->FormField($args);
//
	// speed
	$args = new stdClass();
	$args->type = "number";
	$args->title = "speed";
	$args->name = "speed[]";
	$args->value = $speed;
	$args->min = 0;
	$args->posttitle = "kts";
	$body .= $page->FormField($args);
//
	// altitude
	$args = new stdClass();
	$args->type = "number";
	$args->title = "altitude";
	$args->name = "altitude[]";
	$args->value = $altitude;
	$args->posttitle = "ft";
	$body .= $page->FormField($args);
//
	// temperature
	$args = new stdClass();
	$args->type = "number";
	$args->title = "temperature";
	$args->name = "temperature[]";
	$args->value = $temperature;
	$args->posttitle = "degC";
	$body .= $page->FormField($args);
//
	// windHeading
	$args = new stdClass();
	$args->type = "number";
	$args->title = "wind hdg";
	$args->name = "windHeading[]";
	$args->value = $windHeading;
	$args->min = 0;
	$args->max = 359;
	$args->posttitle = "deg";
	$args->autofocus = true;
	$body .= $page->FormField($args);
//
	// windSpeed
	$args = new stdClass();
	$args->type = "number";
	$args->title = "wind speed";
	$args->name = "windSpeed[]";
	$args->value = $windSpeed;
	$args->min = 0;
	$args->posttitle = "kts";
	$body .= $page->FormField($args);
//
	// buttons
	$args = new stdClass();
	$args->cancelURL = "computer.php";
	$args->CloseTag = false;
	$body .= $page->SubButt(False, NULL, $args);
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
				$args = new stdClass();
				$args->type = "hidden";

				$args->name = "MC[]";
				$args->value = $MC;
				$body .= $page->FormField($args);

				$args->name = "speed[]";
				$args->value = $speed;
				$body .= $page->FormField($args);

				$args->name = "altitude[]";
				$args->value = $altitude;
				$body .= $page->FormField($args);

				$args->name = "temperature[]";
				$args->value = $temperature;
				$body .= $page->FormField($args);

				$args->name = "windHeading[]";
				$args->value = $windHeading;
				$body .= $page->FormField($args);

				$args->name = "windSpeed[]";
				$args->value = $windSpeed;
				$body .= $page->FormField($args);
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

//// Finish
echo $body;
unset($page);
?>

