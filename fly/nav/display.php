<?php
require("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";

use stdClass;

require("common.php");
use MassMomentObject;
use FuelTankObject;
use FuelRequirements;

$docVersion = "2022-12-25";  // TODO before merging
// TODO remove &eacute from latex

$page = new PhPage($rootPath);
$page->dbHelper->init();
// debug
//$page->htmlHelper->init();
//$page->logger->levelUp(6);
// CSS
$page->cssHelper->dirUpWing();

$bIsAdmin = $page->loginHelper->userIsAdmin();

$kDefaultPrecision = 3;
$kFuelTanksNum = 4;

//
	// Aircraft data used to compute nav
	class Aircraft {
		public $sqlID = 0;
		public $type = "";
		public $identification = "";
		public $speedPlanning = 0;
		public $speedClimb = 0;
	}
//
// TODO arm+moment unit???
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
		$kGcRearNums = 2;
		$kGcLuggagesNum = 4;

			// Object having a mass, arm and moment.
			// Used for computation of gravity center.
			class MassMomentObject {
				private $precision = NULL;  // Rounding precision

				public $mass = 0;  // Mass [kg] at input time, can be converted internally at a later stage
				public $arm = 0;  // Arm
				public $moment = 0;  // Moment (mass x arm)

				public $massUnit = "";  // Mass unit
				private $massUnitInUse = "";  // mass unit of the stored value
				public $maxMass = 0;  // Maximum mass

				public function __construct() {
					global $kDefaultPrecision;
					$this->precision = $kDefaultPrecision;
				}

				// Round a value
				public function rounding($value) {
					return round($value, $this->precision);
				}

				// Conversion from kg (input) to the massUnit.
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

				// Get the moment of the object with rounding.
				// If the moment is not part of the inputs, it is computed from mass and arm.
				//
				// Returns:
				//     (float) moment
				public function getMoment() {
					if($this->moment != 0) {
						return $this->rounding($this->moment);
					}

					return $this->rounding($this->mass * $this->arm);
				}

				// Get the arm of the object with rounding.
				// If the arm is not part of the inputs, it is computed from mass and moment.
				//
				// Returns:
				//     (float) arm
				public function getArm() {
					if($this->arm != 0) {
						return $this->rounding($this->arm);
					}

					if($this->moment == 0 || $this->mass == 0) {
						return 0;
					}

					return $this->rounding(1.0 * $this->moment / $this->mass);
				}

				// Check if mass is not too much
				//
				// Returns:
				//     (bool) true if mass exceeds the max mass (if provided)
				// TODO not used -> should be!
				public function isMassTooMuch() {
					if ($this->maxMass == 0) {
						return false;
					}

					return $this->mass > $this->maxMass;
				}

				// Addition of 2 objects: mass, moment
				//
				// Args:
				//     other (object): the object to add to this
				public function add($other) {
					$this->mass += $other->mass;
					$this->moment += $other->getMoment();
				}
			}
		//
			// Singleton to hold all information required to compute the gravity center and the mass.
			class GcData {
				public $massUnit = "?";  // Mass unit
				public $armUnit = "?";  // Arm unit
				public $momentUnit = "?";  // Moment unit

				public $maxTOW = 0;  // Maximum Take-Off Weight
				public $maxLdgW = 0;  // Maximum Landing Weight

				public $gcBoundaries = NULL;  // boundaries of GC

				public $dryEmpty = NULL;  // data of dry+empty plane

				public $front = NULL;  // data for front row
				public $rears = null;  // dynamic array

				public $luggages = null;  // dynamic array
				public $luggageMaxTotalMass = 0;  // if >0 then all luggage stations together shall not exceed this value

				// Data for all fuel tanks
				public $fuelUnusables = null;  // dynamic array
				public $fuelQuantities = null;  // dynamic array

				public $zeroFuel = NULL;  // data for ZeroFuel
				public $takeOff = NULL;  // data for Take-off

				// Constructor: initialize arrays
				public function __construct() {
					$this->gcBoundaries = stdClass();
					$this->gcBoundaries->min = 0;
					$this->gcBoundaries->max = 0;

					$this->dryEmpty = new MassMomentObject();

					$this->front = new MassMomentObject();

					$this->rears = array();
					global $kGcRearNums;
					for ($i = 0; $i < $kGcRearNums; ++$i) {
						$this->rears[] = new MassMomentObject();
					}

					$this->luggages = array();
					global $kGcLuggagesNum;
					for ($i = 0; $i < $kGcLuggagesNum; ++$i) {
						$this->luggages[] = new MassMomentObject();
					}

					$this->FuelUnusable = array();
					$this->fuelQuantities = array();
					global $kFuelTanksNum;
					for ($i = 0; $i < $kFuelTanksNum; ++$i) {
						$this->FuelUnusable[] = new MassMomentObject();
						$this->fuelQuantities[] = new MassMomentObject();
					}

					$this->zeroFuel = new MassMomentObject();
					$this->takeOff = new MassMomentObject();
				}

				// Set the mass unit to all objects, using the class mass unit.
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

				// Copy the required fuel data from fuel unusables to quantities.
				public function propagateFuelData() {
					$unusablesCount =count($this->fuelUnusables); 
					for ($i = 0; $i < $unusablesCount; ++$i) {
						$this->fuelQuantities[$i]->arm = $this->fuelUnusables[$i]->arm;
					}
				}

				// Get the total luggage mass
				//
				// Returns:
				//     (float) Mass of all luggage stations
				public function getLuggageTotalMass() {
					$mass = 0;
					$luggagesCount = count($this->luggages);
					for ($i = 0; $i < $luggagesCount; ++$i) {
						$mass += $this->luggages[$i]->mass;
					}
					return $mass;
				}

				// Check if luggage mass is OK
				//
				// Returns:
				//     (bool) true if luggages mass are OK including total luggage mass (if max provided)
				public function isLuggageMassOk() {
					$status = true;
					if ($this->luggageMaxTotalMass != 0) {
						$status = ($this->getLuggageTotalMass() <= $this->luggageMaxTotalMass);
					}

					$luggagesCount = count($this->luggages);
					for ($i = 0; $i < $luggagesCount; ++$i) {
						if($this->luggages[$i]->maxMass > 0) {
							$status = ($status && $this->luggages[$i]->mass <= $this->luggages[$i]->maxMass);
						}
					}
					return $status;
				}

				// Store the final fuel requirements to compute GC data out of it.
				//
				// Args:
				//     finalFuel (FuelRequirements): object holding all data concerning fuel requirements
				public function storeFuelMass($finalFuel) {
					$tanksCount = count($finalFuel->tanks);
					for ($i = 0; $i < $tanksCount; ++$i) {
						$this->fuelUnusables[$i]->mass = $finalFuel->tanks[$i]->getFuelUnusableMass();
						$this->fuelQuantities[$i]->mass = $finalFuel->tanks[$i]->getFuelQuantityMass();
					}
				}

				// Compute zeroFuel data: add everything except fuel BUT including unusable fuel
				public function computeZeroFuelData() {
					$this->zeroFuel = new MassMomentObject();

					// dryEmpty
					// Do not convert mass, it is the reference in the right unit already
					$this->zeroFuel->add($this->dryEmpty);

					// front
					$this->front->convertMass();
					$this->zeroFuel->add($this->front);

					$rearsCount = count($this->rears);
					for ($i = 0; $i < $rearsCount; ++$i) {
						$this->rears[$i]->convertMass();
						$this->zeroFuel->add($this->rears[$i]);
					}

					$luggagesCount = count($this->luggages);
					for ($i = 0; $i < $luggagesCount; ++$i) {
						$this->luggages[$i]->convertMass();
						$this->zeroFuel->add($this->luggages[$i]);
					}

					$unusablesCount = count($this->fuelUnusables);
					for ($i = 0; $i < $unusablesCount; ++$i) {
						$this->fuelUnusables[$i]->convertMass();
						$this->zeroFuel->add($this->fuelUnusables[$i]);
					}
				}

				// Compute Take-off data: add fuel quantities to the zeroFuel
				public function computeTakeOffData() {
					// Copy zeroFuel
					$this->takeOff = new MassMomentObject();
					$this->takeOff = $this->takeOff->add($this->zeroFuel);

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
			private $precision = NULL;  // Rounding precision

			public $totalCapacity = 0;  // total capacity of the fuel tank
			public $unusable = 0;  // unusable fuel in the tank

			public $fuelUnit = "?";  // fuel unit
			public $fuelType = "?";  // fuel type

			public $quantity = 0;  // fuel quantity computed
			public $allOrNothing = False;  // if True, tank is filled full or not

			public function __construct() {
				global $kDefaultPrecision;
				$this->precision = $kDefaultPrecision;
			}

			// Deep copy of an object to duplicate the required fields (but not quantity).
			//
			// Returns:
			//     new object with data from this
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

			// Get the mass of fuel depending on the type and the unit in use, and rounded to the precision.
			//
			// Args:
			//     quantity (float): fuel quantity
			//
			// Returns:
			//     (float) fuel mass
			public function getFuelMass($quantity) {
				global $kFuelTypes;
				global $kFuelUnits;
				return round($quantity * $kFuelUnits[$this->fuelUnit] * $kFuelTypes[$this->fuelType], $this->precision);  // [kg]
			}

			// Get the unusable fuel mass
			//
			// Returns:
			//     (float) unusable fuel mass
			public function getFuelUnusableMass() {
				return $this->getFuelMass($this->unusable);
			}

			// Get the mass of the fuel quantity
			//
			// Returns:
			//     (float) fuel quantity mass
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

			// Constructor: initialize arrays
			public function __construct() {
				global $kFuelTanksNum;

				$this->tanks = array();
				for ($i = 0; $i < $kFuelTanksNum; ++$i) {
					$this->tanks[$i] = new FuelTankObject();
				}
			}

			// Deep copy of an object without copying the fuel quantity
			//
			// Returns:
			//     new object with data duplicated
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

			// Set the trip and alternate times.
			//
			// Args:
			//     trip (int): minutes for trip
			//     alternate (int): minutes for alternate
			public function setTripAndAlternateTimes($trip, $alternate) {
				$this->tripMinutes = $trip;
				$this->alternateMinutes = $alternate;
			}

			// Propagate fuel data (unit+type) to the tanks
			public function propagateFuelData() {
				$tanksCount = count($this->tanks);
				for ($i = 0; $i < $tanksCount; ++$i) {
					$this->tanks[$i]->fuelUnit = $this->fuelUnit;
					$this->tanks[$i]->fuelType = $this->fuelType;
				}
			}

			// Check if fuel requirement is valid.
			// This is done by checking all the mandatory inputs are filled.
			//
			// Returns:
			//     (bool) True if requirement is valid
			public function isValid() {
				return (
					$this->consumption > 0
					&& $this->unit != ""
					&& $this->tripMinutes > 0
					&& $this->alternateMinutes > 0
					&& $this->reserveMinutes > 0
					&& $this->extraPercent > 0
				);
			}

			// Get total unusable fuel.
			//
			// Returns:
			//     (float) total unusable fuel
			public function getUnusable() {
				$unusable = 0;
				$tanksCount = count($this->tanks);
				for($i = 0; $i < $tanksCount; ++$i) {
					$unusable += $this->tanks[$i]->unusable;
				}
				return $unusable;
			}

			// Get the total fuel capacity.
			//
			// Returns:
			//     (float) total fuel capacity
			public function getTotalCapacity() {
				$totalCapacity = 0;
				$tanksCount = count($this->tanks);
				for($i = 0; $i < $tanksCount; ++$i) {
					$totalCapacity += $this->tanks[$i]->totalCapacity;
				}
				return $totalCapacity;
			}

			// Convert time to fuel quantity.
			//
			// Args:
			//     time (float): time of flight [min]
			//
			// Returns:
			//     (float) fuel quantity [unit]
			public function time2quantity($time) {
				return ceil($time * $this->consumption / 60.0);
			}

			// Get the fuel quantity for the trip.
			//
			// Returns:
			//     (float) fuel quantity for the trip [unit]
			public function getTrip() {
				return $this->time2quantity($this->tripMinutes);
			}

			// Get the fuel quantity for the alternate.
			//
			// Returns:
			//     (float) fuel quantity for the alternate [unit]
			public function getAlternate() {
				return $this->time2quantity($this->alternateMinutes);
			}

			// Get the fuel quantity for the reserve.
			//
			// Returns:
			//     (float) fuel quantity for the reserve [unit]
			public function getReserve() {
				return $this->time2quantity($this->reserveMinutes);
			}

			// Get the minimum fuel: sum of unusable, trip, alternate, reserve.
			//
			// Returns:
			//     (float) minimum fuel quantity [unit]
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

			// Compute and get the extra fuel
			//
			// Returns:
			//     (float) extra fuel
			public function getExtraFuel() {
				if (!$this->isValid()) {
					return -1;
				}

				return ceil($this->getMinimumFuel() * $this->extraPercent);
			}

			// TODO do we check for the overflow???
			// Compute and get the ramp fuel
			//
			// Returns:
			//     (float) ramp fuel [unit]
			public function getRampFuel() {
				if (!$this->isValid()) {
					return -1;
				}

				return $this->getMinimumFuel() + $this->getExtraFuel();
			}

			// Fill a single tank with the required quantity.
			//
			// Args:
			//     quantity (float): fuel quantity to fill in the tank [unit]
			//     tank (object): tank object to fill
			//
			// Returns:
			//     (float) remaining quantity to fill in the next tanks
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

			// Fill all the tanks with the required fuel quantity.
			//
			// Returns:
			//     (bool) True if tanks have been filled
			public function fillTanks() {
				if (!$this->isValid()) {
					return False;
				}

				$quantity = $this->getRampFuel();
				$tanksCount = count($this->tanks);
				for($i = 0; $i < $tanksCount; ++$i) {
					$quantity = $this->fillSingleTank($quantity, $this->tanks[$i]);
				}
				$this->overflow = $quantity;

				return True;
			}

			// Get the time of the desired entry
			//
			// Args:
			//     fuelEntry (enum): the desired entry
			//
			// Returns:
			//     (int) flight time [min]
			public function getEntryMinutes($fuelEntry) {
				if($fuelEntry == FuelEntry::Trip) {
					return $this->tripminues;
				}

				if($fuelEntry == FuelEntry::Alternate) {
					return $this->alternateMinutes;
				}

				if($fuelEntry == FuelEntry::Reserve) {
					return $this->reserveMinutes;
				}

				return -1;  // undefined fuel entry
			}

			// Get the fuel quantity of the desired entry
			//
			// Args:
			//     fuelEntry (enum): the desired entry
			//
			// Returns:
			//     (float) fuel quantity [unit]
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

			// Get the HTML cells to make a partial row for the desired fuel entry
			//
			// Args:
			//     page (object): PhPage
			//     fuelEntry (enum): the desired fuel entry
			//
			// Returns:
			//     (string) HTML cells
			public function htmlRow($page, $fuelEntry) {
				if(!$this->isValid()) {
					return "<td></td>\n<td></td>\n";
				}

				$minutes = $this->getEntryMinutes($fuelEntry);

				$htmlRow = "<td class=\"unavailable\"></td>\n";  // default, if minutes==0
				if($minutes > 0) {
					$htmlRow = "<td>{$page->timeHelper->minutesDisplay($minutes)}</td>\n";
				}

				$htmlRow .= "<td>{$this->getEntryQuantity($fuelEntry)}</td>\n";
				return $htmlRow;
			}

			// Get the HTML cells to make a partial row for the ramp fuel entry
			//
			// Returns:
			//     (string) HTML cells
			public function htmlRamp() {
				if(!$this->isValid()) {
					return "<td></td>\n<td></td>\n";
				}

				$htmlRow = "<td class=\"unavailable\"></td>\n";
				$attrQ = "";

				if($this->overflow > 0) {
					$attrQ = " style=\"background-color: red;\"";
					$htmlRow = "<td style=\"background-color: red;\">Overflow: {$this->overflow}</td>\n";
				}

				$htmlRow .= "<td{$attrQ}>{$this->getRampFuel()}</td>\n";
				return $htmlRow;
			}

			// Get the LaTeX cells to make a partial row for the desired fuel entry
			//
			// Args:
			//     page (object): PhPage
			//     fuelEntry (enum): the desired fuel entry
			//
			// Returns:
			//     (string) LaTeX cells
			public function latexRow($page, $fuelEntry) {
				if(!$this->isValid()) {
					return "&&&";
				}

				$minutes = $this->getEntryMinutes($fuelEntry);

				$latexRow = " \\multicolumn{2}{c}{\\DarkGray}\n";

				if($minutes > 0) {
					$latexRow = " {$page->timeHelper->minutes2HoursInt($minutes)}&";
					$latexRow .= sprintf("%02d", $page->timeHelper->minutes2MinutesRest($minutes)) . " ";
				}

				$latexRow .= "& {$this->getEntryQuantity($fuelEntry)} &";
				return $latexRow;
			}

			// Get the LaTeX cells to make a partial row for the ramp fuel entry
			//
			// Returns:
			//     (string) LaTeX cells
			public function latexRamp() {
				if(!$this->isValid()) {
					return "&&&";
				}

				$latexRow = " \\multicolumn{2}{c}{\\DarkGray}";

				if($this->overflow > 0) {
					$latexRow = "\\multicolumn{2}{c}{\\RedCell Overflow: {$this->overflow}}";
				}

				$latexRow .= "\n& {$this->getRampFuel()} &";

				return $latexRow;
			}
		}
//
	// Init vars
	$maxRow = 17;
	$TABLE = "NavList";

	$plane = new Aircraft();

	$usgAvgas2lbs = 6;  // [lbs/USG]
	//
		// names
		$kStrings = array();

		$kStrings["Waypoint"] = "Waypoint";
		$kStrings["TrueCourse"] = "TC";
		$kStrings["MagneticCourse"] = "MC";
		$kStrings["Dist"] = "Dist";
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
		$kStrings["TOD1"] = "120kts (2NM/min) 500 ft/min (children -12: 300) 3deg makes descent rate = speed x5";
		$kStrings["TOD2"] = "1NM attitude change";
		$kStrings["TOD3"] = "2NM speed decrease";
		$kStrings["TOD4"] = "2NM approach check (if needed)";

		// Fuel
		$kStrings["Fuel"] = "Fuel";
		$kStrings["fuel"] = "fuel";
		$kStrings["time"] = "time";
		$kStrings["FuelPerHour"] = "Fuel per hour:";
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
		$kStrings["WeightAndBalance"] = "Weight and balance";
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
//

	// duplicate
	if(isset($_GET["dup"]) && $bIsAdmin) {
		$dupID = $_GET["dup"];

		// TODO SQL
		$copy_nav = "INSERT INTO `{$page->dbHelper->dbName}`.`$TABLE` (";
		$copy_nav .= "`name`";
		$copy_nav .= ", `plane`";
		$copy_nav .= ", `variation`";
		$copy_nav .= ", `FrontMass`";
		$copy_nav .= ", `RearMass`";
		$copy_nav .= ", `LuggageMass`";
		$copy_nav .= ", `comment`";
		$copy_nav .= ") SELECT ";
		$copy_nav .= "concat(`name`, ' (COPY)')";
		$copy_nav .= ", `plane`";
		$copy_nav .= ", `variation`";
		$copy_nav .= ", 0";
		$copy_nav .= ", 0";
		$copy_nav .= ", $kDefaultLuggageMass";
		$copy_nav .= ", `comment`";
		$copy_nav .= " FROM `{$page->dbHelper->dbName}`.`$TABLE`";
		$copy_Nav .= " WHERE `$TABLE`.`id` = ?";

		$copy_WP  = "INSERT INTO `{$page->dbHelper->dbName}`.`NavWaypoints` (";
		$copy_WP .= "`NavID`";
		$copy_WP .= ", `WPnum`";
		$copy_WP .= ", `waypoint`";
		$copy_WP .= ", `TC`";
		$copy_WP .= ", `distance`";
		$copy_WP .= ", `altitude`";
		$copy_WP .= ", `windTC`";
		$copy_WP .= ", `windSpeed`";
		$copy_WP .= ", `notes`";
		$copy_WP .= ", `climbing`";
		$copy_WP .= ") SELECT ";
		$copy_WP .= "?";
		$copy_WP .= ", `WPnum`";
		$copy_WP .= ", `waypoint`";
		$copy_WP .= ", `TC`";
		$copy_WP .= ", `distance`";
		$copy_WP .= ", `altitude`";
		$copy_WP .= ", `windTC`";
		$copy_WP .= ", `windSpeed`";
		$copy_WP .= ", `notes`";
		$copy_WP .= ", `climbing`";
		$copy_WP .= " FROM `{$page->dbHelper->dbName}`.`NavWaypoints`";
		$copy_WP .= " WHERE `NavWaypoints`.`NavID` = ?";

		$qNav = $page->dbHelper->queryPrepare($copy_nav);
		$qNav->bind_param("i", $dupID);
		$page->dbHelper->executeManage($qNav);
		$newID = $qNav->insert_id;

		$qWP  = $page->dbHelper->queryPrepare($copy_WP );
		$qWP->bind_param("ii", $newID, $dupID);
		$page->dbHelper->executeManage($qWP);

		// redirect to edit page so we can change title and nav infos
		$page->htmlHelper->headerLocation("insert.php?id=$newID");
	}
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
				$latexhead .= "	colorlinks        = true,\n";
				$latexhead .= "	bookmarks         = true,\n";
				$latexhead .= "	bookmarksnumbered = false,\n";
				$latexhead .= "	linkcolor         = black,\n";
				$latexhead .= "	urlcolor          = blue,\n";
				$latexhead .= "	citecolor         = blue,\n";
				$latexhead .= "	filecolor         = blue,\n";
				$latexhead .= "	hyperfigures      = true,\n";
				$latexhead .= "	breaklinks        = false,\n";
				$latexhead .= "	ps2pdf,\n";
				$latexhead .= "	pdftitle          = {\\ThisTitle},\n";
				$latexhead .= "	pdfsubject        = {\\ThisTitle},\n";
				$latexhead .= "	pdfauthor         = {\\ThisAuthors}\n";
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
					$latexhead .= "% Pictures positions {{{\n";
					$latexhead .= "\\renewcommand{\\topfraction}{0.85}\n";
					$latexhead .= "\\renewcommand{\\textfraction}{0.1}\n";
					$latexhead .= "\\renewcommand{\\floatpagefraction}{0.75}\n";
					$latexhead .= "% End of pictures }}}\n";
				//
					$latexhead .= "% New commands {{{\n";
						$latexhead .= "% Miscellaneous {{{\n";
						$latexhead .= "\\renewcommand{\\roman}{\\Roman}\n";
						$latexhead .= "%\\renewcommand{\\geq}{\\geqslant}\n";
						$latexhead .= "%\\renewcommand{\\leq}{\\leqslant}\n";
						$latexhead .= "\\newcommand*{\\oC}{\\ensuremath{^{\\circ}C}}\n";
						$latexhead .= "\\AtBeginDocument{\\renewcommand{\\labelitemi}{\\textbullet}}% Default is \\textendash % must be placed after begin-doc\n";
						$latexhead .= "\\makeatletter\n";
						$latexhead .= "\\newcommand{\\rom}[1]{\\expandafter\\@slowromancap\\romannumeral #1@}\n";
						$latexhead .= "\\makeatother\n";
						$latexhead .= "% }}}\n";
					//
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
			$latexhead .= "\\begin{longtable}{|c|c|l|c||c|}\n";
			$latexhead .= "xxxxxxxxxxxxxxxxxxxxxxxxx xxxxxxxxxxxxxxxxxxxxxxxxx\n";
			$latexhead .= "& xxxxxxxxxxxxx\n";
			$latexhead .= "&\n";
			$latexhead .= "& 0000000000\n";
			$latexhead .= "&\\kill\n";
			$latexhead .= "%\\multicolumn{2}{|c|}{Flight}\n";
			$latexhead .= "Flight\n";
			$latexhead .= "& Aircraft\n";
			$latexhead .= "& Identification\n";
			$latexhead .= "& TAS  [kts]\n";
			$latexhead .= "& VAR $[^{\\textrm{o}} \\textrm{E}]$\n";
			$latexhead .= "\\\\\\hline\n";

			// Fill second line with nav infos
			$latexhead .= "%\n";
			$latexhead .= "\\multirow{2}{*}{{$name}}\n";
			$latexhead .= "&\\multirow{2}{*}{{$plane->type}}\n";
			$latexhead .= "&\\multirow{2}{*}{{$longID}}\n";
			$latexhead .= "&\\multirow{2}{*}{";

			if($plane->speedPlanning > 0) {
				$latexhead .= $plane->speedPlanning;

				if($plane->speedClimb > 0) {
					$latexhead .= " ({$plane->speedClimb})";
				}
			}

			$latexhead .= "}\n";
			$latexhead .= "&\\multirow{2}{*}{{$variation}}\n";
			$latexhead .= "\\\\\n";

			$latexhead .= "&&\n";
			$latexhead .= "&\n";
			$latexhead .= "&\\\\[2mm]\n";
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
			$latexhead .= "\\renewcommand*{\\arraystretch}{1.7}\n";
			$latexhead .= "% Nav plan {{{\n";
			$latexhead .= "\\begin{longtable}{%\n";
			$latexhead .= "	|c|l|%\n";
			$latexhead .= "	>{\\columncolor[gray]{0.75}}c|c|c|c|c|%\n";
			$latexhead .= "	|>{\\columncolor[gray]{0.88}}c|>{\\columncolor[gray]{0.88}}c|c|c|c|%\n";
			$latexhead .= "	|c|c||l|%\n";
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

			$latex2left = "";

			$latex2left .= "\\clearpage\n";
			$latex2left .= "\\fancyhf{}\n";
			$latex2left .= "\\renewcommand{\\headrulewidth}{0pt}\n";
			$latex2left .= "%\n";
			$latex2left .= "\\noindent\n";
			$latex2left .= "%\n";
			$latex2left .= "\\begin{minipage}{0.57\\textwidth}\n";
			$latex2left .= "{\\Large\n";
			$latex2left .= "% {$kStrings["TopOfDescent"]} {{{\n";
			$latex2left .= "\\textbf{{$kStrings["TopOfDescent"]}:}\n";
			$latex2left .= "\\begin{itemize}\n";
			$latex2left .= "\\renewcommand*{\\labelitemi}{\\phantom{(}\\textbullet\\phantom{)}}\n";
			$latex2left .= "	\\item {$kStrings["TOD1"]}\n";
			$latex2left .= "	\\item {$kStrings["TOD2"]}\n";
			$latex2left .= "	\\item {$kStrings["TOD3"]}\n";
			$latex2left .= "	\\item[(\\textbullet)] {$kStrings["TOD4"]}\n";
			$latex2left .= "\\end{itemize}\n";
			$latex2left .= "% }}}\n";
			$latex2left .= "}\n";
			$latex2left .= "\\vspace{11mm}\n";
			return $latex2left;
		}
	//
		// LaTeX 2nd page right column
		function LaTeX2right() {
			// change to 2nd column
			global $kStrings;

			$latex2right = "";
			$latex2right .= "\\vspace*{6mm}\n";
			$latex2right .= "\\end{minipage}\n";
			$latex2right .= "\\begin{minipage}{0.42\\textwidth}\n";
			$latex2right .= "% {$kStrings["THwind"]} {{{\n";
			$latex2right .= "{\n";
			$latex2right .= $kStrings["THwind"] . ":\n";
			$latex2right .= "\\begin{enumerate}\n";
			$latex2right .= "\\item $\\alpha_1 = (360 + \\textrm{TC} - \\textrm{WH}) \\% 360^{\\circ}$ and $\\textrm{sign} = 1$\n";
			$latex2right .= "\\item if $\\alpha_1 > 180: \\alpha_1 = (360 + \\textrm{WH} - \\textrm{TC}) \\% 360^{\\circ}$ and $\\textrm{sign} = -1$\n";
			$latex2right .= "\\item $\\alpha_2 = \\arcsin \\left( \\frac{\\textrm{WS}}{\\textrm{TS}} \\cdot \\sin \\alpha_1 \\right)$\n";
			$latex2right .= "\\item $\\alpha_3 = 180 - (\\alpha_1 + \\alpha_2)$\n";
			$latex2right .= "\\item $\\textrm{TH} = \\textrm{TC} + \\textrm{sign} \\cdot \\alpha_2$\n";
			$latex2right .= "\\item $\\textrm{GS} = \\frac{\\sin \\alpha_3}{\\sin \\alpha_1} \\cdot \\textrm{TS}$\n";
			$latex2right .= "\\end{enumerate}\n";
			$latex2right .= "}\n";
			$latex2right .= "% }}}\n";
			return $latex2right;
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
			$latexend .= "{\n";
			$latexend .= "\\small\n";
			$latexend .= "$1\\ \\textrm{{$kStrings["USG"]}} = {$kFuelUnits["USG"]}\\ l$\n";
			$latexend .= "\\hspace{17mm}\n";
			$latexend .= "$1\\ l\\ \\textrm{{$kStrings["Avgas"]}} = {$kFuelTypes["AVGAS"]}\\ kg$\n";
			$latexend .= "\\\\\n";
			$latexend .= "$1\\ \\textrm{{$kStrings["ImpG"]}} = {$kFuelUnits["ImpG"]}\\ l$\n";
			$latexend .= "\\hspace{17mm}\n";
			$latexend .= "$1\\ \\textrm{{$kStrings["USG"]} {$kStrings["Avgas"]}} = $usgAvgas2lbs$ lbs\n";
			$latexend .= "}\n";
			$latexend .= "}\n";
			$latexend .= "% }}}\n";
			$latexend .= "\\end{minipage}\n";
			$latexend .= "\\end{document}\n";
			$latexend .= "%\n";
			return $latexend;
		}
	//
		// HTML header: first table
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
			if($gcData->front->mass > 0) {
				$htmlHead .= "<div>\n";
				$htmlHead .= "<b>Mass</b>\n";
				$htmlHead .= "<ul>\n";

				if($gcData->front->mass > 0) {
					$htmlHead .= "<li>{$kStrings["Front"]}: $gcData->front->mass kg</li>\n";
				}

				$rearsCount = count($gcData->rears);
				for ($i = 0; $i < $rearsCount; ++$i) {
					if($gcData->rears[$i]->mass > 0) {
						$htmlHead .= "<li>{$kStrings["Rear"]} #$i: {$gcData->rears[$i]->mass} kg</li>\n";
					}
				}

				$luggagesCount = count($gcData->luggages);
				for ($i = 0; $i < $luggagesCount; ++$i) {
					if($gcData->luggages[$i]->mass > 0) {
						$htmlHead .= "<li>{$kStrings["Luggage"]} #$i: $gcData->luggages[$i]->mass kg</li>\n";
					}
				}

				$htmlHead .= "</ul>\n";
				$htmlHead .= "</div>\n";
			}

			$htmlHead .= "</div>\n";

			return $htmlHead;
		}
	//
		// HTML header: header of 2nd table
		function htmlHeader2ndTableHead($bIsAdmin) {
			global $kStrings;

			$htmlHead = "";
			$htmlHead .= "<div>\n";
			$htmlHead .= "<table>\n";

				// HTML table header
				$htmlHead .= "<tr>\n";
				if($bIsAdmin) {
					$htmlHead .= "<th rowspan=\"2\"></th>\n";
				}
				$htmlHead .= "<th rowspan=\"2\">{$kStrings["Waypoint"]}</th>\n";
				$htmlHead .= "<th class=\"TC\">{$kStrings["TrueCourse"]}</th>\n";
				$htmlHead .= "<th>{$kStrings["MagneticCourse"]}</th>\n";
				$htmlHead .= "<th>{$kStrings["Dist"]}.</th>\n";
				$htmlHead .= "<th>{$kStrings["Altitude"]}</th>\n";
				$htmlHead .= "<th>{$kStrings["EstimatedElapsedTime"]}</th>\n";
				$htmlHead .= "<th class=\"wind\" colspan=\"2\">{$kStrings["Wind"]}</th>\n";
				$htmlHead .= "<th>{$kStrings["MagneticHeading"]}</th>\n";
				$htmlHead .= "<th>{$kStrings["GroundSpeed"]}</th>\n";
				$htmlHead .= "<th>{$kStrings["EstimatedElapsedTime"]}</th>\n";
				$htmlHead .= "<th rowspan=\"2\">{$kStrings["EstimatedTimeOver"]}</th>\n";
				$htmlHead .= "<th rowspan=\"2\">{$kStrings["ActualTimeOver"]}</th>\n";
				$htmlHead .= "<th rowspan=\"2\">{$kStrings["Notes"]}</th>\n";
				$htmlHead .= "</tr>\n";
				$htmlHead .= "<tr>\n";
				$htmlHead .= "<th class=\"TC\">{$kStrings["htmlUnitDeg"]}</th>\n";
				$htmlHead .= "<th>{$kStrings["htmlUnitDeg"]}</th>\n";
				$htmlHead .= "<th>{$kStrings["unitNM"]}</th>\n";
				$htmlHead .= "<th>{$kStrings["unitFt"]}</th>\n";
				$htmlHead .= "<th>{$kStrings["unitMin"]}</th>\n";
				$htmlHead .= "<th class=\"wind\">{$kStrings["htmlUnitDeg"]}</th>\n";
				$htmlHead .= "<th class=\"wind\">{$kStrings["unitKts"]}</th>\n";
				$htmlHead .= "<th>{$kStrings["htmlUnitDeg"]}</th>\n";
				$htmlHead .= "<th>{$kStrings["unitKts"]}</th>\n";
				$htmlHead .= "<th>{$kStrings["unitMin"]}</th>\n";
				$htmlHead .= "</tr>\n";

			return $htmlHead;
		}
	//
		// HTML header
		function htmlHeader($name, $plane, $variation, $gcData, $bIsAdmin) {
			$htmlHead = "";
			$htmlHead .= htmlHeader1stTable($name, $plane, $variation, $gcData);
			$htmlHead .= htmlHeader2ndTableHead($bIsAdmin);
			return $htmlHead;
		}
	//
		// HTML 1st row
		function htmlFirstRow($rowArgs) {
			global $kWaypoints;

			$htmlRow = "<tr class=\"WP{$rowArgs->wpNum}\" id=\"WP{$rowArgs->wpNum}\">\n";

			if($rowArgs->bIsAdmin) {
				$htmlRow .= "<td class=\"edit\">\n";

				if($rowArgs->wpNum == $kWaypoints->wayOut->base) {
					$htmlRow .= "<a href=\"waypoint.php?id={$rowArgs->id}\" title=\"edit {$rowArgs->waypoint}\">edit</a>\n";
				}

				$htmlRow .= "</td>\n";
			}

			$destination = $rowArgs->destination;
			if($rowArgs->wpNum == $kWaypoints->wayOut->base) {
				$destination = $rowArgs->waypoint;
			}

			$htmlRow .= "<td class=\"waypoint\">{$destination}</td>\n";

			$htmlRow .= "<td colspan=\"11\" class=\"unavailable\"></td>\n";
			$htmlRow .= "<td></td>\n";

			$htmlRow .= "<td class=\"notes\">";
			if($rowArgs->wpNum == $kWaypoints->wayOut->base) {
				$htmlRow .= $rowArgs->notes;
			}
			$htmlRow .= "</td>\n";

			if($rowArgs->wpNum == $kWaypoints->wayBack->start) {
				$htmlRow .= "</tr>\n";
				$htmlRow .= "<tr class=\"WP{$rowArgs->wpNum}\">\n";
			}

			$htmlRow .= "</tr>\n";

			return $htmlRow;
		}
	//
		// HTML row alternate banner
		function htmlRowAlternateBanner($wpNum, $bIsAdmin) {
			global $kStrings;

			$colspan = 14;
			if($bIsAdmin) {
				$colspan += 1;
			}

			$htmlRow = "<tr class=\"WP{$wpNum}\">\n";
			$htmlRow .= "<td class=\"nav-alternate-title\" colspan=\"{$colspan}\">{$kStrings['Alternate']}</td>\n";
			$htmlRow .= "</tr>\n";
			return $htmlRow;
		}
	//
		// HTML row
		function htmlRow($rowArgs) {
			// Set data
			global $kWaypoints;
			global $kStrings;

			$climbing = "";
			$plus5 = "";

			if($rowArgs->climbing) {
				$climbing = $kStrings["htmlCopyright"];
			}

			if($kWaypoints->isStartLast($rowArgs->wpNum)) {
				$plus5 = $kStrings["Plus5"];
			}

			// Prepare string

			$htmlRow = "<tr class=\"WP{$rowArgs->wpNum}\" id=\"WP{$rowArgs->wpNum}\">\n";

			if($rowArgs->bIsAdmin) {
				$htmlRow .= "<td class=\"edit\">\n";
				$htmlRow .= "<a href=\"waypoint.php?id={$rowArgs->id}\" title=\"edit {$rowArgs->waypoint}\">edit</a>\n";
				$htmlRow .= "</td>\n";
			}

			// WP
			$htmlRow .= "<td class=\"waypoint\">{$rowArgs->waypoint}</td>\n";

			// TC
			$htmlRow .= "<td class=\"TC heading\">";
			if($rowArgs->trueCourse > 0) {
				$htmlRow .= sprintf("%03d", $rowArgs->trueCourse);
			}
			$htmlRow .= "</td>\n";

			// MC
			$htmlRow .= "<td class=\"heading\">";
			$htmlRow .= headingText($rowArgs->magneticCourse, $rowArgs->wpNum);
			$htmlRow .= "</td>\n";

			// distance
			$htmlRow .= "<td class=\"distance\">{$rowArgs->distance}</td>\n";

			// altitude
			$htmlRow .= "<td class=\"altitude\">";
			if($rowArgs->altitude > 0) {
				$htmlRow .= "{$rowArgs->altitude}";
			}
			$htmlRow .= "</td>\n";

			// Theoric EET
			$htmlRow .= "<td class=\"EET\">{$climbing}{$rowArgs->theoricEET}{$plus5}</td>";

				// wind (if provided)
				$windHeading = "";
				$windSpeed = "";
				$magHeading = "";
				$groundSpeed = "";
				$realEET = "";

				if($rowArgs->bHasWind) {
					$windHeading = sprintf("%03d", $rowArgs->windTC);
					$windSpeed = $rowArgs->windSpeed;
					$magHeading = headingText($rowArgs->magneticHeading, $rowArgs->wpNum);
					$groundSpeed = $rowArgs->groundSpeed;
					$realEET = $rowArgs->realEET;
				}

				$htmlRow .= "<td class=\"wind heading\">{$windHeading}</td>\n";
				$htmlRow .= "<td class=\"wind speed\">{$windSpeed}</td>\n";

				$htmlRow .= "<td class=\"heading\">{$magHeading}</td>\n";
				$htmlRow .= "<td class=\"speed\">{$groundSpeed}</td>\n";

				$htmlRow .= "<td class=\"EET\">{$climbing}{$realEET}{$plus5}</td>";

			// ETO + ATO
			$htmlRow .= "<td></td>\n";
			$htmlRow .= "<td></td>\n";

			// notes
			$htmlRow .= "<td class=\"notes\">{$rowArgs->notes}</td>\n";

			$htmlRow .= "</tr>\n";

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
			if($theoricEeTime == 0) {
				// Do not display if zero
				$theoricEeTime = "";
			}
			if($realEeTime == 0) {
				// Do not display if zero
				$realEeTime = "";
			}

			// Prepare string
			$htmlRow = "<tr class=\"summary\">\n";

			if($rowArgs->bIsAdmin) { $htmlRow .= "<td></td>\n"; }

			$htmlRow .= "<td class=\"WP\"></td>\n";
			$htmlRow .= "<td class=\"TC\"></td>\n";
			$htmlRow .= "<td></td>\n";

			$htmlRow .= "<td class=\"distance sum\">{$distance}</td>\n";

			$htmlRow .= "<td></td>\n";

			$htmlRow .= "<td class=\"EET sum\">{$theoricEeTime}</td>\n";

			$htmlRow .= "<td colspan=\"2\" class=\"wind\"></td>\n";
			$htmlRow .= "<td></td>\n";
			$htmlRow .= "<td></td>\n";

			$htmlRow .= "<td class=\"EET sum\">{$realEeTime}</td>\n";

			$htmlRow .= "<td></td>\n";
			$htmlRow .= "<td></td>\n";
			$htmlRow .= "<td></td>\n";
			$htmlRow .= "</tr>\n";

			return $htmlRow;
		}
	//
		// HTML row ANY
		function htmlRowAny($rowArgs) {
			global $kWaypoints;

			$htmlRow = "";

			if($rowArgs->wpNum == $kWaypoints->wayOut->base || $rowArgs->wpNum == $kWaypoints->wayBack->start) {
				// 1st row ever or inbound
				$htmlRow .= htmlFirstRow($rowArgs);
			}

			if($rowArgs->wpNum == $kWaypoints->alternate->start || ($rowArgs->wpNum == $kWaypoints->alternate->last && $rowArgs->oldWP < $kWaypoints->alternate->limit)) {
				$htmlRow .= htmlRowAlternateBanner($rowArgs->wpNum, $rowArgs->bIsAdmin);
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
		// LaTeX notes
		//
		// Returns:
		//     (string) translated HTML characters
		function LaTeXnotes($notes) {
			return htmlspecialchars_decode($notes, ENT_NOQUOTES);
		}
	//
		// LaTeX 1st row
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
				$rowArgs->bHasWind = false;
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

			$back->latexcontent .= "& {$destination} ";
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
		// LaTeX row
		function LaTeXrow($rowArgs) {
			// Set data
		    global $kWaypoints;
			global $kStrings;

			$climbing = "";
			$plus5 = "";

			if($rowArgs->climbing) {
				$climbing = $kStrings["latexCopyright"];
			}

			if($kWaypoints->isStartLast($rowArgs->wpNum)) {
				$plus5 = $kStrings["Plus5"];
			}

			// Prepare string
			$latexcontent = "";

			$newline = "\\hline\n";
			if($rowArgs->wpNum == $kWaypoints->alternate->start || ($rowArgs->wpNum == $kWaypoints->alternate->last && $rowArgs->oldWP < $kWaypoints->alternate->limit)) {
				$newline = "\\hhline{===============}\n";
			}
			$latexcontent .= $newline;
			$latexcontent .= "& {$rowArgs->waypoint}";
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

			if($rowArgs->bHasWind) {
				$windHeading = sprintf("%03d", $rowArgs->windTC);
				$windSpeed = $rowArgs->windSpeed;
				$magHeading = "{\\large " . headingText($rowArgs->magneticHeading, $rowArgs->wpNum) . "}";
				$groundSpeed = $rowArgs->groundSpeed;
				$realEET = "{\\large {$rowArgs->realEET}{$plus5}}";
			}

			$latexcontent .= " {$windHeading}";
			$latexcontent .= " & {$windSpeed}";
			$latexcontent .= " & {$magHeading}";
			$latexcontent .= " & {$groundSpeed}";
			$latexcontent .= " & {$climbing} {$realEET}";
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
			$latexcontent .= "&&&& {$distance} && {$theoricEeTime} &&&&& {$realEeTime} &&&\\\\";

			if($rowArgs->wpNum == $kWaypoints->alternate->last) {
				$latexcontent .= "\\hline\n";
			}

			return $latexcontent;
		}
	//
		// LaTeX row ANY
		function LaTeXrowAny($rowArgs) {
			global $kWaypoints;

			$back = new stdClass();
			$back->inc = 1;
			$back->latexcontent = "";

			if($rowArgs->wpNum == $kWaypoints->wayOut->base || $rowArgs->wpNum == $kWaypoints->wayBack->start) {
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
		function htmlFuel($page, $theoricFuel, $realFuel) {
			global $kStrings;

			$htmlFuel = "";

			$htmlFuel .= "<div class=\"fuel\">\n";
			$htmlFuel .= "<p><b>{$kStrings["FuelPerHour"]}</b> ";
			$htmlFuel .= ($theoricFuel->consumption > 0) ? $theoricFuel->consumption : "?";
			$htmlFuel .= " {$theoricFuel->unit}/h</p>\n";
			$htmlFuel .= "<table class=\"noborder\">\n";
				// head
				$htmlFuel .= "<tr>\n";
				$htmlFuel .= "<td class=\"phantom\"></td>\n";
				$htmlFuel .= "<th colspan=\"2\">{$kStrings["NoWind"]}</th>\n";
				$htmlFuel .= "<th colspan=\"2\">{$kStrings["Wind"]}</th>\n";
				$htmlFuel .= "</tr>\n";
				$htmlFuel .= "<tr>\n";
				$htmlFuel .= "<td class=\"phantom\"></td>\n";
				$htmlFuel .= "<th>{$kStrings["time"]}</th>\n";
				$htmlFuel .= "<th>{$kStrings["fuel"]} [{$theoricFuel->unit}]</th>\n";
				$htmlFuel .= "<th>{$kStrings["time"]}</th>\n";
				$htmlFuel .= "<th>{$kStrings["fuel"]} [{$theoricFuel->unit}]</th>\n";
				$htmlFuel .= "</tr>\n";
			//
				// trip
				$htmlFuel .= "<tr>\n";
				$htmlFuel .= "<td>{$kStrings["Trip"]}:</td>\n";
				$htmlFuel .= $theoricFuel->htmlRow($page, FuelEntry::Trip);
				$htmlFuel .= $realFuel->htmlRow($page, FuelEntry::Trip);
				$htmlFuel .= "</tr>\n";
			//
				// alternate
				$htmlFuel .= "<tr>\n";
				$htmlFuel .= "<td>{$kStrings["Alternate"]}:</td>\n";
				$htmlFuel .= $theoricFuel->htmlRow($page, FuelEntry::Aleternate);
				$htmlFuel .= $realFuel->htmlRow($page, FuelEntry::Aleternate);
				$htmlFuel .= "</tr>\n";
			//
				// reserve
				$htmlFuel .= "<tr>\n";
				$htmlFuel .= "<td>{$kStrings["Reserve"]}:</td>\n";
				$htmlFuel .= $theoricFuel->htmlRow($page, FuelEntry::Reserve);
				$htmlFuel .= $realFuel->htmlRow($page, FuelEntry::Reserve);
				$htmlFuel .= "</tr>\n";
			//
				// unusable
				$htmlFuel .= "<tr>\n";
				$htmlFuel .= "<td>{$kStrings["Unusable"]}:</td>\n";
				$htmlFuel .= $theoricFuel->htmlRow($page, FuelEntry::Unusable);
				$htmlFuel .= $realFuel->htmlRow($page, FuelEntry::Unusable);
				$htmlFuel .= "</tr>\n";
			//
				// minimum
				$htmlFuel .= "<tr>\n";
				$htmlFuel .= "<td>{$kStrings["MinFuel"]}:</td>\n";
				$htmlFuel .= $theoricFuel->htmlRow($page, FuelEntry::Minimum);
				$htmlFuel .= $realFuel->htmlRow($page, FuelEntry::Minimum);
				$htmlFuel .= "</tr>\n";
			//
				// extra
				$htmlFuel .= "<tr>\n";
				$htmlFuel .= "<td>{$kStrings["ExtraPlus"]}" . ($theoricFuel->extraPercent * 100) . "%:</td>\n";
				$htmlFuel .= $theoricFuel->htmlRow($page, FuelEntry::Extra);
				$htmlFuel .= $realFuel->htmlRow($page, FuelEntry::Extra);
				$htmlFuel .= "</tr>\n";
			//
				// ramp
				$htmlFuel .= "<tr>\n";
				$htmlFuel .= "<td>{$kStrings["RampFuel"]}:</td>\n";
				$htmlFuel .= $theoricFuel->htmlRamp();
				$htmlFuel .= $realFuel->htmlRamp();
				$htmlFuel .= "</tr>\n";
			//
			$htmlFuel .= "</table>\n";
			$htmlFuel .= "</div>\n";

			return $htmlFuel;
		}
	//
		// LaTeX fuel
		function LaTeXfuel($page, $theoricFuel, $realFuel) {
			global $kStrings;

			$notAvailable = "~~";

			$consumption = $notAvailable;
			if($theoricFuel->consumption > 0) {
				$consumption = $theoricFuel->consumption;
			}

			$unit = $notAvailable;
			if($theoricFuel->unit != "") {
				$unit = $theoricFuel->unit;
			}

			$latexfuel = "";
				// Fuel fold
				$latexfuel .= "% {$kStrings["Fuel"]} {{{\n";
				$latexfuel .= "\\begin{center}\n";
				$latexfuel .= "\\Large\n";
				$latexfuel .= "\\textbf{{$kStrings["FuelPerHour"]}}\n";
				$latexfuel .= "{$consumption} {$unit}/h\\\\[9mm]\n";
				$latexfuel .= "%\n";
			//
				// Begin table
				$latexfuel .= "\\begin{tabular}{|l||r@{ :}c|r||r@{ :}c|r|}\n";
				$latexfuel .= "\\hhline{~------}\n";
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
				$latexfuel .= $kStrings["Trip"] . ":      &";
				$latexfuel .= $theoricFuel->latexRow($page, FuelEntry::Trip);
				$latexfuel .= $realFuel->latexRow($page, FuelEntry::Trip);
				$latexfuel .= "\\\\\\hline\n";
			//
				// alternate
				$latexfuel .= $kStrings["Alternate"] . ": &";
				$latexfuel .= $theoricFuel->latexRow($page, FuelEntry::Alternate);
				$latexfuel .= $realFuel->latexRow($page, FuelEntry::Alternate);
				$latexfuel .= "\\\\\\hline\n";
			//
				// reserve
				$latexfuel .= $kStrings["Reserve"] . ": &";
				$latexfuel .= $theoricFuel->latexRow($page, FuelEntry::Reserve);
				$latexfuel .= $realFuel->latexRow($page, FuelEntry::Reserve);
				$latexfuel .= "\\\\\\hline\n";
			//
				// unusable
				$latexfuel .= $kStrings["Unusable"] . ": &";
				$latexfuel .= $theoricFuel->latexRow($page, FuelEntry::Unusable);
				$latexfuel .= $realFuel->latexRow($page, FuelEntry::Unusable);
				$latexfuel .= "\\\\\\hline\n";
			$latexfuel .= "\\hline\n";

				// minimum
				$latexfuel .= $kStrings["MinFuel"] . ": &";
				$latexfuel .= $theoricFuel->latexRow($page, FuelEntry::Minimum);
				$latexfuel .= $realFuel->latexRow($page, FuelEntry::Minimum);
				$latexfuel .= "\\\\\\hline\n";
			//
				// extra
				$latexfuel .= $kStrings["ExtraPlus"] . ($theoricFuel->extraPercent * 100) . "\\%: &";
				$latexfuel .= $theoricFuel->latexRow($page, FuelEntry::Extra);
				$latexfuel .= $realFuel->latexRow($page, FuelEntry::Extra);
				$latexfuel .= "\\\\\\hline\\hline\n";
			//
				// ramp fuel
				$latexfuel .= $kStrings["RampFuel"] . ": &\n";
				$latexfuel .= $theoricFuel->latexRamp();
				$latexfuel .= $realFuel->latexRamp();
				$latexfuel .= "\n";
			//
				// End table
				$latexfuel .= "\\\\\\hline\n";
				$latexfuel .= "\\end{tabular}\n";
				$latexfuel .= "\\end{center}\n";
				$latexfuel .= "% }}}\n";
			return $latexfuel;
		}
	//
		// HTML GC entry
		function htmlGCentry($gcData, $strings, $field) {
			$htmlStr = "";
			$htmlStr .= "<tr>\n";
			$htmlStr .= "<td>{$strings[$field]}</td>\n";
			$htmlStr .= "<td class=\"mass\">";
			$htmlStr .= ($gcData->front->mass > 0) ? $gcData->$field->mass : "";
			$htmlStr .= "</td>\n";
			$htmlStr .= "<td class=\"arm\">";
			$htmlStr .= ($gcData->$field->getArm() > 0) ? $gcData->$field->getArm() : "";
			$htmlStr .= "</td>\n";
			$htmlStr .= "<td class=\"moment\">";
			$htmlStr .= ($gcData->$field->getMoment() > 0) ? $gcData->$field->getMoment() : "";
			$htmlStr .= "</td>\n";
			$htmlStr .= "</tr>\n";
			return $htmlStr;
		}
	//
		// LaTeX GC entry
		function LaTeXGCentry($gcData, $strings, $field) {
			$latexStr = "";
			$latexStr .= $strings[$field] . " &";
			$latexStr .= ($gcData->front->mass > 0) ? " {$gcData->$field->mass} " : "";
			$latexStr .= "&";
			$latexStr .= ($gcData->$field->getArm() > 0) ? " {$gcData->$field->getArm()} " : "";
			$latexStr .= "&";
			global $kDefaultPrecision;
			$latexStr .= ($gcData->$field->getMoment() > 0) ? " " . round($gcData->$field->getMoment(), $kDefaultPrecision) . " " : "";
			$latexStr .= "\\\\\\hline\n";
			return $latexStr;
		}
	//
		// HTML GC
		function htmlGC($gcData) {
			global $kStrings;
			global $kFuelTypes;
			global $kFuelUnits;
			global $usgAvgas2lbs;

			$htmlGC = "";

			$htmlGC .= "<div class=\"GC\">\n";
			$htmlGC .= "<table class=\"noborder\">\n";
				// head
				$htmlGC .= "<tr>\n";
				$htmlGC .= "<td class=\"phantom\"></td>\n";
				$htmlGC .= "<th>{$kStrings["Mass"]}</th>\n";
				$htmlGC .= "<th>{$kStrings["Arm"]}</th>\n";
				$htmlGC .= "<th>{$kStrings["Moment"]}</th>\n";
				$htmlGC .= "</tr>\n";
				$htmlGC .= "<tr>\n";
				$htmlGC .= "<td class=\"phantom\"></td>\n";
				$htmlGC .= "<th>[{$gcData->massUnit}]</th>\n";
				$htmlGC .= "<th>[{$gcData->armUnit}]</th>\n";
				$htmlGC .= "<th>[{$gcData->momentUnit}]</th>\n";
				$htmlGC .= "</tr>\n";
			//
				// empty
				$htmlGC .= "<tr>\n";
				$htmlGC .= "<td>{$kStrings["Empty"]}</td>\n";
				$htmlGC .= "<td class=\"mass\">";
				$htmlGC .= ($gcData->dryEmpty->mass > 0) ? $gcData->dryEmpty->mass : "";
				$htmlGC .= "</td>\n";
				$htmlGC .= "<td class=\"unavailable\"></td>\n";
				$htmlGC .= "<td class=\"moment\">";
				$htmlGC .= ($gcData->dryEmpty->getMoment() > 0) ? $gcData->dryEmpty->getMoment() : "";
				$htmlGC .= "</td>\n";
				$htmlGC .= "</tr>\n";
			//
				// front
				$htmlGC .= "<tr>\n";
				$htmlGC .= "<td>{$kStrings["Front"]}</td>\n";
				$htmlGC .= "<td class=\"mass\">";
				$htmlGC .= ($gcData->front->mass > 0) ? $gcData->front->mass : "";
				$htmlGC .= "</td>\n";
				$htmlGC .= "<td class=\"arm\">";
				$htmlGC .= ($gcData->front->getArm() > 0) ? $gcData->front->getArm() : "";
				$htmlGC .= "</td>\n";
				$htmlGC .= "<td class=\"moment\">";
				$htmlGC .= ($gcData->front->getMoment() > 0) ? $gcData->front->getMoment() : "";
				$htmlGC .= "</td>\n";
				$htmlGC .= "</tr>\n";

			// rears...
			$rearsCount = count($gcData->rears);
			for ($i = 0; $i < $rearsCount; ++$i) {
				if($gcData->rears[$i]->getArm() > 0 || $gcData->front->getArm() == 0) {
					$htmlGC .= "<tr>\n";
					$htmlGC .= "<td>" . $kStrings["Rear"] . " #" . ($i + 1) . "</td>\n";
					$htmlGC .= "<td class=\"mass\">";
					$htmlGC .= ($gcData->rears[$i]->mass > 0) ? $gcData->rears[$i]->mass : "";
					$htmlGC .= "</td>\n";
					$htmlGC .= "<td class=\"arm\">";
					$htmlGC .= ($gcData->rears[$i]->getArm() > 0) ? $gcData->rears[$i]->getArm() : "";
					$htmlGC .= "</td>\n";
					$htmlGC .= "<td class=\"moment\">";
					$htmlGC .= ($gcData->rears[$i]->getMoment() > 0) ? $gcData->rears[$i]->getMoment() : "";
					$htmlGC .= "</td>\n";
					$htmlGC .= "</tr>\n";
				}
			}

			// TODO do we check somewhere for max luggage mass?
			// luggages...
			$luggagesCount = count($gcData->luggages);
			for ($i = 0; $i < $luggagesCount; ++$i) {
				if($gcData->luggages[$i]->getArm() > 0 || $gcData->front->getArm() == 0) {
					$htmlGC .= "<tr>\n";
					$htmlGC .= "<td>" . $kStrings["Luggage"] . " #" . ($i + 1) . "</td>\n";
					$htmlGC .= "<td class=\"mass\">";
					$htmlGC .= ($gcData->luggages[$i]->mass > 0) ? $gcData->luggages[$i]->mass : "";
					$htmlGC .= "</td>\n";
					$htmlGC .= "<td class=\"arm\">";
					$htmlGC .= ($gcData->luggages[$i]->getArm() > 0) ? $gcData->luggages[$i]->getArm() : "";
					$htmlGC .= "</td>\n";
					$htmlGC .= "<td class=\"moment\">";
					$htmlGC .= ($gcData->luggages[$i]->getMoment() > 0) ? $gcData->luggages[$i]->getMoment() : "";
					$htmlGC .= "</td>\n";
					$htmlGC .= "</tr>\n";
				}
			}
			// unusable fuels...
			$unusablesCount = count($gcData->fuelUnusables);
			for ($i = 0; $i < $unusablesCount; ++$i) {
				if($gcData->fuelUnusables[$i]->getArm() > 0) {
					$htmlGC .= "<tr>\n";
					$htmlGC .= "<td>" . $kStrings["UnusableFuel"] . " #" . ($i + 1) . "</td>\n";
					$htmlGC .= "<td class=\"mass\">";
					$htmlGC .= ($gcData->fuelUnusables[$i]->mass > 0) ? $gcData->fuelUnusables[$i]->mass : "";
					$htmlGC .= "</td>\n";
					$htmlGC .= "<td class=\"arm\">";
					$htmlGC .= ($gcData->fuelUnusables[$i]->getArm() > 0) ? $gcData->fuelUnusables[$i]->getArm() : "";
					$htmlGC .= "</td>\n";
					$htmlGC .= "<td class=\"moment\">";
					$htmlGC .= ($gcData->fuelUnusables[$i]->getMoment() > 0) ? $gcData->fuelUnusables[$i]->getMoment() : "";
					$htmlGC .= "</td>\n";
					$htmlGC .= "</tr>\n";
				}
			}
			//
			$redBG = ' style="background-color: red;"';

				// 0-fuel
				$gcMinStyle = ($gcData->gcBoundaries->min > 0 && $gcData->zeroFuel->getArm() < $gcData->gcBoundaries->min) ? $redBG : "";
				$gcMaxStyle = ($gcData->gcBoundaries->max > 0 && $gcData->zeroFuel->getArm() > $gcData->gcBoundaries->max) ? $redBG : "";

				// Special: color red o-fuel mass if Take-off mass is more than maxLdgW
				$mldgwStyle  = ($gcData->maxLdgW  > 0 && $gcData->takeOff->mass > $gcData->maxLdgW) ? $redBG : "";

				$htmlGC .= "<tr>\n";
				$htmlGC .= "<td rowspan=\"3\" class=\"GCend GCtitle\">{$kStrings["ZeroFuel"]}</td>\n";
				$htmlGC .= "<td class=\"GCend GCmin\"$mldgwStyle></td>\n";
				$htmlGC .= "<td class=\"GCend GCmin\"$gcMinStyle>min:&nbsp;";
				$htmlGC .= ($gcData->gcBoundaries->min == 0) ? "&nbsp;&nbsp;&nbsp;" : $gcData->gcBoundaries->min;
				$htmlGC .= "</td>\n";
				$htmlGC .= "<td rowspan=\"3\" class=\"moment\">";
				$htmlGC .= ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? $gcData->zeroFuel->getMoment() : "";
				$htmlGC .= "</td>\n";
				$htmlGC .= "</tr>\n";

				$htmlGC .= "<tr>\n";
				$htmlGC .= "<td class=\"mass GCend GCmid\"$mldgwStyle>";
				$htmlGC .= ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? $gcData->zeroFuel->mass : "";
				$htmlGC .= "</td>\n";
				$htmlGC .= "<td class=\"arm GCend GCmid\"$gcMinStyle$gcMaxStyle>";
				$htmlGC .= ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? "&nbsp;&nbsp;{$gcData->zeroFuel->getArm()}" : "";
				$htmlGC .= "</td>\n";
				$htmlGC .= "</tr>\n";

				$htmlGC .= "<tr>\n";
				$htmlGC .= "<td class=\"GCend GCmax\"$mldgwStyle></td>\n";
				$htmlGC .= "<td class=\"GCend GCmax\"$gcMaxStyle>max:&nbsp;";
				$htmlGC .= ($gcData->gcBoundaries->max == 0) ? "&nbsp;&nbsp;&nbsp;" : $gcData->gcBoundaries->max;
				$htmlGC .= "</td>\n";
				$htmlGC .= "</tr>\n";
			// fuels...
			$quantitiesCount = count($gcData->fuelQuantities);
			for ($i = 0; $i < $quantitiesCount; ++$i) {
				if($gcData->fuelQuantities[$i]->getArm() > 0) {
					$htmlGC .= "<tr>\n";
					$htmlGC .= "<td>" . $kStrings["Fuel"] . " #" . ($i + 1) . "</td>\n";
					$htmlGC .= "<td class=\"mass\">";
					$htmlGC .= ($gcData->fuelQuantities[$i]->mass > 0) ? $gcData->fuelQuantities[$i]->mass : "";
					$htmlGC .= "</td>\n";
					$htmlGC .= "<td class=\"arm\">";
					$htmlGC .= ($gcData->fuelQuantities[$i]->getArm() > 0) ? $gcData->fuelQuantities[$i]->getArm() : "";
					$htmlGC .= "</td>\n";
					$htmlGC .= "<td class=\"moment\">";
					$htmlGC .= ($gcData->fuelQuantities[$i]->getMoment() > 0) ? $gcData->fuelQuantities[$i]->getMoment() : "";
					$htmlGC .= "</td>\n";
					$htmlGC .= "</tr>\n";
				}
			}
			//
				// Take-off
				$gcMinStyle = ($gcData->gcBoundaries->min > 0 && $gcData->takeOff->getArm() < $gcData->gcBoundaries->min) ? $redBG : "";
				$gcMaxStyle = ($gcData->gcBoundaries->max > 0 && $gcData->takeOff->getArm() > $gcData->gcBoundaries->max) ? $redBG : "";
				$mtowStyle  = ($gcData->maxTOW > 0 && $gcData->takeOff->mass > $gcData->maxTOW) ? $redBG : "";
				$htmlGC .= "<tr>\n";
				$htmlGC .= "<td rowspan=\"3\" class=\"GCend GCtitle\">Take-off</td>\n";
				$htmlGC .= "<td class=\"mass GCend GCmin\"$mtowStyle>";
				$htmlGc .= (gcData->maxLdgW > 0) ? "MLDGW={$gcData->maxLdgW}" : "";
				$htmlGc .= "</td>\n";
				$htmlGC .= "<td class=\"GCend GCmin\"$gcMinStyle>min:&nbsp;";
				$htmlGC .= ($gcData->gcBoundaries->min == 0) ? "&nbsp;&nbsp;&nbsp;" : $gcData->gcBoundaries->min;
				$htmlGC .= "</td>\n";
				$htmlGC .= "<td rowspan=\"3\" class=\"moment\">";
				$htmlGC .= ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? $gcData->takeOff->getMoment() : "";
				$htmlGC .= "</td>\n";
				$htmlGC .= "</tr>\n";

				$htmlGC .= "<tr>\n";
				$htmlGC .= "<td class=\"mass GCend GCmid\"$mtowStyle>";
				$htmlGC .= ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? $gcData->takeOff->mass : "";
				$htmlGC .= "</td>\n";
				$htmlGC .= "<td class=\"arm GCend GCmid\"$gcMinStyle$gcMaxStyle>";
				$htmlGC .= ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? "&nbsp;&nbsp;{$gcData->takeOff->getArm()}" : "";
				$htmlGC .= "</td>\n";
				$htmlGC .= "</tr>\n";

				$htmlGC .= "<tr>\n";
				$htmlGC .= "<td class=\"GCend GCmax\"$mtowStyle>max:";
				$htmlGC .= ($gcData->maxTOW > 0) ? $gcData->maxTOW : "";
				$htmlGC .= "</td>\n";
				$htmlGC .= "<td class=\"GCend GCmax\"$gcMaxStyle>max:&nbsp;";
				$htmlGC .= ($gcData->gcBoundaries->max == 0) ? "&nbsp;&nbsp;&nbsp;" : $gcData->gcBoundaries->max;
				$htmlGC .= "</td>\n";
				$htmlGC .= "</tr>\n";
			//
			$htmlGC .= "</table>\n";
			$htmlGC .= "<div>1 {$kStrings["USG"]} = {$kFuelUnits["USG"]} liters</div>\n";
			$htmlGC .= "<div>1 {$kStrings["ImpG"]} = {$kFuelUnits["ImpG"]} liters</div>\n";
			$htmlGC .= "<div>1 l {$kStrings["Avgas"]} = {$kFuelTypes["AVGAS"]} kg</div>\n";
			$htmlGC .= "<div>1 {$kStrings["USG"]} {$kStrings["Avgas"]} = $usgAvgas2lbs lbs</div>\n";
			$htmlGC .= "</div>\n";

			return $htmlGC;
		}
	//
		// LaTeX GC
		function LaTeXGC($gcData) {
			global $kStrings;

			$latexGC = "";
			$redCell  = "\\RedCell";
			$grayCell = "\\Gray";

				// GC fold
				$latexGC .= "% {$kStrings["WeightAndBalance"]} {{{\n";
				$latexGC .= "{\\Large\n";
				$latexGC .= "\\begin{center}\n";
			//
				// Begin table
				$latexGC .= "\\begin{tabular}{|l|r|r|r|}\n";
				$latexGC .= "\\hhline{~---}\n";
				$latexGC .= "\\multicolumn{1}{c|}{}\n";
				$latexGC .= "& \\multicolumn{1}{c|}{{$kStrings["Mass"]}}\n";
				$latexGC .= "& \\multicolumn{1}{c|}{{$kStrings["Arm"]}}\n";
				$latexGC .= "& \\multicolumn{1}{c|}{{$kStrings["Moment"]}}\n";
				$latexGC .= "\\\\\n";
			//
				// head
				$latexGC .= "\\multicolumn{1}{c|}{}\n";
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
			//
				// front
				$latexGC .= $kStrings["Front"] . "   &";
				$latexGC .= ($gcData->front->mass > 0) ? $gcData->front->mass : "";
				$latexGC .= "&";
				$latexGC .= ($gcData->front->getArm() > 0) ? " {$gcData->front->getArm()} " : "";
				$latexGC .= "&";
				$latexGC .= ($gcData->front->getMoment() > 0) ? " {$gcData->front->getMoment()} " : "";
				$latexGC .= "\\\\\\hline\n";

			// rears...
			$rearsCount = count($gcData->rears);
			for ($i = 0; $i < $rearsCount; ++$i) {
				if($gcData->rears->getArm() > 0 || $gcData->front->getArm() == 0) {
					$latexGC .= $kStrings["Rear"] . " #" . ($i + 1) . "  &";
					$latexGC .= ($gcData->front->mass > 0) ? " {$gcData->rears[$i]->mass} " : "";
					$latexGC .= "&";
					$latexGC .= ($gcData->rears[$i]->getArm() > 0) ? " {$gcData->rears[$i]->getArm()} " : "";
					$latexGC .= "&";
					$latexGC .= ($gcData->rears[$i]->getMoment() > 0) ? " {$gcData->rears[$i]->getMoment()} " : "";
					$latexGC .= "\\\\\\hline\n";
				}
			}

			// luggages...
			$luggagesCount = count($gcData->luggages);
			for ($i = 0; $i < $luggagesCount; ++$i) {
				if($gcData->luggages[$i]->getArm() > 0) {
					$latexGC .= $kStrings["Luggage"] . " #" . ($i + 1) . " &";
					if($gcData->front->mass > 0) {
						$latexGC .= " {$gcData->luggages[$i]->mass} ";
					}
					$latexGC .= "&";
					if($gcData->luggages[$i]->getArm() > 0) {
						$latexGC .= " {$gcData->luggages[$i]->getArm()} ";
					}
					$latexGC .= "&";
					if($gcData->luggages[$i]->getMoment() > 0) {
						$latexGC .= " {$gcData->luggages[$i]->getMoment()} ";
					}
					$latexGC .= "\\\\\\hline\n";
				}
			}
			//
				// unusable fuels...
			$unusablesCount = count($gcData->fuelUnusables);
			for ($i = 0; $i < $unusablesCount; ++$i) {
				if($gcData->fuelUnusables[$i]->getArm() > 0) {
					$latexGC .= $kStrings["UnusableFuel"] . " #" . ($i + 1) . "";
					$latexGC .= " & {$gcData->fuelUnusables[$i]->mass}";
					$latexGC .= " & {$gcData->fuelUnusables[$i]->getArm()}";
					$latexGC .= " & {$gcData->fuelUnusables[$i]->getMoment()}";
					$latexGC .= "\\\\\\hline\n";
				}
			}
			$latexGC .= "\\hline\n";

				// 0-fuel
				$gcMinStyle = ($gcData->gcBoundaries->min > 0 && $gcData->zeroFuel->getArm() < $gcData->gcBoundaries->min) ? $redCell : $grayCell;
				$gcMaxStyle = ($gcData->gcBoundaries->max > 0 && $gcData->zeroFuel->getArm() > $gcData->gcBoundaries->max) ? $redCell : $grayCell;
				$gcStyle = (
					($gcData->gcBoundaries->min > 0 && $gcData->zeroFuel->getArm() < $gcData->gcBoundaries->min)
					|| ($gcData->gcBoundaries->max > 0 && $gcData->zeroFuel->getArm() > $gcData->gcBoundaries->max)
				) ? $redCell : $grayCell;

				// Special: color red o-fuel mass if Take-off mass is more than maxLdgW
				$mldgwStyle = $grayCell;
				if ($gcData->maxLdgW > 0 && $gcData->takeOff->mass > $gcData->maxLdgW) {
					$mldgwStyle = $redCell;
				}

				$latexGC .= "\\multirow{3}{*}{\\textbf{{$kStrings["ZeroFuel"]}}}\n";
				$latexGC .= "& $mldgwStyle\n";
				$latexGC .= "& $gcMinStyle {\\normalsize min: ";
				$latexGC .= ($gcData->gcBoundaries->min == 0) ? "\phantom{ooooo}" : $gcData->gcBoundaries->min;
				$latexGC .= "}\n";
				$latexGC .= "&\\\\\n";

				$latexGC .= "& $mldgwStyle";
				$latexGC .= ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? " {$gcData->zeroFuel->mass}" : "";
				$latexGC .= "\n";
				$latexGC .= "& $gcStyle";
				$latexGC .= ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? " {$gcData->zeroFuel->getArm()}" : "";
				$latexGC .= "\n";
				$latexGC .= "&";
				$latexGC .= ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? " {$gcData->zeroFuel->getMoment()}" : "";
				$latexGC .= "\\\\\n";

				$latexGC .= "& $mldgwStyle\n";
				$latexGC .= "& $gcMaxStyle {\\normalsize max: ";
				$latexGC .= ($gcData->gcBoundaries->max == 0) ? "\phantom{ooooo}" : $gcData->gcBoundaries->max;
				$latexGC .= "}\n";
				$latexGC .= "&\\\\\n";
				$latexGC .= "\\hhline{====}\n";
			//
			// fuels...
			$quantitiesCount = count($gcData->fuelQuantities);
			for ($i = 0; $i < $quantitiesCount; ++$i) {
				if($gcData->fuelQuantities[$i]->getArm() > 0) {
					$latexGC .= $kStrings["Fuel"] . " #" . ($i + 1) . " &";
					$latexGC .= ($gcData->fuelQuantities[$i]->mass > 0) ? " {$gcData->fuelQuantities[$i]->mass} " : "";
					$latexGC .= "&";
					$latexGC .= ($gcData->fuelQuantities[$i]->getArm() > 0) ? " {$gcData->fuelQuantities[$i]->getArm()} " : "";
					$latexGC .= "&";
					$latexGC .= ($gcData->fuelQuantities[$i]->getMoment() > 0) ? " {$gcData->fuelQuantities[$i]->getMoment()} " : "";
					$latexGC .= "\\\\\\hhline{====}\n";
				}
			}
			//
				// T-off
				$gcMinStyle = ($gcData->gcBoundaries->min > 0 && $gcData->takeOff->getArm() < $gcData->gcBoundaries->min) ? $redCell : $grayCell;
				$gcMaxStyle = ($gcData->gcBoundaries->max > 0 && $gcData->takeOff->getArm() > $gcData->gcBoundaries->max) ? $redCell : $grayCell;
				$gcStyle = (
					($gcData->gcBoundaries->min > 0 && $gcData->takeOff->getArm() < $gcData->gcBoundaries->min)
					|| ($gcData->gcBoundaries->max > 0 && $gcData->takeOff->getArm() > $gcData->gcBoundaries->max)
				) ? $redCell : $grayCell;

				$mtowStyle = ($gcData->maxTOW > 0 && $gcData->takeOff->mass > $gcData->maxTOW) ? $redCell : $grayCell;

				$latexGC .= "\\multirow{3}{*}{\\textbf{{$kStrings["TakeOff"]}}}\n";
				$latexGC .= "& $mtowStyle";
				$latexGC .= ($gcData->maxLdgW > 0) ? " {\\normalsize MLDGW={$gcData->maxLdgW}}" : "";
				$latexGC .= "\n";
				$latexGC .= "& $gcMinStyle {\\normalsize min: ";
				$latexGC .= ($gcData->gcBoundaries->min == 0) ? "\phantom{ooooo}" : $gcData->gcBoundaries->min;
				$latexGC .= "}\n";
				$latexGC .= "&\\\\\n";

				$latexGC .= "& $mtowStyle";
				$latexGC .= ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? " {$gcData->takeOff->mass}" : "";
				$latexGC .= "\n";
				$latexGC .= "& $gcStyle";
				$latexGC .= ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? " {$gcData->takeOff->getArm()}" : "";
				$latexGC .= "\n";
				$latexGC .= "&";
				$latexGC .= ($gcData->dryEmpty->mass > 0 && $gcData->front->mass > 0) ? " {$gcData->takeOff->getMoment()}" : "";
				$latexGC .= "\\\\\n";

				$latexGC .= "& $mtowStyle {\\normalsize max: ";
				$latexGC .= ($gcData->maxTOW > 0) ? $gcData->maxTOW : "\\phantom{1000}";
				$latexGC .= "}\n";
				$latexGC .= "& $gcMaxStyle {\\normalsize max: ";
				$latexGC .= ($gcData->gcBoundaries->max == 0) ? "\phantom{ooooo}" : $gcData->gcBoundaries->max;
				$latexGC .= "}\n";
				$latexGC .= "&\\\\\n";
			//
				// End table
				$latexGC .= "\\hline\n";
				$latexGC .= "\\end{tabular}\n";
				$latexGC .= "\\end{center}\n";
			return $latexGC;
		}
//

	// nav details
	$navid = $_GET["id"];
	if($navid == 0) {
		$filename = "nav/navTemplate";
		$latexfile = fopen("$filename.tex", "w") or die(" Cannot write file $filename.tex");
		$latexhead = LaTeXheader($docVersion, $plane);
		fwrite($latexfile, $latexhead);

		$latexheadend = LaTeXheaderEnd();

		// beginning of second page
		$latex2_1 = LaTeX2left();

		// change to 2nd column
		$latex2_2 = LaTeX2right();

		// end of LaTeX
		$latexend = LaTeXend();
		$row = LaTeXfirstRow(NULL);
		$rows += $row->inc;
		$latexcontent .= $row->contents;
		$latexcontent .= LaTeXfinish1(0, $rows, $maxRow);
		$latexfuel = LaTeXfuel($page, new FuelRequirements(), new FuelRequirements());
		$latexGC = LaTeXGC($gcData);
		fwrite($latexfile, $latexcontent);
		fwrite($latexfile, $latexheadend);
		fwrite($latexfile, $latex2_1);
		fwrite($latexfile, $latexfuel);
		fwrite($latexfile, $latex2_2);
		fwrite($latexfile, $latexGC);
		fwrite($latexfile, $latexend);

		fclose($latexfile);
		// download it??? or link on index?
		$page->htmlHelper->headerLocation();
	}

	$tot = $page->dbHelper->getCount($TABLE, $navid);
	if($tot == 0) {
		$page->htmlHelper->headerLocation();
	}

	$filename = getNavFilename($navid);
	$nav = $page->dbHelper->selectId($TABLE, $navid);

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
	); // TODO adapt SQL

	$nav->fetch();
	$nav->close();
//
	// gohome and make title
	$body = $page->bodyHelper->goHome("..");
	$body .= $page->htmlHelper->setTitle("Nav: $name");// before HotBooty
	$page->htmlHelper->hotBooty();
//
	// heads
	$body .= "<div class=\"wide\">\n";
	$body .= "<div class=\"lhead\">\n";
	//if(file_exists("$filename.tex")) {
		$body .= "<a href=\"$filename.tex\" title=\"$name LaTeX\">{$page->textHelper->strLaTeX}</a>\n";
		if(file_exists("$filename.pdf")) {
			$body .= "<br />\n";
			$body .= "<a href=\"$filename.pdf\" title=\"$name PDF\">PDF</a>\n";
		}
	//}
	$body .= "</div>\n";
	$body .= "<div class=\"chead\">\n";
	$body .= "</div>\n";
	$body .= "<div class=\"rhead\">\n";
	if($bIsAdmin) {
		$body .= "<a href=\"insert.php?id=$navid\" title=\"edit $name\">edit</a>\n";
		$body .= "<br /><a href=\"insert.php\" title=\"new\">new</a>\n";
		$body .= "<br /><a href=\"display.php?dup=$navid\" title=\"duplicate\">duplicate</a>\n";
		$body .= "<br /><a href=\"delete.php?id=$navid\" title=\"delete all WP\">delete all WP</a>\n";
	}
	$body .= "</div>\n";
	$body .= "</div>\n";
//
// Fuel data
$theoricFuel = new FuelRequirements();
//
	// plane details
	if($plane->sqlID > 0) {
		$theplane = $page->dbHelper->selectId("aircrafts", $plane->sqlID);
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

			$gcData->rears[0]->arm,
			$gcData->rears[1]->arm,

			$gcData->luggages[0]->arm,
			$gcData->luggages[0]->maxMass,
			$gcData->luggages[1]->arm,
			$gcData->luggages[1]->maxMass,
			$gcData->luggages[2]->arm,
			$gcData->luggages[2]->maxMass,
			$gcData->luggages[3]->arm,
			$gcData->luggages[3]->maxMass,

			$gcData->luggageMaxTotalMass,

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
			$theoricFuel->fuelUnit,
			$theoricFuel->fuelType
		);
		$theplane->fetch();
		$theplane->close();

		if($gcData->massUnit   == "") {$gcData->massUnit   = "?";}
		if($gcData->armUnit    == "") {$gcData->armUnit    = "?";}
		if($gcData->momentUnit == "") {$gcData->momentUnit = "?";}
		if($theoricFuel->fuelUnit == "") {$theoricFuel->fuelUnit = "?";}
		if($theoricFuel->fuelType == "") {$theoricFuel->fuelType = "?";}

		// apply mass unit
		$gcData->propagateMassUnit();
		$gcData->propagateFuelData();
		$theoricFuel->propagateFuelData();
	}
//
	// prepare LaTeX content
	$latexhead = LaTeXheader($docVersion, $plane, $name, $variation);
	$latexheadend = LaTeXheaderEnd();

	// beginning of second page
	$latex2_1 = LaTeX2left();

	// change to 2nd column
	$latex2_2 = LaTeX2right();

	// end of LaTeX
	$latexend = LaTeXend();
//

$body .= htmlHeader($name, $plane, $variation, $gcData, $bIsAdmin);

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
$bHasWind = false;

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

$wp = $page->dbHelper->queryManage("SELECT * FROM `{$page->dbHelper->dbName}`.`NavWaypoints` WHERE `NavID` = $navid ORDER BY `WPnum` ASC");
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
			if($wpNum == $kWaypoints->wayOut->start || $bHasWind) {
				$bHasWind = true;
				$oldWindTC = $windTC;
				$oldWindSpeed = $windSpeed;

			} else {
				$windTC = 0;
				$windSpeed = 0;
			}

		} elseif($bHasWind && $windTC == 0) {
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

		if($bHasWind) {
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

			if($bHasWind) {
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
		$rowArgs->bHasWind = $bHasWind;
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
		$rowArgs->bIsAdmin = $bIsAdmin;
	//
	// display to page
	$body .= htmlRowAny($rowArgs);

	// LaTeX
	$row = LaTeXrowAny($rowArgs);

	$latexcontent .= $row->contents;

	$rows += $row->inc;
	if($rows > $maxRow) {
		$warning = "<div class=\"warning\">Number of rows is large and table will span on more than one A4.</div>\n";
	}
}
$wp->close();
// note for self: theoricTripTime and theoricAlternateTime are now ready


if($bIsAdmin) {
	// option to insert new WP
	$body .= "<tr>\n";
	$body .= "<td colspan=\"15\" class=\"newWP\">\n";
	$body .= "<a href=\"waypoint.php?nav=$navid\" title=\"new waypoint\">new waypoint</a>\n";
	$body .= "</td>\n";
	$body .= "</tr>\n";
}
$body .= "</table>\n";
$body .= $warning;
$body .= "</div>\n";

// finish latex content 1st page with empty rows
$latexcontent .= LaTeXfinish1($wpNum, $rows, $maxRow - int($roundTrip));  // round-trip uses more rows
// TODO check if round trip rows are all accounted


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
	$body .= "</div>\n";
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
	$body .= "</div>\n";
//

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
	// display
	$body .= htmlFuel($page, $theoricFuel, $realFuel);

	// LaTeX
	$latexfuel = LaTeXfuel($page, $theoricFuel, $realFuel);


//
	// GC MTOW (also takes care of mass conversion if required)
	$gcData->computeZeroFuelData();
	$gcData->computeTakeOffData();

	// display
	$body .= htmlGC($gcData);

	// LaTeX
	$latexGC = LaTeXGC($gcData);

//
	// Write LaTeX file
	$latexfile = fopen("$filename.tex", "w") or die(" Cannot write file $filename.tex");

	fwrite($latexfile, $latexhead);
	fwrite($latexfile, $latexcontent);
	fwrite($latexfile, $latexheadend);
	fwrite($latexfile, $latex2_1);
	fwrite($latexfile, $latexfuel);
	fwrite($latexfile, $latex2_2);
	fwrite($latexfile, $latexGC);
	fwrite($latexfile, $latexend);

	fclose($latexfile);
//
echo $body;
unset($page);
?>
