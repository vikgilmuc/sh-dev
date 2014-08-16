<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * Hello World Component Controller
 */
class jsonController extends JControllerLegacy
{
function json(){
// Get the data
		$data = array();
		$db =& JFactory::getDbo();
		$query = "SELECT * FROM #__content 
                       WHERE catid=10 
                       ORDER BY modified DESC";
		$db->setQuery($query);
		if ( !$db->query() ) {
			$data = array('error'=>'1');
		} else {
			$data = $db->loadObjectList(); 
		}
		
		// Get the document object.
		$document =& JFactory::getDocument();
		
		// Set the MIME type for JSON output.
		$document->setMimeEncoding('application/json');
		
		// Change the suggested filetype.
		JResponse::setHeader('Cache-Control: no-cache, must-revalidate','Content-type: application/json');
		
		// Check callback-param
		if (JFactory::getApplication()->input->get('callback')) {
			echo JFactory::getApplication()->input->get('callback') . '(';
		}
		
		// Output the JSON data.
		echo json_encode($data);
		
		// Check callback-param
		if (JFactory::getApplication()->input->get('callback')) {
			echo ');';
		}
		
		
		die();


}
}