<?php
function liInstagram($page, $username) {
    return $page->bodyBuilder->liAnchor("https://www.instagram.com/$username", "<tt>@$username</tt>");
}


function instagramSources($page, $sources) {
    $body = "<p>Principales sources d'inspiration sur instagram:</p>\n";
    $body .= "<div><ul>\n";

    foreach($sources as $username) {
        $body .= liInstagram($page, $username);
    }

    $body .= "</ul></div>\n";
    return $body;
}
?>
