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
    /*** Prepare text ***/
    if($page->logopedist->checkSessionLang("french")) {
        $title = "Bienvenue sur le website de Xonq Nopp !!";
        $links = "D'autres liens...";
        $randhead = "Une citation au hasard parmi les ";
        $recettes = "Recettes";
        $collections = "Mes collections";
    } else {
        $title = "Welcome to Xonq Nopp's website!";
        $links = "Some other links...";
        $randhead = "A random quotation among the ";
        $recettes = "Recipes (french only)";
        $collections = "My collections";
    }
//
$body = "";

$body .= $page->logopedist->languages();
$body .= $page->htmlHelper->setTitle($title, "main");
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
        // To screen
        $body .= "<div class=\"idxquot\">\n";
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
        $body .= "<div class=\"idxquotrand\">\nCitation random({$theCount}):\n";
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
    $body .= "- " . $page->bodyBuilder->anchor("fly/lsge.php", "LSGE");
    $body .= "- " . $page->bodyBuilder->anchor("fly/lsgs.php", "LSGS");
    $body .= "</li>\n";

    $body .= $page->bodyBuilder->liAnchor("recettes/index.html", $recettes);

    $body .= "<li>\n";
    $body .= $page->bodyBuilder->anchor("collections/index.php", $collections);
    $body .= ": " . $page->bodyBuilder->anchor("collections/bds/index.php", "BDs");
    $body .= "- " . $page->bodyBuilder->anchor("collections/bds/insert.php", "new BD");
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
    $body .= $page->bodyBuilder->liAnchor("http://www.phdcomics.com/comics.php", "PhD comics");
    $body .= $page->bodyBuilder->liAnchor("http://xkcd.org/", "XKCD");
    $body .= $page->bodyBuilder->liAnchor("http://lar5.com/cube/", "lar5 cube");
    $body .= $page->bodyBuilder->liAnchor("http://esolangs.org/wiki/Main_Page/", "esolangs");
    $body .= "</ul>\n";
    $body .= $page->waitress->cellClose();

$body .= $page->waitress->rowClose();
$body .= $page->waitress->tableClose();


// Login/Logout
$logPage = "login";
if($page->loginHelper->userIsAdmin()) {
    $logPage = "logout";
}
$body .= "<div>" . $page->bodyBuilder->anchor("$logPage.php", $logPage) . "</div>\n";
// TODO on each page
// TODO set ?from=$page so we can properly redirect

if($page->loginHelper->userIsAdmin()) {
    $body .= "<div>\n";
    $body .= $page->bodyBuilder->anchor("testament/index.php", "T");
    $body .= $page->bodyBuilder->anchor("testament/reset.php", "R");
    $body .= "</div>\n";
}

echo $body;
?>
