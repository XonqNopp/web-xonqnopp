<?php
/*** Created: Thu 2015-07-16 07:58:03 CEST
 * TODO:
 */

$dogs = array(
	"M" => "messe",
	"R" => "religieux",
	"T" => "Taiz&eacute;",
	"G" => "Gospel",
	"X" => "No&euml;l",
	"C" => "concert",
	"A" => "Afrique"
);

function CatExplode($string) {
	$back = array();
	for($i = 0; $i < strlen($string); $i++) {
		$back[] = substr($string, $i, 1);
	}
	return $back;
}

?>
