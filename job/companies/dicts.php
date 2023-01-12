<?php

$kFields = array(
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


function fields($item=array()) {
    global $kFields;

    if($item == array()) {
        return $kFields;
    }

    if($item == array("")) {
        return array();
    }

    $out = array();

    foreach($item as $in) {
        //// TEMPORARY
        if($in == "engineer") { $in = "consulting"; }
        $out[] = $kFields[$in];
    }

    return $out;
}


$kPhysicist = array(
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


function physicist($item=array()) {
    global $kPhysicist;

    if($item == array()) {
        return $kPhysicist;
    }

    if($item == array("")) {
        return array();
    }

    $out = array();

    foreach($item as $in) {
        $out[] = $kPhysicist[$in];
    }

    return $out;
}
?>
