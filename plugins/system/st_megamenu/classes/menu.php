<?php 
require_once JPATH_ROOT.'/modules/mod_menu/helper.php';

class STMegaMenuClassMenu extends ModMenuHelper {
	
	
	public static function getList(&$params)
	{
		$app = JFactory::getApplication();
		$menu = $app->getMenu();

		// Get active menu item
		$base = self::getBase($params);
		$user = JFactory::getUser();
		$levels = $user->getAuthorisedViewLevels();
		asort($levels);
		$key = 'menu_items' . $params . implode(',', $levels) . '.' . $base->id;
		$cache = JFactory::getCache('mod_menu', '');
		if (!($items = $cache->get($key)))
		{
			$path    = $base->tree;
			$start   = (int) $params->get('startLevel');
			$end     = (int) $params->get('endLevel');
			$showAll = $params->get('showAllChildren');
			$items   = $menu->getItems('menutype', $params->get('menutype'));

			$lastitem = 0;

			if ($items)
			{
				foreach ($items as $i => $item)
				{
					if (($start && $start > $item->level)
						|| ($end && $item->level > $end)
						|| (!$showAll && $item->level > 1 && !in_array($item->parent_id, $path))
						|| ($start > 1 && !in_array($item->tree[$start - 2], $path)))
					{
						unset($items[$i]);
						continue;
					}

					$item->deeper     = false;
					$item->shallower  = false;
					$item->level_diff = 0;

					if (isset($items[$lastitem]))
					{
						$items[$lastitem]->deeper     = ($item->level > $items[$lastitem]->level);
						$items[$lastitem]->shallower  = ($item->level < $items[$lastitem]->level);
						$items[$lastitem]->level_diff = ($items[$lastitem]->level - $item->level);
					}

					$item->parent = (boolean) $menu->getItems('parent_id', (int) $item->id, true);

					$lastitem     = $i;
					$item->active = false;
					$item->flink  = $item->link;

					// Reverted back for CMS version 2.5.6
					switch ($item->type)
					{
						case 'separator':
						case 'heading':
							// No further action needed.
							continue;

						case 'url':
							if ((strpos($item->link, 'index.php?') === 0) && (strpos($item->link, 'Itemid=') === false))
							{
								// If this is an internal Joomla link, ensure the Itemid is set.
								$item->flink = $item->link . '&Itemid=' . $item->id;
							}
							break;

						case 'alias':
							// If this is an alias use the item id stored in the parameters to make the link.
							$item->flink = 'index.php?Itemid=' . $item->params->get('aliasoptions');
							break;

						default:
							$router = JSite::getRouter();
							if ($router->getMode() == JROUTER_MODE_SEF)
							{
								$item->flink = 'index.php?Itemid=' . $item->id;
							}
							else
							{
								$item->flink .= '&Itemid=' . $item->id;
							}
							break;
					}

					if (strcasecmp(substr($item->flink, 0, 4), 'http') && (strpos($item->flink, 'index.php?') !== false))
					{
						$item->flink = JRoute::_($item->flink, true, $item->params->get('secure'));
					}
					else
					{
						$item->flink = JRoute::_($item->flink);
					}

					// We prevent the double encoding because for some reason the $item is shared for menu modules and we get double encoding
					// when the cause of that is found the argument should be removed
					$item->title        = htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8', false);
					$item->anchor_css   = htmlspecialchars($item->params->get('menu-anchor_css', ''), ENT_COMPAT, 'UTF-8', false);
					$item->anchor_title = htmlspecialchars($item->params->get('menu-anchor_title', ''), ENT_COMPAT, 'UTF-8', false);
					$item->menu_image   = $item->params->get('menu_image', '') ? htmlspecialchars($item->params->get('menu_image', ''), ENT_COMPAT, 'UTF-8', false) : '';
				}

				if (isset($items[$lastitem]))
				{
					$items[$lastitem]->deeper     = (($start?$start:1) > $items[$lastitem]->level);
					$items[$lastitem]->shallower  = (($start?$start:1) < $items[$lastitem]->level);
					$items[$lastitem]->level_diff = ($items[$lastitem]->level - ($start?$start:1));
				}
			}

			$cache->store($items, $key);
		}
		$tmp = array ();
		foreach ($items as $item) 
		{
			$subs = array();
			
			foreach ($items as $sub) 
			{
				if ($item->id == $sub->parent_id) {
					$subs[] = $sub;
				}
			}
			$item->subs = $subs;
			$tmp[] = $item;
			 
		} 
		return $tmp;
	}

	public static function renderSubs ($parent, $path, $active)
	{
		if (count($parent->subs)) 
		{
			$count = $parent->params->get('subCols', 1);
			$cols = array();
			for ($i = 0 ; $i < $count; $i++) {
				$cols[$i] = '';
			}
			
			$i = 0;
			
			foreach ($parent->subs as $item) 
			{
				$class = 'item-'.$item->id;
				if ($item->id == $active)
				{
					$class .= ' current';
				}
			
				if (in_array($item->id, $path))
				{
					$class .= ' active';
				}
				elseif ($item->type == 'alias')
				{
					$aliasToId = $item->params->get('aliasoptions');
					if (count($path) > 0 && $aliasToId == $path[count($path) - 1])
					{
						$class .= ' active';
					}
					elseif (in_array($aliasToId, $path))
					{
						$class .= ' alias-parent-active';
					}
				}
			
				if ($item->type == 'separator')
				{
					$class .= ' divider';
				}
			
				if ($item->deeper)
				{
					$class .= ' deeper';
				}
			
				if ($item->parent)
				{
					$class .= ' parent';
				}
			
				if ($item->params->get('group', 0)) {
					$class .= ' group ';
				}
				
				if (!empty($class))
				{
					$class = ' class="'.trim($class) .'"';
				}
				
				$cols[$i] .= '<li'.$class.'>';
				if ($item->parent) {
					$cols[$i] .= '<span class="pull">&nbsp;</span>';
				}
				// Render the menu item.
				if ($item->params->get('subcontent') != 'modules') 
				{
					ob_start();
					switch ($item->type) :
						case 'separator':
						case 'url':
						case 'component':
						case 'heading':
							require JModuleHelper::getLayoutPath('mod_menu', 'default_'.$item->type);
							break;
				
						default:
							require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
							break;
					endswitch;
					$title = ob_get_clean();
					
					if ($parent->params->get('showMenuTitle', 1)) {
						$cols[$i] .= '<div class="title">'.$title. '</div>';	
					}
				} 
				else 
				{
					$ids = $item->params->get('mods', array());
					if (count($ids)) 
					{
						$table = JTableModule::getInstance('module');
						$modules = '<div class="st-mega-menu-modules">';
						
						foreach ($ids as $id) 
						{
							if ($table->load($id))
							{
								if (JModuleHelper::isEnabled($table->module))	
								{
									$mo = self::getModule($id);
									$modules .= '<div class="module">'.JModuleHelper::renderModule($mo, array('style' => 'avatarmodule')).'</div>';
								}
							}	
						}
						
						$modules .= '</div>';	
						$cols[$i] .= $modules;
					}
					
				}
				
				$cols[$i] .= self::renderSubs($item, $path, $active);
				$cols[$i] .= '</li>';
				
				if ($i >= $count -1) {
					$i = 0;
				} else {
					$i++;
				}
				
			}
			
			$style = ($parent->params->get('subWidth', '')) ? 'width: '.$parent->params->get('subWidth', ''). ' ' : '';
			$class = ($parent->params->get('class', '')) ? $parent->params->get('class', '') . ' ' : '';
			
			$html = '<div class="st-mega-menu-row '.$class.' " style=" '.$style.' "><div class="row-fluid clearfix">';
			
			$colWidths = explode("\n", $parent->params->get('colWidth', ''));
			$colClasses = explode("\n", $parent->params->get('colClass', ''));
			
			foreach ($cols as $index => $col) {
				$colWidth = (isset($colWidths[$index]) && $colWidths[$index] != '') ? ' width: '.$colWidths[$index] : '';
				$colClass = (isset($colClasses[$index]) && $colClasses[$index] != '') ? $colClasses[$index] : '';
				
				$html .= '<div class="st-mega-menu-col '.$colClass.' " style="'.$colWidth.'"><ul>'.$col.'</ul></div>';
			}
			
			$html .= '</div></div>';
			return $html;
		}
	}

	public static function getModule($id) 
	{
		$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$groups		= implode(',', $user->getAuthorisedViewLevels());
		$lang 		= JFactory::getLanguage()->getTag();
		$clientId 	= (int) $app->getClientId();
		
		$db	= JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, title, module, position, content, showtitle, params');
		$query->from('#__modules AS m');
		$query->where('m.published = 1');
		$query->where('m.id = ' . $id);
		
		$date = JFactory::getDate();
		$now = $date->toSql();
		$nullDate = $db->getNullDate();
		$query->where('(m.publish_up = '.$db->Quote($nullDate).' OR m.publish_up <= '.$db->Quote($now).')');
		$query->where('(m.publish_down = '.$db->Quote($nullDate).' OR m.publish_down >= '.$db->Quote($now).')');

		$query->where('m.access IN ('.$groups.')');
		$query->where('m.client_id = '. $clientId);
		
		// Filter by language
		if ($app->isSite() && $app->getLanguageFilter()) {
			$query->where('m.language IN (' . $db->Quote($lang) . ',' . $db->Quote('*') . ')');
		}

		$query->order('position, ordering');

		// Set the query
		$db->setQuery($query);

		$module = $db->loadObject();
		
		if (!$module) return null;
		
		$file				= $module->module;
		$custom				= substr($file, 0, 4) == 'mod_' ?  0 : 1;
		$module->user		= $custom;
		$module->name		= $custom ? $module->title : substr($file, 4);
		$module->style		= null;
		$module->position	= strtolower($module->position);
		
		return $module;
	}
}
?>