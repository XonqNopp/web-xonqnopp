<?php
/*** TODO:
 * searchbox to classpage
 * tabledisplay
 * SetTitle
 * Contents for always, results only when asked and below regular content
 */
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
require("$funcpath/convert.php");// for tabledisplay.php
require("$funcpath/tabledisplay.php");
require("$funcpath/searchbox.php");

$page = new PhPage($rootPath);
$page->initDB();

$page_title = "";

$page->SetTitle($page_title);
$page->CSS_ppJump(2);
$page->CSS_ppWing();
$page->js_Form();
$page->js_Push("check", "../../functions");

$body = "";
$body .= $page->GoHome();

if(isset($_POST["search"])) {
	// Display results
	$page_title = "Search results";
	$which = $_POST["which"];
	$Title = $page->field2SQL($_POST["Title"]);
	$Author = $page->field2SQL($_POST["Author"]);
	$Publisher = $page->field2SQL($_POST["Publisher"]);
	$Date = $_POST["Date"];
	$date_type = $_POST["date_type"];
	$date_op = "";
	switch($date_type) {
		case "gt":
			$date_op = " > ";
			break;
		case "ge":
			$date_op = " >= ";
			break;
		case "lt":
			$date_op = " < ";
			break;
		case "le":
			$date_op = " <= ";
			break;
		case "x":
			$date_op = " = ";
			break;
	}
	$default_sql = "SELECT *, ";
	$default_sql .= $page->DB_SortAlpha("serie") . ", ";
	$default_sql .= $page->DB_SortAlpha("title") . " ";
	$default_sql .= "FROM `bds` WHERE ";
	$sql_request = $default_sql;
	foreach($which as $w) {
		if($w != "Date") {
			$sql_request .= "LOWER(`$w`) LIKE '%" . strtolower($$w) . "%', ";
		} else {
			$sql_request .= "YEAR(`date`) $date_op '$Date', ";
		}
	}
	if($sql_request == $default_sql) {
		// Nothing asked, redisplay page (should not happen because submit button enabled only if something selected)
		$page->HeaderLocation("search.php");
		exit;
	} else {
		$page->HotBooty();
		$sql_request = substr($sql_request,0,-2);// removes last useless coma
		$sql_request .= "ORDER BY " . $page->DB_OrderAlpha("serie") . ", `tome` ASC, " . $page->DB_OrderAlpha("title");
		$bds = $page->DB_QueryManage($sql_request);
		$body .= "<h1>$page_title</h1>\n";
		//$body .= $sql_request;
		$body .= "<div class=\"bd_search_main\">\n";
		$body .= "<!-- *** SAVE SEARCH PARAMETERS *** -->\n";
		$body .= "<form action=\"search.php\" method=\"post\">\n";

		$args = new stdClass();
		$args->type = "hidden";

		// again
		$args->name = "again";
		$body .= $page->FormField($args);

		// which
		$args->name = "which";
		$args->value = "";
		foreach($which as $w) {
			if($args->value != "") {
				$args->value .= ",";
			}
			$args->value .= $w;
		}
		$body .= $page->FormField($args);

		// Title
		$args->name = "Title";
		$args->value = $Title;
		$body .= $page->FormField($args);

		// Author
		$args->name = "Author";
		$args->value = $Author;
		$body .= $page->FormField($args);

		// Publisher
		$args->name = "Publisher";
		$args->value = $Publisher;
		$body .= $page->FormField($args);

		// Date
		$args->name = "Date";
		$args->value = $Date;
		$body .= $page->FormField($args);

		// date_type
		$args->name = "date_type";
		$args->value = $date_type;
		$body .= $page->FormField($args);

		$body .= "<div class=\"rhead button_link\">\n";
		$body .= "<input type=\"submit\" name=\"change\" value=\"Change\" />\n";
		$body .= "</div>\n";
		$body .= "</form>\n";
		$body .= "<!-- *** DISPLAY THE RESULTS *** -->\n";
		if($bds->num_rows == 0) {
			$body .= "Sorry, your search criteria did not return any entry...\n";
		} else {
			$bds_array = array();
			while($b = $bds->fetch_object()) {
				$bds_array[] = $b;
			}
			$bds_fields = array("serie" => "serie", "tome" => "0", "title" => "", "author" => "", "borrowed" => "borrowed");
			$bds_struct = array("serie", " ", "tome", " ", "title", " ", "author");
			$bds_width = 2;
			$body .= table_display($bds_array,$bds_fields,$bds_struct,"Hors series",$bds_width,"serie_display","id","bd_search_table","bd_search_table","bd_search_table", true, "serie_display","bd_search_edit","collection","bd_search_borrow","bds");// Change bd_collection for back items
			// Style author and tome
		}
		$bds->close();
		$body .= "<div>\n";
	}
} else {
	$page->HotBooty();
	$the_which = array("Title" => false, "Author" => false, "Publisher" => false, "Date"  => false);
	$disbut = " disabled=\"disabled\"";
	if(isset($_POST["again"])) {
		$disbut = "";
		$which = explode(",",$_POST["which"]);
		foreach($the_which as $tw => $v) {
			if(in_array($tw, $which)) {
				$the_which[$tw] = true;
			}
		}
		$Title = $page->SQL2field($_POST["Title"]);
		$Author = $page->SQL2field($_POST["Author"]);
		$Publisher = $page->SQL2field($_POST["Publisher"]);
		$Date = $_POST["Date"];
		$date_type = $_POST["date_type"];
	}
	// Ask smth
	$page_title = "Search a BD field";
	$body .= "<h1>$page_title</h1>\n";
	$body .= "<form action=\"search.php\" method=\"post\">\n";
	$body .= "<div class=\"bd_search_main\">\n";
	$body .= "<table class=\"bd_search\">\n";
	// Title
	$body .= search_box("Title","which",$the_which,"search",$Title,"bd_search");
	// Author
	$body .= search_box("Author","which",$the_which,"search",$Author,"bd_search");
	// Publisher
	$body .= search_box("Publisher","which",$the_which,"search",$Publisher,"bd_search");
	// Date
	$body .= date_box(1940,"which",$the_which,"search",$Date,$date_type,"bd_search");
	// Buttons
	$fields_array = array("Title","Author","Publisher","Date");
	$body .= search_buttons("search","Search","Reset","Cancel",$disbut,$fields_array,"bd_search");
	$body .= "</table>\n";
	$body .= "</div>\n";
	$body .= "</form>\n";
}

/*** Printing ***/
$page->show($body);
unset($page);
?>
