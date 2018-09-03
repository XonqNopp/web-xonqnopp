<?php
/* TODO:
 */
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
//$page->initDB();
//// debug
//$page->initHTML();
//$page->LogLevelUp(6);
//// CSS paths
$page->CSS_ppJump();
//$page->CSS_ppWing();
//// init body
$body = "";


//// GoHome
$gohome = new stdClass();
$gohome->rootpage = "..";
$body .= $page->GoHome($gohome);
//// Set title and hot booty
$body .= $page->SetTitle("&Eacute;ducation positive");// before HotBooty
$page->HotBooty();

echo $body;
?>

<p>Petit r&eacute;sum&eacute; <b>subjectif</b> de:
<i>Mon p'tit cahier d'&Eacute;ducation positive</i>,
Christine Klein,
aux Editions Solar.</p>



<!-- H2 Education -->
<h2>Education</h2>

<!-- H3 Communication -->
<h3>Communication</h3>
<div><ol>
  <li>On communique <b>toujours</b>! 7% verbal, 38% para-verbal, 55% non-verbal.</li>
  <li>Chaque personne a <b>sa propre</b> vision du monde.<br/>
  soi - humeur - croyances - valeurs - &eacute;ducation - culture - ... - monde</li>
  <li>Chaque comportement a une intention positive</li>
</ol></div>

<!-- H3 Cerveau -->
<h3>Cerveau</h3>
<p>Le cerveau se divise en 3 parties principales:</p>
<div><ol>
  <li>reptilien/archa&iuml;que: combat/fuite</li>
  <li>limbique/&eacute;motionnel</li>
  <li>sup&eacute;rieur (n&eacute;ocortex)</li>
</ol></div>
<p>Le cerveau se sp&eacute;cialise par la fr&eacute;quence des exp&eacute;riences v&eacute;cues, pas par la
qualit&eacute;.</p>
<p>Un enfant <b>n'est pas</b> un petit adulte. ";
Voici un tableau qui explique les capacit&eacute;s de l'enfant par rapport &agrave; son &acirc;ge (p.18-19):</p>

<div class="tablebottom"><table>
  <tr>
    <th>&Acirc;ge</th>
    <th>Comportement</th>
    <th>Explications</th>
    <th>Pistes de changement</th>
  </tr>

  <!-- 0-2 -->
  <tr><td colspan="4" class="fullLine">0-2 ans</td></tr>

  <tr>
    <td rowspan="3">18-24 mois</td>
    <td>hurle &agrave; la moindre frustration</td>
    <td>la partie de son cerveau qui ma&icirc;trise ses impulsions n'est pas encore mature</td>
	<td>pratiquer l'&eacute;coute active: formuler ce qui vient de se passer pour l'aider &agrave; comprendre et a se
    sentir reconnu</td>
  </tr>

  <tr>
    <!--<td>18-24 mois</td>-->
    <td>il me regarde dans les yeux tout en faisant exactement ce que je viens de lui interdire</td>
	<td>l'enfant ne dispose pas du tout du langage ou que tr&egrave;s peu. C"est avec son corps qu'il demande la
    validation de la consigne: c'est ca que tu ne veux pas que je fasse?</td>
    <td>lui confirmer qu'il a bien compris la consigne et lui proposer une autre activit&eacute;</td>
  </tr>

  <tr>
    <!--<td>18-24 mois</td>-->
    <td>il n'&eacute;coute pas quand je lui demande d'arr&ecirc;ter un comportement</td>
    <td>le cerveau ne comprend pas la n&eacute;gation</td>
	<td>formuler les demandes de facon affirmatives et aider l'enfant &agrave; se diriger vers le comportement
    souhait&eacute;</td>
  </tr>

  <!-- 2-4 -->
  <tr><td colspan="4" class="fullLine">2-4 ans</td></tr>

  <tr>
    <td>2-6 ans</td>
    <td>il ne sait pas se tenir tranquille (restaurant, courses...)</td>
	<td>les capacit&eacute;s neuronales de l'enfant ne lui permettent pas de rester calement assis, et un cerveau qui
    s'ennuie trouvera une occupation</td>
	<td>Chercher &agrave; occuper l'enfant autrement plut&ocirc;t que de lui demander de rester calme</td>
  </tr>

  <tr>
    <td>2 ans</td>
    <td>quand il s'amuse &agrave; un endroit (amis, place de jeux), impossible d'en partir sans drame</td>
	<td>il est encore tr&egrave;s difficile &agrave; ce jeune cerveau d'anticiper.
    Les notions de temps et de duree sont encore tres floues</td>
    <td>Installer des routines pour faciliter les moments de transition de la vie courante</td>
  </tr>

  <tr>
    <td>3 ans</td>
    <td>bien qu'il connaisse les r&egrave;gles, il ne les respecte pas</td>
	<td>la partie du cerveau qui reformule la r&egrave;gle est encore mal connect&eacute;e &agrave; la partie du cerveau
    qui permet d'emp&ecirc;cher le geste</td>
	<td>&ecirc;tre parent c'est r&eacute;p&eacute;ter souvent...
	L'enfant a encore besoin que le cadre lui soit rappel&eacute;.
	On peut aussi r&eacute;fl&eacute;chir &agrave; une alternative qui l'aiderait &agrave; se tenir (un jeu, un signal
    convenu d'avance)</td>
  </tr>

  <tr>
    <td>3-4 ans</td>
    <td>il a de nouvelles peurs et il imagine des choses terrifiantes, il fait des cauchemars</td>
	<td>la construction mentale et l'imaginaire se developpent. L'enfant est encore dans la confusion: ce qui existe
    dans son imaginaire existe pour de vrai</td>
	<td>Les actes symboliques sont int&eacute;resants, comme le dessin, la poup&eacute;e &agrave; soucis, le
	pi&egrave;ge &agrave; monstres.
    Se mettre &agrave; l'&eacute;coute pour d&eacute;charger l'intensit&eacute; &eacute;motionnelle de l'enfant.</td>
  </tr>

  <!-- 4-6 -->
  <tr><td colspan="4" class="fullLine">4-6 ans</td></tr>

  <tr>
    <td>4 ans</td>
    <td>il ment en disant que ce n'est pas lui qui a fait telle chose</td>
    <td>l'enfant ne fait pas encore le lien entre ses actes et leurs cons&eacute;quences</td>
	<td>l'aider progressivement &agrave; faire le lien entre ses actions et les cons&eacute;quences qui en
	d&eacute;coulent. Par exemple: tu as pris le feutre dans ta main et il laisse des traces sur le mur</td>
  </tr>

  <tr>
    <td>4 ans</td>
    <td>il parle sans filtre: elle est grosse dame, le monsieur sent mauvais</td>
	<td>&agrave; cet &acirc;ge, les pens&eacute;es se font a haute voix, l'enfant ne peut pas encore penser en silence
    dans sa t&ecirc;te</td>
	<td>l'adulte qui est souvent mal &agrave; l'aise gronde l'enfant. Il n'y a pas grand chose &agrave; faire cependant,
    si ce n'est expliquer &agrave; l'enfant la raison de son embarras, &agrave; distance de la "victime"</td>
  </tr>

  <tr>
    <td>5 ans</td>
    <td>alors qu'il sait parfaitement le faire, il met tr&egrave;s longtemps &agrave; s'habiller</td>
	<td>&agrave; cause de son immaturit&eacute; c&eacute;r&eacute;brale, il se laisse encore distraire tr&egrave;s
    facilement</td>
	<td>l'encourager &agrave; chacune des &eacute;tapes, et pourquoi pas, nommer chacun de ses v&ecirc;tements pour
    l'aider &agrave; se concentrer</td>
  </tr>

  <!-- 6-8 -->
  <tr><td colspan="4" class="fullLine">6-8 ans </td></tr>

  <tr>
    <td>6-7 ans</td>
    <td>il raconte avec aplomb des histoires hallucinantes</td>
	<td>il commence &agrave; utilsier le poteentiel imaginatif de son cerveau mais il distingue encore difficilement la
    realit&eacute; des histoires qu'il se raconte dans la t&ecirc;te</td>
	<td>&eacute;couter son histoire et ne pas h&eacute;siter &agrave; lui faire un petit clin d'oeil pour lui montrer
	que vous n'&ecirc;tes pas dupe.
    Vous pourrez revenir dessus plus tard pour l'aider &agrave; faire la part des choses</td>
  </tr>

  <tr>
    <td>7-8 ans</td>
    <td>il est encore bien maladroit</td>
    <td>le corps de l'enfant grandit et se modifie rapidement; pas facile pour lui de s'adapter sans cesse</td>
    <td>se poser la question: si un ami faisait la m&ecirc;me maladresse, quel discours lui tiendrais-je?</td>
  </tr>

  <tr>
    <td>8 ans</td>
    <td>il fait tout ce qui est interdit</td>
	<td>l'interdit incite &agrave; la transgression et bien que la maturation de leur cerveau le permette maintenant de
	r&eacute;flechir, les enfants ne peuvent pas r&eacute;sister &agrave; l'envie d'aller l&agrave; o&ugrave; se porte
    leur attention (et leurs envies)</td>
    <td>&eacute;tablir des r&egrave;gles plut&ocirc;t que des interdits</td>
  </tr>

</table></div>


<!-- H3 Cles de l'educ -->
<h3>Cl&eacute;s de l'&eacute;ducation positive</h3>
<div><ul>
  <li>&ecirc;tre  l'&eacute;coute des &eacute;motions (les siennes avant celles des autres), savoir les exprimer</li>

  <li>beaucoup d'amour:
  "c'est lorsqu'ils semblent le m&eacute;riter le moins que nos enfants ont le plus besoin d'amour et d'attention."
  (Aletha Solter)
  C&acirc;lins, bisous etc. sont du carburant, pas une r&eacute;compense!</li>

  <li>Ne pas tout accepter: une main ferme et tendre dans un gant de velour.
  Donner du sens aux r&egrave;gles par un recadrage bienveillant mais ferme.</li>

  <li>Une autorit&eacute; d'influence: respect et confiance.
  Par ex. lorsque je m'inqui&egrave;te, plut&ocirc;t que de s'&eacute;nerver et punir,
  lui faire comprendre en essayant de le mettre &agrave; ma place.</li>

  <li>Pas de punitions ni de r&eacute;compenses: coop&eacute;ration plut&ocirc;t que comp&eacute;tition.
  Cela implique une responsabilisation et de l'autonomie pour l'enfant.</li>

</ul></div>


<!-- H2 Emotions -->
<h2>&Eacute;motions</h2>

<p>4 &eacute;motions de base: joie, col&egrave;re, tristesse, peur.</p>
<p>&Eacute;motion est un d&eacute;clencheur, et notre cerveau y apporte 3 r&eacute;ponses: physilogique,
comportementale, cognitive.</p>
<p>Equit&eacute; est plus important que &eacute;galit&eacute;: ce qui compte, ce sont les besoins de chacun.</p>

<div><img src="/pictures/equite.png" alt="equite" /></div>

<p>Lorsqu'on est dans une crise &eacute;motionnelle, on est prisonnier du cerveau &eacute;motionnel, le cerveau
sup&eacute;rieur est hors service.</p>
<p>Il est important d'&eacute;couter (accompagner) les &eacute;motions plut&ocirc;t que de les limiter (les
repousser).</p>
<p>Il faut laisser l'enfant chercher des solutions (ne pas lui donner nos solutions d'adultes) mais l'accompagner dans
ses &eacute;motions.
Sa perception du monde et ses ressources ne sont pas celles d'un adulte, il r&eacute;soudra diff&eacute;remment.</p>
<p>S'ouvrir &agrave; la mani&egrave;re dont l'autre per&ccedil;oit le monde.</p>


<!-- H3 Ecac -->
<h3>&Eacute;coute active</h3>
<p>Aider &agrave; comprendre les &eacute;motions qui se passent en lui, mettre des mots sur ce qu'il vit:
ce qu'il ressent est d&eacute;sagr&eacute;able, mais normal dans cette situation.</p>

<p>Par exemple, avant la rentr&eacute;e de l'&eacute;cole, l'enfant dit qu'il a mal au ventre, qu'il est inquiet:</p>
<div><ul>
  <li>NE PAS: t'inqui&egrave;te pas, c'est rien, ca va aller/passer.</li>
  <li>MAIS: c'est normal d'&ecirc;tre inquiet, les autres aussi. Il y a quelque chose de particulier qui
  t'inqui&egrave;te?</li>
</ul></div>

<p>Il faut privil&eacute;gier les questions ouvertes pour inviter l'enfant &agrave; formuler ses r&eacute;ponses
plut&ocirc;t que laisser l'adulte mener la conversation.</p>
<p>Il est important d'&ecirc;tre <b>vraiment</b> pr&eacute;sent (disponible) pour &eacute;couter l'enfant.</p>

<p>Exemple: une sortie longuement pr&eacute;vue est annul&eacute;e. L'enfant est d&eacute;&ccedil;u et pleure.</p>
<div><ul>
  <li>NE PAS: arr&ecirc;te ton caprice.</li>
  <li>MAIS: discuter de la joie qu'il se faisait &agrave; l'id&eacute;e.</li>
</ul></div>

<p>Reformuler les &eacute;motions de l'enfant pour &ecirc;tre plus pr&egrave;s de son ressenti.</p>
<p>&Eacute;motions exprim&eacute;es, elles se d&eacute;gonflent, cela permet de retrouver le cerveau sup&eacute;rieur et
de pouvoir r&eacute;fl&eacute;chir et chercher des solutions.</p>

<div class="framed">
<!-- H5 Astuce sophrologique -->
<h5>Astuce sophrologie pour g&eacute;rer les &eacute;motions</h5>
<p>Aider l'enfant &agrave; faire le lien entre sensations physiques et &eacute;motions.</p>
<div><ul>
  <li>o&ugrave; ressens-tu [<i>&eacute;motion</i>] dans ton corps?</li>
  <li>si ton/ta [<i>&eacute;motion</i>] avait une couleur, laquelle &ccedil;a serait?</li>
</ul></div>

<p>Quand &eacute;motion commence &agrave; se d&eacute;gonfler, aider l'enfant &agrave; chasser l'inconfort physique (pas
l'&eacute;motion!)</p>
<p>Exercice: sur une longue inspiration, observer les &eacute;motions et les tensions
g&eacute;n&eacute;r&eacute;es&eacute;;
sur une expiration tonique (souffler), lib&eacute;rer les tensions.</p>
<p>Cela permet &agrave; l'enfant de prendre conscience de son &eacute;motion &agrave; la fois verbalement (elle a
&eacute;t&eacute; nomm&eacute;e) et corporellement (il la situe dans son corps).</p>

</div>

<div class="framed"><p><b>ATTENTION:</b></p>
<p>Il ne faut pas laisser l'enfant exprimer ses &eacute;motions brutalement,
mais lui apprendre &agrave; tenir compte des informations fournies par ses &eacute;motions et lui apprendre &agrave;
g&eacute;rer.
Si le corps r&eacute;agit, c'est que quelque chose d'important se passe en lui, il ne peut pas l'ignorer.
Il peut se servir de ce qu'il ressent pour comprendre ce qui est important pour lui dans ce contexte et pour agir en
fonction.</p>
</div>

<p>Pour rappel, derri&egrave;re chaque comportement il y a une intention positive.
Cela signifie qu'un comportement &eacute;quivaut a un besoin.</p>
<p>Si les besoins fondamentaux ne sont pas combl&eacute;s, l'enfant ne peut pas avoir un comportement acceptable.</p>
<p>Si l'enfant a faim/sommeil, il ne pourra pas se comporter correctement et il ne lui sera pas possible de penser
&agrave; autre chose que lui.
Il ne pourra pas r&eacute;pondre &agrave; nos besoins d'adultes.</p>

<p>Les d&eacute;sirs et les envies ne sont pas des besoins, mais ils cachent des besoins.
Il faut se demander "qu'est-ce que cela permet?"
Par exemple, si l'enfant demande un jus de fruits, il n'a pas besoin de jus de fruits mais de boire.</p>
<p>Un besoin doit &ecirc;tre satisfait pour notre bien-&ecirc;tre.
Un d&eacute;sir (qui est important et moteur) n'exige pas d'&ecirc;tre combl&eacute; mais est un moyen de combler un
besoin.</p>
<p>L'enfant ne peut pas combler un besoin de fa&ccedil;on autonome, il a donc besoin du parent.</p>

<p>La frustration est impossible &agrave; g&eacute;rer avant 4 ans, et seulement 60% des enfants de 12 ans y
arrivent.</p>
<p>Pour aider &agrave; g&eacute;rer, on peut proposer des alternatives.</p>
<p>&Eacute;coute active fait progresser l'intelligence &eacute;motionnelle de l'enfant: identifier, comprendre, exprimer
et &eacute;couter, r&eacute;guler, utiliser les &eacute;motions au quotidien.</p>
<p>Pour &ecirc;tre capable d'avoir de l'empathie, il faut en premier &ecirc;tre capable de concevoir les &eacute;motions
chez soi avant autrui.</p>

<div class="framed">
L'<b>&eacute;coute active</b> signifie ne pas imposer nos solutions d'adulte &agrave; l'enfant
mais l'aider &agrave; activer ses ressources pour trouver ses propres solutions.
</div>


<!-- H3 Accompagner les emos -->
<h3>Accompagner les &eacute;motions de l'enfant</h3>

<!-- H4 General -->
<h4>G&eacute;n&eacute;ralit&eacute;s</h4>
<p>Il est important de consacrer au moins 10 minutes de pleine attention &agrave; l'enfant chaque jour.</p>
<p>Un enfant a besoin de contacts physiques jusque vers 10 ans.</p>
<p>On peut faire dans la maison un espace zen (pour tous) pour aller se ressourcer quand on est d&eacute;bord&eacute;
par nos &eacute;motions.
Par exemple des coussins, des matti&egrave;res douces, des boules &agrave; neige &agrave; regarder, des crayons et
des feuilles, des mandalas, des balles &agrave; malaxer, une balle de tennis pour auto-massages, des livres...</p>

<p>Un bon truc est d'utiliser l'imaginaire: les enfants adorent faire semblant.
Par exemple, si l'enfant n'a pas envie d'aller &agrave; l'&eacute;cole, on peut raconter ce qu'on ferait si on allait
pas &agrave; l'&eacute;cole.
Ainsi l'enfant comprend qu'on reconna&icirc;t son envie, mais l'adulte n'a pas besoin d'y c&eacute;der.
Cela renforce les liens de confiance et de proximit&eacute;.</p>
<p>Lorsque l'enfant se sent reconnu dans ses besoins,
cela l'aide &agrave; trouver des ressources en lui pour g&eacute;rer la situation.</p>

<div class="framed"><p><b>ATTENTION:</b></p>
Le <b>sucre</b> est aussi un acteur important dans le comportement de l'enfant.
Si l'enfant fait des motagnes russes &eacute;motionnelles (agitations suivies d'apathies),
il faut surveiller sa consommation de sucre.
Overdose de sucre m&egrave;ne &agrave; <i>surpoids</i> et <i>diab&egrave;te</i>,
mais aussi <b>troubles de la m&eacute;moire</b> et <b>troubles de l'attention</b>.
</div>


<!-- H4 Joie -->
<h4>Joie</h4>
<p>Pour accompagner la joie, on peut par exemple prendre chaque jour un moment pour chacun partager un beau moment de la
journ&eacute;e.
Eventuellement faire une "bo&icirc;te &agrave; bonheur" &agrave; ouvrir une fois par an et &agrave; remplir avec des
petits mots, des histoires, des billets de cin&eacute;ma...</p>


<!-- H4 Colere -->
<h4>Col&egrave;re</h4>
<p><b>D&eacute;finition</b>: la col&egrave;re est l'&eacute;motion qui se manifeste quand on ressent le besoin
de changer une situation inacceptable (injustice, frustration, agression...).
La col&egrave;re a donc besoin de <b>s'exprimer</b> (pas se r&eacute;primer) pour diminuer.</p>
<p>La violence est l'&eacute;chec de la col&egrave;re: quand on n'arrive pas &agrave; se faire comprendre, la
col&egrave;re s'amplifie et passe par les gestes.</p>
<p>Si les col&egrave;res de l'enfant posent probl&egrave;mes, on peut essayer de les analyser avec le tableau
suivant:</p>
<div><ul>
  <li>moment de la journ&eacute;e</li>
  <li>situation</li>
  <li>personnes</li>
  <li>mode d'expression de la col&egrave;re (gestes, mots, volume sonore)</li>
  <li>estimation de l'intensit&eacute; de l'&eacute;motion</li>
  <li>ma r&eacute;action (mots, gestes)</li>
</ul></div>

<p>Une fois les situations qui engendrent la col&egrave;re chez l'enfant identifi&eacute;e,
on peut discuter des situations probl&eacute;matiques explicitement pour trouver des solutions ensembles.</p>

<p>Quand l'enfant manifeste une col&egrave;re, il est important de l'&eacute;couter (&eacute;coute active)
sans le toucher, reformuler ce qu'il dit.
S'il est en col&egrave;re contre moi, placer ma t&ecirc;te plus basse que la sienne peut aider son cerveau a
interpr&eacute;ter que je ne suis pas une menace.
Il est important de respirer profond&eacute;ment pour accueillir l'intensit&eacute; &eacute;motionnelle.
Faire attention au temps de qualit&eacute; pass&eacute; ensemble, cela peut &ecirc;tre un facteur d&eacute;clencheur de
col&egrave;res.</p>

<!-- H5 en cas de coleres -->
<h5>En cas de col&egrave;res</h5>
<div><ul>
  <li>bo&icirc;te &agrave; cris (d&eacute;cor&eacute;e?): on peut crier dedans puis la vider par la fen&ecirc;tre</li>
  <li>du papier &agrave; froisser</li>
  <li>gribouiller avec un gros feutre ou d&eacute;crire la col&egrave;re puis chiffoner et jeter le papier</li>
  <li>ballon de baudruche: on souffle la col&egrave;re dedans et on vide par la fen&ecirc;tre</li>
  <li id="valises">valises &agrave; contrari&eacute;t&eacute; (sophrologie):</li>
  <ul>
    <li>on se met debout</li>
    <li>on imagine des valises (forme, couleur...)</li>
	<li>on met dans les valises les choses dont l'enfant veut s'all&eacute;ger, cec qui le met en col&egrave;re
    (interdiction de mettre des personnes, plut&ocirc;t des attitudes, des ressentis...)</li>
    <li>sur une longue inspiration, on prend les valises, on l&egrave;ve les &eacute;paules</li>
    <li>on bloque la respiration, on baisse et rel&egrave;ve les &eacute;paules de 3 &agrave; 5 fois</li>
    <li>sur une exppiration tonique par la bouche, on jette les valises par terre</li>
  </ul>
  <li>respirations synchronis&eacute;es (sophrologie): on prend une grande inspiration,
  on bloque et on contracte le plus de muscles possibles (visage, &eacute;paules, dos, torse, bras, poings, bas du
  corps),
  puis on fait une longue et profonde expiration et en m&ecirc;me temps on rel&acirc;che les muscles,
  et on finit avec quelques respirations calmes et profonds en &eacute;tant d&eacute;tendu</li>
</ul></div>


<!-- H4 Peur -->
<h4>Peur</h4>
<p><b>D&eacute;finition</b>: la peur nous permet de d&eacute;tecter les dangers, de nous en &eacute;loingner ou de les
combattre, avec pour objectif la survie. C'est l'&eacute;motion la plus profond&eacute;ment ancr&eacute;e en nous.</p>
<p>Un enfant effray&eacute; ne peut pas &ecirc;tre rassur&eacute; par des paroles adultes.
Il a besoin de trouver la r&eacute;assurance en lui-m&ecirc;me, et pour cela il a besoin que l'adulte fournisse de
l'&eacute;coute et des informations.</p>
<p>Il est tr&egrave;s important de ne pas rire ni se moquer des peurs de l'enfant.</p>
<p>Certains enfants sont plus imaginatifs/craintifs.</p>
<p>Lorsqu'on demande &agrave; l'enfant d'expliquer sa peur en d&eacute;tails, il se sentira mieux d'avoir pu mettre des
mots sur son &eacute;motion.</p>
<p>Il est important de bien &eacute;couter, &eacute;viter d'anticiper &agrave; sa place, puis bien l'informer.</p>
<p>L'adulte peut encourager l'enfant (<a href="#compliment">correctement</a>), souligner de fa&ccedil;on descriptive tous
ses progr&egrave;s, les mettre en mots sinc&egrave;rement pour qu'il se les approprie, mais attention de ne pas
exag&eacute;rer les faits.</p>
<p>On peut aussi stimuler la m&eacute;moire des r&eacute;ussites: rappeler des souvenirs de r&eacute;ussites (contre une
peur) pass&eacute;es.</p>

<!-- H5 En cas de peur -->
<h5>En cas de peur</h5>
<div><ul>
  <li>dessiner les peurs: les mettre &agrave; l'ext&eacute;rieur de lui. Il peut aussi poursuivre en dessinant une
  solution rigolote pour les neutraliser</li>
  <li>Apr&egrave;s une phase intense, faire 1-2 minutes de respirations profondes</li>
  <li>Exercice du polichinelle (sophrologie): debout, les pieds restent coll&eacute;s au sol, on fait trembler les
  genoux et on garde le corps souple (comme un pantin qui saute).
  Cela aide l'enfant &agrave; s'ancrer au sol, &agrave; mettre son corps en mouvement face &agrave; la peur, et cela
  augmente son &eacute;nergie.</li>
</ul></div>


<!-- H4 Tristesse -->
<h4>Tristesse</h4>
<p><b>D&eacute;finition:</b> la tristesse est l'&eacute;motion de perte (deuil, s&eacute;paration, d&eacute;ception, d&eacute;sillusion...).</p>
<p>La tristesse se passe en 2 &eacute;tapes: il faut d'abord dig&eacute;rer la perte,
puis on peut imaginer l'avenir sans ce qui a &eacute;t&eacute; perdu;
la vie est pleine de belles surprises!</p>
<p><b>Avec l'accord de l'enfant</b>, on peut lui faire des c&acirc;lins, des caresses et le laisser pleurer.
Il ne faut pas essayer d'avoir des mots r&eacute;confortants, cela ne va pas aider l'enfant.</p>
<p>On peut demander &agrave; l'enfant d'expliquer, sans lui sugg&eacute;rer de solutions/compensations.</p>
<p>Apr&egrave;s avoir s&eacute;ch&eacute; les larmes, on peut gentiment parler du futur.</p>


<!-- H5 En cas de tristesse -->
<h5>En cas de tristesse</h5>
<div><ul>
  <li>Le circuit &eacute;nerg&eacute;tique: &agrave; faire 3 fois, exercer des pressions douces de quelques secondes sur
  (dans l'ordre):</li>
  <ul>
    <li>la base ext&eacute;rieure de l'ongle du gros orteil droite</li>
    <li>en posant la main sur l'&eacute;paule gauche, au niveau du majeur</li>
    <li>l'int&eacute;rieur de l'avant-bras droit &agrave; 3 largeurs de doigts du poignet</li>
    <li>l'articulation pouce-index gauche</li>
    <li>sur le thorax &agrave; mi-chemin des mamelons</li>
  </ul>
  <li>Cahier positif: chaque soir, &eacute;crire/dessiner 3 &agrave; 5 choses positives de la journ&eacute;e. Cela aide
  &agrave; ouvrir le regard sur le positif</li>
  <li>La danse de la bougie: faire danser la flamme avec le souffle sans l'&eacute;teindre; &eacute;loigner la bougie
  pour faire des respirations plus profondes</li>
  <li>visualiser le futur (sophrologie): assis, on essaye de se d&eacute;tendre (au moins les sourcils, la
  m&acirc;choire, les &eacute;paules et le ventre),
  et l'enfant imagine et d&eacute;crit dans quelques mois une situation agr&eacute;able pour lui (avec des
  d&eacute;atils du lieu, des personnes, de l'action).
  L'enfant doit &ecirc;tre attentif &agrave; ses ressentis positifs dans la situation.
  Apr&egrave;s quelques instants, inspirer le positif de la sc&egrave;ne et l'expirer dans le pr&eacute;sent.</li>
</ul></div>


<!-- H4 Jalousie -->
<h4>Jalousie</h4>
<p><b>D&eacute;finition:</b> la Jalousie est l'&eacute;motion de l'ins&eacute;curit&eacute;, la peur de perdre.</p>
<p>Quand un enfant est jaloux, l'adulte doit enqu&ecirc;ter pour comprendre le besoin cach&eacute;.</p>
<p>Il faut faire de l'&eacute;coute active en t&ecirc;te-&agrave;-t&ecirc;te (sans fr&egrave;re/soeur) pour parler
librement.</p>
<p>L'adulte doit rechercher l'&eacute;quit&eacute; plut&ocirc;t que l'&eacute;galit&eacute;:
donner &agrave; chacun ce qu'il a besoin plut&ocirc;t que la m&ecirc;me chose &agrave; tous.
Cela va aider l'enfant &agrave; distinguer ses propres besoins plut&ocirc;t que de se comparer.</p>
<p>L'adulte doit &eacute;viter les comparaisons.</p>
<p>Attention aux compliments, &eacute;viter de faire des compliments importants devant les autres enfants.</p>
<p>Si un gros conflit de jalousie arrive, on peut intervenir (de mani&egrave;re <b>impartiale</b>) comme suit:</p>
<div><ol>
  <li>d&eacute;crire la situation</li>
  <li>demander &agrave; chacun d'exrpimer ses ressentis</li>
  <li>reformuler les &eacute;motions et les besoins de chacun</li>
  <li>dire explicitement qu'il est difficile de satisfaire tout le monde;
  &eacute;viter de minimiser, reconna&icirc;tre que ce n'est pas toujours facile de vivre ensemble et trouver des
  solutions satisfaisantes.</li>
  <li>Si besoin, guider la recherche de solutions en proposant plusieurs choix et en demandant aux enfants leurs
  solutions/id&eacute;es</li>
  <li>dire explicitement que je suis confiant qu'ils vont trouver une solution satisfaisante pour chacun</li>
  <li><b>les laisser seuls</b> pour finaliser et mettre en pratique la solution</li>
</ol></div>


<!-- H5 En cas de jalousie -->
<h5>En cas de jalousie</h5>
<div><ul>
  <li>mettre en place un signe complice: trouver avec l'enfant un signe facile &agrave; faire, qui ne soit pas un geste
  courant. Lorsque l'enfant utilise ce signe, cela signifie qu'il a besoin d'attention ou d'aide</li>
  <li>des petits mots: les parents glissent un petit mot sous l'oreiller (mots d'amour/gratitude, compliments, dessins).
  On peut aussi faire participer tout le monde et en faire un rituel familial dont la fr&eacute;quence est &agrave;
  d&eacute;finir</li>
  <li>respiration du coeur: sur une inspiration, le coeur se remplit d'amour et de sourires; sur l'expiration, le coeur
  rayonne ce qu'il a emmagasin&eacute; dans la maison et plus loin (copains, famille, Terre...)</li>
  <li>pause m&eacute;ditative (sophrologie): proposer un moment tranquille et silencieux (pas trop long) &agrave;
  l'&eacute;coute de ce qui se passe en lui.
  On peut l'aider en lui posant des questions:
  <ul>
    <li>est-ce qu'il y a des endroits calmes/agit&eacute;s dans ton corps?</li>
    <li>Quelles parties de ton corps bougent quand tu respires?</li>
    <li>Ressens-tu une &eacute;motion particuli&egrave;re?</li>
  </ul>
  (Faire une pause apr&egrave;s chaque question.)<br/>
  Si on pratique cela r&eacute;guli&egrave;rement, &ccedil;a nous apprend &agrave; se connecter &agrave; nos ressentis
  et &agrave; nos besoins.
  C'est donc une aide pour revenir &agrave; soi et &agrave; ses propres besoins plut&ocirc;t que de se comparer.</li>
</ul></div>



<!-- H2 Parents -->
<h2>Parents</h2>
<p><b>Personne n'est parfait, tout le monde fait des erreurs.</b></p>
<p>Il est important de se remettre en question.</p>
<p>Quand le parent culpabilise, il faut revenir aux besoins de chacun;
attention car c'est diff&eacute;rent que de projeter ce dont l'enfant pourrait avoir besoin de mon point de vue adulte
coinc&eacute; dans la culpabilit&eacute;.</p>
<p>Personne n'est parfait, on fait tous des erreurs: la remise en question peut &ecirc;tre une occasion
p&eacute;dagogique tant pour l'adulte que pour l'enfant.</p>
<p>Par exemple, si le parent culpabilise de ne pas passer assez de temps avec l'enfant,
lui faire une sortie surprise n'est pas forc&eacute;ment ce qu'il attendra et ne va peut-&ecirc;tre pas am&eacute;liorer
la situation, voire va l'empirer.
Il faut plut&ocirc;t en parler avec l'enfant et lui demander comment il aimerait que la situation soit
r&eacute;par&eacute;e/am&eacute;lior&eacute;e.</p>

<p>Il faut prendre le temps d'apprendre. Lorsqu'on veut mettre en place quelque chose de nouveau, cela ne va pas venir
instantan&eacute;ment.
L'apprentissage se passe en 4 &eacute;tapes:</p>
<div><ol>
  <li><b>In</b>consciemment <b>in</b>comp&eacute;tent: je ne sais <b>pas</b> QUE je ne sais <b>pas</b></li>
  <li>         Consciemment <b>in</b>comp&eacute;tent: je sais               QUE je ne sais <b>pas</b></li>
  <li>         Consciemment          comp&eacute;tent: je sais               QUE je sais</li>
  <li><b>In</b>consciemment          comp&eacute;tent: je ne sais <b>pas</b> QUE je sais</li>
</ol></div>

<p><i>Il n'y a pas de bonne fa&ccedil;on de faire quelque chose qui ne fonctionne pas</i> (Sandrine Donzel).
Cela veut dire que si apr&egrave;s 2 essais (appel, demande, etc), cela ne marche pas, il faut changer de
m&eacute;thode/strat&eacute;gie.</p>

<p>Quand les parents sont en d&eacute;saccord, les enfants peuvent le comprendre par le non-verbal.
Il faut donc bien g&eacute;rer et communiquer avec son partenaire dans ce genre de situation.
On peut le faire devant l'enfant.</p>

<p>C'est important de ne pas &ecirc;tre connect&eacute; en permanence, le cerveau a besoin de pauses.</p>

<!-- H3 stress -->
<h3>Stress</h3>
<p>Le stress est un m&eacute;canisme de survie.
Il permet la fuite ou le combat (gr&acirc;ce &agrave; des hormones d'&eacute;nergie et anti-inflammatoires),
il acc&eacute;l&egrave;re le coeur et la respiration et provoque un afflux sanguin dans les membres.
Mais aujourd'hui il y a trop de stress.
Il faut comprendre les facteurs d&eacute;clencheurs de stress pour leur apporter une r&eacute;ponse
appropri&eacute;e.</p>
<p>Pour identifier les situations &agrave; stress, on peut utiliser le mn&eacute;motechnique CINE: quel CINE je me fais
face &agrave; mon stress?</p>
<div><table>
  <tr>
	<td class="cineLeft">perte de&nbsp;</td>
    <td class="cineCenter">C</td>
	<td class="cineRight">ontr&ocirc;le</td>
  </tr>
  <tr>
    <td></td>
    <td class="cineCenter">I</td>
    <td class="cineRight">mpr&eacute;visibilit&eacute;</td>
  </tr>
  <tr>
    <td></td>
    <td class="cineCenter">N</td>
    <td class="cineRight">ouveaut&eacute;</td>
  </tr>
  <tr>
    <td class="cineLeft">menace pour l'</td>
    <td class="cineCenter">E</td>
	<td class="cineRight">go (ne pas se sentir &agrave; la hauteur)</td>
  </tr>
</table></div>
<p>Le cerveau ne voit <b>pas de diff&eacute;rences</b> entre le r&eacute;el et l'imaginaire, les m&ecirc;mes aires
c&eacute;r&eacute;brales sont activ&eacute;es.
Imaginer une situation &agrave; stress va donc provoquer la m&ecirc;me r&eacute;action de stress que de vivre ladite
situation.</p>
<p>Lors de situation stressante, dans un 2e temps, il est important de trouver des solutions.
Cela permettra au cerveau d'int&eacute;grer l'information "il y a autre chose &agrave; faire que fuire/combattre,
je peux me calmer."</p>

<!-- H4 en cas de stress -->
<h4>En cas de stress</h4>

<div><ul>
  <li>Bouger: sport, danse, jeu avec les enfants...</li>
  <li>Respirer profond&eacute;ment</li>
  <li>Rire (seul, avec des amis...)</li>
  <li>Aider les autres</li>
</ul></div>

<p>Au quotidien, on peut aussi prendre le temps de faire 6 respirations par minute pendant 5 minutes, 3 fois par
jour.</p>
<p>Attention &agrave; l'accumulation de frustrations, cela peut mener &agrave; une explosion de col&egrave;re.</p>
<p>Il est important de savoir quelles parties du corps s'activent quand la col&egrave;re &eacute;merge, et quelles
parties quand la col&egrave;re explose.
Cela permet d'identifier quand la col&egrave;re monte, et on peut alors aller s'isoler et respirer profond&eacute;ment.
Quand on est redevenu calme, on peut analyser la situation et ainsi communiquer clairement &agrave; l'enfant la raison
de notre col&egrave;re.</p>
<p>La col&egrave;re du parent cache souvent une autre &eacute;motion.
Il est donc important une fois le calme revenu en nous d'analyser quelle &eacute;motion se cachait derri&egrave;re notre
col&egrave;re.</p>

<p>Pour &eacute;viter le stress au quotidien:</p>
<div><ul>
<li>au r&eacute;veil, en milieu de matin&eacute;e, au d&icirc;ner, en milieu d'apr&egrave;s-midi: prendre 5 minutes pour
faire des respirations</li>
<li>avant de rentrer &agrave; la maison: s'arr&ecirc;ter et respirer tranquillement pour &ecirc;tre plus disponible pour la
famille</li>
<li>prendre du temps de qualit&eacute; (jouer, discuter) et &eacute;ventuellement repousser les t&acirc;ches de 10 minutes
(devoirs, repas...)</li>
<li>avant de dormir: &eacute;crire 3-5 choses positives de la journ&eacute;e dans un cahier.</li>
</ul></div>

<p>En cas de gros stress, trouver ma fa&ccedil;on de d&eacute;charger (bouger, se promener, danser, courir):</p>
<div><ul>
  <li>respirations synchronis&eacute;es: inspiration, bloquer, contracter gentiment tout le corps, surtout les bras et
  les poings, expiration en rel&acirc;chant la tension</li>
  <li>les <a href="valises">valises &agrave; contrari&eacute;t&eacute;</a></li>
  <li>se trouver un mot-ressource qui, une fois visualis&eacute; mentalement, aide &agrave; apaiser le stress</li>
  <li>masser au moins 2 minutes le muscle entre le pouce et l'index en respirant profond&eacute;ment</li>
  <li>Fleurs de Bach de secours</li>
</ul></div>



<!-- H2 Poser de limites et cooperer -->
<h2>Poser des limites et coop&eacute;rer</h2>

<p>De nombreux conflits du quotidien viennent d'un manque de coop&eacute;ration.</p>

<div class="framed"><p><b>ATTENTION:</b></p>
Il est dangereux de dire "les enfants doivent ob&eacute;ir".
Cela implique une soumission aux parents,
et va apprendre aux enfants &agrave; ob&eacute;ir aux ordres sans en comprendre leurs sens et leurs implications.
On peut rester ferme sur nos besoins, mais souples sur les solutions pour les combler.
Cela va privil&eacute;gier chez l'enfant le libre-arbitre, l'autonomie, la responsabilit&eacute; et la
cr&eacute;ativit&eacute;.
</div>

<p>Il est important de mettre un cadre, pour que l'enfant puisse faire ses exp&eacute;riences en s&eacute;curit&eacute;
sous le regard des parents d&eacute;tendus.</p>
<p>L'enfant comprend/int&egrave;gre mieux une r&egrave;gle avec un sens.
Par exemple:</p>
<div><ul>
  <li>NE PAS: c'est interdit de toucher aux briquets</li>
  <li>MAIS: le feu du briquet br&ucirc;le, je ne veux pas que tu te fasses mal</li>
</ul></div>

<p>L'adulte &eacute;vite les peurs et les col&egrave;res avec des r&egrave;gles claires.</p>
<p>C'est important de discuter en couple, voire en famille, pour exprimer ce qui est acceptable ou pas.
On peut utiliser l'image du feu tricolore:</p>
<div><ul>
  <li><b>ROUGE:</b> inacceptable et non-n&eacute;gotiable.
  Cela concerne g&eacute;n&eacute;ralement la s&eacute;curit&eacute;, le respect, l'hygi&egrave;ne,
  parfois aussi les valeurs et les conventions sociales.
  Ces r&egrave;gles sont ind&eacute;pendantes du contexte et de l'&eacute;tat &eacute;motionel du parent.
  Elles &eacute;voluent avec l'&acirc;ge de l'enfant.
  On peut appliquer les 4C:
  <b>C</b>laires,
  <b>C</b>onnues d'avance,
  <b>C</b>oh&eacute;rentes,
  <b>C</b>ons&eacute;quences.
  </li>

  <li><b>ORANGE:</b> exceptionnelement tol&eacute;r&eacute;s.
  Ces r&egrave;gles sont &agrave; clarifier ensemble.
  A chaque exception, il faut pr&eacute;ciser &agrave; l'enfant que la d&eacute;rogation est exceptionnelle.
  </li>

  <li><b>VERT:</b> souhaitables et encourag&eacute;s.
  Les r&egrave;gles de vie des valeurs &agrave; transmettre, les comportements attendus
  (d&eacute;j&agrave; acquis ou pas encore).
  </li>
</ul></div>


<p>On peut faire un conseil de famille pour r&eacute;viser les r&egrave;gles.
Si l'enfant est impliqu&eacute; dans le processus, il respectera mieux les r&egrave;gles, et cela aide l'adulte
&agrave; clarifier les r&egrave;gles et leurs sens.</p>

<p>Les punitions sont contre-productives.
Elles n'engendrent que des sentiments n&eacute;gatifs envers l'adulte.
L'enfant va prendre de l'&eacute;nergie pour sa rancoeur/vengeance/pas se faire prendre la prochaine fois.
La punition m&egrave;ne &agrave; une rebellion/soumission et &agrave; une d&eacute;gradation des liens familiaux.</p>
<p>La sanction est une r&eacute;ponse plus appropri&eacute;e.
Elle d&eacute;coule naturellement des cons&eacute;quences du comportement.
Elle a une vis&eacute;e r&eacute;paratrice sur la base d'une r&egrave;gle connue et comprise.</p>


<div class="framed">
<p>Les violences &eacute;ducatives ordinaires (VEO) sont dangereuses car elles transmettent les messages suivants
tr&egrave;s puissants:</p>
<div><ul>
  <li>j'ai le droit d'utiliser la violence quand je ne suis pas satisfait</li>
  <li>la violence est un moyen de r&eacute;soudre les probl&egrave;mes</li>
  <li>les plus forts dominent</li>
  <li>on a le droit d'aimer et de faire du mal</li>
</ul></div>
</div>


<p>Les r&eacute;compenses ne fonctionnent pas sur du long terme:
la motivation est externe &agrave; l'enfant, pour avancer il faut qu'il trouve la motivation en lui.
Pour fonctionner, les r&eacute;compenses devront &ecirc;tre de plus en plus importantes et
quelque chose que l'enfant est incapable d'acqu&eacute;rir seul (attention car l'enfant grandit).</p>

<p>Le parent doit s'affirmer avec respect:</p>
<div><ul>
  <li>c'est important d'exprimer ce qu'on ressent &agrave; nos enfants</li>
  <li>il faut &eacute;viter les critiques, les reproches, lui faire la morale</li>
  <li>il faut essayer de lui parler clairement, lui d&eacute;crire la situation</li>
</ul></div>
<p>Par exemple:</p>
<div><ul>
  <li>NE PAS: tes jouets tra&icirc;nent partout &agrave; l'entr&eacute;e de ta chambre</li>
  <li>MAIS: il y a 3 poup&eacute;es, 1 poussette et 2 peluches devant ta porte, je ne peux pas rentrer dans ta
chambre</li>
</ul></div>
<p>C'est important de ne pas faire de jugement dans ma description.
On coop&egrave;re plus volontiers quand on ne se sent pas attaqu&eacute;.</p>

<p>i-message (message-je, messaJe): le probl&egrave;me du parent est qu'il a tendance &agrave; parler "sur" l'enfant.
La solution est d'exprimer clairement ce qui se passe en nous.</p>
<p>Le i-message se contruit comme ceci:</p>
<div><ol>
  <li>Je d&eacute;cris ce que je vois, la situation</li>
  <li>Je d&eacute;cris, je nomme ce que je ressens</li>
  <li>Je d&eacute;cris les cons&eacute;quences</li>
</ol></div>

<p>Il faut pr&eacute;venir pluto que rugir: l'enfant a une vision du monde diff&eacute;rente de l'adulte.
Il ne se rend pas compte de ce qu'on attend de lui.
Il faut lui exprimer clairement nos attentes, cela l'aidera &agrave; comprendre et &agrave; s'y conformer.
Il faut donc d&eacute;crire la situation avant qu'elle ne devienne un probl&egrave;me pour moi.</p>

<p>Quelques outils pour une bonne coop&eacute;ration:</p>
<div><ul>
  <li>&ecirc;tre affirmatif plut&ocirc;t que n&agrave;gatif</li>
  <li>proposer des choix/alternatives plut&ocirc;t qu'ordonner</li>
  <li>passer du temps de qualit&eacute; avec l'enfant</li>
  <li>l'humour et le jeu permettent d'entrer dans l'univers de l'enfant:<br/>
  "Donner une tonalit&eacute; ludique &agrave; nos demandes invite au rire, &agrave; la l&eacute;gert&eacute;, et bien
  souvent &agrave; l'ex&eacute;cution de la t&acirc;che demand&eacute;e."<br/>
  Parler avec une dr&ocirc;le de voix, une langue imaginaire, en faisant l'idiot...
  Pas &agrave; chaque fois, mais briser la routine &agrave; l'aide du jeu pla&icirc;t &eacute;norm&eacute;ment aux
  enfants</li>
  <li id="compliment">renforcer le positif: c'est important de relever les comportements positifs aussi.
  Pour cela, on peut utiliser le i-message en insistant sur les faits et sur mon ressenti.
  On peut aussi le complimenter efficacement, mais pour cela il faut veiller &agrave; rester descriptif sur ce que je
  souhaite valoriser dans le comportement.
  Cela permet &agrave; l'enfant de rejouer l'&eacute;v&egrave;neent dans sa t&ecirc;te,
  pour qu&agrave;il puisse comprendre et int&eacute;grer,
  puis s'accorder &agrave; lui-m&ecirc;me le compliment.</li>
</ul></div>

<p>Lorsque des conflits arrivent:</p>
<div><ul>
  <li>Si besoin, s'isoler</li>
  <li>Faire de grandes et lentes respirations</li>
  <li>Quand l'&eacute;motion est redevenue g&eacute;rable, se poser des questions:</li>
  <ul>
	<li>qu'est-ce qui est si important pour moi dans cette situation? Clarifier les valeurs/besoins frustr&eacute;s</li>
	<li>quelle comp&eacute;tence manque &agrave; mon enfant pour changer de comportement? Gestion de la col&egrave;re?
    Li&eacute; &agrave; l'&acirc;ge?</li>
  </ul>
  <li>En fonction des r&eacute;ponses aux pr&eacute;c&eacute;dentes questions, pr&eacute;parer son i-message</li>
  </ul>
</ul></div>

<p>Au quotidien, pour avoir une bonne relation avec l'enfant:</p>
<div><ul>
  <li>Lorsque je ne suis pas content, je communique avec i-message</li>
  <li>Lui faire au moins 3 compliments/valorisations par jour</li>
  <li>Prendre du temps de qualit&eacute; (jeux, lecture, c&acirc;lins, balade, sport, cuisine...)</li>
  <li>Quand il est boulvers&eacute;, &eacute;couter et descendre de ma montagne</li>
  <li>Le responsabiliser avec des sanctions</li>
  <li>Si malgr&eacute; tout je d&eacute;rape (r&eacute;action/geste brusque): s'excuser aupr&egrave;s de l'enfant et
  r&eacute;fl&eacute;chir ensemble &agrave; comment faire autrement si la situation se reproduit</li>
</ul></div>


<!-- H3 Mettre en place l'education positive en 4 semaines -->
<h3>Mettre en place l'&eacute;ducation positive en 4 semaines</h3>

<!-- H4 Semaine 1 -->
<h4>Semaine 1: observations</h4>
<div class="checkbox"><ul>
  <li><input type="checkbox" name="e[]" />&nbsp;clarifier les capacit&eacute;s du cerveau de l'enfant</li>

  <li><input type="checkbox" name="e[]" />&nbsp;observer mon comportement quand l'enfant exprime ses &eacute;motions;
  ai-je tendance &agrave; essayer de le calmer ou &agrave; accueillir son &eacute;motion? Suis-je &agrave; l'aise avec
  toutes ses &eacute;motions ou certaines g&eacute;n&egrave;rent plus de r&eacute;actionnel?</li>

  <li><input type="checkbox" name="e[]" />&nbsp;combien de temps de qualit&eacute; en semaine et le week-end est-ce que
  je passe chaque jour avec les enfants?
  Est-ce que je fais des jeux avec eux?</li>

  <li><input type="checkbox" name="e[]" />&nbsp;s'il y a un conflit dans les besoins adulte-enfant, que se passe-t-il?
  Dispute, punition...?</li>

  <li><input type="checkbox" name="e[]" />&nbsp;combien de temps je m'accorde &agrave; moi-m&ecirc;me chaque jour?
  Y aurait-il une activit&eacute; que je voudrais reprendre?</li>

  <li><input type="checkbox" name="e[]" />&nbsp;comment, quand, o&ugrave; et avec qui puis-je me d&eacute;tendre?</li>

  <li><input type="checkbox" name="e[]" />&nbsp;suis-je souvent agac&eacute; ou en col&egrave;re dans ma vie
  familiale?</li>
</ul></div>


<!-- H4 Semaine 2 -->
<h4>Semaine 2: &eacute;coute</h4>
<div class="checkbox"><ul>
  <li><input type="checkbox" name="e[]" />&nbsp;durant un jour, lorsque l'enfant a un probl&egrave;me, l'&eacute;couter
  sans l'interrompre avec contact visuels et "accus&eacute;s de r&eacute;ception"</li>

  <li><input type="checkbox" name="e[]" />&nbsp;les jours suivants, quand l'enfant est d&eacute;bord&eacute;
  &eacute;motionnellement, reformuler ce qu'il ressent</li>

  <li><input type="checkbox" name="e[]" />&nbsp;avoir beaucoup de contacts physiques (jeux, c&acirc;lins)</li>

  <li><input type="checkbox" name="e[]" />&nbsp;prendre conscience des situations qui me font r&eacute;agir
  &eacute;motionnellement en tenant compte de mes r&eacute;actions corporelles et de mes pens&eacute;es.</li>

  <li><input type="checkbox" name="e[]" />&nbsp;faire chaque jour quelque chose rien que pour moi (m&ecirc;me si c'est
  seulement quelques minutes)</li>

  <li><input type="checkbox" name="e[]" />&nbsp;&ecirc;tre attentif &agrave; mes besoins de sommeil, de repos
   et de partage</li>
</ul></div>


<!-- H4 Semaine 3 -->
<h4>Semaine 3: r&egrave;gles</h4>
<div class="checkbox"><ul>
  <li><input type="checkbox" name="e[]" />&nbsp;faire le point sur les r&egrave;gles de vie que l'on souhaite
  transmettre aux enfants</li>

  <li><input type="checkbox" name="e[]" />&nbsp;si les enfants sont assez matures, faire un conseil de famille avec eux
  pour poser et discuter les r&egrave;gles ensemble</li>

  <li><input type="checkbox" name="e[]" />&nbsp;si les enfants sont trop jeunes, lorsque la situation se
  pr&eacute;sente, formuler la r&egrave;gle (une &agrave; la fois)</li>

  <li><input type="checkbox" name="e[]" />&nbsp;quand le comportement de l'enfant est inacceptable, utiliser le
  i-message pour le lui communiquer</li>

  <li><input type="checkbox" name="e[]" />&nbsp;valoriser les comportement souhaitables en d&eacute;crivant
  concr&egrave;tement et en exprimant mes &eacute;motions positives</li>

  <li><input type="checkbox" name="e[]" />&nbsp;cr&eacute;er des moments d'attention exclusive pour l'enfant</li>
</ul></div>


<!-- H4 Semaine 4 -->
<h4>Semaine 4: coop&eacute;ration</h4>
<div class="checkbox"><ul>
  <li><input type="checkbox" name="e[]" />&nbsp;utiliser des consignes affirmatives, &eacute;viter les "ne pas"</li>

  <li><input type="checkbox" name="e[]" />&nbsp;proposer des choix/alternatives plut&ocirc;t que d'imposer</li>

  <li><input type="checkbox" name="e[]" />&nbsp;utiliser l'humour, l'imaginaire, le jeu pour entrer en relation avec
  l'enfant</li>

  <li><input type="checkbox" name="e[]" />&nbsp;s'il refuse de coop&eacute;rer, &eacute;couter son probl&egrave;me</li>

  <li><input type="checkbox" name="e[]" />&nbsp;pour la routine quotidienne, faire un tableau ou une affiche avec des
  mots ou des pictogrammes</li>

  <li><input type="checkbox" name="e[]" />&nbsp;si la non-coop&eacute;ration de l'enfant est en conflit avec mes
  besoins, utiliser le i-message pour le lui communiquer</li>
</ul></div>



<?php
//// Finish
unset($page);
?>
