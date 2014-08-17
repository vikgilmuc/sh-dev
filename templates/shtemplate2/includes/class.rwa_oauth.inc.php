<?php
// Makes sure cURL is loaded
if (!extension_loaded('curl')) {
throw new Exception('OAuth requires the cURL PHP extension.');
}
// Makes sure the session is started
if (!session_id()) {
session_start();
}
/**
* An abstract class for handling the basics of OAuth 2.0 authorization
*
* @author Jason Lengstorf
* @copyright 2012 Jason Lengstorf
*/
abstract class RWA_OAuth
{
                
   /**
* The service's auth endpoint
* @var string
*/
public $service_auth_endpoint,         
 /**
* The service's token endpoint
* @var string
*/
$service_token_endpoint,
/**
* The scope, or permissions, required for the app to work properly
* @var string
*/
$scope,
/**
* The service-generated client ID
* @var string
*/
$client_id,
/**
* The service-generated client secret
* @var string
*/
$client_secret,
/**
* The app's authorization endpoint
* @var string
*/
$client_auth_endpoint,
/**
* The user's account ID as loaded from the service
* @var string
*/
$id,
/**
* The user's username as loaded from the service
* @var string
*/
$username,
/**
* The user's name as loaded from the service
* @var string
*/
$name,
/**
* The user's email as loaded from the service
* @var string       
 */
$email,
/**
* Generated HTML markup to display the user's profile image
* @var string
*/
$profile_image;
/**
* The current logged in state
* @var bool
*/
protected $logged_in = FALSE,
/**
* The user access token or FALSE if none is set
* @var mixed
*/
$access_token = FALSE;   


/**
* Checks for a login or logout attempt
*
* @return void
*/
public function __construct( )
{
         if (isset($_GET['logout']) && $_GET['logout']==1) {
    $this->logout();
    header('Location: ' . $this->client_auth_endpoint);
    exit;
    }
    $this->check_login();   
}
/**
* Checks a login attempt for validity
*
* @return void
*/
public function check_login( )
{
        if (isset($_GET['state']) && isset($_GET['code'])) {
            if ($_GET['state']===$_SESSION['state']) {
            $this->save_access_token();
            $this->load_user_data();
            $this->load_user_profile_image();
            } else {
            throw new Exception("States don't match.");
            }
            } elseif (!$this->logged_in && !$this->access_token) {
            if (isset($_SESSION['access_token'])) {
            $this->access_token = $_SESSION['access_token'];
            $this->logged_in = TRUE;
            $this->load_user_data();
            $this->load_user_profile_image();
        }
}
    
}
/**
* Returns the current logged in state
*
* @return bool The current logged in state
*/
public function is_logged_in( )
{
    return $this->logged_in;
}
/**
* Processes a logout attempt
*
* @return void
*/
public function logout( )
{$this->logged_in = FALSE;
$this->access_token = FALSE;
unset($_SESSION['access_token']);
session_regenerate_id();
session_destroy();
        
    
}
/**
* Returns the URI with which a authorization can be requested
*
* @return string The authorization endpoint URI with params
*/
public function get_login_uri( )
    {
        $state = uniqid(mt_rand(10000,99999), TRUE);
    $_SESSION['state'] = $state;
    return $this->service_auth_endpoint
    . '?client_id=' . $this->client_id
    . '&redirect_uri=' . urlencode($this->client_auth_endpoint)
    . '&scope=' . $this->scope
    . '&state=' . $state;
                
            
        
    }
/**
* Returns the URI with which an access token can be generated
*
* @return string The access token endpoint URI with params
*/
protected function get_access_token_uri($code=NULL )
{return $this->service_token_endpoint
. '?client_id=' . $this->client_id
. '&redirect_uri=' . urlencode($this->client_auth_endpoint)
. '&client_secret=' . $this->client_secret
. '&code=' . $code;
        
    
}
/**
* Saves the access token in the session and as a class property
*
* @return bool TRUE on success, FALSE on failure
*/
protected function save_access_token( )
{
       $token_uri = $this->get_access_token_uri($_GET['code']);
    $response = $this->request_uri($token_uri);
    // Parse the response
    $params = array();
    parse_str($response, $params);
    if (isset($params['access_token'])) {
    $_SESSION['access_token'] = $params['access_token'];
    $this->access_token = $params['access_token'];
    $this->logged_in = TRUE;
    return TRUE;
    }
    return FALSE;     
    
}
/**
* Makes a request using cURL and returns the resulting data
*
* @param string $uri The URI to be requested
* @return string The data returned by the requested URI
*/
protected function request_uri( $uri)
{$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $uri);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
return curl_exec($ch);
}
/**
* Loads the basic user data, including name and email
*
* This method will be different for each OAuth authorized service,
* so it will need to be defined in the child class for the service.
*/
abstract protected function load_user_data();
/**
* Generates markup to display the user's profile image
** This method will be different for each OAuth authorized service,
* so it will need to be defined in the child class for the service.
*/
abstract protected function load_user_profile_image();



}