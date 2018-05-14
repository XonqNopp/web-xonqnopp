<?php
$lang = "francais";
if(isset($_GET["language"])) {
	$lang = $_GET["language"];
}

$instructions = "";
if(isset($_GET["instructions"])) {
	$instructions = "#instructions";

}

header("Location: http://www.xonqnopp.ch/fly/PAX.php?language=$lang$instructions");
?>
