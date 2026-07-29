<?php
function session()
{
	/* stilili */
	if(!isset($_SESSION["stilili"])) {
		$_SESSION["stilili"] = false;
	}
	/* xonqnopp */
	if(!isset($_SESSION["xonqnopp"])) {
		$_SESSION["xonqnopp"] = false;
	}
	/* language */
	if(!isset($_SESSION["language"])) {
		$_SESSION["language"] = "french";
	}
	if(isset($_GET["lang"])) {
		if($_GET["lang"] == "fr") {
			$_SESSION["language"] = "french";
		} elseif($_GET["lang"] == "wolof") {
			$_SESSION["language"] = "wolof";
		} elseif($_GET["lang"] == "manding") {
			$_SESSION["language"] = "manding";
		} else {
			$_SESSION["language"] = "english";
		}
	}
}

function nofrench()
{
	if($_SESSION["language"] == "french") {
		$_SESSION["language"] = "english";
	}
}

function noenglish()
{
	if($_SESSION["language"] == "english") {
		$_SESSION["language"] = "french";
	}
}

function nowolof()
{
	if($_SESSION["language"] == "wolof") {
		$_SESSION["language"] = "french";
	}
}

function nomanding()
{
	if($_SESSION["language"] == "manding") {
		$_SESSION["language"] = "french";
	}
}
?>
