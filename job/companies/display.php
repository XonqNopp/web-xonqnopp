<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require_once("dicts.php");
$page = new PhPage($rootPath);
$page->bobbyTable->init();
//$page->logger->levelUp(6);
$body = "";


$page->cssHelper->dirUpWing(2);
$userIsAdmin = $page->loginHelper->userIsAdmin();

if(!isset($_GET["id"])) {
    $page->htmlHelper->headerLocation();
}

$companyId = $_GET["id"];

$SQL = $page->bobbyTable->idManage("SELECT * FROM `companies` WHERE `id` = ?", $companyId);
$SQL->bind_result($companyId, $name, $location, $car_time, $train_time, $fields, $physicist, $contact, $HR, $peopleAll, $peopleCH, $peopleRD, $competitors, $website, $ranking, $comment);
$SQL->fetch();
$SQL->close();
$fields = implode(", ", fields(explode(",", $fields)));
$physicist = implode(", ", physicist(explode(",", $physicist)));


function SIround($val) {
    $mega = 1000000;
    $kilo =    1000;
    if($val >= $mega) {
        $rest = $val % $mega;
        $val = (int) ($val / $mega);
        $val = "{$val}M";
        if($rest > 0) {
            $val = "$val+";
        }
    } elseif($val >= $kilo) {
        $rest = $val % $kilo;
        $val = (int) ($val / $kilo);
        $val = "{$val}k";
        if($rest > 0) {
            $val = "$val+";
        }
    }
    return $val;
}

$peopleAll = SIround($peopleAll);
$peopleCH = SIround($peopleCH);
$peopleRD = SIround($peopleRD);

$people = "";
if($peopleAll > 0) {
    $people = $peopleAll;
    if($peopleCH > 0 || $peopleRD > 0) {
        $people = "all# $peopleAll - ";
    }
}
if($peopleCH > 0) {
    $people .= "CH# $peopleCH";
    if($peopleRD > 0) {
        $people .= " - ";
    }
}
if($peopleRD > 0) {
    $people .= "R&amp;D# $peopleRD";
}


$body .= $page->bodyBuilder->goHome("../..");

$body .= $page->htmlHelper->setTitle($name);  // before HotBooty
$page->htmlHelper->hotBooty();

$body .= "<div class=\"wide\">\n";
$body .= "<div class=\"lhead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"chead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"rhead\">\n";
if($userIsAdmin) {
    $body .= $page->bodyBuilder->anchor("insert.php?id=$companyId", "edit") . "<br>\n";
    $body .= $page->bodyBuilder->anchor("insert.php", "new") . "<br>\n";
    $body .= $page->bodyBuilder->anchor("interactions.php", "interactions");
}
$body .= "</div>\n";
$body .= "</div>\n";

$body .= "<div>\n";
$body .= "<ul>\n";
if($location != "") {
    $body .= "<li><b>Location:</b>\n";
    $locos = explode("/", $location);
    $first = 1;
    $cff = "";
    foreach($locos as $lolo) {
        $lolo = preg_replace("/^ +/", "", $lolo);
        $lolo = preg_replace("/ +$/", "", $lolo);

        if($first) {
            $first = 0;
        } else {
            $body .= ",";
        }

        $body .= " ";

        if($lolo == "worldwide") {
            $body .= $lolo;
            continue;
        }

        $body .= "$lolo ";
        $body .= "(";

        $body .= $page->bodyBuilder->anchor(
            "http://maps.google.ch?"
            . "f=d"
            . ($userIsAdmin ? "&amp;saddr=champ+bonjard,+1782+belfaux" : "")
            . "&amp;daddr="
            . preg_replace("/ /", "+", $lolo),
            "map",
            $lolo,
            NULL, NULL, false, NULL, false
        );

        $body .= ", ";// &+-/;,

        $body .= $page->bodyBuilder->anchor(
            "http://fahrplan.sbb.ch/bin/query.exe/fn?"
            . "start=yes"
            . "&amp;REQ0JourneyStopsS0A=1"
            . ($userIsAdmin ? "&amp;REQ0JourneyStopsS0G=Belfaux%21" : "")
            . "&amp;REQ0JourneyStopsZ0A=1"
            . "&amp;REQ0JourneyStopsZ0G=$lolo%21"
            . "&amp;REQ0JourneyProduct_opt_section_0_list=0%3A0000"
            . "&amp;REQ0JourneyTime=06%3A00"
            //. "&amp;REQ0JourneyTime=08%3A00"
            //. "&amp;REQ0HafasSearchForw=1"
            ,
            "CFF",
            NULL, NULL, false, NULL, false
        );

        $body .= ")";
    }
    $body .= "</li>\n";
}

$body .= $fields == "" ? "" : "<li><b>Fields of work:</b> $fields</li>\n";
$body .= $physicist == "" ? "" : "<li><b>What would a physicist do by them:</b> $physicist</li>\n";

if($contact != "") {
    $li = preg_replace("/ /", "+", $contact);
    $li = $page->bodyBuilder->anchor("https://www.linkedin.com/vsearch/p?type=all&amp;keywords=$li&amp;f_N=F,S");
    $contact = "$li$contact";
    $body .= "<li><b>Contact person inside:</b> $contact</li>\n";
}
$body .= $HR == "" ? "" : "<li><b>HR person:</b> $HR</li>\n";
$body .= $people == "" ? "" : "<li><b>Number of employees:</b> $people</li>\n";
$body .= $competitors == "" ? "" : "<li><b>Competitors:</b> $competitors</li>\n";
if($website != "") {
    $body .= "<li><b>Website:</b> ";
    $body .= $page->dbText->sql2htmlUrl($website);
    $body .= "</li>\n";
}
$body .= $userIsAdmin ? "<li><b>Personal ranking:</b> $ranking</li>\n" : "";
$body .= $comment == "" ? "" : "<li><b>Comment:</b> $comment</li>\n";
$body .= "</ul>\n";


function getCommunications($companyId) {
    global $userIsAdmin;

    if(!$userIsAdmin) {
        return "";
    }

    global $page;

    $sid = NULL;
    $forgetitNOTUSED = NULL;
    $timestamp = NULL;
    $who = NULL;
    $media = NULL;
    $way = NULL;
    $kind = NULL;
    $content = NULL;
    $page->logopedist->changeSessionLang("english");
    $sub = $page->bobbyTable->idManage("SELECT * FROM `comco` WHERE `company` = ? ORDER BY `timestamp` ASC", $companyId);
    $sub->bind_result($sid, $forgetitNOTUSED, $timestamp, $who, $media, $way, $kind, $content);

    $body = $page->waitress->tableOpen(array("class" => "comco"));

    $nowYear = $page->timeHelper->getNow()->year;
    while($sub->fetch()) {
        $theDate = $page->timeHelper->str2date($timestamp);

        $txtTimestamp = $page->timeHelper->months($theDate->month) . "&nbsp;" . $theDate->day;
        if($theDate->year != $nowYear) {
            $txtTimestamp .= ", $theDate->year";
        }

        $body .= $page->waitress->rowOpen();

        $body .= $page->waitress->cell($page->bodyBuilder->anchor("communication.php?id=$sid", "edit", NULL, "edit"));
        $body .= $page->waitress->cell($page->bodyBuilder->img("$way.png", $way), array("class" => "way"));

        // title="$titleTimestamp"
        $body .= $page->waitress->cell($txtTimestamp, array("class" => "timestamp"));

        $body .= $page->waitress->cell($who, array("class" => "who"));
        $body .= $page->waitress->cell($page->bodyBuilder->img("$media.png", $media), array("class" => "media"));
        $body .= $page->waitress->cell($page->bodyBuilder->img("$kind.png", $kind), array("class" => "kind"));
        $body .= $page->waitress->cell($page->dbText->sql2htmlUrl($content), array("class" => "content"));

        $body .= $page->waitress->rowClose();
    }

    $new = "new";
    // If there are no interactions, make it less confusing with more text
    if($sub->num_rows == 0) {
        $new .= " interactions";
    }

    $sub->close();

    $body .= $page->waitress->row($page->bodyBuilder->anchor("communication.php?new=$companyId", $new));

    $body .= $page->waitress->tableClose();

    return $body;
}


$body .= getCommunications($companyId);

$body .= "</div>\n";


echo $body;
?>
