<?php
require("functions/page_helper.php");
$funcpath = "functions";
$page = new PhPage();
//$page->logger->levelUp(6);

if(isset($_POST["pw"])) {
	// Page login
	$hash = "69398554371d64e4580e329f7c137cf101cddce22a9fe8d18c1482ec2fd5dc6ff6a9650965c8b0072ece977e99b32924ca6ed011361c90ca86a8b15e3cd10717";
	if($page->cryptoHelper->hache($_POST["pw"], $hash)) {
		$page->loginHelper->loginSuccessful(1, "index.php", $_POST["fire"] == "cold");

	} else {
		$page->logger->error("Wrong password!");
	}
}

$body = $page->bodyHelper->goHome();
$body .= $page->htmlHelper->setTitle("Login");
$page->htmlHelper->hotBooty();

$body .= "<form action=\"login.php\" method=\"post\">\n";
$body .= "<div class=\"whole\">\n";
$body .= "<div id=\"upwd\">\n";
$body .= "Password&nbsp;: <input type=\"password\" name=\"pw\" value=\"\" autofocus=\"autofocus\"/>\n";
$body .= "</div>\n";
$body .= "<div id=\"mew\">\n";
$body .= "<select name=\"fire\">\n";
$body .= "<option value=\"hot\" selected=\"selected\">remember</option>\n";
$body .= "<option value=\"cold\">forget</option>\n";
$body .= "</select>\n";
$body .= "</div>\n";
$body .= "<div id=\"usub\">\n";
$body .= "<input type=\"submit\" name=\"enter\" value=\"Enter\" />\n";
$body .= "</div>\n";
$body .= "</div>\n";
$body .= "</form>\n";

/*** Printing ***/
echo $body;
unset($page);
?>
