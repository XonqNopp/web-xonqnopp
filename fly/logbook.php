<?php
require_once("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
require_once("{$funcpath}/logging.php");

$page = new PhPage($rootPath);

require_once("$funcpath/form_fields.php");


$page->bobbyTable->init();

$page->htmlHelper->jsForm();

//$page->htmlHelper->init();
//$page->logger->levelUp(6);


function displaySqlTime($item) {
    global $page;

    return $page->timeHelper->minutesDisplay(
        $item->sSEP
        + $item->sMEP
        + $item->sMP
        );
}


function displayThousands($value) {
    $thousand = 1000;

    if($value < $thousand) {
        return $value;
    }

    $thousands = (int)($value / $thousand);
    $remaining = sprintf("%03d", ((int)$value) % $thousand);
    return "$thousands'$remaining";
}


    // setting default values
    $body = "";
    $deleted = "";
    $adList = array("LSGE", "LSGS", "note");
    $typeList = array();
    $idList = array();
    $picList = array($page->miscInit->lastName, "Berger", "Berchtold");
    $defaults = array();
    $defaults["type"] = "";
    $defaults["ID"] = "";
    $defaults["AD"] = "";

    $formDate = "";
    $formTimeStart = "";
    $formTimeStop = "";
    $formAdStart = "";
    $formAdStop = "";
    $formAircraft = "";
    $formIdentification = "";
    $formSpmp = "";
    $sqlSpSep = 0;
    $sqlSpMep = 0;
    $sqlMp = 0;
    $formPic = $page->miscInit->lastName;
    $formLandingsDay = 1;
    $formLandingsNight = 0;
    $formOpsTimeNight = 0;
    $formOpsTimeIfr = 0;
    $formFunction = "";
    $sqlFunctionTimePic = 0;
    $sqlFunctionTimeCopi = 0;
    $sqlFunctionTimeDual = 0;
    $sqlFunctionTimeInstructor = 0;
    $formNotes = "";

    $userIsAdmin = $page->loginHelper->userIsAdmin();


if($userIsAdmin) {
    if(isset($_POST["add"])) {
        // FORM values
        $formDate = $_POST["date"];

        // Time (without seconds if provided)
        $formTimeStart = substr($_POST["start_time"], 0, 5);
        $formTimeStop  = substr($_POST["stop_time"], 0, 5);

        // AD (stop same as start if not provided)
        $formAdStart = $page->dbText->input2sql($_POST["start_ad"]);
        $formAdStop = $page->dbText->input2sql($_POST["stop_ad"]);
        if($formAdStop == "") {
            $formAdStop = $formAdStart;
        }

        $formAircraft = $page->dbText->input2sql($_POST["aircraft"]);
        $formIdentification = $page->dbText->input2sql($_POST["identification"]);
        $formSpmp = $_POST["SPMP"];
        $formPic = $page->dbText->input2sql($_POST["PIC"]);
        $formLandingsDay = (int)$page->dbText->input2sql($_POST["landings_day"]);
        $formLandingsNight = (int)$page->dbText->input2sql($_POST["landings_night"]);
        $formOpsTimeNight = (int)$page->dbText->input2sql($_POST["night_time"]);
        $formOpsTimeIfr = (int)$page->dbText->input2sql($_POST["IFR_time"]);

        if($formAdStart == "note") {
            // Logbook note: do not account anything
            $formTimeStop = $formTimeStart;
            $formLandingsDay = 0;
            $formLandingsNight = 0;
            $formOpsTimeNight = 0;
            $formOpsTimeIfr = 0;
        }

        function timeStr2minutes($timeStr) {
            global $page;
            $timeHelper = $page->timeHelper;
            return $timeHelper->obj2timeMinutes($timeHelper->str2time($timeStr))->timeMinutes;
        }

        $delta = timeStr2minutes($formTimeStop) - timeStr2minutes($formTimeStart);
        if($delta < 0) {
            $delta += (24 * 60);
        }

        switch($formSpmp) {
            case "SP_SEP":
                $sqlSpSep = $delta;
                break;
            case "SP_MEP":
                $sqlSpMep = $delta;
                break;
            case "MP":
                $sqlMp = $delta;
                break;
            default:
                $page->logger->fatal("If this message is displayed, you are trying to introduce malicious content to this website");
                break;
        }

        if($formOpsTimeNight > $delta || $formOpsTimeIfr > $delta) {
            $page->logger->info("Night and IFR conditions cannot exceed global time, setting max");

            if($formOpsTimeNight > $delta) {
                $formOpsTimeNight = $delta;
            }
            if($formOpsTimeIfr > $delta) {
                $formOpsTimeIfr = $delta;
            }
        }

        $formFunction = $_POST["function"];

        switch($_POST["function"]) {
        case "PIC":
            $sqlFunctionTimePic = $delta;
            break;
        case "copi":
            $sqlFunctionTimeCopi = $delta;
            break;
        case "dual":
            $sqlFunctionTimeDual = $delta;
            break;
        case "instructor":
            $sqlFunctionTimeInstructor = $delta;
            break;
        default:
            $page->logger->fatal("If this message is displayed, you are trying to introduce malicious content to this website");
            break;
        }

        $formNotes = $page->dbText->input2sql($_POST["notes"]);
        $insert = $page->bobbyTable->queryPrepare("INSERT INTO `{$page->bobbyTable->dbName}` . `PilotLogbook` (`id`, `date`, `start_time`, `stop_time`, `start_ad`, `stop_ad`, `aircraft`, `identification`, `SP_SEP`, `SP_MEP`, `MP`, `PIC`, `landings_day`, `landings_night`, `night_time`, `IFR_time`, `PIC_time`, `copi_time`, `dual_time`, `instructor_time`, `notes`) VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
        $insert->bind_param("sssssssiiisiiiiiiiis", $formDate, $formTimeStart, $formTimeStop, $formAdStart, $formAdStop, $formAircraft, $formIdentification, $sqlSpSep, $sqlSpMep, $sqlMp, $formPic, $formLandingsDay, $formLandingsNight, $formOpsTimeNight, $formOpsTimeIfr, $sqlFunctionTimePic, $sqlFunctionTimeCopi, $sqlFunctionTimeDual, $sqlFunctionTimeInstructor, $formNotes);
        $page->bobbyTable->executeManage($insert);
        $page->htmlHelper->headerLocation("logbook.php");

    } elseif(isset($_POST["delete"])) {
        $id = $_POST["delete"];
        $exq = $page->bobbyTable->queryManage("SELECT * FROM `PilotLogbook` WHERE `id` = $id");

        if($exq->num_rows == 0) {
            $page->logger->error("Flight #$id not found, cannot delete it");
        } else {

            $extry = $exq->fetch_object();

            $formDate = $extry->date;
            $formTimeStart = $extry->start_time;
            $formAdStart = $extry->start_ad;
            $formAdStop = $extry->stop_ad;
            $formTimeStop = $extry->stop_time;
            $formAircraft = $extry->aircraft;
            $formIdentification = $extry->identification;
            $spSep = $extry->SP_SEP;
            $spMep = $extry->SP_MEP;
            $mp = $extry->MP;
            $formSpmp = "";

            if($spSep > 0) {
                $formSpmp = "SP_SEP";
            } elseif($spMep > 0) {
                $formSpmp = "SP_MEP";
            } elseif($mp > 0) {
                $formSpmp = "MP";
            }

            $formPic = $extry->PIC;
            $formLandingsDay = $extry->landings_day;
            $formLandingsNight = $extry->landings_night;
            $formOpsTimeNight = $extry->night_time;
            $formOpsTimeIfr = $extry->IFR_time;
            $picTime = $extry->PIC_time;
            $copiTime = $extry->copi_time;
            $dualTime = $extry->dual_time;
            $instructorTime = $extry->instructor_time;

            if($picTime > 0) {
                $formFunction = "PIC";
            } elseif($copiTime > 0) {
                $formFunction = "copi";
            } elseif($dualTime > 0) {
                $formFunction = "dual";
            } elseif($instructorTime > 0) {
                $formFunction = "instructor";
            }

            $formNotes = $extry->notes;

            $page->butler->crossCheckDisable();

            $deleted = "\n<!-- Deleted No $id -->\n";
            $deleted .= $page->butler->rowOpen(array("id" => "deleted"));

            // make single td colspan with remarks+delete
            $contents = $page->butler->cell($formNotes, array("colspan" => 21, "style" => "text-align: center;"));

            if($formAdStart != "note" && $formAdStop != "note") {
                $contents = $page->butler->cell($formDate);
                $contents .= $page->butler->cell($formAdStart);
                $contents .= $page->butler->cell(substr($formTimeStart, 0, 5), array("class" => "num"));
                $contents .= $page->butler->cell(substr($formTimeStop, 0, 5), array("class" => "num"));
                $contents .= $page->butler->cell($formAdStop);
                $contents .= $page->butler->cell($formAircraft);
                $contents .= $page->butler->cell($formIdentification);
                $contents .= $page->butler->cell($page->timeHelper->minutesDisplay($spSep), array("class" => "num"));
                $contents .= $page->butler->cell($page->timeHelper->minutesDisplay($spMep), array("class" => "num"));
                $contents .= $page->butler->cell($page->timeHelper->minutesDisplay($mp), array("class" => "num"));
                $contents .= $page->butler->cell($page->timeHelper->minutesDisplay($spSep + $spMep + $mp), array("class" => "num"));
                $contents .= $page->butler->cell($formPic);
                $contents .= $page->butler->cell(sprintf("%d", $formLandingsDay), array("class" => "num"));
                $contents .= $page->butler->cell(sprintf("%d", $formLandingsNight), array("class" => "num"));
                $contents .= $page->butler->cell($page->timeHelper->minutesDisplay($formOpsTimeNight), array("class" => "num"));
                $contents .= $page->butler->cell($page->timeHelper->minutesDisplay($formOpsTimeIfr), array("class" => "num"));
                $contents .= $page->butler->cell($page->timeHelper->minutesDisplay($picTime), array("class" => "num"));
                $contents .= $page->butler->cell($page->timeHelper->minutesDisplay($copiTime), array("class" => "num"));
                $contents .= $page->butler->cell($page->timeHelper->minutesDisplay($dualTime), array("class" => "num"));
                $contents .= $page->butler->cell($page->timeHelper->minutesDisplay($instructorTime), array("class" => "num"));
                $contents .= $page->butler->cell($formNotes);
            }

            $deleted .= $contents;

            $deleted .= $page->butler->cell("DELETED!");
            $deleted .= $page->butler->rowClose();
            $page->bobbyTable->queryManage("DELETE FROM `{$page->bobbyTable->dbName}` . `PilotLogbook` WHERE `PilotLogbook` . `id` = $id LIMIT 1;");

            $page->butler->crossCheckEnable();
        }

        $exq->close();
    }
}

$body .= $page->bodyBuilder->goHome("..");
if($userIsAdmin) {
    $body .= $page->formHelper->tag();
}
$body .= "<div class=\"introduction\">\n";
$body .= $page->htmlHelper->setTitle("My Pilot Logbook");
$page->htmlHelper->hotBooty();

$page->butler->crossCheckDisable();  // we will do tables in several variables

    // run through DB to make body of table
        // head of table
        function tableHead() {
            global $page;

            $thead = "";
            $thead .= $page->butler->theadOpen();
                // 1st line
                $thead .= $page->butler->rowOpen();
                $thead .= $page->butler->headerCell("date", array("class" => "date", "rowspan" => 2));
                $thead .= $page->butler->headerCell("departure", array("colspan" => 2));
                $thead .= $page->butler->headerCell("arrival", array("colspan" => 2));
                $thead .= $page->butler->headerCell("aircraft", array("colspan" => 2));
                $thead .= $page->butler->headerCell("total flight time", array("colspan" => 4));
                $thead .= $page->butler->headerCell("name of PIC", array("class" => "PICname", "rowspan" => 2));
                $thead .= $page->butler->headerCell("landings", array("colspan" => 2));
                $thead .= $page->butler->headerCell("ops condition time", array("colspan" => 2));
                $thead .= $page->butler->headerCell("pilot function time", array("colspan" => 4));
                $thead .= $page->butler->headerCell("remarks", array("class" => "notes", "rowspan" => 2));
                $thead .= $page->butler->headerCell("", array("rowspan" => 2));  // to prevent warning in validator
                $thead .= $page->butler->rowClose();
            //
                // 2nd line
                $thead .= $page->butler->rowOpen();
                $thead .= $page->butler->headerCell("AD", array("class" => "start_ad"));
                $thead .= $page->butler->headerCell("time", array("class" => "start_time"));
                $thead .= $page->butler->headerCell("time", array("class" => "stop_time"));
                $thead .= $page->butler->headerCell("AD", array("class" => "stop_ad"));
                $thead .= $page->butler->headerCell("model", array("class" => "aircraft"));
                $thead .= $page->butler->headerCell("registration", array("class" => "identification"));
                $thead .= $page->butler->headerCell("SP SEP", array("class" => "SP_SEP"));
                $thead .= $page->butler->headerCell("SP MEP", array("class" => "SP_MEP"));
                $thead .= $page->butler->headerCell("MP", array("class" => "MP"));
                $thead .= $page->butler->headerCell("total", array("class" => "SP_MP"));
                $thead .= $page->butler->headerCell("day", array("class" => "landings_day"));
                $thead .= $page->butler->headerCell("night", array("class" => "landings_night"));
                $thead .= $page->butler->headerCell("night", array("class" => "night"));
                $thead .= $page->butler->headerCell("IFR", array("class" => "IFR"));
                $thead .= $page->butler->headerCell("PIC", array("class" => "PIC"));
                $thead .= $page->butler->headerCell("copi", array("class" => "copi"));
                $thead .= $page->butler->headerCell("dual", array("class" => "dual"));
                $thead .= $page->butler->headerCell("instructor", array("class" => "instructor"));
                $thead .= $page->butler->rowClose();
            $thead .= $page->butler->theadClose();
            return $thead;
        }
    //
        // some vars to prepare
        $sumSpSep = 0;
        $sumSpMep = 0;
        $sumMp = 0;
        //total is $spSep+$spMep+$mp
        $sumLandingsDay = 0;
        $sumLandingsNight = 0;
        $sumOpsTimeNight = 0;
        $sumOpsTimeIfr = 0;
        $sumPic = 0;
        $sumCopi = 0;
        $sumDual = 0;
        $sumInstructor = 0;

        class Flight {
            public $minutes;
            public $pax;
            public $freeSeats;

            public function __construct($minutes, $pax=0, $freeSeats=0) {
                $this->minutes = $minutes;
                $this->pax = $pax;
                $this->freeSeats = $freeSeats;
            }
        };

        $stats = array("dual" => array(), "PIC" => array());
        $maxPax = 3;
    //
        // run DB
        $tbody = "";
        $tbody .= $page->butler->tbodyOpen();
        $contents = $page->bobbyTable->queryManage("SELECT * FROM `PilotLogbook` ORDER BY `date` DESC, `start_time` DESC");
        if($contents->num_rows == 0) {
            $tbody .= $page->butler->row("Nothing found in DB", array(), array("colspan" => 22, "class" => "fullnote"));
        } else {
            while($entry = $contents->fetch_object()) {
                    // Get values
                    $id = $entry->id;
                    $date = $entry->date;
                    $startTime = substr($entry->start_time, 0, 5);
                    $startAd = $entry->start_ad;
                    $stopAd = $entry->stop_ad;
                    $stopTime = substr($entry->stop_time, 0, 5);
                    $aircraft = $entry->aircraft;
                    $identification = $entry->identification;
                    $spSep = $entry->SP_SEP;
                    $spMep = $entry->SP_MEP;
                    $mp = $entry->MP;
                    $pic = $entry->PIC;
                    $landingsDay = $entry->landings_day;
                    $landingsNight = $entry->landings_night;
                    $nightTime = $entry->night_time;
                    $ifrTime = $entry->IFR_time;
                    $picTime = $entry->PIC_time;
                    $copiTime = $entry->copi_time;
                    $dualTime = $entry->dual_time;
                    $instructorTime = $entry->instructor_time;
                    $notes = $entry->notes;

                $adminCell = $page->butler->cell();
                if($userIsAdmin) {
                    $adminCell = $page->butler->cellOpen();
                    $adminCell .= "<input type=\"submit\" name=\"delete\" value=\"$id\" ";
                    $adminCell .= "onclick=\"return ConfirmErase('" . addslashes(html_entity_decode($page->dbText->sql2html($notes))) . "')\">";
                    $adminCell .= $page->butler->cellClose();
                }

                    // update data lists
                    if($stopAd != "" && $stopAd != "note") {
                        if(!in_array($stopAd, $adList)) { $adList[] = $stopAd; }
                        if($defaults["AD"] == "") { $defaults["AD"] = $stopAd; }
                        // default AD set to last stop AD is it is more likely the place we take off from
                    }
                    if($startAd != "" && $startAd != "note") {
                        if(!in_array($startAd, $adList)) { $adList[] = $startAd; }
                        if($defaults["AD"] == "") { $defaults["AD"] = $startAd; }
                    }
                    if($aircraft != "") {
                        if(!in_array($aircraft, $typeList)) { $typeList[] = $aircraft; }
                        if($defaults["type"] == "") { $defaults["type"] = $aircraft; }
                    }
                    if($identification != "") {
                        if(!in_array($identification, $idList)) { $idList[] = $identification; }
                        if($defaults["ID"] == "") { $defaults["ID"] = $identification; }
                    }
                    if($pic != "") {
                        if(!in_array($pic, $picList)) { $picList[] = $pic; }
                    }

                $tbody .= "\n<!-- No $id -->\n";
                if($startAd == "note" && $stopAd == "note") {
                    // make single td colspan with remarks+delete
                    $tbody .= $page->butler->rowOpen(array(), false);
                    $tbody .= $page->butler->cell($notes, array("colspan" => 21, "class" => "fullnote"));
                    $tbody .= $adminCell;
                    $tbody .= $page->butler->rowClose();
                    continue;
                }

                    // Compute
                    $sumSpSep += $spSep;
                    $sumSpMep += $spMep;
                    $sumMp += $mp;
                    $sumLandingsDay += $landingsDay;
                    $sumLandingsNight += $landingsNight;
                    $sumOpsTimeNight += $nightTime;
                    $sumOpsTimeIfr += $ifrTime;
                    $sumPic += $picTime;
                    $sumCopi += $copiTime;
                    $sumDual += $dualTime;
                    $sumInstructor += $instructorTime;
                //
                    // stats
                    if((int)substr($date, 0, 4) > 2010) {
                        // Do not count IAP
                        if($picTime > 0) {
                            $pax = 0;
                            $freeSeats = $maxPax;
                            if(substr($notes, 0, 3) == "PAX") {
                                $pax = (int)substr($notes, 3, 1);
                                $freeSeats = $maxPax - $pax;

                                if(in_array($identification, array("HB-KEX", "HB-KFQ")) && $freeSeats > 1) {
                                    $freeSeats -= 1;
                                }

                                if($pax == 0) {
                                    // family flight
                                    $freeSeats = 0;
                                }
                            }
                            $stats["PIC"][] = new Flight($picTime, $pax, $freeSeats);
                        }

                        if($dualTime > 0) {
                            $stats["dual"][] = new Flight($dualTime);
                        }
                    }
                //
                    // display
                    $tbody .= $page->butler->rowOpen();

                    $tbody .= $page->butler->cell($date, array("class" => "date"));
                    $tbody .= $page->butler->cell($startAd, array("class" => "start_ad"));
                    $tbody .= $page->butler->cell($startTime, array("class" => "start_time num"));
                    $tbody .= $page->butler->cell($stopTime, array("class" => "stop_time num"));
                    $tbody .= $page->butler->cell($stopAd, array("class" => "stop_ad"));
                    $tbody .= $page->butler->cell($aircraft, array("class" => "aircraft"));
                    $tbody .= $page->butler->cell($identification, array("class" => "identification"));
                    $tbody .= $page->butler->cell($page->timeHelper->minutesDisplay($spSep), array("class" => "SP_SEP num"));
                    $tbody .= $page->butler->cell($page->timeHelper->minutesDisplay($spMep), array("class" => "SP_MEP num"));
                    $tbody .= $page->butler->cell($page->timeHelper->minutesDisplay($mp), array("class" => "MP num"));
                    $tbody .= $page->butler->cell($page->timeHelper->minutesDisplay($spSep + $spMep + $mp), array("class" => "SP_MP num"));
                    $tbody .= $page->butler->cell($pic, array("class" => "PICname"));
                    $tbody .= $page->butler->cell(sprintf("%d", $landingsDay), array("class" => "landings_day num"));
                    $tbody .= $page->butler->cell(sprintf("%d", $landingsNight), array("class" => "landings_night num"));
                    $tbody .= $page->butler->cell($page->timeHelper->minutesDisplay($nightTime), array("class" => "night num"));
                    $tbody .= $page->butler->cell($page->timeHelper->minutesDisplay($ifrTime), array("class" => "IFR num"));
                    $tbody .= $page->butler->cell($page->timeHelper->minutesDisplay($picTime), array("class" => "PIC num"));
                    $tbody .= $page->butler->cell($page->timeHelper->minutesDisplay($copiTime), array("class" => "copi num"));
                    $tbody .= $page->butler->cell($page->timeHelper->minutesDisplay($dualTime), array("class" => "dual num"));
                    $tbody .= $page->butler->cell($page->timeHelper->minutesDisplay($instructorTime), array("class" => "instructor num"));
                    $tbody .= $page->butler->cell($notes, array("class" => "notes"));

                    $tbody .= $adminCell;
                    $tbody .= $page->butler->rowClose();
            }
        }
        $contents->close();
        $tbody .= $page->butler->tbodyClose();
    //
        /**
         * first line of table with sums
         *
         * @SuppressWarnings(PHPMD.ExcessiveParameterList)
         */
        function tableSums($sumSpSep, $sumSpMep, $sumMp, $sumLandingsDay, $sumLandingsNight, $sumOpsTimeNight, $sumOpsTimeIfr, $sumPic, $sumCopi, $sumDual, $sumInstructor) {
            global $page;

            $tsum = "";
            $tsum .= $page->butler->rowOpen();
            $tsum .= $page->butler->headerCell("", array("colspan" => 7));
            $tsum .= $page->butler->headerCell("SEP {$page->timeHelper->minutesDisplay($sumSpSep)}", array("class" => "SP_SEP num"));
            $tsum .= $page->butler->headerCell("MEP {$page->timeHelper->minutesDisplay($sumSpMep)}", array("class" => "SP_MEP num"));
            $tsum .= $page->butler->headerCell("MP {$page->timeHelper->minutesDisplay($sumMp)}", array("class" => "MP num"));
            $tsum .= $page->butler->headerCell($page->timeHelper->minutesDisplay($sumSpSep + $sumSpMep + $sumMp), array("class" => "SP_MP num"));
            $tsum .= $page->butler->headerCell();
            $tsum .= $page->butler->headerCell("day $sumLandingsDay", array("class" => "landings_day num"));
            $tsum .= $page->butler->headerCell("night $sumLandingsNight", array("class" => "landings_night num"));
            $tsum .= $page->butler->headerCell("night {$page->timeHelper->minutesDisplay($sumOpsTimeNight)}", array("class" => "night num"));
            $tsum .= $page->butler->headerCell("IFR {$page->timeHelper->minutesDisplay($sumOpsTimeIfr)}", array("class" => "IFR num"));
            $tsum .= $page->butler->headerCell("PIC {$page->timeHelper->minutesDisplay($sumPic)}", array("class" => "PIC num"));
            $tsum .= $page->butler->headerCell("copi {$page->timeHelper->minutesDisplay($sumCopi)}", array("class" => "copi num"));
            $tsum .= $page->butler->headerCell("dual {$page->timeHelper->minutesDisplay($sumDual)}", array("class" => "dual num"));
            $tsum .= $page->butler->headerCell("inst. {$page->timeHelper->minutesDisplay($sumInstructor)}", array("class" => "instructor num"));
            $tsum .= $page->butler->headerCell("", array("colspan" => 2));
            $tsum .= $page->butler->rowClose();
            return $tsum;
        }
    //
        // set default values which needed to go through DB
        if($formAdStart == "") { $formAdStart = $defaults["AD"]; }
        if($formAircraft == "") { $formAircraft = $defaults["type"]; }
        if($formIdentification == "") { $formIdentification = $defaults["ID"]; }
    //
        /**
         * get the row for inserting a new entry
         *
         * @SuppressWarnings(PHPMD.MissingImport)
         * @SuppressWarnings(PHPMD.ExcessiveParameterList)
         * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
         */
        function tableInsert(
            $date,
            $adStart,
            $adStop,
            $adList,
            $timeStart,
            $timeStop,
            $aircraft,
            $typeList,
            $identification,
            $idList,
            $spmp,
            $pic,
            $picList,
            $landingsDay,
            $landingsNight,
            $opsTimeNight,
            $opsTimeIfr,
            $function,
            $notes
        ) {
            global $page;

            if(!$page->loginHelper->userIsAdmin()) {
                return "";
            }
            // ...only if allowed!

            global $theTextInput;
            global $theNumberInput;
            global $theSelectInput;
            global $theDateInput;
            global $theTimeInput;

            $tinsert = "";
            $attrText = new FieldAttributes();
            $attrText->size = 5;
            $attrText->autocapitalize = "characters";

            $tinsert .= $page->butler->rowOpen();
                // Date
                $attrDate = new FieldAttributes(true);
                $attrDate->max = "now";
                $tinsert .= $page->butler->cell($theDateInput->get("date", $date, NULL, $attrDate), array("class" => "date"));
            //
                // start AD
                $attrText->isRequired = true;

                $tinsert .= $page->butler->cell(
                    $theTextInput->get("start_ad", $adStart, NULL, $adList, $attrText),
                    array("class" => "start_ad")
                );

                $attrText->isRequired = false;
            //
                // time
                $attrTime = new FieldAttributes(true);

                $tinsert .= $page->butler->cell($theTimeInput->get("start_time", $timeStart, NULL, $attrTime), array("class" => "start_time"));
                $tinsert .= $page->butler->cell($theTimeInput->get("stop_time", $timeStop, NULL, $attrTime), array("class" => "start_time"));

            $tinsert .= $page->butler->cell(
                $theTextInput->get("stop_ad", $adStop, NULL, "start_ad_datalist", $attrText),
                array("class" => "stop_ad")
            );

            $attrText->isRequired = true;

            $attrText->size = 6;
            $tinsert .= $page->butler->cell(
                $theTextInput->get("aircraft", $aircraft, NULL, $typeList, $attrText),
                array("class" => "aircraft")
            );

            $attrText->size = 7;
            $tinsert .= $page->butler->cell(
                $theTextInput->get("identification", $identification, NULL, $idList, $attrText),
                array("class" => "identification")
            );

                // SEP,MEP,MP
                $spmpList = $page->utilsHelper->arraySequential2Associative(array("SP_SEP", "SP_MEP", "MP"));
                $tinsert .= $page->butler->cell(
                    $theSelectInput->get("SPMP", $spmpList, $spmp),
                    array("class" => "SPMP", "colspan" => 4)
                );

            $attrText->size = 9;
            $attrText->autocapitalize = "words";
            $tinsert .= $page->butler->cell(
                $theTextInput->get("PIC", $pic, NULL, $picList, $attrText),
                array("class" => "PICname")
            );

            $attrMin0 = new FieldAttributes(true);
            $attrMin0->min = 0;

            $tinsert .= $page->butler->cell(
                $theNumberInput->get("landings_day", $landingsDay, NULL, $attrMin0),
                array("class" => "landings_day")
            );

            $tinsert .= $page->butler->cell(
                $theNumberInput->get("landings_night", $landingsNight, NULL, $attrMin0),
                array("class" => "landings_night")
            );

            $tinsert .= $page->butler->cell(
                $theNumberInput->get("night_time", $opsTimeNight, NULL, $attrMin0),
                array("class" => "night")
            );

            $tinsert .= $page->butler->cell(
                $theNumberInput->get("IFR_time", $opsTimeIfr, NULL, $attrMin0),
                array("class" => "IFR")
            );

            $functionList = $page->utilsHelper->arraySequential2Associative(array("PIC", "copi", "dual", "instructor"));
            $tinsert .= $page->butler->cell(
                $theSelectInput->get("function", $functionList, $function),
                array("class" => "function", "colspan" => 4)
            );

            $tinsert .= $page->butler->cell($theTextInput->get("notes", $notes), array("class" => "notes"));

            $tinsert .= $page->butler->cell("<input type=\"submit\" name=\"add\" value=\"add\" onclick=\"SubmitForm()\">");
            $tinsert .= $page->butler->rowClose();
            return $tinsert;
        }
    //
        // build table
        $tab = "";
        $tab .= tableHead();
        $tab .= $deleted;

        $tab .= tableInsert(
            $formDate,
            $formAdStart,
            $formAdStop,
            $adList,
            $formTimeStart,
            $formTimeStop,
            $formAircraft,
            $typeList,
            $formIdentification,
            $idList,
            $formSpmp,
            $formPic,
            $picList,
            $formLandingsDay,
            $formLandingsNight,
            $formOpsTimeNight,
            $formOpsTimeIfr,
            $formFunction,
            $formNotes
        );

        $tab .= tableSums($sumSpSep, $sumSpMep, $sumMp, $sumLandingsDay, $sumLandingsNight, $sumOpsTimeNight, $sumOpsTimeIfr, $sumPic, $sumCopi, $sumDual, $sumInstructor);
        $tab .= $tbody;


$body .= "<div id=\"licenses\">\n";
$body .= "<p>All times are local time.</p>\n";
$body .= "<p class=\"pl\">Licence number CH.FCL.47359&nbsp;-&nbsp;Initially issued on 2014-09-15</p>\n";
$body .= "<p class=\"pl\">Medical number CH-REF-17156</p>\n";
$body .= "</div><!-- licenses -->\n";

    // summaries
        // variables
        $queryTimeLandings = "SELECT ";
        $queryTimeLandings .= "SUM(SP_SEP) AS `sSEP`, ";
        $queryTimeLandings .= "SUM(SP_MEP) AS `sMEP`, ";
        $queryTimeLandings .= "SUM(MP) AS `sMP`, ";
        $queryTimeLandings .= "SUM(night_time) AS `sNight`, ";
        $queryTimeLandings .= "SUM(landings_day) AS `sLD`, ";
        $queryTimeLandings .= "SUM(landings_night) AS `sLN` ";
        $queryTimeLandings .= "FROM `PilotLogbook`";

        $queryPic = "`PIC` = '{$page->miscInit->lastName}'";

        $datediff1y = "DATEDIFF(CURDATE(),date) <= 365";
        $datediff3m = "DATEDIFF(CURDATE(),date) <= 90";

        $visitedQueryStart = "SELECT DISTINCT `start_ad` AS `airfield` FROM `PilotLogbook` WHERE `start_ad` <> 'note'";
        $visitedQueryStop = "SELECT DISTINCT `stop_ad` AS `airfield` FROM `PilotLogbook` WHERE `stop_ad` <> 'note'";
        $visitedQueryOrder = "ORDER BY `airfield`";
    //
        // functions
            function dbGetSummaryTime($querySuffix="") {
                global $page;
                global $queryTimeLandings;

                $query = $queryTimeLandings;
                if($querySuffix != "") {
                    $query .= " WHERE $querySuffix";
                }

                $dbQuery = $page->bobbyTable->queryManage($query);
                $time = $dbQuery->fetch_object();
                $dbQuery->close();
                return $time;
            }
        //
            function summaryTotal() {
                global $page;
                global $queryPic;

                // revalidation
                $dueDay = "30";
                $dueMonth = 9;
                $dueYear = $page->timeHelper->getNow()->year;

                $dueYearMinusOne = $dueYear - 1;
                $dueMonthMinusOne = sprintf("%02d", $dueMonth + 1);
                $dueDayMinusOne = "01";

                // formatting
                $dueMonth = sprintf("%02d", $dueMonth);

                $revalidQuerySuffix = "`date` >= '$dueYearMinusOne-$dueMonthMinusOne-$dueDayMinusOne' AND `date` <= '$dueYear-$dueMonth-$dueDay'";
                $revalidItem = dbGetSummaryTime($revalidQuerySuffix);
                $revalidPicItem = dbGetSummaryTime("$revalidQuerySuffix AND $queryPic");

                $summary = "<p>";
                if($dueYear % 2 == 0) {
                    // MUST revalidate every even year
                    $summary .= "<b>";
                }
                $summary .= "12 months preceeding $dueYear-$dueMonth-$dueDay:";
                if($dueYear % 2 == 0) {
                    // MUST revalidate every even year
                    $summary .= "</b>";
                }
                $summary .= " ";
                $summary .= displaySqlTime($revalidItem) . " (" . displaySqlTime($revalidPicItem) . " PIC)";
                $summary .= " with " . ($revalidItem->sLD + $revalidItem->sLN) . " landings (" . ($revalidPicItem->sLD + $revalidPicItem->sLN) . " PIC)";
                $summary .= "</p>\n";

                return $summary;
            }
        //
            function summaryAllTime($dbItem) {
                global $page;
                return $page->timeHelper->minutesDisplay($dbItem->sSEP + $dbItem->sMEP + $dbItem->sMP);
            }
        //
            function summaryPlaneTime($plane) {
                global $page;

                global $datediff1y;
                global $datediff3m;

                $querySuffix = "";
                $querySuffixAnd = "";
                if($plane != "All types") {
                    $querySuffix = "`aircraft` = '$plane'";
                    $querySuffixAnd = "$querySuffix AND ";
                }
                $totalItem = dbGetSummaryTime($querySuffix);
                $yearItem = dbGetSummaryTime("$querySuffixAnd$datediff1y");
                $threeItem = dbGetSummaryTime("$querySuffixAnd$datediff3m");

                $sLN = $threeItem->sLN + 0;

                $landingsTotal = $totalItem->sLD + $totalItem->sLN;
                $landingsYear = $yearItem->sLD + $yearItem->sLN;
                $landingsThree = $threeItem->sLD + $sLN;

                $summary = "";
                $summary .= $page->butler->rowOpen();
                $summary .= $page->butler->cell("$plane");
                $summary .= $page->butler->cell(summaryAllTime($totalItem), array("class" => "num"));
                $summary .= $page->butler->cell("$landingsTotal", array("class" => "num"));
                $summary .= $page->butler->cell(summaryAllTime($yearItem), array("class" => "num"));
                $summary .= $page->butler->cell("$landingsYear", array("class" => "num"));
                $summary .= $page->butler->cell(summaryAllTime($threeItem), array("class" => "num"));
                $summary .= $page->butler->cell("$landingsThree", array("class" => "num"));
                $summary .= $page->butler->cell("{$page->timeHelper->minutesDisplay($threeItem->sNight)}", array("class" => "num"));
                $summary .= $page->butler->cell("$sLN", array("class" => "num"));
                $summary .= $page->butler->rowClose();
                return $summary;
            }
        //
            /*
             * Get visited airfield of family member.
             *
             * Args:
             *     who (string): matching hashtag in DB
             *
             * Returns:
             *     array of visited fields
             */
            function familyVisited($who) {
                global $page;
                global $visitedQueryStart;
                global $visitedQueryStop;
                global $visitedQueryOrder;

                $visited = array();

                $familyMatch = "AND `notes` LIKE '%#$who%'";
                $dbQuery = $page->bobbyTable->queryManage("$visitedQueryStart $familyMatch UNION $visitedQueryStop $familyMatch $visitedQueryOrder");

                while($dbObj = $dbQuery->fetch_object()) {
                    $visited[] = $dbObj->airfield;
                }

                $dbQuery->close();

                return $visited;
            }
        //
            /*
             * Make text for visited table with family member.
             *
             * Args:
             *     airfield (string)
             *     familyArray (array)
             *     display (string): the text to display
             *
             * Returns:
             *     string for single member
             */
            function familyVisitedSingle($airfield, $familyArray, $display) {
                $text = "";

                if(in_array($airfield, $familyArray[$display])) {
                    $text .= " $display";
                }

                return $text;
            }
        //
            function visitedAirfields() {
                global $page;
                global $visitedQueryStart;
                global $visitedQueryStop;
                global $visitedQueryOrder;

                $visited = "";

                // DB
                $visitedDb = $page->bobbyTable->queryManage("$visitedQueryStart UNION $visitedQueryStop $visitedQueryOrder");

                $visitedFamily = array();
                if($page->loginHelper->userIsAdmin()) {
                    $visitedFamily["AL"] = familyVisited("AnneLaure");
                    $visitedFamily["Z"] = familyVisited("Zoe");
                    $visitedFamily["Lu"] = familyVisited("Ludovic");
                    $visitedFamily["K"] = familyVisited("Kayra");
                    $visitedFamily["Ali"] = familyVisited("Alicia");
                }
                // table
                $visited .= "<h3>Visited fields</h3>\n";
                $visited .= "<div id=\"visits\">\n";
                $visited .= "<select>\n";

                while($visitedItem = $visitedDb->fetch_object()) {
                    $airfield = $visitedItem->airfield;

                    $visited .= "<option>$airfield";

                    if($page->loginHelper->userIsAdmin()) {
                        $visitedFamilySingle = "";
                        $visitedFamilySingle .= familyVisitedSingle($airfield, $visitedFamily, "AL");
                        $visitedFamilySingle .= familyVisitedSingle($airfield, $visitedFamily, "Z");
                        $visitedFamilySingle .= familyVisitedSingle($airfield, $visitedFamily, "Lu");

                        if($visitedFamilySingle != "") {
                            $visited .= " -$visitedFamilySingle";
                        }

                    }

                    $visited .= "</option>\n";
                }
                $visitedDb->close();

                $visited .= "</select>\n";
                $visited .= "</div><!-- visits -->\n";
                return $visited;
            }
        //
            /**
             * Get total flight time of a family member.
             *
             * Args:
             *     who (string): matching hashtag in DB
             *
             * Returns:
             *     string: time
             */
            function familyTime($who) {
                global $page;

                $dbQuery = $page->bobbyTable->queryManage(
                    "SELECT "
                    . "SUM(SP_SEP) AS `sSEP`, "
                    . "SUM(SP_MEP) AS `sMEP`, "
                    . "SUM(MP) AS `sMP` "
                    . "FROM `PilotLogbook` "
                    . "WHERE `notes` LIKE '%#$who%' "
                );

                $dbObj = $dbQuery->fetch_object();
                $dbQuery->close();

                return displaySqlTime($dbObj);
            }
    //
    $today = $page->timeHelper->getNow()->date;

        // Table summary for flight hours+landings
        $body .= "<h2>Summary of my flight hours as of today $today</h2>\n";

        $totalItem = displaySqlTime(dbGetSummaryTime());
        $picItem = displaySqlTime(dbGetSummaryTime($queryPic));
        $body .= "<p><b>Total flight hours:</b> $totalItem ($picItem PIC)</p>\n";

        $body .= summaryTotal();

        $body .= "<div id=\"summaryTable\">\n";
        $body .= $page->butler->tableOpen();

            // Head
            $body .= $page->butler->rowOpen();
            $body .= $page->butler->headerCell("Plane type", array("rowspan" => 2));
            $body .= $page->butler->headerCell("All times", array("colspan" => 2));
            $body .= $page->butler->headerCell("Last 365 days<br>(1 year)", array("colspan" => 2));
            $body .= $page->butler->headerCell("Last 90 days<br>(3 months)", array("colspan" => 2));
            $body .= $page->butler->headerCell("Last 90 nights<br>(3 months)", array("colspan" => 2));
            $body .= $page->butler->rowClose();
            $body .= $page->butler->rowOpen();
            $body .= $page->butler->headerCell("hours");
            $body .= $page->butler->headerCell("landings");
            $body .= $page->butler->headerCell("hours");
            $body .= $page->butler->headerCell("landings");
            $body .= $page->butler->headerCell("hours");
            $body .= $page->butler->headerCell("landings");
            $body .= $page->butler->headerCell("hours");
            $body .= $page->butler->headerCell("landings");
            $body .= $page->butler->rowClose();

        $body .= summaryPlaneTime("All types");

        $airplanesDb = $page->bobbyTable->queryManage("SELECT DISTINCT `aircraft` FROM `PilotLogbook` ORDER BY `aircraft` ASC");
        while($airplaneItem = $airplanesDb->fetch_object()) {
            $plane = $airplaneItem->aircraft;
            if($plane == "none" || $plane == "") {
                continue;
            }

            $body .= summaryPlaneTime($plane);
        }
        $airplanesDb->close();
        $body .= $page->butler->tableClose();

        if($userIsAdmin) {
            $members = array(
                "AnneLaure" => "Anne-Laure",
                "Zoe" => "Zo&eacute;",
                "Ludovic" => "Ludovic",
                "Kayra" => "Kayra",
                "Alicia" => "Alicia"
            );

            $body .= "<h3>Family's flight hours:</h3>";
            $body .= "<div id=\"family\">\n";
            $body .= "<ul>\n";
            foreach($members as $key => $value) {
                $body .= "<li>$value: " . familyTime($key) . "</li>\n";
            }
            $body .= "</ul>\n";
            $body .= "</div><!-- family -->\n";

            $body .= visitedAirfields();
        }

        $body .= "</div><!-- summaryTable -->\n";
    //
    function displayStats($stats) {
        global $page;
        if(!$page->loginHelper->userIsAdmin()) {
            return "";
        }
        $paxRate = 150 / 60;  // [CHF/min] what I ask my passengers
        $planeRate = 400 / 60;  // [CHF/min] approximately what the plane costs me
        $instructorRate = 100 / 60;  // [CHF/min] approximately what the instructor costs me

        $numPic = count($stats["PIC"]);
        $numDual = count($stats["dual"]);
        $numTotal = $numPic + $numDual;

        $minutesTotal = 0;
        $minutesDual = 0;
        foreach($stats["dual"] as $flight) {
            $minutesDual += $flight->minutes;
            $minutesTotal += $flight->minutes;
        }

        $numFreeSeats = 0;
        $numPax = 0;

        $minutesFreeSeats = 0;
        $minutesPax = 0;

        $minutesPic = 0;
        foreach($stats["PIC"] as $flight) {
            $minutesPic += $flight->minutes;
            $minutesTotal += $flight->minutes;

            $numPax += $flight->pax;
            $numFreeSeats += $flight->freeSeats;

            $minutesPax += ($flight->pax * $flight->minutes);
            $minutesFreeSeats += ($flight->freeSeats * $flight->minutes);
        }

        $statBody = "";
        $statBody .= "<h3>Passenger statistics</h3>\n";
        $statBody .= "<div class=\"statistics\">\n";
        $statBody .= "<ul>\n";
        $statBody .= "<li>Number of flights: $numTotal";
        $statBody .= " for a total of " . $page->timeHelper->minutesDisplay($minutesTotal) . "</li>\n";

        $statBody .= "<li>Dual flights: $numDual";
        $statBody .= " for a total of " . $page->timeHelper->minutesDisplay($minutesDual);
        $statBody .= " or an approximate expense of CHF " . displayThousands((int)($minutesDual * ($planeRate + $instructorRate))) . "</li>\n";

        $statBody .= "<li>PIC flights: $numPic";
        $statBody .= " for a total of " . $page->timeHelper->minutesDisplay($minutesPic);
        $statBody .= " or an approximate expense of CHF " . displayThousands((int)($minutesPic * $planeRate)) . "</li>\n";

        $statBody .= "<li>Total number of passengers: $numPax";
        $statBody .= " for a total PAX time of " . $page->timeHelper->minutesDisplay($minutesPax);
        $statBody .= " or an approximate gain of CHF " . displayThousands((int)($minutesPax * $paxRate)) . "</li>\n";

        $statBody .= "<li>Total number of free seats: $numFreeSeats";
        $statBody .= " for a total time of " . $page->timeHelper->minutesDisplay($minutesFreeSeats);
        $statBody .= " or an approximate loss of CHF " . displayThousands((int)($minutesFreeSeats * $paxRate)) . "</li>\n";

        $statBody .= "</ul>\n";
        $statBody .= "</div><!-- statistics -->\n";
        return $statBody;
    }

    $body .= displayStats($stats);

    $body .= "</div><!-- introduction -->\n";


$body .= "<div class=\"table\">\n";
$body .= "<h2>Logbook</h2>\n";
$body .= "<p>Sorted in antechronological order: most recent comes first.</p>\n";
if($userIsAdmin) {
    $body .= "<p style=\"font-size: 0.7em;\">To insert a note, just give start and stop airfield the string 'note'. WARNING: start and stop time must be the same, landings must be zero.</p>\n";
}
$body .= $page->butler->tableOpen();
$body .= $tab;
$body .= $page->butler->tableClose();
$body .= "</div><!-- table -->\n";

if($userIsAdmin) {
    $body .= "</form>\n";
}

echo $body;
?>
