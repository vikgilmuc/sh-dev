<?php
/**
 * @package    PurpleBeanie.PBBooking
 * @link http://www.purplebeanie.com
 * @license    GNU/GPL
 */
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');
 

class PbbookingsControllercustomfields extends JControllerLegacy
{
	
	function __construct()
	{
	    parent::__construct();

	    // Register Extra tasks
	    $this->registerTask( 'add'  ,     'edit' );
	    
	    //make sure user knows they need at least one field marked as email
	    $db = JFactory::getDBO();
	    $db->setQuery("select * from #__pbbooking_customfields where is_email = 1");
	    $customfields = $db->loadObjectList();
	    if (count($customfields) < 1) {
	    	$app = JFactory::getApplication();
	    	$app->enqueueMessage(JText::_('COM_PBBOOKING_EMAIL_ERROR'));
	    }	    
	}
	
    /**
     * Method to display the view
     *
     * @access    public
     */
    function display($cacheable=false,$options=array())
    {			
		$sql = "select * from #__pbbooking_customfields";

		$db =JFactory::getDBO();
		$db->setQuery( $sql );
		$customfields = $db->loadAssocList();
		
		JRequest::setVar('customfields',$customfields);
		
		JRequest::setVar( 'view', 'customfields' );
		JRequest::setVar( 'layout', 'default'  );
        parent::display();
    }

	function remove()
	{
		$ids = JRequest::getVar('cid');
		foreach ($ids as $id) {
		
			$sql = sprintf('delete from #__pbbooking_customfields where id = %s',$id);
			$db = JFactory::getDBO();
			$db->setQuery($sql);
			$db->query();
		}

		$this->setRedirect('index.php?option=com_pbbooking&controller=customfields&task=display');
	}

	function edit()
	{
	    JRequest::setVar( 'view', 'customfields' );
	    JRequest::setVar( 'layout', 'form'  );
	    JRequest::setVar('hidemainmenu', 1);
		
		$cid = JRequest::getVar('cid');
		if ($cid) {
			$sql = "select * from #__pbbooking_customfields where id = ".$cid[0];
			$db =JFactory::getDBO();
			$db->setQuery( $sql );
			$customfield = $db->loadAssocList();
			JRequest::setVar('customfield',$customfield[0]);
		} else {
			$customfield = Array("fieldname"=>"Label","fieldtype"=>"text","size"=>60,"varname"=>"dbfield","id"=>0,'is_email'=>0,'is_first_name'=>0,'is_required'=>0,'is_last_name'=>0, 'values'=>'');
			JRequest::setVar('customfield',$customfield);
		}
		parent::display();
	}
	
	
	function save() 
	{
		$db = JFactory::getDbo();
		
		$fieldname = JRequest::getVar('fieldname');
		$fieldtype = JRequest::getVar('fieldtype');
		$varname = JRequest::getVar('varname');
		$size = JRequest::getVar('size');
		$id = JRequest::getVar('id');
		$is_email = JRequest::getVar('is_email',0);
		$is_first_name = JRequest::getVar('is_first_name',0);
		$is_last_name = JRequest::getVar('is_last_name',0);
		$is_required = JRequest::getVar('is_required',0);
		$values = JRequest::getVar('values');
		
		//clean varname
		foreach (array(' ','/','=','-','#','$','%','^','&','*','(',')','+','='.'{','[',']','}','|','\\','?',';','\'','"','<','>') as $bad_char) {
			$varname = str_replace($bad_char, '_', $varname);
		}
				
				
		if ($id !=0) {
			$sql = sprintf("update #__pbbooking_customfields set 
					fieldname = '%s',
					fieldtype='%s',
					varname='%s',
					size=%s, is_email = %s, is_first_name = %s, is_last_name = %s, is_required = %s, `values` = '%s' where id = %s",
					$fieldname,$fieldtype,$varname,$size,$db->escape($is_email),$db->escape($is_first_name),$db->escape($is_last_name),
					$db->escape($is_required),$db->escape($values),$id);
		} else {
			$sql = sprintf("insert into #__pbbooking_customfields 
					(fieldname,fieldtype,varname,size,is_email,is_first_name,is_last_name,is_required,`values`) values 
					('%s','%s','%s',%s,%s,%s,%s,%s,'%s')",
					$fieldname,$fieldtype,$varname,$size,$db->escape($is_email),
					$db->escape($is_first_name),$db->escape($is_last_name),$db->escape($is_required),$db->escape($values));
		}
		$db->setQuery( $sql );
		$db->query();
		//echo $db->getErrorMsg();
		$this->setRedirect( 'index.php?option=com_pbbooking&controller=customfields', null );
	}

}