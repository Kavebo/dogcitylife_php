<?php

require 'instagram_api/Instagram.php';
require_once "admin/db.php";
require_once "admin/functions.php";

use MetzWeb\Instagram\Instagram;

$instagram = new Instagram(array(
    'apiKey' => '635f64f6e1af433e80507afaf2a90cb0',
    'apiSecret' => '16a94b38e79a4d9a90f0584670f2bda7',
    'apiCallback' => 'https://dogcitylife.cz/instagram_success.php' // must point to success.php
));

//GET CODE IF WE HAVE CODE
$db = new Db();
$check = $db->fetch("SELECT value FROM options WHERE name LIKE 'instagram'");
if($check){
	$check = $check['value'];
}

if($check && !empty($check)){
	$result = $instagram->setAccessToken($check);
	$result = $instagram->getUserMedia();
	if(isset($result->meta->error_message)){
		$loginUrl = $instagram->getLoginUrl();
		echo '<a href="' . $loginUrl . '">Přihlásit se do instagramu</a>';
		die();
	}
	$custom_data = array();
	foreach($result->data as $value){
		$custom_data[] = array("img" => $value->images->standard_resolution->url, "text" => $value->caption->text, "link" => $value->link);
	}
	;
	$custom_data = array_slice($custom_data, 0, 5);
	file_put_contents("instagram.json", json_encode($custom_data));
}else{
	$loginUrl = $instagram->getLoginUrl();
	echo '<a href="' . $loginUrl . '">Přihlásit se do instagramu</a>';
}