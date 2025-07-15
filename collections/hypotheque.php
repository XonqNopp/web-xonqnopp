<?php
require_once("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);

require_once("$funcpath/form_fields.php");
global $theNumberInput;


// debug
//$page->htmlHelper->init();
//$page->logger->levelUp(6);

$page->logopedist->setAvailLangs(array("french"));
$page->logopedist->changeSessionLang();

// CSS paths
$page->cssHelper->dirUpWing();


$body = $page->bodyBuilder->goHome("..");

// Set title and hot booty
$body .= $page->htmlHelper->setTitle("Calculateur d'hypotheque");  // before HotBooty
$page->htmlHelper->hotBooty("fr");


    // Data
    $revenu = 0;
    $cash = 0;
    $lpp = 0;

    if (isset($_GET["revenu"])) {
      $revenu = $_GET["revenu"];
      $cash = $_GET["cash"];
      $lpp = $_GET["lpp"];
    }

$body .= "<p><b>Note:</b> les revenus et charges sont annuels.</p>\n";

    // Inputs
    $body .= $page->bodyBuilder->titleAnchor("Inputs");
    $body .= "<div>\n";
    $body .= "</div>\n";

    $body .= "<div>\n";
    $body .= $page->formHelper->tag("get");

    $attr = new FieldAttributes();
    $attr->min = 0;

    $body .= $theNumberInput->get("revenu", $revenu, "Revenu CHF", $attr);
    $body .= $theNumberInput->get("cash", $cash, "Cash CHF", $attr);
    $body .= $theNumberInput->get("lpp", $lpp, "LPP CHF", $attr);

    // buttons
    $body .= $page->formHelper->subButt(False, NULL, "file", true, "Calculer");

    $body .= "</div>\n";

if ($revenu == 0 && $cash == 0 && $lpp == 0) {
    // No inputs yet, no need to display everything
    echo $body;
    unset($page);
    return;
}

    // Fonds propres
    $body .= $page->bodyBuilder->titleAnchor("Fonds propres");
    $body .= "<div>\n";
    $body .= "<p>Les fonds propres par d&eacute;faut se constituent du 20% du prix d'achat (dont max 10% de LPP) et 4% du prix d'achat sous forme de frais.\n";
    $body .= "Pour avoir 10% de fonds propres de LPP, on peut utiliser au maximum 10/14 du cash.</p>\n";

    $lppUtilisable = min($lpp, round(10.0 / 14.0 * $cash));
    $fondsPropres = $cash + $lppUtilisable;

    $body .= "<ul>\n";
    $body .= "<li>LPP utilisable: CHF $lppUtilisable</li>\n";
    $body .= "<li>Fonds propres: CHF $fondsPropres</li>\n";
    $body .= "</ul>\n";

    $prixSeulementFondsPropres = round($fondsPropres / 0.24);
    $body .= "<p>Si on ne consid&egrave;re que les fonds propres, on peut calculer le prix d'achat maximum pour que les fonds propres soient les 24%: CHF $prixSeulementFondsPropres</p>\n";

    $body .= "</div>\n";
//
    // Charges
    $body .= $page->bodyBuilder->titleAnchor("Charges");
    $body .= "<div>\n";
    $chargesMax = round(0.33 * $revenu);
    $body .= "<p>Les charges th&eacute;oriques ne doivent pas exc&eacute;der le tiers du revenu: CHF $chargesMax<p>\n";

    $body .= "<ul>\n";
    $body .= "<li>Charges d'int&eacute;r&ecirc;ts th&eacute;oriques: 5% de la dette</li>\n";
    $body .= "<li>Charges d'entretien th&eacute;oriques: 1% du prix</li>\n";
    $body .= "<li>Amortissement: le 2e rang doit &ecirc;tre rembours&eacute; sous 15 ans, donc 7% par ann&eacute;e</li>\n";
    $body .= "</ul>\n";

    $body .= "<p>Par d&eacute;faut, la dette est 80% du prix r&eacute;partie en 1er rang 66% et 2e rang 14%.\n";
    $body .= "Si on ne consid&egrave;re que le revenu, on peut calculer le prix d'achat maximum.</p>";

    $body .= $page->butler->tableOpen();
    $body .= $page->butler->row(array("charges", "= 5% dette + 1% prix + 7% 2e rang"));
    $body .= $page->butler->row(array("", "= 5% (80% prix) + 1% prix + 7% (14% prix)"));
    $body .= $page->butler->row(array("", "= 5.98% prix"));
    $body .= $page->butler->tableClose();

    $prixSeulementCharges = round($chargesMax / 0.0598);
    $body .= "<p>On trouve donc un prix maximum de CHF $prixSeulementCharges.</p>\n";

    $body .= "</div>\n";
//
    // Calculs
    $body .= $page->bodyBuilder->titleAnchor("Calculs");
    $body .= "<div>\n";
    $body .= "<p>Maintenant nous pouvons calculer en tenant compte de tous les inputs.\n";
    $body .= "Si l'on reprend les charges vues plus haut:</p>\n";

    $body .= $page->butler->tableOpen();
    $body .= $page->butler->row(array("charges", "= 5% dette + 1% prix + 7% 2e rang"));
    $body .= $page->butler->row(array("", "= 5% 1er rang + 5% 2e rang + 1% prix + 7% 2e rang"));
    $body .= $page->butler->row(array("", "= 5% 1er rang + 1% prix + 12% 2e rang"));
    $body .= $page->butler->tableClose();

    $body .= "<p>Nous devons maintenant calculer 4 cas: avec et sans 2e rang, utilisant l'int&eacute;gralit&eacute; de la LPP ou pas.\n";
    $body .= "En effet, autant il est permis de mettre plus que 20% de fonds propres, autant la part provenant de la LPP reste limit&eacute;e &agrave; 10%.\n";
    $body .= "Nous devons donc dans chaque cas calculer le prix en prenant 10% de fonds propres de la LPP (pour autant que le montant soit disponible),\n";
    $body .= "et en prenant l'int&eacute;gralit&eacute; de la LPP (pour autant que cela ne d&eacute;passe pas les 10% du prix).\n";
    $body .= "Bien s&ucirc;r, il n'y aura &agrave; chaque fois qu'un seul cas LPP valide.</p>\n";

        // Sans 2e rang
        $body .= $page->bodyBuilder->titleAnchor("Sans 2e rang", 3);
        $body .= "<div>\n";
        $body .= "<p>Avec la condition que le 2e rang est nul, le 1er rang devient 104% prix moins les fonds propres.\n";
        $body .= "Le calcul des charges devient donc:</p>\n";

        $body .= $page->butler->tableOpen();
        $body .= $page->butler->row(array("charges", "= 5% dette + 1% prix + 7% 2e rang"));
        $body .= $page->butler->row(array("", "= 5% dette + 1% prix"));
        $body .= $page->butler->row(array("", "= 5% (104% prix - fonds propres) + 1% prix"));
        $body .= $page->butler->row(array("", "= 5.2% prix - 5% fonds propres + 1% prix"));
        $body .= $page->butler->row(array("", "= 6.2% prix - 5% fonds propres"));
        $body .= $page->butler->tableClose();

        $body .= "<p>On suppose maintenant qu'on utilise 10% de fonds propres de la LPP.\n";
        $body .= "On peut donc ajuster le calcul:</p>\n";

        $body .= $page->butler->tableOpen();
        $body .= $page->butler->row(array("charges", "= 6.2% prix - 5% fonds propres"));
        $body .= $page->butler->row(array("", "= 6.2% prix - 5% (cash + 10% prix)"));
        $body .= $page->butler->row(array("", "= 6.2% prix - 5% cash - 0.5% prix"));
        $body .= $page->butler->row(array("", "= 5.7% prix - 5% cash"));
        $body .= $page->butler->tableClose();

        $body .= "<p>Ce qui nous donne: prix = (charges + 5% cash) / 5.7%</p>\n";

        $prixSansRang2 = round(($chargesMax + 0.05 * $cash) / 0.057);
        $lppUtiliseeSansRang2 = round(0.10 * $prixSansRang2);
        if ($lpp < $lppUtiliseeSansRang2) {
            $lppUtiliseeSansRang2 = $lpp;

            $body .= "<p>Mais la LPP &agrave; disposition ne permet pas de couvrir 10% du prix comme on vient de le calculer.\n";
            $body .= "On doit donc refaire le calcul avec la valeur connue.</p>\n";

            $body .= $page->butler->tableOpen();
            $body .= $page->butler->row(array("charges", "= 6.2% prix - 5% fonds propres"));
            $body .= $page->butler->row(array("", "= 6.2% prix - 5% (cash + LPP)"));
            $body .= $page->butler->tableClose();

            $body .= "<p>Ce qui nous donne: prix = (charges + 5% (cash + LPP)) / 6.2%</p>\n";

            $prixSansRang2 = round(($chargesMax + 0.05 * ($cash + $lpp)) / 0.062);
        }

        $body .= "<p><b>Mais</b> ce prix n'a de sens que si les fonds propres permettent d'avoir une dette inf&eacute;rieure aux 66% du prix.</p>\n";

        $body .= "<p>Prix max sans 2e rang: ";
        if ($prixSansRang2 - ($cash + $lppUtiliseeSansRang2) > 0.66 * $prixSansRang2) {
          // PAS POSSIBLE
          $prixSansRang2 = 0;

          $body .= "pas possible...";

        } else {
            $body .= "CHF $prixSansRang2";
        }
        $body .= "</p>\n";

        $body .= "</div>\n";
    //
        // Avec 2e rang
        $body .= $page->bodyBuilder->titleAnchor("Avec 2e rang", 3);
        $body .= "<div>\n";
        $body .= "<p>Si l'on a un 2e rang, cela veut dire que le 1er rang vaut 66% du prix.\n";
        $body .= "Le 2e rang se calcule comme 104% prix - fonds propres - 1er rang ce qui donne 38% prix - fonds propres.\n";

        $body .= "Le calcul des charges devient donc:</p>\n";

        $body .= $page->butler->tableOpen();
        $body .= $page->butler->row(array("charges", "= 5% dette + 1% prix + 7% 2e rang"));
        $body .= $page->butler->row(array("", "= 5% 1er rang + 5% 2e rang + 1% prix + 7% 2e rang"));
        $body .= $page->butler->row(array("", "= 5% 1er rang + 1% prix + 12% 2e rang"));
        $body .= $page->butler->row(array("", "= 5% (66% prix) + 1% prix + 12% (38% prix - fonds propres)"));
        $body .= $page->butler->row(array("", "= 3.3% prix + 1% prix + 4.56% prix - 12% fonds propres"));
        $body .= $page->butler->row(array("", "= 8.86% prix - 12% fonds propres"));
        $body .= $page->butler->tableClose();

        $body .= "<p>On suppose maintenant qu'on utilise 10% de fonds propres de la LPP.\n";
        $body .= "On peut donc ajuster le calcul:</p>\n";

        $body .= $page->butler->tableOpen();
        $body .= $page->butler->row(array("charges", "= 8.86% prix - 12% fonds propres"));
        $body .= $page->butler->row(array("", "= 8.86% prix - 12% cash - 12% (10% prix)"));
        $body .= $page->butler->row(array("", "= 8.86% prix - 12% cash - 1.2% prix"));
        $body .= $page->butler->row(array("", "= 7.66% prix - 12% cash"));
        $body .= $page->butler->tableClose();

        $body .= "<p>Ce qui nous donne: prix = (charges + 12% cash) / 7.66%</p>\n";

        $prixAvecRang2 = round(($chargesMax + 0.12 * $cash) / 0.0766);
        $lppUtiliseeAvecRang2 = round(0.10 * $prixAvecRang2);
        if ($lpp < $lppUtiliseeAvecRang2) {
            $lppUtiliseeAvecRang2 = $lpp;

            $body .= "<p>Mais la LPP &agrave; disposition ne permet pas de couvrir 10% du prix comme on vient de le calculer.\n";
            $body .= "On doit donc refaire le calcul avec la valeur connue.</p>\n";

            $body .= $page->butler->tableOpen();
            $body .= $page->butler->row(array("charges", "= 8.86% prix - 12% fonds propres"));
            $body .= $page->butler->row(array("", "= 8.86% prix - 12% (cash + LPP)"));
            $body .= $page->butler->tableClose();

            $body .= "<p>Ce qui nous donne: prix = (charges + 12% (cash + LPP)) / 8.86%</p>\n";

            $prixAvecRang2 = round(($chargesMax + 0.12 * ($cash + $lpp)) / 0.0886);
        }

        $body .= "<p>Ce qui nous donne: prix = CHF $prixAvecRang2</p>\n";

        $body .= "<p>Le 2e rang doit &ecirc;tre le plus bas possible, ce n'est pas rentable de diminuer le 1er rang au profit du 2e.</p>\n";

        $body .= "</div>\n";
    //
        // Conclusion
        $body .= $page->bodyBuilder->titleAnchor("Conclusion", 3);
        $body .= "<div class=\"framed\">\n";

        if ($prixSansRang2 > $prixAvecRang2) {
            if ($lppUtiliseeSansRang2 == $lpp) {
                $lppUtiliseeSansRang2 = " int&eacute;grale";
            }
            $body .= "<p>Prix max possible (sans 2e rang, LPP $lppUtiliseeSansRang2): CHF $prixSansRang2</p>\n";

        } else {
            if ($lppUtiliseeAvecRang2 == $lpp) {
                $lppUtiliseeAvecRang2 = " int&eacute;grale";
            }
            $body .= "<p>Prix max possible (AVEC 2e rang, LPP $lppUtiliseeAvecRang2): CHF $prixAvecRang2</p>\n";
        }
        $body .= "</div>\n";

    $body .= "</div>\n";



// Finish
echo $body;
?>
