<?php
require_once("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->logger->levelUp(6);


$page->cssHelper->push("index");

$body = $page->bodyBuilder->goHome("..");
$body .= $page->htmlHelper->setTitle("Les &eacute;l&eacute;phants");
$page->htmlHelper->hotBooty();

    /*** contents ***/
    $querries = array(
    "Qu&#039;est ce qui est jaune et qui traverse les murs&nbsp;?" =>
        "Une banane magique.",

    "Pourquoi les bananes sont-elles tordues&nbsp;?" =>
        "Pour rentrer dans les pelures.",

    "Qu&#039;est-ce qui est transparent et qui court dans la for&ecirc;t&nbsp;?" =>
        "Un troupeau de vitres...",

    "Pourquoi le koala est tomb&eacute; de l&#039;arbre&nbsp;?" =>
        "Parce qu&#039;il est mort.",

    "Pourquoi le deuxi&egrave;me koala est tomb&eacute; de l&#039;arbre&nbsp;?" =>
        "Parce qu&#039;il a &eacute;t&eacute; percut&eacute; par le premier.",

    "Pourquoi le troisi&egrave;me koala est tomb&eacute; de l&#039;arbre&nbsp;?" =>
        "Parce qu&#039;il a cru que c&#039;&eacute;tait un jeu.",

    "Pourquoi un &eacute;l&eacute;phant ne peut-il pas voir le bout de sa trompe&nbsp;?" =>
        "Parce qu&#039;il est n&eacute; avec d&eacute;fenses d&#039;ivoire...",

    "Pourquoi les &eacute;l&eacute;phants se prom&egrave;nent-ils en troupeau&nbsp;?" =>
        "Parce que c&#039;est celui du milieu qui a la radio.",

    "Comment on met un &eacute;l&eacute;phant dans un frigo en 3 temps&nbsp;?" =>
        "On ouvre la porte, on met l&#039;&eacute;l&eacute;phant et on referme la porte.",

    "Comment sait-on qu&#039;un &eacute;l&eacute;phant rose se cache dans un frigo&nbsp;?" =>
        "On ne peut pas, il est cach&eacute; au milieu des fraises.",

    "Comment voit-on qu&#039;il y a deux &eacute;l&eacute;phants dans un frigo&nbsp;?" =>
        "La porte coince...",

    "Comment on met 4 &eacute;l&eacute;phants dans une 2CV&nbsp;?" =>
        "2 devant, 2 derri&egrave;re.",

    "Et 6 &eacute;l&eacute;phants dans une 2CV&nbsp;?" =>
        "2 devant, 2 derri&egrave;re et 2 sur le toit.",

    "Comment voit-on qu&#039;il y a quatre &eacute;l&eacute;phants dans une 2CV&nbsp;?" =>
        "Elle peine dans les c&ocirc;tes",

    "Et quel est l&#039;&eacute;l&eacute;phant qui conduit&nbsp;?" =>
        "Celui qui a son permis.",

    "Pourquoi les &eacute;l&eacute;phants sont-ils gris&nbsp;?" =>
        "Pour qu&#039;on ne les confonde pas avec des fraises des bois.",

    "Quels sont les &eacute;l&eacute;phants qui ont quand meme peur d&#039;etre pris pour des fraises des bois&nbsp;?" =>
        "Ceux avec des yeux rouges.",

    "Comment fait un &eacute;l&eacute;phant pour passer inapercu&nbsp;?" =>
        "Il met des lunettes noires.",

    "Comment on met un girafe dans un frigo en 4 temps&nbsp;?" =>
        "On ouvre la porte, on enl&egrave;ve l&#039;&eacute;l&eacute;phant, on met la girafe et on referme la porte.",

    "Quelle est la plus grosse diff&eacute;rence entre un &eacute;l&eacute;phant d&#039;Asie et un &eacute;l&eacute;phant d&#039;Afrique&nbsp;?" =>
        "3000 km",

    "Pourquoi les &eacute;l&eacute;phants se prom&egrave;nent en 2CV et pas en v&eacute;lo&nbsp;?" =>
        "Parce qu&#039;ils n&#039;ont pas de petit doigt pour actionner la sonnette.",

    "Pourquoi les canards ont-ils les pattes si larges&nbsp;?" =>
        "Pour eteindre les buissons enflamm&eacute;s.",

    "Comment font les &eacute;l&eacute;phants pour se cacher dans les champs de fraise&nbsp;?" =>
        "Ils se peignent les ongles en rouge.",

    "Tarzan organise une grande reunion dans la jungle avec tous les animaux. Quel animal manque&nbsp;?" =>
        "La girafe, elle est rest&eacute;e dans le frigo.",

    "Comment s&#039;apercoit-on qu&#039;on a un &eacute;l&eacute;phant dans son lit&nbsp;?" =>
        "Il a un E brode sur son pyjama",

    "Pourquoi les rhinoc&eacute;ros se prom&egrave;nent-ils en troupeau&nbsp;?" =>
        "Pour faire croire aux &eacute;l&eacute;phants que eux aussi ils ont une radio.",

    "Tu es au bord d&#039;une riviere habit&eacute;e par de nombreux crocodiles et tu veux aller de l&#039;autre c&ocirc;t&eacute;, il n&#039;y a aucun pont, comment tu fais&nbsp;?" =>
        "Ben tu nages, les crocos sont tous chez Tarzan...",

    "Pourquoi les &eacute;l&eacute;phants prennent-ils des raquettes pour aller &agrave; la plage&nbsp;?" =>
        "Pour ne pas s&#039;enfoncer dans le sable.",

    "Comment on sait s&#039;il y a des &eacute;l&eacute;phants dans un magasin de porcelaine&nbsp;?" =>
        "On regarde s&#039;il y a une 2CV parqu&eacute;e devant.",

    "Comment font les &eacute;l&eacute;phants pour grimper aux arbres&nbsp;?" =>
        "Ils mettent des chaussures de gym.",

    "Comment fait-on pour voir un &eacute;l&eacute;phant dans un champ de fraises&nbsp;?" =>
        "On ne peut pas, il est bien cach&eacute;.",

    "Comment font les &eacute;l&eacute;phants pour grimper sur un arbre de 20m&nbsp;?" =>
        "Ils grimpent sur un arbre de 30m et saute sur l&#039;arbre de 20m.",

    "Et comment font les &eacute;l&eacute;phants pour descendre des arbres&nbsp;?" =>
        "Ils s&#039;asseyent sur une feuille et ils attendent l&#039;automne.",

    "Pourquoi les &eacute;l&eacute;phants mettent-ils des chaussettes roses&nbsp;?" =>
        "Parce que le blanc c&#039;est salissant.",

    "Comment fait-on pour mettre 4 hippopotames dans une 2CV&nbsp;?" =>
        "On enleve les &eacute;l&eacute;phants.",

    "Comment fait un &eacute;l&eacute;phant pour traverser un &eacute;tang&nbsp;?" =>
        "Il retire ses chaussures de gym et saute de n&eacute;nuphar en n&eacute;nuphar !",

    "Pourquoi ne faut-il pas se promener dans la jungle en automne&nbsp;?" =>
        "Parce que les feuilles tombent des arbres avec des &eacute;l&eacute;phants dessus",

    "Pourquoi les autruches mettent-elles la t&ecirc;te dans le sable&nbsp;?" =>
        "Pour discuter avec les &eacute;l&eacute;phants qui ont oubli&eacute; leurs raquettes.",

    "Pourquoi les &eacute;l&eacute;phants ont-ils les pattes si larges&nbsp;?" =>
        "Pour eteindre les canards enflamm&eacute;s.",

    "Quelle est la diff&eacute;rence entre un train et un ours&nbsp;?" =>
        "Tu as deja vu un ours avec des vitres&nbsp;??",

    "Pourquoi les ours n&#039;ont pas de vitres&nbsp;?" =>
        "Parce qu&#039;elles courent dans la foret...",

    "Pourquoi les &eacute;l&eacute;phants nagent-ils sur le dos&nbsp;?" =>
        "Pour ne pas mouiller leurs chaussures de gym.",

    "Pourquoi les crocodiles sont plats&nbsp;?" =>
        "Parce qu&#039;ils se sont promen&eacute;s dans la jungle en automne.",

    "Pourquoi l&#039;&eacute;l&eacute;phant du Zoo de B&acirc;le a des chaussettes vertes ces temps&nbsp;?" =>
        "Parce que les roses sont sales.",

    "La jungle a entierement brul&eacute;e. Quel est lanimal qui a survecu&nbsp;?" =>
        "La girafe, elle est encore dans le frigo.",

    "Qu&#039;a dit Tarzan en voyant arriver les &eacute;l&eacute;phants&nbsp;?" =>
        "Oh, voil&agrave; les &eacute;l&eacute;phants.",

    "Qu&#039;a dit Tarzan en voyant arriver les &eacute;l&eacute;phants avec des lunettes noires&nbsp;?" =>
        "Rien, car ils ne les a pas reconnus.",

    "Pourquoi les &eacute;l&eacute;phants se peignent-ils le ventre en bleu ciel&nbsp;?" =>
        "Pour ne pas se faire rep&eacute;rer quand ils volent.",

    "Qu&#039;est ce qu&#039;une grosse tache rouge sur un mur&nbsp;?" =>
        "Une tomate qui s&#039;est prise pour une banane magique.",

    "Comment font les &eacute;l&eacute;phants pour grimper aux cerisiers&nbsp;?" =>
        "Ils mettent des baskets.",

    "Pourquoi les &eacute;l&eacute;phants grimpent-ils aux cerisiers&nbsp;?" =>
        "Pour manger les cerises.",

    "Comment les &eacute;l&eacute;phants se camouflent-ils dans un cerisier en &eacute;t&eacute;&nbsp;?" =>
        "Ils se peignent les testicules en rouge.",

    "Comment chasser les &eacute;l&eacute;phants bleus&nbsp;?" =>
        "Avec un fusil &agrave; &eacute;l&eacute;phants bleus.",

    "Comment chasser les &eacute;l&eacute;phants blancs&nbsp;?" =>
        "En les &eacute;tranglant, ils deviennent tout bleus, apr&egrave;s tu sais quoi faire...",

    "Comment chasser les &eacute;l&eacute;phants noirs&nbsp;?" =>
        "Il suffit de leur faire peur, ils en palissent, et les &eacute;l&eacute;phants blancs...",

    "Comment chasser les &eacute;l&eacute;phants verts&nbsp;?" =>
        "On leur paye un pot, bourr&eacute;s ils sont noirs, c&#039;est alors tout simple...",

    "Comment chasser les &eacute;l&eacute;phants a rayures&nbsp;?" =>
        "Serieusement, t&#039;as deja vu des &eacute;l&eacute;phants a rayures&nbsp;?",

    "Pourquoi l&#039;Australien est-il tomb&eacute; de son v&eacute;lo&nbsp;?" =>
        "Parce qu&#039;il s&#039;est pris 3 koalas dans la figure.",

    "Quel est le cri le plus strident de la jungle&nbsp;?" =>
        "Une girafe qui mange des cerises."
    );

// prepare body
$body .= "<div class=\"elephant\">Mettre la souris sur le texte pour voir la r&eacute;ponse</div>\n";

$body .= "<div class=\"elephant_table\">\n";
foreach($querries as $question => $answer) {
    $body .= "<div class=\"elephant_query\">\n";
    $body .= "<a title=\"$answer\">\n";
    $body .= "$question\n";
    $body .= "</a>\n";
    $body .= "</div>\n";
    $body .= "<div class=\"upsidedown\">$answer</div>\n";
    $body .= "\n";
}
$body .= "</div>\n";

echo $body;
?>
