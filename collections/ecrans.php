<?php
require_once("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);

// debug
//$page->htmlHelper->init();
//$page->logger->levelUp(6);

$body = "";

$body = $page->bodyBuilder->goHome("..");
// Set title and hot booty
$body .= $page->htmlHelper->setTitle("Enfants et &eacute;crans");  // before HotBooty
$page->htmlHelper->hotBooty("fr");

$body .= "<div>Petit r&eacute;sum&eacute; <b>subjectif</b> de:\n";
$body .= "<ul>\n";
$body .= $page->bodyBuilder->lili("Soir&eacute;e d'information aux parents 5-6H sur le monde num&eacute;rique par le canton de Fribourg");
$body .= $page->bodyBuilder->lili("Soir&eacute;e d'information aux parents sur le cyber-harc&egrave;lement par l'&eacute;cole de Belfaux");
$body .= "<li>ProJuventute ateliers en ligne pour les parents ";
$body .= $page->bodyBuilder->anchor(
    "https://www.projuventute.ch/fr/parents/medias-et-internet/competences-numeriques-ateliers-parents",
    "comp&eacute;tences num&eacute;riques"
) . ":\n";
$body .= "<ul>\n";
$body .= $page->bodyBuilder->lili("Ecrans aux quotidiens de l'enfance &agrave; l'adolescence");
$body .= $page->bodyBuilder->lili("Cyberintimidation");
$body .= $page->bodyBuilder->lili("Les r&eacute;seaux sociaux");
$body .= "</ul>\n";
$body .= "</li>\n";
$body .= "</ul>\n";
$body .= "</div>\n";

$body .= $page->bodyBuilder->titleAnchorCountEnable();
    $body .= $page->bodyBuilder->titleAnchor("Enfants et &eacute;crans");
        $body .= $page->bodyBuilder->titleAnchor("Introduction", 3);
        $body .= "<p>Le r&ocirc;le des parents est en fait multiple:</p>\n";
        $body .= "<div><ul>\n";
        $body .= $page->bodyBuilder->lili("mod&egrave;le");
        $body .= $page->bodyBuilder->lili("protecteur");
        $body .= $page->bodyBuilder->lili("renforceur");
        $body .= $page->bodyBuilder->lili("soutien");
        $body .= "</ul></div>\n";

        $body .= "<p>Il peut &ecirc;tre bien de mettre un panier ou une bo&icirc;te o&ugrave; tout le monde d&eacute;pose son &eacute;cran (oui, aussi les parents).\n";
        $body .= "Une &eacute;tude a d&eacute;montr&eacute; que la simple pr&eacute;sence d'un natel dans la pi&egrave;ce perturbe les pens&eacute;es.</p>\n";

        $body .= "<p>Pour accompagner progressivement les enfants avec les &eacute;crans, il est pr&eacute;conis&eacute; de suivre ces &eacute;tapes:</p>\n";
        $body .= "<div><ul>\n";
        $body .= $page->bodyBuilder->lili("introduction aux &eacute;crans &agrave; partir de 3 ans");
        $body .= $page->bodyBuilder->lili("introduction aux jeux vid&eacute;os &agrave; partir de 6 ans");
        $body .= $page->bodyBuilder->lili("introduction &agrave; internet accompagn&eacute; &agrave; partir de 9 ans");
        $body .= $page->bodyBuilder->lili("introduction &agrave; internet seul &agrave; partir de 12 ans");
        $body .= "</ul></div>\n";

        $body .= "<p>La naviguation sur internet s'introduit pas &agrave; pas jusqu'&agrave; une autonomie en s&eacute;curit&eacute;.</p>\n";
    //
        $body .= $page->bodyBuilder->titleAnchor("Cadre et temps d'&eacute;cran", 3);

        $body .= "<p>Il est important de faire la distinction entre <b>consommation</b> et <b>utilisation</b>.\n";
        $body .= "Il ne faut pas oublier que les &eacute;crans peuvent &ecirc;tre des outils ou des aides.</p>\n";

        $body .= "<p>On peut aussi mettre une limite du nombre d'heures d'&eacute;crans par semaine correspondant &agrave; l'&acirc;ge.</p>\n";

        $body .= "<p>Il peut &ecirc;tre bien de poser un cadre pr&eacute;cis par &eacute;crit, contenant:</p>\n";
        $body .= "<div><ul>\n";
        $body .= $page->bodyBuilder->lili("les moments et la dur&eacute;e (en heures ou en parties de jeux)");
        $body .= $page->bodyBuilder->lili("les activit&eacute;s");
        $body .= $page->bodyBuilder->lili("le lieu: id&eacute;alement une pi&egrave;ce commune ouverte");
        $body .= $page->bodyBuilder->lili("les cons&eacute;quences en cas de non-respect");
        $body .= "</ul></div>\n";

        $body .= "<p>On peut aussi v&eacute;rifier l'historique (navigation ou autre) seul ou avec l'enfant pour en discuter.\n";
        $body .= "Il peut &ecirc;tre int&eacute;ressant de tester les sites que l'on ne conna&icirc;t pas.\n";
        $body .= "Pour les plus techniques, on peut v&eacute;rifier l'historique du routeur internet &agrave; la maison.\n";
        $body .= "(Cela n'est &eacute;videmment pas possible ailleurs.)\n";
        $body .= "Certains wifi (comme &agrave; l'&eacute;cole) peuvent &ecirc;tre prot&eacute;g&eacute;,\n";
        $body .= "mais attention car les enfants peuvent aussi acc&eacute;der &agrave; des wifis publics...</p>\n";

        $body .= "<p>Limiter l'acc&egrave;s n'est pas une bonne solution car les enfants arrivent toujours &agrave; trouver un moyen de contourner.\n";
        $body .= "Attention aux soi-disants contr&ocirc;les parentaux. Les enfants peuvent trouver rapidement des tutoriels pour les cracker (google, youtube...).</p>\n";

        $body .= "<p>Comment se rendre compte si l'enfant passe trop de temps sur son &eacute;cran?</p>\n";
        $body .= "<div><ul>\n";
        $body .= $page->bodyBuilder->lili("ses activit&eacute;s diminuent");
        $body .= $page->bodyBuilder->lili("moins d'interactions avec ses copains, peut-&ecirc;tre moins de copains");
        $body .= $page->bodyBuilder->lili("comportement de manque");
        $body .= $page->bodyBuilder->lili("les &eacute;crans deviennent sujets &agrave; conflits");
        $body .= $page->bodyBuilder->lili("plus de fatigue");
        $body .= $page->bodyBuilder->lili("probl&egrave;mes financiers si achat dans app/jeux");
        $body .= "</ul></div>\n";

        $body .= "<p>Lorsque les &eacute;crans emp&ecirc;chent les activit&eacute;s en famille, cela devient un probl&egrave;me.\n";
        $body .= "La moiti&eacute; des enfants qui ont un smartphone l'utilise la nuit.\n";
        $body .= "Il faut avoir conscience que les enfants qui ont un smartphone montrent des choses &agrave; ceux qui n'en ont pas.</p>\n";
    //
        $body .= $page->bodyBuilder->titleAnchor("Risques", 3);

        $body .= "<p>Les malentendus num&eacute;riques:</p>\n";

        $body .= "<div><dl>\n";
        $body .= "<dt>Rien n'est r&eacute;el</dt>\n";
        $body .= "<dd>On peut croire que tout ce qui se passe sur nos &eacute;crans reste sur nos &eacute;crans,\n";
        $body .= "mais cela peut avoir des cons&eacute;quences parfois grave dans la vraie vie.</dd>\n";

        $body .= "<dt>Tout le monde est anonyme</dt>\n";
        $body .= "<dd>On se cache derri&egrave;re un pseudo, mais on peut toujours retrouver des traces de\n";
        $body .= "la v&eacute;ritable identit&eacute; ou de l'appareil.</dd>\n";

        $body .= "<dt>Espace de libert&eacute;</dt>\n";
        $body .= "<dd>On a l'impression qu'on peut tout faire et que tout est permis, mais la loi s'applique aussi sur internet.</dd>\n";

        $body .= "<dt>Tout est &eacute;ph&eacute;m&egrave;re:</dt>\n";
        $body .= "<dd>Poster un statut &eacute;ph&eacute;m&egrave;re ou un snap, c'est le rendre publique.\n";
        $body .= "N'importe qui ayant acc&egrave;s (&agrave; v&eacute;rifier qui) peut le sauvegarder et le redistribuer &agrave; qui et quand il en a envie.</dd>\n";

        $body .= "<dt>Il ne peut rien arriver</dt>\n";
        $body .= "<dd>On peut avoir l'impression que, cach&eacute; derri&egrave;re un pseudo, on est en s&eacute;curit&eacute;\n";
        $body .= "(pour des bonnes ou des mauvaises raisons). Mais on laisse des traces, addresse email, pseudo connu...\n";
        $body .= "Il suffit de peu d'informations pour que certaines personnes arrivent &agrave; nous identifier.\n";
        $body .= "On peut aussi se faire voler des donn&eacute;es (bancaires, mots de passe) avec des cons&eacute;quences terriblles.</dd>\n";
        $body .= "</dl></div>\n";

        $body .= "<p>Il y a diff&eacute;rents dangers qui varient selon l'utilisation et la consommation d'internet:</p>\n";

        $body .= "<div><dl>\n";
        $body .= "<dt>Telegram</dt>\n";
        $body .= "<dd>Porte sur le dark web... Violences extr&ecirc;mes, achats (drogues, armes, &ecirc;tres humains...), terrorisme, radicalisation...</dd>\n";

        $body .= "<dt>Nudes</dt>\n";
        $body .= "<dd>Envoy&eacute;s veut dire publiques.\n";
        $body .= "On compte qu'il faut environ 4 ans pour qu'une image publique soit oubli&eacute;e. Mais on peut toujours les retrouver...\n";
        $body .= "Si on fait des photos/vid&eacute;os &eacute;rotiques avec des mineurs, cela tombe dans la p&eacute;dopornographie et c'est ill&eacute;gal.\n";
        $body .= "Le code p&eacute;nal s'applique d&egrave;s 10 ans.</dd>\n";

        $body .= "<dt>Youtube</dt>\n";
        $body .= "<dd>Fausse repr&eacute;sentation de soi, contient des pubs et des placements, contenus inappropri&eacute;s disponibles</dd>\n";

        $body .= "<dt>Jeux</dt>\n";
        $body .= "<dd>Gestion de la frustration, de la d&eacute;faite et de la victoire, contacts avec le monde, achats int&eacute;gr&eacute;s,\n";
        $body .= "addiction d&ucirc; aux algorithmes, contenus inappropri&eacute;s</dd>\n";

        $body .= "<dt>Fortnite</dt>\n";
        $body .= "<dd>Pas violent aux yeux des enfants car on ne voit pas de sang.\n";
        $body .= "Age: 12 ans. Conseill&eacute;, rien &agrave; voir avec les lois.</dd>\n";

        $body .= "<dt>Roblox</dt>\n";
        $body .= "<dd>Univers avec avatar, permet d'&eacute;chapper &agrave; la r&eacute;alit&eacute;.\n";
        $body .= "Tout le monde peut chatter avec n'importe qui.\n";
        $body .= "L'avatar peut tromper l'interlocuteur sur la v&eacute;ritable personnalit&eacute;.</dd>\n";
        $body .= "</dl></div>\n";

        $body .= "<p>Dans les jeux online, attention aux autres qu'on rencontre (chat...).</p>\n";

        $body .= "<p>Attention aux internautes qui proposent des rencontres dans la vraie vie, il peut souvent s'agir d'un p&eacute;dopi&egrave;geage.</p>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("R&eacute;seaux sociaux (y compris jeux online)");

    $body .= "<p>Quand on rencontre des gens en ligne, on utilise un pseudonyme, jamais notre vrai nom.\n";
    $body .= "Et on ne communique pas non plus notre adresse ou notre photo.\n";
    $body .= "Ne pas rencontrer en vrai des gens que l'on ne connait qu'en ligne.</p>\n";

    $body .= "<p>Whatsapp et compagnie: conditions g&eacute;n&eacute;rales disent au moins 13 ans (peut changer) mais possible avec accord parental.\n";
    $body .= "Aucune loi pour r&eacute;glementer cela (2026-01-27).</p>\n";

    $body .= "<p>Quand on fait un compte priv&eacute; sur un r&eacute;seau social, il faut &ecirc;tre conscient de qui est dans la\n";
    $body .= "communeaut&eacute; de gens ayant acc&egrave;s.</p>\n";

    $body .= "<p>Trend/challenge: attention car certains sont dangereux, et la pression sociale peut pousser les enfants &agrave; essayer.</p>\n";

    $body .= "<p>Il peut &ecirc;tre bon de limiter les r&eacute;seaux sociaux.\n";
    $body .= "Leurs algorithmes sont performants et veulent nous procurer de la dopamine.\n";
    $body .= "Si on en abuse, cela peut mener &agrave; des dysfonctions, voire addiction et d&eacute;pression.</p>\n";

    $body .= "<p>Le lobe pr&eacute;frontal est responsable d'encadrer la circulation de la dopamine.\n";
    $body .= "Mais il ne devient mature que vers 18-25 ans.</p>\n";

    $body .= "<p>Les influenceurs sont pay&eacute;s:</p>\n";
    $body .= "<div><ul>\n";
    $body .= $page->bodyBuilder->lili("ils ne sont pas naturels");
    $body .= $page->bodyBuilder->lili("ils peuvent mentir");
    $body .= $page->bodyBuilder->lili("malgr&egrave; les apparences des r&eacute;seaux sociaux, ils ne sont pas des vrais amis");
    $body .= "</ul></div>\n";

    $body .= "<p>Les photos que l'on poste peuvent &ecirc;tre r&eacute;utilis&eacute;es, il n'y a pas de droits (copyright) sur ce qu'on publie.</p>\n";

    $body .= "<p>Sur certains r&eacute;seaux sociaux qui utilisent la localisation de l'appareil, les amis peuvent nous localiser.</p>\n";

    $body .= "<p>Snapchat donne l'illusion que c'est temporaire, mais les servers conservent les images, et les amis peuvent faire des captures d'&eacute;crans.</p>\n";

    $body .= "<p>Whatsapp peut avoir beaucoup de notifications, ce qui peut devenir une source importante de distractions, pas toujours utiles ni bonnes;</p>\n";
    $body .= "<div><ul>\n";
    $body .= $page->bodyBuilder->lili("spam");
    $body .= $page->bodyBuilder->lili("phishing");
    $body .= $page->bodyBuilder->lili("cha&icirc;nes de messages");
    $body .= "</ul></div>\n";

    $body .= "<p>Les r&eacute;seaux peuvent devenir une perte de temps et avoir un impact addictif, social, et cela peut devenir un cercle vicieux.</p>\n";

    $body .= "<p>Les r&eacute;seaux ont des algorithmes qui ont un m&eacute;canisme fonctionnant sur la r&eacute;compense.\n";
    $body .= "Cela peut aussi para&icirc;tre un bon moyen de fuire des probl&egrave;mes.</p>\n";

    $body .= "<p>Les r&eacute;seaux ont une grande influence sur la construction de l'image de soi.\n";
    $body .= "Les gens utilisent beaucoup de filtres pour para'&icirc;tre mieux.\n";
    $body .= "L'IA permet aussi de modifier et g&eacute;n&eacute;rer des photos.</p>\n";

    $body .= "<p>On voit les activit&eacute;s des amis (vacances, restaurant...) y compris des influenceurs.\n";
    $body .= "On peut facilement se dire qu'ils ont une vie plus int&eacute;ressante que nous.</p>\n";

    $body .= "<p>Il faut se m&eacute;fier des gens qu'on rencontre online et qu'on ne conna&icirc;t pas dans la vraie vie (IRL).</p>\n";

    $body .= "<p>Quelques astuces pour guider les enfants dans leur apprentissage des r&eacute;seaux sociaux:</p>\n";
    $body .= "<div><ul>\n";
    $body .= $page->bodyBuilder->lili("mettre une limite de temps, proposer des activit&eacute;s alternatives");
    $body .= $page->bodyBuilder->lili("parler ouvertement et proactivement, attention au cyber-harc&egrave;lement");
    $body .= $page->bodyBuilder->lili("discuter des choix des personnes suivies par l'enfant");
    $body .= $page->bodyBuilder->lili("configurer l'app pour r&eacute;duire le temps, les stimulations...");
    $body .= "</ul></div>\n";

    $body .= "<p>Il est important d'encourager l'esprit critique chez l'enfant.\n";
    $body .= "Quand on cherche ou parcourt al&eacute;atoirement des posts, il y a toujours l'algorithme qui travaille\n";
    $body .= "derri&egrave;re et qui veut nous garder le plus longtemps possible.</p>\n";

    $body .= "<p>Il faut discuter de ce qui est vraiment important dans la vie (sant&eacute;, famille, etc.).\n";
    $body .= "Discuter de l'impact des likes sur l'enfant.</p>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("Cyber-harc&egrave;lement");

    $body .= "<p>Le harc&egrave;lement n'existe qu'avec l'effet de groupe.\n";
    $body .= "Si le groupe soutient la victime, il n'y a plus de harc&egrave;lement.\n";
    $body .= "Le harc&egrave;lement se d&eacute;veloppe gr&acirc;ce au silence.\n";
    $body .= "Tout le monde est impliqu&eacute;: bourreaus, victimes ET t&eacute;moins!\n";
    $body .= "Il faut parler tout de suite sinon on devient complice.</p>\n";

    $body .= "<p>Il faut avoir conscience que nos besoins ne sont pas les m&ecirc;mes que ceux de l'enfant.\n";
    $body .= "Il est important de parler avec l'enfant.\n";
    $body .= "On peut lui dire qu'on se fait du souci, lui demander de quoi il a besoin.\n";
    $body .= "On se positionne clairement.\n";
    $body .= "On peut demander de l'aide ext&eacute;rieure au besoin.\n";
    $body .= "On peut envisager de d&eacute;poser une plainte, mais cela peut tout aussi bien empirer la situation.</p>\n";

    $body .= "<p>Il est aussi important d'en parler si l'enfant est t&eacute;moin d'intimidations.</p>\n";

    $body .= "<p>L'enfant peut avoir plein de raisons de ne pas en parler:</p>\n";
    $body .= "<div><ul>\n";
    $body .= "<li>il peut avoir peur:";
    $body .= "<ul>\n";
        $body .= $page->bodyBuilder->lili("que ca soit pire");
        $body .= $page->bodyBuilder->lili("d'&ecirc;tre ou de para&icirc;tre coupable");
        $body .= $page->bodyBuilder->lili("d'&ecirc;tre puni");
    $body .=  "</ul>\n";
    $body .=  "</li>\n";

    $body .= $page->bodyBuilder->lili("il peut penser que c'est normal");
    $body .= $page->bodyBuilder->lili("il peut penser que ce n'est pas en en parlant que cela ira mieux");
    $body .= $page->bodyBuilder->lili("il peut en avoir honte");
    $body .= $page->bodyBuilder->lili("il ne veut peut-&ecirc;tre pas en parler avec la famille, voire avec des adultes");
    $body .= "</ul></div>\n";

    $body .= "<p>L'enfant peut montrer certains signes qu'il y a du (cyber-)harc&egrave;lement:</p>\n";
    $body .= "<div><ul>\n";
    $body .= $page->bodyBuilder->lili("retrait");
    $body .= $page->bodyBuilder->lili("ne veut plus aller &agrave; l'&eacute;cole");
    $body .= $page->bodyBuilder->lili("perte d'app&eacute;tit");
    $body .= $page->bodyBuilder->lili("maux de ventre");
    $body .= $page->bodyBuilder->lili("maux de t&ecirc;te");
    $body .= $page->bodyBuilder->lili("tristesse");
    $body .= $page->bodyBuilder->lili("fatigue (probl&egrave;mes de sommeil)");
    $body .= $page->bodyBuilder->lili("passe plus (ou moins) de temps sur les &eacute;crans");
    $body .= "</ul></div>\n";

    $body .= "<p>Les sentiments de l'enfant peuvent &ecirc;tre:</p>\n";
    $body .= "<div><ul>\n";
    $body .= $page->bodyBuilder->lili("anxieux");
    $body .= $page->bodyBuilder->lili("menac&eacute;");
    $body .= $page->bodyBuilder->lili("impuissant");
    $body .= $page->bodyBuilder->lili("triste");
    $body .= "</ul></div>\n";

    $body .= "<p>Le code p&eacute;nal s'applique d&egrave;s 10 ans.\n";
    $body .= "En suisse, le cyber-harc&egrave;lement n'est pas en tant que tel couvert par le code p&eacute;nal.\n";
    $body .= "En revanche, le code p&eacute;nal couvre diff&eacute;rentes infractions qui peuvent concerner le cyber-harc&egrave;lement.\n";
    $body .= "Quelques exemples:</p>\n";
    $body .= "<div><ul>\n";
    $body .= $page->bodyBuilder->lili("diffamation, calomnies, injures");
    $body .= $page->bodyBuilder->lili("menaces");
    $body .= $page->bodyBuilder->lili("discrimination");
    $body .= $page->bodyBuilder->lili("pornographie: puni si rend accessible aux moins de 16 ans, si offre &agrave; une personne sans y avoir &eacute;t&eacute; invit&eacute;");
    $body .= "</ul></div>\n";

    $body .= "<p>En tant que parent, on peut aider notre enfant:</p>\n";
    $body .= "<div><ul>\n";
    $body .= $page->bodyBuilder->lili("&eacute;couter, questionner");
    $body .= $page->bodyBuilder->lili("prendre au s&eacute;rieux");
    $body .= $page->bodyBuilder->lili("ne pas le culpabiliser");
    $body .= $page->bodyBuilder->lili("trouver un endroit o&ugrave; il peut se ressourcer ou trouver du soutien");
    $body .= $page->bodyBuilder->lili("l'impliquer dans toute la proc&eacute;dure (l'informer)");
    $body .= $page->bodyBuilder->lili("lui expliquer de ne pas insulter ou faire de commentaires en r&eacute;ponse");
    $body .= $page->bodyBuilder->lili("bloquer et signaler l'auteur du probl&egrave;me");
    $body .= $page->bodyBuilder->lili("garder des preuves (captures d'&eacute;crans)");
    $body .= $page->bodyBuilder->lili("demander de l'aide");
    $body .= "</ul></div>\n";

    $body .= "<p>En tant que parent, on a tr&egrave;s envie de confronter l'auteur, mais cela pourrait tres bien empirer la situation.\n";
    $body .= "Il n'est pas conseill&eacute; de le faire.</p>\n";

    $body .= "<p>Pour amener l'enfant &agrave; nous parler, il ne faut pas entrer dans le vif du sujet.\n";
    $body .= "Il faut essayer de l'amener avec des questions d&eacute;tourn&eacute;es.\n";
    $body .= "On peut proposer d'appeler le 147 pour parler avec des adultes qui ne sont pas en lien avec la famille.\n";
    $body .= "Il est important de toujours montrer de l'int&eacute;r&ecirc;t en posant des questions et en proposant de l'aide.</p>\n";

    $body .= "<p>Attention aux &eacute;motions des parents, elles peuvent avoir un effet n&eacute;gatif sur la soituation et les discussions.</p>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("Resources additionnelles");
    $body .= "<div><ul>\n";
    $body .= $page->bodyBuilder->liAnchor("http://fritic.ch", "FRITIC");
    $body .= $page->bodyBuilder->liAnchor("http://prevention-ecrans.ch", "REPER");
    $body .= $page->bodyBuilder->liAnchor("http://147.ch");
    $body .= $page->bodyBuilder->liAnchor("http://jeunesetmedias.ch");
    $body .= $page->bodyBuilder->liAnchor("http://lumni.fr/programme/la-famille-tout-ecran", "La famille tout &eacute;cran");
    $body .= $page->bodyBuilder->liAnchor("https://www.fr.ch/police-et-securite/prevention/brigade-des-mineurs", "Brigade des mineurs (BMI)");
    $body .= $page->bodyBuilder->liAnchor("http://websters.swiss", "La famille Websters (OFCOM)");
    $body .= $page->bodyBuilder->liAnchor("http://instagram.com/ecop.francois", "@ecop.francois");
    $body .= $page->bodyBuilder->liAnchor("https://www.qwantjunior.com/", "Qwant Junior (moteur de recherche pour enfants)");
    $body .= "</ul></div>\n";


echo $body;
?>
