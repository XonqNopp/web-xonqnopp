<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->bobbyTable->init();

$languages = array("fr" => "French", "en" => "English", "it" => "Italian", "de" => "German", "??" => "other");


$body = $page->bodyBuilder->goHome("..");

$id = $_GET["id"];
$query = $page->bobbyTable->idManage("SELECT * FROM `dvds` WHERE `id` = ?", $id);
$query->store_result();
if($query->num_rows == 0) {
    $body .= "Sorry, no result...";
    $page_title = "No result";

} else {
    $query->bind_result($id, $title, $director, $actors, $languages, $subtitles, $duration, $serie, $number, $category, $summary, $burnt, $format, $borrowed);
    $query->fetch();

    $languages = explode(",", $languages);
    $subtitles = explode(",", $subtitles);

    $duration = (int)$duration;
    $number = (int)$number;

    if($category == "tvserie") {
        $body = $page->bodyBuilder->goHome("..", "serie_display.php?id=$id");
    }
    if($borrowed == "1") {
        $isbor = " class=\"away\"";
    } else {
        $isbor = "";
    }
    // Display depends if TV serie (Serie Title) or not (Title (serie))
    if($category == "tvserie") {
        $page_title = $serie;
        if($number > 0) {
            $page_title = "$page_title $number";
        }
        if($title != "") {
            $page_title = "$page_title ($title)";
        }
    } else {
        $page_title = $title;
        if($serie != "") {
            $page_title = "$page_title ($serie";
            if($number > 0) {
                $page_title = "$page_title $number";
            }
            $page_title = "$page_title)";
        }
    }

    $the_languages = array("fr" => "French", "en" => "English", "it" => "Italian", "de" => "German", "zz" => "other");
    $cats = array("movie" => "Movie", "animation" => "Animation", "tvserie" => "TV Serie", "doc" => "Documentary", "humor" => "Humorist", "music" => "Musical", "memory" => "Memory");
    $formats = array("dvd" => "DVD", "blu" => "Blu-ray", "avi" => "AVI");

    // Print
    $body .= "<h1$isbor>$page_title</h1>\n";
    $body .= "<div class=\"wide\">\n";

    // L head
    $body .= "<div class=\"lhead\">\n";
    if($page->loginHelper->userIsAdmin()) {
        // Edit
        $body .= $page->bodyBuilder->anchor("insert.php?id=$id", "edit") . "<br />\n";

        // Propose to borrow
        if($borrowed == 1) {
            $body .= $page->bodyBuilder->anchor("../missings/index.php?view=dvds$id#dvds$id", "who");

        } else {
            $body .= $page->bodyBuilder->anchor("../missings/insert.php?db=dvds&amp;id=$id", "borrow");
        }
    }

    $body .= "</div><!-- lhead -->\n";

    $body .= "<div class=\"chead\"></div>\n";

    // R head
    $body .= "<div class=\"rhead\">\n";
    $body .= $page->bodyBuilder->anchor("../missings/index.php?view=dvds", "Missing DVDs");
    // Search
    // Propose to add a new if authorized
    if($page->loginHelper->userIsAdmin()) {
        $body .= "<br />\n";
        // Add
        $body .= $page->bodyBuilder->anchor("insert.php", "New DVD");
    }
    $body .= "</div><!-- rhead -->\n";
    $body .= "</div><!-- wide -->\n";

    $body .= "<div>\n";
    $body .= "<div class=\"dvd_display_people\">\n";
    // Director
    $body .= "<div class=\"dvd_display_director\">";
    if($director != "") {
        $body .= "Director&nbsp;: $director";
    }
    $body .= "</div><!-- dvd_display_director -->\n";
    // Actors
    $body .= "<div class=\"dvd_display_actors\">";
    if($actors != "") {
        $body .= "Actors&nbsp;: $actors";
    }
    $body .= "</div><!-- dvd_display_actors -->\n";
    $body .= "</div><!-- dvd_display_people -->\n";
    // Languages
    $body .= "<div class=\"dvd_display_lang\">\n";
    $body .= "<div class=\"dvd_display_languages\">";
    if($languages != array("")) {
        $tobody = "";
        $hm = 0;
        foreach($languages as $l) {
            if($tobody != "") {
                $tobody .= ", ";
            }
            $tobody .= $the_languages[$l];
            $hm++;
        }
        $body .= "Language";
        if($hm > 1) {
            $body .= "s";
        }
        $body .= "&nbsp;: ";
        $body .= $tobody;
    }
    $body .= "</div><!-- dvd_display_languages -->\n";
    // Subtitles
    $body .= "<div class=\"dvd_display_subtitles\">";
    if($subtitles != array("")) {
        $tobody = "";
        $hm = 0;
        foreach($subtitles as $s) {
            if($tobody != "") {
                $tobody .= ", ";
            }
            $tobody .= $the_languages[$s];
            $hm++;
        }
        $body .= "Subtitle";
        if($hm > 1) {
            $body .= "s";
        }
        $body .= "&nbsp;: ";
        $body .= $tobody;
    }
    $body .= "</div><!-- dvd_display_subtitles -->\n";
    $body .= "</div><!-- dvd_display_lang -->\n";
    // Duration
    $body .= "<div class=\"dvd_display_duration\">";
    if($duration > 0) {
        if($duration / 60.0 < 1) {
            $body .= "$duration min";
        } else {
            $duration_h = floor($duration / 60.0);
            $duration_m = $duration % 60;
            if($duration_m == 0) {
                $duration_m = "00";
            } elseif($duration_m < 10) {
                $duration_m = "0$duration_m";
            }
            $body .= "$duration_h" . "h$duration_m";
        }
    }
    $body .= "</div><!-- dvd_display_duration -->\n";
    // Category
    $body .= "<div class=\"dvd_display_category\">";
    if($category != "movie") {
        $body .= $cats[$category];
    }
    $body .= "</div><!-- dvd_display_category -->\n";
    // Burnt
    $body .= "<div class=\"dvd_display_burnt\">";
    if($burnt == "1") {
        $body .= "Burnt!!";
    }
    $body .= "</div><!-- dvd_display_burnt -->\n";
    // Format
    $body .= "<div class=\"dvd_display_format\">" . $formats[$format] . "</div>\n";
    // Summary
    $body .= "<div class=\"dvd_display_summary\">$summary</div>\n";
    $body .= "</div>\n";
}
$query->close();

$page->cssHelper->dirUpWing();
$page->htmlHelper->setTitle($page_title);
$page->htmlHelper->hotBooty();

echo $body;
?>
