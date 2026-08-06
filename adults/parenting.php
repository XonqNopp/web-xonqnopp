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

$body .= "<p>Pour que les enfants n'ait pas a demander &agrave; des adultes de toucher leur corps,\n";
$body .= "il est important qu'ils deviennent autonome pour se laver et pour les toilettes.\n";
$body .= "Les enfants aprennent en imitant, on peut donc mimer sur nous les gestes &agrave; faire pour les accompagner.\n";
$body .= "</p>\n";

$body .= "<p>Lorsque l'enfant nous raconte quelque chose de bizarre ou choquant,\n";
$body .= "la premir&egrave;re chose &agrave; lui r&eacute;pondre est \"Je te crois.\"\n";
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





// TODO signe de detresse
// TODO Angie dans un bar???


echo $body;
?>
