<?php
function connection()
{
	$localAddr = "127.0.0.1";
	$serverNameLocalhost = preg_match("/localhost$/", $_SERVER["SERVER_NAME"]);  // match end so we can have multiple localhost
	$serverNameLocal = preg_match("/\.local$/", $_SERVER["SERVER_NAME"]);  // match end so we can have multiple localhost
	$serverNameLan   = preg_match("/^192\.168\./", $_SERVER["SERVER_NAME"]);
	$serverAddr = $_SERVER["SERVER_ADDR"] == $localAddr;
	$remoteAddr = $_SERVER["REMOTE_ADDR"] == $localAddr;
	$isLocalhost = ($serverNameLocalhost || $serverNameLocal || $serverNameLan || $serverAddr || $remoteAddr);
	if($isLocalhost) {
		$link = new mysqli("localhost", "localadmin", "localpassword", dbname());
	} else {
		$link = new mysqli("b13d3.myd.infomaniak.com", "b13d3_xonqnopp", "xonqnopp.ch@b13d3.Infomaniak", dbname());
	}
	if(mysqli_connect_errno()){
		echo "<div id=\"error\">Erreur de connection : " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")</div>";
		exit();
	}
	return $link;
}

function dbname()
{
	return "b13d3_xonqnopp_ch";
}

function displerr($query, $link = false, $file = "", $line = 0)
{
	$error = "<div id=\"error\">\n";
	$errorprint = "";
	$errors = "";
	if($_SESSION["admin"] == "admin") {
		if($file != "") {
			$errorprint .= "In file $file ";
		}
		if($line != 0) {
			$errorprint .= "at line $line ";
		}
		if($errorprint != "") {
			$errorprint .= ": <br />\n";
		}
		if($query -> errno != "") {
			$errors .= "Erreur No " . $query -> errno . " : " . $query -> error . ".\n";
		}
		if($link != false) {
			if($link -> errno != "") {
				if($errors != "") {
					$errors .= "<br />\n";
				}
				$errors .= "Erreur No " . $link -> errno . " : " . $link -> error . ".\n";
			}
		}
	} else {
		$errorprint = "Probl&egrave;me dans la gestion de la base de donn&eacute;e. Merci de rapporter vos actions menants &agrave; cette erreur &agrave; gael.induni A_T gmail.com";
	}
	$error .= $errorprint;
	$error .= $errors;
	$error .= "</div>\n";
	echo $error;
}
?>
