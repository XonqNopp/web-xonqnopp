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


$MC = NULL;
$speed = NULL;
$altitude = NULL;
$temperature = NULL;
$windHeading = NULL;
$windSpeed = NULL;

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

	// MC
	$args = new stdClass();
	$args->type = "number";
	$args->title = "MC";
	$args->name = "MC[]";
	$args->value = $MC;
	$args->min = 0;
	$args->max = 359;
	$args->posttitle = "deg";
	$args->autofocus = true;
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

