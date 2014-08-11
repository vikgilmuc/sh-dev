<?php

/**
 * @package		PurpleBeanie.PBBooking
 * @version		$Id: pbbooking.php 
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.purplebeanie.com
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );
 
/**
 * Hellos View
 *
 * @package    PurpleBeanie.PBBooking
 * @subpackage Components
 */
class PbbookingsViewCalendar extends PbAdminView
{
    /**
     * Hellos view display method
     * @return void
     **/
    function display($tpl = null)
    {
		$task = JRequest::getVar('task');
		if (!$task || $task=="display") {
        	JToolBarHelper::title( JText::_( 'PBBooking Manager' ), 'generic.png' );
        	JToolBarHelper::title( JText::_( 'PBBooking Manager' ), 'generic.png' );
			JToolBarHelper::addNew();
			JToolBarHelper::editList();
			JToolBarHelper::deleteList();


			$this->calendars =  JRequest::getVar('cals');
		}
		
		if ($task == "edit" || $task == "add") {
	      	JToolBarHelper::title( JText::_( 'PBBooking Manager' ), 'generic.png' );
	      	JToolBarHelper::save();
	      	JToolBarHelper::cancel('display');
		}

        
	 parent::display($tpl);
    }
}