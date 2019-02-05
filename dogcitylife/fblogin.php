<?php
session_start();
// added in v4.0.0
require_once 'Facebook/autoload.php';
require_once "admin/db.php";
require_once "admin/functions.php";

/*use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;
// init app with app id and secret

$db = new Db();

FacebookSession::setDefaultApplication( '325204007892526','a6160af572a88a2e9ba66ab0727c1a6f' );
// login helper with redirect_uri
    $helper = new FacebookRedirectLoginHelper( get_front_url() . 'fblogin' );
    //$helper->getLoginUrl(array('scope' => 'email'));
try {
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
  // When Facebook returns an error
} catch( Exception $ex ) {
  // When validation fails or other local issues
}
// see if we have a session
if ( isset( $session ) ) {
  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me?locale=en_US&fields=id,name,email' );
  $response = $request->execute();
  // get response
  var_dump($graphObject); die();
  $graphObject = $response->getGraphObject();
     	$fbid = $graphObject->getProperty('id');              // To Get Facebook ID
 	    $fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
	    $femail = $graphObject->getProperty('email');    // To Get Facebook email ID

      //SAVE TO DB IF NOT EXIST
      $check = fetch("SELECT * FROM users WHERE email LIKE '" . $femail . "' AND FBID LIKE'" . $fbid . "'");

      if(!$check){
        //registrate
        $register = insert("users", array("FBID" => $fbid, "email" => $femail, "login" => $fbfullname, "register_date" => date('Y-m-d H:i:s')));
        //login
        login_front_fb($femail, $fbid);
      }else{
        //login
        login_front_fb($femail, $fbid);
      }

  header("Location: " . get_front_url());
} else {
  $loginUrl = $helper->getLoginUrl(array('scope' => 'email'));
 header("Location: ".$loginUrl);
}*/



$fb = new Facebook\Facebook([
  'app_id' => '325204007892526', // Replace {app-id} with your app id
  'app_secret' => 'a6160af572a88a2e9ba66ab0727c1a6f',
  'default_graph_version' => 'v2.2',
  ]);

$helper = $fb->getRedirectLoginHelper();

try {
  $accessToken = $helper->getAccessToken("https://dogcitylife.cz/fblogin");
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (! isset($accessToken)) {
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit;
}

// Logged in
//echo '<h3>Access Token</h3>';
//var_dump($accessToken->getValue());

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);


$fb_id = $tokenMetadata->getField('user_id');

try {
    // Get the \Facebook\GraphNodes\GraphUser object for the current user.
    // If you provided a 'default_access_token', the '{access-token}' is optional.
    $response = $fb->get('/me?fields=name,email', $accessToken);
} catch(\Facebook\Exceptions\FacebookResponseException $e) {
    echo $e->getMessage();
} catch(\Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo $e->getMessage();
}

// Get user details from facebook.
$me = $response->getGraphUser();
$name = $me->getName();
$email = $me->getField('email');


//var_dump($email, $name, $fb_id);
// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId('325204007892526'); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
  // Exchanges a short-lived access token for a long-lived one
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
    exit;
  }

}

$_SESSION['fb_access_token'] = (string) $accessToken;

// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');

//SAVE TO DB IF NOT EXIST
$db = new Db();
$check = $db->fetch("SELECT * FROM users WHERE FBID LIKE'" . $fb_id . "'");
if(!$check){
  //registrate
  $register = $db->insert("users", array("FBID" => $fb_id, "email" => $email, "login" => $name, "register_date" => date('Y-m-d H:i:s')));
  //login
  login_front_fb($fb_id);
}else{
  //login
  login_front_fb($fb_id);
}

header("Location: " . get_front_url());

?>