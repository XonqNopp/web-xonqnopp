<?php
$doodle = "http://doodle.com/poll/nf494psk34dqnynd";

function RenderDoodle($legend="doodle") {
	global $doodle;
	return "<a target=\"_blank\" href=\"$doodle#table\" title=\"$legend\">$legend</a>";
}
?>
