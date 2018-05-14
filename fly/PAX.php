<?php
/*** Created: Mon 2014-12-01 09:31:30 CET
 ***
 *** TODO:
 *** * generally 4-seat plane
 ***
 ***/
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
require("doodle.php");
$page = new PhPage($rootPath);
//$page->LogLevelUp(6);

// Set languages
$page->setAvailLangs(array("french", "english"));  // This page is both french and english
$page->ChangeSessionLang();  // check if got anything in GET

$page->CSS_ppJump();
$page->CSS_ppWing();

$aref = "http://www.aerodrome-ecuvillens.ch/index.php?page=meteo_webcam.htm";
$gmaps = "http://maps.google.ch/?f=d&amp;daddr=aerodrome+ecuvillens";
$HourlyRate = 150;
$IBAN = "<tt>CH38&nbsp;0027&nbsp;2272&nbsp;3046&nbsp;8440&nbsp;R</tt>";
$IBANstr = "(IBAN: $IBAN)";
$albumURL = "https://en.wikipedia.org/wiki/User:Xonqnopp/Photos/Flights";
$xnGmail = "<tt>xonqnopp.airlines</tt>&nbsp;__A-t__&nbsp;<tt>gmail.com</tt>";

$body = "";
$contents = "";
$gohome = new stdClass();
$gohome->rootpage = "..";
$body .= "<div class=\"wide\">\n";
$body .= $page->GoHome($gohome);
$body .= $page->Languages();
$body .= "</div>\n";


$page_title = "Welcome to XonqNopp Airlines!";

if($page->CheckSessionLang($page->GetFrench())) {
	//// french
		//// some definitions
		$sayyes = "oui";
		$yes = "<img alt=\"$sayyes\" title=\"$sayyes\" src=\"../pictures/tick_pok.png\" />";
		$ifneedbe = "<img alt=\"($sayyes)\" title=\"($sayyes)\" src=\"../pictures/tick_pi.png\" />";
		$dy = "<span class=\"doodle yes\">$sayyes&nbsp;$yes</span>";
		$di = "<span class=\"doodle ifneedbe\">($sayyes)&nbsp;$ifneedbe</span>";
	//
		// infos
		$contents .= "<h2>Informations</h2>\n";
		$contents .= "<div>\n";
		$contents .= "<ul>\n";
		$contents .= "<li><a target=\"_blank\" href=\"$albumURL\">Mes photos</a></li>\n";
		$contents .= "<li>Id&eacute;e cadeau ;-)</li>\n";
		$contents .= "<li>Promenade habituelle 1h-1h30; possible atterrir ailleurs pour manger/boire un verre mais me contacter directement.</li>\n";
		$contents .= "<li>Compter vol+1h pour pr&eacute;paration sans stress.</li>\n";
		$contents .= "<li>D&eacute;part:\n";
		$contents .= "<a target=\"_blank\" href=\"$gmaps\" title=\"Ecuvillens\">Ecuvillens</a> (PAS Gruy&egrave;re/Epagny!)\n";
		$contents .= "ou Sion.\n";
		$contents .= "<li>M&eacute;t&eacute;o est d&eacute;t&eacute;rminante donc d&eacute;cision de partir le jour m&ecirc;me.</li>\n";
		$contents .= "<li>CHF&nbsp;$HourlyRate.-/h par personne $IBANstr</li>\n";
		$contents .= "<li>Contact: $xnGmail</li>\n";
		$contents .= "</ul>\n";
		$contents .= "<h4>A propos de la m&eacute;t&eacute;o</h4>\n";
		$contents .= "<p>C'est la m&eacute;t&eacute;o qui d&eacute;cidera si on peut partir ou pas.\n";
		$contents .= "Je te conseille donc de t'inscrire &agrave; 2 dates pour avoir une de r&eacute;serve.\n";
		$contents .= "La d&eacute;cision de partir ou pas ne peut &ecirc;tre s&ucirc;re qu'au dernier moment.\n";
		$contents .= "Je te tiendrai au courant dans les jours avant sur la tendance.\n";
		$contents .= "Il est possible qu'en vol on remarque que ce qu'on voulait faire n'est pas\n";
		$contents .= "possible et qu'on aille ailleurs ou qu'on rentre apr&egrave;s 10 minutes.</p>\n";
		$contents .= "</div>\n";
	//
		// inscription
		$contents .= "<h2>Inscription</h2>\n";
		$contents .= "<p>Si tu as une id&eacute;e pr&eacute;cise du vol que tu veux faire, contacte-moi.\n";
		$contents .= "On pourra alors discuter du temps n&eacute;cessaire et des dates de disponibilit&eacute;\n";
		$contents .= "de l'avion et de moi-m&ecirc;me.\n";
		$contents .= "Sinon tu peux m'appeler ou m'envoyer un SMS/whatsapp/mail ou t'inscrire directement sur le\n";
		$contents .= RenderDoodle();
		$contents .= ".</p>\n";

		$contents .= "<p>Si tu le souhaites, je peux aussi te mettre dans ma liste whatsapp\n";
		$contents .= "(pas un groupe o&ugrave; tout le monde voit les num&eacute;ros des autres)\n";
		$contents .= "pour t'avertir quand j'ai des nouvelles dates et des places libres.\n";
		$contents .= "</p>\n";

		$contents .= "<h4>Doodle</h4>\n";
		$contents .= "<p>Sur le " . RenderDoodle() . ", merci de faire une ligne par personne.\n";
		$contents .= "Le nombre de places disponibles est indiqu&eacute; en haut de la colonne\n";
		$contents .= "avec l'horaire et le lieu de d&eacute;part.\n";
		$contents .= "Voici comment fonctionne le doodle:</p>\n";
		$contents .= "<ul class=\"doodle\">\n";
			$contents .= "<li>\n";
			$contents .= "Tu peux t'inscrire pour un vol en cochant un $dy\n";
			$contents .= "Comme il est possible que la m&eacute;t&eacute;o nous contraigne\n";
			$contents .= "&agrave; annuler le vol, ce serait bien de cocher un $di\n";
			$contents .= "pour avoir une date de r&eacute;serve.\n";
			$contents .= "Si ton premier choix devait &ecirc;tre annul&eacute;, je transformerais\n";
			$contents .= "alors ton $di en $dy\n";
			$contents .= "</li>\n";
		//
			$contents .= "<li>\n";
			$contents .= "Tu peux cocher un $dy sous des\n";
			$contents .= "$di, mais ces derniers auraient la\n";
			$contents .= "priorit&eacute; sur toi si leurs premi&egrave;res dates sont\n";
			$contents .= "annul&eacute;es.\n";
			$contents .= "</li>\n";
		$contents .= "</ul>\n";
		$contents .= "<p>Je te confirme de toute façon si c'est OK ou pas.</p>\n";
		$contents .= "<p>Si aucune date n'est possible pour toi, contacte-moi pour fixer un autre jour.</p>\n";
		$contents .= "<p>Si tu as des questions, n'h&eacute;site pas &agrave; me contacter ;-)</p>\n";
		$contents .= "<p>" . RenderDoodle("Voici le doodle.") . "</p>\n";
	//
		// instructions
		$contents .= "<h2 id=\"instructions\">Instructions pour le vol</h2>\n";
		$contents .= "<p>Quelques consignes &agrave; observer avant le vol pour que tout se passe bien:</p>\n";
		$contents .= "<ul>\n";
			$contents .= "<li>Il ne faut surtout pas que tu ailles dans un environnement hyperbare (&agrave; une pression plus grande que la pression atmosph&eacute;rique)\n";
			$contents .= "tel que plong&eacute;e sous-marine dans les 24 heures avant le vol.\n";
			$contents .= "Cela pourrait engendrer un accident de d&eacute;compression pendant le vol dont l'issue pourrait &ecirc;tre fatale.</li>\n";
		//
			$contents .= "<li>&Agrave; &eacute;viter aussi dans les heures avant le vol:\n";
			$contents .= "fumer,\n";
			$contents .= "boissons gazeuses ou alcoolis&eacute;es,\n";
			$contents .= "manger beaucoup de fibres alimentaires (comme choux).</li>\n";
		//
			$contents .= "<li>&Agrave; prendre avec: ";
			$contents .= "lunettes de Soleil, ";
			$contents .= "appareil photo, ";
			$contents .= "bouteille d'eau, ";
			$contents .= "des habits confortables (les robes et jupes sont &agrave; &eacute;viter), ";
			$contents .= "des chaussures dans lesquelles tu es &agrave; l'aise pour marcher genre des baskets (pas avec des talons).</li>\n";
		//
			$contents .= "<li>L'avion fonctionne comme une voiture: le moteur nous sert de chauffage dans la cabine ferm&eacute;e.</li>\n";
		//
			$contents .= "<li>En cas de grossesse de plus de 3 mois, il est d&eacute;conseill&eacute;\n";
			$contents .= "de venir voler.</li>\n";
		//
			$contents .= "<li>Si tu veux emmener des enfants, r&eacute;flechis bien s'ils vont\n";
			$contents .= "vraiment en profiter et tenir une heure assis tranquillement.\n";
			$contents .= "Je ne prends pas les enfants de moins de 5 ans.</li>\n";
		//
			$contents .= "<li>Pour bien pr&eacute;parer le vol, je dois effectuer plusieurs calculs pour les performances de l'avion.\n";
			$contents .= "J'aurai pour cela besoin de conna&icirc;tre le poids de mes passagers.\n";
			$contents .= "Ne sois donc pas surpris si je te le demande plus tard.\n";
			$contents .= "Cette information ne sera pas communiqu&eacute;e &agrave; d'autres personnes que moi.</li>\n";
		$contents .= "</ul>\n";
	//
} else {
	//// english
		//// some definitions
		$sayyes = "yes";
		$yes = "<img alt=\"$sayyes\" title=\"$sayyes\" src=\"../pictures/tick_pok.png\" />";
		$ifneedbe = "<img alt=\"($sayyes)\" title=\"($sayyes)\" src=\"../pictures/tick_pi.png\" />";
		$dy = "<span class=\"doodle yes\">$sayyes&nbsp;$yes</span>";
		$di = "<span class=\"doodle ifneedbe\">($sayyes)&nbsp;$ifneedbe</span>";
	//
		// infos
		$contents .= "<h2>Informations</h2>\n";
		$contents .= "<div>\n";
		$contents .= "<ul>\n";
		$contents .= "<li><a target=\"_blank\" href=\"$albumURL\">My pictures</a></li>\n";
		$contents .= "<li>Gift idea ;-)</li>\n";
		$contents .= "<li>Usual tour 1h-1h30; possible to land somewhere else to eat/drink something but contact me.</li>\n";
		$contents .= "<li>Plan flight+1h to be ready without time constraints.</li>\n";
		$contents .= "<li>Departure:\n";
		$contents .= "<a target=\"_blank\" href=\"$gmaps\" title=\"Ecuvillens\">Ecuvillens</a> (NOT Gruy&egrave;re/Epagny!)\n";
		$contents .= "or Sion.\n";
		$contents .= "<li>Weather is most important decision factor thus decision is taken only at the departure time.</li>\n";
		$contents .= "<li>CHF&nbsp;$HourlyRate.-/h per person $IBANstr</li>\n";
		$contents .= "<li>Contact: $xnGmail</li>\n";
		$contents .= "</ul>\n";
		// FIXME I WAS HERE
		$contents .= "<h4>About the weather</h4>\n";
		$contents .= "<p>The weather is the most important decision factor for our flight.\n";
		$contents .= "I advise you to subscribe to 2 dates to have a backup in case we need to cancel the first one.\n";
		$contents .= "I can tell you GO or NO GO for sure only at the moment to start our flight.\n";
		$contents .= "I'll tell you in the days before how is the trend.\n";
		$contents .= "It is possible that in flight we notice we won't be able to go where we planned\n";
		$contents .= "and we go somewhere else or even we must get back after 10 minutes.</p>\n";
		$contents .= "</div>\n";
	//
		// Subscription
		$contents .= "<h2>Subscription</h2>\n";
		$contents .= "<p>If you have a clear idea of what kind of flight you want to make, contact me.\n";
		$contents .= "We can then discuss it and I can explain how much time we need and check the\n";
		$contents .= "availability of the plane and of myself.\n";
		$contents .= "Otherwise you can call me or send me an SMS/whatsapp/mail or subscribe directly to the\n";
		$contents .= RenderDoodle();
		$contents .= ".</p>\n";

		$contents .= "<p>If you want I can add you to my whatsapp list\n";
		$contents .= "(not a group where everyone sees all the numbers of the others)\n";
		$contents .= "to tell you when I have new dates and free places.</p>\n";

		$contents .= "<h4>Doodle</h4>\n";

		$contents .= "<p>On the " . RenderDoodle() . ", please make a line per person.\n";
		$contents .= "The number of available seats is indicated at the top of each column together\n";
		$contents .= "with the schedule and the place of departure.\n";
		$contents .= "Here is how my doodle works:</p>\n";
		$contents .= "<ul class=\"doodle\">\n";
			$contents .= "<li>\n";
			$contents .= "You can subscribe for a flight by ticking a $dy\n";
			$contents .= "As the weather may make us cancel the flight, you should also tick a\n";
			$contents .= "$di to have a reserve date.\n";
			$contents .= "If your first choice becomes cancelled, I would then change your\n";
			$contents .= "$di into a $dy\n";
			$contents .= "</li>\n";
		//
			$contents .= "<li>\n";
			$contents .= "You can tick a $dy under some $di,\n";
			$contents .= "but the latter would have priority upon you would their first choices be cancelled.\n";
			$contents .= "</li>\n";
		$contents .= "</ul>\n";
		$contents .= "<p>I will confirm you anyway if it is OK or not.</p>\n";
		$contents .= "<p>If no proposed date suits you, please contact me to plan another day.</p>\n";
		$contents .= "<p>If you have any question, do not hesitate to contact me ;-)</p>\n";
		$contents .= "<p>" . RenderDoodle("Here is the doodle.") . "</p>\n";
	//
		// Instructions
		$contents .= "<h2 id=\"instructions\">Instructions for the flight</h2>\n";
		$contents .= "<p>Here are some rules to follow before the flight so that everything\n";
		$contents .= "goes well:</p>\n";
		$contents .= "<ul>\n";
			$contents .= "<li>You must not at all go to a hyperbaric environment\n";
			$contents .= "(where the pressure is higher than the atmospheric pressure)\n";
			$contents .= "such as underwater diving in the 24h preceding the flight.\n";
			$contents .= "It could lead to a decompression incident during the flight which\n";
			$contents .= "can be fatal.</li>\n";
		//
			$contents .= "<li>Please avoid in the preceding hours:\n";
			$contents .= "smoking,\n";
			$contents .= "gaseous or alcohol drinks,\n";
			$contents .= "eat a lot of dietary fibers (such as cabbages).</li>\n";
		//
			$contents .= "<li>Please take with you:\n";
			$contents .= "sunglasses,\n";
			$contents .= "camera,\n";
			$contents .= "a bottle of water,\n";
			$contents .= "comfortable clothes (please avoid skirts), ";
			$contents .= "shoes in which you are comfortable to walk like sneakers (no high-heeled).</li>\n";
		//
			$contents .= "<li>The airplane works like a car: the engine heats the closed cabin.</li>\n";
		//
			$contents .= "<li>In case of pregnancy of more than 3 months, it is advised not\n";
			$contents .= "to fly in small airplanes.</li>\n";
		//
			$contents .= "<li>If you want to take children flying with you, please think about\n";
			$contents .= "what they will take from it and if they will remain seated calmly\n";
			$contents .= "more than one hour.\n";
			$contents .= "I do not take children under 5 years old.</li>\n";
		//
			$contents .= "<li>To be well prepared for the flight, I have to compute the aircraft's\n";
			$contents .= "performances. For this I need to know the weights of my passengers.\n";
			$contents .= "Please do not be surprised if I ask you this later.\n";
			$contents .= "This information will not be communicated to other people than me.</li>\n";
		$contents .= "</ul>\n";
	//
}


$body .= $page->SetTitle($page_title);
$page->HotBooty();

$body .= "<div class=\"wide\">\n";
$body .= "<div class=\"lhead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"chead\">\n";
$body .= "</div>\n";
$body .= "<div class=\"rhead\">\n";
$body .= "</div>\n";
$body .= "</div>\n";

$body .= "<div class=\"PAXpara\">\n";
$body .= $contents;
$body .= "</div>\n";
$body .= "<div>&nbsp;</div>\n";

$page->show($body);
unset($page);
?>
