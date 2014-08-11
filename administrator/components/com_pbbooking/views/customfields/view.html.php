<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );
 

class PbbookingsViewcustomfields extends PbAdminView
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
	        
	        
			$this->customfields = JRequest::getVar('customfields');
		}
		
		if ($task == "edit" || $task == "add") {
	      	JToolBarHelper::title( JText::_( 'PBBooking Manager' ), 'generic.png' );
			JToolBarHelper::save();
			JToolBarHelper::cancel('display');
			$this->customfield=JRequest::getVar('customfield');
		}

        
	 parent::display($tpl);
    }
}