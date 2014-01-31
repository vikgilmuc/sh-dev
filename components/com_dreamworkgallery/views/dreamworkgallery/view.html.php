<?php
/**
* @Copyright Copyright (C) 2011- xml/swf
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
 
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
class GalleryViewDreamworkgallery extends JViewSST
{
    function display($tpl = null)
    {
        parent::display($tpl);
    }
}