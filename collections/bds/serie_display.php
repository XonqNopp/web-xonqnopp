<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require_once("{$funcpath}_local/borrowback.php");


$page = new PhPage($rootPath);

//$page->htmlHelper->init();
//$page->logger->levelUp(6);

$page->bobbyTable->init();

// Borrowed item came home (link from missing index)
if(isset($_GET["back"])) {
    $backId = NULL;
    if(isset($_GET["id"])) {
        $backId = $_GET["id"];
    }
    borrow_back($page, "bds", $_GET["back"], $backId);
}

$page->cssHelper->dirUpWing();


/**
 * Get the serie details.
 *
 * @SuppressWarnings(PHPMD.MissingImport)
 */
function getSerieDetails($serieId) {
    global $page;

    $result = new stdClass();
    $result->serie = "";
    $result->nAlbums = NULL;

    $findserie = $page->bobbyTable->idManage("SELECT `name`, `Nalbums` FROM `bd_series` WHERE `id` = ?", $serieId);
    $findserie->store_result();
    if($findserie->num_rows == 0) {
        $findserie->close();
        $page->htmlHelper->headerLocation();
        return;
    }

    $findserie->bind_result($result->serie, $result->nAlbums);
    $findserie->fetch();
    $findserie->close();

    return $result;
}


function getAdminLinks($isGI, $bdId, $borrowed) {
    if(!$isGI) {
        return "";
    }

    global $page;

    $body = $page->bodyBuilder->anchor("insert.php?id=$bdId", "edit");
    $body .= "&nbsp;\n";

    if($borrowed) {
        $body .= $page->bodyBuilder->anchor("../missings/index.php?view=bds$bdId#bds$bdId", "who");
        return $body;

    }

    $body .= $page->bodyBuilder->anchor("../missings/insert.php?db=bds&amp;id=$bdId", "borrow");
    return $body;
}


function getPageTitle($serie) {
    if($serie == "") {
        return "Hors s&eacute;rie (BDs)";
    }

    return "$serie (BDs)";
}


function rHead($isGI, $serieId) {
    global $page;

    $body = $page->bodyBuilder->anchor("../missings/index.php?view=bds", "Missing BDs");
    if(!$isGI) {
        return $body;
    }

    $body .= "<br />\n";
    if($serieId > 1) {
        $body .= $page->bodyBuilder->anchor("serie_insert.php?id=$serieId", "Edit BD serie") . "<br />\n";
    }
    $body .= $page->bodyBuilder->anchor("insert.php?serie_id=$serieId", "Add a BD") . "<br />\n";
    $body .= $page->bodyBuilder->anchor("serie_insert.php", "Add a BD serie");

    return $body;
}


function getBody($serieId) {
    global $page;

    $body = "";

    $isGI = $page->loginHelper->userIsAdmin();

    // Find serie
    $serieDetails = getSerieDetails($serieId);
    $serie = $serieDetails->serie;
    $nAlbums = $serieDetails->nAlbums;

    $pageTitle = getPageTitle($serie);

    $body .= $page->bodyBuilder->goHome("..");
    $body .= $page->htmlHelper->setTitle($pageTitle);

    $page->htmlHelper->hotBooty();

    $body .= "<div class=\"wide\">\n";
    $body .= "<div class=\"lhead\">\n";
    //$body .= $page->bodyBuilder->anchor("search.php", "Search");
    $body .= "</div><!-- lhead -->\n";
    $body .= "<div class=\"chead\"></div>\n";
    // Propose to add a new
    $body .= "<div class=\"rhead\">\n";
    $body .= rHead($isGI, $serieId);
    $body .= "</div><!-- rhead -->\n";

    $body .= "</div><!-- wide -->\n";


    // Fetch all from this serie
    $displaySerie = $page->bobbyTable->idManage("SELECT * FROM `bds` WHERE `serie_id` = ? ORDER BY `tome` ASC, `title` ASC", $serieId);
    $displaySerie->store_result();

    if($displaySerie->num_rows == 0) {
        $displaySerie->close();
        return "<div>No BD yet</div>\n";
    }

    if($nAlbums > 0 && $displaySerie->num_rows >= $nAlbums) {
        $body .= "<div class=\"bd_serie_complete\">S&eacute;rie compl&egrave;te</div>\n";
    }

    $bdId = NULL;
    $isbnNOTUSED = NULL;
    $tome = NULL;
    $title = NULL;
    $tiNOTUSED = NULL;
    $author = NULL;
    $publisher = NULL;
    $date = NULL;
    $borrowed = NULL;
    $displaySerie->bind_result($bdId, $isbnNOTUSED, $serieId, $tome, $title, $tiNOTUSED, $author, $publisher, $date, $borrowed);
    $body .= "<div class=\"bd_serie_table\">\n";
    $body .= $page->butler->tableOpen(array("class" => "bd_serie_table"));
    while($displaySerie->fetch()) {
        $isbor = "";
        if($borrowed == "1") {
            $isbor = " away";
        }
        $body .= $page->butler->rowOpen(array("class" => "bd_serie_table$isbor"));

        $body .= $page->butler->cell(getAdminLinks($isGI, $bdId, $borrowed), array("class" => "bd_serie_edit"));
        $body .= $page->butler->cell($tome > 0 ? $tome : "", array("class" => "bd_serie_table_tome ten"));
        $body .= $page->butler->cell($title, array("class" => "bd_serie_table_title"));
        $body .= $page->butler->cell($author, array("class" => "bd_serie_table_author"));

        $body .= $page->butler->rowClose();
    }
    $body .= $page->butler->tableClose();
    $body .= "</div><!-- bd_serie_table -->\n";

    $displaySerie->close();

    return $body;
}


echo getBody($_GET["id"]);
?>
