<?php
/**
 * @package    PurpleBeanie.PBBooking
 * @subpackage Components
 * @link http://www.purplebeanie.com
 * @license    GNU/GPL
*/
 
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

//define the timezone as a constant since I'm going to always use it!
$config = JFactory::getConfig();
$version = new JVersion();
$input = JFactory::getApplication()->input;


//set some defines cause we're goign to use things a lot!!
define('PBBOOKING_TIMEZONE',$config->get('offset')); 
echo(PBBOOKING_TIMEZONE);
if ($version->RELEASE != '2.5') {
	if (!defined('DS'))
		define('DS',DIRECTORY_SEPARATOR);
	define('JOOMLA_VERSION','3.0');
}
else
	define('JOOMLA_VERSION',$version->RELEASE);

//check if there's anything additional that needs to be loaded due to Joomla CMS core changes
jimport('cms.html.html');

//some requires 
require_once(JPATH_COMPONENT.DS.'pbframe'.DS.'pbfrontview.php');
require_once( JPATH_COMPONENT.DS.'controller.php' );
require_once( JPATH_COMPONENT.DS.'models'.DS.'calendar.php' );
require_once( JPATH_COMPONENT.DS.'models'.DS.'event.php' );
require_once( JPATH_COMPONENT.DS.'views'.DS.'pbbooking'.DS.'view.html.php' );
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'pbdebug.php');

 
// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}
 
// Create the controller
$controller   = new PbbookingController();


$task = JRequest::getWord('task');

if(!$task) {
	$controller->display();
}

if($task == "view") {
	$controller->display();
}

if ($task =="dayview") {
	$controller->dayview();
}

if($task=="create") {
	$controller->create();
}

if($task=="save") {
	$controller->save();
}

if($task=="validate") {
	$controller->validate();
}

if($task == "view_day") {
	$controller->view_day();
}

if($task =="subscribe") {
	$controller->subscribe();
}

if($task=='load_slots_for_day') {
	$controller->load_slots_for_day();
}
 
// Perform the Request task
//$controller->execute( JRequest::getWord( 'task' ) );
 
// Redirect if set by the controller
$controller->redirect();