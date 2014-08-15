<?php

/**
* @package		PurpleBeanie.PBBooking
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );
 
class PbbookingsViewTreatment extends PbAdminView
{

	function display($tpl = null)
    {
		$task = JRequest::getVar('task');
		if (!$task || $task=="display") {
        	JToolBarHelper::title( JText::_( 'PBBooking Manager' ), 'generic.png' );
        	JToolBarHelper::title( JText::_( 'PBBooking Manager' ), 'generic.png' );
        	JToolBarHelper::addNew();
			JToolBarHelper::editList();
			JToolBarHelper::deleteList();
	        
	        
			$this->treatments= JRequest::getVar('treatments');
		}
		
		if ($task == "edit" || $task == "add") {
	      	JToolBarHelper::title( JText::_( 'PBBooking Manager' ), 'generic.png' );
			JToolBarHelper::save();
			JToolBarHelper::cancel('display');
		}
        
	 parent::display($tpl);
    }
}