<?php
function fetch_barcode($ean)
{
	$fullurl = "http://www.ean-search.org/perl/ean-search.pl?q=$ean";
	$ripopt = array(
		CURLOPT_HEADER         => false,
		CURLOPT_NOBODY         => false,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_ENCODING       => "",
		CURLOPT_AUTOREFERER    => true,
		CURLOPT_FRESH_CONNECT  => true,
		CURLOPT_USERAGENT      => "Mozilla/5.0 (X11)"
	);
	$rip = curl_init($fullurl);
	curl_setopt_array($rip,$ripopt);
	$ripcurl = curl_exec($rip);
	curl_close($rip);

	$ripcurl = preg_replace('/^(.|\n)*?<body.*\n/','',$ripcurl);
	$ripcurl = preg_replace('/^(.|\n)*?' . "Product name for EAN $ean:" . '.*\n+/','',$ripcurl);
	//echo "$ripcurl\n\n";
	$ripcurl = preg_replace('/\n(.|\n)*$/','',$ripcurl);
	//echo "$ripcurl\n\n";
	$ripcurl = preg_replace('/<.*?>/','',$ripcurl);
	$ripcurl = preg_replace('/ *movie */i','',$ripcurl);
	//echo "$ripcurl\n\n";
	// ONLY TITLE LEFT, but within garbage
	// Get title and fetch infos from imdb.fr (imdb.com if burnt in english)
	return $ripcurl;
}
?>
