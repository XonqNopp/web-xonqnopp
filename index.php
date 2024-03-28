<?php
require("functions/page_helper.php");
$page = new PhPage();
//$page->checkWww();  // TODO what now?
//$page->logLevelUp(6);
$page->dbHelper->init();

	// Checking for testament
	require("testament/warning.php");
	testamentWarning($page);
	$testament = testamentDisplay($page);
//
	/*** Prepare text ***/
	$fly = "Fly";
	if($page->languageHelper->checkSessionLang("french")) {
		$title = "Bienvenue sur le website de Xonq Nopp !!";
		$links = "D'autres liens...";
		$mines = "Sites &eacute;crits";
		$randhead = "Une citation au hasard parmi les ";
		$recettes = "Recettes";
		$collections = "Mes collections";
	} else {
		$title = "Welcome to Xonq Nopp&#039;s website!!";
		$links = "Some other links...";
		$mines = "Written websites";
		$randhead = "A random quotation among the ";
		$recettes = "Recipes (french only)";
		$collections = "My collections";
	}
//
$body = "";

$body .= $page->languageHelper->languages();
$body .= $page->htmlHelper->setTitle($title, "main");
$page->htmlHelper->hotBooty();

$body .= $testament;

	/*** QUOTATIONS ***/
		// DB management
			// Count
			$the_count = $page->dbHelper->getCount("quotations");
		//
			// A random citation
			$randsql = $page->dbHelper->randomEntry("quotations");
			$randquot = $randsql->fetch_object();
			$randsql->close();
	//
		// To screen
		$body .= "<div class=\"idxquot\">\n";
		$randid          = $randquot->id;
		$randlastauthor  = $randquot->authorlast;
		$randfirstauthor = $randquot->authorfirst;
		$randbody        = $randquot->quote;
		$randbook        = $randquot->place;
		$inter = " ";
		if($randfirstauthor === NULL || substr($randfirstauthor, -1) == "'") {
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
$body .= $page->tableHelper->open();
$body .= $page->tableHelper->rowOpen();

	/*** flying stuff ***/
	$body .= $page->tableHelper->cellOpen("fly");
	$body .= "<a href=\"fly/index.php\" title=\"$fly\">\n";
	$body .= "<img src=\"pictures/hornet.png\" alt=\"$fly\" title=\"$fly\" />\n";
	$body .= "<br/>Fly\n";
	$body .= "</a>\n";
	$body .= $page->tableHelper->cellClose();
//
	/*** Recettes ***/
	$body .= $page->tableHelper->cellOpen("coll");
	$body .= "<a href=\"recettes/index.html\" title=\"$recettes\">\n";
	$body .= "<img src=\"pictures/asterix.png\" alt=\"$recettes\" title=\"$recettes\" />\n";
	$body .= "<br/>$recettes\n";
	$body .= "</a>\n";
	$body .= $page->tableHelper->cellClose();
//
	/*** Collections ***/
	$body .= $page->tableHelper->cellOpen("coll");
	$body .= "<a href=\"collections/index.php\" title=\"$collections\">\n";
	$body .= "<img src=\"pictures/jenga.png\" alt=\"$collections\" title=\"$collections\" />\n";
	$body .= "<br/>Collections\n";
	$body .= "</a>\n";
	$body .= $page->tableHelper->cellClose();
//
	/*** job stuff ***/
	$body .= $page->tableHelper->cellOpen("job");
	$body .= "<a href=\"job/index.php\" title=\"job\">\n";
	$body .= "<img src=\"pictures/leprechaun.png\" alt=\"job\" title=\"job\" />\n";
	$body .= "<br/>Job\n";
	$body .= "</a>\n";
	$body .= $page->tableHelper->cellClose();

$body .= $page->tableHelper->rowClose();
$body .= $page->tableHelper->close();

	// External links
	$body .= "<div><a href=\"/links.php\" title=\"$links\">$links</a></div>\n";

// Login/Logout
$logPage = "login";
if($page->loginHelper->userIsAdmin()) {
	$logPage = "logout";
}
$body .= "<div><a href=\"$logPage.php\" title=\"$logPage\">$logPage</a></div>\n";

if($page->loginHelper->userIsAdmin()) {
	$body .= "<div>\n";
	$body .= "<a href=\"../testament/index.php\">T</a>\n";
	$body .= "<a href=\"../testament/reset.php\">R</a>\n";
	$body .= "<a href=\"http://b13d3axybd.preview.infomaniak.website/\">P</a>\n";
	$body .= "</div>\n";
}


echo $body;
//$page->close();  // TODO test if without unset it works
?>
