<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require_once("dicts.php");
$page = new PhPage($rootPath);
$page->loginHelper->notAllowed();
$page->bobbyTable->init();
//$page->logger->levelUp(6);
$body = "";

    function DisplayComco($item, $nowYear, $cname = "") {
        global $page;

        $back = "";
        $sid = $item->id;
        $cid = $item->company;
        $timestamp = $item->timestamp;
        $who = $item->who;
        $media = $item->media;
        $way = $item->way;
        $kind = $item->kind;
        $content = $item->content;

        $timestamp = substr($timestamp, 0, 10);
        $year  = substr($timestamp, 0, 4) + 0;
        $month = substr($timestamp, 5, 2) + 0;
        $day   = substr($timestamp, 8, 2) + 0;
        $timestamp = $page->timeHelper->months($month) . "&nbsp;$day";
        if($year != $nowYear) {
            $timestamp .= ", $year";
        }

        $back .= $page->waitress->rowOpen();

        $back .= $page->waitress->cell($page->bodyBuilder->anchor("communication.php?id=$sid", "edit", NULL, "edit"));

            // company name (if required)
            if($cname == "") {
                $query = $page->bobbyTable->queryManage("SELECT * FROM `companies` WHERE `id` = $cid");
                $companyObj = $query->fetch_object();
                $query->close();
                $cname = $companyObj->name;

                $back .= $page->waitress->cell($page->bodyBuilder->anchor("display.php?id=$cid", $cname), array("class" => "company_name"));
            }

        $back .= $page->waitress->cell($page->bodyBuilder->img("$way.png", $way), array("class" => "way"));
        $back .= $page->waitress->cell("$timestamp\n", array("class" => "timestamp"));
        $back .= $page->waitress->cell("$who\n", array("class" => "who"));
        $back .= $page->waitress->cell($page->bodyBuilder->img("$media.png", $media), array("class" => "media"));
        $back .= $page->waitress->cell($page->bodyBuilder->img("$kind.png", $kind), array("class" => "kind"));
        $back .= $page->waitress->cell($page->dbText->sql2htmlUrl($content) . "\n", array("class" => "content"));

        $back .= $page->waitress->rowClose();
        return $back;
    }

$chrono = true;
$page_title = "Chronological interactions";
if(isset($_GET["sort"]) && $_GET["sort"] == "companies") {
    $chrono = false;
    $page_title = "Interactions by companies";
}

$sum = $page->bobbyTable->getCount("comco");
$page_title .= " ($sum)";

$page->cssHelper->dirUpWing(2);
$page->logopedist->changeSessionLang("english");

$body .= $page->bodyBuilder->goHome("../..");
$body .= $page->htmlHelper->setTitle($page_title);// before HotBooty
$page->htmlHelper->hotBooty();

    // heads
    $body .= "<div class=\"wide\">\n";
    $body .= "<div class=\"lhead\"></div>\n";
    $body .= "<div class=\"chead\"></div>\n";
    $body .= "<div class=\"rhead\">\n";
    if($chrono) {
        // sort by companies
        $body .= $page->bodyBuilder->anchor("interactions.php?sort=companies", "sort by companies");
    } else {
        // sort chronologically
        $body .= $page->bodyBuilder->anchor("interactions.php", "sort chronologically");
    }
    $body .= "</div><!-- rhead -->\n";
    $body .= "</div><!-- wide -->\n";

$body .= $page->waitress->tableOpen(array("class" => "comco"));


function getInteractions($chrono) {
    global $page;

    $body = "";

    $nowYear = $page->timeHelper->getNow()->year;

    if($chrono) {
        // chronological
        $sql = $page->bobbyTable->queryManage("SELECT * FROM `comco` ORDER BY `timestamp` DESC, `company` ASC");
        $last = 999999;

        while($item = $sql->fetch_object()) {
            $timestamp = $item->timestamp;
            $year  = substr($timestamp, 0, 4);
            $month = substr($timestamp, 5, 2);
            $new = (int)"$year$month";

            if($new < $last) {
                $last = $new;
                $body .= $page->waitress->rowOpen();
                $body .= "<h2 class=\"csstab64_h2\">" . $page->timeHelper->months($month+0) . " $year</h2>\n";
                $body .= $page->waitress->rowClose();
            }

            $body .= DisplayComco($item, $nowYear);
        }

        $sql->close();

        return $body;
    }

    // companies
    $query = $page->bobbyTable->queryManage("SELECT * FROM `companies` ORDER BY `name` ASC");

    while($company = $query->fetch_object()) {
        $cid = $company->id;
        $name = $company->name;
        $sql = $page->bobbyTable->queryManage("SELECT * FROM `comco` WHERE `company` = $cid ORDER BY `timestamp` DESC");

        if($sql->num_rows > 0) {
            $body .= $page->waitress->rowOpen();
            $body .= "<h2 class=\"csstab64_h2\">";
            $body .= $page->bodyBuilder->anchor("display.php?id=$cid", $name);
            $body .= "</h2>\n";
            $body .= $page->waitress->rowClose();

            while($item = $sql->fetch_object()) {
                $body .= DisplayComco($item, $nowYear, $name);
            }
        }

        $sql->close();
    }

    $query->close();

    return $body;
}


$body .= getInteractions($chrono);

$body .= "</div>\n";  // TOOD not sure

$body .= $page->waitress->tableClose();


echo $body;
?>
