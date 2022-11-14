<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";

require_once("common.php");


$docVersion = "2024-11-22";

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
                    if($this->arm == $kArmless && $this->mass > 0) {
                        // Arm not available BUT mass provided: error
                        global $page;
                        $page->logger->fatal("Cannot set mass for armless: " . (string)$this);  // never reached
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
             */
            class GcData {
                public $massUnit = "?";  // Mass unit
                public $armUnit = "?";  // Arm unit
                public $momentUnit = "?";  // Moment unit

                public $maxTOW = 0;  // Maximum Take-Off Weight
                public $maxLdgW = 0;  // Maximum Landing Weight

                public $gcBoundaries = NULL;  // boundaries of GC

                public $dryEmpty = NULL;  // data of dry+empty plane

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
                    $tank->quantity = $tank->totalCapacity;
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
                $tank->quantity = $tank->totalCapacity;
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
                    $attrQ["style"] = "background-color: red;";
                    $htmlRow = $page->butler->cell("OVERFLOW: {$this->overflow}", array("style" => "background-color: red;"));
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
                global $page;

                if(!$this->isValid()) {
                    return "&&";
                }

                $minutes = $this->getEntryMinutes($fuelEntry);

                $latexRow = "\\multicolumn{2}{c}{\\DarkGray}\n";

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

                $latexRow = "\\multicolumn{2}{c}{\\DarkGray}\n";

                if($this->overflow > 0) {
                    $latexRow = "\\multicolumn{2}{c}{\\RedCell OVERFLOW: {$this->overflow}}\n";
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
        $kStrings["latexCopyright"] = "{\\footnotesize\\textcopyright}";
        $kStrings["htmlCopyright"] = "&copy;";

        // TOD
        $kStrings["TopOfDescent"] = "TOD";
        $kStrings["TOD1"] = "120kts (2NM/min) 500 ft/min (children -12: 300)<br/>3deg makes descent rate = GS x5";
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

        // TH with wind
        $kStrings["THwind"] = "TH with wind";

        // Weight and balance
        $kStrings["MassAndBalance"] = "Mass and balance";
        $kStrings["Mass"] = "Mass";
        $kStrings["Arm"] = "Arm";
        $kStrings["Moment"] = "Moment";
        $kStrings["Empty"] = "Empty";
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
        global $page;
        global $kTable;
        global $kDefaultLuggageMass;

        if(!isset($_GET["dup"]) || !$isAdmin) {
            return;
        }

        $dupID = $_GET["dup"];

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
            if($kWaypoints->isStartLast($wpNum)) {
                return "VAC";
            }

            return "V";
        }
    //
        // html2latex
        function html2latex($string) {
            $back = preg_replace("/&([aeiouy])(acute|grave|circ|uml);/", "$1", $string);
            $back = preg_replace("/#/", "\\#", $back);
            return $back;
        }
    //
        // LaTeX head: usepackages
        function LaTeXusePackages() {
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
        function LaTeXdefinition($docVersion) {
            $latexhead = "";
            $latexhead .= "%\n";
            $latexhead .= "\\newcommand*{\\DocVersion}{ $docVersion}\n";
                $latexhead .= "% Headers {{{\n";
                $latexhead .= "\\documentclass[12pt,a4paper]{article}\n";
                $latexhead .= "%\n";
                    $latexhead .= "% Document variables {{{\n";
                    $latexhead .= "\\newcommand*{\\Gael}{Ga\\\"el Induni}\n";
                    $latexhead .= "\\newcommand*{\\ThisAuthors}{\\Gael}\n";
                    $latexhead .= "\\newcommand*{\\ThisTitle}{Navigation plan}\n";
                    $latexhead .= "\\newcommand*{\\ThisTitleSHORT}{\\ThisTitle}\n";
                    $latexhead .= "% End of document variables }}}\n";

                $latexhead .= LaTeXusePackages();

                    $latexhead .= "% Document size {{{\n";
                    $latexhead .= "\\setlength{\\topmargin}{-8mm}\n";
                    $latexhead .= "\\setlength{\\textheight}{180mm}\n";
                    $latexhead .= "\\setlength{\\hoffset}{-28mm}\n";
                    $latexhead .= "\\setlength{\\textwidth}{280mm}\n";
                    $latexhead .= "\\setlength{\\evensidemargin}{9mm}\n";
                    $latexhead .= "\\setlength{\\parskip}{0.5ex}\n";
                    $latexhead .= "% End of document size }}}\n";
                //
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
                    $latexhead .= "\\newcommand*{\\DarkGray}{\\cellcolor[gray]{0.66}}\n";
                    $latexhead .= "\\newcommand*{\\Gray}{\\cellcolor[gray]{0.8}}\n";
                    $latexhead .= "\\newcommand*{\\RedCell}{\\cellcolor[rgb]{1,0,0}}\n";
                    $latexhead .= "% End of new commands }}}\n";
                $latexhead .= "% }}}\n";
            $latexhead .= "%\n";
            $latexhead .= "\\begin{document}\n";
            $latexhead .= "%\n";

            return $latexhead;
        }
    //
        // LaTeX head: first table
        function LaTeXheader1stTable($plane, $name, $variation=NULL) {
            if ($variation === NULL) {
                global $kDefaultVariation;
                $variation = $kDefaultVariation;
            }

            $longID = preg_replace("/-/", "---", $plane->identification);

            $latexhead = "";
            $latexhead .= "\\mbox{}\n";
            $latexhead .= "\\vspace{-16mm}\n";
            $latexhead .= "% Header {{{\n";
            $latexhead .= "\\begin{longtable}{|c|c|c||c|}\n";
                $latexhead .= "xxxxxxxxxxxxxxxxxxxxxxxxx xxxxxxxxxxxxxxxxxxxxxxxxx\n";
                $latexhead .= "& xxxxxxxxxxxxx\n";
                $latexhead .= "& 0000000000\n";
                $latexhead .= "&\\kill\n";
            //
                $latexhead .= "\\multirow{2}{*}{" . html2latex($name) . "}\n";
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
                $latexhead .= "\\\\\n";
            $latexhead .= "\n\\end{longtable}\n";
            $latexhead .= "% }}}\n";

            return $latexhead;
        }
    //
        // LaTeX head: header of 2nd table
        function LaTeXheader2ndTableHead() {
            global $kStrings;

            $latexhead = "";
            $latexhead .= "\\vspace{-8.7mm}\n";

            // Make rows a little larger from now on
            $latexhead .= "\\renewcommand*{\\arraystretch}{1.7}\n";

            $latexhead .= "% Nav plan {{{\n";
            $latexhead .= "\\begin{longtable}{%\n";
            $latexhead .= "    |c|l|%\n";
            $latexhead .= "    >{\\columncolor[gray]{0.75}}c|c|c|c|c|%\n";
            $latexhead .= "    |>{\\columncolor[gray]{0.88}}c|>{\\columncolor[gray]{0.88}}c|c|c|c|%\n";
            $latexhead .= "    |c|c||l|%\n";
            $latexhead .= "}\n";
                $latexhead .= "% Template width {{{\n";
                $latexhead .= "i\n";
                $latexhead .= "& bla bla bla bla bla bla\n";
                $latexhead .= "& 00000\n";
                $latexhead .= "& 00000\n";
                $latexhead .= "&\n";
                $latexhead .= "& altitudeee\n";
                $latexhead .= "&\n";
                $latexhead .= "& 00000\n";
                $latexhead .= "&\n";
                $latexhead .= "& 00000\n";
                $latexhead .= "& 00000\n";
                $latexhead .= "&\n";
                $latexhead .= "& 0000\n";
                $latexhead .= "& 0000\n";
                $latexhead .= "& bla bla bla bla bla bla bla\n";
                $latexhead .= "\\kill\n";
                $latexhead .= "% }}}\n";
            $latexhead .= "% Head {{{\n";
            $latexhead .= "\\hline\n";
                $latexhead .= "\\multicolumn{2}{|c|}{\\multirow{2}{*}{{$kStrings["Waypoint"]}}} &\n";
                $latexhead .= $kStrings["TrueCourse"] . " &\n";
                $latexhead .= $kStrings["MagneticCourse"] . " &\n";
                $latexhead .= $kStrings["Dist"] . " &\n";
                $latexhead .= $kStrings["Altitude"] . " &\n";
                $latexhead .= $kStrings["EstimatedElapsedTime"] . " &\n";
                $latexhead .= "\\multicolumn{2}{c|}{\\cellcolor[gray]{0.88}{$kStrings["Wind"]}} &\n";
                $latexhead .= $kStrings["MagneticHeading"] . " &\n";
                $latexhead .= $kStrings["GroundSpeed"] . " &\n";
                $latexhead .= $kStrings["EstimatedElapsedTime"] . " &\n";
                $latexhead .= "\\multirow{2}{*}{{$kStrings["EstimatedTimeOver"]}} &\n";
                $latexhead .= "\\multirow{2}{*}{{$kStrings["ActualTimeOver"]}} &\n";
                $latexhead .= "\\multirow{2}{*}{{$kStrings["Notes"]}}\n";
                $latexhead .= "\\\\\n";
                $latexhead .= "\\hhline{~~~~~~~--~~~~~~}\n";
            //
                $latexhead .= "\\multicolumn{2}{|c|}{} &\n";
                $latexhead .= $kStrings["latexUnitDeg"] . " &\n";
                $latexhead .= $kStrings["latexUnitDeg"] . " &\n";
                $latexhead .= $kStrings["unitNM"] . " &\n";
                $latexhead .= $kStrings["unitFt"] . " &\n";
                $latexhead .= $kStrings["unitMin"] . " &\n";
                $latexhead .= $kStrings["latexUnitDeg"] . " &\n";
                $latexhead .= $kStrings["unitKts"] . " &\n";
                $latexhead .= $kStrings["latexUnitDeg"] . " &\n";
                $latexhead .= $kStrings["unitKts"] . " &\n";
                $latexhead .= $kStrings["unitMin"] . " &\n";
                $latexhead .= "&\n";
                $latexhead .= "&\n";
                $latexhead .= "\\\\\n";
                $latexhead .= "\\hline\n";
            $latexhead .= "% }}}\n";
            $latexhead .= "\\endhead\n";
            $latexhead .= "\\hline\\endfoot\n";

            return $latexhead;
        }
    //
        // LaTeX head
        function LaTeXheader($docVersion, $plane, $name="", $variation=NULL) {
            $latexhead = LaTeXdefinition($docVersion);
            $latexhead .= LaTeXheader1stTable($plane, $name, $variation);
            $latexhead .= LaTeXheader2ndTableHead();
            return $latexhead;
        }
    //
        // LaTeX end of header
        function LaTeXheaderEnd() {
            $latexhead = "";
            $latexhead .= "\\end{longtable}\n";
            $latexhead .= "\\renewcommand*{\\arraystretch}{1.0}\n";
            $latexhead .= "% }}}\n";
            $latexhead .= "%\n";
            return $latexhead;
        }
    //
        // LaTeX 2nd page left column
        function LaTeX2left() {
            // beginning of second page
            global $kStrings;

            $contents = "";

            $contents .= "\\clearpage\n";
            $contents .= "\\fancyhf{}\n";
            $contents .= "\\renewcommand{\\headrulewidth}{0pt}\n";
            $contents .= "\\noindent\n";
            $contents .= "\\begin{minipage}{0.49\\textwidth}\n";

            $contents .= "{\\Large\n";
            $contents .= "% {$kStrings["TopOfDescent"]} {{{\n";
            $contents .= "\\textbf{{$kStrings["TopOfDescent"]}:}\n";
            $contents .= "\\begin{itemize}\n";
            $tod1 = preg_replace("/<br\/>/", "\\\\\\", $kStrings["TOD1"]);
            $contents .= "    \\item {$tod1}\n";
            $contents .= "    \\item {$kStrings["TOD2"]}\n";
            $contents .= "    \\item {$kStrings["TOD3"]}\n";
            $contents .= "    \\item ({$kStrings["TOD4"]})\n";
            $contents .= "\\end{itemize}\n";
            $contents .= "% }}}\n";
            $contents .= "}  % Large\n";

            return $contents;
        }
    //
        // LaTeX 2nd page right column
        function LaTeX2right() {
            // change to 2nd column
            //global $kStrings;

            $contents = "\\vspace*{6mm}\n";
            $contents .= "\\end{minipage}\n";

            $contents .= "\\begin{minipage}{0.50\\textwidth}\n";
            $contents .= "\\vspace{-7mm}\n";

            // Disable this: not useful during flight and not enough space on paper
            //$contents .= "% {$kStrings["THwind"]} {{{\n";
            //$contents .= "{\n";
            //$contents .= $kStrings["THwind"] . ":\n";
            //$contents .= "\\begin{enumerate}\n";
            //$contents .= "\\item $\\alpha_1 = (360 + \\textrm{TC} - \\textrm{WH}) \\% 360^{\\circ}$ and $\\textrm{sign} = 1$\n";
            //$contents .= "\\item if $\\alpha_1 > 180: \\alpha_1 = (360 + \\textrm{WH} - \\textrm{TC}) \\% 360^{\\circ}$ and $\\textrm{sign} = -1$\n";
            //$contents .= "\\item $\\alpha_2 = \\arcsin \\left( \\frac{\\textrm{WS}}{\\textrm{TS}} \\cdot \\sin \\alpha_1 \\right)$\n";
            //$contents .= "\\item $\\alpha_3 = 180 - (\\alpha_1 + \\alpha_2)$\n";
            //$contents .= "\\item $\\textrm{TH} = \\textrm{TC} + \\textrm{sign} \\cdot \\alpha_2$\n";
            //$contents .= "\\item $\\textrm{GS} = \\frac{\\sin \\alpha_3}{\\sin \\alpha_1} \\cdot \\textrm{TS}$\n";
            //$contents .= "\\end{enumerate}\n";
            //$contents .= "}\n";


            $contents .= "% }}}\n";
            return $contents;
        }
    //
        // LaTeX end
        function LaTeXend() {
            // end of LaTeX
            global $kFuelTypes;
            global $kFuelUnits;
            global $kStrings;
            global $usgAvgas2lbs;

            $latexend = "";
            $latexend .= "\\vspace{-2mm}\n";
            $latexend .= "{\\small\n";
            $latexend .= "$1\\ \\textrm{{$kStrings["USG"]}} = {$kFuelUnits["USG"]}\\ l$\n";
            $latexend .= "\\hspace{17mm}\n";
            $latexend .= "$1\\ l\\ \\textrm{{$kStrings["Avgas"]}} = {$kFuelTypes["AVGAS"]}\\ kg$\n";
            $latexend .= "\\\\\n";
            $latexend .= "$1\\ \\textrm{{$kStrings["ImpG"]}} = {$kFuelUnits["ImpG"]}\\ l$\n";
            $latexend .= "\\hspace{17mm}\n";
            $latexend .= "$1\\ \\textrm{{$kStrings["USG"]} {$kStrings["Avgas"]}} = $usgAvgas2lbs$ lbs\n";
            $latexend .= "}  % small\n";
            $latexend .= "% }}}\n";
            $latexend .= "\\end{minipage}\n";
            $latexend .= "\\end{document}\n";
            return $latexend;
        }
    //
        /**
         * HTML header: first table
         *
         * @SuppressWarnings(PHPMD.CyclomaticComplexity)
         * @SuppressWarnings(PHPMD.NPathComplexity)
         */
        function htmlHeader1stTable($name, $plane, $variation, $gcData) {
            global $kStrings;

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
            $htmlHead .= "<div>\n";
            $htmlHead .= "<b>Mass [kg]:</b>\n";
            $htmlHead .= "<ul>\n";

                // Front
                $htmlHead .= "<li>{$kStrings["Front"]}: {$gcData->front->mass}";

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

                    $htmlHead .= "#$i={$gcData->rears[$i]->mass}";
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

                    $htmlHead .= "#$i={$gcData->luggages[$i]->mass} kg";
                    if($gcData->luggages[$i]->isMassTooMuch()) {
                        $htmlHead .= " {$kStrings['htmlTooHeavy']}";
                    }
                    $htmlHead .= " - ";

                }
                $htmlHead = substr($htmlHead, 0, -3);  // remove trailing separator
                if($gcData->luggageTotalMass->mass > $gcData->luggageTotalMass->maxMass) {
                    $htmlHead .= " <span style=\"background-color: red;\">Luggages mass {$gcData->luggageTotalMass->mass} {$kStrings['TooHeavy']}, exceeds {$gcData->luggageTotalMass->maxMass}</span>";
                }
                $htmlHead .= "</li>\n";

            $htmlHead .= "</ul>\n";
            $htmlHead .= "</div>\n";

            $htmlHead .= "</div>\n";

            return $htmlHead;
        }
    //
        // HTML header: header of 2nd table
        function htmlHeader2ndTableHead($isAdmin) {
            global $page;
            global $kStrings;

            $htmlHead = "";
            $htmlHead .= "<div>\n";
            $htmlHead .= $page->butler->tableOpen();

                // HTML table header
                $htmlHead .= $page->butler->rowOpen();
                if($isAdmin) {
                    $htmlHead .= $page->butler->headerCell("", array("rowspan" => 2));
                }
                $htmlHead .= $page->butler->headerCell("{$kStrings["Waypoint"]}", array("rowspan" => 2));
                $htmlHead .= $page->butler->headerCell("{$kStrings["TrueCourse"]}", array("class" => "TC"));
                $htmlHead .= $page->butler->headerCell($kStrings["MagneticCourse"]);
                $htmlHead .= $page->butler->headerCell($kStrings["Dist"]);
                $htmlHead .= $page->butler->headerCell($kStrings["Altitude"]);
                $htmlHead .= $page->butler->headerCell($kStrings["EstimatedElapsedTime"]);
                $htmlHead .= $page->butler->headerCell("{$kStrings["Wind"]}", array("class" => "wind", "colspan" => 2));
                $htmlHead .= $page->butler->headerCell($kStrings["MagneticHeading"]);
                $htmlHead .= $page->butler->headerCell($kStrings["GroundSpeed"]);
                $htmlHead .= $page->butler->headerCell($kStrings["EstimatedElapsedTime"]);
                $htmlHead .= $page->butler->headerCell("{$kStrings["EstimatedTimeOver"]}", array("rowspan" => 2));
                $htmlHead .= $page->butler->headerCell("{$kStrings["ActualTimeOver"]}", array("rowspan" => 2));
                $htmlHead .= $page->butler->headerCell("{$kStrings["Notes"]}", array("rowspan" => 2));
                $htmlHead .= $page->butler->rowClose();
                $htmlHead .= $page->butler->rowOpen();
                $htmlHead .= $page->butler->headerCell("{$kStrings["htmlUnitDeg"]}", array("class" => "TC"));
                $htmlHead .= $page->butler->headerCell($kStrings["htmlUnitDeg"]);
                $htmlHead .= $page->butler->headerCell($kStrings["unitNM"]);
                $htmlHead .= $page->butler->headerCell($kStrings["unitFt"]);
                $htmlHead .= $page->butler->headerCell($kStrings["unitMin"]);
                $htmlHead .= $page->butler->headerCell("{$kStrings["htmlUnitDeg"]}", array("class" => "wind"));
                $htmlHead .= $page->butler->headerCell("{$kStrings["unitKts"]}", array("class" => "wind"));
                $htmlHead .= $page->butler->headerCell($kStrings["htmlUnitDeg"]);
                $htmlHead .= $page->butler->headerCell($kStrings["unitKts"]);
                $htmlHead .= $page->butler->headerCell($kStrings["unitMin"]);
                $htmlHead .= $page->butler->rowClose();

            return $htmlHead;
        }
    //
        // HTML header
        function htmlHeader($name, $plane, $variation, $gcData, $isAdmin) {
            $htmlHead = "";
            $htmlHead .= htmlHeader1stTable($name, $plane, $variation, $gcData);
            $htmlHead .= htmlHeader2ndTableHead($isAdmin);
            return $htmlHead;
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
            global $page;
            global $kStrings;

            $colspan = 14;
            if($isAdmin) {
                $colspan += 1;
            }

            $htmlRow = $page->butler->rowOpen(array("class" => "WP{$wpNum}"));
            $htmlRow .= $page->butler->cell("{$kStrings['Alternate']}", array("class" => "nav-alternate-title", "colspan" => $colspan));
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
            global $page;
            global $kWaypoints;
            global $kStrings;

            $climbing = $rowArgs->climbing ? $kStrings["htmlCopyright"] : "";
            $plus5 = $kWaypoints->isStartLast($rowArgs->wpNum) ? $kStrings["Plus5"] : "";

            // Prepare string

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
            $htmlRow .=$page->butler->cellOpen(array( "class" => "TC heading num"));
            if($rowArgs->trueCourse > 0) {
                $htmlRow .= sprintf("%03d", $rowArgs->trueCourse);
            }
            $htmlRow .= $page->butler->cellClose();

            // MC
            $htmlRow .= $page->butler->cell(headingText($rowArgs->magneticCourse, $rowArgs->wpNum), array("class" => "heading num"));

            // distance
            $htmlRow .= $page->butler->cell($rowArgs->distance, array("class" => "distance num"));

            // altitude
            $htmlRow .= $page->butler->cellOpen(array("class" => "altitude num"));
            if($rowArgs->altitude > 0) {
                $htmlRow .= "{$rowArgs->altitude}";
            }
            $htmlRow .= $page->butler->cellClose();

            // Theoric EET
            $htmlRow .= $page->butler->cell("{$climbing}{$rowArgs->theoricEET}{$plus5}", array("class" => "EET num"));

                // wind (if provided)
                $windHeading = "";
                $windSpeed = "";
                $magHeading = "";
                $groundSpeed = "";
                $realEET = "";

                if($rowArgs->hasWind) {
                    $windHeading = sprintf("%03d", $rowArgs->windTC);
                    $windSpeed = $rowArgs->windSpeed;
                    $magHeading = headingText($rowArgs->magneticHeading, $rowArgs->wpNum);
                    $groundSpeed = $rowArgs->groundSpeed;
                    $realEET = "{$climbing}{$rowArgs->realEET}{$plus5}";
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
            global $page;
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
        /**
         * LaTeX notes
         *
         * Returns:
         *     (string) translated HTML characters
         */
        function LaTeXnotes($notes) {
            return html2latex(htmlspecialchars_decode($notes, ENT_NOQUOTES));
        }
    //
        /**
         * LaTeX 1st row
         *
         * @SuppressWarnings(PHPMD.MissingImport)
         */
        function LaTeXfirstRow($rowArgs) {
            // Set data
            global $kWaypoints;

            if($rowArgs === NULL) {
                // Prepare default args
                $rowArgs = new stdClass();
                $rowArgs->wpNum = 0;
                $rowArgs->oldWP = -1;
                $rowArgs->waypoint = "";
                $rowArgs->destination = "";
                $rowArgs->notes = "";
                $rowArgs->trueCourse = 0;
                $rowArgs->magneticCourse = 0;
                $rowArgs->altitude = 0;
                $rowArgs->distance = 0;
                $rowArgs->climbing = false;
                $rowArgs->theoricEET = 0;
                $rowArgs->hasWind = false;
                $rowArgs->windTC = 0;
                $rowArgs->windSpeed = 0;
                $rowArgs->magneticHeading = 0;
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

            if($rowArgs->wpNum == $kWaypoints->wayBack->start) {
                $back->inc = -1;
                $back->latexcontent .= "\\hhline{===============}\n";
            }

            $destination = $rowArgs->destination;
            if($rowArgs->wpNum == $kWaypoints->wayOut->base) {
                $destination = $rowArgs->waypoint;
            }

            $back->latexcontent .= "& " . html2latex($destination) . " ";
            $back->latexcontent .= "&\\DarkGray\n";
            $back->latexcontent .= "&\\DarkGray\n";
            $back->latexcontent .= "&\\DarkGray\n";
            $back->latexcontent .= "&\\DarkGray\n";
            $back->latexcontent .= "&\\DarkGray\n";
            $back->latexcontent .= "&\n";
            $back->latexcontent .= "&\n";
            $back->latexcontent .= "&\\DarkGray\n";
            $back->latexcontent .= "&\\DarkGray\n";
            $back->latexcontent .= "&\\DarkGray\n";
            $back->latexcontent .= "&\\DarkGray\n";
            $back->latexcontent .= "&\n";
            $back->latexcontent .= "&";
            if($rowArgs->wpNum == $kWaypoints->wayOut->base && $rowArgs->notes != "") {
                $back->latexcontent .= " " . LaTeXnotes($rowArgs->notes) . " ";
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
        function LaTeXrow($rowArgs) {
            // Set data
            global $kWaypoints;
            global $kStrings;

            $climbing = $rowArgs->climbing ? $kStrings["latexCopyright"] : "";
            $plus5 = $kWaypoints->isStartLast($rowArgs->wpNum) ? $kStrings["Plus5"] : "";

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
                $newline = "\\hhline{===============}\n";
            }
            $latexcontent .= $newline;
            $latexcontent .= "& " . html2latex($rowArgs->waypoint);
            $latexcontent .= " & ";
            if($rowArgs->trueCourse > 0) {
                $latexcontent .= sprintf("%03d", $rowArgs->trueCourse);
            }
            $latexcontent .= " & {\\large " . headingText($rowArgs->magneticCourse, $rowArgs->wpNum) . "}";
            $latexcontent .= " & {\\large {$rowArgs->distance}}";
            $latexcontent .= " &";
            if($rowArgs->altitude > 0) {
                $latexcontent .= " {\\large {$rowArgs->altitude}}";
            }
            $latexcontent .= " & {$climbing}";
            $latexcontent .= " {\\large {$rowArgs->theoricEET}{$plus5}}";
            $latexcontent .= " &";

            // wind (if provided)
            $windHeading = "";
            $windSpeed = "";
            $magHeading = "";
            $groundSpeed = "";
            $realEET = "";

            if($rowArgs->hasWind) {
                $windHeading = sprintf("%03d", $rowArgs->windTC);
                $windSpeed = $rowArgs->windSpeed;
                $magHeading = "{\\large " . headingText($rowArgs->magneticHeading, $rowArgs->wpNum) . "}";
                $groundSpeed = $rowArgs->groundSpeed;
                $realEET = "{$climbing} {\\large {$rowArgs->realEET}{$plus5}}";
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

            $latexcontent .= " & " . LaTeXnotes($rowArgs->notes);
            $latexcontent .= "\\\\";

            return $latexcontent;
        }
    //
        // LaTeX row summary
        function LaTeXrowSummary($rowArgs) {
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

            $latexcontent .= "\\hhline{----=-=----=---}\n";
            $latexcontent .= "\\DarkGray ";
            $latexcontent .= "& \\DarkGray ";
            $latexcontent .= "& \\DarkGray ";
            $latexcontent .= "& \\DarkGray ";
            $latexcontent .= "& {$distance} ";
            $latexcontent .= "& \\DarkGray ";
            $latexcontent .= "& {$theoricEeTime} ";
            $latexcontent .= "& \\DarkGray ";
            $latexcontent .= "& \\DarkGray ";
            $latexcontent .= "& \\DarkGray ";
            $latexcontent .= "& \\DarkGray ";
            $latexcontent .= "& {$realEeTime} ";
            $latexcontent .= "& \\DarkGray ";
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
        function LaTeXrowAny($rowArgs) {
            global $kWaypoints;

            $back = new stdClass();
            $back->inc = 1;
            $back->latexcontent = "";

            if($rowArgs->wpNum == $kWaypoints->wayOut->base) {
                $firstRow = LaTeXfirstRow($rowArgs);
                $back->latexcontent .= $firstRow->latexcontent;
                $back->inc += $firstRow->inc;
            }

            if($rowArgs->wpNum > $kWaypoints->wayOut->base) {
                $back->latexcontent .= LaTeXrow($rowArgs);
            }

            if($kWaypoints->isLast($rowArgs->wpNum)) {
                $back->latexcontent .= LaTeXrowSummary($rowArgs);
                $back->inc += 1;
            }

            return $back;
        }
    //
        // LaTeX finish 1st page
        function LaTeXfinish1($wpNum, $rows, $maxRow) {
            $latexcontent = "";
            global $kWaypoints;
            if($wpNum == $kWaypoints->wayOut->base) {
                $latexcontent .= "\\hline\n";
            } elseif($wpNum == $kWaypoints->wayOut->last) {
                $latexcontent .= "\\hhline{~-~~~~~~~~~~--~}\n";
            }
            while($rows < $maxRow - 1) {
                $rows++;
                $latexcontent .= "&&&&&&&&&&&&&&\\\\\\hline\n";
            }
            return $latexcontent;
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
                    "{$kStrings["FuelConsumption"]} "
                    . ($theoricFuel->consumption > 0 ? "{$theoricFuel->consumption}" : "?")
                    . " {$theoricFuel->unit}/h\n",
                    array("colspan" => 4, "style" => "background-color: white;")
                );
                $htmlFuel .= $page->butler->rowClose();

                $htmlFuel .= $page->butler->rowOpen();
                $htmlFuel .= $page->butler->headerCell("{$kStrings["NoWind"]}", array("colspan" => 2));
                $htmlFuel .= $page->butler->headerCell("{$kStrings["Wind"]}", array("colspan" => 2));
                $htmlFuel .= $page->butler->rowClose();
                $htmlFuel .= $page->butler->rowOpen();
                $htmlFuel .= $page->butler->headerCell($kStrings["time"]);
                $htmlFuel .= $page->butler->headerCell("{$kStrings["fuel"]} [{$theoricFuel->unit}]");
                $htmlFuel .= $page->butler->headerCell($kStrings["time"]);
                $htmlFuel .= $page->butler->headerCell("{$kStrings["fuel"]} [{$theoricFuel->unit}]");
                $htmlFuel .= $page->butler->rowClose();
            //
                // trip
                $htmlFuel .= $page->butler->rowOpen();
                $htmlFuel .= $page->butler->cell("{$kStrings["Trip"]}:");
                $htmlFuel .= $theoricFuel->htmlRow(FuelEntry::Trip);
                $htmlFuel .= $realFuel->htmlRow(FuelEntry::Trip);
                $htmlFuel .= $page->butler->rowClose();
            //
                // alternate
                $htmlFuel .= $page->butler->rowOpen();
                $htmlFuel .= $page->butler->cell("{$kStrings["Alternate"]}:");
                $htmlFuel .= $theoricFuel->htmlRow(FuelEntry::Alternate);
                $htmlFuel .= $realFuel->htmlRow(FuelEntry::Alternate);
                $htmlFuel .= $page->butler->rowClose();
            //
                // reserve
                $htmlFuel .= $page->butler->rowOpen();
                $htmlFuel .= $page->butler->cell("{$kStrings["Reserve"]}:");
                $htmlFuel .= $theoricFuel->htmlRow(FuelEntry::Reserve);
                $htmlFuel .= $realFuel->htmlRow(FuelEntry::Reserve);
                $htmlFuel .= $page->butler->rowClose();
            //
                // unusable
                $htmlFuel .= $page->butler->rowOpen();
                $htmlFuel .= $page->butler->cell("{$kStrings["Unusable"]}:");
                $htmlFuel .= $theoricFuel->htmlRow(FuelEntry::Unusable);
                $htmlFuel .= $realFuel->htmlRow(FuelEntry::Unusable);
                $htmlFuel .= $page->butler->rowClose();
            //
                // minimum
                $htmlFuel .= $page->butler->rowOpen();
                $htmlFuel .= $page->butler->cell("{$kStrings["MinFuel"]}:");
                $htmlFuel .= $theoricFuel->htmlRow(FuelEntry::Minimum);
                $htmlFuel .= $realFuel->htmlRow(FuelEntry::Minimum);
                $htmlFuel .= $page->butler->rowClose();
            //
                // extra
                $htmlFuel .= $page->butler->rowOpen();
                $htmlFuel .= $page->butler->cell("{$kStrings["ExtraPlus"]}" . ($theoricFuel->extraPercent * 100) . "%:");
                $htmlFuel .= $theoricFuel->htmlRow(FuelEntry::Extra);
                $htmlFuel .= $realFuel->htmlRow(FuelEntry::Extra);
                $htmlFuel .= $page->butler->rowClose();
            //
                // ramp
                $htmlFuel .= $page->butler->rowOpen();
                $htmlFuel .= $page->butler->cell("{$kStrings["RampFuel"]}:");
                $htmlFuel .= $theoricFuel->htmlRamp();
                $htmlFuel .= $realFuel->htmlRamp();
                $htmlFuel .= $page->butler->rowClose();
            //
            $htmlFuel .= $page->butler->tableClose();
            $htmlFuel .= "</div>\n";

            return $htmlFuel;
        }
    //
        // LaTeX fuel
        function LaTeXfuel($theoricFuel, $realFuel) {
            global $kStrings;

            $notAvailable = "~~";

            $consumption = $theoricFuel->consumption > 0 ? $theoricFuel->consumption : $notAvailable;
            $unit = $theoricFuel->unit != "" ? $theoricFuel->unit : $notAvailable;

            $latexfuel = "";
                // Fuel fold
                $latexfuel .= "% {$kStrings["Fuel"]} {{{\n";
                $latexfuel .= "\\begin{center}\n";
                $latexfuel .= "\\Large\n";
            //
                // Begin table
                $latexfuel .= "\\begin{tabular}{|l||r@{ :}c|r||r@{ :}c|r|}\n";
                $latexfuel .= "\\hline\n";
                $latexfuel .= "\\textbf{Fuel}\n";
                $latexfuel .= "& \\multicolumn{6}{c|}{\\textbf{{$kStrings["FuelConsumption"]}} {$consumption} {$unit}/h}\n";
                $latexfuel .= "\\\\\\hline\n";
                $latexfuel .= "\\multicolumn{1}{c|}{}\n";
                $latexfuel .= "& \\multicolumn{3}{c||}{{$kStrings["NoWind"]}}\n";
                $latexfuel .= "& \\multicolumn{3}{c|}{{$kStrings["Wind"]}}\n";
                $latexfuel .= "\\\\\\hhline{~------}\n";
                $latexfuel .= "\\multicolumn{1}{c|}{}\n";
                $latexfuel .= "& \\multicolumn{2}{c|}{{$kStrings["time"]}}\n";
                $latexfuel .= "& {$kStrings["fuel"]} [{$theoricFuel->unit}]\n";
                $latexfuel .= "& \\multicolumn{2}{c|}{{$kStrings["time"]}}\n";
                $latexfuel .= "& {$kStrings["fuel"]} [{$theoricFuel->unit}]\n";
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
        // HTML GC entry
        function htmlGcEntry($gcDataField, $stringId, $extraString="") {
            global $page;
            global $kArmless;
            global $kStrings;

            if($gcDataField->getArm() == $kArmless) {
                return "";
            }

            $htmlStr = $page->butler->rowOpen();

            $htmlStr .= $page->butler->cell($kStrings[$stringId] . ($extraString == "" ? "" : " $extraString"));

            $htmlStr .= $page->butler->cellOpen(array("class" => "mass num"));
            $htmlStr .= $gcDataField->mass;
            $htmlStr .= $gcDataField->isMassTooMuch() ? $kStrings["htmlTooHeavy"] : "";
            $htmlStr .= $page->butler->cellClose();

            $htmlStr .= $page->butler->cell($gcDataField->getArm(), array("class" => "arm num"));
            $htmlStr .= $page->butler->cell($gcDataField->getMoment(), array("class" => "moment num"));

            $htmlStr .= $page->butler->rowClose();

            return $htmlStr;
        }
    //
        /**
         * HTML GC
         *
         * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
         * @SuppressWarnings(PHPMD.CyclomaticComplexity)
         * @SuppressWarnings(PHPMD.NPathComplexity)
         */
        function htmlGC($gcData, $finalFuel) {
            global $page;
            global $kStrings;
            global $kFuelTypes;
            global $kFuelUnits;
            global $usgAvgas2lbs;

            $redBG = "background-color: red;";

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
                $htmlGC .= $page->butler->headerCell("[{$gcData->massUnit}]");
                $htmlGC .= $page->butler->headerCell("[{$gcData->armUnit}]");
                $htmlGC .= $page->butler->headerCell("[{$gcData->momentUnit}]");
                $htmlGC .= $page->butler->rowClose();
            //
                // empty
                $htmlGC .= $page->butler->rowOpen();
                $htmlGC .= $page->butler->cell($kStrings["Empty"]);
                $htmlGC .= $page->butler->cell(($gcData->dryEmpty->mass > 0) ? $gcData->dryEmpty->mass : "", array("class" => "mass num"));
                $htmlGC .= $page->butler->cell("", array("class" => "unavailable"));
                $htmlGC .= $page->butler->cell(($gcData->dryEmpty->getMoment() > 0) ? $gcData->dryEmpty->getMoment() : "", array("class" => "moment num"));
                $htmlGC .= $page->butler->rowClose();

            $htmlGC .= htmlGcEntry($gcData->front, "Front");

            // rears...
            $rearsCount = count($gcData->rears);
            for ($i = 0; $i < $rearsCount; ++$i) {
                $htmlGC .= htmlGcEntry($gcData->rears[$i], "Rear", "#" . ($i + 1));
            }

            // luggages...
            $luggagesCount = count($gcData->luggages);
            for ($i = 0; $i < $luggagesCount; ++$i) {
                $htmlGC .= htmlGcEntry($gcData->luggages[$i], "Luggage", "#" . ($i + 1));
            }
            // luggage total mass
            if($gcData->luggageTotalMass->mass > $gcData->luggageTotalMass->maxMass) {
                $htmlGC .= $page->butler->rowOpen();
                $htmlGC .= $page->butler->cell("{$kStrings["Luggage"]} total mass");
                $htmlGC .= $page->butler->cell(
                    "{$kStrings['TooHeavy']} {$gcData->luggageTotalMass->mass} &gt; {$gcData->luggageTotalMass->maxMass}",
                    array("colspan" => 3, "style" => $redBG)
                );
                $htmlGC .= $page->butler->rowClose();
            }

            // unusable fuels...
            $unusablesCount = count($gcData->fuelUnusables);
            for ($i = 0; $i < $unusablesCount; ++$i) {
                $tank = $finalFuel->tanks[$i];
                $htmlGC .= htmlGcEntry($gcData->fuelUnusables[$i], "UnusableFuel", "#" . ($i + 1) . "={$tank->unusable}{$tank->fuelUnit}");
            }

            $gcMin = $gcData->gcBoundaries->min == 0 ? "?" : $gcData->gcBoundaries->min;
            $gcMax = $gcData->gcBoundaries->max == 0 ? "?" : $gcData->gcBoundaries->max;

                // 0-fuel
                $gcMinArgs = array("class" => "GCend GCmin num");
                if($gcData->gcBoundaries->min > 0 && $gcData->zeroFuel->getArm() < $gcData->gcBoundaries->min) {
                    $gcMinArgs["style"] = $redBG;
                }

                $gcMaxArgs = array("class" => "GCend GCmax num");
                if($gcData->gcBoundaries->max > 0 && $gcData->zeroFuel->getArm() > $gcData->gcBoundaries->max) {
                    $gcMaxArgs["style"] = $redBG;
                }

                $gcArgs = array("class" => "arm GCend GCmid num");
                if(
                    ($gcData->gcBoundaries->min > 0 && $gcData->zeroFuel->getArm() < $gcData->gcBoundaries->min)
                    || ($gcData->gcBoundaries->max > 0 && $gcData->zeroFuel->getArm() > $gcData->gcBoundaries->max)
                ) {
                    $gcArgs["style"] = $redBG;
                }

                // Special: color red 0-fuel mass if Take-off mass is more than maxLdgW
                $massPrefix = "";
                $mldgwArgs = array();
                if($gcData->maxLdgW  > 0 && $gcData->takeOff->mass > $gcData->maxLdgW) {
                    $massPrefix = $kStrings["TooHeavy"] . " ";
                    $mldgwArgs["style"] = $redBG;
                }

                    // minimums (+moment)
                    $htmlGC .= $page->butler->rowOpen();
                    $htmlGC .= $page->butler->cell($kStrings["ZeroFuel"], array("rowspan" => 3, "class" => "GCend GCtitle"));
                    $mldgwArgs["class"] = "GCend GCmin num";
                    $htmlGC .= $page->butler->cell("", $mldgwArgs);
                    $htmlGC .= $page->butler->cell($gcMin, $gcMinArgs);
                    $htmlGC .= $page->butler->cell(
                        ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? $gcData->zeroFuel->getMoment() : "",
                        array("rowspan" => 3, "class" => "moment num")
                    );
                    $htmlGC .= $page->butler->rowClose();
                //
                    // mass+arm
                    $mass = "";
                    if($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) {
                        $mass = "$massPrefix{$gcData->zeroFuel->mass}";
                    }

                    $htmlGC .= $page->butler->rowOpen();
                    $mldgwArgs["class"] = "mass GCend GCmid num";
                    $htmlGC .= $page->butler->cell($mass, $mldgwArgs);
                    $htmlGC .= $page->butler->cell(
                        ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? "&nbsp;&nbsp;{$gcData->zeroFuel->getArm()}" : "",
                        $gcArgs
                    );
                    $htmlGC .= $page->butler->rowClose();
                //
                    // maximums
                    $htmlGC .= $page->butler->rowOpen();
                    $mldgwArgs["class"] = "GCend GCmax num";
                    $htmlGC .= $page->butler->cell(($gcData->maxLdgW > 0) ? "MLDGW={$gcData->maxLdgW}" : "", $mldgwArgs);
                    $htmlGC .= $page->butler->cell($gcMax, $gcMaxArgs);
                    $htmlGC .= $page->butler->rowClose();

            // fuels...
            $quantitiesCount = count($gcData->fuelQuantities);
            for ($i = 0; $i < $quantitiesCount; ++$i) {
                $tank = $finalFuel->tanks[$i];
                $htmlGC .= htmlGcEntry($gcData->fuelQuantities[$i], "Fuel", "#" . ($i + 1) . "={$tank->quantity}+{$tank->unusable}{$tank->fuelUnit}");
            }

                // Take-off
                $gcMinArgs = array("class" => "GCend GCmin num");
                if($gcData->gcBoundaries->min > 0 && $gcData->takeOff->getArm() < $gcData->gcBoundaries->min) {
                    $gcMinArgs["style"] = $redBG;
                }

                $gcMaxArgs = array("class" => "GCend GCmax num");
                if($gcData->gcBoundaries->max > 0 && $gcData->takeOff->getArm() > $gcData->gcBoundaries->max) {
                    $gcMaxArgs["style"] = $redBG;
                }

                $gcArgs = array("class" => "arm GCend GCmid num");
                if(
                    ($gcData->gcBoundaries->min > 0 && $gcData->takeOff->getArm() < $gcData->gcBoundaries->min)
                    || ($gcData->gcBoundaries->max > 0 && $gcData->takeOff->getArm() > $gcData->gcBoundaries->max)
                ) {
                    $gcArgs["style"] = $redBG;
                }

                $massPrefix = "";
                $mtowArgs = array();
                if($gcData->maxTOW > 0 && $gcData->takeOff->mass > $gcData->maxTOW) {
                    $massPrefix = $kStrings["TooHeavy"] . " ";
                    $mtowArgs["style"] = $redBG;
                }

                    // minimums (+moment)
                    $htmlGC .= $page->butler->rowOpen();
                    $htmlGC .= $page->butler->cell("Take-off", array("rowspan" => 3, "class" => "GCend GCtitle"));
                    $mtowArgs["class"] = "mass GCend GCmin num";
                    $htmlGC .= $page->butler->cell("", $mtowArgs);
                    $htmlGC .= $page->butler->cell($gcMin, $gcMinArgs);
                    $htmlGC .= $page->butler->cell(
                        ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? $gcData->takeOff->getMoment() : "",
                        array("rowspan" => 3, "class" => "moment num")
                    );
                    $htmlGC .= $page->butler->rowClose();
                //
                    // mass+arm
                    $mass = "";
                    if ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) {
                        $mass = "$massPrefix{$gcData->takeOff->mass}";
                    }

                    $htmlGC .= $page->butler->rowOpen();
                    $mtowArgs["class"] = "mass GCend GCmid num";
                    $htmlGC .= $page->butler->cell($mass, $mtowArgs);
                    $htmlGC .= $page->butler->cell(
                        ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? "&nbsp;&nbsp;{$gcData->takeOff->getArm()}" : "",
                        $gcArgs
                    );
                    $htmlGC .= $page->butler->rowClose();
                //
                    // maximums
                    $htmlGC .= $page->butler->rowOpen();
                    $mtowArgs["class"] = "mass GCend GCmax num";
                    $htmlGC .= $page->butler->cell(($gcData->maxTOW == 0) ? "?" : $gcData->maxTOW, $mtowArgs);
                    $htmlGC .= $page->butler->cell($gcMax, $gcMaxArgs);
                    $htmlGC .= $page->butler->rowClose();

            $htmlGC .= $page->butler->tableClose();

            $htmlGC .= "<div>1 {$kStrings["USG"]} = {$kFuelUnits["USG"]} liters</div>\n";
            $htmlGC .= "<div>1 {$kStrings["ImpG"]} = {$kFuelUnits["ImpG"]} liters</div>\n";
            $htmlGC .= "<div>1 l {$kStrings["Avgas"]} = {$kFuelTypes["AVGAS"]} kg</div>\n";
            $htmlGC .= "<div>1 {$kStrings["USG"]} {$kStrings["Avgas"]} = $usgAvgas2lbs lbs</div>\n";
            $htmlGC .= "</div><!-- GC -->\n";

            return $htmlGC;
        }
    //
        // LaTeX GC entry
        function latexGcEntry($gcDataField, $stringId, $extraString="") {
            global $kArmless;
            global $kNoArm;
            global $kDefaultPrecision;
            global $kStrings;

            if($gcDataField->getArm() == $kArmless) {
                return "";
            }

            $latexStr = "";

            $latexStr .= $kStrings[$stringId];
            if($extraString != "") {
                $latexStr .= " $extraString";
            }

            if($gcDataField->getArm() == $kNoArm) {
                return html2latex($latexStr . "&&&\\\\\\hline\n");
            }

            $latexStr .= " & {$gcDataField->mass} ";
            $latexStr .= $gcDataField->isMassTooMuch() ? $kStrings["latexTooHeavy"] : "";

            $latexStr .= " & {$gcDataField->getArm()}";

            $latexStr .= " & " . round($gcDataField->getMoment(), $kDefaultPrecision);

            $latexStr .= "\\\\\\hline\n";
            return html2latex($latexStr);
        }
    //
        /**
         * LaTeX GC
         *
         * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
         * @SuppressWarnings(PHPMD.CyclomaticComplexity)
         * @SuppressWarnings(PHPMD.NPathComplexity)
         */
        function LaTeXGC($gcData, $finalFuel) {
            global $kStrings;

            $redCell  = "\\RedCell";
            $grayCell = "\\Gray";

            $latexGC = "";

                // GC fold
                $latexGC .= "% {$kStrings["MassAndBalance"]} {{{\n";
                $latexGC .= "{\\Large\n";
                $latexGC .= "\\begin{center}\n";
            //
                // Begin table
                $latexGC .= "\\begin{tabular}{|l|r|r|r|}\n";
                $latexGC .= "\\hline\n";
                $latexGC .= "\\multirow{2}{*}{\\textbf{{$kStrings['MassAndBalance']}}}\n";
                $latexGC .= "& \\multicolumn{1}{c|}{{$kStrings["Mass"]}}\n";
                $latexGC .= "& \\multicolumn{1}{c|}{{$kStrings["Arm"]}}\n";
                $latexGC .= "& \\multicolumn{1}{c|}{{$kStrings["Moment"]}}\n";
                $latexGC .= "\\\\\n";
                $latexGC .= "& \\multicolumn{1}{c|}{[{$gcData->massUnit}]}\n";
                $latexGC .= "& \\multicolumn{1}{c|}{[{$gcData->armUnit}]}\n";
                $latexGC .= "& \\multicolumn{1}{c|}{[{$gcData->momentUnit}]}\n";
                $latexGC .= "\\\\\\hhline{-===}\n";
            //
                // empty
                $latexGC .= $kStrings["Empty"] . "   &";
                $latexGC .= ($gcData->dryEmpty->mass > 0) ? " {$gcData->dryEmpty->mass} " : "";
                $latexGC .= "& \\DarkGray &";
                $latexGC .= ($gcData->dryEmpty->getMoment() > 0) ? " {$gcData->dryEmpty->getMoment()}" : "";
                $latexGC .= "\\\\\\hline\n";

            $latexGC .= latexGcEntry($gcData->front, "Front");

            // rears...
            $rearsCount = count($gcData->rears);
            for ($i = 0; $i < $rearsCount; ++$i) {
                $latexGC .= latexGcEntry($gcData->rears[$i], "Rear", "#" . ($i + 1));
            }

            // luggages...
            $luggagesCount = count($gcData->luggages);
            for ($i = 0; $i < $luggagesCount; ++$i) {
                $latexGC .= latexGcEntry($gcData->luggages[$i], "Luggage", "#" . ($i + 1));
            }
            // luggage total mass
            if($gcData->luggageTotalMass->mass > $gcData->luggageTotalMass->maxMass) {
                $latexGC .= "{$kStrings["Luggage"]} total mass";
                $latexGC .= "& \\multicolumn{3}{c|}{";
                $latexGC .= "{$redCell} {$kStrings['TooHeavy']} {$gcData->luggageTotalMass->mass} > {$gcData->luggageTotalMass->maxMass}";
                $latexGC .= "}\\\\\\hline\n";
            }

            // unusable fuels...
            $unusablesCount = count($gcData->fuelUnusables);
            for ($i = 0; $i < $unusablesCount; ++$i) {
                $tank = $finalFuel->tanks[$i];

                $label = "#" . ($i + 1);
                if($tank->unusable > 0) {
                    // If we do the template, we do not want to display this (empty) value
                    $label .= "={$tank->unusable}{$tank->fuelUnit}";
                }

                $latexGC .= latexGcEntry($gcData->fuelUnusables[$i], "UnusableFuel", $label);
            }
            $latexGC .= "\\hline\n";

            $gcMin = $gcData->gcBoundaries->min == 0 ? "?" : $gcData->gcBoundaries->min;
            $gcMax = $gcData->gcBoundaries->max == 0 ? "?" : $gcData->gcBoundaries->max;

                // 0-fuel
                $gcMinStyle = ($gcData->gcBoundaries->min > 0 && $gcData->zeroFuel->getArm() < $gcData->gcBoundaries->min) ? $redCell : $grayCell;
                $gcMaxStyle = ($gcData->gcBoundaries->max > 0 && $gcData->zeroFuel->getArm() > $gcData->gcBoundaries->max) ? $redCell : $grayCell;
                $gcStyle = (
                    ($gcData->gcBoundaries->min > 0 && $gcData->zeroFuel->getArm() < $gcData->gcBoundaries->min)
                    || ($gcData->gcBoundaries->max > 0 && $gcData->zeroFuel->getArm() > $gcData->gcBoundaries->max)
                ) ? $redCell : $grayCell;

                // Special: color red 0-fuel mass if Take-off mass is more than maxLdgW
                $massPrefix = "";
                $mldgwStyle = $grayCell;
                if ($gcData->maxLdgW > 0 && $gcData->takeOff->mass > $gcData->maxLdgW) {
                    $massPrefix = $kStrings["TooHeavy"] . " ";
                    $mldgwStyle = $redCell;
                }

                    // minimums
                    $latexGC .= "\\multirow{3}{*}{\\textbf{{$kStrings["ZeroFuel"]}}}\n";
                    $latexGC .= "& $mldgwStyle\n";
                    $latexGC .= "& $gcMinStyle {\\normalsize min=$gcMin}\n";
                    $latexGC .= "&\\\\\n";
                //
                    // values
                    $mass = "";
                    if($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) {
                        $mass = "$massPrefix{$gcData->zeroFuel->mass}";
                    }

                    $latexGC .= "& $mldgwStyle $mass\n";
                    $latexGC .= "& $gcStyle";
                    $latexGC .= ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? " {$gcData->zeroFuel->getArm()}" : "";
                    $latexGC .= "\n";
                    $latexGC .= "&";
                    $latexGC .= ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? " {$gcData->zeroFuel->getMoment()}" : "";
                    $latexGC .= "\\\\\n";
                //
                    // maximums
                    $latexGC .= "& $mldgwStyle" . (($gcData->maxLdgW > 0) ? " {\\normalsize MLDGW={$gcData->maxLdgW}}" : "") . "\n";
                    $latexGC .= "& $gcMaxStyle {\\normalsize max=$gcMax}\n";
                    $latexGC .= "&\\\\\n";
                    $latexGC .= "\\hhline{====}\n";

            // fuels...
            $quantitiesCount = count($gcData->fuelQuantities);
            for ($i = 0; $i < $quantitiesCount; ++$i) {
                $tank = $finalFuel->tanks[$i];

                $label = "#" . ($i + 1);
                if($tank->quantity > 0) {
                    // If we do the template, we do not want to display this (empty) value
                    $label .= "={$tank->quantity}+{$tank->unusable}{$tank->fuelUnit}";
                }

                $latexGC .= latexGcEntry($gcData->fuelUnusables[$i], "Fuel", $label);
            }
            $latexGC .= "\\hline\n";

                // T-off
                $gcMinStyle = ($gcData->gcBoundaries->min > 0 && $gcData->takeOff->getArm() < $gcData->gcBoundaries->min) ? $redCell : $grayCell;
                $gcMaxStyle = ($gcData->gcBoundaries->max > 0 && $gcData->takeOff->getArm() > $gcData->gcBoundaries->max) ? $redCell : $grayCell;
                $gcStyle = (
                    ($gcData->gcBoundaries->min > 0 && $gcData->takeOff->getArm() < $gcData->gcBoundaries->min)
                    || ($gcData->gcBoundaries->max > 0 && $gcData->takeOff->getArm() > $gcData->gcBoundaries->max)
                ) ? $redCell : $grayCell;

                $massPrefix = "";
                $mtowStyle = $grayCell;
                if ($gcData->maxTOW > 0 && $gcData->takeOff->mass > $gcData->maxTOW) {
                    $massPrefix = $kStrings["TooHeavy"] . " ";
                    $mtowStyle = $redCell;
                }

                    // minimums
                    $latexGC .= "\\multirow{3}{*}{\\textbf{{$kStrings["TakeOff"]}}}\n";
                    $latexGC .= "& $mtowStyle\n";
                    $latexGC .= "& $gcMinStyle {\\normalsize min=$gcMin}\n";
                    $latexGC .= "&\\\\\n";
                //
                    // values
                    $mass = "";
                    if($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) {
                        $mass = "$massPrefix{$gcData->takeOff->mass}";
                    }

                    $latexGC .= "& $mtowStyle $mass\n";
                    $latexGC .= "& $gcStyle";
                    $latexGC .= ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? " {$gcData->takeOff->getArm()}" : "";
                    $latexGC .= "\n";
                    $latexGC .= "&";
                    $latexGC .= ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? " {$gcData->takeOff->getMoment()}" : "";
                    $latexGC .= "\\\\\n";
                //
                    // maximums
                    $latexGC .= "& $mtowStyle {\\normalsize max=" . (($gcData->maxTOW > 0) ? $gcData->maxTOW : "?") . "}\n";
                    $latexGC .= "& $gcMaxStyle {\\normalsize max=$gcMax}\n";
                    $latexGC .= "&\\\\\n";
            //
                // End table
                $latexGC .= "\\hline\n";
                $latexGC .= "\\end{tabular}\n";
                $latexGC .= "\\end{center}\n";
                $latexGC .= "}  % Large\n";

            return $latexGC;
        }
//


    // nav details
    $navid = $_GET["id"];
    if($navid == 0) {
        $latexfile = fopen("$kTemplateFilename.tex", "w") or die("Cannot write file $kTemplateFilename.tex");

        $row = LaTeXfirstRow(NULL);
        $noFuel = new FuelRequirements();

        // Set no-arm to stations we want (not all, only pick some of each)
        $gcData->front->arm = $kNoArm;
        $gcData->rears[0]->arm = $kNoArm;
        $gcData->luggages[0]->arm = $kNoArm;
        $gcData->fuelUnusables[0]->arm = $kNoArm;
        $gcData->fuelUnusables[1]->arm = $kNoArm;
        $gcData->fuelQuantities[0]->arm = $kNoArm;
        $gcData->fuelQuantities[1]->arm = $kNoArm;

        fwrite(
            $latexfile,

            LaTeXheader($docVersion, $plane)
            . $row->latexcontent
            . LaTeXfinish1(0, $row->inc, $maxRow)
            . LaTeXheaderEnd()
            . LaTeX2left()  // beginning of second page
            . LaTeXfuel(new FuelRequirements(), new FuelRequirements())
            . LaTeX2right()  // change to 2nd column
            . LaTeXGC($gcData, $noFuel)
            . LaTeXend()
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

        $DryEmptyTimestamp,
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

    // prepare LaTeX content
    $latexhead = LaTeXheader($docVersion, $plane, $name, $variation);
    $latexheadend = LaTeXheaderEnd();
    $latex2_1 = LaTeX2left();  // beginning of second page
    $latex2_2 = LaTeX2right();  // change to 2nd column
    $latexend = LaTeXend();  // end of LaTeX
//

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

        if($wpNum == $kWaypoints->wayBack->start || $wpNum == $kWaypoints->wayBack->last) {
            // flag to know if round-trip
            $roundTrip = true;
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

        if($plane->speedPlanning > 0) {
            // time computation
            $speed = ($climbing && $plane->speedClimb > 0) ? $plane->speedClimb : $plane->speedPlanning;

            $theoricEET = ComputeEET($distance, $speed);

            if($wpNum < $kWaypoints->alternate->limit) {
                $theoricTripTime += $theoricEET;

            } else {
                $theoricAlternateTime += $theoricEET;
            }

            if($kWaypoints->isStartLast($wpNum)) {
                if($wpNum < $kWaypoints->alternate->limit) {
                    $theoricTripTime += 5;

                } else {
                    $theoricAlternateTime += 5;
                }
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

                if($kWaypoints->isStartLast($wpNum)) {
                    if($wpNum < $kWaypoints->alternate->limit) {
                        $realTripTime += 5;
                    } else {
                        $realAlternateTime += 5;
                    }
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
        $rowArgs = new stdClass();
        $rowArgs->id = $id;
        $rowArgs->wpNum = $wpNum;
        $rowArgs->oldWP = $oldWP;
        $rowArgs->waypoint = $waypoint;
        $rowArgs->destination = $destination;
        $rowArgs->notes = $notes;
        $rowArgs->trueCourse = $trueCourse;
        $rowArgs->magneticCourse = $magneticCourse;
        $rowArgs->altitude = $altitude;
        $rowArgs->distance = $distance;
        $rowArgs->climbing = $climbing;
        $rowArgs->theoricEET = $theoricEET;
        $rowArgs->hasWind = $hasWind;
        $rowArgs->windTC = $windTC;
        $rowArgs->windSpeed = $windSpeed;
        $rowArgs->magneticHeading = $magneticHeading;
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

    // display to page
    $htmlRows .= htmlRowAny($rowArgs);

    // LaTeX
    $row = LaTeXrowAny($rowArgs);

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
    // GC MTOW (also takes care of mass conversion if required)
    $gcData->computeZeroFuelData();
    $gcData->computeTakeOffData();
//
    // HTML
    $body .= htmlHeader($name, $plane, $variation, $gcData, $isAdmin);
    $body .= $htmlRows;

    if($isAdmin) {
        // option to insert new WP
        $body .= $page->butler->rowOpen();
        $body .= $page->butler->cell($page->bodyBuilder->anchor("waypoint.php?nav=$navid", "new waypoint"), array("colspan" => 15, "class" => "newWP"));
        $body .= $page->butler->rowClose();
    }
    $body .= $page->butler->tableClose();
    $body .= $warning;
    $body .= "</div>\n";
    //
        // display more information
        $body .= "<div class=\"NavReminders\">\n";
        $body .= "<div>\n";
        $body .= "<b>{$kStrings["TopOfDescent"]}:</b>\n";
        $body .= "<ul>\n";
        $body .= "<li>{$kStrings["TOD1"]}</li>\n";
        $body .= "<li>{$kStrings["TOD2"]}</li>\n";
        $body .= "<li>{$kStrings["TOD3"]}</li>\n";
        $body .= "<li class=\"optional\">{$kStrings["TOD4"]}</li>\n";
        $body .= "</ul>\n";
        $body .= "</div>\n";
        $body .= "</div><!-- NavReminders -->\n";

        $body .= "<div class=\"NavTHwind\">\n";
        $body .= "<b>Compute {$kStrings["THwind"]}:</b>\n";
        $body .= "<ol>\n";
        $body .= "<li>&alpha;1 = (360 + TC - WH) % 360 and sign=1<br />\n";
        $body .= "if &alpha;1 &gt; 180: &alpha;1 = (360 + WH - TC) % 360 and sign=-1</li>\n";
        $body .= "<li>&alpha;2 = arcsin(WS/TS sin(&alpha;1))</li>\n";
        $body .= "<li>&alpha;3 = 180 - &alpha;1 - &alpha;2</li>\n";
        $body .= "<li>TH = TC + sign &alpha;2</li>\n";
        $body .= "<li>GS = sin(&alpha;3) / sin(&alpha;1) TS</li>\n";
        $body .= "</ol>\n";
        $body .= "</div><!-- NavTHwind -->\n";
    //
    $body .= htmlFuel($theoricFuel, $realFuel);
    $body .= htmlGC($gcData, $finalFuel);
//
    // LaTeX
    $latexcontent .= $latexRows;
        // finish latex content 1st page with empty rows
        $latexcontent .= LaTeXfinish1($wpNum, $rows, $maxRow - (int)$roundTrip);  // round-trip uses more rows
    $latexfuel = LaTeXfuel($theoricFuel, $realFuel);
    $latexGC = LaTeXGC($gcData, $finalFuel);

    // Write LaTeX file
    $latexFull = $latexhead;
    $latexFull .= $latexcontent;
    $latexFull .= $latexheadend;
    $latexFull .= $latex2_1;
    $latexFull .= $latexfuel;
    $latexFull .= $latex2_2;
    $latexFull .= $latexGC;
    $latexFull .= $latexend;

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
            $bodyHeads .= "<input type=\"submit\" name=\"compile\" value=\"online\" style=\"background: none; border: none; padding: none; color: #aaf; cursor: pointer;\"/>\n";
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
            $bodyHeads .= "<br />\n";
            $bodyHeads .= $page->bodyBuilder->anchor("insert.php", "new");
            $bodyHeads .= "<br />\n";
            $bodyHeads .= $page->bodyBuilder->anchor("display.php?dup=$navid", "duplicate");
            $bodyHeads .= "<br />\n";
            $bodyHeads .= $page->bodyBuilder->anchor("delete.php?id=$navid", "delete all WP");
        }
        $bodyHeads .= "</div>\n";
    $bodyHeads .= "</div>\n";

echo $bodyTitle . $bodyHeads . $body;
?>
