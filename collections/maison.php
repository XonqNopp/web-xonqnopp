<?php
/* TODO:
 */
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
// debug
//$page->initHTML();
//$page->LogLevelUp(6);
// CSS paths
$page->CSS_ppJump();
//$page->CSS_ppWing();
// init body
$body = "";

	// Shortcuts
	$rf = "Registre Foncier";
	$proprio = "propri&eacute;taire";
	$arch = "architecte";
	$entr = "entrepreneur";
	$arcent = "$arch/$entr";
	$pret = "pr&ecirc;t";
	$hyp = "hypoth&egrave;que";
	$hyps = "hypoth&egrave;ques";
	$interets = "int&eacute;r&ecirc;ts";
	$impot = "imp&ocirc;t";
	$impots = "imp&ocirc;ts";
	$kchf = "'000 CHF";
	$p2 = "2e pilier";
	$p3 = "3e pilier";
	$engeneral = "en g&eacute;n&eacute;ral";
	$generalement = "g&eacute;n&eacute;ralement";
	$meme = "m&ecirc;me";

function getTitle($title, $level=2) {
	$ascii = $title;
	$string = "";
	$string .= "<!-- H$level $title -->\n";
	$string .= "<h$level id=\"$ascii\">";
	$string .= "$title&nbsp;";
	$string .= "<a class=\"titleAnchor\" href=\"#$ascii\">#</a>";
	$string .= "</h$level>";
	$string .= "\n";
	return $string;
}

function getLink($url) {
	return "<a target=\"_blank\" href=\"$url\">$url</a>";
}

function lili($content) {
	return "<li>$content</li>\n";
}

function lilink($url) {
	return lili(getLink($url));
}


// GoHome
$gohome = new stdClass();
$gohome->rootpage = "..";
$body .= $page->GoHome($gohome);
// Set title and hot booty
$body .= $page->SetTitle("Je deviens propri&eacute;taire");// before HotBooty
$page->HotBooty();

$body .= "<p>Petit r&eacute;sum&eacute; <b>subjectif</b> de:\n";
$body .= "<i>Je deviens propri&eacute;taire</i>,\n";
$body .= "Ellen Weigand,\n";
$body .= "aux Editions Tout compte fait (2015).</p>\n";

if($page->UserIsAdmin()) {
	// Personal stuff
	$body .= "<div><ul>\n";
	$body .= "<li>Localisation: pas VD ni NE; FR ou BE</li>\n";
	$body .= "<li>Offres $hyp: SwissLife, Raiffeisen Belfaux+Fribourg, Credit Suisse Sonova, UBS, HypothekenZentrum VZ</li>\n";
	$body .= "</ul></div>\n";
}

$body .= "<div class=\"framed\">\n";
$body .= "<div style=\"font-weight: 700\">Liens utiles:</div>\n";
$body .= "<ul>\n";
$body .= lilink("http://fri.ch");
$body .= lilink("http://vermoegenszentrum.ch");
$body .= lilink("http://homegate.ch");
$body .= lili(getLink("http://toutcomptefait.ch") . " -> calcul -> logement");
//$body .= lilink("http://");
$body .= "</ul>\n";
$body .= "</div>\n";


	// Intro
	$body .= getTitle("Introduction");

	$body .= "<p>(Rien de notable, mais gard&eacute;e seulement pour la coh&eacute;rence de la num&eacute;rotation des chapitres.)</p>\n";
//
	// Financement
	$body .= getTitle("Financement");
	$body .= "<div>\n";

	$body .= "<p>Futur $proprio doit obtenir un $pret hypoth&eacute;caire en mettant sa maison en gage.\n";
	$body .= "Il doit apporter 20% de fonds propres, l'$hyp ne couvrant pas l'entier du bien.\n";
	$body .= "De plus, les charges du bien ne doivent pas d&eacute;passer le tiers du revenu brut du $proprio (oui, brut; certaines banquent chipotent, faire jouer la concurrence).\n";
	$body .= "L'$hyp ne peut pas &ecirc;tre plus que le 2/3 du prix du bien apres 15 ans.\n";
	$body .= "Il faut donc amortir une partie de l'hyp.\n";
	$body .= "L'amortissement d&eacute;bute $generalement 1 an apre&egrave;s avoir re&ccedil;u le cr&eacute;dit et se fait par paiements r&eacute;guliers..</p>\n";

	$body .= "<p>Pour v&eacute;rifier la capacit&eacute; financi&egrave;re du futur $proprio (pour comparer avec le tiers du revenu brut),\n";
	$body .= "les banques prennent en compte:</p>\n";

	$body .= "<ul>\n";
	$body .= lili("Les $interets de l'$hyp en se basant sur un taux de 5% du cr&eacute;dit par an");
	$body .= lili("L'amortissement pour ramener l'$hyp au 2/3 de la valeur du bien apr&egrave;s 15 ans");
	$body .= lili("L'entretien du bien, en comptant 1% de la valeur du bien par an");
	$body .= "</ul>\n";

	$body .= "<p>En plus de tout cela, il faut pr&eacute;voir environ 5% de frais d'acquisition en plus\n";
	$body .= "(frais de notaire, $RF, taxes, $impots...).</p>\n";

	$body .= "<p>Une fois le bien acquis, il faut aussi pr&eacute;voir environ 3% de la valeur de bien annuellement pour l'entretien et les r&eacute;parations.</p>\n";

	$body .= "<p>La banque se base sur l'estimation du bien la plus basse.\n";
	$body .= "Si le vendeur en demande plus, le $proprio devra mettre la diff&eacute;rence en fonds propres.</p>\n";

	$body .= "<p>Les fonds propres peuvent provenir de diff&eacute;rentes fa&ccedil;ons:</p>\n";
	$body .= "<ul>\n";
	$body .= lili("cash");
	$body .= lili("terrain");
	$body .= lili("2e piliers (max 10% de la valeur du bien)");
	$body .= lili("3e piliers");
	$body .= lili("des avances sur h&eacute;ritages");
	$body .= lili("des futurs travaux: n&eacute;gocier une baisse des fonds propres en promettant d'effectuer des travaux");
	$body .= "</ul>\n";

	$body .= "<p>On peut n&eacute;gocier une $hyp plus grande que 80%\n";
	$body .= "(tout en sachant que dans les 15 ans elle doit &ecirc;tre redescendue au max &agrave; 66%)\n";
	$body .= "si on a un gros revenu et qu'on peut se permettre un gros amortissement.</p>\n";

	$body .= "<p>Si on emprunte &agrave; une connaissance, &eacute;tablir un contrat &eacute;crit comprenant les modalit&eacute;s du remboursement (montant, dur&eacute;e, taux, amortissement).</p>\n";

	$body .= "<p>Il existe plusieurs autres options si les fonds propres ne sont pas suffisants, mais elles entra&icirc;nent toutes plus de frais.</p>\n";

	$body .= "</div>\n";
//
	// Recherche du financement
	$body .= getTitle("Recherche du financement");

		// 2e pilier
		$body .= getTitle($p2, 3);
		$body .= "<div>\n";

		$body .= "<ul>\n";
		$body .= lili("On peut retirer le $p2 jusqu'&agrave; 50 ans. Pass&eacute; ce cap, on peut sortir le maximum de l'&eacute;tat qu'il avait &agrave; 50 ans ou la moiti&eacute; actuelle.");
		$body .= lili("On peut utiliser son $p2 au maximum jusqu'&agrave; 3 ans avant l'&acirc;ge de la retraite <b>selon le r&egrave;glement de la caisse de pension</b>.");
		$body .= lili("Les pr&eacute;l&egrave;vements dans le $p2 sont sous les conditions de retirer au minimum 20$kchf, un retrait par tranche de 5 ans");
		$body .= lili("On n'a pas le droit de pr&eacute;lever dans le $p2 si l'on touche une rente AI.");
		$body .= lili("La caisse de pension fait $generalement le versement dans les 6 mois, mais cela peut aller jusqu'&agrave; 24 mois si elle a des probl&egrave;mes de liquidit&eacute;s.");
		$body .= lili("Il est beaucoup plus rentable de mettre le $p2 en gage (nantissement) plut&ocirc;t que d'en retirer une partie, mais ce n'est pas accept&eacute; partout.");
		$body .= lili("Le versement du $p2 est soumis aux $impots! Penser &agrave; en tenir compte dans le calcul des fonds propres. Note: l'$impot est restitu&eacute; au remboursement <b>seulement si demand&eacute;</b> dans les 3 ans suivant le remboursement.");
		$body .= lili("Demander &agrave; la caisse de pension un comparatif des prestations avant-apr&egrave;s le retrait. Attention: les rentes de retraites, d'invalide, de d&eacute;c&egrave;s peuvent fortement diminuer... Dans ce cas, il est utile de conclure un $p3 et/ou une assurance vie en plus. Mais cela augmente les charges...");
		$body .= lili("On peut rembourser jusqu'&agrave; 3 ans avant la retraite, chaque remboursement au minimum 20$kchf.");
		$body .= lili("Il est parfois possible de conclure une assurance compl&eacute;mentaire aupr&egrave;s de la caisse de pension pour &eacute;viter une r&eacute;duction de la rente de retraite.");
		$body .= "</ul>\n";

		$body .= "</div>\n";
	//
		// 3e pilier
		$body .= getTitle($p3, 3);
		$body .= "<p>&Agrave; peu pr&egrave;s idem. Nantiseement peut permettre d'augmenter l'$hyp au-del&agrave; des 80%.</p>\n";
	//
		// Emprunter
		$body .= getTitle("Emprunter", 3);
		$body .= "<div>\n";

		$body .= "<ul>\n";

		$body .= "<li><b>Banques:</b> elles app&acirc;tent: action, mois gratuits... Elles peuvent proposer des avantages si on met tous les comptes chez elle (ce qui est parfois obligatoire).\n";
		$body .= "Attention car cela peut entra&icrc;ner plus de frais que la banque actuelle. Les frais de dossier sont calcul&eacute;s selon un pourcentage de la valeur du bien.\n";
		$body .= "On peut faire jouer la concurrence pour essayer de les n&eacute;gocier.</li>\n";

		$body .= "<li><b>Assurances:</b> $hyp $generalement taux fixe ou taux variable. Maximum 65-80%. Les fonds propres de pr&eacute;voyance ne sont pas forc&eacute;ment accept&eacute;s.\n";
		$body .= "Certaines proposent une suspension des paiements en cas d'incapacit&eacute; de travail.</li>\n";

		$body .= "<li><b>Autres:</b>\n";
		$body .= "<ul>\n";
			$body .= lili("PostFinance (UBS)");
			$body .= lili("HypothekenZentrum (VZ): sp&eacute;cialis&eacute; dans les $hyps, frais administratifs avantageux");
			$body .= lili("homegate.ch");
			$body .= lili("HausEigentuemerVerband");
			$body .= lili("Certaines casses de pension (seulement 10% proposent des $hyps): payes les $interets financent notre retraite :-)");
		$body .= "</ul>\n";
		$body .= "</li>\n";

		$body .= "</ul>\n";

		$body .= "</div>\n";
	//
		// Types d'hyp
		$body .= getTitle("Types d'$hyp", 3);
		$body .= "<div>\n";

		$body .= "<p>$generalement une $hyp se fait en 2 parties, appel&eacute;es rang.\n";
		$body .= "Le 1er rang se compose de 65% de la valeur du bien et ne se rembourse (amorti) pas.\n";
		$body .= "Le 2e rang se compose jusqu'&agrave; 15% de la valeur du bien et s'amorti sous les 15 ans suivant l'achat.</p>\n";

		$body .= "<p>Une $hyp peut se choisir selon plusieurs variantes sur les taux d'$interets.</p>\n";

		$body .= "<ul>\n";

		$body .= "<li><b>Taux fixe:</b> s&eacute;curit&eacute; mais pas bon march&eacute;.\n";
		$body .= "L'$hyp est bloqu&eacute;e $meme si les taux du march&eacute; varient: attention &agrave; anticiper le budget avec le nouveau taux pour le renouvellement.\n";
		$body .= "Si on veut changer l'$hyp avant son &eacute;ch&eacute;ance, de fortes p&eacute;nalit&eacute;s sont &agrave; payer.</li>\n";

		$body .= "<li><b>Taux variable:</b>pas de dur&eacute;e, les 2 parties peuvent r&eacute;silier en tout temps avec 3-6 mois de pr&eacute;avis.\n";
		$body .= "Attention car le choix du taux n'est pas transparent, la banque le choisit elle-$meme...</li>\n";

		$body .= lili("Combi/mixte: un mix de taux fixe et de taux variable.");

		$body .= "<li><b>Libor:</b>London InterBank Offered Rate.\n";
		$body .= "Le taux est fix&eacute; chaque jour ouvrable &agrave; 11h (Londres).\n";
		$body .= "</li>\n";

		$body .= "</ul>\n";

		$body .= "</div>\n";


echo $body;

//// Finish
unset($page);
?>
