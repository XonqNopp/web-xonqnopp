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
$body .= $page->htmlHelper->setTitle("AI tricks");  // before HotBooty
$page->htmlHelper->hotBooty();

    $body .= $page->bodyBuilder->titleAnchor("How to trick AI in giving better answers");
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


echo $body;
?>
