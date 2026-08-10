<?php
require_once("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);

// debug
//$page->htmlHelper->init();
//$page->logger->levelUp(6);

$page->bobbyTable->init();
//$userIsAdmin = $page->loginHelper->userIsAdmin();

$body = "";


$body = $page->bodyBuilder->goHome("..");
// Set title and hot booty
$body .= $page->htmlHelper->setTitle("Apprendre &agrave; &ecirc;tre parent");  // before HotBooty
$page->htmlHelper->hotBooty();


function liInstagram($username) {
    global $page;
    return $page->bodyBuilder->liAnchor("https://www.instagram.com/$username", "<tt>@$username</tt>");
}


$body .= "<p>Principales sources d'inspiration sur instagram:</p>\n";
$body .= "<div><ul>\n";
$body .= liInstagram("etunjour_cavamieux");
$body .= liInstagram("thepositiveparenting");
$body .= liInstagram("nurturedfirst");
$body .= liInstagram("littlebunbao");
$body .= liInstagram("cynthiaarscott");
$body .= liInstagram("goldmindsapp");
$body .= liInstagram("dralexbeyondbehavior");
$body .= liInstagram("thecoolmama.club");
$body .= liInstagram("yestess.familles");
$body .= "</ul></div>\n";

$body .= "<p>Les enfants connaissent le nom de chaque partie du corps et le nom des parties intimes.\n";
$body .= 'On ne dit pas "zizi" ou "n&eacute;n&eacute;" et compagnie.' . "\n";
$body .= "On en parle &agrave; la maison, on n'a pas honte de nommer les parties du corps.\n";
$body .= "Utiliser d'autres termes cr&eacute;e un tabou.\n";
$body .= "</p>\n";

$body .= "<p>Il n'y a pas non plus de tabou sur la d&eacute;couverte de son corps et des sensations.\n";
$body .= "Ce n'est pas sale, mais c'est intime.\n";
$body .= "On le fait seul dans sa chambre pour avoir son intimit&eacute;, pas par honte ou par culpabilit&eacute;.\n";
$body .= "Pour les parents, on respecte leur &acirc;ge et on r&eacute;pond aux questions sans malaise.\n";
$body .= "</p>\n";

$body .= "<p>Les secrets sont interdits.\n";
$body .= "On peut faire des surprises, mais un adulte qui demande &agrave; un enfant de garder un secret c'est RED FLAG!\n";
$body .= "</p>\n";

$body .= "<p>Un adulte/ado qui demande de l'aide a un enfant (hors vie quotidienne) alors qu'il y a d'autres grandes personnes autour,\n";
$body .= "c'est souvent une technique d'approche des pr&eacute;dateurs.</p>\n";

$body .= "<p>On a des livres qui parlent de consentement.\n";
$body .= "On les lit en famille ou chacun de son c&ocirc;t&eacute;, mais &ccedil;a permet d'ouvrir la discussion.\n";
$body .= "On aime bien offrir ces livres aux copains pour les anniversaires.\n";
$body .= "On trouve aussi ce genre de livres &agrave; la biblioth&egrave;que.\n";
$body .= "</p>\n";

$body .= "<p>Toquer &agrave; la porte de la chambre ou de la salle de bain avant d'entrer, c'est respecter leur intimit&eacute;.\n";
$body .= "Les enfants grandissent ainsi avec cette r&egrave;gle.\n";
$body .= "Ils comprennent que le jour o&ugrave; elle n'est pas respect&eacute;e, il faut se m&eacute;fier.\n";
$body .= "</p>\n";

    // Consentement, toucher le corps
    $body .= "<p>Le constentement, c'est de ne pas toucher le corps de l'autre sans avoir son accord.\n";
    $body .= "Cela vaut aussi pour les bisous et les c&acirc;lins.\n";
    $body .= "On n'a pas &agrave; se forcer si on en n'a pas envie.\n";

    $body .= "En tant que parent aussi, je demande ou pr&eacute;viens mon enfant avant de le toucher pour des soins.\n";
    $body .= "Une petite phrase simple qui leur apprend que leur corps leur appartient,\n";
    $body .= "que les adultes, quels qu'ils soient, n'ont pas de droit &agrave; en disposer &agrave; leur guise.\n";

    $body .= "Le consentement n'est pas que pour les adultes, les enfants entre eux doivent aussi le demander et le respecter.\n";

    $body .= "Ce n'est pas parce qu'hier l'autre personne avait envie que c'est toujours le cas maintenant.\n";

    $body .= "Il y a diff&eacute;rentes fa&ccedil;ons de se dire bonjour avec plus ou moins de contacts corporels,\n";
    $body .= "chacun est libre de choisir &agrave; chaque occasion la mani&egrave;re qui lui convient le mieux.\n";

    $body .= "</p>\n";

$body .= "<p>R&egrave;gles de s&eacute;curit&eacute; de son corps:</p>\n";
$body .= "<div><ul>\n";

$body .= "<li>\n";
$body .= "Les parties intimes restent couvertes.\n";
$body .= "Personne ne peut te demander de les regarder ou de les toucher (dans les 2 sens).\n";
$body .= "</li>\n";

$body .= "<li>\n";
$body .= "Si quelqu'un demande de garder un secret et de ne rien dire aux parents, on peut quand m&ecirc;me en parler avec eux.\n";
$body .= "M&ecirc;me si on dit que quelque chose de grave va se passer, ou que c'est de la faute de l'enfant.\n";
$body .= "</li>\n";

$body .= "<li>\n";
$body .= "Si quelqu'un montre ou dit quelque chose qui fait peur, on peut en parler aux parents.\n";
$body .= "</li>\n";

$body .= "<li>\n";
$body .= "</li>\n";

$body .= "</ul></div>\n";

$body .= "<p>Pour que les enfants n'ait pas a demander &agrave; des adultes de toucher leur corps,\n";
$body .= "il est important qu'ils deviennent autonome pour se laver et pour les toilettes.\n";
$body .= "Les enfants aprennent en imitant, on peut donc mimer sur nous les gestes &agrave; faire pour les accompagner.\n";
$body .= "</p>\n";

$body .= "<p>Lorsque l'enfant nous raconte quelque chose de bizarre ou choquant,\n";
$body .= "la premir&egrave;re chose &agrave; lui r&eacute;pondre est \"Je te crois.\"\n";
$body .= "Il faut aussi souligner l'importance de venir nous en parler.\n";
$body .= "\"Je suis fier que tu viennes me le dire.\n";
$body .= "C'est exactement le genre de chose dont tu dois venir nous en parler.\n";
$body .= "M&ecirc;me si ce n'est pas arriv&eacute; &agrave; toi, on a tous le devoir de se prot&eacute;ger les uns les autres.\"\n";
$body .= "</p>\n";

$body .= "<p>Il nous arrive &agrave; tous de ressentir qu'une situation ne se d&eacute;roule pas comme on s'y attendait.\n";
$body .= "On &eacute;coute son instinct, mais on doit aussi apprendre aux enfants &agrave; &eacute;couter le leur.\n";
$body .= "Par exemple si une personne se comporte bizarrement avec nous, on peut se sentir mal &agrave; l'aise:\n";
$body .= "coeur qui bat vite, transpiration, peur, d&eacute;go&ucirc;t...\n";
$body .= "Ce sont des signaux internes de danger.\n";
$body .= "Si cela arrive, il faut fuire et aller en parler &agrave; une personne de confiance.\n";
$body .= "</p>\n";

$body .= "<p>On peut &eacute;tablir une liste des personnes de confiance avec les enfants.\n";
$body .= "C'est important pour les parents d'&ecirc;tre s&ucirc;rs que les personnes sur cette liste respectent les r&egrave;gles &eacute;nonc&eacute;es ici.\n";
$body .= "</po>\n";

$body .= "<p>La violence n'a pas sa place avec les enfants.\n";
$body .= "Si une personne la justifie avec \"qui aime bien ch&acirc;tie bien\", c'est une personne &agrave; &eacute;viter.\n";
$body .= "C'est une relation toxique, la porte ouverte &agrave; toutes les agressions et aux viols.\n";
$body .= "</po>\n";

$body .= "<p>Les agresseurs ne sont pas ceux qu'on croit.\n";
$body .= "Beaucoup d'agressions ont lieu par des proches.\n";
$body .= "Il faut rester vigilant avec nos proches aussi; par parano et suspicieux, juste conscient et vigilant.\n";
$body .= "Et ce ne sont pas non plus que des hommes, il y a aussi des femmes et d'autres enfants.\n";
$body .= "Pour &eacute;viter de laisser &eacute;chapper une situation qui n'aurait pas d&ucirc; avoir lieu,\n";
$body .= "on peut se raconter notre journ&eacute;e, les moments joyeux et les moments bizarres.\n";
$body .= "Cela permet d'&ecirc;tre bien &agrave; l'&eacute;coute des enfants.\n";
$body .= "</p>\n";

$body .= "<p>L'adolescent devient curieux &agrave; propos de s'embrasser et des parties du corps.\n";
$body .= "Il va alors jouer &agrave; des jeux comme Action/V&eacute;rit&eacute; pour l'aider &agrave; assouvir cette curiosit&agrave;.\n";
$body .= "Il faut lui rappeler de rester prudent avec ces jeux, de ne pas jouer &agrave; des jeux qui pourraient\n";
$body .= "l'EXPOSER, lui ou les autres.\n";  // TODO reveler, mettre a nu...
$body .= "</p>\n";

$body .= "<p>\n";
$body .= "Il est difficile pour un enfant de faire la transition entre 2 activit&eacute;s,\n";
$body .= "surtout si la prochaine n'est pas quelque chose que l'on fait pour le plaisir (comme les devoirs).\n";
$body .= "Savoir g&eacute;rer une transition, cela signifie: arr&ecirc;ter, changer d'activit&eacute;, faire un effort.\n";
$body .= "\n";
$body .= "\n";
$body .= "Un exemple:\n";
$body .= "Un enfant de 8 ans a des devoirs &agrave; faire mais il joue.\n";
$body .= "Le parent demande &agrave; l'enfant: tu pr&eacute;f&egrave;res faire tes devoirs maintenant ou apr&egrave;s ton jeu?\n";
$body .= "L'enfant r&eacute;pond: apr&egrave;s.\n";
$body .= "Quand l'enfant a fini son jeu, au lieu de faire ses devoirs, il trouve une autre activit&eacute; plaisante &agrave; faire.\n";
$body .= "Le parent lui dit alors: Je vois que tu as trouv&eacute; autre chose &agrave; faire. Nous avions dit les devoirs apr&egrave;s le jeu.\n";
$body .= "Mais l'enfant ne veut pas les faire.\n";
$body .= "Le parent le sait, mais il sait aussi ce que beaucoup de parents oublient:\n";
$body .= "Quand l'enfant a choisi apr&egrave;s le jeu, il &eacute;tait sinc&egrave;pre et pensait vraiment\n";
$body .= "faire ses devoirs apr&egrave;s son jeu.\n";
$body .= "Le probl&egrave;me est que le moment o&ugrave; la d&eacute;cision a &eacute;t&eacute; prise et\n";
$body .= "le moment o&ugrave; il faut l'appliquer sont 2 moments tr&egrave;s diff&eacute;rents.\n";
$body .= "L'enfant a accept&eacute; le march&eacute; en imaginant la meilleure version de lui,\n";
$body .= "mais pas la difficult&eacute; d'arr&ecirc;ter ce qu'il aime faire.\n";
$body .= "<b>Maintenant, l'enfant a besoin du parent pour cadrer la transition sans perdre le lien.</b>\n";
$body .= "L'enfant va chercher un &eacute;chapattoire: je lis juste une page, encore 5 minutes, steupl&eacute;&eacute;&eacute;&eacute;&eacute;...\n";
$body .= "Il ne cherche pas &agrave; manipuler le parent, mais &eacute;viter quelque chose qui lui co&ucirc;te.\n";
$body .= "Le parent peut faire la diff&eacute;rence.\n";
$body .= "Il ne faut alors pas dire &agrave; l'enfant: Tu avais promis, on ne peut pas te faire confiance.\n";
$body .= "Ce qu'il faut lui dire:\n";
$body .= "Tu n'aimes pas faire tes devoirs. C'est dur de se motiver &agrave; faire quelque chose qu'on aime pas.\n";
$body .= "Cela va aider l'enfant &agrave; se sentir compris.\n";
$body .= "Son prbl&egrave;me, c'est la transition et le manque de motivation:\n";
$body .= "Quitter quelque chose d'agr&eacute;able pour aller vers quelque chose qui demande plus d'efforts.\n";
$body .= "<b>Cela est une comp&eacute;tence complexe.</b>\n";
$body .= "Il ne s'agit pas d'un manque de volont&eacute;, de paresse ou d'un d&eacute;faut de caract&egrave;re.\n";
$body .= "C'est une <b>comp&eacute;tence que les enfants apprennent progressivement</b>.\n";
$body .= "Le parent peut alors dire: Tu pr&eacute;f&egrave;res commencer par la lecture ou par les maths?\n";
$body .= "Ainsi, il garde la limite, les devoirs vont avoir lieu.\n";
$body .= "Mais il redonne &agrave; l'enfant un peu de contr&ocirc;le sur la fa&ccedil;on de les faire.\n";
$body .= "Pour l'enfant, cela change beaucoup.\n";
$body .= "On peut aussi discuter de ce qu'on fera ensuite pour aider l'enfant &agrave; se motiver.\n";
$body .= "Mais si l'enfant continue de s'opposer, le parent dira simplement:\n";
$body .= "Je reste pr&egrave;s de toi le temps que tu d&eacute;cides comment t'y mettre.\n";
$body .= "L'enfant n'a pas besoin que le parent rappelle ce qu'il faut faire,\n";
$body .= "mais il a besoin que le parent lui apprenne comment le faire.\n";
$body .= "Les devoirs ne sont pas seulement un apprentissage scolaire, ils apprennent aussi &agrave; sortir de la procrastination.\n";
$body .= "La formule avec laquelle nous avons grandi \"arr&ecirc;te de discuter et fais-le\" n'est pas la bonne m&eacute;thode.\n";
$body .= "</p>\n";

$body .= "<p>Les enfants construisent leur vocabulaire et leur vision du monde de tout ce qui sort de la bouche des parents.</p>\n";
$body .= "Au lieu de se plaindre (j'ai mal aux jambes apr&egrave;s cette marche),\n";
$body .= "on peut le formuler d'une mani&egrave;re positive (mon corps est tellement intelligent qu'il me dit que j'ai besoin de repos).\n";
$body .= "</p>\n";

$body .= "<p>Quand les enfants sont frustr&eacute;s et pensent qu'ils n'arriveront pas &agrave; faire quelque chose,\n";
$body .= "le parent peut alors se mettre en mode super-joueur.\n";
$body .= "On peut par exemple prendre dans la poche de la poussi&egrave;re magique d'&eacute;toile et\n";
$body .= "la saupoudrer sur la t&ecirc;te de l'enfant en disant \"Voyons voir si &ccedil;a fonctionne...\"\n";
$body .= "Si cela ne fonctionne toujours pas, on peut aussi appuyer sur le bouton \"Je peux le faire\" (nombril, nez...).\n";
$body .= "</p>\n";

$body .= "<p>Lorsqu'on est de mauvais poil, on peut faire la danse de la lib&eacute;ration.\n";
$body .= "On peut secouer chaque partie du corps, de la t&ecirc;te aux pieds.\n";
$body .= "Les enfants voient alors notre choix de d&eacute;lib&eacute;r&eacute;ment investir son &eacute;nergie au lieu de rester grognon.\n";
$body .= "Tous les membres de la famille peuvent rejoindre quelqu'un qui fait cette danse.\n";
$body .= "</p>\n";

$body .= "<p>On peut expliquer les &eacute;motions comme la m&eacute;t&eacute;o:\n";
$body .= "&ecirc;tre heureux est comme un jour ensoleill&eacute;, &ecirc;tre triste comme un jour de pluie...\n";
$body .= "Et comme la m&eacute;t&eacute;o, les &eacute;motions ne sont pas mauvaises et changent.\n";
$body .= "On ressent nos &eacute;motions, on les laisse nous dire ce qu'on a besoin,\n";
$body .= "et ensuite on peut les laisser s'en aller quand on en a fini.\n";
$body .= "Il est important que les enfants sachent que leurs &eacute;motions sont normales et qu'ils n'ont pas besoin de les garder pour toujours.\n";
$body .= "Ils peuvent se cr&eacute;er une vie incroyable avec leurs propres esprits.\n";
$body .= "</p>\n";

// ---------------------------------

$body .= "<p>Quelques r&egrave;gles qu'on peut &eacute;tablir avec ses enfants:</p>\n";
$body .= "<div><ul>\n";

$body .= "<li>\n";
$body .= "Tu peux m'appeler n'importe quand pour me demander de venir te rechercher, m&ecirc;me au milieu de la nuit.\n";
$body .= "Je ne te poserai pas de questions, tu m'en parleras quand tu seras pr&ecirc;t.\n";
$body .= "</li>\n";

$body .= "<li>\n";
$body .= "Il peut arriver que tu soies dans une situation inconfortable,\n";
$body .= "ou que quelqu'un te demande de faire quelque chose que tu ne veux pas.\n";
$body .= "Souviens-toi alors que tu as une voix et que tu peux l'utiliser.\n";
$body .= "Tu as le droit de dire non. Toujours.\n";
$body .= "</li>\n";

$body .= "<li>\n";
$body .= "Les bons amis ne vont pas te forcer &agrave; faire des choses dangereuses, m&eacute;chantes ou qui impliquent les parties intimes.\n";
$body .= "Ne fais jamais quelque chose comme &ccedil;a pour garder un ami ou te faire un nouvel ami.\n";
$body .= "Tu as le droit de les contrarier.\n";
$body .= "</li>\n";

$body .= "<li>\n";
$body .= "Il y a tr&egrave;s peu de choses que tu pourras me dire qui pourrait me choquer.\n";
$body .= "J'ai d&eacute;j&agrave; entendu beaucoup de choses dans ma vie.\n";
$body .= "Donc m&ecirc;me si tu pense que c'est trop bizarre ou effrayant, tu peux quand m&ecirc;me me le dire.\n";
$body .= "Je suis pr&ecirc;t &agrave; l'entendre.\n";
$body .= "</li>\n";

$body .= "<li>\n";
$body .= "Si quelqu'un te montre une video AI de quelqu'un que tu connais qui fait quelque chose de bizarre, dis-le-moi.\n";
$body .= "Je pr&eacute;f&egrave;re &ecirc;tre au courant pour pouvoir te soutenir.\n";
$body .= "</li>\n";

$body .= "</ul></div>\n";


// ------------------------------------

$body .= "<p>Quelques questions qu'il vaut la peine de poser &agrave; ses enfants,\n";
$body .= "dont leurs r&eacute;ponses pourraient nous surprendre:</p>\n";

$body .= "<div><dl>\n";

$body .= "<dt>Est-ce que tu te sens assez aim&eacute; de moi?</dt>\n";
$body .= "<dd>\n";
$body .= "On dit \"je t'aime\" chaque jour, mais l'amour n'a d'importance que s'il est ressenti.\n";
$body .= "</dd>\n";

$body .= "<dt>Est-ce que tu aimes quand je te fais un c&acirc;lin ou un bisou?</dt>\n";
$body .= "<dd>\n";
$body .= "Est-ce que tu en voudrais plus/moins? Chaque enfant est diff&eacute;rent, il vaut mieux demander que supposer.\n";
$body .= "</dd>\n";

$body .= "<dt>Quelle est la chose que tu pr&eacute;f&egrave;re faire quand on n'est que tous les 2?</dt>\n";
$body .= "<dd>\n";
$body .= "Cela nous permet de savoir ce qui les remplit le plus de bonheur, et comment en faire plus.\n";
$body .= "</dd>\n";

$body .= "<dt>Quel est le meilleur cadeau que je t'ai fait?</dt>\n";
$body .= "<dd>\n";
$body .= "Pas s&ucirc;r que ce soit un jouet, probablement plut&ocirc;t un moment ou un souvenir.\n";
$body .= "</dd>\n";

$body .= "<dt>Est-ce que tu pr&eacute;f&egrave;res recevoir une surprise ou qu'on aille choisir ensemble?</dt>\n";
$body .= "<dd>\n";
$body .= "Comment nous donnons notre amour est aussi important que ce que nous donnons.\n";
$body .= "</dd>\n";

$body .= "<dt>Quand je t'aide, est-ce que tu aimes comme je le fais, ou tu aimerais que je fasse quelque chose autrement?</dt>\n";
$body .= "<dd>\n";
$body .= "Parfois, notre aide peut &ecirc;tre ressentie comme une pression.\n";
$body .= "Cette question ouvre la porte de la confiance.\n";  // TODO
$body .= "</dd>\n";

$body .= "<dt>Quand tu es &eacute;nerv&eacute; ou effray&eacute;, qu'est-ce que tu voudrais que je fasse?</dt>\n";
$body .= "<dd>\n";
$body .= "Les enfants savent ce qui les r&eacute;confortent.\n";
$body .= "Il nous faut juste le leur demander...\n";
$body .= "</dd>\n";

$body .= "<dt>Est-ce que tu as parfois l'impression que je suis trop occup&eacute; pour TODO toi?</dt>\n";
$body .= "<dd>\n";
$body .= "Si oui, qu'est-ce que je pourrais faire pour que tu sentes que je suis vraiment pr&eacute;sent?\n";
$body .= "</dd>\n";

$body .= "<dt>Si demain on annule tout juste pour &ecirc;tre les 2, qu'est-ce que tu voudrais faire?</dt>\n";
$body .= "<dd>\n";
$body .= "</dd>\n";

$body .= "<dt>Quand est-ce que tu te sens vraiment &eacute;cout&eacute; de ma part?</dt>\n";
$body .= "<dd>\n";
$body .= "Derri&egrave;re cette question se cache en fait \"est-ce que je suis important pour toi?\"\n";
$body .= "</dd>\n";

$body .= "</dl></div>\n";






// TODO signe de detresse
// TODO Angie dans un bar???
// TODO 2 categories: choses que les parents doivent etre conscients, choses que les parents doivent enseigner a leurs enfants
// Le fait que je te donne des conseils ne veut pas dire que je suis plus intelligent que toi,
// ca veut selement dire que j'ai fait plus de choses stupides que toi. (wethinkdeeply)
// Les bonnes decisions viennent de l'experience, et l'experience vient des mauvaises decisions.


echo $body;
?>
