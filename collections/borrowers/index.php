<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->bobbyTable->init();

$page->cssHelper->dirUpWing();

$body = $page->bodyBuilder->goHome("../..", "..");
$body .= $page->htmlHelper->setTitle("List of known borrowers");
$page->htmlHelper->hotBooty();


/**
 * Get the body of this page.
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function getBody() {
    global $page;
    $userIsAdmin = $page->loginHelper->userIsAdmin();
    $body = "";

        // header
        $body .= "<div class=\"wide\">\n";
        $body .= "<div class=\"lhead\"></div>\n";
        $body .= "<div class=\"chead\"></div>\n";
        $body .= "<div class=\"rhead\">\n";
        if($userIsAdmin) {
            // Propose to add a new if authorized
            $body .= $page->bodyBuilder->anchor("insert.php", "Add a borrower");
        }
        $body .= "</div><!-- rhead -->\n";
        $body .= "</div><!-- wide -->\n";

    $query = $page->bobbyTable->queryManage("SELECT * FROM `borrowers` ORDER BY `name` ASC");

    if($query->num_rows == 0) {
        $query->close();
        $body .= "Sorry, no one stored yet...";
        return $body;
    }

    $body .= "<div class=\"borrower_display_table\">\n";
    $body .= $page->butler->tableOpen(array("class" => "borrower_display"));
    $body .= $page->butler->rowOpen(array("class" => "borrower_display_header"));
    $body .= $page->butler->headerCell("Who");
    $body .= $page->butler->headerCell("Number");
    $body .= $page->butler->rowClose();
    while($borrower = $query->fetch_object()) {
        $borrowerId = $borrower->id;
        $name = $borrower->name;
        $body .= $page->butler->rowOpen(array("class" => "borrower_display"));
        $body .= $page->butler->cellOpen(array("class" => "borrower_display"));
        if($userIsAdmin) {
            $body .= $page->bodyBuilder->anchor("insert.php?id=$borrowerId", $name);
        } else {
            $body .= "$name\n";
        }
        $body .= $page->butler->cellClose();

        // Fetch count items borrowed
        $howmany = 0;
        $howManyQuery = $page->bobbyTable->idManage("SELECT COUNT(*) AS `how_many` FROM `missings` WHERE `borrower` = ?", $borrowerId);
        $howManyQuery->bind_result($howmany);
        $howManyQuery->fetch();
        $howManyQuery->close();

        $plural = "";
        $howManyLink = "no item";
        if($howmany > 0) {
            if($howmany > 1) {
                $plural = "s";
            }
            $howManyLink = $page->bodyBuilder->anchor("../missings/index.php?view=borrower$borrowerId#borrower$borrowerId", "$howmany item$plural");
        }

        $body .= $page->butler->cell($howManyLink, array("class" => "borrower_display_count"));
        $body .= $page->butler->rowClose();
    }
    $query->close();

    $body .= $page->butler->tableClose();
    $body .= "</div><!-- borrower_display_table -->\n";

    return $body;
}


echo $body . getBody();
?>
