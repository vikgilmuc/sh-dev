<?php
// Makes sure JSON can be parsed
if (!extension_loaded('json')) {
    throw new Exception('OAuth requires the JSON PHP extension.');
}
/**
 * An RWA_OAuth extension for Facebook's OAuth 2.0 implementation
 *
 * @author Jason Lengstorf
 * @copyright 2012 Jason Lengstorf
 */
class RWA_Facebook extends RWA_OAuth {
    public $service_auth_endpoint = 'https://www.facebook.com/dialog/oauth', $service_token_endpoint = 'https://graph.facebook.com/oauth/access_token';
    /**
     * Sets defaults, calls the parent constructor to check login/logout
     *
     * @param array $config Configuration parameters for Facebook OAuth
     * @return void
     */
    public function __construct($config = array()) {
        /*
         * In order to use OAuth, the client_id, client_secret, and
         * client_auth_endpoint must be set, so execution fails here if
         * they aren't provided in the config array
         */
        if (!isset($config['client_id']) || !isset($config['client_secret']) || !isset($config['client_auth_endpoint'])) {
            throw new Exception('Required config data was not set.');
        }
        $this -> client_id = $config['client_id'];
        $this -> client_secret = $config['client_secret'];
        $this -> client_auth_endpoint = $config['client_auth_endpoint'];
        /*
         * Adding scope is optional, so if it's in the config, this sets
         * the class property for authorization requests
         */
        if (isset($config['scope'])) {
            $this -> scope = $config['scope'];
        }
        // Calls the OAuth constructor to check login/logout
        parent::__construct();
    }

    /**
     * Loads the user's data from the Facebook Graph API
     *
     * @return void
     */
    protected function load_user_data() {
        $graph_uri = 'https://graph.facebook.com/me?' . 'access_token=' . $this -> access_token;
        
        $response = $this -> request_uri($graph_uri);
        
        // Decode the response and store the values in the object
        $user = json_decode($response);
        echo $user;
        $this -> id = $user -> id;
        $this -> name = $user -> name;
        $this -> username = $user -> username;
        $this -> email = $user -> email;
    }

    /**
     * Generates HTML markup to display the user's Facebook profile image
     *
     * @return void
     */
    protected function load_user_profile_image() {
        $image_path = 'https://graph.facebook.com/' . $this -> id . '/picture';
        $this -> profile_image = '<img src="' . $image_path . '" ' . 'alt="' . $this -> name . '" />';
    }

}
