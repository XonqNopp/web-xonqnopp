<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->logger->levelUp(6);
$page->bobbyTable->init();

$page->cssHelper->dirUpWing(2);
$userIsAdmin = $page->loginHelper->userIsAdmin();

    // def
    $portals = array(
        "jobup" => "https://www.jobup.ch/b2c/USR_jobmailer.asp"
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
        "monster" => "http://offres.monster.ch/offres-d-emploi/?eid=Master-ou-&eacute;quivalent&amp;q=physics,simulation&amp;sort=dt.rv.di",
        "CH" => "http://www.stelle.admin.ch/stellen/internet/index.html?lang=fr&amp;wlsearch_sd_m1641_110=",
        "Tesla jobs (all-acad)" => "http://www.all-acad.com/Jobs/Physics_Engineering/ch_Switzerland/All_Categories/Experienced/Industry",
        "Turing jobs (all-acad)" => "http://www.all-acad.com/Jobs/Computer_Science/ch_Switzerland/All_Categories/Experienced/Industry",
        "jobs for brains (EPFL)" => "http://www.jobsforbrains.ch/quick_search.php?sel=last_vac",
        "ETH get hired" => "http://eth-gethired.ch/search/sector/27/academic/0",
        "jobs.ch" => "http://www.jobs.ch/en/service/ungelesene.php",
        "ingjobs.ch" => "http://ingjobs.ch/fr/jobs/?"
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
        "job together" => "http://www.jobtogether.com/", //jt/?page_id=7249",
        "myscience.ch" => "http://www.myscience.ch/jobs/search?d=Physics-Materials+Science",
        "talendo" => "https://talendo.ch/de/jobs/search?"
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
        "work pool" => "http://www.workpool-jobs.ch/jobs/?w=physics&amp;s=datum+desc",
        "tcc" => "http://tcc-schweiz.ch/stellenportal/",
        "LinkedIn jobs" => "https://www.linkedin.com/job/home?trk=nav_responsive_sub_nav_jobs",
        "career jet (search)" => "http://www.careerjet.ch/wsuchen/stellenangebote?s=physics&amp;l=Schweiz&amp;sort=date",
        "indeed (search)" => "http://www.indeed.ch/Stellen?q=Physics&amp;l=Bern%2C+BE&amp;sort=date",
        "simply hired (search)" => "http://fr.simplyhired.ch/a/jobs/list/q-physics/sb-dd",
        "StackOverflow" => "http://careers.stackoverflow.com/jobs?location=bern&range=100&distanceUnits=Kilometers"
    );
    $moreportals = array(
        "success and career" => "http://www.success-and-career.ch/offres-emploi/recherche",
        "jobpilot.ch" => "http://www.fr.jobpilot.ch/search.aspx?where=fribourg&amp;rad=100",
        "ORP (chomage)" => "https://www.job-room.ch/pages/job/jobSearch.xhtml"
    );

$sum = $page->bobbyTable->queryManage("SELECT COUNT(*) AS `sum` FROM `companies`");// WHERE `ranking` <> 0");
$sumo = $sum->fetch_object();
$sum->close();
$sum = $sumo->sum;
//$page_title = "$sum interesting companies";
$page_title = "$sum companies";

if($userIsAdmin) {
    $comco = $page->bobbyTable->queryManage("SELECT COUNT(*) AS `sum` FROM `comco`");
    $comcoo = $comco->fetch_object();
    $comco->close();
    $comco = $comcoo->sum;
    $page_title .= " ($comco com)";
}

$body = $page->bodyBuilder->goHome("../..", "..");

$body .= $page->htmlHelper->setTitle($page_title);
$page->htmlHelper->hotBooty();

$body .= "<div class=\"wide\">\n";
$body .= "<div class=\"lhead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"chead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"rhead\">\n";
if($userIsAdmin) {
    $body .= $page->bodyBuilder->anchor("insert.php", "new") . "<br />\n";
    $body .= $page->bodyBuilder->anchor("interactions.php", "interactions");
}
$body .= "</div>\n";
$body .= "</div>\n";

$body .= "<div class=\"AllCompanies\">\n";


/**
 * Get the companies in the table.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function getCompanies() {
    global $page;
    global $userIsAdmin;

    $body = $page->waitress->tableOpen() . $page->waitress->rowOpen() . $page->waitress->cellOpen();

    $sorting = $userIsAdmin ? array("DESCranking", "name") : array("name");
    $all = $page->bobbyTable->selectAll("companies", $sorting);
    $nCompanies = $all->num_rows;

    if($nCompanies == 0) {
        $all->close();
        $body .= "No companies yet...";
        return $body . $page->waitress->cellClose() . $page->waitress->rowClose() . $page->waitress->tableClose();
    }

    $width = 2.0;
    $maxis = ceil($nCompanies / $width);

    $oldrank = 9;

    $iRow = 0;
    $colis = 1;

    $companiesPerRank = array();
    for($rank = 9; $rank >= 0; $rank--) {
        $get = $page->bobbyTable->queryManage("SELECT COUNT(*) AS `count` FROM `companies` WHERE `ranking` = $rank");
        $fetch = $get->fetch_object();
        $get->close();
        $companiesPerRank[$rank] = $fetch->count + 0;
    }

    while($company = $all->fetch_object()) {
        $iRow++;
        $comId = $company->id;
        $name = $company->name;

        if(!$userIsAdmin) {
            if($nCompanies > 1 && $iRow > $maxis) {
                $iRow = 0;
                $body .= $page->waitress->cellClose() . $page->waitress->cellOpen();
            }

            $body .= "<div id=\"c$comId\">";
            $body .= $page->bodyBuilder->anchor("display.php?id=$comId", $name);
            $body .= "</div>\n";
            continue;
        }

        $rank = $company->ranking;
        $nThis = $companiesPerRank[$rank];
        $maxis = ceil($nThis / $width);

        if($rank < $oldrank) {
            $body .= $page->waitress->cellClose();
            while($colis < $width) {
                $colis++;
                $body .= $page->waitress->cell("", array("class" => "cellrank$oldrank"));
            }
            $body .= $page->waitress->rowClose();

            $oldrank = $rank;
            $body .= $page->waitress->rowOpen();  //"cellrank$rank"; not working
            $colis = 1;

            $body .= $page->waitress->cellOpen(array("class" => "cellrank$rank"));
            $iRow = 0;

        } elseif($iRow >= $maxis && $nThis > 1) {
            $body .= $page->waitress->cellClose() . $page->waitress->cellOpen(array("class" => "cellrank$rank"));
            $colis++;
            $iRow = 0;
        }

        $sub = $page->bobbyTable->queryManage("SELECT COUNT(*) AS bus FROM `comco` WHERE `company` = $comId");
        $subtot = $sub->fetch_object();
        $sub->close();
        $subval = $subtot->bus;

        $body .= "<div id=\"c$comId\">\n";
        $body .= $page->bodyBuilder->anchor("insert.php?id=$comId", "edit");
        $body .= "&nbsp;\n";

        $body .= "<span class=\"co$rank\">\n";
        $body .= $page->bodyBuilder->anchor("display.php?id=$comId", $name);
        $body .= $subval > 0 ? "&nbsp;<span class=\"count\">($subval)</span>\n" : "";
        $body .= "</span>\n";  // co$rank
        $body .= "</div><!-- c id -->\n";
    }
    $all->close();

    return $body . $page->waitress->cellClose() . $page->waitress->rowClose() . $page->waitress->tableClose();
}


$body .= getCompanies();

$body .= "<h2>Portals for scientific jobs in Switzerland</h2>\n";

    // portals
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
        $body .= $page->bodyBuilder->liAnchor($url, $title);
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
    $body .= "</div><!-- portals -->\n";

$body .= "<div>&nbsp;</div>\n";

    // more portals
    $body .= "<div>\n";
    $body .= "More portals:\n";
    $body .= "<ul>\n";
    foreach($moreportals as $title => $url) {
        $body .= $page->bodyBuilder->liAnchor($url, $title);
    }
    $body .= "</ul>\n";
    $body .= "</div><!-- more portals -->\n";

$body .= "</div><!-- AllCompanies -->\n";

echo $body;
?>
