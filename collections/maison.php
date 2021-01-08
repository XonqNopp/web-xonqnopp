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
	$aa = "&agrave;";
	$apres = "apr&egrave;s";
	$arcent = "$arch/$entr";
	$arch = "architecte";
	$batiment = "b&acirc;timent";
	$cote = "c&ocirc;t&eacute;";
	$coute = "co&ucirc;te";
	$couts = "co&ucirc;ts";
	$credit = "cr&eacute;dit";
	$deces = "d&eacute;c&egrave;s";
	$deductions = "d&eacute;ductions";
	$deductionsF = "$deductions fiscales";
	$defauts = "d&eacute;fauts";
	$detaille = "d&eacute;taill&eacute;";
	$different = "diff&eacute;rent";
	$echeance = "&eacute;ch&eacute;ance";
	$electricite = "&eacute;lectricit&eacute;";
	$eleve = "&eacute;lev&eacute;";
	$energie = "&eacute;nergie";
	$engeneral = "en g&eacute;n&eacute;ral";
	$entr = "entrepreneur";
	$equipements = "&eacute;quipements";
	$etage = "&eacute;tage";
	$etat = "&eacute;tat";
	$etre = "&ecirc;tre";
	$fenetre = "fen&ecirc;tre";
	$generalement = "g&eacute;n&eacute;ralement";
	$hyp = "hypoth&egrave;que";
	$hypotecaire = "hypot&eacute;caire";
	$ideal = "id&eacute;al";
	$impot = "imp&ocirc;t";
	$indemnites = "indemnit&eacute;s";
	$interets = "int&eacute;r&ecirc;ts";
	$kchf = "'000 CHF";
	$marche = "march&eacute;";
	$materiau = "mat&eacute;riau";
	$meme = "m&ecirc;me";
	$mobiliteReduite = "mobilit&eacute; r&eacute;duite";
	$negocier = "n&eacute;gocier";
	$negociations = "n&eacute;gociations";
	$p2 = "2e pilier";
	$p3 = "3e pilier";
	$piece = "pi&egrave;ce";
	$plutot = "plut&ocirc;t";
	$pret = "pr&ecirc;t";
	$prevoyance = "pr&eacute;voyance";
	$probleme = "probl&egrave;me";
	$proprio = "propri&eacute;taire";
	$renovations = "r&eacute;novations";
	$RF = "Registre Foncier";
	$pasoblvivrec = "Pas obligatoire, vivement recommand&eacute;";


function getTitle($title, $level=3, $idPrefix="") {
	$ascii = $title;
	$ascii = $idPrefix . preg_replace("/[ &;:'\?!\(\)\/]/", '', $title);

	$string = "";
	$string .= "<!-- H$level $title -->\n";
	$string .= "<h$level id=\"$ascii\">";
	$string .= "$title&nbsp;";
	$string .= "<a class=\"titleAnchor\" href=\"#$ascii\">#</a>";
	$string .= "</h$level>";
	$string .= "\n";
	return $string;
}


function getLink($url, $lineBreak=False) {
	$string = "<a target=\"_blank\" href=\"$url\">$url</a>";
	if ($lineBreak) {
		$string .= "<br />\n";
	}
	return $string;
}


function lili($content, $class="") {
	$li = "<li";
	if($class != "") {
		$li .= " class=\"$class\"";
	}
	$li .= ">$content</li>\n";
	return $li;
}


function liliPlus($content) {
	return lili($content, "plus");
}


function liliMinus($content) {
	return lili($content, "minus");
}


// GoHome
$gohome = new stdClass();
$gohome->rootpage = "..";
$body .= $page->GoHome($gohome);
// Set title and hot booty
$body .= $page->SetTitle("Je deviens $proprio");// before HotBooty
$page->HotBooty();

$body .= "<p>Petit r&eacute;sum&eacute; <b>subjectif</b> de:\n";
$body .= "<i>Je deviens $proprio</i>,\n";
$body .= "Ellen Weigand,\n";
$body .= "aux Editions Tout compte fait (2015).</p>\n";

if($page->UserIsAdmin()) {
	// Personal stuff
	$body .= "<div><ul>\n";
	$body .= "<li>Localisation: pas VD ni NE; FR ou BE</li>\n";  // TODO
	$body .= "<li>Offres $hyp: SwissLife, Raiffeisen Belfaux, Credit Suisse Sonova, HypothekenZentrum VZ, DL MoneyPark</li>\n";
	$body .= "</ul></div>\n";
}

	// Links
	$body .= "<div class=\"framed\">\n";

	$body .= "<div style=\"font-weight: 700\">Liens utiles:</div>\n";

	$body .= "<div class=\"csstab64_table\">\n";
	$body .= "<div class=\"csstab64_row\">\n";

	$body .= "<div class=\"csstab64_cell\">\n";
	$body .= getLink("http://fri.ch", True);
	$body .= getLink("http://vermoegenszentrum.ch", True);
	$body .= getLink("http://homegate.ch", True);
	$body .= getLink("http://toutcomptefait.ch") . " -> calcul -> logement<br />\n";
	$body .= getLink("http://hausinfo.ch", True);
	$body .= getLink("http://ubs.com", True);
	$body .= getLink("http://amiante-info.ch", True);
	$body .= getLink("http://ch-radon.ch", True);
	$body .= getLink("http://focore.ch", True);
	$body .= getLink("http://pac.ch", True);
	$body .= getLink("http://baubio.ch", True);
	$body .= getLink("http://eco-energie.ch", True);
	$body .= getLink("http://gaz-naturel.ch", True);
	$body .= getLink("http://cecb.ch", True);
	$body .= "</div>\n";  // cell

	$body .= "<div class=\"csstab64_cell\">\n";
	$body .= getLink("http://swissolar.ch", True);
	$body .= getLink("http://minergie.ch", True);
	$body .= getLink("http://leprogrammebatiments.ch", True);
	$body .= getLink("http://poursuite-faillite-offic.ch", True);
	$body .= getLink("http://cifi.ch", True);
	$body .= getLink("http://uspi.ch", True);
	$body .= getLink("http://sia.ch", True);
	$body .= getLink("http://uts.ch", True);
	$body .= getLink("http://lieudevie.ch", True);
	$body .= getLink("http://lignum.ch", True);
	$body .= getLink("http://cedotec.ch", True);
	$body .= getLink("http://maison-et-bois", True);
	$body .= getLink("http://lecourrierdubois.be", True);
	$body .= getLink("http://schweizerholzbau.ch", True);
	$body .= "</div>\n";  // cell

	$body .= "<div class=\"csstab64_cell\">\n";
	$body .= getLink("http://journal-suisse-du-bois.ch", True);
	$body .= getLink("http://salonbois.ch", True);
	$body .= getLink("http://domespace.ch", True);
	$body .= getLink("http://aber.ch", True);
	$body .= getLink("http://lamaisonnature.ch", True);
	$body .= getLink("http://aseg.ch", True);
	$body .= getLink("http://forumconstruire.ch", True);
	$body .= getLink("http://focore.ch", True);
	$body .= getLink("http://bauteilclick.com", True);
	$body .= getLink("http://vd.ch/boum", True);
	$body .= getLink("http://registre-foncier.ch", True);
	$body .= getLink("http://infomaison.ch", True);
	$body .= "</div>\n";  // cell

	$body .= "</div>\n";  // row
	$body .= "</div>\n";  // table

	$body .= "</div>\n";  // framed
//

//
	// Intro
	$body .= getTitle("Introduction", 2);

	$body .= "<p>(Rien de notable, mais gard&eacute;e seulement pour la coh&eacute;rence de la num&eacute;rotation des chapitres.)</p>\n";
//
	// Financement
	$body .= getTitle("Financement", 2);
	$body .= "<div>\n";

	$body .= "<p>Futur $proprio doit obtenir un $pret $hypothecaire en mettant sa maison en gage.\n";
	$body .= "Il doit apporter 20% de fonds propres, l'$hyp ne couvrant pas l'entier du bien.\n";
	$body .= "De plus, les charges du bien ne doivent pas d&eacute;passer le tiers du revenu brut du $proprio (oui, brut; certaines banquent chipotent, faire jouer la concurrence).\n";
	$body .= "L'$hyp ne peut pas $etre plus que le 2/3 du prix du bien $apres 15 ans.\n";
	$body .= "Il faut donc amortir une partie de l'$hyp.\n";
	$body .= "L'amortissement d&eacute;bute $generalement 1 an $apres avoir re&ccedil;u le $credit et se fait par paiements r&eacute;guliers.</p>\n";

	$body .= "<p>Pour v&eacute;rifier la capacit&eacute; financi&egrave;re du futur $proprio (pour comparer avec le tiers du revenu brut),\n";
	$body .= "les banques prennent en compte:</p>\n";

	$body .= "<ul>\n";
	$body .= lili("Les $interets de l'$hyp en se basant sur un taux de 5% du $credit par an");
	$body .= lili("L'amortissement pour ramener l'$hyp au 2/3 de la valeur du bien $apres 15 ans");
	$body .= lili("L'entretien du bien, en comptant 1% de la valeur du bien par an");
	$body .= "</ul>\n";

	$body .= "<p>En plus de tout cela, il faut pr&eacute;voir environ 5% de frais d'acquisition en plus\n";
	$body .= "(frais de notaire, $RF, taxes, {$impot}s...).</p>\n";

	$body .= "<p>Une fois le bien acquis, il faut aussi pr&eacute;voir environ 3% de la valeur de bien annuellement pour l'entretien et les r&eacute;parations.</p>\n";

	$body .= "<p>La banque se base sur l'estimation du bien la plus basse.\n";
	$body .= "Si le vendeur en demande plus, le $proprio devra mettre la diff&eacute;rence en fonds propres.</p>\n";

	$body .= "<p>Les fonds propres peuvent provenir de {$different}es fa&ccedil;ons:</p>\n";
	$body .= "<ul>\n";
	$body .= lili("cash");
	$body .= lili("terrain");
	$body .= lili("2e piliers (max 10% de la valeur du bien)");
	$body .= lili("3e piliers");
	$body .= lili("des avances sur h&eacute;ritages");
	$body .= lili("des futurs travaux: $negocier une baisse des fonds propres en promettant d'effectuer des travaux");
	$body .= "</ul>\n";

	$body .= "<p>On peut $negocier une $hyp plus grande que 80%\n";
	$body .= "(tout en sachant que dans les 15 ans elle doit $etre redescendue au max $aa 66%)\n";
	$body .= "si on a un gros revenu et qu'on peut se permettre un gros amortissement.</p>\n";

	$body .= "<p>Si on emprunte $aa une connaissance, &eacute;tablir un contrat &eacute;crit comprenant les modalit&eacute;s du remboursement (montant, dur&eacute;e, taux, amortissement).</p>\n";

	$body .= "<p>Il existe plusieurs autres options si les fonds propres ne sont pas suffisants, mais elles entra&icirc;nent toutes plus de frais.</p>\n";

	$body .= "</div>\n";
//
	// Recherche du financement
	$body .= getTitle("Recherche du financement", 2);

		// 2e pilier
		$body .= getTitle($p2);
		$body .= "<div>\n";

		$body .= "<ul>\n";
		$body .= lili("On peut retirer le $p2 jusqu'$aa 50 ans. Pass&eacute; ce cap, on peut sortir le maximum de l'$etat qu'il avait $aa 50 ans ou la moiti&eacute; actuelle.");
		$body .= lili("On peut utiliser son $p2 au maximum jusqu'$aa 3 ans avant l'&acirc;ge de la retraite <b>selon le r&egrave;glement de la caisse de pension</b>.");
		$body .= lili("Les pr&eacute;l&egrave;vements dans le $p2 sont sous les conditions de retirer au minimum 20$kchf, un retrait par tranche de 5 ans");
		$body .= lili("On n'a pas le droit de pr&eacute;lever dans le $p2 si l'on touche une rente AI.");
		$body .= lili("La caisse de pension fait $generalement le versement dans les 6 mois, mais cela peut aller jusqu'$aa 24 mois si elle a des {$probleme}s de liquidit&eacute;s.");
		$body .= lili("Il est beaucoup plus rentable de mettre le $p2 en gage (nantissement) $plutot que d'en retirer une partie, mais ce n'est pas accept&eacute; partout.");
		$body .= lili("Le versement du $p2 est soumis aux {$impot}s! Penser $aa en tenir compte dans le calcul des fonds propres. Note: l'$impot est restitu&eacute; au remboursement <b>seulement si demand&eacute;</b> dans les 3 ans suivant le remboursement.");
		$body .= lili("Demander $aa la caisse de pension un comparatif des prestations avant-$apres le retrait. Attention: les rentes de retraites, d'invalide, de $deces peuvent fortement diminuer... Dans ce cas, il est utile de conclure un $p3 et/ou une assurance vie en plus. Mais cela augmente les charges...");
		$body .= lili("On peut rembourser jusqu'$aa 3 ans avant la retraite, chaque remboursement au minimum 20$kchf.");
		$body .= lili("Il est parfois possible de conclure une assurance compl&eacute;mentaire aupr&egrave;s de la caisse de pension pour &eacute;viter une r&eacute;duction de la rente de retraite.");
		$body .= "</ul>\n";

		$body .= "</div>\n";
	//
		// 3e pilier
		$body .= getTitle($p3);
		$body .= "<p>A peu pr&egrave;s idem. Nantiseement peut permettre d'augmenter l'$hyp au-del$aa des 80%.</p>\n";
	//
		// Emprunter
		$body .= getTitle("Emprunter");
		$body .= "<div>\n";

		$body .= "<ul>\n";

		$body .= "<li><b>Banques:</b> elles app&acirc;tent: action, mois gratuits... Elles peuvent proposer des avantages si on met tous les comptes chez elle (ce qui est parfois obligatoire).\n";
		$body .= "Attention car cela peut entra&icirc;ner plus de frais que la banque actuelle. Les frais de dossier sont calcul&eacute;s selon un pourcentage de la valeur du bien.\n";
		$body .= "On peut faire jouer la concurrence pour essayer de les $negocier.</li>\n";

		$body .= "<li><b>Assurances:</b> $hyp $generalement taux fixe ou taux variable. Maximum 65-80%. Les fonds propres de $prevoyance ne sont pas forc&eacute;ment accept&eacute;s.\n";
		$body .= "Certaines proposent une suspension des paiements en cas d'incapacit&eacute; de travail.</li>\n";

		$body .= "<li><b>Autres:</b>\n";
		$body .= "<ul>\n";
			$body .= lili("PostFinance (UBS)");
			$body .= lili("HypothekenZentrum (VZ): sp&eacute;cialis&eacute; dans les {$hyp}s, frais administratifs avantageux");
			$body .= lili("homegate.ch");
			$body .= lili("HausEigentuemerVerband");
			$body .= lili("Certaines casses de pension (seulement 10% proposent des {$hyp}s): payes les $interets financent notre retraite :-)");
		$body .= "</ul>\n";
		$body .= "</li>\n";

		$body .= "</ul>\n";

		$body .= "</div>\n";
	//
		// Types d'hyp
		$body .= getTitle("Types d'$hyp");
		$body .= "<div>\n";

		$body .= "<p>G&eacute;n&eacute;ralement une $hyp se fait en 2 parties, appel&eacute;es rang.\n";
		$body .= "Le 1er rang se compose au maximum de 65% (selon le calcul du revenu) de la valeur du bien et ne se rembourse (amorti) pas.\n";
		$body .= "Le 2e rang se compose jusqu'$aa 15% de la valeur du bien et s'amorti sous les 15 ans suivant l'achat.</p>\n";

		$body .= "<p>Une $hyp peut se choisir selon plusieurs variantes sur les taux d'$interets.</p>\n";

		$body .= "<ul>\n";

		$body .= "<li><b>Taux fixe:</b> s&eacute;curit&eacute; mais pas bon $marche.\n";
		$body .= "L'$hyp est bloqu&eacute;e $meme si les taux du $marche varient: attention $aa anticiper le budget avec le nouveau taux pour le renouvellement.\n";
		$body .= "Si on veut changer l'$hyp avant son $echeance, de fortes p&eacute;nalit&eacute;s sont $aa payer.</li>\n";

		$body .= "<li><b>Taux variable:</b>pas de dur&eacute;e, les 2 parties peuvent r&eacute;silier en tout temps avec 3-6 mois de pr&eacute;avis.\n";
		$body .= "Attention car le choix du taux n'est pas transparent, la banque le choisit elle-$meme...</li>\n";

		$body .= lili("Combi/mixte: un mix de taux fixe et de taux variable.");

		$body .= "<li><b>Libor:</b>London InterBank Offered Rate.\n";
		$body .= "Le taux est fix&eacute; chaque jour ouvrable $aa 11h (Londres).\n";
		$body .= "Taux fixe entre 1 et 12 mois ($generalement 3 ou 6).\n";
		$body .= "On peut faire un contrat de 2 $aa 5 ans.\n";
		$body .= "Les banques ajoutent une marge de 0.65-1.30% selon le revenu et le $credit, marges figurant sur le contrat.\n";
		$body .= "Si le Libor devient n&eacute;gatif, les banques ne descendent pas en-dessous de (0 + marges).\n";
		$body .= "Cette formule s'adresse $aa un public averti qui suit l'&eacute;volution du $marche.\n";
		$body .= "Les sp&eacute;cialistes conseillent de mettre 2/3 de l'$hyp en taux fixe et le solde en Libor.\n";
		$body .= "Il est ensuite conseill&eacute; d'&eacute;pargner (5% - taux actuel) pour parer les effets d'une forte hausse.</li>\n";

		$body .= lili("<b>Libor Cap/Strike:</b> avec plafond mais plus cher. Pas rentable pour moins de 5 ans.");

		$body .= "<li><b>Variantes de Libor Cap:</b>\n";
		$body .= "<ul>\n";
		$body .= lili("BCV Benefit (6 mois)");
		$body .= lili("UBS Libor Cap Warrants");
		$body .= lili("Cr&eacute;dit Suisse Flex: max + min");
		$body .= "</ul>\n";
		$body .= "</li>\n";

		$body .= "<li><b>Taux liss&eacute; (p.ex. UBS Portfolio):</b>\n";
		$body .= "le $credit est divis&eacute; en plusieurs tranches, chaque tranche (p.ex. 3 mois) a son taux.\n";
		$body .= "Quand une tranche est &eacute;chue, elle est automatiquement renouvel&eacute;e avec le taux courant.\n";
		$body .= "L'$hyp est donc en d&eacute;calage avec le $marche.\n";
		$body .= "Il faut souvent payer des $indemnites d'entr&eacute;e et/ou de sortie.</li>\n";

		$body .= "<li>Rabais/bonus: plusieurs possibilit&eacute;s:\n";

		$body .= "<ul>\n";
		$body .= lili("start/USB FirstHome: rabais sur les taux pour une partie du $credit si c'est le premier logement.");
		$body .= lili("Eco/Minergie");
		$body .= lili("Famille");
		$body .= "</ul>\n";

		$body .= "</li>\n";

		$body .= "<li><b>Assurances:</b> $generalement pour 12 mois contre la perte de salaire induite par l'incapacit&eacute; de travail et le ch&ocirc;mage\n";
		$body .= "pour $interets mensuels (jusqu'$aa 2500.-), amortissement suspendu.\n";
		$body .= "Pas d'examen de sant&eacute;, mais $etre &acirc;g&eacute; au maximum de 54 ans.\n";
		$body .= "Possible assurance vie risque pur.</li>\n";

		$body .= "<li><b>Forward:</b> si $hyp longue arrive $aa terme, possible 'r&eacute;server' les taux 3/6/12 mois avant.\n";
		$body .= "Plus on fait avant, plus c'est cher.</li>\n";

		$body .= "</ul>\n";

		$body .= "</div>\n";
	//
		// Amortissement
		$body .= getTitle("Amortissement");
		$body .= "<div>\n";
		$body .= "<p>La dette doit $etre au maximum au 2/3 de la valeur $apres 15 ans.\n";
		$body .= "On peut amortir de 2 fa&ccedil;ons:</p>\n";

		$body .= "<ul>\n";

		$body .= "<li><b>Direct:</b> chaque payement diminue la dette (donc les charges).\n";
		$body .= "Id&eacute;al quand on est proche de la retraite ou avec une $hyp $aa taux &eacute;lev&eacute;.\n";
		$body .= "Mais les $interets diminuent, donc les {$impot}s augmentent.</li>\n";

		$body .= "<li><b>Indirect:</b> la dette est rembours&eacute;e en fin de contrat.\n";
		$body .= "Les payements sont une forme de police d'amortissement (3a).\n";
		$body .= "Cela a plusieurs avantages financiers:\n";

		$body .= "<ul>\n";
		$body .= lili("$deductionsF des $interets sur le revenu");
		$body .= lili("$deductionsF de la dette sur la fortune");
		$body .= lili("$deductionsF des primes d'amortissements sur le revenu");
		$body .= lili("charges initiales plus basses");
		$body .= lili("couvert en cas de $deces");
		$body .= lili("exon&eacute;ration des primes en cas d'incapacit&eacute; de travail");
		$body .= "</ul>\n";

		$body .= "Un 3a bancaire est plus avantageux fiscalement, mais un 3a assurance vie est plus s&eacute;curitaire (surtout du point de vue de la banque).\n";
		$body .= "</li>\n";

		$body .= "</ul>\n";

		$body .= "</div>\n";
	//
		// Strategie hyp
		$body .= getTitle("Strat&eacute;gie $hypotecaire");
		$body .= "<p>Il faut bien regarder les taux et les variantes, mais parfois les avantages fiscaux sont plus int&eacute;ressants que les taux.</p>\n";
	//
		// Negocier hyp
		$body .= getTitle("N&eacute;gocier l'$hyp");

			// Documents
			$body .= getTitle("Documents $aa r&eacute;unir", 4);
			$body .= "<div>\n";

			$body .= "<p>Informations personnelles:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("formulaire de demande de $credit $hypotecaire");
			$body .= lili("derni&egrave;re d&eacute;claration fiscale");
			$body .= lili("copie des contrats de leasing et/ou de pr&ecirc;ts personnels");
			$body .= lili("convention de divorce");
			$body .= lili("certificats de salaire et fiches de paye");
			$body .= lili("extrait du registre des poursuites sur 3 ans (datant de 3 mois au maximum)");
			$body .= lili("{$piece}s d'identi&eacute;, livret de famille");
			$body .= lili("certificat de $prevoyance ($p2)");
			$body .= lili("liste de tous les fonds propres");
			$body .= lili("d&eacute;claration stipulant que vous habiterez vous-m&ecirc;me le logement (si utilisation 2e et/ou $p3)");
			$body .= lili("extrait du $RF pour d'autres biens vous appartenant");
			$body .= lili("justificatif des avoirs du $p3");
			$body .= "</ul>\n";

			$body .= "<p>Informations sur le bien existant:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("extrait du $RF ou copie du projet de contrat d'acquisition");
			$body .= lili("plan de situation ou plan du cadastre");
			$body .= lili("polices d'assurances de l'immeuble");
			$body .= lili("plans {$detaille}s de construction 1:100 ou 1:50 (si $aa disposition)");
			$body .= lili("plaquette de vente, photos");
			$body .= lili("$etat locatif");
			$body .= lili("descriptif de la construction: ann&eacute;e, volume SIA ou assurance incendie");
			$body .= lili("description des $renovations/transformations");
			$body .= lili("devis et contrats d'entreprises g&eacute;n&eacute;rales");
			$body .= lili("contrat de vente authentique (maison, terrain)");
			$body .= lili("photos actuelles du bien");
			$body .= lili("permis de construre (si disponible)");
			$body .= "</ul>\n";

			$body .= "<p>Informations sur objet sur plan:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("catalogue du promoteur");
			$body .= lili("plans {$detaille}s de l'objet 1:100 ou 1:50");
			$body .= lili("descriptif de la construction");
			$body .= lili("projet de contrat d'entreprise g&eacute;n&eacute;rale");
			$body .= "</ul>\n";

			$body .= "<p>Informations sur la PPE:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("surfaces habitables, terrasse et/ou jardin");
			$body .= lili("situation du fonds de $renovations");
			$body .= lili("expertise (si disponible)");
			$body .= lili("acte constitutif de la PPE");
			$body .= lili("r&egrave;glement d'utilisation et d'administration de la PPE");
			$body .= lili("bilan et comptes d'exploitation de la PPE");
			$body .= "</ul>\n";

			$body .= "<p>Informations sur la nouvelle construction:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("acte de vente du terrain");
			$body .= lili("plans {$detaille}s du projet de construction 1:100 ou 1:50");
			$body .= lili("descriptif $detaille de la construction");
			$body .= lili("plan financier $detaille (si possible bas&eacute; sur le volume SIA)");
			$body .= lili("liste des artisans participant au chantier (si disponible)");
			$body .= lili("permis de construire");
			$body .= "</ul>\n";

			$body .= "<p>Informations pour transformations/$renovations:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("plans {$detaille}s du projet 1:100 ou 1:50");
			$body .= lili("liste des artisans participant au chantier (si disponible)");
			$body .= lili("permis de construire (si n&eacute;cessaire pour le projet)");
			$body .= lili("devis $detaille, si possible bas&eacute; sur les offres");
			$body .= "</ul>\n";

			$body .= "</div>\n";
		//
			// Tour horizon
			$body .= getTitle("Tour d'horizon", 4);
			$body .= "<div>\n";

			$body .= "<p>Avant de commencer, il faut pr&eacute;parer un dossier complet et bien pr&eacute;sent&eacute;.\n";
			$body .= "Id&eacute;alement un classeur avec des s&eacute;parations &eacute;tiquet&eacute;es.</p>\n";

			$body .= "<p>Demander des offres (voir d'abord les sites web) $aa:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("une grande banque: UBS, Credit Suisse...");
			$body .= lili("une banque de proximit&eacute;: Raiffeisen...");
			$body .= lili("la banque cantonale du canton et d'un canton voisin");
			$body .= lili("une compagnie d'assurance: Swisslife...");
			$body .= "</ul>\n";

			$body .= "<p>Demander diff&eacute;rentes variantes, donner un d&eacute;lai pour la r&eacute;ception (car les taux varient) et indiquer contact tel+email.</p>\n";

			$body .= "</div>\n";
		//
			// Concurrence
			$body .= getTitle("Faire jouer la concurrence", 4);
			$body .= "<p>Etablir une liste comparative {$detaille}e (anonyme ou pas) de toutes les offres et l'envoyer $aa tous en demandant mieux.</p>\n";
		//
			// Entretien
			$body .= getTitle("Entretien personnel", 4);
			$body .= "<div>\n";
			$body .= "<p>Avec les 2e offres, en choisir 3-5 et prendre RDV pour un entretien personnel.</p>\n";
			$body .= "<ul>\n";
			$body .= lili("Pr&eacute;parer une liste de questions");
			$body .= lili("Mentionner la possibilit&eacute; de migrer les comptes courant, &eacute;pargnes, 3a");
			$body .= lili("Demander une confirmation &eacute;crite de la derni&egrave;re proposition avec toutes les conditions");
			$body .= "</ul>\n";
			$body .= "</div>\n";
		//
			// Courtier
			$body .= getTitle("Courtier en $hyp", 4);
			$body .= "<p>Pour &eacute;viter ces &eacute;tapes, on peut faire appel $aa un courtier.\n";
			$body .= "Il est familier des produits et du $marche et va chercher les meilleures offres.\n";
			$body .= "Il accompagne aussi $aa la banque pour les n&eacute;gociations finales (et obtient de meilleures conditions car aux yeux de la banque il repr&eacute;sente un certain volume d'affaires).\n";
			$body .= "Il est normalament gratuit sauf en cas d'annulation, mais $aa v&eacute;rifier avant de s'engager.</p>\n";
		//
			// Vocabulaire
			$body .= getTitle("Vocabulaire", 4);
			$body .= "<p>Quand la banque accorde le $pret, le notaire cr&eacute;e une <b>c&eacute;dule $hypotecaire</b> exig&eacute;e comme garantie par la banque.\n";
			$body .= "C'est le titre de propri&eacute;t&eacute; du bien qui est &eacute;mis par le $RF et remis au notaire qui le transmet $aa la banque en &eacute;change du $pret.\n";
			$body .= "Quand la c&eacute;dule est lib&eacute;r&eacute;e par la banque, elle peut $etre r&eacute;utilis&eacute;e (travaux, autre bien).</p>\n";
	//
		// Renouveler
		$body .= getTitle("Renouveler l'$hyp");
		$body .= "<div>\n";
		$body .= "<p>Quand arrive l'$echeance et qu'il faut renouveler l'$hyp, il s'agit de:</p>\n";
		$body .= "<ul>\n";
		$body .= lili("Etudier l'amortissement");
		$body .= "<li>Demander une nouvelle estimation du bien: certains travaux donnent une plus-value qui permettrait d'obtenir de meilleurs taux.\n";
		$body .= "Attention, selon l'&eacute;volution du $marche, le bien peut aussi perdre de la valeur; ne pas informer la banque si l'estimation est d&eacute;favorable</li>";
		$body .= lili("Faire jouer la concurrence: mentionner le meilleur taux du voisin pour essayer d'avoir un geste");
		$body .= lili("Choisir un long taux fixe seulement si on est certain de ne pas d&eacute;m&eacute;nager ni amortir");
		$body .= "<li>R&eacute;fl&eacute;chir au renouvellement assez t&ocirc;t.\n";
		$body .= "Si la situation est diff&eacute;rente (par ex. baisse de revenu), le renouvellement peut $etre refus&eacute;.\n";
		$body .= "Selon la situation, il est pr&eacute;f&eacute;rable de demander conseil $aa un expert au pr&eacute;alable.</li>";
		$body .= "</ul>\n";
		$body .= "</div>\n";
	//
		// Credit construction
		$body .= getTitle("Cr&eacute;dit de construction");

		$body .= "<p>Un $credit de construction ne s'obtient qu'$aupres d'une banque, les assurances et les caisses de pension ne l'accordent pas.\n";
		$body .= "Il s'agit d'une sorte de compte courant.\n";
		$body .= "On y verse les fonds propres et le compte peut aller en n&eacute;gatif jusqu'$aa la limite de l'$hyp.\n";
		$body .= "Le taux est variable entre 0.50 et 2.75%.\n";
		$body .= "Les $interets sont factur&eacute;s chaque 3 mois en fonction de l'$etat du compte, $generalement directement d&eacute;bit&eacute;s du $credit.\n";
		$body .= "Une comission trimestrielle de 0.25% est pr&eacute;lev&eacute;e pour le paiement des facture (au taux moyen du trimestre, au taux max... Ca d&eacute;pend de la banque).</p>\n";

		$body .= "<p>Quand la construction est finie, on transforme le $credit en $hyp: c'est la consolidation.\n";
		$body .= "Il est possible de consolider dans une autre banque, mais cela demande beaucoup de temps et engendre beaucoup de frais.\n";
		$body .= "De plus, une banque qui accorde un $credit de construction accepte l'$hyp qui suivra.\n";
		$body .= "Il est conseill&eacute; de consolider au fur et $aa mesure (les taux peuvent augmenter pendant le chantier).\n";
		$body .= "Si l'on rencontre des impr&eacute;vus pendant le chantier qui engendrent un d&eacute;passement du montant, contacter la banque avant de signer le contrat pour les travaux impr&eacute;vus!\n";
		$body .= "Quand le compte du $credit est ouvert, attendre que le chantier commence avant de commencer $aa verser des fonds propres, les $interets sont r&eacute;mun&eacute;r&eacute;s au max 0.125%.</p>\n";
//
	// Acheter quoi?
	$body .= getTitle("Acheter quoi?", 2);

		// Logement ideal
		$body .= getTitle("Logement $ideal");
		$body .= "<div>\n";

		$body .= "<p>Commencer par &eacute;tablir une liste des besoins et des envies.\n";
		$body .= "Noter ce que l'on aime et ce que l'on n'aime pas dans le logement actuel.\n";
		$body .= "Etre attentif $aa la vie quotidienne: lever, coucher, repas, travail, loisir, soir, week-end...</p>\n";

		$body .= "<p><b>Lieu:</b> choix influenc&eacute; par la valeur du terrain et par la vie quotidienne</p>\n";
		$body .= "<ul>\n";
		$body .= lili("ville, campagne, banlieue, montagne?");
		$body .= lili("voiture, transports publics?");
		$body .= lili("temps de trajet maximum pour aller au travail?");
		$body .= lili("infrastructures: cr&egrave;che, &eacute;cole, sport, culture, commerces, m&eacute;decin, poste, banque?");
		$body .= lili("surface du terrain d&eacute;sir&eacute;e: jardin, jeux, garage?");
		$body .= lili("voisinage: &acirc;ge, niveau social, avec/sans enfants?");
		$body .= lili("environnement: Soleil, climat, nature, routes, agricole, r&eacute;sidentiel?");
		$body .= lili("pollution sonore");
		$body .= lili("{$impot}s");
		$body .= "</ul>\n";

		$body .= "<p><b>Espace:</b> (voir aussi " . getLink("http://hausinfo.ch") . " et " . getLink("http://ubs.com") . "</p>\n";
		$body .= "<ul>\n";
		$body .= lili("combien d'habitants $aa court et long terme? Des animaux?");
		$body .= lili("travail $aa domicile dans une $piece s&eacute;par&eacute;e (th&eacute;rapeute)?");
		$body .= lili("hobby n&eacute;cessitant un espace sp&eacute;cial int-/ext&eacute;rieur (bricolage, musique...)?");
		$body .= lili("divorc&eacute; accueillant p&eacute;riodiquement des enfants?");
		$body .= lili("chambre d'amis?");
		$body .= lili("chacun son espace/sa chambre?");
		$body .= "</ul>\n";

		$body .= "<p><b>Habitat:</b></p>\n";
		$body .= "<ul>\n";
		$body .= lili("quelle forme? (voir suite)");
		$body .= lili("jardin/terrasse, balcon?");
		$body .= lili("moderne, ancien, rustique, design, ecolo?");
		$body .= lili("{$piece}s: spacieuses, faciles $aa transformer plus tard, plain-pied?");
		$body .= lili("aussi pour la retraite ($mobiliteReduite)?");
		$body .= "</ul>\n";

		$body .= "</div>\n";
	//
		// Formes d'habitat
		$body .= getTitle("Formes d'habitat");

		$body .= "<p>Il faut consid&eacute;rer plusieurs aspects pour choisir la forme d'habitat qui nous convienne.\n";
		$body .= "Si l'on regarde le c&ocirc;t&eacute; &eacute;cologique, on peut compter le nombres de faces (murs et toit) $aa b&acirc;tir, isoler, faire les finitions, puis chauffer.</p>\n";

		$body .= "<div>\n";
		$body .= "<table>\n";
		$body .= "<tr><th>Habitat</th><th>Nombres de faces</th></tr>\n";
		$body .= "<tr><td>6 villas individuelles</td><td>30</td></tr>\n";
		$body .= "<tr><td>6 villas mitoyennes</td><td>24</td></tr>\n";
		$body .= "<tr><td>6 villas contigu&euml;es</td><td>20</td></tr>\n";
		$body .= "<tr><td>immeuble de 6 appartements</td><td>19</td></tr>\n";
		$body .= "</table>\n";
		$body .= "</div>\n";

			// Individuelle
			$body .= getTitle("Maison individuelle", 4);
			$body .= "<div>\n";
			$body .= "<ul>\n";

			$body .= liliPlus("libert&eacute;");
			$body .= liliPlus("priv&eacute; (pas de d&eacute;rangements par les murs/escaliers)");
			$body .= liliPlus("ensoleillement");
			$body .= liliPlus("transformations/travaux");

			$body .= liliMinus("co&ucirc;teux (construction, achat, charges, entretien)");
			$body .= liliMinus("grand terrain (jardin)");
			$body .= liliMinus("cambriolages");
			$body .= liliMinus("escaliers");

			$body .= "</ul>\n";
			$body .= "</div>\n";
		//
			// Bungalow
			$body .= getTitle("Bungalow (maison sur 1 $etage)", 4);
			$body .= "<p>Comme pour une maison individuelle mais avec plus de terrain et plus cher.</p>\n";
		//
			// Individuelle en lotissement
			$body .= getTitle("Maison individuelle en lotissement", 4);
			$body .= "<div>\n";
			$body .= "<ul>\n";

			$body .= liliPlus("5-10% moins cher qu'individuelle");
			$body .= liliPlus("partage des frais d'$equipements des terrains (eau, $electricite)");
			$body .= liliPlus("chantier commun");
			$body .= liliPlus("locaux/$equipements communs (place de jeux, garages, chauffage)");
			$body .= liliPlus("pas de bruits par les murs");

			$body .= liliMinus("frais d'entretien");

			$body .= "</ul>\n";
			$body .= "</div>\n";
		//
			// Mitoyenne
			$body .= getTitle("Maison mitoyenne/jumelle", 4);
			$body .= "<div>\n";
			$body .= "<ul>\n";

			$body .= liliPlus("20-25% moins cher qu'individuelle");
			$body .= liliPlus("partage des charges (chauffage, garage)");
			$body .= liliPlus("moins de chauffage");
			$body .= liliPlus("facile $aa revendre");

			$body .= liliMinus("surface de sol r&eacute;duite (donc escaliers)");
			$body .= liliMinus("bruits dans les murs");

			$body .= "</ul>\n";
			$body .= "</div>\n";
		//
			// En terrasses
			$body .= getTitle("Maison en terrasses", 4);
			$body .= "<div>\n";
			$body .= "<ul>\n";

			$body .= liliPlus("grande terrasse sur le toit");
			$body .= liliPlus("charges communes");
			$body .= liliPlus("souvent 1 seul $etage");

			$body .= liliMinus("construction 15-25% plus cher qu'individuelle");
			$body .= liliMinus("attention isolation phonique sols/plafonds (surtout si terrasse/garage sur le toit)");
			$body .= liliMinus("acc&egrave;s souvent par escaliers ext&eacute;rieurs (hiver, $mobiliteReduite)");

			$body .= "</ul>\n";
			$body .= "</div>\n";
		//
			// PPE
			$body .= getTitle("Appartement en PPE", 4);
			$body .= "<div>\n";
			$body .= "<ul>\n";

			$body .= liliPlus("jusqu'$aa 50% moins cher qu'indiviuelle");
			$body .= liliPlus("optimisation du terrain");
			$body .= liliPlus("moins de chauffage");
			$body .= liliPlus("ascenceur");
			$body .= liliPlus("charges et entretiens sont communs");
			$body .= liliPlus("il y a toujours quelqu'un de pr&eacute;sent quand on part en vacances");
			$body .= liliPlus("pas de t&acirc;che d'entretien chauffage/immeuble");
			$body .= liliPlus("appartement $generalement sur un niveau");
			$body .= liliPlus("d&egrave;s le 2e $etage, s&eacute;curit&eacute; pour les cambriolages (seulement par la porte)");

			$body .= liliMinus("r&egrave;glement");
			$body .= liliMinus("bruits des voisins");
			$body .= liliMinus("entr&eacute;e et escaliers communs");
			$body .= liliMinus("peu de locaux annexes");

			$body .= "</ul>\n";

			$body .= "<p>Quand on veut choisir un appartement $aa acheter, il faut chercher mieux qu'un appartement locatif:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("situation calme et ensoleill&eacute;e");
			$body .= lili("{$piece}s lumineuses");
			$body .= lili("agencement pratique et faciles d'entretien");
			$body .= lili("grandes surfaces");
			$body .= lili("salon avec balcon/terrasse");
			$body .= lili("cuisine et salles de bains modernes et pratiques");
			$body .= lili("suffisamment de rangements (armoires encastr&eacute;es, cagibi)");
			$body .= lili("mode de construction de qualit&eacute;");
			$body .= lili("isolation phonique sup&eacute;rieure - attention: locatif transform&eacute; en PPE souvent pas bon, le prix n'est pas gage de confort");
			$body .= lili("immeuble joli");
			$body .= "</ul>\n";

			$body .= "</div>\n";
	//
		// Neuf/occasion
		$body .= getTitle("Neuf ou occasion");

			// Occasion
			$body .= getTitle("Occasion", 4);

			$body .= "<div>\n";
			$body .= "<p>On peut acheter un bien existant, mais suivant l'ann&eacute;e de construction il faut se m&eacute;fier de certains {$materiau}x de construction:</p>\n";
			$body .= "<ul>\n";

			$body .= "<li><b>Amiante:</b> interdite depuis 1994, les b&acirc;timents plus vieux en contiennent certainement\n";
			$body .= "<ul>\n";
			$body .= lili("<b>faiblement agglom&eacute;r&eacute;:</b> risque permanent (choc, vibrations)");
			$body .= lili("<b>fortement agglom&eacute;r&eacute;:</b> risque si travaux (cass&eacute;, perc&eacute;...)");
			$body .= "</ul>\n";

			$body .= "Un assainissement $coute environ 250.-/m2 " . getLink("http://amiante-info.ch");
			$body .= "</li>\n";

			$body .= "<li><b>PolyChloroBiph&eacute;nyles (PCB):</b> dans les mastics de joints de dilatation, le rev&ecirc;tement des sols.\n";
			$body .= "Pose $probleme seulement en cas de travaux, n&eacute;cessite un assainissement pr&eacute;alable (cf. OFSP)</li>\n";

			$body .= lili("peintures au plomb");
			$body .= lili("radon " . getLink("http://ch-radon.ch"));

			$body .= "</ul>\n";

			$body .= "<p>Avantages et inconv&eacute;nients d'acheter d'occasion:</p>\n";
			$body .= "<ul>\n";

			$body .= liliPlus("on peut visiter ($etat, $equipements, dimensions, situations, voisinage...)");
			$body .= liliPlus("il est disponible rapidement");

			$body .= liliMinus("pas toujours possible de faire les transformations pour la maison de ses r&ecirc;ves (et c'est cher!)");
			$body .= liliMinus("$equipements ($electricite, sanitaire, chauffage, isolation) souvent anciens voire obsol&egrave;tes, entra&icirc;nant des charges {$eleve}es et des travaux $aa court terme");
			$body .= liliMinus("constructions anciennes ne sont pas top pour isolation thermique et phonique");
			$body .= liliMinus("si des $renovations sont indispensables, cela peut engendrer des mauvaises surprises au moment des travaux ($electricite, charpente, {$fenetre}s, canalisations) et entra&icirc;ner des co&ucirc;ts {$eleve}s");
			$body .= liliMinus("il est possible de d&eacute;couvrir que le terrain ait subi une pollution ou renferme des d&eacute;ch&ecirc;ts anciens");

			$body .= "</ul>\n";

			$body .= "<p>Pour un bien de plus de 10 ans, il est recommand&eacute; de faire une expertise.\n";
			$body .= "Si le contrat stipule \"en l'$etat\", il n'est pas possible de r&eacute;clamer pour des $defauts cach&eacute;s $apres l'achat.\n";
			$body .= "Une expertise $coute environ 2000.- et comprend l'$etat des murs, la statique du $batiment, le toit, les charpentes, les fa&ccedil;ades,\n";
			$body .= "l'isolation, le chauffage, l'$electricite, les sanitaires, les conduites, les canalisations, l'amiante etc.</p>\n";

			$body .= "<p>Des transformations importantes n&eacute;cessitent un permis de construire, y compris changement d'affectation de $piece, abattage de mur, ajout de $fenetre...\n";
			$body .= "Il faut contacter le service des constructions communal (et parfois aussi le cantonal).\n";
			$body .= "Cela implique des taxes, des plans $aa faire par un professionel et de l'attente (mise $aa l'enqu&ecirc;te).\n";
			$body .= "Si des travaux sont faits sans permis, le $proprio risque une amende et de devoir tout remettre en $etat.\n";
			$body .= "Si on ach&egrave;te un bien avec des transformations sans permis, le nouveau $proprio encourt les m&ecirc;mes sanctions.\n";
			$body .= "Avant d'acheter, il faut donc bien demander les plans et les permis des travaux effectu&eacute;s.</p>\n";

			$body .= "<p>Points importants $aa v&eacute;rifier (extrait du cadastre au $RF):</p>\n";
			$body .= "<ul>\n";
			$body .= lili("l'objet est-il grev&eacute; d'{$hyp}s?");
			$body .= lili("existe-t-il des servitudes/droits de passage?");
			$body .= lili("est-il class&eacute; monument historique? (transformations limit&eacute;es)");
			$body .= lili("quelqu'un est-il acheteur prioritaire (droit de pr&eacute;emption) ou utilisation $aa vie (usufruit) du bien?");
			$body .= "</ul>\n";

			$body .= "<p>Quand tout est clarifi&eacute;, &eacute;tablir un protocole pr&eacute;cis de l'$etat avec des photos au cas o&ugrave; le $proprio\n";
			$body .= "(entre la signature et la remise) n&eacute;gligerait l'entretien, voire remplacerait des $equipements par du meilleur $marche.</p>\n";

			$body .= "<p><b>Ferme:</b> seulement un agriculteur peut l'acheter.\n";
			$body .= "Si aucun agriculteur n'en veut, un non-agriculteur peut l'acheter mais cela reste un terrain agricole.\n";
			$body .= "Le $batiment est soumis $aa des autorisations sp&eacute;ciales pour des travaux.</p>\n";

			$body .= "</div>\n";
		//
			// Neuf
			$body .= getTitle("Neuf", 4);
			$body .= "<div>\n";
			$body .= "<p>Avantages et inconv&eacute;nients d'acheter \"sur plans\":</p>\n";
			$body .= "<ul>\n";

			$body .= liliPlus("on peut choisir beaucoup de choses");
			$body .= liliPlus("le prix est fixe et juste (&eacute;tudi&eacute; par la banque pour la construction)");

			$body .= liliMinus("on ne peut pas visiter");

			$body .= "<li class=\"minus\">on doit verser un accompte et signer un contrat de r&eacute;servation.\n";
			$body .= "Si l'entreprise fait faillite, l'accompte est perdu, $aa moins d'exiger une garantie ou de faire le versement sur un compte bloqu&eacute;</li>";

			$body .= liliMinus("s'il n'y a pas assez d'acheteurs, la construction est annul&eacute;e");
			$body .= liliMinus("certains choix font vite monter les co&ucirc;ts");

			$body .= "</ul>\n";

			$body .= "<p>Le chantier ne commence que quand 60-80% est vendu\n";
			$body .= "On peut aussi comparer l'offre avec d'autres promoteurs et faire jouer la concurrence.\n";
			$body .= "Pour se faire une id&eacute;e du promoteur/constructeur, on peut visiter leurs constructions existantes et demander aux habitants leurs exp&eacute;riences avec eux (qualit&eacute;, d&eacute;lai, disponibilit&eacute;, communication).\n";
			$body .= "Il est aussi bien de se renseigner sur la solvabilit&eacute; du promoteur/constructeur aux poursuites et sur " . getLink("http://focore.ch") . ".</p>\n";

			$body .= "</div>\n";
	//
		// Mobilite reduite
		$body .= getTitle("Mobilit&eacute; r&eacute;duite");
		$body .= "<div>\n";
		$body .= "<p>Sachant qu'on peut chacun $etre $aa $mobiliteReduite $aa un moment de sa vie, il est bon d'anticiper:</p>\n";
		$body .= "<ul>\n";
		$body .= lili("acc&eacute;s partout en fauteuil roulant (garage, buanderie, cave, galetas...), {$ideal}ement un seul niveau, sinon ascenceur $aa disposition");
		$body .= lili("quartier avec commerces, m&eacute;decin, transport publics, restaurant");
		$body .= lili("douche, si baignoire pr&eacute;voir des poign&eacute;es");
		$body .= lili("si possible des stores $plutot que des volets");
		$body .= lili("moquette (antid&eacute;rapante) dans les escaliers");
		$body .= lili("lampes claires, interrupteurs pr&egrave;s des portes et du lit");
		$body .= "</ul>\n";
		$body .= "</div>\n";
	//
		// Minimum exigible
		$body .= getTitle("Minimum exigible");
		$body .= "<div>\n";
		$body .= "<ul>\n";
		$body .= lili("un $batiment neuf devrait $etre minergie");
		$body .= lili("20-25% de surface en plus qu'un locatif: 100m2 pour 3p, 125 pour 4, 150 pour 5)");
		$body .= lili("grand salon, grande cuisine ouverte (30m2), 2 salles de bains, 14m2 par chambre quasi carr&eacute;e");
		$body .= lili("terrasse/balcon au moins 1.5m de large");
		$body .= lili("escaliers lumineux et chauff&eacute;s, toutes les {$piece}s accessibles avec l'ascenceur");
		$body .= "</ul>\n";
		$body .= "</div>\n";

			$body .= getTitle("Equipements", 4);
			$body .= "<div>\n";

			$body .= "<p>Cuisine:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("plan de travail en pierre ou en inox");
			$body .= lili("armoires coulissantes");
			$body .= lili("&eacute;clairages int&eacute;gr&eacute;s aux surfaces de travail");
			$body .= lili("lpan de cuisson vitroc&eacute;ramique ou induction");
			$body .= lili("lave-vaisselle classe &eacute;nerg&eacute;tique A");
			$body .= lili("four $aa hauteur des yeux avec fonction vapeur");
			$body .= lili("frigo avec compartiment cong&eacute;lation ou cong&eacute;lateur s&eacute;par&eacute;");
			$body .= "</ul>\n";

			$body .= "<p>Salles de bains:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("carrelage du sol au plafond sur tous les murs");
			$body .= lili("grande armoire pharmacie");
			$body .= lili("cabine de douche ou paroi de baignoire en alu et verre, surface minimum 0.8m2");
			$body .= lili("place de stockage pour produits de nettoyage et linges");
			$body .= lili("baignoire de minimum 1.80m de long ou d'angle");
			$body .= "</ul>\n";

			$body .= "<p>Installations &eacute;lectriques:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("prises TV-radio dans toutes les {$piece}s");
			$body .= lili("interrupteurs $aa distance pour les lampes $aa pied");
			$body .= lili("bo&icirc;tier de c&acirc;blage universel (informatique, t&eacute;l&eacute;phone)");
			$body .= lili("actionnement de prises, lampes, stores etc. par un syst&egrave;me de bus de commande");
			$body .= "</ul>\n";

			$body .= "<p>Sols:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("parquet ou carrelage, carrelage pour cuisine et salles de bains");
			$body .= "</ul>\n";

			$body .= "<p>Chauffage/ventilation:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("chauffage au sol ou radiateurs dans toutes les {$piece}s");
			$body .= lili("radiateur s&egrave;che-linge dans les salles de bains");
			$body .= lili("ventilation contr&ocirc;l&eacute;e avec r&eacute;cup&eacute;rateur de chaleur");
			$body .= "</ul>\n";

			$body .= "<p>Fen&ecirc;tres:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("doubles voire triples vitrages isolants et antibruits pour les lieux bruyants");
			$body .= lili("stores $aa lamelles int&eacute;gr&eacute;s");
			$body .= "</ul>\n";

			$body .= "<p>Buanderie:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("lave-linge et s&eacute;choir individuels dans l'appartement ou au sous-sol");
			$body .= "</ul>\n";

			$body .= "<p>Cave:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("cave individuelle ferm&eacute;e avec des parois (pas des lattes en bois)");
			$body .= "</ul>\n";

			$body .= "<p>Meubles int&eacute;gr&eacute;s:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("armoires dans les chambres $aa coucher");
			$body .= lili("vestiaire $aa l'entr&eacute;e");
			$body .= "</ul>\n";

			$body .= "<p>Garage:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("<b>immeubles:</b> une place au garage avec armoire fermant $aa cl&eacute; (pneus, porte-bagages...) et une place ext&eacute;rieure");
			$body .= lili("<b>maison:</b> un box ferm&eacute; pour 2 voitures ou garage souterrain");
			$body .= "</ul>\n";

			$body .= "</div>\n";
	//
		// Pompes a chaleur
		$body .= getTitle("Pompes $aa chaleur");
		$body .= "<div>\n";
		$body .= "<p>Il en existe plusieurs sortes:</p>\n";
		$body .= "<ul>\n";
		$body .= lili("<b>air/eau:</b> 30$kchf (40$kchf avec solaire) $ideal pour le chauffage au sol, facile $aa installer, mais utilise un gros ventilateur bruyant pour soi et le voisinage.");
		$body .= lili("<b>eau/eau:</b> 50$kchf et besoin d'un plan d'eau ou une nappe phr&eacute;atique ainsi que des autorisations difficiles $aa obtenir!!");
		$body .= lili("<b>sol/eau:</b> 45$kchf seulement si le terrain souterrain est OK, avec une autorisation cantonale, une mise $aa l'enqu&ecirc;te. Pour l'installation il faut une foresue de 20 tonnes qui doit pouvoir acc&eacute;der, le forage $coute 75.-/m et il faut creuser au moins 180m.");
		$body .= "</ul>\n";
		$body .= "</div>\n";
	//
		// Ecologie
		$body .= getTitle("Ecologie");
		$body .= "<div>\n";

		$body .= "<p>Pour construire &eacute;cologique, il faut penser $aa bien plus que ce que l'on croit: transports, {$materiau}x, $energie...</p>\n";

		$body .= "<p><b>Mat&eacute;riaux:</b> on peut utiliser du bois ou un autre $materiau indig&egrave;ne (pour diminuer l'$energie grise) le plus naturel possible, fixation avec des vis.\n";
		$body .= "Il existe aussi des {$materiau}x &eacute;cobiologiques (Natureplus, FSC) " . getLink("http://baubio.ch") . ".</p>\n";

		$body .= "<p><b>Energie:</b> on peut obtenir une &eacute;tiquette &eacute;nerg&eacute;tique (CECB) pour une maison, fournie avec des conseils pour environ 1$kchf pour une villa individuelle.\n";
		$body .= "Le chauffage est la plus grand source de consommation d'$nergie.\n";
		$body .= "Le mieux est la pompe $aa chaleur, ou les granul&eacute;s de bois, mais ces installations {$coute}nt cher $aa l'achat.\n";
		$body .= "Les co&ucirc;ts de consommation donnent le mazout le plus cher, puis le gaz naturel, et bien en dessous la pompe $aa chaleur.\n";
		$body .= "Les co&ucirc;ts d'entretien sont plus chers pour les granul&eacute;s que pour une pompe $aa chaleur, qui est plus cher que les autres moyens.</p>\n";

		$body .= "<p><b>Solaire:</b> 4m2 de panneaux thermiques chauffe 2/3 de l'eau pour l'ann&eacute;e (chaudi&egrave;re &eacute;teinte l'&eacute;t&eacute;).\n";
		$body .= "Cela $coute 15$kchf, dure 20 ans et est install&eacute; en 2 jours.\n";
		$body .= "Le photovolta&iuml;que pour une villa individuelle n'est pas rentable $aa ce jour (2015).</p>\n";

		$body .= "<p><b>Minergie:</b> 5-10% plus cher mais ca les vaut!!\n";
		$body .= "C'est un standard reconnu par les autorit&eacute;s et les banques.\n";
		$body .= "Il est possible d'obtenir des subventions, $aa demander avant le d&eacute;but des travaux (OFEN).\n";
		$body .= "Tous les architectes/ing&eacute;nieurs ne sont pas encore familiers, voir sur " . getLink("http://minergie.ch") . " une liste de gens de r&eacute;f&eacute;rence.</p>\n";

		$body .= "<p><b>Isolation:</b> ne pas &eacute;conomiser!! Toit 30cm, vitres triples 0.7 U max (unbit&eacute; de perte de chaleur), murs 24cm.</p>\n";

		$body .= "<p><b>Programme {$batiment}s:</b> subventions pour $renovations " . getLink("http://leprogrammebatiments.ch") . ".\n";
		$body .= "Il encourage l'isolation dans les {$batiment}s datant d'avant 2000.\n";
		$body .= "Il faut faire la demande des subventions avant le d&eacute;but des travaux.</p>\n";

		$body .= "</div>\n";
//
	// Recherche
	$body .= getTitle("Recherche de l'objet", 2);

	$body .= "<p>On peut chercher dans {$different}s moyens: internet (agences, portails tel anibis), aussi les journaux.";
	$body .= "Les assurances, les banques et les caisses de pension vendent aussi des biens immobiliers.</p>\n";

		// Dechifrer
		$body .= getTitle("D&eacute;chifrer");
		$body .= "<div>\n";
		$body .= "<ul>\n";

		$body .= "<li><b>Prix:</b> si plusieurs bien group&eacute;s, le prix est celui du meilleur $marche sans aucune option.\n";
		$body .= "'Bon rapport qualit&eacute;-prix' ne signifie rien.</li>\n";

		$body .= "<li><b>Surface:</b> souvent exag&eacute;r&eacute;e (comprenant caves, galetas, escaliers...).\n";
		$body .= "La surface habitable est la somme de toutes les surfaces chauff&eacute;es (sans la surface des murs).</li>\n";

		$body .= lili("Equipements de qualit&eacute;: $aa voir sur place, possible qualit&eacute; minimum.");
		$body .= lili("Images, animations, photos: se m&eacute;fier de ce qui n'est pas montr&eacute;.");
		$body .= lili("Si le bien est $aa vendre depuis longtemps, quelque chose cloche (prix, $etat, situation, voisins...).");

		$body .= "</ul>\n";
		$body .= "</div>\n";
	//
		// Aide
		$body .= getTitle("Aide");
		$body .= "<div>\n";
		$body .= "<ul>\n";

		$body .= "<li><b>Courtier:</b> cherche et aide jusqu'$aa la signature.\n";
		$body .= "Comission de 4% incluse dans le prix.\n";
		$body .= "Si on engage plusieurs courtiers, il est possible que plusieurs nous proposent le m&ecirc;me bien $aa des prix {$different}s.</li>\n";

		$body .= lili("Agent immobilier: 200.-/h");

		$body .= "<li><b>Chasseur immobilier:</b> assiste l'acheteur.\n";
		$body .= "Travaille avec un mandat de 3 mois en ayant le budget et les caract&eacute;ristiques.\n";
		$body .= "Il soumet une s&eacute;lection des biens qu'il va visiter.\n";
		$body .= "Travaille $generalement pour des biens de minimum 1'500$kchf, ou s'il n'impose pas de minimum, il facture 4% du prix en plus (hors comissions d'agences).</li>\n";

		$body .= "</ul>\n";
		$body .= "</div>\n";
	//
		// Ventes aux encheres
		$body .= getTitle("Ventes aux ench&egrave;res");
		$body .= "<div>\n";

		$body .= "<p>Il y a plusieurs biens $aa vendre aux ench&egrave;res chaque semaine, souvent $aa des prix {$interessant}s car ne couvrant que la dette.\n";
		$body .= "Consulter les offices cantonaux, voir " . getLink("http://poursuite-faillite-offic.ch") . ".\n";
		$body .= "<b>Attention</b> $aa $etre bien pr&eacute;par&eacute; car $apres le coup de marteau, il n'est plus possible de se r&eacute;tracter.</p>\n";

		$body .= "<ul>\n";

		$body .= lili("Visiter et examiner avec un professionel, car l'ench&egrave;re exclu le droit en cas de d&eacute;faut d&eacute;couverts ensuite.");
		$body .= lili("Examiner le $RF: &eacute;ventuels charges ($hyp), droits (pr&eacute;emption, usufruit), obligations (servitudes, passage).");
		$body .= lili("R&eacute;gler le financement $aa l'avance (la banque du vendeur risque de refuser, en choisir une autre).");
		$body .= lili("V&eacute;rifier le prix estim&eacute;, si doutes faire faire une estimation.");
		$body .= lili("Fixer une limite (fonds propres et $hyp) $aa l'avance <b>en accord</b> avec le banquier.");
		$body .= lili("Pr&eacute;parer un ch&egrave;que avec l'accompte (10-20% du prix estim&eacute;), exig&eacute; $apres le coup de marteau.");

		$body .= "</ul>\n";

		$body .= "<p><b>Imm&eacute;diatement $apres la vente</b>, il faut assurer le bien!\n";
		$body .= "En revanche, il reste un droit de recours de 30 jours (par exemple pour les cr&eacute;anciers), donc on ne peut pas emm&eacute;nager avant sauf si on en a l'accord explicite.</p>\n";

		$body .= "</div>\n";
	//
		// Prix
		$body .= getTitle("Prix");

		$body .= "<p>V&eacute;rifier le prix car la banque {$pret}e selon sa propre estimation.\n";
		$body .= "Si le prix est plus haut, il faut compenser la diff&eacute;rence avec des fonds propres, et il ne sera peut-$etre pas possible de revendre $aa un prix autant $eleve.</p>\n";

		$body .= "<p>Le principal facteur d&eacute;terminant le prix est la situation: voisinage, $equipements, acc&egrave;s, vue, SOleil, bruit, $impots (d&eacute;chets, eau...).</p>\n";

			// Experts
			$body .= getTitle("Experts", 4);
			$body .= "<div>\n";

			$body .= "<ul>\n";

			$body .= "<li><b>CIFI</b> (Centre d'Information et de Formation Immobili&egrave;res:\n";
			$body .= "estimation online instantan&eacute;e pour environ 500.-.\n";
			$body .= "Fonctionne bien pour un bien courant \"standard\", utilise environ 20 crit&egrave;res.\n";
			$body .= "M&eacute;thode h&eacute;doniste: se base sur des milliers de transactions similaires r&eacute;centes, donc proche du $marche.\n";
			$body .= getLink("http://cifi.ch");
			$body .= "</li>\n";

			$body .= "<li><b>Courtier immobilier:</b> conna&icirc;t bien le $marche, fait des visites, donne un rapport d&eacute;taill&eacute; des atouts et des d&eacute;fauts.\n";
			$body .= "Si on engage un courtier pour vendre, le service est un pourcentage du prix.\n";
			$body .= "Il faut se m&eacute;fier, car il pourrait alors sur&eacute;valuer pour d&eacute;crocher le mandat.\n";
			$body .= "La contre-expertise de la banque peut $etre plus basse et refroidir les acheteurs.\n";
			$body .= getLink("http://uspi.ch") . " -> membres</li>\n";

			$body .= "<li><b>Expert en estimation:</b> la meilleure solution pour estimer le prix!\n";
			$body .= "250.-/h, environ 2$kchf pour une villa.\n";
			$body .= getLink("http://uspi.ch") . " -> chambre d'experts en estimation immobili&egrave;re</li>\n";

			$body .= "<li><b>Architecte:</b> approche b&acirc;s&eacute;e sur les co&ucirc;ts de construction; utile pour des r&eacute;novations.\n";
			$body .= "Environ 2$kchf pour une villa, voire plus s'il doit demander des offres pour des travaux.\n";
			$body .= getLink("http://sia.ch") . " et " . getLink("http://uts.ch") . "</li>\n";

			$body .= "</ul>\n";

			$body .= "<p>Une expertise classique (ch&egrave;re) est appropri&eacute;e pour les immeubles anciens ou pour conna&icirc;tre l'$etat/les possibilit&eacute;s de transformations.\n";
			$body .= "Sinon la m&eacute;thode h&eacute;doniste est plus proche du $marche.</p>\n";

			$body .= "</div>\n";
		//
			// Negocier
			$body .= getTitle("N&eacute;gocier", 4);
			$body .= "<div>\n";

			$body .= "<p>Toujours!!!</p>\n";

			$body .= "<p>Pour un objet <b>ancien</b>, le potentiel de $negociations est &eacute;lev&eacute;.\n";
			$body .= "Il faut donc $etre bien pr&eacute;par&eacute; et demander une expertise.\n";
			$body .= "Attention toutefois car si on baisse trop le prix, il est possible qu'un autre acheteur remporte l'objet.\n";
			$body .= "Si l'objet est recherch&eacute;, il s'agira alors d'une ench&egrave;re officieuse.\n";
			$body .= "Il est important d'avopir une confirmation &eacute;crite de la banque, et garder en t&ecirc;te nos limites de fonds propres et $hyp.</p>\n";

			$body .= "<p>Pour un objet <b>neuf</b>, on peut $negocier le contenu: parquet au lieu de moquette, plus de rangements, rabais sur une place de parc...\n";
			$body .= "Il faut bien mettre par &eacute;crit tout arrangement!</p>\n";

			$body .= "</div>\n";
		//
			// Visite
			$body .= getTitle("Visite", 4);
			$body .= "<div>\n";

			$body .= "<p>Si possible visiter $aa 2 pour une observation plus compl&egrave;te. Pr&eacute;voir assez de temps:</p>\n";
			$body .= "<ul>\n";
			$body .= "<li>Si la premi&egrave;re impression est n&eacute;gative, faire quand $meme la visite en entier pour avoir des points de comparaisons et des id&eacute;es d'am&eacute;nagement.</li>\n";
			$body .= "<li>Si la visite est positive, r&eacute;colter les documents, demander si des travaux sont pr&eacute;vus et l'$etat des $equipements.</li>\n";
			$body .= "</ul>\n";

			$body .= "<p>Ne rien signer!! M&ecirc;me si on nous met la pression (autres acheteurs)! Fixer un rendez-vous pour une 2e visite.</p>\n";
			$body .= "<p>Si int&eacute;ress&eacute;, revenir voir plusieurs fois $aa des jours et des heures {$different}s, id&eacute;alement par tous les temps pour v&eacute;rifier l'ensoleillement, le bruit, l'exposition aux intemp&eacute;ries, les odeurs, la vie du quartier...</p>\n";
			
			$body .= "<p>Orientation E-W mieux que N-S (qui a clairement un $cote chaud et un froid).</p>\n";
			$body .= "<p>Plus on est haut dans les {$etage}s, plus on a de Soleil (sauf s'il y a un vis-a-vis).</p>\n";
			$body .= "<p>Isolation phonique: SIA181</p>\n";
			$body .= "<p>Am&eacute;nagements futurs: $ideal si les murs int&eacute;rieurs ne sont pas porteurs.</p>\n";
			$body .= "<p>La distribution des {$piece}s est importante: ce n'est pas $ideal de traverser le salon pour aller dans la chambre...</p>\n";
			$body .= "<p>Terrasse: intimit&eacute;, exposition, bruit, abri?</p>\n";
			$body .= "<p>Jardin: taille, entretenu, plat/pente, escaliers/chemin raide, robinets, prises, $eacute;clairages, outils, cl&ocirc;tur&eacute;?</p>\n";
			$body .= "<p>\"Am&eacute;nagements ext&eacute;rieurs\" pour objet neuf: $aa clarifier en $detail car les promoteurs &eacute;conomisent souvent.</p>\n";

			$body .= "</div>\n";
//
	// Construire: preparation
	$body .= getTitle("Construire: pr&eacute;paration", 2);

	$body .= "<p>Les $couts de construction sont en constante augmentation.\n";
	$body .= "Il est possible de partager les frais de chantier et de raccordement si le terrain voisin construit en $meme temps.</p>\n";

		// Terrain
		$body .= getTitle("Terrain");
		$body .= "<div>\n";

		$body .= "<p>Pour le choix du terrain, $etre ouvert! Aller voir sur place avant de dire non.</p>";

		$body .= "<p>Contacts pour trouver un terrain:</p>\n";
		$body .= "<ul>\n";
		$body .= lili("Demander $aa la commune: elle sait avant le public, et poss&egrave;de aussi des terrains");
		$body .= lili("Aller voir s'il y a des pancartes sur place, des annonces dans les magasin...");

		$body .= "<li>Les architectes/artisans vendent des terrains pas cher, mais avec une clause de construire avec eux.\n";
		$body .= "Cela supprime la concurrence et peut donc $etre cher.\n";
		$body .= "Bien se renseigner sur l'ensemble du prix du projet!</li>\n";

		$body .= "</ul>\n";

		$body .= "<p>Avant d'avoir le terrain, il est inutile de choisir les plans car la configuration et les contraintes sont d&eacute;t&eacute;rminants.</p>\n";

		$body .= "<p>Un lotissement est moins cher, mais on a moins de libert&eacute;s.</p>\n";

		$body .= "<p>Points $aa relever lors de la visite du terrain:</p>\n";
		$body .= "<ul>\n";

		$body .= "<li><b>Qualit&eacute;:</b>\n";
		$body .= "emplacement, topographie, ensoleillement, tranquilit&eacute;, qualit&eacute; du sol et des sous-sols, passages d'eat ou sources souterraines,\n";
		$body .= "nappe phr&eacute;atique, $equipements existants (sinon cela fait vite grimper la facture du coup le prix du terrain au m2; &eacute;quiper un terrain $coute env. 150.-/m2)\n";
		$body .= "acc&egrave;s, vue, futures constructions...</li>\n";

		$body .= "<li><b>Voisinage:</b>\n";
		$body .= "aller rencontrer les gens (voisins, commerces, resto) et discuter: projets (habitations, commerces, industries, routes),\n";
		$body .= "qualit&eacute; des infrastructures (commerces, &eacute;coles, h&ocirc;pitaux, transports publics), nuisances...</li>\n";

		$body .= "<li><b>Administratif:</b>\n";
		$body .= "le terrain correspond-il au prix du $marche de la r&eacute;gion? (se renseigner aupr&egrave;s de fisc, banques r&eacute;gionales/cantonales, courtier, voisins)\n";
		$body .= "Le terrain est-il grev&eacute; de servitudes fonci&egrave;res? (cf. $RF, plan de zone)\n";
		$body .= "Quels sont la surface, les limites, les conduites, &eacute;lectricit&eacute; etc, zones $aa b&acirc;tir/agricoles/village/villa...</li>\n";

		$body .= "</ul>\n";

		$body .= "</div>\n";
	//
		// Servitudes foncieres
		$body .= getTitle("Servitudes fonci&egrave;res");
		$body .= "<div>\n";

		$body .= "<p>Il s'agit d'obligations ou de contraintes pour le $proprio.\n";
		$body .= "Il peut s'agir d'une interdiction de construire.\n";
		$body .= "Elles sont inscrites au $RF par un contrat &eacute;crit et notari&eacute; avec les d&eacute;tails de l'&eacute;tendue de la servitude et de l'entretien.\n";
		$body .= "Elles sont valables jusqu'$aa radiation du $RF.\n";
		$body .= "Le $proprio peut demander au juge la radiation si elle n'est plus utile au b&eacute;n&eacute;ficiaire.</p>\n";

		$body .= "<p>Formes de servitudes:</p>\n";
		$body .= "<ul>\n";
		$body .= lili("canalisations");
		$body .= lili("construction (droit de superficie, voir dessous)");
		$body .= lili("empi&egrave;tement, restrictions du droit $aa b&acirc;tir");
		$body .= lili("droit de passage (pied, v&eacute;hicule), parcage");
		$body .= lili("plantations (tailler les haies), cl&ocirc;tures (repeindre)");
		$body .= lili("divers: usufruit, usage ext&eacute;rieur, abri PC...");
		$body .= "</ul>\n";

		$body .= "<p>Le droit de superficie permet de construire sur un terrain lou&eacute; (pour une dur&eacute;e de 30 $aa 100 ans).\n";
		$body .= "Le $proprio peut r&eacute;clamer le terrain si le b&eacute;n&eacute;ficiant viole gravement ses obligations.\n";
		$body .= "C'est cher sur le long terme.</p>\n";

		$body .= "</div>\n";
	//
		// Reglement de construction
		$body .= getTitle("R&egrave;glement de construction (communal)");
		$body .= "<p>Il d&eacute;finit beaucoup de d&eacute;tails pour la construction:\n";
		$body .= "ratio surface maison/terrain, hauteur max, nombre d'&eacute;tages max, orientation, pente du toit, dimensions des fen&ecirc;tres, nombre max de velux, couleurs ext&eacute;rieures,\n";
		$body .= "longueur max d'un mur sans d&eacute;crochement, distances entre les maisons et les limites du terrain, surface minimum..........</p>\n";
	//
		// Influences negatives
		$body .= getTitle("Influences n&eacute;gatives");
		$body .= "<p>On peut demander une &eacute;tude g&eacute;obiologiue (200.-) du terrain avant l'achat, voir " . getLink("http://lieudevie.ch") . ".\n";
		$body .= "Il s'agit d'une analyse de l'&eacute;lectromagn&eacute;tisme, passage des eaux...\n";
		$body .= "On peut aussi le faire avant de construire pour aider $aa choisir l'emplacement, l'orientation, l'am&eacute;nagement...</p>\n";
	//
		// Concevoir sa maison
		$body .= getTitle("Concevoir sa maison");
		$body .= "<div>\n";

		$body .= "<p>On peut concevoir sa maison en suivant diff&eacute;rentes voies:</p>\n";

		$body .= "<ul>\n";

		$body .= lili("<b>Maison d'architecte:</b> sur mesure, tout personnalisable, unique. Co&ucirc;te cher et prend tu temps pour $etre r&eacute;alis&eacute;.");

		$body .= "<li><b>Sur catalogue, maison type, pr&eacute;fabriqu&eacute;e:</b>\n";
		$body .= "La r&eacute;alisation est rapide.\n";
		$body .= "On peut visiter une maison t&eacute;moin.\n";
		$body .= "On peut changer +/- la distribution et la taille des ${piece}s; on ne peut pas choisir les mat&eacute;riaux.\n";
		$body .= "Le prix ne comprend que le mod&egrave;le le plus basique, il faut compter des suppl&eacute;ments pour beaucoup de choses (excaver, garage, terrassements...).\n";
		$body .= "Et bien $sur, chaque demande de modifications augmente le prix.\n";
		$body .= "A noter: les entreprises &eacute;trang&egrave;res proposent de r&eacute;aliser le $meme bien pour moins cher, mais il faut aller sur place pour faire les choix, et pas facilement r&eacute;parable.\n";
		$body .= "</li>\n";

		$body .= lili("<b>Maison syst&egrave;me:</b> plans existants mais construite sur place par des artisans; comme la maison type.");

		$body .= "<li><b>Maison en bois:</b>\n";
		$body .= "dur&eacute;e de vie comme le b&eacute;ton, $meme prix.\n";
		$body .= "L'ossature peut $etre l&eacute;g&egrave;re ou massif.\n";
		$body .= "Le bois offre une meilleure isolation (thermique et phonique), il est l&eacute;ger et solide.\n";
		$body .= "Il est possible de fabriquer des &eacute;l&eacute;ments en atelier en parall&egrave;le $aa d'autres travaux sur place.\n";
		$body .= "Attention car le bois apparent a besoin d'entretien (usure du Soleil, air, faune, flore...);\n";
		$body .= "$aa cet effet, il est bon de pr&eacute;voir un avant-toit d'au moins 1m et un bon drainage.\n";
		$body .= "</li>\n";

		$body .= "<li><b>Maison $aa d&ocirc;me rotatif:</b>\n";
		$body .= "ronde, en bois, tourne, peut suivre le Soleil (pour la chaleur ou des panneaux solaires) ou se mettre $aa l'abri du vent/bruit.\n";
		$body .= "Moins de surface donc moins de chauffage.\n";
		$body .= "</li>\n";

		$body .= "<li><b>Maison en paille et terre:</b>\n";
		$body .= "L'ossature est en bois, la paille et la terre isolent.\n";
		$body .= "On peut visiter une telle maison a Morrens (VD), Pres-vers-Siviriez (FR), Sainte-Ursanne (JU), Eco46 (";
		$body .= getLink("http://lamaisonnature.ch") . ").\n";
		$body .= "</li>\n";

		$body .= "</ul>\n";

		$body .= "</div>\n";
	//
		// Plans
		$body .= getTitle("Plans");
		$body .= "<div>\n";
		$body .= "<ul>\n";

		$body .= "<li><b>Porte d'entr&eacute;e:</b> facilement accessible depuis la cuisine, les escaliers et les zones d'habitation.\n";
		$body .= "Sur le $cote le moins expos&eacute; aux intemp&eacute;ries (on peut ajouter un avant-toit).</li>\n";

		$body .= lili("<b>Vestibule:</b> plaque tournante. Bien &eacute;clair&eacute; et spacieux.");
		$body .= lili("<b>Salon:</b> le plus au Sud possible (moins de Soleil en $ete et plus en hiver). Acc&egrave;s jardin/terrasse.");
		$body .= lili("<b>Salle $aa manger:</b> bien reli&eacute;e $aa cuisine et salon.");
		$body .= lili("<b>Cuisine:</b> proche de l-entr&eacute;e, du salon, et la salle $aa manger, des chambres d'enfatns et avec vus sur la place de jeux.");
		$body .= lili("<b>Buanderie:</b> proche de cuisine et salles de bains, si possible $aa la cave ou $aa l'&eacute;tage.");

		$body .= "<li><b>Chambres $aa coucher:</b>\n";
		$body .= "dans la partie calme, proche des chambres d'enfants et d'une salle de bains.\n";
		$body .= "Si possible $aa l'Est.\n";
		$body .= "Attention de la placer dans la partie la plus &eacute;loign&eacute;e du bruit (terrasse, route, voisins...) pour pouvoir ouvrir les fen&ecirc;tres la nuit.\n";
		$body .= "</li>\n";

		$body .= "<li><b>Chambres d'enfants:</b>\n";
		$body .= "au Sud ou Sud-Est (l'Ouest chauffe trop).\n";
		$body .= "Pour des petits enfants, avoir la possibilit&eacute; de les voir et les entendre.\n";
		$body .= "Ne pas sous-estimer la surface.\n";
		$body .= "Les situer pour que les enfants puissent participer $aa la vie de la maison depuis leur chambre (pas que pour dormir).\n";
		$body .= "</li>\n";

		$body .= lili("<b>Salles de bains:</b> $cote dortoir, pas coll&eacute;es (murs) aux chambres mais reli&eacute;es par un couloir.");
		$body .= lili("<b>WC visiteurs:</b> proches de l'entr&eacute;e, &eacute;loign&eacute;s de la cuisine et du salon.");
		$body .= lili("<b>Chambres d'amis:</b> acc&egrave;s $aa l'entr&eacute;e, &eacute;ventuellement petite salle de bains.");
		$body .= lili("<b>Bureau:</b> acc&egrave;s salon et vestibule.");
		$body .= lili("<b>Rangements:</b> Nord pour pas chauffer, a&eacute;r&eacute;s.");
		$body .= lili("<b>Cave:</b> attention $aa l'humidit&eacute;.");

		$body .= "<li><b>Jardin:</b>\n";
		$body .= "souvent oubli&eacute; pour des raisons budgetaires.\n";
		$body .= "Si on refl&eacute;chit mieux: planifier tous les souhaits et les r&eacute;aliser sur plusieurs ann&eacute;es.\n";
		$body .= "On peut ainsi faire les gros travaux (fondations) avec le rest du chantier pendant que les machines sont pr&eacute;sentes.\n";
		$body .= "</li>\n";

		$body .= "</ul>\n";
		$body .= "</div>\n";
//
	// Construire: execution
	$body .= getTitle("Construire: ex&eacute;cution", 2);

		// Avec un architecte
		$body .= getTitle("Avec un architecte");
		$body .= "<div>\n";

		$body .= "<p>L'architecte est la personne de r&eacute;f&eacute;rence.\n";
		$body .= "Il doit donc $etre un bon planificateur, chef de chantier, comptable, psychologue...</p>\n";

		$body .= "<p>Quelques crit&egrave;res pour bien choisir son architecte:</p>\n";
		$body .= "<ul>\n";
		$body .= lili("ses maisons r&eacute;alis&eacute;es nous plaisent (esth&eacute;tique, qualit&eacute;)");
		$body .= lili("il tient les d&eacute;lais et le budget");
		$body .= lili("ses maisons s'int&egrave;grent dans l'environnement");
		$body .= lili("il est int&eacute;ress&eacute; $aa la construction &eacute;cologique, id&eacute;alement avec de l-exp&eacute;rience");
		$body .= lili("il est situ&eacute; proche du futur chantier (plus il est loin, plus il y aura de frais)");
		$body .= lili("il conna&icirc;t le lieu (et ses r&egrave;glements)");
		$body .= lili("il est ouvert aux id&eacute;es nouvelles et coop&eacute;ratif");
		$body .= "</ul>\n";

		$body .= "<p>Il est bien de rencontrer plusieurs architectes,\n";
		$body .= "visiter plusieurs de leurs r&eacute;alisations,\n";
		$body .= "les inviter chez nous pour qu'ils voient comment on vit,\n";
		$body .= "leur poser des questions personnelles,\n";
		$body .= "leur demander s'ils ont $deja eu des probl&egrave;mes sur un chantier (oui!)...</p>\n";

		$body .= "<p>On peut aussi demander un devis provisoire avec un budget max.\n";
		$body .= "Quand on a choisi l'architecte, on peut demander un avant-projet.\n";
		$body .= "C'est une &eacute;tude du terrain et des r&egrave;glements avec une estimation du budget et des dessins.\n";
		$body .= "Cela compte pour 9% des honoraires, soit environ 9$kchf pour une villa.</p>\n";

		$body .= "</div>\n";

			// Contrats de l architecte
			$body .= getTitle("Contrats de l'architecte", 4);
			$body .= "<p>SIA formulaire 1102</p>\n";
			$body .= "<p>Doit contenir les d&eacute;tails du mandat, entre autres faire des plans dans les r&egrave;gles de l'art,\n";
			$body .= "surveiller et respecter le devis, d&eacute;fendre les int&eacute;r&ecirc;ts des clients.\n";
			$body .= "Peut $etre r&eacute;sili&eacute; en tout temps par chacune des parties, normalement avec dommages et int&eacute;r&ecirc;ts.</p>\n";

			$body .= "<p>Bien lire et relire, demander aussi $aa un sp&eacute;cialiste avant de signer.</p>\n";
		//
			// Honoraires de l'architecte
			$body .= getTitle("Honoraires de l'architecte", 4);
			$body .= "<div>\n";

			$body .= "<p>SIA 102</p>\n";

			$body .= "<ul>\n";
			$body .= lili("tarif-temps: temps n&eacute;cessaire $aa la r&eacute;alisation. Tarif horaire SIA en fonction de l'exp&eacute;rience.");
			$body .= lili("tarif-co&ucirc;t/tarif-volume: en fonction du montant des travaux, de la difficult&eacute; (proportionnel au prix de la maison)");

			$body .= "<li>forfait: en fonction du devis, &eacute;vite que l'architect d&eacute;passe les d&eacute;lais/le budget.\n";
			$body .= "Exiger que toutes les modifications enatra&icirc;nant un surco&ucirc;t fasse l'objet d'un devis d&eacute;taill&eacute; avec confirmation &eacute;crite de l'architecte.</li>\n";
			$body .= "</ul>\n";

			$body .= "</div>\n";
		//
			// Devis
			$body .= getTitle("Devis", 4);
			$body .= "<div>\n";

			$body .= "<p>Ce n'est qu'une &eacute;valuation! Pr&eacute;voir des marges:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("avant-projet: +/-15%");
			$body .= lili("projet: +/-10%");
			$body .= lili("apr&egrave;s l'appel d'offres: +/-5%");
			$body .= "</ul>\n";

			$body .= "<p>Avant de signer le contrat, bien v&eacute;rifier toutes les clauses et demander conseil $aa un professionnel.\n";
			$body .= "Il est possible d'adh&eacute;rer a la Chambre Immobili&egrave;re cantonale qui recommande des experts.</p>\n";

			$body .= "<p>Le versement des honoraires se fait g&eacute;n&eacute;ralement par tranchesfix&eacute;es $aa l'avance, par exemple la premi&egrave;re apr&egrave;s les fondations.</p>\n";

			$body .= "<p>Pour $etre $sur que tout est OK, convenir de la derni&egrave;re tranche de 15% sur un compte blolqu&eacute;,\n";
			$body .= "vers&eacute;e quand les travaux sont finis $aa la satisfaction du client, y compris des &eacute;ventuels travaux de r&eacute;parations ou de remplacement en cas de d&eacute;faut.</p>\n";

			$body .= "</div>\n";
	//
		// Entreprise generale/totale
		$body .= getTitle("Entreprise g&eacute;n&eacute;rale/totale");
		$body .= "<div>\n";

		$body .= "<ul>\n";
		$body .= lili("<b>Entreprise g&eacute;n&eacute;rale:</b> r&eacute;alise l'ensemble des travaux selon les plans.");
		$body .= lili("<b>Entreprise totale:</b> comnme g&eacute;n&eacute;rale, et planfification de projet, peut concevoir des maisons d'architecte.");
		$body .= "</ul>\n";

		$body .= "<p>La plupart proposent des avant-projets (devis et plans) gratuits.</p>\n";

		$body .= "<p>Quelques crit&egrave;res pour bien choisir son entreprise (come pour un architecte):</p>\n";
		$body .= "<ul>\n";
		$body .= lili("qualit&eacute;: pr&eacute;f&eacute;rer un membre de " . getLink("http://aseg.ch"));
		$body .= lili("r&eacute;flexion: pas de pr&eacute;cipitation, m&ecirc;me s'il y a des pressions de l'entreprise.");
		$body .= lili("solvabnilit&eacute;: " . getLink("http://aseg.ch") . " OK, sinon demander Office des Poursuites. Les petites entreprises ne peuvent pas toujours assumer l'impr&eacute;vu.");
		$body .= lili("r&eacute;putation: fuir les mauvaises (demander aux amis, banquier...).");
		$body .= lili("lamentations: regarder sur les forums, par ex. " . getLink("http://forumconstruire.ch"));
		$body .= lili("documents: ASEG peut fournir un contrat-type pour 25.-. Si l'entreprise impose son propre document, mandater un sp&eacute;cialiste (architecte/juriste) pour v&eacute;rifier!");
		$body .= lili("lucidit&eacute;: se faire une id&eacute;e des prix. Les prix d'appel augmentent durant le chantier.");
		$body .= "</ul>\n";

		$body .= "<p>Comparer les offres: les meilleurs prix 'oublient' souvent les ext&eacute;rieurs, ou choisissent des mat&eacute;riaux bon $marche de mauvaise $qualite...</p>";

		$body .= "</div>\n";

			// Contrats de l entrepreneur
			$body .= getTitle("Contrats de l'entrepreneur", 4);
			$body .= "<div>\n";

			$body .= "<p>Pas de cadre l&eacute;gal, mais $generalement faits selon la norme SIA118.\n";
			$body .= "Exiger qu'il r&eacute;f&eacute;rencie:</p>\n";
			$body .= "<ul>\n";

			$body .= "<li>descriptif technique d&eacute;taill&eacute;\n";
			$body .= "(mat&eacute;riaux, &eacute;paisseurs des murs/parois, isolation, fen&ecirc;tres),\n";
			$body .= "d&eacute;finit pr&eacute;cis&eacute;ment la collaboration et l'ouvrage.</li>\n";

			$body .= "<li>proc&eacute;dure pour les modifications d&eacute;crite pr&eacute;cis&eacute;ment.\n";
			$body .= "Le $cout des changements &eacute;ventuels doit $etre transparent;\n";
			$body .= "les modifications ne doivent $etre r&eacute;alis&eacute;es qu'apr&egrave;s signature de l'offre par le ma&icirc;tre d'ouvrage (futur proprio).\n";
			$body .= "Etablir un avenant &eacute;crit si cela modifie la valeur de l'ouvrage.</li>\n";

			$body .= lili("le chef du chantier doit $etre formellement nomm&eacute;.");

			$body .= "<li>La date de r&eacute;ception de la maison.\n";
			$body .= "(Une bonne entreprise respecte le d&eacute;lai y compris avec des impr&eacute;vus.)\n";
			$body .= "On peut pr&eacute;voir dans le contrat le versement d'une indemnit&eacute; journali&egrave;re.</li>\n";

			$body .= lili("le plan de financement qui garantit que si l'entrepreneur fait faillite, les prestations pay&eacute;es seront bien fournies.");

			$body .= "<li>la garantie d'ex&eacute;cution qui assure les d&eacute;dommagements si pendant les travaux l'entreprise\n";
			$body .= "ne peut pas fournir les prestations convenues (garantie par banque/assurance).</li>\n";

			$body .= "<li>la garantie pour les d&eacute;fauts occasionn&eacute;s par les sous-traitans de l'entrepreneur.\n";
			$body .= "Les d&eacute;fauts doivent $etre r&eacute;clam&eacute;s au maximum dans les 2 ans suivant la r&eacute;c&eacute;ption\n";
			$body .= "(pour les vices cach&eacute;s, max 5 ans apr&egrave;s la r&eacute;c&eacute;ption).</li>\n";

			$body .= "<li>le prix, les modalit&eacute;s de paiement &eacute;chelonn&eacute; selon l'avancement des travaux.\n";
			$body .= "Attention: ne pas accepter une clause qui interdit de retenir la derni&egrave;re tranche en cas de d&eacute;faut!</li>\n";

			$body .= lili("la RC de l'entreprise (dommages aux personnes et aux choses, dommages et vices de construction).");

			$body .= "</ul>\n";

			$body .= "<p>Bien lire et relire, demander aussi $aa un sp&eacute;cialiste avant de signer.</p>\n";

			$body .= "</div>\n";
		//
			// Hypothese legale
			$body .= getTitle("Hypoth&egrave;se l&eacute;gale", 4);
			$body .= "<div>\n";

			$body .= "<p>Si l'entrepreneur ne peut plus payer les artisans, le $proprio doit les payer.\n";
			$body .= "Les artisans peuvent demander l'$hyp l&eacute;gale, le droit au gage sur l'ouvrage.\n";
			$body .= "Cela sert $aa rembourser le mat&eacute;riel et les heures.\n";
			$body .= "Elle est soumise $aa des r&egrave;gles strictes:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("r&eacute;serv&eacute;e aux ma&icirc;tres d'&eacute;tats ind&eacute;pendants qui ont directement travaill&eacute; sur le chantier");
			$body .= lili("le mat&eacute;riel fourni doit avoir $ete fait sur mesure");
			$body .= lili("au maximum 4 ans apr&egrave;s le travail");
			$body .= "</ul>\n";

			$body .= "<p>En cas de d&eacute;saccord, il faut rencontrer un juge pour une inscription provisoire au $RF, m&eacute;diation etc.\n";
			$body .= "Si pas d'entente, le $proprio doit payer encore.\n";
			$body .= "Si pas possible, poursuites et vente de la maison!</p>\n";

			$body .= "</div>\n";
		//
			// Honoraires
			$body .= getTitle("Honoraires de l'entrepreneur", 4);
			$body .= "<div>\n";
			$body .= "<ul>\n";
			$body .= lili("forfait");
			$body .= lili("prix total: comme un forfait, mais varie selon l'&eacute;volution des prix");
			$body .= lili("prix unitaire: d&eacute;termin&eacute; selon les unit&eacute;s n&eacute;cessaires, fix&eacute; apr&egrave;s les travaux");
			$body .= lili("prix effectif: devis approximatif, prix apr&egrave;s les travaux");
			$body .= "</ul>\n";
			$body .= "</div>\n";
	//
		// Architecte vs Entrepreneur
		$body .= getTitle("Architecte vs Entrepreneur");
		$body .= "<div>\n";

		$body .= "<p>Quelques diff&eacute;rences si l'on compare la construction avec un architecte plut&ocirc;t qu'avec un entrepreneur:</p>\n";
		$body .= "<ul>\n";

		$body .= lili("<b>Libert&eacute;:</b>parfois aussi avec un entrepreneur, mais souvent les changements de plans sont tr&egrave;s co&ucirc;teux.");
		$body .= lili("<b>Choix des mat&eacute;riaux et artisans:</b> pas possible avec un entrepreneur, ce qui fait un risque d'$hyp l&eacute;gale.");
		$body .= lili("Le projet est unique, les erreurs sont possibles et il y a de multiples interlocuteurs.");
		$body .= lili("Les co&ucirc;ts sont variables.");
		$body .= lili("Les d&eacute;lais seront d&eacute;pass&eacute;s.");
		$body .= lili("L'avant-projet est payant.");
		$body .= lili("Chaque artisan a un contrat.");

		$body .= "</ul>\n";
		$body .= "</div>\n";
	//
		// SUivi du chantier
		$body .= getTitle("Suivi du chantier");
		$body .= "<p>L'interlocuteur est le chef de chantier.\n";
		$body .= "C'est lui qui s'occupe de signaler les problm&egrave;mes, demander les changements, etc.\n";
		$body .= "Le $proprio doit aussi suivre de pr&egrave;s.\n";
		$body .= "En cas de probl&egrave;me, prendre des notes et des photos et aviser l'entrepreneur par ecrit.\n";
		$body .= "Amener de temps en temps une collation aux ouvriers aide $aa faire avancer les travaux dans le bon sens et dans les d&eacute;lais.\n";
		$body .= "Venir voir le chantier $aa des heures irr&eacute;guli&egrave;res permet de rep&eacute;rer les fain&eacute;ants.\n";
		$body .= "Il faut signaler les irr&eacute;gularit&eacute;s (alcool, d&eacute;chets, feux...).\n";
		$body .= "On peut demander des modifications au r&egrave;glement de chantier, par exemple qu'il soit interdit de fumer dans la maison d&egrave;s qu'elle est \"hors d'eau\"</p>\n";
	//
		// Reduire les couts
		$body .= getTitle("R&eacute;duire les co&ucirc;ts");
		$body .= "<div>\n";

		$body .= "<p>Il est toujours difficile de faire co&iuml;ncider les d&eacute;sirs avec le budget...</p>\n";

		$body .= "<ul>\n";

		$body .= "<li>Utiliser des mat&eacute;riaux moins cher et des finitions plus simples.\n";
		$body .= "<b>MAIS</b> pas pour tout! Certaines choses m&eacute;ritent la meilleure qualit&eacute; (robinets, parquet...).</li>\n";

		$body .= "<li>Reporter (voire renoncer) $aa certaines choses pas essentielles (chemin goudronn&eacute;, mur de sout&egrave;nement en pierres...).\n";
		$body .= "<b>MAIS</b> pr&eacute;voir les futurs travaux:\n";
		$body .= "<ul>\n";
		$body .= lili("si terrasse plus tard, aplanir, tasser, c&acirc;bler");
		$body .= lili("pr&eacute;voir la tuyauterie et le lieu pour un aspirateur central avec un une sortie dans chaque pi&egrave;ce et une $aa l'ext&eacute;rieur (par ex. garage)");
		$body .= lili("pour une piscine, prendre une pompe $aa chaleur qui peut chauffer l'eau");
		$body .= lili("si installation &eacute;lectrique sur la terrasse (store), pr&eacute;voir les tubes pour les c&acirc;bles");
		$body .= "</ul>\n";
		$body .= "</li>\n";

		$body .= "<li>Utiliser des &eacute;l&eacute;ments d'occasion (en bon &eacute;tat d'immeubles d&eacute;molis) ";
		$body .= getLink("http://bauteilclick.com") . "</li>\n";

		$body .= lili("jardin/am&eacute;nagements ext&eacute;rieurs simples");
		$body .= lili("faire soi-m&ecirc;me certains travaux");

		$body .= "<li>chauffage en leasing (15 ans par mensualit&eacute;s).\n";
		$body .= "S'il casse, il est remplac&eacute;.\n";
		$body .= "Il est possible de le racheter avant la fin du leasing.\n";
		$body .= "Mais ce n'est pas une pratique courante.</li>\n";

		$body .= lili("Eviter le luxe: sauna/jaccuzzi 10$kchf utilis&eacute; qu'au d&eacute;but; piscine 50$kchf, entretien cher");
		$body .= lili("Bourse aux mat&eacute;riaux d'excavations " . getLink("http://vd.ch/boum"));

		$body .= "</ul>\n";

		$body .= "</div>\n";
	//
		// De l'avant-projet au permis de construire
		$body .= getTitle("De l'avant-projet au permis de construire");
		$body .= "<div>\n";
		$body .= "<ol>\n";

		$body .= "<li><b>Pr&eacute;-&eacute;tude et avant-projet</b><br/>\n";
		$body .= "Avant-projet avec plusieurs propositions d'esquises.\n";
		$body .= "Quand les choix sont faits, on peut avoir un devis estimatif.\n";
		$body .= "</li>\n";

		$body .= "<li><b>Projet d&eacute;finitif</b><br/>\n";
		$body .= "D&eacute;tails et plans d&eacute;finitifs, et plan financier.\n";
		$body .= "Prend du temps (pour faire co&iuml;ncider les plans avec le budget).\n";
		$body .= "</li>\n";

		$body .= "<li><b>Proc&eacute;dure d'autorisation de construire</b><br/>\n";
		$body .= "L'architecte fournit aux autorit&eacute;s: les plans d&eacute;taill&eacute;s, les documents, les calculs.\n";
		$body .= "Pose des gabarits.\n";
		$body .= "</li>\n";

		$body .= "<li><b>Mise $aa l'enqu&ecirc;te</b><br/>\n";
		$body .= "Apr&egrave;s que les autorit&eacute;s aient accept&eacute;, le projet est mis $aa l'enqu&ecirc;te\n";
		$body .= "(feuille d'avis officiels et pilier public de la commune).\n";
		$body .= "Il y a ensuite un d&eacute;lai de 14-30 jours pour r&eacute;colter les oppositions.\n";
		$body .= "Souvent un voisin se plaindra pour la vue ou l'ombre.\n";
		$body .= "Pour &eacute;viter cette situation, il serait pr&eacute;f&eacute;rable d'aller rencontrer les voisins avant le projet d&eacute;finitif,\n";
		$body .= "d'&eacute;couter leurs r&eacute;ticences.\n";
		$body .= "Peut-&ecirc;tre qu'il est possible de faire de petites adaptations qui r&eacute;duisent les frustrations et m&egrave;nent $aa une meilleure entente.\n";
		$body .= "Au final, si les r&egrave;glements sont respect&eacute;s, les oppositions sont &eacute;cart&eacute;es et le permis de construire est d&eacute;livr&eacute;.\n";
		$body .= "</li>\n";

		$body .= lili("<b>D&eacute;but des travaux</b>");

		$body .= "<li><b>Remise des cl&eacute;s et d&eacute;compte final</b><br/>\n";
		$body .= "L'objet est remis au $proprio.\n";
		$body .= "Celui-ci doit faire un contr&ocirc;le d&eacute;taill&eacute; pour les d&eacute;fauts, probl&egrave;mes, oublis.\n";
		$body .= "Quand les travaux sont finis et toutes les factures re&ccedil;ues, on peut faire le d&eacute;compte final.\n";
		$body .= "</li>\n";

		$body .= "</ol>\n";
		$body .= "</div>\n";
	//
		// Assurances
		$body .= getTitle("Assurances", 3, "ConstruireExecution");
		$body .= "<div>\n";

		$body .= "<p>Les co&ucirc;ts varient selon les risques (distance au b&acirc;timent le plus proche, pente du terrain, nappe phr&eacute;atique...).\n";
		$body .= "Il faut les faire avant le d&eacute;but des travaux.</p>\n";

		$body .= "<ul>\n";

		$body .= "<li><b>RC ma&icirc;tre d'ouvrage:</b>\n";
		$body .= "facultatif, vivement recommand&eacute;e.\n";
		$body .= "Couvre les dommages aux tiers: glissement de terrain, tassement de fondations...\n";
		$body .= "Prime calcul&eacute;e sur 0.05-0.10% du volume de la construction.</li>\n";

		$body .= "<li><b>Assurance travaux de construction:</b>\n";
		$body .= "facultative mais recommand&eacute;e.\n";
		$body .= "Couvre les accidents d'erreurs humaines hors RC (par ex. pompe pour s&eacute;cher le sous-sol en panne, il faut refaire le carrelage 100$kchf).\n";
		$body .= "Aussi le vandalsime et le vol d'objets fix&eacute;s (robinets).\n";
		$body .= "Prime calcul&eacute;e sur 0.15-0.25% du volume de la construction.</li>\n";

		$body .= "<li><b>Assurance du&eacute;e de construction:</b>\n";
		$body .= "obligatoire sauf GE, VS, TI.\n";
		$body .= "Couvre les incendies et les dommages naturels.\n";
		$body .= "Prime calcul&eacute;e sur 0.03-0.10% de la valeur du b&acirc;timent.</li>\n";

		$body .= "</ul>\n";

		$body .= "</div>\n";
	//
		// Reception et etat des lieux
		$body .= getTitle("R&eacute;ception et &eacute;tat des lieux");

		$body .= "<p>Si on d&eacute;couvre un probl&egrave;me, il faut imm&eacute;diatement le signaler par lettre recommand&eacute;e a l'entrepreneur.\n";
		$body .= "Les d&eacute;fauts cach&eacute;s peuvent &ecirc;tre annonc&eacute;s jusqu'$aa 5 ans apr&egrave;s, 10 ans si l'entrepreneur a intentionnellement abus&eacute;.\n";
		$body .= "Il faut tout rev&eacute;rifier (peinture, porte y compris orientation, joints, r&eacute;glages des fen&ecirc;tres...).\n";
		$body .= "On peut mandater un expert.\n";
		$body .= "Les nettoyages sont inclus dans le prix total, $aa faire par des pros!\n";
		$body .= "V&eacute;rifier les nettoyages avant d'emm&eacute;nager, si besoin r&eacute;clamer et rappeler les nettoyeurs.</p>\n";
	//
		// Defaut d'ouvrage
		$body .= getTitle("D&eacute;faut d'ouvrage");
		$body .= "<div>\n";

		$body .= "<p>Cela signifie que l'ouvrage livr&eacute; ne correspond pas au contrat.</p>\n";

		$body .= "<ul>\n";
		$body .= lili("<b>D&eacute;faut apparent:</b> imm&eacute;diatement d&eacute;couvert $aa r&eacute;ception, ou aurait d&ucirc; &ecirc;tre d&eacute;couvert imm&eacute;diatement.");
		$body .= lili("<b>D&eacute;faut cach&eacute;:</b> ne peut &ecirc;tre d&eacute;couvert que plus tard.");
		$body .= "</ul>\n";

		$body .= "<p>Le Code des Obligations dit que le $proprio doit prouver le d&eacute;faut, ce qui peut &ecirc;tre parfois difficile et/ou co&ucirc;teux.\n";
		$body .= "Si le contrat est avec la norme SIA118, c'est $aa l'entreprise de prouver qu'il n'y a pas de d&eacute;faut.</p>\n";

		$body .= "<p>Si le d&eacute;faut est av&eacute;r&eacute;, il y a 2 possibilit&eacute;s:</p>\n";
		$body .= "<ul>\n";
		$body .= lili("le prix peut &ecirc;tre diminu&eacute;");
		$body .= lili("l'entreprise corrige le d&eacute;faut, mais seulement si c'est possible sans frais exag&eacute;r&eacute;s");
		$body .= "</ul>\n";

		$body .= "<p>Si l'ouvrage est vraiment inhabitable, le $proprio peut refuser de prendre possession.\n";
		$body .= "L'entrepreneur doit alors rembourser.</p>\n";

		$body .= "</div>\n";
	//
		// Decompte final
		$body .= getTitle("D&eacute;compte final");

		$body .= "<p>A contr&ocirc;ler de pr&egrave;s!!!\n";
		$body .= "Bien v&eacute;rifier que les plus-/moins-values des travaux y figurent toutes.\n";
		$body .= "Puis vient la consolidation; pas $aa l'emm&eacute;nagement, mais quand tous les documents exig&eacute;s par la banque sont r&eacute;unis.</p>\n";
//
	// Contrats et proprietes
	$body .= getTitle("Contrats et propri&eacute;t&eacute;s", 2);
	$body .= "<div>\n";
	$body .= "<ul>\n";

	$body .= "<li><b>Protocole d'acccord/r&eacute;servation</b><br/>\n";
	$body .= "Ce n'est ni un acte nontari&eacute;, ni un contrat l&eacute;gal.\n";
	$body .= "Convention &eacute;crite sign&eacute;e par les 2 parties, elle fixe les volont&eacute;s.\n";
	$body .= "Elle s'accompagne d'un premier versement modeste.\n";
	$body .= "Mais elle n'apporte aucune garantie pour les 2 parties.\n";
	$body .= "</li>\n";

	$body .= "<li><b>Promesse d'achat/de vente</b><br/>\n";
	$body .= "Tr&egrave;s courante.\n";
	$body .= "Tous les d&eacute;tails ne sont pas encore r&eacute;gl&eacute;s.\n";
	$body .= "On convient d'un contrat futur, mais la promesse n'implique pas de transfert de la propri&eacute;t&eacute;.\n";
	$body .= "Le bien peut quand m&ecirc;me &ecirc;tre vendu $aa une tierce personne avant d'avoir conclu le contrat;\n";
	$body .= "dans ce cas, l'acheteur de la promesse re&ccedil;oit des dommages-int&eacute;r&ecirc;ts.\n";
	$body .= "Avec la promesse, il y a un versement de 10%.\n";
	$body .= "Si une des parties se r&eacute;tracte, le juge peut l'obliger $aa tenir ses engagements.\n";
	$body .= "</li>\n";

	$body .= "<li><b>Droit de pr&eacute;emption</b><br/>\n";
	$body .= "C'est le droit d'acheter en priorit&eacute; en cas de vente.\n";
	$body .= "<ul>\n";

	$body .= lili("l&eacute;gal (pas de contrat): par ex. descendants, fr&egrave;res/soeurs, paysans, co$proprio");

	$body .= "<li>contractuel: par un pacte de pr&eacute;emption avec un notaire.\n";
	$body .= "Le contrat a une dur&eacute;e maximum de 25 ans.\n";
	$body .= "Si le droit de pr&eacute;emption est inscrit au $RF, il est transmis en cas de revente\n";
	$body .= "(lors de la vente suivante, le droit de pr&eacute;emption sera encore valable).\n";
	$body .= "Pas pour une PPE, sauf s'il est explicitement inscrit dans l'acte constitutif ou dans le r&egrave;glement d'administration et d'utilisation.\n";
	$body .= "</li>\n";

	$body .= "</ul>\n";
	$body .= "</li>\n";

	$body .= "<li><b>Droit d'emption</b><br/>\n";
	$body .= "Il s'agit d'un achat dans des conditions et dans des d&eacute;lais fix&eacute;s (max 10 ans).\n";
	$body .= "S'il est inscrit au $RF, il assure que le vendeur ne vende pas l'objet $aa un tiers.\n";
	$body .= "C'est utile si la banque n'est pas encore pr&ecirc;te.\n";
	$body .= "Il est transmis avec la succession.\n";
	$body .= "</li>\n";

	$body .= "<li><b>Droit de r&eacute;m&eacute;r&eacute;</b>\n";
	$body .= "Le vendeur a la droit de racheter le bien $aa un moment+prix fix&eacute;s dans le contrat.\n";
	$body .= "Max 25 ans, on peut l'inscrire au $RF.\n";
	$body .= "</li>\n";

	$body .= "</ul>\n";
	$body .= "</div>\n";
	//
		// Notaires
		$body .= getTitle("Notaires");
		$body .= "<div>\n";

		$body .= "<p>Il r&eacute;dige le contrat, r&egrave;gle les d&eacute;tails administratifs ($RF, droits de mutation), la c&eacute;dule.\n";
		$body .= "C'est $aa lui qu'est vers&eacute;e une &eacute;ventuelle avance sur le prix d'acaht ou le montant pour la r&eacute;servation.\n";
		$body .= "Il assure le respect des int&eacute;r&ecirc;ts de l'acqu&eacute;reur et du vendeur et les informe de leurs droits et obligations.</p>\n";

		$body .= "<p>Si le promoteur/agent immobilier propose son notaire, il est possible qu'il ne soit pas totalement impartial (sans ill&eacute;galit&eacute;).\n";
		$body .= "Par exemple il pourrait oublier de rendre attentif $aa certains d&eacute;tails, ou r&eacute;diger des clauses en faveur du vendeur.\n";
		$body .= "Dans ce cas, il faut demander $aa choisir le notaire.\n";
		$body .= "En cas de refus, faire examiner le contrat par un juriste.</p>\n";

		$body .= "<p>Si un probl&egrave;me dans la r&eacute;daction du contrat entra&icirc;ne des dommages, il faut d&eacute;poser une plainte.</p>\n";

		$body .= "<p>La signature se fait en pr&eacute;sence des 3 parties, apr&egrave;s que le notaire l'ai enti&egrave;rement relu $aa tous.</p>\n";

		$body .= "<p>Les frais sont tr&egrave;s variables selon la r&eacute;gion.\n";
		$body .= "Il est conseill&eacute; de discuter avec des ams pour savoir si untel est correct/cher...</p>\n";

		$body .= "<p>Composition des frais:</p>\n";
		$body .= "<ul>\n";
		$body .= lili("Acte de vente: droits de mutation, inscription au $RF, r&eacute;mun&eacute;ration et frais.");
		$body .= lili("Acte hypoth&eacute;quaire: inscription au $RF, r&eacute;mun&eacute;ration.");
		$body .= "</ul>\n";

		$body .= "<p>Pour un bien existant, le contrat sp&eacute;cifie clairement:</p>\n";
		$body .= "<ul>\n";
		$body .= lili("la date de changement de $proprio");
		$body .= lili("l'&eacute;tat $aa la remise (propre et vide)");

		$body .= "<li>la reprise/r&eacute;siliation des assurances:\n";
		$body .= "la vente met fin aux assurances,\n";
		$body .= "mais il est possible qu'elles soient soumises $aa une r&eacute;siliation sous 14 jours\n";
		$body .= "sans quoi elles continuentn $aa la charge de l'ancien $proprio.</li>\n";

		$body .= lili("les r&eacute;serves &eacute;ventuelles en cas de d&eacute;chets/pollutions anciens");
		$body .= lili("la participation actuelle au fonds de r&eacute;novation de la PPE");

		$body .= "</ul>\n";

		$body .= "</div>\n";
	//
		// Paiement
		$body .= getTitle("Paiement");

		$body .= "<p>Quand le contrat est conclu et le changement de $proprio est inscrit au $RF, vient le paiement.\n";
		$body .= "MAIS on a besoin d'une preuve que l'acheteur est capable de payer:\n";
		$body .= "il faut une attestation de la banque qui garantit le verssement apr&egrave;s l'inscription au $RF.\n";
		$body .= "Les modalit&eacute;s de paiement sont &eacute;crites dans le contrat.\n";
		$body .= "Si on verse une partie au noir (par ex. contre une r&eacute;duction) et que ce versement est d&eacute;couvert,\n";
		$body .= "le contrat est annul&eacute;!</p>\n";
	//
		// RF
		$body .= getTitle("Registre foncier");
		$body .= "<div>\n";

		$body .= "<p>Le notaire fait l'inscription et le paiement.</p>\n";

		$body .= "<p>Il est en libre acc&egrave;s pour consultation:\n";
		$body .= "coordonn&eacute;es des ${proprio}s, servitudes, droits de gage immobili&egrave;re, date d'achat, adresse compl&egrave;te...</p>\n";

		$body .= "<p>Les documents qui composent une entr&eacute;e du $RF (un feuillet) sont:</p>\n";
		$body .= "<ul>\n";
		$body .= lili("journal: r&eacute;quisitions d'op&eacute;rations inscrites");
		$body .= lili("grand livre: ensemble des feuillets");
		$body .= lili("plan");
		$body .= lili("contrats: achat, servitudes");
		$body .= lili("registres accessoires: ${proprio}s, cr&eacute;anciers...");
		$body .= "</ul>\n";

		$body .= "</div>\n";
	//
		// Formes de proriete
		$body .= getTitle("Formes de propri&eacute;t&eacute;");
		$body .= "<div>\n";
		$body .= "<ul>\n";

		$body .= lili("Individuelle: tout seul, mais le conjoint officiel a l&eacute;gallement des droits.");

		$body .= "<li>Commune: co${proprio}s avec des liens ant&eacute;rieurs (conjoints, h&eacute;ritiers).\n";
		$body .= "Les d&eacute;cisions se prennent ensemble, on ne peut pas disposer librement de sa \"part\".\n";
		$body .= "Le bien appartient $aa tous ind&eacute;pendamment des sommes investies.</li>\n";

		$body .= "<li>Copropri&eacute;t&eacute;: chacun garde son capital, la quote-part est inscrite dans le $RF.\n";
		$body .= "Si un vend, les autres ont un droit de pr&eacute;emption.</li>\n";

		$body .= "<li>Participation $aa une soci&eacute;t&eacute; immobili&egrave;re (SI):\n";
		$body .= "l'immeuble est la propri&eacute;t&eacute; de la SI (elle g&egrave;re les achats/ventes et la gestion d'immeubles).\n";
		$body .= "Les actionnaires ne sont pas ${proprio}s, mais ont des droits de participation et touchent des dividendes.\n";
		$body .= "Une SI d'actionnaires locataires (SIAL) fait la location des appartements aux actionnaires.\n";
		$body .= "Une SIAL-PPE est coupl&eacute;e aux r&egrave;gles de la PPE, est $proprio de toutes les parts de la PPE et loue aux actionnaires.\n";
		$body .= "</li>\n";

		$body .= "</ul>\n";
		$body .= "</div>\n";
	//
		// PPE
		$body .= getTitle("PPE");
		$body .= "<div>\n";

		$body .= "<p>Il s'agit d'une copropri&eacute;t&eacute; o&ugrave; chacun a droit $aa certaines parties.\n";
		$body .= "Chaque $proprio poss&egrave;de des pour-cent/-mille (selon la surface) qui d&eacute;terminent le nombre\n";
		$body .= "de voix pour l'assembl&eacute;e et les parts dans les frais communs.</p>\n";

		$body .= "<p>Avoir des parts dans une PPE donne les droits $aa:</p>\n";
		$body .= "<ul>\n";
		$body .= lili("une quote-part de l'immeuble entier");
		$body .= lili("une part priv&eacute;e");
		$body .= "</ul>\n";

		$body .= "<p>L'administrateur (ex&eacute;cutif) g&egrave;re:</p>\n";
		$body .= "<ul>\n";
		$body .= lili("les travaux d'entretien");
		$body .= lili("l'ex&eacute;cution des d&eacute;cisions de l'assembl&eacute;e");
		$body .= lili("la r&eacute;partition des charges communes, la facturation");
		$body .= lili("le budget suivant");
		$body .= lili("l'utilisation des moyens financiers");
		$body .= lili("les droits sp&eacute;ciaux, l'utilisation des communs");
		$body .= "</ul>\n";

		$body .= "<p>Il est souvent membre de l'entreprise de consrtuction ou de la g&eacute;rance.\n";
		$body .= "Ca pourrait &ecirc;tre un des co${proprio}s mais il y a un risque de conflit d'int&eacute;r&ecirc;t.</p>\n";

		$body .= "<p>L'assembl&eacute;e (organe supr&ecirc;me) g&egrave;re ce que l'administrateur ne fait pas:</p>\n";
		$body .= "<ul>\n";
		$body .= lili("&eacute;lection/d&eacute;mission et contr&ocirc;le de l'administrateur");
		$body .= lili("choix du r&eacute;viseur des comptes");
		$body .= lili("approbation des comptes et du budget de r&eacute;partition des charges");
		$body .= lili("fixation des montants pour les fonds de r&eacute;novation et la r&eacute;partition des co&ucirc;ts");
		$body .= lili("&eacute;laboration/changements de r&egrave;glements");
		$body .= lili("d&eacute;cisions pour les travaux der&eacute;novations/transformations");
		$body .= lili("d&eacute;cisions administrative hors du cadre de l'administrateur");
		$body .= "</ul>\n";

		$body .= "</div>\n";

			// Fond de renovation
			$body .= getTitle("Fonds de r&eacute;novation", 4);

			$body .= "<p>Pas obligatoire, mais vivement recommand&eacute;.\n";
			$body .= "A v&eacute;rifier avant achat, aussi comment il est g&eacute;r&eacute; (s&eacute;curit&eacute; et disponibilit&eacute;).\n";
			$body .= "L'assembl&eacute;e fixe les versements, en g&eacute;n&eacute;ral 0.2% de la valeur de l'assurance incendie.\n";
			$body .= "Si un $proprio vend sa part, la participation aux fonds de r&eacute;novation n'est pas transf&eacute;r&eacute;e $aa l'acheteur.</p>\n";
		//
			// Charges
			$body .= getTitle("Charges", 4);
			$body .= "<div>\n";
			$body .= "<ul>\n";
			$body .= lili("frais d'entretiens/r&eacute;parations");
			$body .= lili("concierge");
			$body .= lili("eau, &eacute;lectricit&eacute;, chauffage");
			$body .= lili("assurances");
			$body .= lili("frais g&eacute;n&eacute;raux");
			$body .= lili("honoraires de l'administrateur");
			$body .= "</ul>\n";
			$body .= "</div>\n";
		//
			// Reglement
			$body .= getTitle("R&egrave;glement", 4);

			$body .= "<p>Concerne la vien en commun.\n";
			$body .= "Certaines dispositions l&eacute;gales, mais possible de changer la plupart.</p>\n";
		//
			// Contenu du contrat d'achat
			$body .= getTitle("Contenu du contrat d'achat", 4);
			$body .= "<div>\n";
			$body .= "<ul>\n";
			$body .= lili("r&egrave;glement de la communeaut&eacute; de co{$proprio}s");
			$body .= lili("r&egrave;glement d'utilisation et d'administration");
			$body .= lili("quote-part");
			$body .= "</ul>\n";
			$body .= "</div>\n";
		//
			// Check-list avant achat
			$body .= getTitle("Check-list avant l'achat", 4);
			$body .= "<div>\n";
			$body .= "<ul>\n";

			$body .= lili("Que comprend le lot en vente, quels sont les communs?");
			$body .= lili("Conditions pour communs? Utilisation par parents, amis, etc?");
			$body .= lili("But? (habitat, bureau, atelier, cabinet...)");
			$body .= lili("Possibilit&eacute; de sous-louer?");
			$body .= lili("Interdictions des professions immorales?");
			$body .= lili("R&egrave;glement pour les loisirs incommodants? (bricolage, musique...)");
			$body .= lili("Animaux admis? Place ext&eacute;rieure?");
			$body .= lili("R&egrave;glement pour la d&eacute;coration des balcons?");
			$body .= lili("Possible de remplacer la moquette par du parquet? (transmission du bruit)");

			$body .= "</ul>\n";
			$body .= "</div>\n";
	//
		// Couple et succession
		$body .= getTitle("Couple et succession");

		$body .= "<p>Testament/pacte successoral, sinon les droits de successions s'appliquent (50% conjoint, 50% enfants).\n";
		$body .= "Le testament peut modifier les parts, par exemple min 50% conjoint, ou 75% enfants...</p>\n";
//
	// Charges et impots
	$body .= getTitle("Charges et imp&ocirc;ts", 2);

		// Charges en un coup d'oeil
		$body .= getTitle("Les charges en un coup d'oeil");
		$body .= "<div>\n";

		$body .= "<p>Voici une petite liste de charges qu'un $proprio doit assumer:</p>\n";
		$body .= "<ul>\n";
		$body .= lili("int&eacute;r&ecirc;ts hypot&eacute;caires");
		$body .= lili("amortissement de l'$hyp");
		$body .= lili("abonnement TV, internet, t&eacute;l&eacute;phone");
		$body .= lili("Chauffage et eau chaude");
		$body .= lili("Eau");
		$body .= lili("Electricit&eacute;");
		$body .= lili("Jardin: entretien, tondeuse, semis...");
		$body .= lili("Ramoneur");
		$body .= lili("Entretien de la chaudi&egrave;re");
		$body .= lili("Entretien du br&ucirc;leur et de la citerne");
		$body .= lili("Taxe des eaux us&eacute;es");
		$body .= lili("Taxe d&eacute;chets");
		$body .= lili("Assurances d&eacute;g&acirc;ts d'eau");
		$body .= lili("Assurance b&acirc;timent");
		$body .= lili("Assurance bris de galce");
		$body .= lili("Assurance RC");
		$body .= lili("Autres assurances...");
		$body .= lili("Imp&ocirc;t foncier");
		$body .= "<li>PPE:\n";
		$body .= "<ul>\n";
		$body .= lili("Conciergerie");
		$body .= lili("Ascenceur");
		$body .= lili("Fonds de r&eacute;novation");
		$body .= lili("Frais d'administration");
		$body .= "</ul></li>\n";
		$body .= "</ul>\n";

		$body .= "</div>\n";
	//
		// Assurances
		$body .= getTitle("Assurances", 3, "ChargesEtImpots");
		$body .= "<div>\n";
		$body .= "<ul>\n";

		$body .= "<li><b>Incendie/&eacute;l&eacute;ments naturels:</b>\n";
		$body .= "<ul>\n";
		$body .= lili("immeuble: assure le b&acirc;timent, obligatoire dans la plupart des cantons");
		$body .= lili("loyer/jardin/etc: compl&eacute;mentaires pour d&eacute;g&acirc;ts jardin, ou pour frais de logement provisoire pendant des travaux cons&eacute;cutifs aux d&eacute;g&acirc;ts");
		$body .= "</ul>\n";

		$body .= "<li><b>d&eacute;g&acirc;ts d'eau:</b>\n";
		$body .= "tout d&eacute;g&acirc;t d'eau sauf naturel (gel dans les canalisations, r&eacute;parations, fuites, &eacute;go&ucirc;ts, toit, mazout).\n";
		$body .= "{$pasoblvivrec}e.\n";
		$body .= "Certains choix augmentent la prime (toit plat, chauffage au sol, g&eacute;othermie...).\n";
		$body .= "</li>\n";

		$body .= lili("<b>RC priv&eacute;e:</b> dommages $aa autrui. Pas n&eacute;cessaire si RC b&acirc;timent. {$pasoblvivrec}e.");
		$body .= lili("<b>RC b&acirc;timent:</b> pas n&eacute;cessaire si vit dans sa propre maison ou si PPE max 3 logements");

		$body .= "<li><b>Bris de glace:</b>\n";
		$body .= "glaces, compl&eacute;mentaires pour lavabos, WC...\n";
		$body .= "Couvert par assurance m&eacute;nage si on habite le logement soi-m&ecirc;me, n&eacute;cessaire seulement si le bien est lou&eacute;.\n";
		$body .= "</li>\n";

		$body .= "<li><b>M&eacute;nage:</b>\n";
		$body .= "vol, dommages aux objets (aussi caus&eacute;s par incendie/&eacute;l&eacute;ments naturels).\n";
		$body .= "{$pasoblvivrec}e.\n";
		$body .= "Si on poss&egrave;de des objets de valeurs, conserver pr&eacute;cieusement les factures et des photos!\n";
		$body .= "</li>\n";

		$body .= "<li><b>Vol:</b>\n";
		$body .= "d&eacute;g&acirc;ts au b&acirc;timent en cas de vol.\n";
		$body .= "Couvert par l'assurance m&eacute;nage si on habite le logement soi-m&ecirc;me, n&eacute;cessaire seulement si le bien est lou&eacute;.\n";
		$body .= "<li>\n";

		$body .= "<li><b>Protection juridique:</b>\n";
		$body .= "le $proprio peut vite avoir des litiges et la justice co&ucirc;te cher.\n";
		$body .= "Cette assurance est donc tr&egrave;s recommand&eacute;e!\n";
		$body .= "Les assurances proposent des protections juridiques sp&eacute;ciales pour $proprio.\n";
		$body .= "Comparer plusieurs offres.\n";
		$body .= "</li>\n";

		$body .= lili("<b>Tremblement de terre:</b> les assurances propos&eacute;es en Suisse (2015) ne sont pas terribles, comparer les offres disponibles.");
		$body .= lili("<b>APG:</b> faite par l'employeur");
		$body .= lili("<b>Assurance vie:</b> pour que les proches touchent un revenu en cas de d&eacute;c&egrave;s");

		$body .= "</ul>\n";
		$body .= "</div>\n";
	//
		// Entretien (frais+provisions)
		$body .= getTitle("Entretien (frais et provisions)");
		$body .= "<div>\n";

		$body .= "<p>Un petit entretien r&eacute;gulier (m&ecirc;me si le bien est neuf) est mieux qu'un gros entretien ponctuel.\n";
		$body .= "Un gros entretien risque de co&ucirc;ter trop cher, donc on ne peut pas rester dans le bien et on doit le revendre pour moins cher.\n";
		$body .= "La banque fait aussi des &eacute;valuations des biens.\n";
		$body .= "S'il est en mauvais &eacute;tat, elle le d&eacute;value et les conditions d'$hyp sont durcies (par ex. augmentation de l'amortissement).</p>\n";

		$body .= "<p>L'entretien compte annuellement pour environ 1% du prix du bien r&eacute;partis comme:</p>\n";
		$body .= "<ul>\n";
		$body .= lili("0.7% pour l'entretien courant");
		$body .= lili("0.3% pour les provisions des gros entretiens");
		$body .= "</ul>\n";

		$body .= "<p>Ces valeurs ne correspondent qu'$agrave; un entretien pour garantir l'&eacute;tat du bien, pas pour l'am&eacute;liorer!</p>\n";

		$body .= "<p>On peut planifier les frais d'entretien de 2 mani&egrave;res:</p>\n";
		$body .= "<ul>\n";
		$body .= lili("&eacute;pargner 1%");
		$body .= lili("&eacute;pargner 0.7% et amortir 0.3%");
		$body .= "</ul>\n";
		$body .= "<p>La 2e alternative r&eacute;duit les charges; quand on veut faire des gros travaux, on peut augmenter l'$hyp.\n";
		$body .= "Mais pas toutes les banques proposent cette solution.\n";
		$body .= "Attention lors d'augmentation de l'$hyp: la banque r&eacute;&eacute;value le bien et le $proprio.\n";
		$body .= "Il est donc pr&eacute;f&eacute;rable de demander une &eacute;valuation $aa un expert auparavant, car on pourrait se retrouver dans une situation o&ucirc;\n";
		$body .= "la banque demande plus pour les prestations d'origine!</p>\n";

		$body .= "<p>Quand on veut faire des travaux, il est bien de demander $aa un architecte/entrepreneur de faire un $eacute;tat du bien,\n";
		$body .= "car il est peut-&ecirc;tre n&eacute;cessaire de faire d'autres travaux.\n";
		$body .= "Il est peut-&ecirc;tre financi&egrave;rement int&eacute;ressant de grouper les travaux.\n";
		$body .= "<b>Il est tr&egrave;s important de bien planifier les travaux.</b>\n";
		$body .= "Si la vente du bien est planifi&eacute;e, il faut r&eacute;fl&eacute;chir aux travaux qui apportent une plus-value:\n";
		$body .= "la cuisine ou la salle de bains ne valent pas la peine, mais am&eacute;nager les combles est int&eacute;ressant.</p>\n";

		$body .= "</div>\n";
	//
		// Impots
		$body .= getTitle("Imp&ocirc;ts");

		$body .= "<p>Les int&eacute;r&ecirc;ts du cr&eacute;dit de construction ne sont pas d&eacute;ductibles (sauf VS+BE).</p>\n";

		$body .= "<p>Imp&ocirc;t foncier: pour utilisation du territoire, calcul&eacute; sur la valeur du bien (sans d&eacute;duire l'$hyp)</p>\n";

		$body .= "<p>Valeur locative: $aa calculer chaque ann&eacute;e pour la d&eacute;claration d'imp&ocirc;ts (si on habite le bien).\n";
		$body .= "La 1&egrave;re fois le calcul est laborieux, ensuite c'est facile.</p>\n";
	//
		// Deductions
		$body .= getTitle("D&eacute;ductions");

			// Interets hypotecaires
			$body .= getTitle("Int&eacute;r&ecirc;ts hypot&eacute;caires",4);

			$body .= "<p>Pas l'amortissement, seulement les int&eacute;r&ecirc;ts.</p>\n";

			$body .= "<p>Impenses (d&eacute;penses du $proprio): droits de mutation, frais de notaire, courtage, d&eacute;penses faisant une plus-value,\n";
			$body .= "travaux d'utilit&eacute; publique $aa charge du $proprio, (r)achat de servitudes, frais des {$hyp}s...</p>\n";
		//
			// Entretien
			$body .= getTitle("Entretien", 4);

			$body .= "<p>D&eacute;ductible si n&eacute;cessaire, pas pour une plus-value.\n";
			$body .= "Partiellement d&eacute;ductible si n&eacute;cessaire, mais choix fait une plus-value.</p>\n";

			$body .= "<p>On peut choisir les d&eacute;ductions effectives (avec les factures) ou forfaitaires; $aa choix chaque ann&eacute;e.</p>\n";

			$body .= "<p>PPE: on peut d&eacute;duire la quote-part des frais communs, l'imp&ocirc;t foncier, les frais pay&eacute;s directement, les versements au fonds de r&eacute;novation</p>\n";
		//
			// Exploitation
			$body .= getTitle("Exploitation", 4);

			$body .= "<p>Les primes d'assurances choses.</p>\n";

			$body .= "<p>PPE: fonds de r&eacute;novation (FR, GE)</p>\n";
		//
			// Administration
			$body .= getTitle("Administration", 4);

			$body .= "<p>Seulement si le bien est lou&eacute;: g&eacute;rance, pub, agence immobili&egrave;re...</p>\n";
		//
			// Planification
			$body .= getTitle("Planification", 4);

			$body .= "<p>Penser $aa bien planifier les travaux pour &eacute;viter que les d&eacute;ductions ne d&eacute;passent le revenu,\n";
			$body .= "r&eacute;partir les travaux sur plusieurs ann&eacute;es.\n";
			$body .= "Garder en t&ecirc;te que la d&eacute;duction est valable pour l'ann&eacute;e dans laquelle le paiement est effectu&eacute;.</p>\n";
	//
		// Impot sur la fortune immobiliere
		$body .= getTitle("Imp&ocirc;t sur la fortune immobili&egrave;re");

		$body .= "<p>En tant que $proprio, le bien est compt&eacute; dans la fortune, moins l'$hyp et les int&eacute;r&ecirc;ts.</p>\n";
	//
		// Societe immobiliere
		$body .= getTitle("Soci&eacute;t&eacute; immobili&egrave;re");

		$body .= "<p>Pas fiscalement avantageux: la SI est soumise aux imp&ocirc;ts sur le b&eacute;n&eacute;fice et sur le capital;\n";
		$body .= "les actionnaires sont en plus soumis aux imp&ocirc;ts sur le revenu et sur la fortune (dont les actions).</p>\n";

		$body .= "<p>Si la SI est inscrite avec une faible valeur et se vend bien, le gros b&eacute;n&eacute;fice sera impos&eacute;\n";
		$body .= "sur la SI et sur les actionnaires.</p>\n";
	//
		// Achat/vente
		$body .= getTitle("Achat / vente");

		$body .= "<p>Droits de mutation: quand $proprio change, donc l'acheteur paye.</p>\n";

		$body .= "<p>Droits de constitution de la c&eacute;dule: nouveau $proprio paye.</p>\n";

		$body .= "<p>Gain immobilier: le vendeur est impos&eacute; sur le b&eacute;n&eacute;fice.\n";
		$body .= "Attention $aa garder pr&eacute;cieusement les quittances/factures entra&icirc;nant une plus-value.\n";
		$body .= "On peut les d&eacute;duire du b&eacute;n&eacute;fice et l'imp&ocirc;t diminue.\n";
		$body .= "(A v&eacute;rifier avec un sp&eacute;cialiste pour analyser la situation fiscale post-vente.)</p>\n";

		$body .= "<p>Le taux d'imposition est d&eacute;gressif.\n";
		$body .= "Il est possible de diff&eacute;rer le paiement si l'on rach&egrave;te un autre bien pour habiter (remploi).\n";
		$body .= "Lors de la vente du nouveau bien, les dur&eacute;es d'occupation sont alors cumul&eacute;es pour le calcul de l'imp&ocirc;t.</p>\n";
//
	// Experiences
	$body .= getTitle("Exp&eacute;riences partag&eacute;es", 2);
	$body .= "<div>\n";

	$body .= "<ul>\n";

	$body .= "<li>Eviter PostFinance.\n";
	$body .= "Les Banques Cantonales sont s&eacute;rieuses, mais pas forc&eacute;ment comp&eacute;tentes (par ex. trop proche de la gare, valeur de l'$hyp diminue).\n";
	$body .= "Attention aux formulaires internet: ils sous-estiment souvent le bien.\n";
	$body .= "La banque Migros est bien; ils ont la r&eacute;putation d'&ecirc;tre s&eacute;v&egrave;re.\n";
	$body .= "Banque coop bien aussi mais $hyp moins haute.</li>\n";

	$body .= lili("SwissLife bon taux, mais on doit conclure une assurance vie chez eux.");
	$body .= lili("Pour choisir la banque, regarder aussi o&ugrave; sont les bancomat et si Twint est disponible.");
	$body .= lili("Attention aux limites de retrait au moment d'amener les fonds propres!");
	$body .= lili("Comparer les notaires avec les amis.");

	$body .= lili("Faire un plan $aa l'&eacute;chelle avec toutes les pi&egrave;ces y compris les ext&eacute;rieurs");
	$body .= lili("Calculer le co&ucirc;t d'une salle de bain, d'une cuisine: frigo 2$kchf, four 2$kchf, plaques 4$kchf, lave-vaisselle 1$kchf, hotte 1$kchf - total 20$kchf");
	$body .= lili("construire est cher, meilleur march&eacute; de r&eacute;nover");
	$body .= lili("5% cash en plus pour frais. Si pas construit, taxes seulement sur prix du terrain, mais rajouter taxes raccordement...");
	$body .= lili("taux fixe 5ans max? Faire estimation avec limite et marge");
	$body .= lili("Surface de fen&ecirc;tres: 1/8 $aa 1/6");
	$body .= lili("PPE: frais en plus 500-1$kchf");
	$body .= lili("PPE plus: r&eacute;partitions des frais communs (par ex. toit); moins: plusieurs $aa d&eacute;cider, on choisit pas les autres");
	$body .= lili("Penser $aa faire une assurance vie pour que le survivant puisse encore payer le bien");
	$body .= "</ul>\n";

	$body .= "</div>\n";


echo $body;
unset($page);
?>

