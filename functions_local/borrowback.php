<?php
function borrow_back(PhPage $page, $db) {
	if(isset($_GET["back"])) {
		$page->NotAllowed();
		//// Get id
		$id = 0;
		if($_GET["back"] != "") {
			$id = $_GET["back"];
		} elseif(isset($_GET["id"])) {
			$id = $_GET["id"];
		}
		//// Check we have id
		if($id == 0) {
			$page->FatalError("back id undefined");
		}
		////
		$the_db = $page->DB_QueryPrepare("UPDATE `" . $page->ddb->DBname . "`.`$db` SET `borrowed` = 0 WHERE `$db`.`id` = ? LIMIT 1;");
		$the_db->bind_param("i", $id);
		$frommissing = $page->DB_QueryManage("SELECT * FROM `missings` WHERE `dbtable` = '$db' AND `dbid` = $id");
		if($frommissing->num_rows != 1) {
			$page->FatalError("Borrowed item not found in missing database");
		}
		$missingitem = $frommissing->fetch_object();
		$frommissing->close();
		$m_id = $missingitem->id;
		$mdb = $page->DB_QueryPrepare("DELETE FROM `" . $page->ddb->DBname . "`.`missings` WHERE `missings`.`id` = ? LIMIT 1;");
		$mdb->bind_param("i", $m_id);
		$page->DB_ExecuteManage($the_db);
		$page->DB_ExecuteManage($mdb);
	}
}
?>
