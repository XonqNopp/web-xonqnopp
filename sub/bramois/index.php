<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
// debug
//$page->initHTML();
//$page->LogLevelUp(6);
// CSS paths
$page->CSS_ppJump(2);
$page->CSS_ppWing(2);
// init body
$body = "";


// Set title and hot booty
$body .= $page->SetTitle("Bramois");// before HotBooty
$page->HotBooty();

	// Fumee
	$body .= "<h2>Fum&eacute;e</h2>\n";
	$body .= "<p>Notre appartement est enti&egrave;rement non-fumeur.\n";
	$body .= "Merci de bien vouloir sortir du b&acirc;timent pour fumer.\n";
	$body .= "Nos voisins du 1er sont fumeurs et il n'est pas rare que la cage d'escalier sente la fum&eacute;e apr&agrave;s qu'ils aient ouvert leur porte.\n";
	$body .= "Si cela vous d&eacute;range, n'h&eacute;sitez pas a a&eacute;rer en ouvrant (grand ou imposte) la fen&ecirc;tre du sommet des escaliers.\n";
	$body .= "Pour que ces mauvaises odeurs ne rentrent pas dans notre appartement, merci de ne pas ouvrir la porte plus que de n&eacute;cessaire.</p>\n";
//
	// Parking
	$body .= "<h2>Parking</h2>\n";
	$body .= "<p>On peut parquer sur la grande place devant le b&acirc;timent.\n";
	$body .= "On peut soit se mettre du c&ocirc;t&eacute; de la porte d'entr&eacute;e, soit le long du quai,\n";
	$body .= "soit du c&ocirc;t&eacute; chemin de l'&eacute;cole, m&ecirc;me si la voiture d&eacute;passe des lignes.\n";

	$body .= "<p>La place de parc est partag&eacute;e avec les autres personnes utilisant le b&acirc;timent:</p>\n";
	$body .= "<div><ul>\n";
	$body .= "<li>Les voisins Brigitte et Jean-Pascal au 1er, y compris leurs visiteurs</li>\n";
	$body .= "<li>Le local &agrave; skis au rez, y compris les clients</li>\n";
	$body .= "<li>Les encaveurs au sous-sol</li>\n";
	$body .= "<li>L'imprimerie dans l'annexe, y compris les clients</li>\n";
	$body .= "<li>Ainsi que des amis des diff&eacute;rents propri&eacute;taires de temps &agrave; autre...</li>\n";
	$body .= "</ul></div>\n";

	$body .= "<p>Si possible, on se parquera de mani&egrave;re &agrave; ne pas bloquer les voitures d&eacute;j&agrave; pr&eacute;sentes.\n";
	$body .= "Par exemple, en g&eacute;n&eacute;ral, Brigitte parque sa voiture le long du quai de l'imprimerie au fond.\n";
	$body .= "Pour ne pas la bloquer, nous nous parquons le long du quai devant le local &agrave; skis,\n";
	$body .= "de mani&egrave;re &agrave; ce qu'elle ait la place de manoeuvrer pour sortir.</p>\n";
//
	// Cuisine
	$body .= "<h2>Cuisine</h2>\n";
	$body .= "<p>Le lave-vaisselle s'ouvre quand son programme est fini.\n";
	$body .= "Vous pouvez sans autres le mettre en route juste avant de partir et nous nous occuperons de le vider la prochaine fois que nous venons.</p>\n";
//
	// Salon
	$body .= "<h2>Salon</h2>\n";

	$body .= "<p>Le salon fait aussi office de chambre adultes.\n";
	$body .= "Il y a un canap&eacute; bleu, et un lit gigogne que nous transformons en canap&eacute; pour la journ&eacute;e.\n";
	$body .= "La nuit, nous rangeons les coussins et le couvre-lit dans l'armoir au fond du salon &agrave; droite.\n";
	$body .= "La journ&eacute;e, les oreillers se trouvent dedans.\n";
	$body .= "Pour ceux qui sont sensibles des courants d'air dans le lit, nous avons aussi une sangle pour serrer ensemble les 2 matelas.\n";
	$body .= "</p>\n";

	$body .= "<p>Pour les enfants, l'armoire de gauche contient des livres, de quoi dessiner et des jeux (jouets, lego, jeux de soci&eacute;t&eacute;).\n";
	$body .= "Il y a aussi des BDs dans l'armoire de la cuisine sous l'horloge avec du sable kinetic et de la p&acirc;te &agrave; modeler.\n";
	$body .= "Et il y a encore des BDs dans la 2e armoire depuis la gauche dans la chambre des enfants.</p>\n";
//
	// Velux
	$body .= "<h2>Velux</h2>\n";
	$body .= "<p>Entre ferm&eacute; et ouvert, il y a un cran pour que l'air puisse circuler sans ouvrir le velux.\n";
	$body .= "Il est conseill&eacute; de les laisser les 4 sur ce cran interm&eacute;diaire pour que l'humidit&eacute; puisse sortir.\n";
	$body .= "M&ecirc;me comme &ccedil;a, le matin au r&eacute;veil, le fen&ecirc;tre de la cuisine est souvent d&eacute;goulinante.</p>\n";

	$body .= "<p>On peut les fermer compl&egrave;tement s'il y a beaucoup de vent et que cela cr&eacute;e un courant froid,\n";
	$body .= "ou quand on utilise la ventilation de la cuisine si le courant d&eacute;range.</p>\n";

	$body .= "<p>Quand ils sont fermes ou interm&eacute;diaires, on peut descendre les stores exterieurs\n";
	$body .= "Le velux de la salle de bains a un store int&eacute;rieur.</p>\n";
//
	// Volets et stores
	$body .= "<h2>Volets et stores</h2>\n";
	$body .= "<p>Pour obscurcir les fen&ecirc;tres, il y a un petit store dans celle de la cuisine.\n";
	$body .= "Celle du salon et de la chambre ont des volets.\n";
	$body .= "Pour la chambre des enfants, il y a la possibilit&eacute; de cadenasser les volets avec une cha&icirc;ne\n";
	$body .= "si on veut &ecirc;tre s&ucirc;r que les enfants n'essaient pas de les ouvrir.\n";
	$body .= "La cl&eacute; du cadenas se trouve dans l'armoir &agrave; balais, pos&eacute;e en haut &agrave; gauche.\n";
	$body .= "La fen&ecirc;tre de la chambre a aussi un store int&eacute;rieur pour mieux obscurcir.</p>\n";

	$body .= "<p>Les velux ont des stores &eacute;lectriques.\n";
	$body .= "Il y a des t&eacute;l&eacute;commandes (salon et cuisine dans le tiroir de la cuisine, chambre dans l'armoir a balais au milieu a gauche).</p>\n";

	$body .= "<p>Quand il g&egrave;le ou neige, les stores peuvent rester coinc&eacute;s.\n";
	$body .= "Soit dans la position qu'ils ont (ferm&eacute;s, ouverts ou entre deux), soit il peut s'&ecirc;tre form&eacute; un obstacle qui emp&ecirc;che\n";
	$body .= "de les changer compl&egrave;tement de position.\n";
	$body .= "S'il y a un obstacle et qu'on a essay&eacute; de d&eacute;placer le store, il va buter sur l'obstacle et revenir un peu en arri&egrave;re.\n";
	$body .= "Apr&egrave;s cela, il y aura un petit d&eacute;lai (2-5min) ou les stores ne r&eacute;pondront plus &agrav;e la t&eacute;l&eacute;commande.\n";
	$body .= "</p>\n";

	$body .= "<p>L'appartement &eacute;tant sous le toit, il a une grande surface communiquant avec l'ext&eacute;rieur.\n";
	$body .= "En &eacute;t&eacute;, cela chauffe beaucoup.\n";
	$body .= "Pour &eacute;viter d'accumuler trop de chaleur, notre strat&eacute;gie consiste &agrave; garder les stores des velux ferm&eacute;s\n";
	$body .= "tout le temps que le Soleil &eacute;claire l'int&eacute;rieur.\n";
	$body .= "Quand ce n'est pas le cas (t&ocirc;t le matin et tard le soir), nous les ouvrons pour ouvrir les velux et faire circuler plus d'air.\n";
	$body .= "Nous laissons aussi en imposte 24h/24 les fen&ecirc;tres du salon et de la cuisine pour faire circuler\n";
	$body .= "l'air et permettre de refroidir le toit par l'int&eacute;rieur.\n";
	$body .= "</p>\n";
//
	// Jardin
	$body .= "<h2>Jardin</h2>\n";
	$body .= "<p>Comme pour le parking, le jardin est partag&eacute; avec tous les autres utilisateurs du b&acirc;timents (sauf les clients).\n";
	$body .= "On peut utiliser le bac &agrave; sable, et dans la caisse &agrave; c&ocirc;t&eacute; de la porte, on trouvera aussi\n";
	$body .= "un hamac (pas toujours), une balan&ccedil;oire-corde et quelques jouets.\n";
	$body .= "Pour jouer avec des enfants, il est conseill&eacute; d'enlever les appareils pour &eacute;loigner les chats, et de les remttre quand on quitte le jardin.</p>\n";

	$body .= "<p>Nous avons aussi un petit grill &agrave; gaz qui peut &ecirc;tre utiliser.\n";
	$body .= "La grille est en haut, dans l'armoire en dessus du frigo.</p>\n";
//
	// Lessive
	$body .= "<h2>Lessive</h2>\n";
	$body .= "<p>Une machine &agrave; laver et un s&eacute;choir sont &agrave; disposition des habitants de l'immeuble.\n";
	$body .= "Pour y acc&eacute;der, il faut prendre la porte du rez et aller &agrave; gauche, ces appareils sont juste derri&egrave;re la 2e porte.<p/>\n";
//
	// Transports publics
	$body .= "<h2>Transports publics</h2>\n";
	$body .= "<p>Malheureusement, le Valais n'est pas parmi les cantons les plus innovateurs en ce qui concerne les transports publics.\n";
	$body .= "Depuis Bramois, on peut rejoindre Sion en bus.\n";
	$body .= "Il y a 3 lignes de bus diff&eacute;rentes, et elles ne passent pas aux m&ecirc;mes arr&ecirc;ts:</p>\n";

	$body .= "<div><ul>\n";
	$body .= "<li>Le Bus S&eacute;dunois passe juste devant &agrave; l'arr&ecirc;t <a target=\"_blank\" href=\"https://www.sbb.ch/fr/acheter/pages/fahrplan/fahrplan.xhtml?suche=true&von=Bramois%2C+Ecole\">Bramois, Ecole</a></li>\n";
	$body .= "<li>Le car Ballestraz suit la route cantonale. On peut le prendre &agrave; <a target=\"_blank\" href=\"https://www.sbb.ch/fr/acheter/pages/fahrplan/fahrplan.xhtml?suche=true&von=Bramois%2C+Paradis\">Bramois, Paradis</a> mais les horaires ne mentionnent pas toujours cet arr&ecirc;t,\n";
	$body .= "il vaut mieux regarder l'horaire &agrave; <a target=\"_blank\" href=\"https://www.sbb.ch/fr/acheter/pages/fahrplan/fahrplan.xhtml?suche=true&von=Bramois%2C+Cassieres\">Bramois, Cassi&egrave;re</a></li>\n";
	$body .= "<li>Le car postal qui monte &agrave; Nax passe par <a target=\"_blank\" href=\"https://www.sbb.ch/fr/acheter/pages/fahrplan/fahrplan.xhtml?suche=true&von=Bramois%2C+Eglise\">Bramois, Eglise</a></li>\n";
	$body .= "</ul></div>\n";

	$body .= "<p>Du vendredi soir au samedi soir, les Bus S&eacute;dunois sont gratuits (2022).\n";
	$body .= "Le samedi il y a en g&eacute;n&eacute;ral des Bus S&eacute;dunois toutes les 20min.\n";
	$body .= "Mais le dimanche, il n'y en a que toutes les 2h...</p>\n";
//
	// Lieux d'interets
	$body .= "<h2>Lieux d'int&eacute;r&ecirc;t</h2>\n";
		// Commerces
		$body .= "<h3>Commerces</h3>\n";
		$body .= "<div><ul>\n";

		$body .= "<li><a target=\"_blank\" href=\"https://goo.gl/maps/yJJhAMPP2YF26GMN6\">Magasin du village (Edelweiss) avec la boucherie</a>.\n";
		$body .= "Aussi du pain, bon choix de fromages.</li>\n";

		$body .= "<li><a target=\"_blank\" href=\"https://goo.gl/maps/HBxGEgHU7oKBpZYv6\">Bioterroir, ou la Roulotte verte</a>:\n";
		$body .= "fruits et l&eacute;gumes bio en vrac, ouvert tous les jours en libre-service, twint ou tirelire cash.\n";
		$body .= "Aussi fromages, viande froide, pain, jus de fruits...</li>\n";

		$body .= "<li><a target=\"_blank\" href=\"https://goo.gl/maps/181tdVc4Yw4fwhAU7\">Pharmacie du village</a></li>\n";

		$body .= "<li><a target=\"_blank\" href=\"https://goo.gl/maps/MSqaFV2FN6t3hohb7\">Migros de Champsec</a> (entre la sortie d'autoroute et notre appartement).</li>\n";

		$body .= "</ul></div>\n";
	//
		// Loisirs
		$body .= "<h3>Loisirs</h3>\n";
		$body .= "<div><ul>\n";

		$body .= "<li>Places de jeux:\n";
		$body .= "<a target=\"_blank\" href=\"https://goo.gl/maps/Xn3DU9vzLByPAfs87\">&eacute;cole</a>,\n";
		$body .= "<a target=\"_blank\" href=\"https://goo.gl/maps/eaeNpJpH19kq7ZQBA\">Clodevis</a>,\n";
		$body .= "<a target=\"_blank\" href=\"https://goo.gl/maps/LFEkkHxoP9zCS6iw9\">Vissigen</a>\n";
		$body .= "</li>\n";

		$body .= "<li>Sentier p&eacute;destre dans les gorges de la Borgne, bons marcheurs.\n";
		$body .= "Alternance entre en bas le long de la Borgne et en haut.\n";
		$body .= "Pour les plus motiv&eacute;s, on peut monter jusqu'&agrav;e Euseigne et redescendre en car postal.</li>\n";

		$body .= "<li><a target=\"_blank\" href=\"https://www.camptocamp.org/waypoints/116027/fr/bramois\">Grimpe dans les gorges de la Borgne</a>.</li>\n";

		$body .= "<li>En hiver, on profite de se promener dans les vergers au Nord du village pour avoir plus de Soleil.</li>\n";

		$body .= "</ul></div>\n";
//
/*
	// Activites en Valais
	$body .= "<h2>Activit&eacute;s en Valais</h2>\n";
	$body .= "<p>\n";
	$body .= "</p>\n";
	$body .= "<div>\n";
	$body .= "</div>\n";
*/


// Finish
echo $body;
unset($page);
?>
