<?php

defined('_JEXEC') or die;


jimport('joomla.application.component.view');


class ShBesucheViewShBesuche extends ShFrontView
{
  
  
  protected $items;
  
  function display($tpl = null)
  {

    
    $this->items = $this->get('Items');

  
   
    parent::display($tpl);
  }
}
