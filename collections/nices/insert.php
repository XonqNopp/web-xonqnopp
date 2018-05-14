<?php
require("../../functions/classPage.php");
$rootPath = "../..";
$funcpath = "$rootPath/functions";
$page = new PhPage($rootPath);
$page->NotAllowed();
$page->initDB();
$page->CSS_ppJump(2);
$page->CSS_ppWing();
$page->js_Form();

//$page->initHTML();
//$page->LogLevelUp(6);

	/*** default empty values ***/
	$id = 0;
	$name = "";
	$address = "";
	$zip = "";
	$city = "";
	$canton = "";
	$country = "";
	$website = "";
	$trip = "";
	$menu = "";

$body = "";
$args = new stdClass();
$args->rootpage = "..";
$body .= $page->GoHome($args);
if(isset($_POST["name"])) {
	if(isset($_POST["erase"])) {
		$id = $_POST["id"];
		$page->DB_IdManage("DELETE FROM `" . $page->ddb->DBname . "` . `nices` WHERE `nices` . `id` = ? LIMIT 1;", $id);
		$page->HeaderLocation();
	} else {
		// DB treatement
		if(isset($_POST["id"])) {
			$id = $_POST["id"];
		}
		$name = $page->field2SQL($_POST["name"]);
		$address = $page->field2SQL($_POST["address"]);
		$zip = $page->field2SQL($_POST["zip"]);
		$city = $page->field2SQL($_POST["city"]);
		$canton = $page->field2SQL($_POST["canton"]);
		$country = $page->field2SQL($_POST["country"]);
		$website = $page->field2SQL($_POST["website"]);
		$trip = $page->field2SQL($_POST["trip"]);
		$menu = $page->txtarea2SQL($_POST["menu"]);
		$grade = $_POST["grade"];
		$query = "";
		if($id > 0) {
			$query = $page->DB_QueryPrepare("UPDATE `" . $page->ddb->DBname . "` . `nices` SET `name` = ?, `address` = ?, `zip` = ?, `city` = ?, `canton` = ?, `country` = ?, `website` = ?, `tripadvisor` = ?, `menu` = ?, `grade` = ? WHERE `nices` . `id` = ? LIMIT 1;");
			$query->bind_param("ssissssssii", $name, $address, $zip, $city, $canton, $country, $website, $trip, $menu, $grade, $id);
		} else {
			$query = $page->DB_QueryPrepare("INSERT INTO `" . $page->ddb->DBname . "` . `nices` (`id`, `name`, `address`, `zip`, `city`, `canton`, `country`, `website`, `tripadvisor`, `menu`, `grade`) VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
			$query->bind_param("ssissssssi", $name, $address, $zip, $city, $canton, $country, $website, $trip, $menu, $grade);
		}
		$page->DB_ExecuteManage($query);
		$id_back = 0;
		if($id > 0) {
			$id_back = $id;
		} else {
			$id_back = $query->insert_id;
		}
		$page->HeaderLocation("index.php#r$id_back");
	}
	exit;
} else {
	// DISPLAY
	//
	$body .= $page->FormTag();
	if(isset($_GET["id"])) {
		$id = $_GET["id"];
		// Fetch infos from DB
		$query = $page->DB_IdManage("SELECT * FROM `" . $page->ddb->DBname . "` . `nices` WHERE `nices` . `id` = ? LIMIT 1;", $id);
		$query->store_result();
		if($query->num_rows == 0) {
			$query->close();
			exit("Error bad id");
		}
		$query->bind_result($id, $name, $name_det, $address, $zip, $city, $canton, $country, $website, $trip, $menu, $grade);
		$query->fetch();
		$query->close();
		$name    = $page->SQL2field($name);
		$address = $page->SQL2field($address);
		$zip     = $page->SQL2field($zip);
		if($zip == "0") {
			$zip = "";
		}
		$city    = $page->SQL2field($city);
		$canton  = $page->SQL2field($canton);
		$country = $page->SQL2field($country);
		$website = $page->SQL2field($website);
		$trip    = $page->SQL2field($trip);
		$menu    = $page->SQL2txtarea($menu);
		// Some infos to display
		$body .= $page->SetTitle("Update infos for $name (nice place)");
		$args = new stdClass();
		$args->type = "hidden";
		$args->name = "id";
		$args->value = $id;
		$args->css = "nice_new_id";
		$body .= $page->FormField($args);
	} else {
		$body .= $page->SetTitle("Insert a new nice place");
	}
	$page->HotBooty();
	$body .= "<div class=\"main\">\n";
	// Name
	$args = new stdClass();
	$args->type = "text";
	$args->title = "Name";
	$args->name = "name";
	$args->value = $name;
	$args->css = "nice_insert_name";
	$args->size = 60;
	$args->autofocus = true;
	$body .= $page->FormField($args);
	// Address
	$args->title = "Address";
	$args->name = "address";
	$args->value = $address;
	$args->css = "nice_insert_address";
	$args->size = 80;
	$args->autofocus = false;
	$body .= $page->FormField($args);
	// Zip
	$args->title = "ZIP";
	$args->name = "zip";
	$args->value = $zip;
	$args->css = "nice_insert_zip";
	$args->size = 10;
	$body .= $page->FormField($args);
	// City
	$args->title = "City";
	$args->name = "city";
	$args->value = $city;
	$args->css = "nice_insert_city";
	$args->size = 20;
	$body .= $page->FormField($args);
	// Canton
	$args->title = "Canton";
	$args->name = "canton";
	$args->value = $canton;
	$args->css = "nice_insert_canton";
	$args->size = 10;
	$body .= $page->FormField($args);
	// Country
	$args->title = "Country";
	$args->name = "country";
	$args->value = $country;
	$args->css = "nice_insert_country";
	$args->size = 20;
	$body .= $page->FormField($args);
	// Website
	$args->title = "Website";
	$args->name = "website";
	$args->value = $website;
	$args->css = "nice_insert_website";
	$args->size = 40;
	$body .= $page->FormField($args);
	// Trip Advisor
	$args->title = "Trip Advisor";
	$args->name = "trip";
	$args->value = $trip;
	$args->css = "nice_insert_trip";
	$args->size = 40;
	$body .= $page->FormField($args);
	// Menu
	$args = new stdClass();
	$args->type = "textarea";
	$args->title = "Comment";
	$args->name = "menu";
	$args->value = $menu;
	$args->css = "nice_insert_menu";
	$args->rows = 7;
	$args->cols = 70;
	$body .= $page->FormField($args);
	// Grade (only excellent)
	$args = new stdClass();
	$args->type = "hidden";
	$args->name = "grade";
	$args->value = 2;
	$args->css = "nice_insert_grade";
	$body .= $page->FormField($args);
	// Buttons
	$body .= "<div class=\"nice_new_valbut\">\n";
	$args = new stdClass();
	$body .= $page->SubButt($id > 0, "'$name'", $args);
	$body .= "</div>\n";

	$body .= "</div>\n";
	$body .= "</form>\n";
}

/*** Printing ***/
$page->show($body);
unset($page);
?>
