<?php
require_once("functions/page_helper.php");
$page = new PhPage();
//$page->logger->levelUp(6);
$page->bobbyTable->init();


    // Checking for testament
    require_once("testament/warning.php");
    testamentWarning($page);
    $testament = testamentDisplay($page);
//
$body = "";

$body .= $page->logopedist->languages();
$body .= $page->htmlHelper->setTitle("Welcome to Xonq Nopp's website!");
$page->htmlHelper->hotBooty();

if(isset($_SESSION["testamentwarning"]) && $_SESSION["testamentwarning"]) {
    $body .= $testament;
}

    /*** QUOTATIONS ***/
        // DB management
            // Count
            $theCount = $page->bobbyTable->getCount("quotations");
        //
            // A random citation
            $randsql = $page->bobbyTable->randomEntry("quotations");
            $randquot = $randsql->fetch_object();
            $randsql->close();
    //
        // Display
        $randid          = $randquot->id;
        $randlastauthor  = $randquot->authorlast;
        $randfirstauthor = $randquot->authorfirst;
        $randbody        = $randquot->quote;
        $inter = " ";
        if($randfirstauthor === NULL || substr($randfirstauthor, -1) == "'") {
            $inter = "";
        }
        $randauthor = "$randfirstauthor$inter$randlastauthor";
        if($randauthor == " ") {
            $randauthor = "";
        }
        $body .= "<div class=\"idxquotrand\">\nCitation random({$theCount}) = #$randid:\n";
        $body .= $page->bodyBuilder->anchor(
            "collections/quotations/index.php?favoris#c$randid",
            $randbody,
            $randauthor != "" ? $randauthor : $randid,
        );
        $body .= "</div><!-- idxquot -->\n";

$body .= $page->waitress->tableOpen(array(), false);
$body .= $page->waitress->rowOpen();

    $body .= $page->waitress->cellOpen();
    $body .= "My pages:\n";
    $body .= "<ul>\n";

    $body .= "<li>\n";
    $body .= $page->bodyBuilder->anchor("fly/index.php", "Fly");
    $body .= ": " . $page->bodyBuilder->anchor("fly/pax.php", "PAX");
    $body .= "- " . $page->bodyBuilder->anchor("fly/logbook.php", "logbook");
    $body .= "- " . $page->bodyBuilder->anchor("fly/nav/index.php", "nav");
    $body .= "- " . $page->bodyBuilder->anchor("fly/pdf/", "pdf");
    $body .= "- " . $page->bodyBuilder->anchor("fly/lsge.php", "LSGE");
    //$body .= "- " . $page->bodyBuilder->anchor("fly/lsgs.php", "LSGS");
    $body .= "</li>\n";

    $body .= "<li>\n";
    $body .= $page->bodyBuilder->anchor("recettes/index.html", "Recettes");
    $body .= ": " . $page->bodyBuilder->anchor("recettes/cuisine/granola.html", "granola");
    $body .= "</li>\n";

    $body .= "<li>\n";
    $body .= $page->bodyBuilder->anchor("collections/index.php", "Collections");
    $body .= ": " . $page->bodyBuilder->anchor("collections/bds/index.php", "BDs");
    if($page->loginHelper->userIsAdmin()) {
        $body .= "- " . $page->bodyBuilder->anchor("collections/bds/insert.php", "new BD");
    }
    $body .= "- " . $page->bodyBuilder->anchor("collections/quotations/index.php", "citations");
    $body .= "</li>\n";

    $body .= "<li>\n";
    $body .= $page->bodyBuilder->anchor("job/index.php", "job");
    $body .= ": " . $page->bodyBuilder->anchor("job/companies/index.php", "companies");
    $body .= "</li>\n";

    $body .= $page->bodyBuilder->liAnchor("sub/backup/exams/choose.php?which=m", "exams");

    $body .= "</ul>\n";
    $body .= $page->waitress->cellClose();
//
    $body .= $page->waitress->cellOpen();
    $body .= "Other websites:\n";
    $body .= "<ul>\n";
    $body .= $page->bodyBuilder->liAnchor("http://www.nidji.org/", "nidji.org");
    $body .= $page->bodyBuilder->liAnchor("http://xkcd.org/", "XKCD");
    $body .= $page->bodyBuilder->liAnchor("http://www.phdcomics.com/comics.php", "PhD comics");
    $body .= $page->bodyBuilder->liAnchor("http://esolangs.org/wiki/Main_Page/", "esolangs");
    $body .= $page->bodyBuilder->liAnchor("http://lar5.com/cube/", "lar5 cube");
    $body .= "</ul>\n";
    $body .= $page->waitress->cellClose();

$body .= $page->waitress->rowClose();
$body .= $page->waitress->tableClose();


if($page->loginHelper->userIsAdmin()) {
    $body .= "<div><!--T-->\n";
    $body .= $page->bodyBuilder->anchor("testament/index.php", "T");
    $body .= $page->bodyBuilder->anchor("testament/reset.php", "R");
    $body .= "</div><!--T-->\n";
}


echo $body;
?>
