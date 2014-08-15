<?php

/**
* @package		PurpleBeanie.PBBooking
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );
 

class PbbookingsViewmanage extends PbAdminView
{

    function display($tpl = null)
    {
		JToolBarHelper::title( 'PBBooking Manager' , 'generic.png' );
		
		$db=JFactory::getDBO();
		$config =JFactory::getConfig();
    	date_default_timezone_set($config->get('offset'));	

		
		$date = JRequest::getVar('date');
 		if (!$date) {
			$date = date_create("now",new DateTimeZone(PBBOOKING_TIMEZONE));
			$this->date = $date;
			parent::display($tpl);
		}
		else if ($date) {
			$this->date = date_create($date,new DateTimeZone(PBBOOKING_TIMEZONE));
	 		parent::display($tpl);
		}
    }
}