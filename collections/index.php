<?php
require_once("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->htmlHelper->init();
//$page->logger->levelUp(6);
$page->bobbyTable->init();
$userIsAdmin = $page->loginHelper->userIsAdmin();
//
$page->htmlHelper->setTitle("Collections");
$page->htmlHelper->hotBooty();

$body = $page->bodyBuilder->goHome(NULL, "..");
$body .= "<h1>Collections</h1>\n";
$body .= "<div>\n";
    $body .= "<ul>\n";
        // BD
        $getcount = $page->bobbyTable->queryManage("SELECT COUNT(*) AS `the_count` FROM `bds`");
        $fetch_count = $getcount->fetch_object();
        $bd_count = $fetch_count->the_count;
        $getcount->close();
        $getcount = $page->bobbyTable->queryManage("SELECT COUNT(*) AS `the_count` FROM `bd_series`");
        $fetch_count = $getcount->fetch_object();
        $serie_count = $fetch_count->the_count;
        $getcount->close();
        $body .= "<li>\n";
        $body .= $page->bodyBuilder->anchor("bds/index.php", "BDs");
        if($bd_count > 0) {
            $body .= "&nbsp;<span class=\"leb\">($serie_count s&eacute;ries, $bd_count BDs)</span>\n";
        }
        if($userIsAdmin) {
            $body .= "&nbsp;new ";
            $body .= $page->bodyBuilder->anchor("bds/insert.php", "BD", "Add a new BD");
            $body .= "/";
            $body .= $page->bodyBuilder->anchor("bds/serie_insert.php", "serie", "Add a BD serie");
            $body .= "\n";
        }
        $body .= "</li>\n";
    //
        // Missing
        $getcount = $page->bobbyTable->queryManage("SELECT COUNT(*) AS `the_count` FROM `missings`");
        $fetch_count = $getcount->fetch_object();
        $missing_count = $fetch_count->the_count;
        $getcount->close();
        $body .= "<li>\n";
        $body .= $page->bodyBuilder->anchor("missings/index.php", "missing");
        if($missing_count > 0) {
            $body .= "&nbsp;<span class=\"leb\">($missing_count)</span>\n";
        }
        $body .= "</li>\n";
    //
        // Borrower
        $getcount = $page->bobbyTable->queryManage("SELECT COUNT(*) AS `the_count` FROM `borrowers`");
        $fetch_count = $getcount->fetch_object();
        $borrower_count = $fetch_count->the_count;
        $getcount->close();
        $body .= "<li>\n";
        $body .= $page->bodyBuilder->anchor("borrowers/index.php", "borrowers");
        if($borrower_count > 0) {
            $body .= "&nbsp;<span class=\"leb\">($borrower_count)</span>\n";
        }
        if($userIsAdmin) {
            $body .= "&nbsp;";
            $body .= $page->bodyBuilder->anchor("borrowers/insert.php", "new", "Add a borrower");
        }
        $body .= "</li>\n";
    //
        // Quotations
        $getcount = $page->bobbyTable->queryManage("SELECT COUNT(*) AS `the_count` FROM `quotations`");
        $fetch_count = $getcount->fetch_object();
        $quote_count = $fetch_count->the_count;
        $getcount->close();
        $body .= "<li>\n";
        $body .= $page->bodyBuilder->anchor("quotations/index.php", "Citations (FR)");
        if($quote_count > 0) {
            $body .= "&nbsp;<span class=\"leb\">($quote_count)</span>\n";
        }
        if($userIsAdmin) {
            $body .= "&nbsp;";
            $body .= $page->bodyBuilder->anchor("quotations/insert.php", "new", "Ajouter une citation");
        }
        $body .= "</li>\n";
    //
        // Elephants
        $elephants = "Les &eacute;l&eacute;phants";
        $body .= "<li>\n";
        $body .= $page->bodyBuilder->anchor("elephants.php", $elephants);
        $body .= "</li>\n";
    //
        $body .= "<li>R&eacute;sum&eacute; (french-only):\n";
        $body .= "<ul>\n";
            $body .= $page->bodyBuilder->anchor("education.php", "&eacute;ducation positive");

            $body .= "<li>";
            $body .= $page->bodyBuilder->anchor("maison.php", "acheter une maison");
            $body .= " avec mon ";
            $hypothequeUrl = "hypotheque.php" . ($userIsAdmin ? "?revenu=75000&cash=95000&lpp=120000" : "");  // 2020-11-01
            $body .= $page->bodyBuilder->anchor($hypothequeUrl, "calculateur d'hypoth&egrave;que");
            $body .= "</li>\n";
        $body .= "</ul>\n";
        $body .= "</li>\n";
    $body .= "</ul>\n";
$body .= "</div>\n";

echo $body;
?>
