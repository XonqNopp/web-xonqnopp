<?php
// TODO:
// * rearrange page
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->initHTML();
//$page->LogLevelUp(6);
$page->initDB();
$UserIsAdmin = $page->UserIsAdmin();
//
$page->CSS_ppJump();
$page->SetTitle("Collections");
$page->HotBooty();

$body = "";
$args = new stdClass();
$args->page = "..";
$body .= $page->GoHome($args);
$body .= "<h1>Collections</h1>\n";
$body .= "<div>\n";
	$body .= "<ul>\n";
	/* DISABLED
		// DVD
		$getcount = $page->DB_QueryManage("SELECT COUNT(*) AS `the_count` FROM `dvds`");
		$fetch_count = $getcount->fetch_object();
		$dvd_count = $fetch_count->the_count;
		$getcount->close();
		$body .= "<div class=\"i_b index_dvd\">\n";
		$body .= "<a href=\"dvds/index.php\" title=\"DVDs\">";
		$body .= "<img class=\"index_dvd\" alt=\"DVDs\" title=\"DVDs\" src=\"pictures/dvd.gif\" />";
		$body .= "</a>\n";
		if($UserIsAdmin) {
			$body .= "<br />\n<a href=\"dvds/insert.php\" title=\"Add a DVD\">New</a>\n";
			//$body .= "&nbsp;<a href=\"insert_barcode.php\" title=\"Fetching infos\">IMDB</a>";
		}
		if($dvd_count > 0) {
			$body .= "<br />\n<span class=\"leb\">($dvd_count)</span>\n";
		}
		$body .= "</div>\n";
	//
		// Book
		$getcount = $page->DB_QueryManage("SELECT COUNT(*) AS `the_count` FROM `books`");
		$fetch_count = $getcount->fetch_object();
		$book_count = $fetch_count->the_count;
		$getcount->close();
		$body .= "<div class=\"i_b index_book\">\n";
		$body .= "<a href=\"books/index.php\" title=\"Books\">";
		$body .= "<img class=\"index_book\" alt=\"Books\" title=\"Books\" src=\"pictures/book.gif\" />";
		$body .= "</a>\n";
		if($UserIsAdmin) {
			$body .= "<br />\n<a href=\"books/insert.php\" title=\"Add a Book\">New</a>";
		}
		if($book_count > 0) {
			$body .= "<br />\n<span class=\"leb\">($book_count)</span>\n";
		}
		$body .= "</div>\n";
	/DISABLED */
	//
		// BD
		$getcount = $page->DB_QueryManage("SELECT COUNT(*) AS `the_count` FROM `bds`");
		$fetch_count = $getcount->fetch_object();
		$bd_count = $fetch_count->the_count;
		$getcount->close();
		$getcount = $page->DB_QueryManage("SELECT COUNT(*) AS `the_count` FROM `bd_series`");
		$fetch_count = $getcount->fetch_object();
		$serie_count = $fetch_count->the_count;
		$getcount->close();
		$body .= "<li>\n";
		$body .= "<a href=\"bds/index.php\" title=\"BDs\">BDs</a>\n";
		if($bd_count > 0) {
			$body .= "&nbsp;<span class=\"leb\">($serie_count s&eacute;ries, $bd_count BDs)</span>\n";
		}
		if($UserIsAdmin) {
			$body .= "&nbsp;new <a href=\"bds/insert.php\" title=\"Add a BD\">BD</a>";
			$body .= "/<a href=\"bds/serie_insert.php\" title=\"Add a BD serie\">serie</a>";
			//$body .= "/<a href=\"insert_isbn.php\" title=\"Add by ISBN\">ISBN</a>";
			$body .= "\n";
		}
		//$body .= "<a href=\"bds/search.php\" title=\"Search\">??</a>\n";
		$body .= "</li>\n";
	//
		// Borrower
		$getcount = $page->DB_QueryManage("SELECT COUNT(*) AS `the_count` FROM `borrowers`");
		$fetch_count = $getcount->fetch_object();
		$borrower_count = $fetch_count->the_count;
		$getcount->close();
		$body .= "<li>\n";
		$body .= "<a href=\"borrowers/index.php\" title=\"Borrowers\">borrowers</a>\n";
		if($borrower_count > 0) {
			$body .= "&nbsp;<span class=\"leb\">($borrower_count)</span>\n";
		}
		if($UserIsAdmin) {
			$body .= "&nbsp;<a href=\"borrowers/insert.php\" title=\"Add a borrower\">new</a>\n";
		}
		$body .= "</li>\n";
	//
		// Missing
		$getcount = $page->DB_QueryManage("SELECT COUNT(*) AS `the_count` FROM `missings`");
		$fetch_count = $getcount->fetch_object();
		$missing_count = $fetch_count->the_count;
		$getcount->close();
		$body .= "<li>\n";
		$body .= "<a href=\"missings/index.php\" title=\"Missings\">missing</a>\n";
		if($missing_count > 0) {
			$body .= "&nbsp;<span class=\"leb\">($missing_count)</span>\n";
		}
		$body .= "</li>\n";
	//
		// Quotations
		$getcount = $page->DB_QueryManage("SELECT COUNT(*) AS `the_count` FROM `quotations`");
		$fetch_count = $getcount->fetch_object();
		$quote_count = $fetch_count->the_count;
		$getcount->close();
		$body .= "<li>\n";
		$body .= "<a href=\"quotations/index.php\" title=\"Citations\">Quotes</a>\n";
		if($quote_count > 0) {
			$body .= "&nbsp;<span class=\"leb\">($quote_count)</span>\n";
		}
		if($UserIsAdmin) {
			$body .= "&nbsp;<a href=\"quotations/insert.php\" title=\"Ajouter une citation\">new</a>\n";
		}
		$body .= "</li>\n";
	//
		// Elephants
		$elephants = "Les &eacute;l&eacute;phants";
		$body .= "<li>\n";
		$body .= "<a href=\"elephants.php\" title=\"$elephants\">$elephants</a>\n";
		$body .= "</li>\n";
	//
	/* DISABLED
		// When I was young...
		$body .= "<li>\n";
		$body .= "<a href=\"wheniwasyoung.php\" title=\"When I was young...\">before...</a>\n";
		$body .= "</li>\n";
	/DISABLED */
	//
		// education
		$body .= "<li><a href=\"education.php\">positive education</a></li>\n";
	$body .= "</ul>\n";
$body .= "</div>\n";

/*** Printing ***/
$page->show($body);
unset($page);
?>
