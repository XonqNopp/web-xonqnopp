<?php
/*** Created: Thu 2014-05-08 10:26:07 CEST
 * Previous major version in revision 416
 ***
 *** TODO:
 ***
 ***/
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->InitDB();
$page->js_Form();
//$page->initHTML();
//$page->LogLevelUp(6);

function displaySQLtime($page, $item) {
	return $page->minutesDisplay(
		$item->sSEP
		+ $item->sMEP
		+ $item->sMP
		);
}

$page->CSS_ppJump();

$body = "";
$deleted = "";
$SPMP_list = array("SP_SEP" => "SP SEP", "SP_MEP" => "SP MEP", "MP" => "MP");
$function_list = array("PIC" => "PIC", "copi" => "copi", "dual" => "dual", "instructor" => "instructor");
$formsize = 3;
$AD_list = array("LSGE", "LSGS", "note");
$type_list = array();
$ID_list = array();
$PIC_list = array("Induni", "Berger", "Berchtold");
$defaults = array();
$defaults["type"] = "";
$defaults["ID"] = "";
$defaults["AD"] = "";

$DueDay = "30";
$DueMonth = 9;
$DueYear = $page->GetNow()->year;
if($DueYear % 2 == 1) {
	$DueYear += 1;
}

	// setting default values
	$FORM_date = "";
	$FORM_start_time = "";
	$FORM_stop_time = "";
	$FORM_start_ad = "";
	$FORM_stop_ad = "";
	$FORM_aircraft = "";
	$FORM_identification = "";
	$FORM_SPMP = "";
	$SQL_SP_SEP = 0;
	$SQL_SP_MEP = 0;
	$SQL_MP = 0;
	$FORM_PIC = "Induni";
	$FORM_landings_day = 1;
	$FORM_landings_night = 0;
	$FORM_night_time = 0;
	$FORM_IFR_time = 0;
	$FORM_function = "";
	$FORM_PIC_time = true;
	$SQL_PIC_time = 0;
	$FORM_copi_time = false;
	$SQL_copi_time = 0;
	$FORM_dual_time = false;
	$SQL_dual_time = 0;
	$FORM_instructor_time = false;
	$SQL_instructor_time = 0;
	$FORM_notes = "";

$UserIsAdmin = $page->UserIsAdmin();
//
if($UserIsAdmin) {
	if(isset($_POST["add"])) {
		/*** FORM values ***/
		$FORM_year = sprintf("%04d", $_POST["date_year"]);
		$FORM_month = sprintf("%02d", $_POST["date_month"]);
		$FORM_day = sprintf("%02d", $_POST["date_day"]);
		$FORM_date = "$FORM_year-$FORM_month-$FORM_day";
		//
		$FORM_start_time_hour = $_POST["start_time_hour"];
		$FORM_start_time_minute = $_POST["start_time_minute"];
		$FORM_start_time = sprintf("%02d", $FORM_start_time_hour) . ":" . sprintf("%02d", $FORM_start_time_minute) . ":00";
		//
		$FORM_stop_time_hour = $_POST["stop_time_hour"];
		$FORM_stop_time_minute = $_POST["stop_time_minute"];
		$FORM_stop_time = sprintf("%02d", $FORM_stop_time_hour) . ":" . sprintf("%02d", $FORM_stop_time_minute) . ":00";
		//
		$delta = ($FORM_stop_time_hour * 60 + $FORM_stop_time_minute) - ($FORM_start_time_hour * 60 + $FORM_start_time_minute);
		if($delta < 0) {
			$delta += (24 * 60);
		}
		//
		$FORM_start_ad = $page->field2SQL($_POST["start_ad"]);
		$FORM_stop_ad = $page->field2SQL($_POST["stop_ad"]);
		if($FORM_stop_ad == "") {
			$FORM_stop_ad = $FORM_start_ad;
		}
		$FORM_aircraft = $page->field2SQL($_POST["aircraft"]);
		$FORM_identification = $page->field2SQL($_POST["identification"]);
		$FORM_SPMP = $_POST["SPMP"];
		switch($_POST["SPMP"]) {
		case "SP_SEP":
			$SQL_SP_SEP = $delta;
			break;
		case "SP_MEP":
			$SQL_SP_MEP = $delta;
			break;
		case "MP":
			$SQL_MP = $delta;
			break;
		default:
			$page->FatalError("If this message is displayed, you are trying to introduce malicious content to this website");
			break;
		}
		$FORM_PIC = $page->field2SQL($_POST["PIC"]);
		$FORM_landings_day = $page->field2SQL($_POST["landings_day"]);
		$FORM_landings_day += 0;
		$FORM_landings_night = $page->field2SQL($_POST["landings_night"]);
		$FORM_landings_night += 0;
		$FORM_night_time = $page->field2SQL($_POST["night_time"]);
		$FORM_night_time += 0;
		$FORM_IFR_time = $page->field2SQL($_POST["IFR_time"]);
		$FORM_IFR_time += 0;
		if($FORM_night_time > $delta || $FORM_IRF_time > $delta) {
			$page->ln_3(3, "Night and IFR conditions cannot exceed global time, setting max");
			if($FORM_night_time > $delta) {
				$FORM_night_time = $delta;
			}
			if($FORM_IFR_time > $delta) {
				$FORM_IFR_time = $delta;
			}
		}
		$FORM_function = $_POST["function"];
		switch($_POST["function"]) {
		case "PIC":
			$FORM_PIC_time = true;
			$SQL_PIC_time = $delta;
			break;
		case "copi":
			$FORM_copi_time = true;
			$SQL_copi_time = $delta;
			break;
		case "dual":
			$FORM_dual_time = true;
			$SQL_dual_time = $delta;
			break;
		case "instructor":
			$FORM_instructor_time = true;
			$SQL_instructor_time = $delta;
			break;
		default:
			$page->FatalError("If this message is displayed, you are trying to introduce malicious content to this website");
			break;
		}
		$FORM_notes = $page->field2SQL($_POST["notes"]);
		$insert = $page->DB_QueryPrepare("INSERT INTO " . $page->ddb->DBname . " . `PilotLogbook` (`id`, `date`, `start_time`, `stop_time`, `start_ad`, `stop_ad`, `aircraft`, `identification`, `SP_SEP`, `SP_MEP`, `MP`, `PIC`, `landings_day`, `landings_night`, `night_time`, `IFR_time`, `PIC_time`, `copi_time`, `dual_time`, `instructor_time`, `notes`) VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
		$insert->bind_param("sssssssiiisiiiiiiiis", $FORM_date, $FORM_start_time, $FORM_stop_time, $FORM_start_ad, $FORM_stop_ad, $FORM_aircraft, $FORM_identification, $SQL_SP_SEP, $SQL_SP_MEP, $SQL_MP, $FORM_PIC, $FORM_landings_day, $FORM_landings_night, $FORM_night_time, $FORM_IFR_time, $SQL_PIC_time, $SQL_copi_time, $SQL_dual_time, $SQL_instructor_time, $FORM_notes);
		$page->DB_ExecuteManage($insert);
		$page->HeaderLocation("logbook.php");
	} elseif(isset($_POST["delete"])) {
		$id = $_POST["delete"];
		$exq = $page->DB_QueryManage("SELECT * FROM `PilotLogbook` WHERE `id` = $id");
		$extry = $exq->fetch_object();
		$exq->close();
		$FORM_date            = $extry->date;
		$start_time      = $extry->start_time;
		$FORM_start_ad        = $extry->start_ad;
		$FORM_stop_ad         = $extry->stop_ad;
		$stop_time       = $extry->stop_time;
		$FORM_start_time = "$FORM_date $start_time";
		$FORM_stop_time = "$FORM_date $stop_time";
		$FORM_aircraft        = $extry->aircraft;
		$FORM_identification  = $extry->identification;
		$SP_SEP          = $extry->SP_SEP;
		$SP_MEP          = $extry->SP_MEP;
		$MP              = $extry->MP;
		$FORM_SPMP = "";
		if($SP_SEP > 0) {
			$FORM_SPMP = "SP_SEP";
		} elseif($SP_MEP > 0) {
			$FORM_SPMP = "SP_MEP";
		} elseif($MP > 0) {
			$FORM_SPMP = "MP";
		}
		$FORM_PIC             = $extry->PIC;
		$FORM_landings_day    = $extry->landings_day;
		$FORM_landings_night  = $extry->landings_night;
		$FORM_night_time      = $extry->night_time;
		$FORM_IFR_time        = $extry->IFR_time;
		$PIC_time        = $extry->PIC_time;
		$copi_time       = $extry->copi_time;
		$dual_time       = $extry->dual_time;
		$instructor_time = $extry->instructor_time;
		if($PIC_time > 0) {
			$FORM_function = "PIC";
		} elseif($copi_time > 0) {
			$FORM_function = "copi";
		} elseif($dual_time > 0) {
			$FORM_function = "dual";
		} elseif($instructor_time > 0) {
			$FORM_function = "instructor";
		}
		$FORM_notes           = $extry->notes;
		//
		$deleted = "\n<!-- Deleted No $id -->\n";
		$deleted .= "<tr id=\"deleted\">\n";
		//
		if($start_ad == "note" && $stop_ad == "note") {
			// make single td colspan with remarks+delete
			$deleted .= "<td colspan=\"21\" style=\"text-align: center;\">\n";
			$deleted .= $notes;
			$deleted .= "</td>\n";
		} else {
			$deleted .= "<td>$FORM_date</td>\n";
			$deleted .= "<td>$FORM_start_ad</td>\n";
			$deleted .= "<td class=\"num\">" . substr($start_time, 0, 5) . "</td>\n";
			$deleted .= "<td class=\"num\">" . substr($stop_time, 0, 5) . "</td>\n";
			$deleted .= "<td>$FORM_stop_ad</td>\n";
			$deleted .= "<td>$FORM_aircraft</td>\n";
			$deleted .= "<td>$FORM_identification</td>\n";
			$deleted .= "<td class=\"num\">" . $page->minutesDisplay($SP_SEP) . "</td>\n";
			$deleted .= "<td class=\"num\">" . $page->minutesDisplay($SP_MEP) . "</td>\n";
			$deleted .= "<td class=\"num\">" . $page->minutesDisplay($MP) . "</td>\n";
			$deleted .= "<td class=\"num\">" . $page->minutesDisplay($SP_SEP + $SP_MEP + $MP) . "</td>\n";
			$deleted .= "<td>$FORM_PIC</td>\n";
			$deleted .= "<td class=\"num\">" . sprintf("%d", $FORM_landings_day) . "</td>\n";
			$deleted .= "<td class=\"num\">" . sprintf("%d", $FORM_landings_night) . "</td>\n";
			$deleted .= "<td class=\"num\">" . $page->minutesDisplay($night_time) . "</td>\n";
			$deleted .= "<td class=\"num\">" . $page->minutesDisplay($IFR_time) . "</td>\n";
			$deleted .= "<td class=\"num\">" . $page->minutesDisplay($PIC_time) . "</td>\n";
			$deleted .= "<td class=\"num\">" . $page->minutesDisplay($copi_time) . "</td>\n";
			$deleted .= "<td class=\"num\">" . $page->minutesDisplay($dual_time) . "</td>\n";
			$deleted .= "<td class=\"num\">" . $page->minutesDisplay($instructor_time) . "</td>\n";
			$deleted .= "<td>$FORM_notes</td>\n";
		}
		$deleted .= "<td>DELETED!</td>\n";
		$deleted .= "</tr>\n";
		$page->DB_QueryManage("DELETE FROM `" . $page->ddb->DBname . "` . `PilotLogbook` WHERE `PilotLogbook` . `id` = $id LIMIT 1;");
	}
}

$args = new stdClass();
$args->rootpage = "..";
$body .= $page->GoHome($args);
if($UserIsAdmin) {
	$body .= $page->FormTag();
}
$body .= "<div class=\"std\">\n";
$body .= $page->SetTitle("My Pilot Logbook");
$page->HotBooty();
//
	//// run through DB to make body of table
		/*** some vars to prepare ***/
		$sum_SP_SEP = 0;
		$sum_SP_MEP = 0;
		$sum_MP = 0;
		//total is $SP_SEP+$SP_MEP+$MP
		$sum_landings_day = 0;
		$sum_landings_night = 0;
		$sum_night = 0;
		$sum_IFR = 0;
		$sum_PIC = 0;
		$sum_copi = 0;
		$sum_dual = 0;
		$sum_instructor = 0;
	//
		/*** run DB ***/
		$tbody = "";
		$tbody .= "<tbody>\n";
		$contents = $page->DB_QueryManage("SELECT * FROM `PilotLogbook` ORDER BY `date` DESC, `start_time` DESC");
		if($contents->num_rows == 0) {
			// colspan all saying nothing in DB
			$tbody .= "<tr>\n";
			$tbody .= "<td colspan=\"22\" class=\"fullnote\">\n";
			$tbody .= "Nothing found in DB\n";
			$tbody .= "</td>\n";
			$tbody .= "</tr>\n";
		} else {
			$onetwo = 0;
			while($entry = $contents->fetch_object()) {
				$onetwo++;
				$id              = $entry->id;
				$date            = $entry->date;
				$start_time      = substr($entry->start_time, 0, 5);
				$start_ad        = $entry->start_ad;
				$stop_ad         = $entry->stop_ad;
				$stop_time       = substr($entry->stop_time, 0, 5);
				$aircraft        = $entry->aircraft;
				$identification  = $entry->identification;
				$SP_SEP          = $entry->SP_SEP;
				$SP_MEP          = $entry->SP_MEP;
				$MP              = $entry->MP;
				$PIC             = $entry->PIC;
				$landings_day    = $entry->landings_day;
				$landings_night  = $entry->landings_night;
				$night_time      = $entry->night_time;
				$IFR_time        = $entry->IFR_time;
				$PIC_time        = $entry->PIC_time;
				$copi_time       = $entry->copi_time;
				$dual_time       = $entry->dual_time;
				$instructor_time = $entry->instructor_time;
				$notes           = $entry->notes;
				//
					//// update data lists
					if($stop_ad != "" && $stop_ad != "note") {
						if(!in_array($stop_ad, $AD_list)) {
							$AD_list[] = $stop_ad;
						}
						if($defaults["AD"] == "") {
							$defaults["AD"] = $stop_ad;
						}
					}
					if($start_ad != "" && $start_ad != "note") {
						if(!in_array($start_ad, $AD_list)) {
							$AD_list[] = $start_ad;
						}
						if($defaults["AD"] == "") {
							$defaults["AD"] = $start_ad;
						}
					}
					if($aircraft != "") {
					   	if(!in_array($aircraft, $type_list)) {
							$type_list[] = $aircraft;
						}
						if($defaults["type"] == "") {
							$defaults["type"] = $aircraft;
						}
					}
					if($identification != "") {
						if(!in_array($identification, $ID_list)) {
							$ID_list[] = $identification;
						}
						if($defaults["ID"] == "") {
							$defaults["ID"] = $identification;
						}
					}
					if($PIC != "" && !in_array($PIC, $PIC_list)) {
						$PIC_list[] = $PIC;
					}
				//
				$tbody .= "\n<!-- No $id -->\n";
				if($start_ad == "note" && $stop_ad == "note") {
					// make single td colspan with remarks+delete
					$tbody .= "<tr>\n";
					$tbody .= "<td colspan=\"21\" class=\"fullnote\">\n";
					$tbody .= $notes;
					$tbody .= "</td>\n";
				} else {
					$sum_SP_SEP         += $SP_SEP;
					$sum_SP_MEP         += $SP_MEP;
					$sum_MP             += $MP;
					$sum_landings_day   += $landings_day;
					$sum_landings_night += $landings_night;
					$sum_night          += $night_time;
					$sum_IFR            += $IFR_time;
					$sum_PIC            += $PIC_time;
					$sum_copi           += $copi_time;
					$sum_dual           += $dual_time;
					$sum_instructor     += $instructor_time;
					//
					$tbody .= "<tr class=\"";
					if($onetwo % 2) {
						$tbody .= "odd";
					} else {
						$tbody .= "even";
					}
					$tbody .= "\">\n";
					$tbody .= "<td class=\"date\">$date</td>\n";
					$tbody .= "<td class=\"start_ad\">$start_ad</td>\n";
					$tbody .= "<td class=\"start_time num\">$start_time</td>\n";
					$tbody .= "<td class=\"stop_time num\">$stop_time</td>\n";
					$tbody .= "<td class=\"stop_ad\">$stop_ad</td>\n";
					$tbody .= "<td class=\"aircraft\">$aircraft</td>\n";
					$tbody .= "<td class=\"identification\">$identification</td>\n";
					$tbody .= "<td class=\"SP_SEP num\">" . $page->minutesDisplay($SP_SEP) . "</td>\n";
					$tbody .= "<td class=\"SP_MEP num\">" . $page->minutesDisplay($SP_MEP) . "</td>\n";
					$tbody .= "<td class=\"MP num\">" . $page->minutesDisplay($MP) . "</td>\n";
					$tbody .= "<td class=\"SP_MP num\">" . $page->minutesDisplay($SP_SEP + $SP_MEP + $MP) . "</td>\n";
					$tbody .= "<td class=\"PICname\">$PIC</td>\n";
					$tbody .= "<td class=\"landings_day num\">" . sprintf("%d", $landings_day) . "</td>\n";
					$tbody .= "<td class=\"landings_night num\">" . sprintf("%d", $landings_night) . "</td>\n";
					$tbody .= "<td class=\"night num\">" . $page->minutesDisplay($night_time) . "</td>\n";
					$tbody .= "<td class=\"IFR num\">" . $page->minutesDisplay($IFR_time) . "</td>\n";
					$tbody .= "<td class=\"PIC num\">" . $page->minutesDisplay($PIC_time) . "</td>\n";
					$tbody .= "<td class=\"copi num\">" . $page->minutesDisplay($copi_time) . "</td>\n";
					$tbody .= "<td class=\"dual num\">" . $page->minutesDisplay($dual_time) . "</td>\n";
					$tbody .= "<td class=\"instructor num\">" . $page->minutesDisplay($instructor_time) . "</td>\n";
					$tbody .= "<td class=\"notes\">$notes</td>\n";
				}
				$tbody .= "<td>";
				if($UserIsAdmin) {
					$tbody .= "<input type=\"submit\" name=\"delete\" value=\"$id\" onclick='return ConfirmErase(\"" . html_entity_decode($page->SQL2field($notes)) . "\")' />";
				}
				$tbody .= "</td>\n";
				$tbody .= "</tr>\n";
			}
		}
		$contents->close();
		$tbody .= "</tbody>\n";
	//
		// head of table
		$thead = "";
		$thead .= "<thead>\n";
			// 1st line
			$thead .= "<tr>\n";
			$thead .= "<th class=\"date\" rowspan=\"2\">date</th>\n";
			$thead .= "<th colspan=\"2\">departure</th>\n";
			$thead .= "<th colspan=\"2\">arrival</th>\n";
			$thead .= "<th colspan=\"2\">aircraft</th>\n";
			$thead .= "<th colspan=\"4\">total flight time</th>\n";
			$thead .= "<th class=\"PICname\" rowspan=\"2\">name of PIC</th>\n";
			$thead .= "<th colspan=\"2\">landings</th>\n";
			$thead .= "<th colspan=\"2\">ops condition time</th>\n";
			$thead .= "<th colspan=\"4\">pilot function time</th>\n";
			$thead .= "<th class=\"notes\" rowspan=\"2\">remarks</th>\n";
			$thead .= "<th rowspan=\"2\"></th>\n";// to prevent warning in validator
			$thead .= "</tr>\n";
		//
			// 2nd line
			$thead .= "<tr>\n";
			$thead .= "<th class=\"start_ad\">AD</th>\n";
			$thead .= "<th class=\"start_time\">time</th>\n";
			$thead .= "<th class=\"stop_time\">time</th>\n";
			$thead .= "<th class=\"stop_ad\">AD</th>\n";
			$thead .= "<th class=\"aircraft\">model</th>\n";
			$thead .= "<th class=\"identification\">registration</th>\n";
			$thead .= "<th class=\"SP_SEP\">SP SEP</th>\n";
			$thead .= "<th class=\"SP_MEP\">SP MEP</th>\n";
			$thead .= "<th class=\"MP\">MP</th>\n";
			$thead .= "<th class=\"SP_MP\">total</th>\n";
			$thead .= "<th class=\"landings_day\">day</th>\n";
			$thead .= "<th class=\"landings_night\">night</th>\n";
			$thead .= "<th class=\"night\">night</th>\n";
			$thead .= "<th class=\"IFR\">IFR</th>\n";
			$thead .= "<th class=\"PIC\">PIC</th>\n";
			$thead .= "<th class=\"copi\">copi</th>\n";
			$thead .= "<th class=\"dual\">dual</th>\n";
			$thead .= "<th class=\"instructor\">instructor</th>\n";
			$thead .= "</tr>\n";
		$thead .= "</thead>\n";
	//
		// foot of table with sums
		$tsum = "";
		$tsum .= "<tr>\n";
		$tsum .= "<th colspan=\"7\"></th>\n";
		$tsum .= "<th class=\"SP_SEP num\">SEP " . $page->minutesDisplay($sum_SP_SEP) . "</th>\n";
		$tsum .= "<th class=\"SP_MEP num\">MEP " . $page->minutesDisplay($sum_SP_MEP) . "</th>\n";
		$tsum .= "<th class=\"MP num\">MP " . $page->minutesDisplay($sum_MP)     . "</th>\n";
		$tsum .= "<th class=\"SP_MP num\">" . $page->minutesDisplay($sum_SP_SEP + $sum_SP_MEP + $sum_MP) . "</th>\n";
		$tsum .= "<th></th>\n";
		$tsum .= "<th class=\"landings_day num\">day $sum_landings_day</th>\n";
		$tsum .= "<th class=\"landings_night num\">night $sum_landings_night</th>\n";
		$tsum .= "<th class=\"night num\">night " . $page->minutesDisplay($sum_night) . "</th>\n";
		$tsum .= "<th class=\"IFR num\">IFR " . $page->minutesDisplay($sum_IFR)   . "</th>\n";
		$tsum .= "<th class=\"PIC num\">PIC " . $page->minutesDisplay($sum_PIC)   . "</th>\n";
		$tsum .= "<th class=\"copi num\">copi " . $page->minutesDisplay($sum_copi)  . "</th>\n";
		$tsum .= "<th class=\"dual num\">dual " . $page->minutesDisplay($sum_dual)  . "</th>\n";
		$tsum .= "<th class=\"instructor num\">inst. " . $page->minutesDisplay($sum_instructor) . "</th>\n";
		$tsum .= "<th colspan=\"2\"></th>\n";
		$tsum .= "</tr>\n";
	//
		// set default values which needed to go through DB
		if($FORM_start_ad == "") {
			$FORM_start_ad = $defaults["AD"];
		}
		if($FORM_aircraft == "") {
			$FORM_aircraft = $defaults["type"];
		}
		if($FORM_identification == "") {
			$FORM_identification = $defaults["ID"];
		}
	//
		//// insert row...
		$tinsert = "";
		if($UserIsAdmin) {
			//// ...only if allowed!
			$tinsert .= "<tr>\n";
				//// Date
				$args = new stdClass();
				$args->type = "Date";
				$args->name = "date";
				$args->value = $FORM_date;
				$args->yearFirst = 2000;
				$args->yearLast = 0;
				$tinsert .= "<td class=\"date\">" . $page->FormField($args) . "</td>\n";
			//
				//// start AD
				$args = new stdClass();
				$args->type = "text";
				$args->name = "start_ad";
				$args->value = $FORM_start_ad;
				$args->size = $formsize;
				$args->ListID = "AD";
				$args->datalist = $AD_list;
				$tinsert .= "<td class=\"start_ad\">" . $page->FormField($args) . "</td>\n";
			//
				//// start time
				$args = new stdClass();
				$args->type = "Time";
				$args->name = "start_time";
				$args->value = $FORM_start_time;
				$args->doSecond = false;
				$tinsert .= "<td class=\"start_time\">" . $page->FormField($args) . "</td>\n";
			//
				//// stop time
				$args = new stdClass();
				$args->type = "Time";
				$args->name = "stop_time";
				$args->value = $FORM_stop_time;
				$args->doSecond = false;
				$tinsert .= "<td class=\"stop_time\">" . $page->FormField($args) . "</td>\n";
			//
				//// stop AD
				$args = new stdClass();
				$args->type = "text";
				$args->name = "stop_ad";
				$args->value = $FORM_stop_ad;
				$args->size = $formsize;
				$args->ListID = "AD";
				$tinsert .= "<td class=\"stop_ad\">" . $page->FormField($args) . "</td>\n";
			//
				//// aircraft type
				$args = new stdClass();
				$args->type = "text";
				$args->name = "aircraft";
				$args->value = $FORM_aircraft;
				$args->size = $formsize;
				$args->ListID = "type_list";
				$args->datalist = $type_list;
				$tinsert .= "<td class=\"aircraft\">" . $page->FormField($args) . "</td>\n";
			//
				//// identification
				$args = new stdClass();
				$args->type = "text";
				$args->name = "identification";
				$args->value = $FORM_identification;
				$args->size = $formsize;
				$args->ListID = "ID_list";
				$args->datalist = $ID_list;
				$tinsert .= "<td class=\"identification\">" . $page->FormField($args) . "</td>\n";
			//
				//// SEP,MEP,MP
				$args = new stdClass();
				$args->type = "select";
				$args->name = "SPMP";
				$args->list = $SPMP_list;
				$args->value = $FORM_SPMP;
				$tinsert .= "<td class=\"SPMP\" colspan=\"3\">" . $page->FormField($args) . "</td>\n";
				$tinsert .= "<td class=\"SP_MP\"></td>\n";
			//
				//// PIC name
				$args = new stdClass();
				$args->type = "text";
				$args->name = "PIC";
				$args->value = $FORM_PIC;
				$args->size = $formsize;
				$args->ListID = "PIClist";
				$args->datalist = $PIC_list;
				$tinsert .= "<td class=\"PICname\">" . $page->FormField($args) . "</td>\n";
			//
				//// landings day
				$args = new stdClass();
				$args->type = "number";
				$args->name = "landings_day";
				$args->value = $FORM_landings_day;
				$args->css = "right";
				$args->min = 0;
				$tinsert .= "<td class=\"landings_day\">" . $page->FormField($args) . "</td>\n";
			//
				//// landings night
				$args->name = "landings_night";
				$args->value = $FORM_landings_night;
				$tinsert .= "<td class=\"landings_night\">" . $page->FormField($args) . "</td>\n";
			//
				//// NVFR
				$args->name = "night_time";
				$args->value = $FORM_night_time;
				$tinsert .= "<td class=\"night\">" . $page->FormField($args) . "</td>\n";
			//
				//// IFR
				$args->name = "IFR_time";
				$args->value = $FORM_IFR_time;
				$tinsert .= "<td class=\"IFR\">" . $page->FormField($args) . "</td>\n";
			//
				//// PIC,COPI,DUAL,INSTRUCTOR
				$args = new stdClass();
				$args->type = "select";
				$args->name = "function";
				$args->list = $function_list;
				$args->value = $FORM_function;
				$tinsert .= "<td class=\"function\" colspan=\"4\">" . $page->FormField($args) . "</td>\n";
			//
				//// notes
				$args = new stdClass();
				$args->type = "text";
				$args->name = "notes";
				$args->value = $FORM_notes;
				$args->size = 0;
				$args->css = "";
				$tinsert .= "<td class=\"notes\">" . $page->FormField($args) . "</td>\n";
			//
				//// submit
				$tinsert .= "<td><input type=\"submit\" name=\"add\" value=\"add\" onclick=\"SubmitForm()\"/></td>\n";
			$tinsert .= "</tr>\n";
		}
	//
		//// build table
		$tab = "";
		$tab .= $thead;
		$tab .= $deleted;
		$tab .= $tinsert;
		$tab .= $tsum;
		$tab .= $tbody;
//
//
$body .= "<div>\n";
$body .= "<p>All times are local time.</p>\n";
$body .= "<p class=\"pl\">Licence number CH.FCL.47359&nbsp;-&nbsp;Initially issued on 2014-09-15</p>\n";
$body .= "<p class=\"pl\">Medical number CH-REF-17156</p>\n";
$body .= "</div>\n";
//
$total_db = $page->DB_QueryManage("SELECT `date`, SUM(SP_SEP) AS `sSEP`, SUM(SP_MEP) AS `sMEP`, SUM(MP) AS `sMP`, SUM(landings_day) AS `sLD`, SUM(landings_night) AS `sLN` FROM `PilotLogbook`");
$pic_db = $page->DB_QueryManage("SELECT `date`, SUM(SP_SEP) AS `sSEP`, SUM(SP_MEP) AS `sMEP`, SUM(MP) AS `sMP`, SUM(landings_day) AS `sLD`, SUM(landings_night) AS `sLN` FROM `PilotLogbook` WHERE `PIC` = 'Induni'");

$AL_db = $page->DB_QueryManage("SELECT SUM(SP_SEP) AS `sSEP`,SUM(SP_MEP) AS `sMEP`,SUM(MP) AS `sMP`,`notes` FROM `PilotLogbook` WHERE `notes` LIKE '%#AnneLaure%'");
$Zoe_db = $page->DB_QueryManage("SELECT SUM(SP_SEP) AS `sSEP`,SUM(SP_MEP) AS `sMEP`,SUM(MP) AS `sMP`,`notes` FROM `PilotLogbook` WHERE `notes` LIKE '%#Zoe%'");
$Ludo_db = $page->DB_QueryManage("SELECT SUM(SP_SEP) AS `sSEP`,SUM(SP_MEP) AS `sMEP`,SUM(MP) AS `sMP`,`notes` FROM `PilotLogbook` WHERE `notes` LIKE '%#Ludovic%'");

$year_db  = $page->DB_QueryManage("SELECT `date`, SUM(SP_SEP) AS `sSEP`, SUM(SP_MEP) AS `sMEP`, SUM(MP) AS `sMP`, SUM(landings_day) AS `sLD`, SUM(landings_night) AS `sLN` FROM `PilotLogbook` WHERE DATEDIFF(CURDATE(),date) <= 365");
$three_db = $page->DB_QueryManage("SELECT `date`, SUM(SP_SEP) AS `sSEP`, SUM(SP_MEP) AS `sMEP`, SUM(MP) AS `sMP`, SUM(landings_day) AS `sLD`, SUM(landings_night) AS `sLN` FROM `PilotLogbook` WHERE DATEDIFF(CURDATE(),date) <= 90");
$night_db = $page->DB_QueryManage("SELECT `date`, SUM(night_time) AS `sNight`, SUM(landings_night) AS `sLN` FROM `PilotLogbook` WHERE DATEDIFF(CURDATE(),date) <= 90");

// revalidation
$DueYearMinusOne = $DueYear - 1;
$DueMonthMinusOne = sprintf("%02d", $DueMonth + 1);
$DueMonth         = sprintf("%02d", $DueMonth);
$DueDayMinusOne = "01";
$revalid_db = $page->DB_QueryManage("SELECT `date`, SUM(SP_SEP) AS `sSEP`, SUM(SP_MEP) AS `sMEP`, SUM(MP) AS `sMP`, SUM(landings_day) AS `sLD`, SUM(landings_day) AS `sLD`, SUM(landings_night) AS `sLN` FROM `PilotLogbook` WHERE `date` >= '$DueYearMinusOne-$DueMonthMinusOne-$DueDayMinusOne' AND `date` <= '$DueYear-$DueMonth-$DueDay'");
$revalidPIC_db = $page->DB_QueryManage("SELECT `date`, SUM(SP_SEP) AS `sSEP`, SUM(SP_MEP) AS `sMEP`, SUM(MP) AS `sMP`, SUM(landings_day) AS `sLD`, SUM(landings_day) AS `sLD`, SUM(landings_night) AS `sLN` FROM `PilotLogbook` WHERE `date` >= '$DueYearMinusOne-$DueMonthMinusOne-$DueDayMinusOne' AND `date` <= '$DueYear-$DueMonth-$DueDay' AND `PIC` = 'Induni'");
$total_item = $total_db->fetch_object();
$total_db->close();
$pic_item = $pic_db->fetch_object();
$pic_db->close();
$AL_item = $AL_db->fetch_object();
$AL_db->close();
$Zoe_item = $Zoe_db->fetch_object();
$Zoe_db->close();
$Ludo_item = $Ludo_db->fetch_object();
$Ludo_db->close();
$year_item = $year_db->fetch_object();
$year_db->close();
$three_item = $three_db->fetch_object();
$three_db->close();
$night_item = $night_db->fetch_object();
$night_db->close();
$revalid_item = $revalid_db->fetch_object();
$revalid_db->close();
$revalidPIC_item = $revalidPIC_db->fetch_object();
$revalidPIC_db->close();
$sLN = $night_item->sLN + 0;
$today = $page->GetNow();
$today = $today->date;
//
	//// Table summary for flight hours+landings
	$body .= "<h2>Summary of my flight hours as of today $today</h2>\n";
	$body .= "<p><b>Total flight hours:</b> " . displaySQLtime($page, $total_item) . "</p>\n";
	$body .= "<p><b>PIC flight hours:</b> " .   displaySQLtime($page, $pic_item) . "</p>\n";
	$body .= "<p><b>12 months preceeding $DueYear-$DueMonth-$DueDay:</b> ";
	$body .= displaySQLtime($page, $revalid_item) . " (of which " . displaySQLtime($page, $revalidPIC_item) . " PIC)";
	$body .= " with " . ($revalid_item->sLD + $revalid_item->sLN) . " landings (of which " . ($revalidPIC_item->sLD + $revalidPIC_item->sLN) . " PIC)";
	$body .= "</p>\n";
	if($UserIsAdmin) {
		$body .= "<div><b>Family's flight hours:</b>";
		$body .= "<ul>\n";
		$body .= "<li>Anne-Laure: " . displaySQLtime($page, $AL_item) . "</li>\n";
		$body .= "<li>Zo&eacute;: " . displaySQLtime($page, $Zoe_item) . "</li>\n";
		$body .= "<li>Ludovic: " . displaySQLtime($page, $Ludo_item) . "</li>\n";
		$body .= "</ul>\n";
		$body .= "</div>\n";
	}
	$body .= "<table>\n";
		//// Head
		$body .= "<tr>\n";
		$body .= "<th rowspan=\"2\">Plane type</th>\n";
		$body .= "<th colspan=\"2\">All times</th>\n";
		$body .= "<th colspan=\"2\">Last 365 days<br />(1 year)</th>\n";
		$body .= "<th colspan=\"2\">Last 90 days<br />(3 months)</th>\n";
		$body .= "<th colspan=\"2\">Last 90 nights<br />(3 months)</th>\n";
		$body .= "</tr>\n";
		$body .= "<tr>\n";
		$body .= "<th>hours</th>\n";
		$body .= "<th>landings</th>\n";
		$body .= "<th>hours</th>\n";
		$body .= "<th>landings</th>\n";
		$body .= "<th>hours</th>\n";
		$body .= "<th>landings</th>\n";
		$body .= "<th>hours</th>\n";
		$body .= "<th>landings</th>\n";
		$body .= "</tr>\n";
	//
		//// All plane types
		$body .= "<tr class=\"odd\">\n";
		$body .= "<td><b>All types</b></td>\n";
		$body .= "<td class=\"num\">" . $page->minutesDisplay($total_item->sSEP + $total_item->sMEP + $total_item->sMP) . "</td>\n";
		$body .= "<td class=\"num\">" . ($total_item->sLD + $total_item->sLN) . "</td>\n";
		$body .= "<td class=\"num\">" . $page->minutesDisplay($year_item->sSEP + $year_item->sMEP + $year_item->sMP) . "</td>\n";
		$body .= "<td class=\"num\">" . ($year_item->sLD + $year_item->sLN) . "</td>\n";
		$body .= "<td class=\"num\">" . $page->minutesDisplay($three_item->sSEP + $three_item->sMEP + $three_item->sMP) . "</td>\n";
		$body .= "<td class=\"num\">" . ($three_item->sLD + $three_item->sLN) . "</td>\n";
		$body .= "<td class=\"num\">" . $page->minutesDisplay($night_item->sNight) . "</td>\n";
		$body .= "<td class=\"num\">$sLN</td>\n";
		$body .= "</tr>\n";
	//
	$airplanes_db = $page->DB_QueryManage("SELECT DISTINCT `aircraft` FROM `PilotLogbook` ORDER BY `aircraft` ASC");
	$onetwo = 1;
	while($plane_item = $airplanes_db->fetch_object()) {
		$plane = $plane_item->aircraft;
		if($plane != "none" && $plane != "") {
			$onetwo++;
			$total_db = $page->DB_QueryManage("SELECT `date`, SUM(SP_SEP) AS `sSEP`, SUM(SP_MEP) AS `sMEP`, SUM(MP) AS `sMP`, SUM(landings_day) AS `sLD`, SUM(landings_night) AS `sLN` FROM `PilotLogbook` WHERE `aircraft` = '$plane'");
			$year_db  = $page->DB_QueryManage("SELECT `date`, SUM(SP_SEP) AS `sSEP`, SUM(SP_MEP) AS `sMEP`, SUM(MP) AS `sMP`, SUM(landings_day) AS `sLD`, SUM(landings_night) AS `sLN` FROM `PilotLogbook` WHERE `aircraft` = '$plane' AND DATEDIFF(CURDATE(),date) <= 365");
			$three_db = $page->DB_QueryManage("SELECT `date`, SUM(SP_SEP) AS `sSEP`, SUM(SP_MEP) AS `sMEP`, SUM(MP) AS `sMP`, SUM(landings_day) AS `sLD`, SUM(landings_night) AS `sLN` FROM `PilotLogbook` WHERE `aircraft` = '$plane' AND DATEDIFF(CURDATE(),date) <= 90");
			$night_db = $page->DB_QueryManage("SELECT `date`, SUM(night_time) AS `sNight`, SUM(landings_night) AS `sLN` FROM `PilotLogbook` WHERE `aircraft` = '$plane' AND DATEDIFF(CURDATE(),date) <= 90");
			$total_item = $total_db->fetch_object();
			$total_db->close();
			$year_item = $year_db->fetch_object();
			$year_db->close();
			$three_item = $three_db->fetch_object();
			$three_db->close();
			$night_item = $night_db->fetch_object();
			$night_db->close();
			$sLN = $night_item->sLN + 0;
			$body .= "<tr class=\"";
			if($onetwo % 2) {
				$body .= "odd";
			} else {
				$body .= "even";
			}
			$body .= "\">\n";
			$body .= "<td><b>$plane</b></td>\n";
			$body .= "<td class=\"num\">" . $page->minutesDisplay($total_item->sSEP + $total_item->sMEP + $total_item->sMP) . "</td>\n";
			$body .= "<td class=\"num\">" . ($total_item->sLD + $total_item->sLN) . "</td>\n";
			$body .= "<td class=\"num\">" . $page->minutesDisplay($year_item->sSEP + $year_item->sMEP + $year_item->sMP) . "</td>\n";
			$body .= "<td class=\"num\">" . ($year_item->sLD + $year_item->sLN) . "</td>\n";
			$body .= "<td class=\"num\">" . $page->minutesDisplay($three_item->sSEP + $three_item->sMEP + $three_item->sMP) . "</td>\n";
			$body .= "<td class=\"num\">" . ($three_item->sLD + $three_item->sLN) . "</td>\n";
			$body .= "<td class=\"num\">" . $page->minutesDisplay($night_item->sNight) . "</td>\n";
			$body .= "<td class=\"num\">$sLN</td>\n";
			$body .= "</tr>\n";
		}
	}
	$airplanes_db->close();
	$body .= "</table>\n";
	$body .= "</div>\n";
//
$body .= "</div>\n";
//
//
$body .= "<div class=\"table\">\n";
$body .= "<h2>Logbook</h2>\n";
$body .= "<p>Sorted in antechronological order: most recent comes first.</p>\n";
if($UserIsAdmin) {
	$body .= "<p style=\"font-size: 0.7em;\">To insert a note, just give start and stop airfield the string 'note'.</p>\n";
}
$body .= "<table>\n";
$body .= $tab;
$body .= "</table>\n";
$body .= "</div>\n";
//
if($UserIsAdmin) {
	$body .= "</form>\n";
}

$page->show($body);
unset($page);
?>
