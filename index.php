<?php
/* TODO:
 *
 */
require("functions/classPage.php");
$page = new PhPage();
//$page->check_www();
$page->LogLevelUp(6);
$args = new stdClass();
$args->redirect = "";
$page->LoginCookie($args);
$page->initDB();
	/*** Checking for testament ***/
		/*** Check due date to warn ***/
		if(!isset($_SESSION["testamentwarning"])) {
			$_SESSION["testamentwarning"] = true;
			$warndate = $page->DB_QueryManage("SELECT * FROM `" . $page->ddb->DBname . "`.`testament` HAVING DATEDIFF(`duedate`,CURDATE()) < 7 AND DATEDIFF(CURDATE(),`lastwarning`) > 0");
			if($warndate->num_rows > 0) {
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
				/*** update lastwarning ***/
				$newwarning = $page->DB_QueryManage("UPDATE `" . $page->ddb->DBname . "`.`testament` SET `lastwarning` = CURDATE() WHERE `testament`.`id` = 1 LIMIT 1;");
				/*** send mail to warn ***/
				$to = "gael.induni@gmail.com";
				$subject = "[xonqnopp] warning";
				$message = "testament reset: $diffdue";
				$headers = "From: XonqNopp <info@xonqnopp.chxn>\n";
				if(!$page->LocalHost()) {
					mail($to, $subject, $message, $headers);
				} else {
					echo "$subject - $message\n";
				}
			}
			$warndate->close();
		}
	//
		/*** Check if must display ***/
		$testamentOK = 0;
		$duedate = $page->DB_QueryManage("SELECT * FROM `" . $page->ddb->DBname . "`.`testament` HAVING DATEDIFF(`duedate`,CURDATE()) >= 0");
		if($duedate->num_rows == 0) {
			$testamentOK = 1;
		}
		$duedate->close();
		if($testamentOK && !isset($_SESSION["testamentOK"])) {
			$_SESSION["testamentOK"] = true;
			$page->HeaderLocation("testament/index.php");
		}
//
	/*** Prepare text ***/
	$sam = "Djelya Cafo";
	$toubacouta = "Touba Couta";
	$fly = "Fly";
	if($page->CheckSessionLang($page->GetFrench())) {
		$title = "Bienvenue sur le website de Xonq Nopp !!";
		$links = "D'autres liens...";
		$mines = "Sites &eacute;crits";
		$nopp = "Nidji souffle mandingue";
		$randhead = "Une citation au hasard parmi les ";
		$collections = "Mes collections";
		$testament = "Mon testament...";
	} else {
		$title = "Welcome to Xonq Nopp&#039;s website!!";
		$links = "Some other links...";
		$mines = "Written websites";
		$nopp = "Nidji souffle mandingue";
		$randhead = "A random quotation among the ";
		$collections = "My collections";
		$testament = "My testament...";
	}
//
$body = "";
//$page->CSS_Push("index");

$body .= $page->Languages();
$ta = new stdClass();
$ta->id = "main";
$body .= $page->SetTitle($title, $ta);
$page->HotBooty();

	/*** Testament ***/
	if($testamentOK) {
		$body .= "<div class=\"index_testament\">\n";
		$body .= "<a href=\"testament/index.php\" title=\"$testament\">$testament</a>\n";
		$body .= "</div>\n";
	}
//
	/*** QUOTATIONS ***/
		// DB management
			// Count
			$the_count = $page->DB_GetCount("quotations");
		//
			// A random citation
			$randid = $page->DB_RandomEntry("quotations");
			$randquery = "SELECT * FROM `quotations` WHERE `id` = $randid";
			$randsql = $page->DB_QueryManage($randquery);
			$randquot = $randsql->fetch_object();
			$randsql->close();
	//
		// To screen
		$body .= "<div class=\"idxquot\">\n";
		$randlastauthor  = $randquot->authorlast;
		$randfirstauthor = $randquot->authorfirst;
		$randbody        = $randquot->quote;
		$randbook        = $randquot->place;
		$inter = " ";
		if(substr($randfirstauthor, -1) == "'") {
			$inter = "";
		}
		$randauthor = "$randfirstauthor$inter$randlastauthor";
		$body .= "<div class=\"idxquotrand\">\n";
		$body .= "<div class=\"idxquotheader\">$randhead$the_count</div>\n";
		$body .= "<div class=\"idxquotbody\"><a href=\"collections/quotations/index.php?favoris#c$randid\" title=\"Acc&eacute;der &agrave; cette citation\">$randbody</a></div>\n";
		if($randauthor != " ") {
			$body .= "<div class=\"idxquotauthor\">$randauthor</div>\n";
		}
		if($randbook != "") {
			$body .= "<div class=\"idxquotbook\">$randbook</div>\n";
		}
		$body .= "</div>\n";
		$body .= "</div>\n";
	//
	//
//
$body .= "<div class=\"csstab64_table\">\n";
$body .= "<div class=\"csstab64_row\">\n";
	/*** flying stuff ***/
	$body .= "<div class=\"csstab64_cell fly\">\n";
	$body .= "<a href=\"fly/index.php\" title=\"$fly\">\n";
	$body .= "<img src=\"pictures/hornet.png\" alt=\"$fly\" title=\"$fly\" />\n";
	$body .= "</a>\n";
	$body .= "</div>\n";
//
	/*** Collections ***/
	$body .= "<div class=\"csstab64_cell coll\">\n";
	$body .= "<a href=\"collections/index.php\" title=\"$collections\">\n";
	$body .= "<img src=\"pictures/jenga.png\" alt=\"$collections\" title=\"$collections\" />\n";
	$body .= "</a>\n";
	$body .= "</div>\n";
//
	/*** job stuff ***/
	$body .= "<div class=\"csstab64_cell job\">\n";
	$body .= "<a href=\"job/index.php\" title=\"job\">\n";
	$body .= "<img src=\"pictures/leprechaun.png\" alt=\"job\" title=\"job\" />\n";
	$body .= "</a>\n";
	$body .= "</div>\n";
//
$body .= "</div>\n";
$body .= "</div>\n";

	// External links
	$body .= "<div class=\"others\"><a href=\"links.php\" title=\"$links\">$links</a></div>\n";

// Login/Logout
$logPage = "login";
if($page->UserIsAdmin()) {
	$logPage = "logout";
}
$body .= "<div><a href=\"$logPage.php\" title=\"$logPage\">$logPage</a></div>\n";

if($page->UserIsAdmin()) {
	$body .= "<div>\n";
	$body .= "<a href=\"../testament/index.php\">T</a>\n";
	$body .= "<a href=\"../testament/reset.php\">R</a>\n";
	$body .= "</div>\n";
}


$page->show($body);
unset($page);
?>
