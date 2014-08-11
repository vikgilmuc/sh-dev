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
 

class PbbookingsViewConfiguration extends PbAdminView
{
    
    function display($tpl = null)
    {
		$task = JRequest::getVar('task');
		if (!$task || $task=="display") {
        	JToolBarHelper::title( JText::_( 'PBBooking Manager' ), 'generic.png' );
        	JToolBarHelper::title( JText::_( 'PBBooking Manager' ), 'generic.png' );
			JToolBarHelper::editList();
			JToolBarHelper::addNew();
			JToolBarHelper::deleteList();
	        
	        
			$this->assignRef( 'calendars', JRequest::getVar('cals'));
		}
		
		if ($task == "edit" || $task == "add") {
			//don't like having this outside the controller but JRequest strips html
			$sql = "select * from #__pbbooking_config";
			$db =JFactory::getDBO();
			$db->setQuery( $sql );
			$configuration = $db->loadAssocList();

			//get the custom fields
			$db->setQuery('select * from #__pbbooking_customfields');
			$this->customfields = $db->loadObjectList();
			
	      	JToolBarHelper::title( JText::_( 'PBBooking Manager' ), 'generic.png' );
			JToolBarHelper::save();
			JToolBarHelper::cancel('cancel');

			$this->assignRef('configuration',$configuration[0]);
		}

        
	 parent::display($tpl);
    }
}