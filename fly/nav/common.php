<?php
$kTemplateFilename = "files/nav_template";
$kArmless = 1234570000;  // use this magic value when arm is not available
$kRearStationsNum = 2;
$kLuggageStationsNum = 4;
$kDefaultLuggageMass = 5;  // [kg] NavDetails: to duplicate a nav
$kFuelTanksNum = 4;
$kDefaultVariation = 3;  // [deg] NavDetails: to create empty nav


function getNavFilename($navId) {
    return "files/nav" . sprintf("%06d", $navId);
}


function deleteNavFile($navId, $fileExt) {
    $filename = getNavFilename($navId) . "." . $fileExt;
    if(!file_exists($filename)) {
        return;
    }

    unlink($filename);
}


function deleteNavPdfFile($navId) {
    deleteNavFile($navId, "pdf");
}


    // arrays
    function arrayKeysWithoutEmptyString($array) {
        if(array_key_exists("", $array)) {
            // Discard default empty key
            unset($array[""]);
        }

        return array_keys($array);
    }

    $kFuelUnits = array();
    $kFuelUnits[""] = 1;  // default unit is l
    $kFuelUnits["l"] = 1;  // default unit, no conversion
    $kFuelUnits["USG"] = 3.785;
    $kFuelUnits["ImpG"] = 4.55;

    $kFuelTypes = array();  // conversion from [l] to [kg]
    $kFuelTypes[""] = 0.72;  // default type is AVGAS 100LL
    $kFuelTypes["AVGAS"] = 0.72;  // convert l to kg
    $kFuelTypes["JET-A1"] = 0.804;  // convert l to kg

    $kMassUnits = array();
    $kMassUnits[""] = 1;  // default unit is kg
    $kMassUnits["kg"] = 1;  // default unit, no conversion
    $kMassUnits["lbs"] = 2.2;

    $kMeterPerInch = 0.0254;

    $kArmUnits = array();
    $kArmUnits[""] = 1;  // default unit is m
    $kArmUnits["m"] = 1;
    $kArmUnits["cm"] = 100;
    $kArmUnits["in"] = 1.0 / $kMeterPerInch;

    $kMomentUnits = array();
    $kMomentUnits[""] = 1;  // default is [m kg]
    $kMomentUnits["m kg"] = 1;
    $kMomentUnits["cm kg"] = 100;
    $kMomentUnits["in lbs"] = 2.2 / $kMeterPerInch;
//
    // Waypoints number constants
    // Used in:
    // NavDetails.php
    // NavWP.php
    class WaypointsClass {
        public $wayOut = NULL;
        public $wayBack = NULL;
        public $alternate = NULL;

        /**
         * Constructor
         *
         * @SuppressWarnings(PHPMD.MissingImport)
         */
        public function __construct() {
            $this->wayOut = new stdClass();
            $this->wayOut->base = 0;
            $this->wayOut->start = 1;
            $this->wayOut->last = 99;

            $this->wayBack = new stdClass();
            $this->wayBack->start = 101;
            $this->wayBack->last = 199;

            $this->alternate = new stdClass();
            $this->alternate->limit = 900;
            $this->alternate->start = 901;
            $this->alternate->last = 999;
        }

        public function getNext($waypoint) {
            if($waypoint == $this->wayOut->last || $waypoint == $this->wayBack->last) {
                return $this->alternate->start;
            }

            if($waypoint == $this->alternate->last) {
                return -1;
            }

            return $waypoint + 1;
        }

        public function isStart($waypoint) {
            return (
                $waypoint == $this->wayOut->start
                || $waypoint == $this->wayBack->start
                || $waypoint == $this->alternate->start
            );
        }

        public function isLast($waypoint) {
            return (
                $waypoint == $this->wayOut->last
                || $waypoint == $this->wayBack->last
                || $waypoint == $this->alternate->last
            );
        }

        public function isStartLast($waypoint) {
            return $this->isStart($waypoint) || $this->isLast($waypoint);
        }
    }

    $kWaypoints = new WaypointsClass();  // singleton
?>
