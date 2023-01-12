<?php
function commonPreparations($page, $userIsAdmin) {
    $metar = $page->bodyBuilder->liAnchor(
        "http://www.meteosuisse.admin.ch/home/service-et-publications/conseil-et-service/previsions-aeronautiques-meteorologie-aeronautique/metar-taf.html",
        "METAR CH"
    );

    $dabs = $page->bodyBuilder->liAnchor("https://www.skybriefing.com/portal/dabs", "DABS");

    $eVfrManual = $page->bodyBuilder->anchor("https://www.skybriefing.com/portal/fr/evfr-manual-gen", "eVFR manual");
    $aipF = $page->bodyBuilder->anchor("https://www.sia.aviation-civile.gouv.fr/html/frameset_aip_fr.htm", "France");
    $aipOe = $page->bodyBuilder->anchor("http://eaip.austrocontrol.at/lo/141114/ad_2.htm", "&Ouml;sterreich");
    $aipEU = $page->bodyBuilder->anchor("https://www.eurocontrol.int/articles/ais-online", "Europe");

    $skybriefing = "<li>";
    $skybriefing .= $page->bodyBuilder->anchor("https://www.skybriefing.com/portal/", "SkyBriefing");
    if($userIsAdmin) {
        $skybriefing .= "<br /><tt>Pilotes@sion.ch</tt><br /><tt>1950Sion</tt>";
    }
    $skybriefing .= "</li>\n";

    $skyvector = $page->bodyBuilder->liAnchor("http://skyvector.com/", "SkyVector planning");
    $navplan = $page->bodyBuilder->liAnchor("http://navplan.ch", "navplan.ch");

    $chartsEU = $page->bodyBuilder->liAnchor("http://www.flyingineurope.be/aviation_weather_maps.htm", "Europe charts");
    $ourAirports = $page->bodyBuilder->liAnchor("http://ourairports.com/", "OurAirports");
    $wetterklima = $page->bodyBuilder->liAnchor("http://www.wetterklima.de/flug/swc.htm", "more weather links");
    $noaa = $page->bodyBuilder->liAnchor("http://weather.noaa.gov/pub/fax/PGDE14.PNG", "NOAA.gov");

    // CH map
    $mapCH = $page->bodyBuilder->liAnchor(
        "http://map.geo.admin.ch/?"
        . "bgLayer=ch.swisstopo.pixelkarte-farbe"
        . "&amp;layers=ch.bazl.luftfahrtkarten-icao,ch.swisstopo.pixelkarte-farbe-pk50.noscale"
        . "&amp;X=184982.69"
        . "&amp;Y=562080.39"
        . "&amp;zoom=3"
        . "&amp;lang=en"
        . "&amp;layers_opacity=1,0",
        "ICAO CH chart"
    );


    $column  = "<ul>\n";
    $column .= $metar;
    $column .= $dabs;
    $column .= $mapCH;
    $column .= $skybriefing;
    $column .= $navplan;
    $column .= $skyvector;

    $column .= "<li>AIP:\n$eVfrManual\n-\n$aipF\n-\n$aipOe\n-\n$aipEU</li>\n";

    $column .= $chartsEU;
    $column .= $ourAirports;
    $column .= $wetterklima;
    $column .= $noaa;
    $column .= "</ul>\n";

    return $column;
}
?>
