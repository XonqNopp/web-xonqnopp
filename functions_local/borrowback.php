<?php
function borrow_back(PhPage $page, $dbTable, $back, $backId) {
	$page->loginHelper->notAllowed();
	// Get id
	$backId = 0;
	if($back != "") {
		$backId = $back;
	}
	// Check we have id
	if($backId === NULL || $backId == 0) {
		$page->logger->fatal("back id undefined");
	}

	$query = $page->dbHelper->queryPrepare("UPDATE `{$page->dbHelper->dbName}`.`$dbTable` SET `borrowed` = 0 WHERE `$dbTable`.`id` = ? LIMIT 1;");
	$query->bind_param("i", $backId);
	$frommissing = $page->dbHelper->queryManage("SELECT * FROM `missings` WHERE `dbtable` = '$dbTable' AND `dbid` = $backId");
	if($frommissing->num_rows != 1) {
		$page->logger->fatal("Borrowed item not found in missing database");
	}
	$missingitem = $frommissing->fetch_object();
	$frommissing->close();
	$missingId = $missingitem->id;
	$mdb = $page->dbHelper->queryPrepare("DELETE FROM `{$page->dbHelper->dbName}`.`missings` WHERE `missings`.`id` = ? LIMIT 1;");
	$mdb->bind_param("i", $missingId);
	$page->dbHelper->executeManage($query);
	$page->dbHelper->executeManage($mdb);
}
?>
