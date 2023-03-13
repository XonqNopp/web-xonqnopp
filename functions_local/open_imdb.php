<?php
function open_IMDB($title,$site,$type)
{
	if( $type = "film" ) {
		$s = 'tt';
	} elseif( $type = "TV" ) {
		$s = 'ep';
	} else {
		exit("Unrecognized type...");
	}
	$title2 = strtr($title,' ','+');
	$fullurl = "http://www.imdb.$site/find?s=$s&q=$title2";
	// Need JS to open new tab window.open(fullurl)
}
?>
