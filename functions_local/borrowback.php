<?php
function borrow_back(PhPage $page, $dbTable, $back, $backId) {
	$page->NotAllowed();
	// Get id
	$backId = 0;
	if($back != "") {
		$backId = $back;
	}
	// Check we have id
	if($backId === NULL || $backId == 0) {
		$page->FatalError("back id undefined");
	}

	$query = $page->DB_QueryPrepare("UPDATE `" . $page->ddb->DBname . "`.`$dbTable` SET `borrowed` = 0 WHERE `$dbTable`.`id` = ? LIMIT 1;");
	$query->bind_param("i", $backId);
	$frommissing = $page->DB_QueryManage("SELECT * FROM `missings` WHERE `dbtable` = '$dbTable' AND `dbid` = $backId");
	if($frommissing->num_rows != 1) {
		$page->FatalError("Borrowed item not found in missing database");
	}
	$missingitem = $frommissing->fetch_object();
	$frommissing->close();
	$missingId = $missingitem->id;
	$mdb = $page->DB_QueryPrepare("DELETE FROM `" . $page->ddb->DBname . "`.`missings` WHERE `missings`.`id` = ? LIMIT 1;");
	$mdb->bind_param("i", $missingId);
	$page->DB_ExecuteManage($query);
	$page->DB_ExecuteManage($mdb);
}
?>
