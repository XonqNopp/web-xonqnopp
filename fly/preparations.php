<?php
function commonPreparations($page) {
    $aipF = $page->bodyBuilder->anchor("https://www.sia.aviation-civile.gouv.fr/documents/htmlshow", "France");
    $aipOe = $page->bodyBuilder->anchor("https://eaip.austrocontrol.at/", "&Ouml;sterreich");
    $aipEU = $page->bodyBuilder->anchor("https://www.eurocontrol.int/articles/ais-online", "Europe");

    $skybriefing = "<li>";
    $skybriefing .= $page->bodyBuilder->anchor("https://www.skybriefing.com/portal/dabs", "SkyBriefing (DABS)");
    $skybriefing .= " - " . $page->bodyBuilder->anchor("https://www.skybriefing.com/portal/fr/evfr-manual-gen", "eVFR manual");

    if($page->loginHelper->userIsAdmin()) {
        $smallTt = "<span class=\"tt smaller\">";
        foreach($page->miscInit->fly->skybriefing as $email => $pw) {
            $skybriefing .= "<br>{$smallTt}{$email}</span>&nbsp;-&nbsp;{$smallTt}{$pw}</span>\n";
        }
    }
    $skybriefing .= "</li>\n";

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
    $column .= $skybriefing;
    $column .= $mapCH;
    $column .= $page->bodyBuilder->liAnchor("http://navplan.ch", "navplan.ch");

    $column .= "<li>AIP: $aipF - $aipOe - $aipEU</li>\n";

    $column .= $page->bodyBuilder->liAnchor("http://ourairports.com/", "OurAirports");
    $column .= $page->bodyBuilder->liAnchor("http://www.wetterklima.de/flug/swc.htm", "SWC");
    $column .= "</ul>\n";

    return $column;
}
?>
