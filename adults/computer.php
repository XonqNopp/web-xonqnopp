<?php
require_once("../functions/page_helper.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);

require_once("shared.php");

// debug
//$page->htmlHelper->init();
//$page->logger->levelUp(6);

$page->bobbyTable->init();
//$userIsAdmin = $page->loginHelper->userIsAdmin();

$body = "";


$body = $page->bodyBuilder->goHome(NULL, "..");
// Set title and hot booty
$body .= $page->htmlHelper->setTitle("Utiliser les ordinateurs");  // before HotBooty
$page->htmlHelper->hotBooty();


$body .= instagramSources(
    array(
        "chatgptricks",
        "forgoodcode",
    )
);


    $body .= $page->bodyBuilder->titleAnchor("AI tricks");
    $body .= $page->bodyBuilder->titleAnchor("How to trick AI in giving better answers", 3);
    // chatgptricks
    $body .= "<div><dl>\n";

    $body .= "<dt>You explained this yesterday, but I forgot about that:</dt>\n";
    $body .= "<dd>Thinks more carefully to be sure not to contradict itself</dd>\n";

    $body .= "<dt>You are a 145 IQ specialist in engineering. Analyze ...</dt>\n";
    $body .= "<dd>Sophisticated answers. 130 -> decent. 160 -> mindblowing</dd>\n";

    $body .= "<dt>Obviously, ...</dt>\n";
    $body .= "<dd>Will correct you and explain instead of agreeing</dd>\n";

    $body .= "<dt>Explain ... like you are teaching a packed audience.</dt>\n";
    $body .= "<dd>Changes structure, adds emphasis and examples, anticipates questions</dd>\n";

    $body .= "<dt>Explain this using only ... analogies.</dt>\n";
    $body .= "<dd>Finds unexpected connections</dd>\n";

    $body .= "<dt>Let's bet $100: ...</dt>\n";
    $body .= "<dd>Will scrutinize harder</dd>\n";

    $body .= "<dt>My colleagues say this is wrong. Defend it or admit they are right.</dt>\n";
    $body .= "<dd>Forces to evaluate instead of just explaining</dd>\n";

    $body .= "<dt>Give me a version 2.0 of this idea.</dt>\n";
    $body .= "<dd>Makes a sequel that needs to innovate, not only polish</dd>\n";

    $body .= "</dl></div>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("Websites");

$websites = array(
    // forgoodcode
    "http://flightradar24.com" => "follow live air traffic",
    "http://10minutemail.com" => "temporary email address for website too curious",
    "http://web.archive.org" => "find any existing/dead website and see how it was earlier",
    "http://haveibeenpwned.com" => "check if your credentials have been subject to data breach",
    "http://bugmenot.com" => "provides shared login details for websites forcing account creation",
    "http://whois.domaintools.com" => "website data: owner, creation date, expiration date, hosting...",
    "http://downforeveryoneorjustme.com" => "check if a website is down",
    "http://justdeleteme.xyz" => "listing websites with a rating how hard it is to delete your data",
    "http://futureme.org" => "write an email and choose when it gets delivered",
    "http://remove.bg" => "remove background from any picture",
);


function liu($url, $description) {
    global $page;
    $res = $page->bodyBuilder->anchor($url) . ": $description";
    return "<li>$res</li>\n";
}


$body .= "<div><ul>\n";
foreach($websites as $website => $description) {
    $body .= liu($website, $description);
}
$body .= "</ul></div>\n";


echo $body;
?>
