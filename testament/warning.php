<?php
/**
 * Send a warning if testament due date is close.
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function testamentWarning($page) {
    if(isset($_SESSION["testamentwarning"])) {
        // Already warned
        return;
    }

    $_SESSION["testamentwarning"] = false;

    $warndate = $page->bobbyTable->queryManage("SELECT * FROM `{$page->bobbyTable->dbName}`.`testament` HAVING DATEDIFF(`duedate`,CURDATE()) < 7 AND DATEDIFF(CURDATE(),`lastwarning`) > 0");

    if($warndate->num_rows <= 0) {
        // No warning needed
        $warndate->close();
        return;
    }

    $_SESSION["testamentwarning"] = true;

    // Need warning, prepare it
    $entry    = $warndate->fetch_object();

    $duedate  = $entry->duedate;

    $dueyear  = substr($duedate, 0, 4);
    $duemonth = substr($duedate, 5, 2);
    $dueday   = substr($duedate, 8, 2);

    $duedate  = $dueyear * 365 + $duemonth * 30 + $dueday;

    $today      = localtime(time(), true);

    $todayyear  = $today["tm_year"] + 1900;
    $todaymonth = $today["tm_mon"] + 1;
    $todayday   = $today["tm_mday"];

    $today      = $todayyear * 365 + $todaymonth * 30 + $todayday;

    $diffdue = $duedate - $today;

    // update lastwarning
    $page->bobbyTable->queryManage("UPDATE `{$page->bobbyTable->dbName}`.`testament` SET `lastwarning` = CURDATE() WHERE `testament`.`id` = 1 LIMIT 1;");

    // send mail to warn
    $emailAddress = $page->miscInit->myEmail;
    $subject = "[xonqnopp] warning";
    $message = "testament reset: $diffdue";
    $headers = "From: XonqNopp <info@xonqnopp.chxn>\n";

    if(!$page->serverHelper->isLocalHost()) {
        mail($emailAddress, $subject, $message, $headers);
    } else {
        echo "$subject - $message\n";
    }

    $warndate->close();
}


/**
 * Display testament if due date reached.
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
function testamentDisplay($page) {
    $bestBefore = $page->loginHelper->userIsAdmin() ? 5 : 0;
    $duedate = $page->bobbyTable->queryManage("SELECT * FROM `{$page->bobbyTable->dbName}`.`testament` HAVING DATEDIFF(`duedate`,CURDATE()) >= $bestBefore");
    $nRows = $duedate->num_rows;
    $duedate->close();

    if($nRows > 0) {
        return "";
    }

    if($nRows == 0 && (!isset($_SESSION["testamentOK"]) || $_SESSION["testamentOK"] === false)) {
        $_SESSION["testamentOK"] = true;
        $page->htmlHelper->headerLocation("testament/index.php");
        return "";
    }

    $content = "<div class=\"index_testament\">\n";
    $content .= $page->bodyBuilder->anchor("testament/index.php", "Mon testament...");
    $content .= "</div>\n";
    return $content;
}
?>
