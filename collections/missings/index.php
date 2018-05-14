<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->initDB();

$page->CSS_ppJump(2);
$page->CSS_ppWing();

$view = "";
if(isset($_GET["view"])) {
	$view = $_GET["view"];
}

$tables = array("bds" => "BD", "books" => "book", "dvds" => "DVD", "games" => "game");

$body = "";
$args = new stdClass();
$args->page = "..";
$body .= $page->GoHome($args);
$body .= $page->SetTitle("Missing items");
$page->HotBooty();

$body .= "<div class=\"whole\">\n";
$check_do_miss = $page->DB_QueryManage("SELECT COUNT(*) AS `howmany` FROM `missings`");
$check_fetch = $check_do_miss->fetch_object();
$check_do_miss->close();
if($check_fetch->howmany > 0) {
	$borrowers = $page->DB_QueryManage("SELECT * FROM `borrowers` ORDER BY `name` ASC");
	if($borrowers->num_rows == 0) {
		$body .= "Nobody is registered as a borrower...\n";
	} else {
		$body .= "<div class=\"missing_display_table\">\n";
		$body .= "<table class=\"missing_display\">\n";
		while($person = $borrowers->fetch_object()) {
			$b_id = $person->id;
			$b_name = $person->name;
			$missings = $page->DB_QueryManage("SELECT * FROM `missings` WHERE `borrower` = $b_id ORDER BY `when` ASC, `dbtable` ASC, `id` ASC");// Check if another order may be usefull
			if($missings->num_rows > 0) {
				$body .= "<tr>\n";
				if($view == "borrower$b_id") {
					$cssborrower = " missing_display_name_borrower";
					$cssitems = " missing_display_borrowed";
				} else {
					$cssborrower = "";
					$cssitems = "";
				}
				$body .= "<td class=\"missing_display_name$cssborrower\" colspan=\"2\"><a id=\"borrower$b_id\">$b_name</a></td>\n";
				$body .= "</tr>\n";
				while($item = $missings->fetch_object()) {
					$m_id = $item->id;
					$dbtable = $item->dbtable;
					$dbid = $item->dbid;
					$date = $item->when;
					$iteminfo = $page->DB_QueryManage("SELECT * FROM `$dbtable` WHERE `id` = $dbid");
					$device = $iteminfo->fetch_object();
					$iteminfo->close();
					$title = "";
					$fulltitle = "";
					$fulltitle2 = "";
					$title_needed = true;
					if($dbtable == "games") {
						$title = $device->name;
					} else {
						if($device->title != "") {
							$title = $device->title;
							$fulltitle = $title;
							$title_needed = false;
						}
						if($dbtable == "bds") {
							$serie = "";
							if($device->serie_id > 1) {
								$serie_query = $page->DB_QueryManage("SELECT * FROM `bd_series` WHERE `id` = $device->serie_id");
								$serie_entry = $serie_query->fetch_object();
								$serie = $serie_entry->name;
							}
							if($title_needed) {
								$title = $serie;
							}
							$fulltitle2 .= $serie;
							if($device->tome != "" && $device->tome != "0") {
								if($title_needed) {
									$title .= " ($device->tome)";
								}
								$fulltitle2 .= " ($device->tome)";
							}
						} else {
							if($title_needed) {
								$title = $device->serie;
							}
							$fulltitle2 .= $device->serie;
							if($device->number != "" && $device->number != "0") {
								if($title_needed) {
									$title .= " ($device->number)";
								}
								$fulltitle2 .= " ($device->number)";
							}
						}
					}
					if($fulltitle != "" && $fulltitle2 != "") {
						$fulltitle .= ", $fulltitle2";
					}
					if($view == "$dbtable$dbid" || $view == $dbtable) {
						$csswanted = " missing_wanted";
					} else {
						$csswanted = "";
					}
					if($dbtable == "bds") {
						$serie_id = $device->serie_id;
					}
					$body .= "<tr class=\"missing_display$csswanted$cssitems\">\n";
					if($page->UserIsAdmin()) {
						$body .= "<td class=\"missing_back\">\n";
						$ink = "index";
						$moreBD = "";
						if($dbtable == "bds") {
							$ink = "serie_display";
							$moreBD = "&amp;id=$serie_id";
						}
						$body .= "<a href=\"../$dbtable/$ink.php?back=$dbid$moreBD\" title=\"back\">back</a>\n";
						$body .= "</td>\n";
					}
					$body .= "<td class=\"missing_display_title\">\n";
					// FULLTITLE for link title
					$body .= "<a id=\"$dbtable$dbid\"";
					$ink = "index";
					$GETid = $dbid;
					if($dbtable == "bds") {
						$ink = "serie_display";
						$GETid = $serie_id;
					}
					$body .= " href=\"../$dbtable/$ink.php?id=$GETid\"";
					$body .= " title=\"$fulltitle\">";
					$body .= "$title";
					$body .= " (" . $tables[$dbtable] . ")";
					$body .= "</a>";
					$body .= "\n";
					$body .= "</td>\n";
					$body .= "<td class=\"missing_display_date\">\n";
					$body .= "$date\n";
					$body .= "</td>\n";
					$body .= "</tr>\n";
				}
			}
			$missings->close();
		}
		$body .= "</table>\n";
		$body .= "</div>\n";
	}
	$borrowers->close();
} else {
	$body .= "Nothing is being borrowed for now...\n";
}
$body .= "</div>\n";

$page->show($body);
unset($page);
?>
