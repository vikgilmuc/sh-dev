<?php
// Set error reporting to keep the code clean
error_reporting(E_ALL^E_STRICT);
ini_set('display_errors', 1);
// Loads the Facebook class
require_once 'includes/class.rwa_oauth.inc.php';
require_once 'includes/class.rwa_facebook.inc.php';
// Loads the $fb_config array
require_once 'includes/fb_config.inc.php';
$facebook = new RWA_Facebook($fb_config);
// If the user is logged in, send them to the home page
if ($facebook->is_logged_in()) {

    //header("Location: ./");
	echo "loggedin";

 //   header("Location: ./");

exit;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf8" />
<title>Please Log In</title>
</head>
<body>
<h2>Please Log In</h2>
<p>
Before you can use this sweet app, you have to log in.
</p>
<p>
<a href="<?php echo $facebook->get_login_uri(); ?>">
Log In with Facebook
</a>
</p>
</body>
</html>