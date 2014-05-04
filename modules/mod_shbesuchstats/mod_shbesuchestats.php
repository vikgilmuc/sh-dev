<?php
/**
 * @version     $Id: mod_mythingsstats.php 20196 06/2011 $
 * @package     Joomla.Site
 * @subpackage  mod_mythingsstats
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once dirname(__FILE__).DS.'helper.php';
$stats = modShBesucheStats::getBesuche();
require JModuleHelper::getLayoutPath('mod_shbesuchestats');
