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

$b = 17.625;
$c = 243.04;

$c1 = 0.363445176;
$c2 = 0.988622465;
$c3 = 4.777114035;
$c4 = -0.114037667;
$c5 = -8.50208e-4;
$c6 = -2.0716198e-2;
$c7 = 6.87678e-4;
$c8 = 2.74954e-4;
$c9 = 0;

function c2f($T) {
    return $T * 9 / 5 + 32;
}

function f2c($Tf) {
    return ($Tf - 32) * 5 / 9;
}

if(isset($_GET["T1"])) {  // assume all are set
    $T1 = floatval($_GET["T1"]);
    $RH1 = floatval($_GET["RH1"]);
    $T2 = floatval($_GET["T2"]);
    $RH2 = floatval($_GET["RH2"]);

    $RH1_T2 = round($RH1 * exp($b * $c * ($T1 - $T2) / ($c + $T1) / ($c + $T2)));
    $RH2_T1 = round($RH2 * exp($b * $c * ($T2 - $T1) / ($c + $T2) / ($c + $T1)));

    if($RH1_T2 > 100) { $RH1_T2 = 100; }
    if($RH2_T1 > 100) { $RH2_T1 = 100; }

    $Tf1 = c2f($T1);
    $Tf2 = c2f($T2);

    $HIf1 = $c1 + $c2 * $Tf1 + $c3 * $RH1 + $c4 * $Tf1 * $RH1 + $c5 * pow($Tf1, 2) + $c6 * pow($RH1, 2) + $c7 * pow($Tf1, 2) * $RH1 + $c8 * $Tf1 * pow($RH1, 2) + $c9 * pow($Tf1, 2) * pow($RH1, 2);
    $HIf2 = $c1 + $c2 * $Tf2 + $c3 * $RH2 + $c4 * $Tf2 * $RH2 + $c5 * pow($Tf2, 2) + $c6 * pow($RH2, 2) + $c7 * pow($Tf2, 2) * $RH2 + $c8 * $Tf2 * pow($RH2, 2) + $c9 * pow($Tf2, 2) * pow($RH2, 2);

    $HI1 = round(f2c($HIf1), 1);
    $HI2 = round(f2c($HIf2), 1);
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
        $body .= $page->butler->cellOpen();
        $temperatureAttr->hasAutofocus = true;
        $body .= $theNumberInput->get("T1", $T1, NULL, $temperatureAttr, $temperatureEmbedder);
        $temperatureAttr->hasAutofocus = false;
        $body .= $page->butler->cellClose();
    //
        $body .= $page->butler->cellOpen();
        $body .= $theNumberInput->get("RH1", $RH1, NULL, $humidityAttr, $humidityEmbedder);
        $body .= $page->butler->cellClose();
    //
    $body .= $page->butler->cell("T=" . ($T2 !== NULL ? "$T2 &deg;C" : ""));
    $body .= $page->butler->cell("RH=" . ($RH1_T2 !== NULL ? "$RH1_T2 %" : ""));
    $body .= $page->butler->cell("HI=" . ($HI1 !== NULL ? "$HI1 &deg;C" : ""));
    $body .= $page->butler->rowClose();
//
    $body .= $page->butler->rowOpen();
    $body .= $page->butler->cell("T=" . ($T1 !== NULL ? "$T1 &deg;C" : ""));
    $body .= $page->butler->cell("RH=" . ($RH2_T1 !== NULL ? "$RH2_T1 %" : ""));
        $body .= $page->butler->cellOpen();
        $body .= $theNumberInput->get("T2", $T2, NULL, $temperatureAttr, $temperatureEmbedder);
        $body .= $page->butler->cellClose();
    //
        $body .= $page->butler->cellOpen();
        $body .= $theNumberInput->get("RH2", $RH2, NULL, $humidityAttr, $humidityEmbedder);
        $body .= $page->butler->cellClose();
    //
    $body .= $page->butler->cell("HI=" . ($HI2 !== NULL ? "$HI2 &deg;C" : ""));
    $body .= $page->butler->rowClose();
$body .= $page->butler->tableClose();

$body .= $page->formHelper->subButt(false, "", "file", true, "Calculer");

echo $body;
?>
