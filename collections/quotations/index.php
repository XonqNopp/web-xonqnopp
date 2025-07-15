<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->bobbyTable->init();
//$page->logger->levelUp(6);
$page->cssHelper->dirUpWing();

require_once("categories.php");
global $kCategories;

$alphabet = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
$userIsAdmin = $page->loginHelper->userIsAdmin();

$body = $page->bodyBuilder->goHome("../..", "..");


    /*** new favourite ***/
    if($userIsAdmin && isset($_GET["NewFav"])) {
        $NewFav = $_GET["NewFav"];
        $FavVal = 1;
        if($NewFav < 0) {
            $FavVal = 0;
            $NewFav = -$NewFav;
        }
        if($NewFav > 0) {
            $q = $page->bobbyTable->queryPrepare("UPDATE `quotations` SET `fav` = ? WHERE `id` = ? LIMIT 1;");
            $q->bind_param("ii", $FavVal, $NewFav);
            $page->bobbyTable->executeManage($q);// no header because link should be .php?NewFav=-0#c0
        }
    }


/**
 * Get the query result.
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function getQuery() {
    global $page;

    if(isset($_GET["random"])) {
        return $page->bobbyTable->randomEntry("quotations");
    }

    /*** query and order ***/
        /*** prepare sorting order ***/
        $sorting = isset($_POST["sorting"]) ? $_POST["sorting"] : "";

        $isDesc = isset($_POST["sc"]) ? $_POST["sc"] : "";
        $ascDesc = "ASC";
        if($isDesc == "dsc") {
            $ascDesc = "DESC";
        }

        // default is "alast"
        $order = "`authorlast` $ascDesc, `authorfirst` ASC, `place` ASC, `quote` ASC";
        if($sorting == "afirst") {
            $order = "`authorfirst` $ascDesc, `authorlast` ASC, `place` ASC, `quote` ASC";
        } elseif($sorting == "place") {
            $order = "`place` $ascDesc, `authorlast` ASC, `authorfirst` ASC, `quote` ASC";
        } elseif($sorting == "quote") {
            $order = "`quote` $ascDesc, `authorlast` ASC, `authorfirst` ASC, `place` ASC";
        } elseif($sorting == "date") {
            $order = "`id` $ascDesc";
        }
        $order = " ORDER BY $order";

    $query = "";

    if(isset($_POST["cats"])) {
        if(in_array("fav", $_POST["cats"])) {
            $query .= "`fav` = '1'";
        }

        global $kCategories;
        foreach($kCategories as $dog) {
            if(in_array($dog, $_POST["cats"])) {
                if($query != "") {
                    $query .= " AND ";
                }
                $query .= "`$dog` = '1'";
            }
        }
    }

    /*
    $keyword = "";
    if(isset($_POST["keyword"])) {
        $keyword = $page->dbText->input2sql($_POST["keyword"]);
    }
    if($keyword != "") {
        $query .= "(";
        $query .= "`quote` LIKE '%$keyword%'";
        $query .= " OR ";
        $query .= "`authorlast` LIKE '%$keyword%'";
        $query .= " OR ";
        $query .= "`authorfist LIKE '%$keyword%'";
        $query .= " OR ";
        $query .= "`place` LIKE '%$keyword%'";
        $query .= ")";
    }
     */
    $query = "SELECT * FROM `quotations`" . ($query != "" ? " WHERE $query" : "") . $order;
    return $page->bobbyTable->queryManage($query);
}


$result = getQuery();



$body .= $page->htmlHelper->setTitle("Citations");
$page->htmlHelper->hotBooty();

$body .= $page->formHelper->tag();
$body .= "<div id=\"navigation\">\n";

    $body .= "<div class=\"wide\">\n";
    $body .= "<div class=\"lhead\"></div>\n";

    $body .= "<div class=\"chead\">\n";
        // Alphabet links
        $body .= "<div class=\"headlinks\">\n";
        foreach($alphabet as $letter) {
            $body .= "<a href=\"#$letter\">" . strtoupper($letter) . "</a>\n";
            if($letter != "z") {
                $body .= "&nbsp;\n";
            }
        }
        $body .= "</div><!-- headlinks -->\n";
    $body .= "</div><!-- chead -->\n";

    $body .= "<div class=\"rhead\">\n";
    if($userIsAdmin) {
        $body .= $page->bodyBuilder->anchor("insert.php", "New quote", NULL, "blue");
        $body .= "<br>\n";
    }

    if(isset($_GET["random"]) || isset($_GET["search"]) || isset($_POST["search"])) {
        $body .= $page->bodyBuilder->anchor("index.php", "Toutes", NULL, "blue");
        $body .= "&nbsp;-&nbsp;\n";
    }
        // random
        $body .= $page->bodyBuilder->anchor("index.php?random", "hasard", NULL, "blue");

    $body .= "<br>\n";

        // order
        $sorting = "";
        if(isset($_POST["sorting"])) {
            $sorting = $_POST["sorting"];
        }

        $ascDesc = "";
        if(isset($_POST["sc"])) {
            $ascDesc = $_POST["sc"];
        }

        $sl = " selected=\"selected\"";
        $ch = " checked=\"checked\"";

        if($ascDesc == "dsc") {
            $dscd = $ch;
        } else {
            $ascd = $ch;
        }

        $body .= "<div id=\"sort\">\n";
        $body .= "<select name=\"sorting\" >\n";

        $body .= "<option value=\"alast\"";
        if($sorting == "alast" || $sorting == "") {
            $body .= $sl;
        }
        $body .= ">Nom</option>\n";

        $body .= "<option value=\"afirst\"" . ($sorting == "afirst" ? $sl : "") . ">Pr&eacute;nom</option>\n";
        $body .= "<option value=\"place\"" . ($sorting == "place" ? $sl : "") . ">Oeuvre</option>\n";
        $body .= "<option value=\"quote\"" . ($sorting == "quote" ? $sl : "") . ">Citation</option>\n";
        $body .= "<option value=\"date\"" . ($sorting == "date" ? $sl : "") . ">Date d'ajout</option>\n";
        $body .= "</select>\n";
        $body .= "<br>\n";

        $body .= "<input type=\"radio\" id=\"o_asc\" name=\"sc\" value=\"asc\"";
        if($ascDesc == "asc" || $ascDesc == "") {
            $body .= $ch;
        }
        $body .= "><label for=\"o_asc\">&nbsp;croissant</label><br>\n";
        $body .= "<input type=\"radio\" id=\"o_dsc\" name=\"sc\" value=\"dsc\"" . ($ascDesc == "dsc" ? $ch : "");
        $body .= "><label for=\"o_dsc\">&nbsp;d&eacute;croissant</label><br>\n";

        $body .= "<input type=\"submit\" value=\"Trier\">\n";

        $body .= "</div><!-- sort -->\n";

    $body .= "</div><!-- rhead -->\n";
    $body .= "</div><!-- wide -->\n";

$body .= "</div><!-- navigation -->\n";

    /*** search fields ***/
    if(isset($_GET["search"]) || isset($_POST["search"])) {
        $body .= "<div class=\"search\">\n";
        $checked = " checked=\"checked\"";

        $keyword = isset($_POST["keyword"]) ? $_POST["keyword"] : "";
        $body .= "<input type=\"text\" name=\"keyword\" value=\"$keyword\" size=\"13\"><br>\n";
        $body .= "<input id=\"s_fav\" type=\"checkbox\" name=\"cats[]\" value=\"fav\"";
        if(in_array("fav", $_POST["cats"])) {
            $body .= $checked;
        }
        $body .= "><label for=\"s_fav\">Favourite</label><br>\n";
        foreach($kCategories as $dog) {
            $body .= "<input id=\"s_$dog\" type=\"checkbox\" name=\"cats[]\" value=\"$dog\"";
            if(in_array($dog, $_POST["cats"])) {
                $body .= $checked;
            }
            $body .= "><label for=\"s_$dog\">$dog</label><br>\n";
        }
        $body .= "<input type=\"submit\" name=\"search\" value=\"search\">\n";
        $body .= "</div>\n";
    }

$body .= "</form>\n";


/**
 * Get the body.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function getBody($result) {
    global $page;
    global $userIsAdmin;

    if($result->num_rows == 0) {
        $result->close();
        return "<div id=\"warning\">Il n'y a aucune citation r&eacute;pondant &agrave; ces crit&egrave;res...</div>\n";
    }

    // main content
    $authorPrevious = "";
    $letterPrevious = "";
    $letterCurrent = $letterPrevious;
    $body = "<div id=\"lebloc\">\n";
    $body .= $page->butler->tableOpen(array("style" => "width: 100%; border: none;"));
    // edit fav quote
    while($row = $result->fetch_object()) {
        $quoteId = $row->id;
        $fav = $row->fav;
        $clf = $fav ? " favclass" : "";
        $authorLastName = $row->authorlast;
        $authorFirstName = $row->authorfirst;

        if($authorLastName === NULL) { $authorLastName = ""; }
        if($authorFirstName === NULL) { $authorFirstName = ""; }

        $authorlast = $authorLastName;
        $authorfirst = $authorFirstName;

        $inter = ($authorfirst !== NULL && substr($authorfirst, -1) == "'") ? "" : " ";

        $authorCurrent = "$authorfirst$inter$authorlast";
        if($authorCurrent == " ") {
            $authorCurrent = "Anonyme";
        }

        if($authorPrevious != $authorCurrent) {
            $colspan = $userIsAdmin ? 2 : 1;
            $body .= $page->butler->rowOpen();
            $body .= $page->butler->headerCellOpen(array("colspan" => $colspan));

            $authorPrevious = $authorCurrent;
            if($authorLastName !== NULL) {
                $letterCurrent = strtolower(substr($authorLastName, 0, 1));
            }

            $body .= "<!-- - - " . strtoupper($authorCurrent) . " - - -->\n";
            $body .= "<div class=\"author\"";

            if($letterCurrent != $letterPrevious) {
                $body .= " id=\"$letterCurrent\"";
                $letterPrevious = $letterCurrent;
            }

            $body .= ">\n";

            $author = $authorCurrent;
            $body .= "$author\n";

            $body .= "</div><!-- author -->\n";
            $body .= $page->butler->headerCellClose();
            $body .= $page->butler->rowClose();
        }

        $body .= $page->butler->rowOpen();

        if($userIsAdmin) {
            $body .= $page->butler->cellOpen(array("class" => "editbutton"));
            $body .= $page->bodyBuilder->anchor("insert.php?id=$quoteId", "edit");
            $body .= "<br>\n";
            $body .= $page->bodyBuilder->anchor(
                "index.php?NewFav=" . ($fav ? "-" : "") . "$quoteId#c$quoteId",
                ($fav ? "un" : "") . "favorize"
            );
            $body .= $page->butler->cellClose();
        }

            // quote
            $body .= $page->butler->cellOpen(array("class" => "quotab"));
            $body .= "<div class=\"quote$clf\" id=\"c$quoteId\">{$row->quote}</div>\n";
            if($row->place != "") {
                $body .= "<div class=\"place\">{$row->place}</div>\n";
            }
            $body .= $page->butler->cellClose();

        $body .= $page->butler->rowClose();
    }
    $result->close();

    $body .= $page->butler->tableClose();
    $body .= "</div><!-- lebloc -->\n";

    return $body;
}


echo $body . getBody($result);
?>
