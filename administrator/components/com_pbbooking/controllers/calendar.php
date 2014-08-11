<?php
/**
 * @package    PurpleBeanie.PBBooking
 * @link http://www.purplebeanie.com
 * @license    GNU/GPL
 */
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');
 

class PbbookingsControllercalendar extends JControllerLegacy
{
	
	function __construct()
	{
	    parent::__construct();

	    // Register Extra tasks
	    $this->registerTask( 'add'  ,     'edit' );
	
		//check calendars
		$db=JFactory::getDBO();
		$db->setQuery("select * from #__pbbooking_config limit 1");
		$config = $db->loadObject();

		if ($config->consolidated_view == 1) {
			//check of we have an out cal
			$db->setQuery("select * from #__pbbooking_cals where out_cal = 1");
			$cals = $db->loadObjectList();
			if (!isset($cals) || count($cals) == 0) {
				$app = JFactory::getApplication();
				$app->enqueueMessage('You are running consolidated views but don\'t have an out_cal defined.  Please either convert to individual views or define an out_cal.');
			}
		}
	}
	
    /**
     * Method to display the view
     *
     * @access    public
     */
    function display($cacheable=false,$options=array())
    {	

		$sql = "select * from #__pbbooking_cals";
		$db =JFactory::getDBO();
		$db->setQuery( $sql );
		$cals = $db->loadAssocList();
		JRequest::setVar('cals',$cals);
		JRequest::setVar( 'view', 'calendar' );
		JRequest::setVar( 'layout', 'default'  );
        parent::display();
    }

	function edit()
	{
		$db =JFactory::getDBO();
		
	    JRequest::setVar( 'view', 'calendar' );
	    JRequest::setVar( 'layout', 'form'  );
	    JRequest::setVar('hidemainmenu', 1);
		
		//load up view
		$view = $this->getView('calendar','html');
		$view->setLayout('form');
		
		$config=$db->loadObject();
		$view->trading_hours = json_decode($config->trading_hours,true);
		
		$cid = JRequest::getVar('cid');
		if ($cid) {
			$sql = "select * from #__pbbooking_cals where id = ".$cid[0];
			$db->setQuery( $sql );
			$cals = $db->loadAssocList();
			$view->calendar = $cals[0];
			$view->trading_hours = ($cals[0]['hours'] > '') ? json_decode($cals[0]['hours'],true) : json_decode($config->trading_hours,true);
		} else {
			$view->calendar = Array("in_cal"=>1,"out_cal"=>0,"id"=>0,"name"=>"Calendar Name",'email'=>'');
		}

	    $view->display();
	}
	
	function save() 
	{
		$db =JFactory::getDBO();
		$path = JRequest::getVar('path');
		$in_cal = JRequest::getVar('in_cal',0);
		$out_cal = JRequest::getVar('out_cal',0);
		$name = JRequest::getVar('name');
		$id = JRequest::getVar('id');
		$is_open_arr = JRequest::getVar('is-open');
		$email = JRequest::getVar('email');

		//retreive opening hours
		$opening_hours = array();
		$i=0;
		foreach (array('sunday','monday','tuesday','wednesday','thursday','friday','saturday') as $day) {
			if (in_array($i,$is_open_arr)) {
				$open_time = JRequest::getVar('open-time-'.$i);
				$close_time = JRequest::getVar('close-time-'.$i);
				$opening_hours[$i] = array('status'=>'open','open_time'=>$open_time,'close_time'=>$close_time);
			} else {
				$opening_hours[$i] = array('status'=>'closed');
			}
			$i++;
		}
		
		//dump to opening hours string
		$opening_hours_string = (count($opening_hours)>0) ? json_encode($opening_hours) : '';

		if ($id !=0) {
			$sql = sprintf("update #__pbbooking_cals set in_cal=%s,out_cal=%s,name='%s', hours = '%s', email = '%s' where id = %s",
				$in_cal,$out_cal,$db->escape($name),$db->escape($opening_hours_string),$db->escape($email),$id);
		} else {
			$sql = sprintf("insert into #__pbbooking_cals (in_cal,out_cal,name,hours,email) values (%s,%s,'%s','%s','%s')",
			$in_cal,$out_cal,$db->escape($name),$db->escape($opening_hours_string),$db->escape($email));
		}
					
		$db->setQuery( $sql );
		$db->query();
		$this->setRedirect( 'index.php?option=com_pbbooking&controller=calendar', null );
		
	}
	
	function remove()
	{
		$ids = JRequest::getVar('cid');
		foreach ($ids as $id) 
		{
			$sql = sprintf("delete from #__pbbooking_cals where id = %s",$id);
			//echo $sql;
			$db =JFactory::getDBO();
			$db->setQuery($sql);
			$db->query();
		}
		$this->setRedirect( 'index.php?option=com_pbbooking&controller=calendar', null );
	}
	

}