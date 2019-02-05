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

$code = $_GET['code'];
// check whether the user has granted access
if (isset($code)) {
    // receive OAuth token object
    $data = $instagram->getOAuthToken($code);
    $db = new Db();
    $db->update("options", array("ID" => 1), array("value" => $data->access_token));
    // store user access token
    $instagram->setAccessToken($data);

    // now you have access to all authenticated user methods
    echo 'Úspěšné získání tokenu - nyní můžete použít cron';
} else {
    // check whether an error occurred
    if (isset($_GET['error'])) {
        echo 'An error occurred: ' . $_GET['error_description'];
    }
}