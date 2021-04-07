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

function todo() {
	return "<p><b>TODO</b></p>\n";
}

	// Shortcuts
	$aa = "&agrave;";
	$accepte = "accept&eacute;";
	$apres = "apr&egrave;s";
	$arcent = "$arch/$entr";
	$arch = "architecte";
	$batiment = "b&acirc;timent";
	$coute = "co&ucirc;te";
	$credit = "cr&eacute;dit";
	$deces = "d&eacute;c&egrave;s";
	$deductions = "d&eacute;ductions";
	$deductionsF = "$deductions fiscales";
	$defauts = "d&eacute;fauts";
	$different = "diff&eacute;rent";
	$echeance = "&eacute;ch&eacute;ance";
	$electricite = "&eacute;lectricit&eacute;";
	$eleve = "&eacute;lev&eacute;";
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
	$impot = "imp&ocirc;t";
	$indemnites = "indemnit&eacute;s";
	$interets = "int&eacute;r&ecirc;ts";
	$kchf = "'000 CHF";
	$marche = "march&eacute;";  // TODO
	$meme = "m&ecirc;me";
	$mobiliteReduite = "mobilit&eacute; r&eacute;duite";
	$negocier = "n&eacute;gocier";
	$p2 = "2e pilier";
	$p3 = "3e pilier";
	$piece = "pi&egrave;ce";
	$plutot = "plut&ocirc;t";
	$pret = "pr&ecirc;t";
	$prevoyance = "pr&eacute;voyance";
	$probleme = "probl&egrave;me";
	$proprio = "propri&eacute;taire";
	$RF = "Registre Foncier";

function getTitle($title, $level=3) {
	$ascii = $title;
	$ascii = str_replace(" ", "", $ascii);
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

function lili($content, $class="") {
	return "<li class=\"$class\">$content</li>\n";
}

function lilink($url) {
	return lili(getLink($url));
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
$body .= lilink("http://hausinfo.ch");
$body .= lilink("http://ubs.com");
$body .= lilink("http://amiante-info.ch");
$body .= lilink("http://ch-radon.ch");
//$body .= lilink("http://");
$body .= "</ul>\n";
$body .= "</div>\n";


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
	$body .= "L'$hyp ne peut pas $etre plus que le 2/3 du prix du bien apres 15 ans.\n";
	$body .= "Il faut donc amortir une partie de l'hyp.\n";
	$body .= "L'amortissement d&eacute;bute $generalement 1 an $apres avoir re&ccedil;u le $credit et se fait par paiements r&eacute;guliers..</p>\n";

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

		$body .= "<li><b>Assurances:</b> $hyp $generalement taux fixe ou taux variable. Maximum 65-80%. Les fonds propres de $prevoyance ne sont pas forc&eacute;ment {$accepte}s.\n";
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

		$body .= "<p>$generalement une $hyp se fait en 2 parties, appel&eacute;es rang.\n";
		$body .= "Le 1er rang se compose de 65% de la valeur du bien et ne se rembourse (amorti) pas.\n";
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
		$body .= "Taux fixe 1-2 mois ($generalement 3 ou 6) TODO.\n";
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
		$body .= todo();
		$body .= "Il faut souvent payer des $indemnites d'entr&eacute;e et/ou de sortie.</li>\n";

		$body .= "<li>Rabais/bonus: plusieurs possibilit&eacute;s:\n";

		$body .= "<ul>\n";
		$body .= lili("start/USB FirstHome: rabais sur les taux pour une partie du $credit si c'est le premier logement.");
		$body .= lili("Eco/Minergie");
		$body .= lili("Famille");
		$body .= "</ul>\n";

		$body .= "</li>\n";

		$body .= "<li><b>Assurances:</b> $generalement $apres 12 mois de ch&ocirc;mage ou incapacit&eacute; de travail\n";
		$body .= todo();
		$body .= "pour $interets mensuels, amortissement suspendu.\n";
		$body .= "Pas d'examen de sant&eacute;, mais $etre &acirc;g&eacute; au maximum de 54 ans.\n";
		$body .= "Possible assurance $deces risque pur.\n</li>\n";
		$body .= todo();

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

			// Tour horizon
			$body .= getTitle("Tour d'horizon", 4);
			$body .= "<div>\n";

			$body .= "<p>Avant de commencer, il faut pr&eacute;parer un dossier complet et bien pr&eacute;sent&eacute;.\n";
			$body .= "Id&eacute;alement un classeur avec des s&eacute;parations &eacute;tiquet&eacute;es.</p>\n";

			$body .= todo();  // TODO p41 dossier complet  (VZ comparatif???)

			$body .= "<p>Demander des offres (voir d'abord les sites web) $aa:</p>\n";
			$body .= "<ul>\n";
			$body .= lili("une grande banque: UBS, Credit Suisse...");
			$body .= lili("une banque de proximit&eacute;: Raiffeisen...");
			$body .= lili("la banque cantonale du canton et d'un canton voisin");
			$body .= lili("une compagnie d'assurance: Swisslife...");
			$body .= "</ul>\n";

			$body .= "<p>Demander diff&eacute;rentes variantes, donner un d&eacute;lai pour la r&eacute;ception et indiquer contact tel+email.</p>\n";  // TODO delai reception???

			$body .= "</div>\n";
		//
			// Concurrence
			$body .= getTitle("Faire jouer la concurrence", 4);
			$body .= "<p>Etablir une liste comparative d&eacute;taill&eacute;e (anonyme ou pas) de toutes les offres et l'envoyer $aa tous en demandant mieux.</p>\n";
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
			$body .= "C'est le titre qui est &eacute;mis par le $RF et remis au notaire qui le transmet $aa la banque en &eacute;change du $pret.\n";
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
		$body .= getTitle("Logement id&eacute;al");
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
			$body .= getTitle("Bungalow (maison sur 1 $etage", 4);
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
			$body .= "<p>On peut acheter un bien existant, mais suivant l'ann&eacute;e de construction il faut se m&eacute;fier de certains mat&eacute;riaux de construction:</p>\n";
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
			$body .= liliMinus("si des r&eacute;novations sont indispensables, cela peut engendrer des mauvaises surprises au moment des travaux ($electricite, charpente, {$fenetre}s, canalisations) et entra&icirc;ner des co&ucirc;ts {$eleve}s");
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


echo $body;
unset($page);
?>

