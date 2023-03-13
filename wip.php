<?php
if(isset($_GET["sha"])) {
	echo hash("sha512", $_GET["sha"]);
} else {
	header("Location: index.php");
}
?>
