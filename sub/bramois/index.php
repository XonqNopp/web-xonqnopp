<?php
require_once("../../functions/page_helper.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
// debug
//$page->htmlHelper->init();
//$page->logger->levelUp(6);
// CSS paths
$page->cssHelper->dirUpWing(2);
// init body
$body = "";


// Set title and hot booty
$body .= $page->htmlHelper->setTitle("Bramois");// before HotBooty
$page->htmlHelper->hotBooty();

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
    $body .= "Il y a 4 places marqu&eacute;es.\n";
    $body .= "Pour le week-end, on peut utiliser la place la plus au fond qui est pour l'entreprise Energiq.\n";
    $body .= "Si vous s&eacute;journez en dehors du week-end, nous devons trouver un arrangement pour avoir une place\n";
    $body .= "disponible, sinon il y a le parking au bout de la rue de l'&eacute;cole.\n";
    $body .= "Normalement, la place le long du quai est r&eacute;serv&eacute;e pour le bus de EnergiQ.\n";
    $body .= "Si besoin on peut aussi se parquer du c&ocirc;t&eacute; chemin de l'&eacute;cole, m&ecirc;me si la voiture d&eacute;passe des lignes.</p>\n";
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

    $body .= "<p>Pour les enfants, l'armoire de gauche au salon contient des livres, de quoi dessiner et des jeux (jouets, lego, jeux de soci&eacute;t&eacute;).\n";
    $body .= "Il y a aussi des BDs dans l'armoire de la cuisine sous l'horloge, avec du sable kinetic et de la p&acirc;te &agrave; modeler.\n";
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
    $body .= "Il y a des t&eacute;l&eacute;commandes.\n";
    $body .= "Pour le salon et la cuisine, elles sont dans le tiroir de la cuisine.\n";
    $body .= "Pour la chambre, elle est soit sur le rebord de la fen&ecirc;tre de la chambre,\n";
    $body .= "soit dans l'armoire a balais en haut du support en metal.</p>\n";

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
    $body .= "Il n'y a pas (du moins jusqu'&agrave; maintenant) de moustiques donc cela ne pose aucun probl&egrave;me de laisser les fen&ecirc;tres ouvertes tout le temps,\n";
    $body .= "m&ecirc;me la nuit avec la lumi&egrave;re allum&eacute;e.\n";
    $body .= "</p>\n";
//
    // Jardin
    $body .= "<h2>Jardin</h2>\n";
    $body .= "<p>Comme pour le parking, le jardin est partag&eacute; avec tous les autres utilisateurs du b&acirc;timents (sauf les clients).\n";
    $body .= "Il y a une caisse avec diff&eacute;rents jeux sous l'abri, avec notamment une balan&ccedil;oire qui s'accroche dans le cerisier (c&ocirc;t&eacute; Ouest).\n";
    $body .= "Il y a aussi un hamac &agrave; accrocher dans le cerisier (c&ocirc;t&eacute; Est ou parking),\n";
    $body .= "mais il est rang&eacute; dans les escaliers, au palier avant notre appartement,\n";
    $body .= "car dehors les souris viennent le grignoter.\n";
    $body .= "Pour jouer avec des enfants, il est conseill&eacute; d'enlever les appareils pour &eacute;loigner les chats, et de les remttre quand on quitte le jardin.</p>\n";

    $body .= "<p>Nous avons aussi un petit grill &agrave; gaz qui peut &ecirc;tre utiliser.\n";
    $body .= "La grille est en haut, dans l'armoire en dessus du frigo.</p>\n";
//
    // Lessive
    $body .= "<h2>Lessive</h2>\n";
    $body .= "<p>Une machine &agrave; laver et un s&eacute;choir sont &agrave; disposition des habitants de l'immeuble.\n";
    $body .= "Pour y acc&eacute;der, il faut prendre la porte du rez et aller &agrave; gauche, ces appareils sont juste derri&egrave;re la 2e porte.</p>\n";
//
    // Transports publics
    $body .= "<h2>Transports publics</h2>\n";
    $body .= "<p>Malheureusement, le Valais n'est pas parmi les cantons les plus innovateurs en ce qui concerne les transports publics.\n";
    $body .= "Depuis Bramois, on peut rejoindre Sion en bus.\n";
    $body .= "Il y a 3 lignes de bus diff&eacute;rentes, et elles ne passent pas aux m&ecirc;mes arr&ecirc;ts:</p>\n";

    $body .= "<div><ul>\n";
    $body .= "<li>Le Bus S&eacute;dunois passe juste devant &agrave; l'arr&ecirc;t ";
    $body .= $page->bodyBuilder->anchor("https://www.sbb.ch/fr/acheter/pages/fahrplan/fahrplan.xhtml?suche=true&von=Bramois%2C+Ecole", "Bramois, Ecole");
    $body .= "</li>\n";
    $body .= "<li>Le car Ballestraz suit la route cantonale. On peut le prendre &agrave; ";
    $body .= $page->bodyBuilder->anchor("https://www.sbb.ch/fr/acheter/pages/fahrplan/fahrplan.xhtml?suche=true&von=Bramois%2C+Paradis", "Bramois, Paradis");
    $body .= " mais les horaires ne mentionnent pas toujours cet arr&ecirc;t,\n";
    $body .= "il vaut mieux regarder l'horaire &agrave; ";
    $body .= $page->bodyBuilder->anchor("https://www.sbb.ch/fr/acheter/pages/fahrplan/fahrplan.xhtml?suche=true&von=Bramois%2C+Cassieres", "Bramois, Cassi&egrave;re");
    $body .= "</li>\n";
    $body .= "<li>Le car postal qui monte &agrave; Nax passe par ";
    $body .= $page->bodyBuilder->anchor("https://www.sbb.ch/fr/acheter/pages/fahrplan/fahrplan.xhtml?suche=true&von=Bramois%2C+Eglise", "Bramois, Eglise");
    $body .= "</li>\n";
    $body .= "</ul></div>\n";

    $body .= "<p>Du vendredi soir au samedi soir, les Bus S&eacute;dunois sont gratuits (2022).\n";
    $body .= "Le samedi il y a en g&eacute;n&eacute;ral des Bus S&eacute;dunois toutes les 20min.\n";
    $body .= "Mais le dimanche, il n'y en a que toutes les 2h...</p>\n";
//
    // Commerces
    $body .= "<h2>Commerces</h2>\n";
    $body .= "<div><ul>\n";

    $body .= "<li>";
    $body .= $page->bodyBuilder->anchor("https://goo.gl/maps/yJJhAMPP2YF26GMN6", "Magasin du village (Edelweiss) avec la boucherie");
    $body .= "Aussi du pain, bon choix de fromages.</li>\n";

    $body .= "<li>";
    $body .= $page->bodyBuilder->anchor("https://goo.gl/maps/HBxGEgHU7oKBpZYv6", "Bioterroir, ou la Roulotte verte");
    $body .= "fruits et l&eacute;gumes bio en vrac, ouvert tous les jours en libre-service, twint ou tirelire cash.\n";
    $body .= "Aussi fromages, viande froide, pain, jus de fruits...</li>\n";

    $body .= $page->bodyBuilder->liAnchor("https://goo.gl/maps/181tdVc4Yw4fwhAU7", "Pharmacie du village");

    $body .= "<li>";
    $body .= $page->bodyBuilder->anchor("https://goo.gl/maps/MSqaFV2FN6t3hohb7", "Migros de Champsec");
    $body .= " (entre la sortie d'autoroute et notre appartement).</li>\n";

    $body .= "</ul></div>\n";


function chm($args) {
    return "https://map.veloland.ch/?"
        . "lang=fr"
        . "&photos=yes"
        . "&logo=yes"
        . "&detours=yes"
        . "&bgLayer=pk"
        . "&layers=Wanderland"
        . "&season=summer"
        . "&$args";
}


function liachm($args, $description) {
    global $page;
    return $page->bodyBuilder->liAnchor(chm($args), $description, true);
}


    $body .= "<h2>Activit&eacute;s en Valais</h2>\n";

        $body .= "<h3>Sur le trajet: Lac L&eacute;man, Chablais</h3>\n";
        $body .= "<div><ul>\n";
        $body .= $page->bodyBuilder->liAnchor("https://www.aquaparc.ch/", "Aquaparc, Bouveret", true);
        $body .= $page->bodyBuilder->liAnchor("https://swissvapeur.ch/", "Swiss vapeur Parc, Bouveret", true);
        $body .= $page->bodyBuilder->liAnchor("https://www.labyrinthe.ch/", "labyrinthe aventure, Evionnaz", true);
        $body .= liachm("resolution=1&N=1110349&E=2568008", "la Pissevache");
        $body .= "</ul></div>\n";
    //
        $body .= "<h3>Ext&eacute;rieur</h3>\n";
        $body .= "<div><ul>\n";

        $body .= "<li>Places de jeux:\n";
        $body .= $page->bodyBuilder->anchor("https://goo.gl/maps/Xn3DU9vzLByPAfs87", "&eacute;cole");
        $body .= "- ";
        $body .= $page->bodyBuilder->anchor("https://goo.gl/maps/eaeNpJpH19kq7ZQBA", "Clodevis");
        $body .= "- ";
        $body .= $page->bodyBuilder->anchor("https://goo.gl/maps/LFEkkHxoP9zCS6iw9", "Vissigen");
        $body .= "</li>\n";

        $body .= $page->bodyBuilder->liAnchor("https://www.longeborgne.ch/", "Ermitage de Longeborgne sur les hauts de Bramois", true);

        $body .= "<li>";
        $body .= $page->bodyBuilder->anchor(chm("resolution=1.37&E=2597414&N=1119247"), "Sentier p&eacute;destre dans les gorges de la Borgne, bons marcheurs.");
        $body .= "Alternance entre en bas le long de la Borgne et en haut des falaises.\n";
        $body .= "Pour les plus motiv&eacute;s, on peut monter jusqu'&agrav;e Euseigne et redescendre en car postal.</li>\n";

        $body .= $page->bodyBuilder->liAnchor("https://www.camptocamp.org/waypoints/116027/fr/bramois", "Grimpe dans les gorges de la Borgne", true);

        $body .= "<li>En hiver, on profite de se promener dans ";
        $body .= $page->bodyBuilder->anchor(chm("resolution=1.45&E=2596563&N=1120865"), "les vergers au Nord du village");
        $body .= " pour avoir plus de Soleil.";

        $body .= $page->bodyBuilder->liAnchor("https://www.western-city.ch/", "Western City, au restoroute du St-Bernard");
        $body .= $page->bodyBuilder->liAnchor("https://www.happyland.ch/", "Happyland, Granges");
        $body .= $page->bodyBuilder->liAnchor("https://www.cretillons.ch/", "animaux &agrave; l'Arche des Cr&eacute;tillons, Chalais");
        $body .= liachm("resolution=1&E=2594156&N=1120336", "Sion, vieille ville, ch&acirc;teaux");
        $body .= $page->bodyBuilder->liAnchor("https://www.maisondelanature.ch/", "lac et colline de Montorge, et la Maison de la Nature");
        $body .= $page->bodyBuilder->liAnchor("https://les-iles.bourgeoisie-de-sion.ch/", "les Iles, Sion: place de jeux, balade, mur de grimpe, parc aventure (ferm&eacute; en hiver, true), baignade en &eacute;t&eacute;, petit train...");
        $body .= "<li>bisses divers: ";
        $body .= $page->bodyBuilder->anchor("https://www.les-bisses-du-valais.ch/fr/Bisse/Bisse-de-Clavau/", "bisse du clavau");
        $body .= ", R&eacute;chy-Chalais, gorges de la R&egrave;che...</li>\n";
        $body .= liachm("resolution=1&E=2604110&N=1122178", "Gorges et cascade de la R&egrave;che");
        $body .= $page->bodyBuilder->liAnchor("https://www.pfyn-finges.ch/fr/", "Bois de Pfyn/Finges");
        $body .= liachm("resolution=2.62&E=2595916&N=1120647", "le long du Rh&ocirc;ne");
        $body .= liachm("resolution=1&E=2580504&N=1114586", "Saillon: t&ecirc;te des g&eacute;ants, passerelle &agrave; Farinet (pas &agrave; la m&ecirc;me altitude)");
        $body .= $page->bodyBuilder->liAnchor("https://www.noble-contree.ch/fr/obabao-2457.html", "Obabao, jeux de l'oie g&eacute;ant, Venth&ocirc;ne");
        $body .= "</ul></div>\n";
    //
        $body .= "<h3>Int&eacute;rieur</h3>\n";
        $body .= "<div><ul>\n";
        $body .= $page->bodyBuilder->liAnchor("https://barryland.ch/", "mus&eacute;e des chiens du St-Bernard");
        $body .= $page->bodyBuilder->liAnchor("https://kidsfunpark.ch/", "Salle de jeux pour enfants, Sion et Martigny");
        $body .= $page->bodyBuilder->liAnchor("https://monstrofun.ch/", "Monstrofun salle de jeux pour enfants, Martigny");
        $body .= $page->bodyBuilder->liAnchor("https://www.musees-valais.ch/musee-de-la-nature/presentation.html", "mus&eacute;e d'histoire naturelle, Sion");
        $body .= $page->bodyBuilder->liAnchor("https://www.mediatheque.ch/", "mediath&egrave;que Sion");
        $body .= $page->bodyBuilder->liAnchor("https://lac-souterrain.com/", "Lac sous-terrain, Saint-L&eacute;onard");
        $body .= "</ul></div>\n";
    //
        $body .= "<h3>Baignade et bains thermaux</h3>\n";
        $body .= "<div><ul>\n";
        $body .= $page->bodyBuilder->liAnchor("https://www.sion.ch/infrastructuressportives/17957", "Sion piscine plein air");
        $body .= $page->bodyBuilder->liAnchor("https://www.sion.ch/infrastructuressportives/17954", "Sion piscine couverte");
        $body .= "<li>Les Iles mentionn&eacute;es plus haut</li>\n";
        $body .= liachm("resolution=1&E=2599969&N=1122048", "Lac de la Corne, Gr&ocirc;ne");
        $body .= liachm("resolution=1&E=2607746&N=1126333", "Lac de G&eacute;ronde, Sierre");
        $body .= $page->bodyBuilder->liAnchor("https://www.bainsdesaillon.ch/", "Saillon");
        $body .= $page->bodyBuilder->liAnchor("https://leukerbad.ch/en/therme", "Leukerbad");
        $body .= $page->bodyBuilder->liAnchor("https://thermalbad-wallis.ch/", "Brigerbad");
        $body .= $page->bodyBuilder->liAnchor("https://www.bains-ovronnaz.ch/", "Ovronnaz");
        $body .= $page->bodyBuilder->liAnchor("https://www.bains-lavey.ch/", "Lavey les bains");
        $body .= $page->bodyBuilder->liAnchor("https://alaia.ch/bay/", "Alaia bay: initiation surf aux Iles, Sion");
        $body .= "</ul></div>\n";


echo $body;
?>
