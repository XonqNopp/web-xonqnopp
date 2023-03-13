<?php
/*** Created: Tue 2015-01-06 09:19:46 CET
 *
 * TODO:
 *
 */

function fields($item = array()) {
	$back = array(
		"physics" => "physics",
		"aero" => "aerodynamics",
		"aviation" => "aviation",
		"space" => "space",
		"nuphi" => "scientific modelling",
		"hydro" => "hydraulics",
		"fluid" => "fluid dynamics",
		"plasma" => "plasma",
		"waves" => "waves",
		"engineering" => "engineering",
		"material" => "material science",
		"solar" => "solar cells",
		"optics" => "optics",
		"consulting" => "ingenieur/consulting",
		"metro" => "metrology",
		"meteo" => "meteorology",
		"prog" => "programmation",
		"auto" => "automation",
		"chemistry" => "chemistry",
		"climate" => "climate",
		"dams" => "dams",
		"electricity" => "electricity",
		"NRJ" => "energy",
		"env" => "environment",
		"noise" => "noise",
		"buildings" => "buildings",
		"gas" => "gas",
		"oil" => "oil",
		"food" => "food",
		"health" => "health",
		"pharma" => "pharmaceutics"
	);
	if($item != array()) {
		$out = array();
		if($item !== array("")) {
			foreach($item as $in) {
				//// TEMPORARY
				if($in == "engineer") { $in = "consulting"; }
				$out[] = $back[$in];
			}
		}
		return $out;
	} else {
		return $back;
	}
}

function physicist($item = array()) {
	$back = array(
		"physics" => "physics",
		"nuphi" => "scientific modelling",
		"RnD" => "R&amp;D",
		"engineering" => "engineering",
		"meteo" => "meteorology",
		"fluid" => "fluid dynamics",
		"chemistry" => "chemistry",
		"prog" => "programmation",
		"management" => "management"
	);
	if($item != array()) {
		$out = array();
		if($item !== array("")) {
			foreach($item as $in) {
				$out[] = $back[$in];
			}
		}
		return $out;
	} else {
		return $back;
	}
}

function media($item = "") {
	$back = array(
		"mail"    => "mail",
		"website" => "website",
		"linkedin" => "linkedin",
		"phone"   => "phone",
		"meeting" => "meeting"
	);
	return $back;
}

function kind($item = "") {
	$back = array(
		"application" => "application",
		"offer"  => "offer",
		"ideas"  => "ideas",
		"conversation" => "conversation",
		"misc"   => "misc"
	);
	return $back;
}

?>
