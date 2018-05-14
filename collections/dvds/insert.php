<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->NotAllowed();

$page->initDB();

//require("${funcpath}_local/fetch_from_imdb.php");

	//// init values
	$id = 0;
	$title = "";
	$director = "";
	$actors = "";
	$languages = array("");
	$subtitles = array("");
	$duration = "";
	$serie = "";
	$number = "";
	$summary = "";
	$burnt = false;
	$format = "dvd";
	$category = "";
//
$is_it = array("yes" => 1, "no" => 0);
$the_languages = array("fr" => "French", "en" => "English", "it" => "Italian", "de" => "German", "zz" => "other");
$cats = array("movie" => "Movie", "animation" => "Animation", "tvserie" => "TV Serie", "doc" => "Documentary", "humor" => "Humorist", "music" => "Musical", "memory" => "Memory");
$formats = array("dvd" => "DVD","blu" => "Blu-ray","avi" => "AVI");

if(isset($_POST["title"]) && (!isset($_POST["imdb"]) || $_POST["imdb"] == "")) {
	if(isset($_POST["erase"])) {
		$id = $_POST["id"];
		$page->DB_IdManage("DELETE FROM `" . $page->ddb->DBname . "` . `dvds` WHERE `dvds` . `id` = ? LIMIT 1;", $id);
		$page->HeaderLocation();
	} else {
		// DB treatement
		if(isset($_POST["id"])) {
			$id = $_POST["id"];
		}
		$title = $page->field2SQL($_POST["title"]);
		$director = $page->field2SQL($_POST["director"]);
		$actors = $page->field2SQL($_POST["actors"]);
		if($_POST["languages"] != "") {
			$languages = implode(",",$_POST["languages"]);
		}
		if($_POST["subtitles"] != "") {
			$subtitles = implode(",",$_POST["subtitles"]);
		}
		$duration = $_POST["duration"];
		$serie = $page->field2SQL($_POST["serie"]);
		$number = $page->field2SQL($_POST["number"]);
		$summary = $page->paragraph2SQL($_POST["summary"]);
		$burnt = $is_it[$_POST["burnt"]];
		$format = $_POST["format"];
		$category = $_POST["category"];
		$query = "";
		if($id > 0) {
			$query = $page->DB_QueryPrepare("UPDATE `" . $page->ddb->DBname . "` . `dvds` SET `title` = ?, `director` = ?, `actors` = ?, `languages` = ?, `subtitles` = ?, `duration` = ?, `serie` = ?, `number` = ?, `summary` = ?, `burnt` = ?, `format` = ?, `category` = ? WHERE `dvds` . `id` = ? LIMIT 1;");
			$query->bind_param("sssssisisissi", $title, $director, $actors, $languages, $subtitles, $duration, $serie, $number, $summary, $burnt, $format, $category, $id);
		} else {
			$query = $page->DB_QueryPrepare("INSERT INTO `" . $page->ddb->DBname . "` . `dvds` (`id`, `title`, `director`, `actors`, `languages`, `subtitles`, `duration`, `serie`, `number`, `summary`, `burnt`, `format`, `category`) VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
			$query->bind_param("sssssisisiss", $title, $director, $actors, $languages, $subtitles, $duration, $serie, $number, $summary, $burnt, $format, $category);
		}
		$page->DB_ExecuteManage($query);
		$id_back = 0;
		if($id > 0) {
			$id_back = $id;
		} else {
			$id_back = $query->insert_id;
		}
		$page->HeaderLocation("index.php#dvd$id_back");// Check if serie
	}
	exit;
}

$page->CSS_ppJump(2);
$page->CSS_ppWing();
$page->js_Form();


$body = "";
$GHargs = new stdClass();
$GHargs->rootpage = "..";

// DISPLAY
$morebody = "";
$page_title = "Insert a new DVD";
$category = "movie";
$format = "dvd";
$languages = array();
$subtitles = array();
/*
if(isset($_GET["imdb"])) {
	$imdb = $_GET["imdb"];
	$imdburl = "http://www.imdb.com/title/tt$imdb";
	$imdbtext = fetch_IMDB($imdburl);
	$imdbinfos = parse_IMDB($imdbtext);
	//print_r($imdbinfos);
	foreach($imdbinfos as $key => $val) {
		$$key = $val;
	}
	// Check if already in DB and get id
	// Need IMDB.fr :-P
}
 */
if(isset($_GET["id"]) || isset($_POST["id"])) {
	if(isset($_GET["id"])) {
		$id = $_GET["id"];
	} else {
		$id = $_POST["id"];
		//$imdb = $_POST["imdb"];
	}
	// Fetch infos from DB
	$query = $page->DB_IdManage("SELECT * FROM `" . $page->ddb->DBname . "` . `dvds` WHERE `dvds` . `id` = ? LIMIT 1;", $id);
	$query->store_result();
	if($query->num_rows == 0) {
		$query->close();
		exit("Error bad id");
	}
	$query->bind_result($id, $title, $director, $actors, $languages, $subtitles, $duration, $serie, $number, $category, $summary, $burnt, $format, $borrowed);
	$query->fetch();
	$query->close();
	$title    = $page->SQL2field($title);
	$director = $page->SQL2field($director);
	$actors   = $page->SQL2field($actors);
	$languages = explode(",", $languages);
	$subtitles = explode(",", $subtitles);
	if($duration == 0) {
		$duration = "";
	}
	$serie   = $page->SQL2field($serie);
	$number  = $page->SQL2field($number);
	if($number == 0) {
		$number = "";
	}
	$summary = $page->SQL2paragraph($summary);
	/*
	if(isset($imdb)) {
		$imdburl = "http://www.imdb.com/title/tt$imdb";
		$imdbinfos = parse_IMDB(fetch_IMDB($imdburl));
		//print_r($imdbinfos);
		foreach($imdbinfos as $key => $val) {
			$$key = $val;
		}
	}
	 */
	// Some infos to display
	$GHargs->page = "display";
	$GHargs->id = $id;
	$page_title = "Update infos for DVD " . stripslashes($title);
	$args = new stdClass();
	$args->type = "hidden";
	$args->name = "id";
	$args->value = $id;
	$args->css = "dvd_new_id";
	$morebody .= $page->FormField($args);
}

$body .= $page->GoHome($GHargs);
$body .= $page->SetTitle($page_title);
$page->HotBooty();

$body .= "<div class=\"main\">\n";

$body .= $page->FormTag();
$body .= $morebody;

$args = new stdClass();

// Title
$args->type = "text";
$args->title = "Title";
$args->name = "title";
$args->value = $title;
$args->autofocus = true;
$args->css = "dvd_new_title";
$args->required = true;
$args->size = 60;
$body .= $page->FormField($args);
$args->autofocus = false;
$args->required = false;
// IMDB
/*
$args->type = "number";
$args->title = "IMDB number";
$args->name = "imbd";
$args->value = $imdb;
$args->css = "dvd_new_imdb";
$args->min = 0;
$body .= $page->FormField($args);
 */
// Director
$args->type = "text";
$args->title = "Director";
$args->name = "director";
$args->value = $director;
$args->css = "dvd_new_director";
$args->size = 50;
$body .= $page->FormField($args);
// Actors
$args->type = "text";
$args->title = "Actors";
$args->name = "actors";
$args->value = $actors;
$args->css = "dvd_new_actors";
$args->size = 50;
$body .= $page->FormField($args);
// Languages
$args->type = "checkbox";
$args->title = "Languages";
$args->name = "languages";
$args->value = $languages;
$args->css = "dvd_new_languages";
$args->list = $the_languages;
$args->size = 0;
$body .= $page->FormField($args);
// Subtitles
$args->title = "Subtitles";
$args->name = "subtitles";
$args->value = $subtitles;
$args->css = "dvd_new_subtitles";
$body .= $page->FormField($args);
// Duration
$args->type = "number";
$args->title = "Duration";
$args->name = "duration";
$args->value = $duration;
$args->css = "dvd_new_duration";
$args->div = false;
$body .= "<div class=\"dvd_new_duration\">\n";
$body .= $page->FormField($args);
$body .= " minutes\n";
$body .= "</div>\n";
$args->div = true;
// Serie
$args->type = "text";
$args->title = "Serie";
$args->name = "serie";
$args->value = $serie;
$args->css = "dvd_new_serie";
$args->size = 50;
$body .= $page->FormField($args);
// Number
$args->type = "number";
$args->title = "Number";
$args->name = "number";
$args->value = $number;
$args->css = "dvd_new_number";
$body .= $page->FormField($args);
$args->size = 0;
// Burnt
$burnes = array("no" => "No", "yes" => "Yes");
$burne = "no";
if($burnt) {$burne = "yes";}
$args->type = "select";
$args->title = "Burnt";
$args->name = "burnt";
$args->value = $burne;
$args->list = $burnes;
$args->css = "dvd_new_burnt";
$body .= $page->FormField($args);
// Format
$args->type = "select";
$args->title = "Format";
$args->name = "format";
$args->value = $format;
$args->list = $formats;
$args->css = "dvd_new_format";
$body .= $page->FormField($args);
// Category
$args->type = "select";
$args->title = "Category";
$args->name = "category";
$args->value = $category;
$args->list = $cats;
$args->css = "dvd_new_category";
$body .= $page->FormField($args);
// Summary
$args->type = "textarea";
$args->title = "Summary";
$args->name = "summary";
$args->value = $summary;
$args->css = "dvd_new_summary";
$args->rows = 15;
$args->cols = 70;
$body .= $page->FormField($args);
// Buttons
$args = new stdClass();
$args->css = "dvd_new_valbut";
$args->CloseTag = true;
$body .= $page->SubButt($id > 0, "'$title'", $args);

$body .= "</div>\n";

$page->show($body);
unset($page);
?>
