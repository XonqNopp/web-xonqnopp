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
	$rf = "Registre Foncier";
	$proprio = "propri&eacute;taire";
	$arch = "architecte";
	$entr = "entrepreneur";
	$arcent = "$arch/$entr";
	$pret = "pr&ecirc;t";
	$credit = "cr&eacute;dit";
	$hyp = "hypoth&egrave;que";
	$hyps = "hypoth&egrave;ques";
	$hypotecaire = "hypot&eacute;caire";
	$interets = "int&eacute;r&ecirc;ts";
	$impot = "imp&ocirc;t";
	$impots = "imp&ocirc;ts";
	$kchf = "'000 CHF";
	$p2 = "2e pilier";
	$p3 = "3e pilier";
	$engeneral = "en g&eacute;n&eacute;ral";
	$generalement = "g&eacute;n&eacute;ralement";
	$meme = "m&ecirc;me";
	$indemnites = "indemnit&eacute;s";
	$marche = "march&eacute;";
	$deductions = "d&eacute;ductions";
	$deductionsF = "$deductions fiscales";
	$etre = "&ecirc;tre";  // TODO

function getTitle($title, $level=3) {
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
	$body .= getTitle("Introduction", 2);

	$body .= "<p>(Rien de notable, mais gard&eacute;e seulement pour la coh&eacute;rence de la num&eacute;rotation des chapitres.)</p>\n";
//
	// Financement
	$body .= getTitle("Financement", 2);
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
	$body .= getTitle("Recherche du financement", 2);

		// 2e pilier
		$body .= getTitle($p2);
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
		$body .= getTitle($p3);
		$body .= "<p>&Agrave; peu pr&egrave;s idem. Nantiseement peut permettre d'augmenter l'$hyp au-del&agrave; des 80%.</p>\n";
	//
		// Emprunter
		$body .= getTitle("Emprunter");
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
		$body .= getTitle("Types d'$hyp");
		$body .= "<div>\n";

		$body .= "<p>$generalement une $hyp se fait en 2 parties, appel&eacute;es rang.\n";
		$body .= "Le 1er rang se compose de 65% de la valeur du bien et ne se rembourse (amorti) pas.\n";
		$body .= "Le 2e rang se compose jusqu'&agrave; 15% de la valeur du bien et s'amorti sous les 15 ans suivant l'achat.</p>\n";

		$body .= "<p>Une $hyp peut se choisir selon plusieurs variantes sur les taux d'$interets.</p>\n";

		$body .= "<ul>\n";

		$body .= "<li><b>Taux fixe:</b> s&eacute;curit&eacute; mais pas bon $marche.\n";
		$body .= "L'$hyp est bloqu&eacute;e $meme si les taux du $marche varient: attention &agrave; anticiper le budget avec le nouveau taux pour le renouvellement.\n";
		$body .= "Si on veut changer l'$hyp avant son &eacute;ch&eacute;ance, de fortes p&eacute;nalit&eacute;s sont &agrave; payer.</li>\n";

		$body .= "<li><b>Taux variable:</b>pas de dur&eacute;e, les 2 parties peuvent r&eacute;silier en tout temps avec 3-6 mois de pr&eacute;avis.\n";
		$body .= "Attention car le choix du taux n'est pas transparent, la banque le choisit elle-$meme...</li>\n";

		$body .= lili("Combi/mixte: un mix de taux fixe et de taux variable.");

		$body .= "<li><b>Libor:</b>London InterBank Offered Rate.\n";
		$body .= "Le taux est fix&eacute; chaque jour ouvrable &agrave; 11h (Londres).\n";
		$body .= "Taux fixe 1-2 mois ($generalement 3 ou 6) TODO.\n";
		$body .= "On peut faire un contrat de 2 &agrave; 5 ans.\n";
		$body .= "Les banques ajoutent une marge de 0.65-1.30% selon le revenu et le $credit, marges figurant sur le contrat.\n";
		$body .= "Si le Libor devient n&eacute;gatif, les banques ne descendent pas en-dessous de (0 + marges).\n";
		$body .= "Cette formule s'adresse &agrave; un public averti qui suit l'&eacute;volution du $marche.\n";
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
		$body .= "Il faut souvent payer des $indemnites d'entr&eacute; et/ou de sortie.</li>\n";

		$body .= "<li>Rabais/bonus: plusieurs possibilit&eacute;s:\n";

		$body .= "<ul>\n";
		$body .= lili("start/USB FirstHome: rabais sur les taux pour une partie du cr&eacute;dit si c'est le premier logement.");
		$body .= lili("Eco/Minergie");
		$body .= lili("Famille");
		$body .= "</ul>\n";

		$body .= "</li>\n";

		$body .= "<li><b>Assurances:</b> g&eacute;n&eacute;ralement apr&egrave;s 12 mois de ch&ocirc;mage ou incapacit&eacute; de travail\n";
		$body .= todo();
		$body .= "pour int&eacute;r&ecirc;ts mensuels, amortissement suspendu.\n";
		$body .= "Pas d'examen de sant&eacute;, mais &ecirc;tre &acirc;g&eacute; au maximum de 54 ans.\n";
		$body .= "Possible assurance d&eacute;c&egrave;s risque pur.\n</li>\n";
		$body .= todo();

		$body .= "<li><b>Forward:</b> si $hyp longue arrive &agrave; terme, possible 'r&eacute;server' les taux 3/6/12 mois avant.\n";
		$body .= "Plus on fait avant, plus c'est cher.</li>\n";

		$body .= "</ul>\n";

		$body .= "</div>\n";

	//
		// Amortissement
		$body .= getTitle("Amortissement");
		$body .= "<div>\n";
		$body .= "<p>La dette doit &ecirc;tre au maximum au 2/3 de la valeur apr&egrave;s 15 ans.\n";
		$body .= "On peut amortir de 2 fa&ccedil;ons:</p>\n";

		$body .= "<ul>\n";

		$body .= "<li><b>Direct:</b> chaque payement diminue la dette (donc les charges).\n";
		$body .= "Id&eacute;al quand on est proche de la retraite ou avec une $hyp &agrave; taux &eacute;lev&eacute;.\n";
		$body .= "Mais les $interets diminuent, donc les $impots augmentent.</li>\n";

		$body .= "<li><b>Indirect:</b> la dette est rembours&eacute;e en fin de contrat.\n";
		$body .= "Les payements sont une forme de police d'amortissement (3a).\n";
		$body .= "Cela a plusieurs avantages financiers:\n";

		$body .= "<ul>\n";
		$body .= lili("$deductionsF des $interets sur le revenu");
		$body .= lili("$deductionsF de la dette sur la fortune");
		$body .= lili("$deductionsF des primes d'amortissements sur le revenu");
		$body .= lili("charges initiales plus basses");
		$body .= lili("couvert en cas de d&eacute;c&egrave;s");
		$body .= lili("exon&eacute;ration des primes en cas d'incapacit&eacute; de travail");
		$body .= "</ul>\n";

		$body .= "Un 3a bancaire est plus avantageux fiscalement, mais un 3a assurance vie est plus s&eacute;curitaire (surtout du point de vue de la banque).\n";
		$body .= "</li>\n";

		$body .= "</ul>\n";

		$body .= "</div>\n";
	//
		// Strategie hyp
		$body .= getTitle("Strat&eacute;gie hypot&eacute;caire");
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

			$body .= "<p>Demander des offres (voir d'abord les sites web) &agrave;:</p>\n";
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
			$body .= "<p>Etablir une liste comparative d&eacute;taill&eacute;e (anonyme ou pas) de toutes les offres et l'envoyer &agrave; tous en demandant mieux.</p>\n";
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
			$body .= "<p>Pour &eacute;viter ces &eacute;tapes, on peut faire appel &agrave; un courtier.\n";
			$body .= "Il est familier des produits et du march&eacute; et va chercher les meilleures offres.\n";
			$body .= "Il accompagne aussi &agrave; la banque pour les n&eacute;gociations finales (et obtient de meilleures conditions car aux yeux de la banque il repr&eacute;sente un certain volume d'affaires).\n";
			$body .= "Il est normalament gratuit sauf en cas d'annulation, mais &agrave; v&eacute;rifier avant de s'engager.</p>\n";
		//
			// Vocabulaire
			$body .= getTitle("Vocabulaire", 4);
			$body .= "<p>Quand la banque accorde le $pret, le notaire cr&eacute;e une <b>c&eacute;dule $hypotecaire</b> exig&eacute;e comme garantie par la banque.\n";
			$body .= "C'est le titre qui est &eacute;mis par le $RF et remis au notaire qui le transmet &agrave; la banque en &eacute;change du $pret.\n";
			$body .= "Quand la c&eacute;dule est lib&eacute;r&eacute;e par la banque, elle peut $etre r&eacute;utilis&eacute;e (travaux, autre bien).</p>\n";
	//
		$body .= getTitle("Renouveler l'$hyp");
		$body .= "<div>\n";
		$body .= "<p>Quand arrive l'&eacute;ch&eacute;ance et qu'il faut renouveler l'$hyp, il s'agit de:</p>\n";
		$body .= "<ul>\n";
		$body .= lili("Etudier l'amortissement");
		$body .= "<li>Demander une nouvelle estimation du bien: certains travaux donnent une plus-value qui permettrait d'obtenir de meilleurs taux.\n";
		$body .= "Attention, selon l'&eacute;volution du march&eacute;, le bien peut aussi perdre de la valeur; ne pas informer la banque si l'estimation est d&eacute;favorable</li>";
		$body .= lili("Faire jouer la concurrence: mentionner le meilleur taux du voisin pour essayer d'avoir un geste");
		$body .= lili("Choisir un long taux fixe seulement si on est certain de ne pas d&eacute;m&eacute;nager ni amortir");
		$body .= "<li>R&eacute;fl&eacute;chir au renouvellement assez t&ocirc;t.\n";
		$body .= "Si la situation est diff&eacute;rente (par ex. baisse de revenu), le renouvellement peut &ecirc;tre refus&eacute;.\n";
		$body .= "Selon la situation, il est pr&eacute;f&eacute;rable de demander conseil &agrave; un expert au pr&eacute;alable.</li>";
		$body .= "</ul>\n";
		$body .= "</div>\n";
	//
		// Credit construction
		$body .= getTitle("Cr&eacute;dit de construction");


echo $body;
unset($page);
?>

