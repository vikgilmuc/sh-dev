<?php
/**
* @Copyright Copyright (C) 2010- xml/swf
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

/**
 * Templates View for Gallery XML Component
 */

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );

if (!class_exists('JViewSST')) {
 if (!class_exists('JView')) {
  if(function_exists('class_alias')) {
   class_alias('JViewLegacy', 'JViewSST');
  } else {
   class JViewSST extends JViewLegacy
   {
    function __construct()
    {
     parent::__construct();
    }
   }
  }
  } else {
  if(function_exists('class_alias')) {
   class_alias('JView', 'JViewSST');
  } else {
   class JViewSST extends JView
   {
    function __construct()
    {
     parent::__construct();
    }
   }
  }
 }
}
class GalleryViewTemplates extends JViewSST
{
	function __construct()
	{
		parent::__construct();
	}
	function display($tpl = null)
	{
		$cmpt_name = 'com_dreamworkgallery';
		JToolBarHelper::title( JText::_( 'Responsive 2 manage' ), 'generic.png' );
		
		JToolBarHelper::preferences($cmpt_name, '380');
		
		JSubMenuHelper::addEntry(JText::_('Pictures'), 'index.php?option='.$cmpt_name);
		JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option='.$cmpt_name.'&controller=categories');
		JSubMenuHelper::addEntry(JText::_('Multiple Upload'), 'index.php?option='.$cmpt_name.'&controller=multiup');
		JSubMenuHelper::addEntry(JText::_('Responsive 2'), 'index.php?option='.$cmpt_name.'&controller=templates', true);
		parent::display($tpl);
	}

}
