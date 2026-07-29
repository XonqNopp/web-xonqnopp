<?php
function beforehead()
{
	$header = "<!doctype html>\n";
	$header .= "<html>\n";
	$header .= "<!-- Page written by Gael Induni -->\n";
	$header .= "<head>\n";
	$header .= "<meta charset=\"utf-8\" />\n";
	return $header;
}

function favicon($mk = false)
{
	return "<link rel=\"icon\" type=\"image/png\" href=\"/pictures/xn.png\" />\n";
}
function commoncss($root = false)
{
	if($root) {
		$c = "/";
	}
	return "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$c}common.css\" />\n";
}

function indexcss()
{
	return "<link rel=\"stylesheet\" type=\"text/css\" href=\"index.css\" />\n";
}

function jsenablesubmit()
{
	return "<script type=\"text/javascript\" src=\"jsenablesubmit.js\" ></script>\n";
}
function jsconfirmerase()
{
	return "<script type=\"text/javascript\" src=\"jsconfirmerase.js\" ></script>\n";
}

function jsonloadfocus($root = false)
{
	if($root) {
		$slash = "/";
	}
	return "<script type=\"text/javascript\" src=\"{$slash}jsonloadfocus.js\" ></script>\n";
}

function jsrandom($file)
{
	return "<script type=\"text/javascript\" src=\"js$file.js\" ></script>\n";
}

function headertitle($title)
{
	return "<title>$title</title>\n";
}

function endhead($onload = false, $name = "thefocus")
{
	if($onload) {
		$js = " onload=\"onloadfocus(\"$name\");\"";
	}
	$return = "</head>\n";
	$return .= "<body$js>\n";
	return $return;
}

function titleh1($title, $css = "")
{
	if($css != "") {
		$style = "class=\"$css\"";
	}
	$return = "<h1$style>$title</h1>\n";
	return $return;
}

function endhtml()
{
	return "</body>\n</html>\n";
}
?>
