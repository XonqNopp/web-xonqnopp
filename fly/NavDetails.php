<?php
/*** Created: Wed 2015-07-08 11:59:56 CEST
 * TODO:
 * do not compute MH(wind) if MC is V(isual)
 */
require("../functions/classPage.php");
$rootPath = "..";
$funcpath = "$rootPath/functions";
require("NavDefaults.php");

$DocVersion = 49;

/*
 * WARNING: default variation is hardcoded in function declaration of LaTeX head.
 */
$page = new PhPage($rootPath);
$page->initDB();
//// debug
//$page->initHTML();
//$page->LogLevelUp(6);
//// CSS
$page->CSS_ppJump();
$page->CSS_ppWing();

$body = "";
$GI = $page->UserIsAdmin();
//
	/// Init vars
	$maxRow = 17;
	$ReserveTime = 45;  // [min]
	//
		/// Plane
		$PlaneType = "";
		$PlaneID = "";
		$PlanningSpeed = 0;
		$ClimbSpeed = 0;
		$FuelCons = 0;
		$FuelUnit = "";
		$UnusableFuel = 0;
		$DryMass = 0;
		$DryMassUnit = "";
		$DryMoment = 0;
		$DryMomentUnit = "";
		$DryTimestamp = "";
		$ArmUnit = "";
		$FrontArm = 0;
		$RearArm = 0;
		$LuggageArm = 0;
		$FuelArm = 0;
		$MTOW = 0;
		$minGC = 0;
		$maxGC = 0;
//
//
	//// duplicate
	if(isset($_GET["dup"]) && $GI) {
		$dupID = $_GET["dup"];
		$copy_nav = "INSERT INTO `" . $page->ddb->DBname . "`.`NavList` (`name`, `MapUsed`, `plane`, `Power`, `PowerManifold`, `PowerManifoldUnit`, `PowerRPM`, `altitude`, `variation`, `FrontMass`, `RearMass`, `LuggageMass`, `comment`) SELECT 
	concat(`name`, ' (COPY)'), `MapUsed`, `plane`, `Power`, `PowerManifold`, `PowerManifoldUnit`, `PowerRPM`, `altitude`, `variation`, 0, 0, $defaultLuggageMass, `comment` FROM `" . $page->ddb->DBname . "`.`NavList` WHERE `NavList`.`id` = ?";
		$copy_WP  = "INSERT INTO `" . $page->ddb->DBname . "`.`NavWaypoints` (`NavID`, `WPnum`, `waypoint`, `TC`, `distance`, `altitude`, `windTC`, `windSpeed`, `notes`, `climbing`) SELECT ?, `WPnum`, `waypoint`, `TC`, `distance`, `altitude`, `windTC`, `windSpeed`, `notes`, `climbing` FROM `" . $page->ddb->DBname . "`.`NavWaypoints` WHERE `NavWaypoints`.`NavID` = ?";
		$qNav = $page->DB_QueryPrepare($copy_nav);
		$qWP  = $page->DB_QueryPrepare($copy_WP );
		$qNav->bind_param("i", $dupID);
		$page->DB_ExecuteManage($qNav);
		$newID = $qNav->insert_id;
		$qWP->bind_param("ii", $newID, $dupID);
		$page->DB_ExecuteManage($qWP);
		// redirect to edit page so we can change title and nav infos
		$page->HeaderLocation("NavNew.php?id=$newID");
	}
//
//
	//// some functions
		//// sin degrees
		function sind($alpha) {
			return sin($alpha * pi() / 180.0);
		}
	//
		//// asin degrees
		function asind($val) {
			return 180.0 / pi() * asin($val);
		}
	//
		//// Compute EET
		function ComputeEET($distance, $speed) {
			return round($distance * 60.0 / $speed);
		}
	//
		//// Fuel time
		function FuelTime($time, $cons) {
			return ceil($time * $cons / 60.0);
		}
	//
		// heading visual
		function headingText($heading, $WPnum) {
			if($heading > 0) {
				return sprintf("%03d", $heading);
			}

			if($WPnum == 1 || $WPnum == 99 || $WPnum == 101 || $WPnum == 199 || $WPnum == 999) {
				return "VAC";
			} else {
				return "V";
			}
		}
	//
		/// LaTeX head
		function LaTeXhead($DocVersion, $plane = 0, $Power = 0, $name = "", $PlaneID = "", $PlaneType = "", $NavAltitude = 0, $PlanningSpeed = 0, $ClimbSpeed = 0, $variation = 2, $PowerManifold = 0, $PowerRPM = 0) {
			// head of LaTeX (already setting power)
				$latexhead = "";
				$latexhead .= "%%\n";
				$latexhead .= "\\newcommand*{\\DocVersion}{ $DocVersion}\n";
					$latexhead .= "%% Headers %%% {{{\n";
					$latexhead .= "\\documentclass[12pt,twoside,a4paper]{article}\n";
					$latexhead .= "%%\n";
						$latexhead .= "%% Document variables %%% {{{\n";
						$latexhead .= "\\newcommand*{\\Gael}{Ga\\\"el Induni}\n";
						$latexhead .= "\\newcommand*{\\ThisAuthors}{\\Gael}\n";
						$latexhead .= "\\newcommand*{\\ThisTitle}{Navigation plan}\n";
						$latexhead .= "\\newcommand*{\\ThisTitleSHORT}{\\ThisTitle}\n";
						$latexhead .= "%% End of document variables %%% }}}\n";
					//
						$latexhead .= "%% Usepackages %%% {{{\n";
							$latexhead .= "%% General {{{\n";
							$latexhead .= "\\usepackage[T1]{fontenc}\n";
							$latexhead .= "\\usepackage{lmodern}\n";
							$latexhead .= "\\usepackage[english]{babel}\n";
							$latexhead .= "%% }}}\n";
						//
							$latexhead .= "%% Math %% {{{\n";
							$latexhead .= "\\usepackage{amsmath}\n";
							$latexhead .= "%\\usepackage{amssymb}\n";
							$latexhead .= "%% }}}\n";
						//
							$latexhead .= "%% Hyper refs %% {{{\n";
							$latexhead .= "\\usepackage{hyperref}\n";
							$latexhead .= "\\hypersetup{\n";
							$latexhead .= "	colorlinks        = true,\n";
							$latexhead .= "	bookmarks         = true,\n";
							$latexhead .= "	bookmarksnumbered = false,\n";
							$latexhead .= "	linkcolor         = black,\n";
							$latexhead .= "	urlcolor          = blue,\n";
							$latexhead .= "	citecolor         = blue,\n";
							$latexhead .= "	filecolor         = blue,\n";
							$latexhead .= "	hyperfigures      = true,\n";
							$latexhead .= "	breaklinks        = false,\n";
							$latexhead .= "	ps2pdf,\n";
							$latexhead .= "	pdftitle          = {\\ThisTitle},\n";
							$latexhead .= "	pdfsubject        = {\\ThisTitle},\n";
							$latexhead .= "	pdfauthor         = {\\ThisAuthors}\n";
							$latexhead .= "}\n";
							$latexhead .= "%% }}}\n";
						//
							$latexhead .= "%% Tables and lists %% {{{\n";
							$latexhead .= "\\usepackage{longtable}\n";
							$latexhead .= "\\usepackage{multirow}\n";
							$latexhead .= "\\usepackage{colortbl}\n";
							$latexhead .= "\\usepackage{hhline}\n";
							$latexhead .= "%% }}}\n";
						//
							$latexhead .= "%% Making stuff fancy (not fancyhdr package) %% {{{\n";
							$latexhead .= "\\usepackage{enumitem}\n";
							$latexhead .= "\\setlist{noitemsep}\n";
							$latexhead .= "\\usepackage[landscape,a4paper]{geometry}\n";
							$latexhead .= "%% }}}\n";
						//
							$latexhead .= "%% Miscellaneous %% {{{\n";
							$latexhead .= "%\\usepackage{ifthen}\n";
							$latexhead .= "%% }}}\n";
						$latexhead .= "%% End of usepackages %%% }}}\n";
					//
						$latexhead .= "%% Document size %%% {{{\n";
						$latexhead .= "\\setlength{\\topmargin}{-8mm}\n";
						$latexhead .= "\\setlength{\\textheight}{180mm}\n";
						$latexhead .= "\\setlength{\\hoffset}{-28mm}\n";
						$latexhead .= "\\setlength{\\textwidth}{280mm}\n";
						$latexhead .= "\\setlength{\\evensidemargin}{9mm}\n";
						$latexhead .= "\\setlength{\\parskip}{0.5ex}\n";
						$latexhead .= "%% End of document size %%% }}}\n";
					//
						$latexhead .= "%% Fancy and header and footer rules %%% {{{\n";
						$latexhead .= "\\usepackage{fancyhdr}\n";
						$latexhead .= "%\\usepackage{lastpage}\n";
						$latexhead .= "%\\newcommand*{\\LastPage}{\\pageref{LastPage}}\n";
						$latexhead .= "%% Defining document filename\n";
						$latexhead .= "\\newcommand*{\\GoodJob}{\\jobname.pdf}\n";
						$latexhead .= "%% Fancy!\n";
						$latexhead .= "\\fancypagestyle{plain}{%\n";
						$latexhead .= "\\fancyhf{}\n";
						$latexhead .= "\\fancyhf[HR]{\\ThisAuthors}\n";
						$latexhead .= "\\fancyhf[HL]{NavPlan.pdf\\ v.~\\DocVersion}\n";
						$latexhead .= "%\\fancyhf[FRO,FLE]{\\thepage/\\LastPage}\n";
						$latexhead .= "%% Rules at top and bottom\n";
						$latexhead .= "}\n";
						$latexhead .= "\\pagestyle{plain}\n";
						$latexhead .= "\\renewcommand{\\headrulewidth}{0.4pt}\n";
						$latexhead .= "%\\renewcommand{\\footrulewidth}{0.4pt}\n";
						$latexhead .= "%% End of fancy %%% }}}\n";
					//
						$latexhead .= "%% Pictures positions %%% {{{\n";
						$latexhead .= "\\renewcommand{\\topfraction}{0.85}\n";
						$latexhead .= "\\renewcommand{\\textfraction}{0.1}\n";
						$latexhead .= "\\renewcommand{\\floatpagefraction}{0.75}\n";
						$latexhead .= "%% End of pictures %%% }}}\n";
					//
						$latexhead .= "%% New commands %%% {{{\n";
							$latexhead .= "%% Hyperref {{{\n";
							$latexhead .= "\\newcommand*{\\mailto}[1]{\\href{mailto:#1}{#1}}\n";
							$latexhead .= "%% \\url{link}\n";
							$latexhead .= "%% \\href{link}{text}\n";
							$latexhead .= "%% \\href[ref]{text}\n";
							$latexhead .= "%% }}}\n";
						//
							$latexhead .= "%% Miscellaneous %% {{{\n";
							$latexhead .= "\\renewcommand{\\epsilon}{\\varepsilon}\n";
							$latexhead .= "\\renewcommand{\\roman}{\\Roman}\n";
							$latexhead .= "%\\renewcommand{\\geq}{\\geqslant}\n";
							$latexhead .= "%\\renewcommand{\\leq}{\\leqslant}\n";
							$latexhead .= "\\newcommand*{\\oC}{\\ensuremath{^{\\circ}C}}\n";
							$latexhead .= "\\AtBeginDocument{\\renewcommand{\\labelitemi}{\\textbullet}}% Default is \\textendash % must be placed after begin-doc\n";
							$latexhead .= "\\makeatletter\n";
							$latexhead .= "\\newcommand{\\rom}[1]{\\expandafter\\@slowromancap\\romannumeral #1@}\n";
							$latexhead .= "\\makeatother\n";
							$latexhead .= "%% }}}\n";
						//
						$latexhead .= "\\newcommand*{\\DarkGray}{\\cellcolor[gray]{0.66}}\n";
						$latexhead .= "\\newcommand*{\\Gray}{\\cellcolor[gray]{0.8}}\n";
						$latexhead .= "\\newcommand*{\\RedCell}{\\cellcolor[rgb]{1,0,0}}\n";
						$latexhead .= "%% End of new commands %%% }}}\n";
					$latexhead .= "%% }}}\n";
				$latexhead .= "%%\n";
				$latexhead .= "\\begin{document}\n";
				$latexhead .= "%%\n";
				$latexhead .= "\\mbox{}\n";
				$latexhead .= "\\vspace{-16mm}\n";
				$latexhead .= "%% Header {{{\n";
				$latexhead .= "\\begin{longtable}{|c|c|l|c||c|}\n";
				$latexhead .= "xxxxxxxxxxxxxxxxxxxxxxxxx xxxxxxxxxxxxxxxxxxxxxxxxx\n";
				$latexhead .= "& xxxxxxxxxxxxx\n";
				$latexhead .= "&\n";
				$latexhead .= "& 0000000000\n";
				$latexhead .= "&\\kill\n";
				$latexhead .= "%\\multicolumn{2}{|c|}{Flight}\n";
				$latexhead .= "Flight\n";
				$latexhead .= "& Aircraft\n";
				$latexhead .= "& Identification\n";
				$latexhead .= "& TAS  [kts]\n";
				$latexhead .= "& VAR $[^{\\textrm{o}} \\textrm{E}]$\n";
				$latexhead .= "\\\\\\hline\n";
			//
				// Fill second line with nav infos
				$latexName1 = "";
				$latexName2 = $name;
				if(preg_match("/SKIP/", $name)) {
					$latexName1 = preg_replace("/SKIP.*$/", "", $name);
					$latexName2 = preg_replace("/^.*SKIP/", "", $name);
				}
				$longID = preg_replace("/-/", "---", $PlaneID);
				$latexhead .= "%%\n";
				if($latexName1 != "") {
					$latexhead .= "\\multicolumn{1}{|l|}{" . "$latexName1} ";
				}
				$latexhead .= "&\\multirow{2}{*}{" . "$PlaneType}\n";
				$latexhead .= "&\\multirow{2}{*}{" . "$longID}\n";
				$latexhead .= "&\\multirow{2}{*}{";
				if($PlanningSpeed > 0) {
					$latexhead .= $PlanningSpeed;
					if($ClimbSpeed > 0) {
						$latexhead .= " ($ClimbSpeed)";
					}
				}
				$latexhead .= "}\n";
				$latexhead .= "&\\multirow{2}{*}{" . "$variation}\n";
				$latexhead .= "\\\\\n";
				////
				$latexhead .= "\\multicolumn{1}{|l|}{" . "$latexName2}\n";
				$latexhead .= "&&\n";
				$latexhead .= "&\n";
				$latexhead .= "&\\\\[2mm]\n";
			//
				//// next of head
				$latexhead .= "\n\\end{longtable}\n";
				$latexhead .= "%% }}}\n";
				$latexhead .= "\\vspace{-8.7mm}\n";
				$latexhead .= "\\renewcommand*{\\arraystretch}{1.7}\n";
				$latexhead .= "%% Nav plan {{{\n";
				$latexhead .= "\\begin{longtable}{%\n";
				$latexhead .= "	|c|l|%\n";
				$latexhead .= "	>{\\columncolor[gray]{0.75}}c|c|c|c|c|%\n";
				$latexhead .= "	|>{\\columncolor[gray]{0.88}}c|>{\\columncolor[gray]{0.88}}c|c|c|c|%\n";
				$latexhead .= "	|c|c||l|%\n";
				$latexhead .= "}\n";
				$latexhead .= "i&bla bla bla bla bla bla&00000&00000&&altitudeee&&00000&&00000&00000&&0000&0000& bla\n";
				$latexhead .= "bla bla bla bla bla bla\\kill\n";
				$latexhead .= "%% Head {{{\n";
				$latexhead .= "\\hline\n";
				$latexhead .= "\\multicolumn{2}{|c|}{\\multirow{2}{*}{Waypoint}} &\n";
				$latexhead .= "TC &\n";
				$latexhead .= "MC &\n";
				$latexhead .= "Dist &\n";
				$latexhead .= "\\multirow{2}{*}{Altitude} &\n";
				$latexhead .= "EET &\n";
				$latexhead .= "\\multicolumn{2}{c|}{\\cellcolor[gray]{0.88}Wind} &\n";
				$latexhead .= "MH &\n";
				$latexhead .= "GS &\n";
				$latexhead .= "EET &\n";
				$latexhead .= "\\multirow{2}{*}{ETO} &\n";
				$latexhead .= "\\multirow{2}{*}{ATO} &\n";
				$latexhead .= "\\multirow{2}{*}{Notes}\n";
				$latexhead .= "\\\\\n";
				$latexhead .= "\\hhline{~~~~~~~--~~~~~~}\n";
				$latexhead .= "\\multicolumn{2}{|c|}{} &\n";
				$latexhead .= "$[^{\\textrm{o}}]$ &\n";
				$latexhead .= "$[^{\\textrm{o}}]$ &\n";
				$latexhead .= "[NM] &\n";
				$latexhead .= "&\n";
				$latexhead .= "[min] &\n";
				$latexhead .= "$[^{\\textrm{o}}]$ &\n";
				$latexhead .= "[kts] &\n";
				$latexhead .= "$[^{\\textrm{o}}]$ &\n";
				$latexhead .= "[kts] &\n";
				$latexhead .= "[min] &\n";
				$latexhead .= "&\n";
				$latexhead .= "&\n";
				$latexhead .= "\\\\\n";
				$latexhead .= "\\hline\n";
				$latexhead .= "%% }}}\n";
				$latexhead .= "\\endhead\n";
				$latexhead .= "\\hline\\endfoot\n";
			return $latexhead;
		}
	//
		/// LaTeX 2nd page left column
		function LaTeX2left($FuelCons = 0, $FuelUnit = "~~") {
			//// beginning of second page
			$latex2_1 = "";
			$latex2_1 .= "\\end{longtable}\n";
			$latex2_1 .= "\\renewcommand*{\\arraystretch}{1.0}\n";
			$latex2_1 .= "%% }}}\n";
			$latex2_1 .= "%%\n";
			$latex2_1 .= "\\clearpage\n";
			$latex2_1 .= "\\fancyhf{}\n";
			$latex2_1 .= "\\renewcommand{\\headrulewidth}{0pt}\n";
			$latex2_1 .= "%%\n";
			$latex2_1 .= "\\noindent\n";
			$latex2_1 .= "%%\n";
			$latex2_1 .= "\\begin{minipage}{0.57\\textwidth}\n";
			$latex2_1 .= "{\\Large\n";
			$latex2_1 .= "\\textbf{Climb:} don't forget speed is lower!\n";
			$latex2_1 .= "\\\\[7mm]\n";
			$latex2_1 .= "%% TOD {{{\n";
			$latex2_1 .= "\\textbf{TOD:}\n";
			$latex2_1 .= "\\begin{itemize}\n";
			$latex2_1 .= "\\renewcommand*{\\labelitemi}{\\phantom{(}\\textbullet\\phantom{)}}\n";
			$latex2_1 .= "	\\item 120kts (2NM/min) 500 ft/min (children <12y: 300)\n";
			$latex2_1 .= "	\\item 1NM attitude change\n";
			$latex2_1 .= "	\\item speed decreasing 2NM\n";
			$latex2_1 .= "	\\item[(\\textbullet)] approach check 2NM if needed\n";
			$latex2_1 .= "\\end{itemize}\n";
			$latex2_1 .= "%% }}}\n";
			$latex2_1 .= "}\n";
			$latex2_1 .= "\\vspace{11mm}\n";
			$latex2_1 .= "%% Fuel {{{\n";
			$latex2_1 .= "\\begin{center}\n";
			$latex2_1 .= "\\Large\n";
			$latex2_1 .= "\\textbf{Fuel per hour:}\n";
			$latex2_1 .= ($FuelCons > 0) ? $FuelCons : "~~";
			$latex2_1 .= " $FuelUnit/h\\\\[9mm]\n";
			$latex2_1 .= "%%\n";
			$latex2_1 .= "\\begin{tabular}{|l||r@{ :}c|r||r@{ :}c|r|}\n";
			$latex2_1 .= "\\hhline{~------}\n";
			$latex2_1 .= "\\multicolumn{1}{c|}{}\n";
			$latex2_1 .= "& \\multicolumn{3}{c||}{Theory}\n";
			$latex2_1 .= "& \\multicolumn{3}{c|}{Realm}\n";
			$latex2_1 .= "\\\\\\hhline{~------}\n";
			$latex2_1 .= "\\multicolumn{1}{c|}{}\n";
			$latex2_1 .= "& \\multicolumn{2}{c|}{time}\n";
			$latex2_1 .= "& fuel [$FuelUnit]\n";
			$latex2_1 .= "& \\multicolumn{2}{c|}{time}\n";
			$latex2_1 .= "& fuel [$FuelUnit]\n";
			$latex2_1 .= "\\\\\\hline\n";
			return $latex2_1;
		}
	//
		/// LaTeX 2nd page right column
		function LaTeX2right() {
			//// change to 2nd column
			$latex2_2 = "";
			$latex2_2 .= "\\\\\\hline\n";
			$latex2_2 .= "\\end{tabular}\n";
			$latex2_2 .= "\\end{center}\n";
			$latex2_2 .= "%% }}}\n";
			$latex2_2 .= "\\vspace*{6mm}\n";
			$latex2_2 .= "\\end{minipage}\n";
			$latex2_2 .= "\\begin{minipage}{0.42\\textwidth}\n";
			$latex2_2 .= "%% TH with wind {{{\n";
			$latex2_2 .= "{\n";
			$latex2_2 .= "TH with wind:\n";
			$latex2_2 .= "\\begin{enumerate}\n";
			$latex2_2 .= "\\item $\\alpha_1 = (360 + \\textrm{TC} - \\textrm{WH}) \\% 360^{\\circ}$ and $\\textrm{sign} = 1$\n";
			$latex2_2 .= "\\item if $\\alpha_1 > 180: \\alpha_1 = (360 + \\textrm{WH} - \\textrm{TC}) \\% 360^{\\circ}$ and $\\textrm{sign} = -1$\n";
			$latex2_2 .= "\\item $\\alpha_2 = \\arcsin \\left( \\frac{\\textrm{WS}}{\\textrm{TS}} \\cdot \\sin \\alpha_1 \\right)$\n";
			$latex2_2 .= "\\item $\\alpha_3 = 180 - (\\alpha_1 + \\alpha_2)$\n";
			$latex2_2 .= "\\item $\\textrm{TH} = \\textrm{TC} + \\textrm{sign} \\cdot \\alpha_2$\n";
			$latex2_2 .= "\\item $\\textrm{GS} = \\frac{\\sin \\alpha_3}{\\sin \\alpha_1} \\cdot \\textrm{TS}$\n";
			$latex2_2 .= "\\end{enumerate}\n";
			$latex2_2 .= "}\n";
			$latex2_2 .= "%% }}}\n";
			$latex2_2 .= "%% W'n'B {{{\n";
			$latex2_2 .= "{\\Large\n";
			$latex2_2 .= "\\begin{center}\n";
			$latex2_2 .= "\\begin{tabular}{|l|r|r|r|}\n";
			$latex2_2 .= "\\hhline{~---}\n";
			$latex2_2 .= "\\multicolumn{1}{c|}{}\n";
			$latex2_2 .= "& \\multicolumn{1}{c|}{Mass}\n";
			$latex2_2 .= "& \\multicolumn{1}{c|}{Arm}\n";
			$latex2_2 .= "& \\multicolumn{1}{c|}{Moment}\n";
			$latex2_2 .= "\\\\\n";
			return $latex2_2;
		}
	//
		/// LaTeX end
		function LaTeXend() {
			//// end of LaTeX
			$latexend = "";
			$latexend .= "\\hline\n";
			$latexend .= "\\end{tabular}\n";
			$latexend .= "\\end{center}\n";
			$latexend .= "\\vspace{-2mm}\n";
			$latexend .= "{\n";
			$latexend .= "\\small\n";
			$latexend .= "$1\\ \\textrm{USG} = 3.785\\ l$\n";
			$latexend .= "\\hspace{17mm}\n";
			$latexend .= "$1\\ l\\ \\textrm{AVGAS} = 0.72\\ kg$\n";
			$latexend .= "\\\\\n";
			$latexend .= "$1\\ \\textrm{Imp.G} = 4.55\\ l$\n";
			$latexend .= "\\hspace{17mm}\n";
			$latexend .= "$1\\ \\textrm{USG Avgas} = 6$ lbs\n";
			$latexend .= "}\n";
			$latexend .= "}\n";
			$latexend .= "%% }}}\n";
			$latexend .= "\\end{minipage}\n";
			$latexend .= "\\end{document}\n";
			$latexend .= "%%\n";
			return $latexend;
		}
	//
		/// LaTeX row
		function LaTeXrow($WPnum = -1, $oldWP = -1, $waypoint = "", $destination = "", $notes = "", $TC = 0, $MC = 0, $altitude = 0, $distance = 0, $climbing = false, $TheoricEET = 0, $HasWind = False, $windTC = 0, $windSpeed = 0, $MH = 0, $GS = 0, $RealEET = 0, $DestinationDistance = 0, $TripDistance = 0, $AlternateDistance = 0, $TheoricDestinationTime = 0, $TheoricTripTime = 0, $TheoricAlternateTime = 0, $RealTripTime = 0, $RealDestinationTime = 0, $RealAlternateTime = 0) {
			$back = new stdClass();
			$inc = 1;
			$latexcontent = "";
			if($WPnum == 0 || $WPnum == 101) {
				if($WPnum == 101) {
					$inc -= 1;
					$latexcontent .= "\\hhline{===============}\n";
				}
				$latexcontent .= "&";
				if($WPnum == 0) {
					$latexcontent .= $waypoint;
				} else {
					$latexcontent .= $destination;
				}
				$latexcontent .= "&\\DarkGray\n";
				$latexcontent .= "&\\DarkGray\n";
				$latexcontent .= "&\\DarkGray\n";
				$latexcontent .= "&\\DarkGray\n";
				$latexcontent .= "&\\DarkGray\n";
				$latexcontent .= "&\n";
				$latexcontent .= "&\n";
				$latexcontent .= "&\\DarkGray\n";
				$latexcontent .= "&\\DarkGray\n";
				$latexcontent .= "&\\DarkGray\n";
				$latexcontent .= "&\\DarkGray\n";
				$latexcontent .= "&\n";
				$latexcontent .= "&";
				if($WPnum == 0 && $notes != "") {
					$latexcontent .= " $notes ";
				}
				$latexcontent .= "\\\\";
			}
			if($WPnum > 0) {
				if($WPnum == 901 || ($WPnum == 999 && $oldWP < 900)) {
					$latexcontent .= "\\hhline{===============}\n";
				} else {
					$latexcontent .= "\\hline\n";
				}
				$latexcontent .= "& $waypoint";
				$latexcontent .= "& ";
				if($TC > 0) {
					$latexcontent .= sprintf("%03d", $TC);
				}
				$latexcontent .= " & {\\large ";
				$latexcontent .= headingText($MC, $WPnum);
				$latexcontent .= "}";
				$latexcontent .= " & {\\large $distance}";
				$latexcontent .= " &";
				if($altitude > 0) {
					$latexcontent .= " {\\large $altitude}";
				}
				$latexcontent .= " & ";
				if($climbing) {
					$latexcontent .= "{\\footnotesize\\textcopyright} ";
				}
				$latexcontent .= " {\\large $TheoricEET";
				if($WPnum == 1 || $WPnum == 99 || $WPnum == 101 || $WPnum == 199 || $WPnum == 999) {
					$latexcontent .= "+5";
				}
				$latexcontent .= "} &";
				if($HasWind) {
					$latexcontent .= " " . sprintf("%03d", $windTC);
					$latexcontent .= " & $windSpeed";
					$latexcontent .= " & {";
					$latexcontent .= "\\large " . headingText($MH, $WPnum);
					$latexcontent .= "}";
					$latexcontent .= " & {" . "$GS}";
					$latexcontent .= " &";
					if($climbing) {
						$latexcontent .= " {\\footnotesize\\textcopyright}";
					}
					$latexcontent .= " {\\large $RealEET";
					if($WPnum == 1 || $WPnum == 99 || $WPnum == 101 || $WPnum == 199 || $WPnum == 999) {
						$latexcontent .= "+5";
					}
					$latexcontent .= "}";
				} else {
					$latexcontent .= "&&&&";
					if($climbing) {
						$latexcontent .= " {\\footnotesize\\textcopyright}";
					}
					if($WPnum == 1 || $WPnum == 99 || $WPnum == 101 || $WPnum == 199 || $WPnum == 999) {
						$latexcontent .= " {\\large +5}";
					}
				}
				$latexcontent .= " &";
				$latexcontent .= " &";
				$latexNotes1 = preg_replace("/ *SKIP */", ", ", $notes);
				$latexcontent .= " & ";
				$latexcontent .= $latexNotes1;
				$latexcontent .= "\\\\";
				//
				//
					//// make summary if required
					if($WPnum == 99 || $WPnum == 199 || $WPnum == 999) {
						$inc += 1;
						$latexcontent .= "\\hhline{----=-=----=---}\n";
						$latexcontent .= "&&&& ";
						if($WPnum == 99) {
							$latexcontent .= $DestinationDistance;
						} elseif($WPnum == 199) {
							$latexcontent .= ($TripDistance - $DestinationDistance);
						} else {
							$latexcontent .= $AlternateDistance;
						}
						$latexcontent .= " && ";
						if($WPnum == 99) {
							if($TheoricDestinationTime > 0) {$latexcontent .= $TheoricDestinationTime;}
						} elseif($WPnum == 199) {
							$destTime = ($TheoricTripTime - $TheoricDestinationTime);
							if($destTime > 0) {$latexcontent .= $destTime;}
						} else {
							if($TheoricAlternateTime > 0) {$latexcontent .= $TheoricAlternateTime;}
						}
						$latexcontent .= " &&&&&";
						if($WPnum == 99) {
							if($RealTripTime > 0) {$latexcontent .= " $RealDestinationTime ";}
						} elseif($WPnum == 199) {
							if($RealTripTime > 0) {$latexcontent .= " " . ($RealTripTime - $RealDestinationTime) . " ";}
						} else {
							if($RealAlternateTime > 0) {$latexcontent .= " $RealAlternateTime ";}
						}
						$latexcontent .= "&&&\\\\";
						if($WPnum == 999) {
							$latexcontent .= "\\hline\n";
						}
					}
				//
			}
			$back->contents = $latexcontent;
			$back->inc = $inc;
			return $back;
		}
	//
		/// LaTeX finish 1st page
		function LaTeXfinish1($WPnum, $rows, $maxRow) {
			$latexcontent = "";
			if($WPnum == 0) {
				$latexcontent .= "\\hline\n";
			} elseif($WPnum == 99) {
				$latexcontent .= "\\hhline{~-~~~~~~~~~~--~}\n";
			}
			while($rows < $maxRow - 1) {
				$rows++;
				$latexcontent .= "&&&&&&&&&&&&&&\\\\\\hline\n";
			}
			return $latexcontent;
		}
	//
		/// LaTeX fuel
		function LaTeXfuel($page, $TheoricTripTime = 0, $TheoricTripFuel = 0, $TheoricAlternateTime = 0, $TheoricAlternateFuel = 0, $RealTripTime = 0, $RealTripFuel = 0, $RealAlternateTime = 0, $RealAlternateFuel = 0, $ReserveFuel = 0, $TheoricMinimumFuel = 0, $RealMinimumFuel = 0, $TheoricExtra = 0, $RealExtra = 0, $TheoricFuel = 0, $RealFuel = 0, $UnusableFuel=0) {
			$latexfuel = "";
				//// compute more numbers
				$TheoricTripHours        =                 $page->minutes2HoursInt($TheoricTripTime);
				$TheoricTripMinutes      = sprintf("%02d", $page->minutes2MinutesRest($TheoricTripTime));
				$TheoricAlternateHours   =                 $page->minutes2HoursInt($TheoricAlternateTime);
				$TheoricAlternateMinutes = sprintf("%02d", $page->minutes2MinutesRest($TheoricAlternateTime));
				$RealTripHours           =                 $page->minutes2HoursInt($RealTripTime);
				$RealTripMinutes         = sprintf("%02d", $page->minutes2MinutesRest($RealTripTime));
				$RealAlternateHours      =                 $page->minutes2HoursInt($RealAlternateTime);
				$RealAlternateMinutes    = sprintf("%02d", $page->minutes2MinutesRest($RealAlternateTime));
			//
				//// trip
				$latexfuel .= "Trip:      &";
				if($TheoricTripTime > 0) {
					$latexfuel .= " $TheoricTripHours&$TheoricTripMinutes ";
				} else {
					$latexfuel .= "&";
				}
				$latexfuel .= "&";
				if($TheoricTripFuel > 0) {
					$latexfuel .= " $TheoricTripFuel ";
				}
				$latexfuel .= "&";
				if($RealTripTime > 0) {
					$latexfuel .= " $RealTripHours&$RealTripMinutes &";
					if($RealTripFuel > 0) {
						$latexfuel .= " $RealTripFuel ";
					}
				} else {
					$latexfuel .= "&&";
				}
				$latexfuel .= "\\\\\\hline\n";
			//
				//// alternate
				$latexfuel .= "Alternate: &";
				if($TheoricAlternateTime > 0) {
					$latexfuel .= " $TheoricAlternateHours&$TheoricAlternateMinutes ";
				} else {
					$latexfuel .= "&";
				}
				$latexfuel .= "&";
				if($TheoricAlternateFuel > 0) {
					$latexfuel .= " $TheoricAlternateFuel ";
				}
				$latexfuel .= "&";
				if($RealAlternateTime > 0) {
					$latexfuel .= " $RealAlternateHours&$RealAlternateMinutes &";
					if($RealTripFuel > 0) {
						$latexfuel .= " $RealAlternateFuel ";
					}
				} else {
					$latexfuel .= "&&";
				}
				$latexfuel .= "\\\\\\hline\n";
			//
				//// reserve
				$latexfuel .= "Reserve:& 0&45 &";
				if($ReserveFuel > 0) {
					$latexfuel .= " $ReserveFuel ";
				}
				$latexfuel .= "& 0&45 &";
				if($RealTripFuel > 0 && $ReserveFuel > 0) {
					$latexfuel .= " $ReserveFuel ";
				}
				$latexfuel .= "\\\\\\hline\n";
			//
				// unusable
				$latexfuel .= "Unusable\n";
				$latexfuel .= "& \\multicolumn{2}{c}{\\DarkGray}\n";
				$latexfuel .= "& $UnusableFuel\n";
				$latexfuel .= "& \\multicolumn{2}{c}{\\DarkGray}\n";
				$latexfuel .= "& $UnusableFuel";
				$latexfuel .= "\\\\\\hline\n";
			$latexfuel .= "\\hline\n";
			//
				//// minimum
				$latexfuel .= "Minimum fuel:&\\multicolumn{2}{c}{\\DarkGray}&";
				if($TheoricMinimumFuel > $ReserveFuel) {
					$latexfuel .= " $TheoricMinimumFuel ";
				}
				$latexfuel .= "&\\multicolumn{2}{c}{\\DarkGray}&";
				if($RealMinimumFuel > $ReserveFuel) {
					$latexfuel .= " $RealMinimumFuel ";
				}
				$latexfuel .= "\\\\\\hline\n";
			//
				//// extra
				$latexfuel .= "Extra +5\\%:&\\multicolumn{2}{c}{\\DarkGray}&";
				if($TheoricMinimumFuel > $ReserveFuel) {
					$latexfuel .= " $TheoricExtra ";
				}
				$latexfuel .= "&\\multicolumn{2}{c}{\\DarkGray}&";
				if($RealMinimumFuel > $ReserveFuel) {
					$latexfuel .= " $RealExtra ";
				}
				$latexfuel .= "\\\\\\hline\\hline\n";
			//
				//// ramp fuel
				$latexfuel .= "Ramp fuel:\n";
				$latexfuel .= "& \\multicolumn{2}{c}{\\DarkGray}\n";
				$latexfuel .= "&";
				if($TheoricMinimumFuel > $ReserveFuel) {
					$latexfuel .= " $TheoricFuel\n";
				}
				$latexfuel .= "& \\multicolumn{2}{c}{\\DarkGray}\n";
				$latexfuel .= "& \\multicolumn{1}{||c||}{";
				if($RealMinimumFuel > $ReserveFuel) {
					$latexfuel .= $RealFuel;
				}
				$latexfuel .= "}\n";
			return $latexfuel;
		}
	//
		/// LaTeX GC
		function LaTeXGC($DryMassUnit = "~~", $ArmUnit = "~~", $DryMomentUnit = "~~", $DryMass = 0, $DryMoment = 0, $FrontMass = 0, $FrontArm = 0, $FrontMoment = 0, $RearMass = 0, $RearArm = 0, $RearMoment = 0, $LuggageMass = 0, $LuggageArm = 0, $LuggageMoment = 0, $minGC = 0, $maxGC = 0, $ZeroMass = 0, $ZeroGC = 0, $ZeroMoment = 0, $FuelMass = 0, $FuelArm = 0, $FuelMoment = 0, $ToffMass = 0, $ToffGC = 0, $ToffMoment = 0, $MTOW = 0, $UnusableFuelMass=0, $UnusableFuelMoment=0) {
			$latexGC = "";
			$redCell  = "\\RedCell";
			$grayCell = "\\Gray";
			//
				//// head
				$latexGC .= "\\multicolumn{1}{c|}{}\n";
				$latexGC .= "& \\multicolumn{1}{c|}{[$DryMassUnit]}\n";
				$latexGC .= "& \\multicolumn{1}{c|}{[$ArmUnit]}\n";
				$latexGC .= "& \\multicolumn{1}{c|}{[$DryMomentUnit]}\n";
				$latexGC .= "\\\\\\hhline{-===}\n";
			//
				//// empty
				$latexGC .= "Empty   &";
				$latexGC .= ($DryMass > 0) ? " $DryMass " : "";
				$latexGC .= "& \\DarkGray &";
				$latexGC .= ($DryMoment > 0) ? " $DryMoment" : "";
				$latexGC .= "\\\\\\hline\n";
			//
				//// front
				$latexGC .= "Front   &";
				$latexGC .= ($FrontMass > 0) ? $FrontMass : "";
				$latexGC .= "&";
				$latexGC .= ($FrontArm > 0) ? " $FrontArm " : "";
				$latexGC .= "&";
				$latexGC .= ($FrontMoment > 0) ? " " . round($FrontMoment, 3) . " " : "";
				$latexGC .= "\\\\\\hline\n";
			//
			if($RearArm > 0 || $FrontArm == 0) {
				//// rear
				$latexGC .= "Rear    &";
				$latexGC .= ($FrontMass > 0) ? " $RearMass " : "";
				$latexGC .= "&";
				$latexGC .= ($RearArm > 0) ? " $RearArm " : "";
				$latexGC .= "&";
				$latexGC .= ($RearMoment > 0) ? " " . round($RearMoment, 3) . " " : "";
				$latexGC .= "\\\\\\hline\n";
			}
			//
				//// luggage
				$latexGC .= "Luggage &";
				$latexGC .= ($FrontMass > 0) ? " $LuggageMass " : "";
				$latexGC .= "&";
				$latexGC .= ($LuggageArm > 0) ? " $LuggageArm " : "";
				$latexGC .= "&";
				$latexGC .= ($LuggageMoment > 0) ? " " . round($LuggageMoment, 3) . " " : "";
				$latexGC .= "\\\\\\hline\n";
			//
				// unusable fuel
				$latexGC .= "Unusable fuel";
				$latexGC .= "& $UnusableFuelMass";
				$latexGC .= "& $FuelArm";
				$latexGC .= "& " . round($UnusableFuelMoment, 3);
				$latexGC .= "\\\\\\hline\n";
			$latexGC .= "\\hline\n";
			//
				//// 0-fuel
				$gcMinStyle = ($minGC > 0 && $minGC > $ZeroGC) ? $redCell : $grayCell;
				$gcMaxStyle = ($maxGC > 0 && $maxGC < $ZeroGC) ? $redCell : $grayCell;
				$gcStyle    = (($minGC > 0 && $minGC > $ZeroGC) || ($maxGC > 0 && $maxGC < $ZeroGC)) ? $redCell : $grayCell;
				$latexGC .= "\\multirow{3}{*}{\\textbf{0-fuel}}\n";
				$latexGC .= "& \\Gray\n";
				$latexGC .= "& $gcMinStyle {\\normalsize min: ";
				$latexGC .= ($minGC == 0) ? "\phantom{ooooo}" : $minGC;
				$latexGC .= "}\n";
				$latexGC .= "&\\\\\n";
				$latexGC .= "& \\Gray";
				$latexGC .= ($DryMass > 0 && $FrontMass > 0) ? " $ZeroMass" : "";
				$latexGC .= "\n";
				$latexGC .= "& $gcStyle";
				$latexGC .= ($DryMass > 0 && $FrontMass > 0) ? " $ZeroGC" : "";
				$latexGC .= "\n";
				$latexGC .= "&";
				$latexGC .= ($DryMass > 0 && $FrontMass > 0) ? " $ZeroMoment" : "";
				$latexGC .= "\\\\\n";
				$latexGC .= "& \\Gray\n";
				$latexGC .= "& $gcMaxStyle {\\normalsize max: ";
				$latexGC .= ($maxGC == 0) ? "\phantom{ooooo}" : $maxGC;
				$latexGC .= "}\n";
				$latexGC .= "&\\\\\n";
				$latexGC .= "\\hhline{====}\n";
			//
				//// fuel
				$latexGC .= "Fuel &";
				$latexGC .= ($FuelMass > 0) ? " $FuelMass " : "";
				$latexGC .= "&";
				$latexGC .= ($FuelArm > 0) ? " $FuelArm " : "";
				$latexGC .= "&";
				$latexGC .= ($FuelMoment > 0) ? " " . round($FuelMoment, 3) . " " : "";
				$latexGC .= "\\\\\\hhline{====}\n";
			//
				//// T-off
				$gcMinStyle = ($minGC > 0 && $minGC > $ToffGC) ? $redCell : $grayCell;
				$gcMaxStyle = ($maxGC > 0 && $maxGC < $ToffGC) ? $redCell : $grayCell;
				$gcStyle    = (($minGC > 0 && $minGC > $ToffGC) || ($maxGC > 0 && $maxGC < $ToffGC)) ? $redCell : $grayCell;
				$MTOWstyle  = ($MTOW > 0 && $MTOW < $ToffMass) ? $redCell : $grayCell;
				$latexGC .= "\\multirow{3}{*}{\\textbf{T-off}}\n";
				$latexGC .= "& $MTOWstyle\n";
				$latexGC .= "& $gcMinStyle {\\normalsize min: ";
				$latexGC .= ($minGC == 0) ? "\phantom{ooooo}" : $minGC;
				$latexGC .= "}\n";
				$latexGC .= "&\\\\\n";
				$latexGC .= "& $MTOWstyle";
				$latexGC .= ($DryMass > 0 && $FrontMass > 0) ? " $ToffMass" : "";
				$latexGC .= "\n";
				$latexGC .= "& $gcStyle";
				$latexGC .= ($DryMass > 0 && $FrontMass > 0) ? " $ToffGC" : "";
				$latexGC .= "\n";
				$latexGC .= "&";
				$latexGC .= ($DryMass > 0 && $FrontMass > 0) ? " $ToffMoment" : "";
				$latexGC .= "\\\\\n";
				$latexGC .= "& $MTOWstyle {\\normalsize max: ";
				$latexGC .= ($MTOW > 0) ? $MTOW : "\\phantom{1000}";
				$latexGC .= "}\n";
				$latexGC .= "& $gcMaxStyle {\\normalsize max: ";
				$latexGC .= ($maxGC == 0) ? "\phantom{ooooo}" : $maxGC;
				$latexGC .= "}\n";
				$latexGC .= "&\\\\\n";
			return $latexGC;
		}
//
//
	//// nav details
	$navid = $_GET["id"];
	if($navid == 0) {
		$filename = "nav/navTemplate";
		$latexfile = fopen("$filename.tex", "w") or die(" Cannot write file $filename.tex");
		$latexhead = LaTeXhead($DocVersion);
		fwrite($latexfile, $latexhead);
		//
		//// beginning of second page
		$latex2_1 = LaTeX2left();
		//
		//// change to 2nd column
		$latex2_2 = LaTeX2right();
		//
		//// end of LaTeX
		$latexend = LaTeXend();
		$row = LaTeXrow(0);
		$rows += $row->inc;
		$latexcontent .= $row->contents;
		$latexcontent .= LaTeXfinish1(0, $rows, $maxRow);
		$latexfuel = LaTeXfuel($page);
		$latexGC = LaTeXGC();
		fwrite($latexfile, $latexcontent);
		fwrite($latexfile, $latex2_1);
		fwrite($latexfile, $latexfuel);
		fwrite($latexfile, $latex2_2);
		fwrite($latexfile, $latexGC);
		fwrite($latexfile, $latexend);
		//
		fclose($latexfile);
		/// download it??? or link on index?
		$page->HeaderLocation("NavList.php");
	}
	$check = $page->DB_IdManage("SELECT COUNT(*) AS `tot` FROM `NavList` WHERE `id` = ?", $navid);
	$check->bind_result($tot);
	$check->fetch();
	$check->close();
	if($tot == 0) {
		$page->HeaderLocation("NavList.php");
	}
	$filename = "nav/nav" . sprintf("%06d", $navid);
	$nav = $page->DB_SelectId("NavList", $navid);
	$nav->bind_result($navid, $name, $MapUsed, $plane, $Power, $PowerManifold, $PowerManifoldUnit, $PowerRPM, $NavAltitude, $variation, $FrontMass, $RearMass, $LuggageMass, $comment);
	$nav->fetch();
	$nav->close();
	$htmlName = preg_replace("/ SKIP/", ",", $name);
//
	//// gohome and make title
	$gohome = new stdClass();
	$gohome->page = "NavList";
	$gohome->rootpage = "index";
	$body .= $page->GoHome($gohome);
	$body .= $page->SetTitle("Nav: $htmlName");// before HotBooty
	$page->HotBooty();
//
	// heads
	$body .= "<div class=\"wide\">\n";
	$body .= "<div class=\"lhead\">\n";
	//if(file_exists("$filename.tex")) {
		$body .= "<a href=\"$filename.tex\" title=\"$htmlName LaTeX\">{$page->LaTeX}</a>\n";
		if(file_exists("$filename.pdf")) {
			$body .= "<br />\n";
			$body .= "<a href=\"$filename.pdf\" title=\"$htmlName PDF\">PDF</a>\n";
		}
	//}
	$body .= "</div>\n";
	$body .= "<div class=\"chead\">\n";
	$body .= "</div>\n";
	$body .= "<div class=\"rhead\">\n";
	if($GI) {
		$body .= "<a href=\"NavNew.php?id=$navid\" title=\"edit $htmlName\">edit</a>\n";
		$body .= "<br /><a href=\"NavNew.php\" title=\"new\">new</a>\n";
		$body .= "<br /><a href=\"NavDetails.php?dup=$navid\" title=\"duplicate\">duplicate</a>\n";
	}
	$body .= "</div>\n";
	$body .= "</div>\n";
//
	//// plane details
	if($plane > 0) {
		$theplane = $page->DB_SelectId("aircrafts", $plane);
		$theplane->bind_result($plane, $PlaneType, $PlaneID, $PlanningSpeed, $ClimbSpeed, $FuelCons, $FuelUnit, $UnusableFuel, $DryMass, $DryMassUnit, $DryMoment, $DryMomentUnit, $DryTimestamp, $ArmUnit, $FrontArm, $RearArm, $LuggageArm, $FuelArm, $MTOW, $minGC, $maxGC);
		$theplane->fetch();
		$theplane->close();
		$FrontArm = round($FrontArm, 3);
		$RearArm = round($RearArm, 3);
		$LuggageArm = round($LuggageArm, 3);
		$FuelArm = round($FuelArm, 3);
		$minGC = round($minGC, 3);
		$maxGC = round($maxGC, 3);
	}
	if($FuelUnit == "") {
		$FuelUnit = "?";
	}
//
	//// prepare LaTeX content and filehandle
	$latexfile = fopen("$filename.tex", "w") or die(" Cannot write file $filename.tex");
	$latexhead = LaTeXhead($DocVersion, $plane, $Power, $name, $PlaneID, $PlaneType, $NavAltitude, $PlanningSpeed, $ClimbSpeed, $variation, $PowerManifold, $PowerRPM);
	fwrite($latexfile, $latexhead);
	//
	//// beginning of second page
	$latex2_1 = LaTeX2left($FuelCons, $FuelUnit);
	//
	//// change to 2nd column
	$latex2_2 = LaTeX2right();
	//
	//// end of LaTeX
	$latexend = LaTeXend();
	//
//
$body .= "<div class=\"NavIntro\">\n";
$body .= "<div><b>Navigation:</b> $htmlName</div>\n";
$body .= "<div><b>Airplane:</b> ";
if($plane > 0) {
	$body .= "$PlaneID ($PlaneType)";
} else {
	$body .= "not chosen yet";
}
$body .= "</div>\n";
if($plane > 0) {
	$body .= "<div><b>Planning speed:</b> {$PlanningSpeed}kts (climb: {$ClimbSpeed}kts)</div>\n";
}
$body .= "<div><b>Variation:</b> $variation&deg;E</div>\n";
//// mass
if($FrontMass > 0) {
	$body .= "<div>\n";
	$body .= "<b>Mass</b>\n";
	$body .= "<ul>\n";
	if($FrontMass > 0) {
		$body .= "<li>Front: $FrontMass kg</li>\n";
	}
	if($RearMass > 0) {
		$body .= "<li>Rear: $RearMass kg</li>\n";
	}
	if($LuggageMass > 0) {
		$body .= "<li>Luggage: $LuggageMass kg</li>\n";
	}
	$body .= "</ul>\n";
	$body .= "</div>\n";
}
$body .= "</div>\n";
//
$body .= "<div>\n";
$body .= "<table>\n";
$warning = "";
//
	//// HTML table header
	$body .= "<tr>\n";
	if($GI) {
		$body .= "<th rowspan=\"2\"></th>\n";
	}
	$body .= "<th rowspan=\"2\">Waypoint</th>\n";
	$body .= "<th class=\"TC\">TC</th>\n";
	$body .= "<th>MC</th>\n";
	$body .= "<th>Dist.</th>\n";
	$body .= "<th>Altitude</th>\n";
	$body .= "<th>EET</th>\n";
	$body .= "<th class=\"wind\" colspan=\"2\">Wind</th>\n";
	$body .= "<th>MH</th>\n";
	$body .= "<th>GS</th>\n";
	$body .= "<th>EET</th>\n";
	$body .= "<th rowspan=\"2\">ETO</th>\n";
	$body .= "<th rowspan=\"2\">ATO</th>\n";
	$body .= "<th rowspan=\"2\">Notes</th>\n";
	$body .= "</tr>\n";
	$body .= "<tr>\n";
	$body .= "<th class=\"TC\">[&deg;]</th>\n";
	$body .= "<th>[&deg;]</th>\n";
	$body .= "<th>[NM]</th>\n";
	$body .= "<th>[ft]</th>\n";
	$body .= "<th>[min]</th>\n";
	$body .= "<th class=\"wind\">[&deg;]</th>\n";
	$body .= "<th class=\"wind\">[kts]</th>\n";
	$body .= "<th>[&deg;]</th>\n";
	$body .= "<th>[kts]</th>\n";
	$body .= "<th>[min]</th>\n";
	$body .= "</tr>\n";
//
$latexcontent = "";
$rows = 0;
$TheoricTripTime = 0;
$TheoricAlternateTime = 0;
$RealTripTime = 0;
$RealAlternateTime = 0;
$TripDistance = 0;
$AlternateDistance = 0;
$oldWindTC = 0;
$oldWindSpeed = 0;
$HasWind = false;
$destination = "";
$DestinationDistance = 0;
$TheoricDestinationTime = 0;
$RealDestinationTime = 0;
$WPnum = -1;
$oldWP = $WPnum;
$TH = 0;
$MH = 0;
$GS = 0;
$RealEET = 0;
$wp = $page->DB_QueryManage("SELECT * FROM `{$page->ddb->DBname}`.`NavWaypoints` WHERE `NavID` = $navid ORDER BY `WPnum` ASC");
while($w = $wp->fetch_object()) {
	//// run
		//// get data and compute
		$id = $w->id;
		$oldWP = $WPnum;
		$WPnum = $w->WPnum + 0;
		$waypoint = $w->waypoint;
		$TC = $w->TC + 0;
		$MC = 0;
		if($TC > 0) {
			$MC = (360 + $TC - $variation) % 360;
		}
		$distance = $w->distance + 0;
		$altitude = $w->altitude + 0;
		if($WPnum == 1 && $altitude == 0 && $NavAltitude > 0) {
			$altitude = $NavAltitude;
		}
		$windTC = $w->windTC + 0;
		$windSpeed = $w->windSpeed + 0;
		if($windTC > 0 && $windSpeed > 0) {
			if($WPnum == 1 || $HasWind) {
				$HasWind = true;
				$oldWindTC = $windTC;
				$oldWindSpeed = $windSpeed;
			} else {
				$windTC = 0;
				$windSpeed = 0;
			}
		} elseif($HasWind && $windTC == 0) {
			$windTC = $oldWindTC;
			$windSpeed = $oldWindSpeed;
		}
		$notes = $w->notes;
		$climbing = $w->climbing + 0;
		//
		//// sum distance
		if($WPnum < 200) {
			$TripDistance += $distance;
		} else {
			$AlternateDistance += $distance;
		}
		//
		if($HasWind) {
			if($TC == 0) {
				$TH = 0;
				$MH = 0;
			} else {
				//// wind angles
				$alpha1 = (360 + $TC - $windTC) % 360;
				$sign = 1;
				if($alpha1 > 180) {
					$alpha1 = (360 + $windTC - $TC) % 360;
					$sign = -1;
				}
				$alpha2 = asind(1.0 * $windSpeed / $speed * sind($alpha1));
				$alpha3 = 180 - $alpha1 - $alpha2;
				$TH = round($TC + $sign * $alpha2) % 360;
				$MH = (360 + $TH - $variation) % 360;
			}
		}
		//
		if($PlanningSpeed > 0) {
			//// time computation
			$speed = $PlanningSpeed;
			if($climbing && $ClimbSpeed > 0) {$speed = $ClimbSpeed;}
			$TheoricEET = ComputeEET($distance, $speed);
			if($WPnum < 200) {
				$TheoricTripTime += $TheoricEET;
			} else {
				$TheoricAlternateTime += $TheoricEET;
			}
			if($WPnum == 1 || $WPnum == 99 || $WPnum == 101 || $WPnum == 199 || $WPnum == 999) {
				if($WPnum < 200) {
					$TheoricTripTime += 5;
				} else {
					$TheoricAlternateTime += 5;
				}
			}
			if($HasWind) {
				if($TC == 0) {
					// No heading indication, take worst-case
					$GS = $speed - $windSpeed;
				} else {
					//// speed and time with wind
					if($windTC == $TC) {
						$GS = $speed + $windSpeed;
					} elseif($alpha1 == 180) {
						$GS = $speed - $windSpeed;
					} else {
						$GS = round($speed * sind($alpha3) / sind($alpha1));
					}
				}
				$RealEET = ComputeEET($distance, $GS);
				if($WPnum < 200) {
					$RealTripTime += $RealEET;
				} else {
					$RealAlternateTime += $RealEET;
				}
				//
				if($WPnum == 1 || $WPnum == 99 || $WPnum == 101 || $WPnum == 199 || $WPnum == 999) {
					if($WPnum < 200) {
						$RealTripTime += 5;
					} else {
						$RealAlternateTime += 5;
					}
				}
			}
		}
		if($WPnum == 99) {
			$destination = $waypoint;
			$DestinationDistance = $TripDistance;
			$TheoricDestinationTime = $TheoricTripTime;
			$RealDestinationTime = $RealTripTime;
		}
	//
	//
		//// display to page
		$htmlNotes = preg_replace("/ SKIP/", ",", $notes);
		$body .= "<tr class=\"WP$WPnum\" id=\"WP$WPnum\">\n";
		if($WPnum == 0 || $WPnum == 101) {
			//// 1st row ever or inbound
			if($GI) {
				$body .= "<td class=\"edit\">\n";
				if($WPnum == 0) {
					$body .= "<a href=\"NavWP.php?id=$id\" title=\"edit $waypoint\">edit</a>\n";
				}
				$body .= "</td>\n";
			}
			$body .= "<td class=\"waypoint\">";
			if($WPnum == 0) {
				$body .= $waypoint;
			} else {
				$body .= $destination;
			}
			$body .= "</td>\n";
			$body .= "<td colspan=\"11\" class=\"unavailable\"></td>\n";
			$body .= "<td></td>\n";
			$body .= "<td class=\"notes\">";
			if($WPnum == 0) {
				$body .= "$htmlNotes</td>\n";
			} else {
				$body .= "</td>\n";
			}
			if($WPnum == 101) {
				$body .= "</tr>\n";
				$body .= "<tr class=\"WP$WPnum\">\n";
			}
		}
		if($WPnum > 0) {
			//// proceed std
			if($WPnum == 901 || ($WPnum == 999 && $oldWP < 900)) {
				//// half-cell for WP
				$colspan = 14;
				if($GI) {
					$colspan += 1;
				}
				$body .= "<td class=\"nav-alternate-title\" colspan=\"$colspan\">Alternate</td>\n";
				$body .= "</tr>\n";
				$body .= "<tr class=\"WP$WPnum\">\n";
			}
				//// edit link
				if($GI) {
					$body .= "<td class=\"edit\">\n";
					$body .= "<a href=\"NavWP.php?id=$id\" title=\"edit $waypoint\">edit</a>\n";
					$body .= "</td>\n";
				}
			//
				//// WP
				$body .= "<td class=\"waypoint\">$waypoint</td>\n";
			//
				//// TC
				$body .= "<td class=\"TC heading\">";
				if($TC > 0) {
					$body .= sprintf("%03d", $TC);
				}
				$body .= "</td>\n";
			//
				//// MC
				$body .= "<td class=\"heading\">";
				$body .= headingText($MC, $WPnum);
				$body .= "</td>\n";
			//
			//// distance
			$body .= "<td class=\"distance\">$distance</td>\n";
			//
				//// altitude
				$body .= "<td class=\"altitude\">";
				if($altitude > 0) {
					$body .= "$altitude";
				}
				$body .= "</td>\n";
			//
				//// Theoric EET
				$body .= "<td class=\"EET\">";
				if($climbing) {
					$body .= "&copy;";
				}
				$body .= $TheoricEET;
				if($WPnum == 1 || $WPnum == 99 || $WPnum == 101 || $WPnum == 199 || $WPnum == 999) {
					$body .= "+5";
				}
				$body .= "</td>";
			//
			if($HasWind) {
				//// wind
				$body .= "<td class=\"wind heading\">" . sprintf("%03d", $windTC) . "</td>\n";
				$body .= "<td class=\"wind speed\">$windSpeed</td>\n";
				$body .= "<td class=\"heading\">";
				$body .= headingText($MH, $WPnum);
				$body .= "</td>\n";
				$body .= "<td class=\"speed\">$GS</td>\n";
				$body .= "<td class=\"EET\">";
				if($climbing) {
					$body .= "&copy;";
				}
				$body .= $RealEET;
				if($WPnum == 1 || $WPnum == 99 || $WPnum == 101 || $WPnum == 199 || $WPnum == 999) {
					$body .= "+5";
				}
				$body .= "</td>";
			} else {
				//// NO wind
				$body .= "<td class=\"wind\"></td>\n";
				$body .= "<td class=\"wind\"></td>\n";
				$body .= "<td></td>\n";
				$body .= "<td></td>\n";
				$body .= "<td>\n";
				if($climbing) {
					$body .= "&copy;";
				}
				if($WPnum == 1 || $WPnum == 99 || $WPnum == 101 || $WPnum == 199 || $WPnum == 999) {
					$body .= "+5";
				}
				$body .= "</td>";
			}
				//// ETO + ATO
				$body .= "<td></td>\n";
				$body .= "<td></td>\n";
			//
				//// notes
				$body .= "<td class=\"notes\">$htmlNotes</td>\n";
			//
			//
				//// make summary if required
				if($WPnum == 99 || $WPnum == 199 || $WPnum == 999) {
					$body .= "</tr>\n";
					$body .= "<tr class=\"summary\">\n";
					if($GI) { $body .= "<td></td>\n"; }
					$body .= "<td class=\"WP\"></td>\n";
					$body .= "<td class=\"TC\"></td>\n";
					$body .= "<td></td>\n";
					$body .= "<td class=\"distance sum\">";
					if($WPnum == 99) {
						$body .= $TripDistance;
					} elseif($WPnum == 199) {
						$body .= ($TripDistance - $DestinationDistance);
					} else {
						$body .= $AlternateDistance;
					}
					$body .= "</td>\n";
					$body .= "<td></td>\n";
					$body .= "<td class=\"EET sum\">";
					if($WPnum == 99) {
						if($TheoricTripTime > 0) {$body .= $TheoricTripTime;}
					} elseif($WPnum == 199) {
						$destTime = ($TheoricTripTime - $TheoricDestinationTime);
						if($destTime > 0) {$body .= $destTime;}
					} else {
						if($TheoricAlternateTime > 0) {$body .= $TheoricAlternateTime;}
					}
					$body .= "</td>\n";
					$body .= "<td colspan=\"2\" class=\"wind\"></td>\n";
					$body .= "<td></td>\n";
					$body .= "<td></td>\n";
					$body .= "<td class=\"EET sum\">";
					if($WPnum == 99) {
						if($RealTripTime > 0) {$body .= $RealTripTime;}
					} elseif($WPnum == 199) {
						if($RealTripTime > 0) {$body .= ($RealTripTime - $RealDestinationTime);}
					} else {
						if($RealAlternateTime > 0) {$body .= $RealAlternateTime;}
					}
					$body .= "</td>\n";
					$body .= "<td></td>\n";
					$body .= "<td></td>\n";
					$body .= "<td></td>\n";
				}
			//
		}
		$body .= "</tr>\n";
	//
	//
		//// LaTeX
		$row = LaTeXrow($WPnum, $oldWP, $waypoint, $destination, $notes, $TC, $MC, $altitude, $distance, $climbing, $TheoricEET, $HasWind, $windTC, $windSpeed, $MH, $GS, $RealEET, $DestinationDistance, $TripDistance, $AlternateDistance, $TheoricDestinationTime, $TheoricTripTime, $TheoricAlternateTime, $RealTripTime, $RealDestinationTime, $RealAlternateTime);
		$rows += $row->inc;
		$latexcontent .= $row->contents;
		if($rows > $maxRow) {
			$warning = "<div class=\"warning\">Number of rows is large and table will span on more than one A4.</div>\n";
		}
}
$wp->close();
//
if($GI) {
	//// option to insert new WP
	$body .= "<tr>\n";
	$body .= "<td colspan=\"15\" class=\"newWP\">\n";
	$body .= "<a href=\"NavWP.php?nav=$navid\" title=\"new waypoint\">new waypoint</a>\n";
	$body .= "</td>\n";
	$body .= "</tr>\n";
}
$body .= "</table>\n";
$body .= $warning;
$body .= "</div>\n";
//
//// finish latex content 1st page with empty rows
$latexcontent .= LaTeXfinish1($WPnum, $rows, $maxRow);
//
//
	//// display more information
	$body .= "<div class=\"NavReminders\">\n";
	$body .= "<div><b>Climb:</b> don't forget speed is lower!</div>\n";
	$body .= "<div>\n";
	$body .= "<b>TOD:</b>\n";
	$body .= "<ul>\n";
	$body .= "<li>120kts (2NM/min) 500 ft/min (children &lt;12y: 300)</li>\n";
	$body .= "<li>1NM attitude change</li>\n";
	$body .= "<li>Speed decreasing 2NM</li>\n";
	$body .= "<li class=\"optional\">Approach check 2NM if needed</li>\n";
	$body .= "</ul>\n";
	$body .= "</div>\n";
	$body .= "</div>\n";
	$body .= "<div class=\"NavTHwind\">\n";
	$body .= "<b>Compute TH with wind:</b>\n";
	$body .= "<ol>\n";
	$body .= "<li>&alpha;1 = (360 + TC - WH) % 360 and sign=1<br />\n";
	$body .= "if &alpha;1 &gt; 180: &alpha;1 = (360 + WH - TC) % 360 and sign=-1</li>\n";
	$body .= "<li>&alpha;2 = arcsin(WS/TS sin(&alpha;1))</li>\n";
	$body .= "<li>&alpha;3 = 180 - &alpha;1 - &alpha;2</li>\n";
	$body .= "<li>TH = TC + sign &alpha;2</li>\n";
	$body .= "<li>GS = sin(&alpha;3) / sin(&alpha;1) TS</li>\n";
	$body .= "</ol>\n";
	$body .= "</div>\n";
//
//
	//// Fuel
		//// compute
		$TheoricTripFuel      = FuelTime($TheoricTripTime,      $FuelCons);
		$TheoricAlternateFuel = FuelTime($TheoricAlternateTime, $FuelCons);
		$RealTripFuel         = FuelTime($RealTripTime,         $FuelCons);
		$RealAlternateFuel    = FuelTime($RealAlternateTime,    $FuelCons);
		$ReserveFuel          = FuelTime($ReserveTime,          $FuelCons);
		$TheoricMinimumFuel = $TheoricTripFuel + $TheoricAlternateFuel + $ReserveFuel + $UnusableFuel;
		$RealMinimumFuel    = $RealTripFuel    + $RealAlternateFuel    + $ReserveFuel + $UnusableFuel;
		$TheoricExtra = ceil($TheoricMinimumFuel * 0.05);
		$RealExtra   = ceil($RealMinimumFuel    * 0.05);
		$TheoricFuel = $TheoricMinimumFuel + $TheoricExtra;
		$RealFuel    = $RealMinimumFuel    + $RealExtra;
		$FinalFuel = $TheoricFuel;
		if($RealMinimumFuel > $ReserveFuel + $UnusableFuel) {
			$FinalFuel = $RealFuel;
		}
		if($FuelUnit == "USG") {
			$FinalFuel *= 3.8;
		} elseif($FuelUnit == "ImpG") {
			$FinalFuel *= 4.5;
		}
	//
		//// display
		$body .= "<div class=\"fuel\">\n";
		$body .= "<p><b>Fuel per hour:</b> ";
		$body .= ($FuelCons > 0) ? $FuelCons : "?";
		$body .= " $FuelUnit/h</p>\n";
		$body .= "<table class=\"noborder\">\n";
			//// head
			$body .= "<tr>\n";
			$body .= "<td class=\"phantom\"></td>\n";
			$body .= "<th colspan=\"2\">Theory</th>\n";
			$body .= "<th colspan=\"2\">Realm</th>\n";
			$body .= "</tr>\n";
			$body .= "<tr>\n";
			$body .= "<td class=\"phantom\"></td>\n";
			$body .= "<th>time</th>\n";
			$body .= "<th>fuel [$FuelUnit]</th>\n";
			$body .= "<th>time</th>\n";
			$body .= "<th>fuel [$FuelUnit]</th>\n";
			$body .= "</tr>\n";
		//
			//// trip
			$body .= "<tr>\n";
			$body .= "<td>Trip:</td>\n";
			$body .= "<td>";
			if($TheoricTripTime > 0) {
				$body .= $page->minutesDisplay($TheoricTripTime);
			}
			$body .= "</td>\n";
			$body .= "<td>";
			if($TheoricTripFuel > 0) {
				$body .= $TheoricTripFuel;
			}
			$body .= "</td>\n";
			if($RealTripTime > 0) {
				$body .= "<td>{$page->minutesDisplay($RealTripTime)}</td>\n";
				$body .= "<td>";
				if($RealTripFuel > 0) {
					$body .= $RealTripFuel;
				}
				$body .= "</td>\n";
			} else {
				$body .= "<td></td>\n";
				$body .= "<td></td>\n";
			}
			$body .= "</tr>\n";
		//
			//// alternate
			$body .= "<tr>\n";
			$body .= "<td>Alternate:</td>\n";
			if($TheoricAlternateTime > 0) {
				$body .= "<td>{$page->minutesDisplay($TheoricAlternateTime)}</td>\n";
				$body .= "<td>";
				if($TheoricAlternateFuel > 0) {
					$body .= $TheoricAlternateFuel;
				}
				$body .= "</td>\n";
			} else {
				$body .= "<td></td>\n";
				$body .= "<td></td>\n";
			}
			if($RealAlternateTime > 0) {
				$body .= "<td>{$page->minutesDisplay($RealAlternateTime)}</td>\n";
				$body .= "<td>";
				if($RealAlternateFuel > 0) {
					$body .= $RealAlternateFuel;
				}
				$body .= "</td>\n";
			} else {
				$body .= "<td></td>\n";
				$body .= "<td></td>\n";
			}
			$body .= "</tr>\n";
		//
			//// reserve
			$body .= "<tr>\n";
			$body .= "<td>Reserve:</td>\n";
			$body .= "<td>0:45</td>\n";
			$body .= "<td>";
			if($ReserveFuel > 0) {
				$body .= $ReserveFuel;
			}
			$body .= "</td>\n";
			$body .= "<td>0:45</td>\n";
			$body .= "<td>";
			if($RealTripTime > 0 && $ReserveFuel > 0) {
				$body .= $ReserveFuel;
			}
			$body .= "</td>\n";
			$body .= "</tr>\n";
		//
			//// unusable
			$body .= "<tr>\n";
			$body .= "<td>Unusable:</td>\n";
			$body .= "<td class=\"unavailable\"></td>\n";
			$body .= "<td>";
			if($UnusableFuel > 0) {
				$body .= $UnusableFuel;
			}
			$body .= "</td>\n";
			$body .= "<td class=\"unavailable\"></td>\n";
			$body .= "<td>";
			if($RealTripTime > 0 && $UnusableFuel > 0) {
				$body .= $UnusableFuel;
			}
			$body .= "</td>\n";
			$body .= "</tr>\n";
		//
			//// minimum
			$body .= "<tr>\n";
			$body .= "<td>Minimum fuel:</td>\n";
			$body .= "<td class=\"unavailable\"></td>\n";
			$body .= "<td>";
			if($TheoricMinimumFuel > 0) {
				$body .= $TheoricMinimumFuel;
			}
			$body .= "</td>\n";
			$body .= "<td class=\"unavailable\"></td>\n";
			$body .= "<td>";
			if($RealTripTime > 0 && $RealMinimumFuel > 0) {
				$body .= $RealMinimumFuel;
			}
			$body .= "</td>\n";
			$body .= "</tr>\n";
		//
			//// extra
			$body .= "<tr>\n";
			$body .= "<td>Extra +5%:</td>\n";
			$body .= "<td class=\"unavailable\"></td>\n";
			$body .= "<td>";
			if($TheoricExtra > 0) {
				$body .= $TheoricExtra;
			}
			$body .= "</td>\n";
			$body .= "<td class=\"unavailable\"></td>\n";
			$body .= "<td>";
			if($RealTripTime > 0 && $RealExtra > 0) {
				$body .= $RealExtra;
			}
			$body .= "</td>\n";
			$body .= "</tr>\n";
		//
			//// ramp
			$body .= "<tr>\n";
			$body .= "<td>Ramp fuel:</td>\n";
			$body .= "<td class=\"unavailable\"></td>\n";
			$body .= "<td>";
			if($TheoricFuel > 0) {
				$body .= $TheoricFuel;
			}
			$body .= "</td>\n";
			$body .= "<td class=\"unavailable\"></td>\n";
			$body .= "<td>";
			if($RealTripTime > 0 && $RealFuel > 0) {
				$body .= $RealFuel;
			}
			$body .= "</td>\n";
			$body .= "</tr>\n";
		//
		$body .= "</table>\n";
		$body .= "</div>\n";
	//
		//// LaTeX
		$latexfuel = LaTeXfuel($page, $TheoricTripTime, $TheoricTripFuel, $TheoricAlternateTime, $TheoricAlternateFuel, $RealTripTime, $RealTripFuel, $RealAlternateTime, $RealAlternateFuel, $ReserveFuel, $TheoricMinimumFuel, $RealMinimumFuel, $TheoricExtra, $RealExtra, $TheoricFuel, $RealFuel, $UnusableFuel);
	//
	//
//
	//// GC MTOW
		//// compute
		$l2kg = 0.72;
		$UnusableFuelMass = round($UnusableFuel * $l2kg);
		$FuelMass = round($FinalFuel * $l2kg) - $UnusableFuelMass;
		if($DryMassUnit == "lbs") {
			$FrontMass   = round($FrontMass * 2.2);
			$RearMass    = round($RearMass * 2.2);
			$LuggageMass = round($LuggageMass * 2.2);
			$FuelMass    = round($FuelMass * 2.2);
		}
		$FrontMoment   = round($FrontMass   * $FrontArm, 3);
		$RearMoment    = round($RearMass    * $RearArm, 3);
		$LuggageMoment = round($LuggageMass * $LuggageArm, 3);
		$UnusableFuelMoment = round($UnusableFuelMass * $FuelArm, 3);
		$FuelMoment    = round($FuelMass    * $FuelArm, 3);
		$ZeroMass = $DryMass + $FrontMass + $RearMass + $LuggageMass + $UnusableFuelMass;
		$ZeroMoment = round($DryMoment + $FrontMoment + $RearMoment + $LuggageMoment + $UnusableFuelMoment, 3);
		$ZeroGC = 0;
		if($ZeroMass > 0) {
			$ZeroGC = round(1.0 * $ZeroMoment / $ZeroMass, 3);
		}
		$ToffMass = $ZeroMass + $FuelMass;
		$ToffMoment = round($ZeroMoment + $FuelMoment, 3);
		$ToffGC = 0;
		if($ToffMass > 0) {
			$ToffGC = round(1.0 * $ToffMoment / $ToffMass, 3);
		}
		if($DryMassUnit   == "") {$DryMassUnit   = "?";}
		if($ArmUnit       == "") {$ArmUnit       = "?";}
		if($DryMomentUnit == "") {$DryMomentUnit = "?";}
	//
		//// display
		$body .= "<div class=\"GC\">\n";
		$body .= "<table class=\"noborder\">\n";
			//// head
			$body .= "<tr>\n";
			$body .= "<td class=\"phantom\"></td>\n";
			$body .= "<th>Mass</th>\n";
			$body .= "<th>Arm</th>\n";
			$body .= "<th>Moment</th>\n";
			$body .= "</tr>\n";
			$body .= "<tr>\n";
			$body .= "<td class=\"phantom\"></td>\n";
			$body .= "<th>[$DryMassUnit]</th>\n";
			$body .= "<th>[$ArmUnit]</th>\n";
			$body .= "<th>[$DryMomentUnit]</th>\n";
			$body .= "</tr>\n";
		//
			//// empty
			$body .= "<tr>\n";
			$body .= "<td>Empty</td>\n";
			$body .= "<td class=\"mass\">";
			$body .= ($DryMass > 0) ? $DryMass : "";
			$body .= "</td>\n";
			$body .= "<td class=\"unavailable\"></td>\n";
			$body .= "<td class=\"moment\">";
			$body .= ($DryMoment > 0) ? $DryMoment : "";
			$body .= "</td>\n";
			$body .= "</tr>\n";
		//
			//// front
			$body .= "<tr>\n";
			$body .= "<td>Front</td>\n";
			$body .= "<td class=\"mass\">";
			$body .= ($FrontMass > 0) ? $FrontMass : "";
			$body .= "</td>\n";
			$body .= "<td class=\"arm\">";
			$body .= ($FrontArm > 0) ? $FrontArm : "";
			$body .= "</td>\n";
			$body .= "<td class=\"moment\">";
			$body .= ($FrontMoment > 0) ? $FrontMoment : "";
			$body .= "</td>\n";
			$body .= "</tr>\n";
		//
		if($RearArm > 0 || $FrontArm == 0) {
			//// rear
			$body .= "<tr>\n";
			$body .= "<td>Rear</td>\n";
			$body .= "<td class=\"mass\">";
			$body .= ($RearMass > 0) ? $RearMass : "";
			$body .= "</td>\n";
			$body .= "<td class=\"arm\">";
			$body .= ($RearArm > 0) ? $RearArm : "";
			$body .= "</td>\n";
			$body .= "<td class=\"moment\">";
			$body .= ($RearMoment > 0) ? $RearMoment : "";
			$body .= "</td>\n";
			$body .= "</tr>\n";
		}
		//
			//// luggage
			$body .= "<tr>\n";
			$body .= "<td>Luggage</td>\n";
			$body .= "<td class=\"mass\">";
			$body .= ($LuggageMass > 0) ? $LuggageMass : "";
			$body .= "</td>\n";
			$body .= "<td class=\"arm\">";
			$body .= ($LuggageArm > 0) ? $LuggageArm : "";
			$body .= "</td>\n";
			$body .= "<td class=\"moment\">";
			$body .= ($LuggageMoment > 0) ? $LuggageMoment : "";
			$body .= "</td>\n";
			$body .= "</tr>\n";
		//
			//// unusable fuel
			$body .= "<tr>\n";
			$body .= "<td>Unusable fuel</td>\n";
			$body .= "<td class=\"mass\">";
			$body .= ($UnusableFuelMass > 0) ? $UnusableFuelMass : "";
			$body .= "</td>\n";
			$body .= "<td class=\"arm\">";
			$body .= ($FuelArm > 0) ? $FuelArm : "";
			$body .= "</td>\n";
			$body .= "<td class=\"moment\">";
			$body .= ($UnusableFuelMoment > 0) ? $UnusableFuelMoment : "";
			$body .= "</td>\n";
			$body .= "</tr>\n";
		//
		$redBG = ' style="background-color: red;"';
		//
			//// 0-fuel
			$gcMinStyle = ($minGC > 0 && $minGC > $ZeroGC) ? $redBG : "";
			$gcMaxStyle = ($maxGC > 0 && $maxGC < $ZeroGC) ? $redBG : "";
			$body .= "<tr>\n";
			$body .= "<td rowspan=\"3\" class=\"GCend GCtitle\">0-fuel</td>\n";
			$body .= "<td class=\"GCend GCmin\"></td>\n";
			$body .= "<td class=\"GCend GCmin\"$gcMinStyle>min:&nbsp;";
			$body .= ($minGC == 0) ? "&nbsp;&nbsp;&nbsp;" : $minGC;
			$body .= "</td>\n";
			$body .= "<td rowspan=\"3\" class=\"moment\">";
			$body .= ($DryMass > 0 && $FrontMass > 0) ? $ZeroMoment : "";
			$body .= "</td>\n";
			$body .= "</tr>\n";
			$body .= "<tr>\n";
			$body .= "<td class=\"mass GCend GCmid\">";
			$body .= ($DryMass > 0 && $FrontMass > 0) ? $ZeroMass : "";
			$body .= "</td>\n";
			$body .= "<td class=\"arm GCend GCmid\"$gcMinStyle$gcMaxStyle>";
			$body .= ($DryMass > 0 && $FrontMass > 0) ? "&nbsp;&nbsp;$ZeroGC" : "";
			$body .= "</td>\n";
			$body .= "</tr>\n";
			$body .= "<tr>\n";
			$body .= "<td class=\"GCend GCmax\"></td>\n";
			$body .= "<td class=\"GCend GCmax\"$gcMaxStyle>max:&nbsp;";
			$body .= ($maxGC == 0) ? "&nbsp;&nbsp;&nbsp;" : $maxGC;
			$body .= "</td>\n";
			$body .= "</tr>\n";
		//
			//// fuel
			$body .= "<tr>\n";
			$body .= "<td>Fuel</td>\n";
			$body .= "<td class=\"mass\">";
			$body .= ($FuelMass > 0) ? $FuelMass : "";
			$body .= "</td>\n";
			$body .= "<td class=\"arm\">";
			$body .= ($FuelArm > 0) ? $FuelArm : "";
			$body .= "</td>\n";
			$body .= "<td class=\"moment\">";
			$body .= ($FuelMoment > 0) ? $FuelMoment : "";
			$body .= "</td>\n";
			$body .= "</tr>\n";
		//
			//// Take-off
			$gcMinStyle = ($minGC > 0 && $minGC > $ToffGC) ? $redBG : "";
			$gcMaxStyle = ($maxGC > 0 && $maxGC < $ToffGC) ? $redBG : "";
			$MTOWstyle  = ($MTOW > 0 && $ToffMass > $MTOW) ? $redBG : "";
			$body .= "<tr>\n";
			$body .= "<td rowspan=\"3\" class=\"GCend GCtitle\">Take-off</td>\n";
			$body .= "<td class=\"mass GCend GCmin\"$MTOWstyle></td>\n";
			$body .= "<td class=\"GCend GCmin\"$gcMinStyle>min:&nbsp;";
			$body .= ($minGC == 0) ? "&nbsp;&nbsp;&nbsp;" : $minGC;
			$body .= "</td>\n";
			$body .= "<td rowspan=\"3\" class=\"moment\">";
			$body .= ($DryMass > 0 && $FrontMass > 0) ? $ToffMoment : "";
			$body .= "</td>\n";
			$body .= "</tr>\n";
			$body .= "<tr>\n";
			$body .= "<td class=\"mass GCend GCmid\"$MTOWstyle>";
			$body .= ($DryMass > 0 && $FrontMass > 0) ? $ToffMass : "";
			$body .= "</td>\n";
			$body .= "<td class=\"arm GCend GCmid\"$gcMinStyle$gcMaxStyle>";
			$body .= ($DryMass > 0 && $FrontMass > 0) ? "&nbsp;&nbsp;$ToffGC" : "";
			$body .= "</td>\n";
			$body .= "</tr>\n";
			$body .= "<tr>\n";
			$body .= "<td class=\"GCend GCmax\"$MTOWstyle>max:";
			$body .= ($MTOW > 0) ? $MTOW : "";
			$body .= "</td>\n";
			$body .= "<td class=\"GCend GCmax\"$gcMaxStyle>max:&nbsp;";
			$body .= ($maxGC == 0) ? "&nbsp;&nbsp;&nbsp;" : $maxGC;
			$body .= "</td>\n";
			$body .= "</tr>\n";
		//
		$body .= "</table>\n";
		$body .= "<div>1 USG = 3.785 liters</div>\n";
		$body .= "<div>1 ImpG = 4.55 liters</div>\n";
		$body .= "<div>1 l AVGAS = 0.72 kg</div>\n";
		$body .= "<div>1 USG AVGAS = 6 lbs</div>\n";
		$body .= "</div>\n";
	//
		//// LaTeX
		$latexGC = LaTeXGC($DryMassUnit, $ArmUnit, $DryMomentUnit, $DryMass, $DryMoment, $FrontMass, $FrontArm, $FrontMoment, $RearMass, $RearArm, $RearMoment, $LuggageMass, $LuggageArm, $LuggageMoment, $minGC, $maxGC, $ZeroMass, $ZeroGC, $ZeroMoment, $FuelMass, $FuelArm, $FuelMoment, $ToffMass, $ToffGC, $ToffMoment, $MTOW, $UnusableFuelMass, $UnusableFuelMoment);
	//
	//
//
//
fwrite($latexfile, $latexcontent);
fwrite($latexfile, $latex2_1);
fwrite($latexfile, $latexfuel);
fwrite($latexfile, $latex2_2);
fwrite($latexfile, $latexGC);
fwrite($latexfile, $latexend);
//
fclose($latexfile);


$page->show($body);
unset($page);
?>
