<?php
/*** Created: Wed 2014-08-06 13:55:44 CEST
 * TODO:
 *
 */
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->LogLevelUp(6);
$page->initDB();

$page->CSS_ppJump(2);
$page->CSS_ppWing(2);
$GI = $page->UserIsAdmin();

$sum = $page->DB_QueryManage("SELECT COUNT(*) AS `sum` FROM `companies`");// WHERE `ranking` <> 0");
$sumo = $sum->fetch_object();
$sum->close();
$sum = $sumo->sum;
//$page_title = "$sum interesting companies";
$page_title = "$sum companies";

if($GI) {
	$comco = $page->DB_QueryManage("SELECT COUNT(*) AS `sum` FROM `comco`");
	$comcoo = $comco->fetch_object();
	$comco->close();
	$comco = $comcoo->sum;
	$page_title .= " ($comco com)";
}

$body = "";
$args = new stdClass();
$args->page = "..";
$args->rootpage = "../..";
$body .= $page->GoHome($args);
$body .= $page->SetTitle($page_title);
$page->HotBooty();

$body .= "<div class=\"wide\">\n";
$body .= "<div class=\"lhead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"chead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"rhead\">\n";
if($GI) {
	$body .= "<a href=\"insert.php\" title=\"new\">new</a><br />\n";
	$body .= "<a href=\"interactions.php\" title=\"interactions\">interactions</a>\n";
}
$body .= "</div>\n";
$body .= "</div>\n";

$body .= "<div class=\"AllCompanies\">\n";
$body .= "<div class=\"csstab64_table\">\n";
$body .= "<div class=\"csstab64_row\">\n";
$body .= "<div class=\"csstab64_cell\">\n";
//// get N for all ranks
for($rank = 9; $rank >= 0; $rank--) {
	$get = $page->DB_QueryManage("SELECT COUNT(*) AS `count` FROM `companies` WHERE `ranking` = $rank");
	$fetch = $get->fetch_object();
	$get->close();
	${"N$rank"} = $fetch->count;
}
//
$sorting = array("name");
if($GI) {
	$sorting = array("DESCranking", "name");
}
$all = $page->DB_SelectAll("companies", $sorting);
$width = 2.0;
$N = $all->num_rows;
$i = 0;
$oldrank = 9;
$maxis = ceil($N / $width);
$colis = 1;
if($N == 0) {
	$body .= "No companies yet...";
} else {
	while($co = $all->fetch_object()) {
		$i++;
		$id = $co->id;
		$name = $co->name;
		if($GI) {
			$rank = $co->ranking;
			$Nthis = ${"N$rank"} + 0;
			$maxis = ceil($Nthis / $width);
			if($Nthis > 1 && $i >= $maxis && $rank == $oldrank) {
				$i = 0;
				$colis++;
				$body .= "</div>\n";
				$body .= "<div class=\"csstab64_cell";
				$body .= " cellrank$rank";
				$body .= "\">\n";
			} elseif($rank < $oldrank) {
				$i = 0;
				$body .= "</div>\n";
				while($colis < $width) {
					$colis++;
					$body .= "<div class=\"csstab64_cell";
					$body .= " cellrank$oldrank";
					$body .= "\">\n";
					$body .= "</div>\n";
				}
				$body .= "</div>\n";
				//// new row
				$oldrank = $rank;
				$body .= "<div class=\"csstab64_row";
				//$body .= " cellrank$rank";
				//// not working
				$body .= "\">\n";
				//// new column
				$colis = 1;
				$body .= "<div class=\"csstab64_cell";
				$body .= " cellrank$rank";
				$body .= "\">\n";
			}
		} else {
			if($N > 1 && $i > $maxis) {
				$i = 0;
				$body .= "</div>\n";
				$body .= "<div class=\"csstab64_cell\">\n";
			}
		}
		$body .= "<div id=\"c$id\">\n";
		if($GI) {
			$body .= "<a class=\"edit\" href=\"insert.php?id=$id\" title=\"edit\">edit</a>&nbsp;\n";
			//$body .= "$rank.&nbsp;";
			$body .= "<span class=\"co$rank\">\n";
		}
		$body .= "<a href=\"display.php?id=$id\" title=\"$name\">$name</a>\n";
		if($GI) {
			$sub = $page->DB_QueryManage("SELECT COUNT(*) AS bus FROM `comco` WHERE `company` = $id");
			$subtot = $sub->fetch_object();
			$sub->close();
			$subval = $subtot->bus;
			if($subval > 0) {
				$body .= "&nbsp;<span class=\"count\">($subval)</span>\n";
			}
			$body .= "</span>\n";
		}
		$body .= "</div>\n";
	}
}
$all->close();
$body .= "</div>\n";
$body .= "</div>\n";
$body .= "</div>\n";
//
//// Portals
$portals = array(
	"jobup"                  => "https://www.jobup.ch/b2c/USR_jobmailer.asp"
								//. "?"
								//. "cmd=showresults"
								//. "&amp;cantons=BE,FR,NE,VS,VD2,VD3,VD4,SO"
								//. "&amp;keywords=physicist/physicien/physiker/physics/physique/physik/engineering/ing%E9nierie/ingenierie/engineer/ing%E9nieur/ingenieur/simulation/modelisation/modelierung"
								//. "&amp;employment=PERMANENT,FREELANCE"
								//. "&amp;employmentmin=70"
								//. "&amp;employmentmax=100"
								//. "&amp;employment_level_min=70"
								//. "&amp;employment_level_max=100"
								//. "&amp;companytypes=0"
								//. "&amp;jobmailerid=730884#1/1139378",
								,
	"monster"                => "http://offres.monster.ch/offres-d-emploi/?eid=Master-ou-&eacute;quivalent&amp;q=physics,simulation&amp;sort=dt.rv.di",
	"CH"                     => "http://www.stelle.admin.ch/stellen/internet/index.html?lang=fr&amp;wlsearch_sd_m1641_110=",
	"Tesla jobs (all-acad)"  => "http://www.all-acad.com/Jobs/Physics_Engineering/ch_Switzerland/All_Categories/Experienced/Industry",
	"Turing jobs (all-acad)" => "http://www.all-acad.com/Jobs/Computer_Science/ch_Switzerland/All_Categories/Experienced/Industry",
	"jobs for brains (EPFL)" => "http://www.jobsforbrains.ch/quick_search.php?sel=last_vac",
	"ETH get hired"          => "http://eth-gethired.ch/search/sector/27/academic/0",
	"jobs.ch"                => "http://www.jobs.ch/en/service/ungelesene.php",
	"ingjobs.ch"             => "http://ingjobs.ch/fr/jobs/?"
								. "sort=date"
								. "&amp;category-ids[]=458"
								. "&amp;category-ids[]=459"
								. "&amp;category-ids[]=460"
								. "&amp;category-ids[]=461"
								. "&amp;category-ids[]=462"
								//. "&amp;region-ids[]=12"// AG SO
								. "&amp;region-ids[]=13"
								. "&amp;region-ids[]=8"
								. "&amp;region-ids[]=9"
								. "&amp;region-ids[]=10"
								,
	"job together"           => "http://www.jobtogether.com/", //jt/?page_id=7249",
	"myscience.ch"           => "http://www.myscience.ch/jobs/search?d=Physics-Materials+Science",
	"talendo"                => "https://talendo.ch/de/jobs/search?"
								. "q=" //physics"
								. "&amp;filters[work_type_ids][]=2"
								. "&amp;filters[work_type_ids][]=1"
								. "&amp;filters[entry_level_ids][]=6"
								. "&amp;filters[region_ids][]=4"
								. "&amp;filters[region_ids][]=3"
								. "&amp;filters[region_ids][]=8"
								. "&amp;filters[region_ids][]=1"
								. "&amp;filters[region_ids][]=5"
								. "&amp;filters[category_ids][]=27"// F&E
								//. "&amp;filters[category_ids][]=31"// informatik
								,
	"work pool"              => "http://www.workpool-jobs.ch/jobs/?w=physics&amp;s=datum+desc",
	"tcc"                    => "http://tcc-schweiz.ch/stellenportal/",
	"LinkedIn jobs"          => "https://www.linkedin.com/job/home?trk=nav_responsive_sub_nav_jobs",
	"career jet (search)"    => "http://www.careerjet.ch/wsuchen/stellenangebote?s=physics&amp;l=Schweiz&amp;sort=date",
	"indeed (search)"        => "http://www.indeed.ch/Stellen?q=Physics&amp;l=Bern%2C+BE&amp;sort=date",
	"simply hired (search)"  => "http://fr.simplyhired.ch/a/jobs/list/q-physics/sb-dd",
	"StackOverflow"          => "http://careers.stackoverflow.com/jobs?location=bern&range=100&distanceUnits=Kilometers"
);
$moreportals = array(
	"success and career"     => "http://www.success-and-career.ch/offres-emploi/recherche",
	"jobpilot.ch"            => "http://www.fr.jobpilot.ch/search.aspx?where=fribourg&amp;rad=100",
	"ORP (chomage)"          => "https://www.job-room.ch/pages/job/jobSearch.xhtml"
);
$body .= "<h2>Portals for scientific jobs in Switzerland</h2>\n";
$body .= "<div>\n";
	$body .= "<script>\n";
	$body .= "function OpenUp() {\n";
	foreach($portals as $title => $url) {
		$url = preg_replace('/&amp;/', "&", $url);
		$body .= "    window.open('$url');\n";
	}
	$body .= "    return true;\n";
	$body .= "}\n";
	$body .= "</script>\n";
	$body .= "<input type=\"button\" onclick=\"return OpenUp()\" value=\"open all\" />\n";
$body .= "<ul>\n";
foreach($portals as $title => $url) {
	$body .= "<li><a target=\"_blank\" href=\"$url\" title=\"$title\">$title</a></li>\n";
}
$body .= "</ul>\n";

$body .= "For CH, you can add the following numbers to the URL:\n";
$body .= "<ol>\n";
$body .= "<li value=\"37188\">armasuisse</li>\n";
$body .= "<li value=\"37191\">OFS</li>\n";
$body .= "<li value=\"37192\">MeteoSwiss</li>\n";
$body .= "<li value=\"37196\">OFIT</li>\n";
$body .= "<li value=\"37199\">OFEV</li>\n";
$body .= "<li value=\"37200\">OFAC</li>\n";
$body .= "<li value=\"37201\">OFEN</li>\n";
$body .= "<li value=\"37209\">SwissTopo</li>\n";
$body .= "<li value=\"37224\">SERFI</li>\n";
$body .= "<li value=\"37234\">OFROU</li>\n";
$body .= "<li value=\"37235\">OFCOM</li>\n";
$body .= "<li value=\"37236\">OFT</li>\n";
$body .= "<li value=\"37238\">OFSP</li>\n";
$body .= "<li value=\"37249\">Forces Aeriennes</li>\n";
$body .= "</ol>\n";
$body .= "Missing: SRC, METAS, IPI.\n";
$body .= "</div>\n";

$body .= "<div>&nbsp;</div>\n";

$body .= "<div>\n";
$body .= "More portals:\n";
$body .= "<ul>\n";
foreach($moreportals as $title => $url) {
	$body .= "<li><a target=\"_blank\" href=\"$url\" title=\"$title\">$title</a></li>\n";
}
$body .= "</ul>\n";
$body .= "</div>\n";

$body .= "</div>\n";

$page->show($body);
unset($page);
?>
