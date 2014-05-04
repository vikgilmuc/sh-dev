<?php
/**

 *
 * Einstiegspunkt im Frontend
 * @package     Frontend
 * @subpackage  com_shbesuch
 * @author      victor ggil
 * @license     GNU/GPL
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');

/* controller shbesuche instance */
$controller = JControllerLegacy::getInstance('shbesuche');

/* object $input, eingabe in application */
$input = JFactory::getApplication()->input;

/* execute task. */
$controller->execute($input->get('task'));





