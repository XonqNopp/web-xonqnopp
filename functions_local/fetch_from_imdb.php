<?php
function fetch_IMDB($fullurl)
{
	$ripcurl = fetch_curl($fullurl);
	// Checking if result of search
	if( preg_match('/<b>popular titles<\/b>/i',$ripcurl) ) {
		// Hem...
		$newurl = $ripcurl;
		$newurl = preg_replace('/^(.|\n)*?<b>popular titles<\/b>.*?<a .*?href="(.*?)".*$/i','\1',$newurl);
		$ripcurl = fetch_IMDB($newurl);
	} elseif( preg_match('/approx matches/i',$ripcurl) ) {
		// To do... or not??
	} else {

		// Removing header
		$ripcurl = preg_replace('/^(.|\n)*?<body.*\n/i','',$ripcurl);
		// Removing extra content
		$ripcurl = preg_replace('/^(.|\n)*?<ul id="main_nav.*?>/i','',$ripcurl);
		$ripcurl = preg_replace('/<ul class="sub_nav">(.|\n)*?<\/ul>/i','',$ripcurl);
		$ripcurl = preg_replace('/^(.|\n)*?<\/ul>/i','',$ripcurl);
		$ripcurl = preg_replace('/^(.|\n)*?<div id="pagecontent.*?>/i','',$ripcurl);
		$ripcurl = preg_replace('/^(.|\n)*?<table.*?id="title-overview-widget-layout".*?>\n*/i','',$ripcurl);
		$ripcurl = preg_replace('/^(.|\n)*?<h1.*?>\n*/i','',$ripcurl);
		// the following is blah
		//$ripcurl = preg_replace('/ *<div id="sidebar">\n*(.|\n)*$/i','',$ripcurl);

		// Removing CSS
		$ripcurl = preg_replace('/<style.*?>(.|\n)*?<\/style>\n*/i','',$ripcurl);
		$ripcurl = preg_replace('/<link.*?>/i','',$ripcurl);
		$ripcurl = preg_replace('/<\/?strong>/i','',$ripcurl);
		// Removing pictures
		$ripcurl = preg_replace('/<img(.|\n)*?>\n*/i','',$ripcurl);
		// Removing iframes
		$ripcurl = preg_replace('/<iframe(.|\n)*?>(.|\n)*?<\/iframe>\n*/i','',$ripcurl);
		// Removing forms
		$ripcurl = preg_replace('/<form.*?>/i','',$ripcurl);
		$ripcurl = preg_replace('/<\/form>/i','',$ripcurl);
		$ripcurl = preg_replace('/<select.*?>(.|\n)*?<\/select>/i','',$ripcurl);
		$ripcurl = preg_replace('/<input .*?>/i','',$ripcurl);
		$ripcurl = preg_replace('/<button.*?>(.|\n)*?<\/button>/i','',$ripcurl);
		// Removing links
		$ripcurl = preg_replace('/<a .*?>/i','',$ripcurl);
		$ripcurl = preg_replace('/<\/a>/i','',$ripcurl);
		// Removing extra spaces
		$ripcurl = preg_replace('/&nbsp;/i',' ',$ripcurl);
		$ripcurl = preg_replace('/\n\s+/',"\n",$ripcurl);
		$ripcurl = preg_replace('/\n\n+/',"\n",$ripcurl);
		$ripcurl = preg_replace('/  +/',' ',$ripcurl);
		// Removing empty divs/spans
		$ripcurl = preg_replace('/<div.*?>\n*<\/div>/i','',$ripcurl);
		$ripcurl = preg_replace('/<div class="rating.*?>(.|\n)*?<\/div>/i','',$ripcurl);
		$ripcurl = preg_replace('/<span.*?>\n*<\/span>/i','',$ripcurl);
		// Removing javascript
		$ripcurl = preg_replace('/<script.*?>(.|\n)*?<\/script>\n*/i','',$ripcurl);
		$ripcurl = preg_replace('/<noscript.*?>(.|\n)*?<\/noscript>\n*/i','',$ripcurl);
		$ripcurl = preg_replace('/on((un)?load|change|(db)?click|error|focus|key(down|up|press)|mouse(down|up|move|out|over)|resize|select|submit)=".*?"/i','',$ripcurl);
		// Removing comments
		$ripcurl = preg_replace('/<!--(.|\n)*?-->\n*/','',$ripcurl);
	}

	return $ripcurl;
}

function parse_IMDB($ripcurl)
{
	$back = array();
	// Title
	$title = preg_replace('/^(.*)\n(.*\n)*$/','\1',$ripcurl);
	$back[ 'title' ] = $title;
	// Duration
	$ripcurl = preg_replace('/^(.|\n)*?<div class="infobar".*?>\n/i','',$ripcurl);
	$duration = preg_replace('/ min - .*\n(.*\n)*$/i','',$ripcurl);
	$back[ 'duration' ] = $duration;
	// Category
	$ripcurl = preg_replace('/^.*?min - /i','',$ripcurl);
	$category = 'movie';
	if( preg_match('/^animation/i',$ripcurl) ) {
		$category = 'animation';
	}
	$back[ 'category' ] = $category;
	// Director
	$ripcurl = preg_replace('/^(.|\n)*?<h[0-9] class="inline">\n?Directors?:\n?<\/h[0-9]>\n?/i','',$ripcurl);
	$director = preg_replace('/\n?<\/div>\n(.*\n)*$/i','',$ripcurl);
	$back[ 'director' ] = $director;
	// Stars
	$ripcurl = preg_replace('/^(.|\n)*?<h[0-9] class="inline">\n?Stars:\n?<\/h[0-9]>\n?/i','',$ripcurl);
	$stars = preg_replace('/\n?<\/div>\n(.*\n)*$/i','',$ripcurl);
	$stars = preg_replace('/ and /',', ',$stars);
	$back[ 'actors' ] = $stars;
	// Storyline
	$ripcurl = preg_replace('/^(.|\n)*?<h[0-9]>\n?Storyline\n?<\/h[0-9]>\n?<p>\n?/i','',$ripcurl);
	$ripcurl = preg_replace('/<\/p>(.*\n)*$/','',$ripcurl);// Not needed
	$summary = preg_replace('/\n(.*\n)*$/','',$ripcurl);
	$back[ 'summary' ] = $summary;
	return $back;
}
?>
