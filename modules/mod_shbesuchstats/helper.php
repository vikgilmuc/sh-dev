<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_shbesuchstats
 * @copyright   Copyright (C) 2014. Victor gil
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class modShBesuchStats
{
    public function getBesuche()
    {
        $db     = JFactory::getDbo();
        $query  = $db->getQuery(true);

        $query->clear();
        $query->select('COUNT(id)');
        $query->from('#__shbesuche');
        $db->setQuery($query);
        $things = $db->loadResult();

        return $things;
    }
}
