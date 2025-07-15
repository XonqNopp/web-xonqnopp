<?php
require_once("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
// debug
//$page->htmlHelper->init();
//$page->logger->levelUp(6);
// CSS paths
//$page->cssHelper->dirUpWing();


$page->bodyBuilder->titleAnchorCountEnable();

$body = $page->bodyBuilder->goHome("..");

// Set title and hot booty
$body .= $page->htmlHelper->setTitle("&Eacute;ducation positive");// before HotBooty
$page->htmlHelper->hotBooty("fr");

$body .= "<p>Petit r&eacute;sum&eacute; <b>subjectif</b> de:\n";
$body .= "<i>Mon p'tit cahier d'&Eacute;ducation positive</i>,\n";
$body .= "Christine Klein,\n";
$body .= "aux Editions Solar.</p>\n";


$body .= "<div class=\"framed\">\n";
$body .= "Un comportement a toujours une intention positive et r&eacute;v&egrave;le un besoin.\n";
$body .= "</div>\n";


$body .= "<!-- H2 Education -->\n";
$body .= "<h2>Education</h2>\n";

$body .= "<!-- H3 Communication -->\n";
$body .= "<h3>Communication</h3>\n";
$body .= "<div><ol>\n";
    $body .= "<li>On communique <b>toujours</b>! 7% verbal, 38% para-verbal, 55% non-verbal.</li>\n";
    $body .= "<li>Chaque personne a <b>sa propre</b> vision du monde:<br>\n";
    $body .= "soi - humeur - croyances - valeurs - &eacute;ducation - culture - ... - monde</li>\n";
    $body .= "<li>Chaque comportement a une intention positive</li>\n";
$body .= "</ol></div>\n";

$body .= "<!-- H3 Cerveau -->\n";
$body .= "<h3>Cerveau</h3>\n";
$body .= "<p>Le cerveau se divise en 3 parties principales:</p>\n";
$body .= "<div><ol>\n";
    $body .= "<li>reptilien/archa&iuml;que: combat/fuite</li>\n";
    $body .= "<li>limbique/&eacute;motionnel</li>\n";
    $body .= "<li>sup&eacute;rieur (n&eacute;ocortex)</li>\n";
$body .= "</ol></div>\n";
$body .= "<p>Le cerveau se sp&eacute;cialise par la fr&eacute;quence des exp&eacute;riences v&eacute;cues, pas par la\n";
$body .= "qualit&eacute;.</p>\n";
$body .= "<p>Un enfant <b>n'est pas</b> un petit adulte.\n";
$body .= "Voici un tableau qui explique les capacit&eacute;s de l'enfant par rapport &agrave; son &acirc;ge (p.18-19):</p>\n";

$body .= "<div class=\"tablebottom\">\n";
$body .= $page->butler->tableOpen();
    $body .= $page->butler->rowOpen();
        $body .= $page->butler->headerCell("&Acirc;ge");
        $body .= $page->butler->headerCell("Comportement");
        $body .= $page->butler->headerCell("Explications");
        $body .= $page->butler->headerCell("Pistes de changement");
    $body .= $page->butler->rowClose();

    $body .= "<!-- 0-2 -->\n";
    $body .= $page->butler->row("0-2 ans", array(), array("colspan" => 4, "class" => "fullLine"));

    $body .= $page->butler->rowOpen();
        $body .= $page->butler->cell("18-24 mois", array("rowspan" => 3));
        $body .= $page->butler->cell("hurle &agrave; la moindre frustration");
        $body .= $page->butler->cell("la partie de son cerveau qui ma&icirc;trise ses impulsions n'est pas encore mature");
        $body .= $page->butler->cell("pratiquer l'&eacute;coute active: formuler ce qui vient de se passer pour l'aider &agrave; comprendre et a se sentir reconnu");
    $body .= $page->butler->rowClose();

    $body .= $page->butler->rowOpen();
        $body .= "<!--<td>18-24 mois</td>-->\n";
        $body .= $page->butler->cell("il me regarde dans les yeux tout en faisant exactement ce que je viens de lui interdire");
        $body .= $page->butler->cell("l'enfant ne dispose pas du tout du langage ou que tr&egrave;s peu. C'est avec son corps qu'il demande la validation de la consigne: c'est ca que tu ne veux pas que je fasse?");
        $body .= $page->butler->cell("lui confirmer qu'il a bien compris la consigne et lui proposer une autre activit&eacute;");
    $body .= $page->butler->rowClose();

    $body .= $page->butler->rowOpen();
        $body .= "<!--<td>18-24 mois</td>-->\n";
        $body .= $page->butler->cell("il n'&eacute;coute pas quand je lui demande d'arr&ecirc;ter un comportement");
        $body .= $page->butler->cell("le cerveau ne comprend pas la n&eacute;gation");
    $body .= $page->butler->cell("formuler les demandes de facon affirmatives et aider l'enfant &agrave; se diriger vers le comportement souhait&eacute;");
    $body .= $page->butler->rowClose();

    $body .= "<!-- 2-4 -->\n";
    $body .= $page->butler->row("2-4 ans", array(), array("colspan" => 4, "class" => "fullLine"));

    $body .= $page->butler->rowOpen();
        $body .= $page->butler->cell("2-6 ans");
        $body .= $page->butler->cell("il ne sait pas se tenir tranquille (restaurant, courses...)");
        $body .= $page->butler->cell("les capacit&eacute;s neuronales de l'enfant ne lui permettent pas de rester calement assis, et un cerveau qui s'ennuie trouvera une occupation");
    $body .= $page->butler->cell("Chercher &agrave; occuper l'enfant autrement plut&ocirc;t que de lui demander de rester calme");
    $body .= $page->butler->rowClose();

    $body .= $page->butler->rowOpen();
        $body .= $page->butler->cell("2 ans");
        $body .= $page->butler->cell("quand il s'amuse &agrave; un endroit (amis, place de jeux), impossible d'en partir sans drame");
        $body .= $page->butler->cell("il est encore tr&egrave;s difficile &agrave; ce jeune cerveau d'anticiper. Les notions de temps et de duree sont encore tres floues");
        $body .= $page->butler->cell("Installer des routines pour faciliter les moments de transition de la vie courante");
    $body .= $page->butler->rowClose();

    $body .= $page->butler->rowOpen();
        $body .= $page->butler->cell("3 ans");
        $body .= $page->butler->cell("bien qu'il connaisse les r&egrave;gles, il ne les respecte pas");
        $body .= $page->butler->cell("la partie du cerveau qui reformule la r&egrave;gle est encore mal connect&eacute;e &agrave; la partie du cerveau qui permet d'emp&ecirc;cher le geste");
        $body .= $page->butler->cell("&ecirc;tre parent c'est r&eacute;p&eacute;ter souvent... L'enfant a encore besoin que le cadre lui soit rappel&eacute;. On peut aussi r&eacute;fl&eacute;chir &agrave; une alternative qui l'aiderait &agrave; se tenir (un jeu, un signal convenu d'avance)");
    $body .= $page->butler->rowClose();

    $body .= $page->butler->rowOpen();
        $body .= $page->butler->cell("3-4 ans");
        $body .= $page->butler->cell("il a de nouvelles peurs et il imagine des choses terrifiantes, il fait des cauchemars");
        $body .= $page->butler->cell("la construction mentale et l'imaginaire se developpent. L'enfant est encore dans la confusion: ce qui existe dans son imaginaire existe pour de vrai");
        $body .= $page->butler->cell("Les actes symboliques sont int&eacute;resants, comme le dessin, la poup&eacute;e &agrave; soucis, le pi&egrave;ge &agrave; monstres. Se mettre &agrave; l'&eacute;coute pour d&eacute;charger l'intensit&eacute; &eacute;motionnelle de l'enfant.");
    $body .= $page->butler->rowClose();

    $body .= "<!-- 4-6 -->\n";
    $body .= $page->butler->row("4-6 ans", array(), array("colspan" => 4, "class" => "fullLine"));

    $body .= $page->butler->rowOpen();
        $body .= $page->butler->cell("4 ans");
        $body .= $page->butler->cell("il ment en disant que ce n'est pas lui qui a fait telle chose");
        $body .= $page->butler->cell("l'enfant ne fait pas encore le lien entre ses actes et leurs cons&eacute;quences");
        $body .= $page->butler->cell("l'aider progressivement &agrave; faire le lien entre ses actions et les cons&eacute;quences qui en d&eacute;coulent. Par exemple: tu as pris le feutre dans ta main et il laisse des traces sur le mur");
    $body .= $page->butler->rowClose();

    $body .= $page->butler->rowOpen();
        $body .= $page->butler->cell("4 ans");
        $body .= $page->butler->cell("il parle sans filtre: elle est grosse la dame, le monsieur sent mauvais");
        $body .= $page->butler->cell("&agrave; cet &acirc;ge, les pens&eacute;es se font a haute voix, l'enfant ne peut pas encore penser en silence dans sa t&ecirc;te");
        $body .= $page->butler->cell("l'adulte qui est souvent mal &agrave; l'aise gronde l'enfant. Il n'y a pas grand chose &agrave; faire cependant, si ce n'est expliquer &agrave; l'enfant la raison de son embarras, &agrave; distance de la \"victime\"");
    $body .= $page->butler->rowClose();

    $body .= $page->butler->rowOpen();
        $body .= $page->butler->cell("5 ans");
        $body .= $page->butler->cell("alors qu'il sait parfaitement le faire, il met tr&egrave;s longtemps &agrave; s'habiller");
        $body .= $page->butler->cell("&agrave; cause de son immaturit&eacute; c&eacute;r&eacute;brale, il se laisse encore distraire tr&egrave;s facilement");
        $body .= $page->butler->cell("l'encourager &agrave; chacune des &eacute;tapes, et pourquoi pas, nommer chacun de ses v&ecirc;tements pour l'aider &agrave; se concentrer");
    $body .= $page->butler->rowClose();

    $body .= "<!-- 6-8 -->\n";
    $body .= $page->butler->row("6-8 ans", array(), array("colspan" => 4, "class" => "fullLine"));

    $body .= $page->butler->rowOpen();
        $body .= $page->butler->cell("6-7 ans");
        $body .= $page->butler->cell("il raconte avec aplomb des histoires hallucinantes");
        $body .= $page->butler->cell("il commence &agrave; utilsier le poteentiel imaginatif de son cerveau mais il distingue encore difficilement la realit&eacute; des histoires qu'il se raconte dans la t&ecirc;te");
        $body .= $page->butler->cell("&eacute;couter son histoire et ne pas h&eacute;siter &agrave; lui faire un petit clin d'oeil pour lui montrer que vous n'&ecirc;tes pas dupe. Vous pourrez revenir dessus plus tard pour l'aider &agrave; faire la part des choses");
    $body .= $page->butler->rowClose();

    $body .= $page->butler->rowOpen();
        $body .= $page->butler->cell("7-8 ans");
        $body .= $page->butler->cell("il est encore bien maladroit");
        $body .= $page->butler->cell("le corps de l'enfant grandit et se modifie rapidement; pas facile pour lui de s'adapter sans cesse");
        $body .= $page->butler->cell("se poser la question: si un ami faisait la m&ecirc;me maladresse, quel discours lui tiendrais-je?");
    $body .= $page->butler->rowClose();

    $body .= $page->butler->rowOpen();
        $body .= $page->butler->cell("8 ans");
        $body .= $page->butler->cell("il fait tout ce qui est interdit");
        $body .= $page->butler->cell("l'interdit incite &agrave; la transgression et bien que la maturation de leur cerveau le permette maintenant de r&eacute;flechir, les enfants ne peuvent pas r&eacute;sister &agrave; l'envie d'aller l&agrave; o&ugrave; se porte leur attention (et leurs envies)");
        $body .= $page->butler->cell("&eacute;tablir des r&egrave;gles plut&ocirc;t que des interdits");
    $body .= $page->butler->rowClose();
$body .= $page->butler->tableClose();
$body .= "</div><!-- tablebottom -->\n";


$body .= "<!-- H3 Cles de l'educ -->\n";
$body .= "<h3>Cl&eacute;s de l'&eacute;ducation positive</h3>\n";
$body .= "<div><ul>\n";
    $body .= "<li>&ecirc;tre &agrave; l'&eacute;coute des &eacute;motions (les siennes avant celles des autres), savoir les exprimer</li>\n";

    $body .= "<li>beaucoup d'amour:\n";
    $body .= "<i>c'est lorsqu'ils semblent le m&eacute;riter le moins que nos enfants ont le plus besoin d'amour et d'attention.</i>\n";
    $body .= "(Aletha Solter)\n";
    $body .= "<li class=\"framed\">\n";
        $body .= "C&acirc;lins, bisous etc. sont du carburant, pas une r&eacute;compense!\n";
    $body .= "</li>\n";

    $body .= "<li>Ne pas tout accepter: une main ferme et tendre dans un gant de velour.\n";
    $body .= "Donner du sens aux r&egrave;gles par un recadrage bienveillant mais ferme.</li>\n";

    $body .= "<li>Une autorit&eacute; d'influence: respect et confiance.\n";
    $body .= "Par ex. lorsque je m'inqui&egrave;te, plut&ocirc;t que de s'&eacute;nerver et punir,\n";
    $body .= "lui faire comprendre en essayant de le mettre &agrave; ma place.</li>\n";

    $body .= "<li>Pas de punitions ni de r&eacute;compenses: coop&eacute;ration plut&ocirc;t que comp&eacute;tition.\n";
    $body .= "Cela implique une responsabilisation et de l'autonomie pour l'enfant.</li>\n";

$body .= "</ul></div>\n";


$body .= "<!-- H2 Emotions -->\n";
$body .= "<h2>&Eacute;motions</h2>\n";

$body .= "<p>4 &eacute;motions de base: joie, col&egrave;re, tristesse, peur.</p>\n";
$body .= "<p>&Eacute;motion est un d&eacute;clencheur, et notre cerveau y apporte 3 r&eacute;ponses: physilogique,\n";
$body .= "comportementale, cognitive.</p>\n";
$body .= "<p>Equit&eacute; est plus important que &eacute;galit&eacute;: ce qui compte, ce sont les besoins de chacun.</p>\n";

$body .= "<div>\n";
$body .= $page->bodyBuilder->img("/pictures/equite.png", "equite");
$body .= "</div>\n";

$body .= "<p>Lorsqu'on est dans une crise &eacute;motionnelle, on est prisonnier du cerveau &eacute;motionnel, le cerveau\n";
$body .= "sup&eacute;rieur est hors service.</p>\n";
$body .= "<p>Il est important d'&eacute;couter (accompagner) les &eacute;motions plut&ocirc;t que de les limiter (les\n";
$body .= "repousser).</p>\n";
$body .= "<p>Il faut laisser l'enfant chercher des solutions (ne pas lui donner nos solutions d'adultes) mais l'accompagner dans\n";
$body .= "ses &eacute;motions.\n";
$body .= "Sa perception du monde et ses ressources ne sont pas celles d'un adulte, il r&eacute;soudra diff&eacute;remment.</p>\n";
$body .= "<p>S'ouvrir &agrave; la mani&egrave;re dont l'autre per&ccedil;oit le monde.</p>\n";


$body .= "<!-- H3 Ecac -->\n";
$body .= "<h3>&Eacute;coute active</h3>\n";
$body .= "<p>Aider &agrave; comprendre les &eacute;motions qui se passent en lui, mettre des mots sur ce qu'il vit:\n";
$body .= "ce qu'il ressent est d&eacute;sagr&eacute;able, mais normal dans cette situation.</p>\n";

$body .= "<p>Par exemple, avant la rentr&eacute;e de l'&eacute;cole, l'enfant dit qu'il a mal au ventre, qu'il est inquiet:</p>\n";
$body .= "<div><ul>\n";
    $body .= "<li>NE PAS: t'inqui&egrave;te pas, c'est rien, ca va aller/passer.</li>\n";
    $body .= "<li>MAIS: c'est normal d'&ecirc;tre inquiet, les autres aussi. Il y a quelque chose de particulier qui\n";
    $body .= "t'inqui&egrave;te?</li>\n";
$body .= "</ul></div>\n";

$body .= "<p>Il faut privil&eacute;gier les questions ouvertes pour inviter l'enfant &agrave; formuler ses r&eacute;ponses\n";
$body .= "plut&ocirc;t que laisser l'adulte mener la conversation.</p>\n";
$body .= "<p>Il est important d'&ecirc;tre <b>vraiment</b> pr&eacute;sent (disponible) pour &eacute;couter l'enfant.</p>\n";

$body .= "<p>Exemple: une sortie longuement pr&eacute;vue est annul&eacute;e. L'enfant est d&eacute;&ccedil;u et pleure.</p>\n";
$body .= "<div><ul>\n";
    $body .= "<li>NE PAS: arr&ecirc;te ton caprice.</li>\n";
    $body .= "<li>MAIS: discuter de la joie qu'il se faisait &agrave; l'id&eacute;e.</li>\n";
$body .= "</ul></div>\n";

$body .= "<p>Reformuler les &eacute;motions de l'enfant pour &ecirc;tre plus pr&egrave;s de son ressenti.</p>\n";
$body .= "<p>&Eacute;motions exprim&eacute;es, elles se d&eacute;gonflent, cela permet de retrouver le cerveau sup&eacute;rieur et\n";
$body .= "de pouvoir r&eacute;fl&eacute;chir et chercher des solutions.</p>\n";

$body .= "<div class=\"framed\">\n";

$body .= "<!-- H5 Astuce sophrologique -->\n";
$body .= "<h5>Astuce sophrologie pour g&eacute;rer les &eacute;motions</h5>\n";

$body .= "<p>Aider l'enfant &agrave; faire le lien entre sensations physiques et &eacute;motions.</p>\n";
$body .= "<div><ul>\n";
    $body .= "<li>o&ugrave; ressens-tu [<i>&eacute;motion</i>] dans ton corps?</li>\n";
    $body .= "<li>si ton/ta [<i>&eacute;motion</i>] avait une couleur, laquelle &ccedil;a serait?</li>\n";
$body .= "</ul></div>\n";

$body .= "<p>Quand &eacute;motion commence &agrave; se d&eacute;gonfler, aider l'enfant &agrave; chasser l'inconfort physique (pas\n";
$body .= "l'&eacute;motion!)</p>\n";
$body .= "<p>Exercice: sur une longue inspiration, observer les &eacute;motions et les tensions\n";
$body .= "g&eacute;n&eacute;r&eacute;es;\n";
$body .= "sur une expiration tonique (souffler), lib&eacute;rer les tensions.</p>\n";
$body .= "<p>Cela permet &agrave; l'enfant de prendre conscience de son &eacute;motion &agrave; la fois verbalement (elle a\n";
$body .= "&eacute;t&eacute; nomm&eacute;e) et corporellement (il la situe dans son corps).</p>\n";

$body .= "</div>\n";

$body .= "<div class=\"framed\"><p><b>ATTENTION:</b></p>\n";
$body .= "<p>Il ne faut pas laisser l'enfant exprimer ses &eacute;motions brutalement,\n";
$body .= "mais lui apprendre &agrave; tenir compte des informations fournies par ses &eacute;motions et lui apprendre &agrave;\n";
$body .= "g&eacute;rer.\n";
$body .= "Si le corps r&eacute;agit, c'est que quelque chose d'important se passe en lui, il ne peut pas l'ignorer.\n";
$body .= "Il peut se servir de ce qu'il ressent pour comprendre ce qui est important pour lui dans ce contexte et pour agir en\n";
$body .= "fonction.</p>\n";
$body .= "</div>\n";

$body .= "<p>Pour rappel, derri&egrave;re chaque comportement il y a une intention positive.\n";
$body .= "Cela signifie qu'un comportement &eacute;quivaut a un besoin.</p>\n";
$body .= "<p>Si les besoins fondamentaux ne sont pas combl&eacute;s, l'enfant ne peut pas avoir un comportement acceptable.</p>\n";
$body .= "<p>Si l'enfant a faim/sommeil, il ne pourra pas se comporter correctement et il ne lui sera pas possible de penser\n";
$body .= "&agrave; autre chose que lui.\n";
$body .= "Il ne pourra pas r&eacute;pondre &agrave; nos besoins d'adultes.</p>\n";

$body .= "<p>Les d&eacute;sirs et les envies ne sont pas des besoins, mais ils cachent des besoins.\n";
$body .= "Il faut se demander \"qu'est-ce que cela permet?\"\n";
$body .= "Par exemple, si l'enfant demande un jus de fruits, il n'a pas besoin de jus de fruits mais de boire.</p>\n";
$body .= "<p>Un besoin doit &ecirc;tre satisfait pour notre bien-&ecirc;tre.\n";
$body .= "Un d&eacute;sir (qui est important et moteur) n'exige pas d'&ecirc;tre combl&eacute; mais est un moyen de combler un\n";
$body .= "besoin.</p>\n";
$body .= "<p>L'enfant ne peut pas combler un besoin de fa&ccedil;on autonome, il a donc besoin du parent.</p>\n";

$body .= "<p>La frustration est impossible &agrave; g&eacute;rer avant 4 ans, et seulement 60% des enfants de 12 ans y\n";
$body .= "arrivent.</p>\n";
$body .= "<p>Pour aider &agrave; g&eacute;rer, on peut proposer des alternatives.</p>\n";
$body .= "<p>&Eacute;coute active fait progresser l'intelligence &eacute;motionnelle de l'enfant: identifier, comprendre, exprimer\n";
$body .= "et &eacute;couter, r&eacute;guler, utiliser les &eacute;motions au quotidien.</p>\n";
$body .= "<p>Pour &ecirc;tre capable d'avoir de l'empathie, il faut en premier &ecirc;tre capable de concevoir les &eacute;motions\n";
$body .= "chez soi avant autrui.</p>\n";

$body .= "<div class=\"framed\">\n";
$body .= "L'<b>&eacute;coute active</b> signifie ne pas imposer nos solutions d'adulte &agrave; l'enfant\n";
$body .= "mais l'aider &agrave; activer ses ressources pour trouver ses propres solutions.\n";
$body .= "</div>\n";




$body .= "<!-- H3 Accompagner les emos -->\n";
$body .= "<h3>Accompagner les &eacute;motions de l'enfant</h3>\n";

$body .= "<!-- H4 General -->\n";
$body .= "<h4>G&eacute;n&eacute;ralit&eacute;s</h4>\n";
$body .= "<p>Il est important de consacrer au moins 10 minutes de pleine attention &agrave; l'enfant chaque jour.</p>\n";
$body .= "<p>Un enfant a besoin de contacts physiques jusque vers 10 ans.</p>\n";
$body .= "<p>On peut faire dans la maison un espace zen (pour tous) pour aller se ressourcer quand on est d&eacute;bord&eacute;\n";
$body .= "par nos &eacute;motions.\n";
$body .= "Par exemple des coussins, des matti&egrave;res douces, des boules &agrave; neige &agrave; regarder, des crayons et\n";
$body .= "des feuilles, des mandalas, des balles &agrave; malaxer, une balle de tennis pour auto-massages, des livres...</p>\n";

$body .= "<p>Un bon truc est d'utiliser l'imaginaire: les enfants adorent faire semblant.\n";
$body .= "Par exemple, si l'enfant n'a pas envie d'aller &agrave; l'&eacute;cole, on peut raconter ce qu'on ferait si on allait\n";
$body .= "pas &agrave; l'&eacute;cole.\n";
$body .= "Ainsi l'enfant comprend qu'on reconna&icirc;t son envie, mais l'adulte n'a pas besoin d'y c&eacute;der.\n";
$body .= "Cela renforce les liens de confiance et de proximit&eacute;.</p>\n";
$body .= "<p>Lorsque l'enfant se sent reconnu dans ses besoins,\n";
$body .= "cela l'aide &agrave; trouver des ressources en lui pour g&eacute;rer la situation.</p>\n";

$body .= "<div class=\"framed\"><p><b>ATTENTION:</b></p>\n";
$body .= "Le <b>sucre</b> est aussi un acteur important dans le comportement de l'enfant.\n";
$body .= "Si l'enfant fait des motagnes russes &eacute;motionnelles (agitations suivies d'apathies),\n";
$body .= "il faut surveiller sa consommation de sucre.\n";
$body .= "Overdose de sucre m&egrave;ne &agrave; <i>surpoids</i> et <i>diab&egrave;te</i>,\n";
$body .= "mais aussi <b>troubles de la m&eacute;moire</b> et <b>troubles de l'attention</b>.\n";
$body .= "</div>\n";


$body .= "<!-- H4 Joie -->\n";
$body .= "<h4>Joie</h4>\n";
$body .= "<p>Pour accompagner la joie, on peut par exemple prendre chaque jour un moment pour chacun partager un beau moment de la\n";
$body .= "journ&eacute;e.\n";
$body .= "Eventuellement faire une \"bo&icirc;te &agrave; bonheur\" &agrave; ouvrir une fois par an et &agrave; remplir avec des\n";
$body .= "petits mots, des histoires, des billets de cin&eacute;ma...</p>\n";


$body .= "<!-- H4 Colere -->\n";
$body .= "<h4>Col&egrave;re</h4>\n";
$body .= "<p><b>D&eacute;finition</b>: la col&egrave;re est l'&eacute;motion qui se manifeste quand on ressent le besoin\n";
$body .= "de changer une situation inacceptable (injustice, frustration, agression...).\n";
$body .= "La col&egrave;re a donc besoin de <b>s'exprimer</b> (pas se r&eacute;primer) pour diminuer.</p>\n";
$body .= "<p>La violence est l'&eacute;chec de la col&egrave;re: quand on n'arrive pas &agrave; se faire comprendre, la\n";
$body .= "col&egrave;re s'amplifie et passe par les gestes.</p>\n";
$body .= "<p>Si les col&egrave;res de l'enfant posent probl&egrave;mes, on peut essayer de les analyser avec le tableau\n";
$body .= "suivant:</p>\n";
$body .= "<div><ul>\n";
    $body .= "<li>moment de la journ&eacute;e</li>\n";
    $body .= "<li>situation</li>\n";
    $body .= "<li>personnes</li>\n";
    $body .= "<li>mode d'expression de la col&egrave;re (gestes, mots, volume sonore)</li>\n";
    $body .= "<li>estimation de l'intensit&eacute; de l'&eacute;motion</li>\n";
    $body .= "<li>ma r&eacute;action (mots, gestes)</li>\n";
$body .= "</ul></div>\n";

$body .= "<p>Une fois les situations qui engendrent la col&egrave;re chez l'enfant identifi&eacute;e,\n";
$body .= "on peut discuter des situations probl&eacute;matiques explicitement pour trouver des solutions ensembles.</p>\n";

$body .= "<p>Quand l'enfant manifeste une col&egrave;re, il est important de l'&eacute;couter (&eacute;coute active)\n";
$body .= "sans le toucher, reformuler ce qu'il dit.\n";
$body .= "S'il est en col&egrave;re contre moi, placer ma t&ecirc;te plus basse que la sienne peut aider son cerveau a\n";
$body .= "interpr&eacute;ter que je ne suis pas une menace.\n";
$body .= "Il est important de respirer profond&eacute;ment pour accueillir l'intensit&eacute; &eacute;motionnelle.\n";
$body .= "Faire attention au temps de qualit&eacute; pass&eacute; ensemble, cela peut &ecirc;tre un facteur d&eacute;clencheur de\n";
$body .= "col&egrave;res.</p>\n";

$body .= "<!-- H5 en cas de coleres -->\n";
$body .= "<h5>En cas de col&egrave;res</h5>\n";
$body .= "<div><ul>\n";
    $body .= "<li>bo&icirc;te &agrave; cris (d&eacute;cor&eacute;e?): on peut crier dedans puis la vider par la fen&ecirc;tre</li>\n";
    $body .= "<li>du papier &agrave; froisser</li>\n";
    $body .= "<li>gribouiller avec un gros feutre ou d&eacute;crire la col&egrave;re puis chiffoner et jeter le papier</li>\n";
    $body .= "<li>ballon de baudruche: on souffle la col&egrave;re dedans et on vide par la fen&ecirc;tre</li>\n";
    $body .= "<li id=\"valises\">valises &agrave; contrari&eacute;t&eacute; (sophrologie):\n";
    $body .= "<ul>\n";
        $body .= "<li>on se met debout</li>\n";
        $body .= "<li>on imagine des valises (forme, couleur...)</li>\n";

        $body .= "<li>on met dans les valises les choses dont l'enfant veut s'all&eacute;ger, cec qui le met en col&egrave;re\n";
        $body .= "(interdiction de mettre des personnes, plut&ocirc;t des attitudes, des ressentis...)</li>\n";

        $body .= "<li>sur une longue inspiration, on prend les valises, on l&egrave;ve les &eacute;paules</li>\n";
        $body .= "<li>on bloque la respiration, on baisse et rel&egrave;ve les &eacute;paules de 3 &agrave; 5 fois</li>\n";
        $body .= "<li>sur une expiration tonique par la bouche, on jette les valises par terre</li>\n";
    $body .= "</ul></li>\n";
    $body .= "<li>respirations synchronis&eacute;es (sophrologie): on prend une grande inspiration,\n";
    $body .= "on bloque et on contracte le plus de muscles possibles (visage, &eacute;paules, dos, torse, bras, poings, bas du\n";
    $body .= "corps),\n";
    $body .= "puis on fait une longue et profonde expiration et en m&ecirc;me temps on rel&acirc;che les muscles,\n";
    $body .= "et on finit avec quelques respirations calmes et profonds en &eacute;tant d&eacute;tendu</li>\n";
$body .= "</ul></div>\n";


$body .= "<!-- H4 Peur -->\n";
$body .= "<h4>Peur</h4>\n";
$body .= "<p><b>D&eacute;finition</b>: la peur nous permet de d&eacute;tecter les dangers, de nous en &eacute;loingner ou de les\n";
$body .= "combattre, avec pour objectif la survie. C'est l'&eacute;motion la plus profond&eacute;ment ancr&eacute;e en nous.</p>\n";
$body .= "<p>Un enfant effray&eacute; ne peut pas &ecirc;tre rassur&eacute; par des paroles adultes.\n";
$body .= "Il a besoin de trouver la r&eacute;assurance en lui-m&ecirc;me, et pour cela il a besoin que l'adulte fournisse de\n";
$body .= "l'&eacute;coute et des informations.</p>\n";
$body .= "<p>Il est tr&egrave;s important de ne pas rire ni se moquer des peurs de l'enfant.</p>\n";
$body .= "<p>Certains enfants sont plus imaginatifs/craintifs.</p>\n";
$body .= "<p>Lorsqu'on demande &agrave; l'enfant d'expliquer sa peur en d&eacute;tails, il se sentira mieux d'avoir pu mettre des\n";
$body .= "mots sur son &eacute;motion.</p>\n";
$body .= "<p>Il est important de bien &eacute;couter, &eacute;viter d'anticiper &agrave; sa place, puis bien l'informer.</p>\n";
$body .= "<p>L'adulte peut encourager l'enfant (<a href=\"#compliment\">correctement</a>), souligner de fa&ccedil;on descriptive tous\n";
$body .= "ses progr&egrave;s, les mettre en mots sinc&egrave;rement pour qu'il se les approprie, mais attention de ne pas\n";
$body .= "exag&eacute;rer les faits.</p>\n";
$body .= "<p>On peut aussi stimuler la m&eacute;moire des r&eacute;ussites: rappeler des souvenirs de r&eacute;ussites (contre une\n";
$body .= "peur) pass&eacute;es.</p>\n";

$body .= "<!-- H5 En cas de peur -->\n";
$body .= "<h5>En cas de peur</h5>\n";
$body .= "<div><ul>\n";
    $body .= "<li>dessiner les peurs: les mettre &agrave; l'ext&eacute;rieur de lui. Il peut aussi poursuivre en dessinant une\n";
    $body .= "solution rigolote pour les neutraliser</li>\n";
    $body .= "<li>Apr&egrave;s une phase intense, faire 1-2 minutes de respirations profondes</li>\n";
    $body .= "<li>Exercice du polichinelle (sophrologie): debout, les pieds restent coll&eacute;s au sol, on fait trembler les\n";
    $body .= "genoux et on garde le corps souple (comme un pantin qui saute).\n";
    $body .= "Cela aide l'enfant &agrave; s'ancrer au sol, &agrave; mettre son corps en mouvement face &agrave; la peur, et cela\n";
    $body .= "augmente son &eacute;nergie.</li>\n";
$body .= "</ul></div>\n";


$body .= "<!-- H4 Tristesse -->\n";
$body .= "<h4>Tristesse</h4>\n";
$body .= "<p><b>D&eacute;finition:</b> la tristesse est l'&eacute;motion de perte (deuil, s&eacute;paration, d&eacute;ception, d&eacute;sillusion...).</p>\n";
$body .= "<p>La tristesse se passe en 2 &eacute;tapes: il faut d'abord dig&eacute;rer la perte,\n";
$body .= "puis on peut imaginer l'avenir sans ce qui a &eacute;t&eacute; perdu;\n";
$body .= "la vie est pleine de belles surprises!</p>\n";
$body .= "<p><b>Avec l'accord de l'enfant</b>, on peut lui faire des c&acirc;lins, des caresses et le laisser pleurer.\n";
$body .= "Il ne faut pas essayer d'avoir des mots r&eacute;confortants, cela ne va pas aider l'enfant.</p>\n";
$body .= "<p>On peut demander &agrave; l'enfant d'expliquer, sans lui sugg&eacute;rer de solutions/compensations.</p>\n";
$body .= "<p>Apr&egrave;s avoir s&eacute;ch&eacute; les larmes, on peut gentiment parler du futur.</p>\n";


$body .= "<!-- H5 En cas de tristesse -->\n";
$body .= "<h5>En cas de tristesse</h5>\n";
$body .= "<div><ul>\n";
    $body .= "<li>Le circuit &eacute;nerg&eacute;tique: &agrave; faire 3 fois, exercer des pressions douces de quelques secondes sur\n";
    $body .= "(dans l'ordre):\n";
    $body .= "<ul>\n";
        $body .= "<li>la base ext&eacute;rieure de l'ongle du gros orteil droite</li>\n";
        $body .= "<li>en posant la main sur l'&eacute;paule gauche, au niveau du majeur</li>\n";
        $body .= "<li>l'int&eacute;rieur de l'avant-bras droit &agrave; 3 largeurs de doigts du poignet</li>\n";
        $body .= "<li>l'articulation pouce-index gauche</li>\n";
        $body .= "<li>sur le thorax &agrave; mi-chemin des mamelons</li>\n";
    $body .= "</ul></li>\n";
    $body .= "<li>Cahier positif: chaque soir, &eacute;crire/dessiner 3 &agrave; 5 choses positives de la journ&eacute;e. Cela aide\n";
    $body .= "&agrave; ouvrir le regard sur le positif</li>\n";
    $body .= "<li>La danse de la bougie: faire danser la flamme avec le souffle sans l'&eacute;teindre; &eacute;loigner la bougie\n";
    $body .= "pour faire des respirations plus profondes</li>\n";
    $body .= "<li>visualiser le futur (sophrologie): assis, on essaye de se d&eacute;tendre (au moins les sourcils, la\n";
    $body .= "m&acirc;choire, les &eacute;paules et le ventre),\n";
    $body .= "et l'enfant imagine et d&eacute;crit dans quelques mois une situation agr&eacute;able pour lui (avec des\n";
    $body .= "d&eacute;atils du lieu, des personnes, de l'action).\n";
    $body .= "L'enfant doit &ecirc;tre attentif &agrave; ses ressentis positifs dans la situation.\n";
    $body .= "Apr&egrave;s quelques instants, inspirer le positif de la sc&egrave;ne et l'expirer dans le pr&eacute;sent.</li>\n";
$body .= "</ul></div>\n";


$body .= "<!-- H4 Jalousie -->\n";
$body .= "<h4>Jalousie</h4>\n";
$body .= "<p><b>D&eacute;finition:</b> la Jalousie est l'&eacute;motion de l'ins&eacute;curit&eacute;, la peur de perdre.</p>\n";
$body .= "<p>Quand un enfant est jaloux, l'adulte doit enqu&ecirc;ter pour comprendre le besoin cach&eacute;.</p>\n";
$body .= "<p>Il faut faire de l'&eacute;coute active en t&ecirc;te-&agrave;-t&ecirc;te (sans fr&egrave;re/soeur) pour parler\n";
$body .= "librement.</p>\n";
$body .= "<p>L'adulte doit rechercher l'&eacute;quit&eacute; plut&ocirc;t que l'&eacute;galit&eacute;:\n";
$body .= "donner &agrave; chacun ce qu'il a besoin plut&ocirc;t que la m&ecirc;me chose &agrave; tous.\n";
$body .= "Cela va aider l'enfant &agrave; distinguer ses propres besoins plut&ocirc;t que de se comparer.</p>\n";
$body .= "<p>L'adulte doit &eacute;viter les comparaisons.</p>\n";
$body .= "<p>Attention aux compliments, &eacute;viter de faire des compliments importants devant les autres enfants.</p>\n";
$body .= "<p>Si un gros conflit de jalousie arrive, on peut intervenir (de mani&egrave;re <b>impartiale</b>) comme suit:</p>\n";
$body .= "<div><ol>\n";
    $body .= "<li>d&eacute;crire la situation</li>\n";
    $body .= "<li>demander &agrave; chacun d'exrpimer ses ressentis</li>\n";
    $body .= "<li>reformuler les &eacute;motions et les besoins de chacun</li>\n";
    $body .= "<li>dire explicitement qu'il est difficile de satisfaire tout le monde;\n";
    $body .= "&eacute;viter de minimiser, reconna&icirc;tre que ce n'est pas toujours facile de vivre ensemble et trouver des\n";
    $body .= "solutions satisfaisantes.</li>\n";
    $body .= "<li>Si besoin, guider la recherche de solutions en proposant plusieurs choix et en demandant aux enfants leurs\n";
    $body .= "solutions/id&eacute;es</li>\n";
    $body .= "<li>dire explicitement que je suis confiant qu'ils vont trouver une solution satisfaisante pour chacun</li>\n";
    $body .= "<li><b>les laisser seuls</b> pour finaliser et mettre en pratique la solution</li>\n";
$body .= "</ol></div>\n";


$body .= "<!-- H5 En cas de jalousie -->\n";
$body .= "<h5>En cas de jalousie</h5>\n";
$body .= "<div><ul>\n";
    $body .= "<li>mettre en place un signe complice: trouver avec l'enfant un signe facile &agrave; faire, qui ne soit pas un geste\n";
    $body .= "courant. Lorsque l'enfant utilise ce signe, cela signifie qu'il a besoin d'attention ou d'aide</li>\n";
    $body .= "<li>des petits mots: les parents glissent un petit mot sous l'oreiller (mots d'amour/gratitude, compliments, dessins).\n";
    $body .= "On peut aussi faire participer tout le monde et en faire un rituel familial dont la fr&eacute;quence est &agrave;\n";
    $body .= "d&eacute;finir</li>\n";
    $body .= "<li>respiration du coeur: sur une inspiration, le coeur se remplit d'amour et de sourires; sur l'expiration, le coeur\n";
    $body .= "rayonne ce qu'il a emmagasin&eacute; dans la maison et plus loin (copains, famille, Terre...)</li>\n";
    $body .= "<li>pause m&eacute;ditative (sophrologie): proposer un moment tranquille et silencieux (pas trop long) &agrave;\n";
    $body .= "l'&eacute;coute de ce qui se passe en lui.\n";
    $body .= "On peut l'aider en lui posant des questions:\n";
    $body .= "<ul>\n";
        $body .= "<li>Est-ce qu'il y a des endroits calmes/agit&eacute;s dans ton corps?</li>\n";
        $body .= "<li>Quelles parties de ton corps bougent quand tu respires?</li>\n";
        $body .= "<li>Ressens-tu une &eacute;motion particuli&egrave;re?</li>\n";
    $body .= "</ul>\n";
    $body .= "(Faire une pause apr&egrave;s chaque question.)<br>\n";
    $body .= "Si on pratique cela r&eacute;guli&egrave;rement, &ccedil;a nous apprend &agrave; se connecter &agrave; nos ressentis\n";
    $body .= "et &agrave; nos besoins.\n";
    $body .= "C'est donc une aide pour revenir &agrave; soi et &agrave; ses propres besoins plut&ocirc;t que de se comparer.</li>\n";
$body .= "</ul></div>\n";



$body .= "<!-- H2 Parents -->\n";
$body .= "<h2>Parents</h2>\n";
$body .= "<p><b>Personne n'est parfait, tout le monde fait des erreurs.</b></p>\n";
$body .= "<p>Il est important de se remettre en question.</p>\n";
$body .= "<p>Quand le parent culpabilise, il faut revenir aux besoins de chacun;\n";
$body .= "attention car c'est diff&eacute;rent que de projeter ce dont l'enfant pourrait avoir besoin de mon point de vue adulte\n";
$body .= "coinc&eacute; dans la culpabilit&eacute;.</p>\n";
$body .= "<p>Personne n'est parfait, on fait tous des erreurs: la remise en question peut &ecirc;tre une occasion\n";
$body .= "p&eacute;dagogique tant pour l'adulte que pour l'enfant.</p>\n";
$body .= "<p>Par exemple, si le parent culpabilise de ne pas passer assez de temps avec l'enfant,\n";
$body .= "lui faire une sortie surprise n'est pas forc&eacute;ment ce qu'il attendra et ne va peut-&ecirc;tre pas am&eacute;liorer\n";
$body .= "la situation, voire va l'empirer.\n";
$body .= "Il faut plut&ocirc;t en parler avec l'enfant et lui demander comment il aimerait que la situation soit\n";
$body .= "r&eacute;par&eacute;e/am&eacute;lior&eacute;e.</p>\n";

$body .= "<p>Il faut prendre le temps d'apprendre. Lorsqu'on veut mettre en place quelque chose de nouveau, cela ne va pas venir\n";
$body .= "instantan&eacute;ment.\n";

$consciemment = "consciemment";
$competent = "comp&eacute;tent";
$in = "<b>in</b>";
$pas = "<b>pas</b>";
$que = "QUE";
// pas+que = Paques

$body .= "L'apprentissage se passe en 4 &eacute;tapes:</p>\n";
$body .= "<div><ol>\n";
    $body .= "<li>$in$consciemment $in$competent: je ne sais $pas $que je ne sais $pas</li>\n";
    $body .= "<li>   $consciemment $in$competent: je sais         $que je ne sais $pas</li>\n";
    $body .= "<li>   $consciemment    $competent: je sais         $que je sais</li>\n";
    $body .= "<li>$in$consciemment    $competent: je ne sais $pas $que je sais</li>\n";
$body .= "</ol></div>\n";

$body .= "<p><i>Il n'y a pas de bonne fa&ccedil;on de faire quelque chose qui ne fonctionne pas</i> (Sandrine Donzel).\n";
$body .= "Cela veut dire que si apr&egrave;s 2 essais (appel, demande, etc), cela ne marche pas, il faut changer de\n";
$body .= "m&eacute;thode/strat&eacute;gie.</p>\n";

$body .= "<p>Quand les parents sont en d&eacute;saccord, les enfants peuvent le comprendre par le non-verbal.\n";
$body .= "Il faut donc bien g&eacute;rer et communiquer avec son partenaire dans ce genre de situation.\n";
$body .= "On peut le faire devant l'enfant.</p>\n";

$body .= "<p>C'est important de ne pas &ecirc;tre connect&eacute; en permanence, le cerveau a besoin de pauses.</p>\n";

$body .= "<!-- H3 stress -->\n";
$body .= "<h3>Stress</h3>\n";
$body .= "<p>Le stress est un m&eacute;canisme de survie.\n";
$body .= "Il permet la fuite ou le combat (gr&acirc;ce &agrave; des hormones d'&eacute;nergie et anti-inflammatoires),\n";
$body .= "il acc&eacute;l&egrave;re le coeur et la respiration et provoque un afflux sanguin dans les membres.\n";
$body .= "Mais aujourd'hui il y a trop de stress.\n";
$body .= "Il faut comprendre les facteurs d&eacute;clencheurs de stress pour leur apporter une r&eacute;ponse\n";
$body .= "appropri&eacute;e.</p>\n";
$body .= "<p>Pour identifier les situations &agrave; stress, on peut utiliser le mn&eacute;motechnique CINE: quel CINE je me fais\n";
$body .= "face &agrave; mon stress?</p>\n";
$body .= "<div>\n";
$body .= $page->butler->tableOpen();
    $body .= $page->butler->rowOpen();
        $body .= $page->butler->cell("perte de&nbsp;", array("class" => "cineLeft"));
        $body .= $page->butler->cell("C", array("class" => "cineCenter"));
        $body .= $page->butler->cell("ontr&ocirc;le", array("class" => "cineRight"));
    $body .= $page->butler->rowClose();
    $body .= $page->butler->rowOpen();
        $body .= $page->butler->cell();
        $body .= $page->butler->cell("I", array("class" => "cineCenter"));
        $body .= $page->butler->cell("mpr&eacute;visibilit&eacute;", array("class" => "cineRight"));
    $body .= $page->butler->rowClose();
    $body .= $page->butler->rowOpen();
        $body .= $page->butler->cell();
        $body .= $page->butler->cell("N", array("class" => "cineCenter"));
        $body .= $page->butler->cell("ouveaut&eacute;", array("class" => "cineRight"));
    $body .= $page->butler->rowClose();
    $body .= $page->butler->rowOpen();
        $body .= $page->butler->cell("menace pour l'", array("class" => "cineLeft"));
        $body .= $page->butler->cell("E", array("class" => "cineCenter"));
        $body .= $page->butler->cell("go (ne pas se sentir &agrave; la hauteur)", array("class" => "cineRight"));
    $body .= $page->butler->rowClose();
$body .= $page->butler->tableClose();
$body .= "</div>\n";
$body .= "<p>Le cerveau ne voit <b>pas de diff&eacute;rences</b> entre le r&eacute;el et l'imaginaire, les m&ecirc;mes aires\n";
$body .= "c&eacute;r&eacute;brales sont activ&eacute;es.\n";
$body .= "Imaginer une situation &agrave; stress va donc provoquer la m&ecirc;me r&eacute;action de stress que de vivre ladite\n";
$body .= "situation.</p>\n";
$body .= "<p>Lors de situation stressante, dans un 2e temps, il est important de trouver des solutions.\n";
$body .= "Cela permettra au cerveau d'int&eacute;grer l'information \"il y a autre chose &agrave; faire que fuire/combattre,\n";
$body .= "je peux me calmer.\"</p>\n";

$body .= "<!-- H4 en cas de stress -->\n";
$body .= "<h4>En cas de stress</h4>\n";

$body .= "<div><ul>\n";
    $body .= "<li>Bouger: sport, danse, jeu avec les enfants...</li>\n";
    $body .= "<li>Respirer profond&eacute;ment</li>\n";
    $body .= "<li>Rire (seul, avec des amis...)</li>\n";
    $body .= "<li>Aider les autres</li>\n";
$body .= "</ul></div>\n";

$body .= "<p>Au quotidien, on peut aussi prendre le temps de faire 6 respirations par minute pendant 5 minutes, 3 fois par\n";
$body .= "jour.</p>\n";
$body .= "<p>Attention &agrave; l'accumulation de frustrations, cela peut mener &agrave; une explosion de col&egrave;re.</p>\n";
$body .= "<p>Il est important de savoir quelles parties du corps s'activent quand la col&egrave;re &eacute;merge, et quelles\n";
$body .= "parties quand la col&egrave;re explose.\n";
$body .= "Cela permet d'identifier quand la col&egrave;re monte, et on peut alors aller s'isoler et respirer profond&eacute;ment.\n";
$body .= "Quand on est redevenu calme, on peut analyser la situation et ainsi communiquer clairement &agrave; l'enfant la raison\n";
$body .= "de notre col&egrave;re.</p>\n";
$body .= "<p>La col&egrave;re du parent cache souvent une autre &eacute;motion.\n";
$body .= "Il est donc important une fois le calme revenu en nous d'analyser quelle &eacute;motion se cachait derri&egrave;re notre\n";
$body .= "col&egrave;re.</p>\n";

$body .= "<p>Pour &eacute;viter le stress au quotidien:</p>\n";
$body .= "<div><ul>\n";
$body .= "<li>au r&eacute;veil, en milieu de matin&eacute;e, au d&icirc;ner, en milieu d'apr&egrave;s-midi: prendre 5 minutes pour\n";
$body .= "faire des respirations</li>\n";
$body .= "<li>avant de rentrer &agrave; la maison: s'arr&ecirc;ter et respirer tranquillement pour &ecirc;tre plus disponible pour la\n";
$body .= "famille</li>\n";
$body .= "<li>prendre du temps de qualit&eacute; (jouer, discuter) et &eacute;ventuellement repousser les t&acirc;ches de 10 minutes\n";
$body .= "(devoirs, repas...)</li>\n";
$body .= "<li>avant de dormir: &eacute;crire 3-5 choses positives de la journ&eacute;e dans un cahier.</li>\n";
$body .= "</ul></div>\n";

$body .= "<p>En cas de gros stress, trouver ma fa&ccedil;on de d&eacute;charger (bouger, se promener, danser, courir):</p>\n";
$body .= "<div><ul>\n";
    $body .= "<li>respirations synchronis&eacute;es: inspiration, bloquer, contracter gentiment tout le corps, surtout les bras et\n";
    $body .= "les poings, expiration en rel&acirc;chant la tension</li>\n";
    $body .= "<li>les <a href=\"valises\">valises &agrave; contrari&eacute;t&eacute;</a></li>\n";
    $body .= "<li>se trouver un mot-ressource qui, une fois visualis&eacute; mentalement, aide &agrave; apaiser le stress</li>\n";
    $body .= "<li>masser au moins 2 minutes le muscle entre le pouce et l'index en respirant profond&eacute;ment</li>\n";
    $body .= "<li>Fleurs de Bach de secours</li>\n";
$body .= "</ul></div>\n";



$body .= "<!-- H2 Poser de limites et cooperer -->\n";
$body .= "<h2>Poser des limites et coop&eacute;rer</h2>\n";

$body .= "<p>De nombreux conflits du quotidien viennent d'un manque de coop&eacute;ration.</p>\n";

$body .= "<div class=\"framed\"><p><b>ATTENTION:</b></p>\n";
$body .= "Il est dangereux de dire \"les enfants doivent ob&eacute;ir\".\n";
$body .= "Cela implique une soumission aux parents,\n";
$body .= "et va apprendre aux enfants &agrave; ob&eacute;ir aux ordres sans en comprendre leurs sens et leurs implications.\n";
$body .= "On peut rester ferme sur nos besoins, mais souples sur les solutions pour les combler.\n";
$body .= "Cela va privil&eacute;gier chez l'enfant le libre-arbitre, l'autonomie, la responsabilit&eacute; et la\n";
$body .= "cr&eacute;ativit&eacute;.\n";
$body .= "</div>\n";

$body .= "<p>Il est important de mettre un cadre, pour que l'enfant puisse faire ses exp&eacute;riences en s&eacute;curit&eacute;\n";
$body .= "sous le regard des parents d&eacute;tendus.</p>\n";
$body .= "<p>L'enfant comprend/int&egrave;gre mieux une r&egrave;gle avec un sens.\n";
$body .= "Par exemple:</p>\n";
$body .= "<div><ul>\n";
    $body .= "<li>NE PAS: c'est interdit de toucher aux briquets</li>\n";
    $body .= "<li>MAIS: le feu du briquet br&ucirc;le, je ne veux pas que tu te fasses mal</li>\n";
$body .= "</ul></div>\n";

$body .= "<p>L'adulte &eacute;vite les peurs et les col&egrave;res avec des r&egrave;gles claires.</p>\n";
$body .= "<p>C'est important de discuter en couple, voire en famille, pour exprimer ce qui est acceptable ou pas.\n";
$body .= "On peut utiliser l'image du feu tricolore:</p>\n";
$body .= "<div><ul>\n";
    $body .= "<li><b>ROUGE:</b> inacceptable et non-n&eacute;gociable.\n";
    $body .= "Cela concerne g&eacute;n&eacute;ralement la s&eacute;curit&eacute;, le respect, l'hygi&egrave;ne,\n";
    $body .= "parfois aussi les valeurs et les conventions sociales.\n";
    $body .= "Ces r&egrave;gles sont ind&eacute;pendantes du contexte et de l'&eacute;tat &eacute;motionel du parent.\n";
    $body .= "Elles &eacute;voluent avec l'&acirc;ge de l'enfant.\n";
    $body .= "On peut appliquer les 4C:\n";
    $body .= "<b>C</b>laires,\n";
    $body .= "<b>C</b>onnues d'avance,\n";
    $body .= "<b>C</b>oh&eacute;rentes,\n";
    $body .= "<b>C</b>ons&eacute;quences.\n";
    $body .= "</li>\n";

    $body .= "<li><b>ORANGE:</b> exceptionnelement tol&eacute;r&eacute;.\n";
    $body .= "Ces r&egrave;gles sont &agrave; clarifier ensemble.\n";
    $body .= "A chaque exception, il faut pr&eacute;ciser &agrave; l'enfant que la d&eacute;rogation est exceptionnelle.\n";
    $body .= "</li>\n";

    $body .= "<li><b>VERT:</b> souhaitable et encourag&eacute;.\n";
    $body .= "Les r&egrave;gles de vie des valeurs &agrave; transmettre, les comportements attendus\n";
    $body .= "(d&eacute;j&agrave; acquis ou pas encore).\n";
    $body .= "</li>\n";
$body .= "</ul></div>\n";


$body .= "<p>On peut faire un conseil de famille pour r&eacute;viser les r&egrave;gles.\n";
$body .= "Si l'enfant est impliqu&eacute; dans le processus, il respectera mieux les r&egrave;gles, et cela aide l'adulte\n";
$body .= "&agrave; clarifier les r&egrave;gles et leurs sens.</p>\n";

$body .= "<p>Les punitions sont contre-productives.\n";
$body .= "Elles n'engendrent que des sentiments n&eacute;gatifs envers l'adulte.\n";
$body .= "L'enfant va prendre de l'&eacute;nergie pour sa rancoeur/vengeance/pas se faire prendre la prochaine fois.\n";
$body .= "La punition m&egrave;ne &agrave; une rebellion/soumission et &agrave; une d&eacute;gradation des liens familiaux.</p>\n";
$body .= "<p>La sanction est une r&eacute;ponse plus appropri&eacute;e.\n";
$body .= "Elle d&eacute;coule naturellement des cons&eacute;quences du comportement.\n";
$body .= "Elle a une vis&eacute;e r&eacute;paratrice sur la base d'une r&egrave;gle connue et comprise.</p>\n";


$body .= "<div class=\"framed\">\n";
$body .= "<p>Les violences &eacute;ducatives ordinaires sont dangereuses car elles transmettent les messages suivants\n";
$body .= "tr&egrave;s puissants:</p>\n";
$body .= "<div><ul>\n";
    $body .= "<li>j'ai le droit d'utiliser la violence quand je ne suis pas satisfait</li>\n";
    $body .= "<li>la violence est un moyen de r&eacute;soudre les probl&egrave;mes</li>\n";
    $body .= "<li>les plus forts dominent</li>\n";
    $body .= "<li>on a le droit d'aimer et de faire du mal</li>\n";
$body .= "</ul></div>\n";
$body .= "</div>\n";


$body .= "<p>Les r&eacute;compenses ne fonctionnent pas sur du long terme:\n";
$body .= "la motivation est externe &agrave; l'enfant, pour avancer il faut qu'il trouve la motivation en lui.\n";
$body .= "Pour fonctionner, les r&eacute;compenses devront &ecirc;tre de plus en plus importantes et\n";
$body .= "quelque chose que l'enfant est incapable d'acqu&eacute;rir seul (attention car l'enfant grandit).</p>\n";

$body .= "<p>Le parent doit s'affirmer avec respect:</p>\n";
$body .= "<div><ul>\n";
    $body .= "<li>c'est important d'exprimer ce qu'on ressent &agrave; nos enfants</li>\n";
    $body .= "<li>il faut &eacute;viter les critiques, les reproches, lui faire la morale</li>\n";
    $body .= "<li>il faut essayer de lui parler clairement, lui d&eacute;crire la situation</li>\n";
$body .= "</ul></div>\n";
$body .= "<p>Par exemple:</p>\n";
$body .= "<div><ul>\n";
    $body .= "<li>NE PAS: tes jouets tra&icirc;nent partout &agrave; l'entr&eacute;e de ta chambre</li>\n";
    $body .= "<li>MAIS: il y a 3 poup&eacute;es, 1 poussette et 2 peluches devant ta porte, je ne peux pas rentrer dans ta\n";
$body .= "chambre</li>\n";
$body .= "</ul></div>\n";
$body .= "<p>C'est important de ne pas faire de jugement dans ma description.\n";
$body .= "On coop&egrave;re plus volontiers quand on ne se sent pas attaqu&eacute;.</p>\n";

$body .= "<p>i-message (message-je, messaJe): le probl&egrave;me du parent est qu'il a tendance &agrave; parler \"sur\" l'enfant.\n";
$body .= "La solution est d'exprimer clairement ce qui se passe en nous.</p>\n";
$body .= "<p>Le i-message se contruit comme ceci:</p>\n";
$body .= "<div><ol>\n";
    $body .= "<li>Je d&eacute;cris ce que je vois, la situation</li>\n";
    $body .= "<li>Je d&eacute;cris, je nomme ce que je ressens</li>\n";
    $body .= "<li>Je d&eacute;cris les cons&eacute;quences</li>\n";
$body .= "</ol></div>\n";

$body .= "<p>Il faut pr&eacute;venir plut&ocirc;t que rugir: l'enfant a une vision du monde diff&eacute;rente de l'adulte.\n";
$body .= "Il ne se rend pas compte de ce qu'on attend de lui.\n";
$body .= "Il faut lui exprimer clairement nos attentes, cela l'aidera &agrave; comprendre et &agrave; s'y conformer.\n";
$body .= "Il faut donc d&eacute;crire la situation avant qu'elle ne devienne un probl&egrave;me pour moi.</p>\n";

$body .= "<p>Quelques outils pour une bonne coop&eacute;ration:</p>\n";
$body .= "<div><ul>\n";
    $body .= "<li>&ecirc;tre affirmatif plut&ocirc;t que n&agrave;gatif</li>\n";
    $body .= "<li>proposer des choix/alternatives plut&ocirc;t qu'ordonner</li>\n";
    $body .= "<li>passer du temps de qualit&eacute; avec l'enfant</li>\n";
    $body .= "<li>l'humour et le jeu permettent d'entrer dans l'univers de l'enfant:<br>\n";
    $body .= "\"Donner une tonalit&eacute; ludique &agrave; nos demandes invite au rire, &agrave; la l&eacute;gert&eacute;, et bien\n";
    $body .= "souvent &agrave; l'ex&eacute;cution de la t&acirc;che demand&eacute;e.\"<br>\n";
    $body .= "Parler avec une dr&ocirc;le de voix, une langue imaginaire, en faisant l'idiot...\n";
    $body .= "Pas &agrave; chaque fois, mais briser la routine &agrave; l'aide du jeu pla&icirc;t &eacute;norm&eacute;ment aux\n";
    $body .= "enfants</li>\n";
    $body .= "<li id=\"compliment\">renforcer le positif: c'est important de relever les comportements positifs aussi.\n";
    $body .= "Pour cela, on peut utiliser le i-message en insistant sur les faits et sur mon ressenti.\n";
    $body .= "On peut aussi le complimenter efficacement, mais pour cela il faut veiller &agrave; rester descriptif sur ce que je\n";
    $body .= "souhaite valoriser dans le comportement.\n";
    $body .= "Cela permet &agrave; l'enfant de rejouer l'&eacute;v&egrave;nement dans sa t&ecirc;te,\n";
    $body .= "pour qu'il puisse comprendre et int&eacute;grer,\n";
    $body .= "puis s'accorder &agrave; lui-m&ecirc;me le compliment.</li>\n";
$body .= "</ul></div>\n";

$body .= "<p>Lorsque des conflits arrivent:</p>\n";
$body .= "<div><ul>\n";
    $body .= "<li>Si besoin, s'isoler</li>\n";
    $body .= "<li>Faire de grandes et lentes respirations</li>\n";
    $body .= "<li>Quand l'&eacute;motion est redevenue g&eacute;rable, se poser des questions:\n";
    $body .= "<ul>\n";
    $body .= "<li>qu'est-ce qui est si important pour moi dans cette situation? Clarifier les valeurs/besoins frustr&eacute;s</li>\n";
    $body .= "<li>quelle comp&eacute;tence manque &agrave; mon enfant pour changer de comportement? Gestion de la col&egrave;re?\n";
        $body .= "Li&eacute; &agrave; l'&acirc;ge?</li>\n";
    $body .= "</ul></li>\n";
    $body .= "<li>En fonction des r&eacute;ponses aux pr&eacute;c&eacute;dentes questions, pr&eacute;parer son i-message</li>\n";
$body .= "</ul></div>\n";

$body .= "<p>Au quotidien, pour avoir une bonne relation avec l'enfant:</p>\n";
$body .= "<div><ul>\n";
    $body .= "<li>Lorsque je ne suis pas content, je communique avec i-message</li>\n";
    $body .= "<li>Lui faire au moins 3 compliments/valorisations par jour</li>\n";
    $body .= "<li>Prendre du temps de qualit&eacute; (jeux, lecture, c&acirc;lins, balade, sport, cuisine...)</li>\n";
    $body .= "<li>Quand il est boulvers&eacute;, &eacute;couter et descendre de ma montagne</li>\n";
    $body .= "<li>Le responsabiliser avec des sanctions</li>\n";
    $body .= "<li>Si malgr&eacute; tout je d&eacute;rape (r&eacute;action/geste brusque): s'excuser aupr&egrave;s de l'enfant et\n";
    $body .= "r&eacute;fl&eacute;chir ensemble &agrave; comment faire autrement si la situation se reproduit</li>\n";
$body .= "</ul></div>\n";


$body .= "<!-- H3 Mettre en place l'education positive en 4 semaines -->\n";
$body .= "<h3>Mettre en place l'&eacute;ducation positive en 4 semaines</h3>\n";

$body .= "<!-- H4 Semaine 1 -->\n";
$body .= "<h4>Semaine 1: observations</h4>\n";
$body .= "<div class=\"checkbox\"><ul>\n";
    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;clarifier les capacit&eacute;s du cerveau de l'enfant</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;observer mon comportement quand l'enfant exprime ses &eacute;motions;\n";
    $body .= "ai-je tendance &agrave; essayer de le calmer ou &agrave; accueillir son &eacute;motion? Suis-je &agrave; l'aise avec\n";
    $body .= "toutes ses &eacute;motions ou certaines g&eacute;n&egrave;rent plus de r&eacute;actionnel?</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;combien de temps de qualit&eacute; en semaine et le week-end est-ce que\n";
    $body .= "je passe chaque jour avec les enfants?\n";
    $body .= "Est-ce que je fais des jeux avec eux?</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;s'il y a un conflit dans les besoins adulte-enfant, que se passe-t-il?\n";
    $body .= "Dispute, punition...?</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;combien de temps je m'accorde &agrave; moi-m&ecirc;me chaque jour?\n";
    $body .= "Y aurait-il une activit&eacute; que je voudrais reprendre?</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;comment, quand, o&ugrave; et avec qui puis-je me d&eacute;tendre?</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;suis-je souvent agac&eacute; ou en col&egrave;re dans ma vie\n";
    $body .= "familiale?</li>\n";
$body .= "</ul></div>\n";


$body .= "<!-- H4 Semaine 2 -->\n";
$body .= "<h4>Semaine 2: &eacute;coute</h4>\n";
$body .= "<div class=\"checkbox\"><ul>\n";
    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;durant un jour, lorsque l'enfant a un probl&egrave;me, l'&eacute;couter\n";
    $body .= "sans l'interrompre avec contact visuels et \"accus&eacute;s de r&eacute;ception\"</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;les jours suivants, quand l'enfant est d&eacute;bord&eacute;\n";
    $body .= "&eacute;motionnellement, reformuler ce qu'il ressent</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;avoir beaucoup de contacts physiques (jeux, c&acirc;lins)</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;prendre conscience des situations qui me font r&eacute;agir\n";
    $body .= "&eacute;motionnellement en tenant compte de mes r&eacute;actions corporelles et de mes pens&eacute;es.</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;faire chaque jour quelque chose rien que pour moi (m&ecirc;me si c'est\n";
    $body .= "seulement quelques minutes)</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;&ecirc;tre attentif &agrave; mes besoins de sommeil, de repos\n";
     $body .= "et de partage</li>\n";
$body .= "</ul></div>\n";


$body .= "<!-- H4 Semaine 3 -->\n";
$body .= "<h4>Semaine 3: r&egrave;gles</h4>\n";
$body .= "<div class=\"checkbox\"><ul>\n";
    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;faire le point sur les r&egrave;gles de vie que l'on souhaite\n";
    $body .= "transmettre aux enfants</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;si les enfants sont assez matures, faire un conseil de famille avec eux\n";
    $body .= "pour poser et discuter les r&egrave;gles ensemble</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;si les enfants sont trop jeunes, lorsque la situation se\n";
    $body .= "pr&eacute;sente, formuler la r&egrave;gle (une &agrave; la fois)</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;quand le comportement de l'enfant est inacceptable, utiliser le\n";
    $body .= "i-message pour le lui communiquer</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;valoriser les comportement souhaitables en d&eacute;crivant\n";
    $body .= "concr&egrave;tement et en exprimant mes &eacute;motions positives</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;cr&eacute;er des moments d'attention exclusive pour l'enfant</li>\n";
$body .= "</ul></div>\n";


$body .= "<!-- H4 Semaine 4 -->\n";
$body .= "<h4>Semaine 4: coop&eacute;ration</h4>\n";
$body .= "<div class=\"checkbox\"><ul>\n";
    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;utiliser des consignes affirmatives, &eacute;viter les \"ne pas\"</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;proposer des choix/alternatives plut&ocirc;t que d'imposer</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;utiliser l'humour, l'imaginaire, le jeu pour entrer en relation avec\n";
    $body .= "l'enfant</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;s'il refuse de coop&eacute;rer, &eacute;couter son probl&egrave;me</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;pour la routine quotidienne, faire un tableau ou une affiche avec des\n";
    $body .= "mots ou des pictogrammes</li>\n";

    $body .= "<li><input type=\"checkbox\" name=\"e[]\">&nbsp;si la non-coop&eacute;ration de l'enfant est en conflit avec mes\n";
    $body .= "besoins, utiliser le i-message pour le lui communiquer</li>\n";
$body .= "</ul></div>\n";


echo $body;
?>
