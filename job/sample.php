<?php
require_once("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);

$hoursweek = 40;  // [h]
$salarymonths = 12;  // [nb salary]
$holidays = 5;  // [week]

if(!isset($_GET["from"])) {
    exit;
}

$from = $_GET["from"];
$to = $from;

if(isset($_GET["to"])) {
    $to = $_GET["to"];
}

if($from == 0 || $to == 0) {
    exit;
}

$step =   2;

$page->cssHelper->dirUpWing();

$body = $page->bodyBuilder->goHome("..");

$body .= $page->htmlHelper->setTitle("Sample of salary");
$page->htmlHelper->hotBooty();

$body .= "<div>\n";
$body .= "<ul>\n";
$body .= "<li>raw</li>\n";
$body .= "<li>hours per week: $hoursweek</li>\n";
$body .= "<li>weeks of holidays: $holidays</li>\n";
$body .= "<li>number of salaries per year: $salarymonths</li>\n";
$body .= "</ul>\n";
$body .= "</div>\n";

$body .= "<div class=\"sample_table\">\n";
$body .= $page->butler->tableOpen();

$body .= $page->butler->rowOpen();
$body .= $page->butler->headerCell("100%", array("colspan" => 3));
$body .= $page->butler->headerCell("90%", array("colspan" => 3));
$body .= $page->butler->headerCell("80%", array("colspan" => 3));
$body .= $page->butler->headerCell("70%", array("colspan" => 3));
$body .= $page->butler->rowClose();

$body .= $page->butler->rowOpen();

for($iter = 0; $iter < 4; $iter++) {
    $body .= $page->butler->headerCell("year");
    $body .= $page->butler->headerCell("month");
    $body .= $page->butler->headerCell("week");
}

$body .= $page->butler->rowClose();


function getMonth($year) {
    global $salarymonths;
    return round($year / $salarymonths);
}


function getWeek($month) {
    return round($month / 4);
}


for($i = $from; $i <= $to; $i += $step) {
    $year  = $i * 1000;
    $month = getMonth($year);
    $week  = round($month / 4);

    $body .= $page->butler->rowOpen();

    for($rate = 1.0; $rate > 0.7; $rate -= 0.1) {
        $thisYear = $rate * $year;
        $thisMonth = getMonth($thisYear);

        $body .= $page->butler->cell("$thisYear.-");
        $body .= $page->butler->cell("$thisMonth.-");
        $body .= $page->butler->cell(getWeek($thisMonth) . ".-");
    }

    $body .= $page->butler->rowClose();
}
$body .= $page->butler->tableClose();
$body .= "</div><!-- sample_table -->\n";

echo $body;
?>
