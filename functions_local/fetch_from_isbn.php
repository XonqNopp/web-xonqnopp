<?php
function fetch_ISBN(PhPage $page, $type, $isbn) {
	$page->ln_3(6, "fetch_ISBN($type, $isbn)", "LOCAL");
	$infos = new stdClass();
	$key = "UMXZQZA8";
	$fullurl = "http://isbndb.com/api/v2/xml/$key/books?q=$isbn";
	$xml = new SimpleXMLElement($page->RIP_curl($fullurl));
	$data = $xml->data[0];
	$infos->isbn      = $isbn;
	$infos->author    = "";
	$infos->title     = "";
	$infos->serie     = "";
	$infos->tome      = "";
	$infos->publisher = "";
	if($xml->result_count > 0) {
		$page->ln_3(5, "fetch_ISBN found result", "LOCAL");
		foreach($data->author_data as $a) {
			if($infos->author != "") {
				$infos->author .= ", ";
			}
			$infos->author .= $a->name;
		}
		$title = preg_replace("/ (french edition[^)]*)/i", "", $data->title_long);
		if($title == "") {
			$title = $data->title;
		}
		$tome = 0;
		$serie = "";
		if(preg_match("/,? ?tome /i", $title)) {
			$tome  = preg_replace("/^.*,?\s*tome ([0-9]+).*$/i",           '\1', $title);
			$serie = preg_replace("/^\s*(.*[^, ]),?\s*tome {$tome}.*$/i",  '\1', $title);
			$title = preg_replace("/^{$serie},?\s*tome {$tome}[ ,.:;]*/i", "",   $title);
		}
		$infos->title = $title;
		$infos->serie = $serie;
		$tome += 0;
		if($tome > 0) {
			$infos->tome = $tome;
		}
		$infos->publisher = $data->publisher_name;
	}
	return $infos;
}
?>
