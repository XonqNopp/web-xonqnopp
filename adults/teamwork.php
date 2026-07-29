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
$body .= $page->htmlHelper->setTitle("Team work");  // before HotBooty
$page->htmlHelper->hotBooty();

$body .= "<p>Fail fast = learn fast.</p>\n";

$body .= "<p>When you listen to someone, you don't simply process the words, it is much more than that.\n";
$body .= "The way the speaker is talking and behaving also play an important role in the message.\n";
$body .= "The proportion of the ways the speaker communicates is:</p>\n";

$body .= "<div><ul>\n";
$body .= $page->bodyBuilder->lili("10% words");
$body .= $page->bodyBuilder->lili("40% tone");
$body .= $page->bodyBuilder->lili("50% body language");
$body .= "</ul></div>\n";

    $body .= $page->bodyBuilder->titleAnchor("Mastering change");

    $body .= "<p>Amygdalia (emotion, threat) too fast for pre-frontal cortex (reasoning) to react.</p>\n";
    $body .= "<p>Brain is a prediction machine. Change means more prediction errors.</p>\n";

    $body .= "<p>To help us analyze a change objectively, we can use the SCARF model:</p>\n";
    $body .= "<div><ul>\n";
    $body .= $page->bodyBuilder->lili("<b>Status:</b> Does this change reduce my value/expertise?");
    $body .= $page->bodyBuilder->lili("<b>Certainty:</b> Do I know what is coming next?");
    $body .= $page->bodyBuilder->lili("<b>Autonomy:</b> Do I have any control?");
    $body .= $page->bodyBuilder->lili("<b>Relatedness:</b> Do I belong here?");
    $body .= $page->bodyBuilder->lili("<b>Fairness:</b> Is this process fair?");
    $body .= "</ul></div>\n";

    $body .= "<p>Tools to adapt to change:</p>\n";
    $body .= "<div><ul>\n";
    $body .= $page->bodyBuilder->lili("micro steps to big result");
    $body .= $page->bodyBuilder->lili("name it to tame it");
    $body .= $page->bodyBuilder->lili("reframe threat as challenge");
    $body .= "</ul></div>\n";

    $body .= "<p><i>You don't get used to change - you get better at it.\n";
    $body .= "The greatest danger in times of turbulence is not the turbulence - it is to act with yesterday's logic.</i>\n";
    $body .= "-&nbsp;Peter Drucker</p>\n";
//
    $body .= $page->bodyBuilder->titleAnchor("Trust and collaboration");

    $body .= "<p><i>Psychological safety is the belief that one can speak up with ideas, questions, concerns or mistakes\n";
    $body .= "without fear of negative consequences.</i>\n";
    $body .= "-&nbsp;Amy Edmondson</p>\n";

    $body .= "<p>Don't assume, ask.\n";
    $body .= "No judging.\n";
    $body .= "Listen to learn, not to speak.</p>\n";

        // Trust equation
        $body .= $page->butler->tableOpen(array("style" => "text-align: center;"), false);
            $body .= $page->butler->rowOpen(array(), false);
            $body .= $page->butler->cell("Trust equation: trust = ", array("rowspan" => 2));
            $body .= $page->butler->cell("(credibility + reliability + intimacy)", array("style" => "border-bottom: solid 1px;"));
            $body .= $page->butler->rowClose();
        //
            $body .= $page->butler->rowOpen(array(), false);
            $body .= $page->butler->cell("self-orientation");
            $body .= $page->butler->rowClose();
        $body .= $page->butler->tableClose();

    $body .= "<div><ul>\n";
    $body .= $page->bodyBuilder->lili("Credibility: they know their stuff.");
    $body .= $page->bodyBuilder->lili("Reliability: they always deliver.");
    $body .= $page->bodyBuilder->lili("Intimacy: I feel safe with them.");
    $body .= $page->bodyBuilder->lili("Self-orientation: are they focused on my interests or theirs?");
    $body .= "</ul></div>\n";

    $body .= "<p>Trust is the foundation.\n";
    $body .= "When team members are able to offer opinions and debate ideas, they feel heard and respected, and will be more likely to commit to decisions.</p>\n";

    $body .= "<p>'Can you live with this decision?'</p>\n";

    $body .= "<p>Collaboration does not start when we agree. It starts when we stay committed to a shared purpose - even if we disagree.\n";
    $body .= "Collaboration is not harmony. It is productive tension in service of a goal.</p>\n";
    $body .= "<div><ul>\n";
    $body .= $page->bodyBuilder->lili("Better decisions require different perspectives.");
    $body .= $page->bodyBuilder->lili("Innovation needs disagreement.");
    $body .= $page->bodyBuilder->lili("Silence slows execution.");
    $body .= "</ul></div>\n";


echo $body;
?>
