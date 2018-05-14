<?php
function commonPreparations($userIsAdmin, $miscInit, $skybriefingLogin="") {
	$metar = "<li><a target=\"_blank\" href=\"http://www.meteosuisse.admin.ch/home/service-et-publications/conseil-et-service/previsions-aeronautiques-meteorologie-aeronautique/metar-taf.html\" title=\"METAR CH\">METAR CH</a></li>\n";
	$dabs = "<li><a target=\"_blank\" href=\"https://www.skybriefing.com/portal/dabs\">DABS</a></li>\n";
	$homebriefing = "";
	$skybriefing = "";
	$aipCH = "<li><a target=\"_blank\" href=\"http://www.delta-fox-fox.ch/vfr/\">AIP CH</a></li>\n";
	$vfrGuide = "<li><a target=\"_blank\" href=\"http://www.skyguide.ch/fileadmin/user_upload/publications/AIM/VFR-Guide.pdf\">VFR Guide CH</a></li>\n";
	$aipF = "<li><a target=\"_blank\" href=\"https://www.sia.aviation-civile.gouv.fr/html/frameset_aip_fr.htm\">AIP France</a></li>\n";
	$aipOe = "<li><a target=\"_blank\" href=\"http://eaip.austrocontrol.at/lo/141114/ad_2.htm\">&Ouml;sterreich AIP</a></li>\n";
	$aipEU = "<li><a target=\"_blank\" href=\"https://www.eurocontrol.int/articles/ais-online\" title=\"&euro; AIP\">&euro; AIP</a></li>\n";
	$homebriefing = "<li><a target=\"_blank\" href=\"https://www.homebriefing.com/aes/login.jsp\">HomeBriefing</a>";
	if($userIsAdmin) {
		$homebriefing .= "<br />mib<br />" . $miscInit->homebriefing;
	}
	$homebriefing .= "</li>\n";

	$skybriefing = "<li>";
	$skybriefing .= "<a target=\"_blank\" href=\"https://www.skybriefing.com/portal/\">SkyBriefing</a>";
	if($userIsAdmin && $skybriefingLogin != "") {
		$skybriefing .= "<br />$skybriefingLogin";
	}
	$skybriefing .= "</li>\n";

	$skyvector = "<li><a target=\"_blank\" href=\"http://skyvector.com/\">SkyVector planning</a></li>\n";
	$ourAirports = "<li><a target=\"_blank\" href=\"http://ourairports.com/\">OurAirports</a></li>\n";
	$noaa = "<li><a target=\"_blank\" href=\"http://weather.noaa.gov/pub/fax/PGDE14.PNG\">NOAA.gov</a></li>\n";
	$wetterklima = "<li><a target=\"_blank\" href=\"http://www.wetterklima.de/flug/swc.htm\">more weather links</a></li>\n";
	$chartsEU = "<li><a target=\"_blank\" href=\"http://www.flyingineurope.be/aviation_weather_maps.htm\">Europe charts</a></li>\n";

	//// CH map
	$mapCH  = "<li>";
	$mapCH .= "<a target=\"_blank\" href=\"";
	$mapCH .= "http://map.geo.admin.ch/?";
	$mapCH .= "bgLayer=ch.swisstopo.pixelkarte-farbe";
	$mapCH .= "&amp;";
	$mapCH .= "layers=ch.bazl.luftfahrtkarten-icao,ch.swisstopo.pixelkarte-farbe-pk50.noscale";
	$mapCH .= "&amp;";
	$mapCH .= "X=184982.69";
	$mapCH .= "&amp;";
	$mapCH .= "Y=562080.39";
	$mapCH .= "&amp;";
	$mapCH .= "zoom=3";
	$mapCH .= "&amp;";
	$mapCH .= "lang=en";
	$mapCH .= "&amp;";
	$mapCH .= "layers_opacity=1,0";
	$mapCH .= "\">";
	$mapCH .= "ICAO CH chart";
	$mapCH .= "</a>";
	$mapCH .= "</li>\n";


	$column  = "<ul>\n";
	$column .= $metar;
	$column .= $dabs;
	$column .= $homebriefing;
	$column .= $skybriefing;
	$column .= $mapCH;
	$column .= $aipCH;
	$column .= $vfrGuide;
	$column .= $aipF;
	$column .= $aipOe;
	$column .= $aipEU;
	$column .= $chartsEU;
	$column .= $ourAirports;
	$column .= $skyvector;
	$column .= $noaa;
	$column .= $wetterklima;
	$column .= "</ul>\n";

	return $column;
}
?>
