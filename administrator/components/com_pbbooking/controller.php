<?php
/**
* @package      PurpleBeanie.PBBooking
* @license      GNU General Public License version 2 or later; see LICENSE.txt
* @link     http://www.purplebeanie.com
*/
 
// No direct access

defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');
 


class PbbookingsController extends JControllerLegacy
{
    /**
     * Method to display the view
     *
     * @access    public
     */
    function display($cachable=false,$options=array())
    {   
        $db = JFactory::getDbo();
        $config = $db->setQuery('select * from #__pbbooking_config')->loadObject();

        $view = $this->getView('pbbookings','html');

        //load some data for the view....
        $query = $db->getQuery(true);
        $query->select('#__pbbooking_events.*,#__pbbooking_treatments.name')->from('#__pbbooking_events')->join('inner','#__pbbooking_treatments on #__pbbooking_events.service_id = #__pbbooking_treatments.id')->order('#__pbbooking_events.dtstart DESC')->where('#__pbbooking_events.dtstart >= "'.date_create("now",new DateTimeZone(PBBOOKING_TIMEZONE))->format(DATE_ATOM).'"');
        $query->setLimit(10,0);
        $view->upcoming_events = $db->setQuery($query)->loadObjectList();

        $query = $db->getQuery(true);
        $query->select('#__pbbooking_pending.*,#__pbbooking_treatments.name')->from('#__pbbooking_pending')->join('inner','#__pbbooking_treatments on #__pbbooking_pending.service = #__pbbooking_treatments.id')->order('#__pbbooking_pending.dtstart DESC')->where('#__pbbooking_pending.verified != 1')->setLimit(10,0);
        $view->pending_events = $db->setQuery($query)->loadObjectList();

        //get teh current php version
        $version_info = explode('.',phpversion());
        if ($version_info[0] >= 5 && $version_info[1] >=3) {
            Pbdebug::log_msg('Dashboard - running PHP > 5.3','com_pbbooking');
            //define dates for calendar util calc...
            if (date_create("now",new DateTimeZone(PBBOOKING_TIMEZONE))->format('w') == $config->calendar_start_day) {
                //we're at start of week....
                $view->dtstart = date_create("now",new DateTimeZone(PBBOOKING_TIMEZONE));
                $view->dtend = date_create("next week", new DateTimeZone(PBBOOKING_TIMEZONE));
                Pbdebug::log_msg('Dashboard - dates at start of week.  Showing utilization for dtstart = '.$view->dtstart->format(DATE_ATOM).' dtend = '.$view->dtend->format(DATE_ATOM),'com_pbbooking');
            } else {
                //we're not at start of week..... let's go back to start of week....
                $view->dtstart = date_create("now",new DateTimeZone(PBBOOKING_TIMEZONE));
                if ($view->dtstart->format('w') > $config->calendar_start_day) {
                    Pbdebug::log_msg('Dashboard - current date > $config->caeldnar_start_day','com_pbbooking');
                    $view->dtstart->modify('- '.($view->dtstart->format('w') - $config->calendar_start_day).' days');
                    $view->dtend = date_create($view->dtstart->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
                    $view->dtend->modify('+7 days');
                } else {
                    Pbdebug::log_msg('Dashboard - current date < $config->caeldnar_start_day','com_pbbooking');
                    $view->dtend = date_create($view->dtstart->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
                    $view->dtend->modify('+ '.($config->calendar_start_day - $view->dtstart->format('w')).' days');
                    $view->dtstart = date_create($view->dtend->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
                    $view->dtstart->modify('-7 days');
                }
            }

        } else {
            Pbdebug::log_msg('Dashboard - old version of PHP, not showing utilization','com_pbbooking');
            $view->old_php_version = true;
        }

        //calc cal utilizations
        $view->cals = array();
        $cals = $db->setQuery('select * from #__pbbooking_cals')->loadObjectList();
        foreach ($cals as $i=>$cal) {
            $view->cals[$i] = new Calendar();
            $view->cals[$i]->loadCalendarFromDbase(array($cal->id),$view->dtstart,$view->dtend); 
            $view->cals[$i]->name = $cal->name;
        }



        //get the latest announcemenets into the view
        $view->announcements = $this->_load_announcements();

        $view->config = $config;

        $view->display();
    }

    /**
    * loads the latest announcments of the purplebeanie.com website for display in the dashboard
    * @access private
    * @since 2.4.2
    */

    private function _load_announcements()
    {
        $announce_url = "http://www.purplebeanie.com/Announcements/feed/rss.html";
        //$announce_url = "http://thisisnotvalid";

        $parser = JFactory::getFeedParser($announce_url);

        if (!$parser)
            return array();

        if (count($parser->get_items()>5))
            return array_slice($parser->get_items(),0,5);
        else 
            return $parser->get_items();
    }

}