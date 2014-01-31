<?php
// No direct access
/**
* @Copyright Copyright (C) 2010- xml/swf
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

if (!function_exists('chIfColor')) {
function chIfColor($color_str, $only_digit = true)
{
	if ($only_digit) {
		if (strlen($color_str) == 6 && strspn($color_str, "0123456789abcdefABCDEF") == 6) {
			return true;
		} else {
			return false;
		}
	} else {
		if (strlen($color_str) == 7 && strspn($color_str, "#0123456789abcdefABCDEF") == 7) {
			return true;
		} else {
			return false;
		}
	}
}
}

jimport('joomla.application.component.controller');

if (!class_exists('JControllerSST')) {
 if (!class_exists('JController')) {
  if(function_exists('class_alias')) {
   class_alias('JControllerLegacy', 'JControllerSST');
  } else {
   class JControllerSST extends JControllerLegacy
   {
    function __construct()
    {
     parent::__construct();
    }
   }
  }
  } else {
  if(function_exists('class_alias')) {
   class_alias('JController', 'JControllerSST');
  } else {
   class JControllerSST extends JController
   {
    function __construct()
    {
     parent::__construct();
    }
   }
  }
 }
}
class GalleryControllerTemplates extends JControllerSST
{
	var $_cmpt_name;
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
	 
		$this->_cmpt_name = 'com_dreamworkgallery';
		// Register Extra tasks
		//$this->registerTask('add', 'edit');
	}
		
    function display()
    {
		JRequest::setVar( 'view', 'templates' );
		JRequest::setVar( 'layout', 'default' );
        parent::display();
    }
	function prepthrsp()
	{
		if(version_compare(JVERSION, '3.0.0', 'ge')) {
			$params_comg = JComponentHelper::getParams('com_dreamworkgallery', true);
		} else {
			$componentstr = &JComponentHelper::getComponent('com_dreamworkgallery');
			$params_comg = new JParameter($componentstr->params);
		}
		$litmid = (int)JRequest::getInt('litmid', 0);
		$model = $this->getModel('templates');
		$ret_arr = $model->PrepareThResp($litmid);
		if ($ret_arr['litmid'] == 0 && !$ret_arr['err']) {
			$old_rsplastwh = $params_comg->get('rsplastwh', "");
			$new_rsplastwh = $params_comg->get('thumbnailBaseWidth', "180").'x'.$params_comg->get('thumbnailBaseHeight', "180");
			if ($old_rsplastwh != $new_rsplastwh) {
				$this->setPar('rsplastwh', $new_rsplastwh, $old_rsplastwh);
			}
			echo JText::_('The thumbs were successfully prepared for Responsive template.');
		} elseif ($ret_arr['litmid'] > 0) {
			echo $ret_arr['litmid'];
		} else {
			$err_msg = JText::_("<font color='ff9999'>Errors on preparing thumbs. Please check for at least one image file(files for sale or thumb) on items with ID-s:</font>");
			if (is_array($ret_arr['errid']) && count($ret_arr['errid']) > 0) {
				$err_msg .= implode(',', $ret_arr['errid']);
			}
			echo $err_msg;
		}
		$mainframe = JFactory::getApplication();
		$mainframe->close();
	}
	
	 function save()
    {
if(version_compare(JVERSION, '3.0.0', 'ge')) {
	$params_comg = JComponentHelper::getParams('com_dreamworkgallery', true);
} else {
	$componentstr = &JComponentHelper::getComponent('com_dreamworkgallery');
	$params_comg = new JParameter($componentstr->params);
}

$thumbnailBaseWidth = $params_comg->get('thumbnailBaseWidth', "180");
$thumbnailBaseHeight = $params_comg->get('thumbnailBaseHeight', "180");
$resize_type = $params_comg->get('resize_type', "2");

if ($resize_type != $_POST['resize_type']) {
 $this->setPar('resize_type', $_POST['resize_type'], $resize_type);
}

if ($thumbnailBaseWidth != $_POST['thumbnailBaseWidth']) {
 $this->setPar('thumbnailBaseWidth', $_POST['thumbnailBaseWidth'], $thumbnailBaseWidth);
}

if ($thumbnailBaseHeight != $_POST['thumbnailBaseHeight']) {
 $this->setPar('thumbnailBaseHeight', $_POST['thumbnailBaseHeight'], $thumbnailBaseHeight);
}

		JRequest::setVar( 'view', 'templates' );
		JRequest::setVar( 'layout', 'default' );
        parent::display();
    }
	
	function setPar($par, $pval, $old_val)
	{
			if (version_compare(JVERSION, '1.6', 'ge')) {
				$replace_q = "UPDATE #__extensions SET `params` = REPLACE(`params`, '\"".$par."\":\"".$old_val."\"', '\"".$par."\":\"".$pval."\"') WHERE `element` = 'com_dreamworkgallery'";
			} else {
				$replace_q = "UPDATE #__components SET `params` = REPLACE(`params`, '".$par."=".$old_val."', '".$par."=".$pval."') WHERE `link` = 'option=com_dreamworkgallery'";
			}
			$database = & JFactory::getDBO();
			$database->setQuery($replace_q);
			$database->query();
			if ($database->getAffectedRows() != 1) {
				if (version_compare(JVERSION, '1.6', 'ge')) {
					$replace_q = "UPDATE #__extensions SET `params` = REPLACE(`params`, '}', ',\"".$par."\":\"".$pval."\"}') WHERE `element` = 'com_dreamworkgallery'";
				} else {
					$replace_q = "UPDATE #__components SET `params` = CONCAT(`params`, '".$par."=".$pval."\\n') WHERE `link` = 'option=com_dreamworkgallery'";
				}
				$database = & JFactory::getDBO();
				$database->setQuery($replace_q);
				$database->query();
			}
	}
 
}
