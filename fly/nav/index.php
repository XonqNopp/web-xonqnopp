<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
//$funcpath = "$rootPath/functions";

require_once("common.php");


$page = new PhPage($rootPath);
$page->bobbyTable->init();
//$page->htmlHelper->init();
//$page->logger->levelUp(6);
$page->cssHelper->dirUpWing();
$GI = $page->loginHelper->userIsAdmin();


$navcount = $page->bobbyTable->getCount("NavList");
$body = $page->bodyBuilder->goHome("../..", "..");
$body .= $page->htmlHelper->setTitle("Navigations ($navcount)");// before HotBooty
$page->htmlHelper->hotBooty();


    // planes processing (display done after nav)
    $PlaneOrder = array("PlaneID");
    $planes = $page->bobbyTable->selectAll("aircrafts", $PlaneOrder);
    $Nplanes = $page->bobbyTable->getCount("aircrafts");
    $AllPlanes = array();
    $width = 1;
    $L = $Nplanes / (1.0 * $width);
    $i = 0;

    $bp = $page->waitress->tableOpen(array(), false);
    $bp .= $page->waitress->rowOpen();
    $bp .= $page->waitress->cellOpen();

    while($p = $planes->fetch_object()) {
        $i++;
        if($i > $L) {
            $bp .= $page->waitress->cellClose();
            $bp .= $page->waitress->cellOpen();
        }

        $id = $p->id;

        $payload = $p->MTOW - $p->DryEmptyMass;
        if($p->MassUnit == "lbs") {
            $payload = floor($payload / 2.2);
        }

        $usableFuel = $p->Fuel0TotalCapacity - $p->Fuel0Unusable;
        $usableFuel += $p->Fuel1TotalCapacity - $p->Fuel1Unusable;
        $usableFuel += $p->Fuel2TotalCapacity - $p->Fuel2Unusable;
        $usableFuel += $p->Fuel3TotalCapacity - $p->Fuel3Unusable;
        $fuelCons = $p->FuelCons;
        $fuelTime = $usableFuel / $fuelCons;
        $maxTime = $fuelTime - 1.0;
        $maxRange = round($maxTime * $p->PlanningSpeed, 0);

        $AllPlanes[$id] = $p->PlaneID;

        $bp .= "<div class=\"onePlane\">\n";
        if($GI) {
            $bp .= "<span class=\"edit\">\n";
            $bp .= $page->bodyBuilder->anchor("plane.php?id=$id", "edit", "edit {$p->PlaneID}");
            $bp .= "</span>\n";
        }
        $bp .= "{$p->PlaneID}: {$p->PlaneType}";
        $bp .= " {$p->PlanningSpeed}kts";
        $bp .= " payload={$payload}kg";
        $bp .= " range={$maxRange}NM";
        $bp .= "\n";
        $bp .= "</div><!-- onePlane -->\n";
    }

    $planes->close();

    $bp .= $page->waitress->cellClose();
    $bp .= $page->waitress->rowClose();
    $bp .= $page->waitress->tableClose();
//

    // Navigations
    $NavOrders = array("name", "plane", "id");
    $nav = $page->bobbyTable->selectAll("NavList", $NavOrders);
    $width = 2;
    $N = $navcount;
    $L = $N / (1.0 * $width);
    $i = 0;

    if($GI) {
        $body .= "<div class=\"wide\">\n";
        $body .= "<div class=\"lhead\">\n";
        $body .= "Template:\n";
        $body .= $page->bodyBuilder->anchor("display.php?id=0", "refresh");

        $body .= "- " . $page->bodyBuilder->anchor("$kTemplateFilename.tex", "TeX");

        $pdfTemplate = "$kTemplateFilename.pdf";
        if(file_exists("$pdfTemplate")) {
            $body .= "- " . $page->bodyBuilder->anchor($pdfTemplate, "PDF");
        }

        $body .= "</div><!-- lhead -->\n";

        $body .= "<div class=\"chead\"></div>\n";

        $body .= "<div class=\"rhead\">\n";
        $body .= $page->bodyBuilder->anchor("insert.php", "new nav");
        $body .= "</div><!-- rhead -->\n";

        $body .= "</div><!-- wide -->\n";
    }

    $body .= $page->waitress->tableOpen(array(), false);
    $body .= $page->waitress->rowOpen();
    $body .= $page->waitress->cellOpen();

    while($item = $nav->fetch_object()) {
        $i++;
        if($i > $L) {
            $i = 0;
            $body .= $page->waitress->cellClose();
            $body .= $page->waitress->cellOpen();
        }
        $plane = "";
        if($item->plane > 0) {
            $plane = $AllPlanes[$item->plane];
        }
        $body .= "<div>\n";
        if($GI) {
            $body .= "<span class=\"edit\">\n";
            $body .= $page->bodyBuilder->anchor("insert.php?id={$item->id}", "edit", "edit {$item->name}");
            $body .= "</span>\n";
        }
        $body .= $page->bodyBuilder->anchor("display.php?id={$item->id}", $item->name . ($plane == "" ? "" : " ($plane)"));
        $filename = getNavFilename($item->id);
        if(file_exists("$filename.pdf")) {
            $body .= "&nbsp;\n";
            $body .= "<span class=\"edit\">\n";
            $body .= $page->bodyBuilder->anchor("$filename.pdf", "PDF", "{$item->name} PDF");
            $body .= "</span>\n";
        }
        $body .= "</div>\n";
    }

    $nav->close();

    $body .= $page->waitress->cellClose();
    $body .= $page->waitress->rowClose();
    $body .= $page->waitress->tableClose();
//

    // planes display
    $body .= "<h2>Available airplanes</h2>\n";
    if($GI) {
        $body .= "<div class=\"wide\">\n";
        $body .= "<div class=\"lhead\">\n";
        $body .= $page->bodyBuilder->anchor("plane.php", "new plane");
        $body .= "</div>\n";
        $body .= "<div class=\"chead\">\n";
        $body .= "</div>\n";
        $body .= "<div class=\"rhead\">\n";
        $body .= "</div>\n";
        $body .= "</div>\n";
    }
    $body .= $bp;


echo $body;
?>
