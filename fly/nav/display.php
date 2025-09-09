<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";

require_once("common.php");


$docVersion = "2025-07-13";

$page = new PhPage($rootPath);
$page->bobbyTable->init();

// debug
//$page->htmlHelper->init();
//$page->logger->levelUp(6);

// CSS
$page->cssHelper->dirUpWing();

$isAdmin = $page->loginHelper->userIsAdmin();

$body = "";

$kDefaultPrecision = 3;
$kNoArm = 1230000000;  // magic value to use when we want armless but still display


// Aircraft data used to compute nav
class Aircraft {
    public $sqlID = 0;
    public $type = "";
    public $identification = "";
    public $speedPlanning = 0;
    public $speedClimb = 0;
}

    // Mass + GC

    // Store:
    // * mass [kg]
    // * mass unit
    // * arm
    // * arm unit
    // * moment
    // * moment unit
    // Compute:
    // * moment
    // * arm (GC)
    // * mass (if not kg)
    //
    // Mass-moment
    // Contains information about mass, arm and moment.
    // Usually 2 out of 3 are provided and the remaining is computed.

        // GC data
            /**
             * Object having a mass, arm and moment.
             * Used for computation of gravity center.
             */
            class MassMomentObject {
                private $precision = NULL;  // Rounding precision

                public $mass = 0;  // Mass [kg] at input time, can be converted internally at a later stage
                public $arm = NULL;  // Arm - cannot use constant here, do in construct
                public $moment = 0;  // Moment (mass x arm)

                public $massUnit = "";  // Mass unit
                private $massUnitInUse = "";  // mass unit of the stored value
                public $maxMass = 0;  // Maximum mass
                public $isArmlessAllowed = false;

                public function __construct() {
                    global $kDefaultPrecision;
                    $this->precision = $kDefaultPrecision;

                    global $kArmless;
                    $this->arm = $kArmless;
                }

                public function __toString() {
                    return "MassMomentObject("
                        . "mass={$this->mass}"
                        . ", massUnit={$this->massUnit}"
                        . ", max={$this->maxMass}"
                        . ", arm={$this->arm}"
                        . ", moment={$this->moment}"
                        . ")";
                }

                /**
                 * Round a value
                 */
                public function rounding($value) {
                    return round($value, $this->precision);
                }

                /**
                 * Conversion from kg (input) to the massUnit.
                 */
                public function convertMass() {
                    if($this->massUnit == $this->massUnitInUse) {
                        // No conversion required;
                        return;
                    }

                    global $kMassUnits;
                    $this->mass = $this->rounding($this->mass * $kMassUnits[$this->massUnit]);

                    // Store the mass unit in use so if called more than once, it has no more effect
                    $this->massUnitInUse = $this->massUnit;
                }

                /**
                 * Get the moment of the object with rounding.
                 * If the moment is not part of the inputs, it is computed from mass and arm.
                 *
                 * Returns:
                 *     (float) moment
                 */
                public function getMoment() {
                    if($this->moment != 0) {
                        return $this->rounding($this->moment);
                    }

                    global $kArmless;
                    if(!$this->isArmlessAllowed && $this->arm == $kArmless && $this->mass > 0) {
                        // Arm not available BUT mass provided: error
                        global $page;
                        $page->logger->fatal("Cannot set mass for armless: " . (string)$this);
                    }

                    return $this->rounding($this->mass * $this->arm);
                }

                /**
                 * Get the arm of the object with rounding.
                 * If the arm is not part of the inputs, it is computed from mass and moment.
                 *
                 * Returns:
                 *     (float) arm
                 */
                public function getArm() {
                    if($this->arm !== NULL) {
                        return $this->rounding($this->arm);
                    }

                    if($this->moment == 0 || $this->mass == 0) {
                        return 0;
                    }

                    return $this->rounding(1.0 * $this->moment / $this->mass);
                }

                /**
                 * Check if mass is not too much
                 *
                 * Returns:
                 *     (bool) true if mass exceeds the max mass (if provided)
                 */
                public function isMassTooMuch() {
                    if ($this->maxMass == 0) {
                        return false;
                    }

                    return $this->mass > $this->maxMass;
                }

                /**
                 * Addition of 2 objects: mass, moment
                 *
                 * Args:
                 *     other (object): the object to add to this
                 */
                public function add($other) {
                    $this->mass += $other->mass;
                    $this->moment += $other->getMoment();
                }
            }
        //
            /**
             * Singleton to hold all information required to compute the gravity center and the mass.
             *
             * @SuppressWarnings(PHPMD.TooManyFields)
             */
            class GcData {
                public $massUnit = "?";  // Mass unit
                public $armUnit = "?";  // Arm unit
                public $momentUnit = "?";  // Moment unit

                public $maxTOW = 0;  // Maximum Take-Off Weight
                public $maxLdgW = 0;  // Maximum Landing Weight

                public $gcBoundaries = NULL;  // boundaries of GC

                public $dryEmpty = NULL;  // data of dry+empty plane
                public $dryEmptyTimestamp = NULL;  // timestamp of dry+empty measurement

                public $front = NULL;  // data for front row
                public $rears = NULL;  // dynamic array

                public $luggages = NULL;  // dynamic array

                // Luggage total mass: MassMoment object so we can store mass+maxMass
                // if >0 then all luggage stations together shall not exceed this value
                public $luggageTotalMass = NULL;

                // Data for all fuel tanks
                public $fuelUnusables = NULL;  // dynamic array
                public $fuelQuantities = NULL;  // dynamic array

                public $zeroFuel = NULL;  // data for ZeroFuel
                public $takeOff = NULL;  // data for Take-off

                /**
                 * Constructor: initialize arrays
                 *
                 * @SuppressWarnings(PHPMD.MissingImport)
                 */
                public function __construct() {
                    $this->gcBoundaries = new stdClass();
                    $this->gcBoundaries->min = 0;
                    $this->gcBoundaries->max = 0;

                    $this->dryEmpty = new MassMomentObject();

                    $this->front = new MassMomentObject();
                    $this->front->isArmlessAllowed = true;

                    $this->rears = array();
                    global $kRearStationsNum;
                    for ($i = 0; $i < $kRearStationsNum; ++$i) {
                        $this->rears[] = new MassMomentObject();
                    }

                    $this->luggages = array();
                    global $kLuggageStationsNum;
                    for ($i = 0; $i < $kLuggageStationsNum; ++$i) {
                        $this->luggages[] = new MassMomentObject();
                    }

                    $this->luggageTotalMass = new MassMomentObject();

                    $this->fuelUnusables = array();
                    $this->fuelQuantities = array();
                    global $kFuelTanksNum;
                    for ($i = 0; $i < $kFuelTanksNum; ++$i) {
                        $this->fuelUnusables[] = new MassMomentObject();
                        $this->fuelQuantities[] = new MassMomentObject();
                    }

                    $this->zeroFuel = new MassMomentObject();
                    $this->zeroFuel->arm = NULL;
                    $this->takeOff = new MassMomentObject();
                    $this->takeOff->arm = NULL;
                }

                /**
                 * Set the mass unit to all objects, using the class mass unit.
                 */
                public function propagateMassUnit() {
                    // dryEmpty is already in the right unit

                    $this->front->massUnit = $this->massUnit;

                    $rearsCount = count($this->rears);
                    for ($i = 0; $i < $rearsCount; ++$i) {
                        $this->rears[$i]->massUnit = $this->massUnit;
                    }

                    $luggagesCount = count($this->luggages);
                    for ($i = 0; $i < $luggagesCount; ++$i) {
                        $this->luggages[$i]->massUnit = $this->massUnit;
                    }

                    $unusablesCount = count($this->fuelUnusables);
                    for ($i = 0; $i < $unusablesCount; ++$i) {
                        $this->fuelUnusables[$i]->massUnit = $this->massUnit;
                    }

                    $quantitiesCount = count($this->fuelQuantities);
                    for ($i = 0; $i < $quantitiesCount; ++$i) {
                        $this->fuelQuantities[$i]->massUnit = $this->massUnit;
                    }

                    // zeroFuel is already in the right unit
                    // takeOff is already in the right unit
                }

                /**
                 * Copy the required fuel data from fuel unusables to quantities.
                 */
                public function propagateFuelData() {
                    $unusablesCount =count($this->fuelUnusables); 
                    for ($i = 0; $i < $unusablesCount; ++$i) {
                        $this->fuelQuantities[$i]->arm = $this->fuelUnusables[$i]->arm;
                    }
                }

                /**
                 * Store the final fuel requirements to compute GC data out of it.
                 *
                 * Args:
                 *     finalFuel (FuelRequirements): object holding all data concerning fuel requirements
                 */
                public function storeFuelMass($finalFuel) {
                    $tanksCount = count($finalFuel->tanks);
                    for ($i = 0; $i < $tanksCount; ++$i) {
                        $this->fuelUnusables[$i]->mass = $finalFuel->tanks[$i]->getFuelUnusableMass();
                        $this->fuelQuantities[$i]->mass = $finalFuel->tanks[$i]->getFuelQuantityMass();
                    }
                }

                /**
                 * Compute zeroFuel data: add everything except fuel BUT including unusable fuel
                 *
                 * @SuppressWarnings(PHPMD.MissingImport)
                 */
                public function computeZeroFuelData() {
                    $this->zeroFuel = new MassMomentObject();
                    $this->zeroFuel->arm = NULL;

                    // dryEmpty
                    // Do not convert mass, it is the reference in the right unit already
                    $this->zeroFuel->add($this->dryEmpty);

                    // front
                    $this->front->convertMass();
                    $this->zeroFuel->add($this->front);

                    global $kArmless;

                    $rearsCount = count($this->rears);
                    for ($i = 0; $i < $rearsCount; ++$i) {
                        if($this->rears[$i]->arm == $kArmless) {
                            continue;
                        }

                        $this->rears[$i]->convertMass();
                        $this->zeroFuel->add($this->rears[$i]);
                    }

                    $this->luggageTotalMass->mass = 0;
                    $luggagesCount = count($this->luggages);
                    for ($i = 0; $i < $luggagesCount; ++$i) {
                        if($this->luggages[$i]->arm == $kArmless) {
                            continue;
                        }

                        $this->luggages[$i]->convertMass();
                        $this->zeroFuel->add($this->luggages[$i]);
                        $this->luggageTotalMass->mass += $this->luggages[$i]->mass;
                    }

                    $unusablesCount = count($this->fuelUnusables);
                    for ($i = 0; $i < $unusablesCount; ++$i) {
                        if($this->fuelUnusables[$i]->arm == $kArmless) {
                            continue;
                        }

                        $this->fuelUnusables[$i]->convertMass();
                        $this->zeroFuel->add($this->fuelUnusables[$i]);
                    }
                }

                /**
                 * Compute Take-off data: add fuel quantities to the zeroFuel
                 *
                 * @SuppressWarnings(PHPMD.MissingImport)
                 */
                public function computeTakeOffData() {
                    // Copy zeroFuel
                    $this->takeOff = new MassMomentObject();
                    $this->takeOff->arm = NULL;
                    $this->takeOff->add($this->zeroFuel);

                    $quantitiesCount = count($this->fuelQuantities);
                    for ($i = 0; $i < $quantitiesCount; ++$i) {
                        $this->fuelQuantities[$i]->convertMass();
                        $this->takeOff->add($this->fuelQuantities[$i]);
                    }
                }
            }

            $gcData = new GcData();
//
    // GC table entries
    class GcTableCell {
        public $value;
        public $color;
        public $rowspan;
        public $tooHeavy = false;
        public $small = false;

        public function __construct($value=NULL, $color=NULL, $rowspan=NULL) {
            $this->value = $value;
            $this->color = $color;
            $this->rowspan = $rowspan;
        }
    }
//
    // Fuel requirements
    // Store
    // * tanks (total capacity, unusable, allOrNothing, quantity)
    // * trip minutes
    // * alternate minutes
    // * reserve minutes
    // * extra
    // * overflow
    // Compute
    // * mass
    // * time to quantity
    // * tank filling
    // * total TotalCapacity
    // * total unusable
    // * display html+LaTeX

        // Enum to know about which part of the fuel computation it is
        enum FuelEntry {
            case Trip;
            case Alternate;
            case Reserve;
            case Unusable;
            case Minimum;
            case Extra;
            case Ramp;
        }
    //
        // Custom class for fuel tank as we also want to know total capacity and unusable.
        // Also used to compute fuel requirements.
        class FuelTankObject {
            // Coming from FuelRequirement
            private $precision = NULL;  // Rounding precision
            public $fuelUnit = "?";  // fuel unit
            public $fuelType = "?";  // fuel type

            // Coming from Plane
            public $totalCapacity = 0;  // total capacity of the fuel tank
            public $unusable = 0;  // unusable fuel in the tank
            public $allOrNothing = False;  // if True, tank is filled full or not

            public $quantity = 0;  // fuel quantity computed in FuelRequirements->fillSingleTank

            public function __construct() {
                global $kDefaultPrecision;
                $this->precision = $kDefaultPrecision;
            }

            /**
             * Deep copy of an object to duplicate the required fields (but not quantity).
             *
             * Returns:
             *     new object with data from this
             *
             * @SuppressWarnings(PHPMD.MissingImport)
             */
            public function deepcopy() {
                $object = new FuelTankObject();
                $object->totalCapacity = $this->totalCapacity;
                $object->unusable = $this->unusable;
                $object->fuelUnit = $this->fuelUnit;
                $object->fuelType = $this->fuelType;
                $object->allOrNothing = $this->allOrNothing;
                // quantity not duplicated since we want to duplicate from TheoricTrip to RealTrip
                return $object;
            }

            /**
             * Get the mass of fuel depending on the type and the unit in use, and rounded to the precision.
             *
             * Args:
             *     quantity (float): fuel quantity
             *
             * Returns:
             *     (float) fuel mass
             */
            public function getFuelMass($quantity) {
                global $kFuelTypes;
                global $kFuelUnits;

                if($this->fuelUnit == "?" || $this->fuelType == "?") {
                    // plane not defined
                    return 0;
                }

                return round($quantity * $kFuelUnits[$this->fuelUnit] * $kFuelTypes[$this->fuelType], $this->precision);  // [kg]
            }

            /**
             * Get the unusable fuel mass
             *
             * Returns:
             *     (float) unusable fuel mass
             */
            public function getFuelUnusableMass() {
                return $this->getFuelMass($this->unusable);
            }

            /**
             * Get the mass of the fuel quantity
             *
             * Returns:
             *     (float) fuel quantity mass
             */
            public function getFuelQuantityMass() {
                return $this->getFuelMass($this->quantity);
            }
        }
    //
        /**
         * Fuel requirements holding all information required to compute how much fuel we take on board.
         *
         * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
         */
        class FuelRequirements {
            public $consumption = 0;         // fuel consumption [unit/h]
            public $unit = "";               // unit of fuel consumption
            public $type = "";               // type of fuel

            public $tanks = NULL;            // Fuel tanks objects

            public $tripMinutes = 0;         // minutes for the trip [min]
            public $alternateMinutes = 0;    // minutes to join alternate [min]
            public $reserveMinutes = 45;     // reserve time [min]

            public $extraPercent = 0.05;     // extra fuel [%]

            public $overflow = 0;            // Fuel overflow to know if we can do it or not

            /**
             * Constructor: initialize arrays
             *
             * @SuppressWarnings(PHPMD.MissingImport)
             */
            public function __construct() {
                global $kFuelTanksNum;

                $this->tanks = array();
                for ($i = 0; $i < $kFuelTanksNum; ++$i) {
                    $this->tanks[$i] = new FuelTankObject();
                }
            }

            /**
             * Deep copy of an object without copying the fuel quantity
             *
             * Returns:
             *     new object with data duplicated
             *
             * @SuppressWarnings(PHPMD.MissingImport)
             */
            public function deepcopy() {
                $object = new FuelRequirements();

                $object->consumption = $this->consumption;
                $object->unit = $this->unit;
                $object->type = $this->type;

                $object->tanks = array();
                $tanksCount = count($this->tanks);
                for ($i = 0; $i < $tanksCount; ++$i) {
                    $object->tanks[$i] = $this->tanks[$i]->deepcopy();
                }

                $object->reserveMinutes = $this->reserveMinutes;
                $object->extraPercent = $this->extraPercent;

                return $object;
            }

            /**
             * Set the trip and alternate times.
             *
             * Args:
             *     trip (int): minutes for trip
             *     alternate (int): minutes for alternate
             */
            public function setTripAndAlternateTimes($trip, $alternate) {
                $this->tripMinutes = $trip;
                $this->alternateMinutes = $alternate;
            }

            // Propagate fuel data (unit+type) to the tanks
            public function propagateFuelData() {
                $tanksCount = count($this->tanks);
                for ($i = 0; $i < $tanksCount; ++$i) {
                    $this->tanks[$i]->fuelUnit = $this->unit;
                    $this->tanks[$i]->fuelType = $this->type;
                }
            }

            /**
             * Check if fuel requirement is valid.
             *
             * This is done by checking all the mandatory inputs are filled.
             *
             * Returns:
             *     (bool) True if requirement is valid
             */
            public function isValid() {
                return (
                    $this->consumption > 0
                    && $this->unit != ""
                    && $this->type != ""
                    && $this->tripMinutes > 0
                    //&& $this->alternateMinutes > 0
                    && $this->reserveMinutes > 0
                    && $this->extraPercent > 0
                );
            }

            /**
             * Get total unusable fuel.
             *
             * Returns:
             *     (float) total unusable fuel
             */
            public function getUnusable() {
                $unusable = 0;
                $tanksCount = count($this->tanks);
                for($i = 0; $i < $tanksCount; ++$i) {
                    $unusable += $this->tanks[$i]->unusable;
                }
                return $unusable;
            }

            /**
             * Get the total fuel capacity.
             *
             * Returns:
             *     (float) total fuel capacity
             */
            public function getTotalCapacity() {
                $totalCapacity = 0;
                $tanksCount = count($this->tanks);
                for($i = 0; $i < $tanksCount; ++$i) {
                    $totalCapacity += $this->tanks[$i]->totalCapacity;
                }
                return $totalCapacity;
            }

            /**
             * Convert time to fuel quantity.
             *
             * Args:
             *     time (float): time of flight [min]
             *
             * Returns:
             *     (float) fuel quantity [unit]
             */
            public function time2quantity($time) {
                return ceil($time * $this->consumption / 60.0);
            }

            /**
             * Get the fuel quantity for the trip.
             *
             * Returns:
             *     (float) fuel quantity for the trip [unit]
             */
            public function getTrip() {
                return $this->time2quantity($this->tripMinutes);
            }

            /**
             * Get the fuel quantity for the alternate.
             *
             * Returns:
             *     (float) fuel quantity for the alternate [unit]
             */
            public function getAlternate() {
                return $this->time2quantity($this->alternateMinutes);
            }

            /**
             * Get the fuel quantity for the reserve.
             *
             * Returns:
             *     (float) fuel quantity for the reserve [unit]
             */
            public function getReserve() {
                return $this->time2quantity($this->reserveMinutes);
            }

            /**
             * Get the minimum fuel: sum of unusable, trip, alternate, reserve.
             *
             * Returns:
             *     (float) minimum fuel quantity [unit]
             */
            public function getMinimumFuel() {
                if (!$this->isValid()) {
                    return -1;
                }

                return ceil(
                    $this->getUnusable()
                    + $this->getTrip()
                    + $this->getAlternate()
                    + $this->getReserve()
                );
            }

            /**
             * Compute and get the extra fuel
             *
             * Returns:
             *     (float) extra fuel
             */
            public function getExtraFuel() {
                if (!$this->isValid()) {
                    return -1;
                }

                return ceil($this->getMinimumFuel() * $this->extraPercent);
            }

            /**
             * Compute and get the ramp fuel
             *
             * Returns:
             *     (float) ramp fuel [unit]
             */
            public function getRampFuel() {
                if (!$this->isValid()) {
                    return -1;
                }

                return $this->getMinimumFuel() + $this->getExtraFuel();
            }

            /**
             * Fill a single tank with the required quantity.
             *
             * Args:
             *     quantity (float): fuel quantity to fill in the tank [unit]
             *     tank (object): tank object to fill
             *
             * Returns:
             *     (float) remaining quantity to fill in the next tanks
             */
            public function fillSingleTank($quantity, $tank) {
                if($quantity <= 0) {
                    return 0;
                }

                $usable = $tank->totalCapacity - $tank->unusable;

                if($tank->allOrNothing) {
                    // Fill tank to max even if we need less
                    $tank->quantity = $usable;
                    $quantity -= $usable;

                    // Return the leftover
                    if($quantity < 0) {
                        return 0;
                    }

                    return $quantity;
                }

                if($quantity <= $usable) {
                    // Fill only what we need
                    $tank->quantity = $quantity;
                    return 0;
                }

                // Fill tank to max
                $tank->quantity = $usable;
                $quantity -= $usable;

                // Return the leftover
                return $quantity;
            }

            /**
             * Fill all the tanks with the required fuel quantity.
             *
             * Returns:
             *     (bool) True if tanks have been filled
             */
            public function fillTanks() {
                if(!$this->isValid()) {
                    return False;
                }

                $quantity = $this->getRampFuel();
                $tanksCount = count($this->tanks);
                for($i = 0; $i < $tanksCount; ++$i) {
                    $quantity = $this->fillSingleTank($quantity, $this->tanks[$i]);

                    if($quantity <= 0) {
                        break;
                    }
                }
                $this->overflow = $quantity;

                return True;
            }

            /**
             * Get the time of the desired entry
             *
             * Args:
             *     fuelEntry (enum): the desired entry
             *
             * Returns:
             *     (int) flight time [min]
             */
            public function getEntryMinutes($fuelEntry) {
                if($fuelEntry == FuelEntry::Trip) {
                    return $this->tripMinutes;
                }

                if($fuelEntry == FuelEntry::Alternate) {
                    return $this->alternateMinutes;
                }

                if($fuelEntry == FuelEntry::Reserve) {
                    return $this->reserveMinutes;
                }

                return -1;  // undefined fuel entry
            }

            /**
             * Get the fuel quantity of the desired entry
             *
             * Args:
             *     fuelEntry (enum): the desired entry
             *
             * Returns:
             *     (float) fuel quantity [unit]
             */
            public function getEntryQuantity($fuelEntry) {
                if($fuelEntry == FuelEntry::Trip) {
                    return $this->getTrip();
                }
                
                if($fuelEntry == FuelEntry::Alternate) {
                    return $this->getAlternate();
                }
                
                if($fuelEntry == FuelEntry::Reserve) {
                    return $this->getReserve();
                }
                
                if($fuelEntry == FuelEntry::Unusable) {
                    return $this->getUnusable();
                }
                
                // Next ones must have valid data
                if (!$this->isValid()) {
                    return -1;
                }
                // from this point on, it is valid

                if($fuelEntry == FuelEntry::Minimum) {
                    return $this->getMinimumFuel();
                }
                
                if($fuelEntry == FuelEntry::Extra) {
                    return $this->getExtraFuel();
                }
                
                return -1;  // Ramp fuel cannot be retreived with this method; others are undefined
            }

            /**
             * Get the HTML cells to make a partial row for the desired fuel entry
             *
             * Args:
             *     page (object): PhPage
             *     fuelEntry (enum): the desired fuel entry
             *
             * Returns:
             *     (string) HTML cells
             */
            public function htmlRow($fuelEntry) {
                global $page;

                if(!$this->isValid()) {
                    return $page->butler->cell() . $page->butler->cell();
                }

                $minutes = $this->getEntryMinutes($fuelEntry);

                $htmlRow = $page->butler->cell("", array("class" => "unavailable"));  // default, if minutes==0
                if($minutes > 0) {
                    $htmlRow = $page->butler->cell($page->timeHelper->minutesDisplay($minutes), array("class" => "num"));
                }

                $htmlRow .= $page->butler->cell($this->getEntryQuantity($fuelEntry), array("class" => "num"));
                return $htmlRow;
            }

            /**
             * Get the HTML cells to make a partial row for the ramp fuel entry
             *
             * Returns:
             *     (string) HTML cells
             */
            public function htmlRamp() {
                global $page;

                if(!$this->isValid()) {
                    return $page->butler->cell() . $page->butler->cell();
                }

                $htmlRow = $page->butler->cell("", array("class" => "unavailable"));
                $attrQ = array("class" => "num");

                if($this->overflow > 0) {
                    global $kStrings;
                    $attrQ["style"] = "background-color: red;";
                    $htmlRow = $page->butler->cell("{$kStrings['overflow']}: {$this->overflow}", array("style" => "background-color: red;"));
                }

                $htmlRow .= $page->butler->cell($this->getRampFuel(), $attrQ);
                return $htmlRow;
            }

            /**
             * Get the LaTeX cells to make a partial row for the desired fuel entry
             *
             * Args:
             *     page (object): PhPage
             *     fuelEntry (enum): the desired fuel entry
             *
             * Returns:
             *     (string) LaTeX cells
             */
            public function latexRow($fuelEntry) {
                if(!$this->isValid()) {
                    return "&&";
                }

                $minutes = $this->getEntryMinutes($fuelEntry);

                $latexRow = "\\multicolumn{2}{c}{\\DarkGrayCell}\n";

                global $page;

                if($minutes > 0) {
                    $latexRow = " {$page->timeHelper->minutes2HoursInt($minutes)}&";
                    $latexRow .= sprintf("%02d", $page->timeHelper->minutes2MinutesRest($minutes)) . " ";
                }

                $latexRow .= "& {$this->getEntryQuantity($fuelEntry)}\n";
                return $latexRow;
            }

            /**
             * Get the LaTeX cells to make a partial row for the ramp fuel entry
             *
             * Returns:
             *     (string) LaTeX cells
             */
            public function latexRamp() {
                if(!$this->isValid()) {
                    return "&&";
                }

                $latexRow = "\\multicolumn{2}{c}{\\DarkGrayCell}\n";

                if($this->overflow > 0) {
                    global $kStrings;
                    $latexRow = "\\multicolumn{2}{c}{\\RedCell {$kStrings['overflow']}: {$this->overflow}}\n";
                }

                $latexRow .= "& {$this->getRampFuel()}\n";

                return $latexRow;
            }
        }
//
    // Init vars
    $maxRow = 17;
    $kTable = "NavList";

    $plane = new Aircraft();

    $usgAvgas2lbs = 6;  // [lbs/USG]
    //
        // names
        $kStrings = array();

        $kStrings["Waypoint"] = "Waypoint";
        $kStrings["TrueCourse"] = "TC";
        $kStrings["MagneticCourse"] = "MC";
        $kStrings["Dist"] = "Dist.";
        $kStrings["Altitude"] = "Altitude";
        $kStrings["EstimatedElapsedTime"] = "EET";
        $kStrings["Wind"] = "Wind";
        $kStrings["MagneticHeading"] = "MH";
        $kStrings["GroundSpeed"] = "GS";
        $kStrings["EstimatedTimeOver"] = "ETO";
        $kStrings["ActualTimeOver"] = "ATO";
        $kStrings["Notes"] = "Notes";
        $kStrings["unitFt"] = "[ft]";
        $kStrings["unitNM"] = "[NM]";
        $kStrings["unitMin"] = "[min]";
        $kStrings["unitKts"] = "[kts]";
        $kStrings["latexUnitDeg"] = "$[^{\\textrm{o}}]$";
        $kStrings["htmlUnitDeg"] = "[&deg;]";
        $kStrings["Plus5"] = "+5";
        $kStrings["Plus10"] = "+10";
        $kStrings["latexCopyright"] = "{\\footnotesize\\textcopyright}";
        $kStrings["htmlCopyright"] = "&copy;";

        // TOD
        $kStrings["TopOfDescent"] = "TOD";
        $kStrings["TOD1"] = "120kts (2NM/min) 500 ft/min (children -12: 300)<br>3deg makes descent rate = GS x5";
        $kStrings["TOD2"] = "1NM attitude change";
        $kStrings["TOD3"] = "2NM speed decrease";
        $kStrings["TOD4"] = "2NM approach check (if needed)";

        // Fuel
        $kStrings["Fuel"] = "Fuel";
        $kStrings["fuel"] = "fuel";
        $kStrings["time"] = "time";
        $kStrings["FuelConsumption"] = "Fuel consumption:";
        $kStrings["NoWind"] = "No wind";
        $kStrings["Trip"] = "Trip";
        $kStrings["Alternate"] = "Alternate";
        $kStrings["Reserve"] = "Reserve";
        $kStrings["Unusable"] = "Unusable";
        $kStrings["MinFuel"] = "Minimum fuel";
        $kStrings["ExtraPlus"] = "Extra +";
        $kStrings["RampFuel"] = "Ramp fuel";
        $kStrings["USG"] = "USG";
        $kStrings["ImpG"] = "Imp.G";
        $kStrings["Avgas"] = "Avgas";
        $kStrings["overflow"] = "OVERFLOW";

        // TH with wind
        $kStrings["THwind"] = "TH with wind";

        // Weight and balance
        $kStrings["MassAndBalance"] = "Mass and balance";
        $kStrings["Mass"] = "Mass";
        $kStrings["Arm"] = "Arm";
        $kStrings["Moment"] = "Moment";
        $kStrings["DryEmpty"] = "Dry+Empty";
        $kStrings["Front"] = "Front";
        $kStrings["Rear"] = "Rear";
        $kStrings["Luggage"] = "Luggage";
        //$kStrings["FuelTotalCapacity"] = "Fuel total capacity";
        $kStrings["UnusableFuel"] = "Unusable fuel";
        $kStrings["ZeroFuel"] = "0-fuel";
        $kStrings["TakeOff"] = "T-off";
        $kStrings["TooHeavy"] = "TOO HEAVY";
        $kStrings["htmlTooHeavy"] = "<b style=\"background-color: red;\">{$kStrings['TooHeavy']}!!</b>";
        $kStrings["latexTooHeavy"] = "\\textbf{{$kStrings['TooHeavy']}!!}";
//

    /**
     * Duplicate a nav and all its waypoints.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    function duplicateNav($isAdmin) {
        if(!isset($_GET["dup"]) || !$isAdmin) {
            return;
        }

        $dupID = $_GET["dup"];

        global $page;
        global $kTable;
        global $kDefaultLuggageMass;

        $navQuery = "INSERT INTO `{$page->bobbyTable->dbName}`.`$kTable` (";
        $navQuery .= "`name`, `plane`, `variation`,";
        $navQuery .= " `FrontMass`,";
        $navQuery .= " `Rear0Mass`, `Rear1Mass`";
        $navQuery .= ", `Luggage0Mass`, `Luggage1Mass`, `Luggage2Mass`, `Luggage3Mass`";
        $navQuery .= ", `comment`";
        $navQuery .= ") SELECT";
        $navQuery .= " concat(`name`, ' (COPY)'), `plane`, `variation`,";
        $navQuery .= " 0,";
        $navQuery .= " NULL, NULL";
        $navQuery .= ", $kDefaultLuggageMass, NULL, NULL, NULL";
        $navQuery .= ", `comment`";
        $navQuery .= " FROM `{$page->bobbyTable->dbName}`.`$kTable` WHERE `$kTable`.`id` = ?";

        $wpQuery  = "INSERT INTO `{$page->bobbyTable->dbName}`.`NavWaypoints` (";
        $wpQuery .= "`NavID`, `WPnum`, `waypoint`,";
        $wpQuery .= " `TC`, `distance`, `altitude`,";
        $wpQuery .= " `windTC`, `windSpeed`,";
        $wpQuery .= " `notes`, `climbing`";
        $wpQuery .= ") SELECT";
        $wpQuery .= " ?, `WPnum`, `waypoint`,";
        $wpQuery .= " `TC`, `distance`, `altitude`,";
        $wpQuery .= " `windTC`, `windSpeed`,";
        $wpQuery .= " `notes`, `climbing`";
        $wpQuery .= " FROM `{$page->bobbyTable->dbName}`.`NavWaypoints` WHERE `NavWaypoints`.`NavID` = ?";

        $qNav = $page->bobbyTable->queryPrepare($navQuery);
        $qNav->bind_param("i", $dupID);
        $page->bobbyTable->executeManage($qNav);
        $newID = $qNav->insert_id;

        $qWP  = $page->bobbyTable->queryPrepare($wpQuery);
        $qWP->bind_param("ii", $newID, $dupID);
        $page->bobbyTable->executeManage($qWP);

        // redirect to edit page so we can change title and nav infos
        $page->htmlHelper->headerLocation("insert.php?id=$newID");
    }

    duplicateNav($isAdmin);
//

    // some functions
        // compute
            // sin degrees
            function sind($alpha) {
                return sin($alpha * pi() / 180.0);
            }
        //
            // asin degrees
            function asind($val) {
                return 180.0 / pi() * asin($val);
            }
        //
            // Compute EET
            function ComputeEET($distance, $speed) {
                return round($distance * 60.0 / $speed);
            }
        //
            // heading visual
            function headingText($heading, $wpNum) {
                if($heading > 0) {
                    return sprintf("%03d", $heading);
                }

                global $kWaypoints;
                if($kWaypoints->isStartLast($wpNum) && $wpNum != $kWaypoints->alternate->start) {
                    return "VAC";
                }

                return "V";
            }
        //
            /**
             * Compute row of GC table.
             *
             * @SuppressWarnings(PHPMD.MissingImport)
             */
            function computeGcTableRow($gcDataField, $stringId, $extraString="") {
                global $kArmless;

                if($gcDataField->getArm() == $kArmless) {
                    return NULL;
                }

                global $kStrings;

                $label = $kStrings[$stringId];
                if($extraString != "") {
                    $label .= " $extraString";
                }

                global $kNoArm;
                if($gcDataField->getArm() == $kNoArm) {
                    return array(new GcTableCell($label), new GcTableCell(), new GcTableCell(), new GcTableCell());
                }

                $mass = new GcTableCell($gcDataField->mass);
                if($gcDataField->isMassTooMuch()) {
                    $mass->tooHeavy = true;
                }

                global $kDefaultPrecision;
                $arm = new GcTableCell($gcDataField->getArm());
                $moment = new GcTableCell(round($gcDataField->getMoment(), $kDefaultPrecision));

                return array(new GcTableCell($label), $mass, $arm, $moment);
            }
        //
            /**
             * Compute GC: takes gcData and outputs the data for the table.
             *
             * Returns:
             *     array: each item is array(label, mass, arm, moment)
             *     label can be a key in kStrings
             *
             * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
             * @SuppressWarnings(PHPMD.CyclomaticComplexity)
             * @SuppressWarnings(PHPMD.NPathComplexity)
             * @SuppressWarnings(PHPMD.MissingImport)
             */
            function computeGC($gcData, $fuelReq) {
                global $kStrings;

                $rows = array();

                    // empty
                    $dryEmpty = $kStrings["DryEmpty"];
                    if($gcData->dryEmptyTimestamp !== NULL && $gcData->dryEmptyTimestamp != "") {
                        $dryEmpty .= " ({$gcData->dryEmptyTimestamp})";
                    }

                    $rows[] = array(
                        new GcTableCell($dryEmpty),
                        new GcTableCell(($gcData->dryEmpty->mass > 0) ? $gcData->dryEmpty->mass : NULL),
                        new GcTableCell(),
                        new GcTableCell(($gcData->dryEmpty->getMoment() > 0) ? $gcData->dryEmpty->getMoment() : NULL)
                    );

                $rows[] = computeGcTableRow($gcData->front, "Front");

                // rears...
                $rearsCount = count($gcData->rears);
                for ($i = 0; $i < $rearsCount; ++$i) {
                    $row = computeGcTableRow($gcData->rears[$i], "Rear", "#" . ($i + 1));
                    if($row !== NULL) {
                        $rows[] = $row;
                    }
                }

                // luggages...
                $luggagesCount = count($gcData->luggages);
                for ($i = 0; $i < $luggagesCount; ++$i) {
                    $row = computeGcTableRow($gcData->luggages[$i], "Luggage", "#" . ($i + 1));
                    if($row !== NULL) {
                        $rows[] = $row;
                    }
                }
                // luggage total mass
                if($gcData->luggageTotalMass->maxMass > 0 && $gcData->luggageTotalMass->mass > $gcData->luggageTotalMass->maxMass) {
                    $row = array(
                        new GcTableCell("{$kStrings['Luggage']} total mass", "red"),
                        new GcTableCell(
                            "{$kStrings['TooHeavy']} {$gcData->luggageTotalMass->mass} > {$gcData->luggageTotalMass->maxMass}",
                            "red"
                        ),
                        new GcTableCell(),
                        new GcTableCell()
                    );
                    $rows[] = $row;
                }

                // unusable fuels...
                $unusablesCount = count($gcData->fuelUnusables);
                for ($i = 0; $i < $unusablesCount; ++$i) {
                    $tank = $fuelReq->tanks[$i];

                    $label = "#" . ($i + 1);
                    if($tank->unusable > 0) {
                        // If we do the template, we do not want to display this (empty) value
                        $label .= "={$tank->unusable}{$tank->fuelUnit}";
                    }

                    $row = computeGcTableRow($gcData->fuelUnusables[$i], "UnusableFuel", $label);
                    if($row !== NULL) {
                        $rows[] = $row;
                    }
                }
                    // set LaTeX hline
                    $lastRow = array_pop($rows);
                    if($lastRow !== NULL) {
                        $lastRow[] = "hhline{====}";
                    };
                    $rows[] = $lastRow;
                //
                $gcMin = $gcData->gcBoundaries->min == 0 ? "?" : $gcData->gcBoundaries->min;
                $gcMax = $gcData->gcBoundaries->max == 0 ? "?" : $gcData->gcBoundaries->max;

                    // 0-fuel 3 rows: GCmin, values, MLdgW+GCmax
                    $gcMinColor = ($gcData->gcBoundaries->min > 0 && $gcData->zeroFuel->getArm() < $gcData->gcBoundaries->min) ? "red" : "gray";
                    $gcMaxColor = ($gcData->gcBoundaries->max > 0 && $gcData->zeroFuel->getArm() > $gcData->gcBoundaries->max) ? "red" : "gray";
                    $gcColor = (
                        ($gcData->gcBoundaries->min > 0 && $gcData->zeroFuel->getArm() < $gcData->gcBoundaries->min)
                        || ($gcData->gcBoundaries->max > 0 && $gcData->zeroFuel->getArm() > $gcData->gcBoundaries->max)
                    ) ? "red" : "gray";

                    // color red 0-fuel mass if more than maxLdgW
                    $massPrefix = "";
                    $mldgwColor = "gray";
                    if ($gcData->maxLdgW > 0 && $gcData->zeroFuel->mass > $gcData->maxLdgW) {
                        $massPrefix = $kStrings["TooHeavy"] . " ";
                        $mldgwColor = "red";
                    }

                        // minimums
                        $gcMinCell = new GcTableCell("min=$gcMin", $gcMinColor);
                        $gcMinCell->small = true;

                        $rows[] = array(
                            new GcTableCell("ZeroFuel", "gray", 3),
                            new GcTableCell(NULL, $mldgwColor),
                            $gcMinCell,
                            new GcTableCell(
                                ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ?  $gcData->zeroFuel->getMoment() : NULL,
                                "gray",
                                3
                            ),
                            "hhline{~---}"
                        );
                    //
                        // values
                        $mass = "";
                        if($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) {
                            $mass = "{$massPrefix}{$gcData->zeroFuel->mass}";
                        }

                        $rows[] = array(
                            new GcTableCell(),
                            new GcTableCell($mass, $mldgwColor),
                            new GcTableCell(
                                ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ?  $gcData->zeroFuel->getArm() : NULL,
                                $gcColor
                            ),
                            new GcTableCell(),
                            "hhline{~---}"
                        );
                    //
                        // maximums
                        $mldgwCell = new GcTableCell();
                        if($gcData->maxLdgW > 0) {
                            // Special: color red 0-fuel mass if Take-off mass is more than maxLdgW
                            $mldgwPrefix = "";
                            $mldgwColor = "gray";
                            if ($gcData->maxLdgW > 0 && $gcData->takeOff->mass > $gcData->maxLdgW) {
                                $mldgwPrefix = $kStrings["TooHeavy"] . " ";
                                $mldgwColor = "red";
                            }
                            $mldgwCell = new GcTableCell("{$mldgwPrefix}MLdgW={$gcData->maxLdgW}", $mldgwColor);
                        }
                        $mldgwCell->small = true;
                        $gcMaxCell = new GcTableCell("max=$gcMax", $gcMaxColor);
                        $gcMaxCell->small = true;

                        $rows[] = array(
                            new GcTableCell(),
                            $mldgwCell,
                            $gcMaxCell,
                            new GcTableCell(),
                            "hhline{====}"
                        );
                //
                    // fuels...
                    $quantitiesCount = count($gcData->fuelQuantities);
                    for ($i = 0; $i < $quantitiesCount; ++$i) {
                        $tank = $fuelReq->tanks[$i];

                        $label = "#" . ($i + 1);
                        if($tank->quantity > 0) {
                            // If we do the template, we do not want to display this (empty) value
                            $label .= "={$tank->quantity}{$tank->fuelUnit}";
                        }

                        $row = computeGcTableRow($gcData->fuelQuantities[$i], "Fuel", $label);
                        if($row !== NULL) {
                            $rows[] = $row;
                        }
                    }

                    // set LaTeX hline
                    $lastRow = array_pop($rows);
                    if($lastRow !== NULL) {
                        $lastRow[] = "hhline{====}";
                    }
                    $rows[] = $lastRow;
                //
                    // T-off
                    $gcMinColor = ($gcData->gcBoundaries->min > 0 && $gcData->takeOff->getArm() < $gcData->gcBoundaries->min) ? "red" : "gray";
                    $gcMaxColor = ($gcData->gcBoundaries->max > 0 && $gcData->takeOff->getArm() > $gcData->gcBoundaries->max) ? "red" : "gray";
                    $gcColor = (
                        ($gcData->gcBoundaries->min > 0 && $gcData->takeOff->getArm() < $gcData->gcBoundaries->min)
                        || ($gcData->gcBoundaries->max > 0 && $gcData->takeOff->getArm() > $gcData->gcBoundaries->max)
                    ) ? "red" : "gray";

                    $massPrefix = "";
                    $mtowColor = "gray";
                    if ($gcData->maxTOW > 0 && $gcData->takeOff->mass > $gcData->maxTOW) {
                        $massPrefix = $kStrings["TooHeavy"] . " ";
                        $mtowColor = "red";
                    }

                        // minimums
                        $gcMinCell = new GcTableCell("min=$gcMin", $gcMinColor);
                        $gcMinCell->small = true;

                        $rows[] = array(
                            new GcTableCell("TakeOff", "gray", 3),
                            new GcTableCell(NULL, $mtowColor),
                            $gcMinCell,
                            new GcTableCell(
                                ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ?  $gcData->takeOff->getMoment() : NULL,
                                "gray",
                                3
                            ),
                            "hhline{~---}"
                        );
                    //
                        // values
                        $mass = "";
                        if($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) {
                            $mass = "{$massPrefix}{$gcData->takeOff->mass}";
                        }

                        $rows[] = array(
                            new GcTableCell(),
                            new GcTableCell($mass, $mtowColor),
                            new GcTableCell(
                                ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ?  $gcData->takeOff->getArm() : NULL,
                                $gcColor
                            ),
                            new GcTableCell(),
                            "hhline{~---}"
                        );
                    //
                        // maximums
                        $mtowCell = new GcTableCell("MTOW=" . (($gcData->maxTOW > 0) ? $gcData->maxTOW : "?"), $mtowColor);
                        $mtowCell->small = true;
                        $gcMaxCell = new GcTableCell("max=$gcMax", $gcMaxColor);
                        $gcMaxCell->small = true;

                        $rows[] = array(
                            new GcTableCell(),
                            $mtowCell,
                            $gcMaxCell,
                            new GcTableCell()
                        );

                return $rows;
            }
        //
            function decrementRowspan($rowspan) {
                if($rowspan === NULL) {
                    return NULL;
                }

                if($rowspan == 1) {
                    return NULL;
                }

                if($rowspan < 0) {
                    $rowspan = -$rowspan;
                }

                return $rowspan - 1;
            }
        //
            /**
             * Compute fuel tanks
             *
             * Returns:
             *     table rows
             *
             *     | label | unusable | quantity | total |
             */
            function computeFuelTanks($gcData, $fuelReq) {
                global $kArmless;

                $rows = array();

                $quantitiesCount = count($gcData->fuelQuantities);
                for ($i = 0; $i < $quantitiesCount; ++$i) {
                    if($gcData->fuelQuantities[$i]->getArm() == $kArmless) {
                        continue;
                    }
                    $tank = $fuelReq->tanks[$i];

                    $unusable = "";
                    $quantity = "";
                    $total = "";
                    // If we do the template, we do not want to display empty values
                    if($tank->unusable > 0 || $tank->quantity > 0) {
                        $unusable = "{$tank->unusable} {$tank->fuelUnit}";
                        $quantity = "{$tank->quantity} {$tank->fuelUnit}";

                        $totalTank = $tank->unusable + $tank->quantity;
                        $total = "$totalTank {$tank->fuelUnit}";

                        if($tank->allOrNothing) {
                            $total .= " ALL";
                        }
                    }

                    $rows[] = array("Tank #" . ($i + 1), $unusable, $quantity, $total);
                }

                if($fuelReq->overflow > 0) {
                    global $kStrings;
                    $rows[] = array($kStrings["overflow"], "{$fuelReq->overflow} {$fuelReq->tanks[0]->fuelUnit}", "", "");
                }

                return $rows;
            }
    //
    //
        // HTML
            /**
             * HTML header: first table
             *
             * @SuppressWarnings(PHPMD.CyclomaticComplexity)
             * @SuppressWarnings(PHPMD.NPathComplexity)
             */
            function htmlIntroTable($name, $plane, $variation, $gcData) {
                $htmlHead = "";

                $htmlHead .= "<div class=\"NavIntro\">\n";
                $htmlHead .= "<div><b>Navigation:</b> $name</div>\n";

                $planeID = "not chosen yet";
                if($plane->sqlID > 0) {
                    $planeID = "{$plane->identification} ({$plane->type})";
                }

                $htmlHead .= "<div><b>Airplane:</b> $planeID</div>\n";

                if($plane->sqlID > 0) {
                    $htmlHead .= "<div><b>Planning speed:</b> {$plane->speedPlanning}kts (climb: {$plane->speedClimb}kts)</div>\n";
                }

                $htmlHead .= "<div><b>Variation:</b> $variation&deg;E</div>\n";

                // mass
                global $kArmless;
                $htmlHead .= "<div class=\"Masses\">\n";
                $htmlHead .= "<b>Mass [kg]:</b>\n";
                $htmlHead .= "<ul>\n";

                global $kStrings;

                    // Front
                    $htmlHead .= "<li>{$kStrings['Front']}: {$gcData->front->mass}";

                    if($gcData->front->isMassTooMuch()) {
                        $htmlHead .= " {$kStrings['htmlTooHeavy']}";
                    }

                    $htmlHead .= "</li>\n";
                //
                    // Rears
                    $htmlHead .= "<li>{$kStrings['Rear']}s: ";

                    $rearsCount = count($gcData->rears);
                    for ($i = 0; $i < $rearsCount; ++$i) {
                        if($gcData->rears[$i]->arm == $kArmless) {
                            continue;
                        }

                        $htmlHead .= "#" . ($i + 1) . "={$gcData->rears[$i]->mass}";
                        if($gcData->rears[$i]->isMassTooMuch()) {
                            $htmlHead .= " {$kStrings['htmlTooHeavy']}";
                        }
                        $htmlHead .= " - ";
                    }
                    $htmlHead = substr($htmlHead, 0, -3);  // remove trailing separator
                    $htmlHead .= "</li>\n";
                //
                    // Luggages
                    $htmlHead .= "<li>{$kStrings['Luggage']}s: ";

                    $luggagesCount = count($gcData->luggages);
                    for ($i = 0; $i < $luggagesCount; ++$i) {
                        if($gcData->luggages[$i]->arm == $kArmless) {
                            continue;
                        }

                        $htmlHead .= "#" . ($i + 1) . "={$gcData->luggages[$i]->mass} kg";
                        if($gcData->luggages[$i]->isMassTooMuch()) {
                            $htmlHead .= " {$kStrings['htmlTooHeavy']}";
                        }
                        $htmlHead .= " - ";

                    }
                    $htmlHead = substr($htmlHead, 0, -3);  // remove trailing separator
                    if($gcData->luggageTotalMass->maxMass > 0 && $gcData->luggageTotalMass->mass > $gcData->luggageTotalMass->maxMass) {
                        $htmlHead .= " <span style=\"background-color: red;\">Luggages mass {$gcData->luggageTotalMass->mass} {$kStrings['TooHeavy']}, exceeds {$gcData->luggageTotalMass->maxMass}</span>";
                    }
                    $htmlHead .= "</li>\n";

                $htmlHead .= "</ul>\n";
                $htmlHead .= "</div><!-- Masses -->\n";

                $htmlHead .= "</div><!-- NavIntro -->\n";

                return $htmlHead;
            }
        //
            // HTML header: header of 2nd table
            function htmlNavPlanTableHead($isAdmin) {
                global $page;

                $htmlHead = "";
                $htmlHead .= "<div class=\"NavPlan\">\n";
                $htmlHead .= $page->butler->tableOpen();

                global $kStrings;

                    // HTML table header
                    $htmlHead .= $page->butler->rowOpen();
                    if($isAdmin) {
                        $htmlHead .= $page->butler->headerCell("", array("rowspan" => 2));
                    }
                    $htmlHead .= $page->butler->headerCell($kStrings["Waypoint"], array("rowspan" => 2));
                    $htmlHead .= $page->butler->headerCell($kStrings["TrueCourse"], array("class" => "TC"));
                    $htmlHead .= $page->butler->headerCell($kStrings["MagneticCourse"]);
                    $htmlHead .= $page->butler->headerCell($kStrings["Dist"]);
                    $htmlHead .= $page->butler->headerCell($kStrings["Altitude"]);
                    $htmlHead .= $page->butler->headerCell($kStrings["EstimatedElapsedTime"]);
                    $htmlHead .= $page->butler->headerCell($kStrings["Wind"], array("class" => "wind", "colspan" => 2));
                    $htmlHead .= $page->butler->headerCell($kStrings["MagneticHeading"]);
                    $htmlHead .= $page->butler->headerCell($kStrings["GroundSpeed"]);
                    $htmlHead .= $page->butler->headerCell($kStrings["EstimatedElapsedTime"]);
                    $htmlHead .= $page->butler->headerCell($kStrings["EstimatedTimeOver"], array("rowspan" => 2));
                    $htmlHead .= $page->butler->headerCell($kStrings["ActualTimeOver"], array("rowspan" => 2));
                    $htmlHead .= $page->butler->headerCell($kStrings["Notes"], array("rowspan" => 2));
                    $htmlHead .= $page->butler->rowClose();
                    $htmlHead .= $page->butler->rowOpen();
                    $htmlHead .= $page->butler->headerCell($kStrings["htmlUnitDeg"], array("class" => "TC"));
                    $htmlHead .= $page->butler->headerCell($kStrings["htmlUnitDeg"]);
                    $htmlHead .= $page->butler->headerCell($kStrings["unitNM"]);
                    $htmlHead .= $page->butler->headerCell($kStrings["unitFt"]);
                    $htmlHead .= $page->butler->headerCell($kStrings["unitMin"]);
                    $htmlHead .= $page->butler->headerCell($kStrings["htmlUnitDeg"], array("class" => "wind"));
                    $htmlHead .= $page->butler->headerCell($kStrings["unitKts"], array("class" => "wind"));
                    $htmlHead .= $page->butler->headerCell($kStrings["htmlUnitDeg"]);
                    $htmlHead .= $page->butler->headerCell($kStrings["unitKts"]);
                    $htmlHead .= $page->butler->headerCell($kStrings["unitMin"]);
                    $htmlHead .= $page->butler->rowClose();

                return $htmlHead;
            }
        //
            function htmlNavPlanTableFoot($navid, $warning, $isAdmin) {
                global $page;

                $foot = "";

                if($isAdmin) {
                    // option to insert new WP
                    $foot .= $page->butler->rowOpen();
                    $foot .= $page->butler->cell($page->bodyBuilder->anchor("waypoint.php?nav=$navid", "new waypoint"), array("colspan" => 15, "class" => "newWP"));
                    $foot .= $page->butler->rowClose();
                }
                $foot .= $page->butler->tableClose();
                $foot .= $warning;
                $foot .= "</div><!-- NavPlan -->\n";

                return $foot;
            }
        //
            // HTML 1st row
            function htmlFirstRow($rowArgs) {
                global $page;
                global $kWaypoints;

                $htmlRow = $page->butler->rowOpen(array("class" => "WP{$rowArgs->wpNum}", "id" => "WP{$rowArgs->wpNum}"));

                if($rowArgs->isAdmin) {
                    $htmlRow .= $page->butler->cellOpen(array("class" => "edit"));

                    if($rowArgs->wpNum == $kWaypoints->wayOut->base) {
                        $htmlRow .= $page->bodyBuilder->anchor("waypoint.php?id={$rowArgs->id}", "edit", "edit {$rowArgs->waypoint} ({$rowArgs->wpNum})");
                    }

                    $htmlRow .= $page->butler->cellClose();
                }

                $destination = $rowArgs->destination;
                if($rowArgs->wpNum == $kWaypoints->wayOut->base) {
                    $destination = $rowArgs->waypoint;
                }

                $htmlRow .= $page->butler->cell($destination, array("class" => "waypoint"));

                $htmlRow .= $page->butler->cell("", array("colspan" => 11, "class" => "unavailable"));
                $htmlRow .= $page->butler->cell();  // ATO

                $htmlRow .= $page->butler->cellOpen(array("class" => "notes"));
                if($rowArgs->wpNum == $kWaypoints->wayOut->base) {
                    $htmlRow .= $rowArgs->notes;
                }
                $htmlRow .= $page->butler->cellClose();

                $htmlRow .= $page->butler->rowClose();
                return $htmlRow;
            }
        //
            // HTML row alternate banner
            function htmlRowAlternateBanner($wpNum, $isAdmin) {
                $colspan = $isAdmin ? 15 : 14;

                global $page;
                global $kStrings;

                $htmlRow = $page->butler->rowOpen(array("class" => "WP{$wpNum}"));
                $htmlRow .= $page->butler->cell($kStrings["Alternate"], array("class" => "nav-alternate-title", "colspan" => $colspan));
                $htmlRow .= $page->butler->rowClose();
                return $htmlRow;
            }
        //
            /**
             * HTML row
             *
             * @SuppressWarnings(PHPMD.ElseExpression)
             */
            function htmlRow($rowArgs) {
                // Set data
                global $kStrings;

                $climbing = $rowArgs->climbing ? $kStrings["htmlCopyright"] : "";

                // Prepare string

                global $page;

                $htmlRow = $page->butler->rowOpen(array("class" => "WP{$rowArgs->wpNum}", "id" => "WP{$rowArgs->wpNum}"));

                if($rowArgs->isAdmin) {
                    $htmlRow .= $page->butler->cell(
                        $page->bodyBuilder->anchor("waypoint.php?id={$rowArgs->id}", "edit", "edit {$rowArgs->waypoint} ({$rowArgs->wpNum})"),
                        array("class" => "edit")
                    );
                }

                // WP
                $htmlRow .= $page->butler->cell($rowArgs->waypoint, array("class" => "waypoint"));

                // TC
                $htmlRow .=$page->butler->cell($rowArgs->trueCourse, array("class" => "TC heading num"));

                // MC
                $htmlRow .= $page->butler->cell($rowArgs->magneticCourse, array("class" => "heading num"));

                // distance
                $htmlRow .= $page->butler->cell($rowArgs->distance, array("class" => "distance num"));

                // altitude
                $htmlRow .= $page->butler->cellOpen(array("class" => "altitude num"));
                if($rowArgs->altitude > 0) {
                    $htmlRow .= "{$rowArgs->altitude}";
                }
                $htmlRow .= $page->butler->cellClose();

                // Theoric EET
                $theoricEET = $rowArgs->theoricEET > 0 ? $rowArgs->theoricEET : "&nbsp;";
                $htmlRow .= $page->butler->cell("{$climbing}{$theoricEET}{$rowArgs->plus5}", array("class" => "EET num"));

                    // wind (if provided)
                    $windHeading = "";
                    $windSpeed = "";
                    $magHeading = "";
                    $groundSpeed = "";
                    $realEET = "";

                    if($rowArgs->hasWind) {
                        $windHeading = $rowArgs->windTC;
                        $windSpeed = $rowArgs->windSpeed;
                        $magHeading = $rowArgs->magneticHeading;
                        $groundSpeed = $rowArgs->groundSpeed;
                        $realEET = "{$climbing}{$rowArgs->realEET}{$rowArgs->plus5}";
                    }

                    $htmlRow .= $page->butler->cell($windHeading, array("class" => "wind heading num"));
                    $htmlRow .= $page->butler->cell($windSpeed, array("class" => "wind speed num"));

                    $htmlRow .= $page->butler->cell($magHeading, array("class" => "heading num"));

                    if($rowArgs->hasWind && $groundSpeed <= 0) {
                        $htmlRow .= $page->butler->cell("Wind too strong", array("colspan" => 2, "style" => "background-color: red;"));
                    } else {
                        $htmlRow .= $page->butler->cell($groundSpeed, array("class" => "speed num"));
                        $htmlRow .= $page->butler->cell($realEET, array("class" => "EET num"));
                    }

                // ETO + ATO
                $htmlRow .= $page->butler->cell();
                $htmlRow .= $page->butler->cell();

                // notes
                $htmlRow .= $page->butler->cell($rowArgs->notes, array("class" => "notes"));

                $htmlRow .= $page->butler->rowClose();

                return $htmlRow;
            }
        //
            // HTML row summary
            function htmlRowSummary($rowArgs) {
                // Set data
                global $kWaypoints;

                $distance = $rowArgs->alternateDistance;
                $theoricEeTime = $rowArgs->theoricAlternateTime;
                $realEeTime = $rowArgs->realAlternateTime;
                if($rowArgs->wpNum == $kWaypoints->wayOut->last) {
                    $distance = $rowArgs->tripDistance;
                    $theoricEeTime = $rowArgs->theoricTripTime;
                    $realEeTime = $rowArgs->realTripTime;

                } elseif($rowArgs->wpNum == $kWaypoints->wayBack->last) {
                    $distance = ($rowArgs->tripDistance - $rowArgs->destinationDistance);
                    $theoricEeTime = ($rowArgs->theoricTripTime - $rowArgs->theoricDestinationTime);
                    $realEeTime = ($rowArgs->realTripTime - $rowArgs->realDestinationTime);
                }
                // Do not display if zero
                if($theoricEeTime == 0) { $theoricEeTime = ""; }
                if($realEeTime == 0) { $realEeTime = ""; }

                // Prepare string
                global $page;

                $htmlRow = $page->butler->rowOpen(array("class" => "summary"));

                if($rowArgs->isAdmin) { $htmlRow .= $page->butler->cell("", array("class" => "unavailable")); }

                $htmlRow .= $page->butler->cell("", array("class" => "WP unavailable"));
                $htmlRow .= $page->butler->cell("", array("class" => "TC unavailable"));
                $htmlRow .= $page->butler->cell("", array("class" => "unavailable"));

                $htmlRow .= $page->butler->cell($distance, array("class" => "distance sum num"));

                $htmlRow .= $page->butler->cell("", array("class" => "unavailable"));

                $htmlRow .= $page->butler->cell($theoricEeTime, array("class" => "EET sum num"));

                $htmlRow .= $page->butler->cell("", array("colspan" => 2, "class" => "wind unavailable"));
                $htmlRow .= $page->butler->cell("", array("class" => "unavailable"));
                $htmlRow .= $page->butler->cell("", array("class" => "unavailable"));

                $htmlRow .= $page->butler->cell($realEeTime, array("class" => "EET sum num"));

                $htmlRow .= $page->butler->cell("", array("class" => "unavailable"));
                $htmlRow .= $page->butler->cell();
                $htmlRow .= $page->butler->cell();
                $htmlRow .= $page->butler->rowClose();

                return $htmlRow;
            }
        //
            // HTML row ANY
            function htmlRowAny($rowArgs) {
                global $kWaypoints;

                $htmlRow = "";

                if($rowArgs->wpNum == $kWaypoints->wayOut->base) {
                    // 1st row ever
                    // 1st row inbound we do not need, it is written on previous line where we are
                    $htmlRow .= htmlFirstRow($rowArgs);
                }

                if($rowArgs->wpNum == $kWaypoints->alternate->start || ($rowArgs->wpNum == $kWaypoints->alternate->last && $rowArgs->oldWP < $kWaypoints->alternate->limit)) {
                    $htmlRow .= htmlRowAlternateBanner($rowArgs->wpNum, $rowArgs->isAdmin);
                }

                if($rowArgs->wpNum > $kWaypoints->wayOut->base) {
                    $htmlRow .= htmlRow($rowArgs);
                }

                if($kWaypoints->isLast($rowArgs->wpNum)) {
                    $htmlRow .= htmlRowSummary($rowArgs);
                }

                return $htmlRow;
            }
        //
            function htmlReminders() {
                global $kStrings;

                $reminders = "<div class=\"NavReminders\">\n";
                $reminders .= "<b>{$kStrings['TopOfDescent']}:</b>\n";
                $reminders .= "<ul>\n";
                $reminders .= "<li>{$kStrings['TOD1']}</li>\n";
                $reminders .= "<li>{$kStrings['TOD2']}</li>\n";
                $reminders .= "<li>{$kStrings['TOD3']}</li>\n";
                $reminders .= "<li class=\"optional\">{$kStrings['TOD4']}</li>\n";
                $reminders .= "</ul>\n";
                $reminders .= "</div><!-- NavReminders -->\n";
                return $reminders;
            }
        //
            function htmlThWind() {
                global $kStrings;

                $thWind = "<div class=\"NavTHwind\">\n";
                $thWind .= "<b>Compute {$kStrings['THwind']}:</b>\n";
                $thWind .= "<ol>\n";
                $thWind .= "<li>&alpha;1 = (360 + TC - WH) % 360 and sign=1<br>\n";
                $thWind .= "if &alpha;1 &gt; 180: &alpha;1 = (360 + WH - TC) % 360 and sign=-1</li>\n";
                $thWind .= "<li>&alpha;2 = arcsin(WS/TS sin(&alpha;1))</li>\n";
                $thWind .= "<li>&alpha;3 = 180 - &alpha;1 - &alpha;2</li>\n";
                $thWind .= "<li>TH = TC + sign &alpha;2</li>\n";
                $thWind .= "<li>GS = sin(&alpha;3) / sin(&alpha;1) TS</li>\n";
                $thWind .= "</ol>\n";
                $thWind .= "</div><!-- NavTHwind -->\n";
                return $thWind;
            }
        //
            // HTML fuel
            function htmlFuel($theoricFuel, $realFuel) {
                global $page;
                global $kStrings;

                $htmlFuel = "<div class=\"fuel\">\n";

                $htmlFuel .= $page->butler->tableOpen(array("class" => "no_border"));
                    // head
                    $htmlFuel .= $page->butler->rowOpen();
                    $htmlFuel .= $page->butler->headerCell("Fuel", array("rowspan" => 3));
                    $htmlFuel .= $page->butler->headerCell(
                        "{$kStrings['FuelConsumption']} "
                        . ($theoricFuel->consumption > 0 ? "{$theoricFuel->consumption}" : "?")
                        . " {$theoricFuel->unit}/h\n",
                        array("colspan" => 4, "style" => "background-color: white;")
                    );
                    $htmlFuel .= $page->butler->rowClose();

                    $htmlFuel .= $page->butler->rowOpen();
                    $htmlFuel .= $page->butler->headerCell($kStrings["NoWind"], array("colspan" => 2));
                    $htmlFuel .= $page->butler->headerCell($kStrings["Wind"], array("colspan" => 2));
                    $htmlFuel .= $page->butler->rowClose();
                    $htmlFuel .= $page->butler->rowOpen();
                    $htmlFuel .= $page->butler->headerCell($kStrings["time"]);
                    $htmlFuel .= $page->butler->headerCell("{$kStrings['fuel']} [{$theoricFuel->unit}]");
                    $htmlFuel .= $page->butler->headerCell($kStrings["time"]);
                    $htmlFuel .= $page->butler->headerCell("{$kStrings['fuel']} [{$theoricFuel->unit}]");
                    $htmlFuel .= $page->butler->rowClose();
                //
                    // trip
                    $htmlFuel .= $page->butler->rowOpen();
                    $htmlFuel .= $page->butler->cell("{$kStrings['Trip']}:");
                    $htmlFuel .= $theoricFuel->htmlRow(FuelEntry::Trip);
                    $htmlFuel .= $realFuel->htmlRow(FuelEntry::Trip);
                    $htmlFuel .= $page->butler->rowClose();
                //
                    // alternate
                    $htmlFuel .= $page->butler->rowOpen();
                    $htmlFuel .= $page->butler->cell("{$kStrings['Alternate']}:");
                    $htmlFuel .= $theoricFuel->htmlRow(FuelEntry::Alternate);
                    $htmlFuel .= $realFuel->htmlRow(FuelEntry::Alternate);
                    $htmlFuel .= $page->butler->rowClose();
                //
                    // reserve
                    $htmlFuel .= $page->butler->rowOpen();
                    $htmlFuel .= $page->butler->cell("{$kStrings['Reserve']}:");
                    $htmlFuel .= $theoricFuel->htmlRow(FuelEntry::Reserve);
                    $htmlFuel .= $realFuel->htmlRow(FuelEntry::Reserve);
                    $htmlFuel .= $page->butler->rowClose();
                //
                    // unusable
                    $htmlFuel .= $page->butler->rowOpen();
                    $htmlFuel .= $page->butler->cell("{$kStrings['Unusable']}:");
                    $htmlFuel .= $theoricFuel->htmlRow(FuelEntry::Unusable);
                    $htmlFuel .= $realFuel->htmlRow(FuelEntry::Unusable);
                    $htmlFuel .= $page->butler->rowClose();
                //
                    // minimum
                    $htmlFuel .= $page->butler->rowOpen();
                    $htmlFuel .= $page->butler->cell("{$kStrings['MinFuel']}:");
                    $htmlFuel .= $theoricFuel->htmlRow(FuelEntry::Minimum);
                    $htmlFuel .= $realFuel->htmlRow(FuelEntry::Minimum);
                    $htmlFuel .= $page->butler->rowClose();
                //
                    // extra
                    $htmlFuel .= $page->butler->rowOpen();
                    $htmlFuel .= $page->butler->cell($kStrings["ExtraPlus"] . ($theoricFuel->extraPercent * 100) . "%:");
                    $htmlFuel .= $theoricFuel->htmlRow(FuelEntry::Extra);
                    $htmlFuel .= $realFuel->htmlRow(FuelEntry::Extra);
                    $htmlFuel .= $page->butler->rowClose();
                //
                    // ramp
                    $htmlFuel .= $page->butler->rowOpen();
                    $htmlFuel .= $page->butler->cell("{$kStrings['RampFuel']}:");
                    $htmlFuel .= $theoricFuel->htmlRamp();
                    $htmlFuel .= $realFuel->htmlRamp();
                    $htmlFuel .= $page->butler->rowClose();
                //
                $htmlFuel .= $page->butler->tableClose();
                $htmlFuel .= "</div>\n";

                return $htmlFuel;
            }
        //
            // HTML fuel tanks
            function htmlFuelTanks($rows) {
                global $kStrings;
                global $page;

                $htmlFuelTanks = "<div class=\"fueltanks\">\n";

                $htmlFuelTanks .= $page->butler->tableOpen(array("class" => "no_border"));
                    // head
                    $htmlFuelTanks .= $page->butler->rowOpen();
                    $htmlFuelTanks .= $page->butler->headerCell($kStrings["Fuel"]);
                    $htmlFuelTanks .= $page->butler->headerCell("Unusable");
                    $htmlFuelTanks .= $page->butler->headerCell("Usable");
                    $htmlFuelTanks .= $page->butler->headerCell("Total");
                    $htmlFuelTanks .= $page->butler->rowClose();

                foreach($rows as $row) {
                    if($row[0] == $kStrings["overflow"]) {
                        $htmlFuelTanks .= $page->butler->rowOpen();
                        $htmlFuelTanks .= $page->butler->cell("{$kStrings['overflow']}!!!", array("style" => "background-color: red;"));
                        $htmlFuelTanks .= $page->butler->cell("{$row[1]}", array("style" => "background-color: red;", "colspan" => 3));
                        $htmlFuelTanks .= $page->butler->rowClose();
                        continue;
                    }

                    $htmlFuelTanks .= $page->butler->rowOpen();
                    $htmlFuelTanks .= $page->butler->cell($row[0]);
                    $htmlFuelTanks .= $page->butler->cell($row[1]);
                    $htmlFuelTanks .= $page->butler->cell($row[2]);
                    $htmlFuelTanks .= $page->butler->cell($row[3]);
                    $htmlFuelTanks .= $page->butler->rowClose();
                }

                $htmlFuelTanks .= $page->butler->tableClose();
                $htmlFuelTanks .= "</div><!-- fueltanks -->\n";

                return $htmlFuelTanks;
            }
        //
            function htmlGcParseArgs($item, $args) {
                if($item->color == "red") {
                    $args["style"] = "background-color: red;";
                } elseif($item->color == "gray") {
                    $args["style"] = "background-color: #999;";
                }

                if($item->rowspan !== NULL) {
                    $args["rowspan"] = $item->rowspan;
                }

                return $args;
            }
        //
            function htmlGcCell($text, $args, $rowspanCell, $rowspanRow) {
                if($rowspanCell ==! NULL && $rowspanCell > 0) {
                    // We had a cell spanning over the current one, do nothing
                    return "";
                }

                if($rowspanCell === NULL && $rowspanRow !== NULL) {
                    // only for cells WITHOUT rowspan when there is one in the row
                    $borderStyle =" border-top: none; border-bottom: none;";
                    if($rowspanRow < 0) {
                        $borderStyle = " border-bottom: none;";
                    } elseif($rowspanRow == 1) {
                        $borderStyle = " border-top: none;";
                    }

                    if(!array_key_exists("style", $args)) {
                        $args["style"] = "";
                    }

                    $args["style"] .= $borderStyle;
                }

                global $page;
                return $page->butler->cell($text, $args);
            }
        //
            /**
             * HTML GC
             *
             * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
             * @SuppressWarnings(PHPMD.CyclomaticComplexity)
             * @SuppressWarnings(PHPMD.NPathComplexity)
             */
            function htmlGC($gcTable, $massUnit, $armUnit, $momentUnit) {
                global $page;
                global $kStrings;

                $htmlGC = "";
                $htmlGC .= "<div class=\"GC\">\n";
                $htmlGC .= $page->butler->tableOpen(array("class" => "no_border"));
                    // head
                    $htmlGC .= $page->butler->rowOpen();
                    $htmlGC .= $page->butler->headerCell($kStrings["MassAndBalance"], array("rowspan" => 2));
                    $htmlGC .= $page->butler->headerCell($kStrings["Mass"]);
                    $htmlGC .= $page->butler->headerCell($kStrings["Arm"]);
                    $htmlGC .= $page->butler->headerCell($kStrings["Moment"]);
                    $htmlGC .= $page->butler->rowClose();
                    $htmlGC .= $page->butler->rowOpen();
                    $htmlGC .= $page->butler->headerCell("[$massUnit]");
                    $htmlGC .= $page->butler->headerCell("[$armUnit]");
                    $htmlGC .= $page->butler->headerCell("[$momentUnit]");
                    $htmlGC .= $page->butler->rowClose();

                $rowspan = array("label" => NULL, "moment" => NULL, "ROW" => NULL);
                foreach($gcTable as $row) {
                    if($row === NULL) {
                        continue;
                    }

                    $label = $row[0];
                    $mass = $row[1];
                    $arm = $row[2];
                    $moment = $row[3];

                    $massText = $mass->value;
                    if($mass->tooHeavy) {
                        $massText .= " " . $kStrings["htmlTooHeavy"];
                    }

                        // args
                        $labelArgs = array();
                        $massArgs = array("class" => "mass num");
                        $armArgs = array("class" => "arm num");
                        $momentArgs = array("class" => "moment num");

                        if($arm->value === NULL) {
                            $armArgs = array("class" => "unavailable");
                        }
                        if($moment->value === NULL) {
                            $momentArgs = array("class" => "unavailable");
                        }

                        $labelArgs = htmlGcParseArgs($label, $labelArgs);
                        if(array_key_exists("rowspan", $labelArgs)) {
                            $rowspan["label"] = -$labelArgs["rowspan"];
                            $rowspan["ROW"] = $rowspan["label"];  // overall rowspan is never more than label
                        }

                        $massArgs = htmlGcParseArgs($mass, $massArgs);
                        $armArgs = htmlGcParseArgs($arm, $armArgs);

                        $momentArgs = htmlGcParseArgs($moment, $momentArgs);
                        if(array_key_exists("rowspan", $momentArgs)) {
                            $rowspan["moment"] = -$momentArgs["rowspan"];
                        }
                    //
                        // display
                        $htmlGC .= $page->butler->rowOpen();

                        $htmlGC .= htmlGcCell($label->value, $labelArgs, $rowspan["label"], $rowspan["ROW"]);
                        $htmlGC .= htmlGcCell($massText, $massArgs, NULL, $rowspan["ROW"]);
                        $htmlGC .= htmlGcCell($arm->value, $armArgs, NULL, $rowspan["ROW"]);
                        $htmlGC .= htmlGcCell($moment->value, $momentArgs, $rowspan["moment"], $rowspan["ROW"]);

                        $htmlGC .= $page->butler->rowClose();
                    //
                        // rowspan
                        $rowspan["label"] = decrementRowspan($rowspan["label"]);
                        $rowspan["moment"] = decrementRowspan($rowspan["moment"]);
                        $rowspan["ROW"] = decrementRowspan($rowspan["ROW"]);
                }

                $htmlGC .= $page->butler->tableClose();
                $htmlGC .= "</div><!-- GC -->\n";

                return $htmlGC;
            }
        //
            function htmlEnd() {
                global $kStrings;
                global $kFuelTypes;
                global $kFuelUnits;
                global $usgAvgas2lbs;

                $htmlEnd = "<div class=\"FuelWeight\">\n";
                $htmlEnd .= "<div>1 {$kStrings['USG']} = {$kFuelUnits["USG"]} liters</div>\n";
                $htmlEnd .= "<div>1 {$kStrings['ImpG']} = {$kFuelUnits["ImpG"]} liters</div>\n";
                $htmlEnd .= "<div>1 l {$kStrings['Avgas']} = {$kFuelTypes["AVGAS"]} kg</div>\n";
                $htmlEnd .= "<div>1 {$kStrings['USG']} {$kStrings['Avgas']} = $usgAvgas2lbs lbs</div>\n";
                $htmlEnd .= "</div><!-- FuelWeight -->\n";
                return $htmlEnd;

            }
    //
    //
        // LaTeX
            // html2latex
            function html2latex($string) {
                // translate html special characters
                $back = preg_replace("/&([aeiouy])(acute|grave|circ|uml);/", "$1", $string);
                $back = preg_replace("/&#[0-9]{3};/", "?", $back);
                $back = preg_replace("/&quot;/", "'", $back);
                $back = preg_replace("/&[a-z]+;/", "?", $back);

                // escape special LaTeX characters
                $back = preg_replace("/([_#&%^$])/", "\\\\$1", $back);

                return $back;
            }
        //
            // LaTeX head: usepackages
            function latexUsePackages() {
                $latexhead = "";
                $latexhead .= "% Usepackages {{{\n";
                    $latexhead .= "% General {{{\n";
                    $latexhead .= "\\usepackage[T1]{fontenc}\n";
                    $latexhead .= "\\usepackage{lmodern}\n";
                    $latexhead .= "\\usepackage[english]{babel}\n";
                    $latexhead .= "% }}}\n";
                //
                    $latexhead .= "% Math {{{\n";
                    $latexhead .= "\\usepackage{amsmath}\n";
                    $latexhead .= "%\\usepackage{amssymb}\n";
                    $latexhead .= "% }}}\n";
                //
                    $latexhead .= "% Hyper refs {{{\n";
                    $latexhead .= "\\usepackage{hyperref}\n";
                    $latexhead .= "\\hypersetup{\n";
                    $latexhead .= "    colorlinks        = true,\n";
                    $latexhead .= "    bookmarks         = true,\n";
                    $latexhead .= "    bookmarksnumbered = false,\n";
                    $latexhead .= "    linkcolor         = black,\n";
                    $latexhead .= "    urlcolor          = blue,\n";
                    $latexhead .= "    citecolor         = blue,\n";
                    $latexhead .= "    filecolor         = blue,\n";
                    $latexhead .= "    hyperfigures      = true,\n";
                    $latexhead .= "    breaklinks        = false,\n";
                    $latexhead .= "    ps2pdf,\n";
                    $latexhead .= "    pdftitle          = {\\ThisTitle},\n";
                    $latexhead .= "    pdfsubject        = {\\ThisTitle},\n";
                    $latexhead .= "    pdfauthor         = {\\ThisAuthors}\n";
                    $latexhead .= "}\n";
                    $latexhead .= "% }}}\n";
                //
                    $latexhead .= "% Tables and lists {{{\n";
                    $latexhead .= "\\usepackage{longtable}\n";
                    $latexhead .= "\\usepackage{multirow}\n";
                    $latexhead .= "\\usepackage{colortbl}\n";
                    $latexhead .= "\\usepackage{hhline}\n";
                    $latexhead .= "% }}}\n";
                //
                    $latexhead .= "% Making stuff fancy (not fancyhdr package) {{{\n";
                    $latexhead .= "\\usepackage{enumitem}\n";
                    $latexhead .= "\\setlist{noitemsep}\n";
                    $latexhead .= "\\usepackage[landscape,a4paper]{geometry}\n";
                    $latexhead .= "% }}}\n";
                $latexhead .= "% End of usepackages }}}\n";

                return $latexhead;
            }
        //
            // LaTeX head: 1st line
            function latexDocumentBegin($navid=0) {
                global $docVersion;

                $latexhead = "%\n";
                $latexhead .= "\\newcommand*{\\DocVersion}{ $docVersion}\n";
                    $latexhead .= "% Headers {{{\n";
                    $latexhead .= "\\documentclass[12pt,a4paper]{article}\n";
                    $latexhead .= "%\n";
                        $latexhead .= "% Document variables {{{\n";
                        $latexhead .= "\\newcommand*{\\Gael}{Ga\\\"el Induni}\n";
                        $latexhead .= "\\newcommand*{\\ThisAuthors}{\\Gael}\n";
                        $latexhead .= "\\newcommand*{\\ThisTitle}{Navigation plan}\n";
                        $latexhead .= "% End of document variables }}}\n";

                    $latexhead .= latexUsePackages();

                        $latexhead .= "% Document size {{{\n";
                        $latexhead .= "\\setlength{\\topmargin}{-8mm}\n";
                        $latexhead .= "\\setlength{\\textheight}{180mm}\n";
                        $latexhead .= "\\setlength{\\hoffset}{-28mm}\n";
                        $latexhead .= "\\setlength{\\textwidth}{280mm}\n";
                        $latexhead .= "\\setlength{\\evensidemargin}{9mm}\n";
                        $latexhead .= "\\setlength{\\parskip}{0.5ex}\n";
                        $latexhead .= "% End of document size }}}\n";
                    //
                        $url = "https://xonnqopp.ch/fly/nav/";
                        if($navid > 0) {
                            $url .= "display.php?id=$navid";
                        }

                        $latexhead .= "% Fancy and header and footer rules {{{\n";
                        $latexhead .= "\\usepackage{fancyhdr}\n";
                        $latexhead .= "%\\usepackage{lastpage}\n";
                        $latexhead .= "%\\newcommand*{\\LastPage}{\\pageref{LastPage}}\n";
                        $latexhead .= "% Defining document filename\n";
                        $latexhead .= "\\newcommand*{\\GoodJob}{\\jobname.pdf}\n";
                        $latexhead .= "% Fancy!\n";
                        $latexhead .= "\\fancypagestyle{plain}{%\n";
                        $latexhead .= "\\fancyhf{}\n";
                        $latexhead .= "\\fancyhf[HR]{\\ThisAuthors}\n";
                        $latexhead .= "\\fancyhf[C]{\\texttt{{$url}}}\n";
                        $latexhead .= "\\fancyhf[HL]{NavPlan.pdf\\ v.~\\DocVersion}\n";
                        $latexhead .= "%\\fancyhf[FRO,FLE]{\\thepage/\\LastPage}\n";
                        $latexhead .= "% Rules at top and bottom\n";
                        $latexhead .= "}\n";
                        $latexhead .= "\\pagestyle{plain}\n";
                        $latexhead .= "\\renewcommand{\\headrulewidth}{0.4pt}\n";
                        $latexhead .= "%\\renewcommand{\\footrulewidth}{0.4pt}\n";
                        $latexhead .= "% End of fancy }}}\n";
                    //
                        $latexhead .= "% New commands {{{\n";
                        //$latexhead .= "\\renewcommand{\\geq}{\\geqslant}\n";
                        //$latexhead .= "\\renewcommand{\\leq}{\\leqslant}\n";
                        $latexhead .= "\\newcommand*{\\oC}{\\ensuremath{^{\\circ}C}}\n";
                        $latexhead .= "\\AtBeginDocument{\\renewcommand{\\labelitemi}{\\textbullet}}\n";
                        $latexhead .= "\\newcommand*{\\DarkGrayCell}{\\cellcolor[gray]{0.66}}\n";
                        $latexhead .= "\\newcommand*{\\GrayCell}{\\cellcolor[gray]{0.8}}\n";
                        $latexhead .= "\\newcommand*{\\RedCell}{\\cellcolor[rgb]{1,0,0}}\n";
                        $latexhead .= "% End of new commands }}}\n";
                    $latexhead .= "% }}}\n";
                $latexhead .= "%\n";
                $latexhead .= "\\begin{document}\n";
                $latexhead .= "%\n";
                $latexhead .= "\\mbox{}\n";
                $latexhead .= "\\vspace{-16mm}\n";

                return $latexhead;
            }
        //
            // LaTeX head: first table
            function latexIntroTable($plane, $name="", $variation=NULL) {
                if ($variation === NULL) {
                    global $kDefaultVariation;
                    $variation = $kDefaultVariation;
                }

                $longID = preg_replace("/-/", "---", $plane->identification);

                $latexhead = "";
                $latexhead .= "% Table intro {{{\n";
                $latexhead .= "\\begin{longtable}{|c|c|c||c|}\n";
                    $latexhead .= "xxxxxxxxxxxxxxxxxxxxxxxxx xxxxxxxxxxxxxxxxxxxxxxxxx\n";
                    $latexhead .= "& xxxxxxxxxxxxx\n";
                    $latexhead .= "& 0000000000\n";
                    $latexhead .= "&\\kill\n";
                //
                    $latexhead .= "\\multirow{2}{*}{\\large " . html2latex($name) . "}\n";
                    $latexhead .= "& {$plane->type}\n";
                    $latexhead .= "& TAS  [kts]\n";
                    $latexhead .= "& \\multirow{2}{*}{VAR: \$$variation^{\\textrm{o}} \\textrm{E}$}\n";
                    $latexhead .= "\\\\\\hhline{~--~}\n";
                //
                    $latexhead .= "%\n";
                    $latexhead .= "&{$longID}\n";
                    $latexhead .= "&";

                    if($plane->speedPlanning > 0) {
                        $latexhead .= $plane->speedPlanning;

                        if($plane->speedClimb > 0) {
                            $latexhead .= " ({$plane->speedClimb})";
                        }
                    }

                    $latexhead .= "\n";
                    $latexhead .= "&\n";
                    $latexhead .= "\\\\\n";
                $latexhead .= "\n\\end{longtable}\n";
                $latexhead .= "% }}} Table intro\n";

                return $latexhead;
            }
        //
            // LaTeX head: header of 2nd table
            function latexNavPlanTableHead() {
                global $kStrings;

                $windGray = "[gray]{0.88}";

                $latexhead = "";
                $latexhead .= "\\vspace{-8.7mm}\n";

                // Make rows a little larger from now on
                $latexhead .= "\\renewcommand*{\\arraystretch}{1.5}\n";

                $latexhead .= "% Nav plan {{{\n";
                $latexhead .= "\\begin{longtable}{%\n";
                $latexhead .= "    |l|%\n";
                $latexhead .= "    >{\\columncolor[gray]{0.75}}c|c|c|c|c|%\n";
                $latexhead .= "    |>{\\columncolor$windGray}c|>{\\columncolor$windGray}c|c|c|c|%\n";
                $latexhead .= "    |c|c||l|%\n";
                $latexhead .= "}\n";
                    $latexhead .= "% Template width {{{\n";
                    $latexhead .= "bla bla bla bla bla bla\n";
                    $latexhead .= "& 000\n";
                    $latexhead .= "& VAC\n";
                    $latexhead .= "&\n";
                    $latexhead .= "& altitudeee\n";
                    $latexhead .= "&\n";
                    $latexhead .= "& 00000\n";
                    $latexhead .= "&\n";
                    $latexhead .= "& VAC\n";
                    $latexhead .= "& 00000\n";
                    $latexhead .= "&\n";
                    $latexhead .= "& 0000\n";
                    $latexhead .= "& 0000\n";
                    $latexhead .= "& bla bla bla bla bla bla bla\n";
                    $latexhead .= "\\kill\n";
                    $latexhead .= "% }}}\n";

                $latexhead .= "% Nav plan Head {{{\n";
                $latexhead .= "\\hline\n";

                    //$latexhead .= "\\multirow{2}{*}{";
                    $latexhead .= "\\textbf{{$kStrings['Waypoint']}}";
                    //$latexhead .= "}";
                    $latexhead .= "\n";

                    $latexhead .= "& \\textbf{{$kStrings['TrueCourse']}}\n";
                    $latexhead .= "& \\textbf{{$kStrings['MagneticCourse']}}\n";
                    $latexhead .= "& \\textbf{{$kStrings['Dist']}}\n";
                    $latexhead .= "& \\textbf{{$kStrings['Altitude']}}\n";
                    $latexhead .= "& \\textbf{{$kStrings['EstimatedElapsedTime']}}\n";
                    $latexhead .= "& \\multicolumn{2}{c|}{\\cellcolor$windGray \\textbf{{$kStrings['Wind']}}}\n";
                    $latexhead .= "& \\textbf{{$kStrings['MagneticHeading']}}\n";
                    $latexhead .= "& \\textbf{{$kStrings['GroundSpeed']}}\n";
                    $latexhead .= "& \\textbf{{$kStrings['EstimatedElapsedTime']}}\n";
                    $latexhead .= "& ";
                    //$latexhead .= "\\multirow{2}{*}{";
                    $latexhead .= "\\textbf{{$kStrings['EstimatedTimeOver']}}";
                    //$latexhead .= "}\n";
                    $latexhead .= "& ";
                    //$latexhead .= "\\multirow{2}{*}{";
                    $latexhead .= "\\textbf{{$kStrings['ActualTimeOver']}}";
                    //$latexhead .= "}\n";
                    $latexhead .= "& ";
                    //$latexhead .= "\\multirow{2}{*}{";
                    $latexhead .= "\\textbf{{$kStrings['Notes']}}";
                    //$latexhead .= "}\n";

                //$latexhead .= "\\\\\n";
                //$latexhead .= "\\hhline{~~~~~~--~~~~~~}\n";

                    /*
                    $latexhead .= "\n";
                    $latexhead .= "& {$kStrings['latexUnitDeg']}\n";
                    $latexhead .= "& {$kStrings['latexUnitDeg']}\n";
                    $latexhead .= "& {$kStrings['unitNM']}\n";
                    $latexhead .= "& {$kStrings['unitFt']}\n";
                    $latexhead .= "& {$kStrings['unitMin']}\n";
                    $latexhead .= "& {$kStrings['latexUnitDeg']}\n";
                    $latexhead .= "& {$kStrings['unitKts']}\n";
                    $latexhead .= "& {$kStrings['latexUnitDeg']}\n";
                    $latexhead .= "& {$kStrings['unitKts']}\n";
                    $latexhead .= "& {$kStrings['unitMin']}\n";
                    $latexhead .= "&&&\n";
                     */

                $latexhead .= "\\\\\n";
                //$latexhead .= "\\hline\n";
                $latexhead .= "\\hhline{==============}\n";

                $latexhead .= "% }}} Nav plan Head\n";
                $latexhead .= "\\endhead\n";
                $latexhead .= "\\hline\\endfoot\n";

                return $latexhead;
            }
        //
            // LaTeX end of header
            function latexNavPlanTableFoot() {
                $latexhead = "";
                $latexhead .= "\\end{longtable}\n";
                $latexhead .= "\\renewcommand*{\\arraystretch}{1.0}\n";
                $latexhead .= "% }}} Nav plan\n";
                $latexhead .= "%\n";
                return $latexhead;
            }
        //
            function latex2ndPageOpen() {
                $contents = "";
                $contents .= "\\clearpage\n";
                $contents .= "\\fancyhf{}\n";
                $contents .= "\\renewcommand{\\headrulewidth}{0pt}\n";
                $contents .= "\\noindent\n";
                $contents .= "\\begin{minipage}{0.49\\textwidth}\n";
                return $contents;
            }
        //
            function latexReminders() {
                global $kStrings;

                $contents = "";

                $contents .= "% {$kStrings['TopOfDescent']} {{{\n";
                $contents .= "\\textbf{{$kStrings['TopOfDescent']}:}\n";
                $contents .= "\\begin{itemize}\n";
                $tod1 = preg_replace("/<br\>/", "\\\\\\", $kStrings["TOD1"]);
                $contents .= "    \\item {$tod1}\n";
                $contents .= "    \\item {$kStrings['TOD2']}\n";
                $contents .= "    \\item {$kStrings['TOD3']}\n";
                $contents .= "    \\item ({$kStrings['TOD4']})\n";
                $contents .= "\\end{itemize}\n";
                $contents .= "% }}} {$kStrings['TopOfDescent']}\n";

                return $contents;
            }
        //
            function latexThWind() {
                global $kStrings;

                $thWind = "% {$kStrings['THwind']} {{{\n";
                $thWind .= "{\n";
                $thWind .= $kStrings["THwind"] . ":\n";
                $thWind .= "\\begin{enumerate}\n";
                $thWind .= "\\item $\\alpha_1 = (360 + \\textrm{TC} - \\textrm{WH}) \\% 360^{\\circ}$ and $\\textrm{sign} = 1$\n";
                $thWind .= "\\item if $\\alpha_1 > 180: \\alpha_1 = (360 + \\textrm{WH} - \\textrm{TC}) \\% 360^{\\circ}$ and $\\textrm{sign} = -1$\n";
                $thWind .= "\\item $\\alpha_2 = \\arcsin \\left( \\frac{\\textrm{WS}}{\\textrm{TS}} \\cdot \\sin \\alpha_1 \\right)$\n";
                $thWind .= "\\item $\\alpha_3 = 180 - (\\alpha_1 + \\alpha_2)$\n";
                $thWind .= "\\item $\\textrm{TH} = \\textrm{TC} + \\textrm{sign} \\cdot \\alpha_2$\n";
                $thWind .= "\\item $\\textrm{GS} = \\frac{\\sin \\alpha_3}{\\sin \\alpha_1} \\cdot \\textrm{TS}$\n";
                $thWind .= "\\end{enumerate}\n";
                $thWind .= "}\n";
                $thWind .= "% }}} {$kStrings['THwind']}\n";
                return $thWind;
            }
        //
            // LaTeX 2nd page right column
            function latex2ndPageChangeColumn() {
                $contents = "\\vspace*{6mm}\n";
                $contents .= "\\end{minipage}\n";

                // change to 2nd column
                $contents .= "\\begin{minipage}{0.50\\textwidth}\n";
                $contents .= "\\vspace{-7mm}\n";

                return $contents;
            }
        //
            // LaTeX end
            function latexEnd() {
                // end of LaTeX
                global $kStrings;
                global $kFuelTypes;
                global $kFuelUnits;
                global $usgAvgas2lbs;

                $latexend = "";
                $latexend .= "\\vspace{-2mm}\n";
                $latexend .= "{\\small\n";
                $latexend .= "$1\\ \\textrm{{$kStrings['USG']}} = {$kFuelUnits["USG"]}\\ l$\n";
                $latexend .= "\\hspace{17mm}\n";
                $latexend .= "$1\\ l\\ \\textrm{{$kStrings['Avgas']}} = {$kFuelTypes["AVGAS"]}\\ kg$\n";
                $latexend .= "\\\\\n";
                $latexend .= "$1\\ \\textrm{{$kStrings['ImpG']}} = {$kFuelUnits["ImpG"]}\\ l$\n";
                $latexend .= "\\hspace{17mm}\n";
                $latexend .= "$1\\ \\textrm{{$kStrings['USG']} {$kStrings['Avgas']}} = $usgAvgas2lbs$ lbs\n";
                $latexend .= "}  % small\n";
                $latexend .= "% }}}\n";
                $latexend .= "\\end{minipage}\n";
                $latexend .= "\\end{document}\n";
                return $latexend;
            }
        //
            /**
             * LaTeX notes
             *
             * Returns:
             *     (string) translated HTML characters
             */
            function latexNotes($notes) {
                return html2latex(htmlspecialchars_decode($notes, ENT_NOQUOTES));
            }
        //
            /**
             * LaTeX 1st row
             *
             * @SuppressWarnings(PHPMD.MissingImport)
             */
            function latexRowFirst($rowArgs) {
                // Set data
                if($rowArgs === NULL) {
                    // Prepare default args
                    $rowArgs = new stdClass();
                    $rowArgs->wpNum = 0;
                    $rowArgs->oldWP = -1;
                    $rowArgs->waypoint = "";
                    $rowArgs->destination = "";
                    $rowArgs->notes = "";
                    $rowArgs->trueCourse = "";
                    $rowArgs->magneticCourse = "";
                    $rowArgs->altitude = 0;
                    $rowArgs->distance = 0;
                    $rowArgs->climbing = false;
                    $rowArgs->theoricEET = 0;
                    $rowArgs->hasWind = false;
                    $rowArgs->windTC = "";
                    $rowArgs->windSpeed = 0;
                    $rowArgs->magneticHeading = "";
                    $rowArgs->groundSpeed = 0;
                    $rowArgs->realEET = 0;
                    $rowArgs->destinationDistance = 0;
                    $rowArgs->tripDistance = 0;
                    $rowArgs->alternateDistance = 0;
                    $rowArgs->theoricDestinationTime = 0;
                    $rowArgs->theoricTripTime = 0;
                    $rowArgs->theoricAlternateTime = 0;
                    $rowArgs->realTripTime = 0;
                    $rowArgs->realDestinationTime = 0;
                    $rowArgs->realAlternateTime = 0;
                }

                // Prepare string
                $back = new stdClass();
                $back->inc = 0;
                $back->latexcontent = "";

                global $kWaypoints;

                if($rowArgs->wpNum == $kWaypoints->wayBack->start) {
                    $back->inc = -1;
                    $back->latexcontent .= "\\hhline{==============}\n";
                }

                $destination = $rowArgs->destination;
                if($rowArgs->wpNum == $kWaypoints->wayOut->base) {
                    $destination = $rowArgs->waypoint;
                }

                $back->latexcontent .= html2latex($destination) . " ";
                $back->latexcontent .= "&\\DarkGrayCell\n";
                $back->latexcontent .= "&\\DarkGrayCell\n";
                $back->latexcontent .= "&\\DarkGrayCell\n";
                $back->latexcontent .= "&\\DarkGrayCell\n";
                $back->latexcontent .= "&\\DarkGrayCell\n";
                $back->latexcontent .= "&\n";
                $back->latexcontent .= "&\n";
                $back->latexcontent .= "&\\DarkGrayCell\n";
                $back->latexcontent .= "&\\DarkGrayCell\n";
                $back->latexcontent .= "&\\DarkGrayCell\n";
                $back->latexcontent .= "&\\DarkGrayCell\n";
                $back->latexcontent .= "&\n";
                $back->latexcontent .= "&";
                if($rowArgs->wpNum == $kWaypoints->wayOut->base && $rowArgs->notes != "") {
                    $back->latexcontent .= " " . latexNotes($rowArgs->notes) . " ";
                }
                $back->latexcontent .= "\\\\";

                return $back;
            }
        //
            /**
             * LaTeX row
             *
             * @SuppressWarnings(PHPMD.ElseExpression)
             * @SuppressWarnings(PHPMD.CyclomaticComplexity)
             * @SuppressWarnings(PHPMD.NPathComplexity)
             */
            function latexRow($rowArgs) {
                // Set data
                global $kWaypoints;
                global $kStrings;

                $climbing = $rowArgs->climbing ? $kStrings["latexCopyright"] : "";

                // Prepare string
                $latexcontent = "";

                $newline = "\\hline\n";
                if(
                    $rowArgs->wpNum == $kWaypoints->alternate->start
                    || (
                        $rowArgs->wpNum == $kWaypoints->alternate->last
                        && $rowArgs->oldWP < $kWaypoints->alternate->limit
                    )
                ) {
                    $newline = "\\hhline{==============}\n";
                }
                $latexcontent .= $newline;
                $latexcontent .= html2latex($rowArgs->waypoint);
                $latexcontent .= " & {$rowArgs->trueCourse}";
                $latexcontent .= " & {\\large {$rowArgs->magneticCourse}}";
                $latexcontent .= " & {\\large {$rowArgs->distance}}";
                $latexcontent .= " &";
                if($rowArgs->altitude > 0) {
                    $latexcontent .= " {\\large {$rowArgs->altitude}}";
                }
                $latexcontent .= " & {$climbing}";
                $latexcontent .= " {\\large ";
                if($rowArgs->theoricEET > 0) {
                    $latexcontent .= "{$rowArgs->theoricEET}";
                }
                $latexcontent .= "{$rowArgs->plus5}}";
                $latexcontent .= " &";

                // wind (if provided)
                $windHeading = "";
                $windSpeed = "";
                $magHeading = "";
                $groundSpeed = "";
                $realEET = "";

                if($rowArgs->hasWind) {
                    $windHeading = $rowArgs->windTC;
                    $windSpeed = $rowArgs->windSpeed;
                    $magHeading = "{\\large {$rowArgs->magneticHeading}}";
                    $groundSpeed = $rowArgs->groundSpeed;
                    $realEET = "{$climbing} {\\large {$rowArgs->realEET}{$rowArgs->plus5}}";
                }

                $latexcontent .= " {$windHeading}";
                $latexcontent .= " & {$windSpeed}";
                $latexcontent .= " & {$magHeading}";

                if($rowArgs->hasWind && $groundSpeed <= 0) {
                    $latexcontent .= " & \\multicolumn{2}{c|}{\\RedCell Wind too strong}";
                } else {
                    $latexcontent .= " & {$groundSpeed}";
                    $latexcontent .= " & {$realEET}";
                }

                // ETO + ATO
                $latexcontent .= " &";
                $latexcontent .= " &";

                $latexcontent .= " & " . latexNotes($rowArgs->notes);
                $latexcontent .= "\\\\";

                return $latexcontent;
            }
        //
            // LaTeX row summary
            function latexRowSummary($rowArgs) {
                // Set data
                global $kWaypoints;

                $distance = $rowArgs->alternateDistance;
                $theoricEeTime = $rowArgs->theoricAlternateTime;
                $realEeTime = $rowArgs->realAlternateTime;
                if($rowArgs->wpNum == $kWaypoints->wayOut->last) {
                    $distance = $rowArgs->tripDistance;
                    $theoricEeTime = $rowArgs->theoricTripTime;
                    $realEeTime = $rowArgs->realTripTime;

                } elseif($rowArgs->wpNum == $kWaypoints->wayBack->last) {
                    $distance = ($rowArgs->tripDistance - $rowArgs->destinationDistance);
                    $theoricEeTime = ($rowArgs->theoricTripTime - $rowArgs->theoricDestinationTime);
                    $realEeTime = ($rowArgs->realTripTime - $rowArgs->realDestinationTime);
                }
                if($theoricEeTime == 0) {
                    // Do not display if zero
                    $theoricEeTime = "";
                }
                if($realEeTime == 0) {
                    // Do not display if zero
                    $realEeTime = "";
                }

                // Prepare string
                $latexcontent = "";

                $latexcontent .= "\\hhline{---=-=----=---}\n";
                $latexcontent .=   "\\DarkGrayCell ";
                $latexcontent .= "& \\DarkGrayCell ";
                $latexcontent .= "& \\DarkGrayCell ";
                $latexcontent .= "& {$distance} ";
                $latexcontent .= "& \\DarkGrayCell ";
                $latexcontent .= "& {$theoricEeTime} ";
                $latexcontent .= "& \\DarkGrayCell ";
                $latexcontent .= "& \\DarkGrayCell ";
                $latexcontent .= "& \\DarkGrayCell ";
                $latexcontent .= "& \\DarkGrayCell ";
                $latexcontent .= "& {$realEeTime} ";
                $latexcontent .= "& \\DarkGrayCell ";
                $latexcontent .= "&";
                $latexcontent .= "&";
                $latexcontent .= "\\\\";

                if($rowArgs->wpNum == $kWaypoints->alternate->last) {
                    $latexcontent .= "\\hline\n";
                }

                return $latexcontent;
            }
        //
            /**
             * LaTeX row ANY
             *
             * Returns:
             *     object: inc, latexcontent
             *
             * @SuppressWarnings(PHPMD.MissingImport)
             */
            function latexRowAny($rowArgs) {
                global $kWaypoints;

                $back = new stdClass();
                $back->inc = 1;
                $back->latexcontent = "";

                if($rowArgs->wpNum == $kWaypoints->wayOut->base) {
                    $firstRow = latexRowFirst($rowArgs);
                    $back->latexcontent .= $firstRow->latexcontent;
                    $back->inc += $firstRow->inc;
                }

                if($rowArgs->wpNum > $kWaypoints->wayOut->base) {
                    $back->latexcontent .= latexRow($rowArgs);
                }

                if($kWaypoints->isLast($rowArgs->wpNum)) {
                    $back->latexcontent .= latexRowSummary($rowArgs);
                    $back->inc += 1;
                }

                return $back;
            }
        //
            function latexNavPlanTableFill($wpNum, $rows, $maxRow) {
                $latexcontent = "";
                global $kWaypoints;
                if($wpNum == $kWaypoints->wayOut->base) {
                    $latexcontent .= "\\hline\n";
                } elseif($wpNum == $kWaypoints->wayOut->last) {
                    $latexcontent .= "\\hhline{-~~~~~~~~~~--~}\n";
                }
                while($rows < $maxRow - 1) {
                    $rows++;
                    $latexcontent .= "&&&&&&&&&&&&&\\\\\\hline\n";
                }
                return $latexcontent;
            }
        //
            // LaTeX fuel
            function latexFuel($theoricFuel, $realFuel) {
                global $kStrings;

                $notAvailable = "~~";

                $consumption = $theoricFuel->consumption > 0 ? $theoricFuel->consumption : $notAvailable;
                $unit = $theoricFuel->unit != "" ? $theoricFuel->unit : $notAvailable;

                $latexfuel = "";
                    // Fuel fold
                    $latexfuel .= "% {$kStrings['Fuel']} {{{\n";
                    $latexfuel .= "\\begin{center}\n";
                //
                    // Begin table
                    $latexfuel .= "\\begin{tabular}{|l||r@{ :}c|r||r@{ :}c|r|}\n";
                    $latexfuel .= "\\hline\n";
                    $latexfuel .= "\\multicolumn{1}{|c|}{}\n";
                    $latexfuel .= "& \\multicolumn{6}{c|}{\\textbf{{$kStrings['FuelConsumption']}} {$consumption} {$unit}/h}\n";
                    $latexfuel .= "\\\\\\hhline{~------}\n";
                    $latexfuel .= "\\multicolumn{1}{|c|}{\\textbf{Fuel}}\n";
                    $latexfuel .= "& \\multicolumn{3}{c||}{{$kStrings['NoWind']}}\n";
                    $latexfuel .= "& \\multicolumn{3}{c|}{{$kStrings['Wind']}}\n";
                    $latexfuel .= "\\\\\\hhline{~------}\n";
                    $latexfuel .= "\\multicolumn{1}{|c|}{}\n";
                    $latexfuel .= "& \\multicolumn{2}{c|}{{$kStrings['time']}}\n";
                    $latexfuel .= "& {$kStrings['fuel']} [{$theoricFuel->unit}]\n";
                    $latexfuel .= "& \\multicolumn{2}{c|}{{$kStrings['time']}}\n";
                    $latexfuel .= "& {$kStrings['fuel']} [{$theoricFuel->unit}]\n";
                    $latexfuel .= "\\\\\\hline\n";
                //
                    // trip
                    $latexfuel .= $kStrings["Trip"] . ":\n";
                    $latexfuel .= "& " . $theoricFuel->latexRow(FuelEntry::Trip);
                    $latexfuel .= "& " . $realFuel->latexRow(FuelEntry::Trip);
                    $latexfuel .= "\\\\\\hline\n";
                //
                    // alternate
                    $latexfuel .= $kStrings["Alternate"] . ":\n";
                    $latexfuel .= "& " . $theoricFuel->latexRow(FuelEntry::Alternate);
                    $latexfuel .= "& " . $realFuel->latexRow(FuelEntry::Alternate);
                    $latexfuel .= "\\\\\\hline\n";
                //
                    // reserve
                    $latexfuel .= $kStrings["Reserve"] . ":\n";
                    $latexfuel .= "& " . $theoricFuel->latexRow(FuelEntry::Reserve);
                    $latexfuel .= "& " . $realFuel->latexRow(FuelEntry::Reserve);
                    $latexfuel .= "\\\\\\hline\n";
                //
                    // unusable
                    $latexfuel .= $kStrings["Unusable"] . ":\n";
                    $latexfuel .= "& " . $theoricFuel->latexRow(FuelEntry::Unusable);
                    $latexfuel .= "& " . $realFuel->latexRow(FuelEntry::Unusable);
                    $latexfuel .= "\\\\\\hline\n";
                $latexfuel .= "\\hline\n";

                    // minimum
                    $latexfuel .= $kStrings["MinFuel"] . ":\n";
                    $latexfuel .= "& " . $theoricFuel->latexRow(FuelEntry::Minimum);
                    $latexfuel .= "& " . $realFuel->latexRow(FuelEntry::Minimum);
                    $latexfuel .= "\\\\\\hline\n";
                //
                    // extra
                    $latexfuel .= $kStrings["ExtraPlus"] . ($theoricFuel->extraPercent * 100) . "\\%:\n";
                    $latexfuel .= "& " . $theoricFuel->latexRow(FuelEntry::Extra);
                    $latexfuel .= "& " . $realFuel->latexRow(FuelEntry::Extra);
                    $latexfuel .= "\\\\\\hline\\hline\n";
                //
                    // ramp fuel
                    $latexfuel .= $kStrings["RampFuel"] . ":\n";
                    $latexfuel .= "& " . $theoricFuel->latexRamp();
                    $latexfuel .= "& " . $realFuel->latexRamp();
                    $latexfuel .= "\\\\\\hline\n";
                //
                    // End table
                    $latexfuel .= "\\end{tabular}\n";
                    $latexfuel .= "\\end{center}\n";
                    $latexfuel .= "% }}}\n";
                return $latexfuel;
            }
        //
            // LaTeX fuel tanks
            function latexFuelTanks($rows) {
                global $kStrings;

                $latexFuelTanks = "";
                $latexFuelTanks .= "% Fuel tanks {{{\n";
                $latexFuelTanks .= "\\begin{center}\n";
                $latexFuelTanks .= "\\begin{tabular}{|l|r|r|r|}\n";
                    // head
                    $latexFuelTanks .= "\\hline\n";
                    $latexFuelTanks .= "\\multicolumn{1}{|c|}{\\textbf{{$kStrings['Fuel']}}}";
                    $latexFuelTanks .= " & \\multicolumn{1}{c|}{\\textbf{Unusable}}";
                    $latexFuelTanks .= " & \\multicolumn{1}{c|}{\\textbf{Usable}}";
                    $latexFuelTanks .= " & \\multicolumn{1}{c|}{\\textbf{Total}}";
                    $latexFuelTanks .= "\\\\\\hline\n";
                if($rows !== NULL) {
                    foreach($rows as $row) {
                        if($row[0] == $kStrings["overflow"]) {
                            $latexFuelTanks .= "\\RedCell " . html2latex($kStrings["overflow"] . "!!!");
                            $latexFuelTanks .= " & \\multicolumn{3}{l|}{\\RedCell " . html2latex($row[1]) . "}\\\\\\hline\n";
                            continue;
                        }

                        $latexFuelTanks .= html2latex($row[0]);
                        $latexFuelTanks .= " & " . html2latex($row[1]);
                        $latexFuelTanks .= " & " . html2latex($row[2]);
                        $latexFuelTanks .= " & " . html2latex($row[3]);
                        $latexFuelTanks .= "\\\\\\hline\n";
                    }
                }

                // End table
                $latexFuelTanks .= "\\end{tabular}\n";
                $latexFuelTanks .= "\\end{center}\n";
                $latexFuelTanks .= "% }}} Fuel tanks\n";

                return $latexFuelTanks;
            }
        //
            /**
             * LaTeX GC cell.
             */
            function latexGcCell($item, $rowspan=NULL) {
                if($rowspan !== NULL && $rowspan > 0) {
                    return "";
                }

                if($item->value === NULL) {
                    return "\\DarkGrayCell";
                }

                $text = html2latex($item->value);
                if($item->tooHeavy) {
                    global $kStrings;
                    $text .= " " . $kStrings["latexTooHeavy"];
                }

                $prefix = "";
                if($item->color == "red") {
                    $prefix = "\\RedCell ";
                } elseif($item->color == "gray") {
                    //$prefix = "\\GrayCell ";
                    // Not really working, just keep all cells white
                }
                $text = "$prefix$text";

                if($item->rowspan !== NULL) {
                    $text = "\\multirow{{$item->rowspan}}{*}{{$text}}";
                }

                return $text;
            }
        //
            /**
             * LaTeX GC
             *
             * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
             * @SuppressWarnings(PHPMD.CyclomaticComplexity)
             * @SuppressWarnings(PHPMD.NPathComplexity)
             */
            function latexGc($gcTable, $massUnit, $armUnit, $momentUnit) {
                global $kStrings;

                $latexGC = "";
                $latexGC .= "% {$kStrings['MassAndBalance']} {{{\n";
                $latexGC .= "\\begin{center}\n";
                    // head
                    $latexGC .= "\\begin{tabular}{|l|r|r|r|}\n";
                    $latexGC .= "\\hline\n";
                    $latexGC .= "\\multirow{2}{*}{\\textbf{{$kStrings['MassAndBalance']}}}\n";
                    $latexGC .= "& \\multicolumn{1}{c|}{{$kStrings['Mass']}}\n";
                    $latexGC .= "& \\multicolumn{1}{c|}{{$kStrings['Arm']}}\n";
                    $latexGC .= "& \\multicolumn{1}{c|}{{$kStrings['Moment']}}\n";
                    $latexGC .= "\\\\\n";
                    $latexGC .= "& \\multicolumn{1}{c|}{[$massUnit]}\n";
                    $latexGC .= "& \\multicolumn{1}{c|}{[$armUnit]}\n";
                    $latexGC .= "& \\multicolumn{1}{c|}{[$momentUnit]}\n";
                    $latexGC .= "\\\\\\hhline{-===}\n";

                $rowspan = array("label" => NULL, "moment" => NULL);
                foreach($gcTable as $row) {
                    if($row === NULL) {
                        continue;
                    }

                    $label = $row[0];
                    $mass = $row[1];
                    $arm = $row[2];
                    $moment = $row[3];

                    $hline = "hline";
                    if(count($row) > 4) {
                        $hline = $row[4];
                    }

                    if($label->rowspan !== NULL) { $rowspan["label"] = -$label->rowspan; }
                    if($moment->rowspan !== NULL) { $rowspan["moment"] = -$moment->rowspan; }

                    $latexGC .= latexGcCell($label, $rowspan["label"]);
                    $latexGC .= " & " . latexGcCell($mass);
                    $latexGC .= " & " . latexGcCell($arm);
                    $latexGC .= " & " . latexGcCell($moment, $rowspan["moment"]);

                    $latexGC .= "\\\\";
                    $latexGC .= "\\$hline\n";

                        $rowspan["label"] = decrementRowspan($rowspan["label"]);
                        $rowspan["moment"] = decrementRowspan($rowspan["moment"]);
                }

                // End table
                $latexGC .= "\\end{tabular}\n";
                $latexGC .= "\\end{center}\n";

                return $latexGC;
            }
//


    // nav details
    $navid = $_GET["id"];
    if($navid == 0) {
        $latexfile = fopen("$kTemplateFilename.tex", "w") or die("Cannot write file $kTemplateFilename.tex");

        $row = latexRowFirst(NULL);
        $noFuel = new FuelRequirements();

        // Set no-arm to stations we want (not all, only pick some of each)
        $gcData->front->arm = $kNoArm;
        $gcData->rears[0]->arm = $kNoArm;
        $gcData->luggages[0]->arm = $kNoArm;
        $gcData->fuelUnusables[0]->arm = $kNoArm;
        $gcData->fuelUnusables[1]->arm = $kNoArm;
        $gcData->fuelQuantities[0]->arm = $kNoArm;
        $gcData->fuelQuantities[1]->arm = $kNoArm;

        $gcTable = computeGC($gcData, $noFuel);

        fwrite(
            $latexfile,

            latexDocumentBegin()
            . latexIntroTable($plane)
            . latexNavPlanTableHead()
            . $row->latexcontent
            . latexNavPlanTableFill(0, $row->inc, $maxRow)
            . latexNavPlanTableFoot()
            . latex2ndPageOpen()
            . latexReminders()
            . latexFuel(new FuelRequirements(), new FuelRequirements())
            . latexFuelTanks(NULL, new FuelRequirements())
            . latex2ndPageChangeColumn()  // change to 2nd column
            //. latexTxWind()  // Disabled: not useful during flight and not enough space on paper
            . latexGc($gcTable, $gcData->massUnit, $gcData->armUnit, $gcData->momentUnit)  // TODO
            . latexEnd()
        );

        fclose($latexfile);
        // download it??? or link on index?
        $page->htmlHelper->headerLocation();
    }

    $tot = $page->bobbyTable->getCount($kTable, $navid);
    if($tot == 0) {
        $page->htmlHelper->headerLocation();
    }

    $filename = getNavFilename($navid);
    $nav = $page->bobbyTable->selectId($kTable, $navid);

    $nav->bind_result(
        $navid,
        $name,
        $plane->sqlID,
        $variation,
        $gcData->front->mass,
        $gcData->rears[0]->mass,
        $gcData->rears[1]->mass,
        $gcData->luggages[0]->mass,
        $gcData->luggages[1]->mass,
        $gcData->luggages[2]->mass,
        $gcData->luggages[3]->mass,
        $comment,
    );

    $nav->fetch();
    $nav->close();
//
    // gohome and make title
    $bodyTitle = $page->bodyBuilder->goHome("..");
    $bodyTitle .= $page->htmlHelper->setTitle("Nav: $name");// before HotBooty
    $page->htmlHelper->hotBooty();

// heads done at end to use $latexFull

// Fuel data
$theoricFuel = new FuelRequirements();

if($plane->sqlID > 0) {
    // plane details
    $theplane = $page->bobbyTable->selectId("aircrafts", $plane->sqlID);
    $theplane->bind_result(
        $plane->sqlID,
        $plane->type,
        $plane->identification,
        $plane->speedPlanning,
        $plane->speedClimb,

        $gcData->massUnit,
        $gcData->armUnit,
        $gcData->momentUnit,

        $gcData->dryEmptyTimestamp,
        $gcData->dryEmpty->mass,
        $gcData->dryEmpty->moment,

        $gcData->maxTOW,
        $gcData->maxLdgW,

        $gcData->gcBoundaries->min,
        $gcData->gcBoundaries->max,

        $gcData->front->arm,
        $gcData->front->maxMass,

        $gcData->rears[0]->arm,
        $gcData->rears[0]->maxMass,
        $gcData->rears[1]->arm,
        $gcData->rears[1]->maxMass,

        $gcData->luggages[0]->arm,
        $gcData->luggages[0]->maxMass,
        $gcData->luggages[1]->arm,
        $gcData->luggages[1]->maxMass,
        $gcData->luggages[2]->arm,
        $gcData->luggages[2]->maxMass,
        $gcData->luggages[3]->arm,
        $gcData->luggages[3]->maxMass,

        $gcData->luggageTotalMass->maxMass,

        $gcData->fuelUnusables[0]->arm,
        $theoricFuel->tanks[0]->totalCapacity,
        $theoricFuel->tanks[0]->unusable,
        $theoricFuel->tanks[0]->allOrNothing,

        $gcData->fuelUnusables[1]->arm,
        $theoricFuel->tanks[1]->totalCapacity,
        $theoricFuel->tanks[1]->unusable,
        $theoricFuel->tanks[1]->allOrNothing,

        $gcData->fuelUnusables[2]->arm,
        $theoricFuel->tanks[2]->totalCapacity,
        $theoricFuel->tanks[2]->unusable,
        $theoricFuel->tanks[2]->allOrNothing,

        $gcData->fuelUnusables[3]->arm,
        $theoricFuel->tanks[3]->totalCapacity,
        $theoricFuel->tanks[3]->unusable,
        $theoricFuel->tanks[3]->allOrNothing,

        $theoricFuel->consumption,
        $theoricFuel->unit,
        $theoricFuel->type
    );
    $theplane->fetch();
    $theplane->close();

    if($gcData->massUnit   == "") {$gcData->massUnit   = "?";}
    if($gcData->armUnit    == "") {$gcData->armUnit    = "?";}
    if($gcData->momentUnit == "") {$gcData->momentUnit = "?";}
    if($theoricFuel->unit == "") {$theoricFuel->unit = "?";}
    if($theoricFuel->type == "") {$theoricFuel->type = "?";}

    function raiseErrorIfArmlessHasMass($gcField, $errorText) {
        global $kArmless;
        global $page;
        if($gcField->arm == $kArmless && $gcField->mass > 0) {
            $page->logger->error("Armless has mass: $errorText");
        }
    }

    raiseErrorIfArmlessHasMass($gcData->rears[0], "rears[0]");
    raiseErrorIfArmlessHasMass($gcData->rears[1], "rears[1]");
    raiseErrorIfArmlessHasMass($gcData->luggages[0], "luggages[0]");
    raiseErrorIfArmlessHasMass($gcData->luggages[1], "luggages[1]");
    raiseErrorIfArmlessHasMass($gcData->luggages[2], "luggages[2]");
    raiseErrorIfArmlessHasMass($gcData->luggages[3], "luggages[3]");

    // apply mass unit
    $gcData->propagateMassUnit();
    $gcData->propagateFuelData();
    $theoricFuel->propagateFuelData();
}

    // init some vars
    $latexcontent = "";

    $rows = 0;

    $theoricTripTime = 0;
    $theoricAlternateTime = 0;

    $realTripTime = 0;
    $realAlternateTime = 0;

    $tripDistance = 0;
    $alternateDistance = 0;

    $oldWindTC = 0;
    $oldWindSpeed = 0;
    $hasWind = false;

    $destination = "";
    $destinationDistance = 0;
    $theoricDestinationTime = 0;
    $realDestinationTime = 0;

    $wpNum = -1;
    $oldWP = $wpNum;

    $TH = 0;
    $magneticHeading = 0;
    $groundSpeed = 0;
    $realEET = 0;

    $roundTrip = false;
    $departurePlus5 = false;

    $warning = "";

$htmlRows = "";
$latexRows = "";
$page->butler->crossCheckDisable();  // we will build the rows but table is not open yet
$wp = $page->bobbyTable->queryManage("SELECT * FROM `{$page->bobbyTable->dbName}`.`NavWaypoints` WHERE `NavID` = $navid ORDER BY `WPnum` ASC");
while($wpObj = $wp->fetch_object()) {
    // run
        // get data and compute
        $id = $wpObj->id;
        $oldWP = $wpNum;
        $wpNum = $wpObj->WPnum + 0;

        if(!$roundTrip && ($wpNum == $kWaypoints->wayBack->start || $wpNum == $kWaypoints->wayBack->last)) {
            // flag to know if round-trip
            $roundTrip = true;
        }

        $plus = 0;
        if($kWaypoints->isStartLast($wpNum)) {
            $plus = 5;
            if($kWaypoints->isLast($wpNum) && !$departurePlus5) {
                $plus = 10;
            }
        }

        $waypoint = $wpObj->waypoint;
        $trueCourse = $wpObj->TC + 0;
        $magneticCourse = 0;
        if($trueCourse > 0) {
            $magneticCourse = (360 + $trueCourse - $variation) % 360;
        }
        $distance = $wpObj->distance + 0;
        $altitude = $wpObj->altitude + 0;
        $windTC = $wpObj->windTC + 0;
        $windSpeed = $wpObj->windSpeed + 0;
        if($windTC > 0 && $windSpeed > 0) {
            if($wpNum == $kWaypoints->wayOut->start || $hasWind) {
                $hasWind = true;
                $oldWindTC = $windTC;
                $oldWindSpeed = $windSpeed;
            }

        } elseif($hasWind && $windTC == 0) {
            $windTC = $oldWindTC;
            $windSpeed = $oldWindSpeed;
        }

        $notes = $wpObj->notes;
        $climbing = $wpObj->climbing + 0;

        // sum distance
        if($wpNum < $kWaypoints->alternate->limit) {
            $tripDistance += $distance;
        } else {
            $alternateDistance += $distance;
        }

        if($hasWind) {
            if($trueCourse == 0) {
                $TH = 0;
                $magneticHeading = 0;

            } else {
                // wind angles
                $alpha1 = (360 + $trueCourse - $windTC) % 360;
                $sign = 1;

                if($alpha1 > 180) {
                    $alpha1 = (360 + $windTC - $trueCourse) % 360;
                    $sign = -1;
                }

                $alpha2 = asind(1.0 * $windSpeed / $speed * sind($alpha1));
                $alpha3 = 180 - $alpha1 - $alpha2;
                $TH = round($trueCourse + $sign * $alpha2) % 360;
                $magneticHeading = (360 + $TH - $variation) % 360;
            }
        }

        $theoricEET = 0;
        if($plane->speedPlanning > 0) {
            // time computation
            $speed = ($climbing && $plane->speedClimb > 0) ? $plane->speedClimb : $plane->speedPlanning;

            $theoricEET = ComputeEET($distance, $speed);

            if($wpNum < $kWaypoints->alternate->limit) {
                $theoricTripTime += $theoricEET;

            } else {
                $theoricAlternateTime += $theoricEET;
            }

            if($wpNum < $kWaypoints->alternate->limit) {
                $theoricTripTime += $plus;
            } else {
                $theoricAlternateTime += $plus;
            }

            if($hasWind) {
                if($trueCourse == 0) {
                    // No heading indication, take worst-case
                    $groundSpeed = $speed - $windSpeed;

                } else {
                    // speed and time with wind
                    if($windTC == $trueCourse) {
                        $groundSpeed = $speed + $windSpeed;

                    } elseif($alpha1 == 180) {
                        $groundSpeed = $speed - $windSpeed;

                    } else {
                        $groundSpeed = round($speed * sind($alpha3) / sind($alpha1));
                    }
                }

                $realEET = ComputeEET($distance, $groundSpeed);

                if($wpNum < $kWaypoints->alternate->limit) {
                    $realTripTime += $realEET;

                } else {
                    $realAlternateTime += $realEET;
                }

                if($wpNum < $kWaypoints->alternate->limit) {
                    $realTripTime += $plus;
                } else {
                    $realAlternateTime += $plus;
                }
            }
        }

        if($wpNum == $kWaypoints->wayOut->last) {
            $destination = $waypoint;
            $destinationDistance = $tripDistance;
            $theoricDestinationTime = $theoricTripTime;
            $realDestinationTime = $realTripTime;
        }
    //
        // Prepare args
            // init values
            $rowArgs = new stdClass();
            $rowArgs->id = $id;
            $rowArgs->wpNum = $wpNum;
            $rowArgs->oldWP = $oldWP;
            $rowArgs->waypoint = $waypoint;
            $rowArgs->destination = $destination;
            $rowArgs->notes = $notes;
            $rowArgs->trueCourse = $trueCourse > 0 ? sprintf("%03d", $trueCourse) : "";
            $rowArgs->magneticCourse = headingText($magneticCourse, $wpNum);
            $rowArgs->altitude = $altitude;
            $rowArgs->distance = $distance;
            $rowArgs->climbing = $climbing;
            $rowArgs->theoricEET = $theoricEET;
            $rowArgs->hasWind = $hasWind;
            $rowArgs->windTC = sprintf("%03d", $windTC);
            $rowArgs->windSpeed = $windSpeed;
            $rowArgs->magneticHeading = headingText($magneticHeading, $wpNum);
            $rowArgs->groundSpeed = $groundSpeed;
            $rowArgs->realEET = $realEET;
            $rowArgs->destinationDistance = $destinationDistance;
            $rowArgs->tripDistance = $tripDistance;
            $rowArgs->alternateDistance = $alternateDistance;
            $rowArgs->theoricDestinationTime = $theoricDestinationTime;
            $rowArgs->theoricTripTime = $theoricTripTime;
            $rowArgs->theoricAlternateTime = $theoricAlternateTime;
            $rowArgs->realTripTime = $realTripTime;
            $rowArgs->realDestinationTime = $realDestinationTime;
            $rowArgs->realAlternateTime = $realAlternateTime;
            $rowArgs->isAdmin = $isAdmin;
        //
            // Compute more
                // +5
                $rowArgs->plus5 = "";
                if($plus > 0) {
                    $rowArgs->plus5 = $plus == 10 ? $kStrings["Plus10"] : $kStrings["Plus5"];
                }

                if($kWaypoints->isStart($wpNum)) {
                    $departurePlus5 = true;
                } elseif($kWaypoints->isLast($wpNum)) {
                    $departurePlus5 = false;  // reset flag
                }

    // display to page
    $htmlRows .= htmlRowAny($rowArgs);

    // LaTeX
    $row = latexRowAny($rowArgs);

    $latexRows .= $row->latexcontent;

    $rows += $row->inc;
    if($rows > $maxRow) {
        $warning = "<div class=\"warning\">Number of rows is large and table will span on more than one A4.</div>\n";
    }
}
$wp->close();
$page->butler->crossCheckEnable();
// From here theoricTripTime and theoricAlternateTime are ready


    // Fuel
    // compute
    $realFuel = $theoricFuel->deepcopy();

    $theoricFuel->setTripAndAlternateTimes($theoricTripTime, $theoricAlternateTime);
    $realFuel->setTripAndAlternateTimes($realTripTime, $realAlternateTime);

    // Fill tanks
    $theoricFuel->fillTanks();

    $finalFuel = $theoricFuel;
    if($realFuel->fillTanks()) {
        $finalFuel = $realFuel;
    }

    // feed finalFuel to gcData and store quantities for GC computation
    $gcData->storeFuelMass($finalFuel);

//
    // Compute GC MTOW (also takes care of mass conversion if required)
    $gcData->computeZeroFuelData();
    $gcData->computeTakeOffData();

    $gcTable = computeGC($gcData, $finalFuel);

    $tanksTable = computeFuelTanks($gcData, $finalFuel);
//
    // HTML
    $body .= htmlIntroTable($name, $plane, $variation, $gcData);
    $body .= htmlNavPlanTableHead($isAdmin);
    $body .= $htmlRows;
    $body .= htmlNavPlanTableFoot($navid, $warning, $isAdmin);
    $body .= htmlReminders();
    $body .= htmlThWind();
    $body .= htmlFuel($theoricFuel, $realFuel);
    $body .= htmlFuelTanks($tanksTable);
    $body .= htmlGC($gcTable, $gcData->massUnit, $gcData->armUnit, $gcData->momentUnit);
    $body .= htmlEnd();
//
    // LaTeX
    $latexcontent .= $latexRows;
        // finish latex content 1st page with empty rows
        $latexcontent .= latexNavPlanTableFill($wpNum, $rows, $maxRow - (int)$roundTrip);  // round-trip uses more rows

    // Write LaTeX file
    $latexFull = latexDocumentBegin($navid);
    $latexFull .= latexIntroTable($plane, $name, $variation);
    $latexFull .= latexNavPlanTableHead();
    $latexFull .= $latexcontent;
    $latexFull .= latexNavPlanTableFoot();
    $latexFull .= latex2ndPageOpen();
    $latexFull .= latexReminders();
    $latexFull .= latexFuel($theoricFuel, $realFuel);
    $latexFull .= latexFuelTanks($tanksTable);
    $latexFull .= latex2ndPageChangeColumn();
    //$latexFull .= latexTxWind()  // Disabled: not useful during flight and not enough space on paper
    $latexFull .= latexGc($gcTable, $gcData->massUnit, $gcData->armUnit, $gcData->momentUnit);
    $latexFull .= latexEnd();  // end of LaTeX

    $latexfile = fopen("$filename.tex", "w") or die(" Cannot write file $filename.tex");
    fwrite($latexfile, $latexFull);
    fclose($latexfile);
//
    // Body heads (not before so we can use $latexFull)
    $bodyHeads = "<div class=\"wide\">\n";
        $bodyHeads .= "<div class=\"lhead\">\n";
        if($isAdmin) {
            $bodyHeads .= "<form target=\"_blank\" action=\"https://latex.informatik.uni-halle.de/latex-online/latex.php\" method=\"POST\">\n";
            $bodyHeads .= "<textarea name=\"quellcode\" style=\"display: none;\">$latexFull</textArea>\n";
        }
        $bodyHeads .= $page->bodyBuilder->anchor("$filename.tex", $page->bodyBuilder->strLaTeX, "$name LaTeX");
        if($isAdmin) {
            $bodyHeads .= "-\n";
            $bodyHeads .= "<input type=\"submit\" name=\"compile\" value=\"online\" style=\"background: none; border: none; padding: 0; color: #aaf; cursor: pointer;\">\n";
        }

        if(file_exists("$filename.pdf")) {
            $bodyHeads .= "-\n";
            $bodyHeads .= $page->bodyBuilder->anchor("$filename.pdf", "PDF", "$name PDF");
        }

        if($isAdmin) {
            $bodyHeads .= "</form>\n";
        }

        $bodyHeads .= "</div>\n";
    $bodyHeads .= "<div class=\"chead\">\n";
    $bodyHeads .= "</div>\n";
        $bodyHeads .= "<div class=\"rhead\">\n";
        if($isAdmin) {
            $bodyHeads .= $page->bodyBuilder->anchor("insert.php?id=$navid", "edit", "edit $name");
            $bodyHeads .= "<br>\n";
            $bodyHeads .= $page->bodyBuilder->anchor("insert.php", "new");
            $bodyHeads .= "<br>\n";
            $bodyHeads .= $page->bodyBuilder->anchor("display.php?dup=$navid", "duplicate");
            $bodyHeads .= "<br>\n";
            $bodyHeads .= $page->bodyBuilder->anchor("delete.php?id=$navid", "delete all WP");
        }
        $bodyHeads .= "</div>\n";
    $bodyHeads .= "</div>\n";

echo $bodyTitle . $bodyHeads . $body;
?>
