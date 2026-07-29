<?php
require_once("functions/page_helper.php");
$page = new PhPage();
require_once("functions/form_fields.php");
global $theNumberInput;

// debug
//$page->htmlHelper->init();
//$page->logger->levelUp(6);

$page->bobbyTable->init();
$page->cssHelper->addRaw(
"table {
    padding: 2em;
}

td {
    border-bottom: 1px solid;
}"
);

$body = "";


$body = $page->bodyBuilder->goHome();
// Set title and hot booty
$body .= $page->htmlHelper->setTitle("Temp&eacute;rature et humidit&eacute;");  // before HotBooty
$page->htmlHelper->hotBooty();

$T1 = NULL;
$RH1 = NULL;
$RH1_T2 = NULL;
$HI1 = NULL;
$T2 = NULL;
$RH2 = NULL;
$RH2_T1 = NULL;
$HI2 = NULL;

function c2f($temperatureCelsius) {
    return $temperatureCelsius * 9 / 5 + 32;
}

function f2c($temperatureF) {
    return ($temperatureF - 32) * 5 / 9;
}

function rhFromT1ToT2($rh1, $temp1, $temp2) {
    $constB = 17.625;
    $constC = 243.04;

    $rh2 = round($rh1 * exp($constB * $constC * ($temp1 - $temp2) / ($constC + $temp1) / ($constC + $temp2)));

    if($rh2 > 100) {
        $rh2 = 100;
    }

    return $rh2;
}

function heatIndex($tempC, $relHum) {
    $const1 = 0.363445176;
    $const2 = 0.988622465;
    $const3 = 4.777114035;
    $const4 = -0.114037667;
    $const5 = -8.50208e-4;
    $const6 = -2.0716198e-2;
    $const7 = 6.87678e-4;
    $const8 = 2.74954e-4;
    $const9 = 0;


    $tempF = c2f($tempC);

    $term1 = $const1;
    $term2 = $const2 * $tempF;
    $term3 = $const3 * $relHum;
    $term4 = $const4 * $tempF * $relHum;
    $term5 = $const5 * $tempF * $tempF;
    $term6 = $const6 * $relHum * $relHum;
    $term7 = $const7 * $tempF * $tempF * $relHum;
    $term8 = $const8 * $tempF * $relHum * $relHum;
    $term9 = $const9 * $tempF * $tempF * $relHum * $relHum;
    $hiF = $term1 + $term2 + $term3 + $term4 + $term5 + $term6 + $term7 + $term8 + $term9;

    return round(f2c($hiF), 1);
}

if(isset($_GET["T1"])) {  // assume all are set
    $T1 = floatval($_GET["T1"]);
    $RH1 = floatval($_GET["RH1"]);
    $T2 = floatval($_GET["T2"]);
    $RH2 = floatval($_GET["RH2"]);

    $RH1_T2 = rhFromT1ToT2($RH1, $T1, $T2);
    $RH2_T1 = rhFromT1ToT2($RH2, $T2, $T1);

    $HI1 = heatIndex($T1, $RH1);
    $HI2 = heatIndex($T2, $RH2);
}

$temperatureAttr = new FieldAttributes(true);
$temperatureAttr->min = -100;
$temperatureAttr->max = 100;
$temperatureAttr->step = 0.1;

$humidityAttr = new FieldAttributes(true);
$humidityAttr->min = 0;
$humidityAttr->max = 100;
$humidityAttr->step = 1;

$temperatureEmbedder = new FieldEmbedder("T", "&deg;C");
$humidityEmbedder = new FieldEmbedder("RH", "%");

$body .= $page->formHelper->tag("get");

$body .= $page->butler->tableOpen();
    $body .= $page->butler->rowOpen();
        $temperatureAttr->hasAutofocus = true;
        $body .= $page->butler->cell($theNumberInput->get("T1", $T1, NULL, $temperatureAttr, $temperatureEmbedder));
        $temperatureAttr->hasAutofocus = false;
    $body .= $page->butler->cell($theNumberInput->get("RH1", $RH1, NULL, $humidityAttr, $humidityEmbedder), array("style" => "border-right: 1px solid;"));
    $body .= $page->butler->cell("T=$T2 &deg;C");
    $body .= $page->butler->cell("RH=$RH1_T2 %", array("style" => "border-right: 1px solid;"));
    $body .= $page->butler->cell("HI=$HI1 &deg;C");
    $body .= $page->butler->rowClose();
//
    $body .= $page->butler->rowOpen();
    $body .= $page->butler->cell("T=$T1 &deg;C");
    $body .= $page->butler->cell("RH=$RH2_T1 %", array("style" => "border-right: 1px solid;"));
    $body .= $page->butler->cell($theNumberInput->get("T2", $T2, NULL, $temperatureAttr, $temperatureEmbedder));
    $body .= $page->butler->cell($theNumberInput->get("RH2", $RH2, NULL, $humidityAttr, $humidityEmbedder), array("style" => "border-right: 1px solid;"));
    $body .= $page->butler->cell("HI=$HI2 &deg;C");
    $body .= $page->butler->rowClose();
$body .= $page->butler->tableClose();

$body .= $page->formHelper->subButt(false, "", "file", true, "Calculer");

echo $body;
?>
