<?php
/**
* @Copyright Copyright (C) 2011- xml/swf
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);//J3 compatibility
}
/**Mobile Gallery**/
$server_path = JPATH_SITE . DS;
	// for joomla
	if(strstr($_SERVER['HTTP_USER_AGENT'],"iPad") || strstr($_SERVER['HTTP_USER_AGENT'],"iPhone") || stristr($_SERVER['HTTP_USER_AGENT'], 'Android')) {
		define('PLUGINNAME', 'mod_xmlswf_gallery_dreamwork');
		require_once($server_path.'modules/'.PLUGINNAME.'/mobile/mobile-joomla.php');
	}
/******************/

// Require the base controller
 
require_once( JPATH_COMPONENT.DS.'controller.php' );
 
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
$classname    = 'GalleryController'.$controller;
$controller   = new $classname( );
 
// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );
 
// Redirect if set by the controller
$controller->redirect();
