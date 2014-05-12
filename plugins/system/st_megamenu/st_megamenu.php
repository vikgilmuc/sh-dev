<?php
/**
 * @version		1.0.0
 * @copyright 	Beautiful-Templates.com
 * @author		Neo
 * @link		http://www.beautiful-templates.com
 * @license		License GNU General Public License version 2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgSystemST_Megamenu extends JPlugin
{
	function onContentPrepareForm($form, $data)
	{
		if ($form->getName()=='com_menus.item')
		{
			JHtml::_('behavior.framework', true);
			$doc = JFactory::getDocument();
			JForm::addFormPath(JPATH_PLUGINS.DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'st_megamenu'.DIRECTORY_SEPARATOR.'elements');
			$form->loadFile('params', false);
		}
	}
	
	public function onAfterInitialise() {
		JHtml::_('jquery.framework');
	 	$app = JFactory::getApplication();
	  	$doc = JFactory::getDocument();
		
		if ($app->isSite()) {
			$doc->addStyleSheet(JUri::base(). '/plugins/system/st_megamenu/assets/css/megamenu.css');
			$doc->addScript(JUri::base(). '/plugins/system/st_megamenu/assets/js/megamenu.js');
		}
	}
}
