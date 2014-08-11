<?php

/**
* @package		PurpleBeanie.PBBooking
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );

//define the timezone as a constant since I'm going to always use it!
$config = JFactory::getConfig();
$version = new JVersion();
$input = JFactory::getApplication()->input;


//set some defines cause we're goign to use things a lot!!
define('PBBOOKING_TIMEZONE',$config->get('offset')); 
if ($version->RELEASE != '2.5') {
	if (!defined('DS'))
		define('DS',DIRECTORY_SEPARATOR);
	define('JOOMLA_VERSION','3.0');
}
else
	define('JOOMLA_VERSION',$version->RELEASE);


jimport('cms.html.html');
require_once(JPATH_SITE.DS.'components'.DS.'com_pbbooking'.DS.'pbframe'.DS.'pbadminview.php');
require_once( JPATH_SITE.DS.'components'.DS.'com_pbbooking'.DS.'models'.DS.'event.php' );
require_once( JPATH_SITE.DS.'components'.DS.'com_pbbooking'.DS.'models'.DS.'calendar.php' );
require_once(JPATH_SITE.DS.'components'.DS.'com_pbbooking'.DS.'helpers'.DS.'pbdebug.php');
 
// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}

if (!$controller) {
	$controller = '';
}

if (JOOMLA_VERSION == '2.5') {

	//setup submenu items
	JSubMenuHelper::addEntry(JText::_('COM_PBBOOKNG_TRADING_HOURS_HOLIDAYS'), 'index.php?option=com_pbbooking&controller=manage&task=blockdays');
	JSubMenuHelper::addEntry(JText::_('COM_PBBOOKING_SUB_MENU_MANAGE_CALENDARS'), 'index.php?option=com_pbbooking&controller=calendar');
	JSubMenuHelper::addEntry(JText::_('COM_PBBOOKING_SUB_MENU_SERVICES'), 'index.php?option=com_pbbooking&controller=treatment');
	JSubMenuHelper::addEntry(JText::_('COM_PBBOOKING_SUB_MENU_MANAGE_CONFIGURATION'), 'index.php?option=com_pbbooking&controller=configuration&task=edit');
	JSubMenuHelper::addEntry(JText::_('COM_PBBOOKING_SUB_MENU_MANAGE_CUSTOM_FIELDS'), 'index.php?option=com_pbbooking&controller=customfields&task=display');
	JSubMenuHelper::addEntry(JText::_('COM_PBBOOKING_SUB_MENU_MANAGE_DIARIES'), 'index.php?option=com_pbbooking&controller=manage');

} else {
	//setup submenu items
	JSubMenuHelper::addEntry(JText::_('COM_PBBOOKING_SUBMENU_DASHBOARD'), 'index.php?option=com_pbbooking',($input->get('controller',null,'string') == null));
	JSubMenuHelper::addEntry(JText::_('COM_PBBOOKNG_TRADING_HOURS_HOLIDAYS'), 'index.php?option=com_pbbooking&controller=manage&task=blockdays',($input->get('task',null,'string') == 'blockdays'));
	JSubMenuHelper::addEntry(JText::_('COM_PBBOOKING_SUB_MENU_MANAGE_CALENDARS'), 'index.php?option=com_pbbooking&controller=calendar',($input->get('controller',null,'string') == 'calendar'));
	JSubMenuHelper::addEntry(JText::_('COM_PBBOOKING_SUB_MENU_SERVICES'), 'index.php?option=com_pbbooking&controller=treatment',($input->get('controller',null,'string') == 'treatment'));
	JSubMenuHelper::addEntry(JText::_('COM_PBBOOKING_SUB_MENU_MANAGE_CONFIGURATION'), 'index.php?option=com_pbbooking&controller=configuration&task=edit',($input->get('controller',null,'string') == 'configuration'));
	JSubMenuHelper::addEntry(JText::_('COM_PBBOOKING_SUB_MENU_MANAGE_CUSTOM_FIELDS'), 'index.php?option=com_pbbooking&controller=customfields&task=display',($input->get('controller',null,'string') == 'customfields'));
	JSubMenuHelper::addEntry(JText::_('COM_PBBOOKING_SUB_MENU_MANAGE_DIARIES'), 'index.php?option=com_pbbooking&controller=manage',($input->get('controller',null,'string') == 'manage' && ($input->get('task',null,'string') != 'blockdays') ));
}


// Require the base controller
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'controller.php' );
 
// Create the controller
$classname = 'PbbookingsController'.$controller;
$controller = new $classname( );
 
// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );
 
// Redirect if set by the controller
$controller->redirect();