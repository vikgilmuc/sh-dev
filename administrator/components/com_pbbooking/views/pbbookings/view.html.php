<?php
/**
* @package		PurpleBeanie.PBBooking
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );
 

class PbbookingsViewPbbookings extends PbAdminView
{
   
    function display($tpl = null)
    {
        JToolBarHelper::title( JText::_( 'PBBooking Manager' ), 'generic.png' );
 
  
        parent::display($tpl);
    }
}