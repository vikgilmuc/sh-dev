<?php
/**
 * @package    PurpleBeanie.PBBooking
 * @link http://www.purplebeanie.com
 * @license    GNU/GPL
 */
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');
 
class PbbookingsControllertreatment extends JControllerLegacy
{
	
	function __construct()
	{
	    parent::__construct();

	    // Register Extra tasks
	    $this->registerTask( 'add'  ,     'edit' );
	}
	
    /**
     * Method to display the view
     *
     * @access    public
     */
    function display($cacheable=false,$options=array())
    {	
		//$sql = "select #__pbbooking_treatments.*, #__pbbooking_cals.name as linked_calendar from #__pbbooking_treatments, #__pbbooking_cals where #__pbbooking_treatments.calendar = #__pbbooking_cals.id order by id asc";
		$sql = 'select * from #__pbbooking_treatments order by id ASC';
		$db = JFactory::getDBO();
		$db->setQuery( $sql );
		$treatments = $db->loadAssocList();
		
		JRequest::setVar('treatments',$treatments);
		JRequest::setVar( 'view', 'treatment' );
		JRequest::setVar( 'layout', 'default'  );
			
		parent::display();
    }

	function edit()
	{
		$db =JFactory::getDBO();

	    JRequest::setVar('hidemainmenu', 1);
		
		//load up the view
		$view=$this->getView('treatment','html');
		$view->setLayout('form');
		
		//push in the relavant vars
		$db->setQuery('select * from #__pbbooking_cals');
		$view->calendars = $db->loadObjectList();
		
		//do I have a treatment to load?
		$cid = JRequest::getVar('cid');
		if ($cid) {
			$sql = "select * from #__pbbooking_treatments where id = ".$cid[0];
			$db->setQuery( $sql );
			$view->treatment = $db->loadAssoc();
		} else {
			$treatment = Array("name"=>"Treatment Name","duration"=>60,"price"=>75,"id"=>0,'calendar'=>'','require_payment'=>0);
			$view->treatment = $treatment;
		}

	    $view->display();
	}
	
	function save() 
	{
		$db =JFactory::getDBO();
		$name = JRequest::getVar('name');
		$duration = JRequest::getVar('duration');
		$price = JRequest::getVar('price');
		$id = JRequest::getVar('id');
		$calendar = JRequest::getVar('linked_calendar');
		$require_payment = JRequest::getVar('require_payment',0);
		
		if ($id !=0) {
			$sql = sprintf("update #__pbbooking_treatments set name = '%s',duration=%s,price=%s, calendar='%s',
							require_payment = '%s' where id = %s",
				$name,$duration,$price,$db->escape(implode(',',$calendar)),$db->escape($require_payment),$id);
		} else {
			$sql = sprintf("insert into #__pbbooking_treatments (name,duration,price,calendar,require_payment) values ('%s',%s,%s,'%s',%s)",
				$name,$duration,$price,$db->escape(implode(',',$calendar)),$db->escape($require_payment));
		}
		$db->setQuery( $sql );
		$db->query();
		$this->setRedirect( 'index.php?option=com_pbbooking&controller=treatment', null );
		
	}
	
	function remove()
	{
		$ids = JRequest::getVar('cid');
		foreach ($ids as $id) 
		{
			$sql = sprintf("delete from #__pbbooking_treatments where id = %s",$id);
			//echo $sql;
			$db =JFactory::getDBO();
			$db->setQuery($sql);
			$db->query();
		}
		$this->setRedirect( 'index.php?option=com_pbbooking&controller=treatment', null );
	}
	

}