<?php
require_once("functions/page_helper.php");
$funcpath = "functions";
$page = new PhPage();
//$page->logger->levelUp(6);


$redirect = "index.php";
if(isset($_GET["from"]) && $_GET["from"] !== NULL && $_GET["from"] != "") {
    $redirect = $_GET["from"];
}

$page->loginHelper->checkWrongData();


$body = $page->bodyBuilder->goHome();
$body .= $page->htmlHelper->setTitle("Login");
$page->htmlHelper->hotBooty();

$body .= "<form action=\"$redirect\" method=\"post\">\n";

$body .= "<div style=\"padding: 3mm;\">";
$body .= "Password&nbsp;: <input type=\"password\" name=\"loginPW\" value=\"\" autofocus=\"autofocus\">";
$body .= "</div>\n";

$body .= "<div id=\"mew\">\n";
$body .= "<select name=\"fire\">\n";
$body .= "<option value=\"hot\" selected=\"selected\">remember</option>\n";
$body .= "<option value=\"cold\">forget</option>\n";
$body .= "</select>\n";
$body .= "</div><!-- mew -->\n";

$body .= "<div style=\"padding: 3mm;\">";
$body .= "<input type=\"submit\" name=\"enter\" value=\"Enter\">";
$body .= "</div>\n";

$body .= "</form>\n";

echo $body;
?>
